<div class="wrap">
    <h1 class="screen-reader-text"><?php esc_html_e('Shop Maestro Settings', 'shop-maestro'); ?></h1>
    <div class="wrap wsh-dashboard__main wsh-settings">
        <section class="wsh-wrap">
            <!-- add tabs: -->
			<div class="wsh-tabs__wrapper">
				<nav class="wsh-tabs__tabs" role="tablist">				
				<?php 
				// Render our settings tabs
				foreach( $settings->tabs() as $key => $tab ){
					$url = conductor_get_route_url( 'shop_maestro_settings' );
					$url = add_query_arg( 'tab', $key, $url );
					$selected = ( $key == $settings->active_tab() ? 'true' : 'false' );

					echo '<a class="wsh-tabs__tab" href="'.\esc_url( $url ).'" aria-selected="'.$selected.'">';
						echo $tab['label'];
					echo '</a>';
				}
				?>
				</nav>
			</div>
			<!-- add settings forms for each registered plugin: -->
			<?php $active_tab = $settings->active_tab();?>
            <section class="wsh-tabs-pane">
                <section class="wsh-tabs-pane__content">
                    <form class="panel" action="<?php echo conductor_get_route_url('shop_maestro_settings_save'); ?>" method="POST">
						<input type="hidden" name="shop_maestro_settings_key" value="<?php echo $active_tab;?>">
                        <?php echo conductor_nonce_field('shop_maestro_settings_save'); ?>
						<?php echo $settings->display_tab( $active_tab );?>
                        <button class="button button-primary"><?php esc_html_e('Save settings', 'wooping-shop-health'); ?></button>
                    </form>
                </section>
            </section>
        </section>
    </div>
</div>
