<?php
	global $cell_store_option;
	$payment_confirmation_message = $cell_store_option['payment']['confirmation']['message'];
	$payment_method_option = $cell_store_option['payment']['confirmation']['method-option'];
	$payment_method_additional_field = $cell_store_option['payment']['confirmation']['additional-field'];
?>
<div id="" class="shopping-cart-process clearfix">
	<div id="" class="transaction-items">
		<h3><?php _e('Thank you for your order', 'cell-store') ?></h3>
		<?php if (isset($_SESSION['shopping-cart']['last-transaction'])): ?>
			<p><?php printf(__('Your order has been confirmed with transaction code : <strong> %s </strong>', 'cell-store'), $_SESSION['shopping-cart']['last-transaction'] ) ?></p>
		<?php endif ?>
		<?php echo $payment_confirmation_message ?>
	</div>
	<div class="user-credential">
		<?php
			global $current_user;
			$user_data = get_user_meta($current_user->ID);
			if ($user_data['first_name'][0]) {
				$full_name = $user_data['first_name'][0] ;
				if (isset($user_data['last_name'][0])) {
					$full_name .= ' '.$user_data['last_name'][0];
				}
			} else {
				$full_name = $current_user->display_name;
			}
		?>
		<h3><?php _e('Confirm Payment', 'cell-store') ?></h3>
		<form id="payment-confirmation" name="payment-confirmation" class="well form-horizontal" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" enctype="multipart/form-data">
			<div class="control-group">
				<label class="control-label" for="name"><?php _e('Name', 'cell-store') ?></label>
				<div class="controls">
					<input type="text" class="input-xlarge " id="name" name="name" value="<?php echo $full_name ?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="email"><?php _e('Email', 'cell-store') ?></label>
				<div class="controls">
					<input type="text" class="input-xlarge " id="email" name="email" value="<?php echo $current_user->user_email ?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="transaction-slug"><?php _e('Transaction Code', 'cell-store') ?></label>
				<div class="controls">
					<input type="text" class="input-xlarge " id="transaction-slug" name="transaction-slug" value="<?php if (isset($_SESSION['shopping-cart']['last-transaction'])) { echo $_SESSION['shopping-cart']['last-transaction'] ;} ?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="date"><?php _e('Date', 'cell-store') ?></label>
				<div class="controls">
					<input type="text" class="input-xlarge " id="date" name="date" value="<?php echo date('d-m-Y') ?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="method"><?php _e('Payment Method', 'cell-store') ?></label>
				<div class="controls">
					<select name="method" id="method">
						<?php foreach ($payment_method_option as $key => $value): ?>
							<option value="<?php echo $value ?>"><?php echo $value ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="other-method"><?php _e('Other Method', 'cell-store') ?></label>
				<div class="controls">
					<input type="text" class="input-xlarge " id="other-method" name="other-method" value="">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="account-holder"><?php _e('Account Holder', 'cell-store') ?></label>
				<div class="controls">
					<input type="text" class="input-xlarge " id="account-holder" name="account-holder" value="">
				</div>
			</div>
			<?php foreach ($payment_method_additional_field as $key => $value): ?>
				<div class="control-group">
					<label class="control-label" for="<?php echo $key ?>"><?php echo $value ; ?></label>
					<div class="controls">
						<input type="text" class="input-xlarge " id="<?php echo $key ?>" name="<?php echo $key ?>" value="">
					</div>
				</div>
			<?php endforeach ?>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary"><?php _e('Confirm Payment', 'cell-store') ?></button>
				<?php wp_nonce_field('payment_confirm','payment_confirm_nonce'); ?>
				<input name="action" value="payment_confirm" type="hidden">
			</div>
		</form>
	</div>
</div>