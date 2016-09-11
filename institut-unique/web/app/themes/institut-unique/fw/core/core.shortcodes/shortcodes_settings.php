<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'jacqueline_shortcodes_is_used' ) ) {
	function jacqueline_shortcodes_is_used() {
		return jacqueline_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
			|| (is_admin() && jacqueline_strpos($_SERVER['REQUEST_URI'], 'vc-roles')!==false)			// VC Role Manager
			|| (function_exists('jacqueline_vc_is_frontend') && jacqueline_vc_is_frontend());			// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'jacqueline_shortcodes_width' ) ) {
	function jacqueline_shortcodes_width($w="") {
		return array(
			"title" => esc_html__("Width", 'jacqueline'),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'jacqueline_shortcodes_height' ) ) {
	function jacqueline_shortcodes_height($h='') {
		return array(
			"title" => esc_html__("Height", 'jacqueline'),
			"desc" => wp_kses_data( __("Width and height of the element", 'jacqueline') ),
			"value" => $h,
			"type" => "text"
		);
	}
}

// Return sc_param value
if ( !function_exists( 'jacqueline_get_sc_param' ) ) {
	function jacqueline_get_sc_param($prm) {
		return jacqueline_storage_get_array('sc_params', $prm);
	}
}

// Set sc_param value
if ( !function_exists( 'jacqueline_set_sc_param' ) ) {
	function jacqueline_set_sc_param($prm, $val) {
		jacqueline_storage_set_array('sc_params', $prm, $val);
	}
}

// Add sc settings in the sc list
if ( !function_exists( 'jacqueline_sc_map' ) ) {
	function jacqueline_sc_map($sc_name, $sc_settings) {
		jacqueline_storage_set_array('shortcodes', $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list after the key
if ( !function_exists( 'jacqueline_sc_map_after' ) ) {
	function jacqueline_sc_map_after($after, $sc_name, $sc_settings='') {
		jacqueline_storage_set_array_after('shortcodes', $after, $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list before the key
if ( !function_exists( 'jacqueline_sc_map_before' ) ) {
	function jacqueline_sc_map_before($before, $sc_name, $sc_settings='') {
		jacqueline_storage_set_array_before('shortcodes', $before, $sc_name, $sc_settings);
	}
}

// Compare two shortcodes by title
if ( !function_exists( 'jacqueline_compare_sc_title' ) ) {
	function jacqueline_compare_sc_title($a, $b) {
		return strcmp($a['title'], $b['title']);
	}
}



/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'jacqueline_shortcodes_settings_theme_setup' ) ) {
//	if ( jacqueline_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'jacqueline_action_before_init_theme', 'jacqueline_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'jacqueline_action_after_init_theme', 'jacqueline_shortcodes_settings_theme_setup' );
	function jacqueline_shortcodes_settings_theme_setup() {
		if (jacqueline_shortcodes_is_used()) {

			// Sort templates alphabetically
			$tmp = jacqueline_storage_get('registered_templates');
			ksort($tmp);
			jacqueline_storage_set('registered_templates', $tmp);

			// Prepare arrays 
			jacqueline_storage_set('sc_params', array(
			
				// Current element id
				'id' => array(
					"title" => esc_html__("Element ID", 'jacqueline'),
					"desc" => wp_kses_data( __("ID for current element", 'jacqueline') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => esc_html__("Element CSS class", 'jacqueline'),
					"desc" => wp_kses_data( __("CSS class for current element (optional)", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => esc_html__("CSS styles", 'jacqueline'),
					"desc" => wp_kses_data( __("Any additional CSS rules (if need)", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
			
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> esc_html__('Unordered', 'jacqueline'),
					'ol'	=> esc_html__('Ordered', 'jacqueline'),
					'iconed'=> esc_html__('Iconed', 'jacqueline')
				),

				'yes_no'	=> jacqueline_get_list_yesno(),
				'on_off'	=> jacqueline_get_list_onoff(),
				'dir' 		=> jacqueline_get_list_directions(),
				'align'		=> jacqueline_get_list_alignments(),
				'float'		=> jacqueline_get_list_floats(),
				'hpos'		=> jacqueline_get_list_hpos(),
				'show_hide'	=> jacqueline_get_list_showhide(),
				'sorting' 	=> jacqueline_get_list_sortings(),
				'ordering' 	=> jacqueline_get_list_orderings(),
				'shapes'	=> jacqueline_get_list_shapes(),
				'sizes'		=> jacqueline_get_list_sizes(),
				'sliders'	=> jacqueline_get_list_sliders(),
				'controls'	=> jacqueline_get_list_controls(),
				'categories'=> jacqueline_get_list_categories(),
				'columns'	=> jacqueline_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), jacqueline_get_list_files("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), jacqueline_get_list_icons()),
				'locations'	=> jacqueline_get_list_dedicated_locations(),
				'filters'	=> jacqueline_get_list_portfolio_filters(),
				'formats'	=> jacqueline_get_list_post_formats_filters(),
				'hovers'	=> jacqueline_get_list_hovers(true),
				'hovers_dir'=> jacqueline_get_list_hovers_directions(true),
				'schemes'	=> jacqueline_get_list_color_schemes(true),
				'animations'		=> jacqueline_get_list_animations_in(),
				'margins' 			=> jacqueline_get_list_margins(true),
				'blogger_styles'	=> jacqueline_get_list_templates_blogger(),
				'forms'				=> jacqueline_get_list_templates_forms(),
				'posts_types'		=> jacqueline_get_list_posts_types(),
				'googlemap_styles'	=> jacqueline_get_list_googlemap_styles(),
				'field_types'		=> jacqueline_get_list_field_types(),
				'label_positions'	=> jacqueline_get_list_label_positions()
				)
			);

			// Common params
			jacqueline_set_sc_param('animation', array(
				"title" => esc_html__("Animation",  'jacqueline'),
				"desc" => wp_kses_data( __('Select animation while object enter in the visible area of page',  'jacqueline') ),
				"value" => "none",
				"type" => "select",
				"options" => jacqueline_get_sc_param('animations')
				)
			);
			jacqueline_set_sc_param('top', array(
				"title" => esc_html__("Top margin",  'jacqueline'),
				"divider" => true,
				"value" => "",
				"type" => "text"
			//	"options" => jacqueline_get_sc_param('margins')
				)
			);
			jacqueline_set_sc_param('bottom', array(
				"title" => esc_html__("Bottom margin",  'jacqueline'),
				"value" => "",
				"type" => "text"
			//	"options" => jacqueline_get_sc_param('margins')
				)
			);
			jacqueline_set_sc_param('left', array(
				"title" => esc_html__("Left margin",  'jacqueline'),
				"value" => "",
				"type" => "text"
			//	"options" => jacqueline_get_sc_param('margins')
				)
			);
			jacqueline_set_sc_param('right', array(
				"title" => esc_html__("Right margin",  'jacqueline'),
				"desc" => wp_kses_data( __("Margins around this shortcode", 'jacqueline') ),
				"value" => "",
				"type" => "text"
				//"options" => jacqueline_get_sc_param('margins')
				)
			);

			jacqueline_storage_set('sc_params', apply_filters('jacqueline_filter_shortcodes_params', jacqueline_storage_get('sc_params')));

			// Shortcodes list
			//------------------------------------------------------------------
			jacqueline_storage_set('shortcodes', array());
			
			// Register shortcodes
			do_action('jacqueline_action_shortcodes_list');

			// Sort shortcodes list
			$tmp = jacqueline_storage_get('shortcodes');
			uasort($tmp, 'jacqueline_compare_sc_title');
			jacqueline_storage_set('shortcodes', $tmp);
		}
	}
}
?>