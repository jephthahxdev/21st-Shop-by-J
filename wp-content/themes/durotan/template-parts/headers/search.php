<?php
/**
 * Template part for displaying the search icon
 *
 * @package Durotan
 */

use Durotan\Icon, Durotan\Helper;

$header_search_layout 					= Helper::get_option( 'header_search_layout' );
$header_search_layout_form_layout 		= Helper::get_option( 'header_search_layout_form_layout' );
$header_search_layout_icon_type			= Helper::get_option( 'header_search_layout_icon_type' );
$header_search_layout_icon_type_text 	= Helper::get_option( 'header_search_layout_icon_type_text' );

if ( Helper::get_option( 'header_layout' ) == 'v7' ) {
	$header_search_layout = 'form';
	$header_search_layout_form_layout = 'right';
}

$header_search_placeholder = Helper::get_option( 'header_search_placeholder' );

?>
<?php if ( $header_search_layout == 'icon' ) : ?>
	<?php if ( $header_search_layout_icon_type == 'icon' ) : ?>
		<div class="header-search header-search--modal">
			<a href="#" data-toggle="modal" data-target="search-modal">
				<?php echo Icon::get_svg( 'search', 'header-search__icon', 'shop' ); ?>
			</a>
		</div>
	<?php else : ?>
		<div class="header-search header-search--modal text">
			<a href="#" class="search-text" data-toggle="modal" data-target="search-modal">
				<?php echo esc_html( $header_search_layout_icon_type_text ); ?>
			</a>
		</div>
	<?php endif; ?>
<?php elseif ( $header_search_layout == 'form' ) : ?>
	<div class="header-search durotan-header__search header-search-form">
		<?php if ( $header_search_layout_form_layout == 'left' ) : ?>
			<form action="<?php echo esc_url( home_url( '/' ) ) ?>" class="form-search left" autocomplete="off">
				<button type="submit" class="search-submit" value="<?php esc_attr_e( 'Search', 'durotan' ); ?>">

					<?php echo \Durotan\Icon::get_svg( 'search', '', 'shop' ); ?>

					<span class="button-text screen-reader-text"><?php esc_html_e( 'Search', 'durotan' ); ?></span>

				</button>
				<input type="search" class="search-field" placeholder="<?php echo esc_attr( $header_search_placeholder ); ?>" value="" name="s">
				<?php if ( Helper::get_option( 'header_search_type' ) === 'product' ) : ?>
					<input type="hidden" name="post_type" value="<?php echo esc_attr( Helper::get_option( 'header_search_type' ) ) ?>">
				<?php endif; ?>
				<span class="spinner"></span>
				<button type="reset" class="search-reset">
					<?php echo Durotan\Icon::get_svg( 'close', 'small' ); ?>
				</button>
			</form>
		<?php else : ?>
			<form action="<?php echo esc_url( home_url( '/' ) ) ?>" class="form-search right" autocomplete="off">
				<input type="search" class="search-field" placeholder="<?php echo esc_attr( $header_search_placeholder ); ?>" value="" name="s">
				<button type="submit" class="search-submit" value="<?php esc_attr_e( 'Search', 'durotan' ); ?>">

					<?php echo \Durotan\Icon::get_svg( 'search', '', 'shop' ); ?>

					<span class="button-text screen-reader-text"><?php esc_html_e( 'Search', 'durotan' ); ?></span>

				</button>
				<?php if ( Helper::get_option( 'header_search_type' ) === 'product' ) : ?>
					<input type="hidden" name="post_type" value="<?php echo esc_attr( Helper::get_option( 'header_search_type' ) ) ?>">
				<?php endif; ?>
				<span class="spinner"></span>
				<button type="reset" class="search-reset">
					<?php echo Durotan\Icon::get_svg( 'close', 'small' ); ?>
				</button>
			</form>
		<?php endif; ?>
		<div class="search-result">
			<div class="search-result__items">
			</div>
		</div>
	</div>
<?php endif; ?>