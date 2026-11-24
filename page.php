<?php
/**
 * Template pagina - Formula Acqua
 *
 * @package Formula_Acqua
 */

get_header();

$fa_is_account  = function_exists( 'is_account_page' ) && is_account_page();
$fa_is_cart     = function_exists( 'is_cart' ) && is_cart();
$fa_is_checkout = function_exists( 'is_checkout' ) && is_checkout() && ! ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-received' ) );
$fa_is_wishlist = function_exists( 'fa_is_wishlist_page' ) && fa_is_wishlist_page();
$fa_is_commerce = $fa_is_account || $fa_is_cart || $fa_is_checkout;
?>

<?php if ( $fa_is_wishlist ) : ?>
	<?php /* Header gestito dallo shortcode wishlist */ ?>
<?php elseif ( ! $fa_is_commerce ) : ?>
<div class="fa-page-header">
    <div class="fa-container"><h1><?php the_title(); ?></h1></div>
</div>
<?php elseif ( $fa_is_cart || $fa_is_checkout ) : ?>
<div class="fa-commerce-hero">
    <div class="fa-container">
        <h1><?php echo esc_html( $fa_is_cart ? fa_t( 'Il tuo carrello' ) : fa_t( 'Pagamento' ) ); ?></h1>
        <p><?php echo esc_html( $fa_is_cart ? fa_t( 'Rivedi i prodotti e procedi all’acquisto in sicurezza.' ) : fa_t( 'Completa l’ordine in pochi passaggi sicuri.' ) ); ?></p>
    </div>
</div>
<?php endif; ?>

<div class="<?php echo $fa_is_wishlist ? 'fa-container fa-wishlist-shell' : ( $fa_is_account ? 'fa-account-shell' : ( $fa_is_cart || $fa_is_checkout ? 'fa-container fa-commerce-shell' : 'fa-container fa-content-area' ) ); ?>">
    <div class="<?php echo ( $fa_is_commerce || $fa_is_wishlist ) ? '' : 'fa-page-content'; ?>">
        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php if ( ! $fa_is_commerce && ! $fa_is_wishlist && has_post_thumbnail() ) : ?>
                    <div class="fa-page-featured"><?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?></div>
                <?php endif; ?>
                <div class="fa-entry-content<?php echo $fa_is_wishlist ? ' fa-entry-content--wishlist' : ''; ?>">
                    <?php the_content(); wp_link_pages(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</div>

<?php get_footer(); ?>
