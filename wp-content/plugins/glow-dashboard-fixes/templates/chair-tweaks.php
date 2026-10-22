<?php
/**
 * GLOW Dashboard Fixes – chairperson dashboard tweaks (client round 3).
 *
 *  1. Seller registration page moved to /seller-registers/ – all links + old URL redirect.
 *  2. Hero: bigger fundraiser code / days left, end date shown in the hero; bigger thank-you text.
 *  3. Duplicate "Edit Fundraiser Info" in Fundraiser Settings hidden; Order Status card moved
 *     above Seller Brochure Orders; Additional Tools tiles reordered.
 *  4. Simple "Edit fundraiser info" page (/fundraiser-info/) – group name, chairperson,
 *     dates, logo, goal, message – linked from the Hi card.
 *  5. QR codes (seller registration + store link) in the Share card, downloadable.
 *  6. Top 5 sellers card.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ------------------------------------------------------------------ */
/* 1. Seller registration URL                                           */
/* ------------------------------------------------------------------ */
function glow_tw_reg_path() {
	static $path = null;
	if ( null !== $path ) {
		return $path;
	}
	$path = '/seller-registers/';
	foreach ( array( 'seller-registers', 'register-as-a-seller' ) as $slug ) {
		$p = get_page_by_path( $slug );
		if ( $p && 'publish' === $p->post_status ) {
			$path = wp_make_link_relative( get_permalink( $p ) );
			break;
		}
	}
	return $path;
}
function glow_tw_reg_url( $fid ) {
	return add_query_arg( 'frid', (int) $fid, home_url( glow_tw_reg_path() ) );
}
add_filter( 'glow_fd_config', function ( $c ) {
	$c['urls']['seller_register'] = glow_tw_reg_path();
	return $c;
}, 30 );
add_filter( 'glow_sd_config', function ( $c ) {
	if ( isset( $c['urls']['seller_register'] ) ) {
		$c['urls']['seller_register'] = glow_tw_reg_path();
	}
	return $c;
}, 30 );
add_action( 'template_redirect', function () {
	$path = trim( (string) parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
	if ( in_array( $path, array( 'register-as-a-seller', 'seller-register' ), true ) && is_404() ) {
		$to = home_url( glow_tw_reg_path() );
		if ( ! empty( $_GET['frid'] ) ) {
			$to = add_query_arg( 'frid', absint( $_GET['frid'] ), $to );
		}
		wp_safe_redirect( $to, 301 );
		exit;
	}
}, 0 );

/* ------------------------------------------------------------------ */
/* Helpers                                                              */
/* ------------------------------------------------------------------ */
function glow_tw_sellers_top( $fid, $n = 5 ) {
	$rows = function_exists( 'glow_ct_orders' ) ? glow_ct_orders( $fid ) : array();
	$by   = array();
	foreach ( $rows as $r ) {
		if ( ! in_array( $r['raw'], array( 'completed', 'processing' ), true ) || ! is_numeric( $r['sid'] ) || (int) $r['sid'] <= 0 ) {
			continue;
		}
		$sid = (int) $r['sid'];
		$by[ $sid ] = ( $by[ $sid ] ?? 0 ) + (float) $r['total'];
	}
	arsort( $by );
	$out = array();
	foreach ( array_slice( $by, 0, $n, true ) as $sid => $sum ) {
		$u = get_userdata( $sid );
		if ( ! $u ) {
			continue;
		}
		$name = trim( get_user_meta( $sid, 'first_name', true ) . ' ' . get_user_meta( $sid, 'last_name', true ) );
		$img  = get_user_meta( $sid, 'user_registration_image_url_profile', true );
		$out[] = array( 'id' => $sid, 'name' => $name ? $name : $u->display_name, 'sum' => $sum, 'img' => $img );
	}
	return $out;
}

/* ------------------------------------------------------------------ */
/* 2–3, 5–6. Dashboard CSS + JS                                         */
/* ------------------------------------------------------------------ */
add_action( 'wp_head', function () {
	if ( ! is_page( 'fundraiser-dashboard' ) && false === strpos( (string) $_SERVER['REQUEST_URI'], '/fundraiser-dashboard' ) ) {
		return;
	}
	?>
	<style id="glow-fd-tweaks">
	.glow-fd-top .glow-fd-code{font-size:20px !important;font-weight:700;letter-spacing:.02em;display:flex;align-items:center;gap:10px;flex-wrap:wrap}
	.glow-fd-top #glow-fd-code{font-size:34px !important;font-weight:900 !important;letter-spacing:.04em;color:#fff;text-shadow:0 2px 10px rgba(0,0,0,.25)}
	.glow-fd-top .glow-fd-code-copy{font-size:13px !important;padding:6px 14px !important;border-radius:999px !important}
	.glow-fd-top .glow-fd-statusrow{display:flex;align-items:center;gap:14px;flex-wrap:wrap;margin-top:8px}
	.glow-fd-top .glow-fd-live{font-size:15px !important;font-weight:800 !important}
	.glow-fd-top .glow-fd-days{font-size:26px !important;font-weight:900 !important;color:#ffd84d !important;letter-spacing:.03em;text-shadow:0 2px 8px rgba(0,0,0,.25)}
	.glow-fd-top .glow-fd-hero-end{font-size:17px;font-weight:800;color:#fff;padding:6px 14px;border-radius:999px;background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.28)}
	.glow-fd-thanks p{font-size:17.5px !important;line-height:1.5 !important;font-weight:600 !important}
	.glow-fd-profile .glow-fd-msg{margin:0 0 20px;padding:14px 16px;border-radius:14px;background:rgba(255,255,255,.75);border:1px solid #ffd3e0;text-align:left}
	.glow-fd-profile .glow-fd-msg-k{display:block;font-size:11.5px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#ec0f63;margin-bottom:6px}
	.glow-fd-profile .glow-fd-msg p{margin:0;font-size:15px;line-height:1.5;color:#3a3752;font-style:italic;max-height:9.2em;overflow:auto}
	.glow-fd-profile .glow-fd-msg p.is-empty{font-style:normal;color:#6f6c85;font-size:14px}
	.glow-fd-profile .glow-fd-msg p a{color:#ec0f63 !important;font-weight:700}
	/* Row 1: fill the white space — bigger logo, name, donut, dollar amounts (client request) */
	.glow-fd-row1 .glow-fd-profile{display:flex;flex-direction:column;justify-content:center}
	.glow-fd-row1 .glow-fd-avatar{width:170px !important;height:170px !important;padding:5px !important;box-shadow:0 0 0 4px #ffb7cd !important;margin-bottom:18px !important}
	.glow-fd-row1 .glow-fd-hi{font-size:44px !important;margin-bottom:10px !important}
	.glow-fd-row1 .glow-fd-role{font-size:18px !important;margin-bottom:22px !important}
	.glow-fd-row1 .glow-fd-store-btn{padding:17px 18px !important;font-size:16px !important}
	.glow-fd-row1 .glow-fd-edit-link{font-size:15.5px !important}
	.glow-fd-row1 .glow-fd-progress{display:flex;flex-direction:column}
	.glow-fd-row1 .glow-fd-progress-body{margin:22px 0 10px !important;gap:28px !important;flex:1 1 auto}
	.glow-fd-row1 .glow-fd-donut{flex:0 0 210px !important;width:210px !important;height:210px !important}
	.glow-fd-row1 .glow-fd-donut::after{inset:20px !important}
	.glow-fd-row1 .glow-fd-donut-in .pct{font-size:48px !important}
	.glow-fd-row1 .glow-fd-donut-in .of{font-size:16px !important;margin-top:6px !important}
	.glow-fd-row1 .glow-fd-donut-in .of a{font-size:13px !important}
	.glow-fd-row1 .glow-fd-raised-amt{font-size:68px !important}
	.glow-fd-row1 .glow-fd-raised-sub{font-size:20px !important;margin:8px 0 18px !important}
	.glow-fd-row1 .glow-fd-bar{height:14px !important}
	.glow-fd-row1 .glow-fd-bar-goal{font-size:16px !important;margin-top:8px !important}
	.glow-fd-row1 .glow-fd-split{margin-top:22px !important}
	.glow-fd-row1 .glow-fd-split div{padding:14px 8px !important;gap:8px !important}
	.glow-fd-row1 .glow-fd-split svg{width:34px !important;height:34px !important}
	.glow-fd-row1 .glow-fd-split b{font-size:34px !important;font-weight:900 !important}
	.glow-fd-row1 .glow-fd-split span{font-size:15px !important;line-height:1.3 !important}
	@media(max-width:1100px){.glow-fd-row1 .glow-fd-donut{flex-basis:170px !important;width:170px !important;height:170px !important}.glow-fd-row1 .glow-fd-raised-amt{font-size:52px !important}.glow-fd-row1 .glow-fd-split b{font-size:26px !important}.glow-fd-row1 .glow-fd-avatar{width:130px !important;height:130px !important}}
	.glow-fd-thanks .glow-fd-thanks-script .a{font-size:1.25em}
	.glow-fd-settings a.glow-fd-setrow[href*="edit-fundraiser"]{display:none !important}
	/* QR */
	#glow-fd-share .glow-fd-qr{display:grid !important;grid-template-columns:1fr 1fr !important;gap:14px !important;margin:16px 0 0 !important;grid-column:1/-1;flex-basis:100%;width:100% !important;max-width:none !important;padding:0 !important;float:none !important}
	#glow-fd-share .glow-fd-qr,#glow-sd-share .glow-fd-qr{height:auto !important;min-height:0 !important;max-height:none !important;overflow:visible !important;position:static !important}
	#glow-fd-share,#glow-sd-share{height:auto !important;max-height:none !important;overflow:visible !important}
	#glow-fd-share .glow-fd-qr-box{width:auto !important;max-width:none !important;margin:0 !important;float:none !important;display:block !important}
	.glow-fd-qr-box{background:#faf9fc;border:1px solid #e8e8f0;border-radius:16px;padding:14px;text-align:center}
	.glow-fd-qr-box h4{margin:0 0 10px;font-size:12.5px;font-weight:800;letter-spacing:.05em;text-transform:uppercase;color:#6f6c85}
	.glow-fd-qr-box canvas{display:none !important}.glow-fd-qr-box img{width:150px !important;height:150px !important;margin:0 auto;display:block !important;background:#fff;padding:6px;border-radius:12px;border:1px solid #e8e8f0}
	.glow-fd-qr-box a.glow-fd-qr-dl{display:inline-flex;margin-top:10px;padding:8px 16px;border-radius:999px;background:#181735;color:#fff !important;font-size:12px;font-weight:800;letter-spacing:.04em;text-transform:uppercase;text-decoration:none !important}
	/* Top sellers */
	.glow-fd-top5{background:#fff;border:1px solid #e8e8f0;border-radius:20px;padding:22px 24px;margin:0 0 18px;box-shadow:0 2px 6px rgba(22,20,50,.05);font-family:inherit}
	.glow-fd-top5 h3{margin:0 0 14px;font-size:16px;font-weight:800;letter-spacing:.02em;text-transform:uppercase;display:flex;align-items:center;gap:8px}
	.glow-fd-top5 ol{list-style:none;margin:0;padding:0;display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:10px}
	.glow-fd-top5 li{display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:14px;background:#faf9fc;border:1px solid #efeef5}
	.glow-fd-top5 li.is-1{background:#fff7e0;border-color:#ffe3a0}.glow-fd-top5 li.is-2{background:#f3f4f8}.glow-fd-top5 li.is-3{background:#fdf1e8}
	.glow-fd-top5 .n{width:24px;height:24px;border-radius:50%;background:#181735;color:#fff;font-size:12px;font-weight:800;display:inline-flex;align-items:center;justify-content:center;flex:0 0 24px}
	.glow-fd-top5 li.is-1 .n{background:#f5b400}.glow-fd-top5 li.is-2 .n{background:#9aa0b4}.glow-fd-top5 li.is-3 .n{background:#c67b3f}
	.glow-fd-top5 .av{width:40px;height:40px;border-radius:50%;object-fit:cover;background:#ffe1e8;flex:0 0 40px;display:inline-flex;align-items:center;justify-content:center;font-weight:800;color:#ec0f63;font-size:14px;overflow:hidden}
	.glow-fd-top5 .av img{width:100%;height:100%;object-fit:cover}
	.glow-fd-top5 .who{flex:1;min-width:0}.glow-fd-top5 .who b{display:block;font-size:14.5px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.glow-fd-top5 .who span{font-size:12.5px;color:#6f6c85}
	.glow-fd-top5 .amt{font-weight:800;font-size:15px;color:#0d6b50;white-space:nowrap}
	.glow-fd-top5 .empty{color:#8d8aa0;font-size:14px}
	@media(max-width:640px){#glow-fd-share .glow-fd-qr{grid-template-columns:1fr !important}.glow-fd-top .glow-fd-days{font-size:22px !important}}
	</style>
	<?php
} );

add_action( 'wp_footer', function () {
	if ( ! is_user_logged_in() ) {
		return;
	}
	if ( ! is_page( 'fundraiser-dashboard' ) && false === strpos( (string) $_SERVER['REQUEST_URI'], '/fundraiser-dashboard' ) ) {
		return;
	}
	$fid   = function_exists( 'glow_ct_fid' ) ? glow_ct_fid() : get_current_user_id();
	$reg   = glow_tw_reg_url( $fid );
	$store = home_url( '/profile/' . $fid );
	$top   = glow_tw_sellers_top( $fid );
	ob_start();
	?>
	<section class="glow-fd-top5" id="glow-fd-top5">
		<h3>🏆 Top 5 Sellers</h3>
		<?php if ( ! $top ) : ?>
			<p class="empty">No seller sales yet &mdash; your top sellers will appear here as orders come in.</p>
		<?php else : ?>
		<ol>
			<?php foreach ( $top as $i => $s ) :
				$ini = strtoupper( mb_substr( $s['name'], 0, 1 ) ); ?>
				<li class="is-<?php echo $i + 1; ?>">
					<span class="n"><?php echo $i + 1; ?></span>
					<span class="av"><?php echo $s['img'] ? '<img src="' . esc_url( $s['img'] ) . '" alt="">' : esc_html( $ini ); ?></span>
					<span class="who"><b><?php echo esc_html( $s['name'] ); ?></b><span><a href="<?php echo esc_url( home_url( '/seller/' . $s['id'] ) ); ?>" target="_blank" rel="noopener">View store</a></span></span>
					<span class="amt"><?php echo wp_kses_post( wc_price( $s['sum'] ) ); ?></span>
				</li>
			<?php endforeach; ?>
		</ol>
		<?php endif; ?>
	</section>
	<?php
	$top5 = ob_get_clean();
	/* group message typed in Edit Fundraiser Info / Customize store */
	$msg = trim( (string) get_user_meta( $fid, 'fund_profile_description', true ) );
	if ( '' === $msg ) { $msg = trim( (string) get_user_meta( $fid, 'user_registration_reason_for_fundraising', true ) ); }
	$msg_html = '<div class="glow-fd-msg">' . ( '' !== $msg
		? '<span class="glow-fd-msg-k">Our message</span><p>' . nl2br( esc_html( wp_strip_all_tags( $msg ) ) ) . '</p>'
		: '<span class="glow-fd-msg-k">Our message</span><p class="is-empty">Tell supporters why you are raising funds. <a href="' . esc_url( home_url( '/fundraiser-info/' ) ) . '">Add your message</a></p>' ) . '</div>';
	?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
	<script id="glow-fd-tweaks-js">
	(function(){
		var fd = document.querySelector('.glow-fd'); if (!fd) return;
		/* hero: end date */
		var end = fd.querySelector('.glow-fd-enddate'), row = fd.querySelector('.glow-fd-statusrow');
		if (end && row && !row.querySelector('.glow-fd-hero-end')) { var e = document.createElement('span'); e.className = 'glow-fd-hero-end'; e.textContent = end.textContent.replace(/\s+/g,' ').trim(); row.appendChild(e); }
		/* group message under the logo in the Hi card */
		var prof = fd.querySelector('.glow-fd-profile'), role = prof && prof.querySelector('.glow-fd-role');
		if (prof && !prof.querySelector('.glow-fd-msg')) { var m = document.createElement('div'); m.innerHTML = <?php echo wp_json_encode( $msg_html ); ?>; var btn = prof.querySelector('.glow-fd-store-btn'); prof.insertBefore(m.firstElementChild, btn || null); }
		/* order status card above seller brochure orders */
		var orders = document.getElementById('glow-fd-orders'), sbo = fd.querySelector('.glow-sbo');
		if (orders && sbo && sbo.parentNode) sbo.parentNode.insertBefore(orders, sbo);
		/* top 5 sellers card above seller brochure orders */
		if (sbo && sbo.parentNode && !document.getElementById('glow-fd-top5')) { var t = document.createElement('div'); t.innerHTML = <?php echo wp_json_encode( $top5 ); ?>; sbo.parentNode.insertBefore(t.firstElementChild, orders || sbo); }
		/* tools order */
		var grid = fd.querySelector('.glow-fd-tools-grid');
		if (grid) {
			var order = [/Fundraiser\s*Details/i, /Marketing/i, /Registered\s*Sellers/i, /Order\s*Status/i, /Download\s*Invoice/i, /Claims/i];
			var tiles = Array.prototype.slice.call(grid.children);
			order.forEach(function(re){ var t = tiles.find(function(x){ return re.test((x.textContent||'').replace(/\s+/g,' ')); }); if (t) grid.appendChild(t); });
		}
		/* simple edit link in the Hi card */
		fd.querySelectorAll('.glow-fd-profile a[href*="edit-fundraiser"]').forEach(function(a){ a.href = <?php echo wp_json_encode( function_exists( 'glow_ct_url' ) ? glow_ct_url( '/fundraiser-info/', $fid ) : home_url( '/fundraiser-info/' ) ); ?>; });
		/* QR codes in the share card */
		var share = document.getElementById('glow-fd-share');
		if (share && window.QRCode && !share.querySelector('.glow-fd-qr-box')) {
			/* Replaces the template's small side QR codes (shown when fundraiser_barcode_link meta exists) — never show both. */
			var old = share.querySelector('.glow-fd-qr-wrap'); if (old) old.style.display = 'none';
			var wrap = document.createElement('div'); wrap.className = 'glow-fd-qr';
			wrap.innerHTML = '<div class="glow-fd-qr-box"><h4>Seller registration QR</h4><div id="glow-qr-reg"></div><a class="glow-fd-qr-dl" href="#" data-for="glow-qr-reg" data-name="seller-registration-qr.png">Download</a></div>'
				+ '<div class="glow-fd-qr-box"><h4>Store link QR</h4><div id="glow-qr-store"></div><a class="glow-fd-qr-dl" href="#" data-for="glow-qr-store" data-name="store-link-qr.png">Download</a></div>';
			share.appendChild(wrap);
			new QRCode(document.getElementById('glow-qr-reg'), { text: <?php echo wp_json_encode( $reg ); ?>, width: 300, height: 300, correctLevel: QRCode.CorrectLevel.M });
			new QRCode(document.getElementById('glow-qr-store'), { text: <?php echo wp_json_encode( $store ); ?>, width: 300, height: 300, correctLevel: QRCode.CorrectLevel.M });
			wrap.querySelectorAll('.glow-fd-qr-dl').forEach(function(a){ a.addEventListener('click', function(ev){ ev.preventDefault(); var box = document.getElementById(a.getAttribute('data-for')); var c = box.querySelector('canvas'); var img = box.querySelector('img'); var url = c ? c.toDataURL('image/png') : (img ? img.src : ''); if (!url) return; var l = document.createElement('a'); l.href = url; l.download = a.getAttribute('data-name'); document.body.appendChild(l); l.click(); l.remove(); }); });
		}
	})();
	</script>
	<?php
}, 25 );

/* ------------------------------------------------------------------ */
/* 4. Simple edit page: /fundraiser-info/                               */
/* ------------------------------------------------------------------ */
add_action( 'template_redirect', function () {
	$path = trim( (string) parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
	if ( 'fundraiser-info' !== $path ) {
		return;
	}
	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( wp_login_url( home_url( $_SERVER['REQUEST_URI'] ) ) );
		exit;
	}
	$fid = function_exists( 'glow_ct_fid' ) ? glow_ct_fid() : get_current_user_id();
	$u   = get_userdata( $fid );
	if ( ! $u ) {
		wp_die( 'Fundraiser not found.' );
	}
	$saved = false;
	$errors = array();
	if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['glow_fi_nonce'] ) ) {
		if ( ! wp_verify_nonce( $_POST['glow_fi_nonce'], 'glow_fi_' . $fid ) ) {
			$errors[] = 'Your session expired. Please try again.';
		} else {
			$org   = sanitize_text_field( wp_unslash( $_POST['org'] ?? '' ) );
			$first = sanitize_text_field( wp_unslash( $_POST['first_name'] ?? '' ) );
			$last  = sanitize_text_field( wp_unslash( $_POST['last_name'] ?? '' ) );
			$start = sanitize_text_field( wp_unslash( $_POST['start_date'] ?? '' ) );
			$end   = sanitize_text_field( wp_unslash( $_POST['end_date'] ?? '' ) );
			$goal  = preg_replace( '/[^0-9.]/', '', (string) ( $_POST['goal'] ?? '' ) );
			$msg   = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );
			if ( '' === $org ) {
				$errors[] = 'Please enter your school / group name.';
			}
			if ( ( $start && ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $start ) ) || ( $end && ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $end ) ) ) {
				$errors[] = 'Please pick valid dates.';
			}
			if ( ! $errors ) {
				update_user_meta( $fid, 'user_registration_name_of_school_business', $org );
				update_user_meta( $fid, 'user_registration_input_company', $org );
				if ( $first ) { update_user_meta( $fid, 'first_name', $first ); }
				if ( $last ) { update_user_meta( $fid, 'last_name', $last ); }
				if ( $first || $last ) { wp_update_user( array( 'ID' => $fid, 'display_name' => trim( $first . ' ' . $last ) ) ); }
				update_user_meta( $fid, 'user_registration_start_date', $start );
				update_user_meta( $fid, 'user_registration_end_date', $end );
				update_user_meta( $fid, 'user_registration_fr_goal', $goal );
				update_user_meta( $fid, 'fund_profile_description', $msg );
				update_user_meta( $fid, 'user_registration_reason_for_fundraising', $msg );
				if ( ! empty( $_FILES['logo']['name'] ) ) {
					require_once ABSPATH . 'wp-admin/includes/file.php';
					require_once ABSPATH . 'wp-admin/includes/media.php';
					require_once ABSPATH . 'wp-admin/includes/image.php';
					$aid = media_handle_upload( 'logo', 0 );
					if ( is_wp_error( $aid ) ) {
						$errors[] = 'Logo upload failed: ' . $aid->get_error_message();
					} else {
						$url = wp_get_attachment_url( $aid );
						foreach ( array( 'user_registration_image_url_profile', 'image_url_profile', 'fund_profile_image' ) as $k ) {
							update_user_meta( $fid, $k, $url );
						}
						update_user_meta( $fid, 'user_registration_profile_pic_url', $aid );
					}
				}
				if ( ! empty( $_POST['remove_logo'] ) ) {
					foreach ( array( 'user_registration_image_url_profile', 'image_url_profile', 'fund_profile_image', 'user_registration_profile_pic_url' ) as $k ) {
						delete_user_meta( $fid, $k );
					}
				}
				delete_transient( 'glow_fd_stats_' . $fid );
				$saved = empty( $errors );
			}
		}
	}
	$v = function ( $k ) use ( $fid ) { return (string) get_user_meta( $fid, $k, true ); };
	$org   = $v( 'user_registration_name_of_school_business' );
	$first = $v( 'first_name' ); $last = $v( 'last_name' );
	$start = $v( 'user_registration_start_date' ); $end = $v( 'user_registration_end_date' );
	$goal  = $v( 'user_registration_fr_goal' );
	$msg   = $v( 'fund_profile_description' );
	$logo  = $v( 'user_registration_image_url_profile' );
	$d = function ( $s ) { $t = $s ? strtotime( $s ) : false; return $t ? date( 'Y-m-d', $t ) : ''; };
	status_header( 200 ); nocache_headers();
	if ( function_exists( 'glow_vp_title' ) ) { glow_vp_title( 'Edit Fundraiser Info' ); }
	get_header();
	?>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
	<style>
	.glow-fi{--pink:#ec0f63;--ink:#181735;--muted:#6f6c85;--line:#e8e8f0;font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:var(--ink);background:#f2f3f9;padding:28px 18px 48px;-webkit-font-smoothing:antialiased}
	.glow-fi *{box-sizing:border-box}.glow-fi-wrap{max-width:760px;margin:0 auto}
	.glow-fi-back{display:inline-flex;gap:6px;font-size:14px;font-weight:600;color:#4a3fd0 !important;text-decoration:none !important;margin-bottom:14px}
	.glow-fi h1{margin:0 0 4px;font-size:26px;font-weight:800}.glow-fi-sub{margin:0 0 20px;color:var(--muted);font-size:15px}
	.glow-fi-card{background:#fff;border:1px solid var(--line);border-radius:20px;box-shadow:0 2px 6px rgba(22,20,50,.05);padding:26px 28px;margin-bottom:18px}
	.glow-fi-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px 20px}.glow-fi-field{display:flex;flex-direction:column;gap:6px}.glow-fi-field.is-full{grid-column:1/-1}
	.glow-fi-field label{font-size:12.5px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;color:#8d8aa0}
	.glow-fi-field input,.glow-fi-field textarea{width:100%;font:inherit;font-size:15px;padding:11px 14px;border:1px solid #d9d7e6;border-radius:10px;background:#fff;color:var(--ink)}
	.glow-fi-field input:focus,.glow-fi-field textarea:focus{outline:none;border-color:var(--pink);box-shadow:0 0 0 3px rgba(236,15,99,.12)}
	.glow-fi-field textarea{min-height:110px;resize:vertical}.glow-fi-help{font-size:13px;color:var(--muted)}
	.glow-fi-logo{display:flex;align-items:center;gap:18px;flex-wrap:wrap}.glow-fi-logo img,.glow-fi-logo .ph{width:110px;height:110px;border-radius:20px;object-fit:cover;border:3px solid #ffd3e0;background:#ffe1e8}
	.glow-fi-logo .ph{display:flex;align-items:center;justify-content:center;color:#e05a86;font-size:12px;font-weight:600;text-align:center;padding:8px}
	.glow-fi-btn{display:inline-flex;align-items:center;gap:8px;padding:12px 26px;border-radius:999px;background:var(--pink);color:#fff !important;font-weight:700;font-size:14px;letter-spacing:.03em;text-transform:uppercase;border:0;cursor:pointer;text-decoration:none !important}
	.glow-fi-btn.is-ghost{background:#fff;color:var(--ink) !important;border:1.5px solid #d9d7e6}
	.glow-fi-actions{display:flex;gap:12px;align-items:center;margin-top:22px;flex-wrap:wrap}
	.glow-fi-notice{padding:12px 16px;border-radius:12px;font-size:14.5px;margin-bottom:18px;background:#eaf8f1;color:#0d6b50;border:1px solid #c6ecd9}.glow-fi-notice.is-error{background:#fff0f3;color:#9b1239;border-color:#f7c9d6}
	.glow-fi-more{font-size:13.5px;color:var(--muted);margin-top:14px}.glow-fi-more a{color:#4a3fd0;font-weight:600}
	@media(max-width:640px){.glow-fi-grid{grid-template-columns:1fr}.glow-fi-card{padding:20px 18px}}
	</style>
	<div class="glow-fi"><div class="glow-fi-wrap">
	<a class="glow-fi-back" href="<?php echo esc_url( function_exists( 'glow_ct_url' ) ? glow_ct_url( '/fundraiser-dashboard/', $fid ) : home_url( '/fundraiser-dashboard/' ) ); ?>">&larr; Back to my dashboard</a>
	<h1>Edit Fundraiser Info</h1>
	<p class="glow-fi-sub">The details that show on your dashboard, your store and your share messages.</p>
	<?php if ( $saved ) : ?><div class="glow-fi-notice">Saved! Your changes are live.</div><?php endif; ?>
	<?php foreach ( $errors as $e ) : ?><div class="glow-fi-notice is-error"><?php echo esc_html( $e ); ?></div><?php endforeach; ?>
	<?php if ( current_user_can( 'manage_options' ) && $fid !== get_current_user_id() ) : ?><div class="glow-fi-notice" style="background:#eef4ff;color:#2b3a8f;border-color:#cfdcff">Admin view &mdash; editing fundraiser <b><?php echo esc_html( $u->display_name ); ?></b> (#<?php echo (int) $fid; ?>).</div><?php endif; ?>
	<form method="post" enctype="multipart/form-data" class="glow-fi-card">
		<?php wp_nonce_field( 'glow_fi_' . $fid, 'glow_fi_nonce' ); ?>
		<div class="glow-fi-grid">
			<div class="glow-fi-field is-full"><label>School / Group name</label><input type="text" name="org" value="<?php echo esc_attr( $org ); ?>" required></div>
			<div class="glow-fi-field"><label>Chairperson first name</label><input type="text" name="first_name" value="<?php echo esc_attr( $first ); ?>"></div>
			<div class="glow-fi-field"><label>Chairperson last name</label><input type="text" name="last_name" value="<?php echo esc_attr( $last ); ?>"></div>
			<div class="glow-fi-field"><label>Sale starts</label><input type="date" name="start_date" value="<?php echo esc_attr( $d( $start ) ); ?>"></div>
			<div class="glow-fi-field"><label>Sale ends</label><input type="date" name="end_date" value="<?php echo esc_attr( $d( $end ) ); ?>"></div>
			<div class="glow-fi-field"><label>Fundraising goal ($)</label><input type="number" min="0" step="1" name="goal" value="<?php echo esc_attr( $goal ); ?>" placeholder="1000"></div>
			<div class="glow-fi-field is-full"><label>Logo / photo</label>
				<div class="glow-fi-logo">
					<?php echo $logo ? '<img src="' . esc_url( $logo ) . '" alt="">' : '<div class="ph">No logo yet</div>'; ?>
					<div><input type="file" name="logo" accept="image/*"><div class="glow-fi-help">Square images (475 &times; 475 px) look best.</div>
					<?php if ( $logo ) : ?><label style="text-transform:none;letter-spacing:0;font-weight:500;color:#6f6c85;margin-top:6px;display:flex;gap:6px;align-items:center"><input type="checkbox" name="remove_logo" value="1" style="width:auto"> Remove current logo</label><?php endif; ?></div>
				</div>
			</div>
			<div class="glow-fi-field is-full"><label>Fundraising message</label><textarea name="message" placeholder="Tell supporters what you are raising money for…"><?php echo esc_textarea( $msg ); ?></textarea><div class="glow-fi-help">Shows on your store page and in your share messages.</div></div>
		</div>
		<div class="glow-fi-actions"><button type="submit" class="glow-fi-btn">Save changes</button><a class="glow-fi-btn is-ghost" href="<?php echo esc_url( function_exists( 'glow_ct_url' ) ? glow_ct_url( '/fundraiser-dashboard/', $fid ) : home_url( '/fundraiser-dashboard/' ) ); ?>">Cancel</a></div>
		<p class="glow-fi-more">Need to change your address, phone number or shipping details? <a href="<?php echo esc_url( add_query_arg( 'frid', $fid, home_url( '/edit-fundraiser/' ) ) ); ?>">Edit full fundraiser details</a>.</p>
	</form>
	</div></div>
	<?php
	get_footer();
	exit;
}, 1 );

/* ------------------------------------------------------------------ */
/* 7. Seller dashboard: QR code in the share card, duplicate Edit link  */
/* ------------------------------------------------------------------ */
add_action( 'wp_head', function () {
	if ( false === strpos( (string) $_SERVER['REQUEST_URI'], '/seller-dashboard' ) ) {
		return;
	}
	?>
	<style id="glow-sd-tweaks">
	.glow-sd-settings a.glow-sd-setrow[href*="edit-seller-info"]{display:none !important}
	#glow-sd-share .glow-fd-qr{display:grid !important;grid-template-columns:1fr !important;gap:14px !important;margin:16px 0 0 !important;width:100% !important;max-width:none !important;padding:0 !important}
	#glow-sd-share .glow-fd-qr-box{background:#faf9fc;border:1px solid #e8e8f0;border-radius:16px;padding:14px;text-align:center;width:auto !important;max-width:none !important;margin:0 !important;display:block !important}
	#glow-sd-share .glow-fd-qr-box h4{margin:0 0 10px;font-size:12.5px;font-weight:800;letter-spacing:.05em;text-transform:uppercase;color:#6f6c85}
	#glow-sd-share .glow-fd-qr-box canvas{display:none !important}
	#glow-sd-share .glow-fd-qr-box img{width:150px !important;height:150px !important;margin:0 auto;display:block !important;background:#fff;padding:6px;border-radius:12px;border:1px solid #e8e8f0}
	#glow-sd-share .glow-fd-qr-box a.glow-fd-qr-dl{display:inline-flex;margin-top:10px;padding:8px 16px;border-radius:999px;background:#181735;color:#fff !important;font-size:12px;font-weight:800;letter-spacing:.04em;text-transform:uppercase;text-decoration:none !important}
	</style>
	<?php
} );
add_action( 'wp_footer', function () {
	if ( ! is_user_logged_in() || false === strpos( (string) $_SERVER['REQUEST_URI'], '/seller-dashboard' ) ) {
		return;
	}
	?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
	<script id="glow-sd-tweaks-js">
	(function(){
		var share = document.getElementById('glow-sd-share'); if (!share || !window.QRCode || share.querySelector('.glow-fd-qr')) return;
		var inp = document.getElementById('glow-sd-link'); if (!inp || !inp.value) return;
		/* This box replaces the template's small QR (shown when seller_barcode_link meta exists) — never show both. */
		var old = share.querySelector('.glow-sd-qr-wrap'); if (old) old.style.display = 'none';
		var url = inp.value.trim(); if (!/^https?:\/\//i.test(url)) url = 'https://' + url;
		var wrap = document.createElement('div'); wrap.className = 'glow-fd-qr';
		wrap.innerHTML = '<div class="glow-fd-qr-box"><h4>My store QR code</h4><div id="glow-qr-seller"></div><a class="glow-fd-qr-dl" href="#">Download</a></div>';
		share.appendChild(wrap);
		new QRCode(document.getElementById('glow-qr-seller'), { text: url, width: 300, height: 300, correctLevel: QRCode.CorrectLevel.M });
		wrap.querySelector('.glow-fd-qr-dl').addEventListener('click', function(ev){ ev.preventDefault(); var box = document.getElementById('glow-qr-seller'); var c = box.querySelector('canvas'); var img = box.querySelector('img'); var u = c ? c.toDataURL('image/png') : (img ? img.src : ''); if (!u) return; var l = document.createElement('a'); l.href = u; l.download = 'my-store-qr.png'; document.body.appendChild(l); l.click(); l.remove(); });
	})();
	</script>
	<?php
}, 25 );
