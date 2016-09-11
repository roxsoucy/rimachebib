<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'jacqueline_template_portfolio_theme_setup' ) ) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_template_portfolio_theme_setup', 1 );
	function jacqueline_template_portfolio_theme_setup() {
		jacqueline_add_template(array(
			'layout' => 'portfolio_2',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Portfolio tile (with hovers, different height) /2 columns/', 'jacqueline'),
			'thumb_title'  => esc_html__('Medium image', 'jacqueline'),
			'w'		 => 370,
			'h_crop' => 209,
			'h'		 => null
		));
		jacqueline_add_template(array(
			'layout' => 'portfolio_3',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Portfolio tile /3 columns/', 'jacqueline'),
			'thumb_title'  => esc_html__('Medium image', 'jacqueline'),
			'w'		 => 370,
			'h_crop' => 209,
			'h'		 => null
		));
		jacqueline_add_template(array(
			'layout' => 'portfolio_4',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Portfolio tile /4 columns/', 'jacqueline'),
			'thumb_title'  => esc_html__('Medium image', 'jacqueline'),
			'w'		 => 370,
			'h_crop' => 209,
			'h'		 => null
		));
		jacqueline_add_template(array(
			'layout' => 'short_2',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'need_terms' => true,
			'container_classes' => '',
			'title'  => esc_html__('Short info /2 columns/', 'jacqueline'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'jacqueline'),
			'w'		 => 370,
			'h' 	 => 370
		));
		jacqueline_add_template(array(
			'layout' => 'short_3',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'need_terms' => true,
			'container_classes' => '',
			'title'  => esc_html__('Short info /3 columns/', 'jacqueline'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'jacqueline'),
			'w'		 => 370,
			'h'		 => 370
		));
		jacqueline_add_template(array(
			'layout' => 'short_4',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'need_terms' => true,
			'container_classes' => '',
			'title'  => esc_html__('Short info /4 columns/', 'jacqueline'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'jacqueline'),
			'w'		 => 370,
			'h'		 => 370
		));
		// Add template specific scripts
		add_action('jacqueline_action_blog_scripts', 'jacqueline_template_portfolio_add_scripts');
	}
}

// Add template specific scripts
if (!function_exists('jacqueline_template_portfolio_add_scripts')) {
	//add_action('jacqueline_action_blog_scripts', 'jacqueline_template_portfolio_add_scripts');
	function jacqueline_template_portfolio_add_scripts($style) {
		if (jacqueline_substr($style, 0, 10) == 'portfolio_' 
			|| jacqueline_substr($style, 0, 5) == 'grid_' 
			|| jacqueline_substr($style, 0, 7) == 'square_' 
			|| jacqueline_substr($style, 0, 6) == 'short_'
			|| jacqueline_substr($style, 0, 6) == 'alter_' 
			|| jacqueline_substr($style, 0, 8) == 'colored_') {
			jacqueline_enqueue_script( 'isotope', jacqueline_get_file_url('js/jquery.isotope.min.js'), array(), null, true );
			if ($style != 'colored_1')  {
				jacqueline_enqueue_script( 'hoverdir', jacqueline_get_file_url('js/hover/jquery.hoverdir.js'), array(), null, true );
				jacqueline_enqueue_style( 'jacqueline-portfolio-style', jacqueline_get_file_url('css/core.portfolio.css'), array(), null );
			}
		}
	}
}

// Template output
if ( !function_exists( 'jacqueline_template_portfolio_output' ) ) {
	function jacqueline_template_portfolio_output($post_options, $post_data) {
		$show_title = !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'));
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($post_options['columns_count']) 
									? (empty($parts[1]) ? 1 : (int) $parts[1])
									: $post_options['columns_count']
									));
		$tag = jacqueline_in_shortcode_blogger(true) ? 'div' : 'article';
		$link_start = !isset($post_options['links']) || $post_options['links'] ? '<a href="'.esc_url($post_data['post_link']).'">' : '';
		$link_end = !isset($post_options['links']) || $post_options['links'] ? '</a>' : '';
									
		?>
		<div class="isotope_item isotope_item_<?php echo esc_attr($style); ?> isotope_item_<?php echo esc_attr($post_options['layout']); ?> isotope_column_<?php echo esc_attr($columns); ?>
			<?php
			if ($post_options['filters'] != '') {
				if ($post_options['filters']=='categories' && !empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms_ids))
					echo ' flt_' . esc_attr(join(' flt_', $post_data['post_terms'][$post_data['post_taxonomy']]->terms_ids));
				else if ($post_options['filters']=='tags' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_ids))
					echo ' flt_' . esc_attr(join(' flt_', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_ids));
			}
			?>">
			<<?php echo trim($tag); ?> class="post_item post_item_<?php echo esc_attr($style); ?> post_item_<?php echo esc_attr($post_options['layout']); ?>
				<?php echo 'post_format_'.esc_attr($post_data['post_format']) 
					. ($post_options['number']%2==0 ? ' even' : ' odd') 
					. ($post_options['number']==0 ? ' first' : '') 
					. ($post_options['number']==$post_options['posts_on_page'] ? ' last' : '')
					. ($post_data['post_type'] == 'players' ? ' type_player' : '');
				?>">

				<div class="post_content isotope_item_content<?php
					if ($style!='colored') {
						echo ' ih-item colored'
							. (!empty($post_options['hover']) ? ' '.esc_attr($post_options['hover']) : '')
							. (!empty($post_options['hover_dir']) ? ' '.esc_attr($post_options['hover_dir']) : '');
					}
				 ?>">
				 
					<div class="post_featured img">
					<?php 
						echo trim($link_start) . trim($post_data['post_thumb']) . trim($link_end);
					?>
					</div>		
					<div class="post_info_wrap info"><div class="info-back">
					<?php
						if($style != 'short'){
						?>
							<div class="post_category"><?php echo the_terms( $post_data['post_id'], 'category', '', '' ); ?></div>
						<?php
						}
						
						if ($show_title) {
							?><h4 class="post_title"><?php echo trim($link_start) . trim($post_data['post_title']) . trim($link_end); ?></h4><?php
						}
						?>

						<div class="post_descr">
						<?php
							if ($post_data['post_protected']) {
								echo trim($link_start) . trim($post_data['post_excerpt']) . trim($link_end);
							} else {
								if ($post_data['post_excerpt']) {
									echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) 
										? ( ($link_start) . ($post_data['post_excerpt']) . ($link_end) )
										: '<p>' . ($link_start) 
											. (jacqueline_strpos($post_options['hover'], 'square')!==false
												? strip_tags($post_data['post_excerpt'])
												: trim(jacqueline_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : jacqueline_get_custom_option('post_excerpt_maxlength_masonry'))) 
												)
											. ($link_end) . '</p>';
								}
								if ($post_data['post_link'] != '' && $style != 'short') {
									?><p class="post_buttons"><?php
									if (!jacqueline_param_is_off($post_options['readmore']) && !in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status'))) {
										?><a href="<?php echo esc_url($post_data['post_link']); ?>" class="post_readmore sc_button sc_button_size_large sc_button_style_filled"><?php echo trim($post_options['readmore']); ?></a><?php
									}
									?></p><?php
								}
							}
						?>
						</div>
					</div></div>	<!-- /.info-back /.info -->
				</div>				<!-- /.post_content -->
			</<?php echo trim($tag); ?>>	<!-- /.post_item -->
		</div>						<!-- /.isotope_item -->
		<?php		
	}
}
?>