<?php
/**
 * The Shop Maestro Suite dashboard page
 */

use ShopMaestro\Conductor\Contracts\Widget;

?>

<div class="maestro-dashboard">
	<header class="page-header">
		<h1 class="page-title">Shop Maestro Suite</h1>
	</header>
	<section class="wrap">
		<h1 class="screen-reader-text"><?php esc_html_e( 'Shop Maestro Dashboard', 'shop-maestro' ); ?></h1>
		<header class="grid-header">
		</header>
		<section class="widget-grid">
			<?php
			if ( ! empty( $widgets ) ) {
				foreach ( $widgets as $widget ) {
					( new $widget )->render();
				}
			}
			?>
		</section>
	</section>
</div>