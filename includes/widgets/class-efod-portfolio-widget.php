<?php
/**
 * Efod custom widget class
 *
 * @package efod-framework
 */

if ( ! class_exists( 'Efod_Portfolio_Widget' ) ) {
	/**
	 * Efod Portfolio Widget
	 *
	 * @package efod-framework
	 * @author FORTI Digital Studio fortidigitalstudio@gmail.com
	 */
	class Efod_Portfolio_Widget extends Elementor\Widget_Base {

		/**
		 * Portfolio Category
		 *
		 * @var cat_options
		 */
		protected $cat_options;

		/**
		 * The query args
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
			 * Construct category option
			 */
			$portfolio_cat_arr = get_terms(
				array(
					'taxonomy'   => 'portfolio_category',
					'hide_empty' => false,
				)
			);

			$this->cat_options['default'] = esc_html__( 'Show All', 'efod-framework' );

			foreach ( $portfolio_cat_arr as $cat ) {
				$this->cat_options[ $cat->slug ] = esc_html( $cat->name );
			}

			/**
			 * Construct portfolio query args
			 */
			$this->query_args = array(
				'post_type'     => 'portfolio',
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
			return 'Efod Portfolio';
		}

		/**
		 * Get widget title
		 *
		 * @return string widget title
		 */
		public function get_title() {
			return __( 'Efod Portfolio', 'efod-framework' );
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
					'default'     => 'Our Works',
					'placeholder' => 'Porfolio Title',
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
					'name'     => 'portfolio_title_typography',
					'selector' => '{{WRAPPER}} .ef-portfolio > .ef-title, .ef-portfolio > .ef-description',
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
					'label'      => __( 'Portfolios To Show', 'efod-framework' ),
					'type'       => \Elementor\Controls_Manager::NUMBER,
					'input_type' => 'number',
					'default'    => 3,
				)
			);

			$this->add_control(
				'portfolio_widget_filter',
				array(
					'label'   => __( 'Filter Category', 'efod-framework' ),
					'type'    => \Elementor\Controls_Manager::SELECT,
					'default' => 'default',
					'options' => $this->cat_options,
				)
			);

			$this->add_control(
				'pagination_type',
				array(
					'label'   => __( 'Pagination Type', 'efod-framework' ),
					'type'    => \Elementor\Controls_Manager::SELECT,
					'default' => 'load_button',
					'options' => array(
						'load_button' => __( 'Default Load Button', 'efod-framework' ),
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
						'grid-3'    => __( 'Grid 3 Column', 'efod-framework' ),
						'grid-4'    => __( 'Grid 4 Column', 'efod-framework' ),
						'masonry-3' => __( 'Masonry 3 column', 'efod-framework' ),
						'masonry-4' => __( 'Masonry 4 column', 'efod-framework' ),
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
			// get settings.
			$settings = $this->get_settings_for_display();

			$this->query_args['posts_per_page'] = $settings['data_counts'];

			if ( 'default' !== $settings['portfolio_widget_filter'] ) {
				// phpcs:ignore
				$this->query_args['tax_query'] = array(
					array(
						'taxonomy' => 'portfolio_category',
						'field'    => 'slug',
						'terms'    => $settings['portfolio_widget_filter'],
					),
				);
			}

			get_template_part(
				'loop',
				'portfolio',
				array(
					'query_filter' => $this->query_args,
					'settings'     => $settings,
				)
			);
		}
	}
}