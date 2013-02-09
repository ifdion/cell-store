<div class="my_meta_control">

 
	<label><?php _e('Transaction Code / Status', 'cell-store') ?></label>
	<p>
		<input class="disabled" type="text" name="transaction_code" value="<?php echo $post->post_name ?>" disabled="disabled"/>
		<select name="<?php $metabox->the_name('transaction_status'); ?>">
			<option value="pending" <?php selected($metabox->get_the_value('transaction_status'), 'pending'); ?>><?php _e('Pending Payment', 'cell-store') ?></option>
			<option value="paid" <?php selected($metabox->get_the_value('transaction_status'), 'paid'); ?>><?php _e('Paid', 'cell-store') ?></option>
			<option value="completed" <?php selected($metabox->get_the_value('transaction_status'), 'completed'); ?>><?php _e('Completed', 'cell-store') ?></option>
			<option value="refunded" <?php selected($metabox->get_the_value('transaction_status'), 'refunded'); ?>><?php _e('Refunded', 'cell-store') ?></option>
			<option value="canceled" <?php selected($metabox->get_the_value('transaction_status'), 'canceled'); ?>><?php _e('Canceled', 'cell-store') ?></option>
		</select>
	</p>

	<label><?php _e('Tracking Code', 'cell-store') ?></label>
	<p>
		<input type="text" name="<?php $mb->the_name('tracking_code'); ?>" value="<?php $mb->the_value('tracking_code'); ?>"/>
	</p>


</div>