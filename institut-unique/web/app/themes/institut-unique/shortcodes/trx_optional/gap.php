<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jacqueline_sc_gap_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_gap_theme_setup' );
	function jacqueline_sc_gap_theme_setup() {
		add_action('jacqueline_action_shortcodes_list', 		'jacqueline_sc_gap_reg_shortcodes');
		if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
			add_action('jacqueline_action_shortcodes_list_vc','jacqueline_sc_gap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_gap]Fullwidth content[/trx_gap]

if (!function_exists('jacqueline_sc_gap')) {	
	function jacqueline_sc_gap($atts, $content = null) {
		if (jacqueline_in_shortcode_blogger()) return '';
		$output = jacqueline_gap_start() . do_shortcode($content) . jacqueline_gap_end();
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_gap', $atts, $content);
	}
	jacqueline_require_shortcode("trx_gap", "jacqueline_sc_gap");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_gap_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_sc_gap_reg_shortcodes');
	function jacqueline_sc_gap_reg_shortcodes() {
	
		jacqueline_sc_map("trx_gap", array(
			"title" => esc_html__("Gap", 'jacqueline'),
			"desc" => wp_kses_data( __("Insert gap (fullwidth area) in the post content. Attention! Use the gap only in the posts (pages) without left or right sidebar", 'jacqueline') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Gap content", 'jacqueline'),
					"desc" => wp_kses_data( __("Gap inner content", 'jacqueline') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_gap_reg_shortcodes_vc' ) ) {
	//add_action('jacqueline_action_shortcodes_list_vc', 'jacqueline_sc_gap_reg_shortcodes_vc');
	function jacqueline_sc_gap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_gap",
			"name" => esc_html__("Gap", 'jacqueline'),
			"description" => wp_kses_data( __("Insert gap (fullwidth area) in the post content", 'jacqueline') ),
			"category" => esc_html__('Structure', 'jacqueline'),
			'icon' => 'icon_trx_gap',
			"class" => "trx_sc_collection trx_sc_gap",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"params" => array(
			)
		) );
		
		class WPBakeryShortCode_Trx_Gap extends JACQUELINE_VC_ShortCodeCollection {}
	}
}
?>