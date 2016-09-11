<?php
/**
 * Jacqueline Framework: Clients support
 *
 * @package	jacqueline
 * @since	jacqueline 1.0
 */

// Theme init
if (!function_exists('jacqueline_clients_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_clients_theme_setup', 1 );
	function jacqueline_clients_theme_setup() {

		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('jacqueline_filter_get_blog_type',			'jacqueline_clients_get_blog_type', 9, 2);
		add_filter('jacqueline_filter_get_blog_title',		'jacqueline_clients_get_blog_title', 9, 2);
		add_filter('jacqueline_filter_get_current_taxonomy',	'jacqueline_clients_get_current_taxonomy', 9, 2);
		add_filter('jacqueline_filter_is_taxonomy',			'jacqueline_clients_is_taxonomy', 9, 2);
		add_filter('jacqueline_filter_get_stream_page_title',	'jacqueline_clients_get_stream_page_title', 9, 2);
		add_filter('jacqueline_filter_get_stream_page_link',	'jacqueline_clients_get_stream_page_link', 9, 2);
		add_filter('jacqueline_filter_get_stream_page_id',	'jacqueline_clients_get_stream_page_id', 9, 2);
		add_filter('jacqueline_filter_query_add_filters',		'jacqueline_clients_query_add_filters', 9, 2);
		add_filter('jacqueline_filter_detect_inheritance_key','jacqueline_clients_detect_inheritance_key', 9, 1);

		// Extra column for clients lists
		if (jacqueline_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-clients_columns',			'jacqueline_post_add_options_column', 9);
			add_filter('manage_clients_posts_custom_column',	'jacqueline_post_fill_options_column', 9, 2);
		}

		// Registar shortcodes [trx_clients] and [trx_clients_item] in the shortcodes list
		add_action('jacqueline_action_shortcodes_list',		'jacqueline_clients_reg_shortcodes');
		if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
			add_action('jacqueline_action_shortcodes_list_vc','jacqueline_clients_reg_shortcodes_vc');
		
		// Add supported data types
		jacqueline_theme_support_pt('clients');
		jacqueline_theme_support_tx('clients_group');
	}
}

if ( !function_exists( 'jacqueline_clients_settings_theme_setup2' ) ) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_clients_settings_theme_setup2', 3 );
	function jacqueline_clients_settings_theme_setup2() {
		// Add post type 'clients' and taxonomy 'clients_group' into theme inheritance list
		jacqueline_add_theme_inheritance( array('clients' => array(
			'stream_template' => 'blog-clients',
			'single_template' => 'single-client',
			'taxonomy' => array('clients_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('clients'),
			'override' => 'custom'
			) )
		);
	}
}


if (!function_exists('jacqueline_clients_after_theme_setup')) {
	add_action( 'jacqueline_action_after_init_theme', 'jacqueline_clients_after_theme_setup' );
	function jacqueline_clients_after_theme_setup() {
		// Update fields in the meta box
		if (jacqueline_storage_get_array('post_meta_box', 'page')=='clients') {
			// Meta box fields
			jacqueline_storage_set_array('post_meta_box', 'title', esc_html__('Client Options', 'jacqueline'));
			jacqueline_storage_set_array('post_meta_box', 'fields', array(
				"mb_partition_clients" => array(
					"title" => esc_html__('Clients', 'jacqueline'),
					"override" => "page,post,custom",
					"divider" => false,
					"icon" => "iconadmin-users",
					"type" => "partition"),
				"mb_info_clients_1" => array(
					"title" => esc_html__('Client details', 'jacqueline'),
					"override" => "page,post,custom",
					"divider" => false,
					"desc" => wp_kses_data( __('In this section you can put details for this client', 'jacqueline') ),
					"class" => "client_meta",
					"type" => "info"),
				"client_name" => array(
					"title" => esc_html__('Contact name',  'jacqueline'),
					"desc" => wp_kses_data( __("Name of the contacts manager", 'jacqueline') ),
					"override" => "page,post,custom",
					"class" => "client_name",
					"std" => '',
					"type" => "text"),
				"client_position" => array(
					"title" => esc_html__('Position',  'jacqueline'),
					"desc" => wp_kses_data( __("Position of the contacts manager", 'jacqueline') ),
					"override" => "page,post,custom",
					"class" => "client_position",
					"std" => '',
					"type" => "text"),
				"client_show_link" => array(
					"title" => esc_html__('Show link',  'jacqueline'),
					"desc" => wp_kses_data( __("Show link to client page", 'jacqueline') ),
					"override" => "page,post,custom",
					"class" => "client_show_link",
					"std" => "no",
					"options" => jacqueline_get_list_yesno(),
					"type" => "switch"),
				"client_link" => array(
					"title" => esc_html__('Link',  'jacqueline'),
					"desc" => wp_kses_data( __("URL of the client's site. If empty - use link to this page", 'jacqueline') ),
					"override" => "page,post,custom",
					"class" => "client_link",
					"std" => '',
					"type" => "text")
				)
			);
		}
	}
}


// Return true, if current page is clients page
if ( !function_exists( 'jacqueline_is_clients_page' ) ) {
	function jacqueline_is_clients_page() {
		$is = in_array(jacqueline_storage_get('page_template'), array('blog-clients', 'single-client'));
		if (!$is) {
			if (!jacqueline_storage_empty('pre_query'))
				$is = jacqueline_storage_call_obj_method('pre_query', 'get', 'post_type')=='clients'
						|| jacqueline_storage_call_obj_method('pre_query', 'is_tax', 'clients_group') 
						|| (jacqueline_storage_call_obj_method('pre_query', 'is_page') 
							&& ($id=jacqueline_get_template_page_id('blog-clients')) > 0 
							&& $id==jacqueline_storage_get_obj_property('pre_query', 'queried_object_id', 0)
							);
			else
				$is = get_query_var('post_type')=='clients' 
						|| is_tax('clients_group') 
						|| (is_page() && ($id=jacqueline_get_template_page_id('blog-clients')) > 0 && $id==get_the_ID());
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'jacqueline_clients_detect_inheritance_key' ) ) {
	//add_filter('jacqueline_filter_detect_inheritance_key',	'jacqueline_clients_detect_inheritance_key', 9, 1);
	function jacqueline_clients_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return jacqueline_is_clients_page() ? 'clients' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'jacqueline_clients_get_blog_type' ) ) {
	//add_filter('jacqueline_filter_get_blog_type',	'jacqueline_clients_get_blog_type', 9, 2);
	function jacqueline_clients_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('clients_group') || is_tax('clients_group'))
			$page = 'clients_category';
		else if ($query && $query->get('post_type')=='clients' || get_query_var('post_type')=='clients')
			$page = $query && $query->is_single() || is_single() ? 'clients_item' : 'clients';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'jacqueline_clients_get_blog_title' ) ) {
	//add_filter('jacqueline_filter_get_blog_title',	'jacqueline_clients_get_blog_title', 9, 2);
	function jacqueline_clients_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( jacqueline_strpos($page, 'clients')!==false ) {
			if ( $page == 'clients_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'clients_group' ), 'clients_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'clients_item' ) {
				$title = jacqueline_get_post_title();
			} else {
				$title = esc_html__('All clients', 'jacqueline');
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'jacqueline_clients_get_stream_page_title' ) ) {
	//add_filter('jacqueline_filter_get_stream_page_title',	'jacqueline_clients_get_stream_page_title', 9, 2);
	function jacqueline_clients_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (jacqueline_strpos($page, 'clients')!==false) {
			if (($page_id = jacqueline_clients_get_stream_page_id(0, $page=='clients' ? 'blog-clients' : $page)) > 0)
				$title = jacqueline_get_post_title($page_id);
			else
				$title = esc_html__('All clients', 'jacqueline');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'jacqueline_clients_get_stream_page_id' ) ) {
	//add_filter('jacqueline_filter_get_stream_page_id',	'jacqueline_clients_get_stream_page_id', 9, 2);
	function jacqueline_clients_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (jacqueline_strpos($page, 'clients')!==false) $id = jacqueline_get_template_page_id('blog-clients');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'jacqueline_clients_get_stream_page_link' ) ) {
	//add_filter('jacqueline_filter_get_stream_page_link',	'jacqueline_clients_get_stream_page_link', 9, 2);
	function jacqueline_clients_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (jacqueline_strpos($page, 'clients')!==false) {
			$id = jacqueline_get_template_page_id('blog-clients');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'jacqueline_clients_get_current_taxonomy' ) ) {
	//add_filter('jacqueline_filter_get_current_taxonomy',	'jacqueline_clients_get_current_taxonomy', 9, 2);
	function jacqueline_clients_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( jacqueline_strpos($page, 'clients')!==false ) {
			$tax = 'clients_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'jacqueline_clients_is_taxonomy' ) ) {
	//add_filter('jacqueline_filter_is_taxonomy',	'jacqueline_clients_is_taxonomy', 9, 2);
	function jacqueline_clients_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('clients_group')!='' || is_tax('clients_group') ? 'clients_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'jacqueline_clients_query_add_filters' ) ) {
	//add_filter('jacqueline_filter_query_add_filters',	'jacqueline_clients_query_add_filters', 9, 2);
	function jacqueline_clients_query_add_filters($args, $filter) {
		if ($filter == 'clients') {
			$args['post_type'] = 'clients';
		}
		return $args;
	}
}





// ---------------------------------- [trx_clients] ---------------------------------------


if ( !function_exists( 'jacqueline_sc_clients' ) ) {
	function jacqueline_sc_clients($atts, $content=null){	
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "clients-1",
			"columns" => 4,
			"slider" => "no",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"custom" => "no",
			"ids" => "",
			"cat" => "",
			"count" => 4,
			"offset" => "",
			"orderby" => "title",
			"order" => "asc",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'jacqueline'),
			"link" => '',
			"scheme" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));

		if (empty($id)) $id = "sc_clients_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && jacqueline_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);

		$css .= ($css ? '; ' : '') . jacqueline_get_css_position_from_values($top, $right, $bottom, $left);

		$ws = jacqueline_get_css_dimensions_from_values($width);
		$hs = jacqueline_get_css_dimensions_from_values('', $height);
		$css .= ($hs) . ($ws);

		if (jacqueline_param_is_on($slider)) jacqueline_enqueue_slider('swiper');
	
		$columns = max(1, min(12, $columns));
		$count = max(1, (int) $count);
		if (jacqueline_param_is_off($custom) && $count < $columns) $columns = $count;
		jacqueline_storage_set('sc_clients_data', array(
			'id'=>$id,
            'style'=>$style,
            'counter'=>0,
            'columns'=>$columns,
            'slider'=>$slider,
            'css_wh'=>$ws . $hs
            )
        );

		$output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '') 
						. ' class="sc_clients_wrap'
						. ($scheme && !jacqueline_param_is_off($scheme) && !jacqueline_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						.'">'
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_clients sc_clients_style_'.esc_attr($style)
							. ' ' . esc_attr(jacqueline_get_template_property($style, 'container_classes'))
							. (!empty($class) ? ' '.esc_attr($class) : '')
						.'"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. (!jacqueline_param_is_off($animation) ? ' data-animation="'.esc_attr(jacqueline_get_animation_classes($animation)).'"' : '')
					. '>'
					. (!empty($subtitle) ? '<h6 class="sc_clients_subtitle sc_item_subtitle">' . trim(jacqueline_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_clients_title sc_item_title">' . trim(jacqueline_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_clients_descr sc_item_descr">' . trim(jacqueline_strmacros($description)) . '</div>' : '')
					. (jacqueline_param_is_on($slider) 
						? ('<div class="sc_slider_swiper swiper-slider-container'
										. ' ' . esc_attr(jacqueline_get_slider_controls_classes($controls))
										. (jacqueline_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
										. ($hs ? ' sc_slider_height_fixed' : '')
										. '"'
									. (!empty($width) && jacqueline_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
									. (!empty($height) && jacqueline_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
									. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
									. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
									. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
									. ' data-slides-min-width="' . ($style=='clients-1' ? 100 : 220) . '"'
								. '>'
							. '<div class="slides swiper-wrapper">')
						: ($columns > 1 
							? '<div class="sc_columns columns_wrap">' 
							: '')
						);
	
		$content = do_shortcode($content);
	
		if (jacqueline_param_is_on($custom) && $content) {
			$output .= $content;
		} else {
			global $post;
	
			if (!empty($ids)) {
				$posts = explode(',', $ids);
				$count = count($posts);
			}
			
			$args = array(
				'post_type' => 'clients',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
			);
		
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
		
			$args = jacqueline_query_add_sort_order($args, $orderby, $order);
			$args = jacqueline_query_add_posts_and_cats($args, $ids, 'clients', $cat, 'clients_group');

			$query = new WP_Query( $args );
	
			$post_number = 0;

			while ( $query->have_posts() ) { 
				$query->the_post();
				$post_number++;
				$args = array(
					'layout' => $style,
					'show' => false,
					'number' => $post_number,
					'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
					"descr" => jacqueline_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : '')),
					"orderby" => $orderby,
					'content' => false,
					'terms_list' => false,
					'columns_count' => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$post_data = jacqueline_get_post_data($args);
				$post_meta = get_post_meta($post_data['post_id'], jacqueline_storage_get('options_prefix') . '_post_options', true);
				$thumb_sizes = jacqueline_get_thumb_sizes(array('layout' => $style));
				$args['client_name'] = $post_meta['client_name'];
				$args['client_position'] = $post_meta['client_position'];
				$args['client_image'] = $post_data['post_thumb'];
				$args['client_link'] = jacqueline_param_is_on('client_show_link')
					? (!empty($post_meta['client_link']) ? $post_meta['client_link'] : $post_data['post_link'])
					: '';
				$output .= jacqueline_show_post_layout($args, $post_data);
			}
			wp_reset_postdata();
		}
	
		if (jacqueline_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>'
				. '</div>';
		} else if ($columns > 1) {
			$output .= '</div>';
		}

		$output .= (!empty($link) ? '<div class="sc_clients_button sc_item_button">'.jacqueline_do_shortcode('[trx_button link="'.esc_url($link).'" ]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
				. '</div><!-- /.sc_clients -->'
			. '</div><!-- /.sc_clients_wrap -->';
	
		// Add template specific scripts and styles
		do_action('jacqueline_action_blog_scripts', $style);
	
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_clients', $atts, $content);
	}
	jacqueline_require_shortcode('trx_clients', 'jacqueline_sc_clients');
}


if ( !function_exists( 'jacqueline_sc_clients_item' ) ) {
	function jacqueline_sc_clients_item($atts, $content=null) {
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts( array(
			// Individual params
			"name" => "",
			"position" => "",
			"image" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => ""
		), $atts)));
	
		jacqueline_storage_inc_array('sc_clients_data', 'counter');
	
		$id = $id ? $id : (jacqueline_storage_get_array('sc_clients_data', 'id') ? jacqueline_storage_get_array('sc_clients_data', 'id') . '_' . jacqueline_storage_get_array('sc_clients_data', 'counter') : '');
	
		$descr = trim(chop(do_shortcode($content)));
	
		$thumb_sizes = jacqueline_get_thumb_sizes(array('layout' => jacqueline_storage_get_array('sc_clients_data', 'style')));

		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		$image = jacqueline_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);

		$post_data = array(
			'post_title' => $name,
			'post_excerpt' => $descr
		);
		$args = array(
			'layout' => jacqueline_storage_get_array('sc_clients_data', 'style'),
			'number' => jacqueline_storage_get_array('sc_clients_data', 'counter'),
			'columns_count' => jacqueline_storage_get_array('sc_clients_data', 'columns'),
			'slider' => jacqueline_storage_get_array('sc_clients_data', 'slider'),
			'show' => false,
			'descr'  => 0,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => $animation,
			'tag_css' => $css,
			'tag_css_wh' => jacqueline_storage_get_array('sc_clients_data', 'css_wh'),
			'client_position' => $position,
			'client_link' => $link,
			'client_image' => $image
		);
		$output = jacqueline_show_post_layout($args, $post_data);
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_clients_item', $atts, $content);
	}
	jacqueline_require_shortcode('trx_clients_item', 'jacqueline_sc_clients_item');
}
// ---------------------------------- [/trx_clients] ---------------------------------------



// Add [trx_clients] and [trx_clients_item] in the shortcodes list
if (!function_exists('jacqueline_clients_reg_shortcodes')) {
	//add_filter('jacqueline_action_shortcodes_list',	'jacqueline_clients_reg_shortcodes');
	function jacqueline_clients_reg_shortcodes() {
		if (jacqueline_storage_isset('shortcodes')) {

			$users = jacqueline_get_list_users();
			$members = jacqueline_get_list_posts(false, array(
				'post_type'=>'clients',
				'orderby'=>'title',
				'order'=>'asc',
				'return'=>'title'
				)
			);
			$clients_groups = jacqueline_get_list_terms(false, 'clients_group');
			$clients_styles = jacqueline_get_list_templates('clients');
			$controls 		= jacqueline_get_list_slider_controls();

			jacqueline_sc_map_after('trx_chat', array(

				// Clients
				"trx_clients" => array(
					"title" => esc_html__("Clients", 'jacqueline'),
					"desc" => wp_kses_data( __("Insert clients list in your page (post)", 'jacqueline') ),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", 'jacqueline'),
							"desc" => wp_kses_data( __("Title for the block", 'jacqueline') ),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", 'jacqueline'),
							"desc" => wp_kses_data( __("Subtitle for the block", 'jacqueline') ),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", 'jacqueline'),
							"desc" => wp_kses_data( __("Short description for the block", 'jacqueline') ),
							"value" => "",
							"type" => "textarea"
						),/*
						"style" => array(
							"title" => esc_html__("Clients style", 'jacqueline'),
							"desc" => wp_kses_data( __("Select style to display clients list", 'jacqueline') ),
							"value" => "clients-1",
							"type" => "select",
							"options" => $clients_styles
						),*/
						"columns" => array(
							"title" => esc_html__("Columns", 'jacqueline'),
							"desc" => wp_kses_data( __("How many columns use to show clients", 'jacqueline') ),
							"value" => 4,
							"min" => 2,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", 'jacqueline'),
							"desc" => wp_kses_data( __("Select color scheme for this block", 'jacqueline') ),
							"value" => "",
							"type" => "checklist",
							"options" => jacqueline_get_sc_param('schemes')
						),
						"slider" => array(
							"title" => esc_html__("Slider", 'jacqueline'),
							"desc" => wp_kses_data( __("Use slider to show clients", 'jacqueline') ),
							"value" => "no",
							"type" => "switch",
							"options" => jacqueline_get_sc_param('yes_no')
						),
						"controls" => array(
							"title" => esc_html__("Controls", 'jacqueline'),
							"desc" => wp_kses_data( __("Slider controls style and position", 'jacqueline') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"divider" => true,
							"value" => "no",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $controls
						),
						"slides_space" => array(
							"title" => esc_html__("Space between slides", 'jacqueline'),
							"desc" => wp_kses_data( __("Size of space (in px) between slides", 'jacqueline') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => esc_html__("Slides change interval", 'jacqueline'),
							"desc" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'jacqueline') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", 'jacqueline'),
							"desc" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'jacqueline') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => jacqueline_get_sc_param('yes_no')
						),
						"custom" => array(
							"title" => esc_html__("Custom", 'jacqueline'),
							"desc" => wp_kses_data( __("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", 'jacqueline') ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => jacqueline_get_sc_param('yes_no')
						),
						"cat" => array(
							"title" => esc_html__("Categories", 'jacqueline'),
							"desc" => wp_kses_data( __("Select categories (groups) to show team members. If empty - select team members from any category (group) or from IDs list", 'jacqueline') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => jacqueline_array_merge(array(0 => esc_html__('- Select category -', 'jacqueline')), $clients_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", 'jacqueline'),
							"desc" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'jacqueline') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 4,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", 'jacqueline'),
							"desc" => wp_kses_data( __("Skip posts before select next part.", 'jacqueline') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", 'jacqueline'),
							"desc" => wp_kses_data( __("Select desired posts sorting method", 'jacqueline') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "title",
							"type" => "select",
							"options" => jacqueline_get_sc_param('sorting')
						),
						"order" => array(
							"title" => esc_html__("Post order", 'jacqueline'),
							"desc" => wp_kses_data( __("Select desired posts order", 'jacqueline') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "asc",
							"type" => "switch",
							"size" => "big",
							"options" => jacqueline_get_sc_param('ordering')
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", 'jacqueline'),
							"desc" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'jacqueline') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => esc_html__("Button URL", 'jacqueline'),
							"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'jacqueline') ),
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", 'jacqueline'),
							"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'jacqueline') ),
							"value" => "",
							"type" => "text"
						),
						"width" => jacqueline_shortcodes_width(),
						"height" => jacqueline_shortcodes_height(),
						"top" => jacqueline_get_sc_param('top'),
						"bottom" => jacqueline_get_sc_param('bottom'),
						"left" => jacqueline_get_sc_param('left'),
						"right" => jacqueline_get_sc_param('right'),
						"id" => jacqueline_get_sc_param('id'),
						"class" => jacqueline_get_sc_param('class'),
						"animation" => jacqueline_get_sc_param('animation'),
						"css" => jacqueline_get_sc_param('css')
					),
					"children" => array(
						"name" => "trx_clients_item",
						"title" => esc_html__("Client", 'jacqueline'),
						"desc" => wp_kses_data( __("Single client (custom parameters)", 'jacqueline') ),
						"container" => true,
						"params" => array(
							"name" => array(
								"title" => esc_html__("Name", 'jacqueline'),
								"desc" => wp_kses_data( __("Client's name", 'jacqueline') ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"position" => array(
								"title" => esc_html__("Position", 'jacqueline'),
								"desc" => wp_kses_data( __("Client's position", 'jacqueline') ),
								"value" => "",
								"type" => "text"
							),
							"link" => array(
								"title" => esc_html__("Link", 'jacqueline'),
								"desc" => wp_kses_data( __("Link on client's personal page", 'jacqueline') ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"image" => array(
								"title" => esc_html__("Image", 'jacqueline'),
								"desc" => wp_kses_data( __("Client's image", 'jacqueline') ),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"_content_" => array(
								"title" => esc_html__("Description", 'jacqueline'),
								"desc" => wp_kses_data( __("Client's short description", 'jacqueline') ),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => jacqueline_get_sc_param('id'),
							"class" => jacqueline_get_sc_param('class'),
							"animation" => jacqueline_get_sc_param('animation'),
							"css" => jacqueline_get_sc_param('css')
						)
					)
				)

			));
		}
	}
}


// Add [trx_clients] and [trx_clients_item] in the VC shortcodes list
if (!function_exists('jacqueline_clients_reg_shortcodes_vc')) {
	//add_filter('jacqueline_action_shortcodes_list_vc',	'jacqueline_clients_reg_shortcodes_vc');
	function jacqueline_clients_reg_shortcodes_vc() {

		$clients_groups = jacqueline_get_list_terms(false, 'clients_group');
		$clients_styles = jacqueline_get_list_templates('clients');
		$controls		= jacqueline_get_list_slider_controls();

		// Clients
		vc_map( array(
				"base" => "trx_clients",
				"name" => esc_html__("Clients", 'jacqueline'),
				"description" => wp_kses_data( __("Insert clients list", 'jacqueline') ),
				"category" => esc_html__('Content', 'jacqueline'),
				'icon' => 'icon_trx_clients',
				"class" => "trx_sc_columns trx_sc_clients",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_clients_item'),
				"params" => array(  
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", 'jacqueline'),
						"description" => wp_kses_data( __("Select color scheme for this block", 'jacqueline') ),
						"class" => "",
						"value" => array_flip(jacqueline_get_sc_param('schemes')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", 'jacqueline'),
						"description" => wp_kses_data( __("Use slider to show testimonials", 'jacqueline') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'jacqueline'),
						"class" => "",
						"std" => "no",
						"value" => array_flip(jacqueline_get_sc_param('yes_no')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Controls", 'jacqueline'),
						"description" => wp_kses_data( __("Slider controls style and position", 'jacqueline') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'jacqueline'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"std" => "no",
						"value" => array_flip($controls),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slides_space",
						"heading" => esc_html__("Space between slides", 'jacqueline'),
						"description" => wp_kses_data( __("Size of space (in px) between slides", 'jacqueline') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'jacqueline'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Slides change interval", 'jacqueline'),
						"description" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'jacqueline') ),
						"group" => esc_html__('Slider', 'jacqueline'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Autoheight", 'jacqueline'),
						"description" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'jacqueline') ),
						"group" => esc_html__('Slider', 'jacqueline'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", 'jacqueline'),
						"description" => wp_kses_data( __("Allow get clients from inner shortcodes (custom) or get it from specified group (cat)", 'jacqueline') ),
						"class" => "",
						"value" => array("Custom clients" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'jacqueline'),
						"description" => wp_kses_data( __("Title for the block", 'jacqueline') ),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'jacqueline'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", 'jacqueline'),
						"description" => wp_kses_data( __("Subtitle for the block", 'jacqueline') ),
						"group" => esc_html__('Captions', 'jacqueline'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", 'jacqueline'),
						"description" => wp_kses_data( __("Description for the block", 'jacqueline') ),
						"group" => esc_html__('Captions', 'jacqueline'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories", 'jacqueline'),
						"description" => wp_kses_data( __("Select category to show clients. If empty - select clients from any category (group) or from IDs list", 'jacqueline') ),
						"group" => esc_html__('Query', 'jacqueline'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(jacqueline_array_merge(array(0 => esc_html__('- Select category -', 'jacqueline')), $clients_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'jacqueline'),
						"description" => wp_kses_data( __("How many columns use to show clients", 'jacqueline') ),
						"group" => esc_html__('Query', 'jacqueline'),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", 'jacqueline'),
						"description" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'jacqueline') ),
						"group" => esc_html__('Query', 'jacqueline'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", 'jacqueline'),
						"description" => wp_kses_data( __("Skip posts before select next part.", 'jacqueline') ),
						"group" => esc_html__('Query', 'jacqueline'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", 'jacqueline'),
						"description" => wp_kses_data( __("Select desired posts sorting method", 'jacqueline') ),
						"group" => esc_html__('Query', 'jacqueline'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"std" => "title",
						"class" => "",
						"value" => array_flip(jacqueline_get_sc_param('sorting')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", 'jacqueline'),
						"description" => wp_kses_data( __("Select desired posts order", 'jacqueline') ),
						"group" => esc_html__('Query', 'jacqueline'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"std" => "asc",
						"class" => "",
						"value" => array_flip(jacqueline_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("client's IDs list", 'jacqueline'),
						"description" => wp_kses_data( __("Comma separated list of client's ID. If set - parameters above (category, count, order, etc.)  are ignored!", 'jacqueline') ),
						"group" => esc_html__('Query', 'jacqueline'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button URL", 'jacqueline'),
						"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'jacqueline') ),
						"group" => esc_html__('Captions', 'jacqueline'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => esc_html__("Button caption", 'jacqueline'),
						"description" => wp_kses_data( __("Caption for the button at the bottom of the block", 'jacqueline') ),
						"group" => esc_html__('Captions', 'jacqueline'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					jacqueline_vc_width(),
					jacqueline_vc_height(),
					jacqueline_get_vc_param('margin_top'),
					jacqueline_get_vc_param('margin_bottom'),
					jacqueline_get_vc_param('margin_left'),
					jacqueline_get_vc_param('margin_right'),
					jacqueline_get_vc_param('id'),
					jacqueline_get_vc_param('class'),
					jacqueline_get_vc_param('animation'),
					jacqueline_get_vc_param('css')
				),
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
		vc_map( array(
				"base" => "trx_clients_item",
				"name" => esc_html__("Client", 'jacqueline'),
				"description" => wp_kses_data( __("Client - all data pull out from it account on your site", 'jacqueline') ),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item trx_sc_clients_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_clients_item',
				"as_child" => array('only' => 'trx_clients'),
				"as_parent" => array('except' => 'trx_clients'),
				"params" => array(
					array(
						"param_name" => "name",
						"heading" => esc_html__("Name", 'jacqueline'),
						"description" => wp_kses_data( __("Client's name", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "position",
						"heading" => esc_html__("Position", 'jacqueline'),
						"description" => wp_kses_data( __("Client's position", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", 'jacqueline'),
						"description" => wp_kses_data( __("Link on client's personal page", 'jacqueline') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Client's image", 'jacqueline'),
						"description" => wp_kses_data( __("Clients's image", 'jacqueline') ),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					jacqueline_get_vc_param('id'),
					jacqueline_get_vc_param('class'),
					jacqueline_get_vc_param('animation'),
					jacqueline_get_vc_param('css')
				),
				'js_view' => 'VcTrxColumnItemView'
			) );
			
		class WPBakeryShortCode_Trx_Clients extends JACQUELINE_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Clients_Item extends JACQUELINE_VC_ShortCodeCollection {}

	}
}
?>