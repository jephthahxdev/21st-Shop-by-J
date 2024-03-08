<?php
/**
 * Single functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan\Blog;

use Durotan\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Single initial
 */
class Post_Loop {
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
		add_filter( 'durotan_post_loop_content_class', array( $this, 'add_class_blog_article' ) );
		add_action( 'durotan_after_open_post_loop_content', array( $this, 'get_entry_header' ), 10 );

		add_action( 'durotan_after_open_post_loop_content', array( $this, 'loop_meta' ), 30 );
		add_action( 'durotan_after_open_post_loop_content', array( $this, 'loop_title' ), 35 );
	}

	/**
	 * Get entry header
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function get_entry_header() {
		global $count;
		$blog_type 	 = Helper::get_option( 'blog_type' );
		$blog_layout = Helper::get_option( 'blog_layout' );

		if ( ! has_post_thumbnail() ) {
			return;
		}

		$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
		$get_image         = wp_get_attachment_image( $post_thumbnail_id, 'durotan-blog-grid' );

		if( $blog_type == 'listing' && $count == 1 && ( $blog_layout !== 'full-content' || is_active_sidebar( 'blog-sidebar' ) ) ) {
			$get_image     = wp_get_attachment_image( $post_thumbnail_id, 'durotan-featured-image-classic' );
		}

		if ( empty( $get_image ) ) {
			return;
		}

		echo sprintf(
			'<a class="post__thumbnail" href="%s">%s</a>',
			esc_url( get_permalink() ),
			$get_image
		);
	}

	/**
	 * Get loop meta
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function loop_meta() {
		echo '<div class="post__meta entry-meta">';

		if( ! is_wp_error( get_the_category() ) && get_the_category() ) {
			\Durotan\Blog\Helper::meta_cat();
			\Durotan\Blog\Helper::meta_divider();
		}
		\Durotan\Blog\Helper::meta_date();
		\Durotan\Blog\Helper::meta_divider();
		\Durotan\Blog\Helper::meta_author();
		echo '</div>';

	}

	/**
	 * Get loop title
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function loop_title() {
		$title = wp_trim_words( get_the_title(), apply_filters( 'durotan_post_loop_title', 10 ) );

		echo '<h5 class="post__title entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">'. esc_html( $title ) . '</a></h5>';
	}

	/**
	 * Add class article.
	 *
	 * @since 1.0.0
	 *
	 * @return object
	 */
	public function add_class_blog_article( $classes ){
		global $count;
		$paged = get_query_var('paged');

		if ( Helper::is_blog() ) {
			$blog_type = Helper::get_option( 'blog_type' );
			$blog_layout = Helper::get_option( 'blog_layout' );

			if( $blog_type === 'grid' ) {
				$classes = join( ' ', get_post_class( 'blog-wrapper col-xs-12 col-sm-6 col-md-4', get_the_ID() ) );
			}elseif($blog_type === 'listing'){
				if ( $count == 1 && $paged == 0 ) {
					$classes = join( ' ', get_post_class( 'post post--large col-xs-12', get_the_ID() ) );
				}elseif ( ( $count == 2 || $count == 3 ) && $paged == 0 ) {
					$classes = join( ' ', get_post_class( 'post post--small col-xs-12 col-sm-6', get_the_ID() ) );
				}else {
					$classes = join( ' ', get_post_class( 'post col-xs-12', get_the_ID() ) );
				}

				if ( $blog_layout === 'full-content' || ! is_active_sidebar( 'blog-sidebar' )) {
					$classes = join( ' ', get_post_class( 'post col-xs-12', get_the_ID() ) );
				}
			}else{
				$classes = join( ' ', get_post_class( 'post col-xs-12', get_the_ID() ) );
			}

			if ( get_post()->post_content == '' ) {
				$classes .= ' no-content';
			}

			if ( get_the_title() == '' ) {
				$classes .= ' no-title';
			}
		} elseif( is_search() ) {
			$classes = join( ' ', get_post_class( 'blog-wrapper col-xs-12', get_the_ID() ) );
		} else{
			$classes = join( ' ', get_post_class( 'blog-wrapper', get_the_ID() ) );
		}

		return $classes;
	}
}
