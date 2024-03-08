<?php
/**
 * Durotan Addons Modules functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan\Addons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Addons Modules
 */
class Modules {

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
	 * Includes files
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function includes() {
		\Durotan\Addons\Auto_Loader::register( [
			'Durotan\Addons\Modules\Mega_Menu'              		=> DUROTAN_ADDONS_DIR . 'modules/mega-menu/class-durotan-mega-menu.php',
			'Durotan\Addons\Modules\Mega_Menu\Settings'     		=> DUROTAN_ADDONS_DIR . 'modules/mega-menu/class-durotan-mega-menu-settings.php',
			'Durotan\Addons\Modules\Products_Filter\Settings'     	=> DUROTAN_ADDONS_DIR . 'modules/products-filter/class-durotan-products-filter-settings.php',
			'Durotan\Addons\Modules\Product_Deals'          		=> DUROTAN_ADDONS_DIR . 'modules/product-deals/class-durotan-product-deals.php',
			'Durotan\Addons\Modules\Product_Deals\Settings' 		=> DUROTAN_ADDONS_DIR . 'modules/product-deals/class-durotan-product-deals-settings.php',
			'Durotan\Addons\Modules\Size_Guide'             		=> DUROTAN_ADDONS_DIR . 'modules/size-guide/class-durotan-size-guide.php',
			'Durotan\Addons\Modules\Size_Guide\Settings'    		=> DUROTAN_ADDONS_DIR . 'modules/size-guide/class-durotan-size-guide-settings.php',
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
		$this->get( 'mega_menu', 'frontend' );
		$this->get( 'mega_menu', 'settings' );
		
		$this->get( 'product_filter' );

		$this->get( 'product_deals', 'frontend' );
		$this->get( 'product_deals', 'settings' );

		$this->get( 'size_guide', 'frontend' );
		$this->get( 'size_guide', 'settings' );
	}

	/**
	 * Get Modules Class instance
	 *
	 * @since 1.0.0
	 *
	 * @return object
	 */
	public function get( $class, $type = '' ) {
		switch ( $class ) {
			case 'mega_menu':
				if ( $type == 'settings' ) {
					if ( is_admin() ) {
						return \Durotan\Addons\Modules\Mega_Menu\Settings::instance();
					}
				} elseif ( get_option( 'durotan_mega_menu' ) != '1' ) {
					return \Durotan\Addons\Modules\Mega_Menu::instance();
				}

				break;

			case 'product_filter':
				return \Durotan\Addons\Modules\Products_Filter\Settings::instance();

				break;

			case 'product_deals':
				if ( $type == 'settings' ) {
					if ( is_admin() ) {
						return \Durotan\Addons\Modules\Product_Deals\Settings::instance();
					}
				} elseif ( get_option( 'durotan_product_deals' ) == 'yes' ) {
					return \Durotan\Addons\Modules\Product_Deals::instance();
				}

				break;

			case 'size_guide':
				if ( $type == 'settings' ) {
					if ( is_admin() ) {
						return \Durotan\Addons\Modules\Size_Guide\Settings::instance();
					}
				} elseif ( get_option( 'durotan_size_guide' ) == 'yes' ) {
					return \Durotan\Addons\Modules\Size_Guide::instance();
				}

				break;
		}
	}
}
