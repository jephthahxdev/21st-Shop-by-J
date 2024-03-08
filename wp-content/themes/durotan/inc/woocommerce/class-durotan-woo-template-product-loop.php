<?php
/**
 * Product Loop template hooks.
 *
 * @package Durotan
 */

namespace Durotan\WooCommerce\Template;

use Durotan\Helper, Durotan\WooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of Product Loop
 */
class Product_Loop {
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
		// Add more class to loop start.
		add_filter( 'woocommerce_product_loop_start', array( $this, 'loop_start' ), 5 );

		// Product inner wrapper
		add_action( 'woocommerce_before_shop_loop_item', array( $this, 'product_wrapper_open' ), 10 );
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'product_wrapper_close' ), 1000 );

		// Remove wrapper link
		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

		// Change product thumbnail.
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail' );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_loop_thumbnail' ), 1 );

		// Add attribute
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

		// Remove add to cart
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'product_variable_add_to_cart_text' ) );

		// Product buttons
		add_filter( 'yith_wcwl_show_add_to_wishlist', '__return_empty_string' );
		add_filter( 'woocommerce_better_compare_show_add_to_compare_button', '__return_empty_string' );

		// Change the product title.
		remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title' );
		add_action( 'woocommerce_shop_loop_item_title', array( WooCommerce\Helper::instance(), 'product_loop_title' ) );

		// Change add to cart link
		add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'add_to_cart_link' ), 20, 3 );

		// Add div class woocommerce detail
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'open_woocommerce_details' ) );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'close_woocommerce_details' ), 100 );

		// Product Loop Layout
		$this->product_loop_layout();

		// Ajax and script data
		add_action( 'wc_ajax_durotan_product_loop_form', array( $this, 'product_loop_form_ajax' ) );

		add_filter( 'durotan_wp_script_data', array( $this, 'loop_script_data' ) );
	}

	/**
	 * Add class to loop start.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function loop_start( $html ) {
		$html = '';

		$classes = array(
			'products'
		);

		$classes[] = 'product-loop-layout-' . intval( Helper::get_option( 'product_loop_layout' ) );

		$classes[] = 'columns-' . wc_get_loop_prop( 'columns' );

		if ( $mobile_pl_col = intval( Helper::get_option( 'mobile_landscape_product_columns' ) ) ) {
			$classes[] = 'mobile-pl-col-' . $mobile_pl_col;
		}

		if ( $mobile_pp_col = intval( Helper::get_option( 'mobile_portrait_product_columns' ) ) ) {
			$classes[] = 'mobile-pp-col-' . $mobile_pp_col;
		}

		if ( intval( Helper::get_option( 'mobile_product_featured_icons' ) ) ) {
			$classes[] = 'mobile-show-featured-icons';
		}

		$html .= '<ul class="' . esc_attr( implode( ' ', $classes ) ) . '">';

		return $html;
	}


	/**
	 * Product loop layout
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_loop_layout() {
		$product_loop_featured_icons = (array) Helper::get_option( 'product_loop_featured_icons' );
		$attributes = (array) Helper::get_option( 'product_loop_attributes' );

		switch ( Helper::get_option( 'product_loop_layout' ) ) {

			case 1:
				add_action( 'durotan_product_loop_thumbnail', array( $this, 'product_buttons_layout_default' ) );

				// Add open box content
				add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'open_woocommerce_details_content' ) );

				add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'display_variation_dropdown' ) );
				add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_attribute_default') );

				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'product_short_description' ) );

				// Add close box content
				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'close_woocommerce_details_content' ) );

				// Add new button add to cart
				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'atc_button_open' ) );
					add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart' );
				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'atc_button_close' ) );
				break;

			// Icon & Add to Cart Button
			case 2:
				if ( $product_loop_featured_icons ) {
					add_action( 'durotan_product_loop_thumbnail', array( $this, 'product_loop_buttons_open' ) );

						if ( in_array( 'cart', $product_loop_featured_icons ) ) {
							add_action( 'durotan_product_loop_thumbnail', 'woocommerce_template_loop_add_to_cart' );
						}

						if ( in_array( 'quickview', $product_loop_featured_icons ) ) {
							add_action( 'durotan_product_loop_thumbnail', array( WooCommerce\Helper::instance(), 'quick_view_button' ) );
						}

						if ( in_array( 'wishlist', $product_loop_featured_icons ) ) {
							add_action( 'durotan_product_loop_thumbnail', array( WooCommerce\Helper::instance(), 'wishlist_button' ) );
						}

						if ( in_array( 'compare', $product_loop_featured_icons ) ) {
							add_action( 'durotan_product_loop_thumbnail', array( WooCommerce\Helper::instance(), 'compare_button' ) );
						}

					add_action( 'durotan_product_loop_thumbnail', array( $this, 'product_loop_buttons_close' ) );
				}

				// Add box content
				add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'open_woocommerce_details_content' ) );

				add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'display_variation_dropdown' ) );

				add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_attribute_default') );

				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'product_short_description' ) );

				// Add close box content
				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'close_woocommerce_details_content' ) );

				// Add new button add to cart
				if ( in_array( 'cart', $product_loop_featured_icons ) ) {
					add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'atc_button_open' ) );
						add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart' );
					add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'atc_button_close' ) );
				}

				break;

			// Standard Button & Icon Text
			case 3:
				$product_loop_featured_icons = (array) Helper::get_option( 'product_loop_featured_icons_2' );
				// Add box content
				add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'open_woocommerce_details_content' ) );

				if ( in_array( 'wishlist', $product_loop_featured_icons ) || in_array( 'quickview', $product_loop_featured_icons ) ) {
					add_action( 'durotan_product_loop_thumbnail', array( $this, 'product_loop_buttons_open' ) );

					if ( in_array( 'wishlist', $product_loop_featured_icons ) ) {
						add_action( 'durotan_product_loop_thumbnail', array( WooCommerce\Helper::instance(), 'wishlist_button' ) );
					}

					if ( in_array( 'quickview', $product_loop_featured_icons ) ) {
						add_action( 'durotan_product_loop_thumbnail', array( WooCommerce\Helper::instance(), 'quick_view_button' ) );
					}

					add_action( 'durotan_product_loop_thumbnail', array( $this, 'product_loop_buttons_close' ) );
				}

				// Add variation
				add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'display_variation_dropdown' ) );

				if ( in_array( 'rating', $attributes ) ) {
					add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_rating' );
				}

				if ( in_array( 'taxonomy', $attributes ) ) {
					add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_loop_category') );
				}

				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'product_short_description' ) );

				// Add close box content
				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'close_woocommerce_details_content' ) );

				if ( in_array( 'cart', $product_loop_featured_icons ) ) {
					add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'atc_button_open' ) );
						add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart' );
					add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'atc_button_close' ) );
				}

				break;

			// Standard Button & Icon
			case 4:
				if ( in_array( 'quickview', $product_loop_featured_icons ) || in_array( 'compare', $product_loop_featured_icons ) ) {
					add_action( 'durotan_product_loop_thumbnail', array( $this, 'product_loop_buttons_open' ) );

					if ( in_array( 'quickview', $product_loop_featured_icons ) ) {
						add_action( 'durotan_product_loop_thumbnail', array( WooCommerce\Helper::instance(), 'quick_view_button' ) );
					}

					if ( in_array( 'compare', $product_loop_featured_icons ) ) {
						add_action( 'durotan_product_loop_thumbnail', array( WooCommerce\Helper::instance(), 'compare_button' ) );
					}

					add_action( 'durotan_product_loop_thumbnail', array( $this, 'product_loop_buttons_close' ) );
				}

				// Add box content
				add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'open_woocommerce_details_content' ) );

				if( in_array( 'taxonomy', $attributes ) || in_array( 'wishlist', $product_loop_featured_icons ) ) {
					add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_loop_meta_open') );
						if ( in_array( 'taxonomy', $attributes ) ) {
							add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_loop_category') );
						}

						if ( in_array( 'wishlist', $product_loop_featured_icons ) ) {
							add_action( 'woocommerce_before_shop_loop_item_title', array( WooCommerce\Helper::instance(), 'wishlist_button' ) );
						}
					add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_loop_meta_close') );
				}

				if ( in_array( 'rating', $attributes ) ) {
					add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_rating' );
				}

				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'product_short_description' ) );

				// Add close box content
				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'close_woocommerce_details_content' ) );

				// Add open box content bottom
				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'open_woocommerce_details_bottom' ) );

				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'display_variation_dropdown' ) );

				// Add close box content bottom
				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'close_woocommerce_details_bottom' ) );

				if ( in_array( 'cart', $product_loop_featured_icons ) ) {
					add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'atc_button_open' ) );
						add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart' );
					add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'atc_button_close' ) );
				}

				break;

			// Standard Button & Icon 2
			case 5:
				if ( in_array( 'wishlist', $product_loop_featured_icons ) || in_array( 'quickview', $product_loop_featured_icons ) || in_array( 'compare', $product_loop_featured_icons ) ) {
					add_action( 'durotan_product_loop_thumbnail', array( $this, 'product_loop_buttons_open' ) );

						if ( in_array( 'wishlist', $product_loop_featured_icons ) ) {
							add_action( 'durotan_product_loop_thumbnail', array( WooCommerce\Helper::instance(), 'wishlist_button' ) );
						}

						if ( in_array( 'quickview', $product_loop_featured_icons ) ) {
							add_action( 'durotan_product_loop_thumbnail', array( WooCommerce\Helper::instance(), 'quick_view_button' ) );
						}

						if ( in_array( 'compare', $product_loop_featured_icons ) ) {
							add_action( 'durotan_product_loop_thumbnail', array( WooCommerce\Helper::instance(), 'compare_button' ) );
						}

					add_action( 'durotan_product_loop_thumbnail', array( $this, 'product_loop_buttons_close' ) );
				}

				// Add box content
				add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'display_variation_dropdown' ) );

				add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'open_woocommerce_details_content' ) );

				add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_attribute_default') );

				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'product_short_description' ) );

				// Add close box content
				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'close_woocommerce_details_content' ) );

				if ( in_array( 'cart', $product_loop_featured_icons ) ) {
					add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'atc_button_open' ) );
						add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart' );
					add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'atc_button_close' ) );
				}

				break;

			// Icon và Button Hover
			case 6:
				if ( in_array( 'cart', $product_loop_featured_icons ) || in_array( 'quickview', $product_loop_featured_icons ) ) {
					add_action( 'durotan_product_loop_thumbnail', array( $this, 'product_loop_buttons_open' ) );
						if ( in_array( 'cart', $product_loop_featured_icons ) ) {
							add_action( 'durotan_product_loop_thumbnail', 'woocommerce_template_loop_add_to_cart' );
						}

						if ( in_array( 'quickview', $product_loop_featured_icons ) ) {
							add_action( 'durotan_product_loop_thumbnail', array( WooCommerce\Helper::instance(), 'quick_view_button' ) );
						}

					add_action( 'durotan_product_loop_thumbnail', array( $this, 'product_loop_buttons_close' ) );
				}

				if ( in_array( 'wishlist', $product_loop_featured_icons ) ) {
					add_action( 'durotan_product_loop_thumbnail', array( WooCommerce\Helper::instance(), 'wishlist_button' ) );
				}

				if ( in_array( 'compare', $product_loop_featured_icons ) ) {
					add_action( 'durotan_product_loop_thumbnail', array( WooCommerce\Helper::instance(), 'compare_button' ) );
				}

				// Add box content
				add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'open_woocommerce_details_content' ) );

				add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_attribute_default') );

				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'product_short_description' ) );

				// Add close box content
				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'close_woocommerce_details_content' ) );
				if ( in_array( 'cart', $product_loop_featured_icons ) ) {
					add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'atc_button_open' ) );
						add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart' );
					add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'atc_button_close' ) );
				}

				break;

			// Quick View button
			case 7:
				add_action( 'durotan_product_loop_thumbnail', array( $this, 'product_loop_buttons_open' ) );

					add_action( 'durotan_product_loop_thumbnail', array( WooCommerce\Helper::instance(), 'quick_view_button' ) );

				add_action( 'durotan_product_loop_thumbnail', array( $this, 'product_loop_buttons_close' ) );

				// Add box content
				add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'open_woocommerce_details_content' ) );

				add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_attribute_default') );

				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'product_short_description' ) );

				// Add close box content
				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'close_woocommerce_details_content' ) );

				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'atc_button_open' ) );
					add_action( 'woocommerce_after_shop_loop_item_title', array( WooCommerce\Helper::instance(), 'quick_view_button' ) );
				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'atc_button_close' ) );

				break;

			default:
				add_action( 'durotan_product_loop_thumbnail', array( $this, 'product_buttons_layout_default' ) );
				add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'display_variation_dropdown' ) );
				add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_attribute_default') );

				break;
		}
	}

	public function product_short_description() {
		$number = apply_filters( 'durotan_woocommerce_short_description_number', 25 );
		echo '<div class="woocommerce-short-description">' . wp_trim_words( get_the_excerpt(), $number ) . '</div>';
	}

	public function open_woocommerce_details_content() {
		echo '<div class="woocommerce-details-content">';
	}

	public function close_woocommerce_details_content() {
		echo '</div>';
	}

	public function open_woocommerce_details_bottom() {
		echo '<div class="woocommerce-details-bottom">';
	}

	public function close_woocommerce_details_bottom() {
		echo '</div>';
	}

	/**
	 * Open product wrapper.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_wrapper_open() {
		echo '<div class="product-inner">';
	}

	/**
	 * Close product wrapper.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Open product Loop buttons.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_loop_buttons_open() {
		echo '<div class="product-loop__buttons product-loop__buttons--' . esc_attr( Helper::get_option( 'product_loop_layout' ) ) . '">';
	}

	/**
	 * Close product Loop buttons.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_loop_buttons_close() {
		echo '</div>';
	}

	/**
	 * Open product loop meta.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_loop_meta_open() {
		echo '<div class="product-loop__meta">';
	}

	/**
	 * Close product loop meta.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_loop_meta_close() {
		echo '</div>';
	}

	/**
	 * Open ATC button.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function atc_button_open() {
		echo '<div class="product-atc-button">';
	}

	/**
	 * Close ATC button.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function atc_button_close() {
		echo '</div>';
	}

	/**
	 * Product thumbnail wrapper.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_loop_thumbnail() {
		global $product;

		if ( Helper::get_option( 'product_loop_hover_type' ) === 'slider' ) {
			$image_ids  = $product->get_gallery_image_ids();
			$image_size = apply_filters( 'single_product_archive_thumbnail_size', 'woocommerce_thumbnail' );

			if ( $image_ids ) {
				echo '<div class="product-thumbnail product-thumbnails--slider swiper-container">';
				echo '<div class="swiper-wrapper">';
			} else {
				echo '<div class="product-thumbnail">';
			}

				woocommerce_template_loop_product_link_open();
				woocommerce_template_loop_product_thumbnail();
				echo '<div class="product-loop__variation--image"></div>';
				woocommerce_template_loop_product_link_close();

				foreach ( $image_ids as $image_id ) {
					$src = wp_get_attachment_image_src( $image_id, $image_size );

					if ( ! $src ) {
						continue;
					}

					woocommerce_template_loop_product_link_open();

					printf(
						'<img src="%s" data-src="%s" width="%s" height="%s" alt="%s" class="swiper-lazy">',
						esc_url( $src[0] ),
						esc_url( $src[0] ),
						esc_attr( $src[1] ),
						esc_attr( $src[2] ),
						esc_attr( $product->get_title() )
					);
					echo '<div class="product-loop__variation--image"></div>';
					woocommerce_template_loop_product_link_close();
				}
				if ( $image_ids ) {
					echo '</div>';
					echo \Durotan\Icon::get_svg( 'chevron-left', 'durotan-product-loop-swiper-prev durotan-swiper-button' );
					echo \Durotan\Icon::get_svg( 'chevron-right', 'durotan-product-loop-swiper-next durotan-swiper-button' );
					echo '<div class="swiper-pagination"></div>';
				}
				do_action( 'durotan_product_loop_thumbnail' );
			echo '</div>';
		} elseif ( Helper::get_option( 'product_loop_hover_type' ) === 'fadein' ) {
			$image_ids = $product->get_gallery_image_ids();

				if ( ! empty( $image_ids ) ) {
					echo '<div class="product-thumbnail">';
					echo '<div class="product-thumbnails--hover">';
				} else {
					echo '<div class="product-thumbnail">';
				}

				woocommerce_template_loop_product_link_open();
				woocommerce_template_loop_product_thumbnail();

				if ( ! empty( $image_ids ) ) {
					$image_size = apply_filters( 'single_product_archive_thumbnail_size', 'woocommerce_thumbnail' );
					echo wp_get_attachment_image( $image_ids[0], $image_size, false, array( 'class' => 'attachment-woocommerce_thumbnail size-woocommerce_thumbnail hover-image' ) );
				}
				echo '<div class="product-loop__variation--image"></div>';
				woocommerce_template_loop_product_link_close();
				if ( ! empty( $image_ids ) ) {
					echo '</div>';
				}
				do_action( 'durotan_product_loop_thumbnail' );
				echo '</div>';
		} else {
			echo '<div class="product-thumbnail">';
				woocommerce_template_loop_product_link_open();
				woocommerce_template_loop_product_thumbnail();
				echo '<div class="product-loop__variation--image"></div>';
				woocommerce_template_loop_product_link_close();
				do_action( 'durotan_product_loop_thumbnail' );
			echo '</div>';
		}
	}

	/**
	 * Product loop layout
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_buttons_layout_default() {
		global $product;
		$product_loop_featured_icons = (array) Helper::get_option( 'product_loop_featured_icons' );
		$count = count( $product_loop_featured_icons )-1;

		if ( $product_loop_featured_icons ) {
			echo '<div class="product-loop__buttons product-loop__buttons--' . esc_attr( Helper::get_option( 'product_loop_layout' ) ) . '">';
					if ( in_array( 'cart', $product_loop_featured_icons ) ) {
						woocommerce_template_loop_add_to_cart();
					}
					echo '<div class="loop-buttons buttons-' .$count. '">';
						if ( in_array( 'quickview', $product_loop_featured_icons ) ) {
							WooCommerce\Helper::quick_view_button();
						}
						if ( in_array( 'wishlist', $product_loop_featured_icons ) ) {
							WooCommerce\Helper::wishlist_button();
						}
						if ( in_array( 'compare', $product_loop_featured_icons ) ) {
							WooCommerce\Helper::compare_button();
						}
					echo '</div>';
			echo '</div>';
		}
	}

	/**
	 * Product add to cart
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function product_attribute_default() {
		$attributes = (array) Helper::get_option( 'product_loop_attributes' );

		if ( in_array( 'taxonomy', $attributes ) ) {
			echo '<div class="product-loop__meta">';
				$this->product_loop_category();
			echo '</div>';
		}

		if ( in_array( 'rating', $attributes ) ) {
			woocommerce_template_loop_rating();
		}
	}

	/**
	 * Category name
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_loop_category() {
		$taxonomy = Helper::get_option( 'product_loop_taxonomy' );
		\Durotan\WooCommerce\Helper::product_taxonomy( $taxonomy );
	}

	/**
	 * Product add to cart
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function add_to_cart_link( $html, $product, $args ) {
		$classes = '';
		if ( intval( Helper::get_option( 'product_loop_layout' ) ) == 1 ) {
			$classes = 'durotan-loop_button--cart-text';
		}
		if ( $product->get_type() === 'external' ) {
			return sprintf(
				'<a href="%s" data-quantity="%s" class="%s durotan-loop_button--external" %s>%s</a>',
				esc_url( $product->add_to_cart_url() ),
				esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
				esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
				isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
				Helper::get_option( 'product_loop_layout' ) == '1' ? esc_html( $product->add_to_cart_text() ) : esc_html( 'Quick Shop', 'durotan' )
			);
		} else {
			return sprintf(
				'<a href="%s" data-quantity="%s" class="durotan-loop_button durotan-loop_button--cart %s %s" %s data-text="%s" data-title="%s" >%s<span class="add-to-cart-text">%s</span></a>',
				esc_url( $product->add_to_cart_url() ),
				esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
				$classes,
				esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
				isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
				esc_html( $product->add_to_cart_text() ),
				esc_html( $product->get_title() ),
				intval( Helper::get_option( 'product_loop_layout' ) ) == 4 ? \Durotan\Icon::get_svg( 'plus', 'add-to-cart-icon', 'shop' ) : \Durotan\Icon::get_svg( 'cart', 'add-to-cart-icon', 'shop' ),
				esc_html( $product->add_to_cart_text() )
			);
		}
	}

	/**
	 * Variable add to cart loop
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function product_variable_add_to_cart_text( $text ) {
		if( Helper::get_option( 'product_loop_attributes_product_variable') ) {
			return $text;
		}

		global $product;

		if ( ! $product->is_type( 'variable' ) ) {
			return $text;
		}

		if ( $product->is_purchasable() ) {
			$text = esc_html__( 'Add to cart', 'durotan' );

		}

		return $text;
	}

	/**
	 * Variation loop
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function display_variation_dropdown() {
		if( Helper::get_option( 'product_loop_attributes_product_variable') ) {
			return;
		}

		global $product;

		if ( ! $product->is_type( 'variable' ) ) {
			return;
		}

		// Get Available variations?
		$get_variations = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );

		// Load the template.
		wc_get_template(
			'loop/add-to-cart-variable.php',
			array(
				'available_variations' => $get_variations ? $product->get_available_variations() : false,
				'attributes'           => $product->get_variation_attributes(),
				'selected_attributes'  => $product->get_default_attributes(),
			)
		);
	}

	/**
	 * Open woocommerce details
	 */
	public function open_woocommerce_details() {
		echo '<div class="woocommerce-details">';
	}

	/**
	 * Close woocommerce details
	 */
	public function close_woocommerce_details() {
		echo '</div>';
	}

	/**
	 * Product loop form AJAX
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_loop_form_ajax() {
		if ( empty( $_POST['variation_id'] ) || empty( $_POST['product_id'] ) ) {
			exit;
		}

		$variation = new \WC_Product_Variation( $_POST['variation_id'] );

		$output = array(
			'variation' 		=> $variation->get_image(),
			'price_variation'	=> $variation->get_price_html()
		);

		wp_send_json_success( array ( $output ) );
		exit;
	}

	/**
	 * Catalog script data.
	 *
	 * @since 1.0.0
	 *
	 * @param $data
	 *
	 * @return array
	 */
	public function loop_script_data( $data ) {

		if ( in_array( \Durotan\WooCommerce\Helper::get_product_loop_layout(), array( '3' ) ) ) {
			$data['product_loop_layout'] = \Durotan\WooCommerce\Helper::get_product_loop_layout();
		}

		return $data;
	}

}
