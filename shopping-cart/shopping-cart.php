<?php

include_once ('ajax.php');


/* add shopping cart to shopping-cart-page
---------------------------------------------------------------
*/

add_filter('the_content','cell_shopping_cart');

function cell_shopping_cart($content){
	if (is_page('shopping-cart')){
		return cell_shopping_cart_content().$content;
	} else{
		return $content;
	}
}

function cell_shopping_cart_content(){
	ob_start();
		include('template/shopping-cart.php');
		$shopping_cart_content = ob_get_contents();
	ob_end_clean();
	return $shopping_cart_content;
}


/* add checkout to checkout page
---------------------------------------------------------------
*/
// add_action('wp_print_scripts', 'add_suggest_script');
// function add_suggest_script(){	
// 		wp_enqueue_script('suggest');
// }

/* checkout content 
---------------------------------------------------------------
*/

// add_filter('the_content','cell_check_out');
// function cell_check_out($content){
// 	if (is_page('checkout')){
// 		return cell_check_out_content().$content;
// 	} else{
// 		return $content;
// 	}
// }

add_action('template_redirect', 'cell_check_out_script');
function cell_check_out_script(){
	if (is_page('checkout')){
		wp_enqueue_script('address', plugins_url().'/cell-store/js/address.js', array('jquery'), '0.1', true);
		wp_localize_script( 'address', 'global', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}	
}

function cell_check_out_content($print = true){
	ob_start();
		include('template/checkout.php');
		$check_out_content = ob_get_contents();
	ob_end_clean();
	if ($print == true) {
		print $check_out_content;
	} else {
		return $check_out_content;
	}
	
}


/* payment option
---------------------------------------------------------------
*/


// add_filter('the_content','cell_payment_option');
// function cell_payment_option($content){
// 	if (is_page('payment-option')){
// 		return cell_payment_option_content().$content;
// 	} else{
// 		return $content;
// 	}
// }

function cell_payment_option_content($print = true){
	ob_start();
		include('template/payment-option.php');
		$payment_option_content = ob_get_contents();
	ob_end_clean();
	if ($print == true) {
		print $payment_option_content;
	} else {
		return $payment_option_content;
	}
}

/* order confirmation
---------------------------------------------------------------
*/


// add_filter('the_content','cell_order_confirmation');
// function cell_order_confirmation($content){
// 	if (is_page('order-confirmation')){
// 		return cell_order_confirmation_content().$content;
// 	} else{
// 		return $content;
// 	}
// }

function cell_order_confirmation_content($print = true){
	ob_start();
		include('template/order-confirmation.php');
		$order_confirmation_content = ob_get_contents();
	ob_end_clean();
	if ($print == true) {
		print $order_confirmation_content;
	} else {
		return $order_confirmation_content;
	}
	
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