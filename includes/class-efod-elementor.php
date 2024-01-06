<?php
/**
 * This file handle efod theme's elementor widget
 *
 * @package efod-framework
 */

if ( ! class_exists( 'Efod_Elementor' ) ) {
	/**
	 * Efod Elementor
	 */
	class Efod_Elementor {

		/**
		 * Widget class array
		 *
		 * @var array $widget_classes contains widget class path
		 */
		protected $widget_classes;

		/**
		 * Load elementor widget
		 */
		public function __construct() {
			$this->widget_classes = array(
				'class-efod-catalog-widget.php',
				'class-efod-portfolio-widget.php',
				'class-efod-portfolio-featured-widget.php',
			);

			$this->init();
		}

		/**
		 * Init object
		 */
		public function init() {
			$this->check_compabilities();
			$this->register_elementor_actions();
		}

		/**
		 * Will call loader to register elementor actions
		 */
		public function register_elementor_actions() {
			add_action( 'elementor/widgets/register', array( $this, 'init_widgets' ) );
		}

		/**
		 * Init widget
		 *
		 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
		 * @return void
		 */
		public function init_widgets( $widgets_manager ) {
			// Include Widget files.
			foreach ( $this->widget_classes as $widget ) {
				require_once __DIR__ . '/widgets/' . $widget;
			}

			// Register widget.
			$widgets_manager->register( new \Efod_Catalog_Widget() );
			$widgets_manager->register( new \Efod_Portfolio_Widget() );
			$widgets_manager->register( new \Efod_Portfolio_Featured_Widget() );
		}

		/**
		 * Will check compabilities with other plugin
		 */
		public function check_compabilities() {

			// Check if Elementor installed and activated.
			if ( ! did_action( 'elementor/loaded' ) ) {
				add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
				return false;
			}

			// Check for required Elementor version.
			if ( ! version_compare( ELEMENTOR_VERSION, MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
				add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
				return false;
			}

			return true;
		}

		/**
		 * Admin notification
		 */
		public function admin_notice_missing_main_plugin() {

			// phpcs:ignore
			if ( isset( $_GET['activate'] ) ) {
				// phpcs:ignore
				unset( $_GET['activate'] );
			}

			$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor */
				esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'efod-framework' ),
				'<strong>' . esc_html__( 'Efod Theme', 'efod-framework' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'efod-framework' ) . '</strong>'
			);

			// phpcs:ignore
			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
		}

		/**
		 * Admin notification for minimum elementor version
		 */
		public function admin_notice_minimum_elementor_version() {

			// phpcs:ignore
			if ( isset( $_GET['activate'] ) ) {
				// phpcs:ignore
				unset( $_GET['activate'] );
			}

			$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
				esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'efod-framework' ),
				'<strong>' . esc_html__( 'Efod Theme', 'efod-framework' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'efod-framework' ) . '</strong>',
				MINIMUM_ELEMENTOR_VERSION
			);

			// phpcs:ignore
			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
		}
	}

}

return new Efod_Elementor();
