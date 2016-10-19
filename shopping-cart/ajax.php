<?php

/* add product to cart
---------------------------------------------------------------
*/

add_action('wp_ajax_nopriv_delete_cart_item', 'process_delete_cart_item');
add_action('wp_ajax_delete_cart_item', 'process_delete_cart_item');

function process_delete_cart_item() {
	$cell_store_pages = get_option( 'cell_store_pages' );
	$shopping_cart_page = $cell_store_pages['shopping-cart'];

	if ( empty($_GET) || !wp_verify_nonce($_GET['_wpnonce'],'delete_cart_item') ) {
		echo 'Sorry, your nonce did not verify.';
		die();
	} else {
		// validate data
		$cart_item = $_GET['cart-item'];
		$return = get_permalink( get_page_by_path( $shopping_cart_page ) );
		unset($_SESSION['shopping-cart']['items'][$cart_item]);

		// remove payment session
		unset($_SESSION['shopping-cart']['payment']['total-weight']);
		unset($_SESSION['shopping-cart']['payment']['total-item-cost']);
		unset($_SESSION['shopping-cart']['payment']['shipping-destination-id']);
		unset($_SESSION['shopping-cart']['payment']['method']);
		unset($_SESSION['shopping-cart']['payment']['shipping-option']);
		unset($_SESSION['shopping-cart']['payment']['shipping-rate']);

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
	$cell_store_pages = get_option( 'cell_store_pages' );
	$checkout_page = $cell_store_pages['checkout'];

	if ( empty($_POST) || !wp_verify_nonce($_POST['update_shopping_cart_nonce'],'update_shopping_cart') ) {
		echo 'Sorry, your nonce did not verify.';
		die();
	} else {
		// validate data
		if (isset($_POST['add-coupon']) && $_POST['add-coupon'] != '') {
			$coupon_code = $_POST['add-coupon'];
			$coupon_result = process_coupon($coupon_code);
			ajax_response($coupon_result);
		}

		$result['type'] = 'success';
		$result['message'] = __('Shopping Cart Updated', 'cell-store');

		$return = get_permalink( get_page_by_path( $checkout_page ) );
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

	$cell_store_pages = get_option( 'cell_store_pages' );
	$payment_option_page = $cell_store_pages['payment-option'];

	if ( empty($_POST) || !wp_verify_nonce($_POST['checkout_nonce'],'checkout') ) {
		echo 'Sorry, your nonce did not verify.';
		die();
	} else {

		// setup return address
		$return_error = $_POST['_wp_http_referer'];
		$return_success = get_permalink( get_page_by_path( $payment_option_page ) );

		// cek for shipping detail field
		$shipping['first-name'] = $_POST['first-name'];
		$shipping['last-name'] = $_POST['last-name'];
		$shipping['email'] = $_POST['email'];
		$shipping['telephone'] = $_POST['telephone'];
		$shipping['company'] = $_POST['company'];
		$shipping['address'] = $_POST['address'];
		$shipping['postcode'] = $_POST['postcode'];

		$required_field = array('first-name','email','telephone','address');
		$missing_field = array();
		$missing_string = __( 'Missing shipping field : ', 'cell-store');

		foreach ($shipping as $shipping_key => $shipping_value) {
			if (in_array($shipping_key, $required_field) && $shipping_value == '') {
				$missing_field[] = $shipping_key;
				$shipping_title = ucfirst(str_replace('-', ' ', $shipping_key));
				$missing_string = $missing_string.' '.ucfirst($shipping_title).', ';
			}
		}

		if (count($missing_field) > 0) {
			$result['type'] = 'danger';
			$result['message'] = $missing_string;
			ajax_response($result,$return_error);
		}

		// cek for shipping destination
		if (isset($_POST['country'])) {
			$shipping['country'] = $_POST['country'];
		}
		if (isset($_POST['province'])) {
			$shipping['province'] = $_POST['province'];
		}
		if (isset($_POST['city'])) {
			$shipping['city'] = $_POST['city'];
		}
		if (isset($_POST['district'])) {
			$shipping['district'] = $_POST['district'];
		}
		if (isset($_POST['save-shipping-address'])) {
			$save_as_shipping = $_POST['save-shipping-address'];
		}

		// shipping to district ?
		if ( isset($shipping['district']) && is_numeric($shipping['district']) && $shipping['district'] != 0) {
			$shipping_to = $shipping['district'];
		}

		// shipping to city
		if (!isset($shipping_to) && isset($shipping['city']) && is_numeric($shipping['city']) && $shipping['city'] != 0) {
			$shipping_to = $shipping['city'];
		}

		// shipping to province
		if (!isset($shipping_to) && isset($shipping['province']) && is_numeric($shipping['province']) && $shipping['province'] != 0) {
			$shipping_to = $shipping['province'];
		}

		// shipping to country
		if (!isset($shipping_to) && isset($shipping['country']) && is_numeric($shipping['country']) && $shipping['country'] != 0) {
			$shipping_to = $shipping['country'];
		}

		// error shipping
		if(!isset($shipping_to)){
			$result['type'] = 'danger';
			$result['message'] = __('Shipping destination is out of our coverage. Please contact us for additional support.', 'cell-store');
			ajax_response($result, $return_error);
		}

		// count total weight
		$total_weight = 0;
		foreach ($_SESSION['shopping-cart']['items'] as $key => $value) {

			// get product data from database
			$product_meta = get_post_meta( $value['ID'] );
			// add details from database to product object
			$value['weight'] = 0;
			if (isset($product_meta['_weight'][0])) {
				$value['weight'] = $product_meta['_weight'][0];
			}

			$total_weight += ($value['weight'] * $value['quantity']);
		}

		// count total item payment
		$total_price = 0;
		foreach ($_SESSION['shopping-cart']['items'] as $items) {
			// get product data from database
			$product_meta = get_post_meta( $value['ID'] );
			// add details from database to product object
			$items['price'] = $product_meta['_price'][0];

			$total_price += ($items['quantity'] * $items['price']);
			if (isset($_SESSION['shopping-cart']['coupon']['discount-value'])) {
				$discount_value = str_replace('%', '', $_SESSION['shopping-cart']['coupon']['discount-value']);
				$total_price = $total_price - ($total_price * $discount_value / 100 );
			}
		}

		// add totals to payment session
		$payment['total-weight'] = ceil($total_weight);
		$payment['total-item-cost'] = $total_price;
		$payment['shipping-destination-id'] = $shipping_to;
		$_SESSION['shopping-cart']['payment'] = $payment;

		// add shipping address
		$_SESSION['shopping-cart']['customer']['shipping'] = $shipping;

		if (is_user_logged_in()) {
			global $current_user;
			$user_meta = get_user_meta($current_user->ID);
			if ($user_meta['first_name'][0]) {
				$first_name = $user_meta['first_name'][0] ;
			} else {
				$first_name = $current_user->display_name;
			}
			$billing['first-name'] = $first_name;
			$billing['last-name'] = $user_meta['last_name'][0];
			$billing['email'] = $current_user->user_email;
			if (isset($user_meta['telephone'][0])) {
				$billing['telephone'] = $user_meta['telephone'][0];
			} else {
				$billing['telephone'] = $shipping['telephone'];
			}
			if (isset($user_meta['company'][0])) {
				$billing['company'] = $user_meta['company'][0];
			} else {
				$billing['company'] = $shipping['company'];
			}
			if (isset($user_meta['address'][0])) {
				$billing['address'] = $user_meta['address'][0];
			} else {
				$billing['address'] = $shipping['address'];
			}
			if (isset($user_meta['country'][0])) {
				$billing['country'] = $user_meta['country'][0];
			} else {
				$billing['country'] = $shipping['country'];
			}
			if (isset($user_meta['province'][0])) {
				$billing['province'] = $user_meta['province'][0];
			} elseif(isset($shipping['province'])) {
				$billing['province'] = $shipping['province'];
			} else {
				$billing['province'] = '';
			}
			if (isset($user_meta['city'][0])) {
				$billing['city'] = $user_meta['city'][0];
			} elseif(isset($shipping['city'])) {
				$billing['city'] = $shipping['city'];
			} else {
				$billing['city'] = '';
			}
			if (isset($user_meta['district'][0])) {
				$billing['district'] = $user_meta['district'][0];
			} elseif(isset($shipping['district'])) {
				$billing['district'] = $shipping['district'];
			} else {
				$billing['district'] = '';
			}
			if (isset($user_meta['postcode'][0])) {
				$billing['postcode'] = $user_meta['postcode'][0];
			} else {
				$billing['postcode'] = $shipping['postcode'];
			}
			
			// if is a logged in user get billing data from db
			$_SESSION['shopping-cart']['customer']['billing'] = $billing;

			// and save the shipping address if necessary
			if (isset($save_as_shipping)) {
				update_user_meta($current_user->ID, 'have-shipping', 1);
				update_user_meta($current_user->ID, 'shipping-first-name', $shipping['first-name']);
				update_user_meta($current_user->ID, 'shipping-last-name', $shipping['last-name']);
				update_user_meta($current_user->ID, 'shipping-email', $shipping['email']);
				update_user_meta($current_user->ID, 'shipping-telephone', $shipping['telephone']);
				update_user_meta($current_user->ID, 'shipping-company', $shipping['company']);
				update_user_meta($current_user->ID, 'shipping-address', $shipping['address']);
				update_user_meta($current_user->ID, 'shipping-country', $shipping['country']);
				if (isset($shipping['province'])) {
					update_user_meta($current_user->ID, 'shipping-province', $shipping['province']);
				}
				if (isset($shipping['city'])) {
					update_user_meta($current_user->ID, 'shipping-city', $shipping['city']);
				}
				if (isset($shipping['district'])) {
					update_user_meta($current_user->ID, 'shipping-district', $shipping['district']);
				}
				if (isset($shipping['postcode'])) {
					update_user_meta($current_user->ID, 'shipping-postcode', $shipping['postcode']);
				}
			}
		} else {

			// if is not a logged in user save shipping as billing also
			$_SESSION['shopping-cart']['customer']['billing'] = $shipping;

			// and register the user data
			if ($_POST['username'] && $_POST['password']) {
				if( username_exists($_POST['username']) || email_exists($_POST['email']) ){
					$error['type'] = 'danger danger';
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
					add_user_meta($user_id, 'telephone', $shipping['telephone']);
					add_user_meta($user_id, 'address', $shipping['address']);
					add_user_meta($user_id, 'country', $shipping['country']);

					if (isset($shipping['last-name'])) {
						add_user_meta($user_id, 'last-name', $shipping['last-name']);
						add_user_meta($user_id, 'shipping-last-name', $shipping['last-name']);
					}
					if (isset($shipping['company'])) {
						add_user_meta($user_id, 'company', $shipping['company']);
						add_user_meta($user_id, 'shipping-company', $shipping['company']);
					}

					if (isset($shipping['province'])) {
						add_user_meta($user_id, 'province', $shipping['province']);
						add_user_meta($user_id, 'shipping-province', $shipping['province']);
					}
					if (isset($shipping['city'])) {
						add_user_meta($user_id, 'city', $shipping['city']);
						add_user_meta($user_id, 'shipping-city', $shipping['city']);
					}
					if (isset($shipping['district'])) {
						add_user_meta($user_id, 'district', $shipping['district']);
						add_user_meta($user_id, 'shipping-district', $shipping['district']);
					}
					if (isset($shipping['postcode'])) {
						add_user_meta($user_id, 'postcode', $shipping['postcode']);
						add_user_meta($user_id, 'shipping-postcode', $shipping['postcode']);
					}

					add_user_meta($user_id, 'shipping-first-name', $shipping['first-name']);
					add_user_meta($user_id, 'shipping-email', $shipping['email']);
					add_user_meta($user_id, 'shipping-telephone', $shipping['telephone']);
					add_user_meta($user_id, 'shipping-address', $shipping['address']);
					add_user_meta($user_id, 'shipping-country', $shipping['country']);

					// registration result
					$success['type'] = 'success';
					$success['message'] = __('Registration Success', 'cell-store');
					ajax_response($success);
				}
			}
		}

		unset($_SESSION['shopping-cart']['payment']['shipping-option']);
		unset($_SESSION['shopping-cart']['payment']['shipping-rate']);

		if (isset($shipping_to) && $shipping_to !='') {
			$result['type'] = 'success';
			$result['message'] = __('Shipping destination confirmed.', 'cell-store');
			ajax_response($result,$return_success);

		} else {
			$result['type'] = 'danger';
			$result['message'] = __('Shipping destination not confirmed.', 'cell-store');
			ajax_response($result,$return_error);
		}

		die();
	}
}

add_action('wp_ajax_nopriv_payment_option', 'process_payment_option');
add_action('wp_ajax_payment_option', 'process_payment_option');

function process_payment_option() {
	$cell_store_pages = get_option( 'cell_store_pages' );
	$order_confirmation_page = $cell_store_pages['order-confirmation'];

	// get store features
	$store_options = get_option('cell_store_features' );
	$currency_symbol = $store_options['currency'];
	$weight_unit = $store_options['weight-unit'];
	$exchange_rate = 1;
	if (isset($_SESSION['shopping-cart']['payment']['use-secondary-currency'])) {
		$currency_symbol = $store_options['secondary-currency'];
		$exchange_rate = $_SESSION['shopping-cart']['payment']['exchange-rate'];
	}

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

			if ($payment_method == 'paypal') {
				// check if current currency is not eligible currency in paypal
				if (! in_array($currency_symbol, array('AUD', 'BRL', 'CAD', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS', 'JPY', 'MYR', 'MXN', 'NOK', 'NZD', 'PHP', 'PLN', 'GBP', 'RUB', 'SGD', 'SEK', 'CHF', 'TWD', 'THB', 'TRY', 'USD')) ) {
					// switch to secondary
					$currency_symbol = cs_switch_currency();
					$additional_message = sprintf( __(' - Switched currency to %s ', 'cell-store'), $currency_symbol );
				}
			}

			$return = get_permalink(get_page_by_path($order_confirmation_page));
			$result['type'] = 'success';
			$result['message'] = __('Payment and shipping confirmed', 'cell-store');
			if (isset($additional_message)) {
				$result['message'] .= $additional_message;
			}
			
			ajax_response($result,$return);

			
		} else {
			$return = $_POST['_wp_http_referer'];
			$error['type'] = 'danger danger';
			$error['message'] = __('You have to agree with the term and condition', 'cell-store');
			ajax_response($error,$return);
		}
		die();
	}
}

add_action('wp_ajax_nopriv_purchase_confirmation', 'process_purchase_confirmation');
add_action('wp_ajax_purchase_confirmation', 'process_purchase_confirmation');

function process_purchase_confirmation() {
	$cell_store_pages = get_option( 'cell_store_pages' );
	$payment_result_page = $cell_store_pages['payment-result'];

	if ( empty($_POST) || !wp_verify_nonce($_POST['purchase_confirmation_nonce'],'purchase_confirmation') ) {
		echo 'Sorry, your nonce did not verify.';
		die();
	} else {

		$billing = $_SESSION['shopping-cart']['customer']['billing'];
		$shipping = $_SESSION['shopping-cart']['customer']['shipping'];
		$items = $_SESSION['shopping-cart']['items'];

		foreach ($items as $key => $value) {
			// get product data from database
			$product_data = get_post( $value['ID'] );
			$product_meta = get_post_meta( $value['ID'] );

			// get product discount price
			$product_price = $product_meta['_price'][0];
			if (cs_get_discount_price($value['ID'])) {
				$product_price = cs_get_discount_price($value['ID']);
			}

			// add details from database to product object
			$items[$key]['stock-manage'] = $product_meta['_use_stock_management'][0];
			$items[$key]['weight'] = 0;
			if (isset($product_meta['_weight'][0])) {
				$items[$key]['weight'] = $product_meta['_weight'][0];
			}
			$items[$key]['name'] = $product_data->post_title;
			$items[$key]['price'] = $product_price;
		}

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
			add_post_meta($new_transaction, '_items', $items, true);
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
			$mail_title = __('Transaction Confirmation', 'cell-store'); // TODO : add a proper title
			$message = sprintf( __('<p> Dear %s </p>', 'cell-store'), $_SESSION['shopping-cart']['customer']['billing']['first-name'] );
			$message .= sprintf( __('<p> Thank you for shopping at %s </p>', 'cell-store'), get_bloginfo('name') );
			$message .= sprintf( __('<p> Your order : %s  has been received </p>', 'cell-store'), $slug );
			$message .= sprintf( __('<p> To check the status of your order at any time, you can log into your account at %s </p>', 'cell-store'), get_bloginfo('url') );
			$message .= sprintf( __('<p> If you have any questions, please contact us at %1$s or +62222013796 (Monday to Friday, 10 AM - 5PM) or +6281382690013 (WhatsApp only). Please make sure to reference your order number : %2$s </p>', 'cell-store'), get_bloginfo('admin_email'), $slug );
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
			foreach ($items as $item) {
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

		$_SESSION['shopping-cart']['last-transaction'] = $slug;

		if ($_SESSION['shopping-cart']['payment']['method'] == 'paypal') {

			cs_process_paypal_payment();

		} else {
			unset($_SESSION['shopping-cart']['items']);
			unset($_SESSION['shopping-cart']['coupon']);
			unset($_SESSION['shopping-cart']['payment']['total-weight']);
			unset($_SESSION['shopping-cart']['payment']['total-item-cost']);
			unset($_SESSION['shopping-cart']['payment']['shipping-destination-id']);
			unset($_SESSION['shopping-cart']['payment']['method']);
			unset($_SESSION['shopping-cart']['payment']['shipping-option']);
			unset($_SESSION['shopping-cart']['payment']['shipping-rate']);
	
			$return = get_permalink(get_page_by_path($payment_result_page ));
			$result['type'] = 'success';
			$result['message'] = __('Purchase has been made', 'cell-store');
			ajax_response($result,$return);
		}
		die();
	}
}

/* payment confirmation 
---------------------------------------------------------------
*/

add_action('wp_ajax_nopriv_payment_confirmation', 'process_payment_confirmation');
add_action('wp_ajax_payment_confirmation', 'process_payment_confirmation');

function process_payment_confirmation() {

	$store_payment = get_option( 'cell_store_payments' );
	$additional_field = $store_payment['transfer-input'];	

	if ( empty($_POST) || !wp_verify_nonce($_POST['payment_confirmation_nonce'],'payment_confirmation') ) {
		echo 'Sorry, your nonce did not verify.';
		die();
	} else {

		$return = $_POST['_wp_http_referer'];

		if ($_POST['name'] != '') {
			$name = $_POST['name'];
		} else {
			$result['type'] = 'danger';
			$result['message'] = __('Name is missing', 'cell-store');
			ajax_response($result,$return);
		}

		if ($_POST['email'] != '') {
			$email = $_POST['email'];
		} else {
			$result['type'] = 'danger';
			$result['message'] = __('Email is missing', 'cell-store');
			ajax_response($result,$return);
		}

		if ($_POST['transaction-slug'] != '') {
			$transaction_slug = $_POST['transaction-slug'];
		} else {
			$result['type'] = 'danger';
			$result['message'] = __('Transaction Code is missing', 'cell-store');
			ajax_response($result,$return);
		}

		$date = $_POST['date'];
		$method = $_POST['method'];

		$account_holder = '';
		if (isset($_POST['account-holder']) && $_POST['account-holder'] != '') {
			$account_holder = __( ' on behalf of ','cell-store' ).  $_POST['account-holder'];
		}

		if (isset($_POST['other-method']) && $_POST['other-method'] != '') {
			$method = $_POST['other-method'];
		}
		
		$transaction_id = get_id_by_slug($transaction_slug,'transaction');

		if ($transaction_id) {

			$user = get_user_by('email', $email);
			$user_id = 0;
			if ($user) {
				$user_id = $user->ID;
			}
			$time = current_time('mysql');

			$additional_message = '';

			foreach ($additional_field as $key => $value) {
				if (isset($_POST[$value['title']]) && $_POST[$value['title']] != '') {
					$additional_message .= ' -'.$value['title'].':'.$_POST[$value['title']].'- ';
				}
			}

			$comment = array(
				'comment_post_ID' => $transaction_id,
				'comment_author' => $name,
				'comment_author_email' => $email,
				'comment_content' => 'Transaction payment confirmed by '.$name.' at '.$date.' by '. $method . $account_holder. $additional_message,
				'user_id' => $user_id,
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
				wp_mail($email, $mail_title, $message);
			}

			// send mail to admin
			$mail_title = __('New Payment Confirmation', 'cell-store');
			$message = 'Transaction payment confirmed by '.$name.' at '.$date.' by '. $method .' on behalf of '. $account_holder;

			if (function_exists('cell_email')) {
				cell_email(get_bloginfo('admin_email'), $mail_title, wpautop($message));
			} else {
				wp_mail(get_bloginfo('admin_email'), $mail_title, $message);
			}
		} else {
			$result['type'] = 'danger';
			$result['message'] = __('Invalid Transaction Code', 'cell-store');
			ajax_response($result,$return);
		}

		$result['type'] = 'success';
		$result['message'] = __('Confirmation has been send', 'cell-store');
		ajax_response($result,$return);

	}
}

/* change currency 
---------------------------------------------------------------
*/

add_action('wp_ajax_nopriv_switch_currency', 'process_switch_currency');
add_action('wp_ajax_switch_currency', 'process_switch_currency');

function process_switch_currency() {

	$return_url =  wp_get_referer();

	if (isset($_SESSION['shopping-cart']['payment']['method']) && $_SESSION['shopping-cart']['payment']['method'] == 'paypal') {


	$cell_store_pages = get_option( 'cell_store_pages' );

	$payment_option_page_url = get_permalink(get_page_by_path($cell_store_pages['payment-option'] ));

		$result['type'] = 'danger';
		$result['message'] = __('Please use other payment method to switch currency. ', 'cell-store');
		$result['message'] = sprintf( __(' Please use other payment method to switch currency. Go to  <a href="%s"><strong>Payment Option</strong></a>', 'cell-store'), $payment_option_page_url );
	} else {
		$current_currency = cs_switch_currency();
		$result['type'] = 'success';
		$result['message'] = sprintf( __('Switched currency to %s ', 'cell-store'), $current_currency );
	}

	ajax_response($result,$return_url);
}
?>