<?php
/**
 * Template ricerca - Formula Acqua
 *
 * @package Formula_Acqua
 */

get_header();
?>

<div class="fa-page-header">
    <div class="fa-container">
        <h1><?php printf( esc_html( fa_t( 'Risultati per: "%s"' ) ), esc_html( get_search_query() ) ); ?></h1>
    </div>
</div>

<div class="fa-container fa-content-area">
    <?php if ( have_posts() ) : ?>
        <div class="fa-posts-grid">
            <?php while ( have_posts() ) : the_post(); ?>
                <article <?php post_class( 'fa-post-card' ); ?>>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="fa-post-card__image"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?></a></div>
                    <?php endif; ?>
                    <div class="fa-post-card__content">
                        <h2 class="fa-post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="fa-post-card__excerpt"><?php the_excerpt(); ?></div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
        <?php the_posts_pagination(); ?>
    <?php else : ?>
        <div class="fa-no-results">
            <h2><?php fa_te( 'Nessun risultato trovato' ); ?></h2>
            <p><?php echo wp_kses_post( sprintf( fa_t( 'Prova con termini diversi o torna alla %s.' ), '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( fa_t( 'homepage' ) ) . '</a>' ) ); ?></p>
            <?php get_search_form(); ?>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
