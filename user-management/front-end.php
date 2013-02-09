<?php

/* Standard Login Form 
---------------------------------------------------------------
*/
function cell_login_form(){
	ob_start();
	include('template/login-form.php');
	$login_form = ob_get_contents();
	ob_end_clean();

	echo $login_form;
}


/* Custom Registration Form 
---------------------------------------------------------------
*/

// $fields = $registration_field;
function cell_custom_login_form(){

	if(!is_user_logged_in()){

	ob_start();

	if (isset($_REQUEST['forgot-password']) && $_REQUEST['forgot-password']==1) {
		include('template/custom-forgot-password-form.php');
	} elseif( isset($_REQUEST['reset-password']) && $_REQUEST['reset-password']==1) {
		include('template/custom-reset-password-form.php');
	} else {
		include('template/custom-login-form.php');
	}
	$login_form = ob_get_contents();
	
	ob_end_clean();

	echo $login_form;

	} else {
		return false;
	}


}


/* Custom Registration Form 
---------------------------------------------------------------
Call cell_registration_form() as a template tag to use the default registration form
or
write your own registration form using the fields on $registration_field and the $required_notification array

*/

// $fields = $registration_field;
function cell_registration_form(){
	global $registration_field;

	if(!is_user_logged_in()){
		$fields = $registration_field;
		ob_start();
		include('template/custom-registration-form.php');
		$register_form = ob_get_contents();
		ob_end_clean();

		echo $register_form;		
	} else {
		return false;
	}

}

/* Custom Profile Edit 
---------------------------------------------------------------
Call cell_edit_profile_form() as a template tag to use the default cell_edit_profile form
or
write your own registration form using the fields on $registration_field and the $required_notification array

*/

// $fields = $registration_field;
function cell_edit_profile_form(){
	global $registration_field;
	$fields = $registration_field;
	ob_start();
	include('template/custom-edit-profile-form.php');
	$register_form = ob_get_contents();
	ob_end_clean();

	echo $register_form;
}

?>