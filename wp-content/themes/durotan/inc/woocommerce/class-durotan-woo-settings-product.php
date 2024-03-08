<?php

/**
 * WooCommerce Product additional settings.
 *
 * @package Durotan
 */

namespace Durotan\WooCommerce\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of Product Settings
 */
class Product {
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
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'woocommerce_product_options_advanced', array( $this, 'product_advanced_options' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'product_meta_fields_save' ) );

		// Product meta box.
		add_filter( 'rwmb_meta_boxes', array( $this, 'get_product_meta_boxes' ) );
	}

	/**
	 * Enqueue Scripts
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_scripts( $hook ) {
		$screen = get_current_screen();
		if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) && $screen->post_type == 'product' ) {
			wp_register_script( 'durotan_wc_settings_js', get_template_directory_uri() . '/assets/js/backend/woocommerce.js', array(
				'jquery',
				'wp-color-picker'
			), '20210708', true );
			wp_enqueue_script( 'durotan_wc_settings_js' );
			wp_enqueue_style( 'durotan_wc_settings_style', get_template_directory_uri() . "/assets/css/woocommerce-settings.css", array(), '20190717' );
		}
	}

	/**
	 * Add more options to advanced tab.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_advanced_options( $post_id ) {
		$post_custom = get_post_custom( $post_id );

		woocommerce_wp_text_input(
			array(
				'id'       => 'custom_badges_text',
				'label'    => esc_html__( 'Custom Badge Text', 'durotan' ),
				'desc_tip' => esc_html__( 'Enter this optional to show your badges.', 'durotan' ),
			)
		);

		$bg_color = ( isset( $post_custom['custom_badges_bg'][0] ) ) ? $post_custom['custom_badges_bg'][0] : '';
		woocommerce_wp_text_input(
			array(
				'id'       => 'custom_badges_bg',
				'label'    => esc_html__( 'Custom Badge Background', 'durotan' ),
				'desc_tip' => esc_html__( 'Pick background color for your badge', 'durotan' ),
				'value'    => $bg_color,
			)
		);

		$color = ( isset( $post_custom['custom_badges_color'][0] ) ) ? $post_custom['custom_badges_color'][0] : '';
		woocommerce_wp_text_input(
			array(
				'id'       => 'custom_badges_color',
				'label'    => esc_html__( 'Custom Badge Color', 'durotan' ),
				'desc_tip' => esc_html__( 'Pick color for your badge', 'durotan' ),
				'value'    => $color,
			)
		);

		woocommerce_wp_checkbox( array(
			'id'          => '_is_new',
			'label'       => esc_html__( 'New product?', 'durotan' ),
			'description' => esc_html__( 'Enable to set this product as a new product. A "New" badge will be added to this product.', 'durotan' ),
		) );
	}

	/**
	 * product_meta_fields_save function.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $post_id
	 *
	 * @return void
	 */
	public function product_meta_fields_save( $post_id ) {
		if ( isset( $_POST['custom_badges_text'] ) ) {
			$woo_data = $_POST['custom_badges_text'];
			update_post_meta( $post_id, 'custom_badges_text', $woo_data );
		}

		if ( isset( $_POST['custom_badges_bg'] ) ) {
			$woo_data = $_POST['custom_badges_bg'];
			update_post_meta( $post_id, 'custom_badges_bg', $woo_data );
		}

		if ( isset( $_POST['custom_badges_color'] ) ) {
			$woo_data = $_POST['custom_badges_color'];
			update_post_meta( $post_id, 'custom_badges_color', $woo_data );
		}

		if ( isset( $_POST['_is_new'] ) ) {
			$woo_data = $_POST['_is_new'];
			update_post_meta( $post_id, '_is_new', $woo_data );
		} else {
			update_post_meta( $post_id, '_is_new', 0 );
		}
	}

	/**
	 * Register meta boxes for product.
	 *
	 * @since 1.0.0
	 *
	 * @param array $meta_boxes The Meta Box plugin configuration variable for meta boxes.
	 *
	 * @return array
	 */
	public function get_product_meta_boxes( $meta_boxes ) {
		$meta_boxes[] = array(
			'id'       => 'product-videos',
			'title'    => esc_html__( 'Product Video', 'durotan' ),
			'pages'    => array( 'product' ),
			'context'  => 'side',
			'priority' => 'low',
			'fields'   => array(
				array(
					'name' => esc_html__( 'Video URL', 'durotan' ),
					'id'   => 'video_url',
					'type' => 'oembed',
					'std'  => false,
					'desc' => esc_html__( 'Enter URL of Youtube or Vimeo or specific filetypes such as mp4, webm, ogv.', 'durotan' ),
				),
				array(
					'name'             => esc_html__( 'Video Thumbnail', 'durotan' ),
					'id'               => 'video_thumbnail',
					'type'             => 'image_advanced',
					'max_file_uploads' => 1,
					'std'              => false,
					'desc'             => esc_html__( 'Add video thumbnail', 'durotan' ),
				),
				array(
					'name' => esc_html__( 'Video Position', 'durotan' ),
					'id'   => 'video_position',
					'type' => 'number',
					'std'  => '1',
				),
			),
		);

		//if ( in_array( \Durotan\Helper::get_option('product_layout'), array('v3','v5'))) {
			$meta_boxes[] = array(
				'id'       => 'display-settings',
				'title'    => esc_html__( 'Display Settings', 'durotan' ),
				'pages'    => array( 'product' ),
				'context'  => 'normal',
				'priority' => 'low',
				'fields'   => array(
					array(
						'name' => esc_html__( 'Background Color', 'durotan' ),
						'desc' => esc_html__( 'Pick a background color for product page. Or leave it empty to automatically detect the background from product main image.', 'durotan' ),
						'id'   => 'background_color',
						'type' => 'color',
					),
				),
			);
		//}
		return $meta_boxes;
	}
}
