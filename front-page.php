<?php
/**
 * Homepage Shop - Formula Acqua
 *
 * @package Formula_Acqua
 */

get_header();

$shop_url = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
$cats     = function_exists( 'fa_get_nav_categories' ) ? fa_get_nav_categories() : array();

/*
 * Prefer /videos/hero.mp4 in web root (Local: app/public/videos).
 * Use root-relative URLs (not home_url) so Live Link / localsite.io
 * loads the file from the same host as the page — absolute *.local
 * URLs are unreachable from an iPhone over the tunnel.
 */
$public_video_path = trailingslashit( ABSPATH ) . 'videos/hero.mp4';
$theme_video_path  = FA_DIR . '/assets/videos/hero.mp4';
if ( file_exists( $public_video_path ) ) {
	$video_path = $public_video_path;
	$video_url  = '/videos/hero.mp4';
} elseif ( file_exists( $theme_video_path ) ) {
	$video_path = $theme_video_path;
	$theme_path = wp_parse_url( FA_URI . '/assets/videos/hero.mp4', PHP_URL_PATH );
	$video_url  = $theme_path ? $theme_path : ( FA_URI . '/assets/videos/hero.mp4' );
} else {
	$video_path = '';
	$video_url  = '';
}
if ( $video_path && $video_url ) {
	$video_url .= '?v=' . rawurlencode( (string) filemtime( $video_path ) );
}

/*
 * Variante mobile più leggera (stesso loop, stessa inquadratura, risoluzione
 * e bitrate ridotti) per reti cellulari più lente: aiuta l'autoplay a
 * partire prima anche su iPhone. Facoltativa: se il file non esiste si
 * ricade semplicemente sul video desktop, senza errori.
 */
$public_video_mobile_path = trailingslashit( ABSPATH ) . 'videos/hero-mobile.mp4';
$theme_video_mobile_path  = FA_DIR . '/assets/videos/hero-mobile.mp4';
if ( file_exists( $public_video_mobile_path ) ) {
	$video_mobile_path = $public_video_mobile_path;
	$video_mobile_url  = '/videos/hero-mobile.mp4';
} elseif ( file_exists( $theme_video_mobile_path ) ) {
	$video_mobile_path = $theme_video_mobile_path;
	$theme_mobile_rel  = wp_parse_url( FA_URI . '/assets/videos/hero-mobile.mp4', PHP_URL_PATH );
	$video_mobile_url  = $theme_mobile_rel ? $theme_mobile_rel : ( FA_URI . '/assets/videos/hero-mobile.mp4' );
} else {
	$video_mobile_path = '';
	$video_mobile_url  = '';
}
if ( $video_mobile_path && $video_mobile_url ) {
	$video_mobile_url .= '?v=' . rawurlencode( (string) filemtime( $video_mobile_path ) );
}

$poster_path = FA_DIR . '/assets/images/hero-poster.jpg';
$poster_url  = '';
if ( file_exists( $poster_path ) ) {
	$poster_rel = wp_parse_url( FA_URI . '/assets/images/hero-poster.jpg', PHP_URL_PATH );
	$poster_url = ( $poster_rel ? $poster_rel : ( FA_URI . '/assets/images/hero-poster.jpg' ) )
		. '?v=' . rawurlencode( (string) filemtime( $poster_path ) );
}

$mobile_quick_defs = array(
	array( 'label' => fa_t( 'Robot' ), 'icon' => 'robot' ),
	array( 'label' => fa_t( 'Chimici' ), 'icon' => 'cloro' ),
	array( 'label' => fa_t( 'Pompe' ), 'icon' => 'pompe' ),
	array( 'label' => fa_t( 'Accessori' ), 'icon' => 'accessori' ),
	array( 'label' => fa_t( 'Ricambi' ), 'icon' => 'ricambi' ),
);
$cats_by_icon = array();
foreach ( $cats as $cat_item ) {
	if ( ! empty( $cat_item['icon'] ) ) {
		$cats_by_icon[ $cat_item['icon'] ] = $cat_item;
	}
}
?>

<section class="fa-hero fa-hero--video" id="fa-hero">
	<div class="fa-hero__media" aria-hidden="true">
		<?php if ( $video_path && $video_url ) : ?>
			<video
				id="fa-hero-video"
				class="fa-hero__video"
				autoplay
				muted
				loop
				playsinline
				webkit-playsinline
				preload="auto"
				disablepictureinpicture
				controlslist="nodownload nofullscreen noremoteplayback"
			>
				<?php if ( $video_mobile_url ) : ?>
					<source src="<?php echo esc_url( $video_mobile_url ); ?>" type="video/mp4" media="(max-width: 767px)">
				<?php endif; ?>
				<source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4">
			</video>
			<script>
			(function () {
				var v = document.getElementById('fa-hero-video');
				var hero = document.getElementById('fa-hero');
				if (!v || !hero) return;
				/*
				 * Do NOT add fa-hero--playing before play() succeeds.
				 * Fading the poster while the video is still paused exposes
				 * Safari iOS native Play chrome. Poster stays opaque until
				 * playback actually starts (same visual path as Mac).
				 *
				 * iOS Safari can silently refuse the very first play() call
				 * (source not buffered yet, Low Power Mode, page restored
				 * from the back/forward cache, app coming back to the
				 * foreground...). None of those block a play() issued from
				 * inside a real event handler, so we keep retrying on every
				 * signal that playback might now be possible, and stop as
				 * soon as it actually starts. No controls/UI are ever shown:
				 * this only calls the native play(), it never reveals them.
				 */
				v.muted = true;
				v.defaultMuted = true;
				v.setAttribute('muted', '');
				v.playsInline = true;
				v.setAttribute('playsinline', '');
				v.setAttribute('webkit-playsinline', '');

				var started = false;

				function markPlaying() {
					if (started) return;
					started = true;
					hero.classList.add('fa-hero--playing');
					teardown();
				}

				function attemptPlay() {
					if (started) return;
					var p = v.play();
					if (p && typeof p.then === 'function') {
						p.then(function () {
							if (!v.paused) markPlaying();
						}).catch(function () {
							/* retried on the next available signal below */
						});
					} else if (!v.paused) {
						markPlaying();
					}
				}

				function onGesture() {
					attemptPlay();
				}

				function onVisible() {
					if (document.visibilityState === 'visible') attemptPlay();
				}

				function teardown() {
					document.removeEventListener('touchstart', onGesture);
					document.removeEventListener('pointerdown', onGesture);
					document.removeEventListener('scroll', onGesture);
					document.removeEventListener('visibilitychange', onVisible);
					window.removeEventListener('pageshow', attemptPlay);
					v.removeEventListener('canplay', attemptPlay);
					v.removeEventListener('loadeddata', attemptPlay);
					v.removeEventListener('playing', markPlaying);
				}

				v.addEventListener('playing', markPlaying);
				v.addEventListener('canplay', attemptPlay);
				v.addEventListener('loadeddata', attemptPlay);
				document.addEventListener('touchstart', onGesture, { passive: true });
				document.addEventListener('pointerdown', onGesture, { passive: true });
				document.addEventListener('scroll', onGesture, { passive: true });
				document.addEventListener('visibilitychange', onVisible);
				/* iOS Safari restores the page from bfcache paused after "back" */
				window.addEventListener('pageshow', attemptPlay);

				attemptPlay();
			})();
			</script>
		<?php endif; ?>
		<?php if ( $poster_url ) : ?>
			<img class="fa-hero__poster" src="<?php echo esc_url( $poster_url ); ?>" alt="" width="1600" height="1067" decoding="async">
		<?php endif; ?>
		<div class="fa-hero__overlay"></div>
	</div>

	<div class="fa-container fa-hero__layout">
		<div class="fa-hero__content">
			<p class="fa-hero__brand" aria-label="FormulAcqua"><span class="fa-hero__brand-formul">Formul</span><span class="fa-hero__brand-acqua">Acqua</span></p>
			<h1 class="fa-hero__title"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Tutto per la tua piscina' ) : 'Tutto per la tua piscina' ); ?></h1>
			<p class="fa-hero__desc">
				<span class="fa-hero__desc-full"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Robot, chimici, pompe e accessori: lo shop specializzato, con spedizioni rapide in tutta Italia.' ) : 'Robot, chimici, pompe e accessori: lo shop specializzato, con spedizioni rapide in tutta Italia.' ); ?></span>
				<span class="fa-hero__desc-short"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Robot, chimici e accessori. Spedizioni rapide in Italia.' ) : 'Robot, chimici e accessori. Spedizioni rapide in Italia.' ); ?></span>
			</p>
			<div class="fa-hero__actions">
				<a href="<?php echo esc_url( $shop_url ); ?>" class="fa-btn fa-btn--primary fa-btn--xl fa-hero__cta-primary">
					<?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Acquista ora' ) : 'Acquista ora' ); ?>
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
				</a>
				<button type="button" class="fa-btn fa-btn--glass fa-btn--xl fa-hero__cta-secondary" id="fa-hero-cats"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Sfoglia categorie' ) : 'Sfoglia categorie' ); ?></button>
			</div>
		</div>

		<?php
		$fa_hero_offers = function_exists( 'fa_get_hero_offer_products' ) ? fa_get_hero_offer_products() : array();
		if ( ! empty( $fa_hero_offers ) ) :
			$fa_offer_label = function_exists( 'fa_t' ) ? fa_t( 'Offerta' ) : 'Offerta';
			?>
		<aside class="fa-hero-offers" data-fa-hero-offers aria-label="<?php echo esc_attr( function_exists( 'fa_t' ) ? fa_t( 'Offerte in evidenza' ) : 'Offerte in evidenza' ); ?>">
			<div class="fa-hero-offers__frame">
				<button type="button" class="fa-hero-offers__nav fa-hero-offers__nav--prev" data-fa-offers-prev aria-label="<?php echo esc_attr( function_exists( 'fa_t' ) ? fa_t( 'Prodotto precedente' ) : 'Prodotto precedente' ); ?>">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M15 6l-6 6 6 6"/></svg>
				</button>
				<div class="fa-hero-offers__viewport">
					<div class="fa-hero-offers__track">
						<?php foreach ( $fa_hero_offers as $oi => $offer ) :
							$regular_html = function_exists( 'fa_format_hero_offer_price' ) ? fa_format_hero_offer_price( $offer['regular_price'] ) : '';
							$sale_html    = function_exists( 'fa_format_hero_offer_price' ) ? fa_format_hero_offer_price( $offer['sale_price'] ) : '';
							?>
							<a href="<?php echo esc_url( $offer['url'] ); ?>" class="fa-hero-offer" data-index="<?php echo esc_attr( (string) $oi ); ?>">
								<span class="fa-hero-offer__badge"><?php echo esc_html( $fa_offer_label ); ?></span>
								<span class="fa-hero-offer__media">
									<?php if ( ! empty( $offer['image'] ) ) : ?>
										<img src="<?php echo esc_url( $offer['image'] ); ?>" alt="<?php echo esc_attr( $offer['name'] ); ?>" width="240" height="180" decoding="async">
									<?php else : ?>
										<span class="fa-hero-offer__ph" aria-hidden="true"></span>
									<?php endif; ?>
								</span>
								<span class="fa-hero-offer__body">
									<span class="fa-hero-offer__name"><?php echo esc_html( $offer['name'] ); ?></span>
									<span class="fa-hero-offer__prices">
										<span class="fa-hero-offer__regular"><?php echo wp_kses_post( $regular_html ); ?></span>
										<span class="fa-hero-offer__sale"><?php echo wp_kses_post( $sale_html ); ?></span>
									</span>
								</span>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
				<button type="button" class="fa-hero-offers__nav fa-hero-offers__nav--next" data-fa-offers-next aria-label="<?php echo esc_attr( function_exists( 'fa_t' ) ? fa_t( 'Prodotto successivo' ) : 'Prodotto successivo' ); ?>">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg>
				</button>
			</div>
			<div class="fa-hero-offers__dots" data-fa-offers-dots role="tablist" aria-label="<?php echo esc_attr( function_exists( 'fa_t' ) ? fa_t( 'Offerte in evidenza' ) : 'Offerte in evidenza' ); ?>"></div>
		</aside>
		<?php endif; ?>
	</div>

	<div class="fa-hero__wave" aria-hidden="true">
		<svg viewBox="0 0 1440 90" preserveAspectRatio="none">
			<path d="M0,40 C320,90 640,0 960,40 C1200,70 1320,60 1440,40 L1440,90 L0,90 Z" fill="#ffffff"/>
		</svg>
	</div>
</section>

<nav class="fa-mobile-quickcats" aria-label="<?php echo esc_attr( fa_t( 'Categorie rapide' ) ); ?>">
	<div class="fa-container">
		<div class="fa-mobile-quickcats__grid">
			<?php foreach ( $mobile_quick_defs as $qi => $qdef ) :
				$qcat = isset( $cats_by_icon[ $qdef['icon'] ] ) ? $cats_by_icon[ $qdef['icon'] ] : null;
				$qurl = $qcat && ! empty( $qcat['url'] ) ? $qcat['url'] : $shop_url;
				?>
				<a href="<?php echo esc_url( $qurl ); ?>" class="fa-mobile-quickcat" style="--fa-delay: <?php echo esc_attr( (string) ( $qi * 40 ) ); ?>ms">
					<span class="fa-mobile-quickcat__icon" aria-hidden="true">
						<?php echo function_exists( 'fa_get_category_icon_svg' ) ? fa_get_category_icon_svg( $qdef['icon'] ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<span class="fa-mobile-quickcat__label"><?php echo esc_html( $qdef['label'] ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</nav>

<section class="fa-section fa-section--categories" id="fa-categories">
	<div class="fa-container">
		<div class="fa-section__header fa-section__header--row" data-fa-animate="fade-up">
			<div>
				<span class="fa-section__label"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Shopping per categoria' ) : 'Shopping per categoria' ); ?></span>
				<h2 class="fa-section__title"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Categorie principali' ) : 'Categorie principali' ); ?></h2>
			</div>
			<a href="<?php echo esc_url( $shop_url ); ?>" class="fa-section__link"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Vedi tutto lo shop' ) : 'Vedi tutto lo shop' ); ?></a>
		</div>

		<div class="fa-cat-grid">
			<?php foreach ( array_slice( $cats, 0, 10 ) as $i => $cat ) :
				$image = ! empty( $cat['image'] ) ? $cat['image'] : ( function_exists( 'fa_get_category_fallback_image' ) ? fa_get_category_fallback_image( $cat['icon'] ) : '' );
				?>
				<a href="<?php echo esc_url( $cat['url'] ); ?>" class="fa-cat-card" data-fa-animate="fade-up" data-fa-delay="<?php echo esc_attr( (string) ( ( $i % 5 ) * 70 ) ); ?>">
					<div class="fa-cat-card__media">
						<?php if ( $image ) : ?>
							<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $cat['name'] ); ?>" loading="lazy">
						<?php else : ?>
							<div class="fa-cat-card__placeholder"><?php echo fa_get_category_icon_svg( $cat['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						<?php endif; ?>
						<div class="fa-cat-card__overlay"></div>
						<span class="fa-cat-card__cta"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Acquista' ) : 'Acquista' ); ?></span>
					</div>
					<div class="fa-cat-card__body">
						<h3 class="fa-cat-card__title"><?php echo esc_html( $cat['name'] ); ?></h3>
						<span class="fa-cat-card__count"><?php echo ! empty( $cat['count'] ) ? esc_html( sprintf( fa_t( '%d prodotti' ), (int) $cat['count'] ) ) : esc_html( fa_t( 'Scopri i prodotti' ) ); ?></span>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php if ( class_exists( 'WooCommerce' ) ) : ?>

<section class="fa-section fa-section--offers" id="fa-offers">
	<div class="fa-container">
		<div class="fa-section__header fa-section__header--row" data-fa-animate="fade-up">
			<div>
				<span class="fa-section__label"><?php fa_te( 'Prezzi speciali' ); ?></span>
				<h2 class="fa-section__title"><?php fa_te( 'Offerte del mese' ); ?></h2>
			</div>
			<a href="<?php echo esc_url( $shop_url ); ?>" class="fa-section__link"><?php fa_te( 'Tutte le offerte' ); ?></a>
		</div>
		<div class="fa-products-wrap" data-fa-animate="fade-up">
			<?php echo do_shortcode( '[products limit="4" columns="4" on_sale="true" orderby="popularity"]' ); ?>
		</div>
	</div>
</section>

<section class="fa-section fa-section--alt" id="fa-bestsellers">
	<div class="fa-container">
		<div class="fa-section__header fa-section__header--row" data-fa-animate="fade-up">
			<div>
				<span class="fa-section__label"><?php fa_te( 'I preferiti dello shop' ); ?></span>
				<h2 class="fa-section__title"><?php fa_te( 'Prodotti più venduti' ); ?></h2>
			</div>
			<a href="<?php echo esc_url( $shop_url . '?orderby=popularity' ); ?>" class="fa-section__link"><?php fa_te( 'Vedi tutti' ); ?></a>
		</div>
		<div class="fa-products-wrap" data-fa-animate="fade-up">
			<?php echo do_shortcode( '[products limit="8" columns="4" orderby="popularity" order="DESC"]' ); ?>
		</div>
	</div>
</section>

<section class="fa-section" id="fa-new">
	<div class="fa-container">
		<div class="fa-section__header fa-section__header--row" data-fa-animate="fade-up">
			<div>
				<span class="fa-section__label"><?php fa_te( 'Novità in vetrina' ); ?></span>
				<h2 class="fa-section__title"><?php fa_te( 'Nuovi arrivi' ); ?></h2>
			</div>
			<a href="<?php echo esc_url( $shop_url . '?orderby=date' ); ?>" class="fa-section__link"><?php fa_te( 'Vedi tutti' ); ?></a>
		</div>
		<div class="fa-products-wrap" data-fa-animate="fade-up">
			<?php echo do_shortcode( '[products limit="8" columns="4" orderby="date" order="DESC"]' ); ?>
		</div>
	</div>
</section>

<section class="fa-section fa-section--brands" id="fa-brands">
	<div class="fa-container">
		<div class="fa-section__header fa-section__header--row" data-fa-animate="fade-up">
			<div>
				<span class="fa-section__label"><?php fa_te( 'Brand selezionati' ); ?></span>
				<h2 class="fa-section__title"><?php fa_te( 'Marchi principali' ); ?></h2>
			</div>
		</div>
		<div class="fa-brands-grid" data-fa-animate="fade-up">
			<?php foreach ( array( 'Zodiac', 'Hayward', 'Intex', 'Bestway', 'AstralPool', 'Dolphin', 'Gre', 'Bayrol' ) as $brand ) : ?>
				<div class="fa-brand-chip"><span><?php echo esc_html( $brand ); ?></span></div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="fa-section fa-section--alt" id="fa-featured">
	<div class="fa-container">
		<div class="fa-section__header fa-section__header--row" data-fa-animate="fade-up">
			<div>
				<span class="fa-section__label"><?php fa_te( 'Scelti per te' ); ?></span>
				<h2 class="fa-section__title"><?php fa_te( 'Prodotti consigliati' ); ?></h2>
			</div>
			<a href="<?php echo esc_url( $shop_url ); ?>" class="fa-section__link"><?php fa_te( 'Vedi tutti' ); ?></a>
		</div>
		<div class="fa-products-wrap" data-fa-animate="fade-up">
			<?php echo do_shortcode( function_exists( 'fa_get_featured_products_shortcode' ) ? fa_get_featured_products_shortcode() : '[products limit="8" columns="4"]' ); ?>
		</div>
	</div>
</section>

<?php endif; ?>

<section class="fa-trust" aria-label="<?php echo esc_attr( fa_t( 'Vantaggi shop' ) ); ?>">
	<div class="fa-container">
		<div class="fa-trust__grid">
			<div class="fa-trust__item"><strong><?php fa_te( 'Spedizione rapida' ); ?></strong><span><?php fa_te( 'Consegna in 24/48h' ); ?></span></div>
			<div class="fa-trust__item"><strong><?php fa_te( 'Pagamenti sicuri' ); ?></strong><span><?php fa_te( 'Carta, PayPal, bonifico' ); ?></span></div>
			<div class="fa-trust__item"><strong><?php fa_te( 'Solo prodotti piscina' ); ?></strong><span><?php fa_te( 'Catalogo specializzato' ); ?></span></div>
			<div class="fa-trust__item"><strong><?php fa_te( 'Reso facile' ); ?></strong><span><?php fa_te( '14 giorni per ripensarci' ); ?></span></div>
		</div>
	</div>
</section>

<?php
get_footer();
