<?php

/* add coupon from query paramater 
---------------------------------------------------------------
*/
add_action('init','process_coupon_in_query');	

function process_coupon_in_query(){
	if ( 'GET' == $_SERVER['REQUEST_METHOD'] && !empty( $_GET['coupon'] )) {
		$coupon_code = $_GET['coupon'];

		$coupon_result = process_coupon($coupon_code);
		ajax_response($coupon_result,get_bloginfo('url'));

		die();

	}
}


?>