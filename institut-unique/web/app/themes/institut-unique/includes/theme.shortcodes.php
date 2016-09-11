<?php
if (!function_exists('jacqueline_theme_shortcodes_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_theme_shortcodes_setup', 1 );
	function jacqueline_theme_shortcodes_setup() {
		add_filter('jacqueline_filter_googlemap_styles', 'jacqueline_theme_shortcodes_googlemap_styles');
	}
}


// Add theme-specific Google map styles
if ( !function_exists( 'jacqueline_theme_shortcodes_googlemap_styles' ) ) {
	function jacqueline_theme_shortcodes_googlemap_styles($list) {
		$list['simple']		= esc_html__('Simple', 'jacqueline');
		$list['greyscale']	= esc_html__('Greyscale', 'jacqueline');
		$list['inverse']	= esc_html__('Inverse', 'jacqueline');
		return $list;
	}
}
?>