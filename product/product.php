<?php

include_once ('ajax.php');

/* Post Types
--------------------------------------------------------------
*/

add_action('init', 'product_post_type', 0 );
function product_post_type() {

	global $cell_store_option;
	$product_slug = $cell_store_option['product']['slug'];
	
	$product_labels = array(
		'name' => _x('Product', 'post type general name'),
		'singular_name' => _x('Product', 'post type singular name'),
		'add_new' => _x('Add New', 'investment'),
		'add_new_item' => __('Add New Product','cell-store'),
		'edit_item' => __('Edit Product','cell-store'),
		'new_item' => __('New Product','cell-store'),
		'view_item' => __('View Product','cell-store'),
		'search_items' => __('Search Product','cell-store'),
		'not_found' =>  __('No product found','cell-store'),
		'not_found_in_trash' => __('No product found in Trash','cell-store'), 
		'parent_item_colon' => '',
		'menu_name' => __('Product','cell-store')
	);
	$product_args = array(
		'labels' => $product_labels,
		'public' => true,
		'can_export' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => array( 'slug' => $product_slug ),
		'capability_type' => 'post',
		'map_meta_cap' => true,
		'has_archive' => true, 
		'hierarchical' => false,
		'menu_position' => 6,
		'show_in_nav_menus' => false,
		'supports' => array('title','editor','thumbnail','comments')
	); 

	register_post_type('product',$product_args);
}

/* metabox 
---------------------------------------------------------------
*/

$product_metabox = new WPAlchemy_MetaBox(array(
	'id' => '_product_meta',
	'title' => __('Product Details','cell-store'),
	'types' => array('product'), // added only for pages and to custom post type "events"
	'context' => 'normal', // same as above, defaults to "normal"
	'priority' => 'high', // same as above, defaults to "high"
	'template' => CELL_STORE_PATH . '/product/metabox.php',
	'prefix' =>'_',
	'mode' => WPALCHEMY_MODE_EXTRACT
));

/* Custom columns for the post types
--------------------------------------------------------------
*/

add_filter('manage_edit-product_columns', 'product_columns');
function product_columns($columns){
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'product-thumbnail' => __('Thumbnail','cell-store'),
		'title' => __('Title','cell-store'),
		'product_price' => __('Price','cell-store'),
		'product_stock' => __('Stock','cell-store'),
		'collection' => __('Collection','cell-store'),
		'product_category' => __('Product Category','cell-store'),
		'product_tag' => __('Product Tag','cell-store'),
		'date' => __('Date','cell-store'),
	);
	return $columns;
}

add_action('manage_posts_custom_column',  'product_custom_column');
function product_custom_column($column){
	global $post;
	$product_meta = get_post_meta($post->ID);
	switch ($column) {
		case 'product-thumbnail':

			the_post_thumbnail(50);
			break;
		case 'product_price':
			if (isset($product_meta['_price'][0])) {
				echo currency_format($product_meta['_price'][0]);
			} else {
				echo 'n/a';
			}
			if (isset($product_meta['_use_discount'][0])) {
				$today_date = new DateTime(date("Y-m-d"));
				$discount_end = new DateTime($product_meta['_discount_end'][0]);
				$discount_start = new DateTime($product_meta['_discount_start'][0]);
				if ($today_date > $discount_start && $today_date < $discount_end) {
					printf(__('<br/>discount %1$s <br/>until  %2$s', 'cell-store'), $product_meta['_discount_value'][0], $product_meta['_discount_start'][0]);
				} else {
					echo __('<br/>discount ended', 'cell-store');
				}
			}
			break;
		case 'product_stock':
			if (isset($product_meta['_use_stock_management'][0])) {
				if (isset($product_meta['_use_variations'][0])) {
					$variations = unserialize($product_meta['_variant'][0]);
					$i = 0;
					foreach ($variations as $variant) {
						if ($i >= 1) { echo '<br/>'; };
						echo $variant['title'].' : '.$variant['stock'].' unit';
						$i ++;
					}
				} else {
					if (isset($product_meta['_stock'][0])) {
						echo $product_meta['_stock'][0] .' unit ';
					} else {
						echo __('out of stock', 'cell-store');
					}
					
				}
			} else {
				echo __('unmanaged', 'cell-store');
			}

			break;
		case 'product_category':
			echo get_the_term_list($post->ID, 'product-category', '', ', ','');
			break;
		case 'collection':
			echo get_the_term_list($post->ID, 'collection', '', ', ','');
			break;
		case 'product_tag':
			echo get_the_term_list($post->ID, 'product-tag', '', ', ','');
			break;
	}
}

add_filter( 'manage_edit-product_sortable_columns', 'product_column_register_sortable' );
function product_column_register_sortable( $columns ) {
	$columns['product_price'] = 'product_price';
	return $columns;
}

add_filter( 'request', 'product_column_orderby' );
function product_column_orderby( $vars ) {
	if ( isset( $vars['orderby'] ) && 'product_price' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => '_price',
			'orderby' => 'meta_value_num'
		) );
	}
	return $vars;
}

add_action( 'restrict_manage_posts', 'product_restrict_manage_posts' );
function product_restrict_manage_posts() {

	global $typenow;
	if ($typenow == 'product') {

		$filters = array('product-category','product-tag');

		foreach ($filters as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);
			echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
			echo "<option value=''>Show All $tax_name</option>";
			if (isset($_GET[$tax_slug])) {
				$current_filter = $_GET[$tax_slug];
			}
			if (isset($terms)) {
				foreach ($terms as $term) {
					echo '<option value='. $term->slug. selected($current_filter, $term->slug, false) .'>' . $term->name .' (' . $term->count .')</option>';
				}
			}
			echo "</select>";
		}
	}
}

/* input title
---------------------------------------------------------------
*/
add_filter('enter_title_here', 'product_title_placeholder', 2, 2);
function product_title_placeholder($label, $post){
	if($post->post_type == 'product')
		$label = __('Enter product name here', 'cell-store');
	return $label;
}

/* add quick buy in product-single 
---------------------------------------------------------------
*/

// add_filter('the_content', 'test_shopping_cart');

// function test_shopping_cart($post_content){
// 	global $post;
// 	if (is_single() && $post->post_type == 'product') {
// 		return product_detail().$post_content;
// 	}else{
// 		return $post_content;
// 	}
// }


/* add product detail template 
---------------------------------------------------------------
*/
function product_detail(){
	ob_start();
		include('template/product-detail.php');
		$product_detail = ob_get_contents();
	ob_end_clean();
	return $product_detail;
}


/* add out-of-stock class in archive / single 
---------------------------------------------------------------
*/

add_filter('post_class', 'out_of_stock');
function out_of_stock($classes) {
	global $post;
	$product_meta = get_post_meta($post->ID);
	if (isset($product_meta['_price'])) {
		$price = $product_meta['_price'][0];
	}
	
	// print_r($price);
	if (isset($product_meta['_use_stock_management']) && $product_meta['_use_stock_management'][0] == 1) {
		$use_stock_management = true;
	} else {
		$use_stock_management = false;
	}

	if (!isset($price)) {
		$classes[] = 'out-of-stock';
		return $classes;
	}
	
	if (isset($product_meta['_use_stock_management'])) {
		if (isset($product_meta['_use_variations'][0])) {
			$variations = unserialize($product_meta['_variant'][0]);
			$variant_stock = false;
			foreach ($variations as $variant) {
				if ($variant['stock']) {
					$variant_stock = true;
				}
			}
			if (!$variant_stock) {
				$classes[] = 'out-of-stock';
				return $classes;
			} else {
				return $classes;
			}
		} else {
			if (!isset($product_meta['_stock'][0])) {
				$classes[] = 'out-of-stock';
				return $classes;
			} else {
				return $classes;
			}
		}
	} else {
		$classes[] = 'unmanaged';
		return $classes;
	}
}

/* load product form
---------------------------------------------------------------
*/
function cell_product_form(){

	$template = 'template/product-form.php';
	if (locate_template('cell-store/product-form.php') != '') {
		$template = get_stylesheet_directory().'/cell-store/product-form.php';
	}
	ob_start();
		include($template);
		$product_form_content = ob_get_contents();
	ob_end_clean();
	print $product_form_content;	
}

/* load fancy script 
---------------------------------------------------------------
*/
add_action('template_redirect', 'cell_product_script');
function cell_product_script(){
	if (is_singular('product')){
		wp_enqueue_script('product-stock', plugins_url().'/cell-store/js/product-stock.js', array('jquery'), '0.1', true);
		wp_localize_script( 'product-stock', 'global', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}	
}


/* get discounted price 
---------------------------------------------------------------
*/


function cs_get_discount_price($post_id = 0){
	global $post;
	if (isset($post->ID) && $post->post_type == 'product') {
		$product_id = $post->ID;
	} else if ($post_id != 0 && is_numeric($post_id)) {
		$product_id = $post_id;
	}

	if (isset($product_id)) {
		$product_meta = get_post_meta( $product_id );
		$price = $product_meta['_price'][0];

		if (isset($product_meta['_use_discount'][0]) && isset($product_meta['_discount_value'][0])) {
			$use_discount = true;
			$discount_value = $product_meta['_discount_value'][0];
			$todays_date = new DateTime(date("Y-m-d"));
			$discount_start = new DateTime($product_meta['_discount_start'][0]);
			$discount_end = new DateTime($product_meta['_discount_end'][0]);
			if ($todays_date >= $discount_start && $todays_date <= $discount_end) {
				$valid_discount = true;
			}
			if(isset($valid_discount)){
				if (stripos($discount_value, '%')) {
					$discount_percentage = str_replace('%', '', $discount_value);
					$price_after_discount = $price * (100 - $discount_percentage) / 100;
					$discount_percentage = true;
				} else {
					$price_after_discount = $product_meta['_discount_value'][0];
				}
				return $price_after_discount;
			} else {
				return false;
			}
		} else {
			return false;
		}		
	} else {
		return false;
	}
}

?>