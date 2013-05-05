jQuery(document).ready(function($){

/* change province based on country
---------------------------------------------------------------
kalau ada perubahan di select.select address,
ambil anak anak cpt pake fungsi ajax get_child_shipping_destination

kalau udah anak anak itu dijadiin select untuk data-target=

*/

$('select.select-address').live('change',function(){
	address_field = $(this).attr('name');
	address = $(this).val();
	target_name = $(this).attr('data-target');
	target = $('[name="'+target_name+'"]');

	$(this).find('[value="intro"]').attr('disabled','disabled');

	if (parseInt(address,10)) {
		if ($(this).attr('data-target')) {
			$(this).after(' <span class="loading"><loading/>');
		}
		data = 'action=get_child_shipping_destination&id='+address;
		$.post(global.ajaxurl, data, function(result) {
			$('.loading').remove();
			result_object = $.parseJSON(result);

			if(result_object.type == 'success'){
				if (result_object.message > 0) {
					target.empty();
					$.each(result_object.content, function(key,value){
						target.append('<option value="'+key+'">'+value+'</option>');
					});
					target.removeAttr('disabled');
				} else {
					hide_child(target_name);
				}
			}
		});
	} else if ( address == 'default') {
	}
});

$('input.working').live('blur',function(){
	$(this).addClass('select-address').removeClass('working');
});

function hide_child(address_field){
	data_target = $('[name="'+address_field+'"]').attr('data-target');
	console.log(data_target);
	$('[name="'+address_field+'"]').attr('disabled','disabled');
	$('[name="'+address_field+'"] option:first-child').attr('selected','selected');
	if (address_field == 'country') {
		hide_child('province');
	} else if (address_field == 'province') {
		hide_child('city');
	} else if (address_field == 'city') {
		hide_child('district');
	}
}

/* show or hide shipping field based on have shiping field 
---------------------------------------------------------------
*/
if($('input[name="have-shipping"]').length > 0){
	check_shipping(); // on document ready

	$('input[name="have-shipping"]').change(function(){
		check_shipping();
	});
}

function check_shipping(){
	if ($('input[name="have-shipping"]').attr('checked') == 'checked') {
		$('#shipping-field').show();
	} else {
		$('#shipping-field').hide();
	}
}

});
