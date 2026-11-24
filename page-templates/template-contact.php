<?php
/**
 * Template Name: Contatti
 *
 * @package Formula_Acqua
 */

get_header();
?>

<div class="fa-page-header">
    <div class="fa-container">
        <h1><?php fa_te( 'Contattaci' ); ?></h1>
        <p><?php fa_te( 'Siamo a tua disposizione per qualsiasi informazione' ); ?></p>
    </div>
</div>

<div class="fa-container fa-content-area">
    <div class="fa-contact-grid">
        <div class="fa-contact-form-wrap">
            <h2><?php fa_te( 'Inviaci un messaggio' ); ?></h2>
            <form class="fa-contact-form" id="fa-contact-form">
                <div class="fa-form-row fa-form-row--half">
                    <div class="fa-form-group">
                        <label for="contact-name"><?php fa_te( 'Nome e cognome *' ); ?></label>
                        <input type="text" id="contact-name" name="name" required>
                    </div>
                    <div class="fa-form-group">
                        <label for="contact-email"><?php echo esc_html( fa_t( 'Email' ) ); ?> *</label>
                        <input type="email" id="contact-email" name="email" required>
                    </div>
                </div>
                <div class="fa-form-row fa-form-row--half">
                    <div class="fa-form-group">
                        <label for="contact-phone"><?php fa_te( 'Telefono' ); ?></label>
                        <input type="tel" id="contact-phone" name="phone">
                    </div>
                    <div class="fa-form-group">
                        <label for="contact-subject"><?php fa_te( 'Oggetto *' ); ?></label>
                        <select id="contact-subject" name="subject" required>
                            <option value=""><?php fa_te( 'Seleziona...' ); ?></option>
                            <option value="Informazioni prodotti"><?php fa_te( 'Informazioni prodotti' ); ?></option>
                            <option value="Assistenza ordine"><?php fa_te( 'Assistenza ordine' ); ?></option>
                            <option value="Reso o rimborso"><?php fa_te( 'Reso o rimborso' ); ?></option>
                            <option value="Collaborazione"><?php fa_te( 'Collaborazione' ); ?></option>
                            <option value="Altro"><?php fa_te( 'Altro' ); ?></option>
                        </select>
                    </div>
                </div>
                <div class="fa-form-group">
                    <label for="contact-message"><?php fa_te( 'Messaggio *' ); ?></label>
                    <textarea id="contact-message" name="message" rows="6" required></textarea>
                </div>
                <label class="fa-form-checkbox">
                    <input type="checkbox" name="privacy" required>
                    <span><?php echo fa_t( 'Ho letto e accetto la' ); ?> <a href="<?php echo esc_url( function_exists( 'fa_get_legal_page_url' ) ? fa_get_legal_page_url( 'privacy-policy' ) : home_url( '/privacy-policy/' ) ); ?>" target="_blank">Privacy Policy</a> *</span>
                </label>
                <button type="submit" class="fa-btn fa-btn--primary fa-btn--lg"><?php fa_te( 'Invia messaggio' ); ?></button>
                <div class="fa-form-message" id="fa-contact-message"></div>
            </form>
        </div>

        <div class="fa-contact-info-wrap">
            <div class="fa-contact-info-card">
                <h3><?php fa_te( 'Informazioni di contatto' ); ?></h3>
                <ul class="fa-contact-info-list">
                    <?php $fa_contact_address = function_exists( 'fa_get_legal_address' ) ? fa_get_legal_address() : get_theme_mod( 'fa_address', '' ); ?>
                    <?php if ( $fa_contact_address ) : ?>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <div><strong><?php fa_te( 'Sede legale' ); ?></strong><span><?php echo esc_html( $fa_contact_address ); ?></span></div>
                    </li>
                    <?php endif; ?>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 11 11 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 11 11 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                        <div><strong><?php fa_te( 'Telefono / WhatsApp' ); ?></strong><span><a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', get_theme_mod( 'fa_phone', '+39 327 916 6457' ) ) ); ?>"><?php echo esc_html( get_theme_mod( 'fa_phone', '+39 327 916 6457' ) ); ?></a></span></div>
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <div><strong>PEC</strong><span><a href="mailto:<?php echo esc_attr( get_theme_mod( 'fa_email', 'formulacqua@ionospec.it' ) ); ?>"><?php echo esc_html( get_theme_mod( 'fa_email', 'formulacqua@ionospec.it' ) ); ?></a></span></div>
                    </li>
                    <?php $fa_contact_hours = trim( (string) get_theme_mod( 'fa_hours', '08:00–18:00' ) ); ?>
                    <?php if ( $fa_contact_hours ) : ?>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        <div><strong><?php fa_te( 'Assistenza WhatsApp' ); ?></strong><span><?php echo esc_html( fa_t( $fa_contact_hours ) ); ?></span></div>
                    </li>
                    <?php endif; ?>
                </ul>
                <?php
                $wa = preg_replace( '/[^0-9]/', '', get_theme_mod( 'fa_whatsapp', '+39 327 916 6457' ) );
                if ( $wa ) :
                ?>
                <a href="https://wa.me/<?php echo esc_attr( $wa ); ?>" class="fa-btn fa-btn--whatsapp" target="_blank" rel="noopener noreferrer"><?php fa_te( 'Scrivici su WhatsApp' ); ?></a>
                <?php endif; ?>
            </div>
            <?php if ( $fa_contact_address ) : ?>
            <div class="fa-contact-map">
                <iframe src="https://www.google.com/maps?q=<?php echo rawurlencode( $fa_contact_address ); ?>&output=embed" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy" title="<?php echo esc_attr( fa_t( 'Mappa' ) ); ?>"></iframe>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
