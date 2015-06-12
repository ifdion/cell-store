<?php

/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */ 

/**
 * Provides default values for the Store Settings.
 */
function cell_store_features() {
	
	$defaults = array(
		'enable_coupon'		=>	'',
		'enable_lookbook'		=>	'',
		'show_footer'		=>	'',
	);
	
	return apply_filters( 'cell_store_features', $defaults );
} // end cell_store_features

/**
 * Initializes the theme's display options page by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */ 
function cell_store_initialize_theme_options() {

	// If the theme options don't exist, create them.
	if( false == get_option( 'cell_store_features' ) ) {	
		add_option( 'cell_store_features', apply_filters( 'cell_store_features', cell_store_features() ) );
	} // end if

	// First, we register a section. This is necessary since all future options must belong to a 
	add_settings_section(
		'general_settings_section',			// ID used to identify this section and with which to register options
		__( 'Store Settings', 'cell_store' ),		// Title to be displayed on the administration page
		'cell_store_general_options_callback',	// Callback used to render the description of the section
		'cell_store_features'		// Page on which to add this section of options
	);
	
	// Next, we'll introduce the fields for toggling the visibility of content elements.
	add_settings_field(	
		'enable_coupon',						// ID used to identify the field throughout the theme
		__( 'Enable Coupon', 'cell_store' ),							// The label to the left of the option interface element
		'cell_store_enable_coupon_callback',	// The name of the function responsible for rendering the option interface
		'cell_store_features',	// The page on which this option will be displayed
		'general_settings_section',			// The name of the section to which this field belongs
		array(								// The array of arguments to pass to the callback. In this case, just a description.
			__( 'Activate this setting to display the header.', 'cell_store' ),
		)
	);
	
	add_settings_field(	
		'enable_lookbook',						
		__( 'Enable Lookbook', 'cell_store' ),				
		'cell_store_enable_lookbook_callback',	
		'cell_store_features',					
		'general_settings_section',			
		array(								
			__( 'Activate this setting to display the content.', 'cell_store' ),
		)
	);
	
	add_settings_field(	
		'show_footer',						
		__( 'Footer', 'cell_store' ),				
		'cell_store_toggle_footer_callback',	
		'cell_store_features',		
		'general_settings_section',			
		array(								
			__( 'Activate this setting to display the footer.', 'cell_store' ),
		)
	);
	
	// Finally, we register the fields with WordPress
	register_setting(
		'cell_store_features',
		'cell_store_features'
	);
	
} // end cell_store_initialize_theme_options
add_action( 'admin_init', 'cell_store_initialize_theme_options' );

/* ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------ */ 

/**
 * This function provides a simple description for the General Options page. 
 *
 * It's called from the 'cell_store_initialize_theme_options' function by being passed as a parameter
 * in the add_settings_section function.
 */
function cell_store_general_options_callback() {
	echo '<p>' . __( 'Select which areas of content you wish to display.', 'cell_store' ) . '</p>';
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
function cell_store_enable_coupon_callback($args) {
	
	// First, we read the options collection
	$options = get_option('cell_store_features');
	
	// Next, we update the name attribute to access this element's ID in the context of the display options array
	// We also access the enable_coupon element of the options collection in the call to the checked() helper function
	$html = '<input type="checkbox" id="enable_coupon" name="cell_store_features[enable_coupon]" value="1" ' . checked( 1, isset( $options['enable_coupon'] ) ? $options['enable_coupon'] : 0, false ) . '/>'; 
	
	// Here, we'll take the first argument of the array and add it to a label next to the checkbox
	$html .= '<label for="enable_coupon">&nbsp;'  . $args[0] . '</label>'; 
	
	echo $html;
	
} // end cell_store_enable_coupon_callback

function cell_store_enable_lookbook_callback($args) {

	$options = get_option('cell_store_features');
	
	$html = '<input type="checkbox" id="enable_lookbook" name="cell_store_features[enable_lookbook]" value="1" ' . checked( 1, isset( $options['enable_lookbook'] ) ? $options['enable_lookbook'] : 0, false ) . '/>'; 
	$html .= '<label for="enable_lookbook">&nbsp;'  . $args[0] . '</label>'; 
	
	echo $html;
	
} // end cell_store_enable_lookbook_callback

function cell_store_toggle_footer_callback($args) {
	
	$options = get_option('cell_store_features');
	
	$html = '<input type="checkbox" id="show_footer" name="cell_store_features[show_footer]" value="1" ' . checked( 1, isset( $options['show_footer'] ) ? $options['show_footer'] : 0, false ) . '/>'; 
	$html .= '<label for="show_footer">&nbsp;'  . $args[0] . '</label>'; 
	
	echo $html;
	
} // end cell_store_toggle_footer_callback

?>