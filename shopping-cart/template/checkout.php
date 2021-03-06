<form id="checkout" name="checkout" class="well form-horizontal" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" enctype="multipart/form-data">
	<h4><?php _e('Shipping Detail', 'cell-store') ?></h4>
	<?php
		if (is_user_logged_in()){
			$update = true;
			global $current_user;
			$user_meta = get_user_meta($current_user->ID);
			if ($user_meta['first_name'][0]) {
				$first_name = $user_meta['first_name'][0] ;
			} else {
				$first_name = $current_user->display_name;
			}
		}
	?>
	<div class="control-group">
		<label class="control-label" for="first-name"><?php _e('First Name', 'cell-store') ?></label>
		<div class="controls">
			<input type="text" class="input-xlarge " id="first-name" name="first-name" value="<?php if (isset($first_name)) {echo $first_name;} ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="last-name"><?php _e('Last Name', 'cell-store') ?></label>
		<div class="controls">
			<input type="text" class="input-xlarge " id="last-name" name="last-name" value="<?php if (isset($user_meta['last_name'][0])) {echo $user_meta['last_name'][0];} ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="email"><?php _e('Email', 'cell-store') ?></label>
		<div class="controls">
			<input type="text" class="input-xlarge " id="email" name="email" value="<?php if (isset($current_user->user_email)) {echo $current_user->user_email;}  ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="telephone"><?php _e('Telephone', 'cell-store') ?></label>
		<div class="controls">
			<?php
				if (isset($user_meta['have-shipping'][0]) && isset($user_meta['shipping-telephone'][0])) {
					$telephone = $user_meta['shipping-telephone'][0];
				} elseif(isset($user_meta['telephone'][0])) {
					$telephone = $user_meta['telephone'][0];
				} else {
					$telephone = '';
				}
			?>
			<input type="text" class="input-xlarge " id="telephone" name="telephone" value="<?php echo $telephone ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="company"><?php _e('Company', 'cell-store') ?></label>
		<div class="controls">
			<?php
				if (isset($user_meta['have-shipping'][0]) && isset($user_meta['shipping-company'][0])) {
					$company = $user_meta['shipping-company'][0];
				} elseif(isset($user_meta['company'][0])) {
					$company = $user_meta['company'][0];
				} else {
					$company = '';
				}
			?>
			<input type="text" class="input-xlarge " id="company" name="company" value="<?php echo $company ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="address"><?php _e('Address', 'cell-store') ?></label>
		<div class="controls">
			<?php
				if (isset($user_meta['have-shipping'][0]) && isset($user_meta['shipping-address'][0])) {
					$address = $user_meta['shipping-address'][0];
				} elseif (isset($user_meta['address'][0])) {
						$address = $user_meta['address'][0];
				} else {
					$address = '';
				}
			?>
			<textarea class="input-xlarge " id="address" name="address" ><?php echo $address ?></textarea>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="country"><?php _e('Country', 'cell-store') ?></label>
		<div class="controls">
			<?php
				$args = array(
					'post_type' => 'shipping-destination',
					'post_parent' => 0,
					'nopaging' => true
				);
				$countries = new WP_Query($args);

				if (isset($user_meta['have-shipping'][0]) && isset($user_meta['shipping-country'][0])) {
					$current_country = $user_meta['shipping-country'][0];
				} elseif(isset($user_meta['country'][0])) {
					$current_country = $user_meta['country'][0];
				} else {
					$current_country = '';
				}
				
				if (is_numeric($current_country)) {
					$selected_country = $current_country;
				}
			?>
			<select id="country" name="country" class="select-address" data-target="province">
				<?php if ( $countries->have_posts() ) : ?>
					<option value="intro"><?php _e('Please select', 'cell-store') ?></option>
					<?php while ( $countries->have_posts() ) : $countries->the_post(); ?>
						<option value="<?php the_ID() ?>" <?php if(isset($selected_country)){selected($selected_country, get_the_ID());} ?>><?php the_title() ?></option>
					<?php endwhile; ?>
				<?php endif; ?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="province"><?php _e('Province', 'cell-store') ?></label>
		<div class="controls">
			<?php
				if (isset($user_meta['have-shipping'][0]) && isset($user_meta['shipping-province'][0])) {
					$current_province =  $user_meta['shipping-province'][0];
				} elseif(isset($user_meta['province'][0])) {
					$current_province = $user_meta['province'][0];
				} else {
					$current_province =  '';
				}
				
				$disabled = 'disabled="disabled"';
				if (is_numeric($current_province)) {
					$selected_province = $current_province;
				} else {
					$selected_province = '';
				}
				if (isset($selected_country)) {
					$args = array(
						'post_type' => 'shipping-destination',
						'post_parent' => $selected_country,
						'nopaging' => true
					);
					$provinces = new WP_Query($args);
					// echo('<pre>');
					// print_r($provinces);
					if ($provinces->post_count > 0) {
						$disabled = '';
					}
					
				}
			?>
			<select id="province" name="province" <?php echo $disabled; ?> class="select-address" data-target="city">
				<option value=""><?php _e('Please select', 'cell-store') ?></option>
				<?php if ( isset($provinces) && $provinces->have_posts() ) : ?>
					<?php while ( $provinces->have_posts() ) : $provinces->the_post(); ?>
						<option value="<?php the_ID() ?>" <?php selected($selected_province, get_the_ID()) ?>><?php the_title() ?></option>
					<?php endwhile; ?>
				<?php endif; ?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="city"><?php _e('City', 'cell-store') ?></label>
		<div class="controls">
			<?php
				if (isset($user_meta['have-shipping'][0]) && isset($user_meta['shipping-city'][0])) {
					$current_city =  $user_meta['shipping-city'][0];
				} elseif(isset($user_meta['city'][0])) {
					$current_city =  $user_meta['city'][0];
				} else {
					$current_city =  '';
				}
				
				$disabled = 'disabled="disabled"';
				if (is_numeric($current_city)) {
					$selected_city = $current_city;
				} else {
					$selected_city = '';
				}
				if (isset($selected_province) && $selected_province != '' && $selected_province != 0) {
					$args = array(
						'post_type' => 'shipping-destination',
						'post_parent' => $selected_province,
						'nopaging' => true
					);
					$cities = new WP_Query($args);
					if ($cities->post_count > 0) {
						$disabled = '';
					}
				}
			?>
			<select id="city" name="city" <?php echo $disabled; ?> class="select-address" data-target="district">
				<option value=""><?php _e('Please select', 'cell-store') ?></option>
				<?php if ( isset($cities) && $cities->have_posts() ) : ?>
					<?php while ( $cities->have_posts() ) : $cities->the_post(); ?>
						<option value="<?php the_ID() ?>" <?php selected($selected_city, get_the_ID()) ?>><?php the_title() ?></option>
					<?php endwhile; ?>
				<?php endif; ?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="district"><?php _e('District', 'cell-store') ?></label>
		<div class="controls">
			<?php
				if (isset($user_meta['have-shipping'][0]) && isset($user_meta['shipping-district'][0])) {
					$current_district =  $user_meta['shipping-district'][0];
				}elseif (isset($user_meta['district'][0])) {
					$current_district =  $user_meta['district'][0];
				} else {
					$current_district =  '';
				}
				
				$disabled = 'disabled="disabled"';
				if (is_numeric($current_district)) {
					$selected_district = $current_district;
				}
				if (isset($selected_city) && $selected_city != '' && $selected_city != 0) {
					$args = array(
						'post_type' => 'shipping-destination',
						'post_parent' => $selected_city,
						'nopaging' => true
					);
					$districts = new WP_Query($args);
					if ($districts->post_count > 0) {
						$disabled = '';
					}
				}
			?>
			<select id="district" name="district" <?php echo $disabled; ?> class="select-address">
				<option value=""><?php _e('Please select', 'cell-store') ?></option>
				<?php if ( isset($selected_district) && isset($districts) && $districts->have_posts() ) : ?>
					<?php while ( $districts->have_posts() ) : $districts->the_post(); ?>
						<option value="<?php the_ID() ?>" <?php selected($selected_district, get_the_ID()) ?>><?php the_title() ?></option>
					<?php endwhile; ?>
				<?php endif; ?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="postcode"><?php _e('Postcode', 'cell-store') ?></label>
		<div class="controls">
			<?php
				if (isset($user_meta['shipping-postcode'][0]) && $user_meta['shipping-postcode'][0] != '') {
					$shipping_postcode = $user_meta['shipping-postcode'][0];
				} elseif(isset($user_meta['postcode'][0])) {
					$shipping_postcode = $user_meta['postcode'][0];
				} else {
					$shipping_postcode = '';
				}
			?>
			<input type="text" class="input-xlarge " id="postcode" name="postcode" value="<?php echo $shipping_postcode ?>">
		</div>
	</div>
	<?php if (isset($update)): ?>
		<div class="control-group">
			<div class="controls">
				<label for="save-shipping-address"><input type="checkbox" class="input-xlarge " id="save-shipping-address" name="save-shipping-address" value="1"> <?php _e('Save as Shipping Address', 'cell-store') ?></label>
			</div>
		</div>
	<?php else: ?>
		<h4><?php _e('Set Up an Account', 'cell-store') ?></h4>
		<div class="control-group">
			<label class="control-label" for="username"><?php _e('Username', 'cell-store') ?></label>
			<div class="controls">
				<input type="text" class="input-xlarge " id="username" name="username" value="">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="password"><?php _e('Password', 'cell-store') ?></label>
			<div class="controls">
				<input type="password" class="input-xlarge " id="password" name="password" value="">
			</div>
			<p class="help-block"><?php _e('Checkout faster and track your purchase with your account.', 'cell-store') ?></p>
		</div>
	<?php endif ?>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary"><?php _e('Checkout', 'cell-store') ?> <i class="icon icon-chevron-right icon-white"></i></button>
		<?php wp_nonce_field('checkout','checkout_nonce'); ?>
		<input name="action" value="checkout" type="hidden">
	</div>
</form>