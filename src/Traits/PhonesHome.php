<?php

namespace ShopMaestro\Conductor\Traits;


/**
 * Trait PhonesHome
 *
 * This trait provides functionality to connect with the 
 * Shop Maestro API. 
 */
trait PhonesHome {

	/**
	 * Send a post request
	 *
	 * @param string $endpoint
	 * @param array $params
	 * @return array|\WP_Error
	 */
	public function post_request( string $endpoint, array $params = [] ): array|\WP_Error {
		
		// Create the request url.
		$url = $this->get_api_url() . $endpoint . '/' . $params['plugin'];
		$request = \wp_remote_post( $url, [
			'body'    => json_encode( $params ),
			'method'  => 'POST',
			'headers' => [
				'Content-Type' => 'application/json',
			]
		]);
		
		// Check if we're not encountering an error.
		if( \is_wp_error( $request ) ){
			return $request;
		}

		// Confirm we're dealing with a JSON response
		$content_type = \wp_remote_retrieve_header($request, 'content-type');
		if (!$content_type || stripos($content_type, 'application/json') === false) {
			return new \WP_Error( 'invalid_response', 'Invalid response format - expected JSON' );
		}

		// Return the body
		$json = \wp_remote_retrieve_body( $request );

		return json_decode( $json, true );
	}


	/**
	 * Return our api url
	 */
	public function get_api_url(): string {
		return \apply_filters( 'shop-maestro/conductor/api-url', 'https://wooping.io/wp-json/wooping/v1/' );
	}

}
