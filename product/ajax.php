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
		// validate data
		$product_name = $_POST['product_name'];
		$product_id = $_POST['product_id'];
		$quantity = $_POST['quantity'];
		$stock = $_POST['stock'];
		if (isset($_POST['product-option'])) {
			$product_option = $_POST['product-option'];
		}
		$weight = $_POST['weight'];
		$price = $_POST['price'];
		$return = $_POST['_wp_http_referer'];
		if ($_POST['return']) {
			$return = get_permalink(get_page_by_path('shopping-cart'));
		}
		

		if (isset($product_option) && isset($stock)) {
			$cart_item = $product_id.'-'.$product_option;
			$stock = $stock[$product_option];
		} elseif($stock) {
			$cart_item = $product_id;
			$stock = $stock['main'];
			$product_option = false;
		} elseif($product_option) {
			$cart_item = $product_id.'-'.$product_option;
			$stock = false;
		} else {
			$cart_item = $product_id;
			$product_option = false;
			$stock = false;
		}
		if ($stock) {
			if (isset($_SESSION['shopping-cart']['items'][$cart_item])) {
				$quantity_in_cart = $_SESSION['shopping-cart']['items'][$cart_item]['quantity'];
				if (($quantity + $quantity_in_cart) > $stock) {
					$quantity = $stock;
				} else {
					$quantity = $quantity + $quantity_in_cart;
				}
			} else {
				if ($quantity > $stock) {
					$quantity = $stock;
				}
			}
		} else {
			if ($_SESSION['shopping-cart']['items'][$cart_item]) {
				$quantity_in_cart = $_SESSION['shopping-cart']['items'][$cart_item]['quantity'];
				$quantity = $quantity + $quantity_in_cart;
			}
		}
		$new_item['ID'] = $product_id;
		$new_item['name'] = $product_name;
		$new_item['option'] = $product_option;
		$new_item['quantity'] = $quantity;
		$new_item['weight'] = $weight;
		$new_item['price'] = $price;
		if ($stock) {
			$new_item['stock-manage'] = 1;
		}
		$_SESSION['shopping-cart']['items'][$cart_item] = $new_item;

		$result['type'] = 'success';
		$result['message'] = sprintf(__(' %s added to shopping-cart ','cell-store'), $product_name);
		ajax_response($result,$return);


		die();
	}
}


?>