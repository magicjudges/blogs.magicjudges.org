=== Custom Menu ===
Contributors: thiudis
Donate link: http://www.evolonix.com/donate/
Tags: custom menu
Requires at least: 2.0.2
Tested up to: 3.3
Stable tag: 1.8

This plugin allows you to display a custom menu that you've created in your theme's "Menus" section in a post or page.

== Description ==

This plugin allows you to display a custom menu that you've created in your theme's "Menus" section in a post or page. Use [menu name="Menu Name"] in your post or page to insert the custom menu. The "name" attribute is required. Since version 1.2, you can now provide a "title" attribute to add a header title to your custom menu (e.g. [menu name="Menu Name" title="My Menu"].)

== Installation ==

1. Upload `custom-menu` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `[menu name="Menu Name"]` in your posts or pages wherever you want the menu to display.
1. Optionally, provide a "title" attribute to add a header title to the custom menu.

== Frequently Asked Questions ==



== Screenshots ==



== Changelog ==

= 1.8 =
* Fixed a problem where the plugin's stylesheet was being included for admin pages.

= 1.7 =
* Made changes to be compatible with WordPress version 3.3.

= 1.6 =
* Removed anonymous function for PHP versions below 5.3.

= 1.5 =
* Fixed a bug where the menu was always being placed at the top of the content, regardless where the short code was placed.

= 1.4 =
* Fixed a bug where the options page was being added to the admin bar for all users, regardless if they had access to it or not.

= 1.3 =
* Added references to the author's website.
* Changed the code to be object-oriented.

= 1.2 =
* Majorly simplified the plugin's code.
* Requiring a valid menu name and not displaying an output if either the "name" attribute isn't specified or a custom menu with the provided name cannot be found.
* You can now provide a "title" attribute to add a header title to your custom menu (e.g. [menu name="Menu Name" title="My Menu"].)

= 1.1 =
* Changed the plugin to use the name="Menu Name" attribute instead of just specifying the name in the brackets.

== Upgrade Notice ==

= 1.6 =
Upgrade if you are experiencing an unexpected T_FUNCTION error, which displays when your PHP version is below 5.3.

= 1.3 =
No new functionality in this version.

= 1.2 =
You should upgrade to version 1.2 so that you can use the "title" attribute and to have it better find and display your menus.

= 1.1 =
You should upgrade from 1.0 so that it is easier to understand what menu name you are specifying. Before, where you would have specified [menu Menu Name] you now write it like [menu name="Menu Name"]. Much more cleaner and easier to read.

== Arbitrary section ==

