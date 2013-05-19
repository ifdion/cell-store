<?php
	global $cell_store_option;
	$weight_unit = $cell_store_option['product']['weight-unit'];
?>
<?php if (isset($_SESSION['shopping-cart']['items'])): ?>
	<?php
		$items = $_SESSION['shopping-cart']['items'];
		$total_price = 0;
		$total_weight = 0;
	?>
	<h2><?php _e('Items', 'cell-store') ?></h2>
	<form id="update-shopping-cart" name="update-shopping-cart" class="well form-horizontal" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" enctype="multipart/form-data">
		<table>
			<thead>
				<tr>
					<th></th>
					<th><?php _e('Item', 'cell-store') ?></th>
					<th><?php _e('Unit Price', 'cell-store') ?></th>
					<th><?php _e('Qty', 'cell-store') ?></th>
					<th><?php _e('Qty', 'cell-store') ?></th>
					<th><?php _e('Weight', 'cell-store') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($items as $key => $item_details): ?>
					<?php
						$price = $item_details['quantity'] * $item_details['price'];
						$total_price +=$price;
						$weight = $item_details['quantity'] * $item_details['weight'];
						$total_weight += $weight;
						$option = '';
						if ($item_details['option']) {
							$option = ' ( '.$item_details['option'].' ) ';
						}
					?>
					<tr>
						<td><a href="<?php echo wp_nonce_url(admin_url('admin-ajax.php') . '?action=delete_cart_item&cart-item='.$key, 'delete_cart_item') ?>" title="Remove">✕</a></td>
						<td><a href="<?php echo get_permalink($item_details['ID']) ?>"><?php echo $item_details['name'].$option ?></a></td>
						<td><?php echo currency_format($item_details['price']) ?></td>
						<td><?php echo number_format($item_details['quantity'],0,'','.') ?></td>
						<td><?php echo currency_format($price) ?></td>
						<td><?php echo number_format($weight,1,'','.').' '.$weight_unit ?></td>
					</tr>
				<?php endforeach ?>

			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td><strong><?php _e('Total', 'cell-store') ?></strong></td>
					<td></td>
					<td></td>
					<td><?php echo currency_format($total_price) ?></td>
					<td><?php echo number_format(ceil($total_weight),1,',','.').' '.$weight_unit ?></td>
				</tr>
			</tfoot>
		</table>
		<h2>Coupon</h2>
		<div class="control-group">
			<label class="control-label" for="add-coupon"><?php _e('Add Coupon', 'cell-store') ?></label>
			<div class="controls">
				<input type="text" class="input-xlarge " id="add-coupon" name="add-coupon" >
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary"><?php _e('Update Shopping Cart', 'cell-store') ?> <i class="icon icon-chevron-right icon-white"></i></button>
			<?php wp_nonce_field('update_shopping_cart','update_shopping_cart_nonce'); ?>
			<input name="action" value="update_shopping_cart" type="hidden">
		</div>
	</form>	
<?php else: ?>
	<h2><?php _e('Cart is empty', 'cell-store') ?></h2>
<?php endif ?>