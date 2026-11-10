<?php
defined('ABSPATH') || exit;

/** Catalog entry supplies seller data on the cart line, unlike supporter shopping. */
function glow_fc_cart_is_catalog() {
    if (!function_exists('WC') || !WC()->cart || !in_array('fundraiser', (array) wp_get_current_user()->roles, true)) return false;
    foreach (WC()->cart->get_cart() as $item) {
        if (($item['glow_order_type'] ?? '') === 'Catlog' || !empty($item['glow_seller_order_id']) || !empty($item['wdm_seller_name'])) return true;
    }
    return false;
}

add_action('woocommerce_cart_calculate_fees', function () {
    if (!function_exists('WC') || !WC()->cart || WC()->cart->is_empty()) return;
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) @session_start();
    $_SESSION['sellername'] = glow_fc_cart_is_catalog() ? 'Catlog' : 'Online';
}, 0);

// Keep a recovery snapshot before legacy checkout code reserves the credit.
add_action('woocommerce_checkout_update_order_meta', function ($order_id) {
    $order = wc_get_order($order_id);
    if (!$order || !glow_fc_cart_is_catalog()) return;
    $user_id = $order->get_customer_id();
    if (!$user_id || get_user_meta($user_id, 'glow_profit_deduct_user_metakey_yes', true)) return;
    $has_credit = false;
    foreach ($order->get_items('fee') as $fee) if ($fee->get_name() === 'Online Profits Deduction' && $fee->get_total() < 0) $has_credit = true;
    if (!$has_credit) return;
    $keys = ['wp_meta_first_catlog_order_for_cron_job','glow_profit_deduct_user_metakey_amount','glow_profit_deduct_user_metakey_ordertype','glow_profit_deduct_user_metakey_orderID','glow_profit_deduct_user_metakey_yes','glow_profit_deduct_invoice_total_onlineOrder_rebate','glow_profit_deduct_invoice_total_shiptoschoolOrder_rebate','glow_profit_deduct_invoice_total_onlineOrder_sales','glow_profit_deduct_invoice_total_shiptoschoolOrder_sales','glow_profit_deduct_invoice_shiptoschoolOrder_percentageProfit','glow_profit_deduct_invoice_implement','user_status','user_rebate','user_rebate_remain_glowProfitDeduct','user_close_out_value','user_close_out_date_inactive','user_action_status'];
    $before = [];
    foreach ($keys as $key) $before[$key] = get_user_meta($user_id, $key, false);
    update_post_meta($order_id, '_glow_profit_user_before', $before);
}, 1);

function glow_fc_release_cancelled_profit_credit($order_id) {
    $order = wc_get_order($order_id);
    if (!$order) return;
    $user_id = $order->get_customer_id();
    $before = get_post_meta($order_id, '_glow_profit_user_before', true);
    if (!is_array($before) || (int)get_user_meta($user_id, 'glow_profit_deduct_user_metakey_orderID', true) !== (int)$order_id) return;
    foreach ($before as $key => $values) {
        delete_user_meta($user_id, $key);
        foreach ($values as $value) add_user_meta($user_id, $key, $value);
    }
    foreach (get_posts(['post_type'=>'close_out','post_status'=>'any','numberposts'=>-1,'meta_key'=>'_glow_profit_source_order','meta_value'=>$order_id]) as $closeout) {
        wp_update_post(['ID'=>$closeout->ID,'post_status'=>'draft']);
    }
    delete_post_meta($order_id, '_glow_profit_user_before');
}
add_action('woocommerce_order_status_cancelled', 'glow_fc_release_cancelled_profit_credit');
add_action('woocommerce_order_status_failed', 'glow_fc_release_cancelled_profit_credit');

/** True when the cart at checkout is the fundraiser's saved brochure order. */
function glow_fc_cart_is_saved_order($saved) {
    if (!is_array($saved) || !$saved || !WC()->cart) return false;
    $cart_qty = [];
    foreach (WC()->cart->get_cart() as $item) {
        // Seller orders sent to the cart carry the same flag but are not the saved order.
        if (($item['restore_saved_order'] ?? '') === 'Yes' && empty($item['glow_seller_order_id'])) return true;
        $product_id = (int)($item['product_id'] ?? 0);
        $cart_qty[$product_id] = ($cart_qty[$product_id] ?? 0) + (int)($item['quantity'] ?? 0);
    }
    // Saved at checkout and placed straight away: the lines are never flagged as restored.
    $saved_qty = [];
    foreach ($saved as $item) {
        $product_id = (int)($item['product_id'] ?? 0);
        $saved_qty[$product_id] = ($saved_qty[$product_id] ?? 0) + (int)($item['quantity'] ?? 0);
    }
    foreach ($saved_qty as $product_id => $qty) {
        if (($cart_qty[$product_id] ?? 0) < $qty) return false;
    }
    return true;
}

// The cart is only available here, but a declined card must not lose the saved order,
// so remember the match on the order and clear the save once the order is really placed.
add_action('woocommerce_checkout_create_order', function ($order) {
    $user_id = $order->get_customer_id();
    if ($user_id && glow_fc_cart_is_saved_order(get_user_meta($user_id, 'cart-restore', true))) {
        $order->update_meta_data('_glow_from_saved_order', 'yes');
    }
});

function glow_fc_clear_placed_saved_order($order_id) {
    $order = wc_get_order($order_id);
    if (!$order || $order->get_meta('_glow_from_saved_order') !== 'yes') return;
    delete_user_meta($order->get_customer_id(), 'cart-restore');
    $order->delete_meta_data('_glow_from_saved_order');
    $order->save_meta_data();
}
// Priority 1: legacy on-hold hooks can stop the request before later callbacks run.
foreach (['on-hold', 'processing', 'completed', 'waiting-call', 'processing-call'] as $glow_status) {
    add_action("woocommerce_order_status_{$glow_status}", 'glow_fc_clear_placed_saved_order', 1);
}
