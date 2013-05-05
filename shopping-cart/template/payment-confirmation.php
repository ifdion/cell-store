<div id="" class="shopping-cart-process clearfix">
	<div id="" class="transaction-items">
		<h3><?php _e('Thank you for your order', 'hijabchic') ?></h3>
		<?php if (isset($_SESSION['shopping-cart']['last-transaction'])): ?>
			<p><?php printf(__('Your order has been confirmed with transaction code : <strong> %s </strong>', 'hijabchic'), $_SESSION['shopping-cart']['last-transaction'] ) ?></p>
		<?php endif ?>
		<p><?php _e('Please complete your payment via bank transfer / e-banking / m-banking to:', 'hijabchic') ?> </p>
		<p><?php _e('<strong>BCA : on behalf of Nisa Pratiwi , account number 4377227777</strong> or', 'hijabchic') ?> </p>
		<p><?php _e('<strong>BANK MANDIRI : on behalf of Nisa Pratiwi , account number 1310088333333</strong> or', 'hijabchic') ?> </p>
		<p><?php _e('<strong>BANK MANDIRI : on behalf of Nisa Pratiwi , account number 1310088333333</strong> or', 'hijabchic') ?> </p>
		<p><?php _e('<strong>WESTERN UNION : on behalf of Iqbal Alghifari.</strong>', 'hijabchic') ?> </p>
		<p><?php _e('Keep your transaction script or record, you will need this to finish the payment confirmation step', 'hijabchic') ?>.</p>
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
		<h3><?php _e('Confirm Payment', 'hijabchic') ?></h3>
		<form id="payment-confirmation" name="payment-confirmation" class="well form-horizontal" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" enctype="multipart/form-data">
			<div class="control-group">
				<label class="control-label" for="name"><?php _e('Name', 'hijabchic') ?></label>
				<div class="controls">
					<input type="text" class="input-xlarge " id="name" name="name" value="<?php echo $full_name ?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="email"><?php _e('Email', 'hijabchic') ?></label>
				<div class="controls">
					<input type="text" class="input-xlarge " id="email" name="email" value="<?php echo $current_user->user_email ?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="transaction-slug"><?php _e('Transaction Code', 'hijabchic') ?></label>
				<div class="controls">
					<input type="text" class="input-xlarge " id="transaction-slug" name="transaction-slug" value="<?php if (isset($_SESSION['shopping-cart']['last-transaction'])) { echo $_SESSION['shopping-cart']['last-transaction'] ;} ?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="date"><?php _e('Date', 'hijabchic') ?></label>
				<div class="controls">
					<input type="text" class="input-xlarge " id="date" name="date" value="<?php echo date('d-m-Y') ?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="method"><?php _e('Payment Method', 'hijabchic') ?></label>
				<div class="controls">
					<select name="method" id="method">
						<option value="BCA - Bank/ATM Transfer"><?php _e('BCA - Bank/ATM Transfer', 'hijabchic') ?></option>
						<option value="Mandiri - Bank/ATM Transfer"><?php _e('Mandiri - Bank/ATM Transfer', 'hijabchic') ?></option>
						<option value="m-banking BCA"><?php _e('m-banking BCA', 'hijabchic') ?></option>
						<option value="m-banking Mandiri"><?php _e('m-banking Mandiri', 'hijabchic') ?></option>
						<option value="klikBCA"><?php _e('klikBCA', 'hijabchic') ?></option>
						<option value="Mandiri E Banking"><?php _e('Mandiri E Banking', 'hijabchic') ?></option>
						<option value="0"><?php _e('Other Method', 'hijabchic') ?></option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="other-method"><?php _e('Other Method', 'hijabchic') ?></label>
				<div class="controls">
					<input type="text" class="input-xlarge " id="other-method" name="other-method" value="">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="account-holder"><?php _e('Account Holder', 'hijabchic') ?></label>
				<div class="controls">
					<input type="text" class="input-xlarge " id="account-holder" name="account-holder" value="">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="mtcn-number"><?php _e('MTCN Number', 'hijabchic') ?></label>
				<div class="controls">
					<input type="text" class="input-xlarge " id="mtcn-number" name="mtcn-number" value="">
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary"><?php _e('Confirm Payment', 'hijabchic') ?></button>
				<?php wp_nonce_field('payment_confirm','payment_confirm_nonce'); ?>
				<input name="action" value="payment_confirm" type="hidden">
			</div>
		</form>
	</div>
</div>