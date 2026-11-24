<?php
/**
 * Shop / Category archive - Formula Acqua (full-width e-commerce)
 *
 * @package Formula_Acqua
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$cat_title = '';
$cat_desc  = '';
if ( is_product_category() ) {
	$cat_title = single_term_title( '', false );
	$term      = get_queried_object();
	if ( $term && ! empty( $term->description ) ) {
		$cat_desc = $term->description;
	}
} elseif ( is_shop() ) {
	$cat_title = fa_t( 'Shop Formula Acqua' );
	$cat_desc  = fa_t( 'Robot, chimici, pompe, filtri e accessori per la tua piscina.' );
} elseif ( is_product_tag() ) {
	$cat_title = single_term_title( '', false );
}
?>

<div class="fa-cat-page">
	<div class="fa-container">
		<?php if ( function_exists( 'woocommerce_breadcrumb' ) ) : ?>
			<?php
			woocommerce_breadcrumb(
				array(
					'delimiter'   => '',
					'wrap_before' => '<nav class="fa-cat-breadcrumb" aria-label="Breadcrumb"><ol class="fa-cat-breadcrumb__list">',
					'wrap_after'  => '</ol></nav>',
					'before'      => '<li class="fa-cat-breadcrumb__item">',
					'after'       => '</li>',
					'home'        => fa_t( 'Home' ),
				)
			);
			?>
		<?php endif; ?>

		<header class="fa-cat-header">
			<?php if ( is_product_category() ) : ?>
				<p class="fa-cat-header__eyebrow"><?php fa_te( 'Categoria' ); ?></p>
			<?php elseif ( is_shop() ) : ?>
				<p class="fa-cat-header__eyebrow"><?php fa_te( 'Catalogo' ); ?></p>
			<?php endif; ?>
			<h1 class="fa-cat-header__title"><?php echo esc_html( $cat_title ); ?></h1>
			<?php if ( $cat_desc ) : ?>
				<div class="fa-cat-header__desc"><?php echo wp_kses_post( wpautop( $cat_desc ) ); ?></div>
			<?php elseif ( is_product_category() ) : ?>
				<p class="fa-cat-header__desc"><?php fa_te( 'I migliori prodotti selezionati per questa categoria.' ); ?></p>
			<?php endif; ?>
		</header>

		<div class="fa-cat-catalog">
			<?php if ( woocommerce_product_loop() ) : ?>
				<div class="fa-cat-toolbar">
					<?php woocommerce_catalog_ordering(); ?>
				</div>

				<?php woocommerce_product_loop_start(); ?>
				<?php
				while ( have_posts() ) :
					the_post();
					wc_get_template_part( 'content', 'product' );
				endwhile;
				?>
				<?php woocommerce_product_loop_end(); ?>

				<div class="fa-shop-pagination"><?php woocommerce_pagination(); ?></div>
			<?php else : ?>
				<div class="fa-no-results">
					<h2><?php echo esc_html( $cat_title ? $cat_title : fa_t( 'Nessun prodotto' ) ); ?></h2>
					<p><?php fa_te( 'Questa categoria è pronta: i prodotti verranno aggiunti a breve.' ); ?></p>
					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
						<p class="fa-no-results__cta"><a class="fa-btn fa-btn--primary" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php fa_te( 'Torna allo shop' ); ?></a></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php
get_footer();
