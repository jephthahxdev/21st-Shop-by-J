<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.5.1
 */

use Durotan\WooCommerce\Helper;

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$attachment_ids = $product->get_gallery_image_ids();
$video_index    = get_post_meta( $product->get_id(), 'video_position', true );
$video_url      = get_post_meta( $product->get_id(), 'video_url', true );

$index = !empty($video_url) && $video_index == '1' ? 3 : 2;

if ( $attachment_ids && $product->get_image_id() ) {
	foreach ( $attachment_ids as $attachment_id ) {
		if ( $index == $video_index && is_singular( 'product' )) {
			echo Helper::get_product_video();
			$index++;
		}
		
		if ( \Durotan\Helper::get_option('product_layout') == 'v3'){
			echo '<div class="product-image" id="control-navigation-image-'.esc_attr($index).'">';
		}

		echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $attachment_id ), $attachment_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped

		if ( \Durotan\Helper::get_option('product_layout') == 'v3'){
			echo '</div>';
		}
		$index++;
	}

	if ( $video_index >= $index && is_singular( 'product' )) { 
		echo Helper::get_product_video();
	}
}