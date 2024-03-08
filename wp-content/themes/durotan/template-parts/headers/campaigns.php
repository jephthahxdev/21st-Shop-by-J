<?php
/**
 * Template part for displaying the campaign bar
 *
 * @package Durotan
 */

use Durotan\Helper,
	Durotan\Icon;

if ( Helper::get_option( 'header_layout'  == 'v7' ) ) {
	return;
}

?>
<div class="durotan-campaign-bar <?php echo esc_attr( Helper::get_option( 'campaign_bar_content_color' ) ); ?> <?php echo Helper::get_option( 'campaign_bar_close' ) ? 'has-close' : ''; ?>">
    <div class="durotan-campaign-bar__campaigns">
		<?php echo wp_kses_post( Helper::get_option( 'campaign_bar_content' ) ); ?>
    </div>
	<?php if( Helper::get_option( 'campaign_bar_close' ) ) echo Icon::get_svg( 'close', 'durotan-close-campaign-bar' ); ?>
</div>