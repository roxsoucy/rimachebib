<?php
/**
 * Jacqueline Framework: return lists
 *
 * @package jacqueline
 * @since jacqueline 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return styles list
if ( !function_exists( 'jacqueline_get_list_styles' ) ) {
	function jacqueline_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf(esc_html__('Style %d', 'jacqueline'), $i);
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}


// Return list of the shortcodes margins
if ( !function_exists( 'jacqueline_get_list_margins' ) ) {
	function jacqueline_get_list_margins($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_margins'))=='') {
			$list = array(
				'null'		=> esc_html__('0 (No margin)',	'jacqueline'),
				'tiny'		=> esc_html__('Tiny',		'jacqueline'),
				'small'		=> esc_html__('Small',		'jacqueline'),
				'medium'	=> esc_html__('Medium',		'jacqueline'),
				'large'		=> esc_html__('Large',		'jacqueline'),
				'huge'		=> esc_html__('Huge',		'jacqueline'),
				'tiny-'		=> esc_html__('Tiny (negative)',	'jacqueline'),
				'small-'	=> esc_html__('Small (negative)',	'jacqueline'),
				'medium-'	=> esc_html__('Medium (negative)',	'jacqueline'),
				'large-'	=> esc_html__('Large (negative)',	'jacqueline'),
				'huge-'		=> esc_html__('Huge (negative)',	'jacqueline')
				);
			$list = apply_filters('jacqueline_filter_list_margins', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_margins', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'jacqueline_get_list_animations' ) ) {
	function jacqueline_get_list_animations($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_animations'))=='') {
			$list = array(
				'none'			=> esc_html__('- None -',	'jacqueline'),
				'bounced'		=> esc_html__('Bounced',		'jacqueline'),
				'flash'			=> esc_html__('Flash',		'jacqueline'),
				'flip'			=> esc_html__('Flip',		'jacqueline'),
				'pulse'			=> esc_html__('Pulse',		'jacqueline'),
				'rubberBand'	=> esc_html__('Rubber Band',	'jacqueline'),
				'shake'			=> esc_html__('Shake',		'jacqueline'),
				'swing'			=> esc_html__('Swing',		'jacqueline'),
				'tada'			=> esc_html__('Tada',		'jacqueline'),
				'wobble'		=> esc_html__('Wobble',		'jacqueline')
				);
			$list = apply_filters('jacqueline_filter_list_animations', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_animations', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}


// Return list of the line styles
if ( !function_exists( 'jacqueline_get_list_line_styles' ) ) {
	function jacqueline_get_list_line_styles($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_line_styles'))=='') {
			$list = array(
				'solid'	=> esc_html__('Solid', 'jacqueline'),
				'dashed'=> esc_html__('Dashed', 'jacqueline'),
				'dotted'=> esc_html__('Dotted', 'jacqueline'),
				'double'=> esc_html__('Double', 'jacqueline'),
				'image'	=> esc_html__('Image', 'jacqueline')
				);
			$list = apply_filters('jacqueline_filter_list_line_styles', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_line_styles', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'jacqueline_get_list_animations_in' ) ) {
	function jacqueline_get_list_animations_in($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_animations_in'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'jacqueline'),
				'bounceIn'			=> esc_html__('Bounce In',			'jacqueline'),
				'bounceInUp'		=> esc_html__('Bounce In Up',		'jacqueline'),
				'bounceInDown'		=> esc_html__('Bounce In Down',		'jacqueline'),
				'bounceInLeft'		=> esc_html__('Bounce In Left',		'jacqueline'),
				'bounceInRight'		=> esc_html__('Bounce In Right',	'jacqueline'),
				'fadeIn'			=> esc_html__('Fade In',			'jacqueline'),
				'fadeInUp'			=> esc_html__('Fade In Up',			'jacqueline'),
				'fadeInDown'		=> esc_html__('Fade In Down',		'jacqueline'),
				'fadeInLeft'		=> esc_html__('Fade In Left',		'jacqueline'),
				'fadeInRight'		=> esc_html__('Fade In Right',		'jacqueline'),
				'fadeInUpBig'		=> esc_html__('Fade In Up Big',		'jacqueline'),
				'fadeInDownBig'		=> esc_html__('Fade In Down Big',	'jacqueline'),
				'fadeInLeftBig'		=> esc_html__('Fade In Left Big',	'jacqueline'),
				'fadeInRightBig'	=> esc_html__('Fade In Right Big',	'jacqueline'),
				'flipInX'			=> esc_html__('Flip In X',			'jacqueline'),
				'flipInY'			=> esc_html__('Flip In Y',			'jacqueline'),
				'lightSpeedIn'		=> esc_html__('Light Speed In',		'jacqueline'),
				'rotateIn'			=> esc_html__('Rotate In',			'jacqueline'),
				'rotateInUpLeft'	=> esc_html__('Rotate In Down Left','jacqueline'),
				'rotateInUpRight'	=> esc_html__('Rotate In Up Right',	'jacqueline'),
				'rotateInDownLeft'	=> esc_html__('Rotate In Up Left',	'jacqueline'),
				'rotateInDownRight'	=> esc_html__('Rotate In Down Right','jacqueline'),
				'rollIn'			=> esc_html__('Roll In',			'jacqueline'),
				'slideInUp'			=> esc_html__('Slide In Up',		'jacqueline'),
				'slideInDown'		=> esc_html__('Slide In Down',		'jacqueline'),
				'slideInLeft'		=> esc_html__('Slide In Left',		'jacqueline'),
				'slideInRight'		=> esc_html__('Slide In Right',		'jacqueline'),
				'zoomIn'			=> esc_html__('Zoom In',			'jacqueline'),
				'zoomInUp'			=> esc_html__('Zoom In Up',			'jacqueline'),
				'zoomInDown'		=> esc_html__('Zoom In Down',		'jacqueline'),
				'zoomInLeft'		=> esc_html__('Zoom In Left',		'jacqueline'),
				'zoomInRight'		=> esc_html__('Zoom In Right',		'jacqueline')
				);
			$list = apply_filters('jacqueline_filter_list_animations_in', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_animations_in', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'jacqueline_get_list_animations_out' ) ) {
	function jacqueline_get_list_animations_out($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_animations_out'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',	'jacqueline'),
				'bounceOut'			=> esc_html__('Bounce Out',			'jacqueline'),
				'bounceOutUp'		=> esc_html__('Bounce Out Up',		'jacqueline'),
				'bounceOutDown'		=> esc_html__('Bounce Out Down',		'jacqueline'),
				'bounceOutLeft'		=> esc_html__('Bounce Out Left',		'jacqueline'),
				'bounceOutRight'	=> esc_html__('Bounce Out Right',	'jacqueline'),
				'fadeOut'			=> esc_html__('Fade Out',			'jacqueline'),
				'fadeOutUp'			=> esc_html__('Fade Out Up',			'jacqueline'),
				'fadeOutDown'		=> esc_html__('Fade Out Down',		'jacqueline'),
				'fadeOutLeft'		=> esc_html__('Fade Out Left',		'jacqueline'),
				'fadeOutRight'		=> esc_html__('Fade Out Right',		'jacqueline'),
				'fadeOutUpBig'		=> esc_html__('Fade Out Up Big',		'jacqueline'),
				'fadeOutDownBig'	=> esc_html__('Fade Out Down Big',	'jacqueline'),
				'fadeOutLeftBig'	=> esc_html__('Fade Out Left Big',	'jacqueline'),
				'fadeOutRightBig'	=> esc_html__('Fade Out Right Big',	'jacqueline'),
				'flipOutX'			=> esc_html__('Flip Out X',			'jacqueline'),
				'flipOutY'			=> esc_html__('Flip Out Y',			'jacqueline'),
				'hinge'				=> esc_html__('Hinge Out',			'jacqueline'),
				'lightSpeedOut'		=> esc_html__('Light Speed Out',		'jacqueline'),
				'rotateOut'			=> esc_html__('Rotate Out',			'jacqueline'),
				'rotateOutUpLeft'	=> esc_html__('Rotate Out Down Left',	'jacqueline'),
				'rotateOutUpRight'	=> esc_html__('Rotate Out Up Right',		'jacqueline'),
				'rotateOutDownLeft'	=> esc_html__('Rotate Out Up Left',		'jacqueline'),
				'rotateOutDownRight'=> esc_html__('Rotate Out Down Right',	'jacqueline'),
				'rollOut'			=> esc_html__('Roll Out',		'jacqueline'),
				'slideOutUp'		=> esc_html__('Slide Out Up',		'jacqueline'),
				'slideOutDown'		=> esc_html__('Slide Out Down',	'jacqueline'),
				'slideOutLeft'		=> esc_html__('Slide Out Left',	'jacqueline'),
				'slideOutRight'		=> esc_html__('Slide Out Right',	'jacqueline'),
				'zoomOut'			=> esc_html__('Zoom Out',			'jacqueline'),
				'zoomOutUp'			=> esc_html__('Zoom Out Up',		'jacqueline'),
				'zoomOutDown'		=> esc_html__('Zoom Out Down',	'jacqueline'),
				'zoomOutLeft'		=> esc_html__('Zoom Out Left',	'jacqueline'),
				'zoomOutRight'		=> esc_html__('Zoom Out Right',	'jacqueline')
				);
			$list = apply_filters('jacqueline_filter_list_animations_out', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_animations_out', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('jacqueline_get_animation_classes')) {
	function jacqueline_get_animation_classes($animation, $speed='normal', $loop='none') {
		// speed:	fast=0.5s | normal=1s | slow=2s
		// loop:	none | infinite
		return jacqueline_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!jacqueline_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of categories
if ( !function_exists( 'jacqueline_get_list_categories' ) ) {
	function jacqueline_get_list_categories($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_categories'))=='') {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_categories', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'jacqueline_get_list_terms' ) ) {
	function jacqueline_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		if (($list = jacqueline_storage_get('list_taxonomies_'.($taxonomy)))=='') {
			$list = array();
			if ( is_array($taxonomy) || taxonomy_exists($taxonomy) ) {
				$terms = get_terms( $taxonomy, array(
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 0,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => $taxonomy,
					'pad_counts'               => false
					)
				);
			} else {
				$terms = jacqueline_get_terms_by_taxonomy_from_db($taxonomy);
			}
			if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
				foreach ($terms as $cat) {
					$list[$cat->term_id] = $cat->name;	// . ($taxonomy!='category' ? ' /'.($cat->taxonomy).'/' : '');
				}
			}
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_taxonomies_'.($taxonomy), $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'jacqueline_get_list_posts_types' ) ) {
	function jacqueline_get_list_posts_types($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_posts_types'))=='') {

			// Return only theme inheritance supported post types
			$list = apply_filters('jacqueline_filter_list_post_types', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_posts_types', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'jacqueline_get_list_posts' ) ) {
	function jacqueline_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (($list = jacqueline_storage_get($hash))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'jacqueline');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set($hash, $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}


// Return list pages
if ( !function_exists( 'jacqueline_get_list_pages' ) ) {
	function jacqueline_get_list_pages($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'page',
			'post_status'		=> 'publish',
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'asc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));
		return jacqueline_get_list_posts($prepend_inherit, $opt);
	}
}


// Return list of registered users
if ( !function_exists( 'jacqueline_get_list_users' ) ) {
	function jacqueline_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		if (($list = jacqueline_storage_get('list_users'))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'jacqueline');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_users', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}


// Return slider engines list, prepended inherit (if need)
if ( !function_exists( 'jacqueline_get_list_sliders' ) ) {
	function jacqueline_get_list_sliders($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_sliders'))=='') {
			$list = array(
				'swiper' => esc_html__("Posts slider (Swiper)", 'jacqueline')
			);
			$list = apply_filters('jacqueline_filter_list_sliders', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_sliders', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}


// Return slider controls list, prepended inherit (if need)
if ( !function_exists( 'jacqueline_get_list_slider_controls' ) ) {
	function jacqueline_get_list_slider_controls($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_slider_controls'))=='') {
			$list = array(
				'no'		=> esc_html__('None', 'jacqueline'),
				'side'		=> esc_html__('Side', 'jacqueline'),
				'bottom'	=> esc_html__('Bottom', 'jacqueline'),
				'pagination'=> esc_html__('Pagination', 'jacqueline')
				);
			$list = apply_filters('jacqueline_filter_list_slider_controls', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_slider_controls', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}


// Return slider controls classes
if ( !function_exists( 'jacqueline_get_slider_controls_classes' ) ) {
	function jacqueline_get_slider_controls_classes($controls) {
		if (jacqueline_param_is_off($controls))	$classes = 'sc_slider_nopagination sc_slider_nocontrols';
		else if ($controls=='bottom')			$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else if ($controls=='pagination')		$classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols';
		else									$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side';
		return $classes;
	}
}

// Return list with popup engines
if ( !function_exists( 'jacqueline_get_list_popup_engines' ) ) {
	function jacqueline_get_list_popup_engines($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_popup_engines'))=='') {
			$list = array(
				"pretty"	=> esc_html__("Pretty photo", 'jacqueline'),
				"magnific"	=> esc_html__("Magnific popup", 'jacqueline')
				);
			$list = apply_filters('jacqueline_filter_list_popup_engines', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_popup_engines', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'jacqueline_get_list_menus' ) ) {
	function jacqueline_get_list_menus($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_menus'))=='') {
			$list = array();
			$list['default'] = esc_html__("Default", 'jacqueline');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_menus', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'jacqueline_get_list_sidebars' ) ) {
	function jacqueline_get_list_sidebars($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_sidebars'))=='') {
			if (($list = jacqueline_storage_get('registered_sidebars'))=='') $list = array();
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_sidebars', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'jacqueline_get_list_sidebars_positions' ) ) {
	function jacqueline_get_list_sidebars_positions($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_sidebars_positions'))=='') {
			$list = array(
				'none'  => esc_html__('Hide',  'jacqueline'),
				'left'  => esc_html__('Left',  'jacqueline'),
				'right' => esc_html__('Right', 'jacqueline')
				);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_sidebars_positions', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'jacqueline_get_sidebar_class' ) ) {
	function jacqueline_get_sidebar_class() {
		$sb_main = jacqueline_get_custom_option('show_sidebar_main');
		$sb_outer = jacqueline_get_custom_option('show_sidebar_outer');
		return (jacqueline_param_is_off($sb_main) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($sb_main))
				. ' ' . (jacqueline_param_is_off($sb_outer) ? 'sidebar_outer_hide' : 'sidebar_outer_show sidebar_outer_'.($sb_outer));
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'jacqueline_get_list_body_styles' ) ) {
	function jacqueline_get_list_body_styles($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_body_styles'))=='') {
			$list = array(
				'boxed'	=> esc_html__('Boxed',		'jacqueline'),
				'wide'	=> esc_html__('Wide',		'jacqueline')
				);
			if (jacqueline_get_theme_setting('allow_fullscreen')) {
				$list['fullwide']	= esc_html__('Fullwide',	'jacqueline');
				$list['fullscreen']	= esc_html__('Fullscreen',	'jacqueline');
			}
			$list = apply_filters('jacqueline_filter_list_body_styles', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_body_styles', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return skins list, prepended inherit
if ( !function_exists( 'jacqueline_get_list_skins' ) ) {
	function jacqueline_get_list_skins($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_skins'))=='') {
			$list = jacqueline_get_list_folders("skins");
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_skins', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return css-themes list
if ( !function_exists( 'jacqueline_get_list_themes' ) ) {
	function jacqueline_get_list_themes($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_themes'))=='') {
			$list = jacqueline_get_list_files("css/themes");
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_themes', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'jacqueline_get_list_templates' ) ) {
	function jacqueline_get_list_templates($mode='') {
		if (($list = jacqueline_storage_get('list_templates_'.($mode)))=='') {
			$list = array();
			$tpl = jacqueline_storage_get('registered_templates');
			if (is_array($tpl) && count($tpl) > 0) {
				foreach ($tpl as $k=>$v) {
					if ($mode=='' || in_array($mode, explode(',', $v['mode'])))
						$list[$k] = !empty($v['icon']) 
									? $v['icon'] 
									: (!empty($v['title']) 
										? $v['title'] 
										: jacqueline_strtoproper($v['layout'])
										);
				}
			}
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_templates_'.($mode), $list);
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'jacqueline_get_list_templates_blog' ) ) {
	function jacqueline_get_list_templates_blog($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_templates_blog'))=='') {
			$list = jacqueline_get_list_templates('blog');
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_templates_blog', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'jacqueline_get_list_templates_blogger' ) ) {
	function jacqueline_get_list_templates_blogger($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_templates_blogger'))=='') {
			$list = jacqueline_array_merge(jacqueline_get_list_templates('blogger'), jacqueline_get_list_templates('blog'));
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_templates_blogger', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'jacqueline_get_list_templates_single' ) ) {
	function jacqueline_get_list_templates_single($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_templates_single'))=='') {
			$list = jacqueline_get_list_templates('single');
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_templates_single', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return header styles list, prepended inherit
if ( !function_exists( 'jacqueline_get_list_templates_header' ) ) {
	function jacqueline_get_list_templates_header($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_templates_header'))=='') {
			$list = jacqueline_get_list_templates('header');
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_templates_header', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return form styles list, prepended inherit
if ( !function_exists( 'jacqueline_get_list_templates_forms' ) ) {
	function jacqueline_get_list_templates_forms($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_templates_forms'))=='') {
			$list = jacqueline_get_list_templates('forms');
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_templates_forms', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'jacqueline_get_list_article_styles' ) ) {
	function jacqueline_get_list_article_styles($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_article_styles'))=='') {
			$list = array(
				"boxed"   => esc_html__('Boxed', 'jacqueline'),
				"stretch" => esc_html__('Stretch', 'jacqueline')
				);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_article_styles', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'jacqueline_get_list_post_formats_filters' ) ) {
	function jacqueline_get_list_post_formats_filters($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_post_formats_filters'))=='') {
			$list = array(
				"no"      => esc_html__('All posts', 'jacqueline'),
				"thumbs"  => esc_html__('With thumbs', 'jacqueline'),
				"reviews" => esc_html__('With reviews', 'jacqueline'),
				"video"   => esc_html__('With videos', 'jacqueline'),
				"audio"   => esc_html__('With audios', 'jacqueline'),
				"gallery" => esc_html__('With galleries', 'jacqueline')
				);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_post_formats_filters', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'jacqueline_get_list_portfolio_filters' ) ) {
	function jacqueline_get_list_portfolio_filters($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_portfolio_filters'))=='') {
			$list = array(
				"hide"		=> esc_html__('Hide', 'jacqueline'),
				"tags"		=> esc_html__('Tags', 'jacqueline'),
				"categories"=> esc_html__('Categories', 'jacqueline')
				);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_portfolio_filters', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'jacqueline_get_list_hovers' ) ) {
	function jacqueline_get_list_hovers($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_hovers'))=='') {
			$list = array();
			$list['circle effect1']  = esc_html__('Circle Effect 1',  'jacqueline');
			$list['circle effect2']  = esc_html__('Circle Effect 2',  'jacqueline');
			$list['circle effect3']  = esc_html__('Circle Effect 3',  'jacqueline');
			$list['circle effect4']  = esc_html__('Circle Effect 4',  'jacqueline');
			$list['circle effect5']  = esc_html__('Circle Effect 5',  'jacqueline');
			$list['circle effect6']  = esc_html__('Circle Effect 6',  'jacqueline');
			$list['circle effect7']  = esc_html__('Circle Effect 7',  'jacqueline');
			$list['circle effect8']  = esc_html__('Circle Effect 8',  'jacqueline');
			$list['circle effect9']  = esc_html__('Circle Effect 9',  'jacqueline');
			$list['circle effect10'] = esc_html__('Circle Effect 10',  'jacqueline');
			$list['circle effect11'] = esc_html__('Circle Effect 11',  'jacqueline');
			$list['circle effect12'] = esc_html__('Circle Effect 12',  'jacqueline');
			$list['circle effect13'] = esc_html__('Circle Effect 13',  'jacqueline');
			$list['circle effect14'] = esc_html__('Circle Effect 14',  'jacqueline');
			$list['circle effect15'] = esc_html__('Circle Effect 15',  'jacqueline');
			$list['circle effect16'] = esc_html__('Circle Effect 16',  'jacqueline');
			$list['circle effect17'] = esc_html__('Circle Effect 17',  'jacqueline');
			$list['circle effect18'] = esc_html__('Circle Effect 18',  'jacqueline');
			$list['circle effect19'] = esc_html__('Circle Effect 19',  'jacqueline');
			$list['circle effect20'] = esc_html__('Circle Effect 20',  'jacqueline');
			$list['square effect1']  = esc_html__('Square Effect 1',  'jacqueline');
			$list['square effect2']  = esc_html__('Square Effect 2',  'jacqueline');
			$list['square effect3']  = esc_html__('Square Effect 3',  'jacqueline');
	//		$list['square effect4']  = esc_html__('Square Effect 4',  'jacqueline');
			$list['square effect5']  = esc_html__('Square Effect 5',  'jacqueline');
			$list['square effect6']  = esc_html__('Square Effect 6',  'jacqueline');
			$list['square effect7']  = esc_html__('Square Effect 7',  'jacqueline');
			$list['square effect8']  = esc_html__('Square Effect 8',  'jacqueline');
			$list['square effect9']  = esc_html__('Square Effect 9',  'jacqueline');
			$list['square effect10'] = esc_html__('Square Effect 10',  'jacqueline');
			$list['square effect11'] = esc_html__('Square Effect 11',  'jacqueline');
			$list['square effect12'] = esc_html__('Square Effect 12',  'jacqueline');
			$list['square effect13'] = esc_html__('Square Effect 13',  'jacqueline');
			$list['square effect14'] = esc_html__('Square Effect 14',  'jacqueline');
			$list['square effect15'] = esc_html__('Square Effect 15',  'jacqueline');
			$list['square effect_dir']   = esc_html__('Square Effect Dir',   'jacqueline');
			$list['square effect_shift'] = esc_html__('Square Effect Shift', 'jacqueline');
			$list['square effect_book']  = esc_html__('Square Effect Book',  'jacqueline');
			$list['square effect_more']  = esc_html__('Square Effect More',  'jacqueline');
			$list['square effect_fade']  = esc_html__('Square Effect Fade',  'jacqueline');
			$list = apply_filters('jacqueline_filter_portfolio_hovers', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_hovers', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}


// Return list of the blog counters
if ( !function_exists( 'jacqueline_get_list_blog_counters' ) ) {
	function jacqueline_get_list_blog_counters($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_blog_counters'))=='') {
			$list = array(
				'views'		=> esc_html__('Views', 'jacqueline'),
				'likes'		=> esc_html__('Likes', 'jacqueline'),
				'rating'	=> esc_html__('Rating', 'jacqueline'),
				'comments'	=> esc_html__('Comments', 'jacqueline')
				);
			$list = apply_filters('jacqueline_filter_list_blog_counters', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_blog_counters', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return list of the item sizes for the portfolio alter style, prepended inherit
if ( !function_exists( 'jacqueline_get_list_alter_sizes' ) ) {
	function jacqueline_get_list_alter_sizes($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_alter_sizes'))=='') {
			$list = array(
					'1_1' => esc_html__('1x1', 'jacqueline'),
					'1_2' => esc_html__('1x2', 'jacqueline'),
					'2_1' => esc_html__('2x1', 'jacqueline'),
					'2_2' => esc_html__('2x2', 'jacqueline'),
					'1_3' => esc_html__('1x3', 'jacqueline'),
					'2_3' => esc_html__('2x3', 'jacqueline'),
					'3_1' => esc_html__('3x1', 'jacqueline'),
					'3_2' => esc_html__('3x2', 'jacqueline'),
					'3_3' => esc_html__('3x3', 'jacqueline')
					);
			$list = apply_filters('jacqueline_filter_portfolio_alter_sizes', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_alter_sizes', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'jacqueline_get_list_hovers_directions' ) ) {
	function jacqueline_get_list_hovers_directions($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_hovers_directions'))=='') {
			$list = array(
				'left_to_right' => esc_html__('Left to Right',  'jacqueline'),
				'right_to_left' => esc_html__('Right to Left',  'jacqueline'),
				'top_to_bottom' => esc_html__('Top to Bottom',  'jacqueline'),
				'bottom_to_top' => esc_html__('Bottom to Top',  'jacqueline'),
				'scale_up'      => esc_html__('Scale Up',  'jacqueline'),
				'scale_down'    => esc_html__('Scale Down',  'jacqueline'),
				'scale_down_up' => esc_html__('Scale Down-Up',  'jacqueline'),
				'from_left_and_right' => esc_html__('From Left and Right',  'jacqueline'),
				'from_top_and_bottom' => esc_html__('From Top and Bottom',  'jacqueline')
			);
			$list = apply_filters('jacqueline_filter_portfolio_hovers_directions', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_hovers_directions', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'jacqueline_get_list_label_positions' ) ) {
	function jacqueline_get_list_label_positions($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_label_positions'))=='') {
			$list = array(
				'top'		=> esc_html__('Top',		'jacqueline'),
				'bottom'	=> esc_html__('Bottom',		'jacqueline'),
				'left'		=> esc_html__('Left',		'jacqueline'),
				'over'		=> esc_html__('Over',		'jacqueline')
			);
			$list = apply_filters('jacqueline_filter_label_positions', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_label_positions', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'jacqueline_get_list_bg_image_positions' ) ) {
	function jacqueline_get_list_bg_image_positions($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_bg_image_positions'))=='') {
			$list = array(
				'left top'	   => esc_html__('Left Top', 'jacqueline'),
				'center top'   => esc_html__("Center Top", 'jacqueline'),
				'right top'    => esc_html__("Right Top", 'jacqueline'),
				'left center'  => esc_html__("Left Center", 'jacqueline'),
				'center center'=> esc_html__("Center Center", 'jacqueline'),
				'right center' => esc_html__("Right Center", 'jacqueline'),
				'left bottom'  => esc_html__("Left Bottom", 'jacqueline'),
				'center bottom'=> esc_html__("Center Bottom", 'jacqueline'),
				'right bottom' => esc_html__("Right Bottom", 'jacqueline')
			);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_bg_image_positions', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'jacqueline_get_list_bg_image_repeats' ) ) {
	function jacqueline_get_list_bg_image_repeats($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_bg_image_repeats'))=='') {
			$list = array(
				'repeat'	=> esc_html__('Repeat', 'jacqueline'),
				'repeat-x'	=> esc_html__('Repeat X', 'jacqueline'),
				'repeat-y'	=> esc_html__('Repeat Y', 'jacqueline'),
				'no-repeat'	=> esc_html__('No Repeat', 'jacqueline')
			);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_bg_image_repeats', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'jacqueline_get_list_bg_image_attachments' ) ) {
	function jacqueline_get_list_bg_image_attachments($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_bg_image_attachments'))=='') {
			$list = array(
				'scroll'	=> esc_html__('Scroll', 'jacqueline'),
				'fixed'		=> esc_html__('Fixed', 'jacqueline'),
				'local'		=> esc_html__('Local', 'jacqueline')
			);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_bg_image_attachments', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}


// Return list of the bg tints
if ( !function_exists( 'jacqueline_get_list_bg_tints' ) ) {
	function jacqueline_get_list_bg_tints($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_bg_tints'))=='') {
			$list = array(
				'white'	=> esc_html__('White', 'jacqueline'),
				'light'	=> esc_html__('Light', 'jacqueline'),
				'dark'	=> esc_html__('Dark', 'jacqueline')
			);
			$list = apply_filters('jacqueline_filter_bg_tints', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_bg_tints', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'jacqueline_get_list_field_types' ) ) {
	function jacqueline_get_list_field_types($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_field_types'))=='') {
			$list = array(
				'text'     => esc_html__('Text',  'jacqueline'),
				'textarea' => esc_html__('Text Area','jacqueline'),
				'password' => esc_html__('Password',  'jacqueline'),
				'radio'    => esc_html__('Radio',  'jacqueline'),
				'checkbox' => esc_html__('Checkbox',  'jacqueline'),
				'select'   => esc_html__('Select',  'jacqueline'),
				'date'     => esc_html__('Date','jacqueline'),
				'time'     => esc_html__('Time','jacqueline'),
				'button'   => esc_html__('Button','jacqueline')
			);
			$list = apply_filters('jacqueline_filter_field_types', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_field_types', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'jacqueline_get_list_googlemap_styles' ) ) {
	function jacqueline_get_list_googlemap_styles($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_googlemap_styles'))=='') {
			$list = array(
				'default' => esc_html__('Default', 'jacqueline')
			);
			$list = apply_filters('jacqueline_filter_googlemap_styles', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_googlemap_styles', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return iconed classes list
if ( !function_exists( 'jacqueline_get_list_icons' ) ) {
	function jacqueline_get_list_icons($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_icons'))=='') {
			$list = jacqueline_parse_icons_classes(jacqueline_get_file_dir("css/fontello/css/fontello-codes.css"));
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_icons', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'jacqueline_get_list_socials' ) ) {
	function jacqueline_get_list_socials($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_socials'))=='') {
			$list = jacqueline_get_list_files("images/socials", "png");
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_socials', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return flags list
if ( !function_exists( 'jacqueline_get_list_flags' ) ) {
	function jacqueline_get_list_flags($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_flags'))=='') {
			$list = jacqueline_get_list_files("images/flags", "png");
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_flags', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'jacqueline_get_list_yesno' ) ) {
	function jacqueline_get_list_yesno($prepend_inherit=false) {
		$list = array(
			'yes' => esc_html__("Yes", 'jacqueline'),
			'no'  => esc_html__("No", 'jacqueline')
		);
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'jacqueline_get_list_onoff' ) ) {
	function jacqueline_get_list_onoff($prepend_inherit=false) {
		$list = array(
			"on" => esc_html__("On", 'jacqueline'),
			"off" => esc_html__("Off", 'jacqueline')
		);
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'jacqueline_get_list_showhide' ) ) {
	function jacqueline_get_list_showhide($prepend_inherit=false) {
		$list = array(
			"show" => esc_html__("Show", 'jacqueline'),
			"hide" => esc_html__("Hide", 'jacqueline')
		);
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'jacqueline_get_list_orderings' ) ) {
	function jacqueline_get_list_orderings($prepend_inherit=false) {
		$list = array(
			"asc" => esc_html__("Ascending", 'jacqueline'),
			"desc" => esc_html__("Descending", 'jacqueline')
		);
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'jacqueline_get_list_directions' ) ) {
	function jacqueline_get_list_directions($prepend_inherit=false) {
		$list = array(
			"horizontal" => esc_html__("Horizontal", 'jacqueline'),
			"vertical" => esc_html__("Vertical", 'jacqueline')
		);
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'jacqueline_get_list_shapes' ) ) {
	function jacqueline_get_list_shapes($prepend_inherit=false) {
		$list = array(
			"round"  => esc_html__("Round", 'jacqueline'),
			"square" => esc_html__("Square", 'jacqueline')
		);
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'jacqueline_get_list_sizes' ) ) {
	function jacqueline_get_list_sizes($prepend_inherit=false) {
		$list = array(
			"tiny"   => esc_html__("Tiny", 'jacqueline'),
			"small"  => esc_html__("Small", 'jacqueline'),
			"medium" => esc_html__("Medium", 'jacqueline'),
			"large"  => esc_html__("Large", 'jacqueline')
		);
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return list with slider (scroll) controls positions
if ( !function_exists( 'jacqueline_get_list_controls' ) ) {
	function jacqueline_get_list_controls($prepend_inherit=false) {
		$list = array(
			"hide" => esc_html__("Hide", 'jacqueline'),
			"side" => esc_html__("Side", 'jacqueline'),
			"bottom" => esc_html__("Bottom", 'jacqueline')
		);
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'jacqueline_get_list_floats' ) ) {
	function jacqueline_get_list_floats($prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'jacqueline'),
			"left" => esc_html__("Float Left", 'jacqueline'),
			"right" => esc_html__("Float Right", 'jacqueline')
		);
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'jacqueline_get_list_alignments' ) ) {
	function jacqueline_get_list_alignments($justify=false, $prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'jacqueline'),
			"left" => esc_html__("Left", 'jacqueline'),
			"center" => esc_html__("Center", 'jacqueline'),
			"right" => esc_html__("Right", 'jacqueline')
		);
		if ($justify) $list["justify"] = esc_html__("Justify", 'jacqueline');
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return list with horizontal positions
if ( !function_exists( 'jacqueline_get_list_hpos' ) ) {
	function jacqueline_get_list_hpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['left'] = esc_html__("Left", 'jacqueline');
		if ($center) $list['center'] = esc_html__("Center", 'jacqueline');
		$list['right'] = esc_html__("Right", 'jacqueline');
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return list with vertical positions
if ( !function_exists( 'jacqueline_get_list_vpos' ) ) {
	function jacqueline_get_list_vpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['top'] = esc_html__("Top", 'jacqueline');
		if ($center) $list['center'] = esc_html__("Center", 'jacqueline');
		$list['bottom'] = esc_html__("Bottom", 'jacqueline');
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'jacqueline_get_list_sortings' ) ) {
	function jacqueline_get_list_sortings($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_sortings'))=='') {
			$list = array(
				"date" => esc_html__("Date", 'jacqueline'),
				"title" => esc_html__("Alphabetically", 'jacqueline'),
				"views" => esc_html__("Popular (views count)", 'jacqueline'),
				"comments" => esc_html__("Most commented (comments count)", 'jacqueline'),
				"author_rating" => esc_html__("Author rating", 'jacqueline'),
				"users_rating" => esc_html__("Visitors (users) rating", 'jacqueline'),
				"random" => esc_html__("Random", 'jacqueline')
			);
			$list = apply_filters('jacqueline_filter_list_sortings', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_sortings', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'jacqueline_get_list_columns' ) ) {
	function jacqueline_get_list_columns($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_columns'))=='') {
			$list = array(
				"none" => esc_html__("None", 'jacqueline'),
				"1_1" => esc_html__("100%", 'jacqueline'),
				"1_2" => esc_html__("1/2", 'jacqueline'),
				"1_3" => esc_html__("1/3", 'jacqueline'),
				"2_3" => esc_html__("2/3", 'jacqueline'),
				"1_4" => esc_html__("1/4", 'jacqueline'),
				"3_4" => esc_html__("3/4", 'jacqueline'),
				"1_5" => esc_html__("1/5", 'jacqueline'),
				"2_5" => esc_html__("2/5", 'jacqueline'),
				"3_5" => esc_html__("3/5", 'jacqueline'),
				"4_5" => esc_html__("4/5", 'jacqueline'),
				"1_6" => esc_html__("1/6", 'jacqueline'),
				"5_6" => esc_html__("5/6", 'jacqueline'),
				"1_7" => esc_html__("1/7", 'jacqueline'),
				"2_7" => esc_html__("2/7", 'jacqueline'),
				"3_7" => esc_html__("3/7", 'jacqueline'),
				"4_7" => esc_html__("4/7", 'jacqueline'),
				"5_7" => esc_html__("5/7", 'jacqueline'),
				"6_7" => esc_html__("6/7", 'jacqueline'),
				"1_8" => esc_html__("1/8", 'jacqueline'),
				"3_8" => esc_html__("3/8", 'jacqueline'),
				"5_8" => esc_html__("5/8", 'jacqueline'),
				"7_8" => esc_html__("7/8", 'jacqueline'),
				"1_9" => esc_html__("1/9", 'jacqueline'),
				"2_9" => esc_html__("2/9", 'jacqueline'),
				"4_9" => esc_html__("4/9", 'jacqueline'),
				"5_9" => esc_html__("5/9", 'jacqueline'),
				"7_9" => esc_html__("7/9", 'jacqueline'),
				"8_9" => esc_html__("8/9", 'jacqueline'),
				"1_10"=> esc_html__("1/10", 'jacqueline'),
				"3_10"=> esc_html__("3/10", 'jacqueline'),
				"7_10"=> esc_html__("7/10", 'jacqueline'),
				"9_10"=> esc_html__("9/10", 'jacqueline'),
				"1_11"=> esc_html__("1/11", 'jacqueline'),
				"2_11"=> esc_html__("2/11", 'jacqueline'),
				"3_11"=> esc_html__("3/11", 'jacqueline'),
				"4_11"=> esc_html__("4/11", 'jacqueline'),
				"5_11"=> esc_html__("5/11", 'jacqueline'),
				"6_11"=> esc_html__("6/11", 'jacqueline'),
				"7_11"=> esc_html__("7/11", 'jacqueline'),
				"8_11"=> esc_html__("8/11", 'jacqueline'),
				"9_11"=> esc_html__("9/11", 'jacqueline'),
				"10_11"=> esc_html__("10/11", 'jacqueline'),
				"1_12"=> esc_html__("1/12", 'jacqueline'),
				"5_12"=> esc_html__("5/12", 'jacqueline'),
				"7_12"=> esc_html__("7/12", 'jacqueline'),
				"10_12"=> esc_html__("10/12", 'jacqueline'),
				"11_12"=> esc_html__("11/12", 'jacqueline')
			);
			$list = apply_filters('jacqueline_filter_list_columns', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_columns', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'jacqueline_get_list_dedicated_locations' ) ) {
	function jacqueline_get_list_dedicated_locations($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_dedicated_locations'))=='') {
			$list = array(
				"default" => esc_html__('As in the post defined', 'jacqueline'),
				"center"  => esc_html__('Above the text of the post', 'jacqueline'),
				"left"    => esc_html__('To the left the text of the post', 'jacqueline'),
				"right"   => esc_html__('To the right the text of the post', 'jacqueline'),
				"alter"   => esc_html__('Alternates for each post', 'jacqueline')
			);
			$list = apply_filters('jacqueline_filter_list_dedicated_locations', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_dedicated_locations', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'jacqueline_get_post_format_name' ) ) {
	function jacqueline_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'jacqueline') : esc_html__('galleries', 'jacqueline');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'jacqueline') : esc_html__('videos', 'jacqueline');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'jacqueline') : esc_html__('audios', 'jacqueline');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'jacqueline') : esc_html__('images', 'jacqueline');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'jacqueline') : esc_html__('quotes', 'jacqueline');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'jacqueline') : esc_html__('links', 'jacqueline');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'jacqueline') : esc_html__('statuses', 'jacqueline');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'jacqueline') : esc_html__('asides', 'jacqueline');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'jacqueline') : esc_html__('chats', 'jacqueline');
		else						$name = $single ? esc_html__('standard', 'jacqueline') : esc_html__('standards', 'jacqueline');
		return apply_filters('jacqueline_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'jacqueline_get_post_format_icon' ) ) {
	function jacqueline_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return apply_filters('jacqueline_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'jacqueline_get_list_fonts_styles' ) ) {
	function jacqueline_get_list_fonts_styles($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_fonts_styles'))=='') {
			$list = array(
				'i' => esc_html__('I','jacqueline'),
				'u' => esc_html__('U', 'jacqueline')
			);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_fonts_styles', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'jacqueline_get_list_fonts' ) ) {
	function jacqueline_get_list_fonts($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_fonts'))=='') {
			$list = array();
			$list = jacqueline_array_merge($list, jacqueline_get_list_font_faces());
			// Google and custom fonts list:
			//$list['Advent Pro'] = array(
			//		'family'=>'sans-serif',																						// (required) font family
			//		'link'=>'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
			//		'css'=>jacqueline_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
			//		);
			$list = jacqueline_array_merge($list, array(
				'Advent Pro' => array('family'=>'sans-serif'),
				'Alegreya Sans' => array('family'=>'sans-serif'),
				'Arimo' => array('family'=>'sans-serif'),
				'Asap' => array('family'=>'sans-serif'),
				'Averia Sans Libre' => array('family'=>'cursive'),
				'Averia Serif Libre' => array('family'=>'cursive'),
				'Bree Serif' => array('family'=>'serif',),
				'Cabin' => array('family'=>'sans-serif'),
				'Cabin Condensed' => array('family'=>'sans-serif'),
				'Caudex' => array('family'=>'serif'),
				'Comfortaa' => array('family'=>'cursive'),
				'Cousine' => array('family'=>'sans-serif'),
				'Crimson Text' => array('family'=>'serif'),
				'Cuprum' => array('family'=>'sans-serif'),
				'Dosis' => array('family'=>'sans-serif'),
				'Droid Serif' => array('family'=>'serif'),
				'Economica' => array('family'=>'sans-serif'),
				'Exo' => array('family'=>'sans-serif'),
				'Expletus Sans' => array('family'=>'cursive'),
				'Karla' => array('family'=>'sans-serif'),
				'Lato' => array('family'=>'sans-serif'),
				'Lekton' => array('family'=>'sans-serif'),
				'Lobster Two' => array('family'=>'cursive'),
				'Lora' => array('family'=>'sans-serif'),
				'Maven Pro' => array('family'=>'sans-serif'),
				'Merriweather' => array('family'=>'serif'),
				'Montserrat' => array('family'=>'sans-serif'),
				'Neuton' => array('family'=>'serif'),
				'Noticia Text' => array('family'=>'serif'),
				'Old Standard TT' => array('family'=>'serif'),
				'Open Sans' => array('family'=>'sans-serif'),
				'Orbitron' => array('family'=>'sans-serif'),
				'Oswald' => array('family'=>'sans-serif'),
				'Overlock' => array('family'=>'cursive'),
				'Oxygen' => array('family'=>'sans-serif'),
				'Philosopher' => array('family'=>'serif'),
				'PT Serif' => array('family'=>'serif'),
				'Puritan' => array('family'=>'sans-serif'),
				'Raleway' => array('family'=>'sans-serif'),
				'Roboto' => array('family'=>'sans-serif'),
				'Roboto Slab' => array('family'=>'sans-serif'),
				'Roboto Condensed' => array('family'=>'sans-serif'),
				'Rosario' => array('family'=>'sans-serif'),
				'Share' => array('family'=>'cursive'),
				'Signika' => array('family'=>'sans-serif'),
				'Signika Negative' => array('family'=>'sans-serif'),
				'Source Sans Pro' => array('family'=>'sans-serif'),
				'Tinos' => array('family'=>'serif'),
				'Ubuntu' => array('family'=>'sans-serif'),
				'Vollkorn' => array('family'=>'serif')
				)
			);
			$list = apply_filters('jacqueline_filter_list_fonts', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_fonts', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'jacqueline_get_list_font_faces' ) ) {
	function jacqueline_get_list_font_faces($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$list = array();
		$dir = jacqueline_get_folder_dir("css/font-face");
		if ( is_dir($dir) ) {
			$hdir = @ opendir( $dir );
			if ( $hdir ) {
				while (($file = readdir( $hdir ) ) !== false ) {
					$pi = pathinfo( ($dir) . '/' . ($file) );
					if ( substr($file, 0, 1) == '.' || ! is_dir( ($dir) . '/' . ($file) ) )
						continue;
					$css = file_exists( ($dir) . '/' . ($file) . '/' . ($file) . '.css' ) 
						? jacqueline_get_folder_url("css/font-face/".($file).'/'.($file).'.css')
						: (file_exists( ($dir) . '/' . ($file) . '/stylesheet.css' ) 
							? jacqueline_get_folder_url("css/font-face/".($file).'/stylesheet.css')
							: '');
					if ($css != '')
						$list[$file.' ('.esc_html__('uploaded font', 'jacqueline').')'] = array('css' => $css);
				}
				@closedir( $hdir );
			}
		}
		return $list;
	}
}
?>