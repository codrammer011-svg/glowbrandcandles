'use strict';
const assert = require('node:assert/strict');
const { chromium } = require(process.env.GRB_PLAYWRIGHT_MODULE || 'playwright');
const path = require('node:path');
const [url, start, end, output] = process.argv.slice(2);

(async () => {
    const browser = await chromium.launch({ headless: true });
    try {
        const context = await browser.newContext({ viewport: { width: 1100, height: 1000 } });
        const page = await context.newPage();
        const errors = [];
        page.on('pageerror', error => errors.push(error.message));
        await page.goto(url + '/fundraiser-register/');
        await page.locator('[data-grb-lookup] input').fill('new-browser@example.invalid');
        await page.locator('[data-grb-lookup] button').click();
        await page.locator('[data-grb-new]').waitFor({ state: 'visible' });
        // Exercise the real popup-close integration contract without requiring Elementor in this isolated install.
        await page.evaluate(() => {
            const popup = document.createElement('div');
            popup.className = 'elementor-popup-modal';
            popup.id = 'elementor-popup-modal-123';
            document.body.appendChild(popup);
            popup.appendChild(document.querySelector('[data-grb]'));
            window.elementorProFrontend = { modules: { popup: { closePopup(settings, event) {
                window.CLOSED_POPUP_ID = settings.id;
                event.target.closest('.elementor-popup-modal').hidden = true;
            } } } };
        });
        await page.locator('[data-grb-register]').click();
        assert.equal(await page.evaluate(() => window.CLOSED_POPUP_ID), 123);
        console.log('PASS: new-account path closes Elementor popup on registration page');

        await page.goto(url + '/fundraiser-register/');
        await page.locator('[data-grb-lookup] input').fill('active@example.invalid');
        await page.locator('[data-grb-lookup] button').click();
        await page.locator('[data-grb-active]').waitFor({ state: 'visible' });
        assert.equal(await page.locator('[data-grb-login-email]').inputValue(), 'active@example.invalid');
        assert.equal(await page.locator('[data-grb-active] [name=password]').count(), 1);
        console.log('PASS: active-account path presents inline login with email prefilled');
        await page.locator('[data-grb-back]').click();
        await page.locator('[data-grb-lookup] input').fill('browser-chair@example.invalid');
        await page.locator('[data-grb-lookup] button').click();
        const form = page.locator('[data-grb-rebook]');
        await form.waitFor({ state: 'visible' });
        await form.locator('[name=password]').fill('wrong-password');
        await form.locator('[name=start_date]').fill(start);
        await form.locator('[name=end_date]').fill(end);
        await form.locator('[name=confirm]').check();
        await form.locator('button[type=submit]').click();
        await page.getByText('Your email or password is incorrect.', { exact: true }).waitFor();
        assert.equal(page.url(), url + '/fundraiser-register/');
        assert.equal(await form.locator('[name=password]').getAttribute('aria-invalid'), 'true');
        await form.locator('.grb-field-error').waitFor();
        console.log('PASS: invalid password leaves user in popup with error');
        await form.locator('[name=password]').fill('Test! Same password 123');
        assert.equal(await form.locator('.grb-field-error').count(), 0);
        await page.screenshot({ path: path.join(output, 'rebook-desktop.png'), fullPage: true });

        const mobile = await browser.newContext({ viewport: { width: 375, height: 812 }, isMobile: true });
        const mobilePage = await mobile.newPage();
        await mobilePage.goto(url + '/fundraiser-register/');
        await mobilePage.locator('[data-grb-lookup] input').fill('browser-chair@example.invalid');
        await mobilePage.locator('[data-grb-lookup] button').click();
        await mobilePage.locator('[data-grb-rebook]').waitFor({ state: 'visible' });
        assert.equal(await mobilePage.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth), true);
        await mobilePage.screenshot({ path: path.join(output, 'rebook-mobile.png'), fullPage: true });
        await mobile.close();
        console.log('PASS: mobile rebook form fits viewport');

        await Promise.all([page.waitForURL('**/fundraiser-dashboard/'), form.locator('button[type=submit]').click()]);
        const identity = await (await page.request.get(url + '/whoami')).json();
        assert.equal(identity.data.email, 'browser-chair@example.invalid');
        const firstId = identity.data.id;
        assert.ok(firstId > 0);
        console.log('PASS: popup rebooking lands on dashboard already signed in');

        await page.request.post(url + '/test-close-current');
        await page.reload();
        await page.getByRole('link', { name: 'Book Next Year', exact: true }).click();
        const dialog = page.locator('dialog[open]');
        await dialog.waitFor({ state: 'visible' });
        assert.notEqual(await page.evaluate(() => window.LEGACY_REBOOK_RAN), true);
        assert.equal(await dialog.locator('[name=password]').count(), 0);
        await dialog.locator('[name=start_date]').fill(start);
        await dialog.locator('[name=end_date]').fill(end);
        await dialog.locator('[name=confirm]').check();
        await Promise.all([
            page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
            dialog.locator('[data-grb-rebook] button[type=submit]').click()
        ]);
        const nextIdentity = await (await page.request.get(url + '/whoami')).json();
        assert.equal(nextIdentity.data.email, 'browser-chair@example.invalid');
        assert.notEqual(nextIdentity.data.id, firstId);
        assert.deepEqual(errors, []);
        console.log('PASS: dashboard date dialog overrides legacy handler and signs into next campaign');
        await context.close();
    } finally {
        await browser.close();
    }
})().catch(error => { console.error(error); process.exitCode = 1; });
