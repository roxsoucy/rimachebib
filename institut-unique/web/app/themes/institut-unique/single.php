<?php
/**
 * Single post
 */
get_header(); 

$single_style = jacqueline_storage_get('single_style');
if (empty($single_style)) $single_style = jacqueline_get_custom_option('single_style');

while ( have_posts() ) { the_post();
	jacqueline_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !jacqueline_param_is_off(jacqueline_get_custom_option('show_sidebar_main')),
			'content' => jacqueline_get_template_property($single_style, 'need_content'),
			'terms_list' => jacqueline_get_template_property($single_style, 'need_terms')
		)
	);
}

get_footer();
?>