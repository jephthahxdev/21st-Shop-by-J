<?php

namespace Durotan\Addons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;
use Elementor\Controls_Stack;
use Durotan\Addons\Elementor\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Slider_Product widget
 */
class Slider_Product extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'durotan-slider-product';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Durotan - Slider Product', 'durotan' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-slider';
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
		return [
			'magnific',
			'pagepiling',
			'durotan-frontend',
		];
	}

	public function get_style_depends() {
		return [
			'magnific',
			'pagepiling'
		];
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

	// Tab Content
	protected function section_content() {
		$this->section_content_slides();
		$this->section_content_option();
	}

	protected function section_content_slides() {
		$this->start_controls_section(
			'section_slides',
			[
				'label' => esc_html__( 'Slides', 'durotan' ),
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->start_controls_tabs( 'slides_repeater' );

		$repeater->start_controls_tab( 'background', [ 'label' => esc_html__( 'Background', 'durotan' ) ] );

		$repeater->add_responsive_control(
			'banner_background_img',
			[
				'label'    => __( 'Background Image', 'durotan' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://via.placeholder.com/1920X600/cccccc?text=1920x600',
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-product {{CURRENT_ITEM}} .durotan-sliders__bg' => 'background-image: url("{{URL}}");',
				],
			]
		);

		$repeater->add_responsive_control(
			'background_size',
			[
				'label'     => esc_html__( 'Background Size', 'durotan' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => [
					'cover'   => esc_html__( 'Cover', 'durotan' ),
					'contain' => esc_html__( 'Contain', 'durotan' ),
					'auto'    => esc_html__( 'Auto', 'durotan' ),
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-product {{CURRENT_ITEM}} .durotan-sliders__bg' => 'background-size: {{VALUE}}',
				],
				'condition' => [
					'banner_background_img[url]!' => '',
				],
			]
		);

		$repeater->add_responsive_control(
			'background_position',
			[
				'label'     => esc_html__( 'Background Position', 'durotan' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''              => esc_html__( 'Default', 'durotan' ),
					'left top'      => esc_html__( 'Left Top', 'durotan' ),
					'left center'   => esc_html__( 'Left Center', 'durotan' ),
					'left bottom'   => esc_html__( 'Left Bottom', 'durotan' ),
					'right top'     => esc_html__( 'Right Top', 'durotan' ),
					'right center'  => esc_html__( 'Right Center', 'durotan' ),
					'right bottom'  => esc_html__( 'Right Bottom', 'durotan' ),
					'center top'    => esc_html__( 'Center Top', 'durotan' ),
					'center center' => esc_html__( 'Center Center', 'durotan' ),
					'center bottom' => esc_html__( 'Center Bottom', 'durotan' ),
					'initial' 		=> esc_html__( 'Custom', 'durotan' ),
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-product {{CURRENT_ITEM}} .durotan-sliders__bg' => 'background-position: {{VALUE}};',
				],
				'condition' => [
					'banner_background_img[url]!' => '',
				],

			]
		);

		$repeater->add_responsive_control(
			'background_position_xy',
			[
				'label'              => esc_html__( 'Custom Background Position', 'durotan' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'allowed_dimensions' => [ 'top', 'left' ],
				'size_units'         => [ 'px', '%' ],
				'default'            => [ ],
				'selectors'          => [
					'{{WRAPPER}} .durotan-slider-product {{CURRENT_ITEM}} .durotan-sliders__bg' => 'background-position: {{LEFT}}{{UNIT}} {{TOP}}{{UNIT}};',
				],
				'condition' => [
					'background_position' => [ 'initial' ],
					'banner_background_img[url]!' => '',
				],
				'required' => true,
			]
		);

		$repeater->add_control(
			'background_overlay',
			[
				'label'      => esc_html__( 'Background Overlay', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-product {{CURRENT_ITEM}} .durotan-sliders__bg::before' => 'background-color: {{VALUE}}',
				]
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'text_content', [ 'label' => esc_html__( 'Content', 'durotan' ) ] );

		$repeater->add_control(
			'product_id',
			[
				'label'       => esc_html__( 'Product', 'durotan' ),
				'placeholder' => esc_html__( 'Click here and start typing...', 'durotan' ),
				'type'        => 'durotan_autocomplete',
				'default'     => '',
				'label_block' => true,
				'multiple'    => false,
				'source'      => 'product',
				'sortable'    => true,
			]
		);

		$repeater->add_control(
			'title',
			[
				'label'       => esc_html__( 'Heading', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Slide Heading', 'durotan' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'description',
			[
				'label'   => esc_html__( 'Description', 'durotan' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'I am slide content. Click edit button to change this text. ', 'durotan' ),
			]
		);

		$repeater->add_control(
			'heading_button',
			[
				'label' => esc_html__( 'Button', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label'   => esc_html__( 'Text', 'durotan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Button', 'durotan' ),
			]
		);

		$repeater->add_control(
			'link',
			[
				'label'       => esc_html__( 'Link', 'durotan' ),
				'type'        => Controls_Manager::URL,
				'default'       => [
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'durotan' ),
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'slides',
			[
				'label'      => esc_html__( 'Slides', 'durotan' ),
				'type'       => Controls_Manager::REPEATER,
				'show_label' => true,
				'fields'     => $repeater->get_controls(),
				'default'    => array(),
			]
		);

		$this->add_responsive_control(
			'slides_height',
			[
				'label'      => esc_html__( 'Height', 'durotan' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 100,
						'max' => 5000,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-product .durotan-slider-product__wrapper' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .durotan-slider-product .durotan-sliders__bg' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.durotan-slider-carousel--parallax-yes' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'showscrollproductgroup',
			[
				'label'     => esc_html__( 'Show Scroll Product Group', 'durotan' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'durotan' ),
				'label_on'  => __( 'On', 'durotan' ),
				'return_value' => 'yes',
				'default'   => '',
				'frontend_available' => true,
				'prefix_class' => 'durotan-slider-product__show-scroll-product-group-',
			]
		);

		$this->add_control(
			'lazyload',
			[
				'label'     => esc_html__( 'Show Lazyload', 'durotan' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'durotan' ),
				'label_on'  => __( 'On', 'durotan' ),
				'return_value' => 'yes',
				'default'   => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'parallax',
			[
				'label'     => esc_html__( 'Parallax', 'durotan' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'durotan' ),
				'label_on'  => __( 'On', 'durotan' ),
				'return_value' => 'yes',
				'default'   => '',
				'frontend_available' => true,
				'prefix_class' => 'durotan-slider-carousel--parallax-',
			]
		);

		$this->add_control(
			'allowscrolling',
			[
				'label'     => esc_html__( 'Allow Scrolling', 'durotan' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'durotan' ),
				'label_on'  => __( 'On', 'durotan' ),
				'return_value' => 'true',
				'default'   => 'true',
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function section_content_option() {
		$this->start_controls_section(
			'section_slider_options',
			[
				'label' => esc_html__( 'Slider Options', 'durotan' ),
				'type'  => Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'direction',
			[
				'label'   => esc_html__( 'Direction', 'durotan' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'horizontal'   	 => esc_html__( 'Horizontal', 'durotan' ),
					'vertical' 	     => esc_html__( 'Vertical', 'durotan' ),
				],
				'default' => 'vertical',
				'toggle'  => false,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'effect',
			[
				'label'   => esc_html__( 'Effect', 'durotan' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'swing'   	 => esc_html__( 'Swing', 'durotan' ),
					'linear' 	 => esc_html__( 'Linear', 'durotan' ),
				],
				'default' => 'swing',
				'toggle'  => false,
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'navigation',
			[
				'label'     => esc_html__( 'Navigation', 'durotan' ),
				'type'      => Controls_Manager::SELECT,
				'options' => [
					''   				=> esc_html__( 'None', 'durotan' ),
					'dots' 	 			=> esc_html__( 'Dots', 'durotan' ),
					'fraction' 			=> esc_html__( 'Fraction', 'durotan' ),
					'dots_fraction' 	=> esc_html__( 'Dots & Fraction', 'durotan' ),
				],
				'default' => 'dots_fraction',
			]
		);


		$this->add_control(
			'autoplay_speed',
			[
				'label'     => esc_html__( 'Autoplay Speed', 'durotan' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 280,
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

	}

	// Tab Style
	protected function section_style() {
		$this->section_style_content();
		$this->section_style_product();
		$this->section_style_carousel();
	}

	protected function section_style_content() {
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'slides_margin',
			[
				'label'      => esc_html__( 'Margin', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'allowed_dimensions' => [ 'top', 'bottom' ],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-product .durotan-sliders__inner' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'slides_content_width',
			[
				'label' => __( 'Content Width', 'durotan' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''               			=> esc_attr__( 'Default', 'durotan' ),
					'container'               	=> esc_attr__( 'Standard', 'durotan' ),
					'durotan-container'       	=> esc_attr__( 'Large', 'durotan' ),
					'durotan-container-narrow'	=> esc_attr__( 'Fluid', 'durotan' ),
					'durotan-container-fluid' 	=> esc_attr__( 'Full Width', 'durotan' ),
					'durotan-container-custom' 	=> esc_attr__( 'Custom', 'durotan' ),
				],
			]
		);

		$this->add_responsive_control(
			'slides_content_width_custom',
			[
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 5000,
					],
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'slides_content_width',
							'value' => 'durotan-container-custom',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-product .durotan-container-custom' => 'width: 100%; max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'slides_vertical_position',
			[
				'label'        => esc_html__( 'Vertical Position', 'durotan' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'default'      => '',
				'options'      => [
					'top'    => [
						'title' => esc_html__( 'Top', 'durotan' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'durotan' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'durotan' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'prefix_class' => 'durotan--v-position-',
			]
		);

		$this->add_control(
			'slides_text_align',
			[
				'label'       => esc_html__( 'Text Align', 'durotan' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'left'   => [
						'title' => esc_html__( 'Left', 'durotan' ),
						'icon'  => 'eicon-text-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'durotan' ),
						'icon'  => 'eicon-text-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'durotan' ),
						'icon'  => 'eicon-text-right',
					],
				],
				'default'     => '',
				'selectors'   => [
					'{{WRAPPER}} .durotan-slider-product .durotan-sliders__inner' => 'text-align: {{VALUE}}',
				],
				'prefix_class' => 'durotan-sliders__inner--',
			]
		);

		$this->add_control(
			'content_bg',
			[
				'label'     => esc_html__( 'Background Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-product .durotan-slide__content' => 'background-color: {{VALUE}}',

				],
			]
		);

		$this->section_style_title();

		$this->section_style_desc();

		$this->section_style_button();

		$this->end_controls_section();

	}

	protected function section_style_title() {

		$this->add_control(
			'heading_title',
			[
				'label'     => esc_html__( 'Title', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .durotan-slider-product .durotan-slide__heading',
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-product .durotan-slide__heading' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_responsive_control(
			'heading_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-product .durotan-slide__heading' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

	}

	protected function section_style_desc() {
		// Description
		$this->add_control(
			'heading_description',
			[
				'label'     => esc_html__( 'Description', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .durotan-slider-product .durotan-slide__description',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-product .durotan-slide__description' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_responsive_control(
			'description_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-product .durotan-slide__description' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
	}

	protected function section_style_button() {

		$this->add_control(
			'heading_button',
			[
				'label'     => esc_html__( 'Button', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_typography',
				'selector' => '{{WRAPPER}} .durotan-slider-product .durotan-slide-button .button-product-text',
			]
		);

		$this->add_control(
			'btn_bg_color',
			[
				'label'      => esc_html__( 'Background Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-product .durotan-slide-button .button-product-text' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-product .durotan-slide-button .button-product-text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'     => 'btn_border',
				'selector' => '{{WRAPPER}} .durotan-slider-product .durotan-slide-button .button-product-text',
			]
		);

		$this->add_responsive_control(
			'btn_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-product .durotan-slide-button .button-product-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'btn_heading_hover',
			[
				'label' => esc_html__( 'Hover', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_color_hover',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-product .durotan-slide-button .button-product-text:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_bg_color_hover',
			[
				'label'      => esc_html__( 'Background Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-product .durotan-slide-button .button-product-text:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_border_color_hover',
			[
				'label'      => esc_html__( 'Border Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-product .durotan-slide-button .button-product-text:hover' => 'border-color: {{VALUE}}',
				],
			]
		);
	}

	protected function section_style_product() {
		$this->start_controls_section(
			'section_style_product',
			[
				'label' => esc_html__( 'Product', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'product_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-product .durotan-slide__product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function section_style_carousel() {

		$this->start_controls_section(
			'section_style_arrows',
			[
				'label' => esc_html__( 'Slider Options', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Dots
		$this->add_control(
			'dots_style_heading',
			[
				'label' => esc_html__( 'Dots', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'sliders_dots_size',
			[
				'label'     => __( 'Size', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 150,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-product #pp-nav li .durotan-slide__pagination-bullet' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sliders_dots_size_dot',
			[
				'label'     => __( 'Dots Size', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 150,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-product #pp-nav li .durotan-slide__pagination-bullet:before' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sliders_dots_horizontal_spacing',
			[
				'label'     => esc_html__( 'Horizontal Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 500,
						'min' => -500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-product #pp-nav li' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'sliders_dots_vertical_spacing',
			[
				'label'     => esc_html__( 'Vertical Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 500,
						'min' => -500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-product #pp-nav li' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'sliders_dots_bottom_spacing',
			[
				'label'     => esc_html__( 'Bottom Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 500,
						'min' => -500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-product #pp-nav' => 'bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);


		$this->add_control(
			'sliders_dots_bgcolor',
			[
				'label'     => esc_html__( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-product #pp-nav li .durotan-slide__pagination-bullet:before' => 'background-color: {{VALUE}};',
				],
			]
		);


		$this->add_control(
			'sliders_dots_ac_bgcolor',
			[
				'label'     => esc_html__( 'Color Hover', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-product #pp-nav li .durotan-slide__pagination-bullet.active:before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .durotan-slider-product #pp-nav li .durotan-slide__pagination-bullet.active' 		=> 'border-color: {{VALUE}};',
					'{{WRAPPER}} .durotan-slider-product #pp-nav li .durotan-slide__pagination-bullet:hover:before'  => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .durotan-slider-product #pp-nav li .durotan-slide__pagination-bullet:hover' 		=> 'border-color: {{VALUE}};',
				],
			]
		);

		// Fraction
		$this->add_control(
			'fraction_style_heading',
			[
				'label' => esc_html__( 'Fraction', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'style_fraction' );

		$this->start_controls_tab(
			'style_fraction_text',
			[
				'label' => esc_html__( 'Text', 'durotan' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'fraction_text_1',
				'selector' => '{{WRAPPER}} .durotan-slide__fraction--style-2 .durotan-slide__fraction-current',
			]
		);

		$this->add_control(
			'fraction_text_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slide__fraction--style-2 .durotan-slide__fraction-current' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_fraction_line',
			[
				'label' => esc_html__( 'Line', 'durotan' ),
			]
		);

		$this->add_control(
			'fraction_line_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slide__fraction--style-2 .durotan-slide__fraction-line' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_fraction_total',
			[
				'label' => esc_html__( 'Total', 'durotan' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'fraction_total',
				'selector' => '{{WRAPPER}} .durotan-slide__fraction--style-2 .durotan-slide__fraction-total',
			]
		);

		$this->add_control(
			'fraction_total_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slide__fraction--style-2 .durotan-slide__fraction-total' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'fraction_line_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slide__fraction--style-2 .durotan-slide__fraction-line' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
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

		if ( empty( $settings['slides'] ) ) {
			return;
		}

		$nav        = $settings['navigation'] ? $settings['navigation'] : 'none';
		$nav_tablet = empty( $settings['navigation_tablet'] ) ? $nav : $settings['navigation_tablet'];
		$nav_mobile = empty( $settings['navigation_mobile'] ) ? $nav : $settings['navigation_mobile'];

		$prallax = '';

		if ( $settings['parallax'] ) {
			$prallax = 'durotan-slider--parallax';
		}

		$classes = [
			'durotan-slider-product',
			'durotan-slider-product-elementor',
			'navigation-' . $nav,
			'navigation-tablet-' . $nav_tablet,
			'navigation-mobile-' . $nav_mobile,
			$prallax,
		];

		$slides_content_width = $settings['slides_content_width'] == '' ? 'durotan-container-fluid' : $settings['slides_content_width'];

		$slide_count = 0;
		$slides_dots = [];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		echo '<div '.$this->get_render_attribute_string( 'wrapper' ).'>';

			echo '<div class="durotan-slider-items durotan-slider-product__wrapper">';

			foreach ( $settings['slides'] as $slide ) {
				echo '<div class="elementor-repeater-item-' . $slide['_id'] . ' durotan-slider-item slider-product-slide section pp-scrollable">';

					$data_lazy_url = $data_lazy_class = $data_lazy_loading = '';

					if ($settings['lazyload'] ) {

						$data_lazy_url = 'data-background="'.$slide['banner_background_img']['url'].'"';
						$data_lazy_loading =  '	<div class="slider-product-lazy-preloader"></div>';
						$data_lazy_class = 'slider-product-lazy';

					}

					echo '<div '. $data_lazy_url .' class="durotan-sliders__bg '.$data_lazy_class.'">'. $data_lazy_loading .'</div>';

					echo '<div class="durotan-sliders__inner ' . $slides_content_width . '">';

						echo '<div class="durotan-slide__content">';

						if ( $slide['title'] ) {
							echo '<div class="durotan-slide__heading">' . $slide['title'] . '</div>';
						}

						if ( $slide['description'] ) {
							echo '<div class="durotan-slide__description">' . $slide['description'] . '</div>';
						}

						echo '</div>';

						echo '<div class="durotan-slide__content--product">';


						// Product
						$product_id = intval($slide['product_id']);
						$product = wc_get_product($product_id);

						if( $product ) {

							echo '<div class="durotan-slide__product">';

							$original_post = $GLOBALS['post'];

							$GLOBALS['post'] = get_post( $product_id ); // WPCS: override ok.
							setup_postdata( $GLOBALS['post'] );
							wc_get_template_part( 'content', 'product-showcase' );

							$GLOBALS['post'] = $original_post; // WPCS: override ok.

							wp_reset_postdata();

							echo '</div>';
						}

						echo '</div>';

						// Button
						$button_text = $slide['button_text'] ? sprintf('<span class="button-product-text">%s</span>', $slide['button_text'] ) : '';

						$button_text = $slide['link']['url'] ? Helper::control_url( 'button', $slide['link'], $slide['button_text'], ['class' => 'button-product-text button-link'] ) : $button_text;

						if ( $slide['button_text'] ) {
							echo '<div class="button-product-text-container '.$slides_content_width.'">'.$button_text.'</div>';
						}

					echo '</div>';

				echo '</div>';

				$slides_dots[] = '<li data-tooltip=""><a class="durotan-slide__pagination-bullet"></a></li>';

				$slide_count ++;
			}

			if ( $slide_count > 1 ) {
				if ( $settings['navigation'] ) {

					if ( $settings['navigation'] === 'dots' || $settings['navigation'] === 'dots_fraction' ) {
						echo '<div id="pp-nav" class="durotan-slide__pagination ' . $slides_content_width . '"><ul>' . implode( '', $slides_dots ) . '</ul></div>';
					}

					if ( $settings['navigation'] === 'fraction' || $settings['navigation'] === 'dots_fraction' ) {
						echo '<div class="durotan-slide__fraction '.$slides_content_width.' durotan-slide__fraction--style-2"><span class="durotan-slide__fraction-current">1</span><span class="durotan-slide__fraction-line"></span><span class="durotan-slide__fraction-total"></span></div>';
					}
				}
			}

			echo '</div>';
		echo '</div>';
	}
}