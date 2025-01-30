<?php
namespace ShopMaestro\Conductor;

use ShopMaestro\Conductor\Routing\Routes;
use ShopMaestro\Conductor\Updates\Plugins;
use ShopMaestro\Conductor\Updates\Updater;

class Conductor{

	/**
	 * The conductor bootstrap instance.
	 */
	protected static ?Conductor $instance = null;

	/**
	 * The Conductor routes ledger
	 */
	protected Routes $routes;

	/**
	 * The Conductor plugin ledger
	 */
	protected Plugins $plugins;

	/**
	 * Init conductor, if the instance doesn't exist yet
	 */
	protected function init(): void {

		if ( is_null( static::$instance ) ){
			$instance = static::instance();
			$instance->init_hooks();
		}
	}

	/**
	 * Hook into WordPress for certain tasks
	 */
	public function init_hooks(): void {
		( new Updater() )->register_hooks();
		//( new Licenses() )->register_hooks();
	}


	/**
	 * Return the Plugins ledger
	 */
	protected function plugins(): Plugins {
		if( is_null( $this->plugins ) ){
			$this->plugins = new Plugins();
		}

		return $this->plugins;
	}

	/**
	 * Return the Routes ledger
	 */
	protected function routes(): Routes {
		if( is_null( $this->routes ) ){
			$this->routes = new Routes();
		}

		return $this->routes;
	}

	 /**
	 * Return the Conductor Instance
	 */
	public static function instance(): Conductor {

		if ( is_null( static::$instance ) ){
			static::$instance = new static();
		}
		return static::$instance;
	}
}
