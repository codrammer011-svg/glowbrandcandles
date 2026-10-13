<?php
/**
 * Template Name: GLOW Seller Dashboard
 *
 * SINGLE FILE — data layer + markup together.
 * PART 1 (data layer) is unchanged apart from the checklist display labels,
 * which now read exactly as the approved mockup. PART 2 (render + CSS) is
 * rebuilt to match that mockup.
 *
 * Still needed elsewhere (leave them where they already are):
 *   - glow_so_get_seller_orders() / glow_so_get_items() / glow_so_catalog_label()
 *   - glow_so_get_seller_wc_orders()
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* =========================================================================
   PART 1 — DATA LAYER
   ========================================================================= */

/* -------------------------------------------------------------------------
   Brochure permission switches. Guarded — a functions.php copy wins.
   ------------------------------------------------------------------------- */

if ( ! function_exists( 'glow_master_allows' ) ) :
function glow_master_allows( $fid ) {
	if ( ! $fid ) return false;
	$val = get_user_meta( $fid, 'allow_seller_orders', true );
	if ( is_array( $val ) ) return ! empty( $val );
	return in_array( $val, array( 'yes', '1', 1 ), true );
}
endif;

if ( ! function_exists( 'glow_seller_allows' ) ) :
function glow_seller_allows( $sid, $fid ) {
	$val = get_user_meta( $sid, 'glow_allow_brochure_order', true );
	if ( '' === $val ) return glow_master_allows( $fid );
	return 'yes' === $val;
}
endif;

if ( ! function_exists( 'glow_sd_config' ) ) :
function glow_sd_config() {

	$config = array(

		'meta' => array(
			// on the SELLER
			'fundraiser_id' => 'user_registration_seller_fundraiser',
			'goal'          => 'user_registration_seller_goal',
			'photo'         => 'user_registration_image_url_profile',
			'photo_alt'     => 'seller_profile_image',
			'description'   => 'description',
			'description_2' => 'seller_profile_description',
			'qr'            => 'seller_barcode_link',

			// on the FUNDRAISER
			'org_name'      => 'user_registration_name_of_school_business',
			'start_date'    => 'user_registration_start_date',
			'end_date'      => 'user_registration_end_date',
			'action_status' => 'user_action_status',
			'closeout_date' => 'user_close_out_date_inactive',
			'fund_goal'     => 'user_registration_fr_goal',
			'participants'  => 'user_registration_participants_no',
		),

		'rebate' => array(
			'base'     => 40,
			'bulk'     => 50,
			'bulk_qty' => (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1),
		),

		'urls' => array(
			'edit_info'    => '/edit-seller-info/',
			'customize'    => '/seller-upload-image-and-description/',
			'report'       => '/seller-report/',
			'brochure_ss'  => '/seller-spring-summer/',
			'brochure_ps'  => '/seller-perfect-scents/',
			'brochure_fw'  => '/seller-fall-winter/',
			'rebook'       => '/rebook-seller/',
			'instructions' => ('' . glow_dynamic_url_base('uploads') . '2020/10/Fundraiser-Dashboard-Glow-Scented-Candles-4-copy.pdf'),
		),

		'recent_buyers'   => 5,
		'orders_in_table' => 250,
		'cache_minutes'   => 5,
	);

	return apply_filters( 'glow_sd_config', $config );
}
endif;

if ( ! function_exists( 'glow_sd_meta' ) ) :
function glow_sd_meta( $key ) {
	$c = glow_sd_config();
	return isset( $c['meta'][ $key ] ) ? $c['meta'][ $key ] : '';
}
endif;

if ( ! function_exists( 'glow_sd_url' ) ) :
function glow_sd_url( $key ) {
	$c = glow_sd_config();
	$p = isset( $c['urls'][ $key ] ) ? $c['urls'][ $key ] : '/';
	return ( 0 === strpos( $p, 'http' ) ) ? $p : home_url( $p );
}
endif;


/* =========================================================
   WHOSE DASHBOARD
   ========================================================= */

if ( ! function_exists( 'glow_sd_current_seller_id' ) ) :
function glow_sd_current_seller_id() {

	if ( ! is_user_logged_in() ) return 0;

	if ( current_user_can( 'manage_options' ) ) {
		if ( isset( $_GET['sid'] ) )  return absint( $_GET['sid'] );
		if ( isset( $_GET['frid'] ) ) return absint( $_GET['frid'] );
	}

	return get_current_user_id();
}
endif;

if ( ! function_exists( 'glow_sd_is_admin_view' ) ) :
function glow_sd_is_admin_view( $sid ) {
	return ( current_user_can( 'manage_options' ) && $sid !== get_current_user_id() );
}
endif;

if ( ! function_exists( 'glow_sd_fundraiser_id' ) ) :
function glow_sd_fundraiser_id( $sid ) {
	return absint( get_user_meta( $sid, glow_sd_meta( 'fundraiser_id' ), true ) );
}
endif;


/* =========================================================
   SELLER PROFILE
   ========================================================= */

if ( ! function_exists( 'glow_sd_seller' ) ) :
function glow_sd_seller( $sid ) {

	$first = trim( get_user_meta( $sid, 'first_name', true ) );
	$last  = trim( get_user_meta( $sid, 'last_name', true ) );
	$user  = get_userdata( $sid );

	$full = trim( $first . ' ' . $last );
	if ( '' === $full && $user ) $full = $user->display_name;

	return array(
		'first' => $first ? $first : ( $user ? $user->display_name : '' ),
		'last'  => $last,
		'full'  => $full,
		'login' => $user ? $user->user_login : '',
		// normalised exactly like the original manual-seller match
		'key'   => strtolower( preg_replace( '/^\s+|\s+$/', '', $full ) ),
	);
}
endif;

if ( ! function_exists( 'glow_sd_photo' ) ) :
function glow_sd_photo( $sid ) {
	$url = get_user_meta( $sid, glow_sd_meta( 'photo' ), true );
	if ( ! $url ) $url = get_user_meta( $sid, glow_sd_meta( 'photo_alt' ), true );
	return $url ? $url : '';
}
endif;

if ( ! function_exists( 'glow_sd_needs_profile' ) ) :
function glow_sd_needs_profile( $sid ) {
	$desc = get_user_meta( $sid, glow_sd_meta( 'description' ), true );
	if ( ! $desc ) $desc = get_user_meta( $sid, glow_sd_meta( 'description_2' ), true );
	return ( '' === glow_sd_photo( $sid ) || '' === $desc );
}
endif;

if ( ! function_exists( 'glow_sd_goal' ) ) :
function glow_sd_goal( $sid ) {
	// the stored value can carry "$" or commas — strip to a number
	$raw = get_user_meta( $sid, glow_sd_meta( 'goal' ), true );
	return (float) preg_replace( '/[^0-9.]/', '', (string) $raw );
}
endif;

/** Shop link: /seller/user/{login}, same shape the original built. */
if ( ! function_exists( 'glow_sd_shop_link' ) ) :
function glow_sd_shop_link( $sid ) {
	$s = glow_sd_seller( $sid );
	return apply_filters( 'glow_sd_shop_link', home_url( '/seller/user/' . $s['login'] ), $sid );
}
endif;

if ( ! function_exists( 'glow_sd_campaign' ) ) :
function glow_sd_campaign( $fid ) {

	$start = get_user_meta( $fid, glow_sd_meta( 'start_date' ), true );
	$end   = get_user_meta( $fid, glow_sd_meta( 'end_date' ), true );
	$stat  = get_user_meta( $fid, glow_sd_meta( 'action_status' ), true );

	$end_ts = $end ? strtotime( $end . ' 23:59:59' ) : 0;
	$days   = $end_ts ? (int) ceil( ( $end_ts - current_time( 'timestamp' ) ) / DAY_IN_SECONDS ) : 0;
	if ( $days < 0 ) $days = 0;

	$closed = ( 2 == $stat );

	return array(
		'org'       => get_user_meta( $fid, glow_sd_meta( 'org_name' ), true ),
		'chair'     => trim( get_user_meta( $fid, 'first_name', true ) . ' ' . get_user_meta( $fid, 'last_name', true ) ),
		'start_fmt' => $start ? date_i18n( 'M j', strtotime( $start ) ) : '',
		'end_fmt'   => $end   ? date_i18n( 'M j, Y', $end_ts )          : '',
		'closed'    => $closed,
		'active'    => ( ! $closed && $days > 0 ),
		'days_left' => $days,
	);
}
endif;


/* =========================================================
   STATS — two queries, one pass
   ========================================================= */

if ( ! function_exists( 'glow_sd_stats' ) ) :
function glow_sd_stats( $sid, $force = false ) {

	$config = glow_sd_config();
	$key    = 'glow_sd_stats_' . $sid;

	if ( ! $force ) {
		$cached = get_transient( $key );
		if ( is_array( $cached ) && ( $cached['_profit_min_items'] ?? 0 ) === GLOW_BROCHURE_PROFIT_MIN_ITEMS ) return $cached;
	}

	$fid    = glow_sd_fundraiser_id( $sid );
	$seller = glow_sd_seller( $sid );

	$statuses = array( 'processing', 'completed', 'on-hold', 'waiting-call' );

	// 1) orders credited to this seller by ID
	$mine = wc_get_orders( array(
		'limit'      => -1,
		'status'     => $statuses,
		'meta_key'   => 'wc_seller_field',
		'meta_value' => $sid,
		'orderby'    => 'date',
		'order'      => 'DESC',
	) );

	// 2) orders where the customer typed this seller's name at checkout
	$manual = array();
	if ( $fid && $seller['key'] ) {
		$manual = wc_get_orders( array(
			'limit'      => -1,
			'status'     => $statuses,
			'orderby'    => 'date',
			'order'      => 'DESC',
			'meta_query' => array(
				'relation' => 'AND',
				array( 'key' => 'fundraiser_field', 'value' => $fid,      'compare' => '=' ),
				array( 'key' => 'wc_seller_field',  'value' => 'custom',  'compare' => '=' ),
			),
		) );
	}

	$closeout   = get_user_meta( $fid, glow_sd_meta( 'closeout_date' ), true );
	$was_closed = ( 2 == get_user_meta( $fid, glow_sd_meta( 'action_status' ), true ) );

	$s = array(
		'online_gross' => 0, 'online_profit' => 0, 'online_items' => 0,
		'sts_gross'    => 0, 'sts_items'     => 0, 'sts_profit'   => 0,
		'cat_items'    => 0,
		'order_count'  => 0, 'processing' => 0, 'shipped' => 0, 'delivered' => 0,
		'buyers'       => array(),
		'days'         => array(),
		'trend'        => 0,
		'table'        => array(),
	);

	$today = strtotime( date_i18n( 'Y-m-d' ) );
	for ( $i = 13; $i >= 0; $i-- ) {
		$s['days'][ date( 'Y-m-d', $today - ( $i * DAY_IN_SECONDS ) ) ] = 0;
	}

	$sts_rows  = array();
	$rate_base = $config['rebate']['base'];
	$rate_bulk = $config['rebate']['bulk'];
	$bulk_qty  = $config['rebate']['bulk_qty'];

	$batches = array( 'own' => $mine, 'manual' => $manual );

	foreach ( $batches as $source => $orders ) {
		foreach ( $orders as $order ) {

			$order_id = $order->get_id();
			$status   = $order->get_status();
			$type     = $order->get_meta( 'wc_order_type' );
			$scat     = $order->get_meta( 'ship_with_catalog' );
			$customer = $order->get_customer_id();
			$fund_of  = $order->get_meta( 'fundraiser_field' );

			// manual batch: only rows whose typed name matches this seller
			if ( 'manual' === $source ) {
				$typed = strtolower( preg_replace( '/^\s+|\s+$/', '', (string) $order->get_meta( 'wc_seller_field_custom' ) ) );
				if ( $typed !== $seller['key'] ) continue;
			}

			$created = $order->get_date_created();
			$ts      = $created ? $created->getTimestamp() : 0;

			// original rule: exclude orders placed after the fundraiser closed out
			if ( $was_closed && $closeout && $ts && $ts > strtotime( $closeout ) ) {
				continue;
			}

			$gross = (float) $order->get_subtotal() - (float) $order->get_total_discount();
			if ( $gross < 0 ) $gross = 0;

			$qty = 0;
			foreach ( $order->get_items() as $item ) {
				$qty += (int) $item->get_quantity();
			}

			$profit = 0;

			if ( 'Online' === $type ) {

				$rate   = ( $qty > $bulk_qty ) ? $rate_bulk : $rate_base;
				$profit = $gross * ( $rate / 100 );

				$s['online_gross']  += $gross;
				$s['online_profit'] += $profit;
				$s['online_items']  += $qty;

				// recent buyers
				if ( count( $s['buyers'] ) < $config['recent_buyers']
				     && in_array( $status, array( 'processing', 'completed' ), true ) ) {
					$name = trim( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() );
					$s['buyers'][] = array(
						'name'   => $name ? $name : 'A supporter',
						'gross'  => $gross,
						'profit' => $profit,
						'qty'    => $qty,
						'ts'     => $ts,
					);
				}

			} elseif ( 'Catlog' === $type ) {

				// chairperson-placed brochure orders credited to this seller
				if ( $fund_of && $customer && $fund_of == $customer ) {
					$s['cat_items'] += $qty;
				}

			} elseif ( 'ship-to-school' === $type && 1 == $scat ) {

				$s['sts_gross'] += $gross;
				$s['sts_items'] += $qty;
				$sts_rows[]      = array( 'gross' => $gross, 'ts' => $ts );
			}

			// ---- status buckets
			$s['order_count']++;
			if ( in_array( $status, array( 'processing', 'on-hold', 'waiting-call' ), true ) ) {
				$s['processing']++;
			} elseif ( 'completed' === $status ) {
				if ( glow_sd_tracking_number( $order_id ) ) $s['shipped']++;
				else                                        $s['delivered']++;
			}

			// ---- daily bucket
			if ( $ts && $profit ) {
				$day = date( 'Y-m-d', $ts );
				if ( isset( $s['days'][ $day ] ) ) $s['days'][ $day ] += $profit;
			}

			// ---- order table
			if ( count( $s['table'] ) < $config['orders_in_table'] ) {
				$s['table'][] = array(
					'id'       => $order_id,
					'customer' => trim( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ),
					'fund'     => $fund_of,
					'date'     => $ts ? date_i18n( 'Y-m-d', $ts ) : '',
					'status'   => ( 'waiting-call' === $status ) ? 'Ship To Group' : wc_get_order_status_name( $status ),
					'raw'      => $status,
					'tracking' => glow_sd_tracking_number( $order_id ),
					'total'    => $gross,
					'source'   => $source,
				);
			}
		}
	}

	// ---- ship-to-group tier, same aggregate rule as the original
	$agg      = $s['cat_items'] + $s['sts_items'];
	$sts_rate = ( $agg > $bulk_qty ) ? $rate_bulk : $rate_base;

	$s['sts_profit'] = $s['sts_gross'] * ( $sts_rate / 100 );

	foreach ( $sts_rows as $row ) {
		if ( ! $row['ts'] ) continue;
		$day = date( 'Y-m-d', $row['ts'] );
		if ( isset( $s['days'][ $day ] ) ) {
			$s['days'][ $day ] += $row['gross'] * ( $sts_rate / 100 );
		}
	}

	$s['total_gross']  = $s['online_gross'] + $s['sts_gross'];
	$s['total_profit'] = $s['online_profit'] + $s['sts_profit'];

	// ---- trend
	$vals = array_values( $s['days'] );
	$prev = array_sum( array_slice( $vals, 0, 7 ) );
	$last = array_sum( array_slice( $vals, 7, 7 ) );
	if ( $prev > 0 )     $s['trend'] = round( ( ( $last - $prev ) / $prev ) * 100 );
	elseif ( $last > 0 ) $s['trend'] = 100;

	// newest orders first in the table
	usort( $s['table'], function ( $a, $b ) {
		return strcmp( $b['date'], $a['date'] );
	} );

	$s['_profit_min_items'] = GLOW_BROCHURE_PROFIT_MIN_ITEMS;
	set_transient( $key, $s, $config['cache_minutes'] * MINUTE_IN_SECONDS );

	return $s;
}
endif;

if ( ! function_exists( 'glow_sd_tracking_number' ) ) :
function glow_sd_tracking_number( $order_id ) {

	static $cache = array();
	if ( isset( $cache[ $order_id ] ) ) return $cache[ $order_id ];

	global $wpdb;

	$note = $wpdb->get_var( $wpdb->prepare(
		"SELECT comment_content FROM {$wpdb->comments}
		 WHERE comment_post_ID = %d AND comment_content LIKE %s
		 ORDER BY comment_ID DESC LIMIT 1",
		$order_id, '%tracking number%'
	) );

	$out = '';
	if ( $note && preg_match( '/tracking number[^0-9A-Z]*([0-9A-Z]{8,})/i', $note, $m ) ) {
		$out = $m[1];
	}

	return $cache[ $order_id ] = $out;
}
endif;


/* =========================================================
   CHECKLIST
   ========================================================= */

if ( ! function_exists( 'glow_sd_checklist_steps' ) ) :
function glow_sd_checklist_steps() {
	// keys unchanged (saved user meta uses them) — labels match the mockup
	return array(
		'store'  => 'Customize Your Store',
		'link'   => 'Share Your Link',
		'text'   => 'Send a Text or Post',
		'social' => 'Keep Selling!',
	);
}
endif;

if ( ! function_exists( 'glow_sd_checklist' ) ) :
function glow_sd_checklist( $sid ) {

	$saved = get_user_meta( $sid, 'glow_sd_checklist', true );
	if ( ! is_array( $saved ) ) $saved = array();

	// step 1 ticks itself once a photo and message are up
	if ( ! glow_sd_needs_profile( $sid ) ) $saved['store'] = 1;

	$out = array();
	foreach ( glow_sd_checklist_steps() as $k => $v ) {
		$out[ $k ] = ! empty( $saved[ $k ] );
	}

	return $out;
}
endif;

if ( ! function_exists( 'glow_sd_handle_step_toggle' ) ) :
function glow_sd_handle_step_toggle() {

	if ( ! isset( $_GET['glow_sd_step'], $_GET['_wpnonce'] ) ) return;

	$step = sanitize_key( $_GET['glow_sd_step'] );

	if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'glow_sd_step_' . $step ) ) return;
	if ( ! array_key_exists( $step, glow_sd_checklist_steps() ) ) return;

	$uid = get_current_user_id();
	if ( ! $uid ) return;

	$saved = get_user_meta( $uid, 'glow_sd_checklist', true );
	if ( ! is_array( $saved ) ) $saved = array();

	if ( ! empty( $saved[ $step ] ) ) unset( $saved[ $step ] );
	else                              $saved[ $step ] = 1;

	update_user_meta( $uid, 'glow_sd_checklist', $saved );

	wp_safe_redirect( remove_query_arg( array( 'glow_sd_step', '_wpnonce' ) ) );
	exit;
}
endif;

if ( ! function_exists( 'glow_sd_step_url' ) ) :
function glow_sd_step_url( $step ) {
	return wp_nonce_url( add_query_arg( 'glow_sd_step', $step ), 'glow_sd_step_' . $step );
}
endif;

if ( ! function_exists( 'glow_sd_maybe_refresh' ) ) :
function glow_sd_maybe_refresh( $sid ) {
	if ( ! empty( $_GET['glow_sd_refresh'] ) ) {
		delete_transient( 'glow_sd_stats_' . $sid );
		return true;
	}
	return false;
}
endif;

/**
 * OPTIONAL — paste into functions.php so the numbers update the moment an order
 * changes status instead of waiting out the 5-minute cache:
 *
 *   add_action( 'woocommerce_order_status_changed', function ( $id, $f, $t, $order ) {
 *       $sid = $order->get_meta( 'wc_seller_field' );
 *       if ( $sid && is_numeric( $sid ) ) delete_transient( 'glow_sd_stats_' . absint( $sid ) );
 *   }, 10, 4 );
 */

/* =========================================================================
   PART 2 — RENDER  (matches the approved seller mockup)
   ========================================================================= */

glow_sd_handle_step_toggle();

get_header();

$sid = glow_sd_current_seller_id();

if ( ! $sid ) {
	echo '<div class="glow-sd-gate"><h2>Please log in</h2><p>Sign in as a seller to see your dashboard.</p>'
	   . '<a class="glow-sd-gate-btn" href="' . esc_url( wp_login_url( get_permalink() ) ) . '">Log in</a></div>';
	get_footer();
	return;
}

$fid = glow_sd_fundraiser_id( $sid );

if ( ! $fid ) {
	echo '<div class="glow-sd-gate"><h2>No fundraiser linked</h2>'
	   . '<p>This account is not registered to a fundraiser yet. Ask your chairperson for the seller registration link.</p></div>';
	get_footer();
	return;
}

glow_sd_maybe_refresh( $sid );

$seller    = glow_sd_seller( $sid );
$campaign  = glow_sd_campaign( $fid );
$stats     = glow_sd_stats( $sid );
$checklist = glow_sd_checklist( $sid );
$config    = glow_sd_config();

$photo  = glow_sd_photo( $sid );
$goal   = glow_sd_goal( $sid );

/**
 * "Total Sales" on the goal card. This keeps the original profit-based figure
 * so the % matches the old dashboard. If the seller goal is meant to be GROSS
 * sales, swap $raised for $stats['total_gross'].
 */
$raised = $stats['total_profit'];
$pct    = ( $goal > 0 ) ? min( 100, round( ( $raised / $goal ) * 100 ) ) : 0;

$shop_link = glow_sd_shop_link( $sid );
$link_show = preg_replace( '#^https?://(www\.)?#', '', $shop_link );
$qr        = get_user_meta( $sid, glow_sd_meta( 'qr' ), true );

$can_brochure = glow_seller_allows( $sid, $fid );

$share_text = sprintf( 'Support %s — shop GLOW candles through my link:', $campaign['org'] );

$cheer = ( $goal > 0 && $pct >= 100 ) ? 'You hit your goal!'
       : ( ( $pct >= 50 )             ? "You're doing amazing!" : 'Great start — keep going!' );

$items_sold = $stats['online_items'] + $stats['sts_items'];
$avg_cust   = $stats['order_count'] > 0 ? $stats['total_gross'] / $stats['order_count'] : 0;

/* top customer: biggest single spender across the order table */
$top_customer = null;
$cust_totals  = array();
foreach ( $stats['table'] as $t_row ) {
	$nm = $t_row['customer'] ? $t_row['customer'] : 'A supporter';
	if ( ! isset( $cust_totals[ $nm ] ) ) $cust_totals[ $nm ] = 0;
	$cust_totals[ $nm ] += $t_row['total'];
}
if ( $cust_totals ) {
	arsort( $cust_totals );
	$top_customer = array( 'name' => key( $cust_totals ), 'total' => current( $cust_totals ) );
}

/* current step = first incomplete; badge action per step */
$step_actions = array(
	'store'  => array( 'label' => 'START NOW', 'url' => glow_sd_url( 'customize' ) ),
	'link'   => array( 'label' => 'SHARE NOW', 'url' => '#glow-sd-share' ),
	'text'   => array( 'label' => 'SHARE NOW', 'url' => 'sms:?&body=' . rawurlencode( $share_text . ' ' . $shop_link ) ),
	'social' => array( 'label' => 'POST NOW',  'url' => 'https://www.facebook.com/sharer.php?u=' . rawurlencode( $shop_link ) ),
);
$current_step = '';
foreach ( glow_sd_checklist_steps() as $sk => $sl ) {
	if ( empty( $checklist[ $sk ] ) ) { $current_step = $sk; break; }
}

/* Additional tools — Sachin: update these URLs to the real pages */
$tools = array(
	'social'   => array( 'label' => 'Social Media<br>Images',     'url' => glow_sd_url( 'instructions' ), 'ic' => 'is-blue' ),
	'template' => array( 'label' => 'Text &amp; Email<br>Templates', 'url' => glow_sd_url( 'instructions' ), 'ic' => 'is-purple' ),
	'flyer'    => array( 'label' => 'Flyer<br>Download',          'url' => glow_sd_url( 'instructions' ), 'ic' => 'is-pink' ),
	'tips'     => array( 'label' => 'Selling<br>Tips',            'url' => glow_sd_url( 'instructions' ), 'ic' => 'is-amber' ),
	'faq'      => array( 'label' => 'FAQ',                        'url' => home_url( '/faq/' ),           'ic' => 'is-teal' ),
	'support'  => array( 'label' => 'Contact<br>Support',         'url' => home_url( '/contact/' ),       'ic' => 'is-orange' ),
);

$is_admin_view = glow_sd_is_admin_view( $sid );
?>

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Caveat:wght@600;700&family=Inter:wght@400;500;600;700;800;900&display=swap">

<div class="glow-sd">

	<!-- ============ HEADER ============ -->
	<header class="glow-sd-top">

		<div class="glow-sd-brand">
			<div class="glow-sd-brand-word">
				<span class="glow-sd-brand-spark l">&#10022;</span>
				<h2>GLOW</h2>
				<span class="glow-sd-brand-spark r">&#10022;</span>
			</div>
			<p class="glow-sd-brand-sub">&#10022; SCENTED CANDLES &#10022;</p>
		</div>

		<div class="glow-sd-top-rule" aria-hidden="true"></div>

		<div class="glow-sd-topmid">
			<div class="glow-sd-code">
				Seller Code: <b id="glow-sd-code"><?php echo esc_html( $sid ); ?></b>
				<button type="button" class="glow-sd-code-copy" data-copy="<?php echo esc_attr( $sid ); ?>">COPY</button>
			</div>
			<div class="glow-sd-statusrow">
				<?php if ( $campaign['closed'] ) : ?>
					<span class="glow-sd-live is-off"><i></i>CAMPAIGN CLOSED</span>
				<?php elseif ( $campaign['active'] ) : ?>
					<span class="glow-sd-live is-on"><i></i>SELLER ACTIVE</span>
				<?php else : ?>
					<span class="glow-sd-live is-warn"><i></i>END DATE PASSED</span>
				<?php endif; ?>
			</div>
		</div>

		<div class="glow-sd-slogan" aria-hidden="true">
			<div class="glow-sd-slogan-txt">
				<span class="l1"><em>Let's Turn</em> <i>Your Goal Into Reality! &#9825;</i></span>
				<span class="l2">Share. Sell. Watch your impact grow.</span>
			</div>
			<svg class="glow-sd-bigheart" width="74" height="70" viewBox="0 0 74 70" fill="none" aria-hidden="true">
				<defs><linearGradient id="glowSdHeart" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#4fd6ff"/><stop offset="45%" stop-color="#ff2d7e"/><stop offset="100%" stop-color="#ffcf5c"/></linearGradient></defs>
				<path d="M37 62C20 49 8 39 8 25 8 15 15 8 24 8c6 0 11 3 13 8 2-5 7-8 13-8 9 0 16 7 16 17 0 14-12 24-29 37Z" stroke="url(#glowSdHeart)" stroke-width="7" stroke-linejoin="round"/>
			</svg>
			<span class="glow-sd-spark s1">&#10022;</span>
			<span class="glow-sd-spark s2">&#10023;</span>
			<span class="glow-sd-spark s3">&#10022;</span>
			<div class="glow-sd-stack">SHARE<br>SELL<br>SUPPORT<br>YOUR GROUP <span>&#9825;</span></div>
		</div>
	</header>

	<main class="glow-sd-board">

		<!-- ---------- NOTICES ---------- -->
		<?php if ( $is_admin_view ) : ?>
			<div class="glow-sd-notice is-admin">Admin view &mdash; you are looking at seller #<?php echo esc_html( $sid ); ?>.</div>
		<?php endif; ?>

		<?php if ( $campaign['closed'] ) : ?>
			<div class="glow-sd-notice is-done">
				This fundraiser has been closed out. Sales profits are sent to your chairperson on the 15th of the month.
				<a href="#" id="rebook_ajax" data-id="<?php echo esc_attr( $sid ); ?>">Register for a new campaign</a>
			</div>
		<?php endif; ?>

		<?php if ( glow_sd_needs_profile( $sid ) ) : ?>
			<div class="glow-sd-notice is-todo">
				Add your photo and a short message &mdash; sellers with both raise noticeably more.
				<a href="<?php echo esc_url( glow_sd_url( 'customize' ) ); ?>">Add them now</a>
			</div>
		<?php endif; ?>

		<?php if ( $goal <= 0 ) : ?>
			<div class="glow-sd-notice is-todo">
				You haven't set a personal sales goal yet.
				<a href="<?php echo esc_url( add_query_arg( 'seller_ID', $sid, glow_sd_url( 'edit_info' ) ) ); ?>">Set your goal</a>
			</div>
		<?php endif; ?>

		<!-- ---------- ROW 1 ---------- -->
		<section class="glow-sd-row glow-sd-row1">

			<!-- Profile -->
			<div class="glow-sd-card glow-sd-profile">
				<span class="glow-sd-profile-spark">&#10022;</span>
				<div class="glow-sd-avatar">
					<?php if ( $photo ) : ?>
						<img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $seller['full'] ); ?>">
					<?php else : ?>
						<svg viewBox="0 0 100 100" role="img" aria-label="No photo yet">
							<rect width="100" height="100" rx="50" fill="#ffe1e8"/>
							<circle cx="50" cy="38" r="17" fill="#f7c9b0"/>
							<path d="M22 92c3-16 14-24 28-24s25 8 28 24H22Z" fill="#e05a86"/>
						</svg>
					<?php endif; ?>
				</div>
				<h3 class="glow-sd-hi">Hi,<br><?php echo $seller['first'] ? esc_html( $seller['first'] ) . '!' : 'there!'; ?></h3>
				<p class="glow-sd-role">Seller Code:<br><?php echo esc_html( $sid ); ?></p>
				<a class="glow-sd-store-btn" href="<?php echo esc_url( $shop_link ); ?>" target="_blank" rel="noopener">
					<svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linejoin="round" aria-hidden="true"><path d="M3.6 4.5h16.8l1.4 4.2a3 3 0 0 1-5.7 1.6 3 3 0 0 1-5.6 0 3 3 0 0 1-5.7-1.6L3.6 4.5Z"/><path d="M4.8 11.4V20h14.4v-8.6"/></svg>
					VIEW MY STORE
					<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12h15M13 6l6 6-6 6"/></svg>
				</a>
				<a class="glow-sd-edit-link" href="<?php echo esc_url( add_query_arg( 'seller_ID', $sid, glow_sd_url( 'edit_info' ) ) ); ?>">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" aria-hidden="true"><path d="M4 20h4L20 8l-4-4L4 16v4Z"/></svg>
					Edit my info
				</a>
			</div>

			<!-- Sales goal -->
			<div class="glow-sd-card glow-sd-goal">
				<div class="glow-sd-card-head">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
						<circle cx="12" cy="12" r="8.5" stroke="#ec0f63" stroke-width="1.9"/>
						<circle cx="12" cy="12" r="4.6" stroke="#ec0f63" stroke-width="1.9"/>
						<circle cx="12" cy="12" r="1.4" fill="#ec0f63"/>
					</svg>
					<h3>MY SALES GOAL</h3>
					<a class="glow-sd-editgoal" href="<?php echo esc_url( add_query_arg( 'seller_ID', $sid, glow_sd_url( 'edit_info' ) ) ); ?>">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" aria-hidden="true"><path d="M4 20h4L20 8l-4-4L4 16v4Z"/></svg>
						Edit Goal
					</a>
				</div>

				<div class="glow-sd-goal-body">
					<div class="glow-sd-donut" style="--pct:<?php echo esc_attr( $pct ); ?>;">
						<div class="glow-sd-donut-in">
							<span class="pct"><?php echo esc_html( $pct ); ?>%</span>
							<span class="of">of $<?php echo esc_html( number_format( $goal, 0 ) ); ?><br>goal</span>
						</div>
					</div>
					<div class="glow-sd-raised">
						<div class="glow-sd-raised-amt">$<?php echo esc_html( number_format( $raised, 0 ) ); ?></div>
						<div class="glow-sd-raised-sub">Total Sales</div>
						<div class="glow-sd-bar"><span style="width:<?php echo esc_attr( $pct ); ?>%"></span></div>
						<div class="glow-sd-bar-goal">$<?php echo esc_html( number_format( $goal, 0 ) ); ?></div>
					</div>
				</div>

				<div class="glow-sd-cheerbox">
					<span class="glow-sd-mini-bars" aria-hidden="true"><i></i><i></i><i></i></span>
					<div>
						<b><?php echo esc_html( $cheer ); ?></b>
						<span>Every sale makes a big impact for your group!</span>
					</div>
				</div>
			</div>

			<!-- Share -->
			<div class="glow-sd-card glow-sd-share" id="glow-sd-share">
				<div class="glow-sd-card-head">
					<svg width="24" height="24" viewBox="0 0 24 24" aria-hidden="true"><path d="M21.5 3 2.8 10.4c-.9.4-.8 1.6.1 1.8l4.6 1.3 1.4 4.8c.3.9 1.5 1 1.9.2L21.5 3Z" fill="#17b98a"/><path d="M21.5 3 9.1 18.5l-.5-4.9L21.5 3Z" fill="#0f8f6b"/></svg>
					<h3>SHARE YOUR LINK</h3>
				</div>
				<p class="glow-sd-share-sub">Every sale through your link is credited to you automatically!</p>

				<div class="glow-sd-share-grid2">
					<div class="glow-sd-share-links">
						<div class="glow-sd-linkbox">
							<input id="glow-sd-link" type="text" readonly value="<?php echo esc_attr( $link_show ); ?>" aria-label="Your store link">
							<button type="button" class="glow-sd-copy" data-copy="<?php echo esc_attr( $shop_link ); ?>">COPY</button>
						</div>
					</div>

					<div class="glow-sd-qr-wrap">
						<?php if ( $qr ) : ?>
							<svg class="glow-sd-qr-arrow" width="52" height="38" viewBox="0 0 70 52" fill="none" aria-hidden="true">
								<path d="M4 6c22-2 44 6 55 26" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
								<path d="M52 24l8 9 3-11" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							<div class="glow-sd-qr-item">
								<img class="glow-sd-qr" src="<?php echo esc_url( $qr ); ?>" alt="QR code for your store link" loading="lazy">
								<span>Scan to Shop<br>My Store</span>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<div class="glow-sd-share-btns">
					<a class="glow-sd-sbtn is-text" href="sms:?&body=<?php echo rawurlencode( $share_text . ' ' . $shop_link ); ?>">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M12 3c5 0 9 3.3 9 7.4 0 4.1-4 7.4-9 7.4-1 0-2-.1-2.9-.4L4 19.5l1.2-3.3C3.8 14.8 3 12.7 3 10.4 3 6.3 7 3 12 3Z"/></svg>
						Text My Link
					</a>
					<a class="glow-sd-sbtn is-fb" target="_blank" rel="noopener" href="https://www.facebook.com/sharer.php?u=<?php echo rawurlencode( $shop_link ); ?>">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.2c-1.2 0-1.6.8-1.6 1.6V12h2.7l-.4 2.9h-2.3v7A10 10 0 0 0 22 12Z"/></svg>
						Share on Facebook
					</a>
					<a class="glow-sd-sbtn is-ig" data-ig href="https://www.instagram.com/" target="_blank" rel="noopener">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5.4"/><circle cx="12" cy="12" r="4.1"/><circle cx="17.3" cy="6.7" r="1.2" fill="#fff" stroke="none"/></svg>
						Share on Instagram
					</a>
				</div>
			</div>
		</section>

		<!-- ---------- ROW 2 : ACTION TILES ---------- -->
		<section class="glow-sd-row glow-sd-row2">

			<a class="glow-sd-tile is-store" href="<?php echo esc_url( glow_sd_url( 'customize' ) ); ?>">
				<span class="glow-sd-tile-icon">
					<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linejoin="round" aria-hidden="true"><path d="M3.6 4.5h16.8l1.4 4.2a3 3 0 0 1-5.7 1.6 3 3 0 0 1-5.6 0 3 3 0 0 1-5.7-1.6L3.6 4.5Z"/><path d="M4.8 11.4V20h14.4v-8.6"/></svg>
				</span>
				<span class="glow-sd-tile-txt">
					<strong>CUSTOMIZE<br>MY STORE</strong>
					<span>Add your photo, message and share your story.</span>
				</span>
				<span class="glow-sd-tile-chev"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg></span>
			</a>

			<a class="glow-sd-tile is-toolbox" href="<?php echo esc_url( glow_sd_url( 'instructions' ) ); ?>" target="_blank" rel="noopener">
				<span class="glow-sd-tile-icon">
					<svg width="26" height="26" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M20 4 4 10v4l3 .9V19a2 2 0 0 0 4 0v-2.9l9 2.9V4Z"/></svg>
				</span>
				<span class="glow-sd-tile-txt">
					<strong>MARKETING<br>TOOLBOX</strong>
					<span>Social images, text ideas, flyers and more to help you sell!</span>
				</span>
				<span class="glow-sd-tile-chev"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg></span>
			</a>

			<?php if ( $can_brochure && ! $campaign['closed'] ) : ?>
				<a class="glow-sd-tile is-brochure" href="#glow-sd-brochures">
			<?php else : ?>
				<span class="glow-sd-tile is-brochure is-locked" title="Your chairperson has not opened brochure orders yet">
			<?php endif; ?>
				<span class="glow-sd-tile-icon">
					<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linejoin="round" aria-hidden="true"><path d="M12 6.5C10.3 5.2 8 4.6 4.5 4.8v13c3.5-.2 5.8.4 7.5 1.7 1.7-1.3 4-1.9 7.5-1.7v-13c-3.5-.2-5.8.4-7.5 1.7Z"/><path d="M12 6.5v13"/></svg>
				</span>
				<span class="glow-sd-tile-txt">
					<strong>SUBMIT<br>BROCHURE ORDER</strong>
					<span>Submit your group's paper order form.<br>(if available)</span>
				</span>
				<span class="glow-sd-tile-chev"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg></span>
			<?php echo ( $can_brochure && ! $campaign['closed'] ) ? '</a>' : '</span>'; ?>

			<a class="glow-sd-tile is-reports" href="<?php echo esc_url( glow_sd_url( 'report' ) ); ?>">
				<span class="glow-sd-tile-icon">
					<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M5 20V10M12 20V4M19 20v-7"/></svg>
				</span>
				<span class="glow-sd-tile-txt">
					<strong>MY SALES REPORT</strong>
					<span>View your sales, customer activity and download your report.</span>
				</span>
				<span class="glow-sd-tile-chev"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg></span>
			</a>
		</section>

		<!-- ---------- LOWER GRID ---------- -->
		<section class="glow-sd-lower">

			<div class="glow-sd-lower-main">

				<!-- Checklist -->
				<div class="glow-sd-card glow-sd-checklist">
					<div class="glow-sd-checklist-intro">
						<div class="glow-sd-card-head">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ec0f63" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4.5" y="3.5" width="15" height="17" rx="2.5"/><path d="M9 3.5h6v2.6H9zM8.6 11h6.8M8.6 15h4.6"/></svg>
							<h3>QUICK-START CHECKLIST</h3>
						</div>
						<p>Complete these steps to boost your success!</p>
					</div>

					<div class="glow-sd-steps">
						<?php $n = 0; foreach ( glow_sd_checklist_steps() as $k => $label ) : $n++;
							$done    = ! empty( $checklist[ $k ] );
							$current = ( $k === $current_step );
						?>
							<div class="glow-sd-step<?php echo $done ? ' is-done' : ''; ?><?php echo $current ? ' is-current' : ''; ?>">
								<a class="glow-sd-step-num" href="<?php echo esc_url( glow_sd_step_url( $k ) ); ?>"
								   title="<?php echo $done ? 'Mark as not done' : 'Mark as done'; ?>"
								   aria-pressed="<?php echo $done ? 'true' : 'false'; ?>"><span><?php echo esc_html( $n ); ?></span></a>
								<span class="glow-sd-step-label"><?php echo esc_html( $label ); ?></span>
								<?php if ( $current && ! $campaign['closed'] ) : ?>
									<a class="glow-sd-step-badge" href="<?php echo esc_url( $step_actions[ $k ]['url'] ); ?>"><?php echo esc_html( $step_actions[ $k ]['label'] ); ?></a>
								<?php else : ?>
									<span class="glow-sd-step-check"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4.5 4.5L19 7"/></svg></span>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- Sales snapshot -->
				<div class="glow-sd-card glow-sd-snapshot">
					<div class="glow-sd-card-head">
						<span class="glow-sd-mini-bars" aria-hidden="true"><i></i><i></i><i></i></span>
						<div class="glow-sd-head-stack">
							<h3>MY SALES SNAPSHOT</h3>
							<p>Live results from your fundraising.</p>
						</div>
						<a class="glow-sd-fullreport" href="<?php echo esc_url( glow_sd_url( 'report' ) ); ?>">View Full Report &rarr;</a>
					</div>

					<div class="glow-sd-snap-grid">
						<div class="glow-sd-snap is-pink">
							<span class="glow-sd-snap-icon">$</span>
							<div><b>$<?php echo esc_html( number_format( $stats['total_gross'], 0 ) ); ?></b><span>Total Sales</span></div>
						</div>
						<div class="glow-sd-snap is-blue">
							<span class="glow-sd-snap-icon">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><circle cx="9" cy="8" r="3.4"/><path d="M2.8 19c1.1-3.4 3.3-4.9 6.2-4.9s5.1 1.5 6.2 4.9H2.8Z"/><circle cx="17" cy="9" r="2.5"/><path d="M15.8 14.4c2.5.3 4.3 1.6 5.3 4.6h-3.9c-.3-1.8-.8-3.3-1.4-4.6Z"/></svg>
							</span>
							<div><b><?php echo esc_html( $stats['order_count'] ); ?></b><span>Customers<br>$<?php echo esc_html( number_format( $avg_cust, 0 ) ); ?> avg/customer</span></div>
						</div>
						<div class="glow-sd-snap is-green">
							<span class="glow-sd-snap-icon">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linejoin="round" aria-hidden="true"><path d="M5 9h14l-1.3 10.2a1.8 1.8 0 0 1-1.8 1.6H8.1a1.8 1.8 0 0 1-1.8-1.6L5 9Z"/><path d="M8.5 9V7a3.5 3.5 0 0 1 7 0v2"/></svg>
							</span>
							<div><b><?php echo esc_html( number_format( $items_sold ) ); ?></b><span>Items Sold</span></div>
						</div>
						<div class="glow-sd-snap is-purple">
							<span class="glow-sd-snap-icon">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M6 3h12v2h3v3c0 2.5-1.9 4.4-4.3 4.9A6 6 0 0 1 13 16.9V19h3v2H8v-2h3v-2.1a6 6 0 0 1-3.7-3.1C4.9 13.4 3 11.5 3 9V5h3V3Zm-1 4v2c0 1.2.8 2.3 2 2.8V7H5Zm14 0h-2v4.8c1.2-.5 2-1.6 2-2.8V7Z"/></svg>
							</span>
							<div>
								<span class="glow-sd-snap-top">Top Customer</span>
								<b class="is-name"><?php echo $top_customer ? esc_html( $top_customer['name'] ) : '&mdash;'; ?></b>
								<span>$<?php echo esc_html( number_format( $top_customer ? $top_customer['total'] : 0, 0 ) ); ?></span>
							</div>
						</div>
					</div>
				</div>

				<!-- Tools -->
				<div class="glow-sd-card glow-sd-tools">
					<div class="glow-sd-card-head">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="#ec0f63" aria-hidden="true"><path d="M12 8.5A3.5 3.5 0 1 0 12 15.5 3.5 3.5 0 0 0 12 8.5Zm9 3.5c0 .6-.05 1.1-.14 1.7l2 1.6-2 3.4-2.4-1a8.9 8.9 0 0 1-2.9 1.7l-.36 2.6H10.8l-.36-2.6a8.9 8.9 0 0 1-2.9-1.7l-2.4 1-2-3.4 2-1.6A9.6 9.6 0 0 1 5 12c0-.6.05-1.1.14-1.7l-2-1.6 2-3.4 2.4 1a8.9 8.9 0 0 1 2.9-1.7L10.8 2h2.4l.36 2.6a8.9 8.9 0 0 1 2.9 1.7l2.4-1 2 3.4-2 1.6c.09.6.14 1.1.14 1.7Z"/></svg>
						<div class="glow-sd-head-stack">
							<h3>ADDITIONAL TOOLS &amp; RESOURCES</h3>
							<p>Everything you need to be a successful seller.</p>
						</div>
					</div>

					<div class="glow-sd-tools-grid">
						<a href="<?php echo esc_url( $tools['social']['url'] ); ?>" target="_blank" rel="noopener">
							<span class="glow-sd-tool-ic is-blue"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" stroke-linejoin="round" aria-hidden="true"><rect x="3.5" y="4.5" width="17" height="15" rx="2.5"/><circle cx="9" cy="10" r="1.8"/><path d="m5 18 5-5 3 3 3-3 3.5 3.5"/></svg></span>
							<?php echo wp_kses_post( $tools['social']['label'] ); ?>
						</a>
						<a href="<?php echo esc_url( $tools['template']['url'] ); ?>" target="_blank" rel="noopener">
							<span class="glow-sd-tool-ic is-purple"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" stroke-linejoin="round" aria-hidden="true"><path d="M4 5.5h16v11H9l-5 4z"/><path d="M8 9.5h8M8 12.5h5"/></svg></span>
							<?php echo wp_kses_post( $tools['template']['label'] ); ?>
						</a>
						<a href="<?php echo esc_url( $tools['flyer']['url'] ); ?>" target="_blank" rel="noopener">
							<span class="glow-sd-tool-ic is-pink"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" stroke-linejoin="round" aria-hidden="true"><path d="M5 3.5h9l5 5V20.5H5z"/><path d="M14 3.5v5h5M8 13h8M8 16.5h5"/></svg></span>
							<?php echo wp_kses_post( $tools['flyer']['label'] ); ?>
						</a>
						<a href="<?php echo esc_url( $tools['tips']['url'] ); ?>" target="_blank" rel="noopener">
							<span class="glow-sd-tool-ic is-amber"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9.5 18h5M10 21h4M12 3a6 6 0 0 0-3.5 10.9c.8.6 1 1.3 1 2.1h5c0-.8.2-1.5 1-2.1A6 6 0 0 0 12 3Z"/></svg></span>
							<?php echo wp_kses_post( $tools['tips']['label'] ); ?>
						</a>
						<a href="<?php echo esc_url( $tools['faq']['url'] ); ?>">
							<span class="glow-sd-tool-ic is-teal"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="8.5"/><path d="M9.6 9.3A2.5 2.5 0 0 1 14.5 10c0 1.6-2.5 1.9-2.5 3.4"/><circle cx="12" cy="16.6" r=".4" fill="#fff"/></svg></span>
							<?php echo wp_kses_post( $tools['faq']['label'] ); ?>
						</a>
						<a href="<?php echo esc_url( $tools['support']['url'] ); ?>">
							<span class="glow-sd-tool-ic is-orange"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4.5 12a7.5 7.5 0 0 1 15 0"/><rect x="3" y="12" width="4" height="6" rx="1.6"/><rect x="17" y="12" width="4" height="6" rx="1.6"/><path d="M19.5 18a3.5 3.5 0 0 1-3.5 3h-2"/></svg></span>
							<?php echo wp_kses_post( $tools['support']['label'] ); ?>
						</a>
					</div>
				</div>
			</div>

			<!-- Right column -->
			<div class="glow-sd-lower-side">

				<div class="glow-sd-card glow-sd-thanks">
					<span class="glow-sd-th-spark s1">&#10022;</span>
					<span class="glow-sd-th-spark s2">&#10023;</span>
					<span class="glow-sd-th-heart h1">&#9825;</span>
					<span class="glow-sd-th-heart h2">&#10084;</span>
					<span class="glow-sd-th-heart h3">&#9825;</span>
					<div class="glow-sd-thanks-script">
						<span class="a">Keep Going &mdash;</span>
						<span class="a">You're Making It Happen!</span>
						<svg class="glow-sd-underline" width="180" height="14" viewBox="0 0 180 14" fill="none" aria-hidden="true"><path d="M4 9C50 3 130 3 176 8" stroke="#ff2d7e" stroke-width="5" stroke-linecap="round"/></svg>
					</div>
					<p>Every order you earn helps move you closer to your goal!</p>
					<span class="glow-sd-confetti" style="left:16%;bottom:16px;background:#ff2d7e;transform:rotate(30deg);"></span>
					<span class="glow-sd-confetti" style="left:34%;bottom:28px;background:#ffcf5c;transform:rotate(-24deg);"></span>
					<span class="glow-sd-confetti" style="left:54%;bottom:14px;background:#4fd6ff;transform:rotate(18deg);"></span>
					<span class="glow-sd-confetti" style="left:72%;bottom:26px;background:#a06bff;transform:rotate(-30deg);"></span>
					<span class="glow-sd-confetti" style="left:88%;bottom:16px;background:#17b98a;transform:rotate(24deg);"></span>
				</div>

				<div class="glow-sd-card glow-sd-settings">
					<div class="glow-sd-card-head">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="#ec0f63" aria-hidden="true"><path d="M12 8.5A3.5 3.5 0 1 0 12 15.5 3.5 3.5 0 0 0 12 8.5Zm9 3.5c0 .6-.05 1.1-.14 1.7l2 1.6-2 3.4-2.4-1a8.9 8.9 0 0 1-2.9 1.7l-.36 2.6H10.8l-.36-2.6a8.9 8.9 0 0 1-2.9-1.7l-2.4 1-2-3.4 2-1.6A9.6 9.6 0 0 1 5 12c0-.6.05-1.1.14-1.7l-2-1.6 2-3.4 2.4 1a8.9 8.9 0 0 1 2.9-1.7L10.8 2h2.4l.36 2.6a8.9 8.9 0 0 1 2.9 1.7l2.4-1 2 3.4-2 1.6c.09.6.14 1.1.14 1.7Z"/></svg>
						<h3>ACCOUNT SETTINGS</h3>
					</div>
					<a class="glow-sd-setrow" href="<?php echo esc_url( add_query_arg( 'seller_ID', $sid, glow_sd_url( 'edit_info' ) ) ); ?>">
						Edit My Info
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg>
					</a>
					<a class="glow-sd-setrow" href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>">
						Log Out
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg>
					</a>
				</div>
			</div>
		</section>

		<!-- ========== DETAIL SECTIONS (functional, below the fold) ========== -->

		<!-- Submit your brochure order -->
		<?php if ( $can_brochure ) : ?>
		<section class="glow-sd-card glow-sd-brochures" id="glow-sd-brochures">
			<div class="glow-sd-card-head">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#f5741a" stroke-width="1.6" stroke-linejoin="round" aria-hidden="true"><path d="M12 6.5C10.3 5.2 8 4.6 4.5 4.8v13c3.5-.2 5.8.4 7.5 1.7 1.7-1.3 4-1.9 7.5-1.7v-13c-3.5-.2-5.8.4-7.5 1.7Z"/><path d="M12 6.5v13"/></svg>
				<h3>SUBMIT YOUR BROCHURE ORDER</h3>
			</div>

			<?php if ( $campaign['closed'] ) : ?>

				<p class="glow-sd-empty">This fundraiser has been closed. Brochure orders are no longer accepted.</p>

			<?php else : ?>

				<div class="glow-sd-brochure-rows">
					<div class="glow-sd-brochure-row">
						<span>Spring Summer Brochure Order</span>
						<a class="glow-sd-enter" href="<?php echo esc_url( glow_sd_url( 'brochure_ss' ) ); ?>">Enter order</a>
					</div>
					<div class="glow-sd-brochure-row">
						<span>Perfect Scents for Every Season Brochure</span>
						<a class="glow-sd-enter" href="<?php echo esc_url( glow_sd_url( 'brochure_ps' ) ); ?>">Enter order</a>
					</div>
					<div class="glow-sd-brochure-row">
						<span>Fall-Winter Brochure Order</span>
						<a class="glow-sd-enter" href="<?php echo esc_url( glow_sd_url( 'brochure_fw' ) ); ?>">Enter order</a>
					</div>
				</div>

				<p class="glow-sd-brochure-note">
					Select your items, then click <b>Done Brochure Order</b> to send your order to your chairperson.
				</p>

			<?php endif; ?>
		</section>
		<?php endif; ?>

		<!-- My brochure orders -->
		<?php
		$my_brochures = function_exists( 'glow_so_get_seller_orders' ) ? glow_so_get_seller_orders( $sid ) : array();
		if ( $my_brochures ) :
		?>
		<section class="glow-sd-card">
			<div class="glow-sd-card-head">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#f5741a" stroke-width="1.6" stroke-linejoin="round" aria-hidden="true"><path d="M12 6.5C10.3 5.2 8 4.6 4.5 4.8v13c3.5-.2 5.8.4 7.5 1.7 1.7-1.3 4-1.9 7.5-1.7v-13c-3.5-.2-5.8.4-7.5 1.7Z"/><path d="M12 6.5v13"/></svg>
				<h3>MY BROCHURE ORDERS</h3>
				<span class="glow-sd-count-pill"><?php echo count( $my_brochures ); ?></span>
			</div>

			<div class="glow-sd-table-wrap">
				<table class="glow-sd-table">
					<thead>
						<tr><th>Catalog</th><th>Date</th><th>Items</th><th>Total</th><th>Status</th></tr>
					</thead>
					<tbody>
					<?php foreach ( $my_brochures as $o ) :
						$labels = array(
							'submitted' => array( 'Sent to chairperson', 'is-amber'  ),
							'confirmed' => array( 'Money received',      'is-green'  ),
							'moved'     => array( 'In chairperson cart', 'is-purple' ),
							'placed'    => array( 'Ordered',             'is-green'  ),
						);
						$l = isset( $labels[ $o->status ] ) ? $labels[ $o->status ] : array( $o->status, '' );
					?>
						<tr>
							<td>
								<?php echo esc_html( function_exists( 'glow_so_catalog_label' ) ? glow_so_catalog_label( $o->catalog ) : $o->catalog ); ?>
								<a href="#" class="glow-view" data-order="<?php echo esc_attr( $o->id ); ?>">view items</a>
							</td>
							<td><?php echo esc_html( date_i18n( 'M j', strtotime( $o->submitted_at ) ) ); ?></td>
							<td><?php echo (int) $o->item_count; ?></td>
							<td>$<?php echo esc_html( number_format( $o->order_total, 2 ) ); ?></td>
							<td><span class="glow-sd-tag <?php echo esc_attr( $l[1] ); ?>"><?php echo esc_html( $l[0] ); ?></span></td>
						</tr>
						<tr class="glow-sd-items-row" id="glow-items-<?php echo esc_attr( $o->id ); ?>" hidden>
							<td colspan="5">
								<div class="glow-sd-items">
									<?php foreach ( ( function_exists( 'glow_so_get_items' ) ? glow_so_get_items( $o->id ) : array() ) as $it ) : ?>
										<div class="glow-sd-item-row">
											<span class="glow-sd-i-sku"><?php echo esc_html( $it->sku ); ?></span>
											<span class="glow-sd-i-name"><?php echo esc_html( $it->product_name ); ?></span>
											<span class="glow-sd-i-qty">&times; <?php echo (int) $it->qty; ?></span>
											<span class="glow-sd-i-total">$<?php echo esc_html( number_format( $it->line_total, 2 ) ); ?></span>
										</div>
									<?php endforeach; ?>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</section>
		<?php endif; ?>

		<!-- Brochure WC orders -->
		<?php
		$bo_orders = function_exists( 'glow_so_get_seller_wc_orders' ) ? glow_so_get_seller_wc_orders( $sid ) : array();
		if ( $bo_orders ) :
		?>
		<section class="glow-sd-card">
			<div class="glow-sd-card-head">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6236e0" stroke-width="1.7" stroke-linejoin="round" aria-hidden="true"><path d="M3 7.5 12 3l9 4.5-9 4.5z"/><path d="M3 7.5v9L12 21l9-4.5v-9M12 12v9"/></svg>
				<h3>BROCHURE ORDERS &mdash; STATUS &amp; TRACKING</h3>
			</div>

			<div class="glow-sd-table-wrap">
				<table class="glow-sd-table">
					<thead>
						<tr><th>Order</th><th>Seller name</th><th>Fund</th><th>Date</th><th>Status</th><th>Tracking</th><th>Detail</th></tr>
					</thead>
					<tbody>
					<?php foreach ( $bo_orders as $g_order ) :

						$g_id     = $g_order->get_id();
						$g_status = $g_order->get_status();
						if ( ! in_array( $g_status, array( 'processing', 'completed', 'waiting-call', 'on-hold' ), true ) ) continue;

						$g_track = glow_sd_tracking_number( $g_id );
						$g_date  = $g_order->get_date_created() ? date_i18n( 'Y-m-d', $g_order->get_date_created()->getTimestamp() ) : '';
					?>
						<tr>
							<td>#<?php echo esc_html( $g_id ); ?></td>
							<td><?php echo esc_html( $g_order->get_meta( 'wc_seller_field_custom' ) ); ?></td>
							<td><?php echo esc_html( $g_order->get_meta( 'fundraiser_field' ) ); ?></td>
							<td><?php echo esc_html( $g_date ); ?></td>
							<td><span class="glow-sd-tag is-<?php echo esc_attr( $g_status ); ?>"><?php echo 'waiting-call' === $g_status ? 'Ship To Group' : esc_html( wc_get_order_status_name( $g_status ) ); ?></span></td>
							<td>
								<?php if ( $g_track ) : ?>
									<a target="_blank" rel="noopener" href="https://www.ups.com/track?loc=en_US&tracknum=<?php echo esc_attr( $g_track ); ?>&requester=WT/trackdetails"><?php echo esc_html( $g_track ); ?></a>
								<?php else : ?>&mdash;<?php endif; ?>
							</td>
							<td><button type="button" class="glow-sd-mini glow-bo-detail" data-order="<?php echo esc_attr( $g_id ); ?>">Check</button></td>
						</tr>

						<tr class="glow-sd-items-row" id="glow-bo-<?php echo esc_attr( $g_id ); ?>" hidden>
							<td colspan="7">
								<div class="glow-sd-items">
									<div class="glow-sd-item-row is-head">
										<span class="glow-sd-i-name">Product</span>
										<span class="glow-sd-i-qty">Qty</span>
										<span class="glow-sd-i-total">Total</span>
									</div>
									<?php
									$g_qty = 0; $g_amt = 0;
									foreach ( $g_order->get_items() as $g_item ) :
										$g_qty += (int) $g_item['quantity'];
										$g_amt += (float) $g_item['subtotal'];
									?>
										<div class="glow-sd-item-row">
											<span class="glow-sd-i-name"><?php echo esc_html( $g_item['name'] ); ?></span>
											<span class="glow-sd-i-qty">&times; <?php echo (int) $g_item['quantity']; ?></span>
											<span class="glow-sd-i-total">$<?php echo esc_html( number_format( $g_item['subtotal'], 2 ) ); ?></span>
										</div>
									<?php endforeach; ?>
									<div class="glow-sd-item-row is-total">
										<span class="glow-sd-i-name">Total</span>
										<span class="glow-sd-i-qty"><?php echo (int) $g_qty; ?></span>
										<span class="glow-sd-i-total">$<?php echo esc_html( number_format( $g_amt, 2 ) ); ?></span>
									</div>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</section>
		<?php endif; ?>

		<!-- Order status + tracking -->
		<section class="glow-sd-card" id="glow-sd-orders">
			<div class="glow-sd-card-head">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0fa47a" stroke-width="1.7" stroke-linejoin="round" aria-hidden="true"><path d="M3 7.5 12 3l9 4.5-9 4.5z"/><path d="M3 7.5v9L12 21l9-4.5v-9M12 12v9"/></svg>
				<h3>MY ORDERS &amp; TRACKING</h3>
				<a class="glow-sd-refresh" href="<?php echo esc_url( add_query_arg( 'glow_sd_refresh', 1 ) ); ?>" title="Recalculate now">Refresh</a>
				<span class="glow-sd-count-pill"><?php echo count( $stats['table'] ); ?> shown</span>
			</div>

			<?php if ( empty( $stats['table'] ) ) : ?>
				<p class="glow-sd-empty">No orders yet.</p>
			<?php else : ?>
				<div class="glow-sd-table-wrap">
					<table class="glow-sd-table">
						<thead>
							<tr><th>Order</th><th>Customer</th><th>Fund</th><th>Date</th><th>Total</th><th>Status</th><th>Tracking</th></tr>
						</thead>
						<tbody>
						<?php foreach ( $stats['table'] as $row ) : ?>
							<tr>
								<td>#<?php echo esc_html( $row['id'] ); ?></td>
								<td>
									<?php echo esc_html( $row['customer'] ); ?>
									<?php if ( 'manual' === $row['source'] ) : ?><em class="glow-sd-flag">name entered at checkout</em><?php endif; ?>
								</td>
								<td>#<?php echo esc_html( $row['fund'] ); ?></td>
								<td><?php echo esc_html( $row['date'] ); ?></td>
								<td>$<?php echo esc_html( number_format( $row['total'], 2 ) ); ?></td>
								<td><span class="glow-sd-tag is-<?php echo esc_attr( $row['raw'] ); ?>"><?php echo esc_html( $row['status'] ); ?></span></td>
								<td>
									<?php if ( $row['tracking'] ) : ?>
										<a target="_blank" rel="noopener" href="https://www.ups.com/track?loc=en_US&tracknum=<?php echo esc_attr( $row['tracking'] ); ?>&requester=WT/trackdetails"><?php echo esc_html( $row['tracking'] ); ?></a>
									<?php else : ?>&mdash;<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endif; ?>
		</section>

	</main>
</div>

<div class="glow-sd-toast" id="glow-sd-toast" role="status" aria-live="polite"></div>

<script>
var GLOW_SD = {
	ajax: "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>",
	sid:  "<?php echo esc_js( $sid ); ?>",
	rebook: "<?php echo esc_js( glow_sd_url( 'rebook' ) ); ?>"
};
</script>

<script>
(function () {
	'use strict';

	var toastEl = document.getElementById('glow-sd-toast'), t;
	function toast(m) {
		toastEl.textContent = m;
		toastEl.classList.add('is-open');
		clearTimeout(t);
		t = setTimeout(function () { toastEl.classList.remove('is-open'); }, 2200);
	}

	/* ---- copy ---- */
	function copyText(val, btn) {
		function done() {
			toast('Copied');
			if (btn) {
				var o = btn.textContent;
				btn.textContent = 'COPIED!';
				btn.classList.add('is-done');
				setTimeout(function () { btn.textContent = o; btn.classList.remove('is-done'); }, 1500);
			}
		}
		if (navigator.clipboard && window.isSecureContext) {
			navigator.clipboard.writeText(val).then(done).catch(fallback);
		} else { fallback(); }

		function fallback() {
			var tmp = document.createElement('input');
			tmp.value = val;
			document.body.appendChild(tmp);
			tmp.select();
			try { document.execCommand('copy'); } catch (e) {}
			document.body.removeChild(tmp);
			done();
		}
	}

	document.querySelectorAll('[data-copy]').forEach(function (btn) {
		btn.addEventListener('click', function () { copyText(btn.getAttribute('data-copy'), btn); });
	});

	/* ---- instagram has no web share URL: copy first, the link opens the app/site ---- */
	var ig = document.querySelector('[data-ig]');
	if (ig) {
		ig.addEventListener('click', function () {
			copyText("<?php echo esc_js( $shop_link ); ?>", null);
			toast('Link copied — paste it in your story or bio');
		});
	}

	/* ---- expandable item rows (brochure orders + WC detail) ---- */
	document.addEventListener('click', function (e) {
		var view = e.target.closest('.glow-view');
		if (view) {
			e.preventDefault();
			var row = document.getElementById('glow-items-' + view.getAttribute('data-order'));
			if (row) row.hidden = !row.hidden;
			return;
		}

		var detail = e.target.closest('.glow-bo-detail');
		if (detail) {
			var box = document.getElementById('glow-bo-' + detail.getAttribute('data-order'));
			if (box) box.hidden = !box.hidden;
		}
	});

	/* ---- rebook (styled confirmation modal) ---- */
	var rebook = document.getElementById('rebook_ajax');
	if (rebook) {
		var gsStyle = document.createElement('style');
		gsStyle.textContent =
			'.glow-sd-rbk-overlay{position:fixed;inset:0;background:rgba(22,21,43,.55);display:none;align-items:center;justify-content:center;z-index:99999;padding:20px;}' +
			'.glow-sd-rbk-overlay.show{display:flex;}' +
			'.glow-sd-rbk-card{background:#fff;border:3px solid #c9a227;border-radius:16px;max-width:560px;width:100%;padding:34px 34px 30px;box-shadow:0 24px 60px rgba(0,0,0,.28);text-align:center;}' +
			'.glow-sd-rbk-title{color:#c9a227;font-size:22px;font-weight:800;margin:0 0 14px;line-height:1.3;}' +
			'.glow-sd-rbk-divider{border-top:2px dotted #e3ce8f;margin:0 0 18px;}' +
			'.glow-sd-rbk-note{color:#3a3a4a;font-size:15px;line-height:1.65;margin:0 0 24px;}' +
			'.glow-sd-rbk-red{color:#e02424;}' +
			'.glow-sd-rbk-actions{display:flex;gap:16px;justify-content:center;flex-wrap:wrap;}' +
			'.glow-sd-rbk-btn{min-width:120px;padding:12px 30px;border:none;border-radius:8px;font-size:15px;font-weight:700;cursor:pointer;color:#fff;transition:background .2s;}' +
			'.glow-sd-rbk-yes{background:#c9a227;}.glow-sd-rbk-yes:hover{background:#b08e1f;}' +
			'.glow-sd-rbk-no{background:#c9b587;}.glow-sd-rbk-no:hover{background:#b3a071;}';
		document.head.appendChild(gsStyle);

		var gsModal = document.createElement('div');
		gsModal.className = 'glow-sd-rbk-overlay';
		gsModal.innerHTML =
			'<div class="glow-sd-rbk-card" role="dialog" aria-modal="true">' +
				'<h3 class="glow-sd-rbk-title">Ready to reregister your NEW SELLER ACCOUNT?</h3>' +
				'<div class="glow-sd-rbk-divider"></div>' +
				'<p class="glow-sd-rbk-note"><strong class="glow-sd-rbk-red">Note:</strong> Once you click the <strong>YES</strong> button, you will no longer have access to this seller dashboard or detailed reports. <strong>Please note</strong>&mdash;if you need any of these reports, please print them and come back to book here. Please complete the Seller Register form and submit. You will receive New Seller Dashboard login credentials via email.</p>' +
				'<div class="glow-sd-rbk-actions">' +
					'<button type="button" class="glow-sd-rbk-btn glow-sd-rbk-yes">Yes</button>' +
					'<button type="button" class="glow-sd-rbk-btn glow-sd-rbk-no">No</button>' +
				'</div>' +
			'</div>';
		document.body.appendChild(gsModal);

		function gsCloseRebook() { gsModal.classList.remove('show'); }

		rebook.addEventListener('click', function (e) {
			e.preventDefault();
			gsModal.classList.add('show');
		});
		gsModal.querySelector('.glow-sd-rbk-no').addEventListener('click', gsCloseRebook);
		gsModal.addEventListener('click', function (e) { if (e.target === gsModal) gsCloseRebook(); });
		gsModal.querySelector('.glow-sd-rbk-yes').addEventListener('click', function () {
			gsCloseRebook();
			jQuery.post(GLOW_SD.ajax, {
				action: 'seller_wp_rebooking_ajax',
				rebook_id: GLOW_SD.sid,
				yes_action: 'yes'
			}).done(function (res) {
				window.location.href = GLOW_SD.rebook + '?rebook_seller_id=' + res;
			}).fail(function () {
				toast('Something went wrong. Please try again.');
			});
		});
	}
})();
</script>

<style>
/* ============================================================
   GLOW Seller Dashboard — mockup design, scoped .glow-sd
   ============================================================ */
.glow-sd{
	--pink:#ec0f63; --pink-hi:#ff2d7e; --orange:#f5741a; --amber:#f9b81c;
	--purple:#6236e0; --blue:#2f7bea; --teal:#17b98a;
	--ink:#181735; --muted:#6f6c85; --board:#f2f3f9; --card:#fff; --line:#e8e8f0;
	font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;
	color:var(--ink); background:#0b1032; max-width:1780px; margin:0 auto; overflow:hidden;
	-webkit-font-smoothing:antialiased;
}
.glow-sd *{ box-sizing:border-box; }
.glow-sd button{ font-family:inherit; cursor:pointer; border:0; background:none; color:inherit; }
.glow-sd a{ text-decoration:none; }
.glow-sd :focus-visible{ outline:3px solid #7c4bf0; outline-offset:2px; border-radius:6px; }
.glow-sd-gate{ max-width:520px; margin:80px auto; text-align:center; padding:40px 24px; }
.glow-sd-gate-btn{ display:inline-block; margin-top:14px; padding:12px 30px; border-radius:30px; background:#ec0f63; color:#fff !important; font-weight:600; }

/* ---------- header ---------- */
.glow-sd-top{ position:relative; display:flex; align-items:center; gap:28px; padding:18px 34px; min-height:112px; overflow:hidden;
	background:
		radial-gradient(420px 200px at 96% 0%, rgba(124,60,220,.55), transparent 70%),
		radial-gradient(500px 240px at 74% 100%, rgba(64,40,150,.5), transparent 70%),
		linear-gradient(100deg,#090d2c 0%,#0e1440 55%,#251a56 100%); }
.glow-sd-brand{ flex:0 0 auto; }
.glow-sd-brand-word{ position:relative; display:inline-block; }
.glow-sd-brand-word h2{ margin:0; font-family:'Playfair Display',Georgia,serif; font-weight:500; font-size:40px; line-height:1; letter-spacing:.12em; color:#fff; }
.glow-sd-brand-spark{ position:absolute; color:#ff2d7e; font-size:16px; }
.glow-sd-brand-spark.l{ left:-16px; top:8px; font-size:12px; }
.glow-sd-brand-spark.r{ right:-18px; top:-4px; }
.glow-sd-brand-sub{ margin:8px 0 0; font-size:11.5px; font-weight:500; letter-spacing:.24em; color:#dcd8ee; }
.glow-sd-top-rule{ flex:0 0 1px; align-self:stretch; background:linear-gradient(180deg,transparent,rgba(255,255,255,.24),transparent); }
.glow-sd-topmid{ flex:0 0 auto; }
.glow-sd-code{ display:flex; align-items:center; gap:10px; font-size:16px; color:#e7e4f5; font-weight:500; }
.glow-sd-code b{ font-size:19px; color:#fff; font-weight:800; letter-spacing:.03em; }
.glow-sd-code-copy{ padding:7px 16px; border-radius:999px; border:1.5px solid rgba(255,255,255,.45); color:#fff; font-size:12.5px; font-weight:700; letter-spacing:.06em; }
.glow-sd-code-copy:hover{ background:rgba(255,255,255,.15); }
.glow-sd-code-copy.is-done{ background:var(--teal); border-color:var(--teal); }
.glow-sd-statusrow{ display:flex; align-items:center; gap:18px; margin-top:10px; }
.glow-sd-live{ display:inline-flex; align-items:center; gap:8px; font-size:14.5px; font-weight:800; letter-spacing:.04em; }
.glow-sd-live i{ width:10px; height:10px; border-radius:50%; }
.glow-sd-live.is-on{ color:#2fdba4; }   .glow-sd-live.is-on i{ background:#2fdba4; box-shadow:0 0 10px rgba(47,219,164,.9); }
.glow-sd-live.is-off{ color:#ff8080; }  .glow-sd-live.is-off i{ background:#ff8080; }
.glow-sd-live.is-warn{ color:#ffcf5c; } .glow-sd-live.is-warn i{ background:#ffcf5c; }
.glow-sd-slogan{ position:relative; flex:1 1 auto; display:flex; align-items:center; justify-content:flex-end; gap:24px; min-width:0; }
.glow-sd-slogan-txt{ text-align:center; line-height:1.05; }
.glow-sd-slogan-txt .l1{ display:block; font-family:'Caveat',cursive; font-weight:700; font-size:38px; transform:rotate(-3deg); }
.glow-sd-slogan-txt .l1 em{ font-style:normal; color:#ffe9b8; }
.glow-sd-slogan-txt .l1 i{ font-style:normal;
	background:linear-gradient(90deg,#ff4d8d,#ff9dc4); -webkit-background-clip:text; background-clip:text; color:transparent; }
.glow-sd-slogan-txt .l2{ display:block; margin-top:6px; font-size:14px; font-weight:600; color:#e7e4f5; }
.glow-sd-bigheart{ flex:0 0 auto; }
.glow-sd-spark{ position:absolute; color:#ffd166; text-shadow:0 0 12px rgba(255,209,102,.8); }
.glow-sd-spark.s1{ right:26%; top:0; font-size:16px; color:#4fd6ff; }
.glow-sd-spark.s2{ right:20%; bottom:2px; font-size:13px; color:#ff77b0; }
.glow-sd-spark.s3{ left:4%; top:2px; font-size:18px; }
.glow-sd-stack{ flex:0 0 auto; text-align:right; font-size:13.5px; font-weight:800; letter-spacing:.08em; line-height:1.6; color:#fff; }
.glow-sd-stack span{ color:#ff77b0; }

/* ---------- board ---------- */
.glow-sd-board{ background:var(--board); padding:18px 18px 30px; display:grid; gap:18px; }
.glow-sd-row{ display:grid; gap:18px; }
.glow-sd-row1{ grid-template-columns:.62fr 1.05fr 1.15fr; }
.glow-sd-row2{ grid-template-columns:repeat(4,1fr); }
.glow-sd-card{ background:var(--card); border:1px solid var(--line); border-radius:20px; box-shadow:0 2px 6px rgba(22,20,50,.05); padding:20px 22px; }
.glow-sd-card-head{ display:flex; align-items:center; gap:11px; }
.glow-sd-card-head h3{ margin:0; font-size:16.5px; font-weight:800; letter-spacing:.02em; }
.glow-sd-head-stack h3{ margin:0; }
.glow-sd-head-stack p{ margin:3px 0 0; font-size:13.5px; color:var(--muted); }
.glow-sd-mini-bars{ display:inline-flex; align-items:flex-end; gap:2.5px; height:20px; }
.glow-sd-mini-bars i{ width:5px; border-radius:2px; background:var(--pink); }
.glow-sd-mini-bars i:nth-child(1){ height:9px; opacity:.55; }
.glow-sd-mini-bars i:nth-child(2){ height:14px; opacity:.8; }
.glow-sd-mini-bars i:nth-child(3){ height:20px; }
.glow-sd-empty{ margin:20px 0; font-size:15px; color:#8d8aa0; text-align:center; }
.glow-sd-green{ color:var(--teal); }
.glow-sd-refresh{ margin-left:auto; font-size:13.5px; font-weight:600; color:#4a3fd0 !important; text-decoration:underline; }
.glow-sd-count-pill{ margin-left:12px; padding:5px 13px; border-radius:999px; background:#f1eff6; font-size:13.5px; font-weight:600; color:#5d5a75; }

/* notices */
.glow-sd-notice{ display:flex; align-items:center; gap:12px; flex-wrap:wrap; padding:14px 20px; border-radius:14px; font-size:15px; font-weight:500; }
.glow-sd-notice a{ font-weight:700; text-decoration:underline; }
.glow-sd-notice.is-admin{ background:#eef0fd; color:#3b3a72; }
.glow-sd-notice.is-done{ background:#eaf8f1; color:#0d6b50; }
.glow-sd-notice.is-todo{ background:#fff4e7; color:#8a4a08; }

/* ---------- profile ---------- */
.glow-sd-profile{ position:relative; background:linear-gradient(160deg,#ffe4ec,#ffd7e4); border-color:#f9cfdd; display:flex; flex-direction:column; align-items:flex-start; padding:24px; }
.glow-sd-profile-spark{ position:absolute; right:20px; top:16px; color:var(--pink-hi); font-size:20px; }
.glow-sd-avatar{ width:104px; height:104px; border-radius:50%; padding:4px; background:#fff; box-shadow:0 0 0 3px #ff5f95; margin-bottom:14px; }
.glow-sd-avatar img,.glow-sd-avatar svg{ width:100%; height:100%; border-radius:50%; display:block; object-fit:cover; background:#ffe3ea; }
.glow-sd-hi{ margin:0 0 8px; font-size:34px; line-height:1.05; font-weight:800; letter-spacing:-.02em; color:#1a1330; }
.glow-sd-role{ margin:0 0 18px; font-size:15.5px; line-height:1.45; color:#5b4a5c; font-weight:500; }
.glow-sd-store-btn{ display:flex; align-items:center; justify-content:center; gap:10px; width:100%; padding:14px 16px; border-radius:14px;
	background:linear-gradient(180deg,var(--pink-hi),var(--pink)) !important; color:#fff !important; font-size:15px; font-weight:800; letter-spacing:.03em;
	box-shadow:0 8px 20px rgba(236,15,99,.35); border:0 !important; text-decoration:none !important; }
.glow-sd-store-btn:hover{ filter:brightness(1.06); }
.glow-sd-edit-link{ display:inline-flex; align-items:center; gap:7px; margin-top:12px; font-size:14px; font-weight:600; color:#4a3fd0 !important; text-decoration:underline; }

/* ---------- sales goal ---------- */
.glow-sd-editgoal{ margin-left:auto; display:inline-flex; align-items:center; gap:6px; font-size:13.5px; font-weight:700; color:#2f4fd8 !important; text-decoration:underline; }
.glow-sd-goal-body{ display:flex; align-items:center; gap:24px; margin:16px 0 4px; }
.glow-sd-donut{ position:relative; flex:0 0 160px; width:160px; height:160px; border-radius:50%; display:grid; place-items:center;
	background:conic-gradient(var(--pink) calc(var(--pct) * 1%), #ece6ef 0); }
.glow-sd-donut::after{ content:""; position:absolute; inset:15px; border-radius:50%; background:var(--card); }
.glow-sd-donut-in{ position:relative; z-index:2; text-align:center; line-height:1.1; }
.glow-sd-donut-in .pct{ display:block; font-size:36px; font-weight:800; letter-spacing:-.03em; }
.glow-sd-donut-in .of{ display:block; margin-top:4px; font-size:13.5px; font-weight:600; color:#4d4b63; }
.glow-sd-raised{ flex:1 1 auto; min-width:0; }
.glow-sd-raised-amt{ font-size:46px; font-weight:900; letter-spacing:-.04em; line-height:1; }
.glow-sd-raised-sub{ margin:6px 0 14px; font-size:15.5px; font-weight:600; color:#4f4d66; }
.glow-sd-bar{ position:relative; height:9px; border-radius:999px; background:#ece9f0; overflow:hidden; }
.glow-sd-bar span{ position:absolute; inset:0 auto 0 0; border-radius:999px; background:linear-gradient(90deg,#f0176e,#ff2d7e); }
.glow-sd-bar-goal{ margin-top:6px; text-align:right; font-size:13.5px; font-weight:700; color:#4f4d66; }
.glow-sd-cheerbox{ display:flex; align-items:center; gap:14px; margin-top:14px; padding:14px 16px; border-radius:14px; background:#ffe9f1; }
.glow-sd-cheerbox b{ display:block; font-size:15.5px; font-weight:800; color:var(--pink); }
.glow-sd-cheerbox span{ display:block; margin-top:2px; font-size:13.5px; color:#5f4a58; font-weight:500; }

/* ---------- share ---------- */
.glow-sd-share-sub{ margin:8px 0 14px; font-size:14px; color:var(--muted); }
.glow-sd-share-grid2{ display:flex; gap:18px; align-items:flex-start; }
.glow-sd-share-links{ flex:1 1 auto; min-width:0; }
.glow-sd-linkbox{ display:flex; align-items:center; gap:8px; padding:5px 5px 5px 14px; border:1.5px solid #e5d9f5; border-radius:12px; background:#faf7ff; }
.glow-sd-linkbox input{ flex:1 1 auto; min-width:0; border:0 !important; outline:0; background:transparent !important; font-family:inherit; font-size:14.5px; font-weight:500; color:#4a3fd0; padding:6px 0; box-shadow:none !important; height:auto; margin:0; }
.glow-sd button.glow-sd-copy{ flex:0 0 auto; padding:10px 18px; border-radius:10px; background:linear-gradient(180deg,var(--pink-hi),var(--pink)) !important; color:#fff !important; font-size:13px; font-weight:800; letter-spacing:.05em; border:0 !important; }
.glow-sd button.glow-sd-copy.is-done{ background:var(--teal) !important; }
.glow-sd-qr-wrap{ position:relative; flex:0 0 auto; display:flex; align-items:flex-start; gap:6px; padding-top:2px; }
.glow-sd-qr-arrow{ color:var(--teal); margin-top:-4px; }
.glow-sd-qr-item{ text-align:center; }
.glow-sd-qr{ width:88px; height:88px; display:block; margin:0 auto 4px; border:1px solid var(--line); border-radius:8px; padding:4px; background:#fff; }
.glow-sd-qr-item span{ display:block; font-size:11.5px; font-weight:600; color:#4b4960; line-height:1.3; }
.glow-sd-share-btns{ display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-top:16px; }
.glow-sd a.glow-sd-sbtn{ display:flex; align-items:center; justify-content:center; gap:9px; padding:13px 10px; border-radius:12px;
	color:#fff !important; font-size:14px; font-weight:700; box-shadow:0 6px 16px rgba(20,18,45,.12); white-space:nowrap;
	border:0 !important; text-decoration:none !important; line-height:1.2; }
.glow-sd a.glow-sd-sbtn:hover{ filter:brightness(1.06); }
.glow-sd a.glow-sd-sbtn.is-text{ background:linear-gradient(180deg,#f5176f,#e00c5e) !important; }
.glow-sd a.glow-sd-sbtn.is-fb{ background:linear-gradient(180deg,#3d6cf5,#6a48ea) !important; }
.glow-sd a.glow-sd-sbtn.is-ig{ background:linear-gradient(135deg,#f9ce34 0%,#ee2a7b 48%,#6228d7 100%) !important; }
.glow-sd a.glow-sd-sbtn svg{ flex:0 0 auto; }

/* ---------- action tiles ---------- */
.glow-sd a.glow-sd-tile,
.glow-sd span.glow-sd-tile{ position:relative; display:flex; align-items:flex-start; gap:14px; padding:20px 18px; border-radius:18px; width:100%; text-align:left;
	border:1px solid transparent !important; box-shadow:0 2px 6px rgba(22,20,50,.05); text-decoration:none !important; }
.glow-sd a.glow-sd-tile:hover{ transform:translateY(-2px); box-shadow:0 8px 20px rgba(22,20,50,.10); }
.glow-sd-tile-icon{ flex:0 0 52px; width:52px; height:52px; border-radius:50%; display:grid; place-items:center; }
.glow-sd-tile-txt{ flex:1 1 auto; min-width:0; }
.glow-sd-tile-txt strong{ display:block; margin-bottom:6px; font-size:15.5px; font-weight:800; line-height:1.22; letter-spacing:.01em; }
.glow-sd-tile-txt span{ display:block; font-size:13px; line-height:1.42; color:#5f5d75; }
.glow-sd-tile-chev{ position:absolute; right:14px; bottom:14px; width:26px; height:26px; border-radius:50%; display:grid; place-items:center; background:#fff; }
.glow-sd a.glow-sd-tile.is-store,.glow-sd span.glow-sd-tile.is-store{ background:#ffe4ee !important; border-color:#fbd0e0 !important; }
.glow-sd-tile.is-store .glow-sd-tile-icon{ background:var(--pink); }
.glow-sd-tile.is-store strong,.glow-sd-tile.is-store .glow-sd-tile-chev{ color:var(--pink); }
.glow-sd a.glow-sd-tile.is-toolbox{ background:#ebe7fd !important; border-color:#d8d0f8 !important; }
.glow-sd-tile.is-toolbox .glow-sd-tile-icon{ background:var(--purple); }
.glow-sd-tile.is-toolbox strong,.glow-sd-tile.is-toolbox .glow-sd-tile-chev{ color:var(--purple); }
.glow-sd a.glow-sd-tile.is-brochure,.glow-sd span.glow-sd-tile.is-brochure{ background:#ffe9db !important; border-color:#fcd6bd !important; }
.glow-sd-tile.is-brochure .glow-sd-tile-icon{ background:var(--orange); }
.glow-sd-tile.is-brochure strong,.glow-sd-tile.is-brochure .glow-sd-tile-chev{ color:#e05f07; }
.glow-sd a.glow-sd-tile.is-reports{ background:#fdf3d3 !important; border-color:#f7e4a8 !important; }
.glow-sd-tile.is-reports .glow-sd-tile-icon{ background:var(--amber); }
.glow-sd-tile.is-reports strong,.glow-sd-tile.is-reports .glow-sd-tile-chev{ color:#a86f00; }
.glow-sd-tile.is-locked{ filter:grayscale(.55); opacity:.72; cursor:not-allowed; }

/* ---------- lower grid ---------- */
.glow-sd-lower{ display:grid; grid-template-columns:1fr 330px; gap:18px; align-items:stretch; }
.glow-sd-lower-main{ display:flex; flex-direction:column; gap:18px; min-width:0; }
.glow-sd-lower-side{ display:flex; flex-direction:column; gap:18px; }

/* checklist */
.glow-sd-checklist{ display:flex; align-items:center; gap:26px; }
.glow-sd-checklist-intro{ flex:0 0 auto; max-width:250px; }
.glow-sd-checklist-intro p{ margin:6px 0 0 35px; font-size:13.5px; color:var(--muted); }
.glow-sd-steps{ flex:1 1 auto; position:relative; display:grid; grid-template-columns:repeat(4,1fr); gap:10px; }
.glow-sd-steps::before{ content:""; position:absolute; left:12%; right:12%; top:23px; height:3px; border-radius:3px; background:#e7e5ef; }
.glow-sd-step{ position:relative; display:flex; flex-direction:column; align-items:center; gap:8px; text-align:center; padding:6px 8px 10px; border-radius:14px; }
.glow-sd-step.is-current{ background:#ffe9f2; }
.glow-sd a.glow-sd-step-num{ width:46px; height:46px; border-radius:50%; display:grid; place-items:center; font-size:19px; font-weight:800; background:#e2e0ea; color:#8d8aa0 !important; position:relative; z-index:2; text-decoration:none !important; }
.glow-sd-step.is-done a.glow-sd-step-num{ background:var(--teal); color:#fff !important; box-shadow:0 4px 12px rgba(23,185,138,.4); }
.glow-sd-step.is-current a.glow-sd-step-num{ background:var(--pink); color:#fff !important; box-shadow:0 4px 14px rgba(236,15,99,.45); }
.glow-sd-step-num span{ color:inherit; }
.glow-sd-step-label{ font-size:13.5px; line-height:1.3; color:#3d3b52; font-weight:600; max-width:110px; }
.glow-sd-step-check{ width:20px; height:20px; border-radius:50%; display:grid; place-items:center; background:#d7d5de; color:#fff; }
.glow-sd-step.is-done .glow-sd-step-check{ background:var(--teal); }
.glow-sd a.glow-sd-step-badge{ display:inline-block; padding:6px 16px; border-radius:999px; background:linear-gradient(180deg,var(--pink-hi),var(--pink)) !important; color:#fff !important; font-size:12px; font-weight:800; letter-spacing:.05em; box-shadow:0 5px 14px rgba(236,15,99,.4); text-decoration:none !important; }

/* snapshot */
.glow-sd-fullreport{ margin-left:auto; font-size:14px; font-weight:700; color:#2f4fd8 !important; text-decoration:underline; white-space:nowrap; }
.glow-sd-snap-grid{ display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-top:16px; }
.glow-sd-snap{ display:flex; align-items:center; gap:12px; padding:14px; border-radius:14px; }
.glow-sd-snap.is-pink{ background:#ffe9f1; }  .glow-sd-snap.is-pink  .glow-sd-snap-icon{ background:var(--pink); }
.glow-sd-snap.is-blue{ background:#e5effc; }  .glow-sd-snap.is-blue  .glow-sd-snap-icon{ background:var(--blue); }
.glow-sd-snap.is-green{ background:#e2f6ec; } .glow-sd-snap.is-green .glow-sd-snap-icon{ background:var(--teal); }
.glow-sd-snap.is-purple{ background:#efeafc; }.glow-sd-snap.is-purple .glow-sd-snap-icon{ background:var(--purple); }
.glow-sd-snap-icon{ flex:0 0 42px; width:42px; height:42px; border-radius:50%; display:grid; place-items:center; color:#fff; font-size:19px; font-weight:800; }
.glow-sd-snap b{ display:block; font-size:21px; font-weight:800; letter-spacing:-.02em; line-height:1.1; }
.glow-sd-snap b.is-name{ font-size:16px; color:#4a3fd0; }
.glow-sd-snap span{ display:flex; align-items: center; justify-content: center; font-size:12.5px; line-height:1.3; color:#5f5d75; font-weight:500; }
.glow-sd-snap span.glow-sd-snap-icon{
	color:#fff;
} 
.glow-sd-snap-top{ font-size:12px !important; font-weight:700 !important; color:#8d8aa0 !important; }

/* tools */
.glow-sd-tools-grid{ display:grid; grid-template-columns:repeat(6,1fr); gap:10px; margin-top:16px; }
.glow-sd-tools-grid a{ display:flex; flex-direction:column; align-items:center; gap:8px; padding:14px 8px; border:1px solid var(--line); border-radius:14px; text-align:center; font-size:12.5px; line-height:1.3; font-weight:600; color:#3d3b52 !important; text-decoration:none !important; }
.glow-sd-tools-grid a:hover{ border-color:#f0c8da; background:#fff8fb; }
.glow-sd-tool-ic{ width:38px; height:38px; border-radius:12px; display:grid; place-items:center; }
.glow-sd-tool-ic.is-pink{ background:var(--pink); }
.glow-sd-tool-ic.is-orange{ background:var(--orange); }
.glow-sd-tool-ic.is-purple{ background:var(--purple); }
.glow-sd-tool-ic.is-blue{ background:var(--blue); }
.glow-sd-tool-ic.is-amber{ background:var(--amber); }
.glow-sd-tool-ic.is-teal{ background:var(--teal); }

/* keep-going card */
.glow-sd-thanks{ position:relative; flex:1 1 auto; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center;
	background:linear-gradient(165deg,#fff6ec,#ffe9dc 55%,#ffe0e9); border-color:#f9d8cf; padding:34px 24px 44px; overflow:hidden; }
.glow-sd-thanks-script .a{ display:block; font-family:'Caveat',cursive; font-weight:700; font-size:38px; line-height:1.05; color:#1a1330; transform:rotate(-3deg); }
.glow-sd-underline{ display:block; margin:6px auto 0; transform:rotate(-3deg); }
.glow-sd-thanks p{ margin:20px 0 0; font-size:15.5px; line-height:1.55; color:#5b4a5c; font-weight:500; max-width:230px; }
.glow-sd-th-heart{ position:absolute; color:#ff5f95; }
.glow-sd-th-heart.h1{ left:16px; top:18px; font-size:17px; transform:rotate(-16deg); }
.glow-sd-th-heart.h2{ right:20px; top:30px; font-size:14px; transform:rotate(14deg); }
.glow-sd-th-heart.h3{ right:30px; bottom:78px; font-size:16px; transform:rotate(20deg); }
.glow-sd-th-spark{ position:absolute; color:#ffb229; }
.glow-sd-th-spark.s1{ left:26px; top:52px; font-size:15px; }
.glow-sd-th-spark.s2{ right:44px; top:14px; font-size:13px; color:#4fd6ff; }
.glow-sd-confetti{ position:absolute; width:14px; height:3.5px; border-radius:3px; }

/* settings */
.glow-sd-settings .glow-sd-card-head{ margin-bottom:10px; }
.glow-sd a.glow-sd-setrow{ display:flex; align-items:center; justify-content:space-between; gap:10px; width:100%; padding:13px 14px; margin-top:8px;
	border:1px solid var(--line) !important; border-radius:12px; font-size:14.5px; font-weight:600; color:#3d3b52 !important; text-align:left; text-decoration:none !important; background:#fff !important; }
.glow-sd a.glow-sd-setrow:hover{ border-color:#d9d3fb !important; background:#f7f5ff !important; }

/* brochure order section */
.glow-sd-brochures{ scroll-margin-top:20px; }
.glow-sd-brochure-rows{ margin-top:14px; border:1px solid var(--line); border-radius:16px; overflow:hidden; }
.glow-sd-brochure-row{ display:flex; align-items:center; justify-content:space-between; gap:16px; padding:16px 18px; border-bottom:1px solid #f0eff4; font-size:16px; font-weight:500; color:var(--ink); }
.glow-sd-brochure-row:last-child{ border-bottom:0; }
.glow-sd-brochure-row:hover{ background:#fdfcfe; }
.glow-sd a.glow-sd-enter{
	flex:0 0 auto; display:inline-block; min-width:140px; text-align:center;
	padding:10px 24px; border-radius:999px;
	background:linear-gradient(180deg,#1cc496,var(--teal)) !important;
	color:#fff !important; font-size:13px; font-weight:700; letter-spacing:.5px;
	text-transform:uppercase; text-decoration:none !important; border:0 !important;
}
.glow-sd a.glow-sd-enter:hover{ filter:brightness(1.07); }
.glow-sd-brochure-note{ margin:14px 0 0; font-size:15px; color:#5b5972; }

@media(max-width:580px){
	.glow-sd-brochure-row{ flex-direction:column; align-items:flex-start; gap:10px; }
	.glow-sd a.glow-sd-enter{ width:100%; }
}

/* tables */
.glow-sd-table-wrap{ margin-top:14px; overflow-x:auto; }
.glow-sd-table{ width:100%; border-collapse:collapse; font-size:14.5px; }
.glow-sd-table th{ padding:10px 12px; text-align:left; font-size:12px; font-weight:700; letter-spacing:.04em; text-transform:uppercase; color:#8d8aa0; border-bottom:1px solid var(--line); white-space:nowrap; }
.glow-sd-table td{ padding:12px; border-bottom:1px solid #f0eff4; vertical-align:middle; }
.glow-sd-table tr:hover td{ background:#faf9fc; }
.glow-sd-table a{ color:#4a3fd0 !important; text-decoration:underline; }
.glow-sd-tag{ display:inline-block; padding:4px 11px; border-radius:999px; font-size:12.5px; font-weight:700; background:#f1eff6; color:#5d5a75; white-space:nowrap; }
.glow-sd-tag.is-completed{ background:#eaf8f1; color:#0d6b50; }
.glow-sd-tag.is-processing{ background:#f0edfd; color:#4030a8; }
.glow-sd-tag.is-waiting-call{ background:#fff4e7; color:#8a4a08; }
.glow-sd-tag.is-on-hold{ background:#fff4e7; color:#8a4a08; }
.glow-sd-tag.is-green{ background:#eaf8f1; color:#0d6b50; }
.glow-sd-tag.is-amber{ background:#fff4e7; color:#8a4a08; }
.glow-sd-tag.is-purple{ background:#f0edfd; color:#4030a8; }
.glow-sd button.glow-sd-mini{ display:inline-block; padding:5px 14px; border-radius:8px; background:var(--teal) !important; color:#fff !important; font-size:13px; font-weight:600; border:0 !important; }
.glow-sd-flag{ display:block; margin-top:3px; font-style:normal; font-size:11.5px; font-weight:700; color:#8d8aa0; }
.glow-view{ display:block; margin-top:2px; font-size:12px; color:#4a3fd0 !important; text-decoration:underline; }

/* expandable item rows */
.glow-sd-items-row td{ background:#faf9fc; padding:0 12px 12px !important; }
.glow-sd-items{ border:1px solid var(--line); border-radius:12px; background:#fff; overflow:hidden; }
.glow-sd-item-row{ display:flex; align-items:center; gap:14px; padding:9px 14px; font-size:13.5px; color:#4f4d66; border-bottom:1px dotted #eceaf1; }
.glow-sd-item-row:last-child{ border-bottom:0; }
.glow-sd-item-row.is-head{ font-size:11.5px; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:#8d8aa0; background:#faf9fc; }
.glow-sd-item-row.is-total{ font-weight:700; color:var(--ink); background:#faf9fc; }
.glow-sd-i-sku{ flex:0 0 90px; font-weight:700; color:#8d8aa0; font-size:11.5px; }
.glow-sd-i-name{ flex:1 1 0; min-width:0; }
.glow-sd-i-qty{ flex:0 0 60px; text-align:center; }
.glow-sd-i-total{ flex:0 0 80px; text-align:right; font-weight:700; color:var(--ink); }

@media(max-width:640px){
	.glow-sd-item-row{ flex-wrap:wrap; gap:6px 10px; }
	.glow-sd-i-sku{ flex:0 0 auto; }
	.glow-sd-i-name{ flex:1 1 100%; order:-1; font-weight:600; color:var(--ink); }
}

/* toast */
.glow-sd-toast{ position:fixed; left:50%; bottom:34px; transform:translate(-50%,20px); background:#16152b; color:#fff; padding:13px 22px; border-radius:12px; font-size:15px; font-weight:600; box-shadow:0 12px 30px rgba(0,0,0,.24); opacity:0; pointer-events:none; transition:opacity .2s ease,transform .2s ease; z-index:99999; }
.glow-sd-toast.is-open{ opacity:1; transform:translate(-50%,0); }

/* responsive */
@media(max-width:1420px){
	.glow-sd-row1{ grid-template-columns:.9fr 1.4fr; }
	.glow-sd-row1 .glow-sd-share{ grid-column:1/-1; }
	.glow-sd-row2{ grid-template-columns:1fr 1fr; }
	.glow-sd-snap-grid{ grid-template-columns:1fr 1fr; }
	.glow-sd-tools-grid{ grid-template-columns:repeat(3,1fr); }
}
@media(max-width:1120px){
	.glow-sd-slogan .glow-sd-stack{ display:none; }
	.glow-sd-lower{ grid-template-columns:1fr; }
	.glow-sd-lower-side{ flex-direction:row; }
	.glow-sd-lower-side > *{ flex:1 1 0; }
	.glow-sd-checklist{ flex-direction:column; align-items:flex-start; gap:16px; }
	.glow-sd-checklist-intro{ max-width:none; }
	.glow-sd-steps{ width:100%; }
}
@media(max-width:820px){
	.glow-sd-top{ flex-wrap:wrap; gap:16px; padding:20px; }
	.glow-sd-top-rule{ display:none; }
	.glow-sd-slogan{ justify-content:center; width:100%; }
	.glow-sd-board{ padding:14px 12px 24px; gap:14px; }
	.glow-sd-row{ gap:14px; grid-template-columns:1fr !important; }
	.glow-sd-row1 .glow-sd-share{ grid-column:auto; }
	.glow-sd-goal-body{ flex-direction:column; }
	.glow-sd-raised{ width:100%; text-align:center; }
	.glow-sd-bar-goal{ text-align:center; }
	.glow-sd-share-grid2{ flex-direction:column; }
	.glow-sd-qr-wrap{ justify-content:center; width:100%; }
	.glow-sd-qr-arrow{ display:none; }
	.glow-sd-share-btns{ grid-template-columns:1fr; }
	.glow-sd-steps{ grid-template-columns:1fr 1fr; }
	.glow-sd-steps::before{ display:none; }
	.glow-sd-snap-grid{ grid-template-columns:1fr; }
	.glow-sd-tools-grid{ grid-template-columns:1fr 1fr; }
	.glow-sd-lower-side{ flex-direction:column; }
}
@media(prefers-reduced-motion:reduce){ .glow-sd *{ transition-duration:.001ms !important; } }
</style>

<?php get_footer(); ?>