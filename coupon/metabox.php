<div class="my_meta_control">
	<label><?php _e('Coupon Code', 'cell-store') ?></label>
	<p>
		<input type="text" name="<?php $metabox->the_name('coupon_code'); ?>" value="<?php $metabox->the_value('coupon_code'); ?>"/>
		<span><?php _e('Enter the the coupon code (e.g. \'lebaran-sale\')', 'cell-store') ?></span>
	</p>
 
	<h4><input type="checkbox" data-target="#free-shipping-input" class="use-optional" name="<?php $metabox->the_name('use_free_shipping'); ?>" value="1"<?php if ($metabox->get_the_value('use_free_shipping')) echo ' checked="checked"'; ?>/> Use Free Shipping</h4>
	<div id="free-shipping-input" class="">
		<p>
			<select name="<?php $metabox->the_name('area_limit'); ?>">
				<option value="all" <?php selected($metabox->get_the_value('area_limit'), 'all'); ?>><?php _e('All Shipping Area', 'cell-store') ?></option>
				<?php
					$args = array(
						'post_type' => 'shipping-destination',
						// 'orderby' => 'parent',
						'sort_order' => 'ASC',
						// 'nopaging' => true

					);
					$shipping_destinations = get_pages($args);
					foreach ($shipping_destinations as $key => $value) {
						$selected = selected($metabox->get_the_value('area_limit'), $value->ID, false);
						$parents = get_ancestors($value->ID, 'shipping-destination');
						if (count($parents) == 1) {
							$child = ' - ';
						} elseif (count($parents) == 2) {
							$child = ' -- ';
						} else {
							$child = '';
						}
						echo '<option value="'.$value->ID.'" '. $selected.'>'.$child.$value->post_title.'</option>';
					}

				?>
			</select>
			<?php // print_r($area) ?>
			<span><?php _e('Limit the shipping area', 'cell-store') ?></span>
		</p>
	</div>

	<h4><input type="checkbox" data-target="#discount-input" class="use-optional" name="<?php $metabox->the_name('use_discount'); ?>" value="1"<?php if ($metabox->get_the_value('use_discount')) echo ' checked="checked"'; ?>/> <?php _e('Use Discount', 'cell-store') ?></h4>
	<div id="discount-input" class="">
		<p>
			<input type="text" name="<?php $metabox->the_name('discount_value'); ?>" value="<?php $metabox->the_value('discount_value'); ?>"/>
			<span><?php _e('Enter the the discount percentage (e.g. \'10%\')', 'cell-store') ?></span>
		</p>
	</div>

	<label><?php _e('Coupon Start / End Date', 'cell-store') ?></label>
	<p>
		<input type="text" class="date-input" name="<?php $mb->the_name('coupon_start'); ?>" value="<?php $mb->the_value('coupon_start'); ?>"/>
		<input type="text" class="date-input" name="<?php $mb->the_name('coupon_end'); ?>" value="<?php $mb->the_value('coupon_end'); ?>"/>
		<span><?php _e('Leave empty for no time limit', 'cell-store') ?></span>
	</p>

	<label><?php _e('Coupon Usage Limit', 'cell-store') ?></label>
	<p>
		<input type="text" name="<?php $mb->the_name('coupon_limit'); ?>" value="<?php $mb->the_value('coupon_limit'); ?>"/>
		<span><?php _e('Leave empty for no usage limit', 'cell-store') ?></span>
	</p>

</div>