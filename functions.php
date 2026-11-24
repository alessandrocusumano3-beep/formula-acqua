<?php
/**
 * Formula Acqua - Premium Theme Functions
 *
 * @package Formula_Acqua
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'FA_VERSION', '4.9.0' );
define( 'FA_DIR', get_template_directory() );
define( 'FA_URI', get_template_directory_uri() );

/**
 * Pulsanti flottanti di contatto (angolo in basso a destra del sito).
 * Imposta su "false" per nascondere il relativo pulsante senza toccare
 * altro codice. Non incide sul box "Assistenza" del footer.
 */
define( 'FA_SHOW_FLOATING_WHATSAPP', true );
define( 'FA_SHOW_FLOATING_EMAIL', true );
define( 'FA_SHOW_FLOATING_INSTAGRAM', true );

require_once FA_DIR . '/inc/i18n.php';
require_once FA_DIR . '/inc/wishlist.php';

function fa_setup() {
    load_theme_textdomain( 'formula-acqua', FA_DIR . '/languages' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 300, 'flex-height' => true, 'flex-width' => true ) );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'wp-block-styles' );

    add_image_size( 'fa-hero', 1920, 1080, true );
    add_image_size( 'fa-card', 600, 600, true );
    add_image_size( 'fa-product', 800, 800, true );
    add_image_size( 'fa-category', 640, 480, true );

    register_nav_menus( array(
        'primary'     => 'Menu Principale',
        'footer'      => 'Menu Footer',
        'footer-info' => 'Menu Informazioni',
    ) );
}
add_action( 'after_setup_theme', 'fa_setup' );

/**
 * Nome brand visibile: FormulAcqua. Non altera slug, opzioni o URL.
 *
 * @param string $value Nome sito salvato.
 * @return string
 */
function fa_filter_blogname_display( $value ) {
	if ( is_admin() && ! wp_doing_ajax() ) {
		return $value;
	}
	if ( 'Formula Acqua' === $value ) {
		return 'FormulAcqua';
	}
	return $value;
}
add_filter( 'option_blogname', 'fa_filter_blogname_display' );

function fa_widgets_init() {
    register_sidebar( array(
        'name'          => 'Sidebar Shop',
        'id'            => 'sidebar-shop',
        'before_widget' => '<div id="%1$s" class="fa-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="fa-widget__title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'fa_widgets_init' );

/**
 * Versione asset basata su filemtime: ogni modifica a main.css/main.js
 * cambia automaticamente la query string "?ver=", forzando i browser
 * (e eventuali cache/CDN) a scaricare il file aggiornato invece di
 * servire una copia in cache con la vecchia FA_VERSION statica.
 *
 * @param string $relative_path Percorso relativo alla root del tema (con slash iniziale).
 * @return string
 */
function fa_asset_version( $relative_path ) {
    $file = FA_DIR . $relative_path;
    return file_exists( $file ) ? (string) filemtime( $file ) : FA_VERSION;
}

function fa_scripts() {
    wp_enqueue_style(
        'fa-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap',
        array(),
        null
    );
    wp_enqueue_style( 'fa-main', FA_URI . '/assets/css/main.css', array( 'fa-fonts' ), fa_asset_version( '/assets/css/main.css' ) );
    wp_enqueue_style( 'fa-style', get_stylesheet_uri(), array( 'fa-main' ), FA_VERSION );

    wp_enqueue_script( 'fa-main', FA_URI . '/assets/js/main.js', array( 'jquery' ), fa_asset_version( '/assets/js/main.js' ), true );

    wp_localize_script( 'fa-main', 'formulaAcqua', array(
        'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
        'nonce'         => wp_create_nonce( 'fa_nonce' ),
        'shopUrl'       => class_exists( 'WooCommerce' ) ? esc_url( wc_get_page_permalink( 'shop' ) ) : '',
        'wishlistUrl'   => function_exists( 'fa_get_wishlist_url' ) ? esc_url( fa_get_wishlist_url() ) : '',
        'wishlistCount' => function_exists( 'fa_get_wishlist_count' ) ? (int) fa_get_wishlist_count() : 0,
    ) );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'fa_scripts' );

/**
 * Resource hints + font display optimization.
 */
function fa_resource_hints( $urls, $relation_type ) {
    if ( 'preconnect' === $relation_type ) {
        $urls[] = array(
            'href' => 'https://fonts.googleapis.com',
        );
        $urls[] = array(
            'href'        => 'https://fonts.gstatic.com',
            'crossorigin' => true,
        );
    }
    return $urls;
}
add_filter( 'wp_resource_hints', 'fa_resource_hints', 10, 2 );

/**
 * Defer theme script for faster first paint.
 *
 * @param string $tag    Script tag.
 * @param string $handle Handle.
 * @return string
 */
function fa_defer_scripts( $tag, $handle ) {
    if ( 'fa-main' === $handle && false === strpos( $tag, ' defer' ) ) {
        return str_replace( ' src', ' defer src', $tag );
    }
    return $tag;
}
add_filter( 'script_loader_tag', 'fa_defer_scripts', 10, 2 );

/**
 * URL pagina legale / informativa.
 *
 * @param string $slug Page slug.
 * @return string
 */
function fa_get_legal_page_url( $slug, $lang = null ) {
    $page = get_page_by_path( $slug );
    if ( $page instanceof WP_Post ) {
        $page_id = (int) $page->ID;
        if ( function_exists( 'pll_get_post' ) ) {
            $translated = $lang ? pll_get_post( $page_id, $lang ) : pll_get_post( $page_id );
            if ( $translated ) {
                $page_id = (int) $translated;
            }
        }
        return get_permalink( $page_id );
    }
    return home_url( '/' . trim( $slug, '/' ) . '/' );
}

/**
 * Preload hero poster (lighter than full video) for faster LCP.
 */
function fa_preload_hero_poster() {
	if ( ! is_front_page() ) {
		return;
	}

	$poster = FA_DIR . '/assets/images/hero-poster.jpg';
	if ( ! file_exists( $poster ) ) {
		return;
	}

	$rel = wp_parse_url( FA_URI . '/assets/images/hero-poster.jpg', PHP_URL_PATH );
	$url = $rel ? $rel : ( FA_URI . '/assets/images/hero-poster.jpg' );
	$url .= '?v=' . rawurlencode( (string) filemtime( $poster ) );
	echo '<link rel="preload" as="image" href="' . esc_url( $url ) . '" fetchpriority="high">' . "\n";
}
add_action( 'wp_head', 'fa_preload_hero_poster', 2 );

/* WooCommerce */
function fa_woocommerce_support() {
    add_theme_support( 'woocommerce', array(
        'thumbnail_image_width' => 600,
        'single_image_width'    => 800,
        'product_grid'          => array(
            'default_rows'    => 4,
            'min_rows'        => 2,
            'max_rows'        => 8,
            'default_columns' => 3,
            'min_columns'     => 2,
            'max_columns'     => 4,
        ),
    ) );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'fa_woocommerce_support' );

add_filter( 'loop_shop_per_page', function() { return 12; } );
add_filter( 'loop_shop_columns', function() { return 4; } );

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
add_action( 'woocommerce_before_main_content', function() { echo '<div class="fa-woo-wrap"><div class="fa-container">'; } );
add_action( 'woocommerce_after_main_content', function() { echo '</div></div>'; } );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

/**
 * Shop/categorie a tutta larghezza: niente sidebar WC o widget predefiniti.
 */
add_action( 'wp', function() {
    if ( function_exists( 'is_woocommerce' ) && ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) ) {
        remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
    }
} );

add_filter( 'is_active_sidebar', function( $is_active, $index ) {
    if ( 'sidebar-shop' === $index && function_exists( 'is_woocommerce' ) && ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) ) {
        return false;
    }
    return $is_active;
}, 10, 2 );

// Disattiva "Coming soon" WooCommerce se ancora attivo.
add_action( 'init', function() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    if ( 'yes' === get_option( 'woocommerce_coming_soon' ) ) {
        update_option( 'woocommerce_coming_soon', 'no' );
    }
    if ( 'yes' === get_option( 'woocommerce_store_pages_only' ) ) {
        update_option( 'woocommerce_store_pages_only', 'no' );
    }
}, 5 );

function fa_cart_count_fragment( $fragments ) {
    ob_start();
    $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    ?>
    <span class="fa-cart-count" data-count="<?php echo esc_attr( $count ); ?>"><?php echo esc_html( $count ); ?></span>
    <?php
    $fragments['.fa-cart-count'] = ob_get_clean();
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'fa_cart_count_fragment' );

function fa_sale_flash( $html, $post, $product ) {
    if ( $product->is_on_sale() && $product->get_regular_price() ) {
        $regular    = (float) $product->get_regular_price();
        $sale       = (float) $product->get_sale_price();
        $percentage = round( ( ( $regular - $sale ) / $regular ) * 100 );
        return '<span class="fa-badge-sale">-' . esc_html( $percentage ) . '%</span>';
    }
    return $html;
}
add_filter( 'woocommerce_sale_flash', 'fa_sale_flash', 10, 3 );

function fa_add_to_cart_text( $text, $product ) {
    if ( $product && $product->is_purchasable() && $product->is_in_stock() ) {
        return function_exists( 'fa_t' ) ? fa_t( 'Aggiungi al carrello' ) : 'Aggiungi al carrello';
    }
    return $text;
}
add_filter( 'woocommerce_product_add_to_cart_text', 'fa_add_to_cart_text', 10, 2 );
add_filter( 'woocommerce_product_single_add_to_cart_text', function() {
    return function_exists( 'fa_t' ) ? fa_t( 'Aggiungi al carrello' ) : 'Aggiungi al carrello';
} );

/**
 * Account e-commerce: abilita registrazione e personalizza menu.
 */
function fa_account_setup() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    if ( 'yes' !== get_option( 'woocommerce_enable_myaccount_registration' ) ) {
        update_option( 'woocommerce_enable_myaccount_registration', 'yes' );
    }
    // Password scelta dall'utente in fase di registrazione (flusso più chiaro).
    if ( 'no' !== get_option( 'woocommerce_registration_generate_password' ) ) {
        update_option( 'woocommerce_registration_generate_password', 'no' );
    }
}
add_action( 'init', 'fa_account_setup', 20 );

function fa_account_menu_items( $items ) {
    unset( $items['downloads'] );

    $labels = array(
        'dashboard'       => fa_t( 'Panoramica' ),
        'orders'          => fa_t( 'I miei ordini' ),
        'edit-address'    => fa_t( 'Indirizzi' ),
        'payment-methods' => fa_t( 'Pagamenti' ),
        'edit-account'    => fa_t( 'Dati personali' ),
        'customer-logout' => fa_t( 'Esci' ),
    );
    foreach ( $labels as $key => $label ) {
        if ( isset( $items[ $key ] ) ) {
            $items[ $key ] = $label;
        }
    }
    return $items;
}
add_filter( 'woocommerce_account_menu_items', 'fa_account_menu_items', 20 );

function fa_account_body_class( $classes ) {
    if ( function_exists( 'is_account_page' ) && is_account_page() ) {
        $classes[] = 'fa-account-page';
        if ( ! is_user_logged_in() ) {
            $classes[] = 'fa-account-guest';
        } else {
            $classes[] = 'fa-account-user';
        }
    }
    if ( function_exists( 'is_cart' ) && is_cart() ) {
        $classes[] = 'fa-cart-page';
    }
    if ( function_exists( 'is_checkout' ) && is_checkout() ) {
        $classes[] = 'fa-checkout-page';
    }
    return $classes;
}
add_filter( 'body_class', 'fa_account_body_class' );

/**
 * Iniziale utente per avatar header/account.
 *
 * @param WP_User|null $user User.
 * @return string
 */
function fa_get_user_initial( $user = null ) {
    if ( ! $user ) {
        $user = wp_get_current_user();
    }
    if ( ! $user || ! $user->ID ) {
        return 'U';
    }
    $name = trim( (string) $user->first_name );
    if ( '' === $name ) {
        $name = trim( (string) $user->display_name );
    }
    if ( '' === $name ) {
        $name = (string) $user->user_login;
    }
    $char = function_exists( 'mb_substr' ) ? mb_substr( $name, 0, 1, 'UTF-8' ) : substr( $name, 0, 1 );
    return strtoupper( $char ? $char : 'U' );
}

/**
 * Prezzo loop premium: azzurro, sconto % e prezzo finale evidenziato.
 *
 * @param WC_Product $product Product.
 * @return string
 */
function fa_get_loop_price_html( $product ) {
    if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
        return '';
    }

    if ( $product->is_on_sale() ) {
        $regular = (float) $product->get_regular_price();
        $sale    = (float) $product->get_sale_price();

        if ( ( $regular <= 0 || $sale <= 0 ) && $product->is_type( 'variable' ) ) {
            $regular = (float) $product->get_variation_regular_price( 'min' );
            $sale    = (float) $product->get_variation_sale_price( 'min' );
        }

        if ( $regular > 0 && $sale > 0 && $sale < $regular ) {
            $pct = (int) round( ( ( $regular - $sale ) / $regular ) * 100 );
            if ( $pct > 0 ) {
                return '<div class="fa-product-card__price fa-product-card__price--sale">'
                    . '<div class="fa-price-row">'
                    . '<del aria-hidden="true">' . wc_price( $regular ) . '</del>'
                    . '<span class="fa-price-discount">-' . esc_html( (string) $pct ) . '%</span>'
                    . '</div>'
                    . '<span class="fa-price-final">' . wc_price( $sale ) . '</span>'
                    . '</div>';
            }
        }
    }

    return '<div class="fa-product-card__price">'
        . '<span class="fa-price-final">' . $product->get_price_html() . '</span>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        . '</div>';
}

/**
 * Badge prodotto: Offerta, Novità, Più venduto.
 *
 * @param WC_Product $product Product.
 * @return string
 */
function fa_get_product_badges_html( $product ) {
    if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
        return '';
    }

    $badges = array();

    if ( $product->is_on_sale() ) {
        $badges[] = array( 'class' => 'fa-badge--sale', 'label' => fa_t( 'Offerta' ) );
    }

    $created = $product->get_date_created();
    if ( $created && $created->getTimestamp() > ( time() - 30 * DAY_IN_SECONDS ) ) {
        $badges[] = array( 'class' => 'fa-badge--new', 'label' => fa_t( 'Novità' ) );
    }

    $sales = (int) $product->get_total_sales();
    if ( $sales >= 5 || $product->is_featured() ) {
        $badges[] = array( 'class' => 'fa-badge--best', 'label' => fa_t( 'Più venduto' ) );
    }

    if ( empty( $badges ) ) {
        return '';
    }

    // Max 2 badge per non sovraccaricare.
    $badges = array_slice( $badges, 0, 2 );

    ob_start();
    echo '<div class="fa-product-badges">';
    foreach ( $badges as $badge ) {
        echo '<span class="fa-badge ' . esc_attr( $badge['class'] ) . '">' . esc_html( $badge['label'] ) . '</span>';
    }
    echo '</div>';
    return ob_get_clean();
}

/**
 * Lingua da usare per generare un contenuto legale: quella passata
 * esplicitamente, altrimenti la lingua attiva corrente (Polylang).
 *
 * @param string|null $lang Codice lingua opzionale.
 * @return string
 */
function fa_legal_lang( $lang = null ) {
    $allowed = fa_get_lang_codes();
    if ( $lang && in_array( $lang, $allowed, true ) ) {
        return $lang;
    }
    return fa_get_lang();
}

/**
 * Stringa nella lingua richiesta, con fallback all'italiano se manca.
 *
 * @param array<string,string> $map  Mappa lingua => testo.
 * @param string               $lang Lingua richiesta.
 * @return string
 */
function fa_legal_pick( $map, $lang ) {
    if ( isset( $map[ $lang ] ) ) {
        return $map[ $lang ];
    }
    return isset( $map['it'] ) ? $map['it'] : reset( $map );
}

/**
 * Contenuto "Termini e Condizioni di Vendita", nella lingua richiesta.
 * Usa solo dati reali confermati (Formula Acqua, titolare, PEC, telefono/WhatsApp).
 * Non contiene Partita IVA, sede legale, corrieri, tempi di spedizione o metodi
 * di pagamento non ancora confermati: queste informazioni sono predisposte per
 * essere aggiunte in automatico non appena disponibili (vedi fa_get_legal_address()
 * e fa_get_piva()), in tutte le lingue.
 *
 * @param string|null $lang Codice lingua (it|en|fr|de|es). Default: lingua attiva.
 * @return string
 */
function fa_get_terms_content( $lang = null ) {
    $lang     = fa_legal_lang( $lang );
    $owner    = fa_get_owner_name();
    $email    = get_theme_mod( 'fa_email', 'formulacqua@ionospec.it' );
    $phone    = get_theme_mod( 'fa_phone', '+39 327 916 6457' );
    $address  = fa_get_legal_address();
    $piva     = fa_get_piva();
    $refund_url = fa_get_refund_policy_url( $lang );

    $t = array(
        'seller_h'    => fa_legal_pick( array( 'it' => '1. Informazioni sul venditore', 'en' => '1. Seller information', 'fr' => '1. Informations sur le vendeur', 'de' => '1. Angaben zum Verkäufer', 'es' => '1. Información del vendedor' ), $lang ),
        'seller_p'    => fa_legal_pick( array(
            'it' => 'Il presente sito e-commerce è gestito da <strong>Formula Acqua</strong>, a titolarità di <strong>%s</strong>.',
            'en' => 'This online store is operated by <strong>Formula Acqua</strong>, owned by <strong>%s</strong>.',
            'fr' => 'Ce site e-commerce est géré par <strong>Formula Acqua</strong>, dont le titulaire est <strong>%s</strong>.',
            'de' => 'Dieser Online-Shop wird von <strong>Formula Acqua</strong> betrieben, Inhaber <strong>%s</strong>.',
            'es' => 'Esta tienda online está gestionada por <strong>Formula Acqua</strong>, cuyo titular es <strong>%s</strong>.',
        ), $lang ),
        'pec_label'   => fa_legal_pick( array( 'it' => 'PEC', 'en' => 'Certified email (PEC)', 'fr' => 'PEC (e-mail certifié)', 'de' => 'PEC (zertifizierte E-Mail)', 'es' => 'PEC (correo certificado)' ), $lang ),
        'phone_label' => fa_legal_pick( array( 'it' => 'Telefono / WhatsApp', 'en' => 'Phone / WhatsApp', 'fr' => 'Téléphone / WhatsApp', 'de' => 'Telefon / WhatsApp', 'es' => 'Teléfono / WhatsApp' ), $lang ),
        'sede_label'  => fa_legal_pick( array( 'it' => 'Sede legale', 'en' => 'Registered office', 'fr' => 'Siège social', 'de' => 'Firmensitz', 'es' => 'Domicilio social' ), $lang ),
        'piva_label'  => fa_legal_pick( array( 'it' => 'Partita IVA', 'en' => 'VAT number', 'fr' => 'Numéro de TVA', 'de' => 'USt-IdNr.', 'es' => 'NIF/IVA' ), $lang ),
        'pending'     => fa_legal_pick( array(
            'it' => 'Partita IVA e sede legale sono in fase di completamento da parte del titolare e saranno indicate in questa pagina non appena disponibili.',
            'en' => 'The VAT number and registered office are being finalised by the owner and will be added to this page as soon as they are available.',
            'fr' => "Le numéro de TVA et le siège social sont en cours de finalisation par le titulaire et seront indiqués sur cette page dès qu'ils seront disponibles.",
            'de' => 'USt-IdNr. und Firmensitz werden vom Inhaber derzeit finalisiert und auf dieser Seite ergänzt, sobald sie verfügbar sind.',
            'es' => 'El NIF/IVA y el domicilio social están siendo completados por el titular y se indicarán en esta página en cuanto estén disponibles.',
        ), $lang ),
        'products_h'  => fa_legal_pick( array( 'it' => '2. Prodotti', 'en' => '2. Products', 'fr' => '2. Produits', 'de' => '2. Produkte', 'es' => '2. Productos' ), $lang ),
        'products_p'  => fa_legal_pick( array(
            'it' => 'Formula Acqua propone la vendita di prodotti e accessori per piscine, tra cui robot pulitori, prodotti chimici, pompe, filtri, accessori e ricambi. Le immagini e le descrizioni presenti sul sito hanno finalità illustrativa: eventuali minime differenze estetiche non pregiudicano le caratteristiche essenziali del prodotto acquistato.',
            'en' => 'Formula Acqua sells pool products and accessories, including cleaning robots, chemicals, pumps, filters, accessories and spare parts. Images and descriptions on the site are for illustrative purposes: minor aesthetic differences do not affect the essential characteristics of the purchased product.',
            'fr' => 'Formula Acqua propose la vente de produits et accessoires pour piscines, notamment des robots nettoyeurs, des produits chimiques, des pompes, des filtres, des accessoires et des pièces détachées. Les images et descriptions présentes sur le site ont une finalité illustrative : d\'éventuelles différences esthétiques mineures ne remettent pas en cause les caractéristiques essentielles du produit acheté.',
            'de' => 'Formula Acqua verkauft Poolprodukte und Zubehör, darunter Reinigungsroboter, Chemikalien, Pumpen, Filter, Zubehör und Ersatzteile. Bilder und Beschreibungen auf der Website dienen nur zur Veranschaulichung: geringe optische Abweichungen beeinträchtigen nicht die wesentlichen Eigenschaften des gekauften Produkts.',
            'es' => 'Formula Acqua ofrece la venta de productos y accesorios para piscinas, entre ellos robots limpiafondos, productos químicos, bombas, filtros, accesorios y repuestos. Las imágenes y descripciones del sitio tienen una finalidad ilustrativa: pequeñas diferencias estéticas no afectan a las características esenciales del producto adquirido.',
        ), $lang ),
        'prices_h'    => fa_legal_pick( array( 'it' => '3. Prezzi', 'en' => '3. Prices', 'fr' => '3. Prix', 'de' => '3. Preise', 'es' => '3. Precios' ), $lang ),
        'prices_p'    => fa_legal_pick( array(
            'it' => "Tutti i prezzi indicati sul sito sono espressi in Euro e comprensivi di IVA, salvo diversa indicazione. Formula Acqua si riserva il diritto di modificare i prezzi in qualsiasi momento; al cliente verrà comunque applicato il prezzo indicato al momento dell'invio dell'ordine.",
            'en' => 'All prices shown on the site are in Euro and include VAT, unless stated otherwise. Formula Acqua reserves the right to change prices at any time; the price applied to the customer will always be the one shown at the time the order is placed.',
            'fr' => "Tous les prix indiqués sur le site sont exprimés en euros et incluent la TVA, sauf indication contraire. Formula Acqua se réserve le droit de modifier les prix à tout moment ; le prix appliqué au client sera toujours celui indiqué au moment de l'envoi de la commande.",
            'de' => 'Alle auf der Website angegebenen Preise sind in Euro und beinhalten die MwSt., sofern nicht anders angegeben. Formula Acqua behält sich das Recht vor, die Preise jederzeit zu ändern; dem Kunden wird stets der zum Zeitpunkt der Bestellung angezeigte Preis berechnet.',
            'es' => 'Todos los precios indicados en el sitio están expresados en euros e incluyen el IVA, salvo que se indique lo contrario. Formula Acqua se reserva el derecho de modificar los precios en cualquier momento; al cliente se le aplicará siempre el precio indicado en el momento de enviar el pedido.',
        ), $lang ),
        'orders_h'    => fa_legal_pick( array( 'it' => '4. Ordini', 'en' => '4. Orders', 'fr' => '4. Commandes', 'de' => '4. Bestellungen', 'es' => '4. Pedidos' ), $lang ),
        'orders_p'    => fa_legal_pick( array(
            'it' => "L'ordine si considera concluso quando il cliente riceve, via email, la conferma d'ordine da parte di Formula Acqua. Formula Acqua si riserva il diritto di non accettare un ordine, ad esempio in caso di indisponibilità del prodotto o di anomalie riscontrate nella richiesta, informando prontamente il cliente.",
            'en' => "An order is considered complete once the customer receives an order confirmation email from Formula Acqua. Formula Acqua reserves the right not to accept an order, for example if the product is unavailable or an anomaly is detected in the request, promptly informing the customer.",
            'fr' => "La commande est considérée comme conclue lorsque le client reçoit, par e-mail, la confirmation de commande de Formula Acqua. Formula Acqua se réserve le droit de ne pas accepter une commande, par exemple en cas d'indisponibilité du produit ou d'anomalie constatée dans la demande, en informant rapidement le client.",
            'de' => 'Die Bestellung gilt als abgeschlossen, wenn der Kunde per E-Mail die Bestellbestätigung von Formula Acqua erhält. Formula Acqua behält sich das Recht vor, eine Bestellung nicht anzunehmen, etwa bei Nichtverfügbarkeit des Produkts oder Unstimmigkeiten in der Anfrage, und informiert den Kunden umgehend darüber.',
            'es' => 'El pedido se considera concluido cuando el cliente recibe, por correo electrónico, la confirmación del pedido por parte de Formula Acqua. Formula Acqua se reserva el derecho de no aceptar un pedido, por ejemplo en caso de indisponibilidad del producto o de anomalías detectadas en la solicitud, informando con prontitud al cliente.',
        ), $lang ),
        'payments_h'  => fa_legal_pick( array( 'it' => '5. Pagamenti', 'en' => '5. Payments', 'fr' => '5. Paiements', 'de' => '5. Zahlungen', 'es' => '5. Pagos' ), $lang ),
        'payments_p'  => fa_legal_pick( array(
            'it' => 'I pagamenti vengono effettuati tramite i metodi di pagamento resi disponibili al momento del checkout sul sito. Il sito non conserva i dati completi degli strumenti di pagamento utilizzati, gestiti direttamente dai fornitori di servizi di pagamento incaricati.',
            'en' => 'Payments are made using the payment methods available at checkout on the site. The site does not store the full details of the payment instruments used, which are managed directly by the appointed payment service providers.',
            'fr' => "Les paiements sont effectués via les méthodes de paiement proposées au moment du passage en caisse sur le site. Le site ne conserve pas les données complètes des moyens de paiement utilisés, gérées directement par les prestataires de services de paiement.",
            'de' => 'Zahlungen erfolgen über die beim Checkout auf der Website verfügbaren Zahlungsmethoden. Die Website speichert keine vollständigen Daten der verwendeten Zahlungsmittel; diese werden direkt von den beauftragten Zahlungsdienstleistern verwaltet.',
            'es' => 'Los pagos se realizan a través de los métodos de pago disponibles en el momento de finalizar la compra en el sitio. El sitio no conserva los datos completos de los instrumentos de pago utilizados, gestionados directamente por los proveedores de servicios de pago encargados.',
        ), $lang ),
        'shipping_h'  => fa_legal_pick( array( 'it' => '6. Spedizioni e consegne', 'en' => '6. Shipping and delivery', 'fr' => '6. Livraisons', 'de' => '6. Versand und Lieferung', 'es' => '6. Envíos y entregas' ), $lang ),
        'shipping_p'  => fa_legal_pick( array(
            'it' => "I prodotti vengono spediti all'indirizzo indicato dal cliente in fase di ordine. Eventuali costi e tempistiche di spedizione, quando applicabili, sono indicati nel corso della procedura di acquisto prima della conferma dell'ordine. Formula Acqua non è responsabile per ritardi nella consegna dovuti a cause non direttamente imputabili alla propria organizzazione.",
            'en' => "Products are shipped to the address provided by the customer when placing the order. Any shipping costs and timeframes, where applicable, are shown during the purchase process before the order is confirmed. Formula Acqua is not liable for delivery delays caused by circumstances not directly attributable to it.",
            'fr' => "Les produits sont expédiés à l'adresse indiquée par le client lors de la commande. D'éventuels frais et délais de livraison, lorsqu'ils s'appliquent, sont indiqués au cours du processus d'achat avant la confirmation de la commande. Formula Acqua n'est pas responsable des retards de livraison dus à des causes qui ne lui sont pas directement imputables.",
            'de' => 'Die Produkte werden an die vom Kunden bei der Bestellung angegebene Adresse versandt. Etwaige Versandkosten und -zeiten werden, sofern zutreffend, während des Kaufvorgangs vor der Bestellbestätigung angezeigt. Formula Acqua haftet nicht für Lieferverzögerungen, die nicht direkt auf das Unternehmen zurückzuführen sind.',
            'es' => 'Los productos se envían a la dirección indicada por el cliente al realizar el pedido. Los posibles costes y plazos de envío, cuando sean aplicables, se indican durante el proceso de compra antes de confirmar el pedido. Formula Acqua no es responsable de los retrasos en la entrega debidos a causas que no le sean directamente imputables.',
        ), $lang ),
        'withdrawal_h' => fa_legal_pick( array( 'it' => '7. Diritto di recesso', 'en' => '7. Right of withdrawal', 'fr' => '7. Droit de rétractation', 'de' => '7. Widerrufsrecht', 'es' => '7. Derecho de desistimiento' ), $lang ),
        'withdrawal_p1' => fa_legal_pick( array(
            'it' => 'Il cliente che agisce in qualità di consumatore ha diritto di recedere dal contratto, senza dover fornire alcuna motivazione, entro <strong>14 giorni</strong> dalla data di consegna del prodotto, comunicando la propria volontà di recesso a Formula Acqua tramite i contatti indicati in questa pagina.',
            'en' => 'A customer acting as a consumer has the right to withdraw from the contract, without giving any reason, within <strong>14 days</strong> of the product delivery date, by notifying Formula Acqua of the intention to withdraw using the contact details on this page.',
            'fr' => 'Le client agissant en qualité de consommateur a le droit de se rétracter du contrat, sans avoir à fournir de motif, dans un délai de <strong>14 jours</strong> à compter de la date de livraison du produit, en communiquant sa volonté de rétractation à Formula Acqua via les coordonnées indiquées sur cette page.',
            'de' => 'Der Kunde, der als Verbraucher handelt, hat das Recht, innerhalb von <strong>14 Tagen</strong> nach Erhalt der Ware ohne Angabe von Gründen vom Vertrag zurückzutreten, indem er Formula Acqua über die auf dieser Seite angegebenen Kontaktdaten über den Widerruf informiert.',
            'es' => 'El cliente que actúe como consumidor tiene derecho a desistir del contrato, sin necesidad de indicar motivo alguno, en un plazo de <strong>14 días</strong> desde la fecha de entrega del producto, comunicando su voluntad de desistimiento a Formula Acqua a través de los contactos indicados en esta página.',
        ), $lang ),
        'withdrawal_p2' => fa_legal_pick( array(
            'it' => 'La normativa applicabile prevede alcuni casi specifici in cui il diritto di recesso può essere escluso o limitato. Per i dettagli operativi si rinvia alla pagina %s.',
            'en' => 'Applicable law provides for certain specific cases in which the right of withdrawal may be excluded or limited. For operational details, please refer to the %s page.',
            'fr' => 'La réglementation applicable prévoit certains cas spécifiques dans lesquels le droit de rétractation peut être exclu ou limité. Pour les détails pratiques, consultez la page %s.',
            'de' => 'Die geltenden Rechtsvorschriften sehen bestimmte Fälle vor, in denen das Widerrufsrecht ausgeschlossen oder eingeschränkt sein kann. Für die praktischen Details verweisen wir auf die Seite %s.',
            'es' => 'La normativa aplicable prevé algunos casos específicos en los que el derecho de desistimiento puede quedar excluido o limitado. Para más detalles operativos, consulta la página %s.',
        ), $lang ),
        'refund_link_label' => fa_legal_pick( array( 'it' => 'Resi e Rimborsi', 'en' => 'Returns and Refunds', 'fr' => 'Retours et remboursements', 'de' => 'Rückgabe und Erstattung', 'es' => 'Devoluciones y reembolsos' ), $lang ),
        'refunds_h'   => fa_legal_pick( array( 'it' => '8. Resi e rimborsi', 'en' => '8. Returns and refunds', 'fr' => '8. Retours et remboursements', 'de' => '8. Rückgabe und Erstattung', 'es' => '8. Devoluciones y reembolsos' ), $lang ),
        'refunds_p'   => fa_legal_pick( array(
            'it' => 'Le condizioni relative a resi e rimborsi sono descritte integralmente nella pagina dedicata %s, che costituisce parte integrante dei presenti Termini e Condizioni.',
            'en' => 'The full terms relating to returns and refunds are described on the dedicated %s page, which forms an integral part of these Terms and Conditions.',
            'fr' => 'Les conditions relatives aux retours et remboursements sont décrites intégralement sur la page dédiée %s, qui fait partie intégrante des présentes Conditions générales.',
            'de' => 'Die Bedingungen für Rückgabe und Erstattung sind vollständig auf der entsprechenden Seite %s beschrieben, die integraler Bestandteil dieser Allgemeinen Geschäftsbedingungen ist.',
            'es' => 'Las condiciones relativas a devoluciones y reembolsos se describen íntegramente en la página dedicada %s, que forma parte integrante de los presentes Términos y Condiciones.',
        ), $lang ),
        'defects_h'   => fa_legal_pick( array( 'it' => '9. Prodotti difettosi e garanzia legale', 'en' => '9. Defective products and legal warranty', 'fr' => '9. Produits défectueux et garantie légale', 'de' => '9. Mangelhafte Produkte und gesetzliche Gewährleistung', 'es' => '9. Productos defectuosos y garantía legal' ), $lang ),
        'defects_p'   => fa_legal_pick( array(
            'it' => 'Tutti i prodotti venduti sono coperti dalla garanzia legale di conformità prevista dal Codice del Consumo per i beni che risultano difettosi o non conformi al momento della consegna. In tali casi il cliente può contattare Formula Acqua per richiedere la riparazione, la sostituzione, la riduzione del prezzo o la risoluzione del contratto, secondo quanto previsto dalla normativa applicabile.',
            'en' => 'All products sold are covered by the legal warranty of conformity provided by applicable consumer protection law for goods that are defective or non-conforming at the time of delivery. In such cases, the customer may contact Formula Acqua to request repair, replacement, a price reduction or termination of the contract, in accordance with applicable law.',
            'fr' => 'Tous les produits vendus sont couverts par la garantie légale de conformité prévue par le Code de la consommation pour les biens qui s\'avèrent défectueux ou non conformes au moment de la livraison. Dans ce cas, le client peut contacter Formula Acqua pour demander la réparation, le remplacement, une réduction de prix ou la résolution du contrat, conformément à la réglementation applicable.',
            'de' => 'Alle verkauften Produkte unterliegen der gesetzlichen Gewährleistung für Waren, die zum Zeitpunkt der Lieferung mangelhaft oder nicht vertragsgemäß sind. In diesem Fall kann der Kunde Formula Acqua kontaktieren, um Reparatur, Ersatz, Preisminderung oder Vertragsauflösung gemäß den geltenden Rechtsvorschriften zu verlangen.',
            'es' => 'Todos los productos vendidos están cubiertos por la garantía legal de conformidad prevista para los bienes que resulten defectuosos o no conformes en el momento de la entrega. En tales casos, el cliente puede contactar con Formula Acqua para solicitar la reparación, la sustitución, la reducción del precio o la resolución del contrato, conforme a la normativa aplicable.',
        ), $lang ),
        'liability_h' => fa_legal_pick( array( 'it' => '10. Responsabilità', 'en' => '10. Liability', 'fr' => '10. Responsabilité', 'de' => '10. Haftung', 'es' => '10. Responsabilidad' ), $lang ),
        'liability_p' => fa_legal_pick( array(
            'it' => "Formula Acqua si impegna a fornire informazioni corrette sui prodotti offerti, ma non può essere ritenuta responsabile per un uso improprio dei prodotti acquistati o per danni derivanti dalla mancata osservanza delle istruzioni d'uso fornite dal produttore. Nei limiti consentiti dalla normativa applicabile, Formula Acqua non è responsabile per interruzioni del servizio dovute a cause di forza maggiore o a fattori esterni non controllabili.",
            'en' => "Formula Acqua undertakes to provide accurate information about the products offered, but cannot be held liable for improper use of purchased products or for damages resulting from failure to follow the manufacturer's instructions. To the extent permitted by applicable law, Formula Acqua is not liable for service interruptions caused by force majeure or uncontrollable external factors.",
            'fr' => "Formula Acqua s'engage à fournir des informations correctes sur les produits proposés, mais ne peut être tenue responsable d'une utilisation inappropriée des produits achetés ou de dommages résultant du non-respect des instructions d'utilisation fournies par le fabricant. Dans les limites permises par la réglementation applicable, Formula Acqua n'est pas responsable des interruptions de service dues à des cas de force majeure ou à des facteurs externes incontrôlables.",
            'de' => 'Formula Acqua verpflichtet sich, korrekte Informationen zu den angebotenen Produkten bereitzustellen, haftet jedoch nicht für unsachgemäßen Gebrauch der erworbenen Produkte oder für Schäden, die durch die Nichtbeachtung der Herstelleranweisungen entstehen. Im gesetzlich zulässigen Rahmen haftet Formula Acqua nicht für Dienstunterbrechungen aufgrund höherer Gewalt oder nicht kontrollierbarer externer Faktoren.',
            'es' => 'Formula Acqua se compromete a proporcionar información correcta sobre los productos ofrecidos, pero no puede ser considerada responsable de un uso indebido de los productos adquiridos ni de daños derivados del incumplimiento de las instrucciones de uso proporcionadas por el fabricante. Dentro de los límites permitidos por la normativa aplicable, Formula Acqua no es responsable de interrupciones del servicio debidas a causas de fuerza mayor o a factores externos no controlables.',
        ), $lang ),
        'contact_h'   => fa_legal_pick( array( 'it' => '11. Contatti', 'en' => '11. Contact', 'fr' => '11. Contact', 'de' => '11. Kontakt', 'es' => '11. Contacto' ), $lang ),
        'contact_p'   => fa_legal_pick( array(
            'it' => 'Per qualsiasi domanda relativa ai presenti Termini e Condizioni, al proprio ordine o ai prodotti acquistati, è possibile contattare Formula Acqua:',
            'en' => 'For any question about these Terms and Conditions, your order or the products purchased, you can contact Formula Acqua:',
            'fr' => 'Pour toute question relative aux présentes Conditions générales, à votre commande ou aux produits achetés, vous pouvez contacter Formula Acqua :',
            'de' => 'Bei Fragen zu diesen Allgemeinen Geschäftsbedingungen, Ihrer Bestellung oder den gekauften Produkten können Sie Formula Acqua kontaktieren:',
            'es' => 'Para cualquier duda relacionada con estos Términos y Condiciones, con tu pedido o con los productos adquiridos, puedes contactar con Formula Acqua:',
        ), $lang ),
    );

    $seller_extra = '';
    if ( $address ) {
        $seller_extra .= '<li>' . esc_html( $t['sede_label'] ) . ': ' . esc_html( $address ) . '</li>';
    }
    if ( $piva ) {
        $seller_extra .= '<li>' . esc_html( $t['piva_label'] ) . ': ' . esc_html( $piva ) . '</li>';
    }
    $pending_note = '';
    if ( ! $address || ! $piva ) {
        $pending_note = '<blockquote><p>' . esc_html( $t['pending'] ) . '</p></blockquote>';
    }

    $refund_link = '<a href="' . esc_url( $refund_url ) . '">' . esc_html( $t['refund_link_label'] ) . '</a>';

    ob_start();
    ?>
    <h2><?php echo esc_html( $t['seller_h'] ); ?></h2>
    <p><?php echo sprintf( $t['seller_p'], esc_html( $owner ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
    <ul>
        <li><?php echo esc_html( $t['pec_label'] ); ?>: <?php echo esc_html( $email ); ?></li>
        <li><?php echo esc_html( $t['phone_label'] ); ?>: <?php echo esc_html( $phone ); ?></li>
        <?php echo $seller_extra; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </ul>
    <?php echo $pending_note; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

    <h2><?php echo esc_html( $t['products_h'] ); ?></h2>
    <p><?php echo esc_html( $t['products_p'] ); ?></p>

    <h2><?php echo esc_html( $t['prices_h'] ); ?></h2>
    <p><?php echo esc_html( $t['prices_p'] ); ?></p>

    <h2><?php echo esc_html( $t['orders_h'] ); ?></h2>
    <p><?php echo esc_html( $t['orders_p'] ); ?></p>

    <h2><?php echo esc_html( $t['payments_h'] ); ?></h2>
    <p><?php echo esc_html( $t['payments_p'] ); ?></p>

    <h2><?php echo esc_html( $t['shipping_h'] ); ?></h2>
    <p><?php echo esc_html( $t['shipping_p'] ); ?></p>

    <h2><?php echo esc_html( $t['withdrawal_h'] ); ?></h2>
    <p><?php echo $t['withdrawal_p1']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
    <p><?php echo sprintf( $t['withdrawal_p2'], $refund_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

    <h2><?php echo esc_html( $t['refunds_h'] ); ?></h2>
    <p><?php echo sprintf( $t['refunds_p'], $refund_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

    <h2><?php echo esc_html( $t['defects_h'] ); ?></h2>
    <p><?php echo esc_html( $t['defects_p'] ); ?></p>

    <h2><?php echo esc_html( $t['liability_h'] ); ?></h2>
    <p><?php echo esc_html( $t['liability_p'] ); ?></p>

    <h2><?php echo esc_html( $t['contact_h'] ); ?></h2>
    <p><?php echo esc_html( $t['contact_p'] ); ?></p>
    <ul>
        <li><?php echo esc_html( $t['pec_label'] ); ?>: <?php echo esc_html( $email ); ?></li>
        <li><?php echo esc_html( $t['phone_label'] ); ?>: <?php echo esc_html( $phone ); ?></li>
    </ul>
    <?php
    return trim( (string) ob_get_clean() );
}

/**
 * Contenuto "Resi e Rimborsi", basato esclusivamente sulle condizioni
 * comunicate dal titolare. Nessuna condizione commerciale aggiuntiva inventata.
 *
 * @param string|null $lang Codice lingua (it|en|fr|de|es). Default: lingua attiva.
 * @return string
 */
function fa_get_refund_policy_content( $lang = null ) {
    $lang  = fa_legal_lang( $lang );
    $phone = get_theme_mod( 'fa_phone', '+39 327 916 6457' );
    $email = get_theme_mod( 'fa_email', 'formulacqua@ionospec.it' );

    $t = array(
        'h1' => fa_legal_pick( array( 'it' => 'Diritto di recesso', 'en' => 'Right of withdrawal', 'fr' => 'Droit de rétractation', 'de' => 'Widerrufsrecht', 'es' => 'Derecho de desistimiento' ), $lang ),
        'p1' => fa_legal_pick( array(
            'it' => 'Il cliente dispone di <strong>14 giorni</strong> dalla consegna del prodotto per comunicare a Formula Acqua la propria volontà di esercitare il diritto di recesso, senza necessità di fornire alcuna motivazione.',
            'en' => 'The customer has <strong>14 days</strong> from the delivery of the product to notify Formula Acqua of the intention to exercise the right of withdrawal, without needing to give any reason.',
            'fr' => 'Le client dispose de <strong>14 jours</strong> à compter de la livraison du produit pour communiquer à Formula Acqua sa volonté d\'exercer le droit de rétractation, sans devoir fournir de motif.',
            'de' => 'Der Kunde hat <strong>14 Tage</strong> nach Erhalt des Produkts Zeit, um Formula Acqua über die Absicht zu informieren, das Widerrufsrecht auszuüben, ohne einen Grund angeben zu müssen.',
            'es' => 'El cliente dispone de <strong>14 días</strong> desde la entrega del producto para comunicar a Formula Acqua su voluntad de ejercer el derecho de desistimiento, sin necesidad de indicar motivo alguno.',
        ), $lang ),
        'p2' => fa_legal_pick( array(
            'it' => "Per comunicare il recesso è possibile contattare Formula Acqua su WhatsApp al numero %s oppure scrivendo alla PEC %s, indicando il numero d'ordine e i prodotti che si desidera restituire.",
            'en' => 'To notify a withdrawal, you can contact Formula Acqua on WhatsApp at %s or by writing to the certified email (PEC) %s, stating the order number and the products you wish to return.',
            'fr' => 'Pour communiquer la rétractation, il est possible de contacter Formula Acqua sur WhatsApp au %s ou par e-mail certifié (PEC) à %s, en indiquant le numéro de commande et les produits que vous souhaitez retourner.',
            'de' => 'Um den Widerruf mitzuteilen, können Sie Formula Acqua per WhatsApp unter %s oder per zertifizierter E-Mail (PEC) an %s kontaktieren und dabei die Bestellnummer sowie die zurückzugebenden Produkte angeben.',
            'es' => 'Para comunicar el desistimiento, puedes contactar con Formula Acqua por WhatsApp al %s o escribiendo al correo certificado (PEC) %s, indicando el número de pedido y los productos que deseas devolver.',
        ), $lang ),
        'h2' => fa_legal_pick( array( 'it' => 'Tempi per la restituzione', 'en' => 'Time frame for returning the product', 'fr' => 'Délais de retour', 'de' => 'Fristen für die Rückgabe', 'es' => 'Plazos para la devolución' ), $lang ),
        'p3' => fa_legal_pick( array(
            'it' => 'Dopo aver comunicato la volontà di recesso, il cliente dispone normalmente di ulteriori <strong>14 giorni</strong> per restituire il prodotto a Formula Acqua.',
            'en' => 'After notifying the intention to withdraw, the customer normally has a further <strong>14 days</strong> to return the product to Formula Acqua.',
            'fr' => 'Après avoir communiqué sa volonté de rétractation, le client dispose normalement de <strong>14 jours</strong> supplémentaires pour retourner le produit à Formula Acqua.',
            'de' => 'Nach der Mitteilung des Widerrufs hat der Kunde in der Regel weitere <strong>14 Tage</strong> Zeit, um das Produkt an Formula Acqua zurückzusenden.',
            'es' => 'Tras comunicar la voluntad de desistimiento, el cliente dispone normalmente de <strong>14 días</strong> adicionales para devolver el producto a Formula Acqua.',
        ), $lang ),
        'h3' => fa_legal_pick( array( 'it' => 'Costi di restituzione', 'en' => 'Return costs', 'fr' => 'Frais de retour', 'de' => 'Rückgabekosten', 'es' => 'Costes de devolución' ), $lang ),
        'p4' => fa_legal_pick( array(
            'it' => 'Le spese di restituzione del prodotto sono a carico del cliente, salvo i casi in cui il prodotto risulti difettoso o non conforme, oppure nei casi in cui la normativa applicabile preveda diversamente.',
            'en' => 'The costs of returning the product are borne by the customer, except when the product is defective or non-conforming, or where applicable law provides otherwise.',
            'fr' => "Les frais de retour du produit sont à la charge du client, sauf si le produit s'avère défectueux ou non conforme, ou dans les cas où la réglementation applicable en dispose autrement.",
            'de' => 'Die Kosten für die Rückgabe des Produkts trägt der Kunde, außer wenn das Produkt mangelhaft oder nicht vertragsgemäß ist oder die geltenden Rechtsvorschriften etwas anderes vorsehen.',
            'es' => 'Los gastos de devolución del producto corren a cargo del cliente, salvo en los casos en que el producto resulte defectuoso o no conforme, o cuando la normativa aplicable disponga lo contrario.',
        ), $lang ),
        'h4' => fa_legal_pick( array( 'it' => 'Modalità e tempi del rimborso', 'en' => 'Refund method and timing', 'fr' => 'Modalités et délais de remboursement', 'de' => 'Art und Zeitpunkt der Erstattung', 'es' => 'Método y plazos del reembolso' ), $lang ),
        'p5' => fa_legal_pick( array(
            'it' => "Il rimborso viene effettuato secondo i termini previsti dalla normativa applicabile e, quando previsto, comprende anche il costo della modalità di spedizione standard sostenuto inizialmente dal cliente. Il rimborso viene erogato di norma utilizzando lo stesso metodo di pagamento impiegato per l'acquisto.",
            'en' => 'The refund is issued according to the terms set out by applicable law and, where provided for, also includes the initial standard shipping cost paid by the customer. The refund is normally made using the same payment method used for the purchase.',
            'fr' => "Le remboursement est effectué selon les modalités prévues par la réglementation applicable et, lorsque cela est prévu, comprend également le coût du mode de livraison standard initialement payé par le client. Le remboursement est en général effectué avec le même moyen de paiement que celui utilisé pour l'achat.",
            'de' => 'Die Erstattung erfolgt gemäß den geltenden Rechtsvorschriften und umfasst, sofern vorgesehen, auch die vom Kunden ursprünglich getragenen Standardversandkosten. Die Erstattung erfolgt in der Regel über dieselbe Zahlungsmethode, die für den Kauf verwendet wurde.',
            'es' => 'El reembolso se realiza conforme a los términos previstos por la normativa aplicable y, cuando corresponda, incluye también el coste del envío estándar inicial abonado por el cliente. El reembolso se efectúa normalmente utilizando el mismo método de pago empleado en la compra.',
        ), $lang ),
        'h5' => fa_legal_pick( array( 'it' => 'Condizioni del prodotto da restituire', 'en' => 'Condition of the product to be returned', 'fr' => 'État du produit à retourner', 'de' => 'Zustand des zurückzusendenden Produkts', 'es' => 'Estado del producto a devolver' ), $lang ),
        'p6' => fa_legal_pick( array(
            'it' => 'Il prodotto deve essere restituito integro. Il cliente può manipolare e ispezionare il prodotto solamente nella misura necessaria a verificarne la natura, le caratteristiche e il funzionamento.',
            'en' => 'The product must be returned intact. The customer may only handle and inspect the product to the extent necessary to establish its nature, characteristics and functioning.',
            'fr' => "Le produit doit être retourné intact. Le client ne peut manipuler et inspecter le produit que dans la mesure nécessaire pour vérifier sa nature, ses caractéristiques et son fonctionnement.",
            'de' => 'Das Produkt muss unversehrt zurückgegeben werden. Der Kunde darf das Produkt nur in dem Maße handhaben und prüfen, wie es zur Feststellung von Art, Eigenschaften und Funktionsweise erforderlich ist.',
            'es' => 'El producto debe devolverse en perfecto estado. El cliente solo puede manipular e inspeccionar el producto en la medida necesaria para comprobar su naturaleza, características y funcionamiento.',
        ), $lang ),
        'h6' => fa_legal_pick( array( 'it' => 'Prodotti difettosi o non conformi', 'en' => 'Defective or non-conforming products', 'fr' => 'Produits défectueux ou non conformes', 'de' => 'Mangelhafte oder nicht vertragsgemäße Produkte', 'es' => 'Productos defectuosos o no conformes' ), $lang ),
        'p7' => fa_legal_pick( array(
            'it' => 'In caso di prodotto difettoso o non conforme a quanto ordinato, si applicano le disposizioni relative alla garanzia legale prevista dal Codice del Consumo, indipendentemente dai termini ordinari sopra indicati per il recesso.',
            'en' => 'In the event of a defective product or one that does not conform to the order, the provisions relating to the legal warranty under applicable consumer protection law apply, regardless of the ordinary withdrawal terms indicated above.',
            'fr' => "En cas de produit défectueux ou non conforme à la commande, les dispositions relatives à la garantie légale prévue par le Code de la consommation s'appliquent, indépendamment des délais ordinaires de rétractation indiqués ci-dessus.",
            'de' => 'Bei einem mangelhaften oder nicht der Bestellung entsprechenden Produkt gelten die Bestimmungen zur gesetzlichen Gewährleistung, unabhängig von den oben genannten gewöhnlichen Widerrufsfristen.',
            'es' => 'En caso de producto defectuoso o no conforme con lo pedido, se aplican las disposiciones relativas a la garantía legal, con independencia de los plazos ordinarios de desistimiento antes indicados.',
        ), $lang ),
        'h7' => fa_legal_pick( array( 'it' => 'Esclusioni dal diritto di recesso', 'en' => 'Exclusions from the right of withdrawal', 'fr' => 'Exclusions du droit de rétractation', 'de' => 'Ausschlüsse vom Widerrufsrecht', 'es' => 'Exclusiones del derecho de desistimiento' ), $lang ),
        'p8' => fa_legal_pick( array(
            'it' => "La normativa applicabile prevede alcuni casi specifici nei quali il diritto di recesso può essere escluso. Per qualsiasi dubbio sulla propria situazione specifica, il cliente può contattare Formula Acqua prima di procedere con l'ordine o con la restituzione.",
            'en' => 'Applicable law provides for certain specific cases in which the right of withdrawal may be excluded. If in doubt about a specific situation, the customer can contact Formula Acqua before placing the order or proceeding with a return.',
            'fr' => "La réglementation applicable prévoit certains cas spécifiques dans lesquels le droit de rétractation peut être exclu. En cas de doute sur sa situation particulière, le client peut contacter Formula Acqua avant de procéder à la commande ou au retour.",
            'de' => 'Die geltenden Rechtsvorschriften sehen bestimmte Fälle vor, in denen das Widerrufsrecht ausgeschlossen sein kann. Bei Zweifeln zur eigenen Situation kann der Kunde Formula Acqua vor der Bestellung oder Rückgabe kontaktieren.',
            'es' => 'La normativa aplicable prevé algunos casos específicos en los que el derecho de desistimiento puede quedar excluido. Ante cualquier duda sobre su situación concreta, el cliente puede contactar con Formula Acqua antes de realizar el pedido o la devolución.',
        ), $lang ),
        'h8' => fa_legal_pick( array( 'it' => 'Come richiedere un reso', 'en' => 'How to request a return', 'fr' => 'Comment demander un retour', 'de' => 'So beantragen Sie eine Rückgabe', 'es' => 'Cómo solicitar una devolución' ), $lang ),
        'p9' => fa_legal_pick( array(
            'it' => 'Per avviare una procedura di reso o rimborso è possibile contattare Formula Acqua tramite:',
            'en' => 'To start a return or refund procedure, you can contact Formula Acqua through:',
            'fr' => 'Pour engager une procédure de retour ou de remboursement, vous pouvez contacter Formula Acqua via :',
            'de' => 'Um ein Rückgabe- oder Erstattungsverfahren einzuleiten, können Sie Formula Acqua wie folgt kontaktieren:',
            'es' => 'Para iniciar un proceso de devolución o reembolso, puedes contactar con Formula Acqua a través de:',
        ), $lang ),
    );

    ob_start();
    ?>
    <h2><?php echo esc_html( $t['h1'] ); ?></h2>
    <p><?php echo $t['p1']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
    <p><?php echo sprintf( $t['p2'], esc_html( $phone ), esc_html( $email ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

    <h2><?php echo esc_html( $t['h2'] ); ?></h2>
    <p><?php echo $t['p3']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

    <h2><?php echo esc_html( $t['h3'] ); ?></h2>
    <p><?php echo esc_html( $t['p4'] ); ?></p>

    <h2><?php echo esc_html( $t['h4'] ); ?></h2>
    <p><?php echo esc_html( $t['p5'] ); ?></p>

    <h2><?php echo esc_html( $t['h5'] ); ?></h2>
    <p><?php echo esc_html( $t['p6'] ); ?></p>

    <h2><?php echo esc_html( $t['h6'] ); ?></h2>
    <p><?php echo esc_html( $t['p7'] ); ?></p>

    <h2><?php echo esc_html( $t['h7'] ); ?></h2>
    <p><?php echo esc_html( $t['p8'] ); ?></p>

    <h2><?php echo esc_html( $t['h8'] ); ?></h2>
    <p><?php echo esc_html( $t['p9'] ); ?></p>
    <ul>
        <li>WhatsApp: <?php echo esc_html( $phone ); ?></li>
        <li>PEC: <?php echo esc_html( $email ); ?></li>
    </ul>
    <?php
    return trim( (string) ob_get_clean() );
}

/**
 * Contenuto "Privacy Policy", predisposto in tutte le lingue del sito.
 * Testo generico standard per un piccolo e-commerce senza DPO dedicato,
 * senza inventare dati aziendali non ancora disponibili (P.IVA, sede legale).
 *
 * @param string|null $lang Codice lingua (it|en|fr|de|es). Default: lingua attiva.
 * @return string
 */
function fa_get_privacy_policy_content( $lang = null ) {
    $lang  = fa_legal_lang( $lang );
    $email = get_theme_mod( 'fa_email', 'formulacqua@ionospec.it' );
    $phone = get_theme_mod( 'fa_phone', '+39 327 916 6457' );
    $owner = fa_get_owner_name();

    $t = array(
        'h1' => fa_legal_pick( array( 'it' => '1. Titolare del trattamento', 'en' => '1. Data controller', 'fr' => '1. Responsable du traitement', 'de' => '1. Verantwortlicher für die Datenverarbeitung', 'es' => '1. Responsable del tratamiento' ), $lang ),
        'p1' => fa_legal_pick( array(
            'it' => 'Il titolare del trattamento dei dati raccolti tramite questo sito è <strong>Formula Acqua</strong>, nella persona di <strong>%s</strong>.',
            'en' => 'The controller of the data collected through this site is <strong>Formula Acqua</strong>, represented by <strong>%s</strong>.',
            'fr' => 'Le responsable du traitement des données collectées via ce site est <strong>Formula Acqua</strong>, représentée par <strong>%s</strong>.',
            'de' => 'Verantwortlicher für die über diese Website erhobenen Daten ist <strong>Formula Acqua</strong>, vertreten durch <strong>%s</strong>.',
            'es' => 'El responsable del tratamiento de los datos recogidos a través de este sitio es <strong>Formula Acqua</strong>, representada por <strong>%s</strong>.',
        ), $lang ),
        'h2' => fa_legal_pick( array( 'it' => '2. Dati raccolti', 'en' => '2. Data collected', 'fr' => '2. Données collectées', 'de' => '2. Erhobene Daten', 'es' => '2. Datos recogidos' ), $lang ),
        'p2' => fa_legal_pick( array(
            'it' => "Nell'ambito della normale attività del sito e dell'e-commerce possono essere raccolti: dati forniti volontariamente dall'utente (registrazione account, acquisto, contatto: nome, cognome, indirizzo email, indirizzo di spedizione/fatturazione, numero di telefono); dati di navigazione (indirizzo IP, browser, pagine visitate, raccolti in forma automatica); dati relativi agli ordini effettuati sul sito.",
            'en' => 'In the normal course of operating the site and the online store, we may collect: data provided voluntarily by the user (account registration, purchase, contact: first name, last name, email address, shipping/billing address, phone number); browsing data (IP address, browser, pages visited, collected automatically); data relating to orders placed on the site.',
            'fr' => "Dans le cadre du fonctionnement normal du site et du commerce en ligne, peuvent être collectées : les données fournies volontairement par l'utilisateur (inscription au compte, achat, contact : nom, prénom, adresse e-mail, adresse de livraison/facturation, numéro de téléphone) ; les données de navigation (adresse IP, navigateur, pages visitées, collectées automatiquement) ; les données relatives aux commandes passées sur le site.",
            'de' => 'Im Rahmen des normalen Betriebs der Website und des Online-Shops können folgende Daten erhoben werden: vom Nutzer freiwillig angegebene Daten (Kontoregistrierung, Kauf, Kontakt: Vor- und Nachname, E-Mail-Adresse, Liefer-/Rechnungsadresse, Telefonnummer); Navigationsdaten (IP-Adresse, Browser, besuchte Seiten, automatisch erfasst); Daten zu über die Website getätigten Bestellungen.',
            'es' => 'En el marco de la actividad normal del sitio y del comercio electrónico se pueden recopilar: datos proporcionados voluntariamente por el usuario (registro de cuenta, compra, contacto: nombre, apellidos, dirección de correo electrónico, dirección de envío/facturación, número de teléfono); datos de navegación (dirección IP, navegador, páginas visitadas, recogidos de forma automática); datos relativos a los pedidos realizados en el sitio.',
        ), $lang ),
        'h3' => fa_legal_pick( array( 'it' => '3. Finalità del trattamento', 'en' => '3. Purpose of processing', 'fr' => '3. Finalités du traitement', 'de' => '3. Zweck der Verarbeitung', 'es' => '3. Finalidad del tratamiento' ), $lang ),
        'p3' => fa_legal_pick( array(
            'it' => "I dati sono trattati per: gestire la registrazione e l'account utente; evadere gli ordini effettuati ed erogare l'assistenza clienti; adempiere obblighi di legge (fiscali, contabili); inviare comunicazioni relative all'ordine (conferma, spedizione); previo consenso, inviare comunicazioni promozionali o newsletter.",
            'en' => "Data is processed to: manage user registration and accounts; fulfil orders placed and provide customer support; comply with legal obligations (tax, accounting); send order-related communications (confirmation, shipping); with prior consent, send promotional communications or newsletters.",
            'fr' => "Les données sont traitées afin de : gérer l'inscription et le compte utilisateur ; traiter les commandes passées et fournir le service client ; respecter les obligations légales (fiscales, comptables) ; envoyer des communications relatives à la commande (confirmation, expédition) ; sous réserve du consentement préalable, envoyer des communications promotionnelles ou une newsletter.",
            'de' => 'Die Daten werden verarbeitet, um: die Registrierung und das Nutzerkonto zu verwalten; erteilte Bestellungen abzuwickeln und Kundensupport zu leisten; gesetzliche Pflichten (steuerlich, buchhalterisch) zu erfüllen; bestellbezogene Mitteilungen zu senden (Bestätigung, Versand); nach vorheriger Einwilligung Werbemitteilungen oder Newsletter zu versenden.',
            'es' => 'Los datos se tratan para: gestionar el registro y la cuenta del usuario; tramitar los pedidos realizados y prestar la atención al cliente; cumplir con obligaciones legales (fiscales, contables); enviar comunicaciones relacionadas con el pedido (confirmación, envío); previo consentimiento, enviar comunicaciones promocionales o boletines informativos.',
        ), $lang ),
        'h4' => fa_legal_pick( array( 'it' => '4. Base giuridica', 'en' => '4. Legal basis', 'fr' => '4. Base juridique', 'de' => '4. Rechtsgrundlage', 'es' => '4. Base jurídica' ), $lang ),
        'p4' => fa_legal_pick( array(
            'it' => "Il trattamento si basa, a seconda dei casi, sull'esecuzione di un contratto (acquisto), sull'adempimento di obblighi legali, sul consenso dell'utente (es. newsletter) o sul legittimo interesse del titolare.",
            'en' => "Processing is based, depending on the case, on the performance of a contract (purchase), compliance with legal obligations, the user's consent (e.g. newsletter), or the controller's legitimate interest.",
            'fr' => "Le traitement repose, selon les cas, sur l'exécution d'un contrat (achat), le respect d'obligations légales, le consentement de l'utilisateur (ex. newsletter) ou l'intérêt légitime du responsable du traitement.",
            'de' => 'Die Verarbeitung beruht je nach Fall auf der Vertragserfüllung (Kauf), der Erfüllung gesetzlicher Pflichten, der Einwilligung des Nutzers (z. B. Newsletter) oder dem berechtigten Interesse des Verantwortlichen.',
            'es' => 'El tratamiento se basa, según los casos, en la ejecución de un contrato (compra), en el cumplimiento de obligaciones legales, en el consentimiento del usuario (p. ej. boletín informativo) o en el interés legítimo del responsable.',
        ), $lang ),
        'h5' => fa_legal_pick( array( 'it' => '5. Conservazione dei dati', 'en' => '5. Data retention', 'fr' => '5. Conservation des données', 'de' => '5. Datenspeicherung', 'es' => '5. Conservación de los datos' ), $lang ),
        'p5' => fa_legal_pick( array(
            'it' => 'I dati sono conservati per il tempo necessario a soddisfare le finalità indicate e, laddove previsto, per il periodo richiesto dalla normativa fiscale e contabile applicabile.',
            'en' => 'Data is retained for as long as necessary to fulfil the stated purposes and, where required, for the period requested by applicable tax and accounting regulations.',
            'fr' => "Les données sont conservées pendant la durée nécessaire pour répondre aux finalités indiquées et, le cas échéant, pendant la période requise par la réglementation fiscale et comptable applicable.",
            'de' => 'Die Daten werden so lange gespeichert, wie es zur Erreichung der genannten Zwecke erforderlich ist, und, sofern vorgesehen, für den nach den geltenden steuerlichen und buchhalterischen Vorschriften erforderlichen Zeitraum.',
            'es' => 'Los datos se conservan durante el tiempo necesario para cumplir las finalidades indicadas y, cuando así lo exija, durante el período requerido por la normativa fiscal y contable aplicable.',
        ), $lang ),
        'h6' => fa_legal_pick( array( 'it' => '6. Cookie', 'en' => '6. Cookies', 'fr' => '6. Cookies', 'de' => '6. Cookies', 'es' => '6. Cookies' ), $lang ),
        'p6' => fa_legal_pick( array(
            'it' => 'Il sito utilizza cookie tecnici necessari al funzionamento e, se accettati dall\'utente, cookie di terze parti. Per maggiori informazioni consulta la %s.',
            'en' => 'The site uses technical cookies necessary for its operation and, if accepted by the user, third-party cookies. For more information, see the %s.',
            'fr' => "Le site utilise des cookies techniques nécessaires à son fonctionnement et, si l'utilisateur les accepte, des cookies tiers. Pour plus d'informations, consultez la %s.",
            'de' => 'Die Website verwendet technisch notwendige Cookies sowie, sofern vom Nutzer akzeptiert, Cookies von Drittanbietern. Weitere Informationen finden Sie in der %s.',
            'es' => 'El sitio utiliza cookies técnicas necesarias para su funcionamiento y, si el usuario las acepta, cookies de terceros. Para más información, consulta la %s.',
        ), $lang ),
        'cookie_link_label' => fa_legal_pick( array( 'it' => 'Cookie Policy', 'en' => 'Cookie Policy', 'fr' => 'Politique de cookies', 'de' => 'Cookie-Richtlinie', 'es' => 'Política de cookies' ), $lang ),
        'h7' => fa_legal_pick( array( 'it' => "7. Diritti dell'interessato", 'en' => '7. Rights of the data subject', 'fr' => '7. Droits de la personne concernée', 'de' => '7. Rechte der betroffenen Person', 'es' => '7. Derechos del interesado' ), $lang ),
        'p7' => fa_legal_pick( array(
            'it' => "L'utente ha diritto di accedere ai propri dati, richiederne la rettifica, la cancellazione, la limitazione del trattamento, la portabilità, nonché di opporsi al trattamento, scrivendo a Formula Acqua ai contatti indicati in questa pagina.",
            'en' => 'The user has the right to access their data, and to request rectification, erasure, restriction of processing, or portability, as well as to object to processing, by writing to Formula Acqua using the contact details on this page.',
            'fr' => "L'utilisateur a le droit d'accéder à ses données, d'en demander la rectification, la suppression, la limitation du traitement, la portabilité, ainsi que de s'opposer au traitement, en écrivant à Formula Acqua aux coordonnées indiquées sur cette page.",
            'de' => 'Der Nutzer hat das Recht, auf seine Daten zuzugreifen, deren Berichtigung, Löschung, Einschränkung der Verarbeitung oder Übertragbarkeit zu verlangen sowie der Verarbeitung zu widersprechen, indem er sich an Formula Acqua über die auf dieser Seite angegebenen Kontaktdaten wendet.',
            'es' => 'El usuario tiene derecho a acceder a sus datos, solicitar su rectificación, supresión, limitación del tratamiento, portabilidad, así como a oponerse al tratamiento, escribiendo a Formula Acqua a los contactos indicados en esta página.',
        ), $lang ),
        'h8' => fa_legal_pick( array( 'it' => '8. Sicurezza dei dati', 'en' => '8. Data security', 'fr' => '8. Sécurité des données', 'de' => '8. Datensicherheit', 'es' => '8. Seguridad de los datos' ), $lang ),
        'p8' => fa_legal_pick( array(
            'it' => 'Formula Acqua adotta misure tecniche e organizzative adeguate a proteggere i dati personali da accessi non autorizzati, perdita o divulgazione indebita.',
            'en' => 'Formula Acqua adopts appropriate technical and organisational measures to protect personal data from unauthorised access, loss or improper disclosure.',
            'fr' => 'Formula Acqua adopte des mesures techniques et organisationnelles appropriées pour protéger les données personnelles contre les accès non autorisés, la perte ou la divulgation indue.',
            'de' => 'Formula Acqua trifft geeignete technische und organisatorische Maßnahmen, um personenbezogene Daten vor unbefugtem Zugriff, Verlust oder unrechtmäßiger Offenlegung zu schützen.',
            'es' => 'Formula Acqua adopta medidas técnicas y organizativas adecuadas para proteger los datos personales frente a accesos no autorizados, pérdida o divulgación indebida.',
        ), $lang ),
        'h9' => fa_legal_pick( array( 'it' => '9. Modifiche alla presente informativa', 'en' => '9. Changes to this policy', 'fr' => '9. Modifications de la présente politique', 'de' => '9. Änderungen dieser Richtlinie', 'es' => '9. Cambios en esta política' ), $lang ),
        'p9' => fa_legal_pick( array(
            'it' => 'La presente informativa può essere aggiornata nel tempo; eventuali modifiche saranno pubblicate su questa pagina.',
            'en' => 'This policy may be updated from time to time; any changes will be published on this page.',
            'fr' => 'La présente politique peut être mise à jour au fil du temps ; toute modification sera publiée sur cette page.',
            'de' => 'Diese Richtlinie kann im Laufe der Zeit aktualisiert werden; etwaige Änderungen werden auf dieser Seite veröffentlicht.',
            'es' => 'Esta política puede actualizarse con el tiempo; cualquier cambio se publicará en esta página.',
        ), $lang ),
        'h10' => fa_legal_pick( array( 'it' => '10. Contatti', 'en' => '10. Contact', 'fr' => '10. Contact', 'de' => '10. Kontakt', 'es' => '10. Contacto' ), $lang ),
        'p10' => fa_legal_pick( array(
            'it' => 'Per qualsiasi richiesta relativa al trattamento dei propri dati personali è possibile contattare Formula Acqua:',
            'en' => 'For any request relating to the processing of personal data, you can contact Formula Acqua:',
            'fr' => 'Pour toute demande relative au traitement de vos données personnelles, vous pouvez contacter Formula Acqua :',
            'de' => 'Für Anfragen zur Verarbeitung personenbezogener Daten können Sie Formula Acqua kontaktieren:',
            'es' => 'Para cualquier solicitud relacionada con el tratamiento de tus datos personales, puedes contactar con Formula Acqua:',
        ), $lang ),
    );

    $cookie_url  = fa_get_legal_page_url( 'cookie-policy', $lang );
    $cookie_link = '<a href="' . esc_url( $cookie_url ) . '">' . esc_html( $t['cookie_link_label'] ) . '</a>';

    ob_start();
    ?>
    <h2><?php echo esc_html( $t['h1'] ); ?></h2>
    <p><?php echo sprintf( $t['p1'], esc_html( $owner ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
    <ul>
        <li>PEC: <?php echo esc_html( $email ); ?></li>
        <li><?php echo esc_html( fa_legal_pick( array( 'it' => 'Telefono / WhatsApp', 'en' => 'Phone / WhatsApp', 'fr' => 'Téléphone / WhatsApp', 'de' => 'Telefon / WhatsApp', 'es' => 'Teléfono / WhatsApp' ), $lang ) ); ?>: <?php echo esc_html( $phone ); ?></li>
    </ul>

    <h2><?php echo esc_html( $t['h2'] ); ?></h2>
    <p><?php echo esc_html( $t['p2'] ); ?></p>

    <h2><?php echo esc_html( $t['h3'] ); ?></h2>
    <p><?php echo esc_html( $t['p3'] ); ?></p>

    <h2><?php echo esc_html( $t['h4'] ); ?></h2>
    <p><?php echo esc_html( $t['p4'] ); ?></p>

    <h2><?php echo esc_html( $t['h5'] ); ?></h2>
    <p><?php echo esc_html( $t['p5'] ); ?></p>

    <h2><?php echo esc_html( $t['h6'] ); ?></h2>
    <p><?php echo sprintf( $t['p6'], $cookie_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

    <h2><?php echo esc_html( $t['h7'] ); ?></h2>
    <p><?php echo esc_html( $t['p7'] ); ?></p>

    <h2><?php echo esc_html( $t['h8'] ); ?></h2>
    <p><?php echo esc_html( $t['p8'] ); ?></p>

    <h2><?php echo esc_html( $t['h9'] ); ?></h2>
    <p><?php echo esc_html( $t['p9'] ); ?></p>

    <h2><?php echo esc_html( $t['h10'] ); ?></h2>
    <p><?php echo esc_html( $t['p10'] ); ?></p>
    <ul>
        <li>PEC: <?php echo esc_html( $email ); ?></li>
        <li><?php echo esc_html( fa_legal_pick( array( 'it' => 'Telefono / WhatsApp', 'en' => 'Phone / WhatsApp', 'fr' => 'Téléphone / WhatsApp', 'de' => 'Telefon / WhatsApp', 'es' => 'Teléfono / WhatsApp' ), $lang ) ); ?>: <?php echo esc_html( $phone ); ?></li>
    </ul>
    <?php
    return trim( (string) ob_get_clean() );
}

/**
 * Contenuto "Cookie Policy", predisposto in tutte le lingue del sito.
 *
 * @param string|null $lang Codice lingua (it|en|fr|de|es). Default: lingua attiva.
 * @return string
 */
function fa_get_cookie_policy_content( $lang = null ) {
    $lang  = fa_legal_lang( $lang );
    $email = get_theme_mod( 'fa_email', 'formulacqua@ionospec.it' );
    $phone = get_theme_mod( 'fa_phone', '+39 327 916 6457' );

    $t = array(
        'h1' => fa_legal_pick( array( 'it' => 'Cosa sono i cookie', 'en' => 'What cookies are', 'fr' => 'Qu\'est-ce qu\'un cookie', 'de' => 'Was sind Cookies', 'es' => 'Qué son las cookies' ), $lang ),
        'p1' => fa_legal_pick( array(
            'it' => 'I cookie sono piccoli file di testo che i siti visitati inviano al dispositivo dell\'utente, dove vengono memorizzati per essere poi ritrasmessi agli stessi siti alla visita successiva.',
            'en' => 'Cookies are small text files that visited sites send to the user\'s device, where they are stored to be sent back to the same sites on the next visit.',
            'fr' => "Les cookies sont de petits fichiers texte que les sites visités envoient à l'appareil de l'utilisateur, où ils sont enregistrés pour être renvoyés aux mêmes sites lors de la visite suivante.",
            'de' => 'Cookies sind kleine Textdateien, die von besuchten Websites an das Gerät des Nutzers gesendet und dort gespeichert werden, um bei einem erneuten Besuch derselben Website wieder übermittelt zu werden.',
            'es' => 'Las cookies son pequeños archivos de texto que los sitios visitados envían al dispositivo del usuario, donde se almacenan para ser enviados de nuevo a esos mismos sitios en la siguiente visita.',
        ), $lang ),
        'h2' => fa_legal_pick( array( 'it' => 'Cookie utilizzati da questo sito', 'en' => 'Cookies used by this site', 'fr' => 'Cookies utilisés par ce site', 'de' => 'Von dieser Website verwendete Cookies', 'es' => 'Cookies utilizadas por este sitio' ), $lang ),
        'p2' => fa_legal_pick( array(
            'it' => 'Il sito Formula Acqua utilizza le seguenti tipologie di cookie:',
            'en' => 'The Formula Acqua website uses the following types of cookies:',
            'fr' => 'Le site Formula Acqua utilise les types de cookies suivants :',
            'de' => 'Die Website von Formula Acqua verwendet folgende Arten von Cookies:',
            'es' => 'El sitio Formula Acqua utiliza los siguientes tipos de cookies:',
        ), $lang ),
        'li1' => fa_legal_pick( array(
            'it' => 'Cookie tecnici necessari, indispensabili per il corretto funzionamento del sito (es. gestione del carrello, sessione utente, accesso all\'area account). Questi cookie non richiedono consenso.',
            'en' => 'Necessary technical cookies, essential for the site to work properly (e.g. cart management, user session, access to the account area). These cookies do not require consent.',
            'fr' => "Cookies techniques nécessaires, indispensables au bon fonctionnement du site (ex. gestion du panier, session utilisateur, accès à l'espace compte). Ces cookies ne nécessitent pas de consentement.",
            'de' => 'Notwendige technische Cookies, die für das ordnungsgemäße Funktionieren der Website unerlässlich sind (z. B. Warenkorbverwaltung, Nutzersitzung, Zugriff auf den Kontobereich). Für diese Cookies ist keine Einwilligung erforderlich.',
            'es' => 'Cookies técnicas necesarias, indispensables para el correcto funcionamiento del sitio (p. ej. gestión del carrito, sesión del usuario, acceso al área de cuenta). Estas cookies no requieren consentimiento.',
        ), $lang ),
        'li2' => fa_legal_pick( array(
            'it' => 'Cookie di preferenza, utilizzati per ricordare le scelte dell\'utente (es. lingua selezionata).',
            'en' => 'Preference cookies, used to remember the user\'s choices (e.g. selected language).',
            'fr' => "Cookies de préférence, utilisés pour mémoriser les choix de l'utilisateur (ex. langue sélectionnée).",
            'de' => 'Präferenz-Cookies, die verwendet werden, um die Auswahl des Nutzers zu speichern (z. B. ausgewählte Sprache).',
            'es' => 'Cookies de preferencia, utilizadas para recordar las elecciones del usuario (p. ej. idioma seleccionado).',
        ), $lang ),
        'li3' => fa_legal_pick( array(
            'it' => 'Cookie statistici/analitici, se attivi, utilizzati in forma aggregata per comprendere come viene utilizzato il sito.',
            'en' => 'Statistical/analytics cookies, if active, used in aggregate form to understand how the site is used.',
            'fr' => "Cookies statistiques/analytiques, s'ils sont actifs, utilisés sous forme agrégée pour comprendre comment le site est utilisé.",
            'de' => 'Statistik-/Analyse-Cookies, sofern aktiv, die in aggregierter Form verwendet werden, um die Nutzung der Website zu verstehen.',
            'es' => 'Cookies estadísticas/analíticas, si están activas, utilizadas de forma agregada para comprender cómo se usa el sitio.',
        ), $lang ),
        'h3' => fa_legal_pick( array( 'it' => 'Gestione dei cookie', 'en' => 'Managing cookies', 'fr' => 'Gestion des cookies', 'de' => 'Verwaltung von Cookies', 'es' => 'Gestión de las cookies' ), $lang ),
        'p3' => fa_legal_pick( array(
            'it' => 'È possibile gestire o disattivare i cookie tramite le impostazioni del proprio browser. La disattivazione dei cookie tecnici potrebbe impedire il corretto funzionamento di alcune funzionalità del sito, come il carrello o l\'accesso all\'area account.',
            'en' => 'Cookies can be managed or disabled through your browser settings. Disabling technical cookies may prevent some site features, such as the cart or access to the account area, from working properly.',
            'fr' => "Il est possible de gérer ou de désactiver les cookies via les paramètres de votre navigateur. La désactivation des cookies techniques peut empêcher le bon fonctionnement de certaines fonctionnalités du site, comme le panier ou l'accès à l'espace compte.",
            'de' => 'Cookies können über die Einstellungen Ihres Browsers verwaltet oder deaktiviert werden. Das Deaktivieren technischer Cookies kann dazu führen, dass einige Funktionen der Website, wie der Warenkorb oder der Zugriff auf den Kontobereich, nicht mehr richtig funktionieren.',
            'es' => 'Es posible gestionar o desactivar las cookies a través de la configuración de tu navegador. Desactivar las cookies técnicas podría impedir el correcto funcionamiento de algunas funciones del sitio, como el carrito o el acceso al área de cuenta.',
        ), $lang ),
        'h4' => fa_legal_pick( array( 'it' => 'Cookie di terze parti', 'en' => 'Third-party cookies', 'fr' => 'Cookies tiers', 'de' => 'Cookies von Drittanbietern', 'es' => 'Cookies de terceros' ), $lang ),
        'p4' => fa_legal_pick( array(
            'it' => 'Il sito potrebbe includere elementi (es. mappe, video, pulsanti social) forniti da servizi terzi che possono installare propri cookie. Formula Acqua non ha controllo diretto su tali cookie: si consiglia di consultare le informative privacy dei rispettivi fornitori.',
            'en' => 'The site may include elements (e.g. maps, videos, social buttons) provided by third-party services that may set their own cookies. Formula Acqua has no direct control over such cookies: we recommend consulting the privacy policies of the relevant providers.',
            'fr' => "Le site peut inclure des éléments (ex. cartes, vidéos, boutons sociaux) fournis par des services tiers susceptibles d'installer leurs propres cookies. Formula Acqua n'a pas de contrôle direct sur ces cookies : il est conseillé de consulter les politiques de confidentialité des fournisseurs concernés.",
            'de' => 'Die Website kann Elemente (z. B. Karten, Videos, Social-Media-Buttons) von Drittanbietern enthalten, die eigene Cookies setzen können. Formula Acqua hat keine direkte Kontrolle über diese Cookies: Wir empfehlen, die Datenschutzrichtlinien der jeweiligen Anbieter zu konsultieren.',
            'es' => 'El sitio puede incluir elementos (p. ej. mapas, vídeos, botones sociales) proporcionados por servicios de terceros que pueden instalar sus propias cookies. Formula Acqua no tiene control directo sobre dichas cookies: se recomienda consultar las políticas de privacidad de los respectivos proveedores.',
        ), $lang ),
        'h5' => fa_legal_pick( array( 'it' => 'Contatti', 'en' => 'Contact', 'fr' => 'Contact', 'de' => 'Kontakt', 'es' => 'Contacto' ), $lang ),
        'p5' => fa_legal_pick( array(
            'it' => 'Per qualsiasi domanda relativa alla presente Cookie Policy è possibile contattare Formula Acqua:',
            'en' => 'For any question about this Cookie Policy, you can contact Formula Acqua:',
            'fr' => 'Pour toute question relative à la présente politique de cookies, vous pouvez contacter Formula Acqua :',
            'de' => 'Bei Fragen zu dieser Cookie-Richtlinie können Sie Formula Acqua kontaktieren:',
            'es' => 'Para cualquier duda sobre esta política de cookies, puedes contactar con Formula Acqua:',
        ), $lang ),
    );

    ob_start();
    ?>
    <h2><?php echo esc_html( $t['h1'] ); ?></h2>
    <p><?php echo esc_html( $t['p1'] ); ?></p>

    <h2><?php echo esc_html( $t['h2'] ); ?></h2>
    <p><?php echo esc_html( $t['p2'] ); ?></p>
    <ul>
        <li><?php echo esc_html( $t['li1'] ); ?></li>
        <li><?php echo esc_html( $t['li2'] ); ?></li>
        <li><?php echo esc_html( $t['li3'] ); ?></li>
    </ul>

    <h2><?php echo esc_html( $t['h3'] ); ?></h2>
    <p><?php echo esc_html( $t['p3'] ); ?></p>

    <h2><?php echo esc_html( $t['h4'] ); ?></h2>
    <p><?php echo esc_html( $t['p4'] ); ?></p>

    <h2><?php echo esc_html( $t['h5'] ); ?></h2>
    <p><?php echo esc_html( $t['p5'] ); ?></p>
    <ul>
        <li>PEC: <?php echo esc_html( $email ); ?></li>
        <li><?php echo esc_html( fa_legal_pick( array( 'it' => 'Telefono / WhatsApp', 'en' => 'Phone / WhatsApp', 'fr' => 'Téléphone / WhatsApp', 'de' => 'Telefon / WhatsApp', 'es' => 'Teléfono / WhatsApp' ), $lang ) ); ?>: <?php echo esc_html( $phone ); ?></li>
    </ul>
    <?php
    return trim( (string) ob_get_clean() );
}

/**
 * Pagine legali di base se mancanti, più aggiornamento del contenuto
 * "Termini e Condizioni" e "Resi e Rimborsi" quando risultano ancora al
 * contenuto segnaposto (non tocca pagine già personalizzate manualmente).
 */
function fa_ensure_legal_pages() {
    $pages = array(
        'privacy-policy'       => array( 'Privacy Policy', 'Informativa sulla privacy di Formula Acqua. Aggiorna questo contenuto con i tuoi dati aziendali.' ),
        'cookie-policy'        => array( 'Cookie Policy', 'Informativa sui cookie utilizzati dal sito Formula Acqua.' ),
        'termini-e-condizioni' => array( 'Termini e Condizioni', 'Termini e condizioni di vendita e utilizzo del sito Formula Acqua.' ),
        'contatti'             => array( 'Contatti', 'Contattaci per assistenza sui prodotti per piscina.' ),
    );

    // Segnaposto storici: se una pagina contiene ancora esattamente uno di
    // questi testi, oppure il boilerplate inglese generato di default da
    // WordPress, può essere aggiornata in sicurezza (non è stata modificata
    // manualmente dal titolare).
    $known_placeholders = array(
        'Termini e condizioni di vendita e utilizzo del sito Formula Acqua.',
        'Informativa sui cookie utilizzati dal sito Formula Acqua.',
    );

    $content_builders = array(
        'privacy-policy'       => 'fa_get_privacy_policy_content',
        'cookie-policy'        => 'fa_get_cookie_policy_content',
        'termini-e-condizioni' => 'fa_get_terms_content',
    );

    if ( get_option( 'fa_legal_pages_ready' ) !== FA_VERSION ) {
        foreach ( $pages as $slug => $data ) {
            $existing = get_page_by_path( $slug, OBJECT, 'page' );
            if ( ! $existing ) {
                $found = get_posts(
                    array(
                        'name'           => $slug,
                        'post_type'      => 'page',
                        'post_status'    => array( 'publish', 'draft', 'private' ),
                        'posts_per_page' => 1,
                    )
                );
                $existing = ! empty( $found ) ? $found[0] : null;
            }

            if ( $existing ) {
                $update = array( 'ID' => $existing->ID );
                if ( 'publish' !== $existing->post_status ) {
                    $update['post_status'] = 'publish';
                    $update['post_name']   = $slug;
                }
                $is_known_placeholder = in_array( trim( $existing->post_content ), $known_placeholders, true );
                // Boilerplate inglese di default generato da WordPress per la Privacy Policy.
                $is_wp_default_privacy = ( 'privacy-policy' === $slug && false !== strpos( $existing->post_content, 'privacy-policy-tutorial' ) );
                if ( isset( $content_builders[ $slug ] ) && ( $is_known_placeholder || $is_wp_default_privacy ) ) {
                    $update['post_content'] = call_user_func( $content_builders[ $slug ], 'it' );
                }
                if ( count( $update ) > 1 ) {
                    wp_update_post( $update );
                }
                if ( 'contatti' === $slug ) {
                    update_post_meta( $existing->ID, '_wp_page_template', 'page-templates/template-contact.php' );
                }
                continue;
            }

            $content = isset( $content_builders[ $slug ] ) ? call_user_func( $content_builders[ $slug ], 'it' ) : $data[1];
            $new_id  = wp_insert_post(
                array(
                    'post_title'   => $data[0],
                    'post_name'    => $slug,
                    'post_content' => $content,
                    'post_status'  => 'publish',
                    'post_type'    => 'page',
                )
            );
            if ( 'contatti' === $slug && $new_id && ! is_wp_error( $new_id ) ) {
                update_post_meta( $new_id, '_wp_page_template', 'page-templates/template-contact.php' );
            }
        }

        // Collega la pagina Termini e Condizioni al checkbox termini del checkout WooCommerce.
        $terms_page = get_page_by_path( 'termini-e-condizioni', OBJECT, 'page' );
        if ( $terms_page && ! get_option( 'woocommerce_terms_page_id' ) ) {
            update_option( 'woocommerce_terms_page_id', $terms_page->ID );
        }

        // Pagina "Resi e Rimborsi": WooCommerce ne crea già una di default
        // (contenuto in inglese generico) — se non è ancora stata personalizzata
        // la aggiorniamo con il testo reale di Formula Acqua e la pubblichiamo.
        $refund_id = (int) get_option( 'woocommerce_refund_returns_page_id' );
        if ( $refund_id ) {
            $refund_page = get_post( $refund_id );
            if ( $refund_page && false !== strpos( $refund_page->post_content, '{email address}' ) ) {
                wp_update_post(
                    array(
                        'ID'           => $refund_id,
                        'post_title'   => 'Resi e Rimborsi',
                        'post_name'    => 'resi-e-rimborsi',
                        'post_content' => fa_get_refund_policy_content( 'it' ),
                        'post_status'  => 'publish',
                    )
                );
            }
        }

        update_option( 'fa_legal_pages_ready', FA_VERSION, false );
    }
}
add_action( 'init', 'fa_ensure_legal_pages', 25 );

/**
 * Crea o collega la pagina “Chi siamo” al template dedicato, senza duplicarla.
 *
 * @return void
 */
function fa_ensure_about_page() {
    $template = 'page-templates/template-about.php';
    $stored   = (int) get_option( 'fa_about_page_id', 0 );
    if ( $stored && get_post( $stored ) ) {
        if ( get_post_meta( $stored, '_wp_page_template', true ) !== $template ) {
            update_post_meta( $stored, '_wp_page_template', $template );
        }
        return;
    }

    $found = get_posts(
        array(
            'post_type'      => 'page',
            'post_status'    => array( 'publish', 'draft', 'private' ),
            'posts_per_page' => 1,
            'meta_key'       => '_wp_page_template',
            'meta_value'     => $template,
        )
    );
    $page = ! empty( $found ) ? $found[0] : null;

    if ( ! $page ) {
        $page = get_page_by_path( 'chi-siamo', OBJECT, 'page' );
    }
    if ( $page ) {
        update_post_meta( $page->ID, '_wp_page_template', $template );
        if ( 'publish' !== $page->post_status ) {
            wp_update_post(
                array(
                    'ID'          => $page->ID,
                    'post_status' => 'publish',
                )
            );
        }
        update_option( 'fa_about_page_id', (int) $page->ID, false );
        return;
    }

    $new_id = wp_insert_post(
        array(
            'post_title'   => 'Chi siamo',
            'post_name'    => 'chi-siamo',
            'post_content' => '',
            'post_status'  => 'publish',
            'post_type'    => 'page',
        )
    );
    if ( $new_id && ! is_wp_error( $new_id ) ) {
        update_post_meta( $new_id, '_wp_page_template', $template );
        update_option( 'fa_about_page_id', (int) $new_id, false );
    }
}
add_action( 'init', 'fa_ensure_about_page', 26 );

/**
 * URL della pagina Chi siamo.
 *
 * @return string
 */
function fa_get_about_page_url() {
    $id = (int) get_option( 'fa_about_page_id', 0 );
    if ( $id && function_exists( 'pll_get_post' ) ) {
        $translated = pll_get_post( $id );
        if ( $translated ) {
            $id = (int) $translated;
        }
    }
    if ( $id && get_post( $id ) ) {
        return get_permalink( $id );
    }
    return fa_get_legal_page_url( 'chi-siamo' );
}

/**
 * URL della pagina "Resi e Rimborsi" (quella già usata da WooCommerce),
 * nella lingua richiesta (default: lingua attiva).
 *
 * @param string|null $lang Codice lingua opzionale.
 * @return string
 */
function fa_get_refund_policy_url( $lang = null ) {
    $refund_id = (int) get_option( 'woocommerce_refund_returns_page_id' );
    if ( $refund_id && function_exists( 'pll_get_post' ) ) {
        $translated = $lang ? pll_get_post( $refund_id, $lang ) : pll_get_post( $refund_id );
        if ( $translated ) {
            $refund_id = (int) $translated;
        }
    }
    if ( $refund_id && get_post( $refund_id ) ) {
        return get_permalink( $refund_id );
    }
    return home_url( '/resi-e-rimborsi/' );
}

/* Customizer */
function fa_customize_register( $wp_customize ) {
    $wp_customize->add_section( 'fa_contact_info', array( 'title' => 'Informazioni Contatto', 'priority' => 30 ) );

    // Dati ufficiali reali di Formula Acqua. Partita IVA e sede legale sono
    // volutamente vuote: il titolare sta completando le relative pratiche.
    // Non impostare qui valori fittizi: il codice è già predisposto a
    // mostrare questi campi automaticamente non appena verranno compilati.
    $fields = array(
        'fa_phone'     => array( 'Telefono / WhatsApp assistenza', '+39 327 916 6457' ),
        'fa_email'     => array( 'Email / PEC assistenza', 'formulacqua@ionospec.it' ),
        'fa_address'   => array( 'Sede legale (lascia vuoto se non ancora disponibile)', '' ),
        'fa_whatsapp'  => array( 'WhatsApp (numero assistenza clienti)', '+39 327 916 6457' ),
        // Solo l'orario dell'assistenza WhatsApp: i giorni di disponibilità
        // non sono ancora stati definiti, quindi mostriamo solo la fascia
        // oraria. Non è associato in alcun modo all'email/PEC, che resta
        // sempre "aperta" (nessun orario mostrato per l'email).
        'fa_hours'     => array( 'Orario assistenza WhatsApp (solo fascia oraria, es. 08:00–18:00)', '08:00–18:00' ),
        'fa_facebook'  => array( 'Facebook URL', '' ),
        'fa_instagram' => array( 'Instagram URL (profilo ufficiale, es. https://www.instagram.com/...)', 'https://www.instagram.com/formulacqua/' ),
        'fa_piva'      => array( 'Partita IVA (lascia vuoto se non ancora disponibile)', '' ),
    );

    foreach ( $fields as $id => $f ) {
        $wp_customize->add_setting( $id, array( 'default' => $f[1], 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( $id, array( 'label' => $f[0], 'section' => 'fa_contact_info', 'type' => 'text' ) );
    }
}
add_action( 'customize_register', 'fa_customize_register' );

/**
 * Helper centralizzati per i dati ufficiali dell'attività. Usarli al posto
 * di get_theme_mod() sparso nel codice, così Partita IVA e sede legale
 * potranno essere aggiunte in un solo punto quando saranno disponibili.
 */
function fa_get_owner_name() {
    return 'Mirko Ventafridda';
}
function fa_get_legal_address() {
    return trim( (string) get_theme_mod( 'fa_address', '' ) );
}
function fa_get_piva() {
    return trim( (string) get_theme_mod( 'fa_piva', '' ) );
}

/* AJAX Newsletter */
function fa_newsletter_subscribe() {
    check_ajax_referer( 'fa_nonce', 'nonce' );
    $email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
    if ( ! is_email( $email ) ) {
        wp_send_json_error( array( 'message' => fa_t( 'Inserisci un indirizzo email valido.' ) ) );
    }
    $subs = get_option( 'fa_newsletter_subscribers', array() );
    if ( ! in_array( $email, $subs, true ) ) {
        $subs[] = $email;
        update_option( 'fa_newsletter_subscribers', $subs );
    }
    wp_send_json_success( array( 'message' => fa_t( 'Iscrizione completata con successo!' ) ) );
}
add_action( 'wp_ajax_fa_newsletter', 'fa_newsletter_subscribe' );
add_action( 'wp_ajax_nopriv_fa_newsletter', 'fa_newsletter_subscribe' );

/* AJAX Contact */
function fa_contact_form() {
    check_ajax_referer( 'fa_nonce', 'nonce' );
    $name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
    $email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
    $phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
    $subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

    if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
        wp_send_json_error( array( 'message' => fa_t( 'Compila tutti i campi obbligatori.' ) ) );
    }

    $to      = get_option( 'admin_email' );
    $headers = array( 'Content-Type: text/html; charset=UTF-8', 'Reply-To: ' . $email );
    $body    = sprintf(
        '<p><strong>Nome:</strong> %s</p><p><strong>Email:</strong> %s</p><p><strong>Telefono:</strong> %s</p><p><strong>Oggetto:</strong> %s</p><p><strong>Messaggio:</strong><br>%s</p>',
        esc_html( $name ), esc_html( $email ), esc_html( $phone ), esc_html( $subject ), nl2br( esc_html( $message ) )
    );

    $sent = wp_mail( $to, 'Contatto Formula Acqua: ' . $subject, $body, $headers );
    if ( $sent ) {
        wp_send_json_success( array( 'message' => fa_t( 'Messaggio inviato con successo!' ) ) );
    } else {
        wp_send_json_error( array( 'message' => fa_t( 'Errore nell\'invio. Riprova più tardi.' ) ) );
    }
}
add_action( 'wp_ajax_fa_contact', 'fa_contact_form' );
add_action( 'wp_ajax_nopriv_fa_contact', 'fa_contact_form' );

/* Body classes */
function fa_body_classes( $classes ) {
    if ( class_exists( 'WooCommerce' ) ) {
        $classes[] = 'fa-woo-active';
    }
    if ( is_front_page() ) {
        $classes[] = 'fa-home';
    }
    return $classes;
}
add_filter( 'body_class', 'fa_body_classes' );

/**
 * SEO: meta description dinamico per pagina.
 * Nessuna informazione inventata: riusa contenuti già presenti sul sito
 * (categorie WooCommerce, estratti prodotto/pagina) o testi coerenti con
 * quanto già pubblicato (spedizione rapida, assistenza specializzata).
 *
 * @return string
 */
function fa_get_meta_description() {
    if ( is_front_page() ) {
        return 'Formula Acqua: prodotti per piscine, accessori piscina e tutto il necessario per la manutenzione piscina. Robot, pompe, filtri e cloro con spedizione rapida in tutta Italia.';
    }

    if ( class_exists( 'WooCommerce' ) && function_exists( 'is_shop' ) && is_shop() ) {
        return 'Scopri tutti i prodotti per piscine di Formula Acqua: robot, pompe, filtri, cloro, accessori e coperture piscina in un unico shop online.';
    }

    if ( class_exists( 'WooCommerce' ) && function_exists( 'is_product_category' ) && is_product_category() ) {
        $term = get_queried_object();
        $slug = $term ? $term->slug : '';

        $category_descriptions = array(
            'pompe'         => "Pompe piscina e pompe per piscine efficienti e silenziose: scopri la selezione Formula Acqua per una filtrazione impeccabile.",
            'filtri'        => "Filtri piscina e cartucce di ricambio per un'acqua sempre limpida: la selezione Formula Acqua per la manutenzione piscina.",
            'robot-piscina' => "Robot piscina Formula Acqua: i migliori robot automatici per pulire in autonomia il fondo e le pareti della tua piscina.",
            'cloro'         => "Cloro piscina in pastiglie, granulare e liquido: mantieni l'acqua della piscina pulita e disinfettata con Formula Acqua.",
            'accessori'     => "Accessori piscina Formula Acqua: tutto il necessario per la manutenzione piscina, dagli aspirafanghi alle coperture.",
            'coperture'     => "Coperture piscina estive e invernali per proteggere la tua piscina dal freddo e dallo sporco: la selezione Formula Acqua.",
        );

        if ( isset( $category_descriptions[ $slug ] ) ) {
            return $category_descriptions[ $slug ];
        }

        if ( $term && ! empty( $term->description ) ) {
            $desc = wp_strip_all_tags( $term->description );
            return mb_strlen( $desc ) > 160 ? mb_substr( $desc, 0, 157 ) . '...' : $desc;
        }
        return 'Scopri la selezione di prodotti per piscine Formula Acqua in questa categoria: qualità, convenienza e spedizione rapida in tutta Italia.';
    }

    if ( is_singular( 'product' ) ) {
        $excerpt = get_the_excerpt();
        if ( $excerpt ) {
            $excerpt = wp_strip_all_tags( $excerpt );
            return mb_strlen( $excerpt ) > 160 ? mb_substr( $excerpt, 0, 157 ) . '...' : $excerpt;
        }
    }

    if ( is_singular() ) {
        $excerpt = get_the_excerpt();
        if ( $excerpt ) {
            $excerpt = wp_strip_all_tags( $excerpt );
            return mb_strlen( $excerpt ) > 160 ? mb_substr( $excerpt, 0, 157 ) . '...' : $excerpt;
        }
    }

    $tagline = get_bloginfo( 'description' );
    if ( $tagline ) {
        return $tagline;
    }

    return 'Formula Acqua: prodotti per piscine e accessori piscina per la manutenzione della tua piscina, con spedizione rapida in tutta Italia.';
}

/**
 * SEO: canonical corretto sugli archivi (home, shop, categorie prodotto),
 * dove il core di WordPress non ne stampa uno di default (rel_canonical()
 * del core agisce solo su is_singular()). Nessuna duplicazione: usciamo
 * subito se è una pagina singola, già gestita dal core.
 */
function fa_archive_canonical() {
    if ( is_singular() ) {
        return;
    }

    $url = '';

    if ( is_front_page() ) {
        $url = home_url( '/' );
    } elseif ( class_exists( 'WooCommerce' ) && function_exists( 'is_shop' ) && is_shop() ) {
        $shop_id = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'shop' ) : 0;
        $url     = $shop_id > 0 ? get_permalink( $shop_id ) : '';
    } elseif ( is_tax() || is_category() || is_tag() ) {
        $term = get_queried_object();
        if ( $term ) {
            $link = get_term_link( $term );
            if ( ! is_wp_error( $link ) ) {
                $url = $link;
            }
        }
    }

    if ( ! $url ) {
        return;
    }

    $paged = max( 1, (int) get_query_var( 'paged' ) );
    if ( $paged > 1 ) {
        $paged_url = get_pagenum_link( $paged, false );
        if ( $paged_url ) {
            $url = $paged_url;
        }
    }

    echo '<link rel="canonical" href="' . esc_url( $url ) . '" />' . "\n";
}
add_action( 'wp_head', 'fa_archive_canonical', 5 );

/**
 * SEO: noindex su pagine funzionali/personali che non devono apparire
 * su Google (Preferiti = wishlist personale, archivio autore = non
 * pertinente per un e-commerce senza blog). Usa il filtro core wp_robots
 * cosi WordPress unisce tutte le direttive in UN SOLO meta tag, senza
 * duplicazioni (stesso meccanismo già usato da WooCommerce per
 * carrello/account).
 *
 * @param array $robots Direttive robots già presenti.
 * @return array
 */
function fa_wp_robots_noindex_pages( $robots ) {
    if ( is_author() ) {
        $robots['noindex'] = true;
    } elseif ( function_exists( 'fa_is_wishlist_page' ) && fa_is_wishlist_page() ) {
        $robots['noindex'] = true;
    }
    return $robots;
}
add_filter( 'wp_robots', 'fa_wp_robots_noindex_pages' );

/**
 * SEO: rimuove l'archivio autori dalla sitemap XML nativa di WordPress.
 * Su un e-commerce senza blog l'autore non è un contenuto pertinente
 * da proporre ai motori di ricerca.
 *
 * @param bool|WP_Sitemaps_Provider $provider Provider registrato.
 * @param string                    $name     Nome del provider.
 * @return bool|WP_Sitemaps_Provider
 */
function fa_disable_users_sitemap( $provider, $name ) {
    return 'users' === $name ? false : $provider;
}
add_filter( 'wp_sitemaps_add_provider', 'fa_disable_users_sitemap', 10, 2 );

/* Schema.org JSON-LD */
function fa_schema_output() {
    if ( ! is_front_page() ) return;
    $schema = array(
        '@context' => 'https://schema.org',
        '@type'    => 'Store',
        'name'     => 'Formula Acqua',
        'description' => 'Tutto per la tua piscina - Prodotti e accessori per piscine',
        'url'      => home_url( '/' ),
        'telephone' => get_theme_mod( 'fa_phone', '+39 327 916 6457' ),
        'email'    => get_theme_mod( 'fa_email', 'formulacqua@ionospec.it' ),
    );

    // Sede legale e Partita IVA: aggiunte automaticamente allo schema solo
    // quando saranno compilate nel Customizer, per non pubblicare dati inventati.
    $legal_address = fa_get_legal_address();
    if ( $legal_address ) {
        $schema['address'] = array(
            '@type'          => 'PostalAddress',
            'streetAddress'  => $legal_address,
            'addressCountry' => 'IT',
        );
    }
    $piva = fa_get_piva();
    if ( $piva ) {
        $schema['vatID'] = $piva;
    }

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'fa_schema_output' );

/* Page templates */
function fa_page_templates( $templates ) {
    $templates['page-templates/template-contact.php'] = 'Contatti';
    $templates['page-templates/template-about.php']   = 'Chi Siamo';
    $templates['page-templates/template-faq.php']     = 'FAQ';
    return $templates;
}
add_filter( 'theme_page_templates', 'fa_page_templates' );

/**
 * Immagine fallback categoria (assets locali).
 *
 * @param string $icon Chiave icona/categoria.
 * @return string
 */
function fa_get_category_fallback_image( $icon ) {
    $file = FA_DIR . '/assets/images/categories/' . sanitize_file_name( $icon ) . '.jpg';
    if ( file_exists( $file ) ) {
        return FA_URI . '/assets/images/categories/' . sanitize_file_name( $icon ) . '.jpg';
    }
    $default = FA_DIR . '/assets/images/hero/crystal-water.jpg';
    return file_exists( $default ) ? FA_URI . '/assets/images/hero/crystal-water.jpg' : '';
}

/**
 * Mappa categorie navigazione e-commerce.
 *
 * @return array<int, array<string, mixed>>
 */
function fa_get_category_map() {
    return array(
        array(
            'label' => 'Robot automatici per la pulizia della piscina',
            'slugs' => array( 'robot-piscina', 'robot', 'robot-per-piscina', 'robots', 'robot-per-piscine' ),
            'desc'  => 'I migliori robot per mantenere la tua piscina sempre pulita e cristallina.',
            'icon'  => 'robot',
        ),
        array(
            'label' => 'Cloro',
            'slugs' => array( 'cloro', 'cloro-e-disinfezione', 'disinfezione', 'cloro-piscina' ),
            'desc'  => 'Cloro in pastiglie, granulare e liquido per acqua pura.',
            'icon'  => 'cloro',
        ),
        array(
            'label' => 'Controllo pH',
            'slugs' => array( 'ph', 'regolatori-ph', 'regolatore-ph', 'ph-minus', 'ph-plus', 'correttori-ph' ),
            'desc'  => 'Regolatori per mantenere il pH al livello ideale.',
            'icon'  => 'ph',
        ),
        array(
            'label' => 'Antialghe',
            'slugs' => array( 'antialghe', 'anti-alghe', 'alghicida', 'antialga' ),
            'desc'  => 'Trattamenti antialghe per un\'acqua sempre cristallina.',
            'icon'  => 'antialghe',
        ),
        array(
            'label' => 'Aspirafanghi',
            'slugs' => array( 'aspirafanghi', 'aspira-fanghi', 'aspirafango', 'aspiratori', 'pulizia-fondo' ),
            'desc'  => 'Aspirafanghi professionali per il fondo della piscina.',
            'icon'  => 'aspirafanghi',
        ),
        array(
            'label' => 'Pompe',
            'slugs' => array( 'pompe', 'pompa', 'pompe-piscina', 'pompe-di-filtrazione', 'pompa-piscina' ),
            'desc'  => 'Pompe di filtrazione efficienti e silenziose.',
            'icon'  => 'pompe',
        ),
        array(
            'label' => 'Filtri',
            'slugs' => array( 'filtri', 'filtro', 'filtri-piscina', 'filtro-a-sabbia', 'cartucce' ),
            'desc'  => 'Filtri e cartucce per un\'acqua sempre limpida.',
            'icon'  => 'filtri',
        ),
        array(
            'label' => 'Accessori',
            'slugs' => array( 'accessori', 'accessori-piscina', 'attrezzatura' ),
            'desc'  => 'Accessori essenziali per ogni tipo di piscina.',
            'icon'  => 'accessori',
        ),
        array(
            'label' => 'Ricambi',
            'slugs' => array( 'ricambi', 'parti-di-ricambio', 'ricambi-piscina' ),
            'desc'  => 'Ricambi originali e compatibili per i tuoi impianti.',
            'icon'  => 'ricambi',
        ),
        array(
            'label' => 'Coperture',
            'slugs' => array( 'coperture', 'copertura', 'teli', 'copertura-piscina', 'teloni' ),
            'desc'  => 'Coperture estive e invernali per proteggere la piscina.',
            'icon'  => 'coperture',
        ),
    );
}

/**
 * Resolve a single category item from the map.
 *
 * @param array<string, mixed> $item Category map item.
 * @return array<string, mixed>|null
 */
function fa_resolve_category_item( $item ) {
    $term = null;

    // 'lang' => '' forza la ricerca su tutte le lingue: gli slug/nomi nella
    // mappa sono quelli storici in italiano e devono essere trovati anche
    // quando la lingua corrente (Polylang) non è l'italiano, altrimenti la
    // query verrebbe filtrata sulla lingua attiva e non troverebbe nulla.
    foreach ( $item['slugs'] as $slug ) {
        $found = get_terms( array(
            'taxonomy'   => 'product_cat',
            'slug'       => $slug,
            'hide_empty' => false,
            'lang'       => '',
        ) );
        if ( ! empty( $found ) && ! is_wp_error( $found ) ) {
            $term = $found[0];
            break;
        }
    }

    if ( ! $term ) {
        $found = get_terms( array(
            'taxonomy'   => 'product_cat',
            'name'       => $item['label'],
            'hide_empty' => false,
            'lang'       => '',
        ) );
        if ( ! empty( $found ) && ! is_wp_error( $found ) ) {
            $term = $found[0];
        }
    }

    // Passa alla traduzione della lingua corrente, se disponibile.
    if ( $term && function_exists( 'pll_get_term' ) ) {
        $translated_id = pll_get_term( (int) $term->term_id );
        if ( $translated_id && (int) $translated_id !== (int) $term->term_id ) {
            $maybe = get_term( (int) $translated_id, 'product_cat' );
            if ( $maybe && ! is_wp_error( $maybe ) ) {
                $term = $maybe;
            }
        }
    }

    $shop_url = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
    $link     = $shop_url;
    $count    = 0;
    $image    = '';
    $slug     = $item['slugs'][0];
    $term_name = $item['label'];
    $id        = 0;
    $description = $item['desc'];

    if ( $term ) {
        $term_link = get_term_link( $term, 'product_cat' );
        if ( ! is_wp_error( $term_link ) ) {
            $link = $term_link;
        }
        $count     = (int) $term->count;
        $slug      = $term->slug;
        $term_name = $term->name;
        $id        = (int) $term->term_id;
        if ( $term->description ) {
            $description = wp_strip_all_tags( $term->description );
        }

        $thumbnail_id = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
        $image_url    = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'fa-category' ) : '';
        if ( ! $image_url && $thumbnail_id ) {
            $image_url = wp_get_attachment_image_url( $thumbnail_id, 'medium_large' );
        }
        $image = $image_url ? $image_url : '';
    }

    if ( ! $image ) {
        $image = fa_get_category_fallback_image( $item['icon'] );
    }

    return array(
        'id'          => $id,
        'name'        => $term_name,
        'term_name'   => $term_name,
        'slug'        => $slug,
        'description' => $description,
        'count'       => $count,
        'url'         => $link,
        'image'       => $image,
        'icon'        => $item['icon'],
        'exists'      => (bool) $term,
    );
}

/**
 * Categorie navigazione laterale (sempre le 10 richieste).
 *
 * @return array<int, array<string, mixed>>
 */
function fa_get_nav_categories() {
    if ( ! taxonomy_exists( 'product_cat' ) ) {
        $shop = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
        $out  = array();
        foreach ( fa_get_category_map() as $item ) {
            $out[] = array(
                'id'          => 0,
                'name'        => $item['label'],
                'term_name'   => $item['label'],
                'slug'        => $item['slugs'][0],
                'description' => $item['desc'],
                'count'       => 0,
                'url'         => $shop,
                'image'       => fa_get_category_fallback_image( $item['icon'] ),
                'icon'        => $item['icon'],
                'exists'      => false,
            );
        }
        return $out;
    }

    $categories = array();
    foreach ( fa_get_category_map() as $item ) {
        $categories[] = fa_resolve_category_item( $item );
    }
    return $categories;
}

/**
 * Categorie principali homepage collegate a WooCommerce.
 *
 * @return array<int, array<string, mixed>>
 */
function fa_get_homepage_categories() {
    $categories = array_values( array_filter(
        fa_get_nav_categories(),
        function( $cat ) {
            return ! empty( $cat['exists'] );
        }
    ) );

    if ( ! empty( $categories ) ) {
        return $categories;
    }

    if ( ! taxonomy_exists( 'product_cat' ) ) {
        return fa_get_nav_categories();
    }

    $terms = get_terms( array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'number'     => 10,
        'exclude'    => array( get_option( 'default_product_cat', 0 ) ),
        'orderby'    => 'count',
        'order'      => 'DESC',
    ) );

    $fallback = array();
    if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
        foreach ( $terms as $term ) {
            $link = get_term_link( $term, 'product_cat' );
            if ( is_wp_error( $link ) ) {
                continue;
            }
            $thumbnail_id = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
            $image_url    = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'fa-category' ) : '';

            $fallback[] = array(
                'id'          => (int) $term->term_id,
                'name'        => $term->name,
                'term_name'   => $term->name,
                'slug'        => $term->slug,
                'description' => $term->description ? wp_strip_all_tags( $term->description ) : 'Scopri i prodotti di questa categoria.',
                'count'       => (int) $term->count,
                'url'         => $link,
                'image'       => $image_url ? $image_url : '',
                'icon'        => 'default',
                'exists'      => true,
            );
        }
    }

    return ! empty( $fallback ) ? $fallback : fa_get_nav_categories();
}

/**
 * Stelle valutazione prodotto.
 *
 * @param WC_Product $product Product.
 * @return string
 */
function fa_get_product_rating_html( $product ) {
    if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
        return '';
    }

    $rating = (float) $product->get_average_rating();
    $count  = (int) $product->get_rating_count();

    if ( $rating <= 0 ) {
        $rating = 0;
    }

    $full  = (int) floor( $rating );
    $half  = ( $rating - $full ) >= 0.5 ? 1 : 0;
    $empty = 5 - $full - $half;

    ob_start();
    ?>
    <div class="fa-rating" aria-label="<?php echo esc_attr( sprintf( fa_t( 'Valutazione %s su 5' ), number_format_i18n( $rating, 1 ) ) ); ?>">
        <div class="fa-rating__stars" aria-hidden="true">
            <?php for ( $i = 0; $i < $full; $i++ ) : ?>
                <svg class="fa-star fa-star--full" width="14" height="14" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            <?php endfor; ?>
            <?php if ( $half ) : ?>
                <svg class="fa-star fa-star--half" width="14" height="14" viewBox="0 0 24 24"><defs><linearGradient id="fa-half-<?php echo esc_attr( (string) $product->get_id() ); ?>"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="transparent"/></linearGradient></defs><path fill="url(#fa-half-<?php echo esc_attr( (string) $product->get_id() ); ?>)" stroke="currentColor" stroke-width="1" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            <?php endif; ?>
            <?php for ( $i = 0; $i < $empty; $i++ ) : ?>
                <svg class="fa-star fa-star--empty" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            <?php endfor; ?>
        </div>
        <span class="fa-rating__count">(<?php echo esc_html( (string) max( $count, 0 ) ); ?>)</span>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * SVG icona categoria (fallback se manca l'immagine WooCommerce).
 *
 * @param string $icon Chiave icona.
 * @return string
 */
function fa_get_category_icon_svg( $icon ) {
    $svg_open = '<svg class="fa-cat-icon" width="32" height="32" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">';
    $svg_close = '</svg>';

    $icons = array(
        // Robot pulitore piscina: corpo, cingoli, sensori, maniglia e pelo dell'acqua.
        'robot' => $svg_open . '<rect x="6" y="21" width="5" height="13" rx="1.4"/><rect x="37" y="21" width="5" height="13" rx="1.4"/><path d="M8 24.2h1M8 27.4h1M8 30.6h1M39 24.2h1M39 27.4h1M39 30.6h1"/><rect x="12" y="18.5" width="24" height="16" rx="4.2"/><path d="M20 18.5v-4.2h8v4.2"/><circle cx="24" cy="12.2" r="1.35"/><circle cx="20.2" cy="26.4" r="2.15"/><circle cx="27.8" cy="26.4" r="2.15"/><path d="M18 31.6h12"/><path d="M9 40.5c2.4-1.7 4.8-1.7 7.2 0s4.8 1.7 7.2 0 4.8-1.7 7.2 0 4.8 1.7 7.2 0"/>' . $svg_close,
        // Chimici: tanica professionale con manico, livello liquido e goccia.
        'cloro' => $svg_open . '<path d="M16.5 8.5h9"/><path d="M18 8.5v4.2L13.2 18a3 3 0 00-.8 2v16.2A3.4 3.4 0 0015.8 39.6h16.4A3.4 3.4 0 0035.6 36.2V20a3 3 0 00-.8-2L30 12.7V8.5"/><path d="M19.5 8.5V6.8h9V8.5"/><path d="M13.8 27.5h20.4"/><path d="M16.6 21.2h4.2"/><path d="M39.2 22.5c2 2.4 2 4.4 0 6.8 2 2.4 2 4.3 0 6.6"/>' . $svg_close,
        // pH: flacone contagocce da laboratorio con scala.
        'ph' => $svg_open . '<path d="M21 6.5h6"/><path d="M22.2 6.5v5.4l-4.6 6.2a3.4 3.4 0 00-.8 2.1V37a3.6 3.6 0 003.6 3.6h7.2A3.6 3.6 0 0031.2 37V20.2a3.4 3.4 0 00-.8-2.1l-4.6-6.2V6.5"/><path d="M18.2 28.5h11.6"/><path d="M19.4 24.2h2.4M19.4 32.6h2.4"/><circle cx="36.4" cy="14.8" r="3.1"/><path d="M36.4 18v4.2"/>' . $svg_close,
        // Antialghe: flacone trattamento + ciuffo di alga.
        'antialghe' => $svg_open . '<rect x="10.5" y="14" width="13.5" height="24.5" rx="3.2"/><path d="M14 14V10.4h6.4V14"/><path d="M12.2 24.8h10.2"/><path d="M29.5 39.2c.4-6.4 3.1-10.2 7.6-12.4"/><path d="M29.8 39.2c1.2-7.8 5.6-11.4 10.7-8.6"/><path d="M29.6 39.2c-.2-5.2 1.8-9.8 5.5-13.8"/>' . $svg_close,
        // Aspirafanghi: testa triangolare a ruote e tubo corrugato.
        'aspirafanghi' => $svg_open . '<path d="M10.5 34.2L24 17.8 37.5 34.2z"/><path d="M16.2 31.6h15.6"/><circle cx="15.4" cy="36.6" r="2.1"/><circle cx="24" cy="36.6" r="2.1"/><circle cx="32.6" cy="36.6" r="2.1"/><path d="M24 17.8c0-3.2.4-6.4 2.4-8.2 2.2-2 6.2-1.4 8.8.2 2.4 1.5 3.8 2.8 7.2 2.6"/>' . $svg_close,
        // Pompe: motore alette + voluta e tubo di mandata.
        'pompe' => $svg_open . '<rect x="5" y="16.5" width="12.5" height="14.5" rx="2.2"/><path d="M8 20.2h6.4M8 23.8h6.4M8 27.4h6.4"/><rect x="16.8" y="21.4" width="4.2" height="4.6" rx="1"/><circle cx="31.2" cy="23.8" r="9.2"/><circle cx="31.2" cy="23.8" r="2.2"/><path d="M31.2 14.6V9.4h6.8"/><path d="M6 38.6h32.5"/><path d="M31.2 33V38.6"/>' . $svg_close,
        // Filtri: serbatoio a sabbia con calotta, manometro e valvola.
        'filtri' => $svg_open . '<path d="M14.5 18.5h19v16.8a6.4 6.4 0 01-6.4 6.4h-6.2a6.4 6.4 0 01-6.4-6.4z"/><path d="M16.2 18.5a7.8 7.8 0 0115.6 0"/><circle cx="24" cy="12.4" r="2.3"/><path d="M33.5 24.2h5.6v4.4h-5.6"/><path d="M17.8 28.6h12.4M17.8 32.8h12.4"/>' . $svg_close,
        // Accessori: retino skimmer con rete e manico telescopico.
        'accessori' => $svg_open . '<path d="M8.2 10.5h18.6l-2.8 14.2a5 5 0 01-4.9 4H16a5 5 0 01-4.9-4z"/><path d="M10.2 16.4h14.6M11.4 21.6h12.2M14.8 10.7l-1 17.8M19.6 10.7l-.8 17.8"/><path d="M26.4 10.8L41.2 6.4"/><path d="M38.6 5.2l3.2.8-.7 3.2"/>' . $svg_close,
        // Ricambi: ingranaggio e dado esagonale.
        'ricambi' => $svg_open . '<circle cx="20.2" cy="22.4" r="7.2"/><circle cx="20.2" cy="22.4" r="2.4"/><path d="M20.2 12.4v2.6M20.2 29.8v2.6M10.2 22.4h2.6M27.6 22.4h2.6M13.2 15.4l1.8 1.8M25.4 27.6l1.8 1.8M13.2 29.4l1.8-1.8M25.4 17.2l1.8-1.8"/><path d="M34.2 29.2l3.6-2.1 3.6 2.1v4.2l-3.6 2.1-3.6-2.1z"/><circle cx="37.8" cy="31.3" r="1.15"/>' . $svg_close,
        // Coperture: vasca in pianta e telo avvolto sul rullo.
        'coperture' => $svg_open . '<rect x="7.5" y="16.5" width="26" height="18" rx="3.2"/><path d="M11 21.6h19M11 26.4h19M11 31.2h19"/><rect x="31.2" y="13.2" width="9.2" height="24.6" rx="4.6"/><path d="M33.4 18.4h4.8M33.4 24.8h4.8M33.4 31.2h4.8"/>' . $svg_close,
        'default' => $svg_open . '<path d="M24 8.5c6.4 7.2 10.5 12 10.5 17.2A10.5 10.5 0 0124 36.2a10.5 10.5 0 01-10.5-10.5C13.5 20.5 17.6 15.7 24 8.5z"/><path d="M20.5 24.8h7"/>' . $svg_close,
    );

    return isset( $icons[ $icon ] ) ? $icons[ $icon ] : $icons['default'];
}

/**
 * Pannello brand condiviso per login, registrazione, recupero password.
 * Estratto in funzione per evitare markup duplicato tra i vari template auth.
 */
function fa_render_auth_aside() {
    $fa_auth_logo_svg = FA_DIR . '/assets/images/logo-fa-footer.svg';
    ?>
    <aside class="fa-auth__aside" aria-hidden="false">
        <span class="fa-auth__aside-glow" aria-hidden="true"></span>
        <span class="fa-auth__aside-ripple" aria-hidden="true"></span>
        <div class="fa-auth__aside-inner">
            <div class="fa-auth__aside-logo">
                <?php
                if ( file_exists( $fa_auth_logo_svg ) ) {
                    $fa_auth_svg = file_get_contents( $fa_auth_logo_svg ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
                    if ( false !== $fa_auth_svg ) {
                        $fa_auth_svg = preg_replace( '/<script\b[^>]*>.*?<\/script>/is', '', $fa_auth_svg );
                        $fa_auth_svg = preg_replace( '/\s(onload|onclick|onerror)=("|\')[^"\']*\2/i', '', $fa_auth_svg );
                        $fa_auth_svg = preg_replace( '/<svg\b/', '<svg class="fa-auth__aside-logo-img"', $fa_auth_svg, 1 );
                        echo $fa_auth_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    }
                }
                ?>
            </div>
            <h2 class="fa-auth__aside-title"><?php fa_te( 'Il tuo spazio Formula Acqua' ); ?></h2>
            <p class="fa-auth__aside-text"><?php fa_te( 'Ordini, preferiti e acquisti sempre sotto controllo.' ); ?></p>
            <ul class="fa-auth__aside-list">
                <li>
                    <span class="fa-auth__aside-icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                    </span>
                    <span><?php fa_te( 'Ordini sempre a portata' ); ?></span>
                </li>
                <li>
                    <span class="fa-auth__aside-icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                    </span>
                    <span><?php fa_te( 'Pagamento più veloce' ); ?></span>
                </li>
                <li>
                    <span class="fa-auth__aside-icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </span>
                    <span><?php fa_te( 'Preferiti sincronizzati' ); ?></span>
                </li>
            </ul>
        </div>
    </aside>
    <?php
}

/**
 * Categorie del menu hamburger, collegate agli archivi WooCommerce.
 *
 * @return array<int, array<string, mixed>>
 */
function fa_get_menu_categories() {
    $order = array(
        'Robot pulitori' => 'robot-piscina',
        'Cloro'          => 'cloro',
        'Controllo pH'   => 'ph',
        'Antialghe'      => 'antialghe',
        'Aspirafanghi'   => 'aspirafanghi',
        'Pompe'          => 'pompe',
        'Filtri'         => 'filtri',
        'Accessori'      => 'accessori',
        'Ricambi'        => 'ricambi',
        'Coperture'      => 'coperture',
    );

    $shop = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
    $out  = array();

    foreach ( $order as $label => $slug ) {
        $url  = $shop;
        $icon = ( 'Robot pulitori' === $label ) ? 'robot' : ( ( 'Controllo pH' === $label ) ? 'ph' : sanitize_title( $label ) );

        if ( taxonomy_exists( 'product_cat' ) ) {
            // 'lang' => '' bypassa il filtro lingua di Polylang: la ricerca per slug/nome
            // italiano deve funzionare anche quando si naviga in un'altra lingua.
            $found = get_terms(
                array(
                    'taxonomy'   => 'product_cat',
                    'slug'       => $slug,
                    'hide_empty' => false,
                    'lang'       => '',
                )
            );
            $term = ( ! empty( $found ) && ! is_wp_error( $found ) ) ? $found[0] : null;
            if ( ! $term ) {
                $found = get_terms(
                    array(
                        'taxonomy'   => 'product_cat',
                        'name'       => $label,
                        'hide_empty' => false,
                        'lang'       => '',
                    )
                );
                $term = ( ! empty( $found ) && ! is_wp_error( $found ) ) ? $found[0] : null;
            }
            if ( $term ) {
                if ( function_exists( 'pll_get_term' ) ) {
                    $translated_term = pll_get_term( (int) $term->term_id );
                    if ( $translated_term ) {
                        $maybe = get_term( (int) $translated_term, 'product_cat' );
                        if ( $maybe && ! is_wp_error( $maybe ) ) {
                            $term = $maybe;
                        }
                    }
                }
                $label = $term->name;
                $link  = get_term_link( $term, 'product_cat' );
                if ( ! is_wp_error( $link ) ) {
                    $url = $link;
                }
            }
        }

        $out[] = array(
            'name'  => $label,
            'slug'  => $slug,
            'url'   => $url,
            'icon'  => $icon,
            'image' => function_exists( 'fa_get_category_fallback_image' ) ? fa_get_category_fallback_image( $icon ) : '',
        );
    }

    return $out;
}

/**
 * Prodotti carosello hero: prima WooCommerce (on sale / featured), altrimenti demo.
 * Filtro `fa_hero_offer_products` per sostituire l’elenco.
 * Filtro `fa_hero_offers_use_woocommerce` (bool, default false) per usare WooCommerce.
 *
 * @return array<int, array<string, mixed>>
 */
function fa_get_hero_offer_products() {
    $custom = apply_filters( 'fa_hero_offer_products', null );
    if ( is_array( $custom ) && ! empty( $custom ) ) {
        return array_values( $custom );
    }

    $use_woo = (bool) apply_filters( 'fa_hero_offers_use_woocommerce', false );
    if ( $use_woo && class_exists( 'WooCommerce' ) ) {
        $from_woo = fa_get_hero_offer_products_from_woocommerce( 6 );
        if ( count( $from_woo ) >= 3 ) {
            return $from_woo;
        }
    }

    return fa_get_hero_offer_demo_products();
}

/**
 * Prodotti WooCommerce in offerta o in evidenza, mappati per il carosello hero.
 *
 * @param int $limit Numero massimo.
 * @return array<int, array<string, mixed>>
 */
function fa_get_hero_offer_products_from_woocommerce( $limit = 6 ) {
    if ( ! function_exists( 'wc_get_products' ) ) {
        return array();
    }

    $limit = max( 3, (int) $limit );
    $query = array(
        'status'  => 'publish',
        'limit'   => $limit,
        'orderby' => 'date',
        'order'   => 'DESC',
        'return'  => 'objects',
    );

    $sale_ids = function_exists( 'wc_get_product_ids_on_sale' ) ? wc_get_product_ids_on_sale() : array();
    if ( ! empty( $sale_ids ) ) {
        $query['include'] = array_map( 'intval', $sale_ids );
    } else {
        $query['featured'] = true;
    }

    $products = wc_get_products( $query );
    if ( empty( $products ) ) {
        unset( $query['include'], $query['featured'] );
        $products = wc_get_products( $query );
    }

    $out = array();
    foreach ( $products as $product ) {
        if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
            continue;
        }
        $mapped = fa_map_hero_offer_from_product( $product );
        if ( $mapped ) {
            $out[] = $mapped;
        }
    }
    return $out;
}

/**
 * @param WC_Product $product Product.
 * @return array<string, mixed>|null
 */
function fa_map_hero_offer_from_product( $product ) {
    $image_id = (int) $product->get_image_id();
    $image    = $image_id ? wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' ) : '';
    if ( ! $image ) {
        $image = wc_placeholder_img_src( 'woocommerce_thumbnail' );
    }

    $regular = (float) $product->get_regular_price();
    $sale    = $product->is_on_sale() ? (float) $product->get_sale_price() : 0;
    if ( $regular <= 0 ) {
        $regular = (float) $product->get_price();
    }
    if ( $sale <= 0 ) {
        $sale = $regular > 0 ? round( $regular * 0.85, 2 ) : 0;
        $regular = $regular > 0 ? $regular : $sale;
    }

    return array(
        'id'            => (int) $product->get_id(),
        'name'          => $product->get_name(),
        'url'           => $product->get_permalink(),
        'image'         => $image,
        'regular_price' => $regular,
        'sale_price'    => $sale,
        'source'        => 'woocommerce',
    );
}

/**
 * Demo facilmente sostituibili (niente dipendenza da SKU reali).
 *
 * @return array<int, array<string, mixed>>
 */
function fa_get_hero_offer_demo_products() {
    $shop = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
    $by_icon = array();
    if ( function_exists( 'fa_get_nav_categories' ) ) {
        foreach ( fa_get_nav_categories() as $cat ) {
            if ( empty( $cat['icon'] ) ) {
                continue;
            }
            $by_icon[ $cat['icon'] ] = $cat;
        }
    }

    $defs = array(
        array(
            'name'    => 'Robot Pulitore AquaClean X100',
            'icon'    => 'robot',
            'regular' => 429.90,
            'sale'    => 349.90,
        ),
        array(
            'name'    => 'Cloro in pastiglie 5 kg',
            'icon'    => 'cloro',
            'regular' => 39.90,
            'sale'    => 29.90,
        ),
        array(
            'name'    => 'Correttore Controllo pH Minus',
            'icon'    => 'ph',
            'regular' => 24.90,
            'sale'    => 18.90,
        ),
        array(
            'name'    => 'Pompa di filtrazione 8 m³/h',
            'icon'    => 'pompe',
            'regular' => 219.00,
            'sale'    => 179.00,
        ),
        array(
            'name'    => 'Filtro a sabbia Ø 500',
            'icon'    => 'filtri',
            'regular' => 259.00,
            'sale'    => 199.00,
        ),
        array(
            'name'    => 'Kit retino e asta telescopica',
            'icon'    => 'accessori',
            'regular' => 49.90,
            'sale'    => 34.90,
        ),
    );

    $out = array();
    foreach ( $defs as $i => $def ) {
        $cat  = isset( $by_icon[ $def['icon'] ] ) ? $by_icon[ $def['icon'] ] : null;
        $url  = ( $cat && ! empty( $cat['url'] ) ) ? $cat['url'] : $shop;
        $img  = ( $cat && ! empty( $cat['image'] ) ) ? $cat['image'] : '';
        $out[] = array(
            'id'            => 0 - ( $i + 1 ),
            'name'          => $def['name'],
            'url'           => $url,
            'image'         => $img,
            'regular_price' => $def['regular'],
            'sale_price'    => $def['sale'],
            'source'        => 'demo',
        );
    }
    return $out;
}

/**
 * Prezzo HTML per le card hero.
 *
 * @param float $amount Amount.
 * @return string
 */
function fa_format_hero_offer_price( $amount ) {
    $amount = (float) $amount;
    if ( function_exists( 'wc_price' ) ) {
        return wc_price( $amount );
    }
    return '€ ' . number_format( $amount, 2, ',', '.' );
}

/**
 * Prodotti in evidenza: se non ci sono featured, usa i più recenti.
 *
 * @return string Shortcode WooCommerce.
 */
function fa_get_featured_products_shortcode() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return '';
    }

    $featured = wc_get_products( array(
        'status'   => 'publish',
        'limit'    => 1,
        'featured' => true,
        'return'   => 'ids',
    ) );

    if ( ! empty( $featured ) ) {
        return '[products limit="8" columns="4" visibility="featured" orderby="date" order="DESC"]';
    }

    return '[products limit="8" columns="4" orderby="date" order="DESC"]';
}

/**
 * Crea (se mancanti) le categorie prodotto Formula Acqua.
 * Idempotente: non duplica slug già esistenti.
 *
 * @return void
 */
function fa_ensure_product_categories() {
    if ( ! taxonomy_exists( 'product_cat' ) ) {
        return;
    }

    foreach ( fa_get_category_map() as $item ) {
        $slug  = $item['slugs'][0];
        $found = get_terms(
            array(
                'taxonomy'   => 'product_cat',
                'slug'       => $slug,
                'hide_empty' => false,
                'lang'       => '',
            )
        );
        $term = ( ! empty( $found ) && ! is_wp_error( $found ) ) ? $found[0] : false;

        // Aggiorna nome/descrizione se la categoria esiste già.
        if ( $term && ! is_wp_error( $term ) ) {
            $needs_update = ( $term->name !== $item['label'] ) || ( $term->description !== $item['desc'] );
            if ( $needs_update ) {
                wp_update_term(
                    (int) $term->term_id,
                    'product_cat',
                    array(
                        'name'        => $item['label'],
                        'description' => $item['desc'],
                        'slug'        => $slug,
                    )
                );
            }
            continue;
        }

        if ( term_exists( $item['label'], 'product_cat' ) ) {
            continue;
        }

        $result = wp_insert_term(
            $item['label'],
            'product_cat',
            array(
                'slug'        => $slug,
                'description' => $item['desc'],
            )
        );

        if ( is_wp_error( $result ) || empty( $result['term_id'] ) ) {
            continue;
        }

        $fallback = fa_get_category_fallback_image( $item['icon'] );
        if ( ! $fallback ) {
            continue;
        }

        $path = str_replace( FA_URI, FA_DIR, $fallback );
        if ( ! file_exists( $path ) ) {
            continue;
        }

        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $tmp = wp_tempnam( basename( $path ) );
        if ( ! $tmp || ! copy( $path, $tmp ) ) {
            continue;
        }

        $attachment_id = media_handle_sideload(
            array(
                'name'     => basename( $path ),
                'tmp_name' => $tmp,
            ),
            0,
            $item['label']
        );

        if ( ! is_wp_error( $attachment_id ) ) {
            update_term_meta( (int) $result['term_id'], 'thumbnail_id', (int) $attachment_id );
        }
    }

    update_option( 'fa_product_categories_ready', FA_VERSION, false );
}
add_action( 'init', function() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    if ( get_option( 'fa_product_categories_ready' ) === FA_VERSION ) {
        return;
    }
    fa_ensure_product_categories();
}, 30 );
