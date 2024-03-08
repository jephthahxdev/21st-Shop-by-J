<?php
/**
 * Durotan Addons init
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
 * Durotan Addons init
 *
 * @since 1.0.0
 */
class Addons {

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
		add_action( 'plugins_loaded', array( $this, 'load_templates' ) );
	}

	/**
	 * Load Templates
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function load_templates() {
		$this->includes();
		spl_autoload_register( '\Durotan\Addons\Auto_Loader::load' );

		$this->add_actions();
	}

	/**
	 * Includes files
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function includes() {
		// Auto Loader
		require_once DUROTAN_ADDONS_DIR . 'class-durotan-addons-autoloader.php';
		\Durotan\Addons\Auto_Loader::register( [
			'Durotan\Addons\Helper'         => DUROTAN_ADDONS_DIR . 'class-durotan-addons-helper.php',
			'Durotan\Addons\Elementor'      => DUROTAN_ADDONS_DIR . 'inc/elementor/class-durotan-elementor.php',
			'Durotan\Addons\Widgets'        => DUROTAN_ADDONS_DIR . 'inc/widgets/class-durotan-addons-widgets.php',
			'Durotan\Addons\Modules'        => DUROTAN_ADDONS_DIR . 'modules/class-durotan-addons-modules.php',
			'Durotan\Addons\Product_Brands' => DUROTAN_ADDONS_DIR . 'inc/backend/class-durotan-addons-product-brand.php',
			'Durotan\Addons\Importer'       => DUROTAN_ADDONS_DIR . 'inc/backend/class-durotan-addons-importer.php',
		] );
	}

	/**
	 * Add Actions
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function add_actions() {
		// Before init action.
		do_action( 'before_durotan_init' );

		$this->get( 'product_brand' );

		$this->get( 'importer' );

		// Elmentor
		$this->get( 'elementor' );

		// Modules
		$this->get( 'modules' );

		// Widgets
		$this->get( 'widgets' );

		add_action( 'after_setup_theme', array( $this, 'addons_init' ), 20 );

		// Init action.
		do_action( 'after_durotan_init' );
	}

	/**
	 * Get Durotan Addons Class instance
	 *
	 * @since 1.0.0
	 *
	 * @return object
	 */
	public function get( $class ) {
		switch ( $class ) {
			case 'product_brand':
				if ( class_exists( 'WooCommerce' ) ) {
					return \Durotan\Addons\Product_Brands::instance();
				}
				break;
			case 'importer':
				if ( is_admin() ) {
					return \Durotan\Addons\Importer::instance();
				}
				break;
			case 'modules':
				return \Durotan\Addons\Modules::instance();
				break;

			case 'elementor':
				if ( did_action( 'elementor/loaded' ) ) {
					return \Durotan\Addons\Elementor::instance();
				}
				break;

			case 'widgets':
				return \Durotan\Addons\Widgets::instance();
				break;
		}
	}

	/**
	 * Get Durotan Addons Language
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function addons_init() {
		load_plugin_textdomain( 'durotan', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
	}
}
