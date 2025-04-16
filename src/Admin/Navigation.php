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
			'icon' => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTUzIiBoZWlnaHQ9IjU1MyIgdmlld0JveD0iMCAwIDU1MyA1NTMiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxnIGNsaXAtcGF0aD0idXJsKCNjbGlwMF8zXzMyKSI+CjxwYXRoIGQ9Ik0yMjguNDI3IDU1Mi42NjZDMjExLjEyOCA1NTIuNjY2IDE5NS41MzYgNTQ3LjQ4OSAxODMuMSA1MzcuNTQ5QzE3MC43NjEgNTI3LjY5MiAxNjIuNTk3IDUxMy43ODEgMTYwLjEyMSA0OTguMzgyQzE1Ny42NTMgNDgzLjAyIDE2MS4wNTEgNDY3LjQ2OCAxNjkuNjkzIDQ1NC42MDNDMTc5LjI0IDQ0MC4zOCAxOTQuMTUzIDQzMC41NjkgMjEyLjgxMSA0MjYuMjMyQzIyNy45OTkgNDIyLjcwNSAyNDMuMzQgNDMxLjQ0NyAyNDcuMDg2IDQ0NS43NzhDMjUwLjgzMiA0NjAuMTA4IDI0MS41NiA0NzQuNTg0IDIyNi4zNzIgNDc4LjExOUMyMTYuNjM4IDQ4MC4zODYgMjE1LjQyNSA0ODYuMDI5IDIxNi4xMjkgNDkwLjM2NUMyMTYuODI0IDQ5NC42OTQgMjE5Ljc5NCA0OTkuNzMzIDIzMC4wNDYgNDk5LjE2OEMyMzUuMDIyIDQ5OC44OTMgMjQxLjY1NyA0OTcuMzY2IDI0OC45MDYgNDg3Ljk3NUMyNTUuNTMzIDQ3OS4zOTQgMjYwLjk4NyA0NjYuNTgzIDI2NS4xMjkgNDQ5LjlDMjcwLjAxNyA0MzAuMTk1IDI3Mi4xMiA0MDguOTAxIDI3My4wNTEgMzkxLjA4MUMyNDQuOTM0IDM5MC43OTEgMjE4LjI3MyAzODIuMTEgMTk1LjMxIDM2NS42NzNDMTcyLjIxNyAzNDkuMTQzIDE1NC43MjQgMzI1LjM2MSAxNDcuMzA0IDMwMC40MjVDMTQxLjY0IDI4MS4zNzYgMTQxLjk0IDI2Mi41MSAxNDguMTc4IDI0NS44NjZDMTU1LjI5OCAyMjYuODcxIDE2OS43MDEgMjExLjc0NiAxODkuODQgMjAyLjEzNEMyMDguODQ2IDE5My4wNTYgMjQxLjY2NSAxODguNjgxIDI3MS42MjcgMjA2LjIxOUMzMDMuNzI1IDIyNS4wMDggMzIyLjgxMiAyNjIuNjQ4IDMyOC42MDYgMzE4LjM3NUMzNDIuNzI1IDMwNS45MzggMzUwLjU2NSAyODkuMTAzIDM1Mi4zNjIgMjcyLjgwMkMzNTQuMjU1IDI1NS41OTMgMzQ5Ljc4OSAyMzkuMDEgMzM5LjQ0IDIyNC44NTVDMzI3LjMzNSAyMDguMzAzIDMwNy4zMjUgMTk1Ljc4MiAyODEuNTcxIDE4OC42NDNDMjQ4Ljg5IDE3OS41ODEgMjIzLjI2NSAxNjMuNjA5IDIwNy40NzEgMTQyLjQ1MkMxOTIuNjggMTIyLjY0OCAxODYuODYyIDk4Ljc5NjMgMTkxLjA3OCA3NS4yOTYyQzE5NC44NjUgNTQuMjE2MyAyMDYuMzk1IDM0Ljk5OTQgMjIzLjU2NCAyMS4xNzI3QzI0MS44NTEgNi40NTI2OSAyNjUuMzg4IC0xLjAzNzEgMjg5LjgzMiAwLjExNTc2QzMyMC4yMzEgMS41NDM0OCAzNDYuMzAxIDE1LjAxOSAzNjMuMjQ0IDM4LjA1MzRDMzgwLjEzOSA2MS4wMzQzIDM4NS4wNjcgOTAuMDM5MSAzNzYuNDMzIDExNS42NTRDMzcxLjY5MiAxMjkuNzE3IDM1NS43NjggMTM3LjQ5IDM0MC44NjQgMTMzLjAxNkMzMjUuOTYgMTI4LjU0MiAzMTcuNzIzIDExMy41MTYgMzIyLjQ2NCA5OS40NTI5QzMyNS43MDkgODkuODI1MyAzMjMuNDUxIDc3LjY4NTkgMzE2LjcxMSA2OC41MTY0QzMxMS45NzggNjIuMDcyNiAzMDIuOTggNTQuMjM5MiAyODcuMDI0IDUzLjQ5MUMyNjQuNDk4IDUyLjQzNzQgMjQ5LjgwNSA2OC4xNTc2IDI0Ni45MTYgODQuMjM2NkMyNDUuNzEgOTAuOTU1MyAyNDUuOTIxIDEwMS4xNzggMjUzLjc2OSAxMTEuNjg0QzI2Mi4xMTkgMTIyLjg2OSAyNzcuMjU4IDEzMS43NTYgMjk3LjUzNSAxMzcuMzc1QzMzNS44ODggMTQ4LjAxMSAzNjYuNDk3IDE2Ny43NyAzODYuMDU0IDE5NC41MTRDNDAzLjkxMSAyMTguOTM4IDQxMS45NTQgMjQ4LjcwNyA0MDguNjkzIDI3OC4zM0M0MDUuNTc4IDMwNi42MzIgMzkyLjM4MSAzMzMuMzAxIDM3MS41MyAzNTMuNDE5QzM1OS41ODcgMzY0Ljk0NyAzNDUuNjM4IDM3My45NzkgMzMwLjE1OSAzODAuMzE2QzMyOS41MjggNDAyLjI3NCAzMjcuNTA1IDQzMi45MzYgMzIwLjI3MiA0NjIuMDg2QzMwMi40NzEgNTMzLjgwNyAyNjMuMjA0IDU1MC44NDggMjMzLjM5NSA1NTIuNTEzQzIzMS43MjkgNTUyLjYwNCAyMzAuMDcgNTUyLjY1IDIyOC40MjcgNTUyLjY1VjU1Mi42NjZaTTIyNi43NTIgMjQ3LjY5MUMyMjEuNDYxIDI0Ny42OTEgMjE3LjE4IDI0OC45NzQgMjE1LjQgMjQ5LjgyMUMyMDguMzIxIDI1My4yMDQgMjAzLjc4OSAyNTcuNzM5IDIwMS41NjQgMjYzLjY5NEMxOTkuMjQyIDI2OS44OTMgMTk5LjMzMSAyNzcuNjA1IDIwMS44MjMgMjg1Ljk4OEMyMDUuOTc0IDI5OS45NDQgMjE2LjA3MiAzMTMuNDY2IDIyOS41MzYgMzIzLjEwOEMyMzguODY1IDMyOS43ODEgMjUzLjY4IDMzNy4zMTcgMjczLjMyNiAzMzcuNjM3QzI3MC44NDIgMjkyLjk1MSAyNTkuNjkyIDI2Mi4xMzYgMjQxLjc2MiAyNTEuNjM4QzIzNi42NCAyNDguNjM4IDIzMS4zMjQgMjQ3LjY5MSAyMjYuNzYxIDI0Ny42OTFIMjI2Ljc1MloiIGZpbGw9IndoaXRlIi8+CjwvZz4KPGRlZnM+CjwvZGVmcz4KPC9zdmc+Cg=='
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
