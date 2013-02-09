<?php

/* add product to cart
---------------------------------------------------------------
*/
add_action('wp_ajax_nopriv_delete_cart_item', 'process_delete_cart_item');
add_action('wp_ajax_delete_cart_item', 'process_delete_cart_item');

function process_delete_cart_item() {
	if ( empty($_GET) || !wp_verify_nonce($_GET['_wpnonce'],'delete_cart_item') ) {
		echo 'Sorry, your nonce did not verify.';
		die();
	} else {
		// validate data
		$cart_item = $_GET['cart-item'];
		$return = get_permalink( get_page_by_path( 'shopping-cart' ) );
		unset($_SESSION['shopping-cart']['items'][$cart_item]);
		$result['type'] = 'success';
		$result['message'] = __('Item removed from cart', 'cell-store');
		ajax_response($result,$return);


		die();
	}
}

/* update shopping cart 
---------------------------------------------------------------
*/
add_action('wp_ajax_nopriv_update_shopping_cart', 'process_update_shopping_cart');
add_action('wp_ajax_update_shopping_cart', 'process_update_shopping_cart');

function process_update_shopping_cart() {
	if ( empty($_POST) || !wp_verify_nonce($_POST['update_shopping_cart_nonce'],'update_shopping_cart') ) {
		echo 'Sorry, your nonce did not verify.';
		die();
	} else {
		// validate data
		if (isset($_POST['add-coupon'])) {
			$coupon_code = $_POST['add-coupon'];
		}
		$return = get_permalink( get_page_by_path( 'checkout' ) );

		if (isset($coupon_code)) {
			$coupon_result = process_coupon($coupon_code);
			ajax_response($coupon_result);
		} else{
			$result['type'] = 'success';
			$result['message'] = __('Shopping Cart Updated', 'cell-store');
		}


		ajax_response($result,$return);
		die();
	}
}

/* checkout 
---------------------------------------------------------------
*/

add_action('wp_ajax_nopriv_checkout', 'process_checkout');
add_action('wp_ajax_checkout', 'process_checkout');

function process_checkout() {
	if ( empty($_POST) || !wp_verify_nonce($_POST['checkout_nonce'],'checkout') ) {
		echo 'Sorry, your nonce did not verify.';
		die();
	} else {

		$shipping['first-name'] = $_POST['first-name'];
		$shipping['last-name'] = $_POST['last-name'];
		$shipping['email'] = $_POST['email'];
		$shipping['telephone'] = $_POST['telephone'];
		$shipping['company'] = $_POST['company'];
		$shipping['address'] = $_POST['address'];
		$shipping['country'] = $_POST['country'];
		if ($_POST['province'] != 'intro') {
			$shipping['province'] = $_POST['province'];
		}
		if ($_POST['city'] != 'intro') {
			$shipping['city'] = $_POST['city'];
		}
		if ($_POST['district'] != 'intro') {
			$shipping['district'] = $_POST['district'];
		}
		$shipping['postcode'] = $_POST['postcode'];
		if (isset($_POST['save-shipping-address'])) {
			$save_as_shipping = $_POST['save-shipping-address'];
		}
		
		$return = get_permalink( get_page_by_path( 'payment-option' ) );


		// count total weight
		$total_weight = 0;
		foreach ($_SESSION['shopping-cart']['items'] as $key => $value) {
			$total_weight += ($value['weight'] * $value['quantity']);
		}

		// count total item payment
		$total_price = 0;
		foreach ($_SESSION['shopping-cart']['items'] as $items) {
			$total_price += ($items['quantity'] * $items['price']);
			if (isset($_SESSION['shopping-cart']['coupon']['discount-value'])) {
				$discount_value = str_replace('%', '', $_SESSION['shopping-cart']['coupon']['discount-value']);
				$total_price = $total_price - ($total_price * $discount_value / 100 );
			}
		}

		// add totals to payment session
		$payment['total-weight'] = ceil($total_weight);
		$payment['total-item-cost'] = $total_price;
		$_SESSION['shopping-cart']['payment'] = $payment;

		// add shipping address
		$_SESSION['shopping-cart']['customer']['shipping'] = $shipping;

		if (is_user_logged_in()) {
			global $current_user;
			$user_data = get_user_meta($current_user->ID);
			if ($user_data['first_name'][0]) {
				$first_name = $user_data['first_name'][0] ;
			} else {
				$first_name = $current_user->display_name;
			}
			$billing['first-name'] = $first_name;
			$billing['last-name'] = $user_data['last_name'][0];
			$billing['email'] = $current_user->user_email;
			$billing['telephone'] = $user_data['telephone'][0];
			$billing['company'] = $user_data['company'][0];
			$billing['address'] = $user_data['address'][0];
			$billing['country'] = $user_data['country'][0];
			$billing['province'] = $user_data['province'][0];
			$billing['city'] = $user_data['city'][0];
			$billing['district'] = $user_data['district'][0];
			$billing['postcode'] = $user_data['postcode'][0];

			// if is a logged in user get billing data from db
			$_SESSION['shopping-cart']['customer']['billing'] = $billing;

			// and save the shipping address if necessary
			if (isset($save_as_shipping)) {
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
		} else {

			// if is not a logged in user save shipping as billing also
			$_SESSION['shopping-cart']['customer']['billing'] = $shipping;

			// and register the user data
			if ($_POST['username'] && $_POST['password']) {
				if( username_exists($_POST['username']) || email_exists($_POST['email']) ){
					$error['type'] = 'error';
					$error['message'] = __('Username or email already registered', 'cell-store');
					ajax_response($error);
				} else {
					$user_registration_data = array(
						'user_login' => $_POST['username'],
						'user_pass' => $_POST['password'],
						'user_email' => $_POST['email'],
						'role' => 'author'
					);
					$user_id = wp_insert_user( $user_registration_data );
					$notifcation = wp_new_user_notification($user_id, $_POST['password']);
					$login = wp_signon( array( 'user_login' => $_POST['username'], 'user_password' => $_POST['password'], 'remember' => false ), false );


					add_user_meta($user_id, 'first-name', $shipping['first-name']);
					add_user_meta($user_id, 'last-name', $shipping['last-name']);
					add_user_meta($user_id, 'telephone', $shipping['telephone']);
					add_user_meta($user_id, 'company', $shipping['company']);
					add_user_meta($user_id, 'address', $shipping['address']);
					add_user_meta($user_id, 'country', $shipping['country']);
					add_user_meta($user_id, 'province', $shipping['province']);
					add_user_meta($user_id, 'city', $shipping['city']);
					add_user_meta($user_id, 'district', $shipping['district']);
					add_user_meta($user_id, 'postcode', $shipping['postcode']);

					add_user_meta($user_id, 'shipping-first-name', $shipping['first-name']);
					add_user_meta($user_id, 'shipping-last-name', $shipping['last-name']);
					add_user_meta($user_id, 'shipping-email', $shipping['email']);
					add_user_meta($user_id, 'shipping-telephone', $shipping['telephone']);
					add_user_meta($user_id, 'shipping-company', $shipping['company']);
					add_user_meta($user_id, 'shipping-address', $shipping['address']);
					add_user_meta($user_id, 'shipping-country', $shipping['country']);
					add_user_meta($user_id, 'shipping-province', $shipping['province']);
					add_user_meta($user_id, 'shipping-city', $shipping['city']);
					add_user_meta($user_id, 'shipping-district', $shipping['district']);
					add_user_meta($user_id, 'shipping-postcode', $shipping['postcode']);

					// registration result
					$success['type'] = 'success';
					$success['message'] = __('Registration Success', 'cell-store');
					ajax_response($success);
				}
			}
		}

		// check shipping destination

		// shipping to district ?
		if ( isset($shipping['district']) && is_numeric($shipping['district'])) {
			$_SESSION['shopping-cart']['payment']['shipping-destination-id'] = $shipping['district'];
			$shipping_to = $shipping['district'];
		} elseif (isset($shipping['district'])) {
			$shipping_destination = get_page_by_title($shipping['district'], 'OBJECT', 'shipping-destination');
			if (isset($shipping_destination)) {
				$_SESSION['shopping-cart']['payment']['shipping-destination-id'] = $shipping_destination->ID;
				$shipping_to = $shipping_destination->post_title;
			}
		}

		// shipping to city
		if (!isset($shipping_to) && isset($shipping['city']) && is_numeric($shipping['city'])) {
			$_SESSION['shopping-cart']['payment']['shipping-destination-id'] = $shipping['city'];
			$shipping_to = $shipping['city'];
		} elseif (!isset($shipping_to) && isset($shipping['city'])) {
			$shipping_destination = get_page_by_title($shipping['city'], 'OBJECT', 'shipping-destination');
			if ($shipping_destination) {
				$_SESSION['shopping-cart']['payment']['shipping-destination-id'] = $shipping_destination->ID;
				$shipping_to = $shipping_destination->post_title;
			}
		}

		// shipping to province
		if (!isset($shipping_to) && isset($shipping['province']) && is_numeric($shipping['province'])) {
			$_SESSION['shopping-cart']['payment']['shipping-destination-id'] = $shipping['province'];
			$shipping_to = $shipping['province'];
		} elseif (!isset($shipping_to) && is_numeric($shipping['province'])) {
			$shipping_destination = get_page_by_title($shipping['province'], 'OBJECT', 'shipping-destination');
			if ($shipping_destination) {
				$_SESSION['shopping-cart']['payment']['shipping-destination-id'] = $shipping_destination->ID;
				$shipping_to = $shipping_destination->post_title;
			}
		}

		// shipping to country
		if (!isset($shipping_to) && isset($shipping['country']) && is_numeric($shipping['country'])) {
			$_SESSION['shopping-cart']['payment']['shipping-destination-id'] = $shipping['country'];
			$shipping_to = $shipping['country'];
		} elseif (!isset($shipping_to) && is_numeric($shipping['country'])) {
			$shipping_destination = get_page_by_title($shipping['country'], 'OBJECT', 'shipping-destination');
			if ($shipping_destination) {
				$_SESSION['shopping-cart']['payment']['shipping-destination-id'] = $shipping_destination->ID;
				$shipping_to = $shipping_destination->post_title;
			}
		}

		unset($_SESSION['shopping-cart']['payment']['shipping-option']);
		unset($_SESSION['shopping-cart']['payment']['shipping-rate']);


		// check shipping destination


		if ($shipping_to) {
			$result['type'] = 'success';
			$result['message'] = __('Shipping destination confirmed', 'cell-store');
			ajax_response($result,$return);
		} else {
			$result['type'] = 'error';
			$result['message'] = __('Shipping destination not confirmed', 'cell-store');
			ajax_response($result,$return);
		}

		die();
	}
}

add_action('wp_ajax_nopriv_payment_option', 'process_payment_option');
add_action('wp_ajax_payment_option', 'process_payment_option');

function process_payment_option() {
	if ( empty($_POST) || !wp_verify_nonce($_POST['payment_option_nonce'],'payment_option') ) {
		echo 'Sorry, your nonce did not verify.';
		die();
	} else {

		$shipping_option = $_POST['shipping-option'];
		$payment_method = $_POST['payment-method'];
		if (isset($_POST['term-condition'])) {
			$term_condition = $_POST['term-condition'];
		}
		

		if (isset($term_condition)) {

			$shipping_array = explode('-', $shipping_option);
			$shipping_service = $shipping_array[0];
			$shipping_rate = $shipping_array[1];

			$_SESSION['shopping-cart']['payment']['method'] = $payment_method;
			$_SESSION['shopping-cart']['payment']['shipping-option'] = $shipping_service;
			$_SESSION['shopping-cart']['payment']['shipping-rate'] = $shipping_rate;

			$return = get_permalink(get_page_by_path('order-confirmation'));
			$result['type'] = 'success';
			$result['message'] = __('Payment and shipping confirmed', 'cell-store');
			ajax_response($result,$return);

			
		} else {
			$return = $_POST['_wp_http_referer'];
			$error['type'] = 'error';
			$error['message'] = __('You have to agree with the term and condition', 'cell-store');
			ajax_response($error,$return);
		}
		die();
	}
}

add_action('wp_ajax_nopriv_purchase_confirmation', 'process_purchase_confirmation');
add_action('wp_ajax_purchase_confirmation', 'process_purchase_confirmation');

function process_purchase_confirmation() {
	if ( empty($_POST) || !wp_verify_nonce($_POST['purchase_confirmation_nonce'],'purchase_confirmation') ) {
		echo 'Sorry, your nonce did not verify.';
		die();
	} else {

		$billing = $_SESSION['shopping-cart']['customer']['billing'];
		$shipping = $_SESSION['shopping-cart']['customer']['shipping'];
		$items = $_SESSION['shopping-cart']['items'];
		if (isset($_SESSION['shopping-cart']['coupon'])) {
			$coupon = $_SESSION['shopping-cart']['coupon'];
		}
		
		$payment = $_SESSION['shopping-cart']['payment'];

		$title = 'Transaction by '.$billing['first-name'].' '.$billing['last-name'].' at '. date('H:i:s, d F Y');
		$slug = time('u').wp_generate_password('5', false, false);
		$slug = strtolower($slug);

		$post = array(
			'post_name'      => $slug,
			'post_status'    => 'publish',
			'post_title'     => $title,
			'post_type'      => 'transaction'
		);  
		$new_transaction = wp_insert_post($post);

		if($new_transaction){

			add_post_meta($new_transaction, '_billing', $_SESSION['shopping-cart']['customer']['billing'], true);
			add_post_meta($new_transaction, '_shipping', $_SESSION['shopping-cart']['customer']['shipping'], true);
			add_post_meta($new_transaction, '_items', $_SESSION['shopping-cart']['items'], true);
			add_post_meta($new_transaction, '_payment', $_SESSION['shopping-cart']['payment'], true);
			if (isset($_SESSION['shopping-cart']['coupon'])) {
				add_post_meta($new_transaction, '_coupon', $_SESSION['shopping-cart']['coupon'], true);
				add_post_meta($new_transaction, '_payment_coupon', $_SESSION['shopping-cart']['coupon']['ID'], true);
			}
			
			add_post_meta($new_transaction, '_transaction_status', 'pending', true);
			add_post_meta($new_transaction, '_payment_method', $_SESSION['shopping-cart']['payment']['method'], true);
			add_post_meta($new_transaction, '_payment_shipping', $_SESSION['shopping-cart']['payment']['shipping-option'], true);
			add_post_meta($new_transaction, '_payment_weight', $_SESSION['shopping-cart']['payment']['total-weight'], true);
			add_post_meta($new_transaction, '_payment_item_cost', $_SESSION['shopping-cart']['payment']['total-item-cost'], true);

			// send email to billing address
			$mail_title = __('Transaction Confirmation', 'cell-store');
			$message = sprintf( __('<p> Dear %s </p>', 'cell-store'), $_SESSION['shopping-cart']['customer']['billing']['first-name'] );
			$message .= sprintf( __('<p> Thank you for shopping at %s </p>', 'cell-store'), get_bloginfo('name') );
			$message .= sprintf( __('<p> Your order : %s  has been received </p>', 'cell-store'), $slug );
			$message .= sprintf( __('<p> To check the status of your order at any time, you can log into your account at %s </p>', 'cell-store'), get_bloginfo('url') );
			$message .= sprintf( __('<p> If you have any questions, please contact us at %1$s or SMS us at +6287824039090. Please make sure to reference your order number : %2$s </p>', 'cell-store'), get_bloginfo('admin_email'), $slug );
			$message .= sprintf( __('<p> Once again, thanks for shopping with %s </p>', 'cell-store'), get_bloginfo('name') );
			$message .= sprintf( __('<p> Sincerely, <br/> %s  Team </p>', 'cell-store'), get_bloginfo('name') );

			if (function_exists('cell_email')) {
				cell_email($_SESSION['shopping-cart']['customer']['billing']['email'], $mail_title, $message);
			} else {
				wp_mail($_SESSION['shopping-cart']['customer']['billing']['email'], $mail_title, strip_tags($message));
			}

			// send mail to admin
			$mail_title = __('New Transaction', 'cell-store');
			$message = sprintf( __('New transaction has been made at %s ', 'cell-store'), get_bloginfo('name') );

			if (function_exists('cell_email')) {
				cell_email(get_bloginfo('admin_email'), $mail_title, wpautop($message));
			} else {
				wp_mail(get_bloginfo('admin_email'), $mail_title, $message);
			}

			if (is_user_logged_in()) {
				global $current_user;
				$customer = $current_user->ID;
				update_post_meta($new_transaction, '_customer_id', $customer);
			} else {
				$customer = 0 ;
			}
			$time = current_time('mysql');

			if (isset($coupon)) {
				$update_coupon_usage = $coupon['usage'] + 1;
				update_post_meta($coupon['ID'], '_coupon_usage', $update_coupon_usage);

				$transaction_url = admin_url('post.php?post='.$new_transaction.'&action=edit');
				$billing_name = $billing['first-name'].' '.$billing['last-name'];
				
				$comment = array(
					'comment_post_ID' => $coupon['ID'],
					'comment_author' => $billing['first-name'].' '.$billing['last-name'],
					'comment_author_email' => $billing['email'],
					'comment_content' => sprintf(__('Coupon used once for <a href=" %1$s"> a transaction </a> by %2$s', 'cell-store'), $transaction_url, $billing_name),
					'user_id' => $customer,
					'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
					'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
					'comment_date' => $time,
					'comment_approved' => 1,
				);
				wp_insert_comment($comment);
			}

			// reduce the stock if stock managed
			foreach ($_SESSION['shopping-cart']['items'] as $item) {
				if ($item['stock-manage']) {
					$product_meta =get_post_meta($item['ID']);
					if (isset($product_meta['_use_variations'][0])) {
						$variations = unserialize($product_meta['_variant'][0]);
						foreach ($variations as $key =>$variation) {
							if ($variation['title'] == $item['option']) {
								$variations[$key]['stock'] = $variation['stock'] - $item['quantity'];
								update_post_meta($item['ID'], '_variant', $variations);
							}
						}
					} else {
						$new_stock = $product_meta['_stock'][0] - $item['quantity'];
						update_post_meta($item['ID'], '_stock', $new_stock);
					}
				}
			}
		}

		unset($_SESSION['shopping-cart']['items']);
		unset($_SESSION['shopping-cart']['coupon']);
		unset($_SESSION['shopping-cart']['payment']);

		$_SESSION['shopping-cart']['last-transaction'] = $slug;

		$return = get_permalink(get_page_by_path('thank-you'));
		$result['type'] = 'success';
		$result['message'] = __('Purchase has been made', 'cell-store');
		ajax_response($result,$return);

		die();
	}
}

/* payment confirmation 
---------------------------------------------------------------
*/

add_action('wp_ajax_nopriv_payment_confirm', 'process_payment_confirmation');
add_action('wp_ajax_payment_confirm', 'process_payment_confirmation');

function process_payment_confirmation() {
	if ( empty($_POST) || !wp_verify_nonce($_POST['payment_confirm_nonce'],'payment_confirm') ) {
		echo 'Sorry, your nonce did not verify.';
		die();
	} else {

		$name = $_POST['name'];
		$email = $_POST['email'];
		$transaction_slug = $_POST['transaction-slug'];
		$date = $_POST['date'];
		$method = $_POST['method'];
		$other_method = $_POST['other-method'];
		$account_holder = $_POST['account-holder'];
		$return = $_POST['_wp_http_referer'];
		$mtcn = $_POST['mtcn-number'];

		if ($other_method) {
			$method = $other_method;
		}

		if ($mtcn) {
			$mtcn_code = '. MTCN code : '.$mtcn;
		}
		
		$transaction_id = get_id_by_slug($transaction_slug,'transaction');

		if ($transaction_id) {

			$user = get_user_by('email', $email);
			$time = current_time('mysql');

			$comment = array(
				'comment_post_ID' => $transaction_id,
				'comment_author' => $name,
				'comment_author_email' => $email,
				'comment_content' => 'Transaction payment confirmed by '.$name.' at '.$date.' by '. $method .' on behalf of '. $account_holder. $mtcn_cod,
				'user_id' => $user->ID,
				'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
				'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
				'comment_date' => $time,
				'comment_approved' => 1,
			);
			$comment_result = wp_insert_comment($comment);

			// send email to confirmer
			$mail_title = __('Payment Confirmation', 'cell-store');
			$message = __('Your confirmation has been sent', 'cell-store');

			if (function_exists('cell_email')) {
				cell_email($email, $mail_title, wpautop($message));
			} else {
				wp_mail($user_email, $mail_title, $message);
			}

			// send mail to admin
			$mail_title = __('New Payment Confirmation', 'cell-store');
			$message = 'Transaction payment confirmed by '.$name.' at '.$date.' by '. $method .' on behalf of '. $account_holder;

			if (function_exists('cell_email')) {
				cell_email(get_bloginfo('admin_email'), $mail_title, wpautop($message));
			} else {
				wp_mail(get_bloginfo('admin_email'), $mail_title, $message);
			}
		}

		$result['type'] = 'success';
		$result['message'] = __('Confirmation has been send', 'cell-store');
		ajax_response($result,$return);

	}
}
?>