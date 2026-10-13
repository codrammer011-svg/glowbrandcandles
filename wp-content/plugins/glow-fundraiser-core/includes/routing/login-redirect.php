<?php
/**
 * Role-based login redirect + dashboard access guards.
 *
 * The Elementor "User Login" widget only accepts a single, literal redirect URL
 * (shortcodes do NOT work in its URL field), so every role ended up on the same
 * page. This routes each user to their own dashboard after login, and keeps users
 * who aren't allowed off the fundraiser / representative dashboards.
 *
 * Access-control summary:
 *   - Fundraiser dashboard  -> admins + fundraisers   (guarded in Fundraiser-Modules:fundraiser_dashboard_redirect)
 *   - Representative dashboard -> admins + representatives (guarded here — its template calls get_header() first, so it can't redirect itself)
 *   - Seller dashboard   -> sellers land here on login and are bounced here from other dashboards
 *   - Seller profile (/profile/{id}, /seller/{id}) -> PUBLIC (anyone, incl. other sellers, may view)
 */
defined( 'ABSPATH' ) || exit;

/**
 * Send each role to its own landing page after login. Applies to wp-login AND
 * the Elementor login widget (both run through the `login_redirect` filter).
 * Runs after the legacy `glowscented_users_login_redirect` (priority 10).
 */
add_filter( 'login_redirect', 'glow_fc_role_login_redirect', 20, 3 );
function glow_fc_role_login_redirect( $redirect_to, $requested_redirect_to, $user ) {
	if ( ! is_a( $user, 'WP_User' ) ) {
		return $redirect_to;
	}
	$roles = (array) $user->roles;

	// Admins keep their normal destination (wp-admin from wp-login, or the widget URL).
	if ( in_array( 'administrator', $roles, true ) ) {
		return $redirect_to;
	}
	if ( in_array( 'representative', $roles, true ) ) {
		return home_url( '/representative-dashboard/' );
	}
	if ( in_array( 'fundraiser', $roles, true ) ) {
		return home_url( '/fundraiser-dashboard/' );
	}
	if ( in_array( 'seller', $roles, true ) ) {
		return home_url( '/seller-dashboard/' );
	}
	// Everyone else (customers/subscribers) → account. NEVER a restricted dashboard.
	return home_url( '/my-account/' );
}

/**
 * Guard the REPRESENTATIVE dashboard. Its template outputs (get_header) before
 * checking the role, so it can't redirect itself — do it here, before render.
 * (The fundraiser dashboard is guarded in Fundraiser-Modules.)
 */
add_action( 'template_redirect', 'glow_fc_representative_dashboard_guard' );
function glow_fc_representative_dashboard_guard() {
	if ( is_admin() || ! is_page( 'representative-dashboard' ) ) {
		return;
	}
	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( wp_login_url( home_url( '/representative-dashboard/' ) ) );
		exit;
	}
	$user  = wp_get_current_user();
	$roles = (array) $user->roles;

	// Admins & representatives may view it.
	if ( array_intersect( array( 'administrator', 'representative' ), $roles ) ) {
		return;
	}
	// Everyone else → their own place.
	if ( in_array( 'fundraiser', $roles, true ) ) {
		wp_safe_redirect( home_url( '/fundraiser-dashboard/' ) );
	} elseif ( in_array( 'seller', $roles, true ) ) {
		wp_safe_redirect( home_url( '/seller-dashboard/' ) );
	} else {
		wp_safe_redirect( home_url( '/my-account/' ) );
	}
	exit;
}
