<?php
/**
 * Template part for displaying the wishlist icon
 *
 * @package Durotan
 */

use Durotan\Helper;

if ( ! function_exists( 'WC' ) ) {
	return;
}

if( class_exists( '\WCBoost\Wishlist\Helper' ) ) {
	$link  = wc_get_page_permalink( 'wishlist' );
	$count = \WCBoost\Wishlist\Helper::get_wishlist()->count_items();
} elseif ( defined( 'YITH_WCWL' ) ) {
	$link         = get_permalink( yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) ) );
	$count        = intval( YITH_WCWL()->count_products() );
} else {
	return;
}

if ( Helper::get_option( 'header_wishlist_link' ) ) {
	$link = Helper::get_option( 'header_wishlist_link' );
}

?>

<div class="header-wishlist">
	<a href="<?php echo esc_url( $link ); ?>">
		<span class="header-wishlist__icon">
			<?php echo \Durotan\Icon::get_svg( 'heart', '', 'shop' ); ?>
			<span class="header-wishlist__counter header-counter"><?php echo intval( $count ); ?></span>
		</span>
	</a>
</div>