<?php

namespace ShopMaestro\Conductor\Admin;

use ShopMaestro\Conductor\Contracts\Interfaces\Hookable;

/**
 * Register all admin pages and display their menu items.
 */
class Assets implements Hookable {

	public function register_hooks(): void {
		\add_action( 'admin_enqueue_scripts', [ $this, 'styles' ] );
		// wp_loaded werkt wel, wp niet meer.
	}

	public function styles() {
		\wp_register_style( 'maestro-dashboard', conductor()->get_assets_url( 'css/dashboard.css' ) );
		\wp_enqueue_style( 'maestro-dashboard' );
	}
}
