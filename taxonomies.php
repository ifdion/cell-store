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


/* create taxonomy for every project
---------------------------------------------------------------
*/

function save_cpt_as_term($post_id) {

	$post = get_post( $post_id );
	$cpt_as_term = array('collection');

	
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
	if (current_user_can('delete_posts')) add_action('before_delete_post', 'delete_cpt_as_term', 1);
}

function delete_cpt_as_term($post_id) {
	$cpt_as_term = array('collection');
	$post = get_post( $post_id );
	if (in_array($post->post_type, $cpt_as_term)) {
		$term = get_post_meta($post_id, $post->post_type.'-term-id', true);
		wp_delete_term( $term, $post->post_type.'-term');
	}
}

/* cell store collection 
---------------------------------------------------------------
*/

add_shortcode( 'cell-store-collection', 'cell_store_collection' );

function cell_store_collection(){


	$current_paging = get_query_var( 'paged' );

	$taxonomies = array( 
		'collection'
	);

	$per_page = 12;

	if ($current_paging == 0) {
		$offset = 0;
	} else {
		$offset = ($current_paging-1) * $per_page;	
	}
	
	$taxonomies = array( 
		'collection'
	);

	$args = array(
		'orderby'       => 'id', 
		'hide_empty'    => true, 
		'number'        => $per_page, 
		'fields'        => 'all', 
		'offset'        => $offset, 
	); 

	$collections = get_terms( $taxonomies, $args );

	$all_collections = wp_count_terms($taxonomies);

	ob_start();

		echo '<div class="collection-shortcode clearfix">';
		foreach ($collections as $key => $collection) {
			$link = get_term_link( $collection );

			$args = array(
				'post_type' => 'banner',
				'posts_per_page' => 1,
				'tax_query' => array(
					array(
						'taxonomy' => 'position',
						'field' => 'slug',
						'terms' => $collection->slug
					)
				)
			);
			$side_loop = new WP_query($args);

			if ( $side_loop->have_posts() ) :
				while ( $side_loop->have_posts() ) : $side_loop->the_post();
					if (has_post_thumbnail( get_the_ID())) {
						echo '<div id="" class="collection-banner">';
						echo '<a href="'.$link.'">';
							the_post_thumbnail('lookbook');
						echo '</a>';
						echo '</div>';
					}
				endwhile;
			endif;

		}

		if ($all_collections > count($collections)) {

			$page = get_queried_object();
			$page_link = get_permalink( $page->ID );
			$total_page = ceil($all_collections / $per_page);
			$current_paging = get_query_var( 'paged' );

			if ($current_paging == 0 ) {

				$next_link = add_query_arg('paged','2',$page_link);

			} elseif ($current_paging == 2 ) {

				$prev_link = $page_link;

				$next_page = $current_paging + 1;
				$next_link = add_query_arg('paged',$next_page,$page_link);

			} elseif ($current_paging == $total_page ) {

				$prev_page = $current_paging - 1;
				$prev_link = add_query_arg('paged',$prev_page,$page_link);
			} else {

				$prev_page = $current_paging - 1;
				$prev_link = add_query_arg('paged',$next_page,$page_link);

				$next_page = $current_paging + 1;
				$next_link = add_query_arg('paged',$next_page,$page_link);

			}

			if (isset($prev_link)) {
				echo '<a href="'.$prev_link.'">Previous</a> ';
			}

			if (isset($next_link)) {
				echo '<a href="'.$next_link.'">Next</a> ';
			}


			

			// echo '<a href="#">Previous</a> ';
			// echo ' <a href="'.$next_link.'">Next</a>';
			// echo count($collections).'/'.$all_collections;
		}

		echo '</div>';

		$collections_content = ob_get_contents();
	ob_end_clean();

	
	return $collections_content;
}
