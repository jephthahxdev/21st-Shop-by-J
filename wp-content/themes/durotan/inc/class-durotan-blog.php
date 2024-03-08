<?php
/**
 * Blog functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Blog {
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
		add_action( 'wp', array( $this, 'add_actions' ) );
	}

	/**
	 * Add actions
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_actions() {
		$this->get( 'featured' );
		$this->get( 'archive' );
		$this->get( 'post' );
		$this->get( 'post_loop' );
		$this->get( 'related_posts' );

	}

	/**
	 * Get Durotan Page Template Class.
	 *
	 * @since 1.0.0
	 *
	 * @return object
	 */
	public function get( $class ) {
		switch ( $class ) {
			case 'featured':
				if ( \Durotan\Helper::is_blog() && Helper::get_option( 'featured_content' ) )  {
					return \Durotan\Blog\Featured::instance();
				}
				break;
			case 'archive':
				if ( \Durotan\Helper::is_blog() ) {
					return \Durotan\Blog\Archive::instance();
				}
				break;

			case 'post':
				if ( is_singular( 'post' ) ) {
					return \Durotan\Blog\Post::instance();
				}
				break;

			case 'post_loop':
				return \Durotan\Blog\Post_Loop::instance();
				break;

			case 'related_posts':
				if ( is_singular( 'post' ) && Helper::get_option( 'related_posts' ) ) {
					return \Durotan\Blog\Related_Posts::instance();
				}
				break;

		}

	}
}
