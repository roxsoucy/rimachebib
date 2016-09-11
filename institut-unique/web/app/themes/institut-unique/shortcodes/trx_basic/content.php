<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jacqueline_sc_content_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_content_theme_setup' );
	function jacqueline_sc_content_theme_setup() {
		add_action('jacqueline_action_shortcodes_list', 		'jacqueline_sc_content_reg_shortcodes');
		if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
			add_action('jacqueline_action_shortcodes_list_vc','jacqueline_sc_content_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_content id="unique_id" class="class_name" style="css-styles"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_content]
*/

if (!function_exists('jacqueline_sc_content')) {	
	function jacqueline_sc_content($atts, $content=null){	
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . jacqueline_get_css_position_as_classes($top, '', $bottom);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_content content_wrap' 
				. ($scheme && !jacqueline_param_is_off($scheme) && !jacqueline_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
				. ($class ? ' '.esc_attr($class) : '') 
				. '"'
			. (!jacqueline_param_is_off($animation) ? ' data-animation="'.esc_attr(jacqueline_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '').'>' 
			. do_shortcode($content) 
			. '</div>';
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_content', $atts, $content);
	}
	jacqueline_require_shortcode('trx_content', 'jacqueline_sc_content');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_content_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_sc_content_reg_shortcodes');
	function jacqueline_sc_content_reg_shortcodes() {
	
		jacqueline_sc_map("trx_content", array(
			"title" => esc_html__("Content block", 'jacqueline'),
			"desc" => wp_kses_data( __("Container for main content block with desired class and style (use it only on fullscreen pages)", 'jacqueline') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'jacqueline'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'jacqueline') ),
					"value" => "",
					"type" => "checklist",
					"options" => jacqueline_get_sc_param('schemes')
				),
				"_content_" => array(
					"title" => esc_html__("Container content", 'jacqueline'),
					"desc" => wp_kses_data( __("Content for section container", 'jacqueline') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"top" => jacqueline_get_sc_param('top'),
				"bottom" => jacqueline_get_sc_param('bottom'),
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
if ( !function_exists( 'jacqueline_sc_content_reg_shortcodes_vc' ) ) {
	//add_action('jacqueline_action_shortcodes_list_vc', 'jacqueline_sc_content_reg_shortcodes_vc');
	function jacqueline_sc_content_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_content",
			"name" => esc_html__("Content block", 'jacqueline'),
			"description" => wp_kses_data( __("Container for main content block (use it only on fullscreen pages)", 'jacqueline') ),
			"category" => esc_html__('Content', 'jacqueline'),
			'icon' => 'icon_trx_content',
			"class" => "trx_sc_collection trx_sc_content",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'jacqueline'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'jacqueline') ),
					"group" => esc_html__('Colors and Images', 'jacqueline'),
					"class" => "",
					"value" => array_flip(jacqueline_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				jacqueline_get_vc_param('id'),
				jacqueline_get_vc_param('class'),
				jacqueline_get_vc_param('animation'),
				jacqueline_get_vc_param('css'),
				jacqueline_get_vc_param('margin_top'),
				jacqueline_get_vc_param('margin_bottom')
			)
		) );
		
		class WPBakeryShortCode_Trx_Content extends JACQUELINE_VC_ShortCodeCollection {}
	}
}
?>