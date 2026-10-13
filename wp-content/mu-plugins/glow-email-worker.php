<?php
/** Plugin Name: Glow Email Worker
 * Description: Durable registration email queue processed after the HTTP response, without WP-Cron.
 */
defined('ABSPATH') || exit;

function glow_email_queue_event($hook, $args, $timestamp = null) {
    $name = 'glow_mail_queue_' . wp_generate_uuid4();
    $event = array('hook' => $hook, 'args' => $args, 'timestamp' => $timestamp ?? time(), 'option_name' => $name, 'attempts' => 0);
    $saved = add_option($name, $event, '', false);
    if ($saved) { $GLOBALS['glow_email_queued_this_request'] = true; }
    return $saved;
}

function glow_email_worker_events() {
    global $wpdb;
    $rows = $wpdb->get_results($wpdb->prepare("SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE %s ORDER BY option_id ASC", $wpdb->esc_like('glow_mail_queue_') . '%'));
    $events = array();
    foreach ($rows as $row) {
        $event = maybe_unserialize($row->option_value);
        if (!is_array($event) || !isset($event['hook'], $event['args'], $event['timestamp'])) { continue; }
        $event['option_name'] = $row->option_name;
        $events[hash('sha256', $row->option_name)] = $event;
    }
    return $events;
}

function glow_email_remove_event($event) {
    return isset($event['option_name']) ? delete_option($event['option_name'])
        : wp_unschedule_event($event['timestamp'], $event['hook'], $event['args'], true);
}

function glow_email_restore_event($event) {
    return isset($event['option_name']) ? add_option($event['option_name'], $event, '', false)
        : wp_schedule_single_event($event['timestamp'], $event['hook'], $event['args'], true);
}

function glow_email_worker_migrate() {
    foreach ((array) _get_cron_array() as $timestamp => $hooks) {
        foreach (array('glow_fc_async_mail', 'glow_fc_family_email_cron') as $hook) {
            foreach ($hooks[$hook] ?? array() as $key => $event) {
                $name = 'glow_mail_queue_' . hash('sha256', $timestamp . '|' . $hook . '|' . $key);
                $copy = array('timestamp' => (int) $timestamp, 'hook' => $hook, 'args' => $event['args'], 'option_name' => $name, 'attempts' => 0);
                if (add_option($name, $copy, '', false) || get_option($name)) {
                    $removed = wp_unschedule_event($timestamp, $hook, $event['args'], true);
                    if (is_wp_error($removed) || !$removed) { delete_option($name); }
                }
            }
        }
    }
}

function glow_email_worker_run() {
    global $wpdb;
    $lock = 'glow_email_' . md5(DB_NAME . $wpdb->prefix);
    if ((int) $wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s, 0)', $lock)) !== 1) { return; }
    try {
        glow_email_worker_migrate();
        $started = microtime(true);
        $processed = 0;
        foreach (glow_email_worker_events() as $event) {
            if ($event['timestamp'] > time() || ($event['attempts'] ?? 0) >= 5) { continue; }
            if ($processed >= 10 || microtime(true) - $started > 45) { break; }
            $processed++;
            $accepted = false;
            try {
                if ($event['hook'] === 'glow_fc_async_mail') {
                    $args = array_pad(array_values($event['args']), 5, array());
                    $accepted = (bool) wp_mail($args[0], $args[1], $args[2], $args[3], $args[4]);
                } elseif (function_exists('send_email_after_few_mintues') && get_userdata((int) ($event['args'][0] ?? 0))) {
                    $sent = function () use (&$accepted) { $accepted = true; };
                    add_action('wp_mail_succeeded', $sent);
                    try { call_user_func_array('send_email_after_few_mintues', $event['args']); }
                    finally { remove_action('wp_mail_succeeded', $sent); }
                }
            } catch (Throwable $error) {
                error_log('Glow email worker failed: ' . get_class($error));
            }
            if ($accepted) { delete_option($event['option_name']); }
            else {
                $event['attempts'] = ($event['attempts'] ?? 0) + 1;
                $event['timestamp'] = time() + min(3600, 60 * (2 ** $event['attempts']));
                update_option($event['option_name'], $event, false);
            }
        }
    } finally { $wpdb->get_var($wpdb->prepare('SELECT RELEASE_LOCK(%s)', $lock)); }
}

register_shutdown_function(function () {
    if (PHP_SAPI === 'cli' || wp_doing_cron() || (defined('GLOW_EMAIL_WORKER_DISABLED') && GLOW_EMAIL_WORKER_DISABLED)) { return; }
    // Admin actions remain explicit; registration requests can process their own newly queued emails.
    if (is_admin() && empty($GLOBALS['glow_email_queued_this_request'])) { return; }
    $fatal = error_get_last();
    if ($fatal && in_array($fatal['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR), true)) { return; }
    if (function_exists('fastcgi_finish_request')) { fastcgi_finish_request(); }
    else { while (ob_get_level()) { if (!@ob_end_flush()) { break; } } flush(); }
    ignore_user_abort(true);
    glow_email_worker_run();
});
