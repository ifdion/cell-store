<?php
include_once ('ajax.php');
// include_once ('admin-page.php');

/* Post Types
--------------------------------------------------------------
*/

add_action('init', 'shipping_post_type', 1 );

function shipping_post_type() {
	
  $shipping_labels = array(
		'name' => _x('Shipping Destination', 'post type general name'),
		'singular_name' => _x('Shipping Destination', 'post type singular name'),
		'add_new' => _x('Add New', 'shipping-destination'),
		'add_new_item' => __('Add New Shipping Destination','cell-store'),
		'edit_item' => __('Edit Shipping Destination','cell-store'),
		'new_item' => __('New Shipping Destination','cell-store'),
		'view_item' => __('View Shipping Destination','cell-store'),
		'search_items' => __('Search Shipping Destination','cell-store'),
		'not_found' =>  __('No shipping destination found','cell-store'),
		'not_found_in_trash' => __('No shipping destination found in Trash','cell-store'), 
		'parent_item_colon' => '',
		'menu_name' => __('Shipping Destination', 'cell-store')
	);
	$shipping_args = array(
		'labels' => $shipping_labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => 'options-general.php', 
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => false, 
		'hierarchical' => true,
		'menu_position' => 7,
		'supports' => array('title','page-attributes')
	); 

	register_post_type('shipping-destination',$shipping_args);
  
}

/* metabox 
---------------------------------------------------------------
*/

$shipping_metabox = new WPAlchemy_MetaBox(array(
	'id' => '_shipping_meta',
	'title' => __('Shipping Detail', 'cell-store'),
	'types' => array('shipping-destination'), // added only for pages and to custom post type "events"
	'context' => 'normal', // same as above, defaults to "normal"
	'priority' => 'high', // same as above, defaults to "high"
	'template' => CELL_STORE_PATH . '/shipping-destination/metabox.php',
	'mode' => WPALCHEMY_MODE_EXTRACT
));

/* quick edit form 
---------------------------------------------------------------
*/
// add_action('admin_notices', 'my_admin_notice');
// function my_admin_notice(){
// 	if (is_admin() && is_post_type_archive('shipping-destination')) {
//  		echo '<div class="updated"><p>Please change this into a simple shipping post type submit form, so you dont have to load another page, just for two weeny shipping details</p></div>';
//  	}
// }



/* input 
---------------------------------------------------------------
*/
add_filter('enter_title_here', 'shipping_title_placeholder', 2, 2);
function shipping_title_placeholder($label, $post){
	if($post->post_type == 'shipping-destination')
		$label = __('Enter shipping destination here', 'cell-store');
	return $label;
}
?>