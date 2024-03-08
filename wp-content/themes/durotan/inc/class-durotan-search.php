<?php
/**
 * Campaign functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Campaign initial
 *
 */
class Search {
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
		add_action( 'durotan_after_open_search_loop', array( $this, 'open_post_list' ) );
		add_action( 'durotan_before_close_search_loop', array( $this, 'close_post_list' ) );

		add_action( 'durotan_before_close_search_loop', array( '\Durotan\Blog\Helper', 'durotan_pagination' ) );
	}

	/**
	 * Open post list
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function open_post_list() {
		echo '<div class="durotan-posts-list row">';
	}

	/**
	 * Close post list
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function close_post_list() {
		echo '</div>';
	}

	/**
	 * Pagination view
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function durotan_pagination() {
		$blog_pagination_type = Helper::get_option( 'blog_pagination_type' );
		if( $blog_pagination_type === 'load' )
		{
			echo Helper::load_pagination();
		}else {
			echo Helper::numeric_pagination();
		}
	}
}
