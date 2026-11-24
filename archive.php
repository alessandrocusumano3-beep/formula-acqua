<?php
/**
 * Template archivio - Formula Acqua
 *
 * @package Formula_Acqua
 */

get_header();
?>

<div class="fa-page-header">
    <div class="fa-container">
        <h1><?php the_archive_title(); ?></h1>
        <?php the_archive_description( '<p>', '</p>' ); ?>
    </div>
</div>

<div class="fa-container fa-content-area">
    <div class="fa-posts-grid">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <article <?php post_class( 'fa-post-card' ); ?>>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="fa-post-card__image"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?></a></div>
                    <?php endif; ?>
                    <div class="fa-post-card__content">
                        <div class="fa-post-card__meta"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></div>
                        <h2 class="fa-post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="fa-post-card__excerpt"><?php the_excerpt(); ?></div>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="fa-no-results"><h2><?php fa_te( 'Nessun contenuto trovato' ); ?></h2></div>
        <?php endif; ?>
    </div>
    <?php the_posts_pagination( array( 'mid_size' => 2, 'prev_text' => '&laquo;', 'next_text' => '&raquo;' ) ); ?>
</div>

<?php get_footer(); ?>
