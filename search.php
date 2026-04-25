<?php
/**
 * Search template.
 *
 * @package xibufz
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container content-layout">
		<section class="card archive-card">
			<header class="archive-header">
				<h1>
					<?php
					printf(
						/* translators: %s: search query. */
						esc_html__( '搜索结果：%s', 'xibufz' ),
						esc_html( get_search_query() )
					);
					?>
				</h1>
				<?php get_search_form(); ?>
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
				<p class="empty-state"><?php echo esc_html__( '没有找到匹配内容，请换个关键词再试。', 'xibufz' ); ?></p>
			<?php endif; ?>
		</section>
	</div>
</main>

<?php
get_footer();
