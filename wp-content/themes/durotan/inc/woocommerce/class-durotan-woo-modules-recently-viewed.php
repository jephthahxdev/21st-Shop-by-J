<?php
/**
 * Recently viewed template hooks.
 *
 * @package Durotan
 */

namespace Durotan\WooCommerce\Modules;

use Durotan\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of general Recently viewed .
 */
class Recently_Viewed {
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
	 * Instance
	 *
	 * @var $instance
	 */
	private $product_ids;

	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$viewed_products   = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();
		$this->product_ids = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );

		// Track Product View
		add_action( 'template_redirect', array( $this, 'track_product_view' ) );
		if ( intval( Helper::get_option( 'recently_viewed_ajax' ) ) ) {
			add_action( 'wc_ajax_durotan_get_recently_viewed', array( $this, 'do_ajax_products_content' ) );
		}

		add_action( 'durotan_before_open_site_footer', array( $this, 'products_recently_viewed_section' ) );
	}

	/**
	 * Track product views
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function track_product_view() {
		if ( ! is_singular( 'product' ) ) {
			return;
		}

		global $post;

		if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) ) {
			$viewed_products = array();
		} else {
			$viewed_products = (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] );
		}

		if ( ! in_array( $post->ID, $viewed_products ) ) {
			$viewed_products[] = $post->ID;
		}

		if ( sizeof( $viewed_products ) > 15 ) {
			array_shift( $viewed_products );
		}

		// Store for session only
		wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ), time() + 60 * 60 * 24 * 30 );
	}

	/**
	 * Get product recently viewed
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function products_content() {
		$limit = Helper::get_option( 'recently_viewed_numbers' );

		if ( empty( $this->product_ids ) ) {
			printf(
				'<ul class="product-list no-products">' .
				'<li class="text-left">%s <br><a href="%s" class="durotan-button">%s</a></li>' .
				'</ul>',
				esc_html__( 'Recently Viewed Products is a function which helps you keep track of your recent viewing history.', 'durotan' ),
				esc_url( get_permalink( get_option( 'woocommerce_shop_page_id' ) ) ),
				esc_html__( 'Shop Now', 'durotan' )
			);
		} else {

			woocommerce_product_loop_start();
            $original_post = $GLOBALS['post'];

            $index = 1;
            foreach ( $this->product_ids as $post_id ) {
                if ( $index > $limit ) {
                    break;
                }

                $index ++;

				$product = get_post( $post_id );
				if( empty($product) ) {
					continue;
				}
				$GLOBALS['post'] = $product;
                setup_postdata( $GLOBALS['post'] );
                wc_get_template_part( 'content', 'product' );
            }
            $GLOBALS['post'] = $original_post;
            woocommerce_product_loop_end();
            wc_reset_loop();

            wp_reset_postdata();
		}
	}

	/**
	 * Get product content AJAX
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function do_ajax_products_content() {
		ob_start();

		$this->products_content();

		$output [] = ob_get_clean();

		wp_send_json_success( $output );
		die();
	}

	/**
	 * Get product recently viewed
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function products_recently_viewed_section() {
		if ( ! intval( Helper::get_option( 'recently_viewed_enable' ) ) ) {
			return;
		}

		if ( ! is_singular( 'product' ) && ! \Durotan\Helper::is_catalog() && ! is_cart() && is_checkout() ) {
			return;
		}

		$display_page = (array) Helper::get_option( 'recently_viewed_display_page' );

		if ( is_singular( 'product' ) && ! in_array( 'single', $display_page ) ) {
			return;
		} elseif ( \Durotan\Helper::is_catalog() && ! in_array( 'catalog', $display_page ) ) {
			return;
		}

		$check_ajax = Helper::get_option( 'recently_viewed_ajax' );

		$addClass = $check_ajax ? '' : 'no-ajax';

		if ( empty( $this->product_ids ) ) {
			$addClass .= intval( Helper::get_option( 'recently_viewed_empty' ) ) ? ' hide-empty' : '';
		}

		$container = 'durotan-container';
		if ( \Durotan\Helper::is_catalog() ) {
			$container = Helper::get_option( 'catalog_width' );
		} elseif( is_singular( 'product' ) ) {
			$container = Helper::get_option( 'single_product_width' );

			if ( Helper::get_option( 'product_layout' ) == 'v7' ) {
				$container = 'container';
			}
		}

		?>
        <section class="durotan-recently-viewed-product <?php echo esc_attr( $addClass ) ?>" id="durotan-recently-viewed-product"
                 data-col=<?php echo esc_attr( Helper::get_option( 'recently_viewed_columns' ) ) ?>>
            <div class="recently-viewed-container <?php echo esc_attr( $container ) ?>">
				<?php if ( Helper::get_option( 'recently_viewed_title' )) :
                    printf( '<h2 class="recently-title">%s</h2>', esc_html( Helper::get_option( 'recently_viewed_title' ) ) );
				endif; ?>
                <div class="recently-products ">

					<?php if ( ! $check_ajax ) :
						$this->products_content();
					else: ?>
                        <div class="durotan-spinner-loading">
							<div class="bar-1"></div>
							<div class="bar-2"></div>
							<div class="bar-3"></div>
							<div class="bar-4"></div>
							<div class="bar-5"></div>
							<div class="bar-6"></div>
							<div class="bar-7"></div>
							<div class="bar-8"></div>
                        </div>
					<?php endif; ?>
                </div>
            </div>
        </section>
		<?php
	}

}
