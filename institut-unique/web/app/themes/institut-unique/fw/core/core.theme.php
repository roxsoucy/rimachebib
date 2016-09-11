<?php
/**
 * Jacqueline Framework: Theme specific actions
 *
 * @package	jacqueline
 * @since	jacqueline 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'jacqueline_core_theme_setup' ) ) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_core_theme_setup', 11 );
	function jacqueline_core_theme_setup() {

		// Add default posts and comments RSS feed links to head 
		add_theme_support( 'automatic-feed-links' );
		
		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );
		
		// Custom header setup
		add_theme_support( 'custom-header', array('header-text'=>false));
		
		// Custom backgrounds setup
		add_theme_support( 'custom-background');
		
		// Supported posts formats
		add_theme_support( 'post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat') ); 
 
 		// Autogenerate title tag
		add_theme_support('title-tag');
 		
		// Add user menu
		add_theme_support('nav-menus');
		
		// WooCommerce Support
		add_theme_support( 'woocommerce' );
		
		// Editor custom stylesheet - for user
		add_editor_style(jacqueline_get_file_url('css/editor-style.css'));	
		
		// Make theme available for translation
		// Translations can be filed in the /languages directory
		load_theme_textdomain( 'jacqueline', jacqueline_get_folder_dir('languages') );


		/* Front and Admin actions and filters:
		------------------------------------------------------------------------ */

		if ( !is_admin() ) {
			
			/* Front actions and filters:
			------------------------------------------------------------------------ */
	
			// Filters wp_title to print a neat <title> tag based on what is being viewed
			if (floatval(get_bloginfo('version')) < "4.1") {
				add_action('wp_head',						'jacqueline_wp_title_show');
				add_filter('wp_title',						'jacqueline_wp_title_modify', 10, 2);
			}

			// Add main menu classes
			//add_filter('wp_nav_menu_objects', 			'jacqueline_add_mainmenu_classes', 10, 2);
	
			// Prepare logo text
			add_filter('jacqueline_filter_prepare_logo_text',	'jacqueline_prepare_logo_text', 10, 1);
	
			// Add class "widget_number_#' for each widget
			add_filter('dynamic_sidebar_params', 			'jacqueline_add_widget_number', 10, 1);
	
			// Enqueue scripts and styles
			add_action('wp_enqueue_scripts', 				'jacqueline_core_frontend_scripts');
			add_action('wp_footer',		 					'jacqueline_core_frontend_scripts_inline');
			add_action('jacqueline_action_add_scripts_inline','jacqueline_core_add_scripts_inline');

			// Prepare theme core global variables
			add_action('jacqueline_action_prepare_globals',	'jacqueline_core_prepare_globals');
		}

		// Frontend editor: Save post data
		add_action('wp_ajax_frontend_editor_save',		'jacqueline_callback_frontend_editor_save');
		add_action('wp_ajax_nopriv_frontend_editor_save', 'jacqueline_callback_frontend_editor_save');

		// Frontend editor: Delete post
		add_action('wp_ajax_frontend_editor_delete', 	'jacqueline_callback_frontend_editor_delete');
		add_action('wp_ajax_nopriv_frontend_editor_delete', 'jacqueline_callback_frontend_editor_delete');

		// Register theme specific nav menus
		jacqueline_register_theme_menus();

		// Register theme specific sidebars
		jacqueline_register_theme_sidebars();
	}
}




/* Theme init
------------------------------------------------------------------------ */

// Init theme template
function jacqueline_core_init_theme() {
	if (jacqueline_storage_get('theme_inited')===true) return;
	jacqueline_storage_set('theme_inited', true);

	if (!is_admin()) jacqueline_profiler_add_point(esc_html__('After WP INIT actions', 'jacqueline'), false);

	// Load custom options from GET and post/page/cat options
	if (isset($_GET['set']) && $_GET['set']==1) {
		foreach ($_GET as $k=>$v) {
			if (jacqueline_get_theme_option($k, null) !== null) {
				setcookie($k, $v, 0, '/');
				$_COOKIE[$k] = $v;
			}
		}
	}

	// Get custom options from current category / page / post / shop / event
	jacqueline_load_custom_options();

	// Load skin
	$skin = jacqueline_esc(jacqueline_get_custom_option('theme_skin'));
	jacqueline_storage_set('theme_skin', $skin);
	if ( file_exists(trailingslashit( get_template_directory() ) . 'skins/'.($skin).'/skin.php' )) {
		require_once trailingslashit( get_template_directory() ) . 'skins/'.($skin).'/skin.php';
	}

	// Fire init theme actions (after skin and custom options are loaded)
	do_action('jacqueline_action_init_theme');

	// Prepare theme core global variables
	do_action('jacqueline_action_prepare_globals');

	// Fire after init theme actions
	do_action('jacqueline_action_after_init_theme');
	jacqueline_profiler_add_point(esc_html__('After Theme Init', 'jacqueline'));
}


// Prepare theme global variables
if ( !function_exists( 'jacqueline_core_prepare_globals' ) ) {
	function jacqueline_core_prepare_globals() {
		if (!is_admin()) {
			// Logo text and slogan
			jacqueline_storage_set('logo_text', apply_filters('jacqueline_filter_prepare_logo_text', jacqueline_get_custom_option('logo_text')));
			jacqueline_storage_set('logo_slogan', (get_bloginfo('description') != 'Just another WordPress site' ? get_bloginfo('description') : ''));
			
			// Logo image and icons from skin
			$logo        = (jacqueline_get_logo_icon('logo') != '' ? jacqueline_get_logo_icon('logo') : jacqueline_get_file_url('skins/less/images/logo.png'));
			$logo_side   = jacqueline_get_logo_icon('logo_side');
			$logo_fixed  = jacqueline_get_logo_icon('logo_fixed');
			$logo_footer = jacqueline_get_logo_icon('logo_footer');
			jacqueline_storage_set('logo', $logo);
			jacqueline_storage_set('logo_icon',   jacqueline_get_logo_icon('logo_icon'));
			jacqueline_storage_set('logo_side',   $logo_side   ? $logo_side   : $logo);
			jacqueline_storage_set('logo_fixed',  $logo_fixed  ? $logo_fixed  : $logo);
			jacqueline_storage_set('logo_footer', $logo_footer ? $logo_footer : $logo);
	
			$shop_mode = '';
			if (jacqueline_get_custom_option('show_mode_buttons')=='yes')
				$shop_mode = jacqueline_get_value_gpc('jacqueline_shop_mode');
			if (empty($shop_mode))
				$shop_mode = jacqueline_get_custom_option('shop_mode', '');
			if (empty($shop_mode) || !is_archive())
				$shop_mode = 'thumbs';
			jacqueline_storage_set('shop_mode', $shop_mode);
		}
	}
}


// Return url for the uploaded logo image or (if not uploaded) - to image from skin folder
if ( !function_exists( 'jacqueline_get_logo_icon' ) ) {
	function jacqueline_get_logo_icon($slug) {
		$mult = jacqueline_get_retina_multiplier();
		$logo_icon = '';
		if ($mult > 1) 			$logo_icon = jacqueline_get_custom_option($slug.'_retina');
		if (empty($logo_icon))	$logo_icon = jacqueline_get_custom_option($slug);
		return $logo_icon;
	}
}


// Display logo image with text and slogan (if specified)
if ( !function_exists( 'jacqueline_show_logo' ) ) {
	function jacqueline_show_logo($logo_main=true, $logo_fixed=false, $logo_footer=false, $logo_side=false, $logo_text=true, $logo_slogan=true) {
		if ($logo_main===true)		$logo_main   = jacqueline_storage_get('logo');
		if ($logo_fixed===true)		$logo_fixed  = jacqueline_storage_get('logo_fixed');
		if ($logo_footer===true)	$logo_footer = jacqueline_storage_get('logo_footer');
		if ($logo_side===true)		$logo_side   = jacqueline_storage_get('logo_side');
		if ($logo_text===true)		$logo_text   = jacqueline_storage_get('logo_text');
		if ($logo_slogan===true)	$logo_slogan = jacqueline_storage_get('logo_slogan');
		if ($logo_main || $logo_fixed || $logo_footer || $logo_side || $logo_text) {
		?>
		<div class="logo">
			<a href="<?php echo esc_url(home_url('/')); ?>"><?php
				if (!empty($logo_main)) {
					$attr = jacqueline_getimagesize($logo_main);
					echo '<img src="'.esc_url($logo_main).'" class="logo_main" alt=""'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'>';
				}
				if (!empty($logo_fixed)) {
					$attr = jacqueline_getimagesize($logo_fixed);
					echo '<img src="'.esc_url($logo_fixed).'" class="logo_fixed" alt=""'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'>';
				}
				if (!empty($logo_footer)) {
					$attr = jacqueline_getimagesize($logo_footer);
					echo '<img src="'.esc_url($logo_footer).'" class="logo_footer" alt=""'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'>';
				}
				if (!empty($logo_side)) {
					$attr = jacqueline_getimagesize($logo_side);
					echo '<img src="'.esc_url($logo_side).'" class="logo_side" alt=""'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'>';
				}
				echo !empty($logo_text) ? '<div class="logo_text">'.trim($logo_text).'</div>' : '';
				echo !empty($logo_slogan) ? '<br><div class="logo_slogan">' . esc_html($logo_slogan) . '</div>' : '';
			?></a>
		</div>
		<?php 
		}
	} 
}


// Add menu locations
if ( !function_exists( 'jacqueline_register_theme_menus' ) ) {
	function jacqueline_register_theme_menus() {
		register_nav_menus(apply_filters('jacqueline_filter_add_theme_menus', array(
			'menu_main'		=> esc_html__('Main Menu', 'jacqueline'),
			'menu_user'		=> esc_html__('User Menu', 'jacqueline'),
			'menu_footer'	=> esc_html__('Footer Menu', 'jacqueline'),
			'menu_side'		=> esc_html__('Side Menu', 'jacqueline')
		)));
	}
}


// Register widgetized area
if ( !function_exists( 'jacqueline_register_theme_sidebars' ) ) {
	function jacqueline_register_theme_sidebars($sidebars=array()) {
		if (!is_array($sidebars)) $sidebars = array();
		// Custom sidebars
		$custom = jacqueline_get_theme_option('custom_sidebars');
		if (is_array($custom) && count($custom) > 0) {
			foreach ($custom as $i => $sb) {
				if (trim(chop($sb))=='') continue;
				$sidebars['sidebar_custom_'.($i)]  = $sb;
			}
		}
		$sidebars = apply_filters( 'jacqueline_filter_add_theme_sidebars', $sidebars );
		jacqueline_storage_set('registered_sidebars', $sidebars);
		if (is_array($sidebars) && count($sidebars) > 0) {
			foreach ($sidebars as $id=>$name) {
				register_sidebar( array_merge( array(
													'name'          => $name,
													'id'            => $id
												),
												jacqueline_storage_get('widgets_args')
									)
				);
			}
		}
	}
}





/* Front actions and filters:
------------------------------------------------------------------------ */

//  Enqueue scripts and styles
if ( !function_exists( 'jacqueline_core_frontend_scripts' ) ) {
	function jacqueline_core_frontend_scripts() {
		
		// Modernizr will load in head before other scripts and styles
		// Use older version (from photostack)
		jacqueline_enqueue_script( 'jacqueline-core-modernizr-script', jacqueline_get_file_url('js/photostack/modernizr.min.js'), array(), null, false );
		
		// Enqueue styles
		//-----------------------------------------------------------------------------------------------------
		
		// Prepare custom fonts
		$fonts = jacqueline_get_list_fonts(false);
		$theme_fonts = array();
		$custom_fonts = jacqueline_get_custom_fonts();
		if (is_array($custom_fonts) && count($custom_fonts) > 0) {
			foreach ($custom_fonts as $s=>$f) {
				if (!empty($f['font-family']) && !jacqueline_is_inherit_option($f['font-family'])) $theme_fonts[$f['font-family']] = 1;
			}
		}
		// Prepare current skin fonts
		$theme_fonts = apply_filters('jacqueline_filter_used_fonts', $theme_fonts);
		// Link to selected fonts
		if (is_array($theme_fonts) && count($theme_fonts) > 0) {
			$google_fonts = '';
			foreach ($theme_fonts as $font=>$v) {
				if (isset($fonts[$font])) {
					$font_name = ($pos=jacqueline_strpos($font,' ('))!==false ? jacqueline_substr($font, 0, $pos) : $font;
					if (!empty($fonts[$font]['css'])) {
						$css = $fonts[$font]['css'];
						jacqueline_enqueue_style( 'jacqueline-font-'.str_replace(' ', '-', $font_name).'-style', $css, array(), null );
					} else {
						$google_fonts .= ($google_fonts ? '%7C' : '') 
							. (!empty($fonts[$font]['link']) ? $fonts[$font]['link'] : str_replace(' ', '+', $font_name).':300,300italic,400,400italic,700,700italic');
					}
				}
			}
			if ($google_fonts)
				jacqueline_enqueue_style( 'jacqueline-font-google_fonts-style', jacqueline_get_protocol() . '://fonts.googleapis.com/css?family=' . $google_fonts . '&subset=' . jacqueline_get_theme_option('fonts_subset'), array(), null );
		}
		
		// Fontello styles must be loaded before main stylesheet
		jacqueline_enqueue_style( 'jacqueline-fontello-style',  jacqueline_get_file_url('css/fontello/css/fontello.css'),  array(), null);
		//jacqueline_enqueue_style( 'jacqueline-fontello-animation-style', jacqueline_get_file_url('css/fontello/css/animation.css'), array(), null);

		// Main stylesheet
		jacqueline_enqueue_style( 'jacqueline-main-style', get_stylesheet_uri(), array(), null );
		
		// Animations
		if (jacqueline_get_theme_option('css_animation')=='yes' && (jacqueline_get_theme_option('animation_on_mobile')=='yes' || !wp_is_mobile()) && !jacqueline_vc_is_frontend())
			jacqueline_enqueue_style( 'jacqueline-animation-style',	jacqueline_get_file_url('css/core.animation.css'), array(), null );

		// Theme skin stylesheet
		do_action('jacqueline_action_add_styles');
		
		// Theme customizer stylesheet and inline styles
		jacqueline_enqueue_custom_styles();

		// Responsive
		if (jacqueline_get_theme_option('responsive_layouts') == 'yes') {
			$suffix = jacqueline_param_is_off(jacqueline_get_custom_option('show_sidebar_outer')) ? '' : '-outer';
			jacqueline_enqueue_style( 'jacqueline-responsive-style', jacqueline_get_file_url('css/responsive'.($suffix).'.css'), array(), null );
			do_action('jacqueline_action_add_responsive');
			if (jacqueline_get_custom_option('theme_skin')!='') {
				$css = apply_filters('jacqueline_filter_add_responsive_inline', '');
				if (!empty($css)) wp_add_inline_style( 'jacqueline-responsive-style', $css );
			}
			jacqueline_enqueue_style( 'jacqueline-custom-responsive-style', jacqueline_get_file_url('css/custom.responsive.css'), array(), null );
		}

		// Disable loading JQuery UI CSS
		//global $wp_styles, $wp_scripts;
		//$wp_styles->done[]	= 'jquery-ui';
		//$wp_styles->done[]	= 'date-picker-css';
		wp_deregister_style('jquery_ui');
		wp_deregister_style('date-picker-css');


		// Enqueue scripts	
		//----------------------------------------------------------------------------------------------------------------------------
		
		// Load separate theme scripts
		jacqueline_enqueue_script( 'superfish', jacqueline_get_file_url('js/superfish.js'), array('jquery'), null, true );
		if (jacqueline_get_theme_option('menu_slider')=='yes') {
			jacqueline_enqueue_script( 'jacqueline-slidemenu-script', jacqueline_get_file_url('js/jquery.slidemenu.js'), array('jquery'), null, true );
			//jacqueline_enqueue_script( 'jacqueline-jquery-easing-script', jacqueline_get_file_url('js/jquery.easing.js'), array('jquery'), null, true );
		}

		if ( is_single() && jacqueline_get_custom_option('show_reviews')=='yes' ) {
			jacqueline_enqueue_script( 'jacqueline-core-reviews-script', jacqueline_get_file_url('js/core.reviews.js'), array('jquery'), null, true );
		}

		jacqueline_enqueue_script( 'jacqueline-core-utils-script',	jacqueline_get_file_url('js/core.utils.js'), array('jquery'), null, true );
		jacqueline_enqueue_script( 'jacqueline-core-init-script',	jacqueline_get_file_url('js/core.init.js'), array('jquery'), null, true );	
		jacqueline_enqueue_script( 'jacqueline-theme-init-script',	jacqueline_get_file_url('js/theme.init.js'), array('jquery'), null, true );	

		// Media elements library	
		if (jacqueline_get_theme_option('use_mediaelement')=='yes') {
			wp_enqueue_style ( 'mediaelement' );
			wp_enqueue_style ( 'wp-mediaelement' );
			wp_enqueue_script( 'mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		} else {
			wp_deregister_style('mediaelement');
			wp_deregister_style('wp-mediaelement');
		}
		
		// Video background
		if (jacqueline_get_custom_option('show_video_bg') == 'yes' && jacqueline_get_custom_option('video_bg_youtube_code') != '') {
			jacqueline_enqueue_script( 'jacqueline-video-bg-script', jacqueline_get_file_url('js/jquery.tubular.1.0.js'), array('jquery'), null, true );
		}

		// Google map
		  if ( jacqueline_get_custom_option('show_googlemap')=='yes' ) { 
			   $api_key = jacqueline_get_theme_option('api_google');
			   jacqueline_enqueue_script( 'googlemap', jacqueline_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
			   jacqueline_enqueue_script( 'jacqueline-googlemap-script', jacqueline_get_file_url('js/core.googlemap.js'), array(), null, true );
		  }

			
		// Social share buttons
		if (is_singular() && !jacqueline_storage_get('blog_streampage') && jacqueline_get_custom_option('show_share')!='hide') {
			jacqueline_enqueue_script( 'jacqueline-social-share-script', jacqueline_get_file_url('js/social/social-share.js'), array('jquery'), null, true );
		}

		// Comments
		if ( is_singular() && !jacqueline_storage_get('blog_streampage') && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply', false, array(), null, true );
		}

		// Custom panel
		if (jacqueline_get_theme_option('show_theme_customizer') == 'yes') {
			if (file_exists(jacqueline_get_file_dir('core/core.customizer/front.customizer.css')))
				jacqueline_enqueue_style(  'jacqueline-customizer-style',  jacqueline_get_file_url('core/core.customizer/front.customizer.css'), array(), null );
			if (file_exists(jacqueline_get_file_dir('core/core.customizer/front.customizer.js')))
				jacqueline_enqueue_script( 'jacqueline-customizer-script', jacqueline_get_file_url('core/core.customizer/front.customizer.js'), array(), null, true );	
		}
		
		//Debug utils
		if (jacqueline_get_theme_option('debug_mode')=='yes') {
			jacqueline_enqueue_script( 'jacqueline-core-debug-script', jacqueline_get_file_url('js/core.debug.js'), array(), null, true );
		}

		// Theme skin script
		do_action('jacqueline_action_add_scripts');
	}
}

//  Enqueue Swiper Slider scripts and styles
if ( !function_exists( 'jacqueline_enqueue_slider' ) ) {
	function jacqueline_enqueue_slider($engine='all') {
		if ($engine=='all' || $engine=='swiper') {
			jacqueline_enqueue_style(  'jacqueline-swiperslider-style', 			jacqueline_get_file_url('js/swiper/swiper.css'), array(), null );
			jacqueline_enqueue_script( 'jacqueline-swiperslider-script', 			jacqueline_get_file_url('js/swiper/swiper.js'), array(), null, true );
			// jQuery version conflict with Revolution Slider
			//jacqueline_enqueue_script( 'jacqueline-swiperslider-script', 			jacqueline_get_file_url('js/swiper/swiper.jquery.js'), array(), null, true );
		}
	}
}

//  Enqueue Photostack gallery
if ( !function_exists( 'jacqueline_enqueue_polaroid' ) ) {
	function jacqueline_enqueue_polaroid() {
		jacqueline_enqueue_style(  'jacqueline-polaroid-style', 	jacqueline_get_file_url('js/photostack/component.css'), array(), null );
		jacqueline_enqueue_script( 'jacqueline-classie-script',		jacqueline_get_file_url('js/photostack/classie.js'), array(), null, true );
		jacqueline_enqueue_script( 'jacqueline-polaroid-script',	jacqueline_get_file_url('js/photostack/photostack.js'), array(), null, true );
	}
}

//  Enqueue Messages scripts and styles
if ( !function_exists( 'jacqueline_enqueue_messages' ) ) {
	function jacqueline_enqueue_messages() {
		jacqueline_enqueue_style(  'jacqueline-messages-style',		jacqueline_get_file_url('js/core.messages/core.messages.css'), array(), null );
		jacqueline_enqueue_script( 'jacqueline-messages-script',	jacqueline_get_file_url('js/core.messages/core.messages.js'),  array('jquery'), null, true );
	}
}

//  Enqueue Portfolio hover scripts and styles
if ( !function_exists( 'jacqueline_enqueue_portfolio' ) ) {
	function jacqueline_enqueue_portfolio($hover='') {
		jacqueline_enqueue_style( 'jacqueline-portfolio-style',  jacqueline_get_file_url('css/core.portfolio.css'), array(), null );
		if (jacqueline_strpos($hover, 'effect_dir')!==false)
			jacqueline_enqueue_script( 'hoverdir', jacqueline_get_file_url('js/hover/jquery.hoverdir.js'), array(), null, true );
	}
}

//  Enqueue Charts and Diagrams scripts and styles
if ( !function_exists( 'jacqueline_enqueue_diagram' ) ) {
	function jacqueline_enqueue_diagram($type='all') {
		if ($type=='all' || $type=='pie') jacqueline_enqueue_script( 'jacqueline-diagram-chart-script',	jacqueline_get_file_url('js/diagram/chart.min.js'), array(), null, true );
		if ($type=='all' || $type=='arc') jacqueline_enqueue_script( 'jacqueline-diagram-raphael-script',	jacqueline_get_file_url('js/diagram/diagram.raphael.min.js'), array(), 'no-compose', true );
	}
}

// Enqueue Theme Popup scripts and styles
// Link must have attribute: data-rel="popup" or data-rel="popup[gallery]"
if ( !function_exists( 'jacqueline_enqueue_popup' ) ) {
	function jacqueline_enqueue_popup($engine='') {
		if ($engine=='pretty' || (empty($engine) && jacqueline_get_theme_option('popup_engine')=='pretty')) {
			jacqueline_enqueue_style(  'jacqueline-prettyphoto-style',	jacqueline_get_file_url('js/prettyphoto/css/prettyPhoto.css'), array(), null );
			jacqueline_enqueue_script( 'jacqueline-prettyphoto-script',	jacqueline_get_file_url('js/prettyphoto/jquery.prettyPhoto.min.js'), array('jquery'), 'no-compose', true );
		} else if ($engine=='magnific' || (empty($engine) && jacqueline_get_theme_option('popup_engine')=='magnific')) {
			jacqueline_enqueue_style(  'jacqueline-magnific-style',	jacqueline_get_file_url('js/magnific/magnific-popup.css'), array(), null );
			jacqueline_enqueue_script( 'jacqueline-magnific-script',jacqueline_get_file_url('js/magnific/jquery.magnific-popup.min.js'), array('jquery'), '', true );
		} else if ($engine=='internal' || (empty($engine) && jacqueline_get_theme_option('popup_engine')=='internal')) {
			jacqueline_enqueue_messages();
		}
	}
}

//  Add inline scripts in the footer hook
if ( !function_exists( 'jacqueline_core_frontend_scripts_inline' ) ) {
	//add_action('wp_footer', 'jacqueline_core_frontend_scripts_inline');
	function jacqueline_core_frontend_scripts_inline() {
		add_filter('style_loader_tag', 'jacqueline_core_add_property_to_link', 10, 3);
		do_action('jacqueline_action_add_scripts_inline');
	}
}

//  Add property="stylesheet" into all tags <link> in the footer
if (!function_exists('jacqueline_core_add_property_to_link')) {
	//add_filter('style_loader_tag', 'jacqueline_core_add_property_to_link', 10, 3);
	function jacqueline_core_add_property_to_link($link, $handle='', $href='') {
		return str_replace('<link ', '<link property="stylesheet" ', $link);
	}
}

//  Add inline scripts in the footer
if (!function_exists('jacqueline_core_add_scripts_inline')) {
	function jacqueline_core_add_scripts_inline() {

		$msg = jacqueline_get_system_message(true); 
		if (!empty($msg['message'])) jacqueline_enqueue_messages();

		echo "<script type=\"text/javascript\">"
			
			. "if (typeof JACQUELINE_STORAGE == 'undefined') var JACQUELINE_STORAGE = {};"
			
			// AJAX parameters
			. "JACQUELINE_STORAGE['ajax_url']			 = '" . esc_url(admin_url('admin-ajax.php')) . "';"
			. "JACQUELINE_STORAGE['ajax_nonce']		 = '" . esc_attr(wp_create_nonce(admin_url('admin-ajax.php'))) . "';"
			
			// Site base url
			. "JACQUELINE_STORAGE['site_url']			= '" . get_site_url() . "';"
			
			// VC frontend edit mode
			. "JACQUELINE_STORAGE['vc_edit_mode']		= " . (function_exists('jacqueline_vc_is_frontend') && jacqueline_vc_is_frontend() ? 'true' : 'false') . ";"
			
			// Theme base font
			. "JACQUELINE_STORAGE['theme_font']		= '" . jacqueline_get_custom_font_settings('p', 'font-family') . "';"
			
			// Theme skin
			. "JACQUELINE_STORAGE['theme_skin']			= '" . esc_attr(jacqueline_get_custom_option('theme_skin')) . "';"
			. "JACQUELINE_STORAGE['theme_skin_color']		= '" . jacqueline_get_scheme_color('text_dark') . "';"
			. "JACQUELINE_STORAGE['theme_skin_bg_color']	= '" . jacqueline_get_scheme_color('bg_color') . "';"
			
			// Slider height
			. "JACQUELINE_STORAGE['slider_height']	= " . max(100, jacqueline_get_custom_option('slider_height')) . ";"
			
			// System message
			. "JACQUELINE_STORAGE['system_message']	= {"
				. "message: '" . addslashes($msg['message']) . "',"
				. "status: '"  . addslashes($msg['status'])  . "',"
				. "header: '"  . addslashes($msg['header'])  . "'"
				. "};"
			
			// User logged in
			. "JACQUELINE_STORAGE['user_logged_in']	= " . (is_user_logged_in() ? 'true' : 'false') . ";"
			
			// Show table of content for the current page
			. "JACQUELINE_STORAGE['toc_menu']		= '" . esc_attr(jacqueline_get_custom_option('menu_toc')) . "';"
			. "JACQUELINE_STORAGE['toc_menu_home']	= " . (jacqueline_get_custom_option('menu_toc')!='hide' && jacqueline_get_custom_option('menu_toc_home')=='yes' ? 'true' : 'false') . ";"
			. "JACQUELINE_STORAGE['toc_menu_top']	= " . (jacqueline_get_custom_option('menu_toc')!='hide' && jacqueline_get_custom_option('menu_toc_top')=='yes' ? 'true' : 'false') . ";"
			
			// Fix main menu
			. "JACQUELINE_STORAGE['menu_fixed']		= " . (jacqueline_get_theme_option('menu_attachment')=='fixed' ? 'true' : 'false') . ";"
			
			// Use responsive version for main menu
			. "JACQUELINE_STORAGE['menu_mobile']	= " . (jacqueline_get_theme_option('responsive_layouts') == 'yes' ? max(0, (int) jacqueline_get_theme_option('menu_mobile')) : 0) . ";"
			. "JACQUELINE_STORAGE['menu_slider']     = " . (jacqueline_get_theme_option('menu_slider')=='yes' ? 'true' : 'false') . ";"
			
			// Menu cache is used
			. "JACQUELINE_STORAGE['menu_cache']	= " . (jacqueline_get_theme_option('use_menu_cache')=='yes' ? 'true' : 'false') . ";"

			// Right panel demo timer
			. "JACQUELINE_STORAGE['demo_time']		= " . (jacqueline_get_theme_option('show_theme_customizer')=='yes' ? max(0, (int) jacqueline_get_theme_option('customizer_demo')) : 0) . ";"

			// Video and Audio tag wrapper
			. "JACQUELINE_STORAGE['media_elements_enabled'] = " . (jacqueline_get_theme_option('use_mediaelement')=='yes' ? 'true' : 'false') . ";"
			
			// Use AJAX search
			. "JACQUELINE_STORAGE['ajax_search_enabled'] 	= " . (jacqueline_get_theme_option('use_ajax_search')=='yes' ? 'true' : 'false') . ";"
			. "JACQUELINE_STORAGE['ajax_search_min_length']	= " . min(3, jacqueline_get_theme_option('ajax_search_min_length')) . ";"
			. "JACQUELINE_STORAGE['ajax_search_delay']		= " . min(200, max(1000, jacqueline_get_theme_option('ajax_search_delay'))) . ";"

			// Use CSS animation
			. "JACQUELINE_STORAGE['css_animation']      = " . (jacqueline_get_theme_option('css_animation')=='yes' ? 'true' : 'false') . ";"
			. "JACQUELINE_STORAGE['menu_animation_in']  = '" . esc_attr(jacqueline_get_theme_option('menu_animation_in')) . "';"
			. "JACQUELINE_STORAGE['menu_animation_out'] = '" . esc_attr(jacqueline_get_theme_option('menu_animation_out')) . "';"

			// Popup windows engine
			. "JACQUELINE_STORAGE['popup_engine']	= '" . esc_attr(jacqueline_get_theme_option('popup_engine')) . "';"

			// E-mail mask
			. "JACQUELINE_STORAGE['email_mask']		= '^([a-zA-Z0-9_\\-]+\\.)*[a-zA-Z0-9_\\-]+@[a-z0-9_\\-]+(\\.[a-z0-9_\\-]+)*\\.[a-z]{2,6}$';"
			
			// Messages max length
			. "JACQUELINE_STORAGE['contacts_maxlength']	= " . intval(jacqueline_get_theme_option('message_maxlength_contacts')) . ";"
			. "JACQUELINE_STORAGE['comments_maxlength']	= " . intval(jacqueline_get_theme_option('message_maxlength_comments')) . ";"

			// Remember visitors settings
			. "JACQUELINE_STORAGE['remember_visitors_settings']	= " . (jacqueline_get_theme_option('remember_visitors_settings')=='yes' ? 'true' : 'false') . ";"

			// Internal vars - do not change it!
			// Flag for review mechanism
			. "JACQUELINE_STORAGE['admin_mode']			= false;"
			// Max scale factor for the portfolio and other isotope elements before relayout
			. "JACQUELINE_STORAGE['isotope_resize_delta']	= 0.3;"
			// jQuery object for the message box in the form
			. "JACQUELINE_STORAGE['error_message_box']	= null;"
			// Waiting for the viewmore results
			. "JACQUELINE_STORAGE['viewmore_busy']		= false;"
			. "JACQUELINE_STORAGE['video_resize_inited']	= false;"
			. "JACQUELINE_STORAGE['top_panel_height']		= 0;"
			
			. "</script>";
	}
}


//  Enqueue Custom styles (main Theme options settings)
if ( !function_exists( 'jacqueline_enqueue_custom_styles' ) ) {
	function jacqueline_enqueue_custom_styles() {
		// Custom stylesheet
		$custom_css = '';	//jacqueline_get_custom_option('custom_stylesheet_url');
		jacqueline_enqueue_style( 'jacqueline-custom-style', $custom_css ? $custom_css : jacqueline_get_file_url('css/custom-style.css'), array(), null );
		// Custom inline styles
		wp_add_inline_style( 'jacqueline-custom-style', jacqueline_prepare_custom_styles() );
	}
}

// Add class "widget_number_#' for each widget
if ( !function_exists( 'jacqueline_add_widget_number' ) ) {
	//add_filter('dynamic_sidebar_params', 'jacqueline_add_widget_number', 10, 1);
	function jacqueline_add_widget_number($prm) {
		if (is_admin()) return $prm;
		static $num=0, $last_sidebar='', $last_sidebar_id='', $last_sidebar_columns=0, $last_sidebar_count=0, $sidebars_widgets=array();
		$cur_sidebar = jacqueline_storage_get('current_sidebar');
		if (empty($cur_sidebar)) $cur_sidebar = 'undefined';
		if (count($sidebars_widgets) == 0)
			$sidebars_widgets = wp_get_sidebars_widgets();
		if ($last_sidebar != $cur_sidebar) {
			$num = 0;
			$last_sidebar = $cur_sidebar;
			$last_sidebar_id = $prm[0]['id'];
			$last_sidebar_columns = max(1, (int) jacqueline_get_custom_option('sidebar_'.($cur_sidebar).'_columns'));
			$last_sidebar_count = count($sidebars_widgets[$last_sidebar_id]);
		}
		$num++;
		$prm[0]['before_widget'] = str_replace(' class="', ' class="widget_number_'.esc_attr($num).($last_sidebar_columns > 1 ? ' column-1_'.esc_attr($last_sidebar_columns) : '').' ', $prm[0]['before_widget']);
		return $prm;
	}
}


// Show <title> tag under old WP (version < 4.1)
if ( !function_exists( 'jacqueline_wp_title_show' ) ) {
	// add_action('wp_head', 'jacqueline_wp_title_show');
	function jacqueline_wp_title_show() {
		?><title><?php wp_title( '|', true, 'right' ); ?></title><?php
	}
}

// Filters wp_title to print a neat <title> tag based on what is being viewed.
if ( !function_exists( 'jacqueline_wp_title_modify' ) ) {
	// add_filter( 'wp_title', 'jacqueline_wp_title_modify', 10, 2 );
	function jacqueline_wp_title_modify( $title, $sep ) {
		global $page, $paged;
		if ( is_feed() ) return $title;
		// Add the blog name
		$title .= get_bloginfo( 'name' );
		// Add the blog description for the home/front page.
		if ( is_home() || is_front_page() ) {
			$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description )
				$title .= " $sep $site_description";
		}
		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			$title .= " $sep " . sprintf( esc_html__( 'Page %s', 'jacqueline' ), max( $paged, $page ) );
		return $title;
	}
}

// Add main menu classes
if ( !function_exists( 'jacqueline_add_mainmenu_classes' ) ) {
	// add_filter('wp_nav_menu_objects', 'jacqueline_add_mainmenu_classes', 10, 2);
	function jacqueline_add_mainmenu_classes($items, $args) {
		if (is_admin()) return $items;
		if ($args->menu_id == 'mainmenu' && jacqueline_get_theme_option('menu_colored')=='yes' && is_array($items) && count($items) > 0) {
			foreach($items as $k=>$item) {
				if ($item->menu_item_parent==0) {
					if ($item->type=='taxonomy' && $item->object=='category') {
						$cur_tint = jacqueline_taxonomy_get_inherited_property('category', $item->object_id, 'bg_tint');
						if (!empty($cur_tint) && !jacqueline_is_inherit_option($cur_tint))
							$items[$k]->classes[] = 'bg_tint_'.esc_attr($cur_tint);
					}
				}
			}
		}
		return $items;
	}
}


// Save post data from frontend editor
if ( !function_exists( 'jacqueline_callback_frontend_editor_save' ) ) {
	function jacqueline_callback_frontend_editor_save() {

		if ( !wp_verify_nonce( jacqueline_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();
		$response = array('error'=>'');

		parse_str($_REQUEST['data'], $output);
		$post_id = $output['frontend_editor_post_id'];

		if ( jacqueline_get_theme_option("allow_editor")=='yes' && (current_user_can('edit_posts', $post_id) || current_user_can('edit_pages', $post_id)) ) {
			if ($post_id > 0) {
				$title   = stripslashes($output['frontend_editor_post_title']);
				$content = stripslashes($output['frontend_editor_post_content']);
				$excerpt = stripslashes($output['frontend_editor_post_excerpt']);
				$rez = wp_update_post(array(
					'ID'           => $post_id,
					'post_content' => $content,
					'post_excerpt' => $excerpt,
					'post_title'   => $title
				));
				if ($rez == 0) 
					$response['error'] = esc_html__('Post update error!', 'jacqueline');
			} else {
				$response['error'] = esc_html__('Post update error!', 'jacqueline');
			}
		} else
			$response['error'] = esc_html__('Post update denied!', 'jacqueline');
		
		echo json_encode($response);
		die();
	}
}

// Delete post from frontend editor
if ( !function_exists( 'jacqueline_callback_frontend_editor_delete' ) ) {
	function jacqueline_callback_frontend_editor_delete() {

		if ( !wp_verify_nonce( jacqueline_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();

		$response = array('error'=>'');
		
		$post_id = $_REQUEST['post_id'];

		if ( jacqueline_get_theme_option("allow_editor")=='yes' && (current_user_can('delete_posts', $post_id) || current_user_can('delete_pages', $post_id)) ) {
			if ($post_id > 0) {
				$rez = wp_delete_post($post_id);
				if ($rez === false) 
					$response['error'] = esc_html__('Post delete error!', 'jacqueline');
			} else {
				$response['error'] = esc_html__('Post delete error!', 'jacqueline');
			}
		} else
			$response['error'] = esc_html__('Post delete denied!', 'jacqueline');

		echo json_encode($response);
		die();
	}
}


// Prepare logo text
if ( !function_exists( 'jacqueline_prepare_logo_text' ) ) {
	function jacqueline_prepare_logo_text($text) {
		$text = str_replace(array('[', ']'), array('<span class="theme_accent">', '</span>'), $text);
		$text = str_replace(array('{', '}'), array('<strong>', '</strong>'), $text);
		return $text;
	}
}
?>