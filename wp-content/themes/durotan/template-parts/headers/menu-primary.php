<?php
/**
 * Template part for display primary menu
 *
 * @package Durotan
 */

use Durotan\Helper;

?>
<nav id="primary-menu" class="primary-menu header-navigation main-navigation <?php echo esc_attr( Helper::get_option( 'header_layout' ) == 'v7' ? 'durotan-scrollbar' : '' ) ?>">
	<?php \Durotan\Theme::instance()->get('header')->primary_menu(); ?>
</nav>
