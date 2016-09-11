<?php
/**
 * Theme sprecific functions and definitions
 */

/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'jacqueline_theme_setup' ) ) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_theme_setup', 1 );
	function jacqueline_theme_setup() {

		// Register theme menus
		add_filter( 'jacqueline_filter_add_theme_menus',		'jacqueline_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'jacqueline_filter_add_theme_sidebars',	'jacqueline_add_theme_sidebars' );

		// Set options for importer
		add_filter( 'jacqueline_filter_importer_options',		'jacqueline_set_importer_options' );

		// Add theme required plugins
		add_filter( 'jacqueline_filter_required_plugins',		'jacqueline_add_required_plugins' );

		// Add theme specified classes into the body
		add_filter( 'body_class', 'jacqueline_body_classes' );

		// Set list of the theme required plugins
		jacqueline_storage_set('required_plugins', array(
			'booked',
			'essgrids',
			'instagram_widget',
			'revslider',
			'tribe_events',
			'trx_utils',
			'visual_composer',
			'woocommerce',
			)
		);
		
	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'jacqueline_add_theme_menus' ) ) {
	//add_filter( 'jacqueline_filter_add_theme_menus', 'jacqueline_add_theme_menus' );
	function jacqueline_add_theme_menus($menus) {
		//For example:
		//$menus['menu_footer'] = esc_html__('Footer Menu', 'jacqueline');
		//if (isset($menus['menu_panel'])) unset($menus['menu_panel']);
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'jacqueline_add_theme_sidebars' ) ) {
	//add_filter( 'jacqueline_filter_add_theme_sidebars',	'jacqueline_add_theme_sidebars' );
	function jacqueline_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'jacqueline' ),
				'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'jacqueline' )
			);
			if (function_exists('jacqueline_exists_woocommerce') && jacqueline_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = esc_html__( 'WooCommerce Cart Sidebar', 'jacqueline' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}


// Add theme required plugins
if ( !function_exists( 'jacqueline_add_required_plugins' ) ) {
	//add_filter( 'jacqueline_filter_required_plugins',		'jacqueline_add_required_plugins' );
	function jacqueline_add_required_plugins($plugins) {
		$plugins[] = array(
			'name' 		=> 'Jacqueline Utilities',
			'version'	=> '2.7',					// Minimal required version
			'slug' 		=> 'trx_utils',
			'source'	=> jacqueline_get_file_dir('plugins/install/trx_utils.zip'),
			'force_activation'   => false,			// If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => true,			// If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'required' 	=> true
		);
		return $plugins;
	}
}


// Add theme specified classes into the body
if ( !function_exists('jacqueline_body_classes') ) {
	//add_filter( 'body_class', 'jacqueline_body_classes' );
	function jacqueline_body_classes( $classes ) {

		$classes[] = 'jacqueline_body';
		$classes[] = 'body_style_' . trim(jacqueline_get_custom_option('body_style'));
		$classes[] = 'body_' . (jacqueline_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent');
		$classes[] = 'theme_skin_' . trim(jacqueline_get_custom_option('theme_skin'));
		$classes[] = 'article_style_' . trim(jacqueline_get_custom_option('article_style'));
		
		$blog_style = jacqueline_get_custom_option(is_singular() && !jacqueline_storage_get('blog_streampage') ? 'single_style' : 'blog_style');
		$classes[] = 'layout_' . trim($blog_style);
		$classes[] = 'template_' . trim(jacqueline_get_template_name($blog_style));
		
		$body_scheme = jacqueline_get_custom_option('body_scheme');
		if (empty($body_scheme)  || jacqueline_is_inherit_option($body_scheme)) $body_scheme = 'original';
		$classes[] = 'scheme_' . $body_scheme;

		$top_panel_position = jacqueline_get_custom_option('top_panel_position');
		if (!jacqueline_param_is_off($top_panel_position)) {
			$classes[] = 'top_panel_show';
			$classes[] = 'top_panel_' . trim($top_panel_position);
		} else 
			$classes[] = 'top_panel_hide';
		$classes[] = jacqueline_get_sidebar_class();

		if (jacqueline_get_custom_option('show_video_bg')=='yes' && (jacqueline_get_custom_option('video_bg_youtube_code')!='' || jacqueline_get_custom_option('video_bg_url')!=''))
			$classes[] = 'video_bg_show';

		if (jacqueline_get_theme_option('page_preloader')!='')
			$classes[] = 'preloader';

		return $classes;
	}
}


// Set theme specific importer options
if ( !function_exists( 'jacqueline_set_importer_options' ) ) {
	//add_filter( 'jacqueline_filter_importer_options',	'jacqueline_set_importer_options' );
	function jacqueline_set_importer_options($options=array()) {
		if (is_array($options)) {
			$options['debug'] = jacqueline_get_theme_option('debug_mode')=='yes';
			$options['menus'] = array(
				'menu-main'	  => esc_html__('Main menu', 'jacqueline'),
				'menu-user'	  => esc_html__('User menu', 'jacqueline'),
				'menu-footer' => esc_html__('Footer menu', 'jacqueline'),
				'menu-outer'  => esc_html__('Main menu', 'jacqueline')
			);

			// Prepare demo data
			$demo_data_url = esc_url('http://jacqueline.themerex.net/wp-content/demo/');
			
			// Main demo
			$options['files']['default'] = array(
				'title'				=> esc_html__('Basekit demo', 'jacqueline'),
				'file_with_posts'	=> $demo_data_url . 'posts.txt',
				'file_with_users'	=> $demo_data_url . 'users.txt',
				'file_with_mods'	=> $demo_data_url . 'theme_mods.txt',
				'file_with_options'	=> $demo_data_url . 'theme_options.txt',
				'file_with_templates'=>$demo_data_url . 'templates_options.txt',
				'file_with_widgets'	=> $demo_data_url . 'widgets.txt',
				'file_with_revsliders' => array(
					$demo_data_url . 'revsliders/home-1.zip',
					$demo_data_url . 'revsliders/home-2.zip',
					$demo_data_url . 'revsliders/home-3.zip'
				),
				'file_with_attachments' => array(),
				'attachments_by_parts'	=> true,
				'domain_dev'	=> 'jacqueline.themerex.net',	// Developers domain ( without protocol, used only for str_replace(), not need esc_url() )
				'domain_demo'	=> 'jacqueline.themerex.net'	// Demo-site domain ( without protocol, used only for str_replace(), not need esc_url() )
			);
			for ($i=1; $i<=11; $i++) {
				$options['files']['default']['file_with_attachments'][] = $demo_data_url . 'uploads/uploads.' . sprintf('%03u', $i);
			}
		}
		return $options;
	}
}


/* Include framework core files
------------------------------------------------------------------- */
	require_once trailingslashit( get_template_directory() ) . 'fw/loader.php';
?>