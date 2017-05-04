function playvids( slider ) {
	slider.removeClass( 'pootle-slider-transparent' );
	slider.find( 'video' ).each( function () {
		$( this )[0].play();
	} );
}
( function ( $ ) {
	pootleSliderInit = function( s, props, ratio ) {
		var $t = $( s ),width;
		setTimeout( function() {

			width = Math.min( $t.innerWidth(), window.innerWidth );
			$t.removeClass( 'pootle-slider-transparent' );

			$t.find( '.ppb-row' ).each( function () {

				var
					$row = $( this ),
					hi = 5;

				$row.find( '.ppb-block' ).each( function () {
					if( hi < $( this ).outerHeight() ) {
						hi =  $( this ).outerHeight();
					}
				} );

				hi += 50;
				var cellRatio = hi * 100/width;

				if ( cellRatio > ratio ) {
					ratio = cellRatio;
				}

			} );

			$t.find( '.ppb-row' ).css( 'padding-top', ratio + '%' );
			$t.flexslider( props );
		}, 350 );
	}
} )( jQuery );

jQuery( function ( $ ) {

	var $bd = $( 'body' );
	if ( $bd.hasClass( 'single-pootle-slider' ) ) {
		var $ppb = $( '#pootle-page-builder' ),
			$ppb_sli = $( '.pootle-slider-wrap' );

		$( 'body *' )
			.not( '#ps-bar, #ps-bar *')
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

		$( '#ps-slide-height' ).on('change input', function(){
			var val = this.value;
			$( '#ps-height-css' ).html(
				'#pootle-page-builder .panel-grid .panel-row-style {min-height:' + ( val * 10 ) + 'vw !important;}'
			);
			$( this ).siblings('.value').html( val );
			ppbAjax.pootle_slider_height = val;
		} );

		$ppb_sli.before(
			$( '<div/>' )
				.attr( 'id', 'ps-bar' )
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
	}
} );