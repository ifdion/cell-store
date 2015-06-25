<?php

// echo '<pre>';
// print_r($options);
// echo '</pre>';

$transfer_input = [];
$next_key = 0;
if (isset($options['transfer-input'])) {
	$transfer_input = $options['transfer-input'];
}

// echo '<pre>';
// print_r($transfer_input);
// echo '</pre>';

?>

<div id="" class="custom-option">
	<?php foreach ($transfer_input as $key => $detail): ?>
		<?php
			$title = '';
			if (isset($detail['title'])) {
				$title = $detail['title'];
			}
			$image = '';
			if (isset($detail['image'])) {
				$image = $detail['image'];
			}
			$next_key = $key +1 ;
		?>
		<div id="wpa_loop-input" class="wpa_loop wpa_loop-input">
			<div class="wpa_group wpa_group-input ">
				<p><label>Input Title</label></p>
				<p>
					<input type="text" value="<?php echo $title ?>" class="regular-text" name="cell_store_payments[transfer-input][<?php echo $key ?>][title]"/>
					<a class="button-secondary dodelete" href="#" title="<?php esc_attr_e( 'Title for Example Link Button' ); ?>"><?php esc_attr_e( 'Delete Input' ); ?></a>
				</p>
				<hr>
			</div>
		</div>
	<?php endforeach ?>
	<div id="wpa_loop-input" class="wpa_loop wpa_loop-input">
		<div class="wpa_group wpa_group-input last tocopy">
			<p><label>Input Title</label></p>
			<p>
				<input type="text" class="regular-text" name="cell_store_payments[transfer-input][<?php echo $next_key ?>][title]"/>
				<a class="button-secondary dodelete" href="#" title="<?php esc_attr_e( 'Title for Example Link Button' ); ?>"><?php esc_attr_e( 'Delete Input' ); ?></a>
			</p>
			<hr>
		</div>
	</div>
	<a href="#" class="docopy-input button">Add Input Field</a>
</div>

