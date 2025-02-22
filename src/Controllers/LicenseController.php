<?php

namespace ShopMaestro\Conductor\Controllers;

use ShopMaestro\Conductor\Traits\PhonesHome;
use ShopMaestro\Conductor\Contracts\Controller;

class LicenseController extends Controller{

	use PhonesHome;

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

			// Fetch the remote license info, and save it locally
			$remote_license = $this->get_remote_license_info( $plugin_name, $license_key );
			update_option( $plugin_name.'_license_validated', $remote_license['validated'] );
			update_option( $plugin_name.'_license_expires', $remote_license['expires'] );
		}

		// Redirect back to the licenses page
		wp_redirect( conductor_get_route_url( 'shop_maestro_licenses' ) );
		exit();
	}


	/**
	 * Check the license with the wooping api remotely
	 *
	 * @return void
	 */
	public function get_remote_license_info( string $plugin_name, string $license_key ): array {
	
		// @temp; remove this later
		if( $plugin_name == 'socials' ){
			$plugin_name = 'wooping-socials';			
		}

		// Verify our license at the API
		$request = $this->post_request( 'verify-license', [
			'plugin' => $plugin_name,
			'license_key' => $license_key,
			'timestamp' => \time(),
			'site_url'  => \get_site_url(),
		]);

		if (is_wp_error($request)) {
			return [
				'message' => $request->get_error_message(),
				'validated' => false,
				'expires' => null
			];
		}

		// Otherwise, we can just return the request wholesale.
    	return $request; 
	}
}
