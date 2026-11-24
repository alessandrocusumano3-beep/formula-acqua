<?php
/**
 * My Account - Formula Acqua
 *
 * @package Formula_Acqua
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

$current_user = wp_get_current_user();
?>

<div class="fa-account">
	<div class="fa-container">
		<header class="fa-account__header">
			<div>
				<p class="fa-account__eyebrow"><?php fa_te( 'Area personale' ); ?></p>
				<h1 class="fa-account__title"><?php echo esc_html( fa_t( 'Ciao' ) ); ?>, <?php echo esc_html( $current_user->display_name ? $current_user->display_name : fa_t( 'cliente' ) ); ?></h1>
				<p class="fa-account__desc"><?php fa_te( 'Gestisci ordini, indirizzi e dati del tuo account Formula Acqua.' ); ?></p>
			</div>
			<a class="fa-account__shop-link" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php fa_te( 'Continua lo shopping' ); ?></a>
		</header>

		<div class="fa-account__layout">
			<?php
			/**
			 * My Account navigation.
			 *
			 * @since 2.6.0
			 */
			do_action( 'woocommerce_account_navigation' );
			?>

			<div class="woocommerce-MyAccount-content fa-account__content">
				<?php
				/**
				 * My Account content.
				 *
				 * @since 2.6.0
				 */
				do_action( 'woocommerce_account_content' );
				?>
			</div>
		</div>
	</div>
</div>
