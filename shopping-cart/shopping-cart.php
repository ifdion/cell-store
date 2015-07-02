<?php

include_once ('ajax.php');


/* global script 
---------------------------------------------------------------
*/
add_action( 'init','cell_store_script' );
function cell_store_script(){
	wp_register_script( 'address-script', plugins_url('cell-store/js/address-script.js'), array('jquery'), '1.0', true);
	wp_localize_script( 'address-script', 'global', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}


/* cell-shopping-cart shortcode
---------------------------------------------------------------
*/

add_shortcode( 'cell-shopping-cart', 'cell_shopping_cart_shortcode' );
function cell_shopping_cart_shortcode(){
	// check if current theme has a replacement template
	$template = 'template/shopping-cart.php';
	if (locate_template('cell-store/shopping-cart.php') != '') {
		$template = get_stylesheet_directory().'/cell-store/shopping-cart.php';
	}
	ob_start();
		include($template);
		$shopping_cart_content = ob_get_contents();
	ob_end_clean();
	return $shopping_cart_content;
}

/* cell-checkout shortcode
---------------------------------------------------------------
*/
add_shortcode( 'cell-checkout', 'cell_checkout_shortcode' );
function cell_checkout_shortcode(){
	// add addrees script
	wp_enqueue_script('address-script');
	wp_localize_script( 'address-script', 'global', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

	// check if current theme has a replacement template
	$template = 'template/checkout.php';
	if (locate_template('cell-store/checkout.php') != '') {
		$template = get_stylesheet_directory().'/cell-store/checkout.php';
	}
	ob_start();
		include($template);
		$checkout = ob_get_contents();
	ob_end_clean();
	return $checkout;
}

/* payment option
---------------------------------------------------------------
*/

add_shortcode( 'cell-payment-option', 'cell_payment_option_shortcode' );
function cell_payment_option_shortcode(){

	// check if current theme has a replacement template
	$template = 'template/payment-option.php';
	if (locate_template('cell-store/payment-option.php') != '') {
		$template = get_stylesheet_directory().'/cell-store/payment-option.php';
	}
	ob_start();
		include($template);
		$payment_option = ob_get_contents();
	ob_end_clean();
	return $payment_option;
}

/* order confirmation
---------------------------------------------------------------
*/


add_shortcode( 'cell-order-confirmation', 'cell_order_confirmation_shortcode' );
function cell_order_confirmation_shortcode(){

	// check if current theme has a replacement template
	$template = 'template/order-confirmation.php';
	if (locate_template('cell-store/order-confirmation.php') != '') {
		$template = get_stylesheet_directory().'/cell-store/order-confirmation.php';
	}
	ob_start();
		include($template);
		$order_confirmation = ob_get_contents();
	ob_end_clean();
	return $order_confirmation;
}


/* payment result
---------------------------------------------------------------
*/

add_shortcode( 'cell-payment-result', 'cell_payment_result_content' );
function cell_payment_result_content(){

	// check if current theme has a replacement template
	$template = 'template/payment-result.php';
	if (locate_template('cell-store/payment-result.php') != '') {
		$template = get_stylesheet_directory().'/cell-store/payment-result.php';
	}
	ob_start();
		include($template);
		$payment_result = ob_get_contents();
	ob_end_clean();
	return $payment_result;	
}

/* payment confirmation
---------------------------------------------------------------
*/

add_shortcode( 'cell-payment-confirmation', 'cell_payment_confirmation_content' );
function cell_payment_confirmation_content(){

	// check if current theme has a replacement template
	$template = 'template/payment-confirmation.php';
	if (locate_template('cell-store/payment-confirmation.php') != '') {
		$template = get_stylesheet_directory().'/cell-store/payment-confirmation.php';
	}
	ob_start();
		include($template);
		$payment_confirmation = ob_get_contents();
	ob_end_clean();
	return $payment_confirmation;	
}

/* order detail 
---------------------------------------------------------------
*/
function cell_item_detail(){
	ob_start();
		include('template/item-detail.php');
		$order_table_content = ob_get_contents();
	ob_end_clean();
	print $order_table_content;
}

function cell_shipping_detail(){
	ob_start();
		include('template/shipping-detail.php');
		$shipping_detail_content = ob_get_contents();
	ob_end_clean();
	print $shipping_detail_content;
}

function cell_payment_detail(){
	ob_start();
		include('template/payment-detail.php');
		$payment_detail_content = ob_get_contents();
	ob_end_clean();
	print $payment_detail_content;	
}

/* switch currency 
---------------------------------------------------------------
*/

function cs_switch_currency(){

	$option = get_option('cell_store_features' );

	if ( false === ( $exchange_rate = get_transient( 'cs-exchange-rate' ) ) ) {
		$api_endpoint = 'https://openexchangerates.org/api/latest.json?app_id='.$option['open-exchange-id'];
		$api_results = wp_remote_get($api_endpoint);
		$api_results = json_decode($api_results['body']);
		$rates = $api_results->rates;
		$exchange_rate = $rates->$option['secondary-currency'] / $rates->$option['currency'];
		set_transient( 'cs-exchange-rate', $exchange_rate, 60*60*1 );
	}

	if (isset($_SESSION['shopping-cart']['payment']['use-seconary-currency'])) {
		unset($_SESSION['shopping-cart']['payment']['use-seconary-currency']);
		unset($_SESSION['shopping-cart']['payment']['exchange-rate']);
		$current_currency = $option['currency'];
	} else {
		$_SESSION['shopping-cart']['payment']['use-seconary-currency'] = 1;
		$_SESSION['shopping-cart']['payment']['exchange-rate'] = $exchange_rate;
		$current_currency = $option['secondary-currency'];
	}

	return $current_currency;

}

?>