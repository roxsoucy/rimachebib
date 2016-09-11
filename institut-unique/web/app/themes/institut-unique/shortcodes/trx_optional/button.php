<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jacqueline_sc_button_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_button_theme_setup' );
	function jacqueline_sc_button_theme_setup() {
		add_action('jacqueline_action_shortcodes_list', 		'jacqueline_sc_button_reg_shortcodes');
		if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
			add_action('jacqueline_action_shortcodes_list_vc','jacqueline_sc_button_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_button id="unique_id" type="square|round" fullsize="0|1" style="global|light|dark" size="mini|medium|big|huge|banner" icon="icon-name" link='#' target='']Button caption[/trx_button]
*/

if (!function_exists('jacqueline_sc_button')) {	
	function jacqueline_sc_button($atts, $content=null){	
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "square",
			"style" => "filled",
			"size" => "base",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"link" => "",
			"target" => "",
			"align" => "",
			"rel" => "",
			"popup" => "no",
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
		$css .= jacqueline_get_css_dimensions_from_values($width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . '; border-color:'. esc_attr($bg_color) .';' : '');
		if (jacqueline_param_is_on($popup)) jacqueline_enqueue_popup('magnific');
		$output = '<a href="' . (empty($link) ? '#' : $link) . '"'
			. (!empty($target) ? ' target="'.esc_attr($target).'"' : '')
			. (!empty($rel) ? ' rel="'.esc_attr($rel).'"' : '')
			. (!jacqueline_param_is_off($animation) ? ' data-animation="'.esc_attr(jacqueline_get_animation_classes($animation)).'"' : '')
			. ' class="sc_button sc_button_' . esc_attr($type) 
					. ' sc_button_style_' . esc_attr($style) 
					. ' sc_button_size_' . esc_attr($size)
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($icon!='' ? '  sc_button_iconed '. esc_attr($icon) : '') 
					. (jacqueline_param_is_on($popup) ? ' sc_popup_link' : '') 
					. '"'
			. ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. ($style == 'border' ?  '>'. do_shortcode($content). '</a>' : '><div>'
				. '<span class="first">'. do_shortcode($content). '</span>'
				. '<span class="second">'. do_shortcode($content). '</span>'
				. '</div></a>');
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_button', $atts, $content);
	}
	jacqueline_require_shortcode('trx_button', 'jacqueline_sc_button');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_button_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_sc_button_reg_shortcodes');
	function jacqueline_sc_button_reg_shortcodes() {
	
		jacqueline_sc_map("trx_button", array(
			"title" => esc_html__("Button", 'jacqueline'),
			"desc" => wp_kses_data( __("Button with link", 'jacqueline') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Caption", 'jacqueline'),
					"desc" => wp_kses_data( __("Button caption", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"type" => array(
					"title" => esc_html__("Button's shape", 'jacqueline'),
					"desc" => wp_kses_data( __("Select button's shape", 'jacqueline') ),
					"value" => "square",
					"size" => "medium",
					"options" => array(
						'square' => esc_html__('Square', 'jacqueline'),
						'round' => esc_html__('Round', 'jacqueline')
					),
					"type" => "switch"
				), 
				"style" => array(
					"title" => esc_html__("Button's style", 'jacqueline'),
					"desc" => wp_kses_data( __("Select button's style", 'jacqueline') ),
					"value" => "default",
					"dir" => "horizontal",
					"options" => array(
						'filled' => esc_html__('Filled', 'jacqueline'),
						'border' => esc_html__('Border', 'jacqueline')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Button's size", 'jacqueline'),
					"desc" => wp_kses_data( __("Select button's size", 'jacqueline') ),
					"value" => "small",
					"dir" => "horizontal",
					"options" => array(
						'base' => esc_html__('Base', 'jacqueline'),
						'small' => esc_html__('Small', 'jacqueline'),
						'medium' => esc_html__('Medium', 'jacqueline'),
						'large' => esc_html__('Large', 'jacqueline')
					),
					"type" => "checklist"
				), 
				"icon" => array(
					"title" => esc_html__("Button's icon",  'jacqueline'),
					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'jacqueline') ),
					"value" => "",
					"type" => "icons",
					"options" => jacqueline_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Button's text color", 'jacqueline'),
					"desc" => wp_kses_data( __("Any color for button's caption", 'jacqueline') ),
					"std" => "",
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Button's backcolor", 'jacqueline'),
					"desc" => wp_kses_data( __("Any color for button's background", 'jacqueline') ),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Button's alignment", 'jacqueline'),
					"desc" => wp_kses_data( __("Align button to left, center or right", 'jacqueline') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => jacqueline_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'jacqueline'),
					"desc" => wp_kses_data( __("URL for link on button click", 'jacqueline') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"target" => array(
					"title" => esc_html__("Link target", 'jacqueline'),
					"desc" => wp_kses_data( __("Target for link on button click", 'jacqueline') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"popup" => array(
					"title" => esc_html__("Open link in popup", 'jacqueline'),
					"desc" => wp_kses_data( __("Open link target in popup window", 'jacqueline') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "no",
					"type" => "switch",
					"options" => jacqueline_get_sc_param('yes_no')
				), 
				"rel" => array(
					"title" => esc_html__("Rel attribute", 'jacqueline'),
					"desc" => wp_kses_data( __("Rel attribute for button's link (if need)", 'jacqueline') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
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
if ( !function_exists( 'jacqueline_sc_button_reg_shortcodes_vc' ) ) {
	//add_action('jacqueline_action_shortcodes_list_vc', 'jacqueline_sc_button_reg_shortcodes_vc');
	function jacqueline_sc_button_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_button",
			"name" => esc_html__("Button", 'jacqueline'),
			"description" => wp_kses_data( __("Button with link", 'jacqueline') ),
			"category" => esc_html__('Content', 'jacqueline'),
			'icon' => 'icon_trx_button',
			"class" => "trx_sc_single trx_sc_button",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Caption", 'jacqueline'),
					"description" => wp_kses_data( __("Button caption", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Button's shape", 'jacqueline'),
					"description" => wp_kses_data( __("Select button's shape", 'jacqueline') ),
					"class" => "",
					"value" => array(
						esc_html__('Square', 'jacqueline') => 'square',
						esc_html__('Round', 'jacqueline') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Button's style", 'jacqueline'),
					"description" => wp_kses_data( __("Select button's style", 'jacqueline') ),
					"class" => "",
					"value" => array(
						esc_html__('Filled', 'jacqueline') => 'filled',
						esc_html__('Border', 'jacqueline') => 'border'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Button's size", 'jacqueline'),
					"description" => wp_kses_data( __("Select button's size", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Base', 'jacqueline') => 'base',
						esc_html__('Small', 'jacqueline') => 'small',
						esc_html__('Medium', 'jacqueline') => 'medium',
						esc_html__('Large', 'jacqueline') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Button's icon", 'jacqueline'),
					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'jacqueline') ),
					"class" => "",
					"value" => jacqueline_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Button's text color", 'jacqueline'),
					"description" => wp_kses_data( __("Any color for button's caption", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Button's backcolor", 'jacqueline'),
					"description" => wp_kses_data( __("Any color for button's background", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Button's alignment", 'jacqueline'),
					"description" => wp_kses_data( __("Align button to left, center or right", 'jacqueline') ),
					"class" => "",
					"value" => array_flip(jacqueline_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'jacqueline'),
					"description" => wp_kses_data( __("URL for the link on button click", 'jacqueline') ),
					"class" => "",
					"group" => esc_html__('Link', 'jacqueline'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'jacqueline'),
					"description" => wp_kses_data( __("Target for the link on button click", 'jacqueline') ),
					"class" => "",
					"group" => esc_html__('Link', 'jacqueline'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "popup",
					"heading" => esc_html__("Open link in popup", 'jacqueline'),
					"description" => wp_kses_data( __("Open link target in popup window", 'jacqueline') ),
					"class" => "",
					"group" => esc_html__('Link', 'jacqueline'),
					"value" => array(esc_html__('Open in popup', 'jacqueline') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "rel",
					"heading" => esc_html__("Rel attribute", 'jacqueline'),
					"description" => wp_kses_data( __("Rel attribute for the button's link (if need", 'jacqueline') ),
					"class" => "",
					"group" => esc_html__('Link', 'jacqueline'),
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
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Button extends JACQUELINE_VC_ShortCodeSingle {}
	}
}
?>