<?php
defined('ABSPATH') || exit;

/**
 * Seller rebooking: from the seller dashboard, a seller whose fundraiser is over
 * (closed out, deleted, or never linked) enters a new Fundraiser ID and seller goal.
 *
 * Same model as fundraiser rebooking: the old seller account is archived (it keeps
 * its orders and reports under the old user ID) and a new seller account takes over
 * the same login, email and password, so the store link and sign-in do not change.
 */
final class Glow_FC_Seller_Rebooking {
    const NONCE = 'gsr_flow';

    /** Persistent seller profile only. Never orders, carts, sessions, QR codes or campaign state. */
    const PROFILE_KEYS = array(
        'first_name', 'last_name', 'nickname', 'description',
        'ur_form_id', 'ur_login_option', 'ur_user_status', 'ur_confirm_email',
        'ur_admin_approval_after_email_confirmation', 'ur_registered_language',
        'user_registration_profile_pic_url', 'user_registration_image_url_profile', 'image_url_profile',
        'fund_profile_image', 'seller_profile_image', 'seller_profile_description',
        'user_registration_billing_phone', 'user_registration_phone',
        'billing_first_name', 'billing_last_name', 'billing_phone', 'billing_email',
        'shipping_first_name', 'shipping_last_name', 'shipping_company', 'shipping_address_1',
        'shipping_address_2', 'shipping_city', 'shipping_state', 'shipping_postcode', 'shipping_country',
        'glow_sms_optin', 'glow_sms_optin_ip', 'glow_sms_optin_at',
    );

    public static function boot() {
        // Signed-in sellers only: there is deliberately no wp_ajax_nopriv_ handler.
        add_action('wp_ajax_gsr_flow', array(__CLASS__, 'request'));
        add_action('wp_enqueue_scripts', array(__CLASS__, 'assets'));
        add_action('wp_footer', array(__CLASS__, 'dialog'));
    }

    public static function is_seller($user) {
        return $user instanceof WP_User && in_array('seller', $user->roles, true)
            && !array_intersect(array('administrator', 'representative', 'fundraiser'), $user->roles)
            && !user_can($user, 'manage_options');
    }

    /**
     * State of the fundraiser a seller is linked to:
     * 'none' (no ID), 'deleted' (no such fundraiser any more), 'closed' (closed out) or 'active'.
     */
    public static function fundraiser_state($fid) {
        $fid = absint($fid);
        if (!$fid) {
            return 'none';
        }
        $fundraiser = get_userdata($fid);
        if (!$fundraiser || !in_array('fundraiser', $fundraiser->roles, true)) {
            return 'deleted';
        }
        return Glow_FC_Rebooking_Campaigns::is_closed($fid) ? 'closed' : 'active';
    }

    public static function linked_fundraiser($seller_id) {
        return absint(get_user_meta($seller_id, 'user_registration_seller_fundraiser', true));
    }

    public static function can_rebook($seller_id) {
        $user = get_userdata($seller_id);
        return self::is_seller($user)
            && !get_user_meta($seller_id, '_grb_archived', true)
            && !get_user_meta($seller_id, '_gsr_next_seller', true)
            && 'active' !== self::fundraiser_state(self::linked_fundraiser($seller_id));
    }

    private static function on_dashboard() {
        return is_page('seller-dashboard') && is_user_logged_in() && self::can_rebook(get_current_user_id());
    }

    public static function assets() {
        if (!self::on_dashboard()) {
            return;
        }
        $url = plugin_dir_url(__FILE__);
        // rebooking.css (the shared .grb form styles) is already enqueued site-wide by Glow_FC_Rebooking.
        wp_enqueue_script('glow-seller-rebooking', $url . 'assets/seller-rebooking.js', array(), filemtime(__DIR__ . '/assets/seller-rebooking.js'), true);
        wp_localize_script('glow-seller-rebooking', 'GLOW_SELLER_REBOOK', array('ajax' => admin_url('admin-ajax.php')));
    }

    public static function dialog() {
        if (!self::on_dashboard()) {
            return;
        }
        ob_start();
        require __DIR__ . '/views/seller-form.php';
        echo '<dialog class="grb-dialog" data-gsr-dialog aria-label="Re-book for a new fundraiser"><button type="button" class="grb-close" data-gsr-close aria-label="Close">&times;</button>'
            . ob_get_clean() . '</dialog>';
    }

    /** Validates a fundraiser a seller may join; returns its organization name. */
    public static function joinable_fundraiser($fid, $current_fid = 0) {
        $fid = absint($fid);
        $fundraiser = $fid ? get_userdata($fid) : false;
        if (!$fundraiser || !Glow_FC_Rebooking_Campaigns::is_fundraiser($fundraiser) || get_user_meta($fid, '_grb_archived', true)) {
            throw new RuntimeException('We could not find a fundraiser with that ID. Please check the Fundraiser ID from your chairperson.');
        }
        if ($fid === absint($current_fid)) {
            throw new RuntimeException('That is the fundraiser you are already registered with. Please enter the new Fundraiser ID.');
        }
        if (Glow_FC_Rebooking_Campaigns::is_closed($fid)) {
            throw new RuntimeException('That fundraiser has already been closed out. Please check the Fundraiser ID with your chairperson.');
        }
        $org = trim((string) get_user_meta($fid, 'user_registration_name_of_school_business', true));
        return $org !== '' ? $org : trim($fundraiser->first_name . ' ' . $fundraiser->last_name);
    }

    /** Seller goal as a plain dollar amount, the format registration stores. */
    public static function clean_goal($raw) {
        $goal = preg_replace('/[^0-9.]/', '', (string) $raw);
        if ($goal === '' || !is_numeric($goal) || (float) $goal <= 0 || (float) $goal > 100000) {
            throw new RuntimeException('Please enter your seller goal as a dollar amount, e.g. 500.');
        }
        $goal = round((float) $goal, 2);
        return (string) (floor($goal) == $goal ? (int) $goal : $goal);
    }

    public static function request() {
        nocache_headers();
        try {
            if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
                Glow_FC_Rebooking::fail('Please submit the form again.', 405);
            }
            $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
            if (($origin && !Glow_FC_Rebooking::same_origin($origin)) || ($_SERVER['HTTP_SEC_FETCH_SITE'] ?? '') === 'cross-site') {
                Glow_FC_Rebooking::fail('Please open this form on the GLOW website.', 403);
            }
            $user = wp_get_current_user();
            if (!self::is_seller($user)) {
                Glow_FC_Rebooking::fail('Please sign in to your seller account first.', 401);
            }
            $op = Glow_FC_Rebooking::input('operation');
            if ($op === 'bootstrap') {
                wp_send_json_success(array('nonce' => wp_create_nonce(self::NONCE)));
            }
            if (!wp_verify_nonce(Glow_FC_Rebooking::input('nonce'), self::NONCE)) {
                Glow_FC_Rebooking::fail('Your form expired. Please reload the page and try again.', 403);
            }
            // A repeated request after a lost response: the account it signed in with is already the new one.
            if (!self::can_rebook($user->ID)) {
                Glow_FC_Rebooking::fail('Your seller account is linked to an active fundraiser, so it cannot be re-booked.', 409);
            }
            $current = self::linked_fundraiser($user->ID);
            if ($op === 'fundraiser') {
                Glow_FC_Rebooking::rate_limit('seller-lookup:' . $user->ID, 40, false);
                $org = self::joinable_fundraiser(Glow_FC_Rebooking::input('fundraiser_id'), $current);
                wp_send_json_success(array('org' => $org));
            }
            if ($op !== 'rebook') {
                Glow_FC_Rebooking::fail('Please choose a valid action.');
            }
            Glow_FC_Rebooking::rate_limit('seller-rebook:' . $user->ID, 10, false);
            if (Glow_FC_Rebooking::input('confirm') !== 'yes') {
                Glow_FC_Rebooking::fail('Please confirm that you want to re-book.');
            }
            $fid = absint(Glow_FC_Rebooking::input('fundraiser_id'));
            self::joinable_fundraiser($fid, $current);
            $goal = self::clean_goal(Glow_FC_Rebooking::input('goal'));
            $result = self::rebook($user->ID, $fid, $goal);
            Glow_FC_Rebooking::sign_in($result['id']);
            wp_send_json_success(array('redirect' => home_url('/seller-dashboard/')));
        } catch (Throwable $error) {
            // Never expose database errors in the response.
            $safe = $error instanceof RuntimeException ? $error->getMessage() : 'Re-booking could not be completed. Please try again or contact GLOW support.';
            Glow_FC_Rebooking::fail($safe);
        }
    }

    /** Caller must have authenticated $source_id as the signed-in seller. */
    public static function rebook($source_id, $fid, $goal) {
        global $wpdb;
        Glow_FC_Rebooking_Campaigns::require_transactions();
        $lock = 'gsr:' . substr(hash('sha256', DB_NAME . $wpdb->prefix . ':' . (int) $source_id), 0, 40);
        if ('1' !== (string) $wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s, 10)', $lock))) {
            throw new RuntimeException('Your re-booking is already in progress. Please wait a moment.');
        }
        $source = null;
        $new_id = 0;
        $transaction = false;
        try {
            clean_user_cache($source_id);
            wp_cache_delete($source_id, 'user_meta');
            $source = get_userdata($source_id);
            if (!self::can_rebook($source_id)) {
                throw new RuntimeException('Your seller account is linked to an active fundraiser, so it cannot be re-booked.');
            }
            if (get_user_meta($source_id, 'ur_disable_users', true)) {
                throw new RuntimeException('Your account is disabled. Please contact GLOW support.');
            }
            if (!is_email($source->user_email)) {
                throw new RuntimeException('Please contact GLOW support to update your seller account email first.');
            }
            if (false === $wpdb->query('START TRANSACTION')) {
                throw new RuntimeException('Re-booking is temporarily unavailable. Please try again.');
            }
            $transaction = true;
            $archive_login = 'gsr_archive_' . $source_id . '_' . strtolower(wp_generate_password(10, false));
            // Same email format the old seller rebooking used, e.g. rebook_2026-11-11_101500_seller@example.com.
            $archive_email = 'rebook_' . current_time('Y-m-d_His') . '_' . $source->user_email;
            if (strlen($archive_email) > 100 || $wpdb->get_var($wpdb->prepare("SELECT ID FROM {$wpdb->users} WHERE user_email = %s", $archive_email))) {
                $archive_email = $archive_login . '@rebook.invalid';
            }
            // Direct update: archiving must not send an email-change notification.
            if (1 !== $wpdb->update($wpdb->users, array(
                'user_login' => $archive_login,
                'user_email' => $archive_email,
                'user_activation_key' => '',
            ), array('ID' => $source_id))) {
                throw new RuntimeException('Your previous seller account could not be archived. Please try again.');
            }
            clean_user_cache($source);
            $new_id = wp_insert_user(array(
                'user_login' => $source->user_login,
                'user_email' => $source->user_email,
                'user_pass' => wp_generate_password(64, true, true),
                'display_name' => $source->display_name,
                'first_name' => $source->first_name,
                'last_name' => $source->last_name,
                'role' => 'seller',
            ));
            if (is_wp_error($new_id)) {
                $new_id = 0;
                throw new RuntimeException('The new seller account could not be created. Please try again.');
            }
            Glow_FC_Rebooking_Campaigns::check_db();
            // Carry the password HASH over; the password itself is never seen.
            if (false === $wpdb->update($wpdb->users, array('user_pass' => $source->user_pass), array('ID' => $new_id))) {
                throw new RuntimeException('Your password could not be carried over. Please try again.');
            }
            clean_user_cache($new_id);
            foreach (self::PROFILE_KEYS as $key) {
                if (!metadata_exists('user', $source_id, $key)) {
                    continue;
                }
                delete_user_meta($new_id, $key);
                foreach (get_user_meta($source_id, $key, false) as $value) {
                    add_user_meta($new_id, $key, wp_slash($value));
                }
                Glow_FC_Rebooking_Campaigns::check_db();
            }
            foreach (array(
                // Same keys a new seller registration writes (glow_handle_seller_form_submission).
                'user_registration_seller_fundraiser' => (string) $fid,
                'seller_fundraiser' => (string) $fid,
                'fundraiser_id' => (string) $fid,
                'user_registration_seller_goal' => $goal,
                'seller_goal' => $goal,
                'user_status' => '1',
                'user_action_status' => '1',
                '_gsr_previous_seller' => (int) $source_id,
                '_gsr_booked_at' => current_time('mysql', true),
            ) as $key => $value) {
                update_user_meta($new_id, $key, $value);
                Glow_FC_Rebooking_Campaigns::check_db();
            }
            foreach (array(
                // _grb_archived blocks sign-in to the archived account (Glow_FC_Rebooking::block_archive_login).
                '_grb_archived' => '1',
                '_gsr_next_seller' => $new_id,
                '_gsr_original_email' => $source->user_email,
                '_gsr_original_login' => $source->user_login,
                'rebook_yes' => 'yes',
                'rebook_date' => current_time('Y-m-d'),
                'rebook_email' => $source->user_email,
            ) as $key => $value) {
                update_user_meta($source_id, $key, $value);
                Glow_FC_Rebooking_Campaigns::check_db();
            }
            if (false === $wpdb->query('COMMIT')) {
                throw new RuntimeException('Your new seller account could not be saved. Please try again.');
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
            $wpdb->get_var($wpdb->prepare('SELECT RELEASE_LOCK(%s)', $lock));
        }
        WP_Session_Tokens::get_instance($source_id)->destroy_all();
        // QR code and "Seller Registered" email come from the theme; a failure there must not undo the re-booking.
        try {
            do_action('glow_fc_seller_rebooked', $new_id, $source_id);
        } catch (Throwable $error) {
            error_log('[GLOW Seller Rebooking] Follow-up failed for seller ' . (int) $new_id . ': ' . $error->getMessage());
        }
        return array('id' => $new_id);
    }
}
