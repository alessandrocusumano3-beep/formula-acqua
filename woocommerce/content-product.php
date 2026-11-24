<?php
/**
 * Product card - Formula Acqua
 *
 * @package Formula_Acqua
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( 'fa-product-card', $product ); ?>>
	<div class="fa-product-card__media">
		<?php
		if ( function_exists( 'fa_get_product_badges_html' ) ) {
			echo fa_get_product_badges_html( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		if ( function_exists( 'fa_get_wishlist_button_html' ) ) {
			echo fa_get_wishlist_button_html( $product->get_id(), true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>
		<a href="<?php echo esc_url( get_permalink() ); ?>" class="fa-product-card__image" aria-label="<?php echo esc_attr( $product->get_name() ); ?>">
			<?php echo $product->get_image( 'fa-card', array( 'loading' => 'lazy', 'alt' => $product->get_name() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</a>
	</div>

	<div class="fa-product-card__body">
		<a href="<?php echo esc_url( get_permalink() ); ?>" class="fa-product-card__title">
			<?php echo esc_html( $product->get_name() ); ?>
		</a>

		<?php echo function_exists( 'fa_get_product_rating_html' ) ? fa_get_product_rating_html( $product ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

		<?php
		if ( function_exists( 'fa_get_loop_price_html' ) ) {
			echo fa_get_loop_price_html( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			echo '<div class="fa-product-card__price">' . $product->get_price_html() . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>

		<div class="fa-product-card__actions">
			<?php woocommerce_template_loop_add_to_cart(); ?>
		</div>
	</div>
</li>
