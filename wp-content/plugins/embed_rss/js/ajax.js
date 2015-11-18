function cets_embedrss_form_shortcode() {
	var url = jQuery("#cets_RSS-dialog-input").val();
	var itemcount = jQuery("#cets_RSS-dialog-itemcount").val();
	var itemcontent = jQuery("#cets_RSS-dialog-itemcontent").attr('checked')?1:0;
	var itemauthor = jQuery("#cets_RSS-dialog-itemauthor").attr('checked')?1:0;
	var itemdate = jQuery("#cets_RSS-dialog-itemdate").attr('checked')?1:0;

	// Create the shortcode here
	var text = "[cetsEmbedRSS id=" + url + " itemcount=" + itemcount + " itemauthor=" + itemauthor + " itemdate=" + itemdate + " itemcontent=" + itemcontent + "]";
	return text;
}
function cets_embedrss_to_editor(){       
	

	var url = jQuery("#cets_RSS-dialog-input").val();
	if (url.length == 0) {
		alert("You must enter the URL of an RSS feed in the box above.");
		return;
	}
	var shortcode = cets_embedrss_form_shortcode();
	jQuery("#cets_RSS-dialog-input").val("");
	cets_embedrss_set_preview_html("<p>Enter a Feed URL above and click <a href='#' onClick='cets_embedrss_live_preview()'>Preview Feed Output</a> to test and preview your feed.</p>");
    
    window.send_to_editor(shortcode);
}

function cets_embedrss_live_preview() {
	cets_embedrss_set_preview_html("&nbsp;");
	
	var url = jQuery("#cets_RSS-dialog-input").val();
	if (url.length == 0) {
		alert("You must enter the URL of an RSS feed.");
		return;
	}

	var shortcode = cets_embedrss_form_shortcode();
	

	cets_embedrss_set_preview_html("Fetching preview...");

	jQuery.post(
		ajaxurl,
		{
			'action':'cets_embedrss_ajax_preview',
			'shortcode':shortcode
		},
		function(response) {
			if (jQuery.type(response) === "string") {
				response = JSON.parse(response);
			}
			jQuery('#cets_embedrss_preview_wrapper').html(response.html);
			//set preview links to open in new window
			jQuery(function($){
			    $('#cets_embedrss_preview_wrapper a[href^="http://"]')
			        .attr('target','_blank');
			});
				            			
		}
	);
}

function cets_embedrss_set_preview_html(html) {
	jQuery('#cets_embedrss_preview_wrapper').html(html);
}