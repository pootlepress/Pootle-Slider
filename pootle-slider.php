<?php
/*
Plugin Name: Pootle Slider
Plugin URI: http://pootlepress.com/
Description: The amazing Pootle slider
Author: pootlepress
Version: 1.2.0
Author URI: http://pootlepress.com/
@developer shramee <shramee.srivastav@gmail.com>
*/
/** Plugin admin class */
require 'inc/class-admin.php';
/** Plugin public class */
require 'inc/class-public.php';
/** Including Main Plugin class */
require 'class-pootle-slider.php';
/** Intantiating main plugin class */
Pootle_Slider::instance( __FILE__ );

/**
 * Flush rewrite rules on activation
 */
register_activation_hook( __FILE__, function () {
	Pootle_Slider::instance( __FILE__ )->admin->register_post_type();
	flush_rewrite_rules();
} );
