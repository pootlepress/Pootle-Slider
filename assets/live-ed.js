/**
 * Plugin front end scripts
 *
 * @package Pootle_Slider
 * @version 1.0.0
 */
jQuery( function ( $ ) {
	var $bd = $( 'body' );
	if ( $bd.hasClass( 'single-pootle-slider' ) ) {

		var $setTitleDialog = $( '#pootlepb-set-title' );

		if ( $setTitleDialog.length ) {
			$setTitleDialog.ppbDialog( 'option', 'title', 'Set title of the slider' )
			$setTitleDialog.find( 'p' ).html( 'Please set the title for the slider' );
		}
	}

	window.ppbModules.pootleSliderSlide = function ( $t, ed ) {
		console.log( $t );
		var $tlbr = $t.closest( '.panel-grid' ).find( '.ppb-edit-row' );
		ed.execCommand( 'mceInsertContent', false, '<h1 style="color: #fff;">Write your cool headline here</h1>' );
		$tlbr.find( '.ui-sortable-handle' ).click();
		ppbData.grids[ppbRowI].style.full_width = true;
		ppbData.grids[ppbRowI].style.background_toggle = '.bg_image';
		ppbData.grids[ppbRowI].style.row_height = '500';
	}
} );