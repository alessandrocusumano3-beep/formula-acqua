<?php
/**
 * 404 Premium - Formula Acqua
 *
 * @package Formula_Acqua
 */

get_header();
?>

<div class="fa-404">
    <div class="fa-container">
        <div class="fa-404__content">
            <h1 class="fa-404__title">404</h1>
            <h2><?php fa_te( 'Pagina non trovata' ); ?></h2>
            <p><?php fa_te( 'La pagina che cerchi non esiste o è stata spostata. Prova a cercare quello che ti serve o torna alla homepage.' ); ?></p>
            <div class="fa-404__actions">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="fa-btn fa-btn--primary fa-btn--lg"><?php fa_te( 'Torna alla homepage' ); ?></a>
                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="fa-btn fa-btn--secondary fa-btn--lg"><?php fa_te( 'Vai allo shop' ); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
