<?php

namespace ShopMaestro\Conductor\Updates;

use ShopMaestro\Conductor\Contracts\Ledger;

class Plugins extends Ledger{

	protected const FEEDBACK_URL = 'https://wooping.io/wp-json/wooping/v1/';


	/**
	 * Regiser an item to the ledger
	 */
	public function register( string $key, array $params = [] ): void {
		
		// Do the default:
		parent::register( $key, $params );
		
		// Upon activation check if the data model is in order.
		register_activation_hook( $params['file'], function() use ( $key, $params ){

			// Log activation.
			$this->activated( $key, $params['version'] );

			// Run a callable if it's in the params.
			if( isset( $params['on_activate'] ) && is_callable( $params['on_activate'] ) ){
				call_user_func( $params['on_activate'] );
			}

			// Trigger an activation action. "conductor_activated_wooping_shop_health"
			\do_action( 'conductor_activated_'. str_replace( '-', '_', $key ) );
		});

		// Upon deactivation, uninstall the plugin
		register_deactivation_hook( $params['file'], function() use ( $key, $params ){

			// Log deactivation.
			$this->deactivated( $key, $params['version'] );

			// Run a callable if it's in the params.
			if( isset( $params['on_deactivate'] ) && is_callable( $params['on_deactivate'] ) ){
				call_user_func( $params['on_deactivate'] );
			}

			// Trigger a deactivation action.
			\do_action( 'conductor_deactivated_'. str_replace( '-', '_', $key ) );
		});
	}


	/**
	 * Return all registered premium plugins with Conductor
	 */
	public function premium(): array {
		return array_filter( $this->ledger, function( $plugin ){
			return ( isset( $plugin['is_premium'] ) && $plugin['is_premium'] );
		});
	}

	/**
	 * Send activation data back to Wooping
	 */
	public function activated( string $slug, string $version ): void {

		$url = self::FEEDBACK_URL . 'plugin/activated';
		\wp_remote_post( $url, $this->get_plugin_info( $slug, $version ) );
	}

	/**
	 * Send deactivation data back to Wooping
	 */
	public function deactivated( string $slug, string $version ): void {

		$url = self::FEEDBACK_URL . 'plugin/deactivated';
		\wp_remote_post( $url, $this->get_plugin_info( $slug, $version ) );
	}

	/**
	 * Send update data back to Wooping
	 *
	 * @param WP_Upgrader   $wp_upgrader Upgrader class.
	 * @param array<string> $options     Array with upgrader options (see https://developer.wordpress.org/reference/hooks/upgrader_process_complete/).
	 */
	public function updated( string $slug, string $version ): void {

		$url = self::FEEDBACK_URL . 'plugin/updated';
		\wp_remote_post( $url, $this->get_plugin_info( $slug, $version ) );
	}

	/**
	 * Returns an array of simple plugin information
	 */
	public function get_plugin_info( string $slug, string $version ): array {

		return [
			'body' => [
				'plugin'    => $slug,
				'version'   => $version,
				'timestamp' => \time(),
				'site_url'  => \get_site_url(),
			],
		];
	}

}
