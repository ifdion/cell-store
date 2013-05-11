<?php

/* iniate cell user dependency 
---------------------------------------------------------------
*/

add_action( 'admin_notices', 'cell_login_dependency' );
function cell_login_dependency(){
	if (!class_exists('CellLogin')) {
		echo '<div class="error"><p>'.__( '<strong>Cell User</strong> plugin dependency is missing.', 'cell-store' ).'</p></div>';
	}
}

/* initiate cell user
---------------------------------------------------------------
*/

add_action( 'init','iniate_cell_login' );
function iniate_cell_login(){

	$login_args = array(
		'page' => 'login',
		'page-redirect' => 'profile',
	);

	if (class_exists('CellLogin')) {
		$login_form = new CellLogin( $login_args);
	}

	$register_form_args = array(
		'page' => 'register',
		'page-redirect' => 'profile',
		'fields' =>  array(
			'username' => array( // the key will be used in the label for attribute and the input name
				'title' => __('User Name', 'cell-user'), // the label text
				'type' => 'text', // the input type or textarea
				'required_text' => __('(required)', 'cell-user'),
				'note' =>__('Use 3 - 15 character lowercase, numbers and \'- \' only', 'cell-user') // does it need a helper note, use inline html tags only
			),
			'email' => array(
				'title' => __('Email', 'cell-user'),
				'type' => 'text',
				'note' => ''
			),
			'password' => array(
				'title' => __('Password', 'cell-user'),
				'type' => 'password',
				'note' => ''
			)
		)
	);

	if (class_exists('CellRegister')) {
		$login_form = new CellRegister($register_form_args);
	}

	$membership_fields = array(
		'account_level' => array(
			'title' => __('Account Level', 'cell-user'),
			'type' => 'text',
		),
		'account_payment' => array(
			'title' => __('Account Payment', 'cell-user'),
			'type' => 'text',
		),
	);

	$billing_fields = array(
		'first_name' => array(
			'title' => __('First Name', 'cell-user'),
			'type' => 'text',
		),
		'last_name' => array(
			'title' => __('Last Name', 'cell-user'),
			'type' => 'text',
		),
		'telephone' => array(
			'title' => __('Telephone', 'cell-user'),
			'type' => 'text',
		),
		'company' => array(
			'title' => __('Company', 'cell-user'),
			'type' => 'text',
		),
		'address' => array(
			'title' => __('Address', 'cell-user'),
			'type' => 'textarea',
		),
		'country' => array(
			'title' => __('Country', 'cell-user'),
			'type' => 'select',
			'option' => 'get_country',
			'attr' => array(
				'class' => 'select-address',
				'data-target' => 'province'
			)
		),
		'province' => array(
			'title' => __('Province', 'cell-user'),
			'type' => 'select',
			'option' => 'get_province',
			'attr' => array(
				'class' => 'select-address',
				'data-target' => 'city'
			)
		),
		'city' => array(
			'title' => __('City', 'cell-user'),
			'type' => 'select',
			'option' => 'get_city',
			'attr' => array(
				'class' => 'select-address',
				'data-target' => 'district'
			)
		),
		'district' => array(
			'title' => __('District', 'cell-user'),
			'type' => 'select',
			'option' => 'get_district',
			'attr' => array(
				'class' => 'select-address',
			)
		),
		'postcode' => array(
			'title' => __('Post Code', 'cell-user'),
			'type' => 'text',
		),
		'have-shipping' => array(
			'title' => __('Have Shipping', 'cell-user'),
			'type' => 'checkbox',
		),
	);

	$shipping_fields = array(
		'shipping-first-name' => array(
			'title' => __('Shipping First Name', 'cell-user'),
			'type' => 'text',
		),
		'shipping-last-name' => array(
			'title' => __('Shipping Last Name', 'cell-user'),
			'type' => 'text',
		),
		'shipping-email' => array(
			'title' => __('Shipping Email', 'cell-user'),
			'type' => 'text',
		),
		'shipping-telephone' => array(
			'title' => __('Shipping Telephone', 'cell-user'),
			'type' => 'text',
		),
		'shipping-company' => array(
			'title' => __('Shipping Company', 'cell-user'),
			'type' => 'text',
		),
		'shipping-address' => array(
			'title' => __('Shipping Address', 'cell-user'),
			'type' => 'textarea',
		),
		'shipping-country' => array(
			'title' => __('Shipping Country', 'cell-user'),
			'type' => 'select',
			'option' => 'get_country',
			'attr' => array(
				'class' => 'select-address',
				'data-target' => 'shipping-province'
			)
		),
		'shipping-province' => array(
			'title' => __('Shipping Province', 'cell-user'),
			'type' => 'select',
			'option' => 'get_shipping_province',
			'attr' => array(
				'class' => 'select-address',
				'data-target' => 'shipping-city'
			)
		),
		'shipping-city' => array(
			'title' => __('Shipping City', 'cell-user'),
			'type' => 'select',
			'option' => 'get_shipping_city',
			'attr' => array(
				'class' => 'select-address',
				'data-target' => 'shipping-district'
			)
		),
		'shipping-district' => array(
			'title' => __('Shipping District', 'cell-user'),
			'type' => 'select',
			'option' => 'get_shipping_district',
			'attr' => array(
				'class' => 'select-address',
			)
		),
		'shipping-postcode' => array(
			'title' => __('Shipping Post Code', 'cell-user'),
			'type' => 'text',
		),
	);
	$user_profile_args = array(
		'page' => 'profile',
		'page-redirect' => 'login',
		'include-script' => 'address',
		'fieldset' => array(
			'billing' => array(
				'title' => __('Billing', 'cell-user'),
				'class' => 'billing fieldset',
				'fields' => $billing_fields,
				'public' => true,
			),
			'shipping' => array(
				'title' => __('Shipping', 'cell-user'),
				'class' => 'shipping fieldset',
				'fields' => $shipping_fields,
				'show-on' => 'have-shipping',
				'public' => true,
			),
		),
	);

	if (class_exists('CellProfile')) {
		$profile_form = new CellProfile($user_profile_args);
	}

}

/* option functions 
---------------------------------------------------------------
*/

function get_country(){
	global  $wpdb;
	$result = array();
	$result[0] = __( 'Please select.', 'cell-store' );
	$query = $wpdb->get_results(
		"SELECT ID, post_title
		FROM $wpdb->posts
		WHERE post_status = 'publish'
		AND     post_type = 'shipping-destination'
		AND   post_parent = 0
		ORDER BY post_title
		DESC");
	foreach ($query as $key => $value) {
		$result[$value->ID] = $value->post_title;
	}
	return $result;
}

function get_province($user_id){
	if (!$user_id) {
		$result = array(__( 'Please select.', 'cell-store' ));
		return $result;
	} else {
		global $wpdb;
		$user_meta = get_user_meta( $user_id );
		$result = array();
		$result[0] = __( 'Please select.', 'cell-store' );
		
		if (isset($user_meta['country'][0]) && $user_meta['country'][0] != 0) {
			$user_country = $user_meta['country'][0];
			$query = $wpdb->get_results(
				"SELECT ID, post_title
				FROM $wpdb->posts
				WHERE post_status = 'publish'
				AND     post_type = 'shipping-destination'
				AND   post_parent = $user_country
				ORDER BY post_title
				DESC");
			foreach ($query as $key => $value) {
				$result[$value->ID] = $value->post_title;
			}
		}
		return $result;
	}
}

function get_city($user_id){
	if (!$user_id) {
		$result = array(__( 'Please select.', 'cell-store' ));
		return $result;
	} else {
		global $wpdb;
		$user_meta = get_user_meta( $user_id );
		$result = array();
		$result[0] = __( 'Please select.', 'cell-store' );
		
		if (isset($user_meta['province'][0]) && $user_meta['province'][0] != 0) {
			$user_province = $user_meta['province'][0];
			$query = $wpdb->get_results(
				"SELECT ID, post_title
				FROM $wpdb->posts
				WHERE post_status = 'publish'
				AND     post_type = 'shipping-destination'
				AND   post_parent = $user_province
				ORDER BY post_title
				DESC");
			foreach ($query as $key => $value) {
				$result[$value->ID] = $value->post_title;
			}
		}
		return $result;
	}
}

function get_district($user_id){
	if (!$user_id) {
		$result = array(__( 'Please select.', 'cell-store' ));
		return $result;
	} else {
		global $wpdb;
		$user_meta = get_user_meta( $user_id );
		$result = array();
		$result[0] = __( 'Please select.', 'cell-store' );
		
		if (isset($user_meta['city'][0]) && $user_meta['city'][0] != 0) {
			$user_city = $user_meta['city'][0];
			$query = $wpdb->get_results(
				"SELECT ID, post_title
				FROM $wpdb->posts
				WHERE post_status = 'publish'
				AND     post_type = 'shipping-destination'
				AND   post_parent = $user_city
				ORDER BY post_title
				DESC");
			foreach ($query as $key => $value) {
				$result[$value->ID] = $value->post_title;
			}
		}
		return $result;
	}
}

function get_shipping_province($user_id){
	if (!$user_id) {
		$result = array(__( 'Please select.', 'cell-store' ));
		return $result;
	} else {
		global $wpdb;
		$user_meta = get_user_meta( $user_id );
		$result = array();
		$result[0] = __( 'Please select.', 'cell-store' );
		
		if (isset($user_meta['shipping-country'][0]) && $user_meta['shipping-country'][0] != 0) {
			$user_shipping_country = $user_meta['shipping-country'][0];
			$query = $wpdb->get_results(
				"SELECT ID, post_title
				FROM $wpdb->posts
				WHERE post_status = 'publish'
				AND     post_type = 'shipping-destination'
				AND   post_parent = $user_shipping_country
				ORDER BY post_title
				DESC");
			foreach ($query as $key => $value) {
				$result[$value->ID] = $value->post_title;
			}
		}
		return $result;
	}
}

function get_shipping_city($user_id){
	if (!$user_id) {
		$result = array(__( 'Please select.', 'cell-store' ));
		return $result;
	} else {
		global $wpdb;
		$user_meta = get_user_meta( $user_id );
		$result = array();
		$result[0] = __( 'Please select.', 'cell-store' );
		
		if (isset($user_meta['shipping-province'][0]) && $user_meta['shipping-province'][0] != 0) {
			$user_shipping_province = $user_meta['shipping-province'][0];
			$query = $wpdb->get_results(
				"SELECT ID, post_title
				FROM $wpdb->posts
				WHERE post_status = 'publish'
				AND     post_type = 'shipping-destination'
				AND   post_parent = $user_shipping_province
				ORDER BY post_title
				DESC");
			foreach ($query as $key => $value) {
				$result[$value->ID] = $value->post_title;
			}
		}
		return $result;
	}
}

function get_shipping_district($user_id){
	if (!$user_id) {
		$result = array(__( 'Please select.', 'cell-store' ));
		return $result;
	} else {
		global $wpdb;
		$user_meta = get_user_meta( $user_id );
		$result = array();
		$result[0] = __( 'Please select.', 'cell-store' );
		
		if (isset($user_meta['shipping-city'][0]) && $user_meta['shipping-city'][0] != 0) {
			$user_shipping_city = $user_meta['shipping-city'][0];
			$query = $wpdb->get_results(
				"SELECT ID, post_title
				FROM $wpdb->posts
				WHERE post_status = 'publish'
				AND     post_type = 'shipping-destination'
				AND   post_parent = $user_shipping_city
				ORDER BY post_title
				DESC");
			foreach ($query as $key => $value) {
				$result[$value->ID] = $value->post_title;
			}
		}
		return $result;
	}
}
