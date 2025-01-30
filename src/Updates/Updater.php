<?php

namespace ShopMaestro\Conductor\Updates;

use Throwable;
use WP_Upgrader;
use ShopMaestro\Conductor\Contracts\Interfaces\Hookable;
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

/**
 * Class Updates
 *
 * This class handles updates for all Shop Maestro plugins.
 */
class Updater implements Hookable {

	/**
	 * The url constants with which we communicate to the world outside.
	 */
	protected const UPDATE_URL   = 'https://updates.wooping.io/';
	protected const FEEDBACK_URL = 'https://wooping.io/wp-json/wooping/v1/';

	/**
	 * Register hooks for enqueueing scripts and styles
	 *
	 * @return void
	 */
	public function register_hooks(): void {

		$plugins = conductor()->plugins()->get();
		foreach( $plugins as $slug => $plugin ){

			// Register our custom updater, if it exists.
			if ( \class_exists( 'YahnisElsts\PluginUpdateChecker\v5\PucFactory' ) ) {
				$this->build_update_checker( $slug, $plugin );
			}

			// Sent a ping if the plugin gets activated
			register_activation_hook( $plugin['file'], function() {
				$this->plugin_activated( $plugin );
			} );

			// Sent a ping back to our API if the plugin gets deactivated
			register_deactivation_hook( $plugin['file'], function() {
				$this->plugin_deactivated( $plugin );
			} );
		}

		// And sent a ping if plugins gets updated:
		\add_action( 'upgrader_process_complete', function( WP_Upgrader $wp_upgrader, array $update_info  ){
			foreach( $plugins as $slug => $plugin ){

				// If this update contains one of our plugins, 
				// Sent a ping to our API and run a custom hook for that plugin.
				if( $this->verify_update_is_ours( $plugin['file'], $update_info ) ){

					// Sent a ping
					$this->plugin_updated( $plugin );

					// And run a hook that looks like "conductor_plugin_shop_health_updated"
					$slug = str_replace( '-', '_', $slug );
					do_action( 'conductor_plugin_'. $slug .'_updated', $update_info, $wp_upgrader );
				}
			}
		}, 10, 2 );

	}


	/**
	 * Initiate a plugin update checker for each plugin
	 */
	public function build_update_checker( $slug, $plugin ): void {
	
		try {
			PucFactory::buildUpdateChecker(
				static::UPDATE_URL . $slug ,
				$plugin['file'],
				$slug
			);

		} catch ( Throwable $error ) {
			// Do nothing. Just no new updates found.
		}
	}

	/**
	 * Send activation data back to Wooping
	 */
	public function plugin_activated( array $plugin ): void {

		$url = self::FEEDBACK_URL . 'plugin/activated';
		\wp_remote_post( $url, $this->get_plugin_info( $plugin ) );
	}

	/**
	 * Send deactivation data back to Wooping
	 */
	public function plugin_deactivated( array $plugin ): void {

		$url = self::FEEDBACK_URL . 'plugin/deactivated';
		\wp_remote_post( $url, $this->get_plugin_info( $plugin ) );
	}

	/**
	 * Send update data back to Wooping
	 *
	 * @param WP_Upgrader   $wp_upgrader Upgrader class.
	 * @param array<string> $options     Array with upgrader options (see https://developer.wordpress.org/reference/hooks/upgrader_process_complete/).
	 */
	public function plugin_updated( array $plugin ): void {

		$url = self::FEEDBACK_URL . 'plugin/updated';
		\wp_remote_post( $url, $this->get_plugin_info( $plugin ) );
	}

	/**
	 * Returns an array of simple plugin information
	 */
	public function get_plugin_info( array $plugin ): array {
		return [
			'body' => [
				'plugin'    => $plugin['slug'],
				'version'   => $plugin['current_version'],
				'timestamp' => \time(),
				'site_url'  => \get_site_url(),
			],
		];
	}

	/**
	 * Check if an update being run belongs to us
	 */
	public function verify_update_is_ours( string $plugin_file, array $update_info ): bool {
		if ( $update_info['action'] === 'update' && $update_info['type'] === 'plugin' ) {
			// loop through the plugins that we're updating.
			foreach ( $update_info['plugins'] as $plugin ) {
				// check if we're updating this plugin.
				if ( $plugin === \plugin_basename( $plugin_file ) ) {
					return true;
				}
			}
		}

		return false;
	}
}
