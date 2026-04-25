<?php
/**
 * Single post template.
 *
 * @package xibufz
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container content-layout">
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'card entry-card' ); ?>>
				<header class="entry-header">
					<div class="item-meta"><?php echo esc_html( xibufz_post_date() ); ?></div>
					<h1 class="entry-title"><?php echo esc_html( get_the_title() ); ?></h1>
				</header>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="entry-cover"><?php the_post_thumbnail( 'large' ); ?></div>
				<?php endif; ?>
				<div class="entry-content">
					<?php
					the_content();
					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( '分页：', 'xibufz' ),
						'after'  => '</div>',
					) );
					?>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</main>

<?php
get_footer();
