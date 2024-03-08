<?php

namespace Durotan\Addons\Elementor;

use Durotan\Addons\Elementor\Control\Autocomplete;
use Durotan\Addons\Elementor\Controls\AjaxLoader;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Controls {

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
		// Include plugin files
		$this->includes();

		// Register controls
		add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );

		AjaxLoader::instance();
	}

	/**
	 * Include Files
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function includes() {
		\Durotan\Addons\Auto_Loader::register( [
				'Durotan\Addons\Elementor\Controls\AjaxLoader'  => DUROTAN_ADDONS_DIR . 'inc/elementor/controls/class-durotan-elementor-controls-ajaxloader.php',
				'Durotan\Addons\Elementor\Control\Autocomplete' => DUROTAN_ADDONS_DIR . 'inc/elementor/controls/class-durotan-elementor-autocomplete.php',
			]
		);

	}

	/**
	 * Register autocomplete control
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_controls() {
		$controls_manager = \Elementor\Plugin::$instance->controls_manager;
		$controls_manager->register_control( 'durotan_autocomplete', \Durotan\Addons\Elementor\Control\Autocomplete::instance() );

	}
}