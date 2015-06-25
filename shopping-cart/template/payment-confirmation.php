<?php

	$store_payment = get_option( 'cell_store_payments' );
	$transfer_destination = $store_payment['transfer-destination'];
	$transfer_input = $store_payment['transfer-input'];

	// echo '<pre>';
	// print_r($transfer_destination);
	// print_r($transfer_input);
	// echo '</pre>';

?>
<div id="" class="shopping-cart-process">
	<div id="" class="transaction-items">
		<?php if (isset($_SESSION['shopping-cart']['last-transaction'])): ?>
			<p class="lead"><?php printf(__('Your transaction code : <strong> %s </strong>', 'cell-store'), $_SESSION['shopping-cart']['last-transaction'] ) ?></p>
		<?php endif ?>
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
		
		<form id="payment-confirmation" name="payment-confirmation" class=" well form-horizontal" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" enctype="multipart/form-data">
			<h4><?php _e('Confirm Payment', 'cell-store') ?></h4>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="name"><?php _e('Name', 'cell-store') ?></label>
				<div class="col-sm-6">
					<input type="text" class="form-control " id="name" name="name" value="<?php echo $full_name ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="email"><?php _e('Email', 'cell-store') ?></label>
				<div class="col-sm-6">
					<input type="text" class="form-control " id="email" name="email" value="<?php echo $current_user->user_email ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="transaction-slug"><?php _e('Transaction Code', 'cell-store') ?></label>
				<div class="col-sm-6">
					<input type="text" class="form-control " id="transaction-slug" name="transaction-slug" value="<?php if (isset($_SESSION['shopping-cart']['last-transaction'])) { echo $_SESSION['shopping-cart']['last-transaction'] ;} ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="date"><?php _e('Date', 'cell-store') ?></label>
				<div class="col-sm-6">
					<input type="text" class="form-control " id="date" name="date" value="<?php echo date('d-m-Y') ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="method"><?php _e('Transfer Destination', 'cell-store') ?></label>
				<div class="col-sm-6">
					<select name="method" id="method" class="form-control">
						<?php foreach ($transfer_destination as $key => $value): ?>
							<option value="<?php echo $value['title'] ?>"><?php echo $value['title'] ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="account-holder"><?php _e('Account Holder', 'cell-store') ?></label>
				<div class="col-sm-6">
					<input type="text" class="form-control " id="account-holder" name="account-holder" value="">
				</div>
			</div>

			<?php foreach ($transfer_input as $key => $value): ?>
				<div class="form-group">
					<label class="col-sm-3 control-label" for="<?php echo $value['title'] ?>"><?php echo $value['title'] ; ?></label>
					<div class="col-sm-6">
						<input type="text" class="form-control " id="<?php echo $value['title'] ?>" name="<?php echo $value['title'] ?>" value="">
					</div>
				</div>
			<?php endforeach ?>
			<div class="form-group">
				<div id="" class="col-sm-6 col-sm-offset-3">
					<button type="submit" class="btn btn-primary btn-block"><?php _e('Confirm Payment', 'cell-store') ?></button>
					<?php wp_nonce_field('payment_confirm','payment_confirm_nonce'); ?>
					<input name="action" value="payment_confirm" type="hidden">
				</div>
			</div>
		</form>
	</div>
</div>