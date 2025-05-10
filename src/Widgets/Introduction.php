<?php

namespace ShopMaestro\Conductor\Widgets;

use ShopMaestro\Conductor\Contracts\Widget;

class Introduction extends Widget {
	public function __construct() {
		parent::__construct();
		$this->set_id( 'shop-maestro-intro' )
			 ->set_title( __( 'Welcome to the Shop Maestro Suite', 'shop-maestro' ) )
			 ->set_name( __( 'Introduction', 'shop-maestro' ) )
			 ->set_content( $this->content() );
	}

	/**
	 * Helper method to set the content of the dashboard widget.
	 */
	public function content(): string {
		return '<p>This is the content of the first widget</p>';
	}
}