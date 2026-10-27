<?php
/** Plugin Name: Glow Seller Registration Phone Mapping */
defined('ABSPATH') || exit;

/*
 * The Seller Registration form (62307) collects the phone in a plain text field named
 * billing_phone. User Registration saves text fields as user_registration_{name}, so copy
 * it to billing_phone, where the seller edit page and seller SMS read it.
 */
add_action('user_registration_after_register_user_action', function($form_data, $form_id, $user_id) {
    if ((int) $form_id !== 62307 || !is_array($form_data) || empty($form_data['billing_phone']->value)) { return; }
    $phone = sanitize_text_field(wp_unslash($form_data['billing_phone']->value));
    if ($phone !== '') { update_user_meta($user_id, 'billing_phone', $phone); }
}, 10, 3);
