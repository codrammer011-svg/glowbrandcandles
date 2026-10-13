<?php
/** Plugin Name: Glow Check Payment Information */
defined('ABSPATH') || exit;

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
