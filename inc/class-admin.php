<?php
/**
 * Pootle Slider Admin class
 * @property string token Plugin token
 * @property string $url Plugin root dir url
 * @property string $path Plugin root dir path
 * @property string $version Plugin version
 */
class Pootle_Slider_Admin{

	/**
	 * @var 	Pootle_Slider_Admin Instance
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Main Pootle Slider Instance
	 * Ensures only one instance of Storefront_Extension_Boilerplate is loaded or can be loaded.
	 * @return Pootle_Slider_Admin instance
	 * @since 	1.0.0
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
	 * @since 	1.0.0
	 */
	private function __construct() {
		$this->token   =   Pootle_Slider::$token;
		$this->url     =   Pootle_Slider::$url;
		$this->path    =   Pootle_Slider::$path;
		$this->version =   Pootle_Slider::$version;
	} // End __construct()

	public function enqueue() {
		if ( filter_input( INPUT_GET, 'post_type' ) === 'pootle-slider' ) {
			wp_enqueue_style( $this->token . '-js', $this->url . '/assets/admin.css', null, $this->version );
		}
	}

	/**
	 * Creates pootle slider post type
	 * @action init
	 * @since 	1.0.0
	 */
	public function register_post_type() {
		$labels = array(
			'name'                  => _x( 'Pootle Sliders', 'Pootle Slider General Name', 'pootle-slider' ),
			'singular_name'         => _x( 'Pootle Slider', 'Pootle Slider Singular Name', 'pootle-slider' ),
			'menu_name'             => __( 'Pootle Sliders', 'pootle-slider' ),
			'name_admin_bar'        => __( 'Pootle Slider', 'pootle-slider' ),
			'archives'              => __( 'Pootle Slider Archives', 'pootle-slider' ),
			'parent_item_colon'     => __( 'Parent Pootle Slider:', 'pootle-slider' ),
			'all_items'             => __( 'All Pootle Sliders', 'pootle-slider' ),
			'add_new_item'          => __( 'Add New Pootle Slider', 'pootle-slider' ),
			'add_new'               => __( 'Add New', 'pootle-slider' ),
			'new_item'              => __( 'New Pootle Slider', 'pootle-slider' ),
			'edit_item'             => __( 'Edit Pootle Slider', 'pootle-slider' ),
			'update_item'           => __( 'Update Pootle Slider', 'pootle-slider' ),
			'view_item'             => __( 'View Pootle Slider', 'pootle-slider' ),
			'search_items'          => __( 'Search Pootle Slider', 'pootle-slider' ),
			'not_found'             => __( 'Not found', 'pootle-slider' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'pootle-slider' ),
			'featured_image'        => __( 'Featured Image', 'pootle-slider' ),
			'set_featured_image'    => __( 'Set featured image', 'pootle-slider' ),
			'remove_featured_image' => __( 'Remove featured image', 'pootle-slider' ),
			'use_featured_image'    => __( 'Use as featured image', 'pootle-slider' ),
			'insert_into_item'      => __( 'Insert into Pootle Slider', 'pootle-slider' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Pootle Slider', 'pootle-slider' ),
			'items_list'            => __( 'Pootle Sliders list', 'pootle-slider' ),
			'items_list_navigation' => __( 'Pootle Sliders list navigation', 'pootle-slider' ),
			'filter_items_list'     => __( 'Filter Pootle Sliders list', 'pootle-slider' ),
		);
		$args = array(
			'label'               => __( 'Pootle Slider', 'pootle-slider' ),
			'description'         => __( 'Slider for using in Pootle Pagebuilder', 'pootle-slider' ),
			'labels'              => $labels,
			'supports'            => array(),
			'hierarchical'        => false,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'menu_position'       => 20.59,
			'menu_icon'           => 'dashicons-images-alt2',
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);
		register_post_type( 'pootle-slider', $args );
	}

	/**
	 * Adds new slider link to admon bar
	 * @param WP_Admin_Bar $admin_bar
	 */
	function admin_bar_menu( $admin_bar ) {
		$new_live_page_url = admin_url( 'admin-ajax.php' );
		$new_live_page_url = wp_nonce_url( $new_live_page_url, 'ppb-new-live-post', 'ppbLiveEditor' ) . '&action=pootlepb_live_page';

		$admin_bar->add_menu( array(
			'parent' => 'new-content',
			'id'     => 'ppb-new-live-pootle-slider',
			'title'  => 'Pootle Slider',
			'href'   => $new_live_page_url . '&post_type=pootle-slider'
		) );

	}

	/**
	 * Adds new slider link to admon bar
	 */
	function admin_menu() {
		add_submenu_page(
			'page_builder',
			'Pootle Sliders',
			'Pootle Sliders',
			'manage_options',
			'edit.php?post_type=pootle-slider'
		);
	}

	/**
	 * Filters the row actions
	 * @filter post_row_actions
	 */
	public function post_row_actions( $actions, $post ) {

		if( 'pootle-slider' == $post->post_type ) {
			if ( ! empty( $actions['edit'] ) ) {
				$nonce_url = wp_nonce_url( get_the_permalink( $post->ID ), 'ppb-live-edit-nonce', 'ppbLiveEditor' );

				$actions['edit'] = '<a href="' . $nonce_url . '" aria-label="Edit Slider">Live edit</a>';
				unset( $actions['inline hide-if-no-js'] );
			}
		}
		return $actions;
	}

	/**
	 * Adds row settings panel fields
	 * @param array $fields Fields to output in row settings panel
	 * @return array Tabs
	 * @filter pootlepb_row_settings_fields
	 * @since 	1.0.0
	 */
	public function ppb_posts( $posts ) {
		$posts[] = 'pootle-slider';
		return $posts;
	}

	/**
	 * Adds row settings panel fields
	 * @param array $fields Fields to output in row settings panel
	 * @return array Tabs
	 * @filter pootlepb_live_page_template
	 * @since 	1.0.0
	 */
	public function new_pootle_slider( $ppble_new_live_page, $id, $post_type ) {
		if ( 'pootle-slider' == $post_type ) {
			$ppble_new_live_page['grids'] = array();
			$ppble_new_live_page['grid_cells'] = array();
			$ppble_new_live_page['widgets'] = array();
			update_post_meta( $id, 'pootlepb-new', '1' );
		}
		return $ppble_new_live_page;
	}

	/**
	 * Adds editor panel tab
	 * @param array $tabs The array of tabs
	 * @return array Tabs
	 * @filter pootlepb_content_block_tabs
	 * @since 	1.0.0
	 */
	public function content_block_tabs( $tabs ) {
		$tabs[ $this->token ] = array(
			'label' => 'Slider',
			'priority' => 7,
		);
		return $tabs;
	}

	/**
	 * Adds content block panel fields
	 * @param array $fields Fields to output in content block panel
	 * @return array Tabs
	 * @filter pootlepb_content_block_fields
	 * @since 	1.0.0
	 */
	public function content_block_fields( $fields ) {

		$sliders = get_posts( array(
			'post_type' => 'pootle-slider',
			'numberposts' => 25,
			'post_status' => 'any',
			) );

		$options = array( '' => 'Please choose...' );

		foreach ( $sliders as $s ) {
			$options[ $s->ID ] = $s->post_title;
		}

		$fields[ "$this->token-id" ] = array(
			'name' => 'Choose slider',
			'type' => 'select',
			'priority' => 5,
			'options'  => $options,
			'tab' => $this->token,
		);

		$fields[ "$this->token-full_width" ] = array(
			'name' => 'Make slider go full width',
			'type' => 'checkbox',
			'priority' => 8,
			'tab' => $this->token,
		);

		$fields[ "$this->token-js_pauseOnHover" ] = array(
			'name' => 'Pause the slider on mouse over',
			'type' => 'checkbox',
			'priority' => 8,
			'tab' => $this->token,
		);

		$fields[ "$this->token-js_animation"] = array(
			'name' => 'Animation',
			'type' => 'select',
			'priority' => 12,
			'options'  => array(
				'' => 'Fade',
				"'slide'" => 'Slide',
//				"slide', direction: 'vertical" => 'Slide Vertical',
			),
			'tab' => $this->token,
		);
		$fields[ "$this->token-js_controlNav"] = array(
			'name' => 'Navigation',
			'type' => 'select',
			'priority' => 12,
			'options'  => array(
				'' => 'Arrows and slide bullets',
				'false' => 'Arrows only',
				'false,directionNav:false' => 'None',
			),
			'tab' => $this->token,
		);
		$fields[ "$this->token-js_slideshowSpeed"] =  array(
			'name' => 'Sliding speed',
			'type' => 'select',
			'options'  => array(
				'10600' => 'Very slow',
				'8800' => 'Slow',
				'' => 'Normal',
				'4300' => 'Fast',
				'2500' => 'Very Fast',
			),
			'priority' => 16,
			'tab' => $this->token,
		);
		$fields[ "$this->token-ratio"] =  array(
			'name' => 'Height as a percentage of width',
			'type' => 'select',
			'options'  => array(
				'' => 'Same as design',
				'75' => '4:3 Standard Definition',
				'56' => '16:9 High Definition',
				'33.33' => '21:9 Cinematic',
			),
			'priority' => 20,
			'tab' => $this->token,
		);
		return $fields;
	}
}