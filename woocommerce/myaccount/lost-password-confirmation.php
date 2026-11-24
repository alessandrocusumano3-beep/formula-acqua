<?php
/**
 * Conferma invio email di recupero password - Formula Acqua
 *
 * Presentazione grafica coerente con login/registrazione (fa-auth).
 *
 * @package Formula_Acqua
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="fa-auth fa-auth--utility">
	<div class="fa-auth__shell">
		<?php fa_render_auth_aside(); ?>

		<div class="fa-auth__panel">
			<div class="fa-auth__brand">
				<span class="fa-auth__eyebrow"><?php fa_te( 'Area clienti' ); ?></span>
				<h1 class="fa-auth__title"><?php fa_te( 'Controlla la tua email' ); ?></h1>
			</div>

			<p class="fa-auth__hint">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
				<?php
				echo esc_html(
					apply_filters(
						'woocommerce_lost_password_confirmation_message',
						esc_html__( 'A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.', 'woocommerce' )
					)
				);
				?>
			</p>

			<a class="woocommerce-Button button fa-auth__submit" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php fa_te( 'Torna al login' ); ?></a>
		</div>
	</div>
</div>
