<?php
/**
 * This file handle loop of custom post type: portfolio
 *
 * @package efod-framework
 */

/**
 * $args is arguments from elementor. We use this template for widgets and archive page
 * so we need to declare default value and override with elementor $args if exists.
 */

if ( empty( $args ) ) {
	$args = array(
		'title'           => '',
		'description'     => '',
		'data_counts'     => get_option( 'posts_per_page' ),
		'pagination_type' => 'slider',
		'layout_type'     => 'grid-4',
		'q'               => null,
	);
}

// Grouped some types into another array.
$is_list_layout    = array_intersect_key( $responsive_layout_type, array_flip( array( 'grid-3', 'grid-4', 'masonry-3', 'masonry-4' ) ) );
$joined_list_value = join( ' ', $is_list_layout );

$is_icon_layout    = array_intersect_key( $responsive_layout_type, array_flip( array( 'single-icon', 'multi-icon' ) ) );
$joined_icon_value = join( ' ', $is_icon_layout );

if ( $q && $q->have_posts() ) : ?>
	<?php if ( ! empty( $title ) ) : ?>
		<h2 class="ef-title"><?php echo esc_html( $title ); ?></h2>
	<?php endif; ?>
	<?php if ( ! empty( $description ) ) : ?>
		<p class="ef-description"><?php echo esc_html( $description ); ?></p>
	<?php endif; ?>
	<?php if ( $responsive_layout_type && ! empty( $joined_list_value ) ) : ?>
		<div class="ef-loop ef-paginate_<?php echo esc_html( $pagination_type ); ?> <?php echo esc_html( $joined_list_value ); ?>">
			<div class="ef-loop-container ef-portfolio <?php echo esc_html( $layout_type ); ?>">
				<?php
				while ( $q->have_posts() ) :
					$q->the_post();
					?>
					<?php $ef_post_id = $q->post->ID; ?>
					<div class="ef-widget__portfolio">
						<div class="ef-widget__wrapper">
							<div class="ef-post-thumbnail">
								<?php efod_the_thumbnail(); ?>
							</div> <!-- .ef-post-thumbnail -->
							<div class="ef-post-body">
								<h3 class="ef-title"> 
									<?php the_title(); ?> <?php efod_posted_on(); ?>
								</h3>
								<div class="ef-description"> 
									<?php the_excerpt(); ?> 
								</div>
								<div class="ef-post-meta">
									<?php efod_custom_posts_meta( 'portfolio' ); ?>
								</div>
							</div> <!-- .ef-post-body -->
						</div> <!-- .ef-widget__wrapper -->
					</div> <!-- .ef-widget__portfolio -->
				<?php endwhile; ?>
			</div> <!-- .ef-loop-container.ef-portfolio -->
			<?php
			efod_pagination_links(
				array(
					'q'             => $q,
					'type'          => $pagination_type,
					'post_type'     => 'portfolios',
					'post_per_page' => $data_counts,
				)
			);
			?>
		</div>
	<?php endif; ?>
	<?php if ( $responsive_layout_type && ! empty( $joined_icon_value ) ) : ?>
		<div class="ef-loop ef-portfolio-icon <?php echo esc_html( $joined_icon_value ); ?>">
			<ul>
				<?php
				while ( $q->have_posts() ) :
					$q->the_post();
					?>
					<?php
					$image_id = get_post_meta( $q->post->ID, 'efod_portfolio_client_icon', true );
						$url  = wp_get_attachment_image_url( $image_id );
					?>
					<li><img src="<?php echo esc_html( $url ); ?>" alt="<?php the_title(); ?>"/></li>
				<?php endwhile; ?>
			</ul>
		</div>
	<?php endif; ?>
	<?php
else :
	efod_get_views( 'content-none' );
endif;
wp_reset_postdata();