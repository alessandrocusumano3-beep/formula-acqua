<?php
/**
 * Header Shop - Formula Acqua
 *
 * @package Formula_Acqua
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fa_cats        = function_exists( 'fa_get_menu_categories' ) ? fa_get_menu_categories() : ( function_exists( 'fa_get_nav_categories' ) ? fa_get_nav_categories() : array() );
$fa_about_url   = function_exists( 'fa_get_about_page_url' ) ? fa_get_about_page_url() : home_url( '/chi-siamo/' );
$fa_contact_url = function_exists( 'fa_get_legal_page_url' ) ? fa_get_legal_page_url( 'contatti' ) : home_url( '/contatti/' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php echo esc_attr( function_exists( 'fa_get_meta_description' ) ? fa_get_meta_description() : get_bloginfo( 'description' ) ); ?>">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="fa-topbar">
	<div class="fa-container fa-topbar__inner">
		<div class="fa-topbar__items">
			<span class="fa-topbar__item"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Spedizione gratuita sopra 79€' ) : 'Spedizione gratuita sopra 79€' ); ?></span>
			<span class="fa-topbar__divider" aria-hidden="true"></span>
			<span class="fa-topbar__item"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Reso entro 14 giorni' ) : 'Reso entro 14 giorni' ); ?></span>
			<span class="fa-topbar__divider fa-topbar__divider--desktop" aria-hidden="true"></span>
			<span class="fa-topbar__item fa-topbar__item--desktop"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Assistenza specializzata piscine' ) : 'Assistenza specializzata piscine' ); ?></span>
		</div>
	</div>
</div>

<header class="fa-header" id="fa-header">
	<div class="fa-container fa-header__inner">
		<div class="fa-header__left">
			<button class="fa-header__menu-btn" id="fa-cats-toggle" type="button" aria-label="<?php echo esc_attr( function_exists( 'fa_t' ) ? fa_t( 'Apri menu' ) : 'Apri menu' ); ?>" aria-expanded="false" aria-controls="fa-cats-drawer">
				<span class="fa-header__hamburger" aria-hidden="true"><span></span><span></span><span></span></span>
			</button>

			<div class="fa-header__logo">
				<?php
				$fa_logo_file = FA_DIR . '/assets/images/logo-fa.png';
				$fa_logo_url  = FA_URI . '/assets/images/logo-fa.png';
				if ( file_exists( $fa_logo_file ) ) {
					$fa_logo_url .= '?v=' . rawurlencode( (string) filemtime( $fa_logo_file ) );
				}
				?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="fa-logo" rel="home">
					<img
						class="fa-logo__img"
						src="<?php echo esc_url( $fa_logo_url ); ?>"
						alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
						width="54"
						height="40"
						decoding="async"
					>
				</a>
			</div>

			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="fa-header__home" aria-label="<?php echo esc_attr( function_exists( 'fa_t' ) ? fa_t( 'Home' ) : 'Home' ); ?>">
				<svg class="fa-header__home-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M3 10.5L12 3l9 7.5"/>
					<path d="M5 9.5V20h14V9.5"/>
					<path d="M10 20v-6h4v6"/>
				</svg>
				<span class="fa-header__home-label"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Home' ) : 'Home' ); ?></span>
			</a>

			<?php if ( function_exists( 'fa_lang_switcher' ) ) : ?>
				<?php fa_lang_switcher( 'header' ); ?>
			<?php endif; ?>
		</div>

		<div class="fa-header__search">
			<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="fa-search-bar">
				<svg class="fa-search-bar__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
				<input type="search" name="s" placeholder="<?php echo esc_attr( function_exists( 'fa_t' ) ? fa_t( 'Cerca tra migliaia di prodotti per la piscina...' ) : 'Cerca tra migliaia di prodotti per la piscina...' ); ?>" data-placeholder-desktop="<?php echo esc_attr( function_exists( 'fa_t' ) ? fa_t( 'Cerca tra migliaia di prodotti per la piscina...' ) : 'Cerca tra migliaia di prodotti per la piscina...' ); ?>" data-placeholder-mobile="<?php echo esc_attr( function_exists( 'fa_t' ) ? fa_t( 'Cerca prodotti per la piscina...' ) : 'Cerca prodotti per la piscina...' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" aria-label="<?php echo esc_attr( function_exists( 'fa_t' ) ? fa_t( 'Cerca' ) : 'Cerca' ); ?>">
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<input type="hidden" name="post_type" value="product">
				<?php endif; ?>
				<button type="submit" class="fa-search-bar__btn"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Cerca' ) : 'Cerca' ); ?></button>
			</form>
		</div>

		<div class="fa-header__actions">
			<?php
			$fa_account_url  = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
			$fa_wishlist_url = function_exists( 'fa_get_wishlist_url' ) ? fa_get_wishlist_url() : home_url( '/' );
			$fa_cart_url     = class_exists( 'WooCommerce' ) ? wc_get_cart_url() : home_url( '/' );
			$fa_cart_count   = ( class_exists( 'WooCommerce' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
			$fa_wish_count   = function_exists( 'fa_get_wishlist_count' ) ? fa_get_wishlist_count() : 0;
			$fa_logged_in    = is_user_logged_in();
			$fa_user         = $fa_logged_in ? wp_get_current_user() : null;
			$fa_user_name    = $fa_user ? ( $fa_user->first_name ? $fa_user->first_name : $fa_user->display_name ) : '';
			$fa_user_initial = function_exists( 'fa_get_user_initial' ) ? fa_get_user_initial( $fa_user ) : 'U';
			?>
			<a href="<?php echo esc_url( $fa_wishlist_url ); ?>" class="fa-header__action fa-header__wishlist" aria-label="<?php echo esc_attr( function_exists( 'fa_t' ) ? fa_t( 'Preferiti' ) : 'Preferiti' ); ?>">
				<span class="fa-header__action-icon" aria-hidden="true">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
					<span class="fa-wish-count" data-count="<?php echo esc_attr( (string) $fa_wish_count ); ?>"><?php echo esc_html( (string) $fa_wish_count ); ?></span>
				</span>
				<span class="fa-header__action-label"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Preferiti' ) : 'Preferiti' ); ?></span>
			</a>
			<a href="<?php echo esc_url( $fa_account_url ); ?>" class="fa-header__action fa-header__account<?php echo $fa_logged_in ? ' is-logged' : ''; ?>" aria-label="<?php echo esc_attr( function_exists( 'fa_t' ) ? ( $fa_logged_in ? fa_t( 'Il mio account' ) : fa_t( 'Accedi o registrati' ) ) : ( $fa_logged_in ? 'Il mio account' : 'Accedi o registrati' ) ); ?>">
				<span class="fa-header__action-icon" aria-hidden="true">
					<?php if ( $fa_logged_in ) : ?>
						<span class="fa-header__avatar"><?php echo esc_html( $fa_user_initial ); ?></span>
					<?php else : ?>
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
					<?php endif; ?>
				</span>
				<span class="fa-header__action-label"><?php echo $fa_logged_in ? esc_html( $fa_user_name ) : esc_html( function_exists( 'fa_t' ) ? fa_t( 'Account' ) : 'Account' ); ?></span>
			</a>
			<a href="<?php echo esc_url( $fa_cart_url ); ?>" class="fa-header__action fa-header__cart" aria-label="<?php echo esc_attr( function_exists( 'fa_t' ) ? fa_t( 'Carrello' ) : 'Carrello' ); ?>">
				<span class="fa-header__action-icon" aria-hidden="true">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
					<span class="fa-cart-count" data-count="<?php echo esc_attr( (string) $fa_cart_count ); ?>"><?php echo esc_html( (string) $fa_cart_count ); ?></span>
				</span>
				<span class="fa-header__action-label"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Carrello' ) : 'Carrello' ); ?></span>
			</a>
		</div>
	</div>
</header>

<nav class="fa-nav" aria-label="<?php echo esc_attr( function_exists( 'fa_t' ) ? fa_t( 'Categorie' ) : 'Categorie' ); ?>">
	<div class="fa-container fa-nav__inner">
		<ul class="fa-nav__list">
			<?php foreach ( $fa_cats as $cat ) :
				$nav_label = $cat['name'];
				if ( ! empty( $cat['icon'] ) && 'robot' === $cat['icon'] ) {
					$nav_label = function_exists( 'fa_t' ) ? fa_t( 'Robot' ) : 'Robot';
				}
				?>
				<li>
					<a href="<?php echo esc_url( $cat['url'] ); ?>"><?php echo esc_html( $nav_label ); ?></a>
				</li>
			<?php endforeach; ?>
			<li>
				<a href="<?php echo esc_url( $fa_about_url ); ?>"<?php echo is_page_template( 'page-templates/template-about.php' ) ? ' aria-current="page"' : ''; ?>><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Chi siamo' ) : 'Chi siamo' ); ?></a>
			</li>
			<li>
				<a href="<?php echo esc_url( $fa_contact_url ); ?>"<?php echo is_page_template( 'page-templates/template-contact.php' ) ? ' aria-current="page"' : ''; ?>><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Contatti' ) : 'Contatti' ); ?></a>
			</li>
		</ul>
	</div>
</nav>

<div class="fa-cats-backdrop" id="fa-cats-backdrop" hidden></div>
<aside class="fa-cats-drawer" id="fa-cats-drawer" aria-hidden="true" role="dialog" aria-label="<?php echo esc_attr( fa_t( 'Menu' ) ); ?>">
	<div class="fa-cats-drawer__head">
		<div class="fa-cats-drawer__title">
			<span class="fa-cats-drawer__title-full"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Tutte le categorie' ) : 'Tutte le categorie' ); ?></span>
			<span class="fa-cats-drawer__title-short"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Menu' ) : 'Menu' ); ?></span>
		</div>
		<button type="button" class="fa-cats-drawer__close" id="fa-cats-close" aria-label="<?php echo esc_attr( function_exists( 'fa_t' ) ? fa_t( 'Chiudi menu' ) : 'Chiudi menu' ); ?>">
			<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
		</button>
	</div>
	<nav class="fa-cats-drawer__nav" aria-label="<?php echo esc_attr( fa_t( 'Navigazione' ) ); ?>">
		<div class="fa-cats-drawer__account">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="fa-cats-drawer__account-link fa-cats-drawer__home">
				<span class="fa-cats-drawer__account-icon" aria-hidden="true">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5"/><path d="M5 9.5V20h14V9.5"/><path d="M10 20v-6h4v6"/></svg>
				</span>
				<span class="fa-cats-drawer__account-text">
					<strong><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Home' ) : 'Home' ); ?></strong>
					<small><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Torna alla homepage' ) : 'Torna alla homepage' ); ?></small>
				</span>
				<svg class="fa-cats-drawer__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
			</a>
			<a href="<?php echo esc_url( $fa_account_url ); ?>" class="fa-cats-drawer__account-link">
				<span class="fa-cats-drawer__account-icon" aria-hidden="true">
					<?php if ( $fa_logged_in ) : ?>
						<span class="fa-header__avatar"><?php echo esc_html( $fa_user_initial ); ?></span>
					<?php else : ?>
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
					<?php endif; ?>
				</span>
				<span class="fa-cats-drawer__account-text">
					<strong><?php echo $fa_logged_in ? esc_html( $fa_user_name ) : esc_html( function_exists( 'fa_t' ) ? fa_t( 'Account' ) : 'Account' ); ?></strong>
					<small><?php echo esc_html( function_exists( 'fa_t' ) ? ( $fa_logged_in ? fa_t( 'Il mio account' ) : fa_t( 'Accedi o registrati' ) ) : ( $fa_logged_in ? 'Il mio account' : 'Accedi o registrati' ) ); ?></small>
				</span>
				<svg class="fa-cats-drawer__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
			</a>
			<a href="<?php echo esc_url( $fa_wishlist_url ); ?>" class="fa-cats-drawer__account-link">
				<span class="fa-cats-drawer__account-icon" aria-hidden="true">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
					<?php if ( $fa_wish_count > 0 ) : ?>
						<span class="fa-wish-count fa-wish-count--drawer" data-count="<?php echo esc_attr( (string) $fa_wish_count ); ?>"><?php echo esc_html( (string) $fa_wish_count ); ?></span>
					<?php endif; ?>
				</span>
				<span class="fa-cats-drawer__account-text">
					<strong><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Preferiti' ) : 'Preferiti' ); ?></strong>
					<small><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Prodotti salvati' ) : 'Prodotti salvati' ); ?></small>
				</span>
				<svg class="fa-cats-drawer__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
			</a>
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
			<a href="<?php echo esc_url( $fa_cart_url ); ?>" class="fa-cats-drawer__account-link">
				<span class="fa-cats-drawer__account-icon" aria-hidden="true">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
					<?php if ( $fa_cart_count > 0 ) : ?>
						<span class="fa-cart-count" data-count="<?php echo esc_attr( (string) $fa_cart_count ); ?>"><?php echo esc_html( (string) $fa_cart_count ); ?></span>
					<?php endif; ?>
				</span>
				<span class="fa-cats-drawer__account-text">
					<strong><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Carrello' ) : 'Carrello' ); ?></strong>
				</span>
				<svg class="fa-cats-drawer__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
			</a>
			<?php endif; ?>
			<a href="<?php echo esc_url( $fa_about_url ); ?>" class="fa-cats-drawer__account-link"<?php echo is_page_template( 'page-templates/template-about.php' ) ? ' aria-current="page"' : ''; ?>>
				<span class="fa-cats-drawer__account-icon" aria-hidden="true">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.2"/><path d="M5.5 19.2c.8-3.2 3.4-5.2 6.5-5.2s5.7 2 6.5 5.2"/></svg>
				</span>
				<span class="fa-cats-drawer__account-text">
					<strong><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Chi siamo' ) : 'Chi siamo' ); ?></strong>
				</span>
				<svg class="fa-cats-drawer__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
			</a>
		</div>
		<p class="fa-cats-drawer__section-label"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Categorie' ) : 'Categorie' ); ?></p>
		<ul class="fa-cats-drawer__list">
			<?php foreach ( $fa_cats as $cat ) :
				$thumb = ! empty( $cat['image'] ) ? $cat['image'] : '';
				?>
				<li>
					<a href="<?php echo esc_url( $cat['url'] ); ?>">
						<?php if ( $thumb ) : ?>
							<span class="fa-cats-drawer__thumb"><img src="<?php echo esc_url( $thumb ); ?>" alt="" loading="lazy"></span>
						<?php elseif ( ! empty( $cat['icon'] ) && function_exists( 'fa_get_category_icon_svg' ) ) : ?>
							<span class="fa-cats-drawer__icon"><?php echo fa_get_category_icon_svg( $cat['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<?php endif; ?>
						<span class="fa-cats-drawer__name"><?php echo esc_html( $cat['name'] ); ?></span>
						<svg class="fa-cats-drawer__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
					</a>
				</li>
			<?php endforeach; ?>
				<li class="fa-cats-drawer__item--about">
					<a href="<?php echo esc_url( $fa_about_url ); ?>"<?php echo is_page_template( 'page-templates/template-about.php' ) ? ' aria-current="page"' : ''; ?>>
						<span class="fa-cats-drawer__icon" aria-hidden="true">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.2"/><path d="M5.5 19.2c.8-3.2 3.4-5.2 6.5-5.2s5.7 2 6.5 5.2"/></svg>
						</span>
						<span class="fa-cats-drawer__name"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Chi siamo' ) : 'Chi siamo' ); ?></span>
						<svg class="fa-cats-drawer__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
					</a>
				</li>
				<li>
					<a href="<?php echo esc_url( $fa_contact_url ); ?>"<?php echo is_page_template( 'page-templates/template-contact.php' ) ? ' aria-current="page"' : ''; ?>>
						<span class="fa-cats-drawer__icon" aria-hidden="true">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v2.2a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3.1 19.5 19.5 0 01-6-6A19.8 19.8 0 012.1 4.2 2 2 0 014.1 2h2.2a2 2 0 012 1.7c.1.9.3 1.7.6 2.5a2 2 0 01-.5 2.1L7.1 9.6a16 16 0 006.3 6.3l1.3-1.3a2 2 0 012.1-.5c.8.3 1.6.5 2.5.6a2 2 0 011.7 2z"/></svg>
						</span>
						<span class="fa-cats-drawer__name"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Contatti' ) : 'Contatti' ); ?></span>
						<svg class="fa-cats-drawer__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
					</a>
				</li>
		</ul>
		<?php if ( class_exists( 'WooCommerce' ) ) : ?>
			<a class="fa-cats-drawer__shop" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php echo esc_html( function_exists( 'fa_t' ) ? fa_t( 'Vai allo shop completo' ) : 'Vai allo shop completo' ); ?></a>
		<?php endif; ?>
	</nav>
</aside>

<main id="main-content" class="fa-main">
