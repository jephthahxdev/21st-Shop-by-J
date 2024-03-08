<?php
/**
 * Language widget
 *
 * @package Durotan
 */

namespace Durotan\Addons\Widgets;
/**
 * Class Durotan_Categories_Widget
 */
class Durotan_Language_Widget extends \WP_Widget {
	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $default;

	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function __construct() {
		$this->default = array(
			'title' 		 => '',
			'language_type'  => 'horizontal',
		);

		parent::__construct(
			'durotan-language-widget',
			esc_html__( 'Durotan - Language Switch', 'durotan' ),
			array(
				'classname'                   => 'widget_language',
				'description'                 => esc_html__( 'Show language switch.', 'durotan' ),
				'customize_selective_refresh' => true,
			),
		);
	}

	/**
	 * Outputs the HTML for this widget.
     *
	 * @since 1.0.0
	 *
	 * @param array $args     An array of standard parameters for widgets in this theme
	 * @param array $instance An array of settings for this widget instance
	 *
	 * @return void Echoes it's output
	 */
	function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->default );

		echo $args['before_widget'];

		if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$languages = function_exists( 'icl_get_languages' ) ? icl_get_languages() : array();
		$languages = apply_filters( 'wpml_active_languages', $languages );

		$lang_list = array();

		foreach ( (array) $languages as $code => $language ) {

			if ( $language['active'] == '1' ) $current = $language['native_name'];
			$lang_list[] = sprintf(
				'<li class="%s"><a href="%s">%s</a></li>',
				esc_attr( $code ) . ( $language['active'] == '1' ? ' active' : '' ),
				esc_url( $language['url'] ),
				esc_html( $language['native_name'] )
			);
		}
		if ( $instance['language_type'] !== 'horizontal' ) {
			$output = sprintf(
				'<div class="dropdown"><span class="current"><span class="selected">%s</span>%s</span><ul>%s</ul></div>',
				$current,
				\Durotan\Icon::get_svg( 'chevron-bottom' ),
				implode( "\n\t", $lang_list ),
			);
		}else {
			$output = sprintf(
				'<ul>%s</ul>',
				implode( "\n\t", $lang_list ),
			);
		}
		?>
		<div class="footer-language">
			<div class="durotan-language durotan-language--<?php echo esc_attr( $instance['language_type'] ); ?>">
				<?php echo $output; ?>
			</div>
		</div>
		<?php

		echo $args['after_widget'];
	}

	/**
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
     *
	 * @since 1.0.0
	 *
	 * @param array $instance
	 *
	 * @return string|void
	 */
	function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->default );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'durotan' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'language_type' ) ); ?>"><?php esc_html_e( 'Currency type', 'durotan' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'language_type' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'language_type' ) ); ?>">
				<option value="horizontal" <?php selected( $instance['language_type'], 'horizontal' ); ?>><?php echo esc_html__( 'Horizontal', 'durotan' ); ?></option>
				<option value="list-dropdown" <?php selected( $instance['language_type'], 'list-dropdown' ); ?>><?php echo esc_html__( 'Dropdown', 'durotan' ); ?></option>
			</select>
		</p>
		<?php
	}
}
