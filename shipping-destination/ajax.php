<?php

/* get chil shipping destination 
---------------------------------------------------------------
*/
add_action('wp_ajax_nopriv_get_child_shipping_destination', 'process_get_child_shipping_destination');
add_action('wp_ajax_get_child_shipping_destination', 'process_get_child_shipping_destination');

function process_get_child_shipping_destination() {

	$shipping_destination_id = $_POST['id'];
	$args = array(
		'post_type' => 'shipping-destination',
		'post_parent' => $shipping_destination_id,
		'nopaging' => true
	);
	$child_destination = new WP_Query($args);
	$child_array = array();
	$i = 0;
	$result_count = 0;
	if ( $child_destination->have_posts() ) :
		while ( $child_destination->have_posts() ) : $child_destination->the_post();
			$destination_id = get_the_ID();
			$destination_title = get_the_title();
			$child_array[$destination_id] = $destination_title;
			$result_count += 1;
		endwhile;
		$child_array[0] = __('Please select', 'cell-store');
	else:

	endif;

	$result['type'] = 'success';
	$result['message'] = $result_count;
	$result['content'] = $child_array;
	ajax_response($result);
	die();
}



?>