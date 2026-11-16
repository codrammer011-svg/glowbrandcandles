<?php
/**
 * When ShipStation marks an order shipped, the customer got two emails: the tracking
 * number (ShipStation's customer note) and WooCommerce's "Completed order" email from
 * the status change straight after it. Keep only the tracking email for those orders.
 * Orders completed by hand in admin still send "Completed order" as before.
 */

defined('ABSPATH') || exit;

add_action('woocommerce_shipstation_shipnotify', 'glow_shipstation_mark_shipped', 1, 1);

function glow_shipstation_mark_shipped($order) {
    if ($order instanceof WC_Order) {
        $GLOBALS['glow_shipstation_shipped'][$order->get_id()] = true;
    }
}

add_filter('woocommerce_email_enabled_customer_completed_order', 'glow_shipstation_skip_completed_email', 10, 2);

function glow_shipstation_skip_completed_email($enabled, $order) {
    if ($enabled && $order instanceof WC_Order && !empty($GLOBALS['glow_shipstation_shipped'][$order->get_id()])) {
        return false;
    }
    return $enabled;
}
