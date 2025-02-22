<div class="wrap">
    <h1 class="screen-reader-text"><?php esc_html_e('Shop Maestro Settings', 'shop-maestro'); ?></h1>
    <div class="wrap wsh-dashboard__main wsh-settings">
        <section class="wsh-wrap">
            <!-- add tabs: -->
			<div class="wsh-tabs__wrapper">
				<nav class="wsh-tabs__tabs" role="tablist">				
				<?php 

				// Render our license tabs
				foreach( $plugins as $key => $plugin ){
					$url = conductor_get_route_url( 'shop_maestro_licenses' );
					$url = add_query_arg( 'plugin', $key, $url );
					$selected = ( $key == $active_plugin ? 'true' : 'false' );

					echo '<a class="wsh-tabs__tab" href="'.\esc_url( $url ).'" aria-selected="'.$selected.'">';
						echo ( isset( $plugin['label'] ) ? $plugin['label'] : $key );
					echo '</a>';
				}
				?>
				</nav>
			</div>
			<!-- add settings forms for the active plugin: -->
            <section class="wsh-tabs-pane">
                <section class="wsh-tabs-pane__content">
                    <form class="panel" action="<?php echo conductor_get_route_url('shop_maestro_licenses_save'); ?>" method="POST">
						<input type="hidden" name="shop_maestro_license_for" value="<?php echo $active_plugin;?>">
                        <?php conductor_nonce_field('shop_maestro_licenses_save'); ?>
						<div class="field-wrapper">
							<label for="license_key"><?php esc_html_e( 'License key', 'shop_maestro_conductor' );?></label><br/>
							<input id="license_key" name="license_key" value="<?php echo $license['key'];?>"/>
							<?php echo $license['icon'];?>
						</div>
						<?php 
						if( !is_null( $license['expires'] ) ){
							echo '<datetime class="expires" datetime="'.$license['expires'].'">';
								echo '<span>'.__( 'Expires on', 'shop_maestro_conductor' );
								echo '&nbsp';
								echo date_i18n( 'l j F Y', strtotime( $license['expires'] ) );
							echo '</datetime>';
						}?><br/><hr/><br/>
						<button class="button button-primary"><?php esc_html_e('Save settings', 'wooping-shop-health'); ?></button>
                    </form>
                </section>
            </section>
        </section>
    </div>
</div>
