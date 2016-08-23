<?php

/**
 * Pootle Slider public class
 * @property string $token Plugin token
 * @property string $url Plugin root dir url
 * @property string $path Plugin root dir path
 * @property string $version Plugin version
 */
class Pootle_Slider_Public{

	/** @var Pootle_Slider_Public Instance */
	private static $_instance = null;


	/** @var int Slider post id */
	protected $id = 0;

	/** @var array Slider animation */
	protected $js_props = array();

	/** @var string Slider duration */
	protected $duration;

	/** @var string Slider height ratio */
	protected $ratio;

	/** @var string Stretch full width */
	protected $full_width = 1;

	private $defaults = array(
		'ratio'			=> 56.25,
		'full_width'    => '',
	);

	/**
	 * Main Pootle Slider Instance
	 * Ensures only one instance of Storefront_Extension_Boilerplate is loaded or can be loaded.
	 * @since 1.0.0
	 * @return Pootle_Slider_Public instance
	 */
	public static function instance() {
		if ( null == self::$_instance ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Constructor function.
	 * @access  private
	 * @since   1.0.0
	 */
	private function __construct() {
		$this->token   =   Pootle_Slider::$token;
		$this->url     =   Pootle_Slider::$url;
		$this->path    =   Pootle_Slider::$path;
		$this->version =   Pootle_Slider::$version;
	} // End __construct()

	/**
	 * Adds front end stylesheet and js
	 * @action wp_enqueue_scripts
	 * @since 1.0.0
	 */
	public function enqueue() {
		$token = $this->token;
		$url = $this->url;

		wp_enqueue_style( $token . '-css', $url . '/assets/front-end.css' );
		wp_enqueue_script( $token . '-js', $url . '/assets/front-end.js', array( 'jquery', ) );
		wp_enqueue_script( $token . '-le-js', $url . '/assets/live-ed.js', array( 'jquery', 'pootle-live-editor' ) );
		wp_enqueue_script( 'ppb-flex-slider', $url . '/assets/jquery.flexslider.min.js', array( 'jquery' ) );

		wp_localize_script( $token . '-js', 'pootle_slider', array(
			'title' => get_the_title(),
		) );
	}

	/**
	 * Adds or modifies the row attributes
	 * @param array $cb Content block settings
	 * @action pootlepb_content_block
	 * @since 1.0.0
	 */
	public function content_block( $cb ) {
		$settings = json_decode( $cb['info']['style'], true );
		if ( ! empty( $settings["{$this->token}-id"]) ) {
			$this->get_properties( $settings );
			Pootle_Page_Builder_Live_Editor_Public::deactivate_le();
			echo Pootle_Page_Builder_Render_Layout::render( $this->id );
		}
	}

	private function get_properties( $settings ) {
		$pre = $this->token . '-';
		foreach ( $settings as $k => $v ) {
			if ( 0 === strpos( $k, $pre ) ) {
				$k = str_replace( $pre, '', $k );
				if ( 0 === strpos( $k, 'js_' ) ) {
					$k = str_replace( 'js_', '', $k );
					if ( $v ) $this->js_props[ $k ] = $v;
				} else {
					$this->$k = $v ? $v : $this->defaults[ $k ];
				}
			}
		}
	}

	/**
	 * Converts pootle slider rows into a slider
	 *
	 * @param string $ppb_html
	 * @param int $post_id
	 * @return string
	 */
	public function render_slider_preview( $ppb_html, $post_id ) {
		if ( 'pootle-slider' != get_post_type( $post_id ) || Pootle_Page_Builder_Live_Editor_Public::is_active() ) {
			return $ppb_html;
		}

		$id = "pootle-slider-$post_id";
		$pb = str_replace(
			array(
				'id="pootle-page-builder"',
				'class="panel-grid ppb-row"',
				'ppb-stretch-full-width',
				'ppb-full-width-no-bg',
			),
			array(
				"class='pootle-slider' id='$id'",
				'class="pootle-slide"',
				'',
				'',
			),
			$ppb_html
		);

		$class = 'pootle-slider-wrap pootle-slider-transparent';
		$class .= $this->full_width ? ' ppb-stretch-full-width' : '';

		return
			$this->style( $id ) .
			"<div class='$class' id='{$id}-wrap'>$pb</div>" .
			$this->script( $id );
	}

	private function script( $id ) {
		$js_props = 'start : playvids,selector  : ".pootle-slider > .pootle-slide"';
		foreach ( $this->js_props as $p => $v ) {
			$js_props .= ",$p : $v";
		}

		return /** @lang html */
<<<SCRIPT
		<script id='$id-script'>
			jQuery( function( $ ) {

				var playvids = function ( slider ) {
					slider.removeClass( 'pootle-slider-transparent' );
					slider.find( 'video' ).each( function () {
						$( this )[0].play();
					} );
				};

				$( '#$id-wrap' ).flexslider( { $js_props } );
			} );
		</script>
SCRIPT;
	}

	private function style( $id ) {
		$ratio = $this->ratio;

		if ( $ratio == 56.25 || ! $ratio ) return '';

		$ratio160p = $ratio * 1.60;
		$ratio250p = $ratio * 2.5;
		return /** @lang html */
<<<STYLE
		<style id="$id-style">
			#$id .pootle-slide .panel-row-style{padding-top: 160%;}
			@media screen and (min-width:475px) {
				#$id .pootle-slide .panel-row-style{padding-top: {$ratio250p}%;}
			}
			@media screen and (min-width:520px) {
				#$id .pootle-slide .panel-row-style{padding-top: {$ratio160p}%;}
			}
			@media screen and (min-width:800px) {
				#$id .pootle-slide .panel-row-style{padding-top: {$ratio}%;}
			}
		</style>
STYLE;
	}
}