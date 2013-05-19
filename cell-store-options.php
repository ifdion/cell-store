<?php

global $cell_store_option;

$payment_confirmation_message =
'<p>Please complete your payment via bank transfer / e-banking / m-banking to: </p>
<p><strong>BCA : on behalf of Nisa Pratiwi , account number 4377227777</strong> or </p> 
<p><strong>BANK MANDIRI : on behalf of Nisa Pratiwi , account number 1310088333333</strong> or </p> 
<p><strong>WESTERN UNION : on behalf of Iqbal Alghifari.</strong> </p> 
<p>Keep your transaction script or record, you will need this to finish the payment confirmation step </p> ';

$cell_store_option = array(
	'currency' => array(
		'symbol'=> 'IDR', // check
		'thousand-separator'=> ',', // check
		'decimal-separator'=> '.', // check
		'decimal-digit'=> 0, // check
		'use-in-front' => true // check
	),
	'product' => array(
		'slug' => 'product', // check
		'weight-unit' => 'kg', // check
		'collection-slug' => 'collection', // check
		'product-category-slug' => 'product-category', // check
		'product-tag-slug' => 'product-tag', // check
	),
	'shopping' => array(
		'page' => array(
			'shopping-cart' => 'shopping-cart', // check
			'checkout' => 'checkout', // check
			'payment-option' => 'payment-option', //check
			'order-confirmation' => 'order-confirmation', // check
			'payment-confirmation'=> 'payment-confirmation', // check
			'login'=> 'login', // check
			'register'=> 'login', // check
			'profile'=> 'profile', // check
			'my-order'=> 'my-order',
		),
		'redirect-after-buy' => '', 
		'cancelation-due' => 1 , // check 
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
				'title' => __( 'Bank Mandiri','cell-store' ),
				'image' => plugins_url('cell-store/img/mandiri.jpeg'),
				'description' => ''

			),
			array(
				'title' => __( 'BCA','cell-store' ),
				'image' => plugins_url('cell-store/img/bca.jpeg'),
				'description' => ''
			),
			array(
				'title' => __( 'Western Union','cell-store' ),
				'image' => plugins_url('cell-store/img/western-union.png'),
				'description' => ''
			),
		),
		'agreement' => __('I agree with the term and conditions', 'cell-store'),
		'confirmation' => array(
			'message' => __( $payment_confirmation_message,'cell-store'),
			'method-option' => array( 
				__( 'BCA - Bank/ATM Transfer','cell-store' ),
				__( 'Mandiri - Bank/ATM Transfer','cell-store' ),
				__( 'm-banking BCA','cell-store' ),
				__( 'm-banking Mandiri','cell-store' ),
				__( 'klikBCA','cell-store' ),
				__( 'Mandiri E Banking','cell-store' ),
				__( 'BCA - Bank/ATM Transfer','cell-store' ),
				__( 'Other Method','cell-store' ),
			),
			'additional-field' => array(
				'other-method' => __( 'Other Method','cell-store' ),
				'account-holder' => __( 'Account Holder','cell-store' ),
				'mtcn-number' => __( 'MTCN Number','cell-store' ),
			)
		),
	),
);