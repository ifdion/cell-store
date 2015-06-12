<?php

/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */ 

/**
 * Provides default values for the Social Options.
 */
function cell_store_default_pages() {
	
	$defaults = array(
		'shopping-cart'			=>	'shopping-cart',
		'checkout'				=>	'checkout',
		'payment-option'		=>	'payment-option',
		'terms-of-agreement'		=>	'terms-of-agreement',
		'order-confirmation'	=>	'order-confirmation',
		'thank-you'				=>	'thank-you',
		'login'					=>	'login',
		'logout'				=>	'logout',
		'register'				=>	'register',
		'profile'				=>	'profile',
		'my-order'				=>	'my-order',

	);
	
	return apply_filters( 'cell_store_default_pages', $defaults );
} // end cell_store_default_pages

/**
 * Initializes the theme's social options by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */ 
function cell_store_initialize_pages() {

	if( false == get_option( 'cell_store_pages' ) ) {	
		add_option( 'cell_store_pages', apply_filters( 'cell_store_default_pages', cell_store_default_pages() ) );
	} // end if
	
	add_settings_section(
		'store_pages_section',			// ID used to identify this section and with which to register options
		__( 'Store Pages', 'cell_store' ),		// Title to be displayed on the administration page
		'cell_store_pages_callback',	// Callback used to render the description of the section
		'cell_store_pages'		// Page on which to add this section of options
	);
	
	add_settings_field(	
		'shopping-cart',						
		'Shopping Cart Page',							
		'cell_store_shopping_cart_callback',	
		'cell_store_pages',	
		'store_pages_section'			
	);

	add_settings_field(	
		'checkout',						
		'Checkout Page',							
		'cell_store_checkout_callback',	
		'cell_store_pages',	
		'store_pages_section'			
	);
	
	add_settings_field(	
		'payment-option',						
		'Payment Option Page',							
		'cell_store_payment_option_callback',	
		'cell_store_pages',	
		'store_pages_section'			
	);

	add_settings_field(	
		'terms-of-agreement',						
		'Terms of Agreement',							
		'cell_store_terms_of_agreement_callback',	
		'cell_store_pages',	
		'store_pages_section'			
	);

	add_settings_field(	
		'order-confirmation',						
		'Order Confirmation Page',							
		'cell_store_order_confirmation_callback',	
		'cell_store_pages',	
		'store_pages_section'			
	);

	add_settings_field(	
		'thank-you',						
		'Thank You Page',							
		'cell_store_thank_you_callback',	
		'cell_store_pages',	
		'store_pages_section'			
	);
	
	register_setting(
		'cell_store_pages',
		'cell_store_pages',
		'cell_store_sanitize_pages'
	);
	
} // end cell_store_initialize_pages
add_action( 'admin_init', 'cell_store_initialize_pages' );

/* ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------ */ 

/**
 * This function provides a simple description for the Social Options page. 
 *
 * It's called from the 'cell_store_initialize_social_options' function by being passed as a parameter
 * in the add_settings_section function.
 */
function cell_store_pages_callback() {
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

function cell_store_shopping_cart_callback() {
	
	// First, we read the social options collection
	$options = get_option( 'cell_store_pages' );
	
	// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
	$url = '';
	if( isset( $options['shopping-cart'] ) ) {
		$url = esc_attr( $options['shopping-cart'] );
	} // end if
	
	// Render the output
	echo '<input type="text" id="shopping-cart" name="cell_store_pages[shopping-cart]" value="' . $url . '" />';
	
} // end cell_store_shopping_cart_callback

function cell_store_checkout_callback() {
	
	// First, we read the social options collection
	$options = get_option( 'cell_store_pages' );
	
	// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
	$url = '';
	if( isset( $options['checkout'] ) ) {
		$url = esc_attr( $options['checkout'] );
	} // end if
	
	// Render the output
	echo '<input type="text" id="checkout" name="cell_store_pages[checkout]" value="' . $url . '" />';
	
} // end cell_store_checkout_callback

function cell_store_payment_option_callback() {
	
	// First, we read the social options collection
	$options = get_option( 'cell_store_pages' );
	
	// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
	$url = '';
	if( isset( $options['payment-option'] ) ) {
		$url = esc_attr( $options['payment-option'] );
	} // end if
	
	// Render the output
	echo '<input type="text" id="payment-option" name="cell_store_pages[payment-option]" value="' . $url . '" />';
	
} // end cell_store_payment_option_callback

function cell_store_terms_of_agreement_callback () {
	
	// First, we read the social options collection
	$options = get_option( 'cell_store_pages' );
	
	// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
	$url = '';
	if( isset( $options['terms-of-agreement'] ) ) {
		$url = esc_attr( $options['terms-of-agreement'] );
	} // end if
	
	// Render the output
	echo '<input type="text" id="terms-of-agreement" name="cell_store_pages[terms-of-agreement]" value="' . $url . '" />';
	
} // end cell_store_terms_of_agreement_callback

function cell_store_order_confirmation_callback() {
	
	// First, we read the social options collection
	$options = get_option( 'cell_store_pages' );
	
	// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
	$url = '';
	if( isset( $options['order-confirmation'] ) ) {
		$url = esc_attr( $options['order-confirmation'] );
	} // end if
	
	// Render the output
	echo '<input type="text" id="order-confirmation" name="cell_store_pages[order-confirmation]" value="' . $url . '" />';
	
} // end cell_store_order_confirmation_callback

function cell_store_thank_you_callback() {
	
	// First, we read the social options collection
	$options = get_option( 'cell_store_pages' );
	
	// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
	$url = '';
	if( isset( $options['thank-you'] ) ) {
		$url = esc_attr( $options['thank-you'] );
	} // end if
	
	// Render the output
	echo '<input type="text" id="thank-you" name="cell_store_pages[thank-you]" value="' . $url . '" />';
	
} // end cell_store_thank_you_callback

/* ------------------------------------------------------------------------ *
 * Setting Callbacks
 * ------------------------------------------------------------------------ */ 
 
 
/**
 * Sanitization callback for the social options. Since each of the social options are text inputs,
 * this function loops through the incoming option and strips all tags and slashes from the value
 * before serializing it.
 *	
 * @params	$input	The unsanitized collection of options.
 *
 * @returns			The collection of sanitized values.
 */

function cell_store_sanitize_pages( $input ) {
	
	// Define the array for the updated options
	$output = array();

	// Loop through each of the options sanitizing the data
	foreach( $input as $key => $val ) {
	
		if( isset ( $input[$key] ) ) {
			$output[$key] = strip_tags( stripslashes( $input[$key] ) );
		} // end if	
	
	} // end foreach
	
	// Return the new collection
	return apply_filters( 'cell_store_sanitize_pages', $output, $input );

} // end cell_store_sanitize_social_options

?>