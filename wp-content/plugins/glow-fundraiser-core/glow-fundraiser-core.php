<?php
/**
 * Plugin Name: Glow Fundraiser Core
 * Description: All fundraiser & admin business logic (custom post types, admin panels, AJAX handlers, email automation, rebooking, DOCX generation, shortcodes) extracted from the theme so it runs independently of the active theme. Elementor-ready.
 * Version: 1.0.0
 * Author: Glow
 * Text Domain: glow-fundraiser-core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Declare WooCommerce HPOS compatibility
 */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

define( 'GLOW_FC_DIR', plugin_dir_path( __FILE__ ) );
define( 'GLOW_FC_URL', plugin_dir_url( __FILE__ ) );

/**
 * Custom post types register on 'init', so load them immediately.
 */
require_once GLOW_FC_DIR . 'includes/post-types.php';

/**
 * Admin: Import / Export tool for the fundraiser post types.
 */
require_once GLOW_FC_DIR . 'includes/admin/import-export.php';

/**
 * Front-end: [glow_fundraiser_shop] QR-landing selector
 * (Fundraiser ID -> registered sellers dropdown -> shop).
 */
require_once GLOW_FC_DIR . 'includes/shortcode/fundraiser-shop-selector.php';

/**
 * Image helpers (profile_image_resize_crop) for dashboard & seller templates.
 */
require_once GLOW_FC_DIR . 'includes/helpers/image-helpers.php';

/**
 * Register the bundled legacy page templates in the page "Template" dropdown
 * (they live in the plugin, not the active theme, so WP won't list them otherwise).
 */
require_once GLOW_FC_DIR . 'includes/legacy-page-templates.php';

/**
 * Compatibility shims for Royal-theme helpers (et_page_config) used by the bundled
 * legacy templates — without them those templates fatal under Hello Elementor.
 */
require_once GLOW_FC_DIR . 'includes/compat/royal-shims.php';

/**
 * Free shipping on catalog (brochure) orders over $99 (Fundraiser Dashboard flow).
 */
require_once GLOW_FC_DIR . 'includes/shipping/catalog-free-shipping.php';

/**
 * Recompute the online-store profit deduction on checkout via a hook (was in the
 * "Custom Checkout Page" page template, which no longer runs under Elementor).
 */
require_once GLOW_FC_DIR . 'includes/checkout/online-profit-deduction.php';

/**
 * Role-based login redirect (each role → its own dashboard) + a guard that keeps
 * non-fundraisers off the fundraiser dashboard.
 */
require_once GLOW_FC_DIR . 'includes/routing/login-redirect.php';

/**
 * Load the fundraiser/admin modules on 'after_setup_theme'.
 *
 * Why not at plugin-load time? During this transition, some of these modules
 * may still reference helper functions that live in the theme's functions.php.
 * The theme's functions.php is loaded BEFORE the after_setup_theme action fires,
 * so loading here guarantees those helpers already exist. Once the inline theme
 * logic is migrated too (Stage 2), this can move to plugins_loaded.
 */
add_action( 'after_setup_theme', function () {

	$modules = array(
		'includes/inc/fundraiser-docx.php',
		'includes/shortcode/shortcode.php',
		'includes/shortcode/shortcode-new.php',
		'includes/admin-panel/fundraiser_operation.php',
		'includes/rebooking/rebooking-post-ajax.php',
		'includes/rebooking/rebookbutton_shortcode.php',
		'includes/admin-panel/send_check_orders.php',
		'includes/admin-panel/search_fundrasier_ajax.php',
		'includes/email/fundraiser_email_automation.php',
		'includes/admin-panel/fundraiser_email_tracking.php',
		'includes/admin-panel/fundrasier-booking-detail.php',
		'includes/admin-panel/theme_fundraiser_feedbacks.php',
		'includes/admin-panel/wp_paid_fundraiser_ajax_record.php',
		'includes/admin-panel/wp_unpaid_fundraiser_ajax_record.php',
		'includes/admin-panel/wp_send_check_order_ajax_record.php',
		'includes/admin-panel/wp_outstanding_fund_ajax.php',
		'includes/admin-panel/fundraiser-outstanding.php',
		'includes/admin-panel/ajax_send_check_received_email.php',
		'includes/admin-panel/create_merge_order_by_cron_job.php',
		'includes/representative-admin-panel/representative-operation.php',
		'includes/admin-panel/admin-menus.php',
		'includes/admin-panel/seller-register-ajax.php',
		'includes/admin-panel/operations-ajax.php',
		'includes/routing/profile-routes.php',
	);

	foreach ( $modules as $module ) {
		$path = GLOW_FC_DIR . $module;
		if ( is_file( $path ) ) {
			require_once $path;
		}
	}

	/**
	 * Legacy bridge: the remaining inline fundraiser logic (shortcodes, AJAX
	 * handlers, helpers) still lives in the old theme's functions.php. While the
	 * Royal theme is active, that file already loads it — loading our copy too
	 * would fatal (redeclared functions). So we load the bundled copy ONLY when
	 * the Royal theme is NOT the active theme (i.e. after switching to Hello
	 * Elementor), so nothing is lost in the theme switch.
	 */
	// Match the OLD Royal theme by its EXACT slug (folder name), not a loose
	// substring. The new Elementor child theme is named "glow-scented-candles",
	// which previously tripped the substring check and wrongly disabled the
	// legacy bridge on the new build.
	$slugs = array( strtolower( (string) get_template() ), strtolower( (string) get_stylesheet() ) );
	$royal_slugs = array( 'glowscentedcandles', 'royal' );
	$is_royal_active = (bool) array_intersect( $slugs, $royal_slugs );
	if ( ! $is_royal_active ) {
		$legacy = GLOW_FC_DIR . 'legacy/theme-functions.php';
		if ( is_file( $legacy ) ) {
			require_once $legacy;
		}
	}
}, 5 );

/**
 * PHP 8.1+ Compatibility: ensure format_to_edit receives a string, not null.
 */
add_filter( 'format_to_edit', function( $content ) {
	return is_null( $content ) ? '' : (string) $content;
}, 1 );

/**
 * Flush rewrite rules on activation so CPT archives resolve immediately.
 */
register_activation_hook( __FILE__, function () {
	require_once GLOW_FC_DIR . 'includes/post-types.php';
	foreach ( array( 'glow_register_fundraiser_cpt', 'glow_register_feedback_cpt', 'glow_register_close_out_cpt', 'glow_register_payment_history_cpt' ) as $fn ) {
		if ( function_exists( $fn ) ) {
			call_user_func( $fn );
		}
	}
	flush_rewrite_rules();
} );

register_deactivation_hook( __FILE__, function () {
	flush_rewrite_rules();
} );
