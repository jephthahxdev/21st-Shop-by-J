<?php
/**
 * Template part for modal menu
 *
 * @package Durotan
 */

use Durotan\Helper;

?>
<div id="menu-panel" class="menu-panel offscreen-panel <?php echo esc_attr( Helper::get_option( 'hamburger_side_type' ) == 'side-right' ? 'side-right' : '' ) ?>">
	<div class="offscreen-panel__backdrop"></div>
	<div class="offscreen-panel__wrapper">
		<div class="offscreen-panel__button-close">
			<?php if ( Helper::get_option( 'hamburger_side_type' ) == 'side-right' ) : ?>
				<span class="menu-text">Close</span>
				<span class="hamburger-box hamburger-box__two-line"><span class="hamburger-inner"></span></span>
			<?php else: ?>
				<span class="hamburger-box hamburger-box__two-line"><span class="hamburger-inner"></span></span>
				<span class="menu-text">Close</span>
			<?php endif; ?>
		</div>
		<div class="offscreen-panel__content">
			<div class="language-currency-switcher">
				<?php if ( Helper::get_option( 'hamburger_currency' ) ) { Helper::currency_switcher(); } ?>
				<?php if ( Helper::get_option( 'hamburger_language' ) ) { Helper::language_switcher($args, 'menu'); } ?>
			</div>
			<?php
				if ( has_nav_menu( 'hamburger' ) ) {
					echo '<nav id="primary-menu" class="main-navigation primary-menu">';

						if ( class_exists( '\Durotan\Addons\Modules\Mega_Menu\Walker' ) ) {
							wp_nav_menu( apply_filters( 'durotan_navigation_hanburger_content', array(
								'theme_location' => 'hamburger',
								'container'      => false,
								'menu_class'     => 'menu',
								'walker' 		=>  new \Durotan\Addons\Modules\Mega_Menu\Walker()
							) ) );

						} else {
							wp_nav_menu( apply_filters( 'durotan_navigation_hanburger_content', array(
								'theme_location' => 'hamburger',
								'container'      => false,
								'menu_class'     => 'menu',
							) ) );
						}
					echo '</nav>';
				}
			?>
			<div class="offscreen-panel__footer">
				<div class="text-box">
					<?php if ( Helper::get_option( 'hamburger_custom_text' ) ) : ?>
						<?php echo wp_kses_post( Helper::get_option( 'hamburger_custom_text_html' ) ); ?>
					<?php endif; ?>
				</div>
				<?php Helper::social_hamburger_menu(); ?>
			</div>
		</div>
	</div>
</div>