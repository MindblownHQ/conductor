<?php

namespace ShopMaestro\Conductor\Controllers;

use ShopMaestro\Conductor\Contracts\Controller;
use ShopMaestro\Conductor\Contracts\Widget;
use ShopMaestro\Conductor\Widgets\Introduction;

final class DashboardController extends Controller {
	/**
	 * @var array|\Widget[]
	 */
	protected array $widgets = [
		Introduction::class,
	];

	public function __construct() {
		parent::__construct();

		$this->widgets = $this->widgets();
	}

	/**
	 * Display the dashboard template.
	 */
	public function display(): void {
		conductor_template( 'dashboard', [
			'widgets' => $this->widgets
		] );
	}

	/**
	 * Create an array of all widgets on the dashboard.
	 * A filter is present so custom widgets can be added to the dashboard.
	 */
	protected function widgets(): array {
		$widgets = \apply_filters( 'shop-maestro/conductor/widgets', $this->widgets );
		foreach ( $widgets as $key => $widget ) {
			if( ! is_subclass_of( $widget, Widget::class ) ) {
				unset( $widgets[ $key ] );
				// @todo: Throw error that registered widget is not of the correct class.
			}
		}

		return $widgets;
	}

}
