<?php
/**
 * Prodotto singolo - Formula Acqua
 *
 * @package Formula_Acqua
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<div class="fa-single-product-page">
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

		<?php
		while ( have_posts() ) :
			the_post();
			wc_get_template_part( 'content', 'single-product' );
		endwhile;
		?>
	</div>
</div>

<?php get_footer(); ?>
