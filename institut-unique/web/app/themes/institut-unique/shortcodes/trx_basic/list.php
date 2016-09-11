<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jacqueline_sc_list_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_list_theme_setup' );
	function jacqueline_sc_list_theme_setup() {
		add_action('jacqueline_action_shortcodes_list', 		'jacqueline_sc_list_reg_shortcodes');
		if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
			add_action('jacqueline_action_shortcodes_list_vc','jacqueline_sc_list_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_list id="unique_id" style="arrows|iconed|ol|ul"]
	[trx_list_item id="unique_id" title="title_of_element"]Et adipiscing integer.[/trx_list_item]
	[trx_list_item]A pulvinar ut, parturient enim porta ut sed, mus amet nunc, in.[/trx_list_item]
	[trx_list_item]Duis sociis, elit odio dapibus nec, dignissim purus est magna integer.[/trx_list_item]
	[trx_list_item]Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus.[/trx_list_item]
[/trx_list]
*/

if (!function_exists('jacqueline_sc_list')) {	
	function jacqueline_sc_list($atts, $content=null){	
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "ul",
			"icon" => "icon-right",
			"icon_size" => "",
			"icon_color" => "",
			"color" => "",
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
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($style) == '' || (trim($icon) == '' && $style=='iconed')) $style = 'ul';
		jacqueline_storage_set('sc_list_data', array(
			'counter' => 0,
            'icon' => empty($icon) || jacqueline_param_is_inherit($icon) ? "icon-right" : $icon,
            'icon_color' => $icon_color,
            'style' => $style,
			'icon_size' => $icon_size
            )
        );
		$output = '<' . ($style=='ol' ? 'ol' : 'ul')
				. ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_list sc_list_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!jacqueline_param_is_off($animation) ? ' data-animation="'.esc_attr(jacqueline_get_animation_classes($animation)).'"' : '')
				. '>'
				. do_shortcode($content)
				. '</' .($style=='ol' ? 'ol' : 'ul') . '>';
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_list', $atts, $content);
	}
	jacqueline_require_shortcode('trx_list', 'jacqueline_sc_list');
}


if (!function_exists('jacqueline_sc_list_item')) {	
	function jacqueline_sc_list_item($atts, $content=null) {
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts( array(
			// Individual params
			"color" => "",
			"icon" => "",
			"icon_color" => "",
			"title" => "",
			"link" => "",
			"target" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		jacqueline_storage_inc_array('sc_list_data', 'counter');
		$icon_size = jacqueline_storage_get_array('sc_list_data', 'icon_size');
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		$css .= $icon_size !== '' ? 'padding-left: '.$icon_size.' !important;' : '';
		$css .= $icon_size !== '' ? 'padding-left: '.$icon_size.' !important;' : '';
		if (trim($icon) == '' || jacqueline_param_is_inherit($icon)) $icon = jacqueline_storage_get_array('sc_list_data', 'icon');
		if (trim($color) == '' || jacqueline_param_is_inherit($icon_color)) $icon_color = jacqueline_storage_get_array('sc_list_data', 'icon_color');
		$content = do_shortcode($content);
		if (empty($content)) $content = $title;
		$output = '<li' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_list_item' 
			. (!empty($class) ? ' '.esc_attr($class) : '')
			. (jacqueline_storage_get_array('sc_list_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
			. (jacqueline_storage_get_array('sc_list_data', 'counter') == 1 ? ' first' : '')  
			. '"' 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. ($title ? ' title="'.esc_attr($title).'"' : '') 
			. '>' 
			. (!empty($link) ? '<a href="'.esc_url($link).'"' . (!empty($target) ? ' target="'.esc_attr($target).'"' : '') . '>' : '')
			. (jacqueline_storage_get_array('sc_list_data', 'style')=='iconed' && $icon!='' ? '<span class="sc_list_icon '.esc_attr($icon).'"'.($icon_color !== '' || $icon_size !== '' ? ' style="color:'.esc_attr($icon_color).';font-size:'.esc_attr($icon_size).';"' : '').'></span>' : '')
			. trim($content)
			. (!empty($link) ? '</a>': '')
			. '</li>';
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_list_item', $atts, $content);
	}
	jacqueline_require_shortcode('trx_list_item', 'jacqueline_sc_list_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_list_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_sc_list_reg_shortcodes');
	function jacqueline_sc_list_reg_shortcodes() {
	
		jacqueline_sc_map("trx_list", array(
			"title" => esc_html__("List", 'jacqueline'),
			"desc" => wp_kses_data( __("List items with specific bullets", 'jacqueline') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Bullet's style", 'jacqueline'),
					"desc" => wp_kses_data( __("Bullet's style for each list item", 'jacqueline') ),
					"value" => "ul",
					"type" => "checklist",
					"options" => jacqueline_get_sc_param('list_styles')
				), 
				"color" => array(
					"title" => esc_html__("Color", 'jacqueline'),
					"desc" => wp_kses_data( __("List items color", 'jacqueline') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('List icon',  'jacqueline'),
					"desc" => wp_kses_data( __("Select list icon from Fontello icons set (only for style=Iconed)",  'jacqueline') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => jacqueline_get_sc_param('icons')
				),
				"icon_size" => array(
					"title" => esc_html__('Icon size',  'jacqueline'),
					"desc" => wp_kses_data( __("Write icon size",  'jacqueline') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "textarea"
				),
				"icon_color" => array(
					"title" => esc_html__("Icon color", 'jacqueline'),
					"desc" => wp_kses_data( __("List icons color", 'jacqueline') ),
					"value" => "",
					"dependency" => array(
						'style' => array('iconed')
					),
					"type" => "color"
				),
				"top" => jacqueline_get_sc_param('top'),
				"bottom" => jacqueline_get_sc_param('bottom'),
				"left" => jacqueline_get_sc_param('left'),
				"right" => jacqueline_get_sc_param('right'),
				"id" => jacqueline_get_sc_param('id'),
				"class" => jacqueline_get_sc_param('class'),
				"animation" => jacqueline_get_sc_param('animation'),
				"css" => jacqueline_get_sc_param('css')
			),
			"children" => array(
				"name" => "trx_list_item",
				"title" => esc_html__("Item", 'jacqueline'),
				"desc" => wp_kses_data( __("List item with specific bullet", 'jacqueline') ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"_content_" => array(
						"title" => esc_html__("List item content", 'jacqueline'),
						"desc" => wp_kses_data( __("Current list item content", 'jacqueline') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"title" => array(
						"title" => esc_html__("List item title", 'jacqueline'),
						"desc" => wp_kses_data( __("Current list item title (show it as tooltip)", 'jacqueline') ),
						"value" => "",
						"type" => "text"
					),
					"color" => array(
						"title" => esc_html__("Color", 'jacqueline'),
						"desc" => wp_kses_data( __("Text color for this item", 'jacqueline') ),
						"value" => "",
						"type" => "color"
					),
					"icon" => array(
						"title" => esc_html__('List icon',  'jacqueline'),
						"desc" => wp_kses_data( __("Select list item icon from Fontello icons set (only for style=Iconed)",  'jacqueline') ),
						"value" => "",
						"type" => "icons",
						"options" => jacqueline_get_sc_param('icons')
					),
					"icon_color" => array(
						"title" => esc_html__("Icon color", 'jacqueline'),
						"desc" => wp_kses_data( __("Icon color for this item", 'jacqueline') ),
						"value" => "",
						"type" => "color"
					),
					"link" => array(
						"title" => esc_html__("Link URL", 'jacqueline'),
						"desc" => wp_kses_data( __("Link URL for the current list item", 'jacqueline') ),
						"divider" => true,
						"value" => "",
						"type" => "text"
					),
					"target" => array(
						"title" => esc_html__("Link target", 'jacqueline'),
						"desc" => wp_kses_data( __("Link target for the current list item", 'jacqueline') ),
						"value" => "",
						"type" => "text"
					),
					"id" => jacqueline_get_sc_param('id'),
					"class" => jacqueline_get_sc_param('class'),
					"css" => jacqueline_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_list_reg_shortcodes_vc' ) ) {
	//add_action('jacqueline_action_shortcodes_list_vc', 'jacqueline_sc_list_reg_shortcodes_vc');
	function jacqueline_sc_list_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_list",
			"name" => esc_html__("List", 'jacqueline'),
			"description" => wp_kses_data( __("List items with specific bullets", 'jacqueline') ),
			"category" => esc_html__('Content', 'jacqueline'),
			"class" => "trx_sc_collection trx_sc_list",
			'icon' => 'icon_trx_list',
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_list_item'),
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Bullet's style", 'jacqueline'),
					"description" => wp_kses_data( __("Bullet's style for each list item", 'jacqueline') ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip(jacqueline_get_sc_param('list_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", 'jacqueline'),
					"description" => wp_kses_data( __("List items color", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("List icon", 'jacqueline'),
					"description" => wp_kses_data( __("Select list icon from Fontello icons set (only for style=Iconed)", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => jacqueline_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_size",
					"heading" => esc_html__("Icon size", 'jacqueline'),
					"description" => wp_kses_data( __("Write icon size", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", 'jacqueline'),
					"description" => wp_kses_data( __("List icons color", 'jacqueline') ),
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
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
			'default_content' => '
				[trx_list_item][/trx_list_item]
				[trx_list_item][/trx_list_item]
			'
		) );
		
		
		vc_map( array(
			"base" => "trx_list_item",
			"name" => esc_html__("List item", 'jacqueline'),
			"description" => wp_kses_data( __("List item with specific bullet", 'jacqueline') ),
			"class" => "trx_sc_container trx_sc_list_item",
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_list_item',
			"as_child" => array('only' => 'trx_list'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"as_parent" => array('except' => 'trx_list'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("List item title", 'jacqueline'),
					"description" => wp_kses_data( __("Title for the current list item (show it as tooltip)", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'jacqueline'),
					"description" => wp_kses_data( __("Link URL for the current list item", 'jacqueline') ),
					"admin_label" => true,
					"group" => esc_html__('Link', 'jacqueline'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'jacqueline'),
					"description" => wp_kses_data( __("Link target for the current list item", 'jacqueline') ),
					"admin_label" => true,
					"group" => esc_html__('Link', 'jacqueline'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", 'jacqueline'),
					"description" => wp_kses_data( __("Text color for this item", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("List item icon", 'jacqueline'),
					"description" => wp_kses_data( __("Select list item icon from Fontello icons set (only for style=Iconed)", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => jacqueline_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", 'jacqueline'),
					"description" => wp_kses_data( __("Icon color for this item", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("List item text", 'jacqueline'),
					"description" => wp_kses_data( __("Current list item content", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
*/
				jacqueline_get_vc_param('id'),
				jacqueline_get_vc_param('class'),
				jacqueline_get_vc_param('css')
			)
		
		) );
		
		class WPBakeryShortCode_Trx_List extends JACQUELINE_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_List_Item extends JACQUELINE_VC_ShortCodeContainer {}
	}
}
?>