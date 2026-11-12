<?php
/**
 * When ShipStation ships a fundraiser's brochure kit (the $0 "Fundraiser product"
 * order), email the tracking number and carrier link to the referral contact saved
 * on the fundraiser (user_registration_referral_email).
 */

defined('ABSPATH') || exit;

add_action('woocommerce_shipstation_shipnotify', 'glow_referral_tracking_email', 20, 2);

function glow_referral_tracking_email($order, $tracking) {
    if (!$order instanceof WC_Order || !function_exists('glow_fundraiser_shipping_product')) {
        return;
    }
    $number = trim((string) ($tracking['tracking_number'] ?? ''));
    $carrier = trim((string) ($tracking['carrier'] ?? ''));
    $product = glow_fundraiser_shipping_product();
    if ('' === $number || !$product) {
        return;
    }

    $is_kit = false;
    foreach ($order->get_items() as $item) {
        if ((int) $item->get_product_id() === $product->get_id()) {
            $is_kit = true;
        }
    }
    // Join Us kits use the same product but carry no fundraiser_field, so they are skipped.
    $fundraiser_id = (int) (get_post_meta($order->get_id(), 'fundraiser_field', true) ?: $order->get_meta('fundraiser_field'));
    $fundraiser = $fundraiser_id ? get_userdata($fundraiser_id) : false;
    if (!$is_kit || !$fundraiser || !in_array('fundraiser', (array) $fundraiser->roles, true)) {
        return;
    }

    $to = sanitize_email(trim((string) get_user_meta($fundraiser_id, 'user_registration_referral_email', true)));
    if (!is_email($to)) {
        return;
    }

    // ShipStation retries notifications; send once per tracking number.
    $sent = (array) $order->get_meta('_glow_referral_tracking_sent');
    if (in_array($number, $sent, true)) {
        return;
    }

    $org = trim((string) get_user_meta($fundraiser_id, 'user_registration_name_of_school_business', true)) ?: $fundraiser->display_name;
    $name = trim((string) get_user_meta($fundraiser_id, 'user_registration_referral_name', true));
    $url = glow_tracking_url($carrier, $number);
    $e = 'esc_html';

    $ship_to = implode(', ', array_filter(array(
        $order->get_shipping_address_1(),
        $order->get_shipping_city(),
        trim($order->get_shipping_state() . ' ' . $order->get_shipping_postcode()),
    )));

    $body = '<div style="font-family:Arial,sans-serif;font-size:15px;color:#222;max-width:600px;">'
        . '<p>Hi ' . $e($name ?: 'there') . ',</p>'
        . '<p>The brochure kit for <b>' . $e($org) . '</b> (Fundraiser #' . $fundraiser_id . ') has shipped.</p>'
        . '<table cellpadding="6" style="border-collapse:collapse;">'
        . '<tr><td><b>Carrier</b></td><td>' . $e($carrier ?: 'Carrier') . '</td></tr>'
        . '<tr><td><b>Tracking number</b></td><td>' . $e($number) . '</td></tr>'
        . ($ship_to ? '<tr><td><b>Shipping to</b></td><td>' . $e($ship_to) . '</td></tr>' : '')
        . '<tr><td><b>Order</b></td><td>#' . $e($order->get_order_number()) . '</td></tr>'
        . '</table>'
        . ($url ? '<p style="margin:24px 0;"><a href="' . esc_url($url) . '" style="background:#e91e63;color:#fff;padding:12px 22px;border-radius:6px;text-decoration:none;font-weight:bold;">Track this package</a></p>' : '')
        . '<p style="color:#555;">The Glow Team</p></div>';

    $subject = 'Brochure kit shipped: ' . $org . ' (#' . $fundraiser_id . ')';
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . (defined('GLOW_FROM_EMAIL') ? GLOW_FROM_EMAIL : 'Glow Scented Candles <info@glowscentedcandles.com>'),
    );

    $queued = function_exists('glow_fc_queue_mail')
        ? glow_fc_queue_mail($to, $subject, $body, $headers)
        : wp_mail($to, $subject, $body, $headers);
    if (false === $queued) {
        return;
    }

    $sent[] = $number;
    $order->update_meta_data('_glow_referral_tracking_sent', $sent);
    $order->save_meta_data();
    $order->add_order_note(sprintf('Shipping email with tracking %s sent to referral contact %s.', $number, $to));
}

/** Public tracking page for the carrier ShipStation reports, or '' if unknown. */
function glow_tracking_url($carrier, $number) {
    $n = rawurlencode($number);
    $c = strtolower($carrier);
    if (false !== strpos($c, 'ups') || 0 === stripos($number, '1Z')) {
        return 'https://www.ups.com/track?tracknum=' . $n;
    }
    if (false !== strpos($c, 'usps') || false !== strpos($c, 'stamps')) {
        return 'https://tools.usps.com/go/TrackConfirmAction?tLabels=' . $n;
    }
    if (false !== strpos($c, 'fedex')) {
        return 'https://www.fedex.com/fedextrack/?trknbr=' . $n;
    }
    if (false !== strpos($c, 'dhl')) {
        return 'https://www.dhl.com/us-en/home/tracking/tracking-express.html?submit=1&tracking-id=' . $n;
    }
    return '';
}
