<?php
/**
 * Plugin Name: GLOW Dashboard Fixes
 * Description: Fixes for the chairperson fundraiser dashboard (seller registration link, hidden seller list, goal display, table and section styling). Deactivate to undo.
 * Version: 1.7.8
 * Author: Glow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* 1. Dashboard config: seller registration page is /register-as-a-seller/, not /seller-register/. */
add_filter( 'glow_fd_config', function ( $config ) {
	$config['urls']['seller_register'] = '/register-as-a-seller/';
	/* Total Raised and the progress ring include brochure profit, not just online profit. */
	$config['progress_includes_brochure'] = true;
	return $config;
} );

/* 2. Old links and QR codes that still point to /seller-register/ → real page, keep ?frid=. */
add_action( 'template_redirect', function () {
	$path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
	if ( 'seller-register' !== $path ) {
		return;
	}
	$target = home_url( '/register-as-a-seller/' );
	if ( ! empty( $_GET['frid'] ) ) {
		$target = add_query_arg( 'frid', absint( $_GET['frid'] ), $target );
	}
	wp_safe_redirect( $target, 301 );
	exit;
}, 1 );

/* 2a. Seller pages the seller dashboard links to (Edit My Info, Customize My Store, My Sales Report). */
require_once plugin_dir_path( __FILE__ ) . 'templates/seller-pages.php';
require_once plugin_dir_path( __FILE__ ) . 'templates/chair-pages.php';
require_once plugin_dir_path( __FILE__ ) . 'templates/chair-tools.php';
require_once plugin_dir_path( __FILE__ ) . 'templates/brochure-ui.php';
require_once plugin_dir_path( __FILE__ ) . 'templates/chair-tweaks.php';
require_once plugin_dir_path( __FILE__ ) . 'templates/seller-tweaks.php';

/* 2b. Fall/Winter brochure pages: use the rebuilt template (summer-catalogue design + winter products). */
add_filter( 'template_include', function ( $template ) {
	if ( ! is_page() ) {
		return $template;
	}
	$slug = basename( (string) get_page_template_slug() );
	if ( 'template-new-wintercatalog.php' === $slug || 'template-new-wintercatalog.php' === basename( $template ) ) {
		$mine = plugin_dir_path( __FILE__ ) . 'templates/winter-catalogue.php';
		if ( is_file( $mine ) ) {
			return $mine;
		}
	}
	return $template;
}, 99 );


/* 2c. Seller Fall-Winter brochure: full store categories instead of the old SKU allow-lists. */
add_filter( 'template_include', function ( $template ) {
	if ( 'seller-fall-winter.php' === basename( (string) $template ) || ( is_page() && 'seller-fall-winter.php' === basename( (string) get_page_template_slug() ) ) ) {
		$mine = plugin_dir_path( __FILE__ ) . 'templates/seller-fall-winter.php';
		if ( is_file( $mine ) ) {
			return $mine;
		}
	}
	return $template;
}, 100 );


/* 2d. Spring/Summer brochure pages (chair + seller): plugin copies with brochure ordering + simple tour. */
add_filter( 'template_include', function ( $template ) {
	$b = basename( (string) $template );
	$slug = is_page() ? basename( (string) get_page_template_slug() ) : '';
	$map = array( 'template-summercatalog.php' => 'summer-catalogue.php' );
	foreach ( $map as $from => $to ) {
		if ( $from === $b || $from === $slug ) {
			$mine = plugin_dir_path( __FILE__ ) . 'templates/' . $to;
			if ( is_file( $mine ) ) {
				return $mine;
			}
		}
	}
	return $template;
}, 101 );

/* 3. CSS: hidden data lists, invoice button contrast, brochure-permissions card matches the other cards. */
add_action( 'wp_head', function () {
	?>
<style id="glow-dashboard-fixes">
#seller-list, #fundraiser-list { display:none !important; }
.glow-fd-table a.glow-fd-mini { color:#fff !important; text-decoration:none !important; }
.glow-fd-table a.glow-fd-mini:hover { filter:brightness(1.06); }
.glow-sbo { font-family:inherit; }
.glow-fd .glow-sbo .glow-sbo-head { padding:0 0 14px !important; }
.glow-fd .glow-sbo .glow-sbo-head h4 { display:flex !important; align-items:center; gap:11px; margin:0 !important; font-family:inherit !important; font-size:16.5px !important; font-weight:800 !important; letter-spacing:.02em !important; line-height:1.3 !important; color:#181735 !important; text-transform:uppercase !important; }
.glow-sbo-head h4 svg { flex:0 0 auto; }
.glow-row-head { font-size:12px; letter-spacing:.04em; color:#8d8aa0; }
.glow-row { font-size:14.5px; color:#181735; }
.glow-btn.is-on { background:#17b98a; }
.glow-fd-donut-in .of a { color:#ec0f63 !important; font-weight:600; text-decoration:underline; font-size:11px; }
.glow-fd-bar-goal a { color:#ec0f63 !important; font-weight:600; text-decoration:underline; }
</style>
	<?php
}, 99 );

/* 4. Small markup fixes on the dashboard. */
add_action( 'wp_footer', function () {
	?>
<script id="glow-dashboard-fixes-js">
(function () {
	var fd = document.querySelector('.glow-fd');
	if (!fd) return;

	var edit = fd.querySelector('.glow-fd-edit-link');
	var editUrl = edit ? edit.getAttribute('href') : '';

	/* No goal set: measure progress against a default $1,000 goal so sales still show as progress. */
	var DEFAULT_GOAL = 1000;
	var of = fd.querySelector('.glow-fd-donut-in .of');
	if (of && /\$0\s*goal/i.test(of.textContent.replace(/\s+/g, ' '))) {
		var amtEl = fd.querySelector('.glow-fd-raised-amt');
		var raised = amtEl ? parseFloat(amtEl.textContent.replace(/[^0-9.]/g, '')) || 0 : 0;
		var pct = Math.min(100, Math.round(raised / DEFAULT_GOAL * 100));
		var donut = fd.querySelector('.glow-fd-donut');
		if (donut) donut.style.setProperty('--pct', pct);
		var pctEl = fd.querySelector('.glow-fd-donut-in .pct');
		if (pctEl) pctEl.textContent = pct + '%';
		of.innerHTML = 'of $1,000<br>' + (editUrl ? '<a href="' + editUrl + '">set your goal</a>' : 'default goal');
		var bar = fd.querySelector('.glow-fd-bar span');
		if (bar) bar.style.width = pct + '%';
		var barGoal = fd.querySelector('.glow-fd-bar-goal');
		if (barGoal) barGoal.innerHTML = '$1,000 default &middot; ' + (editUrl ? '<a href="' + editUrl + '">Set your goal</a>' : '');
	}

	/* Order table: empty Seller cell → dash, like the other empty cells. */
	fd.querySelectorAll('#glow-fd-orders tbody tr').forEach(function (tr) {
		var td = tr.children[1];
		if (td && !td.textContent.trim()) td.innerHTML = '&mdash;';
	});

	/* Seller permissions card: anchor for the "Registered Sellers" tile, clearer title, icon like other cards. */
	var sbo = fd.querySelector('.glow-sbo') || document.querySelector('.glow-sbo');
	if (sbo) {
		sbo.id = 'glow-fd-sellers-card';
		var h = sbo.querySelector('.glow-sbo-head h4');
		if (h) {
			h.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="#6236e0" aria-hidden="true"><circle cx="9" cy="8" r="3.6"/><path d="M2.5 19.5c1.1-3.6 3.5-5.2 6.5-5.2s5.4 1.6 6.5 5.2H2.5Z"/><circle cx="17" cy="9" r="2.7"/><path d="M15.6 14.6c2.8.2 4.8 1.6 5.9 4.9h-4.2c-.3-2-.9-3.6-1.7-4.9Z"/></svg>Registered Sellers &amp; Brochure Access';
		}
	}
})();
</script>
	<?php
}, 99 );

/* 9. Admin-only diagnostics: /fundraiser-dashboard/?frid=X&glow_fd_debug=1 */
add_action( 'template_redirect', function () {
	if ( empty( $_GET['glow_fd_debug'] ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$fid = absint( $_GET['frid'] ?? 0 );
	header( 'Content-Type: text/plain; charset=utf-8' );
	echo "fid=$fid\n";
	foreach ( array( 'user_action_status', 'user_close_out_date_inactive', 'user_registration_start_date', 'user_registration_end_date', 'user_registration_fr_goal', 'user_registration_name_of_school_business' ) as $k ) {
		echo "$k = " . var_export( get_user_meta( $fid, $k, true ), true ) . "\n";
	}
	$co = get_user_meta( $fid, 'user_close_out_date_inactive', true );
	echo "strtotime(closeout) = " . var_export( $co ? date( 'Y-m-d H:i:s', strtotime( $co ) ) : null, true ) . "\n";
	$orders = wc_get_orders( array( 'limit' => -1, 'status' => array( 'processing', 'completed', 'on-hold', 'waiting-call' ), 'meta_key' => 'fundraiser_field', 'meta_value' => $fid, 'orderby' => 'date', 'order' => 'DESC' ) );
	echo "wc_get_orders(meta_key) count = " . count( $orders ) . "\n";
	$orders2 = wc_get_orders( array( 'limit' => -1, 'status' => array( 'processing', 'completed', 'on-hold', 'waiting-call' ), 'meta_query' => array( array( 'key' => 'fundraiser_field', 'value' => $fid ) ) ) );
	echo "wc_get_orders(meta_query) count = " . count( $orders2 ) . "\n";
	echo "HPOS enabled = " . var_export( class_exists( '\\Automattic\\WooCommerce\\Utilities\\OrderUtil' ) && \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled(), true ) . "\n";
	echo "HPOS sync = " . var_export( get_option( 'woocommerce_custom_orders_table_data_sync_enabled' ), true ) . "\n";
	foreach ( array_slice( $orders2, 0, 8 ) as $o ) {
		echo sprintf( "#%d %s %s type=%s seller=%s fund=%s total=%s\n", $o->get_id(), $o->get_status(), $o->get_date_created() ? $o->get_date_created()->date( 'Y-m-d' ) : '-', $o->get_meta( 'wc_order_type' ), $o->get_meta( 'wc_seller_field' ), $o->get_meta( 'fundraiser_field' ), $o->get_total() );
	}
	exit;
}, 0 );

/* 10. Close-out dates were saved like "2026-05-18 00:58:52 am", which strtotime() cannot read
 *     (it returns false → 1970), so EVERY order looked like it was placed after close-out and
 *     closed fundraisers showed $0. Normalise the value on read. */
add_filter( 'get_user_metadata', function ( $value, $object_id, $meta_key, $single ) {
	if ( 'user_close_out_date_inactive' !== $meta_key ) {
		return $value;
	}
	static $busy = false;
	if ( $busy ) {
		return $value;
	}
	$busy = true;
	$raw  = get_user_meta( $object_id, $meta_key, true );
	$busy = false;
	if ( ! is_string( $raw ) || '' === $raw ) {
		return $value;
	}
	$fixed = $raw;
	if ( preg_match( '/^(\d{4}-\d{2}-\d{2}) (\d{1,2}):(\d{2}):(\d{2})\s*(am|pm)$/i', $raw, $m ) ) {
		$h = (int) $m[2];
		if ( 0 === $h || $h > 12 ) {
			$fixed = sprintf( '%s %02d:%s:%s', $m[1], $h, $m[3], $m[4] ); // already 24h, drop am/pm
		}
	}
	if ( false === strtotime( $fixed ) ) {
		return $value;
	}
	/* The value is site-local time but the dashboard compares it with UTC order
	 * timestamps via strtotime() (UTC). Express it in UTC so the comparison is right. */
	$dt = date_create( $fixed, wp_timezone() );
	if ( $dt ) {
		$dt->setTimezone( new DateTimeZone( 'UTC' ) );
		$fixed = $dt->format( 'Y-m-d H:i:s' );
	}
	return array( $fixed );
}, 10, 4 );

/* 11. Admin-only tool: link old sellers to their fundraiser from their orders.
 *     /fundraiser-dashboard/?glow_fd_backfill_sellers=1          → dry run (report only)
 *     /fundraiser-dashboard/?glow_fd_backfill_sellers=1&apply=1  → write user meta */
add_action( 'template_redirect', function () {
	if ( empty( $_GET['glow_fd_backfill_sellers'] ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$apply = ! empty( $_GET['apply'] );
	header( 'Content-Type: text/plain; charset=utf-8' );
	echo $apply ? "APPLYING\n" : "DRY RUN (add &apply=1 to write)\n";
	$sellers = get_users( array( 'role' => 'seller', 'fields' => array( 'ID', 'display_name' ), 'number' => -1 ) );
	$missing = 0; $fixed = 0; $nodata = 0;
	foreach ( $sellers as $s ) {
		$cur = get_user_meta( $s->ID, 'user_registration_seller_fundraiser', true );
		if ( '' !== (string) $cur ) {
			continue;
		}
		$missing++;
		$orders = wc_get_orders( array( 'limit' => 1, 'orderby' => 'date', 'order' => 'DESC', 'meta_key' => 'wc_seller_field', 'meta_value' => (string) $s->ID ) );
		if ( empty( $orders ) ) {
			$nodata++;
			continue;
		}
		$fid = (string) $orders[0]->get_meta( 'fundraiser_field' );
		if ( '' === $fid ) {
			$nodata++;
			continue;
		}
		$chair = get_userdata( (int) $fid );
		printf( "seller #%d %s -> fundraiser #%s (%s), last order #%d %s\n", $s->ID, $s->display_name, $fid, $chair ? $chair->display_name : '?', $orders[0]->get_id(), $orders[0]->get_date_created()->date( 'Y-m-d' ) );
		if ( $apply ) {
			update_user_meta( $s->ID, 'user_registration_seller_fundraiser', $fid );
			update_user_meta( $s->ID, 'seller_fundraiser', $fid );
			update_user_meta( $s->ID, 'fundraiser_id', $fid );
			$fixed++;
		}
	}
	printf( "\nsellers: %d total, %d without a fundraiser, %d with no orders to learn from, %d %s\n", count( $sellers ), $missing, $nodata, $apply ? $fixed : ( $missing - $nodata ), $apply ? 'updated' : 'would be linked' );
	exit;
}, 0 );

/* 12. Seller brochure orders moved to the chairperson's cart ("Send to Cart") are added with
 *     restore_saved_order=Yes, which skips the code that marks the session as a brochure
 *     ("Catlog") order. If the session was already "Online", checkout then showed NO
 *     Fundraiser Discount and the order would not be typed as a brochure order.
 *     Mark the session (and the seller cookie the legacy code reads) whenever such an item
 *     is added by a fundraiser. */
add_filter( 'woocommerce_add_cart_item_data', function ( $cart_item_data, $product_id ) {
	if ( empty( $cart_item_data['glow_seller_order_id'] ) ) {
		return $cart_item_data;
	}
	$user = wp_get_current_user();
	if ( ! $user || ! in_array( 'fundraiser', (array) $user->roles, true ) ) {
		return $cart_item_data;
	}
	if ( PHP_SESSION_ACTIVE !== session_status() && ! headers_sent() ) {
		session_start();
	}
	$_SESSION['sellername'] = 'Catlog';
	$seller = ! empty( $cart_item_data['wdm_seller_name'] ) ? (string) $cart_item_data['wdm_seller_name'] : 'Catalog';
	if ( ! headers_sent() ) {
		setcookie( 'massSellerName', rawurlencode( $seller ), time() + 30 * DAY_IN_SECONDS, '/' );
		setcookie( 'massSellerDisplay', rawurlencode( $seller ), time() + 30 * DAY_IN_SECONDS, '/' );
	}
	$_COOKIE['massSellerName']    = $seller;
	$_COOKIE['massSellerDisplay'] = $seller;
	return $cart_item_data;
}, 11, 2 );

/* 12b. While a fundraiser's cart contains seller brochure items, keep the session typed as
 *      "Catlog" on every page load, so the discount survives later browsing. */
add_action( 'template_redirect', function () {
	if ( ! function_exists( 'WC' ) || ! WC()->cart || ! is_user_logged_in() ) {
		return;
	}
	$user = wp_get_current_user();
	if ( ! in_array( 'fundraiser', (array) $user->roles, true ) ) {
		return;
	}
	foreach ( WC()->cart->get_cart() as $item ) {
		if ( ! empty( $item['glow_seller_order_id'] ) ) {
			if ( PHP_SESSION_ACTIVE !== session_status() && ! headers_sent() ) {
				session_start();
			}
			$_SESSION['sellername'] = 'Catlog';
			return;
		}
	}
}, 1 );

/* 13. "ON FOR ALL" should mean all: when the chairperson turns the master switch ON, clear any
 *     seller's individual BLOCKED setting so every seller follows the master switch again.
 *     (Runs just before the dashboard's own toggle handler, which then flips the master value.) */
add_action( 'template_redirect', function () {
	if ( ! isset( $_GET['glow_master'], $_GET['_wpnonce'] ) ) {
		return;
	}
	$fid = absint( $_GET['glow_master'] );
	if ( ! $fid || ! wp_verify_nonce( $_GET['_wpnonce'], 'glow_master_' . $fid ) ) {
		return;
	}
	if ( $fid !== get_current_user_id() && ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$val = get_user_meta( $fid, 'allow_seller_orders', true );
	$currently_on = is_array( $val ) ? ! empty( $val ) : in_array( $val, array( 'yes', '1', 1 ), true );
	if ( $currently_on ) {
		return; // being switched OFF — leave individual settings alone
	}
	$sellers = get_users( array( 'meta_key' => 'user_registration_seller_fundraiser', 'meta_value' => $fid, 'fields' => 'ID', 'number' => -1 ) );
	foreach ( $sellers as $sid ) {
		if ( 'no' === get_user_meta( $sid, 'glow_allow_brochure_order', true ) ) {
			delete_user_meta( $sid, 'glow_allow_brochure_order' );
		}
	}
}, 2 );

/* 14. Older fundraisers have no group name / dates / goal / participants on the USER account
 *     (the dashboards, seller store pages and share texts read those user-meta keys), but the
 *     data exists on the linked "fundraiser" post (ACF). Fall back to the post when the user
 *     meta is empty. Fixes "--" group name, "January 01 - January 01" dates and "$0 goal". */
function glow_fix_fundraiser_post_id( $uid ) {
	static $cache = array();
	if ( isset( $cache[ $uid ] ) ) {
		return $cache[ $uid ];
	}
	$pid = (int) get_user_meta( $uid, 'fundraiser_post_id', true );
	if ( ! $pid || 'fundraiser' !== get_post_type( $pid ) ) {
		$posts = get_posts( array(
			'post_type'   => 'fundraiser',
			'post_status' => 'any',
			'meta_key'    => 'fundraiser_id',
			'meta_value'  => $uid,
			'numberposts' => 1,
			'orderby'     => 'date',
			'order'       => 'DESC',
			'fields'      => 'ids',
		) );
		$pid = $posts ? (int) $posts[0] : 0;
	}
	return $cache[ $uid ] = $pid;
}

add_filter( 'get_user_metadata', function ( $value, $object_id, $meta_key, $single ) {
	static $map = array(
		'user_registration_name_of_school_business' => 'organizations_name',
		'user_registration_start_date'              => 'start_date',
		'user_registration_end_date'                => 'end_date',
		'user_registration_fr_goal'                 => 'fundraising_goal',
		'user_registration_participants_no'         => 'number_of_participants',
	);
	if ( ! isset( $map[ $meta_key ] ) ) {
		return $value;
	}
	static $busy = false;
	if ( $busy ) {
		return $value;
	}
	$busy = true;
	$raw  = get_user_meta( $object_id, $meta_key, true );
	$busy = false;
	if ( '' !== trim( (string) $raw ) ) {
		return $value;
	}
	$pid = glow_fix_fundraiser_post_id( $object_id );
	if ( ! $pid ) {
		return $value;
	}
	$v = trim( (string) get_post_meta( $pid, $map[ $meta_key ], true ) );
	if ( '' === $v ) {
		return $value;
	}
	if ( in_array( $meta_key, array( 'user_registration_start_date', 'user_registration_end_date' ), true ) ) {
		$ts = preg_match( '/^\d{8}$/', $v ) ? strtotime( substr( $v, 0, 4 ) . '-' . substr( $v, 4, 2 ) . '-' . substr( $v, 6, 2 ) ) : strtotime( $v );
		if ( ! $ts ) {
			return $value;
		}
		$v = date( 'Y-m-d', $ts );
	} elseif ( 'user_registration_fr_goal' === $meta_key ) {
		$n = (float) preg_replace( '/[^0-9.]/', '', $v );
		if ( $n < 100 ) {
			return $value; // free-text field; ignore junk like "4"
		}
		$v = (string) $n;
	}
	return array( $v );
}, 10, 4 );

/* 15. Public store pages (/seller/user/..., /seller/ID, /profile/ID): when the fundraiser has no
 *     group name the template prints "--", and with no dates it prints "January 01 - January 01".
 *     Show the chairperson's name as the group instead, and hide the empty date line. */
add_action( 'wp_footer', function () {
	$seller_q = get_query_var( 'user' ) ? get_query_var( 'user' ) : get_query_var( 'seller' );
	$prof_q   = get_query_var( 'profile' );
	if ( ! $seller_q && ! $prof_q ) {
		return;
	}
	$fid = 0;
	if ( $seller_q ) {
		$u = is_numeric( $seller_q ) ? get_user_by( 'id', (int) $seller_q ) : get_user_by( 'login', $seller_q );
		if ( $u ) {
			$fid = (int) get_user_meta( $u->ID, 'user_registration_seller_fundraiser', true );
		}
	} elseif ( is_numeric( $prof_q ) ) {
		$fid = (int) $prof_q;
	}
	$label = '';
	if ( $fid ) {
		$org = trim( (string) get_user_meta( $fid, 'user_registration_name_of_school_business', true ) );
		if ( '' === $org ) {
			$chair = trim( get_user_meta( $fid, 'first_name', true ) . ' ' . get_user_meta( $fid, 'last_name', true ) );
			if ( '' === $chair ) {
				$cu = get_userdata( $fid );
				$chair = $cu ? $cu->display_name : '';
			}
			$label = $chair ? $chair . "'s Fundraiser" : 'GLOW Fundraiser';
		}
	}
	?>
	<script id="glow-store-page-fix">
	(function(){
		var label = <?php echo wp_json_encode( $label ); ?>;
		document.querySelectorAll('div, span, h1, h2, h3, h4, p').forEach(function(el){
			if (el.children.length) return;
			var t = el.textContent.trim();
			if (t === '--' && label) { el.textContent = label; }
			if (/^January 01\s*-\s*January 01$/.test(t)) { el.style.display = 'none'; }
		});
	})();
	</script>
	<?php
}, 99 );
