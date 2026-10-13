<?php
/**
 * Plugin Name: Glow Brochure Profit Policy
 * Description: Shared 150-item threshold for all brochure collections.
 */
defined('ABSPATH') || exit;
define('GLOW_BROCHURE_PROFIT_MIN_ITEMS', 150);

function glow_brochure_profit_rate($total_items) {
    return (int) $total_items >= GLOW_BROCHURE_PROFIT_MIN_ITEMS ? 50 : 40;
}

/** Count every product line, rather than the last line of a mixed brochure order. */
function glow_brochure_order_quantity($order) {
    if (!is_object($order) || !method_exists($order, 'get_items')) {
        $id = is_object($order) && isset($order->ID) ? $order->ID : $order;
        $order = function_exists('wc_get_order') && is_numeric($id) ? wc_get_order($id) : false;
    }
    if (!$order) {
        return 0;
    }
    $quantity = 0;
    foreach ($order->get_items('line_item') as $item) {
        $quantity += max(0, (int) $item->get_quantity());
    }
    return $quantity;
}
