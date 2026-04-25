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
$notice_query = xibufz_query_by_category( '公告公示', 3 );
$posts_page_id = (int) get_option( 'page_for_posts' );
$more_url      = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/' );
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
		<section class="card panel notice-panel">
			<div class="panel-header">
				<h3 class="panel-title"><?php echo esc_html__( '站务公告', 'xibufz' ); ?></h3>
				<a class="panel-more" href="<?php echo esc_url( xibufz_category_url( '公告公示' ) ); ?>"><?php echo esc_html__( '更多 >', 'xibufz' ); ?></a>
			</div>
			<ul class="notice-list">
				<?php if ( $notice_query->have_posts() ) : ?>
					<?php while ( $notice_query->have_posts() ) : ?>
						<?php $notice_query->the_post(); ?>
						<li><a class="notice-link" href="<?php echo esc_url( get_permalink() ); ?>"><span class="item-title"><?php echo esc_html( get_the_title() ); ?></span></a></li>
					<?php endwhile; ?>
				<?php else : ?>
					<?php xibufz_empty_state(); ?>
				<?php endif; ?>
			</ul>
			<?php wp_reset_postdata(); ?>
		</section>

		<?php get_sidebar(); ?>
	</div>
</section>
