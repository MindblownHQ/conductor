<?php

namespace ShopMaestro\Conductor\Middleware;

use Error;
use ShopMaestro\Conductor\Routing\Router;
use ShopMaestro\Conductor\Contracts\Interfaces\Middleware;

/**
 * Check the validity of a nonce for every post action defined by routes in shop-health/src/routes.php
 */
class ValidNonce implements Middleware {

	/**
	 * Run the handle function.
	 *
	 * @throws Error If the nonce does not match the correct route.
	 */
	public function handle(): bool {
		// a non-post request always succeeds.
		if ( Router::request_type() !== 'POST' ) {
			return true;
		}

		// check if it's set.
		if ( ! isset( $_POST['conductor_nonce'] ) || !isset( $_GET['conductor_route'] ) ) {
			return false;
		}

		// then verify.
		// phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$valid = \wp_verify_nonce( $_POST['conductor_nonce'], $_GET['conductor_route'] );
		if ( ! $valid ) {
			throw new Error( \esc_html__( 'Nonce doesn\'t match the current route', 'shop-maestro-conductor' ) );
		}

		return true;
	}
}
