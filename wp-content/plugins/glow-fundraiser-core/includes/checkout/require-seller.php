<?php
defined('ABSPATH') || exit;

/**
 * A supporter who buys for a fundraiser must pick the seller to credit.
 *
 * The legacy check (wc_seller() in legacy/theme-functions.php) only runs when
 * $_SESSION['sellername'] is set, so orders slipped through with "Select Seller".
 * Fundraisers are skipped: their catalog orders carry the seller on each line.
 */
add_action('woocommerce_checkout_process', function () {
    if ('Yes' !== ($_POST['wc_checkbox_field'] ?? '')
        || in_array('fundraiser', (array) wp_get_current_user()->roles, true)) {
        return;
    }
    $seller = trim(sanitize_text_field(wp_unslash($_POST['wc_seller_field'] ?? '')));
    $custom = trim(sanitize_text_field(wp_unslash($_POST['wc_seller_field_custom'] ?? '')));

    // Same wording as the legacy check, so a shopper never sees the error twice.
    if ('' === $seller || 'No-seller' === $seller) {
        $message = 'Please Select Seller';
    } elseif ('custom' === $seller && '' === $custom) {
        $message = 'Please Enter Seller Name';
    } else {
        return;
    }
    if (!wc_has_notice($message, 'error')) {
        wc_add_notice($message, 'error');
    }
}, 20);
