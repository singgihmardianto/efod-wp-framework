<?php
/**
 * Efod theme custom widget class
 *
 * @package efod-theme
 */

if ( ! class_exists( 'Efod_Testimonial_Widget' ) ) {
	/**
	 * Efod Testimonial Widget
	 *
	 * @package efod-theme
	 * @author FORTI Digital Studio fortidigitalstudio@gmail.com
	 */
	class Efod_Testimonial_Widget extends \Elementor\Widget_Base {

		/**
		 * Array of query arguments
		 *
		 * @var query_args
		 */
		protected $query_args;

		/**
		 * Construct efod testimonial widget
		 *
		 * @param array $data default parameter elementor widget.
		 * @param array $args default null, default elementor arguments.
		 */
		public function __construct( $data = array(), $args = null ) {
			parent::__construct( $data, $args );

			/**
			 * Construct testimonial query args
			 */
			$this->query_args = array(
				'post_type'     => 'testimonial',
				'post_status'   => 'publish',
				'post_per_page' => -1,
				'orderby'       => 'id',
				'order'         => 'DESC',
			);
		}

		/**
		 * Get widget name
		 *
		 * @return string widget name
		 */
		public function get_name() {
			return 'Efod Testimonial';
		}

		/**
		 * Get widget title
		 *
		 * @return string widget title
		 */
		public function get_title() {
			return __( 'Efod Testimonial', 'efod-framework' );
		}

		/**
		 * Get widget icon
		 *
		 * @return string widget icon
		 */
		public function get_icon() {
			return 'fa fa-code';
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
			return array();
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
				'title',
				array(
					'label'       => __( 'Title', 'efod-framework' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'default'     => 'What They Say',
					'placeholder' => 'Testimonial Title',
				)
			);

			$this->add_control(
				'description',
				array(
					'label'       => __( 'Description', 'efod-framework' ),
					'type'        => \Elementor\Controls_Manager::TEXTAREA,
					'default'     => '',
					'placeholder' => 'Widget description',
				)
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				array(
					'name'     => 'testimonial_title_typography',
					'selector' => '{{WRAPPER}} .ef-testimonial > .ef-title, .ef-testimonial > .ef-description',
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'data_section',
				array(
					'label' => __( 'Data', 'efod-framework' ),
					'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'data_counts',
				array(
					'label'      => __( 'Testimonial To Show', 'efod-framework' ),
					'type'       => \Elementor\Controls_Manager::NUMBER,
					'input_type' => 'number',
					'default'    => 3,
				)
			);

			$this->add_control(
				'pagination_type',
				array(
					'label'   => __( 'Pagination Type', 'efod-framework' ),
					'type'    => \Elementor\Controls_Manager::SELECT,
					'default' => 'load_button',
					'options' => array(
						'load_button' => __( 'Load Button', 'efod-framework' ),
						'slider'      => __( 'Slide (Carousel)', 'efod-framework' ),
						'default'     => __( 'Default Pagination ', 'efod-framework' ),
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'style_section',
				array(
					'label' => __( 'Style', 'efod-framework' ),
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_control(
				'layout_type',
				array(
					'label'   => __( 'Layout Type', 'efod-framework' ),
					'type'    => \Elementor\Controls_Manager::SELECT,
					'default' => 'grid-3',
					'options' => array(
						'grid-3' => __( 'Grid 3 Column', 'efod-framework' ),
						'grid-4' => __( 'Grid 4 Column', 'efod-framework' ),
					),
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
			// get widget settings.
			$settings = $this->get_settings_for_display();

			$this->query_args['posts_per_page'] = $settings['data_counts'];

			get_template_part(
				'loop',
				'testimonial',
				array(
					'query_filter' => $this->query_args,
					'settings'     => $settings,
				)
			);
		}
	}
}
