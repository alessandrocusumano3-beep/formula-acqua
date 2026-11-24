<?php
/**
 * My Account navigation - Formula Acqua
 *
 * @package Formula_Acqua
 * @version 9.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$icons = array(
	'dashboard'       => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>',
	'orders'          => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>',
	'edit-address'    => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>',
	'payment-methods' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>',
	'edit-account'    => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
	'customer-logout' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>',
);

$items  = wc_get_account_menu_items();
$logout = null;
if ( isset( $items['customer-logout'] ) ) {
	$logout = $items['customer-logout'];
	unset( $items['customer-logout'] );
}

$user = wp_get_current_user();

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation fa-account__nav" aria-label="<?php esc_attr_e( 'Account pages', 'woocommerce' ); ?>">
	<div class="fa-account__nav-profile">
		<span class="fa-account__nav-avatar" aria-hidden="true"><?php echo esc_html( function_exists( 'fa_get_user_initial' ) ? fa_get_user_initial( $user ) : 'U' ); ?></span>
		<div class="fa-account__nav-meta">
			<strong><?php echo esc_html( $user->display_name ); ?></strong>
			<small class="fa-account__nav-email" title="<?php echo esc_attr( $user->user_email ); ?>"><?php echo esc_html( $user->user_email ); ?></small>
		</div>
	</div>

	<ul class="fa-account__nav-list">
		<?php foreach ( $items as $endpoint => $label ) : ?>
			<li class="<?php echo esc_attr( wc_get_account_menu_item_classes( $endpoint ) ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" <?php echo wc_is_current_account_menu_item( $endpoint ) ? 'aria-current="page"' : ''; ?>>
					<span class="fa-account__nav-icon" aria-hidden="true">
						<?php echo isset( $icons[ $endpoint ] ) ? $icons[ $endpoint ] : $icons['dashboard']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<span class="fa-account__nav-label"><?php echo esc_html( $label ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>

	<?php if ( $logout ) : ?>
		<div class="fa-account__nav-footer">
			<a class="fa-account__logout" href="<?php echo esc_url( wc_get_account_endpoint_url( 'customer-logout' ) ); ?>">
				<span class="fa-account__nav-icon" aria-hidden="true"><?php echo $icons['customer-logout']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span><?php echo esc_html( $logout ); ?></span>
			</a>
		</div>
	<?php endif; ?>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
