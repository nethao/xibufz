<?php
/**
 * Search form template.
 *
 * @package xibufz
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php $xibufz_search_id = wp_unique_id( 'xibufz-search-field-' ); ?>
<form class="search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $xibufz_search_id ); ?>"><?php echo esc_html__( '搜索', 'xibufz' ); ?></label>
	<input id="<?php echo esc_attr( $xibufz_search_id ); ?>" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php echo esc_attr__( '搜索新闻、法规、专题、公告', 'xibufz' ); ?>">
	<button type="submit"><?php echo esc_html__( '搜索', 'xibufz' ); ?></button>
</form>
