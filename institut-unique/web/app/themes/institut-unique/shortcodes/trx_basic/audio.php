<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jacqueline_sc_audio_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_sc_audio_theme_setup' );
	function jacqueline_sc_audio_theme_setup() {
		add_action('jacqueline_action_shortcodes_list', 		'jacqueline_sc_audio_reg_shortcodes');
		if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
			add_action('jacqueline_action_shortcodes_list_vc','jacqueline_sc_audio_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */


if (!function_exists('jacqueline_sc_audio')) {	
	function jacqueline_sc_audio($atts, $content = null) {
		if (jacqueline_in_shortcode_blogger()) return '';
		extract(jacqueline_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"author" => "",
			"image" => "",
			"mp3" => '',
			"wav" => '',
			"src" => '',
			"url" => '',
			"align" => '',
			"controls" => "",
			"autoplay" => "",
			"frame" => "on",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => '',
			"height" => '',
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		if ($src=='' && $url=='' && isset($atts[0])) {
			$src = $atts[0];
		}
		if ($src=='') {
			if ($url) $src = $url;
			else if ($mp3) $src = $mp3;
			else if ($wav) $src = $wav;
		}
		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		$css .= ($css ? '; ' : '') . jacqueline_get_css_position_from_values($top, $right, $bottom, $left);
		$data = ($title != ''  ? ' data-title="'.esc_attr($title).'"'   : '')
				. ($author != '' ? ' data-author="'.esc_attr($author).'"' : '')
				. ($image != ''  ? ' data-image="'.esc_url($image).'"'   : '')
				. ($align && $align!='none' ? ' data-align="'.esc_attr($align).'"' : '')
				. (!jacqueline_param_is_off($animation) ? ' data-animation="'.esc_attr(jacqueline_get_animation_classes($animation)).'"' : '');
		$audio = '<audio'
			. ($id ? ' id="'.esc_attr($id).'"' : '')
			. ' class="sc_audio' . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
			. ' src="'.esc_url($src).'"'
			. (jacqueline_param_is_on($controls) ? ' controls="controls"' : '')
			. (jacqueline_param_is_on($autoplay) && is_single() ? ' autoplay="autoplay"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. ($data)
			. '></audio>';
		if ( jacqueline_get_custom_option('substitute_audio')=='no') {
			if (jacqueline_param_is_on($frame)) {
				$audio = jacqueline_get_audio_frame($audio, $image, $s);
			}
		} else {
			if ((isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')) {
				$audio = jacqueline_substitute_audio($audio, false);
			}
		}
		if (jacqueline_get_theme_option('use_mediaelement')=='yes')
			jacqueline_enqueue_script('wp-mediaelement');
		return apply_filters('jacqueline_shortcode_output', $audio, 'trx_audio', $atts, $content);
	}
	jacqueline_require_shortcode("trx_audio", "jacqueline_sc_audio");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_sc_audio_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_sc_audio_reg_shortcodes');
	function jacqueline_sc_audio_reg_shortcodes() {
	
		jacqueline_sc_map("trx_audio", array(
			"title" => esc_html__("Audio", 'jacqueline'),
			"desc" => wp_kses_data( __("Insert audio player", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"url" => array(
					"title" => esc_html__("URL for audio file", 'jacqueline'),
					"desc" => wp_kses_data( __("URL for audio file", 'jacqueline') ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'title' => esc_html__('Choose audio', 'jacqueline'),
						'action' => 'media_upload',
						'type' => 'audio',
						'multiple' => false,
						'linked_field' => '',
						'captions' => array( 	
							'choose' => esc_html__('Choose audio file', 'jacqueline'),
							'update' => esc_html__('Select audio file', 'jacqueline')
						)
					),
					"after" => array(
						'icon' => 'icon-cancel',
						'action' => 'media_reset'
					)
				),
				"image" => array(
					"title" => esc_html__("Cover image", 'jacqueline'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for audio cover", 'jacqueline') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"title" => array(
					"title" => esc_html__("Title", 'jacqueline'),
					"desc" => wp_kses_data( __("Title of the audio file", 'jacqueline') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"author" => array(
					"title" => esc_html__("Author", 'jacqueline'),
					"desc" => wp_kses_data( __("Author of the audio file", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"controls" => array(
					"title" => esc_html__("Show controls", 'jacqueline'),
					"desc" => wp_kses_data( __("Show controls in audio player", 'jacqueline') ),
					"divider" => true,
					"size" => "medium",
					"value" => "show",
					"type" => "switch",
					"options" => jacqueline_get_sc_param('show_hide')
				),
				"autoplay" => array(
					"title" => esc_html__("Autoplay audio", 'jacqueline'),
					"desc" => wp_kses_data( __("Autoplay audio on page load", 'jacqueline') ),
					"value" => "off",
					"type" => "switch",
					"options" => jacqueline_get_sc_param('on_off')
				),
				"align" => array(
					"title" => esc_html__("Align", 'jacqueline'),
					"desc" => wp_kses_data( __("Select block alignment", 'jacqueline') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => jacqueline_get_sc_param('align')
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
if ( !function_exists( 'jacqueline_sc_audio_reg_shortcodes_vc' ) ) {
	//add_action('jacqueline_action_shortcodes_list_vc', 'jacqueline_sc_audio_reg_shortcodes_vc');
	function jacqueline_sc_audio_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_audio",
			"name" => esc_html__("Audio", 'jacqueline'),
			"description" => wp_kses_data( __("Insert audio player", 'jacqueline') ),
			"category" => esc_html__('Content', 'jacqueline'),
			'icon' => 'icon_trx_audio',
			"class" => "trx_sc_single trx_sc_audio",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "url",
					"heading" => esc_html__("URL for audio file", 'jacqueline'),
					"description" => wp_kses_data( __("Put here URL for audio file", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Cover image", 'jacqueline'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for audio cover", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'jacqueline'),
					"description" => wp_kses_data( __("Title of the audio file", 'jacqueline') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "author",
					"heading" => esc_html__("Author", 'jacqueline'),
					"description" => wp_kses_data( __("Author of the audio file", 'jacqueline') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "controls",
					"heading" => esc_html__("Controls", 'jacqueline'),
					"description" => wp_kses_data( __("Show/hide controls", 'jacqueline') ),
					"class" => "",
					"value" => array("Hide controls" => "hide" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "autoplay",
					"heading" => esc_html__("Autoplay", 'jacqueline'),
					"description" => wp_kses_data( __("Autoplay audio on page load", 'jacqueline') ),
					"class" => "",
					"value" => array("Autoplay" => "on" ),
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
			),
		) );
		
		class WPBakeryShortCode_Trx_Audio extends JACQUELINE_VC_ShortCodeSingle {}
	}
}
?>