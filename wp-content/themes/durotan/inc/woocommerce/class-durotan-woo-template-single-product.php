<?php

/**
 * Hooks of single product.
 *
 * @package Durotan
 */

namespace Durotan\WooCommerce\Template;

use Durotan\Helper;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Class of single product template.
 */
class Single_Product
{
	/**
	 * Instance
	 *
	 * @var $instance
	 */
	protected static $instance = null;

	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object
	 */
	public static function instance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct()
	{
		// Adds a class of product layout to body.
		add_filter( 'body_class', array( $this, 'body_class' ) );

		// Change header background.
		add_filter( 'durotan_header_class', array( $this, 'header_class' ), 20 );

		// Adds a class of product layout to product page.
		add_filter('post_class', array($this, 'product_class'), 10, 3);
		add_action('wp_enqueue_scripts', array($this, 'scripts'), 20);

		add_filter('durotan_wp_script_data', array(
			$this,
			'product_script_data'
		));

		// Replace the default sale flash.
		remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash');

		// Change the product thumbnails columns
		add_filter('woocommerce_product_thumbnails_columns', array($this, 'product_thumbnails_columns'));

		// Gallery summary wrapper
		add_action('woocommerce_before_single_product_summary', array(
			$this,
			'open_gallery_summary_wrapper'
		), 19);
		add_action('woocommerce_after_single_product_summary', array(
			$this,
			'close_gallery_summary_wrapper'
		), 2);

		// Button wrapper
		add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'open_control_button_wrapper' ), 10 );
		add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'product_single_break_button' ), 80 );
		add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'product_single_wishlist' ), 85 );
		add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'product_single_compare'), 90 );
		add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'close_control_button_wrapper' ), 100 );

		// Buy now
		add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'product_buy_now_button' ), 80 );

		// Change wishlist button
		add_filter('yith_wcwl_show_add_to_wishlist', '__return_empty_string');
		// Change conpare button
		add_filter('yith_woocompare_remove_compare_link_by_cat', '__return_true');

		// Summary order els
		add_action( 'woocommerce_single_product_summary', array( $this, 'single_product_taxonomy' ), 3 );

		if($this->single_get_product_layout() !== 'v5'){
			add_action('woocommerce_single_product_summary', array(
				\Durotan\WooCommerce\Helper::instance(),
				'product_availability'
			), 16);
			add_action('woocommerce_single_product_summary', array($this, 'product_share'), 50);
		}

		// Remove product tab heading.
		add_filter('woocommerce_product_description_heading', '__return_null');
		add_filter('woocommerce_product_reviews_heading', '__return_null');
		add_filter('woocommerce_product_additional_information_heading', '__return_null');


		// Change Review Avatar Size
		add_filter( 'woocommerce_review_gravatar_size', array( $this, 'review_gravatar_size' ) );

		// change product gallery classes
		add_filter('woocommerce_single_product_image_gallery_classes', array(
			$this,
			'product_image_gallery_classes'
		));

		// Related options
		add_filter('woocommerce_product_related_products_heading', array($this,'related_products_heading'));
		add_filter('woocommerce_product_related_posts_relate_by_category', array($this,'related_products_by_category'));
		add_filter('woocommerce_get_related_product_cat_terms', array($this,'related_products_by_parent_category'), 20, 2);
		add_filter('woocommerce_product_related_posts_relate_by_tag', array($this, 'related_products_by_tag'));
		add_filter('woocommerce_output_related_products_args', array($this, 'get_related_products_args'));

		// Upsells Products
		add_filter( 'woocommerce_upsell_display_args', array($this, 'get_upsell_display_args') );
		add_filter( 'woocommerce_product_upsells_products_heading', array($this, 'product_upsells_heading') );


		if (in_array($this->single_get_product_layout(), array('v1', 'v2', 'v4', 'v7'))) {
			// Replace Woocommerce notices
			remove_action('woocommerce_before_single_product', 'woocommerce_output_all_notices', 10);
			add_action('woocommerce_before_single_product_summary', 'woocommerce_output_all_notices', 10);
			// Custom product toolbar
			remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
			add_action('woocommerce_before_single_product_summary', array($this, 'product_toolbar'), 5);


			// Upsells Products
			if ( ! intval( Helper::get_option( 'product_upsells' ) ) ) {
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
			}
			// Related Products
			if (!intval(Helper::get_option('product_related'))) {
				remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
			}
		}

		// Product Layout
		switch ( $this->single_get_product_layout() ) {
			case 'v1':
				break;
			case 'v2':
				// Move product tabs into the summary.
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
				add_action( 'woocommerce_single_product_summary', array( $this, 'product_data_tabs' ), 100 );
				break;
			case 'v3':
				// Replace Woocommerce notices
				remove_action('woocommerce_before_single_product', 'woocommerce_output_all_notices', 10);
				add_action('woocommerce_before_single_product_summary', 'woocommerce_output_all_notices', 19);
				// Custom product toolbar
				remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
				add_action('woocommerce_single_product_summary', array($this, 'product_toolbar'), 2);

				// Change Gallery
				add_filter( 'woocommerce_single_product_flexslider_enabled', '__return_false' );
				add_filter( 'woocommerce_gallery_image_size', array( $this, 'gallery_image_size_large' ) );

				// Move product tabs into the summary.
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
				add_action( 'woocommerce_single_product_summary', array( $this, 'product_data_tabs' ), 100 );

				// Place related products outside product container.
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

				if (intval(Helper::get_option('product_upsells'))) {
					add_action( 'woocommerce_after_single_product', 'woocommerce_upsell_display', 10 );
				}
				if (intval(Helper::get_option('product_related'))) {
					add_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 20 );
				}

				// Add preloader
				if ( intval( Helper::get_option( 'product_auto_background' ) ) && !get_post_meta( Helper::get_post_ID(), 'background_color', true )) {
					add_action( 'wp_footer', array( $this, 'add_preloader_to_footer' ) );
				}

				break;
			case 'v4':
				// Add a singe product widget on the right side.
				add_action( 'woocommerce_after_single_product_summary', array( $this, 'sidebar_products' ), 1 );
				break;
			case 'v5':
				// Add SKU after product title
				add_action( 'woocommerce_single_product_summary',  array( $this, 'product_sku'), 6);

				// Replace Woocommerce notices
				if(intval( Helper::get_option('product_transparent_header'))){
					remove_action('woocommerce_before_single_product', 'woocommerce_output_all_notices', 10);
					add_action('woocommerce_single_product_summary', 'woocommerce_output_all_notices', 3);
				}

				// Custom product toolbar
				remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
				add_action('woocommerce_single_product_summary', array($this, 'product_toolbar'), 2);

				// Move product tabs into the summary.
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
				add_action( 'woocommerce_single_product_summary', array( $this, 'product_data_tabs_v2' ), 100 );

				// Place related products outside product container.
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

				if (intval(Helper::get_option('product_upsells'))) {
					add_action( 'woocommerce_after_single_product', 'woocommerce_upsell_display', 10 );
				}
				if (intval(Helper::get_option('product_related'))) {
					add_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 20 );
				}


				// Flexslider options - Add Navigation Arrows
				add_filter( 'woocommerce_single_product_carousel_options', array( $this, 'flexslider_options') );

				// Sharing
				add_action('woocommerce_after_add_to_cart_button', array($this, 'product_share'), 99);

				// Re-order the stars rating.
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 8 );

				// Re-order price.
				add_action( 'woocommerce_single_product_summary', array( $this, 'open_price_box_wrapper' ), 9 );
				add_action( 'woocommerce_single_product_summary', array( $this, 'product_price_save' ), 10 );
				add_action( 'woocommerce_single_product_summary', array( $this, 'close_price_box_wrapper' ), 11 );
				break;
			case 'v6':
				// Replace Woocommerce notices
				remove_action('woocommerce_before_single_product', 'woocommerce_output_all_notices', 10);
				add_action('woocommerce_single_product_summary', 'woocommerce_output_all_notices', 3);

				// Custom product toolbar
				remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
				add_action('woocommerce_single_product_summary', array($this, 'product_toolbar'), 2);

				// Move product tabs into the summary.
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
				add_action( 'woocommerce_single_product_summary', array( $this, 'product_data_tabs' ), 100 );

				// Flexslider options - Add Navigation Arrows
				add_filter( 'woocommerce_single_product_carousel_options', array( $this, 'flexslider_options') );

				// Add SKU after product title
				add_action( 'woocommerce_single_product_summary',  array( $this, 'product_sku'), 5);

				// Place related products outside product container.
				if ( Helper::get_option( 'header_layout' ) !== 'v7' || Helper::get_option( 'header_type' ) !== 'default') {
					remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
					remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

					if (intval(Helper::get_option('product_upsells'))) {
						add_action( 'woocommerce_after_single_product', 'woocommerce_upsell_display', 10 );
					}
					if (intval(Helper::get_option('product_related'))) {
						add_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 20 );
					}
				}else{
					// Upsells Products
					if ( ! intval( Helper::get_option( 'product_upsells' ) ) ) {
						remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
					}
					// Related Products
					if (!intval(Helper::get_option('product_related'))) {
						remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
					}
				}
				// Change "Browse wishlist" text
				add_filter( 'yith_wcwl_browse_wishlist_label', array($this, 'change_browse_wishlist_label'));
				add_filter( 'wcboost_wishlist_button_view_text',	array( $this, 'wishlist_button_view_text' ) );
				break;
			case 'v7':
				// Flexslider options - Add Navigation Arrows
				add_filter( 'woocommerce_single_product_carousel_options', array( $this, 'flexslider_options') );

				// Add SKU after product title
				add_action( 'woocommerce_single_product_summary',  array( $this, 'product_sku'), 6);

				// Change "Browse wishlist" text
				add_filter( 'yith_wcwl_browse_wishlist_label', array($this, 'change_browse_wishlist_label'));
				add_filter( 'wcboost_wishlist_button_view_text',	array( $this, 'wishlist_button_view_text' ) );
				break;
			default:
				break;
		}
	}

	/**
	 * WooCommerce specific scripts & stylesheets.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function scripts()
	{
		$debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_register_script( 'background-color-theif', get_template_directory_uri() . '/assets/js/plugins/background-color-theif' . $debug . '.js', array(), '1.0', true );
		wp_enqueue_script('durotan-single-product', get_template_directory_uri() . '/assets/js/woocommerce/single-product.js', array(
			'durotan',
			'background-color-theif'
		), '20201012', true);
	}

	/**
	 * WooCommerce specific scripts & stylesheets.
	 *
	 * @since 1.0.0
	 *
	 * @param $data
	 *
	 * @return array
	 */
	public function product_script_data($data)
	{
		$data['product_gallery_slider'] 	 = $this->product_gallery_is_slider();
		$data['product_image_zoom']     	 = intval(Helper::get_option('product_image_zoom'));
		$data['product_auto_background']     = intval(Helper::get_option('product_auto_background'));
		$data['swiper_message']     		 = __('Hold & Drag','durotan');

		return $data;
	}

	/**
	 * Make header transparent on product page if selected product layout is Version 1.
	 *
	 * @since 1.0.0
	 *
	 * @param array $classes Header classes.
	 *
	 * @return array
	 */
	public function header_class( $classes ) {
		if ( ! is_product() ) {
			return $classes;
		}
		if ( in_array( $this->single_get_product_layout(), array( 'v5' ) ) && intval( Helper::get_option('product_transparent_header')) ) {
			$classes .= ' has-transparent';
		}

		return $classes;
	}

	/**
	 * Adds classes to body
	 *
	 * @since 1.0.0
	 *
	 * @param string $class Body class.
	 *
	 * @return array
	 */
	public function body_class( $classes ) {
		$product_layout = $this->single_get_product_layout();
		$classes[]      = 'product-' . $product_layout;

		if ( in_array( $this->single_get_product_layout(), array( 'v5') ) && intval( Helper::get_option('product_transparent_header')) ) {
			$classes[] = 'header-transparent';
		}

		return $classes;
	}

	/**
	 * Adds classes to products
	 *
	 * @since 1.0.0
	 *
	 * @param string $class Post class.
	 *
	 * @return array
	 */
	public function product_class($classes)
	{
		if (is_admin() && !defined('DOING_AJAX')) {
			return $classes;
		}

		$classes[] = 'layout-' . $this->single_get_product_layout();

		if (in_array($this->single_get_product_layout(), array('v1', 'v2'))) {
			$classes[] = 'product-thumbnails-vertical';
		}

		if($this->single_get_product_layout() === 'v4' && is_active_sidebar( 'product-sidebar' )){
			$classes[] = 'sidebar-active';
		}

		if (Helper::get_option('product_add_to_cart_ajax')) {
			$classes[] = 'product-add-to-cart-ajax';
		}

		global $product;

		$video_image = get_post_meta($product->get_id(), 'video_thumbnail', true);

		if ($product->get_gallery_image_ids() || $video_image) {
			$classes[] = 'has-gallery-image';
		}

		if (in_array($this->single_get_product_layout(), array('v3', 'v5')) && get_post_meta( $product->get_id(), 'background_color', true ) ) {
			$classes[] = 'background-set';
		}

		return $classes;
	}

	/**
	 * Open gallery summary wrapper
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function open_gallery_summary_wrapper()
	{
		global $product;

		$container = apply_filters('durotan_single_product_container_class', '');

		if (in_array($this->single_get_product_layout(), array('v3', 'v5'))) {
			$container = Helper::get_option( 'single_product_width' );
		}
		echo '<div class="product-gallery-summary clearfix ' . esc_attr($container) . ' single-product-'.$product->get_type().'">';
	}

	/**
	 * Close gallery summary wrapper
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function close_gallery_summary_wrapper()
	{
		echo '</div>';
	}

	/**
	 * Open button wrapper
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function open_price_box_wrapper()
	{
		echo '<div class="summary-price-box">';
	}

	/**
	 * Close button wrapper
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function close_price_box_wrapper()
	{
		echo '</div>';
	}

	/**
	 * Get product price
	 */
	public static function product_price_save() {

		$product = wc_get_product( get_the_id() );
		$discount  = 0;

		if ( $product->get_type() == 'variable' ) {
			$available_variations = $product->get_available_variations();
			$max_percentage       = 0;
			$max_saved            = 0;
			$total_variations     = count( $available_variations );

			for ( $i = 0; $i < $total_variations; $i ++ ) {
				$variation_id        = $available_variations[ $i ]['variation_id'];
				$variable_product    = new \WC_Product_Variation( $variation_id );
				$regular_price       = $variable_product->get_regular_price();
				$sales_price         = $variable_product->get_sale_price();
				$variable_saved      = $regular_price && $sales_price ? ( $regular_price - $sales_price ) : 0;
				$variable_percentage = $regular_price && $sales_price ? round( ( ( ( $regular_price - $sales_price ) / $regular_price ) * 100 ) ) : 0;

				if ( $variable_saved > $max_saved ) {
					$max_saved = $variable_saved;
				}

				if ( $variable_percentage > $max_percentage ) {
					$max_percentage = $variable_percentage;
				}
			}

			if ( $max_percentage !== 0 ) {
				$discount = $max_percentage;
			}
		}else{
			$price_regular = intval( $product->get_regular_price() );
			$price_sale = intval( $product->get_sale_price() );
			$discount = intval( ( ( $price_regular - $price_sale ) / $price_regular ) * 100 );
		}

		if ( $discount ) {
			echo sprintf(
					'<span class="discount">%s %s</span>',
					esc_html__( 'Save', 'durotan' ),
					$discount.'%'
				);
		}
	}

	/**
	 * Product thumbnails columns
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function product_thumbnails_columns()
	{
		return intval(Helper::get_option('product_thumbnail_numbers'));
	}

	/**
	 * Displays sharing buttons.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_share()
	{
		if (!class_exists('\Durotan\Addons\Helper') && !method_exists('\Durotan\Addons\Helper', 'share_link')) {
			return;
		}

		if (!Helper::get_option('product_sharing')) {
			return;
		}

		$socials = Helper::get_option('product_sharing_socials');
		$whatsapp_number = Helper::get_option( 'product_sharing_whatsapp_number' );

		if (empty($socials)) {
			return;
		}
		?>
		<div class="product-share share">
			<span class="socials">
				<?php
				foreach ($socials as $social) {
					echo \Durotan\Addons\Helper::share_link($social, array( 'whatsapp_number' => $whatsapp_number ), 'social_2');
				}
				?>
			</span>
		</div>
		<?php
	}

	/**
	 * Change review gravatar size
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function review_gravatar_size()
	{
		return '70';
	}

	/**
	 * Related products by category
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function related_products_by_category()
	{
		return Helper::get_option('product_related_by_categories');
	}

	/**
	 * Related products by parent category
	 *
	 * @since 1.0.0
	 *
	 * @param array $term_ids
	 * @param int $product_id
	 *
	 * @return array
	 */
	public function related_products_by_parent_category($term_ids, $product_id)
	{
		if (!intval(Helper::get_option('product_related_by_categories'))) {
			return $term_ids;
		}

		if (!intval(Helper::get_option('product_related_by_parent_category'))) {
			return $term_ids;
		}

		$terms = wc_get_product_terms(
			$product_id,
			'product_cat',
			array(
				'orderby' => 'parent',
			)
		);

		$term_ids = array();

		if (!is_wp_error($terms) && $terms) {
			$current_term = end($terms);
			$term_ids[]   = $current_term->term_id;
		}

		return $term_ids;
	}

	/**
	 * Related products by tag
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function related_products_by_tag()
	{
		return Helper::get_option('product_related_by_tags');
	}

	/**
	 * Related products heading
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function related_products_heading()
	{
		return Helper::get_option('product_related_title');
	}

	/**
	 * Change Related products args
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_related_products_args($args)
	{
		$args = array(
			'posts_per_page' => intval( Helper::get_option('product_related_numbers') ),
			'columns'        => intval( Helper::get_option('product_related_columns') ),
			'orderby'        => 'rand',
		);

		return $args;
	}

	/**
	 * Add class to product gallery
	 *
	 * @since 1.0.0
	 *
	 * @param array $classes
	 *
	 * @return array
	 */
	public function product_image_gallery_classes($classes)
	{
		global $product;
		$attachment_ids = $product->get_gallery_image_ids();

		if (!$attachment_ids) {
			$classes[] = 'without-thumbnails';
		}

		return $classes;
	}

	/**
	 * Change Upsell products args
	 *
	 * @since 1.0.0
     *
     * @param array $args
	 *
	 * @return array
	 */
	public function get_upsell_display_args( $args ) {
		$args = array(
			'posts_per_page' => intval( Helper::get_option( 'product_upsells_numbers' ) ),
			'columns'        => intval( Helper::get_option( 'product_upsells_columns' ) ),
		);

		return $args;
	}

	/**
	 * Product Upsells heading
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function product_upsells_heading() {
		return Helper::get_option( 'product_upsells_title' );
	}

	/**
	 * Change product gallery image size
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function gallery_image_size_large($size)
	{
		return 'woocommerce_single';
	}

	/**
	 * Category name
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function single_product_taxonomy()
	{
		if ( $taxonomy = Helper::get_option( 'product_taxonomy' ) ) {
			\Durotan\WooCommerce\Helper::product_taxonomy( $taxonomy );
		}
	}

	/**
	 * Check if product gallery is slider.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function product_gallery_is_slider()
	{
		$support = ! in_array( $this->single_get_product_layout(), array( 'v3' ) );
		return apply_filters('durotan_product_gallery_is_slider', $support);
	}

	/**
	 * Get product layout
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function single_get_product_layout()
	{
		$product_layout = Helper::get_option('product_layout');
		return apply_filters('durotan_single_get_product_layout', $product_layout);
	}

	/**
	 * Previous/next product navigation.
	 *
	 * @return void
	 */
	public function product_toolbar()
	{
		$breadcrumb = Helper::get_option( 'single_product_breadcrumb' ) && ! in_array( $this->single_get_product_layout(), array( 'v3', 'v5', 'v6' ));
		$navigation = Helper::get_option( 'single_product_navigation' ) && ! in_array( $this->single_get_product_layout(), array( 'v6', 'v7' ) );
		$back_to_shop = Helper::get_option( 'product_back_to_shop' ) && in_array( $this->single_get_product_layout(), array( 'v3', 'v5', 'v6' ) );

		if ( ! $breadcrumb && ! $navigation && !$back_to_shop) {
			return;
		}

		?>
		<div class="product-toolbar">
		<?php
			if ( $breadcrumb ) {
				\Durotan\Theme::instance()->get( 'breadcrumbs' )->breadcrumbs();
			}
			if ( $back_to_shop ) {
				printf(
					'<a href="%s" class="durotan-back-to-shop">%s</a>',
					esc_url(  wc_get_page_permalink( 'shop' ) ),
					esc_html( 'Back to Shop', 'durotan' )
				);
			}
			if ( $navigation ) {
				$nav_args = array(
					'prev_text' 		 => \Durotan\Icon::get_svg('chevron-left') . '<span class="meta-nav" aria-hidden="true">' . __( 'Prev', 'durotan' ) . '</span> ',
					'next_text'			 => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'durotan' ) . '</span>'. \Durotan\Icon::get_svg('chevron-right'),
					'screen_reader_text' => esc_html__( 'Product navigation', 'durotan' ),
					'in_same_term' 		 => false,
					'taxonomy' 			 => 'product_cat',
				);

				the_post_navigation( $nav_args );
			}
			?>
		</div>
		<?php
	}

	/**
	 * Product data tabs.
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_data_tabs()
	{
		$tabs = apply_filters( 'woocommerce_product_tabs', array() );

		if ( ! empty( $tabs ) ) :
			?>
			<div class="woocommerce-tabs wc-tabs-wrapper panels-offscreen">
			<ul class="tabs wc-tabs" role="tablist">
					<?php foreach ( $tabs as $key => $tab ) : ?>
						<li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
							<a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
				<div class="panels">
					<div class="backdrop"></div>
					<?php foreach ( $tabs as $key => $tab ) : ?>
						<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
							<div class="button-close">
								<a href="#" class="close">
									<span class="menu-text"><?php esc_html_e( 'Close', 'durotan' ) ?></span>
									<span class="hamburger-box active"><span class="hamburger-inner"></span></span>
								</a>
							</div>
							<div class="panel-header">
								<div class="panel__title"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></div>
							</div>
							<div class="panel-content">
								<?php if ( isset( $tab['callback'] ) ) {
									call_user_func( $tab['callback'], $key, $tab );
								} ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
            </div>
		<?php
		endif;
	}

	/**
	 * Product data tabs.
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_data_tabs_v2()
	{
		$tabs = apply_filters( 'woocommerce_product_tabs', array() );

		if ( ! empty( $tabs ) ) :
			?>

            <div class="woocommerce-tabs wc-tabs-wrapper">
				<?php foreach ( $tabs as $key => $tab ) : ?>
                    <div class="durotan-tab-wrapper <?php echo esc_attr( $key ); ?>_tab">
                        <a href="#tab-<?php echo esc_attr( $key ); ?>"
                           class="durotan-accordion-title tab-title-<?php echo esc_attr( $key ); ?>">
							<?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?>
							<?php echo \Durotan\Icon::get_svg('plus', 'increase', 'shop'); ?>
							<?php echo \Durotan\Icon::get_svg('minus', 'decrease', 'shop'); ?>
                        </a>
                        <div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> entry-content wc-tab panel-content"
                             id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel">
							<?php
							if ( isset( $tab['callback'] ) ) {
								call_user_func( $tab['callback'], $key, $tab );
							}
							?>
                        </div>
                    </div>
				<?php endforeach; ?>
            </div>

		<?php
		endif;
	}

	/**
	 * Display sidebar on product page v4
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function sidebar_products(){
		if ( ! is_active_sidebar( 'product-sidebar' ) ) {
			return;
		}
		?>
		<div class="sidebar_products">
			<?php dynamic_sidebar( 'product-sidebar' ); ?>
		</div>
		<?php
	}

	/**
	 * Flexslider options - Add Navigation Arrows
	 *
	 * @since 1.0.0
	 *
	 * @param $options
	 *
	 * @return array
	 */
	public function flexslider_options($options)
	{
		$options['directionNav'] = true;
		$options['prevText'] = \Durotan\Icon::get_svg( 'chevron-left' );
		$options['nextText'] = \Durotan\Icon::get_svg( 'chevron-right' );

    	return $options;
	}

	/**
	 * Add SKU after product title
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_sku()
	{
		global $product;
		$product_meta = (array) Helper::get_option( 'product_meta' );
		if ( in_array( 'sku', $product_meta ) ) {
			if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) {
			?>
				<span class="dorutan_sku sku_wrapper"><span class="label"><?php esc_html_e( 'SKU:', 'durotan' ); ?></span><span class="sku"><?php if ( $sku = $product->get_sku() ) { echo wp_kses_post( $sku ); } else { esc_html_e( 'N/A', 'durotan' ); } ?></span></span>
			<?php
			}

		}
	}

	/**
	 * Add preloader
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_preloader_to_footer()
	{
		?>
		<div id="durotan-preloader" class="durotan-preloader">
			<span class="loading-icon">
				<span class="bubble"><span class="dot"></span></span>
				<span class="bubble"><span class="dot"></span></span>
				<span class="bubble"><span class="dot"></span></span>
			</span>
		</div>
		<?php
	}

	/**
	 * Open button wrapper
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function open_control_button_wrapper() {
		echo '<div class="product-button-wrapper">';
	}

	/**
	 * Close button wrapper
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function close_control_button_wrapper() {
		echo '</div>';
	}

	/**
	 * Breack button
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_single_break_button() {
		echo '<div class="durotan-break"></div>';
	}

	/**
	 * Display wishlist button
	 *
	 * @since 1.0
     *
	 * @return void
	 */
	public function product_single_wishlist() {
		if(! apply_filters( 'durotan_product_show_wishlist', true )){
            return;
        }

		if ( shortcode_exists( 'wcboost_wishlist_button' ) ) {
			echo '<div class="durotan-wishlist-button durotan-button button-outline show-wishlist-title">';
			echo do_shortcode( '[wcboost_wishlist_button]' );
			echo '</div>';
		} else if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
			echo '<div class="durotan-wishlist-button durotan-button button-outline show-wishlist-title">';
			echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
			echo '</div>';
		}
	}

	/**
	 * Display wishlist button
	 *
	 * @since 1.0
     *
	 * @return void
	 */
	public function product_single_compare() {
		global $product;
		if ( $product->get_type() == 'grouped' ) {
			return;
		}

		if ( 'no' === get_option( 'yith_woocompare_compare_button_in_product_page' ) )  {
			return;
		}

		if(! apply_filters( 'durotan_product_show_compare', true )){
            return;
        }

		if (in_array( $this->single_get_product_layout(), array('v4', 'v7'))) {
			return;
		}

		echo '<div class="durotan-compare-button durotan-button button-outline show-compare-title">';
		\Durotan\WooCommerce\Helper::compare_button();
		echo '</div>';
	}

	/**
	 * Add buy now button
	 *
	 * @since 1.0
	 */
	public function product_buy_now_button() {
		global $product;
		if ( ! intval(( Helper::get_option('product_buy_now') )) ) {
			return;
		}

		if(! apply_filters( 'durotan_product_show_buy_now', true )){
            return;
        }

		if (!in_array( $this->single_get_product_layout(), array('v1', 'v2'))) {
			return;
		}

		if ( $product->get_type() == 'external' || $product->get_type() == 'grouped') {
			return;
		}

		echo sprintf( '<button class="durotan-buy-now-button button">%s</button>', esc_html( Helper::get_option( 'product_buy_now_text' ) ) );
	}

	/**
	 * Change "Browse wishlist" text
	 *
	 * @since 1.0
	 */
	public function change_browse_wishlist_label() {
		return __( 'Added to wishlist', 'durotan' );
	}

	/**
	 * Change button view text
	 *
	 * @return void
	 */
	public function wishlist_button_view_text() {
		return esc_html__( 'Added to wishlist', 'durotan' );
	}
}