<?php
/**
 * Template Name: GLOW Seller Dashboard
 *
 * SINGLE FILE — data layer + markup together, like your original template.
 *
 * Still needed elsewhere (leave them where they already are):
 *   - glow_master_allows() / glow_seller_allows()
 *   - glow_so_get_seller_orders() / glow_so_get_items() / glow_so_catalog_label()
 *   - glow_so_get_seller_wc_orders()
 *
 * Same three adjustments as the fundraiser template, because a page template
 * never loads during admin-ajax or admin requests:
 *   1. The checklist saves through a nonced link + redirect, not AJAX.
 *   2. The stats cache clears on a 5-minute timer or the Refresh link.
 *      Search "OPTIONAL" below for the hook version.
 *   3. The webfont is a <link> tag, not wp_enqueue_style().
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* =========================================================================
   PART 1 — DATA LAYER
   ========================================================================= */

/* -------------------------------------------------------------------------
   Brochure permission switches.

   These were defined at the top of the OLD seller template, so replacing that
   file removed them. They are guarded, so if you have also moved them into
   functions.php that copy wins and these are ignored.
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
			'instructions' => 'https://glowscentedcandles.com/wp-content/uploads/2020/10/Fundraiser-Dashboard-Glow-Scented-Candles-4-copy.pdf',
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

/**
 * The original read $_GET['frid'] with no check, so any logged-in seller could
 * open another seller's sales and their buyers' names. Admin-only now.
 */
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
	return array(
		'store'  => 'Customize your store',
		'link'   => 'Share your link',
		'text'   => 'Send a text',
		'social' => 'Post to social',
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

if ( ! function_exists( 'glow_sd_sparkline' ) ) :
function glow_sd_sparkline( $days, $w = 300, $h = 132 ) {

	$values = array_slice( array_values( $days ), -7 );
	if ( count( $values ) < 2 ) $values = array( 0, 0 );

	$max = max( $values );
	if ( $max <= 0 ) $max = 1;

	$pad_x = 10; $pad_t = 12; $pad_b = 14;
	$step  = ( $w - ( $pad_x * 2 ) ) / ( count( $values ) - 1 );
	$plot  = $h - $pad_t - $pad_b;

	$points = array();
	foreach ( $values as $i => $v ) {
		$points[] = array(
			round( $pad_x + ( $i * $step ), 1 ),
			round( $pad_t + ( $plot - ( ( $v / $max ) * $plot ) ), 1 ),
		);
	}

	$line = array();
	foreach ( $points as $p ) $line[] = $p[0] . ',' . $p[1];

	$first = $points[0];
	$last  = end( $points );

	$area = 'M' . $first[0] . ',' . $first[1];
	foreach ( array_slice( $points, 1 ) as $p ) $area .= ' L' . $p[0] . ',' . $p[1];
	$area .= ' L' . $last[0] . ',' . $h . ' L' . $first[0] . ',' . $h . ' Z';

	return array(
		'points' => $points,
		'line'   => implode( ' ', $line ),
		'area'   => $area,
		'w'      => $w,
		'h'      => $h,
	);
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
   PART 2 — RENDER
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
$raised = $stats['total_profit'];
$pct    = ( $goal > 0 ) ? min( 100, round( ( $raised / $goal ) * 100 ) ) : 0;
$left   = max( 0, $goal - $raised );

$shop_link = glow_sd_shop_link( $sid );
$link_show = preg_replace( '#^https?://#', '', $shop_link );
$qr        = get_user_meta( $sid, glow_sd_meta( 'qr' ), true );

$spark = glow_sd_sparkline( $stats['days'] );

$can_brochure = glow_seller_allows( $sid, $fid );

$share_text = sprintf( 'Support %s — shop GLOW candles through my link:', $campaign['org'] );

$cheer = ( $goal <= 0 )   ? "Every sale counts! 🎉"
       : ( ( $pct >= 100 ) ? "You hit your goal! 🎉"
       : ( ( $pct >= 50 )  ? "You're doing amazing! 🎉" : "Great start — keep going! 🎉" ) );

$is_admin_view = glow_sd_is_admin_view( $sid );
?>

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Inter:wght@400;500;600;700;800&display=swap">

<div class="glow-sd">

	<!-- ============ HEADER ============ -->
	<header class="glow-sd-top">

		<div class="glow-sd-brand">
			<div class="glow-sd-brand-word">
				<h2>GLOW</h2>
				<svg width="26" height="26" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 1.5c.6 3.9 2.1 5.4 6 6-3.9.6-5.4 2.1-6 6-.6-3.9-2.1-5.4-6-6 3.9-.6 5.4-2.1 6-6Z" fill="#ff2d7e"/></svg>
			</div>
			<p class="glow-sd-brand-sub"><i></i><span>SCENTED CANDLES</span><i></i></p>
		</div>

		<div class="glow-sd-top-rule" aria-hidden="true"></div>

		<div class="glow-sd-campaign">
			<h1><?php echo esc_html( $campaign['org'] ); ?></h1>

			<div class="glow-sd-meta">
				<?php if ( $campaign['closed'] ) : ?>
					<span class="glow-sd-pill is-off">Campaign Closed</span>
				<?php elseif ( $campaign['active'] ) : ?>
					<span class="glow-sd-pill is-on">Campaign Active</span>
				<?php else : ?>
					<span class="glow-sd-pill is-warn">End date passed</span>
				<?php endif; ?>

				<?php if ( $campaign['start_fmt'] ) : ?>
					<span class="glow-sd-dates">
						<svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.7" stroke-linecap="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="3"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
						<?php echo esc_html( $campaign['start_fmt'] . ' – ' . $campaign['end_fmt'] ); ?>
					</span>
				<?php endif; ?>

				<span class="glow-sd-meta-rule" aria-hidden="true"></span>

				<span class="glow-sd-code">
					Fundraiser code
					<b><?php echo esc_html( $fid ); ?></b>
					<button type="button" class="glow-sd-code-copy" data-copy="<?php echo esc_attr( $fid ); ?>">Copy</button>
				</span>

				<?php if ( $campaign['active'] ) : ?>
					<p class="glow-sd-countdown">
						<b><?php echo esc_html( $campaign['days_left'] ); ?>
						<?php echo esc_html( _n( 'day left', 'days left', $campaign['days_left'] ) ); ?></b>—share today to reach your goal.
					</p>
				<?php endif; ?>
			</div>
		</div>

		<div class="glow-sd-decor" aria-hidden="true">
			<div class="glow-sd-candles">
				<div class="glow-sd-candle is-red"><div class="wick"></div><div class="flame"></div><div class="glass"><div class="rim"></div></div><div class="label">GLOW</div></div>
				<div class="glow-sd-candle is-snow"><div class="wick"></div><div class="flame"></div><div class="glass"><div class="rim"></div></div><div class="label">SNOW DAY<small>Frost · Coconut · Pine</small></div></div>
				<div class="glow-sd-candle is-white"><div class="wick"></div><div class="flame"></div><div class="glass"><div class="rim"></div></div><div class="label">GLOW</div></div>
			</div>
			<span class="glow-sd-confetti" style="left:130px;top:44px;background:#ff2d7e;transform:rotate(28deg);"></span>
			<span class="glow-sd-confetti" style="left:146px;top:66px;background:#ff2d7e;transform:rotate(28deg);"></span>
			<span class="glow-sd-confetti" style="left:120px;top:88px;background:#ff2d7e;transform:rotate(28deg);"></span>
			<span class="glow-sd-confetti" style="right:74px;top:24px;background:#ffd166;transform:rotate(-38deg);"></span>
			<span class="glow-sd-confetti" style="right:44px;top:44px;background:#4fd6ff;transform:rotate(-38deg);"></span>
			<span class="glow-sd-confetti" style="right:34px;top:74px;background:#a06bff;transform:rotate(-30deg);"></span>
		</div>
	</header>

	<main class="glow-sd-board">

		<!-- ---------- NOTICES ---------- -->
		<?php if ( $is_admin_view ) : ?>
			<div class="glow-sd-notice is-admin">Admin view — you are looking at seller #<?php echo esc_html( $sid ); ?>.</div>
		<?php endif; ?>

		<?php if ( $campaign['closed'] ) : ?>
			<div class="glow-sd-notice is-done">
				This fundraiser has been closed out. Sales profits are sent to your chairperson on the 15th of the month.
				<a href="#" id="rebook_ajax" data-id="<?php echo esc_attr( $sid ); ?>">Register for a new campaign</a>
			</div>
		<?php endif; ?>

		<?php if ( glow_sd_needs_profile( $sid ) ) : ?>
			<div class="glow-sd-notice is-todo">
				Add your photo and a short message — sellers with both raise noticeably more.
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
				<div>
					<div class="glow-sd-profile-top">
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
						<div>
							<h3 class="glow-sd-hi">
								Hi, <?php echo esc_html( $seller['first'] ); ?>!
								<svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2c.6 4.4 2.1 6 6 6.6-3.9.6-5.4 2.2-6 6.6-.6-4.4-2.1-6-6-6.6 3.9-.6 5.4-2.2 6-6.6Z" fill="#ff2d7e"/></svg>
							</h3>
							<p class="glow-sd-role">
								Seller<br>
								<?php if ( $campaign['chair'] ) : ?>
									Chair: <?php echo esc_html( $campaign['chair'] ); ?>
								<?php endif; ?>
							</p>
						</div>
					</div>
				</div>

				<div class="glow-sd-profile-actions">
					<a class="glow-sd-profile-btn" href="<?php echo esc_url( $shop_link ); ?>" target="_blank" rel="noopener">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2c2340" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4.5 20c1.4-4.2 4.2-6 7.5-6s6.1 1.8 7.5 6"/></svg>
						View My Store
						<svg class="chev" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2c2340" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg>
					</a>
					<a class="glow-sd-profile-link" href="<?php echo esc_url( add_query_arg( 'seller_ID', $sid, glow_sd_url( 'edit_info' ) ) ); ?>">Edit my info</a>
				</div>
			</div>

			<!-- Goal -->
			<div class="glow-sd-card glow-sd-goal">
				<div class="glow-sd-donut">
					<div class="glow-sd-donut-in">
						<span class="amt">$<?php echo esc_html( number_format( $raised, 0 ) ); ?></span>
						<span class="of">of $<?php echo esc_html( number_format( $goal, 0 ) ); ?><br>goal</span>
					</div>
				</div>

				<div class="glow-sd-goal-body">
					<div class="glow-sd-goal-eyebrow">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
							<circle cx="11" cy="13" r="8" stroke="#ec0f63" stroke-width="1.8"/>
							<circle cx="11" cy="13" r="3.4" stroke="#ec0f63" stroke-width="1.8"/>
							<path d="m14.5 9.5 5-5M17 4.5h3v3" stroke="#ec0f63" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						MY GOAL
					</div>

					<div class="glow-sd-goal-target">$<?php echo esc_html( number_format( $goal, 0 ) ); ?></div>

					<div class="glow-sd-goal-bar">
						<div class="glow-sd-bar"><span style="width:<?php echo esc_attr( $pct ); ?>%"></span></div>
						<div class="glow-sd-pct"><?php echo esc_html( $pct ); ?>%</div>
					</div>

					<p class="glow-sd-cheer"><?php echo esc_html( $cheer ); ?></p>
					<p class="glow-sd-cheer-sub">
						<?php if ( $goal > 0 && $left > 0 ) : ?>
							$<?php echo esc_html( number_format( $left, 2 ) ); ?> to go — keep sharing!
						<?php else : ?>
							Keep sharing—you've got this!
						<?php endif; ?>
					</p>
				</div>
			</div>

			<!-- Share -->
			<div class="glow-sd-card glow-sd-share">
				<div class="glow-sd-share-main">
					<div class="glow-sd-head">
						<svg width="26" height="26" viewBox="0 0 24 24" aria-hidden="true"><path d="M21.5 3 2.8 10.4c-.9.4-.8 1.6.1 1.8l4.6 1.3 1.4 4.8c.3.9 1.5 1 1.9.2L21.5 3Z" fill="#17b98a"/><path d="M21.5 3 9.1 18.5l-.5-4.9L21.5 3Z" fill="#0f8f6b"/></svg>
						<h3>Share My Link</h3>
					</div>
					<p class="glow-sd-share-sub">Every sale through this link is credited to you automatically.</p>

					<div class="glow-sd-linkbox">
						<input id="glow-sd-link" type="text" readonly value="<?php echo esc_attr( $link_show ); ?>" aria-label="Your store link">
						<button type="button" class="glow-sd-copy" data-copy="<?php echo esc_attr( $shop_link ); ?>">Copy</button>
					</div>

					<div class="glow-sd-share-grid">
						<a class="glow-sd-sbtn is-text" href="sms:?&body=<?php echo rawurlencode( $share_text . ' ' . $shop_link ); ?>">
							<svg width="21" height="21" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M12 3c5 0 9 3.3 9 7.4 0 4.1-4 7.4-9 7.4-1 0-2-.1-2.9-.4L4 19.5l1.2-3.3C3.8 14.8 3 12.7 3 10.4 3 6.3 7 3 12 3Z"/></svg>
							Text My Link
						</a>

						<a class="glow-sd-sbtn is-wa" target="_blank" rel="noopener" href="https://wa.me/?text=<?php echo rawurlencode( $share_text . ' ' . $shop_link ); ?>">
							<svg width="21" height="21" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.5 15.2L2 22l4.9-1.4A10 10 0 1 0 12 2Zm5.3 14c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .1-1.7-.1a12 12 0 0 1-5.4-4.6c-.4-.6-.9-1.5-.9-2.4 0-.9.5-1.4.7-1.6.2-.2.4-.3.6-.3h.5c.2 0 .4 0 .6.5l.7 1.8c.1.2 0 .4-.1.5l-.4.5c-.1.1-.3.3-.1.6.2.3.7 1.2 1.5 1.9 1 .9 1.8 1.1 2.1 1.3.2.1.4 0 .5-.1l.7-.8c.2-.2.3-.2.6-.1l1.6.8c.3.1.5.2.5.3.1.2.1.6 0 1.1Z"/></svg>
							WhatsApp
						</a>

						<a class="glow-sd-sbtn is-fb" target="_blank" rel="noopener"
						   href="https://www.facebook.com/sharer.php?u=<?php echo rawurlencode( $shop_link ); ?>">
							<svg width="21" height="21" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.2c-1.2 0-1.6.8-1.6 1.6V12h2.7l-.4 2.9h-2.3v7A10 10 0 0 0 22 12Z"/></svg>
							Share on Facebook
						</a>

						<a class="glow-sd-sbtn is-ig" data-ig href="https://www.instagram.com/" target="_blank" rel="noopener">
							<svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5.4"/><circle cx="12" cy="12" r="4.1"/><circle cx="17.3" cy="6.7" r="1.2" fill="#fff" stroke="none"/></svg>
							Share on Instagram
						</a>
					</div>
				</div>

				<div class="glow-sd-qr-wrap">
					<?php if ( $qr ) : ?>
						<svg class="glow-sd-qr-arrow" width="70" height="52" viewBox="0 0 70 52" fill="none" aria-hidden="true">
							<path d="M4 6c22-2 44 6 55 26" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
							<path d="M52 24l8 9 3-11" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						<img class="glow-sd-qr" src="<?php echo esc_url( $qr ); ?>" alt="QR code for your store link" loading="lazy">
						<a class="glow-sd-qr-dl" href="<?php echo esc_url( $qr ); ?>" download>Download</a>
					<?php else : ?>
						<p class="glow-sd-qr-empty">Your QR code appears here once it's generated.</p>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<!-- ---------- ROW 2 ---------- -->
		<section class="glow-sd-row glow-sd-row2">

			<a class="glow-sd-tile is-store" href="<?php echo esc_url( glow_sd_url( 'customize' ) ); ?>">
				<span class="glow-sd-tile-icon">
					<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.5" stroke-linejoin="round" aria-hidden="true"><path d="M3.6 4.5h16.8l1.4 4.2a3 3 0 0 1-5.7 1.6 3 3 0 0 1-5.6 0 3 3 0 0 1-5.7-1.6L3.6 4.5Z"/><path d="M4.8 11.4V20h14.4v-8.6M10 20v-5h4v5"/></svg>
				</span>
				<span class="glow-sd-tile-txt">
					<strong>Customize My Store</strong>
					<span>Add your photo, story &amp; personalize your storefront.</span>
				</span>
				<span class="glow-sd-tile-chev"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg></span>
			</a>

			<?php if ( $can_brochure ) : ?>
				<a class="glow-sd-tile is-brochure" href="#glow-sd-brochures">
			<?php else : ?>
				<span class="glow-sd-tile is-brochure is-locked" title="Your chairperson has not opened brochure orders yet">
			<?php endif; ?>
				<span class="glow-sd-tile-icon">
					<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.5" stroke-linejoin="round" aria-hidden="true"><path d="M12 6.5C10.3 5.2 8 4.6 4.5 4.8v13c3.5-.2 5.8.4 7.5 1.7 1.7-1.3 4-1.9 7.5-1.7v-13c-3.5-.2-5.8.4-7.5 1.7Z"/><path d="M12 6.5v13"/></svg>
				</span>
				<span class="glow-sd-tile-txt">
					<strong>Submit Brochure Order</strong>
					<span>Enter the paper orders you collected.</span>
					<span class="glow-sd-badge">
						<?php
						if ( ! $can_brochure )            echo 'Not open yet';
						elseif ( $campaign['closed'] )    echo 'Campaign closed';
						else                              echo 'Available!';
						?>
					</span>
				</span>
			<?php echo $can_brochure ? '</a>' : '</span>'; ?>

			<a class="glow-sd-tile is-report" href="<?php echo esc_url( glow_sd_url( 'report' ) ); ?>">
				<span class="glow-sd-tile-icon">
					<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#4a3300" stroke-width="1.5" stroke-linejoin="round" aria-hidden="true"><path d="M5 3.5h9l5 5V20.5H5z"/><path d="M14 3.5v5h5M8.5 13v4M12 11v6M15.5 14v3"/></svg>
				</span>
				<span class="glow-sd-tile-txt">
					<strong>My Sales Report</strong>
					<span>Every order credited to you, in detail.</span>
				</span>
				<span class="glow-sd-tile-chev"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#6b4a00" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg></span>
			</a>

			<div class="glow-sd-card glow-sd-checklist">
				<div class="glow-sd-checklist-intro">
					<div class="glow-sd-head">
						<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#3d3b52" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4.5" y="3.5" width="15" height="17" rx="2.5"/><path d="M9 3.5h6v2.6H9zM8.6 11h6.8M8.6 15h4.6"/></svg>
						<div>
							<h3>Quick-Start Checklist</h3>
							<p>Complete all 4 to boost your success!</p>
						</div>
					</div>
				</div>

				<div class="glow-sd-steps">
					<?php $n = 0; foreach ( glow_sd_checklist_steps() as $k => $label ) : $n++; ?>
						<a class="glow-sd-step" href="<?php echo esc_url( glow_sd_step_url( $k ) ); ?>"
						   aria-pressed="<?php echo $checklist[ $k ] ? 'true' : 'false'; ?>">
							<span class="glow-sd-step-num"><span><?php echo esc_html( $n ); ?></span></span>
							<span class="glow-sd-step-label"><?php echo esc_html( $label ); ?></span>
							<span class="glow-sd-step-check"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4.5 4.5L19 7"/></svg></span>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<!-- ---------- SUBMIT YOUR BROCHURE ORDER ---------- -->
		<?php if ( $can_brochure ) : ?>
		<section class="glow-sd-card glow-sd-brochures" id="glow-sd-brochures">
			<div class="glow-sd-head">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#f5741a" stroke-width="1.6" stroke-linejoin="round" aria-hidden="true"><path d="M12 6.5C10.3 5.2 8 4.6 4.5 4.8v13c3.5-.2 5.8.4 7.5 1.7 1.7-1.3 4-1.9 7.5-1.7v-13c-3.5-.2-5.8.4-7.5 1.7Z"/><path d="M12 6.5v13"/></svg>
				<h3>Submit Your Brochure Order</h3>
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

		<!-- ---------- ROW 3 ---------- -->
		<section class="glow-sd-row glow-sd-row3">

			<!-- Sales -->
			<div class="glow-sd-card">
				<div class="glow-sd-head">
					<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ec0f63" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 16.5 8.5 11l3.5 3.5L20.5 6"/><path d="M15.5 6h5v5"/></svg>
					<h3>Sales to Date</h3>
					<a class="glow-sd-refresh" href="<?php echo esc_url( add_query_arg( 'glow_sd_refresh', 1 ) ); ?>" title="Recalculate now">Refresh</a>
				</div>

				<div class="glow-sd-sales-body">
					<div class="glow-sd-figures">
						<div class="glow-sd-big"><?php echo esc_html( $stats['online_items'] + $stats['sts_items'] ); ?></div>
						<p class="glow-sd-big-sub">items sold</p>

						<?php if ( 0 != $stats['trend'] ) : ?>
							<div class="glow-sd-trend <?php echo $stats['trend'] < 0 ? 'is-down' : ''; ?>">
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
									<?php if ( $stats['trend'] < 0 ) : ?><path d="M6 6 18 18"/><path d="M18 9v9H9"/><?php else : ?><path d="M6 18 18 6"/><path d="M9 6h9v9"/><?php endif; ?>
								</svg>
								<?php echo esc_html( ( $stats['trend'] > 0 ? '+' : '' ) . $stats['trend'] ); ?>%
							</div>
							<p class="glow-sd-trend-sub">vs last 7 days</p>
						<?php endif; ?>
					</div>

					<div class="glow-sd-chart">
						<svg viewBox="0 0 <?php echo esc_attr( $spark['w'] . ' ' . $spark['h'] ); ?>" preserveAspectRatio="none" role="img" aria-label="Daily profit for the last seven days">
							<defs><linearGradient id="glowSdFill" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#ec0f63" stop-opacity=".30"/><stop offset="100%" stop-color="#ec0f63" stop-opacity=".02"/></linearGradient></defs>
							<path d="<?php echo esc_attr( $spark['area'] ); ?>" fill="url(#glowSdFill)"/>
							<polyline points="<?php echo esc_attr( $spark['line'] ); ?>" fill="none" stroke="#ec0f63" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round"/>
							<?php foreach ( $spark['points'] as $i => $p ) : ?>
								<circle cx="<?php echo esc_attr( $p[0] ); ?>" cy="<?php echo esc_attr( $p[1] ); ?>" r="5.6" <?php echo ( 0 === $i % 3 ) ? 'fill="#fff" stroke="#ec0f63" stroke-width="3.2"' : 'fill="#ec0f63"'; ?>/>
							<?php endforeach; ?>
						</svg>
					</div>
				</div>

				<div class="glow-sd-breakdown">
					<div><span>Total sales</span><b>$<?php echo esc_html( number_format( $stats['total_gross'], 2 ) ); ?></b><em>$<?php echo esc_html( number_format( $stats['total_profit'], 2 ) ); ?> profit</em></div>
					<div><span>Online</span><b><?php echo esc_html( $stats['online_items'] ); ?> items</b><em>$<?php echo esc_html( number_format( $stats['online_profit'], 2 ) ); ?></em></div>
					<div><span>Ship-to-group</span><b><?php echo esc_html( $stats['sts_items'] ); ?> items</b><em>$<?php echo esc_html( number_format( $stats['sts_profit'], 2 ) ); ?></em></div>
				</div>
			</div>

			<!-- Orders -->
			<div class="glow-sd-card">
				<div class="glow-sd-head">
					<svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M2.5 6.5h10v9h-10z" fill="#6236e0"/><path d="M12.5 9.5h4l3 3v3h-7z" fill="#6236e0"/><circle cx="7" cy="18" r="1.9" fill="#6236e0"/><circle cx="16.5" cy="18" r="1.9" fill="#6236e0"/></svg>
					<h3>Order Tracking</h3>
				</div>

				<div class="glow-sd-orders-body">
					<div class="glow-sd-orders-count">
						<div class="glow-sd-big"><?php echo esc_html( $stats['order_count'] ); ?></div>
						<p>orders</p>
					</div>
					<div class="glow-sd-statuses">
						<div class="glow-sd-status is-processing"><span class="dot"><?php echo esc_html( $stats['processing'] ); ?></span> Processing</div>
						<div class="glow-sd-status is-shipped"><span class="dot"><?php echo esc_html( $stats['shipped'] ); ?></span> Shipped</div>
						<div class="glow-sd-status is-delivered"><span class="dot"><?php echo esc_html( $stats['delivered'] ); ?></span> Delivered</div>
					</div>
				</div>

				<a class="glow-sd-ghost is-purple" href="#glow-sd-orders">View all orders</a>
			</div>

			<!-- Recent buyers -->
			<div class="glow-sd-card">
				<div class="glow-sd-head">
					<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ec0f63" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.5 12h4l2.5-7 4.5 14 2.5-7h5.5"/></svg>
					<h3>Recent Buyers</h3>
				</div>

				<div class="glow-sd-feed">
					<?php if ( empty( $stats['buyers'] ) ) : ?>
						<p class="glow-sd-empty">No orders yet. Share your link to get your first sale.</p>
					<?php else : ?>
						<?php $colors = array( 'pink', 'amber', 'purple', 'pink', 'amber' ); ?>
						<?php foreach ( $stats['buyers'] as $i => $b ) : ?>
							<div class="glow-sd-feed-row">
								<span class="glow-sd-feed-ava is-<?php echo esc_attr( $colors[ $i % 5 ] ); ?>">
									<?php if ( 0 === $i ) : ?>
										<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" stroke-linejoin="round" aria-hidden="true"><path d="M4.5 7.5h15l-1.2 12H5.7z"/><path d="M8.7 7.5V6a3.3 3.3 0 0 1 6.6 0v1.5"/></svg>
									<?php else : ?>
										<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="8.4" r="3.6"/><path d="M5 19.5c1.3-3.6 3.9-5.2 7-5.2s5.7 1.6 7 5.2"/></svg>
									<?php endif; ?>
								</span>
								<span class="glow-sd-feed-txt">
									<strong>
										<?php if ( 0 === $i ) : ?>
											You made a sale!
										<?php else : ?>
											<?php echo esc_html( $b['name'] ); ?>
										<?php endif; ?>
									</strong>
									<span>
										<?php if ( 0 === $i ) : ?><?php echo esc_html( $b['name'] ); ?> · <?php endif; ?>
										$<?php echo esc_html( number_format( $b['gross'], 2 ) ); ?>
										· <b class="glow-sd-green">$<?php echo esc_html( number_format( $b['profit'], 2 ) ); ?></b> profit
									</span>
								</span>
								<span class="glow-sd-feed-time">
									<?php echo $b['ts'] ? esc_html( human_time_diff( $b['ts'], current_time( 'timestamp' ) ) . ' ago' ) : ''; ?>
								</span>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<!-- ---------- MY BROCHURE ORDERS ---------- -->
		<?php
		$my_brochures = function_exists( 'glow_so_get_seller_orders' ) ? glow_so_get_seller_orders( $sid ) : array();
		if ( $my_brochures ) :
		?>
		<section class="glow-sd-card">
			<div class="glow-sd-head">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#f5741a" stroke-width="1.6" stroke-linejoin="round" aria-hidden="true"><path d="M12 6.5C10.3 5.2 8 4.6 4.5 4.8v13c3.5-.2 5.8.4 7.5 1.7 1.7-1.3 4-1.9 7.5-1.7v-13c-3.5-.2-5.8.4-7.5 1.7Z"/><path d="M12 6.5v13"/></svg>
				<h3>My Brochure Orders</h3>
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
											<span class="glow-sd-i-qty">× <?php echo (int) $it->qty; ?></span>
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

		<!-- ---------- BROCHURE WC ORDERS ---------- -->
		<?php
		$bo_orders = function_exists( 'glow_so_get_seller_wc_orders' ) ? glow_so_get_seller_wc_orders( $sid ) : array();
		if ( $bo_orders ) :
		?>
		<section class="glow-sd-card">
			<div class="glow-sd-head">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#6236e0" stroke-width="1.7" stroke-linejoin="round" aria-hidden="true"><path d="M3 7.5 12 3l9 4.5-9 4.5z"/><path d="M3 7.5v9L12 21l9-4.5v-9M12 12v9"/></svg>
				<h3>Brochure Orders — Status &amp; Tracking</h3>
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
								<?php else : ?>—<?php endif; ?>
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
											<span class="glow-sd-i-qty">× <?php echo (int) $g_item['quantity']; ?></span>
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

		<!-- ---------- ORDER STATUS + TRACKING ---------- -->
		<section class="glow-sd-card" id="glow-sd-orders">
			<div class="glow-sd-head">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#0fa47a" stroke-width="1.7" stroke-linejoin="round" aria-hidden="true"><path d="M3 7.5 12 3l9 4.5-9 4.5z"/><path d="M3 7.5v9L12 21l9-4.5v-9M12 12v9"/></svg>
				<h3>My Orders &amp; Tracking</h3>
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
									<?php else : ?>—<?php endif; ?>
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
				btn.textContent = 'Copied!';
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

	/* ---- rebook ---- */
	var rebook = document.getElementById('rebook_ajax');
	if (rebook) {
		rebook.addEventListener('click', function (e) {
			e.preventDefault();
			if (!confirm('Registering a new account removes access to this dashboard and its reports. Print anything you need first. Continue?')) return;

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
   GLOW Seller Dashboard — scoped under .glow-sd
   ============================================================ */
.glow-sd{
	--pink:#ec0f63; --pink-hi:#ff2d7e; --orange:#f5741a; --amber:#f9b81c;
	--purple:#6236e0; --purple-hi:#7c4bf0; --teal:#0fa47a; --teal-hi:#17b98a;
	--ink:#16152b; --muted:#6f6c85; --board:#f3f2f5; --card:#fff;
	--card-soft:#f8f7f9; --line:#e9e8ee; --line-soft:#efeef2;
	font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;
	color:var(--ink); background:#0a1130; max-width:1780px; margin:0 auto; overflow:hidden;
	-webkit-font-smoothing:antialiased;
}
.glow-sd *{ box-sizing:border-box; }
.glow-sd button{ font-family:inherit; cursor:pointer; border:0; background:none; color:inherit; }
.glow-sd a{ text-decoration:none; }
.glow-sd :focus-visible{ outline:3px solid #7c4bf0; outline-offset:2px; border-radius:6px; }
.glow-sd-gate{ max-width:520px; margin:80px auto; text-align:center; padding:40px 24px; }
.glow-sd-gate-btn{ display:inline-block; margin-top:14px; padding:12px 30px; border-radius:30px; background:#ec0f63; color:#fff !important; font-weight:600; }

/* header */
.glow-sd-top{ position:relative; display:flex; align-items:flex-start; gap:34px; padding:26px 34px 30px; min-height:152px;
	background:radial-gradient(900px 300px at 78% 0%, rgba(70,60,160,.55), transparent 62%), linear-gradient(105deg,#0a1130 0%,#101a3f 48%,#16224b 100%); overflow:hidden; }
.glow-sd-brand{ flex:0 0 auto; padding-top:4px; }
.glow-sd-brand-word{ display:flex; align-items:flex-start; gap:10px; }
.glow-sd-brand-word h2{ margin:0; font-family:'Playfair Display',Georgia,serif; font-weight:500; font-size:44px; line-height:1; letter-spacing:.10em; color:#fff; }
.glow-sd-brand-sub{ margin:11px 0 0; display:flex; align-items:center; gap:9px; font-size:12.5px; font-weight:500; letter-spacing:.22em; color:#e7e4f2; }
.glow-sd-brand-sub i{ width:4px; height:4px; border-radius:50%; background:var(--pink-hi); }
.glow-sd-top-rule{ flex:0 0 1px; align-self:stretch; margin:6px 0 10px; background:linear-gradient(180deg,transparent,rgba(255,255,255,.22),transparent); }
.glow-sd-campaign{ flex:1 1 auto; min-width:0; padding-top:6px; }
.glow-sd-campaign h1{ margin:0 0 16px; font-size:31px; line-height:1.15; font-weight:700; letter-spacing:-.01em; color:#fff; }
.glow-sd-meta{ display:flex; align-items:center; gap:20px; flex-wrap:wrap; }
.glow-sd-pill{ display:inline-flex; align-items:center; padding:9px 22px; border-radius:999px; font-size:14.5px; font-weight:600; }
.glow-sd-pill.is-on{ border:1.5px solid rgba(23,185,138,.85); color:#2fdba4; background:rgba(15,164,122,.10); }
.glow-sd-pill.is-off{ border:1.5px solid rgba(255,90,90,.7); color:#ff8080; background:rgba(255,60,60,.10); }
.glow-sd-pill.is-warn{ border:1.5px solid rgba(249,184,28,.8); color:#ffcf5c; background:rgba(249,184,28,.10); }
.glow-sd-dates{ display:inline-flex; align-items:center; gap:11px; color:#eceaf5; font-size:15.5px; font-weight:500; }
.glow-sd-meta-rule{ width:1px; height:44px; background:linear-gradient(180deg,transparent,rgba(255,255,255,.26),transparent); }
.glow-sd-code{ display:inline-flex; align-items:center; gap:10px; font-size:13.5px; color:#c9c5de; letter-spacing:.02em; }
.glow-sd-code b{ font-size:19px; color:#fff; font-weight:700; letter-spacing:.02em; }
.glow-sd-code-copy{ padding:6px 14px; border-radius:8px; background:rgba(255,255,255,.14); color:#fff; font-size:13px; font-weight:600; }
.glow-sd-code-copy:hover{ background:rgba(255,255,255,.24); }
.glow-sd-code-copy.is-done{ background:var(--teal); }
.glow-sd-countdown{ margin:0; font-size:14.5px; line-height:1.5; color:#e4e1f0; max-width:230px; }
.glow-sd-countdown b{ color:var(--pink-hi); font-weight:700; }

.glow-sd-decor{ position:absolute; right:0; bottom:-2px; width:620px; height:100%; pointer-events:none; }
.glow-sd-candles{ position:absolute; right:96px; bottom:-6px; display:flex; align-items:flex-end; gap:12px; }
.glow-sd-candle{ position:relative; width:98px; }
.glow-sd-candle .glass{ position:relative; height:118px; border-radius:8px 8px 12px 12px; overflow:hidden; box-shadow:inset 0 0 0 1px rgba(255,255,255,.35),0 10px 24px rgba(0,0,0,.35); }
.glow-sd-candle .glass::after{ content:""; position:absolute; inset:0; background:linear-gradient(100deg,rgba(255,255,255,.42) 0 9%,transparent 22% 78%,rgba(255,255,255,.20) 92% 100%); }
.glow-sd-candle .rim{ position:absolute; top:0; left:0; right:0; height:11px; background:linear-gradient(180deg,rgba(255,255,255,.55),rgba(255,255,255,.06)); z-index:2; }
.glow-sd-candle .label{ position:absolute; left:11px; right:11px; bottom:16px; height:52px; border-radius:3px; z-index:3; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:2px; font-size:8px; letter-spacing:.16em; font-weight:700; color:rgba(255,255,255,.75); border:1px solid rgba(255,255,255,.18); background:rgba(255,255,255,.08); }
.glow-sd-candle .label small{ font-size:6px; font-weight:600; letter-spacing:.02em; opacity:.8; }
.glow-sd-candle .flame{ position:absolute; left:50%; top:-30px; width:15px; height:32px; transform:translateX(-50%); border-radius:50% 50% 46% 46%/62% 62% 38% 38%; background:radial-gradient(ellipse at 50% 78%,#fff9d8 0%,#ffd66b 34%,#ff9d2f 62%,rgba(255,120,20,.15) 88%,transparent 100%); box-shadow:0 0 26px 8px rgba(255,170,60,.42); }
.glow-sd-candle .wick{ position:absolute; left:50%; top:0; width:2px; height:9px; transform:translateX(-50%); background:#2a2118; border-radius:2px; z-index:4; }
.glow-sd-candle.is-red .glass{ background:linear-gradient(180deg,#a02631,#7d1a23 62%,#5f121a); }
.glow-sd-candle.is-snow .glass{ background:linear-gradient(180deg,#f6f2ea,#e6ded1 60%,#d6cec1); }
.glow-sd-candle.is-snow .label{ background:linear-gradient(180deg,#cfe6f5,#eef7fd); border-color:rgba(0,0,0,.10); color:#2b4f6b; letter-spacing:.05em; }
.glow-sd-candle.is-white .glass{ background:linear-gradient(180deg,#fbfbfb,#eceaea 60%,#dcd9d9); }
.glow-sd-candle.is-white .label{ color:rgba(60,55,55,.6); border-color:rgba(0,0,0,.08); background:rgba(255,255,255,.5); }
.glow-sd-confetti{ position:absolute; width:16px; height:3.5px; border-radius:3px; }

/* board */
.glow-sd-board{ background:var(--board); border-radius:28px 28px 0 0; margin:0 12px; padding:22px 22px 30px; display:grid; gap:22px; }
.glow-sd-row{ display:grid; gap:22px; }
.glow-sd-row1{ grid-template-columns:.92fr 1.62fr 2.30fr; }
.glow-sd-row2{ grid-template-columns:1fr 1fr 1fr 2.22fr; }
.glow-sd-row3{ grid-template-columns:1.28fr 1fr 1.52fr; }
.glow-sd-card{ background:var(--card); border:1px solid var(--line); border-radius:26px; box-shadow:0 1px 2px rgba(20,18,45,.04); padding:22px; }
.glow-sd-head{ display:flex; align-items:center; gap:12px; }
.glow-sd-head h3{ margin:0; font-size:19px; font-weight:700; letter-spacing:-.01em; }
.glow-sd-refresh{ margin-left:auto; font-size:13.5px; font-weight:600; color:#4a3fd0 !important; text-decoration:underline; }
.glow-sd-count-pill{ margin-left:auto; padding:5px 13px; border-radius:999px; background:#f1eff6; font-size:13.5px; font-weight:600; color:#5d5a75; }
.glow-sd-hint{ margin:12px 0 4px; font-size:14.5px; color:#6b6982; }
.glow-sd-empty{ margin:20px 0; font-size:15px; color:#8d8aa0; text-align:center; }
.glow-sd-green{ color:var(--teal); }

/* notices */
.glow-sd-notice{ display:flex; align-items:center; gap:12px; flex-wrap:wrap; padding:15px 20px; border-radius:16px; font-size:15.5px; font-weight:500; }
.glow-sd-notice a{ font-weight:700; text-decoration:underline; }
.glow-sd-notice.is-admin{ background:#eef0fd; color:#3b3a72; }
.glow-sd-notice.is-done{ background:#eaf8f1; color:#0d6b50; }
.glow-sd-notice.is-todo{ background:#fff4e7; color:#8a4a08; }
.glow-sd-notice.is-cart{ background:#f3f1ff; color:#3f2fa8; }
.glow-sd-spin{ display:none; width:16px; height:16px; }

/* profile */
.glow-sd-profile{ background:linear-gradient(150deg,#ffd9de,#ffcdd6 44%,#ffc0cf); border-color:#f9c3ce; display:flex; flex-direction:column; justify-content:space-between; padding:26px 24px 22px; }
.glow-sd-profile-top{ display:flex; align-items:center; gap:20px; }
.glow-sd-avatar{ width:106px; height:106px; flex:0 0 106px; border-radius:50%; padding:4px; background:linear-gradient(140deg,var(--pink-hi),#ff7aa8); }
.glow-sd-avatar img,.glow-sd-avatar svg{ width:100%; height:100%; border-radius:50%; display:block; object-fit:cover; background:#ffe3ea; }
.glow-sd-hi{ display:flex; align-items:center; gap:8px; margin:0 0 8px; font-size:26px; font-weight:700; letter-spacing:-.02em; color:#1a1330; }
.glow-sd-role{ margin:0; font-size:16px; line-height:1.45; color:#5b4a5c; font-weight:500; }
.glow-sd-profile-actions{ margin-top:22px; }
.glow-sd-profile-btn{ width:100%; display:flex; align-items:center; gap:12px; padding:14px 16px; border-radius:14px; background:rgba(255,255,255,.62); border:1px solid rgba(255,255,255,.9); font-size:16px; font-weight:600; color:#2c2340 !important; }
.glow-sd-profile-btn:hover{ background:rgba(255,255,255,.9); }
.glow-sd-profile-btn .chev{ margin-left:auto; opacity:.55; }
.glow-sd-profile-link{ display:block; margin-top:10px; text-align:center; font-size:14.5px; font-weight:600; color:#7a3556 !important; text-decoration:underline; }

/* goal */
.glow-sd-goal{ background:var(--card-soft); display:flex; align-items:center; gap:26px; padding:20px 26px; }
.glow-sd-donut{ position:relative; flex:0 0 208px; width:208px; height:208px; border-radius:50%; display:grid; place-items:center;
	background:conic-gradient(from 200deg,#ff2d7e 0deg,#ff7a2f 52deg,#ffc328 104deg,#46c96b 152deg,#16b3c9 206deg,#3f7bea 254deg,#7a4bf0 300deg,#ff2d7e 360deg); }
.glow-sd-donut::after{ content:""; position:absolute; inset:15px; border-radius:50%; background:var(--card-soft); }
.glow-sd-donut-in{ position:relative; z-index:2; text-align:center; line-height:1.15; }
.glow-sd-donut-in .amt{ display:block; font-size:36px; font-weight:800; letter-spacing:-.03em; }
.glow-sd-donut-in .of{ display:block; margin-top:2px; font-size:18px; font-weight:600; color:#4d4b63; }
.glow-sd-goal-body{ flex:1 1 auto; min-width:0; }
.glow-sd-goal-eyebrow{ display:flex; align-items:center; gap:10px; font-size:14.5px; font-weight:700; color:#4b4960; }
.glow-sd-goal-target{ margin:6px 0 16px; font-size:44px; font-weight:800; letter-spacing:-.035em; }
.glow-sd-goal-bar{ display:flex; align-items:center; gap:14px; }
.glow-sd-bar{ position:relative; flex:1 1 auto; height:10px; border-radius:999px; background:#e5e3ea; overflow:hidden; }
.glow-sd-bar span{ position:absolute; inset:0 auto 0 0; border-radius:999px; background:linear-gradient(90deg,#f0176e,#ff2d7e); }
.glow-sd-pct{ font-size:16px; font-weight:700; color:#3a3850; }
.glow-sd-cheer{ margin:18px 0 0; font-size:19px; font-weight:700; color:var(--pink); }
.glow-sd-cheer-sub{ margin:8px 0 0; font-size:16px; color:#5b5972; }

/* share */
.glow-sd-share{ display:flex; gap:22px; }
.glow-sd-share-main{ flex:1 1 auto; min-width:0; }
.glow-sd-share-sub{ margin:9px 0 14px; font-size:15.5px; color:#5b5972; }
.glow-sd-linklabel{ display:block; margin:0 0 6px; font-size:13px; font-weight:600; color:#6f6c85; }
.glow-sd-linkbox{ display:flex; align-items:center; gap:8px; padding:6px 6px 6px 16px; border:1px solid #e2e0ea; border-radius:14px; background:#fff; margin-bottom:12px; }
.glow-sd-linkbox input{ flex:1 1 auto; min-width:0; border:0; outline:0; background:transparent; font-family:inherit; font-size:15.5px; font-weight:500; color:#4a3fd0; padding:6px 0; }
.glow-sd-copy{ flex:0 0 auto; padding:10px 20px; border-radius:10px; background:linear-gradient(180deg,var(--purple-hi),var(--purple)); color:#fff; font-size:14.5px; font-weight:600; }
.glow-sd-copy.is-done{ background:linear-gradient(180deg,var(--teal-hi),var(--teal)); }
.glow-sd-share-grid{ display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-top:14px; }
.glow-sd-sbtn{ display:flex; align-items:center; justify-content:center; gap:10px; padding:14px 12px; border-radius:13px; color:#fff !important; font-size:15.5px; font-weight:600; box-shadow:0 6px 18px rgba(20,18,45,.10); }
.glow-sd-sbtn:hover{ filter:brightness(1.06); }
.glow-sd-sbtn.is-text{ background:linear-gradient(180deg,#f5176f,#e00c5e); }
.glow-sd-sbtn.is-fb{ background:linear-gradient(180deg,#6a48ea,#5330d8); }
.glow-sd-sbtn.is-wa{ background:linear-gradient(180deg,#14b58a,#0d9470); }
.glow-sd-sbtn.is-letter{ background:linear-gradient(180deg,#f97d22,#ee6410); }
.glow-sd-qr-wrap{ flex:0 0 auto; width:190px; display:flex; flex-direction:column; gap:14px; align-items:center; justify-content:center; }
.glow-sd-qr-item{ text-align:center; }
.glow-sd-qr{ width:118px; height:118px; display:block; margin:0 auto 6px; }
.glow-sd-qr-item span{ display:block; font-size:13px; font-weight:600; color:#4b4960; }
.glow-sd-qr-item a{ font-size:12.5px; color:#4a3fd0 !important; text-decoration:underline; }
.glow-sd-qr-empty{ font-size:13.5px; color:#8d8aa0; text-align:center; }

/* tiles */
.glow-sd-tile{ position:relative; display:flex; align-items:center; gap:18px; padding:26px 22px; border-radius:26px; color:#fff !important; min-height:158px; width:100%; text-align:left; box-shadow:0 6px 18px rgba(20,18,45,.10); }
.glow-sd-tile:hover{ filter:brightness(1.05); }
.glow-sd-tile.is-store{ background:linear-gradient(135deg,#f5176f,#e0095c); }
.glow-sd-tile.is-brochure{ background:linear-gradient(135deg,#fb8b32,#f26a12); }
.glow-sd-tile.is-report{ background:linear-gradient(135deg,#fdcb3c,#f5ad0c); color:#4a3300 !important; }
.glow-sd-tile-icon{ flex:0 0 auto; opacity:.95; }
.glow-sd-tile-txt{ flex:1 1 auto; min-width:0; }
.glow-sd-tile-txt strong{ display:block; margin-bottom:7px; font-size:20px; font-weight:700; letter-spacing:-.015em; }
.glow-sd-tile-txt span{ display:block; font-size:15px; line-height:1.42; opacity:.94; }
.glow-sd-tile-chev{ flex:0 0 auto; opacity:.8; }
.glow-sd-badge{ display:inline-block !important; width:auto !important; margin-top:11px; padding:5px 13px; border-radius:999px; background:#fff; color:#ef6a11; font-size:13.5px !important; font-weight:700; opacity:1 !important; }

/* brochure order section */
.glow-sd-brochures{ scroll-margin-top:20px; }
.glow-sd-brochure-rows{ margin-top:14px; border:1px solid var(--line); border-radius:16px; overflow:hidden; }
.glow-sd-brochure-row{ display:flex; align-items:center; justify-content:space-between; gap:16px; padding:16px 18px; border-bottom:1px solid var(--line-soft); font-size:16px; font-weight:500; color:var(--ink); }
.glow-sd-brochure-row:last-child{ border-bottom:0; }
.glow-sd-brochure-row:hover{ background:#fdfcfe; }
.glow-sd a.glow-sd-enter{
	flex:0 0 auto; display:inline-block; min-width:140px; text-align:center;
	padding:10px 24px; border-radius:999px;
	background:linear-gradient(180deg,var(--teal-hi),var(--teal)) !important;
	color:#fff !important; font-size:13px; font-weight:700; letter-spacing:.5px;
	text-transform:uppercase; text-decoration:none !important; border:0 !important;
}
.glow-sd a.glow-sd-enter:hover{ filter:brightness(1.07); }
.glow-sd-brochure-note{ margin:14px 0 0; font-size:15px; color:#5b5972; }

@media(max-width:580px){
	.glow-sd-brochure-row{ flex-direction:column; align-items:flex-start; gap:10px; }
	.glow-sd a.glow-sd-enter{ width:100%; }
}

/* checklist */
.glow-sd-checklist{ display:flex; align-items:center; gap:26px; }
.glow-sd-checklist-intro{ flex:0 0 auto; max-width:270px; }
.glow-sd-checklist-intro h3{ margin:0 0 6px; font-size:19px; font-weight:700; }
.glow-sd-checklist-intro p{ margin:0; font-size:14.5px; color:#5f5d75; }
.glow-sd-steps{ flex:1 1 auto; display:grid; grid-template-columns:repeat(4,1fr); gap:12px; }
.glow-sd-step{ display:flex; flex-direction:column; align-items:center; gap:10px; text-align:center; color:inherit !important; }
.glow-sd-step-num{ width:52px; height:52px; border-radius:50%; display:grid; place-items:center; font-size:22px; font-weight:700; border:2.5px solid currentColor; transition:background .2s ease; }
.glow-sd-step:nth-child(1) .glow-sd-step-num{ color:var(--pink); }
.glow-sd-step:nth-child(2) .glow-sd-step-num{ color:var(--orange); }
.glow-sd-step:nth-child(3) .glow-sd-step-num{ color:var(--amber); }
.glow-sd-step:nth-child(4) .glow-sd-step-num{ color:var(--teal); }
.glow-sd-step-label{ font-size:14.5px; line-height:1.32; color:#3d3b52; font-weight:500; max-width:100px; }
.glow-sd-step-check{ width:20px; height:20px; border-radius:50%; display:grid; place-items:center; background:var(--teal-hi); color:#fff; }
.glow-sd-step[aria-pressed="false"] .glow-sd-step-check{ background:#d7d5de; opacity:.55; }
.glow-sd-step[aria-pressed="true"] .glow-sd-step-num{ background:currentColor; }
.glow-sd-step[aria-pressed="true"] .glow-sd-step-num span{ color:#fff; }

/* sales / orders / sellers */
.glow-sd-sales-body{ display:flex; align-items:flex-end; gap:18px; margin:18px 0 6px; }
.glow-sd-figures{ flex:0 0 auto; padding-bottom:12px; }
.glow-sd-big{ font-size:46px; font-weight:800; letter-spacing:-.04em; line-height:1; }
.glow-sd-big-sub{ margin:6px 0 16px; font-size:16px; color:#4f4d66; font-weight:500; }
.glow-sd-trend{ display:flex; align-items:center; gap:6px; font-size:17px; font-weight:700; color:var(--teal); }
.glow-sd-trend.is-down{ color:#e0424b; }
.glow-sd-trend-sub{ margin:5px 0 0; font-size:14.5px; color:#6f6d84; }
.glow-sd-chart{ flex:1 1 auto; min-width:0; }
.glow-sd-chart svg{ width:100%; height:132px; display:block; }
.glow-sd-breakdown{ display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-top:14px; }
.glow-sd-breakdown div{ padding:12px 14px; border-radius:14px; background:#f7f6f9; }
.glow-sd-breakdown span{ display:block; font-size:13px; color:#6f6c85; font-weight:600; }
.glow-sd-breakdown b{ display:block; margin-top:4px; font-size:16px; font-weight:700; }
.glow-sd-breakdown em{ display:block; margin-top:2px; font-size:14.5px; font-style:normal; font-weight:700; color:var(--teal); }
.glow-sd-orders-body{ display:flex; align-items:center; gap:22px; margin:18px 0 6px; }
.glow-sd-orders-count p{ margin:6px 0 0; font-size:17px; color:#4f4d66; font-weight:500; }
.glow-sd-statuses{ flex:1 1 auto; display:grid; gap:8px; }
.glow-sd-status{ display:flex; align-items:center; gap:12px; padding:9px 14px; border-radius:10px; font-size:16px; font-weight:600; color:#33314a; }
.glow-sd-status .dot{ width:24px; height:24px; border-radius:50%; display:grid; place-items:center; color:#fff; font-size:12.5px; font-weight:700; }
.glow-sd-status.is-processing{ background:#f0edfd; } .glow-sd-status.is-processing .dot{ background:var(--purple-hi); }
.glow-sd-status.is-shipped{ background:#eaf8f1; }    .glow-sd-status.is-shipped .dot{ background:var(--teal-hi); }
.glow-sd-status.is-delivered{ background:#eaf8f1; }  .glow-sd-status.is-delivered .dot{ background:var(--teal); }
.glow-sd-ghost{ display:block; width:100%; margin-top:12px; padding:15px; border-radius:14px; border:1px solid var(--line); background:#fff; font-size:16px; font-weight:600; text-align:center; }
.glow-sd-ghost.is-purple{ color:#4a3fd0 !important; }
.glow-sd-ghost.is-purple:hover{ background:#f3f1ff; border-color:#d9d3fb; }

.glow-sd-sellers{ margin-top:14px; }
.glow-sd-sellers-head,.glow-sd-seller-row{ display:grid; grid-template-columns:1fr 90px 90px 50px; gap:10px; align-items:center; }
.glow-sd-sellers-head{ padding:0 6px 8px; font-size:12.5px; font-weight:700; letter-spacing:.03em; color:#8d8aa0; text-transform:uppercase; }
.glow-sd-sellers-head span:not(:first-child),.glow-sd-seller-row span:not(.glow-sd-seller-name){ text-align:right; font-size:14.5px; font-weight:600; }
.glow-sd-sellers-scroll{ max-height:236px; overflow-y:auto; }
.glow-sd-seller-row{ padding:9px 6px; border-top:1px solid var(--line-soft); font-size:15px; }
.glow-sd-seller-name{ display:flex; align-items:center; gap:10px; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-weight:600; }
.glow-sd-seller-name em{ font-style:normal; font-size:11.5px; font-weight:700; color:#8d8aa0; background:#f1eff6; padding:2px 7px; border-radius:999px; }
.glow-sd-rank{ flex:0 0 22px; width:22px; height:22px; border-radius:50%; display:grid; place-items:center; font-style:normal; font-size:12px; font-weight:700; background:#f1eff6; color:#6f6c85; }
.glow-sd-rank.is-top{ background:linear-gradient(140deg,#ff4d8d,#ec0f63); color:#fff; }

/* permissions */
.glow-sd-toggle{ margin-left:auto; min-width:104px; padding:7px 18px; border-radius:999px; text-align:center; font-size:12.5px; font-weight:700; letter-spacing:.4px; color:#fff !important; }
.glow-sd-toggle.is-on{ background:var(--teal); }
.glow-sd-toggle.is-off{ background:#9b9b9b; }
.glow-sd-toggle:hover{ opacity:.88; }
.glow-sd-perms{ margin-top:8px; max-height:320px; overflow-y:auto; }
.glow-sd-perm-row{ display:flex; align-items:center; gap:14px; padding:11px 4px; border-top:1px solid var(--line-soft); font-size:15.5px; }
.glow-sd-perm-row .glow-sd-toggle{ min-width:96px; }

/* tables */
.glow-sd-table-wrap{ margin-top:14px; overflow-x:auto; }
.glow-sd-table{ width:100%; border-collapse:collapse; font-size:14.5px; }
.glow-sd-table th{ padding:10px 12px; text-align:left; font-size:12px; font-weight:700; letter-spacing:.04em; text-transform:uppercase; color:#8d8aa0; border-bottom:1px solid var(--line); white-space:nowrap; }
.glow-sd-table td{ padding:12px; border-bottom:1px solid var(--line-soft); vertical-align:middle; }
.glow-sd-table tr:hover td{ background:#faf9fc; }
.glow-sd-table a{ color:#4a3fd0 !important; text-decoration:underline; }
.glow-sd-tag{ display:inline-block; padding:4px 11px; border-radius:999px; font-size:12.5px; font-weight:700; background:#f1eff6; color:#5d5a75; white-space:nowrap; }
.glow-sd-tag.is-completed{ background:#eaf8f1; color:#0d6b50; }
.glow-sd-tag.is-processing{ background:#f0edfd; color:#4030a8; }
.glow-sd-tag.is-waiting-call{ background:#fff4e7; color:#8a4a08; }
.glow-sd-mini{ display:inline-block; padding:5px 14px; border-radius:8px; background:var(--teal); color:#fff !important; font-size:13px; font-weight:600; text-decoration:none !important; }
.glow-sd-check{ display:inline-flex; align-items:center; gap:8px; margin:0; }
.glow-money-msg{ font-size:12px; color:var(--teal); }
.glow-moved{ font-style:normal; font-size:13px; color:#4a3fd0; }
.glow-view{ display:block; margin-top:2px; font-size:12px; }

/* close out */
.glow-sd-closeout{ display:flex; align-items:center; gap:24px; flex-wrap:wrap; }
.glow-sd-closeout h3{ margin:0 0 6px; font-size:19px; font-weight:700; }
.glow-sd-closeout p{ margin:0; font-size:15px; color:#6b6982; max-width:70ch; }
.glow-sd-closeout button{ margin-left:auto; padding:14px 32px; border-radius:14px; border:1.5px solid #e0424b; color:#e0424b; font-size:15.5px; font-weight:700; }
.glow-sd-closeout button:hover{ background:#e0424b; color:#fff; }

/* modal + toast */
.glow-sd-modal{ display:none; position:fixed; inset:0; background:rgba(10,12,30,.6); z-index:99998; }
.glow-sd-modal.is-open{ display:block; }
.glow-sd-modal-box{ position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); width:100%; max-width:520px; background:#fff; border-radius:22px; padding:32px 30px; text-align:center; font-family:'Inter',sans-serif; }
.glow-sd-modal-box h3{ margin:0 0 10px; font-size:22px; font-weight:700; }
.glow-sd-modal-box p{ margin:0; font-size:15.5px; color:#6b6982; line-height:1.5; }
.glow-sd-modal-btns{ display:flex; gap:12px; justify-content:center; margin-top:22px; }
.glow-sd-modal-btns button{ padding:13px 26px; border-radius:12px; font-size:15px; font-weight:700; }
.glow-sd-modal-cancel{ background:#f1eff6; color:#3d3b52; }
.glow-sd-modal-msg{ margin-top:14px !important; font-size:14px !important; color:#4a3fd0 !important; }
.glow-sd-toast{ position:fixed; left:50%; bottom:34px; transform:translate(-50%,20px); background:#16152b; color:#fff; padding:13px 22px; border-radius:12px; font-size:15px; font-weight:600; box-shadow:0 12px 30px rgba(0,0,0,.24); opacity:0; pointer-events:none; transition:opacity .2s ease,transform .2s ease; z-index:99999; }
.glow-sd-toast.is-open{ opacity:1; transform:translate(-50%,0); }


/* seller-specific bits */

/* Theme button styles (WooCommerce / parent theme) are more specific than a
   bare class, and strip the gradient off any of these that is a <button>.
   These element+class rules win them back. */
.glow-sd a.glow-sd-sbtn,
.glow-sd button.glow-sd-sbtn{
	color:#fff !important; border:0 !important; text-decoration:none !important;
	line-height:1.2; box-shadow:0 6px 18px rgba(20,18,45,.10);
}
.glow-sd a.glow-sd-sbtn.is-text,   .glow-sd button.glow-sd-sbtn.is-text{ background:linear-gradient(180deg,#f5176f,#e00c5e) !important; }
.glow-sd a.glow-sd-sbtn.is-wa,     .glow-sd button.glow-sd-sbtn.is-wa{   background:linear-gradient(180deg,#14b58a,#0d9470) !important; }
.glow-sd a.glow-sd-sbtn.is-fb,     .glow-sd button.glow-sd-sbtn.is-fb{   background:linear-gradient(180deg,#6a48ea,#5330d8) !important; }
.glow-sd a.glow-sd-sbtn.is-ig,     .glow-sd button.glow-sd-sbtn.is-ig{
	background:linear-gradient(135deg,#f9ce34 0%,#ee2a7b 48%,#6228d7 100%) !important;
}
.glow-sd a.glow-sd-sbtn svg,
.glow-sd button.glow-sd-sbtn svg{ flex:0 0 auto; }

.glow-sd a.glow-sd-tile,
.glow-sd button.glow-sd-tile,
.glow-sd span.glow-sd-tile{ border:0 !important; text-decoration:none !important; }
.glow-sd a.glow-sd-tile.is-store,   .glow-sd button.glow-sd-tile.is-store{   background:linear-gradient(135deg,#f5176f,#e0095c) !important; color:#fff !important; }
.glow-sd a.glow-sd-tile.is-brochure,.glow-sd button.glow-sd-tile.is-brochure,
.glow-sd span.glow-sd-tile.is-brochure{ background:linear-gradient(135deg,#fb8b32,#f26a12) !important; color:#fff !important; }
.glow-sd a.glow-sd-tile.is-report,  .glow-sd button.glow-sd-tile.is-report{  background:linear-gradient(135deg,#fdcb3c,#f5ad0c) !important; color:#4a3300 !important; }

.glow-sd a.glow-sd-copy,
.glow-sd button.glow-sd-copy{ background:linear-gradient(180deg,var(--purple-hi),var(--purple)) !important; color:#fff !important; border:0 !important; }
.glow-sd button.glow-sd-copy.is-done{ background:linear-gradient(180deg,var(--teal-hi),var(--teal)) !important; }
.glow-sd button.glow-sd-code-copy{ background:rgba(255,255,255,.14) !important; color:#fff !important; border:0 !important; }
.glow-sd button.glow-sd-code-copy.is-done{ background:var(--teal) !important; }

.glow-sd-tile.is-locked{ filter:grayscale(.55); opacity:.72; cursor:not-allowed; }
.glow-sd-qr-wrap{ position:relative; padding-top:24px; }
.glow-sd-qr-arrow{ position:absolute; top:6px; left:-6px; color:var(--teal-hi); }
.glow-sd-qr{ width:150px; height:150px; }
.glow-sd-qr-dl{ display:block; margin-top:8px; font-size:13px; color:#4a3fd0 !important; text-decoration:underline; text-align:center; }
.glow-sd-brochure-note{ margin:0; font-size:15px; color:#5b5972; }
.glow-sd-flag{ display:block; margin-top:3px; font-style:normal; font-size:11.5px; font-weight:700; color:#8d8aa0; }
.glow-sd-tag.is-green{ background:#eaf8f1; color:#0d6b50; }
.glow-sd-tag.is-amber{ background:#fff4e7; color:#8a4a08; }
.glow-sd-tag.is-purple{ background:#f0edfd; color:#4030a8; }
.glow-sd-tag.is-on-hold{ background:#fff4e7; color:#8a4a08; }

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
.glow-view{ display:block; margin-top:2px; font-size:12px; color:#4a3fd0 !important; text-decoration:underline; }
.glow-sd-mini{ display:inline-block; padding:5px 14px; border-radius:8px; background:var(--teal); color:#fff !important; font-size:13px; font-weight:600; }

@media(max-width:640px){
	.glow-sd-item-row{ flex-wrap:wrap; gap:6px 10px; }
	.glow-sd-i-sku{ flex:0 0 auto; }
	.glow-sd-i-name{ flex:1 1 100%; order:-1; font-weight:600; color:var(--ink); }
}

/* responsive */
@media(max-width:1500px){
	.glow-sd-row1{ grid-template-columns:1fr 1.6fr; }
	.glow-sd-row1 .glow-sd-share{ grid-column:1/-1; }
	.glow-sd-row2{ grid-template-columns:1fr 1fr 1fr; }
	.glow-sd-row2 .glow-sd-checklist{ grid-column:1/-1; }
	.glow-sd-row3{ grid-template-columns:1fr 1fr; }
	.glow-sd-row3 .glow-sd-card:last-child{ grid-column:1/-1; }
}
@media(max-width:1080px){
	.glow-sd-top{ flex-wrap:wrap; padding-right:28px; }
	.glow-sd-decor{ display:none; }
	.glow-sd-row1,.glow-sd-row2,.glow-sd-row3{ grid-template-columns:1fr 1fr; }
	.glow-sd-row1 .glow-sd-share,.glow-sd-row2 .glow-sd-checklist,.glow-sd-row3 .glow-sd-card:last-child{ grid-column:1/-1; }
}
@media(max-width:760px){
	.glow-sd-board{ margin:0; border-radius:22px 22px 0 0; padding:16px; gap:16px; }
	.glow-sd-row{ gap:16px; grid-template-columns:1fr !important; }
	.glow-sd-row1 .glow-sd-share,.glow-sd-row2 .glow-sd-checklist,.glow-sd-row3 .glow-sd-card:last-child{ grid-column:auto; }
	.glow-sd-top{ padding:22px 20px 26px; gap:18px; }
	.glow-sd-top-rule,.glow-sd-meta-rule{ display:none; }
	.glow-sd-brand-word h2{ font-size:34px; }
	.glow-sd-campaign h1{ font-size:24px; }
	.glow-sd-goal{ flex-direction:column; align-items:flex-start; gap:18px; }
	.glow-sd-donut{ margin:0 auto; }
	.glow-sd-share{ flex-direction:column; }
	.glow-sd-qr-wrap{ width:100%; flex-direction:row; justify-content:center; gap:26px; }
	.glow-sd-share-grid{ grid-template-columns:1fr; }
	.glow-sd-checklist{ flex-direction:column; align-items:flex-start; }
	.glow-sd-steps{ width:100%; }
	.glow-sd-sales-body,.glow-sd-orders-body{ flex-direction:column; align-items:flex-start; }
	.glow-sd-chart{ width:100%; }
	.glow-sd-breakdown{ grid-template-columns:1fr; }
	.glow-sd-closeout button{ margin-left:0; width:100%; }
}
@media(prefers-reduced-motion:reduce){ .glow-sd *{ transition-duration:.001ms !important; } }
</style>

<?php get_footer(); ?>