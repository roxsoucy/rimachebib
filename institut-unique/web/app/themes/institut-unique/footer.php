<?php
/**
 * The template for displaying the footer.
 */

				jacqueline_close_wrapper();	// <!-- </.content> -->

				jacqueline_profiler_add_point(esc_html__('After Page content', 'jacqueline'));
	
				// Show main sidebar
				get_sidebar();

				if (jacqueline_get_custom_option('body_style')!='fullscreen') jacqueline_close_wrapper();	// <!-- </.content_wrap> -->
				?>
			
			</div>		<!-- </.page_content_wrap> -->
			
			<?php
			
			// Footer sidebar
			get_template_part(jacqueline_get_file_slug('templates/_parts/footer-sidebar.php'));

			// Footer contacts
			get_template_part(jacqueline_get_file_slug('templates/_parts/footer-contacts.php'));
			
			// Copyright area
			get_template_part(jacqueline_get_file_slug('templates/_parts/footer-copyright-area.php'));
			
			jacqueline_profiler_add_point(esc_html__('After Footer', 'jacqueline'));
			?>
			
		</div>	<!-- /.page_wrap -->

	</div>		<!-- /.body_wrap -->
	
	<?php if ( !jacqueline_param_is_off(jacqueline_get_custom_option('show_sidebar_outer')) ) { ?>
	</div>	<!-- /.outer_wrap -->
	<?php } ?>

<?php
// Post/Page views counter
get_template_part(jacqueline_get_file_slug('templates/_parts/views-counter.php'));

// Login/Register
if (jacqueline_get_theme_option('show_login')=='yes') {
	jacqueline_enqueue_popup();
	// Anyone can register ?
	if ( (int) get_option('users_can_register') > 0) {
		get_template_part(jacqueline_get_file_slug('templates/_parts/popup-register.php'));
	}
	get_template_part(jacqueline_get_file_slug('templates/_parts/popup-login.php'));
}

// Front customizer
if (jacqueline_get_custom_option('show_theme_customizer')=='yes') {
	require_once trailingslashit( get_template_directory() ) . 'core/core.customizer/front.customizer.php';
}


// Scroll to top
if (jacqueline_get_custom_option('scroll_to_top')=='yes') {
	?>
	<a href="#" class="scroll_to_top icon-up" title="<?php esc_attr_e('Scroll to top', 'jacqueline'); ?>"></a>
	<?php
}
?>


<div class="custom_html_section">
<?php echo force_balance_tags(jacqueline_get_custom_option('custom_code')); ?>
</div>

<?php
echo force_balance_tags(jacqueline_get_custom_option('gtm_code2'));

jacqueline_profiler_add_point(esc_html__('After Theme HTML output', 'jacqueline'));
	
wp_footer(); 
?>

</body>
</html>