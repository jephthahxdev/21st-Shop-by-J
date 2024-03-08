<?php
/**
 * Compare template hooks.
 *
 * @package Durotan
 */

namespace Durotan\WooCommerce\Modules;

use Durotan\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of Compare template.
 */
class Compare {
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
		add_filter( 'wcboost_products_compare_button_icon', array( $this, 'products_compare_button_icon' ), 10, 2 );

		add_filter( 'wcboost_products_compare_button_template_args', array( $this, 'products_compare_button_template_args' ), 10, 2 );

		add_filter( 'wcboost_products_compare_add_to_compare_fragments', array( $this, 'products_compare_add_to_compare_fragments' ), 10, 1 );

		// Compare button.
		$compare = \WCBoost\ProductsCompare\Frontend::instance();
		remove_action( 'woocommerce_after_add_to_cart_form', [ $compare, 'single_add_to_compare_button' ] );
		remove_action( 'woocommerce_after_shop_loop_item', [ $compare, 'loop_add_to_compare_button' ], 15 );
	}

	/**
	 * Update a single cart item.
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function products_compare_button_icon( $svg, $icon ) {
		if( $icon == 'arrows' ) {
			$svg = \Durotan\Icon::get_svg( 'repeat', '', 'shop' );
		} else if ( $icon == 'check' ) {
			$svg = \Durotan\Icon::get_svg( 'repeat-done', '', 'shop' );
		}

		return $svg;
	}

	/**
	 * Show button compare.
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function products_compare_button_template_args( $args, $product ) {
		$args['class'][] = 'durotan-loop_button';

		return $args;
	}

	/**
	 * Ajaxify update count compare
	 *
	 * @since 1.0
	 *
	 * @param array $fragments
	 *
	 * @return array
	 */
	public static function products_compare_add_to_compare_fragments( $data ) {
		$data['.header-compare .header-compare__counter'] = '<span class="header-compare__counter header-counter">'. \WCBoost\ProductsCompare\Plugin::instance()->list->count_items() . '</span>';

		return $data;
	}
}
