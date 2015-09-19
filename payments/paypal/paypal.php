<?php

include_once('paypal-express-checkout/paypal.class.php');

/**
 * undocumented function
 *
 * @return void
 * @author 
 **/
function cs_process_paypal_payment(){

	// get store features
	$store_options = get_option('cell_store_features' );
	$currency_symbol = $store_options['currency'];
	$weight_unit = $store_options['weight-unit'];
	$exchange_rate = 1;
	if (isset($_SESSION['shopping-cart']['payment']['use-secondary-currency'])) {
		$currency_symbol = $store_options['secondary-currency'];
		$exchange_rate = $_SESSION['shopping-cart']['payment']['exchange-rate'];
	}

	$options = get_option( 'cell_store_payments' );

	$PayPalMode 			= 'sandbox'; // sandbox or live
	$PayPalApiUsername 		= $options['paypal-username']; //PayPal API Username
	$PayPalApiPassword 		= $options['paypal-password']; //Paypal API password
	$PayPalApiSignature 	= $options['paypal-signature']; //Paypal API Signature
	$PayPalCurrencyCode 	= $options['paypal-currency'];; //Paypal Currency Code
	$PayPalReturnURL 		= admin_url('admin-ajax.php').'?action=paypal_return'; // 'http://localhost/paypal/process.php'; //Point to process.php page
	$PayPalCancelURL 		= admin_url('admin-ajax.php').'?action=paypal_cancel'; //http://localhost/paypal/cancel_url.php'; //Cancel URL if user clicks cancel

	$PayPalMode				= 'sandbox';
	if (isset($options['enable-live-paypal'])) {
		$PayPalMode 			= 'live'; // sandbox or live
	}

	$items = $_SESSION['shopping-cart']['items'];
	$shipping_rate = ceil($exchange_rate * $_SESSION['shopping-cart']['payment']['shipping-rate'] * 100) / 100;

	$items_array = [];
	$item_total_price = 0;
	$item_total_weight = 0;
	$i = 0;
	$product_parameter = '';
	foreach ($items as $product_id => $product_cart) {
		
		$product_data = get_post($product_cart['ID'] );
		$product_meta = get_post_meta($product_cart['ID'] );

		// get product discount price
		$product_price = ceil($exchange_rate * $product_meta['_price'][0] * 100) / 100;
		if (cs_get_discount_price($product_cart['ID'])) {
			$product_price = ceil($exchange_rate * cs_get_discount_price($product_cart['ID']) * 100) / 100;
		}
		$product_weight = 0;
		if (isset($product_meta['_weight'][0])) {
			$product_weight = $product_meta['_weight'][0];
		}
		$item_total_price += $product_cart['quantity'] * $product_price;
		$item_total_weight += $product_cart['quantity'] * $product_weight;

		$product_parameter .=
			'&L_PAYMENTREQUEST_0_NAME'.$i.'='.urlencode($product_data->post_title.' '.$product_cart['option']).
			'&L_PAYMENTREQUEST_0_NUMBER'.$i.'='.urlencode($product_id).
			'&L_PAYMENTREQUEST_0_DESC'.$i.'='.urlencode(strip_tags($product_data->post_content)).
			'&L_PAYMENTREQUEST_0_AMT'.$i.'='.urlencode($product_price).
			'&L_PAYMENTREQUEST_0_QTY'.$i.'='. urlencode($product_cart['quantity'])
		;

		$i ++;
	}

	// get the totals
	$total_shipping_cost = $item_total_weight * $shipping_rate;
	$grand_total = $total_shipping_cost + $item_total_price;
		
	//Parameters for SetExpressCheckout, which will be sent to PayPal
	$padata = 	'&METHOD=SetExpressCheckout'.
				'&RETURNURL='.urlencode($PayPalReturnURL ).
				'&CANCELURL='.urlencode($PayPalCancelURL).
				'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE");
	$padata .=	$product_parameter;	

	$padata .=			
				
				'&NOSHIPPING=1'. //set 1 to hide buyer's shipping address, in-case products that does not require shipping
				'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($item_total_price).
				// '&PAYMENTREQUEST_0_TAXAMT='.urlencode($TotalTaxAmount).
				'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($total_shipping_cost).
				// '&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($HandalingCost).
				// '&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($ShippinDiscount).
				// '&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($InsuranceCost).
				'&PAYMENTREQUEST_0_AMT='.urlencode($grand_total).
				'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
				'&LOCALECODE=GB'. //PayPal pages to match the language on your website.
				'&LOGOIMG='. get_template_directory_uri().'/images/lembur-logo.png' . //http://www.sanwebe.com/wp-content/themes/sanwebe/img/logo.png'. //site logo
				'&CARTBORDERCOLOR=FFFFFF'. //border color of cart
				'&ALLOWNOTE=0';

	//We need to execute the "SetExpressCheckOut" method to obtain paypal token
	$paypal= new MyPayPal();
	$httpParsedResponseAr = $paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

	//Respond according to message we receive from Paypal
	if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){
			//Redirect user to PayPal store with Token received.
		 	$paypalurl ='https://www.'.$PayPalMode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
			header('Location: '.$paypalurl);
	} else {
		//Show error message
		echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
		echo '<pre>';
		print_r($httpParsedResponseAr);
		echo '</pre>';
	}
}

/* paypal  
---------------------------------------------------------------
*/

add_action('wp_ajax_nopriv_paypal_return', 'process_paypal_return');
add_action('wp_ajax_paypal_return', 'process_paypal_return');

function process_paypal_return() {

	$cell_store_pages = get_option( 'cell_store_pages' );
	$options = get_option( 'cell_store_payments' );

	$payment_result_page = $cell_store_pages['payment-result'];
	$return_success = get_permalink(get_page_by_path($payment_result_page ));

	$PayPalMode 			= 'sandbox'; // sandbox or live
	$PayPalApiUsername 		= $options['paypal-username']; //PayPal API Username
	$PayPalApiPassword 		= $options['paypal-password']; //Paypal API password
	$PayPalApiSignature 	= $options['paypal-signature']; //Paypal API Signature
	$PayPalCurrencyCode 	= $options['paypal-currency'];; //Paypal Currency Code
	$PayPalReturnURL 		= admin_url('admin-ajax.php').'?action=paypal_return'; // 'http://localhost/paypal/process.php'; //Point to process.php page
	$PayPalCancelURL 		= admin_url('admin-ajax.php').'?action=paypal_cancel'; //http://localhost/paypal/cancel_url.php'; //Cancel URL if user clicks cancel

	$PayPalMode				= 'sandbox';
	if (isset($options['enable-live-paypal'])) {
		$PayPalMode 			= 'live'; // sandbox or live
	}

	$paypalmode = ($PayPalMode=='sandbox') ? '.sandbox' : '';

	if(isset($_GET["token"]) && isset($_GET["PayerID"])){
		
		$token = $_GET["token"];
		$payer_id = $_GET["PayerID"];

		$items = $_SESSION['shopping-cart']['items'];
		$shipping_rate = $_SESSION['shopping-cart']['payment']['shipping-rate'];

		$items_array = [];
		$item_total_price = 0;
		$item_total_weight = 0;
		$i = 0;
		$product_parameter = '';
		foreach ($items as $product_id => $product_cart) {
			
			$product_data = get_post($product_cart['ID'] );
			$product_meta = get_post_meta($product_cart['ID'] );

			// get product discount price
			$product_price = $product_meta['_price'][0];
			if (cs_get_discount_price($product_cart['ID'])) {
				$product_price = cs_get_discount_price($product_cart['ID']);
			}
			$product_weight = 0;
			if (isset($product_meta['weight'][0])) {
				$product_weight = $product_meta['weight'][0];
			}
			$item_total_price += $product_cart['quantity'] * $product_price;
			$item_total_weight += $product_cart['quantity'] * $product_weight;

			$product_parameter .=
				'&L_PAYMENTREQUEST_0_NAME'.$i.'='.urlencode($product_data->post_title.' '.$product_cart['option']).
				'&L_PAYMENTREQUEST_0_NUMBER'.$i.'='.urlencode($product_id).
				'&L_PAYMENTREQUEST_0_DESC'.$i.'='.urlencode(strip_tags($product_data->post_content)).
				'&L_PAYMENTREQUEST_0_AMT'.$i.'='.urlencode($product_price).
				'&L_PAYMENTREQUEST_0_QTY'.$i.'='. urlencode($product_cart['quantity'])
			;

			$i ++;
		}

		// get the total
		$total_shipping_cost = $item_total_weight * $shipping_rate;
		$grand_total = $total_shipping_cost + $item_total_price;
	
		$padata =   '&TOKEN='.urlencode($token).
					'&PAYERID='.urlencode($payer_id).
					'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE");

		$padata .=	$product_parameter;						
					
		$padata .=
					'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($item_total_price).
					'&PAYMENTREQUEST_0_TAXAMT='.urlencode(0).
					'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($total_shipping_cost).
					'&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode(0).
					'&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode(0).
					'&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode(0).
					'&PAYMENTREQUEST_0_AMT='.urlencode($grand_total).
					'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode);


		
		//We need to execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.
		$paypal= new MyPayPal();
		$httpParsedResponseAr = $paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
		
		//Check if everything went ok..
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){
				
			/*
			//Sometimes Payment are kept pending even when transaction is complete. 
			//hence we need to notify user about it and ask him manually approve the transiction
			*/
					
			// if('Completed' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]){
			// 	echo '<div style="color:green">Payment Received! Your product will be sent to you very soon!</div>';
			// } elseif('Pending' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]){
			// 	echo '<div style="color:red">Transaction Complete, but payment is still pending! '.
			// 	'You need to manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a></div>';
			// }

			// we can retrive transection details using either GetTransactionDetails or GetExpressCheckoutDetails
			// GetTransactionDetails requires a Transaction ID, and GetExpressCheckoutDetails requires Token returned by SetExpressCheckOut
			$padata =   '&TOKEN='.urlencode($token);
			$paypal= new MyPayPal();
			$httpParsedResponseAr = $paypal->PPHttpPost('GetExpressCheckoutDetails', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

			if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){

				// update transaction data
				$transaction_data = get_page_by_path( $_SESSION['shopping-cart']['last-transaction'], 'object', 'transaction' );
				update_post_meta( $transaction_data->ID, '_transaction_status', 'paid' );
				add_post_meta( $transaction_data->ID, '_token', $_REQUEST['token'] );
				add_post_meta( $transaction_data->ID, '_payer_id', $_REQUEST['PayerID'] );

				unset($_SESSION['shopping-cart']['items']);
				unset($_SESSION['shopping-cart']['coupon']);
				unset($_SESSION['shopping-cart']['payment']['total-weight']);
				unset($_SESSION['shopping-cart']['payment']['total-item-cost']);
				unset($_SESSION['shopping-cart']['payment']['shipping-destination-id']);
				unset($_SESSION['shopping-cart']['payment']['method']);
				unset($_SESSION['shopping-cart']['payment']['shipping-option']);
				unset($_SESSION['shopping-cart']['payment']['shipping-rate']);
				
				$result['type'] = 'success';
				$result['message'] = __('Payment Success.', 'cell-store');
				ajax_response($result,$return_success);

				exit;

			} else {
				echo '<div style="color:red"><b>GetTransactionDetails failed:</b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
				echo '<pre>';
				print_r($httpParsedResponseAr);
				echo '</pre>';
				exit();
			}
		
		} else {
			echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
			echo '<pre>';
			print_r($httpParsedResponseAr);
			echo '</pre>';
			exit();
		}
	}
}

/* paypal  
---------------------------------------------------------------
*/

add_action('wp_ajax_nopriv_paypal_cancel', 'process_paypal_cancel');
add_action('wp_ajax_paypal_cancel', 'process_paypal_cancel');

function process_paypal_cancel() {

	$cell_store_pages = get_option( 'cell_store_pages' );
	$options = get_option( 'cell_store_payments' );

	$order_confirmation_page = $cell_store_pages['order-confirmation'];
	$return_success = get_permalink(get_page_by_path($order_confirmation_page ));

	unset($_SESSION['shopping-cart']['last-transaction']);

	$result['type'] = 'danger';
	$result['message'] = __('Payment Cannceled.', 'cell-store');
	ajax_response($result,$return_success);

}

?>