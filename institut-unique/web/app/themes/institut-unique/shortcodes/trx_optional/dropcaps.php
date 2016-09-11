<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jacqueline_sc_dropcaps_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_dropcaps_theme_setup' );
	function jacqueline_sc_dropcaps_theme_setup() {
		add_action('jacqueline_action_shortcodes_list', 		'jacqueline_sc_dropcaps_reg_shortcodes');
		if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
			add_action('jacqueline_action_shortcodes_list_vc','jacqueline_sc_dropcaps_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_dropcaps id="unique_id" style="1-6"]paragraph text[/trx_dropcaps]

if (!function_exists('jacqueline_sc_dropcaps')) {	
	function jacqueline_sc_dropcaps($atts, $content=null){
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= ($css ? '; ' : '') . jacqueline_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= jacqueline_get_css_dimensions_from_values($width, $height);
		$style = min(4, max(1, $style));
		$content = do_shortcode(str_replace(array('[vc_column_text]', '[/vc_column_text]'), array('', ''), $content));
		$output = jacqueline_substr($content, 0, 1) == '<' 
			? $content 
			: '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_dropcaps sc_dropcaps_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css ? ' style="'.esc_attr($css).'"' : '')
				. (!jacqueline_param_is_off($animation) ? ' data-animation="'.esc_attr(jacqueline_get_animation_classes($animation)).'"' : '')
				. '>' 
					. '<span class="sc_dropcaps_item">' . trim(jacqueline_substr($content, 0, 1)) . '</span>' . trim(jacqueline_substr($content, 1))
			. '</div>';
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_dropcaps', $atts, $content);
	}
	jacqueline_require_shortcode('trx_dropcaps', 'jacqueline_sc_dropcaps');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_dropcaps_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_sc_dropcaps_reg_shortcodes');
	function jacqueline_sc_dropcaps_reg_shortcodes() {
	
		jacqueline_sc_map("trx_dropcaps", array(
			"title" => esc_html__("Dropcaps", 'jacqueline'),
			"desc" => wp_kses_data( __("Make first letter as dropcaps", 'jacqueline') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'jacqueline'),
					"desc" => wp_kses_data( __("Dropcaps style", 'jacqueline') ),
					"value" => "1",
					"type" => "checklist",
					"options" => jacqueline_get_list_styles(1, 4)
				),
				"_content_" => array(
					"title" => esc_html__("Paragraph content", 'jacqueline'),
					"desc" => wp_kses_data( __("Paragraph with dropcaps content", 'jacqueline') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
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
if ( !function_exists( 'jacqueline_sc_dropcaps_reg_shortcodes_vc' ) ) {
	//add_action('jacqueline_action_shortcodes_list_vc', 'jacqueline_sc_dropcaps_reg_shortcodes_vc');
	function jacqueline_sc_dropcaps_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_dropcaps",
			"name" => esc_html__("Dropcaps", 'jacqueline'),
			"description" => wp_kses_data( __("Make first letter of the text as dropcaps", 'jacqueline') ),
			"category" => esc_html__('Content', 'jacqueline'),
			'icon' => 'icon_trx_dropcaps',
			"class" => "trx_sc_container trx_sc_dropcaps",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'jacqueline'),
					"description" => wp_kses_data( __("Dropcaps style", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(jacqueline_get_list_styles(1, 4)),
					"type" => "dropdown"
				),
/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Paragraph text", 'jacqueline'),
					"description" => wp_kses_data( __("Paragraph with dropcaps content", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
*/
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
		
		class WPBakeryShortCode_Trx_Dropcaps extends JACQUELINE_VC_ShortCodeContainer {}
	}
}
?>