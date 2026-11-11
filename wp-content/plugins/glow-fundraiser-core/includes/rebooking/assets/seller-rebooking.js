(function () {
    'use strict';
    const config = window.GLOW_SELLER_REBOOK;
    if (!config) return;
    let noncePromise;

    async function request(data) {
        const response = await fetch(config.ajax, {
            method: 'POST', credentials: 'same-origin', cache: 'no-store',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
            body: new URLSearchParams(Object.assign({ action: 'gsr_flow' }, data))
        });
        let result;
        try { result = await response.json(); } catch (_) { throw new Error('We could not reach GLOW. Please try again.'); }
        if (!response.ok || !result.success) {
            if (response.status === 403) noncePromise = null;
            throw new Error(result.data && result.data.message || 'Something went wrong. Please try again.');
        }
        return result.data;
    }

    // Fresh nonce from an uncached request, not from cached page HTML.
    function nonce() {
        if (!noncePromise) {
            noncePromise = request({ operation: 'bootstrap' }).then(data => data.nonce).catch(error => { noncePromise = null; throw error; });
        }
        return noncePromise;
    }

    async function call(operation, fields) {
        return request(Object.assign({}, fields, { operation: operation, nonce: await nonce() }));
    }

    function init(root) {
        if (root.dataset.gsrReady) return;
        root.dataset.gsrReady = '1';
        const form = root.querySelector('[data-gsr-rebook]');
        const status = root.querySelector('[data-gsr-status]');
        const org = root.querySelector('[data-gsr-org]');
        const idField = form.elements.fundraiser_id;
        const button = form.querySelector('button[type=submit]');
        let busy = false;
        let lookupSeq = 0;

        function setStatus(message, error) {
            status.textContent = message;
            status.classList.toggle('is-error', Boolean(error));
            status.setAttribute('role', error ? 'alert' : 'status');
        }

        function formError(message) {
            form.querySelectorAll('.grb-form-error').forEach(node => node.remove());
            if (!message) return;
            // An <output>, not a div: theme form styles strip borders/backgrounds from divs and spans.
            const note = document.createElement('output');
            note.className = 'grb-form-error';
            note.setAttribute('role', 'alert');
            note.textContent = message;
            button.before(note);
            note.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        function showOrg(text, ok) {
            org.hidden = !text;
            org.textContent = text;
            org.style.color = ok ? '#027a48' : '#b42318';
            idField.setAttribute('aria-invalid', ok || !text ? 'false' : 'true');
        }

        // Shows which group the ID belongs to, so a typo is caught before the account is moved.
        async function lookup() {
            const value = idField.value.trim();
            const seq = ++lookupSeq;
            if (!/^\d{1,10}$/.test(value)) { showOrg('', true); return; }
            showOrg('Checking Fundraiser ID…', true);
            try {
                const data = await call('fundraiser', { fundraiser_id: value });
                if (seq === lookupSeq) showOrg('✓ ' + data.org, true);
            } catch (error) {
                if (seq === lookupSeq) showOrg(error.message, false);
            }
        }
        let timer;
        idField.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(lookup, 500); });
        idField.addEventListener('change', () => { clearTimeout(timer); lookup(); });

        form.addEventListener('submit', async event => {
            event.preventDefault();
            if (busy || !form.reportValidity()) return;
            busy = true;
            button.disabled = true;
            root.setAttribute('aria-busy', 'true');
            formError('');
            setStatus('Re-booking your seller account. Please keep this window open…');
            try {
                const data = Object.fromEntries(new FormData(form).entries());
                const result = await call('rebook', data);
                setStatus('You are re-booked. Opening your new dashboard…');
                // Navigate only after the server confirms the new account and sign-in.
                window.location.assign(result.redirect);
                return;
            } catch (error) {
                setStatus('');
                formError(error.message);
            } finally {
                busy = false;
                button.disabled = false;
                root.removeAttribute('aria-busy');
            }
        });
        nonce().catch(error => setStatus(error.message, true));
    }

    document.addEventListener('click', event => {
        if (!(event.target instanceof Element)) return;
        const opener = event.target.closest('[data-gsr-open]');
        const closer = event.target.closest('[data-gsr-close]');
        const dialog = document.querySelector('[data-gsr-dialog]');
        if (opener && dialog) {
            event.preventDefault();
            init(dialog.querySelector('[data-gsr]'));
            dialog.showModal();
            dialog.querySelector('input[name=fundraiser_id]').focus();
        } else if (closer) {
            closer.closest('dialog').close();
        }
    });
})();
