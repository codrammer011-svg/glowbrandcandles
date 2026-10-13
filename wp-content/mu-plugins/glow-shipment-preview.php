<?php
/** Plugin Name: Glow Shipment Print Preview
 * Description: Shows an explanatory seller-wise preview when no merged shipment exists.
 */
defined('ABSPATH') || exit;
add_action('template_redirect', function () {
    if (!is_page('ship-print-order')) { return; }
    $fid = absint($_GET['fund_id'] ?? 0);
    if (!is_user_logged_in()) { auth_redirect(); exit; }
    $admin = current_user_can('manage_options');
    $owner = get_current_user_id() === $fid && in_array('fundraiser', (array) wp_get_current_user()->roles, true);
    if (!$admin && !$owner) { wp_die('You cannot view this fundraiser shipment.', 'Access denied', array('response' => 403)); }
    $fundraiser = get_userdata($fid);
    if (!$fundraiser || !in_array('fundraiser', (array) $fundraiser->roles, true)) {
        wp_die('A valid fundraiser ID is required.', 'Invalid fundraiser', array('response' => 400));
    }
    $merged = absint(get_user_meta($fid, 'wc_ship_mergeOrderID', true));
    if ($merged && wc_get_order($merged)) {
        if (!$admin) { wp_die('The merged shipment printout is available to administrators.'); }
        return; // Keep the existing merged-shipment print flow.
    }
    nocache_headers();
    $orders = wc_get_orders(array('limit' => -1, 'status' => array('on-hold', 'pending', 'waiting-call', 'processing-call', 'processing', 'completed'), 'meta_key' => 'fundraiser_field', 'meta_value' => (string) $fid));
    $rows = array();
    foreach ($orders as $order) {
        if (!in_array($order->get_meta('wc_order_type'), array('Catlog', 'ship-to-school'), true)) { continue; }
        $sid = $order->get_meta('wc_seller_field');
        $seller = is_numeric($sid) ? get_userdata((int) $sid) : false;
        $name = $seller ? trim($seller->first_name . ' ' . $seller->last_name) : $order->get_meta('wc_seller_field_custom');
        $rows[] = array('order' => $order, 'seller' => $name ?: 'Group / unassigned seller');
    }
    usort($rows, function ($a, $b) { return strcasecmp($a['seller'], $b['seller']); });
    $compiled = ($_GET['q'] ?? '') === 'compile';
    $sheets = array();
    foreach ($rows as $row) {
        $order = $row['order'];
        $key = $compiled ? strtolower($row['seller']) : (string) $order->get_id();
        if (!isset($sheets[$key])) {
            $sheets[$key] = array('seller' => $row['seller'], 'orders' => array(), 'customers' => array(), 'items' => array(), 'quantity' => 0, 'unpaid' => false);
        }
        $sheet = &$sheets[$key];
        $sheet['orders'][] = $order->get_id();
        $sheet['customers'][] = $order->get_formatted_billing_full_name();
        $sheet['unpaid'] = $sheet['unpaid'] || !$order->get_date_paid();
        foreach ($order->get_items() as $item) {
            $item_key = $item->get_product_id() . '|' . $item->get_variation_id() . '|' . $item->get_name();
            if (!isset($sheet['items'][$item_key])) { $sheet['items'][$item_key] = array('name' => $item->get_name(), 'quantity' => 0); }
            $sheet['items'][$item_key]['quantity'] += $item->get_quantity();
            $sheet['quantity'] += $item->get_quantity();
        }
        unset($sheet);
    }
    // Reuse the original template's stylesheet for the existing print layout.
    $original = WP_PLUGIN_DIR . '/glow-fundraiser-core/legacy/templates/tempate-ship-printorder.php';
    $css = '';
    if (is_readable($original) && preg_match('/<style>(.*?)<\/style>/s', file_get_contents($original), $match)) { $css = $match[1]; }
    ?>
    <!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Print Order</title>
    <style><?php echo $css; ?>
    .printPage{min-height:540px;break-after:page}.printPage:last-child{break-after:auto}.preview-status{font-size:11px;margin:6px 0;color:#8b3100}
    @media print{#print_button,#print_button2,.totalSeller{display:none}body{margin:0;background:white!important}#printTable{padding:25px!important}.printPage{break-inside:avoid}}
    </style></head><body style="background:#f2f2f2">
    <span id="print_button" role="button" tabindex="0" onclick="window.print()" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();window.print();}">Print Out</span>
    <span id="print_button2"><a href="<?php echo esc_url(add_query_arg(array('fund_id' => $fid, 'q' => $compiled ? 'individual' : 'compile'), get_permalink())); ?>"><?php echo $compiled ? 'Individual' : 'Compile'; ?></a></span>
    <div class="totalSeller">Total Sellers: <?php echo esc_html(count(array_unique(array_column($rows, 'seller')))); ?></div>
    <div style="font-family:Arial,sans-serif;width:384px;margin:0 auto"><div id="printTable" style="margin:0 auto;background:#fff;padding:25px;font-size:11pt">
    <?php $page = 0; foreach ($sheets as $sheet) : $page++; ?>
    <div class="printPage">
        <div class="billing-details"><h5><?php echo esc_html('FUND ID #' . $fid . ', ' . (get_user_meta($fid, 'user_registration_name_of_school_business', true) ?: $fundraiser->display_name)); ?></h5>
        <h5><?php echo esc_html(implode(', ', array_filter(array(get_user_meta($fid, 'user_registration_street_address', true), get_user_meta($fid, 'user_registration_City2', true), get_user_meta($fid, 'user_registration_select_state', true), get_user_meta($fid, 'user_registration_input_box_zip', true))))); ?></h5><span class="border-line"></span></div>
        <div class="SellerName page-incr"><h5><?php echo esc_html('Seller Name: ' . $sheet['seller']); ?> <span class="pageNo">(Page <?php echo esc_html($page); ?>)</span></h5>
        <h5><?php echo esc_html('Customer Name: ' . implode(', ', array_unique($sheet['customers']))); ?></h5><h5><?php echo esc_html('Order #ID: ' . implode(', ', $sheet['orders'])); ?></h5><span class="border-line"></span></div>
        <p class="preview-status"><?php echo $sheet['unpaid'] ? 'UNPAID — DO NOT SHIP' : 'PREVIEW — no merged shipment exists'; ?></p>
        <?php foreach ($sheet['items'] as $item) : ?><p class="productName"><span class="pName"><span class="p-name"><?php echo esc_html($item['quantity'] . ' ' . $item['name']); ?></span></span></p><?php endforeach; ?>
        <span class="border-line border-line-2"></span><h4 class="item_sold">Total Items Sold: <?php echo esc_html($sheet['quantity']); ?></h4><span class="border-line border-line-2"></span>
    </div><?php endforeach; ?>
    <?php if (!$sheets) : ?><p>No catalog or ship-to-group orders found for this fundraiser.</p><?php endif; ?>
    </div></div></body></html>
    <?php exit;
}, 1);
