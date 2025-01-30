<?php

namespace ShopMaestro\Conductor\Contracts;

use Exception;
use ShopMaestro\Conductor\Middleware\IsAllowed;
use ShopMaestro\Conductor\Middleware\ValidNonce;

/**
 * The Controller Class
 */
abstract class Controller {

	/**
	 * Applied middleware for this controller
	 *
	 * @var array<string|array> $middleware
	 */
	protected static array $middleware = [ IsAllowed::class, ValidNonce::class ];

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->setup();
	}

	/**
	 * Run this at setup
	 */
	public function setup(): void {
	}

	/**
	 * Validate the middleware on this controller
	 */
	public function middleware( string $method ): bool {
		$passes     = true;
		$middleware = $this->middleware;

		// run middleware for a specific method.
		if ( isset( $middleware['for-method'][ $method ] ) ) {
			$subset = $middleware['for-method'][ $method ];
			$passes = $this->validate_middleware( $subset );
		}

		// skip middleware that doesn't need to run on this method.
		if ( isset( $middleware['except'] ) && \in_array( $method, $middleware['except'], true ) ) {
			return $passes;
		}

		// run general middleware.
		unset( $middleware['for-method'] );
		unset( $middleware['except'] );
		$passes = $this->validate_middleware( $middleware );
		return $passes;
	}

	/**
	 * Validate an array of middleware, by running each middleware class
	 *
	 * @param array<string> $middleware An array of strings that maps to the registered middleware in /Middleware/Register.php.
	 * @throws Exception If the middleware doesn't exist.
	 */
	public function validate_middleware( array $middleware ): bool {

		$passes   = true;
		$register = new MiddlewareRegister();

		foreach ( $middleware as $instance ) {
			// get the instance.
			$instance = new $instance;

			// if the instance doesn't exist, throw an error.
			if ( \is_null( $instance ) ) {
				throw new Exception( \sprintf( \esc_html( 'Middleware %s not found.' ), \esc_html( $slug ) ) );
			}

			// run the middleware and see if it validates.
			// middleware can pass, fail or redirect before coming back to this loop.
			$response = $instance->handle();
			if ( ! \is_bool( $response ) || $response === false ) {
				$passes = false;
			}
		}

		return $passes;
	}

	/**
	 * Return the middleware as an array
	 */
	public static function get_middleware(): array {
		return static::$middleware;
	}

	/**
	 * Handle calls to missing methods on the controller.
	 * Ignore sniff about how $parameters isn't used.
	 *
	 * @throws Exception If a method called in this controller doesn't exist.
	 */
	public function __call( string $method, array $parameters ): void { //phpcs:ignore
		throw new Exception(
			\sprintf(
				'Method %s::%s does not exist.',
				\esc_html( static::class ),
				\esc_html( $method )
			)
		);
	}
}
