<?php

namespace Durotan\Addons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Product Category widget
 */
class Product_Category extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'durotan-product-category';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Durotan - Product Category', 'durotan' );
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

		// Brands Settings
		$this->start_controls_section(
			'section_blogs',
			[ 'label' => esc_html__( 'Content', 'durotan' ) ]
		);

        $this->add_control(
			'source',
			[
				'label'   => esc_html__( 'Source', 'durotan' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'durotan' ),
					'custom'  => esc_html__( 'Custom', 'durotan' ),
				],
				'default' => 'default',
				'toggle'  => false,
				'separator' => 'before',
			]
		);

        $this->add_control(
			'product_cat',
			[
				'label'       => esc_html__( 'Category', 'durotan' ),
				'placeholder' => esc_html__( 'Click here and start typing...', 'durotan' ),
				'type'        => 'durotan_autocomplete',
				'default'     => '',
				'label_block' => true,
				'multiple'    => true,
				'source'      => 'product_cat',
				'sortable'    => true,
                'condition' => [
					'source' => 'custom',
				],
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'      => esc_html__( 'Order By', 'durotan' ),
				'type'       => Controls_Manager::SELECT,
				'options'    => [
					''           => esc_html__( 'Default', 'durotan' ),
					'date'       => esc_html__( 'Date', 'durotan' ),
					'title'      => esc_html__( 'Title', 'durotan' ),
					'count'      => esc_html__( 'Count', 'durotan' ),
					'menu_order' => esc_html__( 'Menu Order', 'durotan' ),
				],
				'default'    => '',
                'condition' => [
					'source' => 'default',
				],
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
                'condition' => [
					'source' => 'default',
				],
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

	/**
	 * Section Style
	 */
	protected function section_style() {

		$this->start_controls_section(
			'section_content_styles',
			[
				'label' => __( 'Style', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_responsive_control(
			'content_padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-product-category ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'spacing_item',
			[
				'label'      => __( 'Space Item', 'durotan' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-product-category ul li:not(:first-child)' => 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .durotan-product-category ul li:not(:last-child)' => 'padding-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .durotan-product-category ul li a',
			]
		);

        $this->add_control(
			'color',
			[
				'label'     => esc_html__( 'Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-product-category ul li a' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'hover_color',
			[
				'label'     => esc_html__( 'Hover Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .durotan-product-category ul li a:hover' => 'color: {{VALUE}};',
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
			'durotan-product-category',
		];

		$this->add_render_attribute('wrapper', 'class', $classes );

        $output[] = '<ul class="category-list">';
        if ( $settings['product_cat'] ) {
            $cats = explode(',', $settings['product_cat']);
            $i             = 0;
            foreach ( $cats as $cat ) {
				$term = get_term_by( 'slug', $cat, 'product_cat' );

                if ( is_wp_error( $term ) || !$term ) {
					continue;
				}
                $thumbnail_id = absint( get_term_meta( $term->term_id, 'thumbnail_id', true ) );
                $add_class = $thumbnail_html = '';
                if ( $thumbnail_id ) {
					$thumbnail_html = sprintf( '%s', $this->render_thumbnail($thumbnail_id, $settings));
					$add_class = 'has-thumbnail';
				}
                $output[] = sprintf(
					'<li class="cat-item %s">
						<a class="cat-name" href="%s">%s%s</a>
					</li>',
					esc_attr( $add_class ),
					esc_url( get_term_link( $term->term_id, 'product_cat' ) ),
                    $thumbnail_html,
					esc_html($term->name),
				);
            }
        }else{
            $args_cat = array(
				'taxonomy' => 'product_cat',
				'orderby'  => $settings['orderby'],
				'order'    => $settings['order']
			);

            $terms = get_terms($args_cat);
            foreach ( $terms as $term ) {
                $thumbnail_id = absint( get_term_meta( $term->term_id, 'thumbnail_id', true ) );
                $add_class = $thumbnail_html = '';
                if ( $thumbnail_id ) {
					$thumbnail_html = sprintf( '%s', $this->render_thumbnail($thumbnail_id, $settings));
					$add_class = 'has-thumbnail';
				}
                $output[] = sprintf(
					'<li class="cat-item %s">
                    <a class="cat-name" href="%s">%s%s</a>
					</li>',
					esc_attr( $add_class ),
					esc_url( get_term_link( $term->term_id, 'product_cat' ) ),
                    $thumbnail_html,
					esc_html($term->name),
				);
            }
        }
        $output[] = '</ul>';
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php echo implode( '', $output ); ?>
		</div>
		<?php
	}

    // Render thumbnail svg
    protected function render_thumbnail($thumbnail_id,$settings) {
        $mime = get_post_mime_type( $thumbnail_id );
        $image = '';
        if ( 'image/svg+xml' === $mime ) {
            $thumbnail = [
                'library' => 'svg',
                'value'   => [
                    'url' => wp_get_attachment_image_src( $thumbnail_id )[0],
                    'id'  => $thumbnail_id
                ]
            ];
            $icon = '';
            ob_start();
            \Elementor\Icons_Manager::render_icon( $thumbnail, [ 'aria-hidden' => 'true' ] );
            $add_class_icon = $thumbnail['library'] == 'svg' ? 'durotan-svg-icon' : '';
            $image = '<span class="durotan-icon '.$add_class_icon.'">' . ob_get_clean() . '</span>';
        }else{
            $settings['image']['url'] = wp_get_attachment_image_src( $thumbnail_id );
            $settings['image']['id']  = $thumbnail_id;
            $image = $content = sprintf('<span class="durotan-img">%s</span>', Group_Control_Image_Size::get_attachment_image_html( $settings ));
        }
        return $image;
    }
}
