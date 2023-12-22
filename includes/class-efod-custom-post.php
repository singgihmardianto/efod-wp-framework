<?php
/**
 * Efod custom post class
 *
 * @package efod-framework
 */

if ( ! class_exists( 'Efod_Custom_Post' ) ) {

	/**
	 * Efod Custom Post
	 */
	class Efod_Custom_Post {

		/**
		 * Instantiate new Efod Custom Post object
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'init' ) );
		}

		/**
		 * Register custom post
		 */
		public function init() {
			register_post_type(
				'catalog',
				array(
					'labels'               => array(
						'name'          => __( 'Catalogs', 'efod-framework' ),
						'singular_name' => __( 'Catalog', 'efod-framework' ),
						'add_new_item'  => __( 'Add New Catalog', 'efod-framework' ),
					),
					'supports'             => array( 'title', 'editor', 'thumbnail' ),
					'menu_icon'            => 'dashicons-category',
					'public'               => true,
					'has_archive'          => true,
					'menu_position'        => 22, // below pages.
					'rewrite'              => array( 'slug' => 'catalogs' ),
					'taxonomies'           => array( 'catalog_category' ),
					'register_meta_box_cb' => array( $this, 'efod_catalog_metabox' ),
				)
			);

			register_taxonomy(
				'catalog_category',
				'catalog',
				array(
					'label'             => __( 'Category', 'efod-framework' ),
					'description'       => __( 'Category for your catalog', 'efod-framework' ),
					'hierarchical'      => true,
					'show_ui'           => true,
					'show_in_rest'      => true,
					'show_admin_column' => true,
					'query_var'         => true,
					'public'            => true,
					'rewrite'           => array( 'slug' => 'catalog-category' ),
				)
			);

			register_post_type(
				'portfolio',
				array(
					'labels'               => array(
						'name'          => __( 'Portfolios', 'efod-framework' ),
						'singular_name' => __( 'Portfolio', 'efod-framework' ),
						'add_new_item'  => __( 'Add New Portfolio', 'efod-framework' ),
					),
					'supports'             => array( 'title', 'editor', 'thumbnail' ),
					'public'               => true,
					'menu_icon'            => 'dashicons-testimonial',
					'has_archive'          => true,
					'menu_position'        => 21, // Below pages.
					'rewrite'              => array( 'slug' => 'portfolios' ),
					'register_meta_box_cb' => array( $this, 'efod_portfolio_metabox' ),
				)
			);

			register_post_type(
				'job_vacancies',
				array(
					'labels'        => array(
						'name'          => __( 'Job Vacancies', 'efod-framework' ),
						'singular_name' => __( 'Job Vacancy', 'efod-framework' ),
						'add_new_item'  => __( 'Add New Vacancy', 'efod-framework' ),
					),
					'supports'      => array( 'title', 'editor', 'thumbnail' ),
					'public'        => true,
					'menu_icon'     => 'dashicons-groups',
					'has_archive'   => true,
					'menu_position' => 23, // below pages.
					'taxonomies'    => array( 'job_category' ),
					'rewrite'       => array( 'slug' => 'job-vacancies' ),
				)
			);

			register_taxonomy(
				'job_category',
				'job_vacancies',
				array(
					'label'             => __( 'Category', 'efod-framework' ),
					'description'       => __( 'Category for your job vacancy', 'efod-framework' ),
					'hierarchical'      => true,
					'show_ui'           => true,
					'show_in_rest'      => true,
					'show_admin_column' => true,
					'query_var'         => true,
					'public'            => true,
					'rewrite'           => array( 'slug' => 'job-category' ),
				)
			);

			add_action(
				'save_post_catalog',
				function( $post_id ) {
					$this->efod_save_meta_box(
						$post_id,
						array(
							'nonce' => array(
								'efod_catalog_price_from_nonce',
								'efod_catalog_cta_url_nonce',
								'efod_catalog_cta_name_nonce',
								'efod_catalog_img_galleries_nonce',
							),
							'field' => array(
								'efod_catalog_price_from',
								'efod_catalog_cta_url',
								'efod_catalog_cta_name',
								'efod_catalog_new_tab_url_checkbox',
								'efod_catalog_img_galleries',
							),
						)
					);
				}
			);

			add_action(
				'save_post_portfolio',
				function( $post_id ) {
					$this->efod_save_meta_box(
						$post_id,
						array(
							'nonce' => array(
								'efod_portfolio_project_period_nonce',
								'efod_portfolio_project_link_nonce',
								'efod_portfolio_img_galleries_nonce',
								'efod_portfolio_one2many_catalog_nonce',
								'efod_portfolio_client_icon_nonce',
							),
							'field' => array(
								'efod_portfolio_project_period',
								'efod_portfolio_project_link',
								'efod_portfolio_project_link_type_checkbox',
								'efod_portfolio_img_galleries',
								'efod_portfolio_one2many_catalog',
								'efod_portfolio_client_icon',
							),
						)
					);
				}
			);
		}

		/**
		 * Add custom metabox input for catalog
		 */
		public function efod_catalog_metabox() {

			add_meta_box(
				'efod_catalog_metabox_price_from',
				__( 'Price From', 'efod-framework' ),
				array( $this, 'efod_catalog_metabox_catalog_price_from_html' ),
				'catalog',
				'side'
			);

			add_meta_box(
				'efod_catalog_metabox_cta_url',
				__( 'Call To Action', 'efod-framework' ),
				array( $this, 'efod_catalog_metabox_cta_url' ),
				'catalog',
				'side'
			);

			add_meta_box(
				'efod_catalog_metabox_img_galleries',
				__( 'Catalog Gallery', 'efod-framework' ),
				array( $this, 'efod_catalog_metabox_catalog_galleries_html' ),
				'catalog',
				'side',
				'low'
			);
		}

		/**
		 * Add custom metabox input for portfolio
		 */
		public function efod_portfolio_metabox() {
			add_meta_box(
				'efod_portfolio_one2many_catalog',
				__( 'Select Catalog', 'efod-framework' ),
				array( $this, 'efod_portfolio_metabox_one2many_catalog_html' ),
				'portfolio',
				'side',
				'low'
			);

			add_meta_box(
				'efod_portfolio_project_period',
				__( 'Project Period', 'efod-framework' ),
				array( $this, 'efod_portfolio_metabox_project_period_html' ),
				'portfolio',
				'side'
			);

			add_meta_box(
				'efod_portfolio_project_link',
				__( 'Project URL', 'efod-framework' ),
				array( $this, 'efod_portfolio_metabox_project_link_html' ),
				'portfolio',
				'side'
			);

			add_meta_box(
				'efod_portfolio_img_galleries',
				__( 'Portfolio Gallery', 'efod-framework' ),
				array( $this, 'efod_portfolio_metabox_portfolio_galleries_html' ),
				'portfolio',
				'side',
				'low'
			);

			add_meta_box(
				'efod_portfolio_client_icon',
				__( 'Portfolio Client Icon', 'efod-framework' ),
				array( $this, 'efod_portfolio_metabox_portfolio_client_icon_html' ),
				'portfolio',
				'side',
				'low'
			);
		}

		/**
		 * Render catalog galleries html
		 *
		 * @param Post $post the custom post.
		 */
		public function efod_catalog_metabox_catalog_galleries_html( $post ) {
			// Add a nonce field so we can check for it later.
			wp_nonce_field( 'efod_catalog_img_galleries_nonce', 'efod_catalog_img_galleries_nonce' );
			$value = get_post_meta( $post->ID, 'efod_catalog_img_galleries', true );

			echo esc_html(
				$this->create_input(
					'img_galleries',
					(object) array(
						'name'        => 'efod_catalog_img_galleries',
						'id'          => 'efod_catalog_img_galleries',
						'value'       => $value,
					)
				)
			);
		}

		/**
		 * Custom input catalog price
		 *
		 * @param Post $post the custom post.
		 */
		public function efod_catalog_metabox_catalog_price_from_html( $post ) {
			// Add a nonce field so we can check for it later.
			wp_nonce_field( 'efod_catalog_price_from_nonce', 'efod_catalog_price_from_nonce' );
			$value = get_post_meta( $post->ID, 'efod_catalog_price_from', true );

			echo esc_html(
				$this->create_input(
					'number',
					(object) array(
						'name'        => 'efod_catalog_price_from',
						'id'          => 'efod_catalog_price_from',
						'placeholder' => 'USD 1.000',
						'value'       => $value,
					)
				)
			);
		}

		/**
		 * Custom input catalog contact form id
		 *
		 * @param Post $post the custom post.
		 */
		public function efod_catalog_metabox_cta_url( $post ) {
			// Add a nonce field so we can check for it later.
			wp_nonce_field( 'efod_catalog_cta_url_nonce', 'efod_catalog_cta_url_nonce' );
			$cta_url_value = get_post_meta( $post->ID, 'efod_catalog_cta_url', true );

			wp_nonce_field( 'efod_catalog_cta_name_nonce', 'efod_catalog_cta_name_nonce' );
			$cta_name_value = get_post_meta( $post->ID, 'efod_catalog_cta_name', true );

			$link_type_value = get_post_meta( $post->ID, 'efod_catalog_new_tab_url_checkbox', true );

			echo esc_html(
				$this->create_input(
					'text',
					(object) array(
						'name'        => 'efod_catalog_cta_url',
						'id'          => 'efod_catalog_cta_url',
						'placeholder' => 'https://wa.me/62000000',
						'value'       => $cta_url_value,
					)
				)
			);

			echo esc_html(
				$this->create_input(
					'text',
					(object) array(
						'name'        => 'efod_catalog_cta_name',
						'id'          => 'efod_catalog_cta_name',
						'placeholder' => 'Discuss Now',
						'value'       => $cta_name_value,
						'style'       => 'margin-top: 10px; display: inline-block;',
					)
				)
			);

			echo esc_html(
				$this->create_input(
					'checkbox',
					(object) array(
						'name'  => 'efod_catalog_new_tab_url_checkbox',
						'id'    => 'efod_catalog_new_tab_url_checkbox',
						'value' => $link_type_value,
						'label' => 'Open New Window',
						'style' => 'margin-top: 10px; display: inline-block;',
					)
				)
			);
		}

		/**
		 * Render one2many field with catalog
		 *
		 * @param Post $post the custom post.
		 */
		public function efod_portfolio_metabox_one2many_catalog_html( $post ) {
			// Add a nonce field so we can check for it later.
			wp_nonce_field( 'efod_portfolio_one2many_catalog_nonce', 'efod_portfolio_one2many_catalog_nonce' );
			$value = get_post_meta( $post->ID, 'efod_portfolio_one2many_catalog', true );

			// get options from catalog.
			// Get all Portfolio items.
			$catalog_args  = array(
				'post_type'      => 'catalog',
				'posts_per_page' => -1,
			);
			$catalog_query = new WP_Query( $catalog_args );

			$catalogs = array();
			if ( $catalog_query->have_posts() ) {
				while ( $catalog_query->have_posts() ) {
					$catalog_query->the_post();
					$catalog_id = get_the_ID();
					array_push(
						$catalogs,
						array(
							'label' => get_the_title(),
							'value' => $catalog_id,
						)
					);
				}
				wp_reset_postdata();
			}

			echo esc_html(
				$this->create_input(
					'select',
					(object) array(
						'name'        => 'efod_portfolio_one2many_catalog',
						'id'          => 'efod_portfolio_one2many_catalog',
						'value'       => $value,
						'options'     => $catalogs,
					)
				)
			);
		}

		/**
		 * Render portfolio galleries html
		 *
		 * @param Post $post the custom post.
		 */
		public function efod_portfolio_metabox_portfolio_galleries_html( $post ) {
			// Add a nonce field so we can check for it later.
			wp_nonce_field( 'efod_portfolio_img_galleries_nonce', 'efod_portfolio_img_galleries_nonce' );
			$value = get_post_meta( $post->ID, 'efod_portfolio_img_galleries', true );

			echo esc_html(
				$this->create_input(
					'img_galleries',
					(object) array(
						'name'        => 'efod_portfolio_img_galleries',
						'id'          => 'efod_portfolio_img_galleries',
						'value'       => $value,
					)
				)
			);
		}

		/**
		 * Render portfolio client icon
		 *
		 * @param Post $post the custom post.
		 */
		public function efod_portfolio_metabox_portfolio_client_icon_html( $post ) {
			// Add a nonce field so we can check for it later.
			wp_nonce_field( 'efod_portfolio_client_icon_nonce', 'efod_portfolio_client_icon_nonce' );
			$value = get_post_meta( $post->ID, 'efod_portfolio_client_icon', true );

			echo esc_html(
				$this->create_input(
					'img',
					(object) array(
						'name'        => 'efod_portfolio_client_icon',
						'id'          => 'efod_portfolio_client_icon',
						'value'       => $value,
					)
				)
			);
		}

		/**
		 * Custom input portfolio project period
		 *
		 * @param Post $post the custom post.
		 */
		public function efod_portfolio_metabox_project_period_html( $post ) {
				// Add a nonce field so we can check for it later.
				wp_nonce_field( 'efod_portfolio_project_period_nonce', 'efod_portfolio_project_period_nonce' );
				$value = get_post_meta( $post->ID, 'efod_portfolio_project_period', true );

				echo esc_html(
					$this->create_input(
						'text',
						(object) array(
							'name'        => 'efod_portfolio_project_period',
							'id'          => 'efod_portfolio_project_period',
							'placeholder' => '2020 until (now)',
							'value'       => $value,
						)
					)
				);
		}

		/**
		 * Custom input portfolio link
		 *
		 * @param Post $post the custom post.
		 */
		public function efod_portfolio_metabox_project_link_html( $post ) {
				// Add a nonce field so we can check for it later.
				wp_nonce_field( 'efod_portfolio_project_link_nonce', 'efod_portfolio_project_link_nonce' );
				$link_value      = get_post_meta( $post->ID, 'efod_portfolio_project_link', true );
				$link_type_value = get_post_meta( $post->ID, 'efod_portfolio_project_link_type_checkbox', true );

				echo esc_html(
					$this->create_input(
						'text',
						(object) array(
							'name'        => 'efod_portfolio_project_link',
							'id'          => 'efod_portfolio_project_link',
							'placeholder' => 'https://yoursitedomain.com',
							'value'       => $link_value,
						)
					)
				);

				echo esc_html(
					$this->create_input(
						'checkbox',
						(object) array(
							'name'  => 'efod_portfolio_project_link_type_checkbox',
							'id'    => 'efod_portfolio_project_link_type_checkbox',
							'value' => $link_type_value,
							'label' => 'Open New Window',
							'style' => 'margin-top: 10px; display: inline-block;',
						)
					)
				);
		}

		/**
		 * When the post is saved, saves our custom data.
		 *
		 * @param int   $post_id integer post id.
		 * @param array $args    array fields and nonce to check.
		 */
		public function efod_save_meta_box( $post_id, $args ) {
			// Check if our nonce is set.
			foreach ( $args['nonce'] as $nonce ) {
				if ( ! isset( $_POST[ $nonce ] ) ) {
					return;
				}

				// Verify that the nonce is valid.
				// phpcs:ignore
				if ( ! wp_verify_nonce( $_POST[ $nonce ], $nonce ) ) {
					return;
				}
			}

			// If this is an autosave, our form has not been submitted, so we don't want to do anything.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			// Check the user's permissions.
			if ( isset( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) {

				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return;
				}
			} else {

				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					return;
				}
			}

			foreach ( $args['field'] as $field ) {
				if ( ! strpos( $field, 'checkbox' ) ) {
					// process non checkbox input.
					// Sanitize user input.
					if ( ! isset( $_POST[ $field ] ) ) {
						return;
					}
					$my_data = sanitize_text_field( wp_unslash( $_POST[ $field ] ) );
					update_post_meta( $post_id, $field, $my_data );
				}

				if ( strpos( $field, 'checkbox' ) ) {
					// Process checkbox or radio input.
					update_post_meta( $post_id, $field, isset( $_POST[ $field ] ) ? 'yes' : 'no' );
				}
			}
		}

		/**
		 * Create input based on type
		 *
		 * @param string $type input type.
		 * @param mix    $args input arguments.
		 */
		protected function create_input(
			$type = 'text',
			$args = array(
				'id'          => null,
				'name'        => null,
				'placeholder' => null,
				'style'       => null,
				'value'       => null,
			)
		) {
			// phpcs:disable
			switch ( $type ) {
				case 'number':
					echo '<input 
							name="' . $args->name . '"
							id="' . ( isset( $args->id ) ? $args->id : $args->name ) . '" 
							type="number"
							placeholder="' . ( isset( $args->placeholder ) ? $args->placeholder : '' ) . '" 
							style="' . ( isset( $args->style ) ? $args->style : '' ) . '"
							value="' . ( isset( $args->value ) ? $args->value : '' ) . '"
						>';
					break;

				case 'text':
					echo '<input 
							name="' . $args->name . '"
							id="' . ( isset( $args->id ) ? $args->id : $args->name ) . '"
							type="text"
							placeholder="' . ( isset( $args->placeholder ) ? $args->placeholder : '' ) . '" 
							style="' . ( isset( $args->style ) ? $args->style : '' ) . '"
							value="' . ( isset( $args->value ) ? $args->value : '' ) . '"
						>';
					break;

				case 'checkbox':
					echo '<label style="' . ( isset( $args->style ) ? $args->style : '' ) . '"> 
							<input
								name="' . $args->name . '"
								id="' . ( isset( $args->id ) ? $args->id : $args->name ) . '"
								placeholder="' . ( isset( $args->placeholder ) ? $args->placeholder : '' ) . '" 
								type="checkbox" ' . ( isset( $args->value ) && 'yes' === $args->value ? 'checked' : '' ) . '> 
								' . $args->label . '
						</label>';
					break;

				case 'img_galleries':
					$image_ids = explode(",", $args->value);
					?>
						<ul class="ef-cpt-meta-img-galleries__list">
							<?php
								foreach( $image_ids as $i => &$id ) : ?>
								<?php 
								$url = wp_get_attachment_image_url( $id, array( 80, 80 ) ); 
								if ($url): ?>
									<li data-id="<?php echo $id ?>">
										<span style="background-image:url('<?php echo $url ?>')"></span>
									</li>
								<?php else: unset( $image_ids[$i] ); ?>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
						<input 
							type="hidden" 
							name="<?php echo esc_html( $args->name ); ?>" 
							value="<?php echo esc_html( join( ',', $image_ids ) ); ?>" 
							class="ef-cpt-meta-img-galleries__input_hidden" />
					<?php
					echo '<button id="' . $args->id . '" class="button ef-cpt-meta-img-galleries__add_btn" type="button">' . __( 'Add Images', 'efod-framework' ) . '</button>';
					break;

				case 'img':
					$image_id = $args->value;
					?>
						<ul class="ef-cpt-meta-img-galleries__list">
							<?php
								$url = wp_get_attachment_image_url( $image_id, array( 80, 80 ) ); 
								if ($url): ?>
									<li data-id="<?php echo $image_id ?>">
										<span style="background-image:url('<?php echo $url ?>')"></span>
									</li>
								<?php endif; ?>
						</ul>
						<input 
							type="hidden" 
							name="<?php echo esc_html( $args->name ); ?>" 
							value="<?php echo esc_html( $image_id ); ?>" 
							class="ef-cpt-meta-img-galleries__input_hidden" />
					<?php
					echo '<button id="' . $args->id . '" class="button ef-cpt-meta-img-galleries__add_btn" data-attr-is-single="true" type="button">' . __( 'Add Image', 'efod-framework' ) . '</button>';
					break;

				case 'select':
					$options = ''; $selected = '';
					foreach( $args->options as $opt ) {
						if ( $args->value == $opt['value'] ) {
							$selected = 'selected';
						} else { 
							$selected = '';
						}
						$options .= '<option value="' . $opt['value'] . '" ' . $selected . '>' . $opt['label'] . '</option>';
					}
					echo '<label style="' . ( isset( $args->style ) ? $args->style : '' ) . '">
						<select 
							name="' . $args->name . '"
							id="' . $args->id . '">
							<option value="">--Select Catalog--</option>
							' . $options . '
						</select>
					</label>';
					break;

				default:
					// code...
					break;
			}
			// phpcs:enable
		}
	}

}


return new Efod_Custom_Post();
