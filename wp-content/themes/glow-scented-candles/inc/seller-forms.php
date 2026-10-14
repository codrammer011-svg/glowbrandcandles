<?php
/**
 * Seller Forms – submission, shop popup
 * Auto-split from functions.php – edit this file, not the monolith.
 */


/**
 * Hook triggered after Seller Registration Form submission.
 * Correctly maps seller_fundraiser, seller_goal, description, image_url_profile to user meta.
 */
add_action('user_registration_after_register_user_action', 'glow_handle_seller_form_submission', 25, 3);
function glow_handle_seller_form_submission($valid_form_data, $form_id, $user_id)
{
    $user = get_userdata($user_id);
    $has_seller_data = isset($valid_form_data['seller_fundraiser']) || isset($valid_form_data['seller_goal']);
    $is_seller_role = $user && in_array('seller', (array) $user->roles, true);

    if (!$has_seller_data && !$is_seller_role) {
        return;
    }

    // 1. Ensure user has 'seller' role
    if ($user && !in_array('seller', (array) $user->roles, true)) {
        $user->add_role('seller');
    }

    // 2. Map Fundraiser ID (seller_fundraiser)
    $fid = get_user_meta($user_id, 'user_registration_seller_fundraiser', true);
    if (empty($fid)) {
        $fid = glow_registration_field_value($valid_form_data, 'seller_fundraiser');
    }
    if (!empty($fid)) {
        $clean_fid = preg_replace('/\D/', '', $fid);
        update_user_meta($user_id, 'user_registration_seller_fundraiser', sanitize_text_field($clean_fid));
        update_user_meta($user_id, 'seller_fundraiser', sanitize_text_field($clean_fid));
        update_user_meta($user_id, 'fundraiser_id', sanitize_text_field($clean_fid));
    }

    // 3. Map Seller Goal (seller_goal)
    $goal = get_user_meta($user_id, 'user_registration_seller_goal', true);
    if (empty($goal)) {
        $goal = glow_registration_field_value($valid_form_data, 'seller_goal');
    }
    if (!empty($goal)) {
        update_user_meta($user_id, 'user_registration_seller_goal', sanitize_text_field($goal));
        update_user_meta($user_id, 'seller_goal', sanitize_text_field($goal));
    }

    // 4. Map Profile Image (image_url_profile)
    $img = get_user_meta($user_id, 'user_registration_image_url_profile', true);
    if (empty($img)) {
        $img = glow_registration_field_value($valid_form_data, 'image_url_profile');
    }
    if (!empty($img)) {
        if (is_numeric($img)) {
            $url = wp_get_attachment_url((int) $img);
            if ($url) {
                $img = $url;
            }
        }
        update_user_meta($user_id, 'user_registration_image_url_profile', esc_url_raw($img));
        update_user_meta($user_id, 'image_url_profile', esc_url_raw($img));
        update_user_meta($user_id, 'fund_profile_image', esc_url_raw($img));
    }

    // 5. Ensure status is active
    if (!get_user_meta($user_id, 'user_status', true)) {
        update_user_meta($user_id, 'user_status', '1');
    }
    if (!get_user_meta($user_id, 'user_action_status', true)) {
        update_user_meta($user_id, 'user_action_status', '1');
    }
}

/** Load the native Elementor popup without adding another overlay or click handler. */
add_action('wp_footer', 'glow_register_native_fundraiser_popup', 5);
function glow_register_native_fundraiser_popup()
{
    if (is_admin() || !class_exists('\ElementorPro\Modules\Popup\Module')) {
        return;
    }
    $popup_id = (int) get_option('glow_fundraiser_popup_id', 61511);
    if ($popup_id) {
        \ElementorPro\Modules\Popup\Module::add_popup_to_location($popup_id);
    }
}