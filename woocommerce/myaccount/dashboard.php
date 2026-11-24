<?php
/**
 * My Account Dashboard - Formula Acqua
 *
 * @package Formula_Acqua
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user = wp_get_current_user();
$orders_url    = wc_get_endpoint_url( 'orders' );
$address_url   = wc_get_endpoint_url( 'edit-address' );
$account_url   = wc_get_endpoint_url( 'edit-account' );
$cart_url      = wc_get_cart_url();
$shop_url      = wc_get_page_permalink( 'shop' );

$order_count = 0;
if ( function_exists( 'wc_get_customer_order_count' ) ) {
	$order_count = (int) wc_get_customer_order_count( get_current_user_id() );
}

$recent_orders = wc_get_orders(
	array(
		'customer_id' => get_current_user_id(),
		'limit'       => 3,
		'orderby'     => 'date',
		'order'       => 'DESC',
		'status'      => array_keys( wc_get_order_statuses() ),
	)
);
?>

<div class="fa-dash">
	<div class="fa-dash__welcome">
		<div class="fa-dash__welcome-main">
			<span class="fa-dash__avatar" aria-hidden="true"><?php echo esc_html( function_exists( 'fa_get_user_initial' ) ? fa_get_user_initial( $current_user ) : 'U' ); ?></span>
			<div class="fa-dash__welcome-text">
				<h2><?php echo esc_html( fa_t( 'Bentornato' ) ); ?>, <?php echo esc_html( $current_user->first_name ? $current_user->first_name : $current_user->display_name ); ?></h2>
				<p><?php fa_te( 'Gestisci ordini, indirizzi e preferenze dal tuo spazio personale Formula Acqua.' ); ?></p>
			</div>
		</div>
	</div>

	<div class="fa-dash__stats">
		<div class="fa-dash__stat">
			<span class="fa-dash__stat-label"><?php fa_te( 'Ordini' ); ?></span>
			<strong class="fa-dash__stat-value"><?php echo esc_html( (string) $order_count ); ?></strong>
		</div>
		<div class="fa-dash__stat">
			<span class="fa-dash__stat-label"><?php fa_te( 'Carrello' ); ?></span>
			<strong class="fa-dash__stat-value"><?php echo esc_html( (string) ( WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ) ); ?></strong>
		</div>
		<div class="fa-dash__stat">
			<span class="fa-dash__stat-label"><?php fa_te( 'Account' ); ?></span>
			<strong class="fa-dash__stat-value"><?php fa_te( 'Attivo' ); ?></strong>
		</div>
	</div>

	<div class="fa-dash__cards">
		<a class="fa-dash__card" href="<?php echo esc_url( $orders_url ); ?>">
			<span class="fa-dash__card-icon" aria-hidden="true">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
			</span>
			<strong><?php fa_te( 'I miei ordini' ); ?></strong>
			<span><?php fa_te( 'Stato, dettagli e storico acquisti' ); ?></span>
		</a>
		<a class="fa-dash__card" href="<?php echo esc_url( $address_url ); ?>">
			<span class="fa-dash__card-icon" aria-hidden="true">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
			</span>
			<strong><?php fa_te( 'Indirizzi' ); ?></strong>
			<span><?php fa_te( 'Fatturazione e spedizione' ); ?></span>
		</a>
		<a class="fa-dash__card" href="<?php echo esc_url( $account_url ); ?>">
			<span class="fa-dash__card-icon" aria-hidden="true">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
			</span>
			<strong><?php fa_te( 'Dati personali' ); ?></strong>
			<span><?php fa_te( 'Nome, email e password' ); ?></span>
		</a>
		<a class="fa-dash__card" href="<?php echo esc_url( $cart_url ); ?>">
			<span class="fa-dash__card-icon" aria-hidden="true">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
			</span>
			<strong><?php fa_te( 'Il tuo carrello' ); ?></strong>
			<span><?php fa_te( 'Riprendi gli articoli salvati' ); ?></span>
		</a>
	</div>

	<section class="fa-dash__orders">
		<div class="fa-dash__orders-head">
			<h3><?php fa_te( 'Ordini recenti' ); ?></h3>
			<a href="<?php echo esc_url( $orders_url ); ?>"><?php fa_te( 'Vedi tutti' ); ?></a>
		</div>

		<?php if ( ! empty( $recent_orders ) ) : ?>
			<ul class="fa-dash__order-list">
				<?php foreach ( $recent_orders as $order ) : ?>
					<li>
						<div>
							<strong>#<?php echo esc_html( $order->get_order_number() ); ?></strong>
							<span><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></span>
						</div>
						<div class="fa-dash__order-meta">
							<span class="fa-dash__status"><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></span>
							<strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
						</div>
						<a class="fa-dash__order-link" href="<?php echo esc_url( $order->get_view_order_url() ); ?>"><?php fa_te( 'Dettagli' ); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php else : ?>
			<div class="fa-dash__empty">
				<p><?php fa_te( 'Non hai ancora effettuato ordini.' ); ?></p>
				<a class="fa-btn fa-btn--primary" href="<?php echo esc_url( $shop_url ); ?>"><?php fa_te( 'Inizia a fare shopping' ); ?></a>
			</div>
		<?php endif; ?>
	</section>
</div>

<?php
do_action( 'woocommerce_account_dashboard' );
do_action( 'woocommerce_before_my_account' );
do_action( 'woocommerce_after_my_account' );
