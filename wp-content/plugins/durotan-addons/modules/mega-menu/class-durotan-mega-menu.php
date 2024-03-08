<?php

namespace Durotan\Addons\Modules;

class Mega_Menu {
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
		$this->load();
	}

	/**
	 * Load files
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function load() {
		require_once DUROTAN_ADDONS_DIR . 'modules/mega-menu/class-durotan-mega-menu-walker.php';
		require_once DUROTAN_ADDONS_DIR . 'modules/mega-menu/class-durotan-mega-menu-socials-walker.php';
	}
}