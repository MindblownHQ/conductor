<?php

namespace ShopMaestro\Conductor\Contracts;

use ShopMaestro\Conductor\Contracts\Interfaces\Hookable;
use ShopMaestro\Conductor\Middleware\IsAllowed;

/**
 * The Hookable class
 */
abstract class Rest implements Hookable {

	/**
	 * Namespace used for custom endpoints.
	 */
	public const NAMESPACE = 'conductor/v1';

	/**
	 * Register hooks
	 *
	 * @return void
	 */
	public function register_hooks(): void {
		\add_action( 'rest_api_init', [ $this, 'register_endpoints' ] );
	}

	/**
	 * The REST Class always has and initiates a register_endpoints function
	 *
	 * @return void
	 */
	abstract public function register_endpoints(): void;

	/**
	 * Has Permission
	 */
	public function has_permission(): bool {
		return ( new IsAllowed() )->handle();
	}
}
