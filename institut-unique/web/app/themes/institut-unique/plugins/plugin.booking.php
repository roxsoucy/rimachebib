<?php
/* Booking Calendar support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jacqueline_booking_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_booking_theme_setup', 1 );
	function jacqueline_booking_theme_setup() {
		// Register shortcode in the shortcodes list
		if (jacqueline_exists_booking()) {
			add_action('jacqueline_action_add_styles',					'jacqueline_booking_frontend_scripts');
			add_action('jacqueline_action_shortcodes_list',				'jacqueline_booking_reg_shortcodes');
			if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
				add_action('jacqueline_action_shortcodes_list_vc',		'jacqueline_booking_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'jacqueline_filter_importer_options',			'jacqueline_booking_importer_set_options' );
				add_action( 'jacqueline_action_importer_params',			'jacqueline_booking_importer_show_params', 10, 1 );
				add_action( 'jacqueline_action_importer_import',			'jacqueline_booking_importer_import', 10, 2 );
				add_action( 'jacqueline_action_importer_import_fields',	'jacqueline_booking_importer_import_fields', 10, 1 );
				add_action( 'jacqueline_action_importer_export',			'jacqueline_booking_importer_export', 10, 1 );
				add_action( 'jacqueline_action_importer_export_fields',	'jacqueline_booking_importer_export_fields', 10, 1 );
			}
		}
		if (is_admin()) {
			add_filter( 'jacqueline_filter_importer_required_plugins',	'jacqueline_booking_importer_required_plugins', 10, 2);
			add_filter( 'jacqueline_filter_required_plugins',				'jacqueline_booking_required_plugins' );
		}
	}
}


// Check if Booking Calendar installed and activated
if ( !function_exists( 'jacqueline_exists_booking' ) ) {
	function jacqueline_exists_booking() {
		return function_exists('wp_booking_start_session');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'jacqueline_booking_required_plugins' ) ) {
	//add_filter('jacqueline_filter_required_plugins',	'jacqueline_booking_required_plugins');
	function jacqueline_booking_required_plugins($list=array()) {
		if (in_array('booking', jacqueline_storage_get('required_plugins'))) {
			$path = jacqueline_get_file_dir('plugins/install/wp-booking-calendar.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> 'Booking Calendar',
					'slug' 		=> 'wp-booking-calendar',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'jacqueline_booking_frontend_scripts' ) ) {
	//add_action( 'jacqueline_action_add_styles', 'jacqueline_booking_frontend_scripts' );
	function jacqueline_booking_frontend_scripts() {
		if (file_exists(jacqueline_get_file_dir('css/plugin.booking.css')))
			jacqueline_enqueue_style( 'jacqueline-plugin.booking-style',  jacqueline_get_file_url('css/plugin.booking.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'jacqueline_booking_importer_required_plugins' ) ) {
	//add_filter( 'jacqueline_filter_importer_required_plugins',	'jacqueline_booking_importer_required_plugins', 10, 2);
	function jacqueline_booking_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('booking', jacqueline_storage_get('required_plugins')) && !jacqueline_exists_booking() )
		if (jacqueline_strpos($list, 'booking')!==false && !jacqueline_exists_booking() )
			$not_installed .= '<br>Booking Calendar';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'jacqueline_booking_importer_set_options' ) ) {
	//add_filter( 'jacqueline_filter_importer_options',	'jacqueline_booking_importer_set_options', 10, 1 );
	function jacqueline_booking_importer_set_options($options=array()) {
		if ( in_array('booking', jacqueline_storage_get('required_plugins')) && jacqueline_exists_booking() ) {
			if (is_array($options['files']) && count($options['files']) > 0) {
				foreach ($options['files'] as $k => $v) {
					$options['files'][$k]['file_with_booking'] = str_replace('posts', 'booking', $v['file_with_posts']);
				}
			}
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'jacqueline_booking_importer_show_params' ) ) {
	//add_action( 'jacqueline_action_importer_params',	'jacqueline_booking_importer_show_params', 10, 1 );
	function jacqueline_booking_importer_show_params($importer) {
		?>
		<input type="checkbox" <?php echo in_array('booking', jacqueline_storage_get('required_plugins')) && $importer->options['plugins_initial_state']
											? 'checked="checked"' 
											: ''; ?> value="1" name="import_booking" id="import_booking" /> <label for="import_booking"><?php esc_html_e('Import Booking Calendar', 'jacqueline'); ?></label><br>
		<?php
	}
}

// Import posts
if ( !function_exists( 'jacqueline_booking_importer_import' ) ) {
	//add_action( 'jacqueline_action_importer_import',	'jacqueline_booking_importer_import', 10, 2 );
	function jacqueline_booking_importer_import($importer, $action) {
		if ( $action == 'import_booking' ) {
			$importer->response['result'] = $importer->import_dump('booking', esc_html__('Booking Calendar', 'jacqueline'));
		}
	}
}

// Display import progress
if ( !function_exists( 'jacqueline_booking_importer_import_fields' ) ) {
	//add_action( 'jacqueline_action_importer_import_fields',	'jacqueline_booking_importer_import_fields', 10, 1 );
	function jacqueline_booking_importer_import_fields($importer) {
		?>
		<tr class="import_booking">
			<td class="import_progress_item"><?php esc_html_e('Booking Calendar', 'jacqueline'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}

// Export posts
if ( !function_exists( 'jacqueline_booking_importer_export' ) ) {
	//add_action( 'jacqueline_action_importer_export',	'jacqueline_booking_importer_export', 10, 1 );
	function jacqueline_booking_importer_export($importer) {
		jacqueline_storage_set('export_booking', serialize( array(
			"booking_calendars"		=> $importer->export_dump("booking_calendars"),
			"booking_categories"	=> $importer->export_dump("booking_categories"),
            "booking_config"		=> $importer->export_dump("booking_config"),
            "booking_reservation"	=> $importer->export_dump("booking_reservation"),
            "booking_slots"			=> $importer->export_dump("booking_slots")
            ) )
        );
	}
}

// Display exported data in the fields
if ( !function_exists( 'jacqueline_booking_importer_export_fields' ) ) {
	//add_action( 'jacqueline_action_importer_export_fields',	'jacqueline_booking_importer_export_fields', 10, 1 );
	function jacqueline_booking_importer_export_fields($importer) {
		?>
		<tr>
			<th align="left"><?php esc_html_e('Booking', 'jacqueline'); ?></th>
			<td><?php jacqueline_fpc(jacqueline_get_file_dir('core/core.importer/export/booking.txt'), jacqueline_storage_get('export_booking')); ?>
				<a download="booking.txt" href="<?php echo esc_url(jacqueline_get_file_url('core/core.importer/export/booking.txt')); ?>"><?php esc_html_e('Download', 'jacqueline'); ?></a>
			</td>
		</tr>
		<?php
	}
}


// Lists
//------------------------------------------------------------------------

// Return Booking categories list, prepended inherit (if need)
if ( !function_exists( 'jacqueline_get_list_booking_categories' ) ) {
	function jacqueline_get_list_booking_categories($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_booking_cats'))=='') {
			$list = array();
			if (jacqueline_exists_booking()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT category_id, category_name FROM " . esc_sql($wpdb->prefix . 'booking_categories') );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->category_id] = $row->category_name;
					}
				}
			}
			$list = apply_filters('jacqueline_filter_list_booking_categories', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_booking_cats', $list); 
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return Booking calendars list, prepended inherit (if need)
if ( !function_exists( 'jacqueline_get_list_booking_calendars' ) ) {
	function jacqueline_get_list_booking_calendars($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_booking_calendars'))=='') {
			$list = array();
			if (jacqueline_exists_booking()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT cl.calendar_id, cl.calendar_title, ct.category_name FROM " . esc_sql($wpdb->prefix . 'booking_calendars') . " AS cl"
												. " INNER JOIN " . esc_sql($wpdb->prefix . 'booking_categories') . " AS ct ON cl.category_id=ct.category_id"
										);
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->calendar_id] = $row->calendar_title . ' (' . $row->category_name . ')';
					}
				}
			}
			$list = apply_filters('jacqueline_filter_list_booking_calendars', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_booking_calendars', $list); 
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}



// Shortcodes
//------------------------------------------------------------------------

// Register shortcode in the shortcodes list
if (!function_exists('jacqueline_booking_reg_shortcodes')) {
	//add_filter('jacqueline_action_shortcodes_list',	'jacqueline_booking_reg_shortcodes');
	function jacqueline_booking_reg_shortcodes() {
		if (jacqueline_storage_isset('shortcodes')) {

			$booking_cats = jacqueline_get_list_booking_categories();
			$booking_cals = jacqueline_get_list_booking_calendars();

			jacqueline_sc_map('wp_booking_calendar', array(
				"title" => esc_html__("Booking Calendar", 'jacqueline'),
				"desc" => esc_html__("Insert Booking calendar", 'jacqueline'),
				"decorate" => true,
				"container" => false,
				"params" => array(
					"category_id" => array(
						"title" => esc_html__("Category", 'jacqueline'),
						"desc" => esc_html__("Select booking category", 'jacqueline'),
						"value" => "",
						"type" => "select",
						"options" => jacqueline_array_merge(array(0 => esc_html__('- Select category -', 'jacqueline')), $booking_cats)
					),
					"calendar_id" => array(
						"title" => esc_html__("Calendar", 'jacqueline'),
						"desc" => esc_html__("or select booking calendar (id category is empty)", 'jacqueline'),
						"dependency" => array(
							'category_id' => array('empty', '0')
						),
						"value" => "",
						"type" => "select",
						"options" => jacqueline_array_merge(array(0 => esc_html__('- Select calendar -', 'jacqueline')), $booking_cals)
					)
				)
			));
		}
	}
}


// Register shortcode in the VC shortcodes list
if (!function_exists('jacqueline_booking_reg_shortcodes_vc')) {
	//add_filter('jacqueline_action_shortcodes_list_vc',	'jacqueline_booking_reg_shortcodes_vc');
	function jacqueline_booking_reg_shortcodes_vc() {

		$booking_cats = jacqueline_get_list_booking_categories();
		$booking_cals = jacqueline_get_list_booking_calendars();


		// Jacqueline Donations form
		vc_map( array(
				"base" => "wp_booking_calendar",
				"name" => esc_html__("Booking Calendar", 'jacqueline'),
				"description" => esc_html__("Insert Booking calendar", 'jacqueline'),
				"category" => esc_html__('Content', 'jacqueline'),
				'icon' => 'icon_trx_booking',
				"class" => "trx_sc_single trx_sc_booking",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "category_id",
						"heading" => esc_html__("Category", 'jacqueline'),
						"description" => esc_html__("Select Booking category", 'jacqueline'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(jacqueline_array_merge(array(0 => esc_html__('- Select category -', 'jacqueline')), $booking_cats)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "calendar_id",
						"heading" => esc_html__("Calendar", 'jacqueline'),
						"description" => esc_html__("Select Booking calendar", 'jacqueline'),
						"admin_label" => true,
						'dependency' => array(
							'element' => 'category_id',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(jacqueline_array_merge(array(0 => esc_html__('- Select calendar -', 'jacqueline')), $booking_cals)),
						"type" => "dropdown"
					)
				)
			) );
			
		class WPBakeryShortCode_Wp_Booking_Calendar extends JACQUELINE_VC_ShortCodeSingle {}

	}
}
?>