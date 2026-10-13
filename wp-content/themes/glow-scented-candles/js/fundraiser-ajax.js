/*
 * fundraiser-ajax.js
 *
 * The original old-theme file was not carried into the migration. Its only job
 * was to POST the chosen fundraiser to `save_fundraiser_session` (localized as
 * `fundraiser_ajax_obj`). That flow is now handled inline by the catalog
 * templates (template-summercatalog.php et al.), so this file is a safe no-op
 * that simply prevents a 404 for the still-registered `fundraiser-ajax` handle.
 *
 * If a page ever needs the standalone popup save, implement it here using
 * fundraiser_ajax_obj.ajax_url + action=save_fundraiser_session.
 */
(function () {
  'use strict';
  // Intentionally empty — session saving is handled by the catalog templates.
})();
