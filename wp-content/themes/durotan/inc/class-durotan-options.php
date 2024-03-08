<?php
/**
 * Theme Options
 *
 * @package Durotan
 */

namespace Durotan;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Options {
	/**
	 * Instance
	 *
	 * @var $instance
	 */
	protected static $instance = null;

	/**
	 * $durotan_customize
	 *
	 * @var $durotan_customize
	 */
	protected static $durotan_customize = null;

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
	 * The class constructor
	 *
	 * @since 1.0.0
	 *
	 * @param array $config
	 */
	public function __construct() {
		add_action('init', array($this, 'init') );
	}

	/**
	 * Init
	 *
	 * @since 1.0.0
	 *
	 * @param array $config
	 */
	public function init() {
		add_filter( 'durotan_customize_config', array( $this, 'customize_settings' ) );
		self::$durotan_customize = Theme::instance()->get( 'customizer' );
	}

	/**
	 * This is a short hand function for getting setting value from customizer
	 *
	 * @since 1.0.0
	 *
	 * @param string $name
	 *
	 * @return bool|string
	 */
	public function get_option( $name ) {
		if ( class_exists( 'Kirki' ) && is_object( self::$durotan_customize ) ) {
			$value = \Kirki::get_option( 'durotan', $name );
		} elseif ( is_object( self::$durotan_customize ) ) {
			$value = self::$durotan_customize->get_option( $name );
		} elseif ( false !== get_theme_mod( $name ) ) {
			$value = get_theme_mod( $name );
		} else {
			$value = $this->get_option_default( $name );
		}

		return apply_filters( 'durotan_get_option', $value, $name );
	}

	/**
	 * Get default option values
	 *
	 * @since 1.0.0
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	public function get_option_default( $name ) {
		if ( empty( self::$durotan_customize ) ) {
			return false;
		}

		return self::$durotan_customize->get_option_default( $name );
	}

	/**
	 * Get customize settings
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function customize_settings() {
		$settings = array(
			'theme' => 'durotan',
		);

		$panels = array(
			'general' => array(
				'priority' => 1,
				'title'    => esc_html__( 'General', 'durotan' ),
			),

			// Header
			'header'  => array(
				'title'      => esc_html__( 'Header', 'durotan' ),
				'capability' => 'edit_theme_options',
			),

			// Blog
			'blog'  => array(
				'title'      => esc_html__( 'Blog', 'durotan' ),
				'capability' => 'edit_theme_options',
			),

			// Footer
			'footer' => array(
				'title'      => esc_html__( 'Footer', 'durotan' ),
				'capability' => 'edit_theme_options',
			),

			// Style
			'styling'  => array(
				'title'      => esc_html__( 'Styling', 'durotan' ),
				'capability' => 'edit_theme_options',
			),

		);

		$sections = array(
			// Maintenance
			'maintenance'  => array(
				'title'      => esc_html__('Maintenance', 'durotan'),
				'priority' => 2,
				'capability' => 'edit_theme_options',
			),

			// Styling
			'color_scheme'           => array(
				'title'       => esc_html__( 'Color Scheme', 'durotan' ),
				'description' => '',
				'priority'    => 10,
				'capability'  => 'edit_theme_options',
				'panel'       => 'styling',
			),


			// Header
			'header_layout'     => array(
				'title' => esc_html__( 'Header Layout', 'durotan' ),
				'panel' => 'header',
			),
			'header_main'     => array(
				'title' => esc_html__( 'Header Main', 'durotan' ),
				'panel' => 'header',
			),
			'header_bottom'     => array(
				'title' => esc_html__('Header Bottom', 'durotan'),
				'panel' => 'header',
			),
			'header_background' => array(
				'title' => esc_html__('Header Background', 'durotan'),
				'panel' => 'header',
			),
			'header_campaign'   => array(
				'title' => esc_html__('Campaign Bar', 'durotan'),
				'panel' => 'header',
			),
			'header_logo'       => array(
				'title' => esc_html__( 'Logo', 'durotan' ),
				'panel' => 'header',
			),
			'header_menu'       => array(
				'title' => esc_html__( 'Menu', 'durotan' ),
				'panel' => 'header',
			),
			'header_hamburger'  => array(
				'title' => esc_html__('Hamburger Menu', 'durotan'),
				'panel' => 'header',
			),
			'header_search'     => array(
				'title' => esc_html__('Search', 'durotan'),
				'panel' => 'header',
			),
			'header_account'     => array(
				'title' => esc_html__('Account', 'durotan'),
				'panel' => 'header',
			),
			'header_cart'     => array(
				'title' => esc_html__('Cart', 'durotan'),
				'panel' => 'header',
			),
			'header_wishlist'   => array(
				'title' => esc_html__('Wishlist', 'durotan'),
				'panel' => 'header',
			),
			'header_compare'   => array(
				'title' => esc_html__('Compare', 'durotan'),
				'panel' => 'header',
			),
			'header_currencies'   => array(
				'title' => esc_html__('Currencies', 'durotan'),
				'panel' => 'header',
			),
			'header_languages'   => array(
				'title' => esc_html__('Languages', 'durotan'),
				'panel' => 'header',
			),

			// Blog
			'blog_page_header'         => array(
				'title'       => esc_html__( 'Page Header', 'durotan' ),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'blog',
			),
			'blog_page'         => array(
				'title'       => esc_html__( 'Blog Archive', 'durotan' ),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'blog',
			),
			'blog_featured_content'         => array(
				'title'       => esc_html__( 'Featured Content', 'durotan' ),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'blog',
			),
			'blog_featured_post'         => array(
				'title'       => esc_html__( 'Featured Post', 'durotan' ),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'blog',
			),
			'blog_newsletter'         => array(
				'title'       => esc_html__( 'Newsletter', 'durotan' ),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'blog',
			),
			'blog_toolbar'         => array(
				'title'       => esc_html__( 'Blog Toolbar', 'durotan' ),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'blog',
			),

			// Single post
			'single_post'       => array(
				'title' => esc_html__( 'Single Post', 'durotan' ),
				'panel' => 'blog',
			),

			// Footer
			'footer_layout'     => array(
				'title'       => esc_html__( 'Footer Layout', 'durotan' ),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'footer',
			),
			'footer_newsletter' => array(
				'title'       => esc_html__('Footer Newsletter', 'durotan'),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'footer',
			),
			'footer_widget'		=> array(
				'title'       => esc_html__('Footer Widget', 'durotan'),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'footer',
			),
			'footer_extra'      => array(
				'title'       => esc_html__('Footer Extra', 'durotan'),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'footer',
			),
			'footer_main'       => array(
				'title'       => esc_html__('Footer Main', 'durotan'),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'footer',
			),
			'footer_background' => array(
				'title'       => esc_html__('Footer Background', 'durotan'),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'footer',
			),
			'footer_copyright'  => array(
				'title'       => esc_html__( 'Copyright', 'durotan' ),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'footer',
			),
			'footer_menu'  => array(
				'title'       => esc_html__('Menu', 'durotan'),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'footer',
			),
			'footer_text'       => array(
				'title'       => esc_html__('Custom Text', 'durotan'),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'footer',
			),
			'footer_payment'    => array(
				'title'       => esc_html__('Payments', 'durotan'),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'footer',
			),
			'footer_currencies'   => array(
				'title' => esc_html__('Currencies', 'durotan'),
				'panel' => 'footer',
			),
			'footer_languages'   => array(
				'title' => esc_html__('Languages', 'durotan'),
				'panel' => 'footer',
			),
		);

		$fields = array(
			// Maintenance
			'maintenance_enable'             => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Enable Maintenance Mode', 'durotan'),
				'description' => esc_html__('Put your site into maintenance mode', 'durotan'),
				'default'     => false,
				'section'     => 'maintenance',
			),
			'maintenance_mode'               => array(
				'type'        => 'radio',
				'label'       => esc_html__('Mode', 'durotan'),
				'description' => esc_html__('Select the correct mode for your site', 'durotan'),
				'tooltip'     => wp_kses_post(sprintf(__('If you are putting your site into maintenance mode for a longer perior of time, you should set this to "Coming Soon". Maintenance will return HTTP 503, Comming Soon will set HTTP to 200. <a href="%s" target="_blank">Learn more</a>', 'durotan'), 'https://yoast.com/http-503-site-maintenance-seo/')),
				'default'     => 'maintenance',
				'choices'     => array(
					'maintenance' => esc_attr__('Maintenance', 'durotan'),
					'coming_soon' => esc_attr__('Coming Soon', 'durotan'),
				),
				'active_callback' => array(
					array(
						'setting'  => 'maintenance_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
				'section' => 'maintenance',
			),
			'maintenance_page'               => array(
				'type'            => 'dropdown-pages',
				'label'           => esc_html__('Maintenance Page', 'durotan'),
				'default'         => 0,
				'active_callback' => array(
					array(
						'setting'  => 'maintenance_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
				'section' => 'maintenance',
			),
			//Color Scheme
			'color_scheme'                    => array(
				'type'     => 'palette',
				'label'    => esc_html__( 'Base Color Scheme', 'durotan' ),
				'default'  => '',
				'section'  => 'color_scheme',
				'priority' => 10,
				'choices'  => array(
					''        => array( '#928656' ),
					'#da5f39' => array( '#da5f39' ),
					'#503a2a' => array( '#503a2a' ),
					'#669900' => array( '#669900' ),
					'#5e4d30' => array( '#5e4d30' ),
				),
			),
			'custom_color_scheme'             => array(
				'type'     => 'toggle',
				'label'    => esc_html__( 'Custom Color Scheme', 'durotan' ),
				'default'  => 0,
				'section'  => 'color_scheme',
				'priority' => 10,
			),
			'custom_color'                    => array(
				'type'            => 'color',
				'label'           => esc_html__( 'Color', 'durotan' ),
				'default'         => '',
				'section'         => 'color_scheme',
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'custom_color_scheme',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			// Header Layout
			'header_type'             => array(
				'type'        => 'radio',
				'label'       => esc_html__( 'Header Type', 'durotan' ),
				'description' => esc_html__( 'Select a default header or custom header', 'durotan' ),
				'section'     => 'header_layout',
				'default'     => 'default',
				'choices'     => array(
					'default' => esc_html__( 'Use pre-build header', 'durotan' ),
					'custom'  => esc_html__( 'Build my own', 'durotan' ),
				),
			),
			'header_layout'           => array(
				'type'            => 'select',
				'label'           => esc_html__( 'Header Layout', 'durotan' ),
				'section'         => 'header_layout',
				'default'         => 'v10',
				'choices'         => array(
					'v1' => esc_html__( 'Header v1', 'durotan' ),
					'v2' => esc_html__( 'Header v2', 'durotan' ),
					'v3' => esc_html__( 'Header v3', 'durotan' ),
					'v4' => esc_html__( 'Header v4', 'durotan' ),
					'v5' => esc_html__( 'Header v5', 'durotan' ),
					'v6' => esc_html__( 'Header v6', 'durotan' ),
					'v7' => esc_html__( 'Header v7', 'durotan' ),
					'v8' => esc_html__( 'Header v8', 'durotan' ),
					'v9' => esc_html__( 'Header v9', 'durotan' ),
					'v10' => esc_html__( 'Header v10', 'durotan' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_type',
						'operator' => '==',
						'value'    => 'default',
					),
				),
			),
			'header_width'	=> array(
				'type'    => 'select',
				'label'   => esc_html__('Header Width', 'durotan'),
				'section' => 'header_layout',
				'default' => 'durotan-container',
				'choices' => array(
					'container'               	=> esc_attr__( 'Standard', 'durotan' ),
					'durotan-container'       	=> esc_attr__( 'Large', 'durotan' ),
					'durotan-container-narrow'	=> esc_attr__( 'Fluid', 'durotan' ),
					'durotan-container-fluid' 	=> esc_attr__( 'Full Width', 'durotan' ),
				),
			),

			// Header Sticky
			'header_sticky_custom_field_1'              => array(
				'type'    => 'custom',
				'section' => 'header_layout',
				'default' => '<hr/>',
			),
			'header_sticky'                             => array(
				'type'    => 'toggle',
				'label'   => esc_html__('Header Sticky', 'durotan'),
				'default' => 0,
				'section' => 'header_layout',
			),
			'header_sticky_el'                          => array(
				'type'     => 'multicheck',
				'label'    => esc_html__('Header Sticky Elements', 'durotan'),
				'section'  => 'header_layout',
				'default'  => array('header_main'),
				'choices'  => array(
					'header_main'   => esc_html__('Header Main', 'durotan'),
					'header_bottom' => esc_html__('Header Bottom', 'durotan'),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_sticky',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			// Header Main
			'header_main_left'                          => array(
				'type'        => 'repeater',
				'label'       => esc_html__('Left Items', 'durotan'),
				'description' => esc_html__('Control items on the left side of header main', 'durotan'),
				'transport'   => 'postMessage',
				'section'     => 'header_main',
				'default'     => array(),
				'row_label'   => array(
					'type'  => 'field',
					'value' => esc_attr__('Item', 'durotan'),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'choices' => \Durotan\Header::get_header_items_option(),
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_type',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),
			'header_main_center'                        => array(
				'type'        => 'repeater',
				'label'       => esc_html__('Center Items', 'durotan'),
				'description' => esc_html__('Control items at the center of header main', 'durotan'),
				'transport'   => 'postMessage',
				'section'     => 'header_main',
				'default'     => array(),
				'row_label'   => array(
					'type'  => 'field',
					'value' => esc_attr__('Item', 'durotan'),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'choices' => \Durotan\Header::get_header_items_option(),
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_type',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),
			'header_main_right'                         => array(
				'type'        => 'repeater',
				'label'       => esc_html__('Right Items', 'durotan'),
				'description' => esc_html__('Control items on the right of header main', 'durotan'),
				'transport'   => 'postMessage',
				'section'     => 'header_main',
				'default'     => array(),
				'row_label'   => array(
					'type'  => 'field',
					'value' => esc_attr__('Item', 'durotan'),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'choices' => \Durotan\Header::get_header_items_option(),
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_type',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),
			'header_main_hr'                            => array(
				'type'    => 'custom',
				'section' => 'header_main',
				'default' => '<hr>',
			),
			'header_main_height' => array(
				'type'        => 'slider',
				'label'       => esc_html__( 'Header Height', 'durotan' ),
				'section'     => 'header_main',
				'default'     => '155',
				'choices'     => array(
					'min'  => 50,
					'max'  => 500,
				),
				'transport' => 'postMessage',
				'js_vars'   => array(
					array(
					'element'  => '.header__main',
					'property' => 'height',
					'units' => 'px',
					),
				),
			),
			'sticky_header_main_height'                 => array(
				'type'        => 'slider',
				'label'       => esc_html__('Header Sticky Height', 'durotan'),
				'description' => esc_html__('Adjust Header Main height when Header Sticky is enable', 'durotan'),
				'transport'   => 'postMessage',
				'section'     => 'header_main',
				'default'     => '156',
				'choices'     => array(
					'min' => 50,
					'max' => 500,
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_sticky',
						'operator' => '==',
						'value'    => 1,
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.header-sticky .site-header.minimized .header__main',
						'property' => 'height',
						'units'    => 'px',
					),
				),
			),
			// Header Bottom
			'header_bottom_left'                        => array(
				'type'        => 'repeater',
				'label'       => esc_html__('Left Items', 'durotan'),
				'description' => esc_html__('Control items on the left side of header bottom', 'durotan'),
				'transport'   => 'postMessage',
				'section'     => 'header_bottom',
				'default'     => array(),
				'row_label'   => array(
					'type'  => 'field',
					'value' => esc_attr__('Item', 'durotan'),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'choices' => \Durotan\Header::get_header_items_option(),
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_type',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),
			'header_bottom_center'                      => array(
				'type'        => 'repeater',
				'label'       => esc_html__('Center Items', 'durotan'),
				'description' => esc_html__('Control items at the center of header bottom', 'durotan'),
				'transport'   => 'postMessage',
				'section'     => 'header_bottom',
				'default'     => array(),
				'row_label'   => array(
					'type'  => 'field',
					'value' => esc_attr__('Item', 'durotan'),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'choices' => \Durotan\Header::get_header_items_option(),
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_type',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),
			'header_bottom_right'                       => array(
				'type'        => 'repeater',
				'label'       => esc_html__('Right Items', 'durotan'),
				'description' => esc_html__('Control items on the right of header bottom', 'durotan'),
				'transport'   => 'postMessage',
				'section'     => 'header_bottom',
				'default'     => array(),
				'row_label'   => array(
					'type'  => 'field',
					'value' => esc_attr__('Item', 'durotan'),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'choices' => \Durotan\Header::get_header_items_option(),
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_type',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),
			'header_bottom_hr'                          => array(
				'type'    => 'custom',
				'section' => 'header_bottom',
				'default' => '<hr>',
			),
			'header_bottom_height'                      => array(
				'type'      => 'slider',
				'label'     => esc_html__('Header Height', 'durotan'),
				'transport' => 'postMessage',
				'section'   => 'header_bottom',
				'default'   => '80',
				'choices'   => array(
					'min' => 0,
					'max' => 500,
				),
				'js_vars'   => array(
					array(
						'element'  => '.header__bottom',
						'property' => 'height',
						'units'    => 'px',
					),
				),
			),
			'sticky_header_bottom_height'               => array(
				'type'        => 'slider',
				'label'       => esc_html__('Header Sticky Height', 'durotan'),
				'description' => esc_html__('Adjust Header Bottom height when Header Sticky is enable', 'durotan'),
				'transport'   => 'postMessage',
				'section'     => 'header_bottom',
				'default'     => '80',
				'choices'     => array(
					'min' => 0,
					'max' => 500,
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_sticky',
						'operator' => '==',
						'value'    => 1,
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.header-sticky .site-header.minimized .header__bottom',
						'property' => 'height',
						'units'    => 'px',
					),
				),
			),
			// Header Backgound
			'header_main_background'                    => array(
				'type'    => 'toggle',
				'label'   => esc_html__('Header Main Custom Color', 'durotan'),
				'section' => 'header_background',
				'default' => 0,
			),
			'header_main_background_color'              => array(
				'type'            => 'color',
				'label'           => esc_html__('Background Color', 'durotan'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_background',
				'active_callback' => array(
					array(
						'setting'  => 'header_main_background',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.site-header .header__main',
						'property' => 'background-color',
					),
				),
			),
			'header_main_background_text_color'         => array(
				'type'            => 'color',
				'label'           => esc_html__('Text Color', 'durotan'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_background',
				'active_callback' => array(
					array(
						'setting'  => 'header_main_background',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.site-header .header__main',
						'property' => '--durotan-header-text',
					),
				),
			),
			'header_main_background_text_color_hover'   => array(
				'type'            => 'color',
				'label'           => esc_html__('Text Hover Color', 'durotan'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_background',
				'active_callback' => array(
					array(
						'setting'  => 'header_main_background',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.site-header .header__main',
						'property' => '--durotan-header-text-hover',
					),
				),
			),
			'header_background_hr'                      => array(
				'type'    => 'custom',
				'section' => 'header_background',
				'default' => '<hr>',
			),
			'header_bottom_background'                  => array(
				'type'    => 'toggle',
				'label'   => esc_html__('Header Bottom Custom Color', 'durotan'),
				'section' => 'header_background',
				'default' => 0,
			),
			'header_bottom_background_color'            => array(
				'type'            => 'color',
				'label'           => esc_html__('Background Color', 'durotan'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_background',
				'active_callback' => array(
					array(
						'setting'  => 'header_bottom_background',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.site-header .header__bottom',
						'property' => 'background-color',
					),
				),
			),
			'header_bottom_background_text_color'       => array(
				'type'            => 'color',
				'label'           => esc_html__('Text Color', 'durotan'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_background',
				'active_callback' => array(
					array(
						'setting'  => 'header_bottom_background',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.site-header .header__bottom',
						'property' => '--durotan-header-text',
					),
				),
			),
			'header_bottom_background_text_color_hover' => array(
				'type'            => 'color',
				'label'           => esc_html__('Text Hover Color', 'durotan'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_background',
				'active_callback' => array(
					array(
						'setting'  => 'header_bottom_background',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.site-header .header__bottom',
						'property' => '--durotan-header-text-hover',
					),
				),
			),
			// Header Campain
			'campaign_bar'  => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Campaign Bar', 'durotan'),
				'section'     => 'header_campaign',
				'description' => esc_html__('Display a bar bellow site header.', 'durotan'),
				'default'     => 0,
			),
			'campaign_bar_content' => array(
				'type'            => 'textarea',
				'label'           => esc_html__('Content', 'durotan'),
				'description'     => esc_html__('Enter the content of Campaign bar. Use class: durotan-special-text, add special text color.', 'durotan'),
				'section'         => 'header_campaign',
				'default'         => '',
				'active_callback' => array(
					array(
						'setting'  => 'campaign_bar',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
			'campaign_bar_content_color'  => array(
				'type'    => 'radio',
				'label'   => esc_html__( 'Content Color', 'durotan' ),
				'default' => 'text-dark',
				'section' => 'header_campaign',
				'choices' => array(
					'text-dark'   	=> esc_html__( 'Text Dark', 'durotan' ),
					'text-light' 	=> esc_html__( 'Text Light', 'durotan' ),
					'custom-color'  => esc_html__( 'Custom Color', 'durotan' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'campaign_bar',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
			'campaign_bar_content_color_custom' => array(
				'type'            => 'color',
				'label'           => esc_html__('Color', 'durotan'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_campaign',
				'active_callback' => array(
					array(
						'setting'  => 'campaign_bar',
						'operator' => '==',
						'value'    => 1,
					),
					array(
						'setting'  => 'campaign_bar_content_color',
						'operator' => '==',
						'value'    => 'custom-color',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.durotan-campaign-bar',
						'property' => 'color',
					),
					array(
						'element'  => '.durotan-campaign-bar',
						'property' => 'fill',
					),
				),
			),
			'campaign_bar_background_color' => array(
				'type'            => 'color',
				'label'           => esc_html__('Background Color', 'durotan'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_campaign',
				'active_callback' => array(
					array(
						'setting'  => 'campaign_bar',
						'operator' => '==',
						'value'    => 1,
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.durotan-campaign-bar',
						'property' => 'background-color',
					),
				),
			),
			'campaign_bar_height'   => array(
				'type'      => 'slider',
				'label'     => esc_html__('Height', 'durotan'),
				'section'   => 'header_campaign',
				'default'   => '45',
				'transport' => 'postMessage',
				'choices'   => array(
					'min' => 0,
					'max' => 500,
				),
				'js_vars'         => array(
					array(
						'element'  => '.durotan-campaign-bar',
						'property' => 'height',
						'units'    => 'px',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'campaign_bar',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
			'campaign_bar_close'  => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Enable close button', 'durotan'),
				'section'     => 'header_campaign',
				'default'     => 0,
				'active_callback' => array(
					array(
						'setting'  => 'campaign_bar',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			// Logo
			'logo_type'                                 => array(
				'type'    => 'radio',
				'label'   => esc_html__( 'Logo Type', 'durotan' ),
				'default' => 'text',
				'section' => 'header_logo',
				'choices' => array(
					'image' => esc_html__( 'Image', 'durotan' ),
					'svg'   => esc_html__( 'SVG', 'durotan' ),
					'text'  => esc_html__( 'Text', 'durotan' ),
				),
			),
			'logo_svg'                                  => array(
				'type'              => 'textarea',
				'label'             => esc_html__( 'Logo SVG', 'durotan' ),
				'section'           => 'header_logo',
				'description'       => esc_html__( 'Paste SVG code of your logo here', 'durotan' ),
				'sanitize_callback' => '\Durotan\Icon::sanitize_svg',
				'output'            => array(
					array(
						'element' => '.site-branding .logo',
					),
				),
				'active_callback'   => array(
					array(
						'setting'  => 'logo_type',
						'operator' => '==',
						'value'    => 'svg',
					),
				),
			),
			'logo'                                      => array(
				'type'            => 'image',
				'label'           => esc_html__( 'Logo', 'durotan' ),
				'default'         => '',
				'section'         => 'header_logo',
				'active_callback' => array(
					array(
						'setting'  => 'logo_type',
						'operator' => '==',
						'value'    => 'image',
					),
				),
			),
			'logo_text'                                 => array(
				'type'            => 'textarea',
				'label'           => esc_html__( 'Logo Text', 'durotan' ),
				'default'         => 'Durotan',
				'section'         => 'header_logo',
				'output'          => array(
					array(
						'element' => '.site-branding .logo',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'logo_type',
						'operator' => '==',
						'value'    => 'text',
					),
				),
			),
			'logo_svg_light'                                  => array(
				'type'              => 'textarea',
				'label'             => esc_html__( 'Logo SVG Light', 'durotan' ),
				'section'           => 'header_logo',
				'description'       => esc_html__( 'Paste SVG code of your logo here', 'durotan' ),
				'sanitize_callback' => '\Durotan\Icon::sanitize_svg',
				'output'            => array(
					array(
						'element' => '.site-branding .logo',
					),
				),
				'active_callback'   => array(
					array(
						'setting'  => 'logo_type',
						'operator' => '==',
						'value'    => 'svg',
					),
				),
			),
			'logo_light'                                      => array(
				'type'            => 'image',
				'label'           => esc_html__( 'Logo Light', 'durotan' ),
				'default'         => '',
				'section'         => 'header_logo',
				'active_callback' => array(
					array(
						'setting'  => 'logo_type',
						'operator' => '==',
						'value'    => 'image',
					),
				),
			),
			'logo_dimension'                            => array(
				'type'            => 'dimensions',
				'label'           => esc_html__( 'Logo Dimension', 'durotan' ),
				'default'         => array(
					'width'  => '',
					'height' => '',
				),
				'section'         => 'header_logo',
				'active_callback' => array(
					array(
						'setting'  => 'logo_type',
						'operator' => '!=',
						'value'    => 'text',
					),
					array(
						'setting'  => 'logo_type',
						'operator' => '!=',
						'value'    => 'svg',
					),
				),
			),

			// Header Menu
			'header_menu_custom_color'                  => array(
				'type'    => 'toggle',
				'label'   => esc_html__('Header Menu Custom Color', 'durotan'),
				'section' => 'header_menu',
				'default' => 0,
			),
			'header_menu_text_color'              => array(
				'type'            => 'color',
				'label'           => esc_html__('Text Color', 'durotan'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_menu',
				'active_callback' => array(
					array(
						'setting'  => 'header_menu_custom_color',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.main-navigation ul.menu > li > a',
						'property' => 'color',
					),
				),
			),
			'header_menu_hover_color'              => array(
				'type'            => 'color',
				'label'           => esc_html__('Hover Color', 'durotan'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_menu',
				'active_callback' => array(
					array(
						'setting'  => 'header_menu_custom_color',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.main-navigation ul.menu > li:hover > a, .main-navigation ul.menu > li.current-menu-item > a, .header-v1 .durotan-menu-item__dot',
						'property' => '--durotan-header-text-hover',
					),
				),
			),

			// Header Menu Hamburger
			'hamburger_type'                                 => array(
				'type'    => 'radio',
				'label'   => esc_html__( 'Hamburger Menu Type', 'durotan' ),
				'default' => 'icon',
				'section' => 'header_hamburger',
				'choices' => array(
					'icon' => esc_html__( 'Icon', 'durotan' ),
					'text'  => esc_html__( 'Icon Text', 'durotan' ),
				),
			),
			'hamburger_type_text'                                 => array(
				'type'    => 'text',
				'label'   => esc_html__( 'Label', 'durotan' ),
				'default' => 'Menu',
				'section' => 'header_hamburger',
				'active_callback' => array(
					array(
						'setting'  => 'hamburger_type',
						'operator' => '==',
						'value'    => 'text',
					),
				),
			),
			'hamburger_side_type'                       => array(
				'type'    => 'select',
				'label'   => esc_html__('Show Menu', 'durotan'),
				'section' => 'header_hamburger',
				'default' => 'side-right',
				'choices' => array(
					'side-left'  => esc_html__('Side to right', 'durotan'),
					'side-right' => esc_html__('Side to left', 'durotan'),
				),
			),
			'hamburger_language'                        => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Show Language', 'durotan'),
				'section'     => 'header_hamburger',
				'default'     => 1,
			),
			'hamburger_currency'                        => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Show Currency', 'durotan'),
				'section'     => 'header_hamburger',
				'default'     => 1,
			),
			'hamburger_custom_text' => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Custom Text', 'durotan' ),
				'section'     => 'header_hamburger',
				'default'     => 1,
			),
			'hamburger_custom_text_html'   => array(
				'type'       	  => 'textarea',
				'description'  	  => esc_html__( 'Enter content or html', 'durotan' ),
				'default'     	  => '',
				'section'    	  => 'header_hamburger',
				'active_callback' => array(
					array(
						'setting'  => 'hamburger_custom_text',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
			'hamburger_socials' => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Socials Share', 'durotan' ),
				'description' => esc_html__( 'Check this option to show socials share in the hamburger menu footer.', 'durotan' ),
				'section'     => 'header_hamburger',
				'default'     => 1,
			),
			'hamburger_socials_share'           => array(
				'type'            => 'multicheck',
				'label'           => esc_html__( 'Socials List', 'durotan' ),
				'section'         => 'header_hamburger',
				'default'         => array( 'facebook', 'twitter', 'googleplus', 'tumblr' ),
				'choices'         => array(
					'facebook'    => esc_html__( 'Facebook', 'durotan' ),
					'twitter'     => esc_html__( 'Twitter', 'durotan' ),
					'googleplus'  => esc_html__( 'Google Plus', 'durotan' ),
					'tumblr'      => esc_html__( 'Tumblr', 'durotan' ),
					'pinterest'   => esc_html__( 'Pinterest', 'durotan' ),
					'linkedin'    => esc_html__( 'Linkedin', 'durotan' ),
					'reddit'      => esc_html__( 'Reddit', 'durotan' ),
					'telegram'    => esc_html__( 'Telegram', 'durotan' ),
					'pocket'      => esc_html__( 'Pocket', 'durotan' ),
					'email'       => esc_html__( 'Email', 'durotan' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'hamburger_socials',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			// Header Search
			'header_search_layout'                       => array(
				'type'    => 'select',
				'label'   => esc_html__('Search Layout', 'durotan'),
				'default' => 'icon',
				'section' => 'header_search',
				'choices' => array(
					'form'     => esc_html__('Icon and search field', 'durotan'),
					'icon'     => esc_html__('Icon only', 'durotan'),
				),
			),
			'header_search_custom_field_1'              => array(
				'type'            => 'custom',
				'section'         => 'header_search',
				'default'         => '<hr/>',
			),
			'header_search_layout_icon_type'       	=> array(
				'type'            => 'radio',
				'label'   		  => esc_html__('Search Type', 'durotan'),
				'section'         => 'header_search',
				'default'         => 'icon',
				'choices' => array(
					'icon'   	=> esc_html__('Icon', 'durotan'),
					'text'    	=> esc_html__('Text', 'durotan'),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_search_layout',
						'operator' => '==',
						'value'    => 'icon',
					),
				),
			),
			'header_search_layout_icon_type_text'       	=> array(
				'type'            => 'text',
				'label'           => esc_html__('Label', 'durotan'),
				'section'         => 'header_search',
				'default'         => esc_html__('Search', 'durotan'),
				'active_callback' => array(
					array(
						'setting'  => 'header_search_layout',
						'operator' => '==',
						'value'    => 'icon',
					),
					array(
						'setting'  => 'header_search_layout_icon_type',
						'operator' => '==',
						'value'    => 'text',
					),
				),
			),
			'header_search_layout_form_layout'       	=> array(
				'type'            => 'radio',
				'label'           => esc_html__('Position Icon', 'durotan'),
				'section'         => 'header_search',
				'default'         => 'left',
				'choices' => array(
					'left'   	=> esc_html__('Icon left', 'durotan'),
					'right'     => esc_html__('Icon right', 'durotan'),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_search_layout',
						'operator' => '==',
						'value'    => 'form',
					),
				),
			),
			'header_search_custom_form_2'              => array(
				'type'            => 'custom',
				'section'         => 'header_search',
				'default'         => '<hr/>',
			),
			'header_search_type'                        => array(
				'type'    => 'select',
				'label'   => esc_html__('Search For', 'durotan'),
				'default' => '',
				'section' => 'header_search',
				'choices' => array(
					''        => esc_html__('Search for everything', 'durotan'),
					'product' => esc_html__('Search for products', 'durotan'),
				),
			),
			'header_search_custom_field_3'              => array(
				'type'    => 'custom',
				'section' => 'header_search',
				'default' => '<hr/>',
				'active_callback' => array(
					array(
						'setting'  => 'header_search_type',
						'operator' => '==',
						'value'    => 'product',
					),
				),
			),
			'header_search_placeholder'                 => array(
				'type'    => 'text',
				'label'   => esc_html__('Placeholder', 'durotan'),
				'section' => 'header_search',
				'default' => esc_html__('Search...', 'durotan'),
				'active_callback' => array(
					array(
						'setting'  => 'header_search_type',
						'operator' => '==',
						'value'    => 'product',
					),
				),
			),
			'header_search_custom_field_4'              => array(
				'type'            => 'custom',
				'section'         => 'header_search',
				'default'         => '<hr/>',
				'active_callback' => array(
					array(
						'setting'  => 'header_search_type',
						'operator' => '==',
						'value'    => 'product',
					),
					array(
						'setting'  => 'header_search_layout',
						'operator' => '==',
						'value'    => 'icon',
					),
				),
			),
			'header_search_cat_filter'                        => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Categories Filter', 'durotan'),
				'section'     => 'header_search',
				'default'     => 0,
				'active_callback' => array(
					array(
						'setting'  => 'header_search_type',
						'operator' => '==',
						'value'    => 'product',
					),
					array(
						'setting'  => 'header_search_layout',
						'operator' => '==',
						'value'    => 'icon',
					),
				),
			),
			'header_search_cat_number'                      => array(
				'type'            => 'number',
				'label'           => esc_html__('Categories Numbers', 'durotan'),
				'default'         => 4,
				'section'         => 'header_search',
				'active_callback' => array(
					array(
						'setting'  => 'header_search_type',
						'operator' => '==',
						'value'    => 'product',
					),
					array(
						'setting'  => 'header_search_cat_filter',
						'operator' => '==',
						'value'    => 1,
					),
					array(
						'setting'  => 'header_search_layout',
						'operator' => '==',
						'value'    => 'icon',
					),
				),
			),
			'header_search_ajax'                        => array(
				'type'        => 'toggle',
				'label'       => esc_html__('AJAX Search', 'durotan'),
				'section'     => 'header_search',
				'default'     => 0,
				'description' => esc_html__('Check this option to enable AJAX search in the header', 'durotan'),
			),
			'header_search_number'                      => array(
				'type'            => 'number',
				'label'           => esc_html__('AJAX Product Numbers', 'durotan'),
				'default'         => 5,
				'section'         => 'header_search',
				'active_callback' => array(
					array(
						'setting'  => 'header_search_ajax',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			// Account
			'header_account_type' => array(
				'type'    => 'radio',
				'label'   => esc_html__( 'Account Type', 'durotan' ),
				'default' => 'icon',
				'section' => 'header_account',
				'choices' => array(
					'icon' => esc_attr__( 'Icon', 'durotan' ),
					'text' => esc_attr__( 'Text', 'durotan' ),
				),
			),
			'header_account_type_text' => array(
				'type'            => 'text',
				'label'           => esc_html__('Label', 'durotan'),
				'default'         => 'Account',
				'section'         => 'header_account',
				'active_callback' => array(
					array(
						'setting'  => 'header_account_type',
						'operator' => '==',
						'value'    => 'text',
					),
				),
			),
			// Header Cart
			'header_cart_type' => array(
				'type'    => 'radio',
				'label'   => esc_html__( 'Cart Type', 'durotan' ),
				'default' => 'icon',
				'section' => 'header_cart',
				'choices' => array(
					'icon' => esc_attr__( 'Icon', 'durotan' ),
					'text' => esc_attr__( 'Text', 'durotan' ),
				),
			),
			'header_cart_type_text' => array(
				'type'            => 'text',
				'label'           => esc_html__('Label', 'durotan'),
				'default'         => 'Basket',
				'section'         => 'header_cart',
				'active_callback' => array(
					array(
						'setting'  => 'header_cart_type',
						'operator' => '==',
						'value'    => 'text',
					),
				),
			),
			'header_cart_custom_field_1' => array(
				'type'    => 'custom',
				'section' => 'header_cart',
				'default' => '<hr/',
			),
			'header_cart_behaviour' => array(
				'type'    => 'radio',
				'label'   => esc_html__( 'Cart Behaviour', 'durotan' ),
				'default' => 'page',
				'section' => 'header_cart',
				'choices' => array(
					'page' => esc_attr__( 'Open the cart page', 'durotan' ),
					'panel' => esc_attr__( 'Open the cart panel', 'durotan' ),
				),
			),
			'header_cart_custom_field_2' => array(
				'type'    => 'custom',
				'section' => 'header_cart',
				'default' => '<hr/',
			),
			'header_cart_side_type'                       => array(
				'type'    => 'select',
				'label'   => esc_html__('Show Menu', 'durotan'),
				'section' => 'header_cart',
				'default' => 'side-right',
				'choices' => array(
					'side-left'  => esc_html__('Side to right', 'durotan'),
					'side-right' => esc_html__('Side to left', 'durotan'),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_cart_behaviour',
						'operator' => '==',
						'value'    => 'panel',
					),
				),
			),
			'header_cart_custom_field_3' => array(
				'type'    => 'custom',
				'section' => 'header_cart',
				'default' => '<hr/',
			),
			'header_cart_total'     => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Enable Total Price', 'durotan' ),
				'description' => esc_html__( 'Enable total price next to the icon or text on the header.', 'durotan' ),
				'section'     => 'header_cart',
				'default'     => 0,
			),
			'header_cart_custom_color'     => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Enable Custom Color', 'durotan' ),
				'section'     => 'header_cart',
				'default'     => 0,
			),
			'header_cart_custom_color_counter'              => array(
				'type'            => 'color',
				'label'           => esc_html__('Color Counter', 'durotan'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_cart',
				'active_callback' => array(
					array(
						'setting'  => 'header_cart_custom_color',
						'operator' => '==',
						'value'    => '1',
					),
					array(
						'setting'  => 'header_cart_type',
						'operator' => '==',
						'value'    => 'icon',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.header-cart__counter',
						'property' => 'background-color',
					),
				),
			),
			'header_cart_custom_color_total_price'              => array(
				'type'            => 'color',
				'label'           => esc_html__('Color Total Price', 'durotan'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_cart',
				'active_callback' => array(
					array(
						'setting'  => 'header_cart_custom_color',
						'operator' => '==',
						'value'    => '1',
					),
					array(
						'setting'  => 'header_cart_total',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.header-cart__total-price',
						'property' => 'color',
					),
				),
			),

			// Header Wishlist
			'header_wishlist_link' => array(
				'type'    => 'text',
				'label'   => esc_html__( 'Custom Wishlist Link', 'durotan' ),
				'section' => 'header_wishlist',
				'default' => '',
			),
			'header_wishlist_custom_color'     => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Enable Custom Color', 'durotan' ),
				'section'     => 'header_wishlist',
				'default'     => 0,
			),
			'header_wishlist_custom_color_counter'              => array(
				'type'            => 'color',
				'label'           => esc_html__('Color Counter', 'durotan'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_wishlist',
				'active_callback' => array(
					array(
						'setting'  => 'header_wishlist_custom_color',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.header-wishlist__counter',
						'property' => 'background-color',
					),
				),
			),

			// Header Compare
			'header_compare_custom_color'     => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Enable Custom Color', 'durotan' ),
				'section'     => 'header_compare',
				'default'     => 0,
			),
			'header_compare_custom_color_counter'              => array(
				'type'            => 'color',
				'label'           => esc_html__('Color Counter', 'durotan'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_compare',
				'active_callback' => array(
					array(
						'setting'  => 'header_compare_custom_color',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.header-compare__counter',
						'property' => 'background-color',
					),
				),
			),

			// Header Currencies
			'header_currencies_type'	=> array(
				'type'    => 'select',
				'label'   => esc_html__('Currencies Type', 'durotan'),
				'section' => 'header_currencies',
				'default' => 'dropdown',
				'choices' => array(
					'horizontal'        => esc_attr__( 'Horizontal', 'durotan' ),
					'dropdown'       	=> esc_attr__( 'Dropdown', 'durotan' ),
				),
			),

			// Header Language
			'header_languages_type'	=> array(
				'type'    => 'select',
				'label'   => esc_html__('Language Type', 'durotan'),
				'section' => 'header_languages',
				'default' => 'horizontal',
				'choices' => array(
					'horizontal'        => esc_attr__( 'Horizontal', 'durotan' ),
					'dropdown'       	=> esc_attr__( 'Dropdown', 'durotan' ),
				),
			),

			// Blog Header
			'blog_page_header'     => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Enable Page Header', 'durotan' ),
				'description' => esc_html__( 'Enable the blog header on blog pages.', 'durotan' ),
				'section'     => 'blog_page_header',
				'default'     => 1,
			),

			'blog_page_header_els'   => array(
				'type'            => 'multicheck',
				'label'           => esc_html__( 'Elements', 'durotan' ),
				'section'         => 'blog_page_header',
				'default'         => array( 'title', 'description' ),
				'priority'        => 10,
				'choices'         => array(
					'title'      => esc_html__( 'Title', 'durotan' ),
					'description' => esc_html__( 'Description', 'durotan' ),
				),
				'description'     => esc_html__( 'Select which elements you want to show.', 'durotan' ),
				'active_callback' => array(
					array(
						'setting'  => 'blog_page_header',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			'blog_header_title'    => array(
				'type'            => 'text',
				'label'           => esc_html__( 'Title', 'durotan' ),
				'default'         => esc_html__( 'The Latest Posts', 'durotan' ),
				'section'    	  => 'blog_page_header',
				'active_callback' => array(
					array(
						'setting'  => 'blog_page_header',
						'operator' => '==',
						'value'    => 1,
					),
					array(
						'setting'  => 'blog_page_header_els',
						'operator' => 'in',
						'value'    => 'title',
					),
				),
			),
			'blog_header_description'   => array(
				'type'       	  => 'textarea',
				'label'        	  => esc_html__( 'Description', 'durotan' ),
				'default'     	  => '',
				'section'    	  => 'blog_page_header',
				'active_callback' => array(
					array(
						'setting'  => 'blog_page_header',
						'operator' => '==',
						'value'    => 1,
					),
					array(
						'setting'  => 'blog_page_header_els',
						'operator' => 'in',
						'value'    => 'description',
					),
				),
			),
			// Blog
			'blog_custom_field_1' => array(
				'type'    	  => 'custom',
				'section' 	  => 'blog_page',
				'default' 	  => '<h2>' . esc_html__( 'Blog Archive', 'durotan' ) . '</h2>',
			),
			'blog_type'                                 => array(
				'type'        => 'radio',
				'label'       => esc_html__( 'Type', 'durotan' ),
				'description' => esc_html__( 'The layout of blog posts', 'durotan' ),
				'default'     => 'classic',
				'choices'     => array(
					'listing'    => esc_attr__( 'Listing', 'durotan' ),
					'grid' 		 => esc_attr__( 'Grid', 'durotan' ),
					'classic'    => esc_attr__( 'Classic', 'durotan' ),
				),
				'section'     => 'blog_page',
			),

			'blog_layout' => array(
				'type'            => 'select',
				'label'           => esc_html__( 'Layout', 'durotan' ),
				'section'         => 'blog_page',
				'default'         => 'content-sidebar',
				'description'     => esc_html__( 'Select default sidebar for the single post page.', 'durotan' ),
				'choices'         => array(
					'content-sidebar' => esc_html__( 'Content Sidebar', 'durotan' ),
					'sidebar-content' => esc_html__( 'Sidebar Content', 'durotan' ),
					'full-content' => esc_html__( 'Full Content', 'durotan' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'blog_type',
						'operator' => '!=',
						'value'    => 'grid',
					),
				),
			),

			'excerpt_length' => array(
				'type'     => 'number',
				'label'    => esc_html__( 'Excerpt Length', 'durotan' ),
				'section'  => 'blog_page',
				'default'  => 45,
				'active_callback' => array(
					array(
						'setting'  => 'blog_type',
						'operator' => '==',
						'value'    => 'listing',
					),
				),
			),
			// Featured Content
			'featured_content'                        => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Featured Content', 'durotan' ),
				'description' => esc_html__( 'Display the featured content section on blog page', 'durotan' ),
				'section'     => 'blog_featured_content',
				'default'     => 0,
			),
			'featured_content_tag_slug'   => array(
				'type'            => 'select',
				'section'         => 'blog_featured_content',
				'label'           => esc_html__( 'Select Tags', 'durotan' ),
				'description'     => esc_html__( 'Select tag you want to show.', 'durotan' ),
				'default'         => 'featured',
				'multiple'        => 999,
				'choices'         => $this->get_all_tags( 'post_tag' ),
				'active_callback' => array(
					array(
						'setting'  => 'featured_content',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
			'featured_content_number' => array(
				'type'     => 'number',
				'label'    => esc_html__( 'Number of Items', 'durotan' ),
				'section'  => 'blog_featured_content',
				'default'  => 4,
				'choices'     => array(
					'max'  => 10,
				),
				'active_callback' => array(
					array(
						'setting'  => 'featured_content',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
			'featured_content_height' => array(
				'type'        => 'slider',
				'label'       => esc_html__( 'Height', 'durotan' ),
				'section'     => 'blog_featured_content',
				'default'     => '670',
				'choices'     => array(
					'min'  => 0,
					'max'  => 2400,
				),
				'transport' => 'postMessage',
				'js_vars'   => array(
					array(
					'element'  => '.durotan-featured',
					'property' => 'height',
					'units' => 'px',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'featured_content',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
			'featured_content_type'      => array(
				'type'        => 'radio',
				'label'       => esc_html__( 'Width', 'durotan' ),
				'default'     => 'wide',
				'choices'     => array(
					'wide' 	  => esc_attr__( 'Wide', 'durotan' ),
					'full'    => esc_attr__( 'Full Width', 'durotan' ),
				),
				'section'     => 'blog_featured_content',
				'active_callback' => array(
					array(
						'setting'  => 'featured_content',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			// NewsLetter
			'newsletter_status'                        => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Enable', 'durotan' ),
				'description' => esc_html__( 'Display newsletter.', 'durotan' ),
				'section'     => 'blog_newsletter',
				'default'     => 0,
			),
			'newsletter_title'    => array(
				'type'            => 'text',
				'label'           => esc_html__( 'NewsLetter Title', 'durotan' ),
				'default'         => esc_html__( 'Our Newsletter', 'durotan' ),
				'section'    	  => 'blog_newsletter',
				'active_callback' => array(
					array(
						'setting'  => 'newsletter_status',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
			'newsletter_popup_form' => array(
				'type'            => 'textarea',
				'label'           => esc_html__('NewsLetter Form', 'durotan'),
				'default'         => '',
				'description'     => sprintf(wp_kses_post('Enter the shortcode of MailChimp form . You can edit your sign - up form in the <a href= "%s" > MailChimp for WordPress form settings </a>.', 'durotan'), admin_url('admin.php?page=mailchimp-for-wp-forms')),
				'section'         => 'blog_newsletter',
				'transport'       => 'postMessage',
				'partial_refresh' => array(
					'newsletter_popup_form' => array(
						'selector'        => '#newsletter-popup-modal .newsletter-popup-form',
						'render_callback' => function () {
							echo do_shortcode(wp_kses_post(Helper::get_option('newsletter_popup_form'))) . \Durotan\Icon:: get_svg('paper-plane');
						},
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'newsletter_status',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			// Featured Posts
			'featured_posts_status'          => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Enable', 'durotan' ),
				'description' => esc_html__( 'Display featured posts.', 'durotan' ),
				'section'     => 'blog_featured_post',
				'default'     => 0,
			),
			'featured_posts_title'    => array(
				'type'            => 'text',
				'label'           => esc_html__( 'Featured Posts Title', 'durotan' ),
				'default'         => esc_html__( 'Latest Articles', 'durotan' ),
				'section'    	  => 'blog_featured_post',
				'active_callback' => array(
					array(
						'setting'  => 'featured_posts_status',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
			'featured_posts_tag_slug'   => array(
				'type'            => 'select',
				'section'         => 'blog_featured_post',
				'label'           => esc_html__( 'Select Tags', 'durotan' ),
				'description'     => esc_html__( 'Select tag you want to show.', 'durotan' ),
				'default'         => 'featured-posts',
				'multiple'        => 999,
				'choices'         => $this->get_all_tags( 'post_tag' ),
				'active_callback' => array(
					array(
						'setting'  => 'featured_posts_status',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
			'featured_posts_number' => array(
				'type'     => 'number',
				'label'    => esc_html__( 'Number of Items', 'durotan' ),
				'section'  => 'blog_featured_post',
				'default'  => 2,
				'choices'     => array(
					'max'  => 10,
				),
				'active_callback' => array(
					array(
						'setting'  => 'featured_posts_status',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
			'featured_posts_orderby'      => array(
				'type'     => 'select',
				'label'    => esc_html__( 'Orderby', 'durotan' ),
				'section'  => 'blog_featured_post',
				'default'  => 'title',
				'choices'  => array(
					'id'    	=> esc_html__( 'ID', 'durotan' ),
					'title' 	=> esc_html__( 'Title', 'durotan' ),
					'date' 		=> esc_html__( 'Date', 'durotan' ),
					'menu_order' => esc_html__( 'Menu Order', 'durotan' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'featured_posts_status',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
			'featured_posts_order'        => array(
				'type'     => 'select',
				'label'    => esc_html__( 'Order', 'durotan' ),
				'section'  => 'blog_featured_post',
				'default'  => 'DESC',
				'choices'  => array(
					'DESC' => esc_html__( 'DESC', 'durotan' ),
					'ASC'  => esc_html__( 'ASC', 'durotan' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'featured_posts_status',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			// Blog Toolbar
			'blog_categories_filter_custom_field_2' => array(
				'type'    => 'custom',
				'section' => 'blog_toolbar',
				'default' => '<hr/><h2>' . esc_html__( 'Blog Toolbar', 'durotan' ) . '</h2>',
			),
			'show_blog_cats'                        => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Categories Filter', 'durotan' ),
				'section'     => 'blog_toolbar',
				'default'     => 0,
				'description' => esc_html__( 'Display categories list above posts list', 'durotan' ),
				'priority'    => 10,
			),

			'custom_blog_cats' => array(
				'type'     => 'toggle',
				'label'    => esc_html__( 'Custom Categories List', 'durotan' ),
				'section'  => 'blog_toolbar',
				'default'  => 0,
				'priority' => 10,
				'active_callback' => array(
					array(
						'setting'  => 'show_blog_cats',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
			'blog_cats_slug'   => array(
				'type'            => 'select',
				'section'         => 'blog_toolbar',
				'label'           => esc_html__( 'Custom Categories', 'durotan' ),
				'description'     => esc_html__( 'Select product categories you want to show.', 'durotan' ),
				'default'         => '',
				'multiple'        => 999,
				'priority'        => 10,
				'choices'         => $this->get_categories( 'category' ),
				'active_callback' => array(
					array(
						'setting'  => 'custom_blog_cats',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			'blog_cats_view_all'     => array(
				'type'     => 'text',
				'label'    => esc_html__( 'View All Text', 'durotan' ),
				'section'  => 'blog_toolbar',
				'default'  => esc_html__( 'All', 'durotan' ),
				'priority' => 10,
				'active_callback' => array(
					array(
						'setting'  => 'show_blog_cats',
						'operator' => '==',
						'value'    => 1,
					),
					array(
						'setting'  => 'custom_blog_cats',
						'operator' => '==',
						'value'    => 0,
					),
				),
			),
			'blog_cats_orderby'      => array(
				'type'     => 'select',
				'label'    => esc_html__( 'Categories Orderby', 'durotan' ),
				'section'  => 'blog_toolbar',
				'default'  => 'count',
				'priority' => 10,
				'choices'  => array(
					'count' => esc_html__( 'Count', 'durotan' ),
					'title' => esc_html__( 'Title', 'durotan' ),
					'id'    => esc_html__( 'ID', 'durotan' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'show_blog_cats',
						'operator' => '==',
						'value'    => 1,
					),
					array(
						'setting'  => 'custom_blog_cats',
						'operator' => '==',
						'value'    => 0,
					),
				),
			),
			'blog_cats_order'        => array(
				'type'     => 'select',
				'label'    => esc_html__( 'Categories Order', 'durotan' ),
				'section'  => 'blog_toolbar',
				'default'  => 'DESC',
				'priority' => 10,
				'choices'  => array(
					'DESC' => esc_html__( 'DESC', 'durotan' ),
					'ASC'  => esc_html__( 'ASC', 'durotan' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'show_blog_cats',
						'operator' => '==',
						'value'    => 1,
					),
					array(
						'setting'  => 'custom_blog_cats',
						'operator' => '==',
						'value'    => 0,
					),
				),
			),
			'blog_cats_number'       => array(
				'type'     => 'number',
				'label'    => esc_html__( 'Categories Number', 'durotan' ),
				'section'  => 'blog_toolbar',
				'default'  => 5,
				'priority' => 10,
				'active_callback' => array(
					array(
						'setting'  => 'show_blog_cats',
						'operator' => '==',
						'value'    => 1,
					),
					array(
						'setting'  => 'custom_blog_cats',
						'operator' => '==',
						'value'    => 0,
					),
				),
			),
			// Search
			'show_blog_search'                        => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Search Box', 'durotan' ),
				'section'     => 'blog_toolbar',
				'default'     => 0,
				'description' => esc_html__( 'Display search form above posts list', 'durotan' ),
				'priority'    => 10,
			),

			// Pagination
			'blog_pagination_custom_field_1' => array(
				'type'    => 'custom',
				'section' => 'blog_page',
				'default' => '<hr/><h2>' . esc_html__( 'Pagination', 'durotan' ) . '</h2>',
			),
			'blog_pagination_type'                                 => array(
				'type'        => 'radio',
				'label'       => esc_html__( 'Pagination Type', 'durotan' ),
				'default'     => 'numeric',
				'choices'     => array(
					'numeric'    => esc_attr__( 'Numeric', 'durotan' ),
					'load' 		 => esc_attr__( 'Load more', 'durotan' ),
				),
				'section'     => 'blog_page',
			),
			'blog_pagination_view_more_text'                   => array(
				'type'     => 'text',
				'label'    => esc_html__( 'Loading Text', 'durotan' ),
				'section'  => 'blog_page',
				'default'  => esc_html__( 'Load more', 'durotan' ),
				'active_callback' => array(
					array(
						'setting'  => 'blog_pagination_type',
						'operator' => '==',
						'value'    => 'load',
					),
				),
			),
			'blog_pagination_position_load' => array(
				'type'            => 'select',
				'label'           => esc_html__( 'Pagination Position', 'durotan' ),
				'section'         => 'blog_page',
				'default'         => 'center',
				'choices'         => array(
					'left' => esc_html__( 'Left', 'durotan' ),
					'center' => esc_html__( 'Center', 'durotan' ),
					'right' => esc_html__( 'Right', 'durotan' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'blog_pagination_type',
						'operator' => '==',
						'value'    => 'load',
					),
				),
			),
			'blog_pagination_position_numeric' => array(
				'type'            => 'select',
				'label'           => esc_html__( 'Pagination Position', 'durotan' ),
				'section'         => 'blog_page',
				'default'         => 'left',
				'choices'         => array(
					'left' => esc_html__( 'Left', 'durotan' ),
					'center' => esc_html__( 'Center', 'durotan' ),
					'right' => esc_html__( 'Right', 'durotan' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'blog_pagination_type',
						'operator' => '==',
						'value'    => 'numeric',
					),
				),
			),

			// Single Post
			'single_post_layout' => array(
				'type'        => 'select',
				'label'       => esc_html__( 'Layout', 'durotan' ),
				'description' => esc_html__( 'Select default sidebar for the single post page.', 'durotan' ),
				'default'     => 'full-content',
				'section'     => 'single_post',
				'choices'     => array(
					'content-sidebar' => esc_html__( 'Content Sidebar', 'durotan' ),
					'sidebar-content' => esc_html__( 'Sidebar Content', 'durotan' ),
					'full-content'    => esc_html__( 'Full Content', 'durotan' ),
				),
			),

			'single_post_featured' => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Enable featured image', 'durotan' ),
				'default'     => '1',
				'section'     => 'single_post',
				'description' => esc_html__( 'Display the featured image on the post', 'durotan' ),
			),

			'post_socials_toggle' => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Socials Share', 'durotan' ),
				'description' => esc_html__( 'Check this option to show socials share in the single post.', 'durotan' ),
				'section'     => 'single_post',
				'default'     => 0,
			),

			'post_socials_share'           => array(
				'type'            => 'multicheck',
				'label'           => esc_html__( 'Socials List', 'durotan' ),
				'section'         => 'single_post',
				'default'         => array( 'facebook', 'twitter', 'googleplus', 'tumblr' ),
				'choices'         => array(
					'facebook'    => esc_html__( 'Facebook', 'durotan' ),
					'twitter'     => esc_html__( 'Twitter', 'durotan' ),
					'googleplus'  => esc_html__( 'Google Plus', 'durotan' ),
					'tumblr'      => esc_html__( 'Tumblr', 'durotan' ),
					'pinterest'   => esc_html__( 'Pinterest', 'durotan' ),
					'linkedin'    => esc_html__( 'Linkedin', 'durotan' ),
					'reddit'      => esc_html__( 'Reddit', 'durotan' ),
					'telegram'    => esc_html__( 'Telegram', 'durotan' ),
					'pocket'      => esc_html__( 'Pocket', 'durotan' ),
					'email'       => esc_html__( 'Email', 'durotan' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'post_socials_toggle',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			// Author Box
			'author_box_custom_field_1' => array(
				'type'    => 'custom',
				'section' => 'single_post',
				'default' => '<hr/><h2>' . esc_html__( 'Author Box', 'durotan' ) . '</h2>',
			),

			'author_box'             => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Enable', 'durotan' ),
				'description' => esc_html__( 'Check this option to show author box.', 'durotan' ),
				'section'     => 'single_post',
				'default'     => 0,
				'priority'    => 10,
			),

			// Related Posts
			'related_posts_custom_field_1' => array(
				'type'    => 'custom',
				'section' => 'single_post',
				'default' => '<hr/><h2>' . esc_html__( 'Related Posts', 'durotan' ) . '</h2>',
			),

			'related_posts'             => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Enable', 'durotan' ),
				'description' => esc_html__( 'Check this option to show related posts.', 'durotan' ),
				'section'     => 'single_post',
				'default'     => 0,
			),
			'related_posts_title'       => array(
				'type'     => 'text',
				'label'    => esc_html__( 'Title', 'durotan' ),
				'section'  => 'single_post',
				'default'  => esc_html__( 'Related Posts', 'durotan' ),
				'active_callback' => array(
					array(
						'setting'  => 'related_posts',
						'operator' => '==',
						'value'    => 1,
					),
				),

			),

			// Footer
			'footer_sections'           => array(
				'type'        => 'sortable',
				'label'       => esc_html__('Footer Sections', 'durotan'),
				'description' => esc_html__('Select and order footer contents', 'durotan'),
				'default'     => '',
				'choices'     => array(
					'newsletter' => esc_attr__('Newsletter', 'durotan'),
					'widgets'    => esc_attr__('Footer Widgets', 'durotan'),
					'main'       => esc_attr__('Footer Main', 'durotan'),
					'extra'      => esc_attr__('Footer Extra', 'durotan'),
				),
				'section' => 'footer_layout',
			),
			'footer_layout_custom_hr_1' => array(
				'type'    => 'custom',
				'default' => '<hr/>',
				'section' => 'footer_layout',
			),
			'footer_container'          => array(
				'type'        => 'select',
				'label'       => esc_html__('Footer Width', 'durotan'),
				'description' => esc_html__('Select the width of footer container', 'durotan'),
				'default'     => 'durotan-container',
				'choices'     => array(
					'container'               	=> esc_attr__( 'Standard', 'durotan' ),
					'durotan-container'       	=> esc_attr__( 'Large', 'durotan' ),
					'durotan-container-narrow'	=> esc_attr__( 'Fluid', 'durotan' ),
					'durotan-container-fluid' 	=> esc_attr__( 'Full Width', 'durotan' ),
				),
				'section' => 'footer_layout',
			),

			'footer_layout_custom_hr_2' => array(
				'type'    => 'custom',
				'default' => '<hr/>',
				'section' => 'footer_layout',
			),

			'footer_border' => array(
				'type'            => 'toggle',
				'label'           => esc_html__( 'Enable Border', 'durotan' ),
				'section'         => 'footer_layout',
				'default'         => 1,
			),

			'footer_layout_custom_hr_3' => array(
				'type'    => 'custom',
				'default' => '<hr/>',
				'section' => 'footer_layout',
			),

			'footer_parallax' => array(
				'type'            => 'toggle',
				'label'           => esc_html__( 'Enable Parallax', 'durotan' ),
				'section'         => 'footer_layout',
				'default'         => 0,
			),

			'footer_parallax_bg_color' => array(
				'label'     => esc_html__('Site Content Background Color', 'durotan'),
				'type'      => 'color',
				'default'   => '',
				'transport' => 'postMessage',
				'js_vars'   => array(
					array(
						'element'  => 'body.footer-has-parallax',
						'property' => '--durotan-site-content-background-color',
					),
				),
				'section' => 'footer_layout',
				'active_callback' => array(
					array(
						'setting'  => 'footer_parallax',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			// Footer newsletter
			'footer_newsletter_text' => array(
				'type'            => 'text',
				'label'           => esc_html__('Title', 'durotan'),
				'section'         => 'footer_newsletter',
				'default'         => '',
			),

			'footer_newsletter_form' => array(
				'type'            => 'textarea',
				'label'           => esc_html__('Form', 'durotan'),
				'description'     => esc_html__('Enter the shortcode of MailChimp form', 'durotan'),
				'section'         => 'footer_newsletter',
				'default'         => '',
			),

			'custom_newsletter_link_to_form' => array(
				'type'            => 'custom',
				'section'         => 'footer_newsletter',
				'default'         => sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=mailchimp-for-wp-forms'), esc_html__('Go to MailChimp form', 'durotan')),
			),

			// Footer Widget
			'footer_widgets_layout'     => array(
				'type'        => 'select',
				'label'       => esc_html__('Layout', 'durotan'),
				'description'=> esc_html__('Go to appearance > widgets find to Footer Widget to edit your sidebar', 'durotan'),
				'default'     => 'v1',
				'choices'     => array(
					'v1'      => esc_html__('Layout 1', 'durotan'),
					'v2'      => esc_html__('Layout 2', 'durotan'),
					'v3'      => esc_html__('Layout 3', 'durotan'),
				),
				'section' => 'footer_widget',
			),

			'footer_widgets_columns'     => array(
				'type'        => 'select',
				'label'       => esc_html__('Select Columns', 'durotan'),
				'default'     => '4',
				'choices'     => array(
					'1'      => esc_html__('1 Columns', 'durotan'),
					'2'      => esc_html__('2 Columns', 'durotan'),
					'3'      => esc_html__('3 Columns', 'durotan'),
					'4'      => esc_html__('4 Columns', 'durotan'),
					'5'      => esc_html__('5 Columns', 'durotan'),
				),
				'section' => 'footer_widget',
			),

			// Footer Extra
			'footer_extra_left'             => array(
				'type'            => 'repeater',
				'label'           => esc_html__( 'Left Items', 'durotan' ),
				'description'     => esc_html__( 'Control left items of the footer', 'durotan' ),
				'transport'       => 'postMessage',
				'default'         => array( array( 'item' => 'copyright' ) ),
				'row_label'       => array(
					'type'  => 'field',
					'value' => esc_attr__( 'Item', 'durotan' ),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'choices' => \Durotan\Footer::footer_items_option(),
					),
				),
				'partial_refresh' => array(
					'footer_extra_left' => array(
						'selector'            => '.footer-extra',
						'container_inclusive' => true,
						'render_callback'     => function() {
							get_template_part( 'template-parts/footer/footer-extra' );
						},
					),
				),
				'section'         => 'footer_extra',
			),
			'footer_extra_center'           => array(
				'type'            => 'repeater',
				'label'           => esc_html__( 'Center Items', 'durotan' ),
				'description'     => esc_html__( 'Control center items of the footer', 'durotan' ),
				'transport'       => 'postMessage',
				'default'         => array(),
				'row_label'       => array(
					'type'  => 'field',
					'value' => esc_attr__( 'Item', 'durotan' ),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'choices' => \Durotan\Footer::footer_items_option(),
					),
				),
				'partial_refresh' => array(
					'footer_extra_center' => array(
						'selector'            => '.footer-extra',
						'container_inclusive' => true,
						'render_callback'     => function() {
							get_template_part( 'template-parts/footer/footer-extra' );
						},
					),
				),
				'section'         => 'footer_extra',
			),
			'footer_extra_right'            => array(
				'type'            => 'repeater',
				'label'           => esc_html__( 'Right Items', 'durotan' ),
				'description'     => esc_html__( 'Control right items of the footer', 'durotan' ),
				'transport'       => 'postMessage',
				'default'         => array( array( 'item' => 'menu' ) ),
				'row_label'       => array(
					'type'  => 'field',
					'value' => esc_attr__( 'Item', 'durotan' ),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'default' => 'copyright',
						'choices' => \Durotan\Footer::footer_items_option(),
					),
				),
				'partial_refresh' => array(
					'footer_extra_right' => array(
						'selector'            => '.footer-extra',
						'container_inclusive' => true,
						'render_callback'     => function() {
							get_template_part( 'template-parts/footer/footer-extra' );
						},
					),
				),
				'section'         => 'footer_extra',
			),

			// Footer Main
			'footer_main_left'             => array(
				'type'            => 'repeater',
				'label'           => esc_html__( 'Left Items', 'durotan' ),
				'description'     => esc_html__( 'Control left items of the footer', 'durotan' ),
				'transport'       => 'postMessage',
				'default'         => array( array( 'item' => 'copyright' ) ),
				'row_label'       => array(
					'type'  => 'field',
					'value' => esc_attr__( 'Item', 'durotan' ),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'choices' => \Durotan\Footer::footer_items_option(),
					),
				),
				'partial_refresh' => array(
					'footer_main_left' => array(
						'selector'            => '.footer-main',
						'container_inclusive' => true,
						'render_callback'     => function() {
							get_template_part( 'template-parts/footer/footer' );
						},
					),
				),
				'section'         => 'footer_main',
			),
			'footer_main_center'           => array(
				'type'            => 'repeater',
				'label'           => esc_html__( 'Center Items', 'durotan' ),
				'description'     => esc_html__( 'Control center items of the footer', 'durotan' ),
				'transport'       => 'postMessage',
				'default'         => array(),
				'row_label'       => array(
					'type'  => 'field',
					'value' => esc_attr__( 'Item', 'durotan' ),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'choices' => \Durotan\Footer::footer_items_option(),
					),
				),
				'partial_refresh' => array(
					'footer_main_center' => array(
						'selector'            => '.footer-main',
						'container_inclusive' => true,
						'render_callback'     => function() {
							get_template_part( 'template-parts/footer/footer' );
						},
					),
				),
				'section'         => 'footer_main',
			),
			'footer_main_right'            => array(
				'type'            => 'repeater',
				'label'           => esc_html__( 'Right Items', 'durotan' ),
				'description'     => esc_html__( 'Control right items of the footer', 'durotan' ),
				'transport'       => 'postMessage',
				'default'         => array( array( 'item' => 'menu' ) ),
				'row_label'       => array(
					'type'  => 'field',
					'value' => esc_attr__( 'Item', 'durotan' ),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'default' => 'copyright',
						'choices' => \Durotan\Footer::footer_items_option(),
					),
				),
				'partial_refresh' => array(
					'footer_main_right' => array(
						'selector'            => '.footer-main',
						'container_inclusive' => true,
						'render_callback'     => function() {
							get_template_part( 'template-parts/footer/footer' );
						},
					),
				),
				'section'         => 'footer_main',
			),

			// Footer Background
			'footer_background_scheme'       => array(
				'type'    => 'toggle',
				'label'   => esc_html__('Enable background Scheme', 'durotan'),
				'section' => 'footer_background',
				'default' => 0,
			),

			'footer_bg_color' => array(
				'label'     => esc_html__('Background Color', 'durotan'),
				'type'      => 'color',
				'default'   => '',
				'transport' => 'postMessage',
				'js_vars'   => array(
					array(
						'element'  => '.site-footer ',
						'property' => '--durotan-footer-background-color',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			'footer_bg_heading_color' => array(
				'type'      => 'color',
				'label'     => esc_html__('Heading Color', 'durotan'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '.site-footer',
						'property' => '--durotan-footer-heading-color',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			'footer_bg_text_color' => array(
				'type'      => 'color',
				'label'     => esc_html__('Text Color', 'durotan'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '.site-footer',
						'property' => '--durotan-footer-text-color',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			'footer_bg_text_color_hover' => array(
				'type'      => 'color',
				'label'     => esc_html__('Text Hover Color', 'durotan'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '.site-footer',
						'property' => '--durotan-footer-text-color-hover',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			'footer_bg_field' => array(
				'type'      => 'color',
				'label'     => esc_html__('Field Color', 'durotan'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '.site-footer',
						'property' => '--durotan-footer-field-background-color',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			'footer_bg_field_text_placeholder' => array(
				'type'      => 'color',
				'label'     => esc_html__('Field Text Placeholder Color', 'durotan'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '.site-footer',
						'property' => '--durotan-footer-field-text-placeholder-color',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			'footer_bg_field_border_color' => array(
				'type'      => 'color',
				'label'     => esc_html__('Field Boder Color', 'durotan'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '.site-footer',
						'property' => '--durotan-footer-field-border-color',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			'footer_bg_button' => array(
				'type'      => 'color',
				'label'     => esc_html__('Button Color', 'durotan'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '.site-footer',
						'property' => '--durotan-footer-button-background-color',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			'footer_bg_button_hover' => array(
				'type'      => 'color',
				'label'     => esc_html__('Button Color Hover', 'durotan'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '.site-footer',
						'property' => '--durotan-footer-button-background-color-hover',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			'footer_bg_button_text' => array(
				'type'      => 'color',
				'label'     => esc_html__('Button Text Color', 'durotan'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '.site-footer',
						'property' => '--durotan-footer-button-text-color',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			// Footer Item
			'footer_copyright'           => array(
				'type'        => 'textarea',
				'label'       => esc_html__( 'Footer Copyright', 'durotan' ),
				'description' => esc_html__( 'Display copyright info on the left side of footer', 'durotan' ),
				'default'     => sprintf( '%s <strong>%s</strong>. ' . esc_html__( 'All rights reserved', 'durotan' ), '&copy;' . date( 'Y' ), get_bloginfo( 'name' ) ),
				'section'     => 'footer_copyright',
			),
			'footer_menu'       => array(
				'type'            => 'select',
				'label'           => esc_html__( 'Menu', 'durotan' ),
				'section'         => 'footer_menu',
				'default'         => '',
				'choices'         => $this->get_navigation_bar_get_menus(),

			),
			'footer_payment_label' => array(
				'type'            => 'text',
				'label'           => esc_html__('Label', 'durotan'),
				'section'         => 'footer_payment',
				'default'         => '',
			),
			'footer_payment_images' => array(
				'type'      => 'repeater',
				'label'     => esc_html__('Payment Images', 'durotan'),
				'section'   => 'footer_payment',
				'row_label' => array(
					'type'  => 'text',
					'value' => esc_html__('Image', 'durotan'),
				),
				'fields'    => array(
					'image' => array(
						'type'    => 'image',
						'label'   => esc_html__('Image', 'durotan'),
						'default' => '',
					),
					'link'  => array(
						'type'    => 'text',
						'label'   => esc_html__('Link', 'durotan'),
						'default' => '',
					),
				),
			),
			'footer_currencies_type'	=> array(
				'type'    => 'select',
				'label'   => esc_html__('Currencies Type', 'durotan'),
				'section' => 'footer_currencies',
				'default' => 'list-dropdown',
				'choices' => array(
					'horizontal'	=> esc_attr__( 'Horizontal', 'durotan' ),
					'list-dropdown' => esc_attr__( 'Dropdown', 'durotan' ),
				),
			),
			'footer_languages_type'	=> array(
				'type'    => 'select',
				'label'   => esc_html__('Language Type', 'durotan'),
				'section' => 'footer_languages',
				'default' => 'horizontal',
				'choices' => array(
					'horizontal'	=> esc_attr__( 'Horizontal', 'durotan' ),
					'list-dropdown'	=> esc_attr__( 'Dropdown', 'durotan' ),
				),
			),

		);


		$settings['panels']   = apply_filters( 'durotan_customize_panels', $panels );
		$settings['sections'] = apply_filters( 'durotan_customize_sections', $sections );
		$settings['fields']   = apply_filters( 'durotan_customize_fields', $fields );

		return $settings;
	}

	/**
	 * Get categories
	 *
	 * @since 1.0.0
	 *
	 * @param $taxonomies
	 * @param $default
	 *
	 * @return array
	 */
	public function get_categories( $taxonomies, $default = false ) {
		if ( ! taxonomy_exists( $taxonomies ) ) {
			return [];
		}

		if ( ! is_admin() ) {
			return [];
		}

		$output = [];

		if ( $default ) {
			$output[0] = esc_html__( 'Select Category', 'durotan' );
		}

		global $wpdb;
		$post_meta_infos = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT a.term_id AS id, b.name as name, b.slug AS slug
						FROM {$wpdb->term_taxonomy} AS a
						INNER JOIN {$wpdb->terms} AS b ON b.term_id = a.term_id
						WHERE a.taxonomy = '%s'", $taxonomies
			), ARRAY_A
		);

		if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
			foreach ( $post_meta_infos as $value ) {
				$output[ $value['slug'] ] = $value['name'];
			}
		}

		return $output;
	}

	/**
	 * Get tags
	 *
	 * @since 1.0.0
	 *
	 * @param $taxonomy
	 * @param $default
	 *
	 * @return array
	 */
	public function get_all_tags( $taxonomy, $default = false ) {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			return [];
		}

		if ( ! is_admin() ) {
			return [];
		}

		$output = [];

		if ( $default ) {
			$output[0] = esc_html__( 'Select Tags', 'durotan' );
		}

		$tags = get_tags( array(
							'taxonomy' => $taxonomy,
							'hide_empty' => false,
						) );
		foreach ( $tags as $tag )
		{
			$output[ $tag->slug ] = $tag->name;
		}

		return $output;
	}

	/**
	 * Get nav menus
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_navigation_bar_get_menus() {
		if ( ! is_admin() ) {
			return [];
		}

		$menus = wp_get_nav_menus();
		if ( ! $menus ) {
			return [];
		}

		$output = array(
			0 => esc_html__( 'Select Menu', 'durotan' ),
		);
		foreach ( $menus as $menu ) {
			$output[ $menu->slug ] = $menu->name;
		}

		return $output;
	}
}