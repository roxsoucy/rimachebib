<?php
/**
 * Jacqueline Framework: shortcodes manipulations
 *
 * @package	jacqueline
 * @since	jacqueline 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('jacqueline_sc_theme_setup')) {
	add_action( 'jacqueline_action_init_theme', 'jacqueline_sc_theme_setup', 1 );
	function jacqueline_sc_theme_setup() {
		// Add sc stylesheets
		add_action('jacqueline_action_add_styles', 'jacqueline_sc_add_styles', 1);
	}
}

if (!function_exists('jacqueline_sc_theme_setup2')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_theme_setup2' );
	function jacqueline_sc_theme_setup2() {

		if ( !is_admin() || isset($_POST['action']) ) {
			// Enable/disable shortcodes in excerpt
			add_filter('the_excerpt', 					'jacqueline_sc_excerpt_shortcodes');
	
			// Prepare shortcodes in the content
			if (function_exists('jacqueline_sc_prepare_content')) jacqueline_sc_prepare_content();
		}

		// Add init script into shortcodes output in VC frontend editor
		add_filter('jacqueline_shortcode_output', 'jacqueline_sc_add_scripts', 10, 4);

		// AJAX: Send contact form data
		add_action('wp_ajax_send_form',			'jacqueline_sc_form_send');
		add_action('wp_ajax_nopriv_send_form',	'jacqueline_sc_form_send');

		// Show shortcodes list in admin editor
		add_action('media_buttons',				'jacqueline_sc_selector_add_in_toolbar', 11);

	}
}


// Register shortcodes styles
if ( !function_exists( 'jacqueline_sc_add_styles' ) ) {
	//add_action('jacqueline_action_add_styles', 'jacqueline_sc_add_styles', 1);
	function jacqueline_sc_add_styles() {
		// Shortcodes
		jacqueline_enqueue_style( 'jacqueline-shortcodes-style',	jacqueline_get_file_url('shortcodes/theme.shortcodes.css'), array(), null );
	}
}


// Register shortcodes init scripts
if ( !function_exists( 'jacqueline_sc_add_scripts' ) ) {
	//add_filter('jacqueline_shortcode_output', 'jacqueline_sc_add_scripts', 10, 4);
	function jacqueline_sc_add_scripts($output, $tag='', $atts=array(), $content='') {

		if (jacqueline_storage_empty('shortcodes_scripts_added')) {
			jacqueline_storage_set('shortcodes_scripts_added', true);
			//jacqueline_enqueue_style( 'jacqueline-shortcodes-style', jacqueline_get_file_url('shortcodes/theme.shortcodes.css'), array(), null );
			jacqueline_enqueue_script( 'jacqueline-shortcodes-script', jacqueline_get_file_url('shortcodes/theme.shortcodes.js'), array('jquery'), null, true );	
		}
		
		return $output;
	}
}


/* Prepare text for shortcodes
-------------------------------------------------------------------------------- */

// Prepare shortcodes in content
if (!function_exists('jacqueline_sc_prepare_content')) {
	function jacqueline_sc_prepare_content() {
		if (function_exists('jacqueline_sc_clear_around')) {
			$filters = array(
				array('jacqueline', 'sc', 'clear', 'around'),
				array('widget', 'text'),
				array('the', 'excerpt'),
				array('the', 'content')
			);
			if (function_exists('jacqueline_exists_woocommerce') && jacqueline_exists_woocommerce()) {
				$filters[] = array('woocommerce', 'template', 'single', 'excerpt');
				$filters[] = array('woocommerce', 'short', 'description');
			}
			if (is_array($filters) && count($filters) > 0) {
				foreach ($filters as $flt)
					add_filter(join('_', $flt), 'jacqueline_sc_clear_around', 1);	// Priority 1 to clear spaces before do_shortcodes()
			}
		}
	}
}

// Enable/Disable shortcodes in the excerpt
if (!function_exists('jacqueline_sc_excerpt_shortcodes')) {
	function jacqueline_sc_excerpt_shortcodes($content) {
		if (!empty($content)) {
			$content = do_shortcode($content);
			//$content = strip_shortcodes($content);
		}
		return $content;
	}
}


if (!function_exists('jacqueline_sc_clear_around')) {
	function jacqueline_sc_clear_around($content) {
		if (!empty($content)) $content = preg_replace("/\](\s|\n|\r)*\[/", "][", $content);
		return $content;
	}
}


/* Shortcodes support utils
---------------------------------------------------------------------- */

// Jacqueline shortcodes load scripts
if (!function_exists('jacqueline_sc_load_scripts')) {
	function jacqueline_sc_load_scripts() {
		jacqueline_enqueue_script( 'jacqueline-shortcodes_admin-script', jacqueline_get_file_url('core/core.shortcodes/shortcodes_admin.js'), array('jquery'), null, true );
		jacqueline_enqueue_script( 'jacqueline-selection-script',  jacqueline_get_file_url('js/jquery.selection.js'), array('jquery'), null, true );
		wp_localize_script( 'jacqueline-shortcodes_admin-script', 'JACQUELINE_SHORTCODES_DATA', jacqueline_storage_get('shortcodes') );
	}
}

// Jacqueline shortcodes prepare scripts
if (!function_exists('jacqueline_sc_prepare_scripts')) {
	function jacqueline_sc_prepare_scripts() {
		if (!jacqueline_storage_isset('shortcodes_prepared')) {
			jacqueline_storage_set('shortcodes_prepared', true);
			?>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					JACQUELINE_STORAGE['shortcodes_cp'] = '<?php echo is_admin() ? (!jacqueline_storage_empty('to_colorpicker') ? jacqueline_storage_get('to_colorpicker') : 'wp') : 'custom'; ?>';	// wp | tiny | custom
				});
			</script>
			<?php
		}
	}
}

// Show shortcodes list in admin editor
if (!function_exists('jacqueline_sc_selector_add_in_toolbar')) {
	//add_action('media_buttons','jacqueline_sc_selector_add_in_toolbar', 11);
	function jacqueline_sc_selector_add_in_toolbar(){

		if ( !jacqueline_options_is_used() ) return;

		jacqueline_sc_load_scripts();
		jacqueline_sc_prepare_scripts();

		$shortcodes = jacqueline_storage_get('shortcodes');
		$shortcodes_list = '<select class="sc_selector"><option value="">&nbsp;'.esc_html__('- Select Shortcode -', 'jacqueline').'&nbsp;</option>';

		if (is_array($shortcodes) && count($shortcodes) > 0) {
			foreach ($shortcodes as $idx => $sc) {
				$shortcodes_list .= '<option value="'.esc_attr($idx).'" title="'.esc_attr($sc['desc']).'">'.esc_html($sc['title']).'</option>';
			}
		}

		$shortcodes_list .= '</select>';

		echo trim($shortcodes_list);
	}
}

// Jacqueline shortcodes builder settings
require_once trailingslashit( get_template_directory() ) . 'fw/core/core.shortcodes/shortcodes_settings.php';

// VC shortcodes settings
if ( class_exists('WPBakeryShortCode') ) {
	require_once trailingslashit( get_template_directory() ) . 'fw/core/core.shortcodes/shortcodes_vc.php';
}

// Jacqueline shortcodes implementation
jacqueline_autoload_folder( 'shortcodes/trx_basic' );
jacqueline_autoload_folder( 'shortcodes/trx_optional' );
?>