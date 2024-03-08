<?php
/**
 * Hooks for importer
 *
 * @package Durotan
 */

namespace Durotan\Addons;


/**
 * Class Importter
 */
class Importer {

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
		add_filter( 'soo_demo_packages', array( $this, 'importer' ), 20 );
		add_action( 'soodi_before_import_content', array( $this,'import_product_attributes') );
	}

	/**
	 * Importer the demo content
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function importer() {
		return array(
			array(
				'name'       => 'Home Default',
				'preview'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-default/preview.png',
				'content'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-default/demo-content.xml',
				'customizer' => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-default/customizer.dat',
				'widgets'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-default/widgets.wie',
				'pages'      => array(
					'front_page' => 'Home Default',
					'blog'       => 'Blog',
					'shop'       => 'Shop',
					'cart'       => 'Cart',
					'checkout'   => 'Checkout',
					'my_account' => 'My Account',
				),
				'menus'      => array(
					'primary'   => 'primary-menu',
					'mobile'    => 'primary-menu',
				),
				'options'    => array(
					'shop_catalog_image_size'   => array(
						'width'  => 400,
						'height' => 400,
						'crop'   => 1,
					),
					'shop_single_image_size'    => array(
						'width'  => 600,
						'height' => 600,
						'crop'   => 1,
					),
					'shop_thumbnail_image_size' => array(
						'width'  => 70,
						'height' => 70,
						'crop'   => 1,
					),
				),
			),
			array(
				'name'       => 'Home Full Width',
				'preview'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-full-width/preview.png',
				'content'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-full-width/demo-content.xml',
				'customizer' => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-full-width/customizer.dat',
				'widgets'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-full-width/widgets.wie',
				'pages'      => array(
					'front_page' => 'Home FullWidth',
					'blog'       => 'Blog',
					'shop'       => 'Shop',
					'cart'       => 'Cart',
					'checkout'   => 'Checkout',
					'my_account' => 'My Account',
				),
				'menus'      => array(
					'primary'   => 'primary-menu',
					'hamburger' => 'hamburger-menu',
					'mobile'    => 'primary-menu',
				),
				'options'    => array(
					'shop_catalog_image_size'   => array(
						'width'  => 400,
						'height' => 400,
						'crop'   => 1,
					),
					'shop_single_image_size'    => array(
						'width'  => 600,
						'height' => 600,
						'crop'   => 1,
					),
					'shop_thumbnail_image_size' => array(
						'width'  => 70,
						'height' => 70,
						'crop'   => 1,
					),
				),
			),
			array(
				'name'       => 'Home Minimal',
				'preview'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-minimal/preview.png',
				'content'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-minimal/demo-content.xml',
				'customizer' => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-minimal/customizer.dat',
				'widgets'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-minimal/widgets.wie',
				'pages'      => array(
					'front_page' => 'Home Minimal',
					'blog'       => 'Blog',
					'shop'       => 'Shop',
					'cart'       => 'Cart',
					'checkout'   => 'Checkout',
					'my_account' => 'My Account',
				),
				'menus'      => array(
					'primary' => 'primary-menu',
					'hamburger' => 'hamburger-menu',
					'mobile' => 'primary-menu',
				),
				'options'    => array(
					'shop_catalog_image_size'   => array(
						'width'  => 400,
						'height' => 400,
						'crop'   => 1,
					),
					'shop_single_image_size'    => array(
						'width'  => 600,
						'height' => 600,
						'crop'   => 1,
					),
					'shop_thumbnail_image_size' => array(
						'width'  => 70,
						'height' => 70,
						'crop'   => 1,
					),
				),
			),
			array(
				'name'       => 'Home Dark Skin',
				'preview'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-dark-skin/preview.png',
				'content'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-dark-skin/demo-content.xml',
				'customizer' => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-dark-skin/customizer.dat',
				'widgets'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-dark-skin/widgets.wie',
				'pages'      => array(
					'front_page' => 'Home Dark Skin',
					'blog'       => 'Blog',
					'shop'       => 'Shop',
					'cart'       => 'Cart',
					'checkout'   => 'Checkout',
					'my_account' => 'My Account',
				),
				'menus'      => array(
					'primary'   => 'primary-menu',
					'hamburger' => 'hamburger-menu',
					'mobile'    => 'primary-menu',
				),
				'options'    => array(
					'shop_catalog_image_size'   => array(
						'width'  => 400,
						'height' => 400,
						'crop'   => 1,
					),
					'shop_single_image_size'    => array(
						'width'  => 600,
						'height' => 600,
						'crop'   => 1,
					),
					'shop_thumbnail_image_size' => array(
						'width'  => 70,
						'height' => 70,
						'crop'   => 1,
					),
				),
			),
			array(
				'name'       => 'Home Collections',
				'preview'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-collections/preview.png',
				'content'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-collections/demo-content.xml',
				'customizer' => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-collections/customizer.dat',
				'widgets'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-collections/widgets.wie',
				'pages'      => array(
					'front_page' => 'Home Collections',
					'blog'       => 'Blog',
					'shop'       => 'Shop',
					'cart'       => 'Cart',
					'checkout'   => 'Checkout',
					'my_account' => 'My Account',
				),
				'menus'      => array(
					'primary'   => 'primary-menu',
					'hamburger' => 'hamburger-menu',
					'mobile'    => 'primary-menu',
				),
				'options'    => array(
					'shop_catalog_image_size'   => array(
						'width'  => 400,
						'height' => 400,
						'crop'   => 1,
					),
					'shop_single_image_size'    => array(
						'width'  => 600,
						'height' => 600,
						'crop'   => 1,
					),
					'shop_thumbnail_image_size' => array(
						'width'  => 70,
						'height' => 70,
						'crop'   => 1,
					),
				),
			),
			array(
				'name'       => 'Home Sidebar',
				'preview'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-sidebar/preview.png',
				'content'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-sidebar/demo-content.xml',
				'customizer' => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-sidebar/customizer.dat',
				'widgets'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-sidebar/widgets.wie',
				'pages'      => array(
					'front_page' => 'Home Sidebar',
					'blog'       => 'Blog',
					'shop'       => 'Shop',
					'cart'       => 'Cart',
					'checkout'   => 'Checkout',
					'my_account' => 'My Account',
				),
				'menus'      => array(
					'primary'   => 'primary-menu-home-v8',
					'mobile'    => 'primary-menu',
					'secondary'  => 'secondary-menu-home-v8',
					'hamburger'  => 'hamburger-menu',
				),
				'options'    => array(
					'shop_catalog_image_size'   => array(
						'width'  => 400,
						'height' => 400,
						'crop'   => 1,
					),
					'shop_single_image_size'    => array(
						'width'  => 600,
						'height' => 600,
						'crop'   => 1,
					),
					'shop_thumbnail_image_size' => array(
						'width'  => 70,
						'height' => 70,
						'crop'   => 1,
					),
				),
			),
			array(
				'name'       => 'Home Best Selling',
				'preview'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-best-selling/preview.png',
				'content'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-best-selling/demo-content.xml',
				'customizer' => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-best-selling/customizer.dat',
				'widgets'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-best-selling/widgets.wie',
				'pages'      => array(
					'front_page' => 'Home Best Selling',
					'blog'       => 'Blog',
					'shop'       => 'Shop',
					'cart'       => 'Cart',
					'checkout'   => 'Checkout',
					'my_account' => 'My Account',
				),
				'menus'      => array(
					'primary'   => 'primary-menu',
					'hamburger' => 'hamburger-menu',
					'mobile'    => 'primary-menu',
				),
				'options'    => array(
					'shop_catalog_image_size'   => array(
						'width'  => 400,
						'height' => 400,
						'crop'   => 1,
					),
					'shop_single_image_size'    => array(
						'width'  => 600,
						'height' => 600,
						'crop'   => 1,
					),
					'shop_thumbnail_image_size' => array(
						'width'  => 70,
						'height' => 70,
						'crop'   => 1,
					),
				),
			),
			array(
				'name'       => 'Home Modern',
				'preview'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-modern/preview.png',
				'content'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-modern/demo-content.xml',
				'customizer' => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-modern/customizer.dat',
				'widgets'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-modern/widgets.wie',
				'pages'      => array(
					'front_page' => 'Home Modern',
					'blog'       => 'Blog',
					'shop'       => 'Shop',
					'cart'       => 'Cart',
					'checkout'   => 'Checkout',
					'my_account' => 'My Account',
				),
				'menus'      => array(
					'primary' => 'primary-menu',
					'hamburger' => 'hamburger-menu',
					'mobile' => 'primary-menu',
				),
				'options'    => array(
					'shop_catalog_image_size'   => array(
						'width'  => 400,
						'height' => 400,
						'crop'   => 1,
					),
					'shop_single_image_size'    => array(
						'width'  => 600,
						'height' => 600,
						'crop'   => 1,
					),
					'shop_thumbnail_image_size' => array(
						'width'  => 70,
						'height' => 70,
						'crop'   => 1,
					),
				),
			),
			array(
				'name'       => 'Home Classic',
				'preview'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-classic/preview.png',
				'content'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-classic/demo-content.xml',
				'customizer' => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-classic/customizer.dat',
				'widgets'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-classic/widgets.wie',
				'pages'      => array(
					'front_page' => 'Home Classic',
					'blog'       => 'Blog',
					'shop'       => 'Shop',
					'cart'       => 'Cart',
					'checkout'   => 'Checkout',
					'my_account' => 'My Account',
				),
				'menus'      => array(
					'primary' => 'primary-menu',
					'hamburger' => 'hamburger-menu',
					'mobile' => 'primary-menu',
				),
				'options'    => array(
					'shop_catalog_image_size'   => array(
						'width'  => 400,
						'height' => 400,
						'crop'   => 1,
					),
					'shop_single_image_size'    => array(
						'width'  => 600,
						'height' => 600,
						'crop'   => 1,
					),
					'shop_thumbnail_image_size' => array(
						'width'  => 70,
						'height' => 70,
						'crop'   => 1,
					),
				),
			),
			array(
				'name'       => 'Home Shoppable',
				'preview'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-shoppable/preview.png',
				'content'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-shoppable/demo-content.xml',
				'customizer' => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-shoppable/customizer.dat',
				'widgets'    => 'https://raw.githubusercontent.com/drfuri/demo-content/main/durotan/home-shoppable/widgets.wie',
				'pages'      => array(
					'front_page' => 'Home Shoppable',
					'blog'       => 'Blog',
					'shop'       => 'Shop',
					'cart'       => 'Cart',
					'checkout'   => 'Checkout',
					'my_account' => 'My Account',
				),
				'menus'      => array(
					'primary' => 'primary-menu',
					'hamburger' => 'hamburger-menu',
					'mobile' => 'primary-menu',
				),
				'options'    => array(
					'shop_catalog_image_size'   => array(
						'width'  => 400,
						'height' => 400,
						'crop'   => 1,
					),
					'shop_single_image_size'    => array(
						'width'  => 600,
						'height' => 600,
						'crop'   => 1,
					),
					'shop_thumbnail_image_size' => array(
						'width'  => 70,
						'height' => 70,
						'crop'   => 1,
					),
				),
			),
		);
	}

	/**
	 * Prepare product attributes before import demo content
	 *
	 * @param $file
	 */
	function import_product_attributes( $file ) {
		global $wpdb;

		if ( ! class_exists( 'WXR_Parser' ) ) {
			if ( ! file_exists( WP_PLUGIN_DIR . '/soo-demo-importer/includes/parsers.php' ) ) {
				return;
			}

			require_once WP_PLUGIN_DIR . '/soo-demo-importer/includes/parsers.php';
		}

		$parser      = new \WXR_Parser();
		$import_data = $parser->parse( $file );

		if ( empty( $import_data ) || is_wp_error( $import_data ) ) {
			return;
		}

		if ( isset( $import_data['posts'] ) ) {
			$posts = $import_data['posts'];

			if ( $posts && sizeof( $posts ) > 0 ) {
				foreach ( $posts as $post ) {
					if ( 'product' === $post['post_type'] ) {
						if ( ! empty( $post['terms'] ) ) {
							foreach ( $post['terms'] as $term ) {
								if ( strstr( $term['domain'], 'pa_' ) ) {
									if ( ! taxonomy_exists( $term['domain'] ) ) {
										$attribute_name = wc_sanitize_taxonomy_name( str_replace( 'pa_', '', $term['domain'] ) );

										// Create the taxonomy
										if ( ! in_array( $attribute_name, wc_get_attribute_taxonomies() ) ) {
											$attribute = array(
												'attribute_label'   => $attribute_name,
												'attribute_name'    => $attribute_name,
												'attribute_type'    => 'select',
												'attribute_orderby' => 'menu_order',
												'attribute_public'  => 0
											);
											$wpdb->insert( $wpdb->prefix . 'woocommerce_attribute_taxonomies', $attribute );
											delete_transient( 'wc_attribute_taxonomies' );
										}

										// Register the taxonomy now so that the import works!
										register_taxonomy(
											$term['domain'],
											apply_filters( 'woocommerce_taxonomy_objects_' . $term['domain'], array( 'product' ) ),
											apply_filters( 'woocommerce_taxonomy_args_' . $term['domain'], array(
												'hierarchical' => true,
												'show_ui'      => false,
												'query_var'    => true,
												'rewrite'      => false,
											) )
										);
									}
								}
							}
						}
					}
				}
			}
		}
	}

}
