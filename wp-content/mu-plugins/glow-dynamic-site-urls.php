<?php
/** Plugin Name: Glow Dynamic Site URLs */
defined('ABSPATH') || exit;

function glow_dynamic_url_base($kind = 'home') {
    switch ($kind) {
        case 'uploads': return trailingslashit(wp_get_upload_dir()['baseurl']);
        case 'theme': return trailingslashit(get_stylesheet_directory_uri());
        case 'content': return trailingslashit(content_url());
        case 'admin': return trailingslashit(admin_url());
        case 'root': return untrailingslashit(home_url());
        default: return trailingslashit(home_url());
    }
}

// Available to interpolated email bodies and legacy heredoc templates.
foreach (array('uploads', 'theme', 'content', 'admin', 'root', 'home') as $kind) {
    $GLOBALS['glow_dynamic_url_bases'][$kind] = glow_dynamic_url_base($kind);
}

add_action('wp_head', function() {
    $uploads = glow_dynamic_url_base('uploads');
    echo '<style id="glow-profile-asset-urls">:root{--glow-profile-pattern:url(' . wp_json_encode($uploads . '2021/11/3135715.png') . ');--glow-profile-artwork:url(' . wp_json_encode($uploads . '2025/10/84566287_9977709-removebg-preview-1.png') . ');}</style>';
}, 20);
