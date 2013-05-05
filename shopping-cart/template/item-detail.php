<?php if ($_SESSION['shopping-cart']['items']):?>
	<?php
		$items = $_SESSION['shopping-cart']['items'];
		$total_price = 0;
		$total_weight = 0;
	?>
	<table>
		<?php foreach ($items as $key => $item_details): ?>
			<?php
				$price = $item_details['quantity'] * $item_details['price'];
				$total_price +=$price;
				$weight = $item_details['quantity'] * $item_details['weight'];
				$total_weight += $weight;
				$option = '';
				if ($item_details['option']) {
					$option = $item_details['option'];
				}
			?>
			<tr class="item">
				<td>
					<h4><a href="<?php echo get_permalink($item_details['ID']) ?>"><span class="product-title"><?php echo $item_details['name'] ?></span></a></h4>
					<span><?php _e('Option', 'cell-store') ?> : <?php echo $option ?> <br> <?php _e('Amount', 'cell-store') ?> : <?php echo number_format($item_details['quantity'],0,'','.') ?></span>
				</td>
				<td class="cost"><span><?php echo 'IDR '.number_format($price,0,'','.').',-' ?></span></td>
			</tr>
		<?php endforeach ?>
		<tr class="subtotal">
			<td><?php _e('Sub Total', 'cell-store') ?></td>
			<td class="cost"><span><?php echo 'IDR '.number_format($total_price,0,'','.').',-' ?></span></td>
		</tr>
		<?php if (isset($_SESSION['shopping-cart']['coupon']['discount'])): ?>
			<?php
				$discount = 1;
				$discount_value = str_replace('%', '', $_SESSION['shopping-cart']['coupon']['discount-value']);
				$old_total_price = $total_price;
				$total_price = $old_total_price - ($old_total_price * $discount_value/100);
			?>
			<tr>
				<td ><strong><?php printf(__('Total after discount of %s', 'cell-store'),$_SESSION['shopping-cart']['coupon']['discount-value']) ?></strong></td>
				<td class="cost"><?php echo 'IDR ' .number_format($total_price,0,'','.').',-' ?></td>
			</tr>
		<?php endif ?>
		<?php
			if (isset($_SESSION['shopping-cart']['payment'])) {
				if (isset($_SESSION['shopping-cart']['payment']['shipping-option'])) {
					$shipping_option = $_SESSION['shopping-cart']['payment']['shipping-option'];
				}
				if (isset($_SESSION['shopping-cart']['payment']['shipping-rate'])) {
					$shipping_rate = $_SESSION['shopping-cart']['payment']['shipping-rate'];
				}
				if (isset($_SESSION['shopping-cart']['payment']['total-weight'])) {
					$total_weight = $_SESSION['shopping-cart']['payment']['total-weight'];
				}

				if (isset($shipping_rate) && isset($total_weight)) {
					$shipping_cost = $shipping_rate * $total_weight;
				}

			}

			if (isset($_SESSION['shopping-cart']['coupon']['free-shipping'])) {
				$free_shipping = $_SESSION['shopping-cart']['coupon']['free-shipping'];
			}

			if (isset($_SESSION['shopping-cart']['coupon']['free-shipping-area'])) {
				$free_shipping_area = $_SESSION['shopping-cart']['coupon']['free-shipping-area'];
			}
			
			if (isset($_SESSION['shopping-cart']['payment']['shipping-destination-id'])) {
				$shipping_destination_id = $_SESSION['shopping-cart']['payment']['shipping-destination-id'];
			}
			
			if ((isset($free_shipping) && $shipping_destination_id == $free_shipping_area) || (isset($free_shipping) && is_descendant($shipping_destination_id,$free_shipping_area))) {
				$shipping_cost = 0;
				$shipping_option = __('FREE', 'cell-store');
				$valid_free_shipping = true;
			}

			if (isset($shipping_cost)) {
				$grand_total = $total_price + $shipping_cost;
			}

			
		?>
		<?php if (isset($shipping_option) && isset($shipping_rate) && isset($shipping_destination_id)): ?>
			<tr class="shipping">
				<td><?php echo __('Shipping Cost to <strong>', 'cell-store') . get_the_title($shipping_destination_id) . '</strong> ('.$shipping_option.')'?></td>
				<td class="cost"><span> <?php echo 'IDR  '.number_format($shipping_cost,'0',',','.').',-' ?></span></td>
			</tr>
			<tr class="total">
				<td><?php _e('Total', 'cell-store') ?></td>
				<td class="cost"><span> <?php echo 'IDR  '.number_format($grand_total,'0',',','.').',-' ?></span></td>
			</tr>
		<?php endif ?>
	</table>
<?php endif ?>