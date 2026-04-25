<?php
/**
 * Theme functions.
 *
 * @package xibufz
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'XIBUFZ_VERSION', '1.0.0' );

require get_template_directory() . '/inc/theme-setup.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/admin.php';
