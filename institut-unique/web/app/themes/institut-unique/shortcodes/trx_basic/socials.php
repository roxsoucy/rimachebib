<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jacqueline_sc_socials_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_socials_theme_setup' );
	function jacqueline_sc_socials_theme_setup() {
		add_action('jacqueline_action_shortcodes_list', 		'jacqueline_sc_socials_reg_shortcodes');
		if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
			add_action('jacqueline_action_shortcodes_list_vc','jacqueline_sc_socials_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */


if (!function_exists('jacqueline_sc_socials')) {	
	function jacqueline_sc_socials($atts, $content=null){	
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "small",		// tiny | small | medium | large
			"shape" => "square",	// round | square
			"type" => jacqueline_get_theme_setting('socials_type'),	// icons | images
			"socials" => "",
			"custom" => "no",
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
		jacqueline_storage_set('sc_social_data', array(
			'icons' => false,
            'type' => $type
            )
        );
		if (!empty($socials)) {
			$allowed = explode('|', $socials);
			$list = array();
			for ($i=0; $i<count($allowed); $i++) {
				$s = explode('=', $allowed[$i]);
				if (!empty($s[1])) {
					$list[] = array(
						'icon'	=> $type=='images' ? jacqueline_get_socials_url($s[0]) : 'icon-'.trim($s[0]),
						'url'	=> $s[1]
						);
				}
			}
			if (count($list) > 0) jacqueline_storage_set_array('sc_social_data', 'icons', $list);
		} else if (jacqueline_param_is_off($custom))
			$content = do_shortcode($content);
		if (jacqueline_storage_get_array('sc_social_data', 'icons')===false) jacqueline_storage_set_array('sc_social_data', 'icons', jacqueline_get_custom_option('social_icons'));
		$output = jacqueline_prepare_socials(jacqueline_storage_get_array('sc_social_data', 'icons'), $type);
		$output = $output
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_socials sc_socials_type_' . esc_attr($type) . ' sc_socials_shape_' . esc_attr($shape) . ' sc_socials_size_' . esc_attr($size) . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!jacqueline_param_is_off($animation) ? ' data-animation="'.esc_attr(jacqueline_get_animation_classes($animation)).'"' : '')
				. '>' 
				. ($output)
				. '</div>'
			: '';
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_socials', $atts, $content);
	}
	jacqueline_require_shortcode('trx_socials', 'jacqueline_sc_socials');
}


if (!function_exists('jacqueline_sc_social_item')) {	
	function jacqueline_sc_social_item($atts, $content=null){	
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			// Individual params
			"name" => "",
			"url" => "",
			"icon" => ""
		), $atts)));
		if (!empty($name) && empty($icon)) {
			$type = jacqueline_storage_get_array('sc_social_data', 'type');
			if ($type=='images') {
				if (file_exists(jacqueline_get_socials_dir($name.'.png')))
					$icon = jacqueline_get_socials_url($name.'.png');
			} else
				$icon = 'icon-'.esc_attr($name);
		}
		if (!empty($icon) && !empty($url)) {
			if (jacqueline_storage_get_array('sc_social_data', 'icons')===false) jacqueline_storage_set_array('sc_social_data', 'icons', array());
			jacqueline_storage_set_array2('sc_social_data', 'icons', '', array(
				'icon' => $icon,
				'url' => $url
				)
			);
		}
		return '';
	}
	jacqueline_require_shortcode('trx_social_item', 'jacqueline_sc_social_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_socials_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_sc_socials_reg_shortcodes');
	function jacqueline_sc_socials_reg_shortcodes() {
	
		jacqueline_sc_map("trx_socials", array(
			"title" => esc_html__("Social icons", 'jacqueline'),
			"desc" => wp_kses_data( __("List of social icons (with hovers)", 'jacqueline') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Icon's type", 'jacqueline'),
					"desc" => wp_kses_data( __("Type of the icons - images or font icons", 'jacqueline') ),
					"value" => jacqueline_get_theme_setting('socials_type'),
					"options" => array(
						'icons' => esc_html__('Icons', 'jacqueline'),
						'images' => esc_html__('Images', 'jacqueline')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Icon's size", 'jacqueline'),
					"desc" => wp_kses_data( __("Size of the icons", 'jacqueline') ),
					"value" => "small",
					"options" => jacqueline_get_sc_param('sizes'),
					"type" => "checklist"
				), 
				"shape" => array(
					"title" => esc_html__("Icon's shape", 'jacqueline'),
					"desc" => wp_kses_data( __("Shape of the icons", 'jacqueline') ),
					"value" => "square",
					"options" => jacqueline_get_sc_param('shapes'),
					"type" => "checklist"
				), 
				"socials" => array(
					"title" => esc_html__("Manual socials list", 'jacqueline'),
					"desc" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'jacqueline') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"custom" => array(
					"title" => esc_html__("Custom socials", 'jacqueline'),
					"desc" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'jacqueline') ),
					"divider" => true,
					"value" => "no",
					"options" => jacqueline_get_sc_param('yes_no'),
					"type" => "switch"
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
				"name" => "trx_social_item",
				"title" => esc_html__("Custom social item", 'jacqueline'),
				"desc" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'jacqueline') ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"name" => array(
						"title" => esc_html__("Social name", 'jacqueline'),
						"desc" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'jacqueline') ),
						"value" => "",
						"type" => "text"
					),
					"url" => array(
						"title" => esc_html__("Your profile URL", 'jacqueline'),
						"desc" => wp_kses_data( __("URL of your profile in specified social network", 'jacqueline') ),
						"value" => "",
						"type" => "text"
					),
					"icon" => array(
						"title" => esc_html__("URL (source) for icon file", 'jacqueline'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'jacqueline') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_socials_reg_shortcodes_vc' ) ) {
	//add_action('jacqueline_action_shortcodes_list_vc', 'jacqueline_sc_socials_reg_shortcodes_vc');
	function jacqueline_sc_socials_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_socials",
			"name" => esc_html__("Social icons", 'jacqueline'),
			"description" => wp_kses_data( __("Custom social icons", 'jacqueline') ),
			"category" => esc_html__('Content', 'jacqueline'),
			'icon' => 'icon_trx_socials',
			"class" => "trx_sc_collection trx_sc_socials",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_social_item'),
			"params" => array_merge(array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Icon's type", 'jacqueline'),
					"description" => wp_kses_data( __("Type of the icons - images or font icons", 'jacqueline') ),
					"class" => "",
					"std" => jacqueline_get_theme_setting('socials_type'),
					"value" => array(
						esc_html__('Icons', 'jacqueline') => 'icons',
						esc_html__('Images', 'jacqueline') => 'images'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Icon's size", 'jacqueline'),
					"description" => wp_kses_data( __("Size of the icons", 'jacqueline') ),
					"class" => "",
					"std" => "small",
					"value" => array_flip(jacqueline_get_sc_param('sizes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Icon's shape", 'jacqueline'),
					"description" => wp_kses_data( __("Shape of the icons", 'jacqueline') ),
					"class" => "",
					"std" => "square",
					"value" => array_flip(jacqueline_get_sc_param('shapes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "socials",
					"heading" => esc_html__("Manual socials list", 'jacqueline'),
					"description" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom socials", 'jacqueline'),
					"description" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'jacqueline') ),
					"class" => "",
					"value" => array(esc_html__('Custom socials', 'jacqueline') => 'yes'),
					"type" => "checkbox"
				),
				jacqueline_get_vc_param('id'),
				jacqueline_get_vc_param('class'),
				jacqueline_get_vc_param('animation'),
				jacqueline_get_vc_param('css'),
				jacqueline_get_vc_param('margin_top'),
				jacqueline_get_vc_param('margin_bottom'),
				jacqueline_get_vc_param('margin_left'),
				jacqueline_get_vc_param('margin_right')
			))
		) );
		
		
		vc_map( array(
			"base" => "trx_social_item",
			"name" => esc_html__("Custom social item", 'jacqueline'),
			"description" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'jacqueline') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			'icon' => 'icon_trx_social_item',
			"class" => "trx_sc_single trx_sc_social_item",
			"as_child" => array('only' => 'trx_socials'),
			"as_parent" => array('except' => 'trx_socials'),
			"params" => array(
				array(
					"param_name" => "name",
					"heading" => esc_html__("Social name", 'jacqueline'),
					"description" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Your profile URL", 'jacqueline'),
					"description" => wp_kses_data( __("URL of your profile in specified social network", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("URL (source) for icon file", 'jacqueline'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Socials extends JACQUELINE_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Social_Item extends JACQUELINE_VC_ShortCodeSingle {}
	}
}
?>