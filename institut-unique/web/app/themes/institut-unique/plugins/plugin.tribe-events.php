<?php
/* Tribe Events (TE) support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jacqueline_tribe_events_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_tribe_events_theme_setup', 1 );
	function jacqueline_tribe_events_theme_setup() {
		if (jacqueline_exists_tribe_events()) {

			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('jacqueline_filter_get_blog_type',					'jacqueline_tribe_events_get_blog_type', 9, 2);
			add_filter('jacqueline_filter_get_blog_title',					'jacqueline_tribe_events_get_blog_title', 9, 2);
			add_filter('jacqueline_filter_get_current_taxonomy',			'jacqueline_tribe_events_get_current_taxonomy', 9, 2);
			add_filter('jacqueline_filter_is_taxonomy',						'jacqueline_tribe_events_is_taxonomy', 9, 2);
			add_filter('jacqueline_filter_get_stream_page_title',			'jacqueline_tribe_events_get_stream_page_title', 9, 2);
			add_filter('jacqueline_filter_get_stream_page_link',			'jacqueline_tribe_events_get_stream_page_link', 9, 2);
			add_filter('jacqueline_filter_get_stream_page_id',				'jacqueline_tribe_events_get_stream_page_id', 9, 2);
			add_filter('jacqueline_filter_get_period_links',				'jacqueline_tribe_events_get_period_links', 9, 3);
			add_filter('jacqueline_filter_detect_inheritance_key',			'jacqueline_tribe_events_detect_inheritance_key', 9, 1);

			add_action( 'jacqueline_action_add_styles',						'jacqueline_tribe_events_frontend_scripts' );

			add_filter('jacqueline_filter_list_post_types', 				'jacqueline_tribe_events_list_post_types', 10, 1);
			add_filter('jacqueline_filter_post_date',	 					'jacqueline_tribe_events_post_date', 9, 3);

			add_filter('jacqueline_filter_add_sort_order', 					'jacqueline_tribe_events_add_sort_order', 10, 3);
			add_filter('jacqueline_filter_orderby_need',					'jacqueline_tribe_events_orderby_need', 9, 2);

			// Advanced Calendar filters
			add_filter('jacqueline_filter_calendar_get_month_link',		'jacqueline_tribe_events_calendar_get_month_link', 9, 2);
			add_filter('jacqueline_filter_calendar_get_prev_month',		'jacqueline_tribe_events_calendar_get_prev_month', 9, 2);
			add_filter('jacqueline_filter_calendar_get_next_month',		'jacqueline_tribe_events_calendar_get_next_month', 9, 2);
			add_filter('jacqueline_filter_calendar_get_curr_month_posts',	'jacqueline_tribe_events_calendar_get_curr_month_posts', 9, 2);

			// Add Google API key to the map's link
			add_filter('tribe_events_google_maps_api',    				'jacqueline_tribe_events_google_maps_api');
   
			// Add query params to show events in the blog
			add_filter( 'posts_join',									'jacqueline_tribe_events_posts_join', 10, 2 );
			add_filter( 'getarchives_join',								'jacqueline_tribe_events_getarchives_join', 10, 2 );
			add_filter( 'posts_where',									'jacqueline_tribe_events_posts_where', 10, 2 );
			add_filter( 'getarchives_where',							'jacqueline_tribe_events_getarchives_where', 10, 2 );
			

			// Extra column for events lists
			if (jacqueline_get_theme_option('show_overriden_posts')=='yes') {
				add_filter('manage_edit-'.Tribe__Events__Main::POSTTYPE.'_columns',			'jacqueline_post_add_options_column', 9);
				add_filter('manage_'.Tribe__Events__Main::POSTTYPE.'_posts_custom_column',	'jacqueline_post_fill_options_column', 9, 2);
			}

			// One-click installer
			if (is_admin()) {
				add_filter( 'jacqueline_filter_importer_options',			'jacqueline_tribe_events_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'jacqueline_filter_importer_required_plugins',	'jacqueline_tribe_events_importer_required_plugins', 10, 2 );
			add_filter( 'jacqueline_filter_required_plugins',				'jacqueline_tribe_events_required_plugins' );
		}
	}
}

if ( !function_exists( 'jacqueline_tribe_events_settings_theme_setup2' ) ) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_tribe_events_settings_theme_setup2', 3 );
	function jacqueline_tribe_events_settings_theme_setup2() {
		if (jacqueline_exists_tribe_events()) {
			jacqueline_add_theme_inheritance( array('tribe_events' => array(
				'stream_template' => 'tribe-events/default-template',
				'single_template' => '',
				'taxonomy' => array(Tribe__Events__Main::TAXONOMY),
				'taxonomy_tags' => array(),
				'post_type' => array(
					Tribe__Events__Main::POSTTYPE,
					Tribe__Events__Main::VENUE_POST_TYPE,
					Tribe__Events__Main::ORGANIZER_POST_TYPE
				),
				'override' => 'post'
				) )
			);
	
			// Add Tribe Events specific options in the Theme Options
	
			jacqueline_storage_set_array_before('options', 'partition_reviews', array(
			
				"partition_tribe_events" => array(
						"title" => __('Events', 'jacqueline'),
						"icon" => "iconadmin-clock",
						"type" => "partition"),
			
				"info_tribe_events_1" => array(
						"title" => __('Events settings', 'jacqueline'),
						"desc" => __('Set up events posts behaviour in the blog.', 'jacqueline'),
						"type" => "info"),
			
				"show_tribe_events_in_blog" => array(
						"title" => __('Show events in the blog',  'jacqueline'),
						"desc" => __("Show events in stream pages (blog, archives) or only in special pages", 'jacqueline'),
						"divider" => false,
						"std" => "yes",
						"options" => jacqueline_get_options_param('list_yes_no'),
						"type" => "switch")
				)
			);	
		}
	}
}

// Check if Tribe Events installed and activated
if (!function_exists('jacqueline_exists_tribe_events')) {
	function jacqueline_exists_tribe_events() {
		return class_exists( 'Tribe__Events__Main' );
	}
}


// Return true, if current page is any TE page
if ( !function_exists( 'jacqueline_is_tribe_events_page' ) ) {
	function jacqueline_is_tribe_events_page() {
		$is = false;
		if (jacqueline_exists_tribe_events()) {
			$is = in_array(jacqueline_storage_get('page_template'), array('tribe-events/default-template'));
			if (!$is) {
				if (jacqueline_storage_empty('pre_query')) {
					if (!is_search()) $is = tribe_is_event() || tribe_is_event_query() || tribe_is_event_category() || tribe_is_event_venue() || tribe_is_event_organizer();
				} else {
					$is = jacqueline_storage_get_obj_property('pre_query', 'tribe_is_event')
							|| jacqueline_storage_get_obj_property('pre_query', 'tribe_is_multi_posttype')
							|| jacqueline_storage_get_obj_property('pre_query', 'tribe_is_event_category')
							|| jacqueline_storage_get_obj_property('pre_query', 'tribe_is_event_venue')
							|| jacqueline_storage_get_obj_property('pre_query', 'tribe_is_event_organizer')
							|| jacqueline_storage_get_obj_property('pre_query', 'tribe_is_event_query')
							|| jacqueline_storage_get_obj_property('pre_query', 'tribe_is_past');
				}
			}
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'jacqueline_tribe_events_detect_inheritance_key' ) ) {
	//add_filter('jacqueline_filter_detect_inheritance_key',	'jacqueline_tribe_events_detect_inheritance_key', 9, 1);
	function jacqueline_tribe_events_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return jacqueline_is_tribe_events_page() ? 'tribe_events' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'jacqueline_tribe_events_get_blog_type' ) ) {
	//add_filter('jacqueline_filter_get_blog_type',	'jacqueline_tribe_events_get_blog_type', 10, 2);
	function jacqueline_tribe_events_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if (!is_search() && jacqueline_is_tribe_events_page()) {
			//$tribe_ecp = Tribe__Events__Main::instance();
			if (/*tribe_is_day()*/ isset($query->query_vars['eventDisplay']) && $query->query_vars['eventDisplay']=='day') 			$page = 'tribe_day';
			else if (/*tribe_is_month()*/ isset($query->query_vars['eventDisplay']) && $query->query_vars['eventDisplay']=='month')	$page = 'tribe_month';
			else if (is_single())																									$page = 'tribe_event';
			else if (/*tribe_is_event_venue()*/		isset($query->tribe_is_event_venue) && $query->tribe_is_event_venue)			$page = 'tribe_venue';
			else if (/*tribe_is_event_organizer()*/	isset($query->tribe_is_event_organizer) && $query->tribe_is_event_organizer)	$page = 'tribe_organizer';
			else if (/* tribe_is_event_category()*/	isset($query->tribe_is_event_category) && $query->tribe_is_event_category)		$page = 'tribe_category';
			else if (/*is_tax($tribe_ecp->get_event_taxonomy())*/ is_tag())															$page = 'tribe_tag';
			else if (isset($query->query_vars['eventDisplay']) && $query->query_vars['eventDisplay']=='upcoming')					$page = 'tribe_list';
			else																													$page = 'tribe';
		}
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'jacqueline_tribe_events_get_blog_title' ) ) {
	//add_filter('jacqueline_filter_get_blog_title',	'jacqueline_tribe_events_get_blog_title', 10, 2);
	function jacqueline_tribe_events_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( jacqueline_strpos($page, 'tribe')!==false ) {
			//return tribe_get_events_title();
			if ( $page == 'tribe_category' ) {
				$cat = get_term_by( 'slug', get_query_var( 'tribe_events_cat' ), 'tribe_events_cat', ARRAY_A);
				$title = $cat['name'];
			} else if ( $page == 'tribe_tag' ) {
				$title = sprintf( esc_html__( 'Tag: %s', 'jacqueline' ), single_tag_title( '', false ) );
			} else if ( $page == 'tribe_venue' ) {
				$title = sprintf( esc_html__( 'Venue: %s', 'jacqueline' ), tribe_get_venue());
			} else if ( $page == 'tribe_organizer' ) {
				$title = sprintf( esc_html__( 'Organizer: %s', 'jacqueline' ), tribe_get_organizer());
			} else if ( $page == 'tribe_day' ) {
				$title = sprintf( esc_html__( 'Daily Events: %s', 'jacqueline' ), date_i18n(tribe_get_date_format(true), strtotime(get_query_var( 'start_date' ))) );
			} else if ( $page == 'tribe_month' ) {
				$title = sprintf( esc_html__( 'Monthly Events: %s', 'jacqueline' ), date_i18n(tribe_get_option('monthAndYearFormat', 'F Y' ), strtotime(tribe_get_month_view_date())));
			} else if ( $page == 'tribe_event' ) {
				$title = jacqueline_get_post_title();
			} else {
				$title = esc_html__( 'All Events', 'jacqueline' );
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'jacqueline_tribe_events_get_stream_page_title' ) ) {
	//add_filter('jacqueline_filter_get_stream_page_title',	'jacqueline_tribe_events_get_stream_page_title', 9, 2);
	function jacqueline_tribe_events_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (jacqueline_strpos($page, 'tribe')!==false) {
			if (($page_id = jacqueline_tribe_events_get_stream_page_id(0, $page)) > 0)
				$title = jacqueline_get_post_title($page_id);
			else
				$title = esc_html__( 'All Events', 'jacqueline');
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'jacqueline_tribe_events_get_stream_page_id' ) ) {
	//add_filter('jacqueline_filter_get_stream_page_id',	'jacqueline_tribe_events_get_stream_page_id', 9, 2);
	function jacqueline_tribe_events_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (jacqueline_strpos($page, 'tribe')!==false) $id = jacqueline_get_template_page_id('tribe-events/default-template');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'jacqueline_tribe_events_get_stream_page_link' ) ) {
	//add_filter('jacqueline_filter_get_stream_page_link',	'jacqueline_tribe_events_get_stream_page_link', 9, 2);
	function jacqueline_tribe_events_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (jacqueline_strpos($page, 'tribe')!==false) $url = tribe_get_events_link();
		return $url;
	}
}

// Filter to return breadcrumbs links to the parent period
if ( !function_exists( 'jacqueline_tribe_events_get_period_links' ) ) {
	//add_filter('jacqueline_filter_get_period_links',	'jacqueline_tribe_events_get_period_links', 9, 3);
	function jacqueline_tribe_events_get_period_links($links, $page, $delimiter='') {
		if (!empty($links)) return $links;
		global $post;
		if ($page == 'tribe_day' && is_object($post))
			$links = '<a class="breadcrumbs_item cat_parent" href="' . tribe_get_gridview_link(false) . '">' . date_i18n(tribe_get_option('monthAndYearFormat', 'F Y' ), strtotime(tribe_get_month_view_date())) . '</a>';
		return $links;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'jacqueline_tribe_events_get_current_taxonomy' ) ) {
	//add_filter('jacqueline_filter_get_current_taxonomy',	'jacqueline_tribe_events_get_current_taxonomy', 9, 2);
	function jacqueline_tribe_events_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( jacqueline_strpos($page, 'tribe')!==false ) {
			$tax = Tribe__Events__Main::TAXONOMY;
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'jacqueline_tribe_events_is_taxonomy' ) ) {
	//add_filter('jacqueline_filter_is_taxonomy',	'jacqueline_tribe_events_is_taxonomy', 10, 2);
	function jacqueline_tribe_events_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else
			return $query && isset($query->tribe_is_event_category) && $query->tribe_is_event_category || is_tax(Tribe__Events__Main::TAXONOMY) ? Tribe__Events__Main::TAXONOMY : '';
	}
}

// Add custom post type into list
if ( !function_exists( 'jacqueline_tribe_events_list_post_types' ) ) {
	//add_filter('jacqueline_filter_list_post_types', 	'jacqueline_tribe_events_list_post_types', 10, 1);
	function jacqueline_tribe_events_list_post_types($list) {
		if (jacqueline_get_theme_option('show_tribe_events_in_blog')=='yes') {
			$list['tribe_events'] = esc_html__('Events', 'jacqueline');
	    }
		return $list;
	}
}



// Return previous month and year with published posts
if ( !function_exists( 'jacqueline_tribe_events_calendar_get_month_link' ) ) {
	//add_filter('jacqueline_filter_calendar_get_month_link',	'jacqueline_tribe_events_calendar_get_month_link', 9, 2);
	function jacqueline_tribe_events_calendar_get_month_link($link, $opt) {
		if (!empty($opt['posts_types']) && in_array(Tribe__Events__Main::POSTTYPE, $opt['posts_types']) && count($opt['posts_types'])==1) {
			$events = Tribe__Events__Main::instance();
			$link = $events->getLink('month', ($opt['year']).'-'.($opt['month']), null);			
		}
		return $link;
	}
}

// Return previous month and year with published posts
if ( !function_exists( 'jacqueline_tribe_events_calendar_get_prev_month' ) ) {
	//add_filter('jacqueline_filter_calendar_get_prev_month',	'jacqueline_tribe_events_calendar_get_prev_month', 9, 2);
	function jacqueline_tribe_events_calendar_get_prev_month($prev, $opt) {
		if (!empty($opt['posts_types']) && !in_array(Tribe__Events__Main::POSTTYPE, $opt['posts_types'])) return $prev;
		if (!empty($prev['done']) && in_array(Tribe__Events__Main::POSTTYPE, $prev['done'])) return $prev;
		$args = array(
			'suppress_filters' => true,
			'post_type' => Tribe__Events__Main::POSTTYPE,
			'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
			'posts_per_page' => 1,
			'ignore_sticky_posts' => true,
			'orderby' => 'meta_value',
			'meta_key' => '_EventStartDate',
			'order' => 'desc',
			'meta_query' => array(
				array(
					'key' => '_EventStartDate',
					'value' => ($opt['year']).'-'.($opt['month']).'-01',
					'compare' => '<',
					'type' => 'DATE'
				)
			)
		);
		$q = new WP_Query($args);
		$month = $year = 0;
		if ($q->have_posts()) {
			while ($q->have_posts()) { $q->the_post();
				$dt = strtotime(get_post_meta(get_the_ID(), '_EventStartDate', true));
				$year  = date('Y', $dt);
				$month = date('m', $dt);
			}
			wp_reset_postdata();
		}
		if (empty($prev) || ($year+$month > 0 && ($prev['year']+$prev['month']==0 || ($prev['year']).($prev['month']) < ($year).($month)))) {
			$prev['year'] = $year;
			$prev['month'] = $month;
		}
		if (empty($prev['done'])) $prev['done'] = array();
		$prev['done'][] = Tribe__Events__Main::POSTTYPE;
		return $prev;
	}
}

// Return next month and year with published posts
if ( !function_exists( 'jacqueline_tribe_events_calendar_get_next_month' ) ) {
	//add_filter('jacqueline_filter_calendar_get_next_month',	'jacqueline_tribe_events_calendar_get_next_month', 9, 2);
	function jacqueline_tribe_events_calendar_get_next_month($next, $opt) {
		if (!empty($opt['posts_types']) && !in_array(Tribe__Events__Main::POSTTYPE, $opt['posts_types'])) return $next;
		if (!empty($next['done']) && in_array(Tribe__Events__Main::POSTTYPE, $next['done'])) return $next;
		$args = array(
			'suppress_filters' => true,
			'post_type' => Tribe__Events__Main::POSTTYPE,
			'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
			'posts_per_page' => 1,
			'orderby' => 'meta_value',
			'ignore_sticky_posts' => true,
			'meta_key' => '_EventStartDate',
			'order' => 'asc',
			'meta_query' => array(
				array(
					'key' => '_EventStartDate',
					'value' => ($opt['year']).'-'.($opt['month']).'-'.($opt['last_day']).' 23:59:59',
					'compare' => '>',
					'type' => 'DATE'
				)
			)
		);
		$q = new WP_Query($args);
		$month = $year = 0;
		if ($q->have_posts()) {
			while ($q->have_posts()) { $q->the_post();
				$dt = strtotime(get_post_meta(get_the_ID(), '_EventStartDate', true));
				$year  = date('Y', $dt);
				$month = date('m', $dt);
			}
			wp_reset_postdata();
		}
		if (empty($next) || ($year+$month > 0 && ($next['year']+$next['month'] ==0 || ($next['year']).($next['month']) > ($year).($month)))) {
			$next['year'] = $year;
			$next['month'] = $month;
		}
		if (empty($next['done'])) $next['done'] = array();
		$next['done'][] = Tribe__Events__Main::POSTTYPE;
		return $next;
	}
}

// Return current month published posts
if ( !function_exists( 'jacqueline_tribe_events_calendar_get_curr_month_posts' ) ) {
	//add_filter('jacqueline_filter_calendar_get_curr_month_posts',	'jacqueline_tribe_events_calendar_get_curr_month_posts', 9, 2);
	function jacqueline_tribe_events_calendar_get_curr_month_posts($posts, $opt) {
		if (!empty($opt['posts_types']) && !in_array(Tribe__Events__Main::POSTTYPE, $opt['posts_types'])) return $posts;
		if (!empty($posts['done']) && in_array(Tribe__Events__Main::POSTTYPE, $posts['done'])) return $posts;
		$args = array(
			'suppress_filters' => true,
			'post_type' => Tribe__Events__Main::POSTTYPE,
			'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
			'posts_per_page' => -1,
			'ignore_sticky_posts' => true,
			'orderby' => 'meta_value',
			'meta_key' => '_EventStartDate',
			'order' => 'asc',
			'meta_query' => array(
				array(
					'key' => '_EventStartDate',
					'value' => array(($opt['year']).'-'.($opt['month']).'-01', ($opt['year']).'-'.($opt['month']).'-'.($opt['last_day']).' 23:59:59'),
					'compare' => 'BETWEEN',
					'type' => 'DATE'
				)
			)
		);
		$q = new WP_Query($args);
		if ($q->have_posts()) {
			if (empty($posts)) $posts = array();
			$events = Tribe__Events__Main::instance();
			while ($q->have_posts()) { $q->the_post();
				$dt = strtotime(get_post_meta(get_the_ID(), '_EventStartDate', true));
				$day = (int) date('d', $dt);
				$title = get_the_title();	//apply_filters('the_title', get_the_title());
				if (empty($posts[$day])) 
					$posts[$day] = array();
				if (empty($posts[$day]['link']) && count($opt['posts_types'])==1)
					$posts[$day]['link'] = $events->getLink('day', ($opt['year']).'-'.($opt['month']).'-'.($day), null);
				if (empty($posts[$day]['titles']))
					$posts[$day]['titles'] = $title;
				else
					$posts[$day]['titles'] = is_int($posts[$day]['titles']) ? $posts[$day]['titles']+1 : 2;
				if (empty($posts[$day]['posts'])) $posts[$day]['posts'] = array();
				$posts[$day]['posts'][] = array(
					'post_id' => get_the_ID(),
					'post_type' => get_post_type(),
					'post_date' => date(get_option('date_format'), $dt),
					'post_title' => $title,
					'post_link' => get_permalink()
				);
			}
			wp_reset_postdata();
		}
		if (empty($posts['done'])) $posts['done'] = array();
		$posts['done'][] = Tribe__Events__Main::POSTTYPE;
		return $posts;
	}
}



// Enqueue Tribe Events custom styles
if ( !function_exists( 'jacqueline_tribe_events_frontend_scripts' ) ) {
	//add_action( 'jacqueline_action_add_styles', 'jacqueline_tribe_events_frontend_scripts' );
	function jacqueline_tribe_events_frontend_scripts() {
		//global $wp_styles;
		//$wp_styles->done[] = 'tribe-events-custom-jquery-styles';
		wp_deregister_style('tribe-events-custom-jquery-styles');
		if (file_exists(jacqueline_get_file_dir('css/plugin.tribe-events.css')))
			jacqueline_enqueue_style( 'jacqueline-plugin.tribe-events-style',  jacqueline_get_file_url('css/plugin.tribe-events.css'), array(), null );
	}
}




// Before main content
if ( !function_exists( 'jacqueline_tribe_events_wrapper_start' ) ) {
	//add_filter('tribe_events_before_html', 'jacqueline_tribe_events_wrapper_start');
	function jacqueline_tribe_events_wrapper_start($html) {
		return '
		<section class="post tribe_events_wrapper">
			<article class="post_content">
		' . ($html);
	}
}

// After main content
if ( !function_exists( 'jacqueline_tribe_events_wrapper_end' ) ) {
	//add_filter('tribe_events_after_html', 'jacqueline_tribe_events_wrapper_end');
	function jacqueline_tribe_events_wrapper_end($html) {
		return $html . '
			</article><!-- .post_content -->
		</section>
		';
	}
}

// Add sorting parameter in query arguments
if (!function_exists('jacqueline_tribe_events_add_sort_order')) {
	function jacqueline_tribe_events_add_sort_order($q, $orderby, $order) {
		if ($orderby == 'event_date') {
			$q['orderby'] = 'meta_value';
			$q['meta_key'] = '_EventStartDate';
		}
		return $q;
	}
}

// Return false if current plugin not need theme orderby setting
if ( !function_exists( 'jacqueline_tribe_events_orderby_need' ) ) {
	//add_filter('jacqueline_filter_orderby_need',	'jacqueline_tribe_events_orderby_need', 9, 1);
	function jacqueline_tribe_events_orderby_need($need) {
		if ($need == false || jacqueline_storage_empty('pre_query'))
			return $need;
		else {
			return ! ( jacqueline_storage_get_obj_property('pre_query', 'tribe_is_event')
					|| jacqueline_storage_get_obj_property('pre_query', 'tribe_is_multi_posttype')
					|| jacqueline_storage_get_obj_property('pre_query', 'tribe_is_event_category')
					|| jacqueline_storage_get_obj_property('pre_query', 'tribe_is_event_venue')
					|| jacqueline_storage_get_obj_property('pre_query', 'tribe_is_event_organizer')
					|| jacqueline_storage_get_obj_property('pre_query', 'tribe_is_event_query')
					|| jacqueline_storage_get_obj_property('pre_query', 'tribe_is_past')
					);
		}
	}
}


/* Query params to show Events in blog stream
-------------------------------------------------------------------------- */

// Pre query: Join tables into main query
if ( !function_exists( 'jacqueline_tribe_events_posts_join' ) ) {
	// add_action( 'posts_join', 'jacqueline_tribe_events_posts_join', 10, 2 );
	function jacqueline_tribe_events_posts_join($join_sql, $query) {
		if (jacqueline_get_theme_option('show_tribe_events_in_blog')=='yes' && !is_admin() && $query->is_main_query()) {
			if ($query->is_day || $query->is_month || $query->is_year || $query->is_archive || $query->is_posts_page) {
				global $wpdb;
				$join_sql .= " LEFT JOIN " . esc_sql($wpdb->postmeta) . " AS _tribe_events_meta ON " . esc_sql($wpdb->posts) . ".ID = _tribe_events_meta.post_id AND  _tribe_events_meta.meta_key = '_EventStartDate'";
			}
		}
		return $join_sql;
	}
}

// Pre query: Join tables into archives widget query
if ( !function_exists( 'jacqueline_tribe_events_getarchives_join' ) ) {
	// add_action( 'getarchives_join', 'jacqueline_tribe_events_getarchives_join', 10, 2 );
	function jacqueline_tribe_events_getarchives_join($join_sql, $r) {
		if (jacqueline_get_theme_option('show_tribe_events_in_blog')=='yes') {
			global $wpdb;
			$join_sql .= " LEFT JOIN " . esc_sql($wpdb->postmeta) . " AS _tribe_events_meta ON " . esc_sql($wpdb->posts) . ".ID = _tribe_events_meta.post_id AND  _tribe_events_meta.meta_key = '_EventStartDate'";
		}
		return $join_sql;
	}
}

// Pre query: Where section into main query
if ( !function_exists( 'jacqueline_tribe_events_posts_where' ) ) {
	// add_action( 'posts_where', 'jacqueline_tribe_events_posts_where', 10, 2 );
	function jacqueline_tribe_events_posts_where($where_sql, $query) {
		if (jacqueline_get_theme_option('show_tribe_events_in_blog')=='yes' && !is_admin() && $query->is_main_query()) {
			if ($query->is_day || $query->is_month || $query->is_year || $query->is_archive || $query->is_posts_page) {
				global $wpdb;
				$where_sql .= " OR (1=1";
				// Posts status
				if ((!isset($_REQUEST['preview']) || $_REQUEST['preview']!='true') && (!isset($_REQUEST['vc_editable']) || $_REQUEST['vc_editable']!='true')) {
					if (current_user_can('read_private_pages') && current_user_can('read_private_posts'))
						$where_sql .= " AND (" . esc_sql($wpdb->posts) . ".post_status='publish' OR " . esc_sql($wpdb->posts) . ".post_status='private')";
					else
						$where_sql .= " AND " . esc_sql($wpdb->posts) . ".post_status='publish'";
				}
				// Posts type and date
				$dt = $query->get('m');
				$y = $query->get('year');
				if (empty($y)) $y = (int) jacqueline_substr($dt, 0, 4);
				$where_sql .= " AND " . esc_sql($wpdb->posts) . ".post_type='".esc_sql(Tribe__Events__Main::POSTTYPE)."' AND YEAR(_tribe_events_meta.meta_value)=".esc_sql($y);
				if ($query->is_month || $query->is_day) {
					$m = $query->get('monthnum');
					if (empty($m)) $m = (int) jacqueline_substr($dt, 4, 2);
					$where_sql .= " AND MONTH(_tribe_events_meta.meta_value)=".esc_sql($m);
				}
				if ($query->is_day) {
					$d = $query->get('day');
					if (empty($d)) $d = (int) jacqueline_substr($dt, 6, 2);
					$where_sql .= " AND DAYOFMONTH(_tribe_events_meta.meta_value)=".esc_sql($d);
				}
				$where_sql .= ')';
			}
		}
		return $where_sql;
	}
}

// Pre query: Where section into archives widget query
if ( !function_exists( 'jacqueline_tribe_events_getarchives_where' ) ) {
	// add_action( 'getarchives_where', 'jacqueline_tribe_events_getarchives_where', 10, 2 );
	function jacqueline_tribe_events_getarchives_where($where_sql, $r) {
		if (jacqueline_get_theme_option('show_tribe_events_in_blog')=='yes') {
			global $wpdb;
			// Posts type and date
			$where_sql .= " OR " . esc_sql($wpdb->posts) . ".post_type='".esc_sql(Tribe__Events__Main::POSTTYPE)."'";
		}
		return $where_sql;
	}
}

// Return tribe_events start date instead post publish date
if ( !function_exists( 'jacqueline_tribe_events_post_date' ) ) {
	//add_filter('jacqueline_filter_post_date', 'jacqueline_tribe_events_post_date', 9, 3);
	function jacqueline_tribe_events_post_date($post_date, $post_id, $post_type) {
		if ($post_type == Tribe__Events__Main::POSTTYPE) {
			$post_date = get_post_meta($post_id, '_EventStartDate', true);
		}
		return $post_date;
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'jacqueline_tribe_events_required_plugins' ) ) {
	//add_filter('jacqueline_filter_required_plugins',	'jacqueline_tribe_events_required_plugins');
	function jacqueline_tribe_events_required_plugins($list=array()) {
		if (in_array('tribe_events', jacqueline_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> 'Tribe Events Calendar',
					'slug' 		=> 'the-events-calendar',
					'required' 	=> false
				);

		return $list;
	}
}

// Add Google API key to the map's link
 if ( !function_exists( 'jacqueline_tribe_events_google_maps_api' ) ) {
//add_filter('tribe_events_google_maps_api', 'jacqueline_tribe_events_google_maps_api');
	function jacqueline_tribe_events_google_maps_api($url) {
		$api_key = jacqueline_get_theme_option('api_google');
		if ($api_key) {
			 $url = jacqueline_add_to_url($url, array(
			  'key' => $api_key
			 ));
		}
		return $url;
	   }
}
  
  
// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'jacqueline_tribe_events_importer_required_plugins' ) ) {
	//add_filter( 'jacqueline_filter_importer_required_plugins',	'jacqueline_tribe_events_importer_required_plugins', 10, 2 );
	function jacqueline_tribe_events_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('tribe_events', jacqueline_storage_get('required_plugins')) && !jacqueline_exists_tribe_events() )
		if (jacqueline_strpos($list, 'tribe_events')!==false && !jacqueline_exists_tribe_events() )
			$not_installed .= '<br>Tribe Events Calendar';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'jacqueline_tribe_events_importer_set_options' ) ) {
	//add_filter( 'jacqueline_filter_importer_options',	'jacqueline_tribe_events_importer_set_options' );
	function jacqueline_tribe_events_importer_set_options($options=array()) {
		if ( in_array('tribe_events', jacqueline_storage_get('required_plugins')) && jacqueline_exists_tribe_events() ) {
			// Add slugs to export options for this plugin
			$options['additional_options'][] = 'tribe_events_calendar_options';
		}
		return $options;
	}
}

?>