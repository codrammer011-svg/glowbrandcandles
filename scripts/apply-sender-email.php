<?php
/** CLI-only sender migration and verification. Does not send mail. */
if (PHP_SAPI !== 'cli') { http_response_code(403); exit; }
$port = (int) ($argv[1] ?? 10010);
if ($port < 1 || $port > 65535) { throw new RuntimeException('Invalid database port'); }
define('DB_HOST', '127.0.0.1:' . $port);
define('DISABLE_WP_CRON', true);
define('SHORTINIT', true);
require dirname(__DIR__) . '/wp-load.php';
require_once ABSPATH . WPINC . '/formatting.php';
require_once ABSPATH . WPINC . '/meta.php';
require_once ABSPATH . WPINC . '/option.php';
require_once ABSPATH . WPINC . '/class-wp-error.php';
require ABSPATH . 'wp-content/mu-plugins/glow-sender-email.php';
glow_migrate_sender_email_settings();
$target = 'info@glowscentedcandles.com';
$results = array(
    'configured_from' => GLOW_FROM_EMAIL,
    'wordpress_from' => apply_filters('wp_mail_from', 'wordpress@example.com'),
    'woocommerce_from' => get_option('woocommerce_email_from_address'),
    'database_migration' => get_option('glow_sender_email_migration'),
);
$smtp = get_option('wp_mail_smtp', array());
if (isset($smtp['mail'])) {
    $results['smtp_from'] = $smtp['mail']['from_email'] ?? '';
    $results['smtp_force_from'] = !empty($smtp['mail']['from_email_force']);
}
$mailer = new stdClass();
do_action('phpmailer_init', $mailer);
$results['mailer_from'] = $mailer->From;
$results['envelope_sender'] = $mailer->Sender;
foreach (array('wordpress_from', 'woocommerce_from', 'database_migration', 'mailer_from', 'envelope_sender') as $key) {
    if ($results[$key] !== $target) { throw new RuntimeException('Sender verification failed: ' . $key); }
}
echo json_encode($results, JSON_PRETTY_PRINT) . PHP_EOL;
