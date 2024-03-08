<?php
/**
 * Elementor Global init
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Durotan
 */

namespace Durotan\Addons\Elementor;

use \Elementor\Controls_Manager;

/**
 * Integrate with Elementor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Page_Settings {

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
		if ( ! class_exists( '\Elementor\Core\DocumentTypes\PageBase' ) ) {
			return;
		}

		add_action( 'elementor/element/wp-page/document_settings/after_section_end', array( $this, 'add_new_page_settings_section' ) );

		add_action( 'elementor/document/after_save', array( $this, 'save_post_meta' ), 10, 2 );

		add_action( 'save_post', array( $this, 'save_elementor_settings' ), 10, 3 );

	}

	/**
	 * Add settings to Elementor page settings
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_new_page_settings_section( \Elementor\Core\DocumentTypes\PageBase $page ) {
		// Header
		$page->start_controls_section(
			'section_header_settings',
			[
				'label' => esc_html__( 'Header Settings', 'durotan' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$page->add_control(
			'durotan_header_background',
			[
				'label'       => esc_html__( 'Header Background', 'durotan' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'default'     => esc_html__( 'Default', 'durotan' ),
					'transparent' => esc_html__( 'Transparent', 'durotan' ),
				],
				'default'     => 'default',
				'label_block' => true,
			]
		);

		$page->add_control(
			'durotan_header_text_color',
			[
				'label'       => esc_html__( 'Text Color', 'durotan' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'default' => esc_html__( 'Default', 'durotan' ),
					'dark'    => esc_html__( 'Dark', 'durotan' ),
					'light'   => esc_html__( 'Light', 'durotan' ),
				),
				'default'     => 'default',
				'label_block' => true,
				'condition'   => [
					'durotan_header_background' => 'transparent',
				],
			]
		);

		$page->add_control(
			'durotan_hide_header_border',
			[
				'label'        => esc_html__( 'Hide Border Bottom', 'durotan' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => '1',
				'default'      => ''

			]
		);

		$page->add_control(
			'durotan_header_border_width',
			[
				'label'       => esc_html__( 'Header Border Bottom Width', 'durotan' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'standard' 		=> esc_html__( 'Standard', 'durotan' ),
					'full-width'    => esc_html__( 'Full Width', 'durotan' ),
				),
				'default'     => 'standard',
				'label_block' => true,
			]
		);

		$page->add_control(
			'durotan_header_border_color',
			[
				'label'     => esc_html__( 'Header Border Bottom Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'.site-header__border' => '--durotan-header-border-color: {{VALUE}};',
				],
			]
		);

		$page->end_controls_section();
		// Page Header
		$page->start_controls_section(
			'section_page_header_settings',
			[
				'label' => esc_html__( 'Page Header Settings', 'durotan' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);
		$page->add_control(
			'durotan_hide_page_header',
			[
				'label'        => esc_html__( 'Hide Page Header', 'durotan' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => '1',
				'default'      => ''

			]
		);
		$page->end_controls_section();

		// Content
		$page->start_controls_section(
			'section_content_settings',
			[
				'label' => esc_html__( 'Content Settings', 'durotan' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);
		$page->add_control(
			'durotan_content_width',
			[
				'label'       => esc_html__( 'Content Width', 'durotan' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'container'     			=> esc_html__( 'Standard', 'durotan' ),
					'durotan-container' 		=> esc_html__( 'Large', 'durotan' ),
					'durotan-container-narrow'	=> esc_html__( 'Fluid', 'durotan' ),
					'durotan-container-fluid' 	=> esc_html__( 'Full Width', 'durotan' ),
				],
				'default'     => 'container',
				'label_block' => true,
			]
		);
		$page->add_control(
			'durotan_content_top_spacing',
			[
				'label'       => esc_html__( 'Content Top Spacing', 'durotan' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'default'     => esc_html__( 'Default', 'durotan' ),
					'no' => esc_html__( 'No spacing', 'durotan' ),
					'custom' => esc_html__( 'Custom', 'durotan' ),
				],
				'default'     => 'default',
				'label_block' => true,
			]
		);
		$page->add_control(
			'durotan_content_top_padding',
			[
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 80,
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'durotan_content_top_spacing',
							'value' => 'custom',
						],
					],
				],
			]
		);
		$page->add_control(
			'durotan_content_bottom_spacing',
			[
				'label'       => esc_html__( 'Content Bottom Spacing', 'durotan' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'default'     => esc_html__( 'Default', 'durotan' ),
					'no' => esc_html__( 'No spacing', 'durotan' ),
					'custom' => esc_html__( 'Custom', 'durotan' ),
				],
				'default'     => 'default',
				'label_block' => true,
			]
		);
		$page->add_control(
			'durotan_content_bottom_padding',
			[
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 80,
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'durotan_content_bottom_spacing',
							'value' => 'custom',
						],
					],
				],
			]
		);
		$page->end_controls_section();

		// Footer
		$page->start_controls_section(
			'section_footer_settings',
			[
				'label' => esc_html__( 'Footer Settings', 'durotan' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);
		$page->add_control(
			'durotan_hide_footer',
			[
				'label'        => esc_html__( 'Hide Footer', 'durotan' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => '1',
				'default'      => ''

			]
		);
		$page->end_controls_section();
	}

	/**
	 * Save post meta when save page settings in Elementor
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function save_post_meta( $el, $data ) {
		if ( ! isset( $data['settings'] ) ) {
			return;
		}

		$settings = $data['settings'];
		$post_id  = $el->get_post()->ID;

		// Header
		$header_background = isset( $settings['durotan_header_background'] ) ? $settings['durotan_header_background'] : 'default';
		update_post_meta( $post_id, 'durotan_header_background', $header_background );

		$header_text_color = isset( $settings['durotan_header_text_color'] ) ? $settings['durotan_header_text_color'] : 'default';
		update_post_meta( $post_id, 'durotan_header_text_color', $header_text_color );

		$page_header = isset( $settings['durotan_hide_header_border'] ) ? $settings['durotan_hide_header_border'] : false;
		update_post_meta( $post_id, 'durotan_hide_header_border', $page_header );

		$header_border_width = isset( $settings['durotan_header_border_width'] ) ? $settings['durotan_header_border_width'] : false;
		update_post_meta( $post_id, 'durotan_header_border_width', $header_border_width );

		$header_border_color = isset( $settings['durotan_header_border_color'] ) ? $settings['durotan_header_border_color'] : false;
		update_post_meta( $post_id, 'durotan_header_border_color', $header_border_color );

		// Page Header
		$page_header_setting = isset( $settings['durotan_hide_page_header'] ) ? $settings['durotan_hide_page_header'] : false;
		update_post_meta( $post_id, 'durotan_hide_page_header', $page_header_setting );

		// Content
		$content_width = isset( $settings['durotan_content_width'] ) ? $settings['durotan_content_width'] : 'container';
		update_post_meta( $post_id, 'durotan_content_width', $content_width );

		$content_top_spacing = isset( $settings['durotan_content_top_spacing'] ) ? $settings['durotan_content_top_spacing'] : 'default';
		update_post_meta( $post_id, 'durotan_content_top_spacing', $content_top_spacing );

		$content_bottom_spacing = isset( $settings['durotan_content_bottom_spacing'] ) ? $settings['durotan_content_bottom_spacing'] : 'default';
		update_post_meta( $post_id, 'durotan_content_bottom_spacing', $content_bottom_spacing );

		if(isset( $settings['durotan_content_top_padding'],$settings['durotan_content_top_padding']['size'])){
			$content_top_padding = $settings['durotan_content_top_padding']['size'];
			update_post_meta( $post_id, 'durotan_content_top_padding', $content_top_padding );
		}
		if(isset( $settings['durotan_content_bottom_padding'],$settings['durotan_content_bottom_padding']['size']  )){
			$content_bottom_padding = $settings['durotan_content_bottom_padding']['size'];
			update_post_meta( $post_id, 'durotan_content_bottom_padding', $content_bottom_padding );
		}

		// Footer
		$durotan_hide_footer = isset( $settings['durotan_hide_footer'] ) ? $settings['durotan_hide_footer'] : false;
		update_post_meta( $post_id, 'durotan_hide_footer', $durotan_hide_footer );
	}

	/**
	 * Save Elementor page settings when save metabox
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function save_elementor_settings( $post_id, $post, $update ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['_elementor_edit_mode_nonce'] ) ) {
			return;
		}

		if ( ! is_admin() ) {
			return;
		}

		if ( $post->post_type !== 'page' ) {
			return;
		}

		// Check permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}


		if ( ! class_exists( '\Elementor\Core\Settings\Manager' ) ) {
			return;
		}

		if ( isset( $_POST['action'] ) && $_POST['action'] == 'elementor_ajax' ) {
			return;
		}

		$settings = array();

		// Header
		if ( isset( $_POST['durotan_header_background'] ) ) {
			$settings['durotan_header_background'] = $_POST['durotan_header_background'];
		}

		if ( isset( $_POST['durotan_header_text_color'] ) ) {
			$settings['durotan_header_text_color'] = $_POST['durotan_header_text_color'];
		}

		if ( isset( $_POST['durotan_hide_header_border'] ) ) {
			$settings['durotan_hide_header_border'] = $_POST['durotan_hide_header_border'];
		}

		if ( isset( $_POST['durotan_header_border_width'] ) ) {
			$settings['durotan_header_border_width'] = $_POST['durotan_header_border_width'];
		}

		if ( isset( $_POST['durotan_header_border_color'] ) ) {
			$settings['durotan_header_border_color'] = $_POST['durotan_header_border_color'];
		}

		// Page Header// Page Header
		if ( isset( $_POST['durotan_hide_page_header'] ) ) {
			$settings['durotan_hide_page_header'] = $_POST['durotan_hide_page_header'];
		}

		// Content
		if ( isset( $_POST['durotan_content_width'] ) ) {
			$settings['durotan_content_width'] = $_POST['durotan_content_width'];
		}
		if ( isset( $_POST['durotan_content_top_spacing'] ) ) {
			$settings['durotan_content_top_spacing'] = $_POST['durotan_content_top_spacing'];
		}
		if ( isset( $_POST['durotan_content_bottom_spacing'] ) ) {
			$settings['durotan_content_bottom_spacing'] = $_POST['durotan_content_bottom_spacing'];
		}
		if ( isset( $_POST['durotan_content_top_padding'] ) ) {
			$settings['durotan_content_top_padding'] = $_POST['durotan_content_top_padding'];
		}
		if ( isset( $_POST['durotan_content_bottom_padding'] ) ) {
			$settings['durotan_content_bottom_padding'] = $_POST['durotan_content_bottom_padding'];
		}

		// Footer
		if ( isset( $_POST['durotan_hide_footer'] ) ) {
			$settings['durotan_hide_footer'] = $_POST['durotan_hide_footer'];
		}

		$page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );
		$page_settings_manager->save_settings( $settings, $post_id );

	}
}
