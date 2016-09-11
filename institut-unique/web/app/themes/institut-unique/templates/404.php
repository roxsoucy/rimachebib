<?php
/*
 * The template for displaying "Page 404"
*/

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'jacqueline_template_404_theme_setup' ) ) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_template_404_theme_setup', 1 );
	function jacqueline_template_404_theme_setup() {
		jacqueline_add_template(array(
			'layout' => '404',
			'mode'   => 'internal',
			'title'  => 'Page 404',
			'theme_options' => array(
				'article_style' => 'stretch'
			)
		));
	}
}

// Template output
if ( !function_exists( 'jacqueline_template_404_output' ) ) {
	function jacqueline_template_404_output() {
		?>
		<article class="post_item post_item_404">
			<div class="post_content">
				<h1 class="page_title"><?php esc_html_e( '404!', 'jacqueline' ); ?></h1>
				<h2 class="page_subtitle"><?php esc_html_e('SORRY! PAGE NOT FOUND!', 'jacqueline'); ?></h2>
				<div class="page_home"><a href="<?php echo esc_url(home_url('/')); ?>" class="sc_button sc_button_style_filled sc_button_size_medium"><?php esc_html_e('Go to home Page', 'jacqueline'); ?></a></div>
				<div class="page_or"><?php esc_html_e('Or', 'jacqueline'); ?></div>
				<div class="page_search"><?php echo trim(jacqueline_sc_search(array('state'=>'fixed', 'title'=>__('To search type and hit enter', 'jacqueline')))); ?></div>
			</div>
		</article>
		<?php
	}
}
?>