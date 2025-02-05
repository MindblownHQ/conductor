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

	/**
	 * Register hooks for enqueueing scripts and styles
	 *
	 * @return void
	 */
	public function register_hooks(): void {

		// Create the updater class for each plugin
		\add_action( 'init', [ $this, 'create_updater' ]);

		// And sent a ping if plugins gets updated:
		\add_action( 'upgrader_process_complete', [ $this, 'register_update' ], 10, 2 );

	}

	/**
	 * Add update options for the registered plugins
	 *
	 * @return void
	 */
	public function create_updater(): void {
		
		$plugins = conductor()->plugins()->get();
		foreach( $plugins as $slug => $plugin ){

			// Register our custom updater, if it exists.
			if ( \class_exists( 'YahnisElsts\PluginUpdateChecker\v5\PucFactory' ) ) {
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
		}
	}

	public function register_update( WP_Upgrader $wp_upgrader, array $update_info ): void {

		$plugins = conductor()->plugins()->get();
		foreach( $plugins as $slug => $plugin ){

			// If this update contains one of our plugins, 
			// Sent a ping to our API and run a custom hook for that plugin.
			if( $this->verify_update_is_ours( $plugin['file'], $update_info ) ){

				// Sent a ping
				conductor()->plugins()->updated( $slug, $plugin['version'] );

				// And run a hook that looks like "conductor_plugin_shop_health_updated"
				$slug = str_replace( '-', '_', $slug );
				do_action( 'conductor_plugin_'. $slug .'_updated', $update_info, $wp_upgrader );
			}
		}
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
