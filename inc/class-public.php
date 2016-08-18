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
	protected $full_width;

	private $defaults = array(
		'ratio'			=> '56.25',
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
		wp_enqueue_script( $token . '-js', $url . '/assets/live-editing.js', array( 'jquery', 'pootle-live-editor', ) );
		wp_enqueue_script( 'ppb-flex-slider', $url . '/assets/jquery.flexslider.min.js', array( 'jquery' ) );
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
			$this->render_slider();
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

	private function render_slider() {
		$id = "pootle-slider-{$this->id}";
		Pootle_Page_Builder_Live_Editor_Public::enable_do_nothing();
		$pb = Pootle_Page_Builder_Render_Layout::render( $this->id );
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
			$pb
		);

		$class = 'pootle-slider-wrap';
		$class .= $this->full_width ? ' ppb-stretch-full-width' : '';

		$this->style( $id );
		echo "<div class='$class' id='{$id}-wrap'>$pb</div>";
		$this->script( $id );
	}

	private function script( $id ) {
		?>
		<script>
			jQuery( function( $ ) {

				var playvids = function ( slider ) {
					slider.find( 'video' ).each( function () {
						$( this )[0].play();
					} );
				};

				$( '#<?php echo $id ?>-wrap' ).flexslider( {
					start : playvids,selector  : ".pootle-slider > .pootle-slide"<?php
					foreach ( $this->js_props as $p => $v ) {
						echo ",$p : $v";
					}
					?>
				} );
			} );
		</script>
		<?php
	}

	private function style( $id ) {
		echo <<<STYLE
		<style id="pootle-slider-style">
		#$id .pootle-slide .panel-row-style{padding-top: {$this->ratio}%;}
		</style>
STYLE;
	}
}