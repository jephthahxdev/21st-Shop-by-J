<?php
/**
 * Register required, recommended plugins for theme
 *
 * @package Durotan
 */

namespace Durotan\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register required plugins
 *
 * @since  1.0
 */
class Plugin_Install {
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
		add_action( 'tgmpa_register', array( $this, 'register_required_plugins' ) );
	}


	/**
	 * Register required plugins
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_required_plugins() {
		$plugins = array(
			array(
				'name'               => esc_html__( 'Meta Box', 'durotan' ),
				'slug'               => 'meta-box',
				'required'           => true,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'               => esc_html__( 'Kirki', 'durotan' ),
				'slug'               => 'kirki',
				'required'           => true,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'               => esc_html__( 'WooCommerce', 'durotan' ),
				'slug'               => 'woocommerce',
				'required'           => true,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'               => esc_html__( 'Elementor Page Builder', 'durotan' ),
				'slug'               => 'elementor',
				'required'           => true,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'               => esc_html__( 'Durotan Addons', 'durotan' ),
				'slug'               => 'durotan-addons',
				'source'             => esc_url( 'https://github.com/drfuri/plugins/raw/main/durotan-addons.zip' ),
				'required'           => true,
				'force_activation'   => false,
				'force_deactivation' => false,
				'version'            => '1.0.3',
			),
			array(
				'name'               => esc_html__( 'Contact Form 7', 'durotan' ),
				'slug'               => 'contact-form-7',
				'required'           => false,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'               => esc_html__( 'MailChimp for WordPress', 'durotan' ),
				'slug'               => 'mailchimp-for-wp',
				'required'           => false,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'               => esc_html__( 'WCBoost - Wishlist', 'durotan' ),
				'slug'               => 'wcboost-wishlist',
				'required'           => false,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'               => esc_html__( 'WCBoost - Products Compare', 'durotan' ),
				'slug'               => 'wcboost-products-compare',
				'required'           => false,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'               => esc_html__( 'WCBoost - Variation Swatches', 'durotan' ),
				'slug'               => 'wcboost-variation-swatches',
				'required'           => false,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
		);

		$config  = array(
			'domain'       => 'durotan',
			'default_path' => '',
			'menu'         => 'install-required-plugins',
			'has_notices'  => true,
			'is_automatic' => false,
			'message'      => '',
			'strings'      => array(
				'page_title'                      => esc_html__( 'Install Required Plugins', 'durotan' ),
				'menu_title'                      => esc_html__( 'Install Plugins', 'durotan' ),
				'installing'                      => esc_html__( 'Installing Plugin: %s', 'durotan' ),
				'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'durotan' ),
				'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'durotan' ),
				'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'durotan' ),
				'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'durotan' ),
				'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'durotan' ),
				'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'durotan' ),
				'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'durotan' ),
				'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'durotan' ),
				'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'durotan' ),
				'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'durotan' ),
				'activate_link'                   => _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'durotan' ),
				'return'                          => esc_html__( 'Return to Required Plugins Installer', 'durotan' ),
				'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'durotan' ),
				'complete'                        => esc_html__( 'All plugins installed and activated successfully. %s', 'durotan' ),
				'nag_type'                        => 'updated',
			),
		);
		tgmpa( $plugins, $config );
	}
}
