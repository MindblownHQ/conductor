<?php

namespace ShopMaestro\Conductor\Contracts;

/**
 * A ledger class basically needs to keep stuff in an array in memory
 * like routes or update-checks.
 * 
 */
abstract class Ledger{

	protected array $ledger = [];

	/**
	 * Regiser an item to the ledger
	 */
	public function register( string $key, array $params = [] ): void {
		$this->ledger[ $key ] = $params;
	}

	/**
	 * Remove an item from the ledger
	 *
	 * @param string $slug
	 * @return void
	 */
	public function remove( string $slug ): void {
		if( isset( $this->ledger[ $key ] ) ){
			unset( $this->ledger[ $key ] );
		}
	}

	/**
	 * Returns one or all items in the ledger, depending on wether
	 * or not you pass the key.
	 */
	public function get( string $key = null ): array {
		if( !is_null( $key ) ){
			return $this->ledger[ $key ];
		}

		return $this->ledger;
	}
}
