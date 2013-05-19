<?php
	global $cell_store_option;
	$weight_unit = $cell_store_option['product']['weight-unit'];
	$payment_bank = $cell_store_option['payment']['bank'];
	$payment_agreement = $cell_store_option['payment']['agreement'];
?>
<form id="payment-option" name="payment-option" class="well form-horizontal" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" enctype="multipart/form-data">
	<?php if (isset($_SESSION['shopping-cart']['payment']['shipping-destination-id']) && is_numeric($_SESSION['shopping-cart']['payment']['shipping-destination-id'])): ?>
		<?php
			$shipping_destination_id = $_SESSION['shopping-cart']['payment']['shipping-destination-id'];
			$shipping_option = get_post_meta($shipping_destination_id,'detail',true);
		?>
		<h3><?php _e('Shipping Option', 'cell-store') ?></h3>
		<p><?php _e('You are shipping destination is registered to : ', 'cell-store') ?> <strong><?php echo(get_the_title($shipping_destination_id)) ?></strong>.</p>
		<div class="control-group">
			<div class="controls">
				<?php if (isset($shipping_option)): ?>
					<?php foreach ($shipping_option as $value): ?>
						<?php
							$shipping_value = $value['shipping_service'].'-'.$value['shipping_rate'];
							if ($value['shipping_rate'] == 0) {
								$cost = 'Free';
								$checked = '';
							} else{
								$cost = currency_format($value['shipping_rate']).' per '.$weight_unit;
								$checked = 'checked="checked"';
							}
							$note = '';
							if (isset($value['shipping_days'])) {
								$note = ' - '.$value['shipping_days'];
							}
						?>
						<label><input type="radio" id="" name="shipping-option" value="<?php echo $shipping_value ?>" <?php echo $checked ?>> <?php echo $value['shipping_service'] ?> - <?php echo $cost . $note ?></label><br>
					<?php endforeach ?>		
				<?php endif ?>			
			</div>
		</div>
		<h3><?php _e('Payment Option', 'cell-store') ?></h3>
		<div class="control-group">
			<div class="control-group">
				<div class="controls">
					<?php $i = 0; ?>
					<?php foreach ($payment_bank as $key => $value): ?>
						<?php $i ++; ?>
						<label><input type="radio" id="" name="payment-method" value="<?php echo $value['title'] ?>" <?php checked( 1, $i ) ?>> <img class="payment-icon" src="<?php echo $value['image'] ?>"> <?php echo $value['title'] ?></label><br>	
					<?php endforeach ?>
				</div>
			</div>
		</div>
		<h3><?php _e('Terms & Conditions', 'cell-store') ?></h3>
		<div class="control-group">
			<div class="control-group">
				<div class="controls">
					<label><input type="checkbox" id="" name="term-condition" value="1" ><?php echo $payment_agreement ; ?></label><br>
				</div>
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary"><?php _e('Confirm Shipping & Payment', 'cell-store') ?> <i class="icon icon-chevron-right icon-white"></i></button>
			<?php wp_nonce_field('payment_option','payment_option_nonce'); ?>
			<input name="action" value="payment_option" type="hidden">
		</div>
	<?php else: ?>
		<h3><?php _e('Shipping Option', 'cell-store') ?></h3>
		<p><?php _e('For international purchase, we will process order by custom to ensure your order get the best payment and shipping method.<br> We will contact you via email for further detail and instruction regarding payment completion.<br> Please also check your spam folder in case our email is  automatically directed to it.', 'cell-store') ?></p>
		<p><?php _e('Thank you', 'cell-store') ?>.</p>
	<?php endif ?>
</form>