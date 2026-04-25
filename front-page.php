<?php
/**
 * Front page template.
 *
 * @package xibufz
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container">
		<section class="mobile-quick-entry" aria-label="<?php echo esc_attr__( '移动端快捷入口', 'xibufz' ); ?>">
			<a class="mobile-quick-card" href="<?php echo esc_url( xibufz_category_url( '综合信息' ) ); ?>"><span class="mobile-quick-icon">□</span><span class="mobile-quick-label"><?php echo esc_html__( '今日要闻', 'xibufz' ); ?></span></a>
			<a class="mobile-quick-card" href="<?php echo esc_url( xibufz_category_url( '公告公示' ) ); ?>"><span class="mobile-quick-icon">!</span><span class="mobile-quick-label"><?php echo esc_html__( '公告公示', 'xibufz' ); ?></span></a>
			<a class="mobile-quick-card" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="mobile-quick-icon">⚖</span><span class="mobile-quick-label"><?php echo esc_html__( '法律服务', 'xibufz' ); ?></span></a>
			<a class="mobile-quick-card" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="mobile-quick-icon">✎</span><span class="mobile-quick-label"><?php echo esc_html__( '在线投稿', 'xibufz' ); ?></span></a>
		</section>

		<?php
		get_template_part( 'template-parts/sections/hero' );
		get_template_part( 'template-parts/sections/exclusive' );
		get_template_part( 'template-parts/sections/services' );
		get_template_part( 'template-parts/sections/modules' );
		get_template_part( 'template-parts/sections/partners' );
		?>
	</div>
</main>

<?php
get_footer();
