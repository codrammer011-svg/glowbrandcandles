<?php
/** Plugin Name: Glow Fundraiser Registration Success Popup */
defined('ABSPATH') || exit;

/**
 * One-time sign-in link for the "Go to My Dashboard" button in the registration popups.
 * It is only returned in the AJAX response of the registration itself, so only the browser
 * that submitted the form gets it. Single use, 30 minutes, never for staff/admin accounts.
 */
function glow_reg_autologin_url($user_id, $dashboard) {
    $user = get_userdata($user_id);
    // A logged-in fundraiser adding a seller must stay signed in as themselves.
    if (!$user || (is_user_logged_in() && get_current_user_id() !== (int) $user_id) || user_can($user, 'edit_others_posts') || user_can($user, 'manage_options')) {
        return $dashboard;
    }
    $token = wp_generate_password(40, false, false);
    update_user_meta($user_id, '_glow_reg_autologin', array('hash' => wp_hash($token), 'expires' => time() + 30 * MINUTE_IN_SECONDS, 'to' => $dashboard));
    return add_query_arg(array('glow_reg_login' => $token, 'u' => (int) $user_id), home_url('/'));
}

add_action('init', function() {
    if (empty($_GET['glow_reg_login']) || empty($_GET['u'])) { return; }
    $user_id = absint($_GET['u']);
    $token = sanitize_text_field(wp_unslash($_GET['glow_reg_login']));
    $saved = get_user_meta($user_id, '_glow_reg_autologin', true);
    $to = is_array($saved) && !empty($saved['to']) ? wp_validate_redirect($saved['to'], home_url('/my-account/')) : home_url('/my-account/');
    $user = get_userdata($user_id);
    $valid = $user && is_array($saved) && !empty($saved['hash']) && time() <= (int) $saved['expires'] && hash_equals($saved['hash'], wp_hash($token))
        && !user_can($user, 'edit_others_posts') && !user_can($user, 'manage_options');
    if ($valid) {
        delete_user_meta($user_id, '_glow_reg_autologin');
        if (get_current_user_id() !== $user_id) {
            wp_clear_auth_cookie();
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id, false, is_ssl());
            do_action('wp_login', $user->user_login, $user);
        }
    }
    // Invalid or expired links fall through to the dashboard, which asks them to log in.
    nocache_headers();
    wp_safe_redirect($to);
    exit;
}, 1);

add_filter('user_registration_success_params_before_send_json', function($response, $fields, $form_id, $user_id) {
    if (!empty($response['payment_process'])) { return $response; }
    // Seller form: the share popup (theme seller-share-popup.php) reads this link.
    if ((int) $form_id === 62307) {
        $response['glow_seller_dashboard'] = glow_reg_autologin_url($user_id, home_url('/seller-dashboard/'));
        return $response;
    }
    if (!in_array((int) $form_id, array(62316, 62179), true)) { return $response; }
    $user = get_userdata($user_id);
    if (!$user) { return $response; }
    $parts = explode('@', $user->user_email, 2);
    $first = function_exists('mb_substr') ? mb_substr($parts[0], 0, 1) : substr($parts[0], 0, 1);
    $masked = $first . '***@' . ($parts[1] ?? '');
    $response['glow_fundraiser_success'] = array(
        'id' => (int) $user_id,
        'email' => $masked,
        'dashboard' => glow_reg_autologin_url($user_id, home_url('/fundraiser-dashboard/')),
    );
    return $response;
}, 100, 4);

add_action('wp_enqueue_scripts', function() {
    $base = get_stylesheet_directory();
    $uri = get_stylesheet_directory_uri();
    foreach (array('/css/registration-success-popup.css', '/js/registration-success-popup.js') as $file) {
        if (!is_file($base . $file)) { return; }
    }
    wp_enqueue_style('glow-registration-success-popup', $uri . '/css/registration-success-popup.css', array(), filemtime($base . '/css/registration-success-popup.css'));
    wp_enqueue_script('glow-registration-success-popup', $uri . '/js/registration-success-popup.js', array('jquery'), filemtime($base . '/js/registration-success-popup.js'), true);
    wp_localize_script('glow-registration-success-popup', 'glowRegistrationPopup', array('logo' => get_theme_file_uri('/images/FINAL_2_Logo_Glow_2_300x300_transperant_40.png')));
});
