<?php
/**
 * Template part for display secondary menu
 *
 * @package Durotan
 */

use Durotan\Helper;

?>
<nav id="secondary-menu" class="main-navigation secondary-navigation <?php echo esc_attr( Helper::get_option( 'header_layout' ) == 'v7' ? 'durotan-scrollbar' : '' ) ?>">
	<?php
		if ( has_nav_menu( 'secondary' ) ) {
			wp_nav_menu( array(
				'theme_location' => 'secondary',
				'container'      => null,
				'menu_class'     => 'menu nav-menu',
			) );
		}
	?>
</nav>
