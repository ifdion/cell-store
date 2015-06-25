jQuery(document).ready(function($){


console.log('do option here');



/* duplicator for transfer destination 
---------------------------------------------------------------
*/

	$.wpalchemy = $('<div></div>').attr('id','wpalchemy').appendTo('body');

	$(document).click(function(e){
		var elem = $(e.target);

		if (elem.attr('class') && elem.filter('[class*=dodelete]').length){
			e.preventDefault();

			var p = elem.parents('.custom-option'); /*wp*/

			var the_name = elem.attr('class').match(/dodelete-([a-zA-Z0-9_-]*)/i);

			the_name = (the_name && the_name[1]) ? the_name[1] : null ;

			/* todo: expose and allow editing of this message */
			if (confirm('This action can not be undone, are you sure?')){
				if (the_name){
					$('.wpa_group-'+ the_name, p).not('.tocopy').remove();

				} else {
					elem.parents('.wpa_group').remove();
				}
				
				var the_group = elem.parents('.wpa_group');
				
				if(the_group && the_group.attr('class')){
					the_name = the_group.attr('class').match(/wpa_group-([a-zA-Z0-9_-]*)/i);
					the_name = (the_name && the_name[1]) ? the_name[1] : null ;
					checkOptionLoopLimit(the_name);
				}

				$.wpalchemy.trigger('wpa_delete');
			}
		}
	});

	$('[class*=docopy-]').click(function(e){
		e.preventDefault();

		var p = $(this).parents('.custom-option'); /*wp*/

		var the_name = $(this).attr('class').match(/docopy-([a-zA-Z0-9_-]*)/i)[1];

		var the_group = $('.wpa_group-'+ the_name +'.tocopy', p).first();

		var the_clone = the_group.clone().removeClass('tocopy last');

		var the_props = ['name', 'id', 'for', 'class'];

		the_group.find('*').each(function(i, elem)
		{
			for (var j = 0; j < the_props.length; j++)
			{
				var the_prop = $(elem).attr(the_props[j]);

				if (the_prop)
				{
					var the_match = the_prop.match(/\[(\d+)\]/i);

					if (the_match)
					{
						the_prop = the_prop.replace(the_match[0],'['+ (+the_match[1]+1) +']');

						$(elem).attr(the_props[j], the_prop);
					}

					the_match = null;

					// todo: this may prove to be too broad of a search
					the_match = the_prop.match(/n(\d+)/i);

					if (the_match)
					{
						the_prop = the_prop.replace(the_match[0], 'n' + (+the_match[1]+1));

						$(elem).attr(the_props[j], the_prop);
					}
				}
			}
		});

		if ($(this).hasClass('ontop'))
		{
			$('.wpa_group-'+ the_name, p).first().before(the_clone);
		}
		else
		{
			the_group.before(the_clone);
		}

		checkOptionLoopLimit(the_name);

		$.wpalchemy.trigger('wpa_copy', [the_clone]);
	});

	function checkOptionLoopLimit(name)
	{
		var elem = $('.docopy-' + name);

		var the_class = $('.wpa_loop-' + name).attr('class');

		if (the_class)
		{
			var the_match = the_class.match(/wpa_loop_limit-([0-9]*)/i);

			if (the_match)
			{
				var the_limit = the_match[1];

				if ($('.wpa_group-' + name).not('.wpa_group.tocopy').length >= the_limit)
				{
					elem.hide();
				}
				else
				{
					elem.show();
				}
			}
		}
	}
	
	/* do an initial limit check, show or hide buttons */
	$('[class*=docopy-]').each(function()
	{
		var the_name = $(this).attr('class').match(/docopy-([a-zA-Z0-9_-]*)/i)[1];

		checkOptionLoopLimit(the_name);
	});


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