<?php
/**
 * Single functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan\Blog;

use Durotan\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Single initial
 */
class Post {
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
		add_action( 'durotan_after_open_site_content', array( $this, 'open_entry_header' ), 10 );
		add_action( 'durotan_after_open_site_content', array( $this, 'close_entry_header' ), 50 );

		add_action( 'durotan_after_open_site_content', array( $this, 'get_entry_meta' ), 20 );
		add_action( 'durotan_after_open_site_content', array( $this, 'get_title' ), 30 );
		add_action( 'durotan_after_open_site_content', array( $this, 'get_post_thumbnail' ), 40 );

		add_action( 'durotan_after_open_post_content', array( $this, 'set_post_views' ) );
		add_action( 'durotan_after_open_post_content', array( $this, 'open_get_content' ), 100 );

		add_action( 'durotan_before_close_post_content', array( $this, 'close_get_content' ), 1 );
		add_action( 'durotan_before_close_post_content', array( $this, 'open_entry_footer' ), 10 );
		add_action( 'durotan_before_close_post_content', array( $this, 'meta_tag' ), 20 );
		add_action( 'durotan_before_close_post_content', array( $this, 'meta_socials' ), 30 );
		add_action( 'durotan_before_close_post_content', array( $this, 'close_entry_footer' ), 50 );

		add_action( 'durotan_after_close_post_content', array( $this, 'meta_author' ) );
	}

	/**
	 * Set post views
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function set_post_views() {
		if( is_single() )
		{
			Helper::set_post_views( get_the_ID() );
		}
	}

	/**
	 * Open entry header
	 *
	 * @since 1.0.0
	 *
	 * @return void*
	 *
	 */
	public function open_entry_header() {
		if ( is_sticky( get_the_ID() ) ) {
			$classes = 'sticky';
		}else {
			$classes = '';
		}
		printf( '<header class="entry-header single-post__header durotan-container text-center %s">', esc_attr( $classes ) );
	}

	/**
	 * Close entry header
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 *
	 */
	public function close_entry_header() {
		echo '</header><!-- .entry-header -->';
	}

	/**
	 * Open entry footer
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function open_entry_footer() {
		if ( has_tag() == false && ! class_exists( '\Durotan\Addons\Helper' ) && ! method_exists( '\Durotan\Addons\Helper','share_link' ) ) {
			return;
		}

		echo '<footer class="entry-footer single-post__footer">';
	}

	/**
	 * Close entry footer
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function close_entry_footer() {
		echo '</footer><!-- .entry-footer -->';
	}

	/**
	 * Get post thumbnail
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function get_post_thumbnail() {
		if ( ! intval( Helper::get_option( 'single_post_featured' ) ) ) {
			return;
		}

		Helper::post_thumbnail( 'durotan-featured-image' );
	}

	/**
	 * Get title
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function get_title() {
		the_title( '<h1 class="entry-title single-post__title">', '</h1>' );
	}

	/**
	 * Get entry meta
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function get_entry_meta() {
		echo '<div class="entry-meta single-post__meta">';

			\Durotan\Blog\Helper::meta_cat();
			\Durotan\Blog\Helper::meta_divider();
			\Durotan\Blog\Helper::meta_date();

		echo '</div>';
	}

	/**
	 * Meta author
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function meta_author() {
		$author_box = Helper::get_option( 'author_box' );

		if ( $author_box == 0 ) return;

		$user_meta = get_userdata( get_the_author_meta( 'ID' ) );
		$user_roles = $user_meta->roles;
		$avatar = sprintf( '<div class="durotan-author-box__avatar"><img src="%s" alt="%s"></div>', esc_url( get_avatar_url( 'ID' ) ), esc_attr__('Banner', 'durotan') );
		$author_name = sprintf( '<div class="author-name">%s</div>',  get_the_author_link() );
		$author_role = sprintf( '<div class="author-job">%s</div>', $user_roles[0] );
		$author_des = sprintf( '<div class="author-desc">%s</div>', esc_html( get_the_author_meta( 'description' ) ) );

		echo sprintf( '<div class="durotan-author-box">%s<div class="durotan-author-box__info">%s %s %s</div></div>', $avatar, $author_name, $author_role, $author_des );
	}

	/**
	 * Meta tag
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function meta_tag() {
		if ( has_tag() == false ) {
			return;
		}

		if ( has_tag() ) :
			the_tags( '<div class="single-post__tags-links">', ' ', '</div>' );
		endif;
	}

	/**
	 * open get content
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function open_get_content() {
		echo '<div class="entry-content">';
	}

	/**
	 * Close get content
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function close_get_content() {
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'durotan' ),
			'after'  => '</div>',
		) );

		echo '</div>';
	}

	/**
	 * Meta tag
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function meta_socials() {
		if ( ! class_exists( '\Durotan\Addons\Helper' ) && ! method_exists( '\Durotan\Addons\Helper','share_link' )) {
			return;
		}

		$label = esc_html__( 'Share on', 'durotan' );

		if( ! intval( Helper::get_option('post_socials_toggle') ) ) {
			return;
		}

		$socials = Helper::get_option( 'post_socials_share' );
		if ( ( ! empty( $socials ) ) ) {
			$output = array();

			foreach ( $socials as $social => $value ) {
				$output[] = \Durotan\Addons\Helper::share_link( $value );
			}
			echo sprintf( '<div class="durotan-social-links durotan-social-links--fill"><label>%s</label>%s</div>', $label, implode( '', $output ) );
		};
	}
}
