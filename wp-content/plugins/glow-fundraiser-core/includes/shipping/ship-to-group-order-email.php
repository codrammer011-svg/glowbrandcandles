<?php
/**
 * Emails for the combined Ship to Group order (close out, cron and 30-day auto close out).
 *
 * That order is created straight into the custom status "Processing call", which has no
 * WooCommerce email, so neither the admin nor the chairperson heard about it. When it is
 * created, send WooCommerce's own "New order" email (admin) and order confirmation email
 * (the chairperson, who is the order's billing contact).
 */

defined('ABSPATH') || exit;

/** Whether an order is a combined Ship to Group order. */
function glow_is_ship_to_group_order($order) {
    if (!$order instanceof WC_Order) {
        return false;
    }
    // The merge code saves this with update_post_meta() after creating the order object.
    return '' !== trim((string) ($order->get_meta('ship_orders_ids') ?: get_post_meta($order->get_id(), 'ship_orders_ids', true)));
}

add_action('woocommerce_order_status_pending_to_processing-call', 'glow_ship_to_group_order_emails', 20, 2);

function glow_ship_to_group_order_emails($order_id, $order = null) {
    $order = $order instanceof WC_Order ? $order : wc_get_order($order_id);
    if (!glow_is_ship_to_group_order($order)) {
        return;
    }
    // This runs while the close out is still saving the order, so a failure inside an
    // email (e.g. a plugin hook choking on a deleted product) must not stop the close out.
    $emails = WC()->mailer()->get_emails();
    foreach (array('WC_Email_New_Order', 'WC_Email_Customer_Processing_Order') as $class) {
        if (empty($emails[$class])) {
            continue;
        }
        $buffers = ob_get_level();
        try {
            $emails[$class]->trigger($order->get_id(), $order);
        } catch (\Throwable $e) {
            while (ob_get_level() > $buffers) {
                ob_end_clean(); // drop the half-rendered email template
            }
            error_log(sprintf('glow_ship_to_group_order_emails: %s for order %d failed: %s', $class, $order->get_id(), $e->getMessage()));
            $order->add_order_note(sprintf('Ship to Group email (%s) could not be sent: %s', $class, $e->getMessage()));
        }
    }
}

/** The fundraiser this combined order belongs to. */
function glow_ship_to_group_fundraiser_id($order) {
    return (int) ($order->get_meta('fundraiser_field') ?: get_post_meta($order->get_id(), 'fundraiser_field', true));
}

add_filter('woocommerce_email_subject_new_order', function ($subject, $order) {
    if (glow_is_ship_to_group_order($order)) {
        return sprintf('[GLOW] New Ship to Group order #%d - Fundraiser #%d', $order->get_id(), glow_ship_to_group_fundraiser_id($order));
    }
    return $subject;
}, 20, 2);

add_filter('woocommerce_email_subject_customer_processing_order', function ($subject, $order) {
    if (glow_is_ship_to_group_order($order)) {
        return sprintf('Your Ship to Group order #%d has been received', $order->get_id());
    }
    return $subject;
}, 20, 2);

add_filter('woocommerce_email_heading_customer_processing_order', function ($heading, $order) {
    if (glow_is_ship_to_group_order($order)) {
        return 'Your Ship to Group order';
    }
    return $heading;
}, 20, 2);
