<?php
/**
 * Template Name: Chi Siamo
 *
 * @package Formula_Acqua
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$shop_url = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );

get_header();
?>

<article class="fa-about">
	<header class="fa-about-hero">
		<div class="fa-about-hero__media" aria-hidden="true">
			<div class="fa-about-hero__wash"></div>
			<svg class="fa-about-hero__wave" viewBox="0 0 1440 56" preserveAspectRatio="none">
				<path d="M0,24 C320,56 640,0 960,24 C1200,40 1320,36 1440,24 L1440,56 L0,56 Z" fill="#ffffff"/>
			</svg>
		</div>
		<div class="fa-container fa-about-hero__inner">
			<p class="fa-about-hero__label"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Chi siamo' ) : 'Chi siamo' ); ?></p>
			<h1 class="fa-about-hero__title"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Formula Acqua: prodotti e soluzioni per la cura della piscina' ) : 'Formula Acqua: prodotti e soluzioni per la cura della piscina' ); ?></h1>
			<div class="fa-about-hero__lead">
				<p><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Formula Acqua nasce con un obiettivo preciso: rendere più semplice trovare online tutto quello che serve per mantenere la piscina pulita, efficiente e pronta da utilizzare.' ) : 'Formula Acqua nasce con un obiettivo preciso: rendere più semplice trovare online tutto quello che serve per mantenere la piscina pulita, efficiente e pronta da utilizzare.' ); ?></p>
				<p><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Siamo una realtà italiana specializzata nel mondo della piscina e della manutenzione. Nel nostro shop proponiamo prodotti per il trattamento dell’acqua, strumenti per la pulizia, ricambi e accessori piscina, cercando di offrire una scelta chiara e adatta alle diverse necessità.' ) : 'Siamo una realtà italiana specializzata nel mondo della piscina e della manutenzione. Nel nostro shop proponiamo prodotti per il trattamento dell’acqua, strumenti per la pulizia, ricambi e accessori piscina, cercando di offrire una scelta chiara e adatta alle diverse necessità.' ); ?></p>
			</div>
		</div>
	</header>

	<section class="fa-about-section fa-about-section--why" aria-labelledby="fa-about-why-title">
		<div class="fa-container fa-about-section__inner">
			<div class="fa-about-section__copy" data-fa-animate="fade-up">
				<h2 id="fa-about-why-title" class="fa-about-section__title"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Perché nasce Formula Acqua' ) : 'Perché nasce Formula Acqua' ); ?></h2>
				<p><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Gestire una piscina significa prestare attenzione a diversi aspetti: la qualità dell’acqua, il trattamento chimico, l’analisi dei valori, la pulizia dell’impianto e la manutenzione delle attrezzature.' ) : 'Gestire una piscina significa prestare attenzione a diversi aspetti: la qualità dell’acqua, il trattamento chimico, l’analisi dei valori, la pulizia dell’impianto e la manutenzione delle attrezzature.' ); ?></p>
				<p><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Formula Acqua nasce per aiutarti a gestire queste attività in modo più pratico, mettendo a disposizione prodotti utili per la cura della piscina durante tutto l’anno.' ) : 'Formula Acqua nasce per aiutarti a gestire queste attività in modo più pratico, mettendo a disposizione prodotti utili per la cura della piscina durante tutto l’anno.' ); ?></p>
			</div>
			<ul class="fa-about-concepts" data-fa-animate="fade-up">
				<li>
					<span class="fa-about-concepts__icon" aria-hidden="true">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3c0 0-6 7.2-6 11.2A6 6 0 0012 21a6 6 0 006-6.8C18 10.2 12 3 12 3z"/></svg>
					</span>
					<span><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Qualità dell’acqua' ) : 'Qualità dell’acqua' ); ?></span>
				</li>
				<li>
					<span class="fa-about-concepts__icon" aria-hidden="true">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 3h6"/><path d="M10 3v2.2c0 1.5-.8 2.9-2.1 3.7A6 6 0 0012 21a6 6 0 004.1-12.1A4.3 4.3 0 0114 5.2V3"/></svg>
					</span>
					<span><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Trattamento' ) : 'Trattamento' ); ?></span>
				</li>
				<li>
					<span class="fa-about-concepts__icon" aria-hidden="true">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M12 3v2M12 19v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M3 12h2M19 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
					</span>
					<span><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Pulizia' ) : 'Pulizia' ); ?></span>
				</li>
				<li>
					<span class="fa-about-concepts__icon" aria-hidden="true">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.8-3.8a6 6 0 01-8.5 8.5l-8.8 8.8-4.2-4.2 8.8-8.8a6 6 0 018.5-8.5z"/></svg>
					</span>
					<span><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Manutenzione' ) : 'Manutenzione' ); ?></span>
				</li>
			</ul>
			<p class="fa-about-section__closing" data-fa-animate="fade-up"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Nel nostro catalogo trovi una selezione in continua crescita, pensata per accompagnarti nella manutenzione ordinaria e nella gestione delle esigenze più comuni.' ) : 'Nel nostro catalogo trovi una selezione in continua crescita, pensata per accompagnarti nella manutenzione ordinaria e nella gestione delle esigenze più comuni.' ); ?></p>
		</div>
	</section>

	<section class="fa-about-section fa-about-section--shop" aria-labelledby="fa-about-shop-title">
		<div class="fa-container fa-about-section__inner fa-about-section__inner--narrow">
			<h2 id="fa-about-shop-title" class="fa-about-section__title" data-fa-animate="fade-up"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Un e-commerce pensato per chi ha una piscina' ) : 'Un e-commerce pensato per chi ha una piscina' ); ?></h2>
			<p class="fa-about-highlight" data-fa-animate="fade-up"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Acquistare un prodotto non significa soltanto riceverlo a casa.' ) : 'Acquistare un prodotto non significa soltanto riceverlo a casa.' ); ?></p>
			<div class="fa-about-section__copy" data-fa-animate="fade-up">
				<p><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Per questo cerchiamo di rendere ogni fase più semplice: dalla ricerca dell’articolo giusto fino all’assistenza dopo l’acquisto. L’obiettivo è offrire informazioni chiare e un servizio concreto, senza complicare la scelta.' ) : 'Per questo cerchiamo di rendere ogni fase più semplice: dalla ricerca dell’articolo giusto fino all’assistenza dopo l’acquisto. L’obiettivo è offrire informazioni chiare e un servizio concreto, senza complicare la scelta.' ); ?></p>
				<p><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Che si tratti di una piscina fuori terra, di una piscina interrata o di una semplice esigenza legata alla qualità dell’acqua, puoi trovare soluzioni per il trattamento, la pulizia e la manutenzione.' ) : 'Che si tratti di una piscina fuori terra, di una piscina interrata o di una semplice esigenza legata alla qualità dell’acqua, puoi trovare soluzioni per il trattamento, la pulizia e la manutenzione.' ); ?></p>
			</div>
		</div>
	</section>

	<section class="fa-about-section fa-about-section--path" aria-labelledby="fa-about-path-title">
		<div class="fa-container fa-about-section__inner fa-about-section__inner--narrow">
			<h2 id="fa-about-path-title" class="fa-about-section__title" data-fa-animate="fade-up"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Il nostro percorso' ) : 'Il nostro percorso' ); ?></h2>
			<p class="fa-about-path__text" data-fa-animate="fade-up"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Il nostro percorso è iniziato da poco, ma l’obiettivo è costruire nel tempo un punto di riferimento affidabile per la manutenzione, il trattamento dell’acqua e la cura della piscina.' ) : 'Il nostro percorso è iniziato da poco, ma l’obiettivo è costruire nel tempo un punto di riferimento affidabile per la manutenzione, il trattamento dell’acqua e la cura della piscina.' ); ?></p>
		</div>
	</section>

	<section class="fa-about-cta" aria-labelledby="fa-about-cta-title">
		<div class="fa-container fa-about-cta__inner" data-fa-animate="fade-up">
			<p class="fa-about-cta__brand">FormulAcqua</p>
			<h2 id="fa-about-cta-title" class="fa-about-cta__title"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Tutto il necessario per prenderti cura della tua piscina.' ) : 'Tutto il necessario per prenderti cura della tua piscina.' ); ?></h2>
			<p class="fa-about-cta__text"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Scopri il catalogo e trova prodotti, ricambi e accessori per la gestione della piscina in ogni stagione.' ) : 'Scopri il catalogo e trova prodotti, ricambi e accessori per la gestione della piscina in ogni stagione.' ); ?></p>
			<a class="fa-btn fa-btn--primary fa-btn--lg" href="<?php echo esc_url( $shop_url ); ?>"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Scopri il catalogo' ) : 'Scopri il catalogo' ); ?></a>
		</div>
	</section>
</article>

<?php
get_footer();
