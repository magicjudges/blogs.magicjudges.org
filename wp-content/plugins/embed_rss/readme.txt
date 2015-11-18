=== Embed RSS ===
Contributors: MadtownLems, DeannaS, CETS, kgraeme

Tags: inline RSS, RSS, feed, inline feed, tinymce, shortcode
Requires at least: 3.3
Tested up to: 3.6
Stable tag: trunk

Embed RSS lets users embed an RSS feed into pages

== Description ==

This plugin places an icon in the media row that allows a user to enter an RSS feed url, choose the number of items to display, and choose to display content, author or date for each item. It works via the use of a shortcode in the following format:

[cetsEmbedRSS id='http://en.blog.wordpress.com/feed/' itemcount='2' itemauthor='1' itemdate='1' itemcontent='1']

== Installation ==

Extract the folder and place in the wp-content/plugins folder. Activate the plugin.


== Screenshots ==

1. User View of thickbox window for entering RSS link via media row button


== Changelog ==

3.1 - Major Rewrite.  Drops jQuery UI dialog in favor of ThickBox.  Re-enables support in Posts (removable via filter).  Allows previewing of feed contents inside ThickBox.  Added sample-hooks.php for examples of filter usage

2.3 - Updated JavaScript to work in WordPress 3.4 (Props Viper007)

2.2 - IMPORTANT: Version 2+ no longer supports embedding feeds in posts. You can now only embed feeds in PAGES.  This is because of way too many situations of people accidentally crashing their sites by putting them into infinite loops.  

2.0 - completely rewritten parser to use simplepie

1.6 - removed option to ever allow on non-page content, and removed settings page

1.5 - fixed non-loading CSS. Updated for WP 3.0

1.4 - changed the structure to get rid of the wp-load. Uses different type of pop up box now.

1.3.3 - Tested on 2.9.1.1 and fixed a typo that resulted in an extra closing li

1.3.2 - Updated contributors and fixed minor typos (did not affect functionality).

1.3.1 - updated project structure to work with WordPress.org auto install/update.

