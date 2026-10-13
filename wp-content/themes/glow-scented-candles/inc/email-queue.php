<?php
/** Registration email queue: processed after the response by Glow Email Worker. */
defined('ABSPATH') || exit;

add_action('user_registration_after_register_user_action', 'glow_fc_start_mail_intercept', 1, 3);
function glow_fc_start_mail_intercept($data, $form_id, $user_id) {
    $GLOBALS['_glow_fc_intercept'] = true;
    add_filter('pre_wp_mail', 'glow_fc_pre_mail_handler', 10, 2);
}

add_action('user_registration_after_register_user_action', 'glow_fc_stop_mail_intercept', 999, 3);
function glow_fc_stop_mail_intercept($data, $form_id, $user_id) {
    remove_filter('pre_wp_mail', 'glow_fc_pre_mail_handler', 10);
    unset($GLOBALS['_glow_fc_intercept']);
}

function glow_fc_pre_mail_handler($result, $atts) {
    if ($result !== null) { return $result; }
    // If the worker is unavailable, let WordPress send normally rather than lose mail.
    if (!function_exists('glow_email_queue_event')) { return null; }
    return glow_email_queue_event('glow_fc_async_mail', array(
        $atts['to'] ?? '', $atts['subject'] ?? '', $atts['message'] ?? '',
        $atts['headers'] ?? array(), $atts['attachments'] ?? array()
    ));
}

function glow_fc_queue_mail($to, $subject, $message, $headers = array(), $attachments = array()) {
    if (function_exists('glow_email_queue_event')) {
        return glow_email_queue_event('glow_fc_async_mail', array($to, $subject, $message, $headers, $attachments));
    }
    return wp_mail($to, $subject, $message, $headers, $attachments);
}

function glow_fc_queue_family_mail($user_id, $full_name) {
    if (function_exists('glow_email_queue_event')) {
        return glow_email_queue_event('glow_fc_family_email_cron', array($user_id, $full_name));
    }
    if (function_exists('send_email_after_few_mintues')) { send_email_after_few_mintues($user_id, $full_name); }
}
