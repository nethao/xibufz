<?php
/**
 * Built-in admin management panel for the xibufz theme.
 *
 * The panel writes to theme_mod values so the existing theme templates and
 * Customizer settings can keep working together.
 *
 * @package xibufz
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'XIBUFZ_ADMIN_SLUG' ) ) {
	define( 'XIBUFZ_ADMIN_SLUG', 'xibufz-management' );
}

/**
 * Register admin menu pages.
 */
function xibufz_admin_menu() {
	add_menu_page(
		esc_html__( '西部法制管理', 'xibufz' ),
		esc_html__( '西部法制管理', 'xibufz' ),
		'edit_theme_options',
		XIBUFZ_ADMIN_SLUG,
		'xibufz_admin_overview_page',
		'dashicons-layout',
		61
	);

	add_submenu_page(
		XIBUFZ_ADMIN_SLUG,
		esc_html__( '管理概览', 'xibufz' ),
		esc_html__( '管理概览', 'xibufz' ),
		'edit_theme_options',
		XIBUFZ_ADMIN_SLUG,
		'xibufz_admin_overview_page'
	);

	add_submenu_page(
		XIBUFZ_ADMIN_SLUG,
		esc_html__( '首页 Banner', 'xibufz' ),
		esc_html__( '首页 Banner', 'xibufz' ),
		'edit_theme_options',
		'xibufz-home-banner',
		'xibufz_admin_banner_page'
	);

	add_submenu_page(
		XIBUFZ_ADMIN_SLUG,
		esc_html__( '首页栏目管理', 'xibufz' ),
		esc_html__( '首页栏目管理', 'xibufz' ),
		'edit_theme_options',
		'xibufz-home-modules',
		'xibufz_admin_home_modules_page'
	);

	add_submenu_page(
		XIBUFZ_ADMIN_SLUG,
		esc_html__( '便民服务', 'xibufz' ),
		esc_html__( '便民服务', 'xibufz' ),
		'edit_theme_options',
		'xibufz-services',
		'xibufz_admin_services_page'
	);

	add_submenu_page(
		XIBUFZ_ADMIN_SLUG,
		esc_html__( '友情链接', 'xibufz' ),
		esc_html__( '友情链接', 'xibufz' ),
		'edit_theme_options',
		'xibufz-partners',
		'xibufz_admin_partners_page'
	);

	add_submenu_page(
		XIBUFZ_ADMIN_SLUG,
		esc_html__( '页脚信息', 'xibufz' ),
		esc_html__( '页脚信息', 'xibufz' ),
		'edit_theme_options',
		'xibufz-footer',
		'xibufz_admin_footer_page'
	);

	add_submenu_page(
		XIBUFZ_ADMIN_SLUG,
		esc_html__( '内容工具', 'xibufz' ),
		esc_html__( '内容工具', 'xibufz' ),
		'edit_theme_options',
		'xibufz-tools',
		'xibufz_admin_tools_page'
	);
}
add_action( 'admin_menu', 'xibufz_admin_menu' );

/**
 * Enqueue admin assets only on xibufz admin pages.
 *
 * @param string $hook Current admin hook.
 */
function xibufz_admin_enqueue_assets( $hook ) {
	$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( 0 !== strpos( $page, 'xibufz' ) ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_style(
		'xibufz-admin',
		get_template_directory_uri() . '/assets/css/admin.css',
		array(),
		defined( 'XIBUFZ_VERSION' ) ? XIBUFZ_VERSION : '1.0.0'
	);
	wp_enqueue_script(
		'xibufz-admin',
		get_template_directory_uri() . '/assets/js/admin.js',
		array( 'jquery' ),
		defined( 'XIBUFZ_VERSION' ) ? XIBUFZ_VERSION : '1.0.0',
		true
	);
}
add_action( 'admin_enqueue_scripts', 'xibufz_admin_enqueue_assets' );

/**
 * Save admin forms.
 */
function xibufz_admin_maybe_save() {
	if ( ! is_admin() || ! isset( $_POST['xibufz_admin_action'] ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( '您没有权限执行此操作。', 'xibufz' ) );
	}

	$action = sanitize_key( wp_unslash( $_POST['xibufz_admin_action'] ) );
	check_admin_referer( 'xibufz_admin_' . $action, 'xibufz_admin_nonce' );

	switch ( $action ) {
		case 'save_banner':
			xibufz_admin_save_banner();
			xibufz_admin_redirect( 'xibufz-home-banner' );
			break;
		case 'save_home_modules':
			xibufz_admin_save_home_modules();
			xibufz_admin_redirect( 'xibufz-home-modules' );
			break;
		case 'save_services':
			xibufz_admin_save_services();
			xibufz_admin_redirect( 'xibufz-services' );
			break;
		case 'save_partners':
			xibufz_admin_save_partners();
			xibufz_admin_redirect( 'xibufz-partners' );
			break;
		case 'save_footer':
			xibufz_admin_save_footer();
			xibufz_admin_redirect( 'xibufz-footer' );
			break;
		case 'create_categories':
			xibufz_admin_create_default_categories();
			xibufz_admin_redirect( 'xibufz-tools' );
			break;
		case 'create_pages':
			xibufz_admin_create_default_pages();
			xibufz_admin_redirect( 'xibufz-tools' );
			break;
		case 'create_menus':
			xibufz_admin_create_default_menus();
			xibufz_admin_redirect( 'xibufz-tools' );
			break;
		case 'reset_home_modules':
			set_theme_mod( 'xibufz_home_modules', xibufz_default_home_modules() );
			xibufz_admin_redirect( 'xibufz-home-modules' );
			break;
		case 'init_all':
			xibufz_admin_create_default_categories();
			xibufz_admin_create_default_pages();
			xibufz_admin_create_default_menus();
			xibufz_admin_write_default_theme_mods();
			xibufz_admin_redirect( 'xibufz-tools' );
			break;
		default:
			xibufz_admin_redirect( XIBUFZ_ADMIN_SLUG );
			break;
	}
}
add_action( 'admin_init', 'xibufz_admin_maybe_save' );

/**
 * Redirect after saving.
 *
 * @param string $page Page slug.
 */
function xibufz_admin_redirect( $page ) {
	wp_safe_redirect(
		add_query_arg(
			array(
				'page'          => $page,
				'xibufz_updated' => '1',
			),
			admin_url( 'admin.php' )
		)
	);
	exit;
}

/**
 * Save banner fields.
 */
function xibufz_admin_save_banner() {
	set_theme_mod( 'xibufz_banner_image', isset( $_POST['xibufz_banner_image'] ) ? esc_url_raw( wp_unslash( $_POST['xibufz_banner_image'] ) ) : '' );
	set_theme_mod( 'xibufz_banner_kicker', isset( $_POST['xibufz_banner_kicker'] ) ? sanitize_text_field( wp_unslash( $_POST['xibufz_banner_kicker'] ) ) : '' );
	set_theme_mod( 'xibufz_banner_title', isset( $_POST['xibufz_banner_title'] ) ? sanitize_text_field( wp_unslash( $_POST['xibufz_banner_title'] ) ) : '' );
	set_theme_mod( 'xibufz_banner_subtitle', isset( $_POST['xibufz_banner_subtitle'] ) ? sanitize_textarea_field( wp_unslash( $_POST['xibufz_banner_subtitle'] ) ) : '' );
	set_theme_mod( 'xibufz_banner_url', isset( $_POST['xibufz_banner_url'] ) ? esc_url_raw( wp_unslash( $_POST['xibufz_banner_url'] ) ) : '' );
}

/**
 * Save home modules.
 */
function xibufz_admin_save_home_modules() {
	$raw_modules = isset( $_POST['xibufz_home_modules'] ) && is_array( $_POST['xibufz_home_modules'] ) ? wp_unslash( $_POST['xibufz_home_modules'] ) : array();
	$modules     = xibufz_sanitize_home_modules( $raw_modules );
	set_theme_mod( 'xibufz_home_modules', $modules );
}

/**
 * Save service cards. Keeps compatibility with current theme_mod field names.
 */
function xibufz_admin_save_services() {
	for ( $i = 1; $i <= 4; $i++ ) {
		$prefix = 'xibufz_service_' . $i . '_';
		set_theme_mod( $prefix . 'icon', isset( $_POST[ $prefix . 'icon' ] ) ? sanitize_text_field( wp_unslash( $_POST[ $prefix . 'icon' ] ) ) : '' );
		set_theme_mod( $prefix . 'title', isset( $_POST[ $prefix . 'title' ] ) ? sanitize_text_field( wp_unslash( $_POST[ $prefix . 'title' ] ) ) : '' );
		set_theme_mod( $prefix . 'desc', isset( $_POST[ $prefix . 'desc' ] ) ? sanitize_text_field( wp_unslash( $_POST[ $prefix . 'desc' ] ) ) : '' );
		set_theme_mod( $prefix . 'url', isset( $_POST[ $prefix . 'url' ] ) ? esc_url_raw( wp_unslash( $_POST[ $prefix . 'url' ] ) ) : '' );
	}
}

/**
 * Save partner links as newline separated label|url text for compatibility.
 */
function xibufz_admin_save_partners() {
	$text_links = xibufz_admin_links_rows_to_text( isset( $_POST['xibufz_partner_text_links_rows'] ) ? wp_unslash( $_POST['xibufz_partner_text_links_rows'] ) : array() );
	$logo_links = xibufz_admin_links_rows_to_text( isset( $_POST['xibufz_partner_logo_links_rows'] ) ? wp_unslash( $_POST['xibufz_partner_logo_links_rows'] ) : array() );

	set_theme_mod( 'xibufz_partner_text_links', $text_links );
	set_theme_mod( 'xibufz_partner_logo_links', $logo_links );
}

/**
 * Save footer fields.
 */
function xibufz_admin_save_footer() {
	set_theme_mod( 'xibufz_footer_desc', isset( $_POST['xibufz_footer_desc'] ) ? sanitize_textarea_field( wp_unslash( $_POST['xibufz_footer_desc'] ) ) : '' );
	set_theme_mod( 'xibufz_footer_record', isset( $_POST['xibufz_footer_record'] ) ? sanitize_text_field( wp_unslash( $_POST['xibufz_footer_record'] ) ) : '' );
	set_theme_mod( 'xibufz_footer_organizer', isset( $_POST['xibufz_footer_organizer'] ) ? sanitize_text_field( wp_unslash( $_POST['xibufz_footer_organizer'] ) ) : '' );
	set_theme_mod( 'xibufz_footer_site_url', isset( $_POST['xibufz_footer_site_url'] ) ? esc_url_raw( wp_unslash( $_POST['xibufz_footer_site_url'] ) ) : '' );
}

/**
 * Convert link rows to label|url text.
 *
 * @param array $rows Submitted rows.
 * @return string
 */
function xibufz_admin_links_rows_to_text( $rows ) {
	$lines = array();
	if ( ! is_array( $rows ) ) {
		return '';
	}

	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}
		$label = isset( $row['label'] ) ? sanitize_text_field( $row['label'] ) : '';
		$url   = isset( $row['url'] ) ? esc_url_raw( $row['url'] ) : '';
		if ( '' === $label && '' === $url ) {
			continue;
		}
		$lines[] = $label . '|' . $url;
	}

	return implode( "\n", $lines );
}

/**
 * Create default categories if missing.
 */
function xibufz_admin_create_default_categories() {
	foreach ( xibufz_home_default_category_names() as $category_name ) {
		if ( ! term_exists( $category_name, 'category' ) ) {
			wp_insert_term( $category_name, 'category' );
		}
	}
}

/**
 * Create default pages if missing.
 */
function xibufz_admin_create_default_pages() {
	$pages = array(
		'网站介绍',
		'投稿须知',
		'联系我们',
		'隐私政策',
		'法律服务',
		'律师查询',
		'人员查询',
		'在线投稿',
	);

	foreach ( $pages as $page_title ) {
		$page = get_page_by_title( $page_title );
		if ( $page ) {
			continue;
		}
		wp_insert_post(
			array(
				'post_title'   => $page_title,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => sprintf( '这里是“%s”页面，请在后台编辑完善内容。', $page_title ),
			)
		);
	}
}

/**
 * Create and assign default menus.
 */
function xibufz_admin_create_default_menus() {
	$locations = get_theme_mod( 'nav_menu_locations', array() );

	$primary_menu_id = xibufz_admin_get_or_create_menu( '西部法制网主导航' );
	$footer_menu_id  = xibufz_admin_get_or_create_menu( '西部法制网页脚菜单' );

	$primary_items = array(
		'首页'     => home_url( '/' ),
		'综合信息' => xibufz_category_url_by_id( xibufz_category_id_by_name( '综合信息' ) ),
		'法制热点' => xibufz_category_url_by_id( xibufz_category_id_by_name( '法制热点' ) ),
		'法律法规' => xibufz_category_url_by_id( xibufz_category_id_by_name( '法律法规' ) ),
		'公告公示' => xibufz_category_url_by_id( xibufz_category_id_by_name( '公告公示' ) ),
		'专题专栏' => xibufz_category_url_by_id( xibufz_category_id_by_name( '专题专栏' ) ),
	);

	$footer_items = array(
		'网站介绍' => home_url( '/' ),
		'投稿须知' => home_url( '/' ),
		'法律服务' => home_url( '/' ),
		'联系我们' => home_url( '/' ),
	);

	xibufz_admin_seed_menu_items( $primary_menu_id, $primary_items );
	xibufz_admin_seed_menu_items( $footer_menu_id, $footer_items );

	if ( $primary_menu_id ) {
		$locations['primary'] = $primary_menu_id;
	}
	if ( $footer_menu_id ) {
		$locations['footer'] = $footer_menu_id;
	}

	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Get or create menu by name.
 *
 * @param string $name Menu name.
 * @return int
 */
function xibufz_admin_get_or_create_menu( $name ) {
	$menu = wp_get_nav_menu_object( $name );
	if ( $menu ) {
		return (int) $menu->term_id;
	}

	$menu_id = wp_create_nav_menu( $name );
	return is_wp_error( $menu_id ) ? 0 : (int) $menu_id;
}

/**
 * Seed menu items if menu is empty.
 *
 * @param int   $menu_id Menu ID.
 * @param array $items Menu items.
 */
function xibufz_admin_seed_menu_items( $menu_id, $items ) {
	$menu_id = absint( $menu_id );
	if ( ! $menu_id ) {
		return;
	}

	$existing = wp_get_nav_menu_items( $menu_id );
	if ( ! empty( $existing ) ) {
		return;
	}

	foreach ( $items as $label => $url ) {
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'  => $label,
				'menu-item-url'    => $url,
				'menu-item-status' => 'publish',
				'menu-item-type'   => 'custom',
			)
		);
	}
}

/**
 * Write safe default theme mods without overwriting non-empty values.
 */
function xibufz_admin_write_default_theme_mods() {
	$defaults = array(
		'xibufz_banner_kicker'       => '焦点专题',
		'xibufz_banner_title'        => '法治中国建设纵深推进，构建更清晰的资讯门户首页',
		'xibufz_banner_subtitle'     => '这里用于展示重大新闻、专题报道和重点宣传内容。',
		'xibufz_banner_url'          => home_url( '/' ),
		'xibufz_footer_desc'         => '西部法制网致力于打造权威、清晰、专业的法治资讯与服务门户。',
		'xibufz_footer_record'       => '陕ICP备2025000000号',
		'xibufz_footer_organizer'    => '西部法制网',
		'xibufz_footer_site_url'     => home_url( '/' ),
		'xibufz_partner_text_links'  => "网站介绍|" . home_url( '/' ) . "\n投稿须知|" . home_url( '/' ) . "\n法律服务|" . home_url( '/' ) . "\n联系我们|" . home_url( '/' ),
		'xibufz_partner_logo_links'  => "陕西法治协作平台|" . home_url( '/' ) . "\n西部政法资讯中心|" . home_url( '/' ) . "\n公共法律服务站|" . home_url( '/' ) . "\n法治传播研究组|" . home_url( '/' ),
	);

	foreach ( $defaults as $key => $value ) {
		$current = get_theme_mod( $key, '' );
		if ( '' === $current || null === $current ) {
			set_theme_mod( $key, $value );
		}
	}

	$services = function_exists( 'xibufz_default_services' ) ? xibufz_default_services() : array(
		array( 'icon' => '✎', 'title' => '投稿入口', 'desc' => '提交新闻线索与稿件内容', 'url' => home_url( '/' ) ),
		array( 'icon' => '⚖', 'title' => '法律援助', 'desc' => '查看援助指引与服务说明', 'url' => home_url( '/' ) ),
		array( 'icon' => '📄', 'title' => '律师查询', 'desc' => '快速查找机构与执业信息', 'url' => home_url( '/' ) ),
		array( 'icon' => '👤', 'title' => '人员查询', 'desc' => '支持站内业务信息检索', 'url' => home_url( '/' ) ),
	);

	foreach ( $services as $index => $service ) {
		$i      = $index + 1;
		$prefix = 'xibufz_service_' . $i . '_';
		foreach ( array( 'icon', 'title', 'desc', 'url' ) as $field ) {
			$key = $prefix . $field;
			if ( '' === get_theme_mod( $key, '' ) ) {
				set_theme_mod( $key, isset( $service[ $field ] ) ? $service[ $field ] : '' );
			}
		}
	}

	if ( ! is_array( get_theme_mod( 'xibufz_home_modules', array() ) ) || empty( get_theme_mod( 'xibufz_home_modules', array() ) ) ) {
		set_theme_mod( 'xibufz_home_modules', xibufz_default_home_modules() );
	}
}

/**
 * Render a save notice.
 */
function xibufz_admin_notice() {
	$updated = isset( $_GET['xibufz_updated'] ) ? sanitize_text_field( wp_unslash( $_GET['xibufz_updated'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( '1' !== $updated ) {
		return;
	}
	?>
	<div class="notice notice-success is-dismissible"><p><?php echo esc_html__( '配置已更新。', 'xibufz' ); ?></p></div>
	<?php
}

/**
 * Open a form.
 *
 * @param string $action Action slug.
 */
function xibufz_admin_form_open( $action ) {
	?>
	<form method="post" action="">
		<input type="hidden" name="xibufz_admin_action" value="<?php echo esc_attr( $action ); ?>">
		<?php wp_nonce_field( 'xibufz_admin_' . $action, 'xibufz_admin_nonce' ); ?>
	<?php
}

/**
 * Render overview page.
 */
function xibufz_admin_overview_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$modules       = xibufz_get_home_modules( true );
	$visible_count = 0;
	foreach ( $modules as $module ) {
		if ( ! empty( $module['show'] ) ) {
			$visible_count++;
		}
	}
	?>
	<div class="wrap xibufz-admin-wrap">
		<h1><?php echo esc_html__( '西部法制管理', 'xibufz' ); ?></h1>
		<?php xibufz_admin_notice(); ?>
		<div class="xibufz-admin-grid">
			<div class="xibufz-admin-card">
				<h2><?php echo esc_html__( '当前状态', 'xibufz' ); ?></h2>
				<ul class="xibufz-status-list">
					<li><strong><?php echo esc_html__( '主题：', 'xibufz' ); ?></strong><?php echo esc_html( wp_get_theme()->get( 'Name' ) ); ?></li>
					<li><strong><?php echo esc_html__( '首页栏目：', 'xibufz' ); ?></strong><?php echo esc_html( sprintf( '已配置 %1$d 个，显示 %2$d 个', count( $modules ), $visible_count ) ); ?></li>
					<li><strong><?php echo esc_html__( '字段保存：', 'xibufz' ); ?></strong><code>xibufz_home_modules</code></li>
				</ul>
			</div>
			<div class="xibufz-admin-card">
				<h2><?php echo esc_html__( '建议操作', 'xibufz' ); ?></h2>
				<ol>
					<li><?php echo esc_html__( '先到“内容工具”创建缺失分类和菜单。', 'xibufz' ); ?></li>
					<li><?php echo esc_html__( '再到“首页栏目管理”绑定分类并调整排序。', 'xibufz' ); ?></li>
					<li><?php echo esc_html__( '最后配置 Banner、便民服务、友情链接和页脚信息。', 'xibufz' ); ?></li>
				</ol>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Render banner page.
 */
function xibufz_admin_banner_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	?>
	<div class="wrap xibufz-admin-wrap">
		<h1><?php echo esc_html__( '首页 Banner', 'xibufz' ); ?></h1>
		<?php xibufz_admin_notice(); ?>
		<div class="xibufz-admin-card">
			<?php xibufz_admin_form_open( 'save_banner' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="xibufz_banner_image"><?php echo esc_html__( 'Banner 图片 URL', 'xibufz' ); ?></label></th>
					<td>
						<input class="regular-text xibufz-media-url" id="xibufz_banner_image" type="url" name="xibufz_banner_image" value="<?php echo esc_attr( get_theme_mod( 'xibufz_banner_image', '' ) ); ?>">
						<button type="button" class="button xibufz-media-button"><?php echo esc_html__( '从媒体库选择', 'xibufz' ); ?></button>
						<p class="description"><?php echo esc_html__( '不填则前台使用默认占位视觉。', 'xibufz' ); ?></p>
					</td>
				</tr>
				<?php xibufz_admin_text_row( 'xibufz_banner_kicker', __( '小标题', 'xibufz' ), get_theme_mod( 'xibufz_banner_kicker', '焦点专题' ) ); ?>
				<?php xibufz_admin_text_row( 'xibufz_banner_title', __( '主标题', 'xibufz' ), get_theme_mod( 'xibufz_banner_title', '' ) ); ?>
				<tr>
					<th scope="row"><label for="xibufz_banner_subtitle"><?php echo esc_html__( '副标题', 'xibufz' ); ?></label></th>
					<td><textarea class="large-text" rows="4" id="xibufz_banner_subtitle" name="xibufz_banner_subtitle"><?php echo esc_textarea( get_theme_mod( 'xibufz_banner_subtitle', '' ) ); ?></textarea></td>
				</tr>
				<?php xibufz_admin_text_row( 'xibufz_banner_url', __( '跳转链接', 'xibufz' ), get_theme_mod( 'xibufz_banner_url', home_url( '/' ) ), 'url' ); ?>
			</table>
			<?php submit_button( esc_html__( '保存 Banner', 'xibufz' ) ); ?>
			</form>
		</div>
	</div>
	<?php
}

/**
 * Render home modules page.
 */
function xibufz_admin_home_modules_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$modules = xibufz_get_home_modules( true );
	$rows    = max( count( $modules ) + 4, 12 );
	$styles  = array(
		'default' => esc_html__( '默认蓝色', 'xibufz' ),
		'red'     => esc_html__( '红色重点', 'xibufz' ),
		'dark'    => esc_html__( '深色稳重', 'xibufz' ),
	);
	?>
	<div class="wrap xibufz-admin-wrap">
		<h1><?php echo esc_html__( '首页栏目管理', 'xibufz' ); ?></h1>
		<?php xibufz_admin_notice(); ?>
		<div class="xibufz-admin-card">
			<p><?php echo esc_html__( '管理首页“资讯专题”区块。留空行不会保存；排序值越小越靠前。', 'xibufz' ); ?></p>
			<?php xibufz_admin_form_open( 'save_home_modules' ); ?>
			<div class="xibufz-table-scroll">
				<table class="widefat striped xibufz-admin-table">
					<thead>
						<tr>
							<th><?php echo esc_html__( '显示', 'xibufz' ); ?></th>
							<th><?php echo esc_html__( '栏目标题', 'xibufz' ); ?></th>
							<th><?php echo esc_html__( '绑定分类', 'xibufz' ); ?></th>
							<th><?php echo esc_html__( '数量', 'xibufz' ); ?></th>
							<th><?php echo esc_html__( '样式', 'xibufz' ); ?></th>
							<th><?php echo esc_html__( '排序', 'xibufz' ); ?></th>
							<th><?php echo esc_html__( '状态', 'xibufz' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php for ( $i = 0; $i < $rows; $i++ ) : ?>
							<?php
							$module = isset( $modules[ $i ] ) ? $modules[ $i ] : array(
								'show'        => 0,
								'title'       => '',
								'category_id' => 0,
								'count'       => 5,
								'style'       => 'default',
								'order'       => ( $i + 1 ) * 10,
							);
							$category = ! empty( $module['category_id'] ) ? get_category( absint( $module['category_id'] ) ) : null;
							$status   = $category && ! is_wp_error( $category ) ? sprintf( '分类文章：%d 篇', (int) $category->count ) : __( '未绑定分类', 'xibufz' );
							?>
							<tr>
								<td><label><input type="checkbox" name="xibufz_home_modules[<?php echo esc_attr( $i ); ?>][show]" value="1" <?php checked( ! empty( $module['show'] ) ); ?>> <?php echo esc_html__( '显示', 'xibufz' ); ?></label></td>
								<td><input class="regular-text" type="text" name="xibufz_home_modules[<?php echo esc_attr( $i ); ?>][title]" value="<?php echo esc_attr( $module['title'] ); ?>"></td>
								<td>
									<?php
									wp_dropdown_categories(
										array(
											'name'              => 'xibufz_home_modules[' . esc_attr( $i ) . '][category_id]',
											'selected'          => absint( $module['category_id'] ),
											'show_option_none'  => esc_html__( '不绑定分类', 'xibufz' ),
											'option_none_value' => 0,
											'hide_empty'        => false,
											'taxonomy'          => 'category',
										)
									);
									?>
								</td>
								<td><input class="small-text" type="number" min="1" max="20" name="xibufz_home_modules[<?php echo esc_attr( $i ); ?>][count]" value="<?php echo esc_attr( $module['count'] ); ?>"></td>
								<td>
									<select name="xibufz_home_modules[<?php echo esc_attr( $i ); ?>][style]">
										<?php foreach ( $styles as $value => $label ) : ?>
											<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $module['style'], $value ); ?>><?php echo esc_html( $label ); ?></option>
										<?php endforeach; ?>
									</select>
								</td>
								<td><input class="small-text" type="number" name="xibufz_home_modules[<?php echo esc_attr( $i ); ?>][order]" value="<?php echo esc_attr( $module['order'] ); ?>"></td>
								<td><span class="xibufz-status-pill"><?php echo esc_html( $status ); ?></span></td>
							</tr>
						<?php endfor; ?>
					</tbody>
				</table>
			</div>
			<?php submit_button( esc_html__( '保存栏目配置', 'xibufz' ) ); ?>
			</form>
			<?php xibufz_admin_form_open( 'reset_home_modules' ); ?>
				<?php submit_button( esc_html__( '恢复默认 6 个栏目', 'xibufz' ), 'secondary', 'submit', false, array( 'data-xibufz-confirm' => esc_attr__( '确定恢复默认首页栏目吗？', 'xibufz' ) ) ); ?>
			</form>
		</div>
	</div>
	<?php
}

/**
 * Render services page.
 */
function xibufz_admin_services_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	$defaults = function_exists( 'xibufz_default_services' ) ? xibufz_default_services() : array();
	?>
	<div class="wrap xibufz-admin-wrap">
		<h1><?php echo esc_html__( '便民服务', 'xibufz' ); ?></h1>
		<?php xibufz_admin_notice(); ?>
		<div class="xibufz-admin-card">
			<p><?php echo esc_html__( '当前主题前台固定显示 4 个服务入口，这里用于维护每个入口的文字和链接。', 'xibufz' ); ?></p>
			<?php xibufz_admin_form_open( 'save_services' ); ?>
			<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
				<?php
				$default = isset( $defaults[ $i - 1 ] ) ? $defaults[ $i - 1 ] : array( 'icon' => '', 'title' => '', 'desc' => '', 'url' => home_url( '/' ) );
				$prefix  = 'xibufz_service_' . $i . '_';
				?>
				<h2><?php echo esc_html( sprintf( '服务入口 %d', $i ) ); ?></h2>
				<table class="form-table" role="presentation">
					<?php xibufz_admin_text_row( $prefix . 'icon', __( '图标', 'xibufz' ), get_theme_mod( $prefix . 'icon', $default['icon'] ) ); ?>
					<?php xibufz_admin_text_row( $prefix . 'title', __( '标题', 'xibufz' ), get_theme_mod( $prefix . 'title', $default['title'] ) ); ?>
					<?php xibufz_admin_text_row( $prefix . 'desc', __( '说明', 'xibufz' ), get_theme_mod( $prefix . 'desc', $default['desc'] ) ); ?>
					<?php xibufz_admin_text_row( $prefix . 'url', __( '链接', 'xibufz' ), get_theme_mod( $prefix . 'url', $default['url'] ), 'url' ); ?>
				</table>
			<?php endfor; ?>
			<?php submit_button( esc_html__( '保存便民服务', 'xibufz' ) ); ?>
			</form>
		</div>
	</div>
	<?php
}

/**
 * Render partners page.
 */
function xibufz_admin_partners_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	$text_rows = xibufz_admin_parse_links_text( get_theme_mod( 'xibufz_partner_text_links', '' ) );
	$logo_rows = xibufz_admin_parse_links_text( get_theme_mod( 'xibufz_partner_logo_links', '' ) );
	?>
	<div class="wrap xibufz-admin-wrap">
		<h1><?php echo esc_html__( '友情链接', 'xibufz' ); ?></h1>
		<?php xibufz_admin_notice(); ?>
		<div class="xibufz-admin-card">
			<?php xibufz_admin_form_open( 'save_partners' ); ?>
			<h2><?php echo esc_html__( '顶部文本链接', 'xibufz' ); ?></h2>
			<?php xibufz_admin_links_table( 'xibufz_partner_text_links_rows', $text_rows, 8 ); ?>
			<h2><?php echo esc_html__( '合作展示位链接', 'xibufz' ); ?></h2>
			<?php xibufz_admin_links_table( 'xibufz_partner_logo_links_rows', $logo_rows, 8 ); ?>
			<?php submit_button( esc_html__( '保存友情链接', 'xibufz' ) ); ?>
			</form>
		</div>
	</div>
	<?php
}

/**
 * Render footer page.
 */
function xibufz_admin_footer_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	?>
	<div class="wrap xibufz-admin-wrap">
		<h1><?php echo esc_html__( '页脚信息', 'xibufz' ); ?></h1>
		<?php xibufz_admin_notice(); ?>
		<div class="xibufz-admin-card">
			<?php xibufz_admin_form_open( 'save_footer' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="xibufz_footer_desc"><?php echo esc_html__( '页脚说明', 'xibufz' ); ?></label></th>
					<td><textarea class="large-text" rows="4" id="xibufz_footer_desc" name="xibufz_footer_desc"><?php echo esc_textarea( get_theme_mod( 'xibufz_footer_desc', '' ) ); ?></textarea></td>
				</tr>
				<?php xibufz_admin_text_row( 'xibufz_footer_record', __( '备案号', 'xibufz' ), get_theme_mod( 'xibufz_footer_record', '' ) ); ?>
				<?php xibufz_admin_text_row( 'xibufz_footer_organizer', __( '主办单位', 'xibufz' ), get_theme_mod( 'xibufz_footer_organizer', '' ) ); ?>
				<?php xibufz_admin_text_row( 'xibufz_footer_site_url', __( '官方网址', 'xibufz' ), get_theme_mod( 'xibufz_footer_site_url', home_url( '/' ) ), 'url' ); ?>
			</table>
			<?php submit_button( esc_html__( '保存页脚信息', 'xibufz' ) ); ?>
			</form>
		</div>
	</div>
	<?php
}

/**
 * Render tools page.
 */
function xibufz_admin_tools_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	?>
	<div class="wrap xibufz-admin-wrap">
		<h1><?php echo esc_html__( '内容工具', 'xibufz' ); ?></h1>
		<?php xibufz_admin_notice(); ?>
		<div class="xibufz-admin-grid">
			<div class="xibufz-admin-card">
				<h2><?php echo esc_html__( '基础初始化', 'xibufz' ); ?></h2>
				<p><?php echo esc_html__( '创建默认分类、常用页面、菜单，并写入不覆盖已有内容的默认配置。', 'xibufz' ); ?></p>
				<?php xibufz_admin_tool_button( 'init_all', __( '执行基础初始化', 'xibufz' ), 'primary' ); ?>
			</div>
			<div class="xibufz-admin-card">
				<h2><?php echo esc_html__( '分类状态', 'xibufz' ); ?></h2>
				<ul class="xibufz-status-list">
					<?php foreach ( xibufz_home_default_category_names() as $category_name ) : ?>
						<?php $exists = term_exists( $category_name, 'category' ); ?>
						<li><?php echo esc_html( $category_name ); ?>：<span class="<?php echo $exists ? 'xibufz-ok' : 'xibufz-missing'; ?>"><?php echo esc_html( $exists ? '已创建' : '缺失' ); ?></span></li>
					<?php endforeach; ?>
				</ul>
				<?php xibufz_admin_tool_button( 'create_categories', __( '创建缺失分类', 'xibufz' ) ); ?>
			</div>
			<div class="xibufz-admin-card">
				<h2><?php echo esc_html__( '页面与菜单', 'xibufz' ); ?></h2>
				<?php xibufz_admin_tool_button( 'create_pages', __( '创建常用页面', 'xibufz' ) ); ?>
				<?php xibufz_admin_tool_button( 'create_menus', __( '创建并分配菜单', 'xibufz' ) ); ?>
			</div>
			<div class="xibufz-admin-card">
				<h2><?php echo esc_html__( '首页栏目', 'xibufz' ); ?></h2>
				<p><?php echo esc_html__( '将首页栏目恢复为综合信息、法制热点、法律法规、专题专栏、法治理论、普法宣传。', 'xibufz' ); ?></p>
				<?php xibufz_admin_tool_button( 'reset_home_modules', __( '恢复默认首页栏目', 'xibufz' ), 'secondary', true ); ?>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Render a text field row.
 *
 * @param string $name Field name.
 * @param string $label Label.
 * @param string $value Value.
 * @param string $type Input type.
 */
function xibufz_admin_text_row( $name, $label, $value, $type = 'text' ) {
	?>
	<tr>
		<th scope="row"><label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $label ); ?></label></th>
		<td><input class="regular-text" id="<?php echo esc_attr( $name ); ?>" type="<?php echo esc_attr( $type ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>"></td>
	</tr>
	<?php
}

/**
 * Parse newline links into rows.
 *
 * @param string $text Raw link text.
 * @return array
 */
function xibufz_admin_parse_links_text( $text ) {
	if ( function_exists( 'xibufz_parse_links' ) ) {
		return xibufz_parse_links( $text );
	}

	$links = array();
	$rows  = preg_split( '/\r\n|\r|\n/', (string) $text );
	foreach ( $rows as $row ) {
		$row = trim( $row );
		if ( '' === $row ) {
			continue;
		}
		$parts   = array_map( 'trim', explode( '|', $row, 2 ) );
		$links[] = array(
			'label' => isset( $parts[0] ) ? $parts[0] : '',
			'url'   => isset( $parts[1] ) ? $parts[1] : home_url( '/' ),
		);
	}
	return $links;
}

/**
 * Render links table.
 *
 * @param string $name Field group name.
 * @param array  $rows Existing rows.
 * @param int    $min_rows Minimum rows.
 */
function xibufz_admin_links_table( $name, $rows, $min_rows = 8 ) {
	$count = max( count( $rows ) + 2, $min_rows );
	?>
	<div class="xibufz-table-scroll">
		<table class="widefat striped xibufz-admin-table xibufz-links-table">
			<thead><tr><th><?php echo esc_html__( '名称', 'xibufz' ); ?></th><th><?php echo esc_html__( '链接', 'xibufz' ); ?></th></tr></thead>
			<tbody>
				<?php for ( $i = 0; $i < $count; $i++ ) : ?>
					<?php $row = isset( $rows[ $i ] ) ? $rows[ $i ] : array( 'label' => '', 'url' => '' ); ?>
					<tr>
						<td><input class="regular-text" type="text" name="<?php echo esc_attr( $name ); ?>[<?php echo esc_attr( $i ); ?>][label]" value="<?php echo esc_attr( isset( $row['label'] ) ? $row['label'] : '' ); ?>"></td>
						<td><input class="regular-text" type="url" name="<?php echo esc_attr( $name ); ?>[<?php echo esc_attr( $i ); ?>][url]" value="<?php echo esc_attr( isset( $row['url'] ) ? $row['url'] : '' ); ?>"></td>
					</tr>
				<?php endfor; ?>
			</tbody>
		</table>
	</div>
	<?php
}

/**
 * Render a tool action button.
 *
 * @param string $action Action slug.
 * @param string $label Button label.
 * @param string $type Button type.
 * @param bool   $confirm Whether to add confirm text.
 */
function xibufz_admin_tool_button( $action, $label, $type = 'secondary', $confirm = false ) {
	xibufz_admin_form_open( $action );
	$attrs = array();
	if ( $confirm ) {
		$attrs['data-xibufz-confirm'] = esc_attr__( '确定执行此操作吗？', 'xibufz' );
	}
	submit_button( $label, $type, 'submit', false, $attrs );
	echo '</form>';
}

/**
 * Add home recommendation meta box.
 */
function xibufz_admin_add_post_meta_box() {
	add_meta_box(
		'xibufz-home-featured',
		esc_html__( '西部法制首页推荐', 'xibufz' ),
		'xibufz_admin_render_post_meta_box',
		'post',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'xibufz_admin_add_post_meta_box' );

/**
 * Render post recommendation meta box.
 *
 * @param WP_Post $post Post object.
 */
function xibufz_admin_render_post_meta_box( $post ) {
	wp_nonce_field( 'xibufz_save_post_recommend', 'xibufz_post_recommend_nonce' );
	$featured_id = xibufz_category_id_by_name( 'featured' );
	$is_featured = $featured_id ? has_category( $featured_id, $post ) : false;
	?>
	<p><label><input type="checkbox" name="xibufz_featured_post" value="1" <?php checked( $is_featured ); ?>> <?php echo esc_html__( '设为首页热门文章', 'xibufz' ); ?></label></p>
	<p class="description"><?php echo esc_html__( '勾选后会自动加入 featured 分类，供首页热门文章区调用。', 'xibufz' ); ?></p>
	<?php
}

/**
 * Save post recommendation meta box.
 *
 * @param int $post_id Post ID.
 */
function xibufz_admin_save_post_meta_box( $post_id ) {
	if ( ! isset( $_POST['xibufz_post_recommend_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['xibufz_post_recommend_nonce'] ) ), 'xibufz_save_post_recommend' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$featured_id = xibufz_category_id_by_name( 'featured' );
	if ( ! $featured_id ) {
		$result = wp_insert_term( 'featured', 'category' );
		if ( is_wp_error( $result ) || empty( $result['term_id'] ) ) {
			return;
		}
		$featured_id = absint( $result['term_id'] );
	}

	$categories = wp_get_post_categories( $post_id );
	$checked    = ! empty( $_POST['xibufz_featured_post'] );

	if ( $checked && ! in_array( $featured_id, $categories, true ) ) {
		$categories[] = $featured_id;
	} elseif ( ! $checked ) {
		$categories = array_diff( $categories, array( $featured_id ) );
	}

	wp_set_post_categories( $post_id, $categories, false );
}
add_action( 'save_post_post', 'xibufz_admin_save_post_meta_box' );
