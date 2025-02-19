<?php

namespace ShopMaestro\Conductor\Admin;

use ShopMaestro\Conductor\Routing\Router;
use ShopMaestro\Conductor\Contracts\Interfaces\Hookable;

/**
 * Register all admin pages and display their menu items.
 */
class Request implements Hookable{

	public function register_hooks(): void {

		// Check our current request, see if we need to do anything.
		\add_action( 'init', [ $this, 'handle_post_request'], 10 );

	}

	/**
	 * See if we need to handle a POST request
	 */
	public function handle_post_request(): void {
		
		// Only do stuff if this is a valid post-request:
		if ( Router::request_type() === 'POST' ) {
			$route = conductor()->routes()->current();

			if( Router::is_valid_route( $route ) ){
				Router::handle_callback( $route );
				die();	
			}
		}
	}
}
