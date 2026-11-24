<?php
/**
 * Login / Registrazione - Formula Acqua
 *
 * @package Formula_Acqua
 * @version 9.9.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$show_register = ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) );
$active_tab    = ( ! empty( $_POST['register'] ) && $show_register ) ? 'register' : 'login'; // phpcs:ignore WordPress.Security.NonceVerification.Missing

$privacy_url = function_exists( 'fa_get_legal_page_url' ) ? fa_get_legal_page_url( 'privacy-policy' ) : home_url( '/privacy-policy/' );
$terms_url   = function_exists( 'fa_get_legal_page_url' ) ? fa_get_legal_page_url( 'termini-e-condizioni' ) : home_url( '/termini-e-condizioni/' );

do_action( 'woocommerce_before_customer_login_form' );
?>

<div class="fa-auth">
	<div class="fa-auth__shell">
		<?php fa_render_auth_aside(); ?>

		<div class="fa-auth__panel">
			<div class="fa-auth__brand">
				<span class="fa-auth__eyebrow"><?php fa_te( 'Area clienti' ); ?></span>
				<h1 class="fa-auth__title"><?php echo esc_html( 'register' === $active_tab ? fa_t( 'Crea il tuo account' ) : fa_t( 'Accedi' ) ); ?></h1>
				<p class="fa-auth__subtitle" data-fa-auth-subtitle>
					<?php if ( 'register' === $active_tab ) : ?>
						<?php fa_te( 'Registrati in pochi secondi: ordini e preferiti sempre con te.' ); ?>
					<?php else : ?>
						<?php fa_te( 'Accedi con email e password per continuare i tuoi acquisti.' ); ?>
					<?php endif; ?>
				</p>
			</div>

			<?php if ( $show_register ) : ?>
				<div class="fa-auth__tabs" role="tablist" aria-label="<?php echo esc_attr( fa_t( 'Accedi o registrati' ) ); ?>">
					<button type="button" class="fa-auth__tab<?php echo 'login' === $active_tab ? ' is-active' : ''; ?>" data-fa-auth-tab="login" role="tab" aria-controls="fa-auth-login" aria-selected="<?php echo 'login' === $active_tab ? 'true' : 'false'; ?>" id="fa-auth-tab-login"><?php fa_te( 'Accedi' ); ?></button>
					<button type="button" class="fa-auth__tab<?php echo 'register' === $active_tab ? ' is-active' : ''; ?>" data-fa-auth-tab="register" role="tab" aria-controls="fa-auth-register" aria-selected="<?php echo 'register' === $active_tab ? 'true' : 'false'; ?>" id="fa-auth-tab-register"><?php fa_te( 'Crea account' ); ?></button>
				</div>
			<?php endif; ?>

			<div class="fa-auth__forms">
				<div class="fa-auth__pane<?php echo 'login' === $active_tab ? ' is-active' : ''; ?>" data-fa-auth-pane="login" id="fa-auth-login" role="tabpanel" aria-labelledby="fa-auth-tab-login" <?php echo 'login' === $active_tab ? '' : 'hidden'; ?>>
					<form class="woocommerce-form woocommerce-form-login login fa-auth__form" method="post" novalidate>
						<?php do_action( 'woocommerce_login_form_start' ); ?>

						<p class="woocommerce-form-row form-row form-row-wide fa-auth__field">
							<label for="username"><?php fa_te( 'Email o nome utente' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
							<span class="fa-auth__input-wrap">
								<span class="fa-auth__input-icon" aria-hidden="true">
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
								</span>
								<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" placeholder="<?php echo esc_attr( fa_t( 'es. mario@email.it' ) ); ?>" value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required /><?php // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized ?>
							</span>
						</p>

						<p class="woocommerce-form-row form-row form-row-wide fa-auth__field">
							<label for="password">Password&nbsp;<span class="required" aria-hidden="true">*</span></label>
							<span class="fa-auth__input-wrap">
								<span class="fa-auth__input-icon" aria-hidden="true">
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
								</span>
								<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" placeholder="<?php echo esc_attr( fa_t( 'La tua password' ) ); ?>" required />
								<button type="button" class="fa-auth__toggle-pass" data-fa-toggle-pass="password" data-label-show="<?php echo esc_attr( fa_t( 'Mostra password' ) ); ?>" data-label-hide="<?php echo esc_attr( fa_t( 'Nascondi password' ) ); ?>" aria-label="<?php echo esc_attr( fa_t( 'Mostra password' ) ); ?>" aria-pressed="false">
									<svg class="fa-auth__eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
										<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
										<circle cx="12" cy="12" r="3"/>
										<line class="fa-auth__eye-slash" x1="2.5" y1="2.5" x2="21.5" y2="21.5"/>
									</svg>
								</button>
							</span>
						</p>

						<?php do_action( 'woocommerce_login_form' ); ?>

						<div class="fa-auth__row">
							<label class="woocommerce-form__label woocommerce-form__label-for-checkbox fa-auth__checkbox">
								<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
								<span><?php fa_te( 'Ricordami' ); ?></span>
							</label>
							<a class="fa-auth__link" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php fa_te( 'Password dimenticata?' ); ?></a>
						</div>

						<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
						<button type="submit" class="woocommerce-button button woocommerce-form-login__submit fa-auth__submit" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php fa_te( 'Accedi' ); ?></button>

						<?php if ( $show_register ) : ?>
							<p class="fa-auth__switch">
								<?php fa_te( 'Nuovo cliente?' ); ?>
								<button type="button" class="fa-auth__switch-btn" data-fa-auth-tab="register"><?php fa_te( 'Crea un account' ); ?></button>
							</p>
						<?php endif; ?>

						<?php do_action( 'woocommerce_login_form_end' ); ?>
					</form>
				</div>

				<?php if ( $show_register ) : ?>
					<div class="fa-auth__pane<?php echo 'register' === $active_tab ? ' is-active' : ''; ?>" data-fa-auth-pane="register" id="fa-auth-register" role="tabpanel" aria-labelledby="fa-auth-tab-register" <?php echo 'register' === $active_tab ? '' : 'hidden'; ?>>
						<form method="post" class="woocommerce-form woocommerce-form-register register fa-auth__form" <?php do_action( 'woocommerce_register_form_tag' ); ?> novalidate>
							<?php do_action( 'woocommerce_register_form_start' ); ?>

							<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
								<p class="woocommerce-form-row form-row form-row-wide fa-auth__field">
									<label for="reg_username"><?php fa_te( 'Nome utente' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
									<span class="fa-auth__input-wrap">
										<span class="fa-auth__input-icon" aria-hidden="true">
											<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
										</span>
										<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" placeholder="<?php echo esc_attr( fa_t( 'Scegli un nome utente' ) ); ?>" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required /><?php // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized ?>
									</span>
								</p>
							<?php endif; ?>

							<p class="woocommerce-form-row form-row form-row-wide fa-auth__field">
								<label for="reg_email"><?php fa_te( 'Indirizzo email' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
								<span class="fa-auth__input-wrap">
									<span class="fa-auth__input-icon" aria-hidden="true">
										<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
									</span>
									<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" placeholder="<?php echo esc_attr( fa_t( 'es. mario@email.it' ) ); ?>" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required /><?php // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized ?>
								</span>
							</p>

							<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
								<p class="woocommerce-form-row form-row form-row-wide fa-auth__field">
									<label for="reg_password">Password&nbsp;<span class="required" aria-hidden="true">*</span></label>
									<span class="fa-auth__input-wrap">
										<span class="fa-auth__input-icon" aria-hidden="true">
											<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
										</span>
										<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" placeholder="<?php echo esc_attr( fa_t( 'Crea una password sicura' ) ); ?>" required />
										<button type="button" class="fa-auth__toggle-pass" data-fa-toggle-pass="reg_password" data-label-show="<?php echo esc_attr( fa_t( 'Mostra password' ) ); ?>" data-label-hide="<?php echo esc_attr( fa_t( 'Nascondi password' ) ); ?>" aria-label="<?php echo esc_attr( fa_t( 'Mostra password' ) ); ?>" aria-pressed="false">
											<svg class="fa-auth__eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
												<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
												<circle cx="12" cy="12" r="3"/>
												<line class="fa-auth__eye-slash" x1="2.5" y1="2.5" x2="21.5" y2="21.5"/>
											</svg>
										</button>
									</span>
								</p>
							<?php else : ?>
								<p class="fa-auth__hint">
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
									<?php fa_te( 'Ti invieremo un link per impostare la password.' ); ?>
								</p>
							<?php endif; ?>

							<?php do_action( 'woocommerce_register_form' ); ?>

							<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
							<button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit fa-auth__submit" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php fa_te( 'Crea account' ); ?></button>

							<p class="fa-auth__legal">
								<?php fa_te( 'Creando l’account accetti i' ); ?>
								<a href="<?php echo esc_url( $terms_url ); ?>"><?php fa_te( 'Termini e Condizioni' ); ?></a>
								<?php fa_te( 'e la' ); ?>
								<a href="<?php echo esc_url( $privacy_url ); ?>">Privacy Policy</a>.
							</p>

							<p class="fa-auth__switch">
								<?php fa_te( 'Hai già un account?' ); ?>
								<button type="button" class="fa-auth__switch-btn" data-fa-auth-tab="login"><?php fa_te( 'Accedi' ); ?></button>
							</p>

							<?php do_action( 'woocommerce_register_form_end' ); ?>
						</form>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
