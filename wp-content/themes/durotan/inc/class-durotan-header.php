<?php
/**
 * Header functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Header initial
 *
 */
class Header {
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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 15 );

		add_action( 'durotan_before_open_site_header', array( $this, 'show_header_sticky_minimized' ) );

		add_action( 'durotan_after_open_site_header', array( $this, 'show_header' ) );
		add_filter( 'durotan_site_header_class', array( $this, 'add_header_class' ) );

		// Mobile
		add_action( 'durotan_after_open_site_header', array( $this, 'mobile_header' ), 99 );

		add_action( 'durotan_header_mobile_content', array( $this, 'mobile_header_build' ) );

		add_action( 'wp_footer', array( $this, 'menu_modal' ) );
		add_action( 'wp_footer', array( $this, 'menu_mobile_modal' ) );
	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		/**
		 * Register and enqueue style
		 */
		wp_register_style( 'durotan-fonts', Helper::get_google_fonts() );
		wp_register_style( 'swiper', get_template_directory_uri() . '/assets/css/swiper.css');

		wp_enqueue_style( 'durotan', get_template_directory_uri() . '/style.css', array(
			'durotan-fonts',
			'swiper',
		), '20220501' );

		do_action( 'durotan_after_enqueue_style' );

		/**
		 * Register and enqueue scripts
		 */

		wp_register_script( 'perfect-scrollbar', get_template_directory_uri() . '/assets/js/perfect-scrollbar.js');
		wp_register_script( 'swiper', get_template_directory_uri() . '/assets/js/plugins/swiper.min.js');
		wp_register_script( 'isInViewport', get_template_directory_uri() . '/assets/js/plugins/isInViewport.min.js', array(), '20201012', true );

		wp_enqueue_script( 'durotan', get_template_directory_uri() . "/assets/js/scripts.js", array(
			'jquery',
			'swiper',
			'isInViewport',
			'imagesloaded',
		), '20210520', true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		$durotan_data = array(
			'ajax_url'             => class_exists( 'WC_AJAX' ) ? \WC_AJAX::get_endpoint( '%%endpoint%%' ) : '',
			'nonce'                => wp_create_nonce( '_durotan_nonce' ),
			'search_content_type'  => Helper::get_option( 'header_search_type' ),
			'header_search_number' => Helper::get_option( 'header_search_number' ),
			'header_ajax_search'   => intval( Helper::get_option( 'header_search_ajax' ) ),
			'mobile_landscape'     => Helper::get_option( 'mobile_landscape_product_columns' ),
			'mobile_portrait'      => Helper::get_option( 'mobile_portrait_product_columns' ),
			'footer_parallax'  	   => intval( Helper::get_option( 'footer_parallax' ) ),
		);

		$durotan_data = apply_filters( 'durotan_wp_script_data', $durotan_data );

		wp_localize_script(
			'durotan', 'durotanData', $durotan_data
		);

		do_action( 'durotan_after_enqueue_script' );
	}

	/**
	 * Display the site header
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */

	public function show_header() {
		if ( ! apply_filters( 'durotan_get_header', true ) ) {
			return;
		}

		if ( 'default' == Helper::get_option( 'header_type' ) ) {
			$this->prebuild_header( Helper::get_option( 'header_layout' ) );
		} else {
			// Header main.
			$sections = array(
				'left'   => Helper::get_option( 'header_main_left' ),
				'center' => Helper::get_option( 'header_main_center' ),
				'right'  => Helper::get_option( 'header_main_right' ),
			);

			$classes = array( 'header__main', 'header__main--custom' );

			$this->get_header_contents( $sections, array( 'class' => $classes ) );

			// Header bottom.
			$sections = array(
				'left'   => Helper::get_option( 'header_bottom_left' ),
				'center' => Helper::get_option( 'header_bottom_center' ),
				'right'  => Helper::get_option( 'header_bottom_right' ),
			);

			$classes = array( 'header__bottom', 'header__bottom--custom' );

			$this->get_header_contents( $sections, array( 'class' => $classes ) );
		}
	}

	/**
	 * Display pre-build header
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function prebuild_header( $version = 'v1' ) {
		$classes[] = 'header__main';
		$classes_bottom[] = 'header__bottom';
		switch ( $version ) {
			case 'v1':
				$main_sections   = array(
					'left'   => array(
						array( 'item' => 'logo' ),
						array( 'item' => 'menu-primary' ),
					),
					'center' => array(

					),
					'right'  => array(
						array( 'item' => 'languages' ),
						array( 'item' => 'search' ),
						array( 'item' => 'account' ),
						array( 'item' => 'cart' ),
					),
				);
				$bottom_sections = array();
				$classes[] =  '';
				$classes_bottom[] =  '';
				break;
			case 'v2':
				$main_sections   = array(
					'left'   => array(
						array( 'item' => 'logo' ),
					),
					'center' => array(
					),
					'right'  => array(
						array( 'item' => 'search' ),
						array( 'item' => 'account' ),
						array( 'item' => 'cart' ),
						array( 'item' => 'hamburger' ),
					),
				);
				$bottom_sections = array();
				$classes_bottom[] =  '';
				break;
			case 'v3':
				$main_sections   = array(
					'left'   => array(
						array( 'item' => 'menu-primary' ),
					),
					'center' => array(
						array( 'item' => 'logo' ),
					),
					'right'  => array(
						array( 'item' => 'search' ),
						array( 'item' => 'currencies' ),
						array( 'item' => 'account' ),
						array( 'item' => 'cart' ),
					),
				);
				$bottom_sections = array();
				$classes[] =  'header__main--has-center';
				$classes_bottom[] =  '';
				break;
			case 'v4':
				$main_sections   = array(
					'left'   => array(
						array( 'item' => 'hamburger' ),
					),
					'center' => array(
						array( 'item' => 'logo' ),
					),
					'right'  => array(
						array( 'item' => 'search' ),
						array( 'item' => 'account' ),
						array( 'item' => 'cart' ),
					),
				);
				$bottom_sections = array();
				$classes[] =  'header__main--has-center';
				$classes_bottom[] =  '';
				break;
			case 'v5':
				$main_sections   = array(
					'left'   => array(
						array( 'item' => 'menu-primary' ),
					),
					'center' => array(
						array( 'item' => 'logo' ),
					),
					'right'  => array(
						array( 'item' => 'currencies' ),
						array( 'item' => 'search' ),
						array( 'item' => 'account' ),
						array( 'item' => 'wishlist' ),
						array( 'item' => 'cart' ),
					),
				);
				$bottom_sections = array();
				$classes[] =  'header__main--has-center';
				$classes_bottom[] =  '';
				break;
			case 'v6':
				$main_sections   = array(
					'left'   => array(
						array( 'item' => 'search' ),
					),
					'center' => array(
						array( 'item' => 'logo' ),
					),
					'right'  => array(
						array( 'item' => 'currencies' ),
						array( 'item' => 'account' ),
						array( 'item' => 'wishlist' ),
						array( 'item' => 'cart' ),
					),
				);
				$bottom_sections = array(
					'left'   => array(
					),
					'center' => array(
						array( 'item' => 'menu-primary' ),
					),
					'right'  => array(
					),
				);
				$classes[] =  'header__main--has-center';
				$classes_bottom[] =  '';
				break;
			case 'v7':
				$main_sections   = array(
					'left'   => array(
						array( 'item' => 'logo' ),
						array( 'item' => 'search' ),
						array( 'item' => 'cart' ),
						array( 'item' => 'menu-primary' ),
						array( 'item' => 'menu-secondary' ),
					),
					'center' => array(
					),
					'right'  => array(
						array( 'item' => 'account' ),
					),
				);
				$bottom_sections = array();
				$classes[] =  '';
				$classes_bottom[] =  '';
				break;
			case 'v8':
				$main_sections   = array(
					'left'   => array(
						array( 'item' => 'logo' ),
						array( 'item' => 'menu-primary' ),
					),
					'center' => array(
					),
					'right'  => array(
						array( 'item' => 'search' ),
						array( 'item' => 'account' ),
						array( 'item' => 'cart' ),
						array( 'item' => 'hamburger' ),
					),
				);
				$bottom_sections = array();
				$classes_bottom[] =  '';
				break;
			case 'v9':
				$main_sections   = array(
					'left'   => array(
						array( 'item' => 'logo' ),
					),
					'center' => array(
					),
					'right'  => array(
						array( 'item' => 'header-bar' ),
						array( 'item' => 'search' ),
						array( 'item' => 'account' ),
						array( 'item' => 'cart' ),
					),
				);
				$bottom_sections = array(
					'left'   => array(
						array( 'item' => 'menu-primary' ),
					),
					'center' => array(
					),
					'right'  => array(
						array( 'item' => 'languages' ),
						array( 'item' => 'currencies' ),
					),
				);
				$classes[] =  '';
				$classes_bottom[] =  '';
				break;
			case 'v10':
				$main_sections   = array(
					'left'   => array(
						array( 'item' => 'logo' ),
					),
					'center' => array(
						array( 'item' => 'menu-primary' ),
					),
					'right'  => array(
						array( 'item' => 'search' ),
						array( 'item' => 'account' ),
						array( 'item' => 'cart' ),
					),
				);
				$bottom_sections = array();
				$classes[] =  '';
				$classes_bottom[] =  '';
				break;
			default:
				$main_sections   = array();
				$bottom_sections = array();
				$classes[] =  '';
				$classes_bottom[] =  '';
				break;
		}

		$this->get_header_contents( $main_sections, array( 'class' => $classes ) );

		$this->get_header_contents( $bottom_sections, array( 'class' => $classes_bottom ) );
	}

	/**
	 * Display header items
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function get_header_contents( $sections, $atts = array() ) {
		if ( false == array_filter( $sections ) ) {
			return;
		}

		$classes = array();
		if ( isset( $atts['class'] ) ) {
			$classes = (array) $atts['class'];
			unset( $atts['class'] );
		}

		if ( empty( $sections['left'] ) && empty( $sections['right'] ) ) {
			unset( $sections['left'] );
			unset( $sections['right'] );
		}

		if ( ! empty( $sections['center'] ) ) {
			$classes[]    = 'has-center';
			$center_items = wp_list_pluck( $sections['center'], 'item' );

			if ( in_array( 'logo', $center_items ) ) {
				$classes[] = 'logo-center';
			}

			if ( in_array( 'menu-primary', $center_items ) ) {
				$classes[] = 'menu-center';
			}

			if ( empty( $sections['left'] ) && empty( $sections['right'] ) ) {
				$classes[] = 'no-sides';
			}
		} else {
			$classes[] = 'no-center';
			unset( $sections['center'] );

			if ( empty( $sections['left'] ) ) {
				unset( $sections['left'] );
			}

			if ( empty( $sections['right'] ) ) {
				unset( $sections['right'] );
			}
		}
		$attr = $container_width = '';
		foreach ( $atts as $name => $value ) {
			$attr .= ' ' . $name . '=' . esc_attr( $value ) . '';
		}

		if ( Helper::get_option( 'header_layout' ) != 'v7' ) {
			$container_width = Helper::get_option( 'header_width' );
		}

		?>
        <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?> <?php echo esc_attr( $attr ); ?>">
            <div class="header__container <?php echo esc_attr( apply_filters( 'durotan_header_container_class', $container_width ) ); ?>">
				<div class="header__wrapper">
					<?php foreach ( $sections as $section => $items ) : ?>

						<div class="header__items header__items--<?php echo esc_attr( $section ) ?>">
							<?php $this->get_header_items( $items ); ?>
						</div>

					<?php endforeach; ?>
				</div>
            </div>
        </div>
		<?php
	}

	/**
	 * Display header items
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function get_header_items( $items ) {
		if ( empty( $items ) ) {
			return;
		}

		foreach ( $items as $item ) {
			if ( ! isset( $item['item'] ) ) {
				continue;
			}

			$item['item']  = $item['item'] ? $item['item'] : key( $this->get_header_items_option() );
			$template_file = $item['item'];

			switch ( $item['item'] ) {
				case 'hamburger':
					\Durotan\Theme::instance()->set_prop( 'modals', $item['item'] );
					break;

				case 'compare':
					if( ! class_exists( '\WCBoost\ProductsCompare\Plugin' ) ) {
						$template_file = '';
						break;
					}
			}

			if ( $template_file ) {
				get_template_part( 'template-parts/headers/' . $template_file );
			}
		}
	}

	/**
	 * Options of header items
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_header_items_option() {
		return apply_filters( 'durotan_header_items_option', array(
			'0'              	=> esc_html__( 'Select a item', 'durotan' ),
			'logo'           	=> esc_html__( 'Logo', 'durotan' ),
			'menu-primary'   	=> esc_html__( 'Primary Menu', 'durotan' ),
			'menu-secondary'   	=> esc_html__( 'Secondary Menu', 'durotan' ),
			'search'         	=> esc_html__( 'Search', 'durotan' ),
			'hamburger'      	=> esc_html__( 'Hamburger Icon', 'durotan' ),
			'account'  		 	=> esc_html__( 'Account', 'durotan' ),
			'cart'  		 	=> esc_html__( 'Cart', 'durotan' ),
			'wishlist'		 	=> esc_html__( 'Wishlist', 'durotan' ),
			'compare'		 	=> esc_html__( 'Compare', 'durotan' ),
			'currencies'	 	=> esc_html__( 'Currencies', 'durotan' ),
			'languages'		 	=> esc_html__( 'Languages', 'durotan' ),
			'header-bar'	 	=> esc_html__( 'Header Bar', 'durotan' ),
		) );
	}

	/**
	 * Options of mobile header items
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_mobile_header_items_option() {
		return apply_filters( 'get_mobile_header_items_option', array(
			'0'              	=> esc_html__( 'Select a item', 'durotan' ),
			'logo'           	=> esc_html__( 'Logo', 'durotan' ),
			'search'         	=> esc_html__( 'Search', 'durotan' ),
			'menu'      		=> esc_html__( 'Menu Icon', 'durotan' ),
			'account'  		 	=> esc_html__( 'Account', 'durotan' ),
			'cart'  		 	=> esc_html__( 'Cart', 'durotan' ),
		) );
	}

	/**
	 * Get nav menu
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function primary_menu( $mega_menu = true ) {
		$class   = array( 'nav-menu menu' );
		$classes = implode( ' ', $class );

		if ( has_nav_menu( 'primary' ) ) {
			if ( $mega_menu == true && class_exists( '\Durotan\Addons\Modules\Mega_Menu\Walker' ) && Helper::get_option('header_layout') != 'v7' ) {
				wp_nav_menu( apply_filters( 'durotan_navigation_primary_content', array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => $classes,
					'walker' 		=>  new \Durotan\Addons\Modules\Mega_Menu\Walker()
				) ) );

			} else {
				wp_nav_menu( apply_filters( 'durotan_navigation_primary_content', array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => $classes,
				) ) );
			}
		}
	}

	/**
	 * Add class to header
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function add_header_class() {
		$classes = 'site-header ';

		if ( intval( Helper::get_option( 'header_sticky' ) ) && Helper::get_option('header_layout') != 'v7' ) {
			$header_sticky_el = (array) Helper::get_option( 'header_sticky_el' );

			if( Helper::get_option('header_type') == 'custom' ) {
				if ( ! in_array( 'header_main', $header_sticky_el ) ) {
					$classes .= ' header-main-no-sticky';
				}

				if ( ! in_array( 'header_bottom', $header_sticky_el ) ) {
					$classes .= ' header-bottom-no-sticky';
				}
			} else {
				$classes .= ' header-bottom-no-sticky';
			}

		}

		if ( ! get_post_meta( \Durotan\Helper::get_post_ID(), 'durotan_hide_header_border', true ) && Helper::get_option( 'header_layout' )  != 'v7' ) {
			$class_border = 'standard';
			if ( get_post_meta( \Durotan\Helper::get_post_ID(), 'durotan_header_border_width', true ) == 'full-width' ) {
				$class_border = 'full-width';
			}
			$classes .= ' site-header__border site-header__border--' . $class_border;
		}

		return apply_filters( 'durotan_header_class', $classes );
	}

	/**
	 * Add menu modal
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function menu_modal() {
		if ( ! apply_filters( 'durotan_get_header', true ) ) {
			return;
		}

		$modals = Theme::instance()->get_prop( 'modals' );

		if ( ! in_array( 'hamburger', $modals ) ) {
			return;
		}

		get_template_part( 'template-parts/modals/menu' );
	}

	/**
	 * Mobile header.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function mobile_header() {
		if ( ! apply_filters( 'durotan_get_header_mobile', true ) ) {
			return;
		}

		?>
		<?php get_template_part( 'template-parts/mobile/header-mobile' ); ?>

		<?php
	}

	/**
	 * Mobile header.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function mobile_header_build() {

		if ( 'default' == Helper::get_option( 'mobile_header_type' ) ) {
			$this->prebuild_mobile_header( Helper::get_option( 'mobile_header_layout' ) );
		} else {
			// Header main.
			$sections = array(
				'left'   => Helper::get_option( 'mobile_header_left' ),
				'center' => Helper::get_option( 'mobile_header_center' ),
				'right'  => Helper::get_option( 'mobile_header_right' ),
			);

			$classes = array( 'header__mobile--custom' );

			$this->get_mobile_header_contents( $sections, array( 'class' => $classes ) );
		}
	}

	/**
	 * Display pre-build header
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function prebuild_mobile_header( $version = 'v1' ) {
		$classes[] = '';
		switch ( $version ) {
			case 'v1':
				$sections   = array(
					'left'   => array(
						array( 'item' => 'menu' ),
						array( 'item' => 'logo' ),
					),
					'center' => array(
					),
					'right'  => array(
						array( 'item' => 'search' ),
						array( 'item' => 'account' ),
						array( 'item' => 'cart' ),
					),
				);
				$classes[] =  'v1';
				break;
			case 'v2':
				$sections   = array(
					'left'   => array(
						array( 'item' => 'logo' ),
					),
					'center' => array(
					),
					'right'  => array(
						array( 'item' => 'search' ),
						array( 'item' => 'account' ),
						array( 'item' => 'cart' ),
						array( 'item' => 'menu' ),
					),
				);
				$classes[] =  'v2';
				break;
			case 'v3':
				$sections   = array(
					'left'   => array(
						array( 'item' => 'menu' ),
					),
					'center' => array(
						array( 'item' => 'logo' ),
					),
					'right'  => array(
						array( 'item' => 'search' ),
						array( 'item' => 'cart' ),
					),
				);
				$classes[] =  'v3';
				break;

			default:
				$sections   = array();
				$classes[] =  '';
				break;
		}

		$this->get_mobile_header_contents( $sections, array( 'class' => $classes ) );
	}

	/**
	 * Display header items
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function get_mobile_header_contents( $sections, $atts = array() ) {
		if ( false == array_filter( $sections ) ) {
			return;
		}

		$classes = array();
		if ( isset( $atts['class'] ) ) {
			$classes = (array) $atts['class'];
			unset( $atts['class'] );
		}

		if ( empty( $sections['left'] ) && empty( $sections['right'] ) ) {
			unset( $sections['left'] );
			unset( $sections['right'] );
		}

		if ( ! empty( $sections['center'] ) ) {
			$classes[]    = 'has-center';
			$center_items = wp_list_pluck( $sections['center'], 'item' );

			if ( in_array( 'logo', $center_items ) ) {
				$classes[] = 'logo-center';
			}

			if ( empty( $sections['left'] ) && empty( $sections['right'] ) ) {
				$classes[] = 'no-sides';
			}
		} else {
			$classes[] = 'no-center';
			unset( $sections['center'] );

			if ( empty( $sections['left'] ) ) {
				unset( $sections['left'] );
			}

			if ( empty( $sections['right'] ) ) {
				unset( $sections['right'] );
			}
		}
		$attr = '';
		foreach ( $atts as $name => $value ) {
			$attr .= ' ' . $name . '=' . esc_attr( $value ) . '';
		}

		?>
		<div class="header__container <?php echo esc_attr( Helper::get_option( 'header_width' ) ); ?><?php echo implode( ' ', $classes ); ?>">
			<div class="header__wrapper">
				<?php foreach ( $sections as $section => $items ) : ?>

					<div class="header__items header__items--<?php echo esc_attr( $section ) ?>">
						<?php $this->get_mobile_header_items( $items ); ?>
					</div>

				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Display header items
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function get_mobile_header_items( $items ) {
		if ( empty( $items ) ) {
			return;
		}

		foreach ( $items as $item ) {
			if ( ! isset( $item['item'] ) ) {
				continue;
			}

			$item['item']  = $item['item'] ? $item['item'] : key( $this->get_header_items_option() );
			$template_file = $item['item'];

			switch ( $item['item'] ) {
				case 'hamburger':
					\Durotan\Theme::instance()->set_prop( 'modals', $item['item'] );
					break;
			}

			if ( $template_file ) {
				get_template_part( 'template-parts/mobile/headers/' . $template_file );
			}
		}
	}

	/**
	 * Display mobile header left
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function mobile_header_left() {
		echo '<div class="header__items header__items--left">';
			get_template_part( 'template-parts/mobile/headers/menu' );
			get_template_part( 'template-parts/headers/logo' );
		echo '</div>';
	}

	/**
	 * Display mobile header right
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function mobile_header_icons() {
		echo ' <div class="header__items header__items--right">';
			get_template_part( 'template-parts/mobile/headers/search' );
			get_template_part( 'template-parts/mobile/headers/account' );
			get_template_part( 'template-parts/mobile/headers/cart' );
		echo '</div>';
	}

	/**
	 * Add menu mobile modal
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function menu_mobile_modal() {
		if ( ! apply_filters( 'durotan_get_header_mobile', true ) ) {
			return;
		}

		$classes = '';
		if ( Helper::get_option( 'mobile_menu_side_type' ) == 'side-right' ) {
			$classes = 'slide-right';
		}
		?>
        <div id="mobile-menu-modal" class="mobile-menu-panel offscreen-panel durotan-menu-mobile-modal <?php echo esc_attr( $classes )?>">
			 <div class="offscreen-panel__backdrop"></div>
            <div class="offscreen-panel__wrapper">
				<div class="offscreen-panel__header">
					<?php get_template_part( 'template-parts/headers/logo' ); ?>
					<a href="#" class="offscreen-panel__button-close">
						<?php echo \Durotan\Icon::get_svg( 'close' ); ?>
					</a>
				</div>
                <div class="offscreen-panel__content modal-content">
                    <nav class="hamburger-navigation menu-mobile-navigation">
						<?php
							$class = array( 'nav-menu', 'menu', 'click-item' );
							$classes = implode( ' ', $class );
							$menu = has_nav_menu( 'mobile' ) ? 'mobile' : 'primary';

							$arg = array(
								'theme_location' => $menu,
								'container'      => null,
								'fallback_cb'    => 'wp_page_menu',
								'menu_class'     => $classes,
							);

							wp_nav_menu( $arg );
						?>
                    </nav>
                    <div class="offscreen-panel__footer">
						<?php
							if ( intval( Helper::get_option( 'mobile_menu_show_socials' ) ) ) {
								Helper::social_hamburger_menu();
							}

							if ( intval( Helper::get_option( 'mobile_menu_show_copyright' ) ) ) {
								echo '<div class="durotan-menu__copyright">' . do_shortcode( wp_kses_post( Helper::get_option( 'footer_copyright' ) ) ) . '</div>';
							}
						?>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}

	/**
	 * Display the site header sticky minimized
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */

	public function show_header_sticky_minimized() {
		if ( ! intval( Helper::get_option( 'header_sticky' ) ) ) {
			return;
		}

		if ( get_post_meta( Helper::get_post_ID(), 'durotan_header_background', true ) == 'transparent' ) {
			return;
		}

		echo '<div id="site-header-minimized"></div>';
	}
}
