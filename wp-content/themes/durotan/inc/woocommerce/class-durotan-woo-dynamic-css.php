<?php
/**
 * Style functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan\WooCommerce;

use Durotan\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class Dynamic_CSS {
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
		add_action( 'durotan_enqueue_woocommerce_style', array( $this, 'add_static_css' ) );
	}

	/**
	 * Get get style data
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function add_static_css() {
		$parse_css = $this->catalog_page_header_static_css();
		$parse_css .= $this->catalog_page_static_css();
		$parse_css .= $this->product_badges_static_css();
		$parse_css .= $this->single_product_page_static_css();

		wp_add_inline_style( 'durotan-woocommerce-style', apply_filters( 'durotan_inline_woocommerce_style', $parse_css ) );
	}

	/**
	 * Get CSS code of settings for shop.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function catalog_page_header_static_css() {
		$static_css = '';
		if ( ! class_exists( 'WooCommerce' ) ) {
			return $static_css;
		}

		// Catalog Page header color
		if( Helper::get_option('catalog_page_header') == 'layout-2' ) {
			if ( ( $color = Helper::get_option( 'catalog_page_header_text_color' ) ) && $color != '' ) {
				$static_css .= '.page-header__catalog--layout-2 .page-header__title {--durotan-color-darker: ' . $color . '}';
			}

			if ( ( $color = Helper::get_option( 'catalog_page_header_description_text_color' ) ) && $color != '' ) {
				$static_css .= '.page-header__catalog--layout-2 .page-header__description { color: ' . $color . '}';
			}

			if ( ( $color = Helper::get_option( 'catalog_page_header_bread_link_color' ) ) && $color != '' ) {
				$static_css .= '.page-header__catalog-page .woocommerce-breadcrumb a, .page-header__catalog-page .woocommerce-breadcrumb .delimiter { color: ' . $color . '}';
			}

			if ( ( $color = Helper::get_option( 'catalog_page_header_bread_color' ) ) && $color != '' ) {
				$static_css .= '.page-header__catalog--layout-2 .woocommerce-breadcrumb { color: ' . $color . '}';
			}
		}

		// Catalog Page header spacing
		$padding_top = Helper::get_option( 'catalog_page_header_padding_top' );
		$padding_bottom = Helper::get_option( 'catalog_page_header_padding_bottom' );

		if ( $padding_top && $padding_top != '75' ) {
			$static_css .= '.page-header__catalog-page {padding-top: ' . $padding_top . 'px}';
		}

		if ( $padding_bottom && $padding_bottom != '75' ) {
			$static_css .= '.page-header__catalog-page {padding-bottom: ' . $padding_bottom . 'px}';
		}

		return $static_css;
	}

	/**
	 * Get page style data
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	protected function catalog_page_static_css() {
		$static_css = '';

		// Page content spacings
		if ( \Durotan\Helper::is_catalog() ) {
			$post_id = intval( get_option( 'woocommerce_shop_page_id' ) );
			if ( $top = get_post_meta( $post_id, 'durotan_content_top_padding', true ) ) {
				$static_css .= '.site-content.custom-top-spacing { padding-top: ' . $top . 'px; }';
			}

			if ( $bottom = get_post_meta( $post_id, 'durotan_content_bottom_padding', true ) ) {
				$static_css .= '.site-content.custom-bottom-spacing{ padding-bottom: ' . $bottom . 'px; }';
			}
		}

		return $static_css;
	}

	/**
	 * Get CSS code of settings for product badges.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function product_badges_static_css() {
		$static_css = '';
		if ( ! class_exists( 'WooCommerce' ) ) {
			return $static_css;
		}

		// Product badges.
		if ( ( $bg = Helper::get_option( 'shop_badges_sale_bg' ) ) && $bg != '' ) {
			$static_css .= '.woocommerce-badges .onsale {background-color: ' . esc_attr( $bg ) . '}';
		}

		if ( ( $color = Helper::get_option( 'shop_badges_sale_color' ) ) && $color != '' ) {
			$static_css .= '.woocommerce-badges .onsale {color: ' . esc_attr( $color ) . '}';
		}

		if ( ( $bg = Helper::get_option( 'shop_badges_new_bg' ) ) && $bg != '' ) {
			$static_css .= '.woocommerce-badges .new {background-color: ' . esc_attr( $bg ) . '}';
		}

		if ( ( $color = Helper::get_option( 'shop_badges_new_color' ) ) && $color != '' ) {
			$static_css .= '.woocommerce-badges .new {color: ' . esc_attr( $color ) . '}';
		}

		if ( ( $bg = Helper::get_option( 'shop_badges_featured_bg' ) ) && $bg != '' ) {
			$static_css .= '.woocommerce-badges .featured {background-color: ' . esc_attr( $bg ) . '}';
		}

		if ( ( $color = Helper::get_option( 'shop_badges_featured_color' ) ) && $color != '' ) {
			$static_css .= '.woocommerce-badges .featured {color: ' . esc_attr( $color ) . '}';
		}

		if ( ( $bg = Helper::get_option( 'shop_badges_soldout_bg' ) ) && $bg != '' ) {
			$static_css .= '.woocommerce-badges .sold-out {background-color: ' . esc_attr( $bg ) . '}';
		}

		if ( ( $color = Helper::get_option( 'shop_badges_soldout_color' ) ) && $color != '' ) {
			$static_css .= '.woocommerce-badges .sold-out {color: ' . esc_attr( $color ) . '}';
		}

		return $static_css;
	}

	/**
	 * Get CSS code of settings for single product.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function single_product_page_static_css() {
		$static_css = '';
		if ( ! class_exists( 'WooCommerce' ) ) {
			return $static_css;
		}

		if ( in_array( Helper::get_option('product_layout'), array('v3', 'v5'))) {
			$background = get_post_meta( Helper::get_post_ID(), 'background_color', true );
			if ( $background ) {
				$static_css .= '.woocommerce div.product { background-color: ' . esc_attr( $background ) . ' }';
			}
		}
		if ( Helper::get_option('product_layout') == 'v6') {
			if ( ( $bg = Helper::get_option( 'product_content_bg_color' ) ) && $bg != '' ) {
				$static_css .= '.single-product div.product.layout-v6 .summary {background-color: ' . esc_attr( $bg ) . '}';
			}
		}

		if ( ( $rv_bg = Helper::get_option( 'recently_viewed_bg_color' ) ) && $rv_bg != '' ) {
			$static_css .= '.durotan-recently-viewed-product { --durotan-recently-viewed-background-color: ' . esc_attr( $rv_bg ) . ' }';
		}

		return $static_css;
	}
}
