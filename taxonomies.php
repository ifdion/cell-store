<?php

/* Taxonomies
--------------------------------------------------------------
*/

add_action( 'init', 'cell_store_taxonomies', 1 );

function cell_store_taxonomies() {

	global $cell_store_option;
	$collection_slug = $cell_store_option['product']['collection-slug'];
	$product_category_slug = $cell_store_option['product']['product-category-slug'];
	$product_tag_slug = $cell_store_option['product']['product-tag-slug'];

	$collection_labels = array(
		'name' => _x( 'Collection', 'taxonomy general name' ),
		'singular_name' => _x( 'Collection', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Collection','cell-store'),
		'all_items' => __( 'All Collection','cell-store' ),
		'parent_item' => __( 'Parent Collection','cell-store' ),
		'parent_item_colon' => __( 'Parent Collection:','cell-store' ),
		'edit_item' => __( 'Edit Collection','cell-store' ), 
		'update_item' => __( 'Update Collection','cell-store' ),
		'add_new_item' => __( 'Add New Collection','cell-store' ),
		'new_item_name' => __( 'New Collection Name','cell-store' ),
		'menu_name' => __( 'Collection','cell-store' ),
	);
	
	register_taxonomy('collection',array('product'), array(
		'hierarchical' => true,
		'labels' => $collection_labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => $collection_slug ),
	));

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
		'rewrite' => array( 'slug' => $product_category_slug ),
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
		'rewrite' => array( 'slug' => $product_tag_slug ),
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
