<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$classes = wc_get_product_class( '', $product  );
$classes = apply_filters( 'durotan_single_product_summary_classes', $classes )

?>
<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">

	<?php
	/**
	 * Hook: woocommerce_before_single_product_summary.
	 *
	 * @hooked woocommerce_show_product_sale_flash - 10
	 * @hooked woocommerce_show_product_images - 20
	 */
	do_action( 'durotan_woocommerce_before_single_product_summary' );
	?>

	<div class="summary entry-summary">
		<?php
		/**
		 * Hook: woocommerce_single_product_summary.
		 *
		 * @hooked woocommerce_template_single_title - 5
		 * @hooked woocommerce_template_single_rating - 10
		 * @hooked woocommerce_template_single_price - 15
		 * @hooked woocommerce_template_single_add_to_cart - 20
		 * @hooked woocommerce_template_single_excerpt - 30
		 * @hooked woocommerce_template_single_meta - 35
		 * @hooked woocommerce_template_single_sharing - 40
		 */
		do_action( 'durotan_woocommerce_single_product_summary' );
		?>
	</div>

	<?php
	/**
	 * Hook: durotan_after_single_product_summary.
	 */
	do_action( 'durotan_woocommerce_after_single_product_summary' );
	?>
</div>
