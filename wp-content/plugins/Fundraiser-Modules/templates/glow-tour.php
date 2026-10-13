<?php
/**
 * Glow — Seller onboarding: welcome modal + guided tour.
 *
 * Drop next to glow-order-grid.php and include it at the very END of any
 * seller order template (after the grid has been output):
 *
 *     include plugin_dir_path( __FILE__ ) . 'glow-tour.php';
 *
 * Everything the tour points at is defined in $glow_tour_steps below.
 * Change the `selector` values to match whatever glow-order-grid.php renders.
 * A step whose selector matches nothing is skipped silently, so a wrong
 * selector never breaks the page.
 *
 * Bump GLOW_TOUR_VERSION to re-show the modal to sellers who already saw it.
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'GLOW_TOUR_VERSION' ) ) {
	define( 'GLOW_TOUR_VERSION', '1' );
}

/* -------------------------------------------------------------------------
 * Icons
 * ---------------------------------------------------------------------- */
if ( ! function_exists( 'glow_tour_icon' ) ) {
	function glow_tour_icon( $name ) {
		$paths = array(
			'bag'       => '<path d="M5 8h14l-1.1 12H6.1L5 8Z"/><path d="M9 8V6.2a3 3 0 0 1 6 0V8"/><rect x="9.6" y="12" width="4.8" height="4" rx="1"/>',
			'user'      => '<circle cx="12" cy="8.4" r="3.3"/><path d="M5.5 19.2a6.5 6.5 0 0 1 13 0"/>',
			'candle'    => '<rect x="8" y="11" width="8" height="9" rx="2"/><path d="M12 11V8.6"/><path d="M12 8.6c1.5-1 1.5-2.7 0-4.1-1.5 1.4-1.5 3.1 0 4.1Z"/>',
			'clipboard' => '<rect x="5" y="4.5" width="14" height="16" rx="2.2"/><path d="M9.2 4.5h5.6v2.4H9.2z"/><path d="M8.6 10.5h1M12 10.5h4M8.6 14h1M12 14h4M8.6 17.4h1M12 17.4h4"/>',
			'cart'      => '<path d="M3 4.6h2l2.3 10.4h10"/><path d="M5.7 7.6h13.6l-1.6 6.2H7"/><circle cx="9.6" cy="18.9" r="1.4"/><circle cx="16.9" cy="18.9" r="1.4"/>',
		);

		if ( empty( $paths[ $name ] ) ) {
			return '';
		}

		return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" '
			. 'stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'
			. $paths[ $name ] . '</svg>';
	}
}

/* -------------------------------------------------------------------------
 * The five steps — edit `selector`, `title` and `text` to taste.
 * `label` is what shows in the welcome modal list.
 * `color` is one of: pink | orange | purple | amber
 * ---------------------------------------------------------------------- */
$glow_tour_steps = array(

	array(
		'icon'     => 'bag',
		'color'    => 'pink',
		'label'    => 'Choose a product category',
		'selector' => '.glow-order__nav, .glow-catalog-nav, #glow-category-nav',
		'title'    => 'Pick a category first',
		'text'     => 'Jump straight to the section of the brochure you are entering — Classic Candles, Signature 2 Wick, Wax Melts and so on.',
	),

	array(
		'icon'     => 'user',
		'color'    => 'orange',
		'label'    => 'Enter your name',
		'selector' => '.glow-seller-name, #glow_seller_name, input[name="seller_name"]',
		'title'    => 'Add the customer name',
		'text'     => 'Every brochure order is saved under a name so your chairperson knows exactly whose order it is.',
	),

	array(
		'icon'     => 'candle',
		'color'    => 'purple',
		'label'    => 'Select the products',
		'selector' => '.glow-product-grid, .glow-order__grid, .products',
		'title'    => 'Tick the products ordered',
		'text'     => 'Scroll the grid and select each product from the brochure. You can move between categories as many times as you need.',
	),

	array(
		'icon'     => 'clipboard',
		'color'    => 'amber',
		'label'    => 'Enter each product quantity',
		'selector' => '.glow-qty, input.qty, input[type="number"]',
		'title'    => 'Set the quantity',
		'text'     => 'Type how many of each item were ordered. Leave anything they did not buy at zero.',
	),

	array(
		'icon'     => 'cart',
		'color'    => 'pink',
		'label'    => 'Click Add Order to Cart',
		'selector' => '.glow-add-order, #glow_add_order_to_cart, .glow-order__submit',
		'title'    => 'Add the order',
		'text'     => 'Your order is saved and stays editable until you finish. Repeat for the next customer on your brochure.',
	),

);

$glow_tour_steps = apply_filters( 'glow_tour_steps', $glow_tour_steps );

/* Data handed to JS: only what the tour needs. */
$glow_tour_js = array();
foreach ( $glow_tour_steps as $i => $s ) {
	$glow_tour_js[] = array(
		'n'        => $i + 1,
		'selector' => $s['selector'],
		'title'    => $s['title'],
		'text'     => $s['text'],
	);
}
?>

<style id="glow-tour-css">
.glow-tour-scope{--g-pink:#ec1e79;--g-orange:#f26522;--g-purple:#7b4fd8;--g-amber:#fba919;--g-navy:#12225c;--g-cream:#fbf7f3;--g-ink:#1d2b5e}
.glow-tour-scope *{box-sizing:border-box}

/* ---------- welcome modal ---------- */
.glow-welcome-backdrop{position:fixed;inset:0;z-index:99990;display:flex;align-items:center;justify-content:center;padding:20px;background:rgba(38,38,38,.72);backdrop-filter:blur(2px);opacity:0;transition:opacity .25s ease}
.glow-welcome-backdrop.is-open{opacity:1}
.glow-welcome-backdrop[hidden]{display:none}

.glow-welcome-stack{position:relative;width:100%;max-width:520px;transform:scale(.94);transition:transform .28s cubic-bezier(.2,.9,.3,1.2)}
.glow-welcome-backdrop.is-open .glow-welcome-stack{transform:scale(1)}
.glow-welcome-stack::before,.glow-welcome-stack::after{content:"";position:absolute;inset:14px -10px -18px 14px;border-radius:26px;z-index:0}
.glow-welcome-stack::before{background:#9b6bf0;transform:rotate(-2.6deg);inset:-16px 24px 24px -14px}
.glow-welcome-stack::after{background:#f8579b;transform:rotate(2.2deg);inset:24px -16px -16px 24px}

.glow-welcome{position:relative;z-index:1;background:var(--g-cream);border-radius:26px;padding:34px 30px 24px;box-shadow:0 26px 60px rgba(0,0,0,.28);max-height:86vh;overflow-y:auto}

.glow-welcome__close{position:absolute;top:16px;right:16px;width:42px;height:42px;border:0;border-radius:50%;background:#fbe4ec;color:var(--g-navy);font-size:22px;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .18s,transform .18s}
.glow-welcome__close:hover{background:#f7cfdd;transform:rotate(90deg)}

.glow-welcome__head{display:flex;align-items:flex-start;gap:10px;padding-right:44px}
.glow-welcome__heart{flex:0 0 auto;margin-top:26px;color:var(--g-pink)}
.glow-welcome__title{margin:0;font-size:clamp(30px,7vw,42px);line-height:1.06;font-weight:800;letter-spacing:-.02em;color:var(--g-navy)}
.glow-welcome__rule{display:block;margin:6px 0 0;width:min(280px,70%);color:var(--g-pink)}

.glow-welcome__lede{margin:14px 0 18px;font-size:16px;font-weight:600;color:var(--g-ink);text-align:center}

.glow-welcome__steps{list-style:none;margin:0;padding:0}
.glow-welcome__steps li{display:flex;align-items:center;gap:14px;padding:11px 0;border-bottom:1.5px dotted #ddd6cd}
.glow-welcome__steps li:last-child{border-bottom:0}
.glow-welcome__num{flex:0 0 auto;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:17px}
.glow-welcome__ico{flex:0 0 auto;width:48px;height:48px;border-radius:50%;display:flex;align-items:center;justify-content:center}
.glow-welcome__ico svg{width:26px;height:26px}
.glow-welcome__label{font-size:17px;font-weight:700;color:var(--g-navy)}

.g-c-pink .glow-welcome__num,.g-c-pink.glow-welcome__num{background:var(--g-pink)}
.g-c-orange .glow-welcome__num,.g-c-orange.glow-welcome__num{background:var(--g-orange)}
.g-c-purple .glow-welcome__num,.g-c-purple.glow-welcome__num{background:var(--g-purple)}
.g-c-amber .glow-welcome__num,.g-c-amber.glow-welcome__num{background:var(--g-amber)}
.g-c-pink .glow-welcome__ico{background:#fde9f1;color:var(--g-pink)}
.g-c-orange .glow-welcome__ico{background:#fdece1;color:var(--g-orange)}
.g-c-purple .glow-welcome__ico{background:#ece5fb;color:var(--g-purple)}
.g-c-amber .glow-welcome__ico{background:#fdf1dd;color:var(--g-amber)}

.glow-welcome__cta{display:flex;align-items:center;justify-content:center;gap:12px;width:100%;margin-top:20px;padding:17px 20px;border:0;border-radius:12px;background:linear-gradient(90deg,var(--g-pink) 0%,#f4416a 45%,#f78a1e 100%);color:#fff;font-size:22px;font-weight:800;letter-spacing:.03em;text-transform:uppercase;cursor:pointer;box-shadow:0 8px 20px rgba(236,30,121,.3);transition:transform .16s,box-shadow .16s}
.glow-welcome__cta:hover{transform:translateY(-2px);box-shadow:0 12px 26px rgba(236,30,121,.38)}
.glow-welcome__cta svg{width:26px;height:26px}

.glow-welcome__foot{display:flex;align-items:center;gap:12px;margin-top:18px;color:var(--g-navy)}
.glow-welcome__foot span{flex:0 0 auto;font-size:13px;font-weight:700;letter-spacing:.22em;text-transform:uppercase}
.glow-welcome__foot i{flex:1 1 auto;height:2px;border-radius:2px;background:#c9b8f0}
.glow-welcome__star{flex:0 0 auto;width:22px;height:22px}
.glow-welcome__star--l{color:var(--g-pink)}
.glow-welcome__star--r{color:var(--g-purple)}

/* ---------- spotlight tour ---------- */
.glow-tour-spot{position:fixed;z-index:99991;border-radius:14px;pointer-events:none;box-shadow:0 0 0 9999px rgba(18,34,92,.66);transition:top .3s cubic-bezier(.4,0,.2,1),left .3s cubic-bezier(.4,0,.2,1),width .3s,height .3s;opacity:0}
.glow-tour-spot.is-on{opacity:1}
.glow-tour-spot::after{content:"";position:absolute;inset:-3px;border:2.5px dashed rgba(255,255,255,.85);border-radius:16px}

.glow-tour-pop{position:fixed;z-index:99992;width:min(340px,calc(100vw - 32px));background:var(--g-cream);border-radius:18px;padding:20px 22px 18px;box-shadow:0 20px 46px rgba(0,0,0,.3);opacity:0;transform:translateY(6px);transition:opacity .22s,transform .22s}
.glow-tour-pop.is-on{opacity:1;transform:none}
.glow-tour-pop__n{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:50%;background:var(--g-pink);color:#fff;font-weight:800;font-size:15px;margin-bottom:10px}
.glow-tour-pop__t{margin:0 0 6px;font-size:20px;font-weight:800;color:var(--g-navy);letter-spacing:-.01em}
.glow-tour-pop__d{margin:0;font-size:15px;line-height:1.5;color:#43507d}
.glow-tour-pop__bar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:16px}
.glow-tour-pop__dots{display:flex;gap:6px}
.glow-tour-pop__dots b{width:8px;height:8px;border-radius:50%;background:#ddd4c8}
.glow-tour-pop__dots b.on{background:var(--g-pink);transform:scale(1.25)}
.glow-tour-pop__btns{display:flex;gap:8px}
.glow-tour-btn{border:0;border-radius:9px;padding:10px 16px;font-size:14px;font-weight:700;cursor:pointer;font-family:inherit}
.glow-tour-btn--ghost{background:transparent;color:#6b7396}
.glow-tour-btn--ghost:hover{color:var(--g-navy)}
.glow-tour-btn--next{background:linear-gradient(90deg,var(--g-pink),#f78a1e);color:#fff;box-shadow:0 5px 14px rgba(236,30,121,.3)}
.glow-tour-btn--next:hover{filter:brightness(1.05)}
.glow-tour-skip{position:fixed;z-index:99992;bottom:20px;right:20px;background:rgba(255,255,255,.14);color:#fff;border:1px solid rgba(255,255,255,.35);border-radius:8px;padding:9px 14px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit}
.glow-tour-skip:hover{background:rgba(255,255,255,.24)}

body.glow-tour-lock{overflow:hidden}

@media (max-width:600px){
	.glow-welcome{padding:28px 20px 20px;border-radius:22px}
	.glow-welcome__ico{width:40px;height:40px}
	.glow-welcome__label{font-size:15px}
	.glow-welcome__cta{font-size:18px;padding:15px}
	.glow-tour-pop{width:calc(100vw - 24px)}
}
@media (prefers-reduced-motion:reduce){
	.glow-tour-scope *,.glow-tour-spot,.glow-tour-pop{transition:none!important}
}
</style>

<div class="glow-tour-scope">
	<div class="glow-welcome-backdrop" id="glowWelcome" hidden>
		<div class="glow-welcome-stack">
			<div class="glow-welcome" role="dialog" aria-modal="true" aria-labelledby="glowWelcomeTitle">

				<button type="button" class="glow-welcome__close" data-glow-close aria-label="Close">&times;</button>

				<div class="glow-welcome__head">
					<svg class="glow-welcome__heart" width="52" height="52" viewBox="0 0 52 52" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true">
						<path d="M26 44c-6-4.4-13-9.6-13-16.4a7.2 7.2 0 0 1 13-4.2 7.2 7.2 0 0 1 13 4.2C39 34.4 32 39.6 26 44Z" transform="translate(-4 -6) scale(.86)"/>
						<path d="M9 8 5 3M4 13H0M14 5l1-5"/>
					</svg>
					<div>
						<h2 class="glow-welcome__title" id="glowWelcomeTitle">Welcome,<br>Glow Seller!</h2>
						<svg class="glow-welcome__rule" viewBox="0 0 280 12" fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round" aria-hidden="true">
							<path d="M4 8.5C70 2.5 200 2 276 6"/>
						</svg>
					</div>
				</div>

				<p class="glow-welcome__lede">Entering your brochure orders is easy. Here&rsquo;s what you&rsquo;ll do:</p>

				<ol class="glow-welcome__steps">
					<?php foreach ( $glow_tour_steps as $i => $step ) : ?>
						<li class="g-c-<?php echo esc_attr( $step['color'] ); ?>">
							<span class="glow-welcome__num"><?php echo (int) ( $i + 1 ); ?></span>
							<span class="glow-welcome__ico"><?php echo glow_tour_icon( $step['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
							<span class="glow-welcome__label"><?php echo esc_html( $step['label'] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ol>

				<button type="button" class="glow-welcome__cta" data-glow-start>
					Start the Tour
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12h15M13 6l6 6-6 6"/></svg>
				</button>

				<div class="glow-welcome__foot">
					<svg class="glow-welcome__star glow-welcome__star--l" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" aria-hidden="true"><path d="M12 2.5 14.8 9 22 9.6l-5.4 4.7 1.7 7-6.3-3.8-6.3 3.8 1.7-7L2 9.6 9.2 9Z"/></svg>
					<i></i><span>5 Simple Steps</span><i></i>
					<svg class="glow-welcome__star glow-welcome__star--r" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" aria-hidden="true"><path d="M12 2.5 14.8 9 22 9.6l-5.4 4.7 1.7 7-6.3-3.8-6.3 3.8 1.7-7L2 9.6 9.2 9Z"/></svg>
				</div>

			</div>
		</div>
	</div>
</div>

<script id="glow-tour-js">
(function () {
	'use strict';

	var STEPS   = <?php echo wp_json_encode( $glow_tour_js ); ?>;
	var KEY     = 'glowTourSeen_v<?php echo esc_js( GLOW_TOUR_VERSION ); ?>';
	var backdrop = document.getElementById('glowWelcome');
	if (!backdrop) { return; }

	var scope = backdrop.closest('.glow-tour-scope') || document.body;
	var spot, pop, skipBtn, idx = 0, live = [];

	/* ---------- storage (private mode safe) ---------- */
	function seen() { try { return localStorage.getItem(KEY) === '1'; } catch (e) { return false; } }
	function markSeen() { try { localStorage.setItem(KEY, '1'); } catch (e) {} }

	/* ---------- welcome modal ---------- */
	function openWelcome() {
		backdrop.hidden = false;
		document.body.classList.add('glow-tour-lock');
		requestAnimationFrame(function () { backdrop.classList.add('is-open'); });
		var cta = backdrop.querySelector('[data-glow-start]');
		if (cta) { cta.focus(); }
	}

	function closeWelcome() {
		backdrop.classList.remove('is-open');
		document.body.classList.remove('glow-tour-lock');
		setTimeout(function () { backdrop.hidden = true; }, 250);
		markSeen();
	}

	backdrop.addEventListener('click', function (e) {
		if (e.target === backdrop) { closeWelcome(); }
		if (e.target.closest('[data-glow-close]')) { closeWelcome(); }
		if (e.target.closest('[data-glow-start]')) { closeWelcome(); setTimeout(startTour, 260); }
	});

	/* ---------- tour ---------- */
	function resolve() {
		live = [];
		STEPS.forEach(function (s) {
			var el = document.querySelector(s.selector);
			if (el && el.offsetParent !== null) { live.push({ el: el, data: s }); }
		});
		return live.length;
	}

	function build() {
		spot = document.createElement('div');
		spot.className = 'glow-tour-spot';

		pop = document.createElement('div');
		pop.className = 'glow-tour-pop';
		pop.setAttribute('role', 'dialog');
		pop.setAttribute('aria-live', 'polite');

		skipBtn = document.createElement('button');
		skipBtn.type = 'button';
		skipBtn.className = 'glow-tour-skip';
		skipBtn.textContent = 'Skip tour';
		skipBtn.addEventListener('click', endTour);

		scope.appendChild(spot);
		scope.appendChild(pop);
		scope.appendChild(skipBtn);

		pop.addEventListener('click', function (e) {
			if (e.target.closest('[data-glow-next]')) { go(idx + 1); }
			if (e.target.closest('[data-glow-prev]')) { go(idx - 1); }
			if (e.target.closest('[data-glow-end]'))  { endTour(); }
		});
	}

	function render() {
		var s = live[idx].data;
		var last = idx === live.length - 1;
		var dots = live.map(function (_, i) {
			return '<b class="' + (i === idx ? 'on' : '') + '"></b>';
		}).join('');

		pop.innerHTML =
			'<span class="glow-tour-pop__n">' + (idx + 1) + '</span>' +
			'<h3 class="glow-tour-pop__t"></h3>' +
			'<p class="glow-tour-pop__d"></p>' +
			'<div class="glow-tour-pop__bar">' +
				'<div class="glow-tour-pop__dots">' + dots + '</div>' +
				'<div class="glow-tour-pop__btns">' +
					(idx > 0 ? '<button type="button" class="glow-tour-btn glow-tour-btn--ghost" data-glow-prev>Back</button>' : '') +
					'<button type="button" class="glow-tour-btn glow-tour-btn--next" ' +
						(last ? 'data-glow-end>Got it!' : 'data-glow-next>Next') +
					'</button>' +
				'</div>' +
			'</div>';

		pop.querySelector('.glow-tour-pop__t').textContent = s.title;
		pop.querySelector('.glow-tour-pop__d').textContent = s.text;
	}

	function place() {
		if (!live[idx]) { return; }
		var pad = 8;
		var r = live[idx].el.getBoundingClientRect();
		var vw = window.innerWidth, vh = window.innerHeight;

		spot.style.top    = Math.max(pad, r.top - pad) + 'px';
		spot.style.left   = Math.max(pad, r.left - pad) + 'px';
		spot.style.width  = Math.min(r.width + pad * 2, vw - pad * 2) + 'px';
		spot.style.height = (r.height + pad * 2) + 'px';

		var pr = pop.getBoundingClientRect();
		var gap = 18, top, left;

		if (r.bottom + gap + pr.height < vh - 10) {
			top = r.bottom + gap;                       // below
		} else if (r.top - gap - pr.height > 10) {
			top = r.top - gap - pr.height;              // above
		} else {
			top = Math.max(12, (vh - pr.height) / 2);   // centred
		}

		left = r.left + (r.width / 2) - (pr.width / 2);
		left = Math.min(Math.max(12, left), vw - pr.width - 12);

		pop.style.top  = top + 'px';
		pop.style.left = left + 'px';
	}

	function go(n) {
		if (n < 0 || n >= live.length) { return endTour(); }
		idx = n;
		render();

		var r = live[idx].el.getBoundingClientRect();
		if (r.top < 90 || r.bottom > window.innerHeight - 90) {
			live[idx].el.scrollIntoView({ block: 'center', behavior: 'smooth' });
			setTimeout(place, 420);
		} else {
			place();
		}
		setTimeout(place, 60);
	}

	function startTour() {
		if (!resolve()) { return; }               // nothing on screen — bail quietly
		if (!spot) { build(); }
		spot.classList.add('is-on');
		pop.classList.add('is-on');
		skipBtn.style.display = '';
		document.addEventListener('keydown', keys);
		window.addEventListener('scroll', place, { passive: true });
		window.addEventListener('resize', place);
		go(0);
	}

	function endTour() {
		if (!spot) { return; }
		spot.classList.remove('is-on');
		pop.classList.remove('is-on');
		skipBtn.style.display = 'none';
		document.removeEventListener('keydown', keys);
		window.removeEventListener('scroll', place);
		window.removeEventListener('resize', place);
		markSeen();
	}

	function keys(e) {
		if (e.key === 'Escape') { endTour(); }
		if (e.key === 'ArrowRight') { go(idx + 1); }
		if (e.key === 'ArrowLeft')  { go(idx - 1); }
	}

	/* ---------- triggers ---------- */
	document.addEventListener('click', function (e) {
		if (e.target.closest('[data-glow-tour-open]')) {
			e.preventDefault();
			openWelcome();
		}
	});

	/* auto-open once per seller, or any time with ?glow_tour=1 */
	var force = /[?&]glow_tour=1/.test(window.location.search);
	if (force || !seen()) {
		setTimeout(openWelcome, force ? 200 : 700);
	}
}());
</script>