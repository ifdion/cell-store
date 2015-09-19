<?php

/* ajax : custom function to detect wheter a request is made by ajax or not
---------------------------------------------------------------
*/

// if (!function_exists('ajax_request')) {
// 	function ajax_request(){
// 		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
// 			return true;
// 		} else {
// 			return false;
// 		}
// 	}
// }

/* ajax : custom function to create an ajax response or http location redirect
---------------------------------------------------------------
*/

// if (!function_exists('ajax_response')) {
// 	function ajax_response($data,$redirect = false){
// 		if(ajax_request()){
// 			$data_json = json_encode($data);
// 			echo $data_json;			
// 		} else {
// 			$_SESSION['global_message'][] = $data;
// 		}
// 		if ($redirect) {
// 			wp_redirect( $redirect );
// 			exit;
// 			die();
// 		}
// 	}	
// }

/* global message 
---------------------------------------------------------------
*/

// add_action( 'init', 'setup_global_message');

// if (!function_exists('setup_global_message')) {
// 	function setup_global_message(){
// 		global $global_message;
// 		if ( isset( $_SESSION['global_message'] ) ){
// 			$global_message = $_SESSION['global_message'];
// 			unset( $_SESSION['global_message'] );
// 		}
// 	}
// }

// if (!function_exists('the_global_message')) {
// 	function the_global_message(){
// 		global $global_message;
// 		if ($global_message != '' && (count($global_message) > 0)) {
// 			foreach ($global_message as $message){
// 				? >
// 					<div id="" class="alert alert-<?php echo $message['type'].' '.$message['type'] ? >">
// 						<a href="" class="delete">✕</a> <span><?php echo $message['message'] ? ></span>
// 					</div>
// 				< ?php
// 			}
// 		}
// 		$global_message = false;
// 	}	
// }


/* get template file 
---------------------------------------------------------------
*/
// if (!function_exists('get_template_file')) {
// 	function get_template_file($template){
// 		ob_start();
// 			include($template);
// 			$shopping_cart_content = ob_get_contents();
// 		ob_end_clean();
// 		return $shopping_cart_content;
// 	}
// }


/* admin_bar_for_admin_only
---------------------------------------------------------------
*/

// if (function_exists('admin_bar_for_admin_only')) {
// 	add_filter( 'show_admin_bar' , 'admin_bar_for_admin_only');
// 	function admin_bar_for_admin_only(){
// 		if (!current_user_can('administrator') && !is_admin()) {
// 			return false;
// 		} else {
// 			return true;
// 		}
// 	}
// }


/* wp-admin : add script in wp-admin 
---------------------------------------------------------------
*/

if (!function_exists('cell_store_admin_script')) {
	add_action('admin_print_scripts', 'cell_store_admin_script'); //dion

	function cell_store_admin_script() { //dion
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script('admin-cellscript', plugins_url() . '/cell-store/js/admin-script.js', array('jquery'),'1.0',true);
	}
}



/* wp-admin : add style in wp-admin 
---------------------------------------------------------------
*/

if (!function_exists('cell_store_admin_style')) {
	add_action('admin_enqueue_scripts', 'cell_store_admin_style');
	function cell_store_admin_style() {
		wp_enqueue_style('smoothness', plugins_url() . '/cell-store/css/smoothness/jquery-ui-1.9.0.custom.min.css');
	}
}


/* wp-admin : Adding Custom Post Type and Custom Taxonomy to Right Now Admin Widget
--------------------------------------------------------------
*/
add_action( 'right_now_content_table_end' , 'ucc_right_now_content_table_end' );

function ucc_right_now_content_table_end() {
	$args = array(
		'public' => true ,
		'_builtin' => false
	);
	$output = 'object';
	$operator = 'and';
	
	$post_types = get_post_types( $args , $output , $operator );
	
	foreach( $post_types as $post_type ) {
		$num_posts = wp_count_posts( $post_type->name );
		$num = number_format_i18n( $num_posts->publish );
		$text = _n( $post_type->labels->singular_name, $post_type->labels->name , intval( $num_posts->publish ) );
		if ( current_user_can( 'edit_posts' ) ) {
			$num = "<a href='edit.php?post_type=$post_type->name'>$num</a>";
			$text = "<a href='edit.php?post_type=$post_type->name'>$text</a>";
		}
		echo '<tr><td class="first b b-' . $post_type->name . '">' . $num . '</td>';
		echo '<td class="t ' . $post_type->name . '">' . $text . '</td></tr>';
	}
	
	$taxonomies = get_taxonomies( $args , $output , $operator );
	
	foreach( $taxonomies as $taxonomy ) {
		$num_terms  = wp_count_terms( $taxonomy->name );
		$num = number_format_i18n( $num_terms );
		$text = _n( $taxonomy->labels->singular_name, $taxonomy->labels->name , intval( $num_terms ) );
		if ( current_user_can( 'manage_categories' ) ) {
			$num = "<a href='edit-tags.php?taxonomy=$taxonomy->name'>$num</a>";
			$text = "<a href='edit-tags.php?taxonomy=$taxonomy->name'>$text</a>";
		}
		echo '<tr><td class="first b b-' . $taxonomy->name . '">' . $num . '</td>';
		echo '<td class="t ' . $taxonomy->name . '">' . $text . '</td></tr>';
	}
}


/* wp-admin : add small image  size for admin table
---------------------------------------------------------------
*/
if (function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
}

if (function_exists( 'add_image_size' ) ) { 
	add_image_size( '50', 50, 50, true );
}

/* check if is descendant 
---------------------------------------------------------------
*/

if(!function_exists('is_descendant')){
	function is_descendant( $page, $ancestor = false ) {
		if( !is_object( $page ) ) {
			$page = intval( $page );
			$page = get_post( $page );
		}
		if( is_object( $page ) ) {
			if( isset( $page->ancestors ) && !empty( $page->ancestors ) ) {
				if( !$ancestor ){
					return true;
				}elseif ( in_array( $ancestor, $page->ancestors ) ){
					return true;
				}
			}
		}
		return false;
	}
}

/* get id from slug 
---------------------------------------------------------------
*/
// if (!function_exists('get_id_by_slug')) {
// 	function get_id_by_slug($post_slug,$post_type){
// 		global $wpdb;
// 		$post_id = $wpdb->get_var(
// 			"	SELECT ID
// 				FROM wp_posts
// 				WHERE post_name = '$post_slug'
// 				AND post_type ='$post_type'
// 				LIMIT 0,1
// 			");
// 		return $post_id;
// 	}
// }

/* setup currency format 
---------------------------------------------------------------
*/

if (!function_exists('currency_format')) {
	function currency_format($number){
		global $cell_store_option;
		$currency = $cell_store_option['currency']['symbol'];
		$thousand_sep = $cell_store_option['currency']['thousand-separator'];
		$decimal_sep = $cell_store_option['currency']['decimal-separator'];
		$decimal_digit = $cell_store_option['currency']['decimal-digit'];
		$in_front = $cell_store_option['currency']['use-in-front'];

		if ($in_front == true) {
			$result = $currency.' '.number_format($number,$decimal_digit,$decimal_sep,$thousand_sep);
		} else {
			$result = number_format($number,$decimal_digit,$decimal_sep,$thousand_sep).' '.$currency;
		}

		return $result;

	}
}

?>