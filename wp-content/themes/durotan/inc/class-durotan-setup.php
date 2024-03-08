<?php
/**
 * Durotan functions and definitions.
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
 * Durotan after setup theme
 */
class Setup {
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
		add_action( 'after_setup_theme', array( $this, 'setup_theme' ), 2 );
	}

	/**
	 * Setup theme
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function setup_theme() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on durotan, use a find and replace
	 * to change  'durotan' to the name of your theme in all the template files.
	 */
		load_theme_textdomain( 'durotan', get_template_directory() . '/lang' );

		// Theme supports
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		add_theme_support( 'customize-selective-refresh-widgets' );

		add_editor_style( 'assets/css/editor-style.css' );

		// Load regular editor styles into the new block-based editor.
		add_theme_support( 'editor-styles' );

		// Load default block styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for responsive embeds.
		add_theme_support( 'responsive-embeds' );

		add_theme_support( 'align-wide' );

		add_theme_support( 'align-full' );

		add_image_size( 'durotan-hotspot-image', 170, 208, true );
		add_image_size( 'durotan-image-swatches-slider', 70, 86, true );
		add_image_size( 'durotan-featured-image', 1560, 670, true );
		add_image_size( 'durotan-featured-posts', 555, 325, true );
		add_image_size( 'durotan-featured-image-classic', 770, 451, true );
		add_image_size( 'durotan-blog-grid', 350, 205, true );
		add_image_size( 'durotan-thumbnail-mini-cart', 60, 73, true );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary'    => esc_html__( 'Primary Menu', 'durotan' ),
			'secondary'  => esc_html__( 'Secondary Menu', 'durotan' ),
			'hamburger'  => esc_html__( 'Hamburger Menu', 'durotan' ),
			'mobile'     => esc_html__( 'Mobile Menu', 'durotan' ),
		) );
	}

	/**
	 * Set the $content_width global variable used by WordPress to set image dimennsions.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function setup_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'durotan_content_width_layout', 640 );
	}
}
