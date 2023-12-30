<?php
/**
 * This file handle loop of custom post type: catalog
 * 
 * @package efod-theme
 */

/**
 * $args is arguments from elementor. We use this template for widgets and archive page
 * so we need to declare default value and override with elementor $args if exists.
 *
 */

if ( empty( $args ) ) {
	$args                 = array(
		'title'           => '',
		'description'     => '',
		'data_counts'     => get_option( 'posts_per_page' ),
		'pagination_type' => 'slider',
		'layout_type'     => 'masonry-4',
		'q'				  => null
	);
}

$_layout_list_options = ['grid-3', 'grid-4', 'masonry-3', 'masonry-4'];

if ( $q && $q->have_posts() ) { ?>
	<?php if ( ! empty( $title ) ) : ?>
		<h2 class="ef-title"><?php echo esc_html( $title ); ?></h2>
	<?php endif; ?>
	<?php if ( ! empty( $description ) ) : ?>
		<p class="ef-description"><?php echo esc_html( $description ); ?></p>
	<?php endif; ?>
	<?php if ( $responsive_layout_type && ! empty( $responsive_layout_type['list'] ) ) : ?>
		<div class="ef-loop ef-paginate_<?php echo esc_html( $pagination_type ); ?> <?php echo esc_html( $responsive_layout_type['list'] ); ?>">
			<div class="ef-loop-container ef-catalog">
				<?php
				while ( $q->have_posts() ) :
					$q->the_post();
					?>
					<?php $ef_post_id = $q->post->ID; ?>
					<div class="ef-widget__catalog">
						<div class="ef-widget__wrapper">
							<div class="ef-post-thumbnail">
								<?php efod_the_thumbnail(); ?>
							</div>
							<div class="ef-post-body">
								<h3 class="ef-title"> 
									<?php the_title(); ?> <?php efod_posted_on(); ?>
								</h3>
								<div class="ef-description"> 
									<?php the_excerpt(); ?> 
								</div>
								<div class="ef-post-meta">
									<?php efod_custom_posts_meta( 'catalog' ); ?>
								</div>
							</div>
						</div>
					</div> <!-- .ef-widget__catalog -->
				<?php endwhile; ?>
			</div> <!-- .ef-loop-container.ef-catalog -->
			<?php 
			efod_pagination_links(
				array(
					'q'             => $q, 
					'type'          => $pagination_type, 
					'post_type'     => 'catalogs',
					'post_per_page' => $data_counts
				) 
			); 
			?>
		</div> <!-- .ef-loop.ef-paginate -->
	<?php endif; ?>
	<?php if ( $responsive_layout_type && ! empty( $responsive_layout_type['tab'] ) ) : ?>
		<div class="ef-loop ef-tabs <?php echo esc_html( $responsive_layout_type['tab'] ); ?>">
			<ul class="nav nav-tabs" id="efCatalogTab" role="tablist">
				<?php foreach ($terms as $i => $tax_term): $term = $tax_term['term']; ?>
					<li class="nav-item" role="presentation">
						<button class="nav-link <?php echo $i == 0 ? 'active':''; ?>" id="<?php echo $term->slug;?>-tab" data-bs-toggle="tab" data-bs-target="#<?php echo esc_html($term->slug);?>-tab-pane" type="button" role="tab" <?php echo $i == 0 ? 'aria-selected="true"' : '';?>><?php echo esc_html($term->name); ?></button>
					</li>	
				<?php endforeach; ?>
			</ul> <!--.nav.nav-tabs -->
			<div class="tab-content" id="efCatalogTabContent">
				<?php foreach ($terms as $i => $tax_term): $term = $tax_term['term']; $q = $tax_term['q']; ?>
					<div class="tab-pane fade <?php echo $i == 0 ? 'show active' : 'fade'; ?>" id="<?php echo esc_html($term->slug);?>-tab-pane" role="tabpanel" tabindex="<?php echo $i; ?>">
						<ul>
							<?php while($q->have_posts()): $q->the_post();?>
								<li>
									<span class=""><?php echo the_title(); ?></span>
									<a href="<?php echo the_permalink(); ?>"><i class="fa-solid fa-chevron-right"></i></a>
								</li>
							<?php endwhile; ?>
						</ul>
					</div>
				<?php endforeach; ?>
			</div> <!--.tab-content -->
		</div> <!--.ef-loop.ef-tabs -->
	<?php endif; ?>
	<?php if ( $responsive_layout_type && ! empty( $responsive_layout_type['accordion'] ) ) : ?>
		<div class="ef-loop ef-accordion <?php echo esc_html( $responsive_layout_type['accordion'] ); ?>">
			<div class="accordion accordion-flush" id="catalog_accord">
				<?php foreach ($terms as $i => $tax_term): $term = $tax_term['term']; $q = $tax_term['q']; ?>
					<div class="accordion-item">
						<h2 class="accordion-header">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ef-accord-<?php echo esc_html($term->slug); ?>" aria-expanded="false" aria-controls="ef-accord-<?php echo esc_html($term->slug); ?>">
								<?php echo esc_html( $term->name ); ?>
							</button>  <!-- .accordion-button -->
						</h2>  <!-- .accordion-header -->
						<div id="ef-accord-<?php echo esc_html($term->slug); ?>" class="accordion-collapse collapse" data-bs-parent="#catalog_accord">
							<div class="accordion-body">
								<ul>
									<?php while($q->have_posts()): $q->the_post();?>
										<li>
											<span class=""><?php echo the_title(); ?></span>
											<a href="<?php echo the_permalink(); ?>"><i class="fa-solid fa-chevron-right"></i></a>
										</li>
									<?php endwhile; ?>
								</ul>
							</div>  <!-- .accordion-body -->
						</div>  <!-- .accordion-collapse.collapse -->
					</div>  <!-- .accordion-item -->
				<?php endforeach; ?>
			</div>  <!-- .accordion.accordion-flush -->
		</div> <!-- .ef-loop.ef-accordion -->
	<?php endif;
} else {
	efod_get_views( 'content-none' );
}
wp_reset_postdata();