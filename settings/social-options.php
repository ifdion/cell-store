<?php

/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */ 

/**
 * Provides default values for the Social Options.
 */
function cell_store_default_social_options() {
	
	$defaults = array(
		'twitter'		=>	'',
		'facebook'		=>	'',
		'instagram'	=>	'',
	);
	
	return apply_filters( 'cell_store_default_social_options', $defaults );
	
} // end cell_store_default_social_options

/**
 * Initializes the theme's social options by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */ 
function cell_store_initialize_social_options() {

	if( false == get_option( 'cell_store_social_options' ) ) {	
		add_option( 'cell_store_social_options', apply_filters( 'cell_store_default_social_options', cell_store_default_social_options() ) );
	} // end if
	
	add_settings_section(
		'store_pages_section',			// ID used to identify this section and with which to register options
		__( 'Social Options', 'cell_store' ),		// Title to be displayed on the administration page
		'cell_store_social_options_callback',	// Callback used to render the description of the section
		'cell_store_social_options'		// Page on which to add this section of options
	);
	
	add_settings_field(	
		'twitter',						
		'Twitter',							
		'cell_store_twitter_callback',	
		'cell_store_social_options',	
		'store_pages_section'			
	);

	add_settings_field(	
		'facebook',						
		'Facebook',							
		'cell_store_facebook_callback',	
		'cell_store_social_options',	
		'store_pages_section'			
	);
	
	add_settings_field(	
		'instagram',						
		'Instagram',							
		'cell_store_instagram_callback',	
		'cell_store_social_options',	
		'store_pages_section'			
	);

	add_settings_field(	
		'pinterest',						
		'Pinterest',							
		'cell_store_pinterest_callback',	
		'cell_store_social_options',	
		'store_pages_section'			
	);
	
	register_setting(
		'cell_store_social_options',
		'cell_store_social_options',
		'cell_store_sanitize_social_options'
	);
	
} // end cell_store_initialize_social_options
add_action( 'admin_init', 'cell_store_initialize_social_options' );

/* ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------ */ 

/**
 * This function provides a simple description for the Social Options page. 
 *
 * It's called from the 'cell_store_initialize_social_options' function by being passed as a parameter
 * in the add_settings_section function.
 */
function cell_store_social_options_callback() {
	echo '<p>' . __( 'Provide the URL to the social networks you\'d like to display.', 'cell_store' ) . '</p>';
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

function cell_store_twitter_callback() {
	
	// First, we read the social options collection
	$options = get_option( 'cell_store_social_options' );
	
	// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
	$url = '';
	if( isset( $options['twitter'] ) ) {
		$url = esc_url( $options['twitter'] );
	} // end if
	
	// Render the output
	echo '<input type="text" id="twitter" name="cell_store_social_options[twitter]" value="' . $url . '" />';
	
} // end cell_store_twitter_callback

function cell_store_facebook_callback() {
	
	$options = get_option( 'cell_store_social_options' );
	
	$url = '';
	if( isset( $options['facebook'] ) ) {
		$url = esc_url( $options['facebook'] );
	} // end if
	
	// Render the output
	echo '<input type="text" id="facebook" name="cell_store_social_options[facebook]" value="' . $url . '" />';
	
} // end cell_store_facebook_callback

function cell_store_instagram_callback() {
	
	$options = get_option( 'cell_store_social_options' );
	
	$url = '';
	if( isset( $options['instagram'] ) ) {
		$url = esc_url( $options['instagram'] );
	} // end if
	
	// Render the output
	echo '<input type="text" id="instagram" name="cell_store_social_options[instagram]" value="' . $url . '" />';
	
} // end cell_store_instagram_callback

function cell_store_pinterest_callback() {
	
	$options = get_option( 'cell_store_social_options' );
	
	$url = '';
	if( isset( $options['pinterest'] ) ) {
		$url = esc_url( $options['pinterest'] );
	} // end if
	
	// Render the output
	echo '<input type="text" id="pinterest" name="cell_store_social_options[pinterest]" value="' . $url . '" />';
	
} // end cell_store_pinterest_callback

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

function cell_store_sanitize_social_options( $input ) {
	
	// Define the array for the updated options
	$output = array();

	// Loop through each of the options sanitizing the data
	foreach( $input as $key => $val ) {
	
		if( isset ( $input[$key] ) ) {
			$output[$key] = esc_url(strip_tags( stripslashes( $input[$key] ) ) );
		} // end if	
	
	} // end foreach
	
	// Return the new collection
	return apply_filters( 'cell_store_sanitize_social_options', $output, $input );

} // end cell_store_sanitize_social_options 


?>