# Glow Brand Candles

Custom WordPress application source for Glow Brand Candles (live site: `glowscentedcandles.com`).

This repository holds **only the custom Glow code**: the Glow themes, the Glow plugins and the Glow MU plugins. It is not a full site backup. To run the site you also need four things that are **not in Git** and are provided by the repository owner:

| Not in Git | Provided by | Goes to |
|---|---|---|
| WordPress core | Download from wordpress.org (or Local) | site root |
| Third-party plugins (WooCommerce, Elementor Pro, User Registration Pro, …) | Repository owner (zip) | `wp-content/plugins/` |
| Uploads (product images, PDFs, fonts) | Repository owner (zip) | `wp-content/uploads/` |
| Database | Repository owner (`.sql` export) | MySQL |

Never commit any of these. They are excluded in `.gitignore` together with `wp-config.php`, `.env` files, SQL dumps, logs, archives and backups.

---

## What is in this repository

```
.gitignore
README.md
scripts/                                   Local sync and maintenance helpers
wp-content/
  themes/
    glow-scented-candles/                  Active theme (templates, print/report pages, dashboards)
    hello-elementor/                       Elementor base theme
  plugins/
    glow-fundraiser-core/                  Fundraiser core logic and legacy templates
    Fundraiser-Modules/                    Fundraiser/seller modules
    glow-dashboard-fixes/                  Dashboard fixes
    glow-seller-email-resend/              Seller email resend tool
    quick-orders-for-woocommerces/         Customised quick/group order plugin
    shiptoschool-shipping/                 Ship-to-school shipping method
  mu-plugins/
    glow-*.php                             Glow MU plugins (always loaded)
```

The `.gitignore` uses an allow-list: everything is ignored except the paths above. A **new** custom plugin or theme will not be tracked until you add a matching `!/wp-content/...` line to `.gitignore`.

---

## Requirements

- PHP 8.1 or newer (8.2 recommended, to match the live server). Some third-party plugins need PHP 8.3; use 8.3 if a plugin reports a Composer platform error.
- MySQL 8 / MariaDB 10.6 or newer
- WordPress 6.x or newer (the current local install runs 7.1)
- Git
- Recommended on Windows: [Local](https://localwp.com/) to run the site, and [WP-CLI](https://wp-cli.org/)

---

## Local setup step by step

### 1. Get the files from the repository owner

Ask the repository owner for:

1. **`plugins.zip`**: all third-party plugins from `wp-content/plugins/`
2. **`uploads.zip`**: `wp-content/uploads/` (can be a recent subset if the full folder is too large)
3. **`database.sql`**: a database export (table prefix `wp_`)
4. The **local site URL** the database export was prepared for, if it was already converted

Keep these files outside the project folder and never commit them.

### 2. Create an empty WordPress site

Using Local:

1. Create a new site, for example `glow`, with the domain `glow.local`.
2. Choose PHP 8.2 or newer, MySQL 8 and nginx or Apache.
3. Note the database name, user, password and port under **Database** in Local.

Without Local, any WordPress install works (XAMPP, Laragon, Docker). The site root is the folder that contains `wp-config.php`.

### 3. Pull the code from GitHub into the site root

The repository root **is** the WordPress root (`public/`). Clone it into the existing WordPress folder:

```bash
cd "path/to/your-site/app/public"
git init
git remote add origin https://github.com/codrammer011-svg/glowbrandcandles.git
git fetch origin
git checkout -f -B main origin/main
```

`checkout -f` writes only the tracked files (themes, Glow plugins, MU plugins, scripts). It does not touch WordPress core, `wp-config.php` or untracked plugins.

> Cloning into an empty folder (`git clone ... public`) also works, but then you must copy WordPress core and `wp-config.php` in yourself.

### 4. Add the third-party plugins

Extract `plugins.zip` into `wp-content/plugins/`. **Do not overwrite** the Glow plugin folders that came from Git:

- `glow-fundraiser-core`
- `Fundraiser-Modules`
- `glow-dashboard-fixes`
- `glow-seller-email-resend`
- `quick-orders-for-woocommerces`
- `shiptoschool-shipping`

If the zip contains these folders, skip them when extracting. The Git version is the source of truth.

The site currently uses these third-party plugins, among others: WooCommerce, Elementor, Elementor Pro, User Registration Pro (+ its WooCommerce, file-upload, advanced-fields, conditional-logic and profile-connect add-ons), PDF Invoices & Packing Slips, WooCommerce Stripe / Payments / Authorize.net, WooCommerce Shipping and ShipStation, WooLentor (+ Pro), Element Pack, Advanced Custom Fields, Contact Form 7, WP Mail SMTP (+ Pro), Yoast SEO, User Role Editor, HM Multiple Roles and Facebook for WooCommerce.

### 5. Add the uploads

Extract `uploads.zip` into `wp-content/uploads/`. Product images, the PDF invoice fonts and the profile artwork are loaded from here. Missing files show up as broken images but do not break the site.

### 6. Import the database

With WP-CLI from the site root (Local: right-click the site → **Open site shell**):

```bash
wp db reset --yes          # empties the local database created by Local
wp db import path/to/database.sql
```

Or import `database.sql` with Adminer/phpMyAdmin (Local → Database → Open Adminer).

In `wp-config.php`, keep Local's own database credentials and make sure that:

```php
$table_prefix = 'wp_';
```

### 7. Point the database to your local URL

The live database contains `https://glowscentedcandles.com` and `https://glowbrandscandles.com`. Replace them with your local URL:

```bash
wp search-replace 'https://glowscentedcandles.com' 'http://glow.local' --all-tables --precise
wp search-replace 'https://glowbrandscandles.com'  'http://glow.local' --all-tables --precise
wp cache flush
```

Skip this step if the repository owner says the export is already converted to your local URL.

Most theme and email links are built at runtime by `mu-plugins/glow-dynamic-site-urls.php`, so links in the code follow whatever `home_url()` is.

### 8. Block emails and cron locally (required)

The database contains real customers, sellers and fundraisers. **A local copy must never send email or run payment/order cron jobs.** Create this file. It is local-only and intentionally not in Git:

`wp-content/mu-plugins/000-glow-local-mail-guard.php`

```php
<?php
defined('ABSPATH') || exit;
if (!defined('GLOW_EMAIL_WORKER_DISABLED')) define('GLOW_EMAIL_WORKER_DISABLED', true);
if (!defined('DISABLE_WP_CRON')) define('DISABLE_WP_CRON', true);
add_filter('pre_wp_mail', '__return_false', PHP_INT_MAX);
add_filter('action_scheduler_allow_async_request_runner', '__return_false', PHP_INT_MAX);
```

Also, in **WooCommerce → Settings → Payments**, make sure Stripe/Authorize.net are in **test mode** or disabled locally.

### 9. Finish and check

```bash
wp rewrite flush
wp theme list            # glow-scented-candles should be active
wp plugin list --status=inactive
```

Then:

1. Log in at `http://glow.local/wp-admin` with an admin account. Ask the repository owner for one, or reset a password with `wp user update <id> --user_pass=...`.
2. Open **Settings → Permalinks** and click **Save** once.
3. Check the homepage, the shop, a fundraiser dashboard and a print page (`/print/?fund_id=<id>`).

If plugins show PHP errors on activation, check the PHP version first (step "Requirements").

---

## Keeping a local copy up to date

**Code:**

```bash
git checkout main
git pull --ff-only origin main
```

**Data:** ask the repository owner for a fresh database export and repeat steps 6–8. Developers with SSH access to the live server can use the sync scripts in [`scripts/`](scripts/README.md) instead. They export live data read-only and convert URLs to `http://glow.local`.

---

## Working on changes

1. Branch from `main`: `git checkout -b fix/short-description`
2. Change only tracked Glow code (themes, Glow plugins, `mu-plugins/glow-*.php`, `scripts/`).
3. Check the syntax of changed PHP files: `php -l path/to/file.php`
4. Test locally, with emails blocked (step 8).
5. Commit, push the branch and open a pull request into `main`.

Do not commit: `wp-config.php`, database dumps, uploads, third-party plugins, credentials, `.env` files, logs, test screenshots or backup copies (`*.bak`, `*-backup*`).

---

## Deploying to live

Live runs on Hostinger. Only the repository owner, or someone they authorise, deploys.

1. Merge the reviewed branch into `main`.
2. Before deploying, compare the live files with `main`. Other developers sometimes edit live directly, and those changes must be kept.
3. Back up the files you will replace, and any database settings you change.
4. Deploy only the changed files (or `git pull --ff-only origin main` where the server has a Git checkout).
5. Purge the LiteSpeed cache, then test the changed pages on the live site.

Never import a local database into live, and never run local scripts against the live database.
