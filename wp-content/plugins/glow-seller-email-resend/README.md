# GLOW – Resend Seller Emails

**Version:** 1.5.0 · **Author:** Saad Ahmed (saad@flywebsolutions.com)
**Type:** standalone WordPress plugin, a single file (`glow-seller-email-resend.php`)
**Admin page:** *Users → Resend Seller Emails* (`users.php?page=glow-resend-seller-emails`, needs `manage_options`)

Re-sends the **seller registration email** (QR code, seller link, login info) to sellers who didn't receive it, for example because of an SMTP outage. It works out who missed the email from the WP Mail SMTP error log, sends one email per person (duplicate accounts are skipped), never re-sends twice, and can send in the background through WooCommerce's Action Scheduler at 4 emails per minute.

This document covers how the plugin works, what it assumes about the **current live site** (the old *ROYAL – 8theme* theme, slug `glowscentedcandles`), and **what to change when moving to the new site structure** (Hello Elementor plus the `glow-scented-candles` child theme, `glow-fundraiser-core`, `Fundraiser-Modules`, `glow-dashboard-fixes`).

---

## Contents

1. [Why it exists](#1-why-it-exists)
2. [How it works](#2-how-it-works)
3. [Function reference](#3-function-reference)
4. [Data it reads](#4-data-it-reads)
5. [Data it writes](#5-data-it-writes)
6. [AJAX endpoints and hooks](#6-ajax-endpoints-and-hooks)
7. [Constants and settings](#7-constants-and-settings)
8. [Live-site assumptions (important)](#8-live-site-assumptions-important)
9. [Porting to the new site structure](#9-porting-to-the-new-site-structure)
10. [Testing checklist](#10-testing-checklist)
11. [Known limitations](#11-known-limitations)
12. [Changelog](#12-changelog)

---

## 1. Why it exists

On the live site the seller welcome email is sent **once**, during registration, by the theme's `send_welcome_email_to_new_user()` (hooked on `user_register`, priority 40, in the ROYAL theme's `functions.php`). Nothing records whether it was delivered. When the Bluehost SMTP server failed (auth errors from about Oct 3–7, 2026, then connection refused), those sellers never got their QR code or link, and many registered again, creating duplicate accounts.

## 2. How it works

```
Admin page ─┬─ list sellers (role "seller") registered in a date range
            ├─ flag "Failed": WP Mail SMTP debug-event error logged right after registration
            ├─ group duplicates: same first+last name under the same fundraiser → one "primary"
            ├─ Preview / Test (send to any address, subject prefixed "[TEST]", not recorded)
            ├─ Send now from this page: browser loop, 1 email per AJAX request, adjustable pause
            └─ Send in background: Action Scheduler jobs, 4/min, hard per-minute cap
```

### 2.1 Building the email (`glow_ser_compose()`)

1. **Site template first** (`glow_ser_theme_email()`). It calls the theme's own `send_welcome_email_to_new_user( $user_id )` with a fake `$_POST['form_data']` built from the seller's saved meta, and **intercepts every `wp_mail()` call** with a `pre_wp_mail` filter at `PHP_INT_MAX` that returns `true`, so nothing is actually sent. The theme normally also mails Candace's two addresses and some invalid ones. Only the copy addressed to the seller is kept, then cleaned up:
   - The plain-text password (never stored) is swapped for a marker, then replaced with "the password you created… (reset link)". `&pass=` is removed from the login URL.
   - The template's hard-coded example link `…/seller/user/tulip1936` is replaced with the seller's own link.
   - The optional "we're re-sending…" note goes inside the card, at the start of the first `<td>` after the header's `</h1>` (`glow_ser_insert_outage_note()`), with a fallback to just after `<body>`.
   - The only side effect: the theme regenerates `uploads/qrcodes/image_{id}.png` (same URL encoded) and re-saves the `seller_barcode_link` meta.
2. **Built-in copy** (`glow_ser_build_email()`) is used if the theme function doesn't exist or didn't produce a seller email. It's a hand-made copy of the live seller template.

The preview bar and test-email result say which source was used ("site's current seller email" or "built-in copy").

### 2.2 Detecting failed sends (`glow_ser_failed_send_times()` and `glow_ser_has_failure()`)

- Reads `{prefix}wpmailsmtp_debug_events` where `event_type = 0` (error) **and** `initiator LIKE '%send_welcome_email_to_new_user%'`. The initiator JSON only contains the backtrace while **WP Mail SMTP → Tools → Debug Events → "Debug Email Sending"** is ON, as it is on live.
- Excludes `at least one recipient` (the theme always tries one empty address) and `following recipients failed` (a single bad address, not an outage).
- A seller is **Failed** if such an error falls within **−10 s / +120 s** of their registration time.

### 2.3 Registration-time zone (`glow_ser_registered_is_local()`)

On live, `wp_users.user_registered` is stored in **site-local time (UTC−6)**, not UTC as WordPress normally does. The plugin **auto-detects** this by checking which reading of `user_registered` lines up with the WP Mail SMTP events logged at registration for the latest 30 sellers. The result is cached for a day in the transient `glow_ser_reg_is_local`. Every date query, failure match and displayed time goes through `glow_ser_registered_ts()`.

### 2.4 Duplicates (`glow_ser_dup_info()`)

- **Same person:** lowercase letters-only `first_name + last_name`, under the same fundraiser ID (`user_registration_seller_fundraiser` or `seller_fundraiser`). It compares against **all** sellers of that fundraiser, not just the date range.
- **Primary account** (the only one emailed), in priority order:
  1. Most orders credited (`wc_seller_field` in `postmeta` and, if present, `wc_orders_meta`).
  2. Email quality: personal (2), then school (1, domain matches `school|.edu|k12`), then typo (0, e.g. `gmial.com`, `*.con`, `warwickschools.com`).
  3. Newest `user_registered`, then highest ID.
- Duplicates are greyed out in the UI and refused server-side. **Nothing is deleted.**

### 2.5 Never twice

`glow_ser_skip_reason()` is shared by manual and background sending. It refuses non-sellers, duplicates, and anyone with the `_glow_seller_email_resent` meta. Test sends never write that meta.

### 2.6 Background sending

- **Queue** (`wp_ajax_glow_ser_bg_queue`): schedules one `glow_ser_bg_send` action per seller (group `glow-seller-email-resend`), spaced `60 / 4 = 15 s` apart, appending after any existing queue (`next_slot`). It marks the user with `_glow_seller_email_queued`.
- **Job** (`GLOW_SER_BG_HOOK`): takes a slot from a per-minute counter (`glow_ser_bg_rate` option). If 4 have already gone this minute, it reschedules itself for the next minute, so the scheduler running late can never cause a burst. Then it sends, records the result, and updates the stats option.
- **Cancel** (`wp_ajax_glow_ser_bg_cancel`): `as_unschedule_all_actions( 'glow_ser_bg_send' )` and clears all queued meta.
- **Status** is shown on page load and polled every 20 s while anything is pending.

### 2.7 Browser sending

The page's JS sends one seller per AJAX request, with a configurable pause (default 10 s). The pause runs on a **Web Worker timer**, because Chrome throttles main-thread timers in background tabs, and is measured by the clock, not by counting ticks. It has a Stop button and a `beforeunload` warning while running.

## 3. Function reference

| Function | Purpose |
|---|---|
| `glow_ser_meta( $uid, $keys )` | First non-empty user meta from a list of keys |
| `glow_ser_seller_data( WP_User )` | Normalised seller data: fid, org, goal, link, names, registered |
| `glow_ser_name_key()` / `glow_ser_email_quality()` | Duplicate matching and ranking helpers |
| `glow_ser_order_counts( $ids )` | Orders per seller via `wc_seller_field` (postmeta and HPOS) |
| `glow_ser_dup_info()` / `glow_ser_is_duplicate()` | Duplicate group and primary account |
| `glow_ser_qr_url( $s )` | Reuses or creates `uploads/qrcodes/image_{id}.png` (served over https) |
| `glow_ser_failed_send_times()` / `glow_ser_has_failure()` | Failure detection from WP Mail SMTP debug events |
| `glow_ser_registered_is_local()` / `glow_ser_registered_ts()` | `user_registered` time-zone detection and conversion |
| `glow_ser_build_email()` | Built-in fallback template |
| `glow_ser_theme_email()` | Captures the live theme's seller email (see 2.1) |
| `glow_ser_insert_outage_note()` / `glow_ser_outage_note_html()` | Outage note placement |
| `glow_ser_compose()` | Picks the template source; returns subject, body, headers, source |
| `glow_ser_send()` | Composes and sends via `wp_mail()`; returns `['ok','source']` |
| `glow_ser_skip_reason()` / `glow_ser_record_resent()` | Shared guard and resend log |
| `glow_ser_bg_*()` | Background availability, stats, pending count, rate slot, status |
| `glow_ser_render_page()` | Admin page (HTML and inline JS) |

## 4. Data it reads

| Source | Keys / tables |
|---|---|
| Users | role `seller`; `user_email`, `user_login`, `user_registered` |
| Seller meta | `first_name`, `last_name`, `user_registration_seller_fundraiser` / `seller_fundraiser` / `fundraiser_id`, `user_registration_seller_goal` / `seller_goal` |
| Fundraiser meta | `user_registration_name_of_school_business` (group name, on the fundraiser user `{fid}`) |
| Orders | `wc_seller_field` (seller user ID) in `wp_postmeta` and `wp_wc_orders_meta` |
| QR image | `wp-content/uploads/qrcodes/image_{user_id}.png` |
| Email log | `{prefix}wpmailsmtp_debug_events` (WP Mail SMTP) |
| Options | `gmt_offset` |

## 5. Data it writes

| Key | Where | Meaning |
|---|---|---|
| `_glow_seller_email_resent` | user meta | Array of `['at' => local mysql time, 'by' => user ID, 'background' => bool]`. Its presence blocks further resends. |
| `_glow_seller_email_queued` | user meta | Queued for background sending |
| `_glow_seller_email_bg_failed` | user meta | Time of the last failed background send (cleared on success or re-queue) |
| `glow_ser_bg_stats` | option (no autoload) | total, sent, failed, skipped, started, next_slot, subject, note, by |
| `glow_ser_bg_rate` | option (no autoload) | `['m' => unix minute, 'n' => count]`, the per-minute cap |
| `glow_ser_reg_is_local` | transient (1 day) | `'yes'` / `'no'`, the time-zone detection result |
| `seller_barcode_link` | user meta | Written by the theme template or `glow_ser_qr_url()` when a QR is generated |

To reset a seller so they can be re-sent: `delete_user_meta( $id, '_glow_seller_email_resent' );`

## 6. AJAX endpoints and hooks

All AJAX endpoints require `manage_options` and the nonce `glow_ser` (`nonce` param).

| Action | Method | Does |
|---|---|---|
| `glow_ser_preview` | GET | Email preview for `user_id` (`note=1` adds the outage note) |
| `glow_ser_test` | POST | Sends one seller's email to `test_to` (`seller` = ID, login or email; blank means the latest seller) |
| `glow_ser_send` | POST | Sends to `ids[]` (max 10 per request; the UI sends 1) |
| `glow_ser_bg_queue` | POST | Queues `ids[]` for background sending |
| `glow_ser_bg_cancel` | POST | Cancels all pending background jobs |
| `glow_ser_bg_status` | POST | Background progress JSON |

Action Scheduler hook: **`glow_ser_bg_send`** (arg: user ID), group **`glow-seller-email-resend`**.

## 7. Constants and settings

| Constant | Default | Notes |
|---|---|---|
| `GLOW_SER_SELLER_BASE` | `https://glowscentedcandles.com` | Base of the seller link `/seller/user/{login}`. Can be overridden in `wp-config.php`. |
| `GLOW_SER_PER_MINUTE` | `4` | Background send rate (and the hard per-minute cap) |
| `GLOW_SER_MAX_ROWS` | `1500` | Max sellers listed or queued at once |
| `GLOW_SER_BG_HOOK` / `GLOW_SER_BG_GROUP` | see above | Action Scheduler identifiers |

UI options per run: subject (blank means the template subject, "Seller Registered"), outage note on/off, and the pause between emails (browser mode).

Hard-coded values to review: the From header `Glow Scented Candles <info@glowscentedcandles.com>`, the Dropbox product-images link in the built-in template, the `tulip1936` replacement, and the typo-domain list (including `warwickschools.com`).

## 8. Live-site assumptions (important)

These are true on **glowscentedcandles.com today** and are what the plugin was built against:

1. **Theme:** ROYAL – 8theme (`glowscentedcandles`). It defines `send_welcome_email_to_new_user()` and `glow_find_character_field()` in its `functions.php`.
2. The theme function reads **`$_POST['form_data']`**, a JSON array of `{label, value}` built by the old registration JS, **by position**:
   `[0]` first name · `[1]` last name · `[2]` email · `[3]` password · `[4]` fundraiser ID · `[5]` seller goal. It recognises a seller **only** when `[5]->label === "Seller Goal: (Dollar Amount)"`, and the subject is `"Seller Registered"`.
3. The theme sends the seller email with `wp_mail( $user_email, … )` from the line commented `/* Registered Recipients */`. It also mails `candace@glowscentedcandles.com` and `candacethompson12@gmail.com` on every registration. The plugin intercepts all of these.
4. QR: the theme uses `get_template_directory() . '/phpqrcode/qrlib.php'` and writes `uploads/qrcodes/image_{id}.png` encoding `https://glowscentedcandles.com/seller/user/{login}`.
5. `user_registered` is stored in **local time (UTC−6)**. This is auto-detected (2.3).
6. WP Mail SMTP (free) with **Debug Email Sending ON**. It has no email log or resend feature, which is why this plugin exists.
7. WooCommerce present, so Action Scheduler is available. Orders use classic post storage (`shop_order`). HPOS is also handled.
8. The admin account used does **not** have the `install_plugins` capability, so the plugin was installed over SFTP.

## 9. Porting to the new site structure

The new build (Hello Elementor + `glow-scented-candles` child theme) is different in ways that matter here.

### 9.1 What the new build does today

- Seller registration: User Registration form **#62307** on `/register-as-a-seller/` (page 2242). It submits via UR AJAX, **not** the old `form_data` JS.
- `glow_handle_seller_form_submission()` (child theme `functions.php`, `user_registration_after_register_user_action`, priority 25) adds the `seller` role and maps `user_registration_seller_fundraiser`, `seller_fundraiser`, `fundraiser_id`, `seller_goal` and `image_url_profile`. **The meta keys match what this plugin reads.**
- `seller-share-popup.php` shows a "Share Now" modal with **`/profile/{id}`** as the seller link.
- The seller dashboard (`Fundraiser-Modules/templates/seller-dashboard-new.php`) builds the shop link as **`/seller/user/{login}`** (filterable via `glow_sd_shop_link`) and reads the QR from **`seller_barcode_link`** meta.
- The bundled legacy copy `glow-fundraiser-core/legacy/theme-functions.php` has `add_action('user_register', 'send_welcome_email_to_new_user')` **commented out**. Even if enabled, it depends on `$_POST['form_data']`, which the new form doesn't send.

**So on the new site, sellers get no welcome email with a QR code or link, and nothing generates `seller_barcode_link` for them.** Only User Registration's generic "registration complete" email goes out. This is the main gap to fix, and it isn't part of this plugin.

### 9.2 Recommended changes

1. **Create one real seller-email builder** in the child theme or `glow-fundraiser-core`, for example:
   ```php
   /** @return array{subject:string, body:string, headers:array} */
   function glow_build_seller_welcome_email( int $user_id ): array { … }
   function glow_send_seller_welcome_email( int $user_id ): bool { … } // generates QR + seller_barcode_link, then wp_mail()
   ```
   - Read everything from **user meta** (like `glow_email_user_fundraiser_welcome()` already does for chairs). Never use positional `$_POST` fields, and **never email plain-text passwords**.
   - Generate the QR with `GLOW_FC_DIR . 'includes/phpqrcode/qrlib.php'` into `uploads/qrcodes/image_{id}.png` and save `seller_barcode_link`. The seller dashboard depends on it.
   - Hook it on `user_registration_after_register_user_action` for form 62307 / role `seller` (after priority 25, so the meta is mapped). Store a "sent" flag such as `_glow_seller_welcome_sent`, as the chair email does with `_glow_welcome_email_sent`.
2. **Point this plugin at it.** Replace the body of `glow_ser_theme_email()` with:
   ```php
   if ( function_exists( 'glow_build_seller_welcome_email' ) ) {
       $m = glow_build_seller_welcome_email( $user->ID );
       return array( 'subject' => $m['subject'], 'body' => $m['body'], 'headers' => $m['headers'] );
   }
   return null;
   ```
   Then delete the `$_POST['form_data']` faking, the `pre_wp_mail` interception, and the password-marker and `tulip1936` replacements. Keep `glow_ser_insert_outage_note()`, or move the note into the builder behind a parameter. Once that's done, `glow_ser_build_email()` (the built-in copy) can be removed.
3. **Update failure detection.** In `glow_ser_failed_send_times()`, change the `initiator LIKE` needle from `send_welcome_email_to_new_user` to the new send function's name. Remove the "at least one recipient" exclusion if the new code no longer mails empty addresses. Keep "Debug Email Sending" ON in WP Mail SMTP so the backtrace is stored. Better still, have the new send function record success or failure in user meta (e.g. `_glow_seller_welcome_sent` / `_glow_seller_welcome_failed`) and read that instead of parsing the log.
4. **Decide the seller link.** The live email and QR use `/seller/user/{login}`. The new share popup uses `/profile/{id}`. Pick one for the email, QR, popup and dashboard, then set `GLOW_SER_SELLER_BASE` and the path in `glow_ser_seller_data()` to match. Existing printed QR codes point to `/seller/user/{login}`, so keep that route working, or redirect it.
5. **QR library path.** `glow_ser_qr_url()` already tries `get_template_directory()/phpqrcode/qrlib.php` and then `GLOW_FC_DIR/includes/phpqrcode/qrlib.php`. On Hello Elementor only the second exists, which is fine. Remove the first once ROYAL is gone.
6. **Time zone.** Check how the new registration stores `user_registered` (WordPress default is UTC). The auto-detection handles either, but **delete the transient `glow_ser_reg_is_local` after the switch** so it re-detects immediately. Once the new site is confirmed to use UTC, the detection can be replaced with plain `strtotime( $registered . ' UTC' )`.
7. **Duplicate accounts.** The rules depend only on user meta and `wc_seller_field`. Confirm the new checkout still writes the seller's user ID to `wc_seller_field`, and whether HPOS gets enabled (both storages are queried).
8. **Admin notification bug** (separate from this plugin): the new child theme's `glow_email_admin_fundraiser_summary()` sends to `samraimi786@gmail.com`, with Candace's addresses "temporarily disabled for testing". Restore those before launch.

### 9.3 Things that carry over unchanged

The admin page, filters, duplicate logic, the never-twice guard, test emails, browser sending (Web Worker timer), background sending (Action Scheduler with the 4/min cap), and all stored meta and options keys.

## 10. Testing checklist

- [ ] Set the date range to the outage days. The "Failed" count should be realistic, not a handful. If it's near zero, check the initiator needle (9.2 step 3) and the time-zone transient (9.2 step 6).
- [ ] Duplicates: compare greyed rows and "Main account" rows against the fundraiser's seller report (Warwick, fundraiser 215868, had 12 duplicated sellers and 20 extra accounts).
- [ ] **Preview**: the dark bar shows the expected template source; the seller's own link and QR; no password; the reset link works; the note sits under the header.
- [ ] **Test email** to Gmail and Outlook addresses: arrives with the QR image showing (QR URLs are forced to https).
- [ ] Background: queue 2 sellers; the panel goes Running, then "2 sent" within about 2 minutes; the rows show "Resent … (background)"; re-queueing them is refused.
- [ ] Cancel: queue several, then Cancel; pending goes to 0 and queued sellers become selectable again.
- [ ] Browser mode: switch tabs for over 5 minutes; the pace holds.
- [ ] Confirm **nothing reaches Candace's addresses** during preview, test or send. The interception short-circuits them; check WP Mail SMTP Debug Events.

## 11. Known limitations

- "Failed" is inferred from the SMTP error log. If SMTP accepted an email and Bluehost dropped it later, it shows as "No error logged". Use the "All sellers not re-sent yet" filter in that case.
- Background sending depends on Action Scheduler running (WP-Cron or the async runner, which needs site traffic or admin page loads).
- The browser mode stops if the tab is closed or the computer sleeps. Chrome's Memory Saver and Edge's Sleeping tabs can freeze a long-hidden tab.
- Duplicate matching is by name plus fundraiser, so two different children with identical names in the same fundraiser would be merged. This is rare, but check the Duplicates column.
- The capture approach (2.1) depends on the ROYAL template's positional fields and its "Seller Goal: (Dollar Amount)" label. If those change, it falls back to the built-in copy, and the preview bar shows that.

## 12. Changelog

| Version | Change |
|---|---|
| 1.0.0 | Admin page, failure detection from WP Mail SMTP, rebuilt seller email, batched resend |
| 1.1.0 | Standalone "Send a test email" panel (any address, choose seller) |
| 1.2.0 | Duplicate detection: one email per person; duplicates refused server-side |
| 1.2.1 | Author credit |
| 1.2.2 | Auto-detects `user_registered` stored in local time (live is UTC−6); fixes failure matching and displayed times |
| 1.3.0 | Email built from the site's live template (captured, nothing else sent); password becomes a reset link; `tulip1936` link fixed |
| 1.3.1 | "Not re-sent yet" filters; already re-sent sellers can't be sent again |
| 1.4.0 | One email at a time with adjustable pause; Stop button |
| 1.4.1 | Outage note placed inside the card under the header |
| 1.4.2 | Web Worker timer so background tabs don't stall sending |
| 1.5.0 | Background sending via Action Scheduler, 4/min with a hard per-minute cap; progress panel and Cancel |

---

Questions: **Saad Ahmed**, saad@flywebsolutions.com
