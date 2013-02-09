<?php

include_once ('ajax.php');
include_once ('front-end.php');
include_once ('admin-page.php');

/* write the user meta on edit screen
---------------------------------------------------------------
*/

add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );

function my_show_extra_profile_fields( $user ) {
	include('template/admin-user-detail.php');
}

/* save the user meta on edit screen
---------------------------------------------------------------
*/

add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

function my_save_extra_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;
	update_user_meta( $user_id, 'telephone', $_POST['telephone'] );
	update_user_meta( $user_id, 'company', $_POST['company'] );
	update_user_meta( $user_id, 'address', $_POST['address'] );
	update_user_meta( $user_id, 'district', $_POST['district'] );
	update_user_meta( $user_id, 'city', $_POST['city'] );
	update_user_meta( $user_id, 'postcode', $_POST['postcode'] );
	update_user_meta( $user_id, 'country', $_POST['country'] );
	update_user_meta( $user_id, 'province', $_POST['province'] );
	update_user_meta( $user_id, 'shipping-first-name', $_POST['shipping-first-name'] );
	update_user_meta( $user_id, 'shipping-last-name', $_POST['shipping-last-name'] );
	update_user_meta( $user_id, 'shipping-telephone', $_POST['shipping-telephone'] );
	update_user_meta( $user_id, 'shipping-company', $_POST['shipping-company'] );
	update_user_meta( $user_id, 'shipping-address', $_POST['shipping-address'] );
	update_user_meta( $user_id, 'shipping-city', $_POST['shipping-city'] );
	update_user_meta( $user_id, 'shipping-district', $_POST['shipping-district'] );
	update_user_meta( $user_id, 'shipping-postcode', $_POST['shipping-postcode'] );
	update_user_meta( $user_id, 'shipping-country', $_POST['shipping-country'] );
	update_user_meta( $user_id, 'shipping-province', $_POST['shipping-province'] );
}

/* global registration fields 
---------------------------------------------------------------
*/

$registration_field = array(
	'username' => array( // the key will be used in the label for attribute and the input name
		'title' => __('Username', 'cell-store'), // the label text
		'type' => 'text', // the input type or textarea
		'required' => 1, // is it required? 1 or 0
		'required_text' => __('(required)', 'cell-store'),
		'note' =>__('Use 3 - 15 character lowercase, numbers and \'- \' only', 'cell-store') // does it need a helper note, use inline html tags only
		),
	'email' => array(
		'title' => __('Email', 'cell-store'),
		'type' => 'text',
		'required' => 1,
		'note' => ''
		),
	'password' => array(
		'title' => __('Password', 'cell-store'),
		'type' => 'password',
		'required' => 1,
		'note' => ''
		)
	);


/* load fancy script 
---------------------------------------------------------------
*/
add_action('template_redirect', 'cell_profile_script');
function cell_profile_script(){
	if (is_page('profile')){
		wp_enqueue_script('address', plugins_url().'/cell-store/js/address.js', array('jquery'), '0.1', true);
		wp_localize_script( 'address', 'global', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}	
}

/* Global Administration Edit User field 
---------------------------------------------------------------
*/

$admin_edit_user_field = array(

	);


/* Global Edit User Field
---------------------------------------------------------------
*/

$admin_edit_user_field = array(

	);

?>