<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://fortidigitalstudio.com
 * @since      1.0.0
 *
 * @package    Efod_Framework
 * @subpackage Efod_Framework/includes
 */

/**
 * The core plugin class.
 *
 * @since      1.0.0
 * @package    Efod_Framework
 * @subpackage Efod_Framework/includes
 * @author     FORTI Digital Studio <fortidigitalstudio@gmail.com>
 */
class Efod_Framework {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Efod_Framework_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name = 'efod-framework';

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Initialize framework object
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_assets' ) );
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'enqueue_elementor_assets' ) );
	}

	/**
	 * Initialize the class.
	 */
	public function init() {
		/*
		 * Make plugin available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_plugin_textdomain(
			'efod-framework',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-efod-methods.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-efod-custom-post.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-efod-elementor.php';
	}

	/**
	 * Initialize required scripts
	 */
	public function enqueue_admin_assets() {
		wp_enqueue_script( 'efod-admin-js', plugin_dir_url( __FILE__ ) . '../public/js/admin.js', false, EFOD_FRAMEWORK_VERSION, true );
		wp_enqueue_style( 'efod-admin-styles', plugin_dir_url( __FILE__ ) . '../public/css/admin.css', false, EFOD_FRAMEWORK_VERSION );
	}

	/**
	 * Initialize public styles & scripts
	 */
	public function enqueue_public_assets() {
		wp_enqueue_script( 'efod-js', plugin_dir_url( __FILE__ ) . '../public/js/public.js', false, EFOD_FRAMEWORK_VERSION, true );
		wp_enqueue_style( 'efod-styles', plugin_dir_url( __FILE__ ) . '../public/css/public.css', false, EFOD_FRAMEWORK_VERSION );
	}

	/**
	 * Initialize required scripts
	 */
	public function enqueue_elementor_assets() {
		wp_enqueue_style( 'efod-admin-styles', plugin_dir_url( __FILE__ ) . '../public/css/admin.css', false, EFOD_FRAMEWORK_VERSION );
	}
}

return new Efod_Framework();
