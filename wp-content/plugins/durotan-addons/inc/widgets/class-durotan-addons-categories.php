<?php
/**
 * Categories widget
 *
 * @package Durotan
 */

namespace Durotan\Addons\Widgets;
/**
 * Class Durotan_Categories_Widget
 */
class Durotan_Categories_Widget extends \WP_Widget {
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
			'title' 		=> 'Categories',
			'cats_orderby'  => 'count',
			'cats_order'	=> 'DESC',
			'cats_number'	=> 5,
			'cats_view_all'	=> 'All',
		);

		parent::__construct(
			'durotan-categories-widget',
			esc_html__( 'Durotan - Categories', 'durotan' ),
			array(
				'classname'                   => 'widget_categories',
				'description'                 => esc_html__( 'A list or dropdown of categories.', 'durotan' ),
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
			$taxonomy = 'category';
			$orderby  = $instance[ 'cats_orderby' ];
			$order    = $instance[ 'cats_order' ];
			$number   = $instance[ 'cats_number' ];
			$view_all = $instance[ 'cats_view_all' ];

			$cats   = '';
			$output = array();

			$args_cat = array(
				'number'  => $number,
				'orderby' => $orderby,
				'order'   => $order,
				'exclude' => array( 1 ),
			);

			$term_id = 0;

			if ( is_tax( $taxonomy ) || is_category() ) {

				$queried_object = get_queried_object();
				if ( $queried_object ) {
					$term_id = $queried_object->term_id;
				}
			}

			$found       = false;

			$categories = get_terms( $taxonomy, $args_cat );
			if ( ! is_wp_error( $categories ) && $categories ) {
				foreach ( $categories as $cat ) {
					$cat_selected = '';
					if ( $cat->term_id == $term_id ) {
						$cat_selected = 'selected';
						$found        = true;
					}
					$count = '<span class="posts-count">'.$cat->count.'</span>';
					$cats .= sprintf( '<li class="cat-item"><a href="%s" class="%s">%s %s</a></li>', esc_url( get_term_link( $cat ) ), esc_attr( $cat_selected ), esc_html( $cat->name ), $count );
				}
			}

			$cat_selected = $found ? '' : 'selected';

			if ( $cats ) {

				$blog_url = get_page_link( get_option( 'page_for_posts' ) );
				if ( 'posts' == get_option( 'show_on_front' ) ) {
					$blog_url = home_url();
				}

				$view_all_box = '';

				if ( ! empty( $view_all ) ) {
					$view_all_box = sprintf(
						'<li class="cat-item"><a href="%s" class="%s">%s</a></li>',
						esc_url( $blog_url ),
						esc_attr( $cat_selected ),
						esc_html( $view_all )
					);
				}

				$output[] = sprintf(
					'<ul>%s%s</ul>',
					$view_all_box,
					$cats
				);
			}

			if ( $output ) {

				echo implode( "\n", $output );
			}

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
			<label for="<?php echo esc_attr( $this->get_field_id( 'cats_view_all' ) ); ?>"><?php esc_html_e( 'View All Text', 'durotan' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'cats_view_all' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cats_view_all' ) ); ?>" value="<?php echo esc_attr( $instance['cats_view_all'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'cats_orderby' ) ); ?>"><?php esc_html_e( 'Categories Orderby', 'durotan' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'cats_orderby' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'cats_orderby' ) ); ?>">
				<option value="count" <?php selected( $instance['cats_orderby'], 'count' ); ?>>Count</option>
				<option value="title" <?php selected( $instance['cats_orderby'], 'title' ); ?>>Title</option>
				<option value="id" <?php selected( $instance['cats_orderby'], 'id' ); ?>>ID</option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'cats_order' ) ); ?>"><?php esc_html_e( 'Categories Order', 'durotan' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'cats_order' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'cats_order' ) ); ?>">
				<option value="DESC" <?php selected( $instance['cats_order'], 'DESC' ); ?>>DESC</option>
				<option value="ASC" <?php selected( $instance['cats_order'], 'ASC' ); ?>>ASC</option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'cats_number' ) ); ?>"><?php esc_html_e( 'Categories Number', 'durotan' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'cats_number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cats_number' ) ); ?>" value="<?php echo esc_attr( $instance['cats_number'] ); ?>" />
		</p>
		<?php
	}
}
