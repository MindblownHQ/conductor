<?php

namespace ShopMaestro\Conductor\Updates;

use ShopMaestro\Conductor\Contracts\Ledger;
use ShopMaestro\Conductor\Traits\PhonesHome;

class Plugins extends Ledger{

	use PhonesHome;

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
	 * Simple and quick way to get all the license information
	 */
	public function get_license( string $slug ): array {

		// Get validated and its icon
		$validated = \get_option( $slug.'_license_validated', null );
		switch( $validated ){
			// not yet validated
			case null:
				$icon = '<svg xmlns="http://www.w3.org/2000/svg" style="width: 1.4rem; height: 1.4rem;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>';
				break;
			case true:
				$icon = '<svg xmlns="http://www.w3.org/2000/svg" style="width: 1.4rem; height: 1.4rem;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="green" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" /></svg>';
				break;
			case false:
				$icon = '<svg xmlns="http://www.w3.org/2000/svg" style="width: 1.4rem; height: 1.4rem;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="crimson" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>';
				break;
		}

		return [
			'key' 			=> \get_option( $slug.'_license_key', '' ),
			'expires'		=> \get_option( $slug.'_license_expires', null ),
			'validated' 	=> $validated,
			'icon'			=> $icon
		];
	}

	/**
	 * Send activation data back to Wooping
	 */
	public function activated( string $slug, string $version ): void {

		$this->post_request( 'plugin/activated', $this->get_plugin_info( $slug, $version ) );
	}

	/**
	 * Send deactivation data back to Wooping
	 */
	public function deactivated( string $slug, string $version ): void {

		$this->post_request( 'plugin/deactivated', $this->get_plugin_info( $slug, $version ) );
	}

	/**
	 * Send update data back to Wooping
	 *
	 * @param WP_Upgrader   $wp_upgrader Upgrader class.
	 * @param array<string> $options     Array with upgrader options (see https://developer.wordpress.org/reference/hooks/upgrader_process_complete/).
	 */
	public function updated( string $slug, string $version ): void {

		$this->post_request( 'plugin/updated', $this->get_plugin_info( $slug, $version ) );
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
