<?php
/**
 * Jacqueline Framework: MenuItems support
 *
 * @package	jacqueline
 * @since	jacqueline 3.5
 */

// Theme init
if (!function_exists('jacqueline_menuitems_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_menuitems_theme_setup', 1 );
	function jacqueline_menuitems_theme_setup() {

		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('jacqueline_filter_get_blog_type',			'jacqueline_menuitems_get_blog_type', 9, 2);
		add_filter('jacqueline_filter_get_blog_title',		'jacqueline_menuitems_get_blog_title', 9, 2);
		add_filter('jacqueline_filter_get_current_taxonomy',	'jacqueline_menuitems_get_current_taxonomy', 9, 2);
		add_filter('jacqueline_filter_is_taxonomy',			'jacqueline_menuitems_is_taxonomy', 9, 2);
		add_filter('jacqueline_filter_get_stream_page_title',	'jacqueline_menuitems_get_stream_page_title', 9, 2);
		add_filter('jacqueline_filter_get_stream_page_link',	'jacqueline_menuitems_get_stream_page_link', 9, 2);
		add_filter('jacqueline_filter_get_stream_page_id',	'jacqueline_menuitems_get_stream_page_id', 9, 2);
		add_filter('jacqueline_filter_query_add_filters',		'jacqueline_menuitems_query_add_filters', 9, 2);
		add_filter('jacqueline_filter_detect_inheritance_key','jacqueline_menuitems_detect_inheritance_key', 9, 1);
		
		add_action('wp_ajax_ajax_menuitem',			'jacqueline_callback_ajax_menuitem');
		add_action('wp_ajax_nopriv_ajax_menuitem',	'jacqueline_callback_ajax_menuitem');
		
		// Extra column for menuitems lists
		if (jacqueline_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-menuitems_columns',			'jacqueline_post_add_options_column', 9);
			add_filter('manage_menuitems_posts_custom_column',	'jacqueline_post_fill_options_column', 9, 2);
		}

		// Registar shortcodes [trx_menuitems] and [trx_menuitems_item] in the shortcodes list
		add_action('jacqueline_action_shortcodes_list',		'jacqueline_menuitems_reg_shortcodes');
		if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
			add_action('jacqueline_action_shortcodes_list_vc','jacqueline_menuitems_reg_shortcodes_vc');
		
		// Add supported data types
		jacqueline_theme_support_pt('menuitems');
		jacqueline_theme_support_tx('menuitems_group');
	}
}

if ( !function_exists( 'jacqueline_menuitems_settings_theme_setup2' ) ) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_menuitems_settings_theme_setup2', 3 );
	function jacqueline_menuitems_settings_theme_setup2() {
		// Add post type 'menuitems' and taxonomy 'menuitems_group' into theme inheritance list
		jacqueline_add_theme_inheritance( array('menuitems' => array(
			'stream_template' => 'blog-menuitems',
			'single_template' => 'single-menuitems',
			'taxonomy' => array('menuitems_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('menuitems'),
			'override' => 'custom'
			) )
		);
	}
}



if (!function_exists('jacqueline_menuitems_after_theme_setup')) {
	add_action( 'jacqueline_action_after_init_theme', 'jacqueline_menuitems_after_theme_setup' );
	function jacqueline_menuitems_after_theme_setup() {
		// Update fields in the meta box
		if (jacqueline_storage_get_array('post_meta_box', 'page')=='menuitems') {

			// Meta box fields
			jacqueline_storage_set_array('post_meta_box', 'title', esc_html__('MenuItem Options', 'jacqueline'));
			jacqueline_storage_set_array('post_meta_box', 'fields', array(

				"mb_partition_menuitems" => array(
					"title" => esc_html__('MenuItems', 'jacqueline'),
					"override" => "page,post,custom",
					"divider" => false,
					"icon" => "icon-th-list",
					"type" => "partition"),

				"mb_info_menuitems_1" => array(
					"title" => esc_html__('MenuItem details', 'jacqueline'),
					"override" => "page,post,custom",
					"divider" => false,
					"desc" => wp_kses_data( __('In this section you can put details for this menuitem', 'jacqueline') ),
					"class" => "menuitem_meta",
					"type" => "info"),
				"menuitem_price" => array(
					"title" => esc_html__('Price',  'jacqueline'),
					"desc" => '',
					"override" => "page,post,custom",
					"class" => "menuitem_price",
					"std" => '',
					"type" => "text"),
				"menuitem_master" => array(
					"title" => esc_html__('Master-level massage',  'jacqueline'),
					"desc" => '',
					"override" => "page,post,custom",
					"class" => "menuitem_master",
					"std" => '',
					"type" => "textarea"),
				"menuitem_services" => array(
					"title" => __('Services included',  'jacqueline'),
					"desc" => __("Select services", 'jacqueline'),
					"override" => "page,post,custom",
					"class" => "menuitem_services",
					"std" => '',
					"options" => jacqueline_get_list_posts(false, 'services'),
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
				"menuitem_product" => array(
					"title" => __('Link to product',  'jacqueline'),
					"desc" => __("Link to product page for this menu items", 'jacqueline'),
					"override" => "page,post,custom",
					"class" => "menuitem_product",
					"std" => '',
					"options" => jacqueline_get_list_posts(false, 'product'),
					"type" => "select")
				
				)
			);
		}
	}
}


// Return true, if current page is menuitems page
if ( !function_exists( 'jacqueline_is_menuitems_page' ) ) {
	function jacqueline_is_menuitems_page() {
		$is = in_array(jacqueline_storage_get('page_template'), array('blog-menuitems', 'single-menuitem'));
		if (!$is) {
			if (!jacqueline_storage_empty('pre_query'))
				$is = jacqueline_storage_call_obj_method('pre_query', 'get', 'post_type')=='menuitems'
						|| jacqueline_storage_call_obj_method('pre_query', 'is_tax', 'menuitems_group') 
						|| (jacqueline_storage_call_obj_method('pre_query', 'is_page') 
							&& ($id=jacqueline_get_template_page_id('blog-menuitems')) > 0 
							&& $id==jacqueline_storage_get_obj_property('pre_query', 'queried_object_id', 0)
							);
			else
				$is = get_query_var('post_type')=='menuitems' 
						|| is_tax('menuitems_group') 
						|| (is_page() && ($id=jacqueline_get_template_page_id('blog-menuitems')) > 0 && $id==get_the_ID());
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'jacqueline_menuitems_detect_inheritance_key' ) ) {
	//add_filter('jacqueline_filter_detect_inheritance_key',	'jacqueline_menuitems_detect_inheritance_key', 9, 1);
	function jacqueline_menuitems_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return jacqueline_is_menuitems_page() ? 'menuitems' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'jacqueline_menuitems_get_blog_type' ) ) {
	//add_filter('jacqueline_filter_get_blog_type',	'jacqueline_menuitems_get_blog_type', 9, 2);
	function jacqueline_menuitems_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('menuitems_group') || is_tax('menuitems_group'))
			$page = 'menuitems_category';
		else if ($query && $query->get('post_type')=='menuitems' || get_query_var('post_type')=='menuitems')
			$page = $query && $query->is_single() || is_single() ? 'menuitems_item' : 'menuitems';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'jacqueline_menuitems_get_blog_title' ) ) {
	//add_filter('jacqueline_filter_get_blog_title',	'jacqueline_menuitems_get_blog_title', 9, 2);
	function jacqueline_menuitems_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( jacqueline_strpos($page, 'menuitems')!==false ) {
			if ( $page == 'menuitems_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'menuitems_group' ), 'menuitems_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'menuitems_item' ) {
				$title = jacqueline_get_post_title();
			} else {
				$title = esc_html__('All menuitems', 'jacqueline');
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'jacqueline_menuitems_get_stream_page_title' ) ) {
	//add_filter('jacqueline_filter_get_stream_page_title',	'jacqueline_menuitems_get_stream_page_title', 9, 2);
	function jacqueline_menuitems_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (jacqueline_strpos($page, 'menuitems')!==false) {
			if (($page_id = jacqueline_menuitems_get_stream_page_id(0, $page=='menuitems' ? 'blog-menuitems' : $page)) > 0)
				$title = jacqueline_get_post_title($page_id);
			else
				$title = esc_html__('All menuitems', 'jacqueline');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'jacqueline_menuitems_get_stream_page_id' ) ) {
	//add_filter('jacqueline_filter_get_stream_page_id',	'jacqueline_menuitems_get_stream_page_id', 9, 2);
	function jacqueline_menuitems_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (jacqueline_strpos($page, 'menuitems')!==false) $id = jacqueline_get_template_page_id('blog-menuitems');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'jacqueline_menuitems_get_stream_page_link' ) ) {
	//add_filter('jacqueline_filter_get_stream_page_link',	'jacqueline_menuitems_get_stream_page_link', 9, 2);
	function jacqueline_menuitems_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (jacqueline_strpos($page, 'menuitems')!==false) {
			$id = jacqueline_get_template_page_id('blog-menuitems');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'jacqueline_menuitems_get_current_taxonomy' ) ) {
	//add_filter('jacqueline_filter_get_current_taxonomy',	'jacqueline_menuitems_get_current_taxonomy', 9, 2);
	function jacqueline_menuitems_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( jacqueline_strpos($page, 'menuitems')!==false ) {
			$tax = 'menuitems_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'jacqueline_menuitems_is_taxonomy' ) ) {
	//add_filter('jacqueline_filter_is_taxonomy',	'jacqueline_menuitems_is_taxonomy', 9, 2);
	function jacqueline_menuitems_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('menuitems_group')!='' || is_tax('menuitems_group') ? 'menuitems_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'jacqueline_menuitems_query_add_filters' ) ) {
	//add_filter('jacqueline_filter_query_add_filters',	'jacqueline_menuitems_query_add_filters', 9, 2);
	function jacqueline_menuitems_query_add_filters($args, $filter) {
		if ($filter == 'menuitems') {
			$args['post_type'] = 'menuitems';
		}
		return $args;
	}
}




// AJAX handler - return menuitems list
//----------------------------------------------------------------------------
if ( !function_exists( 'jacqueline_callback_ajax_menuitem' ) ) {
	function jacqueline_callback_ajax_menuitem() {
		
		if ( !wp_verify_nonce( jacqueline_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();
		
		$response = array('error'=>'', 'data' => '');
		
		$id = $_REQUEST['text'];
		
		$response['data'] = jacqueline_sc_menuitems(array(
			'style' => 'menuitems-2',
			'columns' => '1',
			'popup' => 'no',
			'slider' => 'no',
			'custom' => 'no',
			'count' => '1',
			'offset' => '0',
			'orderby' => 'title',
			'order' => 'asc',
			'ids' => $id,
			'top' => 'inherit',
			'bottom' => 'inherit',
			'left' => 'inherit',
			'right' => 'inherit'
			));
		
		echo json_encode($response);
		die();
	}
}





// ---------------------------------- [trx_menuitems] ---------------------------------------


if ( !function_exists( 'jacqueline_sc_menuitems' ) ) {
	function jacqueline_sc_menuitems($atts, $content=null){	
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "menuitems-1",
			"columns" => 4,
			"popup" => "no",
			"slider" => "no",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"ids" => "",
			"cat" => "",
			"count" => 4,
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
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

		if (empty($id)) $id = "sc_menuitems_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && jacqueline_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);

		$css .= ($css ? '; ' : '') . jacqueline_get_css_position_from_values($top, $right, $bottom, $left);

		$ws = jacqueline_get_css_dimensions_from_values($width);
		$hs = jacqueline_get_css_dimensions_from_values('', $height);
		$css .= ($hs) . ($ws);
	
		$columns = max(1, min(12, $columns));
		$count = max(1, (int) $count);
		if ($count < $columns) $columns = $count;

		if (jacqueline_param_is_on($slider)) jacqueline_enqueue_slider('swiper');

		jacqueline_storage_set('sc_menuitems_data', array(
			'id'=>$id,
            'style'=>$style,
            'counter'=>0,
            'columns'=>$columns,
            'slider'=>$slider,
            'css_wh'=>$ws . $hs
            )
        );

		$output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '') 
						. ' class="sc_menuitems_wrap'
						. ($scheme && !jacqueline_param_is_off($scheme) && !jacqueline_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						.'">'
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_menuitems'
							. ' sc_menuitems_style_'.esc_attr($style)
							. ' ' . esc_attr(jacqueline_get_template_property($style, 'container_classes'))
							. (!empty($class) ? ' '.esc_attr($class) : '')
						.'"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. (!jacqueline_param_is_off($animation) ? ' data-animation="'.esc_attr(jacqueline_get_animation_classes($animation)).'"' : '')
					. '>'
					. (!empty($subtitle) ? '<h6 class="sc_menuitems_subtitle sc_item_subtitle">' . trim(jacqueline_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_menuitems_title sc_item_title">' . trim(jacqueline_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_menuitems_descr sc_item_descr">' . trim(jacqueline_strmacros($description)) . '</div>' : '')
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
									. ($style!='menuitems-1' ? ' data-slides-min-width="250"' : '')
								. '>'
							. '<div class="slides swiper-wrapper">')
						: ($columns > 1 
							? '<div class="sc_columns columns_wrap">' 
							: '')
						);
	
		global $post;

		if (!empty($ids)) {
			$posts = explode(',', $ids);
			$count = count($posts);
		}
		
		$args = array(
			'post_type' => 'menuitems',
			'post_status' => 'publish',
			'posts_per_page' => $count,
			'ignore_sticky_posts' => true,
			'order' => $order=='asc' ? 'asc' : 'desc',
		);
	
		if ($offset > 0 && empty($ids)) {
			$args['offset'] = $offset;
		}
	
		$args = jacqueline_query_add_sort_order($args, $orderby, $order);
		$args = jacqueline_query_add_posts_and_cats($args, $ids, 'menuitems', $cat, 'menuitems_group');

		$query = new WP_Query( $args );

		$post_number = 0;
		$post_list = array();

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
			$post_list[] = $post_data['post_id'];

			$args['menuitem_price'] = $post_meta['menuitem_price'];
			$args['menuitem_master'] = $post_meta['menuitem_master'];
			$args['menuitem_services'] = $post_meta['menuitem_services'];
			$args['menuitem_product'] = $post_meta['menuitem_product'];
			$args['menuitem_image'] = $post_data['post_thumb'];
			
			$args['popup'] = $popup;

			$args['menuitem_link'] = jacqueline_param_is_on('menuitem_show_link')
				? (!empty($post_meta['menuitem_link']) ? $post_meta['menuitem_link'] : $post_data['post_link'])
				: '';
			
			$output .= jacqueline_show_post_layout($args, $post_data);
		}
		wp_reset_postdata();

		$output .= "<script>
			jQuery(document).ready(function(){
				if (JACQUELINE_STORAGE['menuitems']===undefined) JACQUELINE_STORAGE['menuitems'] = [];
				JACQUELINE_STORAGE['menuitems']['" . trim($id) . "'] = '" . join(',', $post_list) . "';
			});
		</script>
		";
	
		if (jacqueline_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>'
				. '</div>';
		} else if ($columns > 1) {
			$output .= '</div>';
		}

		$output .= (!empty($link) ? '<div class="sc_menuitems_button sc_item_button">'.jacqueline_do_shortcode('[trx_button link="'.esc_url($link).'" ]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
				. '</div><!-- /.sc_menuitems -->'
			. '</div><!-- /.sc_menuitems_wrap -->';
	
		// Add template specific scripts and styles
		do_action('jacqueline_action_blog_scripts', $style);
	
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_menuitems', $atts, $content);
	}
	jacqueline_require_shortcode('trx_menuitems', 'jacqueline_sc_menuitems');
}


if ( !function_exists( 'jacqueline_sc_menuitems_item' ) ) {
	function jacqueline_sc_menuitems_item($atts, $content=null) {
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
	
		jacqueline_storage_inc_array('sc_menuitems_data', 'counter');
	
		$id = $id ? $id : (jacqueline_storage_get_array('sc_menuitems_data', 'id') ? jacqueline_storage_get_array('sc_menuitems_data', 'id') . '_' . jacqueline_storage_get_array('sc_menuitems_data', 'counter') : '');
	
		$descr = trim(chop(do_shortcode($content)));
	
		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		$thumb_sizes = jacqueline_get_thumb_sizes(array('layout' => jacqueline_storage_get_array('sc_menuitems_data', 'style')));
		$image = jacqueline_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);

		$post_data = array(
			'post_title' => $name,
			'post_excerpt' => $descr,
			'post_thumb' => $image,
			'post_link' => $link,
			'post_protected' => false,
			'post_format' => 'standard'
		);
		$args = array(
			'layout' => jacqueline_storage_get_array('sc_menuitems_data', 'style'),
			'number' => jacqueline_storage_get_array('sc_menuitems_data', 'counter'),
			'columns_count' => jacqueline_storage_get_array('sc_menuitems_data', 'columns'),
			'slider' => jacqueline_storage_get_array('sc_menuitems_data', 'slider'),
			'show' => false,
			'descr'  => -1,		// -1 - don't strip tags, 0 - strip_tags, >0 - strip_tags and truncate string
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => $animation,
			'tag_css' => $css,
			'tag_css_wh' => jacqueline_storage_get_array('sc_menuitems_data', 'css_wh'),
			//'menuitem_position' => $position,
			//'menuitem_link' => $link,
			'menuitem_image' => $image
		);
		$output = jacqueline_show_post_layout($args, $post_data);
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_menuitems_item', $atts, $content);
	}
	jacqueline_require_shortcode('trx_menuitems_item', 'jacqueline_sc_menuitems_item');
}
// ---------------------------------- [/trx_menuitems] ---------------------------------------



// Add [trx_menuitems] and [trx_menuitems_item] in the shortcodes list
if (!function_exists('jacqueline_menuitems_reg_shortcodes')) {
	//add_filter('jacqueline_action_shortcodes_list',	'jacqueline_menuitems_reg_shortcodes');
	function jacqueline_menuitems_reg_shortcodes() {
		if (jacqueline_storage_isset('shortcodes')) {

			$menuitems_groups = jacqueline_get_list_terms(false, 'menuitems_group');
			$menuitems_styles = jacqueline_get_list_templates('menuitems');
			$controls 		  = jacqueline_get_list_slider_controls();

			jacqueline_sc_map_after('trx_list', array(

				// MenuItems
				"trx_menuitems" => array(
					"title" => esc_html__("Menu Items", 'jacqueline'),
					"desc" => wp_kses_data( __("Insert menu items list in your page (post)", 'jacqueline') ),
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
						),
						"style" => array(
							"title" => esc_html__("MenuItems style", 'jacqueline'),
							"desc" => wp_kses_data( __("Select style to display menuitems list", 'jacqueline') ),
							"value" => "menuitems-1",
							"type" => "select",
							"options" => $menuitems_styles
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'jacqueline'),
							"desc" => wp_kses_data( __("How many columns use to show menuitems", 'jacqueline') ),
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
						"popup" => array(
							"title" => esc_html__("Popup info", 'jacqueline'),
							"desc" => wp_kses_data( __("If 'popup info' is set to 'no', the menu item will be opened in the same window. If it's switched to 'yes', a popup window will show up.", 'jacqueline') ),
							"value" => "no",
							"type" => "switch",
							"options" => jacqueline_get_sc_param('yes_no')
						),
						"slider" => array(
							"title" => esc_html__("Slider", 'jacqueline'),
							"desc" => wp_kses_data( __("Use slider to show menuitems", 'jacqueline') ),
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
						"cat" => array(
							"title" => esc_html__("Categories", 'jacqueline'),
							"desc" => wp_kses_data( __("Select categories (groups) to show menu items. If empty - select items from any category (group) or from IDs list", 'jacqueline') ),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => jacqueline_array_merge(array(0 => esc_html__('- Select category -', 'jacqueline')), $menuitems_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", 'jacqueline'),
							"desc" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'jacqueline') ),
							"value" => 4,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", 'jacqueline'),
							"desc" => wp_kses_data( __("Skip posts before select next part.", 'jacqueline') ),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", 'jacqueline'),
							"desc" => wp_kses_data( __("Select desired posts sorting method", 'jacqueline') ),
							"value" => "title",
							"type" => "select",
							"options" => jacqueline_get_sc_param('sorting')
						),
						"order" => array(
							"title" => esc_html__("Post order", 'jacqueline'),
							"desc" => wp_kses_data( __("Select desired posts order", 'jacqueline') ),
							"value" => "asc",
							"type" => "switch",
							"size" => "big",
							"options" => jacqueline_get_sc_param('ordering')
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", 'jacqueline'),
							"desc" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'jacqueline') ),
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
					)
				)

			));
		}
	}
}


// Add [trx_menuitems] and [trx_menuitems_item] in the VC shortcodes list
if (!function_exists('jacqueline_menuitems_reg_shortcodes_vc')) {
	//add_filter('jacqueline_action_shortcodes_list_vc',	'jacqueline_menuitems_reg_shortcodes_vc');
	function jacqueline_menuitems_reg_shortcodes_vc() {

		$menuitems_groups = jacqueline_get_list_terms(false, 'menuitems_group');
		$menuitems_styles = jacqueline_get_list_templates('menuitems');
		$controls		= jacqueline_get_list_slider_controls();

		// MenuItems
		vc_map( array(
				"base" => "trx_menuitems",
				"name" => esc_html__("MenuItems", 'jacqueline'),
				"description" => wp_kses_data( __("Insert menuitems list", 'jacqueline') ),
				"category" => esc_html__('Content', 'jacqueline'),
				'icon' => 'icon_trx_menuitems',
				"class" => "trx_sc_single trx_sc_menuitems",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("MenuItems style", 'jacqueline'),
						"description" => wp_kses_data( __("Select style to display menuitems list", 'jacqueline') ),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($menuitems_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", 'jacqueline'),
						"description" => wp_kses_data( __("Select color scheme for this block", 'jacqueline') ),
						"class" => "",
						"value" => array_flip(jacqueline_get_sc_param('schemes')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "popup",
						"heading" => esc_html__("Popup info", 'jacqueline'),
						"description" => wp_kses_data( __("If 'popup info' is set to 'no', the menu item will be opened in the same window. If it's switched to 'yes', a popup window will show up.", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"std" => "no",
						"value" => array_flip(jacqueline_get_sc_param('yes_no')),
						"type" => "dropdown"
						),
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", 'jacqueline'),
						"description" => wp_kses_data( __("Use slider to show menuitems", 'jacqueline') ),
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
						"description" => wp_kses_data( __("Select category to show menuitems. If empty - select menuitems from any category (group) or from IDs list", 'jacqueline') ),
						"group" => esc_html__('Query', 'jacqueline'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(jacqueline_array_merge(array(0 => esc_html__('- Select category -', 'jacqueline')), $menuitems_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'jacqueline'),
						"description" => wp_kses_data( __("How many columns use to show menuitems", 'jacqueline') ),
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
						"class" => "",
						"value" => array_flip(jacqueline_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Menuitem's IDs list", 'jacqueline'),
						"description" => wp_kses_data( __("Comma separated list of menuitem's ID. If set - parameters above (category, count, order, etc.)  are ignored!", 'jacqueline') ),
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
				'js_view' => 'VcTrxSingleView'
			) );
			
		class WPBakeryShortCode_Trx_MenuItems extends JACQUELINE_VC_ShortCodeSingle {}

	}
}
?>