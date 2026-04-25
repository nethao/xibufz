<?php
/**
 * Feature content card.
 *
 * @package xibufz
 */

$index = isset( $args['index'] ) ? absint( $args['index'] ) : 1;
?>
<article class="feature-item">
	<div class="feature-index"><?php echo esc_html( $index ); ?></div>
	<div class="feature-body">
		<h4><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a></h4>
		<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 44, '...' ) ); ?></p>
	</div>
	<a class="feature-cover-link" href="<?php echo esc_url( get_permalink() ); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
		<?php xibufz_post_thumb_box( get_the_ID(), 'feature-cover', 'medium_large' ); ?>
	</a>
</article>
