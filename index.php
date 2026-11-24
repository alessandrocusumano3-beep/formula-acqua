<?php
/**
 * Template principale - Formula Acqua
 *
 * @package Formula_Acqua
 */

get_header();
?>

<div class="fa-page-header">
    <div class="fa-container">
        <h1>Blog</h1>
        <p>Consigli, guide e novità dal mondo delle piscine</p>
    </div>
</div>

<div class="fa-container fa-content-area">
    <div class="fa-posts-grid">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'fa-post-card' ); ?>>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="fa-post-card__image">
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?></a>
                        </div>
                    <?php endif; ?>
                    <div class="fa-post-card__content">
                        <div class="fa-post-card__meta">
                            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
                        </div>
                        <h2 class="fa-post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="fa-post-card__excerpt"><?php the_excerpt(); ?></div>
                        <a href="<?php the_permalink(); ?>" class="fa-btn fa-btn--secondary fa-btn--sm">Leggi tutto</a>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="fa-no-results"><h2>Nessun articolo trovato</h2></div>
        <?php endif; ?>
    </div>
    <?php the_posts_pagination( array( 'mid_size' => 2, 'prev_text' => '&laquo;', 'next_text' => '&raquo;' ) ); ?>
</div>

<?php get_footer(); ?>
