<?php 
namespace ShopMaestro\Conductor\Routing;

use Error;

/**
 * This class handles all routing of the routes that have been set in the
 * Routes ledger.
 */
class Router {

	/**
	 * Handle a single callback
	 */
	public static function handle_callback( array $route ): void{

		// get the controller and method.
		$resolved_route = static::get_callback( $route );
		$controller     = $resolved_route[0];
		$method         = $resolved_route[1];

		// Check if middleware passed
		if( static::does_middleware_pass( $route, $controller, $method ) === false ){
			\wp_die( 'Middleware went wrong.' );
		}

		// We're allowed to be here, run our callback:
		$controller->{$method}();
	}


	/**
	 * Figure out if a route is valid
	 *
	 * @return boolean
	 */
	public static function is_valid_route( $route ): bool {
		
		// a route needs to be an array with a method and a callback
		if( !is_array( $route ) || !isset( $route['method'] ) && !isset( $route['callback'] ) ){
			return false;
		}

		// the method needs to match our current request
		$method = ( strpos( $route['method'], ',' ) === false ? [ $route['method'] ] : explode( ',', $route['method'] ) );

		if( !in_array( static::request_type(), $method )){
			return false;
		}

		// if that checks out, our route is okay:
		return true;
	}


	/**
	 * Return the request type
	 */
	public static function request_type(): string {
		if(	isset( $_SERVER['REQUEST_METHOD'] ) ){
			return $_SERVER['REQUEST_METHOD'];
		}

		//assume it's a GET request otherwise:
		return 'GET';
	}

	/**
	 * Run our middleware, see if we're allowed to visit this route
	 * 
	 * @return boolean
	 */
	protected static function does_middleware_pass( $route, $controller, $method ): bool {

		// If this route has middleware, test it:
		if( isset( $route['middleware'] ) && is_array( $route['middleware'] ) && !empty( $route['middelware'] ) ){
			return $controller->validate_middleware( $route['middleware'] );
		
		// Else, our controller might have middleware:
		}else{
			return $controller->middleware( $method );
		}
	}



	/**
	 * Returns the controller and method to trigger of a router endpoint.
	 *
	 * @throws Error If a route doesn't have a valid trigger.
	 * @throws Error If a method called on a controller doesn't exist.
	 *
	 * @return array<string> A call_user_func_array compatible array of Class and method.
	 */
	protected static function get_callback( array $route ): array {

		// set the callback.
		$callback = ( isset( $route['callback'] ) ? $route['callback'] : [] );

		// check if this route is added well.
		if ( empty( $callback ) || ! \is_array( $callback ) ) {
			throw new Error( 'A valid route needs to have a Controller class and a method defined with the triggers key as [Controller::class, "method"].' );
		}

		// set class and method as seperate variables.
		$instance = new $callback[0]();
		$method   = $callback[1];

		// test if this method exists on this controller.
		if ( ! \method_exists( $instance, $method ) ) {
			throw new Error(
				\sprintf(
					\esc_html( 'Method %1$s on controller %2$s not found' ),
					\esc_html( $method ),
					\esc_html( $instance )
				)
			);
		}

		return [ $instance, $method ]; // return the initiated controller and its method.
	}
}
