<?php
/**
 * WooCommerce Customizer functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan\WooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Durotan WooCommerce Customizer class
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
		$panels['woocommerce'] = array(
			'title'    => esc_html__( 'Woocommerce', 'durotan' ),
		);

		$panels['product_catalog'] = array(
			'title'    => esc_html__( 'Product Catalog', 'durotan' ),
		);

		$panels['shop_product'] = array(
			'title'    => esc_html__( 'Single Product', 'durotan' ),
		);

		$panels['recently_viewed'] = array(
			'title'    => esc_html__( 'Recently Viewed', 'durotan' ),
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
			'woocommerce_cart' => array(
				'title'    => esc_html__( 'Cart', 'durotan' ),
				'panel'    => 'woocommerce',
			),
		) );
		// Product loop
		$sections = array_merge( $sections, array(
			'product_loop' => array(
				'title' => esc_html__( 'Product Loop', 'durotan' ),
				'panel' => 'woocommerce',
			),
		) );

		// Product Notification
		$sections = array_merge( $sections, array(
			'product_notifications' => array(
				'title'    => esc_html__( 'Product Notifications', 'durotan' ),
				'panel'    => 'woocommerce',
			),
		) );

		// Badges
		$sections = array_merge( $sections, array(
			'shop_badges' => array(
				'title'    => esc_html__( 'Badges', 'durotan' ),
				'panel'    => 'woocommerce',
			),
		) );

		// Catalog Page
		$sections = array_merge( $sections, array(
			'catalog_layout' => array(
				'title' => esc_html__( 'Catalog Layout', 'durotan' ),
				'panel' => 'product_catalog',
			),
			'catalog_page_header' => array(
				'title' => esc_html__( 'Page Header', 'durotan' ),
				'panel' => 'product_catalog',
			),
			'catalog_toolbar' => array(
				'title' => esc_html__( 'Catalog Toolbar', 'durotan' ),
				'panel' => 'product_catalog',
			),

		) );

		// Product Page
		$sections = array_merge( $sections, array(
			'single_product_layout'  => array(
				'title'    => esc_html__( 'Product Layout', 'durotan' ),
				'panel'    => 'shop_product',
			),
			'buy_now_button'         => array(
				'title'    => esc_html__( 'Buy Now', 'durotan' ),
				'panel'    => 'shop_product',
			),
			'sticky_add_to_cart'     => array(
				'title'    => esc_html__( 'Sticky Add To Cart', 'durotan' ),
				'panel'    => 'shop_product',
			),
			'single_product_related' => array(
				'title'    => esc_html__( 'Related Products', 'durotan' ),
				'panel'    => 'shop_product',
			),
			'single_product_upsells' => array(
				'title'    => esc_html__( 'Upsells Products', 'durotan' ),
				'panel'    => 'shop_product',
			),
			'single_product_sharing' => array(
				'title'    => esc_html__( 'Product Sharing', 'durotan' ),
				'panel'    => 'shop_product',
			)
		) );

		// Recently Viewed
		$sections = array_merge( $sections, array(
			'recently_viewed'  => array(
				'title'      => esc_html__('Recently Viewed', 'durotan'),
				'capability' => 'edit_theme_options',
				'panel'    => 'recently_viewed',
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
				// Page Cart
				'page_cart_show_payments'       => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Show payments', 'durotan' ),
					'description' => esc_html__( 'Change content to: Customize > Footer > Payments', 'durotan' ),
					'default'     => 1,
					'section'     => 'woocommerce_cart',
				),
				'page_cart_show_payments_label'          => array(
					'type'            => 'text',
					'label'           => esc_html__( 'Label', 'durotan' ),
					'default'         => esc_attr__( 'Accept Payment Methods', 'durotan' ),
					'active_callback' => array(
						array(
							'setting'  => 'page_cart_show_payments',
							'operator' => '=',
							'value'    => 1,
						),
					),
					'section'         => 'woocommerce_cart',
				),
			));
		// Product loop
		$fields = array_merge(
			$fields, array(
				'product_loop_layout'                 => array(
					'type'     => 'select',
					'label'    => esc_html__( 'Product Loop Layout', 'durotan' ),
					'default'  => '1',
					'section'  => 'product_loop',
					'choices'  => array(
						'1' => esc_html__( 'Icons & Add to cart text', 'durotan' ),
						'2' => esc_html__( 'Icons over thumbnail', 'durotan' ),
						'3' => esc_html__( 'Add To Cart Button', 'durotan' ),
						'4' => esc_html__( 'Icons & Add To Cart Button', 'durotan' ),
						'5' => esc_html__( 'Icons Boxed & Add To Cart Button', 'durotan' ),
						'6' => esc_html__( 'Buttons Over Thumbnail', 'durotan' ),
						'7' => esc_html__( 'Quick View button', 'durotan' ),
					),
				),
				'product_loop_hover_type' => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Product Loop Hover', 'durotan' ),
					'description' => esc_html__( 'Product hover animation.', 'durotan' ),
					'default' => 'classic',
					'section' => 'product_loop',
					'choices' => array(
						'none'		=> esc_attr__( 'Classic', 'durotan' ),
						'slider' 	=> esc_attr__( 'Slider', 'durotan' ),
						'fadein'    => esc_attr__( 'Fadein', 'durotan' ),
					),
				),
				'product_loop_hr'               => array(
					'type'    => 'custom',
					'section' => 'product_loop',
					'default' => '<hr/>',
					'active_callback' => array(
						array(
							'setting'  => 'product_loop_layout',
							'operator' => '!==',
							'value'    => '7',
						),
					),
				),
				'product_loop_featured_icons'         => array(
					'type'            => 'multicheck',
					'label'           => esc_html__( 'Featured Icons', 'durotan' ),
					'section'         => 'product_loop',
					'default'         => array( 'cart', 'quickview', 'wishlist', 'compare' ),
					'choices'         => array(
						'cart' 		=> esc_html__( 'Cart', 'durotan' ),
						'quickview' => esc_html__( 'Quick View', 'durotan' ),
						'wishlist'	=> esc_html__( 'Wishlist', 'durotan' ),
						'compare' 	=> esc_html__( 'Compare', 'durotan' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'product_loop_layout',
							'operator' => '!==',
							'value'    => '7',
						),
						array(
							'setting'  => 'product_loop_layout',
							'operator' => '!==',
							'value'    => '3',
						),
					),
				),
				'product_loop_featured_icons_2'         => array(
					'type'            => 'multicheck',
					'label'           => esc_html__( 'Featured Icons', 'durotan' ),
					'section'         => 'product_loop',
					'default'         => array( 'cart', 'quickview', 'wishlist' ),
					'choices'         => array(
						'cart' 		=> esc_html__( 'Cart', 'durotan' ),
						'quickview' => esc_html__( 'Quick View', 'durotan' ),
						'wishlist'	=> esc_html__( 'Wishlist', 'durotan' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'product_loop_layout',
							'operator' => '==',
							'value'    => '3',
						),
					),
				),
				'product_loop_attributes_custom'      => array(
					'type'     => 'custom',
					'section'  => 'product_loop',
					'default'  => '<hr/>',
				),
				'product_loop_attributes'             => array(
					'type'     => 'multicheck',
					'label'    => esc_html__( 'Attributes', 'durotan' ),
					'section'  => 'product_loop',
					'default'  => array( 'rating' ),
					'choices'  => array(
						'rating'   => esc_html__( 'Rating', 'durotan' ),
						'taxonomy' => esc_html__( 'Taxonomy', 'durotan' ),
					),
				),
				'product_loop_taxonomy'               => array(
					'type'            => 'select',
					'label'           => esc_html__( 'Product Taxonomy', 'durotan' ),
					'section'         => 'product_loop',
					'default'         => 'product_cat',
					'priority'        => 10,
					'choices'         => array(
						'product_cat'   => esc_html__( 'Category', 'durotan' ),
						'product_brand' => esc_html__( 'Brand', 'durotan' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'product_loop_attributes',
							'operator' => 'in',
							'value'    => 'taxonomy',
						),
					),
				),
				'product_loop_hr_2'               => array(
					'type'    => 'custom',
					'section' => 'product_loop',
					'default' => '<hr/>',
					'active_callback' => array(
						array(
							'setting'  => 'product_loop_layout',
							'operator' => '!==',
							'value'    => '7',
						),
					),
				),
				'custom_quick_view'       => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Custom Quick View Text', 'durotan' ),
					'default'     => false,
					'section'     => 'product_loop',
					'active_callback' => array(
						array(
							'setting'  => 'product_loop_layout',
							'operator' => '!==',
							'value'    => '7',
						),
					),
				),
				'custom_quick_view_text'          => array(
					'type'            => 'text',
					'default'         => esc_attr__( 'Quick Shop', 'durotan' ),
					'active_callback' => array(
						array(
							'setting'  => 'custom_quick_view',
							'operator' => '=',
							'value'    => true,
						),
						array(
							'setting'  => 'product_loop_layout',
							'operator' => '!==',
							'value'    => '7',
						),
					),
					'section'         => 'product_loop',
				),
				'product_loop_hr_3'               => array(
					'type'    => 'custom',
					'section' => 'product_loop',
					'default' => '<hr/>',
					'active_callback' => array(
						array(
							'setting'  => 'product_loop_layout',
							'operator' => 'in',
							'value'    => [ '1', '2', '3', '4', '5'],
						),
					),
				),
				'product_loop_attributes_product_variable'      => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Hide Attributes on Variable Products', 'durotan' ),
					'default'     => false,
					'section'     => 'product_loop',
					'active_callback' => array(
						array(
							'setting'  => 'product_loop_layout',
							'operator' => 'in',
							'value'    => [ '1', '2', '3', '4', '5'],
						),
					),
				),
			)
		);

		// Product Notification
		$fields = array_merge(
			$fields, array(
				// Added to cart Notice
				'added_to_cart_notice'                => array(
					'type'        => 'select',
					'label'       => esc_html__( 'Added to Cart Notice', 'durotan' ),
					'description' => esc_html__( 'Display a notification when a product is added to cart.', 'durotan' ),
					'default'     => 'panel',
					'section'     => 'product_notifications',
					'choices'     => array(
						'panel'  => esc_attr__( 'Open mini cart panel', 'durotan' ),
						'none'   => esc_attr__( 'None', 'durotan' ),
					),
				),
			)
		);

		// Badges
		$fields = array_merge(
			$fields, array(
				'product_catalog_badges' => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Catalog Badges', 'durotan' ),
					'description' => esc_html__( 'Display the badges in the catalog page', 'durotan' ),
					'default'     => true,
					'section'     => 'shop_badges',
				),

				'single_product_badges' => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Product Badges', 'durotan' ),
					'description' => esc_html__( 'Display the badges in the single page', 'durotan' ),
					'default'     => false,
					'section'     => 'shop_badges',
				),

				// Badges
				'product_hr_sale'               => array(
					'type'    => 'custom',
					'section' => 'shop_badges',
					'default' => '<hr/><h3>' . esc_html__( 'Sale Badges', 'durotan' ) . '</h3>',
				),
				'shop_badges_sale'               => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Enalble', 'durotan' ),
					'description' => esc_html__( 'Display a badges for sale products.', 'durotan' ),
					'default'     => true,
					'section'     => 'shop_badges',
				),
				'shop_badges_sale_type'          => array(
					'type'            => 'radio',
					'label'           => esc_html__( 'Type', 'durotan' ),
					'default'         => 'percent',
					'choices'         => array(
						'percent' => esc_html__( 'Percentage', 'durotan' ),
						'text'    => esc_html__( 'Text', 'durotan' ),
						'both'    => esc_html__( 'Both', 'durotan' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'shop_badges_sale',
							'operator' => '=',
							'value'    => true,
						),
					),
					'section'         => 'shop_badges',
				),
				'shop_badges_sale_text'          => array(
					'type'            => 'text',
					'label'           => esc_html__( 'Text', 'durotan' ),
					'tooltip'         => esc_html__( 'Use {%} to display discount percentages, {$} to display discount amount.', 'durotan' ),
					'default'         => esc_attr__( 'Off', 'durotan' ),
					'active_callback' => array(
						array(
							'setting'  => 'shop_badges_sale',
							'operator' => '=',
							'value'    => true,
						),
						array(
							'setting'  => 'shop_badges_sale_type',
							'operator' => 'in',
							'value'    => array( 'text', 'both' ),
						),
					),
					'section'         => 'shop_badges',
				),
				'shop_badges_sale_color'         => array(
					'type'            => 'color',
					'label'           => esc_html__( 'Color', 'durotan' ),
					'default'         => '',
					'active_callback' => array(
						array(
							'setting'  => 'shop_badges_sale',
							'operator' => '=',
							'value'    => true,
						),
					),
					'transport'       => 'postMessage',
					'js_vars'         => array(
						array(
							'element'  => '.woocommerce-badges .onsale',
							'property' => 'color',
						),
					),
					'section'         => 'shop_badges',
				),
				'shop_badges_sale_bg'            => array(
					'type'            => 'color',
					'label'           => esc_html__( 'Background', 'durotan' ),
					'default'         => '',
					'active_callback' => array(
						array(
							'setting'  => 'shop_badges_sale',
							'operator' => '=',
							'value'    => true,
						),
					),
					'transport'       => 'postMessage',
					'js_vars'         => array(
						array(
							'element'  => '.woocommerce-badges .onsale',
							'property' => 'background-color',
						),
					),
					'section'         => 'shop_badges',
				),

				'product_hr_new' => array(
					'type'    => 'custom',
					'section' => 'shop_badges',
					'default' => '<hr/><h3>' . esc_html__( 'New Badges', 'durotan' ) . '</h3>',
				),

				'shop_badges_new'       => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Enable', 'durotan' ),
					'description' => esc_html__( 'Display a badges for new products.', 'durotan' ),
					'default'     => true,
					'section'     => 'shop_badges',
				),
				'shop_badges_new_text'  => array(
					'type'            => 'text',
					'label'           => esc_html__( 'Text', 'durotan' ),
					'default'         => esc_attr__( 'New', 'durotan' ),
					'active_callback' => array(
						array(
							'setting'  => 'shop_badges_new',
							'operator' => '=',
							'value'    => true,
						),
					),
					'section'         => 'shop_badges',
				),
				'shop_badges_newness'   => array(
					'type'            => 'number',
					'description'     => esc_html__( 'Display the "New" badges for how many days?', 'durotan' ),
					'tooltip'         => esc_html__( 'You can also add the NEW badges to each product in the Advanced setting tab of them.', 'durotan' ),
					'default'         => 3,
					'active_callback' => array(
						array(
							'setting'  => 'shop_badges_new',
							'operator' => '=',
							'value'    => true,
						),
					),
					'section'         => 'shop_badges',
				),
				'shop_badges_new_color' => array(
					'type'            => 'color',
					'label'           => esc_html__( 'Color', 'durotan' ),
					'default'         => '',
					'active_callback' => array(
						array(
							'setting'  => 'shop_badges_new',
							'operator' => '=',
							'value'    => true,
						),
					),
					'transport'       => 'postMessage',
					'js_vars'         => array(
						array(
							'element'  => '.woocommerce-badges .new',
							'property' => 'color',
						),
					),
					'section'         => 'shop_badges',
				),
				'shop_badges_new_bg'    => array(
					'type'            => 'color',
					'label'           => esc_html__( 'Background', 'durotan' ),
					'default'         => '',
					'active_callback' => array(
						array(
							'setting'  => 'shop_badges_new',
							'operator' => '=',
							'value'    => true,
						),
					),
					'transport'       => 'postMessage',
					'js_vars'         => array(
						array(
							'element'  => '.woocommerce-badges .new',
							'property' => 'background-color',
						),
					),
					'section'         => 'shop_badges',
				),

				'product_hr_featured' => array(
					'type'    => 'custom',
					'section' => 'shop_badges',
					'default' => '<hr/><h3>' . esc_html__( 'Featured Badges', 'durotan' ) . '</h3>',
				),

				'shop_badges_featured'       => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Enable', 'durotan' ),
					'description' => esc_html__( 'Display a badges for featured products.', 'durotan' ),
					'default'     => false,
					'section'     => 'shop_badges',
				),
				'shop_badges_featured_text'  => array(
					'type'            => 'text',
					'label'           => esc_html__( 'Text', 'durotan' ),
					'default'         => esc_attr__( 'Hot', 'durotan' ),
					'active_callback' => array(
						array(
							'setting'  => 'shop_badges_featured',
							'operator' => '=',
							'value'    => true,
						),
					),
					'section'         => 'shop_badges',
				),
				'shop_badges_featured_color' => array(
					'type'            => 'color',
					'label'           => esc_html__( 'Color', 'durotan' ),
					'default'         => '',
					'active_callback' => array(
						array(
							'setting'  => 'shop_badges_featured',
							'operator' => '=',
							'value'    => true,
						),
					),
					'transport'       => 'postMessage',
					'js_vars'         => array(
						array(
							'element'  => '.woocommerce-badges .featured',
							'property' => 'color',
						),
					),
					'section'         => 'shop_badges',
				),
				'shop_badges_featured_bg'    => array(
					'type'            => 'color',
					'label'           => esc_html__( 'Background', 'durotan' ),
					'default'         => '',
					'active_callback' => array(
						array(
							'setting'  => 'shop_badges_featured',
							'operator' => '=',
							'value'    => true,
						),
					),
					'transport'       => 'postMessage',
					'js_vars'         => array(
						array(
							'element'  => '.woocommerce-badges .featured',
							'property' => 'background-color',
						),
					),
					'section'         => 'shop_badges',
				),

				'product_hr_soldout'       => array(
					'type'    => 'custom',
					'section' => 'shop_badges',
					'default' => '<hr/><h3>' . esc_html__( 'Sold Out Badges', 'durotan' ) . '</h3>',
				),
				'shop_badges_soldout'       => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Enable', 'durotan' ),
					'description' => esc_html__( 'Display a badges for out of stock products.', 'durotan' ),
					'default'     => false,
					'section'     => 'shop_badges',
				),
				'shop_badges_soldout_text'  => array(
					'type'            => 'text',
					'label'           => esc_html__( 'Text', 'durotan' ),
					'default'         => esc_attr__( 'Sold Out', 'durotan' ),
					'active_callback' => array(
						array(
							'setting'  => 'shop_badges_soldout',
							'operator' => '=',
							'value'    => true,
						),
					),
					'section'         => 'shop_badges',
				),
				'shop_badges_soldout_color' => array(
					'type'            => 'color',
					'label'           => esc_html__( 'Color', 'durotan' ),
					'default'         => '',
					'active_callback' => array(
						array(
							'setting'  => 'shop_badges_soldout',
							'operator' => '=',
							'value'    => true,
						),
					),
					'transport'       => 'postMessage',
					'js_vars'         => array(
						array(
							'element'  => '.woocommerce-badges .sold-out',
							'property' => 'color',
						),
					),
					'section'         => 'shop_badges',
				),
				'shop_badges_soldout_bg'    => array(
					'type'            => 'color',
					'label'           => esc_html__( 'Background', 'durotan' ),
					'default'         => '',
					'active_callback' => array(
						array(
							'setting'  => 'shop_badges_soldout',
							'operator' => '=',
							'value'    => true,
						),
					),
					'transport'       => 'postMessage',
					'js_vars'         => array(
						array(
							'element'  => '.woocommerce-badges .sold-out',
							'property' => 'background-color',
						),
					),
					'section'         => 'shop_badges',
				),
			)
		);

		// Catalog page.
		$fields = array_merge(
			$fields, array(
				// Shop product catalog
				'shop_catalog_layout' => array(
					'type'    => 'radio',
					'label'   => esc_html__( 'Catalog Layout', 'durotan' ),
					'default' => 'grid',
					'choices' => array(
						'grid'    => esc_attr__( 'Grid', 'durotan' ),
						'masonry' => esc_attr__( 'Masonry', 'durotan' ),
					),
					'section' => 'catalog_layout',
				),

				'catalog_sidebar' => array(
					'type'            => 'select',
					'label'           => esc_html__( 'Sidebar', 'durotan' ),
					'tooltip'         => esc_html__( 'Go to appearance > widgets find to catalog sidebar to edit your sidebar', 'durotan' ),
					'default'         => 'full-content',
					'choices'         => array(
						'content-sidebar' => esc_html__( 'Right Sidebar', 'durotan' ),
						'sidebar-content' => esc_html__( 'Left Sidebar', 'durotan' ),
						'full-content'    => esc_html__( 'No Sidebar', 'durotan' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'shop_catalog_layout',
							'operator' => '==',
							'value'    => 'grid',
						)
					),
					'section'         => 'catalog_layout',
				),

				'catalog_widget_collapse_content' => array(
					'type'            => 'toggle',
					'label'           => esc_html__( 'Collapse Widget', 'durotan' ),
					'default'         => 1,
					'active_callback' => array(
						array(
							'setting'  => 'catalog_sidebar',
							'operator' => '!=',
							'value'    => 'full-content',
						),
						array(
							'setting'  => 'shop_catalog_layout',
							'operator' => '==',
							'value'    => 'grid',
						),

					),
					'section'         => 'catalog_layout',
				),

				'catalog_hr_1'             => array(
					'type'    => 'custom',
					'default' => '<hr>',
					'section' => 'catalog_layout',
				),

				// Content Width
				'catalog_width'	=> array(
					'type'    => 'select',
					'label'   => esc_html__('Content Width', 'durotan'),
					'section' => 'catalog_layout',
					'default' => 'durotan-container',
					'choices' => array(
						'container'               	=> esc_attr__( 'Standard', 'durotan' ),
						'durotan-container'       	=> esc_attr__( 'Large', 'durotan' ),
						'durotan-container-narrow'	=> esc_attr__( 'Fluid', 'durotan' ),
						'durotan-container-fluid' 	=> esc_attr__( 'Full Width', 'durotan' ),
					),
				),

				'catalog_hr_2'             => array(
					'type'    => 'custom',
					'default' => '<hr>',
					'section' => 'catalog_layout',
				),

				'catalog_category_tabs' => array(
					'type'    => 'toggle',
					'label'   => esc_html__( 'Catalog Category Tabs', 'durotan' ),
					'section' => 'catalog_layout',
					'default' => 0,
				),
				'catalog_category_tabs_number'                      => array(
					'type'            => 'number',
					'label'           => esc_html__('Categories Tabs Numbers', 'durotan'),
					'default'         => 5,
					'section'         => 'catalog_layout',
					'active_callback' => array(
						array(
							'setting'  => 'catalog_category_tabs',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),

				'catalog_toolbar_filtered' => array(
					'type'    => 'toggle',
					'label'   => esc_html__( 'Enable Active Product Filters', 'durotan' ),
					'section' => 'catalog_layout',
					'default' => 1,
				),

				'product_catalog_navigation' => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Navigation Type', 'durotan' ),
					'default' => 'loadmore',
					'choices' => array(
						'numeric'  => esc_attr__( 'Numeric', 'durotan' ),
						'loadmore' => esc_attr__( 'Load More', 'durotan' ),
						'infinite' => esc_attr__( 'Infinite Scroll', 'durotan' ),
					),
					'section' => 'catalog_layout',
				),

				// Page Header
				'catalog_page_header' => array(
					'type'    => 'radio',
					'label'   => esc_html__( 'Layout', 'durotan' ),
					'default' => '',
					'section' => 'catalog_page_header',
					'choices' => array(
						''         => esc_attr__( 'None', 'durotan' ),
						'layout-1' => esc_attr__( 'Layout 1', 'durotan' ),
						'layout-2' => esc_attr__( 'Layout 2', 'durotan' ),
						'layout-3' => esc_attr__( 'Layout 3', 'durotan' ),
					),
				),

				'catalog_page_header_transparent'               => array(
					'type'    => 'toggle',
					'label'   => esc_html__( 'Header Transparent', 'durotan' ),
					'section' => 'catalog_page_header',
					'default' => '',
					'active_callback' => array(
						array(
							'setting'  => 'catalog_page_header',
							'operator' => '==',
							'value'    => 'layout-2',
						),
					),
				),

				'catalog_page_header_image' => array(
					'type'            => 'image',
					'label'           => esc_html__( 'Image', 'durotan' ),
					'default'         => '',
					'section'         => 'catalog_page_header',
					'active_callback' => array(
						array(
							'setting'  => 'catalog_page_header',
							'operator' => '==',
							'value'    => 'layout-2',
						),
					),
				),

				'catalog_page_header_els'   => array(
					'type'            => 'multicheck',
					'label'           => esc_html__( 'Elements', 'durotan' ),
					'section'         => 'catalog_page_header',
					'default'         => array( 'breadcrumb', 'title' ),
					'priority'        => 10,
					'choices'         => array(
						'breadcrumb' => esc_html__( 'BreadCrumb', 'durotan' ),
						'title'      => esc_html__( 'Title', 'durotan' ),
						'description' => esc_html__( 'Description', 'durotan' ),
					),
					'description'     => esc_html__( 'Select which elements you want to show.', 'durotan' ),
					'active_callback' => array(
						array(
							'setting'  => 'catalog_page_header',
							'operator' => '!=',
							'value'    => '',
						),
					),
				),

				'catalog_page_header_description_limit' => array(
					'type'            => 'number',
					'label'           => esc_html__( 'Limit Description', 'durotan' ),
					'default'         => 25,
					'section'         => 'catalog_page_header',
					'active_callback' => array(
						array(
							'setting'  => 'catalog_page_header',
							'operator' => '!=',
							'value'    => '',
						),
						array(
							'setting'  => 'catalog_page_header_els',
							'operator' => 'contains',
							'value'    => 'description',
						),
					),
				),

				'catalog_page_header_custom_field_1' => array(
					'type'            => 'custom',
					'section'         => 'catalog_page_header',
					'default'         => '<hr/><h3>' . esc_html__( 'Custom', 'durotan' ) . '</h3>',
					'active_callback' => array(
						array(
							'setting'  => 'catalog_page_header',
							'operator' => '!=',
							'value'    => '',
						),
					),
				),

				'catalog_page_header_text_color' => array(
					'type'            => 'color',
					'label'           => esc_html__( 'Title Color', 'durotan' ),
					'transport'       => 'postMessage',
					'default'         => '',
					'active_callback' => array(
						array(
							'setting'  => 'catalog_page_header',
							'operator' => '==',
							'value'    => 'layout-2',
						),
						array(
							'setting'  => 'catalog_page_header_els',
							'operator' => 'in',
							'value'    => 'title',
						),
					),
					'js_vars'         => array(
						array(
							'element'  => '.page-header__catalog--layout-2 .page-header__title',
							'property' => '--durotan-color-darker',
						),
					),
					'section'         => 'catalog_page_header',

				),

				'catalog_page_header_description_text_color' => array(
					'type'            => 'color',
					'label'           => esc_html__( 'Description Color', 'durotan' ),
					'transport'       => 'postMessage',
					'default'         => '',
					'active_callback' => array(
						array(
							'setting'  => 'catalog_page_header',
							'operator' => '==',
							'value'    => 'layout-2',
						),
						array(
							'setting'  => 'catalog_page_header_els',
							'operator' => 'in',
							'value'    => 'description',
						),
					),
					'js_vars'         => array(
						array(
							'element'  => '.page-header__catalog--layout-2 .page-header__description',
							'property' => 'color',
						),
					),
					'section'         => 'catalog_page_header',

				),

				'catalog_page_header_bread_link_color' => array(
					'type'            => 'color',
					'label'           => esc_html__( 'Breadcrumb Link Color', 'durotan' ),
					'transport'       => 'postMessage',
					'default'         => '',
					'active_callback' => array(
						array(
							'setting'  => 'catalog_page_header',
							'operator' => '==',
							'value'    => 'layout-2',
						),
						array(
							'setting'  => 'catalog_page_header_els',
							'operator' => 'in',
							'value'    => 'breadcrumb',
						),
					),
					'js_vars'         => array(
						array(
							'element'  => '.page-header__catalog-page .woocommerce-breadcrumb a, .page-header__catalog-page .woocommerce-breadcrumb .delimiter',
							'property' => '--durotan-color-gray',
						),
					),
					'section'         => 'catalog_page_header',
				),
				'catalog_page_header_bread_color' => array(
					'type'            => 'color',
					'label'           => esc_html__( 'Breadcrumb Text Color', 'durotan' ),
					'transport'       => 'postMessage',
					'default'         => '',
					'active_callback' => array(
						array(
							'setting'  => 'catalog_page_header',
							'operator' => '==',
							'value'    => 'layout-2',
						),
						array(
							'setting'  => 'catalog_page_header_els',
							'operator' => 'in',
							'value'    => 'breadcrumb',
						),
					),
					'js_vars'         => array(
						array(
							'element'  => '.page-header__catalog--layout-2 .woocommerce-breadcrumb',
							'property' => 'color',
						),
					),
					'section'         => 'catalog_page_header',
				),

				'catalog_page_header_padding_top' => array(
					'type'            => 'slider',
					'label'           => esc_html__( 'Padding Top', 'durotan' ),
					'transport'       => 'postMessage',
					'section'         => 'catalog_page_header',
					'default'         => '75',
					'choices'         => array(
						'min' => 0,
						'max' => 700,
					),
					'js_vars'         => array(
						array(
							'element'  => '.page-header__catalog-page',
							'property' => 'padding-top',
							'units'    => 'px',
						),
					),
					'active_callback' => array(
						array(
							'setting'  => 'catalog_page_header',
							'operator' => '!=',
							'value'    => '',
						),
					),
				),

				'catalog_page_header_padding_bottom' => array(
					'type'            => 'slider',
					'label'           => esc_html__( 'Padding Bottom', 'durotan' ),
					'transport'       => 'postMessage',
					'section'         => 'catalog_page_header',
					'default'         => '75',
					'choices'         => array(
						'min' => 0,
						'max' => 700,
					),
					'js_vars'         => array(
						array(
							'element'  => '.page-header__catalog-page',
							'property' => 'padding-bottom',
							'units'    => 'px',
						),
					),
					'active_callback' => array(
						array(
							'setting'  => 'catalog_page_header',
							'operator' => '!=',
							'value'    => '',
						),
					),
				),

				// Catalog Toolbar
				'catalog_toolbar'       => array(
					'type'    => 'toggle',
					'label'   => esc_html__( 'Catalog Toolbar', 'durotan' ),
					'default' => true,
					'section' => 'catalog_toolbar',
				),
				'catalog_toolbar_elements'                          => array(
					'type'    => 'multicheck',
					'label'   => esc_html__( 'ToolBar Elements', 'durotan' ),
					'default' => array( 'filter', 'total_product', 'sort_by', 'view' ),
					'choices' => array(
						'filter'          => esc_attr__( 'Filter', 'durotan' ),
						'total_product' => esc_attr__( 'Total Products', 'durotan' ),
						'sort_by'       => esc_attr__( 'Sort by', 'durotan' ),
						'view'          => esc_attr__( 'View', 'durotan' ),
					),
					'description' => esc_html__('Select which elements you want to show.','durotan'),
					'section' => 'catalog_toolbar',
					'active_callback' => array(
						array(
							'setting'  => 'catalog_toolbar',
							'operator' => '==',
							'value'    => 1,
						),
					),
				),

				'catalog_toolbar_view_type' => array(
					'type'            => 'sortable',
					'label'           => esc_html__( 'Catalog Type', 'durotan' ),
					'section'         => 'catalog_toolbar',
					'default'         => array( 'grid-3', 'grid', 'list' ),
					'choices'         => array(
						'grid-3'   => esc_attr__( 'Grid 3', 'durotan' ),
						'grid'     => esc_attr__( 'Grid', 'durotan' ),
						'list'     => esc_attr__( 'List', 'durotan' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'catalog_toolbar_elements',
							'operator' => 'contains',
							'value'    => 'view',
						),
						array(
							'setting'  => 'catalog_toolbar',
							'operator' => '==',
							'value'    => 1,
						),
					),
					'description'	=> esc_html__('The first value will become the default type.','durotan')
				),
			)
		);

		// Product Page
		$fields = array_merge(
			$fields, array(
				'product_layout'           => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Product Layout', 'durotan' ),
					'default' => 'v1',
					'section' => 'single_product_layout',
					'choices' => array(
						'v1' => esc_html__( 'Layout 1', 'durotan' ),
						'v2' => esc_html__( 'Layout 2', 'durotan' ),
						'v3' => esc_html__( 'Layout 3', 'durotan' ),
						'v4' => esc_html__( 'Layout 4', 'durotan' ),
						'v5' => esc_html__( 'Layout 5', 'durotan' ),
						'v6' => esc_html__( 'Layout 6', 'durotan' ),
						'v7' => esc_html__( 'Layout 7', 'durotan' ),
					),
				),
				'product_hr_1'                => array(
					'type'    => 'custom',
					'default' => '<hr>',
				),
				'product_auto_background'     => array(
					'type'            => 'toggle',
					'label'           => esc_html__( 'Auto Background', 'durotan' ),
					'description'     => esc_html__( 'Detect background color from product main image', 'durotan' ),
					'default'         => true,
					'active_callback' => array(
						array(
							'setting'  => 'product_layout',
							'operator' => 'in',
							'value'    => array( 'v3', 'v5'),
						),
					),
					'section'         => 'single_product_layout',
				),
				'product_transparent_header'     => array(
					'type'            => 'toggle',
					'label'           => esc_html__( 'Header Transparent', 'durotan' ),
					'default'         => false,
					'active_callback' => array(
						array(
							'setting'  => 'product_layout',
							'operator' => 'in',
							'value'    => array( 'v5' ),
						),
					),
					'section'         => 'single_product_layout',
				),
				'product_content_bg_color' => array(
					'type'            => 'color',
					'label'           => esc_html__( 'Content Background Color', 'durotan' ),
					'transport'       => 'postMessage',
					'default'         => '',
					'active_callback' => array(
						array(
							'setting'  => 'product_layout',
							'operator' => 'in',
							'value'    => array( 'v6'),
						),
					),
					'js_vars'         => array(
						array(
							'element'  => '.single-product div.product.layout-v6 .summary',
							'property' => 'background-color',
						),
					),
					'section'         => 'single_product_layout',
				),
				'product_hr_2'             => array(
					'type'    => 'custom',
					'default' => '<hr>',
					'section' => 'single_product_layout',
				),
				// Content Width
				'single_product_width'	=> array(
					'type'    => 'select',
					'label'   => esc_html__('Content Width', 'durotan'),
					'section' => 'single_product_layout',
					'default' => 'durotan-container',
					'choices' => array(
						'container'               	=> esc_attr__( 'Standard', 'durotan' ),
						'durotan-container'       	=> esc_attr__( 'Large', 'durotan' ),
						'durotan-container-narrow'	=> esc_attr__( 'Fluid', 'durotan' ),
						'durotan-container-fluid' 	=> esc_attr__( 'Full Width', 'durotan' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'product_layout',
							'operator' => 'in',
							'value'    => array( 'v1', 'v2', 'v3', 'v4', 'v5'),
						),
					),
				),
				'product_hr_3'             => array(
					'type'    => 'custom',
					'default' => '<hr>',
					'section' => 'single_product_layout',
				),
				'product_back_to_shop'       => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Back to Shop', 'durotan' ),
					'section'     => 'single_product_layout',
					'description' => esc_html__( 'Display "Back to Shop" on top of product page', 'durotan' ),
					'default'     => true,
					'active_callback' => array(
						array(
							'setting'  => 'product_layout',
							'operator' => 'in',
							'value'    => array( 'v3', 'v5'),
						),
					),
				),
				'single_product_breadcrumb'       => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Breadcrumb', 'durotan' ),
					'section'     => 'single_product_layout',
					'description' => esc_html__( 'Display breadcrumb on top of product page', 'durotan' ),
					'default'     => true,
					'active_callback' => array(
						array(
							'setting'  => 'product_layout',
							'operator' => 'in',
							'value'    => array( 'v1', 'v2', 'v4', 'v7'),
						),
					),
				),
				'single_product_navigation'       => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Navigation ', 'durotan' ),
					'section'     => 'single_product_layout',
					'description' => esc_html__( 'Display navigation on top of product page', 'durotan' ),
					'default'     => true,
					'active_callback' => array(
						array(
							'setting'  => 'product_layout',
							'operator' => 'in',
							'value'    => array( 'v1', 'v2', 'v3', 'v4', 'v5', 'v6'),
						),
					),
				),
				'product_add_to_cart_ajax' => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Add to cart with AJAX', 'durotan' ),
					'section'     => 'single_product_layout',
					'default'     => 1,
					'description' => esc_html__( 'Check this option to enable add to cart with AJAX on the product page.', 'durotan' ),
				),
				'product_taxonomy'         => array(
					'type'        => 'select',
					'label'       => esc_html__( 'Product Taxonomy', 'durotan' ),
					'section'     => 'single_product_layout',
					'description' => esc_html__( 'Show a taxonomy above the product title', 'durotan' ),
					'default'     => 'product_cat',
					'choices'     => array(
						'product_cat'   => esc_html__( 'Category', 'durotan' ),
						'product_brand' => esc_html__( 'Brand', 'durotan' ),
						''              => esc_html__( 'None', 'durotan' ),
					),
				),
				'product_hr_4'             => array(
					'type'    => 'custom',
					'default' => '<hr>',
					'section' => 'single_product_layout',
				),
				'product_image_zoom'        => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Image Zoom', 'durotan' ),
					'description' => esc_html__( 'Zooms in where your cursor is on the image', 'durotan' ),
					'default'     => true,
					'section'     => 'single_product_layout',
				),
				'product_image_lightbox'    => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Image Lightbox', 'durotan' ),
					'description' => esc_html__( 'Opens your images against a dark backdrop', 'durotan' ),
					'default'     => true,
					'section'     => 'single_product_layout',
				),
				'product_thumbnail_numbers' => array(
					'type'            => 'number',
					'label'           => esc_html__( 'Thumbnail Numbers', 'durotan' ),
					'default'         => 6,
					'section'         => 'single_product_layout',
				),
				'product_hr_6' => array(
					'type'    => 'custom',
					'default' => '<hr>',
					'section' => 'single_product_layout',
				),
				'product_meta' => array(
					'type'     => 'multicheck',
					'label'    => esc_html__( 'Product Meta', 'durotan' ),
					'section'  => 'single_product_layout',
					'default'  => array( 'sku', 'category', 'tags' ),
					'priority' => 10,
					'choices'  => array(
						'sku'      => esc_html__( 'Sku', 'durotan' ),
						'tags'     => esc_html__( 'Tags', 'durotan' ),
						'category' => esc_html__( 'Category', 'durotan' ),
						'brand'    => esc_html__( 'Brand', 'durotan' ),
					),
				),

				'product_buy_now' => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Buy Now Button', 'durotan' ),
					'section'     => 'buy_now_button',
					'default'     => false,
					'description' => esc_html__( 'Show buy now in the single product.', 'durotan' ),
					'priority'    => 10,
					'active_callback' => array(
						array(
							'setting'  => 'product_layout',
							'operator' => 'in',
							'value'    => array( 'v1', 'v2' ),
						),
					),
				),
				'product_buy_now_text'=> array(
					'type'            => 'text',
					'label'           => esc_html__( 'Buy Now Text', 'durotan' ),
					'description'     => esc_html__( 'Enter Buy not button text.', 'durotan' ),
					'section'         => 'buy_now_button',
					'default'         => esc_html__( 'Buy Now', 'durotan' ),
					'priority'        => 10,
					'active_callback' => array(
						array(
							'setting'  => 'product_layout',
							'operator' => 'in',
							'value'    => array( 'v1', 'v2' ),
						),
						array(
							'setting'  => 'product_buy_now',
							'operator' => '==',
							'value'    => 1,
						),
					),
				),
				'product_buy_now_link'=> array(
					'type'            => 'textarea',
					'label'           => esc_html__( 'Buy Now Link', 'durotan' ),
					'section'         => 'buy_now_button',
					'default'         => '',
					'priority'        => 10,
					'active_callback' => array(
						array(
							'setting'  => 'product_layout',
							'operator' => 'in',
							'value'    => array( 'v1', 'v2' ),
						),
						array(
							'setting'  => 'product_buy_now',
							'operator' => '==',
							'value'    => 1,
						),
					),
				),

				'product_add_to_cart_sticky' => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Sticky Add To Cart', 'durotan' ),
					'section'     => 'sticky_add_to_cart',
					'default'     => false,
					'description' => esc_html__( 'A small content bar at the top of the browser window which includes relevant product information and an add-to-cart button. It slides into view once the standard add-to-cart button has scrolled out of view.', 'durotan' ),
				),

				'product_upsells'                    => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Show Upsells Products', 'durotan' ),
					'section'     => 'single_product_upsells',
					'description' => esc_html__( 'Check this option to show up-sells products in single product page', 'durotan' ),
					'default'     => 1,
					'priority'    => 40,
				),
				'product_upsells_title'              => array(
					'type'     => 'text',
					'label'    => esc_html__( 'Up-sells Products Title', 'durotan' ),
					'section'  => 'single_product_upsells',
					'default'  => esc_html__( 'You may also like', 'durotan' ),
					'priority' => 40,
				),
				'product_upsells_numbers'            => array(
					'type'        => 'number',
					'label'       => esc_html__( 'Up-sells Products Numbers', 'durotan' ),
					'section'     => 'single_product_upsells',
					'default'     => 6,
					'priority'    => 40,
					'description' => esc_html__( 'Specify how many numbers of up-sells products you want to show on single product page', 'durotan' ),
				),
				'product_upsells_columns'            => array(
					'type'        => 'number',
					'label'       => esc_html__( 'Up-sells Products Per Row', 'durotan' ),
					'section'     => 'single_product_upsells',
					'default'     => 5,
					'priority'    => 40,
					'description' => esc_html__( 'Specify the number of of up-sells products you want to display per row', 'durotan' ),
				),

				'product_related' => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Show Related Products', 'durotan' ),
					'section'     => 'single_product_related',
					'description' => esc_html__( 'Check this option to show related products in single product page', 'durotan' ),
					'default'     => 1,
					'priority'    => 40,
				),
				'product_related_title'              => array(
					'type'     => 'text',
					'label'    => esc_html__( 'Related Products Title', 'durotan' ),
					'section'  => 'single_product_related',
					'default'  => esc_html__( 'Related products', 'durotan' ),
					'priority' => 40,
				),
				'product_related_by_categories'      => array(
					'type'     => 'toggle',
					'label'    => esc_html__( 'Related Products By Categories', 'durotan' ),
					'section'  => 'single_product_related',
					'default'  => 1,
					'priority' => 40,
				),
				'product_related_by_parent_category' => array(
					'type'            => 'toggle',
					'label'           => esc_html__( 'Related Products By Parent Category', 'durotan' ),
					'section'         => 'single_product_related',
					'default'         => 0,
					'priority'        => 40,
					'active_callback' => array(
						array(
							'setting'  => 'product_related_by_categories',
							'operator' => '==',
							'value'    => 1,
						),
					),
				),
				'product_related_by_tags'            => array(
					'type'     => 'toggle',
					'label'    => esc_html__( 'Related Products By Tags', 'durotan' ),
					'section'  => 'single_product_related',
					'default'  => 1,
					'priority' => 40,
				),
				'product_related_numbers'            => array(
					'type'        => 'number',
					'label'       => esc_html__( 'Related Products Numbers', 'durotan' ),
					'section'     => 'single_product_related',
					'default'     => 6,
					'priority'    => 40,
					'description' => esc_html__( 'Specify how many numbers of related products you want to show on single product page', 'durotan' ),
				),

				'product_related_columns'            => array(
					'type'        => 'number',
					'label'       => esc_html__( 'Related Products Per Row', 'durotan' ),
					'section'     => 'single_product_related',
					'default'     => 5,
					'priority'    => 40,
					'description' => esc_html__( 'Specify the number of related products you want to display per row', 'durotan' ),
				),

				'product_sharing'                 => array(
					'type'    => 'toggle',
					'label'   => esc_html__( 'Product Sharing', 'durotan' ),
					'default' => true,
					'section' => 'single_product_sharing',
				),
				'product_sharing_socials'         => array(
					'type'            => 'multicheck',
					'description'     => esc_html__( 'Select social media for sharing products', 'durotan' ),
					'section'         => 'single_product_sharing',
					'default'         => array(
						'twitter',
						'facebook',
						'pinterest',
					),
					'choices'         => array(
						'twitter'    => esc_html__( 'Twitter', 'durotan' ),
						'facebook'   => esc_html__( 'Facebook', 'durotan' ),
						'googleplus' => esc_html__( 'Google Plus', 'durotan' ),
						'tumblr'     => esc_html__( 'Tumblr', 'durotan' ),
						'telegram'   => esc_html__( 'Telegram', 'durotan' ),
						'whatsapp'   => esc_html__( 'WhatsApp', 'durotan' ),
						'email'      => esc_html__( 'Email', 'durotan' ),
						'pinterest'  => esc_html__( 'Pinterest', 'durotan' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'product_sharing',
							'operator' => '==',
							'value'    => true,
						),
					),
				),
				'product_sharing_whatsapp_number' => array(
					'type'            => 'text',
					'description'     => esc_html__( 'WhatsApp Phone Number', 'durotan' ),
					'section'         => 'single_product_sharing',
					'active_callback' => array(
						array(
							'setting'  => 'product_sharing',
							'operator' => '==',
							'value'    => true,
						),
						array(
							'setting'  => 'product_sharing_socials',
							'operator' => 'contains',
							'value'    => 'whatsapp',
						),
					),
				),
			)
		);

		// Recently viewed
		$fields = array_merge(
			$fields, array(
				'recently_viewed_enable'          => array(
					'type'        => 'toggle',
					'label'       => esc_html__('Recently Viewed', 'durotan'),
					'section'     => 'recently_viewed',
					'default'     => 0,
					'description' => esc_html__('Check this option to show the recently viewed products above the footer.', 'durotan'),
				),
				'recently_viewed_bg_color' => array(
					'type'            => 'color',
					'label'           => esc_html__( 'Background Color', 'durotan' ),
					'transport'       => 'postMessage',
					'default'         => '',
					'js_vars'         => array(
						array(
							'element'  => '#durotan-recently-viewed-product',
							'property' => 'background-color',
						),
					),
					'section'         => 'recently_viewed',
				),
				'recently_viewed_ajax' => array(
					'type'    => 'toggle',
					'label'   => esc_html__('Load With Ajax', 'durotan'),
					'section' => 'recently_viewed',
					'default' => 0,
				),
				'recently_viewed_empty' => array(
					'type'            => 'toggle',
					'label'           => esc_html__('Hide Empty Products', 'durotan'),
					'section'         => 'recently_viewed',
					'default'         => 1,
					'description'     => esc_html__('Check this option to hide the recently viewed products when empty.', 'durotan'),
					'active_callback' => array(
						array(
							'setting'  => 'recently_viewed_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
				'recently_viewed_display_page' => array(
					'type'     => 'multicheck',
					'label'    => esc_html__('Display On Page', 'durotan'),
					'section'  => 'recently_viewed',
					'default'  => '',
					'choices'  => array(
						'single'   => esc_html__('Single Product', 'durotan'),
						'catalog'  => esc_html__('Catalog Page', 'durotan'),
					),
					'active_callback' => array(
						array(
							'setting'  => 'recently_viewed_enable',
							'operator' => '==',
							'value'    => 1,
						),
					),
				),

				'recently_viewed_custom' => array(
					'type'    => 'custom',
					'section' => 'recently_viewed',
					'default' => '<hr/>',
				),

				'recently_viewed_title' => array(
					'type'            => 'text',
					'label'           => esc_html__('Title', 'durotan'),
					'section'         => 'recently_viewed',
					'default'         => esc_html__('Recently Viewed', 'durotan'),
					'active_callback' => array(
						array(
							'setting'  => 'recently_viewed_enable',
							'operator' => '==',
							'value'    => 1,
						),
					),
				),

				'recently_viewed_columns' => array(
					'type'            => 'number',
					'label'           => esc_html__('Columns', 'durotan'),
					'section'         => 'recently_viewed',
					'default'         => 5,
					'active_callback' => array(
						array(
							'setting'  => 'recently_viewed_enable',
							'operator' => '==',
							'value'    => 1,
						),
					),
					'description' => esc_html__('Specify how many numbers of recently viewed products you want to show on page', 'durotan'),
				),

				'recently_viewed_numbers' => array(
					'type'            => 'number',
					'label'           => esc_html__('Numbers', 'durotan'),
					'section'         => 'recently_viewed',
					'default'         => 5,
					'active_callback' => array(
						array(
							'setting'  => 'recently_viewed_enable',
							'operator' => '==',
							'value'    => 1,
						),
					),
					'description' => esc_html__('Specify how many numbers of recently viewed products you want to show on page', 'durotan'),
				),
			)
		);

		return $fields;
	}
}
