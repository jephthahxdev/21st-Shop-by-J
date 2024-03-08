<?php
/**
 * Load and register widgets
 *
 * @package Durotan
 */

namespace Durotan\Addons;
/**
 * Durotan theme init
 */
class Widgets {

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
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		// Include plugin files
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
	}


	/**
	 * Register widgets
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function register_widgets() {
		$this->includes();
		$this->add_actions();
	}

	/**
	 * Include Files
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function includes() {
		\Durotan\Addons\Auto_Loader::register( [
			'Durotan\Addons\Widgets\Social_Links'   			 	 => DUROTAN_ADDONS_DIR . 'inc/widgets/class-durotan-addons-socials.php',
			'Durotan\Addons\Widgets\Durotan_Categories_Widget'    	 => DUROTAN_ADDONS_DIR . 'inc/widgets/class-durotan-addons-categories.php',
			'Durotan\Addons\Widgets\Durotan_Popular_Posts_Widget'    => DUROTAN_ADDONS_DIR . 'inc/widgets/class-durotan-addons-popularposts.php',
			'Durotan\Addons\Widgets\Durotan_Instagram_Widget'   	 => DUROTAN_ADDONS_DIR . 'inc/widgets/class-durotan-addons-instagram.php',
			'Durotan\Addons\Widgets\Durotan_Currency_Widget'    	 => DUROTAN_ADDONS_DIR . 'inc/widgets/class-durotan-addons-currency.php',
			'Durotan\Addons\Widgets\Durotan_Language_Widget'    	 => DUROTAN_ADDONS_DIR . 'inc/widgets/class-durotan-addons-language.php',
		] );
	}

	/**
	 * Add Actions
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_actions() {
		register_widget( new \Durotan\Addons\Widgets\Social_Links() );
		register_widget( new \Durotan\Addons\Widgets\Durotan_Categories_Widget() );
		register_widget( new \Durotan\Addons\Widgets\Durotan_Popular_Posts_Widget() );
		register_widget( new \Durotan\Addons\Widgets\Durotan_Instagram_Widget() );

		if ( class_exists( 'WOOCS' ) ) {
			register_widget( new \Durotan\Addons\Widgets\Durotan_Currency_Widget() );
		}

		$languages = function_exists( 'icl_get_languages' ) ? icl_get_languages() : array();
		$languages = apply_filters( 'wpml_active_languages', $languages );
		if ( $languages ) {
			register_widget( new \Durotan\Addons\Widgets\Durotan_Language_Widget() );
		}
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