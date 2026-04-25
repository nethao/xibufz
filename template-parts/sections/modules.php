<?php
/**
 * Topic modules section.
 *
 * @package xibufz
 */

$modules = function_exists( 'xibufz_get_home_modules' ) ? xibufz_get_home_modules( false ) : array();

if ( empty( $modules ) && function_exists( 'xibufz_default_home_modules' ) ) {
	$modules = xibufz_default_home_modules();
}

?>
<section class="section">
	<div class="section-title-row">
		<h2 class="section-title"><span class="title-bar"></span><?php echo esc_html__( '资讯专题', 'xibufz' ); ?></h2>
		<a class="panel-more" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( '更多 >', 'xibufz' ); ?></a>
	</div>

	<div class="module-grid">
		<?php foreach ( $modules as $module ) : ?>
			<?php
			if ( empty( $module['show'] ) ) {
				continue;
			}

			$module_title = isset( $module['title'] ) ? $module['title'] : '';
			$category_id  = isset( $module['category_id'] ) ? absint( $module['category_id'] ) : 0;
			$count        = isset( $module['count'] ) ? absint( $module['count'] ) : 5;
			$style        = isset( $module['style'] ) ? $module['style'] : 'default';
			$module_class = function_exists( 'xibufz_home_module_style_class' ) ? xibufz_home_module_style_class( $style ) : '';
			$module_query = function_exists( 'xibufz_query_by_category_id' ) ? xibufz_query_by_category_id( $category_id, $count ) : new WP_Query( array( 'post__in' => array( 0 ) ) );
			$term_url     = function_exists( 'xibufz_category_url_by_id' ) ? xibufz_category_url_by_id( $category_id ) : home_url( '/' );
			?>
			<article class="card module-card <?php echo esc_attr( $module_class ); ?>">
				<div class="module-head">
					<h3><?php echo esc_html( $module_title ); ?></h3>
					<a href="<?php echo esc_url( $term_url ); ?>"><?php echo esc_html__( '更多 >', 'xibufz' ); ?></a>
				</div>

				<div class="module-body">
					<?php if ( $module_query->have_posts() ) : ?>
						<?php $module_query->the_post(); ?>
						<div class="module-feature">
							<a href="<?php echo esc_url( get_permalink() ); ?>"><?php xibufz_post_thumb_box( get_the_ID(), 'module-thumb', 'medium' ); ?></a>
							<div>
								<h4><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a></h4>
								<div class="date"><?php echo esc_html( xibufz_post_date() ); ?></div>
							</div>
						</div>

						<ul class="module-links">
							<?php while ( $module_query->have_posts() ) : ?>
								<?php $module_query->the_post(); ?>
								<li><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a></li>
							<?php endwhile; ?>
						</ul>
					<?php else : ?>
						<div class="module-feature">
							<span class="module-thumb thumb-placeholder"><span><?php echo esc_html__( '西部法制', 'xibufz' ); ?></span></span>
							<div>
								<h4><?php echo esc_html__( '暂无内容，请添加文章后查看。', 'xibufz' ); ?></h4>
								<div class="date"><?php echo esc_html( wp_date( 'Y-m-d' ) ); ?></div>
							</div>
						</div>
						<ul class="module-links">
							<li><a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=category' ) ); ?>"><?php echo esc_html__( '后台绑定分类并发布文章后，将自动显示在这里。', 'xibufz' ); ?></a></li>
						</ul>
					<?php endif; ?>
				</div>
			</article>
			<?php wp_reset_postdata(); ?>
		<?php endforeach; ?>
	</div>
</section>
