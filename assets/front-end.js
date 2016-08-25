jQuery( function ( $ ) {
	var $bd = $( 'body' );
	if ( $bd.hasClass( 'single-pootle-slider' ) ) {
		var $ppb = $( '#pootle-page-builder' ),
			$ppb_sli = $( '.pootle-slider-wrap' );

		$( 'body *' )
			.not( '#pootlepb-modules-wrap, #pootlepb-modules-wrap *' )
			.not( '#wpadminbar, #wpadminbar *' )
			.not( '.ppb-widget, .ppb-widget *' )
			.not( '.pootlepb-dialog, .pootlepb-dialog *' )
			.not( '.pootle-slider-wrap, .pootle-slider-wrap *' )
			.not( '#pootle-page-builder, #pootle-page-builder *' )
			.not( $ppb_sli.parents() )
			.not( $ppb.parents().css( {
				margin: 'auto',
				padding: 'auto',
				width: 'auto',
				float: 'none'
			} ) )
			.hide();
		$bd.show();

		$ppb_sli.before(
			$( '<div/>' )
				.html(
					'<small>Previewing slider</small>' +
					'<h2 style="margin:0">' + pootle_slider.title + '</h2>' +
					"<h3 style='font-weight: 400;margin:0'>Now you can use this slider in your pages or your posts</h3>"
				)
				.css( {
					'text-align': 'center',
					'background-color': '#eee',
					margin: '0 -999px',
					padding: '25px 999px 16px'
				} )
		);
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
	}
} );