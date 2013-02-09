<?php

/* Taxonomies
--------------------------------------------------------------
*/

add_action( 'init', 'cell_store_taxonomies', 1 );

function cell_store_taxonomies() {

	$product_category_labels = array(
		'name' => _x( 'Product Category', 'taxonomy general name' ),
		'singular_name' => _x( 'Product Category', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Product Category','cell-store'),
		'all_items' => __( 'All Product Category','cell-store' ),
		'parent_item' => __( 'Parent Product Category','cell-store' ),
		'parent_item_colon' => __( 'Parent Product Category:','cell-store' ),
		'edit_item' => __( 'Edit Product Category','cell-store' ), 
		'update_item' => __( 'Update Product Category','cell-store' ),
		'add_new_item' => __( 'Add New Product Category','cell-store' ),
		'new_item_name' => __( 'New Product Category Name','cell-store' ),
		'menu_name' => __( 'Product Category','cell-store' ),
	);
	
	register_taxonomy('product-category',array('product'), array(
		'hierarchical' => true,
		'labels' => $product_category_labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'product-category' ),
	));

	$product_tag_labels = array(
		'name' => _x( 'Product Tag', 'taxonomy general name' ),
		'singular_name' => _x( 'Product Tag', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Product Tag' ,'cell-store'),
		'all_items' => __( 'All Product Tag' ,'cell-store'),
		'parent_item' => __( 'Parent Product Tag' ,'cell-store'),
		'parent_item_colon' => __( 'Parent Product Tag:' ,'cell-store'),
		'edit_item' => __( 'Edit Product Tag' ,'cell-store'), 
		'update_item' => __( 'Update Product Tag' ,'cell-store'),
		'add_new_item' => __( 'Add New Product Tag' ,'cell-store'),
		'new_item_name' => __( 'New Product Tag Name' ,'cell-store'),
		'menu_name' => __( 'Product Tag','cell-store' ),
	);
	
	register_taxonomy('product-tag',array('product'), array(
		'hierarchical' => false,
		'labels' => $product_tag_labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'product-tag' ),
	));

	$position_labels = array(
		'name' => _x( 'Position', 'taxonomy general name' ),
		'singular_name' => _x( 'Position', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Positions' ,'cell-store'),
		'all_items' => __( 'All Positions' ,'cell-store'),
		'parent_item' => __( 'Parent Position','cell-store' ),
		'parent_item_colon' => __( 'Parent Position:' ,'cell-store'),
		'edit_item' => __( 'Edit Position','cell-store' ), 
		'update_item' => __( 'Update Position','cell-store' ),
		'add_new_item' => __( 'Add New Position' ,'cell-store'),
		'new_item_name' => __( 'New Position Name' ,'cell-store'),
		'menu_name' => __( 'Position' ),
	); 		
	register_taxonomy('position',array('banner'), array(
		'hierarchical' => true,
		'labels' => $position_labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'position' ),
	));

}

/* shipping area as setting 
---------------------------------------------------------------
*/
// add_action( 'admin_menu', 'register_taxonomy_page' );
// function register_taxonomy_page() {
// 	add_submenu_page( 'options-general.php', 'Shipping Area', 'Shipping Area', 'edit_users', 'edit-tags.php?taxonomy=area&post_type=shipping-destination' );
// }

/* create taxonomy for every project
---------------------------------------------------------------
*/

function save_cpt_as_term($post_id) {

	$post = get_post( $post_id );
	$cpt_as_term = array('lot','project','block','sponsor');

	
	if(in_array($post->post_type, $cpt_as_term) && $post->post_status=='publish'){

		$term_args['name'] = $post->post_title;
		$term_args['slug'] = $post->post_name.'';
		$term_id = get_post_meta($post_id, $post->post_type.'-term-id', true);

		if($term_id){
			$term = wp_update_term( $term_id, $post->post_type.'-term', $term_args );
		} else {
			$term = wp_insert_term( $term_args['name'], $post->post_type.'-term', $term_args );
			$meta_status = add_post_meta($post_id, $post->post_type.'-term-id', $term['term_id'], true);
		}

	}
}
// add_action( 'save_post', 'save_cpt_as_term');

/* delete taxonomy on post delete
---------------------------------------------------------------
*/
// add_action('admin_init', 'codex_init');
function codex_init() {
	global $wpdb;
	if (current_user_can('delete_posts')) add_action('before_delete_post', 'delete_cpt_as_term', 10);
}

function delete_cpt_as_term($pid) {
	$cpt_as_term = array('lot','project','block','sponsor');
	$post = get_post( $post_id );
	if (in_array($post->post_type, $cpt_as_term)) {
		$term = get_post_meta($pid, $post->post_type.'-term-id', true);
		wp_delete_term( $term, $post->post_type.'-term');
	}
}

?>