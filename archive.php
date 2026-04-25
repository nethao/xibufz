<?php
/**
 * Archive template.
 *
 * @package xibufz
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container content-layout">
		<section class="card archive-card">
			<header class="archive-header">
				<?php the_archive_title( '<h1>', '</h1>' ); ?>
				<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
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
