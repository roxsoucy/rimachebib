<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jacqueline_sc_infobox_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_infobox_theme_setup' );
	function jacqueline_sc_infobox_theme_setup() {
		add_action('jacqueline_action_shortcodes_list', 		'jacqueline_sc_infobox_reg_shortcodes');
		if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
			add_action('jacqueline_action_shortcodes_list_vc','jacqueline_sc_infobox_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */


if (!function_exists('jacqueline_sc_infobox')) {	
	function jacqueline_sc_infobox($atts, $content=null){	
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "warning",
			"closeable" => "no",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= ($css ? '; ' : '') . jacqueline_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) .';' : '');
		if (empty($icon)) {
			if ($icon=='none')
				$icon = '';
			else if ($style=='warning')
				$icon = 'icon-ray';
			else if ($style=='error')
				$icon = 'icon-error';
			else if ($style=='success')
				$icon = 'icon-check-1';
			else if ($style=='info')
				$icon = 'icon-info-1';
		}
		$content = do_shortcode($content);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_infobox sc_infobox_style_' . esc_attr($style) 
					. (jacqueline_param_is_on($closeable) ? ' sc_infobox_closeable' : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. ($icon!='' && !jacqueline_param_is_inherit($icon) ? ' sc_infobox_iconed '. esc_attr($icon) : '') 
					. '"'
				. (!jacqueline_param_is_off($animation) ? ' data-animation="'.esc_attr(jacqueline_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>'
				. trim($content)
				. '</div>';
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_infobox', $atts, $content);
	}
	jacqueline_require_shortcode('trx_infobox', 'jacqueline_sc_infobox');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_infobox_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_sc_infobox_reg_shortcodes');
	function jacqueline_sc_infobox_reg_shortcodes() {
	
		jacqueline_sc_map("trx_infobox", array(
			"title" => esc_html__("Infobox", 'jacqueline'),
			"desc" => wp_kses_data( __("Insert infobox into your post (page)", 'jacqueline') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'jacqueline'),
					"desc" => wp_kses_data( __("Infobox style", 'jacqueline') ),
					"value" => "regular",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'warning' => esc_html__('Warning', 'jacqueline'),
						'info' => esc_html__('Info', 'jacqueline'),
						'success' => esc_html__('Success', 'jacqueline'),
						'error' => esc_html__('Error', 'jacqueline')
					)
				),
				"closeable" => array(
					"title" => esc_html__("Closeable box", 'jacqueline'),
					"desc" => wp_kses_data( __("Create closeable box (with close button)", 'jacqueline') ),
					"value" => "no",
					"type" => "switch",
					"options" => jacqueline_get_sc_param('yes_no')
				),
				"icon" => array(
					"title" => esc_html__("Custom icon",  'jacqueline'),
					"desc" => wp_kses_data( __('Select icon for the infobox from Fontello icons set. If empty - use default icon',  'jacqueline') ),
					"value" => "",
					"type" => "icons",
					"options" => jacqueline_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Text color", 'jacqueline'),
					"desc" => wp_kses_data( __("Any color for text and headers", 'jacqueline') ),
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'jacqueline'),
					"desc" => wp_kses_data( __("Any background color for this infobox", 'jacqueline') ),
					"value" => "",
					"type" => "color"
				),
				"_content_" => array(
					"title" => esc_html__("Infobox content", 'jacqueline'),
					"desc" => wp_kses_data( __("Content for infobox", 'jacqueline') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
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
if ( !function_exists( 'jacqueline_sc_infobox_reg_shortcodes_vc' ) ) {
	//add_action('jacqueline_action_shortcodes_list_vc', 'jacqueline_sc_infobox_reg_shortcodes_vc');
	function jacqueline_sc_infobox_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_infobox",
			"name" => esc_html__("Infobox", 'jacqueline'),
			"description" => wp_kses_data( __("Box with info or error message", 'jacqueline') ),
			"category" => esc_html__('Content', 'jacqueline'),
			'icon' => 'icon_trx_infobox',
			"class" => "trx_sc_container trx_sc_infobox",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'jacqueline'),
					"description" => wp_kses_data( __("Infobox style", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Warning', 'jacqueline') => 'warning',
							esc_html__('Info', 'jacqueline') => 'info',
							esc_html__('Success', 'jacqueline') => 'success',
							esc_html__('Error', 'jacqueline') => 'error'
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "closeable",
					"heading" => esc_html__("Closeable", 'jacqueline'),
					"description" => wp_kses_data( __("Create closeable box (with close button)", 'jacqueline') ),
					"class" => "",
					"value" => array(esc_html__('Close button', 'jacqueline') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Custom icon", 'jacqueline'),
					"description" => wp_kses_data( __("Select icon for the infobox from Fontello icons set. If empty - use default icon", 'jacqueline') ),
					"class" => "",
					"value" => jacqueline_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'jacqueline'),
					"description" => wp_kses_data( __("Any color for the text and headers", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'jacqueline'),
					"description" => wp_kses_data( __("Any background color for this infobox", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				jacqueline_get_vc_param('id'),
				jacqueline_get_vc_param('class'),
				jacqueline_get_vc_param('animation'),
				jacqueline_get_vc_param('css'),
				jacqueline_get_vc_param('margin_top'),
				jacqueline_get_vc_param('margin_bottom'),
				jacqueline_get_vc_param('margin_left'),
				jacqueline_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextContainerView'
		) );
		
		class WPBakeryShortCode_Trx_Infobox extends JACQUELINE_VC_ShortCodeContainer {}
	}
}
?>