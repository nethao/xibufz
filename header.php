<?php
/**
 * Header template.
 *
 * @package xibufz
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="mobile-header">
	<div class="container mobile-header-inner">
		<div class="mobile-brand">
			<?php xibufz_site_logo(); ?>
			<div class="mobile-brand-text">
				<a class="mobile-brand-title" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
				<div class="mobile-brand-sub"><?php echo esc_html( get_bloginfo( 'description' ) ? get_bloginfo( 'description' ) : esc_html__( '法治资讯与服务平台', 'xibufz' ) ); ?></div>
			</div>
		</div>
		<div class="mobile-actions">
			<button class="mobile-icon-btn mobile-search-toggle" type="button" aria-expanded="false" aria-controls="mobile-search-panel" aria-label="<?php echo esc_attr__( '搜索', 'xibufz' ); ?>">⌕</button>
			<button class="mobile-icon-btn mobile-nav-toggle" type="button" aria-expanded="false" aria-controls="mobile-menu-panel" aria-label="<?php echo esc_attr__( '菜单', 'xibufz' ); ?>">☰</button>
		</div>
	</div>
	<div class="mobile-panel mobile-search-panel" id="mobile-search-panel">
		<div class="container"><?php get_search_form(); ?></div>
	</div>
	<nav class="mobile-panel mobile-menu-panel" id="mobile-menu-panel" aria-label="<?php echo esc_attr__( '移动端菜单', 'xibufz' ); ?>">
		<div class="container">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'menu_class'     => 'mobile-menu-list',
				'container'      => false,
				'fallback_cb'    => 'xibufz_menu_fallback',
			) );
			?>
		</div>
	</nav>
</header>

<div class="topbar">
	<div class="container topbar-inner">
		<div class="meta">
			<span><?php echo esc_html__( '欢迎访问西部法制网', 'xibufz' ); ?></span>
			<span>
				<?php
				printf(
					/* translators: %s: current date. */
					esc_html__( '今天是 %s', 'xibufz' ),
					esc_html( wp_date( 'Y年n月j日 l' ) )
				);
				?>
			</span>
		</div>
		<div class="links">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( '投稿入口', 'xibufz' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( '联系我们', 'xibufz' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( '网站地图', 'xibufz' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( '设为首页', 'xibufz' ); ?></a>
		</div>
	</div>
</div>

<header class="brand">
	<div class="container brand-inner">
		<div class="brand-left">
			<?php xibufz_site_logo(); ?>
			<div>
				<?php if ( is_front_page() && is_home() ) : ?>
					<h1 class="brand-title"><?php bloginfo( 'name' ); ?></h1>
				<?php else : ?>
					<div class="brand-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></div>
				<?php endif; ?>
				<div class="brand-sub"><?php echo esc_html( get_bloginfo( 'description' ) ? get_bloginfo( 'description' ) : esc_html__( '西部法治资讯与服务平台 · 权威 · 清晰 · 专业', 'xibufz' ) ); ?></div>
			</div>
		</div>
		<div class="brand-right">
			<?php get_search_form(); ?>
			<a class="ghost-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( '官方微信', 'xibufz' ); ?></a>
			<a class="solid-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( '在线投稿', 'xibufz' ); ?></a>
		</div>
	</div>
</header>

<nav class="nav" aria-label="<?php echo esc_attr__( '主导航', 'xibufz' ); ?>">
	<div class="container">
		<?php
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'menu_class'     => 'nav-list',
			'container'      => false,
			'fallback_cb'    => 'xibufz_menu_fallback',
		) );
		?>
	</div>
</nav>
