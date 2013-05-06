<?php
/* ajax : custom function to detect wheter a request is made by ajax or not
---------------------------------------------------------------
*/

if (!function_exists('ajax_request')) {
	function ajax_request(){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			return true;
		} else {
			return false;
		}
	}
}

/* ajax : custom function to create an ajax response or http location redirect
---------------------------------------------------------------
*/

if (!function_exists('ajax_response')) {
	function ajax_response($data,$redirect = false){
			if(ajax_request()){
				$data_json = json_encode($data);
				echo $data_json;			
			} else {
				$_SESSION['global_message'][] = $data;
			}
			if ($redirect) {
				wp_redirect( $redirect );
				exit;
				die();
			}
	}	
}

if (!function_exists('the_global_message')) {
	function the_global_message(){

		global $global_message;

		if ($global_message != '' && (count($global_message) > 0)) {
			foreach ($global_message as $message){
				?>
					<div id="" class="alert alert-<?php echo $message['type'] ?>">
						<a href="" class="delete">✕</a> <span><?php echo $message['message'] ?></span>
					</div>
				<?php
			}
		}
	}	
}

/* wp-admin : remove admin bar
---------------------------------------------------------------
*/

// add_action('init', 'remove_admin_bar');

// function remove_admin_bar() {
// 	if (!current_user_can('administrator') && !is_admin()) {
// 		show_admin_bar(false);
// 	}
// }



if (function_exists('admin_bar_for_admin_only')) {

	add_filter( 'show_admin_bar' , 'admin_bar_for_admin_only');

	function admin_bar_for_admin_only(){
		if (!current_user_can('administrator') && !is_admin()) {
			return false;
		} else {
			return true;
		}
	}
}



/* wp-admin : disable non administrator to access wp-admin
---------------------------------------------------------------
*/
function my_admin_init(){
	if( !defined('DOING_AJAX') && !current_user_can('administrator') ){
		wp_redirect( home_url() );
		exit();
	}
}
add_action('admin_init','my_admin_init');


/* wp-admin : add script in wp-admin 
---------------------------------------------------------------
*/
add_action('admin_print_scripts', 'add_script'); //dion
function add_script() { //dion
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script('admin-cellscript', plugins_url() . '/cell-store/js/admin-script.js', array('jquery'),'1.0',true);
}


/* wp-admin : add style in wp-admin 
---------------------------------------------------------------
*/
add_action('admin_enqueue_scripts', 'add_style');
function add_style() {
	wp_enqueue_style('smoothness', plugins_url() . '/cell-store/css/smoothness/jquery-ui-1.9.0.custom.min.css');
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
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
}

if ( function_exists( 'add_image_size' ) ) { 
	add_image_size( '50', 50, 50, true );
}

/* check if is descendant 
---------------------------------------------------------------
*/

if(function_exists('is_descendant')){
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
if (function_exists('get_id_by_slug')) {
	function get_id_by_slug($post_slug,$post_type){
		global $wpdb;
		$post_id = $wpdb->get_var(
			"	SELECT ID
				FROM wp_posts
				WHERE post_name = '$post_slug'
				AND post_type ='$post_type'
				LIMIT 0,1
			");
		return $post_id;
	}
}

?>