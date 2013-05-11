<?php

include_once ('ajax.php');

/* cell-shopping-cart shortcode
---------------------------------------------------------------
*/

add_shortcode( 'cell-shopping-cart', 'cell_shopping_cart_shortcode' );

function cell_shopping_cart_shortcode(){
	// check if current theme has a replacement template
	if ( '' != locate_template( 'store-shopping-cart.php' ) ) {
		$current_theme = wp_get_theme();
		$template = $current_theme->theme_root.'/'.$current_theme->stylesheet.'/store-shopping-cart.php';
		return get_template_file($template);
	} else{
		return cell_shopping_cart_base();
	}
}

function cell_shopping_cart_base(){
	$template = 'template/shopping-cart.php';
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
	wp_register_script('address', plugins_url('cell-store/js/address.js'), array('jquery'), '1.0', true);
	wp_localize_script( 'address', 'global', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

	// check if current theme has a replacement template
	if ( '' != locate_template( 'store-checkout.php' ) ) {
		$current_theme = wp_get_theme();
		$template = $current_theme->theme_root.'/'.$current_theme->stylesheet.'/store-checkout.php';
		return get_template_file($template);
	} else{
		return cell_checkout();
	}	
}

function cell_checkout(){
	$template = 'template/checkout.php';
	ob_start();
		include($template);
		$checkout_content = ob_get_contents();
	ob_end_clean();
	echo $checkout_content;
}

/* payment option
---------------------------------------------------------------
*/


add_shortcode( 'cell-payment-option', 'cell_payment_option_shortcode' );

function cell_payment_option_shortcode(){

	// check if current theme has a replacement template
	if ( '' != locate_template( 'store-payment-option.php' ) ) {
		$current_theme = wp_get_theme();
		$template = $current_theme->theme_root.'/'.$current_theme->stylesheet.'/store-payment-option.php';
		return get_template_file($template);
	} else{
		return cell_payment_option();
		
	}	
}

function cell_payment_option(){
	$template = 'template/payment-option.php';
	ob_start();
		include($template);
		$payment_option_content = ob_get_contents();
	ob_end_clean();
	echo $payment_option_content;
}

/* order confirmation
---------------------------------------------------------------
*/


add_shortcode( 'cell-order-confirmation', 'cell_order_confirmation_shortcode' );

function cell_order_confirmation_shortcode(){

	// check if current theme has a replacement template
	if ( '' != locate_template( 'store-order-confirmation.php' ) ) {
		$current_theme = wp_get_theme();
		$template = $current_theme->theme_root.'/'.$current_theme->stylesheet.'/store-order-confirmation.php';
		return get_template_file($template);
	} else{
		return cell_order_confirmation();
		
	}	
}

function cell_order_confirmation(){
	$template = 'template/order-confirmation.php';
	ob_start();
		include($template);
		$order_confirmation_content = ob_get_contents();
	ob_end_clean();
	echo $order_confirmation_content;
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
		return get_template_file($template);
	} else{
		return cell_payment_confirmation();
	}
}

function cell_payment_confirmation(){
	ob_start();
		include('template/payment-confirmation.php');
		$payment_confirmation_content = ob_get_contents();
	ob_end_clean();
	echo $payment_confirmation_content;
	
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