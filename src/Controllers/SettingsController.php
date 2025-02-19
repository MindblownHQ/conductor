<?php

namespace ShopMaestro\Conductor\Controllers;

use ShopMaestro\Conductor\Contracts\Controller;

class SettingsController extends Controller{

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function display(): void {
		// render the view.
		$settings = conductor()->settings();
		\conductor_template( 'settings-page', \compact( 'settings' ) );
	}

	/**
	 * Update the settings on this page
	 *
	 * @return void
	 */
	public function update(): void {

		$key = $_POST['shop_maestro_settings_key'] ?? null;
		$settings = conductor()->settings();
		
		// Check if we have a settings key
		if( !is_null( $key ) ){
			
			// Remove nonce and key:
			unset( $_POST['shop_maestro_settings_key'] );
			unset( $_POST['conductor_nonce'] ); 
		
			// Save each value:
			foreach( $_POST as $option_name => $option_value ){
				$settings->update( $key, $option_name, $option_value );
			}
		}

		// Redirect back to the settings page
		wp_redirect( conductor_get_route_url( 'shop_maestro_settings' ) );
		exit();
	}
}
