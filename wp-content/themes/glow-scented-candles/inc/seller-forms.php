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
 * Seller QR code: uploads/qrcodes/image_{id}.png, encoding the seller's store link.
 *
 * The legacy send_welcome_email_to_new_user() was the only thing that created this
 * file and the seller_barcode_link meta the seller dashboard reads; it is disabled
 * on the new build, so new sellers had no QR code at all. Reuses an existing file.
 * Best-effort: returns '' if the QR library is unavailable.
 */
function glow_seller_qr_url($user_id)
{
    $user = get_userdata($user_id);
    if (!$user) {
        return '';
    }
    $upload = wp_upload_dir();
    $dir    = trailingslashit($upload['basedir']) . 'qrcodes/';
    $file   = $dir . 'image_' . $user_id . '.png';
    $url    = set_url_scheme(trailingslashit($upload['baseurl']) . 'qrcodes/image_' . $user_id . '.png', 'https');

    if (!is_file($file)) {
        try {
            $qrlib = defined('GLOW_FC_DIR') ? GLOW_FC_DIR . 'includes/phpqrcode/qrlib.php' : '';
            if (!$qrlib || !is_file($qrlib)) {
                return '';
            }
            wp_mkdir_p($dir);
            // A different QRcode class (User Registration Pro's TCPDF) may already be loaded; redeclaring it is fatal.
            if (!class_exists('QRcode', false)) {
                require_once $qrlib;
            }
            if (!method_exists('QRcode', 'png')) {
                return '';
            }
            QRcode::png(home_url('/seller/user/' . $user->user_login), $file);
        } catch (\Throwable $e) {
            error_log('[glow] seller QR generation failed for user ' . $user_id . ': ' . $e->getMessage());
            return '';
        }
    }
    if (!is_file($file)) {
        return '';
    }
    update_user_meta($user_id, 'seller_barcode_link', $url);
    return $url;
}

/**
 * Seller welcome email ("Seller Registered"): QR code, store link, login, fundraiser.
 *
 * Replaces the seller half of the legacy send_welcome_email_to_new_user(), which was
 * disabled on the new build (it read positional $_POST['form_data'] from the old
 * theme) — only the fundraiser half was rebuilt, as glow_email_user_fundraiser_welcome().
 * Reads everything from user meta, and never includes the password (WordPress only
 * stores a hash) — it links to password reset instead.
 *
 * Also used by the "Resend Seller Emails" plugin, so this is the one place to edit
 * the seller email.
 *
 * @return array{subject:string, body:string, headers:array}|null
 */
function glow_build_seller_welcome_email($user_id)
{
    $user = get_userdata($user_id);
    if (!$user || !in_array('seller', (array) $user->roles, true)) {
        return null;
    }
    $m = static function ($key) use ($user_id) {
        return trim((string) get_user_meta($user_id, $key, true));
    };

    $first = $m('first_name') !== '' ? $m('first_name') : $user->display_name;
    $fid   = preg_replace('/\D/', '', $m('user_registration_seller_fundraiser') ?: $m('seller_fundraiser') ?: $m('fundraiser_id'));
    $org   = $fid !== '' ? trim((string) get_user_meta((int) $fid, 'user_registration_name_of_school_business', true)) : '';
    $goal  = ltrim($m('user_registration_seller_goal') ?: $m('seller_goal'), '$');
    $link  = home_url('/seller/user/' . $user->user_login);
    $qr    = glow_seller_qr_url($user_id);
    $e     = 'esc_html';

    ob_start();
    ?>
<div style="font-family:Arial,sans-serif;background:#f6f6f6;width:100%;padding:0;margin:0;">
  <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#ffffff" style="max-width:900px;margin:40px auto;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <tr>
      <td align="center" style="background:#333;padding:30px;">
        <h1 style="color:#ffffff;font-size:28px;font-weight:400;margin:0;text-align:center;">💛 Welcome to Your Glow Fundraiser, <?php echo $e($first); ?>!</h1>
      </td>
    </tr>
    <tr>
      <td align="left" style="padding:25px 40px 0;">
        <p style="color:#333;font-size:16px;margin-top:10px;">⚠️ Don’t hit delete! 💌 This email has everything you need for your fundraiser &amp; seller dashboard — your key to glowing success! ✨</p>
      </td>
    </tr>
    <tr>
      <td style="padding:30px 40px;padding-top:10px;color:#333;line-height:1.6;font-size:15px;">
        <p>Thank you for registering as a <b>Glow Scented Candles Seller</b><?php if ($org !== '') : ?> and supporting <b><?php echo $e($org); ?></b><?php endif; ?>! 🌙<br>
        You’re helping your team shine bright while earning profits through beautiful candles, room sprays, wax melts, and essential oils.</p>

        <hr style="border:0; border-top:1px double #edeae9; width:100%; margin:20px auto;">

        <h3 style="color:#333;margin-top:30px;">🔍 Your Seller Details</h3>
        <?php if ($qr) : ?>
          <p><b>📸 Profile QR Code:</b><br><img src="<?php echo esc_url($qr); ?>" alt="Profile QR Code" style="width:110px;border-radius:6px;margin:10px 0;"></p>
        <?php endif; ?>
        <p><b>🔗 Your Seller Link:</b> <a href="<?php echo esc_url($link); ?>" target="_blank" style="color:#4184f3;text-decoration:none;"><?php echo $e($link); ?></a></p>
        <p><b>📧 Email:</b> <?php echo $e($user->user_email); ?></p>
        <p><b>🔑 Password:</b> the password you created when you registered (<a href="<?php echo esc_url(wp_lostpassword_url()); ?>" target="_blank" style="color:#4184f3;">forgot it? reset it here</a>)</p>
        <?php if ($fid !== '') : ?><p><b>🪪 Fundraiser ID:</b> <?php echo $e($fid); ?></p><?php endif; ?>
        <?php if ($org !== '') : ?><p><b>🎉 Group Name:</b> <?php echo $e($org); ?></p><?php endif; ?>
        <?php if ($goal !== '') : ?><p><b>💵 Seller Goal:</b> $<?php echo $e($goal); ?></p><?php endif; ?>

        <hr style="border:0; border-top:1px double #edeae9; width:100%; margin:20px auto;">

        <h3 style="margin-top:35px;">🚀 Let’s Get Started!</h3>
        <p>🖥️ Log in to your Seller Dashboard.</p>
        <p><b>👉 My Account Login:</b> <a href="<?php echo esc_url(home_url('/my-account/')); ?>" target="_blank" style="color:#4184f3;">Access Dashboard</a></p>
        <p>Here, you’ll see real-time updates of your sales and success!</p>

        <h4 style="margin-top:25px;">✅ Start Sharing Your Link Right Away</h4>
        <ol style="padding-left:20px;">
          <li>Copy your Seller Link: <a href="<?php echo esc_url($link); ?>" target="_blank" style="color:#4184f3;"><?php echo $e($link); ?></a></li>
          <li>Share your link via email, text, and social media — include why you’re fundraising, your goal, and a photo/logo.</li>
        </ol>
        <p style="font-style:italic;">💡 The more you share, the more you sell!</p>
        <p style="font-style:italic;">📲 Spread the Glow! Tap to share on Facebook, Instagram, or copy the link.</p>
        <h4 style="margin-top:25px;">✨ Glow Up Your Fundraiser — One Post at a Time!</h4>
        <h4 style="margin-top:25px;">Ready, set, post! 🚀 These images make promoting your Glow easy &amp; fun 👇</h4>
        <a href="https://www.dropbox.com/scl/fo/2qaa5ldadzp5nnu0l4acn/AF2UoshWPeClkWYJkxHHpAM?rlkey=ex6gonh1c6hvx1ojit5dxu94v&amp;st=ubblc6lz&amp;dl=0" target="_blank" style="color: blue; font-weight:700;font-size:15px;text-align: left;">Download Glow Product Images Here</a>

        <hr style="border:0; border-top:1px double #edeae9; width:100%; margin:20px auto;">

        <h4 style="margin-top:30px;">🌟 Keep Your Momentum Going</h4>
        <ul style="padding-left:20px;">
          <li>📣 Send your link every few days to family &amp; friends:<br/><a href="<?php echo esc_url($link); ?>">🔗 <?php echo $e($link); ?></a><br/><em>People get busy and forget — friendly reminders keep your fundraiser shining!</em></li>
          <li>📱 Post, text, and email often for maximum visibility and support.</li>
          <li>💛 Remember: Each share is a chance to make a sale and help your cause glow brighter.</li>
        </ul>

        <h4 style="margin-top:30px;">📣 Final Push</h4>
        <ul style="padding-left:20px;">
          <li>Send a “Last Chance to Order!” reminder a few days before your sale ends.</li>
        </ul>

        <hr style="border:0; border-top:1px double #edeae9; width:100%; margin:20px auto;">

        <h4 style="margin-top:30px;">🛍️ Spread the Glow</h4>
        <p style="margin-top:25px;">✨ Candles, Room Sprays, Wax Melts, and Essential Oils make perfect gifts for every occasion!</p>
        <p style="margin-top:25px;">🎁 Invite supporters to shop online for more scents and exclusive items.</p>
        <p style="margin-top:25px;">🚚 Free Shipping on orders $99+, or just $11.95 for smaller orders.</p>
        <p style="margin-top:25px;">📦 Orders ship in 3–5 business days or can ship free to your group at the end of your fundraiser!</p>

        <hr style="border:0; border-top:1px double #edeae9; width:100%; margin:20px auto;">

        <h4 style="margin-top:30px;">💫 You’ve Got This!</h4>
        <p style="margin-top:25px;">We’re so excited to see your success and help your team shine bright!</p>
        <p style="margin-top:25px;">Keep sharing, keep glowing, and remember — every candle sold helps your cause sparkle. ✨</p>
        <p style="color:#555;margin-top:20px;border-top:1px solid #eee;padding-top:15px;">
          💌 <b>Happy Selling,</b><br>
          💛 The Glow Team
        </p>
      </td>
    </tr>
  </table>
</div>
    <?php
    return array(
        'subject' => 'Seller Registered',
        'body'    => ob_get_clean(),
        'headers' => array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . (defined('GLOW_FROM_EMAIL') ? GLOW_FROM_EMAIL : 'Glow Scented Candles <info@glowscentedcandles.com>'),
        ),
    );
}

/**
 * Send the seller welcome email once, after glow_handle_seller_form_submission()
 * (priority 25) has mapped the fundraiser ID and goal. Queued through the Glow
 * Email Worker like the fundraiser welcome, so registration isn't slowed by SMTP.
 */
add_action('user_registration_after_register_user_action', 'glow_email_user_seller_welcome', 36, 3);
function glow_email_user_seller_welcome($valid_form_data, $form_id, $user_id)
{
    $user = get_userdata($user_id);
    if (!$user || in_array('representative', (array) $user->roles, true) || !in_array('seller', (array) $user->roles, true)) {
        return;
    }
    if (get_user_meta($user_id, '_glow_seller_welcome_sent', true)) {
        return;
    }
    $mail = glow_build_seller_welcome_email($user_id);
    if (!$mail) {
        return;
    }
    glow_fc_queue_mail($user->user_email, $mail['subject'], $mail['body'], $mail['headers']);
    update_user_meta($user_id, '_glow_seller_welcome_sent', current_time('mysql'));
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