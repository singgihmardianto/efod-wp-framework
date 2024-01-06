<?php
/**
 * This file handle single of custom post type: portfolio
 *
 * @package efod-framework
 */

/**
 * $args is arguments from elementor. We use this template for widgets and archive page
 * so we need to declare default value and override with elementor $args if exists.
 */
?>

<div class="ef-single ef-featured-portfolio row">
	<div class="col-xs-12 col-sm-6">
		<div class="portfolio-area">
			<?php if ( isset( $subtitle ) ) : ?>
				<h2 class="ef-subtitle"><?php echo esc_html( $subtitle ); ?></h2>
			<?php endif; ?>
			<?php if ( isset( $the_portfolio ) ) : ?>
				<h1 class="ef-title"><?php echo esc_html( $the_portfolio->post_title ); ?></h1>
				<p class="ef-description"><?php echo esc_html( $the_portfolio->post_content ); ?></p>
			<?php endif; ?>
			<?php if ( isset( $the_portfolio ) && 'yes' === $enable_cta ): ?>
				<a class="ef-cta" href="<?php echo esc_html( get_permalink( $the_portfolio ) ); ?>">Read more success stories</a>
			<?php endif; ?>
		</div>
	</div>
	<div class="col-xs-12 col-sm-6">
		<div class="portfolio-thumbnail">
			<?php if ( isset( $featured_thumbnail ) ) : ?>
				<div class="<?php echo ($featured_thumbnail_tablet['id'] || $featured_thumbnail_mobile['id']) ? 'ef-d-lg-block': 'd-block';?>">
					<?php echo wp_get_attachment_image( $featured_thumbnail['id'], 'full' ); ?>
				</div>
			<?php endif; ?>
			<?php if ( isset( $featured_thumbnail_tablet ) ) : ?>
				<div class="ef-d-md-block <?php echo !$featured_thumbnail_mobile['id'] ? 'ef-d-sm-block' : ''; ?>">
					<?php echo wp_get_attachment_image( $featured_thumbnail_tablet['id'], 'full' ); ?>
				</div>
			<?php endif; ?>
			<?php if ( isset( $featured_thumbnail_mobile ) ) : ?>
				<div class="ef-d-sm-block <?php echo !$featured_thumbnail_tablet['id'] ? 'ef-d-md-block' : ''; ?>">
					<?php echo wp_get_attachment_image( $featured_thumbnail_mobile['id'], 'full' ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>