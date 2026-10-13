<?php
/**
 * Template Name: GLOW Fundraiser Dashboard
 *
 * SINGLE FILE — data layer + markup together.
 * PART 1 (data layer) is unchanged. PART 2 (render + CSS) is rebuilt to match
 * the approved dashboard mockup.
 *
 * Still needed elsewhere (leave them where they already are):
 *   - glow_master_allows() / glow_seller_allows()   (brochure toggles)
 *   - glow_so_get_fundraiser_orders() / glow_so_catalog_label()
 *   - the glow_get_order / glow_save_order / glow_move_to_cart AJAX handlers
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* =========================================================================
   PART 1 — DATA LAYER
   ========================================================================= */

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
			'instructions'   => 'https://glowscentedcandles.com/wp-content/uploads/2020/10/Fundraiser-Dashboard-Glow-Scented-Candles-4-copy.pdf',
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

if ( ! function_exists( 'glow_fd_checklist_steps' ) ) :
function glow_fd_checklist_steps() {
	return array(
		'store'   => 'Customize Your Store',
		'sellers' => 'Invite Your Sellers',
		'share'   => 'Share Your Links',
		'orders'  => 'Submit Brochure Orders',
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

/* -------------------------------------------------------------------------
   Checklist toggle — nonced link, saved on page load, then redirect.
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
   PART 2 — RENDER  (matches the approved mockup)
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

$shop_link   = glow_fd_shop_link( $fid );
$reg_link    = glow_fd_seller_register_link( $fid );
$qr_shop     = get_user_meta( $fid, glow_fd_meta( 'qr_shop' ), true );
$qr_reg      = get_user_meta( $fid, glow_fd_meta( 'qr_seller_reg' ), true );
$cart_saved  = get_user_meta( $fid, glow_fd_meta( 'cart_restore' ), true );
$merge_id    = get_user_meta( $fid, glow_fd_meta( 'merge_id' ), true );
$merge_qty   = get_user_meta( $fid, glow_fd_meta( 'merge_qty' ), true );

$first_name  = $campaign['chair'] ? current( explode( ' ', $campaign['chair'] ) ) : '';
$items_sold  = $stats['online_items'] + $stats['broch_items'] + $stats['sts_items'];
$avg_seller  = $stats['seller_count'] > 0 ? $stats['total_gross'] / $stats['seller_count'] : 0;

$top_seller = null;
foreach ( $stats['sellers'] as $sel_row ) {
	if ( $sel_row['gross'] > 0 ) { $top_seller = $sel_row; break; }
}

/* current step = first incomplete; each step has an action link + badge label */
$step_actions = array(
	'store'   => array( 'label' => 'START NOW',  'url' => glow_fd_url( 'customize' ) ),
	'sellers' => array( 'label' => 'INVITE NOW', 'url' => '#glow-fd-share' ),
	'share'   => array( 'label' => 'SHARE NOW',  'url' => '#glow-fd-share' ),
	'orders'  => array( 'label' => 'SUBMIT NOW', 'url' => '#glow-fd-brochures' ),
);
$current_step = '';
foreach ( glow_fd_checklist_steps() as $sk => $sl ) {
	if ( empty( $checklist[ $sk ] ) ) { $current_step = $sk; break; }
}

$is_admin_view = glow_fd_is_admin_view( $fid );
?>

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Caveat:wght@600;700&family=Inter:wght@400;500;600;700;800;900&display=swap">

<div class="glow-fd">

	<!-- ============ HEADER ============ -->
	<header class="glow-fd-top">

		<div class="glow-fd-brand">
			<div class="glow-fd-brand-word">
				<h2>GLOW</h2>
				<svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 1.5c.6 3.9 2.1 5.4 6 6-3.9.6-5.4 2.1-6 6-.6-3.9-2.1-5.4-6-6 3.9-.6 5.4-2.1 6-6Z" fill="#ff2d7e"/></svg>
			</div>
			<p class="glow-fd-brand-sub">SCENTED CANDLES</p>
		</div>

		<div class="glow-fd-top-rule" aria-hidden="true"></div>

		<div class="glow-fd-topmid">
			<div class="glow-fd-code">
				Fundraiser Code: <b id="glow-fd-code"><?php echo esc_html( $fid ); ?></b>
				<button type="button" class="glow-fd-code-copy" data-copy="<?php echo esc_attr( $fid ); ?>">COPY</button>
			</div>
			<div class="glow-fd-statusrow">
				<?php if ( $campaign['closed'] ) : ?>
					<span class="glow-fd-live is-off"><i></i>CAMPAIGN CLOSED</span>
				<?php elseif ( $campaign['active'] ) : ?>
					<span class="glow-fd-live is-on"><i></i>FUNDRAISER ACTIVE</span>
					<span class="glow-fd-days"><?php echo esc_html( $campaign['days_left'] ); ?> <?php echo esc_html( _n( 'DAY LEFT', 'DAYS LEFT', $campaign['days_left'] ) ); ?></span>
				<?php else : ?>
					<span class="glow-fd-live is-warn"><i></i>END DATE PASSED</span>
				<?php endif; ?>
			</div>
		</div>

		<div class="glow-fd-slogan" aria-hidden="true">
			<span class="glow-fd-spark s1">&#10022;</span>
			<span class="glow-fd-spark s2">&#10022;</span>
			<span class="glow-fd-spark s3">&#10023;</span>
			<span class="glow-fd-heart h1">&#9825;</span>
			<div class="glow-fd-slogan-txt">
				<span class="l1">Let's Reach</span>
				<span class="l2">Our Goal Together! <i>&#9825;</i></span>
			</div>
			<svg class="glow-fd-bars" width="120" height="92" viewBox="0 0 120 92" fill="none" aria-hidden="true">
				<defs><linearGradient id="glowBars" x1="0" y1="1" x2="0" y2="0"><stop offset="0%" stop-color="#ff2d7e"/><stop offset="100%" stop-color="#ffcf5c"/></linearGradient></defs>
				<rect x="4"  y="58" width="18" height="30" rx="5" fill="url(#glowBars)" opacity=".85"/>
				<rect x="30" y="44" width="18" height="44" rx="5" fill="url(#glowBars)" opacity=".9"/>
				<rect x="56" y="30" width="18" height="58" rx="5" fill="url(#glowBars)"/>
				<rect x="82" y="14" width="18" height="74" rx="5" fill="url(#glowBars)"/>
				<path d="M8 42 C40 20, 70 18, 102 6" stroke="#ffcf5c" stroke-width="4" stroke-linecap="round"/>
				<path d="M92 4 h12 v12" stroke="#ffcf5c" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
			</svg>
		</div>
	</header>

	<main class="glow-fd-board">

		<!-- ---------- NOTICES ---------- -->
		<?php if ( $is_admin_view ) : ?>
			<div class="glow-fd-notice is-admin">
				Admin view &mdash; you are looking at fundraiser #<?php echo esc_html( $fid ); ?>.
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
				Your online store is missing a photo or message &mdash; groups with both raise noticeably more.
				<a href="<?php echo esc_url( glow_fd_url( 'customize' ) ); ?>">Add them now</a>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $cart_saved ) ) : ?>
			<div class="glow-fd-notice is-cart">
				You have a saved brochure order waiting.
				<a href="#" id="savedOrder">Move it to my cart</a>
				<img id="loading_r" class="glow-fd-spin" src="https://glowscentedcandles.com/wp-content/uploads/2020/08/ajax-loader.gif" alt="">
			</div>
		<?php endif; ?>

		<!-- ---------- ROW 1 ---------- -->
		<section class="glow-fd-row glow-fd-row1">

			<!-- Chairperson -->
			<div class="glow-fd-card glow-fd-profile">
				<span class="glow-fd-profile-spark">&#10022;</span>
				<div class="glow-fd-avatar">
					<?php if ( $photo ) : ?>
						<img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $campaign['org'] ); ?>">
					<?php else : ?>
						<svg viewBox="0 0 100 100" role="img" aria-label="No photo yet">
							<rect width="100" height="100" rx="50" fill="#ffe1e8"/>
							<circle cx="50" cy="38" r="17" fill="#f7c9b0"/>
							<path d="M22 92c3-16 14-24 28-24s25 8 28 24H22Z" fill="#e05a86"/>
						</svg>
					<?php endif; ?>
				</div>
				<h3 class="glow-fd-hi">Hi,<br><?php echo $first_name ? esc_html( $first_name ) . '!' : 'there!'; ?></h3>
				<p class="glow-fd-role">
					Chairperson<br>
					<?php echo esc_html( $stats['seller_count'] ); ?> registered
					<?php echo esc_html( _n( 'seller', 'sellers', $stats['seller_count'] ) ); ?>
				</p>
				<a class="glow-fd-store-btn" href="<?php echo esc_url( $shop_link ); ?>" target="_blank" rel="noopener">
					<svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linejoin="round" aria-hidden="true"><path d="M3.6 4.5h16.8l1.4 4.2a3 3 0 0 1-5.7 1.6 3 3 0 0 1-5.6 0 3 3 0 0 1-5.7-1.6L3.6 4.5Z"/><path d="M4.8 11.4V20h14.4v-8.6"/></svg>
					VIEW MY STORE
					<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12h15M13 6l6 6-6 6"/></svg>
				</a>
				<a class="glow-fd-edit-link" href="<?php echo esc_url( add_query_arg( 'frid', $fid, glow_fd_url( 'edit_info' ) ) ); ?>">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" aria-hidden="true"><path d="M4 20h4L20 8l-4-4L4 16v4Z"/></svg>
					Edit fundraiser info
				</a>
			</div>

			<!-- Fundraising progress -->
			<div class="glow-fd-card glow-fd-progress">
				<div class="glow-fd-card-head">
					<span class="glow-fd-mini-bars" aria-hidden="true"><i></i><i></i><i></i></span>
					<h3>FUNDRAISING PROGRESS</h3>
					<?php if ( $campaign['end_fmt'] ) : ?>
						<span class="glow-fd-enddate">End Date: <?php echo esc_html( $campaign['end_fmt'] ); ?></span>
					<?php endif; ?>
				</div>

				<div class="glow-fd-progress-body">
					<div class="glow-fd-donut" style="--pct:<?php echo esc_attr( $pct ); ?>;">
						<div class="glow-fd-donut-in">
							<span class="pct"><?php echo esc_html( $pct ); ?>%</span>
							<span class="of">of $<?php echo esc_html( number_format( $goal, 0 ) ); ?><br>goal</span>
						</div>
					</div>
					<div class="glow-fd-raised">
						<div class="glow-fd-raised-amt">$<?php echo esc_html( number_format( $raised, 0 ) ); ?></div>
						<div class="glow-fd-raised-sub">Total Raised</div>
						<div class="glow-fd-bar"><span style="width:<?php echo esc_attr( $pct ); ?>%"></span></div>
						<div class="glow-fd-bar-goal">$<?php echo esc_html( number_format( $goal, 0 ) ); ?></div>
					</div>
				</div>

				<div class="glow-fd-split">
					<div>
						<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ec0f63" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 4h2l2.4 12.2a1.6 1.6 0 0 0 1.6 1.3h8.6a1.6 1.6 0 0 0 1.6-1.2L21 8H6"/><circle cx="10" cy="20.4" r="1.4"/><circle cx="17" cy="20.4" r="1.4"/></svg>
						<b>$<?php echo esc_html( number_format( $stats['online_gross'], 0 ) ); ?></b>
						<span>Online Sales<br>(ship to home)</span>
					</div>
					<div>
						<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ec0f63" stroke-width="1.8" stroke-linejoin="round" aria-hidden="true"><path d="M3.5 10.5 12 3.5l8.5 7"/><path d="M5.5 9.5V20h13V9.5M10 20v-6h4v6"/></svg>
						<b>$<?php echo esc_html( number_format( $stats['sts_gross'], 0 ) ); ?></b>
						<span>Online Sales<br>(ship to school)</span>
					</div>
					<div>
						<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ec0f63" stroke-width="1.8" stroke-linejoin="round" aria-hidden="true"><path d="M12 6.5C10.3 5.2 8 4.6 4.5 4.8v13c3.5-.2 5.8.4 7.5 1.7 1.7-1.3 4-1.9 7.5-1.7v-13c-3.5-.2-5.8.4-7.5 1.7Z"/><path d="M12 6.5v13"/></svg>
						<b>$<?php echo esc_html( number_format( $stats['broch_gross'], 0 ) ); ?></b>
						<span>Brochure<br>Sales</span>
					</div>
				</div>
			</div>

			<!-- Share -->
			<div class="glow-fd-card glow-fd-share" id="glow-fd-share">
				<div class="glow-fd-card-head">
					<svg width="24" height="24" viewBox="0 0 24 24" aria-hidden="true"><path d="M21.5 3 2.8 10.4c-.9.4-.8 1.6.1 1.8l4.6 1.3 1.4 4.8c.3.9 1.5 1 1.9.2L21.5 3Z" fill="#17b98a"/><path d="M21.5 3 9.1 18.5l-.5-4.9L21.5 3Z" fill="#0f8f6b"/></svg>
					<h3>SHARE YOUR FUNDRAISER</h3>
				</div>
				<p class="glow-fd-share-sub">Get more sellers and support by sharing these links!</p>

				<div class="glow-fd-share-grid2">
					<div class="glow-fd-share-links">
						<label class="glow-fd-linklabel">Seller Registration Link</label>
						<div class="glow-fd-linkbox">
							<input type="text" readonly value="<?php echo esc_attr( preg_replace( '#^https?://(www\.)?#', '', $reg_link ) ); ?>" aria-label="Seller registration link">
							<button type="button" class="glow-fd-copy" data-copy="<?php echo esc_attr( $reg_link ); ?>">COPY</button>
						</div>

						<label class="glow-fd-linklabel">Store Shopping Link</label>
						<div class="glow-fd-linkbox">
							<input type="text" readonly value="<?php echo esc_attr( preg_replace( '#^https?://(www\.)?#', '', $shop_link ) ); ?>" aria-label="Store shopping link">
							<button type="button" class="glow-fd-copy" data-copy="<?php echo esc_attr( $shop_link ); ?>">COPY</button>
						</div>
					</div>

					<div class="glow-fd-qr-wrap">
						<?php if ( $qr_reg ) : ?>
							<div class="glow-fd-qr-item">
								<img class="glow-fd-qr" src="<?php echo esc_url( $qr_reg ); ?>" alt="QR code for seller registration" loading="lazy">
								<span>Scan to Register</span>
							</div>
						<?php endif; ?>
						<?php if ( $qr_shop ) : ?>
							<div class="glow-fd-qr-item">
								<img class="glow-fd-qr" src="<?php echo esc_url( $qr_shop ); ?>" alt="QR code for the shop link" loading="lazy">
								<span>Scan to Shop</span>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<div class="glow-fd-share-btns">
					<a class="glow-fd-sbtn is-text" href="sms:?&body=<?php echo rawurlencode( 'Support ' . $campaign['org'] . ' — shop GLOW candles here: ' . $shop_link ); ?>">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M12 3c5 0 9 3.3 9 7.4 0 4.1-4 7.4-9 7.4-1 0-2-.1-2.9-.4L4 19.5l1.2-3.3C3.8 14.8 3 12.7 3 10.4 3 6.3 7 3 12 3Z"/></svg>
						Text the Link
					</a>
					<a class="glow-fd-sbtn is-fb" target="_blank" rel="noopener" href="https://www.facebook.com/sharer.php?u=<?php echo rawurlencode( $shop_link ); ?>">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.2c-1.2 0-1.6.8-1.6 1.6V12h2.7l-.4 2.9h-2.3v7A10 10 0 0 0 22 12Z"/></svg>
						Facebook
					</a>
					<a class="glow-fd-sbtn is-letter" href="<?php echo esc_url( add_query_arg( 'fund_id', $fid, glow_fd_url( 'participant_ltr' ) ) ); ?>">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linejoin="round" aria-hidden="true"><path d="M5 3.5h9l5 5V20.5H5z"/><path d="M14 3.5v5h5M8 13h8M8 16.5h5"/></svg>
						Participant Letter
					</a>
				</div>
			</div>
		</section>

		<!-- ---------- ROW 2 : ACTION TILES ---------- -->
		<section class="glow-fd-row glow-fd-row2">

			<a class="glow-fd-tile is-store" href="<?php echo esc_url( glow_fd_url( 'customize' ) ); ?>">
				<span class="glow-fd-tile-icon">
					<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linejoin="round" aria-hidden="true"><path d="M3.6 4.5h16.8l1.4 4.2a3 3 0 0 1-5.7 1.6 3 3 0 0 1-5.6 0 3 3 0 0 1-5.7-1.6L3.6 4.5Z"/><path d="M4.8 11.4V20h14.4v-8.6"/></svg>
				</span>
				<span class="glow-fd-tile-txt">
					<strong>CUSTOMIZE<br>YOUR STORE</strong>
					<span>Add your logo, photo and a message for your sellers and supporters.</span>
				</span>
				<span class="glow-fd-tile-chev"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg></span>
			</a>

			<a class="glow-fd-tile is-sellers" href="#glow-fd-share">
				<span class="glow-fd-tile-icon">
					<svg width="26" height="26" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><circle cx="9" cy="8" r="3.6"/><path d="M2.5 19.5c1.1-3.6 3.5-5.2 6.5-5.2s5.4 1.6 6.5 5.2H2.5Z"/><circle cx="17" cy="9" r="2.7"/><path d="M15.6 14.6c2.8.2 4.8 1.6 5.9 4.9h-4.2c-.3-2-0.9-3.6-1.7-4.9Z"/></svg>
				</span>
				<span class="glow-fd-tile-txt">
					<strong>INVITE<br>YOUR SELLERS</strong>
					<span>Share your registration link, QR code and participant letter.</span>
				</span>
				<span class="glow-fd-tile-chev"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg></span>
			</a>

			<button type="button" class="glow-fd-tile is-brochure" data-open="glow-fd-brochures">
				<span class="glow-fd-tile-icon">
					<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linejoin="round" aria-hidden="true"><path d="M12 6.5C10.3 5.2 8 4.6 4.5 4.8v13c3.5-.2 5.8.4 7.5 1.7 1.7-1.3 4-1.9 7.5-1.7v-13c-3.5-.2-5.8.4-7.5 1.7Z"/><path d="M12 6.5v13"/></svg>
				</span>
				<span class="glow-fd-tile-txt">
					<strong>SUBMIT<br>BROCHURE ORDERS</strong>
					<span>Enter your group's paper order forms.</span>
				</span>
				<span class="glow-fd-tile-chev"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg></span>
			</button>

			<a class="glow-fd-tile is-reports" href="<?php echo esc_url( glow_fd_url( 'report' ) ); ?>">
				<span class="glow-fd-tile-icon">
					<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M5 20V10M12 20V4M19 20v-7"/></svg>
				</span>
				<span class="glow-fd-tile-txt">
					<strong>SALES &amp; REPORTS</strong>
					<span>View sales, seller activity and download your final report.</span>
				</span>
				<span class="glow-fd-tile-chev"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg></span>
			</a>
		</section>

		<!-- brochure links, revealed by the tile -->
		<section class="glow-fd-card glow-fd-drawer" id="glow-fd-brochures" hidden>
			<div class="glow-fd-card-head"><h3>ENTER A BROCHURE ORDER</h3></div>
			<div class="glow-fd-brochure-links">
				<a href="<?php echo esc_url( glow_fd_url( 'brochure_ss' ) ); ?>">Spring / Summer Brochure<span>Enter order</span></a>
				<a href="<?php echo esc_url( glow_fd_url( 'brochure_new' ) ); ?>">Perfect Scents for Every Season<span>Enter order</span></a>
				<a href="<?php echo esc_url( glow_fd_url( 'brochure_fw' ) ); ?>">Season to Sparkle Brochure<span>Enter order</span></a>
			</div>
			<p class="glow-fd-brochure-total">
				Brochure sales so far: <b>$<?php echo esc_html( number_format( $stats['broch_gross'], 2 ) ); ?></b>
				&middot; amount due to GLOW: <b>$<?php echo esc_html( number_format( $stats['broch_due'], 2 ) ); ?></b>
			</p>
		</section>

		<!-- ---------- LOWER GRID : main column + right column ---------- -->
		<section class="glow-fd-lower">

			<div class="glow-fd-lower-main">

				<!-- Checklist -->
				<div class="glow-fd-card glow-fd-checklist">
					<div class="glow-fd-checklist-intro">
						<div class="glow-fd-card-head">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ec0f63" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4.5" y="3.5" width="15" height="17" rx="2.5"/><path d="M9 3.5h6v2.6H9zM8.6 11h6.8M8.6 15h4.6"/></svg>
							<h3>CHAIRPERSON CHECKLIST</h3>
						</div>
						<p>Follow these steps to hit your goal!</p>
					</div>

					<div class="glow-fd-steps">
						<?php $n = 0; foreach ( glow_fd_checklist_steps() as $k => $label ) : $n++;
							$done    = ! empty( $checklist[ $k ] );
							$current = ( $k === $current_step );
						?>
							<div class="glow-fd-step<?php echo $done ? ' is-done' : ''; ?><?php echo $current ? ' is-current' : ''; ?>">
								<a class="glow-fd-step-num" href="<?php echo esc_url( glow_fd_step_url( $k ) ); ?>"
								   title="<?php echo $done ? 'Mark as not done' : 'Mark as done'; ?>"
								   aria-pressed="<?php echo $done ? 'true' : 'false'; ?>"><span><?php echo esc_html( $n ); ?></span></a>
								<span class="glow-fd-step-label"><?php echo esc_html( $label ); ?></span>
								<?php if ( $current && ! $campaign['closed'] ) : ?>
									<a class="glow-fd-step-badge" href="<?php echo esc_url( $step_actions[ $k ]['url'] ); ?>"
									   <?php if ( '#glow-fd-brochures' === $step_actions[ $k ]['url'] ) echo 'data-open-drawer="1"'; ?>><?php echo esc_html( $step_actions[ $k ]['label'] ); ?></a>
								<?php else : ?>
									<span class="glow-fd-step-check"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4.5 4.5L19 7"/></svg></span>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- Sales snapshot -->
				<div class="glow-fd-card glow-fd-snapshot">
					<div class="glow-fd-card-head">
						<span class="glow-fd-mini-bars" aria-hidden="true"><i></i><i></i><i></i></span>
						<div class="glow-fd-head-stack">
							<h3>SALES SNAPSHOT</h3>
							<p>Live results from your fundraiser.</p>
						</div>
						<a class="glow-fd-fullreport" href="<?php echo esc_url( glow_fd_url( 'report' ) ); ?>">View Full Report &rarr;</a>
					</div>

					<div class="glow-fd-snap-grid">
						<div class="glow-fd-snap is-pink">
							<span class="glow-fd-snap-icon">$</span>
							<div><b>$<?php echo esc_html( number_format( $stats['total_gross'], 0 ) ); ?></b><span>Total Sales</span></div>
						</div>
						<div class="glow-fd-snap is-blue">
							<span class="glow-fd-snap-icon">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><circle cx="9" cy="8" r="3.4"/><path d="M2.8 19c1.1-3.4 3.3-4.9 6.2-4.9s5.1 1.5 6.2 4.9H2.8Z"/><circle cx="17" cy="9" r="2.5"/><path d="M15.8 14.4c2.5.3 4.3 1.6 5.3 4.6h-3.9c-.3-1.8-.8-3.3-1.4-4.6Z"/></svg>
							</span>
							<div><b><?php echo esc_html( $stats['seller_count'] ); ?></b><span>Sellers<br>$<?php echo esc_html( number_format( $avg_seller, 0 ) ); ?> avg/seller</span></div>
						</div>
						<div class="glow-fd-snap is-green">
							<span class="glow-fd-snap-icon">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linejoin="round" aria-hidden="true"><path d="M5 9h14l-1.3 10.2a1.8 1.8 0 0 1-1.8 1.6H8.1a1.8 1.8 0 0 1-1.8-1.6L5 9Z"/><path d="M8.5 9V7a3.5 3.5 0 0 1 7 0v2"/></svg>
							</span>
							<div><b><?php echo esc_html( number_format( $items_sold ) ); ?></b><span>Items Sold</span></div>
						</div>
						<div class="glow-fd-snap is-purple">
							<span class="glow-fd-snap-icon">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M6 3h12v2h3v3c0 2.5-1.9 4.4-4.3 4.9A6 6 0 0 1 13 16.9V19h3v2H8v-2h3v-2.1a6 6 0 0 1-3.7-3.1C4.9 13.4 3 11.5 3 9V5h3V3Zm-1 4v2c0 1.2.8 2.3 2 2.8V7H5Zm14 0h-2v4.8c1.2-.5 2-1.6 2-2.8V7Z"/></svg>
							</span>
							<div>
								<span class="glow-fd-snap-top">Top Seller</span>
								<b class="is-name"><?php echo $top_seller ? esc_html( $top_seller['name'] ) : '&mdash;'; ?></b>
								<?php if ( $top_seller ) : ?><span>$<?php echo esc_html( number_format( $top_seller['gross'], 0 ) ); ?></span><?php endif; ?>
							</div>
						</div>
					</div>
				</div>

				<!-- Tools -->
				<div class="glow-fd-card glow-fd-tools">
					<div class="glow-fd-card-head">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="#ec0f63" aria-hidden="true"><path d="M12 8.5A3.5 3.5 0 1 0 12 15.5 3.5 3.5 0 0 0 12 8.5Zm9 3.5c0 .6-.05 1.1-.14 1.7l2 1.6-2 3.4-2.4-1a8.9 8.9 0 0 1-2.9 1.7l-.36 2.6H10.8l-.36-2.6a8.9 8.9 0 0 1-2.9-1.7l-2.4 1-2-3.4 2-1.6A9.6 9.6 0 0 1 5 12c0-.6.05-1.1.14-1.7l-2-1.6 2-3.4 2.4 1a8.9 8.9 0 0 1 2.9-1.7L10.8 2h2.4l.36 2.6a8.9 8.9 0 0 1 2.9 1.7l2.4-1 2 3.4-2 1.6c.09.6.14 1.1.14 1.7Z"/></svg>
						<div class="glow-fd-head-stack">
							<h3>ADDITIONAL TOOLS &amp; RESOURCES</h3>
							<p>Everything you need to run a successful fundraiser.</p>
						</div>
					</div>

					<div class="glow-fd-tools-grid">
						<a href="<?php echo esc_url( add_query_arg( 'frid', $fid, glow_fd_url( 'edit_info' ) ) ); ?>">
							<span class="glow-fd-tool-ic is-pink"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" stroke-linejoin="round" aria-hidden="true"><path d="M5 3.5h9l5 5V20.5H5z"/><path d="M14 3.5v5h5M8 13h8M8 16.5h5"/></svg></span>
							Fundraiser<br>Details
						</a>
						<a href="<?php echo esc_url( glow_fd_url( 'instructions' ) ); ?>" target="_blank" rel="noopener">
							<span class="glow-fd-tool-ic is-orange"><svg width="16" height="16" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M20 4 4 10v4l3 .9V19a2 2 0 0 0 4 0v-2.9l9 2.9V4Z"/></svg></span>
							Marketing<br>Toolbox
						</a>
						<a href="#glow-fd-sellers-card">
							<span class="glow-fd-tool-ic is-purple"><svg width="16" height="16" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><circle cx="9" cy="8" r="3.4"/><path d="M2.8 19c1.1-3.4 3.3-4.9 6.2-4.9s5.1 1.5 6.2 4.9H2.8Z"/><circle cx="17" cy="9" r="2.5"/><path d="M15.8 14.4c2.5.3 4.3 1.6 5.3 4.6h-3.9c-.3-1.8-.8-3.3-1.4-4.6Z"/></svg></span>
							Registered<br>Sellers
						</a>
						<a href="#glow-fd-orders">
							<span class="glow-fd-tool-ic is-blue"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v11M7.5 10 12 14.5 16.5 10"/><path d="M4.5 17.5V20h15v-2.5"/></svg></span>
							Download<br>Invoice
						</a>
						<a href="#glow-fd-orders">
							<span class="glow-fd-tool-ic is-amber"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" stroke-linejoin="round" aria-hidden="true"><path d="M3 7.5 12 3l9 4.5-9 4.5z"/><path d="M3 7.5v9L12 21l9-4.5v-9M12 12v9"/></svg></span>
							Claims /<br>Replacements
						</a>
						<a href="#glow-fd-orders">
							<span class="glow-fd-tool-ic is-teal"><svg width="16" height="16" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M2.5 6.5h10v9h-10z"/><path d="M12.5 9.5h4l3 3v3h-7z"/><circle cx="7" cy="18" r="1.9"/><circle cx="16.5" cy="18" r="1.9"/></svg></span>
							Order Status<br>&amp; Tracking
						</a>
					</div>
				</div>
			</div>

			<!-- Right column -->
			<div class="glow-fd-lower-side">

				<div class="glow-fd-card glow-fd-thanks">
					<span class="glow-fd-th-heart h1">&#10084;</span>
					<span class="glow-fd-th-heart h2">&#9825;</span>
					<span class="glow-fd-th-heart h3">&#10084;</span>
					<span class="glow-fd-th-heart h4">&#9825;</span>
					<div class="glow-fd-thanks-script">
						<span class="a">Thank You</span>
						<span class="b">for Choosing GLOW!</span>
					</div>
					<p>Your time and dedication are helping your group make this fundraiser a success!</p>
					<span class="glow-fd-confetti" style="left:14%;bottom:14px;background:#ff2d7e;transform:rotate(30deg);"></span>
					<span class="glow-fd-confetti" style="left:30%;bottom:26px;background:#ffcf5c;transform:rotate(-24deg);"></span>
					<span class="glow-fd-confetti" style="left:52%;bottom:12px;background:#4fd6ff;transform:rotate(18deg);"></span>
					<span class="glow-fd-confetti" style="left:70%;bottom:24px;background:#a06bff;transform:rotate(-30deg);"></span>
					<span class="glow-fd-confetti" style="left:86%;bottom:14px;background:#17b98a;transform:rotate(24deg);"></span>
				</div>

				<div class="glow-fd-card glow-fd-settings">
					<div class="glow-fd-card-head">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="#ec0f63" aria-hidden="true"><path d="M12 8.5A3.5 3.5 0 1 0 12 15.5 3.5 3.5 0 0 0 12 8.5Zm9 3.5c0 .6-.05 1.1-.14 1.7l2 1.6-2 3.4-2.4-1a8.9 8.9 0 0 1-2.9 1.7l-.36 2.6H10.8l-.36-2.6a8.9 8.9 0 0 1-2.9-1.7l-2.4 1-2-3.4 2-1.6A9.6 9.6 0 0 1 5 12c0-.6.05-1.1.14-1.7l-2-1.6 2-3.4 2.4 1a8.9 8.9 0 0 1 2.9-1.7L10.8 2h2.4l.36 2.6a8.9 8.9 0 0 1 2.9 1.7l2.4-1 2 3.4-2 1.6c.09.6.14 1.1.14 1.7Z"/></svg>
						<h3>FUNDRAISER SETTINGS</h3>
					</div>
					<a class="glow-fd-setrow" href="<?php echo esc_url( add_query_arg( 'frid', $fid, glow_fd_url( 'edit_info' ) ) ); ?>">
						Edit Fundraiser Info
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg>
					</a>
					<?php if ( ! $campaign['closed'] ) : ?>
						<button type="button" class="glow-fd-setrow is-danger" id="close_out" data-id="<?php echo esc_attr( $fid ); ?>">
							<?php echo $campaign['virtual'] ? 'Close Virtual Fundraiser' : 'Close Out Fundraiser'; ?>
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg>
						</button>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<!-- ========== DETAIL SECTIONS (functional, below the fold) ========== -->

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
		<?php
		if ( function_exists( 'glow_so_get_fundraiser_orders' ) ) :
			$glow_orders = glow_so_get_fundraiser_orders( $fid );
			$glow_new    = array_filter( $glow_orders, function ( $o ) { return 'moved' !== $o->status; } );
		?>
		<section class="glow-fd-card">
			<div class="glow-fd-card-head">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#f5741a" stroke-width="1.6" stroke-linejoin="round" aria-hidden="true"><path d="M12 6.5C10.3 5.2 8 4.6 4.5 4.8v13c3.5-.2 5.8.4 7.5 1.7 1.7-1.3 4-1.9 7.5-1.7v-13c-3.5-.2-5.8.4-7.5 1.7Z"/><path d="M12 6.5v13"/></svg>
				<h3>SELLER BROCHURE ORDERS</h3>
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

		<section class="glow-fd-card" id="glow-fd-orders">
			<div class="glow-fd-card-head">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0fa47a" stroke-width="1.7" stroke-linejoin="round" aria-hidden="true"><path d="M3 7.5 12 3l9 4.5-9 4.5z"/><path d="M3 7.5v9L12 21l9-4.5v-9M12 12v9"/></svg>
				<h3>ORDER STATUS &amp; TRACKING</h3>
				<a class="glow-fd-refresh" href="<?php echo esc_url( add_query_arg( 'glow_fd_refresh', 1 ) ); ?>" title="Recalculate now">Refresh</a>
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
										&mdash;
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
										&mdash;
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
										&mdash;
									<?php endif; ?>
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

<!-- close-out confirm -->
<div class="glow-fd-modal" id="glow-fd-closeout-modal">
	<div class="glow-fd-modal-box">
		<h3>Close this campaign?</h3>
		<p>You will no longer be able to submit orders for <?php echo esc_html( $campaign['org'] ); ?>. Print any reports you need first.
		<?php if ( ! $campaign['virtual'] ) : ?> Don't close out if you still have brochure orders to submit.<?php endif; ?></p>
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
				btn.textContent = 'COPIED!';
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
	function openDrawer(scroll) {
		var el = document.getElementById('glow-fd-brochures');
		if (!el) return;
		el.hidden = false;
		if (scroll) el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
	}
	var opener = document.querySelector('[data-open]');
	if (opener) {
		opener.addEventListener('click', function () {
			var el = document.getElementById(opener.getAttribute('data-open'));
			if (!el) return;
			el.hidden = !el.hidden;
			if (!el.hidden) el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
		});
	}
	document.querySelectorAll('[data-open-drawer]').forEach(function (a) {
		a.addEventListener('click', function () { openDrawer(true); });
	});

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
 * Seller brochure order editor modal — unchanged; depends on
 * glow_get_order / glow_save_order / glow_move_to_cart.
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
		<div class="glow-e-foot"><span><b id="glow-e-count">0</b> items &middot; <b>$<span id="glow-e-total">0.00</span></b></span></div>
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
   GLOW Fundraiser Dashboard — mockup design, scoped .glow-fd
   ============================================================ */
.glow-fd{
	--pink:#ec0f63; --pink-hi:#ff2d7e; --orange:#f5741a; --amber:#f9b81c;
	--purple:#6236e0; --blue:#2f7bea; --teal:#17b98a;
	--ink:#181735; --muted:#6f6c85; --board:#f2f3f9; --card:#fff; --line:#e8e8f0;
	font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;
	color:var(--ink); background:#0b1032; max-width:1780px; margin:0 auto; overflow:hidden;
	-webkit-font-smoothing:antialiased;
}
.glow-fd *{ box-sizing:border-box; }
.glow-fd button{ font-family:inherit; cursor:pointer; border:0; background:none; color:inherit; }
.glow-fd a{ text-decoration:none; }
.glow-fd :focus-visible{ outline:3px solid #7c4bf0; outline-offset:2px; border-radius:6px; }
.glow-fd-gate{ max-width:520px; margin:80px auto; text-align:center; padding:40px 24px; }
.glow-fd-gate-btn{ display:inline-block; margin-top:14px; padding:12px 30px; border-radius:30px; background:#ec0f63; color:#fff !important; font-weight:600; }

/* ---------- header ---------- */
.glow-fd-top{ position:relative; display:flex; align-items:center; gap:30px; padding:20px 34px; min-height:118px; overflow:hidden;
	background:
		radial-gradient(420px 200px at 96% 0%, rgba(124,60,220,.55), transparent 70%),
		radial-gradient(500px 240px at 74% 100%, rgba(64,40,150,.5), transparent 70%),
		linear-gradient(100deg,#090d2c 0%,#0e1440 55%,#251a56 100%); }
.glow-fd-brand{ flex:0 0 auto; }
.glow-fd-brand-word{ display:flex; align-items:flex-start; gap:8px; }
.glow-fd-brand-word h2{ margin:0; font-family:'Playfair Display',Georgia,serif; font-weight:500; font-size:40px; line-height:1; letter-spacing:.12em; color:#fff; }
.glow-fd-brand-sub{ margin:8px 0 0; font-size:12px; font-weight:500; letter-spacing:.28em; color:#dcd8ee; }
.glow-fd-top-rule{ flex:0 0 1px; align-self:stretch; background:linear-gradient(180deg,transparent,rgba(255,255,255,.24),transparent); }
.glow-fd-topmid{ flex:0 0 auto; }
.glow-fd-code{ display:flex; align-items:center; gap:10px; font-size:16px; color:#e7e4f5; font-weight:500; }
.glow-fd-code b{ font-size:19px; color:#fff; font-weight:800; letter-spacing:.03em; }
.glow-fd-code-copy{ padding:7px 16px; border-radius:999px; border:1.5px solid rgba(255,255,255,.45); color:#fff; font-size:12.5px; font-weight:700; letter-spacing:.06em; }
.glow-fd-code-copy:hover{ background:rgba(255,255,255,.15); }
.glow-fd-code-copy.is-done{ background:var(--teal); border-color:var(--teal); }
.glow-fd-statusrow{ display:flex; align-items:center; gap:18px; margin-top:10px; }
.glow-fd-live{ display:inline-flex; align-items:center; gap:8px; font-size:14.5px; font-weight:800; letter-spacing:.04em; }
.glow-fd-live i{ width:10px; height:10px; border-radius:50%; }
.glow-fd-live.is-on{ color:#2fdba4; }   .glow-fd-live.is-on i{ background:#2fdba4; box-shadow:0 0 10px rgba(47,219,164,.9); }
.glow-fd-live.is-off{ color:#ff8080; }  .glow-fd-live.is-off i{ background:#ff8080; }
.glow-fd-live.is-warn{ color:#ffcf5c; } .glow-fd-live.is-warn i{ background:#ffcf5c; }
.glow-fd-days{ font-size:14.5px; font-weight:800; letter-spacing:.05em; color:#ffcf5c; }
.glow-fd-slogan{ position:relative; flex:1 1 auto; display:flex; align-items:center; justify-content:flex-end; gap:26px; min-width:0; }
.glow-fd-slogan-txt{ text-align:center; line-height:1; transform:rotate(-4deg); }
.glow-fd-slogan-txt .l1{ display:block; font-family:'Caveat',cursive; font-weight:700; font-size:44px; color:#fff; }
.glow-fd-slogan-txt .l2{ display:block; font-family:'Caveat',cursive; font-weight:700; font-size:40px; margin-top:-4px;
	background:linear-gradient(90deg,#ff4d8d,#ff77b0); -webkit-background-clip:text; background-clip:text; color:transparent; }
.glow-fd-slogan-txt .l2 i{ font-style:normal; color:#ff77b0; }
.glow-fd-bars{ flex:0 0 auto; }
.glow-fd-spark{ position:absolute; color:#ffd166; text-shadow:0 0 12px rgba(255,209,102,.8); }
.glow-fd-spark.s1{ left:2%; top:6px; font-size:20px; }
.glow-fd-spark.s2{ right:34%; bottom:4px; font-size:14px; color:#ff77b0; }
.glow-fd-spark.s3{ right:2%; top:2px; font-size:22px; }
.glow-fd-heart{ position:absolute; color:#ff77b0; }
.glow-fd-heart.h1{ left:14%; bottom:8px; font-size:18px; }

/* ---------- board ---------- */
.glow-fd-board{ background:var(--board); padding:18px 18px 30px; display:grid; gap:18px; }
.glow-fd-row{ display:grid; gap:18px; }
.glow-fd-row1{ grid-template-columns:.62fr 1.05fr 1.15fr; }
.glow-fd-row2{ grid-template-columns:repeat(4,1fr); }
.glow-fd-card{ background:var(--card); border:1px solid var(--line); border-radius:20px; box-shadow:0 2px 6px rgba(22,20,50,.05); padding:20px 22px; }
.glow-fd-card-head{ display:flex; align-items:center; gap:11px; }
.glow-fd-card-head h3{ margin:0; font-size:16.5px; font-weight:800; letter-spacing:.02em; }
.glow-fd-head-stack h3{ margin:0; }
.glow-fd-head-stack p{ margin:3px 0 0; font-size:13.5px; color:var(--muted); }
.glow-fd-mini-bars{ display:inline-flex; align-items:flex-end; gap:2.5px; height:20px; }
.glow-fd-mini-bars i{ width:5px; border-radius:2px; background:var(--pink); }
.glow-fd-mini-bars i:nth-child(1){ height:9px; opacity:.55; }
.glow-fd-mini-bars i:nth-child(2){ height:14px; opacity:.8; }
.glow-fd-mini-bars i:nth-child(3){ height:20px; }
.glow-fd-hint{ margin:12px 0 4px; font-size:14.5px; color:#6b6982; }
.glow-fd-empty{ margin:20px 0; font-size:15px; color:#8d8aa0; text-align:center; }
.glow-fd-green{ color:var(--teal); }
.glow-fd-refresh{ margin-left:auto; font-size:13.5px; font-weight:600; color:#4a3fd0 !important; text-decoration:underline; }
.glow-fd-count-pill{ margin-left:12px; padding:5px 13px; border-radius:999px; background:#f1eff6; font-size:13.5px; font-weight:600; color:#5d5a75; }
.glow-fd-refresh + .glow-fd-count-pill{ margin-left:12px; }

/* notices */
.glow-fd-notice{ display:flex; align-items:center; gap:12px; flex-wrap:wrap; padding:14px 20px; border-radius:14px; font-size:15px; font-weight:500; }
.glow-fd-notice a{ font-weight:700; text-decoration:underline; }
.glow-fd-notice.is-admin{ background:#eef0fd; color:#3b3a72; }
.glow-fd-notice.is-done{ background:#eaf8f1; color:#0d6b50; }
.glow-fd-notice.is-todo{ background:#fff4e7; color:#8a4a08; }
.glow-fd-notice.is-cart{ background:#f3f1ff; color:#3f2fa8; }
.glow-fd-spin{ display:none; width:16px; height:16px; }

/* ---------- profile ---------- */
.glow-fd-profile{ position:relative; background:linear-gradient(160deg,#ffe4ec,#ffd7e4); border-color:#f9cfdd; display:flex; flex-direction:column; align-items:flex-start; padding:24px; }
.glow-fd-profile-spark{ position:absolute; right:20px; top:16px; color:var(--pink-hi); font-size:20px; }
.glow-fd-avatar{ width:104px; height:104px; border-radius:50%; padding:4px; background:#fff; box-shadow:0 0 0 3px #ffb7cd; margin-bottom:14px; }
.glow-fd-avatar img,.glow-fd-avatar svg{ width:100%; height:100%; border-radius:50%; display:block; object-fit:cover; background:#ffe3ea; }
.glow-fd-hi{ margin:0 0 8px; font-size:34px; line-height:1.05; font-weight:800; letter-spacing:-.02em; color:#1a1330; }
.glow-fd-role{ margin:0 0 18px; font-size:15.5px; line-height:1.45; color:#5b4a5c; font-weight:500; }
.glow-fd-store-btn{ display:flex; align-items:center; justify-content:center; gap:10px; width:100%; padding:14px 16px; border-radius:14px;
	background:linear-gradient(180deg,var(--pink-hi),var(--pink)); color:#fff !important; font-size:15px; font-weight:800; letter-spacing:.03em;
	box-shadow:0 8px 20px rgba(236,15,99,.35); }
.glow-fd-store-btn:hover{ filter:brightness(1.06); }
.glow-fd-edit-link{ display:inline-flex; align-items:center; gap:7px; margin-top:12px; font-size:14px; font-weight:600; color:#4a3fd0 !important; text-decoration:underline; }

/* ---------- progress ---------- */
.glow-fd-enddate{ margin-left:auto; font-size:13.5px; color:var(--muted); font-weight:500; }
.glow-fd-progress-body{ display:flex; align-items:center; gap:24px; margin:18px 0 6px; }
.glow-fd-donut{ position:relative; flex:0 0 172px; width:172px; height:172px; border-radius:50%; display:grid; place-items:center;
	background:conic-gradient(var(--pink) calc(var(--pct) * 1%), #f0e4ec 0); }
.glow-fd-donut::after{ content:""; position:absolute; inset:17px; border-radius:50%; background:var(--card); }
.glow-fd-donut-in{ position:relative; z-index:2; text-align:center; line-height:1.1; }
.glow-fd-donut-in .pct{ display:block; font-size:38px; font-weight:800; letter-spacing:-.03em; }
.glow-fd-donut-in .of{ display:block; margin-top:4px; font-size:14px; font-weight:600; color:#4d4b63; }
.glow-fd-raised{ flex:1 1 auto; min-width:0; }
.glow-fd-raised-amt{ font-size:48px; font-weight:900; letter-spacing:-.04em; line-height:1; }
.glow-fd-raised-sub{ margin:6px 0 14px; font-size:16px; font-weight:600; color:#4f4d66; }
.glow-fd-bar{ position:relative; height:10px; border-radius:999px; background:#ece9f0; overflow:hidden; }
.glow-fd-bar span{ position:absolute; inset:0 auto 0 0; border-radius:999px; background:linear-gradient(90deg,#f0176e,#ff2d7e); }
.glow-fd-bar-goal{ margin-top:6px; text-align:right; font-size:14px; font-weight:700; color:#4f4d66; }
.glow-fd-split{ display:grid; grid-template-columns:repeat(3,1fr); margin-top:14px; }
.glow-fd-split div{ display:flex; flex-direction:column; align-items:center; text-align:center; gap:4px; padding:8px 6px; }
.glow-fd-split div + div{ border-left:1px solid var(--line); }
.glow-fd-split b{ font-size:19px; font-weight:800; letter-spacing:-.01em; }
.glow-fd-split span{ font-size:12.5px; line-height:1.3; color:var(--muted); font-weight:500; }

/* ---------- share ---------- */
.glow-fd-share-sub{ margin:8px 0 14px; font-size:14px; color:var(--muted); }
.glow-fd-share-grid2{ display:flex; gap:18px; }
.glow-fd-share-links{ flex:1 1 auto; min-width:0; }
.glow-fd-linklabel{ display:block; margin:0 0 6px; font-size:13px; font-weight:700; color:#3d3b52; }
.glow-fd-linkbox{ display:flex; align-items:center; gap:8px; padding:5px 5px 5px 14px; border:1.5px solid #e5d9f5; border-radius:12px; background:#faf7ff; margin-bottom:12px; }
.glow-fd-linkbox input{ flex:1 1 auto; min-width:0; border:0; outline:0; background:transparent; font-family:inherit; font-size:14.5px; font-weight:500; color:#4a3fd0; padding:6px 0; }
.glow-fd-copy{ flex:0 0 auto; padding:10px 18px; border-radius:10px; background:linear-gradient(180deg,var(--pink-hi),var(--pink)); color:#fff; font-size:13px; font-weight:800; letter-spacing:.05em; }
.glow-fd-copy.is-done{ background:var(--teal); }
.glow-fd-qr-wrap{ flex:0 0 auto; display:flex; flex-direction:column; gap:10px; align-items:center; justify-content:flex-start; }
.glow-fd-qr-item{ text-align:center; }
.glow-fd-qr{ width:84px; height:84px; display:block; margin:0 auto 4px; border:1px solid var(--line); border-radius:8px; padding:4px; background:#fff; }
.glow-fd-qr-item span{ display:block; font-size:11.5px; font-weight:600; color:#4b4960; }
.glow-fd-share-btns{ display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-top:8px; }
.glow-fd-sbtn{ display:flex; align-items:center; justify-content:center; gap:9px; padding:13px 10px; border-radius:12px; color:#fff !important; font-size:14.5px; font-weight:700; box-shadow:0 6px 16px rgba(20,18,45,.12); white-space:nowrap; }
.glow-fd-sbtn:hover{ filter:brightness(1.06); }
.glow-fd-sbtn.is-text{ background:linear-gradient(180deg,#f5176f,#e00c5e); }
.glow-fd-sbtn.is-fb{ background:linear-gradient(180deg,#6a48ea,#5330d8); }
.glow-fd-sbtn.is-letter{ background:linear-gradient(180deg,#f97d22,#ee6410); }

/* ---------- action tiles ---------- */
.glow-fd-tile{ position:relative; display:flex; align-items:flex-start; gap:14px; padding:20px 18px; border-radius:18px; width:100%; text-align:left; border:1px solid transparent; box-shadow:0 2px 6px rgba(22,20,50,.05); }
.glow-fd-tile:hover{ transform:translateY(-2px); box-shadow:0 8px 20px rgba(22,20,50,.10); }
.glow-fd-tile-icon{ flex:0 0 52px; width:52px; height:52px; border-radius:50%; display:grid; place-items:center; }
.glow-fd-tile-txt{ flex:1 1 auto; min-width:0; }
.glow-fd-tile-txt strong{ display:block; margin-bottom:6px; font-size:15.5px; font-weight:800; line-height:1.22; letter-spacing:.01em; }
.glow-fd-tile-txt span{ display:block; font-size:13px; line-height:1.42; color:#5f5d75; }
.glow-fd-tile-chev{ position:absolute; right:14px; bottom:14px; width:26px; height:26px; border-radius:50%; display:grid; place-items:center; background:#fff; }
.glow-fd-tile.is-store{ background:#ffe4ee; border-color:#fbd0e0; }
.glow-fd-tile.is-store .glow-fd-tile-icon{ background:var(--pink); }
.glow-fd-tile.is-store strong,.glow-fd-tile.is-store .glow-fd-tile-chev{ color:var(--pink); }
.glow-fd-tile.is-sellers{ background:#ebe7fd; border-color:#d8d0f8; }
.glow-fd-tile.is-sellers .glow-fd-tile-icon{ background:var(--purple); }
.glow-fd-tile.is-sellers strong,.glow-fd-tile.is-sellers .glow-fd-tile-chev{ color:var(--purple); }
.glow-fd-tile.is-brochure{ background:#ffe9db; border-color:#fcd6bd; }
.glow-fd-tile.is-brochure .glow-fd-tile-icon{ background:var(--orange); }
.glow-fd-tile.is-brochure strong,.glow-fd-tile.is-brochure .glow-fd-tile-chev{ color:#e05f07; }
.glow-fd-tile.is-reports{ background:#fdf3d3; border-color:#f7e4a8; }
.glow-fd-tile.is-reports .glow-fd-tile-icon{ background:var(--amber); }
.glow-fd-tile.is-reports strong,.glow-fd-tile.is-reports .glow-fd-tile-chev{ color:#a86f00; }

/* drawer */
.glow-fd-drawer{ padding:20px 24px 24px; }
.glow-fd-brochure-links{ display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin:16px 0 12px; }
.glow-fd-brochure-links a{ display:block; padding:18px; border:1px solid var(--line); border-radius:14px; font-size:15.5px; font-weight:600; color:var(--ink) !important; }
.glow-fd-brochure-links a:hover{ border-color:#f9cfe0; background:#fff6fa; }
.glow-fd-brochure-links span{ display:block; margin-top:6px; font-size:13.5px; font-weight:700; color:var(--pink); }
.glow-fd-brochure-total{ margin:0; font-size:14.5px; color:#5b5972; }

/* ---------- lower grid ---------- */
.glow-fd-lower{ display:grid; grid-template-columns:1fr 330px; gap:18px; align-items:stretch; }
.glow-fd-lower-main{ display:flex; flex-direction:column; gap:18px; min-width:0; }
.glow-fd-lower-side{ display:flex; flex-direction:column; gap:18px; }

/* checklist */
.glow-fd-checklist{ display:flex; align-items:center; gap:26px; }
.glow-fd-checklist-intro{ flex:0 0 auto; max-width:250px; }
.glow-fd-checklist-intro p{ margin:6px 0 0 35px; font-size:13.5px; color:var(--muted); }
.glow-fd-steps{ flex:1 1 auto; position:relative; display:grid; grid-template-columns:repeat(4,1fr); gap:10px; }
.glow-fd-steps::before{ content:""; position:absolute; left:12%; right:12%; top:23px; height:3px; border-radius:3px; background:#e7e5ef; }
.glow-fd-step{ position:relative; display:flex; flex-direction:column; align-items:center; gap:8px; text-align:center; padding:6px 8px 10px; border-radius:14px; }
.glow-fd-step.is-current{ background:#ffe9f2; }
.glow-fd-step-num{ width:46px; height:46px; border-radius:50%; display:grid; place-items:center; font-size:19px; font-weight:800; background:#e2e0ea; color:#8d8aa0; position:relative; z-index:2; }
.glow-fd-step.is-done .glow-fd-step-num{ background:var(--teal); color:#fff; box-shadow:0 4px 12px rgba(23,185,138,.4); }
.glow-fd-step.is-current .glow-fd-step-num{ background:var(--pink); color:#fff; box-shadow:0 4px 14px rgba(236,15,99,.45); }
.glow-fd-step-num span{ color:inherit; }
.glow-fd-step-label{ font-size:13.5px; line-height:1.3; color:#3d3b52; font-weight:600; max-width:110px; }
.glow-fd-step-check{ width:20px; height:20px; border-radius:50%; display:grid; place-items:center; background:#d7d5de; color:#fff; }
.glow-fd-step.is-done .glow-fd-step-check{ background:var(--teal); }
.glow-fd-step-badge{ display:inline-block; padding:6px 16px; border-radius:999px; background:linear-gradient(180deg,var(--pink-hi),var(--pink)); color:#fff !important; font-size:12px; font-weight:800; letter-spacing:.05em; box-shadow:0 5px 14px rgba(236,15,99,.4); }

/* snapshot */
.glow-fd-fullreport{ margin-left:auto; font-size:14px; font-weight:700; color:#2f4fd8 !important; text-decoration:underline; white-space:nowrap; }
.glow-fd-snap-grid{ display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-top:16px; }
.glow-fd-snap{ display:flex; align-items:center; gap:12px; padding:14px; border-radius:14px; }
.glow-fd-snap.is-pink{ background:#ffe9f1; }  .glow-fd-snap.is-pink  .glow-fd-snap-icon{ background:var(--pink); }
.glow-fd-snap.is-blue{ background:#e5effc; }  .glow-fd-snap.is-blue  .glow-fd-snap-icon{ background:var(--blue); }
.glow-fd-snap.is-green{ background:#e2f6ec; } .glow-fd-snap.is-green .glow-fd-snap-icon{ background:var(--teal); }
.glow-fd-snap.is-purple{ background:#efeafc; }.glow-fd-snap.is-purple .glow-fd-snap-icon{ background:var(--purple); }
.glow-fd-snap-icon{ flex:0 0 42px; width:42px; height:42px; border-radius:50%; display:grid; place-items:center; color:#fff; font-size:19px; font-weight:800; }
.glow-fd-snap b{ display:block; font-size:21px; font-weight:800; letter-spacing:-.02em; line-height:1.1; }
.glow-fd-snap b.is-name{ font-size:17px; color:#4a3fd0; }
.glow-fd-snap span{ display:flex;align-items: center; justify-content: center; font-size:12.5px; line-height:1.3; color:#5f5d75; font-weight:500; }
.glow-fd-snap span.glow-fd-snap-icon{
   color:#fff;
}
.glow-fd-snap-top{ font-size:12px !important; font-weight:700 !important; color:#8d8aa0 !important; }

/* tools */
.glow-fd-tools-grid{ display:grid; grid-template-columns:repeat(6,1fr); gap:10px; margin-top:16px; }
.glow-fd-tools-grid a{ display:flex; flex-direction:column; align-items:center; gap:8px; padding:14px 8px; border:1px solid var(--line); border-radius:14px; text-align:center; font-size:12.5px; line-height:1.3; font-weight:600; color:#3d3b52 !important; }
.glow-fd-tools-grid a:hover{ border-color:#f0c8da; background:#fff8fb; }
.glow-fd-tool-ic{ width:38px; height:38px; border-radius:12px; display:grid; place-items:center; }
.glow-fd-tool-ic.is-pink{ background:var(--pink); }
.glow-fd-tool-ic.is-orange{ background:var(--orange); }
.glow-fd-tool-ic.is-purple{ background:var(--purple); }
.glow-fd-tool-ic.is-blue{ background:var(--blue); }
.glow-fd-tool-ic.is-amber{ background:var(--amber); }
.glow-fd-tool-ic.is-teal{ background:var(--teal); }

/* thank you */
.glow-fd-thanks{ position:relative; flex:1 1 auto; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center;
	background:linear-gradient(165deg,#fff2f7,#ffe3ee 55%,#ffd9e8); border-color:#f9cfdd; padding:34px 26px 44px; overflow:hidden; }
.glow-fd-thanks-script .a{ display:block; font-family:'Caveat',cursive; font-weight:700; font-size:58px; line-height:.9; color:#1a1330; transform:rotate(-3deg); }
.glow-fd-thanks-script .b{ display:block; font-family:'Caveat',cursive; font-weight:700; font-size:34px; margin-top:6px; color:var(--pink); transform:rotate(-3deg); }
.glow-fd-thanks p{ margin:20px 0 0; font-size:15.5px; line-height:1.55; color:#5b4a5c; font-weight:500; max-width:240px; }
.glow-fd-th-heart{ position:absolute; color:#ff5f95; }
.glow-fd-th-heart.h1{ left:14px; top:16px; font-size:15px; transform:rotate(-16deg); }
.glow-fd-th-heart.h2{ right:20px; top:26px; font-size:20px; transform:rotate(14deg); }
.glow-fd-th-heart.h3{ right:34px; bottom:70px; font-size:13px; transform:rotate(20deg); }
.glow-fd-th-heart.h4{ left:26px; bottom:90px; font-size:17px; transform:rotate(-12deg); }
.glow-fd-confetti{ position:absolute; width:14px; height:3.5px; border-radius:3px; }

/* settings */
.glow-fd-settings .glow-fd-card-head{ margin-bottom:10px; }
.glow-fd-setrow{ display:flex; align-items:center; justify-content:space-between; gap:10px; width:100%; padding:13px 14px; margin-top:8px;
	border:1px solid var(--line); border-radius:12px; font-size:14.5px; font-weight:600; color:#3d3b52 !important; text-align:left; }
.glow-fd-setrow:hover{ border-color:#d9d3fb; background:#f7f5ff; }
.glow-fd-setrow.is-danger{ color:#d5325b !important; }
.glow-fd-setrow.is-danger:hover{ border-color:#f6b8c8; background:#fff5f8; }

/* permissions */
.glow-fd-toggle{ margin-left:auto; min-width:104px; padding:7px 18px; border-radius:999px; text-align:center; font-size:12.5px; font-weight:700; letter-spacing:.4px; color:#fff !important; }
.glow-fd-toggle.is-on{ background:var(--teal); }
.glow-fd-toggle.is-off{ background:#9b9b9b; }
.glow-fd-toggle:hover{ opacity:.88; }
.glow-fd-perms{ margin-top:8px; max-height:320px; overflow-y:auto; }
.glow-fd-perm-row{ display:flex; align-items:center; gap:14px; padding:11px 4px; border-top:1px solid #f0eff4; font-size:15.5px; }
.glow-fd-perm-row .glow-fd-toggle{ min-width:96px; }

/* tables */
.glow-fd-table-wrap{ margin-top:14px; overflow-x:auto; }
.glow-fd-table{ width:100%; border-collapse:collapse; font-size:14.5px; }
.glow-fd-table th{ padding:10px 12px; text-align:left; font-size:12px; font-weight:700; letter-spacing:.04em; text-transform:uppercase; color:#8d8aa0; border-bottom:1px solid var(--line); white-space:nowrap; }
.glow-fd-table td{ padding:12px; border-bottom:1px solid #f0eff4; vertical-align:middle; }
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
	border:1px solid #e8e8f0;
	border-radius: 20px;
    box-shadow: 0 2px 6px rgba(22, 20, 50, .05);
    padding: 20px 22px;
	overflow:hidden;
	font-family:'Proxima Nova',Georgia,sans-serif;
}

.glow-sbo-head{
	padding:18px 0px;
	background:#fff;
	border-bottom:1px solid #e8e8f0;
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
	border-bottom:1px solid #e8e8f0;

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

/* seller brochure order editor (outside .glow-fd) */
.glow-modal{ display:none; position:fixed; inset:0; background:rgba(10,12,30,.6); z-index:99999; }
.glow-modal.show{ display:block; }
.glow-modal-box{ position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); background:#fff; padding:30px 26px; width:calc(100% - 32px); max-width:640px; border-radius:16px; text-align:left; max-height:88vh; overflow-y:auto; box-shadow:0 24px 60px rgba(0,0,0,.3); font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif; }
.glow-modal-box h3{ margin:0 0 18px; font-size:20px; font-weight:700; color:#16152b; text-align:center; }
.glow-modal-btns{ display:flex; flex-direction:column; gap:10px; align-items:center; margin-top:6px; }
.glow-done-btn{ background:#0fa47a; color:#fff; border:0; border-radius:30px; padding:12px 30px; font-size:13px; font-weight:700; letter-spacing:.5px; cursor:pointer; min-width:190px; }
.glow-done-btn:hover{ filter:brightness(1.06); }
.glow-done-btn:disabled{ background:#d5d5d5; cursor:not-allowed; filter:none; }
.glow-cart-btn{ background:#6236e0; }
.glow-cancel{ background:none; border:0; color:#fff !important; cursor:pointer; font-size:13px; text-decoration:underline; padding:4px; }
.page-template-fundraiser-dashboard-new table td, .page-template-fundraiser-dashboard-new table th{
	border: 0px solid hsla(0, 0%, 50%, .502);
}
.glow-name-label{ display:block; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.4px; color:#8d8aa0; margin-bottom:6px; }
.glow-name-input{ width:100%; height:42px; border:1px solid #e2e0ea; border-radius:8px; padding:0 12px; font-size:15px; margin:0; box-sizing:border-box; font-family:inherit; }
.glow-name-input:focus{ border-color:#0fa47a; outline:0; }
.glow-e-items{ margin:14px 0; border:1px solid #eceaf1; border-radius:8px; max-height:280px; overflow-y:auto; }
.glow-e-row{ display:flex; align-items:center; gap:10px; padding:9px 10px; border-bottom:1px solid #f3f2f5; font-size:13px; }
.glow-e-row:last-child{ border-bottom:0; }
.glow-e-sku{ flex:0 0 80px; font-weight:700; color:#8d8aa0; font-size:11px; }
.glow-e-row .glow-e-name{ flex:1 1 0; min-width:0; color:#3d3b52; line-height:1.35; }
.glow-e-qty{ flex:0 0 62px; height:32px; text-align:center; border:1px solid #e2e0ea; border-radius:6px; margin:0; padding:4px !important; font-size:14px; box-sizing:border-box; }
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
@media(max-width:1420px){
	.glow-fd-row1{ grid-template-columns:.9fr 1.4fr; }
	.glow-fd-row1 .glow-fd-share{ grid-column:1/-1; }
	.glow-fd-row2{ grid-template-columns:1fr 1fr; }
	.glow-fd-snap-grid{ grid-template-columns:1fr 1fr; }
	.glow-fd-tools-grid{ grid-template-columns:repeat(3,1fr); }
}
@media(max-width:1120px){
	.glow-fd-slogan .glow-fd-bars{ display:none; }
	.glow-fd-lower{ grid-template-columns:1fr; }
	.glow-fd-lower-side{ flex-direction:row; }
	.glow-fd-lower-side > *{ flex:1 1 0; }
	.glow-fd-checklist{ flex-direction:column; align-items:flex-start; gap:16px; }
	.glow-fd-checklist-intro{ max-width:none; }
	.glow-fd-steps{ width:100%; }
}
@media(max-width:820px){
	.glow-fd-top{ flex-wrap:wrap; gap:16px; padding:20px; }
	.glow-fd-top-rule{ display:none; }
	.glow-fd-slogan{ justify-content:center; width:100%; }
	.glow-fd-board{ padding:14px 12px 24px; gap:14px; }
	.glow-fd-row{ gap:14px; grid-template-columns:1fr !important; }
	.glow-fd-row1 .glow-fd-share{ grid-column:auto; }
	.glow-fd-progress-body{ flex-direction:column; }
	.glow-fd-raised{ width:100%; text-align:center; }
	.glow-fd-bar-goal{ text-align:center; }
	.glow-fd-share-grid2{ flex-direction:column; }
	.glow-fd-qr-wrap{ flex-direction:row; justify-content:center; }
	.glow-fd-share-btns{ grid-template-columns:1fr; }
	.glow-fd-steps{ grid-template-columns:1fr 1fr; }
	.glow-fd-steps::before{ display:none; }
	.glow-fd-snap-grid{ grid-template-columns:1fr; }
	.glow-fd-tools-grid{ grid-template-columns:1fr 1fr; }
	.glow-fd-lower-side{ flex-direction:column; }
	.glow-fd-brochure-links{ grid-template-columns:1fr; }
}
@media(prefers-reduced-motion:reduce){ .glow-fd *{ transition-duration:.001ms !important; } }
</style>

<?php get_footer(); ?>