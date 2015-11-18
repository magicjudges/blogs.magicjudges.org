<?php
/*
Plugin Name: Lems Public Post Preview Expiration Extender
Description: Extends the Public Post Preview expiration period from 2 days to 28 days. Has no effect if that plugin is not active.
Version: 0.1
Author: Jason Lemahieu
Author URI: http://www.jasonlemahieu.com
License: GPLv2
*/



add_filter( 'ppp_nonce_life', 'lems_nonce_life' );
function lems_nonce_life() {
    return 60 * 60 * 24 * 7 * 2; // 14 days
}