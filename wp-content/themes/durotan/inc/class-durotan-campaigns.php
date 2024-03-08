<?php
/**
 * Campaign functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Campaign initial
 *
 */
class Campaigns {
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
		add_action( 'durotan_before_open_site_header', array( $this, 'display_campaign_bar' ) );
	}

	/**
	 * Campaign bar
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function display_campaign_bar() {
		if ( ! apply_filters( 'durotan_get_campaign_bar', Helper::get_option( 'campaign_bar' ) ) ) return;

		get_template_part( 'template-parts/headers/campaigns' );
	}
}
