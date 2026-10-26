<?php
/**
 * Plugin Name: Glow Admin CC
 * Description: Copies both admin inboxes on every email the site sends.
 */
defined('ABSPATH') || exit;

function glow_admin_cc_addresses() {
    return array('candace@glowscentedcandles.com', 'candacethompson12@gmail.com');
}

// Runs before the admin routing and the one-email-per-order filters (both PHP_INT_MAX),
// so those see the copy and can drop it when the admin already got that order's email.
add_filter('wp_mail', 'glow_admin_cc_every_email', PHP_INT_MAX - 10);

function glow_admin_cc_every_email($mail) {
    // Staging mail must not reach the real admin inboxes.
    if (0 === stripos((string) wp_parse_url(home_url(), PHP_URL_HOST), 'staging.')) {
        return $mail;
    }

    // Never copy emails that carry a password reset or account confirmation link.
    $subject = (string) ($mail['subject'] ?? '');
    if (preg_match('/password|confirm your email|verify your|verification code|login link/i', $subject)) {
        return $mail;
    }

    $headers  = $mail['headers'] ?? array();
    $is_array = is_array($headers);
    $lines    = $is_array ? $headers : preg_split('/\r\n|\r|\n/', (string) $headers);
    $lines    = array_values(array_filter($lines, 'strlen'));

    // Skip any admin address already in To, Cc or Bcc.
    $to      = is_array($mail['to'] ?? '') ? implode(',', $mail['to']) : (string) ($mail['to'] ?? '');
    $present = strtolower($to . ' ' . implode(' ', $lines));
    $missing = array();
    foreach (glow_admin_cc_addresses() as $address) {
        if (false === strpos($present, $address)) {
            $missing[] = $address;
        }
    }
    if (!$missing) {
        return $mail;
    }

    $lines[]         = 'Cc: ' . implode(', ', $missing);
    $mail['headers'] = $is_array ? $lines : implode("\r\n", $lines);
    return $mail;
}
