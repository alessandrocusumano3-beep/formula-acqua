<?php
/**
 * Footer Premium - Formula Acqua
 *
 * @package Formula_Acqua
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$privacy_url = function_exists( 'fa_get_legal_page_url' ) ? fa_get_legal_page_url( 'privacy-policy' ) : home_url( '/privacy-policy/' );
$cookie_url  = function_exists( 'fa_get_legal_page_url' ) ? fa_get_legal_page_url( 'cookie-policy' ) : home_url( '/cookie-policy/' );
$terms_url   = function_exists( 'fa_get_legal_page_url' ) ? fa_get_legal_page_url( 'termini-e-condizioni' ) : home_url( '/termini-e-condizioni/' );
$contact_url = function_exists( 'fa_get_legal_page_url' ) ? fa_get_legal_page_url( 'contatti' ) : home_url( '/contatti/' );
$refund_url  = function_exists( 'fa_get_refund_policy_url' ) ? fa_get_refund_policy_url() : home_url( '/resi-e-rimborsi/' );

// La voce FAQ nel footer compare solo se esiste davvero una pagina pubblicata
// che usa il template FAQ del tema (nessun link a vuoto/segnaposto).
$faq_url  = '';
$faq_page = get_posts(
	array(
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'meta_key'       => '_wp_page_template',
		'meta_value'     => 'page-templates/template-faq.php',
	)
);
if ( ! empty( $faq_page ) ) {
	$faq_url = get_permalink( $faq_page[0]->ID );
}

$phone   = get_theme_mod( 'fa_phone', '+39 327 916 6457' );
$email   = get_theme_mod( 'fa_email', 'formulacqua@ionospec.it' );
$address = function_exists( 'fa_get_legal_address' ) ? fa_get_legal_address() : get_theme_mod( 'fa_address', '' );
$piva    = function_exists( 'fa_get_piva' ) ? fa_get_piva() : get_theme_mod( 'fa_piva', '' );
$hours   = get_theme_mod( 'fa_hours', '08:00–18:00' );
$whatsapp = get_theme_mod( 'fa_whatsapp', '+39 327 916 6457' );
$wa_digits = preg_replace( '/[^0-9]/', '', (string) $whatsapp );
$wa_url    = $wa_digits ? 'https://wa.me/' . $wa_digits : '';

$instagram = get_theme_mod( 'fa_instagram', 'https://www.instagram.com/formulacqua/' );
if ( ! $instagram || '#' === $instagram ) {
	$instagram = 'https://www.instagram.com/formulacqua/';
}

$footer_cats = function_exists( 'fa_get_menu_categories' )
	? fa_get_menu_categories()
	: ( function_exists( 'fa_get_nav_categories' ) ? fa_get_nav_categories() : array() );
$footer_cats = array_slice( $footer_cats, 0, 6 );
?>
</main>

<footer class="fa-footer">
	<div class="fa-footer__glow" aria-hidden="true"></div>
	<div class="fa-footer__wave" aria-hidden="true">
		<svg viewBox="0 0 1440 80" preserveAspectRatio="none">
			<path d="M0,40 C240,80 480,0 720,40 C960,80 1200,0 1440,40 L1440,80 L0,80 Z" fill="#071B3A"/>
		</svg>
	</div>

	<div class="fa-footer__main">
		<div class="fa-container">
			<div class="fa-footer__grid">

				<div class="fa-footer__col fa-footer__brand">
					<?php
					$fa_footer_svg      = FA_DIR . '/assets/images/logo-fa-footer.svg';
					$fa_footer_png      = FA_DIR . '/assets/images/logo-fa-footer.png';
					$fa_footer_logo_alt = get_bloginfo( 'name' );
					?>
					<div class="fa-footer__brand-lockup">
					<div class="fa-footer__logo">
						<?php if ( file_exists( $fa_footer_svg ) ) : ?>
							<?php
							// SVG vettoriale inline (non dentro <a>: evita underline/trattini del browser).
							$fa_svg = file_get_contents( $fa_footer_svg ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
							if ( false !== $fa_svg ) {
								$fa_svg = preg_replace( '/<script\b[^>]*>.*?<\/script>/is', '', $fa_svg );
								$fa_svg = preg_replace( '/\s(onload|onclick|onerror)=("|\')[^"\']*\2/i', '', $fa_svg );
								// Wordmark e tagline escono dal file e diventano elementi flex separati:
								// il viewBox del marchio usa il bounding box effettivo F+goccia+A.
								$fa_svg = preg_replace( '/<!-- Wordmark -->.*<\/svg>/s', '</svg>', $fa_svg );
								$fa_svg = preg_replace( '/\sviewBox="[^"]*"/', ' viewBox="198 14 342 204"', $fa_svg, 1 );
								$fa_svg = preg_replace( '/\soverflow="[^"]*"/', '', $fa_svg, 1 );
								if ( false === strpos( $fa_svg, 'aria-label' ) ) {
									$fa_svg = preg_replace( '/<svg\b/', '<svg class="fa-footer__logo-img" overflow="visible" role="img" aria-hidden="true"', $fa_svg, 1 );
								} else {
									$fa_svg = preg_replace( '/<svg\b/', '<svg class="fa-footer__logo-img" overflow="visible" aria-hidden="true"', $fa_svg, 1 );
								}
								echo $fa_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							}
							?>
						<?php else : ?>
							<?php
							$fa_footer_logo_file = file_exists( $fa_footer_png ) ? $fa_footer_png : ( FA_DIR . '/assets/images/logo-fa.png' );
							$fa_footer_logo_url  = FA_URI . '/assets/images/' . basename( $fa_footer_logo_file );
							if ( file_exists( $fa_footer_logo_file ) ) {
								$fa_footer_logo_url .= '?v=' . rawurlencode( (string) filemtime( $fa_footer_logo_file ) );
							}
							?>
							<img
								class="fa-footer__logo-img"
								src="<?php echo esc_url( $fa_footer_logo_url ); ?>"
								alt=""
								width="171"
								height="102"
								decoding="async"
								loading="eager"
								fetchpriority="low"
							>
						<?php endif; ?>
					</div>
					<svg class="fa-footer__wordmark" viewBox="188.15 237 296.7 39" overflow="visible" aria-hidden="true" focusable="false">
						<text
							x="340"
							y="268"
							text-anchor="middle"
							font-family="Montserrat, Plus Jakarta Sans, Avenir Next, Gotham, Helvetica Neue, Arial, sans-serif"
							font-size="32"
							font-weight="700"
							letter-spacing="7"
						>
							<tspan fill="#FFFFFF">Formul</tspan><tspan fill="#5EC8F8">Acqua</tspan>
						</text>
					</svg>
					<svg class="fa-footer__tagline-svg" viewBox="132 292 416 14" overflow="visible" aria-hidden="true" focusable="false">
						<g class="fa-logo-tagline" opacity="0.92">
							<line x1="132" y1="299" x2="188" y2="299" stroke="#E8F4FF" stroke-width="1" stroke-linecap="round" opacity="0.5"/>
							<text
								x="340"
								y="303"
								text-anchor="middle"
								font-family="Montserrat, Plus Jakarta Sans, Avenir Next, Gotham, Helvetica Neue, Arial, sans-serif"
								font-size="11"
								font-weight="500"
								letter-spacing="4"
								fill="#E8F4FF"
							>TUTTO PER LA TUA PISCINA</text>
							<line x1="492" y1="299" x2="548" y2="299" stroke="#E8F4FF" stroke-width="1" stroke-linecap="round" opacity="0.5"/>
						</g>
					</svg>
						<a
							href="<?php echo esc_url( home_url( '/' ) ); ?>"
							class="fa-footer__logo-hit"
							rel="home"
							aria-label="<?php echo esc_attr( $fa_footer_logo_alt ); ?>"
						></a>
					</div>
					<p class="fa-footer__desc"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Tutto per la tua piscina. Prodotti selezionati, assistenza specializzata e spedizioni rapide in tutta Italia.' ) : 'Tutto per la tua piscina. Prodotti selezionati, assistenza specializzata e spedizioni rapide in tutta Italia.' ); ?></p>
				</div>

				<div class="fa-footer__col">
					<h4><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Categorie' ) : 'Categorie' ); ?></h4>
					<ul class="fa-footer__links">
						<?php if ( class_exists( 'WooCommerce' ) ) : ?>
							<li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Tutti i prodotti' ) : 'Tutti i prodotti' ); ?></a></li>
						<?php endif; ?>
						<?php foreach ( $footer_cats as $fcat ) : ?>
							<li><a href="<?php echo esc_url( $fcat['url'] ); ?>"><?php echo esc_html( $fcat['name'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>

				<div class="fa-footer__col">
					<h4><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Informazioni' ) : 'Informazioni' ); ?></h4>
					<ul class="fa-footer__links">
						<li><a href="<?php echo esc_url( $terms_url ); ?>"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Termini e Condizioni' ) : 'Termini e Condizioni' ); ?></a></li>
						<li><a href="<?php echo esc_url( $refund_url ); ?>"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Resi e Rimborsi' ) : 'Resi e Rimborsi' ); ?></a></li>
						<li><a href="<?php echo esc_url( $privacy_url ); ?>"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Privacy Policy' ) : 'Privacy Policy' ); ?></a></li>
						<li><a href="<?php echo esc_url( $cookie_url ); ?>"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Cookie Policy' ) : 'Cookie Policy' ); ?></a></li>
					</ul>
				</div>

				<div class="fa-footer__col">
					<h4><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Assistenza' ) : 'Assistenza' ); ?></h4>
					<ul class="fa-footer__links">
						<li><a href="<?php echo esc_url( $contact_url ); ?>"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Contatti / Assistenza' ) : 'Contatti / Assistenza' ); ?></a></li>
						<?php if ( $faq_url ) : ?>
							<li><a href="<?php echo esc_url( $faq_url ); ?>"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'FAQ' ) : 'FAQ' ); ?></a></li>
						<?php endif; ?>
						<?php if ( class_exists( 'WooCommerce' ) ) : ?>
							<li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Il mio account' ) : 'Il mio account' ); ?></a></li>
							<li><a href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Carrello' ) : 'Carrello' ); ?></a></li>
						<?php endif; ?>
					</ul>
				</div>

				<div class="fa-footer__col fa-footer__col--contact">
					<h4><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Contatti' ) : 'Contatti' ); ?></h4>
					<ul class="fa-footer__contact">
						<?php if ( $phone ) : ?>
						<li>
							<span class="fa-footer__contact-icon" aria-hidden="true">
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 11.042 11.042 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 11.042 11.042 0 002.81.7A2 2 0 0122 16.92z"/></svg>
							</span>
							<span class="fa-footer__contact-body">
								<span class="fa-footer__contact-label"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Telefono' ) : 'Telefono' ); ?></span>
								<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
							</span>
						</li>
						<?php endif; ?>
						<?php if ( $email ) : ?>
						<li>
							<span class="fa-footer__contact-icon" aria-hidden="true">
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
							</span>
							<span class="fa-footer__contact-body">
								<span class="fa-footer__contact-label"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Email' ) : 'Email' ); ?></span>
								<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
							</span>
						</li>
						<?php endif; ?>
						<?php if ( $hours || $wa_url ) : ?>
						<li>
							<span class="fa-footer__contact-icon fa-footer__contact-icon--wa" aria-hidden="true">
								<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
							</span>
							<span class="fa-footer__contact-body">
								<?php if ( $wa_url ) : ?>
									<a class="fa-footer__contact-label" href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Assistenza WhatsApp' ) : 'Assistenza WhatsApp' ); ?></a>
								<?php else : ?>
									<span class="fa-footer__contact-label"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Assistenza WhatsApp' ) : 'Assistenza WhatsApp' ); ?></span>
								<?php endif; ?>
								<?php if ( $hours ) : ?>
									<span class="fa-footer__contact-meta"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( $hours ) : $hours ); ?></span>
								<?php endif; ?>
							</span>
						</li>
						<?php endif; ?>
					</ul>
				</div>

			</div>

			<?php if ( $wa_url || $email ) : ?>
				<div class="fa-footer__assist">
					<div class="fa-footer__assist-grid">
						<?php if ( $wa_url ) : ?>
						<a
							href="<?php echo esc_url( $wa_url . '?text=' . rawurlencode( fa_t( 'Ciao! Vorrei un consiglio sui prodotti per la piscina.' ) ) ); ?>"
							class="fa-footer__assist-link fa-footer__assist-link--whatsapp"
								target="_blank"
								rel="noopener noreferrer"
							>
								<span class="fa-footer__assist-icon" aria-hidden="true">
									<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
								</span>
								<span class="fa-footer__assist-copy">
									<strong><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Assistenza clienti' ) : 'Assistenza clienti' ); ?></strong>
									<span><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Hai bisogno di un consiglio? Scrivici su WhatsApp, ti risponde un esperto.' ) : 'Hai bisogno di un consiglio? Scrivici su WhatsApp, ti risponde un esperto.' ); ?></span>
								</span>
								<span class="fa-footer__assist-cta"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Scrivici' ) : 'Scrivici' ); ?></span>
							</a>
						<?php endif; ?>
						<?php if ( $email ) : ?>
							<a
								href="<?php echo esc_url( 'mailto:' . $email ); ?>"
								class="fa-footer__assist-link fa-footer__assist-link--email"
							>
								<span class="fa-footer__assist-icon" aria-hidden="true">
									<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
								</span>
								<span class="fa-footer__assist-copy">
									<strong><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Assistenza via email' ) : 'Assistenza via email' ); ?></strong>
									<span>
										<?php
										echo esc_html(
											sprintf(
												function_exists( 'fa_t' ) ? fa_t( 'Scrivici a %s per domande su prodotti, ordini o spedizioni: ti risponderemo il prima possibile.' ) : 'Scrivici a %s per domande su prodotti, ordini o spedizioni: ti risponderemo il prima possibile.',
												$email
											)
										);
										?>
									</span>
								</span>
								<span class="fa-footer__assist-cta"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Scrivici' ) : 'Scrivici' ); ?></span>
							</a>
						<?php endif; ?>
						<?php
						/* URL ufficiale: Customizer → Informazioni Contatto → Instagram URL (`fa_instagram`). */
						$ig_url = ( $instagram && '#' !== $instagram ) ? $instagram : '';
						$ig_tag = $ig_url ? 'a' : 'div';
						?>
						<<?php echo $ig_tag; ?>
							<?php if ( $ig_url ) : ?>
								href="<?php echo esc_url( $ig_url ); ?>"
								target="_blank"
								rel="noopener noreferrer"
							<?php endif; ?>
							class="fa-footer__assist-link fa-footer__assist-link--instagram"
						>
							<span class="fa-footer__assist-icon" aria-hidden="true">
								<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="5"/><circle cx="17.5" cy="6.5" r="1.5" fill="currentColor" stroke="none"/></svg>
							</span>
							<span class="fa-footer__assist-copy">
								<strong><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Instagram' ) : 'Instagram' ); ?></strong>
								<span><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Seguici su Instagram' ) : 'Seguici su Instagram' ); ?></span>
							</span>
							<span class="fa-footer__assist-cta"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Apri Instagram' ) : 'Apri Instagram' ); ?></span>
						</<?php echo $ig_tag; ?>>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<div class="fa-footer__bottom">
		<div class="fa-container fa-footer__bottom-inner">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> FormulAcqua<?php echo $piva ? ' — ' . esc_html( fa_t( 'P.IVA' ) ) . ' ' . esc_html( $piva ) : ''; ?>. <?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Tutti i diritti riservati.' ) : 'Tutti i diritti riservati.' ); ?></p>
			<nav class="fa-footer__legal" aria-label="<?php echo esc_attr( function_exists( 'fa_t' ) ? fa_t( 'Informazioni' ) : 'Informazioni' ); ?>">
				<a href="<?php echo esc_url( $privacy_url ); ?>"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Privacy Policy' ) : 'Privacy Policy' ); ?></a>
				<a href="<?php echo esc_url( $cookie_url ); ?>"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Cookie Policy' ) : 'Cookie Policy' ); ?></a>
				<a href="<?php echo esc_url( $terms_url ); ?>"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Termini e Condizioni' ) : 'Termini e Condizioni' ); ?></a>
				<a href="<?php echo esc_url( $refund_url ); ?>"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Resi e Rimborsi' ) : 'Resi e Rimborsi' ); ?></a>
				<a href="<?php echo esc_url( $contact_url ); ?>"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Contatti' ) : 'Contatti' ); ?></a>
			</nav>
			<div class="fa-footer__payments fa-footer__payments--badges" aria-label="<?php echo esc_attr( function_exists( 'fa_t' ) ? fa_t( 'Pagamenti sicuri' ) : 'Pagamenti sicuri' ); ?>">
				<span class="fa-footer__payments-label">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>
					<span><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Pagamenti sicuri' ) : 'Pagamenti sicuri' ); ?></span>
				</span>
				<span class="fa-footer__pay-badges">
					<span class="fa-footer__pay-badge" title="Visa">
						<svg viewBox="0 0 24 24" height="12" aria-hidden="true"><path fill="#1A1F71" d="M9.112 8.262L5.97 15.758H3.92L2.374 9.775c-.094-.368-.175-.503-.461-.658C1.447 8.864.677 8.627 0 8.479l.046-.217h3.3a.904.904 0 01.894.764l.817 4.338 2.018-5.102zm8.033 5.049c.008-1.979-2.736-2.088-2.717-2.972.006-.269.262-.555.822-.628a3.66 3.66 0 011.913.336l.34-1.59a5.207 5.207 0 00-1.814-.333c-1.917 0-3.266 1.02-3.278 2.479-.012 1.079.963 1.68 1.698 2.04.756.367 1.01.603 1.006.931-.005.504-.602.725-1.16.734-.975.015-1.54-.263-1.992-.473l-.351 1.642c.453.208 1.289.39 2.156.398 2.037 0 3.37-1.006 3.377-2.564m5.061 2.447H24l-1.565-7.496h-1.656a.883.883 0 00-.826.55l-2.909 6.946h2.036l.405-1.12h2.488zm-2.163-2.656l1.02-2.815.588 2.815zm-8.16-4.84l-1.603 7.496H8.34l1.605-7.496z"/></svg>
						<span class="screen-reader-text">Visa</span>
					</span>
					<span class="fa-footer__pay-badge" title="Mastercard">
						<svg viewBox="0 0 24 16" height="12" aria-hidden="true"><circle cx="9" cy="8" r="6" fill="#EB001B"/><circle cx="15" cy="8" r="6" fill="#F79E1B"/><path fill="#FF5F00" d="M12 3.15a6 6 0 0 1 0 9.7 6 6 0 0 1 0-9.7z"/></svg>
						<span class="screen-reader-text">Mastercard</span>
					</span>
					<span class="fa-footer__pay-badge" title="PayPal">
						<svg viewBox="0 0 24 24" height="13" aria-hidden="true"><path fill="#003087" d="M7.016 19.198h-4.2a.562.562 0 0 1-.555-.65L5.093.584A.692.692 0 0 1 5.776 0h7.222c3.417 0 5.904 2.488 5.846 5.5-.006.25-.027.5-.066.747A6.794 6.794 0 0 1 12.071 12H8.743a.69.69 0 0 0-.682.583l-.325 2.056-.013.083-.692 4.39-.015.087z"/><path fill="#009CDE" d="M19.79 6.142c-.01.087-.01.175-.023.261a7.76 7.76 0 0 1-7.695 6.598H9.007l-.283 1.795-.013.083-.692 4.39-.134.843-.014.088H6.86l-.497 3.15a.562.562 0 0 0 .555.65h3.612c.34 0 .63-.249.683-.585l.952-6.031a.692.692 0 0 1 .683-.584h2.126a6.793 6.793 0 0 0 6.707-5.752c.306-1.95-.466-3.744-1.89-4.906z"/></svg>
						<span class="screen-reader-text">PayPal</span>
					</span>
				</span>
			</div>
		</div>
	</div>
</footer>

<?php
$fa_show_wa_float    = defined( 'FA_SHOW_FLOATING_WHATSAPP' ) ? FA_SHOW_FLOATING_WHATSAPP : true;
$fa_show_email_float = defined( 'FA_SHOW_FLOATING_EMAIL' ) ? FA_SHOW_FLOATING_EMAIL : true;
$fa_show_ig_float    = defined( 'FA_SHOW_FLOATING_INSTAGRAM' ) ? FA_SHOW_FLOATING_INSTAGRAM : true;
$ig_float_url        = ( $instagram && '#' !== $instagram ) ? $instagram : '';
?>
<?php if ( ( $fa_show_wa_float && $wa_url ) || ( $fa_show_email_float && $email ) || $fa_show_ig_float ) : ?>
<div class="fa-float-contacts">
	<?php if ( $fa_show_ig_float ) : ?>
		<?php if ( $ig_float_url ) : ?>
			<a href="<?php echo esc_url( $ig_float_url ); ?>" class="fa-float-btn fa-instagram-float" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( function_exists( 'fa_t' ) ? fa_t( 'Apri Instagram' ) : 'Apri Instagram' ); ?>">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="5"/><circle cx="17.5" cy="6.5" r="1.5" fill="currentColor" stroke="none"/></svg>
			</a>
		<?php else : ?>
			<span class="fa-float-btn fa-instagram-float" role="img" aria-label="<?php echo esc_attr( function_exists( 'fa_t' ) ? fa_t( 'Instagram' ) : 'Instagram' ); ?>">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="5"/><circle cx="17.5" cy="6.5" r="1.5" fill="currentColor" stroke="none"/></svg>
			</span>
		<?php endif; ?>
	<?php endif; ?>
	<?php if ( $fa_show_email_float && $email ) : ?>
		<a href="<?php echo esc_url( 'mailto:' . $email ); ?>" class="fa-float-btn fa-email-float" aria-label="<?php echo esc_attr( function_exists( 'fa_t' ) ? fa_t( 'Scrivici una email' ) : 'Scrivici una email' ); ?>">
			<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
		</a>
	<?php endif; ?>
	<?php if ( $fa_show_wa_float && $wa_url ) : ?>
		<a href="<?php echo esc_url( $wa_url . '?text=' . rawurlencode( fa_t( 'Ciao! Vorrei informazioni sui vostri prodotti per piscina.' ) ) ); ?>" class="fa-float-btn fa-whatsapp" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( fa_t( 'Contattaci su WhatsApp' ) ); ?>">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
		</a>
	<?php endif; ?>
</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
