<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jacqueline_sc_price_block_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_price_block_theme_setup' );
	function jacqueline_sc_price_block_theme_setup() {
		add_action('jacqueline_action_shortcodes_list', 		'jacqueline_sc_price_block_reg_shortcodes');
		if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
			add_action('jacqueline_action_shortcodes_list_vc','jacqueline_sc_price_block_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('jacqueline_sc_price_block')) {	
	function jacqueline_sc_price_block($atts, $content=null){	
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			// Individual params
		//	"style" => 1,
			"title" => "",
			"subtitle" => "",
			"link" => "",
			"link_text" => "",
			"icon" => "",
			"icon_color" => "",
			"money" => "",
			"currency" => "$",
			"period" => "",
			"align" => "",
			"scheme" => "",
			"featured" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$output = '';
		$style = 1;
		$css .= ($css ? '; ' : '') . jacqueline_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= jacqueline_get_css_dimensions_from_values($width, $height);
		if ($money) $money = do_shortcode('[trx_price money="'.esc_attr($money).'" period="'.esc_attr($period).'"'.($currency ? ' currency="'.esc_attr($currency).'"' : '').']');
		$content = do_shortcode(jacqueline_sc_clear_around($content));
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_price_block sc_price_block_style_'.max(1, min(3, $style))
						. (!empty($class) ? ' '.esc_attr($class) : '')
						. ($scheme && !jacqueline_param_is_off($scheme) && !jacqueline_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. ($featured == 'yes' ? ' featured' : '') 
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!jacqueline_param_is_off($animation) ? ' data-animation="'.esc_attr(jacqueline_get_animation_classes($animation)).'"' : '')
					. '>'
				. (!empty($icon) ? '<div class="sc_price_block_icon '.esc_attr($icon).'" '.(!empty($icon_color) ? 'style="color:'.$icon_color.';"' : '').'></div>' : '')
				. (!empty($subtitle) ? '<h6 class="sc_price_block_subtitle sc_item_subtitle">' . trim(jacqueline_strmacros($subtitle)) . '</h6>' : '')
				. (!empty($title) ? '<div class="sc_price_block_title"><span>'.($title).'</span></div>' : '')
				. '<div class="sc_price_block_money">'
					. ($money)
				. '</div>'
				. (!empty($content) ? '<div class="sc_price_block_description">'.($content).'</div>' : '')
				. (!empty($link_text) ? '<div class="sc_price_block_link">'.do_shortcode('[trx_button link="'.($link ? esc_url($link) : '#').'" size="small"]'.($link_text).'[/trx_button]').'</div>' : '')
			. '</div>';
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_price_block', $atts, $content);
	}
	jacqueline_require_shortcode('trx_price_block', 'jacqueline_sc_price_block');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_price_block_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_sc_price_block_reg_shortcodes');
	function jacqueline_sc_price_block_reg_shortcodes() {
	
		jacqueline_sc_map("trx_price_block", array(
			"title" => esc_html__("Price block", 'jacqueline'),
			"desc" => wp_kses_data( __("Insert price block with title, price and description", 'jacqueline') ),
			"decorate" => false,
			"container" => true,
			"params" => array(/*
				"style" => array(
					"title" => esc_html__("Block style", 'jacqueline'),
					"desc" => wp_kses_data( __("Select style for this price block", 'jacqueline') ),
					"value" => 1,
					"options" => jacqueline_get_list_styles(1, 3),
					"type" => "checklist"
				),*/
				"title" => array(
					"title" => esc_html__("Title", 'jacqueline'),
					"desc" => wp_kses_data( __("Block title", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"subtitle" => array(
					"title" => esc_html__("Subtitle", 'jacqueline'),
					"desc" => wp_kses_data( __("Subtitle for the block", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"link" => array(
					"title" => esc_html__("Link URL", 'jacqueline'),
					"desc" => wp_kses_data( __("URL for link from button (at bottom of the block)", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"link_text" => array(
					"title" => esc_html__("Link text", 'jacqueline'),
					"desc" => wp_kses_data( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"icon" => array(
					"title" => esc_html__("Icon",  'jacqueline'),
					"desc" => wp_kses_data( __('Select icon from Fontello icons set (placed before/instead price)',  'jacqueline') ),
					"value" => "",
					"type" => "icons",
					"options" => jacqueline_get_sc_param('icons')
				),
				"icon_color" => array(
					"title" => esc_html__("Icon color", 'jacqueline'),
					"value" => "",
					"dependency" => array(
						'style' => array('iconed')
					),
					"type" => "color"
				),
				"money" => array(
					"title" => esc_html__("Money", 'jacqueline'),
					"desc" => wp_kses_data( __("Money value (dot or comma separated)", 'jacqueline') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"currency" => array(
					"title" => esc_html__("Currency", 'jacqueline'),
					"desc" => wp_kses_data( __("Currency character", 'jacqueline') ),
					"value" => "$",
					"type" => "text"
				),
				"period" => array(
					"title" => esc_html__("Period", 'jacqueline'),
					"desc" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"featured" => array(
					"title" => esc_html__("Featured item", 'jacqueline'),
					"value" => "no",
					"type" => "select",
					"options" => jacqueline_get_sc_param('yes_no')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'jacqueline'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'jacqueline') ),
					"value" => "",
					"type" => "checklist",
					"options" => jacqueline_get_sc_param('schemes')
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'jacqueline'),
					"desc" => wp_kses_data( __("Align price to left or right side", 'jacqueline') ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => jacqueline_get_sc_param('float')
				), 
				"_content_" => array(
					"title" => esc_html__("Description", 'jacqueline'),
					"desc" => wp_kses_data( __("Description for this price block", 'jacqueline') ),
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
if ( !function_exists( 'jacqueline_sc_price_block_reg_shortcodes_vc' ) ) {
	//add_action('jacqueline_action_shortcodes_list_vc', 'jacqueline_sc_price_block_reg_shortcodes_vc');
	function jacqueline_sc_price_block_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_price_block",
			"name" => esc_html__("Price block", 'jacqueline'),
			"description" => wp_kses_data( __("Insert price block with title, price and description", 'jacqueline') ),
			"category" => esc_html__('Content', 'jacqueline'),
			'icon' => 'icon_trx_price_block',
			"class" => "trx_sc_single trx_sc_price_block",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(/*
				array(
					"param_name" => "style",
					"heading" => esc_html__("Block style", 'jacqueline'),
					"desc" => wp_kses_data( __("Select style of this price block", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"std" => 1,
					"value" => array_flip(jacqueline_get_list_styles(1, 3)),
					"type" => "dropdown"
				),*/
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'jacqueline'),
					"description" => wp_kses_data( __("Block title", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", 'jacqueline'),
					"description" => wp_kses_data( __("Subtitle for the block", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'jacqueline'),
					"description" => wp_kses_data( __("URL for link from button (at bottom of the block)", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_text",
					"heading" => esc_html__("Link text", 'jacqueline'),
					"description" => wp_kses_data( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'jacqueline'),
					"description" => wp_kses_data( __("Select icon from Fontello icons set (placed before/instead price)", 'jacqueline') ),
					"class" => "",
					"value" => jacqueline_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", 'jacqueline'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "featured",
					"heading" => esc_html__("Featured item", 'jacqueline'),
					"class" => "",
					"value" => array(esc_html__('Featured', 'jacqueline') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "money",
					"heading" => esc_html__("Money", 'jacqueline'),
					"description" => wp_kses_data( __("Money value (dot or comma separated)", 'jacqueline') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'jacqueline'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "currency",
					"heading" => esc_html__("Currency symbol", 'jacqueline'),
					"description" => wp_kses_data( __("Currency character", 'jacqueline') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'jacqueline'),
					"class" => "",
					"value" => "$",
					"type" => "textfield"
				),
				array(
					"param_name" => "period",
					"heading" => esc_html__("Period", 'jacqueline'),
					"description" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'jacqueline') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'jacqueline'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'jacqueline'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'jacqueline') ),
					"group" => esc_html__('Colors and Images', 'jacqueline'),
					"class" => "",
					"value" => array_flip(jacqueline_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'jacqueline'),
					"description" => wp_kses_data( __("Align price to left or right side", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(jacqueline_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Description", 'jacqueline'),
					"description" => wp_kses_data( __("Description for this price block", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
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
		
		class WPBakeryShortCode_Trx_PriceBlock extends JACQUELINE_VC_ShortCodeSingle {}
	}
}
?>