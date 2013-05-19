<?php
// include_once ('ajax.php');
// include_once ('admin-page.php');

/* Post Types
--------------------------------------------------------------
*/

add_action('init', 'transaction_post_type');

function transaction_post_type() {
	
  $transaction_labels = array(
		'name' => _x('Transaction', 'post type general name'),
		'singular_name' => _x('Transaction', 'post type singular name'),
		'add_new' => _x('Add New', 'investment'),
		'add_new_item' => __('Add New Transaction', 'cell-store'),
		'edit_item' => __('Edit Transaction', 'cell-store'),
		'new_item' => __('New Transaction', 'cell-store'),
		'view_item' => __('View Transaction', 'cell-store'),
		'search_items' => __('Search Transaction', 'cell-store'),
		'not_found' =>  __('No transaction found', 'cell-store'),
		'not_found_in_trash' => __('No transaction found in Trash', 'cell-store'), 
		'parent_item_colon' => '',
		'menu_name' => __('Transaction', 'cell-store')
	);
	$transaction_args = array(
		'labels' => $transaction_labels,
		'public' => true,
		'publicly_queryable' => false,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => true, 
		'hierarchical' => false,
		'menu_position' => 7,
		'supports' => array('title')
	); 

	register_post_type('transaction',$transaction_args);
  
}

/* metabox 
---------------------------------------------------------------
*/

$transaction_metabox = new WPAlchemy_MetaBox(array(
	'id' => '_transaction_meta',
	'title' => __('Transaction Status', 'cell-store'),
	'types' => array('transaction'), // added only for pages and to custom post type "events"
	'context' => 'normal', // same as above, defaults to "normal"
	'priority' => 'high', // same as above, defaults to "high"
	'template' => CELL_STORE_PATH . '/transaction/metabox.php',
	'prefix' => '_',
	'mode' => WPALCHEMY_MODE_EXTRACT
));

$transaction_details = new WPAlchemy_MetaBox(array(
	'id' => '_transaction_details',
	'title' => __('Transaction Details', 'cell-store'),
	'types' => array('transaction'), // added only for pages and to custom post type "events"
	'context' => 'normal', // same as above, defaults to "normal"
	'priority' => 'high', // same as above, defaults to "high"
	'template' => CELL_STORE_PATH . '/transaction/metabox-details.php'
));

$transaction_history = new WPAlchemy_MetaBox(array(
	'id' => '_transaction_history',
	'title' => __('Transaction History', 'cell-store'),
	'types' => array('transaction'), // added only for pages and to custom post type "events"
	'context' => 'normal', // same as above, defaults to "normal"
	'priority' => 'low', // same as above, defaults to "high"
	'template' => CELL_STORE_PATH . '/transaction/metabox-history.php'
));

/* Custom columns for the post types
--------------------------------------------------------------
*/

add_filter('manage_edit-transaction_columns', 'transaction_columns');
function transaction_columns($columns){
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __('Title', 'cell-store'),
		'transaction_code' => __('Transaction Code', 'cell-store'),
		'transaction_status' => __('Transaction Status', 'cell-store'),
		'date' => __('Date', 'cell-store'),
	);
	return $columns;
}


add_action('manage_posts_custom_column',  'transaction_custom_column');
function transaction_custom_column($column){
	global $post;
	switch ($column) {
		case 'transaction_status':
			echo ucfirst(get_post_meta($post->ID, '_transaction_status', true));
			break;
		case 'transaction_code':
			echo $post->post_name;
			break;
	}
}

add_filter( 'manage_edit-transaction_sortable_columns', 'transaction_column_register_sortable' );
function transaction_column_register_sortable( $columns ) {
	$columns['transaction_status'] = 'transaction_status';
	$columns['transaction_code'] = 'transaction_code';
	return $columns;
}

add_filter( 'request', 'status_column_orderby' );
function status_column_orderby( $vars ) {
	if ( isset( $vars['orderby'] ) && 'transaction_status' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => '_transaction_status',
			'orderby' => 'meta_value'
		) );
	}
	if ( isset( $vars['orderby'] ) && 'transaction_code' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			// 'meta_key' => '_transaction_status',
			'orderby' => 'name'
		) );
	}
	return $vars;
}

/* Delete hit and run by cron 
---------------------------------------------------------------
*/
if ( ! wp_next_scheduled( 'daily_transaction_check_1' ) ) {
  wp_schedule_event( time(), 'daily', 'daily_transaction_check_1' );
}
add_action( 'daily_transaction_check_1', 'delete_hit_and_run' );

add_action('init', 'delete_hit_and_run');

function delete_hit_and_run(){
	$args = array(
		'post_type' => 'transaction',
		'nopaging' => true,
		'meta_query' => array(
			array(
				'key' => '_transaction_status',
				'value' => 'pending'
			)
		)
	);

	$time = current_time('mysql');

	add_filter( 'posts_where', 'filter_where' );
	$query = new WP_Query( $args );
	remove_filter( 'posts_where', 'filter_where' );

	if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
		global $post;
		setup_postdata($query->post);

		cell_restore_stock($post->ID);

		$test = update_post_meta($post->ID, '_transaction_status', 'canceled');

		$comment = array(
			'comment_post_ID' => $post->ID,
			'comment_author' => 'cell-store System',
			'comment_author_email' => get_bloginfo('admin_email'),
			'comment_content' => __('Transaction canceled due to no payment confirmation within 3 days', 'cell-store'),
			'user_id' => 0,
			'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
			'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
			'comment_date' => $time,
			'comment_approved' => 1,
		);
		wp_insert_comment($comment);
	endwhile;
	endif;


}

function filter_where( $where = '' ) {
	global $cell_store_option;
	$limit = $cell_store_option['shopping']['cancelation-due'];

	// posts  3 days old
	$where .= " AND post_date <= '" . date('Y-m-d', strtotime('-'.$limit.' days')) . "'";
	return $where;
}

function cell_restore_stock($post_id){

		$transaction_meta = get_post_meta($post_id);
		$items = unserialize($transaction_meta['_items'][0]);

		// restore the stock if stock managed
		foreach ($items as $item) {
			if ($item['stock-manage']) {
				$product_meta =get_post_meta($item['ID']);
				if (isset($product_meta['_use_variations'][0])) {
					$variations = unserialize($product_meta['_variant'][0]);

					foreach ($variations as $key =>$variation) {
						if ($variation['title'] == $item['option']) {
							$variations[$key]['stock'] = $variation['stock'] + $item['quantity'];
							update_post_meta($item['ID'], '_variant', $variations);
						}
					}
				} else {
					$new_stock = $product_meta['_stock'][0] + $item['quantity'];
					update_post_meta($item['ID'], '_stock', $new_stock);
				}
			}
		}
}
/* write transaction log on status change 
---------------------------------------------------------------
*/


add_action('save_post','write_transaction_log');

function write_transaction_log($post_id){
	global $post, $current_user;

	if (!isset($post)) {
		$post = get_post($post_id);
	}
	// print_r($post);
	// print_r($post_id);
	// wp_die();

	if ($post->post_type == 'transaction' && $post->post_status == 'publish') {
		// global $current_user;
		$old_status = get_post_meta($post_id, '_transaction_status', true);
		if (isset($_REQUEST['_transaction_meta']['transaction_status'])) {
			$new_status = $_REQUEST['_transaction_meta']['transaction_status'];
		}
		
		if (isset($old_status) && isset($new_status) && ($old_status != $new_status)) {
			$time = current_time('mysql');

			$comment_content = sprintf(__('Transaction status changed from %1$s to %2$s by %3$s', 'cell-store'), $old_status, $new_status, $current_user->display_name);
			
			$comment = array(
				'comment_post_ID' => $post->ID,
				'comment_author' => 'cell-store System',
				'comment_author_email' => $current_user->email,
				'comment_content' => $comment_content,
				'user_id' => $current_user->ID,
				'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
				'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
				'comment_date' => $time,
				'comment_approved' => 1,
			);
			wp_insert_comment($comment);

			if ($new_status == 'canceled') {
				cell_restore_stock($post_id);
			}
		}

	}

}


/* send email on status change 
---------------------------------------------------------------
*/

add_action('save_post', 'send_transaction_notification');

function send_transaction_notification($post_id){
	global $post;
	$tracking_code = get_post_meta($post_id, '_tracking_code', true);
	$tracking_code_sent = get_post_meta($post_id, '_tracking_code_sent', true);
	if ($tracking_code && !$tracking_code_sent) {

		$billing = get_post_meta($post_id, '_billing', true);
		$buyer = $billing['first-name'];
		$buyer_email = $billing['email'];
		$slug = $post->post_name;

		// send email to billing address
		$mail_title = __('Shipping Code', 'cell-store');
		$message = sprintf( __('<p> Dear %s </p>', 'cell-store'), $buyer );
		$message .= sprintf( __('<p> Thank you for shopping at %s </p>', 'cell-store'), get_bloginfo('name') );
		$message .= sprintf( __('<p> Your order has been shipped to shipping company today </p>', 'cell-store'), $slug );
		$message .= sprintf( __('<p> Please use the the code : %s  to track your order shipping status </p>', 'cell-store'), $tracking_code );
		$message .= sprintf( __('<p> To check the status of your order at any time, you can log into your account at %s </p>', 'cell-store'), get_bloginfo('url') );
		$message .= sprintf( __('<p> If you have any questions, please contact us at %1$s or SMS us at +6287824039090. Please make sure to reference your order number : %2$s </p>', 'cell-store'), get_bloginfo('admin_email'), $slug );
		$message .= sprintf( __('<p> Once again, thanks for shopping with %s </p>', 'cell-store'), get_bloginfo('name') );
		$message .= sprintf( __('<p> Sincerely, <br/> %s  Team </p>', 'cell-store'), get_bloginfo('name') );

		if (function_exists('cell_email')) {
			cell_email($buyer_email, $mail_title, $message);
		} else {
			wp_mail($buyer_email, $mail_title, strip_tags($message));
		}

		add_post_meta($post_id, '_tracking_code_sent', 1, true);
	}
}

/* check my order
---------------------------------------------------------------
*/


add_shortcode( 'cell-my-order', 'cell_my_order_content' );

function cell_my_order_content(){

	// check if current theme has a replacement template
	if ( '' != locate_template( 'store-my-order.php' ) ) {
		$current_theme = wp_get_theme();
		$template = $current_theme->theme_root.'/'.$current_theme->stylesheet.'/store-my-order.php';
		return get_template_file($template);
	} else{
		return cell_my_order();
	}
}

function cell_my_order(){
	ob_start();
		include('template/my-order.php');
		$my_order_content = ob_get_contents();
	ob_end_clean();
	echo $my_order_content;
	
}

?>
