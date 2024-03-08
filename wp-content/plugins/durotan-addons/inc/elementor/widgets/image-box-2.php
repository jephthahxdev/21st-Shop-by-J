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
 * Image Box 2 widget
 */
class Image_Box_2 extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'durotan-image-box-2';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Durotan - Image Box 2', 'durotan' );
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

		$this->add_responsive_control(
			'background_img',
			[
				'label'    => __( 'Image', 'durotan' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://via.placeholder.com/353x190/cccccc?text=353x190',
				],
			]
		);

		$this->add_control(
			'text',
			[
				'label'       => esc_html__( 'Text', 'durotan' ),
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
		$this->start_controls_section(
			'section_general_style',
			[
				'label' => __( 'Style', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'background_color',
			[
				'label'      => esc_html__( 'Background Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-image-box-2 .durotan-image-box-2__bg' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'text_heading',
			[
				'label' => esc_html__( 'Text', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .durotan-image-box-2 .durotan-image-box-2__text',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-image-box-2 .durotan-image-box-2__text' => 'color: {{VALUE}}',
					'{{WRAPPER}} .durotan-image-box-2 .durotan-image-box-2__text::after' => 'border-color: {{VALUE}}',

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
			'durotan-image-box-2',
			'durotan-image-box-2-elementor',
		];

		$output =  '';

		$output .= $settings['link']['url'] ? '<a class="durotan-image-box-2__link" href="'.$settings['link']['url'].'">' : '<div class="durotan-image-box-2__link">';

			if ( $settings['background_img']['id'] ) {
				$output .= wp_get_attachment_image( $settings['background_img']['id'], 'full', false );
			}

			$output .= '<span class="durotan-image-box-2__bg"></span>';

			if ( $settings['text'] ) {
				$output .= '<span class="durotan-image-box-2__text">'.$settings['text'].'</span>';
			}

		$output .= $settings['link']['url'] ? '</a>' : '</div>';

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