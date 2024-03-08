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
 * Video_Banner widget
 */
class Video_Banner extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'durotan-video-banner';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Durotan - Video Banner', 'durotan' );
	}

	/**
	 * Retrieve the widget circle.
	 *
	 * @return string Widget circle.
	 */
	public function get_icon() {
		return 'eicon-youtube';
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
			'durotan-frontend',
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
			'source_video',
			[
				'label' => __( 'Source Video', 'durotan' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'youtube',
				'options' => [
					'youtube'  => __( 'Youtube', 'durotan' ),
					'vimeo'    => __( 'Vimeo', 'durotan' ),
					'self_hosted' => __( 'Self Hosted', 'durotan' ),
				],
			]
		);

		$this->add_control(
			'youtube_url',
			[
				'label' => __( 'Link', 'durotan' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter your URL', 'durotan' ) . ' (YouTube)',
				'default' => 'https://www.youtube.com/embed/XWFLG-u1sA4',
				'label_block' => false,
				'condition' => [
					'source_video' => 'youtube',
				],
			]
		);

		$this->add_control(
			'vimeo_url',
			[
				'label' => __( 'Link', 'durotan' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter your URL', 'durotan' ) . ' (Vimeo)',
				'default' => 'https://player.vimeo.com/video/397056760',
				'label_block' => false,
				'condition' => [
					'source_video' => 'vimeo',
				],
			]
		);

		$this->add_control(
			'insert_url',
			[
				'label' => __( 'External URL', 'durotan' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'source_video' => 'self_hosted',
				],
			]
		);

		$this->add_control(
			'external_url',
			[
				'label' => __( 'Link', 'durotan' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'Enter your URL', 'durotan' ),
				'autocomplete' => false,
				'options' => false,
				'label_block' => true,
				'show_label' => false,
				'media_type' => 'video',
				'condition' => [
					'source_video' => 'self_hosted',
					'insert_url' => 'yes'
				],
			]
		);

		$this->add_control(
			'hosted_url',
			[
				'label' => __( 'Choose File', 'durotan' ),
				'type' => Controls_Manager::MEDIA,
				'media_type' => 'video',
				'condition' => [
					'source_video' => 'self_hosted',
					'insert_url' => '',
				],
			]
		);

		$this->add_control(
			'text',
			[
				'label'   => esc_html__( 'Text', 'durotan' ),
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
				'label' => __( 'Style', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'video_banner_height',
			[
				'label'      => esc_html__( 'Video Banner Height', 'durotan' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 100,
						'max' => 5000,
					],
				],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-video-banner .durotan-video-banner__video' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'background_color',
			[
				'label'     => esc_html__( 'Background Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-video-banner .durotan-video-banner__video' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'video_banner_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-video-banner .durotan-video-banner__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->section_style_text();
		$this->section_style_button();

		$this->end_controls_section();

	}

	protected function section_style_text() {
		// Description
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
				'selector' => '{{WRAPPER}} .durotan-video-banner .durotan-video-banner__text',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-video-banner .durotan-video-banner__text' => 'color: {{VALUE}}',

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
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-video-banner .durotan-video-banner__text' => 'margin-bottom: {{SIZE}}{{UNIT}}',
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
				'selector' => '{{WRAPPER}} .durotan-video-banner .durotan-video-banner__button--text',
			]
		);

		$this->add_control(
			'button_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-video-banner .durotan-video-banner__button--text' => 'color: {{VALUE}}',
					'{{WRAPPER}} .durotan-video-banner .durotan-video-banner__button--text:before' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-video-banner .durotan-video-banner__button--text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .durotan-video-banner .durotan-video-banner__button--text:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_color_hover',
			[
				'label'      => esc_html__( 'Border Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-video-banner .durotan-video-banner__button--text::after' => 'border-color: {{VALUE}}',
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
			'durotan-video-banner',
			'durotan-video-banner-elementor',
		];

		$output =  '';

		if ($settings['source_video'] == 'youtube') {

			$video_url['url'] = $settings['youtube_url'];

		} elseif ($settings['source_video'] == 'vimeo') {

			$video_url['url'] = $settings['vimeo_url'];

		} else {

			if ( ! empty( $settings['insert_url'] ) ) {
				$video_url['url'] = $settings['external_url']['url'];
			} else {
				$video_url['url'] = $settings['hosted_url']['url'];
			}
		}

		if ( $video_url['url'] ) {

			$output .= '<div class="durotan-video-banner__video" data-video-source="'.$settings['source_video'].'" data-video-url="'.$video_url['url'].'">';

			$output .= '</div>';
		}

		$output .= '<div class="durotan-video-banner__content">';

			$output .= '<div class="durotan-video-banner__wrapper">';

				if ( $settings['text'] ) {
					$output .= '<div class="durotan-video-banner__text">' . $settings['text'] . '</div>';
				}

				$button_text = $settings['button_text'] ? sprintf('<span class="durotan-video-banner__button--text">%s</span>', $settings['button_text'] ) : '';

				$button_text = $settings['link']['url'] ? Helper::control_url( 'button', $settings['link'], $settings['button_text'], ['class' =>'durotan-video-banner__button--text button-link '] ) : $button_text;

				if ( $settings['button_text'] ) {
					$output .= '<div class="durotan-video-banner__button">'.$button_text.'</div>';
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