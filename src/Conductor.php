<?php

namespace ShopMaestro\Conductor;

use ShopMaestro\Conductor\Admin\Assets;
use ShopMaestro\Conductor\Routing\Routes;
use ShopMaestro\Conductor\Updates\Plugins;
use ShopMaestro\Conductor\Updates\Updater;

use ShopMaestro\Conductor\Admin\Request;
use ShopMaestro\Conductor\Admin\Settings;
use ShopMaestro\Conductor\Admin\Navigation;

class Conductor{

	/**
	 * The conductor bootstrap instance.
	 */
	protected static $instance = null;

	/**
	 * The Conductor routes ledger
	 */
	protected Routes $routes;

	/**
	 * The Conductor plugin ledger
	 */
	protected Plugins $plugins;

	/**
	 * The Conductor settings ledger
	 */
	protected Settings $settings;

	protected string $plugin_folder;

	/**
	 * Init conductor, if the instance doesn't exist yet
	 */
	public function __construct( Routes $routes, Plugins $plugins, Settings $settings ) {
		$this->routes        = $routes;
		$this->plugins       = $plugins;
		$this->settings      = $settings;

		$this->init_hooks();
	}

	/**
	 * Hook into WordPress for certain tasks
	 */
	public function init_hooks(): void {
		( new Assets() )->register_hooks();
		( new Request() )->register_hooks();
		( new Navigation() )->register_hooks();
		( new Updater() )->register_hooks();
	}


	/**
	 * Return the Plugins ledger
	 */
	public function plugins(): Plugins {
		return $this->plugins;
	}

	/**
	 * Return the Routes ledger
	 */
	public function routes(): Routes {
		return $this->routes;
	}

	/**
	 * Return the Settings ledger
	 */
	public function settings(): Settings {
		return $this->settings;
	}


	/**
	 * Return the assets url for this Conductor instance
	 * 
	 * @return string
	 */
	public function get_assets_url( string $file_name ): string {
		
		// First, figure out which plugin launched this instance.
		$parent_plugin = $this->get_parent_plugin();

		// Then create the path to our assets.
		$path = $parent_plugin . '/vendor/shop-maestro/conductor/assets/dist/' . $file_name;
		return plugins_url( $path );
	}
	

	/**
	 * Which plugin launched the first (and thus _this_) version of Conductor?
	 *
	 * @return string
	 */
	public function get_parent_plugin(): string {
		// Get our directory, remove the vendor folders.
		$dir = __DIR__;
		$dir = str_replace( '/vendor/shop-maestro/conductor/src', '', $dir );

		// Get our main folder:
	    return basename( $dir );
	}
}
