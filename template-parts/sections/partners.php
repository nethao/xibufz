<?php
/**
 * Partners section.
 *
 * @package xibufz
 */

$text_links = xibufz_parse_links( xibufz_mod( 'xibufz_partner_text_links', "网站介绍|#\n投稿说明|#\n法律服务|#\n律师团|#\n官方微博|#" ) );
$logo_links = xibufz_parse_links( xibufz_mod( 'xibufz_partner_logo_links', "陕西法治协作平台|#\n西部政法资讯中心|#\n公共法律服务站|#\n法治传播研究组|#\n社会治理观察室|#\n站点合作展示位|#" ) );
?>

<section class="section">
	<div class="card partner-card">
		<div class="partner-top">
			<h2 class="section-title"><span class="title-bar"></span><?php echo esc_html__( '合作单位 / 友情链接', 'xibufz' ); ?></h2>
			<div class="partner-links">
				<?php foreach ( $text_links as $link ) : ?>
					<a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="partner-logos">
			<?php foreach ( $logo_links as $link ) : ?>
				<a class="logo-item" href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
