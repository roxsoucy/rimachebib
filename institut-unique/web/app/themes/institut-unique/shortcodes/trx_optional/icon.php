<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jacqueline_sc_icon_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_icon_theme_setup' );
	function jacqueline_sc_icon_theme_setup() {
		add_action('jacqueline_action_shortcodes_list', 		'jacqueline_sc_icon_reg_shortcodes');
		if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
			add_action('jacqueline_action_shortcodes_list_vc','jacqueline_sc_icon_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_icon id="unique_id" style='round|square' icon='' color="" bg_color="" size="" weight=""]
*/

if (!function_exists('jacqueline_sc_icon')) {	
	function jacqueline_sc_icon($atts, $content=null){	
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			// Individual params
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"bg_shape" => "",
			"font_size" => "",
			"font_weight" => "",
			"align" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= ($css ? '; ' : '') . jacqueline_get_css_position_from_values($top, $right, $bottom, $left);
		$css2 = ($font_weight != '' && !jacqueline_is_inherit_option($font_weight) ? 'font-weight:'. esc_attr($font_weight).';' : '')
			. ($font_size != '' ? 'font-size:' . esc_attr(jacqueline_prepare_css_value($font_size)) . ';' : '')
			. ($color != '' ? 'color:'.esc_attr($color).';' : '')
			. ($bg_color != '' ? 'background-color:'.esc_attr($bg_color).';border-color:'.esc_attr($bg_color).';' : '')
		;
		$output = $icon!='' 
			? ($link ? '<a href="'.esc_url($link).'"' : '<span') . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_icon '.esc_attr($icon)
					. ($bg_shape && !jacqueline_param_is_inherit($bg_shape) ? ' sc_icon_shape_'.esc_attr($bg_shape) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
				.'"'
				.($css || $css2 ? ' style="'.($class ? 'display:block;' : '') . ($css) . ($css2) . '"' : '')
				.'>'
				.($link ? '</a>' : '</span>')
			: '';
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_icon', $atts, $content);
	}
	jacqueline_require_shortcode('trx_icon', 'jacqueline_sc_icon');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_icon_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_sc_icon_reg_shortcodes');
	function jacqueline_sc_icon_reg_shortcodes() {
	
		jacqueline_sc_map("trx_icon", array(
			"title" => esc_html__("Icon", 'jacqueline'),
			"desc" => wp_kses_data( __("Insert icon", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__('Icon',  'jacqueline'),
					"desc" => wp_kses_data( __('Select font icon from the Fontello icons set',  'jacqueline') ),
					"value" => "",
					"type" => "icons",
					"options" => jacqueline_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Icon's color", 'jacqueline'),
					"desc" => wp_kses_data( __("Icon's color", 'jacqueline') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "color"
				),
				"bg_shape" => array(
					"title" => esc_html__("Background shape", 'jacqueline'),
					"desc" => wp_kses_data( __("Shape of the icon background", 'jacqueline') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "none",
					"type" => "radio",
					"options" => array(
						'none' => esc_html__('None', 'jacqueline'),
						'round' => esc_html__('Round', 'jacqueline'),
						'square' => esc_html__('Square', 'jacqueline')
					)
				),
				"bg_color" => array(
					"title" => esc_html__("Icon's background color", 'jacqueline'),
					"desc" => wp_kses_data( __("Icon's background color", 'jacqueline') ),
					"dependency" => array(
						'icon' => array('not_empty'),
						'background' => array('round','square')
					),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'jacqueline'),
					"desc" => wp_kses_data( __("Icon's font size", 'jacqueline') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "spinner",
					"min" => 8,
					"max" => 240
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'jacqueline'),
					"desc" => wp_kses_data( __("Icon font weight", 'jacqueline') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'jacqueline'),
						'300' => esc_html__('Light (300)', 'jacqueline'),
						'400' => esc_html__('Normal (400)', 'jacqueline'),
						'700' => esc_html__('Bold (700)', 'jacqueline')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'jacqueline'),
					"desc" => wp_kses_data( __("Icon text alignment", 'jacqueline') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => jacqueline_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'jacqueline'),
					"desc" => wp_kses_data( __("Link URL from this icon (if not empty)", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"top" => jacqueline_get_sc_param('top'),
				"bottom" => jacqueline_get_sc_param('bottom'),
				"left" => jacqueline_get_sc_param('left'),
				"right" => jacqueline_get_sc_param('right'),
				"id" => jacqueline_get_sc_param('id'),
				"class" => jacqueline_get_sc_param('class'),
				"css" => jacqueline_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_icon_reg_shortcodes_vc' ) ) {
	//add_action('jacqueline_action_shortcodes_list_vc', 'jacqueline_sc_icon_reg_shortcodes_vc');
	function jacqueline_sc_icon_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_icon",
			"name" => esc_html__("Icon", 'jacqueline'),
			"description" => wp_kses_data( __("Insert the icon", 'jacqueline') ),
			"category" => esc_html__('Content', 'jacqueline'),
			'icon' => 'icon_trx_icon',
			"class" => "trx_sc_single trx_sc_icon",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'jacqueline'),
					"description" => wp_kses_data( __("Select icon class from Fontello icons set", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => jacqueline_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'jacqueline'),
					"description" => wp_kses_data( __("Icon's color", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'jacqueline'),
					"description" => wp_kses_data( __("Background color for the icon", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_shape",
					"heading" => esc_html__("Background shape", 'jacqueline'),
					"description" => wp_kses_data( __("Shape of the icon background", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('None', 'jacqueline') => 'none',
						esc_html__('Round', 'jacqueline') => 'round',
						esc_html__('Square', 'jacqueline') => 'square'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'jacqueline'),
					"description" => wp_kses_data( __("Icon's font size", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'jacqueline'),
					"description" => wp_kses_data( __("Icon's font weight", 'jacqueline') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'jacqueline') => 'inherit',
						esc_html__('Thin (100)', 'jacqueline') => '100',
						esc_html__('Light (300)', 'jacqueline') => '300',
						esc_html__('Normal (400)', 'jacqueline') => '400',
						esc_html__('Bold (700)', 'jacqueline') => '700'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Icon's alignment", 'jacqueline'),
					"description" => wp_kses_data( __("Align icon to left, center or right", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(jacqueline_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'jacqueline'),
					"description" => wp_kses_data( __("Link URL from this icon (if not empty)", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				jacqueline_get_vc_param('id'),
				jacqueline_get_vc_param('class'),
				jacqueline_get_vc_param('css'),
				jacqueline_get_vc_param('margin_top'),
				jacqueline_get_vc_param('margin_bottom'),
				jacqueline_get_vc_param('margin_left'),
				jacqueline_get_vc_param('margin_right')
			),
		) );
		
		class WPBakeryShortCode_Trx_Icon extends JACQUELINE_VC_ShortCodeSingle {}
	}
}
?>