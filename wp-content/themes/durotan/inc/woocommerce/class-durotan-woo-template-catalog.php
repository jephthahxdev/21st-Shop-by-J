<?php
/**
 * Hooks of product catalog.
 *
 * @package Durotan
 */

namespace Durotan\WooCommerce\Template;

use Durotan\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of Catalog page
 */
class Catalog {
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
	 * @var string shop view
	 */
	public static $catalog_view;

	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'body_class', array( $this, 'body_class' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 20 );

		// Add div shop loop
		add_action( 'woocommerce_before_shop_loop', array( $this, 'shop_content_open_wrapper' ), 60 );
		add_action( 'woocommerce_after_shop_loop', array( $this, 'shop_content_close_wrapper' ), 20 );

		// Catalog Toolbar
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

		// Remove shop page title
		add_filter( 'woocommerce_show_page_title', '__return_false' );
		// Remove Breadcrumb
		remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);

		if ( intval( Helper::get_option( 'catalog_toolbar' ) ) ) {
			add_action( 'woocommerce_before_shop_loop', array( $this, 'products_toolbar' ), 40 );
			add_action( 'woocommerce_before_shop_loop', array( $this, 'products_filter' ), 50 );
		}

		add_action( 'dynamic_sidebar_before', array( $this, 'catalog_sidebar_before_content') );

		add_action( 'dynamic_sidebar_after', array( $this, 'catalog_sidebar_after_content' ) );

		add_filter( 'durotan_primary_sidebar_classes', array( $this, 'catalog_sidebar_add_class' ) );

		// Pagination.
		remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination' );
		add_action( 'woocommerce_after_shop_loop', array( $this, 'pagination' ) );
		add_filter( 'woocommerce_pagination_args', array( $this, 'pagination_args' ) );

		add_action( 'template_redirect', array( $this, 'durotan_template_redirect' ) );

		// Category tabs
		if ( intval( Helper::get_option( 'catalog_category_tabs' ) ) ) {
			add_action( 'durotan_after_open_site_content', array( $this, 'woocommerce_category_tabs' ), 30 );
		}
	}

	/**
	 * Add 'woocommerce-active' class to the body tag.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $classes CSS classes applied to the body tag.
	 *
	 * @return array $classes modified to include 'woocommerce-active' class.
	 */
	public function body_class( $classes ) {
		$classes[] = 'durotan-catalog-page';

		if ( \Durotan\Helper::is_catalog() ) {
			$catalog_view   = isset( $_COOKIE['catalog_view'] ) ? $_COOKIE['catalog_view'] : Helper::get_option( 'catalog_toolbar_view_type' )[0];

			$classes[] = 'catalog-view-' . apply_filters( 'durotan_catalog_view', $catalog_view );

			if ( Helper::get_option( 'catalog_page_header' ) ) {
				$classes[] = 'catalog-has-pageheader';
			}
		}

		return $classes;
	}

	/**
	 * WooCommerce specific scripts & stylesheets.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function scripts() {
		wp_enqueue_script( 'durotan-product-catalog', get_template_directory_uri() . '/assets/js/woocommerce/product-catalog.js', array(
			'durotan',
		), '20210707', true );

		$durotan_catalog_data = array();

		if ( intval( Helper::get_option( 'catalog_widget_collapse_content' ) ) && \Durotan\WooCommerce\Helper::get_catalog_layout() == 'grid' ) {
			$durotan_catalog_data['catalog_widget_collapse_content'] = 1;
		}

		$durotan_catalog_data = apply_filters('durotan_get_catalog_localize_data', $durotan_catalog_data);

		wp_localize_script(
			'durotan-product-catalog', 'durotanCatalogData', $durotan_catalog_data
		);
	}

	public static function durotan_template_redirect() {
		self::$catalog_view = isset( $_COOKIE['catalog_view'] ) ? $_COOKIE['catalog_view'] : Helper::get_option( 'catalog_toolbar_view_type' )[0];
		self::$catalog_view = apply_filters('durotan_catalog_product_view', self::$catalog_view );
	}

	/**
	 * Open Shop Content
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function shop_content_open_wrapper() {
		echo '<div id="durotan-shop-content" class="durotan-shop-content">';
	}

	/**
	 * Close Shop Content
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function shop_content_close_wrapper() {
		echo '</div>';
	}

	/**
	 * Catalog products toolbar.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function products_toolbar() {
		global $wp_query;

		$els = apply_filters( 'durotan_catalog_toolbar_elements', Helper::get_option( 'catalog_toolbar_elements' ) );

		if ( empty( $els ) ) {
			return;
		}

		$filter = $filter_sidebar = $products_found = $filter = $sort_by = $view = '';

		if ( in_array( 'total_product', $els ) ) {
			$total          = $wp_query->found_posts;
			$products_found = '<div class="durotan-products-found"><span>' . $total . '</span>' . esc_html__( ' Products found', 'durotan' ) . '</div>';
		}

		$has_sidebar = apply_filters( 'durotan_get_sidebar', false );

		if ( in_array( 'filter', $els ) ) {
			$filter = $this->products_filter_button();

			if ( intval( Helper::get_option( 'catalog_toolbar_filtered' ) ) ) {
				$class = $has_sidebar ? 'hidden-xs hidden-sm' : '';
				$filter .= $this->products_filtered();
			}

			$filter = '<div class="filter-box '. esc_attr( $class ) .'">'. $filter .'</div>';
		}

		if ( intval( Helper::get_option( 'catalog_toolbar_filtered' ) ) ) {
			$filter .= $this->products_filtered();
		}

		if ( $has_sidebar ) {
			$filter_sidebar = $this->products_filter_sidebar();
		}

		if ( in_array( 'sort_by', $els ) ) {
			$sort_by = $this->products_ordering();
		}

		if ( in_array( 'view', $els ) ) {
			$view = $this->shop_view();
		}

		$left = $right = '';

		if ( ! empty( $products_found ) || ! empty( $filter ) ) {
			$left = sprintf( '<div class="catalog-toolbar__left">%s%s%s</div>', $filter, $filter_sidebar, $products_found );
		}

		if ( ! empty( $sort_by ) || ! empty( $view ) ) {
			$right = sprintf( '<div class="catalog-toolbar__right">%s%s</div>', $sort_by, $view );
		}

		printf(
			'<div class="durotan-catalog-toolbar">%s%s</div>',
			$left,
			$right
		);
	}

	/**
	 * Products sorting
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function products_ordering() {
		$catalog_orderby_options = apply_filters( 'durotan_products_filter_order_by', array(
			'menu_order' => __( 'Default sorting', 'durotan' ),
			'popularity' => __( 'Sort by popularity', 'durotan' ),
			'rating'     => __( 'Sort by average rating', 'durotan' ),
			'date'       => __( 'Sort by latest', 'durotan' ),
			'price'      => __( 'Sort by price: low to high', 'durotan' ),
			'price-desc' => __( 'Sort by price: high to low', 'durotan' ),
		) );

		// get form action url
		$form_action = \Durotan\WooCommerce\Helper::get_page_base_url();

		$orderby    = ! empty( $_GET['orderby'] ) ? $_GET['orderby'] : '';
		$order_html = $order_current = '';
		foreach ( $catalog_orderby_options as $id => $name ) {
			$url       = $form_action . '?orderby=' . esc_attr( $id );
			$css_class = '';
			if ( $orderby == $id ) {
				$css_class     = 'active';
				$order_current = $name;
			}

			$order_html .= sprintf(
				'<li><a href="%s" class="woocommerce-ordering__link %s">%s</a></li>',
				esc_url( $url ),
				esc_attr( $css_class ),
				esc_html( $name )
			);
		}

		return sprintf(
			'<div class="woocommerce-ordering">
				<span class="woocommerce-ordering__button">
					<span class="woocommerce-ordering__button-label">%s</span>
					%s
				</span>
				<ul class="woocommerce-ordering__submenu">%s</ul>
			</div>',
			! empty( $orderby ) ? $order_current : esc_html__( 'Default', 'durotan' ),
			\Durotan\Icon::get_svg( 'chevron-bottom' ),
			wp_kses_post( $order_html )
		);
	}

	public function shop_view() {
		$grid_3_current     = self::$catalog_view == 'grid-3' ? 'current' : '';
		$grid_current     	= self::$catalog_view == 'grid' ? 'current' : '';
		$list_current 		= self::$catalog_view == 'list' ? 'current' : '';

		$catalog_type = Helper::get_option( 'catalog_toolbar_view_type' );
		$output_type  = array();

		foreach ( $catalog_type as $type ) {
			$current_class = $name_icon = '';
			if ( $type == 'grid' ) {
				$current_class = $grid_current;
				$name_icon     = 'grid-alt';
			} elseif ( $type == 'grid-3' ) {
				$current_class = $grid_3_current;
				$name_icon     = 'grid';
			} elseif ( $type == 'list' ) {
				$current_class = $list_current;
				$name_icon     = 'list';
			}

			$output_type[] = sprintf(
				'<a href="#" class="%1$s %2$s" data-view="%1$s" data-type="%1$s">%3$s</a>',
				esc_attr( $type ),
				esc_attr( $current_class ),
				\Durotan\Icon::get_svg( $name_icon )
			);
		}

		return sprintf(
			'<div id="durotan-shop-view" class="durotan-shop-view">
				<div class="shop-view__icon">
					%s
				</div>
			</div>',
			implode( $output_type )
		);
	}

	/**
	 * Products filter toggle.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function products_filter_button() {
		if ( ! woocommerce_products_will_display() ) {
			return;
		}

		return sprintf(
			'<a href="#catalog-filters" class="durotan-toggle-filters catalog-toolbar-item__control" data-toggle="dropdown" data-target="catalog-filters-dropdown">
				%s%s
				<span class="text-filter">%s</span>
			</a>',
			\Durotan\Icon::get_svg( 'control-panel', 'svg-normal' ),
			\Durotan\Icon::get_svg( 'close', 'svg-active' ),
			esc_html__( 'Filter', 'durotan' )
		);
	}

	/**
	 * Products filter
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function products_filter() {
		if ( ! is_active_sidebar( 'catalog-filters-sidebar' ) ) {
			return;
		}

		?>
        <div id="catalog-filters"
             class="catalog-toolbar-filters products-filter-dropdown catalog-toolbar-filters__dropdown">
            <div class="catalog-filters-content">
				<?php dynamic_sidebar( 'catalog-filters-sidebar' ); ?>
            </div>
        </div>
		<?php
	}

	/**
	 * Displays products filtered
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function products_filtered() {
		return '<div id="durotan-products-filter__activated" class="products-filter__activated"></div>';
	}

	/**
	 * Products filter sidebar.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function products_filter_sidebar() {
		$has_sidebar = apply_filters( 'durotan_get_sidebar', false );
		if ( ! $has_sidebar ) {
			return;
		}

		return sprintf(
			'<a href="#primary-sidebar" class="durotan-toggle-filters durotan-toggle-filters--sidebar" data-toggle="off-canvas" data-target="primary-sidebar">
				%s%s
				<span class="text-filter">%s</span>
			</a>',
			\Durotan\Icon::get_svg( 'control-panel', 'svg-normal' ),
			\Durotan\Icon::get_svg( 'close', 'svg-active' ),
			esc_html__( 'Filter', 'durotan' )
		);
	}

	/**
	 * Add modal content before Widget Content
	 *
	 * @since 1.0.0
	 *
	 * @param $index
	 *
	 * @return void
	 */
	public function catalog_sidebar_before_content( $index ) {
		if ( is_admin() ) {
			return;
		}

		if ( $index != 'catalog-sidebar' ) {
			return;
		}
		?>
        <div class="offscreen-panel__backdrop"></div>
        <div class="offscreen-panel__wrapper">
        <div class="offscreen-panel__header">
            <h3 class="modal-title"><?php esc_html_e( 'Filter By', 'durotan' ) ?></h3>
             <a href="#"
                   class="offscreen-panel__button-close"><?php echo \Durotan\Icon::get_svg( 'close' ); ?></a>
        </div>
        <div class="offscreen-panel__content modal-content">
		<?php

	}

	/**
	 * Change catalog sidebar after content
	 *
	 * @since 1.0.0
	 *
	 * @param $index
	 *
	 * @return void
	 */
	public function catalog_sidebar_after_content( $index ) {
		if ( is_admin() ) {
			return;
		}

		if ( $index != 'catalog-sidebar' ) {
			return;
		}
		?>
        </div>
        </div>
		<?php

	}

	/**
	 * Change catalog sidebar after content
	 *
	 * @since 1.0.0
	 *
	 * @param $index
	 *
	 * @return void
	 */
	public function catalog_sidebar_add_class( $css ) {

		$css .= ' offscreen-panel';

		return $css;
	}

	/**
	 * Displays Category Tabs
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function woocommerce_category_tabs() {
		$number = Helper::get_option( 'catalog_category_tabs_number' );
		$container = Helper::get_option( 'catalog_width' );

		echo '<div class="durotan-product-taxonomy-list__catalog '. esc_attr( $container ) .' durotan-scrollbar">';

		\Durotan\WooCommerce\Helper::instance()->taxs_list_search( 'product_cat', $number );

		echo '</div>';
	}

	/**
	 * WooCommerce pagination arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args The pagination args.
	 *
	 * @return array
	 */
	public function pagination_args( $args ) {
		$args['prev_text'] = \Durotan\Icon::get_svg( 'chevron-left' ) . esc_html__( 'Prev', 'durotan' );
		$args['next_text'] = esc_html__( 'Next', 'durotan' ). \Durotan\Icon::get_svg( 'chevron-right' );

		return $args;
	}

	/**
	 * Products pagination.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function pagination() {
		if ( wc_get_loop_prop( 'is_shortcode' ) ) {
			woocommerce_pagination();

			return;
		}

		global $wp_query;

		$nav_type = Helper::get_option( 'product_catalog_navigation' );

		if ( 'numeric' == $nav_type ) {
			woocommerce_pagination();

		} else {

			if ( get_next_posts_link() ) {

				$classes = array(
					'woocommerce-navigation',
					'next-posts-navigation',
					'ajax-navigation',
					'ajax-' . $nav_type,
				);

				$total = $wp_query->found_posts - $wp_query->post_count;

				$nav_html = sprintf( '<span class="button-text">%s (%s)</span>', esc_html__( 'Load More', 'durotan' ), $total );

				echo '<nav class="' . esc_attr( implode( ' ', $classes ) ) . '">';
					echo '<div id="durotan-catalog-previous-ajax" class="nav-previous-ajax">';
						next_posts_link( $nav_html );
						if ( 'loadmore' == $nav_type ) {
							echo '<div class="durotan-gooey-loading">
								<div class="durotan-gooey">
									<div class="dots">
										<span></span>
										<span></span>
										<span></span>
									</div>
								</div>
							</div>';

						}else {
							echo '<div class="durotan-spinner-loading">
							<div class="bar-1"></div>
							<div class="bar-2"></div>
							<div class="bar-3"></div>
							<div class="bar-4"></div>
							<div class="bar-5"></div>
							<div class="bar-6"></div>
							<div class="bar-7"></div>
							<div class="bar-8"></div>
							</div>';
						}
					echo '</div>';
				echo '</nav>';
			}

		}
	}
}
