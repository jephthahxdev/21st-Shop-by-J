<?php
/**
 * WooCommerce Sticky Add To Cart template hooks.
 *
 * @package Durotan
 */

namespace Durotan\WooCommerce\Modules;
use Durotan\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of Sticky Add To Cart
 */
class Sticky_ATC {
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
		// Sticky add to cart
		add_action( 'wp_footer', array( $this, 'sticky_single_add_to_cart' ) );
	}

    /**
	 * Check has sticky add to cart
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public function has_sticky_atc() {
		global $product;
		if ( $product->is_purchasable() && $product->is_in_stock() ) {
			return true;
		} elseif ( $product->is_type( 'external' ) || $product->is_type( 'grouped' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Change ‘Choose an Option’ variation dropdown
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function sticky_dropdown_variation_args( $args ) {
		$attr_name = apply_filters( 'the_title', wc_attribute_label( $args['attribute'] ) );
		$args['show_option_none'] = sprintf( __( 'Choose your %s', 'durotan' ), strtolower( $attr_name ) );

		return $args;
	}

    /**
	 * Add sticky add to cart HTML
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function sticky_single_add_to_cart() {
        global $product;

        if ( ! $this->has_sticky_atc() ) {
			return;
		}
		add_filter( 'woocommerce_dropdown_variation_attribute_options_args', array( $this,'sticky_dropdown_variation_args'), 10 );
		add_filter( 'durotan_product_show_compare', '__return_false' );
		add_filter( 'durotan_product_show_wishlist', '__return_false' );
		add_filter( 'durotan_product_show_buy_now', '__return_false' );

		$product_avaiable = $product->is_purchasable() && $product->is_in_stock();
        $add_class = [
			'durotan-sticky-add-to-cart__content-button button durotan-button',
			'product_type_' . $product->get_type(),
			$product_avaiable ? 'add_to_cart_button' : '',
			$product->supports( 'ajax_add_to_cart' ) && $product_avaiable ? 'ajax_add_to_cart' : '',
		];

		$thumbnail_size = 'shop_thumbnail';

		if ( function_exists( 'wc_get_image_size' ) ) {
			$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
			$thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array(
				$gallery_thumbnail['width'],
				$gallery_thumbnail['height']
			) );
		}

		$product_type    = $product->get_type();
		$container 	     = Helper::get_option( 'product_layout' ) !== 'v7' ? Helper::get_option( 'single_product_width' ) : 'container';
		$container_class = $product_type == 'variable' && $container == 'container' ? 'durotan-container' : $container;
		$sticky_class    = 'durotan-sticky-add-to-cart product-' . $product_type;
		?>
        <section id="durotan-sticky-add-to-cart"
                 class="<?php echo esc_attr( $sticky_class ) ?>">
            <div class="<?php echo esc_attr( $container_class ) ?>">
                <div class="durotan-sticky-add-to-cart__content">
					<?php echo woocommerce_get_product_thumbnail( $thumbnail_size ); ?>
					<div class="durotan-sticky-add-to-cart__content-product-info">
						<div class="durotan-sticky-add-to-cart__content-title"><?php the_title(); ?></div>
						<span class="durotan-sticky-add-to-cart__content-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
					</div>
					<?php
					if ( $product->is_type( 'simple' ) ) {
						woocommerce_template_single_add_to_cart();
					} else {
						if ( $product->is_type( 'variable' ) ) {
							woocommerce_template_single_add_to_cart();
						}

						echo sprintf( '<a href="%s" data-quantity="1" class="%s" data-product_id = "%s" data-text="%s" data-title="%s" rel="nofollow"> %s</a>',
							esc_url( $product->add_to_cart_url() ),
							esc_attr( implode( ' ', $add_class ) ),
							esc_attr( $product->get_id() ),
							esc_attr( $product->add_to_cart_text() ),
							esc_attr( $product->get_title() ),
							esc_html__( 'Add to cart', 'durotan' ) );
					}
					?>
                </div>
            </div>
        </section><!-- .durotan-sticky-add-to-cart -->
		<?php
    }

}
