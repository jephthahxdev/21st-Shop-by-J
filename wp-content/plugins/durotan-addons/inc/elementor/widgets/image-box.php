<?php

namespace Durotan\Addons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Durotan\Addons\Elementor\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Image Box widget
 */
class Image_Box extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'durotan-image-box';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Durotan - Image Box', 'durotan' );
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
					'url' => 'https://via.placeholder.com/770x500/cccccc?text=770x500',
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
			'button_heading',
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
				'label' => __( 'Content', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'position_content',
			[
				'label' => __( 'Position', 'durotan' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'right'    => __( 'Right', 'durotan' ),
					'left'     => __( 'Left', 'durotan' ),
				],
				'prefix_class' => 'durotan-image-box__position-content--',
			]
		);

		$this->add_control(
			'content_background_color',
			[
				'label'      => esc_html__( 'Background Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-image-box' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-image-box .durotan-image-box__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .durotan-image-box .durotan-image-box__content' => 'text-align: {{VALUE}}',
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
				'selector' => '{{WRAPPER}} .durotan-image-box .durotan-image-box__before-heading',
			]
		);

		$this->add_control(
			'before_heading_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-image-box .durotan-image-box__before-heading' => 'color: {{VALUE}}',

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
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-image-box .durotan-image-box__before-heading' => 'margin-bottom: {{SIZE}}{{UNIT}}',
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
				'selector' => '{{WRAPPER}} .durotan-image-box .durotan-image-box__heading',
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-image-box .durotan-image-box__heading' => 'color: {{VALUE}}',

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
					'{{WRAPPER}} .durotan-image-box .durotan-image-box__heading' => 'margin-bottom: {{SIZE}}{{UNIT}}',
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
				'selector' => '{{WRAPPER}} .durotan-image-box .durotan-image-box__description',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-image-box .durotan-image-box__description' => 'color: {{VALUE}}',

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
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-image-box .durotan-image-box__description' => 'margin-bottom: {{SIZE}}{{UNIT}}',
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
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .durotan-image-box .durotan-image-box__content .durotan-image-box__button--text',
			]
		);

		$this->add_control(
			'button_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-image-box .durotan-image-box__content .durotan-image-box__button--text' => 'color: {{VALUE}}',
					'{{WRAPPER}} .durotan-image-box .durotan-image-box__content .durotan-image-box__button--text:after' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_heading_hover',
			[
				'label' => esc_html__( 'Hover', 'durotan' ),
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
					'{{WRAPPER}} .durotan-image-box .durotan-image-box__content .durotan-image-box__button--text:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_color_hover',
			[
				'label'      => esc_html__( 'Border Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-image-box .durotan-image-box__content .durotan-image-box__button--text::after' => 'border-color: {{VALUE}}',
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
			'durotan-image-box',
			'durotan-image-box-elementor',
		];

		$output =  '';

		if ( $settings['background_img']['id'] ) {
			$output .= '<div class="durotan-image-box__bg">';
				$output .= wp_get_attachment_image( $settings['background_img']['id'], 'full', false );
			$output .= '</div>';
		}

		$output .= '<div class="durotan-image-box__content">';

			if ( $settings['before_title'] ) {
				$output .= '<div class="durotan-image-box__before-heading">' . $settings['before_title'] . '</div>';
			}

			if ( $settings['title'] ) {
				$output .= '<div class="durotan-image-box__heading">' . $settings['title'] . '</div>';
			}

			if ( $settings['description'] ) {
				$output .= '<div class="durotan-image-box__description">' . $settings['description'] . '</div>';
			}

			$button_text = $settings['button_text'] ? sprintf('<span class="durotan-image-box__button--text">%s</span>', $settings['button_text'] ) : '';

			$button_text = $settings['link']['url'] ? Helper::control_url( 'button', $settings['link'], $settings['button_text'], ['class' =>'durotan-image-box__button--text button-link '] ) : $button_text;

			if ( $settings['button_text'] ) {
				$output .= '<div class="durotan-image-box__button">'.$button_text.'</div>';
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