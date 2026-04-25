<?php
/**
 * Main index template.
 *
 * @package xibufz
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container content-layout">
		<section class="card archive-card">
			<header class="archive-header">
				<h1><?php echo esc_html__( '最新文章', 'xibufz' ); ?></h1>
			</header>
			<?php if ( have_posts() ) : ?>
				<div class="post-list">
					<?php while ( have_posts() ) : ?>
						<?php the_post(); ?>
						<?php get_template_part( 'template-parts/content/content-summary' ); ?>
					<?php endwhile; ?>
				</div>
				<?php the_posts_pagination(); ?>
			<?php else : ?>
				<?php xibufz_empty_state(); ?>
			<?php endif; ?>
		</section>
	</div>
</main>

<?php
get_footer();
