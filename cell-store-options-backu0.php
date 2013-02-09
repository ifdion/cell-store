<?php

define('NHP_OPTIONS_URL', site_url('/wp-content/plugins/cell-store/options/'));
if(!class_exists('NHP_Options')){
	require_once( dirname( __FILE__ ) . '/options/options.php' );
}

function setup_framework_options(){
	$args = array();
	$args['dev_mode'] = false;
	$args['show_import_export'] = false;
	$args['opt_name'] = 'cell-store';
	$args['menu_title'] = __('Store Options', 'cell-store');
	$args['page_title'] = __('Store Options', 'cell-store');
	$args['page_slug'] = 'cell-store-options';
	$args['page_cap'] = 'manage_options';
	$args['page_position'] = 27;
	$args['allow_sub_menu'] = false;

	$std['admin_email'] = get_bloginfo('admin_email');
	$std['url'] = get_bloginfo('url');


	$sections = array();

	$sections[] = array(
		'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_023_cogwheels.png',
		'title' => __('General Options', 'cell-store'),
		'desc' => __('<p class="description">General Store Options</p>', 'cell-store'),
		'fields'=> array(
			array(
				'id' => 'general_email',
				'type' => 'text',
				'title' => __('Email address to send correspondence', 'cell-store'), 
				'std' => $std['admin_email']
				),
			array(
				'id' => 'product_slug',
				'type' => 'text',
				'title' => __('Product Slug', 'cell-store'), 
				'sub_desc' => 'This will be used as the slug for the product archive e.g : '. $std['url'].'/product/product-name',
				'std' => 'product'
				),
			array(
				'id' => 'product_category_slug',
				'type' => 'text',
				'title' => __('Product Category Slug', 'cell-store'), 
				'sub_desc' => 'This will be used as the slug for the product category e.g : '. $std['url'].'/product-category',
				'std' => 'product-category'
				),
			array(
				'id' => 'product_tag_slug',
				'type' => 'text',
				'title' => __('Product Tag Slug', 'cell-store'), 
				'sub_desc' => 'This will be used as the slug for the product tag e.g : '. $std['url'].'/product-tag',
				'std' => 'product-tag'
				),
			array(
				'id' => 'redirect_after_buy',
				'type' => 'select',
				'title' => __('Redirection after Buy', 'cell-store'),
				'options' => array('stay' => 'Stay in same page','checkout' => 'Checkout','shopping-cart'=>'Shopping Cart'),
				'std' => 'shopping-cart'
				),
			array(
				'id' => 'cancelation_treshold',
				'type' => 'text',
				'title' => __('Days before unconfirmed order cancelation', 'cell-store'), 
				'std' => '1'
				),
			)
		);
				
	$sections[] = array(
		'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_155_show_thumbnails.png',
		'title' => __('Store Pages', 'cell-store'),
		'desc' => __('<p class="description">Choose which page is used as a store page.</p>', 'cell-store'),
		'fields'=> array(
			array(
				'id' => 'shopping_cart_page',
				'type' => 'pages_select',
				'title' => __('Shopping Cart Page', 'cell-store'), 
				),
			array(
				'id' => 'check_out_page',
				'type' => 'pages_select',
				'title' => __('Check Out Page', 'cell-store'), 
				),
			array(
				'id' => 'payment_option_page',
				'type' => 'pages_select',
				'title' => __('Payment Option Page', 'cell-store'), 
				),
			array(
				'id' => 'order_confirmation_page',
				'type' => 'pages_select',
				'title' => __('Order Confirmation Page', 'cell-store'), 
				),
			array(
				'id' => 'payment_confirmation_page',
				'type' => 'pages_select',
				'title' => __('Payment Confirmation Page', 'cell-store'), 
				),
			array(
				'id' => 'login_page',
				'type' => 'pages_select',
				'title' => __('Login Page', 'cell-store'), 
				),
			array(
				'id' => 'profile_page',
				'type' => 'pages_select',
				'title' => __('Profile Page', 'cell-store'), 
				),
			array(
				'id' => 'my_transaction_page',
				'type' => 'pages_select',
				'title' => __('My Transaction Page', 'cell-store'), 
				),
			)
		);

	$sections[] = array(
		'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_060_compass.png',
		'title' => __('Store Details', 'cell-store'),
		'desc' => __('<p class="description">Basic Store details for invoice and correspondence.</p>', 'cell-store'),
		'fields'=> array(
			array(
				'id' => 'store_name',
				'type' => 'text',
				'title' => __('Store Name', 'cell-store'), 
				),
			array(
				'id' => 'store_address',
				'type' => 'textarea',
				'title' => __('Store Address', 'cell-store'), 
				'validate' => 'no_html'
				),
			array(
				'id' => 'store_phone_1',
				'type' => 'text',
				'title' => __('Store Phone Number', 'cell-store'), 
				),
			array(
				'id' => 'store_phone_2',
				'type' => 'text',
				'title' => __('Store Phone Number (2)', 'cell-store'), 
				),
			array(
				'id' => 'store_email',
				'type' => 'text',
				'title' => __('Store Email', 'cell-store'), 
				'validate' =>'email',
				),
			)
		);

	$sections[] = array(
		'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_318_more-items.png',
		'title' => __('PayPal', 'cell-store'),
		'desc' => __('<p class="description">Basic setup for PayPal payment gateway.</p>', 'cell-store'),
		'fields'=> array(
			array(
				'id' => 'paypal_enable',
				'type' => 'checkbox',
				'title' => __('Enable', 'cell-store'), 
				'desc' => __('Check to enable', 'cell-store'),
				'std' => '1'
				),
			array(
				'id' => 'paypal_title',
				'type' => 'text',
				'title' => __('Title', 'cell-store'), 
				),
			array(
				'id' => 'paypal_upload',
				'type' => 'upload',
				'title' => __('Image Upload', 'cell-store'), 
				'desc' => __('This is the image that will be used before the title.', 'cell-store')
				),
			array(
				'id' => 'paypal_description',
				'type' => 'textarea',
				'title' => __('Description', 'cell-store'), 
				),
			array(
				'id' => 'paypal_email',
				'type' => 'text',
				'title' => __('Email Account', 'cell-store'), 
				// 'sub_desc' => __('This is the page that uses <code>[cell-shopping-cart]</code> shortcode. By default it will find page with the slug <code> shopping-cart</code>', 'cell-store'),
				),
			array(
				'id' => 'paypal_sandbox',
				'type' => 'checkbox',
				'title' => __('Enable Sandbox Mode', 'cell-store'), 
				'desc' => __('Check to enable', 'cell-store'),
				'std' => '1'
				),
			array(
				'id' => 'paypal_sandbox_email',
				'type' => 'text',
				'title' => __('Sandbox Email Account', 'cell-store'), 
				'validate' =>'email',
				),
			)
		);

	$sections[] = array(
		'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_263_bank.png',
		'title' => __('Bank', 'cell-store'),
		'desc' => __('<p class="description">Basic setup for Bank Wire Transfer payment gateway.</p>', 'cell-store'),
		'fields'=> array(
			array(
				'id' => 'bank_1_title',
				'type' => 'text',
				'title' => __('Bank 1 : Title', 'cell-store'), 
				),
			array(
				'id' => 'bank_1_description',
				'type' => 'textarea',
				'title' => __('Bank 1 : Description', 'cell-store'), 
				),
			array(
				'id' => 'bank_2_enable',
				'type' => 'checkbox',
				'title' => __('Enable Bank 2', 'cell-store'), 
				'desc' => __('Check to enable', 'cell-store'),
				'std' => '1'
				),
			array(
				'id' => 'bank_2_title',
				'type' => 'text',
				'title' => __('Bank 2 : Title', 'cell-store'), 
				),
			array(
				'id' => 'bank_2_description',
				'type' => 'textarea',
				'title' => __('Bank 2 : Description', 'cell-store'), 
				),
			array(
				'id' => 'bank_3_enable',
				'type' => 'checkbox',
				'title' => __('Enable Bank 3', 'cell-store'), 
				'desc' => __('Check to enable', 'cell-store'),
				'std' => '1'
				),
			array(
				'id' => 'bank_3_title',
				'type' => 'text',
				'title' => __('Bank 3 : Title', 'cell-store'), 
				),
			array(
				'id' => 'bank_3_description',
				'type' => 'textarea',
				'title' => __('Bank 3 : Description', 'cell-store'), 
				),
			)
		);

	$sections[] = array(
		'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_037_credit.png',
		'title' => __('Pricing', 'cell-store'),
		'desc' => __('<p class="description">Basic setup for Pricing.</p>', 'cell-store'),
		'fields'=> array(
			array(
				'id' => 'currency_display',
				'type' => 'text',
				'title' => __('Currency Display', 'cell-store'), 
				'std' => 'IDR'
				),
			array(
				'id' => 'currency_thousand_separator',
				'type' => 'text',
				'title' => __('Thousand Separator', 'cell-store'), 
				'std' => '.'
				),
			array(
				'id' => 'currency_decimal_separator',
				'type' => 'text',
				'title' => __('Decimal Separator', 'cell-store'), 
				'std' => ','
				),
			array(
				'id' => 'currency_decimal_digit',
				'type' => 'text',
				'title' => __('Number of Decimal', 'cell-store'), 
				'std' => '2'
				),
			array(
				'id' => 'currency_placement',
				'type' => 'select',
				'title' => __('Currency Placement', 'cell-store'),
				'options' => array('1' => 'Before value','after' => 'After value'),
				),
			)
		);

	// $sections[] = array(
	// 	'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_107_text_resize.png',
	// 	'title' => __('Text Fields', 'cell-store'),
	// 	'desc' => __('<p class="description">This is the Description. Again HTML is allowed2</p>', 'cell-store'),
	// 	'fields' => array(
	// 		array(
	// 			'id' => '1', //must be unique
	// 			'type' => 'text', //builtin fields include:
	// 							  //text|textarea|editor|checkbox|multi_checkbox|radio|radio_img|button_set|select|multi_select|color|date|divide|info|upload
	// 			'title' => __('Text Option', 'cell-store'),
	// 			'sub_desc' => __('This is a little space under the Field Title in the Options table, additonal info is good in here.', 'cell-store'),
				
	// 			//'validate' => '', //builtin validation includes: email|html|html_custom|no_html|js|numeric|url
	// 			//'msg' => 'custom error message', //override the default validation error message for specific fields
	// 			//'std' => '', //This is a default value, used to set the options on theme activation, and if the user hits the Reset to defaults Button
	// 			//'class' => '' //Set custom classes for elements if you want to do something a little different - default is "regular-text"
	// 			),
	// 		array(
	// 			'id' => '2',
	// 			'type' => 'text',
	// 			'title' => __('Text Option - Email Validated', 'cell-store'),
	// 			'sub_desc' => __('This is a little space under the Field Title in the Options table, additonal info is good in here.', 'cell-store'),
				
	// 			'validate' => 'email',
	// 			'msg' => 'custom error message',
	// 			'std' => 'test@test.com'
	// 			),
	// 		array(
	// 			'id' => 'multi_text',
	// 			'type' => 'multi_text',
	// 			'title' => __('Multi Text Option', 'cell-store'),
	// 			'sub_desc' => __('This is a little space under the Field Title in the Options table, additonal info is good in here.', 'cell-store'),
	// 			'desc' => __('This is the description field, again good for additional info.', 'cell-store')
	// 			),
	// 		array(
	// 			'id' => '3',
	// 			'type' => 'text',
	// 			'title' => __('Text Option - URL Validated', 'cell-store'),
	// 			'sub_desc' => __('This must be a URL.', 'cell-store'),
				
	// 			'validate' => 'url',
	// 			'std' => 'http://no-half-pixels.com'
	// 			),
	// 		array(
	// 			'id' => '4',
	// 			'type' => 'text',
	// 			'title' => __('Text Option - Numeric Validated', 'cell-store'),
	// 			'sub_desc' => __('This must be numeric.', 'cell-store'),
				
	// 			'validate' => 'numeric',
	// 			'std' => '0',
	// 			'class' => 'small-text'
	// 			),
	// 		array(
	// 			'id' => 'comma_numeric',
	// 			'type' => 'text',
	// 			'title' => __('Text Option - Comma Numeric Validated', 'cell-store'),
	// 			'sub_desc' => __('This must be a comma seperated string of numerical values.', 'cell-store'),
				
	// 			'validate' => 'comma_numeric',
	// 			'std' => '0',
	// 			'class' => 'small-text'
	// 			),
	// 		array(
	// 			'id' => 'no_special_chars',
	// 			'type' => 'text',
	// 			'title' => __('Text Option - No Special Chars Validated', 'cell-store'),
	// 			'sub_desc' => __('This must be a alpha numeric only.', 'cell-store'),
				
	// 			'validate' => 'no_special_chars',
	// 			'std' => '0'
	// 			),
	// 		array(
	// 			'id' => 'str_replace',
	// 			'type' => 'text',
	// 			'title' => __('Text Option - Str Replace Validated', 'cell-store'),
	// 			'sub_desc' => __('You decide.', 'cell-store'),
				
	// 			'validate' => 'str_replace',
	// 			'str' => array('search' => ' ', 'replacement' => 'thisisaspace'),
	// 			'std' => '0'
	// 			),
	// 		array(
	// 			'id' => 'preg_replace',
	// 			'type' => 'text',
	// 			'title' => __('Text Option - Preg Replace Validated', 'cell-store'),
	// 			'sub_desc' => __('You decide.', 'cell-store'),
				
	// 			'validate' => 'preg_replace',
	// 			'preg' => array('pattern' => '/[^a-zA-Z_ -]/s', 'replacement' => 'no numbers'),
	// 			'std' => '0'
	// 			),
	// 		array(
	// 			'id' => 'custom_validate',
	// 			'type' => 'text',
	// 			'title' => __('Text Option - Custom Callback Validated', 'cell-store'),
	// 			'sub_desc' => __('You decide.', 'cell-store'),
				
	// 			'validate_callback' => 'validate_callback_function',
	// 			'std' => '0'
	// 			),
	// 		array(
	// 			'id' => '5',
	// 			'type' => 'textarea',
	// 			'title' => __('Textarea Option - No HTML Validated', 'cell-store'), 
	// 			'sub_desc' => __('All HTML will be stripped', 'cell-store'),
				
	// 			'validate' => 'no_html',
	// 			'std' => 'No HTML is allowed in here.'
	// 			),
	// 		array(
	// 			'id' => '6',
	// 			'type' => 'textarea',
	// 			'title' => __('Textarea Option - HTML Validated', 'cell-store'), 
	// 			'sub_desc' => __('HTML Allowed (wp_kses)', 'cell-store'),
				
	// 			'validate' => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
	// 			'std' => 'HTML is allowed in here.'
	// 			),
	// 		array(
	// 			'id' => '7',
	// 			'type' => 'textarea',
	// 			'title' => __('Textarea Option - HTML Validated Custom', 'cell-store'), 
	// 			'sub_desc' => __('Custom HTML Allowed (wp_kses)', 'cell-store'),
				
	// 			'validate' => 'html_custom',
	// 			'std' => 'Some HTML is allowed in here.',
	// 			'allowed_html' => array('') //see http://codex.wordpress.org/Function_Reference/wp_kses
	// 			),
	// 		array(
	// 			'id' => '8',
	// 			'type' => 'textarea',
	// 			'title' => __('Textarea Option - JS Validated', 'cell-store'), 
	// 			'sub_desc' => __('JS will be escaped', 'cell-store'),
				
	// 			'validate' => 'js'
	// 			),
	// 		array(
	// 			'id' => '9',
	// 			'type' => 'editor',
	// 			'title' => __('Editor Option', 'cell-store'), 
	// 			'sub_desc' => __('Can also use the validation methods if required', 'cell-store'),
				
	// 			'std' => 'OOOOOOhhhh, rich editing.'
	// 			)
	// 		,
	// 		array(
	// 			'id' => 'editor2',
	// 			'type' => 'editor',
	// 			'title' => __('Editor Option 2', 'cell-store'), 
	// 			'sub_desc' => __('Can also use the validation methods if required', 'cell-store'),
				
	// 			'std' => 'OOOOOOhhhh, rich editing2.'
	// 			)
	// 		)
	// 	);
// $sections[] = array(
// 				'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_150_check.png',
// 				'title' => __('Radio/Checkbox Fields', 'cell-store'),
// 				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'cell-store'),
// 				'fields' => array(
// 					array(
// 						'id' => '10',
// 						'type' => 'checkbox',
// 						'title' => __('Checkbox Option', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
						
// 						'std' => '1'// 1 = on | 0 = off
// 						),
// 					array(
// 						'id' => '11',
// 						'type' => 'multi_checkbox',
// 						'title' => __('Multi Checkbox Option', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
						
// 						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for multi checkbox options
// 						'std' => array('1' => '1', '2' => '0', '3' => '0')//See how std has changed? you also dont need to specify opts that are 0.
// 						),
// 					array(
// 						'id' => '12',
// 						'type' => 'radio',
// 						'title' => __('Radio Option', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
						
// 						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for radio options
// 						'std' => '2'
// 						),
// 					array(
// 						'id' => '13',
// 						'type' => 'radio_img',
// 						'title' => __('Radio Image Option', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
						
// 						'options' => array(
// 										'1' => array('title' => 'Opt 1', 'img' => 'images/align-none.png'),
// 										'2' => array('title' => 'Opt 2', 'img' => 'images/align-left.png'),
// 										'3' => array('title' => 'Opt 3', 'img' => 'images/align-center.png'),
// 										'4' => array('title' => 'Opt 4', 'img' => 'images/align-right.png')
// 											),//Must provide key => value(array:title|img) pairs for radio options
// 						'std' => '2'
// 						),
// 					array(
// 						'id' => 'radio_img',
// 						'type' => 'radio_img',
// 						'title' => __('Radio Image Option For Layout', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
// 						'desc' => __('This uses some of the built in images, you can use them for layout options.', 'cell-store'),
// 						'options' => array(
// 										'1' => array('title' => '1 Column', 'img' => NHP_OPTIONS_URL.'img/1col.png'),
// 										'2' => array('title' => '2 Column Left', 'img' => NHP_OPTIONS_URL.'img/2cl.png'),
// 										'3' => array('title' => '2 Column Right', 'img' => NHP_OPTIONS_URL.'img/2cr.png')
// 											),//Must provide key => value(array:title|img) pairs for radio options
// 						'std' => '2'
// 						)																		
// 					)
// 				);
// $sections[] = array(
// 				'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_157_show_lines.png',
// 				'title' => __('Select Fields', 'cell-store'),
// 				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'cell-store'),
// 				'fields' => array(
// 					array(
// 						'id' => '14',
// 						'type' => 'select',
// 						'title' => __('Select Option', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
						
// 						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for select options
// 						'std' => '2'
// 						),
// 					array(
// 						'id' => '15',
// 						'type' => 'multi_select',
// 						'title' => __('Multi Select Option', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
						
// 						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for radio options
// 						'std' => array('2','3')
// 						)									
// 					)
// 				);
// $sections[] = array(
// 				'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_023_cogwheels.png',
// 				'title' => __('Custom Fields', 'cell-store'),
// 				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'cell-store'),
// 				'fields' => array(
// 					array(
// 						'id' => '16',
// 						'type' => 'color',
// 						'title' => __('Color Option', 'cell-store'), 
// 						'sub_desc' => __('Only color validation can be done on this field type', 'cell-store'),
						
// 						'std' => '#FFFFFF'
// 						),
// 					array(
// 						'id' => 'color_gradient',
// 						'type' => 'color_gradient',
// 						'title' => __('Color Gradient Option', 'cell-store'), 
// 						'sub_desc' => __('Only color validation can be done on this field type', 'cell-store'),
						
// 						'std' => array('from' => '#000000', 'to' => '#FFFFFF')
// 						),
// 					array(
// 						'id' => '17',
// 						'type' => 'date',
// 						'title' => __('Date Option', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
// 						'desc' => __('This is the description field, again good for additional info.', 'cell-store')
// 						),
// 					array(
// 						'id' => '18',
// 						'type' => 'button_set',
// 						'title' => __('Button Set Option', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
						
// 						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for radio options
// 						'std' => '2'
// 						),
// 					array(
// 						'id' => '19',
// 						'type' => 'upload',
// 						'title' => __('Upload Option', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
// 						'desc' => __('This is the description field, again good for additional info.', 'cell-store')
// 						),
// 					array(
// 						'id' => 'pages_select',
// 						'type' => 'pages_select',
// 						'title' => __('Pages Select Option', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
// 						'desc' => __('This field creates a drop down menu of all the sites pages.', 'cell-store'),
// 						'args' => array()//uses get_pages
// 						),
// 					array(
// 						'id' => 'pages_multi_select',
// 						'type' => 'pages_multi_select',
// 						'title' => __('Pages Multiple Select Option', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
// 						'desc' => __('This field creates a Multi Select menu of all the sites pages.', 'cell-store'),
// 						'args' => array('number' => '5')//uses get_pages
// 						),
// 					array(
// 						'id' => 'posts_select',
// 						'type' => 'posts_select',
// 						'title' => __('Posts Select Option', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
// 						'desc' => __('This field creates a drop down menu of all the sites posts.', 'cell-store'),
// 						'args' => array('numberposts' => '10')//uses get_posts
// 						),
// 					array(
// 						'id' => 'posts_multi_select',
// 						'type' => 'posts_multi_select',
// 						'title' => __('Posts Multiple Select Option', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
// 						'desc' => __('This field creates a Multi Select menu of all the sites posts.', 'cell-store'),
// 						'args' => array('numberposts' => '10')//uses get_posts
// 						),
// 					array(
// 						'id' => 'tags_select',
// 						'type' => 'tags_select',
// 						'title' => __('Tags Select Option', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
// 						'desc' => __('This field creates a drop down menu of all the sites tags.', 'cell-store'),
// 						'args' => array('number' => '10')//uses get_tags
// 						),
// 					array(
// 						'id' => 'tags_multi_select',
// 						'type' => 'tags_multi_select',
// 						'title' => __('Tags Multiple Select Option', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
// 						'desc' => __('This field creates a Multi Select menu of all the sites tags.', 'cell-store'),
// 						'args' => array('number' => '10')//uses get_tags
// 						),
// 					array(
// 						'id' => 'cats_select',
// 						'type' => 'cats_select',
// 						'title' => __('Cats Select Option', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
// 						'desc' => __('This field creates a drop down menu of all the sites cats.', 'cell-store'),
// 						'args' => array('number' => '10')//uses get_categories
// 						),
// 					array(
// 						'id' => 'cats_multi_select',
// 						'type' => 'cats_multi_select',
// 						'title' => __('Cats Multiple Select Option', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
// 						'desc' => __('This field creates a Multi Select menu of all the sites cats.', 'cell-store'),
// 						'args' => array('number' => '10')//uses get_categories
// 						),
// 					array(
// 						'id' => 'menu_select',
// 						'type' => 'menu_select',
// 						'title' => __('Menu Select Option', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
// 						'desc' => __('This field creates a drop down menu of all the sites menus.', 'cell-store'),
// 						//'args' => array()//uses wp_get_nav_menus
// 						),
// 					array(
// 						'id' => 'select_hide_below',
// 						'type' => 'select_hide_below',
// 						'title' => __('Select Hide Below Option', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
// 						'desc' => __('This field requires certain options to be checked before the below field will be shown.', 'cell-store'),
// 						'options' => array(
// 									'1' => array('name' => 'Opt 1 field below allowed', 'allow' => 'true'),
// 									'2' => array('name' => 'Opt 2 field below hidden', 'allow' => 'false'),
// 									'3' => array('name' => 'Opt 3 field below allowed', 'allow' => 'true')
// 									),//Must provide key => value(array) pairs for select options
// 						'std' => '2'
// 						),
// 					array(
// 						'id' => 'menu_location_select',
// 						'type' => 'menu_location_select',
// 						'title' => __('Menu Location Select Option', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
// 						'desc' => __('This field creates a drop down menu of all the themes menu locations.', 'cell-store')
// 						),
// 					array(
// 						'id' => 'checkbox_hide_below',
// 						'type' => 'checkbox_hide_below',
// 						'title' => __('Checkbox to hide below', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
// 						'desc' => __('This field creates a checkbox which will allow the user to use the next setting.', 'cell-store'),
// 						),
// 						array(
// 						'id' => 'post_type_select',
// 						'type' => 'post_type_select',
// 						'title' => __('Post Type Select Option', 'cell-store'), 
// 						'sub_desc' => __('No validation can be done on this field type', 'cell-store'),
// 						'desc' => __('This field creates a drop down menu of all registered post types.', 'cell-store'),
// 						//'args' => array()//uses get_post_types
// 						),
// 					array(
// 						'id' => 'custom_callback',
// 						//'type' => 'nothing',//doesnt need to be called for callback fields
// 						'title' => __('Custom Field Callback', 'cell-store'), 
// 						'sub_desc' => __('This is a completely unique field type', 'cell-store'),
// 						'desc' => __('This is created with a callback function, so anything goes in this field. Make sure to define the function though.', 'cell-store'),
// 						'callback' => 'my_custom_field'
// 						),
// 					array(
// 						'id' => 'google_webfonts',
// 						'type' => 'google_webfonts',//doesnt need to be called for callback fields
// 						'title' => __('Google Webfonts', 'cell-store'), 
// 						'sub_desc' => __('This is a completely unique field type', 'cell-store'),
// 						'desc' => __('This is a simple implementation of the developer API for Google webfonts. Preview selection will be coming in future releases.', 'cell-store')
// 						)							
// 					)
// 				);

// $sections[] = array(
// 				'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_093_crop.png',
// 				'title' => __('Non Value Fields', 'cell-store'),
// 				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'cell-store'),
// 				'fields' => array(
// 					array(
// 						'id' => '20',
// 						'type' => 'text',
// 						'title' => __('Text Field', 'cell-store'), 
// 						'sub_desc' => __('Additional Info', 'cell-store'),
// 						'desc' => __('This is the description field, again good for additional info.', 'cell-store')
// 						),
// 					array(
// 						'id' => '21',
// 						'type' => 'divide'
// 						),
// 					array(
// 						'id' => '22',
// 						'type' => 'text',
// 						'title' => __('Text Field', 'cell-store'), 
// 						'sub_desc' => __('Additional Info', 'cell-store'),
// 						'desc' => __('This is the description field, again good for additional info.', 'cell-store')
// 						),
// 					array(
// 						'id' => '23',
// 						'type' => 'info',
// 						'desc' => __('<p class="description">This is the info field, if you want to break sections up.</p>', 'cell-store')
// 						),
// 					array(
// 						'id' => '24',
// 						'type' => 'text',
// 						'title' => __('Text Field', 'cell-store'), 
// 						'sub_desc' => __('Additional Info', 'cell-store'),
// 						'desc' => __('This is the description field, again good for additional info.', 'cell-store')
// 						)				
// 					)
// 				);
				
				
	// $tabs = array();
			
	// if (function_exists('wp_get_theme')){
	// 	$theme_data = wp_get_theme();
	// 	$theme_uri = $theme_data->get('ThemeURI');
	// 	$description = $theme_data->get('Description');
	// 	$author = $theme_data->get('Author');
	// 	$version = $theme_data->get('Version');
	// 	$tags = $theme_data->get('Tags');
	// }else{
	// 	$theme_data = get_theme_data(trailingslashit(get_stylesheet_directory()).'style.css');
	// 	$theme_uri = $theme_data['URI'];
	// 	$description = $theme_data['Description'];
	// 	$author = $theme_data['Author'];
	// 	$version = $theme_data['Version'];
	// 	$tags = $theme_data['Tags'];
	// }	

	// $theme_info = '<div class="cell-store-section-desc">';
	// $theme_info .= '<p class="cell-store-theme-data description theme-uri">'.__('<strong>Theme URL:</strong> ', 'cell-store').'<a href="'.$theme_uri.'" target="_blank">'.$theme_uri.'</a></p>';
	// $theme_info .= '<p class="cell-store-theme-data description theme-author">'.__('<strong>Author:</strong> ', 'cell-store').$author.'</p>';
	// $theme_info .= '<p class="cell-store-theme-data description theme-version">'.__('<strong>Version:</strong> ', 'cell-store').$version.'</p>';
	// $theme_info .= '<p class="cell-store-theme-data description theme-description">'.$description.'</p>';
	// $theme_info .= '<p class="cell-store-theme-data description theme-tags">'.__('<strong>Tags:</strong> ', 'cell-store').implode(', ', $tags).'</p>';
	// $theme_info .= '</div>';



	// $tabs['theme_info'] = array(
	// 				'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_195_circle_info.png',
	// 				'title' => __('Theme Information', 'cell-store'),
	// 				'content' => $theme_info
	// 				);
	
	// if(file_exists(trailingslashit(get_stylesheet_directory()).'README.html')){
	// 	$tabs['theme_docs'] = array(
	// 					'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_071_book.png',
	// 					'title' => __('Documentation', 'cell-store'),
	// 					'content' => nl2br(file_get_contents(trailingslashit(get_stylesheet_directory()).'README.html'))
	// 					);
	// }

	global $NHP_Options;
	// $NHP_Options = new NHP_Options($sections, $args, $tabs);
	$NHP_Options = new NHP_Options($sections, $args);

}//function
add_action('init', 'setup_framework_options', 0);

/*
 * 
 * Custom function for the callback referenced above
 *
 */
function my_custom_field($field, $value){
	print_r($field);
	print_r($value);

}//function

/*
 * 
 * Custom function for the callback validation referenced above
 *
 */
function validate_callback_function($field, $value, $existing_value){
	
	$error = false;
	$value =  'just testing';
	/*
	do your validation
	
	if(something){
		$value = $value;
	}elseif(somthing else){
		$error = true;
		$value = $existing_value;
		$field['msg'] = 'your custom error message';
	}
	*/
	
	$return['value'] = $value;
	if($error == true){
		$return['error'] = $field;
	}
	return $return;
	
}//function
?>