<?php
/**
 * Template part for displaying the account icon
 *
 * @package Durotan
 */

use Durotan\Helper;

if ( ! function_exists( 'WC' ) ) {
	return;
}

?>

<div class="header-account header-account--<?php echo esc_attr( Helper::get_option( 'header_account_type' ) ); ?>">
	<?php if ( is_user_logged_in() ) : ?>
		<?php if ( Helper::get_option( 'header_layout' ) === 'v7' ) : ?>
			<?php echo \Durotan\Icon::get_svg( 'account', '', 'shop' ); ?>
			<div class="header-account__links">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>"><?php echo esc_html__( 'My account', 'durotan' ); ?></a>
			</div>
		<?php else : ?>
			<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>" class="account-<?php echo esc_attr( Helper::get_option( 'header_account_type' ) ); ?>">
				<?php
				if ( 'icon' == Helper::get_option( 'header_account_type' ) ) {
					echo \Durotan\Icon::get_svg( 'account', '', 'shop' );
				} else {
					echo wp_kses_post( Helper::get_option( 'header_account_type_text' ) );
				}
				?>
			</a>

			<ul class="account-links">
				<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
					<li class="account-link--<?php echo esc_attr( $endpoint ); ?>">
						<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" class="underline-hover"><?php echo esc_html( $label ); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	<?php else : ?>
		<?php if ( Helper::get_option( 'header_layout' ) == 'v7' ) : ?>
			<?php echo \Durotan\Icon::get_svg( 'account', '', 'shop' ); ?>
			<div class="header-account__links">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>"><?php echo esc_html__( 'Sign in', 'durotan' ); ?></a>
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>"><?php echo esc_html__( 'Sign up', 'durotan' ); ?></a>
			</div>
		<?php else : ?>
			<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>">
				<?php
				if ( 'icon' == Helper::get_option( 'header_account_type' ) ) {
					echo \Durotan\Icon::get_svg( 'account', '', 'shop' );
				} else {
					echo wp_kses_post( Helper::get_option( 'header_account_type_text' ) );
				}
				?>
			</a>
		<?php endif; ?>
	<?php endif; ?>
</div>