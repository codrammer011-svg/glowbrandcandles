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
 * Fundraiser popup #61511 controller.
 *
 * - /shop page (and /candace_old/shop): auto-opens if no fundraiser in session/cookie.
 * - Any page: opens when a .glow-open-fundraiser-popup button is clicked, or when
 *   window.glowOpenFundraiserPopup() is called from JS.
 *
 * Relies on Elementor Pro's own popup rendering (display conditions must include
 * the shop page in Elementor's popup settings). On pages where Elementor Pro has
 * not pre-rendered the popup, a fallback inline overlay is used instead.
 */
add_action('wp_footer', 'glow_shop_auto_popup_fundraiser', 30);
function glow_shop_auto_popup_fundraiser()
{
    if (is_admin()) {
        return;
    }

    $popup_id = 61511;

    // --- Session / cookie check (only relevant for auto-open on shop page) ---
    $request_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

    $is_target_shop =
        (function_exists('is_shop') && is_shop())
        || $request_path === 'shop'
        || $request_path === 'candace_old/shop';

    $has_fundraiser = false;

    if ($is_target_shop) {
        if (function_exists('WC') && WC()->session) {
            $fid = WC()->session->get('fridID') ?: WC()->session->get('popup_fundraiser_id');
            if (!empty($fid)) {
                $has_fundraiser = true;
            }
        }
        if (!$has_fundraiser && session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['fridID'])) {
            $has_fundraiser = true;
        }
        if (!$has_fundraiser && !empty($_COOKIE['fundraiserID'])) {
            $has_fundraiser = true;
        }
    }

    $auto_open = $is_target_shop && !$has_fundraiser;

    // --- Fallback inline overlay (used when Elementor Pro hasn't pre-rendered the popup) ---
    // get_builder_content_for_display with $with_css = true ensures styles are inlined.
    $fallback_content = '';
    if (class_exists('\Elementor\Plugin')) {
        $fallback_content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($popup_id, true);
    }

    ?>
    <?php if (!empty($fallback_content)) : ?>
    <div id="glow-fundraiser-overlay" style="display:none;position:fixed;inset:0;z-index:999999;background:rgba(0,0,0,0.7);overflow-y:auto;">
        <div id="glow-fundraiser-overlay-inner" style="min-height:100%;display:flex;align-items:center;justify-content:center;padding:20px;box-sizing:border-box;">
            <div style="position:relative;width:100%;max-width:720px;background:#fff;border-radius:6px;overflow:hidden;">
                <button id="glow-fundraiser-close" aria-label="Close" style="position:absolute;top:10px;right:10px;z-index:10;width:32px;height:32px;border-radius:50%;background:rgba(0,0,0,0.15);border:none;font-size:20px;line-height:1;cursor:pointer;">&times;</button>
                <?php echo $fallback_content; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script type="text/javascript">
    (function () {
        'use strict';

        var popupId  = <?php echo (int) $popup_id; ?>;
        var autoOpen = <?php echo $auto_open ? 'true' : 'false'; ?>;

        /* ---- Elementor Pro native popup (preferred) ---- */
        function tryElementorPopup() {
            if (
                typeof elementorProFrontend !== 'undefined' &&
                elementorProFrontend.modules &&
                elementorProFrontend.modules.popup
            ) {
                elementorProFrontend.modules.popup.showPopup({ id: popupId });
                return true;
            }
            return false;
        }

        /* ---- Fallback: our custom inline overlay ---- */
        var overlay  = document.getElementById('glow-fundraiser-overlay');
        var inner    = document.getElementById('glow-fundraiser-overlay-inner');
        var closeBtn = document.getElementById('glow-fundraiser-close');

        function openFallbackOverlay() {
            if (!overlay) return false;
            overlay.style.display = 'block';
            document.body.style.overflow = 'hidden';
            return true;
        }

        function closeFallbackOverlay() {
            if (!overlay) return;
            overlay.style.display = 'none';
            document.body.style.overflow = '';
        }

        /* ---- Public API ---- */
        window.glowOpenFundraiserPopup = function () {
            if (!tryElementorPopup()) {
                if (!openFallbackOverlay()) {
                    // Neither is ready yet — retry until Elementor loads
                    var attempts = 0;
                    var t = setInterval(function () {
                        attempts++;
                        if (tryElementorPopup() || openFallbackOverlay() || attempts >= 30) {
                            clearInterval(t);
                        }
                    }, 200);
                }
            }
        };

        /* ---- Close handlers for fallback overlay ---- */
        if (closeBtn) closeBtn.addEventListener('click', closeFallbackOverlay);
        if (overlay) {
            overlay.addEventListener('click', function (e) {
                if (e.target === overlay || e.target === inner) closeFallbackOverlay();
            });
        }
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeFallbackOverlay();
        });

        /* ---- Button trigger (.glow-open-fundraiser-popup on any element) ---- */
        document.addEventListener('click', function (e) {
            var trigger = e.target.closest('.glow-open-fundraiser-popup');
            if (trigger) {
                e.preventDefault();
                window.glowOpenFundraiserPopup();
            }
        });

        /* ---- Auto-open on shop page when no fundraiser ---- */
        if (autoOpen) {
            window.glowOpenFundraiserPopup();
        }
    })();
    </script>
    <?php
}
