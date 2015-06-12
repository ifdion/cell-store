<?php
	global $cell_store_option;
	$currency = $cell_store_option['currency']['symbol'];
?>
<div class="my_meta_control">

	<div id="product-basic" class="meta-parts">
		<label><?php _e('Price', 'cell-store') ?></label>
		<p>
			<input type="text" name="<?php $metabox->the_name('price'); ?>" value="<?php $metabox->the_value('price'); ?>"/>
			<span><?php printf(__( 'Enter in a price, in %s (e.g. 100000)','cell-store' ), $currency) ?></span>
		</p>
		<label><?php _e('Stock', 'cell-store') ?></label>
		<p>
			<select id="use-stock-management" class="use-optional" data-target=".stock-management" name="<?php $metabox->the_name('use_stock_management'); ?>">
				<option value="0" <?php if (!$metabox->get_the_value('use_stock_management')) echo ' selected="selected"'; ?>><?php _e('Unmanaged', 'cell-store') ?></option>
				<option value="1" <?php if ($metabox->get_the_value('use_stock_management')) echo ' selected="selected"'; ?>><?php _e('Managed', 'cell-store') ?></option>
			</select>
			<input class="stock-management" type="text" name="<?php $metabox->the_name('stock'); ?>" value="<?php $metabox->the_value('stock'); ?>"/>
			<span class="stock-management" ><?php _e('Enter in amount of stock', 'cell-store') ?></span>
		</p>
	</div>
	<div id="product-details"class="meta-parts">
		<label><?php _e('Weight', 'cell-store') ?></label>
		<p>
			<input type="text" name="<?php $metabox->the_name('weight'); ?>" value="<?php $metabox->the_value('weight'); ?>"/>
			<span><?php _e('Enter the shipping weight', 'cell-store') ?></span>
		</p>
		<a style="float:right; margin:0 10px;" href="#" class="dodelete-detail button"><?php _e('Remove All', 'cell-store') ?></a>

		<?php while($mb->have_fields_and_multi('detail')): ?>
			<?php $mb->the_group_open(); ?>
				<div id="" class="">
					<label><?php _e('Details & Description', 'cell-store') ?></label>
					<p>
						<input type="text" name="<?php $mb->the_name('title'); ?>" value="<?php $mb->the_value('title'); ?>"/>
						<input type="text" name="<?php $mb->the_name('description'); ?>" value="<?php $mb->the_value('description'); ?>"/>
						<a href="#" class="dodelete button">✕</a>
						
					</p>
				</div>
			<?php $mb->the_group_close(); ?>
		<?php endwhile; ?>

		<p style="margin-bottom:15px; padding-top:5px;"><a href="#" class="docopy-detail button"><?php _e('Add Detail', 'cell-store') ?></a></p>

	</div>
	<div id="product-variation" class="meta-parts">
		<h4><input type="checkbox" data-target="#variation-input" class="use-optional" name="<?php $metabox->the_name('use_variations'); ?>" value="1"<?php if ($metabox->get_the_value('use_variations')) echo ' checked="checked"'; ?>/> <?php _e('Use Product Options', 'cell-store') ?></h4>
		<div id="variation-input" class="optional-meta">
			<a style="float:right; margin:0 10px;" href="#" class="dodelete-variant button"><?php _e('Remove All', 'cell-store') ?></a>
			<?php while($mb->have_fields_and_multi('variant')): ?>
				<?php $mb->the_group_open(); ?>
						<label><?php _e('Option Name <span class="stock-management" > & Stock </span>', 'cell-store') ?></label>
						<p>
							<input type="text" name="<?php $mb->the_name('title'); ?>" value="<?php $mb->the_value('title'); ?>"/>
							<input type="text" class="stock-management" name="<?php $mb->the_name('stock'); ?>" value="<?php $mb->the_value('stock'); ?>"/>
							<a href="#" class="dodelete button">✕</a>
						</p>
				<?php $mb->the_group_close(); ?>
			<?php endwhile; ?>
			<p style="margin-bottom:15px; padding-top:5px;"><a href="#" class="docopy-variant button"><?php _e('Add Variant', 'cell-store') ?></a></p>
		</div>
	</div>
	<div id="product-discount" class="meta-parts">
		<h4><input type="checkbox" data-target="#discount-input" class="use-optional" name="<?php $metabox->the_name('use_discount'); ?>" value="1"<?php if ($metabox->get_the_value('use_discount')) echo ' checked="checked"'; ?>/> <?php _e('Use Product Discount', 'cell-store') ?></h4>
		<div id="discount-input" class="optional-meta">
			<p>
				<input type="text" name="<?php $metabox->the_name('discount_value'); ?>" value="<?php $metabox->the_value('discount_value'); ?>"/>
				<span><?php printf(__( 'Enter the discounted price in %s (e.g. 90000) or the discount percentage (e.g. 10&#37;)','cell-store' ), $currency) ?></span>

			</p>
			<label><?php _e('Discount Start / End Date', 'cell-store') ?></label>
			<p>
				<input class="date-input" type="text" name="<?php $mb->the_name('discount_start'); ?>" value="<?php $mb->the_value('discount_start'); ?>"/>
				<input class="date-input" type="text" name="<?php $mb->the_name('discount_end'); ?>" value="<?php $mb->the_value('discount_end'); ?>"/>
			</p>
		</div>
	</div>

</div>