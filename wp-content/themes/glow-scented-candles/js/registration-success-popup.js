(function ($) {
    'use strict';
    var previousFocus, previousOverflow, dialog;
    function close() {
        if (!dialog) { return; }
        dialog.remove(); dialog = null;
        document.body.style.overflow = previousOverflow;
        if (previousFocus && document.contains(previousFocus)) { previousFocus.focus(); }
    }
    function show(data) {
        if (dialog) { return; }
        var target;
        try { target = new URL(data.dashboard, window.location.href); } catch (e) { return; }
        if (target.origin !== window.location.origin || !/^\d+$/.test(String(data.id))) { return; }
        previousFocus = document.activeElement;
        previousOverflow = document.body.style.overflow;
        dialog = $('<div class="glow-reg-overlay"><section class="glow-reg-card" role="dialog" aria-modal="true" aria-labelledby="glow-reg-title" tabindex="-1">' +
            '<button class="glow-reg-close" type="button" aria-label="Close registration confirmation">×</button>' +
            '<img class="glow-reg-logo" alt="GLOW Scented Candles">' +
            '<div class="glow-reg-confetti" aria-hidden="true"><span>✧</span><span>✧</span><span>♡</span><span>✧</span><span>✧</span><span>✧</span></div>' +
            '<h2 id="glow-reg-title">You’re<br><span>Registered!</span></h2>' +
            '<p class="glow-reg-ready">Your <strong>GLOW</strong> fundraiser is ready to go.</p>' +
            '<div class="glow-reg-row glow-reg-id-row"><span class="glow-reg-icon" aria-hidden="true">#</span><div class="glow-reg-detail">Your Fundraiser ID:<b class="glow-reg-id"></b></div><button class="glow-reg-copy" type="button">▢ Copy</button></div>' +
            '<div class="glow-reg-row glow-reg-email-row"><span class="glow-reg-icon" aria-hidden="true">✉</span><div><b>Check <span class="glow-reg-email"></span> for your fundraiser details.</b><p>Don’t see it? Check your spam or junk folder.</p></div></div>' +
            '<a class="glow-reg-dashboard">Go to My Dashboard <span aria-hidden="true">→</span></a>' +
            '<p class="glow-reg-thanks">Thank you for choosing GLOW!</p><p class="glow-reg-goal">We can’t wait to help your group reach its goal.</p><span class="glow-reg-heart" aria-hidden="true">♡</span>' +
            '<span class="glow-reg-status" role="status" aria-live="polite"></span></section></div>');
        dialog.find('.glow-reg-logo').attr('src', window.glowRegistrationPopup.logo);
        dialog.find('.glow-reg-id').text(data.id);
        dialog.find('.glow-reg-email').text(data.email);
        dialog.find('.glow-reg-dashboard').attr('href', target.href);
        dialog.find('.glow-reg-close').on('click', close);
        dialog.on('click', function (event) { if (event.target === this) { close(); } });
        dialog.find('.glow-reg-copy').on('click', async function () {
            var button = this, copied = false;
            try {
                if (navigator.clipboard && window.isSecureContext) { await navigator.clipboard.writeText(String(data.id)); copied = true; }
                else {
                    var input = $('<textarea readonly>').val(String(data.id)).css({position: 'fixed', opacity: 0}).appendTo(dialog);
                    input[0].select(); copied = document.execCommand('copy'); input.remove(); button.focus();
                }
            } catch (e) { copied = false; }
            if (dialog) { $(button).text(copied ? '✓ Copied' : 'Copy'); dialog.find('.glow-reg-status').text(copied ? 'Fundraiser ID copied.' : 'Please select and copy your fundraiser ID.'); }
        });
        dialog.on('keydown', function (event) {
            if (event.key === 'Escape') { close(); return; }
            if (event.key !== 'Tab') { return; }
            var buttons = dialog.find('button, a[href]'), first = buttons[0], last = buttons[buttons.length - 1];
            if (event.shiftKey && (document.activeElement === first || document.activeElement === dialog.find('section')[0])) { event.preventDefault(); last.focus(); }
            else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
        });
        dialog.appendTo(document.body);
        document.body.style.overflow = 'hidden';
        dialog.find('.glow-reg-close')[0].focus();
    }
    $(document).on('user_registration_frontend_before_ajax_complete_success_message.glowPopup', function (event, xhr, flag, form) {
        var response;
        try { response = JSON.parse(xhr.responseText); } catch (e) { return; }
        if (!response.success || !response.data || !response.data.glow_fundraiser_success) { return; }
        show(response.data.glow_fundraiser_success);
        if (!dialog) { return; }
        flag.status = false; // Prevent the small success notice and automatic redirect.
        $(form).find('.ur-submit-button').prop('disabled', true).find('span').removeClass('ur-front-spinner');
        $(form).find('.user-registration-error, .user-registration-message').remove();
    });
})(jQuery);
