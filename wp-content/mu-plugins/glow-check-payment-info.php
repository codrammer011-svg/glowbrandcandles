<?php
/** Plugin Name: Glow Check Payment Information */
defined('ABSPATH') || exit;

/** Check payments are for signed-in fundraiser accounts, never public/seller checkout. */
function glow_check_payment_available_gateways($gateways) {
    if (is_admin() && !(defined('DOING_AJAX') && DOING_AJAX)) return $gateways;
    $allowed = is_user_logged_in() && in_array('fundraiser', (array) wp_get_current_user()->roles, true);
    if (!$allowed) {
        unset($gateways['cheque']);
        return $gateways;
    }
    // Keep the enabled native gateway available even if an earlier display filter removed it.
    if (!isset($gateways['cheque']) && function_exists('WC') && WC()->payment_gateways()) {
        $registered = WC()->payment_gateways()->payment_gateways();
        if (isset($registered['cheque']) && $registered['cheque']->enabled === 'yes' && $registered['cheque']->is_available()) {
            $gateways['cheque'] = $registered['cheque'];
        }
    }
    return $gateways;
}
add_filter('woocommerce_available_payment_gateways', 'glow_check_payment_available_gateways', PHP_INT_MAX);

// Existing invoices retain historical settings, including the previous shop name.
add_filter('wpo_wcpdf_shop_name_settings_text', function($name, $document) {
    return 'Glow Brands LLC';
}, 20, 2);

function glow_check_payment_instructions() {
    return "Checks made payable to:\nGlow Brands LLC\n3702 Cavalier Drive\nSuite 100\nGarland TX 75042";
}

add_action('wpo_wcpdf_after_order_details', function($document_type, $order) {
    if ($document_type !== 'invoice' || !$order || $order->get_payment_method() !== 'cheque') {
        return;
    }
    echo '<div class="glow-check-payment-info"><p>' . nl2br(esc_html(glow_check_payment_instructions())) . '</p></div>';
}, 10, 2);
