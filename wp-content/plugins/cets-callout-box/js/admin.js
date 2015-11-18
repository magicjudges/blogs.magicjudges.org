function cets_callout_box_send_to_editor(text){   

	var title = jQuery("#cets_callout_box_title").val();
	var style = jQuery("#cets_callout_box_style").val();
	var align = jQuery("#cets_callout_box_align").val();
			    		
	var text = "[cets_callout_box style='" + style + "' align='" + align + "' title='" + title + "'][/cets_callout_box]";
	
    window.send_to_editor(text);
}