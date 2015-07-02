<?php

// echo '<pre>';
// print_r($options);
// echo '</pre>';

$transfer_destination = [];
$next_key = 0;
if (isset($options['transfer-destination'])) {
	$transfer_destination = $options['transfer-destination'];
}

echo '<pre>';
print_r($transfer_destination);
echo '</pre>';

?>

<div id="" class="custom-option">
	<?php foreach ($transfer_destination as $key => $detail): ?>
		<?php
			$title = '';
			if (isset($detail['title'])) {
				$title = $detail['title'];
			}
			$image = '';
			if (isset($detail['image'])) {
				$image = $detail['image'];
			}
			$description = '';
			if (isset($detail['description'])) {
				$description = $detail['description'];
			}
			$next_key = $key +1 ;
		?>
		<div id="wpa_loop-destination" class="wpa_loop wpa_loop-destination">
			<div class="wpa_group wpa_group-destination ">
				<p><label>Transfer Title</label></p>
				<p>
					<input type="text" value="<?php echo $title ?>" class="regular-text" name="cell_store_payments[transfer-destination][<?php echo $key ?>][title]"/>
					<a class="button-secondary dodelete" href="#" title="<?php esc_attr_e( 'Title for Example Link Button' ); ?>"><?php esc_attr_e( 'Delete Transfer Destination' ); ?></a>
				</p>
				<p><label>Transfer Icon</label></p>
				<p>
					<input type="text" value="<?php echo $image ?>" class="regular-text" name="cell_store_payments[transfer-destination][<?php echo $key ?>][image]"/>
				</p>
				<p><textarea id="" name="cell_store_payments[transfer-destination][<?php echo $key ?>][description]" cols="80" rows="10" class="large-text"><?php echo $description ?></textarea><br></p>
				<hr>
			</div>
		</div>
	<?php endforeach ?>
	<div id="wpa_loop-destination" class="wpa_loop wpa_loop-destination">
		<div class="wpa_group wpa_group-destination last tocopy">
			<p><label>Transfer Title</label></p>
			<p>
				<input type="text" class="regular-text" name="cell_store_payments[transfer-destination][<?php echo $next_key ?>][title]"/>
				<a class="button-secondary dodelete" href="#" title="<?php esc_attr_e( 'Title for Example Link Button' ); ?>"><?php esc_attr_e( 'Delete Transfer Destination' ); ?></a>
			</p>
			<p><label>Transfer Icon</label></p>
			<p>
				<input type="text" class="regular-text" name="cell_store_payments[transfer-destination][<?php echo $next_key ?>][image]"/>
			</p>
			<p><textarea id="" name="cell_store_payments[transfer-destination][<?php echo $next_key ?>][description]" cols="80" rows="10" class="large-text"></textarea><br></p>
			<hr>
		</div>
	</div>
	<a href="#" class="docopy-destination button">Add Transfer Destination</a>
</div>

