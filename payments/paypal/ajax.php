<?php


/* add product to cart
---------------------------------------------------------------
*/
add_action('wp_ajax_nopriv_test_paypal', 'process_test_paypal');
add_action('wp_ajax_test_paypal', 'process_test_paypal');

function process_test_paypal() {

	$options = get_option( 'cell_store_payments' );
	echo '<pre>';
	print_r($options);
	echo '</pre>';
	?>
		<h2 align="center">Test Products</h2>
		<div class="product_wrapper">
		<table class="procut_item" border="0" cellpadding="4">
		  <tr>
		    <td width="70%"><h4>Canon EOS Rebel XS</h4>(Capture all your special moments with the Canon EOS Rebel XS/1000D DSLR camera and cherish the memories over and over again.)</td>
		    <td width="30%">
		    <form method="post" action="<?php echo admin_url('admin-ajax.php' ); ?>?action=paypal_payment">
			<input type="hidden" name="itemname" value="Canon EOS Rebel XS" /> 
			<input type="hidden" name="itemnumber" value="10000" /> 
		    <input type="hidden" name="itemdesc" value="Capture all your special moments with the Canon EOS Rebel XS/1000D DSLR camera and cherish the memories over and over again." /> 
			<input type="hidden" name="itemprice" value="225.00" />
		    Quantity : <select name="itemQty"><option value="1">1</option><option value="2">2</option><option value="3">3</option></select> 
		    <input class="dw_button" type="submit" name="submitbutt" value="Buy (225.00 USD )" />
		    </form>
		    </td>
		  </tr>
		</table>

		<table class="procut_item" border="0" cellpadding="4">
		  <tr>
		    <td width="70%"><h4>Nikon COOLPIX</h4>(Nikon Coolpix S9050 26355 digital camera capture vibrant photos up to 12.1 megapixels)</td>
		    <td width="30%">
		    <form method="post" action="<?php echo admin_url('admin-ajax.php' ); ?>?action=paypal_payment">
			<input type="hidden" name="itemname" value="Nikon COOLPIX" /> 
			<input type="hidden" name="itemnumber" value="20000" /> 
		    <input type="hidden" name="itemdesc" value="Nikon Coolpix S9050 26355 digital camera capture vibrant photos up to 12.1 megapixels." /> 
			<input type="hidden" name="itemprice" value="109.99" /> Quantity : <select name="itemQty"><option value="1">1</option><option value="2">2</option><option value="3">3</option></select> 
		    <input class="dw_button" type="submit" name="submitbutt" value="Buy (109.99 USD )" />
		    </form></td>
		  </tr>
		</table>
		</div>
		</body>
		</html>
	<?php

	die();
}

/* paypal process 
---------------------------------------------------------------
*/

add_action('wp_ajax_nopriv_paypal_payment', 'process_paypal_payment');
add_action('wp_ajax_paypal_payment', 'process_paypal_payment');

function process_paypal_payment() {

	$options = get_option( 'cell_store_payments' );

	$PayPalMode 			= 'sandbox'; // sandbox or live
	$PayPalApiUsername 		= $options['paypal-username']; //PayPal API Username
	$PayPalApiPassword 		= $options['paypal-password']; //Paypal API password
	$PayPalApiSignature 	= $options['paypal-signature']; //Paypal API Signature
	$PayPalCurrencyCode 	= $options['paypal-currency'];; //Paypal Currency Code
	$PayPalReturnURL 		= admin_url('admin-ajax.php').'?action=process_paypal_return'; // 'http://localhost/paypal/process.php'; //Point to process.php page
	$PayPalCancelURL 		= admin_url('admin-ajax.php').'?action=process_paypal_cancel'; //http://localhost/paypal/cancel_url.php'; //Cancel URL if user clicks cancel

	$PayPalMode				= 'sandbox';
	if (isset($options['enable-live-paypal'])) {
		$PayPalMode 			= 'live'; // sandbox or live
	}

	//Mainly we need 4 variables from product page Item Name, Item Price, Item Number and Item Quantity.
	
	//Please Note : People can manipulate hidden field amounts in form,
	//In practical world you must fetch actual price from database using item id. Eg: 
	//$ItemPrice = $mysqli->query("SELECT item_price FROM products WHERE id = Product_Number");

	$ItemName 		= $_POST["itemname"]; //Item Name
	$ItemPrice 		= $_POST["itemprice"]; //Item Price
	$ItemNumber 	= $_POST["itemnumber"]; //Item Number
	$ItemDesc 		= $_POST["itemdesc"]; //Item Number
	$ItemQty 		= $_POST["itemQty"]; // Item Quantity
	$ItemTotalPrice = ($ItemPrice*$ItemQty); //(Item Price x Quantity = Total) Get total amount of product; 
	
	//Other important variables like tax, shipping cost
	$TotalTaxAmount 	= 2.58;  //Sum of tax for all items in this order. 
	$HandalingCost 		= 2.00;  //Handling cost for this order.
	$InsuranceCost 		= 1.00;  //shipping insurance cost for this order.
	$ShippinDiscount 	= -3.00; //Shipping discount for this order. Specify this as negative number.
	$ShippinCost 		= 3.00; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
	
	//Grand total including all tax, insurance, shipping cost and discount
	$GrandTotal = ($ItemTotalPrice + $TotalTaxAmount + $HandalingCost + $InsuranceCost + $ShippinCost + $ShippinDiscount);
	
	//Parameters for SetExpressCheckout, which will be sent to PayPal
	$padata = 	'&METHOD=SetExpressCheckout'.
				'&RETURNURL='.urlencode($PayPalReturnURL ).
				'&CANCELURL='.urlencode($PayPalCancelURL).
				'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
				
				'&L_PAYMENTREQUEST_0_NAME0='.urlencode($ItemName).
				'&L_PAYMENTREQUEST_0_NUMBER0='.urlencode($ItemNumber).
				'&L_PAYMENTREQUEST_0_DESC0='.urlencode($ItemDesc).
				'&L_PAYMENTREQUEST_0_AMT0='.urlencode($ItemPrice).
				'&L_PAYMENTREQUEST_0_QTY0='. urlencode($ItemQty).
				
				/* 
				//Additional products (L_PAYMENTREQUEST_0_NAME0 becomes L_PAYMENTREQUEST_0_NAME1 and so on)
				'&L_PAYMENTREQUEST_0_NAME1='.urlencode($ItemName2).
				'&L_PAYMENTREQUEST_0_NUMBER1='.urlencode($ItemNumber2).
				'&L_PAYMENTREQUEST_0_DESC1='.urlencode($ItemDesc2).
				'&L_PAYMENTREQUEST_0_AMT1='.urlencode($ItemPrice2).
				'&L_PAYMENTREQUEST_0_QTY1='. urlencode($ItemQty2).
				*/
				
				/* 
				//Override the buyer's shipping address stored on PayPal, The buyer cannot edit the overridden address.
				'&ADDROVERRIDE=1'.
				'&PAYMENTREQUEST_0_SHIPTONAME=J Smith'.
				'&PAYMENTREQUEST_0_SHIPTOSTREET=1 Main St'.
				'&PAYMENTREQUEST_0_SHIPTOCITY=San Jose'.
				'&PAYMENTREQUEST_0_SHIPTOSTATE=CA'.
				'&PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE=US'.
				'&PAYMENTREQUEST_0_SHIPTOZIP=95131'.
				'&PAYMENTREQUEST_0_SHIPTOPHONENUM=408-967-4444'.
				*/
				
				'&NOSHIPPING=0'. //set 1 to hide buyer's shipping address, in-case products that does not require shipping
				
				'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).
				'&PAYMENTREQUEST_0_TAXAMT='.urlencode($TotalTaxAmount).
				'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($ShippinCost).
				'&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($HandalingCost).
				'&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($ShippinDiscount).
				'&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($InsuranceCost).
				'&PAYMENTREQUEST_0_AMT='.urlencode($GrandTotal).
				'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
				'&LOCALECODE=GB'. //PayPal pages to match the language on your website.
				'&LOGOIMG=http://www.sanwebe.com/wp-content/themes/sanwebe/img/logo.png'. //site logo
				'&CARTBORDERCOLOR=FFFFFF'. //border color of cart
				'&ALLOWNOTE=1';
				
				############# set session variable we need later for "DoExpressCheckoutPayment" #######
				$_SESSION['ItemName'] 			=  $ItemName; //Item Name
				$_SESSION['ItemPrice'] 			=  $ItemPrice; //Item Price
				$_SESSION['ItemNumber'] 		=  $ItemNumber; //Item Number
				$_SESSION['ItemDesc'] 			=  $ItemDesc; //Item Number
				$_SESSION['ItemQty'] 			=  $ItemQty; // Item Quantity
				$_SESSION['ItemTotalPrice'] 	=  $ItemTotalPrice; //(Item Price x Quantity = Total) Get total amount of product; 
				$_SESSION['TotalTaxAmount'] 	=  $TotalTaxAmount;  //Sum of tax for all items in this order. 
				$_SESSION['HandalingCost'] 		=  $HandalingCost;  //Handling cost for this order.
				$_SESSION['InsuranceCost'] 		=  $InsuranceCost;  //shipping insurance cost for this order.
				$_SESSION['ShippinDiscount'] 	=  $ShippinDiscount; //Shipping discount for this order. Specify this as negative number.
				$_SESSION['ShippinCost'] 		=   $ShippinCost; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
				$_SESSION['GrandTotal'] 		=  $GrandTotal;


		//We need to execute the "SetExpressCheckOut" method to obtain paypal token
		$paypal= new MyPayPal();
		$httpParsedResponseAr = $paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
	
		//Respond according to message we receive from Paypal
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
		{

				//Redirect user to PayPal store with Token received.
			 	$paypalurl ='https://www.'.$PayPalMode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';

			 	


				header('Location: '.$paypalurl);
			 
		}else{
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

	global $tree_naming_page;

	$options = get_option( 'cell_store_payments' );

	$PayPalMode 			= 'sandbox'; // sandbox or live
	$PayPalApiUsername 		= $options['paypal-username']; //PayPal API Username
	$PayPalApiPassword 		= $options['paypal-password']; //Paypal API password
	$PayPalApiSignature 	= $options['paypal-signature']; //Paypal API Signature
	$PayPalCurrencyCode 	= $options['paypal-currency']; //Paypal Currency Code
	$PayPalReturnURL 		= admin_url('admin-ajax.php').'?action=process_paypal_return'; // 'http://localhost/paypal/process.php'; //Point to process.php page
	$PayPalCancelURL 		= admin_url('admin-ajax.php').'?action=process_paypal_cancel'; //http://localhost/paypal/cancel_url.php'; //Cancel URL if user clicks cancel

	$PayPalMode				= 'sandbox';
	if (isset($options['enable-live-paypal'])) {
		$PayPalMode 			= 'live'; // sandbox or live
	}


	$paypalmode = ($PayPalMode=='sandbox') ? '.sandbox' : '';

	if(isset($_GET["token"]) && isset($_GET["PayerID"])){
		
		$token = $_GET["token"];
		$payer_id = $_GET["PayerID"];
		$post_id = $_GET["post_id"];
		
		//get session variables
		$ItemName           = $_SESSION['ItemName']; //Item Name
		$ItemPrice          = $_SESSION['ItemPrice'] ; //Item Price
		$ItemNumber         = $_SESSION['ItemNumber']; //Item Number
		$ItemDesc           = $_SESSION['ItemDesc']; //Item Number
		$ItemQty            = $_SESSION['ItemQty']; // Item Quantity
		$ItemTotalPrice     = $_SESSION['ItemTotalPrice']; //(Item Price x Quantity = Total) Get total amount of product; 
		$TotalTaxAmount = 0;
		if (isset($_SESSION['TotalTaxAmount'])) {
			$TotalTaxAmount     = $_SESSION['TotalTaxAmount'] ;  //Sum of tax for all items in this order. 
		}
		$HandalingCost = 0;
		if (isset($_SESSION['HandalingCost'])) {
			$HandalingCost      = $_SESSION['HandalingCost'];  //Handling cost for this order.
		}
		$InsuranceCost = 0;
		if (isset($_SESSION['InsuranceCost'])) {
			$InsuranceCost      = $_SESSION['InsuranceCost'];  //shipping insurance cost for this order.
		}
		$ShippinDiscount = 0;
		if (isset($_SESSION['ShippinDiscount'])) {
			$ShippinDiscount    = $_SESSION['ShippinDiscount']; //Shipping discount for this order. Specify this as negative number.
		}
		$ShippinCost = 0;
		if (isset($_SESSION['ShippinCost'])) {
			$ShippinCost        = $_SESSION['ShippinCost']; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
		}
		$GrandTotal         = $_SESSION['GrandTotal'];

		$padata =   '&TOKEN='.urlencode($token).
					'&PAYERID='.urlencode($payer_id).
					'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
					
					//set item info here, otherwise we won't see product details later  
					'&L_PAYMENTREQUEST_0_NAME0='.urlencode($ItemName).
					'&L_PAYMENTREQUEST_0_NUMBER0='.urlencode($ItemNumber).
					'&L_PAYMENTREQUEST_0_DESC0='.urlencode($ItemDesc).
					'&L_PAYMENTREQUEST_0_AMT0='.urlencode($ItemPrice).
					'&L_PAYMENTREQUEST_0_QTY0='. urlencode($ItemQty).

					/* 
					//Additional products (L_PAYMENTREQUEST_0_NAME0 becomes L_PAYMENTREQUEST_0_NAME1 and so on)
					'&L_PAYMENTREQUEST_0_NAME1='.urlencode($ItemName2).
					'&L_PAYMENTREQUEST_0_NUMBER1='.urlencode($ItemNumber2).
					'&L_PAYMENTREQUEST_0_DESC1=Description text'.
					'&L_PAYMENTREQUEST_0_AMT1='.urlencode($ItemPrice2).
					'&L_PAYMENTREQUEST_0_QTY1='. urlencode($ItemQty2).
					*/

					'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).
					'&PAYMENTREQUEST_0_TAXAMT='.urlencode($TotalTaxAmount).
					'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($ShippinCost).
					'&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($HandalingCost).
					'&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($ShippinDiscount).
					'&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($InsuranceCost).
					'&PAYMENTREQUEST_0_AMT='.urlencode($GrandTotal).
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

			if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){


				echo 'success, really';
				
				$result['type'] = 'success';
				$result['message'] = __('Payment Success.', 'trees-id-payment');
				ajax_response($result,$return);

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
}

?>