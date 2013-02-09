jQuery(document).ready(function($){

/* change province based on country
---------------------------------------------------------------
*/
$('select.select-address').live('change',function(){
	address_field = $(this).attr('name');
	address = $(this).val();
	target_name = $(this).attr('data-target');
	target = $('[name="'+target_name+'"]');

	$(this).find('[value="intro"]').attr('disabled','disabled');

	if (parseInt(address)) {
		if ($(this).attr('data-target')) {
			$(this).after(' <span class="loading"><loading/>');
		};
		data = 'action=get_child_shipping_destination&id='+address;
		$.post(global.ajaxurl, data, function(result) {
			$('.loading').remove();
			result_object = $.parseJSON(result);
			if(result_object.type == 'success'){
				if (result_object.message > 0) {
					if(target.is('input')){
						change_to_select(target_name);
						target = $('[name="'+target_name+'"]');
					};
					target.empty();
					target.append('<option value="intro">Please select</option>');
					$.each(result_object.content, function(key,value){
						target.append('<option value="'+key+'">'+value+'</option>');
					});
					target.removeAttr('disabled');
				} else {
					change_to_input(target_name);
				};
			};
		}); 	
	} else if ( address == 'other') {
		change_to_input(address_field);
	};

});

$('input.select-address').live('focus',function(){
	if ($(this).val()) {
		$(this).blur();
		target = $(this);
		parent = $('[data-target="'+ target.attr('name') +'"]');
		parent_val = parent.val();
		if (parent.is('select')) {
			parent.after(' <span class="loading"><loading/>');
			data = 'action=get_child_shipping_destination&id='+parent_val;
			$.post(global.ajaxurl, data, function(result) {
				$('.loading').remove();
				result_object = $.parseJSON(result);
				if(result_object.type == 'success'){
					if (result_object.message > 0) {
						console.log(result_object.content);
						change_to_select(target.attr('name'));
						target = $('[name="'+target.attr('name')+'"]');
						target.empty();
						target.append('<option value="intro">Please select</option>');
						$.each(result_object.content, function(key,value){
							target.append('<option value="'+key+'">'+value+'</option>');
						});
						target.removeAttr('disabled');
					} else {
						$(this).removeClass('select-address').addClass('working').focus();
					};
				};
			});
		} else {
			target.removeClass('select-address').addClass('working').focus();
		};
	};
});


$('input.working').live('blur',function(){
	$(this).addClass('select-address').removeClass('working');
});

/* change input to select vv
---------------------------------------------------------------
*/
function change_to_input(address_field){
	data_target = $('[name="'+address_field+'"]').attr('data-target');
	$('[name="'+address_field+'"]').hide().after('<input type="text" class="input-xlarge select-address" id="'+address_field+'" name="'+address_field+'" data-target="'+data_target+'" >').remove();
	if (address_field == 'country') {
		change_to_input('province');
	} else if (address_field == 'province') {
		change_to_input('city');
	} else if (address_field == 'city') {
		change_to_input('district');
	};
}

function change_to_select(address_field){
	data_target = $('[name="'+address_field+'"]').attr('data-target');
	$('[name="'+address_field+'"]').hide().after('<select class="select-address" id="'+address_field+'" name="'+address_field+'" data-target="'+data_target+'" disabled="disabled"></select> ').remove();
	if (address_field == 'country') {
		change_to_select('province');
	} else if (address_field == 'province') {
		change_to_select('city');
	} else if (address_field == 'city') {
		change_to_select('district');
	};
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
	};
}

});
