# Fundraiser rebooking (part of GLOW Fundraiser Core)

This feature is loaded by the existing `glow-fundraiser-core.php` entry point. No additional plugin is required.

## Elementor registration popup

Add a Shortcode widget with:

```text
[glow_fundraiser_rebook_gate]
```

Show the popup on `/fundraiser-register/` on page load. Keep the existing registration form on the page behind it.

- New email: continue to the registration form (closes the popup on this page).
- Active fundraiser or other existing account: log in to the existing account.
- Closed fundraiser (`user_action_status = 2`): verify the existing password, enter new start/end dates, confirm, create the new campaign and log in automatically.

## Dashboard

The existing `#rebook_ajax` link opens the Book Next Year date/confirmation dialog. The signed-in chairperson does not need to enter their password again. For a custom Elementor dashboard, `[glow_book_next_year]` renders the same form directly for the owner of a closed fundraiser.

The old `wp_rebooking_ajax` compatibility handler now rejects direct legacy requests instead of renaming the account email. The separate seller handler is unchanged.

## Campaign transfer

The new fundraiser receives a distinct user ID, the same email/username/password hash, the selected dates, profile/contact/organization details and the existing representative's current payment link. The explicit profile allowlist is in `class-glow-fc-rebooking-campaigns.php`.

Old orders, sellers, reports, payments, carts and close-out metadata stay with the previous ID. The previous login/email is archived (email becomes `rebook_<Y-m-d_His>_<original email>`, as with the legacy handler) and linked to the new account; its sessions are revoked after a successful transaction. Original identity information remains in private audit metadata. Archived accounts cannot sign in. Historical records remain available to administrators.

The transfer uses InnoDB transactions and a database lock; repeat clicks reuse the new campaign. Guest rebooking requires WordPress password authentication; dashboard rebooking uses the current session's user ID. Requests require nonce validation, confirmation, date validation and same-origin checks; public lookup/password attempts are rate-limited.

Rebooking does not replay the original registration email sequence or create another automatic $0 kit order. New QR codes are generated when the existing Core QR library/GD are available. User Registration form/approval markers carry over. Disabled accounts cannot rebook.

## Local verification

The existing isolated integration/HTTP/browser tests now live under `scripts/tests/fundraiser-rebooking/` and load this Core module directly. The standalone implementation previously passed its suite; this integration changes loader paths and the legacy compatibility handler. Its full suite has not been rerun during this correction. No live-site tests, deployment or activation were performed.
