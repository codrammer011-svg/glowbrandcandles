<?php
defined('ABSPATH') || exit;

function glow_is_virtual_preference($preference) {
    return strcasecmp(trim((string)$preference), 'Virtual Fundraiser - No Brochures.') === 0;
}

function glow_checkout_ship_to_group_blocked() {
    $fields = [];
    if (isset($_POST['post_data'])) parse_str(wp_unslash($_POST['post_data']), $fields);
    elseif (isset($_POST['fundraiser_field'])) $fields['fundraiser_field'] = wp_unslash($_POST['fundraiser_field']);
    $fund_id = array_key_exists('fundraiser_field', $fields) ? $fields['fundraiser_field'] : '';
    if (!$fields && function_exists('WC') && WC()->session) {
        $fund_id = WC()->session->get('fridID') ?: WC()->session->get('popup_fundraiser_id');
        if (!$fund_id) $fund_id = $_COOKIE['fundraiserID'] ?? '';
    }
    $fund_id = absint($fund_id);
    if (!$fund_id) return false;
    $fundraiser = get_userdata($fund_id);
    if (!$fundraiser || !in_array('fundraiser', (array)$fundraiser->roles, true)) return true;
    return (int)get_user_meta($fund_id, 'user_action_status', true) === 2
        || get_user_meta($fund_id, 'wc_first_catlog_order', true) === 'Yes'
        || glow_is_virtual_preference(get_user_meta($fund_id, 'user_registration_fundraiser_preference', true));
}

function glow_ship_to_group_customer_allowed() {
    $roles = (array)wp_get_current_user()->roles;
    return !is_user_logged_in() || (in_array('seller', $roles, true) && !in_array('fundraiser', $roles, true) && !in_array('administrator', $roles, true));
}

// Shipping is calculated before fees, so select the method before totals start.
add_action('woocommerce_before_calculate_totals', 'glow_choose_installment_shipping_method', 20);
function glow_choose_installment_shipping_method($cart) {
    if ((is_admin() && !(defined('DOING_AJAX') && DOING_AJAX)) || !is_checkout() || !WC()->session) return;
    if (glow_fc_cart_is_catalog()) {
        WC()->session->set('installment_fee', 0);
        return; // Brochure shipping uses the catalog rate filter; do not override it.
    }
    if (isset($_POST['post_data'])) {
        parse_str(wp_unslash($_POST['post_data']), $fields);
        WC()->session->set('installment_fee', !empty($fields['installment_fee']) ? 1 : 0);
    } elseif (isset($_POST['fundraiser_field'])) {
        WC()->session->set('installment_fee', !empty($_POST['installment_fee']) ? 1 : 0);
    }
    if (!glow_ship_to_group_customer_allowed() || glow_checkout_ship_to_group_blocked()) WC()->session->set('installment_fee', 0);
    // Current line prices are reliable on the first refresh; previous cart totals can be stale.
    $amount = 0;
    foreach ($cart->get_cart() as $item) $amount += wc_get_price_excluding_tax($item['data'], ['qty'=>$item['quantity']]);
    $method = WC()->session->get('installment_fee') == 1 || $amount >= 100 ? 'shiptoschool' : 'flat_rate:1';
    WC()->session->set('chosen_shipping_methods', [$method]);
}
