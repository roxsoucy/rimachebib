<?php
/**
 * Attachment page
 */
get_header(); 

while ( have_posts() ) { the_post();

	// Move jacqueline_set_post_views to the javascript - counter will work under cache system
	if (jacqueline_get_custom_option('use_ajax_views_counter')=='no') {
		jacqueline_set_post_views(get_the_ID());
	}

	jacqueline_show_post_layout(
		array(
			'layout' => 'attachment',
			'sidebar' => !jacqueline_param_is_off(jacqueline_get_custom_option('show_sidebar_main'))
		)
	);

}

get_footer();
?>