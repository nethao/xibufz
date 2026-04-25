<?php
/**
 * Home hero section.
 *
 * @package xibufz
 */

$headline_config = function_exists( 'xibufz_get_home_headline_config' ) ? xibufz_get_home_headline_config() : array(
	'show'           => 1,
	'badge'          => esc_html__( '今日头条', 'xibufz' ),
	'show_excerpt'   => 1,
	'excerpt_length' => 58,
	'secondary_show' => 1,
);
$headline        = ! empty( $headline_config['show'] ) && function_exists( 'xibufz_get_home_headline_post' ) ? xibufz_get_home_headline_post( $headline_config ) : null;
$secondary_query = ! empty( $headline_config['secondary_show'] ) && function_exists( 'xibufz_get_home_secondary_headlines_query' ) ? xibufz_get_home_secondary_headlines_query( $headline_config, $headline ? (int) $headline->ID : 0 ) : null;
$headline_badge  = ! empty( $headline_config['badge'] ) ? $headline_config['badge'] : esc_html__( '今日头条', 'xibufz' );
$excerpt_length  = ! empty( $headline_config['excerpt_length'] ) ? absint( $headline_config['excerpt_length'] ) : 58;

$banner_image_mod = xibufz_mod( 'xibufz_banner_image', '' );
$banner_image     = '';

if ( is_numeric( $banner_image_mod ) ) {
	$banner_image = wp_get_attachment_image_url( absint( $banner_image_mod ), 'full' );
} elseif ( $banner_image_mod ) {
	$banner_image = esc_url_raw( $banner_image_mod );
}

$banner_source      = sanitize_key( xibufz_mod( 'xibufz_banner_source', 'custom' ) );
$banner_category_id = absint( xibufz_mod( 'xibufz_banner_category_id', 0 ) );
$banner_post_count  = min( 8, max( 4, absint( xibufz_mod( 'xibufz_banner_post_count', 4 ) ) ) );
$banner_url         = xibufz_mod( 'xibufz_banner_url', home_url( '/' ) );
$banner_kicker      = xibufz_mod( 'xibufz_banner_kicker', esc_html__( '焦点专题 · 首屏主视觉 Banner', 'xibufz' ) );
$banner_title       = xibufz_mod( 'xibufz_banner_title', esc_html__( '法治中国建设纵深推进，构建更高效、更清晰、更可信的资讯门户首页', 'xibufz' ) );
$banner_subtitle    = xibufz_mod( 'xibufz_banner_subtitle', esc_html__( '这里预留为大 banner 主图区域，可替换为会议现场、政法主题宣传图、法治专题视觉图或重大新闻事件配图。', 'xibufz' ) );
$banner_query_args  = array(
	'posts_per_page'      => 3,
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
);

if ( 'category' === $banner_source && $banner_category_id ) {
	$banner_query_args['posts_per_page'] = $banner_post_count;
	$banner_query_args['cat']            = $banner_category_id;
}

$banner_query = new WP_Query( $banner_query_args );

if ( 'category' === $banner_source && $banner_query->have_posts() ) {
	$banner_query->the_post();
	$banner_post     = get_post();
	$banner_url      = get_permalink( $banner_post );
	$banner_title    = get_the_title( $banner_post );
	$banner_subtitle = wp_trim_words( get_the_excerpt( $banner_post ), 42, '...' );
	$banner_term     = get_category( $banner_category_id );

	if ( $banner_term && ! is_wp_error( $banner_term ) ) {
		$banner_kicker = $banner_term->name;
	}

	if ( has_post_thumbnail( $banner_post ) ) {
		$banner_image = get_the_post_thumbnail_url( $banner_post, 'full' );
	}
}

$hero_panels = function_exists( 'xibufz_get_home_sidebar_panels' ) ? xibufz_get_home_sidebar_panels( 'hero' ) : array();
?>

<section class="hero-grid">
	<div>
		<?php if ( ! empty( $headline_config['show'] ) ) : ?>
			<article class="card headline-card">
				<div class="badge"><?php echo esc_html( $headline_badge ); ?></div>
				<?php if ( $headline ) : ?>
					<h2 class="headline-title"><a href="<?php echo esc_url( get_permalink( $headline ) ); ?>"><?php echo esc_html( get_the_title( $headline ) ); ?></a></h2>
					<?php if ( ! empty( $headline_config['show_excerpt'] ) ) : ?>
						<p class="headline-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt( $headline ), $excerpt_length, '...' ) ); ?></p>
					<?php endif; ?>
				<?php else : ?>
					<h2 class="headline-title"><?php echo esc_html__( '聚焦法治建设新任务，推动权威资讯传播与公共法律服务协同升级', 'xibufz' ); ?></h2>
					<?php if ( ! empty( $headline_config['show_excerpt'] ) ) : ?>
						<p class="headline-desc"><?php echo esc_html__( '围绕法治新闻、公告公示、专题报道与服务入口进行首页重构，让用户快速识别重点、直达服务。', 'xibufz' ); ?></p>
					<?php endif; ?>
				<?php endif; ?>
				<?php if ( ! empty( $headline_config['secondary_show'] ) ) : ?>
					<ul class="headline-list">
						<?php if ( $secondary_query && $secondary_query->have_posts() ) : ?>
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
				<?php endif; ?>
			</article>
		<?php endif; ?>

		<section class="card banner">
			<a class="banner-main" href="<?php echo esc_url( $banner_url ); ?>">
				<?php if ( $banner_image ) : ?>
					<img class="banner-image" src="<?php echo esc_url( $banner_image ); ?>" alt="<?php echo esc_attr( $banner_title ); ?>">
				<?php else : ?>
					<span class="banner-image banner-fallback" aria-hidden="true"></span>
				<?php endif; ?>
				<span class="banner-content">
					<span class="banner-kicker"><?php echo esc_html( $banner_kicker ); ?></span>
					<span class="banner-title"><?php echo esc_html( $banner_title ); ?></span>
					<span class="banner-text"><?php echo esc_html( $banner_subtitle ); ?></span>
				</span>
			</a>
			<div class="banner-thumbs">
				<?php if ( $banner_query->have_posts() ) : ?>
					<?php $banner_thumb_count = 0; ?>
					<?php while ( $banner_query->have_posts() && 3 > $banner_thumb_count ) : ?>
						<?php $banner_query->the_post(); ?>
						<a class="thumb" href="<?php echo esc_url( get_permalink() ); ?>">
							<?php xibufz_post_thumb_box( get_the_ID(), 'thumb-image', 'medium' ); ?>
							<p><?php echo esc_html( wp_trim_words( get_the_title(), 28, '...' ) ); ?></p>
						</a>
						<?php $banner_thumb_count++; ?>
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
		<?php foreach ( $hero_panels as $panel ) : ?>
			<?php xibufz_render_home_sidebar_panel( $panel ); ?>
		<?php endforeach; ?>
	</aside>
</section>
