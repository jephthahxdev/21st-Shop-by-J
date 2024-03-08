<?php
/**
 * Hooks of cart.
 *
 * @package Durotan
 */

namespace Durotan\WooCommerce\Template;
use Durotan\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of cart template.
 */
class Cart {
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

		add_action( 'woocommerce_cart_collaterals', array( $this, 'open_woocommerce_cart_collaterals_bg' ), 1 );
		add_action( 'woocommerce_cart_collaterals', array( $this, 'get_payments' ) );
		add_action( 'woocommerce_cart_collaterals', array( $this, 'close_woocommerce_cart_collaterals_bg' ) );

		// Add button continue shopping
		add_action( 'woocommerce_cart_collaterals', array( $this, 'button_continue_shop' ) );

		// Change template qty
		add_action('woocommerce_before_quantity_input_field', array($this, 'open_template_qty'), 10);
		add_action('woocommerce_after_quantity_input_field', array($this, 'close_template_qty'), 10);
	}

	/**
	 * Open woocommerce cart collaterals bg
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function open_woocommerce_cart_collaterals_bg () {
		echo '<div class="woocommerce-cart-collaterals__bg">';
	}

	/**
	 * Close woocommerce cart collaterals bg
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function close_woocommerce_cart_collaterals_bg () {
		echo '</div>';
	}

	/**
	 * Add button continue shop
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function button_continue_shop() {
		echo sprintf(
			'<a href="%s" class="continue-button">%s</a>',
			esc_url( get_permalink( get_option( 'woocommerce_shop_page_id' ) ) ),
			esc_html__( 'Continue Shopping', 'durotan' )
		);
	}

	/**
	 * Get payments
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_payments() {
		$output = array();
		$images = Helper::get_option( 'footer_payment_images' );
		if ( $images ) {
			if( Helper::get_option( 'page_cart_show_payments_label' ) )
			{
				$output[] = '<span class="label">' . Helper::get_option( 'page_cart_show_payments_label' ) . '</span>';
			}

			foreach ( $images as $image ) {

				if ( ! isset( $image['image'] ) && ! $image['image'] ) {
					continue;
				}

				$image_id = $image['image'];

				$img = wp_get_attachment_image( $image_id, 'full' );
				if ( isset( $image['link'] ) && ! empty( $image['link'] ) ) {
					if ( $img ) {
						$output[] = sprintf( '<span class="payment-image"><a href="%s">%s</a></span>', esc_url( $image['link'] ), $img );
					}
				} else {
					if ( $img ) {
						$output[] = sprintf( '<span class="payment-image">%s</span>', $img );
					}
				}

			}
		}

		if ( $output ) {
			if( Helper::get_option( 'page_cart_show_payments' ) ) {
				printf( '<div class="page-cart-payments">%s</div>', implode( ' ', $output ) );
			}
		}
	}

	/**
	 * Open template qty
	 *
	 * @return void
	 */
	public function open_template_qty() {
		echo '<div class="qty-box qty-box__cart">';
		echo \Durotan\Icon::get_svg('minus', 'decrease', 'shop');
	}

	/**
	 * Close template qty
	 *
	 * @return void
	 */
	public function close_template_qty() {
		echo \Durotan\Icon::get_svg('plus', 'increase', 'shop');
		echo '</div>';
	}
}
