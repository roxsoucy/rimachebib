<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jacqueline_sc_title_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_title_theme_setup' );
	function jacqueline_sc_title_theme_setup() {
		add_action('jacqueline_action_shortcodes_list', 		'jacqueline_sc_title_reg_shortcodes');
		if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
			add_action('jacqueline_action_shortcodes_list_vc','jacqueline_sc_title_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_title id="unique_id" style='regular|iconed' icon='' image='' background="on|off" type="1-6"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_title]
*/

if (!function_exists('jacqueline_sc_title')) {	
	function jacqueline_sc_title($atts, $content=null){	
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "1",
			"style" => "regular",
			"align" => "",
			"font_weight" => "",
			"font_size" => "",
			"color" => "",
			"icon" => "",
			"image" => "",
			"picture" => "",
			"image_size" => "small",
			"position" => "left",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= ($css ? '; ' : '') . jacqueline_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= jacqueline_get_css_dimensions_from_values($width)
			.($align && $align!='none' && !jacqueline_param_is_inherit($align) ? 'text-align:' . esc_attr($align) .';' : '')
			.($color ? 'color:' . esc_attr($color) .';' : '')
			.($font_weight && !jacqueline_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) .';' : '')
			.($font_size   ? 'font-size:' . esc_attr($font_size) .';' : '')
			;
		$type = min(6, max(1, $type));
		if ($picture > 0) {
			$attach = wp_get_attachment_image_src( $picture, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$picture = $attach[0];
		}
		$pic = $style!='iconed' 
			? '' 
			: '<span class="sc_title_icon sc_title_icon_'.esc_attr($position).'  sc_title_icon_'.esc_attr($image_size).($icon!='' && $icon!='none' ? ' '.esc_attr($icon) : '').'"'.'>'
				.($picture ? '<img src="'.esc_url($picture).'" alt="" />' : '')
				.(empty($picture) && $image && $image!='none' ? '<img src="'.esc_url(jacqueline_strpos($image, 'http:')!==false ? $image : jacqueline_get_file_url('images/icons/'.($image).'.png')).'" alt="" />' : '')
				.'</span>';
		$output = '<h' . esc_attr($type) . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_title sc_title_'.esc_attr($style)
					.($align && $align!='none' && !jacqueline_param_is_inherit($align) ? ' sc_align_' . esc_attr($align) : '')
					.(!empty($class) ? ' '.esc_attr($class) : '')
					.'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!jacqueline_param_is_off($animation) ? ' data-animation="'.esc_attr(jacqueline_get_animation_classes($animation)).'"' : '')
				. '>'
					. ($pic)
					. ($style=='divider' ? '<span class="sc_title_divider_before"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
					. do_shortcode($content) 
					. ($style=='divider' ? '<span class="sc_title_divider_after"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
				. '</h' . esc_attr($type) . '>';
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_title', $atts, $content);
	}
	jacqueline_require_shortcode('trx_title', 'jacqueline_sc_title');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_title_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_sc_title_reg_shortcodes');
	function jacqueline_sc_title_reg_shortcodes() {
	
		jacqueline_sc_map("trx_title", array(
			"title" => esc_html__("Title", 'jacqueline'),
			"desc" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'jacqueline') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Title content", 'jacqueline'),
					"desc" => wp_kses_data( __("Title content", 'jacqueline') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"type" => array(
					"title" => esc_html__("Title type", 'jacqueline'),
					"desc" => wp_kses_data( __("Title type (header level)", 'jacqueline') ),
					"divider" => true,
					"value" => "1",
					"type" => "select",
					"options" => array(
						'1' => esc_html__('Header 1', 'jacqueline'),
						'2' => esc_html__('Header 2', 'jacqueline'),
						'3' => esc_html__('Header 3', 'jacqueline'),
						'4' => esc_html__('Header 4', 'jacqueline'),
						'5' => esc_html__('Header 5', 'jacqueline'),
						'6' => esc_html__('Header 6', 'jacqueline'),
					)
				),
				"style" => array(
					"title" => esc_html__("Title style", 'jacqueline'),
					"desc" => wp_kses_data( __("Title style", 'jacqueline') ),
					"value" => "regular",
					"type" => "select",
					"options" => array(
						'regular' => esc_html__('Regular', 'jacqueline'),
						'underline' => esc_html__('Underline', 'jacqueline'),
						'divider' => esc_html__('Divider', 'jacqueline'),
						'iconed' => esc_html__('With icon (image)', 'jacqueline')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'jacqueline'),
					"desc" => wp_kses_data( __("Title text alignment", 'jacqueline') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => jacqueline_get_sc_param('align')
				), 
				"font_size" => array(
					"title" => esc_html__("Font_size", 'jacqueline'),
					"desc" => wp_kses_data( __("Custom font size. If empty - use theme default", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'jacqueline'),
					"desc" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'jacqueline') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'inherit' => esc_html__('Default', 'jacqueline'),
						'100' => esc_html__('Thin (100)', 'jacqueline'),
						'300' => esc_html__('Light (300)', 'jacqueline'),
						'400' => esc_html__('Normal (400)', 'jacqueline'),
						'600' => esc_html__('Semibold (600)', 'jacqueline'),
						'700' => esc_html__('Bold (700)', 'jacqueline'),
						'900' => esc_html__('Black (900)', 'jacqueline')
					)
				),
				"color" => array(
					"title" => esc_html__("Title color", 'jacqueline'),
					"desc" => wp_kses_data( __("Select color for the title", 'jacqueline') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('Title font icon',  'jacqueline'),
					"desc" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)",  'jacqueline') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => jacqueline_get_sc_param('icons')
				),
				"image" => array(
					"title" => esc_html__('or image icon',  'jacqueline'),
					"desc" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)",  'jacqueline') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "images",
					"size" => "small",
					"options" => jacqueline_get_sc_param('images')
				),
				"picture" => array(
					"title" => esc_html__('or URL for image file', 'jacqueline'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'jacqueline') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_size" => array(
					"title" => esc_html__('Image (picture) size', 'jacqueline'),
					"desc" => wp_kses_data( __("Select image (picture) size (if style='iconed')", 'jacqueline') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "small",
					"type" => "checklist",
					"options" => array(
						'small' => esc_html__('Small', 'jacqueline'),
						'medium' => esc_html__('Medium', 'jacqueline'),
						'large' => esc_html__('Large', 'jacqueline')
					)
				),
				"position" => array(
					"title" => esc_html__('Icon (image) position', 'jacqueline'),
					"desc" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'jacqueline') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "left",
					"type" => "checklist",
					"options" => array(
						'top' => esc_html__('Top', 'jacqueline'),
						'left' => esc_html__('Left', 'jacqueline')
					)
				),
				"top" => jacqueline_get_sc_param('top'),
				"bottom" => jacqueline_get_sc_param('bottom'),
				"left" => jacqueline_get_sc_param('left'),
				"right" => jacqueline_get_sc_param('right'),
				"id" => jacqueline_get_sc_param('id'),
				"class" => jacqueline_get_sc_param('class'),
				"animation" => jacqueline_get_sc_param('animation'),
				"css" => jacqueline_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_title_reg_shortcodes_vc' ) ) {
	//add_action('jacqueline_action_shortcodes_list_vc', 'jacqueline_sc_title_reg_shortcodes_vc');
	function jacqueline_sc_title_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_title",
			"name" => esc_html__("Title", 'jacqueline'),
			"description" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'jacqueline') ),
			"category" => esc_html__('Content', 'jacqueline'),
			'icon' => 'icon_trx_title',
			"class" => "trx_sc_single trx_sc_title",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Title content", 'jacqueline'),
					"description" => wp_kses_data( __("Title content", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Title type", 'jacqueline'),
					"description" => wp_kses_data( __("Title type (header level)", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Header 1', 'jacqueline') => '1',
						esc_html__('Header 2', 'jacqueline') => '2',
						esc_html__('Header 3', 'jacqueline') => '3',
						esc_html__('Header 4', 'jacqueline') => '4',
						esc_html__('Header 5', 'jacqueline') => '5',
						esc_html__('Header 6', 'jacqueline') => '6'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Title style", 'jacqueline'),
					"description" => wp_kses_data( __("Title style: only text (regular) or with icon/image (iconed)", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'jacqueline') => 'regular',
						esc_html__('Underline', 'jacqueline') => 'underline',
						esc_html__('Divider', 'jacqueline') => 'divider',
						esc_html__('With icon (image)', 'jacqueline') => 'iconed'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'jacqueline'),
					"description" => wp_kses_data( __("Title text alignment", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(jacqueline_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'jacqueline'),
					"description" => wp_kses_data( __("Custom font size. If empty - use theme default", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'jacqueline'),
					"description" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'jacqueline') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'jacqueline') => 'inherit',
						esc_html__('Thin (100)', 'jacqueline') => '100',
						esc_html__('Light (300)', 'jacqueline') => '300',
						esc_html__('Normal (400)', 'jacqueline') => '400',
						esc_html__('Semibold (600)', 'jacqueline') => '600',
						esc_html__('Bold (700)', 'jacqueline') => '700',
						esc_html__('Black (900)', 'jacqueline') => '900'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Title color", 'jacqueline'),
					"description" => wp_kses_data( __("Select color for the title", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title font icon", 'jacqueline'),
					"description" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)", 'jacqueline') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'jacqueline'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => jacqueline_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("or image icon", 'jacqueline'),
					"description" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)", 'jacqueline') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'jacqueline'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => jacqueline_get_sc_param('images'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "picture",
					"heading" => esc_html__("or select uploaded image", 'jacqueline'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'jacqueline') ),
					"group" => esc_html__('Icon &amp; Image', 'jacqueline'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_size",
					"heading" => esc_html__("Image (picture) size", 'jacqueline'),
					"description" => wp_kses_data( __("Select image (picture) size (if style=iconed)", 'jacqueline') ),
					"group" => esc_html__('Icon &amp; Image', 'jacqueline'),
					"class" => "",
					"value" => array(
						esc_html__('Small', 'jacqueline') => 'small',
						esc_html__('Medium', 'jacqueline') => 'medium',
						esc_html__('Large', 'jacqueline') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Icon (image) position", 'jacqueline'),
					"description" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'jacqueline') ),
					"group" => esc_html__('Icon &amp; Image', 'jacqueline'),
					"class" => "",
					"std" => "left",
					"value" => array(
						esc_html__('Top', 'jacqueline') => 'top',
						esc_html__('Left', 'jacqueline') => 'left'
					),
					"type" => "dropdown"
				),
				jacqueline_get_vc_param('id'),
				jacqueline_get_vc_param('class'),
				jacqueline_get_vc_param('animation'),
				jacqueline_get_vc_param('css'),
				jacqueline_get_vc_param('margin_top'),
				jacqueline_get_vc_param('margin_bottom'),
				jacqueline_get_vc_param('margin_left'),
				jacqueline_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Title extends JACQUELINE_VC_ShortCodeSingle {}
	}
}
?>