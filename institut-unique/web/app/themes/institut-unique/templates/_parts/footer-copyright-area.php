<?php
		$copyright_style = jacqueline_get_custom_option('show_copyright_in_footer');
		if (!jacqueline_param_is_off($copyright_style)) {
			?> 
			<div class="copyright_wrap copyright_style_<?php echo esc_attr($copyright_style); ?>  scheme_<?php echo esc_attr(jacqueline_get_custom_option('copyright_scheme')); ?>">
				<div class="copyright_wrap_inner">
					<div class="content_wrap">
						<?php
						if ($copyright_style == 'menu') {
							if (($menu = jacqueline_get_nav_menu('menu_footer'))!='') {
								echo trim($menu);
							}
						} else if ($copyright_style == 'socials') {
							echo trim(jacqueline_sc_socials(array('size'=>"tiny", 'type'=>"text")));
							echo '<span class="beforeSocials">'.esc_html__('Connect With Us:', 'jacqueline').'</span>';
						}
						?>
						<div class="copyright_text"><?php echo force_balance_tags(jacqueline_get_custom_option('footer_copyright')); ?></div>
					</div>
				</div>
			</div>
			<?php
		}
?>