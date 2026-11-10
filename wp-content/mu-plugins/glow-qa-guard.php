<?php
/**
 * Plugin Name: Glow QA Guard (temporary)
 * Description: Keeps QA test data from leaving the site. Blocks emails, Twilio SMS and
 * ShipStation exports that involve QA-marked data ("QA TEST" names, @glow-qa.test
 * addresses, 555-01xx phones, or IDs listed in the glow_qa_ids option). Everything
 * else on the site is untouched. Remove this file to turn it off.
 */
defined('ABSPATH') || exit;

function glow_qa_ids() {
    $ids = get_option('glow_qa_ids', array());
    return is_array($ids) ? array_map('intval', $ids) : array();
}

function glow_qa_text_matches($text) {
    if (is_array($text)) {
        $text = implode(' ', array_map(function ($v) { return is_scalar($v) ? (string) $v : wp_json_encode($v); }, $text));
    }
    $text = (string) $text;
    if ($text === '') {
        return false;
    }
    if (stripos($text, 'QA TEST') !== false || stripos($text, 'glow-qa.test') !== false) {
        return true;
    }
    if (preg_match('/555[^0-9]{0,3}01\d\d/', $text)) {
        return true;
    }
    foreach (glow_qa_ids() as $id) {
        if ($id && preg_match('/(?<!\d)' . $id . '(?!\d)/', $text)) {
            return true;
        }
    }
    return false;
}

function glow_qa_request_flagged() {
    return !empty($_COOKIE['glow_qa']) || !empty($_SERVER['HTTP_X_GLOW_QA']) || (defined('GLOW_QA_RUN') && GLOW_QA_RUN);
}

function glow_qa_log($what, $detail) {
    $log = get_option('glow_qa_blocked_log', array());
    if (!is_array($log)) {
        $log = array();
    }
    $log[] = gmdate('Y-m-d H:i:s') . " | $what | " . substr(wp_strip_all_tags((string) $detail), 0, 160);
    update_option('glow_qa_blocked_log', array_slice($log, -300), false);
}

// Emails: return true so WordPress treats the email as sent without sending it.
add_filter('pre_wp_mail', function ($short, $atts) {
    if ($short !== null) {
        return $short;
    }
    $hit = glow_qa_request_flagged()
        || glow_qa_text_matches($atts['to'] ?? '')
        || glow_qa_text_matches($atts['subject'] ?? '')
        || glow_qa_text_matches($atts['message'] ?? '')
        || glow_qa_text_matches($atts['headers'] ?? '');
    if ($hit) {
        glow_qa_log('email', (is_array($atts['to'] ?? '') ? implode(',', $atts['to']) : ($atts['to'] ?? '')) . ' :: ' . ($atts['subject'] ?? ''));
        // The Glow email queue treats a delayed email as sent only after this action fires.
        do_action('wp_mail_succeeded', $atts);
        return true;
    }
    return $short;
}, 1, 2);

// Twilio SMS: answer with a fake "created" response for QA numbers or QA requests.
add_filter('pre_http_request', function ($pre, $args, $url) {
    if (stripos((string) $url, 'api.twilio.com') === false) {
        return $pre;
    }
    $body = $args['body'] ?? array();
    if (glow_qa_request_flagged() || glow_qa_text_matches($body)) {
        glow_qa_log('sms', is_array($body) ? ($body['To'] ?? '') : '');
        return array('headers' => array(), 'body' => '{"sid":"QA"}', 'response' => array('code' => 201, 'message' => 'Created'), 'cookies' => array(), 'filename' => null);
    }
    return $pre;
}, 1, 3);

function glow_qa_order_is_qa($order_id) {
    $order = function_exists('wc_get_order') ? wc_get_order($order_id) : false;
    if (!$order) {
        return false;
    }
    if (in_array((int) $order_id, glow_qa_ids(), true) || $order->get_meta('_glow_qa')) {
        return true;
    }
    if (in_array((int) $order->get_meta('fundraiser_field'), glow_qa_ids(), true)) {
        return true;
    }
    return glow_qa_text_matches(array($order->get_billing_email(), $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(), $order->get_billing_company()));
}

// ShipStation: leave QA orders out of every export.
add_filter('woocommerce_shipstation_export_order', function ($export, $order_id) {
    if ($export && glow_qa_order_is_qa($order_id)) {
        glow_qa_log('shipstation', 'skipped order ' . $order_id);
        return false;
    }
    return $export;
}, 1, 2);
