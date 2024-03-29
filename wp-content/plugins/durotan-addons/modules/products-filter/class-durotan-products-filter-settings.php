<?php

namespace Durotan\Addons\Modules\Products_Filter;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main class of plugin for admin
 */
class Settings {

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

	private $option = 'durotan_products_filter';

	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'settings_api_init' ) );

		// Include plugin files
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );

	}

	/**
	 * Register widgets
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function register_widgets() {
		if ( get_option( 'durotan_products_filter' ) == '1' ) {
			return;
		}

		\Durotan\Addons\Auto_Loader::register( [
			'Durotan\Addons\Modules\Products_Filter'    => DUROTAN_ADDONS_DIR . 'modules/products-filter/class-durotan-products-filter.php',
		] );

		if ( class_exists( 'WooCommerce' ) ) {
			register_widget( new \Durotan\Addons\Modules\Products_Filter() );
		}
	}

	/**
	 * Add  field in 'Settings' > 'Writing'
	 * for enabling CPT functionality.
     *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function settings_api_init() {
		add_settings_section(
			'durotan_product_filter_section',
			'<span id="product-filter-options">' . esc_html__( 'Products Filter', 'durotan' ) . '</span>',
			array( $this, 'writing_section_html' ),
			'writing'
		);

		add_settings_field(
			$this->option,
			'<span class="product-filter-options">' . esc_html__( 'Products Filter', 'durotan' ) . '</span>',
			array( $this, 'disable_field_html' ),
			'writing',
			'durotan_product_filter_section'
		);
		register_setting(
			'writing',
			$this->option,
			'intval'
		);
	}

	/**
	 * Add writing setting section
     *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function writing_section_html() {
		?>
        <p>
			<?php esc_html_e( 'Use these settings to disable product filter of widget on catalog page', 'durotan' ); ?>
        </p>
		<?php
	}

	/**
	 * HTML code to display a checkbox true/false option
	 * for the Services CPT setting.
     *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function disable_field_html() {
		?>

        <label for="<?php echo esc_attr( $this->option ); ?>">
            <input name="<?php echo esc_attr( $this->option ); ?>"
                   id="<?php echo esc_attr( $this->option ); ?>" <?php checked( get_option( $this->option ), true ); ?>
                   type="checkbox" value="1"/>
			<?php esc_html_e( 'Disable Product Filter for this site.', 'durotan' ); ?>
        </label>

		<?php
	}
}