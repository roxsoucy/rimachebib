<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jacqueline_sc_section_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_section_theme_setup' );
	function jacqueline_sc_section_theme_setup() {
		add_action('jacqueline_action_shortcodes_list', 		'jacqueline_sc_section_reg_shortcodes');
		if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
			add_action('jacqueline_action_shortcodes_list_vc','jacqueline_sc_section_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_section id="unique_id" class="class_name" style="css-styles" dedicated="yes|no"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_section]
*/

jacqueline_storage_set('sc_section_dedicated', '');

if (!function_exists('jacqueline_sc_section')) {	
	function jacqueline_sc_section($atts, $content=null){	
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			// Individual params
			"dedicated" => "no",
			"align" => "none",
			"columns" => "none",
			"pan" => "no",
			"scroll" => "no",
			"scroll_dir" => "horizontal",
			"scroll_controls" => "hide",
			"color" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
			"bg_tile" => "no",
			"bg_padding" => "yes",
			"font_size" => "",
			"font_weight" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'jacqueline'),
			"link" => '',
			"line" => 'hide',
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
	
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
	
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = jacqueline_get_scheme_color('bg');
			$rgb = jacqueline_hex2rgb($bg_color);
		}
	
		$css .= ($css ? '; ' : '') . jacqueline_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= ($color !== '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');'.(jacqueline_param_is_on($bg_tile) ? 'background-repeat:repeat;' : 'background-repeat:no-repeat;background-size:cover;') : '')
			.(!jacqueline_param_is_off($pan) ? 'position:relative;' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(jacqueline_prepare_css_value($font_size)) . '; line-height: 1.3em;' : '')
			.($font_weight != '' && !jacqueline_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) . ';' : '');
		$css_dim = jacqueline_get_css_dimensions_from_values($width, $height);
		if ($bg_image == '' && $bg_color == '' && $bg_overlay==0 && $bg_texture==0 && jacqueline_strlen($bg_texture)<2) $css .= $css_dim;
		
		$width  = jacqueline_prepare_css_value($width);
		$height = jacqueline_prepare_css_value($height);
	
		if ((!jacqueline_param_is_off($scroll) || !jacqueline_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());
	
		if (!jacqueline_param_is_off($scroll)) jacqueline_enqueue_slider();
	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_section' 
					. ($class ? ' ' . esc_attr($class) : '') 
					. ($scheme && !jacqueline_param_is_off($scheme) && !jacqueline_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($columns) && $columns!='none' ? ' column-'.esc_attr($columns) : '') 
					. (jacqueline_param_is_on($scroll) && !jacqueline_param_is_off($scroll_controls) ? ' sc_scroll_controls sc_scroll_controls_'.esc_attr($scroll_dir).' sc_scroll_controls_type_'.esc_attr($scroll_controls) : '')
					. (!empty($width) ? ' max_width' : '')
					. '"'
				. (!jacqueline_param_is_off($animation) ? ' data-animation="'.esc_attr(jacqueline_get_animation_classes($animation)).'"' : '')
				. ($css!='' || $css_dim!='' ? ' style="'.esc_attr($css.$css_dim).'"' : '')
				.'>' 
				. '<div class="sc_section_inner">'
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay>0 || $bg_texture>0 || jacqueline_strlen($bg_texture)>2
						? '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
							. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
								. (jacqueline_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
								. '"'
								. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
								. '>'
								. '<div class="sc_section_content' . (jacqueline_param_is_on($bg_padding) ? ' padding_on' : ' padding_off') . '"'
								//	. ' style="'.esc_attr($css_dim).'"'
									. '>'
						: '')
					. (jacqueline_param_is_on($scroll) 
						? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($scroll_dir).' swiper-slider-container scroll-container"'
							. ' style="'.($height != '' ? 'height:'.esc_attr($height).';' : '') . ($width != '' ? 'width:'.esc_attr($width).';' : '').'"'
							. '>'
							. '<div class="sc_scroll_wrapper swiper-wrapper">' 
							. '<div class="sc_scroll_slide swiper-slide">' 
						: '')
					. (jacqueline_param_is_on($pan) 
						? '<div id="'.esc_attr($id).'_pan" class="sc_pan sc_pan_'.esc_attr($scroll_dir).'">' 
						: '')
							. (!empty($subtitle) ? '<h6 class="sc_section_subtitle sc_item_subtitle">' . trim(jacqueline_strmacros($subtitle)) . '</h6>' : '')
							. (!empty($title) ? '<h2 class="sc_section_title sc_item_title line_'.$line.'">' . trim(jacqueline_strmacros($title)) . '</h2>' : '')
							. (!empty($description) ? '<div class="sc_section_descr sc_item_descr">' . trim(jacqueline_strmacros($description)) . '</div>' : '')
							. '<div class="sc_section_content_wrap">' . do_shortcode($content) . '</div>'
							. (!empty($link) ? '<div class="sc_section_button sc_item_button">'.jacqueline_do_shortcode('[trx_button link="'.esc_url($link).'" ]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. (jacqueline_param_is_on($pan) ? '</div>' : '')
					. (jacqueline_param_is_on($scroll) 
						? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($scroll_dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
							. (!jacqueline_param_is_off($scroll_controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
						: '')
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay > 0 || $bg_texture>0 || jacqueline_strlen($bg_texture)>2 ? '</div></div>' : '')
					. '</div>'
				. '</div>';
		if (jacqueline_param_is_on($dedicated)) {
			if (jacqueline_storage_get('sc_section_dedicated')=='') {
				jacqueline_storage_set('sc_section_dedicated', $output);
			}
			$output = '';
		}
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_section', $atts, $content);
	}
	jacqueline_require_shortcode('trx_section', 'jacqueline_sc_section');
}

if (!function_exists('jacqueline_sc_block')) {	
	function jacqueline_sc_block($atts, $content=null) {
		$atts['class'] = (!empty($atts['class']) ? ' '.esc_attr($atts['class']).' ' : '') . 'sc_section_block';
		return apply_filters('jacqueline_shortcode_output', jacqueline_sc_section($atts, $content), 'trx_block', $atts, $content);
	}
	jacqueline_require_shortcode('trx_block', 'jacqueline_sc_block');
}


/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_section_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_sc_section_reg_shortcodes');
	function jacqueline_sc_section_reg_shortcodes() {
	
		$sc = array(
			"title" => esc_html__("Block container", 'jacqueline'),
			"desc" => wp_kses_data( __("Container for any block ([trx_section] analog - to enable nesting)", 'jacqueline') ),
			"decorate" => true,
			"container" => true,
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
				"line" => array(
					"title" => esc_html__("Title underline", 'jacqueline'),
					"desc" => wp_kses_data( __("Show or hide title underline", 'jacqueline') ),
					"value" => "hide",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'hide' => esc_html__('Hide', 'jacqueline'),
						'show' => esc_html__('Show', 'jacqueline')
					)
				),
				"description" => array(
					"title" => esc_html__("Description", 'jacqueline'),
					"desc" => wp_kses_data( __("Short description for the block", 'jacqueline') ),
					"value" => "",
					"type" => "textarea"
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
				"dedicated" => array(
					"title" => esc_html__("Dedicated", 'jacqueline'),
					"desc" => wp_kses_data( __("Use this block as dedicated content - show it before post title on single page", 'jacqueline') ),
					"value" => "no",
					"type" => "switch",
					"options" => jacqueline_get_sc_param('yes_no')
				),
				"align" => array(
					"title" => esc_html__("Align", 'jacqueline'),
					"desc" => wp_kses_data( __("Select block alignment", 'jacqueline') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => jacqueline_get_sc_param('align')
				),
				"columns" => array(
					"title" => esc_html__("Columns emulation", 'jacqueline'),
					"desc" => wp_kses_data( __("Select width for columns emulation", 'jacqueline') ),
					"value" => "none",
					"type" => "checklist",
					"options" => jacqueline_get_sc_param('columns')
				), 
				"pan" => array(
					"title" => esc_html__("Use pan effect", 'jacqueline'),
					"desc" => wp_kses_data( __("Use pan effect to show section content", 'jacqueline') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => jacqueline_get_sc_param('yes_no')
				),
				"scroll" => array(
					"title" => esc_html__("Use scroller", 'jacqueline'),
					"desc" => wp_kses_data( __("Use scroller to show section content", 'jacqueline') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => jacqueline_get_sc_param('yes_no')
				),
				"scroll_dir" => array(
					"title" => esc_html__("Scroll and Pan direction", 'jacqueline'),
					"desc" => wp_kses_data( __("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", 'jacqueline') ),
					"dependency" => array(
						'pan' => array('yes'),
						'scroll' => array('yes')
					),
					"value" => "horizontal",
					"type" => "switch",
					"size" => "big",
					"options" => jacqueline_get_sc_param('dir')
				),
				"scroll_controls" => array(
					"title" => esc_html__("Scroll controls", 'jacqueline'),
					"desc" => wp_kses_data( __("Show scroll controls (if Use scroller = yes)", 'jacqueline') ),
					"dependency" => array(
						'scroll' => array('yes')
					),
					"value" => "hide",
					"type" => "checklist",
					"options" => jacqueline_get_sc_param('controls')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'jacqueline'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'jacqueline') ),
					"value" => "",
					"type" => "checklist",
					"options" => jacqueline_get_sc_param('schemes')
				),
				"color" => array(
					"title" => esc_html__("Fore color", 'jacqueline'),
					"desc" => wp_kses_data( __("Any color for objects in this section", 'jacqueline') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'jacqueline'),
					"desc" => wp_kses_data( __("Any background color for this section", 'jacqueline') ),
					"value" => "",
					"type" => "color"
				),
				"bg_image" => array(
					"title" => esc_html__("Background image URL", 'jacqueline'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the background", 'jacqueline') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_tile" => array(
					"title" => esc_html__("Tile background image", 'jacqueline'),
					"desc" => wp_kses_data( __("Do you want tile background image or image cover whole block?", 'jacqueline') ),
					"value" => "no",
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"type" => "switch",
					"options" => jacqueline_get_sc_param('yes_no')
				),
				"bg_overlay" => array(
					"title" => esc_html__("Overlay", 'jacqueline'),
					"desc" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'jacqueline') ),
					"min" => "0",
					"max" => "1",
					"step" => "0.1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_texture" => array(
					"title" => esc_html__("Texture", 'jacqueline'),
					"desc" => wp_kses_data( __("Predefined texture style from 1 to 11. 0 - without texture.", 'jacqueline') ),
					"min" => "0",
					"max" => "11",
					"step" => "1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_padding" => array(
					"title" => esc_html__("Paddings around content", 'jacqueline'),
					"desc" => wp_kses_data( __("Add paddings around content in this section (only if bg_color or bg_image enabled).", 'jacqueline') ),
					"value" => "yes",
					"dependency" => array(
						'compare' => 'or',
						'bg_color' => array('not_empty'),
						'bg_texture' => array('not_empty'),
						'bg_image' => array('not_empty')
					),
					"type" => "switch",
					"options" => jacqueline_get_sc_param('yes_no')
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'jacqueline'),
					"desc" => wp_kses_data( __("Font size of the text (default - in pixels, allows any CSS units of measure)", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'jacqueline'),
					"desc" => wp_kses_data( __("Font weight of the text", 'jacqueline') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'jacqueline'),
						'300' => esc_html__('Light (300)', 'jacqueline'),
						'400' => esc_html__('Normal (400)', 'jacqueline'),
						'700' => esc_html__('Bold (700)', 'jacqueline')
					)
				),
				"_content_" => array(
					"title" => esc_html__("Container content", 'jacqueline'),
					"desc" => wp_kses_data( __("Content for section container", 'jacqueline') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
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
		);
		jacqueline_sc_map("trx_block", $sc);
		$sc["title"] = esc_html__("Section container", 'jacqueline');
		$sc["desc"] = esc_html__("Container for any section ([trx_block] analog - to enable nesting)", 'jacqueline');
		jacqueline_sc_map("trx_section", $sc);
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_section_reg_shortcodes_vc' ) ) {
	//add_action('jacqueline_action_shortcodes_list_vc', 'jacqueline_sc_section_reg_shortcodes_vc');
	function jacqueline_sc_section_reg_shortcodes_vc() {
	
		$sc = array(
			"base" => "trx_block",
			"name" => esc_html__("Block container", 'jacqueline'),
			"description" => wp_kses_data( __("Container for any block ([trx_section] analog - to enable nesting)", 'jacqueline') ),
			"category" => esc_html__('Content', 'jacqueline'),
			'icon' => 'icon_trx_block',
			"class" => "trx_sc_collection trx_sc_block",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "dedicated",
					"heading" => esc_html__("Dedicated", 'jacqueline'),
					"description" => wp_kses_data( __("Use this block as dedicated content - show it before post title on single page", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(esc_html__('Use as dedicated content', 'jacqueline') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'jacqueline'),
					"description" => wp_kses_data( __("Select block alignment", 'jacqueline') ),
					"class" => "",
					"value" => array_flip(jacqueline_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "columns",
					"heading" => esc_html__("Columns emulation", 'jacqueline'),
					"description" => wp_kses_data( __("Select width for columns emulation", 'jacqueline') ),
					"class" => "",
					"value" => array_flip(jacqueline_get_sc_param('columns')),
					"type" => "dropdown"
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
					"param_name" => "line",
					"heading" => esc_html__("Title underline", 'jacqueline'),
					"description" => wp_kses_data( __("Show or hide title underline", 'jacqueline') ),
					"admin_label" => true,
					"group" => esc_html__('Captions', 'jacqueline'),
					"class" => "",
					"value" => array(
						esc_html__('Hide', 'jacqueline') => 'hide',
						esc_html__('Show', 'jacqueline') => 'show'
					),
					"type" => "dropdown"
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
				array(
					"param_name" => "pan",
					"heading" => esc_html__("Use pan effect", 'jacqueline'),
					"description" => wp_kses_data( __("Use pan effect to show section content", 'jacqueline') ),
					"group" => esc_html__('Scroll', 'jacqueline'),
					"class" => "",
					"value" => array(esc_html__('Content scroller', 'jacqueline') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "scroll",
					"heading" => esc_html__("Use scroller", 'jacqueline'),
					"description" => wp_kses_data( __("Use scroller to show section content", 'jacqueline') ),
					"group" => esc_html__('Scroll', 'jacqueline'),
					"admin_label" => true,
					"class" => "",
					"value" => array(esc_html__('Content scroller', 'jacqueline') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "scroll_dir",
					"heading" => esc_html__("Scroll direction", 'jacqueline'),
					"description" => wp_kses_data( __("Scroll direction (if Use scroller = yes)", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"group" => esc_html__('Scroll', 'jacqueline'),
					"value" => array_flip(jacqueline_get_sc_param('dir')),
					'dependency' => array(
						'element' => 'scroll',
						'not_empty' => true
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scroll_controls",
					"heading" => esc_html__("Scroll controls", 'jacqueline'),
					"description" => wp_kses_data( __("Show scroll controls (if Use scroller = yes)", 'jacqueline') ),
					"class" => "",
					"group" => esc_html__('Scroll', 'jacqueline'),
					'dependency' => array(
						'element' => 'scroll',
						'not_empty' => true
					),
					"value" => array_flip(jacqueline_get_sc_param('controls')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'jacqueline'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'jacqueline') ),
					"group" => esc_html__('Colors and Images', 'jacqueline'),
					"class" => "",
					"value" => array_flip(jacqueline_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Fore color", 'jacqueline'),
					"description" => wp_kses_data( __("Any color for objects in this section", 'jacqueline') ),
					"group" => esc_html__('Colors and Images', 'jacqueline'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'jacqueline'),
					"description" => wp_kses_data( __("Any background color for this section", 'jacqueline') ),
					"group" => esc_html__('Colors and Images', 'jacqueline'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image URL", 'jacqueline'),
					"description" => wp_kses_data( __("Select background image from library for this section", 'jacqueline') ),
					"group" => esc_html__('Colors and Images', 'jacqueline'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_tile",
					"heading" => esc_html__("Tile background image", 'jacqueline'),
					"description" => wp_kses_data( __("Do you want tile background image or image cover whole block?", 'jacqueline') ),
					"group" => esc_html__('Colors and Images', 'jacqueline'),
					"class" => "",
					'dependency' => array(
						'element' => 'bg_image',
						'not_empty' => true
					),
					"std" => "no",
					"value" => array(esc_html__('Tile background image', 'jacqueline') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "bg_overlay",
					"heading" => esc_html__("Overlay", 'jacqueline'),
					"description" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'jacqueline') ),
					"group" => esc_html__('Colors and Images', 'jacqueline'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_texture",
					"heading" => esc_html__("Texture", 'jacqueline'),
					"description" => wp_kses_data( __("Texture style from 1 to 11. Empty or 0 - without texture.", 'jacqueline') ),
					"group" => esc_html__('Colors and Images', 'jacqueline'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_padding",
					"heading" => esc_html__("Paddings around content", 'jacqueline'),
					"description" => wp_kses_data( __("Add paddings around content in this section (only if bg_color or bg_image enabled).", 'jacqueline') ),
					"group" => esc_html__('Colors and Images', 'jacqueline'),
					"class" => "",
					'dependency' => array(
						'element' => array('bg_color','bg_texture','bg_image'),
						'not_empty' => true
					),
					"std" => "yes",
					"value" => array(esc_html__('Disable padding around content in this block', 'jacqueline') => 'no'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'jacqueline'),
					"description" => wp_kses_data( __("Font size of the text (default - in pixels, allows any CSS units of measure)", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'jacqueline'),
					"description" => wp_kses_data( __("Font weight of the text", 'jacqueline') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'jacqueline') => 'inherit',
						esc_html__('Thin (100)', 'jacqueline') => '100',
						esc_html__('Light (300)', 'jacqueline') => '300',
						esc_html__('Normal (400)', 'jacqueline') => '400',
						esc_html__('Bold (700)', 'jacqueline') => '700'
					),
					"type" => "dropdown"
				),
				jacqueline_get_vc_param('id'),
				jacqueline_get_vc_param('class'),
				jacqueline_get_vc_param('animation'),
				jacqueline_get_vc_param('css'),
				jacqueline_vc_width(),
				jacqueline_vc_height(),
				jacqueline_get_vc_param('margin_top'),
				jacqueline_get_vc_param('margin_bottom'),
				jacqueline_get_vc_param('margin_left'),
				jacqueline_get_vc_param('margin_right')
			)
		);
		
		// Block
		vc_map($sc);
		
		// Section
		$sc["base"] = 'trx_section';
		$sc["name"] = esc_html__("Section container", 'jacqueline');
		$sc["description"] = wp_kses_data( __("Container for any section ([trx_block] analog - to enable nesting)", 'jacqueline') );
		$sc["class"] = "trx_sc_collection trx_sc_section";
		$sc["icon"] = 'icon_trx_section';
		vc_map($sc);
		
		class WPBakeryShortCode_Trx_Block extends JACQUELINE_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Section extends JACQUELINE_VC_ShortCodeCollection {}
	}
}
?>