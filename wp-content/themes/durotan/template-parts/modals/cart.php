<?php
/**
 * Template part for modal cart panel
 *
 * @package Durotan
 */

use Durotan\Helper;

if ( ! function_exists( 'WC' ) ) {
	return;
}

$header_cart_site = Helper::get_option( 'header_cart_side_type' );

if ( Helper::get_option( 'header_layout' ) == 'v7' ) {
	$header_cart_site = 'side-left';
}
?>
<div id="cart-panel" class="cart-panel offscreen-panel <?php echo esc_attr( $header_cart_site == 'side-left' ? 'header-cart-side-right' : '' )  ?> ">
	<div class="offscreen-panel__backdrop"></div>
	<div class="offscreen-panel__wrapper">
		<div class="offscreen-panel__button-close">
			<?php echo \Durotan\Icon::get_svg( 'close' ); ?>
		</div>
		<div class="offscreen-panel__header">
			<label><?php echo esc_html__( 'Cart', 'durotan' ); ?><span class="cart-panel__counter"><?php echo '('. WC()->cart->get_cart_contents_count() .')'; ?></span></label>
		</div>
		<div class="offscreen-panel__content">
			<div class="widget_shopping_cart_content">
				<?php woocommerce_mini_cart(); ?>
			</div>
		</div>
	</div>
</div>