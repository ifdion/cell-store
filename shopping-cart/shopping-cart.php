<?php

include_once ('ajax.php');


/* required script 
---------------------------------------------------------------
*/

add_action('init', 'register_address_script');
function register_address_script() {
	wp_register_script('address', plugins_url('cell-store/js/address.js'), array('jquery'), '1.0', true);
	wp_localize_script( 'address', 'global', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}

add_action('wp_footer', 'print_address_script');
function print_address_script() {
	global $add_address_script;
	if ( ! $add_address_script ){
		return;		
	}
	wp_print_scripts('address');
}
/* cell-shopping-cart shortcode
---------------------------------------------------------------
*/

add_shortcode( 'cell-shopping-cart', 'cell_shopping_cart_content' );

function cell_shopping_cart_content(){
	// check if current theme has a replacement template
	if ( '' != locate_template( 'store-shopping-cart.php' ) ) {
		$current_theme = wp_get_theme();
		$template = $current_theme->theme_root.'/'.$current_theme->stylesheet.'/store-shopping-cart.php';
	} else{
		$template = 'template/shopping-cart.php';
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
add_shortcode( 'cell-checkout', 'cell_check_out_content' );

function cell_check_out_content(){

	// add addrees script
	global $add_address_script;
	$add_address_script = true;

	// check if current theme has a replacement template
	if ( '' != locate_template( 'store-checkout.php' ) ) {
		wp_die( 'ada file?' );
		$current_theme = wp_get_theme();
		$template = $current_theme->theme_root.'/'.$current_theme->stylesheet.'/store-checkout.php';
	} else{
		$template = 'template/checkout.php';
	}

	// output shortcode
	ob_start();
		include($template);
		$check_out_content = ob_get_contents();
	ob_end_clean();
	return $check_out_content;
	
}


/* payment option
---------------------------------------------------------------
*/


add_shortcode( 'cell-payment-option', 'cell_payment_option_content' );

function cell_payment_option_content(){
	// check if current theme has a replacement template
	if ( '' != locate_template( 'store-payment-option.php' ) ) {
		$current_theme = wp_get_theme();
		$template = $current_theme->theme_root.'/'.$current_theme->stylesheet.'/store-payment-option.php';
	} else{
		$template = 'template/payment-option.php';
	}

	ob_start();
		include($template);
		$check_out_content = ob_get_contents();
	ob_end_clean();
	return $check_out_content;
	
}


/* order confirmation
---------------------------------------------------------------
*/


add_shortcode( 'cell-order-confirmation', 'cell_order_confirmation_content' );

function cell_order_confirmation_content(){

	// check if current theme has a replacement template
	if ( '' != locate_template( 'store-order-confirmation.php' ) ) {
		$current_theme = wp_get_theme();
		$template = $current_theme->theme_root.'/'.$current_theme->stylesheet.'/store-order-confirmation.php';
	} else{
		$template = 'template/order-confirmation.php';
	}

	ob_start();
		include('template/order-confirmation.php');
		$order_confirmation_content = ob_get_contents();
	ob_end_clean();
	return $order_confirmation_content;
	
}

/* payment confirmation
---------------------------------------------------------------
*/


add_shortcode( 'cell-payment-confirmation', 'cell_payment_confirmation_content' );

function cell_payment_confirmation_content(){

	// check if current theme has a replacement template
	if ( '' != locate_template( 'store-payment-confirmation.php' ) ) {
		$current_theme = wp_get_theme();
		$template = $current_theme->theme_root.'/'.$current_theme->stylesheet.'/store-payment-confirmation.php';
	} else{
		$template = 'template/payment-confirmation.php';
	}

	ob_start();
		include('template/payment-confirmation.php');
		$order_confirmation_content = ob_get_contents();
	ob_end_clean();
	return $order_confirmation_content;
	
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


?>