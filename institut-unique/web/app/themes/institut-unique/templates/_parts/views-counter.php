<?php 
if (is_singular()) {
	if (jacqueline_get_theme_option('use_ajax_views_counter')=='yes') {
		?>
		<!-- Post/page views count increment -->
		<script type="text/javascript">
			jQuery(document).ready(function() {
				setTimeout(function(){
					jQuery.post(JACQUELINE_STORAGE['ajax_url'], {
						action: 'post_counter',
						nonce: JACQUELINE_STORAGE['ajax_nonce'],
						post_id: <?php echo (int) get_the_ID(); ?>,
						views: <?php echo (int) jacqueline_get_post_views(get_the_ID()); ?>
					});
					}, 10);
			});
		</script>
		<?php
	} else
		jacqueline_set_post_views(get_the_ID());
}
?>