<?php
/**
 * Hooks of Account.
 *
 * @package Durotan
 */

namespace Durotan\WooCommerce\Template;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of Account template.
 */
class Account {
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
		add_action( 'woocommerce_edit_account_form_start', array( $this, 'account_title' ), 20 );
	}

	/**
	 * Add title page account detail
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function account_title() {
		echo sprintf( '<h3 class="account-title title">%s</h3>', esc_html__( 'Edit Account', 'durotan' ) );
	}
}
