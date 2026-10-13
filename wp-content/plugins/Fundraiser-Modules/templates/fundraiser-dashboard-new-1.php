<?php
/**
 * Template Name: GLOW Fundraiser Dashboard
 *
 * SINGLE FILE — data layer + markup together, like your original template.
 *
 * Still needed elsewhere (leave them where they already are):
 *   - glow_master_allows() / glow_seller_allows()   (brochure toggles)
 *   - glow_so_get_fundraiser_orders() / glow_so_catalog_label()
 *   - the glow_get_order / glow_save_order / glow_move_to_cart AJAX handlers
 *
 * Three things changed vs. the two-file version, because a page template is
 * only loaded on this page and never during admin-ajax or admin requests:
 *   1. The checklist saves through a nonced link + redirect instead of AJAX.
 *   2. The stats cache clears on a 5-minute timer or the Refresh link, not on
 *      an order-status hook. Search for "OPTIONAL" below if you want that hook
 *      back — those lines belong in functions.php.
 *   3. The webfont is a <link> tag instead of wp_enqueue_style().
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* =========================================================================
   PART 1 — DATA LAYER
   Everything below is wrapped in function_exists() so nothing fatals if you
   also load the standalone functions file.
   ========================================================================= */


/* =========================================================
   1. CONFIG
   ========================================================= */

if ( ! function_exists( 'glow_fd_config' ) ) :
function glow_fd_config() {

	$config = array(

		'meta' => array(
			'org_name'       => 'user_registration_name_of_school_business',
			'start_date'     => 'user_registration_start_date',
			'end_date'       => 'user_registration_end_date',
			'goal'           => 'user_registration_fr_goal',
			'participants'   => 'user_registration_participants_no',
			'preference'     => 'user_registration_fundraiser_preference',
			'action_status'  => 'user_action_status',
			'closeout_date'  => 'user_close_out_date_inactive',
			'photo'          => 'fund_profile_image',
			'photo_alt'      => 'user_registration_image_url_profile',
			'description'    => 'fund_profile_description',
			'qr_shop'        => 'fundraiser_barcode_link',
			'qr_seller_reg'  => 'fundraiser_seller_register_barcode_link',
			'cart_restore'   => 'cart-restore',
			'merge_id'       => 'wc_ship_mergeOrderID',
			'merge_qty'      => 'wc_ship_mergeOrderQuanty',
			'seller_of'      => 'user_registration_seller_fundraiser',
		),

		'rebate' => array(
			'base'     => 40,
			'bulk'     => 50,
			'bulk_qty' => (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1),
		),

		/**
		 * The original progress bar counted ONLINE + SHIP-TO-GROUP profit only —
		 * brochure profit was NOT included. Set true to include it.
		 */
		'progress_includes_brochure' => false,

		'urls' => array(
			'edit_info'      => '/edit-fundraiser/',
			'customize'      => '/upload-image-and-description/?type=image',
			'store_message'  => '/upload-image-and-description/?type=description',
			'seller_register'=> '/seller-register/',
			'brochure_ss'    => '/spring-summer-catalogue-mass-order/',
			'brochure_new'   => '/digital-fall-winter-catalogue/?new',
			'brochure_fw'    => '/digital-fall-winter-catalogue/',
			'report'         => '/fundraiser-dashboard/fundraiser-reports/',
			'final_report'   => '/print/',
			'participant_ltr'=> '/docs/word.php',
			'reset_pw'       => '/my-account/lost-password/',
			'instructions'   => ('' . glow_dynamic_url_base('uploads') . '2020/10/Fundraiser-Dashboard-Glow-Scented-Candles-4-copy.pdf'),
		),

		'invoice_key'     => 'wc_order_d3GG6Op7BmE3i',
		'orders_in_table' => 250,
		'cache_minutes'   => 5,
	);

	return apply_filters( 'glow_fd_config', $config );
}
endif;

if ( ! function_exists( 'glow_fd_meta' ) ) :
function glow_fd_meta( $key ) {
	$c = glow_fd_config();
	return isset( $c['meta'][ $key ] ) ? $c['meta'][ $key ] : '';
}
endif;

if ( ! function_exists( 'glow_fd_url' ) ) :
function glow_fd_url( $key ) {
	$c = glow_fd_config();
	$p = isset( $c['urls'][ $key ] ) ? $c['urls'][ $key ] : '/';
	return ( 0 === strpos( $p, 'http' ) ) ? $p : home_url( $p );
}
endif;


/* =========================================================
   SELLER BROCHURE ORDER PERMISSIONS
   ========================================================= */

   if ( ! function_exists( 'glow_master_allows' ) ) :
	function glow_master_allows( $fid ) {
	
		if ( ! $fid ) {
			return false;
		}
	
		$val = get_user_meta( $fid, 'allow_seller_orders', true );
	
		if ( is_array( $val ) ) {
			return ! empty( $val );
		}
	
		return in_array( $val, array( 'yes', '1', 1 ), true );
	}
	endif;
	
	
	if ( ! function_exists( 'glow_seller_allows' ) ) :
	function glow_seller_allows( $sid, $fid ) {
	
		$val = get_user_meta(
			$sid,
			'glow_allow_brochure_order',
			true
		);
	
		/* If seller has no individual setting,
		   follow fundraiser master setting */
		if ( $val === '' ) {
			return glow_master_allows( $fid );
		}
	
		return $val === 'yes';
	}
	endif;
	
	
	/* =========================================================
	   MASTER TOGGLE
	   ========================================================= */
	
	if ( ! function_exists( 'glow_handle_master_toggle' ) ) :
	function glow_handle_master_toggle() {
	
		if (
			! isset( $_GET['glow_master'], $_GET['_wpnonce'] )
		) {
			return;
		}
	
		$fid = absint( $_GET['glow_master'] );
	
		if (
			! wp_verify_nonce(
				$_GET['_wpnonce'],
				'glow_master_' . $fid
			)
		) {
			return;
		}
	
		if (
			$fid === get_current_user_id()
			|| current_user_can( 'manage_options' )
		) {
	
			update_user_meta(
				$fid,
				'allow_seller_orders',
				glow_master_allows( $fid ) ? 'no' : 'yes'
			);
		}
	
		wp_safe_redirect(
			remove_query_arg(
				array( 'glow_master', '_wpnonce' )
			)
		);
	
		exit;
	}
	endif;
	
	
	/* =========================================================
	   INDIVIDUAL SELLER TOGGLE
	   ========================================================= */
	
	if ( ! function_exists( 'glow_handle_seller_toggle' ) ) :
	function glow_handle_seller_toggle() {
	
		if (
			! isset( $_GET['glow_seller'], $_GET['_wpnonce'] )
		) {
			return;
		}
	
		$sid = absint( $_GET['glow_seller'] );
	
		if (
			! wp_verify_nonce(
				$_GET['_wpnonce'],
				'glow_seller_' . $sid
			)
		) {
			return;
		}
	
		$fid = absint(
			get_user_meta(
				$sid,
				'user_registration_seller_fundraiser',
				true
			)
		);
	
		/*
		 * Seller must belong to this fundraiser.
		 */
		if (
			$fid
			&& (
				$fid === get_current_user_id()
				|| current_user_can( 'manage_options' )
			)
		) {
	
			update_user_meta(
				$sid,
				'glow_allow_brochure_order',
				glow_seller_allows( $sid, $fid ) ? 'no' : 'yes'
			);
		}
	
		wp_safe_redirect(
			remove_query_arg(
				array( 'glow_seller', '_wpnonce' )
			)
		);
	
		exit;
	}
	endif;

/* =========================================================
   2. WHOSE DASHBOARD
   ========================================================= */

/**
 * Returns the fundraiser ID to show.
 *
 * The original template did `$u_id = $_GET['frid']` with no check at all, so any
 * logged-in user could read another group's sales and customer addresses.
 * ?frid= is now admin-only.
 */
if ( ! function_exists( 'glow_fd_current_fundraiser_id' ) ) :
function glow_fd_current_fundraiser_id() {

	if ( ! is_user_logged_in() ) return 0;

	if ( isset( $_GET['frid'] ) && current_user_can( 'manage_options' ) ) {
		return absint( $_GET['frid'] );
	}

	return get_current_user_id();
}
endif;

if ( ! function_exists( 'glow_fd_is_admin_view' ) ) :
function glow_fd_is_admin_view( $fid ) {
	return ( current_user_can( 'manage_options' ) && $fid !== get_current_user_id() );
}
endif;


/* =========================================================
   3. CAMPAIGN + PROFILE
   ========================================================= */

if ( ! function_exists( 'glow_fd_campaign' ) ) :
function glow_fd_campaign( $fid ) {

	$start = get_user_meta( $fid, glow_fd_meta( 'start_date' ), true );
	$end   = get_user_meta( $fid, glow_fd_meta( 'end_date' ), true );
	$stat  = get_user_meta( $fid, glow_fd_meta( 'action_status' ), true );

	$end_ts = $end ? strtotime( $end . ' 23:59:59' ) : 0;
	$now    = current_time( 'timestamp' );

	$days_left = $end_ts ? (int) ceil( ( $end_ts - $now ) / DAY_IN_SECONDS ) : 0;
	if ( $days_left < 0 ) $days_left = 0;

	$closed = ( 2 == $stat );

	return array(
		'org'        => get_user_meta( $fid, glow_fd_meta( 'org_name' ), true ),
		'chair'      => trim( get_user_meta( $fid, 'first_name', true ) . ' ' . get_user_meta( $fid, 'last_name', true ) ),
		'start_fmt'  => $start ? date_i18n( 'M j', strtotime( $start ) ) : '',
		'end_fmt'    => $end   ? date_i18n( 'M j, Y', $end_ts )          : '',
		'closed'     => $closed,
		'active'     => ( ! $closed && $days_left > 0 ),
		'days_left'  => $days_left,
		'goal'       => (float) get_user_meta( $fid, glow_fd_meta( 'goal' ), true ),
		'preference' => get_user_meta( $fid, glow_fd_meta( 'preference' ), true ),
		'virtual'    => ( 'Virtual Fundraiser - No Brochures.' === get_user_meta( $fid, glow_fd_meta( 'preference' ), true ) ),
	);
}
endif;

if ( ! function_exists( 'glow_fd_photo' ) ) :
function glow_fd_photo( $fid ) {

	$url = get_user_meta( $fid, glow_fd_meta( 'photo' ), true );
	if ( ! $url ) $url = get_user_meta( $fid, glow_fd_meta( 'photo_alt' ), true );
	if ( ! $url ) return '';

	if ( function_exists( 'profile_image_resize_crop' ) ) {
		$cropped = profile_image_resize_crop( $url, 300, 300, null, true, true );
		if ( $cropped ) $url = $cropped;
	}

	return $url;
}
endif;

if ( ! function_exists( 'glow_fd_needs_photo' ) ) :
function glow_fd_needs_photo( $fid ) {
	return ( '' === glow_fd_photo( $fid ) )
	    || ( '' === get_user_meta( $fid, glow_fd_meta( 'description' ), true ) );
}
endif;

if ( ! function_exists( 'glow_fd_shop_link' ) ) :
function glow_fd_shop_link( $fid ) {
	return apply_filters( 'glow_fd_shop_link', home_url( '/profile/' . $fid ), $fid );
}
endif;

if ( ! function_exists( 'glow_fd_seller_register_link' ) ) :
function glow_fd_seller_register_link( $fid ) {
	return add_query_arg( 'frid', $fid, glow_fd_url( 'seller_register' ) );
}
endif;


/* =========================================================
   4. STATS — one query, one pass
   ========================================================= */

if ( ! function_exists( 'glow_fd_stats' ) ) :
function glow_fd_stats( $fid, $force = false ) {

	$config = glow_fd_config();
	$key    = 'glow_fd_stats_' . $fid;

	if ( ! $force ) {
		$cached = get_transient( $key );
		if ( is_array( $cached ) && ( $cached['_profit_min_items'] ?? 0 ) === GLOW_BROCHURE_PROFIT_MIN_ITEMS ) return $cached;
	}

	$orders = wc_get_orders( array(
		'limit'      => -1,
		'status'     => array( 'processing', 'completed', 'on-hold', 'waiting-call' ),
		'meta_key'   => 'fundraiser_field',
		'meta_value' => $fid,
		'orderby'    => 'date',
		'order'      => 'DESC',
	) );

	$closeout   = get_user_meta( $fid, glow_fd_meta( 'closeout_date' ), true );
	$was_closed = ( 2 == get_user_meta( $fid, glow_fd_meta( 'action_status' ), true ) );

	$s = array(
		'online_gross' => 0, 'online_profit' => 0, 'online_items' => 0,
		'broch_gross'  => 0, 'broch_profit'  => 0, 'broch_items'  => 0, 'broch_due' => 0,
		'sts_gross'    => 0, 'sts_profit'    => 0, 'sts_items'    => 0,
		'order_count'  => 0, 'processing' => 0, 'shipped' => 0, 'delivered' => 0,
		'sellers'      => array(),
		'days'         => array(),
		'trend'        => 0,
		'table'        => array(),
		'recent'       => array(),
	);

	$today = strtotime( date_i18n( 'Y-m-d' ) );
	for ( $i = 13; $i >= 0; $i-- ) {
		$s['days'][ date( 'Y-m-d', $today - ( $i * DAY_IN_SECONDS ) ) ] = 0;
	}

	$sts_orders   = array(); // second pass: ship-to-group uses an aggregate qty tier
	$rate_base    = $config['rebate']['base'];
	$rate_bulk    = $config['rebate']['bulk'];
	$bulk_qty     = $config['rebate']['bulk_qty'];

	foreach ( $orders as $order ) {

		$order_id  = $order->get_id();
		$status    = $order->get_status();
		$type      = $order->get_meta( 'wc_order_type' );
		$seller_id = $order->get_meta( 'wc_seller_field' );
		$customer  = $order->get_customer_id();
		$scat      = $order->get_meta( 'ship_with_catalog' );

		$created = $order->get_date_created();
		$ts      = $created ? $created->getTimestamp() : 0;

		// original rule: orders placed after the close-out date are excluded
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

		} elseif ( 'Catlog' === $type ) {

			// brochure orders are placed by the chairperson themselves
			$rate   = ( $qty > $bulk_qty ) ? $rate_bulk : $rate_base;
			$profit = $gross * ( $rate / 100 );

			$s['broch_gross']  += $gross;
			$s['broch_profit'] += $profit;
			$s['broch_due']    += ( $gross - $profit );
			$s['broch_items']  += $qty;

		} elseif ( 'ship-to-school' === $type && 1 == $scat ) {

			$s['sts_gross'] += $gross;
			$s['sts_items'] += $qty;
			$sts_orders[]   = array( 'gross' => $gross, 'ts' => $ts );
		}

		// ---- status buckets
		$s['order_count']++;
		if ( in_array( $status, array( 'processing', 'on-hold', 'waiting-call' ), true ) ) {
			$s['processing']++;
		} elseif ( 'completed' === $status ) {
			if ( glow_fd_tracking_number( $order_id ) ) $s['shipped']++;
			else                                        $s['delivered']++;
		}

		// ---- daily bucket (online + brochure profit)
		if ( $ts && $profit ) {
			$day = date( 'Y-m-d', $ts );
			if ( isset( $s['days'][ $day ] ) ) $s['days'][ $day ] += $profit;
		}

		// ---- per-seller breakdown
		$sk = '';
		if ( 'custom' === $seller_id ) {
			$name = trim( $order->get_meta( 'wc_seller_field_custom' ) );
			if ( $name ) $sk = 'c:' . strtolower( $name );
		} elseif ( is_numeric( $seller_id ) && $seller_id ) {
			$sk   = 'u:' . (int) $seller_id;
			$name = trim( get_user_meta( $seller_id, 'first_name', true ) . ' ' . get_user_meta( $seller_id, 'last_name', true ) );
			if ( ! $name ) {
				$u    = get_userdata( $seller_id );
				$name = $u ? $u->display_name : '#' . $seller_id;
			}
		}

		if ( $sk ) {
			if ( ! isset( $s['sellers'][ $sk ] ) ) {
				$s['sellers'][ $sk ] = array(
					'name'       => $name,
					'id'         => is_numeric( $seller_id ) ? (int) $seller_id : 0,
					'registered' => is_numeric( $seller_id ),
					'gross'      => 0, 'profit' => 0, 'qty' => 0,
				);
			}
			$s['sellers'][ $sk ]['gross']  += $gross;
			$s['sellers'][ $sk ]['profit'] += $profit;
			$s['sellers'][ $sk ]['qty']    += $qty;
		}

		// ---- recent activity
		if ( count( $s['recent'] ) < 4 && 'Online' === $type ) {
			$buyer = trim( $order->get_billing_first_name() );
			$s['recent'][] = array(
				'buyer'  => $buyer ? $buyer : 'A supporter',
				'seller' => isset( $name ) ? $name : '',
				'total'  => $gross,
				'qty'    => $qty,
				'ts'     => $ts,
			);
		}

		// ---- order table rows
		if ( count( $s['table'] ) < $config['orders_in_table'] ) {
			$s['table'][] = array(
				'id'       => $order_id,
				'seller'   => isset( $name ) ? $name : '',
				'customer' => trim( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ),
				'date'     => $ts ? date_i18n( 'Y-m-d', $ts ) : '',
				'status'   => ( 'waiting-call' === $status ) ? 'Ship To Group' : wc_get_order_status_name( $status ),
				'raw'      => $status,
				'tracking' => glow_fd_tracking_number( $order_id ),
				'claimed'  => (bool) $order->get_meta( 'claim_requested' ),
				'total'    => $gross,
				'qty'      => $qty,
			);
		}

		unset( $name );
	}

	// ---- ship-to-group tier: aggregate brochure + STG quantity, as in the original
	$agg_qty  = $s['broch_items'] + $s['sts_items'];
	$sts_rate = ( $agg_qty > $bulk_qty ) ? $rate_bulk : $rate_base;

	$s['sts_profit'] = $s['sts_gross'] * ( $sts_rate / 100 );

	foreach ( $sts_orders as $o ) {
		if ( ! $o['ts'] ) continue;
		$day = date( 'Y-m-d', $o['ts'] );
		if ( isset( $s['days'][ $day ] ) ) {
			$s['days'][ $day ] += $o['gross'] * ( $sts_rate / 100 );
		}
	}

	// ---- totals
	$s['total_gross']  = $s['online_gross'] + $s['broch_gross'] + $s['sts_gross'];
	$s['total_profit'] = $s['online_profit'] + $s['broch_profit'] + $s['sts_profit'];

	$s['progress'] = $config['progress_includes_brochure']
		? $s['total_profit']
		: ( $s['online_profit'] + $s['sts_profit'] );

	// ---- trend
	$values = array_values( $s['days'] );
	$prev   = array_sum( array_slice( $values, 0, 7 ) );
	$last   = array_sum( array_slice( $values, 7, 7 ) );
	if ( $prev > 0 )      $s['trend'] = round( ( ( $last - $prev ) / $prev ) * 100 );
	elseif ( $last > 0 )  $s['trend'] = 100;

	// ---- sellers: add registered sellers with zero sales, then sort
	$registered = get_users( array(
		'meta_key'   => glow_fd_meta( 'seller_of' ),
		'meta_value' => $fid,
		'fields'     => array( 'ID', 'display_name' ),
	) );

	foreach ( $registered as $r ) {
		$sk = 'u:' . $r->ID;
		if ( ! isset( $s['sellers'][ $sk ] ) ) {
			$name = trim( get_user_meta( $r->ID, 'first_name', true ) . ' ' . get_user_meta( $r->ID, 'last_name', true ) );
			$s['sellers'][ $sk ] = array(
				'name'       => $name ? $name : $r->display_name,
				'id'         => (int) $r->ID,
				'registered' => true,
				'gross'      => 0, 'profit' => 0, 'qty' => 0,
			);
		}
	}

	$s['seller_count']  = count( $registered );
	$s['manual_count']  = 0;
	foreach ( $s['sellers'] as $sk => $row ) {
		if ( ! $row['registered'] ) $s['manual_count']++;
	}

	uasort( $s['sellers'], function ( $a, $b ) {
		if ( $a['profit'] == $b['profit'] ) return 0;
		return ( $a['profit'] > $b['profit'] ) ? -1 : 1;
	} );

	$s['_profit_min_items'] = GLOW_BROCHURE_PROFIT_MIN_ITEMS;
	set_transient( $key, $s, $config['cache_minutes'] * MINUTE_IN_SECONDS );

	return $s;
}
endif;

/** Tracking number from the order notes, same source as the original table. */
if ( ! function_exists( 'glow_fd_tracking_number' ) ) :
function glow_fd_tracking_number( $order_id ) {

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
   5. CHECKLIST
   ========================================================= */

if ( ! function_exists( 'glow_fd_checklist_steps' ) ) :
function glow_fd_checklist_steps() {
	return array(
		'store'   => 'Customize your store',
		'sellers' => 'Invite your sellers',
		'share'   => 'Share the shop link',
		'orders'  => 'Submit brochure orders',
	);
}
endif;

if ( ! function_exists( 'glow_fd_checklist' ) ) :
function glow_fd_checklist( $fid, $stats ) {

	$saved = get_user_meta( $fid, 'glow_fd_checklist', true );
	if ( ! is_array( $saved ) ) $saved = array();

	// some steps complete themselves from real data
	if ( ! glow_fd_needs_photo( $fid ) )   $saved['store']   = 1;
	if ( $stats['seller_count'] > 0 )      $saved['sellers'] = 1;
	if ( $stats['broch_items'] > 0 )       $saved['orders']  = 1;

	$out = array();
	foreach ( glow_fd_checklist_steps() as $k => $v ) {
		$out[ $k ] = ! empty( $saved[ $k ] );
	}

	return $out;
}
endif;

/* =========================================================
   6. SPARKLINE
   ========================================================= */

if ( ! function_exists( 'glow_fd_sparkline' ) ) :
function glow_fd_sparkline( $days, $w = 300, $h = 132 ) {

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

/* -------------------------------------------------------------------------
   Checklist toggle — nonced link, saved on page load, then redirect.
   (An AJAX handler registered in a template would never fire, because
   admin-ajax.php does not load page templates.)
   ------------------------------------------------------------------------- */
if ( ! function_exists( 'glow_fd_handle_step_toggle' ) ) :
function glow_fd_handle_step_toggle() {

	if ( ! isset( $_GET['glow_fd_step'], $_GET['_wpnonce'] ) ) return;

	$step = sanitize_key( $_GET['glow_fd_step'] );

	if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'glow_fd_step_' . $step ) ) return;
	if ( ! array_key_exists( $step, glow_fd_checklist_steps() ) ) return;

	$uid = get_current_user_id();
	if ( ! $uid ) return;

	$saved = get_user_meta( $uid, 'glow_fd_checklist', true );
	if ( ! is_array( $saved ) ) $saved = array();

	if ( ! empty( $saved[ $step ] ) ) unset( $saved[ $step ] );
	else                              $saved[ $step ] = 1;

	update_user_meta( $uid, 'glow_fd_checklist', $saved );

	wp_safe_redirect( remove_query_arg( array( 'glow_fd_step', '_wpnonce' ) ) );
	exit;
}
endif;

if ( ! function_exists( 'glow_fd_step_url' ) ) :
function glow_fd_step_url( $step ) {
	return wp_nonce_url( add_query_arg( 'glow_fd_step', $step ), 'glow_fd_step_' . $step );
}
endif;

/* Manual cache refresh: ?glow_fd_refresh=1 */
if ( ! function_exists( 'glow_fd_maybe_refresh' ) ) :
function glow_fd_maybe_refresh( $fid ) {
	if ( ! empty( $_GET['glow_fd_refresh'] ) ) {
		delete_transient( 'glow_fd_stats_' . $fid );
		return true;
	}
	return false;
}
endif;

/**
 * OPTIONAL — paste these three lines into functions.php so the numbers update
 * the moment an order changes status instead of waiting out the 5-minute cache:
 *
 *   add_action( 'woocommerce_order_status_changed', function ( $id, $f, $t, $order ) {
 *       $fid = $order->get_meta( 'fundraiser_field' );
 *       if ( $fid ) delete_transient( 'glow_fd_stats_' . absint( $fid ) );
 *   }, 10, 4 );
 */

/* =========================================================================
   PART 2 — RENDER
   ========================================================================= */

/* Handle seller brochure permission toggles */
glow_handle_master_toggle();
glow_handle_seller_toggle();

/* Existing checklist toggle */
glow_fd_handle_step_toggle();

get_header();


$fid = glow_fd_current_fundraiser_id();

if ( ! $fid ) {
	echo '<div class="glow-fd-gate"><h2>Please log in</h2><p>Sign in as a chairperson to see your dashboard.</p>'
	   . '<a class="glow-fd-gate-btn" href="' . esc_url( wp_login_url( get_permalink() ) ) . '">Log in</a></div>';
	get_footer();
	return;
}

glow_fd_maybe_refresh( $fid );

$campaign  = glow_fd_campaign( $fid );
$stats     = glow_fd_stats( $fid );
$checklist = glow_fd_checklist( $fid, $stats );
$config    = glow_fd_config();

$photo   = glow_fd_photo( $fid );
$goal    = $campaign['goal'];
$raised  = $stats['progress'];
$pct     = ( $goal > 0 ) ? min( 100, round( ( $raised / $goal ) * 100 ) ) : 0;
$left    = max( 0, $goal - $raised );

$shop_link   = glow_fd_shop_link( $fid );
$reg_link    = glow_fd_seller_register_link( $fid );
$qr_shop     = get_user_meta( $fid, glow_fd_meta( 'qr_shop' ), true );
$qr_reg      = get_user_meta( $fid, glow_fd_meta( 'qr_seller_reg' ), true );
$cart_saved  = get_user_meta( $fid, glow_fd_meta( 'cart_restore' ), true );
$merge_id    = get_user_meta( $fid, glow_fd_meta( 'merge_id' ), true );
$merge_qty   = get_user_meta( $fid, glow_fd_meta( 'merge_qty' ), true );

$spark = glow_fd_sparkline( $stats['days'] );

$cheer = ( $pct >= 100 ) ? 'Goal reached! 🎉'
       : ( ( $pct >= 50 ) ? 'Over halfway there! 🎉' : 'Off to a good start! 🎉' );

$is_admin_view = glow_fd_is_admin_view( $fid );
?>

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Inter:wght@400;500;600;700;800&display=swap">

<div class="glow-fd">

	<!-- ============ HEADER ============ -->
	<header class="glow-fd-top">

		<div class="glow-fd-brand">
			<div class="glow-fd-brand-word">
				<h2>GLOW</h2>
				<svg width="26" height="26" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 1.5c.6 3.9 2.1 5.4 6 6-3.9.6-5.4 2.1-6 6-.6-3.9-2.1-5.4-6-6 3.9-.6 5.4-2.1 6-6Z" fill="#ff2d7e"/></svg>
			</div>
			<p class="glow-fd-brand-sub"><i></i><span>SCENTED CANDLES</span><i></i></p>
		</div>

		<div class="glow-fd-top-rule" aria-hidden="true"></div>

		<div class="glow-fd-campaign">
			<h1><?php echo esc_html( $campaign['org'] ); ?></h1>

			<div class="glow-fd-meta">
				<?php if ( $campaign['closed'] ) : ?>
					<span class="glow-fd-pill is-off">Campaign Closed</span>
				<?php elseif ( $campaign['active'] ) : ?>
					<span class="glow-fd-pill is-on">Campaign Active</span>
				<?php else : ?>
					<span class="glow-fd-pill is-warn">End date passed</span>
				<?php endif; ?>

				<?php if ( $campaign['start_fmt'] ) : ?>
					<span class="glow-fd-dates">
						<svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.7" stroke-linecap="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="3"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
						<?php echo esc_html( $campaign['start_fmt'] . ' – ' . $campaign['end_fmt'] ); ?>
					</span>
				<?php endif; ?>

				<span class="glow-fd-meta-rule" aria-hidden="true"></span>

				<span class="glow-fd-code">
					Fundraiser code
					<b id="glow-fd-code"><?php echo esc_html( $fid ); ?></b>
					<button type="button" class="glow-fd-code-copy" data-copy="<?php echo esc_attr( $fid ); ?>">Copy</button>
				</span>

				<?php if ( $campaign['active'] ) : ?>
					<p class="glow-fd-countdown">
						<b><?php echo esc_html( $campaign['days_left'] ); ?>
						<?php echo esc_html( _n( 'day left', 'days left', $campaign['days_left'] ) ); ?></b>—keep your sellers sharing.
					</p>
				<?php endif; ?>
			</div>
		</div>

		<div class="glow-fd-decor" aria-hidden="true">
			<div class="glow-fd-candles">
				<div class="glow-fd-candle is-red"><div class="wick"></div><div class="flame"></div><div class="glass"><div class="rim"></div></div><div class="label">GLOW</div></div>
				<div class="glow-fd-candle is-snow"><div class="wick"></div><div class="flame"></div><div class="glass"><div class="rim"></div></div><div class="label">SNOW DAY<small>Frost · Coconut · Pine</small></div></div>
				<div class="glow-fd-candle is-white"><div class="wick"></div><div class="flame"></div><div class="glass"><div class="rim"></div></div><div class="label">GLOW</div></div>
			</div>
			<span class="glow-fd-confetti" style="left:130px;top:44px;background:#ff2d7e;transform:rotate(28deg);"></span>
			<span class="glow-fd-confetti" style="left:146px;top:66px;background:#ff2d7e;transform:rotate(28deg);"></span>
			<span class="glow-fd-confetti" style="left:120px;top:88px;background:#ff2d7e;transform:rotate(28deg);"></span>
			<span class="glow-fd-confetti" style="right:74px;top:24px;background:#ffd166;transform:rotate(-38deg);"></span>
			<span class="glow-fd-confetti" style="right:44px;top:44px;background:#4fd6ff;transform:rotate(-38deg);"></span>
			<span class="glow-fd-confetti" style="right:34px;top:74px;background:#a06bff;transform:rotate(-30deg);"></span>
		</div>
	</header>

	<main class="glow-fd-board">

		<!-- ---------- NOTICES ---------- -->
		<?php if ( $is_admin_view ) : ?>
			<div class="glow-fd-notice is-admin">
				Admin view — you are looking at fundraiser #<?php echo esc_html( $fid ); ?>.
				<a id="merge_order_ajax" data-id="<?php echo esc_attr( $fid ); ?>" href="#">Merge ship-to-group order</a>
			</div>
		<?php endif; ?>

		<?php if ( $campaign['closed'] ) : ?>
			<div class="glow-fd-notice is-done">
				Your GLOW fundraiser is closed out. Online sales profits are sent on the 15th of the month. Thank you for fundraising with GLOW.
				<a id="rebook_ajax" href="#">Book your next campaign</a>
			</div>
		<?php endif; ?>

		<?php if ( glow_fd_needs_photo( $fid ) ) : ?>
			<div class="glow-fd-notice is-todo">
				Your online store is missing a photo or message — groups with both raise noticeably more.
				<a href="<?php echo esc_url( glow_fd_url( 'customize' ) ); ?>">Add them now</a>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $cart_saved ) ) : ?>
			<div class="glow-fd-notice is-cart">
				You have a saved brochure order waiting.
				<a href="#" id="savedOrder">Move it to my cart</a>
				<img id="loading_r" class="glow-fd-spin" src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/08/ajax-loader.gif" alt="">
			</div>
		<?php endif; ?>

		<!-- ---------- ROW 1 ---------- -->
		<section class="glow-fd-row glow-fd-row1">

			<!-- Chairperson -->
			<div class="glow-fd-card glow-fd-profile">
				<div>
					<div class="glow-fd-profile-top">
						<div class="glow-fd-avatar">
							<?php if ( $photo ) : ?>
								<img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $campaign['org'] ); ?>">
							<?php else : ?>
								<svg viewBox="0 0 100 100" role="img" aria-label="No logo yet">
									<rect width="100" height="100" rx="50" fill="#ffe1e8"/>
									<circle cx="50" cy="38" r="17" fill="#f7c9b0"/>
									<path d="M22 92c3-16 14-24 28-24s25 8 28 24H22Z" fill="#e05a86"/>
								</svg>
							<?php endif; ?>
						</div>
						<div>
							<h3 class="glow-fd-hi">
								Hi<?php echo $campaign['chair'] ? ', ' . esc_html( current( explode( ' ', $campaign['chair'] ) ) ) : ''; ?>!
								<svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2c.6 4.4 2.1 6 6 6.6-3.9.6-5.4 2.2-6 6.6-.6-4.4-2.1-6-6-6.6 3.9-.6 5.4-2.2 6-6.6Z" fill="#ff2d7e"/></svg>
							</h3>
							<p class="glow-fd-role">
								Chairperson<br>
								<?php echo esc_html( $stats['seller_count'] ); ?> registered
								<?php echo esc_html( _n( 'seller', 'sellers', $stats['seller_count'] ) ); ?>
							</p>
						</div>
					</div>
				</div>

				<div class="glow-fd-profile-actions">
					<a class="glow-fd-profile-btn" href="<?php echo esc_url( $shop_link ); ?>" target="_blank" rel="noopener">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2c2340" stroke-width="1.7" stroke-linejoin="round" aria-hidden="true"><path d="M3.6 4.5h16.8l1.4 4.2a3 3 0 0 1-5.7 1.6 3 3 0 0 1-5.6 0 3 3 0 0 1-5.7-1.6L3.6 4.5Z"/><path d="M4.8 11.4V20h14.4v-8.6"/></svg>
						View My Store
						<svg class="chev" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2c2340" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg>
					</a>
					<a class="glow-fd-profile-link" href="<?php echo esc_url( add_query_arg( 'frid', $fid, glow_fd_url( 'edit_info' ) ) ); ?>">Edit fundraiser info</a>
				</div>
			</div>

			<!-- Goal -->
			<div class="glow-fd-card glow-fd-goal">
				<div class="glow-fd-donut">
					<div class="glow-fd-donut-in">
						<span class="amt">$<?php echo esc_html( number_format( $raised, 0 ) ); ?></span>
						<span class="of">of $<?php echo esc_html( number_format( $goal, 0 ) ); ?><br>goal</span>
					</div>
				</div>

				<div class="glow-fd-goal-body">
					<div class="glow-fd-goal-eyebrow">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
							<circle cx="11" cy="13" r="8" stroke="#ec0f63" stroke-width="1.8"/>
							<circle cx="11" cy="13" r="3.4" stroke="#ec0f63" stroke-width="1.8"/>
							<path d="m14.5 9.5 5-5M17 4.5h3v3" stroke="#ec0f63" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						GROUP GOAL
					</div>

					<div class="glow-fd-goal-target">$<?php echo esc_html( number_format( $goal, 0 ) ); ?></div>

					<div class="glow-fd-goal-bar">
						<div class="glow-fd-bar"><span style="width:<?php echo esc_attr( $pct ); ?>%"></span></div>
						<div class="glow-fd-pct"><?php echo esc_html( $pct ); ?>%</div>
					</div>

					<p class="glow-fd-cheer"><?php echo esc_html( $cheer ); ?></p>
					<p class="glow-fd-cheer-sub">
						<?php if ( $left > 0 ) : ?>
							$<?php echo esc_html( number_format( $left, 2 ) ); ?> to go.
						<?php else : ?>
							You've passed your goal — anything more is bonus.
						<?php endif; ?>
					</p>
				</div>
			</div>

			<!-- Share links -->
			<div class="glow-fd-card glow-fd-share">
				<div class="glow-fd-share-main">
					<div class="glow-fd-head">
						<svg width="26" height="26" viewBox="0 0 24 24" aria-hidden="true"><path d="M21.5 3 2.8 10.4c-.9.4-.8 1.6.1 1.8l4.6 1.3 1.4 4.8c.3.9 1.5 1 1.9.2L21.5 3Z" fill="#17b98a"/><path d="M21.5 3 9.1 18.5l-.5-4.9L21.5 3Z" fill="#0f8f6b"/></svg>
						<h3>Share Your Links</h3>
					</div>
					<p class="glow-fd-share-sub">One link signs sellers up. The other sends supporters straight to your store.</p>

					<label class="glow-fd-linklabel">Seller registration link</label>
					<div class="glow-fd-linkbox">
						<input type="text" readonly value="<?php echo esc_attr( preg_replace( '#^https?://#', '', $reg_link ) ); ?>" aria-label="Seller registration link">
						<button type="button" class="glow-fd-copy" data-copy="<?php echo esc_attr( $reg_link ); ?>">Copy</button>
					</div>

					<label class="glow-fd-linklabel">Store shopping link</label>
					<div class="glow-fd-linkbox">
						<input type="text" readonly value="<?php echo esc_attr( preg_replace( '#^https?://#', '', $shop_link ) ); ?>" aria-label="Store shopping link">
						<button type="button" class="glow-fd-copy" data-copy="<?php echo esc_attr( $shop_link ); ?>">Copy</button>
					</div>

					<div class="glow-fd-share-grid">
						<a class="glow-fd-sbtn is-text" href="sms:?&body=<?php echo rawurlencode( 'Support ' . $campaign['org'] . ' — shop GLOW candles here: ' . $shop_link ); ?>">
							<svg width="21" height="21" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M12 3c5 0 9 3.3 9 7.4 0 4.1-4 7.4-9 7.4-1 0-2-.1-2.9-.4L4 19.5l1.2-3.3C3.8 14.8 3 12.7 3 10.4 3 6.3 7 3 12 3Z"/></svg>
							Text the link
						</a>
						<a class="glow-fd-sbtn is-fb" target="_blank" rel="noopener" href="https://www.facebook.com/sharer.php?u=<?php echo rawurlencode( $shop_link ); ?>">
							<svg width="21" height="21" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.2c-1.2 0-1.6.8-1.6 1.6V12h2.7l-.4 2.9h-2.3v7A10 10 0 0 0 22 12Z"/></svg>
							Facebook
						</a>
						<a class="glow-fd-sbtn is-wa" target="_blank" rel="noopener" href="https://wa.me/?text=<?php echo rawurlencode( $shop_link ); ?>">
							<svg width="21" height="21" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.5 15.2L2 22l4.9-1.4A10 10 0 1 0 12 2Zm5.3 14c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .1-1.7-.1a12 12 0 0 1-5.4-4.6c-.4-.6-.9-1.5-.9-2.4 0-.9.5-1.4.7-1.6.2-.2.4-.3.6-.3h.5c.2 0 .4 0 .6.5l.7 1.8c.1.2 0 .4-.1.5l-.4.5c-.1.1-.3.3-.1.6.2.3.7 1.2 1.5 1.9 1 .9 1.8 1.1 2.1 1.3.2.1.4 0 .5-.1l.7-.8c.2-.2.3-.2.6-.1l1.6.8c.3.1.5.2.5.3.1.2.1.6 0 1.1Z"/></svg>
							WhatsApp
						</a>
						<a class="glow-fd-sbtn is-letter" href="<?php echo esc_url( add_query_arg( 'fund_id', $fid, glow_fd_url( 'participant_ltr' ) ) ); ?>">
							<svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linejoin="round" aria-hidden="true"><path d="M5 3.5h9l5 5V20.5H5z"/><path d="M14 3.5v5h5M8 13h8M8 16.5h5"/></svg>
							Participant letter
						</a>
					</div>
				</div>

				<div class="glow-fd-qr-wrap">
					<?php if ( $qr_reg ) : ?>
						<div class="glow-fd-qr-item">
							<img class="glow-fd-qr" src="<?php echo esc_url( $qr_reg ); ?>" alt="QR code for seller registration" loading="lazy">
							<span>Seller sign-up</span>
							<a href="<?php echo esc_url( $qr_reg ); ?>" download>Download</a>
						</div>
					<?php endif; ?>
					<?php if ( $qr_shop ) : ?>
						<div class="glow-fd-qr-item">
							<img class="glow-fd-qr" src="<?php echo esc_url( $qr_shop ); ?>" alt="QR code for the shop link" loading="lazy">
							<span>Shop link</span>
							<a href="<?php echo esc_url( $qr_shop ); ?>" download>Download</a>
						</div>
					<?php endif; ?>
					<?php if ( ! $qr_reg && ! $qr_shop ) : ?>
						<p class="glow-fd-qr-empty">QR codes appear here once they're generated for your campaign.</p>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<!-- ---------- ROW 2 ---------- -->
		<section class="glow-fd-row glow-fd-row2">

			<a class="glow-fd-tile is-store" href="<?php echo esc_url( glow_fd_url( 'customize' ) ); ?>">
				<span class="glow-fd-tile-icon">
					<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.5" stroke-linejoin="round" aria-hidden="true"><path d="M3.6 4.5h16.8l1.4 4.2a3 3 0 0 1-5.7 1.6 3 3 0 0 1-5.6 0 3 3 0 0 1-5.7-1.6L3.6 4.5Z"/><path d="M4.8 11.4V20h14.4v-8.6M10 20v-5h4v5"/></svg>
				</span>
				<span class="glow-fd-tile-txt">
					<strong>Customize Our Store</strong>
					<span>Add your logo, photo &amp; a message for supporters.</span>
				</span>
				<span class="glow-fd-tile-chev"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg></span>
			</a>

			<button type="button" class="glow-fd-tile is-brochure" data-open="glow-fd-brochures">
				<span class="glow-fd-tile-icon">
					<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.5" stroke-linejoin="round" aria-hidden="true"><path d="M12 6.5C10.3 5.2 8 4.6 4.5 4.8v13c3.5-.2 5.8.4 7.5 1.7 1.7-1.3 4-1.9 7.5-1.7v-13c-3.5-.2-5.8.4-7.5 1.7Z"/><path d="M12 6.5v13"/></svg>
				</span>
				<span class="glow-fd-tile-txt">
					<strong>Submit Brochure Orders</strong>
					<span>Enter your group's paper order forms.</span>
					<span class="glow-fd-badge">3 brochures</span>
				</span>
			</button>

			<a class="glow-fd-tile is-reports" href="<?php echo esc_url( glow_fd_url( 'report' ) ); ?>">
				<span class="glow-fd-tile-icon">
					<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#4a3300" stroke-width="1.5" stroke-linejoin="round" aria-hidden="true"><path d="M5 3.5h9l5 5V20.5H5z"/><path d="M14 3.5v5h5M8.5 13v4M12 11v6M15.5 14v3"/></svg>
				</span>
				<span class="glow-fd-tile-txt">
					<strong>Sales Reports</strong>
					<span>Full order breakdown &amp; final printable report.</span>
				</span>
				<span class="glow-fd-tile-chev"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#6b4a00" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg></span>
			</a>

			<div class="glow-fd-card glow-fd-checklist">
				<div class="glow-fd-checklist-intro">
					<div class="glow-fd-head">
						<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#3d3b52" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4.5" y="3.5" width="15" height="17" rx="2.5"/><path d="M9 3.5h6v2.6H9zM8.6 11h6.8M8.6 15h4.6"/></svg>
						<div>
							<h3>Chairperson Checklist</h3>
							<p>Four steps that set your group up to hit goal.</p>
						</div>
					</div>
				</div>

				<div class="glow-fd-steps">
					<?php $n = 0; foreach ( glow_fd_checklist_steps() as $k => $label ) : $n++; ?>
						<a class="glow-fd-step" href="<?php echo esc_url( glow_fd_step_url( $k ) ); ?>"
						   aria-pressed="<?php echo $checklist[ $k ] ? 'true' : 'false'; ?>">
							<span class="glow-fd-step-num"><span><?php echo esc_html( $n ); ?></span></span>
							<span class="glow-fd-step-label"><?php echo esc_html( $label ); ?></span>
							<span class="glow-fd-step-check"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4.5 4.5L19 7"/></svg></span>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<!-- brochure links, revealed by the tile -->
		<section class="glow-fd-card glow-fd-drawer" id="glow-fd-brochures" hidden>
			<div class="glow-fd-head"><h3>Enter a brochure order</h3></div>
			<div class="glow-fd-brochure-links">
				<a href="<?php echo esc_url( glow_fd_url( 'brochure_ss' ) ); ?>">Spring / Summer Brochure<span>Enter order</span></a>
				<a href="<?php echo esc_url( glow_fd_url( 'brochure_new' ) ); ?>">Perfect Scents for Every Season<span>Enter order</span></a>
				<a href="<?php echo esc_url( glow_fd_url( 'brochure_fw' ) ); ?>">Season to Sparkle Brochure<span>Enter order</span></a>
			</div>
			<p class="glow-fd-brochure-total">
				Brochure sales so far: <b>$<?php echo esc_html( number_format( $stats['broch_gross'], 2 ) ); ?></b>
				· amount due to GLOW: <b>$<?php echo esc_html( number_format( $stats['broch_due'], 2 ) ); ?></b>
			</p>
		</section>

		<!-- ---------- ROW 3 ---------- -->
		<section class="glow-fd-row glow-fd-row3">

			<!-- Sales -->
			<div class="glow-fd-card">
				<div class="glow-fd-head">
					<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ec0f63" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 16.5 8.5 11l3.5 3.5L20.5 6"/><path d="M15.5 6h5v5"/></svg>
					<h3>Sales to Date</h3>
					<a class="glow-fd-refresh" href="<?php echo esc_url( add_query_arg( 'glow_fd_refresh', 1 ) ); ?>" title="Recalculate now">Refresh</a>
				</div>

				<div class="glow-fd-sales-body">
					<div class="glow-fd-figures">
						<div class="glow-fd-big">$<?php echo esc_html( number_format( $stats['total_gross'], 0 ) ); ?></div>
						<p class="glow-fd-big-sub">
							total sales · <b class="glow-fd-green">$<?php echo esc_html( number_format( $stats['total_profit'], 2 ) ); ?></b> profit
						</p>

						<?php if ( 0 != $stats['trend'] ) : ?>
							<div class="glow-fd-trend <?php echo $stats['trend'] < 0 ? 'is-down' : ''; ?>">
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
									<?php if ( $stats['trend'] < 0 ) : ?><path d="M6 6 18 18"/><path d="M18 9v9H9"/><?php else : ?><path d="M6 18 18 6"/><path d="M9 6h9v9"/><?php endif; ?>
								</svg>
								<?php echo esc_html( ( $stats['trend'] > 0 ? '+' : '' ) . $stats['trend'] ); ?>%
							</div>
							<p class="glow-fd-trend-sub">vs last 7 days</p>
						<?php endif; ?>
					</div>

					<div class="glow-fd-chart">
						<svg viewBox="0 0 <?php echo esc_attr( $spark['w'] . ' ' . $spark['h'] ); ?>" preserveAspectRatio="none" role="img" aria-label="Daily profit for the last seven days">
							<defs><linearGradient id="glowFdFill" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#ec0f63" stop-opacity=".30"/><stop offset="100%" stop-color="#ec0f63" stop-opacity=".02"/></linearGradient></defs>
							<path d="<?php echo esc_attr( $spark['area'] ); ?>" fill="url(#glowFdFill)"/>
							<polyline points="<?php echo esc_attr( $spark['line'] ); ?>" fill="none" stroke="#ec0f63" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round"/>
							<?php foreach ( $spark['points'] as $i => $p ) : ?>
								<circle cx="<?php echo esc_attr( $p[0] ); ?>" cy="<?php echo esc_attr( $p[1] ); ?>" r="5.6" <?php echo ( 0 === $i % 3 ) ? 'fill="#fff" stroke="#ec0f63" stroke-width="3.2"' : 'fill="#ec0f63"'; ?>/>
							<?php endforeach; ?>
						</svg>
					</div>
				</div>

				<div class="glow-fd-breakdown">
					<div><span>Online</span><b><?php echo esc_html( $stats['online_items'] ); ?> items</b><em>$<?php echo esc_html( number_format( $stats['online_profit'], 2 ) ); ?></em></div>
					<div><span>Brochure</span><b><?php echo esc_html( $stats['broch_items'] ); ?> items</b><em>$<?php echo esc_html( number_format( $stats['broch_profit'], 2 ) ); ?></em></div>
					<div><span>Ship-to-group</span><b><?php echo esc_html( $stats['sts_items'] ); ?> items</b><em>$<?php echo esc_html( number_format( $stats['sts_profit'], 2 ) ); ?></em></div>
				</div>
			</div>

			<!-- Orders -->
			<div class="glow-fd-card">
				<div class="glow-fd-head">
					<svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M2.5 6.5h10v9h-10z" fill="#6236e0"/><path d="M12.5 9.5h4l3 3v3h-7z" fill="#6236e0"/><circle cx="7" cy="18" r="1.9" fill="#6236e0"/><circle cx="16.5" cy="18" r="1.9" fill="#6236e0"/></svg>
					<h3>Order Tracking</h3>
				</div>

				<div class="glow-fd-orders-body">
					<div class="glow-fd-orders-count">
						<div class="glow-fd-big"><?php echo esc_html( $stats['order_count'] ); ?></div>
						<p>orders</p>
					</div>
					<div class="glow-fd-statuses">
						<div class="glow-fd-status is-processing"><span class="dot"><?php echo esc_html( $stats['processing'] ); ?></span> Processing</div>
						<div class="glow-fd-status is-shipped"><span class="dot"><?php echo esc_html( $stats['shipped'] ); ?></span> Shipped</div>
						<div class="glow-fd-status is-delivered"><span class="dot"><?php echo esc_html( $stats['delivered'] ); ?></span> Delivered</div>
					</div>
				</div>

				<a class="glow-fd-ghost is-purple" href="#glow-fd-orders">View all orders</a>
			</div>

			<!-- Sellers -->
			<div class="glow-fd-card">
				<div class="glow-fd-head">
					<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ec0f63" stroke-width="1.9" stroke-linecap="round" aria-hidden="true"><circle cx="9" cy="8" r="3.4"/><path d="M2.5 19.5c1.2-3.5 3.5-5 6.5-5s5.3 1.5 6.5 5"/><path d="M16.5 5.2a3.4 3.4 0 0 1 0 5.6M18 14.8c2 .7 3.2 2.2 3.9 4.7"/></svg>
					<h3>Sellers</h3>
					<span class="glow-fd-count-pill"><?php echo esc_html( $stats['seller_count'] ); ?> registered<?php if ( $stats['manual_count'] ) : ?> · <?php echo esc_html( $stats['manual_count'] ); ?> manual<?php endif; ?></span>
				</div>

				<div class="glow-fd-sellers">
					<?php if ( empty( $stats['sellers'] ) ) : ?>
						<p class="glow-fd-empty">No sellers yet. Share the registration link to get your group started.</p>
					<?php else : ?>
						<div class="glow-fd-sellers-head">
							<span>Seller</span><span>Sales</span><span>Profit</span><span>Qty</span>
						</div>
						<div class="glow-fd-sellers-scroll">
							<?php $rank = 0; foreach ( $stats['sellers'] as $row ) : $rank++; ?>
								<div class="glow-fd-seller-row">
									<span class="glow-fd-seller-name">
										<i class="glow-fd-rank<?php echo $rank <= 3 ? ' is-top' : ''; ?>"><?php echo esc_html( $rank ); ?></i>
										<?php echo esc_html( $row['name'] ); ?>
										<?php if ( ! $row['registered'] ) : ?><em>manual</em><?php endif; ?>
									</span>
									<span>$<?php echo esc_html( number_format( $row['gross'], 2 ) ); ?></span>
									<span class="glow-fd-green">$<?php echo esc_html( number_format( $row['profit'], 2 ) ); ?></span>
									<span><?php echo esc_html( $row['qty'] ); ?></span>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<!-- ---------- SELLER BROCHURE ORDERS ---------- -->

		<div class="glow-sbo">

		<div class="glow-sbo-head">

			<h4>Seller Brochure Orders</h4>

			<?php
			$glow_master = glow_master_allows( $fid );

			$glow_master_url = wp_nonce_url(
				add_query_arg(
					'glow_master',
					$fid
				),
				'glow_master_' . $fid
			);
			?>

			<a
				class="glow-btn <?php echo $glow_master ? 'is-on' : 'is-off'; ?>"
				href="<?php echo esc_url( $glow_master_url ); ?>"
			>
				<?php
				echo $glow_master
					? 'ON FOR ALL'
					: 'OFF FOR ALL';
				?>
			</a>

		</div>


		<div class="glow-sbo-body">

			<div class="glow-row glow-row-head">

				<span class="glow-name">
					Seller
				</span>

				<span class="glow-action">
					Brochure Order
				</span>

			</div>


			<?php

			$glow_sellers = get_users(
				array(
					'meta_key'   => glow_fd_meta( 'seller_of' ),
					'meta_value' => $fid,
				)
			);

			?>


			<?php if ( empty( $glow_sellers ) ) : ?>

				<div class="glow-row glow-empty">
					No sellers registered yet.
				</div>

			<?php else : ?>

				<?php foreach ( $glow_sellers as $glow_s ) : ?>

					<?php

					$s_on = glow_seller_allows(
						$glow_s->ID,
						$fid
					);

					$s_url = wp_nonce_url(
						add_query_arg(
							'glow_seller',
							$glow_s->ID
						),
						'glow_seller_' . $glow_s->ID
					);

					$s_name = trim(
						$glow_s->first_name . ' ' .
						$glow_s->last_name
					);

					if ( '' === $s_name ) {
						$s_name = $glow_s->display_name;
					}

					if ( '' === trim( $s_name ) ) {
						$s_name = $glow_s->user_email;
					}

					?>

					<div class="glow-row">

						<span class="glow-name">
							<?php echo esc_html( $s_name ); ?>
						</span>

						<span class="glow-action">

							<a
								class="glow-btn <?php echo $s_on ? 'is-on' : 'is-off'; ?>"
								href="<?php echo esc_url( $s_url ); ?>"
							>
								<?php
								echo $s_on
									? 'ALLOWED'
									: 'BLOCKED';
								?>
							</a>

						</span>

					</div>

				<?php endforeach; ?>

			<?php endif; ?>

		</div>

		</div>

		<!-- ---------- ROW 5: seller brochure orders ---------- -->
		<?php
		if ( function_exists( 'glow_so_get_fundraiser_orders' ) ) :
			$glow_orders = glow_so_get_fundraiser_orders( $fid );
			$glow_new    = array_filter( $glow_orders, function ( $o ) { return 'moved' !== $o->status; } );
		?>
		<section class="glow-fd-card">
			<div class="glow-fd-head">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#f5741a" stroke-width="1.6" stroke-linejoin="round" aria-hidden="true"><path d="M12 6.5C10.3 5.2 8 4.6 4.5 4.8v13c3.5-.2 5.8.4 7.5 1.7 1.7-1.3 4-1.9 7.5-1.7v-13c-3.5-.2-5.8.4-7.5 1.7Z"/><path d="M12 6.5v13"/></svg>
				<h3>Seller Brochure Orders</h3>
				<span class="glow-fd-count-pill"><?php echo count( $glow_new ); ?> new</span>
			</div>

			<?php if ( empty( $glow_orders ) ) : ?>
				<p class="glow-fd-empty">No seller orders submitted yet.</p>
			<?php else : ?>
				<div class="glow-fd-table-wrap">
					<table class="glow-fd-table">
						<thead>
							<tr><th>Seller</th><th>Catalog</th><th>Date</th><th>Items</th><th>Total</th><th>Money received</th></tr>
						</thead>
						<tbody>
						<?php foreach ( $glow_orders as $o ) : ?>
							<tr>
								<td>
									<?php echo esc_html( $o->seller_name ); ?>
									<a href="#" class="glow-view" data-order="<?php echo esc_attr( $o->id ); ?>">view items</a>
								</td>
								<td><?php echo esc_html( glow_so_catalog_label( $o->catalog ) ); ?></td>
								<td><?php echo esc_html( date_i18n( 'M j', strtotime( $o->submitted_at ) ) ); ?></td>
								<td><?php echo (int) $o->item_count; ?></td>
								<td>$<?php echo esc_html( number_format( $o->order_total, 2 ) ); ?></td>
								<td>
									<?php if ( in_array( $o->status, array( 'moved', 'placed' ), true ) ) : ?>
										<em class="glow-moved"><?php echo 'placed' === $o->status ? 'Order placed' : 'Moved to cart'; ?></em>
									<?php else : ?>
										<label class="glow-fd-check">
											<input type="checkbox" class="glow-money"
											       data-order="<?php echo esc_attr( $o->id ); ?>"
											       data-nonce="<?php echo esc_attr( wp_create_nonce( 'glow_money_received' ) ); ?>"
											       <?php checked( $o->money_received, 1 ); ?>>
											<span class="glow-money-msg"></span>
										</label>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endif; ?>
		</section>
		<?php endif; ?>

		<!-- ---------- ROW 6: order status + tracking ---------- -->
		<section class="glow-fd-card" id="glow-fd-orders">
			<div class="glow-fd-head">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#0fa47a" stroke-width="1.7" stroke-linejoin="round" aria-hidden="true"><path d="M3 7.5 12 3l9 4.5-9 4.5z"/><path d="M3 7.5v9L12 21l9-4.5v-9M12 12v9"/></svg>
				<h3>Order Status &amp; Tracking</h3>
				<span class="glow-fd-count-pill"><?php echo count( $stats['table'] ); ?> shown</span>
			</div>

			<?php if ( empty( $stats['table'] ) ) : ?>
				<p class="glow-fd-empty">No orders yet.</p>
			<?php else : ?>
				<div class="glow-fd-table-wrap">
					<table class="glow-fd-table">
						<thead>
							<tr>
								<th>Order</th><th>Seller</th><th>Customer</th><th>Date</th>
								<th>Status</th><th>Tracking</th><th>Missing / damaged</th><th>Invoice</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ( $stats['table'] as $row ) : ?>
							<tr>
								<td>#<?php echo esc_html( $row['id'] ); ?></td>
								<td><?php echo esc_html( $row['seller'] ); ?></td>
								<td><?php echo esc_html( $row['customer'] ); ?></td>
								<td><?php echo esc_html( $row['date'] ); ?></td>
								<td><span class="glow-fd-tag is-<?php echo esc_attr( $row['raw'] ); ?>"><?php echo esc_html( $row['status'] ); ?></span></td>
								<td>
									<?php if ( $row['tracking'] ) : ?>
										<a target="_blank" rel="noopener" href="https://www.ups.com/track?loc=en_US&tracknum=<?php echo esc_attr( $row['tracking'] ); ?>&requester=WT/trackdetails"><?php echo esc_html( $row['tracking'] ); ?></a>
									<?php else : ?>
										—
									<?php endif; ?>
								</td>
								<td>
									<?php if ( 'completed' === $row['raw'] ) : ?>
										<?php if ( $row['claimed'] ) : ?>
											<span class="glow-fd-green">Report submitted</span>
										<?php else : ?>
											<a target="_blank" rel="noopener" href="<?php echo esc_url( home_url( '/my-account/view-my-order/' . $row['id'] ) ); ?>">Submit</a>
										<?php endif; ?>
									<?php else : ?>
										—
									<?php endif; ?>
								</td>
								<td>
									<?php if ( 'completed' === $row['raw'] ) : ?>
										<a class="glow-fd-mini" target="_blank" rel="noopener"
										   href="<?php echo esc_url( add_query_arg( array(
												'action'        => 'generate_wpo_wcpdf',
												'document_type' => 'invoice',
												'order_ids'     => $row['id'],
												'access_key'    => $config['invoice_key'],
										   ), admin_url( 'admin-ajax.php' ) ) ); ?>">Invoice</a>
									<?php else : ?>
										—
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endif; ?>
		</section>

		<!-- ---------- CLOSE OUT ---------- -->
		<?php if ( ! $campaign['closed'] ) : ?>
		<section class="glow-fd-card glow-fd-closeout">
			<div>
				<h3>Close out this fundraiser</h3>
				<p>Only close out when every order is in. Online sales profits are sent on the 15th of the month after closing.
				<?php if ( ! $campaign['virtual'] ) : ?> Don't close out if you still have brochure orders to submit.<?php endif; ?></p>
			</div>
			<button type="button" id="close_out" data-id="<?php echo esc_attr( $fid ); ?>">
				<?php echo $campaign['virtual'] ? 'End virtual fundraiser' : 'Close out'; ?>
			</button>
		</section>
		<?php endif; ?>

	</main>
</div>

<!-- close-out confirm -->
<div class="glow-fd-modal" id="glow-fd-closeout-modal">
	<div class="glow-fd-modal-box">
		<h3>Close this campaign?</h3>
		<p>You will no longer be able to submit orders for <?php echo esc_html( $campaign['org'] ); ?>. Print any reports you need first.</p>
		<div class="glow-fd-modal-btns">
			<button type="button" id="close_out_ajax" data-id="<?php echo esc_attr( $fid ); ?>">Yes, close it out</button>
			<button type="button" class="glow-fd-modal-cancel">Cancel</button>
		</div>
		<p class="glow-fd-modal-msg"></p>
	</div>
</div>

<div class="glow-fd-toast" id="glow-fd-toast" role="status" aria-live="polite"></div>

<script>
var GLOW_FD = {
	ajax:      "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>",
	fid:       "<?php echo esc_js( $fid ); ?>",
	needMerge: <?php echo ( empty( $merge_id ) && empty( $merge_qty ) ) ? 'true' : 'false'; ?>
};
</script>

<script>
(function () {
	'use strict';

	var toastEl = document.getElementById('glow-fd-toast'), t;
	function toast(m) {
		toastEl.textContent = m;
		toastEl.classList.add('is-open');
		clearTimeout(t);
		t = setTimeout(function () { toastEl.classList.remove('is-open'); }, 2200);
	}

	/* ---- copy buttons ---- */
	document.querySelectorAll('[data-copy]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var val = btn.getAttribute('data-copy');

			function done() {
				toast('Copied');
				var o = btn.textContent;
				btn.textContent = 'Copied!';
				btn.classList.add('is-done');
				setTimeout(function () { btn.textContent = o; btn.classList.remove('is-done'); }, 1500);
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
		});
	});

	/* ---- brochure drawer ---- */
	var opener = document.querySelector('[data-open]');
	if (opener) {
		opener.addEventListener('click', function () {
			var el = document.getElementById(opener.getAttribute('data-open'));
			if (!el) return;
			el.hidden = !el.hidden;
			if (!el.hidden) el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
		});
	}

	/* ---- close out ---- */
	var modal = document.getElementById('glow-fd-closeout-modal');
	var openBtn = document.getElementById('close_out');

	if (openBtn) openBtn.addEventListener('click', function () { modal.classList.add('is-open'); });

	var cancel = modal.querySelector('.glow-fd-modal-cancel');
	if (cancel) cancel.addEventListener('click', function () { modal.classList.remove('is-open'); });

	var yes = document.getElementById('close_out_ajax');
	if (yes) {
		yes.addEventListener('click', function () {
			var msg = modal.querySelector('.glow-fd-modal-msg');
			yes.disabled = true;
			msg.textContent = 'Closing out…';

			jQuery.post(GLOW_FD.ajax, { action: 'close_out_fundraiser', fund_id: GLOW_FD.fid })
				.done(function (res) {
					if (GLOW_FD.needMerge) {
						msg.textContent = 'Processing your ship-to-group order…';
						jQuery.post(GLOW_FD.ajax, { action: 'wpm_order_merge_ajax', fid: GLOW_FD.fid })
							.always(function () { location.reload(); });
					} else {
						location.reload();
					}
				})
				.fail(function () { yes.disabled = false; msg.textContent = 'Something went wrong. Please try again.'; });
		});
	}

	/* ---- saved cart / merge / rebook (unchanged endpoints) ---- */
	var saved = document.getElementById('savedOrder');
	if (saved) {
		saved.addEventListener('click', function (e) {
			e.preventDefault();
			document.getElementById('loading_r').style.display = 'inline-block';
			jQuery.post(GLOW_FD.ajax, { action: 'wdm_restore_cart_details' }).always(function () {
				window.location.href = '<?php echo esc_js( wc_get_checkout_url() ); ?>';
			});
		});
	}

	var merge = document.getElementById('merge_order_ajax');
	if (merge) {
		merge.addEventListener('click', function (e) {
			e.preventDefault();
			toast('Merging…');
			jQuery.post(GLOW_FD.ajax, { action: 'wpm_order_merge_ajax', fid: merge.getAttribute('data-id') })
				.always(function () { toast('Merge complete'); });
		});
	}

	var rebook = document.getElementById('rebook_ajax');
	if (rebook) {
		rebook.addEventListener('click', function (e) {
			e.preventDefault();
			if (!confirm('Booking a new campaign removes access to this dashboard and its reports. Print anything you need first. Continue?')) return;
			jQuery.post(GLOW_FD.ajax, { action: 'wp_rebooking_ajax', rebook_id: GLOW_FD.fid, yes_action: 'yes' })
				.always(function () { window.location.href = '<?php echo esc_js( home_url( '/fundraiser-register/' ) ); ?>'; });
		});
	}
})();
</script>

<?php
/**
 * The seller brochure order editor modal + its jQuery is unchanged from your
 * original file — it depends on glow_get_order / glow_save_order / glow_move_to_cart.
 */
if ( function_exists( 'glow_so_get_fundraiser_orders' ) ) : ?>
<div class="glow-modal" id="glow-edit">
	<div class="glow-modal-box glow-edit-box">
		<h3>Edit Order</h3>
		<label class="glow-name-label">Seller Name</label>
		<input type="text" id="glow-e-name" class="glow-name-input">
		<div class="glow-e-items"></div>
		<div class="glow-e-add">
			<input type="text" id="glow-e-search" class="glow-name-input" placeholder="Search product by name or SKU to add...">
			<div class="glow-e-results"></div>
		</div>
		<div class="glow-e-foot"><span><b id="glow-e-count">0</b> items · <b>$<span id="glow-e-total">0.00</span></b></span></div>
		<p class="glow-e-msg"></p>
		<div class="glow-modal-btns">
			<button type="button" id="glow-e-save" class="glow-done-btn">SAVE CHANGES</button>
			<button type="button" id="glow-e-cart" class="glow-done-btn glow-cart-btn">SEND TO CART</button>
			<button type="button" class="glow-cancel glow-e-close">Close</button>
		</div>
	</div>
</div>

<script>
var GLOW_EDIT_NONCE = "<?php echo esc_attr( wp_create_nonce( 'glow_order_edit' ) ); ?>";
var GLOW_ADMIN_AJAX = "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>";
</script>

<script>
jQuery(function ($) {

	$(document).on('change', '.glow-money', function () {
		var $b = $(this), $m = $b.closest('label').find('.glow-money-msg');
		$b.prop('disabled', true); $m.text('...');
		$.post(GLOW_ADMIN_AJAX, {
			action: 'glow_money_received', order_id: $b.data('order'),
			checked: $b.is(':checked') ? 1 : 0, nonce: $b.data('nonce')
		}, function (res) {
			$b.prop('disabled', false);
			$m.text(res.success ? 'Saved' : 'Error');
			setTimeout(function () { $m.text(''); }, 1500);
		});
	});

	var EO = { id: 0, items: [], locked: 0 };

	function render() {
		var html = '', count = 0, total = 0;
		if (!EO.items.length) html = '<p class="glow-e-empty">No items. Add products below.</p>';
		$.each(EO.items, function (i, it) {
			count += it.qty; total += it.qty * it.price;
			html += '<div class="glow-e-row" data-i="' + i + '">' +
			          '<span class="glow-e-sku">' + (it.sku || '') + '</span>' +
			          '<span class="glow-e-name">' + it.name + '</span>' +
			          '<input type="number" class="glow-e-qty" min="1" value="' + it.qty + '">' +
			          '<span class="glow-e-line">$' + (it.qty * it.price).toFixed(2) + '</span>' +
			          '<a href="#" class="glow-e-del">&times;</a></div>';
		});
		$('.glow-e-items').html(html);
		$('#glow-e-count').text(count);
		$('#glow-e-total').text(total.toFixed(2));
	}

	$(document).on('click', '.glow-view', function (e) {
		e.preventDefault();
		var id = $(this).data('order');
		$('.glow-e-msg').text('');
		$('.glow-e-items').html('<p class="glow-e-empty">Loading...</p>');
		$('#glow-edit').addClass('show');

		$.post(GLOW_ADMIN_AJAX, { action: 'glow_get_order', order_id: id, nonce: GLOW_EDIT_NONCE }, function (res) {
			if (!res.success) { $('.glow-e-msg').css('color', '#d33').text(res.data.msg); return; }
			EO.id = res.data.order_id; EO.items = res.data.items; EO.locked = res.data.locked;
			$('#glow-e-name').val(res.data.seller_name).prop('disabled', !!EO.locked);
			$('#glow-e-save, #glow-e-cart').prop('disabled', !!EO.locked);
			$('.glow-e-add').toggle(!EO.locked);
			render();
		});
	});

	$(document).on('click', '.glow-e-close', function () { $('#glow-edit').removeClass('show'); });

	$(document).on('input', '.glow-e-qty', function () {
		var i = $(this).closest('.glow-e-row').data('i');
		var q = parseInt(this.value, 10) || 1;
		if (q < 1) q = 1;
		EO.items[i].qty = q; render();
	});

	$(document).on('click', '.glow-e-del', function (e) {
		e.preventDefault();
		EO.items.splice($(this).closest('.glow-e-row').data('i'), 1);
		render();
	});

	var timer;
	$(document).on('input', '#glow-e-search', function () {
		clearTimeout(timer);
		var term = $.trim(this.value);
		if (term.length < 2) { $('.glow-e-results').empty(); return; }
		timer = setTimeout(function () {
			$.post(GLOW_ADMIN_AJAX, { action: 'glow_search_products', term: term, nonce: GLOW_EDIT_NONCE }, function (res) {
				var h = '';
				$.each(res.data || [], function (i, p) {
					h += '<a href="#" class="glow-e-pick" data-p=\'' + JSON.stringify(p) + '\'>' +
					     '<b>' + (p.sku || '') + '</b> ' + p.name + ' <em>$' + p.price.toFixed(2) + '</em></a>';
				});
				$('.glow-e-results').html(h || '<span class="glow-e-empty">No match</span>');
			});
		}, 350);
	});

	$(document).on('click', '.glow-e-pick', function (e) {
		e.preventDefault();
		var p = $(this).data('p'), found = false;
		$.each(EO.items, function (i, it) { if (it.pid == p.pid) { it.qty++; found = true; return false; } });
		if (!found) EO.items.push({ pid: p.pid, sku: p.sku, name: p.name, qty: 1, price: p.price });
		$('#glow-e-search').val(''); $('.glow-e-results').empty(); render();
	});

	function payload() {
		var items = {};
		$.each(EO.items, function (i, it) { items[it.pid] = it.qty; });
		return items;
	}

	$(document).on('click', '#glow-e-save', function () {
		var $b = $(this).prop('disabled', true).text('SAVING...');
		$.post(GLOW_ADMIN_AJAX, {
			action: 'glow_save_order', order_id: EO.id, nonce: GLOW_EDIT_NONCE,
			seller_name: $('#glow-e-name').val(), items: payload()
		}, function (res) {
			$b.prop('disabled', false).text('SAVE CHANGES');
			if (res.success) {
				$('.glow-e-msg').css('color', '#17c671').text('Saved');
				setTimeout(function () { location.reload(); }, 900);
			} else { $('.glow-e-msg').css('color', '#d33').text(res.data.msg); }
		});
	});

	$(document).on('click', '#glow-e-cart', function () {
		if (!confirm('Send this order to your cart?')) return;
		var $b = $(this).prop('disabled', true).text('SENDING...');
		$.post(GLOW_ADMIN_AJAX, {
			action: 'glow_save_order', order_id: EO.id, nonce: GLOW_EDIT_NONCE,
			seller_name: $('#glow-e-name').val(), items: payload()
		}, function () {
			$.post(GLOW_ADMIN_AJAX, { action: 'glow_move_to_cart', order_id: EO.id, nonce: GLOW_EDIT_NONCE }, function (res) {
				$b.prop('disabled', false).text('SEND TO CART');
				if (res.success) { window.location.href = '/cart/'; }
				else { $('.glow-e-msg').css('color', '#d33').text(res.data.msg); }
			});
		});
	});
});
</script>
<?php endif; ?>

<style>
/* ============================================================
   GLOW Fundraiser Dashboard — scoped under .glow-fd
   ============================================================ */
.glow-fd{
	--pink:#ec0f63; --pink-hi:#ff2d7e; --orange:#f5741a; --amber:#f9b81c;
	--purple:#6236e0; --purple-hi:#7c4bf0; --teal:#0fa47a; --teal-hi:#17b98a;
	--ink:#16152b; --muted:#6f6c85; --board:#f3f2f5; --card:#fff;
	--card-soft:#f8f7f9; --line:#e9e8ee; --line-soft:#efeef2;
	font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;
	color:var(--ink); background:#0a1130; max-width:1780px; margin:0 auto; overflow:hidden;
	-webkit-font-smoothing:antialiased;
}
.glow-fd *{ box-sizing:border-box; }
.glow-fd button{ font-family:inherit; cursor:pointer; border:0; background:none; color:inherit; }
.glow-fd a{ text-decoration:none; }
.glow-fd :focus-visible{ outline:3px solid #7c4bf0; outline-offset:2px; border-radius:6px; }
.glow-fd-gate{ max-width:520px; margin:80px auto; text-align:center; padding:40px 24px; }
.glow-fd-gate-btn{ display:inline-block; margin-top:14px; padding:12px 30px; border-radius:30px; background:#ec0f63; color:#fff !important; font-weight:600; }

/* header */
.glow-fd-top{ position:relative; display:flex; align-items:flex-start; gap:34px; padding:26px 34px 30px; min-height:152px;
	background:radial-gradient(900px 300px at 78% 0%, rgba(70,60,160,.55), transparent 62%), linear-gradient(105deg,#0a1130 0%,#101a3f 48%,#16224b 100%); overflow:hidden; }
.glow-fd-brand{ flex:0 0 auto; padding-top:4px; }
.glow-fd-brand-word{ display:flex; align-items:flex-start; gap:10px; }
.glow-fd-brand-word h2{ margin:0; font-family:'Playfair Display',Georgia,serif; font-weight:500; font-size:44px; line-height:1; letter-spacing:.10em; color:#fff; }
.glow-fd-brand-sub{ margin:11px 0 0; display:flex; align-items:center; gap:9px; font-size:12.5px; font-weight:500; letter-spacing:.22em; color:#e7e4f2; }
.glow-fd-brand-sub i{ width:4px; height:4px; border-radius:50%; background:var(--pink-hi); }
.glow-fd-top-rule{ flex:0 0 1px; align-self:stretch; margin:6px 0 10px; background:linear-gradient(180deg,transparent,rgba(255,255,255,.22),transparent); }
.glow-fd-campaign{ flex:1 1 auto; min-width:0; padding-top:6px; }
.glow-fd-campaign h1{ margin:0 0 16px; font-size:31px; line-height:1.15; font-weight:700; letter-spacing:-.01em; color:#fff; }
.glow-fd-meta{ display:flex; align-items:center; gap:20px; flex-wrap:wrap; }
.glow-fd-pill{ display:inline-flex; align-items:center; padding:9px 22px; border-radius:999px; font-size:14.5px; font-weight:600; }
.glow-fd-pill.is-on{ border:1.5px solid rgba(23,185,138,.85); color:#2fdba4; background:rgba(15,164,122,.10); }
.glow-fd-pill.is-off{ border:1.5px solid rgba(255,90,90,.7); color:#ff8080; background:rgba(255,60,60,.10); }
.glow-fd-pill.is-warn{ border:1.5px solid rgba(249,184,28,.8); color:#ffcf5c; background:rgba(249,184,28,.10); }
.glow-fd-dates{ display:inline-flex; align-items:center; gap:11px; color:#eceaf5; font-size:15.5px; font-weight:500; }
.glow-fd-meta-rule{ width:1px; height:44px; background:linear-gradient(180deg,transparent,rgba(255,255,255,.26),transparent); }
.glow-fd-code{ display:inline-flex; align-items:center; gap:10px; font-size:13.5px; color:#c9c5de; letter-spacing:.02em; }
.glow-fd-code b{ font-size:19px; color:#fff; font-weight:700; letter-spacing:.02em; }
.glow-fd-code-copy{ padding:6px 14px; border-radius:8px; background:rgba(255,255,255,.14); color:#fff; font-size:13px; font-weight:600; }
.glow-fd-code-copy:hover{ background:rgba(255,255,255,.24); }
.glow-fd-code-copy.is-done{ background:var(--teal); }
.glow-fd-countdown{ margin:0; font-size:14.5px; line-height:1.5; color:#e4e1f0; max-width:230px; }
.glow-fd-countdown b{ color:var(--pink-hi); font-weight:700; }

.glow-fd-decor{ position:absolute; right:0; bottom:-2px; width:620px; height:100%; pointer-events:none; }
.glow-fd-candles{ position:absolute; right:96px; bottom:-6px; display:flex; align-items:flex-end; gap:12px; }
.glow-fd-candle{ position:relative; width:98px; }
.glow-fd-candle .glass{ position:relative; height:118px; border-radius:8px 8px 12px 12px; overflow:hidden; box-shadow:inset 0 0 0 1px rgba(255,255,255,.35),0 10px 24px rgba(0,0,0,.35); }
.glow-fd-candle .glass::after{ content:""; position:absolute; inset:0; background:linear-gradient(100deg,rgba(255,255,255,.42) 0 9%,transparent 22% 78%,rgba(255,255,255,.20) 92% 100%); }
.glow-fd-candle .rim{ position:absolute; top:0; left:0; right:0; height:11px; background:linear-gradient(180deg,rgba(255,255,255,.55),rgba(255,255,255,.06)); z-index:2; }
.glow-fd-candle .label{ position:absolute; left:11px; right:11px; bottom:16px; height:52px; border-radius:3px; z-index:3; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:2px; font-size:8px; letter-spacing:.16em; font-weight:700; color:rgba(255,255,255,.75); border:1px solid rgba(255,255,255,.18); background:rgba(255,255,255,.08); }
.glow-fd-candle .label small{ font-size:6px; font-weight:600; letter-spacing:.02em; opacity:.8; }
.glow-fd-candle .flame{ position:absolute; left:50%; top:-30px; width:15px; height:32px; transform:translateX(-50%); border-radius:50% 50% 46% 46%/62% 62% 38% 38%; background:radial-gradient(ellipse at 50% 78%,#fff9d8 0%,#ffd66b 34%,#ff9d2f 62%,rgba(255,120,20,.15) 88%,transparent 100%); box-shadow:0 0 26px 8px rgba(255,170,60,.42); }
.glow-fd-candle .wick{ position:absolute; left:50%; top:0; width:2px; height:9px; transform:translateX(-50%); background:#2a2118; border-radius:2px; z-index:4; }
.glow-fd-candle.is-red .glass{ background:linear-gradient(180deg,#a02631,#7d1a23 62%,#5f121a); }
.glow-fd-candle.is-snow .glass{ background:linear-gradient(180deg,#f6f2ea,#e6ded1 60%,#d6cec1); }
.glow-fd-candle.is-snow .label{ background:linear-gradient(180deg,#cfe6f5,#eef7fd); border-color:rgba(0,0,0,.10); color:#2b4f6b; letter-spacing:.05em; }
.glow-fd-candle.is-white .glass{ background:linear-gradient(180deg,#fbfbfb,#eceaea 60%,#dcd9d9); }
.glow-fd-candle.is-white .label{ color:rgba(60,55,55,.6); border-color:rgba(0,0,0,.08); background:rgba(255,255,255,.5); }
.glow-fd-confetti{ position:absolute; width:16px; height:3.5px; border-radius:3px; }

/* board */
.glow-fd-board{ background:var(--board); border-radius:28px 28px 0 0; margin:0 12px; padding:22px 22px 30px; display:grid; gap:22px; }
.glow-fd-row{ display:grid; gap:22px; }
.glow-fd-row1{ grid-template-columns:.92fr 1.62fr 2.30fr; }
.glow-fd-row2{ grid-template-columns:1fr 1fr 1fr 2.22fr; }
.glow-fd-row3{ grid-template-columns:1.28fr 1fr 1.52fr; }
.glow-fd-card{ background:var(--card); border:1px solid var(--line); border-radius:26px; box-shadow:0 1px 2px rgba(20,18,45,.04); padding:22px; }
.glow-fd-head{ display:flex; align-items:center; gap:12px; }
.glow-fd-head h3{ margin:0; font-size:19px; font-weight:700; letter-spacing:-.01em; }
.glow-fd-refresh{ margin-left:auto; font-size:13.5px; font-weight:600; color:#4a3fd0 !important; text-decoration:underline; }
.glow-fd-count-pill{ margin-left:auto; padding:5px 13px; border-radius:999px; background:#f1eff6; font-size:13.5px; font-weight:600; color:#5d5a75; }
.glow-fd-hint{ margin:12px 0 4px; font-size:14.5px; color:#6b6982; }
.glow-fd-empty{ margin:20px 0; font-size:15px; color:#8d8aa0; text-align:center; }
.glow-fd-green{ color:var(--teal); }

/* notices */
.glow-fd-notice{ display:flex; align-items:center; gap:12px; flex-wrap:wrap; padding:15px 20px; border-radius:16px; font-size:15.5px; font-weight:500; }
.glow-fd-notice a{ font-weight:700; text-decoration:underline; }
.glow-fd-notice.is-admin{ background:#eef0fd; color:#3b3a72; }
.glow-fd-notice.is-done{ background:#eaf8f1; color:#0d6b50; }
.glow-fd-notice.is-todo{ background:#fff4e7; color:#8a4a08; }
.glow-fd-notice.is-cart{ background:#f3f1ff; color:#3f2fa8; }
.glow-fd-spin{ display:none; width:16px; height:16px; }

/* profile */
.glow-fd-profile{ background:linear-gradient(150deg,#ffd9de,#ffcdd6 44%,#ffc0cf); border-color:#f9c3ce; display:flex; flex-direction:column; justify-content:space-between; padding:26px 24px 22px; }
.glow-fd-profile-top{ display:flex; align-items:center; gap:20px; }
.glow-fd-avatar{ width:106px; height:106px; flex:0 0 106px; border-radius:50%; padding:4px; background:linear-gradient(140deg,var(--pink-hi),#ff7aa8); }
.glow-fd-avatar img,.glow-fd-avatar svg{ width:100%; height:100%; border-radius:50%; display:block; object-fit:cover; background:#ffe3ea; }
.glow-fd-hi{ display:flex; align-items:center; gap:8px; margin:0 0 8px; font-size:26px; font-weight:700; letter-spacing:-.02em; color:#1a1330; }
.glow-fd-role{ margin:0; font-size:16px; line-height:1.45; color:#5b4a5c; font-weight:500; }
.glow-fd-profile-actions{ margin-top:22px; }
.glow-fd-profile-btn{ width:100%; display:flex; align-items:center; gap:12px; padding:14px 16px; border-radius:14px; background:rgba(255,255,255,.62); border:1px solid rgba(255,255,255,.9); font-size:16px; font-weight:600; color:#2c2340 !important; }
.glow-fd-profile-btn:hover{ background:rgba(255,255,255,.9); }
.glow-fd-profile-btn .chev{ margin-left:auto; opacity:.55; }
.glow-fd-profile-link{ display:block; margin-top:10px; text-align:center; font-size:14.5px; font-weight:600; color:#7a3556 !important; text-decoration:underline; }

/* goal */
.glow-fd-goal{ background:var(--card-soft); display:flex; align-items:center; gap:26px; padding:20px 26px; }
.glow-fd-donut{ position:relative; flex:0 0 208px; width:208px; height:208px; border-radius:50%; display:grid; place-items:center;
	background:conic-gradient(from 200deg,#ff2d7e 0deg,#ff7a2f 52deg,#ffc328 104deg,#46c96b 152deg,#16b3c9 206deg,#3f7bea 254deg,#7a4bf0 300deg,#ff2d7e 360deg); }
.glow-fd-donut::after{ content:""; position:absolute; inset:15px; border-radius:50%; background:var(--card-soft); }
.glow-fd-donut-in{ position:relative; z-index:2; text-align:center; line-height:1.15; }
.glow-fd-donut-in .amt{ display:block; font-size:36px; font-weight:800; letter-spacing:-.03em; }
.glow-fd-donut-in .of{ display:block; margin-top:2px; font-size:18px; font-weight:600; color:#4d4b63; }
.glow-fd-goal-body{ flex:1 1 auto; min-width:0; }
.glow-fd-goal-eyebrow{ display:flex; align-items:center; gap:10px; font-size:14.5px; font-weight:700; color:#4b4960; }
.glow-fd-goal-target{ margin:6px 0 16px; font-size:44px; font-weight:800; letter-spacing:-.035em; }
.glow-fd-goal-bar{ display:flex; align-items:center; gap:14px; }
.glow-fd-bar{ position:relative; flex:1 1 auto; height:10px; border-radius:999px; background:#e5e3ea; overflow:hidden; }
.glow-fd-bar span{ position:absolute; inset:0 auto 0 0; border-radius:999px; background:linear-gradient(90deg,#f0176e,#ff2d7e); }
.glow-fd-pct{ font-size:16px; font-weight:700; color:#3a3850; }
.glow-fd-cheer{ margin:18px 0 0; font-size:19px; font-weight:700; color:var(--pink); }
.glow-fd-cheer-sub{ margin:8px 0 0; font-size:16px; color:#5b5972; }

/* share */
.glow-fd-share{ display:flex; gap:22px; }
.glow-fd-share-main{ flex:1 1 auto; min-width:0; }
.glow-fd-share-sub{ margin:9px 0 14px; font-size:15.5px; color:#5b5972; }
.glow-fd-linklabel{ display:block; margin:0 0 6px; font-size:13px; font-weight:600; color:#6f6c85; }
.glow-fd-linkbox{ display:flex; align-items:center; gap:8px; padding:6px 6px 6px 16px; border:1px solid #e2e0ea; border-radius:14px; background:#fff; margin-bottom:12px; }
.glow-fd-linkbox input{ flex:1 1 auto; min-width:0; border:0; outline:0; background:transparent; font-family:inherit; font-size:15.5px; font-weight:500; color:#4a3fd0; padding:6px 0; }
.glow-fd-copy{ flex:0 0 auto; padding:10px 20px; border-radius:10px; background:linear-gradient(180deg,var(--purple-hi),var(--purple)); color:#fff; font-size:14.5px; font-weight:600; }
.glow-fd-copy.is-done{ background:linear-gradient(180deg,var(--teal-hi),var(--teal)); }
.glow-fd-share-grid{ display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-top:14px; }
.glow-fd-sbtn{ display:flex; align-items:center; justify-content:center; gap:10px; padding:14px 12px; border-radius:13px; color:#fff !important; font-size:15.5px; font-weight:600; box-shadow:0 6px 18px rgba(20,18,45,.10); }
.glow-fd-sbtn:hover{ filter:brightness(1.06); }
.glow-fd-sbtn.is-text{ background:linear-gradient(180deg,#f5176f,#e00c5e); }
.glow-fd-sbtn.is-fb{ background:linear-gradient(180deg,#6a48ea,#5330d8); }
.glow-fd-sbtn.is-wa{ background:linear-gradient(180deg,#14b58a,#0d9470); }
.glow-fd-sbtn.is-letter{ background:linear-gradient(180deg,#f97d22,#ee6410); }
.glow-fd-qr-wrap{ flex:0 0 auto; width:190px; display:flex; flex-direction:column; gap:14px; align-items:center; justify-content:center; }
.glow-fd-qr-item{ text-align:center; }
.glow-fd-qr{ width:118px; height:118px; display:block; margin:0 auto 6px; }
.glow-fd-qr-item span{ display:block; font-size:13px; font-weight:600; color:#4b4960; }
.glow-fd-qr-item a{ font-size:12.5px; color:#4a3fd0 !important; text-decoration:underline; }
.glow-fd-qr-empty{ font-size:13.5px; color:#8d8aa0; text-align:center; }

/* tiles */
.glow-fd-tile{ position:relative; display:flex; align-items:center; gap:18px; padding:26px 22px; border-radius:26px; color:#fff !important; min-height:158px; width:100%; text-align:left; box-shadow:0 6px 18px rgba(20,18,45,.10); }
.glow-fd-tile:hover{ filter:brightness(1.05); }
.glow-fd-tile.is-store{ background:linear-gradient(135deg,#f5176f,#e0095c); }
.glow-fd-tile.is-brochure{ background:linear-gradient(135deg,#fb8b32,#f26a12); }
.glow-fd-tile.is-reports{ background:linear-gradient(135deg,#fdcb3c,#f5ad0c); color:#4a3300 !important; }
.glow-fd-tile-icon{ flex:0 0 auto; opacity:.95; }
.glow-fd-tile-txt{ flex:1 1 auto; min-width:0; }
.glow-fd-tile-txt strong{ display:block; margin-bottom:7px; font-size:20px; font-weight:700; letter-spacing:-.015em; }
.glow-fd-tile-txt span{ display:block; font-size:15px; line-height:1.42; opacity:.94; }
.glow-fd-tile-chev{ flex:0 0 auto; opacity:.8; }
.glow-fd-badge{ display:inline-block !important; width:auto !important; margin-top:11px; padding:5px 13px; border-radius:999px; background:#fff; color:#ef6a11; font-size:13.5px !important; font-weight:700; opacity:1 !important; }

/* drawer */
.glow-fd-drawer{ padding:22px 26px 26px; }
.glow-fd-brochure-links{ display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin:16px 0 12px; }
.glow-fd-brochure-links a{ display:block; padding:18px; border:1px solid var(--line); border-radius:16px; font-size:16px; font-weight:600; color:var(--ink) !important; }
.glow-fd-brochure-links a:hover{ border-color:#f9cfe0; background:#fff6fa; }
.glow-fd-brochure-links span{ display:block; margin-top:6px; font-size:14px; font-weight:600; color:var(--pink); }
.glow-fd-brochure-total{ margin:0; font-size:15px; color:#5b5972; }

/* checklist */
.glow-fd-checklist{ display:flex; align-items:center; gap:26px; }
.glow-fd-checklist-intro{ flex:0 0 auto; max-width:270px; }
.glow-fd-checklist-intro h3{ margin:0 0 6px; font-size:19px; font-weight:700; }
.glow-fd-checklist-intro p{ margin:0; font-size:14.5px; color:#5f5d75; }
.glow-fd-steps{ flex:1 1 auto; display:grid; grid-template-columns:repeat(4,1fr); gap:12px; }
.glow-fd-step{ display:flex; flex-direction:column; align-items:center; gap:10px; text-align:center; color:inherit !important; }
.glow-fd-step-num{ width:52px; height:52px; border-radius:50%; display:grid; place-items:center; font-size:22px; font-weight:700; border:2.5px solid currentColor; transition:background .2s ease; }
.glow-fd-step:nth-child(1) .glow-fd-step-num{ color:var(--pink); }
.glow-fd-step:nth-child(2) .glow-fd-step-num{ color:var(--orange); }
.glow-fd-step:nth-child(3) .glow-fd-step-num{ color:var(--amber); }
.glow-fd-step:nth-child(4) .glow-fd-step-num{ color:var(--teal); }
.glow-fd-step-label{ font-size:14.5px; line-height:1.32; color:#3d3b52; font-weight:500; max-width:100px; }
.glow-fd-step-check{ width:20px; height:20px; border-radius:50%; display:grid; place-items:center; background:var(--teal-hi); color:#fff; }
.glow-fd-step[aria-pressed="false"] .glow-fd-step-check{ background:#d7d5de; opacity:.55; }
.glow-fd-step[aria-pressed="true"] .glow-fd-step-num{ background:currentColor; }
.glow-fd-step[aria-pressed="true"] .glow-fd-step-num span{ color:#fff; }

/* sales / orders / sellers */
.glow-fd-sales-body{ display:flex; align-items:flex-end; gap:18px; margin:18px 0 6px; }
.glow-fd-figures{ flex:0 0 auto; padding-bottom:12px; }
.glow-fd-big{ font-size:46px; font-weight:800; letter-spacing:-.04em; line-height:1; }
.glow-fd-big-sub{ margin:6px 0 16px; font-size:16px; color:#4f4d66; font-weight:500; }
.glow-fd-trend{ display:flex; align-items:center; gap:6px; font-size:17px; font-weight:700; color:var(--teal); }
.glow-fd-trend.is-down{ color:#e0424b; }
.glow-fd-trend-sub{ margin:5px 0 0; font-size:14.5px; color:#6f6d84; }
.glow-fd-chart{ flex:1 1 auto; min-width:0; }
.glow-fd-chart svg{ width:100%; height:132px; display:block; }
.glow-fd-breakdown{ display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-top:14px; }
.glow-fd-breakdown div{ padding:12px 14px; border-radius:14px; background:#f7f6f9; }
.glow-fd-breakdown span{ display:block; font-size:13px; color:#6f6c85; font-weight:600; }
.glow-fd-breakdown b{ display:block; margin-top:4px; font-size:16px; font-weight:700; }
.glow-fd-breakdown em{ display:block; margin-top:2px; font-size:14.5px; font-style:normal; font-weight:700; color:var(--teal); }
.glow-fd-orders-body{ display:flex; align-items:center; gap:22px; margin:18px 0 6px; }
.glow-fd-orders-count p{ margin:6px 0 0; font-size:17px; color:#4f4d66; font-weight:500; }
.glow-fd-statuses{ flex:1 1 auto; display:grid; gap:8px; }
.glow-fd-status{ display:flex; align-items:center; gap:12px; padding:9px 14px; border-radius:10px; font-size:16px; font-weight:600; color:#33314a; }
.glow-fd-status .dot{ width:24px; height:24px; border-radius:50%; display:grid; place-items:center; color:#fff; font-size:12.5px; font-weight:700; }
.glow-fd-status.is-processing{ background:#f0edfd; } .glow-fd-status.is-processing .dot{ background:var(--purple-hi); }
.glow-fd-status.is-shipped{ background:#eaf8f1; }    .glow-fd-status.is-shipped .dot{ background:var(--teal-hi); }
.glow-fd-status.is-delivered{ background:#eaf8f1; }  .glow-fd-status.is-delivered .dot{ background:var(--teal); }
.glow-fd-ghost{ display:block; width:100%; margin-top:12px; padding:15px; border-radius:14px; border:1px solid var(--line); background:#fff; font-size:16px; font-weight:600; text-align:center; }
.glow-fd-ghost.is-purple{ color:#4a3fd0 !important; }
.glow-fd-ghost.is-purple:hover{ background:#f3f1ff; border-color:#d9d3fb; }

.glow-fd-sellers{ margin-top:14px; }
.glow-fd-sellers-head,.glow-fd-seller-row{ display:grid; grid-template-columns:1fr 90px 90px 50px; gap:10px; align-items:center; }
.glow-fd-sellers-head{ padding:0 6px 8px; font-size:12.5px; font-weight:700; letter-spacing:.03em; color:#8d8aa0; text-transform:uppercase; }
.glow-fd-sellers-head span:not(:first-child),.glow-fd-seller-row span:not(.glow-fd-seller-name){ text-align:right; font-size:14.5px; font-weight:600; }
.glow-fd-sellers-scroll{ max-height:236px; overflow-y:auto; }
.glow-fd-seller-row{ padding:9px 6px; border-top:1px solid var(--line-soft); font-size:15px; }
.glow-fd-seller-name{ display:flex; align-items:center; gap:10px; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-weight:600; }
.glow-fd-seller-name em{ font-style:normal; font-size:11.5px; font-weight:700; color:#8d8aa0; background:#f1eff6; padding:2px 7px; border-radius:999px; }
.glow-fd-rank{ flex:0 0 22px; width:22px; height:22px; border-radius:50%; display:grid; place-items:center; font-style:normal; font-size:12px; font-weight:700; background:#f1eff6; color:#6f6c85; }
.glow-fd-rank.is-top{ background:linear-gradient(140deg,#ff4d8d,#ec0f63); color:#fff; }

/* permissions */
.glow-fd-toggle{ margin-left:auto; min-width:104px; padding:7px 18px; border-radius:999px; text-align:center; font-size:12.5px; font-weight:700; letter-spacing:.4px; color:#fff !important; }
.glow-fd-toggle.is-on{ background:var(--teal); }
.glow-fd-toggle.is-off{ background:#9b9b9b; }
.glow-fd-toggle:hover{ opacity:.88; }
.glow-fd-perms{ margin-top:8px; max-height:320px; overflow-y:auto; }
.glow-fd-perm-row{ display:flex; align-items:center; gap:14px; padding:11px 4px; border-top:1px solid var(--line-soft); font-size:15.5px; }
.glow-fd-perm-row .glow-fd-toggle{ min-width:96px; }

/* tables */
.glow-fd-table-wrap{ margin-top:14px; overflow-x:auto; }
.glow-fd-table{ width:100%; border-collapse:collapse; font-size:14.5px; }
.glow-fd-table th{ padding:10px 12px; text-align:left; font-size:12px; font-weight:700; letter-spacing:.04em; text-transform:uppercase; color:#8d8aa0; border-bottom:1px solid var(--line); white-space:nowrap; }
.glow-fd-table td{ padding:12px; border-bottom:1px solid var(--line-soft); vertical-align:middle; }
.glow-fd-table tr:hover td{ background:#faf9fc; }
.glow-fd-table a{ color:#4a3fd0 !important; text-decoration:underline; }
.glow-fd-tag{ display:inline-block; padding:4px 11px; border-radius:999px; font-size:12.5px; font-weight:700; background:#f1eff6; color:#5d5a75; white-space:nowrap; }
.glow-fd-tag.is-completed{ background:#eaf8f1; color:#0d6b50; }
.glow-fd-tag.is-processing{ background:#f0edfd; color:#4030a8; }
.glow-fd-tag.is-waiting-call{ background:#fff4e7; color:#8a4a08; }
.glow-fd-mini{ display:inline-block; padding:5px 14px; border-radius:8px; background:var(--teal); color:#fff !important; font-size:13px; font-weight:600; text-decoration:none !important; }
.glow-fd-check{ display:inline-flex; align-items:center; gap:8px; margin:0; }
.glow-money-msg{ font-size:12px; color:var(--teal); }
.glow-moved{ font-style:normal; font-size:13px; color:#4a3fd0; }
.glow-view{ display:block; margin-top:2px; font-size:12px; }

/* close out */
.glow-fd-closeout{ display:flex; align-items:center; gap:24px; flex-wrap:wrap; }
.glow-fd-closeout h3{ margin:0 0 6px; font-size:19px; font-weight:700; }
.glow-fd-closeout p{ margin:0; font-size:15px; color:#6b6982; max-width:70ch; }
.glow-fd-closeout button{ margin-left:auto; padding:14px 32px; border-radius:14px; border:1.5px solid #e0424b; color:#e0424b; font-size:15.5px; font-weight:700; }
.glow-fd-closeout button:hover{ background:#e0424b; color:#fff; }

/* modal + toast */
.glow-fd-modal{ display:none; position:fixed; inset:0; background:rgba(10,12,30,.6); z-index:99998; }
.glow-fd-modal.is-open{ display:block; }
.glow-fd-modal-box{ position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); width:100%; max-width:520px; background:#fff; border-radius:22px; padding:32px 30px; text-align:center; font-family:'Inter',sans-serif; }
.glow-fd-modal-box h3{ margin:0 0 10px; font-size:22px; font-weight:700; }
.glow-fd-modal-box p{ margin:0; font-size:15.5px; color:#6b6982; line-height:1.5; }
.glow-fd-modal-btns{ display:flex; gap:12px; justify-content:center; margin-top:22px; }
.glow-fd-modal-btns button{ padding:13px 26px; border-radius:12px; font-size:15px; font-weight:700; }
#close_out_ajax{ background:#e0424b; color:#fff; }
#close_out_ajax:disabled{ background:#d5d5d5; }
.glow-fd-modal-cancel{ background:#f1eff6; color:#3d3b52; }
.glow-fd-modal-msg{ margin-top:14px !important; font-size:14px !important; color:#4a3fd0 !important; }
.glow-fd-toast{ position:fixed; left:50%; bottom:34px; transform:translate(-50%,20px); background:#16152b; color:#fff; padding:13px 22px; border-radius:12px; font-size:15px; font-weight:600; box-shadow:0 12px 30px rgba(0,0,0,.24); opacity:0; pointer-events:none; transition:opacity .2s ease,transform .2s ease; z-index:99999; }
.glow-fd-toast.is-open{ opacity:1; transform:translate(-50%,0); }


/* =========================================================
   SELLER BROCHURE ORDERS
   ========================================================= */

   .glow-sbo{
	background:#fff;
	border:1px solid #e9e8ee;
	border-radius: 26px;
    box-shadow: 0 1px 2px rgba(20, 18, 45, .04);
    padding: 22px;
	overflow:hidden;
	font-family:'Proxima Nova',Georgia,sans-serif;
}

.glow-sbo-head{
	padding:18px 0px;
	background:#fff;
	border-bottom:1px solid #e9e8ee;
    justify-content: space-between;
	display:flex;
	align-items:center;
}

.glow-sbo-head h4{
	margin:0;
	font-size:18px;
	font-weight:700;
	color:#4a4a4a;
	text-transform:none;
}

.glow-sbo-body{
	background:#fff;
}

.glow-row{
	display:flex;
	align-items:center;
	gap:12px;
	padding:12px 0px;
	border-bottom:1px solid #f1f1f1;

	font-size:14px;
	color:#4a4a4a;
}

.glow-row:last-child{
	border-bottom:0;
}

.glow-row-head{
	background:#fff;
	font-size:11px;
	font-weight:700;
	text-transform:uppercase;
	letter-spacing:.4px;
	color:#8c8c8c;
}

.glow-name{
	flex:1 1 0;
	min-width:0;
}

.glow-action{
	flex:0 0 170px;
	text-align:right;
}

.glow-btn{
	display:inline-block;

	min-width:104px;

	padding:8px 18px;

	border-radius:999px;

	text-align:center;

	font-size:12px;

	font-weight:700;

	letter-spacing:.4px;

	text-decoration:none !important;

	color:#fff !important;

	transition:opacity .2s ease;
}

.glow-btn:hover{
	opacity:.85;
	color:#fff !important;
}

.glow-btn.is-on{
	background:#17c671;
}

.glow-btn.is-off{
	background:#9b9b9b;
}

.glow-empty{
	color:#999;
	justify-content:flex-start;
}


/* Mobile */

@media(max-width:640px){

	.glow-sbo-head{
		flex-wrap:wrap;
		gap:10px;
	}

	.glow-sbo-head .glow-btn{
		margin-left:auto;
	}

	.glow-row{
		flex-wrap:wrap;
		gap:8px;
	}

	.glow-row-head{
		display:none;
	}

	.glow-name{
		flex:1 1 100%;
	}

	.glow-action{
		flex:1 1 100%;
		text-align:left;
	}

}
/* ============================================================
   Seller brochure order editor (#glow-edit)
   Carried over from the original template — these classes are NOT
   under .glow-fd, because the modal renders outside that wrapper.
   ============================================================ */
.glow-modal{ display:none; position:fixed; inset:0; background:rgba(10,12,30,.6); z-index:99999; }
.glow-modal.show{ display:block; }
.glow-modal-box{ position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); background:#fff; padding:30px 26px; width:calc(100% - 32px); max-width:640px; border-radius:16px; text-align:left; max-height:88vh; overflow-y:auto; box-shadow:0 24px 60px rgba(0,0,0,.3); font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif; }
.glow-modal-box h3{ margin:0 0 18px; font-size:20px; font-weight:700; color:#16152b; text-align:center; }
.glow-modal-btns{ display:flex; flex-direction:column; gap:10px; align-items:center; margin-top:6px; }
.glow-done-btn{ background:#0fa47a; color:#fff; border:0; border-radius:30px; padding:12px 30px; font-size:13px; font-weight:700; letter-spacing:.5px; cursor:pointer; min-width:190px; }
.glow-done-btn:hover{ filter:brightness(1.06); }
.glow-done-btn:disabled{ background:#d5d5d5; cursor:not-allowed; filter:none; }
.glow-cart-btn{ background:#6236e0; }
.glow-cancel{ background:none; border:0; color:#8d8aa0 !important; cursor:pointer; font-size:13px; text-decoration:underline; padding:4px; }
.glow-name-label{ display:block; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.4px; color:#8d8aa0; margin-bottom:6px; }
.glow-name-input{ width:100%; height:42px; border:1px solid #e2e0ea; border-radius:8px; padding:0 12px; font-size:15px; margin:0; box-sizing:border-box; font-family:inherit; }
.glow-name-input:focus{ border-color:#0fa47a; outline:0; }
.glow-e-items{ margin:14px 0; border:1px solid #eceaf1; border-radius:8px; max-height:280px; overflow-y:auto; }
.glow-e-row{ display:flex; align-items:center; gap:10px; padding:9px 10px; border-bottom:1px solid #f3f2f5; font-size:13px; }
.glow-e-row:last-child{ border-bottom:0; }
.glow-e-sku{ flex:0 0 80px; font-weight:700; color:#8d8aa0; font-size:11px; }
.glow-e-row .glow-e-name{ flex:1 1 0; min-width:0; color:#3d3b52; line-height:1.35; }
.glow-e-qty{ flex:0 0 62px; height:32px; text-align:center; border:1px solid #e2e0ea; border-radius:6px; margin:0; padding:0; font-size:14px; box-sizing:border-box; }
.glow-e-line{ flex:0 0 70px; text-align:right; font-weight:700; }
.glow-e-del{ flex:0 0 22px; text-align:center; color:#e0424b !important; font-size:20px; line-height:1; text-decoration:none !important; }
.glow-e-empty{ padding:16px; text-align:center; color:#a9a6b8; font-size:13px; margin:0; }
.glow-e-add{ margin-bottom:12px; }
.glow-e-results{ max-height:180px; overflow-y:auto; border:1px solid #eceaf1; border-radius:8px; margin-top:4px; }
.glow-e-results:empty{ display:none; }
.glow-e-pick{ display:block; padding:9px 10px; font-size:13px; color:#3d3b52 !important; text-decoration:none !important; border-bottom:1px solid #f3f2f5; }
.glow-e-pick:hover{ background:#f5faf7; }
.glow-e-pick b{ color:#8d8aa0; font-size:11px; }
.glow-e-pick em{ color:#0fa47a; font-style:normal; font-weight:700; }
.glow-e-foot{ text-align:right; font-size:16px; color:#3d3b52; padding:10px 0; border-top:2px solid #eceaf1; }
.glow-e-msg{ min-height:20px; margin:6px 0; font-size:13px; text-align:center; }

@media(max-width:600px){
	.glow-modal-box{ padding:24px 18px; }
	.glow-e-row{ flex-wrap:wrap; gap:6px 8px; }
	.glow-e-sku{ flex:0 0 auto; }
	.glow-e-row .glow-e-name{ flex:1 1 100%; order:-1; font-weight:600; }
	.glow-done-btn{ width:100%; }
}

/* responsive */
@media(max-width:1500px){
	.glow-fd-row1{ grid-template-columns:1fr 1.6fr; }
	.glow-fd-row1 .glow-fd-share{ grid-column:1/-1; }
	.glow-fd-row2{ grid-template-columns:1fr 1fr 1fr; }
	.glow-fd-row2 .glow-fd-checklist{ grid-column:1/-1; }
	.glow-fd-row3{ grid-template-columns:1fr 1fr; }
	.glow-fd-row3 .glow-fd-card:last-child{ grid-column:1/-1; }
}
@media(max-width:1080px){
	.glow-fd-top{ flex-wrap:wrap; padding-right:28px; }
	.glow-fd-decor{ display:none; }
	.glow-fd-row1,.glow-fd-row2,.glow-fd-row3{ grid-template-columns:1fr 1fr; }
	.glow-fd-row1 .glow-fd-share,.glow-fd-row2 .glow-fd-checklist,.glow-fd-row3 .glow-fd-card:last-child{ grid-column:1/-1; }
	.glow-fd-brochure-links{ grid-template-columns:1fr; }
}
@media(max-width:760px){
	.glow-fd-board{ margin:0; border-radius:22px 22px 0 0; padding:16px; gap:16px; }
	.glow-fd-row{ gap:16px; grid-template-columns:1fr !important; }
	.glow-fd-row1 .glow-fd-share,.glow-fd-row2 .glow-fd-checklist,.glow-fd-row3 .glow-fd-card:last-child{ grid-column:auto; }
	.glow-fd-top{ padding:22px 20px 26px; gap:18px; }
	.glow-fd-top-rule,.glow-fd-meta-rule{ display:none; }
	.glow-fd-brand-word h2{ font-size:34px; }
	.glow-fd-campaign h1{ font-size:24px; }
	.glow-fd-goal{ flex-direction:column; align-items:flex-start; gap:18px; }
	.glow-fd-donut{ margin:0 auto; }
	.glow-fd-share{ flex-direction:column; }
	.glow-fd-qr-wrap{ width:100%; flex-direction:row; justify-content:center; gap:26px; }
	.glow-fd-share-grid{ grid-template-columns:1fr; }
	.glow-fd-checklist{ flex-direction:column; align-items:flex-start; }
	.glow-fd-steps{ width:100%; }
	.glow-fd-sales-body,.glow-fd-orders-body{ flex-direction:column; align-items:flex-start; }
	.glow-fd-chart{ width:100%; }
	.glow-fd-breakdown{ grid-template-columns:1fr; }
	.glow-fd-closeout button{ margin-left:0; width:100%; }
}
@media(prefers-reduced-motion:reduce){ .glow-fd *{ transition-duration:.001ms !important; } }
</style>

<?php get_footer(); ?>