<?php
/**
 * Badges template hooks.
 *
 * @package Durotan
 */

namespace Durotan\WooCommerce\Modules;
use Durotan\Helper, Durotan\WooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of Badges template.
 */
class Badges {
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
		// Change the markup of sale flash.
		add_filter( 'woocommerce_sale_flash', array( $this, 'get_sale_flash' ), 10, 3 );

		// Remove the default sale flash.
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash' );
		if ( Helper::get_option( 'product_catalog_badges' ) ) {
			add_action( 'durotan_product_loop_thumbnail', array( $this, 'product_badges' ) );
		}

		// Badges
		if ( Helper::get_option( 'single_product_badges' ) ) {
			add_action( 'durotan_before_product_gallery', array( $this, 'product_badges' ) );
		}
	}

	/**
	 * Product badges.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_badges() {
		global $product;
		$badges = array();
		$custom_badges       = maybe_unserialize( get_post_meta( $product->get_id(), 'custom_badges_text', true ) );
		$custom_badges_bg    = get_post_meta( $product->get_id(), 'custom_badges_bg', true );
		$custom_badges_color = get_post_meta( $product->get_id(), 'custom_badges_color', true );
		
		if ( $custom_badges ) {
			$style    = '';
			$bg_color = ! empty( $custom_badges_bg ) ? 'background-color:' . $custom_badges_bg . ';' : '';
			$color    = ! empty( $custom_badges_color ) ? 'color:' . $custom_badges_color . ';' : '';

			if ( $bg_color || $color ) {
				$style = 'style="' . $color . $bg_color . '"';
			}

			$badges[] = '<span class="wc-badges custom ribbon"' . $style . '>' . esc_html( $custom_badges ) . '</span>';

		} else {
			$badges = self::get_badges();
		}

		if ( $badges != '' ) {
			if ( $custom_badges ) {
				$badges = implode( '', $badges );

			} else {
				$badges = implode( '', $badges[0] );
			}

			if ( ! empty( $badges ) ) {
				printf( '<span class="woocommerce-badges">%s</span>', $badges );
			}
		}
	}

	/**
	 * Get product badges.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_badges() {
		global $product;
		$badge  = '';
		$badges = array();

		if ( Helper::get_option( 'shop_badges_soldout' ) && ! $product->is_in_stock() ) {
			$in_stock = false;

			// Double check if this is a variable product.
			if ( $product->is_type( 'variable' ) ) {
				$variations = $product->get_available_variations();

				foreach ( $variations as $variation ) {
					if ( $variation['is_in_stock'] ) {
						$in_stock = true;
						break;
					}
				}
			}

			if ( ! $in_stock ) {
				$text               = Helper::get_option( 'shop_badges_soldout_text' );
				$text               = empty( $text ) ? esc_html__( 'Sold Out', 'durotan' ) : $text;
				$badges['sold-out'] = $badge = '<span class="wc-badges sold-out">' . esc_html( $text ) . '</span>';
			}
		} else {

			if ( Helper::get_option( 'shop_badges_new' ) && in_array( $product->get_id(), WooCommerce\Helper::get_new_product_ids() ) ) {
				$text          = Helper::get_option( 'shop_badges_new_text' );
				$text          = empty( $text ) ? esc_html__( 'New', 'durotan' ) : $text;
				$badges['new'] = $badge = '<span class="wc-badges new">' . esc_html( $text ) . '</span>';
			}

			if ( $product->is_featured() && Helper::get_option( 'shop_badges_featured' ) ) {
				$text               = Helper::get_option( 'shop_badges_featured_text' );
				$text               = empty( $text ) ? esc_html__( 'Hot', 'durotan' ) : $text;
				$badges['featured'] = $badge = '<span class="wc-badges featured">' . esc_html( $text ) . '</span>';
			}

			if ( $product->is_on_sale() && Helper::get_option( 'shop_badges_sale' ) ) {
				ob_start();
				woocommerce_show_product_sale_flash();
				$badges['sale'] = $badge = ob_get_clean();
			}
		}

		$badges = apply_filters( 'durotan_product_badges', $badges, $product );
		ksort( $badges );

		return array( $badges, $badge );
	}

	/**
	 * Sale badge.
	 *
	 * @since 1.0.0
	 *
	 * @param string $output The sale flash HTML.
	 * @param object $post The post object.
	 * @param object $product The product object.
	 *
	 * @return string
	 */
	public function get_sale_flash( $output, $post, $product ) {
		if ( ! Helper::get_option( 'shop_badges_sale' ) ) {
			return '';
		}

		$type       = Helper::get_option( 'shop_badges_sale_type' );
		$text       = Helper::get_option( 'shop_badges_sale_text' );
		$percentage = 0;

		if ( 'percent' == $type || 'both' == $type || false !== strpos( $text, '{%}' ) || false !== strpos( $text, '{$}' ) ) {

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

				$percentage = $max_percentage ? $max_percentage : $percentage;
			} elseif ( (float) $product->get_regular_price() != 0 ) {
				$saved      = (float) $product->get_regular_price() - (float) $product->get_sale_price();
				$percentage = round( ( $saved / (float) $product->get_regular_price() ) * 100 );
			}
		}

		if ( 'percent' == $type ) {
			$output = $percentage ? '<span class="wc-badges onsale"> -' . $percentage . '%</span>' : '';
		} elseif ( 'both' == $type ) {
			$output = '<span class="wc-badges onsale"><span class="percent">' . $percentage . '%</span><span class="text"> ' . wp_kses_post( $text ) . '</span></span>';
		} else {
			$output = '<span class="wc-badges onsale">' . wp_kses_post( $text ) . '</span>';
		}

		return $output;
	}

}
