// JavaScript Document
function function_cets_childpages_javascript() {
	var text = "[list-children heading='More Information' depth=1]";
	if ( typeof tinyMCE != 'undefined' && ( ed = tinyMCE.activeEditor ) && !ed.isHidden() ) {
				ed.focus();
				if (tinymce.isIE)
					ed.selection.moveToBookmark(tinymce.EditorManager.activeEditor.windowManager.bookmark);
	
				ed.execCommand('mceInsertContent', false, text);
		} 
}