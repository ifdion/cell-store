<?php

/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */ 

/**
 * Provides default values for the Input Options.
 */
function cell_store_default_input_options() {
	
	$defaults = array(
		'input_example'		=>	'',
		'textarea_example'	=>	'',
		'checkbox_example'	=>	'',
		'radio_example'		=>	'',
		'time_options'		=>	'default'	
	);
	
	return apply_filters( 'cell_store_default_input_options', $defaults );
	
} // end cell_store_default_input_options

/**
 * Initializes the theme's input example by registering the Sections,
 * Fields, and Settings. This particular group of options is used to demonstration
 * validation and sanitization.
 *
 * This function is registered with the 'admin_init' hook.
 */ 
function cell_store_initialize_input_examples() {

	if( false == get_option( 'cell_store_input_examples' ) ) {	
		add_option( 'cell_store_input_examples', apply_filters( 'cell_store_default_input_options', cell_store_default_input_options() ) );
	} // end if

	add_settings_section(
		'input_examples_section',
		__( 'Input Examples', 'cell_store' ),
		'cell_store_input_examples_callback',
		'cell_store_input_examples'
	);
	
	add_settings_field(	
		'Input Element',						
		__( 'Input Element', 'cell_store' ),							
		'cell_store_input_element_callback',	
		'cell_store_input_examples',	
		'input_examples_section'			
	);
	
	add_settings_field(	
		'Textarea Element',						
		__( 'Textarea Element', 'cell_store' ),							
		'cell_store_textarea_element_callback',	
		'cell_store_input_examples',	
		'input_examples_section'			
	);
	
	add_settings_field(
		'Checkbox Element',
		__( 'Checkbox Element', 'cell_store' ),
		'cell_store_checkbox_element_callback',
		'cell_store_input_examples',
		'input_examples_section'
	);
	
	add_settings_field(
		'Radio Button Elements',
		__( 'Radio Button Elements', 'cell_store' ),
		'cell_store_radio_element_callback',
		'cell_store_input_examples',
		'input_examples_section'
	);
	
	add_settings_field(
		'Select Element',
		__( 'Select Element', 'cell_store' ),
		'cell_store_select_element_callback',
		'cell_store_input_examples',
		'input_examples_section'
	);
	
	register_setting(
		'cell_store_input_examples',
		'cell_store_input_examples',
		'cell_store_validate_input_examples'
	);

} // end cell_store_initialize_input_examples
add_action( 'admin_init', 'cell_store_initialize_input_examples' );

/* ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------ */ 

/**
 * This function provides a simple description for the Input Examples page.
 *
 * It's called from the 'cell_store_initialize_input_examples_options' function by being passed as a parameter
 * in the add_settings_section function.
 */
function cell_store_input_examples_callback() {
	echo '<p>' . __( 'Provides examples of the five basic element types.', 'cell_store' ) . '</p>';
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

function cell_store_input_element_callback() {
	
	$options = get_option( 'cell_store_input_examples' );
	
	// Render the output
	echo '<input type="text" id="input_example" name="cell_store_input_examples[input_example]" value="' . $options['input_example'] . '" />';
	
} // end cell_store_input_element_callback

function cell_store_textarea_element_callback() {
	
	$options = get_option( 'cell_store_input_examples' );
	
	// Render the output
	echo '<textarea id="textarea_example" name="cell_store_input_examples[textarea_example]" rows="5" cols="50">' . $options['textarea_example'] . '</textarea>';
	
} // end cell_store_textarea_element_callback

function cell_store_checkbox_element_callback() {

	$options = get_option( 'cell_store_input_examples' );
	
	$html = '<input type="checkbox" id="checkbox_example" name="cell_store_input_examples[checkbox_example]" value="1"' . checked( 1, $options['checkbox_example'], false ) . '/>';
	$html .= '&nbsp;';
	$html .= '<label for="checkbox_example">This is an example of a checkbox</label>';
	
	echo $html;

} // end cell_store_checkbox_element_callback

function cell_store_radio_element_callback() {

	$options = get_option( 'cell_store_input_examples' );
	
	$html = '<input type="radio" id="radio_example_one" name="cell_store_input_examples[radio_example]" value="1"' . checked( 1, $options['radio_example'], false ) . '/>';
	$html .= '&nbsp;';
	$html .= '<label for="radio_example_one">Option One</label>';
	$html .= '&nbsp;';
	$html .= '<input type="radio" id="radio_example_two" name="cell_store_input_examples[radio_example]" value="2"' . checked( 2, $options['radio_example'], false ) . '/>';
	$html .= '&nbsp;';
	$html .= '<label for="radio_example_two">Option Two</label>';
	
	echo $html;

} // end cell_store_radio_element_callback

function cell_store_select_element_callback() {

	$options = get_option( 'cell_store_input_examples' );
	
	$html = '<select id="time_options" name="cell_store_input_examples[time_options]">';
		$html .= '<option value="default">' . __( 'Select a time option...', 'cell_store' ) . '</option>';
		$html .= '<option value="never"' . selected( $options['time_options'], 'never', false) . '>' . __( 'Never', 'cell_store' ) . '</option>';
		$html .= '<option value="sometimes"' . selected( $options['time_options'], 'sometimes', false) . '>' . __( 'Sometimes', 'cell_store' ) . '</option>';
		$html .= '<option value="always"' . selected( $options['time_options'], 'always', false) . '>' . __( 'Always', 'cell_store' ) . '</option>';	$html .= '</select>';
	
	echo $html;

} // end cell_store_radio_element_callback


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

function cell_store_validate_input_examples( $input ) {

	// Create our array for storing the validated options
	$output = array();
	
	// Loop through each of the incoming options
	foreach( $input as $key => $value ) {
		
		// Check to see if the current option has a value. If so, process it.
		if( isset( $input[$key] ) ) {
		
			// Strip all HTML and PHP tags and properly handle quoted strings
			$output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
			
		} // end if
		
	} // end foreach
	
	// Return the array processing any additional functions filtered by this action
	return apply_filters( 'cell_store_validate_input_examples', $output, $input );

} // end cell_store_validate_input_examples 
 


?>