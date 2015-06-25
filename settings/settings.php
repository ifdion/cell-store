<?php


require_once 'store-features.php';

require_once 'store-pages.php';

require_once 'social-options.php';

require_once 'input-examples.php';

require_once 'store-payments.php';

/**
 * This function introduces the theme options into the 'Appearance' menu and into a top-level 
 * 'Sandbox Theme' menu.
 */
function cell_store_example_theme_menu() {
	
	add_menu_page(
		'Store Settings',					// The value used to populate the browser's title bar when the menu page is active
		'Store Settings',					// The text of the menu in the administrator's sidebar
		'administrator',					// What roles are able to access the menu
		'cell_store_menu',				// The ID used to bind submenu items to this menu 
		'cell_store_options_display'				// The callback function used to render this menu
	);
	
	// add_submenu_page(
	// 	'cell_store_menu',				// The ID of the top-level menu page to which this submenu item belongs
	// 	__( 'Store Settings', 'cell_store' ),			// The value used to populate the browser's title bar when the menu page is active
	// 	__( 'Store Settings', 'cell_store' ),					// The label of this submenu item displayed in the menu
	// 	'administrator',					// What roles are able to access this submenu item
	// 	'cell_store_features',	// The ID used to represent this submenu item
	// 	'cell_store_options_display'				// The callback function used to render the options for this submenu item
	// );
	
	// add_submenu_page(
	// 	'cell_store_menu',
	// 	__( 'Store Pages', 'cell_store' ),
	// 	__( 'Store Pages', 'cell_store' ),
	// 	'administrator',
	// 	'cell_store_pages',
	// 	create_function( null, 'cell_store_options_display( "social_options" );' )
	// );

	// add_submenu_page(
	// 	'cell_store_menu',
	// 	__( 'Social Options', 'cell_store' ),
	// 	__( 'Social Options', 'cell_store' ),
	// 	'administrator',
	// 	'cell_store_social_options',
	// 	create_function( null, 'cell_store_options_display( "social_options" );' )
	// );
	
	// add_submenu_page(
	// 	'cell_store_menu',
	// 	__( 'Input Examples', 'cell_store' ),
	// 	__( 'Input Examples', 'cell_store' ),
	// 	'administrator',
	// 	'cell_store_input_examples',
	// 	create_function( null, 'cell_store_options_display( "input_examples" );' )
	// );
} // end cell_store_example_theme_menu
add_action( 'admin_menu', 'cell_store_example_theme_menu' );

/**
 * Renders a simple page to display for the theme menu defined above.
 */
function cell_store_options_display( $active_tab = '' ) {
?>
	<!-- Create a header in the default WordPress 'wrap' container -->
	<div class="wrap">
	
		<div id="icon-themes" class="icon32"></div>
		<h2><?php _e( 'Sandbox Theme Options', 'cell_store' ); ?></h2>
		<?php settings_errors(); ?>
		
		<?php if( isset( $_GET[ 'tab' ] ) ) {
			$active_tab = $_GET[ 'tab' ];
		} else if( $active_tab == 'store_pages' ) {
			$active_tab = 'store_pages';
		} else if( $active_tab == 'store_payments' ) {
			$active_tab = 'store_payments';
		} else if( $active_tab == 'input_examples' ) {
			$active_tab = 'input_examples';
		} else {
			$active_tab = 'store_features';
		} // end if/else ?>
		
		<h2 class="nav-tab-wrapper">
			<a href="?page=cell_store_menu" class="nav-tab <?php echo $active_tab == 'store_features' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Store Settings', 'cell_store' ); ?></a>
			<a href="?page=cell_store_menu&tab=store_pages" class="nav-tab <?php echo $active_tab == 'store_pages' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Store Pages', 'cell_store' ); ?></a>
			<a href="?page=cell_store_menu&tab=store_payments" class="nav-tab <?php echo $active_tab == 'store_payments' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Store Payments', 'cell_store' ); ?></a>
			<a href="?page=cell_store_menu&tab=input_examples" class="nav-tab <?php echo $active_tab == 'input_examples' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Input Examples', 'cell_store' ); ?></a>
			<a href="?page=cell_store_menu&tab=social_options" class="nav-tab <?php echo $active_tab == 'social_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Social Options', 'cell_store' ); ?></a>
		</h2>
		
		<form method="post" action="options.php">
			<?php
			
				if( $active_tab == 'store_features' ) {
				
					settings_fields( 'cell_store_features' );
					do_settings_sections( 'cell_store_features' );
				
				} elseif( $active_tab == 'store_pages' ) {
				
					settings_fields( 'cell_store_pages' );
					do_settings_sections( 'cell_store_pages' );

				} elseif( $active_tab == 'store_payments' ) {
				
					settings_fields( 'cell_store_payments' );
					do_settings_sections( 'cell_store_payments' );

				} elseif( $active_tab == 'input_examples' ) {
				
					settings_fields( 'cell_store_input_examples' );
					do_settings_sections( 'cell_store_input_examples' );

				} elseif( $active_tab == 'social_options' ) {
				
					settings_fields( 'cell_store_social_options' );
					do_settings_sections( 'cell_store_social_options' );
										
				} // end if/else
				
				submit_button();
			
			?>
		</form>
		
	</div><!-- /.wrap -->
<?php
} // end cell_store_options_display










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
			'cell_store' => false,
			'cell_store-email' => '',
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
				'image' => plugins_url('cell-store/img/western_union.png'),
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
				'mtcn-number' => __( 'MTCN Number','cell-store' ),
			)
		),
	),
);