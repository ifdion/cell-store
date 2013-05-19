<?php

/* Custom Post Types
--------------------------------------------------------------
*/

add_action('init', 'lookbook_custom_post_type');
function lookbook_custom_post_type() {

	$lookbook_labels = array(
		'name' => _x('Lookbook', 'post type general name'),
		'singular_name' => _x('Lookbook', 'post type singular name'),
		'add_new' => _x('Add New', 'lookbook'),
		'add_new_item' => __('Add New Lookbook', 'cell-store'),
		'edit_item' => __('Edit Lookbook', 'cell-store'),
		'new_item' => __('New Lookbook', 'cell-store'),
		'view_item' => __('View Lookbook', 'cell-store'),
		'search_items' => __('Search Lookbook', 'cell-store'),
		'not_found' =>  __('No lookbooks found', 'cell-store'),
		'not_found_in_trash' => __('No lookbooks found in Trash', 'cell-store'),
		'parent_item_colon' => '',
		'menu_name' => __('Lookbook', 'cell-store')
	);
	$lookbook_args = array(
		'labels' => $lookbook_labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => true, 
		'hierarchical' => false,
		'menu_position' => 5,
		'supports' => array('title','author','excerpt','thumbnail')
	);
	
	register_post_type('lookbook',$lookbook_args);
}

?>