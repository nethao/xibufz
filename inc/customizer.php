<?php
/**
 * Customizer settings.
 *
 * @package xibufz
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize URL or empty value.
 *
 * @param string $value URL value.
 * @return string
 */
function xibufz_sanitize_url( $value ) {
	return esc_url_raw( $value );
}

/**
 * Sanitize textarea.
 *
 * @param string $value Text value.
 * @return string
 */
function xibufz_sanitize_textarea( $value ) {
	return sanitize_textarea_field( $value );
}

/**
 * Register Customizer controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function xibufz_customize_register( $wp_customize ) {
	$wp_customize->add_panel( 'xibufz_theme_options', array(
		'title'       => esc_html__( '西部法制网主题设置', 'xibufz' ),
		'description' => esc_html__( '配置首页 Banner、服务入口、友情链接和页脚信息。', 'xibufz' ),
		'priority'    => 30,
	) );

	$wp_customize->add_section( 'xibufz_banner', array(
		'title' => esc_html__( '首页 Banner', 'xibufz' ),
		'panel' => 'xibufz_theme_options',
	) );

	$wp_customize->add_setting( 'xibufz_banner_image', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'xibufz_banner_image', array(
		'label'     => esc_html__( 'Banner 图片', 'xibufz' ),
		'section'   => 'xibufz_banner',
		'mime_type' => 'image',
	) ) );

	$banner_fields = array(
		'xibufz_banner_kicker'   => array(
			'label'   => esc_html__( 'Banner 小标题', 'xibufz' ),
			'default' => esc_html__( '焦点专题 · 首屏主视觉 Banner', 'xibufz' ),
			'type'    => 'text',
		),
		'xibufz_banner_title'    => array(
			'label'   => esc_html__( 'Banner 标题', 'xibufz' ),
			'default' => esc_html__( '法治中国建设纵深推进，构建更高效、更清晰、更可信的资讯门户首页', 'xibufz' ),
			'type'    => 'text',
		),
		'xibufz_banner_subtitle' => array(
			'label'   => esc_html__( 'Banner 副标题', 'xibufz' ),
			'default' => esc_html__( '这里预留为大 banner 主图区域，可替换为会议现场、政法主题宣传图、法治专题视觉图或重大新闻事件配图。', 'xibufz' ),
			'type'    => 'textarea',
		),
		'xibufz_banner_url'      => array(
			'label'   => esc_html__( 'Banner 链接', 'xibufz' ),
			'default' => home_url( '/' ),
			'type'    => 'text',
		),
	);

	foreach ( $banner_fields as $setting => $field ) {
		$wp_customize->add_setting( $setting, array(
			'default'           => $field['default'],
			'sanitize_callback' => 'xibufz_banner_url' === $setting ? 'xibufz_sanitize_url' : 'sanitize_text_field',
		) );
		$wp_customize->add_control( $setting, array(
			'label'   => $field['label'],
			'section' => 'xibufz_banner',
			'type'    => $field['type'],
		) );
	}

	$wp_customize->add_section( 'xibufz_services', array(
		'title' => esc_html__( '便民服务', 'xibufz' ),
		'panel' => 'xibufz_theme_options',
	) );

	$services = xibufz_default_services();
	$service_field_labels = array(
		'icon'  => esc_html__( '图标', 'xibufz' ),
		'title' => esc_html__( '标题', 'xibufz' ),
		'desc'  => esc_html__( '说明', 'xibufz' ),
		'url'   => esc_html__( '链接', 'xibufz' ),
	);
	for ( $i = 1; $i <= 4; $i++ ) {
		$defaults = $services[ $i - 1 ];
		foreach ( array( 'icon', 'title', 'desc', 'url' ) as $field ) {
			$setting = "xibufz_service_{$i}_{$field}";
			$wp_customize->add_setting( $setting, array(
				'default'           => $defaults[ $field ],
				'sanitize_callback' => 'url' === $field ? 'xibufz_sanitize_url' : 'sanitize_text_field',
			) );
			$wp_customize->add_control( $setting, array(
				'label'   => sprintf(
					/* translators: 1: service number, 2: field name. */
					esc_html__( '服务 %1$d %2$s', 'xibufz' ),
					$i,
					$service_field_labels[ $field ]
				),
				'section' => 'xibufz_services',
				'type'    => 'text',
			) );
		}
	}

	$wp_customize->add_section( 'xibufz_links', array(
		'title' => esc_html__( '友情链接', 'xibufz' ),
		'panel' => 'xibufz_theme_options',
	) );

	$wp_customize->add_setting( 'xibufz_partner_text_links', array(
		'default'           => "网站介绍|#\n投稿说明|#\n法律服务|#\n律师团|#\n官方微博|#",
		'sanitize_callback' => 'xibufz_sanitize_textarea',
	) );
	$wp_customize->add_control( 'xibufz_partner_text_links', array(
		'label'       => esc_html__( '顶部文本链接', 'xibufz' ),
		'description' => esc_html__( '每行一个，格式：名称|链接', 'xibufz' ),
		'section'     => 'xibufz_links',
		'type'        => 'textarea',
	) );

	$wp_customize->add_setting( 'xibufz_partner_logo_links', array(
		'default'           => "陕西法治协作平台|#\n西部政法资讯中心|#\n公共法律服务站|#\n法治传播研究组|#\n社会治理观察室|#\n站点合作展示位|#",
		'sanitize_callback' => 'xibufz_sanitize_textarea',
	) );
	$wp_customize->add_control( 'xibufz_partner_logo_links', array(
		'label'       => esc_html__( '展示位链接', 'xibufz' ),
		'description' => esc_html__( '每行一个，格式：名称|链接', 'xibufz' ),
		'section'     => 'xibufz_links',
		'type'        => 'textarea',
	) );

	$wp_customize->add_section( 'xibufz_footer', array(
		'title' => esc_html__( '页脚信息', 'xibufz' ),
		'panel' => 'xibufz_theme_options',
	) );

	$footer_fields = array(
		'xibufz_footer_desc'      => array(
			'label'   => esc_html__( '页脚说明', 'xibufz' ),
			'default' => esc_html__( '打造更权威、更清晰、更现代的法治资讯与服务门户首页，适合新闻发布、公告公示、专题传播与便民服务统一承载。', 'xibufz' ),
		),
		'xibufz_footer_record'    => array(
			'label'   => esc_html__( '备案号', 'xibufz' ),
			'default' => esc_html__( '陕ICP备2025000000号', 'xibufz' ),
		),
		'xibufz_footer_organizer' => array(
			'label'   => esc_html__( '主办单位', 'xibufz' ),
			'default' => esc_html__( '西部法制网', 'xibufz' ),
		),
		'xibufz_footer_site_url'  => array(
			'label'   => esc_html__( '官网地址', 'xibufz' ),
			'default' => 'www.xibufz.com',
		),
	);

	foreach ( $footer_fields as $setting => $field ) {
		$wp_customize->add_setting( $setting, array(
			'default'           => $field['default'],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( $setting, array(
			'label'   => $field['label'],
			'section' => 'xibufz_footer',
			'type'    => 'text',
		) );
	}
}
add_action( 'customize_register', 'xibufz_customize_register' );
