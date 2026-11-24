<?php
/**
 * Template articolo singolo - Formula Acqua
 *
 * @package Formula_Acqua
 */

get_header();
?>

<div class="fa-page-header">
    <div class="fa-container">
        <h1><?php the_title(); ?></h1>
        <div class="fa-post-meta" style="margin-top:8px;font-size:0.9rem;opacity:0.7;">
            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
            &mdash; <?php the_author(); ?>
        </div>
    </div>
</div>

<div class="fa-container fa-content-area">
    <article id="post-<?php the_ID(); ?>" <?php post_class( 'fa-page-content' ); ?>>
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="fa-page-featured" style="margin-bottom:32px;"><?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?></div>
        <?php endif; ?>
        <div class="fa-entry-content"><?php the_content(); wp_link_pages(); ?></div>
    </article>
    <?php if ( comments_open() || get_comments_number() ) : ?>
        <div style="max-width:800px;margin:40px auto 0;"><?php comments_template(); ?></div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
