<?php
/**
 * Block Editor functions
 *
 * @package Durotan
 */

namespace Durotan\Admin;

use Durotan\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Block Editor
 *
 */
class Block_Editor {
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
		add_action( 'enqueue_block_editor_assets', array( $this, 'block_editor_styles' ) );
	}

	/**
	 * Enqueue editor styles for Gutenberg
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 *
	 */
	public function block_editor_styles() {
		wp_enqueue_style( 'durotan-block-editor-style', get_template_directory_uri() . '/assets/css/editor-blocks.css' );
		wp_enqueue_style( 'durotan-block-editor-fonts', Helper::get_google_fonts(), array(), '20180831' );
	}
}
