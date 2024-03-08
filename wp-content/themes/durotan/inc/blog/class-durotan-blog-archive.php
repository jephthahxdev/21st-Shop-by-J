<?php
/**
 * Blog grid functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan\Blog;

use Durotan\Helper, Durotan\Icon;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Blog grid initial
 *
 */
class Archive {
	/**
	 * Instance
	 *
	 * @var $instance
	 */
	protected static $instance = null;

	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'durotan_after_open_site_content', array( $this, 'newsletter_view' )  );
		add_action( 'durotan_after_open_site_content', array( $this, 'featured_posts_view' ) );
		add_action( 'durotan_after_open_site_content', array( $this, 'open_posts_header' ) );
		add_action( 'durotan_after_open_site_content', array( $this, 'show_posts_header' ) );
		add_action( 'durotan_after_open_site_content', array( $this, 'close_posts_header' ) );

		add_action( 'durotan_after_open_posts_content', array( $this, 'global_count' ) );
		add_action( 'durotan_after_open_posts_content', array( $this, 'open_post_list' ) );
		add_action( 'durotan_after_open_posts_content', array( new \Durotan\Helper, 'durotan_loading' ) );

		add_action( 'durotan_after_open_post_loop_content', array( $this, 'post_divider' ), 6 );
		add_action( 'durotan_after_open_post_loop_content', array( $this, 'open_post_summary_classic' ), 25 );
		add_action( 'durotan_after_open_post_loop_content', array( $this, 'open_post_content_top' ), 26 );
		add_action( 'durotan_after_open_post_loop_content', array( $this, 'loop_content' ), 40 );
		add_action( 'durotan_after_open_post_loop_content', array( $this, 'close_post_content_top' ), 42 );
		if(Helper::get_option( 'blog_type' ) !== 'classic' ){
			add_action( 'durotan_after_open_post_loop_content', array( $this, 'loop_footer' ), 45 );
		}
		add_action( 'durotan_after_open_post_loop_content', array( $this, 'close_post_summary_classic' ), 50 );

		add_action( 'durotan_after_close_post_loop_content', array( $this, 'global_count_plus' ) );

		add_action( 'durotan_before_close_posts_content', array( $this, 'close_post_list' ) );
		add_action( 'durotan_before_close_posts_content', array( '\Durotan\Blog\Helper', 'durotan_pagination' ) );

		add_filter( 'next_posts_link_attributes', array( $this, 'next_posts_link_class' ) );
		add_filter( 'previous_posts_link_attributes', array( $this, 'previous_posts_link_class' ) );
	}

	/**
	 * Open posts header
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function open_posts_header() {
		if ( ! intval( Helper::get_option( 'show_blog_cats' ) ) ) {
			return;
		}

		echo '<div class="durotan-posts-header container">';
	}

	/**
	 * Posts header
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function show_posts_header() {
		if ( ! Helper::is_blog() && ! is_singular( 'post' ) ) {
			return;
		}

		$this->taxs_list();
		$this->search();
	}

	/**
	 * Close posts header
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function close_posts_header() {
		if ( ! intval( Helper::get_option( 'show_blog_cats' ) ) ) {
			return;
		}

		echo '</div>';
	}

	/**
	 * Create global count
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function global_count() {
		global $count;
		$count = 1;
	}

	/**
	 * Open post list
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function open_post_list() {
		echo '<div class="durotan-posts-list row">';
	}

	/**
	 * Close post list
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function close_post_list() {
		echo '</div>';
	}

	/**
	 * Open post summary classic
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function open_post_summary_classic() {
		global $count;
		$paged 		 = get_query_var('paged');
		if ( ( $count == 1 && $paged == 0 ) && Helper::get_option( 'blog_type' ) == 'listing' ) return;

		echo '<div class="post__summary">';

	}

	/**
	 * Close post summary classic
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function close_post_summary_classic() {
		global $count;
		$paged 		 = get_query_var('paged');
		if ( ( $count == 1 && $paged == 0 ) && Helper::get_option( 'blog_type' ) == 'listing' ) return;

		echo '</div>';
	}

	/**
	 * Open post summary classic
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function open_post_content_top() {
		if ( Helper::get_option( 'blog_type' ) != 'listing' ) {
			return;
		}

		echo '<div class="post__summary--top">';

	}

	/**
	 * Close post summary classic
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function close_post_content_top() {
		if ( Helper::get_option( 'blog_type' ) != 'listing' ) {
			return;
		}

		echo '</div>';
	}

	/**
	 * Post divider
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function post_divider() {
		global $count;
		$blog_layout = Helper::get_option( 'blog_layout' );
		$paged 		 = get_query_var('paged');
		if ( $count !== 4 || $paged !== 0 || $blog_layout === 'full-content' || ! is_active_sidebar( 'blog-sidebar' ) || Helper::get_option( 'blog_type' ) !== 'listing' ) return;

		echo '<hr class="post__divider">';
	}

	/**
	 * Get blog taxonomy list
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function taxs_list( $taxonomy = 'category' ) {
		if ( Helper::get_option( 'show_blog_cats' ) == 0 ) return;
		$orderby  = Helper::get_option( 'blog_cats_orderby' );
		$order    = Helper::get_option( 'blog_cats_order' );
		$number   = Helper::get_option( 'blog_cats_number' );
		$view_all = wp_kses_post( Helper::get_option( 'blog_cats_view_all' ) );

		$cats   = '';
		$output = array();
		$number = apply_filters( 'durotan_blog_cats_number', $number );

		$args = array(
			'number'  	 => $number,
			'orderby' 	 => $orderby,
			'order'   	 => $order,
			'exclude'	 => array( 1 ),
		);

		$term_id = 0;

		if ( is_tax( $taxonomy ) || is_category() ) {

			$queried_object = get_queried_object();
			if ( $queried_object ) {
				$term_id = $queried_object->term_id;
			}
		}

		$found       = false;
		$custom_slug = intval( Helper::get_option( 'custom_blog_cats' ) );
		if ( $custom_slug ) {
			$cats_slug = (array) Helper::get_option( 'blog_cats_slug' );

			foreach ( $cats_slug as $slug ) {
				$cat = get_term_by( 'slug', $slug, $taxonomy );
				if ( $cat ) {
					$css_class = '';
					if ( $cat->term_id == $term_id ) {
						$css_class = 'selected';
						$found     = true;
					}
					$cats .= sprintf( '<li><a class="%s" href="%s">%s</a></li>', esc_attr( $css_class ), esc_url( get_term_link( $cat ) ), esc_html( $cat->name ) );
				}
			}
		} else {
			$categories = get_terms( $taxonomy, $args );
			if ( ! is_wp_error( $categories ) && $categories ) {
				foreach ( $categories as $cat ) {
					$cat_selected = '';
					if ( $cat->term_id == $term_id ) {
						$cat_selected = 'selected';
						$found        = true;
					}
					$cats .= sprintf( '<li><a href="%s" class="%s">%s</a></li>', esc_url( get_term_link( $cat ) ), esc_attr( $cat_selected ), esc_html( $cat->name ) );
				}
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
					'<li><a href="%s" class="%s">%s</a></li>',
					esc_url( $blog_url ),
					esc_attr( $cat_selected ),
					esc_html( $view_all )
				);
			}

			$output[] = sprintf(
				'<ul class="durotan-posts-header__taxs-list">%s%s</ul>',
				$view_all_box,
				$cats
			);
		}

		if ( $output ) {

			$output = apply_filters( 'durotan_blog_taxs_list', $output );

			echo implode( "\n", $output );
		}
	}

	/**
	 * Search
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function search() {
		if ( Helper::get_option( 'show_blog_search' ) == 0 ) return;
		?>
		<form action="#" class="durotan-posts-header__search">
			<button type="submit" class="search-submit" value="<?php esc_attr_e('Search', 'durotan') ?>">

				<?php echo \Durotan\Icon::get_svg( 'search', '', 'shop' ); ?>

				<span class="button-text screen-reader-text"><?php esc_html_e('Search', 'durotan') ?></span>

			</button>
			<input type="search" class="search-field" placeholder="<?php esc_attr_e('Search in blog', 'durotan') ?>" value="" name="s">
		</form>
		<?php
	}

	/**
	 * Get loop content
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function loop_content() {
		if ( Helper::get_option( 'blog_type' ) == 'grid' ) {
			return;
		}

		if ( get_post()->post_content == '' ) {
			return;
		}

		global $count;
		$paged 		 = get_query_var('paged');
		if ( $count == 1 && $paged == 0 && Helper::get_option( 'listing' ) == '' || Helper::get_option( 'blog_type' ) == 'classic') {

			if ( $length = Helper::get_option( 'excerpt_length' ) ) {
				echo sprintf(
					'<div class="post__excerpt">%s</div>',
					\Durotan\Helper::get_content_limit( $length )
				);
			}
		}

	}

	/**
	 * Get loop footer
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function loop_footer() {
		if ( Helper::get_option( 'blog_type' ) == 'grid' ) {
			return;
		}

		global $count;
		$blog_layout = Helper::get_option( 'blog_layout' );
		if( ( $count == 1 || $count == 2 || $count == 3 ) && $blog_layout !== 'full-content'  ) return;

		echo '<div class="post__footer entry-footer"><div class="post__meta entry-meta">';
			\Durotan\Blog\Helper::meta_comment();
			\Durotan\Blog\Helper::meta_divider();
			\Durotan\Blog\Helper::meta_view();
		echo '</div></div>';
	}

	/**
	 * Newsletter view
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function newsletter_view() {
		$newsletter_status 		= Helper::get_option( 'newsletter_status' );
		$newsletter_title  		= Helper::get_option( 'newsletter_title' );
		$newsletter_popup_form  = Helper::get_option( 'newsletter_popup_form' );
		if( $newsletter_status == 0 ) return;
	?>
		<div class="durotan-posts-newsletter">
			<div class="container">
				<h3><?php echo esc_html( $newsletter_title ); ?></h3>
				<?php echo do_shortcode( wp_kses_post( $newsletter_popup_form ) ) ; ?>
			</div>
			<div class="container">
				<hr class="divider">
			</div>
		</div>
	<?php
	}

	/**
	 * Featured Posts View
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function featured_posts_view() {
		if ( ! intval( Helper::get_option( 'featured_posts_status' ) ) ) {
			return;
		}

		$args    = array(
					'tag' 			 => Helper::get_option( 'featured_posts_tag_slug'),
					'posts_per_page' => Helper::get_option( 'featured_posts_number'),
					'orderby' 		 => Helper::get_option( 'featured_posts_orderby'),
					'order' 		 => Helper::get_option( 'featured_posts_order'),
				);
		$featured_posts = new \WP_Query( $args );

		if( $featured_posts->have_posts() ) :
		?>
		<div class="durotan-featured-posts container">
			<div id="durotan-latest-posts" class="durotan-latest-posts-carousel swiper-container">
				<div class="durotan-latest-posts-carousel__header">
					<h2><?php echo esc_html( Helper::get_option( 'featured_posts_title') ); ?></h2>
					<div class="posts-arrow">
						<?php
							echo Icon::get_svg( 'chevron-left', 'durotan-posts-button durotan-posts-button-prev' );
							echo Icon::get_svg( 'chevron-right', 'durotan-posts-button durotan-posts-button-next' );
						?>
					</div>
				</div>
				<div class="durotan-latest-posts-carousel__list swiper-wrapper">
					<?php while ( $featured_posts->have_posts() ) : $featured_posts->the_post(); $post_thumbnail_id = get_post_thumbnail_id( get_the_ID() ); $image = wp_get_attachment_image( $post_thumbnail_id, 'durotan-featured-posts' ); ?>
						<div class="latest-post swiper-slide">
							<?php echo sprintf( '<a class="latest-post__thumbnail" href="%s" aria-hidden="true">%s</a>', esc_url( get_permalink() ), $image ); ?>
							<span class="latest-post__time">
								<span class="day-number"><?php echo esc_html( get_the_date( 'j' ) ); ?></span>
								<span class="date-number"><?php echo esc_html( get_the_date( 'F Y' ) ); ?></span>
							</span>
							<?php the_title( '<h5 class="latest-post__title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h5>' ); ?>
							<span class="latest-post__meta">
								<?php
									\Durotan\Blog\Helper::meta_cat();
									\Durotan\Blog\Helper::meta_divider();
									\Durotan\Blog\Helper::meta_author();
								?>
							</span>
						</div>
					<?php endwhile; ?>
				</div>
			</div>
		</div>
		<?php
		endif;
	}

	/**
	 * Calculator global count
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function global_count_plus() {
		global $count;
		$count++;
	}

	/**
	 * Addclass to next posts link
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function next_posts_link_class() {
		return 'class="next page-numbers"';
	}

	/**
	 * Addclass to previous posts link
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function previous_posts_link_class() {
		return 'class="prev page-numbers"';
	}
}
