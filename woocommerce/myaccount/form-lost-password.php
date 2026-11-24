<?php
/**
 * Recupero password - Formula Acqua
 *
 * Presentazione grafica coerente con login/registrazione (fa-auth).
 * Nessuna modifica alla logica di reset password di WooCommerce.
 *
 * @package Formula_Acqua
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_lost_password_form' );
?>

<div class="fa-auth fa-auth--utility">
	<div class="fa-auth__shell">
		<?php fa_render_auth_aside(); ?>

		<div class="fa-auth__panel">
			<div class="fa-auth__brand">
				<span class="fa-auth__eyebrow"><?php fa_te( 'Area clienti' ); ?></span>
				<h1 class="fa-auth__title"><?php fa_te( 'Password dimenticata?' ); ?></h1>
				<p class="fa-auth__subtitle">
					<?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Inserisci la tua email o il nome utente: ti invieremo un link per creare una nuova password.', 'woocommerce' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</p>
			</div>

			<form method="post" class="woocommerce-ResetPassword lost_reset_password fa-auth__form">
				<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first fa-auth__field">
					<label for="user_login"><?php fa_te( 'Email o nome utente' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
					<span class="fa-auth__input-wrap">
						<span class="fa-auth__input-icon" aria-hidden="true">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
						</span>
						<input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" autocomplete="username" placeholder="<?php echo esc_attr( fa_t( 'es. mario@email.it' ) ); ?>" required aria-required="true" />
					</span>
				</p>

				<div class="clear"></div>

				<?php do_action( 'woocommerce_lostpassword_form' ); ?>

				<p class="woocommerce-form-row form-row">
					<input type="hidden" name="wc_reset_password" value="true" />
					<button type="submit" class="woocommerce-Button button fa-auth__submit" value="<?php esc_attr_e( 'Reset password', 'woocommerce' ); ?>"><?php fa_te( 'Invia link di reset' ); ?></button>
				</p>

				<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

				<p class="fa-auth__switch">
					<?php fa_te( 'Ricordi la password?' ); ?>
					<a class="fa-auth__switch-btn" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php fa_te( 'Accedi' ); ?></a>
				</p>
			</form>
		</div>
	</div>
</div>

<?php
do_action( 'woocommerce_after_lost_password_form' );
