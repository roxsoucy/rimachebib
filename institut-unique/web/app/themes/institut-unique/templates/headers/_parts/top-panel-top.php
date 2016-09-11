<?php 
// Get template args
extract(jacqueline_template_get_args('top-panel-top'));

if (in_array('contact_info', $top_panel_top_components) && ($contact_info=trim(jacqueline_get_custom_option('contact_info')))!='') {
	?>
	<div class="top_panel_top_contact_area">
		<?php echo force_balance_tags($contact_info); ?>
	</div>
	<?php
}
if (in_array('email', $top_panel_top_components) && ($contact_email=trim(jacqueline_get_custom_option('contact_email')))!='') {
	?>
	<div class="top_panel_top_contact_area">
		<?php echo esc_html_e('Contact Us: ', 'jacqueline').force_balance_tags($contact_email); ?>
	</div>
	<?php
}
if (in_array('phone', $top_panel_top_components) && ($contact_phone=trim(jacqueline_get_custom_option('contact_phone')))!='') {
	?>
	<div class="top_panel_top_contact_area">
		<?php echo esc_html_e('Call Us ', 'jacqueline').force_balance_tags($contact_phone); ?>
	</div>
	<?php
}
?>

<?php
if (in_array('open_hours', $top_panel_top_components) && ($open_hours=trim(jacqueline_get_custom_option('contact_open_hours')))!='') {
	?>
	<div class="top_panel_top_open_hours icon-clock"><?php echo force_balance_tags($open_hours); ?></div>
	<?php
}
?>

<div class="top_panel_top_user_area">
	<?php
	
	if (in_array('socials', $top_panel_top_components) && jacqueline_get_custom_option('show_socials')=='yes' && jacqueline_get_custom_option('top_panel_style') == 'header_3') {
		?>
		<div class="top_panel_top_socials">
			<span class="label"><?php echo esc_html_e('Stay connected: ', 'jacqueline'); ?></span>
			<?php echo trim(jacqueline_sc_socials(array('size'=>'tiny'))); ?>
		</div>
		<?php
	}

	$menu_user = jacqueline_get_nav_menu('menu_user');
	if (empty($menu_user)) {
		?>
		<ul class="menu_user_nav">
		<?php
	} else {
		$menu = jacqueline_substr($menu_user, 0, jacqueline_strlen($menu_user)-5);
		$pos = jacqueline_strpos($menu, '<ul');
		if ($pos!==false) $menu = jacqueline_substr($menu, 0, $pos+3) . ' class="menu_user_nav"' . jacqueline_substr($menu, $pos+3);
		echo str_replace('class=""', '', $menu);
	}
	

	if (in_array('currency', $top_panel_top_components) && function_exists('jacqueline_is_woocommerce_page') && jacqueline_is_woocommerce_page() && jacqueline_get_custom_option('show_currency')=='yes') {
		?>
		<li class="menu_user_currency">
			<a href="#">$</a>
			<ul>
				<li><a href="#"><b>&#36;</b> <?php esc_html_e('Dollar', 'jacqueline'); ?></a></li>
				<li><a href="#"><b>&euro;</b> <?php esc_html_e('Euro', 'jacqueline'); ?></a></li>
				<li><a href="#"><b>&pound;</b> <?php esc_html_e('Pounds', 'jacqueline'); ?></a></li>
			</ul>
		</li>
		<?php
	}

	if (in_array('language', $top_panel_top_components) && jacqueline_get_custom_option('show_languages')=='yes' && function_exists('icl_get_languages')) {
		$languages = icl_get_languages('skip_missing=1');
		if (!empty($languages) && is_array($languages)) {
			$lang_list = '';
			$lang_active = '';
			foreach ($languages as $lang) {
				$lang_title = esc_attr($lang['translated_name']);	//esc_attr($lang['native_name']);
				if ($lang['active']) {
					$lang_active = $lang_title;
				}
				$lang_list .= "\n"
					.'<li><a rel="alternate" hreflang="' . esc_attr($lang['language_code']) . '" href="' . esc_url(apply_filters('WPML_filter_link', $lang['url'], $lang)) . '">'
						.'<img src="' . esc_url($lang['country_flag_url']) . '" alt="' . esc_attr($lang_title) . '" title="' . esc_attr($lang_title) . '" />'
						. ($lang_title)
					.'</a></li>';
			}
			?>
			<li class="menu_user_language">
				<a href="#"><span><?php echo trim($lang_active); ?></span></a>
				<ul><?php echo trim($lang_list); ?></ul>
			</li>
			<?php
		}
	}

	if (in_array('bookmarks', $top_panel_top_components) && jacqueline_get_custom_option('show_bookmarks')=='yes') {
		// Load core messages
		jacqueline_enqueue_messages();
		?>
		<li class="menu_user_bookmarks"><a href="#" class="bookmarks_show icon-star" title="<?php esc_attr_e('Show bookmarks', 'jacqueline'); ?>"><?php esc_html_e('Bookmarks', 'jacqueline'); ?></a>
		<?php 
			$list = jacqueline_get_value_gpc('jacqueline_bookmarks', '');
			if (!empty($list)) $list = json_decode($list, true);
			?>
			<ul class="bookmarks_list">
				<li><a href="#" class="bookmarks_add icon-star-empty" title="<?php esc_attr_e('Add the current page into bookmarks', 'jacqueline'); ?>"><?php esc_html_e('Add bookmark', 'jacqueline'); ?></a></li>
				<?php 
				if (!empty($list) && is_array($list)) {
					foreach ($list as $bm) {
						echo '<li><a href="'.esc_url($bm['url']).'" class="bookmarks_item">'.($bm['title']).'<span class="bookmarks_delete icon-cancel" title="'.esc_attr__('Delete this bookmark', 'jacqueline').'"></span></a></li>';
					}
				}
				?>
			</ul>
		</li>
		<?php 
	}

	if (in_array('login', $top_panel_top_components) && jacqueline_get_custom_option('show_login')=='yes') {
		if ( !is_user_logged_in() ) {
			// Load core messages
			jacqueline_enqueue_messages();
			// Anyone can register ?
			if ( (int) get_option('users_can_register') > 0) {
				?><li class="menu_user_register"><a href="#popup_registration" class="popup_link popup_register_link icon-pencil"><?php esc_html_e('Register', 'jacqueline'); ?></a></li><?php
			}
			?><li class="menu_user_login"><a href="#popup_login" class="popup_link popup_login_link icon-user"><?php esc_html_e('Login', 'jacqueline'); ?></a></li><?php 
		} else {
			$current_user = wp_get_current_user();
			?>
			<li class="menu_user_controls">
				<a href="#" class="icon-user"><?php
					$user_avatar = '';
					$mult = jacqueline_get_retina_multiplier();
					if ($current_user->user_email) $user_avatar = get_avatar($current_user->user_email, 16*$mult);
					if ($user_avatar) {
					}?><span class="user_name"><?php echo trim($current_user->display_name); ?></span></a>
				<ul>
					<?php if (current_user_can('publish_posts')) { ?>
					<li><a href="<?php echo esc_url(home_url('/')); ?>/wp-admin/post-new.php?post_type=post" class="icon icon-doc"><?php esc_html_e('New post', 'jacqueline'); ?></a></li>
					<?php } ?>
					<li><a href="<?php echo get_edit_user_link(); ?>" class="icon icon-cog"><?php esc_html_e('Settings', 'jacqueline'); ?></a></li>
				</ul>
			</li>
			<li class="menu_user_logout"><a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="icon icon-logout"><?php esc_html_e('Logout', 'jacqueline'); ?></a></li>
			<?php 
		}
	}

	if (in_array('cart', $top_panel_top_components) && function_exists('jacqueline_exists_woocommerce') && jacqueline_exists_woocommerce() && (jacqueline_is_woocommerce_page() && jacqueline_get_custom_option('show_cart')=='shop' || jacqueline_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) { 
		?>
		<li class="menu_user_cart">
			<?php get_template_part(jacqueline_get_file_slug('templates/headers/_parts/contact-info-cart.php')); ?>
		</li>
		<?php
	}
	?>

	</ul>
</div>
<?php
	if (in_array('socials', $top_panel_top_components) && jacqueline_get_custom_option('show_socials')=='yes' && jacqueline_get_custom_option('top_panel_style') != 'header_3') {
		?>
		<div class="top_panel_top_socials">
			<span class="label"><?php echo esc_html_e('Stay connected: ', 'jacqueline'); ?></span>
			<?php echo trim(jacqueline_sc_socials(array('size'=>'tiny'))); ?>
		</div>
		<?php
	}
	if (in_array('search', $top_panel_top_components) && jacqueline_get_custom_option('show_search')=='yes') {
		?>
		<div class="top_panel_top_search"><?php echo trim(jacqueline_sc_search(array('state'=>'closed'))); ?></div>
		<?php
	}
?>