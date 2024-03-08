<?php
/**
 * Plugin Name: Durotan Addons
 * Plugin URI: http://drfuri.com/plugins/durotan-addons.zip
 * Description: Extra elements for Elementor. It was built for Durotan theme.
 * Version: 1.0.3
 * Author: Drfuri
 * Author URI: http://drfuri.com/
 * License: GPL2+
 * Text Domain: durotan
 * Domain Path: /lang/
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! defined( 'DUROTAN_ADDONS_DIR' ) ) {
	define( 'DUROTAN_ADDONS_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'DUROTAN_ADDONS_URL' ) ) {
	define( 'DUROTAN_ADDONS_URL', plugin_dir_url( __FILE__ ) );
}

require_once DUROTAN_ADDONS_DIR . 'class-durotan-addons-plugin.php';

\Durotan\Addons::instance();