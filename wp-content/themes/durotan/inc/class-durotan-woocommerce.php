<?php
/**
 * Woocommerce functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Woocommerce initial
 *
 */
class WooCommerce {
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
		$this->init();
		add_action( 'wp', array( $this, 'add_actions' ), 10 );
	}

	/**
	 * WooCommerce Init
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init() {
		$this->get( 'setup' );
		$this->get( 'sidebars' );
		$this->get( 'customizer' );
		$this->get( 'cache' );
		$this->get( 'dynamic_css' );
		$this->get( 'product_settings' );
		$this->get( 'wcboost_wishlist' );

		$this->get_template( 'general' );

		$this->get_element( 'showcase' );
		$this->get_element( 'summary' );
	}

	/**
	 * Add Actions
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_actions() {
		$this->get_template( 'catalog' );
		$this->get_template( 'product_loop' );
		$this->get_template( 'single_product' );
		$this->get_template( 'account' );
		$this->get_template( 'cart' );
		$this->get_template( 'checkout' );

		$this->get_module( 'badges' );
		$this->get_module( 'quick_view' );
		$this->get_module( 'notices' );
		$this->get_module( 'mini_cart' );
		$this->get_module( 'sticky_atc' );
		$this->get_module( 'recently_viewed' );
		$this->get_module( 'compare' );
	}

	/**
	 * Get WooCommerce Class Init.
	 *
	 * @since 1.0.0
	 *
	 * @return object
	 */
	public function get( $class ) {
		switch ( $class ) {
			case 'setup':
				return \Durotan\WooCommerce\Setup::instance();
				break;
			case 'sidebars':
				return \Durotan\WooCommerce\Sidebars::instance();
				break;
			case 'customizer':
				return \Durotan\WooCommerce\Customizer::instance();
				break;

			case 'cache':
				return \Durotan\WooCommerce\Cache::instance();
				break;

			case 'dynamic_css':
				return \Durotan\WooCommerce\Dynamic_CSS::instance();
				break;
			case 'product_settings':
				if ( is_admin() ) {
					return \Durotan\WooCommerce\Settings\Product::instance();
				}
				break;
			case 'wcboost_wishlist':
				if( function_exists('wcboost_wishlist') ) {
					return \Durotan\WooCommerce\WCBoost_Wishlist::instance();
				}
				break;
		}
	}

	/**
	 * Get WooCommerce Template Class.
	 *
	 * @since 1.0.0
	 *
	 * @return object
	 */
	public function get_template( $class ) {
		switch ( $class ) {
			case 'general':
				return \Durotan\WooCommerce\Template\General::instance();
				break;
			case 'catalog':
				if ( \Durotan\Helper::is_catalog() ) {
					return \Durotan\WooCommerce\Template\Catalog::instance();
				}
				break;
			case 'product_loop':
				return \Durotan\WooCommerce\Template\Product_Loop::instance();
				break;
			case 'single_product':
				if ( is_singular( 'product' ) ) {
					return \Durotan\WooCommerce\Template\Single_Product::instance();
				}
				break;
			case 'account':
				if ( function_exists('is_account_page') && is_account_page() ) {
					return \Durotan\WooCommerce\Template\Account::instance();
				}
				break;
			case 'cart':
				if ( function_exists('is_cart') && is_cart() ) {
					return \Durotan\WooCommerce\Template\Cart::instance();
				}
				break;
			case 'checkout':
				if ( function_exists('is_checkout') && is_checkout() ) {
					return \Durotan\WooCommerce\Template\Checkout::instance();
				}
				break;
			default :
				break;
		}
	}

	/**
	 * Get Module.
	 *
	 * @since 1.0.0
	 *
	 * @return object
	 */
	public function get_module( $class ) {
		switch ( $class ) {
			case 'mini_cart':
				return \Durotan\WooCommerce\Modules\Mini_Cart::instance();
				break;
			case 'badges':
				return \Durotan\WooCommerce\Modules\Badges::instance();
				break;
			case 'quick_view':
				return \Durotan\WooCommerce\Modules\Quick_View::instance();
				break;
			case 'notices':
				return \Durotan\WooCommerce\Modules\Notices::instance();
				break;
			case 'sticky_atc':
				if ( is_singular( 'product' ) && intval( apply_filters( 'durotan_product_add_to_cart_sticky', Helper::get_option( 'product_add_to_cart_sticky' ) ) ) ) {
					return \Durotan\WooCommerce\Modules\Sticky_ATC::instance();
				}
				break;
			case 'recently_viewed':
				return \Durotan\WooCommerce\Modules\Recently_Viewed::instance();
				break;
			case 'compare':
				if( function_exists('wcboost_products_compare') ) {
					return \Durotan\WooCommerce\Modules\Compare::instance();
				}
				break;
			default :
				break;
		}
	}

	/**
	 * Get WooCommerce Elements.
	 *
	 * @since 1.0.0
	 *
	 * @return object
	 */
	public function get_element( $class ) {
		switch ( $class ) {
			case 'showcase':
				return \Durotan\WooCommerce\Elements\Product_ShowCase::instance();
				break;
			case 'summary':
				return \Durotan\WooCommerce\Elements\Product_Summary::instance();
				break;
		}
	}
}
