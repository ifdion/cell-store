<?php



/* front end registration
---------------------------------------------------------------
*/
add_action('wp_ajax_nopriv_frontend_registration', 'process_frontend_registration');

function process_frontend_registration() {
	if ( empty($_POST) || !wp_verify_nonce($_POST['registration_nonce'],'frontend_registration') ) {
		echo 'Sorry, your nonce did not verify.';
		die();
	} else {
		// validate data
		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$return = $_POST['_wp_http_referer'];

		if(preg_match('/^[a-z0-9_-]{3,15}$/i', $username) == 0){
			$error['type'] = 'error';
			$error['message'] = __('Username not valid.', 'cell-store');
			ajax_response($error,$return);

		} elseif(!is_email($email))	{
			$error['type'] = 'error';
			$error['message'] = __('Email not valid.', 'cell-store');
			ajax_response($error,$return);

		} elseif($password == "") {
			$error['type'] = 'error';
			$error['message'] = __('Password empty.', 'cell-store');
			ajax_response($error,$return);

		} elseif( username_exists($username) || email_exists($email) ){
			$error['type'] = 'error';
			$error['message'] = __('Username or email already registered.', 'cell-store');
			ajax_response($error,$return);

		} else {
			$user_registration_data = array(
				'user_login' => $username,
				'user_pass' => $password,
				'user_email' => $email,
				'role' => get_option('default_role')
			);
			$user_id = wp_insert_user( $user_registration_data );
			$notifcation = wp_new_user_notification($user_id, $password);
			$login = wp_signon( array( 'user_login' => $username, 'user_password' => $password, 'remember' => false ), false );

			$return = get_bloginfo('url');

			// registration result
			$success['type'] = 'success';
			$success['message'] = __('Registration Success.', 'cell-store');
			ajax_response($success,$return);
			
		}		
		die();
	}
}

/* custom front end login
---------------------------------------------------------------
*/
add_action('wp_ajax_nopriv_frontend_login', 'process_frontend_login');

function process_frontend_login() {
	if ( empty($_POST) || !wp_verify_nonce($_POST['login_nonce'],'frontend_login') ) {
		echo 'Sorry, your nonce did not verify.';
		die();
	} else {
		// validate data
		$username = $_POST['username'];
		$password = $_POST['password'];
		$return = $_POST['_wp_http_referer'];

		if ($username == "" || $password == "") {
			$result['type'] = 'error';
			$result['message'] = __('Field empty.', 'cell-store');
			ajax_response($result,$return);
		} elseif (email_exists($username)) {
			$user = get_user_by('email', $username);
			$login = wp_signon( array( 'user_login' => $user->user_login, 'user_password' => $password, 'remember' => false ), false );
			if (is_wp_error($login)) {
				$result['type'] = 'error';
				$result['message'] = __('Invalid Password.', 'cell-store');
				ajax_response($result,$return);
			} else {
				$success['type'] = 'success';
				$success['message'] = __('Login Success.', 'cell-store');
				ajax_response($success,$return);
			}
		} elseif (username_exists($username)) {
			$login = wp_signon( array( 'user_login' => $username, 'user_password' => $password, 'remember' => false ), false );
			if (is_wp_error($login)) {
				$result['type'] = 'error';
				$result['message'] = __('Invalid Password.', 'cell-store');
				ajax_response($result,$return);
			} else {
				$success['type'] = 'success';
				$success['message'] = __('Login Success.', 'cell-store');
				ajax_response($success,$return);
			}
		} else {
			$success['type'] = 'error';
			$success['message'] = __('Username or Email does not exist.', 'cell-store');
			ajax_response($success,$return);

		}
		die();
	}
}


/* custom front end forgot password
---------------------------------------------------------------
*/
add_action('wp_ajax_nopriv_frontend_forgot_password', 'process_frontend_forgot_password');

function process_frontend_forgot_password() {
	if ( empty($_POST) || !wp_verify_nonce($_POST['forgot_password_nonce'],'frontend_forgot_password') ) {
		echo 'Sorry, your nonce did not verify.';
		die();
	} else {
		// validate data
		$username = $_POST['username'];
		$return = $_POST['_wp_http_referer'];
		if (!$username) {
			$result['type'] = 'error';
			$result['message'] = __('Field empty.', 'cell-store');
			ajax_response($result,$return);
			die();
		} else {
			if (username_exists($username)) {
				$user = get_user_by('login', $username);
			} elseif(email_exists($username)) {
				$user = get_user_by('email', $username);
			} else {
				$result['type'] = 'error';
				$result['message'] = __('Username or Email does not exist.', 'cell-store');
				ajax_response($result,$return);
				die();
			}

			if ($user) {
				$user_login = $user->user_login;
				$user_email = $user->user_email;
				global $wpdb;

				$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
				if(empty($key)) {
					$key = wp_generate_password(20, false);
					$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));

				}

				$return_array = explode('?', $return);
				$base_url = $return_array[0];
				$activation_link = get_bloginfo('url').$base_url.'?reset-password=1&key='.$key.'&login='.$user_login;

				$mail_title = sprintf(__('Reset Password Activation Key at %s', 'cell-store'), get_bloginfo('name'));

				$message = sprintf(__('
					It likes like you (hopefully) want to reset your password for your %1$s account.
					To reset your password, visit the following address, otherwise just ignore this email and nothing will happen.
					%2$s
					Have a nice day', 'cell-store'), get_bloginfo('name'), $activation_link );

				if (function_exists('cell_email')) {
					cell_email($user_email, $mail_title, wpautop($message));
				} else {
					wp_mail($user_email, $mail_title, $message);
				}

				$result['type'] = 'success';
				$result['message'] = __('Activation email sent', 'cell-store');
				ajax_response($result,$return);
				die();
			}
		}
	}
}

/* custom front end forgot password
---------------------------------------------------------------
*/
add_action('wp_ajax_nopriv_frontend_reset_password', 'process_frontend_reset_password');

function process_frontend_reset_password() {
	if ( empty($_POST) || !wp_verify_nonce($_POST['reset_password_nonce'],'frontend_reset_password') ) {
		echo 'Sorry, your nonce did not verify.';
		die();
	} else {
		// validate data
		$return = $_POST['_wp_http_referer'];
		$reset_key = $_POST['key'];
		$user_login = $_POST['login'];
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];

		global $wpdb;
		$user_data = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $reset_key, $user_login));

		if(!$user_data){
			$result['type'] = 'error';
			$result['message'] = __('User not found.', 'cell-store');
			ajax_response($result,$return);
		} elseif(!$reset_key) {
			$result['type'] = 'error';
			$result['message'] = __('Activation key not found.', 'cell-store');
			ajax_response($result,$return);
		} else {
			if ($password1 &&($password1 != $password2)) {
				$result['type'] = 'error';
				$result['message'] = __('Input password is incorrect.', 'cell-store');
				ajax_response($result,$return);
			} else {
				wp_set_password($password1, $user_data->ID);

				$return_array = explode('?', $return);
				$base_url = $return_array[0];
				
				$result['type'] = 'success';
				$result['message'] = __('Password reset.', 'cell-store');
				ajax_response($result,$base_url);
			}
		}
		die();
	}
}

/* front end profile edit 
---------------------------------------------------------------
*/
add_action('wp_ajax_edit-author', 'process_edit_author');

function process_edit_author() {

	global $current_user;
	if ( empty($_POST) || !wp_verify_nonce($_POST['edit-author_nonce'],'edit-author') ) {
		echo 'You targeted the right function, but sorry, your nonce did not verify.';
		die();
	} else {

		// validate data
		$userdata['first_name'] = $_POST['first-name'];
		$userdata['last_name'] = $_POST['last-name'];
		$userdata['ID'] = $current_user->ID;
		$email = $_POST['email'];
		$telephone = $_POST['telephone'];
		$company = $_POST['company'];
		$address = $_POST['address'];
		$country = $_POST['country'];
		$province = $_POST['province'];
		$city = $_POST['city'];
		$district = $_POST['district'];
		$postcode = $_POST['postcode'];

		$have_shipping = $_POST['have-shipping'];

		$shipping['first-name'] = $_POST['shipping-first-name'];
		$shipping['last-name'] = $_POST['shipping-last-name'];
		$shipping['email'] = $_POST['shipping-email'];
		$shipping['telephone'] = $_POST['shipping-telephone'];
		$shipping['company'] = $_POST['shipping-company'];
		$shipping['address'] = $_POST['shipping-address'];
		$shipping['country'] = $_POST['shipping-country'];
		$shipping['province'] = $_POST['shipping-province'];
		$shipping['city'] = $_POST['shipping-city'];
		$shipping['district'] = $_POST['shipping-district'];
		$shipping['postcode'] = $_POST['shipping-postcode'];

		$return = $_POST['_wp_http_referer'];
	
		// set the name
		$userdata['display_name'] = $_POST['first-name'];

		// check th email first
		if (is_email() || !email_exists($email) || $current_user->user_email == $email) {
			$userdata['user_email'] = $email;
		} else {
			$result['type'] = 'error';
			$result['message'] = __('Email is invalid', 'cell-store');
			ajax_response($result);
		}

		// set the password
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		if ($password1 || $password2) {
			if ($password1 && $password2 && $password1 == $password2) {
				$userdata['user_pass'] = $password1;
				$result['type'] = 'success';
				$result['message'] = __('Password replaced.', 'cell-store');
				ajax_response($result);
			} else {
				$result['type'] = 'error';
				$result['message'] = __('Password incorrect.', 'cell-store');
				ajax_response($result);
			}
		}

		wp_update_user( $userdata );

		// set some user meta
		update_user_meta( $current_user->ID, 'telephone', $telephone );
		update_user_meta( $current_user->ID, 'company', $company );
		update_user_meta( $current_user->ID, 'address', $address );
		update_user_meta( $current_user->ID, 'postcode', $postcode );
		if ($country != 'intro') {
			update_user_meta( $current_user->ID, 'country', $country );
		}
		if ($province != 'intro') {
			update_user_meta( $current_user->ID, 'province', $province );
		}
		if ($city != 'intro') {
			update_user_meta( $current_user->ID, 'city', $city );
		}
		if ($district != 'intro') {
			update_user_meta( $current_user->ID, 'district', $district );
		}

		if ($have_shipping) {
			update_user_meta($current_user->ID, 'shipping-first-name', $shipping['first-name']);
			update_user_meta($current_user->ID, 'shipping-last-name', $shipping['last-name']);
			update_user_meta($current_user->ID, 'shipping-email', $shipping['email']);
			update_user_meta($current_user->ID, 'shipping-telephone', $shipping['telephone']);
			update_user_meta($current_user->ID, 'shipping-company', $shipping['company']);
			update_user_meta($current_user->ID, 'shipping-address', $shipping['address']);
			update_user_meta($current_user->ID, 'shipping-country', $shipping['country']);
			update_user_meta($current_user->ID, 'shipping-province', $shipping['province']);
			update_user_meta($current_user->ID, 'shipping-city', $shipping['city']);
			update_user_meta($current_user->ID, 'shipping-district', $shipping['district']);
			update_user_meta($current_user->ID, 'shipping-postcode', $shipping['postcode']);
		}
		update_user_meta( $current_user->ID, 'have-shipping', $have_shipping );

		$result['type'] = 'success';
		$result['message'] = __('Profile updated.', 'cell-store');
		ajax_response($result, $return);
		die();
	}
}

?>