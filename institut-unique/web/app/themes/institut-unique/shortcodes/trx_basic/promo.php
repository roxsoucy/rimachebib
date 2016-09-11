<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jacqueline_sc_promo_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_promo_theme_setup' );
	function jacqueline_sc_promo_theme_setup() {
		add_action('jacqueline_action_shortcodes_list', 		'jacqueline_sc_promo_reg_shortcodes');
		if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
			add_action('jacqueline_action_shortcodes_list_vc','jacqueline_sc_promo_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */


if (!function_exists('jacqueline_sc_promo')) {	
	function jacqueline_sc_promo($atts, $content=null){	
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "large",
			"align" => "none",
			"image" => "",
			"image_position" => "left",
			"image_width" => "50%",
			"text_width" => "",
			"text_margins" => '',
			"text_paddings" => '',
			"text_align" => "left",
			"scheme" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'jacqueline'),
			"link" => '',
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
	
		if ($image > 0) {
			$attach = wp_get_attachment_image_src($image, 'full');
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		if ($image == '') {
			$image_width = '0%';
			$text_margins = '';
		}
		
		$width  = jacqueline_prepare_css_value($width);
		$height = jacqueline_prepare_css_value($height);
		
		$css .= ($css ? '; ' : '') . jacqueline_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= jacqueline_get_css_dimensions_from_values($width, $height);
		
		$css_image = (!empty($image) ? 'background-image:url(' . esc_url($image) . ');' : '')
				     . (!empty($image_width) ? 'width:'.trim($image_width).';' : '')
				     . (!empty($image_position) ? $image_position.': 0;' : '');
	
		$text_width = (!empty($text_width) ? $text_width : (jacqueline_strpos($image_width, '%')!==false
						? (100 - (int) str_replace('%', '', $image_width)).'%'
						: 'calc(100%-'.trim($image_width).')'));
		$css_text = 'width: '.esc_attr($text_width).'; float: '.($image_position=='left' ? 'right' : 'left').';'.(!empty($text_margins) ? ' margin:'.esc_attr($text_margins).';' : '');
		
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_promo' 
						. ($class ? ' ' . esc_attr($class) : '') 
						. ($scheme && !jacqueline_param_is_off($scheme) && !jacqueline_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						. ($size ? ' sc_promo_size_'.esc_attr($size) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. (empty($image) ? ' no_image' : '')
						. '"'
					. (!jacqueline_param_is_off($animation) ? ' data-animation="'.esc_attr(jacqueline_get_animation_classes($animation)).'"' : '')
					. ($css ? 'style="'.esc_attr($css).'"' : '')
					.'>' 
					. '<div class="sc_promo_inner">'
						. '<div class="sc_promo_image" style="'.esc_attr($css_image).'"></div>'
						. '<div class="sc_promo_block sc_align_'.esc_attr($text_align).'" style="'.esc_attr($css_text).'">'
							. '<div class="sc_promo_block_inner" '.(!empty($text_paddings) ? 'style="padding: '.$text_paddings.';"' : '').'>'
									. (!empty($subtitle) ? '<h6 class="sc_promo_subtitle sc_item_subtitle">' . trim(jacqueline_strmacros($subtitle)) . '</h6>' : '')
									. (!empty($title) ? '<h2 class="sc_promo_title sc_item_title">' . trim(jacqueline_strmacros($title)) . '</h2>' : '')
									. (!empty($description) ? '<div class="sc_promo_descr sc_item_descr">' . trim(jacqueline_strmacros($description)) . '</div>' : '')
									. (!empty($content) ? '<div class="sc_promo_content">'.do_shortcode($content).'</div>' : '')
									. (!empty($link) ? '<div class="sc_promo_button sc_item_button">'.jacqueline_do_shortcode('[trx_button link="'.esc_url($link).'" ]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
							. '</div>'
						. '</div>'
					. '</div>'
				. '</div>';
	
	
	
		return apply_filters('jacqueline_shortcode_output', $output, 'trx_promo', $atts, $content);
	}
	jacqueline_require_shortcode('trx_promo', 'jacqueline_sc_promo');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_promo_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_sc_promo_reg_shortcodes');
	function jacqueline_sc_promo_reg_shortcodes() {
	
		jacqueline_sc_map("trx_promo", array(
			"title" => esc_html__("Promo", 'jacqueline'),
			"desc" => wp_kses_data( __("Insert promo diagramm in your page (post)", 'jacqueline') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"align" => array(
					"title" => esc_html__("Alignment of the promo block", 'jacqueline'),
					"desc" => wp_kses_data( __("Align whole promo block to left or right side of the page or parent container", 'jacqueline') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => jacqueline_get_sc_param('float')
				), 
				"size" => array(
					"title" => esc_html__("Size of the promo block", 'jacqueline'),
					"desc" => wp_kses_data( __("Size of the promo block: large - one in the row, small - insize two or greater columns", 'jacqueline') ),
					"value" => "large",
					"type" => "switch",
					"options" => array(
						'small' => esc_html__('Small', 'jacqueline'),
						'large' => esc_html__('Large', 'jacqueline')
					)
				), 
				"image" => array(
					"title" => esc_html__("Image URL", 'jacqueline'),
					"desc" => wp_kses_data( __("Select the promo image from the library for this section", 'jacqueline') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_position" => array(
					"title" => esc_html__("Image position", 'jacqueline'),
					"desc" => wp_kses_data( __("Place the image to the left or to the right from the text block", 'jacqueline') ),
					"value" => "left",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => jacqueline_get_sc_param('hpos')
				),
				"image_width" => array(
					"title" => esc_html__("Image width", 'jacqueline'),
					"desc" => wp_kses_data( __("Width (in pixels or percents) of the block with image", 'jacqueline') ),
					"value" => "50%",
					"type" => "text"
				),
				"text_width" => array(
					"title" => esc_html__("Text width", 'jacqueline'),
					"desc" => wp_kses_data( __("Width (in pixels or percents) of the block with text", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"text_margins" => array(
					"title" => esc_html__("Text margins", 'jacqueline'),
					"desc" => wp_kses_data( __("Margins for the all sides of the text block (Example: 30px 10px 40px 30px = top right botton left OR 30px = equal for all sides)", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"text_paddings" => array(
					"title" => esc_html__("Text paddings", 'jacqueline'),
					"desc" => wp_kses_data( __("Paddings for the all sides of the text block (Example: 30px 10px 40px 30px = top right botton left OR 30px = equal for all sides)", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"text_align" => array(
					"title" => esc_html__("Text alignment", 'jacqueline'),
					"desc" => wp_kses_data( __("Align the text inside the block", 'jacqueline') ),
					"value" => "left",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => jacqueline_get_sc_param('align')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'jacqueline'),
					"desc" => wp_kses_data( __("Select color scheme for the section with text", 'jacqueline') ),
					"value" => "",
					"type" => "checklist",
					"options" => jacqueline_get_sc_param('schemes')
				),
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
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_promo_reg_shortcodes_vc' ) ) {
	//add_action('jacqueline_action_shortcodes_list_vc', 'jacqueline_sc_promo_reg_shortcodes_vc');
	function jacqueline_sc_promo_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_promo",
			"name" => esc_html__("Promo", 'jacqueline'),
			"description" => wp_kses_data( __("Insert promo block", 'jacqueline') ),
			"category" => esc_html__('Content', 'jacqueline'),
			'icon' => 'icon_trx_promo',
			"class" => "trx_sc_collection trx_sc_promo",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment of the promo block", 'jacqueline'),
					"description" => wp_kses_data( __("Align whole promo block to left or right side of the page or parent container", 'jacqueline') ),
					"class" => "",
					"std" => 'none',
					"value" => array_flip(jacqueline_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Size of the promo block", 'jacqueline'),
					"description" => wp_kses_data( __("Size of the promo block: large - one in the row, small - insize two or greater columns", 'jacqueline') ),
					"class" => "",
					"value" => array(esc_html__('Use small block', 'jacqueline') => 'small'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Image URL", 'jacqueline'),
					"description" => wp_kses_data( __("Select the promo image from the library for this section", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_position",
					"heading" => esc_html__("Image position", 'jacqueline'),
					"description" => wp_kses_data( __("Place the image to the left or to the right from the text block", 'jacqueline') ),
					"class" => "",
					"std" => 'left',
					"value" => array_flip(jacqueline_get_sc_param('hpos')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image_width",
					"heading" => esc_html__("Image width", 'jacqueline'),
					"description" => wp_kses_data( __("Width (in pixels or percents) of the block with image", 'jacqueline') ),
					"value" => '',
					"std" => "50%",
					"type" => "textfield"
				),
				array(
					"param_name" => "text_width",
					"heading" => esc_html__("Text width", 'jacqueline'),
					"description" => wp_kses_data( __("Width (in pixels or percents) of the block with text", 'jacqueline') ),
					"value" => '',
					"std" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "text_margins",
					"heading" => esc_html__("Text margins", 'jacqueline'),
					"description" => wp_kses_data( __("Margins for the all sides of the text block (Example: 30px 10px 40px 30px = top right botton left OR 30px = equal for all sides)", 'jacqueline') ),
					"value" => '',
					"type" => "textfield"
				),
				array(
					"param_name" => "text_paddings",
					"heading" => esc_html__("Text paddings", 'jacqueline'),
					"description" => wp_kses_data( __("Paddings for the all sides of the text block (Example: 30px 10px 40px 30px = top right botton left OR 30px = equal for all sides)", 'jacqueline') ),
					"value" => '',
					"type" => "textfield"
				),
				array(
					"param_name" => "text_align",
					"heading" => esc_html__("Text alignment", 'jacqueline'),
					"description" => wp_kses_data( __("Align text to the left or to the right side inside the block", 'jacqueline') ),
					"class" => "",
					"std" => 'left',
					"value" => array_flip(jacqueline_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'jacqueline'),
					"description" => wp_kses_data( __("Select color scheme for the section with text", 'jacqueline') ),
					"class" => "",
					"value" => array_flip(jacqueline_get_sc_param('schemes')),
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
		) );
		
		class WPBakeryShortCode_Trx_Promo extends JACQUELINE_VC_ShortCodeCollection {}
	}
}
?>