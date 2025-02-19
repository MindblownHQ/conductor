<?php

namespace ShopMaestro\Conductor\Routing;

use ShopMaestro\Conductor\Contracts\Ledger;

class Routes extends Ledger{

	/**
	 * Returns the current route
	 */
	public function current(): ?array {
		
		if( isset( $_GET['conductor_route'] ) ){
			return $this->get( $_GET['conductor_route'] );
		}
		
		return null;
	}
}
