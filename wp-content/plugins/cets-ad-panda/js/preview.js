
function ad_panda_preview(inSelect,inPreview) {
	
	var ad_id = jQuery("#"+inSelect).val();	
	jQuery("#"+inPreview).load("/wp-content/plugins/cets-ad-panda/ads/"+ad_id+"/ad.php");
	
	
}

