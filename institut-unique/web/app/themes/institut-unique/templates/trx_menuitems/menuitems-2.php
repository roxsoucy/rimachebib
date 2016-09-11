<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'jacqueline_template_menuitems_2_theme_setup' ) ) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_template_menuitems_2_theme_setup', 1 );
	function jacqueline_template_menuitems_2_theme_setup() {
		jacqueline_add_template(array(
			'layout' => 'menuitems-2',
			'template' => 'menuitems-2',
			'mode'   => 'menuitems',
			'title'  => esc_html__('MenuItems /Style 2/', 'jacqueline'),
			'thumb_title'  => esc_html__('Fullwidth image (crop)', 'jacqueline'),
			'w'		 => 1170,	//1920,
			'h'		 => 659		//698
		));
	}
}

// Template output
if ( !function_exists( 'jacqueline_template_menuitems_2_output' ) ) {
	function jacqueline_template_menuitems_2_output($post_options, $post_data) {
		$show_title = !empty($post_data['post_title']);
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($parts[1]) ? $post_options['columns_count'] : (int) $parts[1]));
		?>
			<div<?php echo !empty($post_options['tag_id']) ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''; ?>
				class="sc_menuitems_item sc_menuitems_item_<?php echo esc_attr($post_options['number']) . ($post_options['number'] % 2 == 1 ? ' odd' : ' even') . ($post_options['number'] == 1 ? ' first' : '') . (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"
				<?php echo (!empty($post_options['tag_css']) ? ' style="'.esc_attr($post_options['tag_css']).'"' : '') 
				. (!jacqueline_param_is_off($post_options['tag_animation']) ? ' data-animation="'.esc_attr(jacqueline_get_animation_classes($post_options['tag_animation'])).'"' : ''); ?>>

				<?php
				if ($post_options['menuitem_image']) {
					?><div class="sc_menuitem_image"><?php
					echo trim($post_options['menuitem_image']);
					?></div><?php
				}
				
				if ( $post_data['post_title'] || ( $post_options['menuitem_price'] != 'inherit' && strlen($post_options['menuitem_price']) != 0 ) ) {
					?>
					<div class="sc_menuitem_box_title">
						<?php
						if ($post_data['post_title']) {
							?><div class="sc_menuitem_title"><?php echo trim($post_data['post_title']);?></div><?php
						}
						if ( $post_options['menuitem_price'] != 'inherit' && strlen($post_options['menuitem_price']) != 0 ) {
							?><div class="sc_menuitem_price"><?php echo trim($post_options['menuitem_price']); ?></div><?php
						}
						?>
						<div class="cL"></div>
					</div>
					<?php
				}
				
				if ($post_data['post_content']) {
					?>
					<div class="sc_menuitem_content navigation">
						<div class="sc_menuitem_content_title"><span class="icon-chronometer9"></span>Description</div>
						<?php echo trim($post_data['post_content']); ?>
					</div>
					<?php
				}
				if ($post_options['menuitem_master'] != 'inherit') {
					?>
					<div class="sc_menuitem_content">
						<div class="sc_menuitem_content_title"><span class="icon-gesture3"></span>Master-Level Massage</div>
						<?php echo trim($post_options['menuitem_master']); ?>
					</div>
					<?php
				}
				if ($post_options['menuitem_services'] != 'inherit') {
					?>
					<div class="sc_menuitem_content services">
						<div class="sc_menuitem_content_title"><span class="icon-candle23"></span>Services Included</div>
						<div class="sc_menuitem_services">
						<?php 
						$services = explode(',', $post_options['menuitem_services']);
						foreach($services as $i){
							
							$title = get_the_title($i);
							$link = get_permalink($i);
							$icon = jacqueline_get_custom_option('icon', null, $i);
							
							echo	'<div class="sc_menuitem_service">'
										.'<span class="sc_menuitem_service_icon '.esc_attr($icon).'"></span>'
										.'<a href="'.esc_url($link).'">'.esc_html($title).'</a>'
									.'</div>';
						}
						?>
						</div>
					</div>
					<?php
				}
				?>
				
				<div class="sc_menuitem_more">
					<?php if ( $post_options['menuitem_product'] != 'inherit' ) { ?>
						<a class="sc_button sc_button_square sc_button_style_filled sc_button_size_base" href="<?php echo get_permalink($post_options['menuitem_product']); ?>">
							<div>
								<span class="first"><?php esc_html_e('ORDER', 'jacqueline'); ?></span>
								<span class="second"><?php esc_html_e('ORDER', 'jacqueline'); ?></span>
							</div>
						</a>
					<?php } ?>
					<a class="sc_button sc_button_square sc_button_style_filled sc_button_size_base" href="<?php echo esc_url($post_data['post_comments_link']); ?>">
						<div>
							<span class="first"><?php esc_html_e('POST COMMENT', 'jacqueline'); ?></span>
							<span class="second"><?php esc_html_e('POST COMMENT', 'jacqueline'); ?></span>
						</div>
					</a>
					<div class="cL"></div>
				</div>

				<div class="clearfix"></div>
			</div>
		<?php
	}
}
?>