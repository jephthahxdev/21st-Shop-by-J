<?php
/**
 * Social links widget
 *
 * @package Durotan
 */

namespace Durotan\Addons\Widgets;
/**
 * Class Durotan_Social_Links_Widget
 */
class Social_Links extends \WP_Widget {
	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $default;

	/**
	 * List of supported socials
	 *
	 * @var array
	 */
	protected $socials;

	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function __construct() {
		$socials = array(
			'twitter'     => esc_html__( 'Twitter', 'durotan' ),
			'facebook'    => esc_html__( 'Facebook', 'durotan' ),
			'google'	  => esc_html__( 'Google Plus', 'durotan' ),
			'instagram'   => esc_html__( 'Instagram', 'durotan' ),
			'youtube'     => esc_html__( 'Youtube', 'durotan' ),
			'tumblr'      => esc_html__( 'Tumblr', 'durotan' ),
			'linkedin'    => esc_html__( 'Linkedin', 'durotan' ),
			'pinterest'   => esc_html__( 'Pinterest', 'durotan' ),
			'flickr'      => esc_html__( 'Flickr', 'durotan' ),
			'dribbble'    => esc_html__( 'Dribbble', 'durotan' ),
			'behance'     => esc_html__( 'Behance', 'durotan' ),
			'github'      => esc_html__( 'Github', 'durotan' ),
			'vimeo'       => esc_html__( 'Vimeo', 'durotan' ),
			'rss'         => esc_html__( 'RSS', 'durotan' ),
		);

		$this->socials = apply_filters( 'durotan_social_media', $socials );
		$this->default = array(
			'title' => '',
			'social_type' => 1,
		);

		foreach ( $this->socials as $k => $v ) {
			$this->default["{$k}_title"] = $v;
			$this->default["{$k}_url"]   = '';
		}

		parent::__construct(
			'social-links-widget',
			esc_html__( 'Durotan - Social Links', 'durotan' ),
			array(
				'classname'                   => 'durotan-socials-widget',
				'description'                 => esc_html__( 'Display links to social media networks.', 'durotan' ),
				'customize_selective_refresh' => true,
			),
			array( 'width' => 600 )
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

		echo '<div class="socials-content ' . ( $instance['social_type'] == '1' ? 'socials-content__v1' : 'socials-content__v2' ) . '">';

		foreach ( $this->socials as $social => $label ) {
			if ( empty( $instance[ $social . '_url' ] ) ) {
				continue;
			}

			$icon = $social;

			printf(
				'<a href="%s" class="%s social" rel="nofollow" title="%s" data-toggle="tooltip" data-placement="top" target="_blank">%s</a>',
				esc_url( $instance[ $social . '_url' ] ),
				esc_attr( $social ),
				esc_attr( $instance[ $social . '_title' ] ),
				$instance['social_type'] == '1' ? \Durotan\Icon::get_svg( $icon, 'svg-icon', 'social' ) : \Durotan\Icon::get_svg( $icon, 'svg-icon', 'social_2' ),
			);
		}

		echo '</div>';

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
			<label for="<?php echo esc_attr( $this->get_field_id( 'social_type' ) ); ?>"><?php esc_html_e( 'Social type', 'durotan' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'social_type' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'social_type' ) ); ?>">
				<option value="1" <?php selected( $instance['social_type'], '1' ); ?>><?php echo esc_html__( 'Type 1', 'durotan' ); ?></option>
				<option value="2" <?php selected( $instance['social_type'], '2' ); ?>><?php echo esc_html__( 'Type 2', 'durotan' ); ?></option>
			</select>
		</p>
		<?php
		foreach ( $this->socials as $social => $label ) {
			printf(
				'<div style="width: 280px; float: left; margin-right: 10px;">
					<label>%s</label>
					<p><input type="text" class="widefat" name="%s" placeholder="%s" value="%s"></p>
				</div>',
				$label,
				$this->get_field_name( $social . '_url' ),
				esc_html__( 'URL', 'durotan' ),
				$instance[ $social . '_url' ]
			);
		}
	}
}
