jQuery(document).ready(function($){



/* datepicker
---------------------------------------------------------------
*/
$('.date-input').datepicker({
	dateFormat : 'yy-mm-dd'
});


/* optional meta 
---------------------------------------------------------------
*/
function toogle_optional(object){

	target = object.attr('data-target');
	if (object.is(':checkbox')) {
		if(object.is(':checked')){
			$(target).show();
		} else  {
			$(target).hide();
		}
	} else {
		if(object.val() == 1){
			$(target).show();
		} else  {
			$(target).hide();
		}
	};
}

$('.use-optional').each(function(){
	toogle_optional($(this));
}).change(function(){
	toogle_optional($(this));
});

/* optional stock managament
---------------------------------------------------------------
*/

if ($('a[href="?page=cell-store-options&tab=payment"]').hasClass('nav-tab-active')) {
	$('input[value="1"]').addClass('toggle-payment');
};

$('.toggle-payment').live('click', function(){
	show_hide_tr($(this));
});

$('.toggle-payment').each(function(index){
	show_hide_tr($(this));
});

function show_hide_tr(tr){
	parent_tr = tr.parent().parent();
	if (tr.attr('checked') == 'checked') {
		parent_tr.siblings().show();
	} else {
		parent_tr.siblings().hide();
	};
}

})