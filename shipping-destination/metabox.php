<div class="my_meta_control">
	<?php _e('<p>Each shipping destinations can have one or more shipping options. <br> Shippings to Jakarta can be made using Economical, Reguler or Express Service<br> while shippings to Sabang can only use Reguler Service </p>', 'cell-store') ?>
	<div id="product-details">
		<a style="float:right; margin:0 10px;" href="#" class="dodelete-detail button"><?php _e('Remove All', 'cell-store') ?></a>
		<?php while($mb->have_fields_and_multi('detail')): ?>
			<div id="" class="">
				<?php $mb->the_group_open(); ?>
					<label><?php _e('Shipping Service  : Rate per kg | Shipping Days', 'cell-store') ?></label>
					<p>
						<input type="text" name="<?php $mb->the_name('shipping_service'); ?>" value="<?php $mb->the_value('shipping_service'); ?>" placeholder="Express"/>
						<input type="text" name="<?php $mb->the_name('shipping_rate'); ?>" value="<?php $mb->the_value('shipping_rate'); ?>" placeholder="10000"/>
						<input type="text" name="<?php $mb->the_name('shipping_days'); ?>" value="<?php $mb->the_value('shipping_days'); ?>" placeholder="3-4 days"/><a href="#" class="dodelete button">✕</a>
					</p>
				<?php $mb->the_group_close(); ?>
			</div>
		<?php endwhile; ?>
		<p style="margin-bottom:15px; padding-top:5px;"><a href="#" class="docopy-detail button"><?php _e('Add', 'cell-store') ?></a></p>
	</div>
</div>