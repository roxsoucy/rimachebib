<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'jacqueline_template_header_4_theme_setup' ) ) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_template_header_4_theme_setup', 1 );
	function jacqueline_template_header_4_theme_setup() {
		jacqueline_add_template(array(
			'layout' => 'header_4',
			'mode'   => 'header',
			'title'  => esc_html__('Header 4', 'jacqueline'),
			'icon'   => jacqueline_get_file_url('templates/headers/images/4.jpg')
			));
	}
}

// Template output
if ( !function_exists( 'jacqueline_template_header_4_output' ) ) {
	function jacqueline_template_header_4_output($post_options, $post_data) {

		// WP custom header
		$header_css = '';
		if ($post_options['position'] != 'over') {
			$header_image = get_header_image();
			$header_css = $header_image!='' 
				? ' style="background-image: url('.esc_url($header_image).')"' 
				: '';
		}
		?>
		

		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_4 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_4 top_panel_position_<?php echo esc_attr(jacqueline_get_custom_option('top_panel_position')); ?>">
			
			<?php if (jacqueline_get_custom_option('show_top_panel_top')=='yes') { ?>
				<div class="top_panel_top">
					<div class="content_wrap clearfix">
						<?php
						jacqueline_template_set_args('top-panel-top', array(
							'top_panel_top_components' => array('email', 'phone', 'socials', 'cart')
						));
						get_template_part(jacqueline_get_file_slug('templates/headers/_parts/top-panel-top.php'));
						?>
					</div>
				</div>
			<?php } ?>

			<div class="top_panel_middle" <?php echo trim($header_css); ?>>
				<div class="content_wrap">
					<div class="contact_logo">
						<?php jacqueline_show_logo(true, true); ?>
					</div>
					<div class="menu_main_wrap">
						<nav class="menu_main_nav_area">
							<?php
							$menu_main = jacqueline_get_nav_menu('menu_main');
							if (empty($menu_main)) $menu_main = jacqueline_get_nav_menu();
							echo trim($menu_main);
							?>
						</nav>
						<?php if (jacqueline_get_custom_option('show_search')=='yes') 
							echo trim(jacqueline_sc_search(array('state'=>"closed"))); ?>
					</div>
				</div>
			</div>

			</div>
		</header>

		<?php
		jacqueline_storage_set('header_mobile', array(
				 'open_hours' => true,
				 'login' => true,
				 'socials' => true,
				 'bookmarks' => false,
				 'contact_address' => true,
				 'contact_phone' => true,
				 'contact_email' => true,
				 'woo_cart' => true,
				 'search' => true
			)
		);
	}
}
?>