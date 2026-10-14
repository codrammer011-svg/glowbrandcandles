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
 * Render fundraiser popup overlay on every frontend page.
 * Auto-open fires only on the shop page when no fundraiser is selected.
 * Manual trigger: add class .glow-open-fundraiser-popup to any button/link,
 * or call window.glowOpenFundraiserPopup() from JS.
 */
add_action('wp_footer', 'glow_shop_auto_popup_fundraiser', 30);
function glow_shop_auto_popup_fundraiser()
{
    // Skip admin/editor context entirely
    if (is_admin()) {
        return;
    }

    $popup_id = 61511;

    // Determine whether to auto-open (shop page + no fundraiser in session/cookie)
    $request_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

    $is_target_shop =
        (function_exists('is_shop') && is_shop())
        || $request_path === 'shop'
        || $request_path === 'candace_old/shop';

    $has_fundraiser = false;

    if ($is_target_shop) {
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
    }

    $auto_open = $is_target_shop && !$has_fundraiser;

    // Render popup overlay on every frontend page so button triggers work anywhere
    if (class_exists('\Elementor\Plugin')) {
        $content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($popup_id);
        if ($content) : ?>
        <div id="glow-fundraiser-overlay" style="display:none;position:fixed;inset:0;z-index:999999;background:rgba(0,0,0,0.65);overflow-y:auto;">
            <div id="glow-fundraiser-overlay-inner" style="min-height:100%;display:flex;align-items:center;justify-content:center;padding:20px;box-sizing:border-box;">
                <div style="position:relative;width:100%;max-width:700px;">
                    <button id="glow-fundraiser-close" aria-label="Close" style="position:absolute;top:-14px;right:-14px;z-index:10;width:30px;height:30px;border-radius:50%;background:#fff;border:none;font-size:20px;line-height:1;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,0.25);">&times;</button>
                    <?php echo $content; ?>
                </div>
            </div>
        </div>
        <script type="text/javascript">
        (function () {
            'use strict';

            var autoOpen = <?php echo $auto_open ? 'true' : 'false'; ?>;
            var overlay   = document.getElementById('glow-fundraiser-overlay');
            var inner     = document.getElementById('glow-fundraiser-overlay-inner');
            var closeBtn  = document.getElementById('glow-fundraiser-close');

            function openPopup() {
                if (!overlay) return;
                overlay.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }

            function closePopup() {
                if (!overlay) return;
                overlay.style.display = 'none';
                document.body.style.overflow = '';
            }

            // Global — call from anywhere: window.glowOpenFundraiserPopup()
            window.glowOpenFundraiserPopup = openPopup;

            // Close button
            if (closeBtn) closeBtn.addEventListener('click', closePopup);

            // Click on backdrop to close
            if (overlay) {
                overlay.addEventListener('click', function (e) {
                    if (e.target === overlay || e.target === inner) closePopup();
                });
            }

            // ESC key to close
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closePopup();
            });

            // Wire any element with class .glow-open-fundraiser-popup
            document.addEventListener('click', function (e) {
                var trigger = e.target.closest('.glow-open-fundraiser-popup');
                if (trigger) {
                    e.preventDefault();
                    openPopup();
                }
            });

            // Auto-open on page load when no fundraiser selected
            if (autoOpen) {
                openPopup();
            }
        })();
        </script>
        <?php endif;
    }
}
