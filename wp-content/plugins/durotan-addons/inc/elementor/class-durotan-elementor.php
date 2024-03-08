<?php
/**
 * Elementor init
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Durotan
 */

namespace Durotan\Addons;

/**
 * Integrate with Elementor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elementor {

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
		$this->includes();
		$this->add_actions();
	}

	/**
	 * Includes files which are not widgets
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function includes() {
		\Durotan\Addons\Auto_Loader::register( [
			'Durotan\Addons\Elementor\Helper'                 => DUROTAN_ADDONS_DIR . 'inc/elementor/class-durotan-elementor-helper.php',
			'Durotan\Addons\Elementor\Setup'                  => DUROTAN_ADDONS_DIR . 'inc/elementor/class-durotan-elementor-setup.php',
			'Durotan\Addons\Elementor\Products'               => DUROTAN_ADDONS_DIR . 'inc/elementor/class-durotan-elementor-products.php',
			'Durotan\Addons\Elementor\Page_Settings'          => DUROTAN_ADDONS_DIR . 'inc/elementor/class-durotan-elementor-page-settings.php',
			'Durotan\Addons\Elementor\Widgets'                => DUROTAN_ADDONS_DIR . 'inc/elementor/class-durotan-elementor-widgets.php',
			'Durotan\Addons\Elementor\Controls'               => DUROTAN_ADDONS_DIR . 'inc/elementor/controls/class-durotan-elementor-controls.php',
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
		$this->get( 'setup' );
		$this->get( 'products' );
		$this->get( 'page_settings' );
		$this->get( 'widgets' );
		$this->get( 'controls' );
	}

	/**
	 * Get Durotan Elementor Class instance
	 *
	 * @since 1.0.0
	 *
	 * @return object
	 */
	public function get( $class ) {
		switch ( $class ) {
			case 'setup':
				return \Durotan\Addons\Elementor\Setup::instance();
				break;
			case 'page_settings':
				return \Durotan\Addons\Elementor\Page_Settings::instance();
				break;
			case 'widgets':
				return \Durotan\Addons\Elementor\Widgets::instance();
				break;
			case 'products':
				return \Durotan\Addons\Elementor\Products::instance();
				break;
			case 'controls':
				return \Durotan\Addons\Elementor\Controls::instance();
				break;
		}
	}
}
