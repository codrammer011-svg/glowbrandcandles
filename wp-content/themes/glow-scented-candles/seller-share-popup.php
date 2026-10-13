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
	wp_send_json_success( array(
		'name' => $name,
		'link' => home_url( '/profile/' . $uid ),
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
	$banner   = get_stylesheet_directory_uri() . '/images/seller-share-popup.png'; // replace with your 1200x600 image
	$ajax_url = admin_url( 'admin-ajax.php' );
	?>
	<style>
	#glow-share-pop{position:fixed;inset:0;z-index:99999;display:none;align-items:center;justify-content:center;padding:18px;
		background:radial-gradient(circle at 18% 18%,rgba(236,30,121,.38),transparent 42%),radial-gradient(circle at 84% 82%,rgba(123,97,255,.40),transparent 46%),rgba(16,10,26,.55);
		-webkit-backdrop-filter:blur(9px);backdrop-filter:blur(9px);opacity:0;transition:opacity .25s ease;}
	#glow-share-pop.open{display:flex;opacity:1;}
	#glow-share-pop *{box-sizing:border-box;}
	#glow-share-pop .gs-card{position:relative;width:100%;max-width:540px;border-radius:28px;overflow:hidden;
		background:rgba(255,255,255,.62);-webkit-backdrop-filter:blur(34px) saturate(185%);backdrop-filter:blur(34px) saturate(185%);
		border:1px solid rgba(255,255,255,.65);box-shadow:0 34px 80px rgba(60,8,55,.42),inset 0 1px 0 rgba(255,255,255,.75);
		animation:gsIn .35s cubic-bezier(.2,.9,.3,1.25);}
	@keyframes gsIn{from{transform:translateY(18px) scale(.97);opacity:0}to{transform:none;opacity:1}}
	#glow-share-pop .gs-close{position:absolute;top:14px;right:14px;z-index:3;width:40px;height:40px;border-radius:50%;padding:0;cursor:pointer;
		display:flex;align-items:center;justify-content:center;color:#17264c;
		background:rgba(255,255,255,.4);-webkit-backdrop-filter:blur(12px);backdrop-filter:blur(12px);
		border:1px solid rgba(255,255,255,.7);box-shadow:0 6px 18px rgba(0,0,0,.16);transition:transform .25s ease,background .2s;}
	#glow-share-pop .gs-close:hover{background:rgba(255,255,255,.7);transform:rotate(90deg);}
	#glow-share-pop .gs-banner{width:100%;display:block;aspect-ratio:2/1;object-fit:cover;background:linear-gradient(135deg,#ffd9ea,#e9dcff);}
	#glow-share-pop .gs-body{padding:26px 28px 26px;text-align:center;}
	#glow-share-pop .gs-body h2{margin:0 0 8px;font-size:25px;font-weight:800;color:#17264c;letter-spacing:-.3px;line-height:1.2;}
	#glow-share-pop .gs-sub{margin:0 0 20px;color:#4a4658;font-size:15px;line-height:1.55;}
	#glow-share-pop .gs-sub b{color:#ec1e79;}
	#glow-share-pop .gs-linkrow{display:flex;gap:10px;margin-bottom:18px;}
	#glow-share-pop .gs-linkrow input{flex:1;min-width:0;border:1px solid rgba(255,255,255,.75);border-radius:14px;padding:13px 15px;font-size:14px;color:#2c2c38;
		background:rgba(255,255,255,.5);-webkit-backdrop-filter:blur(6px);backdrop-filter:blur(6px);box-shadow:inset 0 1px 3px rgba(0,0,0,.06);outline:none;}
	#glow-share-pop .gs-copy{border:1px solid rgba(255,255,255,.35);border-radius:14px;padding:0 22px;font-weight:700;color:#fff;cursor:pointer;white-space:nowrap;
		background:linear-gradient(135deg,#ff3d92,#d6006e);box-shadow:0 8px 22px rgba(214,0,110,.4);transition:transform .15s,filter .15s;}
	#glow-share-pop .gs-copy:hover{transform:translateY(-1px);filter:brightness(1.06);}
	#glow-share-pop .gs-copy.done{background:linear-gradient(135deg,#37c172,#2f9e60);box-shadow:0 8px 22px rgba(47,158,96,.4);}
	#glow-share-pop .gs-btns{display:flex;flex-wrap:wrap;gap:10px;justify-content:center;margin-bottom:16px;}
	#glow-share-pop .gs-btn{flex:1 1 auto;min-width:92px;display:flex;align-items:center;justify-content:center;gap:7px;padding:13px 10px;border-radius:15px;font-weight:700;font-size:13.5px;color:#fff;text-decoration:none;
		border:1px solid rgba(255,255,255,.4);-webkit-backdrop-filter:blur(6px);backdrop-filter:blur(6px);box-shadow:0 7px 18px rgba(0,0,0,.14),inset 0 1px 0 rgba(255,255,255,.25);transition:transform .16s,box-shadow .16s,filter .16s;}
	#glow-share-pop .gs-btn:hover{transform:translateY(-2px);box-shadow:0 12px 24px rgba(0,0,0,.2);filter:brightness(1.06);}
	#glow-share-pop .gs-btn svg{width:16px;height:16px;flex:0 0 16px;}
	#glow-share-pop .gs-fb{background:linear-gradient(135deg,rgba(45,135,255,.96),rgba(24,119,242,.82));}
	#glow-share-pop .gs-wa{background:linear-gradient(135deg,rgba(48,224,122,.96),rgba(37,211,102,.82));}
	#glow-share-pop .gs-tw{background:linear-gradient(135deg,rgba(44,44,50,.96),rgba(17,17,17,.85));}
	#glow-share-pop .gs-em{background:linear-gradient(135deg,rgba(138,148,166,.96),rgba(107,114,128,.85));}
	#glow-share-pop .gs-sms{background:linear-gradient(135deg,rgba(48,176,255,.96),rgba(10,162,255,.85));}
	#glow-share-pop .gs-later{display:inline-block;color:#8a8494;font-size:13px;cursor:pointer;padding:7px 14px;border-radius:11px;transition:background .2s,color .2s;}
	#glow-share-pop .gs-later:hover{background:rgba(255,255,255,.45);color:#5a5568;}
	@media(max-width:520px){#glow-share-pop .gs-btn{min-width:calc(50% - 5px);}#glow-share-pop .gs-body h2{font-size:22px;}}
	</style>

	<div id="glow-share-pop" aria-hidden="true" role="dialog" aria-modal="true">
		<div class="gs-card">
			<button class="gs-close" type="button" aria-label="Close">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
			</button>
			<img class="gs-banner" src="<?php echo esc_url( $banner ); ?>" alt="" onerror="this.style.display='none'">
			<div class="gs-body">
				<h2>🎉 You're a GLOW Seller!</h2>
				<p class="gs-sub">Don't wait for the dashboard — <b>share your link now</b>. The sooner you share, the sooner the orders come in!</p>
				<div class="gs-linkrow">
					<input type="text" id="gs-link" readonly>
					<button type="button" class="gs-copy" id="gs-copy">Copy</button>
				</div>
				<div class="gs-btns">
					<a class="gs-btn gs-fb" id="gs-fb" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 10-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.2c-1.2 0-1.6.76-1.6 1.53V12h2.7l-.43 2.9h-2.27v7A10 10 0 0022 12z"/></svg>Facebook</a>
					<a class="gs-btn gs-wa" id="gs-wa" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 14.4c-.28-.15-1.68-.83-1.94-.92-.26-.1-.45-.14-.64.14s-.73.92-.9 1.11c-.16.19-.33.21-.61.07-.28-.14-1.2-.44-2.29-1.41-.85-.75-1.42-1.68-1.58-1.96-.17-.28-.02-.43.12-.57.13-.13.28-.33.42-.5.14-.16.19-.28.28-.47.1-.19.05-.35-.02-.5-.07-.14-.64-1.54-.88-2.11-.23-.55-.46-.47-.63-.48h-.55c-.19 0-.5.07-.76.35-.26.28-1 .98-1 2.38s1.02 2.76 1.16 2.95c.14.19 2 3.06 4.85 4.29.68.29 1.2.47 1.62.6.68.22 1.29.19 1.78.11.54-.08 1.68-.68 1.92-1.34.24-.66.24-1.23.17-1.34-.07-.12-.26-.19-.54-.33zM12 2a10 10 0 00-8.5 15.3L2 22l4.8-1.3A10 10 0 1012 2z"/></svg>WhatsApp</a>
					<a class="gs-btn gs-tw" id="gs-tw" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 2H22l-7.3 8.3L23 22h-6.8l-5.3-6.9L4.8 22H1.7l7.8-8.9L1 2h6.9l4.8 6.3L18.9 2zm-1.2 18h1.9L7.4 4H5.4l12.3 16z"/></svg>Post</a>
					<a class="gs-btn gs-em" id="gs-em"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm0 2v.4l8 5 8-5V6H4zm16 2.3l-7.4 4.6a1 1 0 01-1.2 0L4 8.3V18h16V8.3z"/></svg>Email</a>
					<a class="gs-btn gs-sms" id="gs-sms"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M4 4h16a2 2 0 012 2v9a2 2 0 01-2 2H8l-4 4V6a2 2 0 012-2zm3 6h2v2H7v-2zm4 0h2v2h-2v-2zm4 0h2v2h-2v-2z"/></svg>Text</a>
				</div>
				<span class="gs-later" id="gs-later">I'll share later</span>
			</div>
		</div>
	</div>

	<script>
	(function(){
		var AJAX = <?php echo wp_json_encode( $ajax_url ); ?>;
		var pop  = document.getElementById('glow-share-pop');
		if(!pop) return;
		var opened = false;

		function openWith(name, link){
			if(opened) return; opened = true;
			var enc = encodeURIComponent(link);
			var txt = encodeURIComponent((name ? name+' | ' : '') + 'Support my GLOW fundraiser — shop with my link!');
			document.getElementById('gs-link').value = link;
			document.getElementById('gs-fb').href  = 'https://www.facebook.com/sharer.php?u='+enc;
			document.getElementById('gs-wa').href  = 'https://wa.me/?text='+txt+'%20'+enc;
			document.getElementById('gs-tw').href  = 'https://twitter.com/intent/tweet?text='+txt+'&url='+enc;
			document.getElementById('gs-em').href  = 'mailto:?subject='+encodeURIComponent('Shop my GLOW store')+'&body='+txt+'%20'+enc;
			document.getElementById('gs-sms').href = 'sms:?&body='+txt+'%20'+enc;
			pop.classList.add('open'); pop.setAttribute('aria-hidden','false');
		}

		function fetchAndOpen(){
			if(opened) return;
			var fd = new FormData(); fd.append('action','glow_seller_share_links');
			fetch(AJAX, {method:'POST', body:fd, credentials:'same-origin'})
				.then(function(r){return r.json();})
				.then(function(res){ if(res && res.success && res.data && res.data.link){ openWith(res.data.name, res.data.link); } })
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

		// controls
		function closePop(){ pop.classList.remove('open'); pop.setAttribute('aria-hidden','true'); }
		pop.querySelector('.gs-close').addEventListener('click', closePop);
		document.getElementById('gs-later').addEventListener('click', closePop);
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
