<?php
/**
 * WooCommerce Quick View template hooks.
 *
 * @package Durotan
 */

namespace Durotan\WooCommerce\Modules;
use Durotan\Helper, Durotan\WooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of Product Quick View
 */
class Quick_View {
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
	public static function instance() {
		if ( is_null( self::$instance ) ) {
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
	public function __construct() {
		// Quick view modal.
		add_action( 'wc_ajax_product_quick_view', array( $this, 'quick_view' ) );

		add_action( 'durotan_woocommerce_product_quickview_thumbnail', 'woocommerce_show_product_images', 10 );
		if ( Helper::get_option( 'product_catalog_badges' ) ) {
			add_action( 'durotan_woocommerce_product_quickview_thumbnail', array( WooCommerce\Modules\Badges::instance(), 'product_badges' ) );
		}

		add_action( 'durotan_woocommerce_product_quickview_summary', 'woocommerce_template_single_title', 20 );
		add_action( 'durotan_woocommerce_product_quickview_summary', array( $this, 'open_price_box_wrapper'	), 30 );

		if ( apply_filters( 'durotan_product_show_price', true ) ) {
			add_action( 'durotan_woocommerce_product_quickview_summary', 'woocommerce_template_single_price', 40 );
		}

		add_action( 'durotan_woocommerce_product_quickview_summary', array(	WooCommerce\Helper::instance(), 'product_availability'	), 50 );
		add_action( 'durotan_woocommerce_product_quickview_summary', array(	$this, 'close_price_box_wrapper' ), 60 );

		add_action( 'durotan_woocommerce_product_quickview_summary', 'woocommerce_template_single_excerpt', 70 );
		add_action( 'durotan_woocommerce_product_quickview_summary', 'woocommerce_template_single_add_to_cart', 80 );
		add_action( 'durotan_woocommerce_product_quickview_summary', 'woocommerce_template_single_meta', 90 );
		add_action( 'durotan_woocommerce_product_quickview_summary', array( WooCommerce\Helper::instance(), 'product_share' ), 100 );

		add_action( 'wp_footer', array( $this, 'quick_view_modal' ), 40 );	
		
		add_action( 'durotan_woocommerce_product_quickview_summary', array( $this, 'open_product_button' ), 85 );
			
		add_action( 'durotan_woocommerce_product_quickview_summary', array( $this, 'close_product_button' ), 89 );
		
		if (class_exists('\Durotan\Addons\Modules\Size_Guide') ) {
			add_action( 'durotan_woocommerce_product_quickview_summary', array( \Durotan\Addons\Modules\Size_Guide::instance(), 'display_size_guide_quick_view' ), 86);
			add_action( 'durotan_woocommerce_after_product_quickview_summary', array( \Durotan\Addons\Modules\Size_Guide::instance(), 'size_guide_panel' ));
		}
	}

	/**
	 * Open button wrapper
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function open_price_box_wrapper() {
		echo '<div class="summary-price-box">';
	}

	/**
	 * Close button wrapper
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function close_price_box_wrapper() {
		echo '</div>';
	}

	/**
	 * Product quick view template.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function quick_view() {
		if ( empty( $_POST['product_id'] ) ) {
			wp_send_json_error( esc_html__( 'No product.', 'durotan' ) );
			exit;
		}

		$post_object = get_post( $_POST['product_id'] );
		if ( ! $post_object || ! in_array( $post_object->post_type, array(
				'product',
				'product_variation',
				true
			) ) ) {
			wp_send_json_error( esc_html__( 'Invalid product.', 'durotan' ) );
			exit;
		}
		$GLOBALS['post'] = $post_object;
		wc_setup_product_data( $post_object );
		ob_start();
		wc_get_template( 'content-product-quickview.php', array(
			'post_object' => $post_object,
		) );
		wp_reset_postdata();
		wc_setup_product_data( $GLOBALS['post'] );
		$output = ob_get_clean();

		wp_send_json_success( $output );
		exit;
	}

	/**
	 * Quick view modal.
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function quick_view_modal() {
		$product_loop_featured_icons = (array) Helper::get_option( 'product_loop_featured_icons' );
		if ( ! in_array( 'quickview', $product_loop_featured_icons ) ) {
			return;
		}
		?>
        <div id="quick-view-modal" class="quick-view-modal modal single-product">
            <div class="modal__backdrop"></div>
            <div class="modal-content container woocommerce">
                <div class="product"></div>
            </div>
            <div class="durotan-spinner-loading">
				<div class="bar-1"></div>
				<div class="bar-2"></div>
				<div class="bar-3"></div>
				<div class="bar-4"></div>
				<div class="bar-5"></div>
				<div class="bar-6"></div>
				<div class="bar-7"></div>
				<div class="bar-8"></div>
			</div>
        </div>
		<?php
	}

	/**
	 * Open product button
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function open_product_button() {
		?>
		<div class="product-button">
			<div class="product-button-wrapper">
				<div class="durotan-wishlist-button durotan-button button-outline show-wishlist-title">
					<?php \Durotan\WooCommerce\Helper::wishlist_button(); ?>
				</div>
		<?php
	}

	/**
	 * Close product button
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function close_product_button() {
		echo '</div></div>';
	}
}
