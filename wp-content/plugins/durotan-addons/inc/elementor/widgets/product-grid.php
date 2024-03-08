<?php

namespace Durotan\Addons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Durotan\Addons\Elementor\Helper;
use Durotan\Addons\Elementor\Products;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Icon Box widget
 */
class Product_Grid extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'durotan-product-grid';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Durotan - Product Grid', 'durotan' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-grid';
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

	// Tab Content
	protected function section_content() {
        $this->section_heading_settings_controls();
		$this->section_products_settings_controls();
	}

	// Tab Style
	protected function section_style() {
        $this->section_heading_style_controls();
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
				'default'     => esc_html__( 'See all product', 'durotan' ),
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
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'columns',
			[
				'label'   => esc_html__( 'Columns', 'durotan' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 2,
				'max'     => 7,
				'step'    => 1,
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
				'condition' => [
					'products'            => [ 'recent_products', 'top_rated_products', 'sale_products', 'featured_products' ],
				],
				'frontend_available' => true,
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
				'condition' => [
					'products'            => [ 'recent_products', 'top_rated_products', 'sale_products', 'featured_products' ],
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'category',
			[
				'label'       => esc_html__( 'Products Category', 'durotan' ),
				'placeholder' => esc_html__( 'Click here and start typing...', 'durotan' ),
				'type'        => 'durotan_autocomplete',
				'default'     => '',
				'label_block' => true,
				'multiple'    => true,
				'source'      => 'product_cat',
				'sortable'    => true,
				'separator' => 'before',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'product_tags',
			[
				'label'       => esc_html__( 'Products Tags', 'durotan' ),
				'placeholder' => esc_html__( 'Click here and start typing...', 'durotan' ),
				'type'        => 'durotan_autocomplete',
				'default'     => '',
				'label_block' => true,
				'multiple'    => true,
				'source'      => 'product_tag',
				'sortable'    => true,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'product_brands',
			[
				'label'       => esc_html__( 'Products Brands', 'durotan' ),
				'placeholder' => esc_html__( 'Click here and start typing...', 'durotan' ),
				'type'        => 'durotan_autocomplete',
				'default'     => '',
				'label_block' => true,
				'multiple'    => true,
				'source'      => 'product_brand',
				'sortable'    => true,
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
                    '{{WRAPPER}} .durotan-product-grid .tabs-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .durotan-product-grid .durotan-product-grid__title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .durotan-product-grid .durotan-product-grid__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Color', 'durotan' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .durotan-product-grid .durotan-product-grid__title' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .durotan-product-grid .durotan-product-grid__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .durotan-product-grid .heading-link',
            ]
        );
        $this->add_control(
            'button_color',
            [
                'label'     => esc_html__( 'Color', 'durotan' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .durotan-product-grid .heading-link' => 'color: {{VALUE}};',
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

		$classes = [
			'durotan-product-grid woocommerce'
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		$attr = [
			'products' 			=> $settings['products'],
			'orderby'  			=> $settings['orderby'],
			'order'    			=> $settings['order'],
			'product_brands'    => $settings['product_brands'],
			'limit'    			=> $settings['per_page'],
			'columns'    		=> $settings['columns'],
			'paginate'			=> true,
		];

		if ( $settings['category'] ) {
			$attr['category'] = $settings['category'];
		}

		if ( $settings['product_tags'] ) {
			$attr['tag'] = $settings['product_tags'];
		}

        if( $attr['products'] === 'featured_products' ){
            $attr['visibility'] = 'featured';
        }
		wc_setup_loop(
			array(
				'columns'      => $attr['columns']
			)
		);
        $results = Products::products_shortcode( $attr );

        $title = $button_link = '';
		if ( $settings['button_text'] ) {
			$button_link = sprintf( '%s',Helper::control_url( 'btn' , $settings['button_link'], esc_html($settings['button_text']), [ 'class' => 'heading-link' ] ));
		}
        if ( $settings['title'] || $settings['button_text']) {
			$title = sprintf( '<h2 class="durotan-product-grid__title">%s%s</h2>', esc_html( $settings['title'] ), $button_link);
		}
        $products = Products::get_template_loop( $results['ids'], false);
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <?php printf( '%s<div class="products-content">%s</div>', $title, $products); ?>
		</div>
		<?php
	}

}