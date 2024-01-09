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
		 * Filter by Catalog Category
		 *
		 * @var array cat_options
		 */
		protected $cat_options;

		/**
		 * Layout options
		 *
		 * @var array layout_options
		 */
		protected $layout_options;

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
			 * Construct portfolio query args
			 */
			$this->query_args = array(
				'post_type'     => 'portfolio',
				'post_status'   => 'publish',
				'post_per_page' => -1,
				'orderby'       => 'id',
				'order'         => 'DESC',
			);

			// Load filter portfolio by a catalog (post).
			// Lets assume catalog under a hundred, so it's safe to set the `numberposts` = -1.
			$_catalogs                    = get_posts(
				array(
					'post_type'   => 'catalog',
					'numberposts' => -1,
				)
			);
			$this->cat_options['default'] = __( 'Choose Catalog', 'efod-framework' );
			foreach ( $_catalogs as $catalog ) {
				$this->cat_options[ $catalog->ID ] = esc_html( $catalog->post_title );
			}

			// Load layout options.
			$this->layout_options = array(
				'grid-3'      => __( 'Grid 3 Column', 'efod-framework' ),
				'grid-4'      => __( 'Grid 4 Column', 'efod-framework' ),
				'masonry-3'   => __( 'Masonry 3 column', 'efod-framework' ),
				'masonry-4'   => __( 'Masonry 4 column', 'efod-framework' ),
				'single-icon' => __( 'Single Line Icon', 'efod-framework' ),
			);
		}

		/**
		 * Get widget name
		 *
		 * @return string widget name
		 */
		public function get_name() {
			return 'Efod Portfolio List';
		}

		/**
		 * Get widget title
		 *
		 * @return string widget title
		 */
		public function get_title() {
			return __( 'Efod Portfolio List', 'efod-framework' );
		}

		/**
		 * Get widget icon
		 *
		 * @return string widget icon
		 */
		public function get_icon() {
			return 'fa-solid fa-comments';
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
					'label'   => __( 'Filter By Catalog', 'efod-framework' ),
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

			$this->add_responsive_control(
				'layout_type',
				array(
					'label'           => __( 'Layout Type', 'efod-framework' ),
					'type'            => \Elementor\Controls_Manager::SELECT,
					'options'         => $this->layout_options,
					'desktop_default' => 'grid-3',
					'tablet_default'  => 'grid-3',
					'mobile_default'  => 'grid-3',
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

			$determined_layout_type = efod_determine_responsive_class(
				array_keys( $this->layout_options ),
				$settings['layout_type'],
				isset( $settings['layout_type_tablet'] ) ? $settings['layout_type_tablet'] : 'grid-3',
				isset( $settings['layout_type_mobile'] ) ? $settings['layout_type_mobile'] : 'grid-3'
			);

			$q = null;

			$q_filter = array(
				'post_type'      => 'portfolio',
				'post_status'    => 'publish',
				'posts_per_page' => $settings['data_counts'],
				'orderby'        => 'id',
				'order'          => 'DESC',
				'paged'          => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1,
			);

			$catalog_filter = $settings['portfolio_widget_filter'];
			if ( 'default' !== $catalog_filter ) {
				//phpcs:ignore
				$q_filter['meta_query'] = array(
					array(
						'key'   => 'efod_portfolio_one2many_catalog',
						'value' => $catalog_filter,
					),
				);
			}
			$q = new WP_Query( $q_filter );

			$data = array_merge(
				$settings,
				array(
					'q'                      => $q && $q->have_posts() ? $q : null,
					'responsive_layout_type' => $determined_layout_type,
				)
			);

			efod_get_views(
				'widgets/loop-portfolio',
				$data
			);
		}
	}
}
