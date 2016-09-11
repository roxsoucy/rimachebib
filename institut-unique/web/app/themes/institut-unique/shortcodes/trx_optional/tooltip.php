<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jacqueline_sc_tooltip_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_tooltip_theme_setup' );
	function jacqueline_sc_tooltip_theme_setup() {
		add_action('jacqueline_action_shortcodes_list', 		'jacqueline_sc_tooltip_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_tooltip id="unique_id" title="Tooltip text here"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/tooltip]
*/

if (!function_exists('jacqueline_sc_tooltip')) {	
	function jacqueline_sc_tooltip($atts, $content=null){	
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_tooltip_parent'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
						. do_shortcode($content)
						. '<span class="sc_tooltip">' . ($title) . '</span>'
					. '</span>';
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_tooltip', $atts, $content);
	}
	jacqueline_require_shortcode('trx_tooltip', 'jacqueline_sc_tooltip');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_tooltip_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_sc_tooltip_reg_shortcodes');
	function jacqueline_sc_tooltip_reg_shortcodes() {
	
		jacqueline_sc_map("trx_tooltip", array(
			"title" => esc_html__("Tooltip", 'jacqueline'),
			"desc" => wp_kses_data( __("Create tooltip for selected text", 'jacqueline') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'jacqueline'),
					"desc" => wp_kses_data( __("Tooltip title (required)", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Tipped content", 'jacqueline'),
					"desc" => wp_kses_data( __("Highlighted content with tooltip", 'jacqueline') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"id" => jacqueline_get_sc_param('id'),
				"class" => jacqueline_get_sc_param('class'),
				"css" => jacqueline_get_sc_param('css')
			)
		));
	}
}
?>