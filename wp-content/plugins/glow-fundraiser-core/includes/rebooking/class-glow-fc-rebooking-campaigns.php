<?php
defined('ABSPATH') || exit;

final class Glow_FC_Rebooking_Campaigns {
    public static function is_fundraiser($user) {
        return $user instanceof WP_User && in_array('fundraiser', $user->roles, true)
            && !array_intersect(array('administrator', 'representative', 'seller'), $user->roles)
            && !user_can($user, 'manage_options');
    }

    public static function is_closed($id) {
        return '2' === (string) get_user_meta($id, 'user_action_status', true);
    }

    /** Only persistent profile settings. Never copy orders, sellers, carts, sessions or payment history. */
    public static function profile_keys() {
        return array(
            'first_name', 'last_name', 'description', 'nickname',
            'ur_form_id', 'ur_login_option', 'ur_user_status', 'ur_confirm_email',
            'ur_admin_approval_after_email_confirmation',
            'user_registration_name_of_school_business', 'user_registration_input_company',
            'school_name', 'organization_name', 'user_registration_input_box_phone',
            'user_registration_street_address', 'user_registration_City2',
            'user_registration_select_state', 'user_registration_input_box_zip',
            'user_registration_input_box_1595312762', 'chairperson_phone', 'phone_number',
            'billing_first_name', 'billing_last_name', 'billing_company', 'billing_address_1',
            'billing_address_2', 'billing_city', 'billing_state', 'billing_postcode',
            'billing_country', 'billing_phone', 'billing_email',
            'shipping_first_name', 'shipping_last_name', 'shipping_company', 'shipping_address_1',
            'shipping_address_2', 'shipping_city', 'shipping_state', 'shipping_postcode',
            'shipping_country', 'shipping_phone',
            'user_registration_participants_no', 'user_registration_fundraiser_preference',
            'user_registration_radio_1788399847', 'user_registration_brochure_choice', 'brochure_choice',
            'user_registration_check_box_1791918628',
            'user_registration_fr_goal', 'user_registration_selling_goal',
            'user_registration_reason_for_fundraising', 'fund_profile_description',
            'fund_profile_image', 'user_registration_image_url_profile', 'image_url_profile',
            'user_registration_profile_pic_url', 'profile_pic_url', 'ur_profile_pic_url',
            'user_registration_reference', 'user_registration_referral_name',
            'user_registration_referral_email',
            'user_registration_check_payable', 'check_payable', 'user_registration_input_box_1693293590',
        );
    }

    public static function validate_dates($start, $end) {
        $zone = wp_timezone();
        $dates = array();
        foreach (array($start, $end) as $value) {
            $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value, $zone);
            if (!$date || $date->format('Y-m-d') !== $value) {
                throw new RuntimeException('Please enter valid start and end dates.');
            }
            $dates[] = $date;
        }
        if ($dates[0] < new DateTimeImmutable('today', $zone)) {
            throw new RuntimeException('The new campaign must start today or later.');
        }
        if ($dates[1] < $dates[0]) {
            throw new RuntimeException('The end date must be on or after the start date.');
        }
    }

    /** All tables changed by this service must support rollback. */
    private static function require_transactions() {
        global $wpdb;
        if (is_multisite()) {
            throw new RuntimeException('This rebooking flow requires a single-site WordPress installation.');
        }
        foreach (array($wpdb->users, $wpdb->usermeta, $wpdb->postmeta) as $table) {
            $engine = $wpdb->get_var($wpdb->prepare(
                'SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = %s', $table
            ));
            if ('innodb' !== strtolower((string) $engine)) {
                throw new RuntimeException('Rebooking is temporarily unavailable. Please contact GLOW support.');
            }
        }
    }

    private static function check_db() {
        global $wpdb;
        if ($wpdb->last_error) {
            throw new RuntimeException('The new campaign could not be saved. Your previous campaign is unchanged.');
        }
    }

    /** Caller must authenticate the source account before entering this service. */
    public static function rebook($source_id, $start, $end) {
        global $wpdb;
        self::validate_dates($start, $end);
        self::require_transactions();
        // Serialize representative-list edits as well as repeat clicks on a source account.
        $lock = 'grb:' . substr(hash('sha256', DB_NAME . $wpdb->prefix), 0, 40);
        if ('1' !== (string) $wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s, 10)', $lock))) {
            throw new RuntimeException('Another booking is in progress. Please try again in a moment.');
        }
        $source = null;
        $new_id = 0;
        $transaction = false;
        $rep_post = 0;
        try {
            clean_user_cache($source_id);
            $source = get_userdata($source_id);
            if (!self::is_fundraiser($source)) {
                throw new RuntimeException('Please sign in with your fundraiser account.');
            }
            $existing = (int) get_user_meta($source_id, '_grb_next_campaign', true);
            if ($existing) {
                $child = get_userdata($existing);
                if (self::is_fundraiser($child) && (int) get_user_meta($existing, '_grb_previous_campaign', true) === (int) $source_id) {
                    return array('id' => $existing, 'created' => false);
                }
                throw new RuntimeException('Please contact GLOW support about your previous booking.');
            }
            if (!self::is_closed($source_id)) {
                throw new RuntimeException('Close out your current fundraiser before booking a new campaign.');
            }
            if (get_user_meta($source_id, 'ur_disable_users', true)) {
                throw new RuntimeException('Your account is disabled. Please contact GLOW support.');
            }
            if (!is_email($source->user_email) || get_user_meta($source_id, '_grb_archived', true)) {
                throw new RuntimeException('Please use your current fundraiser account.');
            }
            // Resolve the current representative payment post, rather than reusing a historical one.
            $referral = (string) get_user_meta($source_id, 'user_registration_referral_email', true);
            if ($referral !== '') {
                $rep = get_user_by('email', $referral);
                if ($rep && in_array('representative', $rep->roles, true)) {
                    $rep_post = (int) get_user_meta($rep->ID, 'um_unique_payment_id', true);
                    if (!$rep_post || !get_post($rep_post)) {
                        throw new RuntimeException('Your representative assignment needs an update. Please contact GLOW support.');
                    }
                }
            }
            if (false === $wpdb->query('START TRANSACTION')) {
                throw new RuntimeException('Rebooking is temporarily unavailable. Please try again.');
            }
            $transaction = true;
            $archive_login = 'grb_archive_' . $source_id . '_' . strtolower(wp_generate_password(10, false));
            // Same format as the legacy handler, e.g. rebook_2024-05-08_015256_chair@example.com.
            $archive_email = 'rebook_' . current_time('Y-m-d_His') . '_' . $source->user_email;
            // Query the table directly: the user cache can still hold a rolled-back archive email.
            if (strlen($archive_email) > 100 || $wpdb->get_var($wpdb->prepare("SELECT ID FROM {$wpdb->users} WHERE user_email = %s", $archive_email))) {
                $archive_email = $archive_login . '@rebook.invalid';
            }
            // Update directly: archive identity must not send an email-change notification.
            $changed = $wpdb->update($wpdb->users, array(
                'user_login' => $archive_login,
                'user_email' => $archive_email,
                'user_activation_key' => '',
            ), array('ID' => $source_id));
            if (1 !== $changed) {
                throw new RuntimeException('Your previous campaign could not be archived. Please try again.');
            }
            clean_user_cache($source);
            $new_id = wp_insert_user(array(
                'user_login' => $source->user_login,
                'user_email' => $source->user_email,
                'user_pass' => wp_generate_password(64, true, true),
                'display_name' => $source->display_name,
                'user_nicename' => 'fundraiser-' . $source_id . '-' . strtolower(wp_generate_password(8, false)),
                'role' => 'fundraiser',
            ));
            if (is_wp_error($new_id)) {
                $new_id = 0;
                throw new RuntimeException('The new account could not be created. Please try again.');
            }
            self::check_db();
            // Preserve the password HASH exactly; never store or log the submitted password.
            if (false === $wpdb->update($wpdb->users, array('user_pass' => $source->user_pass), array('ID' => $new_id))) {
                throw new RuntimeException('The password could not be transferred. Please try again.');
            }
            clean_user_cache($new_id);
            foreach (self::profile_keys() as $key) {
                if (metadata_exists('user', $source_id, $key)) {
                    // wp_insert_user may already have created first_name/nickname defaults.
                    delete_user_meta($new_id, $key);
                    self::check_db();
                }
                foreach (get_user_meta($source_id, $key, false) as $value) {
                    add_user_meta($new_id, $key, wp_slash($value));
                    self::check_db();
                }
            }
            $new_meta = array(
                'user_registration_start_date' => $start,
                'user_registration_end_date' => $end,
                'user_status' => '1', 'user_action_status' => '1',
                'user_registration_radio_1534260773' => 'Yes',
                'user_email' => $source->user_email,
                '_grb_previous_campaign' => (int) $source_id,
                '_grb_booked_at' => current_time('mysql', true),
            );
            foreach ($new_meta as $key => $value) {
                update_user_meta($new_id, $key, $value);
                self::check_db();
            }
            if ($rep_post) {
                update_user_meta($new_id, 'referral_payment_postid', $rep_post);
                self::check_db();
                $list = array_values(array_filter(array_map('trim', explode(',', (string) get_post_meta($rep_post, 'fundraiser_ids', true))), 'strlen'));
                if (!in_array((string) $new_id, $list, true)) {
                    $list[] = (string) $new_id;
                    update_post_meta($rep_post, 'fundraiser_ids', implode(',', $list));
                    self::check_db();
                }
            }
            foreach (array(
                '_grb_archived' => '1', '_grb_next_campaign' => $new_id,
                '_grb_original_email' => $source->user_email,
                '_grb_original_login' => $source->user_login,
                'rebook_yes' => 'yes', 'rebook_date' => current_time('Y-m-d'),
            ) as $key => $value) {
                update_user_meta($source_id, $key, $value);
                self::check_db();
            }
            // Integration/testing hook runs inside the transaction: exceptions roll back the whole transfer.
            do_action('grb_before_commit', $new_id, $source_id);
            if (false === $wpdb->query('COMMIT')) {
                throw new RuntimeException('The new campaign could not be saved. Please try again.');
            }
            $transaction = false;
        } catch (Throwable $error) {
            if ($transaction) {
                $wpdb->query('ROLLBACK');
            }
            if ($new_id) {
                clean_user_cache($new_id);
                wp_cache_delete($new_id, 'user_meta');
            }
            throw $error;
        } finally {
            if ($source) {
                clean_user_cache($source);
                wp_cache_delete($source_id, 'user_meta');
            }
            if ($rep_post) {
                wp_cache_delete($rep_post, 'post_meta');
            }
            $wpdb->get_var($wpdb->prepare('SELECT RELEASE_LOCK(%s)', $lock));
        }
        // Revoke sessions on the old campaign only after the transfer succeeds.
        WP_Session_Tokens::get_instance($source_id)->destroy_all();
        self::qr_codes($new_id);
        return array('id' => $new_id, 'created' => true);
    }

    private static function qr_codes($id) {
        try {
            $lib = defined('GLOW_FC_DIR') ? GLOW_FC_DIR . 'includes/phpqrcode/qrlib.php' : '';
            if (!class_exists('QRcode') && $lib && is_file($lib)) {
                require_once $lib;
            }
            if (!class_exists('QRcode') || !function_exists('imagepng')) {
                return;
            }
            $uploads = wp_upload_dir();
            if ($uploads['error']) {
                return;
            }
            $dir = trailingslashit($uploads['basedir']) . 'qrcodes/';
            if (!wp_mkdir_p($dir)) {
                return;
            }
            foreach (array(
                'fundraiser_barcode_link' => array('image_' . $id . '.png', home_url('/profile/' . $id)),
                'fundraiser_seller_register_barcode_link' => array('image_seller_register' . $id . '.png', add_query_arg('frid', $id, home_url('/seller-register/'))),
            ) as $key => $data) {
                QRcode::png($data[1], $dir . $data[0], 'L', 4, 2);
                if (is_file($dir . $data[0])) {
                    update_user_meta($id, $key, trailingslashit($uploads['baseurl']) . 'qrcodes/' . $data[0]);
                }
            }
        } catch (Throwable $error) {
            error_log('[GLOW Rebooking] QR generation failed for campaign ' . (int) $id);
        }
    }
}
