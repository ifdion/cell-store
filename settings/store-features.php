<?php

/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */ 

/**
 * Provides default values for the Store Settings.
 */
function cell_store_features() {
	
	$defaults = array(
		'enable-coupon'		=>	'',
		'enable-lookbook'		=>	'',
		'enable-banner-management'		=>	'',
		'shipping_destination'		=>	'custom',
		'rajaongkir_api'		=>	'',
		'shipping_origin'		=>	'',
		'international_shipping'		=>	'',
		'currency'		=>	'IDR',
		'secondary-currency'		=>	'USD',
		'weight-unit'		=>	'KG',
		'cancelation-due'		=>	1,
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
		'enable_coupon',// ID used to identify the field throughout the theme
		__( 'Enable Coupon', 'cell_store' ),// The label to the left of the option interface element
		'cell_store_enable_coupon_callback',	// The name of the function responsible for rendering the option interface
		'cell_store_features',	// The page on which this option will be displayed
		'general_settings_section',			// The name of the section to which this field belongs
		array(// The array of arguments to pass to the callback. In this case, just a description.
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
		'enable_banner_management',
		__( 'Enable Banner Management', 'cell_store' ),
		'cell_store_enable_banner_management_callback',	
		'cell_store_features',
		'general_settings_section',			
		array(
			__( 'Activate this setting to display the content.', 'cell_store' ),
		)
	);

	add_settings_field(	
		'shipping_destination',
		'Shipping Destination',
		'cell_store_shipping_destination_callback',	
		'cell_store_features',	
		'general_settings_section'
	);

	add_settings_field(	
		'rajaongkir_api',
		'Rajaongkir API Key',
		'cell_store_rajaongkir_api_callback',	
		'cell_store_features',	
		'general_settings_section'
	);

	$options = get_option( 'cell_store_features' );
	if (isset($options['shipping_destination']) && isset($options['rajaongkir_api']) && $options['shipping_destination'] == 'rajaongkir') {
		add_settings_field(	
			'shipping_origin',
			'Shipping Origin',
			'cell_store_shipping_origin_callback',	
			'cell_store_features',	
			'general_settings_section'
		);
	}

	add_settings_field(	
		'international_shipping',
		'International Shipping',
		'cell_store_international_shipping_callback',	
		'cell_store_features',	
		'general_settings_section',
		array(
			__( 'Activate this setting to enable international shipping.', 'cell_store' ),
		)
	);

	add_settings_field(	
		'currency',
		'Currency',
		'cell_store_currency_callback',	
		'cell_store_features',	
		'general_settings_section'
	);

	add_settings_field(	
		'secondary_currency',
		'Secondary Currency',
		'cell_store_secondary_currency_callback',	
		'cell_store_features',	
		'general_settings_section'
	);

	add_settings_field(	
		'open_exchange_id',
		'Open Exchange ID',
		'cell_store_open_exchange_id_callback',	
		'cell_store_features',	
		'general_settings_section'
	);

	add_settings_field(	
		'weight_unit',
		'Weight Unit',
		'cell_store_weight_unit_callback',	
		'cell_store_features',	
		'general_settings_section'
	);

	add_settings_field(	
		'cancelation_due',
		__( 'Cancelation Due', 'cell_store' ),
		'cell_store_cancelation_due_callback',	
		'cell_store_features',
		'general_settings_section',			
		array(
			__( 'Number of days before pending payments are cancelled.', 'cell_store' ),
		)
	);
	
	// add_settings_field(	
	// 	'show_footer',
	// 	__( 'Footer', 'cell_store' ),				
	// 	'cell_store_toggle_footer_callback',	
	// 	'cell_store_features',		
	// 	'general_settings_section',			
	// 	array(
	// 		__( 'Activate this setting to display the footer.', 'cell_store' ),
	// 	)
	// );
	
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
	$html = '<input type="checkbox" id="enable-coupon" name="cell_store_features[enable-coupon]" value="1" ' . checked( 1, isset( $options['enable-coupon'] ) ? $options['enable-coupon'] : 0, false ) . '/>'; 
	
	// Here, we'll take the first argument of the array and add it to a label next to the checkbox
	$html .= '<label for="enable-coupon">&nbsp;'  . $args[0] . '</label>'; 
	
	echo $html;
	
} // end cell_store_enable_coupon_callback

function cell_store_enable_lookbook_callback($args) {

	$options = get_option('cell_store_features');
	
	$html = '<input type="checkbox" id="enable-lookbook" name="cell_store_features[enable-lookbook]" value="1" ' . checked( 1, isset( $options['enable-lookbook'] ) ? $options['enable-lookbook'] : 0, false ) . '/>'; 
	$html .= '<label for="enable-lookbook">&nbsp;'  . $args[0] . '</label>'; 
	
	echo $html;
	
} // end cell_store_enable_lookbook_callback

function cell_store_enable_banner_management_callback($args) {

	$options = get_option('cell_store_features');
	
	$html = '<input type="checkbox" id="enable_banner_management" name="cell_store_features[enable-banner-management]" value="1" ' . checked( 1, isset( $options['enable-banner-management'] ) ? $options['enable-banner-management'] : 0, false ) . '/>'; 
	$html .= '<label for="enable-banner-management">&nbsp;'  . $args[0] . '</label>'; 
	
	echo $html;
	
} // end cell_store_enable_banner_management_callback


function cell_store_shipping_destination_callback() {

	$options = get_option( 'cell_store_features' );

	$options['shipping_destination'] = (isset($options['shipping_destination']) ? $options['shipping_destination'] : 'custom');
	
	$html = '<select id="shipping_destination" name="cell_store_features[shipping_destination]">';
		$html .= '<option value="custom">' . __( 'Custom Shipping Destination', 'cell_store' ) . '</option>';
		$html .= '<option value="rajaongkir"' . selected( $options['shipping_destination'], 'rajaongkir', false) . '>' . __( 'Rajaongkir', 'cell_store' ) . '</option>';
	
	echo $html;

} // end cell_store_radio_element_callback

function cell_store_shipping_origin_callback() {

	$options = get_option( 'cell_store_features' );
	$options['shipping_origin'] = (isset($options['shipping_origin']) ? $options['shipping_origin'] : '');

	if ( false === ( $cities = get_transient( 'rajaongkir_cities' ) ) ) {

	  // It wasn't there, so regenerate the data and save the transient
		$options = get_option( 'cell_store_features' );
		$key = $options['rajaongkir_api'];
		$result = wp_remote_get( 'http://pro.rajaongkir.com/api/city?key='.$key );
		$cities = wp_remote_retrieve_body( $result );

	  set_transient( 'rajaongkir_cities', $cities, DAY_IN_SECONDS );
	}

	$cities = json_decode($cities);
	$cities = $cities->rajaongkir->results;

	$html = '<select id="shipping_origin" name="cell_store_features[shipping_origin]">';
	$html .= '<option value="custom">' . __( 'Select Shipping Origin', 'cell_store' ) . '</option>';

	foreach ($cities as $city => $value) {
		$html .= '<option value="'.$value->city_id.'"' . selected( $options['shipping_origin'], $value->city_id, false) . '>' . $value->city_name .' '.$value->type . '</option>';
	}
	
	
	echo $html;

} // end cell_store_radio_element_callback


function cell_store_rajaongkir_api_callback() {
	
	// First, we read the social options collection
	$options = get_option( 'cell_store_features' );
	
	// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
	$url = '';
	if( isset( $options['rajaongkir_api'] ) ) {
		$url = esc_attr( $options['rajaongkir_api'] );
	} // end if
	
	// Render the output
	echo '<input type="text" id="rajaongkir_api" name="cell_store_features[rajaongkir_api]" value="' . $url . '" />';
	
} // end cell_store_rajaongkir_api_callback

function cell_store_international_shipping_callback($args) {

	$options = get_option('cell_store_features');
	
	$html = '<input type="checkbox" id="international_shipping" name="cell_store_features[international_shipping]" value="1" ' . checked( 1, isset( $options['international_shipping'] ) ? $options['international_shipping'] : 0, false ) . '/>'; 
	$html .= '<label for="international_shipping">&nbsp;'  . $args[0] . '</label>'; 
	
	echo $html;
	
} // end cell_store_international_shipping_callback

function cell_store_currency_callback() {
	
	// First, we read the social options collection
	$options = get_option( 'cell_store_features' );
	
	// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
	$url = '';
	if( isset( $options['currency'] ) ) {
		$url = esc_attr( $options['currency'] );
	} // end if
	
	// Render the output
	echo '<input type="text" id="currency" name="cell_store_features[currency]" value="' . $url . '" />';
	
} // end cell_store_currency_callback

function cell_store_secondary_currency_callback() {
	
	// First, we read the social options collection
	$options = get_option( 'cell_store_features' );
	
	// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
	$url = '';
	if( isset( $options['secondary-currency'] ) ) {
		$url = esc_attr( $options['secondary-currency'] );
	} // end if
	
	// Render the output
	echo '<input type="text" id="secondary-currency" name="cell_store_features[secondary-currency]" value="' . $url . '" />';
	
} // end cell_store_secondary_currency_callback

function cell_store_open_exchange_id_callback() {
	
	// First, we read the social options collection
	$options = get_option( 'cell_store_features' );
	
	// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
	$url = '';
	if( isset( $options['open-exchange-id'] ) ) {
		$url = esc_attr( $options['open-exchange-id'] );
	} // end if
	
	// Render the output
	echo '<input type="text" id="open-exchange-id" name="cell_store_features[open-exchange-id]" value="' . $url . '" />';
	
} // end cell_store_open_exchange_id_callback

function cell_store_weight_unit_callback() {
	
	// First, we read the social options collection
	$options = get_option( 'cell_store_features' );
	
	// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
	$url = '';
	if( isset( $options['weight-unit'] ) ) {
		$url = esc_attr( $options['weight-unit'] );
	} // end if
	
	// Render the output
	echo '<input type="text" id="weight-unit" name="cell_store_features[weight-unit]" value="' . $url . '" />';
	
} // end cell_store_weight_unit_callback

function cell_store_cancelation_due_callback($args) {

	$options = get_option('cell_store_features');
	
	// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
	$url = '';
	if( isset( $options['cancelation-due'] ) ) {
		$url = esc_attr( $options['cancelation-due'] );
	} // end if
	
	// Render the output
	echo '<input type="text" id="cancelation-due" name="cell_store_features[cancelation-due]" value="' . $url . '" />';
	echo '<label for="cancelation_due">&nbsp;'  . $args[0] . '</label>'; 
	
} // end cell_store_cancelation_due_callback

// function cell_store_toggle_footer_callback($args) {
	
// 	$options = get_option('cell_store_features');
	
// 	$html = '<input type="checkbox" id="show_footer" name="cell_store_features[show_footer]" value="1" ' . checked( 1, isset( $options['show_footer'] ) ? $options['show_footer'] : 0, false ) . '/>'; 
// 	$html .= '<label for="show_footer">&nbsp;'  . $args[0] . '</label>'; 
	
// 	echo $html;
	
// } // end cell_store_toggle_footer_callback

?>