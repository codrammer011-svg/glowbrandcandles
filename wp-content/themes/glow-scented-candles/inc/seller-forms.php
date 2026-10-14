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

/**
 * Automatically popup Elementor Template #61511 on WooCommerce Shop page
 * if the fundraiser ID does not exist in the session.
 * Also exposes window.glowOpenFundraiserPopup() so any button can trigger it manually.
 */
add_action('wp_footer', 'glow_shop_auto_popup_fundraiser', 30);
function glow_shop_auto_popup_fundraiser()
{
    // Only run on WooCommerce shop page OR /candace_old/shop
    $request_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

    $is_target_shop =
        (function_exists('is_shop') && is_shop())
        || $request_path === 'shop'
        || $request_path === 'candace_old/shop';

    if (!$is_target_shop) {
        return;
    }

    // Check if Fundraiser ID exists in WC session, PHP session, or cookies
    $has_fundraiser = false;

    if (function_exists('WC') && WC()->session) {
        $fid = WC()->session->get('fridID');

        if (empty($fid)) {
            $fid = WC()->session->get('popup_fundraiser_id');
        }

        if (!empty($fid)) {
            $has_fundraiser = true;
        }
    }

    if (
        !$has_fundraiser &&
        session_status() === PHP_SESSION_ACTIVE &&
        !empty($_SESSION['fridID'])
    ) {
        $has_fundraiser = true;
    }

    if (!$has_fundraiser && !empty($_COOKIE['fundraiserID'])) {
        $has_fundraiser = true;
    }

    $popup_id = 61511;

    // Always render popup markup so button-triggered open works regardless of session state
    if (class_exists('\Elementor\Plugin')) {
        echo '<div style="display:none;" id="glow-popup-wrapper-' . esc_attr($popup_id) . '">';
        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($popup_id);
        echo '</div>';
    }

    ?>
    <script type="text/javascript">
        (function () {
            'use strict';

            var popupId = <?php echo (int) $popup_id; ?>;
            var autoOpen = <?php echo $has_fundraiser ? 'false' : 'true'; ?>;

            function openElementorPopup() {
                if (
                    typeof elementorProFrontend !== 'undefined' &&
                    elementorProFrontend.modules &&
                    elementorProFrontend.modules.popup
                ) {
                    elementorProFrontend.modules.popup.showPopup({
                        id: popupId
                    });

                    return true;
                }

                return false;
            }

            // Global function — call window.glowOpenFundraiserPopup() from any button
            window.glowOpenFundraiserPopup = function () {
                if (!openElementorPopup()) {
                    var attempts = 0;
                    var interval = setInterval(function () {
                        attempts++;
                        if (openElementorPopup() || attempts >= 30) {
                            clearInterval(interval);
                        }
                    }, 200);
                }
            };

            // Wire up any button/link with class .glow-open-fundraiser-popup
            document.addEventListener('click', function (e) {
                var trigger = e.target.closest('.glow-open-fundraiser-popup');
                if (trigger) {
                    e.preventDefault();
                    window.glowOpenFundraiserPopup();
                }
            });

            // Auto-open on page load only when no fundraiser is selected
            if (autoOpen) {
                if (!openElementorPopup()) {
                    var attempts = 0;

                    var popupInterval = setInterval(function () {
                        attempts++;

                        if (openElementorPopup() || attempts >= 30) {
                            clearInterval(popupInterval);
                        }
                    }, 200);
                }
            }
        })();
    </script>
    <?php
}
