<?php
/**
 * Popular Posts widget
 *
 * @package Durotan
 */

namespace Durotan\Addons\Widgets;
/**
 * Class Durotan_Popular_Posts_Widget
 */
class Durotan_Popular_Posts_Widget extends \WP_Widget {
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
			'title' 		=> 'Popular Posts',
			'number_post' 	=> 4,
		);

		parent::__construct(
			'durotan-popular-posts-widget',
			esc_html__( 'Durotan - Popular Posts', 'durotan' ),
			array(
				'classname'                   => 'durotan-popular-posts-widget',
				'description'                 => esc_html__( 'A list popular posts.', 'durotan' ),
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

		$number = $instance[ 'number_post' ];
		$count 	= 1;
		$r 		= new \WP_Query(
						array(
							'posts_per_page'      => $number,
							'no_found_rows'       => true,
							'post_status'         => 'publish',
							'ignore_sticky_posts' => true,
							'post__not_in'        => array(get_the_ID()),
							'meta_key'			  => 'durotan_post_views_count',
							'orderby'			  => 'meta_value_num',
							'order'				  => 'DESC',
						),
					);

		if ( ! $r->have_posts() ) {
			return;
		}

		echo $args['before_widget'];

		if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
			?>

			<ul>
				<?php foreach ( $r->posts as $popular_post ) : ?>
					<?php
					$post_title   = get_the_title( $popular_post->ID );
					$title        = ( ! empty( $post_title ) ) ? $post_title : __( '(no title)' );
					$aria_current = '';

					if ( get_queried_object_id() === $popular_post->ID ) {
						$aria_current = ' aria-current="page"';
					}
					$post_cat = get_the_category( $popular_post->ID );
					?>
					<li>
						<span class="no"><?php echo esc_html( $count ); ?></span>
						<div class="post-summary">
							<span class="post-cat"><a href="<?php echo esc_url( get_category_link( $post_cat[0]->term_id ) ) ?>" rel="category tag"><?php echo esc_html( $post_cat[0]->cat_name ); ?></a></span>
							<span class="post-title">
								<a href="<?php the_permalink( $popular_post->ID ); ?>"<?php echo esc_attr( $aria_current ); ?>><?php echo esc_html( $title ); ?></a>
							</span>
						</div>
					</li>
				<?php $count++; endforeach; ?>
			</ul>

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
			<label for="<?php echo esc_attr( $this->get_field_id( 'number_post' ) ); ?>"><?php esc_html_e( 'Number of posts to show', 'durotan' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number_post' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number_post' ) ); ?>" value="<?php echo esc_attr( $instance['number_post'] ); ?>" />
		</p>
		<?php
	}
}
