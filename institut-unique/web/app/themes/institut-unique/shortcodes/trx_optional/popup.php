<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jacqueline_sc_popup_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_popup_theme_setup' );
	function jacqueline_sc_popup_theme_setup() {
		add_action('jacqueline_action_shortcodes_list', 		'jacqueline_sc_popup_reg_shortcodes');
		if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
			add_action('jacqueline_action_shortcodes_list_vc','jacqueline_sc_popup_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_popup id="unique_id" class="class_name" style="css_styles"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_popup]
*/

if (!function_exists('jacqueline_sc_popup')) {	
	function jacqueline_sc_popup($atts, $content=null){	
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
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
		jacqueline_enqueue_popup('magnific');
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_popup mfp-with-anim mfp-hide' . ($class ? ' '.esc_attr($class) : '') . '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</div>';
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_popup', $atts, $content);
	}
	jacqueline_require_shortcode('trx_popup', 'jacqueline_sc_popup');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_popup_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_sc_popup_reg_shortcodes');
	function jacqueline_sc_popup_reg_shortcodes() {
	
		jacqueline_sc_map("trx_popup", array(
			"title" => esc_html__("Popup window", 'jacqueline'),
			"desc" => wp_kses_data( __("Container for any html-block with desired class and style for popup window", 'jacqueline') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
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
if ( !function_exists( 'jacqueline_sc_popup_reg_shortcodes_vc' ) ) {
	//add_action('jacqueline_action_shortcodes_list_vc', 'jacqueline_sc_popup_reg_shortcodes_vc');
	function jacqueline_sc_popup_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_popup",
			"name" => esc_html__("Popup window", 'jacqueline'),
			"description" => wp_kses_data( __("Container for any html-block with desired class and style for popup window", 'jacqueline') ),
			"category" => esc_html__('Content', 'jacqueline'),
			'icon' => 'icon_trx_popup',
			"class" => "trx_sc_collection trx_sc_popup",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				jacqueline_get_vc_param('id'),
				jacqueline_get_vc_param('class'),
				jacqueline_get_vc_param('css'),
				jacqueline_get_vc_param('margin_top'),
				jacqueline_get_vc_param('margin_bottom'),
				jacqueline_get_vc_param('margin_left'),
				jacqueline_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Popup extends JACQUELINE_VC_ShortCodeCollection {}
	}
}
?>