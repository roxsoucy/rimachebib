<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jacqueline_sc_columns_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_columns_theme_setup' );
	function jacqueline_sc_columns_theme_setup() {
		add_action('jacqueline_action_shortcodes_list', 		'jacqueline_sc_columns_reg_shortcodes');
		if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
			add_action('jacqueline_action_shortcodes_list_vc','jacqueline_sc_columns_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('jacqueline_sc_columns')) {	
	function jacqueline_sc_columns($atts, $content=null){	
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			// Individual params
			"count" => "2",
			"fluid" => "no",
			"equal_height" => "no",
			"margins" => "yes",
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
		$count = max(1, min(12, (int) $count));
		jacqueline_storage_set('sc_columns_data', array(
			'counter' => 1,
            'after_span2' => false,
            'after_span3' => false,
            'after_span4' => false,
            'count' => $count
            )
        );
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="columns_wrap sc_columns'
					. ' columns_' . (jacqueline_param_is_on($fluid) ? 'fluid' : 'nofluid') 
					. (!empty($margins) && jacqueline_param_is_off($margins) ? ' no_margins' : '') 
					. ' sc_columns_count_' . esc_attr($count)
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. ($equal_height == 'yes' ? ' equal_height' : '') 
				. '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!jacqueline_param_is_off($animation) ? ' data-animation="'.esc_attr(jacqueline_get_animation_classes($animation)).'"' : '')
				. '>'
					. do_shortcode($content)
				. '</div>';
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_columns', $atts, $content);
	}
	jacqueline_require_shortcode('trx_columns', 'jacqueline_sc_columns');
}


if (!function_exists('jacqueline_sc_column_item')) {	
	function jacqueline_sc_column_item($atts, $content=null) {
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts( array(
			// Individual params
			"span" => "1",
			"align" => "",
			"color" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_tile" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => ""
		), $atts)));
		$css .= ($align !== '' ? 'text-align:' . esc_attr($align) . ';' : '') 
			. ($color !== '' ? 'color:' . esc_attr($color) . ';' : '');
		$span = max(1, min(11, (int) $span));
		if (!empty($bg_image)) {
			if ($bg_image > 0) {
				$attach = wp_get_attachment_image_src( $bg_image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$bg_image = $attach[0];
			}
		}
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="column-'.($span > 1 ? esc_attr($span) : 1).'_'.esc_attr(jacqueline_storage_get_array('sc_columns_data', 'count')).' sc_column_item sc_column_item_'.esc_attr(jacqueline_storage_get_array('sc_columns_data', 'counter')) 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (jacqueline_storage_get_array('sc_columns_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
					. (jacqueline_storage_get_array('sc_columns_data', 'counter') == 1 ? ' first' : '') 
					. ($span > 1 ? ' span_'.esc_attr($span) : '') 
					. (jacqueline_storage_get_array('sc_columns_data', 'after_span2') ? ' after_span_2' : '') 
					. (jacqueline_storage_get_array('sc_columns_data', 'after_span3') ? ' after_span_3' : '') 
					. (jacqueline_storage_get_array('sc_columns_data', 'after_span4') ? ' after_span_4' : '') 
					. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!jacqueline_param_is_off($animation) ? ' data-animation="'.esc_attr(jacqueline_get_animation_classes($animation)).'"' : '')
					. '>'
					. ($bg_color!=='' || $bg_image !== '' ? '<div class="sc_column_item_inner" style="'
							. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
							. ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');'.(jacqueline_param_is_on($bg_tile) ? 'background-repeat:repeat;' : 'background-repeat:no-repeat;background-size:cover;') : '')
							. '">' : '')
						. do_shortcode($content)
					. ($bg_color!=='' || $bg_image !== '' ? '</div>' : '')
					. '</div>';
		jacqueline_storage_inc_array('sc_columns_data', 'counter', $span);
		jacqueline_storage_set_array('sc_columns_data', 'after_span2', $span==2);
		jacqueline_storage_set_array('sc_columns_data', 'after_span3', $span==3);
		jacqueline_storage_set_array('sc_columns_data', 'after_span4', $span==4);
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_column_item', $atts, $content);
	}
	jacqueline_require_shortcode('trx_column_item', 'jacqueline_sc_column_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_columns_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_sc_columns_reg_shortcodes');
	function jacqueline_sc_columns_reg_shortcodes() {
	
		jacqueline_sc_map("trx_columns", array(
			"title" => esc_html__("Columns", 'jacqueline'),
			"desc" => wp_kses_data( __("Insert up to 5 columns in your page (post)", 'jacqueline') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"fluid" => array(
					"title" => esc_html__("Fluid columns", 'jacqueline'),
					"desc" => wp_kses_data( __("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", 'jacqueline') ),
					"value" => "no",
					"type" => "switch",
					"options" => jacqueline_get_sc_param('yes_no')
				), 
				"equal_height" => array(
					"title" => esc_html__("Equal height", 'jacqueline'),
					"desc" => wp_kses_data( __("Make columns equal height", 'jacqueline') ),
					"value" => "no",
					"type" => "switch",
					"options" => jacqueline_get_sc_param('yes_no')
				), 
				"margins" => array(
					"title" => esc_html__("Margins between columns", 'jacqueline'),
					"desc" => wp_kses_data( __("Add margins between columns", 'jacqueline') ),
					"value" => "yes",
					"type" => "switch",
					"options" => jacqueline_get_sc_param('yes_no')
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
			),
			"children" => array(
				"name" => "trx_column_item",
				"title" => esc_html__("Column", 'jacqueline'),
				"desc" => wp_kses_data( __("Column item", 'jacqueline') ),
				"container" => true,
				"params" => array(
					"span" => array(
						"title" => esc_html__("Merge columns", 'jacqueline'),
						"desc" => wp_kses_data( __("Count merged columns from current", 'jacqueline') ),
						"value" => "",
						"type" => "text"
					),
					"align" => array(
						"title" => esc_html__("Alignment", 'jacqueline'),
						"desc" => wp_kses_data( __("Alignment text in the column", 'jacqueline') ),
						"value" => "",
						"type" => "checklist",
						"dir" => "horizontal",
						"options" => jacqueline_get_sc_param('align')
					),
					"color" => array(
						"title" => esc_html__("Fore color", 'jacqueline'),
						"desc" => wp_kses_data( __("Any color for objects in this column", 'jacqueline') ),
						"value" => "",
						"type" => "color"
					),
					"bg_color" => array(
						"title" => esc_html__("Background color", 'jacqueline'),
						"desc" => wp_kses_data( __("Any background color for this column", 'jacqueline') ),
						"value" => "",
						"type" => "color"
					),
					"bg_image" => array(
						"title" => esc_html__("URL for background image file", 'jacqueline'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the background", 'jacqueline') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					),
					"bg_tile" => array(
						"title" => esc_html__("Tile background image", 'jacqueline'),
						"desc" => wp_kses_data( __("Do you want tile background image or image cover whole column?", 'jacqueline') ),
						"value" => "no",
						"dependency" => array(
							'bg_image' => array('not_empty')
						),
						"type" => "switch",
						"options" => jacqueline_get_sc_param('yes_no')
					),
					"_content_" => array(
						"title" => esc_html__("Column item content", 'jacqueline'),
						"desc" => wp_kses_data( __("Current column item content", 'jacqueline') ),
						"divider" => true,
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => jacqueline_get_sc_param('id'),
					"class" => jacqueline_get_sc_param('class'),
					"animation" => jacqueline_get_sc_param('animation'),
					"css" => jacqueline_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_columns_reg_shortcodes_vc' ) ) {
	//add_action('jacqueline_action_shortcodes_list_vc', 'jacqueline_sc_columns_reg_shortcodes_vc');
	function jacqueline_sc_columns_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_columns",
			"name" => esc_html__("Columns", 'jacqueline'),
			"description" => wp_kses_data( __("Insert columns with margins", 'jacqueline') ),
			"category" => esc_html__('Content', 'jacqueline'),
			'icon' => 'icon_trx_columns',
			"class" => "trx_sc_columns",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_column_item'),
			"params" => array(
				array(
					"param_name" => "count",
					"heading" => esc_html__("Columns count", 'jacqueline'),
					"description" => wp_kses_data( __("Number of the columns in the container.", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => "2",
					"type" => "textfield"
				),
				array(
					"param_name" => "fluid",
					"heading" => esc_html__("Fluid columns", 'jacqueline'),
					"description" => wp_kses_data( __("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", 'jacqueline') ),
					"class" => "",
					"value" => array(esc_html__('Fluid columns', 'jacqueline') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "equal_height",
					"heading" => esc_html__("Equal height", 'jacqueline'),
					"description" => wp_kses_data( __("Make columns equal height", 'jacqueline') ),
					"class" => "",
					"value" => array(esc_html__('Equal height', 'jacqueline') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "margins",
					"heading" => esc_html__("Margins between columns", 'jacqueline'),
					"description" => wp_kses_data( __("Add margins between columns", 'jacqueline') ),
					"class" => "",
					"std" => "yes",
					"value" => array(esc_html__('Disable margins between columns', 'jacqueline') => 'no'),
					"type" => "checkbox"
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
			'default_content' => '
				[trx_column_item][/trx_column_item]
				[trx_column_item][/trx_column_item]
			',
			'js_view' => 'VcTrxColumnsView'
		) );
		
		
		vc_map( array(
			"base" => "trx_column_item",
			"name" => esc_html__("Column", 'jacqueline'),
			"description" => wp_kses_data( __("Column item", 'jacqueline') ),
			"show_settings_on_create" => true,
			"class" => "trx_sc_collection trx_sc_column_item",
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_column_item',
			"as_child" => array('only' => 'trx_columns'),
			"as_parent" => array('except' => 'trx_columns'),
			"params" => array(
				array(
					"param_name" => "span",
					"heading" => esc_html__("Merge columns", 'jacqueline'),
					"description" => wp_kses_data( __("Count merged columns from current", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'jacqueline'),
					"description" => wp_kses_data( __("Alignment text in the column", 'jacqueline') ),
					"class" => "",
					"value" => array_flip(jacqueline_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Fore color", 'jacqueline'),
					"description" => wp_kses_data( __("Any color for objects in this column", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'jacqueline'),
					"description" => wp_kses_data( __("Any background color for this column", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("URL for background image file", 'jacqueline'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the background", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_tile",
					"heading" => esc_html__("Tile background image", 'jacqueline'),
					"description" => wp_kses_data( __("Do you want tile background image or image cover whole column?", 'jacqueline') ),
					"class" => "",
					'dependency' => array(
						'element' => 'bg_image',
						'not_empty' => true
					),
					"std" => "no",
					"value" => array(esc_html__('Tile background image', 'jacqueline') => 'yes'),
					"type" => "checkbox"
				),
				jacqueline_get_vc_param('id'),
				jacqueline_get_vc_param('class'),
				jacqueline_get_vc_param('animation'),
				jacqueline_get_vc_param('css')
			),
			'js_view' => 'VcTrxColumnItemView'
		) );
		
		class WPBakeryShortCode_Trx_Columns extends JACQUELINE_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Column_Item extends JACQUELINE_VC_ShortCodeCollection {}
	}
}
?>