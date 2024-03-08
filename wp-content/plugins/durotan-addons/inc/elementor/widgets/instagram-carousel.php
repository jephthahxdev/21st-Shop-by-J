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
 * Instagram Carousel widget
 */
class Instagram_Carousel extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'durotan-instagram-carousel';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Durotan - Instagram Carousel', 'durotan' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-carousel';
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
		$this->section_content_option();
		$this->section_carousel_option();

	}

	protected function section_content_option() {
		$this->start_controls_section(
			'section_content',
			[ 'label' => esc_html__( 'Content', 'durotan' ) ]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Title', 'durotan' ),
				'label_block' => true,
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

		$this->add_control(
			'limit',
			[
				'label'       => esc_html__( 'Limit', 'durotan' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 10,
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


		$this->end_controls_section();
	}

	protected function section_carousel_option() {
		$this->start_controls_section(
			'section_carousel_options',
			[
				'label' => esc_html__( 'Carousel Settings', 'durotan' ),
				'type'  => Controls_Manager::SECTION,
			]
		);

		$this->add_responsive_control(
			'slide_to_show',
			[
				'label'     => esc_html__( 'Slide to Show', 'durotan' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5,
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'slide_to_scroll',
			[
				'label'     => esc_html__( 'Slide to Scroll', 'durotan' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
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
					'arrows' 			=> esc_html__( 'Arrows', 'durotan' ),
					'scrollbar'			=> esc_html__( 'ScrollBar', 'durotan' ),
				],
				'default' => 'dots',
			]
		);

		$this->add_control(
			'infinite',
			[
				'label'   => esc_html__( 'Infinite Loop', 'durotan' ),
				'type'    => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'durotan' ),
				'label_off'    => __( 'No', 'durotan' ),
				'return_value' => 'yes',
				'default'      => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'   => esc_html__( 'Autoplay', 'durotan' ),
				'type'    => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'durotan' ),
				'label_off'    => __( 'No', 'durotan' ),
				'return_value' => 'yes',
				'default' => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label'     => esc_html__( 'Autoplay Speed', 'durotan' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1000,
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Section Style
	 */

	protected function section_style() {
		$this->section_content_style();
		$this->section_content_carousel();

	}

	protected function section_content_style() {
		$this->start_controls_section(
			'style_general',
			[
				'label' => __( 'Content', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_style',
			[
				'label' => esc_html__( 'Title', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .durotan-instagram-carousel .durotan-instagram-carousel__heading',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'        => esc_html__( 'Color', 'durotan' ),
				'type'         => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-instagram-carousel .durotan-instagram-carousel__heading' => 'color: {{VALUE}};',
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
						'max' => 250,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-instagram-carousel .durotan-instagram-carousel__header' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'text_style',
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
				'selector' => '{{WRAPPER}} .durotan-instagram-carousel .durotan-instagram-carousel__text',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'        => esc_html__( 'Color', 'durotan' ),
				'type'         => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-instagram-carousel .durotan-instagram-carousel__text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_style',
			[
				'label' => esc_html__( 'Space Item', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'space_between',
			[
				'label'     => esc_html__( 'Space Between', 'durotan' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 10,
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function section_content_carousel() {
		$this->start_controls_section(
			'style_carousel',
			[
				'label' => __( 'Carousel Style', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Arrows
		$this->add_control(
			'arrow_style_heading',
			[
				'label' => esc_html__( 'Arrows', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'arrow_icon_size',
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
					'{{WRAPPER}} .durotan-twitter-carousel .durotan-twitter-carousel__arrow .durotan-swiper-button' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'arrow_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-twitter-carousel .durotan-twitter-carousel__arrow .durotan-swiper-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'arrow_color_hover',
			[
				'label'      => esc_html__( 'Color Hover', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-twitter-carousel .durotan-twitter-carousel__arrow .durotan-swiper-button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'heading_custom_spacing',
			[
				'label'     => esc_html__( 'Horizontal Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-twitter-carousel .durotan-twitter-carousel__arrow .durotan-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .durotan-twitter-carousel .durotan-twitter-carousel__heading-arrow .durotan-twitter-carousel__arrow .durotan-swiper-button-prev' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .durotan-twitter-carousel .durotan-twitter-carousel__arrow .durotan-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				],
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
						'max' => 250,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-twitter-carousel .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .durotan-twitter-carousel .swiper-pagination-bullet' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'sliders_dots_bottom_spacing',
			[
				'label'     => esc_html__( 'Top Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 500,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-twitter-carousel .durotan-twitter-carousel__pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .durotan-twitter-carousel .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dots_style_heading_active',
			[
				'label' => esc_html__( 'Active', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'sliders_dots_width_active',
			[
				'label'     => __( 'Width', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 250,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-twitter-carousel .swiper-pagination-bullet-active' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'sliders_dots_ac_bgcolor',
			[
				'label'     => esc_html__( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-twitter-carousel .swiper-pagination-bullet-active' 	=> 'background-color: {{VALUE}};',
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

		$nav        = $settings['navigation'] ? $settings['navigation'] : 'none';
		$nav_tablet = empty( $settings['navigation_tablet'] ) ? $nav : $settings['navigation_tablet'];
		$nav_mobile = empty( $settings['navigation_mobile'] ) ? $nav : $settings['navigation_mobile'];

		$this->add_render_attribute(
			'wrapper', 'class', [
				'durotan-instagram-carousel',
				'durotan-instagram-carousel-elementor',
				'durotan-swiper-carousel-elementor',
				'navigation-' . $nav,
				'navigation-tablet-' . $nav_tablet,
				'navigation-mobile-' . $nav_mobile,
			]
		);

		$output    = [];

		if ( $settings['title'] || $settings['text'] ) {

			$output[] = '<div class="durotan-instagram-carousel__header">';

				$output[] = '<div class="durotan-instagram-carousel__heading">'.$settings['title'].'</div>';
				$output[] = '<div class="durotan-instagram-carousel__text">'.$settings['text'].'</div>';

			$output[] = '</div>';

		}

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

			$output[] = '<div class="durotan-instagram-carousel__items swiper-container">';

				$output[] = sprintf('<ul class="instagram-wrapper swiper-wrapper">');

				foreach ( $instagram as $data ) {

					if ( $count > intval( $settings['limit'] ) ) {
						break;
					}

					$output[] = '<li class="instagram-item swiper-slide"><img src="' . esc_url( $data['images']['thumbnail'] ) . '" alt="' . esc_attr( $data['caption'] ) . '"><a target="_blank" href="' . esc_url( $data['link'] ) . '">'.\Durotan\Addons\Helper::get_svg( 'instagram', '', 'social_2' ).'</a></li>';

					$count ++;
				}

				$output[] = sprintf('</ul>');

			$output[] = sprintf('</div>');
		} else {
			$output[] = '<div class="durotan-instagram-carousel__items swiper-container">';

				$output[] = sprintf('<ul class="instagram-wrapper swiper-wrapper">');

				$count = 1;

				foreach ( $settings['image_list'] as $index => $item ) {
					if ( $item['image']['url'] ) {
						$this->add_link_attributes( 'icon-link', $item['link'] );
						$link = $item['link']['url'] ? $item['link']['url'] : '#';
						$target = $item['link']['is_external'] ? ' target="_blank"' : '';
						$nofollow = $item['link']['nofollow'] ? ' rel="nofollow"' : '';

						$output[] = '<li class="instagram-item swiper-slide">';
							$output[] = '<img src="' . esc_url( $item['image']['url'] ) . '" loading="lazy" alt="' . esc_attr( $item['caption'] ) . '">';
							$output[] = '<a href="' . $link . '" ' . $target . $nofollow . $this->get_render_attribute_string( 'icon-link' ) . '">'.\Durotan\Addons\Helper::get_svg( 'instagram', '', 'social_2' ).'</a>';
						$output[] = '</li>';
					}

					$count ++;
				}

				$output[] = sprintf('</ul>');

			$output[] = sprintf('</div>');
		}

		if ( $count > $settings['slide_to_show'] ) {

			$output[] = '<div class="durotan-instagram-carousel__pagination swiper-pagination"></div>';

			$output[] = '<div class="durotan-instagram-carousel__scrollbar swiper-scrollbar"></div>';

			$output[] = '<div class="durotan-instagram-carousel__arrow durotan-swiper-button">';

				$output[] = \Durotan\Addons\Helper::get_svg( 'chevron-left', 'durotan-swiper-button-prev durotan-swiper-button' );

				$output[] = \Durotan\Addons\Helper::get_svg( 'chevron-right', 'durotan-swiper-button-next durotan-swiper-button' );

			$output[] = '</div>';
		}

		echo sprintf(
			'<div %s>%s</div>',
			$this->get_render_attribute_string( 'wrapper' ),
			implode( '', $output )
		);

	}
}
