<?php

/**
 * WCBoost Wishlist additional settings.
 *
 * @package Durotan
 */

namespace Durotan\WooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of WCBoost Wishlist Settings
 */
class WCBoost_Wishlist {
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
		add_filter( 'wcboost_wishlist_add_to_wishlist_fragments', array( $this, 'update_wishlist_count' ), 10, 1 );

		add_filter( 'wcboost_wishlist_button_template_args', array( $this, 'wishlist_args' ), 20, 3 );
		add_filter( 'wcboost_wishlist_svg_icon', array( $this, 'wishlist_svg_icon' ), 20, 3 );
		add_filter( 'wcboost_wishlist_button_view_text',	array( $this, 'wishlist_button_view_text' ) );

		add_action( 'wcboost_wishlist_after_item_name', array( $this, 'wishlist_table' ), 10, 3 );
	}

	/**
	 * Ajaxify update count wishlist
	 *
	 * @since 1.0
	 *
	 * @param array $fragments
	 *
	 * @return array
	 */
	public function update_wishlist_count($data) {
		$data['.header-wishlist .header-wishlist__counter'] = '<span class="header-wishlist__counter header-counter">'.\WCBoost\Wishlist\Helper::get_wishlist()->count_items() . '</span>';

		return $data;
	}

	/**
	 * Wishlist array
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function wishlist_args($args) {
		$args['class'][] = esc_attr( 'durotan-loop_button' );

		return $args;
	}

	/**
	 * Wishlist icon
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function wishlist_svg_icon($svg, $icon) {
		if( $icon == 'heart' ) {
			$svg = \Durotan\Icon::get_svg( 'heart', '', 'shop' );
		} else if ( $icon == 'heart-filled' ) {
			$svg = \Durotan\Icon::get_svg( 'heart-2', '', 'shop' );
		}

		return $svg;
	}

	/**
	 * Change button view text
	 *
	 * @return void
	 */
	public function wishlist_button_view_text() {
		return esc_html__( 'Browse wishlist', 'durotan' );
	}

	/**
	 * Wishlist table
	 *
	 * @return void
	 */
	public function wishlist_table( $item, $item_key, $wishlist ) {
		$_product = $item->get_product();
		$default_columns  = [
			'price'    => 'yes',
			'stock'    => 'yes',
			'quantity' => 'no',
			'date'     => 'no',
			'purchase' => 'yes',
		];

		$columns = get_option( 'wcboost_wishlist_table_columns' , $default_columns );
		$columns = wp_parse_args( $columns, $default_columns );
		?>
			<?php if( isset( $columns['price'] ) && $columns['price'] == 'yes' ) : ?>
				<div class="product-price hidden-lg hidden-md">
					<span class="label"><?php esc_html_e( 'Price', 'durotan' ); ?></span>
					<span class="price"><?php echo wp_kses_post( $_product->get_price_html() ); ?></span>
				</div>
			<?php endif; ?>

			<?php if( isset( $columns['stock'] ) && $columns['stock'] == 'yes' ) : ?>
				<div class="product-stock-status hidden-lg hidden-md">
					<span class="label"><?php esc_html_e( 'Stock', 'durotan' ); ?></span>
					<?php
					$availability = $_product->get_availability();
					printf( '<span class="%s">%s</span>', esc_attr( $availability['class'] ), $availability['availability'] ? esc_html( $availability['availability'] ) : esc_html__( 'In Stock', 'durotan' ) );
					?>
				</div>
			<?php endif; ?>

			<?php if ( isset( $columns['date'] ) && $columns['date'] == 'yes' ) : ?>
				<div class="product-date hidden-lg hidden-md">
					<?php echo esc_html( $item->get_date_added()->format( get_option( 'date_format' ) ) ); ?>
				</div>
			<?php endif; ?>
		<?php
	}
}
