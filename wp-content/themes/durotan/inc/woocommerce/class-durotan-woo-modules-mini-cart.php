<?php
/**
 * Mini Cart template hooks.
 *
 * @package Durotan
 */

namespace Durotan\WooCommerce\Modules;

use Durotan\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of Mini Cart template.
 */
class Mini_Cart {
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
		// Ajax update mini cart.
		add_action( 'wc_ajax_update_cart_item', array( $this, 'update_cart_item' ) );

		add_action( 'wp_footer', array( $this, 'cart_modal' ) );
	}

	/**
	 * Display Cart Modal
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function cart_modal() {
		if ( Helper::get_option( 'header_cart_behaviour' ) === 'panel' || Helper::get_option( 'header_layout' ) === 'v7' ) {
			get_template_part( 'template-parts/modals/cart' );
		}
	}

	/**
	 * Update a single cart item.
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function update_cart_item() {
		if ( empty( $_POST['cart_item_key'] ) || ! isset( $_POST['qty'] ) ) {
			wp_send_json_error();
			exit;
		}

		$cart_item_key = wc_clean( $_POST['cart_item_key'] );
		$qty           = floatval( $_POST['qty'] );

		check_admin_referer( 'durotan-update-cart-qty--' . $cart_item_key, 'security' );

		ob_start();

		WC()->cart->set_quantity( $cart_item_key, $qty );

		if ( $cart_item_key && false !== WC()->cart->set_quantity( $cart_item_key, $qty ) ) {
			\WC_AJAX::get_refreshed_fragments();
		} else {
			wp_send_json_error();
		}
	}

}
