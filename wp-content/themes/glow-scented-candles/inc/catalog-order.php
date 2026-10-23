<?php
/**
 * Catalog Order – type meta, cookies, email blocking
 * Auto-split from functions.php – edit this file, not the monolith.
 */

/**
 * Force wc_order_type = 'Catlog' for brochure-catalog (Spring/Summer) orders.
 *
 * The catalogue "Add order to cart" sets a massSellerName cookie. The core writer
 * (gsc_select_checkout_field_update_order_meta) sets wc_order_type from
 * $_SESSION['sellername'], but PHP sessions don't reliably persist between the
 * AJAX add-to-cart request and the checkout request, so brochure orders were
 * landing on 'Online'. Reading the cookie at checkout (after that writer, priority
 * 99) fixes it reliably.
 */
add_action('woocommerce_checkout_update_order_meta', 'glow_force_catalog_order_type', 99, 1);
function glow_force_catalog_order_type($order_id)
{
    if ( ! empty($_COOKIE['massSellerName']) ) {
        update_post_meta($order_id, 'wc_order_type', 'Catlog');
    }
}

/**
 * Clear the catalogue seller cookies once an order is placed, so they can't
 * mis-tag a subsequent online order as Catlog.
 */
add_action('woocommerce_thankyou', 'glow_clear_catalog_cookies', 5, 1);
function glow_clear_catalog_cookies($order_id)
{
    ?>
    <script>
    document.cookie = "massSellerName=; path=/; max-age=0";
    document.cookie = "massSellerDisplay=; path=/; max-age=0";
    </script>
    <?php
}

/**
 * Suppress User Registration's generic "Registration Success" email
 * ("Welcome to {site}! Your registration is complete…") for FUNDRAISER
 * registrations only, since they now receive the two branded fundraiser
 * emails instead. Sellers and any other registration still get the default.
 *
 * Uses pre_wp_mail so it works regardless of which hook/priority triggers the
 * mail. Returning true short-circuits wp_mail() and reports success, so UR's
 * mail logging/notices stay happy.
 */
add_filter('pre_wp_mail', 'glow_block_generic_welcome_for_fundraisers', 10, 2);
function glow_block_generic_welcome_for_fundraisers($short_circuit, $atts)
{
    // Respect any other plugin that already short-circuited the send.
    if (null !== $short_circuit) {
        return $short_circuit;
    }

    $message = (is_array($atts) && isset($atts['message'])) ? (string) $atts['message'] : '';
    // Signature phrase unique to UR's "successfully registered" template.
    if ($message === '' || strpos($message, 'Get started by visiting your account dashboard') === false) {
        return $short_circuit; // Not the generic UR success email — leave it alone.
    }

    $to = (is_array($atts) && isset($atts['to'])) ? $atts['to'] : '';
    $emails = is_array($to) ? $to : array($to);

    foreach ($emails as $addr) {
        $addr = is_string($addr) ? trim($addr) : '';
        if ($addr !== '' && preg_match('/<([^>]+)>/', $addr, $m)) {
            $addr = trim($m[1]); // pull address out of "Name <email>"
        }
        $user = $addr !== '' ? get_user_by('email', $addr) : false;
        if (!$user) {
            continue;
        }
        $is_seller     = in_array('seller', (array) $user->roles, true);
        $is_fundraiser = get_user_meta($user->ID, 'user_registration_name_of_school_business', true)
            || get_user_meta($user->ID, 'user_registration_fundraiser_preference', true);

        // Block only for a fundraiser that is not a seller.
        if ($is_fundraiser && !$is_seller) {
            return true; // Short-circuit: treat as "sent" so nothing actually goes out.
        }
    }

    return $short_circuit;
}

/**
 * Show the old-site order number for orders that were renumbered by the
 * Oct 2026 migration (old ID kept in _glow_production_original_id).
 */
function glow_order_display_id($order_id)
{
    $original_id = get_post_meta((int) $order_id, '_glow_production_original_id', true);
    if ($original_id !== '' && in_array(get_post_type((int) $order_id), array('shop_order', 'shop_order_refund'), true)) {
        return (string) $original_id;
    }

    return (string) $order_id;
}

/**
 * Turn an order number someone typed (old or new) into the order's post ID.
 */
function glow_order_id_from_number($number)
{
    global $wpdb;

    $number = absint($number);
    if (!$number || get_post_type($number) === 'shop_order') {
        return $number;
    }
    $order_id = $wpdb->get_var($wpdb->prepare(
        "SELECT m.post_id FROM {$wpdb->postmeta} m JOIN {$wpdb->posts} p ON p.ID = m.post_id
         WHERE m.meta_key = '_glow_production_original_id' AND m.meta_value = %s AND p.post_type = 'shop_order' LIMIT 1",
        (string) $number
    ));

    return $order_id ? (int) $order_id : $number;
}

add_filter('woocommerce_order_number', 'glow_production_order_number', 10, 2);
function glow_production_order_number($order_number, $order)
{
    if ($order instanceof WC_Order && (string) $order_number === (string) $order->get_id()) {
        return glow_order_display_id($order->get_id());
    }

    return $order_number;
}

/**
 * The legacy subject filters build "(# ID)" from the post ID, so swap in the
 * displayed order number.
 */
foreach (array('new_order', 'customer_processing_order', 'customer_on_hold_order', 'customer_completed_order', 'customer_invoice') as $glow_email_id) {
    add_filter('woocommerce_email_subject_' . $glow_email_id, 'glow_production_order_email_subject', 99, 2);
}
function glow_production_order_email_subject($subject, $order)
{
    if ($order instanceof WC_Order && $order->get_order_number() !== (string) $order->get_id()) {
        $subject = str_replace('(# ' . $order->get_id() . ')', '(# ' . $order->get_order_number() . ')', $subject);
    }

    return $subject;
}

/* Admin order search: an old order number finds the renumbered order. */
add_filter('woocommerce_shop_order_search_results', 'glow_production_order_search', 10, 2);
function glow_production_order_search($order_ids, $term)
{
    $term = trim(ltrim((string) $term, '#'));
    if (ctype_digit($term)) {
        $order_id = glow_order_id_from_number($term);
        if ($order_id !== (int) $term) {
            $order_ids[] = $order_id;
        }
    }

    return array_unique($order_ids);
}

/* ShipStation shipment updates: an old order number/ID finds the renumbered order. */
add_filter('woocommerce_shipstation_get_order_id_from_order_number', 'glow_production_shipstation_order_id', 10, 2);
function glow_production_shipstation_order_id($order_id, $order_number)
{
    if (get_post_type((int) $order_id) === 'shop_order') {
        return $order_id;
    }

    return glow_order_id_from_number($order_number);
}
