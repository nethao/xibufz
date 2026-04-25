<?php
/**
 * Sidebar template.
 *
 * @package xibufz
 */

if ( is_active_sidebar( 'home-sidebar' ) ) {
	dynamic_sidebar( 'home-sidebar' );
	return;
}
?>

<section class="card panel">
	<div class="panel-header">
		<h3 class="panel-title"><?php echo esc_html__( '编务提醒', 'xibufz' ); ?></h3>
	</div>
	<ul class="news-list">
		<li><span class="news-link"><span><span class="item-title"><?php echo esc_html__( '头条标题建议控制在 24 至 32 字内', 'xibufz' ); ?></span><span class="item-meta"><?php echo esc_html__( '编辑规范', 'xibufz' ); ?></span></span></span></li>
		<li><span class="news-link"><span><span class="item-title"><?php echo esc_html__( '推荐新闻尽量上传高质量横版封面图', 'xibufz' ); ?></span><span class="item-meta"><?php echo esc_html__( '图片规范', 'xibufz' ); ?></span></span></span></li>
		<li><span class="news-link"><span><span class="item-title"><?php echo esc_html__( '公告信息与普通资讯分栏维护，避免混排', 'xibufz' ); ?></span><span class="item-meta"><?php echo esc_html__( '内容规范', 'xibufz' ); ?></span></span></span></li>
	</ul>
</section>
