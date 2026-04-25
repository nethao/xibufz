<?php
/**
 * Exclusive reading section.
 *
 * @package xibufz
 */

$exclusive_query = new WP_Query( array(
	'posts_per_page'      => 3,
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
) );
$posts_page_id = (int) get_option( 'page_for_posts' );
$more_url      = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/' );
$lower_panels  = function_exists( 'xibufz_get_home_sidebar_panels' ) ? xibufz_get_home_sidebar_panels( 'lower' ) : array();
?>

<section class="section split-grid">
	<div>
		<div class="section-title-row">
			<h2 class="section-title"><span class="title-bar"></span><?php echo esc_html__( '独家阅读', 'xibufz' ); ?></h2>
			<a class="panel-more" href="<?php echo esc_url( $more_url ); ?>"><?php echo esc_html__( '更多 >', 'xibufz' ); ?></a>
		</div>
		<div class="card feature-list-card">
			<div class="feature-list">
				<?php if ( $exclusive_query->have_posts() ) : ?>
					<?php $index = 1; ?>
					<?php while ( $exclusive_query->have_posts() ) : ?>
						<?php $exclusive_query->the_post(); ?>
						<?php get_template_part( 'template-parts/content/content-card', null, array( 'index' => $index ) ); ?>
						<?php $index++; ?>
					<?php endwhile; ?>
				<?php else : ?>
					<?php xibufz_empty_state(); ?>
				<?php endif; ?>
			</div>
		</div>
		<?php wp_reset_postdata(); ?>
	</div>

	<div class="side-stack">
		<?php foreach ( $lower_panels as $panel ) : ?>
			<?php xibufz_render_home_sidebar_panel( $panel ); ?>
		<?php endforeach; ?>

		<?php get_sidebar(); ?>
	</div>
</section>
