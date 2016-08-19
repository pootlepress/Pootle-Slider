jQuery( function ( $ ) {
	var $bd = $( 'body' );
	if ( $bd.hasClass( 'single-pootle-slider' ) ) {
		var $ppb = $( '#pootle-page-builder' );

		$( 'body *' )
			.not( '#pootlepb-modules-wrap, #pootlepb-modules-wrap *' )
			.not( '#wpadminbar, #wpadminbar *' )
			.not( '.ppb-widget, .ppb-widget *' )
			.not( '.pootlepb-dialog, .pootlepb-dialog *' )
			.not( '.pootle-slider-wrap, .pootle-slider-wrap *' )
			.not( '#pootle-page-builder, #pootle-page-builder *' )
			.not( $('.pootle-slider-wrap').parents() )
			.not( $ppb.parents() )
			.hide();
		$bd.show();

		$ppb.before(
			$( '<div/>' )
				.html(
					'<h1 style="margin:0">Welcome to the Pootle Slider live designer.</h1>' +
					"<h2 style='margin:0'>Start by dragging the 'New Slide' module over the + new icon.</h2>"
				)
				.css( {
					'text-align': 'center',
					'background-color': '#eee',
					margin: '0 -999px',
					padding: '25px 999px 16px'
				} )
		);
	};
} );