// Docu : http://wiki.moxiecode.com/index.php/TinyMCE:Create_plugin/3.x#Creating_your_own_plugins

(function() {
	// Load plugin specific language pack
	//tinymce.PluginManager.requireLangPack('cets_EmbedGmaps');
	
	//alert("1. call to editor_plugin.js");
	
	
	tinymce.create('tinymce.plugins.js_cets_childpages', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');
			
	
			// Register sample button
			ed.addButton('js_cets_childpages', {
				
				title : 'List Childpages',
				image : url + '/cets_childpages.png',
				onclick : function() {
					function_cets_childpages_javascript();
				}
			});
			
			/*
			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('cets_sample_button', n.nodeName == 'IMG');
			});
			*/
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
					longname  : 'List All Childpages',
					author 	  : 'Jason Lemahieu',
					authorurl : 'My Website',
					infourl   : 'My Website',
					version   : "1.0"
			};
		}
	});

	//alert("3. done creating button");

	// Register plugin
	tinymce.PluginManager.add('js_cets_childpages', tinymce.plugins.js_cets_childpages);
	
	//alert("4. plugin registered.");
})();


