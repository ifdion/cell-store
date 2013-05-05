<?php
	$customer_shipping = $_SESSION['shopping-cart']['customer']['shipping'];
	$customer_billing = $_SESSION['shopping-cart']['customer']['billing'];
?>
<table>
	<tr>
		<td><?php _e('First name', 'cell-store') ?></td>
		<td><?php echo $customer_shipping['first-name'] ?></td>
	</tr>
	<tr>
		<td><?php _e('Last name', 'cell-store') ?></td>
		<td><?php echo $customer_shipping['last-name'] ?></td>
	</tr>
	<tr>
		<td><?php _e('Email', 'cell-store') ?></td>
		<td><?php echo $customer_shipping['email'] ?></td>
	</tr>
	<tr>
		<td><?php _e('Telephone', 'cell-store') ?></td>
		<td><?php echo $customer_shipping['telephone'] ?></td>
	</tr>
	<tr>
		<td><?php _e('Company', 'cell-store') ?></td>
		<td><?php echo $customer_shipping['company'] ?></td>
	</tr>
	<tr>
		<td><?php _e('Address', 'cell-store') ?></td>
		<td><?php echo $customer_shipping['address'] ?></td>
	</tr>
	<tr>
		<?php if (is_numeric($customer_shipping['country'])) {
			$customer_shipping['country'] = get_the_title($customer_shipping['country']);
		} ?>
		<td><?php _e('Country', 'cell-store') ?></td>
		<td><?php echo $customer_shipping['country'] ?></td>
	</tr>
	<tr>
		<?php if (isset($customer_shipping['province']) && is_numeric($customer_shipping['province'])) {
			$customer_shipping['province'] = get_the_title($customer_shipping['province']);
		} ?>
		<td><?php _e('Province', 'cell-store') ?></td>
		<td><?php if (isset($customer_shipping['province'])) { echo $customer_shipping['province']; } ?></td>
	</tr>
	<tr>
		<?php if (isset($customer_shipping['city']) && is_numeric($customer_shipping['city'])) {
			$customer_shipping['city'] = get_the_title($customer_shipping['city']);
		} ?>
		<td><?php _e('City', 'cell-store') ?></td>
		<td><?php if (isset($customer_shipping['city'])) { echo $customer_shipping['city']; } ?></td>
	</tr>
	<tr>
		<?php if (isset($customer_shipping['district']) && is_numeric($customer_shipping['district'])) {
			$customer_shipping['district'] = get_the_title($customer_shipping['district']);
		} ?>
		<?php if (isset($customer_shipping['district'])): ?>
			<td><?php _e('District', 'cell-store') ?></td>
			<td><?php echo $customer_shipping['district'] ?></td>			
		<?php endif ?>

	</tr>
	<tr>
		<td><?php _e('Postcode', 'cell-store') ?></td>
		<td><?php echo $customer_shipping['postcode'] ?></td>
	</tr>
</table>