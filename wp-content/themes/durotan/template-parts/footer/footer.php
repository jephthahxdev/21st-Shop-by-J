<?php
/**
 * Template part for displaying footer main
 *
 * @package Durotan
 */

use Durotan\Helper;

$sections = array(
	'left'   => Helper::get_option( 'footer_main_left' ),
	'center' => Helper::get_option( 'footer_main_center' ),
	'right'  => Helper::get_option( 'footer_main_right' ),
);
/**
 * Hook: durotan_footer_main_sections
 *
 * @hooked: durotan_split_content_custom_footer - 10
 */
$sections = apply_filters( 'durotan_footer_main_sections', $sections );
$sections = array_filter( $sections );
if ( empty( $sections ) ) {
	return;
}
if ( count($sections) == 3 ) {
	if ( Helper::get_option( 'footer_container' ) === 'container' ) {
		$classes = array(
			'left' 	 => 'col-xs-12 col-sm-3 col-md-3',
			'center' => 'col-xs-12 col-sm-6 col-md-6',
			'right'	 => 'col-xs-12 col-sm-3 col-md-3',
		);
	}else {
		$classes = array(
			'left' 	 => 'col-xs-12 col-sm-5 col-md-3',
			'center' => 'col-xs-12 col-sm-2 col-md-2',
			'right'	 => 'col-xs-12 col-sm-5 col-md-6',
		);
	}
}else {
	$classes = array(
		'left' 	 => 'col-xs-12 col-sm-'. 12 / count($sections) .' col-md-' . 12 / count($sections),
		'center' => 'col-xs-12 col-sm-'. 12 / count($sections) .' col-md-' . 12 / count($sections),
		'right'	 => 'col-xs-12 col-sm-'. 12 / count($sections) .' col-md-' . 12 / count($sections),
	);
}
?>
<div class="footer-main footer-main__col-<?php echo count($sections); ?>">
	<div class="footer-container <?php echo esc_attr( apply_filters( 'durotan_footer_container_class', Helper::get_option( 'footer_container' ), 'main' ) ); ?>">
		<div class="row">
			<?php foreach ( $sections as $section => $items ) : ?>
				<div class="footer-main__items footer-main__items--<?php echo esc_attr( $section ); ?> <?php echo esc_attr( $classes[$section] ); ?>">
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
