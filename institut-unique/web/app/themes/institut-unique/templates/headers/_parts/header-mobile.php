<?php
$header_options = jacqueline_storage_get('header_mobile');
$contact_address_1 = trim(jacqueline_get_custom_option('contact_address_1'));
$contact_address_2 = trim(jacqueline_get_custom_option('contact_address_2'));
$contact_phone = trim(jacqueline_get_custom_option('contact_phone'));
$contact_email = trim(jacqueline_get_custom_option('contact_email'));
?>
	<div class="header_mobile">
		<div class="content_wrap">
			<div class="menu_button icon-menu"></div>
			<?php 
			jacqueline_show_logo(); 
			if ($header_options['woo_cart']){
				if (function_exists('jacqueline_exists_woocommerce') && jacqueline_exists_woocommerce() && (jacqueline_is_woocommerce_page() && jacqueline_get_custom_option('show_cart')=='shop' || jacqueline_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) { 
					?>
					<div class="menu_main_cart top_panel_icon">
						<?php get_template_part(jacqueline_get_file_slug('templates/headers/_parts/contact-info-cart.php')); ?>
					</div>
					<?php
				}
			}
			?>
		</div>
		<div class="side_wrap">
			<div class="close"><?php esc_html_e('Close', 'jacqueline'); ?></div>
			<div class="panel_top">
				<nav class="menu_main_nav_area">
					<?php
						$menu_main = jacqueline_get_nav_menu('menu_main');
						if (empty($menu_main)) $menu_main = jacqueline_get_nav_menu();
							echo trim($menu_main);
					?>
				</nav>
				<?php 
				if ($header_options['search'] && jacqueline_get_custom_option('show_search')=='yes')
					echo trim(jacqueline_sc_search(array()));
				
				if ($header_options['login']) {
					if ( is_user_logged_in() ) { 
						?>
						<div class="login"><a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="popup_link"><?php esc_html_e('Logout', 'jacqueline'); ?></a></div>
						<?php
					} else {
						// Load core messages
						jacqueline_enqueue_messages();
						// Load Popup engine
						jacqueline_enqueue_popup();
						?>
						<div class="login"><a href="#popup_login" class="popup_link popup_login_link icon-user"><?php esc_html_e('Login', 'jacqueline'); ?></a></div>
						<?php
						// Anyone can register ?
						if ( (int) get_option('users_can_register') > 0) {
							?>
							<div class="login"><a href="#popup_registration" class="popup_link popup_register_link icon-pencil"><?php esc_html_e('Register', 'jacqueline'); ?></a></div>
							<?php 
						}
					}
				}
				?>
			</div>
			
			<?php if ($header_options['contact_address'] || $header_options['contact_phone'] || $header_options['contact_email'] || $header_options['open_hours']) { ?>
			<div class="panel_middle">
				<?php
				if ($header_options['contact_address'] && (!empty($contact_address_1) || !empty($contact_address_2))) {
					?><div class="contact_field contact_address">
								<span class="contact_icon icon-home"></span>
								<span class="contact_label contact_address_1"><?php echo force_balance_tags($contact_address_1); ?></span>
								<span class="contact_address_2"><?php echo force_balance_tags($contact_address_2); ?></span>
							</div><?php
				}
						
				if ($header_options['contact_phone'] && !empty($contact_phone)) {
					?><div class="contact_field contact_phone">
						<span class="contact_icon icon-icon_phone"></span>
						<span class="contact_label contact_phone"><?php echo force_balance_tags($contact_phone); ?></span>
					</div><?php
				}
				if ($header_options['contact_email'] && !empty($contact_email)) {
					?><div class="contact_field contact_mail">
						<span class="contact_icon icon-mail"></span>
						<span class="contact_email"><?php echo force_balance_tags($contact_email); ?></span>
					</div><?php
				}
				
				jacqueline_template_set_args('top-panel-top', array(
					'top_panel_top_components' => array(
						($header_options['open_hours'] ? 'open_hours' : '')
					)
				));
				get_template_part(jacqueline_get_file_slug('templates/headers/_parts/top-panel-top.php'));
				?>
			</div>
			<?php } ?>

			<div class="panel_bottom">
				<?php if ($header_options['socials'] && jacqueline_get_custom_option('show_socials')=='yes') { ?>
					<div class="contact_socials">
						<?php echo trim(jacqueline_sc_socials(array('size'=>'small'))); ?>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="mask"></div>
	</div>

<?php if ( is_user_logged_in() ) { ?>
	<script>jQuery('html').addClass('bar');</script>
<?php } ?>