<?php
/**
 * Content summary card.
 *
 * @package xibufz
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-summary' ); ?>>
	<a class="post-summary-thumb" href="<?php echo esc_url( get_permalink() ); ?>">
		<?php xibufz_post_thumb_box( get_the_ID(), 'summary-thumb', 'medium' ); ?>
	</a>
	<div class="post-summary-body">
		<h2><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a></h2>
		<div class="item-meta"><?php echo esc_html( xibufz_post_date() ); ?></div>
		<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 50, '...' ) ); ?></p>
	</div>
</article>
