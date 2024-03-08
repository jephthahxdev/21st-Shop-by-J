<?php
/**
 * WooCommerce Notices template hooks.
 *
 * @package Durotan
 */

namespace Durotan\WooCommerce\Modules;

use Durotan\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of WooCommerce Notices
 */

class Notices {
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
        add_filter( 'durotan_wp_script_data', array( $this, 'notices_script_data' ) );
   }

    /**
    * Get notices script data
    *
    * @since 1.0.0
    *
    * @param $data
    *
    * @return array
    */
    public function notices_script_data( $data ) {

        if ( Helper::get_option( 'added_to_cart_notice' ) !== 'none' ) {
            $data['added_to_cart_notice']['added_to_cart_notice_layout'] = Helper::get_option( 'added_to_cart_notice' );
            $data['added_to_cart_notice']['added_to_cart_notice_type'] = Helper::get_option( 'header_cart_behaviour' );
        }

        return $data;
    }
}
