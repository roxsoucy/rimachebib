<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jacqueline_sc_hide_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_hide_theme_setup' );
	function jacqueline_sc_hide_theme_setup() {
		add_action('jacqueline_action_shortcodes_list', 		'jacqueline_sc_hide_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */


if (!function_exists('jacqueline_sc_hide')) {	
	function jacqueline_sc_hide($atts, $content=null){	
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			// Individual params
			"selector" => "",
			"hide" => "on",
			"delay" => 0
		), $atts)));
		$selector = trim(chop($selector));
		$output = $selector == '' ? '' : 
			'<script type="text/javascript">
				jQuery(document).ready(function() {
					'.($delay>0 ? 'setTimeout(function() {' : '').'
					jQuery("'.esc_attr($selector).'").' . ($hide=='on' ? 'hide' : 'show') . '();
					'.($delay>0 ? '},'.($delay).');' : '').'
				});
			</script>';
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_hide', $atts, $content);
	}
	jacqueline_require_shortcode('trx_hide', 'jacqueline_sc_hide');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_hide_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_sc_hide_reg_shortcodes');
	function jacqueline_sc_hide_reg_shortcodes() {
	
		jacqueline_sc_map("trx_hide", array(
			"title" => esc_html__("Hide/Show any block", 'jacqueline'),
			"desc" => wp_kses_data( __("Hide or Show any block with desired CSS-selector", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"selector" => array(
					"title" => esc_html__("Selector", 'jacqueline'),
					"desc" => wp_kses_data( __("Any block's CSS-selector", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"hide" => array(
					"title" => esc_html__("Hide or Show", 'jacqueline'),
					"desc" => wp_kses_data( __("New state for the block: hide or show", 'jacqueline') ),
					"value" => "yes",
					"size" => "small",
					"options" => jacqueline_get_sc_param('yes_no'),
					"type" => "switch"
				)
			)
		));
	}
}
?>