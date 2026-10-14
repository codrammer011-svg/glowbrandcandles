<?php
defined('ABSPATH') || exit;

/** Catalog entry supplies seller data on the cart line, unlike supporter shopping. */
function glow_fc_cart_is_catalog() {
    if (!function_exists('WC') || !WC()->cart || !in_array('fundraiser', (array) wp_get_current_user()->roles, true)) return false;
    foreach (WC()->cart->get_cart() as $item) {
        if (!empty($item['glow_seller_order_id']) || !empty($item['wdm_seller_name'])) return true;
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
