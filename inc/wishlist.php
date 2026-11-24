<?php
/**
 * Wishlist / Preferiti - Formula Acqua
 *
 * Sistema nativo WooCommerce (user meta + cookie), indipendente dall'account.
 *
 * @package Formula_Acqua
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cookie name for guest wishlist.
 */
define( 'FA_WISHLIST_COOKIE', 'fa_wishlist' );

/**
 * Crea la pagina Preferiti se mancante.
 */
function fa_ensure_wishlist_page() {
	$page_id = absint( get_option( 'fa_wishlist_page_id' ) );
	if ( $page_id && get_post_status( $page_id ) ) {
		$page = get_post( $page_id );
		if ( $page && false === strpos( (string) $page->post_content, '[fa_wishlist]' ) ) {
			wp_update_post(
				array(
					'ID'           => $page_id,
					'post_content' => '[fa_wishlist]',
				)
			);
		}
		return;
	}

	foreach ( array( 'preferiti', 'lista-desideri', 'wishlist' ) as $slug ) {
		$existing = get_page_by_path( $slug );
		if ( $existing ) {
			update_option( 'fa_wishlist_page_id', (int) $existing->ID, false );
			if ( false === strpos( (string) $existing->post_content, '[fa_wishlist]' ) ) {
				wp_update_post(
					array(
						'ID'           => $existing->ID,
						'post_content' => '[fa_wishlist]',
						'post_status'  => 'publish',
					)
				);
			}
			return;
		}
	}

	$page_id = wp_insert_post(
		array(
			'post_title'   => 'Preferiti',
			'post_name'    => 'preferiti',
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_content' => '[fa_wishlist]',
		),
		true
	);

	if ( ! is_wp_error( $page_id ) && $page_id ) {
		update_option( 'fa_wishlist_page_id', (int) $page_id, false );
		update_option( 'fa_wishlist_flush_rewrites', 1, false );
	}
}
add_action( 'init', 'fa_ensure_wishlist_page', 22 );

/**
 * Flush rewrite rules una sola volta dopo la creazione della pagina Preferiti.
 */
function fa_wishlist_maybe_flush_rewrites() {
	if ( get_option( 'fa_wishlist_flush_rewrites' ) ) {
		flush_rewrite_rules( false );
		delete_option( 'fa_wishlist_flush_rewrites' );
	}
}
add_action( 'init', 'fa_wishlist_maybe_flush_rewrites', 99 );

/**
 * @return bool
 */
function fa_is_wishlist_page() {
	$page_id = absint( get_option( 'fa_wishlist_page_id' ) );
	if ( $page_id ) {
		if ( is_page( $page_id ) ) {
			return true;
		}
		if ( function_exists( 'pll_get_post_translations' ) ) {
			$translations = pll_get_post_translations( $page_id );
			if ( is_array( $translations ) && is_page( array_values( $translations ) ) ) {
				return true;
			}
		}
	}
	return is_page( array( 'preferiti', 'lista-desideri', 'wishlist', 'favoris', 'wunschliste', 'favoritos' ) );
}

/**
 * Sanitize product IDs.
 *
 * @param mixed $ids Raw IDs.
 * @return int[]
 */
function fa_sanitize_wishlist_ids( $ids ) {
	if ( ! is_array( $ids ) ) {
		return array();
	}
	$ids = array_map( 'absint', $ids );
	$ids = array_filter( $ids );
	return array_values( array_unique( $ids ) );
}

/**
 * Read guest cookie IDs.
 *
 * @return int[]
 */
function fa_get_wishlist_cookie_ids() {
	if ( empty( $_COOKIE[ FA_WISHLIST_COOKIE ] ) ) {
		return array();
	}
	$raw = wp_unslash( $_COOKIE[ FA_WISHLIST_COOKIE ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	$decoded = json_decode( $raw, true );
	if ( ! is_array( $decoded ) ) {
		$decoded = array_filter( array_map( 'absint', explode( ',', $raw ) ) );
	}
	return fa_sanitize_wishlist_ids( $decoded );
}

/**
 * Persist guest cookie.
 *
 * @param int[] $ids Product IDs.
 */
function fa_set_wishlist_cookie( $ids ) {
	$ids  = fa_sanitize_wishlist_ids( $ids );
	$json = wp_json_encode( $ids );
	if ( ! headers_sent() ) {
		setcookie( FA_WISHLIST_COOKIE, $json, time() + ( DAY_IN_SECONDS * 30 ), COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), false );
	}
	$_COOKIE[ FA_WISHLIST_COOKIE ] = $json;
}

/**
 * Current wishlist product IDs.
 *
 * @return int[]
 */
function fa_get_wishlist_ids() {
	if ( function_exists( 'yith_wcwl_count_products' ) && function_exists( 'YITH_WCWL' ) ) {
		// Prefer native FA wishlist even if YITH exists later; keep FA source of truth.
	}

	if ( is_user_logged_in() ) {
		$ids = get_user_meta( get_current_user_id(), '_fa_wishlist', true );
		return fa_sanitize_wishlist_ids( $ids );
	}

	return fa_get_wishlist_cookie_ids();
}

/**
 * Save wishlist IDs.
 *
 * @param int[] $ids Product IDs.
 */
function fa_set_wishlist_ids( $ids ) {
	$ids = fa_sanitize_wishlist_ids( $ids );

	if ( is_user_logged_in() ) {
		update_user_meta( get_current_user_id(), '_fa_wishlist', $ids );
	}

	fa_set_wishlist_cookie( $ids );
}

/**
 * @param int $product_id Product ID.
 * @return bool
 */
function fa_is_in_wishlist( $product_id ) {
	$product_id = absint( $product_id );
	if ( ! $product_id ) {
		return false;
	}
	return in_array( $product_id, fa_get_wishlist_ids(), true );
}

/**
 * Toggle product in wishlist.
 *
 * @param int $product_id Product ID.
 * @return array{in_wishlist:bool,count:int,ids:int[]}
 */
function fa_wishlist_toggle_product( $product_id ) {
	$product_id = absint( $product_id );
	$ids        = fa_get_wishlist_ids();

	if ( $product_id && in_array( $product_id, $ids, true ) ) {
		$ids = array_values( array_diff( $ids, array( $product_id ) ) );
		$in  = false;
	} elseif ( $product_id ) {
		$ids[] = $product_id;
		$ids   = fa_sanitize_wishlist_ids( $ids );
		$in    = true;
	} else {
		$in = false;
	}

	fa_set_wishlist_ids( $ids );

	return array(
		'in_wishlist' => $in,
		'count'       => count( $ids ),
		'ids'         => $ids,
	);
}

/**
 * Merge cookie wishlist into user meta after login.
 *
 * @param string  $user_login Username.
 * @param WP_User $user       User.
 */
function fa_wishlist_merge_on_login( $user_login, $user ) {
	if ( ! $user || empty( $user->ID ) ) {
		return;
	}
	$cookie_ids = fa_get_wishlist_cookie_ids();
	$user_ids   = fa_sanitize_wishlist_ids( get_user_meta( $user->ID, '_fa_wishlist', true ) );
	$merged     = fa_sanitize_wishlist_ids( array_merge( $user_ids, $cookie_ids ) );
	update_user_meta( $user->ID, '_fa_wishlist', $merged );
	fa_set_wishlist_cookie( $merged );
}
add_action( 'wp_login', 'fa_wishlist_merge_on_login', 10, 2 );

/**
 * Wishlist page URL (never account).
 *
 * @return string
 */
function fa_get_wishlist_url() {
	if ( function_exists( 'YITH_WCWL' ) ) {
		$yith_url = YITH_WCWL()->get_wishlist_url();
		if ( $yith_url && false === strpos( $yith_url, 'my-account' ) && false === strpos( $yith_url, 'il-mio-account' ) ) {
			return $yith_url;
		}
	}

	$page_id = absint( get_option( 'fa_wishlist_page_id' ) );
	if ( $page_id ) {
		if ( function_exists( 'pll_get_post' ) ) {
			$translated = pll_get_post( $page_id );
			if ( $translated ) {
				$page_id = (int) $translated;
			}
		}
		$url = get_permalink( $page_id );
		if ( $url ) {
			return $url;
		}
	}

	foreach ( array( 'preferiti', 'lista-desideri', 'wishlist', 'favoris', 'wunschliste', 'favoritos' ) as $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page ) {
			return get_permalink( $page );
		}
	}

	return home_url( '/preferiti/' );
}

/**
 * Wishlist count.
 *
 * @return int
 */
function fa_get_wishlist_count() {
	return count( fa_get_wishlist_ids() );
}

/**
 * Heart button HTML.
 *
 * @param int  $product_id Product ID.
 * @param bool $icon_only  Compact icon button.
 * @return string
 */
function fa_get_wishlist_button_html( $product_id, $icon_only = true ) {
	$product_id = absint( $product_id );
	if ( ! $product_id ) {
		return '';
	}

	$in    = fa_is_in_wishlist( $product_id );
	$class = 'fa-wishlist-btn' . ( $in ? ' is-active' : '' ) . ( $icon_only ? ' fa-wishlist-btn--icon' : ' fa-wishlist-btn--text' );
	$label = $in ? fa_t( 'Rimuovi dai preferiti' ) : fa_t( 'Aggiungi ai preferiti' );

	ob_start();
	?>
	<button type="button"
		class="<?php echo esc_attr( $class ); ?>"
		data-product-id="<?php echo esc_attr( (string) $product_id ); ?>"
		aria-pressed="<?php echo $in ? 'true' : 'false'; ?>"
		aria-label="<?php echo esc_attr( $label ); ?>"
		title="<?php echo esc_attr( $label ); ?>">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="<?php echo $in ? 'currentColor' : 'none'; ?>" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
			<path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
		</svg>
		<?php if ( ! $icon_only ) : ?>
			<span class="fa-wishlist-btn__label"><?php echo esc_html( $in ? fa_t( 'Nei preferiti' ) : fa_t( 'Aggiungi ai preferiti' ) ); ?></span>
		<?php endif; ?>
	</button>
	<?php
	return ob_get_clean();
}

/**
 * AJAX toggle.
 */
function fa_ajax_wishlist_toggle() {
	check_ajax_referer( 'fa_nonce', 'nonce' );

	$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
	if ( ! $product_id || ! class_exists( 'WooCommerce' ) ) {
		wp_send_json_error( array( 'message' => fa_t( 'Prodotto non valido.' ) ), 400 );
	}

	$product = wc_get_product( $product_id );
	if ( ! $product ) {
		wp_send_json_error( array( 'message' => fa_t( 'Prodotto non trovato.' ) ), 404 );
	}

	$result = fa_wishlist_toggle_product( $product_id );

	wp_send_json_success(
		array(
			'in_wishlist' => $result['in_wishlist'],
			'count'       => $result['count'],
			'product_id'  => $product_id,
			'label'       => $result['in_wishlist'] ? fa_t( 'Rimuovi dai preferiti' ) : fa_t( 'Aggiungi ai preferiti' ),
			'text'        => $result['in_wishlist'] ? fa_t( 'Nei preferiti' ) : fa_t( 'Aggiungi ai preferiti' ),
			'count_html'  => fa_wishlist_count_badge_html( $result['count'] ),
		)
	);
}
add_action( 'wp_ajax_fa_wishlist_toggle', 'fa_ajax_wishlist_toggle' );
add_action( 'wp_ajax_nopriv_fa_wishlist_toggle', 'fa_ajax_wishlist_toggle' );

/**
 * Header badge HTML.
 *
 * @param int|null $count Count override.
 * @return string
 */
function fa_wishlist_count_badge_html( $count = null ) {
	if ( null === $count ) {
		$count = fa_get_wishlist_count();
	}
	$count = (int) $count;
	return '<span class="fa-wish-count" data-count="' . esc_attr( (string) $count ) . '">' . esc_html( (string) $count ) . '</span>';
}

/**
 * Shortcode: lista preferiti.
 *
 * @return string
 */
function fa_wishlist_shortcode() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return '<p>WooCommerce non è attivo.</p>';
	}

	$ids = fa_get_wishlist_ids();

	ob_start();
	?>
	<div class="fa-wishlist-page">
		<header class="fa-wishlist-page__header">
			<p class="fa-wishlist-page__eyebrow"><?php echo esc_html( fa_t( 'I tuoi prodotti' ) ); ?></p>
			<h1 class="fa-wishlist-page__title"><?php echo esc_html( fa_t( 'Preferiti' ) ); ?></h1>
			<p class="fa-wishlist-page__desc">
				<?php
				$fa_wishlist_count_label = 1 === count( $ids ) ? fa_t( '%d prodotto salvato' ) : fa_t( '%d prodotti salvati' );
				printf( esc_html( $fa_wishlist_count_label ), count( $ids ) );
				?>
			</p>
		</header>

		<?php if ( empty( $ids ) ) : ?>
			<div class="fa-wishlist-empty">
				<div class="fa-wishlist-empty__icon" aria-hidden="true">
					<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
				</div>
				<h2><?php echo esc_html( fa_t( 'La tua lista preferiti è vuota' ) ); ?></h2>
				<p><?php echo esc_html( fa_t( 'Aggiungi i prodotti che ti interessano cliccando sul cuore nelle schede prodotto.' ) ); ?></p>
				<p class="fa-wishlist-empty__cta">
					<a class="fa-btn fa-btn--primary" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php echo esc_html( fa_t( 'Continua lo shopping' ) ); ?></a>
				</p>
			</div>
		<?php else : ?>
			<ul class="fa-wishlist-grid products">
				<?php
				foreach ( $ids as $product_id ) {
					$product = wc_get_product( $product_id );
					if ( ! $product || 'publish' !== get_post_status( $product_id ) ) {
						continue;
					}
					$GLOBALS['product'] = $product;
					?>
					<li class="fa-wishlist-item fa-product-card product" data-product-id="<?php echo esc_attr( (string) $product_id ); ?>">
						<div class="fa-product-card__media">
							<?php echo fa_get_wishlist_button_html( $product_id, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="fa-product-card__image">
								<?php echo $product->get_image( 'woocommerce_thumbnail', array( 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
						</div>
						<div class="fa-product-card__body">
							<a class="fa-product-card__title" href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
								<?php echo esc_html( $product->get_name() ); ?>
							</a>
							<?php
							if ( function_exists( 'fa_get_loop_price_html' ) ) {
								echo fa_get_loop_price_html( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							} else {
								echo '<div class="fa-product-card__price">' . $product->get_price_html() . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							}
							?>
							<div class="fa-product-card__actions fa-wishlist-item__actions">
								<?php
								echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									'woocommerce_loop_add_to_cart_link',
									sprintf(
										'<a href="%s" data-quantity="1" class="button product_type_%s add_to_cart_button ajax_add_to_cart" data-product_id="%s" data-product_sku="%s" aria-label="%s" rel="nofollow">%s</a>',
										esc_url( $product->add_to_cart_url() ),
										esc_attr( $product->get_type() ),
										esc_attr( (string) $product->get_id() ),
										esc_attr( $product->get_sku() ),
										esc_attr( $product->add_to_cart_description() ),
										esc_html( $product->add_to_cart_text() )
									),
									$product
								);
								?>
								<button type="button" class="fa-wishlist-remove" data-product-id="<?php echo esc_attr( (string) $product_id ); ?>">
									<?php echo esc_html( fa_t( 'Rimuovi' ) ); ?>
								</button>
							</div>
						</div>
					</li>
					<?php
				}
				?>
			</ul>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'fa_wishlist', 'fa_wishlist_shortcode' );

/**
 * Heart on loop cards.
 */
function fa_wishlist_loop_button() {
	global $product;
	if ( ! $product ) {
		return;
	}
	echo fa_get_wishlist_button_html( $product->get_id(), true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Heart on single product.
 */
function fa_wishlist_single_button() {
	global $product;
	if ( ! $product ) {
		return;
	}
	echo '<div class="fa-wishlist-single">';
	echo fa_get_wishlist_button_html( $product->get_id(), false ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '</div>';
}
add_action( 'woocommerce_after_add_to_cart_button', 'fa_wishlist_single_button', 15 );

/**
 * Body class for wishlist page.
 *
 * @param string[] $classes Classes.
 * @return string[]
 */
function fa_wishlist_body_class( $classes ) {
	if ( fa_is_wishlist_page() ) {
		$classes[] = 'fa-wishlist-body';
	}
	return $classes;
}
add_filter( 'body_class', 'fa_wishlist_body_class' );
