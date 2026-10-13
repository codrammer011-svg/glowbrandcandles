<?php
/**
 * Plugin Name: GLOW - Resend Seller Emails
 * Description: Re-sends the seller registration ("Seller Registered") email to sellers who registered during an email outage. Users → Resend Seller Emails.
 * Version:     1.5.0
 * Author:      Saad Ahmed
 * Author URI:  mailto:saad@flywebsolutions.com
 *
 * Made by Saad Ahmed — saad@flywebsolutions.com
 *
 * Why this exists: the seller welcome email (QR code, seller link, login info) is
 * sent once, at registration, by the theme's send_welcome_email_to_new_user().
 * Nothing records whether it was delivered, so when the SMTP server was down those
 * sellers never got it. This tool:
 *   1. Lists sellers registered in a date range.
 *   2. Flags the ones whose email failed, by matching their registration time to the
 *      WP Mail SMTP "Debug Events" error log (wp_wpmailsmtp_debug_events).
 *   3. Rebuilds the same email from the seller's saved data and sends it, in small
 *      batches, recording each resend on the seller's account.
 *
 * The original email showed the seller's plain-text password, taken from the form
 * post. WordPress only stores a hash, so the resend links to password reset instead.
 */

defined( 'ABSPATH' ) || exit;

// Base used for the seller link. Matches the live site's original email (and the
// URL encoded in each seller's existing QR code). Override in wp-config.php if needed.
if ( ! defined( 'GLOW_SER_SELLER_BASE' ) ) {
	define( 'GLOW_SER_SELLER_BASE', 'https://glowscentedcandles.com' );
}

const GLOW_SER_RESENT_META = '_glow_seller_email_resent';
const GLOW_SER_NONCE       = 'glow_ser';
const GLOW_SER_MAX_ROWS    = 1500;

// Background sending.
const GLOW_SER_PER_MINUTE     = 4;
const GLOW_SER_BG_HOOK        = 'glow_ser_bg_send';
const GLOW_SER_BG_GROUP       = 'glow-seller-email-resend';
const GLOW_SER_BG_OPTION      = 'glow_ser_bg_stats';
const GLOW_SER_BG_QUEUED_META = '_glow_seller_email_queued';
const GLOW_SER_BG_ERROR_META  = '_glow_seller_email_bg_failed';

add_action( 'admin_menu', function () {
	add_users_page( 'Resend Seller Emails', 'Resend Seller Emails', 'manage_options', 'glow-resend-seller-emails', 'glow_ser_render_page' );
} );

/* -------------------------------------------------------------------------
 * Data
 * ---------------------------------------------------------------------- */

/** First non-empty user meta value from a list of keys. */
function glow_ser_meta( $user_id, array $keys ) {
	foreach ( $keys as $key ) {
		$v = trim( (string) get_user_meta( $user_id, $key, true ) );
		if ( '' !== $v ) {
			return $v;
		}
	}
	return '';
}

/** Everything the email needs about one seller. */
function glow_ser_seller_data( WP_User $user ) {
	$uid  = $user->ID;
	$fid  = preg_replace( '/\D/', '', glow_ser_meta( $uid, array( 'user_registration_seller_fundraiser', 'seller_fundraiser', 'fundraiser_id' ) ) );
	$org  = $fid ? trim( (string) get_user_meta( (int) $fid, 'user_registration_name_of_school_business', true ) ) : '';
	$name = glow_ser_meta( $uid, array( 'first_name' ) );

	return array(
		'id'          => $uid,
		'login'       => $user->user_login,
		'email'       => $user->user_email,
		'first_name'  => '' !== $name ? $name : $user->display_name,
		'full_name'   => trim( glow_ser_meta( $uid, array( 'first_name' ) ) . ' ' . glow_ser_meta( $uid, array( 'last_name' ) ) ),
		'fid'         => $fid,
		'org'         => $org,
		'goal'        => glow_ser_meta( $uid, array( 'user_registration_seller_goal', 'seller_goal' ) ),
		'seller_link' => untrailingslashit( GLOW_SER_SELLER_BASE ) . '/seller/user/' . $user->user_login,
		'registered'  => $user->user_registered, // raw; see glow_ser_registered_ts()
	);
}

/* -------------------------------------------------------------------------
 * Duplicates
 *
 * Many sellers registered more than once when their email didn't arrive. A
 * "person" is the same first + last name (letters only, case-insensitive) under
 * the same fundraiser. Only one account per person — the primary — is emailed:
 *   1. the account with the most orders credited to it (wc_seller_field);
 *   2. then the best email: personal > school address > obvious typo;
 *   3. then the most recently registered.
 * ---------------------------------------------------------------------- */

function glow_ser_name_key( $user_id ) {
	$first = (string) get_user_meta( $user_id, 'first_name', true );
	$last  = (string) get_user_meta( $user_id, 'last_name', true );
	return strtolower( preg_replace( '/[^a-z]/i', '', $first . $last ) );
}

/** 2 = personal, 1 = school (filters often block mail), 0 = obvious typo. */
function glow_ser_email_quality( $email ) {
	$domain = strtolower( (string) substr( strrchr( (string) $email, '@' ), 1 ) );
	$typos  = array( 'gmial.com', 'gmai.com', 'gmal.com', 'gamil.com', 'gmail.co', 'hotmial.com', 'hotmal.com', 'yaho.com', 'yahooo.com', 'icloud.co', 'warwickschools.com' );
	if ( '' === $domain || in_array( $domain, $typos, true ) || preg_match( '/\.(con|cmo|comm|om)$/', $domain ) ) {
		return 0;
	}
	if ( preg_match( '/school|\.edu$|k12/', $domain ) ) {
		return 1;
	}
	return 2;
}

/** Orders credited to each seller ID (classic order storage and HPOS). */
function glow_ser_order_counts( array $ids ) {
	global $wpdb;
	$ids = array_values( array_filter( array_map( 'strval', array_map( 'intval', $ids ) ) ) );
	if ( ! $ids ) {
		return array();
	}
	$in     = implode( ',', array_fill( 0, count( $ids ), '%s' ) );
	$counts = array();
	$tables = array( $wpdb->postmeta => 'post_id' );
	$hpos   = $wpdb->prefix . 'wc_orders_meta';
	if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $hpos ) ) === $hpos ) {
		$tables[ $hpos ] = 'order_id';
	}
	foreach ( $tables as $table => $col ) {
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$rows = $wpdb->get_results( $wpdb->prepare( "SELECT meta_value AS sid, COUNT(DISTINCT {$col}) AS n FROM {$table} WHERE meta_key = 'wc_seller_field' AND meta_value IN ($in) GROUP BY meta_value", $ids ) );
		foreach ( $rows as $r ) {
			$counts[ (int) $r->sid ] = max( isset( $counts[ (int) $r->sid ] ) ? $counts[ (int) $r->sid ] : 0, (int) $r->n );
		}
	}
	return $counts;
}

/**
 * Duplicate info for a seller: [ 'primary' => WP_User, 'accounts' => WP_User[] ].
 * Looks across every seller of the same fundraiser (not just the chosen dates),
 * so an older account still counts. Cached per fundraiser for the request.
 */
function glow_ser_dup_info( WP_User $user ) {
	static $groups = array();

	$fid  = preg_replace( '/\D/', '', glow_ser_meta( $user->ID, array( 'user_registration_seller_fundraiser', 'seller_fundraiser', 'fundraiser_id' ) ) );
	$name = glow_ser_name_key( $user->ID );
	if ( '' === $fid || '' === $name ) {
		return array( 'primary' => $user, 'accounts' => array( $user ) );
	}

	if ( ! isset( $groups[ $fid ] ) ) {
		$sellers = get_users( array(
			'role'       => 'seller',
			'number'     => -1,
			'meta_query' => array(
				'relation' => 'OR',
				array( 'key' => 'user_registration_seller_fundraiser', 'value' => $fid ),
				array( 'key' => 'seller_fundraiser', 'value' => $fid ),
			),
		) );
		$by_name = array();
		foreach ( $sellers as $s ) {
			$by_name[ glow_ser_name_key( $s->ID ) ][] = $s;
		}
		$groups[ $fid ] = array();
		foreach ( $by_name as $key => $accounts ) {
			if ( '' === $key ) {
				continue;
			}
			if ( count( $accounts ) > 1 ) {
				$orders = glow_ser_order_counts( wp_list_pluck( $accounts, 'ID' ) );
				usort( $accounts, function ( $a, $b ) use ( $orders ) {
					$oa = isset( $orders[ $a->ID ] ) ? $orders[ $a->ID ] : 0;
					$ob = isset( $orders[ $b->ID ] ) ? $orders[ $b->ID ] : 0;
					if ( $oa !== $ob ) {
						return $ob - $oa;
					}
					$qa = glow_ser_email_quality( $a->user_email );
					$qb = glow_ser_email_quality( $b->user_email );
					if ( $qa !== $qb ) {
						return $qb - $qa;
					}
					return strcmp( $b->user_registered, $a->user_registered ) ?: $b->ID - $a->ID;
				} );
			}
			$groups[ $fid ][ $key ] = $accounts;
		}
	}

	$accounts = isset( $groups[ $fid ][ $name ] ) ? $groups[ $fid ][ $name ] : array( $user );
	return array( 'primary' => $accounts[0], 'accounts' => $accounts );
}

/** True when this account is a duplicate (i.e. not its person's primary). */
function glow_ser_is_duplicate( WP_User $user ) {
	$info = glow_ser_dup_info( $user );
	return $info['primary']->ID !== $user->ID;
}

/**
 * QR code URL for the seller. Reuses the file created at registration (it is
 * generated before the email is sent, so it exists even when the send failed);
 * otherwise generates it the same way the original did.
 */
function glow_ser_qr_url( array $s ) {
	$upload = wp_upload_dir();
	$file   = trailingslashit( $upload['basedir'] ) . 'qrcodes/image_' . $s['id'] . '.png';
	// Live's upload URL is http://; serve the image over https so mail clients don't block it.
	$url    = set_url_scheme( trailingslashit( $upload['baseurl'] ) . 'qrcodes/image_' . $s['id'] . '.png', 'https' );

	if ( is_file( $file ) ) {
		return $url;
	}

	$libs = array( get_template_directory() . '/phpqrcode/qrlib.php' );
	if ( defined( 'GLOW_FC_DIR' ) ) {
		$libs[] = GLOW_FC_DIR . 'includes/phpqrcode/qrlib.php';
	}
	try {
		foreach ( $libs as $lib ) {
			if ( is_file( $lib ) ) {
				require_once $lib;
				break;
			}
		}
		if ( class_exists( 'QRcode' ) ) {
			wp_mkdir_p( dirname( $file ) );
			QRcode::png( $s['seller_link'], $file );
			if ( is_file( $file ) ) {
				update_user_meta( $s['id'], 'seller_barcode_link', $url );
				return $url;
			}
		}
	} catch ( \Throwable $e ) {
		error_log( '[glow-ser] QR generation failed for user ' . $s['id'] . ': ' . $e->getMessage() );
	}
	return '';
}

/**
 * UTC timestamps (unix) of failed seller-welcome sends between two UTC datetimes,
 * from WP Mail SMTP's debug events. Returns null when that table is unavailable.
 *
 * Excluded: "at least one recipient" (the theme always tries one empty address) and
 * "following recipients failed" (a single bad address, not an outage) — neither
 * means the seller's own copy failed.
 */
function glow_ser_failed_send_times( $from_utc, $to_utc ) {
	global $wpdb;
	$table = $wpdb->prefix . 'wpmailsmtp_debug_events';
	if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) ) !== $table ) {
		return null;
	}
	$rows = $wpdb->get_col( $wpdb->prepare(
		"SELECT created_at FROM `{$table}`
		 WHERE event_type = 0
		   AND created_at BETWEEN %s AND %s
		   AND initiator LIKE %s
		   AND content NOT LIKE %s
		   AND content NOT LIKE %s",
		$from_utc,
		$to_utc,
		'%' . $wpdb->esc_like( 'send_welcome_email_to_new_user' ) . '%',
		'%' . $wpdb->esc_like( 'at least one recipient' ) . '%',
		'%' . $wpdb->esc_like( 'following recipients failed' ) . '%'
	) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	$times = array();
	foreach ( $rows as $r ) {
		$times[] = strtotime( $r . ' UTC' );
	}
	sort( $times );
	return $times;
}

/**
 * Is users.user_registered stored in the site's local time instead of UTC?
 *
 * WordPress stores it in UTC, but on the live site the registration flow saves
 * local time (UTC-6): a seller shown as registered at 2:41 pm has their emails
 * logged at 2:41 pm local. Rather than assume, detect it: for the latest
 * sellers, see which reading of user_registered lines up with the email attempts
 * WP Mail SMTP logged at registration. Cached for a day.
 */
function glow_ser_registered_is_local() {
	static $is_local = null;
	if ( null !== $is_local ) {
		return $is_local;
	}
	$cached = get_transient( 'glow_ser_reg_is_local' );
	if ( false !== $cached ) {
		return $is_local = ( 'yes' === $cached );
	}

	global $wpdb;
	$offset   = (int) round( (float) get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
	$is_local = false;
	$table    = $wpdb->prefix . 'wpmailsmtp_debug_events';

	if ( 0 !== $offset && $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) ) === $table ) {
		$sellers = get_users( array( 'role' => 'seller', 'number' => 30, 'orderby' => 'registered', 'order' => 'DESC', 'fields' => array( 'user_registered' ) ) );
		$regs    = array_map( function ( $u ) { return strtotime( $u->user_registered . ' UTC' ); }, $sellers );
		if ( $regs ) {
			$pad  = abs( $offset ) + HOUR_IN_SECONDS;
			$rows = $wpdb->get_col( $wpdb->prepare(
				"SELECT created_at FROM `{$table}` WHERE created_at BETWEEN %s AND %s AND ( initiator LIKE %s OR initiator LIKE %s )",
				gmdate( 'Y-m-d H:i:s', min( $regs ) - $pad ),
				gmdate( 'Y-m-d H:i:s', max( $regs ) + $pad ),
				'%' . $wpdb->esc_like( 'send_welcome_email_to_new_user' ) . '%',
				'%' . $wpdb->esc_like( 'user-registration' ) . '%'
			) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$events = array_map( function ( $r ) { return strtotime( $r . ' UTC' ); }, $rows );
			sort( $events );
			$hits_utc   = 0;
			$hits_local = 0;
			foreach ( $regs as $t ) {
				$hits_utc   += glow_ser_has_failure( $t, $events ) ? 1 : 0;
				$hits_local += glow_ser_has_failure( $t - $offset, $events ) ? 1 : 0;
			}
			$is_local = $hits_local > $hits_utc;
		}
	}

	set_transient( 'glow_ser_reg_is_local', $is_local ? 'yes' : 'no', DAY_IN_SECONDS );
	return $is_local;
}

/** Real (UTC) unix timestamp of a user_registered value. */
function glow_ser_registered_ts( $registered ) {
	$t = strtotime( $registered . ' UTC' );
	return glow_ser_registered_is_local() ? $t - (int) round( (float) get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) : $t;
}

/** Did a failed send happen in the moments right after this registration (unix UTC)? */
function glow_ser_has_failure( $t, array $times ) {
	foreach ( $times as $ft ) {
		if ( $ft >= $t - 10 && $ft <= $t + 120 ) {
			return true;
		}
		if ( $ft > $t + 120 ) {
			break;
		}
	}
	return false;
}

/* -------------------------------------------------------------------------
 * Email
 * ---------------------------------------------------------------------- */

function glow_ser_build_email( WP_User $user, $outage_note = true ) {
	$s       = glow_ser_seller_data( $user );
	$qr      = glow_ser_qr_url( $s );
	$link    = $s['seller_link'];
	$account = home_url( '/my-account/' );
	$reset   = wp_lostpassword_url();
	$e       = 'esc_html';

	ob_start();
	?>
<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;background:#f6f6f6;width:100%;padding:0;margin:0;">
  <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#ffffff" style="max-width:900px;margin:40px auto;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <tr>
      <td align="center" style="background:#333;padding:30px;">
        <h1 style="color:#ffffff;font-size:28px;font-weight:400;margin:0;text-align:center;">💛 Welcome to Your Glow Fundraiser, <?php echo $e( $s['first_name'] ); ?>!</h1>
      </td>
    </tr>
    <tr>
      <td align="left" style="padding:25px 40px 0;">
        <?php if ( $outage_note ) : ?>
        <p style="background:#fff8e1;border:1px solid #ffe08a;border-radius:8px;padding:12px 15px;color:#5c4b00;font-size:14px;margin:0 0 10px;">📬 We’re re-sending your seller registration email because our email system had a temporary outage when you signed up. If you already received it, no action is needed.</p>
        <?php endif; ?>
        <p style="color:#333;font-size:16px;margin-top:10px;">⚠️ Don’t hit delete! 💌 This email has everything you need for your fundraiser &amp; seller dashboard — your key to glowing success! ✨</p>
      </td>
    </tr>
    <tr>
      <td style="padding:30px 40px;padding-top:10px;color:#333;line-height:1.6;font-size:15px;">
        <p>Thank you for registering as a <b>Glow Scented Candles Seller</b><?php if ( '' !== $s['org'] ) : ?> and supporting <b><?php echo $e( $s['org'] ); ?></b><?php endif; ?>! 🌙<br>
        You’re helping your team shine bright while earning profits through beautiful candles, room sprays, wax melts, and essential oils.</p>

        <hr style="border:0; border-top:1px double #edeae9; width:100%; margin:20px auto;">

        <h3 style="color:#333;margin-top:30px;">🔍 Your Seller Details</h3>
        <?php if ( $qr ) : ?>
          <p><b>📸 Profile QR Code:</b><br><img src="<?php echo esc_url( $qr ); ?>" alt="Profile QR Code" style="width:110px;border-radius:6px;margin:10px 0;"></p>
        <?php endif; ?>
        <p><b>🔗 Your Seller Link:</b> <a href="<?php echo esc_url( $link ); ?>" target="_blank" style="color:#4184f3;text-decoration:none;"><?php echo $e( $link ); ?></a></p>
        <p><b>📧 Email:</b> <?php echo $e( $s['email'] ); ?></p>
        <p><b>🔑 Password:</b> the password you created when you registered (<a href="<?php echo esc_url( $reset ); ?>" target="_blank" style="color:#4184f3;">forgot it? reset it here</a>)</p>
        <?php if ( '' !== $s['fid'] ) : ?><p><b>🪪 Fundraiser ID:</b> <?php echo $e( $s['fid'] ); ?></p><?php endif; ?>
        <?php if ( '' !== $s['org'] ) : ?><p><b>🎉 Group Name:</b> <?php echo $e( $s['org'] ); ?></p><?php endif; ?>
        <?php if ( '' !== $s['goal'] ) : ?><p><b>💵 Seller Goal:</b> $<?php echo $e( ltrim( $s['goal'], '$' ) ); ?></p><?php endif; ?>

        <hr style="border:0; border-top:1px double #edeae9; width:100%; margin:20px auto;">

        <h3 style="margin-top:35px;">🚀 Let’s Get Started!</h3>
        <p>🖥️ Log in to your Seller Dashboard.</p>
        <p><b>👉 My Account Login:</b> <a href="<?php echo esc_url( $account ); ?>" target="_blank" style="color:#4184f3;">Access Dashboard</a></p>
        <p>Here, you’ll see real-time updates of your sales and success!</p>

        <h4 style="margin-top:25px;">✅ Start Sharing Your Link Right Away</h4>
        <ol style="padding-left:20px;">
          <li>Copy your Seller Link: <a href="<?php echo esc_url( $link ); ?>" target="_blank" style="color:#4184f3;"><?php echo $e( $link ); ?></a></li>
          <li>Share your link via email, text, and social media — include why you’re fundraising, your goal, and a photo/logo.</li>
        </ol>
        <p style="font-style:italic;">💡 The more you share, the more you sell!</p>
        <p style="font-style:italic;">📲 Spread the Glow! Tap to share on Facebook, Instagram, or copy the link.</p>
        <h4 style="margin-top:25px;">✨ Glow Up Your Fundraiser — One Post at a Time!</h4>
        <h4 style="margin-top:25px;">Ready, set, post! 🚀 These images make promoting your Glow easy &amp; fun 👇</h4>
        <a href="https://www.dropbox.com/scl/fo/2qaa5ldadzp5nnu0l4acn/AF2UoshWPeClkWYJkxHHpAM?rlkey=ex6gonh1c6hvx1ojit5dxu94v&amp;st=ubblc6lz&amp;dl=0" target="_blank" style="color: blue; font-weight:700;font-size:15px;text-align: left;">Download Glow Product Images Here</a>

        <hr style="border:0; border-top:1px double #edeae9; width:100%; margin:20px auto;">

        <h4 style="margin-top:30px;">🌟 Keep Your Momentum Going</h4>
        <ul style="padding-left:20px;">
          <li>📣 Send your link every few days to family &amp; friends:<br/><a href="<?php echo esc_url( $link ); ?>">🔗 <?php echo $e( $link ); ?></a><br/><em>People get busy and forget — friendly reminders keep your fundraiser shining!</em></li>
          <li>📱 Post, text, and email often for maximum visibility and support.</li>
          <li>💛 Remember: Each share is a chance to make a sale and help your cause glow brighter.</li>
        </ul>

        <h4 style="margin-top:30px;">📣 Final Push</h4>
        <ul style="padding-left:20px;">
          <li>Send a “Last Chance to Order!” reminder a few days before your sale ends.</li>
        </ul>

        <hr style="border:0; border-top:1px double #edeae9; width:100%; margin:20px auto;">

        <h4 style="margin-top:30px;">🛍️ Spread the Glow</h4>
        <p style="margin-top:25px;">✨ Candles, Room Sprays, Wax Melts, and Essential Oils make perfect gifts for every occasion!</p>
        <p style="margin-top:25px;">🎁 Invite supporters to shop online for more scents and exclusive items.</p>
        <p style="margin-top:25px;">🚚 Free Shipping on orders $99+, or just $11.95 for smaller orders.</p>
        <p style="margin-top:25px;">📦 Orders ship in 3–5 business days or can ship free to your group at the end of your fundraiser!</p>

        <hr style="border:0; border-top:1px double #edeae9; width:100%; margin:20px auto;">

        <h4 style="margin-top:30px;">💫 You’ve Got This!</h4>
        <p style="margin-top:25px;">We’re so excited to see your success and help your team shine bright!</p>
        <p style="margin-top:25px;">Keep sharing, keep glowing, and remember — every candle sold helps your cause sparkle. ✨</p>
        <p style="color:#555;margin-top:20px;border-top:1px solid #eee;padding-top:15px;">
          💌 <b>Happy Selling,</b><br>
          💛 The Glow Team
        </p>
      </td>
    </tr>
  </table>
</div>
	<?php
	return ob_get_clean();
}

function glow_ser_outage_note_html() {
	return '<p style="margin:10px 0;background:#fff8e1;border:1px solid #ffe08a;border-radius:8px;padding:12px 15px;color:#5c4b00;font-family:Arial,sans-serif;font-size:14px;line-height:1.5;">📬 We’re re-sending your seller registration email because our email system had a temporary outage when you signed up. If you already received it, no action is needed.</p>';
}

/**
 * Put the outage note inside the email card, just under the dark header — the
 * same place the built-in copy shows it. In the seller template the header ends
 * with </h1>, and the next <td> opens the content area ("Don't hit delete!"),
 * so the note goes at the start of that cell. Falls back to the top of <body>
 * (or the very top) if the template's layout ever changes.
 */
function glow_ser_insert_outage_note( $body ) {
	$note = glow_ser_outage_note_html();
	$h1   = stripos( $body, '</h1>' );
	if ( false !== $h1 && preg_match( '/<td\b[^>]*>/i', $body, $m, PREG_OFFSET_CAPTURE, $h1 ) ) {
		$at = $m[0][1] + strlen( $m[0][0] );
		return substr( $body, 0, $at ) . $note . substr( $body, $at );
	}
	$wrapped = '<div style="max-width:900px;margin:20px auto 0;">' . $note . '</div>';
	return preg_match( '/<body[^>]*>/i', $body ) ? preg_replace( '/(<body[^>]*>)/i', '$1' . $wrapped, $body, 1 ) : $wrapped . $body;
}

/**
 * Build the seller email from the site's own template, so any change to the
 * theme's seller registration email is picked up automatically.
 *
 * Runs the theme's send_welcome_email_to_new_user() exactly as a registration
 * would, with the seller's saved details in place of the form post, while
 * intercepting every wp_mail() it makes — nothing it tries to send goes out (it
 * normally also mails the admin addresses). The seller's own copy is kept and
 * cleaned up:
 *   - the plain-text password (not stored anywhere) becomes a reset link;
 *   - the template's hard-coded example link (…/seller/user/tulip1936) becomes
 *     this seller's link.
 * Its only side effect is rewriting the seller's QR image with the same link.
 *
 * Returns [ subject, body, headers ] or null when the theme template isn't
 * available (e.g. a different theme) or didn't produce a seller email.
 */
function glow_ser_theme_email( WP_User $user ) {
	if ( ! function_exists( 'send_welcome_email_to_new_user' ) ) {
		return null;
	}
	$s      = glow_ser_seller_data( $user );
	$marker = 'GLOWSERPASSWORDMARKER';

	// Same field order as the seller registration form posts it ([0] first name,
	// [3] password, [4] fundraiser ID, [5] goal — whose label marks it as a seller).
	$fields = array_fill( 0, 32, array( 'label' => '', 'value' => '' ) );
	$fields[0] = array( 'label' => 'First Name', 'value' => $s['first_name'] );
	$fields[1] = array( 'label' => 'Last Name', 'value' => (string) get_user_meta( $user->ID, 'last_name', true ) );
	$fields[2] = array( 'label' => 'Email', 'value' => $s['email'] );
	$fields[3] = array( 'label' => 'Password', 'value' => $marker );
	$fields[4] = array( 'label' => 'Fundraiser ID', 'value' => $s['fid'] );
	$fields[5] = array( 'label' => 'Seller Goal: (Dollar Amount)', 'value' => $s['goal'] );

	$had_post = array_key_exists( 'form_data', $_POST );
	$old_post = $had_post ? $_POST['form_data'] : null;
	// The theme runs stripslashes() on this, so keep it free of JSON backslash
	// escapes (e.g. ’ in "Jay’shon") and slash it like a real form post.
	$_POST['form_data'] = wp_slash( wp_json_encode( $fields, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) );

	$captured = array();
	$catch    = function ( $return, $atts ) use ( &$captured ) {
		$captured[] = $atts;
		return true; // Short-circuit: nothing is actually sent.
	};
	add_filter( 'pre_wp_mail', $catch, PHP_INT_MAX, 2 );
	ob_start();
	try {
		send_welcome_email_to_new_user( $user->ID );
	} catch ( \Throwable $e ) {
		error_log( '[glow-ser] theme template failed for user ' . $user->ID . ': ' . $e->getMessage() );
	} finally {
		ob_end_clean();
		remove_filter( 'pre_wp_mail', $catch, PHP_INT_MAX );
		if ( $had_post ) {
			$_POST['form_data'] = $old_post;
		} else {
			unset( $_POST['form_data'] );
		}
	}

	// Keep only the copy addressed to the seller.
	$mine = null;
	foreach ( $captured as $atts ) {
		$to = is_array( $atts['to'] ) ? $atts['to'] : explode( ',', (string) $atts['to'] );
		foreach ( $to as $addr ) {
			if ( 0 === strcasecmp( trim( (string) $addr ), $user->user_email ) ) {
				$mine = $atts;
				break 2;
			}
		}
	}
	if ( ! $mine || '' === trim( (string) $mine['message'] ) ) {
		return null;
	}

	$link  = $s['seller_link'];
	$reset = 'the password you created when you registered (<a href="' . esc_url( wp_lostpassword_url() ) . '" target="_blank" style="color:#4184f3;">forgot it? reset it here</a>)';
	$body  = str_replace(
		array( '&pass=' . $marker, '&amp;pass=' . $marker, $marker, 'https://glowscentedcandles.com/seller/user/tulip1936', 'https://glowbrandscandles.com/seller/user/tulip1936' ),
		array( '', '', $reset, $link, $link ),
		(string) $mine['message']
	);

	return array(
		'subject' => (string) $mine['subject'],
		'body'    => $body,
		'headers' => $mine['headers'],
	);
}

/**
 * The email to send: the site's live template when available, otherwise the
 * built-in copy. $subject overrides the template's subject when not empty.
 */
function glow_ser_compose( WP_User $user, $outage_note, $subject = '' ) {
	$theme = glow_ser_theme_email( $user );
	if ( $theme ) {
		$body = $theme['body'];
		if ( $outage_note ) {
			$body = glow_ser_insert_outage_note( $body );
		}
		return array(
			'subject' => '' !== $subject ? $subject : ( '' !== $theme['subject'] ? $theme['subject'] : 'Seller Registered' ),
			'body'    => $body,
			'headers' => $theme['headers'],
			'source'  => 'theme',
		);
	}
	return array(
		'subject' => '' !== $subject ? $subject : 'Seller Registered',
		'body'    => glow_ser_build_email( $user, $outage_note ),
		'headers' => array( 'Content-Type: text/html; charset=UTF-8', 'From: Glow Scented Candles <info@glowscentedcandles.com>' ),
		'source'  => 'built-in',
	);
}

/**
 * Send one seller's email to $to (defaults to the seller).
 * Returns [ 'ok' => bool, 'source' => 'theme'|'built-in' ].
 */
function glow_ser_send( WP_User $user, $subject, $outage_note, $to = '', $subject_prefix = '' ) {
	$mail = glow_ser_compose( $user, $outage_note, $subject );
	$ok   = wp_mail( $to ? $to : $user->user_email, $subject_prefix . $mail['subject'], $mail['body'], $mail['headers'] );
	return array( 'ok' => $ok, 'source' => $mail['source'] );
}

/* -------------------------------------------------------------------------
 * AJAX
 * ---------------------------------------------------------------------- */

function glow_ser_check_ajax() {
	if ( ! current_user_can( 'manage_options' ) || ! check_ajax_referer( GLOW_SER_NONCE, 'nonce', false ) ) {
		wp_send_json_error( 'Not allowed.', 403 );
	}
}

function glow_ser_get_seller( $id ) {
	$user = get_userdata( (int) $id );
	return ( $user && in_array( 'seller', (array) $user->roles, true ) ) ? $user : null;
}

// Preview one email in the browser.
add_action( 'wp_ajax_glow_ser_preview', function () {
	if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( GLOW_SER_NONCE, 'nonce' ) ) {
		wp_die( 'Not allowed.' );
	}
	$user = glow_ser_get_seller( isset( $_GET['user_id'] ) ? $_GET['user_id'] : 0 );
	if ( ! $user ) {
		wp_die( 'Seller not found.' );
	}
	$mail = glow_ser_compose( $user, ! empty( $_GET['note'] ) );
	header( 'Content-Type: text/html; charset=UTF-8' );
	// Admin-only banner above the preview; not part of the email.
	printf(
		'<div style="font:13px Arial,sans-serif;background:#1d2327;color:#fff;padding:8px 12px;">Preview — Subject: <b>%s</b> · Template: <b>%s</b></div>',
		esc_html( $mail['subject'] ),
		'theme' === $mail['source'] ? 'site’s current seller email' : 'built-in copy (site template not available)'
	);
	echo $mail['body']; // phpcs:ignore WordPress.Security.EscapeOutput
	exit;
} );

/**
 * Find a seller for a test send from an ID, username or email. Empty input
 * falls back to the most recently registered seller.
 */
function glow_ser_find_test_seller( $needle ) {
	$needle = trim( (string) $needle );
	if ( '' === $needle ) {
		$latest = get_users( array( 'role' => 'seller', 'number' => 1, 'orderby' => 'registered', 'order' => 'DESC' ) );
		return $latest ? $latest[0] : null;
	}
	$user = ctype_digit( $needle ) ? get_userdata( (int) $needle ) : false;
	if ( ! $user ) {
		$user = get_user_by( is_email( $needle ) ? 'email' : 'login', $needle );
	}
	return ( $user && in_array( 'seller', (array) $user->roles, true ) ) ? $user : null;
}

// Test send: one seller's email to any address. Never recorded as a resend.
add_action( 'wp_ajax_glow_ser_test', function () {
	glow_ser_check_ajax();
	$to      = isset( $_POST['test_to'] ) ? sanitize_email( wp_unslash( $_POST['test_to'] ) ) : '';
	$subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
	$user    = glow_ser_find_test_seller( isset( $_POST['seller'] ) ? wp_unslash( $_POST['seller'] ) : '' );

	if ( ! is_email( $to ) ) {
		wp_send_json_error( 'Enter a valid test email address.' );
	}
	if ( ! $user ) {
		wp_send_json_error( 'No seller found with that ID, username or email.' );
	}

	$res   = glow_ser_send( $user, $subject, ! empty( $_POST['note'] ), $to, '[TEST] ' );
	$label = sprintf( '%s (ID %d)', $user->user_login, $user->ID );
	$src   = 'theme' === $res['source'] ? 'the site’s current seller email template' : 'the built-in copy (site template not available)';
	if ( $res['ok'] ) {
		wp_send_json_success( 'Test email sent to ' . $to . ' using seller ' . $label . ' and ' . $src . '. Check the inbox (and spam folder).' );
	}
	wp_send_json_error( 'The site could not send the test email to ' . $to . '. Check WP Mail SMTP → Tools → Debug Events for the error.' );
} );

/**
 * Why a seller must not be emailed, or '' if they can be: not a seller, a
 * duplicate account, or already re-sent. Shared by manual and background sending.
 */
function glow_ser_skip_reason( $user ) {
	if ( ! $user ) {
		return 'Not a seller';
	}
	if ( glow_ser_is_duplicate( $user ) ) {
		return 'Skipped — duplicate of #' . glow_ser_dup_info( $user )['primary']->ID;
	}
	$already = array_filter( (array) get_user_meta( $user->ID, GLOW_SER_RESENT_META, true ) );
	if ( $already ) {
		$last = end( $already );
		return 'Skipped — already re-sent ' . mysql2date( 'M j, g:i a', $last['at'] );
	}
	return '';
}

/** Record a successful resend on the seller's account. */
function glow_ser_record_resent( $user_id, $by, $background = false ) {
	$log   = array_filter( (array) get_user_meta( $user_id, GLOW_SER_RESENT_META, true ) );
	$log[] = array( 'at' => current_time( 'mysql' ), 'by' => (int) $by, 'background' => (bool) $background );
	update_user_meta( $user_id, GLOW_SER_RESENT_META, $log );
	delete_user_meta( $user_id, GLOW_SER_BG_ERROR_META );
}

// Send a batch (a few at a time, driven by the page's JS).
add_action( 'wp_ajax_glow_ser_send', function () {
	glow_ser_check_ajax();
	$subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
	$note    = ! empty( $_POST['note'] );
	$ids     = isset( $_POST['ids'] ) ? array_slice( array_map( 'intval', (array) $_POST['ids'] ), 0, 10 ) : array();

	$results = array();
	foreach ( $ids as $id ) {
		$user = glow_ser_get_seller( $id );
		$skip = glow_ser_skip_reason( $user );
		if ( '' !== $skip ) {
			$results[ $id ] = array( 'ok' => false, 'msg' => $skip );
			continue;
		}
		$ok = glow_ser_send( $user, $subject, $note )['ok'];
		if ( $ok ) {
			glow_ser_record_resent( $id, get_current_user_id() );
		}
		$results[ $id ] = array( 'ok' => $ok, 'msg' => $ok ? 'Resent ' . current_time( 'M j, g:i a' ) : 'Send failed — check WP Mail SMTP' );
	}
	wp_send_json_success( $results );
} );

/* -------------------------------------------------------------------------
 * Background sending (WooCommerce's Action Scheduler)
 *
 * "Queue in background" schedules one job per seller, spaced GLOW_SER_PER_MINUTE
 * per minute, so the browser can be closed. Action Scheduler runs due jobs about
 * once a minute while the site has traffic (and on admin page loads). If it falls
 * behind, a per-minute limit in the job itself still caps sending at
 * GLOW_SER_PER_MINUTE — extra jobs are pushed to the next minute, never bunched.
 * ---------------------------------------------------------------------- */

function glow_ser_bg_available() {
	return function_exists( 'as_schedule_single_action' ) && function_exists( 'as_get_scheduled_actions' );
}

function glow_ser_bg_stats() {
	return wp_parse_args( (array) get_option( GLOW_SER_BG_OPTION, array() ), array(
		'total'     => 0,
		'sent'      => 0,
		'failed'    => 0,
		'skipped'   => 0,
		'started'   => '',
		'next_slot' => 0,
		'subject'   => '',
		'note'      => true,
		'by'        => 0,
	) );
}

function glow_ser_bg_pending_count() {
	if ( ! glow_ser_bg_available() ) {
		return 0;
	}
	return count( as_get_scheduled_actions( array(
		'hook'     => GLOW_SER_BG_HOOK,
		'status'   => ActionScheduler_Store::STATUS_PENDING,
		'per_page' => -1,
	), 'ids' ) );
}

/** Take one sending slot for the current minute; false if this minute is full. */
function glow_ser_bg_take_slot() {
	$minute = (int) floor( time() / 60 );
	$rate   = (array) get_option( 'glow_ser_bg_rate', array() );
	$count  = ( isset( $rate['m'] ) && (int) $rate['m'] === $minute ) ? (int) $rate['n'] : 0;
	if ( $count >= GLOW_SER_PER_MINUTE ) {
		return false;
	}
	update_option( 'glow_ser_bg_rate', array( 'm' => $minute, 'n' => $count + 1 ), false );
	return true;
}

// The background job: send one seller's email.
add_action( GLOW_SER_BG_HOOK, function ( $user_id ) {
	$user_id = (int) $user_id;

	if ( ! glow_ser_bg_take_slot() ) {
		// This minute's quota is used — try again at the start of the next minute.
		as_schedule_single_action( ( (int) floor( time() / 60 ) + 1 ) * 60 + 2, GLOW_SER_BG_HOOK, array( $user_id ), GLOW_SER_BG_GROUP );
		return;
	}

	$stats = glow_ser_bg_stats();
	$user  = glow_ser_get_seller( $user_id );
	$skip  = glow_ser_skip_reason( $user );

	if ( '' !== $skip ) {
		$stats['skipped']++;
	} else {
		$ok = glow_ser_send( $user, (string) $stats['subject'], (bool) $stats['note'] )['ok'];
		if ( $ok ) {
			glow_ser_record_resent( $user_id, $stats['by'], true );
			$stats['sent']++;
		} else {
			update_user_meta( $user_id, GLOW_SER_BG_ERROR_META, current_time( 'mysql' ) );
			$stats['failed']++;
		}
	}
	delete_user_meta( $user_id, GLOW_SER_BG_QUEUED_META );
	update_option( GLOW_SER_BG_OPTION, $stats, false );
} );

// Queue the selected sellers for background sending.
add_action( 'wp_ajax_glow_ser_bg_queue', function () {
	glow_ser_check_ajax();
	if ( ! glow_ser_bg_available() ) {
		wp_send_json_error( 'Background sending needs WooCommerce (Action Scheduler), which isn’t available.' );
	}
	$ids = isset( $_POST['ids'] ) ? array_slice( array_map( 'intval', (array) $_POST['ids'] ), 0, GLOW_SER_MAX_ROWS ) : array();

	$running = glow_ser_bg_pending_count() > 0;
	$stats   = $running ? glow_ser_bg_stats() : array(
		'total'     => 0,
		'sent'      => 0,
		'failed'    => 0,
		'skipped'   => 0,
		'started'   => current_time( 'mysql' ),
		'next_slot' => 0,
	);
	// Subject / note apply to everything still waiting in the queue.
	$stats['subject'] = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
	$stats['note']    = ! empty( $_POST['note'] );
	$stats['by']      = get_current_user_id();

	$slot   = max( time() + 5, (int) $stats['next_slot'] );
	$queued = 0;
	$skip   = 0;
	foreach ( $ids as $id ) {
		$user = glow_ser_get_seller( $id );
		if ( '' !== glow_ser_skip_reason( $user ) || get_user_meta( $id, GLOW_SER_BG_QUEUED_META, true ) ) {
			$skip++;
			continue;
		}
		// GLOW_SER_PER_MINUTE jobs per minute, spaced evenly within the minute.
		$at = $slot + (int) floor( $queued / GLOW_SER_PER_MINUTE ) * 60 + ( $queued % GLOW_SER_PER_MINUTE ) * (int) floor( 60 / GLOW_SER_PER_MINUTE );
		as_schedule_single_action( $at, GLOW_SER_BG_HOOK, array( $id ), GLOW_SER_BG_GROUP );
		update_user_meta( $id, GLOW_SER_BG_QUEUED_META, current_time( 'mysql' ) );
		delete_user_meta( $id, GLOW_SER_BG_ERROR_META );
		$queued++;
	}
	$stats['total']    += $queued;
	$stats['next_slot'] = $slot + (int) ceil( $queued / GLOW_SER_PER_MINUTE ) * 60;
	update_option( GLOW_SER_BG_OPTION, $stats, false );

	wp_send_json_success( array(
		'queued'  => $queued,
		'skipped' => $skip,
		'minutes' => (int) ceil( glow_ser_bg_pending_count() / GLOW_SER_PER_MINUTE ),
	) );
} );

// Cancel everything still waiting.
add_action( 'wp_ajax_glow_ser_bg_cancel', function () {
	glow_ser_check_ajax();
	if ( glow_ser_bg_available() ) {
		as_unschedule_all_actions( GLOW_SER_BG_HOOK );
	}
	delete_metadata( 'user', 0, GLOW_SER_BG_QUEUED_META, '', true );
	$stats              = glow_ser_bg_stats();
	$stats['next_slot'] = 0;
	update_option( GLOW_SER_BG_OPTION, $stats, false );
	wp_send_json_success( glow_ser_bg_status() );
} );

function glow_ser_bg_status() {
	$stats   = glow_ser_bg_stats();
	$pending = glow_ser_bg_pending_count();
	$next    = ( $pending && function_exists( 'as_next_scheduled_action' ) ) ? as_next_scheduled_action( GLOW_SER_BG_HOOK ) : false;
	return array(
		'pending' => $pending,
		'total'   => (int) $stats['total'],
		'sent'    => (int) $stats['sent'],
		'failed'  => (int) $stats['failed'],
		'skipped' => (int) $stats['skipped'],
		'started' => $stats['started'] ? mysql2date( 'M j, g:i a', $stats['started'] ) : '',
		'next'    => is_int( $next ) ? wp_date( 'g:i:s a', $next ) : '',
		'minutes' => (int) ceil( $pending / GLOW_SER_PER_MINUTE ),
	);
}

add_action( 'wp_ajax_glow_ser_bg_status', function () {
	glow_ser_check_ajax();
	wp_send_json_success( glow_ser_bg_status() );
} );

/* -------------------------------------------------------------------------
 * Admin page
 * ---------------------------------------------------------------------- */

function glow_ser_render_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$tz    = wp_timezone();
	$today = new DateTime( 'now', $tz );
	$from  = isset( $_GET['from'] ) ? sanitize_text_field( wp_unslash( $_GET['from'] ) ) : ( clone $today )->modify( '-7 days' )->format( 'Y-m-d' );
	$to    = isset( $_GET['to'] ) ? sanitize_text_field( wp_unslash( $_GET['to'] ) ) : $today->format( 'Y-m-d' );
	$show  = isset( $_GET['show'] ) ? sanitize_key( $_GET['show'] ) : 'failed_not_resent';
	if ( ! in_array( $show, array( 'failed', 'failed_not_resent', 'not_resent', 'all' ), true ) ) {
		$show = 'failed_not_resent';
	}

	$from_dt = DateTime::createFromFormat( 'Y-m-d H:i:s', $from . ' 00:00:00', $tz );
	$to_dt   = DateTime::createFromFormat( 'Y-m-d H:i:s', $to . ' 23:59:59', $tz );
	$utc     = new DateTimeZone( 'UTC' );

	$rows      = array();
	$error     = '';
	$times     = null;
	$dup_count = 0;
	if ( ! $from_dt || ! $to_dt || $from_dt > $to_dt ) {
		$error = 'Please choose a valid date range.';
	} else {
		$from_local = $from_dt->format( 'Y-m-d H:i:s' );
		$to_local   = $to_dt->format( 'Y-m-d H:i:s' );
		$from_utc   = $from_dt->setTimezone( $utc )->format( 'Y-m-d H:i:s' );
		$to_utc     = $to_dt->setTimezone( $utc )->format( 'Y-m-d H:i:s' );
		// Query user_registered in whatever zone this site stores it in.
		$reg_local  = glow_ser_registered_is_local();
		$users      = get_users( array(
			'role'       => 'seller',
			'number'     => GLOW_SER_MAX_ROWS,
			'orderby'    => 'registered',
			'order'      => 'ASC',
			'date_query' => array( array(
				'column'    => 'user_registered',
				'after'     => $reg_local ? $from_local : $from_utc,
				'before'    => $reg_local ? $to_local : $to_utc,
				'inclusive' => true,
			) ),
		) );
		$times = glow_ser_failed_send_times( $from_utc, gmdate( 'Y-m-d H:i:s', strtotime( $to_utc . ' UTC' ) + 300 ) );

		foreach ( $users as $u ) {
			$s       = glow_ser_seller_data( $u );
			$resent  = array_filter( (array) get_user_meta( $u->ID, GLOW_SER_RESENT_META, true ) );
			$last    = $resent ? end( $resent ) : null;
			$status  = null === $times ? 'unknown' : ( glow_ser_has_failure( glow_ser_registered_ts( $s['registered'] ), $times ) ? 'failed' : 'ok' );
			$only_failed     = in_array( $show, array( 'failed', 'failed_not_resent' ), true );
			$only_not_resent = in_array( $show, array( 'failed_not_resent', 'not_resent' ), true );
			if ( ( $only_failed && 'failed' !== $status ) || ( $only_not_resent && $resent ) ) {
				continue;
			}
			$dup         = glow_ser_dup_info( $u );
			$s['status'] = $status;
			$s['resent'] = $last ? mysql2date( 'M j, g:i a', $last['at'] ) . ( count( $resent ) > 1 ? ' (' . count( $resent ) . '×)' : '' ) : '';
			$s['copies'] = count( $dup['accounts'] );
			$s['is_dup'] = $dup['primary']->ID !== $u->ID;
			$s['primary'] = $dup['primary'];
			$s['queued']  = '' === $s['resent'] && (bool) get_user_meta( $u->ID, GLOW_SER_BG_QUEUED_META, true );
			$bg_failed    = '' === $s['resent'] ? (string) get_user_meta( $u->ID, GLOW_SER_BG_ERROR_META, true ) : '';
			$s['bg_failed'] = '' !== $bg_failed ? mysql2date( 'M j, g:i a', $bg_failed ) : '';
			if ( $last && ! empty( $last['background'] ) ) {
				$s['resent'] .= ' (background)';
			}
			$rows[]      = $s;
			if ( $s['is_dup'] ) {
				$dup_count++;
			}
		}
	}

	$nonce   = wp_create_nonce( GLOW_SER_NONCE );
	$me      = wp_get_current_user();
	$labels  = array(
		'failed'  => '<span style="color:#b32d2e;font-weight:600;">✗ Failed</span>',
		'ok'      => '<span style="color:#2e7d32;">✓ No error logged</span>',
		'unknown' => '<span style="color:#777;">? Unknown</span>',
	);
	?>
	<div class="wrap">
		<h1>Resend Seller Emails</h1>
		<p>Re-sends the seller registration email (QR code, seller link, login info) to sellers who registered in the chosen dates.
		“Failed” means WP Mail SMTP logged a sending error for the welcome email right after that seller registered.</p>

		<?php if ( null === $times && ! $error ) : ?>
			<div class="notice notice-warning"><p>The WP Mail SMTP debug log isn’t available on this site, so failures can’t be detected. Switch to “All sellers” to choose manually.</p></div>
		<?php endif; ?>
		<?php if ( $error ) : ?>
			<div class="notice notice-error"><p><?php echo esc_html( $error ); ?></p></div>
		<?php endif; ?>

		<form method="get" style="margin:15px 0;display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
			<input type="hidden" name="page" value="glow-resend-seller-emails">
			<label>Registered from <input type="date" name="from" value="<?php echo esc_attr( $from ); ?>"></label>
			<label>to <input type="date" name="to" value="<?php echo esc_attr( $to ); ?>"></label>
			<select name="show">
				<option value="failed" <?php selected( $show, 'failed' ); ?>>Only sellers whose email failed</option>
				<option value="failed_not_resent" <?php selected( $show, 'failed_not_resent' ); ?>>Only sellers whose email failed and not re-sent yet</option>
				<option value="not_resent" <?php selected( $show, 'not_resent' ); ?>>All sellers not re-sent yet</option>
				<option value="all" <?php selected( $show, 'all' ); ?>>All sellers in range</option>
			</select>
			<button class="button">Show sellers</button>
		</form>

		<div style="background:#fff;border:1px solid #ccd0d4;padding:12px 15px;margin-bottom:15px;max-width:900px;">
			<h2 style="margin:0 0 10px;font-size:15px;">Email settings</h2>
			<p style="margin:0 0 4px;"><label>Subject: <input type="text" id="glow-ser-subject" value="" placeholder="Same as the site’s seller email (“Seller Registered”)" style="width:380px;"></label></p>
			<p style="margin:0 0 8px;color:#555;">The email body is taken from the site’s current seller registration email, so template changes are picked up automatically. Leave the subject blank to use the template’s subject.</p>
			<p style="margin:0 0 8px;"><label><input type="checkbox" id="glow-ser-note" checked> Include a short note explaining why the email is being re-sent</label></p>
			<p style="margin:0;"><label>Wait <input type="number" id="glow-ser-delay" value="10" min="1" max="300" step="1" style="width:70px;"> seconds between emails</label>
				<span style="color:#555;">— emails go out one at a time so the mail server isn’t flooded. 10 seconds ≈ 360 emails/hour. Keep this page open while sending.</span></p>
		</div>

		<div style="background:#fff;border:1px solid #ccd0d4;border-left:4px solid #2271b1;padding:12px 15px;margin-bottom:15px;max-width:900px;">
			<h2 style="margin:0 0 6px;font-size:15px;">Send a test email</h2>
			<p style="margin:0 0 10px;color:#555;">Sends the seller email to any address you choose, so you can check that email is working and see exactly what sellers will get. Test sends are marked “[TEST]” and are not recorded as resends.</p>
			<p style="margin:0 0 8px;"><label>Send to: <input type="email" id="glow-ser-test-to" value="<?php echo esc_attr( $me->user_email ); ?>" style="width:280px;"></label></p>
			<p style="margin:0 0 8px;"><label>Use details from seller: <input type="text" id="glow-ser-test-seller" placeholder="ID, username or email (blank = latest seller)" style="width:340px;"></label></p>
			<p style="margin:0;">
				<button class="button button-secondary" id="glow-ser-test">Send test email</button>
				<span id="glow-ser-test-result" style="margin-left:10px;"></span>
			</p>
		</div>

		<?php $bg = glow_ser_bg_status(); ?>
		<div id="glow-ser-bg" style="background:#fff;border:1px solid #ccd0d4;border-left:4px solid #00a32a;padding:12px 15px;margin-bottom:15px;max-width:900px;">
			<h2 style="margin:0 0 6px;font-size:15px;">Background sending</h2>
			<?php if ( ! glow_ser_bg_available() ) : ?>
				<p style="margin:0;color:#b32d2e;">Not available — needs WooCommerce’s Action Scheduler. Use “Send now from this page” instead.</p>
			<?php else : ?>
				<p style="margin:0 0 6px;color:#555;">Queued emails are sent by the server, <?php echo (int) GLOW_SER_PER_MINUTE; ?> per minute, even after you close this page. It runs with the site’s normal background tasks (about once a minute while the site has visitors).</p>
				<p style="margin:0;" id="glow-ser-bg-status" data-pending="<?php echo (int) $bg['pending']; ?>"></p>
				<p style="margin:8px 0 0;"><button class="button" id="glow-ser-bg-cancel" style="<?php echo $bg['pending'] ? '' : 'display:none;'; ?>">Cancel remaining</button></p>
			<?php endif; ?>
		</div>

		<?php if ( ! $error ) : ?>

		<?php if ( $dup_count ) : ?>
			<div class="notice notice-info inline" style="margin:0 0 10px;"><p><strong><?php echo (int) $dup_count; ?></strong> account(s) below are duplicates — the same seller registered more than once for the same fundraiser. They are greyed out and will <strong>not</strong> be emailed; only one account per seller gets the email (the one with orders, otherwise the best email address, otherwise the newest).</p></div>
		<?php endif; ?>

		<p><strong><?php echo count( $rows ); ?></strong> seller(s) shown<?php echo count( $rows ) >= GLOW_SER_MAX_ROWS ? ' (limit reached — narrow the dates)' : ''; ?>.
			<?php if ( glow_ser_bg_available() ) : ?>
				<button class="button button-primary" id="glow-ser-bg-queue" <?php disabled( empty( $rows ) ); ?>>Send selected in background (<?php echo (int) GLOW_SER_PER_MINUTE; ?>/min)</button>
			<?php endif; ?>
			<button class="button<?php echo glow_ser_bg_available() ? '' : ' button-primary'; ?>" id="glow-ser-send" <?php disabled( empty( $rows ) ); ?>>Send now from this page</button>
			<button class="button" id="glow-ser-stop" style="display:none;">Stop sending</button>
			<span id="glow-ser-progress" style="margin-left:10px;font-weight:600;"></span>
		</p>

		<table class="wp-list-table widefat fixed striped">
			<thead><tr>
				<td class="check-column"><input type="checkbox" id="glow-ser-all"></td>
				<th>Seller</th><th>Email</th><th>Fundraiser</th><th>Registered</th><th>Original email</th><th>Duplicates</th><th>Resent</th><th>Preview</th>
			</tr></thead>
			<tbody>
			<?php if ( ! $rows ) : ?>
				<tr><td colspan="9">No sellers found for these dates.</td></tr>
			<?php endif; ?>
			<?php foreach ( $rows as $r ) : ?>
				<tr data-id="<?php echo (int) $r['id']; ?>"<?php echo $r['is_dup'] ? ' style="opacity:.55;"' : ''; ?>>
					<th class="check-column"><input type="checkbox" class="glow-ser-cb" value="<?php echo (int) $r['id']; ?>" <?php checked( ! $r['is_dup'] && ! $r['queued'] && 'failed' === $r['status'] && '' === $r['resent'] ); ?> <?php disabled( $r['is_dup'] || $r['queued'] || '' !== $r['resent'] ); ?> title="<?php echo esc_attr( '' !== $r['resent'] ? 'Already re-sent — will not be sent again' : ( $r['queued'] ? 'Queued for background sending' : '' ) ); ?>"></th>
					<td><a href="<?php echo esc_url( get_edit_user_link( $r['id'] ) ); ?>"><?php echo esc_html( $r['full_name'] !== '' ? $r['full_name'] : $r['login'] ); ?></a></td>
					<td><?php echo esc_html( $r['email'] ); ?></td>
					<td><?php echo esc_html( trim( $r['fid'] . ( $r['org'] !== '' ? ' — ' . $r['org'] : '' ) ) ); ?></td>
					<td><?php echo esc_html( wp_date( 'M j, g:i a', glow_ser_registered_ts( $r['registered'] ) ) ); ?></td>
					<td><?php echo $labels[ $r['status'] ]; // phpcs:ignore ?></td>
					<td>
						<?php if ( $r['is_dup'] ) : ?>
							<span style="color:#996800;">Duplicate — not emailed.<br>Email goes to <a href="<?php echo esc_url( get_edit_user_link( $r['primary']->ID ) ); ?>">#<?php echo (int) $r['primary']->ID; ?> <?php echo esc_html( $r['primary']->user_login ); ?></a></span>
						<?php elseif ( $r['copies'] > 1 ) : ?>
							<span style="color:#2e7d32;">✓ Main account (<?php echo (int) $r['copies']; ?> accounts)</span>
						<?php else : ?>
							—
						<?php endif; ?>
					</td>
					<td class="glow-ser-resent">
						<?php if ( '' !== $r['resent'] ) : ?>
							<?php echo esc_html( $r['resent'] ); ?>
						<?php elseif ( $r['queued'] ) : ?>
							<span style="color:#2271b1;">⏳ Queued (background)</span>
						<?php elseif ( '' !== $r['bg_failed'] ) : ?>
							<span style="color:#b32d2e;">Background send failed <?php echo esc_html( $r['bg_failed'] ); ?></span>
						<?php endif; ?>
					</td>
					<td><a href="#" class="glow-ser-preview" data-id="<?php echo (int) $r['id']; ?>">Preview</a> | <a href="#" class="glow-ser-use" data-id="<?php echo (int) $r['id']; ?>" title="Use this seller in the test panel">Test</a></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php endif; ?>
	</div>

	<script>
	(function () {
		var ajax = <?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>;
		var nonce = <?php echo wp_json_encode( $nonce ); ?>;
		var $ = function (s) { return document.querySelector(s); };

		// Test panel — works with or without a seller table on the page.
		$('#glow-ser-test').addEventListener('click', function () {
			var btn = this, out = $('#glow-ser-test-result'), fd = new FormData();
			fd.append('action', 'glow_ser_test');
			fd.append('nonce', nonce);
			fd.append('test_to', $('#glow-ser-test-to').value);
			fd.append('seller', $('#glow-ser-test-seller').value);
			fd.append('subject', $('#glow-ser-subject').value);
			fd.append('note', $('#glow-ser-note').checked ? 1 : '');
			btn.disabled = true;
			out.style.color = '#555';
			out.textContent = 'Sending…';
			fetch(ajax, { method: 'POST', body: fd, credentials: 'same-origin' })
				.then(function (r) { return r.json(); })
				.then(function (res) {
					out.style.color = res.success ? '#2e7d32' : '#b32d2e';
					out.textContent = (res.success ? '✓ ' : '✗ ') + res.data;
				})
				.catch(function () {
					out.style.color = '#b32d2e';
					out.textContent = '✗ Request failed — please reload the page and try again.';
				})
				.then(function () { btn.disabled = false; });
		});

		// Background sending status — refreshes every 20 seconds while anything is queued.
		var bgStatus = $('#glow-ser-bg-status'), bgCancel = $('#glow-ser-bg-cancel'), bgTimer = null;
		function bgCall(action, extra) {
			var fd = new FormData();
			fd.append('action', action);
			fd.append('nonce', nonce);
			Object.keys(extra || {}).forEach(function (k) {
				[].concat(extra[k]).forEach(function (v) { fd.append(k, v); });
			});
			return fetch(ajax, { method: 'POST', body: fd, credentials: 'same-origin' }).then(function (r) { return r.json(); });
		}
		function bgRender(s) {
			if (!bgStatus) { return; }
			if (!s.total && !s.pending) {
				bgStatus.innerHTML = 'Nothing queued. Select sellers below and click <b>Send selected in background</b>.';
			} else {
				bgStatus.innerHTML = (s.pending ? '<b style="color:#00a32a;">● Running</b> — ' : '<b>Finished</b> — ') +
					'<b>' + s.sent + '</b> sent, <b>' + s.failed + '</b> failed, <b>' + s.skipped + '</b> skipped, <b>' + s.pending + '</b> waiting' +
					(s.pending ? ' (about ' + s.minutes + ' min left' + (s.next ? ', next at ' + s.next : '') + ')' : '') +
					(s.started ? '. Started ' + s.started + '.' : '.');
			}
			if (bgCancel) { bgCancel.style.display = s.pending ? '' : 'none'; }
			clearTimeout(bgTimer);
			if (s.pending) { bgTimer = setTimeout(bgRefresh, 20000); }
		}
		function bgRefresh() {
			bgCall('glow_ser_bg_status').then(function (res) { if (res.success) { bgRender(res.data); } });
		}
		if (bgStatus) { bgRender(<?php echo wp_json_encode( $bg ); ?>); }
		if (bgCancel) {
			bgCancel.addEventListener('click', function () {
				if (!confirm('Cancel all emails still waiting in the background queue? Emails already sent are not affected.')) { return; }
				bgCancel.disabled = true;
				bgCall('glow_ser_bg_cancel').then(function (res) {
					bgCancel.disabled = false;
					if (res.success) { bgRender(res.data); location.reload(); }
				});
			});
		}

		var all = $('#glow-ser-all');
		if (!all) { return; }

		var bgQueue = $('#glow-ser-bg-queue');
		if (bgQueue) {
			bgQueue.addEventListener('click', function () {
				var ids = selected(), o = opts();
				if (!ids.length) { alert('No sellers selected.'); return; }
				var mins = Math.ceil(ids.length / <?php echo (int) GLOW_SER_PER_MINUTE; ?>);
				if (!confirm('Queue the seller email for ' + ids.length + ' seller(s)?\n\nThe server sends <?php echo (int) GLOW_SER_PER_MINUTE; ?> per minute (about ' + mins + ' minute' + (mins === 1 ? '' : 's') + '). You can close this page.')) { return; }
				bgQueue.disabled = true;
				bgCall('glow_ser_bg_queue', { 'ids[]': ids, subject: o.subject, note: o.note }).then(function (res) {
					bgQueue.disabled = false;
					if (!res.success) { alert(res.data || 'Could not queue the emails.'); return; }
					alert(res.data.queued + ' email(s) queued' + (res.data.skipped ? ' (' + res.data.skipped + ' skipped: duplicate, already re-sent or already queued)' : '') + '. About ' + res.data.minutes + ' minute(s) to finish.');
					location.reload();
				}).catch(function () {
					bgQueue.disabled = false;
					alert('Request failed — please reload the page and try again.');
				});
			});
		}

		document.querySelectorAll('.glow-ser-use').forEach(function (a) {
			a.addEventListener('click', function (e) {
				e.preventDefault();
				$('#glow-ser-test-seller').value = a.dataset.id;
				$('#glow-ser-test-to').focus();
				window.scrollTo({ top: 0, behavior: 'smooth' });
			});
		});

		function selected() {
			return Array.prototype.map.call(document.querySelectorAll('.glow-ser-cb:checked:not(:disabled)'), function (c) { return c.value; });
		}
		function opts() {
			return { subject: $('#glow-ser-subject').value, note: $('#glow-ser-note').checked ? 1 : '' };
		}
		function post(ids) {
			var fd = new FormData(), o = opts();
			fd.append('action', 'glow_ser_send');
			fd.append('nonce', nonce);
			fd.append('subject', o.subject);
			fd.append('note', o.note);
			ids.forEach(function (id) { fd.append('ids[]', id); });
			return fetch(ajax, { method: 'POST', body: fd, credentials: 'same-origin' }).then(function (r) { return r.json(); });
		}

		all.addEventListener('change', function () {
			// Duplicates are disabled and never selected.
			document.querySelectorAll('.glow-ser-cb:not(:disabled)').forEach(function (c) { c.checked = all.checked; });
		});

		document.querySelectorAll('.glow-ser-preview').forEach(function (a) {
			a.addEventListener('click', function (e) {
				e.preventDefault();
				window.open(ajax + '?action=glow_ser_preview&nonce=' + nonce + '&note=' + opts().note + '&user_id=' + a.dataset.id, '_blank');
			});
		});

		// Browsers throttle timers in background tabs (Chrome: after ~5 min hidden,
		// to about once a minute), which stalled the pause between emails. Timers
		// inside a Web Worker aren't throttled that way, so the pause runs there and
		// the page falls back to a normal timer only if a worker can't be created.
		var timerWorker = null, timerCbs = {}, timerSeq = 0;
		function useFallbackTimers() {
			timerWorker = null;
			Object.keys(timerCbs).forEach(function (id) { var cb = timerCbs[id]; delete timerCbs[id]; setTimeout(cb, 0); });
		}
		try {
			var src = 'onmessage=function(e){setTimeout(function(){postMessage(e.data.id)},e.data.ms)}';
			timerWorker = new Worker(URL.createObjectURL(new Blob([src], { type: 'text/javascript' })));
			timerWorker.onmessage = function (e) { var cb = timerCbs[e.data]; delete timerCbs[e.data]; if (cb) { cb(); } };
			timerWorker.onerror = useFallbackTimers;
		} catch (err) {
			timerWorker = null;
		}
		function later(fn, ms) {
			if (!timerWorker) { setTimeout(fn, ms); return; }
			var id = ++timerSeq;
			timerCbs[id] = fn;
			timerWorker.postMessage({ id: id, ms: ms });
		}

		var sending = false, stopRequested = false;
		window.addEventListener('beforeunload', function (e) {
			if (sending) { e.preventDefault(); e.returnValue = ''; }
		});
		$('#glow-ser-stop').addEventListener('click', function () {
			stopRequested = true;
			this.disabled = true;
			this.textContent = 'Stopping after the current email…';
		});

		// One email per request, with a pause between emails so the SMTP server
		// isn't hit with a burst.
		$('#glow-ser-send').addEventListener('click', function () {
			var ids = selected(), btn = this, stop = $('#glow-ser-stop'), progress = $('#glow-ser-progress');
			var done = 0, failed = 0, skipped = 0, total = ids.length;
			var delay = Math.min(300, Math.max(1, parseInt($('#glow-ser-delay').value, 10) || 10));
			if (!ids.length) { alert('No sellers selected.'); return; }
			var mins = Math.ceil(ids.length * delay / 60);
			if (!confirm('Send the seller email to ' + ids.length + ' seller(s), one every ' + delay + ' seconds (about ' + mins + ' minute' + (mins === 1 ? '' : 's') + ')?\n\nKeep this page open until it finishes.')) { return; }

			sending = true; stopRequested = false;
			btn.disabled = true;
			stop.style.display = ''; stop.disabled = false; stop.textContent = 'Stop sending';

			function finish(text) {
				sending = false;
				btn.disabled = false;
				stop.style.display = 'none';
				progress.textContent = text + ' ' + done + ' sent, ' + failed + ' failed, ' + skipped + ' skipped.';
			}
			function status(waitLeft) {
				var left = ids.length;
				progress.textContent = 'Sending… ' + done + ' sent, ' + failed + ' failed, ' + skipped + ' skipped, ' + left + ' left' +
					(waitLeft ? ' — next email in ' + waitLeft + 's' : '') +
					(left ? ' (about ' + Math.ceil(left * delay / 60) + ' min remaining)' : '');
			}
			// Pause by the clock, not by counting ticks, so a late tick can't stretch it.
			function wait(seconds, then) {
				var end = Date.now() + seconds * 1000;
				(function tick() {
					if (stopRequested) { finish('Stopped.'); return; }
					var left = Math.ceil((end - Date.now()) / 1000);
					if (left <= 0) { then(); return; }
					status(left);
					later(tick, Math.min(1000, end - Date.now()));
				})();
			}

			(function next() {
				if (stopRequested) { finish('Stopped.'); return; }
				var id = ids.shift();
				if (!id) { finish('Finished:'); return; }
				status(0);
				var emailed = false;
				post([id]).then(function (res) {
					var r = res.success && res.data[id] ? res.data[id] : { ok: false, msg: 'Error' };
					var isSkip = !r.ok && /^Skipped/.test(r.msg);
					var row = document.querySelector('tr[data-id="' + id + '"]');
					if (r.ok) { done++; emailed = true; } else if (isSkip) { skipped++; } else { failed++; emailed = true; }
					if (row) {
						row.querySelector('.glow-ser-resent').innerHTML = '<span style="color:' + (r.ok ? '#2e7d32' : (isSkip ? '#996800' : '#b32d2e')) + ';">' + r.msg + '</span>';
						var cb = row.querySelector('.glow-ser-cb');
						cb.checked = false;
						if (r.ok) { cb.disabled = true; }
					}
				}).catch(function () {
					failed++; emailed = true;
				}).then(function () {
					// Only pause after an actual send attempt; skips move straight on.
					if (ids.length && emailed) { wait(delay, next); } else { next(); }
				});
			})();
		});
	})();
	</script>
	<?php
}
