(function () {
    'use strict';
    const config = window.GLOW_REBOOK;
    if (!config) return;
    let bootstrapPromise;
    const states = new WeakMap();

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
            throw new Error(result.data && result.data.message || 'Something went wrong. Please try again.');
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
        const field = root.querySelector('[data-grb-' + state + '] input');
        if (field) field.focus();
    }

    function status(root, message, error) {
        const node = root.querySelector('[data-grb-status]');
        node.textContent = message;
        node.classList.toggle('is-error', Boolean(error));
    }

    function initialize(root) {
        if (states.has(root)) return;
        const state = { email: '', busy: false };
        states.set(root, state);
        const lookup = root.querySelector('[data-grb-lookup]');
        const rebook = root.querySelector('[data-grb-rebook]');
        bootstrap().then(data => {
            root.querySelectorAll('input[type=date]').forEach(input => { input.min = data.today; });
        }).catch(error => status(root, error.message, true));

        async function submit(form, operation) {
            if (state.busy || !form.reportValidity()) return;
            state.busy = true;
            form.querySelector('button[type=submit]').disabled = true;
            root.setAttribute('aria-busy', 'true');
            status(root, operation === 'lookup' ? 'Checking your account…' : 'Creating your new fundraiser. Please keep this window open…');
            try {
                const boot = await bootstrap();
                const data = Object.fromEntries(new FormData(form).entries());
                Object.assign(data, { operation: operation, nonce: boot.nonce, mode: root.dataset.mode });
                if (operation === 'rebook' && root.dataset.mode !== 'dashboard') data.email = state.email;
                const result = await request(data);
                if (operation === 'lookup') {
                    state.email = data.email.trim();
                    root.querySelector('[data-grb-email]').textContent = state.email;
                    root.querySelector('[data-grb-login]').href = result.login;
                    root.querySelector('[data-grb-register]').href = result.register;
                    showState(root, result.state === 'closed' ? 'rebook' : result.state);
                    status(root, '');
                } else {
                    const password = root.querySelector('input[name=password]');
                    if (password) password.value = '';
                    status(root, 'Your fundraiser is ready. Opening your dashboard…');
                    // Only navigate after the backend confirms that the transfer succeeded.
                    window.location.assign(result.redirect);
                    return;
                }
            } catch (error) {
                status(root, error.message, true);
            } finally {
                state.busy = false;
                form.querySelector('button[type=submit]').disabled = false;
                root.removeAttribute('aria-busy');
            }
        }
        lookup.addEventListener('submit', event => { event.preventDefault(); submit(lookup, 'lookup'); });
        rebook.addEventListener('submit', event => { event.preventDefault(); submit(rebook, 'rebook'); });
        const start = rebook.elements.start_date;
        const end = rebook.elements.end_date;
        start.addEventListener('change', () => { end.min = start.value || start.min; });
        root.querySelector('[data-grb-back]').addEventListener('click', () => {
            if (state.busy) return;
            rebook.reset();
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
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', scan); else scan();
    new MutationObserver(scan).observe(document.documentElement, { childList: true, subtree: true });
})();
