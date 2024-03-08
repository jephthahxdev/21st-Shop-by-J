<?php
/**
 * Mobile Customizer functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan\Mobile;

use Kirki_Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Durotan Mobile Customizer class
 */
class Customizer {
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
		add_filter( 'durotan_customize_panels', array( $this, 'get_customize_panels' ) );
		add_filter( 'durotan_customize_sections', array( $this, 'get_customize_sections' ) );
		add_filter( 'durotan_customize_fields', array( $this, 'get_customize_fields' ) );
	}

	/**
	 * Adds theme options panels of WooCommerce.
	 *
	 * @since 1.0.0
	 *
	 * @param array $panels Theme options panels.
	 *
	 * @return array
	 */
	public function get_customize_panels( $panels ) {
		$panels['mobile'] = array(
			'title'    => esc_html__( 'Mobile', 'durotan' ),
		);

		return $panels;
	}

	/**
	 * Adds theme options sections of WooCommerce.
	 *
	 * @since 1.0.0
	 *
	 * @param array $sections Theme options sections.
	 *
	 * @return array
	 */
	public function get_customize_sections( $sections ) {
		// Page Cart
		$sections = array_merge( $sections, array(
			'mobile_header'           => array(
				'title' => esc_html__( 'Header', 'durotan' ),
				'panel' => 'mobile',
			),
			'mobile_product_catalog'  => array(
				'title' => esc_html__( 'Product Catalog', 'durotan' ),
				'panel' => 'mobile',
			),
		) );

		return $sections;
	}

	/**
	 * Adds theme options of WooCommerce.
	 *
	 * @since 1.0.0
	 *
	 * @param array $fields Theme options fields.
	 *
	 * @return array
	 */
	public function get_customize_fields( $fields ) {
		// WooCommerce settings.
		$fields = array_merge(
			$fields, array(
				'mobile_header_type'             => array(
					'type'        => 'radio',
					'label'       => esc_html__( 'Header Type', 'durotan' ),
					'description' => esc_html__( 'Select a default mobile header or custom header', 'durotan' ),
					'section'     => 'mobile_header',
					'default'     => 'default',
					'choices'     => array(
						'default' => esc_html__( 'Use pre-build header', 'durotan' ),
						'custom'  => esc_html__( 'Build my own', 'durotan' ),
					),
				),
				'mobile_header_layout'           => array(
					'type'            => 'select',
					'label'           => esc_html__( 'Header Layout', 'durotan' ),
					'section'         => 'mobile_header',
					'default'         => 'v1',
					'choices'         => array(
						'v1' => esc_html__( 'Header v1', 'durotan' ),
						'v2' => esc_html__( 'Header v2', 'durotan' ),
						'v3' => esc_html__( 'Header v3', 'durotan' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'mobile_header_type',
							'operator' => '==',
							'value'    => 'default',
						),
					),
				),
				'mobile_header_left'                          => array(
					'type'        => 'repeater',
					'label'       => esc_html__('Left Items', 'durotan'),
					'description' => esc_html__('Control items on the left side of header main', 'durotan'),
					'transport'   => 'postMessage',
					'section'     => 'mobile_header',
					'default'     => array(),
					'row_label'   => array(
						'type'  => 'field',
						'value' => esc_attr__('Item', 'durotan'),
						'field' => 'item',
					),
					'fields'          => array(
						'item' => array(
							'type'    => 'select',
							'choices' => \Durotan\Header::get_mobile_header_items_option(),
						),
					),
					'active_callback' => array(
						array(
							'setting'  => 'mobile_header_type',
							'operator' => '==',
							'value'    => 'custom',
						),
					),
				),
				'mobile_header_center'                        => array(
					'type'        => 'repeater',
					'label'       => esc_html__('Center Items', 'durotan'),
					'description' => esc_html__('Control items at the center of header main', 'durotan'),
					'transport'   => 'postMessage',
					'section'     => 'mobile_header',
					'default'     => array(),
					'row_label'   => array(
						'type'  => 'field',
						'value' => esc_attr__('Item', 'durotan'),
						'field' => 'item',
					),
					'fields'          => array(
						'item' => array(
							'type'    => 'select',
							'choices' => \Durotan\Header::get_mobile_header_items_option(),
						),
					),
					'active_callback' => array(
						array(
							'setting'  => 'mobile_header_type',
							'operator' => '==',
							'value'    => 'custom',
						),
					),
				),
				'mobile_header_right'                         => array(
					'type'        => 'repeater',
					'label'       => esc_html__('Right Items', 'durotan'),
					'description' => esc_html__('Control items on the right of header main', 'durotan'),
					'transport'   => 'postMessage',
					'section'     => 'mobile_header',
					'default'     => array(),
					'row_label'   => array(
						'type'  => 'field',
						'value' => esc_attr__('Item', 'durotan'),
						'field' => 'item',
					),
					'fields'          => array(
						'item' => array(
							'type'    => 'select',
							'choices' => \Durotan\Header::get_mobile_header_items_option(),
						),
					),
					'active_callback' => array(
						array(
							'setting'  => 'mobile_header_type',
							'operator' => '==',
							'value'    => 'custom',
						),
					),
				),
				'mobile_header_hr_1'                            => array(
					'type'    => 'custom',
					'section' => 'mobile_header',
					'default' => '<hr>',
				),
				'mobile_header_height'              => array(
					'type'      => 'slider',
					'label'     => esc_html__( 'Header Height', 'durotan' ),
					'section'   => 'mobile_header',
					'transport' => 'postMessage',
					'default'   => '60',
					'choices'   => array(
						'min' => 40,
						'max' => 300,
					),
					'js_vars'   => array(
						array(
							'element'  => '.header__mobile',
							'property' => 'height',
							'units'    => 'px',
						),
					),
				),
				// Mobile Menu
				'mobile_menu_custom_field_1'        => array(
					'type'    => 'custom',
					'section' => 'mobile_header',
					'default' => '<hr/>',
				),
				'mobile_menu_custom_field_2'        => array(
					'type'    => 'custom',
					'section' => 'mobile_header',
					'default' => '<h2>' . esc_html__( 'Header Menu', 'durotan' ) . '</h2>',
				),
				'mobile_menu_side_type'                       => array(
					'type'    => 'select',
					'label'   => esc_html__('Show Menu', 'durotan'),
					'section' => 'mobile_header',
					'default' => 'side-left',
					'choices' => array(
						'side-left'  => esc_html__('Side to right', 'durotan'),
						'side-right' => esc_html__('Side to left', 'durotan'),
					),
				),
				'mobile_menu_show_socials'          => array(
					'type'    => 'toggle',
					'label'   => esc_html__( 'Show Socials', 'durotan' ),
					'default' => '0',
					'section' => 'mobile_header',
				),
				'mobile_menu_show_copyright'        => array(
					'type'    => 'toggle',
					'label'   => esc_html__( 'Show Copyright', 'durotan' ),
					'default' => '0',
					'section' => 'mobile_header',
				),

				// Catalog
				'mobile_catalog_product_loop_hr' => array(
					'type'            => 'custom',
					'section'         => 'mobile_product_catalog',
					'default'         => '<hr/><h2>' . esc_html__( 'Product Loop', 'durotan' ) . '</h2>',
				),
				'mobile_product_featured_icons'  => array(
					'type'            => 'toggle',
					'label'           => esc_html__( 'Always Show Featured Icons', 'durotan' ),
					'default'         => '0',
					'section'         => 'mobile_product_catalog',
				),

				'shop_products_hr_4' => array(
					'type'    => 'custom',
					'default' => '<hr/><h2>' . esc_html__( 'Product Columns', 'durotan' ) . '</h2>',
					'section' => 'mobile_product_catalog',
				),

				'mobile_landscape_product_columns'     => array(
					'label'   => esc_html__( 'Mobile Landscape(767px)', 'durotan' ),
					'section' => 'mobile_product_catalog',
					'type'    => 'select',
					'default' => '3',
					'choices' => array(
						'1' => esc_attr__( '1 Column', 'durotan' ),
						'2' => esc_attr__( '2 Columns', 'durotan' ),
						'3' => esc_attr__( '3 Columns', 'durotan' ),
					),
				),
				'mobile_portrait_product_columns'      => array(
					'label'   => esc_html__( 'Mobile Portrait(479px)', 'durotan' ),
					'section' => 'mobile_product_catalog',
					'type'    => 'select',
					'default' => '2',
					'choices' => array(
						'1' => esc_attr__( '1 Column', 'durotan' ),
						'2' => esc_attr__( '2 Columns', 'durotan' ),
						'3' => esc_attr__( '3 Columns', 'durotan' ),
					),
				),
			)
		);

		return $fields;
	}

	/**
	 * Options of mobile header icons
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_mobile_header_icons_option() {
		return apply_filters( 'durotan_mobile_header_icons_option', array(
			'cart'     => esc_html__( 'Cart Icon', 'durotan' ),
			'wishlist' => esc_html__( 'Wishlist Icon', 'durotan' ),
			'account'  => esc_html__( 'Account Icon', 'durotan' ),
			'menu'     => esc_html__( 'Menu Icon', 'durotan' ),
			'search'   => esc_html__( 'Search Icon', 'durotan' ),
		) );
	}
}
