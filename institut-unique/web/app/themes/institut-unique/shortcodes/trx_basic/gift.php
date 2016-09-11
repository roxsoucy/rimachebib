<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jacqueline_sc_gift_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_gift_theme_setup' );
	function jacqueline_sc_gift_theme_setup() {
		add_action('jacqueline_action_shortcodes_list', 		'jacqueline_sc_gift_reg_shortcodes');
		if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
			add_action('jacqueline_action_shortcodes_list_vc','jacqueline_sc_gift_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */


if (!function_exists('jacqueline_sc_gift')) {	
	function jacqueline_sc_gift($atts, $content=null){	
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
			"image" => "",
			"title" => "",
			"currency" => "$",
			"price" => "",
			"link" => "",
			"link_caption" => esc_html__('Submit', 'jacqueline'),
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		$css .= ($css ? '; ' : '') . jacqueline_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= jacqueline_get_css_dimensions_from_values($width, $height);
		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		$output = '<div '.(!empty($id) ? 'id="'.esc_attr($id).'"' : '').' class="sc_gift'. (!empty($class) ? ' '.esc_attr($class) : '') . ' sc_gift_style_'.esc_attr($style).'"'
				  . ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				  . (!jacqueline_param_is_off($animation) ? ' data-animation="'.esc_attr(jacqueline_get_animation_classes($animation)).'"' : '') .'>'
						. (!empty($title) ? '<div class="sc_gift_title sc_item_title">' . trim(jacqueline_strmacros($title)) . '</div>' : '')
						. (!empty($image) ? '<div class="sc_gift_featured"><img src=' . esc_url($image) . ' alt=""></div>' : '')
						. (!empty($price) ? '<div class="sc_gift_price"><div class="sc_gift_currency">'.esc_html($currency).'</div>' . trim(jacqueline_strmacros($price)) . '</div>' : '')
						. (!empty($link) ? jacqueline_do_shortcode('[trx_button link="'.esc_url($link).'" size="medium"]'.esc_html($link_caption).'[/trx_button]') : '')
				  .'</div>';
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_gift', $atts, $content);
	}
	jacqueline_require_shortcode('trx_gift', 'jacqueline_sc_gift');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_gift_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_sc_gift_reg_shortcodes');
	function jacqueline_sc_gift_reg_shortcodes() {
	
		jacqueline_sc_map("trx_gift", array(
			"title" => esc_html__("Gift card", 'jacqueline'),
			"desc" => wp_kses_data( __("Insert gift card into your post (page)", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'jacqueline'),
					"desc" => wp_kses_data( __("Gift card style", 'jacqueline') ),
					"value" => "1",
					"type" => "checklist",
					"options" => jacqueline_get_list_styles(1, 2)
				),
				"image" => array(
					"title" => esc_html__("URL for image file", 'jacqueline'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site", 'jacqueline') ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'sizes' => true		// If you want allow user select thumb size for image. Otherwise, thumb size is ignored - image fullsize used
					)
				),
				"title" => array(
					"title" => esc_html__("Title", 'jacqueline'),
					"desc" => wp_kses_data( __("Gift card title", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"currency" => array(
					"title" => esc_html__("Currency", 'jacqueline'),
					"value" => "$",
					"type" => "text"
				),
				"price" => array(
					"title" => esc_html__("Price", 'jacqueline'),
					"desc" => wp_kses_data( __("Gift card price", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"link" => array(
					"title" => esc_html__("Button URL", 'jacqueline'),
					"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", 'jacqueline'),
					"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"width" => jacqueline_shortcodes_width(),
				"height" => jacqueline_shortcodes_height(),
				"top" => jacqueline_get_sc_param('top'),
				"bottom" => jacqueline_get_sc_param('bottom'),
				"left" => jacqueline_get_sc_param('left'),
				"right" => jacqueline_get_sc_param('right'),
				"id" => jacqueline_get_sc_param('id'),
				"class" => jacqueline_get_sc_param('class'),
				"animation" => jacqueline_get_sc_param('animation'),
				"css" => jacqueline_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_gift_reg_shortcodes_vc' ) ) {
	//add_action('jacqueline_action_shortcodes_list_vc', 'jacqueline_sc_gift_reg_shortcodes_vc');
	function jacqueline_sc_gift_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_gift",
			"name" => esc_html__("Gift card", 'jacqueline'),
			"description" => wp_kses_data( __("Insert gift card", 'jacqueline') ),
			"category" => esc_html__('Content', 'jacqueline'),
			'icon' => 'icon_trx_gifts',
			"class" => "trx_sc_single trx_sc_gift",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'jacqueline'),
					"description" => wp_kses_data( __("Gift card style", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(jacqueline_get_list_styles(1, 2)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Select image", 'jacqueline'),
					"description" => wp_kses_data( __("Select image from library", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'jacqueline'),
					"description" => wp_kses_data( __("Gift card's title", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "currency",
					"heading" => esc_html__("Currency", 'jacqueline'),
					"admin_label" => true,
					"class" => "",
					"value" => "$",
					"type" => "textfield"
				),
				array(
					"param_name" => "price",
					"heading" => esc_html__("Price", 'jacqueline'),
					"description" => wp_kses_data( __("Gift card's price", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Button URL", 'jacqueline'),
					"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_caption",
					"heading" => esc_html__("Button caption", 'jacqueline'),
					"description" => wp_kses_data( __("Caption for the button at the bottom of the block", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				jacqueline_get_vc_param('id'),
				jacqueline_get_vc_param('class'),
				jacqueline_get_vc_param('animation'),
				jacqueline_get_vc_param('css'),
				jacqueline_vc_width(),
				jacqueline_vc_height(),
				jacqueline_get_vc_param('margin_top'),
				jacqueline_get_vc_param('margin_bottom'),
				jacqueline_get_vc_param('margin_left'),
				jacqueline_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_trx_gift extends JACQUELINE_VC_ShortCodeSingle {}
	}
}
?>