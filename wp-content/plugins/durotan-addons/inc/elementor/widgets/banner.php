<?php

namespace Durotan\Addons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Controls_Stack;
use Durotan\Addons\Elementor\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Banner widget
 */
class Banner extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'durotan-banner';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Durotan - Banner', 'durotan' );
	}

	/**
	 * Retrieve the widget circle.
	 *
	 * @return string Widget circle.
	 */
	public function get_icon() {
		return 'eicon-image';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'durotan' ];
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
		$this->content_settings_controls();
	}

	protected function content_settings_controls() {
		$this->start_controls_section(
			'section_content',
			[ 'label' => esc_html__( 'Content', 'durotan' ) ]
		);

		$this->add_control(
			'heading_background',
			[
				'label' => esc_html__( 'Background', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'background_img',
			[
				'label'    => __( 'Image', 'durotan' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://via.placeholder.com/735x650/cccccc?text=735x650',
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__bg' => 'background-image: url("{{URL}}");',
				],
			]
		);

		$this->add_responsive_control(
			'background_size',
			[
				'label'     => esc_html__( 'Size', 'durotan' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => [
					'cover'   => esc_html__( 'Cover', 'durotan' ),
					'contain' => esc_html__( 'Contain', 'durotan' ),
					'auto'    => esc_html__( 'Auto', 'durotan' ),
					'inherit' => esc_html__( 'Inherit', 'durotan' ),
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__bg' => 'background-size: {{VALUE}}',
				],
				'condition' => [
					'background_img[url]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'background_repeat',
			[
				'label'     => esc_html__( 'Repeat', 'durotan' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'repeat',
				'options'   => [
					'repeat'   => esc_html__( 'Repeat', 'durotan' ),
					'no-repeat' => esc_html__( 'No Repeat', 'durotan' ),
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__bg' => 'background-repeat: {{VALUE}}',
				],
				'condition' => [
					'background_img[url]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'background_position',
			[
				'label'     => esc_html__( 'Position', 'durotan' ),
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
					'{{WRAPPER}} .durotan-banner .durotan-banner__bg' => 'background-position: {{VALUE}};',
				],
				'condition' => [
					'background_img[url]!' => '',
				],

			]
		);

		$this->add_responsive_control(
			'background_position_xy',
			[
				'label'              => esc_html__( 'Custom Position', 'durotan' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'allowed_dimensions' => [ 'top', 'left' ],
				'size_units'         => [ 'px', '%' ],
				'default'            => [ ],
				'selectors'          => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__bg' => 'background-position: {{LEFT}}{{UNIT}} {{TOP}}{{UNIT}};',
				],
				'condition' => [
					'background_position' => [ 'initial' ],
					'background_img[url]!' => '',
				],
				'required' => true,
			]
		);

		$this->add_responsive_control(
			'background_color',
			[
				'label'     => esc_html__( 'Background Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__bg' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'background_img[url]!' => '',
				],
			]
		);

		$this->add_control(
			'before_title',
			[
				'label'       => esc_html__( 'Before Title', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Before title', 'durotan' ),
				'label_block' => true,
				'separator' => 'before',
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
				'default' => esc_html__( 'I am box content. Click edit button to change this text. ', 'durotan' ),
			]
		);

		$this->add_control(
			'link_type',
			[
				'label'     => esc_html__( 'Link Type', 'durotan' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'only_button_text'   => esc_html__( 'Only Button Text', 'durotan' ),
					'all_banner'         => esc_html__( 'All Banner', 'durotan' ),
				],
				'default'   => 'only_button_text',
			]
		);

		$this->add_control(
			'button_heading',
			[
				'label' => esc_html__( 'Button 1', 'durotan' ),
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

		$this->add_control(
			'button_2_heading',
			[
				'label' => esc_html__( 'Button 2', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_2_text',
			[
				'label'   => esc_html__( 'Text', 'durotan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			]
		);

		$this->add_control(
			'link_2',
			[
				'label'       => esc_html__( 'Link', 'durotan' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'durotan' ),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Section Style
	 */
	protected function section_style() {
		$this->section_general_style();
	}

	protected function section_general_style() {
		// Content
		$this->start_controls_section(
			'section_general_style',
			[
				'label' => __( 'Style', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'banner_height',
			[
				'label'      => esc_html__( 'Banner Height', 'durotan' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 100,
						'max' => 5000,
					],
				],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'banner_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 5000,
					],
					'em' => [
						'min' => 0,
						'max' => 5000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .durotan-banner .durotan-banner__link-all' => 'top: -{{TOP}}{{UNIT}}; right: -{{RIGHT}}{{UNIT}}; bottom: -{{BOTTOM}}{{UNIT}}; left: -{{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_position_h',
			[
				'label'                => esc_html__( 'Horizontal Position', 'durotan' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => [
					'left'   => [
						'title' => esc_html__( 'Left', 'durotan' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'durotan' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'durotan' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'              => '',
				'selectors'            => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'left'   => 'align-items: flex-start',
					'center' => 'align-items: center',
					'right'  => 'align-items: flex-end',
				],
			]
		);

		$this->add_control(
			'content_position_v',
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
				'default'              => '',
				'selectors'            => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'top'   	=> 'justify-content: flex-start',
					'middle'	=> 'justify-content: center',
					'bottom'  	=> 'justify-content: flex-end',
				],
			]
		);

		$this->add_responsive_control(
			'content_text_align',
			[
				'label'       => esc_html__( 'Text Align', 'durotan' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'left'   => [
						'title' => esc_html__( 'Left', 'durotan' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'durotan' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'durotan' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content' => 'text-align: {{VALUE}}',
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
				'selector' => '{{WRAPPER}} .durotan-banner .durotan-banner__before-heading',
			]
		);

		$this->add_control(
			'before_heading_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__before-heading' => 'color: {{VALUE}}',

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
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__before-heading' => 'margin-bottom: {{SIZE}}{{UNIT}}',
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
				'selector' => '{{WRAPPER}} .durotan-banner .durotan-banner__heading',
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__heading' => 'color: {{VALUE}}',

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
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__heading' => 'margin-bottom: {{SIZE}}{{UNIT}}',
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

		$this->add_responsive_control(
			'description_width',
			[
				'label'      => esc_html__( 'Width', 'durotan' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 100,
						'max' => 5000,
					],
				],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__description' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .durotan-banner .durotan-banner__description',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__description' => 'color: {{VALUE}}',

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
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__description' => 'margin-bottom: {{SIZE}}{{UNIT}}',
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

		$this->start_controls_tabs( 'style_button_type' );

		$this->start_controls_tab(
			'style_button_1',
			[
				'label' => esc_html__( 'Button 1', 'durotan' ),
			]
		);

		$this->section_style_button_1();

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_button_2',
			[
				'label' => esc_html__( 'Button 2', 'durotan' ),
			]
		);

		$this->section_style_button_2();

		$this->end_controls_tab();

		$this->end_controls_tabs();

	}

	protected function section_style_button_1() {
		$this->add_control(
			'button_1_style',
			[
				'label' => __( 'Style', 'durotan' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'style_1',
				'options' => [
					'style_1'  => __( 'Style 1', 'durotan' ),
					'line' 	   => __( 'Style 2', 'durotan' ),
					'line-2'   => __( 'Style 3', 'durotan' ),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_1_typography',
				'selector' => '{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text--1',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_1_bg_color',
			[
				'label'      => esc_html__( 'Background Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text--1:before' => 'background-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_1_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text--1' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'     => 'btn_1_border',
				'selector' => '{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text--1',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'btn_1_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text--1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'btn_1_spacing',
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
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text--1' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_1_heading_hover',
			[
				'label' => esc_html__( 'Hover', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_1_color_hover',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text--1:hover' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_1_bg_color_hover',
			[
				'label'      => esc_html__( 'Background Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text--1:after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text--1:hover:after' => 'background-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_1_border_color_hover',
			[
				'label'      => esc_html__( 'Border Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text--1:hover' => 'border-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		// Button 1 line

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_line_1_typography',
				'selector' => '{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text-1--line',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'operator' => '!==',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_line_1_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text-1--line' => 'color: {{VALUE}}',
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text-1--line:before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text-1--line.button-text--line-2:after' => 'border-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'operator' => '!==',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'btn_line_1_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text-1--line' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'operator' => '!==',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'btn_line_1_spacing',
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
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text-1--line' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'operator' => '!==',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_line_1_heading_hover',
			[
				'label' => esc_html__( 'Hover', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'operator' => '!==',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_line_1_color_hover',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text-1--line:hover' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'operator' => '!==',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'btn_line_1_border_height_hover',
			[
				'label'     => esc_html__( 'Border Height', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text-1--line::after' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'operator' => '!==',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_line_1_border_color_hover',
			[
				'label'      => esc_html__( 'Border Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text-1--line::after' => 'border-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'operator' => '!==',
							'value' => 'style_1',
						],
					],
				],
			]
		);
	}

	protected function section_style_button_2() {
		$this->add_control(
			'button_2_style',
			[
				'label' => __( 'Style', 'durotan' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'style_1',
				'options' => [
					'style_1'  => __( 'Style 1', 'durotan' ),
					'line' 	   => __( 'Style 2', 'durotan' ),
					'line-2'   => __( 'Style 3', 'durotan' ),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_2_typography',
				'selector' => '{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text--2',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_2_bg_color',
			[
				'label'      => esc_html__( 'Background Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text--2:before' => 'background-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_2_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text--2' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'     => 'btn_2_border',
				'selector' => '{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text--2',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'btn_2_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text--2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_2_heading_hover',
			[
				'label' => esc_html__( 'Hover', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_2_color_hover',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text--2:hover' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_2_bg_color_hover',
			[
				'label'      => esc_html__( 'Background Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text--2:after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text--2:hover:after' => 'background-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_2_border_color_hover',
			[
				'label'      => esc_html__( 'Border Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text--2:hover' => 'border-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		// Button 2 line

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_line_2_typography',
				'selector' => '{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text-2--line',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'operator' => '!==',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_line_2_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text-2--line' => 'color: {{VALUE}}',
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text-2--line:before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text-2--line.button-text--line-2:after' => 'border-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'operator' => '!==',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'btn_line_2_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text-2--line' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'operator' => '!==',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_line_2_heading_hover',
			[
				'label' => esc_html__( 'Hover', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'operator' => '!==',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_line_2_color_hover',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text-2--line:hover' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'operator' => '!==',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'btn_line_2_border_height_hover',
			[
				'label'     => esc_html__( 'Border Height', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text-2--line::after' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'operator' => '!==',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_line_2_border_color_hover',
			[
				'label'      => esc_html__( 'Border Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-banner .durotan-banner__content .button-text-2--line::after' => 'border-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'operator' => '!==',
							'value' => 'style_1',
						],
					],
				],
			]
		);
	}

	/**
	 * Render circle box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$classes = [
			'durotan-banner',
			'durotan-banner-elementor',
		];

		$output =  '';

		if ( $settings['background_img'] ) {
			$output .= '<div class="durotan-banner__bg"></div>';
		}

		$output .= '<div class="durotan-banner__content">';

			$output .= '<div class="durotan-banner__details">';

				if ( $settings['before_title'] ) {
					$output .= '<div class="durotan-banner__before-heading">' . $settings['before_title'] . '</div>';
				}

				if ( $settings['title'] ) {
					$output .= '<div class="durotan-banner__heading">' . $settings['title'] . '</div>';
				}

				if ( $settings['description'] ) {
					$output .= '<div class="durotan-banner__description">' . $settings['description'] . '</div>';
				}

			$output .= '</div>';


			if ( $settings['link_type'] === 'all_banner' ) {
				$output .= '<a href="'.$settings['link']['url'].'" class="durotan-banner__link-all"></a>';
			}

			$output .= '<div class="durotan-banner__buttons">';

				// Buttons
				if ( $settings['button_1_style'] === 'style_1' ) {
					$class_button_1 = 'button-text button-text--1';
				} elseif ( $settings['button_1_style'] === 'line' ) {
					$class_button_1 = 'button-text-1--line button-text--line';
				} else {
					$class_button_1 = 'button-text-1--line button-text--line-2';
				}

				if ( $settings['button_2_style'] === 'style_1' ) {
					$class_button_2 = 'button-text button-text--2';
				} elseif ( ( $settings['button_2_style'] === 'line' ) ) {
					$class_button_2 = 'button-text-2--line button-text--line';
				} else {
					$class_button_2 = 'button-text-2--line button-text--line-2';
				}

				$button_text_1 = $settings['button_text'] ? sprintf('<span class="%s">%s</span>', $class_button_1, $settings['button_text'] ) : '';

				$button_text_2 = $settings['button_2_text'] ? sprintf('<span class="%s">%s</span>', $class_button_2, $settings['button_2_text'] ) : '';

				$button_text_1 = $settings['link']['url'] ? Helper::control_url( 'button', $settings['link'], $settings['button_text'], ['class' => $class_button_1 . ' button-link '] ) : $button_text_1;

				$button_text_2 = $settings['link_2']['url'] ? Helper::control_url( 'button-2', $settings['link_2'], $settings['button_2_text'], ['class' => $class_button_2 . ' button-link '] ) : $button_text_2;

				if ( $settings['button_text'] ) {
					$output .= $button_text_1;
				}

				if ( $settings['button_2_text'] ) {
					$output .= $button_text_2;
				}

			$output .= '</div>';

		$output .= '</div>';

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		echo sprintf(
			'<div %s>
				%s
			</div>',
			$this->get_render_attribute_string( 'wrapper' ),
			$output
		);
	}
}