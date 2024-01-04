<?php
/**
 * The file that declare all helper methods
 *
 * @package    efod-framework
 */

if ( ! function_exists( 'efod_get_views' ) ) {
	/**
	 * Load view files under folder /include/templates.
	 *
	 * @param string $slug file path slug.
	 * @param arrray $variables data pass to file.
	 */
	function efod_get_views( $slug, $variables = array() ) {
		$file = plugin_dir_path( __FILE__ ) . 'templates/' . $slug . '.php';

		if ( $variables && count( $variables ) > 0 ) {
			// Iterate over the $variables array and create variables dynamically.
			foreach ( $variables as $key => $value ) {
				${$key} = $value;
			}
		}

		if ( file_exists( $file ) ) {
			include $file;
		}
	}
}

if ( ! function_exists( 'efod_the_thumbnail' ) ) {
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function efod_the_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( ! is_singular() ) :
			?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
					the_post_thumbnail(
						'post-thumbnail',
						array(
							'alt' => the_title_attribute(
								array(
									'echo' => false,
								)
							),
						)
					);
				?>
			</a>

			<?php
		endif; // End is_singular().
	}
}

if ( ! function_exists( 'efod_posted_on' ) ) {
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function efod_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
}

if ( ! function_exists( 'efod_custom_posts_meta' ) ) {
	/**
	 * Prints HTML with meta information for the current author.
	 *
	 * @param string $type custom post type.
	 */
	function efod_custom_posts_meta( $type = 'catalog' ) {
		$author = '<span class="ef-meta-author"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '"><i class="fa-solid fa-user"></i> ' . esc_html( get_the_author() ) . '</a></span>';

		$category  = '';
		$term_id   = 0;
		$term_name = '';
		$term_type = '';
		$terms     = '';
		$url       = '';

		if ( in_array( $type, array( 'catalog' ), true ) ) {
			$term_type = $type . '_category';
		}

		if ( 'posts' === $type ) {
			$term_type = 'category';
		}

		if ( ! empty( $term_type ) ) {
			$terms = get_the_terms( get_the_ID(), $term_type );
		}

		if ( ! empty( $terms ) && count( $terms ) > 0 ) {
			$term_id   = $terms[0]->term_id; // get primary term.
			$term_name = $terms[0]->name;
		}

		// if is custom post type, then get term by term_type.
		// only applied for: catalog & portfolio.
		if ( ! empty( $term_id ) && ! empty( $term_type ) ) {
			$url = get_term_link( $term_id, $term_type );
		}

		// if is posts.
		if ( ! empty( $term_id ) ) {
			$url = get_term_link( $term_id );
		}

		if ( ! empty( $url ) ) {
			$category = ' <span class="ef-meta-category"><a class="url fn n" href="' . esc_url( $url ) . '"><i class="fa-solid fa-folder"></i> ' . esc_html( $term_name ) . '</a></span>';
		}

		echo $author . $category; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
}

if ( ! function_exists( 'efod_pagination_links' ) ) {
	/**
	 *
	 * Render pagination links
	 *
	 * @param Array $params array of pagination parameters.
	 */
	function efod_pagination_links( $params = array(
		'q'             => null,
		'type'          => 'default',
		'post_type'     => 'posts',
		'post_per_page' => 6,
	) ) {
		switch ( $params['type'] ) {
			case 'load_button':
				?>
					<div class="ef-pagination-wrapper"> 
					<?php
					if ( $params['q']->max_num_pages > 1 ) :
						?>
						<button class="ef-btn-pagination btn_fetch_pagination_load_more" 
							data-post-type="<?php echo esc_html( $params['post_type'] ); ?>"
							data-post-per-page="<?php echo esc_html( $params['post_per_page'] ); ?>">
							<?php echo esc_html__( 'Load More', 'efod-framework' ); ?>
						</button>
						<?php
					endif;
					?>
					</div>
					<?php
				break;

			case 'slider':
				if ( $params['q']->max_num_pages > 1 ) :
					?>
					<button class="ef-btn-pagination btn_fetch_pagination_prev_page" 
						data-post-type="<?php echo esc_html( $params['post_type'] ); ?>"
						data-post-per-page="<?php echo esc_html( $params['post_per_page'] ); ?>">
						<i class="fa-solid fa-angle-left"></i>
					</button>
					<button class="ef-btn-pagination btn_fetch_pagination_next_page" 
						data-post-type="<?php echo esc_html( $params['post_type'] ); ?>"
						data-post-per-page="<?php echo esc_html( $params['post_per_page'] ); ?>"
						data-max-page="<?php echo esc_html( $params['q']->max_num_pages ); ?>">
						<i class="fa-solid fa-angle-right"></i>
					</button>
					<?php
				endif;
				break;

			default:
				?>
				<div class="ef-pagination-wrapper">
					<?php
					for ( $i = 0; $i < $params['q']->max_num_pages; $i++ ) :
						?>
							<button class="ef-btn-pagination btn_fetch_pagination" 
								data-post-type="<?php echo esc_html( $params['post_type'] ); ?>"
								data-post-per-page="<?php echo esc_html( $params['post_per_page'] ); ?>"
								data-paged="<?php echo esc_html( $i + 1 ); ?>">
								<?php echo esc_html( $i + 1 ); ?>
							</button>
						<?php
					endfor;
					?>
				</div>
				<?php
				break;
		}
	}
}

if ( ! function_exists( 'efod_determine_responsive_class' ) ) {
	/**
	 * Is a method used in widget class
	 * From widget control input to strings responsive class
	 *
	 * @param array  $layout_options is an array of string layout options e.g: ['grid-3', 'grid-4', 'masonry'].
	 * @param string ...$medias is an array of string which contain: 'layout_type', 'layout_type_tablet', 'layout_type_mobile' and must be declare sequentially.
	 * @return array $responsive_result is a mixed array with key is layout_type and value is the responsive class (d-lg-block, etc).
	 */
	function efod_determine_responsive_class( array $layout_options, ...$medias ) {
		$results = array();
		foreach ( $layout_options as $opt ) {
			$results[ $opt ] = '';
		}

		$temp           = null;
		$str_responsive = array();
		foreach ( $medias as $i => $media ) {
			$_i = '';
			switch ( $i ) {
				case 0:
					$_i = 'ef-d-lg-block';
					break;
				case 1:
					$_i = 'ef-d-md-block';
					break;
				case 2:
					$_i = 'ef-d-sm-block';
					break;
				default:
					$_i = '';
					break;
			}

			if ( null === $temp ) {
				$str_responsive[] = $_i;
				$temp             = $media;
			} elseif ( null !== $temp && $temp === $media ) {
				$str_responsive[] = $_i;
			} elseif ( null !== $temp && $temp !== $media ) {
				$str_responsive[] = 'd-none';
				$results[ $temp ] = join( ' ', $str_responsive );
				$temp             = $media;
				$str_responsive   = array();
				$str_responsive[] = $_i;
			}
		}
		if ( count( $str_responsive ) > 0 && null !== $temp ) {
			$str_responsive[] = 'd-none';
			$results[ $temp ] = join( ' ', $str_responsive );
		}
		return $results;
	}
}
