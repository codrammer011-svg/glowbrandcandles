<?php
/**
 * Free shipping on CATALOG (brochure) orders over $99.
 *
 * Catalog orders entered from the Fundraiser Dashboard flow through the normal
 * WooCommerce cart/checkout and are flagged with $_SESSION['sellername'] === 'Catlog'
 * (the same flag the catalog profit-discount in Fundraiser-Modules relies on —
 * set during catalog entry, unset when the order is placed). When such an order's
 * product subtotal is over $99, shipping is shown as $0.00.
 *
 * Scope: catalog orders only. Online/seller/normal orders are untouched.
 */
defined( 'ABSPATH' ) || exit;

add_filter( 'woocommerce_package_rates', 'glow_catalog_free_shipping_over_99', 100, 2 );
function glow_catalog_free_shipping_over_99( $rates, $package ) {

	// Leave admin screens alone (but allow checkout AJAX).
	if ( is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		return $rates;
	}
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return $rates;
	}

	// Catalog orders set this session flag during entry (see Fundraiser-Modules).
	if ( PHP_SESSION_NONE === session_status() && ! headers_sent() ) {
		@session_start();
	}
	 $is_catalog = glow_fc_cart_is_catalog();
	if ( ! $is_catalog ) {
		return $rates;
	}

	// $100+ => free shipping, hiding the flat rate. The legacy set_installment_fee
	// (same hook) CLEARS chosen_shipping_methods and re-selects 'shiptoschool' when
	// cart_contents_total >= 100 (else 'flat_rate:1'). 'shiptoschool' is otherwise
	// not an available rate, which caused "No shipping method has been selected".
	// So we expose EXACTLY that id as the single free rate, on the same $100 basis,
	// so the chosen method always matches an available rate.
	$amount = isset( $package['contents_cost'] ) ? (float) $package['contents_cost'] : (float) WC()->cart->get_subtotal();
	if ( $amount >= 100 ) {
		return array(
			'shiptoschool' => new WC_Shipping_Rate(
				'shiptoschool',
				__( 'Free shipping', 'woocommerce' ),
				0,
				array(),
				'shiptoschool'
			),
		);
	}

	// Under $100: show ONLY the paid flat rate. Strip any free option (e.g. the
	// zone's "Free shipping" method) so free shipping never appears below $100.
	foreach ( $rates as $key => $rate ) {
		if ( 'flat_rate' !== $rate->method_id ) {
			unset( $rates[ $key ] );
		}
	}
	return $rates;
}
