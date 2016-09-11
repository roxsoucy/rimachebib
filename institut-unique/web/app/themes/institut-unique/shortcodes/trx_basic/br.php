<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jacqueline_sc_br_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_br_theme_setup' );
	function jacqueline_sc_br_theme_setup() {
		add_action('jacqueline_action_shortcodes_list', 		'jacqueline_sc_br_reg_shortcodes');
		if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
			add_action('jacqueline_action_shortcodes_list_vc','jacqueline_sc_br_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_br clear="left|right|both"]
*/

if (!function_exists('jacqueline_sc_br')) {	
	function jacqueline_sc_br($atts, $content = null) {
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			"clear" => ""
		), $atts)));
		$output = in_array($clear, array('left', 'right', 'both', 'all')) 
			? '<div class="clearfix" style="clear:' . str_replace('all', 'both', $clear) . '"></div>'
			: '<br />';
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_br', $atts, $content);
	}
	jacqueline_require_shortcode("trx_br", "jacqueline_sc_br");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_br_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_sc_br_reg_shortcodes');
	function jacqueline_sc_br_reg_shortcodes() {
	
		jacqueline_sc_map("trx_br", array(
			"title" => esc_html__("Break", 'jacqueline'),
			"desc" => wp_kses_data( __("Line break with clear floating (if need)", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"clear" => 	array(
					"title" => esc_html__("Clear floating", 'jacqueline'),
					"desc" => wp_kses_data( __("Clear floating (if need)", 'jacqueline') ),
					"value" => "",
					"type" => "checklist",
					"options" => array(
						'none' => esc_html__('None', 'jacqueline'),
						'left' => esc_html__('Left', 'jacqueline'),
						'right' => esc_html__('Right', 'jacqueline'),
						'both' => esc_html__('Both', 'jacqueline')
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_br_reg_shortcodes_vc' ) ) {
	//add_action('jacqueline_action_shortcodes_list_vc', 'jacqueline_sc_br_reg_shortcodes_vc');
	function jacqueline_sc_br_reg_shortcodes_vc() {
/*
		vc_map( array(
			"base" => "trx_br",
			"name" => esc_html__("Line break", 'jacqueline'),
			"description" => wp_kses_data( __("Line break or Clear Floating", 'jacqueline') ),
			"category" => esc_html__('Content', 'jacqueline'),
			'icon' => 'icon_trx_br',
			"class" => "trx_sc_single trx_sc_br",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "clear",
					"heading" => esc_html__("Clear floating", 'jacqueline'),
					"description" => wp_kses_data( __("Select clear side (if need)", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"value" => array(
						esc_html__('None', 'jacqueline') => 'none',
						esc_html__('Left', 'jacqueline') => 'left',
						esc_html__('Right', 'jacqueline') => 'right',
						esc_html__('Both', 'jacqueline') => 'both'
					),
					"type" => "dropdown"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Br extends JACQUELINE_VC_ShortCodeSingle {}
*/
	}
}
?>