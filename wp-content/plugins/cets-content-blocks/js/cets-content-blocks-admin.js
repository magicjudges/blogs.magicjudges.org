
function cets_content_blocks_send_to_editor() {
	var blockid = jQuery("#cets_content_block_select").val();

	var hint = jQuery("#cets_content_block_select option:selected").text();

	var text = "[cets_content_block id=" + blockid + " hint=" + hint + "]";


	window.send_to_editor(text);
}
		
