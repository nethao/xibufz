<?php
/**
 * Theme setup, assets, menus, and widgets.
 *
 * @package xibufz
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'xibufz_setup' ) ) :
	/**
	 * Configure theme supports.
	 */
	function xibufz_setup() {
		load_theme_textdomain( 'xibufz', get_template_directory() . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'custom-logo', array(
			'height'      => 148,
			'width'       => 148,
			'flex-height' => true,
			'flex-width'  => true,
		) );
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		) );
		add_theme_support( 'automatic-feed-links' );

		register_nav_menus( array(
			'primary' => esc_html__( '主导航菜单', 'xibufz' ),
			'footer'  => esc_html__( '页脚菜单', 'xibufz' ),
		) );
	}
endif;
add_action( 'after_setup_theme', 'xibufz_setup' );

/**
 * Enqueue theme assets.
 */
function xibufz_enqueue_assets() {
	wp_enqueue_style( 'xibufz-main', get_template_directory_uri() . '/assets/css/main.css', array(), XIBUFZ_VERSION );
	wp_enqueue_style( 'xibufz-responsive', get_template_directory_uri() . '/assets/css/responsive.css', array( 'xibufz-main' ), XIBUFZ_VERSION );
	wp_enqueue_script( 'xibufz-theme', get_template_directory_uri() . '/assets/js/theme.js', array(), XIBUFZ_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'xibufz_enqueue_assets' );

/**
 * Register widget areas.
 */
function xibufz_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( '首页侧栏小工具区域', 'xibufz' ),
		'id'            => 'home-sidebar',
		'description'   => esc_html__( '显示在首页侧栏，可用于后续扩展。', 'xibufz' ),
		'before_widget' => '<section id="%1$s" class="card panel widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="panel-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'xibufz_widgets_init' );
