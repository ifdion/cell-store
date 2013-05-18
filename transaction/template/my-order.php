<?php
	global $cell_store_option;
	$currency = $cell_store_option['currency']['symbol'];
?>
		<div id="" class="shopping-cart-process clearfix">
			<div id="" class="transaction-items">
				<h3><?php _e('Insert Your Transaction Code', 'cell-store') ?></h3>
					<form id="checkout" name="checkout" class="well form-horizontal" action="" method="get" enctype="multipart/form-data">
						<div class="control-group">
							<div class="controls">
								<input type="text" class="input-xlarge " id="transaction-code" name="transaction-code" >
							</div>
						</div>	
						<div class="form-actions">
							<button type="submit" class="btn btn-primary"><?php _e('Send', 'cell-store') ?></button>
						</div>
					</form>
			</div>
			<?php if (is_user_logged_in()): ?>
				<div id="" class="user-credential">
					<h3><?php _e('My Transaction', 'cell-store') ?></h3>
					<?php
						global $current_user;
						$args = array(
							'post_type' => 'transaction',
							'nopaging' => true,
							'meta_query' => array(
								array(
									'key' => '_customer_id',
									'value' => $current_user->ID,
								)
							)
						);
						$user_transaction = new WP_Query( $args);
					?>
					<ul>
						<?php if ( $user_transaction->have_posts() ) : while ( $user_transaction->have_posts() ) : $user_transaction->the_post(); ?>
							<?php
								$status = get_post_meta($user_transaction->post->ID, '_transaction_status', true);
								if (!$status) {
									$status = 'pending';
								}
							?>
							<li><a href="?transaction-code=<?php echo $post->post_name ?>"><?php the_title() ?> : <?php echo ucfirst($status) ?></a></li>
						<?php endwhile; ?>
						<?php else: ?>
						<li><?php _e('No Transaction', 'cell-store') ?></li>
						<?php endif; ?>
					</ul>
				</div>				
			<?php endif ?>
		</div>
		<?php
			if (isset($_GET['transaction-code'])) {
				$transaction_code = $_GET['transaction-code'];
				$transaction_id = get_id_by_slug($transaction_code,'transaction');
			}
		?>
		<?php if (isset($transaction_id)): ?>
			<?php
				$post_data = get_post($transaction_id);
				$post_meta = get_post_meta($transaction_id);

				$items = unserialize($post_meta['_items'][0]);

				$payment = unserialize($post_meta['_payment'][0]);
				$shipping_option = $payment['shipping-option'];
				$shipping_cost = $payment['total-weight'] * $payment['shipping-rate'];
				$item_cost = $payment['total-item-cost'];
				$grand_total = $item_cost + $shipping_cost;

				$shipping = unserialize($post_meta['_shipping'][0]);
				$billing = unserialize($post_meta['_billing'][0]);

				$status = $post_meta['_transaction_status'][0];
				if (!$status) {
					$status = 'pending';
				}
			?>
			<div id="" class="shopping-cart-process clearfix">
				<div id="" class="transaction-items">
					<h3><?php _e('Your Order', 'cell-store') ?></h3>
					<table>
						<?php
							$total_price = 0;
							$total_weight = 0;
						?>
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
									<span><?php _e('Option', 'cell-store') ?> : <?php echo $option ?> <br> Amount : <?php echo number_format($item_details['quantity'],0,'','.') ?></span>
								</td>
								<td class="cost"><span><?php echo $currency.' '.number_format($price,0,'','.').',-' ?></span></td>
							</tr>
						<?php endforeach ?>
						<tr class="subtotal">
							<td><?php _e('Sub Total', 'cell-store') ?></td>
							<td class="cost"><span><?php echo $currency.' '.number_format($total_price,0,'','.').',-' ?></span></td>
						</tr>
						<tr class="shipping">
							<td><?php _e('Shipping Cost', 'cell-store') ?> : <?php echo $shipping_option ?></td>
							<td class="cost"><span> <?php echo $currency.' '.number_format($shipping_cost,'0',',','.').',-' ?></span></td>
						</tr>
						<tr class="total">
							<td><?php _e('Total', 'cell-store') ?></td>
							<td class="cost"><span> <?php echo $currency.' '.number_format($grand_total,'0',',','.').',-' ?></span></td>
						</tr>
					</table>
				</div>
				<div class="user-address">
					<h3><?php _e('Shipping Address', 'cell-store') ?></h3>
					<table>
						<?php
							$customer_shipping = $shipping;
							$customer_billing = $billing;
						?>
						<?php foreach ($customer_shipping as $key => $value): ?>
							<?php if ($key != 'destination-id'): ?>
								<tr>
									<td><?php echo ucfirst($key) ?></td>
									<td><?php echo $value ?></td>
								</tr>
							<?php endif ?>
						<?php endforeach ?>
					</table>
				</div>
				<div class="user-confirmation">
					<h3><?php _e('Transaction Status', 'cell-store') ?> : <?php echo ucfirst($status) ?></h3>
					<?php if (isset($post_meta['_tracking_code'][0])): ?>
						<p> <?php _e('Shipping Tracking code', 'cell-store') ?> : <?php echo $post_meta['_tracking_code'][0] ?></p>
					<?php endif ?>
				</div>
			</div>
		<?php endif ?>