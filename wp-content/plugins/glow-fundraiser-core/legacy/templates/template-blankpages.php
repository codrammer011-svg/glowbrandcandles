<?php
/** Template Name: Print Blank Pages */
defined('ABSPATH') || exit;
if (!is_user_logged_in()) { auth_redirect(); exit; }
$printing = ($_GET['q'] ?? 'blank') === 'print';
$order_id = absint($printing ? ($_POST['order_id'] ?? 0) : ($_GET['order_id'] ?? 0));
$order = $order_id ? wc_get_order($order_id) : false;
if (!$order) { wp_die('A valid order ID is required.', 'Order not found', array('response' => 404)); }
$uid = get_current_user_id();
$roles = (array) wp_get_current_user()->roles;
$allowed = current_user_can('manage_woocommerce') || current_user_can('manage_options')
    || (in_array('fundraiser', $roles, true) && (int) $order->get_meta('fundraiser_field') === $uid)
    || (in_array('seller', $roles, true) && (int) $order->get_meta('wc_seller_field') === $uid);
if (!$allowed) { wp_die('You cannot print pages for this order.', 'Access denied', array('response' => 403)); }
nocache_headers();
$sid = $order->get_meta('wc_seller_field');
$seller = is_numeric($sid) ? get_userdata((int) $sid) : false;
$seller_name = $seller ? trim($seller->first_name . ' ' . $seller->last_name) : $order->get_meta('wc_seller_field_custom');
if (!$seller_name) { $seller_name = $order->get_formatted_billing_full_name(); }
$pages = 1;
if ($printing) {
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') { wp_die('Open the form before printing.', 'Invalid request', array('response' => 400)); }
    check_admin_referer('glow_blank_pages_' . $order_id);
    $pages = filter_var(wp_unslash($_POST['no_of_pages'] ?? ''), FILTER_VALIDATE_INT, array('options' => array('min_range' => 1, 'max_range' => 100)));
    $seller_name = sanitize_text_field(wp_unslash($_POST['seller_name'] ?? ''));
    if ($pages === false || trim($seller_name) === '') { wp_die('Enter a seller name and a page count between 1 and 100.', 'Invalid print details', array('response' => 400)); }
}
?>
<!doctype html><html <?php language_attributes(); ?>><head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Blank pages — Order #<?php echo esc_html($order_id); ?></title>
<style>
body{font:16px Arial,sans-serif;color:#222;margin:32px;background:#f8f7f5}main{max-width:650px;margin:auto;background:white;padding:28px;border-radius:12px}label{display:block;margin:20px 0 6px;font-weight:bold}input{box-sizing:border-box;width:100%;padding:10px;font:inherit;border:1px solid #aaa;border-radius:4px}button{margin-top:20px;padding:12px 20px;font:inherit;cursor:pointer;background:#28366f;color:white;border:0;border-radius:4px}.notice{padding:12px;background:#fff3cd}.actions{margin:0 auto 24px;max-width:650px}.actions button{margin:0 16px 0 0}.print-sheet{box-sizing:border-box;width:4in;min-height:8in;margin:0 auto 24px;background:white;padding:24px;border:1px solid #ddd;break-after:page}.print-sheet:last-child{break-after:auto}.print-sheet h2{font-size:20px;margin:0 0 12px}.line{border-top:1px dashed #333}.unpaid{font-size:12px;color:#9b3600}
@media print{@page{size:4in 8in;margin:0}body{margin:0;background:white}.actions{display:none}.print-sheet{width:4in;height:8in;min-height:0;margin:0;border:0;overflow:hidden}}
</style></head><body>
<?php if (!$printing) : ?>
<main><a href="<?php echo esc_url(add_query_arg('order_id', $order_id, home_url('/print-order/'))); ?>">Back to order printout</a><h1>Print blank pages</h1>
<p>Generate seller-labelled blank pages for order #<?php echo esc_html($order_id); ?>.</p>
<?php if (!$order->get_date_paid()) : ?><p class="notice">This order is unpaid. Printing does not mark payment received or authorize shipment.</p><?php endif; ?>
<form method="post" action="<?php echo esc_url(add_query_arg('q', 'print', get_permalink())); ?>">
<?php wp_nonce_field('glow_blank_pages_' . $order_id); ?>
<label for="order_id">Order ID</label><input type="number" id="order_id" name="order_id" value="<?php echo esc_attr($order_id); ?>" readonly required>
<label for="seller_name">Seller name</label><input type="text" id="seller_name" name="seller_name" value="<?php echo esc_attr($seller_name); ?>" maxlength="150" required>
<label for="no_of_pages">Number of blank pages</label><input type="number" id="no_of_pages" name="no_of_pages" value="1" min="1" max="100" step="1" required>
<button type="submit">Generate pages</button></form></main>
<?php else : ?>
<div class="actions"><button type="button" onclick="window.print()">Print pages</button><a href="<?php echo esc_url(add_query_arg(array('order_id' => $order_id, 'q' => 'blank'), get_permalink())); ?>">Back to form</a></div>
<?php for ($i = 0; $i < $pages; $i++) : ?>
<section class="print-sheet"><h2><?php echo esc_html($seller_name . ' — #' . $order_id); ?></h2><div class="line"></div><?php if (!$order->get_date_paid()) : ?><p class="unpaid">UNPAID — NOT AUTHORIZED FOR SHIPMENT</p><?php endif; ?></section>
<?php endfor; endif; ?>
</body></html>
