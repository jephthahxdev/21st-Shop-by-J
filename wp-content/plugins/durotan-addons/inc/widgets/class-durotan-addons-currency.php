<?php
/**
 * Currency widget
 *
 * @package Durotan
 */

namespace Durotan\Addons\Widgets;
/**
 * Class Durotan_Categories_Widget
 */
class Durotan_Currency_Widget extends \WP_Widget {
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
			'currency_type'  => 'horizontal',
		);

		parent::__construct(
			'durotan-currency-widget',
			esc_html__( 'Durotan - Currency Switch', 'durotan' ),
			array(
				'classname'                   => 'widget_currency',
				'description'                 => esc_html__( 'Show currency switch.', 'durotan' ),
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

		global $WOOCS;

		$currencies    = $WOOCS->get_currencies();
		$currency_list = array();

		foreach ( $currencies as $key => $currency ) {
			if ( $WOOCS->current_currency == $key ) {
				array_unshift( $currency_list, sprintf(
					'<li class="%s"><a href="#" class="woocs_flag_view_item woocs_flag_view_item_current" data-currency="%s">%s %s</a></li>',
					$currencies[ $WOOCS->current_currency ]['name'] === $currency['name'] ? 'active' : '',
					esc_attr( $currency['name'] ),
					$instance['currency_type'] !== 'horizontal' ? esc_html( $currency['symbol'] ) : '',
					esc_html( $currency['name'] )
				) );
			} else {
				$currency_list[] = sprintf(
					'<li class="%s"><a href="#" class="woocs_flag_view_item" data-currency="%s">%s %s</a></li>',
					$currencies[ $WOOCS->current_currency ]['name'] === $currency['name'] ? 'active' : '',
					esc_attr( $currency['name'] ),
					$instance['currency_type'] !== 'horizontal' ? esc_html( $currency['symbol'] ) : '',
					esc_html( $currency['name'] )
				);
			}
		}

		if ( $instance['currency_type'] !== 'horizontal' ) {
			$output = sprintf(
				'<div class="dropdown"><span class="current"><span class="selected">%s</span>%s</span><ul>%s</ul></div>',
				esc_html( $currencies[ $WOOCS->current_currency ]['symbol'] . ' '. $currencies[ $WOOCS->current_currency ]['name'] ),
				\Durotan\Icon::get_svg( 'chevron-bottom' ),
				implode( "\n\t", $currency_list ),
			);
		}else {
			$output = sprintf(
				'<ul>%s</ul>',
				implode( "\n\t", $currency_list ),
			);
		}
		?>
		<div class="footer-currency">
			<div class="durotan-currency durotan-currency--<?php echo esc_attr( $instance['currency_type'] ); ?>">
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
			<label for="<?php echo esc_attr( $this->get_field_id( 'currency_type' ) ); ?>"><?php esc_html_e( 'Currency type', 'durotan' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'currency_type' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'currency_type' ) ); ?>">
				<option value="horizontal" <?php selected( $instance['currency_type'], 'horizontal' ); ?>><?php echo esc_html__( 'Horizontal', 'durotan' ); ?></option>
				<option value="list-dropdown" <?php selected( $instance['currency_type'], 'list-dropdown' ); ?>><?php echo esc_html__( 'Dropdown', 'durotan' ); ?></option>
			</select>
		</p>
		<?php
	}
}
