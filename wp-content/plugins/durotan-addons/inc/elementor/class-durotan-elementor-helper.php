<?php
/**
 * Elementor Helper init
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Durotan
 */

namespace Durotan\Addons\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Helper {

	/**
	 * Instance
	 *
	 * @var $instance
	 */
	private static $instance;

	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Render link control output
	 *
	 * @since 1.0.0
	 *
	 * @param       $link_key
	 * @param       $url
	 * @param       $content
	 * @param array $attr
	 *
	 * @return string
	 */
	public static function control_url( $link_key, $url, $content, $attr = [] ) {
		$attr_default = [];
		if ( isset( $url['url'] ) && $url['url'] ) {
			$attr_default['href'] = $url['url'];
		}

		if ( isset( $url['is_external'] ) && $url['is_external'] ) {
			$attr_default['target'] = '_blank';
		}

		if ( isset( $url['nofollow'] ) && $url['nofollow'] ) {
			$attr_default['rel'] = 'nofollow';
		}

		$attr = wp_parse_args( $attr, $attr_default );

		$tag = 'a';

		if ( empty( $attr['href'] ) ) {
			$tag = 'span';
		}

		$attributes = [];

		foreach ( $attr as $name => $v ) {
			$attributes[] = $name . '="' . esc_attr( $v ) . '"';
		}

		return sprintf( '<%1$s %2$s>%3$s</%1$s>', $tag, implode( ' ', $attributes ), $content );
	}

	/**
	 * Retrieve the list of taxonomy
	 *
	 * @since 1.0.0
	 *
	 * @return array Widget categories.
	 */
	public static function taxonomy_list( $taxonomy = 'product_cat' ) {
		$output = array();
		$categories = get_categories(
			array(
				'taxonomy' => $taxonomy,
			)
		);

		foreach ( $categories as $category ) {
			$output[ $category->slug ] = $category->name;
		}

		return $output;
	}

	/**
	 * Retrieve the list of tags
	 *
	 * @since 1.0.0
	 *
	 * @return array Widget categories.
	 */
	public static function tags_list( $tag = 'post_tag' ) {
		$output = array();
		$tags = get_tags(
			array(
				'taxonomy' => $tag,
			)
		);

		foreach ( $tags as $tag ) {
			$output[ $tag->slug ] = $tag->name;
		}

		return $output;
	}

	/**
	 * Get Instagram images
	 *
	 * @since 1.0.0
	 *
	 * @param int $limit
	 *
	 * @return array|\WP_Error
	 */
	public static function get_instagram_get_photos_by_token( $limit, $access_token ) {
		if ( empty( $access_token ) ) {
			return new \WP_Error( 'instagram_no_access_token', esc_html__( 'No access token', 'durotan' ) );
		}

		$user = self::get_instagram_user( $access_token );
		if ( ! $user || is_wp_error( $user ) ) {
			return $user;
		}

		if ( isset( $user['error'] ) ) {
			return new \WP_Error( 'instagram_no_images', esc_html__( 'Instagram did not return any images. Please check your access token', 'durotan' ) );

		} else {
			$transient_key = 'durotan_instagram_photos_' . sanitize_title_with_dashes( $user['username'] . '__' . $limit );
			$images        = get_transient( $transient_key );
			$images = apply_filters( 'durotan_get_instagram_photos', $images );

			if ( false !== $images ) {
				return $images;
			}

			$images = array();
			$next   = false;
			while ( count( $images ) < $limit ) {
				if ( ! $next ) {
					$fetched = self::fetch_instagram_media( $access_token );
				} else {
					$fetched = self::fetch_instagram_media( $next );
				}
				if ( is_wp_error( $fetched ) ) {
					break;
				}

				$images = array_merge( $images, $fetched['images'] );
				$next   = $fetched['paging']['cursors']['after'];
			}

			if ( ! empty( $images ) ) {
				set_transient( $transient_key, $images, 2 * 3600 ); // Cache for 2 hours.
			} else {
				return new \WP_Error( 'instagram_no_images', esc_html__( 'Instagram did not return any images.', 'durotan' ) );
			}
		}
	}

	/**
	 * Fetch photos from Instagram API
	 *
	 * @since 1.0.0
	 *
	 * @param  string $access_token
	 *
	 * @return array
	 */
	public static function fetch_instagram_media( $access_token ) {
		$url = add_query_arg( array(
			'fields'       => 'id,caption,media_type,media_url,permalink,thumbnail_url',
			'access_token' => $access_token,
		), 'https://graph.instagram.com/me/media' );

		$remote = wp_remote_retrieve_body( wp_remote_get( $url ) );
		$data   = json_decode( $remote, true );

		$images = array();
		if ( isset( $data['error'] ) ) {
			return new \WP_Error( 'instagram_error', $data['error']['message'] );
		} else {
			foreach ( $data['data'] as $media ) {

				$images[] = array(
					'type'    => $media['media_type'],
					'caption' => isset( $media['caption'] ) ? $media['caption'] : $media['id'],
					'link'    => $media['permalink'],
					'images'  => array(
						'thumbnail' => $media['media_url'],
						'original'  => $media['media_url'],
					),
				);

			}
		}

		return array(
			'images' => $images,
			'paging' => $data['paging'],
		);
	}

	/**
	 * Get user data
	 *
	 * @since 1.0.0
	 *
	 * @return bool|\WP_Error|array
	 */
	public static function get_instagram_user( $access_token ) {
		if ( empty( $access_token ) ) {
			return new \WP_Error( 'no_access_token', esc_html__( 'No access token', 'durotan' ) );
		}
		$transient_key = 'durotan_instagram_user_' . $access_token;
		$user = get_transient( $transient_key);
		$user = apply_filters( 'durotan_get_instagram_user', $user );
		if ( false === $user ) {
			$url  = add_query_arg( array(
				'fields'       => 'id,username',
				'access_token' => $access_token
			), 'https://graph.instagram.com/me' );
			$data = wp_remote_get( $url );
			$data = wp_remote_retrieve_body( $data );
			if ( ! $data ) {
				return new \WP_Error( 'no_user_data', esc_html__( 'No user data received', 'durotan' ) );
			}
			$user = json_decode( $data, true );
			if ( ! empty( $data ) ) {
				set_transient( $transient_key, $user, 2592000 ); // Cache for one month.
			}
		}
		return $user;
	}
}
