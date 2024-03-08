<?php
/**
 * Hooks of single product.
 *
 * @package Durotan
 */

namespace Durotan\WooCommerce\Elements;
use Durotan\WooCommerce\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of single product template.
 */
class Product_Summary {
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
	 * Product layout
	 *
	 * @var $product_layout
	 */
	protected static $product_layout = null;

	/**
	 * Product layout
	 *
	 * @var $product_layout
	 */
	protected static $settings = null;

	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		// Adds a class of product layout to product page.
        add_action( 'durotan_single_product_summary_classes', array( $this, 'product_summary_class' ) );

		// Change the product thumbnails columns
		add_filter('woocommerce_product_thumbnails_columns', array($this, 'product_thumbnails_columns'));

		// Summary order els
        add_action( 'durotan_woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

		// Custom layout
		add_action( 'elementor/widget/before_render_content', array( $this, 'custom_layout_product_shortcode' ));
	}

	public function custom_layout_product_shortcode($widget){
		$widget_name	 = $widget->get_name();
		$sc_name = [
			'durotan-product-shortcode',
			'durotan-product-shortcode-2',
			'durotan-product-shortcode-3',
			'durotan-product-shortcode-4'
		];
		if( in_array( $widget_name, $sc_name ) ){
			self::$settings  = $widget->get_settings_for_display();
			remove_all_actions('durotan_woocommerce_single_product_summary');
			remove_all_actions('durotan_woocommerce_after_single_product_summary');

			// Deal template
			if ( class_exists( '\Durotan\Addons\Modules\Product_Deals' ) ) {
				add_action( 'durotan_woocommerce_single_product_summary', array(
					'\Durotan\Addons\Modules\Product_Deals',
					'single_product_template',
				), 26 );
			}
			// change product gallery classes
			add_filter('woocommerce_single_product_image_gallery_classes', array($this,'product_summary_gallery_classes'));

			// Button wrapper
			add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'open_control_button_wrapper' ), 10 );
			add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'product_single_break_button' ), 80 );
			add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'product_single_wishlist' ), 85 );
			add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'product_single_compare'), 90 );
			add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'close_control_button_wrapper' ), 100 );
		}
		switch($widget_name){
			case 'durotan-product-shortcode':
				//self::$product_layout = 'layout-v4';
				add_action( 'durotan_woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
				add_action( 'durotan_woocommerce_single_product_summary', 'woocommerce_template_single_price', 15 );
				add_action( 'durotan_woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
				add_action( 'durotan_woocommerce_single_product_summary', array( $this , 'product_share' ), 40 );
				add_action( 'durotan_woocommerce_single_product_summary', array( $this , 'product_summary_description' ), 35 );
				add_action( 'woocommerce_before_quantity_input_field', array($this, 'add_label_quantity'), 9);
				add_filter( 'durotan_product_show_wishlist', '__return_false' );
				add_filter( 'durotan_product_show_compare', '__return_false' );
				add_filter( 'durotan_product_show_buy_now', '__return_false' );
				break;
			case 'durotan-product-shortcode-2':
				self::$product_layout = 'layout-v2';
				add_action( 'durotan_woocommerce_single_product_summary', array( $this, 'single_product_taxonomy' ), 3 );
				add_action( 'durotan_woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
				add_action( 'durotan_woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
				add_action( 'durotan_woocommerce_single_product_summary', 'woocommerce_template_single_price', 15 );
				add_action( 'durotan_woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );


				// Buy now
				add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'product_buy_now_button' ), 80 );
				add_filter( 'woocommerce_add_to_cart_redirect', array( $this, 'durotan_buy_now_redirect' ), 99 );
				remove_action( 'woocommerce_after_add_to_cart_button', array( $this , 'product_single_break_button' ), 80);
				add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'product_single_break_button' ), 80);

				add_action( 'durotan_woocommerce_single_product_summary', array( $this , 'product_share' ), 40 );
				add_filter( 'durotan_product_show_compare', '__return_false' );
				add_filter( 'durotan_product_show_wishlist', '__return_true' );
				add_filter( 'durotan_product_show_buy_now', '__return_true' );
				add_filter( 'durotan_size_guide_icon', array( $this , 'size_guide_icon' ));
				break;
			case 'durotan-product-shortcode-3':
				self::$product_layout = 'layout-v6';
				add_action( 'durotan_woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
				add_action( 'durotan_woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
				add_action( 'durotan_woocommerce_single_product_summary', 'woocommerce_template_single_price', 15 );
				add_action( 'durotan_woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
				add_action( 'durotan_woocommerce_single_product_summary', array( $this , 'product_summary_description' ), 40 );
				add_action(	'woocommerce_after_add_to_cart_button', array($this, 'product_share'), 99);
				add_filter( 'durotan_product_summary_sharing_heading', array( $this , 'product_share_heading' ));
				add_filter( 'durotan_product_show_compare', '__return_false' );
				add_filter( 'durotan_product_show_wishlist', '__return_true' );
				add_filter( 'durotan_product_show_buy_now', '__return_false' );
				break;
			case 'durotan-product-shortcode-4':
				self::$product_layout = 'layout-v5';
				add_action( 'durotan_woocommerce_single_product_summary', array($this, 'open_inner_summary'), 1 );
				add_action( 'durotan_woocommerce_single_product_summary', array($this, 'close_inner_summary'), 100 );
				add_action( 'durotan_woocommerce_single_product_summary', array( $this, 'single_product_taxonomy' ), 3 );
				add_action( 'durotan_woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
				add_action( 'durotan_woocommerce_single_product_summary', array( $this, 'open_price_box_wrapper' ), 10 );
				// Sale Badges
				add_action( 'durotan_woocommerce_single_product_summary', array( $this,	'product_price_save' ), 13 );
				add_action( 'durotan_woocommerce_single_product_summary', 'woocommerce_template_single_price', 12 );
				add_action( 'durotan_woocommerce_single_product_summary', array( $this, 'close_price_box_wrapper' ), 15 );
				add_action( 'durotan_woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
				add_action(	'woocommerce_after_add_to_cart_button', array($this, 'product_share'), 99);
				add_filter( 'durotan_product_show_compare', '__return_false' );
				add_filter( 'durotan_product_show_wishlist', '__return_true' );
				add_filter( 'durotan_product_show_buy_now', '__return_false' );
				add_filter( 'durotan_deal_expire_text', array( $this, 'change_deal_expire_text') );
				break;
			default:
				break;
		}
	}

	/**
	 * Change deal expire text
	 *
	 * @return void
	 */
	public function change_deal_expire_text() {
		$expire_text = 'Expires <br />In';

		return $expire_text;
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
	 * Displays sharing buttons.
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_share() {
		if ( ! class_exists( '\Durotan\Addons\Helper' ) && ! method_exists( '\Durotan\Addons\Helper', 'share_link' ) ) {
			return;
		}

        if(! apply_filters( 'durotan_product_show_social', true )){
            return;
        }

		if ( ! \Durotan\Helper::get_option( 'product_sharing' ) ) {
			return;
		}

		$socials = \Durotan\Helper::get_option( 'product_sharing_socials' );
		$whatsapp_number = \Durotan\Helper::get_option( 'product_sharing_whatsapp_number' );

		if ( empty( $socials ) ) {
			return;
		}
		$title =  apply_filters( 'durotan_product_summary_sharing_heading', __( 'Share', 'durotan' ) );
		?>
        <div class="product-share share">
			<span class="sharing-icon">
				<?php echo ! empty( $title ) ? $title : ''; ?>
			</span>
            <span class="socials">
				<?php
				foreach ( $socials as $social ) {
					echo \Durotan\Addons\Helper::share_link($social, array( 'whatsapp_number' => $whatsapp_number ), 'social_2');
				}
				?>
			</span>
        </div>
		<?php
	}

	/**
	 * Adds classes to products
	 *
	 * @since 1.0.0
	 *
	 * @param array $classes product summary classes.
	 *
	 * @return array
	 */
	public function product_summary_class( $classes ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return $classes;
		}

		$classes[] = self::$product_layout;

		if ( \Durotan\Helper::get_option( 'product_add_to_cart_ajax' ) ) {
			$classes[] = 'product-add-to-cart-ajax';
		}

		global $product;

		$video_image = get_post_meta($product->get_id(), 'video_thumbnail', true);

		if ( self::$settings['show_thumbnail'] === 'show' && $product->get_gallery_image_ids() ) {
			$classes[] = 'has-gallery-image';
		}

		return $classes;
	}

	/**
	 * Add class to product gallery
	 *
	 * @since 1.0.0
	 *
	 * @param array $classes
	 *
	 * @return array
	 */
	public function product_summary_gallery_classes($classes)
	{
		global $product;
		$attachment_ids = $product->get_gallery_image_ids();

		if (self::$settings['show_thumbnail'] != 'show' || !$attachment_ids) {
			$classes[] = 'without-thumbnails';
		}

		return $classes;
	}

	/**
	 * Product thumbnails columns
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function product_thumbnails_columns()
	{
		return intval(\Durotan\Helper::get_option('product_thumbnail_numbers'));
	}


    /**
	 * Flexslider options - disable controlNav
	 *
	 * @since 1.0.0
	 *
	 * @param $options
	 *
	 * @return array
	 */
	public function flexslider_options($options)
	{
        $options['controlNav'] = false;
    	return $options;
	}

	/**
	 * Add label Auantity
	 *
	 * @return string
	 */
	public function add_label_quantity(){
		echo '<label class="label">' . esc_html__( 'Quantity', 'durotan' ) . '</label>';
	}

	/**
	 * Category name
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function single_product_taxonomy()
	{
		if ( $taxonomy = self::$settings['show_product_taxonomy']) {
			\Durotan\WooCommerce\Helper::product_taxonomy( $taxonomy );
		}
	}

	/**
	 * Product description
	 *
	 * @return string
	 */
	public function product_summary_description(){
		if(! apply_filters( 'durotan_product_show_description', true )){
            return;
        }
		$product_id = get_the_ID();
		$isElementor = \Elementor\Plugin::$instance->db->is_built_with_elementor( $product_id );
		$full_desc = $isElementor ? \Elementor\Plugin::$instance->frontend->get_builder_content( $product_id , true ) : apply_filters( 'the_content', get_the_content() );
		$excerpt_length = ! empty(self::$settings['excerpt_length']) ? self::$settings['excerpt_length'] : 25;
		?>
		<div class="product_description">
			<?php
				if( apply_filters( 'durotan_product_show_all_content', false )){
					$more_text = sprintf(
						'%s%s',
						esc_html__( 'Read more', 'durotan' ),
						\Durotan\Icon::get_svg( 'chevron-bottom' )
					);
					$collapsed = \Durotan\WooCommerce\Helper::get_content_limit( $excerpt_length , $more_text );
					?>
					<div class="collapsed-desc"><?php echo ! empty( $collapsed ) ? $collapsed : '';?></div>
					<div class="full-desc" style="display:none;"><?php echo ! empty( $full_desc ) ? $full_desc : ''; ?></div>
					<?php
				}else{
					echo ! empty( $full_desc ) ? $full_desc : '';
				}
			?>
		</div>
		<?php
	}

	/**
	 * Size guide icon
	 *
	 * @return string
	 */
	public function size_guide_icon(){
		return \Durotan\Icon::get_svg( 'full-screen', '', 'widget' );
	}


	/**
	 * Product share heading
	 *
	 * @return string
	 */
	public function product_share_heading(){
		return sprintf(
			'%s%s',
			\Durotan\Icon::get_svg( 'reply', '', 'widget' ),
			esc_html__( 'Social share', 'durotan' ),
		);
	}


	/**
	 * Open inner summary
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function open_inner_summary() {
		echo '<div class="inner-summary">';
	}

	/**
	 * Close inner summary
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function close_inner_summary() {
		echo '</div>';
	}

	/**
	 * Open button wrapper
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function open_price_box_wrapper() {
		echo '<div class="summary-price-box">';
	}

	/**
	 * Close button wrapper
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function close_price_box_wrapper() {
		echo '</div>';
	}
	/**
	 * Open button wrapper
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function open_control_button_wrapper() {
		echo '<div class="product-button-wrapper">';
	}

	/**
	 * Close button wrapper
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function close_control_button_wrapper() {
		echo '</div>';
	}

	/**
	 * Breack button
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_single_break_button() {
		echo '<div class="durotan-break"></div>';
	}

	/**
	 * Display wishlist button
	 *
	 * @since 1.0
     *
	 * @return void
	 */
	public function product_single_wishlist() {
		if ( ! shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
			return;
		}

		if(! apply_filters( 'durotan_product_show_wishlist', true )){
            return;
        }

		echo '<div class="durotan-wishlist-button durotan-button button-outline show-wishlist-title">';
		echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
		echo '</div>';
	}

	/**
	 * Display wishlist button
	 *
	 * @since 1.0
     *
	 * @return void
	 */
	public function product_single_compare() {
		global $product;
		if ( $product->get_type() == 'grouped' ) {
			return;
		}

		if ( 'no' === get_option( 'yith_woocompare_compare_button_in_product_page' ) )  {
			return;
		}

		if(! apply_filters( 'durotan_product_show_compare', true )){
            return;
        }

		echo '<div class="durotan-compare-button durotan-button button-outline show-compare-title">';
		\Durotan\WooCommerce\Helper::compare_button();
		echo '</div>';
	}

	/**
	 * Add buy now button
	 *
	 * @since 1.0
	 */
	public function product_buy_now_button() {
		global $product;
		if ( ! intval(( \Durotan\Helper::get_option('product_buy_now') )) ) {
			return;
		}

		if(! apply_filters( 'durotan_product_show_buy_now', true )){
            return;
        }

		if ( $product->get_type() == 'external' || $product->get_type() == 'grouped') {
			return;
		}

		echo sprintf( '<button class="buy_now_button button">%s</button>', esc_html( \Durotan\Helper::get_option( 'product_buy_now_text' ) ) );
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

		$redirect = esc_url(\Durotan\Helper::get_option( 'product_buy_now_link' ));

		if ( empty( $redirect ) ) {
			return wc_get_checkout_url();
		} else {
			wp_redirect( $redirect );
			exit;
		}
	}
}
