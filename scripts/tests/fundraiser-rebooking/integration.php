<?php
/** Real WordPress/MySQL tests, exclusively against a fresh isolated test database. */
if (PHP_SAPI !== 'cli' || count($argv) !== 4) {
    exit("Usage: php integration.php WORDPRESS_ROOT ISOLATED_MYSQL_PORT TEMP_DIRECTORY\n");
}
$wp_root = realpath($argv[1]);
$port = (int) $argv[2];
$temp_dir = realpath($argv[3]);
if (!$wp_root || !$temp_dir || $port < 20000 || $port > 40000) {
    throw new RuntimeException('Invalid isolated environment.');
}
$db_name = 'grb_test_' . bin2hex(random_bytes(5));
$connection = new mysqli('127.0.0.1', 'root', '', '', $port);
$connection->query('CREATE DATABASE `' . $db_name . '`');
define('DB_NAME', $db_name);
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', '127.0.0.1:' . $port);
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');
define('ABSPATH', rtrim($wp_root, '/\\') . '/');
define('WP_CONTENT_DIR', $temp_dir . '/content');
define('WP_CONTENT_URL', 'http://127.0.0.1/content');
define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');
define('WPMU_PLUGIN_DIR', WP_CONTENT_DIR . '/mu-plugins');
define('WP_INSTALLING', true);
define('WP_DEBUG', false);
define('WP_USE_THEMES', false);
define('DISABLE_WP_CRON', true);
define('WP_HTTP_BLOCK_EXTERNAL', true);
define('AUTH_KEY', bin2hex(random_bytes(32)));
define('SECURE_AUTH_KEY', bin2hex(random_bytes(32)));
define('LOGGED_IN_KEY', bin2hex(random_bytes(32)));
define('NONCE_KEY', bin2hex(random_bytes(32)));
define('AUTH_SALT', bin2hex(random_bytes(32)));
define('SECURE_AUTH_SALT', bin2hex(random_bytes(32)));
define('LOGGED_IN_SALT', bin2hex(random_bytes(32)));
define('NONCE_SALT', bin2hex(random_bytes(32)));
$table_prefix = 'grb_';
$_SERVER['HTTP_HOST'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
require ABSPATH . 'wp-settings.php';
require ABSPATH . 'wp-admin/includes/upgrade.php';
add_filter('pre_wp_mail', '__return_false');
wp_install('GLOW Rebooking Tests', 'test-admin', 'nobody@example.invalid', false, '', wp_generate_password(30));
add_role('fundraiser', 'Fundraiser', array('read' => true));
add_role('representative', 'Representative', array('read' => true));
add_role('seller', 'Seller', array('read' => true));
require dirname(__DIR__, 3) . '/wp-content/plugins/glow-fundraiser-core/includes/rebooking/fundraiser-rebooking.php';
$checks = 0;
function expect($condition, $description) {
    global $checks;
    if (!$condition) {
        throw new RuntimeException('FAIL: ' . $description);
    }
    $checks++;
    echo 'PASS: ' . $description . PHP_EOL;
}
function rejects($callback, $description) {
    $rejected = false;
    try { $callback(); } catch (Throwable $e) { $rejected = true; }
    expect($rejected, $description);
}
function fundraiser($email, $closed = true) {
    $id = wp_insert_user(array('user_login' => $email, 'user_email' => $email, 'user_pass' => 'Test! Same password 123', 'role' => 'fundraiser'));
    if (is_wp_error($id)) { throw new RuntimeException($id->get_error_message()); }
    update_user_meta($id, 'user_action_status', $closed ? '2' : '1');
    return $id;
}
$today = current_time('Y-m-d');
$end = (new DateTimeImmutable('today', wp_timezone()))->modify('+21 days')->format('Y-m-d');
$source = fundraiser('chair@example.invalid');
$original = get_userdata($source);
$original_hash = $original->user_pass;
$rep = wp_insert_user(array('user_login' => 'rep', 'user_email' => 'rep@example.invalid', 'user_pass' => 'rep-test', 'role' => 'representative'));
$rep_post = wp_insert_post(array('post_title' => 'Current representative ledger', 'post_status' => 'publish'));
$historical_post = wp_insert_post(array('post_title' => 'Historical representative ledger', 'post_status' => 'publish'));
update_user_meta($rep, 'um_unique_payment_id', $rep_post);
update_post_meta($rep_post, 'fundraiser_ids', (string) $source);
update_user_meta($source, 'referral_payment_postid', $historical_post);
update_user_meta($source, 'user_registration_referral_email', 'rep@example.invalid');
update_user_meta($source, 'first_name', 'Chair');
update_user_meta($source, 'user_registration_name_of_school_business', 'A School');
update_user_meta($source, 'billing_address_1', '123 School Road');
update_user_meta($source, 'fund_profile_description', 'Our message');
update_user_meta($source, 'description', wp_slash('Our \\school and "team"'));
update_user_meta($source, 'ur_form_id', '62316');
update_user_meta($source, 'ur_login_option', 'email_confirmation');
update_user_meta($source, 'ur_confirm_email', '1');
update_user_meta($source, 'user_registration_start_date', '2025-01-01');
update_user_meta($source, 'user_registration_end_date', '2025-01-21');
update_user_meta($source, 'cart-restore', 'old cart');
update_user_meta($source, 'wc_ship_mergeOrderID', '12345');
update_user_meta($source, 'paid_status', 'paid');
update_user_meta($source, 'user_close_out_date_inactive', '2025-01-22');
update_user_meta($source, '_glow_welcome_email_sent', 'old flag');
update_user_meta($source, 'fundraiser_barcode_link', 'old-qr.png');
$seller = wp_insert_user(array('user_login' => 'seller', 'user_email' => 'seller@example.invalid', 'user_pass' => 'seller-test', 'role' => 'seller'));
update_user_meta($seller, 'user_registration_seller_fundraiser', $source);
$old_order = wp_insert_post(array('post_type' => 'shop_order', 'post_title' => 'Previous campaign order', 'post_status' => 'publish'));
update_post_meta($old_order, '_customer_user', $source);
$token = WP_Session_Tokens::get_instance($source)->create(time() + HOUR_IN_SECONDS);

rejects(function () use ($source, $end) { Glow_FC_Rebooking_Campaigns::rebook($source, '2026-02-30', $end); }, 'invalid calendar dates rejected');
rejects(function () use ($source) { Glow_FC_Rebooking_Campaigns::rebook($source, '2020-01-01', '2020-01-30'); }, 'past dates rejected');
rejects(function () use ($source, $today) { Glow_FC_Rebooking_Campaigns::rebook($source, $today, '2020-01-01'); }, 'end before start rejected');
$active = fundraiser('active@example.invalid', false);
rejects(function () use ($active, $today, $end) { Glow_FC_Rebooking_Campaigns::rebook($active, $today, $end); }, 'active fundraiser cannot rebook');
rejects(function () use ($seller, $today, $end) { Glow_FC_Rebooking_Campaigns::rebook($seller, $today, $end); }, 'seller cannot rebook as fundraiser');
expect(is_wp_error(wp_authenticate('chair@example.invalid', 'wrong')), 'wrong password rejected');

$rollback = function () { throw new RuntimeException('Simulated failure before commit'); };
add_action('grb_before_commit', $rollback);
rejects(function () use ($source, $today, $end) { Glow_FC_Rebooking_Campaigns::rebook($source, $today, $end); }, 'failure rolls back transaction');
remove_action('grb_before_commit', $rollback);
expect((int) email_exists('chair@example.invalid') === $source, 'rollback restores email ownership');
expect((int) username_exists('chair@example.invalid') === $source, 'rollback restores username ownership');
expect(!get_user_meta($source, '_grb_next_campaign', true), 'rollback clears campaign linkage');
expect(WP_Session_Tokens::get_instance($source)->verify($token), 'rollback preserves previous session');
expect(get_post_meta($rep_post, 'fundraiser_ids', true) === (string) $source, 'rollback restores representative list');

$result = Glow_FC_Rebooking_Campaigns::rebook($source, $today, $end);
$next = $result['id'];
expect($result['created'] && $next !== $source, 'creates a distinct fundraiser ID');
expect((int) email_exists('chair@example.invalid') === $next, 'same email belongs to new account');
expect((int) username_exists('chair@example.invalid') === $next, 'same username belongs to new account');
expect(get_userdata($next)->user_pass === $original_hash, 'password hash preserved exactly');
expect((int) wp_authenticate('chair@example.invalid', 'Test! Same password 123')->ID === $next, 'same password authenticates new account');
expect(is_wp_error(wp_authenticate(get_userdata($source)->user_login, 'Test! Same password 123')), 'archived login is blocked');
expect((bool) preg_match('/^rebook_\d{4}-\d{2}-\d{2}_\d{6}_chair@example\.invalid$/', get_userdata($source)->user_email), 'archived email uses rebook_date_time_email format');
expect(!WP_Session_Tokens::get_instance($source)->verify($token), 'old sessions revoked after commit');
expect(get_user_meta($next, 'user_registration_name_of_school_business', true) === 'A School', 'organization copied');
expect(get_user_meta($next, 'billing_address_1', true) === '123 School Road', 'contact address copied');
expect(get_user_meta($next, 'fund_profile_description', true) === 'Our message', 'profile message copied');
expect(get_user_meta($next, 'description', true) === 'Our \\school and "team"', 'profile values preserve literal slashes');
expect(get_user_meta($next, 'ur_form_id', true) === '62316' && get_user_meta($next, 'ur_confirm_email', true) === '1', 'registration form and email approval carry over');
expect(get_user_meta($next, 'user_registration_start_date', true) === $today && get_user_meta($next, 'user_registration_end_date', true) === $end, 'requested dates saved');
expect(get_user_meta($next, 'user_action_status', true) === '1', 'new campaign is active');
foreach (array('cart-restore', 'wc_ship_mergeOrderID', 'paid_status', 'user_close_out_date_inactive', '_glow_welcome_email_sent', 'fundraiser_barcode_link') as $key) {
    expect(!metadata_exists('user', $next, $key), 'does not copy ' . $key);
}
expect((int) get_user_meta($seller, 'user_registration_seller_fundraiser', true) === $source, 'previous sellers remain on previous campaign');
expect((int) get_post_meta($old_order, '_customer_user', true) === $source, 'previous order stays on previous campaign');
expect((int) get_user_meta($next, 'referral_payment_postid', true) === $rep_post, 'links to current representative payment post');
expect(in_array((string) $next, explode(',', get_post_meta($rep_post, 'fundraiser_ids', true)), true), 'representative list contains new fundraiser');
expect((int) get_user_meta($source, 'referral_payment_postid', true) === $historical_post, 'historical representative payment unchanged');
expect((int) get_user_meta($next, '_grb_previous_campaign', true) === $source && (int) get_user_meta($source, '_grb_next_campaign', true) === $next, 'audit links are bidirectional');
$again = Glow_FC_Rebooking_Campaigns::rebook($source, $today, $end);
expect($again['id'] === $next && !$again['created'], 'repeat click returns same campaign');

$missing = fundraiser('missing-rep@example.invalid');
update_user_meta($missing, 'user_registration_referral_email', 'rep@example.invalid');
delete_user_meta($rep, 'um_unique_payment_id');
rejects(function () use ($missing, $today, $end) { Glow_FC_Rebooking_Campaigns::rebook($missing, $today, $end); }, 'broken representative assignment fails before archiving');
expect((int) email_exists('missing-rep@example.invalid') === $missing, 'assignment failure preserves original email');
$disabled = fundraiser('disabled@example.invalid');
update_user_meta($disabled, 'ur_disable_users', '1');
rejects(function () use ($disabled, $today, $end) { Glow_FC_Rebooking_Campaigns::rebook($disabled, $today, $end); }, 'disabled account cannot rebook');

wp_set_current_user($active);
expect(Glow_FC_Rebooking::dashboard() === '', 'active account does not see dashboard rebook form');
wp_set_current_user($missing);
expect(strpos(Glow_FC_Rebooking::dashboard(), 'name="start_date"') !== false, 'closed account sees date form');
wp_set_current_user(0);
expect(strpos(Glow_FC_Rebooking::gate(), 'data-grb-lookup') !== false, 'popup shortcode renders email check');
require dirname(__DIR__, 3) . '/wp-content/plugins/glow-fundraiser-core/includes/rebooking/rebooking-post-ajax.php';
expect(false !== has_action('wp_ajax_nopriv_wp_rebooking_ajax', 'wp_rebooking_ajax'), 'legacy route uses Core compatibility handler');

// Exercise the actual AJAX route and auth cookies over HTTP, not just service methods.
$socket = stream_socket_server('tcp://127.0.0.1:0', $errno, $errstr);
if (!$socket) { throw new RuntimeException('Could not reserve local test HTTP port'); }
$address = stream_socket_get_name($socket, false);
fclose($socket);
$url = 'http://' . $address;
$constants = array();
foreach (array('ABSPATH', 'DB_NAME', 'DB_USER', 'DB_PASSWORD', 'DB_HOST', 'DB_CHARSET', 'DB_COLLATE',
    'WP_CONTENT_DIR', 'WP_CONTENT_URL', 'WP_PLUGIN_DIR', 'WPMU_PLUGIN_DIR', 'WP_INSTALLING',
    'WP_DEBUG', 'WP_USE_THEMES', 'DISABLE_WP_CRON', 'WP_HTTP_BLOCK_EXTERNAL',
    'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT') as $name) {
    $constants[$name] = constant($name);
}
$fixture_file = $temp_dir . '/http-fixture.json';
file_put_contents($fixture_file, json_encode(array('constants' => $constants, 'url' => $url)));
putenv('GRB_TEST_FIXTURE=' . $fixture_file);
$server = proc_open(array(PHP_BINARY, '-S', $address, __DIR__ . '/http-router.php'), array(
    0 => array('pipe', 'r'), 1 => array('file', $temp_dir . '/http-server.log', 'a'),
    2 => array('file', $temp_dir . '/http-server.log', 'a'),
), $pipes, __DIR__);
putenv('GRB_TEST_FIXTURE');
if (!is_resource($server)) { throw new RuntimeException('HTTP test server failed'); }
fclose($pipes[0]);
$cookies = array();
function http_call($url, $path, $data = null, &$cookies = array(), $origin = '') {
    $headers = array('Content-Type: application/x-www-form-urlencoded');
    if ($origin) { $headers[] = 'Origin: ' . $origin; }
    if ($cookies) {
        $parts = array();
        foreach ($cookies as $name => $value) { $parts[] = $name . '=' . $value; }
        $headers[] = 'Cookie: ' . implode('; ', $parts);
    }
    $context = stream_context_create(array('http' => array(
        'method' => $data === null ? 'GET' : 'POST', 'header' => implode("\r\n", $headers),
        'content' => $data === null ? '' : http_build_query($data), 'ignore_errors' => true, 'timeout' => 10,
    )));
    $body = file_get_contents($url . $path, false, $context);
    foreach ($http_response_header ?? array() as $header) {
        if (preg_match('/^Set-Cookie: ([^=]+)=([^;]*)/i', $header, $matches)) {
            $cookies[$matches[1]] = $matches[2];
        }
    }
    return array('status' => (int) substr($http_response_header[0] ?? '', 9, 3), 'body' => json_decode($body, true));
}
try {
    $ready = false;
    for ($i = 0; $i < 40; $i++) {
        $probe = @stream_socket_client('tcp://' . $address, $errno, $errstr, 0.1);
        if ($probe) { fclose($probe); $ready = true; break; }
        usleep(100000);
    }
    expect($ready, 'isolated HTTP server starts');
    $boot = http_call($url, '/ajax', array('operation' => 'bootstrap'), $cookies, $url);
    expect($boot['status'] === 200 && !empty($boot['body']['data']['nonce']), 'fresh public nonce fetched over HTTP');
    $nonce = $boot['body']['data']['nonce'];
    $bad = http_call($url, '/ajax', array('operation' => 'lookup', 'email' => 'chair@example.invalid', 'nonce' => 'wrong'), $cookies, $url);
    expect($bad['status'] === 403, 'invalid nonce rejected over HTTP');
    $cross = http_call($url, '/ajax', array('operation' => 'bootstrap'), $cookies, 'https://attacker.invalid');
    expect($cross['status'] === 403, 'cross-origin request rejected');
    $http_source = fundraiser('http-chair@example.invalid');
    foreach (array('never-registered@example.invalid' => 'new', 'active@example.invalid' => 'active', 'http-chair@example.invalid' => 'closed') as $email => $state) {
        $response = http_call($url, '/ajax', array('operation' => 'lookup', 'nonce' => $nonce, 'email' => $email), $cookies, $url);
        expect($response['body']['data']['state'] === $state, 'email lookup routes ' . $state . ' account correctly');
    }
    $login_cookies = array();
    $login_nonce = http_call($url, '/ajax', array('operation' => 'bootstrap'), $login_cookies, $url)['body']['data']['nonce'];
    $bad_login = http_call($url, '/ajax', array('operation' => 'login', 'nonce' => $login_nonce, 'email' => 'active@example.invalid', 'password' => 'wrong'), $login_cookies, $url);
    expect($bad_login['status'] === 401 && $bad_login['body']['data']['message'] === 'Please check your email and password.', 'inline login rejects wrong password');
    $good_login = http_call($url, '/ajax', array('operation' => 'login', 'nonce' => $login_nonce, 'email' => 'active@example.invalid', 'password' => 'Test! Same password 123'), $login_cookies, $url);
    expect($good_login['status'] === 200 && !empty($good_login['body']['data']['redirect']), 'inline login succeeds and returns a redirect');
    expect(http_call($url, '/whoami', null, $login_cookies)['body']['data']['id'] === $active, 'inline login signs the browser in');
    $booking = array('operation' => 'rebook', 'nonce' => $nonce, 'mode' => 'gate', 'email' => 'http-chair@example.invalid', 'password' => 'wrong', 'start_date' => $today, 'end_date' => $end, 'confirm' => 'yes');
    $wrong = http_call($url, '/ajax', $booking, $cookies, $url);
    expect($wrong['status'] === 401, 'wrong password returns HTTP 401');
    expect((int) email_exists('http-chair@example.invalid') === $http_source, 'wrong password does not mutate source');
    $guest_dashboard = $booking;
    $guest_dashboard['mode'] = 'dashboard';
    $guest_dashboard['rebook_id'] = $source;
    $guest = http_call($url, '/ajax', $guest_dashboard, $cookies, $url);
    expect($guest['status'] === 401, 'guest cannot use dashboard session path or injected ID');
    $legacy = http_call($url, '/legacy', array('rebook_id' => $http_source, 'yes_action' => 'yes'), $cookies, $url);
    expect($legacy['status'] === 409, 'legacy unauthenticated account mutation is blocked');
    $booking['password'] = 'Test! Same password 123';
    $unconfirmed = $booking;
    $unconfirmed['confirm'] = '';
    expect(http_call($url, '/ajax', $unconfirmed, $cookies, $url)['status'] === 400, 'booking requires explicit confirmation');
    $success = http_call($url, '/ajax', $booking, $cookies, $url);
    expect($success['status'] === 200 && $success['body']['success'] && $success['body']['data']['created'], 'password-verified popup booking succeeds over HTTP');
    clean_user_cache($http_source);
    $http_next = (int) get_user_meta($http_source, '_grb_next_campaign', true);
    $identity = http_call($url, '/whoami', null, $cookies);
    expect($http_next > 0 && $identity['body']['data']['id'] === $http_next, 'auth cookie logs browser into new fundraiser');
    expect($identity['body']['data']['email'] === 'http-chair@example.invalid', 'signed-in new fundraiser keeps original email');
    // Repeat as a fresh guest to simulate a response lost before cookies reached the browser.
    $retry_cookies = array();
    $retry_nonce = http_call($url, '/ajax', array('operation' => 'bootstrap'), $retry_cookies, $url)['body']['data']['nonce'];
    $booking['nonce'] = $retry_nonce;
    $retry = http_call($url, '/ajax', $booking, $retry_cookies, $url);
    expect($retry['status'] === 200 && !$retry['body']['data']['created'], 'lost-response retry logs into same new campaign');

    // Close the new fixture, then rebook using its signed-in session, with no password.
    update_user_meta($http_next, 'user_action_status', '2');
    $session_boot = http_call($url, '/ajax', array('operation' => 'bootstrap'), $cookies, $url);
    $session_booking = array('operation' => 'rebook', 'nonce' => $session_boot['body']['data']['nonce'], 'mode' => 'dashboard', 'start_date' => $today, 'end_date' => $end, 'confirm' => 'yes', 'rebook_id' => $active);
    $session_result = http_call($url, '/ajax', $session_booking, $cookies, $url);
    expect($session_result['status'] === 200 && $session_result['body']['data']['created'], 'dashboard rebook succeeds without re-entering password');
    $session_identity = http_call($url, '/whoami', null, $cookies);
    expect($session_identity['body']['data']['id'] !== $http_next && $session_identity['body']['data']['email'] === 'http-chair@example.invalid', 'dashboard switches browser into next campaign');
    expect(!get_user_meta($active, '_grb_next_campaign', true), 'client-supplied fundraiser ID is ignored');
    if (getenv('GRB_PLAYWRIGHT_MODULE')) {
        fundraiser('browser-chair@example.invalid');
        $browser = proc_open(array(getenv('GRB_NODE') ?: 'node', __DIR__ . '/browser.cjs', $url, $today, $end, $temp_dir), array(
            0 => array('pipe', 'r'), 1 => STDOUT, 2 => STDERR,
        ), $browser_pipes, __DIR__);
        if (!is_resource($browser)) { throw new RuntimeException('Browser tests could not start'); }
        fclose($browser_pipes[0]);
        expect(proc_close($browser) === 0, 'real Chromium popup and dashboard flows pass');
    }
} finally {
    proc_terminate($server);
    proc_close($server);
}

echo "\n$checks integration checks passed. Database: $db_name (isolated temporary server only).\n";
