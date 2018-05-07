<?php

/**
 * Pootle Slider public class
 * @property string $token Plugin token
 * @property string $url Plugin root dir url
 * @property string $path Plugin root dir path
 * @property string $version Plugin version
 */
class Pootle_Slider_Public {

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
		'ratio'      => '',
		'full_width' => '',
	);

	/** @var bool Whether or not live editor bar has been rendered */
	private $live_editor_bar_rendered = false;

	/**
	 * Constructor function.
	 * @access  private
	 * @since   1.0.0
	 */
	private function __construct() {
		$this->token   = Pootle_Slider::$token;
		$this->url     = Pootle_Slider::$url;
		$this->path    = Pootle_Slider::$path;
		$this->version = Pootle_Slider::$version;
	} // End instance()

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
	} // End __construct()

	/**
	 * Adds front end stylesheet and js
	 * @action wp_enqueue_scripts
	 * @since 1.0.0
	 */
	public function enqueue() {
		$token = $this->token;
		$url   = $this->url;

		wp_enqueue_style( $token . '-css', $url . '/assets/front-end.css', array(), $this->version );
		wp_enqueue_script( $token . '-js', $url . '/assets/front-end.js', array( 'jquery', 'flexslider', ), $this->version );
		wp_enqueue_script( $token . '-le-js', $url . '/assets/live-ed.js', array(
			'jquery',
			'pootle-live-editor',
		), $this->version );
		wp_enqueue_script( 'flexslider', $url . '/assets/jquery.flexslider.min.js', array( 'jquery' ) );

		wp_localize_script( $token . '-js', 'pootle_slider', array(
			'title' => get_the_title(),
		) );
	}

	/**
	 * Adds or modifies the row attributes
	 *
	 * @param array $cb Content block settings
	 *
	 * @action pootlepb_content_block
	 * @since 1.0.0
	 */
	public function content_block( $cb ) {
		$settings = json_decode( $cb['info']['style'], true );
		if ( ! empty( $settings["{$this->token}-id"] ) ) {
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
					if ( $v ) {
						$this->js_props[ $k ] = $v;
					}
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
	 *
	 * @return string
	 */
	public function render_slider_preview( $ppb_html, $post_id ) {
		if ( 'pootle-slider' != get_post_type( $post_id ) || doing_action('wp_head' ) || ! ( did_action( 'wp_head' ) || DOING_AJAX ) ) {
			return $ppb_html;
		} elseif ( Pootle_Page_Builder_Live_Editor_Public::is_active() ) {
			return $this->prependLiveEditorBar( $post_id ) . $ppb_html;
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
		$ratio = $this->get_ratio( $post_id );
		return
			"<div class='$class' id='{$id}-wrap' data-ratio='{$ratio}'>$pb" .
			$this->maybe_show_edit_link( $post_id ) .
			'</div>' .
			$this->script( $id, $post_id );
	}

	function pootlepb_save_post( $data, $post_id ) {
		if ( ! empty( $_POST['pootle_slider_height'] ) ) {
			update_post_meta( $post_id, 'pootle-slider-height', $_POST['pootle_slider_height'] );
		}
	}

	private function prependLiveEditorBar( $post_id ) {

		if ( $this->live_editor_bar_rendered ) {
			return;
		}

		$this->live_editor_bar_rendered = 1;

		$height = get_post_meta( $post_id, 'pootle-slider-height', true );
		$height = $height ? $height : 4.3;
		?>
		<div id="ps-bar"><span class="ps-bar-head">Pootle Slider live designer</span>
			<div class="right">
				<div class="height">
					<i class="dashicons dashicons-leftright"></i>Row height:
					<input id="ps-slide-height" type="range" min="1" max="10" step="0.5" value="<?php echo $height ?>">
					<span class="value"><?php echo $height ?></span>
				</div>
			</div>
		</div>
		<style id="ps-height-css">
			#pootle-page-builder .panel-grid .panel-row-style {
				min-height: <?php echo $height * 10 ?>vw !important;
			}
		</style>
		<?php
	}

	private function get_ratio( $post_id ) {
		$ratio = $this->ratio;

		if ( ! $ratio ) {
			$ratio = get_post_meta( $post_id, 'pootle-slider-height', true );
			$ratio = $ratio ? $ratio * 10 : 56.25;
		}

		return $ratio;
	}

	private function script( $id, $post_id ) {

		$js_props = '{start : playvids,selector  : ".pootle-slider > .pootle-slide"';
		foreach ( $this->js_props as $p => $v ) {
			$js_props .= ",$p:$v";
		}
		$js_props .= '}';
		return "<script id='$id-script'>window.pootleSliderInit( '#$id-wrap', $js_props );</script>";
	}

	/**

	 * Adds edit slider link on live edit screen when user is logged in
	 * @param int $post_id Slider id
	 * @return string Edit link html
	 */
	private function maybe_show_edit_link( $post_id ) {

		if ( is_user_logged_in() && ( filter_input( INPUT_GET, 'ppbLiveEditor' ) || filter_input( INPUT_POST, 'action' ) == "pootlepb_live_editor" ) ) {

			$nonce_url = wp_nonce_url( get_the_permalink( $post_id ), 'ppb-live-edit-nonce', 'ppbLiveEditor' );

			return '<a target="_blank" class="edit-pootle-slider" href="' . $nonce_url . '">Edit slider</a>';

		}

		return '';
	}
}