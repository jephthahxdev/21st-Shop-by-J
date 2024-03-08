<?php
/**
 * Template part for displaying the hamburger icon
 *
 * @package Durotan
 */

use Durotan\Helper;

if ( Helper::get_option( 'hamburger_type' ) === 'icon' ) {
	$hamburger_type = '<span class="hamburger-box"><span class="hamburger-inner"></span></span>';
}else {
	$hamburger_type = '<span class="hamburger-box hamburger-box__two-line"><span class="hamburger-inner"></span></span><span class="header-hamburger__text">' . Helper::get_option( 'hamburger_type_text' ) . '</span>';
}

?>
<div class="header-hamburger">
	<a href="#" class="header-hamburger__link" data-toggle="off-canvas" data-target="menu-panel">
		<?php echo wp_kses_post( $hamburger_type ); ?>
	</a>
</div>
