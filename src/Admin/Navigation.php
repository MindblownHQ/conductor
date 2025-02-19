<?php

namespace ShopMaestro\Conductor\Admin;

use WP_Rest_Server;
use ShopMaestro\Conductor\Routing\Router;
use ShopMaestro\Conductor\Controllers\SettingsController;
use ShopMaestro\Conductor\Controllers\DashboardController;
use ShopMaestro\Conductor\Controllers\LicenseController;
use ShopMaestro\Conductor\Contracts\Interfaces\Hookable;

/**
 * Register all admin pages and display their menu items.
 */
class Navigation implements Hookable{

	public function register_hooks(): void {

		// first, register our custom maestro routes
		\add_action( 'init', [ $this, 'register_maestro_routes'], 1 );

		// Set the actions to hook into.
		\add_action( 'admin_menu', [ $this, 'add_menu_pages' ], 100 );
		//\add_filter( 'submenu_file', [ $this, 'set_current_menu_item' ] );
	}

	/**
	 * Add our custom Maestro routes and pages
	 */
	public function register_maestro_routes(): void {

		// Register the main route:
		conductor()->routes()->register( 'shop_maestro_dashboard', [
			'method' => WP_Rest_Server::READABLE,
			'callback' => [ DashboardController::class, 'display' ],
			'priority' => 1,
			'icon' => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzEyIiBoZWlnaHQ9IjMwMSIgdmlld0JveD0iMCAwIDMxMiAzMDEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGZpbGw9IiNhN2FhYWQiIGQ9Ik0xOTkuNTExIDI4My44MzVDMTk5LjUxMSAyOTMuMzA1IDE5MS45MzYgMzAwLjk3NiAxODIuNTg1IDMwMC45NzZDMTczLjIzNSAzMDAuOTc2IDE2NS42NiAyOTMuMzA1IDE2NS42NiAyODMuODM1QzE2NS42NiAyNzQuMzY2IDE3My4yMzUgMjY2LjY5NSAxODIuNTg1IDI2Ni42OTVDMTkxLjkzNiAyNjYuNjk1IDE5OS41MTEgMjc0LjM2NiAxOTkuNTExIDI4My44MzVaTTMxMS45OTkgMTQuNTQ3MUwzMTEuMjg5IDc2LjkwMDdDMzExLjIxOCA4Mi40MTQ1IDMwOC4xNDEgODcuMzA1IDMwMy4yNDEgODkuNzAyM0MyOTguMzQxIDkyLjA3NTYgMjkyLjYxMiA5MS40NTIzIDI4OC4zNTEgODguMDQ4MkwyODQuNDkyIDg0Ljk3OTZMMjM1LjkxOCAxOTUuNTY3QzIzNC4wNDggMTk5LjgzNCAyMjkuODU4IDIwMi41OTEgMjI1LjI0MiAyMDIuNTkxSDEyOS45NjNDMTI4LjYzNyAyMDQuNDM3IDEyNy4yNDEgMjA2LjIxMSAxMjUuNzk3IDIwNy44NDFDMTE3LjI1MSAyMTcuNTAyIDEwNy4zMDkgMjIyLjIyNSA5NS40NDk2IDIyMi4yMjVDOTQuNjY4NCAyMjIuMjI1IDkzLjkxMDkgMjIyLjIyNSA5My4xMDYxIDIyMi4xNzdDOTIuMDg4MiAyMjIuMTI5IDkxLjA0NjYgMjIyLjAwOSA5MC4wMjg3IDIyMS44ODlMODMuODAzIDIzNS41M0gyMDIuNzU0QzIwOS4yMTYgMjM1LjUzIDIxNC40MjQgMjQwLjgyOCAyMTQuNDI0IDI0Ny4zNDlDMjE0LjQyNCAyNTMuODY5IDIwOS4xOTMgMjU5LjE2NyAyMDIuNzU0IDI1OS4xNjdINjUuNTA0N0M2MS41Mjc5IDI1OS4xNjcgNTcuODExNCAyNTcuMTA2IDU1LjY1NzMgMjUzLjcwMUM1My41MDMxIDI1MC4yOTcgNTMuMjE5MSAyNDYuMDMgNTQuODk5OCAyNDIuMzYyTDY4LjgxODggMjExLjk2NEM2OC4wODUgMjExLjI5MyA2Ny4zNzQ4IDIxMC41NzQgNjYuNjY0NyAyMDkuODU1QzU2LjAzNiAxOTguNjM1IDQ4Ljg4NzEgMTgwLjQ0IDQ1LjQzMTEgMTU1LjcyNEM0My4zOTUzIDE0MS4xOTYgNDIuODAzNSAxMjUuMDYyIDQzLjY1NTcgMTA5LjgxNkwzMi41NzczIDExNC44MDJDMjkuNTk0NiAxMTYuMTQ0IDI2LjQyMjYgMTE2Ljg0IDIzLjE3OTYgMTE2Ljg0QzE0LjAxODYgMTE2Ljg0IDUuNzA5NzkgMTExLjM3NCAxLjk5MzMyIDEwMi44ODdDLTMuMTkwOCA5MS4wNjg4IDIuMDg4MDEgNzcuMTY0NCAxMy43NTgyIDcxLjg5MDRMNjkuMzM5NiA0Ni44NjI2QzcxLjM5OSA0NS45Mjc3IDczLjU3NjggNDUuMzI4NCA3NS44MjU2IDQ1LjA0MDdDODguMDg3NiA0MS41ODg2IDEwMS4zNjggNDUuMjMyNSAxMDkuMDYxIDU0LjI3MDNDMTE1LjQwNSA2MS43MDE5IDExNy41MTIgNzIuMjI2IDExNy45ODUgODIuNTU4NEMxMjYuMzg5IDcwLjcxNTcgMTM3LjQ5MSA1Ni44ODMzIDE0OC45OTUgNDcuOTE3NEwxNDkuMTE0IDQ3LjgyMTVDMTYyLjY3NyAzNy42NTcgMTc3Ljg1MSAzNC45NDgxIDE4OS42ODcgNDAuNjA1N0MxOTYuNTI4IDQzLjg2NiAyMDguMTI3IDUyLjg3OTggMjA5Ljg1NSA3Ni42MzdDMjExLjkxNSA3NC4zMTE3IDIxNC4xMTYgNzEuODkwNCAyMTYuNDYgNjkuNDIxMkMyMjIuMDk0IDYzLjQyNzkgMjI5Ljk3NiA1NS41MTY5IDIzOC41MjIgNDguMzI1TDIyOS40NTYgNDEuMTA5MUMyMjUuMTcxIDM3LjcwNSAyMjMuMjA2IDMyLjIzOTEgMjI0LjM0MiAyNi44NDUyQzIyNS40NzkgMjEuNDUxMyAyMjkuNDMyIDE3LjI1NiAyMzQuNzExIDE1Ljg4OTZMMjk0LjI5MyAwLjQ1MDk3MUMyOTguNjAxIC0wLjY1MTc4NSAzMDMuMDc1IDAuMjgzMTYgMzA2LjU3OCAzLjA4OEMzMTAuMDgyIDUuODY4ODYgMzEyLjA0NyAxMC4wNDAyIDMxMS45OTkgMTQuNTQ3MVpNMjk2LjM5OSAxNi4xNzcyTDI0MS4yMiAzMC40NDExTDI2My45NjkgNDguNTQwN0wyNTUuNjYgNTQuNjc3OEMyNDUuMTAzIDYyLjQ2OSAyMzQuNjE2IDcyLjg5NzMgMjI3LjY4IDgwLjI1N0MyMTcuODA5IDkwLjczMzEgMjEwLjM3NiAxMDAuMDU5IDIwNi45OTEgMTA0Ljg1M0wxODcuMzY3IDEzMi43MzRMMTkyLjk3NyA5OC45NTU5QzE5NC4yMDggOTEuNTk2MiAxOTQuNjU4IDg0LjgzNTggMTk0LjM1IDc4LjkxNDVDMTkzLjY4OCA2Ni41NDQ0IDE4OS42NjMgNTcuOTg2MSAxODMuMDEyIDU0LjgyMTZDMTc2LjQ1NCA1MS43MDUyIDE2Ny4yNDYgNTMuNzkwOCAxNTguMzkzIDYwLjQwNzNDMTQ4Ljk0OCA2Ny43OTEgMTM3LjUxNCA4MS4xOTE5IDEyNi4xNzYgOTguMDkyOEMxMjIuMzY0IDEwMy43NzQgMTE4LjY5NSAxMDkuNjk2IDExNS41NDcgMTE1LjIxTDk3Ljk1ODggMTQ2LjA2M0wxMDEuMjQ5IDEwOC4wNjZDMTAxLjYwNCAxMDQuMDg2IDEwMi4wMDcgOTkuNTU1MiAxMDIuMjQzIDk1LjAyNDNDMTAyLjUyNyA4OS4yNzA4IDEwMy40MjcgNzEuNzcwNSA5Ny4yMDEzIDY0LjQ4MjdDOTMuMzY2NSA1OS45NzU4IDg2LjA5OTIgNTguMjQ5OCA3OS41NDIxIDYwLjI2MzVMNzguNzg0NiA2MC41MDMyTDc4LjAwMzUgNjAuNTc1MkM3Ny4xNzQ5IDYwLjY0NzEgNzYuMzcwMSA2MC44NjI4IDc1LjYxMjYgNjEuMTk4NUwyMC4wMzEyIDg2LjIyNjJDMTguMTg0OCA4Ny4wNjUzIDE2Ljc0MDkgODguNTc1NiAxNi4wMzA3IDkwLjQ5MzRDMTUuMjk2OSA5Mi40MTEzIDE1LjM0NDIgOTQuNDk2OSAxNi4xNzI3IDk2LjM5MDhDMTcuNDAzNyA5OS4xNzE2IDIwLjEyNTkgMTAwLjk3IDIzLjEzMjIgMTAwLjk3QzI0LjE5NzUgMTAwLjk3IDI1LjIzOSAxMDAuNzU0IDI2LjIwOTYgMTAwLjI5OEw2MS45MzAzIDg0LjIxMjVMNjAuMjczMyA5OC4wMjA5QzU2LjAxMjQgMTMzLjM4MSA2MC43MjMgMTgwLjcyOCA3Ny44ODUxIDE5OC44MjdDODEuMTc1NSAyMDIuMzAzIDg0LjcwMjYgMjA0LjUzMyA4OC42Nzk0IDIwNS42NkM5MC40NTQ4IDIwNS45OTUgOTIuMjA2NSAyMDYuMjM1IDkzLjg4NzIgMjA2LjMwN0MxMDIuMDA3IDIwNi43MTQgMTA4LjI1NiAyMDMuOTA5IDExNC4xNzQgMTk3LjIyMUMxMjEuMTU3IDE4OS4zMzQgMTI2Ljg2MiAxNzYuODQ0IDEzMi44OTggMTYzLjYzNUMxMzcuODIyIDE1Mi44NzEgMTQyLjkxMiAxNDEuNzQ4IDE0OS40OTIgMTMwLjg4OEwxNjYuNDE4IDEwMy4wMDdMMTYzLjg4NSAxMzUuNjM1QzE2MS45NDQgMTYwLjgwNiAxNjguMjE3IDE3OS4wNSAxODAuNzE1IDE4NC40MTlDMTg4LjY2OSAxODcuODQ4IDE5OC4wNjcgMTg1LjE4NyAyMDQuNjk1IDE3Ny42NTlMMjA1LjA1IDE3Ny4yNzZDMjA5LjI0IDE3My4xMDQgMjEzLjMxMSAxNjQuNjE4IDIxOC45OTMgMTUyLjg0N0MyMjQuODQgMTQwLjY5MyAyMzIuMTA3IDEyNS41NjYgMjQzLjIzMyAxMDcuMjc0QzI1MC4zODEgOTUuMzM2IDI1OC4yODggODQuODExOCAyNjUuMjcxIDc1LjUzNDNDMjY3Ljg5OSA3Mi4wMzQyIDI3MC4zNiA2OC43NDk5IDI3Mi41NjIgNjUuNzA1NEwyNzcuMzIgNTkuMDg4OEwyOTUuNzYgNzMuNzYwM0wyOTYuNDIzIDE2LjA4MTNMMjk2LjM5OSAxNi4xNzcyWk03Ni43MDE1IDI2Ni43MTlDNjcuMzUxMSAyNjYuNzE5IDU5Ljc3NjIgMjc0LjM5IDU5Ljc3NjIgMjgzLjg1OUM1OS43NzYyIDI5My4zMjkgNjcuMzUxMSAzMDEgNzYuNzAxNSAzMDFDODYuMDUxOSAzMDEgOTMuNjI2OCAyOTMuMzI5IDkzLjYyNjggMjgzLjg1OUM5My42MjY4IDI3NC4zOSA4Ni4wNTE5IDI2Ni43MTkgNzYuNzAxNSAyNjYuNzE5WiIvPgo8L3N2Zz4K'
		]);

		// Register the settingspage if we have registered settings tabs:
		if( sizeof( conductor()->settings()->tabs() ) > 0 ){
			
			// Show page
			conductor()->routes()->register( 'shop_maestro_settings', [
				'method' => WP_Rest_Server::READABLE,
				'callback' => [ SettingsController::class, 'display' ],
				'menu_label' => __( 'Settings', 'shop-maestro'),
				'priority' => 999
			]);

			// Save function
			conductor()->routes()->register( 'shop_maestro_settings_save', [
				'method' => WP_Rest_Server::EDITABLE,
				'callback' => [ SettingsController::class, 'update' ],
			]);
		}

		// Register the licenses page if we have registered premium plugins:
		if( sizeof( conductor()->plugins()->premium() ) > 0 ){

			// Show licenses page
			conductor()->routes()->register( 'shop_maestro_licenses', [
				'method' => WP_Rest_Server::READABLE,
				'callback' => [ LicenseController::class, 'display' ],
				'menu_label' => __( 'Updates', 'shop-maestro'),
				'priority' => 900
			]);

			// Save licenses function
			conductor()->routes()->register( 'shop_maestro_licenses_save', [
				'method' => WP_Rest_Server::EDITABLE,
				'callback' => [ LicenseController::class, 'update' ],
			]);

		}
	}



	/**
	 * Register all menu routes for this application
	 */
	public function add_menu_pages(): void {

		global $submenu;

		// fetch all admin routes.
		$routes = conductor()->routes()->sort_by( 'priority' );
		$main_route  = $routes['shop_maestro_dashboard'];

		// always set the leading page.
		$screen_id = \add_menu_page(
			__( 'Shop Maestro', 'shop-maestro'),
			__( 'Shop Maestro', 'shop-maestro'),
			'manage_woocommerce',
			'shop_maestro_dashboard',
			function() use ( $main_route ){
				Router::handle_callback( $main_route );
			},
			$main_route['icon']
		);

		// then, loop through all regular get route options.
		foreach ( $routes as $key => $route ) {

			// Skip our default route
			if( $route == $main_route || empty( $route )){
				continue;
			}

			// only select the ones that are supposed to show up in the menu.
			if ( $route['method'] == 'GET' ) {

				$screen_id = \add_submenu_page(
					'shop_maestro_dashboard',
					$route['menu_label'] ?? '',
					$route['menu_label'] ?? '',
					'manage_woocommerce',
					$key,
					function() use ( $route ){
						Router::handle_callback( $route );
					}
				);

				\wc_admin_connect_page(
					[
						'id'        => $screen_id,
						'screen_id' => $screen_id,
						'title'     => $route['menu_label'] ?? '',
					]
				);

				// Hide the subpage if no menu label has been set:
				if( !isset( $route['menu_label'] ) ){
					foreach ( $submenu['shop_maestro_dashboard'] as &$item ) {
						if ( $item[2] === "{$key}" ) {
							$item[4] = ( $item[4] ?? '' ) . ' hidden';
						}
					}
				}
			}
		}
	}

	/**
	 * Set the current menu item when viewing a Shop Health Tab
	 *
	 * @param ?string $file The menu item slug.
	 * @return ?string The menu item slug.
	 */
	public function set_current_menu_item( ?string $file ): ?string {
		$screen = \get_current_screen();
		/*$this->get_routes( 'admin' );
		foreach ( $this->routes['get'] as $key => $route ) {
			if ( isset( $route['location'] ) && $route['location'] === 'shop-health' && \strpos( $screen->id, $key ) ) {
				$file = 'woop_dashboard';
			}
		}

		return $file;*/
	}
}
