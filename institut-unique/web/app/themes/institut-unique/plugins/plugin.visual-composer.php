<?php
/* Visual Composer support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jacqueline_vc_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_vc_theme_setup', 1 );
	function jacqueline_vc_theme_setup() {
		if (jacqueline_exists_visual_composer()) {
			if (is_admin()) {
				add_filter( 'jacqueline_filter_importer_options',				'jacqueline_vc_importer_set_options' );
			}
			add_action('jacqueline_action_add_styles',		 				'jacqueline_vc_frontend_scripts' );
		}
		if (is_admin()) {
			add_filter( 'jacqueline_filter_importer_required_plugins',		'jacqueline_vc_importer_required_plugins', 10, 2 );
			add_filter( 'jacqueline_filter_required_plugins',					'jacqueline_vc_required_plugins' );
		}
	}
}

// Check if Visual Composer installed and activated
if ( !function_exists( 'jacqueline_exists_visual_composer' ) ) {
	function jacqueline_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}

// Check if Visual Composer in frontend editor mode
if ( !function_exists( 'jacqueline_vc_is_frontend' ) ) {
	function jacqueline_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
		//return function_exists('vc_is_frontend_editor') && vc_is_frontend_editor();
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'jacqueline_vc_required_plugins' ) ) {
	//add_filter('jacqueline_filter_required_plugins',	'jacqueline_vc_required_plugins');
	function jacqueline_vc_required_plugins($list=array()) {
		if (in_array('visual_composer', jacqueline_storage_get('required_plugins'))) {
			$path = jacqueline_get_file_dir('plugins/install/js_composer.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> 'Visual Composer',
					'slug' 		=> 'js_composer',
					'source'	=> $path,
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Enqueue VC custom styles
if ( !function_exists( 'jacqueline_vc_frontend_scripts' ) ) {
	//add_action( 'jacqueline_action_add_styles', 'jacqueline_vc_frontend_scripts' );
	function jacqueline_vc_frontend_scripts() {
		if (file_exists(jacqueline_get_file_dir('css/plugin.visual-composer.css')))
			jacqueline_enqueue_style( 'jacqueline-plugin.visual-composer-style',  jacqueline_get_file_url('css/plugin.visual-composer.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check VC in the required plugins
if ( !function_exists( 'jacqueline_vc_importer_required_plugins' ) ) {
	//add_filter( 'jacqueline_filter_importer_required_plugins',	'jacqueline_vc_importer_required_plugins', 10, 2 );
	function jacqueline_vc_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('visual_composer', jacqueline_storage_get('required_plugins')) && !jacqueline_exists_visual_composer() && jacqueline_get_value_gp('data_type')=='vc' )
		if (!jacqueline_exists_visual_composer() )		// && jacqueline_strpos($list, 'visual_composer')!==false
			$not_installed .= '<br>Visual Composer';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'jacqueline_vc_importer_set_options' ) ) {
	//add_filter( 'jacqueline_filter_importer_options',	'jacqueline_vc_importer_set_options' );
	function jacqueline_vc_importer_set_options($options=array()) {
		if ( in_array('visual_composer', jacqueline_storage_get('required_plugins')) && jacqueline_exists_visual_composer() ) {
			// Add slugs to export options for this plugin
			$options['additional_options'][] = 'wpb_js_templates';
		}
		return $options;
	}
}
?>