(function ($) {
    'use strict';
    var previousFocus, previousOverflow, dialog;
    var STAR = 'M12 1.5C13.1 8.6 15.4 10.9 22.5 12C15.4 13.1 13.1 15.4 12 22.5C10.9 15.4 8.6 13.1 1.5 12C8.6 10.9 10.9 8.6 12 1.5Z';
    var HEART = 'M12 20.5C6.5 16.4 3 13 3 9.2C3 6.4 5 4.5 7.4 4.5C9.4 4.5 11.2 5.7 12 7.6C12.8 5.7 14.6 4.5 16.6 4.5C19 4.5 21 6.4 21 9.2C21 13 17.5 16.4 12 20.5Z';
    // Background artwork drawn on the card's 1418x953 design grid: [x, y, size, colour] stars, [x1, y1, x2, y2, colour] streaks.
    var DECOR = (function () {
        var c = {pink: '#f0146e', orange: '#f99a1c', yellow: '#f9a51a', green: '#14a06b', purple: '#7b3fe4'};
        var stars = [[160, 238, 65, 'purple'], [80, 300, 55, 'orange'], [225, 350, 40, 'pink'], [1012, 107, 55, 'yellow'], [1215, 150, 65, 'pink'], [1210, 350, 60, 'green'], [1310, 378, 40, 'purple'], [280, 840, 55, 'yellow'], [383, 850, 40, 'purple'], [1038, 855, 55, 'green'], [1135, 838, 55, 'pink'], [1280, 767, 55, 'yellow']];
        var lines = [[395, 115, 450, 170, 'orange'], [350, 160, 402, 183, 'pink'], [278, 232, 350, 242, 'pink'], [298, 298, 345, 277, 'green'],
            [1052, 160, 1092, 127, 'green'], [1092, 202, 1122, 188, 'purple'], [1100, 252, 1145, 253, 'orange'], [1098, 290, 1137, 322, 'pink'],
            [1175, 210, 1205, 238, 'orange'], [1316, 166, 1309, 194, 'orange'], [1340, 210, 1368, 188, 'orange'], [1335, 256, 1368, 252, 'pink'], [1325, 296, 1348, 325, 'orange'], [1185, 292, 1210, 284, 'pink'],
            [215, 698, 260, 720, 'pink'], [190, 735, 240, 742, 'orange'], [205, 790, 252, 760, 'green'],
            [1148, 710, 1193, 690, 'pink'], [1162, 740, 1218, 741, 'orange'], [1150, 760, 1195, 795, 'green']];
        var dots = [[33, 515, 5], [70, 543, 5], [70, 580, 5], [33, 595, 5], [70, 622, 5], [1380, 775, 4], [1355, 800, 4], [1380, 815, 4], [1325, 825, 4], [1360, 840, 4], [1380, 860, 4], [1335, 865, 4], [1305, 890, 4]];
        return '<svg class="glow-reg-decor" viewBox="0 0 1418 953" preserveAspectRatio="none" focusable="false"><defs>' +
            '<symbol id="glow-reg-star" viewBox="0 0 24 24"><path d="' + STAR + '" fill="none" stroke-width="2.4" stroke-linejoin="round"/></symbol>' +
            '<radialGradient id="glow-reg-g-pink" gradientUnits="userSpaceOnUse" cx="-10" cy="-10" r="215"><stop offset="0" stop-color="#f6a6c1"/><stop offset="1" stop-color="#fbd2df"/></radialGradient>' +
            '<linearGradient id="glow-reg-g-peach" gradientUnits="userSpaceOnUse" x1="-60" y1="760" x2="170" y2="953"><stop offset="0" stop-color="#fde4d5"/><stop offset="1" stop-color="#fac0a1"/></linearGradient></defs>' +
            '<ellipse cx="-10" cy="-10" rx="195" ry="212" fill="url(#glow-reg-g-pink)"/><circle cx="1430" cy="-40" r="180" fill="#efe6f9"/>' +
            '<circle cx="-80" cy="970" r="270" fill="#ebe3f8"/><circle cx="-70" cy="985" r="235" fill="url(#glow-reg-g-peach)"/>' +
            '<g fill="#f7c4d5">' + dots.map(function (d) { return '<circle cx="' + d[0] + '" cy="' + d[1] + '" r="' + d[2] + '"/>'; }).join('') + '</g>' +
            '<g fill="none" stroke-width="9" stroke-linecap="round">' + lines.map(function (l) { return '<line x1="' + l[0] + '" y1="' + l[1] + '" x2="' + l[2] + '" y2="' + l[3] + '" stroke="' + c[l[4]] + '"/>'; }).join('') + '</g>' +
            '<path d="M1277 330C1245 300 1232 275 1232 252C1232 228 1248 214 1263 214C1272 214 1276 222 1278 232C1282 220 1290 212 1302 212C1316 212 1325 226 1323 245C1320 272 1302 300 1277 330Z" transform="rotate(6 1277 270)" fill="none" stroke="' + c.pink + '" stroke-width="8" stroke-linejoin="round"/>' +
            stars.map(function (s) { return '<use href="#glow-reg-star" x="' + (s[0] - s[2] / 2) + '" y="' + (s[1] - s[2] / 2) + '" width="' + s[2] + '" height="' + s[2] + '" stroke="' + c[s[3]] + '"/>'; }).join('') +
            '</svg>';
    })();
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
            '<div class="glow-reg-bg" aria-hidden="true">' + DECOR + '</div>' +
            '<button class="glow-reg-close" type="button" aria-label="Close registration confirmation"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M4 4l16 16M20 4L4 20"/></svg></button>' +
            '<div class="glow-reg-logo" role="img" aria-label="GLOW Scented Candles"><span class="glow-reg-logo-name">GLOW<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="' + STAR + '"/></svg></span><span class="glow-reg-logo-tag">Scented Candles</span></div>' +
            '<h2 id="glow-reg-title"><span class="glow-reg-title-top">You’re</span> <span class="glow-reg-title-main">Registered!</span></h2>' +
            '<p class="glow-reg-ready">Your <strong>GLOW</strong> fundraiser is ready to go.</p>' +
            '<div class="glow-reg-row glow-reg-id-row"><span class="glow-reg-icon" aria-hidden="true">#</span><div class="glow-reg-detail"><span class="glow-reg-label">Your Fundraiser ID:</span><b class="glow-reg-id"></b></div><button class="glow-reg-copy" type="button"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="8" y="7" width="12" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h2"/></svg>Copy</button></div>' +
            '<div class="glow-reg-row glow-reg-email-row"><span class="glow-reg-icon" aria-hidden="true"><svg viewBox="0 0 24 24" focusable="false"><rect x="3" y="5.5" width="18" height="13" rx="2"/><path d="M3.5 6.5l8.5 7 8.5-7"/></svg></span><div class="glow-reg-detail"><b class="glow-reg-email-text">We sent your fundraiser details to <span class="glow-reg-email"></span>.</b><p>Don’t see it? Check your spam or junk folder.</p></div></div>' +
            '<a class="glow-reg-dashboard">Go to My Dashboard <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M4 12h15M13 6l6 6-6 6"/></svg></a>' +
            '<p class="glow-reg-thanks">Thank you for choosing GLOW!</p><p class="glow-reg-goal">We can’t wait to help your group reach its goal.</p>' +
            '<svg class="glow-reg-heart" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="' + HEART + '"/></svg>' +
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
