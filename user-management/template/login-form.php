<form id="loginform" name="loginform" class="well form-horizontal" action="<?php bloginfo('url') ?>/wp-login.php" method="post" _lpchecked="1">
	<div class="control-group hide">
		<label class="control-label" for="input01"><?php _e('Login with', 'cell-store') ?></label>
		<div class="controls">
			<button class="btn btn-primary"><?php _e('Facebook Button', 'cell-store') ?></i></button>
			<p class="help-block"><?php _e('or', 'cell-store') ?></p>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="input01"><?php _e('Billing Address', 'cell-store') ?></label>
		<div class="controls">
			<input type="text" class="input-xlarge" id="user_login" name="log">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="input01"><?php _e('Password', 'cell-store') ?></label>
		<div class="controls">
			<input type="password" class="input-xlarge" id="input01" name="pwd">
			<p class="help-block"><a href="<?php bloginfo('url') ?>'wp-login.php?action=lostpassword"><?php _e('Forgot password ?', 'cell-store') ?></a></p>
		</div>
	</div>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary"><?php _e('Login', 'cell-store') ?> <i class="icon icon-chevron-right icon-white"></i></button>
	</div>
</form>