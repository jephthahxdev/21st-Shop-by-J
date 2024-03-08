<?php

namespace Durotan\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Durotan\Addons\Elementor\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Icon Box widget
 */
class Posts_Carousel extends Widget_Base {
    /**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'durotan-posts-carousel';
	}

     /**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Durotan - Posts carousel', 'durotan' );
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
        $this->section_heading_settings_controls();
		$this->section_posts_settings_controls();
		$this->section_carousel_settings_controls();
    }

	/**
	 * Section Style
	 */
	protected function section_style() {
		$this->section_heading_style();
		$this->section_content_style();
		$this->section_carousel_style();
	}

    protected function section_heading_settings_controls() {
        $this->start_controls_section(
			'section_heading',
			[ 'label' => esc_html__( 'Heading', 'durotan' ) ]
		);

        $this->add_control(
			'title',
			[
				'label'       => __( 'Title', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter your title', 'durotan' ),
				'default'     => __( 'Heading Text', 'durotan' ),
                'label_block' => true,
			]
		);

        $this->add_control(
			'description',
			[
				'label'       => __( 'Description', 'durotan' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Enter your description', 'durotan' ),
				'default'     => __( 'Short Description', 'durotan' ),
                'label_block' => true,
			]
		);

        $this->add_control(
			'button_divider',
			[
				'label' => esc_html__( 'Button', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

        $this->add_control(
			'button_text',
			[
				'label'       => esc_html__( 'Button Text', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Button', 'durotan' ),
                'label_block' => true,
			]
		);

        $this->add_control(
			'button_link', [
				'label'         => esc_html__( 'Button Link', 'durotan' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'durotan' ),
				'show_external' => true,
				'default'       => [
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				],
			]
		);
        $this->end_controls_section();
    }

	protected function section_posts_settings_controls() {

		// Brands Settings
		$this->start_controls_section(
			'section_blogs',
			[ 'label' => esc_html__( 'Posts', 'durotan' ) ]
		);

		$this->add_control(
			'blog_cats',
			[
				'label'       => esc_html__( 'Category', 'durotan' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => Helper::taxonomy_list('category'),
				'default'     => '',
				'multiple'    => true,
				'label_block' => true,
			]
		);

		$this->add_control(
			'blog_tags',
			[
				'label'       => esc_html__( 'Tags', 'durotan' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => Helper::tags_list(),
				'default'     => '',
				'multiple'    => true,
				'label_block' => true,
			]
		);

		$this->add_control(
			'limit',
			[
				'label'     => esc_html__( 'Total', 'durotan' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 8,
				'min'       => 2,
				'max'       => 50,
				'step'      => 1,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'      => esc_html__( 'Order By', 'durotan' ),
				'type'       => Controls_Manager::SELECT,
				'options'    => [
					'date'       => esc_html__( 'Date', 'durotan' ),
					'name'       => esc_html__( 'Name', 'durotan' ),
					'id'         => esc_html__( 'Ids', 'durotan' ),
					'rand' 		=> esc_html__( 'Random', 'durotan' ),
				],
				'default'    => 'date',
			]
		);

		$this->add_control(
			'order',
			[
				'label'      => esc_html__( 'Order', 'durotan' ),
				'type'       => Controls_Manager::SELECT,
				'options'    => [
					''     => esc_html__( 'Default', 'durotan' ),
					'ASC'  => esc_html__( 'Ascending', 'durotan' ),
					'DESC' => esc_html__( 'Descending', 'durotan' ),
				],
				'default'    => '',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				'default'   => 'full',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	protected function section_carousel_settings_controls() {
		// Carousel Settings
		$this->start_controls_section(
			'section_carousel_settings',
			[ 'label' => esc_html__( 'Carousel Settings', 'durotan' ) ]
		);
		$this->add_responsive_control(
			'slidesToShow',
			[
				'label'   => esc_html__( 'Slides to show', 'durotan' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 7,
				'desktop_default' => 2,
				'tablet_default' => 2,
				'mobile_default' => 1,
				'frontend_available' => true,
			]
		);
		$this->add_responsive_control(
			'slidesToScroll',
			[
				'label'   => esc_html__( 'Slides to scroll', 'durotan' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 5,
				'desktop_default' => 2,
				'tablet_default' => 2,
				'mobile_default' => 1,
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'navigation',
			[
				'label'     => esc_html__( 'Navigation', 'durotan' ),
				'type'      => Controls_Manager::SELECT,
				'options' => [
					'none'   => esc_html__( 'None', 'durotan' ),
					'arrows' => esc_html__( 'Arrows', 'durotan' ),
					'dots' 	 => esc_html__( 'Dots', 'durotan' ),
				],
				'default' => 'none',
			]
		);

		$this->add_control(
			'infinite',
			[
				'label'     => __( 'Infinite', 'durotan' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'durotan' ),
				'label_on'  => __( 'On', 'durotan' ),
				'return_value' => 'yes',
				'default'   => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'     => __( 'Autoplay', 'durotan' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'durotan' ),
				'label_on'  => __( 'On', 'durotan' ),
				'return_value' => 'yes',
				'default'   => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label'   => __( 'Autoplay Speed (in ms)', 'durotan' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1000,
				'min'     => 100,
				'step'    => 100,
				'frontend_available' => true,
			]
		);

		$this->end_controls_section(); // End Carousel Settings
	}

	protected function section_heading_style(){
		// Carousel Settings
		$this->start_controls_section(
			'section_heading_style',
			[
				'label' => esc_html__( 'Heading', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'position',
			[
				'label'     => __( 'Position', 'durotan' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => __( 'Left', 'durotan' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => __( 'Center', 'durotan' ),
						'icon'  => 'eicon-text-align-center',
					],
				],
				'default'   => 'left',
				'selectors' => [
					'{{WRAPPER}} .durotan-posts-carousel .durotan-posts-carousel__heading' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'heading_padding_style',
			[
				'label'     => __( 'Padding', 'durotan' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'default'    => [],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .durotan-posts-carousel .durotan-posts-carousel__heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_divider',
			[
				'label' => __( 'Title', 'durotan' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .durotan-posts-carousel .durotan-heading__title',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-posts-carousel .durotan-heading__title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_spacing',
			[
				'label'     => __( 'Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-posts-carousel .durotan-heading__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'sescription_divider',
			[
				'label' => __( 'Description', 'durotan' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .durotan-posts-carousel .durotan-heading__desc',
			]
		);
		$this->add_control(
			'description_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-posts-carousel .durotan-heading__desc' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'description_space_bottom',
			[
				'label'     => __( 'Space Bottom', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 150,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-posts-carousel .durotan-heading__desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'button_style_divider',
			[
				'label' => __( 'Button', 'durotan' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .durotan-posts-carousel .durotan-button.button-link',
			]
		);
		$this->add_control(
			'button_color',
			[
				'label'     => esc_html__( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-posts-carousel .durotan-button.button-link' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'show_icon',
			[
				'label'     => esc_html__( 'Icon', 'durotan' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'durotan' ),
				'label_on'  => __( 'Show', 'durotan' ),
				'return_value' => 'show',
				'default'   => 'show',
			]
		);
		$this->end_controls_section();
   	}

	protected function section_content_style(){
		// Content
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => __( 'Content', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'layout',
			[
				'label'      => esc_html__( 'Layout', 'durotan' ),
				'type'       => Controls_Manager::SELECT,
				'options'    => [
					'layout-1'       => esc_html__( 'Layout 1', 'durotan' ),
					'layout-2'       => esc_html__( 'Layout 2', 'durotan' ),
					'layout-3'       => esc_html__( 'Layout 3', 'durotan' ),
				],
				'default'    => 'layout-1',
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'summary_padding_style',
			[
				'label'     => __( 'Padding', 'durotan' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'default'    => [],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .durotan-posts-carousel .post-summary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'post_thumbnail_style',
			[
				'label' => esc_html__( 'Thumbnail', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'layout' => ['layout-1', 'layout-3'],
				],
			]
		);

		$this->add_responsive_control(
			'post_thumbnail_spacing',
			[
				'label'     => __( 'Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-posts-carousel .layout-1 .post-thumbnail' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .durotan-posts-carousel .layout-3 .post-thumbnail' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout' => ['layout-1', 'layout-3'],
				],
			]
		);

		$this->add_control(
			'post_title_style',
			[
				'label' => esc_html__( 'Title', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'post_title_typography',
				'selector' => '{{WRAPPER}} .durotan-posts-carousel .post-title',
			]
		);

		$this->add_control(
			'post_title_color',
			[
				'label'     => __( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-posts-carousel .post-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'post_title_hover_color',
			[
				'label'     => __( 'Hover Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-posts-carousel .post-title a:hover' => 'color: {{VALUE}}; --durotan-color-box-shadow: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'post_title_spacing',
			[
				'label'     => __( 'Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-posts-carousel .post-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'post_desc_style',
			[
				'label' => esc_html__( 'Description', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'layout' => 'layout-2',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'post_desc_typography',
				'selector' => '{{WRAPPER}} .durotan-posts-carousel .post-summary p',
				'condition' => [
					'layout' => 'layout-2',
				],
			]
		);

		$this->add_control(
			'post_desc_color',
			[
				'label'     => __( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-posts-carousel .post-summary p' => 'color: {{VALUE}};',
				],
				'condition' => [
					'layout' => 'layout-2',
				],
			]
		);

		$this->add_control(
			'read_more_style',
			[
				'label' => esc_html__( 'Read more button', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'layout' => 'layout-2',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'read_more_typography',
				'selector' => '{{WRAPPER}} .durotan-posts-carousel .durotan-button.btn-read-more',
				'condition' => [
					'layout' => 'layout-2',
				],
			]
		);

		$this->add_control(
			'read_more_color',
			[
				'label'     => __( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-posts-carousel .durotan-button.btn-read-more' => 'color: {{VALUE}};',
				],
				'condition' => [
					'layout' => 'layout-2',
				],
			]
		);

		$this->add_control(
			'post_meta_style',
			[
				'label' => esc_html__( 'Post Meta', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs(
			'style_tabs_post_meta'
		);

		$this->start_controls_tab(
			'content_style_number',
			[
				'label' => __( 'Number', 'durotan' ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'meta_number_typography',
				'selector' => '{{WRAPPER}} .durotan-posts-carousel .post-date .day',
			]
		);
		$this->add_control(
			'meta_number_color',
			[
				'label'     => __( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-posts-carousel .post-date .day' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'meta_number_spacing',
			[
				'label'     => __( 'Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-posts-carousel .post-date .day' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout' => 'layout-1',
				],
			]
		);

		$this->add_responsive_control(
			'meta_number_spacing_all',
			[
				'label'     => __( 'Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-posts-carousel .layout-2 .post-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .durotan-posts-carousel .layout-3 .post-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout' => ['layout-2', 'layout-3'],
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'content_style_text',
			[
				'label' => __( 'Text', 'durotan' ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'meta_text_typography',
				'selector' => '{{WRAPPER}} .durotan-posts-carousel .post-date span:not(.day),{{WRAPPER}} .durotan-posts-carousel .post-meta',
			]
		);
		$this->add_control(
			'meta_text_color',
			[
				'label'     => __( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-posts-carousel .post-date span:not(.day),{{WRAPPER}} .durotan-posts-carousel .post-meta span' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'meta_cat_color',
			[
				'label'     => __( 'Category Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-posts-carousel .post-meta span.meta-cat a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function section_carousel_style(){
        // Carousel Settings
		$this->start_controls_section(
			'section_carousel_style',
			[
				'label' => esc_html__( 'Carousel Setting', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_control(
			'arrows_divider',
			[
				'label' => __( 'Arrows', 'durotan' ),
				'type' => Controls_Manager::HEADING,
			]
		);

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'arrows_typography',
				'selector' => '{{WRAPPER}} .durotan-posts-carousel .durotan-swiper-button',
                'exclude' => [ 'font_family'],
			]
		);

        $this->add_control(
			'arrows_color',
			[
				'label'     => esc_html__( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-posts-carousel .durotan-swiper-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrows_hover_color',
			[
				'label'     => esc_html__( 'Hover Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-posts-carousel .durotan-swiper-button:hover' => 'color: {{VALUE}};',
				],
			]
		);
        $this->add_responsive_control(
			'arrows_spacing',
			[
				'label'     => __( 'Spacing', 'durotan' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => -150,
						'max' => 150,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-posts-carousel .durotan-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .durotan-posts-carousel .durotan-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_control(
			'dots_divider',
			[
				'label' => __( 'Dots', 'durotan' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_responsive_control(
			'dots_font_size',
			[
				'label'     => __( 'Size', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-posts-carousel .swiper-pagination .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'dots_spacing_horizontal',
			[
				'label'      => __( 'Horizontal Spacing', 'durotan' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-posts-carousel .swiper-pagination' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dots_spacing_vertical ',
			[
				'label'      => __( 'Vertical Spacing', 'durotan' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-posts-carousel .swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'dots_color',
			[
				'label'     => esc_html__( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-posts-carousel .swiper-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dots_hover_color',
			[
				'label'     => esc_html__( 'Hover Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-posts-carousel .swiper-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .durotan-posts-carousel .swiper-pagination .swiper-pagination-bullet:hover' => 'background-color: {{VALUE}};',
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

		$nav        = $settings['navigation'];
		$nav_tablet = empty( $settings['navigation_tablet'] ) ? $nav : $settings['navigation_tablet'];
		$nav_mobile = empty( $settings['navigation_mobile'] ) ? $nav : $settings['navigation_mobile'];

		$not_carousel = $settings['limit'] < 3 ? 'durotan-posts-not-carousel' : '';

		$classes = [
			'durotan-posts-carousel',
		];
		$swiper = [
			'durotan-posts-carousel__elementor',
			'durotan-swiper-carousel-elementor',
			'durotan-swiper-slider-elementor',
			'navigation-' . $nav,
			'navigation-tablet-' . $nav_tablet,
			'navigation-mobile-' . $nav_mobile,
			$not_carousel
		];

		$atts = [
			'post_status' => 'publish',
			'ignore_sticky_posts' => true,
			'posts_per_page'     	=> $settings['limit'],
			'orderby'     	=> $settings['orderby']
		];

		if ($settings['order'] != ''){
			$atts['order'] = $settings['order'];
		}

		if ( ! empty( $settings['blog_cats'] ) ) {
				$atts['tax_query'] = array(
					'relation' => 'OR',
					array(
						'taxonomy' => 'category',
						'field'    => 'slug',
						'terms'    => $settings['blog_cats'],
						'operator' => 'IN',
					),
				);
			}

		if ( !empty( $settings['blog_tags'] ) ) {
			$atts['tag'] = $settings['blog_tags'];
		}

		$query = new \WP_Query($atts);
		$html =array();

		$index = 0;
		while ($query->have_posts()) : $query->the_post();

			$post_url = array();

			$post_url['url'] = esc_url(get_permalink());
			$post_url['is_external'] = $post_url['nofollow'] = '';

			$key_img = 'img_'.$index;

			$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
			$image ='';

			if ( $post_thumbnail_id ) {

				$image_src = wp_get_attachment_image_src( $post_thumbnail_id );

				$settings['image'] = array(
					'url' => $image_src ? $image_src[0] : '',
					'id'  => $post_thumbnail_id
				);

				$image = Helper::control_url( $key_img, $post_url, Group_Control_Image_Size::get_attachment_image_html( $settings ), ['class' => 'post-thumbnail'] );
			}

			$cats = get_the_category();
			$count = count( $cats );
			$i      = 0;
			$number = apply_filters( 'durotan_elementor_meta_cat_number', 0 );
			$cat_html = '';
			$output   = array();

			if ( ! is_wp_error( $cats ) && $cats ) {
				foreach ( $cats as $cat ) {
					$output[] = sprintf( '<a href="%s">%s</a>', esc_url( get_category_link( $cat->term_id ) ), esc_html( $cat->cat_name ) );

					$i ++;

					if ( $i > $number || $i > ( $count - 1 ) ) {
						break;
					}

					$output[] = ', ';
				}

				$cat_html = sprintf( '<span class="meta-cat"> %s</span>', implode( '', $output ) );
			}


			$layout = $settings['layout'];
			$html[] = '<article class="blog-wrapper swiper-slide '.$layout.'">';
			$html[] = $image;
			switch($layout){
				case 'layout-3':
					$date_html   = ' / <span class="meta-date">' . esc_html( get_the_date( "M d, Y" ) ) . '</span>';
					$author_html = sprintf( '/ <span class="meta-author">%s %s</span>', esc_html('By', 'durotan'), get_the_author());
					$html[] = '<div class="post-summary">';
					$html[] = '<div class="post-meta">';
					$html[] = $cat_html;
					$html[] = $date_html;
					$html[] = $author_html;
					$html[] = '</div>';
					$html[] = '<h4 class="post-title"><a href="'.$post_url['url'].'">'.get_the_title( get_the_ID() ).'</a></h4>';
					$html[] = '</div>';
					break;
				case 'layout-2':
					$date_html   = '<span class="meta-date">' . esc_html( get_the_date( "F d, Y" ) ) . '</span>';
					$author_html = sprintf( '/ <span class="meta-author">%s %s</span>', esc_html('By', 'durotan'), get_the_author());
					$html[] = '<div class="post-summary">';
					$html[] = '<div class="post-meta">';
					$html[] = $date_html;
					$html[] = '</div>';
					$html[] = '<h4 class="post-title"><a href="'.$post_url['url'].'">'.get_the_title( get_the_ID() ).'</a></h4>';
					$html[] = \Durotan\Addons\Helper::get_content_limit( 20, '');
					$html[] = '<a class="durotan-button button-normal btn-read-more" href="' . esc_url( get_permalink() ) . '">' . esc_html__( 'Continue', 'durotan' ) .'</a>';
					$html[] = '</div>';
					break;
				case 'layout-1':
				default:
					$date_html  = sprintf( '<div class="post-date">%1s%2s%3s</div>',
										sprintf('<span class="day">%s</span>',esc_html( get_the_date( 'd' ) )),
										sprintf('<span class="month">%s</span>',esc_html( get_the_date( 'F' ) )),
										sprintf('<span class="year">%s</span>',esc_html( get_the_date( 'Y' ) ))
								);
					$author_html = sprintf( '/ <span class="meta-author">%s %s</span>', esc_html('By', 'durotan'), get_the_author());
					$html[] = '<div class="post-summary">';
					$html[] = $date_html;
					$html[] = '<div class="post-details">';
					$html[] = '<h4 class="post-title"><a href="'.$post_url['url'].'">'.get_the_title( get_the_ID() ).'</a></h4>';
					$html[] = '<div class="post-meta">';
					$html[] = $cat_html;
					$html[] = $author_html;
					$html[] = '</div>';
					$html[] = '</div>';
					$html[] = '</div>';
					break;
			}
			$html[] = '</article>';
			$index ++;
		endwhile;
		wp_reset_postdata();

		$this->add_render_attribute('wrapper', 'class', $classes);
		$this->add_render_attribute('swiper', 'class', $swiper);
		$heading = [];

		if ( $settings['title'] || $settings['description']) {
			$title 	   = $settings['title'] ? sprintf( '<h2 class="durotan-heading__title">%s</h2>', esc_html( $settings['title'] ) ) : '';
			$desc  	   = $settings['description'] ? sprintf( '<div class="durotan-heading__desc">%s</div>', wp_kses_post( $settings['description'] ) ) : '';
			$heading[] = sprintf( '<div class="durotan-heading__content">%s%s</div>',$title, $desc);
		}
		if ( $settings['button_text'] ) {
			$button_icon = $settings['show_icon'] === 'show' ? \Durotan\Addons\Helper::get_svg('chevron-right', 'durotan-icon') : '';
			$button_text = $settings['button_text'] ? sprintf('%s%s',esc_html($settings['button_text']), $button_icon) : '';
			$heading[] = sprintf( '<div class="durotan-heading__button">%s</div>',Helper::control_url( 'btn' , $settings['button_link'], $button_text, [ 'class' => 'durotan-button button-normal button-link' ] ));
		}
		$this->add_render_attribute('heading', 'class', array(
			'durotan-posts-carousel__heading',
			'align-items-'.($settings['description'] ? 'end' : 'baseline'),
			'align-'.$settings['position']
		));
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'heading' ); ?>>
				<?php echo implode('', $heading ) ?>
			</div>
			<div <?php echo $this->get_render_attribute_string( 'swiper' ); ?>>
				<div class="list-posts swiper-container" >
					<div class="list-posts__inner swiper-wrapper" >
						<?php echo implode('', $html ) ?>
					</div>
					<?php echo \Durotan\Addons\Helper::get_svg('chevron-left', 'durotan-swiper-button-prev durotan-swiper-button');?>
					<?php echo \Durotan\Addons\Helper::get_svg('chevron-right', 'durotan-swiper-button-next durotan-swiper-button');?>
					<div class="swiper-pagination"></div>
				</div>
			</div>
		</div>
		<?php
    }
}
;