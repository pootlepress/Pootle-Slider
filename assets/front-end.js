/**
 * Plugin front end scripts
 *
 * @package Pootle_Slider
 * @version 1.0.0
 */
jQuery(function ($) {
	if ( $('body').hasClass('single-pootle-slider') ) {
		$( 'body *' )
			.not( '#pootlepb-modules-wrap, #pootlepb-modules-wrap *' )
			.not( '#wpadminbar, #wpadminbar *' )
			.not( '.ppb-widget, .ppb-widget *' )
			.not( '.pootlepb-dialog, .pootlepb-dialog *' )
			.not( '#pootle-page-builder, #pootle-page-builder *' )
			.not( $( '#pootle-page-builder' ).parents() )
			.hide();
	}
});