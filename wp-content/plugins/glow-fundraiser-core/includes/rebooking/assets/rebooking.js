(function () {
    'use strict';
    const config = window.GLOW_REBOOK;
    if (!config) return;
    let bootstrapPromise;
    const states = new WeakMap();
    // A new email chose to register: keep the gate popup away while they fill in the form.
    const SKIP_KEY = 'grbNewRegistration';
    const SKIP_MS = 24 * 60 * 60 * 1000;

    function skipped() {
        try {
            const value = JSON.parse(window.localStorage.getItem(SKIP_KEY));
            return value && value.until > Date.now() ? value : null;
        } catch (_) { return null; }
    }

    function remember(email) {
        try {
            if (email) window.localStorage.setItem(SKIP_KEY, JSON.stringify({ email: email, until: Date.now() + SKIP_MS }));
            else window.localStorage.removeItem(SKIP_KEY);
        } catch (_) { /* storage blocked: the popup simply shows again */ }
    }

    function prefillRegistration(email) {
        const field = document.querySelector('.ur-frontend-form input[type=email], .user-registration-form input[type=email]');
        if (field && !field.value) field.value = email;
    }

    async function request(data) {
        const response = await fetch(config.ajax, {
            method: 'POST', credentials: 'same-origin', cache: 'no-store',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
            body: new URLSearchParams(Object.assign({ action: 'grb_flow' }, data))
        });
        let result;
        try { result = await response.json(); } catch (_) { throw new Error('We could not reach GLOW. Please try again.'); }
        if (!response.ok || !result.success) {
            if (response.status === 403) bootstrapPromise = null;
            const error = new Error(result.data && result.data.message || 'Something went wrong. Please try again.');
            error.field = result.data && result.data.field || '';
            throw error;
        }
        return result.data;
    }

    function bootstrap() {
        if (!bootstrapPromise) bootstrapPromise = request({ operation: 'bootstrap' }).catch(error => { bootstrapPromise = null; throw error; });
        return bootstrapPromise;
    }

    function showState(root, state) {
        ['lookup', 'new', 'active', 'rebook'].forEach(name => {
            root.querySelector('[data-grb-' + name + ']').hidden = name !== state;
        });
        root.querySelector('[data-grb-back]').hidden = state === 'lookup';
        const field = root.querySelector('[data-grb-' + state + '] input:not([readonly])');
        if (field) field.focus();
    }

    function status(root, message, error) {
        const node = root.querySelector('[data-grb-status]');
        node.textContent = message;
        node.classList.toggle('is-error', Boolean(error));
        node.setAttribute('role', error ? 'alert' : 'status');
    }

    const FIELD_HINTS = { password: 'Please try again, or use "Forgot your password?" below.' };

    function clearFieldError(input) {
        input.removeAttribute('aria-invalid');
        const note = input.parentNode.querySelector('.grb-field-error');
        if (note) note.remove();
    }

    /** Shows a form-level error as an alert just above the form's submit button. */
    function formError(form, message) {
        clearFormError(form);
        // Not a span/div/p: the Elementor popup's form styles strip the border, background and padding from those.
        const note = document.createElement('output');
        note.className = 'grb-form-error';
        note.setAttribute('role', 'alert');
        note.textContent = message;
        const button = form.querySelector('button[type=submit]');
        button.before(note);
        note.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function clearFormError(form) {
        form.querySelectorAll('.grb-form-error').forEach(note => note.remove());
    }

    /** Shows the error as an alert right under its input; returns false when the field is not on the form. */
    function fieldError(form, name, message) {
        const input = form.querySelector('input[name="' + name + '"]');
        if (!input || !FIELD_HINTS[name]) return false;
        clearFieldError(input);
        // Not a span/div/p: the Elementor popup's form styles strip the border, background and padding from those.
        const note = document.createElement('output');
        note.className = 'grb-field-error';
        note.id = 'grb-error-' + name + '-' + Math.random().toString(36).slice(2, 8);
        note.setAttribute('role', 'alert');
        const title = document.createElement('strong');
        title.textContent = message;
        const hint = document.createElement('span');
        hint.textContent = FIELD_HINTS[name];
        note.append(title, hint);
        input.after(note);
        input.setAttribute('aria-invalid', 'true');
        input.setAttribute('aria-describedby', note.id);
        input.addEventListener('input', () => clearFieldError(input), { once: true });
        input.focus();
        input.select();
        return true;
    }

    function initialize(root) {
        if (states.has(root)) return;
        const state = { email: '', busy: false };
        states.set(root, state);
        const lookup = root.querySelector('[data-grb-lookup]');
        const rebook = root.querySelector('[data-grb-rebook]');
        const login = root.querySelector('[data-grb-active]');
        const pending = { lookup: 'Checking your account…', login: 'Signing you in…', rebook: 'Creating your new fundraiser. Please keep this window open…' };
        const done = { login: 'Signed in. Opening your dashboard…', rebook: 'Your fundraiser is ready. Opening your dashboard…' };
        bootstrap().then(data => {
            root.querySelectorAll('input[type=date]').forEach(input => { input.min = data.today; });
        }).catch(error => status(root, error.message, true));

        async function submit(form, operation) {
            if (state.busy || !form.reportValidity()) return;
            state.busy = true;
            form.querySelector('button[type=submit]').disabled = true;
            root.setAttribute('aria-busy', 'true');
            clearFormError(form);
            status(root, pending[operation]);
            try {
                const boot = await bootstrap();
                const data = Object.fromEntries(new FormData(form).entries());
                Object.assign(data, { operation: operation, nonce: boot.nonce, mode: root.dataset.mode });
                if (operation === 'rebook' && root.dataset.mode !== 'dashboard') data.email = state.email;
                const result = await request(data);
                if (operation === 'lookup') {
                    state.email = data.email.trim();
                    root.querySelector('[data-grb-email]').textContent = state.email;
                    root.querySelector('[data-grb-login-email]').value = state.email;
                    root.querySelector('[data-grb-register]').href = result.register;
                    remember(result.state === 'new' ? state.email : '');
                    showState(root, result.state === 'closed' ? 'rebook' : result.state);
                    status(root, '');
                } else {
                    root.querySelectorAll('input[name=password]').forEach(input => { input.value = ''; });
                    status(root, done[operation]);
                    // Only navigate after the backend confirms the sign-in or transfer succeeded.
                    window.location.assign(result.redirect);
                    return;
                }
            } catch (error) {
                // A field error is shown under its input; anything else just above the submit button.
                status(root, '');
                if (!error.field || !fieldError(form, error.field, error.message)) formError(form, error.message);
            } finally {
                state.busy = false;
                form.querySelector('button[type=submit]').disabled = false;
                root.removeAttribute('aria-busy');
            }
        }
        rebook.addEventListener('submit', event => { event.preventDefault(); submit(rebook, 'rebook'); });
        const start = rebook.elements.start_date;
        const end = rebook.elements.end_date;
        start.addEventListener('change', () => { end.min = start.value || start.min; });
        // A disabled fieldset is neither validated nor submitted, so the address is only sent when it changed.
        const addressToggle = rebook.querySelector('[data-grb-address-toggle]');
        const address = rebook.querySelector('[data-grb-address]');
        if (addressToggle && address) {
            const syncAddress = () => {
                address.hidden = !addressToggle.checked;
                address.disabled = !addressToggle.checked;
            };
            addressToggle.addEventListener('change', () => {
                syncAddress();
                if (addressToggle.checked) address.querySelector('input').focus();
            });
            rebook.addEventListener('reset', () => setTimeout(syncAddress, 0));
            syncAddress();
        }
        // The signed-in (dashboard) form has only the rebook step.
        if (!lookup) return;
        lookup.addEventListener('submit', event => { event.preventDefault(); submit(lookup, 'lookup'); });
        login.addEventListener('submit', event => { event.preventDefault(); submit(login, 'login'); });
        root.querySelector('[data-grb-back]').addEventListener('click', () => {
            if (state.busy) return;
            rebook.reset();
            login.reset();
            root.querySelectorAll('input[aria-invalid]').forEach(clearFieldError);
            root.querySelectorAll('form').forEach(clearFormError);
            remember('');
            status(root, '');
            showState(root, 'lookup');
        });
        root.querySelector('[data-grb-register]').addEventListener('click', event => {
            // The registration form is already behind the Elementor popup on this page.
            if (new URL(event.currentTarget.href).pathname.replace(/\/$/, '') !== window.location.pathname.replace(/\/$/, '')) return;
            const popup = root.closest('.elementor-popup-modal');
            const api = window.elementorProFrontend && window.elementorProFrontend.modules.popup;
            if (popup && api) {
                event.preventDefault();
                const id = Number(popup.id.replace('elementor-popup-modal-', ''));
                api.closePopup({ id: id }, event);
                prefillRegistration(state.email);
                const form = document.querySelector('.ur-frontend-form input:not([type=hidden]), .user-registration-form input:not([type=hidden])');
                if (form) { form.focus(); form.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
            }
        });
    }

    function scan() {
        document.querySelectorAll('[data-grb]').forEach(initialize);
        if (document.querySelector('[data-grb-dialog]')) {
            const link = document.getElementById('rebook_ajax');
            if (link && link.textContent !== 'Book Next Year') link.textContent = 'Book Next Year';
        }
    }
    // Capture stops the old dashboard handler from renaming the email and logging the user out.
    document.addEventListener('click', event => {
        const target = event.target instanceof Element && event.target.closest('#rebook_ajax, [data-grb-open]');
        if (!target) return;
        const dialog = document.querySelector('[data-grb-dialog]');
        if (!dialog) return;
        event.preventDefault();
        event.stopImmediatePropagation();
        scan();
        dialog.showModal();
    }, true);
    document.addEventListener('click', event => {
        if (event.target instanceof Element && event.target.closest('[data-grb-close]')) {
            event.target.closest('dialog').close();
        }
    });
    const skip = skipped();
    if (skip) {
        // CSS hides the gate popup at once; closing it through Elementor also releases its scroll lock.
        document.documentElement.classList.add('grb-skip-gate');
        if (window.jQuery) {
            window.jQuery(document).on('elementor/popup/show', (event, id, instance) => {
                const modal = document.getElementById('elementor-popup-modal-' + id);
                if (!modal || !modal.querySelector('[data-grb][data-mode=gate]')) return;
                // closePopup() finds the popup from a click target, so hide the popup's modal directly.
                setTimeout(() => {
                    const doc = instance || (window.elementorFrontend && window.elementorFrontend.documentsManager.documents[id]);
                    if (doc && doc.getModal) doc.getModal().hide();
                    document.querySelectorAll('.dialog-prevent-scroll').forEach(node => node.classList.remove('dialog-prevent-scroll'));
                }, 0);
            });
        }
        const fill = () => prefillRegistration(skip.email);
        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fill); else fill();
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', scan); else scan();
    new MutationObserver(scan).observe(document.documentElement, { childList: true, subtree: true });
})();
