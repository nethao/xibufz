<?php
/**
 * Reusable template helpers.
 *
 * @package xibufz
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get a theme mod with a translated fallback.
 *
 * @param string $name    Theme mod name.
 * @param string $default Default value.
 * @return string
 */
function xibufz_mod( $name, $default = '' ) {
	return get_theme_mod( $name, $default );
}

/**
 * Return the category link by name or home URL when missing.
 *
 * @param string $name Category name.
 * @return string
 */
function xibufz_category_url( $name ) {
	$term = get_category_by_slug( sanitize_title( $name ) );

	if ( ! $term ) {
		$term = get_term_by( 'name', $name, 'category' );
	}

	if ( $term && ! is_wp_error( $term ) ) {
		return get_category_link( $term );
	}

	return home_url( '/' );
}

/**
 * Build a WP_Query by category name with fallback to latest posts.
 *
 * @param string $category_name Category name.
 * @param int    $count         Number of posts.
 * @param array  $extra_args    Extra query args.
 * @return WP_Query
 */
function xibufz_query_by_category( $category_name, $count = 5, $extra_args = array() ) {
	$args = array(
		'posts_per_page'      => absint( $count ),
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	$term = get_category_by_slug( sanitize_title( $category_name ) );
	if ( ! $term ) {
		$term = get_term_by( 'name', $category_name, 'category' );
	}

	if ( $term && ! is_wp_error( $term ) ) {
		$args['cat'] = (int) $term->term_id;
	}

	$query = new WP_Query( array_merge( $args, $extra_args ) );

	if ( ! $query->have_posts() && isset( $args['cat'] ) ) {
		wp_reset_postdata();
		unset( $args['cat'] );
		$query = new WP_Query( array_merge( $args, $extra_args ) );
	}

	return $query;
}

/**
 * Echo an accessible logo fallback.
 *
 * @param string $context Class context.
 */
function xibufz_site_logo( $context = 'brand' ) {
	if ( has_custom_logo() ) {
		the_custom_logo();
		return;
	}

	$class = 'logo-box';
	if ( 'footer' === $context ) {
		$class .= ' footer-logo-box';
	}

	printf(
		'<a class="%1$s" href="%2$s" aria-label="%3$s">%4$s</a>',
		esc_attr( $class ),
		esc_url( home_url( '/' ) ),
		esc_attr( get_bloginfo( 'name' ) ),
		esc_html__( '法', 'xibufz' )
	);
}

/**
 * Echo post thumbnail background or placeholder.
 *
 * @param int    $post_id Post ID.
 * @param string $class   Element class.
 * @param string $size    Image size.
 */
function xibufz_post_thumb_box( $post_id, $class = 'module-thumb', $size = 'medium_large' ) {
	$url = get_the_post_thumbnail_url( $post_id, $size );

	if ( $url ) {
		printf(
			'<span class="%1$s has-image"><img src="%2$s" alt="%3$s" loading="lazy"></span>',
			esc_attr( $class ),
			esc_url( $url ),
			esc_attr( get_the_title( $post_id ) )
		);
		return;
	}

	printf(
		'<span class="%1$s thumb-placeholder" aria-hidden="true"><span>%2$s</span></span>',
		esc_attr( $class ),
		esc_html__( '西部法制', 'xibufz' )
	);
}

/**
 * Format a post date.
 *
 * @param int|null $post_id Post ID.
 * @return string
 */
function xibufz_post_date( $post_id = null ) {
	return get_the_date( 'Y-m-d', $post_id );
}

/**
 * Parse newline separated links. Format: label|url.
 *
 * @param string $text Raw text.
 * @return array
 */
function xibufz_parse_links( $text ) {
	$links = array();
	$rows  = preg_split( '/\r\n|\r|\n/', (string) $text );

	foreach ( $rows as $row ) {
		$row = trim( $row );
		if ( '' === $row ) {
			continue;
		}

		$parts = array_map( 'trim', explode( '|', $row, 2 ) );
		$links[] = array(
			'label' => $parts[0],
			'url'   => isset( $parts[1] ) && '' !== $parts[1] ? $parts[1] : home_url( '/' ),
		);
	}

	return $links;
}

/**
 * Default services.
 *
 * @return array
 */
function xibufz_default_services() {
	return array(
		array( 'icon' => '✎', 'title' => esc_html__( '投稿入口', 'xibufz' ), 'desc' => esc_html__( '提交新闻线索与稿件内容', 'xibufz' ), 'url' => home_url( '/' ) ),
		array( 'icon' => '⚖', 'title' => esc_html__( '法律援助', 'xibufz' ), 'desc' => esc_html__( '查看援助指引与服务说明', 'xibufz' ), 'url' => home_url( '/' ) ),
		array( 'icon' => '□', 'title' => esc_html__( '律师查询', 'xibufz' ), 'desc' => esc_html__( '快速查找机构与执业信息', 'xibufz' ), 'url' => home_url( '/' ) ),
		array( 'icon' => '○', 'title' => esc_html__( '人员查询', 'xibufz' ), 'desc' => esc_html__( '支持站内业务信息检索', 'xibufz' ), 'url' => home_url( '/' ) ),
	);
}

/**
 * Echo menu fallback links.
 *
 * @param string $class Menu class.
 */
function xibufz_menu_fallback( $class = 'nav-list' ) {
	if ( is_object( $class ) && isset( $class->menu_class ) ) {
		$class = $class->menu_class;
	}

	$items = array(
		esc_html__( '首页', 'xibufz' )     => home_url( '/' ),
		esc_html__( '综合信息', 'xibufz' ) => xibufz_category_url( '综合信息' ),
		esc_html__( '法制热点', 'xibufz' ) => xibufz_category_url( '法制热点' ),
		esc_html__( '法律法规', 'xibufz' ) => xibufz_category_url( '法律法规' ),
		esc_html__( '公告公示', 'xibufz' ) => xibufz_category_url( '公告公示' ),
		esc_html__( '专题专栏', 'xibufz' ) => xibufz_category_url( '专题专栏' ),
	);

	echo '<ul class="' . esc_attr( $class ) . '">';
	foreach ( $items as $label => $url ) {
		printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * Render a compact empty state.
 */
function xibufz_empty_state() {
	echo '<p class="empty-state">' . esc_html__( '暂无内容，请稍后查看。', 'xibufz' ) . '</p>';
}
