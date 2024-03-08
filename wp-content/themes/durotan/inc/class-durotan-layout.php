<?php
/**
 * Layout functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Layout initial
 *
 */
class Layout {
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
		add_filter( 'body_class', array( $this, 'body_classes' ) );

		add_action( 'durotan_after_open_site_content', array( $this, 'open_site_content_container' ), 90 );

		add_action( 'durotan_before_close_site_content', array( $this, 'close_site_content_container' ) );

		add_filter( 'durotan_get_sidebar', array( $this, 'has_sidebar' ) );

		add_filter( 'durotan_site_content_class', array( $this, 'site_content_class' ));

		add_action( 'elementor/theme/register_locations', array( $this, 'register_elementor_locations' ) );
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @since 1.0.0
	 *
	 * @param array $classes Classes for the body element.
	 *
	 * @return array
	 */
	public function body_classes( $classes ) {
		$header_type   = Helper::get_option( 'header_type' );
		$header_layout = Helper::get_option( 'header_layout' );
		$blog_type = Helper::get_option( 'blog_type' );

		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		$classes[] = $this->content_layout();

		if ( $this->content_layout() == 'full-content' ) {
			$classes[] .= 'no-sidebar';
		}

		if ( Helper::is_blog() ) {
			$classes[] = 'durotan-blog-page';

			if( $blog_type === 'grid' ) {
				$classes[] .= 'durotan-blog-grid';
			}elseif ( $blog_type === 'listing' ) {
				$classes[] .= 'blog-listing';
			}else{
				$classes[] .= 'blog-classic';
			}
		}

		if ( intval( Helper::get_option( 'header_sticky' ) ) ) {
			$classes[] = 'header-sticky';
		}

		$classes[] = 'header-' . $header_type;

		if ( $header_type == 'default' ) {
			$classes[] = 'header-' . $header_layout;
		}
		if ( (is_page() || \Durotan\Helper::is_catalog()) && ( $background = get_post_meta( Helper::get_post_ID(), 'durotan_header_background', true ) ) ) {
			if ( 'transparent' == $background ) {
				$classes[] = 'header-' . $background;

				$text_color = get_post_meta( Helper::get_post_ID(), 'durotan_header_text_color', true );
				if ( $text_color != 'default' ) {
					$classes[] = 'header-transparent-text-' . $text_color;
				}
			}
		}

		if ( $header_layout == 'v1' ) {
			$classes[] = 'header-has-smart-dot';
		}

		if ( Helper::get_option( 'footer_parallax' ) ) {
			$classes[] = 'footer-has-parallax';
		}

		if( Helper::is_page_elementor() ) {
			$classes[] = 'elementor-width-' . get_post_meta( Helper::get_post_ID(), 'durotan_content_width', true );
		}

		if( Helper::is_single_full_content_elementor() ) {
			$classes[] = 'elementor-width-container-single';
		}

		return $classes;
	}

	/**
	 * Print the open tags of site content container
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function open_site_content_container() {
		if( Helper::is_page_elementor() ) {
			return;
		}

		if( Helper::is_single_full_content_elementor() ) {
			return;
		}

		if( is_singular('post') && $this->content_layout() == 'full-content' && get_post_meta( get_the_ID(), '_elementor_edit_mode', true ) ) {
			return;
		}

		if ( is_singular('product') && Helper::get_option( 'product_layout' ) !== 'v7') {
			$container = Helper::get_option( 'single_product_width' );
			echo '<div class="'. esc_attr( $container ) .' product-content-container clearfix">';
		} elseif ( Helper::is_catalog() ) {
			$container = Helper::get_option( 'catalog_width' );

			echo '<div class="'. esc_attr( $container ) .' catalog-container clearfix">';
		} elseif ( is_page() ) {
			$container = get_post_meta( Helper::get_post_ID(), 'durotan_content_width', true );
			if ( ! $container ) {
				$container = 'container';
			}
			echo '<div class="' . esc_attr( $container ) . ' clearfix">';
		} else {
			echo '<div class="container clearfix">';
		}
	}

	/**
	 * Print the close tags of site content container
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function close_site_content_container() {
		if( Helper::is_page_elementor() ) {
			return;
		}

		if( Helper::is_single_full_content_elementor() ) {
			return;
		}

		echo '</div>';
	}

	/**
	 * Get site layout
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function content_layout() {
		$layout = 'full-content';

		if ( is_singular( 'post' ) ) {
			if ( ! is_active_sidebar( 'blog-sidebar' ) ) {
				$layout = 'full-content';
			} else {
				$layout = Helper::get_option( 'single_post_layout' );
			}
		} else if ( \Durotan\Helper::is_blog() ) {

			$blog_type = Helper::get_option( 'blog_type' );
			if ( $blog_type == 'grid' ) {
				$layout = 'full-content';
			}else {
				$layout = Helper::get_option( 'blog_layout' );
			}

			if ( ! is_active_sidebar( 'blog-sidebar' ) ) {
				$layout = 'full-content';
			}
		} elseif ( is_search() ) {
			if( ! empty( $_GET[ 'post_type' ] ) && $_GET[ 'post_type' ] == 'product' ) {
				if ( ! is_active_sidebar( 'catalog-sidebar' ) ) {
					$layout = 'full-content';
				} else {
					$layout = Helper::get_option( 'catalog_sidebar' );
				}
			} else {
				$blog_type = Helper::get_option( 'blog_type' );
				if ( $blog_type == 'grid' ) {
					$layout = 'full-content';
				}else {
					$layout = Helper::get_option( 'blog_layout' );
				}

				if ( ! is_active_sidebar( 'blog-sidebar' ) ) {
					$layout = 'full-content';
				}
			}

		} elseif ( \Durotan\Helper::is_catalog() && \Durotan\WooCommerce\Helper::get_catalog_layout() == 'grid' ) {
			if ( ! is_active_sidebar( 'catalog-sidebar' ) ) {
				$layout = 'full-content';
			} else {
				$layout = Helper::get_option( 'catalog_sidebar' );
			}
		}

		return apply_filters( 'durotan_site_layout', $layout );
	}

	/**
	 * Check has sidebar
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public function has_sidebar() {
		if( $this->content_layout() != 'full-content' ) {
			return true;
		}
		return false;
	}

	/**
	 * Add classed to site content
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function site_content_class($classes) {
		if( is_page() || \Durotan\Helper::is_catalog() || \Durotan\Helper::is_blog() ) {
			$top_spacing = get_post_meta( Helper::get_post_ID(), 'durotan_content_top_spacing', true );
			if ( ! empty($top_spacing) && $top_spacing != 'default' ) {
				$classes .= sprintf( ' %s-top-spacing', $top_spacing );
			}
			$bottom_spacing = get_post_meta( Helper::get_post_ID(), 'durotan_content_bottom_spacing', true );
			if ( ! empty($bottom_spacing) && $bottom_spacing != 'default' ) {
				$classes .= sprintf( ' %s-bottom-spacing', $bottom_spacing );
			}
		}

		return $classes;
	}

	function register_elementor_locations( $elementor_theme_manager ) {
		$elementor_theme_manager->register_location( 'header' );
		$elementor_theme_manager->register_location( 'footer' );
	}
}