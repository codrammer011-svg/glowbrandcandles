<?php
/**
 * Plugin Name: Glow Pending Emails
 * Description: Administrator tools for manually processing Glow registration email events.
 */
defined('ABSPATH') || exit;

function glow_pending_email_events() {
    $items = function_exists('glow_email_worker_events') ? glow_email_worker_events() : array();
    foreach ((array) _get_cron_array() as $timestamp => $hooks) {
        foreach (array('glow_fc_async_mail', 'glow_fc_family_email_cron') as $hook) {
            foreach ($hooks[$hook] ?? array() as $key => $event) {
                $id = hash('sha256', $timestamp . '|' . $hook . '|' . $key);
                $items[$id] = array('timestamp' => (int) $timestamp, 'hook' => $hook, 'args' => $event['args']);
            }
        }
    }
    return $items;
}

add_action('admin_menu', function () {
    add_management_page('Pending Emails', 'Pending Emails', 'manage_options', 'glow-pending-emails', 'glow_pending_emails_page');
});

function glow_pending_emails_page() {
    if (!current_user_can('manage_options')) { return; }
    $events = glow_pending_email_events();
    ?>
    <div class="wrap" id="glow-pending-emails">
        <h1>Pending Emails</h1>
        <p>Send queued registration emails using the current SMTP settings. A successful send means the mailer accepted the email; inbox delivery is not guaranteed. Failed emails stay queued.</p>
        <p>Family follow-up emails are included when you send due emails.</p>
        <p><button class="button button-primary" id="glow-send-pending">Send due emails</button> <a class="button" href="<?php echo esc_url(admin_url('tools.php?page=glow-pending-emails')); ?>">Refresh</a></p>
        <div id="glow-email-result" role="status" aria-live="polite"></div>
        <p><button class="button" id="glow-send-selected">Send selected</button> <button class="button" id="glow-delete-selected">Delete selected</button> <span id="glow-selected-count">0 selected</span></p>
        <table class="widefat striped"><thead><tr><td class="check-column"><input type="checkbox" id="glow-select-all" aria-label="Select all pending emails"></td><th>Recipient</th><th>Subject / Event</th><th>Scheduled</th><th>Action</th></tr></thead><tbody>
        <?php foreach ($events as $id => $event) :
            $is_mail = $event['hook'] === 'glow_fc_async_mail';
            $user = !$is_mail ? get_userdata((int) ($event['args'][0] ?? 0)) : false;
            $to = $is_mail ? ($event['args'][0] ?? '') : ($user ? $user->user_email : 'User not found');
            $to = is_array($to) ? implode(', ', $to) : $to;
            ?>
            <tr data-email-id="<?php echo esc_attr($id); ?>" data-due="<?php echo $event['timestamp'] <= time() ? '1' : '0'; ?>" data-mail="<?php echo $is_mail ? '1' : '0'; ?>">
                <th scope="row" class="check-column"><input type="checkbox" class="glow-select-email" aria-label="<?php echo esc_attr('Select pending email for ' . $to); ?>"></th>
                <td><?php echo esc_html($to); ?></td>
                <td><?php echo esc_html($is_mail ? ($event['args'][1] ?? '') : 'Family follow-up email'); ?></td>
                <td><?php echo esc_html(wp_date('Y-m-d H:i:s T', $event['timestamp'])); ?></td>
                <td><button class="button glow-send-email">Send now</button> <button class="button glow-delete-email">Delete</button> <span class="glow-email-status"></span></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$events) : ?><tr><td colspan="5">No pending Glow registration emails.</td></tr><?php endif; ?>
        </tbody></table>
    </div>
    <script>
    jQuery(function ($) {
        var busy = false, nonce = <?php echo wp_json_encode(wp_create_nonce('glow_pending_email_send')); ?>;
        function updateSelection() {
            var boxes = $('.glow-select-email:not(:disabled)'), count = boxes.filter(':checked').length;
            $('#glow-selected-count').text(count + ' selected');
            $('#glow-select-all').prop('checked', boxes.length > 0 && count === boxes.length).prop('indeterminate', count > 0 && count < boxes.length);
            $('#glow-send-selected, #glow-delete-selected').prop('disabled', busy || count === 0);
        }
        function selectedRows() { return $('.glow-select-email:checked:not(:disabled)').closest('tr').toArray(); }
        $('#glow-select-all').on('change', function () { $('.glow-select-email:not(:disabled)').prop('checked', this.checked); updateSelection(); });
        $('.glow-select-email').on('change', updateSelection);
        async function send(row, operation) {
            row.find('.glow-email-status').text('Processing…');
            try {
                var result = await $.post(ajaxurl, {action: operation === 'delete' ? 'glow_pending_email_delete' : 'glow_pending_email_send', nonce: nonce, event_id: row.data('email-id')});
                row.find('.glow-email-status').text(result.data.message);
                if (result.success) {
                    row.attr('data-done', '1').find('button').remove();
                    row.find('.glow-select-email').prop('checked', false).prop('disabled', true);
                }
                return result.success;
            } catch (error) {
                row.find('.glow-email-status').text('Could not confirm the result. Refresh before retrying.');
                throw error;
            }
        }
        async function process(rows, operation) {
            if (busy) { return; }
            if (!rows.length) { $('#glow-email-result').text('No pending emails selected.'); return; }
            busy = true; $('#glow-pending-emails button').prop('disabled', true);
            $('#glow-select-all, .glow-select-email:not(:disabled)').prop('disabled', true).attr('data-processing-disabled', '1');
            var completed = 0;
            try {
                for (var row of rows) {
                    if (await send($(row), operation)) { completed++; }
                    $('#glow-email-result').text(completed + ' of ' + rows.length + ' completed.');
                }
            }
            catch (error) { $('#glow-email-result').text('Processing stopped because the response could not be confirmed.'); }
            finally {
                busy = false; $('#glow-pending-emails button').prop('disabled', false);
                $('[data-processing-disabled]').removeAttr('data-processing-disabled').prop('disabled', false);
                $('tr[data-done] .glow-select-email').prop('disabled', true);
                updateSelection();
            }
        }
        $('.glow-send-email').on('click', function () { process([$(this).closest('tr')[0]]); });
        $('.glow-delete-email').on('click', function () {
            if (busy || !window.confirm('Delete this pending email? It will be removed from the queue without sending.')) { return; }
            process([$(this).closest('tr')[0]], 'delete');
        });
        $('#glow-send-pending').on('click', function () { process($('tr[data-due="1"]:not([data-done])').toArray()); });
        $('#glow-send-selected').on('click', function () { process(selectedRows()); });
        $('#glow-delete-selected').on('click', function () {
            var rows = selectedRows();
            if (busy || !rows.length || !window.confirm('Delete ' + rows.length + ' selected pending emails without sending?')) { return; }
            process(rows, 'delete');
        });
        updateSelection();
    });
    </script>
    <?php
}

add_action('wp_ajax_glow_pending_email_send', function () {
    if (!current_user_can('manage_options')) { wp_send_json_error(array('message' => 'Administrator access required.'), 403); }
    check_ajax_referer('glow_pending_email_send', 'nonce');
    global $wpdb;
    // Database lock works even when the imported object-cache drop-in is broken.
    $lock = 'glow_email_' . md5(DB_NAME . $wpdb->prefix);
    if ((int) $wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s, 0)', $lock)) !== 1) {
        wp_send_json_error(array('message' => 'Another email is being processed. Try again shortly.'));
    }
    $success = false;
    $message = 'Email was not sent.';
    try {
        $id = sanitize_text_field(wp_unslash($_POST['event_id'] ?? ''));
        $event = glow_pending_email_events()[$id] ?? null;
        if (!$event) { throw new RuntimeException('This event is no longer queued. Refresh the page.'); }
        if ($event['hook'] === 'glow_fc_family_email_cron' && !function_exists('send_email_after_few_mintues')) {
            throw new RuntimeException('The family email handler is unavailable; event remains queued.');
        }
        $removed = function_exists('glow_email_remove_event') ? glow_email_remove_event($event) : wp_unschedule_event($event['timestamp'], $event['hook'], $event['args'], true);
        if (is_wp_error($removed) || !$removed) { throw new RuntimeException('Could not claim this event; no email was sent.'); }
        try {
            if ($event['hook'] === 'glow_fc_async_mail') {
                $args = array_pad(array_values($event['args']), 5, array());
                $success = (bool) wp_mail($args[0], $args[1], $args[2], $args[3], $args[4]);
                $message = $success ? 'Accepted by mailer.' : 'Mailer rejected the email. Event restored; check WP Mail SMTP errors.';
            } else {
                if (!get_userdata((int) ($event['args'][0] ?? 0))) {
                    throw new RuntimeException('Family email user no longer exists.');
                }
                $accepted = false;
                $rejected = false;
                $sent_hook = function () use (&$accepted) { $accepted = true; };
                $failed_hook = function () use (&$rejected) { $rejected = true; };
                add_action('wp_mail_succeeded', $sent_hook);
                add_action('wp_mail_failed', $failed_hook);
                try {
                    call_user_func_array('send_email_after_few_mintues', $event['args']);
                } finally {
                    remove_action('wp_mail_succeeded', $sent_hook);
                    remove_action('wp_mail_failed', $failed_hook);
                }
                $success = $accepted && !$rejected;
                $message = $success ? 'Family email accepted by mailer.' : 'Family email send was not confirmed. Event restored; check SMTP errors.';
            }
        } catch (Throwable $error) {
            $message = 'Processing failed. Event restored; check PHP / SMTP logs.';
            error_log('Glow pending email processing failed: ' . get_class($error));
        }
        if (!$success) {
            $restored = function_exists('glow_email_restore_event') ? glow_email_restore_event($event) : wp_schedule_single_event($event['timestamp'], $event['hook'], $event['args'], true);
            if (is_wp_error($restored) || !$restored) {
                // Preserve the message for recovery if another event prevents restoration.
                update_option('glow_pending_email_recovery_' . $id, $event, false);
                $message .= ' Queue restoration failed; a recovery copy has been saved in the database.';
            }
        }
    } catch (Throwable $error) { $message = $error->getMessage(); }
    finally { $wpdb->get_var($wpdb->prepare('SELECT RELEASE_LOCK(%s)', $lock)); }
    if ($success) { wp_send_json_success(array('message' => $message)); }
    wp_send_json_error(array('message' => $message));
});

add_action('wp_ajax_glow_pending_email_delete', 'glow_pending_email_delete');
function glow_pending_email_delete() {
    if (!current_user_can('manage_options')) { wp_send_json_error(array('message' => 'Administrator access required.'), 403); }
    check_ajax_referer('glow_pending_email_send', 'nonce');
    global $wpdb;
    $lock = 'glow_email_' . md5(DB_NAME . $wpdb->prefix);
    if ((int) $wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s, 0)', $lock)) !== 1) {
        wp_send_json_error(array('message' => 'An email is being processed. Refresh before deleting.'));
    }
    $success = false;
    $message = 'Could not delete the event. Refresh and try again.';
    try {
        $id = sanitize_text_field(wp_unslash($_POST['event_id'] ?? ''));
        $event = glow_pending_email_events()[$id] ?? null;
        if ($event) {
            $removed = function_exists('glow_email_remove_event') ? glow_email_remove_event($event) : wp_unschedule_event($event['timestamp'], $event['hook'], $event['args'], true);
            $success = !is_wp_error($removed) && (bool) $removed;
            if ($success) { $message = 'Deleted from the queue. No email sent.'; }
        } else { $message = 'This event is no longer queued. Refresh the page.'; }
    } finally { $wpdb->get_var($wpdb->prepare('SELECT RELEASE_LOCK(%s)', $lock)); }
    if ($success) { wp_send_json_success(array('message' => $message)); }
    wp_send_json_error(array('message' => $message));
}
