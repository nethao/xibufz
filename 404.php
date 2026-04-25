<?php
/**
 * 404 template.
 *
 * @package xibufz
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container content-layout">
		<section class="card entry-card not-found">
			<header class="entry-header">
				<h1 class="entry-title"><?php echo esc_html__( '页面未找到', 'xibufz' ); ?></h1>
			</header>
			<div class="entry-content">
				<p><?php echo esc_html__( '抱歉，您访问的页面不存在或已被移动。可以尝试搜索相关内容。', 'xibufz' ); ?></p>
				<?php get_search_form(); ?>
			</div>
		</section>
	</div>
</main>

<?php
get_footer();
