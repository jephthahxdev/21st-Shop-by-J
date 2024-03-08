<?php
/**
 * Style functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Style initial
 *
 * @since 1.0.0
 */
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
		add_action( 'durotan_after_enqueue_style', array( $this, 'add_static_css' ) );
	}

	/**
	 * Get get style data
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function add_static_css() {
		$parse_css =  $this->color_schema();
		$parse_css .= $this->header_static_css();
		$parse_css .= $this->page_static_css();
		$parse_css .= $this->blog_static_css();
		$parse_css .= $this->footer_static_css();

		wp_add_inline_style( 'durotan', apply_filters( 'durotan_inline_style', $parse_css ) );
	}

	/**
	 * Get color schema
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	protected function color_schema() {
		$static_css = '';
		/* Color Scheme */
		$color_scheme_option = Helper::get_option( 'color_scheme' );
		if ( ! empty( $color_scheme_option ) || intval(Helper::get_option( 'custom_color_scheme' ))) {
			if ( intval(Helper::get_option( 'custom_color_scheme' )) && ! empty( Helper::get_option( 'custom_color' ) )  ) {
				$color_scheme_option = Helper::get_option( 'custom_color' );
			}

			$static_css .= ':root {--durotan-background-color-primary: '. $color_scheme_option .'; --durotan-color-primary: '. $color_scheme_option .';--durotan-border-color-primary: '. $color_scheme_option .';}';
		}
		return $static_css;
	}

	/**
	 * Get header style data
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	protected function header_static_css() {
		$static_css = '';
		// Header Campaign Bar
		if ( intval( Helper::get_option( 'campaign_bar' ) ) ) {
			if ( Helper::get_option( 'campaign_bar_content_color_custom' ) ) {
				$static_css .= '.durotan-campaign-bar { color: ' . esc_attr( Helper::get_option( 'campaign_bar_content_color_custom' ) ) . ';
												fill: ' . esc_attr( Helper::get_option( 'campaign_bar_content_color_custom' ) ) . ' }';
			}

			if ( Helper::get_option( 'campaign_bar_height' ) != 45 ) {
				$static_css .= '.durotan-campaign-bar { height: ' . intval( Helper::get_option( 'campaign_bar_height' ) ) . 'px; }';
			}

			if ( Helper::get_option( 'campaign_bar_background_color' ) ) {
				$static_css .= '.durotan-campaign-bar { background-color: ' . esc_attr( Helper::get_option( 'campaign_bar_background_color' ) ) . ' }';
			}
		}

		// Header Main Height
		if ( Helper::get_option( 'header_main_height' ) != 156 ) {
			$static_css .= '.header__main { height: ' . intval( Helper::get_option( 'header_main_height' ) ) . 'px; }';
		}

		// Header Main Sticky Height
		if ( Helper::get_option( 'sticky_header_main_height' ) != 156 && Helper::get_option('header_layout') != 'v7' ) {
			$static_css .= '.header-sticky .site-header.minimized .header__main{ height: ' . intval( Helper::get_option( 'sticky_header_main_height' ) ) . 'px; }';
		}

		// Header Bottom Height
		if ( Helper::get_option( 'header_bottom_height' ) != 80 ) {
			$static_css .= '.header__bottom { height: ' . intval( Helper::get_option( 'header_bottom_height' ) ) . 'px; }';
		}

		// Header Bottom Sticky Height
		if ( Helper::get_option( 'sticky_header_bottom_height' ) != 80 ) {
			$static_css .= '.header-sticky .site-header.minimized .header__bottom{ height: ' . intval( Helper::get_option( 'sticky_header_bottom_height' ) ) . 'px; }';
		}

		// Mobile Header Height
		if ( Helper::get_option( 'mobile_header_height' ) != 60 ) {
			$static_css .= '.header__mobile { height: ' . intval( Helper::get_option( 'mobile_header_height' ) ) . 'px; }';
		}

		// Header Background Main
		if ( intval( Helper::get_option( 'header_main_background' ) ) ) {
			if ( Helper::get_option( 'header_main_background_color' ) ) {
				$static_css .= '.header__main, .header__mobile { background-color: ' . esc_attr( Helper::get_option( 'header_main_background_color' ) ) . ';
												--durotan-header-background-color: ' . esc_attr( Helper::get_option( 'header_main_background_color' ) ) . '; }';
			}

			if ( Helper::get_option( 'header_main_background_text_color' ) ) {
				$static_css .= '.header__main { --durotan-header-text-color: ' . esc_attr( Helper::get_option( 'header_main_background_text_color' ) ) . ' }';
			}

			if ( Helper::get_option( 'header_main_background_text_color_hover' ) ) {
				$static_css .= '.header__main { --durotan-header-text-hover-color: ' . esc_attr( Helper::get_option( 'header_main_background_text_color_hover' ) ) . ' }';
			}
		}

		// Header Background Bottom
		if ( intval( Helper::get_option( 'header_bottom_background' ) ) ) {
			if ( Helper::get_option( 'header_bottom_background_color' ) ) {
				$static_css .= '.header__bottom { background-color: ' . esc_attr( Helper::get_option( 'header_bottom_background_color' ) ) . ';
																--durotan-header-background-color: ' . esc_attr( Helper::get_option( 'header_bottom_background_color' ) ) . '; }';
			}

			if ( Helper::get_option( 'header_bottom_background_text_color' ) ) {
				$static_css .= '.header__bottom { --durotan-header-text-color: ' . esc_attr( Helper::get_option( 'header_bottom_background_text_color' ) ) . '}';
			}

			if ( Helper::get_option( 'header_bottom_background_text_color_hover' ) ) {
				$static_css .= '.header__bottom { --durotan-header-text-hover-color: ' . esc_attr( Helper::get_option( 'header_bottom_background_text_color_hover' ) ) . ' }';
			}
		}

		// Header Border Bottom Color
		if ( $border_color = get_post_meta( \Durotan\Helper::get_post_ID(), 'durotan_header_border_color', true ) ) {
			$static_css .= '.site-header__border { --durotan-header-border-color: ' . esc_attr( $border_color ) . '}';
		}


		// Header Menu
		if ( intval( Helper::get_option( 'header_menu_custom_color' ) ) ) {
			if ( Helper::get_option( 'header_menu_text_color' ) ) {
				$static_css .= '.main-navigation ul.menu > li > a { color: ' . esc_attr( Helper::get_option( 'header_menu_text_color' ) ) . '}';
			}

			if ( Helper::get_option( 'header_menu_hover_color' ) ) {
				$static_css .= '.main-navigation ul.menu > li:hover > a,
								.main-navigation ul.menu > li.current-menu-item > a { --durotan-header-text-hover: ' . esc_attr( Helper::get_option( 'header_menu_hover_color' ) ) . ' }';

				$static_css .= '.header-v1 .durotan-menu-item__dot { --durotan-color-primary: ' . esc_attr( Helper::get_option( 'header_menu_hover_color' ) ) . ' }';
			}
		}

		// Cart
		if ( intval( Helper::get_option( 'header_cart_custom_color' ) ) ) {
			if ( Helper::get_option( 'header_cart_custom_color_counter' ) ) {
				$static_css .= '.header-cart__counter { background-color: '. esc_attr( Helper::get_option( 'header_cart_custom_color_counter' ) ) . ' }';
			}

			if ( Helper::get_option( 'header_cart_custom_color_total_price' ) ) {
				$static_css .= '.header-cart__total-price { color: '. esc_attr( Helper::get_option( 'header_cart_custom_color_total_price' ) ) . ' }';
			}
		}

		// Wishlist
		if ( intval( Helper::get_option( 'header_wishlist_custom_color' ) ) ) {
			if ( Helper::get_option( 'header_wishlist_custom_color_counter' ) ) {
				$static_css .= '.header-wishlist__counter { background-color: '. esc_attr( Helper::get_option( 'header_wishlist_custom_color_counter' ) ) . ' }';
			}
		}

		// Compare
		if ( intval( Helper::get_option( 'header_compare_custom_color' ) ) ) {
			if ( Helper::get_option( 'header_compare_custom_color_counter' ) ) {
				$static_css .= '.header-compare__counter { background-color: '. esc_attr( Helper::get_option( 'header_compare_custom_color_counter' ) ) . ' }';
			}
		}

		// Position Sticky
		if ( intval( Helper::get_option( 'header_sticky' ) ) ) {
			$sticky_height    = Helper::get_option( 'sticky_header_main_height' );
			$header_sticky_el = (array) Helper::get_option( 'header_sticky_el' );


			if ( in_array( 'header_bottom', $header_sticky_el ) ) {
				$sticky_height = Helper::get_option( 'sticky_header_bottom_height' );
			}

			if ( in_array( 'header_main', $header_sticky_el ) && in_array( 'header_bottom', $header_sticky_el ) ) {
				$sticky_height = Helper::get_option( 'sticky_header_main_height' ) + Helper::get_option( 'sticky_header_bottom_height' );
			}

			$static_css .= '.header-sticky.woocommerce-cart .cart-collaterals { top: ' . ( $sticky_height + 50 ) . 'px; }';
			$static_css .= '.header-sticky.woocommerce-cart.admin-bar .cart-collaterals { top: ' . ( $sticky_height + 82 ) . 'px; }';

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
	protected function page_static_css() {
		$static_css = '';

		// Page content spacings
		if ( is_page() ) {

			if ( $top = get_post_meta( \Durotan\Helper::get_post_ID(), 'durotan_content_top_padding', true ) ) {
				$static_css .= '.site-content.custom-top-spacing { padding-top: ' . $top . 'px; }';
			}

			if ( $bottom = get_post_meta( \Durotan\Helper::get_post_ID(), 'durotan_content_bottom_padding', true ) ) {
				$static_css .= '.site-content.custom-bottom-spacing{ padding-bottom: ' . $bottom . 'px; }';
			}

			if ( get_post_meta( \Durotan\Helper::get_post_ID(), 'durotan_hide_footer', true ) ) {
				$static_css .= 'footer { display: none !important; }';
			}
		}

		return $static_css;
	}

	/**
	 * Get header style data
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	protected function blog_static_css() {
		$static_css = '';

		// Featured blog
		if ( Helper::get_option( 'featured_content_height' ) != 670 ) {
			$static_css .= '.durotan-featured { height: ' . intval( Helper::get_option( 'featured_content_height' ) ) . 'px; }';
		}

		return $static_css;
	}

	/**
	 * Get footer style data
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	protected function footer_static_css() {
		$static_css = '';

		if( Helper::get_option( 'footer_parallax' ) ) {
			if ( Helper::get_option( 'footer_parallax_bg_color' ) ) {
				$static_css .= 'body.footer-has-parallax { --durotan-site-content-background-color: ' . esc_attr( Helper::get_option( 'footer_parallax_bg_color' ) ) . ' }';
			}
		}

		if( Helper::get_option( 'footer_background_scheme' ) ) {
			if ( Helper::get_option( 'footer_bg_color' ) ) {
				$static_css .= '.site-footer { --durotan-footer-background-color: ' . esc_attr( Helper::get_option( 'footer_bg_color' ) ) . ' }';
			}

			if ( Helper::get_option( 'footer_bg_heading_color' ) ) {
				$static_css .= '.site-footer { --durotan-footer-heading-color: ' . esc_attr( Helper::get_option( 'footer_bg_heading_color' ) ) . ' }';
			}

			if ( Helper::get_option( 'footer_bg_text_color' ) ) {
				$static_css .= '.site-footer { --durotan-footer-text-color: ' . esc_attr( Helper::get_option( 'footer_bg_text_color' ) ) . ' }';
			}

			if ( Helper::get_option( 'footer_bg_text_color_hover' ) ) {
				$static_css .= '.site-footer { --durotan-footer-text-color-hover: ' . esc_attr( Helper::get_option( 'footer_bg_text_color_hover' ) ) . ' }';
			}

			if ( Helper::get_option( 'footer_bg_field' ) ) {
				$static_css .= '.site-footer { --durotan-footer-field-background-color: ' . esc_attr( Helper::get_option( 'footer_bg_field' ) ) . ' }';
			}

			if ( Helper::get_option( 'footer_bg_field_text_placeholder' ) ) {
				$static_css .= '.site-footer { --durotan-footer-field-text-placeholder-color: ' . esc_attr( Helper::get_option( 'footer_bg_field_text_placeholder' ) ) . ' }';
			}

			if ( Helper::get_option( 'footer_bg_field_border_color' ) ) {
				$static_css .= '.site-footer { --durotan-footer-field-border-color: ' . esc_attr( Helper::get_option( 'footer_bg_field_border_color' ) ) . ' }';
			}

			if ( Helper::get_option( 'footer_bg_button' ) ) {
				$static_css .= '.site-footer { --durotan-footer-button-background-color: ' . esc_attr( Helper::get_option( 'footer_bg_button' ) ) . ' }';
			}

			if ( Helper::get_option( 'footer_bg_button_hover' ) ) {
				$static_css .= '.site-footer { --durotan-footer-button-background-color-hover: ' . esc_attr( Helper::get_option( 'footer_bg_button_hover' ) ) . ' }';
			}

			if ( Helper::get_option( 'footer_bg_button_text' ) ) {
				$static_css .= '.site-footer { --durotan-footer-button-text-color: ' . esc_attr( Helper::get_option( 'footer_bg_button_text' ) ) . ' }';
			}
		}

		return $static_css;
	}
}
