<?php
/**
 * Template part for displaying footer extra content
 *
 * @package Durotan
 */

use Durotan\Helper;

$sections = array(
	'left'   => Helper::get_option( 'footer_extra_left' ),
	'center' => Helper::get_option( 'footer_extra_center' ),
	'right'  => Helper::get_option( 'footer_extra_right' ),
);

$sections = apply_filters( 'durotan_footer_extra_sections', $sections );
$sections = array_filter( $sections );
if ( empty( $sections ) ) {
	return;
}
?>
<div class="footer-extra footer-extra__col-<?php echo count($sections); ?>">
	<div class="footer-container <?php echo esc_attr( apply_filters( 'durotan_footer_container_class', Helper::get_option( 'footer_container' ), 'extra' ) ); ?>">
		<div class="footer-extra__wrapper">
			<?php foreach ( $sections as $section => $items ) : ?>
				<div class="footer__items footer__items--<?php echo esc_attr( $section ); ?>">
					<?php
					foreach ( $items as $item ) {
						$item['item'] = $item['item'] ? $item['item'] : key( \Durotan\Theme::instance()->get('footer')->footer_items_option() );
						\Durotan\Theme::instance()->get('footer')->footer_item( $item['item'] );
					}
					?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
