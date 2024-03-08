<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_mini_cart' ); ?>

<?php if ( ! WC()->cart->is_empty() ) : ?>

	<ul class="woocommerce-mini-cart cart_list product_list_widget durotan-scrollbar <?php echo esc_attr( $args['list_class'] ); ?>">
		<?php
		do_action( 'woocommerce_before_mini_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
				$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'durotan-thumbnail-mini-cart' ), $cart_item, $cart_item_key );
				$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<li class="woocommerce-mini-cart-item mini-cart-item-<?php echo esc_attr( $_product->get_id() ); ?> <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
					<div class="woocommerce-mini-cart-item__summary">
						<?php if ( $product_permalink ) : ?>
							<a href="<?php echo esc_url( $product_permalink ); ?>">
						<?php endif; ?>
							<?php echo wp_kses_post( $thumbnail ); ?>
						<?php if ( $product_permalink ) : ?>
							</a>
						<?php endif; ?>
						<span class="woocommerce-mini-cart-item__data">
							<span class="woocommerce-mini-cart-item__name">
							<?php if ( $product_permalink ) : ?>
								<a href="<?php echo esc_url( $product_permalink ); ?>">
							<?php endif; ?>
									<?php echo wp_kses_post( $product_name ); ?>
								<?php if ( $product_permalink ) : ?>
									</a>
								<?php endif; ?>
							</span>
							<span class="variation">
								<?php echo implode( ", ", $cart_item['variation'] ); ?>
							</span>
							<span class="woocommerce-Price-amount amount">
								<span class="quantity cart-behaviour-page"><?php echo wp_kses_post( $cart_item['quantity'] . ' x' ); ?></span>
								<bdi><?php echo ! empty( $product_price ) ? $product_price : '' ?></bdi>
							</span>
						</span>
					</div>
					<div class="woocommerce-mini-cart-item__qty cart-behaviour-panel" data-nonce="<?php echo wp_create_nonce( 'durotan-update-cart-qty--' . $cart_item_key ); ?>">
						<div class="quantity">
							<?php echo \Durotan\Icon::get_svg( 'minus', 'durotan-qty-button qty-button decrease', 'shop' ); ?>
							<input type="number" class="input-text qty text" step="1" min="1" max="50" name="cart_qty" value="<?php echo wp_kses_post( $cart_item['quantity'] ); ?>" title="Qty" key="<?php echo wp_kses_post( $cart_item_key ); ?>">
							<?php echo \Durotan\Icon::get_svg( 'plus', 'durotan-qty-button qty-button increase', 'shop' ); ?>
						</div>
					</div>
					<div class="woocommerce-mini-cart-item__remove-button">
						<?php
						echo apply_filters(
							'woocommerce_cart_item_remove_link',
							sprintf(
								'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">%s</a>',
								esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
								esc_attr__( 'Remove this item', 'durotan' ),
								esc_attr( $product_id ),
								esc_attr( $cart_item_key ),
								esc_attr( $_product->get_sku() ),
								\Durotan\Icon::get_svg( 'close' )
							),
							$cart_item_key
						);
						?>
					</div>
				</li>
				<?php
			}
		}

		do_action( 'woocommerce_mini_cart_contents' );
		?>
	</ul>

	<div class="widget_shopping_cart_footer">

		<p class="woocommerce-mini-cart__total total">
			<?php
			/**
			 * Woocommerce_widget_shopping_cart_total hook.
			 *
			 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
			 */
			do_action( 'woocommerce_widget_shopping_cart_total' );
			?>
		</p>

		<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

		<p class="woocommerce-mini-cart__buttons buttons"><?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?></p>

		<?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>
	</div>

<?php else : ?>

	<p class="woocommerce-mini-cart__empty-message">
		<span>
			<?php esc_html_e( 'Your cart is empty.', 'durotan' ); ?>
		</span>
	</p>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>
