<?php
/**
 * Footer template.
 *
 * @package xibufz
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$footer_desc      = xibufz_mod( 'xibufz_footer_desc', esc_html__( '打造更权威、更清晰、更现代的法治资讯与服务门户首页，适合新闻发布、公告公示、专题传播与便民服务统一承载。', 'xibufz' ) );
$footer_record    = xibufz_mod( 'xibufz_footer_record', esc_html__( '陕ICP备2025000000号', 'xibufz' ) );
$footer_organizer = xibufz_mod( 'xibufz_footer_organizer', esc_html__( '西部法制网', 'xibufz' ) );
$footer_site_url  = xibufz_mod( 'xibufz_footer_site_url', 'www.xibufz.com' );
?>

<footer>
	<div class="container footer-main">
		<div class="footer-grid">
			<div class="footer-brand">
				<?php xibufz_site_logo( 'footer' ); ?>
				<h3 class="footer-title"><?php bloginfo( 'name' ); ?></h3>
				<div class="footer-text"><?php echo esc_html( $footer_desc ); ?></div>
			</div>

			<div>
				<h3 class="footer-title"><?php echo esc_html__( '网站信息', 'xibufz' ); ?></h3>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer',
					'menu_class'     => 'footer-list',
					'container'      => false,
					'fallback_cb'    => false,
				) );
				?>
				<?php if ( ! has_nav_menu( 'footer' ) ) : ?>
					<ul class="footer-list">
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( '网站介绍', 'xibufz' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( '投稿须知', 'xibufz' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( '联系我们', 'xibufz' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( '隐私政策', 'xibufz' ); ?></a></li>
					</ul>
				<?php endif; ?>
			</div>

			<div>
				<h3 class="footer-title"><?php echo esc_html__( '服务入口', 'xibufz' ); ?></h3>
				<ul class="footer-list">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( '法律服务', 'xibufz' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( '律师查询', 'xibufz' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( '人员查询', 'xibufz' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( '会员管理', 'xibufz' ); ?></a></li>
				</ul>
			</div>

			<div>
				<h3 class="footer-title"><?php echo esc_html__( '官方信息', 'xibufz' ); ?></h3>
				<ul class="footer-list">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( '官方微博', 'xibufz' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( '官方微信', 'xibufz' ); ?></a></li>
					<li><?php echo esc_html__( 'ICP备案号：', 'xibufz' ) . esc_html( $footer_record ); ?></li>
					<li><?php echo esc_html__( '主办单位：', 'xibufz' ) . esc_html( $footer_organizer ); ?></li>
					<li><?php echo esc_html__( '官方网址：', 'xibufz' ) . esc_html( $footer_site_url ); ?></li>
				</ul>
			</div>
		</div>

		<div class="mobile-footer-compact">
			<div class="mobile-footer-brand">
				<?php xibufz_site_logo( 'footer' ); ?>
				<div>
					<h3><?php bloginfo( 'name' ); ?></h3>
					<p><?php echo esc_html( get_bloginfo( 'description' ) ? get_bloginfo( 'description' ) : esc_html__( '权威法治资讯与服务平台', 'xibufz' ) ); ?></p>
				</div>
			</div>
			<div class="mobile-footer-linkgrid">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( '网站介绍', 'xibufz' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( '投稿须知', 'xibufz' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( '法律服务', 'xibufz' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( '联系我们', 'xibufz' ); ?></a>
			</div>
			<div class="mobile-footer-meta">
				<div><?php echo esc_html__( 'ICP备案号：', 'xibufz' ) . esc_html( $footer_record ); ?></div>
				<div><?php echo esc_html__( '主办单位：', 'xibufz' ) . esc_html( $footer_organizer ); ?></div>
				<div><?php echo esc_html__( '官方网址：', 'xibufz' ) . esc_html( $footer_site_url ); ?></div>
			</div>
		</div>
	</div>
	<div class="container footer-bottom">
		<?php
		printf(
			/* translators: 1: year, 2: site name. */
			esc_html__( '© %1$s %2$s · 保留所有权利。', 'xibufz' ),
			esc_html( wp_date( 'Y' ) ),
			esc_html( get_bloginfo( 'name' ) )
		);
		?>
	</div>
</footer>

<nav class="mobile-bottom-nav" aria-label="<?php echo esc_attr__( '移动端底部导航', 'xibufz' ); ?>">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="active"><strong>⌂</strong><span><?php echo esc_html__( '首页', 'xibufz' ); ?></span></a>
	<a href="<?php echo esc_url( xibufz_category_url( '综合信息' ) ); ?>"><strong>□</strong><span><?php echo esc_html__( '要闻', 'xibufz' ); ?></span></a>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><strong>⚖</strong><span><?php echo esc_html__( '服务', 'xibufz' ); ?></span></a>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><strong>☏</strong><span><?php echo esc_html__( '联系', 'xibufz' ); ?></span></a>
</nav>

<?php wp_footer(); ?>
</body>
</html>
