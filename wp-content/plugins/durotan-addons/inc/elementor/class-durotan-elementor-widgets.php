<?php
/**
 * Elementor Widgets init
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Durotan
 */

namespace Durotan\Addons\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Widgets init
 *
 * @since 1.0.0
 */
class Widgets {

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
		spl_autoload_register( [ $this, 'autoload' ] );
		$this->add_actions();

	}

	/**
	 * Auto load widgets
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		if ( false === strpos( $class, 'Widgets' ) ) {
			return;
		}

		$path     = explode( '\\', $class );
		$filename = strtolower( array_pop( $path ) );

		$folder = array_pop( $path );

		if ( ! in_array( $folder, array( 'Widgets' ) ) ) {
			return;
		}

		$filename = str_replace( '_', '-', $filename );
		$filename = DUROTAN_ADDONS_DIR . 'inc/elementor/widgets/' . $filename . '.php';

		if ( is_readable( $filename ) ) {
			include( $filename );
		}
	}

	/**
	 * Hooks to init
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function add_actions() {
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
		add_action( 'elementor/widget/before_render_content', array( $this, 'load_product_layout' ));
	}

	/**
	 * Init Widgets
	 *
	 * @since 1.0.0
	 *
	 * @return object
	 */
	public function init_widgets() {
		$widgets_manager = \Elementor\Plugin::instance()->widgets_manager;

		$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Map() );
		$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Socials() );
		$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Contact_Form_7() );
		$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Icons_Box() );
		$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Banner() );
		$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Image_Box_Carousel() );
		$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Countdown() );
		$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Countdown_Banner() );
		$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Newsletter() );
		$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Newsletter_2() );
		$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Slides() );
		$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Slides_2() );
		$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Instagram_Grid() );
		$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Instagram_Carousel() );
		$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Twitter_Carousel() );
		$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Twitter_Carousel_2() );
		$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Video_Banner() );
		$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Image_Box() );
		$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Image_Box_2() );
		$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Image_Parallax() );
		$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Posts_Carousel() );
		$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Icon_Box_List() );

		if ( class_exists( 'WooCommerce' ) ) {
			$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Products_Group_Tabs() );
			$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Slider_Product() );
			$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Banner_Shoppable() );
			$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Product_Shortcode() );
			$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Product_Shortcode_2() );
			$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Product_Shortcode_3() );
			$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Product_Shortcode_4() );
			$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Product_Category() );
			$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Product_Carousel() );
			$widgets_manager->register_widget_type( new \Durotan\Addons\Elementor\Widgets\Product_Grid() );
		}
	}

	/**
	 * Init Widgets
	 *
	 * @since 1.0.0
	 *
	 * @return object
	 */
	public function load_product_layout($widget) {
		$widget_name	 = $widget->get_name();
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			switch($widget_name){
				case 'durotan-product-group-tab':
				case 'durotan-product-carousel':
				case 'durotan-product-grid':
					if ( function_exists( 'TA_WCVS' ) ) {
						TA_WCVS()->frontend();
					}
					if(class_exists( '\Durotan\WooCommerce\Template\Product_Loop' ) ){
						\Durotan\WooCommerce\Template\Product_Loop::instance();
					}
					if(class_exists( '\Durotan\WooCommerce\Modules\Badges') ){
						\Durotan\WooCommerce\Modules\Badges::instance();
					}
					break;
				default:
					break;
			}
		}
	}
}
