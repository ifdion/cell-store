<?php

/* add product to cart
---------------------------------------------------------------
*/
add_action('wp_ajax_nopriv_add_to_cart', 'process_add_to_cart');
add_action('wp_ajax_add_to_cart', 'process_add_to_cart');

function process_add_to_cart() {
	if ( empty($_POST) || !wp_verify_nonce($_POST['add_to_cart_nonce'],'add_to_cart') ) {
		echo 'Sorry, your nonce did not verify.';
		die();
	} else {
		// get pages
		$cell_store_pages = get_option( 'cell_store_pages' );
		$shopping_cart_page = $cell_store_pages['shopping-cart'];

		// get post data
		$product_id = $_POST['product_id'];
		$quantity = $_POST['quantity'];
		$return = $_POST['_wp_http_referer'];
		
		$cart_item = $product_id;
		$product_option = false;
		if (isset($_POST['product-option'])) {
			$product_option = $_POST['product-option'];
			$cart_item = $product_id.'-'.$product_option;
		}
		if ($_POST['return']) {
			$return = get_permalink(get_page_by_path($shopping_cart_page));
		}

		// get from database
		$product_data = get_post($product_id );
		$product_meta = get_post_meta($product_id );
		$product_name = $product_data->post_title;

		// check stock
		if (isset($product_meta['_use_stock_management'][0])) {
			if (isset($product_meta['_stock'][0])) {
				$stock = $product_meta['_stock'][0];
			}
			if (isset($product_meta['_use_variations'][0])) {
				$product_variations = unserialize($product_meta['_variant'][0]);

				foreach ($product_variations as $value) {
					if ($value['title'] == $product_option) {
						$stock = $value['stock'];
					}
				}
			}
		}
		if ($quantity > $stock) {
			$quantity = $stock;
		}

		// set up new item data
		$new_item['ID'] = $product_id;
		$new_item['option'] = $product_option;
		$new_item['quantity'] = $quantity;

		$_SESSION['shopping-cart']['items'][$cart_item] = $new_item;

		// remove payment session
		unset($_SESSION['shopping-cart']['payment']['total-weight']);
		unset($_SESSION['shopping-cart']['payment']['total-item-cost']);
		unset($_SESSION['shopping-cart']['payment']['shipping-destination-id']);
		unset($_SESSION['shopping-cart']['payment']['method']);
		unset($_SESSION['shopping-cart']['payment']['shipping-option']);
		unset($_SESSION['shopping-cart']['payment']['shipping-rate']);

		$result['type'] = 'success';
		$result['message'] = sprintf(__(' %s added to Shopping Cart ','cell-store'), $product_name);
		ajax_response($result,$return);
	}
}
?>