<?php
defined('ABSPATH') || exit;

final class Glow_FC_Rebooking {
    const BAD_LOGIN = 'Your email or password is incorrect.';

    public static function boot() {
        add_shortcode('glow_fundraiser_rebook_gate', array(__CLASS__, 'gate'));
        add_shortcode('glow_book_next_year', array(__CLASS__, 'dashboard'));
        add_action('wp_enqueue_scripts', array(__CLASS__, 'assets'));
        add_action('wp_footer', array(__CLASS__, 'dashboard_dialog'));
        add_action('wp_ajax_grb_flow', array(__CLASS__, 'request'));
        add_action('wp_ajax_nopriv_grb_flow', array(__CLASS__, 'request'));
        add_filter('wp_authenticate_user', array(__CLASS__, 'block_archive_login'), 99);
    }

    public static function legacy_request() {
        // Old callers must upgrade to a nonce + date form; never rename an account silently.
        wp_send_json_error(array('message' => 'Please reload the dashboard and use Book Next Year to choose your new dates.'), 409);
    }

    public static function block_archive_login($user) {
        if ($user instanceof WP_User && get_user_meta($user->ID, '_grb_archived', true)) {
            return new WP_Error('grb_archived', 'This campaign is archived. Please sign in using your usual email for your current campaign.');
        }
        return $user;
    }

    public static function assets() {
        $url = plugin_dir_url(__FILE__);
        // Load once site-wide so Elementor popups inserted after page load work too.
        wp_enqueue_style('glow-rebooking', $url . 'assets/rebooking.css', array(), filemtime(__DIR__ . '/assets/rebooking.css'));
        wp_enqueue_script('glow-rebooking', $url . 'assets/rebooking.js', array(), filemtime(__DIR__ . '/assets/rebooking.js'), true);
        wp_localize_script('glow-rebooking', 'GLOW_REBOOK', array('ajax' => admin_url('admin-ajax.php')));
    }

    /** Fresh nonces come from an uncached request, not Elementor's cached HTML. */
    public static function request() {
        nocache_headers();
        try {
            if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
                self::fail('Please submit the form again.', 405);
            }
            $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
            if (($origin && !self::same_origin($origin)) || ($_SERVER['HTTP_SEC_FETCH_SITE'] ?? '') === 'cross-site') {
                self::fail('Please open this form on the GLOW website.', 403);
            }
            $op = self::input('operation');
            if ($op === 'bootstrap') {
                wp_send_json_success(array('nonce' => wp_create_nonce('grb_flow'), 'today' => current_time('Y-m-d')));
            }
            if (!wp_verify_nonce(self::input('nonce'), 'grb_flow')) {
                self::fail('Your form expired. Please reload and try again.', 403);
            }
            if ($op === 'lookup') {
                self::rate_limit('lookup', 30);
                $email = sanitize_email(self::input('email'));
                if (!is_email($email)) {
                    self::fail('Please enter a valid email address.');
                }
                $user = get_user_by('email', $email);
                $state = !$user ? 'new' : (Glow_FC_Rebooking_Campaigns::is_fundraiser($user) && Glow_FC_Rebooking_Campaigns::is_closed($user->ID) ? 'closed' : 'active');
                wp_send_json_success(array(
                    'state' => $state,
                    'login' => wp_login_url(home_url('/fundraiser-dashboard/')),
                    'register' => home_url('/fundraiser-register/'),
                ));
            }
            if ($op === 'login') {
                self::rate_limit('password', 10);
                $email = sanitize_email(self::input('email'));
                if (!is_email($email)) {
                    self::fail(self::BAD_LOGIN, 401);
                }
                self::rate_limit('account:' . strtolower($email), 8, false);
                // wp_signon runs the normal authenticate filters (User Registration approval, archived campaigns).
                $user = wp_signon(array('user_login' => $email, 'user_password' => self::input('password'), 'remember' => true), is_ssl());
                if (is_wp_error($user)) {
                    $code = $user->get_error_code();
                    $known = in_array($code, array('invalid_email', 'invalid_username', 'incorrect_password', 'empty_password', 'empty_username'), true);
                    // The lookup step already confirmed the email, so a password error points at the password field.
                    $field = in_array($code, array('incorrect_password', 'empty_password'), true) ? 'password' : '';
                    self::fail($known ? self::BAD_LOGIN : wp_strip_all_tags($user->get_error_message()), 401, $field);
                }
                $to = apply_filters('login_redirect', home_url('/fundraiser-dashboard/'), '', $user);
                wp_send_json_success(array('redirect' => wp_validate_redirect($to, home_url('/fundraiser-dashboard/'))));
            }
            if ($op !== 'rebook') {
                self::fail('Please choose a valid action.');
            }
            if (self::input('confirm') !== 'yes') {
                self::fail('Please confirm that you want to book a new campaign.');
            }
            $start = self::input('start_date');
            $end = self::input('end_date');
            Glow_FC_Rebooking_Campaigns::validate_dates($start, $end);
            $details = Glow_FC_Rebooking_Campaigns::campaign_details(
                self::input('sellers'),
                self::input('address_changed') === 'yes'
                    ? array('street' => self::input('street'), 'city' => self::input('city'), 'state' => self::input('state'), 'zip' => self::input('zip'))
                    : null
            );
            if (self::input('mode') === 'dashboard') {
                $user = wp_get_current_user();
                if (!is_user_logged_in() || !Glow_FC_Rebooking_Campaigns::is_fundraiser($user)) {
                    self::fail('Please sign in to your fundraiser account first.', 401);
                }
                // No client-supplied fundraiser ID is ever trusted.
            } else {
                self::rate_limit('password', 10);
                $email = sanitize_email(self::input('email'));
                if (!is_email($email)) {
                    self::fail(self::BAD_LOGIN, 401);
                }
                self::rate_limit('account:' . strtolower($email), 8, false);
                $password = self::input('password');
                $user = $password !== '' ? wp_authenticate($email, $password) : new WP_Error('empty_password');
                unset($password);
                if (is_wp_error($user) || !Glow_FC_Rebooking_Campaigns::is_fundraiser($user)) {
                    $field = is_wp_error($user) && in_array($user->get_error_code(), array('incorrect_password', 'empty_password'), true) ? 'password' : '';
                    self::fail(self::BAD_LOGIN, 401, $field);
                }
                // A lost response / repeated request with the original email must not create another campaign.
                if (!Glow_FC_Rebooking_Campaigns::is_closed($user->ID)) {
                    if (get_user_meta($user->ID, '_grb_previous_campaign', true)) {
                        self::sign_in($user->ID);
                        wp_send_json_success(array('redirect' => home_url('/fundraiser-dashboard/'), 'created' => false));
                    }
                    self::fail('Your fundraiser is still active. Please log in to your current dashboard.', 409);
                }
            }
            $result = Glow_FC_Rebooking_Campaigns::rebook($user->ID, $start, $end, $details);
            self::sign_in($result['id']);
            wp_send_json_success(array('redirect' => home_url('/fundraiser-dashboard/'), 'created' => $result['created']));
        } catch (Throwable $error) {
            // Do not expose database exceptions, secrets, or submitted passwords in responses or logs.
            $safe = $error instanceof RuntimeException ? $error->getMessage() : 'Rebooking could not be completed. Please try again or contact GLOW support.';
            self::fail($safe);
        }
    }

    private static function same_origin($origin) {
        $site = wp_parse_url(home_url());
        $request = wp_parse_url($origin);
        return is_array($request) && strtolower($request['host'] ?? '') === strtolower($site['host'] ?? '')
            && ($request['scheme'] ?? '') === ($site['scheme'] ?? '')
            && ($request['port'] ?? null) === ($site['port'] ?? null);
    }

    private static function sign_in($id) {
        // Empty the previous campaign's shopping cart/session before switching identities.
        if (function_exists('WC') && WC()->cart) {
            WC()->cart->empty_cart();
        }
        if (function_exists('WC') && WC()->session) {
            WC()->session->destroy_session();
        }
        wp_clear_auth_cookie();
        wp_set_current_user($id);
        wp_set_auth_cookie($id, false, is_ssl());
        do_action('wp_login', get_userdata($id)->user_login, get_userdata($id));
    }

    private static function input($key) {
        return isset($_POST[$key]) && is_string($_POST[$key]) ? wp_unslash($_POST[$key]) : '';
    }

    /** $field names the form input the error belongs to, so the popup can mark it. */
    private static function fail($message, $status = 400, $field = '') {
        wp_send_json_error($field !== '' ? array('message' => $message, 'field' => $field) : array('message' => $message), $status);
    }

    /** DB advisory lock keeps rate counters atomic even without a persistent object cache. */
    private static function rate_limit($purpose, $limit, $use_ip = true) {
        global $wpdb;
        $identity = $use_ip ? ($_SERVER['REMOTE_ADDR'] ?? 'unknown') : '';
        // Trust the connection IP, never a user-controlled X-Forwarded-For header.
        $key = 'grb_rl_' . substr(hash_hmac('sha256', $purpose . $identity, wp_salt('auth')), 0, 40);
        if ('1' !== (string) $wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s, 2)', $key))) {
            self::fail('Please wait a moment before trying again.', 429);
        }
        $blocked = false;
        try {
            $data = get_transient($key);
            $now = time();
            if (!is_array($data) || ($data['until'] ?? 0) <= $now) {
                $data = array('count' => 0, 'until' => $now + 15 * MINUTE_IN_SECONDS);
            }
            $data['count']++;
            $blocked = $data['count'] > $limit;
            set_transient($key, $data, max(1, $data['until'] - $now));
        } finally {
            $wpdb->get_var($wpdb->prepare('SELECT RELEASE_LOCK(%s)', $key));
        }
        if ($blocked) {
            self::fail('Too many attempts. Please wait 15 minutes and try again.', 429);
        }
    }

    public static function gate() {
        if (is_user_logged_in()) {
            return self::signed_in();
        }
        ob_start();
        $mode = 'gate';
        require __DIR__ . '/views/form.php';
        return ob_get_clean();
    }

    /**
     * Signed-in visitors skip the email check:
     * closed fundraiser → session rebook form; active fundraiser → close out first; other roles → not a fundraiser account.
     */
    private static function signed_in() {
        $user = wp_get_current_user();
        $fundraiser = Glow_FC_Rebooking_Campaigns::is_fundraiser($user);
        if ($fundraiser && Glow_FC_Rebooking_Campaigns::is_closed($user->ID)) {
            return self::dashboard();
        }
        if ($fundraiser) {
            $to = home_url('/fundraiser-dashboard/');
            $message = 'Your fundraiser is still active. Please close it out from your dashboard before booking a new fundraiser.';
        } else {
            $fallback = home_url('/my-account/');
            $to = user_can($user, 'manage_options') ? admin_url() : apply_filters('login_redirect', $fallback, '', $user);
            $to = wp_validate_redirect($to, $fallback);
            $message = 'The account you are signed in with is not a fundraiser account. Log out and sign in with your fundraiser account to book a fundraiser.';
        }
        $logout = wp_logout_url(home_url('/fundraiser-register/'));
        // No data-grb attribute: the email/rebook script must not initialize or hide this view.
        return '<section class="grb grb-signed-in" data-mode="gate" aria-label="Fundraiser registration">'
            . '<p>You’re signed in as <strong>' . esc_html($user->user_email) . '</strong>.</p>'
            . '<p>' . esc_html($message) . '</p>'
            . '<a class="grb-button" href="' . esc_url($to) . '">Access Dashboard <span aria-hidden="true">&rarr;</span></a>'
            . '<p class="grb-note">Not you? <a class="grb-text-link" href="' . esc_url($logout) . '">Log out</a></p>'
            . '</section>';
    }

    public static function dashboard() {
        $user = wp_get_current_user();
        if (!Glow_FC_Rebooking_Campaigns::is_fundraiser($user) || !Glow_FC_Rebooking_Campaigns::is_closed($user->ID)) {
            return '';
        }
        ob_start();
        $mode = 'dashboard';
        require __DIR__ . '/views/form.php';
        return ob_get_clean();
    }

    public static function dashboard_dialog() {
        if (!is_page('fundraiser-dashboard') || (isset($_GET['frid']) && (int) $_GET['frid'] !== get_current_user_id())) {
            return;
        }
        $form = self::dashboard();
        if ($form === '') {
            return;
        }
        echo '<dialog class="grb-dialog" data-grb-dialog aria-label="Book Next Year"><button type="button" class="grb-close" data-grb-close aria-label="Close">&times;</button>' . $form . '</dialog>';
    }
}
