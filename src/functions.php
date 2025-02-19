<?php
/**
 * Simple helper functions for shop health and other wooping plugins.
 */

use ShopMaestro\Conductor\Routing\Routes;
use ShopMaestro\Conductor\Updates\Plugins;
use ShopMaestro\Conductor\Routing\Settings;
use ShopMaestro\Conductor\Conductor;

if( ! function_exists( 'conductor' ) ) {

	function conductor(){
	
		global $shop_maestro_conductor;
		if ( is_null( $shop_maestro_conductor ) ){
			
			// Initiate conductor once
			$GLOBALS['shop_maestro_conductor'] = new Conductor( 
				new Routes(), 
				new Plugins(),
				new Settings()
			);

			return $GLOBALS['shop_maestro_conductor'];
		}

		return $shop_maestro_conductor;
	}
}

if( ! function_exists( 'register_conductor_route' ) ) {

	/**
	 * Register a new conductor route
	 */
	function register_conductor_route( string $route, array $params = [] ): void {
		conductor()->routes()->register( $route, $params );
	}
}


if( ! function_exists( 'register_conductor_plugin' ) ) {

	/**
	 * Register a new conductor plugin
	 */
	function register_conductor_plugin( string $slug, array $params = [] ): void {
		conductor()->plugins()->register( $slug, $params );
	}
}

if ( ! function_exists( 'conductor_get_route' ) ) {

	/**
	 * Get a route and construct a url from it.
	 */
	function conductor_get_route( string $route_name ): ?array {
		return conductor()->routes()->get( $route_name );
	}
}


if ( ! function_exists( 'conductor_get_route_url' ) ) {

	/**conductor_current_route
	 * Get a route and construct a url from it.
	 */
	function conductor_get_route_url( string $route_name ): ?string {
		$route = conductor_get_route( $route_name );

		if( empty( $route ) ){
			return null;
		} 
		
		if( $route['method'] !== 'GET' ){
			return add_query_arg( 'conductor_route', $route_name, admin_url() );
		}else{
			return add_query_arg( 'page', $route_name, admin_url('admin.php') );
		}
	}
}


if ( ! function_exists( 'conductor_nonce_field' ) ) {
	/**
	 * Echo a nonce field that abides by the router-conventions.
	 */
	function conductor_nonce_field( string $route ): string {
		return wp_nonce_field( $route, 'conductor_nonce', false );
	}
}


if ( ! function_exists( 'conductor_current_route' ) ) {
	/**
	 * Returns the current route, if available.
	 */
	function conductor_current_route(): ?array {
		return conductor()->routes()->current();
	}
}


if ( ! function_exists( 'conductor_is_route' ) ) {
	/**
	 * Check if the string provided matches the current route.
	 */
	function is_conductor_route( string $route_name ): bool {
		return true;

		//return ( conductor_current_route() === $route_name );
	}
}


if ( ! function_exists( 'conductor_template' ) ) {

	/**
	 * Returns and includes template-file.
	 *
 	 * @phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
	 */
	function conductor_template( string $template_name, array $attributes = [], $base_dir = null ): void {

		// Look for our parent folder
		if( is_null( $base_dir ) ){
			$base_dir = dirname( __DIR__, 1 );
		}

		$template = str_replace( '.', '/', $template_name );
		$file     = $base_dir . '/templates/' . $template . '.php';
		if ( file_exists( $file ) ) {

			// Extract args if there are any, this isn't best practice but for now, we're ignoring that.
			if ( is_array( $attributes ) && count( $attributes ) > 0 ) {
				extract( $attributes );  // phpcs:ignore 
			}

			require_once $file;
		}
	}
}

if ( ! function_exists( 'conductor_get_link' ) ) {
	/**
	 * Add parameters to outgoing links
	 *
	 * @param string $url    The URL.
	 * @param string $plugin The plugin name.
	 *
	 * @return string The url with extra parameters.
	 */
	function conductor_get_link( $url, $plugin = 'shop-health' ): string {

		$source = conductor_current_route();

		if ( empty( $source ) ) {
			$screen = get_current_screen();
			$source = $screen && property_exists( $screen, 'base' ) ? $screen->base : 'unknown';

			if ( $screen && property_exists( $screen, 'post_type' ) ) {
				$source .= '-' . $screen->post_type;
			}
		}

		$query_args = [
			'utm_medium' => $plugin,
			'utm_source' => $source,
		];

		return add_query_arg( $query_args, trailingslashit( $url ) );
	}
}
