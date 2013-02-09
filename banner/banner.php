<?php

/* Custom Post Types
--------------------------------------------------------------
*/

add_action('init', 'hijabchic_custom_post_type');
function hijabchic_custom_post_type() {

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
			the_post_thumbnail('50');
			break;
		case 'url':
			echo get_post_meta($post->ID, '_url', true);
			break;
		case 'position':
			echo get_the_term_list($post->ID, 'position', '', ', ','');
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

/* output the banners 
---------------------------------------------------------------
*/

function the_banner(){
	
}

?>