<?php
/**
 * Template part for displaying the compare icon
 *
 * @package Durotan
 */

use Durotan\Helper;

if ( ! function_exists( 'WC' ) ) {
	return;
}

$link  = wc_get_page_permalink( 'compare' );
$count = \WCBoost\ProductsCompare\Plugin::instance()->list->count_items();

if ( Helper::get_option( 'header_compare_link' ) ) {
	$link = Helper::get_option( 'header_compare_link' );
}

?>

<div class="header-compare">
	<a href="<?php echo esc_url( $link ); ?>">
		<span class="header-compare__icon">
			<?php echo \Durotan\Icon::get_svg( 'repeat', '', 'shop' ); ?>
			<span class="header-compare__counter header-counter"><?php echo intval( $count ); ?></span>
		</span>
	</a>
</div>