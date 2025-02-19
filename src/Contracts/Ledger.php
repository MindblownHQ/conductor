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

		// always set a priority of 10, 20, 30...
		if( !isset( $params['priority'] ) ){
			$params['priority'] = ( ( sizeof( $this->ledger ) + 1 ) * 10 );
		}

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

	/**
	 * Sort this ledger on a specific field
	 */
	public function sort_by( string $field = 'priority', string $order = 'ASC' ): array {

		$data = $this->ledger;
		$key = array_keys( $data );
        $key = $key[0];
        $object = false;
    	
		uasort( $data, function( $a,$b ) use ( $object, $order, $field ){

            if( is_bool( $a ) || is_bool( $b ) ){
                return -1;
            }

            if( $object ){
                if( $order == null || $order == 'ASC' ){
        	  	    return strnatcmp( $a->{$field}, $b->{$field} );
                }else{
	  		        return strnatcmp( $b->{$field}, $a->{$field} );
                }
            }else{
                if( $order == null || $order == 'ASC' ){
                    return strnatcmp( $a[$field], $b[$field] );
                }else{
                    return strnatcmp( $b[$field], $a[$field] );
                }
            }
        });

		return $data;
	}
}
