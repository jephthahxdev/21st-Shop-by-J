<?php
/**
 * Template part for displaying footer main
 *
 * @package Durotan
 */

use Durotan\Helper;
?>
<div class="footer-newsletter">
	<div class="footer-container <?php echo esc_attr( apply_filters( 'durotan_footer_container_class', Helper::get_option( 'footer_container' ), 'newsletter' ) ); ?>">
		<div class="footer-newsletter__wrapper">
		<?php
			if ( Helper::get_option( 'footer_newsletter_text' ) ) {
				echo sprintf('<h3 class="footer-newsletter__title">%s</h3>', wp_kses_post( Helper::get_option( 'footer_newsletter_text' ) ) );
			}
			if ( Helper::get_option( 'footer_newsletter_form' ) ) {
				echo do_shortcode( wp_kses_post( Helper::get_option( 'footer_newsletter_form' )) );
			}
		?>
		</div>
	</div>
</div>
