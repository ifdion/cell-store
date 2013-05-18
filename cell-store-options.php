<?php

global $cell_store_option;

$cell_store_option = array(
	'currency' => array(
		'symbol'=> 'IDR',
		'thousand-separator'=> ',',
		'decimal-separator'=> '.',
		'decimal-digit'=> 0,
		'use-in-front' => true
	),
	'product' => array(
		'slug' => 'product',
		'collection-slug' => 'collection',
		'product-category-slug' => 'product-category',
		'product-tag-slug' => 'product-tag',
	),
	'shopping' => array(
		'page' => array(
			'shopping-cart' => 'shopping-cart',
			'checkout' => 'checkout',
			'payment-option' => 'payment-option',
			'order-confirmation' => 'order-confirmation',
			'payment-confirmation'=> 'payment-confirmation',
			'login'=> 'login',
			'profile'=> 'profile',
			'my-transaction'=> 'my-transaction',
		),
		'redirect-after-buy' => 'checkout-page',
		'cancelation-due' => 1 , // 1 day before hit and run cancelation
	),
	'store' => array(
		'name' => 'name',
		'address' => 'address',
		'phone-1' => 'phone-1',
		'phone-2' => 'phone-2',
		'email' => 'email',
	),
	'payment' => array(
		'paypal' => array(
			'enable' => false,
			'title' => '',
			'image' => '',
			'description' => '',
			'email' => '',
			'image' => '',
			'sandbox' => false,
			'sandbox-email' => '',
		),
		'bank' => array(
			array(
				'title' => '',
				'image' => '',
				'description' => ''

			),
			array(
				'title' => '',
				'image' => '',
				'description' => ''

			),
		),
	),

);
