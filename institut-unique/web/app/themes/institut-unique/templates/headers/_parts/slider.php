<?php
if (jacqueline_get_custom_option('show_slider')=='yes') { 
	$slider = jacqueline_get_custom_option('slider_engine');
	$slider_alias = $slider_ids = $slider_html = '';
	$slider_over_content = jacqueline_get_custom_option('slider_over_content');
	
	if ($slider == 'revo' && function_exists('jacqueline_exists_revslider') && jacqueline_exists_revslider()) {
		$slider_alias = jacqueline_get_custom_option('slider_alias');
		if (!empty($slider_alias)) $slider_html = jacqueline_do_shortcode('[rev_slider '.esc_attr($slider_alias).']');

	} else if ($slider == 'royal' && function_exists('jacqueline_exists_royalslider') && jacqueline_exists_royalslider()) {
		$slider_alias = get_new_royalslider($slider_alias);
		if (!empty($slider_alias)) $slider_html = jacqueline_do_shortcode('[rev_slider '.esc_attr($slider_alias).']');
		jacqueline_enqueue_style(  'new-royalslider-core-css', NEW_ROYALSLIDER_PLUGIN_URL . 'lib/royalslider/royalslider.css', array(), null );
		jacqueline_enqueue_script( 'new-royalslider-main-js', NEW_ROYALSLIDER_PLUGIN_URL . 'lib/royalslider/jquery.royalslider.min.js', array('jquery'), NEW_ROYALSLIDER_WP_VERSION, true );

	} else if ($slider == 'swiper') {
		$slider_pagination = jacqueline_get_custom_option("slider_pagination");
		$slider_alias = jacqueline_get_custom_option("slider_category");
		$slider_orderby = jacqueline_get_custom_option("slider_orderby");
		$slider_order = jacqueline_get_custom_option("slider_order");
		$slider_count = $slider_ids = jacqueline_get_custom_option("slider_posts");

		if (jacqueline_strpos($slider_ids, ',')!==false) {
			$slider_alias = '';
			$slider_count = 0;
		} else {
			$slider_ids = '';
			if (empty($slider_count)) $slider_count = 3;
		}

		$slider_interval = jacqueline_get_custom_option("slider_interval");

		if ($slider_count > 0 || !empty($slider_ids)) {
			$args = array(
				'custom'	=> "no",
				'crop'		=> "no",
				'controls'	=> "no",
				'engine'	=> $slider,
				'height'	=> max(100, jacqueline_get_custom_option('slider_height')),
				'titles'	=> jacqueline_get_custom_option("slider_infobox")
			);
			if ($slider_interval)	$args['interval'] = $slider_interval;
			if ($slider_alias)		$args['cat'] = $slider_alias;
			if ($slider_ids)		$args['ids'] = $slider_ids;
			if ($slider_count)		$args['count'] = $slider_count;
			if ($slider_orderby)	$args['orderby'] = $slider_orderby;
			if ($slider_order)		$args['order'] = $slider_order;
			if ($slider_pagination)	$args['pagination'] = $slider_pagination;
			
			$slider_html = jacqueline_sc_slider($args);
		}
	}

	// if slider selected
	if (!empty($slider_html)) {
		?>
		<section class="slider_wrap slider_<?php echo esc_attr(jacqueline_get_custom_option('slider_display')); ?> slider_engine_<?php echo esc_attr($slider); ?> slider_alias_<?php echo esc_attr($slider_alias); ?><?php if (!empty($slider_over_content)) echo ' slider_with_over_content'; ?>">
			<?php
			echo trim($slider_html);
			if (!empty($slider_over_content)) {
				?><div class="slider_over_content scheme_<?php echo esc_attr(jacqueline_get_custom_option('slider_over_scheme')); ?>"><div class="slider_over_content_inner"><?php
				echo do_shortcode($slider_over_content);
				?></div><div class="slider_over_button icon-double-left"></div><div class="slider_over_close icon-cancel"></div></div><?php
			}
			?>
		</section>
		<?php 
		jacqueline_profiler_add_point(esc_html__('After show slider', 'jacqueline'));
	}
}
?>