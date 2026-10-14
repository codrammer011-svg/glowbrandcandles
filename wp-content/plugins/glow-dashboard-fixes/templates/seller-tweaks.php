<?php
/**
 * Seller dashboard: bring the layout closer to the client's mock.
 *  - Header: "X DAYS LEFT · Ends Oct 19" instead of the seller code
 *  - Profile card: "Selling for: <group>"
 *  - Sales goal card: items sold / customers / top customer tiles inside it
 *  - Row 2: Customize | My Team (+ Top 5 customers) | My Sales Report
 *  - Lower: checklist + "Keep going" side by side, tools + account settings side by side
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function glow_st_is_sd() {
	return false !== strpos( (string) $_SERVER['REQUEST_URI'], '/seller-dashboard' );
}

/** Data for the My Team card + top customers. */
function glow_st_data( $sid ) {
	$fid = function_exists( 'glow_sd_fundraiser_id' ) ? glow_sd_fundraiser_id( $sid ) : 0;
	if ( ! $fid ) {
		return null;
	}
	$end   = get_user_meta( $fid, 'user_registration_end_date', true );
	$ts    = $end ? strtotime( $end . ' 23:59:59' ) : 0;
	$days  = $ts ? (int) ceil( ( $ts - current_time( 'timestamp' ) ) / DAY_IN_SECONDS ) : -1;
	$org   = get_user_meta( $fid, 'user_registration_name_of_school_business', true );
	if ( ! $org ) { $org = get_user_meta( $fid, 'user_registration_input_company', true ); }
	$goal  = (float) get_user_meta( $fid, 'user_registration_fr_goal', true );
	if ( $goal <= 0 ) { $goal = 1000; }

	$rows   = function_exists( 'glow_ct_orders' ) ? glow_ct_orders( $fid ) : array();
	$raised = 0; $by_seller = array(); $cust = array();
	foreach ( $rows as $r ) {
		if ( ! in_array( $r['raw'], array( 'completed', 'processing' ), true ) ) { continue; }
		$raised += (float) $r['total'];
		if ( is_numeric( $r['sid'] ) && (int) $r['sid'] > 0 ) {
			$by_seller[ (int) $r['sid'] ] = ( $by_seller[ (int) $r['sid'] ] ?? 0 ) + (float) $r['total'];
		}
		if ( (int) $r['sid'] === (int) $sid ) {
			$n = $r['customer'] ? $r['customer'] : 'A supporter';
			$cust[ $n ] = ( $cust[ $n ] ?? 0 ) + (float) $r['total'];
		}
	}
	arsort( $by_seller );
	$rank = 0; $i = 0;
	foreach ( $by_seller as $s => $sum ) { $i++; if ( $s === (int) $sid ) { $rank = $i; break; } }
	$sellers = (int) count( get_users( array( 'meta_key' => 'user_registration_seller_fundraiser', 'meta_value' => $fid, 'fields' => 'ID', 'number' => -1 ) ) );
	arsort( $cust );

	return array(
		'fid' => $fid, 'days' => $days, 'end' => $ts ? date_i18n( 'M jS', $ts ) : '', 'org' => $org,
		'goal' => $goal, 'raised' => $raised, 'pct' => $goal > 0 ? min( 100, round( $raised / $goal * 100 ) ) : 0,
		'rank' => $rank, 'sellers' => max( $sellers, count( $by_seller ) ), 'top' => array_slice( $cust, 0, 5, true ),
	);
}

add_action( 'wp_head', function () {
	if ( ! glow_st_is_sd() ) { return; }
	?>
	<style id="glow-sd-mock">
	/* header */
	.glow-sd-top .glow-sd-code{display:none !important}
	.glow-sd-top .glow-st-days{display:flex;align-items:center;gap:12px;flex-wrap:wrap;color:#fff}
	.glow-sd-top .glow-st-days .glow-st-clock{width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#ff3d7f,#ff7a3d);display:inline-flex;align-items:center;justify-content:center;box-shadow:0 6px 16px rgba(255,61,127,.35)}
	.glow-sd-top .glow-st-days b{font-size:30px;font-weight:900;color:#ffd84d;letter-spacing:.03em;text-shadow:0 2px 8px rgba(0,0,0,.25)}
	.glow-sd-top .glow-st-days span.glow-st-end{font-size:18px;font-weight:800;color:#fff}
	.glow-sd-top .glow-st-days span.glow-st-end:before{content:"·";margin:0 10px 0 2px;opacity:.7}
	.glow-sd-top .glow-sd-statusrow{margin-top:6px}
	/* profile */
	.glow-sd-profile .glow-sd-role{font-size:16px !important;color:#4a4766 !important;line-height:1.35}
	.glow-sd-profile .glow-sd-role b{display:block;font-weight:800;color:#181735;font-size:17px}
	/* goal card stat tiles */
	.glow-st-stats{display:grid;grid-template-columns:1fr 1fr 1.3fr;gap:10px;margin-top:14px}
	.glow-st-stats .glow-st-stat{display:flex;align-items:center;gap:10px;padding:12px 12px;border-radius:14px;background:#f7f6fb;border:1px solid #ecebf3}
	.glow-st-stats .glow-st-stat i{width:36px;height:36px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-style:normal;font-weight:800;flex:0 0 36px}
	.glow-st-stats .glow-st-stat.is-items i{background:#17b98a}.glow-st-stats .glow-st-stat.is-cust i{background:#4f6cff}.glow-st-stats .glow-st-stat.is-top i{background:#6c3df5}
	.glow-st-stats .glow-st-stat b{display:block;font-size:19px;font-weight:900;color:#181735;line-height:1.1}
	.glow-st-stats .glow-st-stat small{display:block;font-size:12.5px;color:#6f6c85;font-weight:600}
	.glow-sd-snapshot{display:none !important}
	/* row 2 */
	.glow-sd-row2{grid-template-columns:.8fr 2.1fr .9fr !important;align-items:stretch}
	.glow-sd-row2 .glow-sd-tile.is-toolbox,.glow-sd-row2 .glow-sd-tile.is-brochure{display:none !important}
	.glow-st-team{background:#fff;border:1px solid #e8e8f0;border-radius:20px;box-shadow:0 2px 6px rgba(22,20,50,.05);padding:18px 20px;display:grid;grid-template-columns:1.3fr 1fr;gap:18px;align-items:center}
	.glow-st-team .glow-st-team-head{display:flex;align-items:center;gap:11px;margin-bottom:10px}
	.glow-st-team .glow-st-team-head i{width:40px;height:40px;border-radius:50%;background:#6c3df5;color:#fff;display:inline-flex;align-items:center;justify-content:center;font-style:normal}
	.glow-st-team h3{margin:0;font-size:16.5px;font-weight:800;letter-spacing:.02em;color:#181735}
	.glow-st-team p.glow-st-team-line{margin:0 0 8px;font-size:16px;color:#181735}
	.glow-st-team p.glow-st-team-line b{font-weight:900}
	.glow-st-team .glow-st-bar{height:12px;border-radius:999px;background:#ecebf3;overflow:hidden;position:relative}
	.glow-st-team .glow-st-bar span{display:block;height:100%;border-radius:999px;background:linear-gradient(90deg,#6c3df5,#9d7bff)}
	.glow-st-team .glow-st-pct{font-size:14px;font-weight:800;color:#181735;margin:6px 0 10px;text-align:right}
	.glow-st-team .glow-st-rank{font-size:17px;font-weight:900;color:#d97706;margin:0}
	.glow-st-team .glow-st-top{background:#fff2f6;border:1px solid #ffd3e0;border-radius:16px;padding:12px 14px}
	.glow-st-team .glow-st-top h4{margin:0 0 6px;font-size:14px;font-weight:800;color:#181735;display:flex;align-items:center;gap:6px}
	.glow-st-team .glow-st-top ol{list-style:none;margin:0;padding:0}
	.glow-st-team .glow-st-top li{display:flex;align-items:center;gap:10px;padding:5px 8px;border-radius:8px;font-size:14px;color:#181735}
	.glow-st-team .glow-st-top li.is-me{background:#ffe1ea;font-weight:800;color:#ec0f63}
	.glow-st-team .glow-st-top li span.n{width:16px;color:#6f6c85;font-weight:700}
	.glow-st-team .glow-st-top li span.v{margin-left:auto;font-weight:800}
	.glow-st-team .glow-st-top p.glow-st-empty{margin:0;font-size:13px;color:#6f6c85}
	.glow-st-row2b{display:grid;grid-template-columns:1fr 1fr;gap:18px}
	/* lower */
	.glow-sd-lower{display:grid !important;grid-template-columns:3fr 1fr !important;gap:18px !important}
	.glow-sd-lower .glow-sd-checklist{grid-column:1}
	.glow-sd-lower .glow-sd-thanks{grid-column:2;grid-row:1}
	.glow-sd-lower .glow-sd-tools{grid-column:1;grid-row:2}
	.glow-sd-lower .glow-sd-settings{grid-column:2;grid-row:2}
	.glow-sd-tools-grid{grid-template-columns:repeat(6,1fr) !important}
	@media(max-width:1100px){.glow-sd-row2{grid-template-columns:1fr 1fr !important}.glow-st-team{grid-column:1/-1;grid-template-columns:1fr}.glow-sd-lower{grid-template-columns:1fr !important}.glow-sd-lower .glow-sd-checklist,.glow-sd-lower .glow-sd-thanks,.glow-sd-lower .glow-sd-tools,.glow-sd-lower .glow-sd-settings{grid-column:auto;grid-row:auto}.glow-sd-tools-grid{grid-template-columns:repeat(3,1fr) !important}}
	@media(max-width:640px){.glow-st-stats{grid-template-columns:1fr}.glow-sd-row2{grid-template-columns:1fr !important}.glow-st-row2b{grid-template-columns:1fr}.glow-sd-tools-grid{grid-template-columns:1fr 1fr !important}}
	</style>
	<?php
} );

add_action( 'wp_footer', function () {
	if ( ! is_user_logged_in() || ! glow_st_is_sd() || ! function_exists( 'glow_sd_current_seller_id' ) ) { return; }
	$sid = (int) glow_sd_current_seller_id();
	$d   = $sid ? glow_st_data( $sid ) : null;
	if ( ! $d ) { return; }
	$me   = trim( get_user_meta( $sid, 'first_name', true ) . ' ' . get_user_meta( $sid, 'last_name', true ) );
	$top  = '';
	if ( $d['top'] ) {
		$i = 0;
		foreach ( $d['top'] as $name => $sum ) {
			$i++;
			$top .= '<li><span class="n">' . $i . '</span>' . esc_html( $name ) . '<span class="v">' . wp_strip_all_tags( wc_price( $sum ) ) . '</span></li>';
		}
		$top = '<ol>' . $top . '</ol>';
	} else {
		$top = '<p class="glow-st-empty">Your top supporters will show here as orders come in.</p>';
	}
	$team = '<div class="glow-st-team" id="glow-st-team"><div><div class="glow-st-team-head"><i>&#128101;</i><h3>MY TEAM</h3></div>'
		. '<p class="glow-st-team-line"><b>' . esc_html( $d['org'] ? $d['org'] : 'Your group' ) . '</b> has raised <b>' . wp_strip_all_tags( wc_price( $d['raised'] ) ) . '</b> of ' . wp_strip_all_tags( wc_price( $d['goal'] ) ) . '</p>'
		. '<div class="glow-st-bar"><span style="width:' . (int) $d['pct'] . '%"></span></div><div class="glow-st-pct">' . (int) $d['pct'] . '%</div>'
		. ( $d['rank'] ? '<p class="glow-st-rank">You\'re #' . (int) $d['rank'] . ' of ' . (int) $d['sellers'] . ' ' . _n( 'seller', 'sellers', (int) $d['sellers'] ) . ' &#127942;</p>' : '<p class="glow-st-rank" style="color:#6f6c85">' . (int) $d['sellers'] . ' ' . _n( 'seller', 'sellers', (int) $d['sellers'] ) . ' on your team &mdash; make your first sale to get on the board!</p>' )
		. '</div><div class="glow-st-top"><h4>&#10084;&#65039; Top 5 Customers</h4>' . $top . '</div></div>';
	$days = $d['days'] >= 0
		? '<div class="glow-st-days"><span class="glow-st-clock">&#9200;</span><b>' . (int) $d['days'] . ' DAYS LEFT</b>' . ( $d['end'] ? '<span class="glow-st-end">Ends ' . esc_html( $d['end'] ) . '</span>' : '' ) . '</div>'
		: '';
	?>
	<script id="glow-sd-mock-js">
	(function(){
		var $ = function(s, r){ return (r||document).querySelector(s); };
		/* header: days left */
		var mid = $('.glow-sd-topmid'); var days = <?php echo wp_json_encode( $days ); ?>;
		if (mid && days) { var h = document.createElement('div'); h.innerHTML = days; mid.insertBefore(h.firstChild, mid.firstChild); }
		/* profile: selling for */
		var role = $('.glow-sd-profile .glow-sd-role'); var org = <?php echo wp_json_encode( $d['org'] ); ?>;
		if (role && org) { role.innerHTML = 'Selling for:<b>' + org.replace(/[&<>]/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;'}[c]; }) + '</b>'; }
		/* goal card: stat tiles from the snapshot card */
		var snap = $('.glow-sd-snapshot'), goal = $('.glow-sd-goal');
		if (snap && goal) {
			var txt = function(el){ return el ? el.textContent.replace(/\s+/g,' ').trim() : ''; };
			var esc = function(s){ return String(s).replace(/[&<>]/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;'}[c]; }); };
			var items = txt($('.glow-sd-snap.is-green b', snap)) || '0';
			var cust  = txt($('.glow-sd-snap.is-blue b', snap)) || '0';
			var topEl = $('.glow-sd-snap.is-purple', snap);
			var topName = txt($('b.is-name', topEl)), topVal = topEl ? txt(topEl.querySelector('div > span:last-child')) : '';
			if (!topName || /^[—\-–]$/.test(topName)) { topName = ''; }
			var wrap = document.createElement('div'); wrap.className = 'glow-st-stats';
			wrap.innerHTML = '<div class="glow-st-stat is-items"><i>&#128717;</i><div><b>' + esc(items) + '</b><small>Items Sold</small></div></div>'
				+ '<div class="glow-st-stat is-cust"><i>&#128101;</i><div><b>' + esc(cust) + '</b><small>Customers</small></div></div>'
				+ '<div class="glow-st-stat is-top"><i>&#127942;</i><div><small>Top Customer:</small><b style="font-size:15px">' + (topName ? esc(topName) + ' · ' + esc(topVal) : 'No sales yet') + '</b></div></div>';
			goal.appendChild(wrap);
		}
		/* row 2: My Team card between Customize and Sales report; toolbox + brochure tiles go to a row below */
		var row2 = $('.glow-sd-row2');
		if (row2 && !$('#glow-st-team')) {
			var store = row2.querySelector('.is-store'), rep = row2.querySelector('.is-reports');
			var tb = row2.querySelector('.is-toolbox'), br = row2.querySelector('.is-brochure');
			var t = document.createElement('div'); t.innerHTML = <?php echo wp_json_encode( $team ); ?>;
			row2.insertBefore(t.firstChild, rep || null);
			if (tb || br) {
				var sub = document.createElement('section'); sub.className = 'glow-sd-row glow-st-row2b';
				if (tb) { var tb2 = tb.cloneNode(true); tb2.style.display = ''; sub.appendChild(tb2); }
				if (br) { var br2 = br.cloneNode(true); br2.style.display = ''; sub.appendChild(br2); }
				row2.parentNode.insertBefore(sub, row2.nextSibling);
			}
		}
	})();
	</script>
	<style>.glow-st-row2b .glow-sd-tile{display:flex !important}</style>
	<?php
}, 26 );
