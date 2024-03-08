<?php
/**
 * General template hooks.
 *
 * @package Durotan
 */

namespace Durotan\WooCommerce\Template;

use Durotan\Helper;
use WooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of general template.
 */
class General {
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
		// Disable the default WooCommerce stylesheet.
		add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 20 );

		// Update counter via ajax.
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_link_fragment' ) );

		// Edit breadcrum.
		add_filter( 'woocommerce_breadcrumb_defaults', array( $this, 'breadcrumb_args' ) );

		// Update counter wishlist via ajax.
		add_action( 'wc_ajax_update_wishlist_count', array( $this, 'update_wishlist_count' ) );

		// Change total price in mini cart default
		remove_action( 'woocommerce_widget_shopping_cart_total', 'woocommerce_widget_shopping_cart_subtotal' );
		add_action( 'woocommerce_widget_shopping_cart_total', array( $this, 'durotan_widget_shopping_cart_subtotal' ) );

		add_filter( 'woocommerce_cart_subtotal', array( $this, 'durotan_space_cart_subtotal') );
		add_filter( 'woocommerce_cart_product_price', array( $this, 'durotan_space_cart_product_price') );

		// Change star rating HTML.
		add_filter( 'woocommerce_get_star_rating_html', array( $this, 'star_rating_html' ), 10, 3 );

		// Change availability text in single product
		add_filter( 'woocommerce_get_availability_text', array( $this, 'get_product_availability_text' ), 20, 2 );

		// Change template qty
		add_action('woocommerce_before_quantity_input_field', array($this, 'open_template_qty'), 10);
		add_action('woocommerce_after_quantity_input_field', array($this, 'close_template_qty'), 10);

		// Custom review comment form
		add_action( 'comment_post', array($this, 'save_comment_title_field'));
		add_action( 'woocommerce_review_before_comment_meta', array($this, 'get_comment_title_field'));
		remove_action( 'woocommerce_review_meta', 'woocommerce_review_display_meta' );
		add_action( 'woocommerce_review_after_comment_text', 'woocommerce_review_display_meta', 10);

		// Buy now redirect
		add_filter( 'woocommerce_add_to_cart_redirect', array( $this, 'durotan_buy_now_redirect' ), 99 );
	}

	/**
	 * WooCommerce specific scripts & stylesheets.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function scripts() {
		wp_enqueue_style( 'durotan-woocommerce-style', apply_filters( 'durotan_get_style_directory_uri', get_template_directory_uri() ) . '/woocommerce.css' );

		if ( wp_script_is( 'wc-add-to-cart-variation', 'registered' ) ) {
			wp_enqueue_script( 'wc-add-to-cart-variation' );
		}

		do_action( 'durotan_enqueue_woocommerce_style' );

		// Add js mini cart
		wp_enqueue_script( 'wc-cart-fragments' );
	}

	/**
	 * Ensure cart contents update when products are added to the cart via AJAX.
     *
	 * @since 1.0.0
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 *
	 * @return array Fragments to refresh via AJAX.
	 */
	public function cart_link_fragment( $fragments ) {
		$fragments['span.header-cart__counter']     = '<span class="header-cart__counter header-counter">' . intval( WC()->cart->get_cart_contents_count() ) . '</span>';
		$fragments['span.cart-panel__counter'] 		= '<span class="cart-panel__counter"> (' . intval( WC()->cart->get_cart_contents_count() ) . ')</span>';
		$fragments['span.header-cart__total-price'] = '<span class="header-cart__total-price">' . WC()->cart->get_cart_total() . '</span>';

		return $fragments;
	}

	/**
	 * Ensure wishlist contents update when products are added to the wishlist via AJAX.
     *
	 * @since 1.0.0
	 *
	 * @param array Refresh via AJAX.
	 *
	 * @return array Refresh via AJAX.
	 */
	public function update_wishlist_count() {
		if( function_exists('wcboost_wishlist') && class_exists( '\WCBoost\Wishlist\Helper' ) ) {
			$count = \WCBoost\Wishlist\Helper::get_wishlist()->count_items();
		} elseif ( function_exists( 'YITH_WCWL' ) ) {
			$count = YITH_WCWL()->count_products();
		} else {
			return;
		}

		wp_send_json( array(
			'count' => $count
		));
	}

	/**
	 * Output to view cart subtotal.
	 *
	 * @since 3.7.0
	 */
	public function durotan_widget_shopping_cart_subtotal() {
		echo '<strong>' . esc_html__( 'Subtotal', 'durotan' ) . '</strong>' . WC()->cart->get_cart_subtotal();
	}

	/**
	 * Remove space cart subtotal.
	 *
	 * @since 3.7.0
	 */
	public function durotan_space_cart_subtotal( $cart_subtotal ) {
		return str_replace( '&nbsp;', '', $cart_subtotal );
	}

	/**
	 * Remove space cart product price.
	 *
	 * @since 3.7.0
	 */
	public function durotan_space_cart_product_price( $cart_subtotal ) {
		return str_replace( '&nbsp;', '', $cart_subtotal );
	}

	/**
	 * Star rating HTML.
     *
	 * @since 1.0.0
	 *
	 * @param string $html Star rating HTML.
	 * @param int $rating Rating value.
	 * @param int $count Rated count.
	 *
	 * @return string
	 */
	public function star_rating_html( $html, $rating, $count ) {
		$html = '<span class="max-rating rating-stars">'
		        . \Durotan\Icon::get_svg( 'star', '', 'shop' )
		        . \Durotan\Icon::get_svg( 'star', '', 'shop' )
		        . \Durotan\Icon::get_svg( 'star', '', 'shop' )
		        . \Durotan\Icon::get_svg( 'star', '', 'shop' )
		        . \Durotan\Icon::get_svg( 'star', '', 'shop' )
		        . '</span>';
		$html .= '<span class="user-rating rating-stars" style="width:' . ( ( $rating / 5 ) * 100 ) . '%">'
				. \Durotan\Icon::get_svg( 'star', '', 'shop' )
				. \Durotan\Icon::get_svg( 'star', '', 'shop' )
				. \Durotan\Icon::get_svg( 'star', '', 'shop' )
				. \Durotan\Icon::get_svg( 'star', '', 'shop' )
				. \Durotan\Icon::get_svg( 'star', '', 'shop' )
		         . '</span>';

		$html .= '<span class="screen-reader-text">';

		if ( 0 < $count ) {
			/* translators: 1: rating 2: rating count */
			$html .= sprintf( _n( 'Rated %1$s out of 5 based on %2$s customer rating', 'Rated %1$s out of 5 based on %2$s customer ratings', $count, 'durotan' ), '<strong class="rating">' . esc_html( $rating ) . '</strong>', '<span class="rating">' . esc_html( $count ) . '</span>' );
		} else {
			/* translators: %s: rating */
			$html .= sprintf( esc_html__( 'Rated %s out of 5', 'durotan' ), '<strong class="rating">' . esc_html( $rating ) . '</strong>' );
		}

		$html .= '</span>';

		return $html;
	}

	/**
	 * Get Stock Availability Text
     *
	 * @since 1.0.0
     *
	 * @param string $availability.
	 * @param object $product.
	 *
	 * @return string
	 */
	public function get_product_availability_text( $availability, $product ) {
		if ( ! is_single() || ! is_singular('product') ) {
			return;
		}

		if ( ! $product->managing_stock() && $product->get_stock_status() == 'instock' ) {
			$availability = esc_html__( 'In stock', 'durotan' );
		}

		return $availability;
	}

		/**
	 * Changes breadcrumb args.
     *
	 * @since 1.0.0
	 *
	 * @param array $args The breadcrumb argurments.
	 *
	 * @return array
	 */
	public function breadcrumb_args( $args ) {
		$args['delimiter']   = \Durotan\Icon::get_svg( 'chevron-right', 'delimiter' );
		$args['wrap_before'] = '<nav class="woocommerce-breadcrumb site-breadcrumb">';
		$args['wrap_after']  = '</nav>';

		return $args;
	}

	/**
	 * Title field for comments.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function save_comment_title_field( $comment_id ){
		if ( isset( $_POST['title'], $_POST['comment_post_ID'] ) && 'product' === get_post_type( absint( $_POST['comment_post_ID'] ) ) ){
			update_comment_meta( $comment_id, 'title', esc_attr( $_POST['title'] ) );
		}
	}

	/**
	 * Print Title field for comments.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function get_comment_title_field( $comment ){
		$val = get_comment_meta( $comment->comment_ID, "title", true );
        $title = $val ? '<div class="review-title">' . esc_html($val) . '</div>' : '';
        echo ! empty( $title ) ? $title : '';
	}

	/**
	 * Open template qty
	 *
	 * @return void
	 */
	public function open_template_qty()
	{
		global $product;
		if ( function_exists( 'is_cart' ) && !is_cart() ) {
			echo '<div class="qty-box">';
			echo \Durotan\Icon::get_svg('minus', 'decrease', 'shop');
		}
	}

	/**
	 * Close template qty
	 *
	 * @return void
	 */
	public function close_template_qty()
	{
		if ( function_exists( 'is_cart' ) && !is_cart() ) {
			echo \Durotan\Icon::get_svg('plus', 'increase', 'shop');
			echo '</div>';
		}
	}

	/**
	 * Buy now redirect
	 *
	 * @since 1.0
	 */
	public function durotan_buy_now_redirect( $url ) {

		if ( ! isset( $_REQUEST['durotan_buy_now'] ) || $_REQUEST['durotan_buy_now'] == false ) {
			return $url;
		}

		if ( empty( $_REQUEST['quantity'] ) ) {
			return $url;
		}

		$redirect = esc_url(Helper::get_option( 'product_buy_now_link' ));

		if ( empty( $redirect ) ) {
			return wc_get_checkout_url();
		} else {
			wp_redirect( $redirect );
			exit;
		}
	}
}
