<?php
/** Plugin Name: Glow Products Route Compatibility */
defined('ABSPATH') || exit;
add_action('template_redirect', function() {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET' && ($_SERVER['REQUEST_METHOD'] ?? '') !== 'HEAD') { return; }
    $request = wp_parse_url(wp_unslash($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH);
    $old_path = wp_parse_url(home_url('/shop2/'), PHP_URL_PATH);
    if (untrailingslashit((string) $request) !== untrailingslashit($old_path)) { return; }
    $url = home_url('/products/');
    $query = wp_parse_url(wp_unslash($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_QUERY);
    if ($query) { $url .= '?' . $query; }
    wp_safe_redirect($url, 301, 'Glow Products Route');
    exit;
}, 1);
