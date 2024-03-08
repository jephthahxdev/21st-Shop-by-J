<?php

namespace Durotan\Addons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Products Shortcode widget
 */
class Product_Shortcode extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'durotan-product-shortcode';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Durotan - Product Shortcode', 'durotan' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-products';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'durotan' ];
	}

	public function get_script_depends() {
		$scripts = [
			'flexslider',
			'wc-single-product',
			'swiper',
			'imagesLoaded',
			'tawcvs-frontend',
			'durotan-product-shortcode'
		];

		if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			$scripts[] = 'zoom';
			$scripts[] = 'coundown';
		}
		return $scripts;
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$this->section_content();
		$this->section_style();
	}

	/**
	 * Section Content
	 */
	protected function section_content() {
        $this->section_content_settings();

	}
    protected function section_content_settings() {
        $this->start_controls_section(
			'section_product',
			[
                'label' => esc_html__( 'Content', 'durotan' ) ,
            ]
		);
		$this->add_control(
			'attribute_divider',
			[
				'label' => esc_html__( 'Heading', 'durotan'),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'title',
			[
				'label'       => __( 'Title', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter your title', 'durotan' ),
				'default'     => __( 'Add Your Heading Text Here', 'durotan' ),
                'label_block' => true,
			]
		);

        $this->add_control(
			'sub_title',
			[
				'label'       => __( 'Subtitle', 'durotan' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Enter your description', 'durotan' ),
				'default'     => __( 'Short Description', 'durotan' ),
                'label_block' => true,
			]
		);
		$this->add_control(
			'product_divider',
			[
				'label' => esc_html__( 'Product', 'durotan'),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'product_id',
			[
				'label'       => esc_html__( 'Product ID', 'durotan' ),
				'placeholder' => esc_html__( 'Click here and start typing...', 'durotan' ),
				'type'        => 'durotan_autocomplete',
				'default'     => '',
				'label_block' => true,
				'multiple'    => false,
				'source'      => 'product',
				'sortable'    => false,
			]
		);
        $this->add_control(
			'additional_divider',
			[
				'label' => esc_html__( 'Additional Options', 'durotan'),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
			'show_image_zoom',
			[
				'label'     => esc_html__( 'Image Zoom', 'durotan' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'durotan' ),
				'label_on'  => __( 'Show', 'durotan' ),
				'return_value' => 'show',
				'default'   => 'show',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'show_lightbox',
			[
				'label'     => esc_html__( 'Lightbox', 'durotan' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'durotan' ),
				'label_on'  => __( 'Show', 'durotan' ),
				'return_value' => 'show',
				'default'   => 'show',
				'frontend_available' => true,
			]
		);

        $this->add_control(
			'show_description',
			[
				'label'     => esc_html__( 'Description', 'durotan' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'durotan' ),
				'label_on'  => __( 'Show', 'durotan' ),
				'return_value' => 'show',
				'default'   => 'show',
			]
		);
        $this->add_control(
			'show_all_content',
			[
				'label'     => esc_html__( 'Show All Content', 'durotan' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'durotan' ),
				'label_on'  => __( 'Show', 'durotan' ),
				'return_value' => 'show',
                'condition' => [
					'show_description' => 'show',
				],
				'frontend_available' => true,
			]
		);
		$this->add_control(
			'excerpt_length',
			[
				'label'       => __( 'Excerpt Length', 'durotan' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 25,
				'min'         => 1,
				'step'        => 1,
				'condition' => [
					'show_description' => 'show',
					'show_all_content' => '',
				],
				'frontend_available' => true,
			]
		);
        $this->add_control(
			'show_social',
			[
				'label'     => esc_html__( 'Social', 'durotan' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'durotan' ),
				'label_on'  => __( 'Show', 'durotan' ),
				'return_value' => 'show',
				'default'   => 'show',
			]
		);
        $this->add_control(
			'show_thumbnail',
			[
				'label'     => esc_html__( 'Thumbnail Image', 'durotan' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'durotan' ),
				'label_on'  => __( 'Show', 'durotan' ),
				'return_value' => 'show',
				'default'   => '',
				'frontend_available' => true,
			]
		);
		$this->end_controls_section();
    }
	/**
	 * Section Style
	 */
	protected function section_style() {
		$this->section_content_style();
	}

	protected function section_content_style() {
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => __( 'Content', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_control(
			'title_style',
			[
				'label' => esc_html__( 'Title', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .durotan-product-shortcode .durotan-product-shortcode__title',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-product-shortcode .durotan-product-shortcode__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label'     => __( 'Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-product-shortcode .durotan-product-shortcode__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'subtitle_style',
			[
				'label' => esc_html__( 'SubTitle', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'subtitle_typography',
				'selector' => '{{WRAPPER}} .durotan-product-shortcode .durotan-product-shortcode__subtitle',
			]
		);

		$this->add_control(
			'subtitle_color',
			[
				'label'     => __( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-product-shortcode .durotan-product-shortcode__subtitle' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'subtitle_spacing',
			[
				'label'     => __( 'Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-product-shortcode .durotan-product-shortcode__subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'image_divider',
			[
				'label' => esc_html__( 'Image Wrapper', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'image_style_padding',
			[
				'label'      => __( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-product-shortcode .woocommerce-product-gallery__wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_control(
			'content_divider',
			[
				'label' => esc_html__( 'Content Wrapper', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'content_style_padding',
			[
				'label'      => __( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-product-shortcode .entry-summary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Render icon box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
        $settings = $this->get_settings_for_display();

		$classes = [
			'durotan-product-shortcode',
            'woocommerce',
            'single-product',
            'durotan-swiper-carousel-elementor',
		];

        $product_id = intval($settings['product_id']);

		if( empty($product_id) ) {
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		$title = '';
		if ( $settings['title'] ) {
			$title = sprintf( '<h2 class="durotan-product-shortcode__title">%s</h2>', esc_html( $settings['title'] ) );
		}
		if ( $settings['sub_title'] ) {
			$title .= sprintf( '<div class="durotan-product-shortcode__subtitle">%s</div>', esc_html( $settings['sub_title'] ) );
		}

		echo '<div '. $this->get_render_attribute_string( 'wrapper' ) .'>';
		echo '<div class="durotan-product-heading">'. $title .'</div>';
		echo '<div class="durotan-product-body">';
        $product = wc_get_product($product_id);

        if ( empty( $product ) ) {
			echo esc_html__( 'No products were found matching your selection.', 'durotan' );
		} else{
			add_filter( 'woocommerce_single_product_flexslider_enabled', '__return_true' );

			if ( $settings['show_lightbox'] === 'show' ) {
				wp_enqueue_script( 'photoswipe-ui-default' );
				wp_enqueue_style( 'photoswipe-default-skin' );
				add_action( 'wp_footer', 'woocommerce_photoswipe' );
			}

			if ( $settings['show_image_zoom'] === 'show' && wp_script_is( 'zoom', 'registered' ) ) {
				wp_enqueue_script( 'zoom' );
			}

			if ( empty($settings['show_description'] )) {
				add_filter( 'durotan_product_show_description', '__return_false' );
			}

			if ( empty($settings['show_all_content'] )) {
				add_filter( 'durotan_product_show_all_content', '__return_true' );
			}

			if ( empty($settings['show_social'] )) {
				add_filter( 'durotan_product_show_social', '__return_false' );
			}

			if ( empty($settings['show_thumbnail'] )) {
				add_filter( 'durotan_product_show_thumbnail', '__return_false' );
			}

			$original_post = $GLOBALS['post'];

			$GLOBALS['post'] = get_post( $product_id ); // WPCS: override ok.

			setup_postdata( $GLOBALS['post'] );

			wc_get_template_part( 'content', 'single-product-summary' );

			$GLOBALS['post'] = $original_post; // WPCS: override ok.

			wp_reset_postdata();
		}
        echo '</div>';
		echo '</div>';
	}
}