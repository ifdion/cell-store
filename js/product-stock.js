jQuery(document).ready(function($){


/* change stock for  each option
---------------------------------------------------------------
*/
if($('select[name="product-option"]').length > 0){
	change_option_stock();
}

$('select[name="product-option"]').live('change',function(){
	change_option_stock();
});

function change_option_stock(){
	selected_var = $('select[name="product-option"]').val();
	selected_stock = $('select[name="product-option"] option[value="'+selected_var+'"]').attr('data-stock');
	$('select[name="quantity"]').empty();
	for (var i = 0; i < selected_stock; i++) {
		$('select[name="quantity"]').append('<option>'+ (i+1) +'</option>')
	};
}



});
