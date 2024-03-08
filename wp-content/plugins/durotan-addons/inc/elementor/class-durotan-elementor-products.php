<?php

namespace Durotan\Addons\Elementor;
use Durotan\Addons\Elementor\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Products {

	/**
	 * Instance
	 *
	 * @var $instance
	 */
	private static $instance;


	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
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
		add_action( 'wp_ajax_nopriv_durotan_elementor_get_products_tab', [ $this, 'ajax_get_products_tab' ] );
		add_action( 'wc_ajax_durotan_elementor_get_products_tab', [ $this, 'ajax_get_products_tab' ] );
	}

	/**
	 * Get content
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_content($atts){
		if ( ! class_exists( 'WC_Shortcode_Products' ) ) {
			return;
		}
		$type = $atts['products'];
		$shortcode  = new \WC_Shortcode_Products( $atts, $type );
		$output = $shortcode->get_content();
		return $output;
	}

	/**
	 * Product shortcode
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_products( $atts ) {
		$params   = '';
		$order    = $atts['order'];
		$order_by = $atts['orderby'];
		if ( $atts['products'] == 'featured' ) {
			$params = 'visibility="featured"';
		} elseif ( $atts['products'] == 'best_selling' ) {
			$params = 'best_selling="true"';
		} elseif ( $atts['products'] == 'sale' ) {
			$params = 'on_sale="true"';
		} elseif ( $atts['products'] == 'recent' ) {
			$order    = $order ? $order : 'desc';
			$order_by = $order_by ? $order_by : 'date';
		} elseif ( $atts['products'] == 'top_rated' ) {
			$params = 'top_rated="true"';
		}

		if ( ! empty( $atts['ids'] ) ) {
			$params   .= ' ids="' . $atts['ids'] . '" ';
			$order_by = 'post__in';
		}

		$params .= ' columns="' . intval( $atts['columns'] ) . '" limit="' . intval( $atts['per_page'] ) . '" order="' . $order . '" orderby ="' . $order_by . '"';

		if ( ! empty( $atts['product_cats'] ) ) {
			$cats = $atts['product_cats'];
			if ( is_array( $cats ) ) {
				$cats = implode( ',', $cats );
			}

			$params .= ' category="' . $cats . '" ';
		}

		if ( ! empty( $atts['product_tags'] ) ) {
			$params .= ' tag="' . $atts['product_tags'] . '" ';
		}

		if ( isset( $atts['product_brands'] ) && ! empty( $atts['product_brands'] ) ) {
			$params .= ' class="sc_brand,' . $atts['product_brands'] . '" ';
		}

		return do_shortcode( '[products ' . $params . ']' );
	}

	/**
	 * Load products
	 *
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function ajax_get_products_tab() {
        if ( empty( $_POST['settings'] )) {
			wp_send_json_error( esc_html__( 'No query data.', 'durotan' ) );
			exit;
		}
		$settings = $_POST['settings'];
		$atts = array(
			'columns'         => isset( $settings['columns'] ) ? intval( $settings['columns'] ) : '',
			'products'        => isset( $settings['products'] ) ? $settings['products'] : '',
			'order'           => isset( $settings['order'] ) ? $settings['order'] : '',
			'orderby'         => isset( $settings['orderby'] ) ? $settings['orderby'] : '',
			'per_page'        => isset( $settings['per_page'] ) ? intval( $settings['per_page'] ) : '',
			'category'        => isset( $settings['category'] ) ? $settings['category'] : '',
			'tag'		      => isset( $settings['tag'] ) ? $settings['tag'] : '',
			'page'            => isset( $_POST['page'] ) ? $_POST['page'] : 1,
			'paginate'        => true,
		);

		$results = self::products_shortcode( $atts );

		if ( ! $results ) {
			return;
		}

		$product_ids = $results['ids'];

		$current_page = $atts['page'] + 1;
		$data_text    = 'data-text=""';
		if ( $results['current_page'] >= $results['total_pages'] ) {
			$current_page = 0;
			$data_text    = esc_html__( 'No products were found', 'durotan' );
		}

		$products = '<div class="products-tab">';

		ob_start();

		wc_setup_loop(
			array(
				'columns' => $atts['columns']
			)
		);

		self::get_template_loop( $product_ids );

		$products .= ob_get_clean();

		$products .= '</div>';

		wp_send_json_success( $products );
	}

	/**
	 * Get products loop content for shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Shortcode attributes
	 *
	 * @return array
	 */
	public static function products_shortcode( $atts ) {
		if ( ! class_exists( 'WC_Shortcode_Products' ) ) {
			return;
		}
		$type = $atts['products'];
		$shortcode  = new \WC_Shortcode_Products( $atts, $type );
		$query_args = $shortcode->get_query_args();

		if ( isset( $atts['page'] ) ) {
			$query_args['paged'] = isset( $atts['page'] ) ? absint( $atts['page'] ) : 1;
		}

		return self::get_query_results( $query_args, $type );
	}

	/**
	 * Run the query and return an array of data, including queried ids.
	 *
	 * @since 1.0.0
	 *
	 * @return array with the following props; ids
	 */
	public static function get_query_results( $query_args, $type ) {
		$transient_name    = self::get_transient_name( $query_args, $type );
		$transient_version = \WC_Cache_Helper::get_transient_version( 'product_query' );
		$transient_value   = get_transient( $transient_name );

		if ( isset( $transient_value['value'], $transient_value['version'] ) && $transient_value['version'] === $transient_version ) {
			$results = $transient_value['value'];
		} else {

			$query = new \WP_Query( $query_args );

			$paginated = ! $query->get( 'no_found_rows' );

			$results = array(
				'ids'          => wp_parse_id_list( $query->posts ),
				'total'        => $paginated ? (int) $query->found_posts : count( $query->posts ),
				'total_pages'  => $paginated ? (int) $query->max_num_pages : 1,
				'current_page' => $paginated ? (int) max( 1, $query->get( 'paged', 1 ) ) : 1,
			);
			wp_reset_postdata();

			$transient_value = array(
				'version' => $transient_version,
				'value'   => $results,
			);
			set_transient( $transient_name, $transient_value, DAY_IN_SECONDS * 30 );
		}

		// Remove ordering query arguments which may have been added by get_catalog_ordering_args.
		WC()->query->remove_ordering_args();

		return $results;
	}

	/**
	 * Generate and return the transient name for this shortcode based on the query args.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_transient_name( $query_args, $type ) {
		$transient_name = 'durotan_product_loop_' . md5( wp_json_encode( $query_args ) . $type );

		if ( 'rand' === $query_args['orderby'] ) {
			// When using rand, we'll cache a number of random queries and pull those to avoid querying rand on each page load.
			$rand_index     = wp_rand( 0, max( 1, absint( apply_filters( 'woocommerce_product_query_max_rand_cache_count', 5 ) ) ) );
			$transient_name .= $rand_index;
		}

		return $transient_name;
	}

	/**
	 * Loop over products
	 *
	 * @since 1.0.0
	 *
	 * @param string
	 */
	public static function get_template_loop( $products_ids, $echo = true, $template = 'product' ) {
		update_meta_cache( 'post', $products_ids );
		update_object_term_cache( $products_ids, 'product' );

		ob_start();

		$original_post = $GLOBALS['post'];

		woocommerce_product_loop_start();

		foreach ( $products_ids as $product_id ) {
			$GLOBALS['post'] = get_post( $product_id ); // WPCS: override ok.
			setup_postdata( $GLOBALS['post'] );
			wc_get_template_part( 'content', $template );
		}

		$GLOBALS['post'] = $original_post; // WPCS: override ok.

		woocommerce_product_loop_end();

		wp_reset_postdata();
		wc_reset_loop();

		if ( $echo ) {
			echo ob_get_clean();
		} else {
			return ob_get_clean();
		}
	}
}