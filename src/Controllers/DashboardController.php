<?php

namespace ShopMaestro\Conductor\Controllers;

use ShopMaestro\Conductor\Contracts\Controller;
use ShopMaestro\Conductor\Contracts\Widget;
use ShopMaestro\Conductor\Widgets\Introduction;

final class DashboardController extends Controller {

	/**
	 * @var array|Widget[]
	 */
	protected array $default_widgets = [
		Introduction::class,
	];

	protected array $dashboard_widgets = [];

	protected array $default_dashboard_widgets = [
		'shop-maestro-intro',
	];

	protected array $available_widgets = [];

	public function __construct() {
		$this->dashboard_widgets();
		parent::__construct();
	}

	/**
	 * Display the dashboard template.
	 */
	public function display(): void {
		conductor_template( 'dashboard', [
			'available_widgets' => $this->available_widgets,
			'dashboard_widgets' => $this->dashboard_widgets,
		] );
	}

	/**
	 * Create an array of all widgets on the dashboard.
	 * A filter is present so custom widgets can be added to the dashboard.
	 */
	protected function registered_widgets(): array {

		$widgets = $this->default_widgets;
		foreach( conductor()->plugins()->get() as $plugin ){
			if( isset( $plugin['widgets'] ) && !empty( $plugin['widgets'] ) ){
				$widgets = array_merge( $plugin['widgets'], $widgets );
			}
		}


		$widgets = \apply_filters( 'shop-maestro/conductor/registered_widgets', $widgets );
		foreach ( $widgets as $key => $widget ) {
			if ( ! is_subclass_of( $widget, Widget::class ) ) {
				unset( $widgets[ $key ] );
				// @todo: Throw error that registered widget is not of the correct class.
			}
		}

		return $widgets;
	}

	/**
	 * Setup widgets for the dashboard.
	 * Determine what widgets should be shown on the dashboard and which should be selectable to add.
	 */
	public function dashboard_widgets(): void {
		
		// @todo, cross-reference this with the widgets in user_meta.
		//$dashboard_widgets  = \get_user_meta( get_current_user_id(), 'conductor_dashboard_widgets', true );

		$registered_widgets = $this->registered_widgets();

		// Default: just show every widget (for now)
		foreach ( $registered_widgets as $registered_widget ) {
			$widget = new $registered_widget();
			$this->dashboard_widgets[] = $widget;
		}
	}
}
