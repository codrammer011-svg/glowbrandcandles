<?php
/**
 * GLOW — "Share Now" popup after seller registration.
 *
 * When a seller finishes registering on /register-as-a-seller/ (form #62307,
 * which is set to no-redirect so they stay on the page), a modal pops up with
 * their seller link + one-tap share buttons, nudging them to share immediately
 * instead of waiting until the dashboard.
 *
 * Included from the child theme functions.php.
 */
defined( 'ABSPATH' ) || exit;

/**
 * Remember the just-registered seller for ~10 min via a cookie, so the popup can
 * build their link even if the form isn't set to auto-login.
 */
add_action( 'user_registration_after_register_user_action', 'glow_seller_mark_new', 40, 3 );
function glow_seller_mark_new( $valid_form_data, $form_id, $user_id ) {
	$user = get_userdata( $user_id );
	$is_seller = ( $user && in_array( 'seller', (array) $user->roles, true ) )
		|| isset( $valid_form_data['seller_fundraiser'] )
		|| isset( $valid_form_data['seller_goal'] )
		|| (int) $form_id === 62307;
	if ( $is_seller && ! headers_sent() ) {
		setcookie( 'glow_new_seller', (string) $user_id, time() + 600, '/' );
	}
}

/**
 * AJAX: return the (just-registered) seller's share link + name.
 */
add_action( 'wp_ajax_glow_seller_share_links', 'glow_seller_share_links_cb' );
add_action( 'wp_ajax_nopriv_glow_seller_share_links', 'glow_seller_share_links_cb' );
function glow_seller_share_links_cb() {
	$uid = get_current_user_id();
	if ( ! $uid && ! empty( $_COOKIE['glow_new_seller'] ) ) {
		$uid = (int) $_COOKIE['glow_new_seller'];
	}
	$user = $uid ? get_userdata( $uid ) : false;
	if ( ! $user ) {
		wp_send_json_error();
	}
	$name = trim( get_user_meta( $uid, 'first_name', true ) . ' ' . get_user_meta( $uid, 'last_name', true ) );
	if ( '' === $name ) {
		$name = $user->display_name;
	}
	// Display-only extras for the popup: fundraiser name + goal pill and the seller QR code.
	$fid  = preg_replace( '/\D/', '', (string) ( get_user_meta( $uid, 'user_registration_seller_fundraiser', true ) ?: get_user_meta( $uid, 'seller_fundraiser', true ) ) );
	$org  = '' !== $fid ? trim( (string) get_user_meta( (int) $fid, 'user_registration_name_of_school_business', true ) ) : '';
	$goal = ltrim( trim( (string) ( get_user_meta( $uid, 'user_registration_seller_goal', true ) ?: get_user_meta( $uid, 'seller_goal', true ) ) ), '$' );
	$qr   = ( function_exists( 'glow_seller_qr_url' ) && in_array( 'seller', (array) $user->roles, true ) ) ? glow_seller_qr_url( $uid ) : '';
	wp_send_json_success( array(
		'name' => $name,
		'link' => home_url( '/profile/' . $uid ),
		'org'  => $org,
		'goal' => $goal,
		'qr'   => $qr,
	) );
}

/**
 * Output the popup (markup + CSS + JS) on the seller registration page only.
 */
add_action( 'wp_footer', 'glow_seller_share_popup_output' );
function glow_seller_share_popup_output() {
	if ( ! ( is_page( 'register-as-a-seller' ) || is_page( 2242 ) ) ) {
		return;
	}
	$img_dir  = get_stylesheet_directory_uri() . '/images/';
	$ajax_url = admin_url( 'admin-ajax.php' );
	// Background artwork on the card's 1470x947 design grid: [x, y, size, colour, filled] sparkles, [x1, y1, x2, y2, colour] streaks.
	$c        = array( 'p' => '#ec1c6f', 'y' => '#f6b21b' );
	$sparkles = array( array( 102, 86, 80, 'p', 0 ), array( 55, 124, 30, 'y', 1 ), array( 250, 49, 18, 'p', 0 ), array( 652, 88, 22, 'y', 0 ), array( 687, 111, 30, 'y', 0 ), array( 1259, 114, 60, 'p', 0 ), array( 544, 243, 18, 'p', 1 ), array( 572, 271, 40, 'y', 0 ), array( 1302, 286, 35, 'y', 0 ), array( 1417, 284, 80, 'p', 0 ), array( 62, 791, 65, 'p', 0 ), array( 1342, 809, 45, 'y', 0 ), array( 1424, 894, 45, 'p', 0 ) );
	$streaks  = array( array( 589, 63, 617, 101, 'p' ), array( 447, 111, 477, 131, 'p' ), array( 755, 84, 770, 118, 'p' ), array( 812, 66, 827, 94, 'y' ), array( 824, 126, 845, 111, 'p' ), array( 989, 124, 1033, 93, 'p' ), array( 1100, 119, 1124, 103, 'y' ), array( 467, 326, 515, 296, 'p' ), array( 482, 364, 515, 361, 'p' ), array( 29, 306, 59, 356, 'p' ), array( 15, 376, 52, 391, 'p' ), array( 27, 436, 62, 426, 'p' ), array( 1362, 311, 1379, 328, 'y' ), array( 242, 58, 252, 52, 'p' ) );
	?>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Figtree:wght@500;600;700;800&display=swap">
	<style>
	#glow-share-pop{position:fixed;inset:0;z-index:99999;display:none;padding:18px;overflow-y:auto;
		background:radial-gradient(circle at 18% 18%,rgba(236,30,121,.38),transparent 42%),radial-gradient(circle at 84% 82%,rgba(123,97,255,.40),transparent 46%),rgba(16,10,26,.55);
		-webkit-backdrop-filter:blur(9px);backdrop-filter:blur(9px);opacity:0;transition:opacity .25s ease;}
	#glow-share-pop.open{display:flex;opacity:1;}
	#glow-share-pop *{box-sizing:border-box;}
	#glow-share-pop [hidden]{display:none!important;}
	/* Sizes are design pixels on a 1470x947 card: calc(var(--u) * N) scales them with the card width. */
	#glow-share-pop .gs-card{--u:calc(100cqw / 1470);--navy:#13234f;--pink:#ec1a6f;position:relative;container-type:inline-size;margin:auto;
		width:min(1100px,100%,calc((100vh - 36px) * 1470 / 947));width:min(1100px,100%,calc((100dvh - 36px) * 1470 / 947));aspect-ratio:1470/947;
		border-radius:24px;overflow:hidden;background:#fef8f2;box-shadow:0 34px 80px rgba(20,10,40,.45);color:var(--navy);
		font-family:Figtree,"Segoe UI",Arial,sans-serif;animation:gsIn .35s cubic-bezier(.2,.9,.3,1.25);}
	@keyframes gsIn{from{transform:translateY(18px) scale(.97);opacity:0}to{transform:none;opacity:1}}
	#glow-share-pop .gs-bg{position:absolute;inset:0;width:100%;height:100%;pointer-events:none;}
	#glow-share-pop .gs-phone{position:absolute;left:calc(var(--u) * 71);top:calc(var(--u) * 124);width:calc(var(--u) * 423);height:auto;max-width:none;margin:0;pointer-events:none;filter:drop-shadow(0 calc(var(--u) * 18) calc(var(--u) * 26) rgba(19,35,79,.22));}
	#glow-share-pop .gs-close{position:absolute;top:calc(var(--u) * 29);right:calc(var(--u) * 23);z-index:3;display:flex;align-items:center;justify-content:center;
		width:max(36px,calc(var(--u) * 70));height:max(36px,calc(var(--u) * 70));margin:0;padding:0;border:0;border-radius:50%;cursor:pointer;
		background:#fff;color:var(--navy);box-shadow:0 calc(var(--u) * 6) calc(var(--u) * 18) rgba(19,35,79,.16);transition:transform .25s ease;}
	#glow-share-pop .gs-close:hover{transform:rotate(90deg);}
	#glow-share-pop .gs-close svg{width:max(18px,calc(var(--u) * 34));height:max(18px,calc(var(--u) * 34));}

	#glow-share-pop .gs-body{position:absolute;top:0;bottom:0;left:calc(var(--u) * 512);right:calc(var(--u) * 33);display:flex;flex-direction:column;align-items:center;text-align:center;}
	#glow-share-pop .gs-title{display:flex;align-items:center;justify-content:center;gap:calc(var(--u) * 20);width:calc(100% + var(--u) * 46);min-height:calc(var(--u) * 98);
		margin:calc(var(--u) * 126) 0 0 calc(var(--u) * -46);padding:0;color:var(--navy);font:800 calc(var(--u) * 75)/1.05 Figtree,"Segoe UI",Arial,sans-serif;letter-spacing:-.025em;text-transform:none;}
	#glow-share-pop .gs-popper{flex:none;width:calc(var(--u) * 98);height:auto;margin:0;}
	#glow-share-pop .gs-title em{color:var(--pink);font-style:normal;}
	#glow-share-pop .gs-pill{margin-top:calc(var(--u) * 22);padding:0 calc(var(--u) * 42);min-height:calc(var(--u) * 60);display:flex;align-items:center;border-radius:999px;
		background:#fde1eb;font-size:calc(var(--u) * 30);font-weight:600;line-height:1.2;letter-spacing:-.01em;}
	#glow-share-pop .gs-sub{margin:calc(var(--u) * 28) 0 0;color:var(--navy);font-size:calc(var(--u) * 34);font-weight:600;line-height:1.2;letter-spacing:-.015em;}
	#glow-share-pop .gs-linkrow{display:flex;gap:calc(var(--u) * 20);width:100%;margin-top:calc(var(--u) * 36);}
	#glow-share-pop .gs-linkrow input{flex:1;min-width:0;height:calc(var(--u) * 90);margin:0;padding:0 calc(var(--u) * 28);border:max(1px,calc(var(--u) * 2)) solid #e7e1e4;border-radius:calc(var(--u) * 14);
		background:#fff;box-shadow:none;outline:none;color:var(--navy);font:500 calc(var(--u) * 32)/1 Figtree,"Segoe UI",Arial,sans-serif;}
	#glow-share-pop .gs-copy{flex:none;width:calc(var(--u) * 207);height:calc(var(--u) * 90);margin:0;padding:0;border:max(1.5px,calc(var(--u) * 2.5)) solid var(--pink);border-radius:calc(var(--u) * 14);
		background:#fff;color:var(--pink);font:700 calc(var(--u) * 40)/1 Figtree,"Segoe UI",Arial,sans-serif;cursor:pointer;white-space:nowrap;transition:background .15s,color .15s;}
	#glow-share-pop .gs-copy:hover,#glow-share-pop .gs-copy.done{background:var(--pink);color:#fff;}
	#glow-share-pop .gs-btns{display:grid;grid-template-columns:repeat(4,1fr);gap:calc(var(--u) * 18);width:100%;margin-top:calc(var(--u) * 23);}
	#glow-share-pop .gs-btn{display:flex;align-items:center;justify-content:center;gap:calc(var(--u) * 18);height:calc(var(--u) * 90);padding:0 calc(var(--u) * 10);
		border:max(1px,calc(var(--u) * 2)) solid #e9e3e6;border-radius:calc(var(--u) * 14);background:#fff;color:var(--navy);
		font-size:calc(var(--u) * 28);font-weight:600;text-decoration:none;cursor:pointer;transition:transform .16s,box-shadow .16s;}
	#glow-share-pop .gs-btn:hover{transform:translateY(-2px);box-shadow:0 calc(var(--u) * 10) calc(var(--u) * 22) rgba(19,35,79,.1);color:var(--navy);}
	#glow-share-pop .gs-btn svg{flex:none;width:calc(var(--u) * 50);height:calc(var(--u) * 50);}
	#glow-share-pop .gs-sms{display:flex;align-items:center;justify-content:center;gap:calc(var(--u) * 24);width:100%;height:calc(var(--u) * 110);margin-top:calc(var(--u) * 25);
		border-radius:calc(var(--u) * 18);background:linear-gradient(180deg,#f2207a,#e8156a);box-shadow:0 calc(var(--u) * 14) calc(var(--u) * 30) rgba(236,26,111,.28);
		color:#fff;font-size:calc(var(--u) * 44);font-weight:600;letter-spacing:-.01em;text-decoration:none;cursor:pointer;transition:transform .16s,filter .16s;}
	#glow-share-pop .gs-sms:hover{transform:translateY(-2px);filter:brightness(1.05);color:#fff;}
	#glow-share-pop .gs-sms svg{flex:none;width:calc(var(--u) * 60);height:calc(var(--u) * 60);}
	#glow-share-pop .gs-hint{margin:calc(var(--u) * 15) 0 0;color:#5f6070;font-size:calc(var(--u) * 28);line-height:1.2;}
	#glow-share-pop .gs-foot{display:flex;align-items:center;justify-content:center;gap:calc(var(--u) * 28);margin-top:calc(var(--u) * 46);font-size:calc(var(--u) * 30);font-weight:600;line-height:1.2;}
	#glow-share-pop .gs-qr-btn,#glow-share-pop .gs-later{margin:0;padding:calc(var(--u) * 6) calc(var(--u) * 4);border:0;background:none;color:var(--navy);font:inherit;cursor:pointer;}
	#glow-share-pop .gs-qr-btn:hover,#glow-share-pop .gs-later:hover{color:var(--pink);}
	#glow-share-pop .gs-qr{position:absolute;inset:0;z-index:4;display:flex;align-items:center;justify-content:center;padding:20px;background:rgba(254,248,242,.94);}
	#glow-share-pop .gs-qr-box{display:flex;flex-direction:column;align-items:center;gap:14px;padding:26px 30px;border-radius:20px;background:#fff;box-shadow:0 18px 40px rgba(19,35,79,.16);text-align:center;}
	#glow-share-pop .gs-qr-box img{display:block;width:min(240px,60vw);height:auto;margin:0;}
	#glow-share-pop .gs-qr-box p{margin:0;font-size:16px;font-weight:600;}
	#glow-share-pop .gs-qr-box button{margin:0;padding:10px 26px;border:2px solid var(--pink);border-radius:12px;background:#fff;color:var(--pink);font:700 15px Figtree,"Segoe UI",Arial,sans-serif;cursor:pointer;}
	#glow-share-pop button:focus-visible,#glow-share-pop a:focus-visible,#glow-share-pop .gs-later:focus-visible{outline:3px solid var(--navy);outline-offset:3px;}

	@media(max-width:760px){
		#glow-share-pop{padding:12px;}
		#glow-share-pop .gs-card{width:100%;max-width:480px;aspect-ratio:auto;max-height:calc(100vh - 24px);max-height:calc(100dvh - 24px);overflow-y:auto;border-radius:22px;
			background:radial-gradient(95px 62px at 0 0,#13234f 98%,transparent 100%),radial-gradient(170px 34px at 100% 0,#ec1a6f 98%,transparent 100%),radial-gradient(220px 46px at 72% 0,#f790b7 98%,transparent 100%),
			radial-gradient(130px 34px at 0 100%,#ec1a6f 98%,transparent 100%),radial-gradient(115px 40px at 100% 100%,#13234f 98%,transparent 100%),#fef8f2;}
		#glow-share-pop .gs-bg,#glow-share-pop .gs-phone{display:none;}
		#glow-share-pop .gs-close{top:12px;right:12px;width:40px;height:40px;}
		#glow-share-pop .gs-close svg{width:20px;height:20px;}
		#glow-share-pop .gs-body{position:static;padding:72px 18px 40px;}
		#glow-share-pop .gs-title{width:100%;min-height:0;margin:0;gap:10px;font-size:25px;line-height:1.15;}
		#glow-share-pop .gs-popper{width:46px;}
		#glow-share-pop .gs-title > span{text-align:left;text-wrap:balance;}
		#glow-share-pop .gs-pill{margin-top:14px;padding:8px 18px;min-height:0;font-size:14px;}
		#glow-share-pop .gs-sub{margin-top:14px;font-size:16px;line-height:1.35;}
		#glow-share-pop .gs-linkrow{gap:8px;margin-top:18px;}
		#glow-share-pop .gs-linkrow input{height:50px;padding:0 14px;border-width:1.5px;border-radius:12px;font-size:15px;}
		#glow-share-pop .gs-copy{width:auto;min-width:84px;height:50px;padding:0 16px;border-width:2px;border-radius:12px;font-size:17px;}
		#glow-share-pop .gs-btns{grid-template-columns:repeat(2,1fr);gap:10px;margin-top:10px;}
		#glow-share-pop .gs-btn{height:52px;gap:10px;border-width:1.5px;border-radius:12px;font-size:15px;}
		#glow-share-pop .gs-btn svg{width:26px;height:26px;}
		#glow-share-pop .gs-sms{height:58px;gap:12px;margin-top:12px;border-radius:14px;font-size:18px;}
		#glow-share-pop .gs-sms svg{width:30px;height:30px;}
		#glow-share-pop .gs-hint{margin-top:10px;font-size:14px;}
		#glow-share-pop .gs-foot{gap:14px;margin-top:16px;font-size:15px;}
	}
	</style>

	<div id="glow-share-pop" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="gs-title">
		<div class="gs-card">
			<svg class="gs-bg" viewBox="0 0 1470 947" preserveAspectRatio="none" aria-hidden="true" focusable="false">
				<defs><symbol id="gs-star" viewBox="0 0 24 24"><path d="M12 1.5C13.1 8.6 15.4 10.9 22.5 12C15.4 13.1 13.1 15.4 12 22.5C10.9 15.4 8.6 13.1 1.5 12C8.6 10.9 10.9 8.6 12 1.5Z" stroke-width="2.2" stroke-linejoin="round"/></symbol></defs>
				<path fill="#13234f" d="M0 0H382C330 25 262 58 190 95C120 131 52 184 0 236Z"/>
				<path fill="#f790b7" d="M790 0C858 28 930 46 1010 46C1085 46 1140 33 1205 18L1210 0Z"/>
				<path fill="#ec1f74" d="M1080 0C1132 17 1192 33 1262 37C1335 41 1395 52 1470 92V0Z"/>
				<path fill="#13234f" d="M1357 0C1402 16 1442 50 1470 100V0Z"/>
				<path fill="#f68fb5" d="M170 947C232 906 302 899 380 905C442 910 500 929 548 947Z"/>
				<path fill="#ec1a71" d="M0 831C62 836 140 860 200 890C242 911 270 930 292 947H0Z"/>
				<path fill="#ec1a71" d="M1470 722C1440 800 1380 860 1280 895C1210 919 1160 935 1120 947H1470Z"/>
				<path fill="#13234f" d="M1470 796C1430 840 1380 871 1330 900C1300 918 1280 934 1265 947H1470Z"/>
				<g fill="none" stroke-width="6" stroke-linecap="round">
					<?php foreach ( $streaks as $l ) : ?><line x1="<?php echo (int) $l[0]; ?>" y1="<?php echo (int) $l[1]; ?>" x2="<?php echo (int) $l[2]; ?>" y2="<?php echo (int) $l[3]; ?>" stroke="<?php echo esc_attr( $c[ $l[4] ] ); ?>"/><?php endforeach; ?>
				</g>
				<?php foreach ( $sparkles as $s ) : ?><use href="#gs-star" x="<?php echo $s[0] - $s[2] / 2; ?>" y="<?php echo $s[1] - $s[2] / 2; ?>" width="<?php echo (int) $s[2]; ?>" height="<?php echo (int) $s[2]; ?>" stroke="<?php echo esc_attr( $c[ $s[3] ] ); ?>" fill="<?php echo $s[4] ? esc_attr( $c[ $s[3] ] ) : 'none'; ?>"/><?php endforeach; ?>
			</svg>
			<img class="gs-phone" src="<?php echo esc_url( $img_dir . 'seller-share-phone.png' ); ?>" alt="" aria-hidden="true">
			<button class="gs-close" type="button" aria-label="Close">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M5 5l14 14M19 5L5 19"/></svg>
			</button>
			<div class="gs-body">
				<h2 class="gs-title" id="gs-title"><img class="gs-popper" src="<?php echo esc_url( $img_dir . 'seller-share-popper.png' ); ?>" alt="" aria-hidden="true"><span><span id="gs-hi">Your </span><em>store is live!</em></span></h2>
				<div class="gs-pill" id="gs-pill" hidden></div>
				<p class="gs-sub">Share your link to help your group reach their goal!</p>
				<div class="gs-linkrow">
					<input type="text" id="gs-link" readonly aria-label="Your store link">
					<button type="button" class="gs-copy" id="gs-copy">Copy</button>
				</div>
				<div class="gs-btns">
					<a class="gs-btn gs-fb" id="gs-fb" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="11" fill="#1877f2"/><path fill="#fff" d="M15.3 15.2l.5-3.2h-3.1V9.9c0-.9.4-1.7 1.8-1.7h1.4V5.5s-1.3-.2-2.5-.2c-2.6 0-4.2 1.5-4.2 4.3V12H6.4v3.2h2.8V23h3.5v-7.8z"/></svg>Facebook</a>
					<a class="gs-btn gs-wa" id="gs-wa" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><path fill="#25d366" d="M12 1.5A10.5 10.5 0 0 0 3 17.4L1.6 22.5l5.2-1.4A10.5 10.5 0 1 0 12 1.5z"/><path fill="#fff" d="M17.5 14.4c-.28-.15-1.68-.83-1.94-.92-.26-.1-.45-.14-.64.14s-.73.92-.9 1.11c-.16.19-.33.21-.61.07-.28-.14-1.2-.44-2.29-1.41-.85-.75-1.42-1.68-1.58-1.96-.17-.28-.02-.43.12-.57.13-.13.28-.33.42-.5.14-.16.19-.28.28-.47.1-.19.05-.35-.02-.5-.07-.14-.64-1.54-.88-2.11-.23-.55-.46-.47-.63-.48h-.55c-.19 0-.5.07-.76.35-.26.28-1 .98-1 2.38s1.02 2.76 1.16 2.95c.14.19 2 3.06 4.85 4.29.68.29 1.2.47 1.62.6.68.22 1.29.19 1.78.11.54-.08 1.68-.68 1.92-1.34.24-.66.24-1.23.17-1.34-.07-.12-.26-.19-.54-.33z"/></svg>WhatsApp</a>
					<a class="gs-btn gs-tw" id="gs-tw" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><path fill="#111" d="M18.9 2H22l-7.3 8.3L23 22h-6.8l-5.3-6.9L4.8 22H1.7l7.8-8.9L1 2h6.9l4.8 6.3L18.9 2zm-1.2 18h1.9L7.4 4H5.4l12.3 16z"/></svg>Post</a>
					<a class="gs-btn gs-em" id="gs-em"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="1.5" y="4.5" width="21" height="15" rx="2.4" fill="#6b7280"/><path d="M3.2 6.6l8.8 6.4 8.8-6.4" fill="none" stroke="#e5e7eb" stroke-width="1.7" stroke-linejoin="round"/></svg>Email</a>
				</div>
				<a class="gs-sms" id="gs-sms"><svg viewBox="0 0 24 24" aria-hidden="true"><path fill="#fff" d="M12 2.5C6.2 2.5 1.5 6.3 1.5 11c0 2.4 1.2 4.5 3.1 6.1L3.6 21.5l4.9-2.5c1.1.3 2.3.5 3.5.5 5.8 0 10.5-3.8 10.5-8.5S17.8 2.5 12 2.5z"/><path d="M7.4 9.3h9.2M7.4 12.7h6.2" stroke="#ec1a6f" stroke-width="1.9" stroke-linecap="round"/></svg>Text Your Family &amp; Friends</a>
				<p class="gs-hint">We’ll write the message for you</p>
				<div class="gs-foot">
					<button type="button" class="gs-qr-btn" id="gs-qr-btn" hidden>Show QR code</button>
					<span class="gs-dot" aria-hidden="true" hidden>•</span>
					<span class="gs-later" id="gs-later" role="button" tabindex="0">I’ll share later</span>
				</div>
			</div>
			<div class="gs-qr" id="gs-qr" hidden>
				<div class="gs-qr-box">
					<img id="gs-qr-img" alt="QR code for your store link">
					<p>Scan to open your store</p>
					<button type="button" id="gs-qr-close">Back</button>
				</div>
			</div>
		</div>
	</div>

	<script>
	(function(){
		var AJAX = <?php echo wp_json_encode( $ajax_url ); ?>;
		var pop  = document.getElementById('glow-share-pop');
		if(!pop) return;
		var opened = false;

		function openWith(name, link, info){
			if(opened) return; opened = true;
			var enc = encodeURIComponent(link);
			var txt = encodeURIComponent((name ? name+' | ' : '') + 'Support my GLOW fundraiser — shop with my link!');
			document.getElementById('gs-link').value = link;
			document.getElementById('gs-fb').href  = 'https://www.facebook.com/sharer.php?u='+enc;
			document.getElementById('gs-wa').href  = 'https://wa.me/?text='+txt+'%20'+enc;
			document.getElementById('gs-tw').href  = 'https://twitter.com/intent/tweet?text='+txt+'&url='+enc;
			document.getElementById('gs-em').href  = 'mailto:?subject='+encodeURIComponent('Shop my GLOW store')+'&body='+txt+'%20'+enc;
			document.getElementById('gs-sms').href = 'sms:?&body='+txt+'%20'+enc;
			fillDisplay(name, info || {});
			pop.classList.add('open'); pop.setAttribute('aria-hidden','false');
		}

		// Display-only: "Emma, your store is live!", fundraiser/goal pill and the QR code link.
		function fillDisplay(name, info){
			var first = String(name || '').trim().split(/\s+/)[0];
			document.getElementById('gs-hi').textContent = first ? first + ', your ' : 'Your ';
			var parts = [], goal = String(info.goal || '').replace(/[^0-9.]/g, '');
			if(info.org){ parts.push(info.org); }
			if(goal && !isNaN(goal)){ parts.push('Goal: $' + Number(goal).toLocaleString('en-US', {maximumFractionDigits: 2})); }
			var pill = document.getElementById('gs-pill');
			pill.textContent = parts.join(' · '); pill.hidden = !parts.length;
			if(info.qr){
				document.getElementById('gs-qr-img').src = info.qr;
				document.getElementById('gs-qr-btn').hidden = false;
				pop.querySelector('.gs-dot').hidden = false;
			}
		}

		function fetchAndOpen(){
			if(opened) return;
			var fd = new FormData(); fd.append('action','glow_seller_share_links');
			fetch(AJAX, {method:'POST', body:fd, credentials:'same-origin'})
				.then(function(r){return r.json();})
				.then(function(res){ if(res && res.success && res.data && res.data.link){ openWith(res.data.name, res.data.link, res.data); } })
				.catch(function(){});
		}

		// Detect UR's "User successfully registered." message by POLLING the page text —
		// robust whether UR adds a node or just updates existing text. The AJAX gate in
		// fetchAndOpen only opens the popup for a real just-registered seller, so this is safe.
		function gsSuccess(){ var t=(document.body.innerText||document.body.textContent||''); return /successfully\s+registered|registered\s+successfully/i.test(t); }
		if(gsSuccess()){ setTimeout(fetchAndOpen, 500); }
		var gsIv = setInterval(function(){
			if(opened){ clearInterval(gsIv); return; }
			if(gsSuccess()){ clearInterval(gsIv); setTimeout(fetchAndOpen, 500); }
		}, 500);
		setTimeout(function(){ clearInterval(gsIv); }, 180000); // stop after 3 min
		// Bonus: UR's own jQuery success events (fire immediately if available).
		if(window.jQuery){ jQuery(document).on('user_registration_frontend_form_success user_registration_after_form_submit', function(){ setTimeout(fetchAndOpen, 500); }); }

		// UR jumps the page to the form's success message right after a successful submit.
		// Undo that jump in the same tick (before the browser paints) and open the popup straight away.
		if(window.jQuery){
			var gsScrollY = null;
			jQuery(document).on('user_registration_frontend_before_ajax_complete_success_message', function(){ gsScrollY = window.pageYOffset; });
			jQuery(document).on('user_registration_frontend_after_ajax_complete', function(e, responseText, type){
				if(type === 'message' && gsScrollY !== null){
					window.scrollTo({top: gsScrollY, left: window.pageXOffset, behavior: 'instant'});
					fetchAndOpen();
				}
				gsScrollY = null;
			});
		}

		// controls
		var qr = document.getElementById('gs-qr');
		function closePop(){ pop.classList.remove('open'); pop.setAttribute('aria-hidden','true'); qr.hidden = true; }
		pop.querySelector('.gs-close').addEventListener('click', closePop);
		document.getElementById('gs-later').addEventListener('click', closePop);
		document.getElementById('gs-later').addEventListener('keydown', function(e){ if(e.key==='Enter' || e.key===' '){ e.preventDefault(); closePop(); } });
		document.getElementById('gs-qr-btn').addEventListener('click', function(){ qr.hidden = false; });
		document.getElementById('gs-qr-close').addEventListener('click', function(){ qr.hidden = true; });
		pop.addEventListener('click', function(e){ if(e.target===pop){ closePop(); } });
		document.getElementById('gs-copy').addEventListener('click', function(){
			var f=document.getElementById('gs-link'); f.select(); f.setSelectionRange(0,99999);
			var done=function(){ var b=document.getElementById('gs-copy'); b.textContent='Copied!'; b.classList.add('done'); setTimeout(function(){ b.textContent='Copy'; b.classList.remove('done'); },1800); };
			if(navigator.clipboard){ navigator.clipboard.writeText(f.value).then(done, function(){ try{document.execCommand('copy');done();}catch(e){} }); } else { try{document.execCommand('copy');done();}catch(e){} }
		});
	})();
	</script>
	<?php
}
