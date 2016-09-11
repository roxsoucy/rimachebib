<?php
/* Instagram Widget support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jacqueline_instagram_widget_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_instagram_widget_theme_setup', 1 );
	function jacqueline_instagram_widget_theme_setup() {
		if (jacqueline_exists_instagram_widget()) {
			add_action( 'jacqueline_action_add_styles', 						'jacqueline_instagram_widget_frontend_scripts' );
		}
		if (is_admin()) {
			add_filter( 'jacqueline_filter_importer_required_plugins',		'jacqueline_instagram_widget_importer_required_plugins', 10, 2 );
			add_filter( 'jacqueline_filter_required_plugins',					'jacqueline_instagram_widget_required_plugins' );
		}
	}
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'jacqueline_exists_instagram_widget' ) ) {
	function jacqueline_exists_instagram_widget() {
		return function_exists('wpiw_init');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'jacqueline_instagram_widget_required_plugins' ) ) {
	//add_filter('jacqueline_filter_required_plugins',	'jacqueline_instagram_widget_required_plugins');
	function jacqueline_instagram_widget_required_plugins($list=array()) {
		if (in_array('instagram_widget', jacqueline_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> 'Instagram Widget',
					'slug' 		=> 'wp-instagram-widget',
					'required' 	=> false
				);
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'jacqueline_instagram_widget_frontend_scripts' ) ) {
	//add_action( 'jacqueline_action_add_styles', 'jacqueline_instagram_widget_frontend_scripts' );
	function jacqueline_instagram_widget_frontend_scripts() {
		if (file_exists(jacqueline_get_file_dir('css/plugin.instagram-widget.css')))
			jacqueline_enqueue_style( 'jacqueline-plugin.instagram-widget-style',  jacqueline_get_file_url('css/plugin.instagram-widget.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Instagram Widget in the required plugins
if ( !function_exists( 'jacqueline_instagram_widget_importer_required_plugins' ) ) {
	//add_filter( 'jacqueline_filter_importer_required_plugins',	'jacqueline_instagram_widget_importer_required_plugins', 10, 2 );
	function jacqueline_instagram_widget_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('instagram_widget', jacqueline_storage_get('required_plugins')) && !jacqueline_exists_instagram_widget() )
		if (jacqueline_strpos($list, 'instagram_widget')!==false && !jacqueline_exists_instagram_widget() )
			$not_installed .= '<br>WP Instagram Widget';
		return $not_installed;
	}
}
?>