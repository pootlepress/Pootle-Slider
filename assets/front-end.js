/**
 * Plugin front end scripts
 *
 * @package Pootle_Slider
 * @version 1.0.0
 */
jQuery( function ( $ ) {
	var $bd = $( 'body' );
	if ( $bd.hasClass( 'single-pootle-slider' ) ) {
		var $ppb = $( '#pootle-page-builder' );
		$( 'body *' )
			.not( '#pootlepb-modules-wrap, #pootlepb-modules-wrap *' )
			.not( '#wpadminbar, #wpadminbar *' )
			.not( '.ppb-widget, .ppb-widget *' )
			.not( '.pootlepb-dialog, .pootlepb-dialog *' )
			.not( '#pootle-page-builder, #pootle-page-builder *' )
			.not( $ppb.parents() )
			.hide();
		$bd.show();

		$ppb.before(
			$( '<div/>' )
				.html(
					'<h1 style="margin:0">Welcome to the Pootle Slider live designer.</h1>' +
					'<h2 style="margin:0">Start by dragging the \'new slide\' module over the + new icon.</h2>'
				)
				.css( {
					'text-align': 'center',
					'background-color': '#ccc',
					margin: '0 -999px',
					padding: '25px 999px 16px'
				} )
		);
	}
	window.ppbModules.pootleSliderSlide = function ( $t, ed ) {
		console.log( $t );
		var $tlbr = $t.closest( '.panel-grid' ).find( '.ppb-edit-row' );
		ed.execCommand( 'mceInsertContent', false, '<h2 style="color: #fff;">Write your cool headline here</h2>' );
		$tlbr.find( '.ui-sortable-handle' ).click();
		ppbData.grids[ppbRowI].style.full_width = true;
		ppbData.grids[ppbRowI].style.background_toggle = '.bg_image';
		ppbData.grids[ppbRowI].style.row_height = '500';
	}
} );