<?php
/**
 * Efod custom widget class
 *
 * @package efod-framework
 */

if ( ! class_exists( 'Efod_Icon_List_Widget' ) ) {
	/**
	 * Efod Icon List Widget
	 *
	 * @package efod-framework
	 * @author FORTI Digital Studio fortidigitalstudio@gmail.com
	 */
	class Efod_Icon_List_Widget extends Elementor\Widget_Base {

		/**
		 * The icon block
		 *
		 * @var icon_block
		 */
		protected $icon_block;

		/**
		 * Get widget name
		 *
		 * @return string widget name
		 */
		public function get_name() {
			return 'Efod Icon List';
		}

		/**
		 * Get widget title
		 *
		 * @return string widget title
		 */
		public function get_title() {
			return __( 'Efod Icon List', 'efod-framework' );
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
					'default'     => 'Our Clients',
					'placeholder' => 'Our Icon Section Title',
				)
			);

			$this->add_control(
				'show_icon_name',
				array(
					'label'        => __( 'Show Icon Name', 'efod-framework' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'efod-framework' ),
					'label_off'    => __( 'Hide', 'efod-framework' ),
					'return_value' => 'yes',
					'default'      => 'no',
				)
			);

			$this->icon_block = new \Elementor\Repeater();

			$this->icon_block->add_control(
				'icon_image',
				array(
					'label'   => __( 'Image', 'efod-framework' ),
					'type'    => \Elementor\Controls_Manager::MEDIA,
					'default' => array(
						'url' => \Elementor\Utils::get_placeholder_image_src(),
					),

				)
			);

			$this->icon_block->add_control(
				'icon_name',
				array(
					'label'       => __( 'Name', 'efod-framework' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'default'     => __( 'Title', 'efod-framework' ),
					'label_block' => true,
				)
			);

			$this->icon_block->add_control(
				'icon_description',
				array(
					'label'       => __( 'Description', 'efod-framework' ),
					'type'        => \Elementor\Controls_Manager::TEXTAREA,
					'default'     => __( 'Type your description here', 'efod-framework' ),
					'label_block' => true,
				)
			);

			$this->add_control(
				'list',
				array(
					'label'       => __( 'Icon List', 'efod-framework' ),
					'type'        => \Elementor\Controls_Manager::REPEATER,
					'fields'      => $this->icon_block->get_controls(),
					'default'     => array(
						array(
							'icon_name' => __( 'Icon #1', 'efod-framework' ),
						),
						array(
							'icon_name' => __( 'Icon #2', 'efod-framework' ),
						),
					),
					'title_field' => '{{{ icon_name }}}',
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
			$settings = $this->get_settings_for_display();
			get_template_part( 'loop', 'iconlist', array( 'settings' => $settings ) );
		}
	}
}
