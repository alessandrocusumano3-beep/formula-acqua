<?php
/**
 * Searchform - Formula Acqua
 *
 * @package Formula_Acqua
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<form role="search" method="get" class="search-form fa-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <input type="search" placeholder="<?php echo esc_attr( fa_t( 'Cerca...' ) ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
    <button type="submit" class="fa-btn fa-btn--primary"><?php fa_te( 'Cerca' ); ?></button>
</form>
