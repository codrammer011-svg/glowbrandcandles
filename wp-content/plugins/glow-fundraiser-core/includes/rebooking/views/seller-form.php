<?php
defined('ABSPATH') || exit;
$gsr_goal = preg_replace('/[^0-9.]/', '', (string) get_user_meta(get_current_user_id(), 'user_registration_seller_goal', true));
?>
<section class="grb" data-gsr aria-label="Re-book for a new fundraiser">
    <span class="grb-eyebrow">GLOW SCENTED CANDLES</span>
    <h2>Re-book for a new fundraiser</h2>
    <p class="grb-intro">Enter the Fundraiser ID from your new chairperson and your seller goal. Your name, photo, email and password carry over.</p>
    <form data-gsr-rebook novalidate>
        <label>New Fundraiser ID<input type="text" name="fundraiser_id" inputmode="numeric" pattern="\d{1,10}" maxlength="10" required autocomplete="off" placeholder="e.g. 216778" title="Numbers only"></label>
        <p class="grb-note" data-gsr-org hidden></p>
        <label>Seller goal ($)<input type="text" name="goal" inputmode="decimal" pattern="\$?\d{1,6}(\.\d{1,2})?" maxlength="10" required placeholder="e.g. 500" title="A dollar amount, e.g. 500" value="<?php echo $gsr_goal !== '' && (float) $gsr_goal > 0 ? esc_attr($gsr_goal) : ''; ?>"></label>
        <label class="grb-confirm"><input type="checkbox" name="confirm" value="yes" required><span>You will no longer be able to view orders or reports for your previous fundraiser. Print any reports you need first.</span></label>
        <button class="grb-button" type="submit">Re-book me <span aria-hidden="true">&rarr;</span></button>
        <p class="grb-note">Same email. Same password. Same store link.</p>
    </form>
    <p class="grb-status" data-gsr-status role="status" aria-live="polite"></p>
    <noscript>Please enable JavaScript to re-book.</noscript>
</section>
