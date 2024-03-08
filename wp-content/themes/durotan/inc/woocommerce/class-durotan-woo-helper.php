<?php
/**
 * Woocommerce Helper functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan\WooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of Woocommerce Helper
 *
 */
class Helper {
	/**
	 * Instance
	 *
	 * @var $instance
	 */
	protected static $instance = null;

	/**
	 * Product loop
	 *
	 * @var $product_loop
	 */
	protected static $product_loop = null;

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

	/*
	 * Get shop page base URL
	 * @return string
	 */
	public static function get_page_base_url() {
		if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
			$link = home_url();
		} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) ) ) {
			$link = get_post_type_archive_link( 'product' );
		} elseif ( is_product_category() ) {
			$link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
		} elseif ( is_product_tag() ) {
			$link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
		} else {
			$queried_object = get_queried_object();
			$link           = get_term_link( $queried_object->slug, $queried_object->taxonomy );
		}

		return apply_filters( 'durotan_get_page_base_url', $link );
	}

	/**
	 * Get catalog layout
	 *
	 * @static
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_catalog_layout() {
		$layout = \Durotan\Helper::get_option( 'shop_catalog_layout' );
		return apply_filters( 'durotan_get_catalog_layout', $layout );
	}

	/**
	 * Get product loop layout
	 *
	 * @static
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_product_loop_layout() {
		if ( is_null( self::$product_loop ) ) {
			$layout = \Durotan\Helper::get_option( 'product_loop_layout' );

			$layout = apply_filters( 'durotan_get_product_loop_layout', $layout );
			self::$product_loop = $layout;
		}

		return self::$product_loop;
	}

	/**
	 * Get wishlist button
	 *
	 * @static
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function wishlist_button() {
		if ( shortcode_exists( 'wcboost_wishlist_button' ) ) {
			echo do_shortcode( '[wcboost_wishlist_button]' );
		} else if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
			echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
		}
	}

	/**
	 * Get Quick view button
	 *
	 * @static
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function quick_view_button() {
		global $product;

		echo sprintf(
			'<a href="%s" class="quick-view-button durotan-loop_button" data-target="quick-view-modal" data-toggle="modal" data-id="%d" data-text="%s">
				%s<span class="quick-view-text loop_button-text">%s</span>
			</a>',
			is_customize_preview() ? '#' : esc_url( get_permalink() ),
			esc_attr( $product->get_id() ),
			\Durotan\Helper::get_option( 'custom_quick_view' ) ? \Durotan\Helper::get_option( 'custom_quick_view_text' ) : esc_attr__( 'Quick View', 'durotan' ),
			\Durotan\Icon::get_svg( 'fullscreen', '', 'shop' ),
			\Durotan\Helper::get_option( 'custom_quick_view' ) ? \Durotan\Helper::get_option( 'custom_quick_view_text' ) : esc_attr__( 'Quick View', 'durotan' )
		);
	}

	/**
	 * Get compare button
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	public static function compare_button() {
		global $product;

		if( function_exists('wcboost_products_compare') ) {
			echo do_shortcode('[wcboost_compare_button]');
		} else if ( class_exists( 'YITH_Woocompare' ) ) {
			$button_text = get_option( 'yith_woocompare_button_text', esc_html__( 'Compare', 'durotan' ) );
			$product_id  = $product->get_id();
			$url_args    = array(
				'action' => 'yith-woocompare-add-product',
				'id'     => $product_id,
			);
			$lang        = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : false;
			if ( $lang ) {
				$url_args['lang'] = $lang;
			}

			$css_class   = 'compare-button compare durotan-loop_button';
			$label 		 = \Durotan\Icon::get_svg( 'repeat', '', 'shop' );
			$cookie_name = 'yith_woocompare_list';
			if ( function_exists( 'is_multisite' ) && is_multisite() ) {
				$cookie_name .= '_' . get_current_blog_id();
			}
			$the_list = isset( $_COOKIE[ $cookie_name ] ) ? json_decode( $_COOKIE[ $cookie_name ] ) : array();
			if ( in_array( $product_id, $the_list ) ) {
				$css_class          .= ' added';
				$url_args['action'] = 'yith-woocompare-view-table';
				$button_text        = esc_html__( 'Added', 'durotan' );
				$label 		 = \Durotan\Icon::get_svg( 'repeat-done', '', 'shop' );
			}

			$url = esc_url_raw( add_query_arg( $url_args, home_url() ) );

			printf( '<a href="%s" class="%s" data-text="%s" data-product_id="%d">%s<span class="loop_button-text">%s</span></a>',
					esc_url( $url ),
					esc_attr( $css_class ),
					esc_html( $button_text ),
					$product_id,
					$label,
					$button_text
				);
		} else {
			return;
		}
	}


	/**
	 * Get product loop title
	 *
	 * @static
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function product_loop_title() {
		echo '<h2 class="woocommerce-loop-product__title">';
			woocommerce_template_loop_product_link_open();
			the_title();
			woocommerce_template_loop_product_link_close();
		echo '</h2>';
	}

	/**
	 * Get IDs of the products that are set as new ones.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_new_product_ids() {
		// Load from cache.
		$product_ids = get_transient( 'durotan_woocommerce_products_new' );

		// Valid cache found.
		if ( false !== $product_ids && ! empty( $product_ids ) ) {
			return $product_ids;
		}

		$product_ids = array();

		// Get products which are set as new.
		$meta_query   = WC()->query->get_meta_query();
		$meta_query[] = array(
			'key'   => '_is_new',
			'value' => 'yes',
		);
		$new_products = new \WP_Query( array(
			'posts_per_page' => - 1,
			'post_type'      => 'product',
			'fields'         => 'ids',
			'meta_query'     => $meta_query,
		) );

		if ( $new_products->have_posts() ) {
			$product_ids = array_merge( $product_ids, $new_products->posts );
		}


		// Get products after selected days.
		if ( \Durotan\Helper::get_option( 'shop_badges_new' ) ) {
			$newness = intval( \Durotan\Helper::get_option( 'shop_badges_newness' ) );

			if ( $newness > 0 ) {
				$new_products = new \WP_Query( array(
					'posts_per_page' => - 1,
					'post_type'      => 'product',
					'fields'         => 'ids',
					'date_query'     => array(
						'after' => date( 'Y-m-d', strtotime( '-' . $newness . ' days' ) ),
					),
				) );

				if ( $new_products->have_posts() ) {
					$product_ids = array_merge( $product_ids, $new_products->posts );
				}
			}
		}

		set_transient( 'durotan_woocommerce_products_new', $product_ids, DAY_IN_SECONDS );

		return $product_ids;
	}

	/**
	 * Get product taxonomy
	 *
	 * @static
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function product_taxonomy( $taxonomy = 'product_cat' ) {
		global $product;

		$taxonomy = empty($taxonomy) ? 'product_cat' : $taxonomy;
		$terms = get_the_terms( $product->get_id(), $taxonomy );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			echo sprintf(
				'<a class="meta-cat" href="%s">%s</a>',
				esc_url( get_term_link( $terms[0] ), $taxonomy ),
				esc_html( $terms[0]->name ) );
		}
	}

	/**
	 * Get Product availability
	 *
	 * @static
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function product_availability() {
		global $product;

		$availability = $product->get_availability();

		if ( $availability['availability'] == '' ) {
			return;
		}
		echo sprintf(
			'<div class="durotan-stock">
				%s %s<span class="availability %s">%s</span>
			</div>',
			\Durotan\Icon::get_svg( 'package', '', 'shop' ),
			esc_attr__( 'Status: ', 'durotan' ),
			$availability['class'],
			$availability['availability']
		);
	}

	/**
	 * Get blog taxonomy list
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function taxs_list_search( $taxonomy = 'product_cat', $number = 5, $classes = array() ) {
		$classes[] = 'durotan-product-taxonomy-list';
		$view_all = esc_html__('All', 'durotan');
		$cats   = '';
		$output = array();
		$args = array(
			'number'  	 => $number,
			'exclude'	 => array( 15 ),
		);
		$term_id = 0;
		if ( is_tax( $taxonomy ) || is_category() ) {

			$queried_object = get_queried_object();
			if ( $queried_object ) {
				$term_id = $queried_object->term_id;
			}
		}

		$found    = false;
		$children = false;
		if( $term_id > 0 || ! empty( $_GET['product_cat'] ) ) {
			$parent_term = ! empty( $_GET['product_cat'] ) ? get_term_by( 'slug', $_GET['product_cat'], $taxonomy )->term_id : $term_id;

			$terms = get_term_children( $parent_term, $taxonomy );
			if ( ! is_wp_error( $terms ) && $terms ) {
				foreach ( $terms as $child ) {
					$cat = get_term_by( 'id', $child, $taxonomy );

					$cat_selected = '';
					if ( $cat->term_id == $term_id ) {
						$cat_selected = 'active';
						$found        = true;
					}
					$cats .= sprintf( '<li class="underline-hover %s"><a data-catslug="%s" href="%s">%s</a></li>',  esc_attr( $cat_selected ), $cat->slug, esc_url( get_term_link( $cat ) ), esc_html( $cat->name ) );
				}

				$children = true;
			}
		}

		if( ! $children ) {
			$categories = get_terms( $taxonomy, $args );
			if ( ! is_wp_error( $categories ) && $categories ) {
				foreach ( $categories as $cat ) {
					$cat_selected = '';
					if ( $cat->term_id == $term_id ) {
						$cat_selected = 'active';
						$found        = true;
					}
					$cats .= sprintf( '<li class="underline-hover %s"><a data-catslug="%s" href="%s">%s</a></li>',  esc_attr( $cat_selected ), $cat->slug, esc_url( get_term_link( $cat ) ), esc_html( $cat->name ) );
				}
			}
		}

		$cat_selected = $found ? '' : 'active';
		if ( $cats ) {
			$link_url = get_page_link( get_option( 'page_for_posts' ) );
			if ( 'posts' == get_option( 'show_on_front' ) ) {
				$link_url = home_url();
			} elseif ( \Durotan\Helper::is_catalog() ) {
				$link_url = wc_get_page_permalink( 'shop' );
			}
			$view_all_box = '';
			if ( ! empty( $view_all ) ) {
				$view_all_box = sprintf(
					'<li class="underline-hover %s"><a href="%s">%s</a></li>',
					esc_attr( $cat_selected ),
					esc_url( $link_url ),
					$view_all
				);
			}
			$output[] = sprintf(
				'<ul class="%s">%s%s</ul>',
				implode( ' ', $classes ),
				$view_all_box,
				$cats
			);
		}
		if ( $output ) {
			$output = apply_filters( 'durotan_category_taxs_list', $output );
			echo implode( "\n", $output );
		}
	}

	/**
	 * Get product video
	 *
	 * @static
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_product_video() {
		global $product;
		$video_position = get_post_meta( $product->get_id(), 'video_position', true );
		$video_image  = get_post_meta( $product->get_id(), 'video_thumbnail', true );
		$video_url    = get_post_meta( $product->get_id(), 'video_url', true );
		$video_width  = 1024;
		$video_height = 768;
		$video_html   = $video_class = '';

		if ( empty( $video_image ) ) {
			$video_thumb = wc_placeholder_img_src( 'shop_thumbnail' );
		} else {
			$video_thumb = wp_get_attachment_image_src( $video_image, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
			$video_thumb = $video_thumb[0];
		}

		if ( strpos( $video_url, 'youtube' ) !== false ) {
			$video_class = 'video-youtube';
		} elseif ( strpos( $video_url, 'vimeo' ) !== false ) {
			$video_class = 'video-vimeo';
		}

		// If URL: show oEmbed HTML
		if ( filter_var( $video_url, FILTER_VALIDATE_URL ) ) {

			$atts = array(
				'width'  => $video_width,
				'height' => $video_height
			);

			if ( $oembed = @wp_oembed_get( $video_url, $atts ) ) {
				$video_html = $oembed;
			} else {
				$atts = array(
					'src'    => $video_url,
					'width'  => $video_width,
					'height' => $video_height
				);

				$video_html = wp_video_shortcode( $atts );

			}
		}
		if ( $video_html ) {
			$vid_html   = '<div class="durotan-video-wrapper ' . esc_attr( $video_class ) . '">' . $video_html . '</div>';
			$video_html = '<div data-thumb="' . esc_url( $video_thumb ) . '" class="woocommerce-product-gallery__image durotan-product-video">' . $vid_html . '</div>';
		}
		if ( $video_html && \Durotan\Helper::get_option('product_layout') == 'v3'){
			$video_html = '<div class="product-image video" id="control-navigation-image-'.esc_attr($video_position).'">'. $video_html .'</div>';
		}
		return $video_html;
	}

	/**
	 * Get product video
	 *
	 * @static
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_nav_product_video() {
		global $product;
		$video_image  = get_post_meta( $product->get_id(), 'video_thumbnail', true );
		$video_position = get_post_meta( $product->get_id(), 'video_position', true );
		$video_url    = get_post_meta( $product->get_id(), 'video_url', true );
		if ( empty( $video_url ) ) {
			return;
		}

		if ( empty( $video_image ) ) {
			$video_thumb = wc_placeholder_img_src( 'shop_thumbnail' );
		} else {
			$video_thumb = wp_get_attachment_image_src( $video_image, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
			$video_thumb = $video_thumb[0];
		}
		$html = '<li><a class="video" href="#control-navigation-image-' . esc_attr( $video_position ) . '" data-number="'. esc_attr( $video_position ) . '">';
		$html .= '<img src="' . esc_url( $video_thumb ) . '"/><div class="i-video"></div>';
		$html .= '</a></li>';

		return $html;
	}

	/**
	 * Displays sharing buttons.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function product_share() {
		if ( !class_exists('\Durotan\Addons\Helper') && !method_exists('\Durotan\Addons\Helper', 'share_link') ) {
			return;
		}

		if ( ! \Durotan\Helper::get_option('product_sharing') ) {
			return;
		}

		$socials = \Durotan\Helper::get_option('product_sharing_socials');
		$whatsapp_number = \Durotan\Helper::get_option( 'product_sharing_whatsapp_number' );

		if (empty($socials)) {
			return;
		}

		?>
		<div class="product-share share">
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
	 * Content limit
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_content_limit( $num_words, $more = "&hellip;", $content = '' ) {
		$content = empty( $content ) ? get_the_content() : $content;
		if(empty($content)){
			return;
		}
		// Strip tags and shortcodes so the content truncation count is done correctly
		$content = strip_tags(
			strip_shortcodes( $content ), apply_filters(
				'durotan_content_limit_allowed_tags', '<script>,<style>'
			)
		);

		// Remove inline styles / scripts
		$content = trim( preg_replace( '#<(s(cript|tyle)).*?</\1>#si', '', $content ) );

		// Truncate $content to $max_char
		$content = wp_trim_words( $content, $num_words );

		if ( $more ) {
			$output = sprintf(
				'<p>%s <a href="%s" class="read-more" title="%s">%s</a></p>',
				$content,
				get_permalink(),
				sprintf( esc_html__( 'Continue reading &quot;%s&quot;', 'durotan' ), the_title_attribute( 'echo=0' ) ),
				$more
			);
		} else {
			$output = sprintf( '<p>%s</p>', $content );
		}

		return $output;
	}

	/**
	 * Displays sale percent
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function product_sale_percent() {
		global $product;
		$type       = \Durotan\Helper::get_option( 'shop_badges_sale_type' );
		$text       = \Durotan\Helper::get_option( 'shop_badges_sale_text' );
		$percentage = 0;
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

			$percentage = $max_percentage ? $max_percentage : $percentage;
		}else{
			$saved      = (float) $product->get_regular_price() - (float) $product->get_sale_price();
			$percentage = round( ( $saved / (float) $product->get_regular_price() ) * 100 );
		}

		if ( 'percent' == $type ) {
			$output = $percentage ? '<span class="durotan-badges onsale"> ' . $percentage . '%</span>' : '';
		} elseif ( 'both' == $type ) {
			$output = '<span class="durotan-badges onsale"><span class="percent">' . $percentage . '%</span><span class="text"> ' . wp_kses_post( $text ) . '</span></span>';
		} else {
			$output = '<span class="durotan-badges onsale">' . wp_kses_post( $text ) . '</span>';
		}

		echo ! empty( $output ) ? $output : '';
	}

}
