<?php
/**
 * Home hero section.
 *
 * @package xibufz
 */

$sticky_ids = get_option( 'sticky_posts', array() );
$headline   = null;

if ( ! empty( $sticky_ids ) ) {
	$sticky_query = new WP_Query( array(
		'post__in'            => array_map( 'absint', $sticky_ids ),
		'posts_per_page'      => 1,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	) );
	if ( $sticky_query->have_posts() ) {
		$sticky_query->the_post();
		$headline = get_post();
	}
	wp_reset_postdata();
}

if ( ! $headline ) {
	$latest_headline = new WP_Query( array(
		'posts_per_page'      => 1,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	) );
	if ( $latest_headline->have_posts() ) {
		$latest_headline->the_post();
		$headline = get_post();
	}
	wp_reset_postdata();
}

$secondary_query = new WP_Query( array(
	'posts_per_page'      => 3,
	'post_status'         => 'publish',
	'post__not_in'        => $headline ? array( (int) $headline->ID ) : array(),
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
) );

$banner_image_id = absint( xibufz_mod( 'xibufz_banner_image', 0 ) );
$banner_image    = $banner_image_id ? wp_get_attachment_image_url( $banner_image_id, 'full' ) : '';
$banner_url      = xibufz_mod( 'xibufz_banner_url', home_url( '/' ) );
$banner_query    = new WP_Query( array(
	'posts_per_page'      => 3,
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
) );
$popular_query = xibufz_query_by_category( 'featured', 5 );
$notice_query  = xibufz_query_by_category( '公告公示', 4 );
$topic_query   = xibufz_query_by_category( '专题专栏', 3 );
?>

<section class="hero-grid">
	<div>
		<article class="card headline-card">
			<div class="badge"><?php echo esc_html__( '今日头条', 'xibufz' ); ?></div>
			<?php if ( $headline ) : ?>
				<h2 class="headline-title"><a href="<?php echo esc_url( get_permalink( $headline ) ); ?>"><?php echo esc_html( get_the_title( $headline ) ); ?></a></h2>
				<p class="headline-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt( $headline ), 58, '...' ) ); ?></p>
			<?php else : ?>
				<h2 class="headline-title"><?php echo esc_html__( '聚焦法治建设新任务，推动权威资讯传播与公共法律服务协同升级', 'xibufz' ); ?></h2>
				<p class="headline-desc"><?php echo esc_html__( '围绕法治新闻、公告公示、专题报道与服务入口进行首页重构，让用户快速识别重点、直达服务。', 'xibufz' ); ?></p>
			<?php endif; ?>
			<ul class="headline-list">
				<?php if ( $secondary_query->have_posts() ) : ?>
					<?php while ( $secondary_query->have_posts() ) : ?>
						<?php $secondary_query->the_post(); ?>
						<li><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a></li>
					<?php endwhile; ?>
				<?php else : ?>
					<li><?php echo esc_html__( '完善重点新闻推荐机制，突出头条与热点内容的传播价值', 'xibufz' ); ?></li>
					<li><?php echo esc_html__( '强化公告公示与法规栏目在首屏中的辨识度与权威感', 'xibufz' ); ?></li>
					<li><?php echo esc_html__( '新增便民服务入口，形成资讯与服务并重的门户结构', 'xibufz' ); ?></li>
				<?php endif; ?>
			</ul>
			<?php wp_reset_postdata(); ?>
		</article>

		<section class="card banner">
			<a class="banner-main" href="<?php echo esc_url( $banner_url ); ?>">
				<?php if ( $banner_image ) : ?>
					<img class="banner-image" src="<?php echo esc_url( $banner_image ); ?>" alt="<?php echo esc_attr( xibufz_mod( 'xibufz_banner_title', esc_html__( '首页 Banner', 'xibufz' ) ) ); ?>">
				<?php else : ?>
					<span class="banner-image banner-fallback" aria-hidden="true"></span>
				<?php endif; ?>
				<span class="banner-content">
					<span class="banner-kicker"><?php echo esc_html( xibufz_mod( 'xibufz_banner_kicker', esc_html__( '焦点专题 · 首屏主视觉 Banner', 'xibufz' ) ) ); ?></span>
					<span class="banner-title"><?php echo esc_html( xibufz_mod( 'xibufz_banner_title', esc_html__( '法治中国建设纵深推进，构建更高效、更清晰、更可信的资讯门户首页', 'xibufz' ) ) ); ?></span>
					<span class="banner-text"><?php echo esc_html( xibufz_mod( 'xibufz_banner_subtitle', esc_html__( '这里预留为大 banner 主图区域，可替换为会议现场、政法主题宣传图、法治专题视觉图或重大新闻事件配图。', 'xibufz' ) ) ); ?></span>
				</span>
			</a>
			<div class="banner-thumbs">
				<?php if ( $banner_query->have_posts() ) : ?>
					<?php while ( $banner_query->have_posts() ) : ?>
						<?php $banner_query->the_post(); ?>
						<a class="thumb" href="<?php echo esc_url( get_permalink() ); ?>">
							<?php xibufz_post_thumb_box( get_the_ID(), 'thumb-image', 'medium' ); ?>
							<p><?php echo esc_html( wp_trim_words( get_the_title(), 28, '...' ) ); ?></p>
						</a>
					<?php endwhile; ?>
				<?php else : ?>
					<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
						<a class="thumb" href="<?php echo esc_url( home_url( '/' ) ); ?>">
							<span class="thumb-image thumb-placeholder"><span><?php echo esc_html__( '西部法制', 'xibufz' ); ?></span></span>
							<p><?php echo esc_html__( '聚焦重大法治主题，强化新闻主视觉与专题传播表达。', 'xibufz' ); ?></p>
						</a>
					<?php endfor; ?>
				<?php endif; ?>
			</div>
			<?php wp_reset_postdata(); ?>
		</section>
	</div>

	<aside class="side-stack">
		<section class="card panel">
			<div class="panel-header">
				<h3 class="panel-title"><?php echo esc_html__( '热门文章', 'xibufz' ); ?></h3>
				<a class="panel-more" href="<?php echo esc_url( xibufz_category_url( 'featured' ) ); ?>"><?php echo esc_html__( '更多 >', 'xibufz' ); ?></a>
			</div>
			<ol class="rank-list">
				<?php if ( $popular_query->have_posts() ) : ?>
					<?php $rank = 1; ?>
					<?php while ( $popular_query->have_posts() ) : ?>
						<?php $popular_query->the_post(); ?>
						<li>
							<a class="rank-link" href="<?php echo esc_url( get_permalink() ); ?>">
								<span class="rank-num"><?php echo esc_html( $rank ); ?></span>
								<span><span class="item-title"><?php echo esc_html( get_the_title() ); ?></span><span class="item-meta"><?php echo esc_html( xibufz_post_date() ); ?></span></span>
							</a>
						</li>
						<?php $rank++; ?>
					<?php endwhile; ?>
				<?php else : ?>
					<?php xibufz_empty_state(); ?>
				<?php endif; ?>
			</ol>
			<?php wp_reset_postdata(); ?>
		</section>

		<section class="card panel notice-panel">
			<div class="panel-header">
				<h3 class="panel-title"><?php echo esc_html__( '公告信息', 'xibufz' ); ?></h3>
				<a class="panel-more" href="<?php echo esc_url( xibufz_category_url( '公告公示' ) ); ?>"><?php echo esc_html__( '更多 >', 'xibufz' ); ?></a>
			</div>
			<ul class="notice-list">
				<?php if ( $notice_query->have_posts() ) : ?>
					<?php while ( $notice_query->have_posts() ) : ?>
						<?php $notice_query->the_post(); ?>
						<li><a class="notice-link" href="<?php echo esc_url( get_permalink() ); ?>"><span class="item-title"><?php echo esc_html( get_the_title() ); ?></span></a></li>
					<?php endwhile; ?>
				<?php else : ?>
					<?php xibufz_empty_state(); ?>
				<?php endif; ?>
			</ul>
			<?php wp_reset_postdata(); ?>
		</section>

		<section class="card panel">
			<div class="panel-header">
				<h3 class="panel-title"><?php echo esc_html__( '专题推荐', 'xibufz' ); ?></h3>
				<a class="panel-more" href="<?php echo esc_url( xibufz_category_url( '专题专栏' ) ); ?>"><?php echo esc_html__( '更多 >', 'xibufz' ); ?></a>
			</div>
			<ul class="news-list">
				<?php if ( $topic_query->have_posts() ) : ?>
					<?php while ( $topic_query->have_posts() ) : ?>
						<?php $topic_query->the_post(); ?>
						<li><a class="news-link" href="<?php echo esc_url( get_permalink() ); ?>"><span><span class="item-title"><?php echo esc_html( get_the_title() ); ?></span><span class="item-meta"><?php echo esc_html( xibufz_post_date() ); ?></span></span></a></li>
					<?php endwhile; ?>
				<?php else : ?>
					<?php xibufz_empty_state(); ?>
				<?php endif; ?>
			</ul>
			<?php wp_reset_postdata(); ?>
		</section>
	</aside>
</section>
