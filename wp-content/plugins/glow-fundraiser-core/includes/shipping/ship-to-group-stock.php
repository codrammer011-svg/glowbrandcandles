<?php
/**
 * Merged "Ship to Group" orders (close out, cron and 30-day auto close out) only re-pack
 * items from the customers' own ship-to-school orders, listed in ship_orders_ids. Those
 * orders already reduced stock when the customer paid, so the merged order must not
 * reduce it again when it moves from "Processing call" to Processing / Completed.
 */

defined('ABSPATH') || exit;

add_filter('woocommerce_can_reduce_order_stock', 'glow_ship_to_group_skip_stock', 10, 2);

function glow_ship_to_group_skip_stock($can_reduce, $order) {
    if ($can_reduce && $order instanceof WC_Order && '' !== trim((string) $order->get_meta('ship_orders_ids'))) {
        return false;
    }
    return $can_reduce;
}
