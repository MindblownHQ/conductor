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
		$widgets = \apply_filters( 'shop-maestro/conductor/registered_widgets', $this->default_widgets );
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
		$dashboard_widgets  = \get_option( 'shop-maestro/conductor/dashboard_widgets', $this->default_dashboard_widgets );
		$registered_widgets = $this->registered_widgets();

		foreach ( $registered_widgets as $registered_widget ) {
			$widget = new $registered_widget();
			if ( ! in_array( $widget->get_id(), $dashboard_widgets, true ) ) {
				$this->available_widgets[ $widget->get_id() ] = $widget->get_name();
			} else {
				$this->dashboard_widgets[] = $widget;
			}
		}
	}
}
