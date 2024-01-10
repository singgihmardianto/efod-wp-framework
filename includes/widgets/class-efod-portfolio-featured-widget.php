<?php
/**
 * Efod custom widget class
 *
 * @package efod-framework
 */

if ( ! class_exists( 'Efod_Portfolio_Featured_Widget' ) ) {
	/**
	 * Efod Portfolio Widget
	 *
	 * @package efod-framework
	 * @author FORTI Digital Studio fortidigitalstudio@gmail.com
	 */
	class Efod_Portfolio_Featured_Widget extends Elementor\Widget_Base {

		/**
		 * Portfolio options
		 *
		 * @var array portfolio_options
		 */
		protected $portfolio_options;

		/**
		 * Construct efod testimonial widget
		 *
		 * @param array $data default parameter elementor widget.
		 * @param array $args default null, default elementor arguments.
		 */
		public function __construct( $data = array(), $args = null ) {
			parent::__construct( $data, $args );

			// Load filter portfolio.
			// Lets assume catalog under a hundred, so it's safe to set the `numberposts` = -1.
			$_portfolio                  = get_posts(
				array(
					'post_type'   => 'portfolio',
					'numberposts' => -1,
				)
			);
			$this->portfolio_options[''] = __( 'Choose Featured Portfolio', 'efod-framework' );
			foreach ( $_portfolio as $portfolio ) {
				$this->portfolio_options[ $portfolio->ID ] = esc_html( $portfolio->post_title );
			}
		}

		/**
		 * Get widget name
		 *
		 * @return string widget name
		 */
		public function get_name() {
			return 'Efod Featured Portfolio';
		}

		/**
		 * Get widget title
		 *
		 * @return string widget title
		 */
		public function get_title() {
			return __( 'Efod Featured Portfolio', 'efod-framework' );
		}

		/**
		 * Get widget icon
		 *
		 * @return string widget icon
		 */
		public function get_icon() {
			return 'fa-solid fa-comment';
		}

		/**
		 * Get widget categories
		 *
		 * @return array widget categories
		 */
		public function get_categories() {
			return array( 'basic' );
		}

		/**
		 * Get script depends
		 *
		 * @return array string js depends
		 */
		public function get_script_depends() {
			return array();
		}

		/**
		 * Get style depends
		 *
		 * @return array sytle depends
		 */
		public function get_style_depends() {
			return array( 'efod-admin-styles' );
		}

		/**
		 * Register control
		 */
		protected function register_controls() {

			$this->start_controls_section(
				'content_section',
				array(
					'label' => __( 'Content', 'efod-framework' ),
					'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'the_portfolio',
				array(
					'label'   => __( 'Choose Portfolio', 'efod-framework' ),
					'type'    => \Elementor\Controls_Manager::SELECT,
					'default' => 'default',
					'options' => $this->portfolio_options,
				)
			);

			$this->add_control(
				'subtitle',
				array(
					'label'       => __( 'Sub Title', 'efod-framework' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'default'     => '',
					'placeholder' => 'Study Case',
				)
			);

			$this->add_responsive_control(
				'featured_thumbnail',
				array(
					'label'           => esc_html__( 'Override Featured Thumbnail', 'efod-framework' ),
					'type'            => \Elementor\Controls_Manager::MEDIA,
					'default_desktop' => array(
						'url' => \Elementor\Utils::get_placeholder_image_src(),
					),
					'default_tablet'  => array(
						'url' => \Elementor\Utils::get_placeholder_image_src(),
					),
					'default_mobile'  => array(
						'url' => \Elementor\Utils::get_placeholder_image_src(),
					),
				)
			);

			$this->add_control(
				'enable_cta',
				array(
					'label'        => esc_html__( 'Enable CTA?', 'efod-framework' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Enabled', 'efod-framework' ),
					'label_off'    => esc_html__( 'Disabled', 'efod-framework' ),
					'return_value' => 'yes',
					'default'      => 'yes',
				)
			);

			$this->add_control(
				'additional_class',
				array(
					'label'       => __( 'Additional Class', 'efod-framework' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'placeholder' => __( 'myclass myother-class', 'efod-framework' ),
				)
			);

			$this->end_controls_section();
		}

		/**
		 * Render widget
		 */
		protected function render() {
			// get settings.
			$settings = $this->get_settings_for_display();

			if ( '' !== $settings['the_portfolio'] ) {
				$settings['the_portfolio'] = get_post( $settings['the_portfolio'] );
			}
			$data = array_merge(
				$settings
			);

			efod_get_views(
				'widgets/single-portfolio',
				$data
			);
		}
	}
}
