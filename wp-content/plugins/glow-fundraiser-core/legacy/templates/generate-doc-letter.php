<?php
require_once('../../../wp-load.php'); // Adjust this path as needed
require_once $_SERVER["DOCUMENT_ROOT"] . '/wordocument/vendor/vendor/autoload.php'; // PhpWord autoloader
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$template_path = __DIR__ . '/worddocs/participant_letter_phpword_ready_final.docx';

$templateProcessor = new TemplateProcessor($template_path);

$templateProcessor->setValue('organization_name', 'Sunrise School');
$templateProcessor->setValue('fundraiser_id', 'F1234');
$templateProcessor->setValue('fundraising_reason', 'New playground equipment');
$templateProcessor->setValue('items_goal', '500 items');
$templateProcessor->setValue('check_payable_to', 'Sunrise PTA');
$templateProcessor->setValue('end_date', '2025-09-15');
$templateProcessor->setValue('chairperson_name', 'Jane Doe');
$templateProcessor->setValue('chairperson_contact', 'jane@example.com');
$templateProcessor->setImageValue('qr_code', array(
    'path' => ('' . glow_dynamic_url_base('uploads') . 'qrcodes/image_seller_register214057.png'),
    'width' => 100,
    'height' => 100,
    'ratio' => true,
));
$outputFile = 'generated_letter.docx';
$templateProcessor->saveAs($outputFile);

header("Content-Disposition: attachment; filename=\"" . basename($outputFile) . "\"");
header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
readfile($outputFile);
exit;


use PhpOffice\PhpWord\TemplateProcessor;


// Get Fundraiser ID
$fund_id = intval($_GET['fund_id'] ?? 999999);
$user = get_user_by('ID', $fund_id);
if (!$user) wp_die('Invalid Fundraiser ID.');

// Prepare template path
$template_path = __DIR__ . '/worddocs/participant_letter_phpword_ready_final.docx';
if (!file_exists($template_path)) {
    wp_die('Template file not found.');
}

// Load TemplateProcessor
$templateProcessor = new TemplateProcessor($template_path);

// Set dynamic values
$templateProcessor->setValue('organization_name', get_user_meta($fund_id, 'user_registration_name_of_school_business', true));
$templateProcessor->setValue('fundraiser_id', $fund_id);
$templateProcessor->setValue('fundraising_reason', get_user_meta($fund_id, 'user_registration_reason_for_fundraising', true));
$templateProcessor->setValue('items_goal', get_user_meta($fund_id, 'user_registration_selling_goal', true));
$templateProcessor->setValue('check_payable_to', get_user_meta($fund_id, 'user_registration_name_of_school_business', true));
$templateProcessor->setValue('end_date', get_user_meta($fund_id, 'user_registration_end_date', true));
$templateProcessor->setValue('chairperson_name', $user->first_name . ' ' . $user->last_name);
$templateProcessor->setValue('chairperson_contact', get_user_meta($fund_id, 'user_registration_input_box_phone', true) . ' / ' . $user->user_email);

// Download QR image temporarily
$qr_url = ('' . glow_dynamic_url_base('uploads') . 'qrcodes/image_seller_register214057.png');


// Insert QR image (template must have {{qr_code}} in a clean paragraph)
$templateProcessor->setImageValue('qr_code', [
    'path' => $qr_url,
    'width' => 100,
    'height' => 100,
    'ratio' => true,
]);

// Save document
// $output_dir = WP_CONTENT_DIR . "/uploads/fundraisers/{$fund_id}";
// wp_mkdir_p($output_dir);
// $outputFile = "{$output_dir}/support-letter.docx";
$outputFile = 'generated_letter5.docx';

$templateProcessor->saveAs($outputFile);

header("Content-Disposition: attachment; filename=\"" . basename($outputFile) . "\"");
header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
readfile($outputFile);
exit;
