<?php
/**
 * GLOW ships to the lower 48 states and DC only: no Alaska, Hawaii, overseas military
 * (APO/FPO), US territories or Canada.
 *
 * Checkout already drops those states (custom_us_states in legacy/theme-functions.php)
 * and the shipping zone only covers the 48 + DC. This file covers the address forms
 * that are not WooCommerce checkout, and the orders the site creates by itself:
 *  - fundraiser registration / Join Us (User Registration forms): state dropdown
 *  - the fundraiser "Edit info" page: state dropdown (uses glow_lower48_state_names())
 *  - kit orders: glow_create_fundraiser_shipping_order() skips blocked addresses
 *  - any order with a blocked address is kept out of ShipStation and flagged by email.
 */

defined('ABSPATH') || exit;

/** Full names of the states GLOW ships to (lower 48 + District of Columbia). */
function glow_lower48_state_names() {
    $all = include WC()->plugin_path() . '/i18n/states.php';
    return array_values(array_diff_key($all['US'], array_flip(array('AK', 'HI', 'AA', 'AE', 'AP'))));
}

/**
 * Whether an address is outside the shipping area. Only known out-of-area values count:
 * an empty or unrecognised state is left alone, since older orders store full names.
 */
function glow_is_blocked_ship_address($country, $state) {
    $country = strtoupper(trim((string) $country));
    if ('' !== $country && 'US' !== $country) {
        return true;
    }
    $state = strtolower(trim((string) $state));
    if ('' === $state) {
        return false;
    }
    $codes = array('ak', 'hi', 'aa', 'ae', 'ap', 'gu', 'pr', 'vi', 'as', 'mp', 'um', 'mh', 'pw', 'fm');
    if (in_array($state, $codes, true)) {
        return true;
    }
    return (bool) preg_match('/^(alaska|hawaii|armed forces|guam|puerto rico|(us |u\.s\. )?virgin islands|american samoa|northern mariana|marshall islands|palau|micronesia|alberta|british columbia|manitoba|new brunswick|newfoundland|northwest territories|nova scotia|nunavut|ontario|prince edward island|quebec|saskatchewan|yukon)/', $state);
}

/** User Registration state dropdowns (fundraiser registration, Join Us): lower 48 + DC only. */
add_filter('user_registration_form_field_args', function ($args) {
    if (empty($args['options']) || !is_array($args['options']) || 'select' !== ($args['type'] ?? '')) {
        return $args;
    }
    $options = $args['options'];
    if (!in_array('Texas', $options, true) || !in_array('Alaska', $options, true)) {
        return $args; // not a US state list
    }
    // Keys are the submitted values ("Texas" => "Texas"), so keep them as they are.
    $keep = glow_lower48_state_names();
    $args['options'] = array_filter($options, function ($o) use ($keep) {
        return in_array($o, $keep, true) || 0 === stripos((string) $o, 'Please select') || '' === trim((string) $o);
    });
    return $args;
});

/** Never send an order with an out-of-area address to ShipStation; flag it once instead. */
add_filter('woocommerce_shipstation_export_order', function ($export, $order_id) {
    if (!$export) {
        return $export;
    }
    $order = wc_get_order($order_id);
    if (!$order) {
        return $export;
    }
    $ship    = (bool) $order->get_shipping_address_1();
    $country = $ship ? $order->get_shipping_country() : $order->get_billing_country();
    $state   = $ship ? $order->get_shipping_state() : $order->get_billing_state();
    if (!glow_is_blocked_ship_address($country, $state)) {
        return $export;
    }
    if (!$order->get_meta('_glow_outside_ship_area')) {
        $where = trim($state . ' ' . $country);
        $order->update_meta_data('_glow_outside_ship_area', $where);
        $order->save_meta_data();
        $order->add_order_note(sprintf('Not sent to ShipStation: the address is outside the shipping area (%s). GLOW ships to the lower 48 states and DC only.', $where));
        wp_mail(
            get_option('admin_email'),
            sprintf('[GLOW] Order #%d not sent to ShipStation: address outside shipping area', $order->get_id()),
            sprintf("Order #%d has an address in %s, which is outside the shipping area (lower 48 states and DC only), so it was not sent to ShipStation.\n\n%s", $order->get_id(), $where, admin_url('post.php?post=' . $order->get_id() . '&action=edit'))
        );
    }
    return false;
}, 20, 2);
