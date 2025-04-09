<?php
/**
 * The Shop Maestro Suite dashboard page
 */

use ShopMaestro\Conductor\Contracts\Widget;
use ShopMaestro\Conductor\Widgets\Introduction;

$widgets = apply_filters( 'shop-maestro/conductor/widgets', [
	Introduction::class,
] );

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
				/**
				 * @var Widget $widget
				 */
				foreach ( $widgets as $widget ) {
					// Only show actual widgets.
					if( is_subclass_of( $widget, Widget::class ) ) {
						( new $widget )->render();
					}
				}
			}
			?>
		</section>
	</section>
</div>