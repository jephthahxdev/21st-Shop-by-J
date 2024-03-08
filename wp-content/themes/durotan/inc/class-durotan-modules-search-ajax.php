<?php
/**
 * Search AJAX template hooks.
 *
 * @package Durotan
 */

namespace Durotan\Modules;

use Durotan\Helper;
use Durotan\Icon;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of Header Search Form template.
 */
class Search_Ajax {
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
		add_action( 'wp_footer', array( $this, 'search_modal' ), 40 );
		add_action( 'wc_ajax_durotan_instance_search_form', array( $this, 'instance_search_form' ) );
	}

	/**
	 * Display Search Modal
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function search_modal() {
		if ( Helper::get_option( 'header_search_layout' ) !== 'icon' ) return;
		?>
        <div id="search-modal" class="search-modal modal searched durotan-scrollbar">

			<?php get_template_part( 'template-parts/modals/search' ); ?>

        </div>
		<?php
	}

	/**
	 * Search form
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function instance_search_form() {
		$response    = array();
		$ajax_search_number = isset( $_POST['ajax_search_number'] ) ? intval( $_POST['ajax_search_number'] ) : 0;
		$class_title = $title_html = $view_more = '';
		$class_list  = 'result-list-found';

		if ( isset( $_POST['search_type'] ) && empty( $_POST['search_type'] ) ) {
			if( isset( $this->instance_search_every_things_result()['item']) ) {
				$response = $this->instance_search_every_things_result()['item'];
			}
			$response_number =  $this->instance_search_every_things_result()['number'];
			$response_html =  '<span class="search-result__count">('.intval( $response_number ).')</span>';
		} else {
			if( isset( $this->instance_search_products_result()['item']) ) {
				$response = $this->instance_search_products_result()['item'];
			}
			$response_number =  $this->instance_search_products_result()['number'];
			$response_html = '<span class="search-result__count">('.intval( $response_number ).')</span>';
		}

		if ( empty( $response ) ) {
			$response[]  = sprintf( '<div class="item-not-found col-md-12"><span class="text">%s</span>%s</div>', esc_html__( 'Nothing matches your search', 'durotan' ), \Durotan\Icon::get_svg( 'sad' ) );
			$class_title = 'not-found';
			$class_list  = 'result-list-not-found';
		}

		if ( Helper::get_option( 'header_search_layout' ) === 'icon' ) {
			$col = $ajax_search_number;
			$title_html = sprintf( '<h3 class="search-result__label %s">%s</h3>', esc_attr( $class_title ), esc_html__( 'Search Results:', 'durotan' ) );
			$classed = 'search-box';
		}else {
			$col = '1';
			$title_html = '';
			$classed = 'search-list';
			$class_list .= $class_list .' durotan-scrollbar';
		}

		if ( intval( $response_number ) !== 0 )
		{
			if ( $response_number == 1 ) {
				$label = esc_html__( 'All Result', 'durotan' );
			}else {
				$label = esc_html__( 'All Results', 'durotan' );
			}
			$view_more = sprintf( '<div class="search-result__view-more"><a href="#">%s %s</a></div>', $label, $response_html );
		}

		$output = sprintf( '<div class="%s %s">%s<ul class="durotan-posts-list products columns-%s">%s</ul>%s</div>', esc_attr( $classed ),esc_attr( $class_list ), $title_html, $col, implode( ' ', $response ), $view_more );

		wp_send_json_success( array( $output ) );
		die();
	}

	/**
	 * Search products result
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function instance_search_products_result() {
		$response           = array();
		$ajax_search_number = isset( $_POST['ajax_search_number'] ) ? intval( $_POST['ajax_search_number'] ) : 0;
		$args_sku           = array(
			'post_type'        => 'product',
			'posts_per_page'   => $ajax_search_number,
			'meta_query'       => array(
				array(
					'key'     => '_sku',
					'value'   => trim( $_POST['term'] ),
					'compare' => 'like',
				),
			),
			'suppress_filters' => 0,
		);

		$args_sku_count           = array(
			'post_type'        => 'product',
			'posts_per_page'   => -1,
			'meta_query'       => array(
				array(
					'key'     => '_sku',
					'value'   => trim( $_POST['term'] ),
					'compare' => 'like',
				),
			),
			'suppress_filters' => 0,
		);

		$args_variation_sku = array(
			'post_type'        => 'product_variation',
			'posts_per_page'   => $ajax_search_number,
			'meta_query'       => array(
				array(
					'key'     => '_sku',
					'value'   => trim( $_POST['term'] ),
					'compare' => 'like',
				),
			),
			'suppress_filters' => 0,
		);

		$args_variation_sku_count = array(
			'post_type'        => 'product_variation',
			'posts_per_page'   => -1,
			'meta_query'       => array(
				array(
					'key'     => '_sku',
					'value'   => trim( $_POST['term'] ),
					'compare' => 'like',
				),
			),
			'suppress_filters' => 0,
		);

		$args = array(
			'post_type'        => 'product',
			'posts_per_page'   => $ajax_search_number,
			's'                => trim( $_POST['term'] ),
			'suppress_filters' => 0,
		);

		$args_count = array(
			'post_type'        => 'product',
			'posts_per_page'   => -1,
			's'                => trim( $_POST['term'] ),
			'suppress_filters' => 0,
		);

		if ( function_exists( 'wc_get_product_visibility_term_ids' ) ) {
			$product_visibility_term_ids = wc_get_product_visibility_term_ids();
			$args['tax_query'][]         = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $product_visibility_term_ids['exclude-from-search'],
				'operator' => 'NOT IN',
			);

			$args_count['tax_query'][]         = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $product_visibility_term_ids['exclude-from-search'],
				'operator' => 'NOT IN',
			);
		}
		if ( isset( $_POST['cat'] ) && $_POST['cat'] != '0' ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $_POST['cat'],
			);

			$args_sku['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $_POST['cat'],
				),

			);

			$args_count['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $_POST['cat'],
			);

			$args_sku_count['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $_POST['cat'],
				),

			);
		}

		$products_sku           = get_posts( $args_sku );
		$products_s             = get_posts( $args );
		$products_variation_sku = get_posts( $args_variation_sku );

		$products_sku_count           = get_posts( $args_sku_count );
		$products_s_count             = get_posts( $args_count );
		$products_variation_sku_count = get_posts( $args_variation_sku_count );

		$products    = array_merge( $products_sku, $products_s, $products_variation_sku );
		$products_count    = array_merge( $products_sku_count, $products_s_count, $products_variation_sku_count );
		$product_ids = array();
		$count = 0;
		$classes = '';
		$response['number'] = count( array_unique($products_count, SORT_REGULAR) );

		foreach ( $products as $product ) {
			$id = $product->ID;
			if ( ! in_array( $id, $product_ids ) ) {
				$product_ids[] = $id;
				$productw   = wc_get_product( $id );
				if( $count == ( $ajax_search_number - 1 ) ) {
					$classes = 'last';
				}
				$response['item'][] = sprintf(
					'<li class="product %s"><div class="product__inner">' .
					'<div class="product__thumbnail">' .
						'<a href="%s" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">' .
							'%s' .
						'</a>' .
					'</div>' .
					'<div class="product__summary">' .
					'<h2 class="woocommerce-loop-product__title">' .
					'<a href="%s">%s</a>' .
					'</h2>' .
					'<span class="price">' .
					'%s' .
					'</span>' .
					'</div>' .
					'</div></li>',
					$classes,
					esc_url( $productw->get_permalink() ),
					$productw->get_image( 'shop_catalog' ),
					esc_url( $productw->get_permalink() ),
					$productw->get_title(),
					$productw->get_price_html()
				);
			}
			$count++;
			if( $count == $ajax_search_number ) break;
		}

		return $response;
	}


	/**
	 * Search every things result
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function instance_search_every_things_result() {
		$response           = array();
		$ajax_search_number = isset( $_POST['ajax_search_number'] ) ? intval( $_POST['ajax_search_number'] ) : 0;
		$args               = array(
			'post_type'        => 'any',
			'posts_per_page'   => $ajax_search_number,
			's'                => trim( $_POST['term'] ),
			'suppress_filters' => 0,
			'exclude'		   => array(1, 15),
		);
		$posts    = get_posts( $args );
		$post_ids = array();
		$count = 0;
		$classes = '';
		$price = '';
		$response['number'] = count( $posts );

		foreach ( $posts as $post ) {
			$id = $post->ID;
			if( $count == ( $ajax_search_number - 1 ) ) {
				$classes = 'last';
			}
			if ( ! in_array( $id, $post_ids ) ) {
				$post_ids[] = $id;
				$productw   = wc_get_product( $id );
				if ( $productw ) {
					$price = '<span class="price">' . $productw->get_price_html() .'</span>';
				}
				if ( Helper::get_option( 'header_search_layout' ) !== 'icon' ) {
					$thumb = get_the_post_thumbnail( $id, 'shop_catalog' );
				}else {
					$thumb = get_the_post_thumbnail( $id, 'durotan-blog-grid' );
				}
				$response['item'][] = sprintf(
					'<li class="product %s"><div class="product__inner">' .
					'<div class="product__thumbnail">' .
						'<a href="%s" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">' .
							'%s' .
						'</a>' .
					'</div>' .
					'<div class="product__summary">' .
					'<h2 class="woocommerce-loop-product__title">' .
					'<a href="%s">%s</a>' .
					'</h2>' .
					'%s' .
					'</div>' .
					'</div></li>',
					$classes,
					esc_url( get_the_permalink( $id ) ),
					$thumb,
					esc_url( get_the_permalink( $id ) ),
					$post->post_title,
					$price
				);
			}
			$count++;
			if( $count == $ajax_search_number ) break;
		}
		return $response;
	}
}
