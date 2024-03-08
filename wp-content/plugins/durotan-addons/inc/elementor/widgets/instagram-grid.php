<?php

namespace Durotan\Addons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Durotan\Addons\Elementor\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Instagram_Grid widget
 */
class Instagram_Grid extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'durotan-instagram-grid';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Durotan - Instagram Grid', 'durotan' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-instagram-gallery';
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
		$this->start_controls_section(
			'section_content',
			[ 'label' => esc_html__( 'Content', 'durotan' ) ]
		);

		$this->add_control(
			'instagram_type',
			[
				'label' => esc_html__( 'Instagram type', 'durotan' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'token' 	=> 'Token',
					'custom' 	=> 'Custom',
				],
				'default' => 'token',
			]
		);

		$this->add_control(
			'access_token',
			[
				'label'       => esc_html__( 'Access Token', 'durotan' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'Enter your access token', 'durotan' ),
				'label_block' => true,
				'conditions' => [
					'terms' => [
						[
							'name' => 'instagram_type',
							'value' => 'token',
						],
					],
				],
			]
		);

		$this->add_control(
			'limit',
			[
				'label'       => esc_html__( 'Limit', 'durotan' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 9,
				'conditions' => [
					'terms' => [
						[
							'name' => 'instagram_type',
							'value' => 'token',
						],
					],
				],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'durotan' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
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

		$repeater->add_control(
			'caption',
			[
				'label' => esc_html__( 'Caption', 'durotan' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'placeholder' => esc_html__( 'Enter your caption', 'durotan' ),
				'rows' => 4,
			]
		);

		$this->add_control(
			'image_list',
			[
				'label'         => esc_html__( 'Image List', 'durotan' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'prevent_empty' => false,
				'conditions' => [
					'terms' => [
						[
							'name' => 'instagram_type',
							'value' => 'custom',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'   => esc_html__( 'Columns', 'durotan' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 10,
				'default' => 5,
			]
		);


		$this->end_controls_section();
	}

	/**
	 * Section Style
	 */

	protected function section_style() {
		$this->start_controls_section(
			'style_general',
			[
				'label' => __( 'Content', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_style',
			[
				'label' => esc_html__( 'Item', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'item_gap',
			[
				'label'      => __( 'Gap', 'durotan' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-instagram-grid .instagram-wrapper' => 'margin: calc(-{{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} .durotan-instagram-grid .instagram-item' => 'padding:calc({{SIZE}}{{UNIT}}/2);',
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

		$this->add_render_attribute(
			'wrapper', 'class', [
				'durotan-instagram-grid',
				'durotan-element-columns',
				'columns-'.$settings['columns'],
			]
		);

		$output    = [];

		if ( $settings['instagram_type'] === 'token' ) {
			$instagram = Helper::get_instagram_get_photos_by_token( $settings['limit'],$settings['access_token'] );

			$user = apply_filters( 'durotan_get_instagram_user', array() );
			if ( empty( $user ) ) {
				$user = Helper::get_instagram_user( $settings['access_token'] );
			}

			if ( is_wp_error( $instagram ) ) {
				return $instagram->get_error_message();
			}

			if ( ! $instagram ) {
				return;
			}

			$count = 1;

			$output[] = sprintf('<ul class="instagram-wrapper">');

			foreach ( $instagram as $data ) {

				if ( $count > intval( $settings['limit'] ) ) {
					break;
				}

				$output[] = '<li class="instagram-item"><img src="' . esc_url( $data['images']['thumbnail'] ) . '" alt="' . esc_attr( $data['caption'] ) . '"><a target="_blank" href="' . esc_url( $data['link'] ) . '">'.\Durotan\Addons\Helper::get_svg( 'instagram', '', 'social_2' ).'</a></li>';

				$count ++;
			}
			$output[] = sprintf('</ul>');
		} else {
			$output[] = sprintf('<ul class="instagram-wrapper">');

				$count = 1;

				foreach ( $settings['image_list'] as $index => $item ) {
					if ( $item['image']['url'] ) {
						$this->add_link_attributes( 'icon-link', $item['link'] );
						$link = $item['link']['url'] ? $item['link']['url'] : '#';
						$target = $item['link']['is_external'] ? ' target="_blank"' : '';
						$nofollow = $item['link']['nofollow'] ? ' rel="nofollow"' : '';

						$output[] = '<li class="instagram-item">';
							$output[] = '<img src="' . esc_url( $item['image']['url'] ) . '" loading="lazy" alt="' . esc_attr( $item['caption'] ) . '">';
							$output[] = '<a href="' . $link . '" ' . $target . $nofollow . $this->get_render_attribute_string( 'icon-link' ) . '">'.\Durotan\Addons\Helper::get_svg( 'instagram', '', 'social_2' ).'</a>';
						$output[] = '</li>';
					}

					$count ++;
				}

			$output[] = sprintf('</ul>');
		}

		echo sprintf(
			'<div %s>%s</div>',
			$this->get_render_attribute_string( 'wrapper' ),
			implode( '', $output )
		);

	}
}
