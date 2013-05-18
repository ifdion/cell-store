<?php
	$purchase_detail = true;
	if (isset($_SESSION['shopping-cart']['items']) && count($_SESSION['shopping-cart']['items']) > 0) {
		$purchase_item = true;
	} else {
		$purchase_detail = false;
	}
	if (isset($_SESSION['shopping-cart']['payment']['shipping-destination-id'])) {
		$shipping_destination = true;
	} else {
		$purchase_detail = false;
	}
	if (isset($_SESSION['shopping-cart']['payment']['shipping-destination-id'])) 	{
		$shipping_rate = true;
	} else {
		$purchase_detail = false;
	}
	if (isset($_SESSION['shopping-cart']['payment']['method'])) {
		$payment_method = true;
	} else {
		$purchase_detail = false;
	}

	$return = get_permalink( get_page_by_path( 'shopping-cart' ) );
?>
<?php if ($purchase_detail == true): ?>
	<form id="purchase-confirmation" name="purchase-confirmation" class="well form-horizontal" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" enctype="multipart/form-data">
		<div class="user-confirmation">
			<h3><?php _e('Transaction Confirmation', 'cell-store') ?></h3>
			<?php if (function_exists('cell_payment_detail')) { cell_payment_detail();} ?>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary"><?php _e('Confirm Purchase Order', 'cell-store') ?> <i class="icon icon-chevron-right icon-white"></i></button>
				<?php wp_nonce_field('purchase_confirmation','purchase_confirmation_nonce'); ?>
				<input name="action" value="purchase_confirmation" type="hidden">
			</div>
		</div>
	</form>	
<?php else: ?>
	<h3><?php _e('Purchase Detail is Incomplete', 'cell-store') ?></h3>
	<p><?php _e( 'Plese review your shopping cart and resubmit your purchase detail.', 'cell-store' ) ?> <a href="<?php echo $return ?>"><?php _e( 'Go to shopping cart', 'cell-store' ) ?></a></p>
<?php endif ?>