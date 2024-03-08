<?php
/**
 * Durotan blog helper functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan\Blog;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Durotan Blog Helper initial
 *
 */
class Helper {
	/**
	 * Meta cat
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function meta_cat() {
		$cats = get_the_category();

		$count = count( $cats );

		$i      = 0;
		$number = apply_filters( 'durotan_meta_cat_number', 0 );

		$cat_html = '';
		$output   = array();

		if ( ! is_wp_error( $cats ) && $cats ) {
			foreach ( $cats as $cat ) {
				$output[] = sprintf( '<a href="%s">%s</a>', esc_url( get_category_link( $cat->term_id ) ), esc_html( $cat->cat_name ) );

				$i ++;

				if ( $i > $number || $i > ( $count - 1 ) ) {
					break;
				}

				$output[] = ', ';
			}

			$cat_html = sprintf( '<span class="meta-cat">%s</span>', implode( '', $output ) );

		}

		echo ! empty( $cat_html ) ? $cat_html : '';
	}

	/**
	 * Meta cat
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function meta_date() {
		if(\Durotan\Helper::get_option( 'blog_type' ) !== 'classic'){
			echo sprintf( '<time class="meta-date published updated" datetime="2018-08-23T17:58:20+00:00">%s</time>', esc_html( get_the_date() ) );
		}else{
			echo sprintf( '<span class="meta-date">%s <time class="published updated" datetime="2018-08-23T17:58:20+00:00">%s</time></span>', esc_html__('on ', 'durotan'), esc_html( get_the_date() ) );
		}
	}

	/**
	 * Meta author
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function meta_author() {
		$byline = sprintf(
			esc_html_x( ' By %s', 'post author', 'durotan' ),
			'<a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>'
		);

		echo sprintf( '<span class="meta-author">%s</span>', $byline );
	}

	/**
	 * Meta comment
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function meta_comment() {
		$number_comment = get_comments_number();
		echo sprintf(
			'<span class="meta-comment">%s %d %s</span>',
			\Durotan\Icon::get_svg( 'comments' ),
			$number_comment,
			esc_html__( 'Comments', 'durotan' )
		);

	}

	/**
	 * Meta view
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function meta_view() {
		$number_view = \Durotan\Helper::get_post_views( get_the_ID() );
		echo sprintf(
			'<span class="meta-view">%s %d %s</span>',
			\Durotan\Icon::get_svg( 'eye', '', 'shop' ),
			$number_view,
			esc_html__( 'Viewed', 'durotan' )
		);
	}

	/**
	 * Meta divider
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function meta_divider() {
		echo '<span class="meta-divider">/</span>';
	}

	/**
	 * Adds a title to posts and pages that are missing titles.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function durotan_post_title($title){
		return '' === $title ? esc_html_x( 'Untitled', 'Added to posts and pages that are missing titles', 'durotan' ) : $title;
	}

	/**
	 * Pagination view
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function durotan_pagination() {
		$blog_pagination_type = \Durotan\Helper::get_option( 'blog_pagination_type' );
		if( $blog_pagination_type === 'load' )
		{
			echo \Durotan\Helper::load_pagination();
		}else {
			echo \Durotan\Helper::numeric_pagination();
		}
	}
}
