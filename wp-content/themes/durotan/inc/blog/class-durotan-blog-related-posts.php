<?php
/**
 * Related post functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan\Blog;

use Durotan\Helper, Durotan\Blog\Post_Loop;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Related post initial
 */
class Related_Posts {
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
		add_action( 'durotan_after_close_post_content', array( $this, 'related_post' ), 10 );

		add_action( 'durotan_after_open_related_post_contents', array( $this, 'related_heading' ), 10 );

		add_action( 'durotan_after_open_related_post_contents', array( $this, 'open_related_post' ), 20 );
		add_action( 'durotan_before_close_related_post_contents', array( $this, 'close_related_post' ), 50 );
	}

	/**
	 * Related post heading
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function related_heading() {
		$title = Helper::get_option( 'related_posts_title' );

		if ( empty( $title ) ) {
			return;
		}

		echo sprintf( '<h3>%s</h3>', wp_kses_post( $title ) );
	}

	/**
	 * Related post open box
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function open_related_post() {
		echo '<div class="durotan-posts-list">';
	}

	/**
	 * Related post close box
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function close_related_post() {
		echo '</div>';
	}

	/**
	 * Get related_post title
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function related_post_title() {
		the_title( '<h5 class="post__title entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h5>' );
	}

	/**
	 * Related post
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_related_terms( $term, $post_id = null ) {
		$post_id     = $post_id ? $post_id : get_the_ID();
		$terms_array = array( 0 );

		$terms = wp_get_post_terms( $post_id, $term );
		foreach ( $terms as $term ) {
			$terms_array[] = $term->term_id;
		}

		return array_map( 'absint', $terms_array );
	}

	/**
	 * Related post
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function related_post() {
		// Only support posts
		if ( 'post' != get_post_type() ) {
			return;
		}

		global $post;
		$posts_numbers = apply_filters( 'durotan_related_post_number', 2 );

		$args = array(
			'post_type'           => 'post',
			'posts_per_page'      => $posts_numbers,
			'ignore_sticky_posts' => 1,
			'no_found_rows'       => 1,
			'order'               => 'rand',
			'post__not_in'        => array( $post->ID ),
			'tax_query'           => array(
				'relation' => 'OR',
				array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $this->get_related_terms( 'category', $post->ID ),
					'operator' => 'IN',
				),
				array(
					'taxonomy' => 'post_tag',
					'field'    => 'term_id',
					'terms'    => $this->get_related_terms( 'post_tag', $post->ID ),
					'operator' => 'IN',
				),
			),
		);

		get_template_part( 'template-parts/post/related-posts', null, $args );
	}

}
