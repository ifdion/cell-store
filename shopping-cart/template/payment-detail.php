<p><?php _e('Please review your transaction details', 'cell-store') ?>.</p>
<p><?php _e('Once payment succeeds, login and click CONFIRM PAYMENT menu to finish transaction and we will get your order ready to ship', 'cell-store') ?>.</p>
<p><?php printf(__('Payment will be made using <strong> %s </strong>', 'cell-store'), $_SESSION['shopping-cart']['payment']['method']) ?></p>