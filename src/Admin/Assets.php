<?php

namespace ShopMaestro\Conductor\Admin;

use ShopMaestro\Conductor\Contracts\Interfaces\Hookable;

/**
 * Register all admin pages and display their menu items.
 */
class Assets implements Hookable {

	public function register_hooks(): void {
//		\add_action( 'admin_enqueue_scripts', [ $this, 'styles' ] );
		\add_action( 'admin_head', [ $this, 'stylesheet'] );

		// wp_loaded werkt wel, wp niet meer.
	}

	public function stylesheet() {
		echo '<link rel="stylesheet" id="maestro-dashboard" href="https://development.test/wp-content/plugins/wooping-shop-health/vendor/shop-maestro/conductor/assets/dist/css/dashboard.css">';
	}

//	public function styles() {
//		\wp_register_style( 'maestro-dashboard', conductor()->get_plugin_folder() . '/assets/dist/css/dashboard.css' );
//		\wp_enqueue_style( 'maestro-dashboard' );
//	}
}