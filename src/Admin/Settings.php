<?php

namespace ShopMaestro\Conductor\Admin;

use Exception;

class Settings{

	/**
	 * Our settings ledger
	 */
	protected array $ledger = [];
	
	/**
	 * Register a settings page
	 */
	public function register_page( string $key, $params ): void {
		
		// create the settings ledger:
		$this->ledger[ $key ] = $params;
	}

	/**
	 * Returns one setting in a settings-ledger
	 * 
	 * @return mixed option_value
	 */
	public function get( string $key , string $option_name, $default = null ) {
		
		// Get the option key,
		$option_key = $this->get_option_key( $key, $option_name );

		// Return with our default:
		return \get_option( $option_key, $default );
	}


	/**
	 * Update a setting
	 */ 
	public function update( string $key, string $option_name, $option_value ): bool {
		
		// Get the option key,
		$option_key = $this->get_option_key( $key, $option_name );

		// Return with our default:
		return \update_option( $option_key, $option_value );
	}


	/**
	 * Return the key of an option
	 * @return string
	 */
	public function get_option_key( string $key, string $option_name ): string {
		
		if( isset( $this->ledger[ $key ] ) ){
			$params = $this->ledger[ $key ];
			$plugin_key = ( isset( $params['key'] ) ? $params['key'] : $key );
			return 'shop_maestro_'.$plugin_key.'_'.$option_name;
		}

		return '';
	}


	/**
	 * Return all setting tabs
	 *
	 * @return array
	 */
	public function tabs(): array {
		return $this->ledger;
	}

	/**
	 * Return the active tab
	 *
	 * @return string
	 */
	public function active_tab(): string{
		if( isset( $_GET['tab'] ) ){
			return $_GET['tab'];
		}

		$first = array_keys( $this->tabs() )[0];
		return $first;
	}

	/**
	 * Display a tab in our settings
	 */
	public function display_tab( string $key ): string
	{
		$tab = $this->tab( $key );
		
		// Check if we have a tab and if that tab has a callback
		if( is_null( $tab ) || !isset( $tab['callback'] ) || !is_array( $tab['callback'] ) ){
			throw new Exception( 'Tab not found, or it doesn\'t have a callback: '.$tab );
		}

		// Check if our callback exists:
		if( !method_exists( $tab['callback'][0], $tab['callback'][1] ) ){
			throw new Exception( 'Callback doesn\'t exist: '. explode( $tab['callback'], ' ' ) );
		}

		// Init the callback:
		$instance = new $tab['callback'][0];
		$method = $tab['callback'][1];

		// Run our function and return the result
		ob_start();
			$instance->{$method}();
		return ob_get_clean();
	}

	/**
	 * Return a single setting tab
	 */
	public function tab( string $key ): ?array {
		if( isset( $this->ledger[ $key ] ) ){
			return $this->ledger[ $key ];
		}

		return null;
	}
}
