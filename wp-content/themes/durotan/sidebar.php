<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Durotan
 */
$has_sidebar = apply_filters( 'durotan_get_sidebar', false );

if ( ! $has_sidebar ) {
	return;
}

$sidebar = 'blog-sidebar';

if ( \Durotan\Helper::is_catalog() ) {
	$sidebar = 'catalog-sidebar';
}

if ( ! is_active_sidebar( $sidebar ) ) {
	return;
}

$sidebar_class = apply_filters( 'durotan_primary_sidebar_classes', $sidebar );
?>

<aside id="primary-sidebar"
       class="widget-area primary-sidebar <?php echo esc_attr( $sidebar_class ) ?>">
	<?php dynamic_sidebar( $sidebar ); ?>
</aside>