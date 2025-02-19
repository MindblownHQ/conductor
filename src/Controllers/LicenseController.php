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
		// get the variables ready.
		$plugins = conductor()->plugins()->premium();
		$first_key = array_keys( $plugins )[0];
		$active_plugin = ( isset( $_GET['plugin'] ) ? $_GET['plugin'] : $first_key );
		$license = conductor()->plugins()->get_license( $active_plugin );

		// and render the view
		\conductor_template( 'license-page', \compact( 'plugins', 'active_plugin', 'license' ) );
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

			// @todo: fix the remote license checking
			// Fetch the remote license info, and save it locally
			/*$remote_license = $this->get_remote_license_info( $plugin_name, $license_key );
			update_option( $plugin_name.'_license_validated', $remote_license['validated'] );
			update_option( $plugin_name.'_license_expires', $remote_license['expires'] );*/
		}

		// Redirect back to the licenses page
		wp_redirect( conductor_get_route_url( 'shop_maestro_licenses' ) );
		exit();
	}


	public function get_remote_license_info(){
		
	}
}
