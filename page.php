<?php
/**
 * Page template.
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
					<h1 class="entry-title"><?php echo esc_html( get_the_title() ); ?></h1>
				</header>
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
