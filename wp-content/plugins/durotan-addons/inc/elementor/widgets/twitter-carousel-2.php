<?php

namespace Durotan\Addons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Twitter Carousel 2 widget
 */
class Twitter_Carousel_2 extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'durotan-twitter-carousel-2';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Durotan - Twitter Carousel 2', 'durotan' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-twitter';
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
		$this->section_slider_option();
	}

	protected function section_content_option() {
		$this->start_controls_section(
			'section_content',
			[ 'label' => esc_html__( 'Content', 'durotan' ) ]
		);

		$this->add_control(
			'twitter_heading',
			[
				'label' => esc_html__( 'Twitter API Token', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'consumer_key',
			[
				'label'       => esc_html__( 'Consumer Key (API Key)', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			]
		);

		$this->add_control(
			'consumer_secret',
			[
				'label'       => esc_html__( 'Consumer Secret (API Secret)', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			]
		);

		$this->add_control(
			'access_token',
			[
				'label'       => esc_html__( 'Access Token', 'durotan' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'label_block' => true,
			]
		);

		$this->add_control(
			'access_token_secret',
			[
				'label'       => esc_html__( 'Access Token Secret', 'durotan' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'label_block' => true,
			]
		);

		$this->add_control(
			'cache_time',
			[
				'label'       => esc_html__( 'Cache Time (seconds)', 'durotan' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 3600,
			]
		);

		$this->add_control(
			'username',
			[
				'label'       => esc_html__( 'Twitter Username', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
			]
		);

		$this->end_controls_section();
	}

	protected function section_slider_option() {
		$this->start_controls_section(
			'section_slider_options',
			[
				'label' => esc_html__( 'Slider Options', 'durotan' ),
				'type'  => Controls_Manager::SECTION,
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
				],
				'default' => '',
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
		$this->section_style_content();
		$this->section_style_carousel();
	}

	protected function section_style_content() {
		$this->start_controls_section(
			'style_general',
			[
				'label' => __( 'General', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label'     => esc_html__( 'Text', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .durotan-twitter-carousel-2 .durotan-twitter-carousel-2__text',
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-twitter-carousel-2 .durotan-twitter-carousel-2__text' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_control(
			'heading_color_link',
			[
				'label'     => esc_html__( 'Link Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-twitter-carousel-2 .durotan-twitter-carousel-2__text a' => 'color: {{VALUE}}',

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
					'{{WRAPPER}} .durotan-twitter-carousel-2 .durotan-twitter-carousel-2__text' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'after_text_title',
			[
				'label'     => esc_html__( 'After Text', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'after_text_typography',
				'selector' => '{{WRAPPER}} .durotan-twitter-carousel-2 .durotan-twitter-carousel-2__after-text',
			]
		);

		$this->add_control(
			'after_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-twitter-carousel-2 .durotan-twitter-carousel-2__after-text' => 'color: {{VALUE}}',

				],
			]
		);

		$this->end_controls_section();
	}

	protected function section_style_carousel() {
		// Arrows
		$this->start_controls_section(
			'section_style_arrows',
			[
				'label' => esc_html__( 'Slider Styles', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
					'{{WRAPPER}} .durotan-twitter-carousel-2 .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .durotan-twitter-carousel-2 .swiper-pagination-bullet' => 'margin-right: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .durotan-twitter-carousel-2 .durotan-twitter-carousel-2__pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .durotan-twitter-carousel-2 .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .durotan-twitter-carousel-2 .swiper-pagination-bullet-active' => 'width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .durotan-twitter-carousel-2 .swiper-pagination-bullet-active' 	=> 'background-color: {{VALUE}};',
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

		$classes = [
			'durotan-twitter-carousel-2',
			'durotan-twitter-carousel-2-elementor',
			'durotan-swiper-carousel-elementor',
			'navigation-' . $nav,
			'navigation-tablet-' . $nav_tablet,
			'navigation-mobile-' . $nav_mobile,
		];

		$output = $output_html =  '';
		$slide_count = 0;

		if ( $settings['consumer_key'] && $settings['consumer_secret'] && $settings['access_token'] && $settings['access_token_secret'] && $settings['username'] && $settings['cache_time'] ) {

			$transient_key = 'durotan_tweets_' . md5( serialize( $settings ) );

			$username = $settings['username'] == '' ? '' : $settings['username'];

			if ( false === ( $tweets = get_transient( $transient_key ) ) ) {
				require_once DUROTAN_ADDONS_DIR . 'inc/api/twitter-api-php.php';

				$setting = array(
					'oauth_access_token'        => $settings['access_token'],
					'oauth_access_token_secret' => $settings['access_token_secret'],
					'consumer_key'              => $settings['consumer_key'],
					'consumer_secret'           => $settings['consumer_secret'],
				);

				$url    = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
				$fields = '?screen_name='.$username.'&count=10';
				$method = 'GET';

				$twitter = new \TwitterAPIExchange( $setting );
				$tweets  = $twitter->setGetfield( $fields )->buildOauth( $url, $method )->performRequest();
				$tweets  = @json_decode( $tweets );

				if ( empty( $tweets ) ) {
					esc_html_e( 'Cannot retrieve tweets.', 'durotan' );
					echo $after_widget;

					return;
				}

				// Save our new transient.
				set_transient( $transient_key, $tweets, $settings['cache_time'] );
			}

			$output .= '<div class="durotan-twitter-carousel-2__items swiper-container">';
				$output .= '<div class="swiper-wrapper">';

					foreach ( $tweets as $tweet ) {
						if ( $tweet ) {
							$time = date_create($tweet->created_at);
							$created_time = date_format( $time, "d M, Y");

							$output_html .= '<div class="durotan-twitter-carousel-2-item swiper-slide">';

								$output_html .= '<div class="durotan-twitter-carousel-2__icon">';
									$output_html .= \Durotan\Addons\Helper::get_svg( 'twitter', '', 'social' );
								$output_html .= '</div>';

								$output_html .= '<div class="durotan-twitter-carousel-2__text">';
									$output_html .= $this->convert_links( $tweet->text );
								$output_html .= '</div>';

								$output_html .= '<div class="durotan-twitter-carousel-2__after-text">@'.$tweet->user->screen_name.'<span>/</span>' . esc_html__( 'Posted On', 'durotan' ) . ' '.$created_time.'</div>';

							$output_html .= '</div>';

							$slide_count++;
						}

					}

					$slide_count = apply_filters( 'durotan_twitter_carousel_2_count', $slide_count );

					$output .= apply_filters( 'durotan_twitter_carousel_2_content', $output_html );

				$output .= '</div>';
			$output .= '</div>';

			if ( $slide_count > 1 ) {
				$output .= '<div class="durotan-twitter-carousel-2__pagination swiper-pagination"></div>';
			}
		}


		$this->add_render_attribute( 'wrapper', 'class', $classes );

		echo sprintf(
			'<div %s>
				%s
			</div>',
			$this->get_render_attribute_string( 'wrapper' ),
			$output
		);

	}

	/**
	 * Replace link tweet
	 *
	 * @param $text
	 *
	 * @return string
	 */
	protected function convert_links( $text ) {
		$text = preg_replace( '#https?://[a-z0-9._/-]+#i', '<a rel="nofollow" target="_blank" href="$0">$0</a>', $text );
		$text = preg_replace( '#@([a-z0-9_]+)#i', '@<a rel="nofollow" target="_blank" href="http://twitter.com/$1">$1</a>', $text );
		$text = preg_replace( '# \#([a-z0-9_-]+)#i', ' #<a rel="nofollow" target="_blank" href="http://twitter.com/search?q=%23$1">$1</a>', $text );

		return $text;
	}

}