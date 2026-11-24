<?php
/**
 * Nuova password - Formula Acqua
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

do_action( 'woocommerce_before_reset_password_form' );
?>

<div class="fa-auth fa-auth--utility">
	<div class="fa-auth__shell">
		<?php fa_render_auth_aside(); ?>

		<div class="fa-auth__panel">
			<div class="fa-auth__brand">
				<span class="fa-auth__eyebrow"><?php fa_te( 'Area clienti' ); ?></span>
				<h1 class="fa-auth__title"><?php fa_te( 'Crea una nuova password' ); ?></h1>
				<p class="fa-auth__subtitle">
					<?php echo apply_filters( 'woocommerce_reset_password_message', esc_html__( 'Scegli una nuova password sicura per il tuo account.', 'woocommerce' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</p>
			</div>

			<form method="post" class="woocommerce-ResetPassword lost_reset_password fa-auth__form">
				<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first fa-auth__field">
					<label for="password_1"><?php fa_te( 'Nuova password' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
					<span class="fa-auth__input-wrap">
						<span class="fa-auth__input-icon" aria-hidden="true">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
						</span>
						<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_1" id="password_1" autocomplete="new-password" placeholder="<?php echo esc_attr( fa_t( 'Crea una password sicura' ) ); ?>" required aria-required="true" />
						<button type="button" class="fa-auth__toggle-pass" data-fa-toggle-pass="password_1" data-label-show="<?php echo esc_attr( fa_t( 'Mostra password' ) ); ?>" data-label-hide="<?php echo esc_attr( fa_t( 'Nascondi password' ) ); ?>" aria-label="<?php echo esc_attr( fa_t( 'Mostra password' ) ); ?>" aria-pressed="false">
							<svg class="fa-auth__eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
								<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
								<circle cx="12" cy="12" r="3"/>
								<line class="fa-auth__eye-slash" x1="2.5" y1="2.5" x2="21.5" y2="21.5"/>
							</svg>
						</button>
					</span>
				</p>
				<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last fa-auth__field">
					<label for="password_2"><?php fa_te( 'Ripeti nuova password' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
					<span class="fa-auth__input-wrap">
						<span class="fa-auth__input-icon" aria-hidden="true">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
						</span>
						<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_2" id="password_2" autocomplete="new-password" placeholder="<?php echo esc_attr( fa_t( 'Ripeti la password' ) ); ?>" required aria-required="true" />
						<button type="button" class="fa-auth__toggle-pass" data-fa-toggle-pass="password_2" data-label-show="<?php echo esc_attr( fa_t( 'Mostra password' ) ); ?>" data-label-hide="<?php echo esc_attr( fa_t( 'Nascondi password' ) ); ?>" aria-label="<?php echo esc_attr( fa_t( 'Mostra password' ) ); ?>" aria-pressed="false">
							<svg class="fa-auth__eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
								<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
								<circle cx="12" cy="12" r="3"/>
								<line class="fa-auth__eye-slash" x1="2.5" y1="2.5" x2="21.5" y2="21.5"/>
							</svg>
						</button>
					</span>
				</p>

				<input type="hidden" name="reset_key" value="<?php echo esc_attr( $args['key'] ); ?>" />
				<input type="hidden" name="reset_login" value="<?php echo esc_attr( $args['login'] ); ?>" />

				<div class="clear"></div>

				<?php do_action( 'woocommerce_resetpassword_form' ); ?>

				<p class="woocommerce-form-row form-row">
					<input type="hidden" name="wc_reset_password" value="true" />
					<button type="submit" class="woocommerce-Button button fa-auth__submit" value="<?php esc_attr_e( 'Save', 'woocommerce' ); ?>"><?php fa_te( 'Salva nuova password' ); ?></button>
				</p>

				<?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>
			</form>
		</div>
	</div>
</div>

<?php
do_action( 'woocommerce_after_reset_password_form' );
