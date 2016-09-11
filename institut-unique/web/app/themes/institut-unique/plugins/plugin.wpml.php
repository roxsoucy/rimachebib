<?php
/* WPML support functions
------------------------------------------------------------------------------- */

// Check if WPML installed and activated
if ( !function_exists( 'jacqueline_exists_wpml' ) ) {
	function jacqueline_exists_wpml() {
		return defined('ICL_SITEPRESS_VERSION') && class_exists('sitepress');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'jacqueline_wpml_required_plugins' ) ) {
	//add_filter('jacqueline_filter_required_plugins',	'jacqueline_wpml_required_plugins');
	function jacqueline_wpml_required_plugins($list=array()) {
		if (in_array('wpml', jacqueline_storage_get('required_plugins'))) {
			$path = jacqueline_get_file_dir('plugins/install/wpml.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> 'WPML',
					'slug' 		=> 'wpml',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}
?>