<?php
	global $post;
	global $cell_store_option;
	$currency = $cell_store_option['currency']['symbol'];

	$product_meta = get_post_meta($post->ID);
	if ($product_meta['_use_variations'][0] == 1) {
		$use_variations = true;
		$variations = unserialize($product_meta['_variant'][0]);
	} else {
		$use_variations = false;
	}
	if ($product_meta['_use_stock_management'][0] == 1) {
		$use_stock_management = true;
	} else {
		$use_stock_management = false;
	}
	if ($product_meta['_detail'][0]) {
		$use_details = true;
		$details = unserialize($product_meta['_detail'][0]);
	} else {
		$use_details = false;
	}
	$price = $product_meta['_price'][0];
	$final_price = $price;
	if ($product_meta['_use_discount'][0] && $product_meta['_discount_value'][0]) {
		$use_discount = true;
		$discount_value = $product_meta['_discount_value'][0];
		$todays_date = new DateTime(date("Y-m-d"));
		$discount_start = new DateTime($product_meta['_discount_start'][0]);
		$discount_end = new DateTime($product_meta['_discount_end'][0]);
		if ($todays_date >= $discount_start && $todays_date <= $discount_end) {
			$valid_discount = true;
		}
		if($use_discount && $valid_discount){
			if (stripos($discount_value, '%')) {
				$discount_percentage = str_replace('%', '', $discount_value);
				$price_after_discount = $price * (100 - $discount_percentage) / 100;
				$discount_percentage = true;
			} else {
				$price_after_discount = $product_meta['_discount_value'][0];
			}
			$final_price = $price_after_discount;
		}
	}
?>
<?php if ($price): ?>
	<dl>
		<dt><?php _e('Price', 'cell-store') ?></dt>
		<?php if ($price_after_discount): ?>
			<dd>
				<?php echo $currency.number_format($price_after_discount,0,'','.').',-' ?>
				<?php echo ' | was '.$currency.number_format($price,0,'','.').',-' ?>
				<?php if($discount_percentage) echo $discount_value.' discount'; ?>
			</dd>
		<?php else: ?>
			<dd><?php echo $currency.number_format($price,0,'','.').',-' ?></dd>	
		<?php endif ?>
		<?php if ($use_variations): ?>
			<dt><?php _e('Options', 'cell-store') ?></dt>
			<dd>
				<ul>
					<?php foreach ($variations as $variant): ?>
						<li>
							<strong><?php echo $variant['title'] ?></strong>
							<?php
								if ($use_stock_management) { echo ' : '.$variant['stock'];}
							?>
						</li>
					<?php endforeach ?>
				</ul>
			</dd>
		<?php else: ?>
			<?php if ($use_stock_management) { ?>
				<dt><?php _e('Stock', 'cell-store') ?></dt>
				<dd><?php echo number_format($product_meta['_stock'][0],0,'','.') ?></dd>
			<?php } ?>
		<?php endif ?>

		<dt><?php _e('Weight', 'cell-store') ?></dt>
		<dd><?php echo number_format($product_meta['_weight'][0],0,'','.').' kg' ?></dd>

		<?php if ($use_details): ?>
			<dt><?php _e('Details', 'cell-store') ?></dt>
			<dd>
				<u;>
					<?php foreach ($details as $detail): ?>
						<li><strong><?php echo $detail['title'] ?></strong> : <?php echo $detail['description'] ?></li>
					<?php endforeach ?>
				</u;>
			</dd>
		<?php endif ?>
	</dl>

	<form id="add-to-cart-form" name="add-to-cart-form" class="well form-horizontal" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" enctype="multipart/form-data">
		<?php if ($use_variations): ?>
		<div class="control-group">
			<label class="control-label" for="product-option"><?php _e('Option', 'cell-store') ?></label>
			<div class="controls">
				<select name="product-option">
					<?php foreach ($variations as $variant): ?>
						<option value="<?php echo $variant['title'] ?>"><?php echo $variant['title'] ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
	<?php endif; ?>
		<div class="control-group">
			<label class="control-label" for="quantity"><?php _e('Quantity', 'cell-store') ?></label>
			<div class="controls">
				<input type="text" class="input-xlarge " id="quantity" name="quantity" value="1">
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary"><?php _e('Add to Cart', 'cell-store') ?> <i class="icon icon-chevron-right icon-white"></i></button>
			<?php wp_nonce_field('add_to_cart','add_to_cart_nonce'); ?>
			<input name="action" value="add_to_cart" type="hidden">
			<input name="product_name" value="<?php the_title() ?>" type="hidden">
			<input name="product_id" value="<?php the_ID() ?>" type="hidden">
			<input name="price" value="<?php echo $final_price; ?>" type="hidden">
			<input name="weight" value="<?php echo $product_meta['_weight'][0]; ?>" type="hidden">
			<?php if ($use_stock_management) { ?>
				<input name="stock[main]" value="<?php echo $product_meta['_stock'][0]; ?>" type="hidden">
				<?php foreach ($variations as $variant): ?>
					<input name="stock[<?php echo $variant['title'] ?>]" value="<?php echo $variant['stock'] ?>" type="hidden">
				<?php endforeach ?>
			<?php } ?>

		</div>
	</form>
<?php else: ?>
	<h3><?php _e('Out of Stock', 'cell-store') ?></h3>
<?php endif ?>
