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
 * Image Parallax widget
 */
class Image_Parallax extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'durotan-image-parallax';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Durotan - Image Parallax', 'durotan' );
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
					'url' => 'https://via.placeholder.com/1920x750/cccccc?text=1920x750',
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-image-parallax' => 'background-image: url("{{URL}}");',
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
					'{{WRAPPER}} .durotan-image-parallax' => 'background-size: {{VALUE}}',
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
				'default'   => 'no-repeat',
				'options'   => [
					'repeat'   => esc_html__( 'Repeat', 'durotan' ),
					'no-repeat' => esc_html__( 'No Repeat', 'durotan' ),
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-image-parallax' => 'background-repeat: {{VALUE}}',
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
					'{{WRAPPER}} .durotan-image-parallax' => 'background-position: {{VALUE}};',
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
					'{{WRAPPER}} .durotan-image-parallax' => 'background-position: {{LEFT}}{{UNIT}} {{TOP}}{{UNIT}};',
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
					'{{WRAPPER}} .durotan-image-parallax' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'background_img[url]!' => '',
				],
			]
		);

		$this->add_control(
			'text',
			[
				'label'   => esc_html__( 'Text', 'durotan' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'This is text. ', 'durotan' ),
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
			'link',
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
			'image_height',
			[
				'label'      => esc_html__( 'Image Height', 'durotan' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 100,
						'max' => 5000,
					],
				],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-image-parallax' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'banner_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-image-parallax' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_text',
			[
				'label'     => esc_html__( 'Text', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .durotan-image-parallax .durotan-image-parallax__text',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-image-parallax .durotan-image-parallax__text' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_responsive_control(
			'text_spacing',
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
					'{{WRAPPER}} .durotan-image-parallax .durotan-image-parallax__text' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

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
				'selector' => '{{WRAPPER}} .durotan-image-parallax .durotan-image-parallax__title',
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-image-parallax .durotan-image-parallax__title' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_control(
			'heading_border_color',
			[
				'label'     => esc_html__( 'Boder Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-image-parallax .durotan-image-parallax__title::after' => 'border-color: {{VALUE}}',

				],
			]
		);

		$this->add_control(
			'heading_title_hover',
			[
				'label'     => esc_html__( 'Hover', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'heading_color_hover',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-image-parallax .durotan-image-parallax__title:hover' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_control(
			'heading_border_color_hover',
			[
				'label'     => esc_html__( 'Boder Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-image-parallax .durotan-image-parallax__title:hover::after' => 'border-color: {{VALUE}}',

				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render circle box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$classes = [
			'durotan-image-parallax',
			'durotan-image-parallax-elementor',
		];

		$output =  '';

		$output .= '<div class="durotan-image-parallax__content">';

			if ( $settings['text'] ) {
				$output .= '<div class="durotan-image-parallax__text">' . $settings['text'] . '</div>';
			}

			if ( $settings['title'] ) {
				$output .= $settings['link']['url'] ? '<a class="durotan-image-parallax__title" href="'.$settings['link']['url'].'">'.$settings['title'].'</a>' : '<div class="durotan-image-parallax__title">'.$settings['title'].'</div>';
			}

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