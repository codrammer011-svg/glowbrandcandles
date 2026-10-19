# Refresh recent production orders locally

Start the Glow site in Local, then run PowerShell from the site folder. This script reads a **new production SQL export**; it does not connect to or modify the live server.

Preview:

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File .\scripts\sync-recent-production.ps1 -SqlDump "C:\Users\SAAD\Downloads\production.sql"
```

Apply the last seven days:

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File .\scripts\sync-recent-production.ps1 -SqlDump "C:\Users\SAAD\Downloads\production.sql" -Days 7 -Apply
```

- Target defaults to the current `latest` local database. Override with `-Database` if needed; `wp-config.php` is not switched automatically.
- Selects orders created **or modified** in the last seven days relative to the computer's current clock. Includes their refunds/parents, line items, metadata, notes and WooCommerce lookup data. Older untouched orders remain. This is an update/import, not deletion reconciliation: records absent from the export are not automatically removed.
- Refreshes source users/usermeta (customer, fundraiser, seller and representative accounts) and all shared business-context posts, seller tables, payment-token/subscription tables and customer lookup data. These shared tables are full snapshots, **not restricted to seven days**. Local-only users are retained unless an ID/login collision is detected, which stops the import.
- Preserves local products, variations, pages, Elementor templates, registration forms, options and protected metadata. Verifies fingerprints before commit; rolls back on errors. Maps historical product IDs to the updated local catalog by SKU/slug; unmatched products keep order line details with product reference zero.
- Reuses the production-to-local mapping from the initial import and original-order-ID metadata. The target should already be a prepared local mirror; this is not a replacement for the initial full migration.
- Uses direct SQL, bypassing email/payment hooks. Installs a local-only mail/cron/async-worker guard on apply. No images or live credentials/settings are imported. Profile image links and URLs inside business metadata are not rewritten by this incremental script.
- Supports the supplied `wp_` phpMyAdmin/mysqldump format and classic WooCommerce order storage; refuses an HPOS-enabled export. Keep the export trusted and private. Other table prefixes or export formats need adaptation.
- A full backup is saved outside the web root under `%TEMP%\glow-recent-sync-*\local-before.sql` before apply. Reports and staging data are retained there/on local MySQL for review. They contain private data; do not commit or upload them.
- Importing the same snapshot again updates selected records rather than duplicating orders. Internal line-item/comment IDs can change. No historical credits are recalculated and no order is placed or paid.

The existing `sync-live-to-local.ps1` performs a different, full database replacement and is **not** the command for preserving the current local pages/catalog.
