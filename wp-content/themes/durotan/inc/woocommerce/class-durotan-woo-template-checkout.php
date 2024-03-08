<?php
/**
 * Hooks of checkout.
 *
 * @package Durotan
 */

namespace Durotan\WooCommerce\Template;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of checkout template.
 */
class Checkout {
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
		// Wrap checkout login and coupon notices.
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
		add_action( 'woocommerce_before_checkout_form', array( $this, 'before_login_form' ), 10 );
		add_action( 'woocommerce_before_checkout_form', array( $this, 'checkout_login_form' ), 10 );
		add_action( 'woocommerce_before_checkout_form', array( $this, 'checkout_coupon_form' ), 10 );
		add_action( 'woocommerce_before_checkout_form', array( $this, 'after_login_form' ), 10 );

		add_action( 'woocommerce_checkout_before_order_review_heading', array( $this, 'open_checkout_review' ), 1 );
		add_action( 'woocommerce_checkout_after_order_review', array( $this, 'close_checkout_review' ), 100 );

		add_filter('woocommerce_checkout_fields', array( $this, 'custom_override_checkout_fields') );

	}

	/**
	 * Checkout Before login form.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function before_login_form() {
		echo '<div class="row-flex checkout-form-cols">';
	}

	/**
	 * Checkout After login form.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function after_login_form() {
		echo '</div>';
	}

	/**
	 * Checkout login form.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function checkout_login_form() {
		if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
			return;
		}

		echo '<div class="checkout-login checkout-form-col col-flex col-flex-md-6 col-flex-xs-12">';
		woocommerce_checkout_login_form();
		echo '</div>';
	}

	/**
	 * Checkout coupon form.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function checkout_coupon_form() {
		if ( ! wc_coupons_enabled() ) {
			return;
		}

		echo '<div class="checkout-coupon checkout-form-col col-flex col-flex-md-6 col-flex-xs-12">';
		woocommerce_checkout_coupon_form();
		echo '</div>';
	}

	/**
	 * Open checkout review.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function open_checkout_review() {
		echo '<div class="durotan-checkout-review">';
	}

	/**
	 * Close checkout review.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function close_checkout_review() {
		echo '</div>';
	}

	public function custom_override_checkout_fields($fields) {
		$fields['billing']['billing_company']['placeholder'] = esc_attr__( 'Business Name', 'durotan' );
		$fields['billing']['billing_company']['label'] = esc_attr__( 'Business Name', 'durotan' );
		$fields['billing']['billing_first_name']['placeholder'] = esc_attr__( 'First Name *', 'durotan' );
		$fields['billing']['billing_city']['placeholder'] = esc_attr__( 'Town / City *', 'durotan' );
		$fields['billing']['billing_postcode']['placeholder'] = esc_attr__( 'ZIP *', 'durotan' );
		$fields['shipping']['shipping_city']['placeholder'] = esc_attr__( 'Town / City *', 'durotan' );
		$fields['shipping']['shipping_postcode']['placeholder'] = esc_attr__( 'ZIP *', 'durotan' );
		$fields['shipping']['shipping_first_name']['placeholder'] = esc_attr__( 'First Name *', 'durotan' );
		$fields['shipping']['shipping_last_name']['placeholder'] = esc_attr__( 'Last Name *', 'durotan' );
		$fields['shipping']['shipping_company']['placeholder'] = esc_attr__( 'Company Name', 'durotan' );
		$fields['billing']['billing_last_name']['placeholder'] = esc_attr__( 'Last Name *', 'durotan' );
		$fields['billing']['billing_email']['placeholder'] = esc_attr__( 'Email Address', 'durotan' );
		$fields['billing']['billing_phone']['placeholder'] = esc_attr__( 'Phone', 'durotan' );

		return $fields;
	}
}
