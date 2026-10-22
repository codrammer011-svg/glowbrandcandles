<?php defined('ABSPATH') || exit; ?>
<section class="grb" data-grb data-mode="<?php echo esc_attr($mode); ?>" aria-label="Fundraiser registration">
    <?php if ($mode === 'dashboard') : // The Elementor popup supplies its own heading for the gate. ?>
    <span class="grb-eyebrow">GLOW SCENTED CANDLES</span>
    <h2>Book Next Year</h2>
    <p class="grb-intro">Choose your new campaign dates. Your organization details, email and password will carry over.</p>
    <?php endif; ?>
    <?php if ($mode !== 'dashboard') : // Email check, new-account and sign-in steps exist only in the public gate. ?>
    <form data-grb-lookup>
        <label>Email address<input type="email" name="email" autocomplete="email" required maxlength="100" placeholder="you@example.com"></label>
        <button class="grb-button" type="submit">Continue <span aria-hidden="true">&rarr;</span></button>
    </form>
    <div data-grb-new hidden>
        <p>Welcome! You can register a new fundraiser using this email.</p>
        <a class="grb-button" href="<?php echo esc_url(home_url('/fundraiser-register/')); ?>" data-grb-register>Continue to registration</a>
    </div>
    <form data-grb-active hidden>
        <p>You already have an account. Sign in to continue to your dashboard. An existing fundraiser must be closed out before it can be rebooked.</p>
        <label>Email address<input type="email" name="email" autocomplete="username" readonly data-grb-login-email></label>
        <label>Password<input type="password" name="password" autocomplete="current-password" required maxlength="4096"></label>
        <a class="grb-text-link" href="<?php echo esc_url(wp_lostpassword_url()); ?>">Forgot your password?</a>
        <button class="grb-button" type="submit">Log in <span aria-hidden="true">&rarr;</span></button>
    </form>
    <?php endif; ?>
    <form data-grb-rebook <?php echo $mode !== 'dashboard' ? 'hidden' : ''; ?>>
        <?php if ($mode !== 'dashboard') : ?>
        <p>Your previous fundraiser is closed out. Rebook with your existing email and password.</p>
        <p class="grb-email" data-grb-email></p>
        <label>Existing password<input type="password" name="password" autocomplete="current-password" required maxlength="4096"></label>
        <a class="grb-text-link" href="<?php echo esc_url(wp_lostpassword_url()); ?>">Forgot your password?</a>
        <?php endif; ?>
        <div class="grb-dates">
            <label>New start date<input type="date" name="start_date" min="<?php echo esc_attr(current_time('Y-m-d')); ?>" required></label>
            <label>New end date<input type="date" name="end_date" min="<?php echo esc_attr(current_time('Y-m-d')); ?>" required></label>
        </div>
        <?php
        // Only the signed-in owner sees the name; the public gate must not reveal it for any typed email.
        $org = $mode === 'dashboard' ? trim((string) get_user_meta(get_current_user_id(), 'user_registration_name_of_school_business', true)) : '';
        ?>
        <label class="grb-confirm"><input type="checkbox" name="confirm" value="yes" required><span>You will no longer be able to view orders or reports for <strong><?php echo $org !== '' ? esc_html($org) : 'your previous fundraiser'; ?></strong>. Print any reports you need first.</span></label>
        <button class="grb-button" type="submit">Book my fundraiser <span aria-hidden="true">&rarr;</span></button>
        <p class="grb-note">Same email. Same password. A fresh campaign.</p>
    </form>
    <button type="button" class="grb-back" data-grb-back hidden>Use a different email</button>
    <p class="grb-status" data-grb-status role="status" aria-live="polite"></p>
    <noscript>Please enable JavaScript to check your account or rebook your fundraiser.</noscript>
</section>
