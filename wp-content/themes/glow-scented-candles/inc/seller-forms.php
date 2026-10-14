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
 * Button trigger for the fundraiser popup.
 *
 * Auto-open on /shop is already handled by the [glow_fundraiser_shop] shortcode
 * (glow_fc_auto_popup_trigger). This function only adds:
 *   - A custom overlay for non-shop pages (Elementor's cookie guard hides the
 *     native popup on those pages, so we need a separate layer).
 *   - window.glowOpenFundraiserPopup() and .glow-open-fundraiser-popup class
 *     support so any button can open the popup manually.
 */
add_action('wp_footer', 'glow_shop_auto_popup_fundraiser', 35);
function glow_shop_auto_popup_fundraiser()
{
    if (is_admin()) {
        return;
    }

    // Read the same popup ID that the shortcode stores
    $popup_id = (int) get_option('glow_fundraiser_popup_id', 0);
    if (!$popup_id) {
        return;
    }

    $request_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $is_shop =
        (function_exists('is_shop') && is_shop())
        || $request_path === 'shop'
        || $request_path === 'candace_old/shop';

    // On non-shop pages the shortcode's cookie guard blocks showPopup(),
    // so render a standalone overlay with the template content as fallback.
    $overlay_html = '';
    if (!$is_shop && class_exists('\Elementor\Plugin')) {
        $overlay_html = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($popup_id, true);
    }

    ?>
    <?php if (!$is_shop && !empty($overlay_html)) : ?>
    <div id="glow-fundraiser-overlay" style="display:none;position:fixed;inset:0;z-index:999999;background:rgba(0,0,0,0.7);overflow-y:auto;">
        <div id="glow-fundraiser-overlay-inner" style="min-height:100%;display:flex;align-items:center;justify-content:center;padding:20px;box-sizing:border-box;">
            <div style="position:relative;width:100%;max-width:720px;background:#fff;border-radius:6px;overflow:hidden;">
                <button id="glow-fundraiser-close" aria-label="Close" style="position:absolute;top:10px;right:10px;z-index:10;width:32px;height:32px;border-radius:50%;background:rgba(0,0,0,0.15);border:none;font-size:20px;line-height:1;cursor:pointer;">&times;</button>
                <?php echo $overlay_html; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script type="text/javascript">
    (function () {
        'use strict';

        var popupId  = <?php echo $popup_id; ?>;
        var isShop   = <?php echo $is_shop ? 'true' : 'false'; ?>;
        var overlay  = document.getElementById('glow-fundraiser-overlay');
        var inner    = document.getElementById('glow-fundraiser-overlay-inner');
        var closeBtn = document.getElementById('glow-fundraiser-close');

        function openNative() {
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

        function openOverlay() {
            if (!overlay) return false;
            overlay.style.display = 'block';
            document.body.style.overflow = 'hidden';
            return true;
        }

        function closeOverlay() {
            if (!overlay) return;
            overlay.style.display = 'none';
            document.body.style.overflow = '';
        }

        // Public API — works on any page
        window.glowOpenFundraiserPopup = function () {
            if (isShop) {
                // Shop page: use Elementor's native popup (cookie guard allows it here)
                if (!openNative()) {
                    var n = 0, t = setInterval(function () {
                        if (openNative() || ++n >= 20) clearInterval(t);
                    }, 150);
                }
            } else {
                // Non-shop: use standalone overlay
                openOverlay();
            }
        };

        // .glow-open-fundraiser-popup on any button/link
        document.addEventListener('click', function (e) {
            var trigger = e.target.closest('.glow-open-fundraiser-popup');
            if (trigger) {
                e.preventDefault();
                window.glowOpenFundraiserPopup();
            }
        });

        // Overlay close handlers
        if (closeBtn) closeBtn.addEventListener('click', closeOverlay);
        if (overlay) {
            overlay.addEventListener('click', function (e) {
                if (e.target === overlay || e.target === inner) closeOverlay();
            });
        }
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeOverlay();
        });
    })();
    </script>
    <?php
}
