<?php

/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */ 

/**
 * Provides default values for the Social Options.
 */
function cell_store_payments() {
	
	$defaults = array(
		'enable-paypal'			=>	0,
		'enable-live-paypal'	=>	0,
		'paypal-username'		=>	'',
		'paypal-password'		=>	'',
		'paypal-signature'		=>	'',
		'paypal-currency'		=>	'USD',
		'paypal-image'			=>	'',

		'enable-transfer'		=>	0,
		'transfer-detail'		=>	array(),

	);
	
	return apply_filters( 'cell_store_payments', $defaults );
} // end cell_store_payments

/**
 * Initializes the theme's social options by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */ 
function cell_store_initialize_payments() {

	if( false == get_option( 'cell_store_payments' ) ) {	
		add_option( 'cell_store_payments', apply_filters( 'cell_store_payments', cell_store_payments() ) );
	} // end if
	
	add_settings_section(
		'store_payment_section',			// ID used to identify this section and with which to register options
		__( 'Store Payments', 'cell_store' ),		// Title to be displayed on the administration page
		'cell_store_payments_callback',	// Callback used to render the description of the section
		'cell_store_payments'		// Page on which to add this section of options
	);

	add_settings_field(
		'Enable Paypal',
		__( 'Enable Paypal', 'cell_store' ),
		'cell_store_enable_paypal_callback',
		'cell_store_payments',
		'store_payment_section'
	);

	add_settings_field(
		'Enable Live Paypal',
		__( 'Enable Live Paypal', 'cell_store' ),
		'cell_store_enable_live_paypal_callback',
		'cell_store_payments',
		'store_payment_section'
	);
	
	add_settings_field(	
		'paypal-username',						
		'PayPal Username',							
		'cell_store_paypal_username_callback',	
		'cell_store_payments',	
		'store_payment_section'			
	);

	add_settings_field(	
		'paypal-password',						
		'Paypal Password',							
		'cell_store_paypal_password_callback',	
		'cell_store_payments',	
		'store_payment_section'			
	);
	
	add_settings_field(	
		'paypal-signature',						
		'Paypal Signature',							
		'cell_store_paypal_signature_callback',	
		'cell_store_payments',	
		'store_payment_section'			
	);

	add_settings_field(	
		'paypal-currency',						
		'Paypal Currency',							
		'cell_store_paypal_currency_callback',	
		'cell_store_payments',	
		'store_payment_section'			
	);

	add_settings_field(	
		'paypal-image',						
		'Paypal Image',							
		'cell_store_paypal_image_callback',	
		'cell_store_payments',	
		'store_payment_section'			
	);


	add_settings_field(
		'Enable Transfer',
		__( 'Enable Transfer', 'cell_store' ),
		'cell_store_enable_transfer_callback',
		'cell_store_payments',
		'store_payment_section'
	);

	add_settings_field(
		'Transfer Detail',
		__( 'Transfer Detail', 'cell_store' ),
		'cell_store_transfer_detail_callback',
		'cell_store_payments',
		'store_payment_section'
	);

	// add_settings_field(	
	// 	'order-confirmation',						
	// 	'Order Confirmation Page',							
	// 	'cell_store_order_confirmation_callback',	
	// 	'cell_store_payments',	
	// 	'store_payment_section'			
	// );

	// add_settings_field(	
	// 	'thank-you',						
	// 	'Thank You Page',							
	// 	'cell_store_thank_you_callback',	
	// 	'cell_store_payments',	
	// 	'store_payment_section'			
	// );
	
	register_setting(
		'cell_store_payments',
		'cell_store_payments',
		'cell_store_sanitize_payments'
	);
	
} // end cell_store_initialize_payments
add_action( 'admin_init', 'cell_store_initialize_payments' );

/* ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------ */ 

/**
 * This function provides a simple description for the Social Options page. 
 *
 * It's called from the 'cell_store_initialize_social_options' function by being passed as a parameter
 * in the add_settings_section function.
 */
function cell_store_payments_callback() {
	echo '<p>' . __( 'Provide the slug for store pages.', 'cell_store' ) . '</p>';
} // end cell_store_general_options_callback


/* ------------------------------------------------------------------------ *
 * Field Callbacks
 * ------------------------------------------------------------------------ */ 

/**
 * This function renders the interface elements for toggling the visibility of the header element.
 * 
 * It accepts an array or arguments and expects the first element in the array to be the description
 * to be displayed next to the checkbox.
 */


function cell_store_enable_paypal_callback() {

	$options = get_option( 'cell_store_payments' );

	if (!isset($options['enable-paypal'])) {
		$options['enable-paypal'] = 0;
	}
	
	$html = '<input type="checkbox" id="enable-paypal" name="cell_store_payments[enable-paypal]" value="1"' . checked( 1, $options['enable-paypal'], false ) . '/>';
	$html .= '&nbsp;';
	$html .= '<label for="enable-paypal">Check to Enable</label>';
	
	echo $html;

} // end cell_store_enable_paypal_callback

function cell_store_enable_live_paypal_callback() {

	$options = get_option( 'cell_store_payments' );
	if (!isset($options['enable-live-paypal'])) {
		$options['enable-live-paypal'] = 0;
	}
	
	$html = '<input type="checkbox" id="enable-live-paypal" name="cell_store_payments[enable-live-paypal]" value="1"' . checked( 1, $options['enable-live-paypal'], false ) . '/>';
	$html .= '&nbsp;';
	$html .= '<label for="enable-live-paypal">Check to Enable</label>';
	
	echo $html;

} // end cell_store_enable_live_paypal_callback

function cell_store_paypal_username_callback() {
	
	// First, we read the social options collection
	$options = get_option( 'cell_store_payments' );
	
	// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
	$url = '';
	if( isset( $options['paypal-username'] ) ) {
		$url = esc_attr( $options['paypal-username'] );
	} // end if
	
	// Render the output
	echo '<input type="text" id="paypal-username" name="cell_store_payments[paypal-username]" value="' . $url . '" />';
	
} // end cell_store_paypal_username_callback

function cell_store_paypal_password_callback() {
	
	// First, we read the social options collection
	$options = get_option( 'cell_store_payments' );
	
	// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
	$url = '';
	if( isset( $options['paypal-password'] ) ) {
		$url = esc_attr( $options['paypal-password'] );
	} // end if
	
	// Render the output
	echo '<input type="password" id="paypal-password" name="cell_store_payments[paypal-password]" value="' . $url . '" />';
	
} // end cell_store_paypal_password_callback

function cell_store_paypal_signature_callback() {
	
	// First, we read the social options collection
	$options = get_option( 'cell_store_payments' );
	
	// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
	$url = '';
	if( isset( $options['paypal-signature'] ) ) {
		$url = esc_attr( $options['paypal-signature'] );
	} // end if
	
	// Render the output
	echo '<input type="text" id="paypal-signature" name="cell_store_payments[paypal-signature]" value="' . $url . '" />';
	
} // end cell_store_paypal_signature_callback

function cell_store_paypal_currency_callback () {
	
	// First, we read the social options collection
	$options = get_option( 'cell_store_payments' );
	
	// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
	$url = '';
	if( isset( $options['paypal-currency'] ) ) {
		$url = esc_attr( $options['paypal-currency'] );
	} // end if
	
	// Render the output
	echo '<input type="text" id="paypal-currency" name="cell_store_payments[paypal-currency]" value="' . $url . '" />';
	
} // end cell_store_paypal_currency_callback

function cell_store_paypal_image_callback () {
	
	// First, we read the social options collection
	$options = get_option( 'cell_store_payments' );
	
	// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
	$url = '';
	if( isset( $options['paypal-image'] ) ) {
		$url = esc_attr( $options['paypal-image'] );
	} // end if
	
	// Render the output
	echo '<input type="text" id="paypal-image" name="cell_store_payments[paypal-image]" value="' . $url . '" />';
	
} // end cell_store_paypal_image_callback

function cell_store_enable_transfer_callback() {

	$options = get_option( 'cell_store_payments' );
	if (!isset($options['enable-transfer'])) {
		$options['enable-transfer'] = 0;
	}
	
	$html = '<input type="checkbox" id="enable-transfer" name="cell_store_payments[enable-transfer]" value="1"' . checked( 1, $options['enable-transfer'], false ) . '/>';
	$html .= '&nbsp;';
	$html .= '<label for="enable-transfer">Check to Enable</label>';
	
	echo $html;

} // end cell_store_enable_transfer_callback

function cell_store_transfer_detail_callback() {

	$options = get_option( 'cell_store_payments' );

	ob_start();
		include('template/transfer-destination.php');
		$transfer_destination = ob_get_contents();
	ob_end_clean();
	echo $transfer_destination;


} // end cell_store_transfer_detail_callback

// function cell_store_order_confirmation_callback() {
	
// 	// First, we read the social options collection
// 	$options = get_option( 'cell_store_payments' );
	
// 	// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
// 	$url = '';
// 	if( isset( $options['order-confirmation'] ) ) {
// 		$url = esc_attr( $options['order-confirmation'] );
// 	} // end if
	
// 	// Render the output
// 	echo '<input type="text" id="order-confirmation" name="cell_store_payments[order-confirmation]" value="' . $url . '" />';
	
// } // end cell_store_order_confirmation_callback

// function cell_store_thank_you_callback() {
	
// 	// First, we read the social options collection
// 	$options = get_option( 'cell_store_payments' );
	
// 	// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
// 	$url = '';
// 	if( isset( $options['thank-you'] ) ) {
// 		$url = esc_attr( $options['thank-you'] );
// 	} // end if
	
// 	// Render the output
// 	echo '<input type="text" id="thank-you" name="cell_store_payments[thank-you]" value="' . $url . '" />';
	
// } // end cell_store_thank_you_callback

/* ------------------------------------------------------------------------ *
 * Setting Callbacks
 * ------------------------------------------------------------------------ */ 
 
 
/**
 * Sanitization callback for the social options. Since each of the social options are text inputs,
 * this function loops through the incoming option and strips all tags and slashes from the value
 * before serializing it.
 *	
 * @param 		$input	The unsanitized collection of options.
 * @todo 		strip tags and srtipslashes on array value
 * @return		The collection of sanitized values.
 */

function cell_store_sanitize_payments( $input ) {
	
	// Define the array for the updated options
	$output = array();

	// Loop through each of the options sanitizing the data
	foreach( $input as $key => $val ) {
		if( isset ( $input[$key] ) ) {
			if (is_string($input[$key])) {
				$output[$key] = strip_tags( stripslashes( $input[$key] ) );
			} else{

				foreach ($input[$key] as $sub_key => $sub_val) {
					foreach ($sub_val as $sub_sub_key => $sub_sub_value) {
						if ($sub_sub_value != '') {
							$output[$key][$sub_key][$sub_sub_key] = strip_tags( stripslashes( $sub_sub_value ) );
						}
					}
				}
			}
		} // end if	
	
	} // end foreach
	
	// Return the new collection
	return apply_filters( 'cell_store_sanitize_payments', $output, $input );

} // end cell_store_sanitize_social_options

?>