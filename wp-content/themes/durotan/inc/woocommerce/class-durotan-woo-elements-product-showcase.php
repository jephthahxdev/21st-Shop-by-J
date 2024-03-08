<?php
/**
 * Product ShowCase Loop template hooks.
 *
 * @package Durotan
 */

namespace Durotan\WooCommerce\Elements;
use Durotan\WooCommerce\Helper, Durotan\WooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of Product ShowCase Loop
 */
class Product_ShowCase {
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

		add_action( 'durotan_woo_before_shop_loop_item_title', array( $this, 'product_tag' ) );

		add_action( 'durotan_woo_shop_loop_item_title', array( Helper::instance(), 'product_loop_title' ) );

		add_action( 'durotan_woo_after_shop_loop_item_title', array( $this, 'open_price_div' ) );

		add_action( 'durotan_woo_after_shop_loop_item_title', 'woocommerce_template_loop_price' );

		add_action( 'durotan_woo_after_shop_loop_item_title', array( $this, 'product_price_save' ) );

		add_action( 'durotan_woo_after_shop_loop_item_title', array( $this, 'close_price_div' ) );

		add_action( 'durotan_woo_after_shop_loop_item_title', array( $this, 'deal_wrapper_open' ) );

		if(class_exists( '\Durotan\Addons\Modules\Product_Deals' ) ) {
			add_action( 'durotan_woo_after_shop_loop_item_title', array( \Durotan\Addons\Modules\Product_Deals::instance(), 'single_product_template' ) );
		}

		add_action( 'durotan_woo_after_shop_loop_item_title', array( $this, 'deal_wrapper_close' ) );

		add_action( 'durotan_woo_after_shop_loop_item_title', array( $this, 'product_meta' ) );
		add_action( 'durotan_woo_after_shop_loop_item_title', 'woocommerce_template_single_add_to_cart' );
		add_action( 'durotan_woo_after_shop_loop_item_title', array( $this, 'product_button' ) );
	}

	/**
	 * Get product tag
	 */
	public static function product_tag() {

		$product_tag = array();

		$terms = wp_get_post_terms( get_the_id(), 'product_tag' );

		if( count($terms) > 0 ){
			foreach($terms as $term){
				$product_tag[] = $term->name;
			}

			echo '<div class="product-tag">'.$product_tag[0].'</div>';
		}
	}

	/**
	 * Open price div
	 */
	public static function open_price_div() {
		echo '<div class="price-sumary">';
	}

	/**
	 * Close price div
	 */
	public static function close_price_div() {
		echo '</div>';
	}

	/**
	 * Get product price
	 */
	public static function product_price_save() {

		$product = wc_get_product( get_the_id() );

		$price_regular = intval( $product->get_regular_price() );
		$price_sale = intval( $product->get_sale_price() );

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

			if ( $max_percentage !== 0 ) {
				$discount = $max_percentage . '%';
				echo sprintf(
						'<span class="discount">%s %s</span>',
						esc_html__( 'Save', 'durotan' ),
						$discount
					);
			}
		}

		if ( $price_sale ) {
			$discount = intval( ( ( $price_regular - $price_sale ) / $price_regular ) * 100 ) . '%';
			echo sprintf(
					'<span class="discount">%s %s</span>',
					esc_html__( 'Save', 'durotan' ),
					$discount
				);
		}
	}

	/**
	 * Deal wrapper open
	 */
	public static function deal_wrapper_open() {
		echo '<div class="single-product woocommerce">';
			echo '<div class="product">';
	}

	/**
	 * Deal wrapper close
	 */
	public static function deal_wrapper_close() {
			echo '</div>';
		echo '</div>';
	}

	/**
	 * Get product meta
	 *
	 */
	public static function product_meta() {
		global $product;
	?>
		<div class="product_meta">

			<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

				<div class="sku_wrapper meta_wrapper"><span class="label"><?php esc_html_e( 'SKU:', 'durotan' ); ?></span><span class="sku"><?php if ( $sku = $product->get_sku() ) { echo wp_kses_post( $sku ); } else { esc_html_e( 'N/A', 'durotan' ); } ?></span></div>

			<?php endif; ?>


			<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<div class="category_wrapper meta_wrapper"><span class="label">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'durotan' ) . '</span>', '</div>' ); ?>

			<?php echo get_the_term_list( $product->get_id(), 'product_brand', '<div class="brand_wrapper meta_wrapper"><span class="label">' . esc_html( 'Brand:', 'durotan' ) . '</span>',', ', '</div>' ); ?>
		</div>
	<?php
	}

	/**
	 * Get product button
	 *
	 */
	public static function product_button() {
		echo '<div class="product-button">';
			echo '<div class="product-button__wishlist">';
				\Durotan\WooCommerce\Helper::wishlist_button();
			echo '</div>';
			echo '<div class="product-button__share">';
				\Durotan\WooCommerce\Helper::product_share();
			echo '</div>';
		echo '</div>';
	}
}
