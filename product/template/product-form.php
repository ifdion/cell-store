<?php
	global $post;
	$product_meta = get_post_meta($post->ID);
	if (isset($product_meta['_use_variations'][0]) && $product_meta['_use_variations'][0] == 1) {
		$use_variations = true;
		$variations = unserialize($product_meta['_variant'][0]);
	} else {
		$use_variations = false;
	}
	if ($product_meta['_use_stock_management'][0] == 1) {
		$use_stock_management = true;


		if (isset($product_meta['_use_variations'][0])) {
			$variations = unserialize($product_meta['_variant'][0]);
			$variant_stock = false;
			foreach ($variations as $variant) {
				if ($variant['stock']) {
					$variant_stock = true;
				}
			}
			if (!$variant_stock) {
				$in_stock = false;
			} else {
				$in_stock = true;
			}
		} else {
			if (!$product_meta['_stock'][0]) {
				$in_stock = false;
			} else {
				$in_stock = true;
			}
		}
	} else {
		$use_stock_management = false;
	}
	if (isset($product_meta['_detail'][0])) {
		$use_details = true;
		$details = unserialize($product_meta['_detail'][0]);
	} else {
		$use_details = false;
	}
	$price = $product_meta['_price'][0];
	$final_price = $price;
	if (isset($product_meta['_use_discount'][0]) && isset($product_meta['_discount_value'][0])) {
		$use_discount = true;
		$discount_value = $product_meta['_discount_value'][0];
		$todays_date = new DateTime(date("Y-m-d"));
		$discount_start = new DateTime($product_meta['_discount_start'][0]);
		$discount_end = new DateTime($product_meta['_discount_end'][0]);
		if ($todays_date >= $discount_start && $todays_date <= $discount_end) {
			$valid_discount = true;
		}
		if(isset($use_discount) && isset($valid_discount)){
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
<?php if ($price && $in_stock): ?>
	<form id="add-to-cart-form" name="add-to-cart-form" class="well form-horizontal product-form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" enctype="multipart/form-data">
		<?php if ($use_variations): ?>
			<div class="control-group">
				<label class="control-label" for="product-option"><?php _e('Option', 'cell-store') ?></label>
				<div class="controls">
					<select id="product-option" name="product-option">
						<?php foreach ($variations as $variant): ?>
							<?php if ($variant['stock']): ?>
								<option value="<?php echo $variant['title'] ?>" data-stock="<?php echo $variant['stock'] ?>" ><?php echo $variant['title'] ?></option>
							<?php endif ?>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		<?php endif; ?>
		<div class="control-group">
			<label class="control-label" for="quantity"><?php _e('Quantity', 'cell-store') ?></label>
			<div class="controls">
				<select id="quantity" name="quantity">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
				</select>
			</div>
		</div>
		<div class="form-actions">
			<!-- <button type="submit" class="btn btn-primary"><?php _e('Add to Cart', 'cell-store') ?> </button> <br/> -->
			<button type="submit" class="btn btn-primary" name="return" value="checkout"><?php _e('Checkout', 'cell-store') ?> </button>
			<?php wp_nonce_field('add_to_cart','add_to_cart_nonce'); ?>
			<input name="action" value="add_to_cart" type="hidden">
			<input name="product_name" value="<?php the_title() ?>" type="hidden">
			<input name="product_id" value="<?php the_ID() ?>" type="hidden">
			<input name="price" value="<?php echo $final_price; ?>" type="hidden">
			<input name="weight" value="<?php echo $product_meta['_weight'][0]; ?>" type="hidden">
			<?php if ($use_stock_management) { ?>
				<input name="stock[main]" value="<?php echo $product_meta['_stock'][0]; ?>" type="hidden">
				<?php if (isset($variations)): ?>
					<?php foreach ($variations as $variant): ?>
						<input name="stock[<?php echo $variant['title'] ?>]" value="<?php echo $variant['stock'] ?>" type="hidden">
					<?php endforeach ?>					
				<?php endif ?>
			<?php } ?>
		</div>
	</form>
<?php else: ?>
	<h3><?php _e('Out of Stock', 'cell-store') ?></h3>
<?php endif ?>