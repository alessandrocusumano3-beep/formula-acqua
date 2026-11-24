<?php
/**
 * Multilingua Formula Acqua — Polylang / WPML ready + WooCommerce.
 *
 * @package Formula_Acqua
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Lingue supportate dal tema.
 *
 * @return array<string,array{code:string,locale:string,name:string,flag:string,hreflang:string}>
 */
function fa_get_supported_languages() {
	return array(
		'it' => array(
			'code'     => 'it',
			'locale'   => 'it_IT',
			'name'     => 'Italiano',
			'flag'     => '🇮🇹',
			'hreflang' => 'it',
		),
		'en' => array(
			'code'     => 'en',
			'locale'   => 'en_US',
			'name'     => 'English',
			'flag'     => '🇬🇧',
			'hreflang' => 'en',
		),
		'fr' => array(
			'code'     => 'fr',
			'locale'   => 'fr_FR',
			'name'     => 'Français',
			'flag'     => '🇫🇷',
			'hreflang' => 'fr',
		),
		'de' => array(
			'code'     => 'de',
			'locale'   => 'de_DE',
			'name'     => 'Deutsch',
			'flag'     => '🇩🇪',
			'hreflang' => 'de',
		),
		'es' => array(
			'code'     => 'es',
			'locale'   => 'es_ES',
			'name'     => 'Español',
			'flag'     => '🇪🇸',
			'hreflang' => 'es',
		),
	);
}

/**
 * Codici lingua ammessi.
 *
 * @return string[]
 */
function fa_get_lang_codes() {
	return array_keys( fa_get_supported_languages() );
}

/**
 * True se Polylang è attivo.
 *
 * @return bool
 */
function fa_has_polylang() {
	return function_exists( 'pll_current_language' ) && function_exists( 'pll_the_languages' );
}

/**
 * True se Polylang o WPML sono attivi.
 *
 * @return bool
 */
function fa_has_multilang_plugin() {
	return fa_has_polylang() || ( defined( 'ICL_SITEPRESS_VERSION' ) && function_exists( 'icl_get_languages' ) );
}

/**
 * Lingua attiva (it|en|fr|de|es).
 *
 * @return string
 */
function fa_get_lang() {
	$allowed = fa_get_lang_codes();

	if ( function_exists( 'pll_current_language' ) ) {
		$pll = strtolower( (string) pll_current_language( 'slug' ) );
		if ( $pll && in_array( $pll, $allowed, true ) ) {
			return $pll;
		}
	}

	if ( defined( 'ICL_LANGUAGE_CODE' ) && ICL_LANGUAGE_CODE ) {
		$icl = strtolower( (string) ICL_LANGUAGE_CODE );
		if ( in_array( $icl, $allowed, true ) ) {
			return $icl;
		}
	}

	if ( ! empty( $_COOKIE['fa_lang'] ) ) {
		$cookie = sanitize_key( wp_unslash( $_COOKIE['fa_lang'] ) );
		if ( in_array( $cookie, $allowed, true ) ) {
			return $cookie;
		}
	}

	return 'it';
}

/**
 * Traduce una stringa del tema (Polylang String Translations → gettext).
 *
 * @param string $string Stringa sorgente (italiano).
 * @return string
 */
function fa_t( $string ) {
	if ( function_exists( 'pll__' ) ) {
		return pll__( $string );
	}
	return __( $string, 'formula-acqua' );
}

/**
 * Echo sicuro di fa_t().
 *
 * @param string $string Stringa.
 */
function fa_te( $string ) {
	echo esc_html( fa_t( $string ) );
}

/**
 * Registra stringhe tema in Polylang.
 */
function fa_register_polylang_strings() {
	if ( ! function_exists( 'pll_register_string' ) ) {
		return;
	}

	$strings = array(
		'Home',
		'Cerca',
		'Account',
		'Preferiti',
		'I tuoi prodotti',
		'%d prodotto salvato',
		'%d prodotti salvati',
		'La tua lista preferiti è vuota',
		'Aggiungi i prodotti che ti interessano cliccando sul cuore nelle schede prodotto.',
		'Rimuovi',
		'Carrello',
		'Apri menu',
		'Chiudi menu',
		'Tutte le categorie',
		'Menu',
		'Navigazione',
		'Categorie',
		'Categorie rapide',
		'Vai allo shop completo',
		'Torna alla homepage',
		'Il mio account',
		'Accedi o registrati',
		'Prodotti salvati',
		'Cerca tra migliaia di prodotti per la piscina...',
		'Cerca prodotti per la piscina...',
		'Spedizione gratuita sopra 79€',
		'Reso entro 14 giorni',
		'Assistenza specializzata piscine',
		'Tutto per la tua piscina. Prodotti selezionati, assistenza specializzata e spedizioni rapide in tutta Italia.',
		'Tutti i prodotti',
		'Informazioni',
		'Assistenza',
		'Contatti',
		'Contatti / Assistenza',
		'FAQ',
		'Email',
		'PEC',
		'P.IVA',
		'Offerta',
		'Novità',
		'Più venduto',
		'Privacy Policy',
		'Cookie Policy',
		'Termini e Condizioni',
		'Resi e Rimborsi',
		'Tutti i diritti riservati.',
		'Assistenza clienti',
		'Hai bisogno di un consiglio? Scrivici su WhatsApp, ti risponde un esperto.',
		'Assistenza via email',
		'Seguici su Instagram',
		'Apri Instagram',
		'Instagram',
		'Scrivici',
		'Scrivici una email',
		'Scrivici a %s per domande su prodotti, ordini o spedizioni: ti risponderemo il prima possibile.',
		'Ciao! Vorrei un consiglio sui prodotti per la piscina.',
		'Ciao! Vorrei informazioni sui vostri prodotti per piscina.',
		'Contattaci su WhatsApp',
		'Pagamenti sicuri',
		'Seleziona lingua',
		'Acquista ora',
		'Sfoglia categorie',
		'Shopping per categoria',
		'Categorie principali',
		'Vedi tutto lo shop',
		'Acquista',
		'Tutto per la tua piscina',
		'Robot, chimici, pompe e accessori: lo shop specializzato, con spedizioni rapide in tutta Italia.',
		'Robot, chimici e accessori. Spedizioni rapide in Italia.',
		'Offerte in evidenza',
		'Prodotto precedente',
		'Prodotto successivo',
		'Robot',
		'Chimici',
		'Pompe',
		'Accessori',
		'Ricambi',
		'%d prodotti',
		'Scopri i prodotti',
		'Prezzi speciali',
		'Offerte del mese',
		'Tutte le offerte',
		'I preferiti dello shop',
		'Prodotti più venduti',
		'Vedi tutti',
		'Novità in vetrina',
		'Nuovi arrivi',
		'Brand selezionati',
		'Marchi principali',
		'Scelti per te',
		'Prodotti consigliati',
		'Vantaggi shop',
		'Spedizione rapida',
		'Consegna in 24/48h',
		'Carta, PayPal, bonifico',
		'Solo prodotti piscina',
		'Catalogo specializzato',
		'Reso facile',
		'14 giorni per ripensarci',
		'Aggiungi al carrello',
		'Contattaci',
		'Siamo a tua disposizione per qualsiasi informazione',
		'Inviaci un messaggio',
		'Nome e cognome *',
		'Telefono',
		'Oggetto *',
		'Seleziona...',
		'Informazioni prodotti',
		'Assistenza ordine',
		'Reso o rimborso',
		'Collaborazione',
		'Altro',
		'Messaggio *',
		'Ho letto e accetto la',
		'Invia messaggio',
		'Informazioni di contatto',
		'Sede legale',
		'Telefono / WhatsApp',
		'Assistenza WhatsApp',
		'Assistenza WhatsApp: %s',
		'08:00–18:00',
		'Scrivici su WhatsApp',
		'Mappa',
		'Area clienti',
		'Crea il tuo account',
		'Accedi',
		'Registrati in pochi secondi: ordini e preferiti sempre con te.',
		'Accedi con email e password per continuare i tuoi acquisti.',
		'Crea account',
		'Ricordami',
		'Email o nome utente',
		'es. mario@email.it',
		'La tua password',
		'Mostra password',
		'Nascondi password',
		'Password dimenticata?',
		'Nuovo cliente?',
		'Crea un account',
		'Nome utente',
		'Scegli un nome utente',
		'Indirizzo email',
		'Crea una password sicura',
		'Ti invieremo un link per impostare la password.',
		'Creando l’account accetti i',
		'e la',
		'Hai già un account?',
		'Il tuo spazio Formula Acqua',
		'Ordini, preferiti e acquisti sempre sotto controllo.',
		'Ordini sempre a portata',
		'Pagamento più veloce',
		'Preferiti sincronizzati',
		'Invia link di reset',
		'Ricordi la password?',
		'Crea una nuova password',
		'Ripeti nuova password',
		'Ripeti la password',
		'Salva nuova password',
		'Controlla la tua email',
		'Torna al login',
		'Panoramica',
		'I miei ordini',
		'Indirizzi',
		'Pagamenti',
		'Dati personali',
		'Esci',
		'Bentornato',
		'Gestisci ordini, indirizzi e preferenze dal tuo spazio personale Formula Acqua.',
		'Attivo',
		'Stato, dettagli e storico acquisti',
		'Fatturazione e spedizione',
		'Nome, email e password',
		'Il tuo carrello',
		'Riprendi gli articoli salvati',
		'Ordini recenti',
		'Vedi tutti',
		'Dettagli',
		'Non hai ancora effettuato ordini.',
		'Inizia a fare shopping',
		'Area personale',
		'Ciao',
		'cliente',
		'Continua lo shopping',
		'Pagina non trovata',
		'La pagina che cerchi non esiste o è stata spostata. Prova a cercare quello che ti serve o torna alla homepage.',
		'Nessun contenuto trovato',
		'Risultati per: "%s"',
		'Nessun risultato trovato',
		'Prova con termini diversi o torna alla %s.',
		'homepage',
		'Cerca...',
		'%d commento',
		'%d commenti',
		'Lascia un commento',
		'Invia',
		'Pagamento',
		'Rivedi i prodotti e procedi all’acquisto in sicurezza.',
		'Completa l’ordine in pochi passaggi sicuri.',
		'Chi siamo',
		'Formula Acqua: prodotti e soluzioni per la cura della piscina',
		'Formula Acqua nasce con un obiettivo preciso: rendere più semplice trovare online tutto quello che serve per mantenere la piscina pulita, efficiente e pronta da utilizzare.',
		'Siamo una realtà italiana specializzata nel mondo della piscina e della manutenzione. Nel nostro shop proponiamo prodotti per il trattamento dell’acqua, strumenti per la pulizia, ricambi e accessori piscina, cercando di offrire una scelta chiara e adatta alle diverse necessità.',
		'Perché nasce Formula Acqua',
		'Gestire una piscina significa prestare attenzione a diversi aspetti: la qualità dell’acqua, il trattamento chimico, l’analisi dei valori, la pulizia dell’impianto e la manutenzione delle attrezzature.',
		'Formula Acqua nasce per aiutarti a gestire queste attività in modo più pratico, mettendo a disposizione prodotti utili per la cura della piscina durante tutto l’anno.',
		'Nel nostro catalogo trovi una selezione in continua crescita, pensata per accompagnarti nella manutenzione ordinaria e nella gestione delle esigenze più comuni.',
		'Qualità dell’acqua',
		'Trattamento',
		'Pulizia',
		'Manutenzione',
		'Un e-commerce pensato per chi ha una piscina',
		'Acquistare un prodotto non significa soltanto riceverlo a casa.',
		'Per questo cerchiamo di rendere ogni fase più semplice: dalla ricerca dell’articolo giusto fino all’assistenza dopo l’acquisto. L’obiettivo è offrire informazioni chiare e un servizio concreto, senza complicare la scelta.',
		'Che si tratti di una piscina fuori terra, di una piscina interrata o di una semplice esigenza legata alla qualità dell’acqua, puoi trovare soluzioni per il trattamento, la pulizia e la manutenzione.',
		'Il nostro percorso',
		'Il nostro percorso è iniziato da poco, ma l’obiettivo è costruire nel tempo un punto di riferimento affidabile per la manutenzione, il trattamento dell’acqua e la cura della piscina.',
		'Tutto il necessario per prenderti cura della tua piscina.',
		'Scopri il catalogo e trova prodotti, ricambi e accessori per la gestione della piscina in ogni stagione.',
		'Scopri il catalogo',
		'La nostra storia, la nostra passione per il mondo delle piscine',
		'Formula Acqua: qualità e passione dal 2014',
		'Formula Acqua è nata dalla passione di un team di professionisti del settore piscine con un obiettivo chiaro: rendere accessibile a tutti la manutenzione professionale della propria piscina.',
		'Da oltre 10 anni selezioniamo con cura i migliori prodotti chimici, robot automatici e accessori per garantire ai nostri clienti un\'esperienza d\'acquisto semplice, sicura e conveniente.',
		'Ogni prodotto presente nel nostro catalogo è stato testato e scelto per offrire il miglior rapporto qualità-prezzo.',
		'Il nostro team',
		'I nostri valori',
		'Qualità garantita',
		'Solo prodotti di comprovata qualità, testati e certificati per risultati eccellenti.',
		'Assistenza dedicata',
		'Team di esperti sempre disponibile per consulenze tecniche personalizzate.',
		'Prezzi competitivi',
		'Il miglior rapporto qualità-prezzo grazie ai rapporti diretti con i produttori.',
		'Spedizioni veloci',
		'Consegna in tutta Italia in 24/48 ore con corriere espresso tracciabile.',
		'Domande frequenti',
		'Trova le risposte alle domande più comuni',
		'Ordini e spedizioni',
		'Quali sono i tempi di consegna?',
		'Le spedizioni vengono effettuate con corriere espresso. I tempi di consegna sono di 24/48 ore lavorative per le principali città e 48/72 ore per isole e zone remote.',
		'Quanto costa la spedizione?',
		'La spedizione standard costa 6,90€. Per ordini superiori a 79€ la spedizione è gratuita in tutta Italia.',
		'Posso tracciare il mio ordine?',
		'Sì, riceverai un\'email con il codice di tracciamento per seguire la consegna in tempo reale.',
		'Quali metodi di pagamento accettate?',
		'Accettiamo carta di credito (Visa, Mastercard), PayPal, bonifico bancario e contrassegno con supplemento di 4€.',
		'I pagamenti sono sicuri?',
		'Tutte le transazioni sono protette da crittografia SSL a 256 bit. I dati della carta non vengono memorizzati.',
		'Resi e rimborsi',
		'Posso effettuare un reso?',
		'Hai 14 giorni dalla ricezione per effettuare il reso. Il prodotto deve essere integro e nella confezione originale.',
		'Come riceverò il rimborso?',
		'Il rimborso avviene sullo stesso metodo di pagamento utilizzato, entro 14 giorni dalla ricezione del reso.',
		'Prodotti',
		'Come scelgo il robot piscina giusto?',
		'La scelta dipende dalla dimensione della piscina, dal tipo di fondo e dalle funzioni desiderate. Contattaci per una consulenza gratuita.',
		'I prodotti chimici sono pericolosi?',
		'Sono sicuri se utilizzati seguendo le istruzioni. Si raccomanda di conservarli lontano dalla portata dei bambini.',
		'Non hai trovato la risposta?',
		'Contattaci direttamente, saremo felici di aiutarti.',
		'Shop Formula Acqua',
		'Robot, chimici, pompe, filtri e accessori per la tua piscina.',
		'Categoria',
		'Catalogo',
		'I migliori prodotti selezionati per questa categoria.',
		'Nessun prodotto',
		'Questa categoria è pronta: i prodotti verranno aggiunti a breve.',
		'Torna allo shop',
		'Valutazione %s su 5',
		'Rimuovi dai preferiti',
		'Aggiungi ai preferiti',
		'Nei preferiti',
		'Prodotto non valido.',
		'Prodotto non trovato.',
		'Inserisci un indirizzo email valido.',
		'Iscrizione completata con successo!',
		'Compila tutti i campi obbligatori.',
		'Messaggio inviato con successo!',
		'Errore nell\'invio. Riprova più tardi.',
	);

	foreach ( $strings as $string ) {
		pll_register_string( 'fa-' . sanitize_title( $string ), $string, 'Formula Acqua', false );
	}
}
add_action( 'init', 'fa_register_polylang_strings', 20 );

/**
 * URL per lo switch a una lingua (pagina corrente tradotta se disponibile).
 *
 * @param string $lang Codice lingua.
 * @return string
 */
function fa_get_lang_switch_url( $lang ) {
	$lang = sanitize_key( $lang );
	if ( ! in_array( $lang, fa_get_lang_codes(), true ) ) {
		$lang = 'it';
	}

	// Polylang: URL della traduzione della pagina/prodotto corrente.
	if ( function_exists( 'pll_the_languages' ) ) {
		$langs = pll_the_languages(
			array(
				'raw'           => 1,
				'hide_if_empty' => 0,
				'hide_current'  => 0,
			)
		);
		if ( is_array( $langs ) ) {
			foreach ( $langs as $item ) {
				$slug = isset( $item['slug'] ) ? strtolower( (string) $item['slug'] ) : '';
				if ( $slug === $lang && ! empty( $item['url'] ) ) {
					return $item['url'];
				}
			}
		}
		if ( function_exists( 'pll_home_url' ) ) {
			$home = pll_home_url( $lang );
			if ( $home ) {
				return $home;
			}
		}
	}

	// WPML.
	if ( function_exists( 'icl_get_languages' ) ) {
		$langs = icl_get_languages( 'skip_missing=0' );
		if ( is_array( $langs ) ) {
			foreach ( $langs as $code => $item ) {
				if ( strtolower( (string) $code ) === $lang && ! empty( $item['url'] ) ) {
					return $item['url'];
				}
			}
		}
	}

	// Fallback senza plugin multilingua.
	return add_query_arg( 'fa_lang', $lang );
}

/**
 * Elenco lingue per il selettore (sempre IT EN FR DE ES).
 *
 * @return array<int,array<string,mixed>>
 */
function fa_get_languages_for_switcher() {
	$supported = fa_get_supported_languages();
	$current   = fa_get_lang();
	$pll_map   = array();

	if ( function_exists( 'pll_the_languages' ) ) {
		$raw = pll_the_languages(
			array(
				'raw'           => 1,
				'hide_if_empty' => 0,
				'hide_current'  => 0,
			)
		);
		if ( is_array( $raw ) ) {
			foreach ( $raw as $item ) {
				$slug = isset( $item['slug'] ) ? strtolower( (string) $item['slug'] ) : '';
				if ( $slug ) {
					$pll_map[ $slug ] = $item;
				}
			}
		}
	} elseif ( function_exists( 'icl_get_languages' ) ) {
		$raw = icl_get_languages( 'skip_missing=0' );
		if ( is_array( $raw ) ) {
			foreach ( $raw as $code => $item ) {
				$slug = strtolower( (string) $code );
				$pll_map[ $slug ] = array(
					'url'          => isset( $item['url'] ) ? $item['url'] : '',
					'current_lang' => ! empty( $item['active'] ),
				);
			}
		}
	}

	$out = array();
	foreach ( $supported as $code => $meta ) {
		$url    = fa_get_lang_switch_url( $code );
		$active = ( $code === $current );

		if ( isset( $pll_map[ $code ] ) ) {
			if ( ! empty( $pll_map[ $code ]['url'] ) ) {
				$url = $pll_map[ $code ]['url'];
			}
			if ( isset( $pll_map[ $code ]['current_lang'] ) ) {
				$active = (bool) $pll_map[ $code ]['current_lang'];
			}
		}

		$out[] = array_merge(
			$meta,
			array(
				'url'    => $url,
				'active' => $active,
			)
		);
	}

	return $out;
}

/**
 * Cookie fallback quando Polylang/WPML non sono installati.
 */
function fa_handle_lang_switch() {
	if ( fa_has_multilang_plugin() ) {
		return;
	}
	if ( empty( $_GET['fa_lang'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}
	$lang = sanitize_key( wp_unslash( $_GET['fa_lang'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( ! in_array( $lang, fa_get_lang_codes(), true ) ) {
		return;
	}
	$path   = defined( 'COOKIEPATH' ) && COOKIEPATH ? COOKIEPATH : '/';
	$domain = defined( 'COOKIE_DOMAIN' ) ? COOKIE_DOMAIN : '';
	setcookie( 'fa_lang', $lang, time() + YEAR_IN_SECONDS, $path, $domain, is_ssl(), true );
	$_COOKIE['fa_lang'] = $lang;

	$redirect = remove_query_arg( 'fa_lang' );
	wp_safe_redirect( $redirect ? $redirect : home_url( '/' ) );
	exit;
}
add_action( 'init', 'fa_handle_lang_switch', 1 );

/**
 * Locale frontend (solo senza Polylang/WPML).
 *
 * @param string $locale Locale.
 * @return string
 */
function fa_filter_locale( $locale ) {
	if ( is_admin() && ! wp_doing_ajax() ) {
		return $locale;
	}
	if ( fa_has_multilang_plugin() ) {
		return $locale;
	}
	$langs = fa_get_supported_languages();
	$code  = fa_get_lang();
	return isset( $langs[ $code ]['locale'] ) ? $langs[ $code ]['locale'] : $locale;
}
add_filter( 'locale', 'fa_filter_locale', 1 );

/**
 * Markup selettore lingua (dropdown globo).
 *
 * @param string $variant header|topbar.
 * @return string
 */
function fa_get_lang_switcher_html( $variant = 'header' ) {
	$languages = fa_get_languages_for_switcher();
	$current   = null;
	foreach ( $languages as $lang ) {
		if ( ! empty( $lang['active'] ) ) {
			$current = $lang;
			break;
		}
	}
	if ( ! $current ) {
		$current = $languages[0];
	}

	$id    = 'fa-lang-menu-' . sanitize_html_class( $variant );
	$class = 'fa-lang-dropdown fa-lang-dropdown--' . sanitize_html_class( $variant );

	ob_start();
	?>
	<div class="<?php echo esc_attr( $class ); ?>" data-fa-lang-dropdown>
		<button
			type="button"
			class="fa-lang-dropdown__toggle"
			aria-expanded="false"
			aria-haspopup="listbox"
			aria-controls="<?php echo esc_attr( $id ); ?>"
			aria-label="<?php echo esc_attr( fa_t( 'Seleziona lingua' ) ); ?>"
		>
			<span class="fa-lang-dropdown__globe" aria-hidden="true">🌐</span>
			<span class="fa-lang-dropdown__code"><?php echo esc_html( strtoupper( $current['code'] ) ); ?></span>
			<span class="fa-lang-dropdown__arrow" aria-hidden="true">▼</span>
		</button>
		<ul class="fa-lang-dropdown__menu" id="<?php echo esc_attr( $id ); ?>" role="listbox" hidden>
			<?php foreach ( $languages as $lang ) : ?>
				<li role="option"<?php echo ! empty( $lang['active'] ) ? ' aria-selected="true"' : ''; ?>>
					<a
						href="<?php echo esc_url( $lang['url'] ); ?>"
						class="fa-lang-dropdown__option<?php echo ! empty( $lang['active'] ) ? ' is-active' : ''; ?>"
						hreflang="<?php echo esc_attr( $lang['hreflang'] ); ?>"
						lang="<?php echo esc_attr( $lang['code'] ); ?>"
						<?php echo ! empty( $lang['active'] ) ? ' aria-current="true"' : ''; ?>
					>
						<span class="fa-lang-dropdown__flag" aria-hidden="true"><?php echo esc_html( $lang['flag'] ); ?></span>
						<span class="fa-lang-dropdown__name"><?php echo esc_html( $lang['name'] ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Echo selettore lingua.
 *
 * @param string $variant Variant.
 */
function fa_lang_switcher( $variant = 'header' ) {
	echo fa_get_lang_switcher_html( $variant ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Rende "lingua-consapevoli" le pagine WooCommerce/WP che sono salvate
 * come un singolo ID in un'opzione (Carrello, Checkout, Account, Shop,
 * Termini, Resi/Rimborsi, Privacy). Polylang "free" non lo fa in automatico
 * per queste opzioni: senza questo filtro il sito continuerebbe ad aprire
 * sempre la pagina italiana anche cambiando lingua.
 *
 * @param int|string $page_id ID pagina salvato nell'opzione (lingua di default).
 * @return int|string
 */
function fa_polylang_aware_page_id( $page_id ) {
	if ( ! $page_id || ! function_exists( 'pll_get_post' ) ) {
		return $page_id;
	}
	$translated = pll_get_post( (int) $page_id );
	return $translated ? $translated : $page_id;
}
add_filter( 'option_woocommerce_cart_page_id', 'fa_polylang_aware_page_id' );
add_filter( 'option_woocommerce_checkout_page_id', 'fa_polylang_aware_page_id' );
add_filter( 'option_woocommerce_myaccount_page_id', 'fa_polylang_aware_page_id' );
add_filter( 'option_woocommerce_shop_page_id', 'fa_polylang_aware_page_id' );
add_filter( 'option_woocommerce_terms_page_id', 'fa_polylang_aware_page_id' );
add_filter( 'option_woocommerce_refund_returns_page_id', 'fa_polylang_aware_page_id' );
add_filter( 'option_wp_page_for_privacy_policy', 'fa_polylang_aware_page_id' );

/**
 * Il shortcode [products] di WooCommerce (usato in homepage per "Più venduti",
 * "Nuovi arrivi", "Consigliati") genera una cache (transient) basata su un hash
 * degli argomenti della query. Se la lingua non fa parte di quegli argomenti,
 * la cache viene condivisa fra tutte le lingue: la prima versione renderizzata
 * (es. italiano) resterebbe visibile su EN/FR/DE/ES per 30 giorni. Aggiungendo
 * 'lang' alla query si ottiene sia il filtro corretto sia una cache separata
 * per ogni lingua.
 *
 * @param array $query_args Argomenti della query generata dallo shortcode.
 * @return array
 */
function fa_shortcode_products_lang_query( $query_args ) {
	if ( function_exists( 'pll_current_language' ) ) {
		$lang = pll_current_language();
		if ( $lang ) {
			$query_args['lang'] = $lang;
		}
	}
	return $query_args;
}
add_filter( 'woocommerce_shortcode_products_query', 'fa_shortcode_products_lang_query' );

/**
 * Ordinamento catalogo in italiano (solo lingua IT).
 *
 * @param array $options Options.
 * @return array
 */
function fa_catalog_orderby_it( $options ) {
	if ( 'it' !== fa_get_lang() ) {
		return $options;
	}
	return array(
		'menu_order' => 'Ordina per',
		'popularity' => 'Popolarità',
		'rating'     => 'Valutazione',
		'date'       => 'Novità',
		'price'      => 'Prezzo: crescente',
		'price-desc' => 'Prezzo: decrescente',
	);
}
add_filter( 'woocommerce_catalog_orderby', 'fa_catalog_orderby_it', 20 );
add_filter( 'woocommerce_default_catalog_orderby_options', 'fa_catalog_orderby_it', 20 );

/**
 * Mappa traduzioni WooCommerce IT.
 *
 * @return array<string,string>
 */
function fa_get_woocommerce_it_map() {
	return array(
		// Sorting.
		'Default sorting'                 => 'Ordina per',
		'Sort by popularity'              => 'Popolarità',
		'Sort by average rating'          => 'Valutazione',
		'Sort by latest'                  => 'Novità',
		'Sort by price: low to high'      => 'Prezzo: crescente',
		'Sort by price: high to low'      => 'Prezzo: decrescente',
		'Sort by price'                   => 'Prezzo',
		'Relevance'                       => 'Rilevanza',

		// Shop / product.
		'Sale!'                           => 'Offerta!',
		'Sale'                            => 'Offerta',
		'Add to cart'                     => 'Aggiungi al carrello',
		'Select options'                  => 'Scegli opzioni',
		'View products'                   => 'Vedi prodotti',
		'Read more'                       => 'Scopri di più',
		'Out of stock'                    => 'Esaurito',
		'In stock'                        => 'Disponibile',
		'Available on backorder'          => 'Disponibile su ordinazione',
		'Description'                     => 'Descrizione',
		'Additional information'          => 'Informazioni aggiuntive',
		'Reviews'                         => 'Recensioni',
		'Related products'                => 'Prodotti correlati',
		'You may also like&hellip;'       => 'Potrebbe interessarti anche…',
		'You may be interested in&hellip;' => 'Potrebbe interessarti…',
		'SKU:'                            => 'COD:',
		'Category:'                       => 'Categoria:',
		'Categories:'                     => 'Categorie:',
		'Tag:'                            => 'Tag:',
		'Tags:'                           => 'Tag:',
		'Showing all %d results'          => 'Mostra tutti i %d risultati',
		'Showing the single result'       => 'Mostra 1 risultato',
		'Showing %1$d&ndash;%2$d of %3$d results' => 'Mostra %1$d–%2$d di %3$d risultati',
		'No products were found matching your selection.' => 'Nessun prodotto trovato con questi filtri.',
		'Search results'                  => 'Risultati di ricerca',
		'Search products&hellip;'         => 'Cerca prodotti…',
		'Search for:'                     => 'Cerca:',
		'Product'                         => 'Prodotto',
		'Products'                        => 'Prodotti',
		'Home'                            => 'Home',
		'Shop'                            => 'Shop',

		// Cart / checkout.
		'Cart'                            => 'Carrello',
		'View cart'                       => 'Vedi carrello',
		'Checkout'                        => 'Pagamento',
		'Proceed to checkout'             => 'Procedi al pagamento',
		'Update cart'                     => 'Aggiorna carrello',
		'Cart totals'                     => 'Totale carrello',
		'Cart updated.'                   => 'Carrello aggiornato.',
		'Subtotal'                        => 'Subtotale',
		'Total'                           => 'Totale',
		'Shipping'                        => 'Spedizione',
		'Billing'                         => 'Fatturazione',
		'Coupon code'                     => 'Codice sconto',
		'Apply coupon'                    => 'Applica codice',
		'Remove this item'                => 'Rimuovi questo articolo',
		'Quantity'                        => 'Quantità',
		'Price'                           => 'Prezzo',
		'Place order'                     => 'Conferma ordine',
		'Billing details'                 => 'Dati di fatturazione',
		'Ship to a different address?'    => 'Spedire a un indirizzo diverso?',
		'Your order'                      => 'Il tuo ordine',
		'Order notes'                     => 'Note ordine',
		'Notes about your order, e.g. special notes for delivery.' => 'Note sull’ordine, es. indicazioni per la consegna.',
		'Have a coupon?'                  => 'Hai un codice sconto?',
		'Click here to enter your code'   => 'Clicca qui per inserirlo',
		'Returning customer?'             => 'Sei già cliente?',
		'Click here to login'             => 'Clicca qui per accedere',
		'Free shipping'                   => 'Spedizione gratuita',
		'Flat rate'                       => 'Tariffa fissa',
		'Local pickup'                    => 'Ritiro in sede',
		'Calculate shipping'              => 'Calcola spedizione',
		'Continue shopping'               => 'Continua gli acquisti',
		'Return to shop'                  => 'Torna allo shop',
		'Your cart is currently empty.'   => 'Il carrello è vuoto.',
		'%s has been added to your cart.' => '%s è stato aggiunto al carrello.',
		'&ldquo;%s&rdquo; has been added to your cart.' => '“%s” è stato aggiunto al carrello.',
		'Undo?'                           => 'Annulla?',
		'Added to your cart.'             => 'Aggiunto al carrello.',
		'Product added to cart successfully' => 'Prodotto aggiunto al carrello',
		'Wishlist'                        => 'Preferiti',
		'Add to Wishlist'                 => 'Aggiungi ai preferiti',
		'Remove from Wishlist'            => 'Rimuovi dai preferiti',
		'Browse Wishlist'                 => 'Vedi preferiti',
		'Product added!'                  => 'Prodotto aggiunto!',
		'The product is already in the wishlist!' => 'Il prodotto è già nei preferiti!',
		'Product added to wishlist successfully' => 'Prodotto aggiunto ai preferiti',
		'Product removed from wishlist successfully' => 'Prodotto rimosso dai preferiti',
		'Account pages'                   => 'Pagine account',
		'Downloads'                       => 'Download',
		'No downloads available yet.'     => 'Nessun download disponibile.',
		'Customer login'                  => 'Accesso cliente',
		'Username or email'               => 'Email o nome utente',
		'Lost password?'                  => 'Password dimenticata?',
		'Order received'                  => 'Ordine ricevuto',
		'Thank you. Your order has been received.' => 'Grazie. Il tuo ordine è stato ricevuto.',
		'Order number:'                   => 'Numero ordine:',
		'Order number'                    => 'Numero ordine',
		'Payment method:'                 => 'Metodo di pagamento:',
		'Payment method'                  => 'Metodo di pagamento',
		'Total:'                          => 'Totale:',
		'Date:'                           => 'Data:',
		'Email:'                          => 'Email:',
		'Order details'                   => 'Dettagli ordine',
		'Customer details'                => 'Dati cliente',
		'Note:'                           => 'Nota:',

		// Account / addresses.
		'Billing address'                 => 'Indirizzo di fatturazione',
		'Shipping address'                => 'Indirizzo di spedizione',
		'Billing Address'                 => 'Indirizzo di fatturazione',
		'Shipping Address'                => 'Indirizzo di spedizione',
		'Add Billing Address'             => 'Aggiungi indirizzo di fatturazione',
		'Add Shipping Address'            => 'Aggiungi indirizzo di spedizione',
		'Add billing address'             => 'Aggiungi indirizzo di fatturazione',
		'Add shipping address'            => 'Aggiungi indirizzo di spedizione',
		'Edit Billing Address'            => 'Modifica indirizzo di fatturazione',
		'Edit Shipping Address'           => 'Modifica indirizzo di spedizione',
		'Edit billing address'            => 'Modifica indirizzo di fatturazione',
		'Edit shipping address'           => 'Modifica indirizzo di spedizione',
		'Add %s'                          => 'Aggiungi %s',
		'Edit %s'                         => 'Modifica %s',
		'You have not set up this type of address yet.' => 'Non hai ancora impostato questo indirizzo.',
		'Addresses'                       => 'Indirizzi',
		'The following addresses will be used on the checkout page by default.' => 'I seguenti indirizzi verranno usati come predefiniti al pagamento.',
		'Save address'                    => 'Salva indirizzo',
		'Address changed successfully.'   => 'Indirizzo aggiornato con successo.',
		'Account details'                 => 'Dati account',
		'Dashboard'                       => 'Panoramica',
		'Orders'                          => 'Ordini',
		'Payment methods'                 => 'Metodi di pagamento',
		'Logout'                          => 'Esci',
		'Log out'                         => 'Esci',
		'My account'                      => 'Il mio account',
		'No order has been made yet.'     => 'Non hai ancora effettuato ordini.',
		'Browse products'                 => 'Sfoglia i prodotti',
		'Order'                           => 'Ordine',
		'Date'                            => 'Data',
		'Status'                          => 'Stato',
		'Actions'                         => 'Azioni',
		'View'                            => 'Visualizza',
		'Pay'                             => 'Paga',
		'Cancel'                          => 'Annulla',
		'First name'                      => 'Nome',
		'Last name'                       => 'Cognome',
		'Company name'                    => 'Ragione sociale',
		'Company name (optional)'         => 'Ragione sociale (opzionale)',
		'Country / Region'                => 'Paese / Regione',
		'Street address'                  => 'Indirizzo',
		'House number and street name'    => 'Via e numero civico',
		'Apartment, suite, unit, etc. (optional)' => 'Appartamento, scala, interno, ecc. (opzionale)',
		'Town / City'                     => 'Città',
		'State / County'                  => 'Provincia',
		'Postcode / ZIP'                  => 'CAP',
		'Phone'                           => 'Telefono',
		'Email address'                   => 'Indirizzo email',
		'Save changes'                    => 'Salva modifiche',
		'Display name'                    => 'Nome visualizzato',
		'Password change'                 => 'Cambio password',
		'Current password (leave blank to leave unchanged)' => 'Password attuale (lascia vuoto per non modificarla)',
		'New password (leave blank to leave unchanged)' => 'Nuova password (lascia vuoto per non modificarla)',
		'Confirm new password'            => 'Conferma nuova password',
		'Account details changed successfully.' => 'Dati account aggiornati con successo.',
		'Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)' => 'Ciao %1$s (non sei %1$s? <a href="%2$s">Esci</a>)',
		'Go shop'                         => 'Vai allo shop',
		'Default'                         => 'Predefinito',
		'Make default'                    => 'Imposta come predefinito',
		'Delete'                          => 'Elimina',
		'Add payment method'              => 'Aggiungi metodo di pagamento',
		'No saved methods found.'         => 'Nessun metodo di pagamento salvato.',
		'Username or email address'       => 'Email o nome utente',
		'Password'                        => 'Password',
		'Remember me'                     => 'Ricordami',
		'Lost your password?'             => 'Password dimenticata?',
		'Log in'                          => 'Accedi',
		'Register'                        => 'Registrati',
		'Username'                        => 'Nome utente',
		'A link to set a new password will be sent to your email address.' => 'Ti invieremo un link per impostare la password.',
		'Lost your password'              => 'Password dimenticata',
		'Reset password'                  => 'Reimposta password',
		'Login'                           => 'Accedi',
		'Email'                           => 'Email',
	);
}

/**
 * Forza stringhe WooCommerce in italiano.
 *
 * @param string $translation Translated.
 * @param string $text        Original.
 * @param string $domain      Domain.
 * @return string
 */
function fa_woocommerce_it_strings( $translation, $text, $domain ) {
	if ( 'it' !== fa_get_lang() ) {
		return $translation;
	}
	if ( 'woocommerce' !== $domain && 'formula-acqua' !== $domain ) {
		return $translation;
	}

	$map = fa_get_woocommerce_it_map();
	return isset( $map[ $text ] ) ? $map[ $text ] : $translation;
}
add_filter( 'gettext', 'fa_woocommerce_it_strings', 20, 3 );

/**
 * Traduzioni con contesto.
 *
 * @param string $translation Translated.
 * @param string $text        Original.
 * @param string $context     Context.
 * @param string $domain      Domain.
 * @return string
 */
function fa_woocommerce_it_strings_with_context( $translation, $text, $context, $domain ) {
	if ( 'woocommerce' !== $domain || 'it' !== fa_get_lang() ) {
		return $translation;
	}
	if ( 'full name' === $context && 'Name' === $text ) {
		return 'Nome';
	}
	$map = array(
		'Billing address'  => 'Indirizzo di fatturazione',
		'Shipping address' => 'Indirizzo di spedizione',
	);
	return isset( $map[ $text ] ) ? $map[ $text ] : $translation;
}
add_filter( 'gettext_with_context', 'fa_woocommerce_it_strings_with_context', 20, 4 );

/**
 * Titoli indirizzi account.
 *
 * @param array $addresses Addresses.
 * @return array
 */
function fa_account_address_titles( $addresses ) {
	if ( 'it' !== fa_get_lang() ) {
		return $addresses;
	}
	if ( isset( $addresses['billing'] ) ) {
		$addresses['billing'] = 'Indirizzo di fatturazione';
	}
	if ( isset( $addresses['shipping'] ) ) {
		$addresses['shipping'] = 'Indirizzo di spedizione';
	}
	return $addresses;
}
add_filter( 'woocommerce_my_account_get_addresses', 'fa_account_address_titles', 20 );
