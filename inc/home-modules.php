<?php
/**
 * Home module helpers for the xibufz theme.
 *
 * This file makes the homepage topic modules configurable through theme_mod.
 * Load it before inc/admin.php and before template-parts/sections/modules.php is rendered.
 *
 * @package xibufz
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'xibufz_home_default_category_names' ) ) {
	/**
	 * Default category names used by the homepage.
	 *
	 * @return array
	 */
	function xibufz_home_default_category_names() {
		return array(
			'featured',
			'公告公示',
			'专题专栏',
			'综合信息',
			'法制热点',
			'法律法规',
			'法治理论',
			'普法宣传',
		);
	}
}

if ( ! function_exists( 'xibufz_category_id_by_name' ) ) {
	/**
	 * Resolve a category ID by name or slug.
	 *
	 * @param string $name Category name.
	 * @return int
	 */
	function xibufz_category_id_by_name( $name ) {
		$name = (string) $name;
		if ( '' === trim( $name ) ) {
			return 0;
		}

		$term = get_category_by_slug( sanitize_title( $name ) );
		if ( ! $term ) {
			$term = get_term_by( 'name', $name, 'category' );
		}

		return $term && ! is_wp_error( $term ) ? (int) $term->term_id : 0;
	}
}

if ( ! function_exists( 'xibufz_sort_home_modules' ) ) {
	/**
	 * Sort home module rows by order value.
	 *
	 * @param array $a First module.
	 * @param array $b Second module.
	 * @return int
	 */
	function xibufz_sort_home_modules( $a, $b ) {
		$a_order = isset( $a['order'] ) ? (int) $a['order'] : 0;
		$b_order = isset( $b['order'] ) ? (int) $b['order'] : 0;

		if ( $a_order === $b_order ) {
			return 0;
		}

		return $a_order < $b_order ? -1 : 1;
	}
}

if ( ! function_exists( 'xibufz_sanitize_home_modules' ) ) {
	/**
	 * Sanitize home module rows.
	 *
	 * @param array $raw_modules Raw rows from admin form or theme_mod.
	 * @return array
	 */
	function xibufz_sanitize_home_modules( $raw_modules ) {
		$modules = array();

		if ( ! is_array( $raw_modules ) ) {
			return $modules;
		}

		foreach ( $raw_modules as $raw_module ) {
			if ( ! is_array( $raw_module ) ) {
				continue;
			}

			$title       = isset( $raw_module['title'] ) ? sanitize_text_field( $raw_module['title'] ) : '';
			$category_id = isset( $raw_module['category_id'] ) ? absint( $raw_module['category_id'] ) : 0;

			if ( '' === $title && $category_id ) {
				$category = get_category( $category_id );
				$title    = $category && ! is_wp_error( $category ) ? $category->name : '';
			}

			if ( '' === $title && 0 === $category_id ) {
				continue;
			}

			$style = isset( $raw_module['style'] ) ? sanitize_key( $raw_module['style'] ) : 'default';
			if ( ! in_array( $style, array( 'red', 'default', 'dark' ), true ) ) {
				$style = 'default';
			}

			$count = isset( $raw_module['count'] ) ? absint( $raw_module['count'] ) : 5;
			if ( 1 > $count ) {
				$count = 5;
			}
			if ( 20 < $count ) {
				$count = 20;
			}

			$modules[] = array(
				'show'        => ! empty( $raw_module['show'] ) ? 1 : 0,
				'title'       => $title,
				'category_id' => $category_id,
				'count'       => $count,
				'style'       => $style,
				'order'       => isset( $raw_module['order'] ) ? intval( $raw_module['order'] ) : 0,
			);
		}

		usort( $modules, 'xibufz_sort_home_modules' );

		return $modules;
	}
}

if ( ! function_exists( 'xibufz_default_home_modules' ) ) {
	/**
	 * Default homepage topic module configuration.
	 *
	 * @return array
	 */
	function xibufz_default_home_modules() {
		$modules = array(
			array(
				'title'         => __( '综合信息', 'xibufz' ),
				'style'         => 'red',
				'category_name' => '综合信息',
			),
			array(
				'title'         => __( '法制热点', 'xibufz' ),
				'style'         => 'default',
				'category_name' => '法制热点',
			),
			array(
				'title'         => __( '法律法规', 'xibufz' ),
				'style'         => 'default',
				'category_name' => '法律法规',
			),
			array(
				'title'         => __( '专题专栏', 'xibufz' ),
				'style'         => 'default',
				'category_name' => '专题专栏',
			),
			array(
				'title'         => __( '法治理论', 'xibufz' ),
				'style'         => 'dark',
				'category_name' => '法治理论',
			),
			array(
				'title'         => __( '普法宣传', 'xibufz' ),
				'style'         => 'dark',
				'category_name' => '普法宣传',
			),
		);

		foreach ( $modules as $index => $module ) {
			$modules[ $index ]['show']        = 1;
			$modules[ $index ]['category_id'] = xibufz_category_id_by_name( $module['category_name'] );
			$modules[ $index ]['count']       = 5;
			$modules[ $index ]['order']       = ( $index + 1 ) * 10;
			unset( $modules[ $index ]['category_name'] );
		}

		return $modules;
	}
}

if ( ! function_exists( 'xibufz_get_home_modules' ) ) {
	/**
	 * Get configured homepage topic modules.
	 *
	 * @param bool $include_hidden Whether hidden modules should be returned.
	 * @return array
	 */
	function xibufz_get_home_modules( $include_hidden = false ) {
		$modules = get_theme_mod( 'xibufz_home_modules', array() );

		if ( ! is_array( $modules ) || empty( $modules ) ) {
			$modules = xibufz_default_home_modules();
		} else {
			$modules = xibufz_sanitize_home_modules( $modules );
			if ( empty( $modules ) ) {
				$modules = xibufz_default_home_modules();
			}
		}

		if ( $include_hidden ) {
			return $modules;
		}

		$visible = array();
		foreach ( $modules as $module ) {
			if ( ! empty( $module['show'] ) ) {
				$visible[] = $module;
			}
		}

		return $visible;
	}
}

if ( ! function_exists( 'xibufz_home_module_style_class' ) ) {
	/**
	 * Convert module style value to frontend CSS class.
	 *
	 * @param string $style Style value.
	 * @return string
	 */
	function xibufz_home_module_style_class( $style ) {
		$style = sanitize_key( $style );

		if ( 'red' === $style ) {
			return 'red';
		}

		if ( 'dark' === $style ) {
			return 'dark';
		}

		return '';
	}
}

if ( ! function_exists( 'xibufz_query_by_category_id' ) ) {
	/**
	 * Query posts by category ID.
	 *
	 * @param int   $category_id Category ID.
	 * @param int   $count Number of posts.
	 * @param array $extra_args Extra WP_Query args.
	 * @return WP_Query
	 */
	function xibufz_query_by_category_id( $category_id, $count = 5, $extra_args = array() ) {
		$category_id = absint( $category_id );
		$count       = absint( $count );

		if ( 1 > $count ) {
			$count = 5;
		}

		$args = array(
			'posts_per_page'      => $count,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		);

		if ( $category_id ) {
			$args['cat'] = $category_id;
		} else {
			$args['post__in'] = array( 0 );
		}

		return new WP_Query( array_merge( $args, $extra_args ) );
	}
}

if ( ! function_exists( 'xibufz_category_url_by_id' ) ) {
	/**
	 * Get category URL by category ID.
	 *
	 * @param int $category_id Category ID.
	 * @return string
	 */
	function xibufz_category_url_by_id( $category_id ) {
		$category_id = absint( $category_id );
		if ( ! $category_id ) {
			return home_url( '/' );
		}

		$url = get_category_link( $category_id );
		return $url && ! is_wp_error( $url ) ? $url : home_url( '/' );
	}
}

if ( ! function_exists( 'xibufz_default_home_sidebar_panels' ) ) {
	/**
	 * Default homepage sidebar panel configuration.
	 *
	 * @return array
	 */
	function xibufz_default_home_sidebar_panels() {
		return array(
			array(
				'key'          => 'popular',
				'area'         => 'hero',
				'show'         => 1,
				'title'        => __( '热门文章', 'xibufz' ),
				'source'       => 'category',
				'category_id'  => xibufz_category_id_by_name( 'featured' ),
				'fallback'     => 'latest',
				'count'        => 5,
				'order'        => 10,
				'panel_style'  => 'default',
				'display_style' => 'rank',
			),
			array(
				'key'          => 'notice',
				'area'         => 'hero',
				'show'         => 1,
				'title'        => __( '公告信息', 'xibufz' ),
				'source'       => 'category',
				'category_id'  => xibufz_category_id_by_name( '公告公示' ),
				'fallback'     => 'empty',
				'count'        => 4,
				'order'        => 20,
				'panel_style'  => 'notice',
				'display_style' => 'notice',
			),
			array(
				'key'          => 'topics',
				'area'         => 'hero',
				'show'         => 1,
				'title'        => __( '专题推荐', 'xibufz' ),
				'source'       => 'category',
				'category_id'  => xibufz_category_id_by_name( '专题专栏' ),
				'fallback'     => 'empty',
				'count'        => 3,
				'order'        => 30,
				'panel_style'  => 'default',
				'display_style' => 'news',
			),
			array(
				'key'          => 'site_notice',
				'area'         => 'lower',
				'show'         => 1,
				'title'        => __( '站务公告', 'xibufz' ),
				'source'       => 'category',
				'category_id'  => xibufz_category_id_by_name( '公告公示' ),
				'fallback'     => 'empty',
				'count'        => 3,
				'order'        => 40,
				'panel_style'  => 'notice',
				'display_style' => 'notice',
			),
			array(
				'key'          => 'categories',
				'area'         => 'lower',
				'show'         => 1,
				'title'        => __( '分类', 'xibufz' ),
				'source'       => 'categories',
				'category_id'  => 0,
				'fallback'     => 'empty',
				'count'        => 8,
				'order'        => 50,
				'panel_style'  => 'default',
				'display_style' => 'categories',
			),
			array(
				'key'          => 'archives',
				'area'         => 'lower',
				'show'         => 1,
				'title'        => __( '归档', 'xibufz' ),
				'source'       => 'archives',
				'category_id'  => 0,
				'fallback'     => 'empty',
				'count'        => 8,
				'order'        => 60,
				'panel_style'  => 'default',
				'display_style' => 'archives',
			),
		);
	}
}

if ( ! function_exists( 'xibufz_sanitize_home_sidebar_panels' ) ) {
	/**
	 * Sanitize homepage sidebar panel rows.
	 *
	 * @param array $raw_panels Raw panel rows.
	 * @return array
	 */
	function xibufz_sanitize_home_sidebar_panels( $raw_panels ) {
		$panels = array();

		if ( ! is_array( $raw_panels ) ) {
			return $panels;
		}

		$allowed_areas         = array( 'hero', 'lower' );
		$allowed_sources       = array( 'category', 'latest', 'categories', 'archives' );
		$allowed_fallbacks     = array( 'empty', 'latest' );
		$allowed_panel_styles  = array( 'default', 'notice' );
		$allowed_display_styles = array( 'rank', 'notice', 'news', 'categories', 'archives' );

		foreach ( $raw_panels as $raw_panel ) {
			if ( ! is_array( $raw_panel ) ) {
				continue;
			}

			$key = isset( $raw_panel['key'] ) ? sanitize_key( $raw_panel['key'] ) : '';
			if ( '' === $key ) {
				continue;
			}

			$area = isset( $raw_panel['area'] ) ? sanitize_key( $raw_panel['area'] ) : 'hero';
			if ( ! in_array( $area, $allowed_areas, true ) ) {
				$area = 'hero';
			}

			$source = isset( $raw_panel['source'] ) ? sanitize_key( $raw_panel['source'] ) : 'category';
			if ( ! in_array( $source, $allowed_sources, true ) ) {
				$source = 'category';
			}

			$fallback = isset( $raw_panel['fallback'] ) ? sanitize_key( $raw_panel['fallback'] ) : 'empty';
			if ( ! in_array( $fallback, $allowed_fallbacks, true ) ) {
				$fallback = 'empty';
			}

			$panel_style = isset( $raw_panel['panel_style'] ) ? sanitize_key( $raw_panel['panel_style'] ) : 'default';
			if ( ! in_array( $panel_style, $allowed_panel_styles, true ) ) {
				$panel_style = 'default';
			}

			$display_style = isset( $raw_panel['display_style'] ) ? sanitize_key( $raw_panel['display_style'] ) : 'news';
			if ( ! in_array( $display_style, $allowed_display_styles, true ) ) {
				$display_style = 'news';
			}

			$count = isset( $raw_panel['count'] ) ? absint( $raw_panel['count'] ) : 5;
			if ( 1 > $count ) {
				$count = 5;
			}
			if ( 20 < $count ) {
				$count = 20;
			}

			$panels[] = array(
				'key'           => $key,
				'area'          => $area,
				'show'          => ! empty( $raw_panel['show'] ) ? 1 : 0,
				'title'         => isset( $raw_panel['title'] ) ? sanitize_text_field( $raw_panel['title'] ) : '',
				'source'        => $source,
				'category_id'   => isset( $raw_panel['category_id'] ) ? absint( $raw_panel['category_id'] ) : 0,
				'fallback'      => $fallback,
				'count'         => $count,
				'order'         => isset( $raw_panel['order'] ) ? intval( $raw_panel['order'] ) : 0,
				'panel_style'   => $panel_style,
				'display_style' => $display_style,
			);
		}

		usort( $panels, 'xibufz_sort_home_modules' );

		return $panels;
	}
}

if ( ! function_exists( 'xibufz_get_home_sidebar_panels' ) ) {
	/**
	 * Get configured homepage sidebar panels.
	 *
	 * @param string $area Sidebar area, hero or lower.
	 * @param bool   $include_hidden Whether hidden panels should be included.
	 * @return array
	 */
	function xibufz_get_home_sidebar_panels( $area = '', $include_hidden = false ) {
		$panels = get_theme_mod( 'xibufz_home_sidebar_panels', array() );

		if ( ! is_array( $panels ) || empty( $panels ) ) {
			$panels = xibufz_default_home_sidebar_panels();
		} else {
			$panels = xibufz_sanitize_home_sidebar_panels( $panels );
			if ( empty( $panels ) ) {
				$panels = xibufz_default_home_sidebar_panels();
			}
		}

		$filtered = array();
		foreach ( $panels as $panel ) {
			if ( '' !== $area && $panel['area'] !== $area ) {
				continue;
			}
			if ( ! $include_hidden && empty( $panel['show'] ) ) {
				continue;
			}
			$filtered[] = $panel;
		}

		return $filtered;
	}
}

if ( ! function_exists( 'xibufz_sidebar_panel_more_url' ) ) {
	/**
	 * Get a sidebar panel more URL.
	 *
	 * @param array $panel Panel config.
	 * @return string
	 */
	function xibufz_sidebar_panel_more_url( $panel ) {
		if ( 'category' === $panel['source'] && ! empty( $panel['category_id'] ) ) {
			return xibufz_category_url_by_id( $panel['category_id'] );
		}

		if ( 'categories' === $panel['source'] ) {
			return home_url( '/' );
		}

		$posts_page_id = (int) get_option( 'page_for_posts' );
		return $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/' );
	}
}
