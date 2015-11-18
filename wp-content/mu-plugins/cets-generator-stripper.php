<?php
/******************************************************************************************************************
 
Plugin Name: Generator Stripper
Plugin URI:
Description: Removes the <generator> tag from the head in an attempt to reduce WP-targeted attacks
Version:  0.1
Author: Jason Lemahieu

Copyright:

    Copyright 20012 Board of Regents of the University of Wisconsin System
	Cooperative Extension Technology Services
	University of Wisconsin-Extension
*******************************************************************************************************************/

remove_action('wp_head','wp_generator');