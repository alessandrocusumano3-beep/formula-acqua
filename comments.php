<?php
/**
 * Commenti - Formula Acqua
 *
 * @package Formula_Acqua
 */

if ( ! defined( 'ABSPATH' ) || post_password_required() ) {
    return;
}
?>
<div id="comments" class="comments-area">
    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <?php
            $fa_comments_count = get_comments_number();
            echo esc_html( sprintf( _n( fa_t( '%d commento' ), fa_t( '%d commenti' ), $fa_comments_count, 'formula-acqua' ), number_format_i18n( $fa_comments_count ) ) );
            ?>
        </h2>
        <ol class="comment-list"><?php wp_list_comments( array( 'style' => 'ol', 'short_ping' => true, 'avatar_size' => 50 ) ); ?></ol>
        <?php the_comments_navigation(); ?>
    <?php endif; ?>
    <?php comment_form( array( 'title_reply' => fa_t( 'Lascia un commento' ), 'label_submit' => fa_t( 'Invia' ), 'comment_notes_after' => '' ) ); ?>
</div>
