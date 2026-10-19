<?php
/**
 * Plugin Name: Glow Sender Email
 * Description: Uses the Glow sender address for all outgoing WordPress mail.
 */
defined('ABSPATH') || exit;

function glow_sender_email_address() {
    return 'info@glowglowscentedcandles.com';
}
add_filter('wp_mail_from', 'glow_sender_email_address', PHP_INT_MAX);
add_filter('woocommerce_email_from_address', 'glow_sender_email_address', PHP_INT_MAX);
add_action('phpmailer_init', static function ($mailer) {
    $mailer->From = glow_sender_email_address();
    $mailer->Sender = glow_sender_email_address();
}, PHP_INT_MAX);

// Persist sender settings once the database is available, including SMTP connections.
function glow_migrate_sender_email_settings() {
    $email = glow_sender_email_address();
    if (get_option('glow_sender_email_migration') === $email) { return; }
    global $wpdb;
    $walk = static function ($value) use (&$walk, $email) {
        if (!is_array($value)) { return $value; }
        foreach ($value as $key => $item) {
            if (preg_match('/^(?:from_email|sender_email|mail_from|email_from)$/i', (string) $key)) {
                $value[$key] = $email;
            } else { $value[$key] = $walk($item); }
        }
        return $value;
    };
    $names = $wpdb->get_col("SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '%smtp%' OR option_name LIKE '%from%email%' OR option_name LIKE '%sender%'");
    $backup = array();
    $updates = array();
    foreach ($names as $name) {
        if (strpos($name, 'glow_sender_email_') === 0) { continue; }
        $old = get_option($name);
        $new = $walk($old);
        if (preg_match('/^(?:from_email|sender_email|mail_from|email_from)$/i', $name)) { $new = $email; }
        if ($name === 'wp_mail_smtp' && is_array($new)) {
            $new['mail']['from_email'] = $email;
            $new['mail']['from_email_force'] = true;
        }
        if ($new !== $old) { $backup[$name] = $old; $updates[$name] = $new; }
    }
    $backup['woocommerce_email_from_address'] = get_option('woocommerce_email_from_address');
    add_option('glow_sender_email_backup', $backup, '', false);
    foreach ($updates as $name => $value) { update_option($name, $value); }
    update_option('woocommerce_email_from_address', $email);
    update_option('glow_sender_email_migration', $email, false);
}
add_action('init', 'glow_migrate_sender_email_settings', 1);
