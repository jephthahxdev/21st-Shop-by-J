<?php
/**
 * Page Header functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Durotan
 */

namespace Durotan;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Page Header
 *
 */
class Page_Header {
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
		add_action( 'durotan_after_close_site_header', array( $this, 'show_page_header' ), 20 );
		add_action( 'get_the_archive_title', array( $this, 'the_archive_title' ), 30 );

		add_action( 'durotan_after_open_page_header', array( $this, 'show_image' ), 30 );

		add_action( 'durotan_page_header_content_item', array( $this, 'open_content' ), 10 );
		add_action( 'durotan_page_header_content_item', array( $this, 'show_content' ), 20 );
		add_action( 'durotan_page_header_content_item', array( $this, 'close_content' ), 30 );

		add_filter( 'body_class', array( $this, 'body_classes_catalog_page' ) );
		add_filter( 'durotan_page_header_class', array( $this, 'durotan_add_class_page_header' ) );

		add_filter( 'woocommerce_breadcrumb_defaults',  array( $this, 'change_icon_delimiter_breadcrumb' ) );

	}

	/**
	 * Show page header
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function show_page_header() {
		if ( ! apply_filters( 'durotan_get_page_header', true ) ) {
			return;
		}

		if ( is_404() ) {
			return;
		}

		if ( ! $this->has_items() ) {
			return;
		}

		get_template_part( 'template-parts/page-header/page-header' );
	}

	/**
	 * Show archive title
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function the_archive_title( $title ) {
		if ( is_search() ) {
			$title = sprintf( esc_html__( 'Search Results for: %s', 'durotan' ), get_search_query() );
		} elseif ( is_404() ) {
			$title = sprintf( esc_html__( 'Page Not Found', 'durotan' ) );
		} elseif ( is_page() ) {
			$title = get_the_title(Helper::get_post_ID());
		} elseif ( is_home() && is_front_page() ) {
			$title = esc_html__( 'The Latest Posts', 'durotan' );
		} elseif ( is_home() && ! is_front_page() ) {
			$title = get_the_title( intval( get_option( 'page_for_posts' ) ) );
		} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
			$current_term = get_queried_object();
			if ( $current_term && isset( $current_term->term_id ) && $current_term->taxonomy == 'product_cat' ) {
				$title = $current_term->name;
			} else {
				$title = get_the_title( intval( get_option( 'woocommerce_shop_page_id' ) ) );
			}
		} elseif ( function_exists( 'is_cart' ) && is_cart() ) {
			$title = esc_html__( 'Shopping Cart', 'durotan' );
		} elseif ( is_single() ) {
			$title = get_the_title();
		} elseif ( is_tax() || is_category() ) {
			$title = single_term_title( '', false );
		}

		$classes = get_body_class();

		if ( in_array( 'blog' ,$classes) ) {
			if ( ! empty( $title = Helper::get_option( 'blog_header_title' ) ) ) {
				$title = wp_kses_post( $title );
			} else {
				$title = esc_html__( 'The Latest Posts', 'durotan' );
			}
		}

		if( is_home() && is_front_page() ) {
			return sprintf( '<h2 class="page-header__title">%s</h2>', $title );
		}else {
			return sprintf( '<h1 class="page-header__title">%s</h1>', $title );
		}
	}

	/**
	 * Show page header
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_items() {
		if ( ! isset( $this->items ) ) {
			$items = [];

			if ( \Durotan\Helper::is_blog() ) {
				$page_header = Helper::get_option( 'blog_page_header' );

				if ( intval( $page_header ) ) {
					$items = Helper::get_option( 'blog_page_header_els' );
				}

			} elseif ( \Durotan\Helper::is_catalog() ) {
				$page_header = Helper::get_option( 'catalog_page_header' );

				if ( ! empty( $page_header ) ) {
					$items = Helper::get_option( 'catalog_page_header_els' );

					if ( $page_header == 'layout-2' ) {
						$items['bg_image'] = Helper::get_option( 'catalog_page_header_image' );
					}
				}
			} elseif ( is_page() ) {
				$items[] = 'title';
			}

			$items = $this->custom_items( $items );
			$this->items = $items;

		}

		return apply_filters( 'durotan_get_page_header_elements', $this->items );

	}

	/**
	 * Check has items
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function has_items() {
		return count( $this->get_items() );
	}

	/**
	 * Custom page header
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function custom_items( $items ) {
		if ( empty( $items ) ) {
			return [];
		}

		$get_id = Helper::get_post_ID();

		if ( get_post_meta( $get_id, 'durotan_hide_page_header', true ) ) {
			return [];
		}

		if ( get_post_meta( $get_id, 'durotan_hide_breadcrumb', true ) ) {
			$key = array_search( 'breadcrumb', $items );
			if ( $key !== false ) {
				unset( $items[ $key ] );
			}
		}

		if ( get_post_meta( $get_id, 'durotan_hide_title', true ) ) {
			$key = array_search( 'title', $items );
			if ( $key !== false ) {
				unset( $items[ $key ] );
			}
		}

		return $items;
	}

	/**
	 * Open page header
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function open_content() {
		$classes = 'container';

		if( \Durotan\Helper::is_catalog() ) {
			$classes = Helper::get_option( 'catalog_width' );
		}elseif( is_singular('product') ){
			$classes = 'durotan-container';
		}

		printf( '<div class="page-header__inner %s">', esc_attr( $classes ) );
	}

	/**
	 * Close page header
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function close_content() {
		echo '</div>';
	}

	/**
	 * Show content
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function show_content() {
		$items = $this->get_items();

		$layout =  Helper::get_option( 'catalog_page_header' );

		if ( \Durotan\Helper::is_catalog() && $layout == 'layout-3' ) {
			echo '<div class="page-header--top">';
		}
		if ( in_array( 'breadcrumb', $items ) ) {
			\Durotan\Theme::instance()->get( 'breadcrumbs' )->breadcrumbs();
		}

		if ( in_array( 'title', $items ) ) {
			the_archive_title();
		}

		if ( \Durotan\Helper::is_catalog() && $layout == 'layout-3' ) {
			echo '</div>';
		}

		if ( in_array( 'description', $items ) ) {
			if ( \Durotan\Helper::is_blog() && empty( Helper::get_option( 'blog_header_description' ) ) ) {
				return;
			}

			$description = strip_tags( get_the_archive_description() );

			if ( \Durotan\Helper::is_blog() ) {
				$description = Helper::get_option( 'blog_header_description' );
			}

			if ( \Durotan\Helper::is_catalog() ) {
				// Truncate $content to $max_char
				$description = wp_trim_words( $description, Helper::get_option( 'catalog_page_header_description_limit' ) );
			}

			echo '<div class="page-header__description">'. $description .'</div>';
		}
	}

	/**
	 * Change delimiter breadcrumb
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function change_icon_delimiter_breadcrumb( $attr ) {
		$attr['delimiter'] = '<span class="delimiter">&#47;</span>';

		return $attr;
	}

	/**
	 * Show background image
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function show_image() {
		$items = $this->get_items();

		if ( isset( $items['bg_image'] ) && ! empty( $items['bg_image'] ) ) {
			echo '<div class="featured-image" style="background-image: url(' . esc_url( $items['bg_image'] ) . ')"></div>';
		}

	}

	/**
	 * Show archive descriptions
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function the_archive_description() {
		$description = get_the_archive_description();

		if ( ( is_home() || is_front_page() ) && empty( $description ) ) {
			$description = wp_kses_post( Helper::get_option( 'blog_header_description' ) );
		}

		echo sprintf( '<div class="page-header__desc">%s</div>', strip_tags( $description ) );
	}

	/**
	 * Add class
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function body_classes_catalog_page( $classes ) {
		if( \Durotan\Helper::is_catalog() && intval( Helper::get_option( 'catalog_page_header_transparent' ) ) ) {
			$classes[] = 'header-transparent';
		}

		if( !\Durotan\Helper::is_catalog() && Helper::get_option( 'blog_page_header' ) && is_page()) {
			if ( ! get_post_meta( Helper::get_post_ID(), 'durotan_hide_page_header', true )) {
				$classes[] = 'has-pageheader';
			}
		}

		return $classes;
	}

	/**
	 * Add class
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function durotan_add_class_page_header( $classes ) {
		if ( is_search() ) {
			$classes .= ' search-header';
		} elseif( \Durotan\Helper::is_catalog() ) {
			$classes .= ' page-header__catalog-page';

			if ( ! empty( $layout = Helper::get_option( 'catalog_page_header' ) ) ) {
				$classes .= ' page-header__catalog--' . $layout;
			}

			if ( ! get_post_meta( Helper::get_post_ID(), 'catalog_page_header_image', true ) && Helper::get_option( 'catalog_page_header' ) == 'layout-2') {
				$classes .= ' page-header__catalog--has-featured-image';
			}
		}

		return $classes;
	}
}
