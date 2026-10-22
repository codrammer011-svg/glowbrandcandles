<?php
/**
 * Fundraiser Registration – forms, emails, rep referral
 * Auto-split from functions.php – edit this file, not the monolith.
 */

/**
 * ============================================================
 * User Registration Form 62316 / 62179 Enhancements & Mapping:
 * - Dynamic sales goal calculation preview (Goal * 2.5)
 * - Map submitted fields on registration to required plugin meta keys:
 *   - input_box_1693293590 -> user_registration_check_payable / check_payable
 *   - name_of_school_business -> user_registration_input_company
 *   - radio_1788399847 -> user_registration_radio_1788399847 / brochure_choice
 *   - profile_pic_url -> fund_profile_image / user_registration_image_url_profile
 *   - Ensure fundraiser role & active status
 * ============================================================
 */
/** Read UR Pro's object fields while supporting older array payloads. */
function glow_registration_field_value($form_data, $key)
{
    $field = $form_data[$key] ?? null;
    $value = is_object($field) ? ($field->value ?? '') : (is_array($field) ? ($field['value'] ?? '') : '');
    if (is_array($value)) {
        $value = reset($value);
    }
    return is_scalar($value) && trim((string) $value) !== '' ? (string) $value : null;
}

function glow_holiday_brochure_email_value($user_id)
{
    $value = get_user_meta($user_id, 'user_registration_check_box_1791918628', true);
    $values = is_array($value) ? $value : array($value);
    foreach ($values as $choice) {
        if (is_scalar($choice) && !in_array(strtolower(trim((string) $choice)), array('', '0', 'false', 'no'), true)) {
            return 'Yes — A Classic Kind of Sparkle — 15 Classic candles, $15 each';
        }
    }
    return 'No';
}

// Normalize blank optional inputs before UR runs format/length validation.
add_action('wp_ajax_user_registration_user_form_submit', 'glow_fundraiser_nullable_request', 0);
add_action('wp_ajax_nopriv_user_registration_user_form_submit', 'glow_fundraiser_nullable_request', 0);
function glow_fundraiser_nullable_request()
{
    if ((int) ($_POST['form_id'] ?? 0) !== 62316 || !isset($_POST['form_data']) || !function_exists('ur_get_form_fields')) {
        return;
    }
    $inputs = json_decode(wp_unslash($_POST['form_data']), true);
    if (!is_array($inputs)) {
        return; // Leave malformed requests to the plugin's validation.
    }
    $definitions = ur_get_form_fields(62316);
    foreach ($inputs as &$input) {
        $definition = $definitions[$input['field_name'] ?? ''] ?? null;
        if (!$definition || ur_string_to_bool($definition->general_setting->required ?? false)) {
            continue;
        }
        $value = $input['value'] ?? null;
        if ($value === null || $value === false || $value === array() || (is_string($value) && trim($value) === '')) {
            $input['value'] = null;
        }
    }
    unset($input);
    $_POST['form_data'] = wp_slash(wp_json_encode($inputs));
}

// Keep blank optional values nullable after sanitization (e.g. blank number => 0).
// Use the saved form definition, not a separate hard-coded list of required fields.
add_filter('user_registration_reorganize_form_data', 'glow_fundraiser_nullable_optional_fields', 20, 3);
function glow_fundraiser_nullable_optional_fields($valid_form_data, $form_fields, $form_id)
{
    if ((int) $form_id !== 62316) {
        return $valid_form_data;
    }

    $submitted = array();
    $raw = isset($_POST['form_data']) ? json_decode(wp_unslash($_POST['form_data']), true) : array();
    if (is_array($raw)) {
        foreach ($raw as $input) {
            if (is_array($input) && isset($input['field_name'])) {
                $submitted[$input['field_name']] = $input['value'] ?? null;
            }
        }
    }

    foreach ($form_fields as $definition) {
        $definition = (object) $definition;
        $settings = (object) ($definition->general_setting ?? array());
        $name = $settings->field_name ?? '';
        $type = $definition->field_key ?? '';
        if (!$name || $type === 'html' || ur_string_to_bool($settings->required ?? false)) {
            continue; // Required fields keep User Registration's existing validation.
        }

        $field = $valid_form_data[$name] ?? null;
        $value = array_key_exists($name, $submitted) ? $submitted[$name]
            : (is_object($field) ? ($field->value ?? null) : (is_array($field) ? ($field['value'] ?? null) : null));
        $blank = $value === null || $value === false || $value === array()
            || (is_string($value) && trim($value) === '');
        if (!$blank) {
            continue; // Optional values, including zero, still retain normal validation.
        }

        $field = is_object($field) ? $field : (object) ($field ?? array());
        $field->field_name = $name;
        $field->value = null;
        $field->extra_params = $field->extra_params ?? array('field_key' => $type, 'label' => $settings->label ?? '');
        $valid_form_data[$name] = $field;
    }
    return $valid_form_data;
}

add_action('wp_enqueue_scripts', function () {
    $brochure_css = '/css/fundraiser-brochures.css';
    wp_enqueue_style('glow-fundraiser-brochures', get_stylesheet_directory_uri() . $brochure_css, array(), filemtime(get_stylesheet_directory() . $brochure_css));
    $relative = '/js/registration-response-guard.js';
    wp_enqueue_script('glow-registration-response-guard', get_stylesheet_directory_uri() . $relative, array('jquery'), filemtime(get_stylesheet_directory() . $relative), true);
    if (function_exists('ur_get_form_fields')) {
        $fields = array();
        foreach (ur_get_form_fields(62316) as $name => $definition) {
            if ($definition->field_key === 'html') { continue; }
            $fields[$name] = array(
                'required' => ur_string_to_bool($definition->general_setting->required ?? false),
                'label' => $definition->general_setting->label ?? $name,
                'type' => $definition->field_key,
            );
        }
        wp_localize_script('glow-registration-response-guard', 'glowFundraiserFields', array('formId' => 62316, 'fields' => $fields));
    }
}, 99);

add_action('wp_footer', 'glow_user_registration_form_scripts');
function glow_user_registration_form_scripts()
{
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            var formSelectors = '#user-registration-form-62316, #user-registration-form-62179, .ur-frontend-form, .user-registration-form';

            $(formSelectors).each(function () {
                var $form = $(this);
                var goalInput = $form.find('[data-field-id="fr_goal"] input, input[name="fr_goal"]');
                if (goalInput.length) {
                    if (!$form.find('span.sale_require').length) {
                        goalInput.after("<span class='sale_require' style='display:none; margin-top:8px; font-size:14px; font-weight:600; color:#333;'>Total sales required = <b>$</b><b class='val'></b></span>");
                    }

                    $form.on('input change keyup', '[data-field-id="fr_goal"] input, input[name="fr_goal"]', function () {
                        var goal = parseFloat($(this).val()) || 0;
                        if (goal > 0) {
                            var totalAmount = (goal * 2.5).toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 2 });
                            $form.find('span.sale_require b.val').text(totalAmount);
                            $form.find('span.sale_require').css('display', 'block');
                        } else {
                            $form.find('span.sale_require').css('display', 'none');
                        }
                    });
                }
            });
        });
    </script>
    <?php
}

/**
 * Hook triggered after User Registration Form submission (62316, 62179, etc.).
 * Correctly maps and syncs fields for Fundraiser-Modules and glow-fundraiser-core.
 */
add_action('user_registration_after_register_user_action', 'glow_handle_fundraiser_form_submission', 20, 3);
function glow_handle_fundraiser_form_submission($valid_form_data, $form_id, $user_id)
{
    // Check if this is form 62316, 62179, or contains fundraiser registration fields
    $target_forms = array(62316, 62179);
    $is_fundraiser_form = in_array((int) $form_id, $target_forms, true) || isset($valid_form_data['name_of_school_business']) || isset($valid_form_data['fundraiser_preference']);

    if (!$is_fundraiser_form) {
        return;
    }

    // 1. Ensure user has 'fundraiser' role
    $user = get_userdata($user_id);
    if ($user && !in_array('fundraiser', (array) $user->roles, true)) {
        $user->add_role('fundraiser');
    }

    // 2. Map Profit Check Payable To
    $check_payable = get_user_meta($user_id, 'user_registration_input_box_1693293590', true);
    if (empty($check_payable)) {
        $check_payable = glow_registration_field_value($valid_form_data, 'input_box_1693293590');
    }
    if (!empty($check_payable)) {
        update_user_meta($user_id, 'user_registration_check_payable', sanitize_text_field($check_payable));
        update_user_meta($user_id, 'check_payable', sanitize_text_field($check_payable));
        update_user_meta($user_id, 'user_registration_input_box_1693293590', sanitize_text_field($check_payable));
    }

    // 3. Map School / Organization Name to input_company
    $school_name = get_user_meta($user_id, 'user_registration_name_of_school_business', true);
    if (empty($school_name)) {
        $school_name = glow_registration_field_value($valid_form_data, 'name_of_school_business');
    }
    if (!empty($school_name)) {
        update_user_meta($user_id, 'user_registration_name_of_school_business', sanitize_text_field($school_name));
        update_user_meta($user_id, 'user_registration_input_company', sanitize_text_field($school_name));
        update_user_meta($user_id, 'school_name', sanitize_text_field($school_name));
        update_user_meta($user_id, 'organization_name', sanitize_text_field($school_name));
    }

    // 4. Map Fundraiser Brochure Selection (radio_1788399847)
    $brochure_choice = get_user_meta($user_id, 'user_registration_radio_1788399847', true);
    if (empty($brochure_choice)) {
        $brochure_choice = glow_registration_field_value($valid_form_data, 'radio_1788399847');
    }
    if (!empty($brochure_choice)) {
        update_user_meta($user_id, 'user_registration_radio_1788399847', sanitize_text_field($brochure_choice));
        update_user_meta($user_id, 'user_registration_brochure_choice', sanitize_text_field($brochure_choice));
        update_user_meta($user_id, 'brochure_choice', sanitize_text_field($brochure_choice));
    }

    // 5. Map Profile Picture / Logo
    $profile_pic = get_user_meta($user_id, 'user_registration_profile_pic_url', true);
    if (empty($profile_pic)) {
        $profile_pic = get_user_meta($user_id, 'ur_profile_pic_url', true);
    }
    if (empty($profile_pic)) {
        $profile_pic = get_user_meta($user_id, 'profile_pic_url', true);
    }
    if (empty($profile_pic)) {
        $profile_pic = glow_registration_field_value($valid_form_data, 'profile_pic_url');
    }

    if (!empty($profile_pic)) {
        if (is_numeric($profile_pic)) {
            $img_url = wp_get_attachment_url((int) $profile_pic);
            if ($img_url) {
                $profile_pic = $img_url;
            }
        }
        update_user_meta($user_id, 'fund_profile_image', esc_url_raw($profile_pic));
        update_user_meta($user_id, 'user_registration_image_url_profile', esc_url_raw($profile_pic));
        update_user_meta($user_id, 'image_url_profile', esc_url_raw($profile_pic));
        update_user_meta($user_id, 'user_registration_profile_pic_url', esc_url_raw($profile_pic));
    }

    // 6. Map Chairperson Cell Phone (input_box_1595312762)
    $chair_phone = get_user_meta($user_id, 'user_registration_input_box_1595312762', true);
    if (empty($chair_phone)) {
        $chair_phone = glow_registration_field_value($valid_form_data, 'input_box_1595312762');
    }
    if (!empty($chair_phone)) {
        update_user_meta($user_id, 'user_registration_input_box_1595312762', sanitize_text_field($chair_phone));
        update_user_meta($user_id, 'chairperson_phone', sanitize_text_field($chair_phone));
        update_user_meta($user_id, 'phone_number', sanitize_text_field($chair_phone));
    }

    // 7. Ensure status is active for fundraiser dashboards and operations
    if (!get_user_meta($user_id, 'user_status', true)) {
        update_user_meta($user_id, 'user_status', '1');
    }
    if (!get_user_meta($user_id, 'user_action_status', true)) {
        update_user_meta($user_id, 'user_action_status', '1');
    }
}

/**
 * Email admin a full registration summary whenever a Fundraiser registers.
 *
 * Replaces the old admin-summary email in glow-fundraiser-core's
 * send_welcome_email_to_new_user(), which read fields positionally out of
 * $_POST['form_data'] built by js/fundraiser-ajax.js and js/custom_js.js.
 * Neither file exists on this site, so that email never actually fired.
 * This reads the same fields by name, straight from user meta (already
 * saved by User Registration by the time this hook runs).
 */
add_action('user_registration_after_register_user_action', 'glow_email_admin_fundraiser_summary', 30, 3);
function glow_email_admin_fundraiser_summary($valid_form_data, $form_id, $user_id)
{
    $target_forms = array(62316, 62179);
    $is_fundraiser_form = in_array((int) $form_id, $target_forms, true)
        || get_user_meta($user_id, 'user_registration_name_of_school_business', true)
        || get_user_meta($user_id, 'user_registration_fundraiser_preference', true);

    if (!$is_fundraiser_form) {
        return;
    }

    $m = static function (string $key) use ($user_id) {
        return trim((string) get_user_meta($user_id, $key, true));
    };
    $join = static function (array $parts) {
        return implode(', ', array_filter(array_map('trim', $parts), 'strlen'));
    };

    $first_name = $m('first_name');
    $last_name  = $m('last_name');
    $user_email = $m('user_email');

    $school_address = $join(array(
        $m('user_registration_street_address'),
        $m('user_registration_City2'),
        trim($m('user_registration_select_state') . ' ' . $m('user_registration_input_box_zip')),
    ));

    $chair_address = $join(array(
        $m('billing_address_1'),
        $m('billing_city'),
        trim($m('billing_state') . ' ' . $m('billing_postcode')),
    ));

    $school_name = $m('user_registration_name_of_school_business');
    $rebooked = (bool) get_user_meta($user_id, '_grb_previous_campaign', true);

    $fields = array(
        'School / Group Name'            => $school_name,
        'School / Group Phone'           => $m('user_registration_input_box_phone'),
        'School / Group Address'         => $school_address,
        'Number of Participants'         => $m('user_registration_participants_no'),
        'Sale Start Date'                => $m('user_registration_start_date'),
        'Sale End Date'                  => $m('user_registration_end_date'),
        'Select Fundraiser Type'         => $m('user_registration_fundraiser_preference'),
        'Brochure Style'                 => $m('user_registration_radio_1788399847'),
        'Chairperson Name'               => trim($first_name . ' ' . $last_name),
        'Chairperson Cell Phone'         => $m('user_registration_input_box_1595312762'),
        'Chairperson Address'            => $chair_address,
        'Chairperson Email'              => $user_email,
        'Had a GLOW Fundraiser Before?'  => $m('user_registration_radio_1534260773'),
        'How Did You Hear About GLOW'    => $m('user_registration_reference'),
        'Referral Name/Representative'   => $m('user_registration_referral_name'),
        'Referral Email'                 => $m('user_registration_referral_email'),
        'Fundraising Sales Goal'         => $m('user_registration_fr_goal'),
        'Selling Goal (per participant)' => $m('user_registration_selling_goal'),
        'Reason for Fundraising'         => $m('user_registration_reason_for_fundraising'),
        'Small Candle Holiday Brochure' => glow_holiday_brochure_email_value($user_id),
        'Profit Check Payable To'        => $m('user_registration_input_box_1693293590'),
    );

    $rows = '';
    foreach ($fields as $label => $value) {
        if ($value === '') {
            continue;
        }
        $rows .= '<tr><td style="padding:6px 10px;border:1px solid #eee;font-weight:600;white-space:nowrap;vertical-align:top;">'
            . esc_html($label) . '</td><td style="padding:6px 10px;border:1px solid #eee;">'
            . nl2br(esc_html($value)) . '</td></tr>';
    }

    $message = '<div style="font-family:Arial,sans-serif;">'
        . '<h2 style="margin:0 0 10px;">' . ($rebooked ? 'Fundraiser Rebooked' : 'New Fundraiser Registration') . '</h2>'
        . '<p style="margin:0 0 15px;">User ID: ' . (int) $user_id . ' &mdash; <a href="'
        . esc_url(admin_url('user-edit.php?user_id=' . $user_id)) . '">View user in admin</a></p>'
        . '<table style="border-collapse:collapse;width:100%;max-width:700px;">' . $rows . '</table>'
        . '</div>';

    // 'candace@glowscentedcandles.com' and 'candacethompson12@gmail.com' temporarily disabled for testing.
    $to      = array('codrammer011@gmail.com');
    // Keep the "New Fundraiser Registration - " prefix: glow-admin-email-redirect routes admin mail by it.
    $subject = 'New Fundraiser Registration - ' . ($school_name !== '' ? $school_name : trim($first_name . ' ' . $last_name))
        . ($rebooked ? ' (Rebooked)' : '');
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . (defined('GLOW_FROM_EMAIL') ? GLOW_FROM_EMAIL : 'Glow Scented Candles <info@glowscentedcandles.com>'),
    );

    glow_fc_queue_mail($to, $subject, $message, $headers);
}

/**
 * Send the "Your GLOW fundraiser has been registered!" welcome email to the USER.
 *
 * Re-implements the user-facing email that glow-fundraiser-core's legacy
 * send_welcome_email_to_new_user() was supposed to send. That function read
 * fields positionally out of $_POST['form_data'] (built by js/fundraiser-ajax.js
 * / js/custom_js.js, which do not exist on this site), so its subject/body
 * conditions were never met and the email never fired.
 *
 * This reads the same fields BY NAME from user meta (already saved by User
 * Registration by the time this hook runs) — the same reliable approach used by
 * glow_email_admin_fundraiser_summary() above.
 */
add_action('user_registration_after_register_user_action', 'glow_email_user_fundraiser_welcome', 35, 3);
function glow_email_user_fundraiser_welcome($valid_form_data, $form_id, $user_id)
{
    $user = get_userdata($user_id);
    if (!$user) {
        return;
    }
    // Match legacy behaviour: never send to representatives.
    if (in_array('representative', (array) $user->roles, true)) {
        return;
    }
    // Send once per user.
    if (get_user_meta($user_id, '_glow_welcome_email_sent', true)) {
        return;
    }

    // Only for fundraiser registrations (same guard as the admin summary).
    $target_forms = array(62316, 62179);
    $is_fundraiser_form = in_array((int) $form_id, $target_forms, true)
        || get_user_meta($user_id, 'user_registration_name_of_school_business', true)
        || get_user_meta($user_id, 'user_registration_fundraiser_preference', true);
    if (!$is_fundraiser_form) {
        return;
    }

    $m = static function (string $key) use ($user_id) {
        return trim((string) get_user_meta($user_id, $key, true));
    };
    $join = static function (array $parts) {
        return implode(', ', array_filter(array_map('trim', $parts), 'strlen'));
    };

    $first_name  = $m('first_name');
    $last_name   = $m('last_name');
    $full_name   = trim($first_name . ' ' . $last_name);
    $user_email  = $user->user_email;
    $rebooked    = (bool) get_user_meta($user_id, '_grb_previous_campaign', true);

    $school_name = $m('user_registration_name_of_school_business');
    $school_phone = $m('user_registration_input_box_phone');
    $school_address = $join(array(
        $m('user_registration_street_address'),
        $m('user_registration_City2'),
        trim($m('user_registration_select_state') . ' ' . $m('user_registration_input_box_zip')),
    ));
    $chair_cell   = $m('user_registration_input_box_1595312762');
    $chair_address = $join(array(
        $m('billing_address_1'),
        $m('billing_city'),
        trim($m('billing_state') . ' ' . $m('billing_postcode')),
    ));

    $participants   = $m('user_registration_participants_no');
    $start_date     = $m('user_registration_start_date');
    $end_date       = $m('user_registration_end_date');
    $fundraiser_type = $m('user_registration_fundraiser_preference');
    $had_before     = $m('user_registration_radio_1534260773');
    $how_heard      = $m('user_registration_reference');
    $referral_name  = $m('user_registration_referral_name');
    $referral_email = $m('user_registration_referral_email');
    $sales_goal     = $m('user_registration_fr_goal');
    $selling_goal   = $m('user_registration_selling_goal');
    $reason         = $m('user_registration_reason_for_fundraising');
    $holiday_brochure = glow_holiday_brochure_email_value($user_id);
    $payable_to     = $m('user_registration_input_box_1693293590');

    // Domain-safe URLs (works on any environment, not just glowscentedcandles.com).
    $my_account_url = home_url('/my-account/');
    $profile_url    = home_url('/profile/' . $user_id);
    $seller_url     = home_url('/seller-register/?frid=' . $user_id);
    $reset_url      = wp_lostpassword_url();

    // --- QR codes (best-effort; never fatal) ---
    $profile_qr_url = '';
    $seller_qr_url  = '';
    $upload   = wp_upload_dir();
    $qr_dir   = trailingslashit($upload['basedir']) . 'qrcodes/';
    $qr_url   = trailingslashit($upload['baseurl']) . 'qrcodes/';
    $profile_qr_path = $qr_dir . 'image_' . $user_id . '.png';
    $seller_qr_path  = $qr_dir . 'image_seller_register' . $user_id . '.png';
    try {
        $qrlib = defined('GLOW_FC_DIR') ? GLOW_FC_DIR . 'includes/phpqrcode/qrlib.php' : '';
        if ($qrlib && is_file($qrlib)) {
            if (!file_exists($qr_dir)) {
                wp_mkdir_p($qr_dir);
            }
            // Another plugin (User Registration Pro's TCPDF) may already define a different QRcode class;
            // loading phpqrcode on top of it is an uncatchable fatal error.
            if (!class_exists('QRcode', false)) {
                require_once $qrlib;
            }
            if (method_exists('QRcode', 'png')) {
                QRcode::png($profile_url, $profile_qr_path, QR_ECLEVEL_L, 4, 2);
                if (is_file($profile_qr_path)) {
                    $profile_qr_url = $qr_url . 'image_' . $user_id . '.png';
                }
                QRcode::png($seller_url, $seller_qr_path, QR_ECLEVEL_L, 4, 2);
                if (is_file($seller_qr_path)) {
                    $seller_qr_url = $qr_url . 'image_seller_register' . $user_id . '.png';
                }
            }
        }
    } catch (\Throwable $e) {
        error_log('[glow] welcome-email QR generation skipped: ' . $e->getMessage());
    }

    // --- Participant-letter DOCX (optional; skipped if PhpWord/template missing) ---
    $attachments = array();
    if (function_exists('build_fundraiser_docx_for_user')) {
        try {
            $docx = build_fundraiser_docx_for_user(array(
                'fund_id'     => $user_id,
                'org_name'    => $school_name,
                'reason'      => $reason,
                'items_goal'  => $sales_goal,
                'end_date'    => $end_date,
                'chair_name'  => $full_name,
                'chair_phone' => $chair_cell,
                'chair_email' => $user_email,
                'seller_url'  => $seller_url,
                'qr_path'     => $profile_qr_path,
                'qr_url'      => $seller_qr_path,
            ));
            if (!empty($docx) && is_file($docx)) {
                $attachments[] = $docx;
            }
        } catch (\Throwable $e) {
            error_log('[glow] welcome-email participant letter skipped: ' . $e->getMessage());
        }
    }

    $e = static function ($v) {
        return esc_html((string) $v);
    };
    $has_letter = !empty($attachments);

    ob_start();
    ?>
<div style="margin:0;padding:0;background:#f7f7f7;font-family:Arial,Helvetica,sans-serif;color:#333;width:100%;">
  <table align="center" width="100%" cellpadding="0" cellspacing="0" style="max-width:900px;margin:30px auto;background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 6px rgba(0,0,0,0.1);">
    <tr>
      <td style="background:#333;padding:25px 30px;text-align:left;">
        <h1 style="color:#fff;font-size:26px;margin:0;font-weight:400;">🎉 Your Glow Fundraiser Has Been <?php echo $rebooked ? 'Rebooked' : 'Registered'; ?>!</h1>
      </td>
    </tr>
    <tr>
      <td style="padding:30px;">
        <p>Hi <strong><?php echo $e($full_name !== '' ? $full_name : $user->display_name); ?></strong> 👋</p>
        <p>✨ <b><em>Please do not delete this email — it contains your fundraiser details and login info!</em></b></p>
        <p>Thank you for booking your GLOW SCENTED CANDLES fundraiser! We’re thrilled to work with you and your group to make your sale fun, profitable, and stress-free. 🌙 <em>Please read the important details below and save this email for reference.</em> ✨</p>

        <?php if ($has_letter) : ?>
        <hr style="border:0;border-top:1px double #edeae9;width:100%;margin:20px auto;">
        <h2 style="color:#1f1f1f;font-size:18px;">💌 Participant Letter</h2>
        <p>📎 <em>Attached:</em> your customizable <strong>Participant Letter</strong> — review, edit if needed, and share it with your group to boost participation.</p>
        <?php endif; ?>

        <hr style="border:0;border-top:1px double #edeae9;width:100%;margin:20px auto;">
        <h2 style="color:#1f1f1f;font-size:18px;margin-top:25px;">💻 Your Director Login</h2>
        <p>Manage everything for your fundraiser online:</p>
        <p><a href="<?php echo esc_url($my_account_url); ?>" target="_blank" style="color:#4184f3;text-decoration:none;">🔗 Access Your Director Dashboard</a></p>
        <p style="margin-top:15px;"><b>Login Details:</b><br>
        📧 Email: <?php echo $e($user_email); ?><br>
        🔑 Password: use the password you created during registration (<a href="<?php echo esc_url($reset_url); ?>" style="color:#4184f3;">reset it here</a> if needed)<br>
        🪪 Fundraiser ID: <?php echo (int) $user_id; ?><br>
        🌐 Profile Link: <a href="<?php echo esc_url($profile_url); ?>" target="_blank" style="color:#4184f3;"><?php echo esc_html($profile_url); ?></a></p>

        <p style="margin-top:15px;"><b>Your Dashboard Lets You:</b></p>
        <ul style="margin:0 0 15px 18px;padding:0;list-style-type:none;">
          <li>✅ View real-time sales</li>
          <li>✅ Invite sellers to register &amp; track their own progress</li>
          <li>✅ Place your catalog order at the end of your sale</li>
          <li>✅ Monitor your total fundraiser profits</li>
        </ul>

        <?php if ($profile_qr_url || $seller_qr_url) : ?>
        <h3 style="margin-top:25px;">📸 QR Codes</h3>
        <table width="100%" style="margin:10px 0;">
          <tr>
            <?php if ($profile_qr_url) : ?>
            <td width="50%" align="center">
              <img src="<?php echo esc_url($profile_qr_url); ?>" width="120" height="120" alt="Profile QR Code"><br>
              <a href="<?php echo esc_url($profile_url); ?>" style="color:#4184f3;text-decoration:none;" target="_blank">Profile QR Code</a>
            </td>
            <?php endif; ?>
            <?php if ($seller_qr_url) : ?>
            <td width="50%" align="center">
              <img src="<?php echo esc_url($seller_qr_url); ?>" width="120" height="120" alt="Seller Register QR Code"><br>
              <a href="<?php echo esc_url($seller_url); ?>" style="color:#4184f3;text-decoration:none;" target="_blank">Seller Register QR Code</a>
            </td>
            <?php endif; ?>
          </tr>
        </table>
        <?php endif; ?>

        <hr style="border:none;border-top:1px solid #eee;margin:25px 0;">
        <h3 style="font-size:18px;">🏫 Fundraising Agreement</h3>
        <p><b>School/Group Name:</b> <?php echo $e($school_name); ?><br>
        📞 <b>Phone:</b> <?php echo $e($school_phone); ?><br>
        📍 <b>Address:</b> <?php echo $e($school_address); ?><br>
        👥 <b>Participants:</b> <?php echo $e($participants); ?><br>
        📅 <b>Start Date:</b> <?php echo $e($start_date); ?><br>
        📅 <b>End Date:</b> <?php echo $e($end_date); ?><br>
        🛍️ <b>Fundraiser Type:</b> <?php echo $e($fundraiser_type); ?></p>

        <hr style="border:none;border-top:1px solid #eee;margin:25px 0;">
        <h2 style="font-size:18px;margin-top:25px;">👩‍💼 Chairperson Information</h2>
        <p>
          <b>Name:</b> <?php echo $e($full_name); ?><br>
          📱 <b>Cell:</b> <?php echo $e($chair_cell); ?><br>
          🏠 <b>Address:</b> <?php echo $e($chair_address); ?><br>
          📧 <b>Email:</b> <?php echo $e($user_email); ?><br>
          <b>Small Candle Holiday Brochure:</b> <?php echo $e($holiday_brochure); ?>
        </p>

        <hr style="border:none;border-top:1px solid #eee;margin:25px 0;">
        <h2 style="font-size:18px;margin-top:25px;">💛 Fundraiser Details</h2>
        <p>✨ <b>Returning Glow Fundraiser:</b> <?php echo $e($had_before); ?><br>
        ✨ <b>How You Heard About Glow:</b> <?php echo $e($how_heard); ?><br>
        ✨ <b>Name of Referral/Representative:</b> <?php echo $e($referral_name); ?><br>
        ✨ <b>Referral/Representative’s Email:</b> <?php echo $e($referral_email); ?><br>
        💬 <b>Fundraising Sales Goal:</b> <?php echo $e($sales_goal); ?><br>
        💵 <b>Selling Goal (per participant):</b> <?php echo $e($selling_goal); ?></p>
        <?php if ($reason !== '') : ?>
        <h2 style="font-size:16px;margin-top:25px;">💬 Reason for Fundraising:</h2>
        <p><?php echo $e($reason); ?></p>
        <?php endif; ?>
        <?php if ($payable_to !== '') : ?>
        <p style="font-size:14px;line-height:1.6;margin-top:15px;">💵 Profit Check Payable To: <?php echo $e($payable_to); ?></p>
        <?php endif; ?>

        <hr style="border:none;border-top:1px solid #eee;margin:25px 0;">
        <h2 style="font-size:18px;margin-top:25px;">📦 Brochures &amp; Online Store</h2>
        <p style="font-size:14px;line-height:1.6;">Your brochures will ship directly from <b>GLOW</b>, and you’ll get a tracking email once they’re on the way. Your online store is live — upload your logo and store message to make it personal! 🌟</p>
        <p style="font-size:14px;line-height:1.6;"><b>📣 Share your fundraiser!</b> Send supporters your <a href="<?php echo esc_url($profile_url); ?>" target="_blank" style="color:#4184f3;"><?php echo esc_html($profile_url); ?></a> via email, text, or Facebook — and encourage them to “Shop &amp; Support!”</p>

        <hr style="border:none;border-top:1px solid #eee;margin:25px 0;">
        <h2 style="font-size:18px;margin-top:25px;">🌟 Profit Structure</h2>
        <ul style="margin:0 0 15px 18px;padding:0;list-style-type:disc;">
          <li>💰 50% Profit — when 150+ total items are sold (brochure + ship-to-group)</li>
          <li>💰 40% Profit — when fewer than 100 items are sold</li>
          <li>💻 Online Ship-to-Home Orders: 40% profit</li>
          <li>🕯️ Orders typically ship within 7 business days.</li>
        </ul>

        <hr style="border:none;border-top:1px solid #eee;margin:25px 0;">
        <h2 style="font-size:18px;margin-top:25px;">🧾 Seller Registration</h2>
        <p style="font-size:14px;line-height:1.6;">When your fundraiser begins, have all sellers register here: <a href="<?php echo esc_url($seller_url); ?>" target="_blank" style="color:#4184f3;">Seller Registration</a>. 📩 Remind them not to use school email addresses (filters can block messages). Each seller gets their own store link and dashboard to track sales.</p>

        <hr style="border:none;border-top:1px solid #eee;margin:25px 0;">
        <h2 style="font-size:18px;">💬 We’re Here for You</h2>
        <p style="font-size:14px;line-height:1.6;">We’re always here to help — from kickoff to closeout! 💛</p>
        <p>📧 <b>Email:</b> <a href="mailto:contact@glowscentedcandles.com" style="color:#4184f3;">contact@glowscentedcandles.com</a><br>
        🌐 <b>Website:</b> <a href="<?php echo esc_url( glow_dynamic_url_base('root') ); ?>" style="color:#4184f3;">www.glowscentedcandles.com</a></p>
      </td>
    </tr>
  </table>
</div>
    <?php
    $message = ob_get_clean();

    $subject = 'Welcome to the Glow Fundraiser Family! Lets Light Up Your Success!';
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . (defined('GLOW_FROM_EMAIL') ? GLOW_FROM_EMAIL : 'Glow Scented Candles <info@glowscentedcandles.com>'),
    );

    // Queue both emails async so AJAX hook returns before SMTP connects.
    glow_fc_queue_mail($user_email, $subject, $message, $headers, $attachments);
    update_user_meta($user_id, '_glow_welcome_email_sent', current_time('mysql'));
    glow_fc_queue_family_mail($user_id, $full_name !== '' ? $full_name : $user->display_name);
}

/**
 * A rebooked fundraiser (new user ID from glow-fundraiser-core's rebooking) gets the
 * same registration emails; the helpers above switch the title to "Rebooked" via
 * the _grb_previous_campaign marker. Form ID 0: the fundraiser guards use user meta.
 */
add_action('glow_fc_fundraiser_rebooked', 'glow_email_fundraiser_rebooked', 10, 2);
function glow_email_fundraiser_rebooked($user_id, $previous_id)
{
    glow_email_admin_fundraiser_summary(array(), 0, $user_id);
    glow_email_user_fundraiser_welcome(array(), 0, $user_id);
}

/**
 * Link a newly-registered fundraiser to its referring representative.
 *
 * The rep dashboard (representative-dashboard.php "Manage Fundraisers") only
 * shows a fundraiser when BOTH the fundraiser's user_registration_referral_email
 * equals the rep's email AND the fundraiser's ID is listed in the rep's payment
 * post `fundraiser_ids` meta. The code that wrote referral_payment_postid and
 * appended to fundraiser_ids lived in the dead legacy send_welcome_email_to_new_user()
 * flow, so the link was never created and rep dashboards always showed nothing.
 * This restores it on the live registration hook.
 */
/**
 * Capture the ?repID= from the rep dashboard's "Register a Fundraiser" link.
 *
 * representative-dashboard.php links to /fundraiser-register/?repID=<rep_id>, but
 * nothing read that param, so the fundraiser was never tied to the rep. We stash
 * the rep id in a short-lived cookie on page load; the registration submits via
 * AJAX (which sends cookies), so glow_link_fundraiser_to_representative() can read
 * it after the user is created.
 */
add_action('template_redirect', 'glow_capture_rep_referral');
function glow_capture_rep_referral()
{
    if (! isset($_GET['repID']) || ! is_numeric($_GET['repID'])) {
        return;
    }
    $rep = get_user_by('id', (int) $_GET['repID']);
    if ($rep && in_array('representative', (array) $rep->roles, true)) {
        $val = (string) $rep->ID;
        setcookie('glow_ref_rep', $val, time() + DAY_IN_SECONDS, defined('COOKIEPATH') ? COOKIEPATH : '/', defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '');
        $_COOKIE['glow_ref_rep'] = $val; // available immediately this request
    }
}

add_action('user_registration_after_register_user_action', 'glow_link_fundraiser_to_representative', 28, 3);
function glow_link_fundraiser_to_representative($valid_form_data, $form_id, $user_id)
{
    $rep = null;

    // 1) Referral email typed into the form (if it is a representative's email).
    $referral_email = trim((string) get_user_meta($user_id, 'user_registration_referral_email', true));
    if ($referral_email !== '' && is_email($referral_email)) {
        $maybe = get_user_by('email', $referral_email);
        if ($maybe && in_array('representative', (array) $maybe->roles, true)) {
            $rep = $maybe;
        }
    }

    // 2) Fallback: ?repID= carried from the rep dashboard link (cookie). This wins
    //    when no valid rep email was entered, and stamps the referral email so the
    //    dashboard's "referral_email == rep email" query matches.
    if (! $rep && ! empty($_COOKIE['glow_ref_rep']) && is_numeric($_COOKIE['glow_ref_rep'])) {
        $maybe = get_user_by('id', (int) $_COOKIE['glow_ref_rep']);
        if ($maybe && in_array('representative', (array) $maybe->roles, true)) {
            $rep = $maybe;
            update_user_meta($user_id, 'user_registration_referral_email', $maybe->user_email);
        }
    }

    if (! $rep) {
        return;
    }

    $pay_post = get_user_meta($rep->ID, 'um_unique_payment_id', true);
    if (! empty($pay_post)) {
        // Point the fundraiser at the rep's payment post.
        update_user_meta($user_id, 'referral_payment_postid', $pay_post);

        // Append this fundraiser to the rep's fundraiser_ids list (comma-separated).
        $ids = (string) get_post_meta($pay_post, 'fundraiser_ids', true);
        $arr = $ids !== '' ? array_filter(array_map('trim', explode(',', $ids)), 'strlen') : array();
        if (! in_array((string) $user_id, $arr, true)) {
            $arr[] = (string) $user_id;
            update_post_meta($pay_post, 'fundraiser_ids', implode(',', $arr));
        }
    }

    // Clear the referral cookie once consumed.
    if (! empty($_COOKIE['glow_ref_rep'])) {
        setcookie('glow_ref_rep', '', time() - 3600, defined('COOKIEPATH') ? COOKIEPATH : '/', defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '');
        unset($_COOKIE['glow_ref_rep']);
    }
}
