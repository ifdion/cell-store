<div class="my_meta_control">
	<?php
		 $args = array(
			'post_id' => $post->ID,
			'order' =>'ASC'
		); 
		$log = get_comments($args);
		$post_meta = get_post_meta($post->ID);
		$billing = unserialize($post_meta['_billing'][0]);
	?>
	<ul class="post-revisions">
		<li><?php printf(__('%1$s  : Transaction made by %2$s', 'cell-store'), $post->post_date, $billing['first-name'] ) ?></li>
		<?php foreach ($log as $log_item): ?>
			<li> <?php echo $log_item->comment_date.' : '.$log_item->comment_content ;?></li>	
		<?php endforeach ?>
	</ul>
</div>