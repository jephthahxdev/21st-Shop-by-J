<?php
/**
 * Blog grid functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan\Blog;

use Durotan\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Blog grid initial
 *
 */
class Featured {
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
		add_action( 'durotan_after_open_site_content', array( $this, 'featured_view' ), 10  );
	}

	/**
	 * Featured view
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function featured_view() {
		$featured_content_tag 	  = Helper::get_option( 'featured_content_tag_slug');
		$featured_content_number = Helper::get_option( 'featured_content_number');
		$featured_content_type   = Helper::get_option( 'featured_content_type');
		$classed = $featured_content_type === 'wide' ? 'durotan-container' : '';
		$args    = array(
					'tag' 			 => $featured_content_tag,
					'posts_per_page' => $featured_content_number,
				);
		$featured = new \WP_Query( $args );

		if( $featured->have_posts() ) :
		?>
			<div id="durotan-featured-posts" class="durotan-featured durotan-featured-posts-carousel <?php echo esc_attr( $classed ); ?> swiper-container">
				<div class="featured-posts-carousel__inner swiper-wrapper">
					<?php while ( $featured->have_posts() ) : $featured->the_post(); $post_thumbnail_id = get_post_thumbnail_id( get_the_ID() ); $image = wp_get_attachment_image_src( $post_thumbnail_id, 'full' ); ?>
					<div class="featured-post swiper-slide" style="background-image: url(<?php echo esc_attr( $image[0] ); ?>);">
						<div class="featured-post__inner container">
							<div class="blog-wrapper__entry-meta">
								<?php
									\Durotan\Blog\Helper::meta_cat();
									\Durotan\Blog\Helper::meta_divider();
									\Durotan\Blog\Helper::meta_date();
								?>
							</div>
							<?php the_title( '<h3 class="blog-wrapper__entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
						</div>
					</div>
					<?php endwhile; ?>
				</div>
				<div class="swiper-pagination container"></div>
			</div>
		<?php
		endif;
	}
}
