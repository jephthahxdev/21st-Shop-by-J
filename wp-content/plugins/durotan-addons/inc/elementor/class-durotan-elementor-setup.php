<?php
/**
 * Elementor Global init
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Durotan
 */

namespace Durotan\Addons\Elementor;

/**
 * Integrate with Elementor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Setup {

	/**
	 * Instance
	 *
	 * @var $instance
	 */
	private static $instance;


	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
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

		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'styles' ] );
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'scripts' ] );

		add_action( 'elementor/elements/categories_registered', [ $this, 'add_category' ] );

	}

	/**
	 * Add Durotan category
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_category( $elements_manager ) {
		$elements_manager->add_category(
			'durotan',
			[
				'title' => esc_html__( 'Durotan', 'durotan' )
			]
		);
	}

	/**
	 * Register styles
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function styles() {
		wp_register_style( 'mapbox', DUROTAN_ADDONS_URL . 'assets/css/mapbox.css', array(), '1.0' );
		wp_register_style( 'mapboxgl', DUROTAN_ADDONS_URL . 'assets/css/mapbox-gl.css', array(), '1.0' );

		wp_register_style( 'magnific',  DUROTAN_ADDONS_URL . 'assets/css/magnific-popup.css', array(), '1.0' );

		wp_register_style( 'pagepiling',  DUROTAN_ADDONS_URL . 'assets/css/jquery.pagepiling.min.css', array(), '1.0' );

	}

	/**
	 * Register after scripts
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function scripts() {
		wp_register_script( 'durotan-coundown', DUROTAN_ADDONS_URL  . 'assets/js/plugins/jquery.coundown.js', array(), '1.0', true );
		wp_register_script( 'mapbox', DUROTAN_ADDONS_URL  . 'assets/js/plugins/mapbox.min.js', array(), '1.0', true );
		wp_register_script( 'mapboxgl', DUROTAN_ADDONS_URL  . 'assets/js/plugins/mapbox-gl.min.js', array(), '1.0', true );
		wp_register_script( 'mapbox-sdk', DUROTAN_ADDONS_URL  . 'assets/js/plugins/mapbox-sdk.min.js', array(), '1.0', true );

		wp_register_script( 'magnific', DUROTAN_ADDONS_URL . 'assets/js/plugins/jquery.magnific-popup.js', array(), '1.0', true );

		wp_register_script( 'pagepiling', DUROTAN_ADDONS_URL . 'assets/js/plugins/jquery.pagepiling.min.js', array(), '1.0', true );

		wp_register_script( 'durotan-frontend', DUROTAN_ADDONS_URL . 'assets/js/frontend.js', array( 'jquery', 'elementor-frontend' ), '20210802', true );

		wp_register_script( 'durotan-product-shortcode', DUROTAN_ADDONS_URL . '/assets/js/product-shortcode.js', array( 'jquery', 'elementor-frontend' ), '20210802', true );

	}
}
