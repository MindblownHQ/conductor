<?php

namespace ShopMaestro\Conductor\Controllers;

use ShopMaestro\Conductor\Contracts\Controller;

class LicenseController extends Controller{

	/**
	 * Display the licenses page
	 *
	 * @return void
	 */
	public function display(): void {
		// render the view.
		$plugins = conductor()->plugins()->premium();
		\conductor_template( 'license-page', \compact( 'plugins' ) );
	}

	/**
	 * Update the settings on this page
	 *
	 * @return void
	 */
	public function update(): void {

		$plugin_name = $_POST['shop_maestro_license_for'] ?? null;
		
		// Check if we have a plugin whos key to save
		if( !is_null( $plugin_name ) ){
			$license_key = \sanitize_text_field( $_POST['license_key'] );
			update_option( $plugin_name.'_license_key', $license_key );
		}

		// Redirect back to the licenses page
		wp_redirect( conductor_get_route_url( 'shop_maestro_licenses' ) );
		exit();
	}
}
