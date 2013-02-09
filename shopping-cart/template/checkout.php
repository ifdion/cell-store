			<form id="checkout" name="checkout" class="well form-horizontal" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" enctype="multipart/form-data">
				<?php if (!is_user_logged_in()): ?>
					<div class="control-group">
						<label class="control-label" for="first-name"><?php _e('First Name', 'hijabchic') ?></label>
						<div class="controls">
							<input type="text" class="input-xlarge " id="first-name" name="first-name" >
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="last-name"><?php _e('Last Name', 'hijabchic') ?></label>
						<div class="controls">
							<input type="text" class="input-xlarge " id="last-name" name="last-name" >
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="email"><?php _e('Email', 'hijabchic') ?></label>
						<div class="controls">
							<input type="text" class="input-xlarge " id="email" name="email" >
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="telephone"><?php _e('Telephone', 'hijabchic') ?></label>
						<div class="controls">
							<input type="text" class="input-xlarge " id="telephone" name="telephone" >
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="company"><?php _e('Company', 'hijabchic') ?></label>
						<div class="controls">
							<input type="text" class="input-xlarge " id="company" name="company" >
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="address"><?php _e('Address', 'hijabchic') ?></label>
						<div class="controls">
							<textarea class="input-xlarge " id="address" name="address" ></textarea>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="country"><?php _e('Country', 'hijabchic') ?></label>
						<div class="controls">
							<?php
								$args = array(
									'post_type' => 'shipping-destination',
									'post_parent' => 0,
									'nopaging' => true
								);
								$country = new WP_Query($args);
							?>
							<select id="country" name="country" class="select-address" data-target="province">
								<?php if ( $country->have_posts() ) : ?>
									<option value="intro"><?php _e('Please select', 'hijabchic') ?></option>
									<?php while ( $country->have_posts() ) : $country->the_post(); ?>
										<option value="<?php the_ID() ?>"><?php the_title() ?></option>
									<?php endwhile; ?>
									<option value="other"><?php _e('Other', 'hijabchic') ?></option>
								<?php endif; ?>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="province"><?php _e('Province / State / County', 'hijabchic') ?></label>
						<div class="controls">
							<select id="province" name="province" disabled="disabled" class="select-address" data-target="city">
								<option value="intro"><?php _e('Please select', 'hijabchic') ?></option>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="city"><?php _e('City', 'hijabchic') ?></label>
						<div class="controls">
							<select id="city" name="city" disabled="disabled" class="select-address" data-target="district">
								<option value="intro"><?php _e('Please select', 'hijabchic') ?></option>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="district"><?php _e('District', 'hijabchic') ?></label>
						<div class="controls">
							<select id="district" name="district" disabled="disabled" class="select-address">
								<option value="intro"><?php _e('Please select', 'hijabchic') ?></option>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="postcode"><?php _e('Postcode', 'hijabchic') ?></label>
						<div class="controls">
							<input type="text" class="input-xlarge " id="postcode" name="postcode" >
						</div>
					</div>
					<h4><?php _e('Set Up an Account', 'hijabchic') ?></h4>
					<div class="control-group">
						<label class="control-label" for="username"><?php _e('Username', 'hijabchic') ?></label>
						<div class="controls">
							<input type="text" class="input-xlarge " id="username" name="username" value="<?php echo $user_data['username'][0] ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="password"><?php _e('Password', 'hijabchic') ?></label>
						<div class="controls">
							<input type="password" class="input-xlarge " id="password" name="password" value="<?php echo $user_data['password'][0] ?>">
						</div>
						<p class="help-block"><?php _e('Checkout faster and track your purchase with your account.', 'hijabchic') ?></p>
					</div>
				<?php else: ?>
					<?php
						global $current_user;
						$user_data = get_user_meta($current_user->ID);
						if ($user_data['first_name'][0]) {
							$first_name = $user_data['first_name'][0] ;
						} else {
							$first_name = $current_user->display_name;
						}
					?>
					<div class="control-group">
						<label class="control-label" for="first-name"><?php _e('First Name', 'hijabchic') ?></label>
						<div class="controls">
							<input type="text" class="input-xlarge " id="first-name" name="first-name" value="<?php echo $first_name ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="last-name"><?php _e('Last Name', 'hijabchic') ?></label>
						<div class="controls">
							<input type="text" class="input-xlarge " id="last-name" name="last-name" value="<?php echo $user_data['last_name'][0] ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="email"><?php _e('Email', 'hijabchic') ?></label>
						<div class="controls">
							<input type="text" class="input-xlarge " id="email" name="email" value="<?php echo $current_user->user_email ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="telephone"><?php _e('Telephone', 'hijabchic') ?></label>
						<div class="controls">
							<?php
								if ($user_data['have-shipping'][0] && $user_data['shipping-telephone'][0]) {
									$telephone = $user_data['shipping-telephone'][0];
								} else {
									$telephone = $user_data['telephone'][0];
								}
							?>
							<input type="text" class="input-xlarge " id="telephone" name="telephone" value="<?php echo $telephone ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="company"><?php _e('Company', 'hijabchic') ?></label>
						<div class="controls">
							<?php
								if ($user_data['have-shipping'][0] && $user_data['shipping-company'][0]) {
									$company = $user_data['shipping-company'][0];
								} else {
									$company = $user_data['company'][0];
								}
							?>
							<input type="text" class="input-xlarge " id="company" name="company" value="<?php echo $company ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="address"><?php _e('Address', 'hijabchic') ?></label>
						<div class="controls">
							<?php
								if ($user_data['have-shipping'][0] && $user_data['shipping-address'][0]) {
									$address = $user_data['shipping-address'][0];
								} else {
									$address = $user_data['address'][0];
								}
							?>
							<textarea class="input-xlarge " id="address" name="address" ><?php echo $address ?></textarea>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="country"><?php _e('Country', 'hijabchic') ?></label>
						<div class="controls">
							<?php
								$args = array(
									'post_type' => 'shipping-destination',
									'post_parent' => 0,
									'nopaging' => true
								);
								$countries = new WP_Query($args);

								if ($user_data['have-shipping'][0] && $user_data['shipping-country'][0]) {
									$current_country =  $user_data['shipping-country'][0];
								} else {
									$current_country =  $user_data['country'][0];
								}
								
								if (is_numeric($current_country)) {
									$selected_country = $current_country;
								}
							?>
							<select id="country" name="country" class="select-address" data-target="province">
								<?php if ( $countries->have_posts() ) : ?>
									<option value="intro"><?php _e('Please select', 'hijabchic') ?></option>
									<?php while ( $countries->have_posts() ) : $countries->the_post(); ?>
										<option value="<?php the_ID() ?>" <?php selected($selected_country, get_the_ID()) ?>><?php the_title() ?></option>
									<?php endwhile; ?>
									<option value="other"><?php _e('Other', 'hijabchic') ?></option>
								<?php endif; ?>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="province"><?php _e('Province', 'hijabchic') ?></label>
						<div class="controls">
							<?php
								if ($user_data['have-shipping'][0] && $user_data['shipping-province'][0]) {
									$current_province =  $user_data['shipping-province'][0];
								} else {
									$current_province =  $user_data['province'][0];
								}
								
								$disabled = 'disabled="disabled"';
								if (is_numeric($current_province)) {
									$selected_province = $current_province;
								}
								if ($selected_country) {
									$args = array(
										'post_type' => 'shipping-destination',
										'post_parent' => $selected_country,
										'nopaging' => true
									);
									$provinces = new WP_Query($args);
									$disabled = '';
								}
							?>
							<select id="province" name="province" <?php echo $disabled; ?> class="select-address" data-target="city">
								<option value="intro"><?php _e('Please select', 'hijabchic') ?></option>
								<?php if ( $provinces && $provinces->have_posts() ) : ?>
									<?php while ( $provinces->have_posts() ) : $provinces->the_post(); ?>
										<option value="<?php the_ID() ?>" <?php selected($selected_province, get_the_ID()) ?>><?php the_title() ?></option>
									<?php endwhile; ?>
								<?php endif; ?>
								<option value="other"><?php _e('Other', 'hijabchic') ?></option>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="city"><?php _e('City', 'hijabchic') ?></label>
						<div class="controls">
							<?php
								if ($user_data['have-shipping'][0] && $user_data['shipping-city'][0]) {
									$current_city =  $user_data['shipping-city'][0];
								} else {
									$current_city =  $user_data['city'][0];
								}
								
								$disabled = 'disabled="disabled"';
								if (is_numeric($current_city)) {
									$selected_city = $current_city;
								}
								if ($selected_province) {
									$args = array(
										'post_type' => 'shipping-destination',
										'post_parent' => $selected_province,
										'nopaging' => true
									);
									$cities = new WP_Query($args);
									$disabled = '';
								}
							?>
							<select id="city" name="city" <?php echo $disabled; ?> class="select-address" data-target="district">
								<option value="intro"><?php _e('Please select', 'hijabchic') ?></option>
								<?php if ( $cities && $cities->have_posts() ) : ?>
									<?php while ( $cities->have_posts() ) : $cities->the_post(); ?>
										<option value="<?php the_ID() ?>" <?php selected($selected_city, get_the_ID()) ?>><?php the_title() ?></option>
									<?php endwhile; ?>
								<?php endif; ?>
								<option value="other"><?php _e('Other', 'hijabchic') ?></option>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="district"><?php _e('District', 'hijabchic') ?></label>
						<div class="controls">
							<?php
								if ($user_data['have-shipping'][0] && $user_data['shipping-district'][0]) {
									$current_district =  $user_data['shipping-district'][0];
								} else {
									$current_district =  $user_data['district'][0];
								}
								
								$disabled = 'disabled="disabled"';
								if (is_numeric($current_district)) {
									$selected_district = $current_district;
								}
								if ($selected_city) {
									$args = array(
										'post_type' => 'shipping-destination',
										'post_parent' => $selected_city,
										'nopaging' => true
									);
									$districts = new WP_Query($args);
									$disabled = '';
								}
							?>
							<select id="district" name="district" <?php echo $disabled; ?> class="select-address">
								<option value="intro"><?php _e('Please select', 'hijabchic') ?></option>
								<?php if ( $districts && $districts->have_posts() ) : ?>
									<?php while ( $districts->have_posts() ) : $districts->the_post(); ?>
										<option value="<?php the_ID() ?>" <?php selected($selected_district, get_the_ID()) ?>><?php the_title() ?></option>
									<?php endwhile; ?>
								<?php endif; ?>
								<option value="other"><?php _e('Other', 'hijabchic') ?></option>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="postcode"><?php _e('Postcode', 'hijabchic') ?></label>
						<div class="controls">
							<input type="text" class="input-xlarge " id="postcode" name="postcode" value="<?php echo $user_data['shipping-postcode'][0] ?>">
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							<label for="save-shipping-address"><input type="checkbox" class="input-xlarge " id="save-shipping-address" name="save-shipping-address" value="1"> <?php _e('Save as Shipping Address', 'hijabchic') ?></label>
						</div>
					</div>
				<?php endif ?>
				<div class="form-actions">
					<button type="submit" class="btn btn-primary"><?php _e('Checkout', 'hijabchic') ?> <i class="icon icon-chevron-right icon-white"></i></button>
					<?php wp_nonce_field('checkout','checkout_nonce'); ?>
					<input name="action" value="checkout" type="hidden">
				</div>
			</form>