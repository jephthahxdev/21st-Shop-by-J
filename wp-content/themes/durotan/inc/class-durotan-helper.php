<?php
/**
 * Durotan helper functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan;

use Durotan\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Durotan Helper initial
 *
 */
class Helper {
	/**
	 * Post ID
	 *
	 * @var $post_id
	 */
	protected static $post_id = null;
	/**
	 * Get google fonts
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_google_fonts() {
		$fonts = array(
			   "Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900",
			   "Marcellus",
			   "Work+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900",
			);
		$fonts_collection = add_query_arg(array(

			"family"=>urlencode(implode("|",$fonts)),

			"display"=>"swap"

			),'https://fonts.googleapis.com/css');

		return $fonts_collection;
	}

	/**
	 * Get theme option
	 *
	 * @since 1.0.0
	 * @param string $name
	 * @return mixed
	 */
	public static function get_option( $name ) {
		return Theme::instance()->get( 'options' )->get_option( $name );
	}

	/**
	 * Check is blog
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public static function is_blog() {
		if ( ( is_archive() || is_author() || is_category() || is_home() || is_tag() ) && 'post' == get_post_type() ) {
			return true;
		}

		return false;
	}

	/**
	 * Check is catalog
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public static function is_catalog() {
		if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check is page elementor
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public static function is_page_elementor() {
		if( is_page() && get_post_meta( get_the_ID(), '_elementor_edit_mode', true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check is single post full content
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public static function is_single_full_content_elementor() {
		if( is_singular('post') && \Durotan\Layout::content_layout() == 'full-content' && get_post_meta( get_the_ID(), '_elementor_edit_mode', true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get post found
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function posts_found() {
		global $wp_query;
		if ( $wp_query && isset( $wp_query->found_posts ) ) {
			$post_text = $wp_query->found_posts > 1 ? esc_html__( 'posts', 'durotan' ) : esc_html__( 'post', 'durotan' );
			echo sprintf( '<div class="durotan-posts__found"><div class="durotan-posts__found-inner">%s<span class="current-post"> %s </span> %s <span class="found-post"> %s </span> %s <span class="count-bar"></span></div> </div>',
				esc_html__( 'Showing', 'durotan' ), $wp_query->post_count, esc_html__( 'of', 'durotan' ), $wp_query->found_posts, $post_text );
		}
	}

	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 *
	 * @since 1.0.0
	 *	@param string $size
	 * @return void
	 */
	public static function post_thumbnail( $size = 'full' ) {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );

		if ( is_singular() ) :?>

            <div class="single-post__thumbnail">
				<?php echo wp_get_attachment_image( $post_thumbnail_id, $size ); ?>
            </div><!-- .post-thumbnail -->

		<?php else : ?>

            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php echo wp_get_attachment_image( $post_thumbnail_id, $size ); ?>
            </a>

		<?php
		endif; // End is_singular().
	}

	/**
	 * Content limit
	 *
	 * @since 1.0.0
	 * @param int $num_words
	 * @return string
	 */
	public static function get_content_limit( $num_words ) {
		$content = apply_filters( 'the_content', get_the_excerpt() );
		$content = strip_shortcodes( $content );
		$content = wp_strip_all_tags( $content );
		$content = wp_trim_words( $content, $num_words, NULL );
		$content = sprintf( '%s', $content );

		return $content;
	}

	/**
	 * Get Post date
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function post_date() {
		$day   = '<span class="field-day">' . esc_html( get_the_date( "d" ) ) . '</span>';
		$month = '<span class="field-month">' . esc_html( get_the_date( "M" ) ) . '</span>';

		echo sprintf( '<div class="blog-date">%s %s</div>', $month, $day );
	}

	/**
	 * Get Post ID
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_post_ID() {
		if( isset( self::$post_id ) ) {
			return self::$post_id;
		}

		if ( Helper::is_catalog() ) {
			self::$post_id = intval( get_option( 'woocommerce_shop_page_id' ) );
		} elseif ( Helper::is_blog() ) {
			self::$post_id = intval( get_option( 'page_for_posts' ) );
		} else {
			self::$post_id = get_the_ID();
		}

		return self::$post_id;
	}

	/**
	 * Post pagination
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function load_pagination() {
		global $wp_query;
		if ( $wp_query->max_num_pages < 2 ) {
			return;
		}
		$default_posts_per_page = $wp_query->found_posts - $wp_query->post_count;
		$nav_html 				= sprintf( '<span class="button-text">%s (%s)</span>', Helper::get_option( 'blog_pagination_view_more_text' ), $default_posts_per_page );
		$nav_position			= Helper::get_option( 'blog_pagination_position_load' );
		?>
        <nav class="navigation next-posts-navigation load-navigation <?php echo esc_attr( $nav_position ); ?>">
            <div class="nav-links">
				<?php if ( get_next_posts_link() ) : ?>
                    <div id="durotan-blog-previous-ajax" class="nav-previous-ajax">
						<?php next_posts_link( $nav_html ); ?>
                        <div class="durotan-gooey-loading">
                            <div class="durotan-gooey">
                                <div class="dots">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>
				<?php endif; ?>
            </div>
        </nav>
		<?php
	}

	/**
	 * Numeric pagination
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function numeric_pagination() {
		if( is_singular() ) return;

		global $wp_query;
		$nav_position = Helper::get_option( 'blog_pagination_position_numeric' );
		/** Stop execution if there's only 1 page */
		if( $wp_query->max_num_pages <= 1 ) return;

		$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
		$max   = intval( $wp_query->max_num_pages );

		/** Add current page to the array */
		if ( $paged >= 1 )
			$links[] = $paged;

		/** Add the pages around the current page to the array */
		if ( $paged >= 3 ) {
			$links[] = $paged - 1;
			$links[] = $paged - 2;
		}

		if ( ( $paged + 2 ) <= $max ) {
			$links[] = $paged + 2;
			$links[] = $paged + 1;
		}

		echo '<div class="navigation durotan-pagination post-navigation '.esc_attr( $nav_position ).'"><div class="nav-links">'. "\n";

		/** Previous Post Link */
		if ( get_previous_posts_link() )
			printf( '%s' . "\n", get_previous_posts_link( __( \Durotan\Icon::get_svg( 'chevron-left' ).' Previous', 'durotan' ) ) );

		/** Link to first page, plus ellipses if necessary */
		if ( ! in_array( 1, $links ) ) {
			$class = 1 == $paged ? ' class="current"' : '';

			printf( '<a class="page-numbers %s" href="%s">%s</a>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );


			if ( ! in_array( 2, $links ) )
            	echo '<span class="page-numbers"> … </span>';

		}
		/** Link to current page, plus 2 pages in either direction if necessary */
		sort( $links );
		foreach ( (array) $links as $link ) {
			$class = $paged == $link ? 'current' : '';
			printf( '<a class="page-numbers %s" href="%s">%s</a>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
		}
		/** Link to last page, plus ellipses if necessary */
		if ( ! in_array( $max, $links ) ) {
			if ( ! in_array( $max - 1, $links ) )
            	echo '<span class="page-numbers"> … </span>' . "\n";

			$class = $paged == $max ? ' class="current"' : '';
			printf( '<a class="page-numbers %s" href="%s">%s</a>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
		}
		/** Next Post Link */
		if ( get_next_posts_link() )
			printf( '%s' . "\n", get_next_posts_link( ( 'Next '.\Durotan\Icon::get_svg( 'chevron-right' ) ) ) );

		echo '</div></div>' . "\n";
	}

	/**
	 * Get post views
	 *
	 * @since 1.0.0
	 * @param int $post_ID
	 * @return void
	 */
	public static function get_post_views( $post_ID ) {
		$count_key = 'durotan_post_views_count';
		$count = get_post_meta($post_ID, $count_key, true);
		if($count==''){
			delete_post_meta($post_ID, $count_key);
			add_post_meta($post_ID, $count_key, '0');
			return "0";
		}
		return $count;
	}

	/**
	 * Set post views
	 *
	 * @since 1.0.0
	 * @param int $post_ID
	 * @return void
	 */
	public static function set_post_views( $post_ID ) {
		$count_key = 'durotan_post_views_count';
		$count = get_post_meta($post_ID, $count_key, true);
		if($count==''){
			$count = 0;
			delete_post_meta($post_ID, $count_key);
			add_post_meta($post_ID, $count_key, '0');
		}else{
			$count++;
			update_post_meta($post_ID, $count_key, $count);
		}

	}

	/**
	 * Print HTML of currency switcher
	 * It requires plugin WooCommerce Currency Switcher installed
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function currency_switcher( $args = array() ) {
		if ( ! class_exists( 'WOOCS' ) ) {
			return;
		}

		global $WOOCS;

		$args          = wp_parse_args( $args, array( 'label' => '', 'direction' => 'down' ) );
		$currencies    = $WOOCS->get_currencies();
		$currency_list = array();

		foreach ( $currencies as $key => $currency ) {
			$class_link = Helper::get_option( 'header_languages_type' ) == 'dropdown' ? 'underline-hover' : '';
			if ( $WOOCS->current_currency == $key ) {
				array_unshift( $currency_list, sprintf(
					'<li><a href="#" class="woocs_flag_view_item woocs_flag_view_item_current %s" data-currency="%s">%s %s</a></li>',
					esc_attr( $class_link ),
					esc_attr( $currency['name'] ),
					esc_html( $currency['symbol'] ),
					esc_html( $currency['name'] )
				) );
			} else {
				$currency_list[] = sprintf(
					'<li><a href="#" class="woocs_flag_view_item %s" data-currency="%s">%s %s</a></li>',
					esc_attr( $class_link ),
					esc_attr( $currency['name'] ),
					esc_attr( $currency['symbol'] ),
					esc_html( $currency['name'] )
				);
			}
		}
		?>
		<div class="durotan-currency durotan-header-list durotan-header-list--<?php echo esc_attr( Helper::get_option( 'header_currencies_type' ) ) ?> <?php echo esc_attr( $args['direction'] ) ?>">
			<?php if ( ! empty( $args['label'] ) ) : ?>
				<span class="label"><?php echo esc_html( $args['label'] ); ?></span>
			<?php endif; ?>
			<div class="dropdown">
				<span class="current">
					<span class="selected"><?php echo esc_html( $currencies[ $WOOCS->current_currency ]['symbol'] . ' '. $currencies[ $WOOCS->current_currency ]['name'] ); ?></span>
					<?php echo \Durotan\Icon::get_svg( 'chevron-bottom' ) ?>
				</span>
				<div class="currency-dropdown durotan-header-list__dropdown">
					<ul>
						<?php echo implode( "\n\t", $currency_list ); ?>
					</ul>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Print HTML of language switcher
	 * It requires plugin WPML installed
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function language_switcher( $args = array(), $no = '' ) {
		$languages = function_exists( 'icl_get_languages' ) ? icl_get_languages() : array();
		$languages = apply_filters( 'wpml_active_languages', $languages );

		if( isset( $languages[$no] ) ) {
			$languages = $languages[$no];
		}

		if ( empty( $languages ) ) {
			return;
		}

		$args      = wp_parse_args( $args, array( 'label' => '', 'direction' => 'down' ) );
		$lang_list = array();
		$current   = '';

		foreach ( (array) $languages as $code => $language ) {
			$class_link = Helper::get_option( 'header_languages_type' ) == 'dropdown' ? 'underline-hover' : '';
			if ( ! $language['active'] ) {
				$lang_list[] = sprintf(
					'<li class="%s"><a class="%s" href="%s">%s</a></li>',
					esc_attr( $code ),
					esc_attr( $class_link ),
					esc_url( $language['url'] ),
					esc_html( $language['native_name'] )
				);
			} else {
				$current = $language;
				array_unshift( $lang_list, sprintf(
					'<li class="%s"><a class="%s" href="%s">%s</a></li>',
					esc_attr( $code ),
					esc_attr( $class_link ),
					esc_url( $language['url'] ),
					esc_html( $language['native_name'] )
				) );
			}
		}

		?>

		<div class="durotan-language durotan-header-list durotan-header-list--<?php echo esc_attr( Helper::get_option( 'header_languages_type' ) ) ?> <?php echo esc_attr( $args['direction'] ) ?>">
			<?php if ( ! empty( $args['label'] ) ) : ?>
				<span class="label"><?php echo esc_html( $args['label'] ); ?></span>
			<?php endif; ?>
			<div class="dropdown">
				<span class="current">
					<span class="selected"><?php echo esc_html( $current['native_name'] ) ?></span>
					<?php echo \Durotan\Icon::get_svg( 'chevron-bottom' ) ?>
				</span>
				<div class="language-dropdown durotan-header-list__dropdown">
					<ul>
						<?php echo implode( "\n\t", $lang_list ); ?>
					</ul>
				</div>
			</div>
		</div>

		<?php
	}

	/**
	 * Social hamburger menu
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function social_hamburger_menu() {
		if ( Helper::get_option( 'hamburger_socials' ) ) {
			$socials = Helper::get_option( 'hamburger_socials_share' );
			if ( ( ! empty( $socials ) ) ) {
				$output = array();

				foreach ( $socials as $social => $value ) {
					$output[] = \Durotan\Addons\Helper::share_link( $value );
				}
				echo sprintf( '<div class="durotan-socials__humburger-menu">%s</div>', implode( '', $output ) );
			}
		}
	}

	/**
	 * Durotan loading.
	 *
	 */
	public static function durotan_loading() {
		echo '<div class="durotan-loading__background"><div class="durotan-loading"></div></div>';
	}

	/**
	 * Fire the wp_body_open action.
	 *
	 * Added for backward compatibility to support pre-5.2.0 WordPress versions.
	 *
	 * @since Twenty Nineteen 1.4
	 */
	public static function wp_body_open() {
		/**
		 * Triggered after the opening <body> tag.
		 *
		 * @since Twenty Nineteen 1.4
		 */
		do_action( 'wp_body_open' );
	}
}
