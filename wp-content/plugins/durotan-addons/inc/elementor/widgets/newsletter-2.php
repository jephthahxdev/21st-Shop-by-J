<?php

namespace Durotan\Addons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Stack;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Newsletter 2 widget
 */
class Newsletter_2 extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'durotan-newsletter-2';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Durotan - Newsletter 2', 'durotan' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-mailchimp';
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
			'form',
			[
				'label'   => esc_html__( 'Mailchimp Form', 'durotan' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_contact_form_2(),
			]
		);

		$this->add_control(
			'before_title',
			[
				'label'       => esc_html__( 'Before Title', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Before title', 'durotan' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'durotan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Heading', 'durotan' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'description',
			[
				'label'   => esc_html__( 'Description', 'durotan' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'I am box content. Click edit button to change this text. ', 'durotan' ),
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
			'heading_background',
			[
				'label' => esc_html__( 'Background', 'durotan' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'background_img',
			[
				'label'    => __( 'Image', 'durotan' ),
				'type' => Controls_Manager::MEDIA,
				'selectors' => [
					'{{WRAPPER}} .durotan-newsletter-2' => 'background-image: url("{{URL}}");',
				],
			]
		);

		$this->add_responsive_control(
			'background_size',
			[
				'label'     => esc_html__( 'Size', 'durotan' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => [
					'cover'   => esc_html__( 'Cover', 'durotan' ),
					'contain' => esc_html__( 'Contain', 'durotan' ),
					'auto'    => esc_html__( 'Auto', 'durotan' ),
					'100% 100%'    => esc_html__( '100%', 'durotan' ),
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-newsletter-2' => 'background-size: {{VALUE}}',
				],
				'condition' => [
					'background_img[url]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'background_repeat',
			[
				'label'     => esc_html__( 'Repeat', 'durotan' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'repeat',
				'options'   => [
					'repeat'   => esc_html__( 'Repeat', 'durotan' ),
					'no-repeat' => esc_html__( 'No Repeat', 'durotan' ),
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-newsletter-2' => 'background-repeat: {{VALUE}}',
				],
				'condition' => [
					'background_img[url]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'background_position',
			[
				'label'     => esc_html__( 'Position', 'durotan' ),
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
					'{{WRAPPER}} .durotan-newsletter-2' => 'background-position: {{VALUE}};',
				],
				'condition' => [
					'background_img[url]!' => '',
				],

			]
		);

		$this->add_responsive_control(
			'background_position_xy',
			[
				'label'              => esc_html__( 'Custom Position', 'durotan' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'allowed_dimensions' => [ 'top', 'left' ],
				'size_units'         => [ 'px', '%' ],
				'default'            => [ ],
				'selectors'          => [
					'{{WRAPPER}} .durotan-newsletter-2' => 'background-position: {{LEFT}}{{UNIT}} {{TOP}}{{UNIT}};',
				],
				'condition' => [
					'background_position' => [ 'initial' ],
					'background_img[url]!' => '',
				],
				'required' => true,
			]
		);

		$this->add_responsive_control(
			'background_color',
			[
				'label'     => esc_html__( 'Background Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-newsletter-2' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'background_img[url]!' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Section Style
	 */
	protected function section_style() {
		$this->section_general_style();
		$this->section_form_style();
	}

	protected function section_general_style() {
		// Content
		$this->start_controls_section(
			'section_general_style',
			[
				'label' => __( 'General', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label'      => esc_html__( 'Height', 'durotan' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 100,
						'max' => 5000,
					],
				],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-newsletter-2' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label'      => esc_html__( 'Padding', 'durotan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-newsletter-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->section_style_before_title();
		$this->section_style_title();
		$this->section_style_desc();
		$this->section_style_text();

		$this->end_controls_section();

	}

	protected function section_style_before_title() {

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
				'selector' => '{{WRAPPER}} .durotan-newsletter-2 .durotan-newsletter-2__before-heading',
			]
		);

		$this->add_control(
			'before_heading_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-newsletter-2 .durotan-newsletter-2__before-heading' => 'color: {{VALUE}}',

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
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-newsletter-2 .durotan-newsletter-2__before-heading' => 'margin-bottom: {{SIZE}}{{UNIT}}',
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
				'selector' => '{{WRAPPER}} .durotan-newsletter-2 .durotan-newsletter-2__heading',
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-newsletter-2 .durotan-newsletter-2__heading' => 'color: {{VALUE}}',

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

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .durotan-newsletter-2 .durotan-newsletter-2__description',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-newsletter-2 .durotan-newsletter-2__description' => 'color: {{VALUE}}',

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
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .durotan-newsletter-2 .durotan-newsletter-2__description' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
	}

	protected function section_style_text() {
		// Description
		$this->add_control(
			'heading_text',
			[
				'label'     => esc_html__( 'Text', 'durotan' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .durotan-newsletter-2 .durotan-newsletter-2__text',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-newsletter-2 .durotan-newsletter-2__text' => 'color: {{VALUE}}',

				],
			]
		);
	}

	/**
	 * Element in Tab Style
	 *
	 * Form
	 */
	protected function section_form_style() {
		$this->start_controls_section(
			'section_form_style',
			[
				'label' => __( 'Content', 'durotan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'form_width',
			[
				'label'      => __( 'Width', 'durotan' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'%'  => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default'    => [ ],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .durotan-newsletter-2 .durotan-newsletter-2__right' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'form_border',
			[
				'label'     => __( 'Border Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-newsletter-2 form' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'form_text_color',
			[
				'label'     => __( 'Text Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-newsletter-2 .mc4wp-form .durotan-svg-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .durotan-newsletter-2 .mc4wp-form input[type="email"]' => 'color: {{VALUE}};',
					'{{WRAPPER}} .durotan-newsletter-2 .mc4wp-form input[type=submit]' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_text_placeholder_color',
			[
				'label'     => __( 'Text Placeholder Color', 'durotan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .durotan-newsletter-2 .mc4wp-form input[type="email"]::placeholder' => 'color: {{VALUE}};',
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
			'durotan-newsletter-2'
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div class="durotan-newsletter-2__left">
				<div class="durotan-newsletter-2__before-heading"><?php echo wp_kses_post( $settings['before_title'] ); ?></div>
				<div class="durotan-newsletter-2__heading"><?php echo wp_kses_post( $settings['title'] ); ?></div>
			</div>
			<div class="durotan-newsletter-2__right">
				<div class="durotan-newsletter-2__description"><?php echo wp_kses_post( $settings['description'] ); ?></div>
				<?php if ( $settings['form'] ) :
					echo do_shortcode( '[mc4wp_form id="' . esc_attr( $settings['form'] ) . '"]' );
				endif; ?>
				<div class="durotan-newsletter-2__text"><?php echo wp_kses_post( $settings['text'] ); ?></div>
			</div>
		</div>
		<?php
	}

	/**
	 * Get Contact Form
	 */
	protected function get_contact_form_2() {
		$mail_forms    = get_posts( 'post_type=mc4wp-form&posts_per_page=-1' );
		$mail_form_ids = array(
			'' => esc_html__( 'Select Form', 'durotan' ),
		);
		foreach ( $mail_forms as $form ) {
			$mail_form_ids[$form->ID] = $form->post_title;
		}

		return $mail_form_ids;
	}
}