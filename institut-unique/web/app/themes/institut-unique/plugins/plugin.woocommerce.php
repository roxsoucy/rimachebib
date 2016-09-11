<?php
/* Woocommerce support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jacqueline_woocommerce_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_woocommerce_theme_setup', 1 );
	function jacqueline_woocommerce_theme_setup() {

		if (jacqueline_exists_woocommerce()) {
			add_action('jacqueline_action_add_styles', 				'jacqueline_woocommerce_frontend_scripts' );

			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('jacqueline_filter_get_blog_type',				'jacqueline_woocommerce_get_blog_type', 9, 2);
			add_filter('jacqueline_filter_get_blog_title',			'jacqueline_woocommerce_get_blog_title', 9, 2);
			add_filter('jacqueline_filter_get_current_taxonomy',		'jacqueline_woocommerce_get_current_taxonomy', 9, 2);
			add_filter('jacqueline_filter_is_taxonomy',				'jacqueline_woocommerce_is_taxonomy', 9, 2);
			add_filter('jacqueline_filter_get_stream_page_title',		'jacqueline_woocommerce_get_stream_page_title', 9, 2);
			add_filter('jacqueline_filter_get_stream_page_link',		'jacqueline_woocommerce_get_stream_page_link', 9, 2);
			add_filter('jacqueline_filter_get_stream_page_id',		'jacqueline_woocommerce_get_stream_page_id', 9, 2);
			add_filter('jacqueline_filter_detect_inheritance_key',	'jacqueline_woocommerce_detect_inheritance_key', 9, 1);
			add_filter('jacqueline_filter_detect_template_page_id',	'jacqueline_woocommerce_detect_template_page_id', 9, 2);
			add_filter('jacqueline_filter_orderby_need',				'jacqueline_woocommerce_orderby_need', 9, 2);

			add_filter('jacqueline_filter_list_post_types', 			'jacqueline_woocommerce_list_post_types', 10, 1);

			add_action('jacqueline_action_shortcodes_list', 			'jacqueline_woocommerce_reg_shortcodes', 20);
			if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
				add_action('jacqueline_action_shortcodes_list_vc',	'jacqueline_woocommerce_reg_shortcodes_vc', 20);

			if (is_admin()) {
				add_filter( 'jacqueline_filter_importer_options',				'jacqueline_woocommerce_importer_set_options' );
				add_action( 'jacqueline_action_importer_after_import_posts',	'jacqueline_woocommerce_importer_after_import_posts', 10, 1 );
				add_action( 'jacqueline_action_importer_params',				'jacqueline_woocommerce_importer_show_params', 10, 1 );
				add_action( 'jacqueline_action_importer_import',				'jacqueline_woocommerce_importer_import', 10, 2 );
				add_action( 'jacqueline_action_importer_import_fields',		'jacqueline_woocommerce_importer_import_fields', 10, 1 );
				add_action( 'jacqueline_action_importer_export',				'jacqueline_woocommerce_importer_export', 10, 1 );
				add_action( 'jacqueline_action_importer_export_fields',		'jacqueline_woocommerce_importer_export_fields', 10, 1 );
			}
		}

		if (is_admin()) {
			add_filter( 'jacqueline_filter_importer_required_plugins',		'jacqueline_woocommerce_importer_required_plugins', 10, 2 );
			add_filter( 'jacqueline_filter_required_plugins',					'jacqueline_woocommerce_required_plugins' );
		}
	}
}

if ( !function_exists( 'jacqueline_woocommerce_settings_theme_setup2' ) ) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_woocommerce_settings_theme_setup2', 3 );
	function jacqueline_woocommerce_settings_theme_setup2() {
		if (jacqueline_exists_woocommerce()) {
			// Add WooCommerce pages in the Theme inheritance system
			jacqueline_add_theme_inheritance( array( 'woocommerce' => array(
				'stream_template' => 'blog-woocommerce',		// This params must be empty
				'single_template' => 'single-woocommerce',		// They are specified to enable separate settings for blog and single wooc
				'taxonomy' => array('product_cat'),
				'taxonomy_tags' => array('product_tag'),
				'post_type' => array('product'),
				'override' => 'page'
				) )
			);

			// Add WooCommerce specific options in the Theme Options

			jacqueline_storage_set_array_before('options', 'partition_service', array(
				
				"partition_woocommerce" => array(
					"title" => esc_html__('WooCommerce', 'jacqueline'),
					"icon" => "iconadmin-basket",
					"type" => "partition"),

				"info_wooc_1" => array(
					"title" => esc_html__('WooCommerce products list parameters', 'jacqueline'),
					"desc" => esc_html__("Select WooCommerce products list's style and crop parameters", 'jacqueline'),
					"type" => "info"),
		
				"shop_mode" => array(
					"title" => esc_html__('Shop list style',  'jacqueline'),
					"desc" => esc_html__("WooCommerce products list's style: thumbs or list with description", 'jacqueline'),
					"std" => "thumbs",
					"divider" => false,
					"options" => array(
						'thumbs' => esc_html__('Thumbs', 'jacqueline'),
						'list' => esc_html__('List', 'jacqueline')
					),
					"type" => "checklist"),
		
				"show_mode_buttons" => array(
					"title" => esc_html__('Show style buttons',  'jacqueline'),
					"desc" => esc_html__("Show buttons to allow visitors change list style", 'jacqueline'),
					"std" => "yes",
					"options" => jacqueline_get_options_param('list_yes_no'),
					"type" => "switch"),

				"shop_loop_columns" => array(
					"title" => esc_html__('Shop columns',  'jacqueline'),
					"desc" => esc_html__("How many columns used to show products on shop page", 'jacqueline'),
					"std" => "3",
					"step" => 1,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),

				"show_currency" => array(
					"title" => esc_html__('Show currency selector', 'jacqueline'),
					"desc" => esc_html__('Show currency selector in the user menu', 'jacqueline'),
					"std" => "yes",
					"options" => jacqueline_get_options_param('list_yes_no'),
					"type" => "switch"),
		
				"show_cart" => array(
					"title" => esc_html__('Show cart button', 'jacqueline'),
					"desc" => esc_html__('Show cart button in the user menu', 'jacqueline'),
					"std" => "shop",
					"options" => array(
						'hide'   => esc_html__('Hide', 'jacqueline'),
						'always' => esc_html__('Always', 'jacqueline'),
						'shop'   => esc_html__('Only on shop pages', 'jacqueline')
					),
					"type" => "checklist"),

				"crop_product_thumb" => array(
					"title" => esc_html__("Crop product's thumbnail",  'jacqueline'),
					"desc" => esc_html__("Crop product's thumbnails on search results page or scale it", 'jacqueline'),
					"std" => "no",
					"options" => jacqueline_get_options_param('list_yes_no'),
					"type" => "switch")
				
				)
			);

		}
	}
}

// WooCommerce hooks
if (!function_exists('jacqueline_woocommerce_theme_setup3')) {
	add_action( 'jacqueline_action_after_init_theme', 'jacqueline_woocommerce_theme_setup3' );
	function jacqueline_woocommerce_theme_setup3() {

		if (jacqueline_exists_woocommerce()) {

			add_action(    'woocommerce_before_subcategory_title',		'jacqueline_woocommerce_open_thumb_wrapper', 9 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'jacqueline_woocommerce_open_thumb_wrapper', 9 );

			add_action(    'woocommerce_before_subcategory_title',		'jacqueline_woocommerce_open_item_wrapper', 20 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'jacqueline_woocommerce_open_item_wrapper', 20 );

			add_action(    'woocommerce_after_subcategory',				'jacqueline_woocommerce_close_item_wrapper', 20 );
			add_action(    'woocommerce_after_shop_loop_item',			'jacqueline_woocommerce_close_item_wrapper', 20 );

			add_action(    'woocommerce_after_shop_loop_item_title',	'jacqueline_woocommerce_after_shop_loop_item_title', 7);

			add_action(    'woocommerce_after_subcategory_title',		'jacqueline_woocommerce_after_subcategory_title', 10 );
			
			// Remove link around product item
			remove_action('woocommerce_before_shop_loop_item',			'woocommerce_template_loop_product_link_open', 10);
			remove_action('woocommerce_after_shop_loop_item',			'woocommerce_template_loop_product_link_close', 5);
			// Remove link around product category
			remove_action('woocommerce_before_subcategory',				'woocommerce_template_loop_category_link_open', 10);
			remove_action('woocommerce_after_subcategory',				'woocommerce_template_loop_category_link_close', 10);
	
			remove_action( 'woocommerce_after_shop_loop_item', 			'woocommerce_template_loop_add_to_cart', 10);
			add_action(	   'woocommerce_before_shop_loop_item_title', 	'woocommerce_template_loop_add_to_cart', 10);
		}

		if (jacqueline_is_woocommerce_page()) {
			
			remove_action( 'woocommerce_sidebar', 						'woocommerce_get_sidebar', 10 );					// Remove WOOC sidebar
			
			remove_action( 'woocommerce_before_main_content',			'woocommerce_output_content_wrapper', 10);
			add_action(    'woocommerce_before_main_content',			'jacqueline_woocommerce_wrapper_start', 10);
			
			remove_action( 'woocommerce_after_main_content',			'woocommerce_output_content_wrapper_end', 10);		
			add_action(    'woocommerce_after_main_content',			'jacqueline_woocommerce_wrapper_end', 10);

			add_action(    'woocommerce_show_page_title',				'jacqueline_woocommerce_show_page_title', 10);

			remove_action( 'woocommerce_single_product_summary',		'woocommerce_template_single_title', 5);		
			add_action(    'woocommerce_single_product_summary',		'jacqueline_woocommerce_show_product_title', 5 );

			add_action(    'woocommerce_before_shop_loop', 				'jacqueline_woocommerce_before_shop_loop', 10 );

			remove_action( 'woocommerce_after_shop_loop',				'woocommerce_pagination', 10 );
			add_action(    'woocommerce_after_shop_loop',				'jacqueline_woocommerce_pagination', 10 );

			add_action(    'woocommerce_product_meta_end',				'jacqueline_woocommerce_show_product_id', 10);

			add_filter(    'woocommerce_output_related_products_args',	'jacqueline_woocommerce_output_related_products_args' );
			
			add_filter(    'woocommerce_product_thumbnails_columns',	'jacqueline_woocommerce_product_thumbnails_columns' );

			add_filter(    'loop_shop_columns',							'jacqueline_woocommerce_loop_shop_columns' );

			add_filter(    'get_product_search_form',					'jacqueline_woocommerce_get_product_search_form' );

			add_filter(    'post_class',								'jacqueline_woocommerce_loop_shop_columns_class' );
			add_action(    'the_title',									'jacqueline_woocommerce_the_title');
			
			jacqueline_enqueue_popup();
		}
	}
}



// Check if WooCommerce installed and activated
if ( !function_exists( 'jacqueline_exists_woocommerce' ) ) {
	function jacqueline_exists_woocommerce() {
		return class_exists('Woocommerce');
		//return function_exists('is_woocommerce');
	}
}

// Return true, if current page is any woocommerce page
if ( !function_exists( 'jacqueline_is_woocommerce_page' ) ) {
	function jacqueline_is_woocommerce_page() {
		$rez = false;
		if (jacqueline_exists_woocommerce()) {
			if (!jacqueline_storage_empty('pre_query')) {
				$id = jacqueline_storage_get_obj_property('pre_query', 'queried_object_id', 0);
				$rez = jacqueline_storage_call_obj_method('pre_query', 'get', 'post_type')=='product' 
						|| $id==wc_get_page_id('shop')
						|| $id==wc_get_page_id('cart')
						|| $id==wc_get_page_id('checkout')
						|| $id==wc_get_page_id('myaccount')
						|| jacqueline_storage_call_obj_method('pre_query', 'is_tax', 'product_cat')
						|| jacqueline_storage_call_obj_method('pre_query', 'is_tax', 'product_tag')
						|| jacqueline_storage_call_obj_method('pre_query', 'is_tax', get_object_taxonomies('product'));
						
			} else
				$rez = is_shop() || is_product() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_cart() || is_checkout() || is_account_page();
		}
		return $rez;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'jacqueline_woocommerce_detect_inheritance_key' ) ) {
	//add_filter('jacqueline_filter_detect_inheritance_key',	'jacqueline_woocommerce_detect_inheritance_key', 9, 1);
	function jacqueline_woocommerce_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return jacqueline_is_woocommerce_page() ? 'woocommerce' : '';
	}
}

// Filter to detect current template page id
if ( !function_exists( 'jacqueline_woocommerce_detect_template_page_id' ) ) {
	//add_filter('jacqueline_filter_detect_template_page_id',	'jacqueline_woocommerce_detect_template_page_id', 9, 2);
	function jacqueline_woocommerce_detect_template_page_id($id, $key) {
		if (!empty($id)) return $id;
		if ($key == 'woocommerce_cart')				$id = get_option('woocommerce_cart_page_id');
		else if ($key == 'woocommerce_checkout')	$id = get_option('woocommerce_checkout_page_id');
		else if ($key == 'woocommerce_account')		$id = get_option('woocommerce_account_page_id');
		else if ($key == 'woocommerce')				$id = get_option('woocommerce_shop_page_id');
		return $id;
	}
}

// Filter to detect current page type (slug)
if ( !function_exists( 'jacqueline_woocommerce_get_blog_type' ) ) {
	//add_filter('jacqueline_filter_get_blog_type',	'jacqueline_woocommerce_get_blog_type', 9, 2);
	function jacqueline_woocommerce_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		
		if (is_shop()) 					$page = 'woocommerce_shop';
		else if ($query && $query->get('post_type')=='product' || is_product())		$page = 'woocommerce_product';
		else if ($query && $query->get('product_tag')!='' || is_product_tag())		$page = 'woocommerce_tag';
		else if ($query && $query->get('product_cat')!='' || is_product_category())	$page = 'woocommerce_category';
		else if (is_cart())				$page = 'woocommerce_cart';
		else if (is_checkout())			$page = 'woocommerce_checkout';
		else if (is_account_page())		$page = 'woocommerce_account';
		else if (is_woocommerce())		$page = 'woocommerce';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'jacqueline_woocommerce_get_blog_title' ) ) {
	//add_filter('jacqueline_filter_get_blog_title',	'jacqueline_woocommerce_get_blog_title', 9, 2);
	function jacqueline_woocommerce_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		
		if ( jacqueline_strpos($page, 'woocommerce')!==false ) {
			if ( $page == 'woocommerce_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'product_cat' ), 'product_cat', OBJECT);
				$title = $term->name;
			} else if ( $page == 'woocommerce_tag' ) {
				$term = get_term_by( 'slug', get_query_var( 'product_tag' ), 'product_tag', OBJECT);
				$title = esc_html__('Tag:', 'jacqueline') . ' ' . esc_html($term->name);
			} else if ( $page == 'woocommerce_cart' ) {
				$title = esc_html__( 'Your cart', 'jacqueline' );
			} else if ( $page == 'woocommerce_checkout' ) {
				$title = esc_html__( 'Checkout', 'jacqueline' );
			} else if ( $page == 'woocommerce_account' ) {
				$title = esc_html__( 'Account', 'jacqueline' );
			} else if ( $page == 'woocommerce_product' ) {
				$title = jacqueline_get_post_title();
			} else if (($page_id=get_option('woocommerce_shop_page_id')) > 0) {
				$title = jacqueline_get_post_title($page_id);
			} else {
				$title = esc_html__( 'Shop', 'jacqueline' );
			}
		}
		
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'jacqueline_woocommerce_get_stream_page_title' ) ) {
	//add_filter('jacqueline_filter_get_stream_page_title',	'jacqueline_woocommerce_get_stream_page_title', 9, 2);
	function jacqueline_woocommerce_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (jacqueline_strpos($page, 'woocommerce')!==false) {
			if (($page_id = jacqueline_woocommerce_get_stream_page_id(0, $page)) > 0)
				$title = jacqueline_get_post_title($page_id);
			else
				$title = esc_html__('Shop', 'jacqueline');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'jacqueline_woocommerce_get_stream_page_id' ) ) {
	//add_filter('jacqueline_filter_get_stream_page_id',	'jacqueline_woocommerce_get_stream_page_id', 9, 2);
	function jacqueline_woocommerce_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (jacqueline_strpos($page, 'woocommerce')!==false) {
			$id = get_option('woocommerce_shop_page_id');
		}
		return $id;
	}
}

// Filter to detect stream page link
if ( !function_exists( 'jacqueline_woocommerce_get_stream_page_link' ) ) {
	//add_filter('jacqueline_filter_get_stream_page_link',	'jacqueline_woocommerce_get_stream_page_link', 9, 2);
	function jacqueline_woocommerce_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (jacqueline_strpos($page, 'woocommerce')!==false) {
			$id = jacqueline_woocommerce_get_stream_page_id(0, $page);
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'jacqueline_woocommerce_get_current_taxonomy' ) ) {
	//add_filter('jacqueline_filter_get_current_taxonomy',	'jacqueline_woocommerce_get_current_taxonomy', 9, 2);
	function jacqueline_woocommerce_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( jacqueline_strpos($page, 'woocommerce')!==false ) {
			$tax = 'product_cat';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'jacqueline_woocommerce_is_taxonomy' ) ) {
	//add_filter('jacqueline_filter_is_taxonomy',	'jacqueline_woocommerce_is_taxonomy', 9, 2);
	function jacqueline_woocommerce_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query!==null && $query->get('product_cat')!='' || is_product_category() ? 'product_cat' : '';
	}
}

// Return false if current plugin not need theme orderby setting
if ( !function_exists( 'jacqueline_woocommerce_orderby_need' ) ) {
	//add_filter('jacqueline_filter_orderby_need',	'jacqueline_woocommerce_orderby_need', 9, 1);
	function jacqueline_woocommerce_orderby_need($need) {
		if ($need == false || jacqueline_storage_empty('pre_query'))
			return $need;
		else {
			return jacqueline_storage_call_obj_method('pre_query', 'get', 'post_type')!='product' 
					&& jacqueline_storage_call_obj_method('pre_query', 'get', 'product_cat')==''
					&& jacqueline_storage_call_obj_method('pre_query', 'get', 'product_tag')=='';
		}
	}
}

// Add custom post type into list
if ( !function_exists( 'jacqueline_woocommerce_list_post_types' ) ) {
	//add_filter('jacqueline_filter_list_post_types', 	'jacqueline_woocommerce_list_post_types', 10, 1);
	function jacqueline_woocommerce_list_post_types($list) {
		$list['product'] = esc_html__('Products', 'jacqueline');
		return $list;
	}
}


	
// Enqueue WooCommerce custom styles
if ( !function_exists( 'jacqueline_woocommerce_frontend_scripts' ) ) {
	//add_action( 'jacqueline_action_add_styles', 'jacqueline_woocommerce_frontend_scripts' );
	function jacqueline_woocommerce_frontend_scripts() {
		if (jacqueline_is_woocommerce_page() || jacqueline_get_custom_option('show_cart')=='always')
			if (file_exists(jacqueline_get_file_dir('css/plugin.woocommerce.css')))
				jacqueline_enqueue_style( 'jacqueline-plugin.woocommerce-style',  jacqueline_get_file_url('css/plugin.woocommerce.css'), array(), null );
	}
}

// Before main content
if ( !function_exists( 'jacqueline_woocommerce_wrapper_start' ) ) {
	//remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	//add_action('woocommerce_before_main_content', 'jacqueline_woocommerce_wrapper_start', 10);
	function jacqueline_woocommerce_wrapper_start() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			<article class="post_item post_item_single post_item_product">
			<?php
		} else {
			?>
			<div class="list_products shop_mode_<?php echo !jacqueline_storage_empty('shop_mode') ? jacqueline_storage_get('shop_mode') : 'thumbs'; ?>">
			<?php
		}
	}
}

// After main content
if ( !function_exists( 'jacqueline_woocommerce_wrapper_end' ) ) {
	//remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);		
	//add_action('woocommerce_after_main_content', 'jacqueline_woocommerce_wrapper_end', 10);
	function jacqueline_woocommerce_wrapper_end() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			</article>	<!-- .post_item -->
			<?php
		} else {
			?>
			</div>	<!-- .list_products -->
			<?php
		}
	}
}

// Check to show page title
if ( !function_exists( 'jacqueline_woocommerce_show_page_title' ) ) {
	//add_action('woocommerce_show_page_title', 'jacqueline_woocommerce_show_page_title', 10);
	function jacqueline_woocommerce_show_page_title($defa=true) {
		return jacqueline_get_custom_option('show_page_title')=='no';
	}
}

// Check to show product title
if ( !function_exists( 'jacqueline_woocommerce_show_product_title' ) ) {
	//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);		
	//add_action( 'woocommerce_single_product_summary', 'jacqueline_woocommerce_show_product_title', 5 );
	function jacqueline_woocommerce_show_product_title() {
		if (jacqueline_get_custom_option('show_post_title')=='yes' || jacqueline_get_custom_option('show_page_title')=='no') {
			wc_get_template( 'single-product/title.php' );
		}
	}
}

// Add list mode buttons
if ( !function_exists( 'jacqueline_woocommerce_before_shop_loop' ) ) {
	//add_action( 'woocommerce_before_shop_loop', 'jacqueline_woocommerce_before_shop_loop', 10 );
	function jacqueline_woocommerce_before_shop_loop() {
		if (jacqueline_get_custom_option('show_mode_buttons')=='yes') {
			echo '<div class="mode_buttons"><form action="' . esc_url(jacqueline_get_protocol().'://' . ($_SERVER["HTTP_HOST"]) . ($_SERVER["REQUEST_URI"])).'" method="post">'
				. '<input type="hidden" name="jacqueline_shop_mode" value="'.esc_attr(jacqueline_storage_get('shop_mode')).'" />'
				. '<a href="#" class="woocommerce_thumbs icon-th" title="'.esc_attr__('Show products as thumbs', 'jacqueline').'"></a>'
				. '<a href="#" class="woocommerce_list icon-th-list" title="'.esc_attr__('Show products as list', 'jacqueline').'"></a>'
				. '</form></div>';
		}
	}
}


// Open thumbs wrapper for categories and products
if ( !function_exists( 'jacqueline_woocommerce_open_thumb_wrapper' ) ) {
	//add_action( 'woocommerce_before_subcategory_title', 'jacqueline_woocommerce_open_thumb_wrapper', 9 );
	//add_action( 'woocommerce_before_shop_loop_item_title', 'jacqueline_woocommerce_open_thumb_wrapper', 9 );
	function jacqueline_woocommerce_open_thumb_wrapper($cat='') {
		jacqueline_storage_set('in_product_item', true);
		?>
		<div class="post_item_wrap">
			<div class="post_featured">
				<div class="post_thumb">
					<div class="button_container">
						<a class="button view_link" href="<?php echo esc_url(is_object($cat) ? get_term_link($cat->slug, 'product_cat') : get_permalink()); ?>"><?php echo esc_html__('View', 'jacqueline'); ?></a>
					</div>
					<div class="button_container cart"></div>
		<?php
	}
}

// Open item wrapper for categories and products
if ( !function_exists( 'jacqueline_woocommerce_open_item_wrapper' ) ) {
	//add_action( 'woocommerce_before_subcategory_title', 'jacqueline_woocommerce_open_item_wrapper', 20 );
	//add_action( 'woocommerce_before_shop_loop_item_title', 'jacqueline_woocommerce_open_item_wrapper', 20 );
	function jacqueline_woocommerce_open_item_wrapper($cat='') {
		?>
				
			</div>
		</div>
		<div class="post_content">
		<?php
	}
}

// Close item wrapper for categories and products
if ( !function_exists( 'jacqueline_woocommerce_close_item_wrapper' ) ) {
	//add_action( 'woocommerce_after_subcategory', 'jacqueline_woocommerce_close_item_wrapper', 20 );
	//add_action( 'woocommerce_after_shop_loop_item', 'jacqueline_woocommerce_close_item_wrapper', 20 );
	function jacqueline_woocommerce_close_item_wrapper($cat='') {
		?>
			</div>
		</div>
		<?php
		jacqueline_storage_set('in_product_item', false);
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'jacqueline_woocommerce_after_shop_loop_item_title' ) ) {
	//add_action( 'woocommerce_after_shop_loop_item_title', 'jacqueline_woocommerce_after_shop_loop_item_title', 7);
	function jacqueline_woocommerce_after_shop_loop_item_title() {
		if (jacqueline_storage_get('shop_mode') == 'list') {
		    $excerpt = apply_filters('the_excerpt', get_the_excerpt());
			echo '<div class="description">'.trim($excerpt).'</div>';
		}
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'jacqueline_woocommerce_after_subcategory_title' ) ) {
	//add_action( 'woocommerce_after_subcategory_title', 'jacqueline_woocommerce_after_subcategory_title', 10 );
	function jacqueline_woocommerce_after_subcategory_title($category) {
		if (jacqueline_storage_get('shop_mode') == 'list')
			echo '<div class="description">' . trim($category->description) . '</div>';
	}
}

// Add Product ID for single product
if ( !function_exists( 'jacqueline_woocommerce_show_product_id' ) ) {
	//add_action( 'woocommerce_product_meta_end', 'jacqueline_woocommerce_show_product_id', 10);
	function jacqueline_woocommerce_show_product_id() {
		global $post, $product;
		echo '<span class="product_id">'.esc_html__('Product ID: ', 'jacqueline') . '<span>' . ($post->ID) . '</span></span>';
	}
}

// Redefine number of related products
if ( !function_exists( 'jacqueline_woocommerce_output_related_products_args' ) ) {
	//add_filter( 'woocommerce_output_related_products_args', 'jacqueline_woocommerce_output_related_products_args' );
	function jacqueline_woocommerce_output_related_products_args($args) {
		$ppp = $ccc = 0;
		if (jacqueline_param_is_on(jacqueline_get_custom_option('show_post_related'))) {
			$ccc_add = in_array(jacqueline_get_custom_option('body_style'), array('fullwide', 'fullscreen')) ? 1 : 0;
			$ccc =  jacqueline_get_custom_option('post_related_columns');
			$ccc = $ccc > 0 ? $ccc : (jacqueline_param_is_off(jacqueline_get_custom_option('show_sidebar_main')) ? 3+$ccc_add : 2+$ccc_add);
			$ppp = jacqueline_get_custom_option('post_related_count');
			$ppp = $ppp > 0 ? $ppp : $ccc;
		}
		$args['posts_per_page'] = $ppp;
		$args['columns'] = $ccc;
		return $args;
	}
}

// Number columns for product thumbnails
if ( !function_exists( 'jacqueline_woocommerce_product_thumbnails_columns' ) ) {
	//add_filter( 'woocommerce_product_thumbnails_columns', 'jacqueline_woocommerce_product_thumbnails_columns' );
	function jacqueline_woocommerce_product_thumbnails_columns($cols) {
		return 4;
	}
}

// Add column class into product item in shop streampage
if ( !function_exists( 'jacqueline_woocommerce_loop_shop_columns_class' ) ) {
	//add_filter( 'post_class', 'jacqueline_woocommerce_loop_shop_columns_class' );
	function jacqueline_woocommerce_loop_shop_columns_class($class) {
		global $woocommerce_loop;
		if (is_product()) {
			if (!empty($woocommerce_loop['columns']))
			$class[] = ' column-1_'.esc_attr($woocommerce_loop['columns']);
		} else if (!is_product() && !is_cart() && !is_checkout() && !is_account_page()) {
			$ccc_add = in_array(jacqueline_get_custom_option('body_style'), array('fullwide', 'fullscreen')) ? 1 : 0;
			$ccc =  jacqueline_get_custom_option('shop_loop_columns');
			$ccc = $ccc > 0 ? $ccc : (jacqueline_param_is_off(jacqueline_get_custom_option('show_sidebar_main')) ? 3+$ccc_add : 2+$ccc_add);
			$class[] = ' column-1_'.esc_attr($ccc);
		}
		return $class;
	}
}

// Number columns for shop streampage
if ( !function_exists( 'jacqueline_woocommerce_loop_shop_columns' ) ) {
	//add_filter( 'loop_shop_columns', 'jacqueline_woocommerce_loop_shop_columns' );
	function jacqueline_woocommerce_loop_shop_columns($cols) {
		$ccc_add = in_array(jacqueline_get_custom_option('body_style'), array('fullwide', 'fullscreen')) ? 1 : 0;
		$ccc =  jacqueline_get_custom_option('shop_loop_columns');
		$ccc = $ccc > 0 ? $ccc : (jacqueline_param_is_off(jacqueline_get_custom_option('show_sidebar_main')) ? 3+$ccc_add : 2+$ccc_add);
		return $ccc;
	}
}

// Search form
if ( !function_exists( 'jacqueline_woocommerce_get_product_search_form' ) ) {
	//add_filter( 'get_product_search_form', 'jacqueline_woocommerce_get_product_search_form' );
	function jacqueline_woocommerce_get_product_search_form($form) {
		return '
		<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
			<input type="text" class="search_field" placeholder="' . esc_attr__('Search for products &hellip;', 'jacqueline') . '" value="' . get_search_query() . '" name="s" title="' . esc_attr__('Search for products:', 'jacqueline') . '" /><button class="search_button icon-search" type="submit"></button>
			<input type="hidden" name="post_type" value="product" />
		</form>
		';
	}
}

// Wrap product title into link
if ( !function_exists( 'jacqueline_woocommerce_the_title' ) ) {
	//add_filter( 'the_title', 'jacqueline_woocommerce_the_title' );
	function jacqueline_woocommerce_the_title($title) {
		if (jacqueline_storage_get('in_product_item') && get_post_type()=='product') {
			$title = '<a href="'.get_permalink().'">'.($title).'</a>';
		}
		return $title;
	}
}

// Show pagination links
if ( !function_exists( 'jacqueline_woocommerce_pagination' ) ) {
	//add_filter( 'woocommerce_after_shop_loop', 'jacqueline_woocommerce_pagination', 10 );
	function jacqueline_woocommerce_pagination() {
		$style = jacqueline_get_custom_option('blog_pagination');
		jacqueline_show_pagination(array(
			'class' => 'pagination_wrap pagination_' . esc_attr($style),
			'style' => $style,
			'button_class' => '',
			'first_text'=> '',
			'last_text' => '',
			'prev_text' => '',
			'next_text' => '',
			'pages_in_group' => $style=='pages' ? 10 : 20
			)
		);
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'jacqueline_woocommerce_required_plugins' ) ) {
	//add_filter('jacqueline_filter_required_plugins',	'jacqueline_woocommerce_required_plugins');
	function jacqueline_woocommerce_required_plugins($list=array()) {
		if (in_array('woocommerce', jacqueline_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> 'WooCommerce',
					'slug' 		=> 'woocommerce',
					'required' 	=> false
				);

		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check WooC in the required plugins
if ( !function_exists( 'jacqueline_woocommerce_importer_required_plugins' ) ) {
	//add_filter( 'jacqueline_filter_importer_required_plugins',	'jacqueline_woocommerce_importer_required_plugins', 10, 2 );
	function jacqueline_woocommerce_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('woocommerce', jacqueline_storage_get('required_plugins')) && !jacqueline_exists_woocommerce() )
		if (jacqueline_strpos($list, 'woocommerce')!==false && !jacqueline_exists_woocommerce() )
			$not_installed .= '<br>WooCommerce';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'jacqueline_woocommerce_importer_set_options' ) ) {
	//add_filter( 'jacqueline_filter_importer_options',	'jacqueline_woocommerce_importer_set_options' );
	function jacqueline_woocommerce_importer_set_options($options=array()) {
		if ( in_array('woocommerce', jacqueline_storage_get('required_plugins')) && jacqueline_exists_woocommerce() ) {
			if (is_array($options['files']) && count($options['files']) > 0) {
				foreach ($options['files'] as $k => $v) {
					$options['files'][$k]['file_with_woocommerce'] = str_replace('posts', 'woocommerce', $v['file_with_posts']);
				}
			}
			// Add slugs to export options for this plugin
			$options['additional_options'][]	= 'shop_%';
			$options['additional_options'][]	= 'woocommerce_%';
		}
		return $options;
	}
}

// Setup WooC pages after import posts complete
if ( !function_exists( 'jacqueline_woocommerce_importer_after_import_posts' ) ) {
	//add_action( 'jacqueline_action_importer_after_import_posts',	'jacqueline_woocommerce_importer_after_import_posts', 10, 1 );
	function jacqueline_woocommerce_importer_after_import_posts($importer) {
		$wooc_pages = array(						// Options slugs and pages titles for WooCommerce pages
			'woocommerce_shop_page_id' 				=> 'Shop',
			'woocommerce_cart_page_id' 				=> 'Cart',
			'woocommerce_checkout_page_id' 			=> 'Checkout',
			'woocommerce_pay_page_id' 				=> 'Checkout &#8594; Pay',
			'woocommerce_thanks_page_id' 			=> 'Order Received',
			'woocommerce_myaccount_page_id' 		=> 'My Account',
			'woocommerce_edit_address_page_id'		=> 'Edit My Address',
			'woocommerce_view_order_page_id'		=> 'View Order',
			'woocommerce_change_password_page_id'	=> 'Change Password',
			'woocommerce_logout_page_id'			=> 'Logout',
			'woocommerce_lost_password_page_id'		=> 'Lost Password'
		);
		foreach ($wooc_pages as $woo_page_name => $woo_page_title) {
			$woopage = get_page_by_title( $woo_page_title );
			if ($woopage->ID) {
				update_option($woo_page_name, $woopage->ID);
			}
		}
		// We no longer need to install pages
		delete_option( '_wc_needs_pages' );
		delete_transient( '_wc_activation_redirect' );
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'jacqueline_woocommerce_importer_show_params' ) ) {
	//add_action( 'jacqueline_action_importer_params',	'jacqueline_woocommerce_importer_show_params', 10, 1 );
	function jacqueline_woocommerce_importer_show_params($importer) {
		?>
		<input type="checkbox" <?php echo in_array('woocommerce', jacqueline_storage_get('required_plugins')) && $importer->options['plugins_initial_state']
											? 'checked="checked"' 
											: ''; ?> value="1" name="import_woocommerce" id="import_woocommerce" /> <label for="import_woocommerce"><?php esc_html_e('Import WooCommerce', 'jacqueline'); ?></label><br>
		<?php
	}
}

// Import posts
if ( !function_exists( 'jacqueline_woocommerce_importer_import' ) ) {
	//add_action( 'jacqueline_action_importer_import',	'jacqueline_woocommerce_importer_import', 10, 2 );
	function jacqueline_woocommerce_importer_import($importer, $action) {
		if ( $action == 'import_woocommerce' ) {
			$importer->response['result'] = $importer->import_dump('woocommerce', esc_html__('WooCommerce meta', 'jacqueline'));
		}
	}
}

// Display import progress
if ( !function_exists( 'jacqueline_woocommerce_importer_import_fields' ) ) {
	//add_action( 'jacqueline_action_importer_import_fields',	'jacqueline_woocommerce_importer_import_fields', 10, 1 );
	function jacqueline_woocommerce_importer_import_fields($importer) {
		?>
		<tr class="import_woocommerce">
			<td class="import_progress_item"><?php esc_html_e('WooCommerce meta', 'jacqueline'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}

// Export posts
if ( !function_exists( 'jacqueline_woocommerce_importer_export' ) ) {
	//add_action( 'jacqueline_action_importer_export',	'jacqueline_woocommerce_importer_export', 10, 1 );
	function jacqueline_woocommerce_importer_export($importer) {
		jacqueline_storage_set('export_woocommerce', serialize( array(
			"woocommerce_attribute_taxomonies"				=> $importer->export_dump("woocommerce_attribute_taxomonies"),
			"woocommerce_downloadable_product_permissions"	=> $importer->export_dump("woocommerce_downloadable_product_permissions"),
            "woocommerce_order_itemmeta"					=> $importer->export_dump("woocommerce_order_itemmeta"),
            "woocommerce_order_items"						=> $importer->export_dump("woocommerce_order_items"),
            "woocommerce_termmeta"							=> $importer->export_dump("woocommerce_termmeta")
            ) )
        );
	}
}

// Display exported data in the fields
if ( !function_exists( 'jacqueline_woocommerce_importer_export_fields' ) ) {
	//add_action( 'jacqueline_action_importer_export_fields',	'jacqueline_woocommerce_importer_export_fields', 10, 1 );
	function jacqueline_woocommerce_importer_export_fields($importer) {
		?>
		<tr>
			<th align="left"><?php esc_html_e('WooCommerce', 'jacqueline'); ?></th>
			<td><?php jacqueline_fpc(jacqueline_get_file_dir('core/core.importer/export/woocommerce.txt'), jacqueline_storage_get('export_woocommerce')); ?>
				<a download="woocommerce.txt" href="<?php echo esc_url(jacqueline_get_file_url('core/core.importer/export/woocommerce.txt')); ?>"><?php esc_html_e('Download', 'jacqueline'); ?></a>
			</td>
		</tr>
		<?php
	}
}



// Register shortcodes to the internal builder
//------------------------------------------------------------------------
if ( !function_exists( 'jacqueline_woocommerce_reg_shortcodes' ) ) {
	//add_action('jacqueline_action_shortcodes_list', 'jacqueline_woocommerce_reg_shortcodes', 20);
	function jacqueline_woocommerce_reg_shortcodes() {

		// WooCommerce - Cart
		jacqueline_sc_map("woocommerce_cart", array(
			"title" => esc_html__("Woocommerce: Cart", 'jacqueline'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show Cart page", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Checkout
		jacqueline_sc_map("woocommerce_checkout", array(
			"title" => esc_html__("Woocommerce: Checkout", 'jacqueline'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show Checkout page", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - My Account
		jacqueline_sc_map("woocommerce_my_account", array(
			"title" => esc_html__("Woocommerce: My Account", 'jacqueline'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show My Account page", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Order Tracking
		jacqueline_sc_map("woocommerce_order_tracking", array(
			"title" => esc_html__("Woocommerce: Order Tracking", 'jacqueline'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show Order Tracking page", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Shop Messages
		jacqueline_sc_map("shop_messages", array(
			"title" => esc_html__("Woocommerce: Shop Messages", 'jacqueline'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show shop messages", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Product Page
		jacqueline_sc_map("product_page", array(
			"title" => esc_html__("Woocommerce: Product Page", 'jacqueline'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: display single product page", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"sku" => array(
					"title" => esc_html__("SKU", 'jacqueline'),
					"desc" => wp_kses_data( __("SKU code of displayed product", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"id" => array(
					"title" => esc_html__("ID", 'jacqueline'),
					"desc" => wp_kses_data( __("ID of displayed product", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"posts_per_page" => array(
					"title" => esc_html__("Number", 'jacqueline'),
					"desc" => wp_kses_data( __("How many products showed", 'jacqueline') ),
					"value" => "1",
					"min" => 1,
					"type" => "spinner"
				),
				"post_type" => array(
					"title" => esc_html__("Post type", 'jacqueline'),
					"desc" => wp_kses_data( __("Post type for the WP query (leave 'product')", 'jacqueline') ),
					"value" => "product",
					"type" => "text"
				),
				"post_status" => array(
					"title" => esc_html__("Post status", 'jacqueline'),
					"desc" => wp_kses_data( __("Display posts only with this status", 'jacqueline') ),
					"value" => "publish",
					"type" => "select",
					"options" => array(
						"publish" => esc_html__('Publish', 'jacqueline'),
						"protected" => esc_html__('Protected', 'jacqueline'),
						"private" => esc_html__('Private', 'jacqueline'),
						"pending" => esc_html__('Pending', 'jacqueline'),
						"draft" => esc_html__('Draft', 'jacqueline')
						)
					)
				)
			)
		);
		
		// WooCommerce - Product
		jacqueline_sc_map("product", array(
			"title" => esc_html__("Woocommerce: Product", 'jacqueline'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: display one product", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"sku" => array(
					"title" => esc_html__("SKU", 'jacqueline'),
					"desc" => wp_kses_data( __("SKU code of displayed product", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"id" => array(
					"title" => esc_html__("ID", 'jacqueline'),
					"desc" => wp_kses_data( __("ID of displayed product", 'jacqueline') ),
					"value" => "",
					"type" => "text"
					)
				)
			)
		);
		
		// WooCommerce - Best Selling Products
		jacqueline_sc_map("best_selling_products", array(
			"title" => esc_html__("Woocommerce: Best Selling Products", 'jacqueline'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show best selling products", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'jacqueline'),
					"desc" => wp_kses_data( __("How many products showed", 'jacqueline') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'jacqueline'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'jacqueline') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
					)
				)
			)
		);
		
		// WooCommerce - Recent Products
		jacqueline_sc_map("recent_products", array(
			"title" => esc_html__("Woocommerce: Recent Products", 'jacqueline'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show recent products", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'jacqueline'),
					"desc" => wp_kses_data( __("How many products showed", 'jacqueline') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'jacqueline'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'jacqueline') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'jacqueline'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'jacqueline'),
						"title" => esc_html__('Title', 'jacqueline')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'jacqueline'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => jacqueline_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Related Products
		jacqueline_sc_map("related_products", array(
			"title" => esc_html__("Woocommerce: Related Products", 'jacqueline'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show related products", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"posts_per_page" => array(
					"title" => esc_html__("Number", 'jacqueline'),
					"desc" => wp_kses_data( __("How many products showed", 'jacqueline') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'jacqueline'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'jacqueline') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'jacqueline'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'jacqueline'),
						"title" => esc_html__('Title', 'jacqueline')
						)
					)
				)
			)
		);
		
		// WooCommerce - Featured Products
		jacqueline_sc_map("featured_products", array(
			"title" => esc_html__("Woocommerce: Featured Products", 'jacqueline'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show featured products", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'jacqueline'),
					"desc" => wp_kses_data( __("How many products showed", 'jacqueline') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'jacqueline'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'jacqueline') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'jacqueline'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'jacqueline'),
						"title" => esc_html__('Title', 'jacqueline')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'jacqueline'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => jacqueline_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Top Rated Products
		jacqueline_sc_map("featured_products", array(
			"title" => esc_html__("Woocommerce: Top Rated Products", 'jacqueline'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show top rated products", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'jacqueline'),
					"desc" => wp_kses_data( __("How many products showed", 'jacqueline') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'jacqueline'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'jacqueline') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'jacqueline'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'jacqueline'),
						"title" => esc_html__('Title', 'jacqueline')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'jacqueline'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => jacqueline_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Sale Products
		jacqueline_sc_map("featured_products", array(
			"title" => esc_html__("Woocommerce: Sale Products", 'jacqueline'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: list products on sale", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'jacqueline'),
					"desc" => wp_kses_data( __("How many products showed", 'jacqueline') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'jacqueline'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'jacqueline') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'jacqueline'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'jacqueline'),
						"title" => esc_html__('Title', 'jacqueline')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'jacqueline'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => jacqueline_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Product Category
		jacqueline_sc_map("product_category", array(
			"title" => esc_html__("Woocommerce: Products from category", 'jacqueline'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: list products in specified category(-ies)", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'jacqueline'),
					"desc" => wp_kses_data( __("How many products showed", 'jacqueline') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'jacqueline'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'jacqueline') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'jacqueline'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'jacqueline'),
						"title" => esc_html__('Title', 'jacqueline')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'jacqueline'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => jacqueline_get_sc_param('ordering')
				),
				"category" => array(
					"title" => esc_html__("Categories", 'jacqueline'),
					"desc" => wp_kses_data( __("Comma separated category slugs", 'jacqueline') ),
					"value" => '',
					"type" => "text"
				),
				"operator" => array(
					"title" => esc_html__("Operator", 'jacqueline'),
					"desc" => wp_kses_data( __("Categories operator", 'jacqueline') ),
					"value" => "IN",
					"type" => "checklist",
					"size" => "medium",
					"options" => array(
						"IN" => esc_html__('IN', 'jacqueline'),
						"NOT IN" => esc_html__('NOT IN', 'jacqueline'),
						"AND" => esc_html__('AND', 'jacqueline')
						)
					)
				)
			)
		);
		
		// WooCommerce - Products
		jacqueline_sc_map("products", array(
			"title" => esc_html__("Woocommerce: Products", 'jacqueline'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: list all products", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"skus" => array(
					"title" => esc_html__("SKUs", 'jacqueline'),
					"desc" => wp_kses_data( __("Comma separated SKU codes of products", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"ids" => array(
					"title" => esc_html__("IDs", 'jacqueline'),
					"desc" => wp_kses_data( __("Comma separated ID of products", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'jacqueline'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'jacqueline') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'jacqueline'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'jacqueline'),
						"title" => esc_html__('Title', 'jacqueline')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'jacqueline'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => jacqueline_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Product attribute
		jacqueline_sc_map("product_attribute", array(
			"title" => esc_html__("Woocommerce: Products by Attribute", 'jacqueline'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show products with specified attribute", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'jacqueline'),
					"desc" => wp_kses_data( __("How many products showed", 'jacqueline') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'jacqueline'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'jacqueline') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'jacqueline'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'jacqueline'),
						"title" => esc_html__('Title', 'jacqueline')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'jacqueline'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => jacqueline_get_sc_param('ordering')
				),
				"attribute" => array(
					"title" => esc_html__("Attribute", 'jacqueline'),
					"desc" => wp_kses_data( __("Attribute name", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"filter" => array(
					"title" => esc_html__("Filter", 'jacqueline'),
					"desc" => wp_kses_data( __("Attribute value", 'jacqueline') ),
					"value" => "",
					"type" => "text"
					)
				)
			)
		);
		
		// WooCommerce - Products Categories
		jacqueline_sc_map("product_categories", array(
			"title" => esc_html__("Woocommerce: Product Categories", 'jacqueline'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show categories with products", 'jacqueline') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"number" => array(
					"title" => esc_html__("Number", 'jacqueline'),
					"desc" => wp_kses_data( __("How many categories showed", 'jacqueline') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'jacqueline'),
					"desc" => wp_kses_data( __("How many columns per row use for categories output", 'jacqueline') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'jacqueline'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'jacqueline'),
						"title" => esc_html__('Title', 'jacqueline')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'jacqueline'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => jacqueline_get_sc_param('ordering')
				),
				"parent" => array(
					"title" => esc_html__("Parent", 'jacqueline'),
					"desc" => wp_kses_data( __("Parent category slug", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"ids" => array(
					"title" => esc_html__("IDs", 'jacqueline'),
					"desc" => wp_kses_data( __("Comma separated ID of products", 'jacqueline') ),
					"value" => "",
					"type" => "text"
				),
				"hide_empty" => array(
					"title" => esc_html__("Hide empty", 'jacqueline'),
					"desc" => wp_kses_data( __("Hide empty categories", 'jacqueline') ),
					"value" => "yes",
					"type" => "switch",
					"options" => jacqueline_get_sc_param('yes_no')
					)
				)
			)
		);
	}
}



// Register shortcodes to the VC builder
//------------------------------------------------------------------------
if ( !function_exists( 'jacqueline_woocommerce_reg_shortcodes_vc' ) ) {
	//add_action('jacqueline_action_shortcodes_list_vc', 'jacqueline_woocommerce_reg_shortcodes_vc');
	function jacqueline_woocommerce_reg_shortcodes_vc() {
	
		if (false && function_exists('jacqueline_exists_woocommerce') && jacqueline_exists_woocommerce()) {
		
			// WooCommerce - Cart
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_cart",
				"name" => esc_html__("Cart", 'jacqueline'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show cart page", 'jacqueline') ),
				"category" => esc_html__('WooCommerce', 'jacqueline'),
				'icon' => 'icon_trx_wooc_cart',
				"class" => "trx_sc_alone trx_sc_woocommerce_cart",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'jacqueline'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'jacqueline') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_Cart extends JACQUELINE_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Checkout
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_checkout",
				"name" => esc_html__("Checkout", 'jacqueline'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show checkout page", 'jacqueline') ),
				"category" => esc_html__('WooCommerce', 'jacqueline'),
				'icon' => 'icon_trx_wooc_checkout',
				"class" => "trx_sc_alone trx_sc_woocommerce_checkout",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'jacqueline'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'jacqueline') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_Checkout extends JACQUELINE_VC_ShortCodeAlone {}
		
		
			// WooCommerce - My Account
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_my_account",
				"name" => esc_html__("My Account", 'jacqueline'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show my account page", 'jacqueline') ),
				"category" => esc_html__('WooCommerce', 'jacqueline'),
				'icon' => 'icon_trx_wooc_my_account',
				"class" => "trx_sc_alone trx_sc_woocommerce_my_account",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'jacqueline'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'jacqueline') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_My_Account extends JACQUELINE_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Order Tracking
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_order_tracking",
				"name" => esc_html__("Order Tracking", 'jacqueline'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show order tracking page", 'jacqueline') ),
				"category" => esc_html__('WooCommerce', 'jacqueline'),
				'icon' => 'icon_trx_wooc_order_tracking',
				"class" => "trx_sc_alone trx_sc_woocommerce_order_tracking",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'jacqueline'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'jacqueline') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_Order_Tracking extends JACQUELINE_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Shop Messages
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "shop_messages",
				"name" => esc_html__("Shop Messages", 'jacqueline'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show shop messages", 'jacqueline') ),
				"category" => esc_html__('WooCommerce', 'jacqueline'),
				'icon' => 'icon_trx_wooc_shop_messages',
				"class" => "trx_sc_alone trx_sc_shop_messages",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'jacqueline'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'jacqueline') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Shop_Messages extends JACQUELINE_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Product Page
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_page",
				"name" => esc_html__("Product Page", 'jacqueline'),
				"description" => wp_kses_data( __("WooCommerce shortcode: display single product page", 'jacqueline') ),
				"category" => esc_html__('WooCommerce', 'jacqueline'),
				'icon' => 'icon_trx_product_page',
				"class" => "trx_sc_single trx_sc_product_page",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "sku",
						"heading" => esc_html__("SKU", 'jacqueline'),
						"description" => wp_kses_data( __("SKU code of displayed product", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "id",
						"heading" => esc_html__("ID", 'jacqueline'),
						"description" => wp_kses_data( __("ID of displayed product", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "posts_per_page",
						"heading" => esc_html__("Number", 'jacqueline'),
						"description" => wp_kses_data( __("How many products showed", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_type",
						"heading" => esc_html__("Post type", 'jacqueline'),
						"description" => wp_kses_data( __("Post type for the WP query (leave 'product')", 'jacqueline') ),
						"class" => "",
						"value" => "product",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_status",
						"heading" => esc_html__("Post status", 'jacqueline'),
						"description" => wp_kses_data( __("Display posts only with this status", 'jacqueline') ),
						"class" => "",
						"value" => array(
							esc_html__('Publish', 'jacqueline') => 'publish',
							esc_html__('Protected', 'jacqueline') => 'protected',
							esc_html__('Private', 'jacqueline') => 'private',
							esc_html__('Pending', 'jacqueline') => 'pending',
							esc_html__('Draft', 'jacqueline') => 'draft'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Product_Page extends JACQUELINE_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Product
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product",
				"name" => esc_html__("Product", 'jacqueline'),
				"description" => wp_kses_data( __("WooCommerce shortcode: display one product", 'jacqueline') ),
				"category" => esc_html__('WooCommerce', 'jacqueline'),
				'icon' => 'icon_trx_product',
				"class" => "trx_sc_single trx_sc_product",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "sku",
						"heading" => esc_html__("SKU", 'jacqueline'),
						"description" => wp_kses_data( __("Product's SKU code", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "id",
						"heading" => esc_html__("ID", 'jacqueline'),
						"description" => wp_kses_data( __("Product's ID", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Product extends JACQUELINE_VC_ShortCodeSingle {}
		
		
			// WooCommerce - Best Selling Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "best_selling_products",
				"name" => esc_html__("Best Selling Products", 'jacqueline'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show best selling products", 'jacqueline') ),
				"category" => esc_html__('WooCommerce', 'jacqueline'),
				'icon' => 'icon_trx_best_selling_products',
				"class" => "trx_sc_single trx_sc_best_selling_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'jacqueline'),
						"description" => wp_kses_data( __("How many products showed", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'jacqueline'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Best_Selling_Products extends JACQUELINE_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Recent Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "recent_products",
				"name" => esc_html__("Recent Products", 'jacqueline'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show recent products", 'jacqueline') ),
				"category" => esc_html__('WooCommerce', 'jacqueline'),
				'icon' => 'icon_trx_recent_products',
				"class" => "trx_sc_single trx_sc_recent_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'jacqueline'),
						"description" => wp_kses_data( __("How many products showed", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"

					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'jacqueline'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'jacqueline'),
						"description" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'jacqueline') => 'date',
							esc_html__('Title', 'jacqueline') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'jacqueline'),
						"description" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(jacqueline_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Recent_Products extends JACQUELINE_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Related Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "related_products",
				"name" => esc_html__("Related Products", 'jacqueline'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show related products", 'jacqueline') ),
				"category" => esc_html__('WooCommerce', 'jacqueline'),
				'icon' => 'icon_trx_related_products',
				"class" => "trx_sc_single trx_sc_related_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "posts_per_page",
						"heading" => esc_html__("Number", 'jacqueline'),
						"description" => wp_kses_data( __("How many products showed", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'jacqueline'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'jacqueline'),
						"description" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'jacqueline') => 'date',
							esc_html__('Title', 'jacqueline') => 'title'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Related_Products extends JACQUELINE_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Featured Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "featured_products",
				"name" => esc_html__("Featured Products", 'jacqueline'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show featured products", 'jacqueline') ),
				"category" => esc_html__('WooCommerce', 'jacqueline'),
				'icon' => 'icon_trx_featured_products',
				"class" => "trx_sc_single trx_sc_featured_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'jacqueline'),
						"description" => wp_kses_data( __("How many products showed", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'jacqueline'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'jacqueline'),
						"description" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'jacqueline') => 'date',
							esc_html__('Title', 'jacqueline') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'jacqueline'),
						"description" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(jacqueline_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Featured_Products extends JACQUELINE_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Top Rated Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "top_rated_products",
				"name" => esc_html__("Top Rated Products", 'jacqueline'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show top rated products", 'jacqueline') ),
				"category" => esc_html__('WooCommerce', 'jacqueline'),
				'icon' => 'icon_trx_top_rated_products',
				"class" => "trx_sc_single trx_sc_top_rated_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'jacqueline'),
						"description" => wp_kses_data( __("How many products showed", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'jacqueline'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'jacqueline'),
						"description" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'jacqueline') => 'date',
							esc_html__('Title', 'jacqueline') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'jacqueline'),
						"description" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(jacqueline_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Top_Rated_Products extends JACQUELINE_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Sale Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "sale_products",
				"name" => esc_html__("Sale Products", 'jacqueline'),
				"description" => wp_kses_data( __("WooCommerce shortcode: list products on sale", 'jacqueline') ),
				"category" => esc_html__('WooCommerce', 'jacqueline'),
				'icon' => 'icon_trx_sale_products',
				"class" => "trx_sc_single trx_sc_sale_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'jacqueline'),
						"description" => wp_kses_data( __("How many products showed", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'jacqueline'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'jacqueline'),
						"description" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'jacqueline') => 'date',
							esc_html__('Title', 'jacqueline') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'jacqueline'),
						"description" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(jacqueline_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Sale_Products extends JACQUELINE_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Product Category
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_category",
				"name" => esc_html__("Products from category", 'jacqueline'),
				"description" => wp_kses_data( __("WooCommerce shortcode: list products in specified category(-ies)", 'jacqueline') ),
				"category" => esc_html__('WooCommerce', 'jacqueline'),
				'icon' => 'icon_trx_product_category',
				"class" => "trx_sc_single trx_sc_product_category",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'jacqueline'),
						"description" => wp_kses_data( __("How many products showed", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'jacqueline'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'jacqueline'),
						"description" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'jacqueline') => 'date',
							esc_html__('Title', 'jacqueline') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'jacqueline'),
						"description" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(jacqueline_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "category",
						"heading" => esc_html__("Categories", 'jacqueline'),
						"description" => wp_kses_data( __("Comma separated category slugs", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "operator",
						"heading" => esc_html__("Operator", 'jacqueline'),
						"description" => wp_kses_data( __("Categories operator", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('IN', 'jacqueline') => 'IN',
							esc_html__('NOT IN', 'jacqueline') => 'NOT IN',
							esc_html__('AND', 'jacqueline') => 'AND'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Product_Category extends JACQUELINE_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "products",
				"name" => esc_html__("Products", 'jacqueline'),
				"description" => wp_kses_data( __("WooCommerce shortcode: list all products", 'jacqueline') ),
				"category" => esc_html__('WooCommerce', 'jacqueline'),
				'icon' => 'icon_trx_products',
				"class" => "trx_sc_single trx_sc_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "skus",
						"heading" => esc_html__("SKUs", 'jacqueline'),
						"description" => wp_kses_data( __("Comma separated SKU codes of products", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("IDs", 'jacqueline'),
						"description" => wp_kses_data( __("Comma separated ID of products", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'jacqueline'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'jacqueline'),
						"description" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'jacqueline') => 'date',
							esc_html__('Title', 'jacqueline') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'jacqueline'),
						"description" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(jacqueline_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Products extends JACQUELINE_VC_ShortCodeSingle {}
		
		
		
		
			// WooCommerce - Product Attribute
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_attribute",
				"name" => esc_html__("Products by Attribute", 'jacqueline'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show products with specified attribute", 'jacqueline') ),
				"category" => esc_html__('WooCommerce', 'jacqueline'),
				'icon' => 'icon_trx_product_attribute',
				"class" => "trx_sc_single trx_sc_product_attribute",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'jacqueline'),
						"description" => wp_kses_data( __("How many products showed", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'jacqueline'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'jacqueline'),
						"description" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'jacqueline') => 'date',
							esc_html__('Title', 'jacqueline') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'jacqueline'),
						"description" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(jacqueline_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "attribute",
						"heading" => esc_html__("Attribute", 'jacqueline'),
						"description" => wp_kses_data( __("Attribute name", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "filter",
						"heading" => esc_html__("Filter", 'jacqueline'),
						"description" => wp_kses_data( __("Attribute value", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Product_Attribute extends JACQUELINE_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Products Categories
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_categories",
				"name" => esc_html__("Product Categories", 'jacqueline'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show categories with products", 'jacqueline') ),
				"category" => esc_html__('WooCommerce', 'jacqueline'),
				'icon' => 'icon_trx_product_categories',
				"class" => "trx_sc_single trx_sc_product_categories",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "number",
						"heading" => esc_html__("Number", 'jacqueline'),
						"description" => wp_kses_data( __("How many categories showed", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'jacqueline'),
						"description" => wp_kses_data( __("How many columns per row use for categories output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'jacqueline'),
						"description" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'jacqueline') => 'date',
							esc_html__('Title', 'jacqueline') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'jacqueline'),
						"description" => wp_kses_data( __("Sorting order for products output", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(jacqueline_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "parent",
						"heading" => esc_html__("Parent", 'jacqueline'),
						"description" => wp_kses_data( __("Parent category slug", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "date",
						"type" => "textfield"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("IDs", 'jacqueline'),
						"description" => wp_kses_data( __("Comma separated ID of products", 'jacqueline') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "hide_empty",
						"heading" => esc_html__("Hide empty", 'jacqueline'),
						"description" => wp_kses_data( __("Hide empty categories", 'jacqueline') ),
						"class" => "",
						"value" => array("Hide empty" => "1" ),
						"type" => "checkbox"
					)
				)
			) );
			
			class WPBakeryShortCode_Products_Categories extends JACQUELINE_VC_ShortCodeSingle {}
		
		
		}
	}
}
?>