<?php
/** Local integration-test server only. Never include this file in the installable ZIP. */
$fixture = getenv('GRB_TEST_FIXTURE');
if (PHP_SAPI !== 'cli-server' || !$fixture || !is_file($fixture)) {
    http_response_code(404);
    exit;
}
$config = json_decode(file_get_contents($fixture), true);
foreach ($config['constants'] as $name => $value) {
    define($name, $value);
}
define('WP_HOME', $config['url']);
define('WP_SITEURL', $config['url']);
define('DOING_AJAX', true);
$table_prefix = 'grb_';
require ABSPATH . 'wp-settings.php';
add_filter('pre_wp_mail', '__return_false');
require dirname(__DIR__, 3) . '/wp-content/plugins/glow-fundraiser-core/includes/rebooking/fundraiser-rebooking.php';
$path = wp_parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($path === '/ajax') {
    Glow_FC_Rebooking::request();
}
if ($path === '/legacy') {
    Glow_FC_Rebooking::legacy_request();
}
if ($path === '/whoami') {
    wp_send_json_success(array('id' => get_current_user_id(), 'email' => wp_get_current_user()->user_email));
}
if ($path === '/test-close-current' && is_user_logged_in()) {
    update_user_meta(get_current_user_id(), 'user_action_status', '2');
    wp_send_json_success();
}
if ($path === '/assets/rebooking.js' || $path === '/assets/rebooking.css') {
    header('Content-Type: ' . (substr($path, -3) === '.js' ? 'application/javascript' : 'text/css'));
    readfile(dirname(__DIR__, 3) . '/wp-content/plugins/glow-fundraiser-core/includes/rebooking' . $path);
    exit;
}
header('Content-Type: text/html; charset=utf-8');
echo '<!doctype html><html lang="en"><head><meta name="viewport" content="width=device-width,initial-scale=1"><title>GLOW rebooking preview</title><link rel="stylesheet" href="/assets/rebooking.css"></head><body style="background:#f7f1f5;padding:30px 8px;font-family:Arial,sans-serif">';
if ($path === '/fundraiser-dashboard/') {
    echo '<h1>Fundraiser dashboard</h1>';
    if (Glow_FC_Rebooking_Campaigns::is_closed(get_current_user_id())) {
        echo '<a id="rebook_ajax" href="#">Book your next campaign</a><script>document.getElementById("rebook_ajax").addEventListener("click",function(){window.LEGACY_REBOOK_RAN=true;});</script>';
        echo '<dialog class="grb-dialog" data-grb-dialog aria-label="Book Next Year"><button type="button" data-grb-close>Close</button>' . Glow_FC_Rebooking::dashboard() . '</dialog>';
    }
} else {
    echo Glow_FC_Rebooking::gate();
}
echo '<script>window.GLOW_REBOOK={ajax:"/ajax"};</script><script src="/assets/rebooking.js"></script></body></html>';
