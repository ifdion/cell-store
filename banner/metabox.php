<div class="my_meta_control">
	<label><?php echo __('URL', 'cell-store') ?></label>
	<p>
		<input type="text" name="<?php $mb->the_name('url'); ?>" value="<?php $mb->the_value('url'); ?>"/>
		<span><?php printf(__('Enter Absolute link e.g \' %s  \' ', 'cell-store'),get_bloginfo('url')) ?></span>
	</p>
</div>