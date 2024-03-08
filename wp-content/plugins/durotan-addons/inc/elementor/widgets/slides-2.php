<?php

namespace Durotan\Addons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;
use Elementor\Controls_Stack ;
use Durotan\Addons\Elementor\Helper;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Slides 2 widget
 */
class Slides_2 extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'durotan-slides2';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Durotan - Slides 2', 'durotan' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-slider';
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
			'magnific',
			'pagepiling',
			'durotan-frontend'
		];
	}

	public function get_style_depends() {
		return [
			'magnific',
			'pagepiling'
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

	// Tab Content
	protected function section_content() {
		$this->section_content_slides();
		$this->section_content_option();
	}

	protected function section_content_slides() {
		$this->start_controls_section(
			'section_slides',
			[
				'label' => esc_html__( 'Slides', 'durotan' ),
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->start_controls_tabs( 'slides_repeater' );

		$repeater->start_controls_tab( 'background', [ 'label' => esc_html__( 'Background', 'durotan' ) ] );

		$repeater->add_responsive_control(
			'banner_background_img',
			[
				'label'    => __( 'Background Image', 'durotan' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://via.placeholder.com/1920X600/cccccc?text=1920x600',
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-sliders__bg' => 'background-image: url("{{URL}}");',
				],
			]
		);

		$repeater->add_responsive_control(
			'background_size',
			[
				'label'     => esc_html__( 'Background Size', 'durotan' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => [
					'cover'   => esc_html__( 'Cover', 'durotan' ),
					'contain' => esc_html__( 'Contain', 'durotan' ),
					'auto'    => esc_html__( 'Auto', 'durotan' ),
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-sliders__bg' => 'background-size: {{VALUE}}',
				],
				'condition' => [
					'banner_background_img[url]!' => '',
				],
			]
		);

		$repeater->add_responsive_control(
			'background_position',
			[
				'label'     => esc_html__( 'Background Position', 'durotan' ),
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
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-sliders__bg' => 'background-position: {{VALUE}};',
				],
				'condition' => [
					'banner_background_img[url]!' => '',
				],

			]
		);

		$repeater->add_responsive_control(
			'background_position_xy',
			[
				'label'              => esc_html__( 'Custom Background Position', 'durotan' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'allowed_dimensions' => [ 'top', 'left' ],
				'size_units'         => [ 'px', '%' ],
				'default'            => [ ],
				'selectors'          => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-sliders__bg' => 'background-position: {{LEFT}}{{UNIT}} {{TOP}}{{UNIT}};',
				],
				'condition' => [
					'background_position' => [ 'initial' ],
					'banner_background_img[url]!' => '',
				],
				'required' => true,
			]
		);

		$repeater->add_control(
			'background_overlay',
			[
				'label'      => esc_html__( 'Background Overlay', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-sliders__bg::before' => 'background-color: {{VALUE}}',
				]
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'text_content', [ 'label' => esc_html__( 'Content', 'durotan' ) ] );

		$repeater->add_control(
			'tag',
			[
				'label'       => esc_html__( 'Tag', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'before_title',
			[
				'label'       => esc_html__( 'Before Title', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Slide before title', 'durotan' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Slide Heading', 'durotan' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'after_title',
			[
				'label'       => esc_html__( 'After Title', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'description',
			[
				'label'   => esc_html__( 'Description', 'durotan' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'I am slide content. Click edit button to change this text. ', 'durotan' ),
			]
		);

		$repeater->add_control(
			'heading_price',
			[
				'label' => esc_html__( 'Price', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'text_price',
			[
				'label'   => esc_html__( 'Text', 'durotan' ),
				'type'    => Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'price',
			[
				'label'   => esc_html__( 'Price', 'durotan' ),
				'type'    => Controls_Manager::TEXT
			]
		);

		$repeater->add_control(
			'price_sale',
			[
				'label'   => esc_html__( 'Price Sale', 'durotan' ),
				'type'    => Controls_Manager::TEXT
			]
		);

		$repeater->add_control(
			'custom_image_swatches_heading',
			[
				'label' => esc_html__( 'Image Swatches', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'custom_image_swatches',
			[
				'label'       => esc_html__( 'Enable', 'durotan' ),
				'type'        => Controls_Manager::SWITCHER,
			]
		);

		for( $i = 1; $i < 4; $i++ ) {
			$repeater->add_control(
				'heading_image_swatches_'.$i,
				[
					'label' => esc_html__( 'Image Swatches '.$i, 'durotan' ),
					'type'  => Controls_Manager::HEADING,
					'conditions' => [
						'terms' => [
							[
								'name'  => 'custom_image_swatches',
								'value' => 'yes',
							],
						],
					],
				]
			);

			$repeater->add_responsive_control(
				'image_swatches_'.$i.'_banner_background_img',
				[
					'label'    => __( 'Background Image', 'durotan' ),
					'type' => Controls_Manager::MEDIA,
					'selectors' => [
						'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-sliders__bg-item--'.$i.':not(.swiper-lazy)' => 'background-image: url("{{URL}}");',
					],
					'conditions' => [
						'terms' => [
							[
								'name'  => 'custom_image_swatches',
								'value' => 'yes',
							],
						],
					],
				]
			);

			$repeater->add_responsive_control(
				'image_swatches_'.$i.'_banner_background_thumbnail',
				[
					'label'    => __( 'Background Thumbnail', 'durotan' ),
					'type' => Controls_Manager::MEDIA,
					'conditions' => [
						'terms' => [
							[
								'name'  => 'custom_image_swatches',
								'value' => 'yes',
							],
						],
					],
				]
			);

			$repeater->add_control(
				'image_swatches_'.$i.'_price',
				[
					'label'   => esc_html__( 'Price', 'durotan' ),
					'type'    => Controls_Manager::TEXT,
					'conditions' => [
						'terms' => [
							[
								'name'  => 'custom_image_swatches',
								'value' => 'yes',
							],
						],
					],
				]
			);

			$repeater->add_control(
				'image_swatches_'.$i.'_price_sale',
				[
					'label'   => esc_html__( 'Price Sale', 'durotan' ),
					'type'    => Controls_Manager::TEXT,
					'conditions' => [
						'terms' => [
							[
								'name'  => 'custom_image_swatches',
								'value' => 'yes',
							],
						],
					],
				]
			);
		}

		$repeater->add_control(
			'heading_button_1',
			[
				'label' => esc_html__( 'Button 1', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label'   => esc_html__( 'Text', 'durotan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Button 1', 'durotan' ),
			]
		);

		$repeater->add_control(
			'link',
			[
				'label'       => esc_html__( 'Link', 'durotan' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'durotan' ),
			]
		);

		$repeater->add_control(
			'heading_button_2',
			[
				'label' => esc_html__( 'Button 2', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'button_text_2',
			[
				'label'   => esc_html__( 'Text', 'durotan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Button 2', 'durotan' ),
			]
		);

		$repeater->add_control(
			'link_2',
			[
				'label'       => esc_html__( 'Link', 'durotan' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'durotan' ),
			]
		);

		$repeater->add_control(
			'heading_button_video',
			[
				'label' => esc_html__( 'Button Video', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'button_video_icon',
			[
				'label'   => esc_html__( 'Icons', 'durotan' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(),
			]
		);

		$repeater->add_control(
			'button_video_text',
			[
				'label'   => esc_html__( 'Text', 'durotan' ),
				'type'    => Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'button_video_link',
			[
				'label'       => esc_html__( 'Link', 'durotan' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'durotan' ),
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'style', [ 'label' => esc_html__( 'Style', 'durotan' ) ] );

		$repeater->add_control(
			'custom_style',
			[
				'label'       => esc_html__( 'Custom', 'durotan' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Set custom style that will only affect this specific slide.', 'durotan' ),
			]
		);

		$repeater->add_control(
			'heading_style_content',
			[
				'label' => esc_html__( 'Content', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_responsive_control(
			'slide_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'slides_inner_width_cus',
			[
				'label' => __( 'Inner Width', 'durotan' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''               			=> esc_attr__( 'Default', 'durotan' ),
					'container'               	=> esc_attr__( 'Standard', 'durotan' ),
					'durotan-container'       	=> esc_attr__( 'Large', 'durotan' ),
					'durotan-container-narrow'	=> esc_attr__( 'Fluid', 'durotan' ),
					'durotan-container-fluid' 	=> esc_attr__( 'Full Width', 'durotan' ),
					'durotan-container-custom' 	=> esc_attr__( 'Custom', 'durotan' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_responsive_control(
			'slides_inner_width_cus_custom',
			[
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 5000,
					],
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'slides_inner_width_cus',
							'value' => 'durotan-container-custom',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-container-custom' => 'width: 100%; max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$repeater->add_control(
			'slides_content_width_cus',
			[
				'label' => __( 'Content Width', 'durotan' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''          => esc_attr__( 'Default', 'durotan' ),
					'custom' 	=> esc_attr__( 'Custom', 'durotan' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_responsive_control(
			'slides_content_width_cus_custom',
			[
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 5000,
					],
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'slides_content_width_cus',
							'value' => 'custom',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide__content' => 'margin: 0; max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$repeater->add_responsive_control(
			'horizontal_position',
			[
				'label'                => esc_html__( 'Horizontal Position', 'durotan' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => [
					'left'   => [
						'title' => esc_html__( 'Left', 'durotan' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'durotan' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'durotan' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'              => '',
				'selectors'            => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-sliders__inner .durotan-slide__content' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'left'   => 'margin-right: auto',
					'center' => 'margin: 0 auto',
					'right'  => 'margin-left: auto',
				],
				'conditions'           => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_responsive_control(
			'vertical_position',
			[
				'label'                => esc_html__( 'Vertical Position', 'durotan' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => [
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
				'selectors'            => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-sliders__inner' => 'align-items: {{VALUE}}',
				],
				'selectors_dictionary' => [
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'conditions'           => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_responsive_control(
			'text_align',
			[
				'label'       => esc_html__( 'Text Align', 'durotan' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'left'   => [
						'title' => esc_html__( 'Left', 'durotan' ),
						'icon'  => 'eicon-text-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'durotan' ),
						'icon'  => 'eicon-text-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'durotan' ),
						'icon'  => 'eicon-text-right',
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-sliders__inner' => 'text-align: {{VALUE}}',
				],
				'conditions'  => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'heading_style_tag',
			[
				'label' => esc_html__( 'Tag', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_responsive_control(
			'tag_custom_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => -100,
						'max' => 250,
					],
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-sliders__inner .durotan-slide__tag' => 'left: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$repeater->add_control(
			'title_heading_name',
			[
				'label' => esc_html__( 'Title', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_custom_typography',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-sliders__inner .durotan-slide__heading',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'heading_custom_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-sliders__inner .durotan-slide__heading' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				]
			]
		);

		$repeater->add_responsive_control(
			'heading_custom_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-sliders__inner .durotan-slide__heading' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$repeater->add_control(
			'desc_heading_name',
			[
				'label' => esc_html__( 'Description', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'desc_custom_typography',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-sliders__inner .durotan-slide__description',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'content_custom_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-sliders__inner .durotan-slide__description' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				]
			]
		);

		$repeater->add_responsive_control(
			'desc_cus_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-sliders__inner .durotan-slide__description' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$repeater->add_control(
			'heading_price_cus',
			[
				'label'     => esc_html__( 'Price', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'heading_price_cus_color',
			[
				'label'     => esc_html__( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} ins.durotan-slide__price-number' => 'color: {{VALUE}}',

				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'heading_sale_cus_color',
			[
				'label'     => esc_html__( 'Sale Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} del.durotan-slide__price-number' => 'color: {{VALUE}}',

				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'heading_button_cus',
			[
				'label'     => esc_html__( 'Button', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'btn_1_heading_name',
			[
				'label' => esc_html__( 'Button 1', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'button_1_cus_style',
			[
				'label' => __( 'Style', 'durotan' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'style_1',
				'options' => [
					'style_1'     => __( 'Style 1', 'durotan' ),
					'line' 		  => __( 'Style 2', 'durotan' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_1_custom_typography',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text--1',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_1_cus_style',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'btn_1_custom_bg_color',
			[
				'label'      => esc_html__( 'Background Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text--1:before' => 'background-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_1_cus_style',
							'value' => 'style_1',
						],
					],
				]
			]
		);

		$repeater->add_control(
			'btn_1_custom_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text--1' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_1_cus_style',
							'value' => 'style_1',
						],
					],
				]
			]
		);

		$repeater->add_group_control(
			Group_Control_Border::get_type(), [
				'name'     => 'btn_1_custom_border',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text--1',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_1_cus_style',
							'value' => 'style_1',
						],
					],
				]
			]
		);

		$repeater->add_responsive_control(
			'btn_1_custom_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel  {{CURRENT_ITEM}} .durotan-slide-button .button-text--1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_1_cus_style',
							'value' => 'style_1',
						],
					],
				]
			]
		);

		$repeater->add_responsive_control(
			'btn_1_custom_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_1_cus_style',
							'value' => 'style_1',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel  {{CURRENT_ITEM}} .durotan-slide-button .button-text--1' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_line_1_custom_typography',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text-1--line',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_1_cus_style',
							'value' => 'line',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'btn_line_1_custom_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text-1--line' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_1_cus_style',
							'value' => 'line',
						],
					],
				]
			]
		);

		$repeater->add_responsive_control(
			'btn_line_1_custom_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel  {{CURRENT_ITEM}} .durotan-slide-button .button-text-1--line' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_1_cus_style',
							'value' => 'line',
						],
					],
				]
			]
		);

		$repeater->add_responsive_control(
			'btn_line_1_custom_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_1_cus_style',
							'value' => 'line',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel  {{CURRENT_ITEM}} .durotan-slide-button .button-text-1--line' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$repeater->add_control(
			'btn_1_heading_name_hover',
			[
				'label' => esc_html__( 'Button 1 Hover', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'btn_1_custom_color_hover',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text--1:hover' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_1_cus_style',
							'value' => 'style_1',
						],
					],
				]
			]
		);

		$repeater->add_control(
			'btn_1_custom_bg_color_hover',
			[
				'label'      => esc_html__( 'Background Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text--1:after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text--1:hover:after' => 'background-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_1_cus_style',
							'value' => 'style_1',
						],
					],
				]
			]
		);

		$repeater->add_control(
			'btn_1_custom_border_color_hover',
			[
				'label'      => esc_html__( 'Border Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text--1:hover' => 'border-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_1_cus_style',
							'value' => 'style_1',
						],
					],
				]
			]
		);

		$repeater->add_control(
			'btn_line_1_custom_color_hover',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text-1--line:hover' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_1_cus_style',
							'value' => 'line',
						],
					],
				]
			]
		);

		$repeater->add_responsive_control(
			'btn_line_1_custom_border_height_hover',
			[
				'label'     => esc_html__( 'Border Height', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text-1--line::after' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_1_cus_style',
							'value' => 'line',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'btn_line_1_custom_border_color_hover',
			[
				'label'      => esc_html__( 'Border Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text-1--line::after' => 'border-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_1_cus_style',
							'value' => 'line',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'btn_2_heading_name',
			[
				'label' => esc_html__( 'Button 2', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'button_2_cus_style',
			[
				'label' => __( 'Style', 'durotan' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'style_1',
				'options' => [
					'style_1'     => __( 'Style 1', 'durotan' ),
					'line' 		  => __( 'Style 2', 'durotan' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_2_custom_typography',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text--2',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_2_cus_style',
							'value' => 'style_1',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'btn_2_custom_bg_color',
			[
				'label'      => esc_html__( 'Background Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text--2:before' => 'background-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_2_cus_style',
							'value' => 'style_1',
						],
					],
				]
			]
		);

		$repeater->add_control(
			'btn_2_custom_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text--2' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_2_cus_style',
							'value' => 'style_1',
						],
					],
				]
			]
		);

		$repeater->add_group_control(
			Group_Control_Border::get_type(), [
				'name'     => 'btn_2_custom_border',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text--2',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_2_cus_style',
							'value' => 'style_1',
						],
					],
				]
			]
		);

		$repeater->add_responsive_control(
			'btn_2_custom_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel  {{CURRENT_ITEM}} .durotan-slide-button .button-text--2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_2_cus_style',
							'value' => 'style_1',
						],
					],
				]
			]
		);

		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_line_2_custom_typography',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text-2--line',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_2_cus_style',
							'value' => 'line',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'btn_line_2_custom_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text-2--line' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_2_cus_style',
							'value' => 'line',
						],
					],
				]
			]
		);

		$repeater->add_responsive_control(
			'btn_line_2_custom_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel  {{CURRENT_ITEM}} .durotan-slide-button .button-text-2--line' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_2_cus_style',
							'value' => 'line',
						],
					],
				]
			]
		);

		$repeater->add_control(
			'btn_2_heading_name_hover',
			[
				'label' => esc_html__( 'Button 2 Hover', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'btn_2_custom_color_hover',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text--2:hover' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_2_cus_style',
							'value' => 'style_1',
						],
					],
				]
			]
		);

		$repeater->add_control(
			'btn_2_custom_bg_color_hover',
			[
				'label'      => esc_html__( 'Background Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text--2:after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text--2:hover:after' => 'background-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_2_cus_style',
							'value' => 'style_1',
						],
					],
				]
			]
		);

		$repeater->add_control(
			'btn_2_custom_border_color_hover',
			[
				'label'      => esc_html__( 'Border Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text--2:hover' => 'border-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_2_cus_style',
							'value' => 'style_1',
						],
					],
				]
			]
		);

		$repeater->add_control(
			'btn_line_2_custom_color_hover',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text-2--line:hover' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_2_cus_style',
							'value' => 'line',
						],
					],
				]
			]
		);

		$repeater->add_responsive_control(
			'btn_line_2_custom_border_height_hover',
			[
				'label'     => esc_html__( 'Border Height', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text-2--line::after' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_2_cus_style',
							'value' => 'line',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'btn_line_2_custom_border_color_hover',
			[
				'label'      => esc_html__( 'Border Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel {{CURRENT_ITEM}} .durotan-slide-button .button-text-2--line::after' => 'border-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'button_2_cus_style',
							'value' => 'line',
						],
					],
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'slides',
			[
				'label'      => esc_html__( 'Slides', 'durotan' ),
				'type'       => Controls_Manager::REPEATER,
				'show_label' => true,
				'fields'     => $repeater->get_controls(),
				'default'    => array(),
			]
		);

		$this->add_responsive_control(
			'slides_height',
			[
				'label'      => esc_html__( 'Height', 'durotan' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 100,
						'max' => 5000,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'vh', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .slides2-wrapper' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .durotan-slider-carousel .durotan-sliders__bg' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .durotan-slider-carousel .durotan-sliders-bg-wrapper' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.durotan-slider-carousel--parallax-yes' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'lazyload',
			[
				'label'     => esc_html__( 'Show Lazyload', 'durotan' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'durotan' ),
				'label_on'  => __( 'On', 'durotan' ),
				'return_value' => 'yes',
				'default'   => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'parallax',
			[
				'label'     => esc_html__( 'Parallax', 'durotan' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'durotan' ),
				'label_on'  => __( 'On', 'durotan' ),
				'return_value' => 'yes',
				'default'   => '',
				'frontend_available' => true,
				'prefix_class' => 'durotan-slider-carousel--parallax-',
			]
		);

		$this->end_controls_section();
	}

	protected function section_content_option() {
		$this->start_controls_section(
			'section_slider_options',
			[
				'label' => esc_html__( 'Slider Options', 'durotan' ),
				'type'  => Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'direction',
			[
				'label'   => esc_html__( 'Direction', 'durotan' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'horizontal'   	 => esc_html__( 'Horizontal', 'durotan' ),
					'vertical' 	     => esc_html__( 'Vertical', 'durotan' ),
				],
				'default' => 'horizontal',
				'toggle'  => false,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'allowscrolling',
			[
				'label'     => esc_html__( 'Allow Scrolling', 'durotan' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'durotan' ),
				'label_on'  => __( 'On', 'durotan' ),
				'return_value' => 'true',
				'default'   => 'true',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'effect',
			[
				'label'   => esc_html__( 'Effect', 'durotan' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'swing'   	 => esc_html__( 'Swing', 'durotan' ),
					'linear' 	 => esc_html__( 'Linear', 'durotan' ),
				],
				'default' => 'swing',
				'toggle'  => false,
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
					'arrows_dots' 		=> esc_html__( 'Arrows & Dots', 'durotan' ),
					'fraction' 			=> esc_html__( 'Fraction', 'durotan' ),
					'fraction_arrow' 	=> esc_html__( 'Fraction & Arrow', 'durotan' ),
					'all'			 	=> esc_html__( 'All', 'durotan' ),
				],
				'default' => 'arrows',
			]
		);

		$this->end_controls_section();

	}

	// Tab Style
	protected function section_style() {
		$this->section_style_content();
		$this->section_style_carousel();
	}

	protected function section_style_content() {
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'slides_margin',
			[
				'label'      => esc_html__( 'Margin', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'allowed_dimensions' => [ 'top', 'bottom' ],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-sliders__inner' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'slides_inner_width',
			[
				'label' => __( 'Inner Width', 'durotan' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''               			=> esc_attr__( 'Default', 'durotan' ),
					'container'               	=> esc_attr__( 'Standard', 'durotan' ),
					'durotan-container'       	=> esc_attr__( 'Large', 'durotan' ),
					'durotan-container-narrow'	=> esc_attr__( 'Fluid', 'durotan' ),
					'durotan-container-fluid' 	=> esc_attr__( 'Full Width', 'durotan' ),
					'durotan-container-custom' 	=> esc_attr__( 'Custom', 'durotan' ),
				],
			]
		);

		$this->add_responsive_control(
			'slides_inner_width_custom',
			[
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 5000,
					],
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'slides_inner_width',
							'value' => 'durotan-container-custom',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-container-custom' => 'width: 100%; max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'slides_content_width',
			[
				'label' => __( 'Content Width', 'durotan' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''          => esc_attr__( 'Default', 'durotan' ),
					'custom' 	=> esc_attr__( 'Custom', 'durotan' ),
				],
			]
		);

		$this->add_responsive_control(
			'slides_content_width_custom',
			[
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 5000,
					],
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'slides_content_width',
							'value' => 'custom',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__content' => 'margin: 0; max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control (
			'slides_horizontal_position',
			[
				'label'        => esc_html__( 'Horizontal Position', 'durotan' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'default'      => '',
				'options'      => [
					'left'   => [
						'title' => esc_html__( 'Left', 'durotan' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'durotan' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'durotan' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'durotan--h-position-',
			]
		);

		$this->add_control(
			'slides_vertical_position',
			[
				'label'        => esc_html__( 'Vertical Position', 'durotan' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'default'      => '',
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
				'prefix_class' => 'durotan--v-position-',
			]
		);

		$this->add_control(
			'slides_text_align',
			[
				'label'       => esc_html__( 'Text Align', 'durotan' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'left'   => [
						'title' => esc_html__( 'Left', 'durotan' ),
						'icon'  => 'eicon-text-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'durotan' ),
						'icon'  => 'eicon-text-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'durotan' ),
						'icon'  => 'eicon-text-right',
					],
				],
				'default'     => '',
				'selectors'   => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-sliders__inner' => 'text-align: {{VALUE}}',
				],
				'prefix_class' => 'durotan-sliders__inner--',
			]
		);

		$this->add_control(
			'content_bg',
			[
				'label'     => esc_html__( 'Background Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__content' => 'background-color: {{VALUE}}',

				],
			]
		);

		$this->section_style_tag();

		$this->section_style_before_after_title();

		$this->section_style_title();

		$this->section_style_desc();

		$this->section_style_price();

		$this->section_style_image_swatches();

		$this->section_style_button();

		$this->section_style_button_video();

		$this->end_controls_section();

	}

	protected function section_style_tag() {
		$this->add_control(
			'heading_tag',
			[
				'label'     => esc_html__( 'Tag', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tag_typography',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel .durotan-slide__tag',
			]
		);

		$this->add_control(
			'tag_color',
			[
				'label'     => esc_html__( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__tag' => 'color: {{VALUE}}',

				],
			]
		);
	}

	protected function section_style_before_after_title() {

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
				'selector' => '{{WRAPPER}} .durotan-slider-carousel .durotan-slide__before-heading',
			]
		);

		$this->add_control(
			'before_heading_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__before-heading' => 'color: {{VALUE}}',

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
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__before-heading' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'after_heading_title',
			[
				'label'     => esc_html__( 'After Title', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'after_heading_typography',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel .durotan-slide__after-heading',
			]
		);

		$this->add_control(
			'after_heading_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__after-heading' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_responsive_control(
			'after_heading_spacing',
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
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__after-heading' => 'margin-bottom: {{SIZE}}{{UNIT}}',
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
				'selector' => '{{WRAPPER}} .durotan-slider-carousel .durotan-slide__heading',
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__heading' => 'color: {{VALUE}}',

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
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__heading' => 'margin-bottom: {{SIZE}}{{UNIT}}',
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

		$this->add_control(
			'description_layout',
			[
				'label' => __( 'Layout', 'durotan' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'layout_1',
				'options' => [
					'layout_1'     => __( 'Layout 1', 'durotan' ),
					'layout_2' 	   => __( 'Layout 2', 'durotan' ),
				],
				'prefix_class' => 'description-layout__',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel .durotan-slide__description',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__description' => 'color: {{VALUE}}',

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
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__description' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
	}

	protected function section_style_price() {

		$this->add_control(
			'heading_price',
			[
				'label'     => esc_html__( 'Price', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'all_price_spacing_top',
			[
				'label'     => esc_html__( 'Spacing Top', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__price' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'all_price_spacing_bottom',
			[
				'label'     => esc_html__( 'Spacing Bottom', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__price' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->start_controls_tabs( 'style_price_type' );

		$this->start_controls_tab( 'style_text', [ 'label' => esc_html__( 'Text', 'durotan' ) ] );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'text_price_typography',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel .durotan-slide__price-text',
			]
		);

		$this->add_control(
			'text_price_color',
			[
				'label'     => esc_html__( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__price-text' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_responsive_control(
			'text_price_spacing',
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
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__price-text' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'style_price', [ 'label' => esc_html__( 'Price', 'durotan' ) ] );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel ins.durotan-slide__price-number',
			]
		);

		$this->add_control(
			'price_color',
			[
				'label'     => esc_html__( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel ins.durotan-slide__price-number' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_responsive_control(
			'price_spacing',
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
					'{{WRAPPER}} .durotan-slider-carousel ins.durotan-slide__price-number' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_sale',
			[
				'label'     => esc_html__( 'Sale', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sale_typography',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel del.durotan-slide__price-number',
			]
		);

		$this->add_control(
			'sale_color',
			[
				'label'     => esc_html__( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel del.durotan-slide__price-number' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_responsive_control(
			'sale_spacing',
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
					'{{WRAPPER}} .durotan-slider-carousel del.durotan-slide__price-number' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

	}

	protected function section_style_image_swatches() {
		$this->add_control(
			'heading_image_swatches',
			[
				'label'     => esc_html__( 'Image Swatches', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'heading_image_swatches_text',
			[
				'label'     => esc_html__( 'Text', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'image_swatches_text_typography',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel .durotan-slide__image-swatches-text',
			]
		);

		$this->add_control(
			'image_swatches_text_color',
			[
				'label'     => esc_html__( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__image-swatches-text' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_responsive_control(
			'image_swatches_text_spacing',
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
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__image-swatches-text' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_image_swatches_image',
			[
				'label'     => esc_html__( 'Image', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
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

		$this->add_control(
			'image_swatches_image_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__image-swatches-link:hover img' => 'border-color: {{VALUE}} !important',
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__image-swatches-link.active img' => 'border-color: {{VALUE}} !important',

				],
			]
		);

		$this->add_responsive_control(
			'image_swatches_image_spacing',
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
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__image-swatches-link' => 'margin-right: {{SIZE}}{{UNIT}}',
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

		$this->add_responsive_control(
			'button_spacing_top',
			[
				'label'     => esc_html__( 'Spacing Top', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->start_controls_tabs( 'style_button_type' );

		$this->start_controls_tab(
			'style_button_1',
			[
				'label' => esc_html__( 'Button 1', 'durotan' ),
			]
		);

		$this->add_control(
			'button_1_style',
			[
				'label' => __( 'Style', 'durotan' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'style_1',
				'options' => [
					'style_1'  => __( 'Style 1', 'durotan' ),
					'line' 	   => __( 'Style 2', 'durotan' ),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_1_typography',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text--1',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'operator' => '!==',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_1_bg_color',
			[
				'label'      => esc_html__( 'Background Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text--1:before' => 'background-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'operator' => '!==',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_1_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text--1' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'operator' => '!==',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'     => 'btn_1_border',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text--1',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'operator' => '!==',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'btn_1_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text--1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'operator' => '!==',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'btn_1_spacing',
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
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text--1' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'operator' => '!==',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_1_heading_hover',
			[
				'label' => esc_html__( 'Hover', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'operator' => '!==',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_1_color_hover',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text--1:hover' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'operator' => '!==',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_1_bg_color_hover',
			[
				'label'      => esc_html__( 'Background Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text--1:after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text--1:hover:after' => 'background-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'operator' => '!==',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_1_border_color_hover',
			[
				'label'      => esc_html__( 'Border Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text--1:hover' => 'border-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'operator' => '!==',
							'value' => 'line',
						],
					],
				],
			]
		);

		// Button 1 line

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_line_1_typography',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text-1--line',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_line_1_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text-1--line' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'btn_line_1_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text-1--line' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'btn_line_1_spacing',
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
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text-1--line' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_line_1_heading_hover',
			[
				'label' => esc_html__( 'Hover', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_line_1_color_hover',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text-1--line:hover' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'btn_line_1_border_height_hover',
			[
				'label'     => esc_html__( 'Border Height', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text-1--line::after' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_line_1_border_color_hover',
			[
				'label'      => esc_html__( 'Border Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text-1--line::after' => 'border-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_1_style',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_button_2',
			[
				'label' => esc_html__( 'Button 2', 'durotan' ),
			]
		);

		$this->add_control(
			'button_2_style',
			[
				'label' => __( 'Style', 'durotan' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'style_1',
				'options' => [
					'style_1'  => __( 'Style 1', 'durotan' ),
					'line' 	   => __( 'Style 2', 'durotan' ),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_2_typography',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text--2',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'operator' => '!==',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_2_bg_color',
			[
				'label'      => esc_html__( 'Background Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text--2:before' => 'background-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'operator' => '!==',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_2_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text--2' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'operator' => '!==',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'     => 'btn_2_border',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text--2',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'operator' => '!==',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'btn_2_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text--2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'operator' => '!==',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_2_heading_hover',
			[
				'label' => esc_html__( 'Hover', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'operator' => '!==',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_2_color_hover',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text--2:hover' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'operator' => '!==',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_2_bg_color_hover',
			[
				'label'      => esc_html__( 'Background Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text--2:after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text--2:hover:after' => 'background-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'operator' => '!==',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_2_border_color_hover',
			[
				'label'      => esc_html__( 'Border Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text--2:hover' => 'border-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'operator' => '!==',
							'value' => 'line',
						],
					],
				],
			]
		);

		// Button 2 line

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_line_2_typography',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text-2--line',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_line_2_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text-2--line' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'btn_line_2_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text-2--line' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_line_2_heading_hover',
			[
				'label' => esc_html__( 'Hover', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_line_2_color_hover',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text-2--line:hover' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'btn_line_2_border_height_hover',
			[
				'label'     => esc_html__( 'Border Height', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text-2--line::after' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->add_control(
			'btn_line_2_border_color_hover',
			[
				'label'      => esc_html__( 'Border Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide-button .button-text-2--line::after' => 'border-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'button_2_style',
							'value' => 'line',
						],
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
	}

	protected function section_style_button_video() {

		$this->add_control(
			'heading_button_video',
			[
				'label'     => esc_html__( 'Button Video', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->start_controls_tabs( 'style_button_video' );

		$this->start_controls_tab( 'style_button_video_icon', [ 'label' => esc_html__( 'Icon', 'durotan' ) ] );

		$this->add_control(
			'btn_video_icon_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__play-video .durotan-slide__play-video-button-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'btn_video_icon_width',
			[
				'label'      => esc_html__( 'Width', 'durotan' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 250,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__play-video .durotan-slide__play-video-button-icon' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_video_icon_height',
			[
				'label'      => esc_html__( 'Height', 'durotan' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 250,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__play-video .durotan-slide__play-video-button-icon' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'     => 'btn_video_icon_border',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel .durotan-slide__play-video .durotan-slide__play-video-button-icon',
			]
		);

		$this->add_responsive_control(
			'btn_video_icon_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__play-video .durotan-slide__play-video-button-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'btn_video_icon_size',
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
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__play-video .durotan-slide__play-video-button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_video_icon_spacing',
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
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__play-video .durotan-slide__play-video-button-icon' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_video_icon_hover',
			[
				'label'     => esc_html__( 'Hover', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'btn_video_icon_color_hover',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__play-video .durotan-slide__play-video-button:hover .durotan-slide__play-video-button-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_video_icon_border_color_hover',
			[
				'label'      => esc_html__( 'Border Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__play-video .durotan-slide__play-video-button:hover .durotan-slide__play-video-button-icon' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'style_button_video_text', [ 'label' => esc_html__( 'Text', 'durotan' ) ] );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_video_text_typography',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel .durotan-slide__play-video .durotan-slide__play-video-text',
			]
		);

		$this->add_control(
			'btn_video_text_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__play-video .durotan-slide__play-video-text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_video_text_hover',
			[
				'label'     => esc_html__( 'Hover', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'btn_video_text_color_hover',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slide__play-video .durotan-slide__play-video-button:hover .durotan-slide__play-video-text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
	}

	protected function section_style_carousel() {
		// Arrows
		$this->start_controls_section(
			'section_style_arrows',
			[
				'label' => esc_html__( 'Slider Options', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Arrows
		$this->add_control(
			'arrow_style_heading',
			[
				'label' => esc_html__( 'Arrows', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'arrow_style',
			[
				'label' => __( 'Style', 'durotan' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'text',
				'options' => [
					'text'  => __( 'Text', 'durotan' ),
					'icon_1' => __( 'Icon 1', 'durotan' ),
					'icon_2' => __( 'Icon 2', 'durotan' ),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'arrow_text_typography',
				'selector' => '{{WRAPPER}} .durotan-slider-carousel .durotan-slides2-button__text .durotan-slides2-button',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'arrow_style',
							'value' => 'text',
						],
					],
				],
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
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slides2-button__icon .durotan-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'arrow_style',
							'operator' => '!==',
							'value' => 'text',
						]
					],
				],
			]
		);

		$this->add_control(
			'arrow_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slides2-button__text .durotan-slides2-button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slides2-button__text .durotan-slide__arrow-text--prev:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slides2-button__icon .durotan-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'arrow_color_hover',
			[
				'label'      => esc_html__( 'Color Hover', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slides2-button__text .durotan-slides2-button:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slides2-button__icon .durotan-icon:hover' => 'color: {{VALUE}}',
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
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slides2-button__text--prev' => 'margin-right: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slides2-button__icon-1--prev' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slides2-button__icon-1--next' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .durotan-slider-carousel .durotan-slides2-button__icon-2--prev' => 'margin-right: {{SIZE}}{{UNIT}};',
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
			'sliders_dots_align',
			[
				'label'       => esc_html__( 'Align', 'durotan' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'left'   => [
						'title' => esc_html__( 'Left', 'durotan' ),
						'icon'  => 'eicon-text-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'durotan' ),
						'icon'  => 'eicon-text-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'durotan' ),
						'icon'  => 'eicon-text-right',
					],
				],
				'prefix_class' => 'sliders-dot-position__',
			]
		);

		$this->add_responsive_control(
			'sliders_dots_size',
			[
				'label'     => __( 'Size', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 150,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel #pp-nav li .durotan-slide__pagination-bullet' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sliders_dots_size_dot',
			[
				'label'     => __( 'Dots Size', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 150,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel #pp-nav li .durotan-slide__pagination-bullet:before' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .durotan-slider-carousel #pp-nav li' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'sliders_dots_vertical_spacing',
			[
				'label'     => esc_html__( 'Vertical Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 500,
						'min' => -500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel #pp-nav li' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'sliders_dots_bottom_spacing',
			[
				'label'     => esc_html__( 'Bottom Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 500,
						'min' => -500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel #pp-nav' => 'bottom: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .durotan-slider-carousel #pp-nav li .durotan-slide__pagination-bullet:before' => 'background-color: {{VALUE}};',
				],
			]
		);


		$this->add_control(
			'sliders_dots_ac_bgcolor',
			[
				'label'     => esc_html__( 'Color Hover', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-slider-carousel #pp-nav li .durotan-slide__pagination-bullet.active:before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .durotan-slider-carousel #pp-nav li .durotan-slide__pagination-bullet.active' 		=> 'border-color: {{VALUE}};',
					'{{WRAPPER}} .durotan-slider-carousel #pp-nav li .durotan-slide__pagination-bullet:hover:before'  => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .durotan-slider-carousel #pp-nav li .durotan-slide__pagination-bullet:hover' 		=> 'border-color: {{VALUE}};',
				],
			]
		);

		// Fraction
		$this->add_control(
			'fraction_style_heading',
			[
				'label' => esc_html__( 'Fraction', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'fraction_style',
			[
				'label' => __( 'Style', 'durotan' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  	  => __( 'Style 1', 'durotan' ),
					'style_2' => __( 'Style 2', 'durotan' ),
				],
			]
		);

		$this->start_controls_tabs( 'style_fraction' );

		$this->start_controls_tab(
			'style_fraction_text',
			[
				'label' => esc_html__( 'Text', 'durotan' ),
				'conditions' => [
					'terms' => [
						[
							'name'  => 'fraction_style',
							'value' => '',
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'fraction_text',
				'selector' => '{{WRAPPER}} .durotan-slide__fraction--style-1 .durotan-slide__fraction-current',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'fraction_style',
							'value' => '',
						],
					],
				],
			]
		);

		$this->add_control(
			'fraction_text_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slide__fraction--style-1 .durotan-slide__fraction-current' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'fraction_style',
							'value' => '',
						],
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_fraction_line',
			[
				'label' => esc_html__( 'Line', 'durotan' ),
				'conditions' => [
					'terms' => [
						[
							'name'  => 'fraction_style',
							'value' => '',
						],
					],
				],
			]
		);

		$this->add_control(
			'fraction_line_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slide__fraction--style-1 .durotan-slide__fraction-line' => 'background-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'fraction_style',
							'value' => '',
						],
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_fraction_total',
			[
				'label' => esc_html__( 'Total', 'durotan' ),
				'conditions' => [
					'terms' => [
						[
							'name'  => 'fraction_style',
							'value' => '',
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'fraction_total',
				'selector' => '{{WRAPPER}} .durotan-slide__fraction--style-1 .durotan-slide__fraction-total',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'fraction_style',
							'value' => '',
						],
					],
				],
			]
		);

		$this->add_control(
			'fraction_total_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slide__fraction--style-1 .durotan-slide__fraction-total' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'fraction_style',
							'value' => '',
						],
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_fraction_text_1',
			[
				'label' => esc_html__( 'Text', 'durotan' ),
				'conditions' => [
					'terms' => [
						[
							'name'  => 'fraction_style',
							'value' => 'style_2',
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'fraction_text_1',
				'selector' => '{{WRAPPER}} .durotan-slide__fraction--style-2 .durotan-slide__fraction-current',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'fraction_style',
							'value' => 'style_2',
						],
					],
				],
			]
		);

		$this->add_control(
			'fraction_text_1_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slide__fraction--style-2 .durotan-slide__fraction-current' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'fraction_style',
							'value' => 'style_2',
						],
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_fraction_line_1',
			[
				'label' => esc_html__( 'Line', 'durotan' ),
				'conditions' => [
					'terms' => [
						[
							'name'  => 'fraction_style',
							'value' => 'style_2',
						],
					],
				],
			]
		);

		$this->add_control(
			'fraction_line_1_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slide__fraction--style-2 .durotan-slide__fraction-line' => 'background-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'fraction_style',
							'value' => 'style_2',
						],
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_fraction_total_1',
			[
				'label' => esc_html__( 'Total', 'durotan' ),
				'conditions' => [
					'terms' => [
						[
							'name'  => 'fraction_style',
							'value' => 'style_2',
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'fraction_total_1',
				'selector' => '{{WRAPPER}} .durotan-slide__fraction--style-2 .durotan-slide__fraction-total',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'fraction_style',
							'value' => 'style_2',
						],
					],
				],
			]
		);

		$this->add_control(
			'fraction_total_1_color',
			[
				'label'      => esc_html__( 'Color', 'durotan' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .durotan-slide__fraction--style-2 .durotan-slide__fraction-total' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'fraction_style',
							'value' => 'style_2',
						],
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'fraction_line_spacing',
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
					'{{WRAPPER}} .durotan-slide__fraction--style-1 .durotan-slide__fraction-line' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .durotan-slide__fraction--style-2 .durotan-slide__fraction-line' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
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

		if ( empty( $settings['slides'] ) ) {
			return;
		}

		$nav        = $settings['navigation'] ? $settings['navigation'] : 'none';
		$nav_tablet = empty( $settings['navigation_tablet'] ) ? $nav : $settings['navigation_tablet'];
		$nav_mobile = empty( $settings['navigation_mobile'] ) ? $nav : $settings['navigation_mobile'];

		$prallax = '';

		if ( $settings['parallax'] ) {
			$prallax = 'durotan-slider--parallax';
		}

		$classes = [
			'durotan-slider-carousel',
			'durotan-slides2-carousel-elementor',
			'durotan-slides2-slider-elementor',
			'slides2-container',
			'navigation-' . $nav,
			'navigation-tablet-' . $nav_tablet,
			'navigation-mobile-' . $nav_mobile,
			$prallax,
		];

		$slides_inner_width = $settings['slides_inner_width'] == '' ? 'durotan-container' : $settings['slides_inner_width'];
		$slides_inner_width_inner = $settings['slides_inner_width'] == '' ? 'container' : $settings['slides_inner_width'];

		$slides      = [];
		$slide_count = 0;
		$slides_dots = [];

		foreach ( $settings['slides'] as $slide ) {
			$slides_content_inner_width = $slide['slides_inner_width_cus'] == '' ? $slides_inner_width_inner : $slide['slides_inner_width_cus'];

			$slide_html       = '';

			if( $slide['tag'] ) {
				$slide_html .= '<div class="durotan-slide__tag '.$slides_inner_width.'">' . $slide['tag'] . '</div>';
			}

			$slide_html .= '<div class="durotan-slide__content">';

			if ( $slide['before_title'] ) {
				$slide_html .= '<div class="durotan-slide__before-heading">' . $slide['before_title'] . '</div>';
			}

			if ( $slide['title'] ) {
				$slide_html .= '<div class="durotan-slide__heading">' . $slide['title'] . '</div>';
			}

			if ( $slide['after_title'] ) {
				$slide_html .= '<div class="durotan-slide__after-heading">' . $slide['after_title'] . '</div>';
			}

			if ( $slide['description'] ) {
				$slide_html .= '<div class="durotan-slide__description container">' . $slide['description'] . '</div>';
			}

			if ( $slide['custom_image_swatches'] ) {
				$price_html = '';
				$classes_active = '';
				for( $i = 1; $i < 4; $i++ ) {
					if ( $slide['image_swatches_'.$i.'_banner_background_img']['id'] ) {
						if( $slide['image_swatches_'.$i.'_price'] ) {
							if ( $i == 1 ) {
								$classes_active = 'active';
							} else {
								$classes_active = '';
							}

							$price_html .= '<div class="durotan-slide__price durotan-slide__price--'.$i.' '.$classes_active.'">';

							if ( $slide['image_swatches_'.$i.'_price_sale'] ) {
								$price_html .= '<div class="durotan-slide__price-sale">';
							}
							$price_html .= $slide['image_swatches_'.$i.'_price'] ? '<ins class="durotan-slide__price-number">' . $slide['image_swatches_'.$i.'_price'] . '</ins>' : '';
							$price_html .= $slide['image_swatches_'.$i.'_price_sale'] ? '<del class="durotan-slide__price-number">' . $slide['image_swatches_'.$i.'_price_sale'] . '</del>' : '';
							if ( $slide['image_swatches_'.$i.'_price_sale'] ) {
								$price_html .= '</div>';
							}

							$price_html .= '</div>';

						}
					}
				}

				$slide_html .= '<div class="durotan-slide-price-wrapper">' . $price_html . '</div>';

				$slide_html .= '<div class="durotan-slide__image-swatches-text">' . esc_html__( 'Image Swatches', 'durotan' ) . '</div>';

				$slide_html .= 	'<div class="durotan-slide__image-swatches">';

				for( $n = 1; $n < 4; $n++ ) {

					if ( $n == 1 ) {
						$classes_active = 'active';
					} else {
						$classes_active = '';
					}

					if ( $slide['image_swatches_'.$n.'_banner_background_img']['id'] ) {
						if ( $slide['image_swatches_'.$n.'_banner_background_thumbnail']['id'] ) {
							$settings['image'] = $slide['image_swatches_'.$n.'_banner_background_thumbnail'];
							$slide_html .= '<a class="durotan-slide__image-swatches-link '.$classes_active.'" href="#" >';
							$slide_html .= Group_Control_Image_Size::get_attachment_image_html( $settings );
							$slide_html .= '</a>';
						}
					}
				}

				$slide_html .= 	'</div>';
			} else {

				if ( $slide['price'] || $slide['text_price'] ) {
					$price_html = $slide['text_price'] ? '<span class="durotan-slide__price-text">' . $slide['text_price'] . '</span>' : '';
					if ( $slide['price_sale'] ) {
						$price_html .= '<div class="durotan-slide__price-sale">';
					}
					$price_html .= $slide['price'] ? '<ins class="durotan-slide__price-number">' . $slide['price'] . '</ins>' : '';
					$price_html .= $slide['price_sale'] ? '<del class="durotan-slide__price-number">' . $slide['price_sale'] . '</del>' : '';
					if ( $slide['price_sale'] ) {
						$price_html .= '</div>';
					}

					$slide_html .= '<div class="durotan-slide__price">' . $price_html . '</div>';
				}
			}

			// Button
			if ( $settings['button_1_style'] === 'style_1' ) {
				$class_button_1 = 'button-text button-text--1';
				if ( $slide['custom_style'] === 'yes' ) {
					if ( $slide['button_1_cus_style'] ) {
						if ( $slide['button_1_cus_style'] === 'style_1' ) {
							$class_button_1 = 'button-text button-text--1';
						} else {
							$class_button_1 = 'button-text-1--line button-text--line';
						}
					}
				}
			} else {
				$class_button_1 = 'button-text-1--line button-text--line';
				if ( $slide['custom_style'] === 'yes' ) {
					if ( $slide['button_1_cus_style'] === 'style_1' ) {
						$class_button_1 = 'button-text button-text--1';
					} else {
						$class_button_1 = 'button-text-1--line button-text--line';
					}
				}
			}

			if ( $settings['button_2_style'] === 'style_1' ) {
				$class_button_2 = 'button-text button-text--2';
				if ( $slide['custom_style'] === 'yes' ) {
					if ( $slide['button_2_cus_style'] ) {
						if ( $slide['button_2_cus_style'] === 'style_1' ) {
							$class_button_2 = 'button-text button-text--2';
						} else {
							$class_button_2 = 'button-text-2--line button-text--line';
						}
					}
				}
			} else {
				$class_button_2 = 'button-text-2--line button-text--line';
				if ( $slide['custom_style'] === 'yes' ) {
					if ( $slide['button_2_cus_style'] === 'style_1' ) {
						$class_button_2 = 'button-text button-text--2';
					} else {
						$class_button_2 = 'button-text-2--line button-text--line';
					}
				}
			}

			$button_text = $slide['button_text'] ? sprintf('<span class="button-text-1 %s">%s</span>', $class_button_1, $slide['button_text'] ) : '';
			$button_text_2 = $slide['button_text_2'] ? sprintf('<span class="button-text-2 %s">%s</span>', $class_button_2, $slide['button_text_2'] ) : '';

			$key_btn = 'btn_' . $slide_count;
			$key_btn_2 = 'btn2_' . $slide_count;

			$button_text = $slide['link']['url'] ? Helper::control_url( $key_btn, $slide['link'], $slide['button_text'], ['class' => 'button-text-1 button-link '.$class_button_1] ) : $button_text;
			$button_text_2 = $slide['link_2']['url'] ? Helper::control_url( $key_btn_2, $slide['link_2'], $slide['button_text_2'], ['class' => 'button-text-2 button-link '.$class_button_2] ) : $button_text_2;

			$slide_html .= '<div class="durotan-slide-button">';

			if ( $slide['button_text'] ) {
				$slide_html .= $button_text;
			}

			if ( $slide['button_text_2'] ) {
				$slide_html .= $button_text_2;
			}

			if ( $slide['button_video_icon']['value'] || $slide['button_video_text'] ) {

				$icon_video = '';
				if (  $slide['button_video_icon'] && ! empty( $slide['button_video_icon']['value'] ) && \Elementor\Icons_Manager::is_migration_allowed() ) {
					if ( is_wp_error( $slide['button_video_icon'] ) ) {
						return;
					}
					ob_start();
					\Elementor\Icons_Manager::render_icon( $slide['button_video_icon'], [ 'aria-hidden' => 'true' ] );

					$add_class_icon = $slide['button_video_icon']['library'] == 'svg' ? 'durotan-svg-icon' : '';

					$icon_video = '<span class="durotan-slide__play-video-button-icon durotan-icon '.$add_class_icon.'">' . ob_get_clean() . '</span>';
				}

				$slide_html .= sprintf( '<div class="durotan-slide__play-video"><a class="durotan-slide__play-video-button" href="%s">%s<span class="durotan-slide__play-video-text">%s</span></a></div>', $slide['button_video_link']['url'], $icon_video, $slide['button_video_text'] );
			}

			$slide_html .= '</div>';

			$slide_html .= '</div>';

			$slide_html = '<div class="durotan-sliders__inner ' . $slides_content_inner_width . '">' . $slide_html . '</div>';

			$data_lazy_url = $data_lazy_class = $data_lazy_loading = '';

			if ($settings['lazyload'] ) {

				$data_lazy_url = 'data-background="'.$slide['banner_background_img']['url'].'"';
				$data_lazy_loading =  '	<div class="slides2-lazy-preloader"></div>';
				$data_lazy_class = 'slides2-lazy';

			}

			if ( $slide['custom_image_swatches'] ) {
				$bg_image = '<div class="durotan-sliders-bg-wrapper">';
				for ( $j = 1; $j < 4; $j++ ) {

					if( $j == 1 ) {
						$classes_active = 'active';
					} else {
						$classes_active = '';
					}

					if ( $slide['image_swatches_'.$j.'_banner_background_img']['id'] ) {
						if ($settings['lazyload'] ) {

							$data_lazy_url = 'data-background="'.$slide['image_swatches_'.$j.'_banner_background_img']['url'].'"';
							$data_lazy_loading =  '	<div class="swiper-lazy-preloader"></div>';
							$data_lazy_class = 'swiper-lazy';

						}
						$bg_image .= '<div  '. $data_lazy_url .' class="durotan-sliders__bg durotan-sliders__bg--item durotan-sliders__bg-item--'.$j.' '.$data_lazy_class.' '.$classes_active.'">'. $data_lazy_loading .'</div>';
					}
				}

			} else {
				$bg_image = '<div '. $data_lazy_url .' class="durotan-sliders__bg '.$data_lazy_class.'">'. $data_lazy_loading .'</div>';
			}

			$slides[]   = '<div class="elementor-repeater-item-' . $slide['_id'] . ' durotan-slider-item slides2-slide section pp-scrollable">' . $bg_image . '' . $slide_html .'</div>';

			$slides_dots[] = '<li data-tooltip=""><a class="durotan-slide__pagination-bullet"></a></li>';

			$slide_count ++;
		}

		if( $settings['arrow_style'] === 'icon_1' ) {

			$class_arrow = 'durotan-slides2-button__icon durotan-slide__arrow--icon-1 ' . $slides_inner_width;

			$output_arrow = \Durotan\Addons\Helper::get_svg( 'chevron-left', 'durotan-slides2-button-prev durotan-slides2-button durotan-slide__arrow-icon durotan-slide__arrow-icon--prev durotan-slides2-button__icon-1--prev durotan-icon' );
			$output_arrow .= \Durotan\Addons\Helper::get_svg( 'chevron-right', 'durotan-slides2-button-next durotan-slides2-button durotan-slide__arrow-icon durotan-slide__arrow-icon--next durotan-slides2-button__icon-1--next durotan-icon' );
		} elseif( $settings['arrow_style'] === 'icon_2' ) {

			$class_arrow = 'durotan-slides2-button__icon durotan-slide__arrow--icon-2';

			$output_arrow = \Durotan\Addons\Helper::get_svg( 'arrow-left', 'durotan-slides2-button-prev durotan-slides2-button durotan-slide__arrow-icon durotan-slide__arrow-icon--prev durotan-slides2-button__icon-2--prev durotan-icon' );
			$output_arrow .= \Durotan\Addons\Helper::get_svg( 'arrow-right', 'durotan-slides2-button-next durotan-slides2-button durotan-slide__arrow-icon durotan-slide__arrow-icon--next durotan-slides2-button__icon-2--next durotan-icon' );
		} else {

			$class_arrow = 'durotan-slides2-button__text';

			$output_arrow = '<span class="durotan-slides2-button-prev durotan-slides2-button durotan-slide__arrow-text durotan-slide__arrow-text--prev durotan-slides2-button__text--prev">' . esc_html__( 'Prev', 'durotan' ). '</span>';
			$output_arrow .= '<span class="durotan-slides2-button-next durotan-slides2-button durotan-slide__arrow-text durotan-slide__arrow-text--next durotan-slides2-button__text--next">' . esc_html__( 'Next', 'durotan' ). '</span>';
		}

		if ( $slide_count > 1 ) {

			if ( $settings['navigation'] ) {
				$output_pagination = '';

				if ( $settings['navigation'] === 'dots' || $settings['navigation'] === 'all' || $settings['navigation'] === 'arrows_dots' ) {
					$output_pagination .= '<div id="pp-nav" class="durotan-slide__pagination ' . $slides_inner_width . '"><ul>' . implode( '', $slides_dots ) . '</ul></div>';
				}

				if ( $settings['navigation'] === 'fraction' || $settings['navigation'] === 'fraction_arrow' || $settings['navigation'] === 'all' ) {
					$fraction_style = '';

					if( $settings['fraction_style'] === 'style_2' ) {
						$fraction_style .= ' durotan-slide__fraction--style-2';
					} else {
						$fraction_style .= ' durotan-slide__fraction--style-1';
					}
					$output_pagination .= '<div class="durotan-slide__fraction ' . $slides_inner_width . ' ' . $fraction_style . '"><span class="durotan-slide__fraction-current">1</span><span class="durotan-slide__fraction-line"></span><span class="durotan-slide__fraction-total"></span></div>';
				}

				if ( $settings['navigation'] === 'arrows' || $settings['navigation'] === 'fraction_arrow' || $settings['navigation'] === 'all' || $settings['navigation'] === 'arrows_dots' ) {
					$output_pagination .= sprintf( '<div class="durotan-slide__arrow %s %s">%s</div>', $slides_inner_width, $class_arrow, $output_arrow );
				}


			} else {
				$output_pagination = '';
			}

		} else {
			$output_pagination = '';
		}

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		echo sprintf(
			'<div %s>
				<div class="durotan-slider-items slides2-wrapper">
					%s
				</div>
				%s
			</div>',
			$this->get_render_attribute_string( 'wrapper' ),
			implode( '', $slides ),
			$output_pagination
		);
	}
}