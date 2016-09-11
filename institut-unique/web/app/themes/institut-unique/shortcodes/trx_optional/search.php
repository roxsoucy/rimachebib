<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jacqueline_sc_search_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_search_theme_setup' );
	function jacqueline_sc_search_theme_setup() {
		add_action('jacqueline_action_shortcodes_list', 		'jacqueline_sc_search_reg_shortcodes');
		if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
			add_action('jacqueline_action_shortcodes_list_vc','jacqueline_sc_search_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_search id="unique_id" open="yes|no"]
*/

if (!function_exists('jacqueline_sc_search')) {	
	function jacqueline_sc_search($atts, $content=null){	
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"state" => "fixed",
			"scheme" => "original",
			"ajax" => "",
			"title" => esc_html__('Search', 'jacqueline'),
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
		if (empty($ajax)) $ajax = jacqueline_get_theme_option('use_ajax_search');
		// Load core messages
		jacqueline_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="search_wrap search_style_'.esc_attr($style).' search_state_'.esc_attr($state)
						. (jacqueline_param_is_on($ajax) ? ' search_ajax' : '')
						. ($class ? ' '.esc_attr($class) : '')
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!jacqueline_param_is_off($animation) ? ' data-animation="'.esc_attr(jacqueline_get_animation_classes($animation)).'"' : '')
					. '>
						<div class="search_form_wrap">
							<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
								<button type="submit" class="search_submit icon-search-1" title="' . ($state=='closed' ? esc_attr__('Open search', 'jacqueline') : esc_attr__('Start search', 'jacqueline')) . '"></button>
								<input type="text" class="search_field" placeholder="' . esc_attr($title) . '" value="' . esc_attr(get_search_query()) . '" name="s" />
							</form>
						</div>
						<div class="search_results widget_area' . ($scheme && !jacqueline_param_is_off($scheme) && !jacqueline_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') . '"><a class="search_results_close icon-cancel"></a><div class="search_results_content"></div></div>
				</div>';
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_search', $atts, $content);
	}
	jacqueline_require_shortcode('trx_search', 'jacqueline_sc_search');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_search_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_sc_search_reg_shortcodes');
	function jacqueline_sc_search_reg_shortcodes() {
	
		jacqueline_sc_map("trx_search", array(
			"title" => esc_html__("Search", 'jacqueline'),
			"desc" => wp_kses_data( __("Show search form", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'jacqueline'),
					"desc" => wp_kses_data( __("Select style to display search field", 'jacqueline') ),
					"value" => "regular",
					"options" => array(
						"regular" => esc_html__('Regular', 'jacqueline'),
						"rounded" => esc_html__('Rounded', 'jacqueline')
					),
					"type" => "checklist"
				),
				"state" => array(
					"title" => esc_html__("State", 'jacqueline'),
					"desc" => wp_kses_data( __("Select search field initial state", 'jacqueline') ),
					"value" => "fixed",
					"options" => array(
						"fixed"  => esc_html__('Fixed',  'jacqueline'),
						"opened" => esc_html__('Opened', 'jacqueline'),
						"closed" => esc_html__('Closed', 'jacqueline')
					),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", 'jacqueline'),
					"desc" => wp_kses_data( __("Title (placeholder) for the search field", 'jacqueline') ),
					"value" => esc_html__("Search &hellip;", 'jacqueline'),
					"type" => "text"
				),
				"ajax" => array(
					"title" => esc_html__("AJAX", 'jacqueline'),
					"desc" => wp_kses_data( __("Search via AJAX or reload page", 'jacqueline') ),
					"value" => "yes",
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
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_search_reg_shortcodes_vc' ) ) {
	//add_action('jacqueline_action_shortcodes_list_vc', 'jacqueline_sc_search_reg_shortcodes_vc');
	function jacqueline_sc_search_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_search",
			"name" => esc_html__("Search form", 'jacqueline'),
			"description" => wp_kses_data( __("Insert search form", 'jacqueline') ),
			"category" => esc_html__('Content', 'jacqueline'),
			'icon' => 'icon_trx_search',
			"class" => "trx_sc_single trx_sc_search",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'jacqueline'),
					"description" => wp_kses_data( __("Select style to display search field", 'jacqueline') ),
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'jacqueline') => "regular",
						esc_html__('Flat', 'jacqueline') => "flat"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "state",
					"heading" => esc_html__("State", 'jacqueline'),
					"description" => wp_kses_data( __("Select search field initial state", 'jacqueline') ),
					"class" => "",
					"value" => array(
						esc_html__('Fixed', 'jacqueline')  => "fixed",
						esc_html__('Opened', 'jacqueline') => "opened",
						esc_html__('Closed', 'jacqueline') => "closed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'jacqueline'),
					"description" => wp_kses_data( __("Title (placeholder) for the search field", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => esc_html__("Search &hellip;", 'jacqueline'),
					"type" => "textfield"
				),
				array(
					"param_name" => "ajax",
					"heading" => esc_html__("AJAX", 'jacqueline'),
					"description" => wp_kses_data( __("Search via AJAX or reload page", 'jacqueline') ),
					"class" => "",
					"value" => array(esc_html__('Use AJAX search', 'jacqueline') => 'yes'),
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Search extends JACQUELINE_VC_ShortCodeSingle {}
	}
}
?>