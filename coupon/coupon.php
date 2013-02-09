<?php
include_once ('ajax.php');
// include_once ('admin-page.php');

/* Post Types
--------------------------------------------------------------
*/

add_action('init', 'coupon_post_type', 0 );

function coupon_post_type() {
	
  $coupon_labels = array(
		'name' => _x('Coupon', 'post type general name'),
		'singular_name' => _x('Coupon', 'post type singular name'),
		'add_new' => _x('Add New', 'investment'),
		'add_new_item' => __('Add New Coupon', 'cell-store'),
		'edit_item' => __('Edit Coupon', 'cell-store'),
		'new_item' => __('New Coupon', 'cell-store'),
		'view_item' => __('View Coupon', 'cell-store'),
		'search_items' => __('Search Coupon', 'cell-store'),
		'not_found' =>  __('No coupon found', 'cell-store'),
		'not_found_in_trash' => __('No coupon found in Trash', 'cell-store'), 
		'parent_item_colon' => '',
		'menu_name' => __('Coupon', 'cell-store')
	);
	$coupon_args = array(
		'labels' => $coupon_labels,
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
		'menu_position' => 7,
		'supports' => array('title','excerpt')
	); 

	register_post_type('coupon',$coupon_args);
  
}

/* metabox 
---------------------------------------------------------------
*/

$coupon_metabox = new WPAlchemy_MetaBox(array(
	'id' => '_coupon_meta',
	'title' => __('Coupon Details', 'cell-store'),
	'types' => array('coupon'), // added only for pages and to custom post type "events"
	'context' => 'normal', // same as above, defaults to "normal"
	'priority' => 'low', // same as above, defaults to "high"
	'template' => CELL_STORE_PATH . '/coupon/metabox.php',
	'prefix' =>'_',
	'mode' => WPALCHEMY_MODE_EXTRACT
));

$coupon_usage = new WPAlchemy_MetaBox(array(
	'id' => '_coupon_usage',
	'title' => __('Coupon Usage', 'cell-store'),
	'types' => array('coupon'), // added only for pages and to custom post type "events"
	'context' => 'normal', // same as above, defaults to "normal"
	'priority' => 'high', // same as above, defaults to "high"
	'template' => CELL_STORE_PATH . '/coupon/metabox-usage.php'

));


/* Custom columns for the post types
--------------------------------------------------------------
*/

add_filter('manage_edit-coupon_columns', 'coupon_columns');
function coupon_columns($columns){
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __('Title', 'cell-store'),
		'coupon_value' => __('Value', 'cell-store'),
		'coupon_time_limit' => __('Time Limit', 'cell-store'),
		'coupon_usage' => __('Usage Count', 'cell-store')
	);
	return $columns;
}


add_action('manage_posts_custom_column',  'coupon_custom_column');
function coupon_custom_column($column){
	global $post;
	$coupon_meta = get_post_meta($post->ID);
	switch ($column) {
		case 'coupon_value':
			echo '<strong>'.$coupon_meta['_discount_value'][0].'</strong>';
			if (isset($coupon_meta['_use_free_shipping'][0])) {
				echo __(' | Free Shipping to ', 'cell-store'). get_the_title($coupon_meta['_area_limit'][0]);
			}
			break;
		case 'coupon_time_limit':
			if ($coupon_meta['_coupon_end'][0]) {
				$todays_date = new DateTime(date("Y-m-d"));;
				$coupon_end = new DateTime($coupon_meta['_coupon_end'][0]);
				if ($todays_date < $coupon_end) {
					printf(__('<strong> %1$s </strong>, starts %2$s', 'cell-store'), $coupon_meta['_coupon_end'][0], $coupon_meta['_coupon_start'][0]);
				} else {
					printf(__('%1$s, starts %2$s', 'cell-store'), $coupon_meta['_coupon_end'][0], $coupon_meta['_coupon_start'][0]);
				}
			} else {
				echo __('No time limit', 'cell-store');
			}
			break;
		case 'coupon_usage':
			if (isset($coupon_meta['_coupon_usage'][0])) {
				$usage_count = $coupon_meta['_coupon_usage'][0];
			} else {
				$usage_count = 0;
			}
			$usage_limit = $coupon_meta['_coupon_limit'][0];
			if (!$usage_limit) {
				$usage_limit = 0;
			}
			echo $usage_count. ' /  ' . $usage_limit;
			break;
	}
}

add_filter( 'manage_edit-coupon_sortable_columns', 'coupon_column_register_sortable' );
function coupon_column_register_sortable( $columns ) {
	$columns['coupon_value'] = 'coupon_value';
	$columns['coupon_time_limit'] = 'coupon_time_limit';
	$columns['coupon_usage'] = 'coupon_usage';
	return $columns;
}

add_filter( 'request', 'coupon_column_orderby' );
function coupon_column_orderby( $vars ) {
	if ( isset( $vars['orderby'] ) && 'coupon_value' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => '_discount_value',
			'orderby' => 'meta_value_num'
		) );
	}
	if ( isset( $vars['orderby'] ) && 'coupon_usage' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => '_coupon_usage',
			'orderby' => 'meta_value_num'
		) );
	}
	if ( isset( $vars['orderby'] ) && 'coupon_time_limit' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => '_coupon_end',
			'orderby' => 'meta_value'
		) );
	}
	return $vars;
}


/* input title
---------------------------------------------------------------
*/
// add_filter('enter_title_here', 'coupon_code_placeholder', 2, 2);
// function coupon_code_placeholder($label, $post){
// 	if($post->post_type == 'coupon')
// 		$label = __('Enter coupon code, lowercase and no space', 'cell-store');
// 	return $label;
// }


/* process coupon
---------------------------------------------------------------
*/

function process_coupon($coupon_code){
	$coupon_query = new WP_Query( 'post_type=coupon&meta_value='.$coupon_code );
	if ($coupon_query->post_count != 1) {
		$result['type'] = 'error';
		$result['message'] = __('Invalid coupon code', 'cell-store');
		return $result;
	} else {
		$coupon = $coupon_query->post;
		$coupon_meta = get_post_meta($coupon->ID);

		// check for coupon usage
		$coupon_usage = $coupon_meta['_coupon_usage'][0];
		$coupon_limit = $coupon_meta['_coupon_limit'][0];

		if ($coupon_usage >= $coupon_limit) {
			$result['type'] = 'error';
			$result['message'] = __('All coupon has been used', 'cell-store');
			return $result;
		}

		//check for coupon date
		$todays_date = new DateTime(date("Y-m-d"));
		$coupon_start = new DateTime($coupon_meta['_coupon_start'][0]);
		$coupon_end = new DateTime($coupon_meta['_coupon_end'][0]);

		if ($todays_date < $coupon_start) {
			$result['type'] = 'error';
			$result['message'] = __('Coupon is not published yet', 'cell-store');
			return $result;
		} elseif ($todays_date > $coupon_end) {
			$result['type'] = 'error';
			$result['message'] = __('Coupon has expired', 'cell-store');
			return $result;
		}

		// coupon data
		$valid_coupon['ID'] = $coupon->ID;
		$valid_coupon['name'] = $coupon->post_title;
		$valid_coupon['limit'] = $coupon_limit;
		$valid_coupon['usage'] = $coupon_usage;
		$valid_coupon['excerpt'] = $coupon->post_excerpt;

		//check for coupon discount
		if ($coupon_meta['_use_discount'][0]) {
			$valid_coupon['discount'] = true;
			$valid_coupon['discount-value'] = $coupon_meta['_discount_value'][0];
		}

		//check for free shipping
		if ($coupon_meta['_use_free_shipping'][0]) {
			$valid_coupon['free-shipping'] = true;
			$valid_coupon['free-shipping-area'] = $coupon_meta['_area_limit'][0];
			$valid_coupon['free-shipping-area-name'] = get_the_title($coupon_meta['_area_limit'][0]);
		}

		$_SESSION['shopping-cart']['coupon'] = $valid_coupon;

		$result['type'] = 'success';
		$result['message'] = __('Coupon registered', 'cell-store');
		return $result;

	}
};

?>