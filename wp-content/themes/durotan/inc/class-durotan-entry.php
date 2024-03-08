<?php
/**
 * Entry functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan;
use \Durotan\Icon;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Header initial
 *
 */
class Entry {
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
		add_filter( 'wp_kses_allowed_html', array( $this,  'kses_post_allowed_html' ), 10, 2 );
		add_filter( 'get_search_form', array( $this, 'durotan_search_form' ) );

		add_filter( 'mc4wp_form_css_classes', array( $this, 'addclass_wc4wp' ) );
	}

	/**
	 * Custom wp kses post allowed html
	 *
	 * @param array $allowed
	 * @param string $context
	 * @return void
	 */
	public function kses_post_allowed_html( $allowed, $context ) {
		if ( 'post' != $context ) {
			return $allowed;
		}

		return array_replace_recursive(
			$allowed,
			array(
				'svg'              => array(
					'class'           => true,
					'aria-hidden'     => true,
					'aria-labelledby' => true,
					'role'            => true,
					'xmlns'           => true,
					'width'           => true,
					'height'          => true,
					'viewbox'         => true,
				),
				'g'                => array( 'fill'  => true ),
				'title'            => array( 'title' => true ),
				'path'             => array( 'd'     => true, 'fill'       => true,  ),
				'use'              => array( 'href'  => true, 'xlink:href' => true,  ),
			)
		);
	}

	/**
	 * Custom search form
	 *
	 * @param array $form
	 * @return void
	 */
	public function durotan_search_form( $form ) {
		$form = sprintf(
			'<form method="get" id="searchform" class="search-form" action="%s" >
					<label>
						<input type="search" class="search-field" placeholder="%s" value="" name="s">
					</label>
					<button type="submit" class="search-submit" value="Search">
						%s
						<span class="button-text screen-reader-text">%s</span>
					</button>
				</form>',
			home_url( '/' ),
			esc_attr__( 'Search...', 'durotan' ),
			Icon::get_svg( 'search', 'header-search__icon', 'shop' ),
			esc_attr__( 'Search', 'durotan' )
		);

		return $form;
	}

	/**
	 * Addclass to wc4wp
	 *
	 * @param string $classes
	 * @return void
	 */
	public function addclass_wc4wp($classes) {
		$classes[] = 'durotan-posts-newsletter__search';
		return $classes;
	}
}
