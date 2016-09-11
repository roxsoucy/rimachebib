<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jacqueline_sc_highlight_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_highlight_theme_setup' );
	function jacqueline_sc_highlight_theme_setup() {
		add_action('jacqueline_action_shortcodes_list', 		'jacqueline_sc_highlight_reg_shortcodes');
		if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
			add_action('jacqueline_action_shortcodes_list_vc','jacqueline_sc_highlight_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('jacqueline_sc_highlight')) {	
	function jacqueline_sc_highlight($atts, $content=null){	
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			// Individual params
			"color" => "",
			"bg_color" => "",
			"font_size" => "",
			"type" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$css .= ($color != '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color != '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(jacqueline_prepare_css_value($font_size)) . '; line-height: 1em;' : '');
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_highlight'.($type>0 ? ' sc_highlight_style_'.esc_attr($type) : ''). (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</span>';
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_highlight', $atts, $content);
	}
	jacqueline_require_shortcode('trx_highlight', 'jacqueline_sc_highlight');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_highlight_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_sc_highlight_reg_shortcodes');
	function jacqueline_sc_highlight_reg_shortcodes() {
	
		jacqueline_sc_map("trx_highlight", array(
			"title" => esc_html__("Highlight text", 'jacqueline'),
			"desc" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'jacqueline') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Type", 'jacqueline'),
					"desc" => wp_kses_data( __("Highlight type", 'jacqueline') ),
					"value" => "1",
					"type" => "checklist",
					"options" => array(
						0 => esc_html__('Custom', 'jacqueline'),
						1 => esc_html__('Type 1', 'jacqueline'),
						2 => esc_html__('Type 2', 'jacqueline'),
						3 => esc_html__('Type 3', 'jacqueline')
					)
				),
				"color" => array(
					"title" => esc_html__("Color", 'jacqueline'),
					"desc" => wp_kses_data( __("Color for the highlighted text", 'jacqueline') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'jacqueline'),
					"desc" => wp_kses_data( __("Background color for the highlighted text", 'jacqueline') ),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'jacqueline'),
					"desc" => wp_kses_data( __("Font size of the highlighted text (default - in pixels, allows any CSS units of measure)", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Highlighting content", 'jacqueline'),
					"desc" => wp_kses_data( __("Content for highlight", 'jacqueline') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"id" => jacqueline_get_sc_param('id'),
				"class" => jacqueline_get_sc_param('class'),
				"css" => jacqueline_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_highlight_reg_shortcodes_vc' ) ) {
	//add_action('jacqueline_action_shortcodes_list_vc', 'jacqueline_sc_highlight_reg_shortcodes_vc');
	function jacqueline_sc_highlight_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_highlight",
			"name" => esc_html__("Highlight text", 'jacqueline'),
			"description" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'jacqueline') ),
			"category" => esc_html__('Content', 'jacqueline'),
			'icon' => 'icon_trx_highlight',
			"class" => "trx_sc_single trx_sc_highlight",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Type", 'jacqueline'),
					"description" => wp_kses_data( __("Highlight type", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Custom', 'jacqueline') => 0,
							esc_html__('Type 1', 'jacqueline') => 1,
							esc_html__('Type 2', 'jacqueline') => 2,
							esc_html__('Type 3', 'jacqueline') => 3
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'jacqueline'),
					"description" => wp_kses_data( __("Color for the highlighted text", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'jacqueline'),
					"description" => wp_kses_data( __("Background color for the highlighted text", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'jacqueline'),
					"description" => wp_kses_data( __("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Highlight text", 'jacqueline'),
					"description" => wp_kses_data( __("Content for highlight", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				jacqueline_get_vc_param('id'),
				jacqueline_get_vc_param('class'),
				jacqueline_get_vc_param('css')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Highlight extends JACQUELINE_VC_ShortCodeSingle {}
	}
}
?>