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
			<?php if ( ! empty( $available_widgets ) ) { ?>
				<div>
					<button class="button" popovertarget="widget-list">
						<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" viewBox="0 0 16 16">
							<path fill="currentColor" d="M10 1H6v5H1v4h5v5h4v-5h5V6h-5V1Z"/>
						</svg>
						Add widget
					</button>
					<ul id="widget-list" popover>
						<?php
						foreach ( $available_widgets as $id => $name ) {
							echo sprintf( '<li data-widget-id="%1$s">%2$s</li>', esc_attr( $id ), esc_html( $name ) );
						} ?>
					</ul>
				</div>
			<?php } ?>
			<button class="button" id="edit-grid">
				<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" viewBox="0 0 16 16">
					<path fill="currentColor" d="M7 1H1v4h6V1ZM7 7H1v8h6V7ZM9 1h6v8H9V1ZM15 11H9v4h6v-4Z"/>
				</svg>
				Edit
			</button>
		</header>
		<section class="widget-grid">
			<?php
			if ( ! empty( $dashboard_widgets ) ) {
				/**
				 * @var Widget $widget
				 */
				foreach ( $dashboard_widgets as $widget ) {
					$widget->render();
				}
			}
			?>
		</section>
	</section>
</div>