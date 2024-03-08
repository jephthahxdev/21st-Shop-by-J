<?php
/**
 * Footer functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Footer initial
 *
 */
class Footer {
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
		add_filter( 'durotan_site_footer_class', array( $this, 'add_footer_class' ) );

		add_action( 'durotan_after_open_site_footer', array( $this, 'show_footer' ) );
	}

	public function add_footer_class() {
		$classes = 'site-footer ';

		if ( Helper::get_option( 'footer_border' ) ) {
			$classes .= 'has-border';
		}

		return $classes;
	}

	/**
	 * Site footer
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function show_footer() {
		if ( ! apply_filters( 'durotan_get_footer', true ) ) {
			return;
		}

		$footer_sections = Helper::get_option( 'footer_sections' );

		foreach ( (array) $footer_sections as $footer_section ) {
			get_template_part( 'template-parts/footer/footer', $footer_section );
		}
	}

	/**
	 * Options of footer items
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function footer_items_option() {
		return apply_filters( 'durotan_footer_items_option', array(
			'copyright' => esc_html__( 'Copyright', 'durotan' ),
			'menu'      => esc_html__( 'Menu', 'durotan' ),
			'payment'   => esc_html__( 'Payments', 'durotan' ),
			'languages' => esc_html__( 'Languages', 'durotan' ),
			'currency'  => esc_html__( 'Currecies', 'durotan' ),
		) );
	}

	/**
	 * Custom template tags of footer
	 *
	 * @since 1.0.0
	 *
	 * @return  void
	 */
	public function footer_item( $item ) {
		switch ( $item ) {
			case 'copyright':
				echo '<div class="footer__copyright footer__copyright--text-uppercase">' . do_shortcode( wp_kses_post( Helper::get_option( 'footer_copyright' ) ) ) . '</div>';
				break;

			case 'menu':
				$this->footer_menu();
				break;

			case 'payment':
				$this->footer_payments();
				break;

			case 'languages':
				$this->footer_languages();
				break;

			case 'currency':
				$this->footer_currency();
				break;

			default:
				do_action( 'durotan_footer_footer_main_item', $item );
				break;
		}
	}

	/**
	 * Display menu in footer
	 *
	 * @since 1.0.0
	 *
	 * @return  void
	 */
	public function footer_menu() {
		if( ! Helper::get_option( 'footer_menu' ) ) {
			return;
		}
		echo '<div class="footer-menu">';
			wp_nav_menu( array(
				'theme_location' => '',
				'menu'  		 => Helper::get_option( 'footer_menu' ),
				'container'      => false,
				'menu_class'     => 'footer-menu__wrapper nav-menu menu',
				'depth'          => 1,
			) );
		echo '</div>';
	}

	/**
	 * Display payment in footer
	 *
	 * @since 1.0.0
	 *
	 * @return  void
	 */
	public function footer_payments() {
		$output = array();
		$images = Helper::get_option( 'footer_payment_images' );
		if ( $images ) {
			if( Helper::get_option( 'footer_payment_label' ) )
			{
				$output[] = '<span class="label">' . Helper::get_option( 'footer_payment_label' ) . '</span>';
			}

			foreach ( $images as $image ) {

				if ( ! isset( $image['image'] ) && ! $image['image'] ) {
					continue;
				}

				$image_id = $image['image'];

				if( is_numeric($image_id) ) {
					$img = wp_get_attachment_image( $image_id, 'full' );
				} else {
					$img = sprintf('<img class="attachment-full size-full" src="%s">', $image_id);
				}

				if ( isset( $image['link'] ) && ! empty( $image['link'] ) ) {
					if ( $img ) {
						$output[] = sprintf( '<span class="payment-image"><a href="%s">%s</a></span>', esc_url( $image['link'] ), $img );
					}
				} else {
					if ( $img ) {
						$output[] = sprintf( '<span class="payment-image">%s</span>', $img );
					}
				}

			}
		}

		if ( $output ) {
			printf( '<div class="footer-payments">%s</div>', implode( ' ', $output ) );
		}
	}

	/**
	 * Display languages in footer
	 *
	 * @since 1.0.0
	 *
	 * @return  void
	 */
	public function footer_languages( $no = 'footer' ) {
		$languages = function_exists( 'icl_get_languages' ) ? icl_get_languages() : array();
		$languages = apply_filters( 'wpml_active_languages', $languages );

		if( isset( $languages[$no] ) ) {
			$languages = $languages[$no];
		}

		if ( empty( $languages ) ) {
			return;
		}

		$lang_list = array();

		foreach ( (array) $languages as $code => $language ) {

			if ( $language['active'] == '1' ) {
				$current = $language['native_name'];
			}

			$lang_list[] = sprintf(
				'<li class="%s"><a href="%s">%s</a></li>',
				esc_attr( $code ) . ( $language['active'] == '1' ? ' active' : '' ),
				esc_url( $language['url'] ),
				esc_html( $language['native_name'] )
			);

		}

		if ( Helper::get_option( 'footer_languages_type' ) !== 'horizontal' ) {
			$output = sprintf(
				'<div class="dropdown"><span class="current"><span class="selected">%s</span>%s</span><ul>%s</ul></div>',
				$current,
				\Durotan\Icon::get_svg( 'chevron-bottom' ),
				implode( "\n\t", $lang_list )
			);
		} else {
			$output = sprintf(
				'<ul>%s</ul>',
				implode( "\n\t", $lang_list )
			);
		}
		?>
		<div class="footer-language">
			<div class="durotan-language durotan-language--<?php echo esc_attr( Helper::get_option( 'footer_languages_type' ) ); ?>">
				<?php echo ! empty( $output ) ? $output : ''; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Display currency in footer
	 *
	 * @since 1.0.0
	 *
	 * @return  void
	 */
	public function footer_currency() {
		if ( ! class_exists( 'WOOCS' ) ) {
			return;
		}

		global $WOOCS;

		$currencies    = $WOOCS->get_currencies();
		$currency_list = array();

		foreach ( $currencies as $key => $currency ) {
			if ( $WOOCS->current_currency == $key ) {
				array_unshift( $currency_list, sprintf(
					'<li class="%s"><a href="#" class="woocs_flag_view_item woocs_flag_view_item_current" data-currency="%s">%s %s</a></li>',
					$currencies[ $WOOCS->current_currency ]['name'] === $currency['name'] ? 'active' : '',
					esc_attr( $currency['name'] ),
					esc_html( $currency['symbol'] ),
					esc_html( $currency['name'] )
				) );
			} else {
				$currency_list[] = sprintf(
					'<li class="%s"><a href="#" class="woocs_flag_view_item" data-currency="%s">%s %s</a></li>',
					$currencies[ $WOOCS->current_currency ]['name'] === $currency['name'] ? 'active' : '',
					esc_attr( $currency['name'] ),
					esc_attr( $currency['symbol'] ),
					esc_html( $currency['name'] )
				);
			}
		}

		if ( Helper::get_option( 'footer_currencies_type' ) !== 'horizontal' ) {
			$output = sprintf(
				'<div class="dropdown"><span class="current"><span class="selected">%s</span>%s</span><ul>%s</ul></div>',
				esc_html( $currencies[ $WOOCS->current_currency ]['symbol'] . ' '. $currencies[ $WOOCS->current_currency ]['name'] ),
				\Durotan\Icon::get_svg( 'chevron-bottom' ),
				implode( "\n\t", $currency_list )
			);
		}else {
			$output = sprintf(
				'<ul>%s</ul>',
				implode( "\n\t", $currency_list )
			);
		}
		?>
		<div class="footer-currency">
			<div class="durotan-currency durotan-currency--<?php echo esc_attr( Helper::get_option( 'footer_currencies_type' ) ); ?>">
				<?php echo ! empty( $output ) ? $output : ''; ?>
			</div>
		</div>
		<?php
	}

}
