<?php
/**
 * Meta boxes functions
 *
 * @package Durotan
 */

namespace Durotan\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Meta boxes initial
 *
 */
class Meta_Boxes {
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
		add_action( 'admin_enqueue_scripts', array( $this, 'meta_box_scripts' ) );
		add_filter( 'rwmb_meta_boxes', array( $this, 'register_meta_boxes' ) );
	}

	/**
	 * Enqueue scripts
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function meta_box_scripts( $hook ) {
		if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
			wp_enqueue_script( 'durotan-meta-boxes', get_template_directory_uri() . '/assets/js/backend/meta-boxes.js', array( 'jquery' ), '20201012', true );
		}
	}

	/**
	 * Registering meta boxes
	 *
	 * @since 1.0.0
	 *
	 * Using Meta Box plugin: http://www.deluxeblogtips.com/meta-box/
	 *
	 * @see http://www.deluxeblogtips.com/meta-box/docs/define-meta-boxes
	 *
	 * @param array $meta_boxes Default meta boxes. By default, there are no meta boxes.
	 *
	 * @return array All registered meta boxes
	 */
	public function register_meta_boxes( $meta_boxes ) {
		// Header
		$meta_boxes[] = $this->register_header_settings();

		// Page Header
		$meta_boxes[] = $this->register_page_header_settings();

		// Content
		$meta_boxes[] = $this->register_content_settings();

		// Footer
		$meta_boxes[] = $this->register_footer_settings();

		return $meta_boxes;
	}

	/**
	 * Register header settings
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function register_header_settings() {
		return array(
			'id'       => 'header-settings',
			'title'    => esc_html__( 'Header Settings', 'durotan' ),
			'pages'    => array( 'page' ),
			'context'  => 'normal',
			'priority' => 'high',
			'fields'   => array(
				array(
					'name'    => esc_html__( 'Header Background', 'durotan' ),
					'id'      => 'durotan_header_background',
					'type'    => 'select',
					'options' => array(
						'default'     => esc_html__( 'Default', 'durotan' ),
						'transparent' => esc_html__( 'Transparent', 'durotan' ),
					),
				),
				array(
					'name'    => esc_html__( 'Header Text Color', 'durotan' ),
					'id'      => 'durotan_header_text_color',
					'class'   => 'header-text-color hidden',
					'type'    => 'select',
					'options' => array(
						'default' => esc_html__( 'Default', 'durotan' ),
						'dark'    => esc_html__( 'Dark', 'durotan' ),
						'light'   => esc_html__( 'Light', 'durotan' ),
					),
				),
				array(
					'name' => esc_html__( 'Hide Border Bottom', 'durotan' ),
					'id'   => 'durotan_hide_header_border',
					'type' => 'checkbox',
					'std'  => false,
				),
				array(
					'name'    => esc_html__( 'Header Border Bottom Width', 'durotan' ),
					'id'      => 'durotan_header_border_width',
					'type'    => 'select',
					'options' => array(
						'standard' 		=> esc_html__( 'Standard', 'durotan' ),
						'full-width'    => esc_html__( 'Full Width', 'durotan' ),
					),
					'std'  	  => 'standard',
				),
				array(
					'name' => esc_html__( 'Header Border Bottom Color', 'durotan' ),
					'id'   => 'durotan_header_border_color',
					'type' => 'color',
					'std'  => false,
					'alpha_channel' => true,
				),
			)
		);
	}

	/**
	 * Register page header settings
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function register_page_header_settings() {
		return array(
			'id'       => 'page-header-settings',
			'title'    => esc_html__( 'Page Header Settings', 'durotan' ),
			'pages'    => array( 'page' ),
			'context'  => 'normal',
			'priority' => 'high',
			'fields'   => array(
				array(
					'name' => esc_html__( 'Hide Page Header', 'durotan' ),
					'id'   => 'durotan_hide_page_header',
					'type' => 'checkbox',
					'std'  => false,
				),
			)
		);
	}

	/**
	 * Register content settings
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function register_content_settings() {
		return array(
			'id'       => 'content-settings',
			'title'    => esc_html__( 'Content Settings', 'durotan' ),
			'pages'    => array( 'page' ),
			'context'  => 'normal',
			'priority' => 'high',
			'fields'   => array(
				array(
					'name'    => esc_html__( 'Content Width', 'durotan' ),
					'id'      => 'durotan_content_width',
					'type'    => 'select',
					'options' => array(
						'container'               	=> esc_attr__( 'Standard', 'durotan' ),
						'durotan-container'       	=> esc_attr__( 'Large', 'durotan' ),
						'durotan-container-narrow'	=> esc_attr__( 'Fluid', 'durotan' ),
						'durotan-container-fluid' 	=> esc_attr__( 'Full Width', 'durotan' ),
					),
				),

				array(
					'name'    => esc_html__( 'Content Top Spacing', 'durotan' ),
					'id'      => 'durotan_content_top_spacing',
					'type'    => 'select',
					'options' => array(
						'default' => esc_html__( 'Default', 'durotan' ),
						'no'      => esc_html__( 'No spacing', 'durotan' ),
						'custom'  => esc_html__( 'Custom', 'durotan' ),
					),
				),
				array(
					'name'       => '&nbsp;',
					'id'         => 'durotan_content_top_padding',
					'class'      => 'custom-spacing hidden',
					'type'       => 'slider',
					'suffix'     => esc_html__( ' px', 'durotan' ),
					'js_options' => array(
						'min' => 0,
						'max' => 300,
					),
					'std'        => '80',
				),
				array(
					'name'    => esc_html__( 'Content Bottom Spacing', 'durotan' ),
					'id'      => 'durotan_content_bottom_spacing',
					'type'    => 'select',
					'options' => array(
						'default' => esc_html__( 'Default', 'durotan' ),
						'no'      => esc_html__( 'No spacing', 'durotan' ),
						'custom'  => esc_html__( 'Custom', 'durotan' ),
					),
				),
				array(
					'name'       => '&nbsp;',
					'id'         => 'durotan_content_bottom_padding',
					'class'      => 'custom-spacing hidden',
					'type'       => 'slider',
					'suffix'     => esc_html__( ' px', 'durotan' ),
					'js_options' => array(
						'min' => 0,
						'max' => 300,
					),
					'std'        => '80',
				),
			)
		);
	}

	/**
	 * Register footer settings
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function register_footer_settings() {
		return array(
			'id'       => 'footer-settings',
			'title'    => esc_html__( 'Footer Settings', 'durotan' ),
			'pages'    => array( 'page' ),
			'context'  => 'normal',
			'priority' => 'high',
			'fields'   => array(
				array(
					'name' => esc_html__( 'Hide Footer', 'durotan' ),
					'id'   => 'durotan_hide_footer',
					'type' => 'checkbox',
					'std'  => false,
				),
			)
		);
	}
}
