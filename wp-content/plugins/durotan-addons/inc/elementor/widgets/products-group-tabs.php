<?php

namespace Durotan\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Durotan\Addons\Elementor\Helper;
use Durotan\Addons\Elementor\Products;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Icon Box widget
 */
class Products_Group_Tabs extends Widget_Base {

    /**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'durotan-product-group-tab';
	}

    /**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Durotan - Product Group Tabs', 'durotan' );
	}

    /**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-tabs';
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
			'durotan-product-shortcode'
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
        $this->section_products_settings_controls();
        $this->section_carousel_settings_controls();
    }

    /**
	 * Section Style
	 */
	protected function section_style() {
        $this->section_heading_style_controls();
		$this->section_product_style_controls();
		$this->section_carousel_style_controls();
		$this->section_button_style_controls();
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
				'default'     => __( 'Add Your Heading Text Here', 'durotan' ),
                'label_block' => true,
			]
		);

        $this->add_control(
			'button_divider',
			[
				'label' => esc_html__( 'Button', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator'   => 'before',
			]
		);

        $this->add_control(
			'button_text',
			[
				'label'       => esc_html__( 'Button Text', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'View all product', 'durotan' ),
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

    protected function section_products_settings_controls() {
		$this->start_controls_section(
			'section_products',
			[ 'label' => esc_html__( 'Products', 'durotan' ) ]
		);

        $this->add_control(
			'per_page',
			[
				'label'   => esc_html__( 'Total Products', 'durotan' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 8,
				'min'     => 2,
				'max'     => 50,
				'step'    => 1,
			]
		);

        $this->add_control(
			'source',
			[
				'label'   => esc_html__( 'Source', 'durotan' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'special_products' => esc_html__( 'Special Products', 'durotan' ),
					'product_cats'     => esc_html__( 'Product Categories', 'durotan' ),
				],
				'default' => 'special_products',
				'toggle'  => false,
				'separator' => 'before',
			]
		);

        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'icon_type',
			[
				'label' => esc_html__( 'Icon type', 'durotan' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'image' => esc_html__( 'Image', 'durotan' ),
					'icon' 	=> esc_html__( 'Icon', 'durotan' ),
					'external' 	=> esc_html__( 'External', 'durotan' ),
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
				'label' => esc_html__( 'Choose Image', 'durotan' ),
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
				'label' => esc_html__( 'External URL', 'durotan' ),
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
			'title', [
				'label'       => esc_html__( 'Title', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Title', 'durotan' ),
				'label_block' => true,
			]
		);
        $repeater->add_control(
			'tab_products',
			[
				'label'   => esc_html__( 'Products', 'durotan' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'recent_products'       => esc_html__( 'Recent', 'durotan' ),
					'featured_products'     => esc_html__( 'Featured', 'durotan' ),
					'best_selling_products' => esc_html__( 'Best Selling', 'durotan' ),
					'top_rated_products'    => esc_html__( 'Top Rated', 'durotan' ),
					'sale_products'         => esc_html__( 'On Sale', 'durotan' ),
				],
				'default' => 'recent_products',
				'toggle'  => false,
			]
		);
        $repeater->add_control(
			'tab_orderby',
			[
				'label'     => esc_html__( 'Order By', 'durotan' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''           => esc_html__( 'Default', 'durotan' ),
					'date'       => esc_html__( 'Date', 'durotan' ),
					'title'      => esc_html__( 'Title', 'durotan' ),
					'menu_order' => esc_html__( 'Menu Order', 'durotan' ),
					'rand'       => esc_html__( 'Random', 'durotan' ),
				],
				'default'   => '',
				'toggle'    => false,
				'condition' => [
					'tab_products' => [ 'recent_products', 'top_rated_products', 'sale_products', 'featured_products' ],
				],
			]
		);
        $repeater->add_control(
			'tab_order',
			[
				'label'     => esc_html__( 'Order', 'durotan' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''     => esc_html__( 'Default', 'durotan' ),
					'asc'  => esc_html__( 'Ascending', 'durotan' ),
					'desc' => esc_html__( 'Descending', 'durotan' ),
				],
				'default'   => '',
				'toggle'    => false,
				'condition' => [
					'tab_products' => [ 'recent_products', 'top_rated_products', 'sale_products', 'featured_products' ],
				],
			]
		);
		$repeater->add_control(
			'heading_tab_button',
			[
				'label' => esc_html__( 'Button', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$repeater->add_control(
			'tab_button_text',
			[
				'label'       => esc_html__( 'Button Text', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
			]
		);

		$repeater->add_control(
			'tab_button_link', [
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
		$repeater->add_control(
			'heading_tab_view_all',
			[
				'label' => esc_html__( 'View All', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$repeater->add_control(
			'tab_view_all',
			[
				'label'        => __( 'View All Button', 'durotan' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'durotan' ),
				'label_off'    => __( 'Hide', 'durotan' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);
		$repeater->add_control(
			'tab_view_all_text',
			[
				'label'       => esc_html__( 'View All Button Text', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'See All Products', 'durotan' ),
				'label_block' => true,
				'condition' => [
					'tab_view_all' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'tab_view_all_link', [
				'label'         => esc_html__( 'View All Link', 'durotan' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'durotan' ),
				'show_external' => true,
				'default'       => [
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				],
				'condition' => [
					'tab_view_all' => 'yes',
				],
			]
		);

        $this->add_control(
			'special_products_tabs',
			[
				'label'         => '',
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [
					[
						'title'        => esc_html__( 'Best Selling', 'durotan' ),
						'tab_products' => 'best_selling_products',
						'tab_button_text' => ''
					],
                    [
						'title'        => esc_html__( 'New Arrivals', 'durotan' ),
						'tab_products' => 'recent_products',
						'tab_button_text' => ''
					],
					[
						'title'        => esc_html__( 'Editor’s Pick', 'durotan' ),
						'tab_products' => 'top_rated_products',
						'tab_button_text' => ''
					]
				],
				'title_field'   => '{{{ title }}}',
				'prevent_empty' => false,
				'condition'     => [
					'source' => 'special_products',
				],
			]
		);

        $product_cats = Helper::taxonomy_list();
        $product_tags = Helper::tags_list('product_tag');
        $repeater     = new \Elementor\Repeater();

		$repeater->add_control(
			'title_tab', [
				'label'       => esc_html__( 'Title', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			]
		);

        $repeater->add_control(
			'product_cat', [
				'label'       => esc_html__( 'Category Tab', 'durotan' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $product_cats,
				'label_block' => true,
			]
		);

        $repeater->add_control(
			'product_tag', [
				'label'       => esc_html__( 'Tags Tab', 'durotan' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $product_tags,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'product_cat_icon_type',
			[
				'label' => esc_html__( 'Icon type', 'durotan' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'image' => esc_html__( 'Image', 'durotan' ),
					'icon' 	=> esc_html__( 'Icon', 'durotan' ),
					'external' 	=> esc_html__( 'External', 'durotan' ),
				],
				'default' => 'icon',
			]
		);

		$repeater->add_control(
			'product_cat_icon',
			[
				'label'   => esc_html__( 'Icons', 'durotan' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(),
				'conditions' => [
					'terms' => [
						[
							'name' => 'product_cat_icon_type',
							'value' => 'icon',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'product_cat_image',
			[
				'label' => esc_html__( 'Choose Image', 'durotan' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'product_cat_icon_type',
							'value' => 'image',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'product_cat_external_url',
			[
				'label' => esc_html__( 'External URL', 'durotan' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'product_cat_icon_type',
							'value' => 'external',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'heading_product_cat_btn',
			[
				'label' => esc_html__( 'Button', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$repeater->add_control(
			'product_cat_btn_text',
			[
				'label'       => esc_html__( 'Button Text', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
			]
		);
		$repeater->add_control(
			'product_cat_btn_link', [
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

		$repeater->add_control(
			'heading_product_cat_view_all',
			[
				'label' => esc_html__( 'View All', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$repeater->add_control(
			'product_cat_view_all',
			[
				'label'        => __( 'View All Button', 'durotan' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'durotan' ),
				'label_off'    => __( 'Hide', 'durotan' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$repeater->add_control(
			'product_cat_view_all_text',
			[
				'label'       => esc_html__( 'View All Button Text', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'See All Products', 'durotan' ),
				'label_block' => true,
				'condition' => [
					'product_cat_view_all' => 'yes',
				],
			]
		);
		$repeater->add_control(
			'product_cat_view_all_link', [
				'label'         => esc_html__( 'Custom Link', 'durotan' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'durotan' ),
				'show_external' => true,
				'default'       => [
					'url'         => '',
					'is_external' => false,
					'nofollow'    => false,
				],
				'condition' => [
					'product_cat_view_all' => 'yes',
				],
			]
		);
        $this->add_control(
			'product_cats_tabs',
			[
				'label'         => esc_html__( 'Category Tabs', 'durotan' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [],
				'prevent_empty' => false,
				'condition'     => [
					'source' => 'product_cats',
				],
				'title_field'   => '{{{ product_cat }}}',
			]
		);

		$this->add_control(
			'rating',
			[
				'label'     => __( 'Rating', 'durotan' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'durotan' ),
				'label_on'  => __( 'On', 'durotan' ),
				'default'   => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'products',
			[
				'label'   => esc_html__( 'Products', 'durotan' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'recent_products'       => esc_html__( 'Recent', 'durotan' ),
					'featured_products'     => esc_html__( 'Featured', 'durotan' ),
					'best_selling_products' => esc_html__( 'Best Selling', 'durotan' ),
					'top_rated_products'    => esc_html__( 'Top Rated', 'durotan' ),
					'sale_products'         => esc_html__( 'On Sale', 'durotan' ),
				],
				'default' => 'recent_products',
				'toggle'  => false,
				'condition' => [
					'source' => 'product_cats',
				],
			]
		);
        $this->add_control(
			'orderby',
			[
				'label'     => esc_html__( 'Order By', 'durotan' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''           => esc_html__( 'Default', 'durotan' ),
					'date'       => esc_html__( 'Date', 'durotan' ),
					'title'      => esc_html__( 'Title', 'durotan' ),
					'menu_order' => esc_html__( 'Menu Order', 'durotan' ),
					'rand'       => esc_html__( 'Random', 'durotan' ),
				],
				'default'   => '',
				'toggle'    => false,
				'condition' => [
					'products' => [ 'recent_products', 'top_rated_products', 'sale_products', 'featured_products' ],
					'source' => 'product_cats',
				],
			]
		);
		$this->add_control(
			'order',
			[
				'label'     => esc_html__( 'Order', 'durotan' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''     => esc_html__( 'Default', 'durotan' ),
					'asc'  => esc_html__( 'Ascending', 'durotan' ),
					'desc' => esc_html__( 'Descending', 'durotan' ),
				],
				'default'   => '',
				'toggle'    => false,
				'condition' => [
					'products' => [ 'recent_products', 'top_rated_products', 'sale_products', 'featured_products' ],
					'source' => 'product_cats',
				],
			]
		);

        $this->end_controls_section();
    }

    protected function section_carousel_settings_controls() {
        $this->start_controls_section(
			'section_carousel_setting',
			[ 'label' => esc_html__( 'Carousel Setting', 'durotan' ) ]
		);

        $this->add_responsive_control(
			'slidesToShow',
			[
				'label'           => esc_html__( 'Slides to show', 'durotan' ),
				'type'            => Controls_Manager::NUMBER,
				'min'             => 1,
				'max'             => 7,
				'default' 		=> 4,
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'slidesToScroll',
			[
				'label'           => esc_html__( 'Slides to scroll', 'durotan' ),
				'type'            => Controls_Manager::NUMBER,
				'min'             => 1,
				'max'             => 7,
				'default' 		=> 4,
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'slidesToRow',
			[
				'label'           => esc_html__( 'Slides to row', 'durotan' ),
				'type'            => Controls_Manager::NUMBER,
				'min'             => 1,
				'max'             => 4,
				'default' 		=> 1,
				'frontend_available' => true,
			]
		);

        $this->add_responsive_control(
			'navigation',
			[
				'label'     => esc_html__( 'Navigation', 'durotan' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'none'     => esc_html__( 'None', 'durotan' ),
					'arrows' => esc_html__( 'Arrows', 'durotan' ),
					'dots' => esc_html__( 'Dots', 'durotan' ),
					'both' => esc_html__( 'Both', 'durotan' ),
				],
				'default'   => 'both',
				'frontend_available' => true,
			]
		);

        $this->add_control(
			'infinite',
			[
				'label'     => __( 'Infinite Loop', 'durotan' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'durotan' ),
				'label_on'  => __( 'On', 'durotan' ),
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
				'default'   => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'speed',
			[
				'label'       => __( 'Speed', 'durotan' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 800,
				'min'         => 100,
				'step'        => 50,
				'description' => esc_html__( 'Slide animation speed (in ms)', 'durotan' ),
				'frontend_available' => true,
			]
		);

        $this->end_controls_section();
    }

    protected function section_heading_style_controls(){
		 // Carousel Settings
		 $this->start_controls_section(
			'section_heading_style',
			[
				'label' => esc_html__( 'Heading', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'heading_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-products-group-tabs .tabs-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$this->add_responsive_control(
			'title_position',
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
					'{{WRAPPER}} .durotan-products-group-tabs .tabs-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .durotan-products-group-tabs .tabs-title',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-products-group-tabs .tabs-title' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .durotan-products-group-tabs .tabs-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .durotan-products-group-tabs .durotan-button.button-link',
			]
		);
		$this->add_control(
			'button_color',
			[
				'label'     => esc_html__( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-products-group-tabs .durotan-button.button-link' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'tab_style_divider',
			[
				'label' => __( 'Tab', 'durotan' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'tab_position',
			[
				'label'     => __( 'Tab Position', 'durotan' ),
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
			]
		);
		$this->add_responsive_control(
			'tab_header_space_item',
			[
				'label'     => __( 'Space Item', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 150,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-products-group-tabs ul.tabs li:not(:first-child)' => 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .durotan-products-group-tabs ul.tabs li:not(:last-child)' => 'padding-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'tab_header_space_bottom',
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
					'{{WRAPPER}} .durotan-products-group-tabs .tabs-header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs(
			'style_tab_header'
		);
		$this->start_controls_tab(
			'style_tab_header_text',
			[
				'label' => __( 'Text', 'durotan' ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tab_header_typography',
				'selector' => '{{WRAPPER}} .durotan-products-group-tabs ul.tabs li a',
			]
		);
		$this->add_control(
			'tab_header_text_color',
			[
				'label'     => esc_html__( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-products-group-tabs ul.tabs li a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'tab_header_hover',
			[
				'label' => __( 'Hover', 'durotan' ),
				'type' => Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'tab_header_hover_font_weight',
			[
				'label'     => esc_html__( 'Font Weight', 'durotan' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'     => __( 'Normal', 'durotan' ),
					'bold' 	   => __( 'Bold', 'durotan' ),
				],
			]
		);
		$this->add_control(
			'tab_header_hover_color',
			[
				'label'     => esc_html__( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-products-group-tabs  ul.tabs li a' => '--text-shadow-color-active: {{VALUE}};',
					'{{WRAPPER}} .durotan-products-group-tabs  ul.tabs li a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .durotan-products-group-tabs  ul.tabs li a.active' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_tab_header_icon',
			[
				'label' => __( 'Icon', 'durotan' ),
			]
		);
		$this->add_responsive_control(
			'tab_header_icon_spacing',
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
					'{{WRAPPER}} .durotan-products-group-tabs ul.tabs li .durotan-svg-icon' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'tab_header_icon_size',
			[
				'label'     => esc_html__( 'Font Size', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-products-group-tabs ul.tabs li .durotan-svg-icon' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .durotan-products-group-tabs ul.tabs li .durotan-img-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'tab_header_icon_color',
			[
				'label'     => __( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-products-group-tabs ul.tabs li .durotan-svg-icon' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
    }

	protected function section_product_style_controls(){
		$this->start_controls_section(
		   'section_product_style',
		   [
			   'label' => esc_html__( 'Product', 'durotan' ),
			   'tab'   => Controls_Manager::TAB_STYLE,
		   ]
	   );

	   $this->add_responsive_control(
			'product_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 350,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-products-group-tabs .tabs-content ul.products li.product' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

	   $this->end_controls_section();
   }

    protected function section_carousel_style_controls(){
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
				'selector' => '{{WRAPPER}} .durotan-products-group-tabs .durotan-swiper-button',
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
					'{{WRAPPER}} .durotan-products-group-tabs .durotan-swiper-button' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .durotan-products-group-tabs .durotan-swiper-button:hover' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .durotan-products-group-tabs .durotan-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .durotan-products-group-tabs .durotan-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .durotan-products-group-tabs .swiper-pagination .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .durotan-products-group-tabs .swiper-pagination' => 'margin-left: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .durotan-products-group-tabs .swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .durotan-products-group-tabs .swiper-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .durotan-products-group-tabs .swiper-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .durotan-products-group-tabs .swiper-pagination .swiper-pagination-bullet:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
        $this->end_controls_section();
    }
	protected function section_button_style_controls(){
		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Button', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'tab_button_spacing',
			[
				'label'     => __( 'Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 150,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-products-group-tabs .durotan-tabs-button' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tab_button_typography',
				'selector' => '{{WRAPPER}} .durotan-products-group-tabs .durotan-tabs-button .durotan-tab-button',
			]
		);

		$this->add_control(
			'tab_button_divider',
			[
				'label' => '',
				'type'  => Controls_Manager::DIVIDER,
			]
		);

		$this->start_controls_tabs(
			'style_tabs_button'
		);

		$this->start_controls_tab(
			'button_style_normal',
			[
				'label' => __( 'Normal', 'durotan' ),
			]
		);
		$this->add_control(
			'tab_button_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-products-group-tabs .durotan-tabs-button .durotan-tab-button' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'tab_button_border_color',
			[
				'label'     => __( 'Border Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-products-group-tabs .durotan-tabs-button .durotan-tab-button' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'tab_button_background',
			[
				'label'     => esc_html__( 'Background Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-products-group-tabs .durotan-tabs-button .durotan-tab-button' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_style_hover',
			[
				'label' => __( 'Hover', 'durotan' ),
			]
		);
		$this->add_control(
			'tab_button_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-products-group-tabs .durotan-tabs-button .durotan-tab-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_button_hover_border_color',
			[
				'label'     => __( 'Border Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-products-group-tabs .durotan-tabs-button .durotan-tab-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_button_hover_background',
			[
				'label'     => esc_html__( 'Background Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-products-group-tabs .durotan-tabs-button .durotan-tab-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'view_all_style',
			[
				'label' => esc_html__( 'View all', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'view_all_spacing',
			[
				'label'     => __( 'Spacing', 'durotan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 150,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-products-group-tabs .durotan-tabs-button .view-all-button' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'view_all_typography',
				'selector' => '{{WRAPPER}} .durotan-products-group-tabs .durotan-tabs-button .view-all-button',
			]
		);

		$this->start_controls_tabs(
			'style_tabs_view_all'
		);

		$this->start_controls_tab(
			'view_all_style_normal',
			[
				'label' => __( 'Normal', 'durotan' ),
			]
		);
		$this->add_control(
			'view_all_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-products-group-tabs .durotan-tabs-button .view-all-button' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'view_all_border_color',
			[
				'label'     => __( 'Border Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-products-group-tabs .durotan-tabs-button .view-all-button' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'view_all_background',
			[
				'label'     => esc_html__( 'Background Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-products-group-tabs .durotan-tabs-button .view-all-button' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'view_all_style_hover',
			[
				'label' => __( 'Hover', 'durotan' ),
			]
		);
		$this->add_control(
			'view_all_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-products-group-tabs .durotan-tabs-button .view-all-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view_all_hover_border_color',
			[
				'label'     => __( 'Border Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-products-group-tabs .durotan-tabs-button .view-all-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view_all_hover_background',
			[
				'label'     => esc_html__( 'Background Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-products-group-tabs .durotan-tabs-button .view-all-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

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

		$rating = empty( $settings['rating'] ) ? 'no' : $settings['rating'];

        $classes = [
			'durotan-products-group-tabs',
			'durotan-swiper-carousel-elementor',
			'navigation-' . $nav,
			'navigation-tablet-' . $nav_tablet,
			'navigation-mobile-' . $nav_mobile,
			'rating-' . $rating,
			'textshadow-' . $settings['tab_header_hover_font_weight'],
		];

        $this->add_render_attribute( 'wrapper', 'class', $classes );

        $settings['columns'] = intval( $settings['slidesToShow'] );

        ?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php echo $this::get_products_tab( $settings ); ?>
		</div>
		<?php
    }

    protected function get_products_tab( $settings ) {
        $output      = [];
		$header_tabs = [];
		$tab_content = [];
		$nav        = $settings['navigation'];
		$nav_tablet = empty( $settings['navigation_tablet'] ) ? $nav : $settings['navigation_tablet'];
		$nav_mobile = empty( $settings['navigation_mobile'] ) ? $nav : $settings['navigation_mobile'];

        $header_tabs[] = $settings['button_text'] ? '<div class="nav-wrap align-'.esc_attr($settings['tab_position']).'"><ul class="tabs-nav tabs">' : '<ul class="tabs-nav tabs align-'.esc_attr($settings['tab_position']).'">';
		$i             = 0;

		if ( $settings['source'] == 'special_products' ) {
			$tabs = $settings['special_products_tabs'];
			if ( $tabs ) {
				foreach ( $tabs as $index => $item ) {
					$tab_btn = $class_active = '';

					if ( $i == 0 ) {
						$class_active = 'active';
					}

					$tab_atts = [
						'products'     		=> $item['tab_products'],
						'orderby'      		=> ! empty( $item['tab_orderby'] ) ? $item['tab_orderby'] : '',
						'order'        		=> ! empty( $item['tab_order'] ) ? $item['tab_order'] : '',
						'per_page'    		=> $settings['per_page'],
						'columns'      		=> $settings['columns'],
						'paginate'			=> $item['tab_view_all'],
					];

					if( $tab_atts['products'] === 'featured_products' ){
						$tab_atts['visibility'] = 'featured';
					}
					$results = Products::products_shortcode( $tab_atts );

					$icon = '';

					if ( $item['icon_type'] === 'image' ) {
						$cat_icon = sprintf( '<img class="durotan-icon durotan-img-icon" alt="%s" src="%s">', esc_attr( $item['title'] ), esc_url( $item['image']['url'] ) );
					} if ( $item['icon_type'] === 'external' ) {
						$cat_icon = '<img class="durotan-icon durotan-img-icon" src="' . $item['external_url'] . '" alt="' . $item['title'] . '" />';
					} else {
						if (  $item['icon'] && ! empty( $item['icon']['value'] ) && \Elementor\Icons_Manager::is_migration_allowed() ) {
							ob_start();
							\Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] );

							$add_class_icon = $item['icon']['library'] == 'svg' ? 'durotan-svg-icon' : '';

							$icon = '<span class="durotan-icon '.$add_class_icon.'">' . ob_get_clean() . '</span>';
						}
					}

					if ( isset( $item['title'] ) ) {
						$header_tabs[] = sprintf( '<li><a href="#" data-href="%s" class="%s">%s%s</a></li>', esc_attr( $item['tab_products'].'_'.$i ), $class_active, $icon, esc_html( $item['title'] ) );
					}
					if ( $item['tab_button_text'] || $item['tab_view_all']) {
						$button_view = $item['tab_button_text'] ?  sprintf( '%s', Helper::control_url( $index, $item['tab_button_link'], $item['tab_button_text'], [ 'class' => 'durotan-button durotan-tab-button' ] ) ) : '';
						$view_all   = $item['tab_view_all'] ? self::get_query_pagination($results, $item['tab_view_all_link'], $item['tab_view_all_text'], true) : '';
						$tab_btn 	 = sprintf('<div class="durotan-tabs-button">%s%s</div>', $view_all , $button_view);
					}

					if ( $i == 0 ) {
						$tab_content[] = sprintf(
										'<div class="tabs-panel tabs-%s tab-loaded active" data-settings="%s">',
										esc_attr( $item['tab_products'].'_'.$i ),
										esc_attr( wp_json_encode( $tab_atts ) )
									);

						$tab_content[] = Products::get_template_loop( $results['ids'], false);

						$tab_content[] = wp_kses_post( $tab_btn );

						$tab_content[] = '</div>';
					} else {
						$tab_content[] = sprintf(
							'<div class="tabs-panel tabs-%s" data-settings="%s">%s</div>',
							esc_attr( $item['tab_products'].'_'.$i ),
							esc_attr( wp_json_encode( $tab_atts ) ),
							wp_kses_post( $tab_btn )
						);
					}
					$i ++;
				}
			}else{
				$tab_content[] = sprintf(
					'<div class="tabs-panel tab-loaded active">%s</div>',
					self::get_deafult_tab($settings)
				);
			}
		}else{
			$cats = $settings['product_cats_tabs'];
			if ( $cats ) {
				foreach ( $cats as $tab ) {
					$class_active = $i == 0 ? 'active' : '';
					$term = get_term_by( 'slug', $tab['product_cat'], 'product_cat' );
					$term_tag = get_term_by( 'slug', $tab['product_tag'], 'product_tag' );
					$term_link = [];
					if ( ( ! is_wp_error( $term ) && $term ) || ( ! is_wp_error( $term_tag ) && $term_tag ) ) {
						$cat_icon = '';

						if ( $tab['product_cat_icon_type'] === 'image' ) {
							$cat_icon = sprintf( '<img class="durotan-icon durotan-img-icon" alt="%s" src="%s">', esc_attr( $tab['title_tab'] ), esc_url( $tab['product_cat_image']['url'] ) );
						} if ( $tab['product_cat_icon_type'] === 'external' ) {
							$cat_icon = '<img class="durotan-icon durotan-img-icon" src="' . $tab['product_cat_external_url'] . '" alt="' . $tab['title_tab'] . '" />';
						} else {
							if (  $tab['product_cat_icon'] && ! empty( $tab['product_cat_icon']['value'] ) && \Elementor\Icons_Manager::is_migration_allowed() ) {
								ob_start();
								\Elementor\Icons_Manager::render_icon( $tab['product_cat_icon'], [ 'aria-hidden' => 'true' ] );

								$add_class_icon = $tab['product_cat_icon']['library'] == 'svg' ? 'durotan-svg-icon' : '';

								$cat_icon = '<span class="durotan-icon '.$add_class_icon.'">' . ob_get_clean() . '</span>';
							}
						}

						$term_name = isset($term->name) ? $term->name : $term_tag->name;

						$title_tab = isset($tab['title_tab']) ? $tab['title_tab'] : esc_html( $term_name );
						$header_tabs[] = sprintf( '<li><a href="#" data-href="%s" class="%s">%s%s</a></li>', esc_attr( $tab['product_cat'].'_'.$i ), esc_attr($class_active), $cat_icon, $title_tab );

						if( isset($term_tag->term_id) ) {
							$term_link['url'] = get_term_link($term_tag->term_id,'product_tag');
						}

						if( isset($term->term_id) ) {
							$term_link['url'] = get_term_link($term->term_id,'product_cat');
						}

					}

					$tab_atts = array(
						'products'     => $settings['products'],
						'order'        => $settings['order'],
						'orderby'      => $settings['orderby'],
						'per_page'     => intval( $settings['per_page'] ),
						'paginate'	   => true,
					);

					if ( $tab['product_cat'] ) {
						$tab_atts['category'] = $tab['product_cat'];
					}

					if ( $tab['product_tag'] ) {
						$tab_atts['tag'] = $tab['product_tag'];
					}

					if( $tab_atts['products'] == 'featured_products' ){
						$tab_atts['visibility'] = 'featured';
					}
					$results = Products::products_shortcode( $tab_atts );
					$tab_btn = '';
					if ( $tab['product_cat_btn_text'] || $tab['product_cat_view_all']) {
						$button_view 	= $tab['product_cat_btn_text'] ?  sprintf( '%s', Helper::control_url( $i, $tab['product_cat_btn_link'], $tab['product_cat_btn_text'], [ 'class' => 'durotan-button durotan-tab-button' ] ) ) : '';

						$view_all_link  = !empty($tab['product_cat_view_all_link']['url']) ? $tab['product_cat_view_all_link'] : $term_link;

						$view_all    	= $tab['product_cat_view_all'] ? self::get_query_pagination($results, $view_all_link, $tab['product_cat_view_all_text'], true) : '';
						$tab_btn 	 	= sprintf('<div class="durotan-tabs-button">%s%s</div>', $view_all , $button_view);
					}
					if ( $i == 0 ) {
						$tab_content[] = sprintf(
										'<div class="tabs-panel tabs-%s tab-loaded active" data-settings="%s">',
										esc_attr( $tab['product_cat'].'_'.$i ),
										esc_attr( wp_json_encode( $tab_atts ) )
									);

						$tab_content[] = Products::get_template_loop( $results['ids'] , false);
						$tab_content[] = wp_kses_post( $tab_btn );
						$tab_content[] = '</div>';
					} else {
						$tab_content[] = sprintf(
							'<div class="tabs-panel tabs-%s" data-settings="%s">%s</div>',
							esc_attr( $tab['product_cat'].'_'.$i ),
							esc_attr( wp_json_encode( $tab_atts ) ),
							wp_kses_post( $tab_btn ),
						);
					}
					$i ++;
				}
			}else{
				$tab_content[] = sprintf(
					'<div class="tabs-panel tab-loaded active">%s</div>',
					self::get_deafult_tab($settings)
				);
			}
		}
		$header_tabs[] = '</ul>';

		$title = $button_view = '';

		if ( $settings['title'] ) {
			$title = sprintf( '<h2 class="tabs-title">%s</h2>', esc_html( $settings['title'] ) );
		}

		if ( $settings['button_text'] ) {
			$button_text = $settings['button_text'] ? sprintf('<span class="tabs-button__text">%s</span>%s',esc_html($settings['button_text']), \Durotan\Addons\Helper::get_svg('chevron-right', 'durotan-icon')) : '';
			$header_tabs[] = sprintf( '<div class="tabs-button">%s</div>',Helper::control_url( 'btn' , $settings['button_link'], $button_text, [ 'class' => 'durotan-button button-normal button-link' ] ));
			$header_tabs[] = '</div>';
		}

		$output[] = sprintf('<div class="tabs-header">%s%s</div>',
						wp_kses_post($title),
						implode( ' ', $header_tabs ),
					);
		$output[] = sprintf( '<div class="tabs-content">%s</div>',implode( ' ', $tab_content ));

		return implode( '', $output );
    }

	public static function get_query_pagination( $results, $url, $text = 'See All Products', $link_custom = false){
		$view_all_html = '';
		$attr = [];

		if ( isset( $url['url'] ) && $url['url'] ) {
			$attr['href'] = $url['url'];
		}

		if ( isset( $url['is_external'] ) && $url['is_external'] ) {
			$attr['target'] = '_blank';
		}

		if ( isset( $url['nofollow'] ) && $url['nofollow'] ) {
			$attr['rel'] = 'nofollow';
		}

		$attributes = [];

		foreach ( $attr as $name => $v ) {
			$attributes[] = $name . '="' . esc_attr( $v ) . '"';
		}

		if ( $results['current_page'] < $results['total_pages'] || $link_custom ) {
			$view_all_html = sprintf(
				'<a %s class="durotan-button view-all-button" data-page="%d">
					<span class="button-text">%s (%d)</span>
					<div class="durotan-gooey-loading">
						<div class="dorutan-gooey">
							<div class="dots">
								<span></span>
								<span></span>
								<span></span>
							</div>
						</div>
					</div>
				</a>',
				implode( ' ', $attributes ),
				esc_attr( $results['current_page'] + 1 ),
				esc_html($text),
				$results['total']
			);
		}

		return $view_all_html;
	}

	public static function get_deafult_tab( $settings ){
		$tab_atts = array(
			'products'     => 'recent_products',
			'order'        => $settings['order'],
			'orderby'      => $settings['orderby'],
			'per_page'     => intval( $settings['per_page'] ),
			'paginate'	   => false,
		);

		return Products::get_content( $tab_atts);
	}
}