<?php
/**
 * Template part for modal search
 *
 * @package Durotan
 */

use Durotan\Helper;

if ( Helper::get_option( 'header_search_type' ) === 'product' ) {
	$header_search_placeholder = Helper::get_option( 'header_search_placeholder' );
}else {
	$header_search_placeholder = 'Search...';
}
?>
<div class="modal__header">
	<div class="durotan-container">
		<label class="modal__label"><?php esc_html_e( 'Search', 'durotan' ); ?></label>
		<div class="modal__button-close">
			<?php echo \Durotan\Icon::get_svg( 'close' ); ?>
		</div>
	</div>
</div>
<div class="modal__content">
	<form method="get" class="form-search" action="<?php echo esc_url( home_url( '/' ) ); ?>" autocomplete="off">
		<div class="durotan-container">
			<div class="search-form">
				<?php if ( Helper::get_option( 'header_search_cat_filter' ) == 1 && Helper::get_option( 'header_search_type' ) === 'product' ) : ?>
				<div class="product-cats">
					<?php
						$number = Helper::get_option( 'header_search_cat_number' );
						$classes[] = 'durotan-product-cats_search__taxs-list';

						\Durotan\WooCommerce\Helper::instance()->taxs_list_search( 'product_cat', $number, $classes );
					?>
				</div>
				<?php endif; ?>
				<div class="search-fields">
					<?php if ( Helper::get_option( 'header_search_cat_filter' ) == 1 && Helper::get_option( 'header_search_type' ) === 'product' ) : ?>
						<input type="hidden" name="product_cat" value="0">
					<?php endif; ?>
					<input type="text" name="s" placeholder="<?php echo esc_attr( $header_search_placeholder ); ?>" class="search-field">
					<?php if ( Helper::get_option( 'header_search_type' ) === 'product' ) : ?>
						<input type="hidden" name="post_type" value="<?php echo esc_attr( Helper::get_option( 'header_search_type' ) ) ?>">
					<?php endif; ?>
					<span class="spinner"></span>
					<button type="reset" class="search-reset">
						<?php echo Durotan\Icon::get_svg( 'close', 'small' ); ?>
					</button>
				</div>
			</div>
			<div class="search-result">
				<?php Helper::durotan_loading(); ?>
				<div class="search-result__items">
				</div>
			</div>
		</div>
	</form>
</div>