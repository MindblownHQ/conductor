<?php
namespace ShopMaestro\Conductor;

use ShopMaestro\Conductor\Routing\Routes;
use ShopMaestro\Conductor\Routing\Settings;
use ShopMaestro\Conductor\Updates\Plugins;
use ShopMaestro\Conductor\Updates\Updater;
use ShopMaestro\Conductor\Routing\Admin;

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

	/**
	 * Init conductor, if the instance doesn't exist yet
	 */
	public function __construct( Routes $routes, Plugins $plugins, Settings $settings ){
		$this->routes 	= $routes;
		$this->plugins 	= $plugins;
		$this->settings = $settings;
		$this->init_hooks();
	}

	/**
	 * Hook into WordPress for certain tasks
	 */
	public function init_hooks(): void {
		( new Admin() )->register_hooks();
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
}
