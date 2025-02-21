<?php

namespace ShopMaestro\Conductor\Traits;

use WC_Admin_Notices;

/**
 * Trait PhonesHome
 *
 * This trait provides functionality to connect with the 
 * Shop Maestro API. 
 */
trait PhonesHome {

	/**
	 * The url constants with which we communicate to the world outside.
	 */
	protected const API_URL = 'https://wooping.test/wp-json/wooping/v1/';


	public function post_request( string $endpoint, array $params = [] ): array {
		
		// Create the request url.
		$url = self::API_URL . $endpoint;
		$request = wp_remote_post( $url, [
			'body'    => json_encode( $params ),
			'method'  => 'POST',
			'headers' => [
				'Content-Type' => 'application/json',
			]
		]);
		
		// Check if we're not encountering an error.
		if( is_wp_error( $request ) ){
			return [ 'error' => $request->get_error_message() ];
		}

		// Return the body
		$json = wp_remote_retrieve_body( $request );
		return json_decode( $json, true );
	}

}
