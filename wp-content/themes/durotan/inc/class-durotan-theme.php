<?php
/**
 * Durotan init
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
 * Durotan theme init
 */
final class Theme {
	/**
	 * Instance
	 *
	 * @var $instance
	 */
	private static $instance = null;

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
		require_once get_template_directory() . '/inc/class-durotan-autoload.php';

		if ( is_admin() ) {
			require_once get_template_directory() . '/inc/libs/class-tgm-plugin-activation.php';
		}
	}

	/**
	 * Hooks to init
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init() {
		// Before init action.
		do_action( 'before_durotan_init' );

		// Setup
		$this->get( 'autoload' );
		$this->get( 'setup' );
		$this->get( 'entry' );
		$this->get( 'woocommerce' );

		// Mobile
		$this->get( 'mobile' );

		$this->get( 'maintenance' );

		// Header
		$this->get( 'header' );
		$this->get( 'campaigns' );

		//Footer
		$this->get( 'footer' );

		// Page Header
		$this->get( 'page_header' );
		$this->get( 'breadcrumbs' );

		// Sidebar
		$this->get( 'widgets' );

		// Layout & Style
		$this->get( 'layout' );
		$this->get( 'dynamic_css' );

		// Comments
		$this->get( 'comments' );

		// Options
		$this->get( 'options' );

		// Blog
		$this->get( 'blog' );

		// Search
		$this->get( 'search' );

		// Modules
		$this->get( 'search_ajax' );

		// Admin
		$this->get( 'admin' );

		// Init action.
		do_action( 'after_durotan_init' );

	}

	/**
	 * Get Durotan Class.
	 *
	 * @since 1.0.0
	 *
	 * @return object
	 */
	public function get( $class ) {
		switch ( $class ) {
			case 'woocommerce':
				if ( class_exists( 'WooCommerce' ) ) {
					return WooCommerce::instance();
				}
				break;

			case 'options':
				return Options::instance();
				break;

			case 'search_ajax':
				return \Durotan\Modules\Search_Ajax::instance();
				break;

			default :
				$class = ucwords( $class );
				$class = "\Durotan\\" . $class;
				if ( class_exists( $class ) ) {
					return $class::instance();
				}
				break;
		}

	}


	/**
	 * Setup the theme global variable.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function setup_prop( $args = array() ) {
		$default = array(
			'modals' => array(),
		);

		if ( isset( $GLOBALS['durotan'] ) ) {
			$default = array_merge( $default, $GLOBALS['durotan'] );
		}

		$GLOBALS['durotan'] = wp_parse_args( $args, $default );
	}

	/**
	 * Get a propery from the global variable.
	 *
	 * @param string $prop Prop to get.
	 * @param string $default Default if the prop does not exist.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_prop( $prop, $default = '' ) {
		self::setup_prop(); // Ensure the global variable is setup.

		return isset( $GLOBALS['durotan'], $GLOBALS['durotan'][ $prop ] ) ? $GLOBALS['durotan'][ $prop ] : $default;
	}

	/**
	 * Sets a property in the global variable.
	 *
	 * @param string $prop Prop to set.
	 * @param string $value Value to set.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function set_prop( $prop, $value = '' ) {
		if ( ! isset( $GLOBALS['durotan'] ) ) {
			self::setup_prop();
		}

		if ( ! isset( $GLOBALS['durotan'][ $prop ] ) ) {
			$GLOBALS['durotan'][ $prop ] = $value;

			return;
		}

		if ( is_array( $GLOBALS['durotan'][ $prop ] ) ) {
			if ( is_array( $value ) ) {
				$GLOBALS['durotan'][ $prop ] = array_merge( $GLOBALS['durotan'][ $prop ], $value );
			} else {
				$GLOBALS['durotan'][ $prop ][] = $value;
				array_unique( $GLOBALS['durotan'][ $prop ] );
			}
		} else {
			$GLOBALS['durotan'][ $prop ] = $value;
		}
	}
}
