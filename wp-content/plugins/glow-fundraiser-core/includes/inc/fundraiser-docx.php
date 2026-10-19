<?php
// File: wp-content/themes/yourtheme/inc/fundraiser-docx.php

use PhpOffice\PhpWord\TemplateProcessor;

// Composer autoload in /docs at WP root.
// Loaded lazily so a missing dependency can't take down the whole site.


function build_fundraiser_docx_for_user(array $data): string
{
    $autoload = ABSPATH . 'docs/vendor/autoload.php';
    if (!is_file($autoload)) {
        throw new RuntimeException('PhpWord dependencies not installed: ' . $autoload);
    }
    require_once $autoload;

    $fund_id     = (int)($data['fund_id'] ?? 0);
    $org_name    = (string)($data['org_name'] ?? '');
    $reason      = (string)($data['reason'] ?? '');
    $items_goal  = (string)($data['items_goal'] ?? '');
    $end_date    = (string)($data['end_date'] ?? '');
    $chair_name  = (string)($data['chair_name'] ?? '');
    $chair_phone = (string)($data['chair_phone'] ?? '');
    $chair_email = (string)($data['chair_email'] ?? '');
    $seller_url  = (string)($data['seller_url'] ?? '');
    $qr_path     = (string)($data['qr_path'] ?? '');
  $qr_url     = (string)($data['qr_url'] ?? '');
    // Template stored in your active theme assets/
    $template_path = ABSPATH. 'docs/rebuilt_document.docx';
    if (!is_file($template_path)) {
        throw new RuntimeException('Template not found: ' . $template_path);
    }

    $tp = new TemplateProcessor($template_path);
// Fallback image if QR code doesn't exist
$default_qr = ("" . glow_dynamic_url_base('uploads') . "2025/09/logoempty.jpg");

    // Text placeholders
    $tp->setValue('organization_name', $org_name);
    $tp->setValue('fundraiser_id', $fund_id);
    $tp->setValue('fundraising_reason', $reason);
    $tp->setValue('items_goal', $items_goal);
    $tp->setValue('check_payable_to', $org_name); // if you want a different payee, change here
    $tp->setValue('end_date', $end_date);
    $tp->setValue('chairperson_name', $chair_name);
    $tp->setValue('chairperson_contact', trim($chair_phone . ' / ' . $chair_email));
    $tp->setValue('seller_url', $seller_url); // only takes effect if ${seller_url} exists in template
    // Image placeholder ${qr_code} (only if file exists)
$qr_path = WP_CONTENT_DIR . "/uploads/qrcodes/image_{$fund_id}.png";
if (file_exists($qr_path)) {
    $tp->setImageValue('qr_code', [
        'path' => $qr_path,
        'width' => 60,
        'height' => 60,
        'ratio' => true,
    ]);
} else {
    // Optional fallback or skip
    $tp->setValue('qr_code', ''); // Clear if image not found
}


$seller_qr = WP_CONTENT_DIR . "/uploads/qrcodes/image_seller_register{$fund_id}.png";

if (file_exists($seller_qr)) {
    $tp->setImageValue('seller_code', [
        'path' => $seller_qr,
        'width' => 60,
        'height' => 60,
        'ratio' => true,
    ]);
} else {
    $tp->setValue('seller_code', ''); // Clear if image not found
}
  
    $tmpDir = sys_get_temp_dir(); // PHP's system temp directory
    $out_path = tempnam($tmpDir, 'fundraiser_') . '.docx';
    $tp->saveAs($out_path);
// $subject = "Your Fundraiser Letter";
// $m_body  = "<p>Hi, please find attached your fundraiser letter.</p>";

// $headers = array(
//     'Content-Type: text/html; charset=UTF-8',
//     'From: Glow Scented Candles <info@glowscentedcandles.com>'
// );

// // Attach the generated file
// $attachments = array($out_path);

// $sent = wp_mail('akshaywalia770@gmail.com', $subject, $m_body, $headers, $attachments); die;
    return $out_path;
}
