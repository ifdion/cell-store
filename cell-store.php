<?php
	/*
	Plugin Name: Cell Store
	Plugin URI: http://google.com
	Description: Cell Store function plugin, made to work with any cell theme
	Version: 1.0
	Author: Saklik
	Author URI: http://saklik.com
	License: 
	*/


//set constant values
define( 'CELL_STORE_FILE', __FILE__ );
define( 'CELL_STORE', dirname( __FILE__ ) );
define( 'CELL_STORE_PATH', plugin_dir_path(__FILE__) );
define( 'CELL_STORE_TEXT_DOMAIN', 'cell-store' );


// set for internationalization
function cell_store_init() {
	load_plugin_textdomain('cell-store', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action('plugins_loaded', 'cell_store_init');

/* session
---------------------------------------------------------------
*/

	if (!session_id()) {
		session_start();
	}

/* global 
---------------------------------------------------------------
*/

	include_once ('settings/settings.php');

	include_once ('common-functions.php');

	include_once ('metaboxes/setup.php');

	include_once ('user-management/user-management.php');

/* custom post types 
---------------------------------------------------------------
*/

	$store_features = get_option( 'cell_store_features' );

	include_once ('product/product.php');

	include_once ('transaction/transaction.php');

	include_once ('shipping-destination/shipping-destination.php');

	include_once ('payments/payments.php');

	if (isset($store_features['enable-coupon'])) {
		include_once ('coupon/coupon.php');
	}
	if (isset($store_features['enable-banner-management'])) {
		include_once ('banner/banner.php');
	}
	if (isset($store_features['enable-lookbook'])) {
		include_once ('lookbook/lookbook.php');
	}

/* taxonomies
---------------------------------------------------------------
*/

	include_once ('taxonomies.php');


/* rewrites
---------------------------------------------------------------
*/

	include_once ('rewrites.php');


/* shopping cart
---------------------------------------------------------------
*/

	include_once ('shopping-cart/shopping-cart.php');

?>