<?php
/**
 * Template part for displaying the cart icon
 *
 * @package Durotan
 */

use Durotan\Helper;

if ( ! function_exists( 'WC' ) ) {
	return;
}
$empty = '';
if ( WC()->cart->is_empty() ) {
	$empty = 'empty';
}

$header_cart 		= Helper::get_option( 'header_cart_behaviour' );
$header_cart_type 	= Helper::get_option( 'header_cart_type' );
$header_cart_total 	= Helper::get_option( 'header_cart_total' );

if ( Helper::get_option( 'header_layout' ) == 'v7' ) {
	$header_cart = 'panel';
	$header_cart_type = 'icon';
	$header_cart_total = '1';
}

$class_toggle = $header_cart === 'panel' ? 'off-canvas' : 'link';

?>
<div class="header-cart header-cart--<?php echo esc_attr( $header_cart_type ); ?>">
	<a href="<?php echo esc_url( wc_get_cart_url() ) ?>" data-toggle="<?php echo esc_attr( $class_toggle ); ?>" data-target="cart-<?php echo esc_attr( $header_cart ); ?>">
		<?php if ( $header_cart_type === 'icon' ) : ?>
			<span class="header-cart__icon">
				<?php echo \Durotan\Icon::get_svg( 'cart', '', 'shop' ); ?>
				<span class="header-cart__counter header-counter"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
			</span>
		<?php else: ?>
			<span class="header-cart__text"><?php echo wp_kses_post( Helper::get_option( 'header_cart_type_text' ) ); ?></span>
			<span class="header-cart__counter"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
		<?php endif; ?>

		<?php if ( $header_cart_total ) : ?>
			<span class="header-cart__total-price"><?php echo WC()->cart->get_cart_total(); ?></span>
		<?php endif; ?>
	</a>
	<?php if( $header_cart === 'page' && Helper::get_option( 'header_layout' ) !== 'v7' ) : ?>
		<div class="header-cart__mini-cart <?php echo esc_attr( $empty ); ?>">
			<div class="widget_shopping_cart_content">
				<?php woocommerce_mini_cart(); ?>
			</div>
		</div>
	<?php endif; ?>
</div>