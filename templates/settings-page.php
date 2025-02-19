<div class="wrap">
    <h1 class="screen-reader-text"><?php esc_html_e('Shop Maestro Settings', 'shop-maestro'); ?></h1>
    <div class="wrap wsh-dashboard__main wsh-settings">
        <section class="wsh-wrap">
            <!-- add tabs: -->
			<nav class="wsh-tabs">				
			<?php foreach( $settings->tabs() as $key => $tab ):?>
				<a href="#tab_<?php echo $tab;?>"><?php echo $tab['label'];?></a>		
			<?php endforeach;?>
			</nav>

			<!-- add settings forms for each registered plugin: -->
			<?php foreach( $settings->tabs() as $key => $tab ):?>
            <section class="wsh-tabs-pane" id="tab_<?php echo $key;?>">
                <section class="wsh-tabs-pane__content">

                    <form class="panel" action="<?php echo conductor_get_route_url('shop_maestro_settings_save'); ?>" method="POST">
						<input type="hidden" name="shop_maestro_settings_key" value="<?php echo $key;?>">
                        <?php echo conductor_nonce_field('shop_maestro_settings_save'); ?>
						<?php echo $settings->display_tab( $key );?>
                        <button class="button button-primary"><?php esc_html_e('Save settings', 'wooping-shop-health'); ?></button>
                    </form>
                </section>
            </section>
			<?php endforeach;?>
        </section>
    </div>
</div>
