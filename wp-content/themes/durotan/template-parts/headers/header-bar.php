<?php
/**
 * Template part for displaying the custom text
 *
 * @package Durotan
 */

if ( ! is_active_sidebar( 'header-bar' ) ) {
	return;
}

?>
<div class="header-bar">
	<?php dynamic_sidebar( 'header-bar' ); ?>
</div>