<?php
/** Plugin Name: Glow Fundraiser Registration Success Popup */
defined('ABSPATH') || exit;

add_filter('user_registration_success_params_before_send_json', function($response, $fields, $form_id, $user_id) {
    if (!in_array((int) $form_id, array(62316, 62179), true) || !empty($response['payment_process'])) { return $response; }
    $user = get_userdata($user_id);
    if (!$user) { return $response; }
    $parts = explode('@', $user->user_email, 2);
    $first = function_exists('mb_substr') ? mb_substr($parts[0], 0, 1) : substr($parts[0], 0, 1);
    $masked = $first . '***@' . ($parts[1] ?? '');
    $response['glow_fundraiser_success'] = array(
        'id' => (int) $user_id,
        'email' => $masked,
        'dashboard' => home_url('/fundraiser-dashboard/'),
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
