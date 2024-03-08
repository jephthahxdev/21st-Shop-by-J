<?php

namespace Durotan\Addons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Stack;
use Durotan\Addons\Elementor\Helper;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Banner_Shoppable widget
 */
class Banner_Shoppable extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'durotan-banner-shoppable';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Durotan - Banner Shoppable', 'durotan' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-banner';
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
			'durotan-frontend'
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
		$this->section_content_option();
		$this->section_content_hotspot();
	}

	protected function section_content_option() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'durotan' ),
			]
		);

		$this->add_responsive_control(
			'banner_background_img',
			[
				'label'    => __( 'Background Image', 'durotan' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://via.placeholder.com/1920X900/cccccc?text=1920x900',
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-banner-shoppable' => 'background-image: url("{{URL}}");',
				],
			]
		);

		$this->add_responsive_control(
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
					'{{WRAPPER}} .durotan-banner-shoppable' => 'background-size: {{VALUE}}',
				],
				'condition' => [
					'banner_background_img[url]!' => '',
				],
			]
		);

		$this->add_responsive_control(
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
					'{{WRAPPER}} .durotan-banner-shoppable' => 'background-position: {{VALUE}};',
				],
				'condition' => [
					'banner_background_img[url]!' => '',
				],

			]
		);

		$this->add_responsive_control(
			'background_position_xy',
			[
				'label'              => esc_html__( 'Custom Background Position', 'durotan' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'allowed_dimensions' => [ 'top', 'left' ],
				'size_units'         => [ 'px', '%' ],
				'default'            => [ ],
				'selectors'          => [
					'{{WRAPPER}} .durotan-banner-shoppable' => 'background-position: {{LEFT}}{{UNIT}} {{TOP}}{{UNIT}};',
				],
				'condition' => [
					'background_position' => [ 'initial' ],
					'banner_background_img[url]!' => '',
				],
				'required' => true,
				'tablet_default' => [
					'background_position_tablet' => [ 'initial' ],
				],
				'mobile_default' => [
					'background_position_mobile' => [ 'initial' ],
				],
			]
		);

		$this->add_control(
			'before_title',
			[
				'label'       => esc_html__( 'Before Title', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Before Heading', 'durotan' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Heading', 'durotan' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'description',
			[
				'label'   => esc_html__( 'Description', 'durotan' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'I am content. Click edit button to change this text. ', 'durotan' ),
			]
		);

		$this->add_control(
			'heading_button',
			[
				'label' => esc_html__( 'Button', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'   => esc_html__( 'Text', 'durotan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Button', 'durotan' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label'       => esc_html__( 'Link', 'durotan' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'durotan' ),
			]
		);

		$this->end_controls_section();
	}

	protected function section_content_hotspot() {
		$this->start_controls_section(
			'section_hotspot',
			[
				'label' => esc_html__( 'Hotspot', 'durotan' ),
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				'default'   => 'full',
				'separator' => 'before',
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_responsive_control(
			'hotspot_image',
			[
				'label'    => __( 'Image', 'durotan' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://via.placeholder.com/170X208/cccccc?text=170X208',
				],
			]
		);

		$repeater->add_control(
			'hotspot_title',
			[
				'label'       => esc_html__( 'Title', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Heading hotspot', 'durotan' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'hotspot_price',
			[
				'label'   => esc_html__( 'Price', 'durotan' ),
				'type'    => Controls_Manager::TEXT
			]
		);

		$repeater->add_control(
			'hotspot_price_sale',
			[
				'label'   => esc_html__( 'Price Sale', 'durotan' ),
				'type'    => Controls_Manager::TEXT
			]
		);

		$repeater->add_control(
			'hotspot_button',
			[
				'label'     => esc_html__( 'Button', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$repeater->add_control(
			'hotspot_button_text',
			[
				'label'   => esc_html__( 'Text', 'durotan' ),
				'type'    => Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'hotspot_button_link',
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

		$repeater->add_control(
			'hotspot_point',
			[
				'label'     => esc_html__( 'Point', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$repeater->add_control(
			'hotspot_position_point',
			[
				'label'        => __( 'Position', 'durotan' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'durotan' ),
				'label_on'     => __( 'Custom', 'durotan' ),
				'return_value' => 'yes',
			]
		);

		$repeater->start_popover();

		$repeater->add_responsive_control(
			'hotspot_position_x',
			[
				'label'     => esc_html__( 'Position X', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}%',
				],
				'condition' => [
					'hotspot_position_point' => 'yes',
				],
			]
		);

		$repeater->add_responsive_control(
			'hotspot_position_y',
			[
				'label'     => esc_html__( 'Position Y', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}%',
				],
				'condition' => [
					'hotspot_position_point' => 'yes',
				],
			]
		);

		$repeater->end_popover();

		$repeater->add_responsive_control(
			'hotspot_position_content',
			[
				'label'     => esc_html__( 'Position Content', 'durotan' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'top'      	=> esc_html__( 'Top', 'durotan' ),
					'right'     => esc_html__( 'Right', 'durotan' ),
					'bottom' 	=> esc_html__( 'Bottom', 'durotan' ),
					'left' 		=> esc_html__( 'Left', 'durotan' ),
					'custom' 	=> esc_html__( 'Custom', 'durotan' ),
				],
				'default'   => 'top',
				'selectors'            => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .durotan-hotspot__item' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'top'      	=> 'left: -417px; top: -253px;',
					'right'     => 'left: 78px; top: -22px;',
					'bottom' 	=> 'left: -417px; top: 75px;',
					'left' 		=> 'left: -515px; top: -22px;',
				],
			]
		);

		$repeater->add_control(
			'hotspot_position_content_custom',
			[
				'label'        => __( 'Position Content Custom', 'durotan' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'durotan' ),
				'label_on'     => __( 'Custom', 'durotan' ),
				'return_value' => 'yes',
				'condition' => [
					'hotspot_position_content' => [ 'custom' ],
				],
			]
		);

		$repeater->start_popover();

		$repeater->add_responsive_control(
			'hotspot_position_item_x',
			[
				'label'     => esc_html__( 'Position X', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => -2000,
						'max' => 2000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .durotan-hotspot__item' => 'left: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'hotspot_position_content_custom' => 'yes',
				],
			]
		);

		$repeater->add_responsive_control(
			'hotspot_position_item_y',
			[
				'label'     => esc_html__( 'Position Y', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => -2000,
						'max' => 2000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .durotan-hotspot__item' => 'top: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'hotspot_position_content_custom' => 'yes',
				],
			]
		);

		$repeater->end_popover();

		$repeater->add_responsive_control(
			'hotspot_position_arrow',
			[
				'label'     => esc_html__( 'Position Arrow By Point', 'durotan' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'top'      	=> esc_html__( 'Top', 'durotan' ),
					'right'     => esc_html__( 'Right', 'durotan' ),
					'bottom' 	=> esc_html__( 'Bottom', 'durotan' ),
					'left' 		=> esc_html__( 'Left', 'durotan' ),
				],
				'default'   => 'top',
				'selectors'            => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .durotan-hotspot__item::after' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'top'      	=> 'bottom: -19px; right: 29px; top: auto; left: auto; border-style: solid; border-width: 19px 8.5px 0 8.5px; border-color: #ffffff transparent transparent transparent;',
					'left'     => 'top: 26px; left: -19px; bottom: auto; right: auto; border-style: solid; border-width: 8.5px 19px 8.5px 0; border-color: transparent #ffffff transparent transparent;',
					'bottom' 	=> 'top: -19px; right: 29px; bottom: auto; left: auto; border-style: solid; border-width: 0 8.5px 19px 8.5px; border-color: transparent transparent #ffffff transparent;',
					'right' 		=> 'top: 26px; right: -19px; bottom: auto; left: auto; border-style: solid; border-width: 8.5px 0 8.5px 19px; border-color: transparent transparent transparent #ffffff;',
				],
			]
		);

		$repeater->add_control(
			'hotspot_position_arrow_custom',
			[
				'label'        => __( 'Position Arrow Custom', 'durotan' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'durotan' ),
				'label_on'     => __( 'Custom', 'durotan' ),
				'return_value' => 'yes',
			]
		);

		$repeater->start_popover();

		$repeater->add_responsive_control(
			'hotspot_position_arrow_x',
			[
				'label'     => esc_html__( 'Position X', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => -500,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .durotan-hotspot__item::after' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
				],
				'condition' => [
					'hotspot_position_arrow_custom' => 'yes',
				],
			]
		);

		$repeater->add_responsive_control(
			'hotspot_position_arrow_y',
			[
				'label'     => esc_html__( 'Position Y', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => -2000,
						'max' => 2000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .durotan-hotspot__item::after' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
				],
				'condition' => [
					'hotspot_position_arrow_custom' => 'yes',
				],
			]
		);

		$repeater->end_popover();

		$this->add_control(
			'hotspot',
			[
				'type'       => Controls_Manager::REPEATER,
				'show_label' => true,
				'fields'     => $repeater->get_controls(),
				'default'    => array(),
			]
		);

		$this->end_controls_section();
	}

	// Tab Style
	protected function section_style() {
		$this->section_style_content();
		$this->section_style_hotspot();
	}

	protected function section_style_content() {
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_inner_width',
			[
				'label' => __( 'Inner Width', 'durotan' ),
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
			'content_margin',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-banner-shoppable__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_width',
			[
				'label' => __( 'Content Width', 'durotan' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''          => esc_attr__( 'Default', 'durotan' ),
					'custom' 	=> esc_attr__( 'Custom', 'durotan' ),
				],
			]
		);

		$this->add_responsive_control(
			'content_width_custom',
			[
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 5000,
					],
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'content_width',
							'value' => 'custom',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-banner-shoppable__content' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control (
			'content_horizontal_position',
			[
				'label'        => esc_html__( 'Horizontal Position', 'durotan' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'default'      => '',
				'options'      => [
					'flex-start'   => [
						'title' => esc_html__( 'Left', 'durotan' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'durotan' ),
						'icon'  => 'eicon-h-align-center',
					],
					'flex-end'  => [
						'title' => esc_html__( 'Right', 'durotan' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-banner-shoppable__inner' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_vertical_position',
			[
				'label'        => esc_html__( 'Vertical Position', 'durotan' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'default'      => '',
				'options'      => [
					'flex-start'    => [
						'title' => esc_html__( 'Top', 'durotan' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'durotan' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'durotan' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-banner-shoppable__inner' => 'align-items: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_text_align',
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
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-banner-shoppable__content' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_bg',
			[
				'label'     => esc_html__( 'Background Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-banner-shoppable__content' => 'background-color: {{VALUE}}',

				],
			]
		);

		$this->section_style_before_title();

		$this->section_style_title();

		$this->section_style_desc();

		$this->section_style_button();

		$this->end_controls_section();
	}

	protected function section_style_before_title() {

		$this->add_control(
			'before_heading_title',
			[
				'label'     => esc_html__( 'Before Title', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'before_heading_typography',
				'selector' => '{{WRAPPER}} .durotan-banner-shoppable .durotan-banner-shoppable__before-heading',
			]
		);

		$this->add_control(
			'before_heading_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-banner-shoppable__before-heading' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_responsive_control(
			'before_heading_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 350,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-banner-shoppable__before-heading' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
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
				'selector' => '{{WRAPPER}} .durotan-banner-shoppable .durotan-banner-shoppable__heading',
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-banner-shoppable__heading' => 'color: {{VALUE}}',

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
						'max' => 350,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-banner-shoppable__heading' => 'margin-bottom: {{SIZE}}{{UNIT}}',
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
				'selector' => '{{WRAPPER}} .durotan-banner-shoppable .durotan-banner-shoppable__description',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-banner-shoppable__description' => 'color: {{VALUE}}',

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
						'max' => 350,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-banner-shoppable__description' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
	}

	protected function section_style_button() {
		$this->add_control(
			'heading_button_style',
			[
				'label'     => esc_html__( 'Button', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .durotan-banner-shoppable .button-banner-shoppable',
			]
		);

		$this->add_control(
			'button_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner-shoppable .button-banner-shoppable' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label'      => esc_html__( 'Border Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner-shoppable .button-banner-shoppable::after' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_heading_hover',
			[
				'label' => esc_html__( 'Button Hover', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_color_hover',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner-shoppable .button-banner-shoppable:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_color_hover',
			[
				'label'      => esc_html__( 'Border Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner-shoppable .button-banner-shoppable:hover::after' => 'border-color: {{VALUE}}',
				],
			]
		);
	}

	protected function section_style_hotspot() {
		$this->start_controls_section(
			'section_style_hotspot',
			[
				'label' => esc_html__( 'Hotspot', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->section_style_hotspot_title();

		$this->section_style_hotspot_price();

		$this->section_style_hotspot_button();

		$this->section_style_hotspot_button_close();

		$this->section_style_hotspot_point();

		$this->end_controls_section();
	}

	protected function section_style_hotspot_title() {
		$this->add_control(
			'heading_hotspot_title',
			[
				'label'     => esc_html__( 'Title', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_hotspot_typography',
				'selector' => '{{WRAPPER}} .durotan-banner-shoppable .durotan-hotspot__heading',
			]
		);

		$this->add_control(
			'heading_hotspot_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-hotspot__heading' => 'color: {{VALUE}}',
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-hotspot__heading:hover' => 'box-shadow: inset 0 -0.175em white, inset 0 -0.2em {{VALUE}}',

				],
			]
		);
	}

	protected function section_style_hotspot_price() {

		$this->add_control(
			'heading_hotspot_price',
			[
				'label'     => esc_html__( 'Price', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'all_price_spacing_top',
			[
				'label'     => esc_html__( 'Spacing Top', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 350,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-hotspot__price' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .durotan-banner-shoppable ins.durotan-hotspot__price-number',
			]
		);

		$this->add_control(
			'price_color',
			[
				'label'     => esc_html__( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-banner-shoppable ins.durotan-hotspot__price-number' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_responsive_control(
			'price_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 350,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-banner-shoppable ins.durotan-hotspot__price-number' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_sale',
			[
				'label'     => esc_html__( 'Sale', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sale_typography',
				'selector' => '{{WRAPPER}} .durotan-banner-shoppable del.durotan-hotspot__price-number',
			]
		);

		$this->add_control(
			'sale_color',
			[
				'label'     => esc_html__( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-banner-shoppable del.durotan-hotspot__price-number' => 'color: {{VALUE}}',

				],
			]
		);
	}

	protected function section_style_hotspot_button() {
		$this->add_control(
			'heading_hotspot_button_style',
			[
				'label'     => esc_html__( 'Button', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'button_hotspot_text_typography',
			[
				'label'      => esc_html__( 'Size', 'durotan' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 250,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-hotspot__button .durotan-hotspot__button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-hotspot__button .durotan-hotspot__button-text' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_hotspot_text_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-hotspot__button .durotan-hotspot__button-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-hotspot__button .durotan-hotspot__button-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_hotspot_text_hover',
			[
				'label'     => esc_html__( 'Hover', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'button_hotspot_text_color_hover',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-hotspot__button:hover .durotan-hotspot__button-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-hotspot__button:hover .durotan-hotspot__button-icon' => 'color: {{VALUE}}',
				],
			]
		);
	}

	protected function section_style_hotspot_button_close() {
		$this->add_control(
			'heading_hotspot_button_close_style',
			[
				'label'     => esc_html__( 'Button Close', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'button_close_hotspot_icon_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-hotspot__button-close .durotan-svg-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'button_close_hotspot_icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'durotan' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 250,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-hotspot__button-close .durotan-svg-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'heading_close_hotspot_icon_hover',
			[
				'label'     => esc_html__( 'Hover', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'button_close_hotspot_icon_color_hover',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-hotspot__button-close .durotan-svg-icon:hover' => 'color: {{VALUE}}',
				],
			]
		);
	}

	protected function section_style_hotspot_point() {
		$this->add_control(
			'heading_point_style',
			[
				'label'     => esc_html__( 'Point', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'point_width',
			[
				'label'      => esc_html__( 'Width', 'durotan' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 250,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-hotspot__point-icon::after' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-hotspot__point-icon::before' => 'width: calc( {{SIZE}}{{UNIT}} + 16px );',
				],
			]
		);

		$this->add_responsive_control(
			'point_height',
			[
				'label'      => esc_html__( 'Height', 'durotan' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 250,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-hotspot__point-icon::after' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-hotspot__point-icon::before' => 'height: calc( {{SIZE}}{{UNIT}} + 16px );',
				],
			]
		);

		$this->add_control(
			'point_bg_color',
			[
				'label'      => esc_html__( 'Background Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-hotspot__point-icon::before' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'point_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-hotspot__point-icon::after' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'point_icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'durotan' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 250,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-hotspot__point .durotan-hotspot__point-icon .durotan-svg-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'point_icon_color',
			[
				'label'      => esc_html__( 'Icon Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner-shoppable .durotan-hotspot__point-icon .durotan-svg-icon' => 'color: {{VALUE}}',
				],
			]
		);
	}

	/**
	 * Render icon box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$classes = [
			'durotan-banner-shoppable',
			'durotan-banner-shoppable-elementor',
		];

		$content = '';

		$content_inner_width = $settings['content_inner_width'] == '' ? 'container' : $settings['content_inner_width'];

		$content .= '<div class="durotan-banner-shoppable__content">';
			if ( $settings['before_title'] ) {
				$content .= '<div class="durotan-banner-shoppable__before-heading">' . $settings['before_title'] . '</div>';
			}

			if ( $settings['title'] ) {
				$content .= '<div class="durotan-banner-shoppable__heading">' . $settings['title'] . '</div>';
			}

			if ( $settings['description'] ) {
				$content .= '<div class="durotan-banner-shoppable__description">' . $settings['description'] . '</div>';
			}

			// Button
			$button_text = $settings['button_text'] ? sprintf('<span class="button-banner-shoppable">%s</span>', $settings['button_text'] ) : '';

			$button_text = $settings['link']['url'] ? Helper::control_url( 'button', $settings['link'], $settings['button_text'], ['class' => 'button-banner-shoppable button-link'] ) : $button_text;

			if ( $settings['button_text'] ) {
				$content .= $button_text;
			}

		$content .= '</div>';

		if ( $settings['hotspot'] ) {

			$hotspot_count = 0;
			$hotspot_html = '';

			foreach ( $settings['hotspot'] as $hotspot ) {

				$hotspot_html .= '<div class="durotan-hotspot__point durotan-hotspot__point-'.$hotspot['_id'].' durotan-hotspot__point-'.$hotspot_count.' elementor-repeater-item-' . $hotspot['_id'].'">';

					$hotspot_html .= '<div class="durotan-hotspot__point-icon">';
						$hotspot_html .= \Durotan\Addons\Helper::get_svg( 'plus', '', 'shop' );
					$hotspot_html .= '</div>';

					$hotspot_html .= '<div class="durotan-hotspot__item">';

						if ( $hotspot['hotspot_image']['id'] ) {
							$settings['image'] = $hotspot['hotspot_image'];
							$hotspot_html .= '<div class="durotan-hotspot__image">';
							$hotspot_html .= Group_Control_Image_Size::get_attachment_image_html( $settings );
							$hotspot_html .= '</div>';
						}

						$hotspot_html .= '<div class="durotan-hotspot__content">';

							if ( $hotspot['hotspot_title'] ) {
								$hotspot_html .= '<div class="durotan-hotspot__heading"><a href="'.$hotspot['hotspot_button_link']['url'].'">' . $hotspot['hotspot_title'] . '</a></div>';
							}

							if ( $hotspot['hotspot_price'] ) {

								$hotspot_html .= '<div class="durotan-hotspot__price">';

								if ( $hotspot['hotspot_price_sale'] ) {

									$hotspot_html .= $hotspot['hotspot_price'] ? '<ins class="durotan-hotspot__price-number">' . $hotspot['hotspot_price'] . '</ins>' : '';

									$hotspot_html .= $hotspot['hotspot_price_sale'] ? '<del class="durotan-hotspot__price-number">' . $hotspot['hotspot_price_sale'] . '</del>' : '';
								} else {
									$hotspot_html .= $hotspot['hotspot_price'] ? '<span class="durotan-hotspot__price-number">' . $hotspot['hotspot_price'] . '</span>' : '';
								}
								$hotspot_html .= '</div>';
							}

							if ( $hotspot['hotspot_button_text'] ) {

								$hotspot_html .= sprintf(
									'<div class="durotan-hotspot__button"><a href="%s">%s<span class="durotan-hotspot__button-text">%s</span></a></div>',
									$hotspot['hotspot_button_link']['url'],
									\Durotan\Addons\Helper::get_svg( 'cart', 'durotan-hotspot__button-icon durotan-icon', 'shop' ),
									$hotspot['hotspot_button_text']
								);
							}

						$hotspot_html .= '</div>';

						$hotspot_html .= '<div class="durotan-hotspot__button-close">'.\Durotan\Addons\Helper::get_svg( 'close' ).'</div>';

					$hotspot_html .= '</div>';

				$hotspot_html .= '</div>';

				$hotspot_count++;
			}

			$content .= $hotspot_html;
		}

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		echo sprintf(
			'<div %s>
				<div class="durotan-banner-shoppable__inner %s">
					%s
				</div>
			</div>',
			$this->get_render_attribute_string( 'wrapper' ),
			$content_inner_width,
			$content,
		);
	}
}