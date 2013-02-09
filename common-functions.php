<?php
/* ajax : custom function to detect wheter a request is made by ajax or not
---------------------------------------------------------------
*/

function ajax_request(){
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		$result = json_encode($result);
		return true;
	}
	else {
		return false;
	}
}

/* ajax : custom function to create an ajax response or http location redirect
---------------------------------------------------------------
*/
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
		}
}

/* ajax : handle multiple file upload array
---------------------------------------------------------------
*/
function rearrange( $arr ){
	foreach( $arr as $key => $all ){
		foreach( $all as $i => $val ){
			$new[$i][$key] = $val;    
		}    
	}
	return $new;
}

/* ajax : insert uploaded file as attachment
---------------------------------------------------------------
*/

function attach_uploads($uploads,$post_id = 0){
	$files = rearrange($uploads);
	if($files[0]['name']==''){
		return false;	
	}
	foreach($files as $file){
		$upload_file = wp_handle_upload( $file, array('test_form' => false) );
		$attachment = array(
		'post_mime_type' => $upload_file['type'],
		'post_title' => preg_replace('/\.[^.]+$/', '', basename($upload_file['file'])),
		'post_content' => '',
		'post_status' => 'inherit'
		);
		$attach_id = wp_insert_attachment( $attachment, $upload_file['file'], $post_id );
		$attach_array[] = $attach_id;
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		$attach_data = wp_generate_attachment_metadata( $attach_id, $upload_file['file'] );
		wp_update_attachment_metadata( $attach_id, $attach_data );
	}
	return $attach_array;
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
	wp_enqueue_script( 'suggest' );
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

/* comment : add ajax comment
---------------------------------------------------------------
*/
add_action('wp_ajax_add_comment', 'process_add_comment');

function process_add_comment() {
	global $current_user;
	if ( empty($_POST) || !wp_verify_nonce($_POST[$current_user->user_login],'add_comment') ) {
		echo 'You targeted the right function, but sorry, your nonce did not verify.';
		die();
	} else {

		$return = $_POST['_wp_http_referer'];
		$post_ID = $_POST['post_id'];
		$comment_author = $current_user->display_name;
		$comment_email = $current_user->user_email;
		$comment_author_id = $current_user->ID;
		$comment_author_url = get_author_posts_url( $current_user->ID );
		$comment_author_IP = $_SERVER['REMOTE_ADDR'];
		$comment_agent = $_SERVER['HTTP_USER_AGENT'];
		$comment_content = $_POST['comment-content'];

		$comment_data = array(
			'comment_post_ID' => $post_ID,
			'comment_author_url' => $comment_author_url,
			'comment_author' => $comment_author,
			'comment_author_email' => $comment_email,
			'comment_author_url' => $comment_author_url,
			'comment_content' => $comment_content,
			'comment_author_IP' => $comment_author_IP,
			'user_id' => $comment_author_id,
			'comment_agent' => $comment_agent,
			'comment_date' => date('Y-m-d H:i:s'),
			'comment_date_gmt' => date('Y-m-d H:i:s'),
			'comment_approved' => 1,
		);

		$comment_id = wp_insert_comment($comment_data);

		wp_redirect($return.'#li-comment-'.$comment_id);
		exit;

		die();
	}
}

/* comment : edit comment
---------------------------------------------------------------
*/

add_action('wp_ajax_edit_comment', 'process_edit_comment');

function process_edit_comment() {
	global $current_user;
	if ( empty($_POST) || !wp_verify_nonce($_POST[$current_user->user_login],'edit_comment') ) {
		echo 'You targeted the right function, but sorry, your nonce did not verify.';
		die();
	} else {
		$return = $_POST['_wp_http_referer'];
		$comment_ID = $_POST['comment_id'];
		$comment_content = $_POST['comment'];
		$parent_ID = $_POST['parent_id'];
		$return = get_permalink($parent_ID).'#comment-'.$comment_ID;
		$comment_data = array(
			'comment_ID' => $comment_ID,
			'comment_content' => $comment_content
		);
		$comment_id = wp_update_comment($comment_data);

		wp_redirect($return);
		exit;

		die();
	}
}


/* admin : datepicker 
---------------------------------------------------------------
*/
// add_action('admin_init', 'add_date_picker');
function add_date_picker() {
	global $post_type;

	$args = array(
		'public' => true ,
		'_builtin' => false
	);
	$output = 'object';
	$operator = 'and';
	
	$post_types = get_post_types( $args , $output , $operator );

	if (in_array($post_type, $post_types)) {
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-datepicker');
		// wp_enqueue_script('jquery-ui-datepicker', CELL_STORE_PATH . '/js/jquery.ui.datepicker.min.js', array('jquery', 'jquery-ui-core') );
		// wp_enqueue_style('jquery.ui.theme', CELL_STORE_PATH . '/css/smoothness/jquery-ui-1.9.0.custom.min.css');
	}
}


/* check if is descendant 
---------------------------------------------------------------
*/

function is_descendant( $page, $ancestor = false ) {
	if( !is_object( $page ) ) {
		$page = intval( $page );
		$page = get_post( &$page );
	}
	if( is_object( $page ) ) {
		if( isset( $page->ancestors ) && !empty( $page->ancestors ) ) {
			if( !$ancestor )
				return true;
			else if ( in_array( $ancestor, $page->ancestors ) )
				return true;
		}
	}
	return false;
}


/* get id from slug 
---------------------------------------------------------------
*/

function get_id_by_slug($post_slug,$post_type){
	global $wpdb;
	$post_id = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_name = '$post_slug'
																	AND post_type ='$post_type'
																  LIMIT 0,1 ");
	return $post_id;
}

?>