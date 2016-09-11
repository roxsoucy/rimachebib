<?php
/**
 * Skin file for the theme.
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('jacqueline_action_skin_theme_setup')) {
	add_action( 'jacqueline_action_init_theme', 'jacqueline_action_skin_theme_setup', 1 );
	function jacqueline_action_skin_theme_setup() {

		// Add skin fonts in the used fonts list
		add_filter('jacqueline_filter_used_fonts',			'jacqueline_filter_skin_used_fonts');
		// Add skin fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('jacqueline_filter_list_fonts',			'jacqueline_filter_skin_list_fonts');

		// Add skin stylesheets
		add_action('jacqueline_action_add_styles',			'jacqueline_action_skin_add_styles');
		// Add skin inline styles
		add_filter('jacqueline_filter_add_styles_inline',		'jacqueline_filter_skin_add_styles_inline');
		// Add skin responsive styles
		add_action('jacqueline_action_add_responsive',		'jacqueline_action_skin_add_responsive');
		// Add skin responsive inline styles
		add_filter('jacqueline_filter_add_responsive_inline',	'jacqueline_filter_skin_add_responsive_inline');

		// Add skin scripts
		add_action('jacqueline_action_add_scripts',			'jacqueline_action_skin_add_scripts');
		// Add skin scripts inline
		add_action('jacqueline_action_add_scripts_inline',	'jacqueline_action_skin_add_scripts_inline');

		// Add skin less files into list for compilation
		add_filter('jacqueline_filter_compile_less',			'jacqueline_filter_skin_compile_less');


		/* Color schemes
		
		// Accenterd colors
		accent1			- theme accented color 1
		accent1_hover	- theme accented color 1 (hover state)
		accent2			- theme accented color 2
		accent2_hover	- theme accented color 2 (hover state)		
		accent3			- theme accented color 3
		accent3_hover	- theme accented color 3 (hover state)		
		
		// Headers, text and links
		text			- main content
		text_light		- post info
		text_dark		- headers
		inverse_text	- text on accented background
		inverse_light	- post info on accented background
		inverse_dark	- headers on accented background
		inverse_link	- links on accented background
		inverse_hover	- hovered links on accented background
		
		// Block's border and background
		bd_color		- border for the entire block
		bg_color		- background color for the entire block
		bg_image, bg_image_position, bg_image_repeat, bg_image_attachment  - first background image for the entire block
		bg_image2,bg_image2_position,bg_image2_repeat,bg_image2_attachment - second background image for the entire block
		
		// Alternative colors - highlight blocks, form fields, etc.
		alter_text		- text on alternative background
		alter_light		- post info on alternative background
		alter_dark		- headers on alternative background
		alter_link		- links on alternative background
		alter_hover		- hovered links on alternative background
		alter_bd_color	- alternative border
		alter_bd_hover	- alternative border for hovered state or active field
		alter_bg_color	- alternative background
		alter_bg_hover	- alternative background for hovered state or active field 
		alter_bg_image, alter_bg_image_position, alter_bg_image_repeat, alter_bg_image_attachment - background image for the alternative block
		
		*/

		// Add color schemes
		jacqueline_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'jacqueline'),

			// Accent colors
			'accent1'				=> '#F9A392',
			'accent1_hover'			=> '#8ED4CC',
//			'accent2'				=> '#ff0000',
//			'accent2_hover'			=> '#aa0000',
//			'accent3'				=> '',
//			'accent3_hover'			=> '',
			
			// Headers, text and links colors
			'text'					=> '#757575',
			'text_light'			=> '#9A9A9A',
			'text_dark'				=> '#323232',
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#B2B2B2',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#8ED4CC',
			
			// Whole block border and background
			'bd_color'				=> '#F2F2F2',
			'bg_color'				=> '#ffffff',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#757575',
			'alter_light'			=> '#9A9A9A',
			'alter_dark'			=> '#232A34',
			'alter_link'			=> '#F9A392',
			'alter_hover'			=> '#8ED4CC',
			'alter_bd_color'		=> '#F4F4F4',
			'alter_bd_hover'		=> '#9A9A9A',
			'alter_bg_color'		=> '#F4F4F4',
			'alter_bg_hover'		=> '#F4F4F4',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);

		// Add color schemes
		jacqueline_add_color_scheme('light', array(

			'title'					=> esc_html__('Light', 'jacqueline'),

			// Accent colors
			'accent1'				=> '#20C7CA',
			'accent1_hover'			=> '#189799',
//			'accent2'				=> '#ff0000',
//			'accent2_hover'			=> '#aa0000',
//			'accent3'				=> '',
//			'accent3_hover'			=> '',
			
			// Headers, text and links colors
			'text'					=> '#ffffff',
			'text_light'			=> '#ffffff',
			'text_dark'				=> '#ffffff',
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
			
			// Whole block border and background
			'bd_color'				=> '#dddddd',
			'bg_color'				=> '#8ED4CC',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#8a8a8a',
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#232a34',
			'alter_link'			=> '#20c7ca',
			'alter_hover'			=> '#189799',
			'alter_bd_color'		=> '#e7e7e7',
			'alter_bd_hover'		=> '#dddddd',
			'alter_bg_color'		=> '#ffffff',
			'alter_bg_hover'		=> '#f0f0f0',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);

		// Add color schemes
		jacqueline_add_color_scheme('dark', array(

			'title'					=> esc_html__('Dark', 'jacqueline'),

			// Accent colors
			'accent1'				=> '#F9A392',
			'accent1_hover'			=> '#8ED4CC',
//			'accent2'				=> '#ff0000',
//			'accent2_hover'			=> '#aa0000',
//			'accent3'				=> '',
//			'accent3_hover'			=> '',
			
			// Headers, text and links colors
			'text'					=> '#757575',
			'text_light'			=> '#9A9A9A',
			'text_dark'				=> '#323232',
			'inverse_text'			=> '#FFFFFF',
			'inverse_light'			=> '#B2B2B2',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#8ED4CC',
			
			// Whole block border and background
			'bd_color'				=> 'rgba(242, 242, 242, 0.6)',
			'bg_color'				=> '#FFFFFF',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#757575',
			'alter_light'			=> '#9A9A9A',
			'alter_dark'			=> '#FFFFFF',
			'alter_link'			=> '#F9A392',
			'alter_hover'			=> '#8ED4CC',
			'alter_bd_color'		=> '#F4F4F4',
			'alter_bd_hover'		=> '#9A9A9A',
			'alter_bg_color'		=> '#F4F4F4',
			'alter_bg_hover'		=> '#F4F4F4',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);

		/* Font slugs:
		h1 ... h6	- headers
		p			- plain text
		link		- links
		info		- info blocks (Posted 15 May, 2015 by John Doe)
		menu		- main menu
		submenu		- dropdown menus
		logo		- logo text
		button		- button's caption
		input		- input fields
		*/

		// Add Custom fonts
		jacqueline_add_custom_font('h1', array(
			'title'			=> esc_html__('Heading 1', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '2.92em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0em',
			'margin-bottom'	=> '0.5em'
			)
		);
		jacqueline_add_custom_font('h2', array(
			'title'			=> esc_html__('Heading 2', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '2.153em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0em',
			'margin-bottom'	=> '0.5em'
			)
		);
		jacqueline_add_custom_font('h3', array(
			'title'			=> esc_html__('Heading 3', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '1.54em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0em',
			'margin-bottom'	=> '0.5em'
			)
		);
		jacqueline_add_custom_font('h4', array(
			'title'			=> esc_html__('Heading 4', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '1.385em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0em',
			'margin-bottom'	=> '0.5em'
			)
		);
		jacqueline_add_custom_font('h5', array(
			'title'			=> esc_html__('Heading 5', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '1.077em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0em',
			'margin-bottom'	=> '0.5em'
			)
		);
		jacqueline_add_custom_font('h6', array(
			'title'			=> esc_html__('Heading 6', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> 'Droid Serif',
			'font-size' 	=> '1.077em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0em',
			'margin-bottom'	=> '0.5em'
			)
		);
		jacqueline_add_custom_font('p', array(
			'title'			=> esc_html__('Text', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> 'Droid Serif',
			'font-size' 	=> '13px',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.92em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1em'
			)
		);
		jacqueline_add_custom_font('link', array(
			'title'			=> esc_html__('Links', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> ''
			)
		);
		jacqueline_add_custom_font('info', array(
			'title'			=> esc_html__('Post info', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1em',
			'font-weight'	=> '',
			'font-style'	=> 'i',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1.5em'
			)
		);
		jacqueline_add_custom_font('menu', array(
			'title'			=> esc_html__('Main menu items', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '1em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '2.3em',
			'margin-top'	=> '0em',
			'margin-bottom'	=> '1.6em'
			)
		);
		jacqueline_add_custom_font('submenu', array(
			'title'			=> esc_html__('Dropdown menu items', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '1em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '2.3em',
			'margin-top'	=> '',
			'margin-bottom'	=> ''
			)
		);
		jacqueline_add_custom_font('logo', array(
			'title'			=> esc_html__('Logo', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.8571em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '0.75em',
			'margin-top'	=> '2.5em',
			'margin-bottom'	=> '2em'
			)
		);
		jacqueline_add_custom_font('button', array(
			'title'			=> esc_html__('Buttons', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '11px',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '2em'
			)
		);
		jacqueline_add_custom_font('input', array(
			'title'			=> esc_html__('Input fields', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '13px',
			'font-weight'	=> '',
			'font-style'	=> 'i',
			'line-height'	=> '1.3em'
			)
		);

	}
}





//------------------------------------------------------------------------------
// Skin's fonts
//------------------------------------------------------------------------------

// Add skin fonts in the used fonts list
if (!function_exists('jacqueline_filter_skin_used_fonts')) {
	//add_filter('jacqueline_filter_used_fonts', 'jacqueline_filter_skin_used_fonts');
	function jacqueline_filter_skin_used_fonts($theme_fonts) {
		//$theme_fonts['Roboto'] = 1;
		//$theme_fonts['Love Ya Like A Sister'] = 1;
		return $theme_fonts;
	}
}

// Add skin fonts (from Google fonts) in the main fonts list (if not present).
// To use custom font-face you not need add it into list in this function
// How to install custom @font-face fonts into the theme?
// All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!
// Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.
// Create your @font-face kit by using Fontsquirrel @font-face Generator (http://www.fontsquirrel.com/fontface/generator)
// and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install
if (!function_exists('jacqueline_filter_skin_list_fonts')) {
	//add_filter('jacqueline_filter_list_fonts', 'jacqueline_filter_skin_list_fonts');
	function jacqueline_filter_skin_list_fonts($list) {
		// Example:
		// if (!isset($list['Advent Pro'])) {
		//		$list['Advent Pro'] = array(
		//			'family' => 'sans-serif',																						// (required) font family
		//			'link'   => 'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
		//			'css'    => jacqueline_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
		//			);
		// }
		if (!isset($list['Lato']))	$list['Lato'] = array('family'=>'sans-serif');
		return $list;
	}
}



//------------------------------------------------------------------------------
// Skin's stylesheets
//------------------------------------------------------------------------------
// Add skin stylesheets
if (!function_exists('jacqueline_action_skin_add_styles')) {
	//add_action('jacqueline_action_add_styles', 'jacqueline_action_skin_add_styles');
	function jacqueline_action_skin_add_styles() {
		// Add stylesheet files
		jacqueline_enqueue_style( 'jacqueline-skin-style', jacqueline_get_file_url('skin.css'), array(), null );
		if (file_exists(jacqueline_get_file_dir('skin.customizer.css')))
			jacqueline_enqueue_style( 'jacqueline-skin-customizer-style', jacqueline_get_file_url('skin.customizer.css'), array(), null );
	}
}

// Add skin inline styles
if (!function_exists('jacqueline_filter_skin_add_styles_inline')) {
	//add_filter('jacqueline_filter_add_styles_inline', 'jacqueline_filter_skin_add_styles_inline');
	function jacqueline_filter_skin_add_styles_inline($custom_style) {
		// Todo: add skin specific styles in the $custom_style to override
		//       rules from style.css and shortcodes.css
		// Example:
		//		$scheme = jacqueline_get_custom_option('body_scheme');
		//		if (empty($scheme)) $scheme = 'original';
		//		$clr = jacqueline_get_scheme_color('accent1');
		//		if (!empty($clr)) {
		// 			$custom_style .= '
		//				a,
		//				.bg_tint_light a,
		//				.top_panel .content .search_wrap.search_style_regular .search_form_wrap .search_submit,
		//				.top_panel .content .search_wrap.search_style_regular .search_icon,
		//				.search_results .post_more,
		//				.search_results .search_results_close {
		//					color:'.esc_attr($clr).';
		//				}
		//			';
		//		}
		return $custom_style;	
	}
}

// Add skin responsive styles
if (!function_exists('jacqueline_action_skin_add_responsive')) {
	//add_action('jacqueline_action_add_responsive', 'jacqueline_action_skin_add_responsive');
	function jacqueline_action_skin_add_responsive() {
		$suffix = jacqueline_param_is_off(jacqueline_get_custom_option('show_sidebar_outer')) ? '' : '-outer';
		if (file_exists(jacqueline_get_file_dir('skin.responsive'.($suffix).'.css'))) 
			jacqueline_enqueue_style( 'theme-skin-responsive-style', jacqueline_get_file_url('skin.responsive'.($suffix).'.css'), array(), null );
	}
}

// Add skin responsive inline styles
if (!function_exists('jacqueline_filter_skin_add_responsive_inline')) {
	//add_filter('jacqueline_filter_add_responsive_inline', 'jacqueline_filter_skin_add_responsive_inline');
	function jacqueline_filter_skin_add_responsive_inline($custom_style) {
		return $custom_style;	
	}
}

// Add skin.less into list files for compilation
if (!function_exists('jacqueline_filter_skin_compile_less')) {
	//add_filter('jacqueline_filter_compile_less', 'jacqueline_filter_skin_compile_less');
	function jacqueline_filter_skin_compile_less($files) {
		if (file_exists(jacqueline_get_file_dir('skin.less'))) {
		 	$files[] = jacqueline_get_file_dir('skin.less');
		}
		return $files;	
	}
}



//------------------------------------------------------------------------------
// Skin's scripts
//------------------------------------------------------------------------------

// Add skin scripts
if (!function_exists('jacqueline_action_skin_add_scripts')) {
	//add_action('jacqueline_action_add_scripts', 'jacqueline_action_skin_add_scripts');
	function jacqueline_action_skin_add_scripts() {
		if (file_exists(jacqueline_get_file_dir('skin.js')))
			jacqueline_enqueue_script( 'theme-skin-script', jacqueline_get_file_url('skin.js'), array(), null );
		if (jacqueline_get_theme_option('show_theme_customizer') == 'yes' && file_exists(jacqueline_get_file_dir('skin.customizer.js')))
			jacqueline_enqueue_script( 'theme-skin-customizer-script', jacqueline_get_file_url('skin.customizer.js'), array(), null );
	}
}

// Add skin scripts inline
if (!function_exists('jacqueline_action_skin_add_scripts_inline')) {
	//add_action('jacqueline_action_add_scripts_inline', 'jacqueline_action_skin_add_scripts_inline');
	function jacqueline_action_skin_add_scripts_inline() {
		// Todo: add skin specific scripts
		// Example:
		// echo '<script type="text/javascript">'
		//	. 'jQuery(document).ready(function() {'
		//	. "if (JACQUELINE_STORAGE['theme_font']=='') JACQUELINE_STORAGE['theme_font'] = '" . jacqueline_get_custom_font_settings('p', 'font-family') . "';"
		//	. "JACQUELINE_STORAGE['theme_skin_color'] = '" . jacqueline_get_scheme_color('accent1') . "';"
		//	. "});"
		//	. "< /script>";
	}
}
?>