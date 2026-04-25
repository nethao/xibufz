<?php
/**
 * Theme admin pages.
 *
 * @package xibufz
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme admin menu.
 */
function xibufz_admin_menu() {
	add_menu_page(
		esc_html__( '西部法制管理', 'xibufz' ),
		esc_html__( '西部法制管理', 'xibufz' ),
		'edit_theme_options',
		'xibufz-management',
		'xibufz_home_modules_page',
		'dashicons-layout',
		61
	);

	add_submenu_page(
		'xibufz-management',
		esc_html__( '首页栏目管理', 'xibufz' ),
		esc_html__( '首页栏目管理', 'xibufz' ),
		'edit_theme_options',
		'xibufz-management',
		'xibufz_home_modules_page'
	);
}
add_action( 'admin_menu', 'xibufz_admin_menu' );

/**
 * Save home modules when the admin form is submitted.
 */
function xibufz_maybe_save_home_modules() {
	if ( ! is_admin() || ! isset( $_POST['xibufz_home_modules_nonce'] ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( '您没有权限执行此操作。', 'xibufz' ) );
	}

	check_admin_referer( 'xibufz_save_home_modules', 'xibufz_home_modules_nonce' );

	$raw_modules = isset( $_POST['xibufz_home_modules'] ) && is_array( $_POST['xibufz_home_modules'] ) ? wp_unslash( $_POST['xibufz_home_modules'] ) : array();
	$modules     = xibufz_sanitize_home_modules( $raw_modules );

	set_theme_mod( 'xibufz_home_modules', $modules );

	wp_safe_redirect(
		add_query_arg(
			array(
				'page'    => 'xibufz-management',
				'updated' => 'true',
			),
			admin_url( 'admin.php' )
		)
	);
	exit;
}
add_action( 'admin_init', 'xibufz_maybe_save_home_modules' );

/**
 * Render home modules admin page.
 */
function xibufz_home_modules_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$modules = xibufz_get_home_modules();
	$rows    = max( count( $modules ) + 2, 8 );
	$styles  = array(
		'default' => esc_html__( 'default', 'xibufz' ),
		'red'     => esc_html__( 'red', 'xibufz' ),
		'dark'    => esc_html__( 'dark', 'xibufz' ),
	);
	?>
	<div class="wrap">
		<h1><?php echo esc_html__( '首页栏目管理', 'xibufz' ); ?></h1>

		<?php $updated = isset( $_GET['updated'] ) ? sanitize_text_field( wp_unslash( $_GET['updated'] ) ) : ''; ?>
		<?php if ( 'true' === $updated ) : ?>
			<div class="notice notice-success is-dismissible">
				<p><?php echo esc_html__( '首页栏目配置已保存。', 'xibufz' ); ?></p>
			</div>
		<?php endif; ?>

		<form method="post" action="">
			<?php wp_nonce_field( 'xibufz_save_home_modules', 'xibufz_home_modules_nonce' ); ?>
			<p><?php echo esc_html__( '配置首页“资讯专题”模块。留空的行不会保存；排序值越小越靠前。', 'xibufz' ); ?></p>

			<table class="widefat striped">
				<thead>
					<tr>
						<th><?php echo esc_html__( '是否显示', 'xibufz' ); ?></th>
						<th><?php echo esc_html__( '栏目标题', 'xibufz' ); ?></th>
						<th><?php echo esc_html__( '绑定分类', 'xibufz' ); ?></th>
						<th><?php echo esc_html__( '显示文章数量', 'xibufz' ); ?></th>
						<th><?php echo esc_html__( '模块样式', 'xibufz' ); ?></th>
						<th><?php echo esc_html__( '排序值', 'xibufz' ); ?></th>
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
						?>
						<tr>
							<td>
								<label>
									<input type="checkbox" name="xibufz_home_modules[<?php echo esc_attr( $i ); ?>][show]" value="1" <?php checked( ! empty( $module['show'] ) ); ?>>
									<?php echo esc_html__( '显示', 'xibufz' ); ?>
								</label>
							</td>
							<td>
								<input class="regular-text" type="text" name="xibufz_home_modules[<?php echo esc_attr( $i ); ?>][title]" value="<?php echo esc_attr( $module['title'] ); ?>">
							</td>
							<td>
								<?php
								wp_dropdown_categories( array(
									'name'             => 'xibufz_home_modules[' . esc_attr( $i ) . '][category_id]',
									'selected'         => absint( $module['category_id'] ),
									'show_option_none' => esc_html__( '不绑定分类', 'xibufz' ),
									'option_none_value' => 0,
									'hide_empty'       => false,
									'taxonomy'         => 'category',
								) );
								?>
							</td>
							<td>
								<input class="small-text" type="number" min="1" max="20" name="xibufz_home_modules[<?php echo esc_attr( $i ); ?>][count]" value="<?php echo esc_attr( $module['count'] ); ?>">
							</td>
							<td>
								<select name="xibufz_home_modules[<?php echo esc_attr( $i ); ?>][style]">
									<?php foreach ( $styles as $value => $label ) : ?>
										<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $module['style'], $value ); ?>><?php echo esc_html( $label ); ?></option>
									<?php endforeach; ?>
								</select>
							</td>
							<td>
								<input class="small-text" type="number" name="xibufz_home_modules[<?php echo esc_attr( $i ); ?>][order]" value="<?php echo esc_attr( $module['order'] ); ?>">
							</td>
						</tr>
					<?php endfor; ?>
				</tbody>
			</table>

			<?php submit_button( esc_html__( '保存栏目配置', 'xibufz' ) ); ?>
		</form>
	</div>
	<?php
}
