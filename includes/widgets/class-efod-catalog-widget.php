<?php
/**
 * Efod custom widget class
 *
 * @package efod-framework
 */

if ( ! class_exists( 'Efod_Catalog_Widget' ) ) {
	/**
	 * Efod Catalog Widget
	 *
	 * @package efod-framework
	 * @author FORTI Digital Studio fortidigitalstudio@gmail.com
	 */
	class Efod_Catalog_Widget extends Elementor\Widget_Base {

		/**
		 * Array of catalog taxonomies
		 *
		 * @var array cat_options
		 */
		protected $cat_options;

		/**
		 * Array of layout options
		 *
		 * @var array layout_options;
		 */
		protected $layout_options;

		/**
		 * Widget options
		 *
		 * @var widget_options
		 */
		protected $widget_options;

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
			$catalog_cat_arr = get_terms(
				array(
					'taxonomy'   => 'catalog_category',
					'hide_empty' => false,
				)
			);

			$this->cat_options['default'] = 'Show All';

			foreach ( $catalog_cat_arr as $cat ) {
				$this->cat_options[ $cat->slug ] = esc_html( $cat->name );
			}

			/**
			 * Widget display options
			 */
			$this->layout_options = array(
				'grid-3'    => __( 'Grid 3 Column', 'efod-framework' ),
				'grid-4'    => __( 'Grid 4 Column', 'efod-framework' ),
				'masonry-3' => __( 'Masonry 3 column', 'efod-framework' ),
				'masonry-4' => __( 'Masonry 4 column', 'efod-framework' ),
				'tab'       => __( 'Tab', 'efod-framework' ),
				'accordion' => __( 'Accordion', 'efod-framework' ),
			);
		}

		/**
		 * Get widget name
		 *
		 * @return string widget name
		 */
		public function get_name() {
			return 'Efod Catalog';
		}

		/**
		 * Get widget title
		 *
		 * @return string widget title
		 */
		public function get_title() {
			return __( 'Efod Catalog', 'efod-framework' );
		}

		/**
		 * Get widget icon
		 *
		 * @return string widget icon
		 */
		public function get_icon() {
			return 'eicon-folder';
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
					'default'     => 'Our Products',
					'placeholder' => 'Catalog Title',
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
					'name'     => 'catalog_title_typography',
					'selector' => '{{WRAPPER}} .ef-catalog > .ef-title, .ef-catalog > .ef-description',
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
					'label'      => __( 'Catalog To Show', 'efod-framework' ),
					'type'       => \Elementor\Controls_Manager::NUMBER,
					'input_type' => 'number',
					'default'    => 3,
				)
			);

			$this->add_control(
				'catalog_widget_filter',
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

			$this->add_responsive_control(
				'layout_type',
				array(
					'label'           => __( 'Layout Type', 'efod-framework' ),
					'type'            => \Elementor\Controls_Manager::SELECT,
					'options'         => $this->layout_options,
					'default'         => 'grid-3',
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
			// get widget settings.
			$settings = $this->get_settings_for_display();

			$determined_layout_type = efod_determine_responsive_class(
				array_keys( $this->layout_options ),
				$settings['layout_type'],
				isset( $settings['layout_type_tablet'] ) ? $settings['layout_type_tablet'] : 'grid-3',
				isset( $settings['layout_type_mobile'] ) ? $settings['layout_type_mobile'] : 'grid-3'
			);

			$q        = null;
			$q_filter = null;
			if ( $determined_layout_type['grid-3'] ||
				$determined_layout_type['grid-4'] ||
				$determined_layout_type['masonry-3'] ||
				$determined_layout_type['masonry-4'] ) {
				$q_filter = array(
					'post_type'      => 'catalog',
					'post_status'    => 'publish',
					'posts_per_page' => $settings['data_counts'],
					'orderby'        => 'id',
					'order'          => 'DESC',
					'paged'          => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1,
				);
			}

			if ( null !== $q_filter && 'default' !== $settings['catalog_widget_filter'] ) {
				// if used in any screen.
				$tax_category = $settings['catalog_widget_filter'];
				// phpcs:ignore
				$q_filter['tax_query'] = array(
					array(
						'taxonomy' => 'catalog_category',
						'field'    => 'slug',
						'terms'    => array( $tax_category ),
					),
				);
			}
			$q = new WP_Query( $q_filter );

			$terms = null;
			if ( $determined_layout_type['tab'] || $determined_layout_type['accordion'] ) {
				$_terms = get_terms(
					'catalog_category',
					array(
						'hide_empty' => 0,
					)
				);

				$terms = array();
				foreach ( $_terms as $term ) {
					$q_filter = array(
						'post_type'      => 'catalog',
						'post_status'    => 'publish',
						'posts_per_page' => $settings['data_counts'],
						'orderby'        => 'id',
						'order'          => 'DESC',
						'paged'          => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1,
						// phpcs:ignore
						'tax_query'      => array(
							array(
								'taxonomy' => 'catalog_category',
								'field'    => 'slug',
								'terms'    => $term->slug,
							),
						),
					);
					$q_term   = new WP_Query( $q_filter );
					$terms[]  = array(
						'term' => $term,
						'q'    => $q_term,
					);
				}
			}

			$data = array_merge(
				$settings,
				array(
					'q'                      => $q && $q->have_posts() ? $q : null,
					'terms'                  => $terms ? $terms : null,
					'responsive_layout_type' => $determined_layout_type,
				)
			);

			efod_get_views(
				'widgets/loop-catalog',
				$data
			);
		}
	}
}
