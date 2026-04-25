<?php
/**
 * Service links section.
 *
 * @package xibufz
 */

$defaults = xibufz_default_services();
$services = array();

for ( $i = 1; $i <= 4; $i++ ) {
	$fallback   = $defaults[ $i - 1 ];
	$services[] = array(
		'icon'  => xibufz_mod( "xibufz_service_{$i}_icon", $fallback['icon'] ),
		'title' => xibufz_mod( "xibufz_service_{$i}_title", $fallback['title'] ),
		'desc'  => xibufz_mod( "xibufz_service_{$i}_desc", $fallback['desc'] ),
		'url'   => xibufz_mod( "xibufz_service_{$i}_url", $fallback['url'] ),
	);
}
?>

<section class="section">
	<div class="section-title-row">
		<h2 class="section-title"><span class="title-bar"></span><?php echo esc_html__( '便民服务', 'xibufz' ); ?></h2>
	</div>
	<div class="service-grid">
		<?php foreach ( $services as $service ) : ?>
			<a class="card service-card" href="<?php echo esc_url( $service['url'] ); ?>">
				<div class="service-icon"><?php echo esc_html( $service['icon'] ); ?></div>
				<div>
					<h4><?php echo esc_html( $service['title'] ); ?></h4>
					<p><?php echo esc_html( $service['desc'] ); ?></p>
				</div>
			</a>
		<?php endforeach; ?>
	</div>
</section>
