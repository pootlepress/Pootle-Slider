<?php
/**
 * Pootle Slider main class
 * @static string $token Plugin token
 * @static string $file Plugin __FILE__
 * @static string $url Plugin root dir url
 * @static string $path Plugin root dir path
 * @static string $version Plugin version
 */
class Pootle_Slider {

	/**
	 * @var 	Pootle_Slider Instance
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * @var     string Token
	 * @access  public
	 * @since   1.0.0
	 */
	public static $token;

	/**
	 * @var     string Version
	 * @access  public
	 * @since   1.0.0
	 */
	public static $version;

	/**
	 * @var 	string Plugin main __FILE__
	 * @access  public
	 * @since 	1.0.0
	 */
	public static $file;

	/**
	 * @var 	string Plugin directory url
	 * @access  public
	 * @since 	1.0.0
	 */
	public static $url;

	/**
	 * @var 	string Plugin directory path
	 * @access  public
	 * @since 	1.0.0
	 */
	public static $path;

	/**
	 * @var 	Pootle_Slider_Admin Instance
	 * @access  public
	 * @since 	1.0.0
	 */
	public $admin;

	/**
	 * @var 	Pootle_Slider_Public Instance
	 * @access  public
	 * @since 	1.0.0
	 */
	public $public;

	/**
	 * Main Pootle Slider Instance
	 *
	 * Ensures only one instance of Storefront_Extension_Boilerplate is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @return Pootle_Slider instance
	 */
	public static function instance( $file = '' ) {
		if ( null == self::$_instance ) {
			self::$_instance = new self( $file );
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Constructor function.
	 * @param string $file __FILE__ of the main plugin
	 * @access  private
	 * @since   1.0.0
	 */
	private function __construct( $file ) {

		self::$token   =   'pootle-slider';
		self::$file    =   $file;
		self::$url     =   plugin_dir_url( $file );
		self::$path    =   plugin_dir_path( $file );
		self::$version =   '1.2.0';

		//Instantiating admin class
		$this->admin = Pootle_Slider_Admin::instance();

		//Instantiating public class
		$this->public = Pootle_Slider_Public::instance();

		add_action( 'init', array( $this, 'init' ) );
	} // End __construct()

	/**
	 * Initiates the plugin
	 * @action init
	 * @since 1.0.0
	 */
	public function init() {

		if ( class_exists( 'Pootle_Page_Builder' ) ) {

			//Initiate admin
			$this->_admin();

			//Initiate public
			$this->_public();

			//Mark this add on as active
			add_filter( 'pootlepb_installed_add_ons', array( $this, 'add_on_active' ) );

		}
	} // End init()

	/**
	 * Initiates admin class and adds admin hooks
	 * @since 1.0.0
	 */
	private function _admin() {
		//Adding the custom post type
		$this->admin->register_post_type();
		add_action( 'admin_menu',						array( $this->admin, 'admin_menu' ) );
		// + New Pootle Slider
		add_action( 'admin_bar_menu',					array( $this->admin, 'admin_bar_menu' ), 999 );
		// Post actions
		add_action( 'post_row_actions',					array( $this->admin, 'post_row_actions' ), 999, 2 );
		// Template for new pootle slider
		add_filter( 'pootlepb_live_page_template',		array( $this->admin, 'new_pootle_slider' ), 10, 3 );
		//Row settings panel fields
		add_filter( 'pootlepb_builder_post_types',		array( $this->admin, 'ppb_posts' ) );
		//Content block panel tabs
		add_filter( 'pootlepb_content_block_tabs',		array( $this->admin, 'content_block_tabs' ) );
		//Live editor content block panel tabs
		add_filter( 'pootlepb_le_content_block_tabs',	array( $this->admin, 'content_block_tabs' ) );
		//Content block panel fields
		add_filter( 'pootlepb_content_block_fields',	array( $this->admin, 'content_block_fields' ) );
		// Adding admin end JS and CSS in /assets folder
		add_action( 'admin_enqueue_scripts',				array( $this->admin, 'enqueue' ) );

	}

	/**
	 * Initiates public class and adds public hooks
	 * @since 1.0.0
	 */
	private function _public() {
		// Adding front end JS and CSS in /assets folder
		add_action( 'wp_enqueue_scripts',				array( $this->public, 'enqueue' ) );
		// Add/Modify content block html attributes
		add_action( 'pootlepb_render_content_block',	array( $this->public, 'content_block' ), 52, 4 );
		// Slider preview after publish
		add_filter( 'pootlepb_render',					array( $this->public, 'render_slider_preview' ), 25, 2 );
		add_filter( 'pootlepb_save_post',					array( $this->public, 'pootlepb_save_post' ), 25, 2 );

	} // End enqueue()

	/**
	 * Marks this add on as active on
	 * @param array $active Active add ons
	 * @return array Active add ons
	 * @since 1.0.0
	 */
	public function add_on_active( $active ) {

		// To allows ppb add ons page to fetch name, description etc.
		$active[ self::$token ] = self::$file;

		return $active;
	}
}