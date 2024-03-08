<?php

namespace Durotan\Addons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Map widget
 */
class Map extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'durotan-map';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Durotan - Map', 'durotan' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-google-maps';
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
			'mapbox',
			'mapboxgl',
			'mapbox-sdk',
			'durotan-frontend'
		];
	}

	public function get_style_depends() {
		return [
			'mapbox',
			'mapboxgl'
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
		$this->start_controls_section(
			'section_content',
			[ 'label' => esc_html__( 'Content', 'durotan' ) ]
		);

		$this->add_control(
			'access_token',
			[
				'label'       => esc_html__( 'Access Token', 'durotan' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'Enter your access token', 'durotan' ),
				'label_block' => true,
				'description' => sprintf(__('Please go to <a href="%s" target="_blank">Maps Box APIs</a> to get a key', 'durotan'), esc_url('https://www.mapbox.com')),
			]
		);

		$this->add_control(
			'hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);


		$this->add_control(
			'local',
			[
				'label'       => esc_html__( 'Local', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'New York', 'durotan' ),
				'label_block' => true,
			]
		);


		$this->add_control(
			'hr_1',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'zoom',
			[
				'label'       => esc_html__( 'Zoom', 'durotan' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '10',
			]
		);

		$this->add_control(
			'mode',
			[
				'label'       => esc_html__( 'Mode', 'durotan' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'streets-v11' 	=> esc_html__( 'Streets', 'durotan' ),
					'light-v10' 	=> esc_html__( 'Light', 'durotan' ),
					'dark-v10'  	=> esc_html__( 'Dark', 'durotan' ),
					'outdoors-v11'  => esc_html__( 'Outdoors', 'durotan' ),
				],
				'default'     => 'light-v10',
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
			'style_general',
			[
				'label' => __( 'Content', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => __( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-map' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'color_1',
			[
				'label'     => esc_html__( 'Color water', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label'     => esc_html__( 'Map Height', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-map > *' => 'height: {{SIZE}}{{UNIT}}',
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

		$id     = uniqid( 'durotan-map-' );

		$this->add_render_attribute(
			'wrapper', 'class', [
				'durotan-map'
			]
		);

		// JS
		$color_1                     = $settings['color_1'] ? $settings['color_1'] : '#c8d7d4';

		$output_map = array(
			'token'   => $settings['access_token'],
			'zoom'     => intval( $settings['zoom'] ),
			'color_1' => $color_1,
			'local'   => $settings['local'],
			'mode'    => $settings['mode'],
		);

		$this->add_render_attribute('map','data-map',wp_json_encode($output_map) );

		echo sprintf(
			'<div %s %s><div class="durotan-map__content" id="%s"></div></div>',
			$this->get_render_attribute_string( 'wrapper' ),
			$this->get_render_attribute_string( 'map' ),
			$id
		);

	}
}