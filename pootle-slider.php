<?php
/*
Plugin Name: Pootle Slider
Plugin URI: http://pootlepress.com/
Description: The amazing Pootle slider
Author: pootlepress
Version: 1.0.0
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

register_activation_hook( __FILE__, function () {
	Pootle_Slider::instance( __FILE__ )->admin->register_post_type();
	flush_rewrite_rules();
} );


/** Addon update API */
add_action( 'plugins_loaded', 'Pootle_Slider_api_init' );

/**
 * Instantiates Pootle_Page_Builder_Addon_Manager with current add-on data
 * @action plugins_loaded
 */
function Pootle_Slider_api_init() {
	//Return if POOTLEPB_DIR not defined
	if ( ! defined( 'POOTLEPB_DIR' ) ) { return; }
	/** Including PootlePress_API_Manager class */
	require_once POOTLEPB_DIR . 'inc/addon-manager/class-manager.php';
	/** Instantiating PootlePress_API_Manager */
	new Pootle_Page_Builder_Addon_Manager(
		Pootle_Slider::$token,
		'Pootle Slider',
		Pootle_Slider::$version,
		Pootle_Slider::$file,
		Pootle_Slider::$token
	);
}
