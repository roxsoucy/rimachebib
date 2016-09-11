<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jacqueline_sc_googlemap_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_googlemap_theme_setup' );
	function jacqueline_sc_googlemap_theme_setup() {
		add_action('jacqueline_action_shortcodes_list', 		'jacqueline_sc_googlemap_reg_shortcodes');
		if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
			add_action('jacqueline_action_shortcodes_list_vc','jacqueline_sc_googlemap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_googlemap id="unique_id" width="width_in_pixels_or_percent" height="height_in_pixels"]
//	[trx_googlemap_marker address="your_address"]
//[/trx_googlemap]

if (!function_exists('jacqueline_sc_googlemap')) {	
	function jacqueline_sc_googlemap($atts, $content = null) {
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			// Individual params
			"zoom" => 16,
			"style" => 'default',
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "100%",
			"height" => "400",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= ($css ? '; ' : '') . jacqueline_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= jacqueline_get_css_dimensions_from_values($width, $height);
		if (empty($id)) $id = 'sc_googlemap_'.str_replace('.', '', mt_rand());
		if (empty($style)) $style = jacqueline_get_custom_option('googlemap_style');
		$api_key = jacqueline_get_theme_option('api_google');
		jacqueline_enqueue_script( 'googlemap', jacqueline_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
		jacqueline_enqueue_script( 'jacqueline-googlemap-script', jacqueline_get_file_url('js/core.googlemap.js'), array(), null, true );

		jacqueline_storage_set('sc_googlemap_markers', array());
		$content = do_shortcode($content);
		$output = '';
		$markers = jacqueline_storage_get('sc_googlemap_markers');
		if (count($markers) == 0) {
			$markers[] = array(
				'title' => jacqueline_get_custom_option('googlemap_title'),
				'description' => jacqueline_strmacros(jacqueline_get_custom_option('googlemap_description')),
				'latlng' => jacqueline_get_custom_option('googlemap_latlng'),
				'address' => jacqueline_get_custom_option('googlemap_address'),
				'point' => jacqueline_get_custom_option('googlemap_marker')
			);
		}
		$output .= 
			($content ? '<div id="'.esc_attr($id).'_wrap" class="sc_googlemap_wrap'
					. ($scheme && !jacqueline_param_is_off($scheme) && !jacqueline_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. '">' : '')
			. '<div id="'.esc_attr($id).'"'
				. ' class="sc_googlemap'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!jacqueline_param_is_off($animation) ? ' data-animation="'.esc_attr(jacqueline_get_animation_classes($animation)).'"' : '')
				. ' data-zoom="'.esc_attr($zoom).'"'
				. ' data-style="'.esc_attr($style).'"'
				. '>';
		$cnt = 0;
		foreach ($markers as $marker) {
			$cnt++;
			if (empty($marker['id'])) $marker['id'] = $id.'_'.intval($cnt);
			$output .= '<div id="'.esc_attr($marker['id']).'" class="sc_googlemap_marker"'
				. ' data-title="'.esc_attr($marker['title']).'"'
				. ' data-description="'.esc_attr(jacqueline_strmacros($marker['description'])).'"'
				. ' data-address="'.esc_attr($marker['address']).'"'
				. ' data-latlng="'.esc_attr($marker['latlng']).'"'
				. ' data-point="'.esc_attr($marker['point']).'"'
				. '></div>';
		}
		$output .= '</div>'
			. ($content ? '<div class="sc_googlemap_content">' . trim($content) . '</div></div>' : '');
			
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_googlemap', $atts, $content);
	}
	jacqueline_require_shortcode("trx_googlemap", "jacqueline_sc_googlemap");
}


if (!function_exists('jacqueline_sc_googlemap_marker')) {	
	function jacqueline_sc_googlemap_marker($atts, $content = null) {
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"address" => "",
			"latlng" => "",
			"point" => "",
			// Common params
			"id" => ""
		), $atts)));
		if (!empty($point)) {
			if ($point > 0) {
				$attach = wp_get_attachment_image_src( $point, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$point = $attach[0];
			}
		}
		$content = do_shortcode($content);
		jacqueline_storage_set_array('sc_googlemap_markers', '', array(
			'id' => $id,
			'title' => $title,
			'description' => !empty($content) ? $content : $address,
			'latlng' => $latlng,
			'address' => $address,
			'point' => $point ? $point : jacqueline_get_custom_option('googlemap_marker')
			)
		);
		return '';
	}
	jacqueline_require_shortcode("trx_googlemap_marker", "jacqueline_sc_googlemap_marker");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_googlemap_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_sc_googlemap_reg_shortcodes');
	function jacqueline_sc_googlemap_reg_shortcodes() {
	
		jacqueline_sc_map("trx_googlemap", array(
			"title" => esc_html__("Google map", 'jacqueline'),
			"desc" => wp_kses_data( __("Insert Google map with specified markers", 'jacqueline') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"zoom" => array(
					"title" => esc_html__("Zoom", 'jacqueline'),
					"desc" => wp_kses_data( __("Map zoom factor", 'jacqueline') ),
					"divider" => true,
					"value" => 16,
					"min" => 1,
					"max" => 20,
					"type" => "spinner"
				),
				"style" => array(
					"title" => esc_html__("Map style", 'jacqueline'),
					"desc" => wp_kses_data( __("Select map style", 'jacqueline') ),
					"value" => "default",
					"type" => "checklist",
					"options" => jacqueline_get_sc_param('googlemap_styles')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'jacqueline'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'jacqueline') ),
					"value" => "",
					"type" => "checklist",
					"options" => jacqueline_get_sc_param('schemes')
				),
				"width" => jacqueline_shortcodes_width('100%'),
				"height" => jacqueline_shortcodes_height(240),
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
				"name" => "trx_googlemap_marker",
				"title" => esc_html__("Google map marker", 'jacqueline'),
				"desc" => wp_kses_data( __("Google map marker", 'jacqueline') ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"address" => array(
						"title" => esc_html__("Address", 'jacqueline'),
						"desc" => wp_kses_data( __("Address of this marker", 'jacqueline') ),
						"value" => "",
						"type" => "text"
					),
					"latlng" => array(
						"title" => esc_html__("Latitude and Longitude", 'jacqueline'),
						"desc" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", 'jacqueline') ),
						"value" => "",
						"type" => "text"
					),
					"point" => array(
						"title" => esc_html__("URL for marker image file", 'jacqueline'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'jacqueline') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					),
					"title" => array(
						"title" => esc_html__("Title", 'jacqueline'),
						"desc" => wp_kses_data( __("Title for this marker", 'jacqueline') ),
						"value" => "",
						"type" => "text"
					),
					"_content_" => array(
						"title" => esc_html__("Description", 'jacqueline'),
						"desc" => wp_kses_data( __("Description for this marker", 'jacqueline') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => jacqueline_get_sc_param('id')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_googlemap_reg_shortcodes_vc' ) ) {
	//add_action('jacqueline_action_shortcodes_list_vc', 'jacqueline_sc_googlemap_reg_shortcodes_vc');
	function jacqueline_sc_googlemap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_googlemap",
			"name" => esc_html__("Google map", 'jacqueline'),
			"description" => wp_kses_data( __("Insert Google map with desired address or coordinates", 'jacqueline') ),
			"category" => esc_html__('Content', 'jacqueline'),
			'icon' => 'icon_trx_googlemap',
			"class" => "trx_sc_collection trx_sc_googlemap",
			"content_element" => true,
			"is_container" => true,
			"as_parent" => array('only' => 'trx_googlemap_marker,trx_form,trx_section,trx_block,trx_promo'),
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "zoom",
					"heading" => esc_html__("Zoom", 'jacqueline'),
					"description" => wp_kses_data( __("Map zoom factor", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => "16",
					"type" => "textfield"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'jacqueline'),
					"description" => wp_kses_data( __("Map custom style", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(jacqueline_get_sc_param('googlemap_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'jacqueline'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'jacqueline') ),
					"class" => "",
					"value" => array_flip(jacqueline_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				jacqueline_get_vc_param('id'),
				jacqueline_get_vc_param('class'),
				jacqueline_get_vc_param('animation'),
				jacqueline_get_vc_param('css'),
				jacqueline_vc_width('100%'),
				jacqueline_vc_height(240),
				jacqueline_get_vc_param('margin_top'),
				jacqueline_get_vc_param('margin_bottom'),
				jacqueline_get_vc_param('margin_left'),
				jacqueline_get_vc_param('margin_right')
			)
		) );
		
		vc_map( array(
			"base" => "trx_googlemap_marker",
			"name" => esc_html__("Googlemap marker", 'jacqueline'),
			"description" => wp_kses_data( __("Insert new marker into Google map", 'jacqueline') ),
			"class" => "trx_sc_collection trx_sc_googlemap_marker",
			'icon' => 'icon_trx_googlemap_marker',
			//"allowed_container_element" => 'vc_row',
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			"as_child" => array('only' => 'trx_googlemap'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "address",
					"heading" => esc_html__("Address", 'jacqueline'),
					"description" => wp_kses_data( __("Address of this marker", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "latlng",
					"heading" => esc_html__("Latitude and Longitude", 'jacqueline'),
					"description" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'jacqueline'),
					"description" => wp_kses_data( __("Title for this marker", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "point",
					"heading" => esc_html__("URL for marker image file", 'jacqueline'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				jacqueline_get_vc_param('id')
			)
		) );
		
		class WPBakeryShortCode_Trx_Googlemap extends JACQUELINE_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Googlemap_Marker extends JACQUELINE_VC_ShortCodeCollection {}
	}
}
?>