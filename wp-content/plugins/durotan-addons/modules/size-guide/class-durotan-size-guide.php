<?php

namespace Durotan\Addons\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main class of plugin for admin
 */
class Size_Guide {

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

	const POST_TYPE     = 'durotan_size_guide';
	const OPTION_NAME   = 'durotan_size_guide';


	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );

		// Display size guide.
		add_action( 'woocommerce_before_single_product', array( $this, 'display_size_guide'  ) );
	}

	public function scripts() {
		wp_enqueue_style( 'durotan-size-guide-content', DUROTAN_ADDONS_URL . 'modules/size-guide/assets/css/size-guide.css' );
		wp_enqueue_script('durotan-size-guide-content', DUROTAN_ADDONS_URL . 'modules/size-guide/assets/js/size-guide-tab.js');
	}

	/**
	 * Get option of size guide.
     *
	 * @since 1.0.0
	 *
	 * @param string $option
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function get_option( $option = '', $default = false ) {
		if ( ! is_string( $option ) ) {
			return $default;
		}

		if ( empty( $option ) ) {
			return get_option( self::OPTION_NAME, $default );
		}

		return get_option( sprintf( '%s_%s', self::OPTION_NAME, $option ), $default );
	}

	/**
	 * Hooks to display size guide.
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function display_size_guide() {
		global $product;

		if ( 'yes' != $this->get_option() ) {
			return;
		}

		if ( 'yes' == $this->get_option( 'variable_only' ) && ! $product->is_type( 'variable' ) ) {
			return;
		}

		add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'size_guide_button' ), 95 );
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'size_guide_panel' ), 12 );
	}

	/**
	 * Hooks to display size guide.
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function display_size_guide_quick_view() {
		global $product;

		if ( 'yes' != $this->get_option() ) {
			return;
		}

		if ( 'yes' == $this->get_option( 'variable_only' ) && ! $product->is_type( 'variable' ) ) {
			return;
		}
		
		$this->size_guide_button();
	}

	/**
	 * Get HTML of size guide button
     *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_size_guide_button() {
		$guide_id = $this->get_product_size_guide_id();

		if ( ! $guide_id ) {
			return;
		}

		$text = $this->get_option( 'button_text' );

		$button_text = $text ? $text : esc_html__('Size Guide' , 'durotan');

		return apply_filters(
			'durotan_size_guide_button',
			sprintf(
				'<p class="product-size-guide"><a href="#" data-toggle="modal" data-target="size-guide-%d-modal" class="size-guide-button">%s %s</a></p>',
				$guide_id,
				apply_filters('durotan_size_guide_icon',\Durotan\Addons\Helper::get_svg( 'tshirt-alt', '', 'widget' )),
				$button_text
			)
		);
	}

	/**
	 * Display the button to open size guide.
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function size_guide_button() {
		echo $this->get_size_guide_button();
	}

	/**
	 * Size guide panel.
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function size_guide_panel() {
		$guide_id = $this->get_product_size_guide_id();

		if ( ! $guide_id ) {
			return;
		}
		?>
		<div id="size-guide-<?php echo esc_attr($guide_id); ?>-modal" class="size-guide-modal modal">
			<div class="modal__backdrop"></div>
			<div class="modal-content container">
				<div class="modal-header">
                    <h3 class="title"><?php esc_html_e( 'Size Chart', 'durotan' ) ?></h3>
                   <span class="modal__button-close"><?php echo \Durotan\Addons\Helper::get_svg( 'close' ) ?></span>
                </div>
				<div class="modal-size-chart durotan-scrollbar">
					<?php $this->size_guide_content(); ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Display product size guide as a tab.
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function size_guide_content() {
		$guide_id = $this->get_product_size_guide_id();

		if ( ! $guide_id ) {
			return;
		}

		$guide = get_post( $guide_id );

		echo '<div class="durotan-size-guide">';

		if ( ! empty( $guide->post_content ) ) {
			echo '<div class="durotan-size-guide--global-content">' . apply_filters( 'the_content', $guide->post_content ) . '</div>';
		}

		$size_guides = get_post_meta( $guide_id, 'size_guides', true );

		if ( ! $size_guides || ! is_array( $size_guides ) || empty( $size_guides['tables'] ) ) {
			echo '</div>';
			return;
		}

		// Display tabs.
		if ( 1 < count( $size_guides['tables'] ) ) {
			$tabs = array();

			foreach ( $size_guides['tabs'] as $index => $tab ) {
				$tabs[] = sprintf( '<li data-target="%s" class="%s">%s</li>', esc_attr( $index + 1 ), ( $index ? '' : 'active' ), esc_html( $tab ) );
			}

			echo '<div class="durotan-size-guide-tabs">';
			echo '<ul class="durotan-size-guide-tabs__nav">' . implode( '', $tabs ) . '</ul>';
			echo '<div class="durotan-size-guide-tabs__panels">';
		}

		foreach ( $size_guides['tables'] as $index => $table ) {
			echo '<div class="durotan-size-guide-tabs__panel ' . ( $index ? '' : 'active' ) . '" data-panel="' . esc_attr( $index + 1 ) . '">';

			if ( ! empty( $size_guides['names'][ $index ] ) ) {
				echo '<h4 class="durotan-size-guide__name">' . wp_kses_post( $size_guides['names'][ $index ] ) . '</h4>';
			}

			if ( ! empty( $size_guides['descriptions'][ $index ] ) ) {
				echo '<div class="durotan-size-guide__description">' . wp_kses_post( $size_guides['descriptions'][ $index ] ) . '</div>';
			}

			if ( ! empty( $table ) ) {
				$table = json_decode( $table, true );

				echo '<table class="durotan-size-guide__table">';

				foreach ( $table as $row => $columns ) {
					if ( 0 === $row ) {
						echo '<thead>';
					} elseif ( 1 === $row ) {
						echo '</thead><tbody>';
					}

					echo '<tr>';

					if ( 0 === $row ) {
						echo '<th>' . implode( '</th><th>', $columns ) . '</th>';
					} else {
						echo '<td>' . implode( '</td><td>', $columns ) . '</td>';
					}

					echo '</tr>';
				}

				echo '</tbody>';
				echo '</table>';
			}

			if ( ! empty( $size_guides['information'][ $index ] ) ) {
				echo '<div class="durotan-size-guide__info">' . wp_kses_post( $size_guides['information'][ $index ] ) . '</div>';
			}

			echo '</div>';
		}

		if ( 1 < count( $size_guides['tables'] ) ) {
			echo '</div></div>';
		}

		echo '</div>';
	}

	/**
	 * Get assigned size guide of the product.
     *
	 * @since 1.0.0
	 *
	 * @param int|object $object Product object
	 * @return object
	 */
	public function get_product_size_guide_id( $object = false ) {
		global $product;

		$_product = $object ? wc_get_product( $object ) : $product;

		if ( ! $_product ) {
			return false;
		}

		$size_guide = get_post_meta( $_product->get_id(), 'durotan_size_guide', true );

		// Return selected guide.
		if ( is_array( $size_guide ) ) {
			if ( 'none' == $size_guide['guide'] ) {
				return false;
			}

			if ( ! empty( $size_guide['guide'] ) ) {
				return $size_guide['guide'];
			}
		}


		// Get default size guide.
		$categories = $_product->get_category_ids();

		// Firstly, get size guide that assign for these categories directly.
		$guides = new \WP_Query( array(
			'post_type'      => self::POST_TYPE,
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_query '    => array(
				array(
					'key' => 'size_guide_category',
					'value' => array( 'none', 'all' ),
					'compare' => 'NOT IN',
				),
			),
			'tax_query' => array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $categories,
				),
			),
		) );

		if ( $guides->have_posts() ) {
			$id = current( $guides->posts );

			return $this->get_translated_object_id( $id, self::POST_TYPE );
		}

		// Return global guide if it is availabel.
		$guides = new \WP_Query( array(
			'post_type'              => self::POST_TYPE,
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'meta_key'               => 'size_guide_category',
			'meta_value'             => 'all',
		) );


		if ( $guides->have_posts() ) {
			$id = current( $guides->posts );

			return $this->get_translated_object_id( $id, self::POST_TYPE );
		}

		return false;
	}

	/**
	 * Get translated object ID if the WPML plugin is installed
	 * Return the original ID if this plugin is not installed
     *
	 * @since 1.0.0
	 *
	 * @param int    $id            The object ID
	 * @param string $type          The object type 'post', 'page', 'post_tag', 'category' or 'attachment'. Default is 'page'
	 * @param bool   $original      Set as 'true' if you want WPML to return the ID of the original language element if the translation is missing.
	 * @param bool   $language_code If set, forces the language of the returned object and can be different than the displayed language.
	 *
	 * @return mixed
	 */
	function get_translated_object_id( $id, $type = 'page', $original = true, $language_code = null ) {
		if ( function_exists( 'wpml_object_id_filter' ) ) {
			$id = wpml_object_id_filter( $id, $type, $original, $language_code );
		} elseif ( function_exists( 'icl_object_id' ) ) {
			$id = icl_object_id( $id, $type, $original, $language_code );
		}

		return apply_filters( 'wpml_object_id', $id, $type, $original, $language_code );
	}
}