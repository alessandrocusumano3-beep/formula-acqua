<?php
/**
 * Template Name: FAQ
 *
 * @package Formula_Acqua
 */

get_header();
?>

<div class="fa-page-header">
    <div class="fa-container">
        <h1><?php fa_te( 'Domande frequenti' ); ?></h1>
        <p><?php fa_te( 'Trova le risposte alle domande più comuni' ); ?></p>
    </div>
</div>

<div class="fa-container fa-content-area">
    <div class="fa-faq-page" style="max-width:800px;margin:0 auto;">
        <?php
        $groups = array(
            'Ordini e spedizioni' => array(
                array( 'Quali sono i tempi di consegna?', 'Le spedizioni vengono effettuate con corriere espresso. I tempi di consegna sono di 24/48 ore lavorative per le principali città e 48/72 ore per isole e zone remote.' ),
                array( 'Quanto costa la spedizione?', 'La spedizione standard costa 6,90€. Per ordini superiori a 79€ la spedizione è gratuita in tutta Italia.' ),
                array( 'Posso tracciare il mio ordine?', 'Sì, riceverai un\'email con il codice di tracciamento per seguire la consegna in tempo reale.' ),
            ),
            'Pagamenti' => array(
                array( 'Quali metodi di pagamento accettate?', 'Accettiamo carta di credito (Visa, Mastercard), PayPal, bonifico bancario e contrassegno con supplemento di 4€.' ),
                array( 'I pagamenti sono sicuri?', 'Tutte le transazioni sono protette da crittografia SSL a 256 bit. I dati della carta non vengono memorizzati.' ),
            ),
            'Resi e rimborsi' => array(
                array( 'Posso effettuare un reso?', 'Hai 14 giorni dalla ricezione per effettuare il reso. Il prodotto deve essere integro e nella confezione originale.' ),
                array( 'Come riceverò il rimborso?', 'Il rimborso avviene sullo stesso metodo di pagamento utilizzato, entro 14 giorni dalla ricezione del reso.' ),
            ),
            'Prodotti' => array(
                array( 'Come scelgo il robot piscina giusto?', 'La scelta dipende dalla dimensione della piscina, dal tipo di fondo e dalle funzioni desiderate. Contattaci per una consulenza gratuita.' ),
                array( 'I prodotti chimici sono pericolosi?', 'Sono sicuri se utilizzati seguendo le istruzioni. Si raccomanda di conservarli lontano dalla portata dei bambini.' ),
            ),
        );

        foreach ( $groups as $title => $faqs ) :
        ?>
        <div class="fa-faq-group">
            <h2><?php echo esc_html( fa_t( $title ) ); ?></h2>
            <div class="fa-faq-list">
                <?php foreach ( $faqs as $faq ) : ?>
                <div class="fa-faq-item">
                    <button class="fa-faq-item__q" aria-expanded="false">
                        <span><?php echo esc_html( fa_t( $faq[0] ) ); ?></span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                    </button>
                    <div class="fa-faq-item__a"><p><?php echo esc_html( fa_t( $faq[1] ) ); ?></p></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="fa-faq-cta">
            <h2><?php fa_te( 'Non hai trovato la risposta?' ); ?></h2>
            <p><?php fa_te( 'Contattaci direttamente, saremo felici di aiutarti.' ); ?></p>
            <a href="<?php echo esc_url( function_exists( 'fa_get_legal_page_url' ) ? fa_get_legal_page_url( 'contatti' ) : home_url( '/contatti/' ) ); ?>" class="fa-btn fa-btn--primary"><?php fa_te( 'Contattaci' ); ?></a>
        </div>

        <?php the_content(); ?>
    </div>
</div>

<?php get_footer(); ?>
