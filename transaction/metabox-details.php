<?php
	global $cell_store_option;
	$currency = $cell_store_option['currency']['symbol'];
	$weight_unit = $cell_store_option['product']['weight-unit'];

	$store_option = get_option('cell_store_features' );

	// echo '<pre>';
	// print_r($store_option);
	// echo '</pre>';
?>
<div class="my_meta_control">
	<?php
		$post_meta = get_post_meta($post->ID);
		$shipping = unserialize($post_meta['_shipping'][0]);
		$billing = unserialize($post_meta['_billing'][0]);
		$payment = unserialize($post_meta['_payment'][0]);
		if (isset($post_meta['_coupon'][0])) {
			$coupon = unserialize($post_meta['_coupon'][0]);
		}
		$items = unserialize($post_meta['_items'][0]);
	?>
	<h2><?php _e('Items', 'cell-store') ?></h2>
	<?php
		foreach ($items as $key => $value) {
			echo '<dl class="product-order">';
			$option = ' '.$value['option'];
			echo '<dt><strong>'. $value['name'].$option.'</strong></dt>';
			printf(__('<dd>Quantity : %s</dd>', 'cell-store'),$value['quantity']);
			if ($value['weight']) {
				printf(__('<dd>Weight : %s</dd>', 'cell-store'),number_format($value['weight'],0,',','.'));
			}
			printf(__('<dd>Price : %s</dd>', 'cell-store'),currency_format($value['price']));
			if ($value['stock-manage']) {
				echo __('<dd>Stock Managed</dd>', 'cell-store');
			}
			echo '</dl>';
		}
	?>
	<hr>
	<h2><?php _e('Payment Details', 'cell-store') ?></h2>
	<?php
		echo '<ul>';
			echo '<li><strong>'. __('Total Item Cost', 'cell-store').'</strong> : '.currency_format($payment['total-item-cost']).'</li>';
			if (isset($payment['use-seconary-currency'])) {
				echo '<li><strong>'. __('Currency Exchange', 'cell-store').'</strong> : '. currency_format(1 / $payment['exchange-rate']).'</li>';	
				echo '<li><strong>'. __('Total Item Cost in Paid Currency', 'cell-store').'</strong> : '.$store_option['secondary-currency'] . ' ' . ceil($payment['exchange-rate'] * $payment['total-item-cost'] * 100) / 100 .'</li>';	
			}
			echo '<li><strong>'. __('Payment Method', 'cell-store').'</strong> : '.$payment['method'].'</li>';
			echo '<li><strong>'. __('Total Weight', 'cell-store').'</strong> : ' . number_format($payment['total-weight'],0,',','.').' '.$weight_unit.'</li>';
			echo '<li><strong>'. __('Shipping Option', 'cell-store').'</strong> : '.$payment['shipping-option'].'</li>';
			echo '<li><strong>'. __('Shipping Rate', 'cell-store').'</strong> : '. currency_format($payment['shipping-rate']) .'</li>';
			if (isset($payment['use-seconary-currency'])) {
				echo '<li><strong>'. __('Shipping Rate', 'cell-store').'</strong> : '.$store_option['secondary-currency'] . ' ' . ceil($payment['exchange-rate'] * $payment['shipping-rate'] * 100) / 100 .'</li>';	
			}
		echo '</ul>';
	?>
	<hr>
	<?php if ($shipping == $billing): ?>
		<h2><?php _e('Shipping & Billing Details', 'cell-store') ?></h2>
		<ul>
			<?php foreach ($shipping as $key => $value): ?>
				<?php
					$shipping_destination = array('country','province','city','district');
					if (in_array($key, $shipping_destination) && is_numeric($value)){
						$value = get_the_title($value);
					}
				?>
				<ul><strong><?php echo ucfirst(str_replace('-', ' ', $key)) ?></strong> : <?php echo $value ?></ul>
			<?php endforeach ?>
		</ul>
		<hr>
	<?php else: ?>
		<div id="" class="half">
			<h2><?php _e('Shipping', 'cell-store') ?></h2>
			<ul>
				<?php foreach ($shipping as $key => $value): ?>
					<?php
						$shipping_destination = array('country','province','city','district');
						if (in_array($key, $shipping_destination) && is_numeric($value)){
							$value = get_the_title($value);
						}
					?>
					<li><strong><?php echo ucfirst(str_replace('-', ' ', $key)) ?></strong> : <?php echo $value ?></li>
				<?php endforeach ?>
			</ul>
		</div>
		<div id="" class="half">
			<h2><?php _e('Billing Details', 'cell-store') ?></h2>
			<ul>
				<?php foreach ($billing as $key => $value): ?>
					<?php
						$shipping_destination = array('country','province','city','district');
						if (in_array($key, $shipping_destination) && is_numeric($value)){
							$value = get_the_title($value);
						}
					?>
					<li><strong><?php echo ucfirst(str_replace('-', ' ', $key)) ?></strong> : <?php echo $value ?></li>
				<?php endforeach ?>
			</ul>
		</div>
		<hr>
	<?php endif ?>
	<?php if (isset($coupon)): ?>
		<h2><?php _e('Coupon Details', 'cell-store') ?></h2>
		<?php
			echo '<ul>';
				echo '<li><strong>'. __('Coupon Name', 'cell-store').'</strong> : '.$coupon['name'].'</li>';
				echo '<li><strong>'. __('Coupon Usage', 'cell-store').'</strong> : '.$coupon['usage'].' / '.$coupon['limit'].'</li>';
				if ($coupon['discount']) {
					echo '<li><strong>'. __('Discount Value', 'cell-store').'</strong> : '.$coupon['discount-value'].'</li>';
				}
				if ($coupon['free-shipping']) {
					echo '<li><strong>'. __('Free Shipping Area', 'cell-store').'</strong> : '.$coupon['free-shipping-area-name'].'</li>';
				}
			echo '</ul>';
		?>
	<?php endif ?>
</div>