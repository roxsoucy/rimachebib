<?php
/**
 * Jacqueline Framework
 *
 * @package jacqueline
 * @since jacqueline 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Framework directory path from theme root
if ( ! defined( 'JACQUELINE_FW_DIR' ) )			define( 'JACQUELINE_FW_DIR', 'fw' );

// Theme timing
if ( ! defined( 'JACQUELINE_START_TIME' ) )		define( 'JACQUELINE_START_TIME', microtime(true));		// Framework start time
if ( ! defined( 'JACQUELINE_START_MEMORY' ) )		define( 'JACQUELINE_START_MEMORY', memory_get_usage());	// Memory usage before core loading
if ( ! defined( 'JACQUELINE_START_QUERIES' ) )	define( 'JACQUELINE_START_QUERIES', get_num_queries());	// DB queries used

// Include theme variables storage
require_once trailingslashit( get_template_directory() ) . 'fw/core/core.storage.php';

// Theme variables storage
//$theme_slug = str_replace(' ', '_', trim(strtolower(get_stylesheet())));
//jacqueline_storage_set('options_prefix', 'jacqueline'.'_'.trim($theme_slug));	// Used as prefix to store theme's options in the post meta and wp options
jacqueline_storage_set('options_prefix', 'jacqueline');	// Used as prefix to store theme's options in the post meta and wp options
jacqueline_storage_set('page_template', '');			// Storage for current page template name (used in the inheritance system)
jacqueline_storage_set('widgets_args', array(			// Arguments to register widgets
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h5 class="widget_title">',
		'after_title'   => '</h5>',
	)
);

/* Theme setup section
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_loader_theme_setup' ) ) {
	add_action( 'after_setup_theme', 'jacqueline_loader_theme_setup', 20 );
	function jacqueline_loader_theme_setup() {

		jacqueline_profiler_add_point(esc_html__('After load theme required files', 'jacqueline'));

		// Before init theme
		do_action('jacqueline_action_before_init_theme');

		// Load current values for main theme options
		jacqueline_load_main_options();

		// Theme core init - only for admin side. In frontend it called from header.php
		if ( is_admin() ) {
			jacqueline_core_init_theme();
		}
	}
}


/* Include core parts
------------------------------------------------------------------------ */
// Manual load important libraries before load all rest files
// core.strings must be first - we use jacqueline_str...() in the jacqueline_get_file_dir()
require_once trailingslashit( get_template_directory() ) . 'fw/core/core.strings.php';
// core.files must be first - we use jacqueline_get_file_dir() to include all rest parts
require_once trailingslashit( get_template_directory() ) . 'fw/core/core.files.php';

// Include debug and profiler
require_once trailingslashit( get_template_directory() ) . 'fw/core/core.debug.php';

// Include custom theme files
jacqueline_autoload_folder( 'includes' );

// Include core files
jacqueline_autoload_folder( 'core' );

// Include theme-specific plugins and post types
jacqueline_autoload_folder( 'plugins' );

// Include theme templates
jacqueline_autoload_folder( 'templates' );

// Include theme widgets
jacqueline_autoload_folder( 'widgets' );
?>