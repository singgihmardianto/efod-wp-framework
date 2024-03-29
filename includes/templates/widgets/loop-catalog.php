<?php
/**
 * This file handle loop of custom post type: catalog
 *
 * @package efod-framework
 */

// Grouped some types into another array.
$is_list_layout    = array_intersect_key( $responsive_layout_type, array_flip( array( 'grid-3', 'grid-4', 'masonry-3', 'masonry-4' ) ) );
$joined_list_value = trim( join( ' ', $is_list_layout ) ); ?>

<?php if ( ! empty( $title ) ) : ?>
	<h2 class="ef-title"><?php echo esc_html( $title ); ?></h2>
<?php endif; ?>
<?php if ( ! empty( $description ) ) : ?>
	<p class="ef-description"><?php echo esc_html( $description ); ?></p>
<?php endif; ?>

<?php if ( $q && $responsive_layout_type && ! empty( $joined_list_value ) ) : ?>
	<div class="ef-loop ef-paginate_<?php echo esc_html( $pagination_type ); ?> <?php echo esc_html( $joined_list_value ); ?>">
		<div class="ef-loop-container ef-catalog <?php echo esc_html( $layout_type ); ?>">
			<?php
			while ( $q->have_posts() ) :
				$q->the_post();
				?>
				<?php $ef_post_id = $q->post->ID; ?>
				<div class="ef-widget__catalog">
					<div class="ef-widget__wrapper">
						<div class="ef-post-thumbnail">
							<?php efod_the_thumbnail( array( 'custom_link_field' => 'efod_catalog_cta_url' ) ); ?>
						</div>
						<div class="ef-post-body">
							<h3 class="ef-title"> 
								<?php efod_the_title( array( 'custom_link_field' => 'efod_catalog_cta_url' ) ); ?> <?php efod_posted_on(); ?>
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
				'post_per_page' => $data_counts,
			)
		);
		?>
	</div> <!-- .ef-loop.ef-paginate -->
<?php endif; ?>

<?php if ( $responsive_layout_type && ! empty( $responsive_layout_type['tab'] ) ) : ?>
	<div class="ef-loop ef-tabs <?php echo esc_html( $responsive_layout_type['tab'] ); ?>">
		<ul class="nav nav-tabs" id="efCatalogTab" role="tablist">
			<?php
			foreach ( $terms as $i => $tax_term ) :
				$cur_term = $tax_term['term'];
				?>
				<li class="nav-item" role="presentation">
					<button class="nav-link <?php echo esc_html( 0 === $i ? 'active' : '' ); ?>" id="<?php echo esc_html( $cur_term->slug ); ?>-tab" data-bs-toggle="tab" data-bs-target="#<?php echo esc_html( $cur_term->slug ); ?>-tab-pane" type="button" role="tab"><?php echo esc_html( $cur_term->name ); ?></button>
				</li>	
			<?php endforeach; ?>
		</ul> <!--.nav.nav-tabs -->
		<div class="tab-content" id="efCatalogTabContent">
			<?php
			foreach ( $terms as $i => $tax_term ) :
				$cur_term = $tax_term['term'];
				$q_tab    = $tax_term['q'];
				?>
				<div class="tab-pane fade <?php echo esc_html( 0 === $i ? 'show active' : 'fade' ); ?>" id="<?php echo esc_html( $cur_term->slug ); ?>-tab-pane" role="tabpanel">
					<ul>
						<?php
						while ( $q_tab->have_posts() ) :
							$q_tab->the_post();
							?>
							<li>
								<span class=""><?php echo esc_html( the_title() ); ?></span>
								<a href="<?php echo esc_html( the_permalink() ); ?>"><i class="fa-solid fa-chevron-right"></i></a>
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
			<?php
			foreach ( $terms as $i => $tax_term ) :
				$cur_term = $tax_term['term'];
				$q_acc    = $tax_term['q'];
				?>
				<div class="accordion-item">
					<h2 class="accordion-header">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ef-accord-<?php echo esc_html( $cur_term->slug ); ?>" aria-expanded="false" aria-controls="ef-accord-<?php echo esc_html( $cur_term->slug ); ?>">
							<?php echo esc_html( $cur_term->name ); ?>
						</button>  <!-- .accordion-button -->
					</h2>  <!-- .accordion-header -->
					<div id="ef-accord-<?php echo esc_html( $cur_term->slug ); ?>" class="accordion-collapse collapse" data-bs-parent="#catalog_accord">
						<div class="accordion-body">
							<ul>
								<?php
								while ( $q_acc->have_posts() ) :
									$q_acc->the_post();
									?>
									<li>
										<span class=""><?php echo esc_html( the_title() ); ?></span>
										<a href="<?php echo esc_html( the_permalink() ); ?>"><i class="fa-solid fa-chevron-right"></i></a>
									</li>
								<?php endwhile; ?>
							</ul>
						</div>  <!-- .accordion-body -->
					</div>  <!-- .accordion-collapse.collapse -->
				</div>  <!-- .accordion-item -->
			<?php endforeach; ?>
		</div>  <!-- .accordion.accordion-flush -->
	</div> <!-- .ef-loop.ef-accordion -->
	<?php
endif;
?>

<?php
wp_reset_postdata();
