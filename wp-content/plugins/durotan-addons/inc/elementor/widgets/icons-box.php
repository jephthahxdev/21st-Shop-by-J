<?php

namespace Durotan\Addons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Icons Box widget
 */
class Icons_Box extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'durotan-icons-box';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Durotan - Icons Box', 'durotan' );
	}

	/**
	 * Retrieve the widget circle.
	 *
	 * @return string Widget circle.
	 */
	public function get_icon() {
		return 'eicon-icon-box';
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


	/**
	 * Section Content
	 */
	protected function section_content() {
		$this->content_settings_controls();
	}

	protected function content_settings_controls() {
		$this->start_controls_section(
			'section_content',
			[ 'label' => esc_html__( 'Icons Box', 'durotan' ) ]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'icon_type',
			[
				'label' => esc_html__( 'Icon type', 'razzi' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'image' => esc_html__( 'Image', 'razzi' ),
					'icon' 	=> esc_html__( 'Icon', 'razzi' ),
					'external' 	=> esc_html__( 'External', 'razzi' ),
				],
				'default' => 'icon',
			]
		);

		$repeater->add_control(
			'icon',
			[
				'label'   => esc_html__( 'Icons', 'durotan' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(),
				'conditions' => [
					'terms' => [
						[
							'name' => 'icon_type',
							'value' => 'icon',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'razzi' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'icon_type',
							'value' => 'image',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'external_url',
			[
				'label' => esc_html__( 'External URL', 'razzi' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'icon_type',
							'value' => 'external',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'This is title', 'durotan' ),
			]
		);

		$repeater->add_control(
			'description_text',
			[
				'label' => '',
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'durotan' ),
				'placeholder' => __( 'Enter your description', 'durotan' ),
				'rows' => 10,
				'separator' => 'none',
				'show_label' => false,
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'durotan' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'durotan' ),
			]
		);

		$this->add_control(
			'elements',
			[
				'label'         => esc_html__( 'Icons Lists', 'durotan' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => array(),
				'title_field'   => '{{{ title }}}',
				'prevent_empty' => false
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Section Style
	 */
	protected function section_style() {
		$this->section_content_content();
	}

	protected function section_content_content() {
		// Content
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => __( 'Content', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_vertical_alignment',
			[
				'label' => __( 'Type', 'durotan' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'horizontal' => __( 'Horizontal', 'durotan' ),
					'vertical' => __( 'Vertical', 'durotan' ),
				],
				'default' => 'horizontal',
				'prefix_class' => 'icon-box-',
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-icons-box .icon-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Icon
		$this->add_control(
			'heading_title',
			[
				'label' => __( 'Icon', 'durotan' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_position_v',
			[
				'label'                => esc_html__( 'Vertical Position', 'durotan' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
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
					'{{WRAPPER}} .durotan-icons-box .icon-box' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'top'   => 'align-items: flex-start',
					'middle' => 'align-items: center',
					'bottom'  => 'align-items: flex-end',
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
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
					'{{WRAPPER}} .durotan-icons-box .icon-box .durotan-icon' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'     => esc_html__( 'Size', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-icons-box .icon-box .durotan-icon' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .durotan-icons-box .icon-box .durotan-img-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => __( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-icons-box .icon-box .durotan-icon' => 'color: {{VALUE}};',
				],
			]
		);

		// Title
		$this->add_control(
			'heading_title_1',
			[
				'label' => __( 'Title', 'durotan' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_spacing',
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
					'{{WRAPPER}} .durotan-icons-box .icon-box__title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .durotan-icons-box .icon-box__title, {{WRAPPER}} .durotan-icons-box .icon-box__title a',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-icons-box .icon-box__title, {{WRAPPER}} .durotan-icons-box .icon-box__title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color_hover',
			[
				'label'     => __( 'Color Hover', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-icons-box .icon-box__title:hover, {{WRAPPER}} .durotan-icons-box .icon-box__title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		// Description
		$this->add_control(
			'heading_title_2',
			[
				'label' => __( 'Description', 'durotan' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Color', 'durotan' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-icons-box .icon-box__description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .durotan-icons-box .icon-box__description',
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

		$output =  array();

		$els = $settings['elements'];

		if ( ! empty ( $els ) ) {
			foreach ( $els as $index => $item ) {
				$icon = '';

				if ( $item['icon_type'] === 'image' ) {
					$icon = sprintf( '<img class="durotan-icon durotan-img-icon" alt="%s" src="%s">', esc_attr( $item['title'] ), esc_url( $item['image']['url'] ) );
				} if ( $item['icon_type'] === 'external' ) {
					$icon = '<img class="durotan-icon durotan-img-icon" src="' . $item['external_url'] . '" alt="' . $item['title'] . '" />';
				} else {
					if (  $item['icon'] && ! empty( $item['icon']['value'] ) && \Elementor\Icons_Manager::is_migration_allowed() ) {
						ob_start();
						\Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] );

						$add_class_icon = $item['icon']['library'] == 'svg' ? 'durotan-svg-icon' : '';

						$icon = '<span class="durotan-icon '.$add_class_icon.'">' . ob_get_clean() . '</span>';
					}
				}

				if ( ! empty( $item['link']['url'] ) ) {
					$this->add_link_attributes( 'link-'. $index, $item['link'] );
					$title = $item['title'] ? sprintf( '<h6 class="icon-box__title"><a %s>%s</a></h6>', $this->get_render_attribute_string( 'link-'. $index ), $item['title'] ) : '';
				} else {
					$title = $item['title'] ? sprintf('<h6 class="icon-box__title">%s</h6>',$item['title']) : '';
				}

				if( $item['description_text'] ) {
					$des = '<div class="icon-box__description">' . $item['description_text'] . '</div>';
				}

				$output_content = $title;
				$output_content .= $des;

				$output[] = sprintf( '<div class="icon-box">%s<div class="icon-box__content">%s</div></div>', $icon, $output_content );
			}

		}

		echo sprintf(
			'<div class="durotan-icons-box">
				<div class="durotan-icons-box__wrapper">%s</div>
			</div>',
			implode('', $output)
		);
	}
}