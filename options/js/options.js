jQuery(document).ready(function(){
	
	
	if(jQuery('#last_tab').val() == ''){

		jQuery('.cell-store-group-tab:first').slideDown(300);
		jQuery('#cell-store-group-menu li:first').addClass('active');
	
	}else{
		
		tabid = jQuery('#last_tab').val();
		jQuery('#'+tabid+'_section_group').slideDown(300);
		jQuery('#'+tabid+'_section_group_li').addClass('active');
		
	}
	
	
	jQuery('input[name="'+nhp_opts.opt_name+'[defaults]"]').click(function(){
		if(!confirm(nhp_opts.reset_confirm)){
			return false;
		}
	});
	
	jQuery('.cell-store-group-tab-link-a').click(function(){
		relid = jQuery(this).attr('data-rel');
		
		jQuery('#last_tab').val(relid);
		
		jQuery('.cell-store-group-tab').each(function(){
			if(jQuery(this).attr('id') == relid+'_section_group'){
				jQuery(this).delay(400).fadeIn(200);
			}else{
				jQuery(this).hide();
			}
			
		});
		
		jQuery('.cell-store-group-tab-link-li').each(function(){
				if(jQuery(this).attr('id') != relid+'_section_group_li' && jQuery(this).hasClass('active')){
					jQuery(this).removeClass('active');
				}
				if(jQuery(this).attr('id') == relid+'_section_group_li'){
					jQuery(this).addClass('active');
				}
		});
	});
	
	
	
	
	if(jQuery('#cell-store-save').is(':visible')){
		jQuery('#cell-store-save').delay(500).slideUp(300);
	}
	
	if(jQuery('#cell-store-imported').is(':visible')){
		jQuery('#cell-store-imported').delay(500).slideUp(300);
	}	
	
	jQuery('input, textarea, select').change(function(){
		jQuery('#cell-store-save-warn').slideDown(300);
	});
	
	
	jQuery('#cell-store-import-code-button').click(function(){
		if(jQuery('#cell-store-import-link-wrapper').is(':visible')){
			jQuery('#cell-store-import-link-wrapper').hide();
			jQuery('#import-link-value').val('');
		}
		jQuery('#cell-store-import-code-wrapper').fadeIn(300);
	});
	
	jQuery('#cell-store-import-link-button').click(function(){
		if(jQuery('#cell-store-import-code-wrapper').is(':visible')){
			jQuery('#cell-store-import-code-wrapper').hide();
			jQuery('#import-code-value').val('');
		}
		jQuery('#cell-store-import-link-wrapper').fadeIn(300);
	});
	
	
	
	
	jQuery('#cell-store-export-code-copy').click(function(){
		if(jQuery('#cell-store-export-link-value').is(':visible')){jQuery('#cell-store-export-link-value').fadeOut(300);}
		jQuery('#cell-store-export-code').toggle('fade');
	});
	
	jQuery('#cell-store-export-link').click(function(){
		if(jQuery('#cell-store-export-code').is(':visible')){jQuery('#cell-store-export-code').fadeOut(300);}
		jQuery('#cell-store-export-link-value').toggle('fade');
	});
	
	

	
	
	
});