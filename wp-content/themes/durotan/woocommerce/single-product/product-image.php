<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 */

use Durotan\WooCommerce\Helper;


defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters(
	'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ( $product->get_image_id() ? 'with-images' : 'without-images' ),
		'woocommerce-product-gallery--columns-' . absint( $columns ),
		'images',
	)
);

$video_position       = get_post_meta( $product->get_id(), 'video_position', true );
$video_url            = get_post_meta( $product->get_id(), 'video_url', true );

?>
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-video="<?php echo esc_attr( $video_position ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
	<?php do_action( 'durotan_before_product_gallery' ); ?>

	<?php
		if ( \Durotan\Helper::get_option('product_layout') == 'v3'):
			$attachment_ids = $product->get_gallery_image_ids();
			$current_img = 1;

		if ( $attachment_ids || $product->get_image_id() ) :
	?>
		<ul class="flex-control-nav durotan-control-nav">
			<?php
				if (!empty($video_url) && $video_position == '1' && is_singular( 'product' )){
					echo Helper::get_nav_product_video();
					$current_img++;
				}
				$image = get_the_post_thumbnail( $product->get_id(), 'shop_thumbnail' , array(
					'title'	=> esc_attr(get_the_title(get_post_thumbnail_id()))
				) );
				echo apply_filters( 'durotan_single_product_image_html', sprintf( '<li><a class="current" href="#control-navigation-image-%d" data-number="%d">%s</a></li>',$current_img, $current_img, $image) , $product->get_id() );

				foreach ( $attachment_ids as $attachment_id ) {
					$current_img++;
					if (!empty($video_url) && $video_position == $current_img && is_singular( 'product' )){
						echo Helper::get_nav_product_video();
						$current_img++;
					}
					echo apply_filters( 'durotan_single_product_image_thumbnail_html', sprintf( '<li><a href="#control-navigation-image-%d" data-number="%d">%s</a></li>',$current_img, $current_img, wp_get_attachment_image( $attachment_id, 'shop_thumbnail' ) ), $attachment_id );
				}

				if ( !empty($video_url) && $video_position > $current_img && is_singular( 'product' )) {
					echo Helper::get_nav_product_video();
				}
			?>
		</ul>
	<?php
			endif;
		endif;
	?>

	<figure class="woocommerce-product-gallery__wrapper">
		<?php
		$i = 1;
		if ( !empty($video_url) && $video_position == '1' && is_singular( 'product' ) ) {
			echo Helper::get_product_video();
			$i++;
		}

		if ( $product->get_image_id() ) {
			$html = wc_get_gallery_image_html( $post_thumbnail_id, true );
		} else {
			$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
			$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'durotan' ) );
			$html .= '</div>';
		}

		if ( \Durotan\Helper::get_option('product_layout') == 'v3'){
			echo '<div class="product-image" id="control-navigation-image-'.esc_attr($i).'">';
		}

		echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped

		if ( \Durotan\Helper::get_option('product_layout') == 'v3'){
			echo '</div>';
		}

		do_action( 'woocommerce_product_thumbnails' );
		?>
	</figure>

    <?php do_action( 'durotan_after_product_gallery' ); ?>
</div>
