<?php

/* Custom Post Types
--------------------------------------------------------------
*/

add_action('init', 'banner_custom_post_type');
function banner_custom_post_type() {

	$banner_labels = array(
		'name' => _x('Banners', 'post type general name'),
		'singular_name' => _x('Banners', 'post type singular name'),
		'add_new' => _x('Add New', 'banner'),
		'add_new_item' => __('Add New Banners', 'cell-store'),
		'edit_item' => __('Edit Banners', 'cell-store'),
		'new_item' => __('New Banners', 'cell-store'),
		'view_item' => __('View Banners', 'cell-store'),
		'search_items' => __('Search Banners', 'cell-store'),
		'not_found' =>  __('No banners found', 'cell-store'),
		'not_found_in_trash' => __('No banners found in Trash', 'cell-store'),
		'parent_item_colon' => '',
		'menu_name' => __('Banners', 'cell-store')
	);
	$banner_args = array(
		'labels' => $banner_labels,
		'public' => true,
		'exclude_from_search' => true,
		'publicly_queryable' => false,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'show_in_nav_menu' => false, 
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => true, 
		'hierarchical' => false,
		'menu_position' => 5,
		'supports' => array('title','author','excerpt','thumbnail')
	);
	
	register_post_type('banner',$banner_args);
}

/* metabox 
---------------------------------------------------------------
*/

$banner_metabox = new WPAlchemy_MetaBox(array(
	'id' => '_banner_meta',
	'title' => __('Banner Details', 'cell-store'),
	'types' => array('banner'), // added only for pages and to custom post type "events"
	'context' => 'normal', // same as above, defaults to "normal"
	'priority' => 'low', // same as above, defaults to "high"
	'template' => CELL_STORE_PATH . '/banner/metabox.php',
	'prefix' =>'_',
	'mode' => WPALCHEMY_MODE_EXTRACT
));

/* Custom columns for the post types
--------------------------------------------------------------
*/

add_filter('manage_edit-banner_columns', 'banner_columns');
function banner_columns($columns){
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'banner-thumbnail' => __('Thumbnail', 'cell-store'),
		'title' => __('Title', 'cell-store'),
		'url' => __('URL', 'cell-store'),
		'position' => __('Position', 'cell-store')
	);
	return $columns;
}


add_action('manage_posts_custom_column',  'banner_custom_column');
function banner_custom_column($column){
	global $post;
	switch ($column) {
		case 'banner-thumbnail':
			the_post_thumbnail('thumbnail');
			break;
		case 'url':
			echo get_post_meta($post->ID, '_url', true);
			break;
		case 'position':
			$position_term = get_the_term_list($post->ID, 'position', '', ', ','');
			if ($position_term != '') {
				echo $position_term;
			}
			$tax_query = array();
			$banner_shared_tax = array('collection', 'product-category', 'product-tag');
			foreach ($banner_shared_tax as $value) {
				$terms = get_the_term_list($post->ID, $value, '', ', ','');
				if ($terms) {
					$tax_query[] = $terms;
				}
			}
			if (count($tax_query) > 0) {
				if ($position_term != '') {
					echo ' | ';
				}
				$tax_post = implode(' + ', $tax_query);
				echo $tax_post;
			}
			break;
	}
}

add_action( 'restrict_manage_posts', 'banner_restrict_manage_posts' );
function banner_restrict_manage_posts() {

	global $typenow;
	if ($typenow == 'banner') {

		$filters = array('position');

		foreach ($filters as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);

			echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
			echo "<option value=''>Show All $tax_name</option>";
			foreach ($terms as $term) {
				echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
			}
			echo "</select>";
		}
	}
}

/* remove other taxonomies for banner 
---------------------------------------------------------------
*/

add_action( 'admin_menu', 'position_only_for_banner', 999 );
function position_only_for_banner() {
  remove_submenu_page( 'edit.php?post_type=banner', 'edit-tags.php?taxonomy=collection&amp;post_type=banner' );
  remove_submenu_page( 'edit.php?post_type=banner', 'edit-tags.php?taxonomy=product-category&amp;post_type=banner' );
  remove_submenu_page( 'edit.php?post_type=banner', 'edit-tags.php?taxonomy=product-tag&amp;post_type=banner' );
  // $page[0] is the menu title
  // $page[1] is the minimum level or capability required
  // $page[2] is the URL to the item's file
}

/* output the banners 
---------------------------------------------------------------
*/

function cs_banner_image_src($size = 'full', $position = false){

	$banner_image = get_stylesheet_directory_uri() .'/images/sample-1.jpg';
	$banner_tax = get_object_taxonomies('banner');

	$args = array(
		'post_type'   => 'banner',
		'post_status' => 'publish',
		'posts_per_page'         => 1,
		'tax_query' => array(
			'relation'  => 'AND',
		),
	);

	if ($position) {
		$args['tax_query'][] = array(
			'taxonomy' => 'position',
			'field' => 'slug',
			'operator' => 'IN',
			'terms' => array($position)
		);
	} else {
		global $wp_query;
		foreach ($banner_tax as $value) {
			if (isset($wp_query->query[$value])) {
				$args['tax_query'][] = array(
					'taxonomy' => $value,
					'field' => 'slug',
					'operator' => 'IN',
					'terms' => array($wp_query->query[$value])
				);
			}
		}
	}

	$banner_query = new WP_Query( $args );
	$banner_post = $banner_query->post;
	$banner_image_id = get_post_thumbnail_id($banner_post->ID );
	$banner_image = wp_get_attachment_image_src( $banner_image_id, $size, false);
	$banner_image = $banner_image[0];

	return $banner_image;
}

function cs_banner_text($position = false){

	$banner_image = get_stylesheet_directory_uri() .'/images/sample-1.jpg';
	$banner_tax = get_object_taxonomies('banner');

	$args = array(
		'post_type'   => 'banner',
		'post_status' => 'publish',
		'posts_per_page'         => 1,
		'tax_query' => array(
			'relation'  => 'AND',
		),
	);

	if ($position) {
		$args['tax_query'][] = array(
			'taxonomy' => 'position',
			'field' => 'slug',
			'operator' => 'IN',
			'terms' => array($position)
		);
	} else {
		global $wp_query;
		foreach ($banner_tax as $value) {
			if (isset($wp_query->query[$value])) {
				$args['tax_query'][] = array(
					'taxonomy' => $value,
					'field' => 'slug',
					'operator' => 'IN',
					'terms' => array($wp_query->query[$value])
				);
			}
		}
	}

	$banner_query = new WP_Query( $args );
	// $banner_post = $banner_query->post;
	// $banner_image_id = get_post_thumbnail_id($banner_post->ID );
	// $banner_image = wp_get_attachment_image_src( $banner_image_id, $size, false);
	// $banner_image = $banner_image[0];

	return $banner_query->post->post_title;
}

?>