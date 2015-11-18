<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

remove_role( 'translator' );

delete_option( 'wp_translators_version' );