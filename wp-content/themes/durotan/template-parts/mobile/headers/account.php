<?php
/**
 * Template part for displaying the account icon
 *
 * @package Durotan
 */

if ( ! function_exists( 'WC' ) ) {
	return;
}

?>

<div class="header-account header-account--icon">
	<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>" class="account-icon">
		<?php echo \Durotan\Icon::get_svg( 'account', '', 'shop' ); ?>
	</a>
</div>