<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'jacqueline_template_form_2_theme_setup' ) ) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_template_form_2_theme_setup', 1 );
	function jacqueline_template_form_2_theme_setup() {
		jacqueline_add_template(array(
			'layout' => 'form_2',
			'mode'   => 'forms',
			'title'  => esc_html__('Contact Form 2', 'jacqueline')
			));
	}
}

// Template output
if ( !function_exists( 'jacqueline_template_form_2_output' ) ) {
	function jacqueline_template_form_2_output($post_options, $post_data) {
		$address_1 = jacqueline_get_theme_option('contact_address_1');
		$address_2 = jacqueline_get_theme_option('contact_address_2');
		$phone = jacqueline_get_theme_option('contact_phone');
		$fax = jacqueline_get_theme_option('contact_fax');
		$email = jacqueline_get_theme_option('contact_email');
		$open_hours = jacqueline_get_theme_option('contact_open_hours');
		?>
		<div class="sc_columns columns_wrap">
			<div class="sc_form_address column-1_3">
				<div class="sc_form_address_field">
					<span class="sc_form_address_label"><?php esc_html_e('Address', 'jacqueline'); ?></span>
					<span class="sc_form_address_data"><?php echo trim($address_1) . (!empty($address_1) && !empty($address_2) ? ', ' : '') . $address_2; ?></span>
				</div>
				<div class="sc_form_address_field">
					<span class="sc_form_address_label"><?php esc_html_e('We are open', 'jacqueline'); ?></span>
					<span class="sc_form_address_data"><?php echo trim($open_hours); ?></span>
				</div>
				<div class="sc_form_address_field">
					<span class="sc_form_address_label"><?php esc_html_e('Phone', 'jacqueline'); ?></span>
					<span class="sc_form_address_data"><?php echo trim($phone) . (!empty($phone) && !empty($fax) ? ', ' : '') . $fax; ?></span>
				</div>
				<div class="sc_form_address_field">
					<span class="sc_form_address_label"><?php esc_html_e('E-mail', 'jacqueline'); ?></span>
					<span class="sc_form_address_data"><?php echo trim($email); ?></span>
				</div>
				<?php echo do_shortcode('[trx_socials size="tiny" shape="round"][/trx_socials]'); ?>
			</div><div class="sc_form_fields column-2_3">
				<form <?php echo !empty($post_options['id']) ? ' id="'.esc_attr($post_options['id']).'_form"' : ''; ?> data-formtype="<?php echo esc_attr($post_options['layout']); ?>" method="post" action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : admin_url('admin-ajax.php')); ?>">
					<?php jacqueline_sc_form_show_fields($post_options['fields']); ?>
					<div class="sc_form_info">
						<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_username"><?php esc_html_e('Name', 'jacqueline'); ?></label><input id="sc_form_username" type="text" name="username" placeholder="<?php esc_attr_e('Name *', 'jacqueline'); ?>"></div>
						<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_email"><?php esc_html_e('E-mail', 'jacqueline'); ?></label><input id="sc_form_email" type="text" name="email" placeholder="<?php esc_attr_e('E-mail *', 'jacqueline'); ?>"></div>
						<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_subj"><?php esc_html_e('Subject', 'jacqueline'); ?></label><input id="sc_form_subj" type="text" name="subject" placeholder="<?php esc_attr_e('Subject', 'jacqueline'); ?>"></div>
					</div>
					<div class="sc_form_item sc_form_message label_over"><label class="required" for="sc_form_message"><?php esc_html_e('Message', 'jacqueline'); ?></label><textarea id="sc_form_message" name="message" placeholder="<?php esc_attr_e('Message', 'jacqueline'); ?>"></textarea></div>
					<div class="sc_form_item sc_form_button"><button class="sc_button sc_button_style_filled sc_button_size_medium">
						<span class="overlay">
							<span class="first"><?php esc_html_e('Submit message', 'jacqueline'); ?></span>
							<span class="second"><?php esc_html_e('Submit message', 'jacqueline'); ?></span>				
						</span>
					</button></div>
					<div class="result sc_infobox"></div>
				</form>
			</div>
		</div>
		<?php
	}
}
?>