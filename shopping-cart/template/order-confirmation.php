<form id="purchase-confirmation" name="purchase-confirmation" class="well form-horizontal" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" enctype="multipart/form-data">
	<div class="form-actions">
		<button type="submit" class="btn btn-primary"><?php _e('Confirm Purchase Order', 'hijabchic') ?> <i class="icon icon-chevron-right icon-white"></i></button>
		<?php wp_nonce_field('purchase_confirmation','purchase_confirmation_nonce'); ?>
		<input name="action" value="purchase_confirmation" type="hidden">
	</div>
</form>