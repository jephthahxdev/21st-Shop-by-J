<?php
/**
 * Admin functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Mobile initial
 *
 */
class Admin {
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
		if ( is_admin() ) {
			$this->get( 'plugin' );
			$this->get( 'meta_boxes' );
			$this->get( 'block_editor' );
		}
	}

	/**
	 * Get Mobile Class Init.
	 *
	 * @since 1.0.0
	 *
	 * @return object
	 */
	public function get( $class ) {
		switch ( $class ) {
			case 'plugin':
				return \Durotan\Admin\Plugin_Install::instance();
				break;
			case 'meta_boxes':
				return \Durotan\Admin\Meta_Boxes::instance();
				break;
			case 'block_editor':
				return \Durotan\Admin\Block_Editor::instance();
				break;
		}
	}
}
