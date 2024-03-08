<?php
/**
 * Instagram widget
 *
 * @package Durotan
 */

namespace Durotan\Addons\Widgets;

use Durotan\Addons\Widgets;

/**
 * Class Durotan_Instagram_Widget
 */
class Durotan_Instagram_Widget extends \WP_Widget {
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
			'title' 		=> 'Instagram',
			'description'	=> '',
			'access_token' 	=> '',
			'limit' 		=> 6,
			'column' 		=> 3,
		);

		parent::__construct(
			'durotan-instagram-widget',
			esc_html__( 'Durotan - Instagram', 'durotan' ),
			array(
				'classname'                   => 'durotan-instagram-widget',
				'description'                 => esc_html__( 'Show instagram.', 'durotan' ),
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

			$output    	  = [];
			$description  = wp_kses_post( $instance['description'] );
			$access_token = $instance['access_token'];
			$limit		  = $instance['limit'];
			$class_column = $instance['column'];

			$instagram = Widgets::get_instagram_get_photos_by_token( $limit, $access_token );

			$user = apply_filters( 'durotan_get_instagram_user', array() );
			if ( empty( $user ) ) {
				$user = Widgets::get_instagram_user( $access_token );
			}

			if ( is_wp_error( $instagram ) ) {
				return $instagram->get_error_message();
			}

			if ( ! $instagram ) {
				return;
			}

			$count = 1;

			$output[] = sprintf('<ul>');

			foreach ( $instagram as $data ) {

				if ( $count > intval( $limit ) ) {
					break;
				}

				$output[] = '<li><a target="_blank" href="' . esc_url( $data['link'] ) . '"><img src="' . esc_url( $data['images']['thumbnail'] ) . '" alt="' . esc_attr( $data['caption'] ) . '"></a></li>';

				$count ++;
			}
			$output[] = sprintf('</ul>');

			echo sprintf(
				'<div class="instagram-desc">%s</div><div class="instagram-feed instagram-feed--original columns-%s">%s</div>',
				$description,
				$class_column,
				implode( '', $output )
			);

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
			<label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description', 'durotan' ); ?></label>
			<textarea id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>" class="widefat" rows="5"><?php echo esc_textarea( $instance['description'] ); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'access_token' ) ); ?>"><?php esc_html_e( 'Access Token', 'durotan' ); ?></label>
			<textarea id="<?php echo esc_attr( $this->get_field_id( 'access_token' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'access_token' ) ); ?>" class="widefat"><?php echo esc_textarea( $instance['access_token'] ); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'Limit', 'durotan' ); ?></label>
			<input type="number" min="1" max="10" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" value="<?php echo esc_attr( $instance['limit'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'column' ) ); ?>"><?php esc_html_e( 'Column', 'durotan' ); ?></label>
			<input type="number" min="1" max="5" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'column' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'column' ) ); ?>" value="<?php echo esc_attr( $instance['column'] ); ?>" />
		</p>
		<?php
	}
}
