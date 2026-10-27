<?php
/**
 * number_format() for values read from post/user meta: PHP 8 throws a fatal TypeError
 * when meta is missing ('') or not numeric, which stopped these admin reports mid-page.
 */
if ( ! function_exists( 'glow_fc_number_format' ) ) {
	function glow_fc_number_format( $num, $decimals = 0, $decimal_separator = '.', $thousands_separator = ',' ) {
		return number_format( is_numeric( $num ) ? (float) $num : 0.0, (int) $decimals, $decimal_separator, $thousands_separator );
	}
}

/** Fundraiser search term from the Fundraiser Operations search box (?fs=). */
function glow_fo_search_term() {
	return isset( $_GET['fs'] ) ? trim( sanitize_text_field( wp_unslash( $_GET['fs'] ) ) ) : '';
}

/** Fundraiser IDs matching a search term: ID, login, email, name, organization or rep name. */
function glow_fo_search_ids( $term ) {
	global $wpdb;
	$like = '%' . $wpdb->esc_like( $term ) . '%';
	$ids  = $wpdb->get_col( $wpdb->prepare(
		"SELECT DISTINCT u.ID FROM {$wpdb->users} u
		 LEFT JOIN {$wpdb->usermeta} m ON m.user_id = u.ID AND m.meta_key IN (
		     'user_registration_name_of_school_business', 'user_registration_input_company',
		     'first_name', 'last_name', 'user_registration_referral_name' )
		 WHERE u.ID = %d OR u.user_login LIKE %s OR u.user_email LIKE %s OR u.display_name LIKE %s OR m.meta_value LIKE %s
		 LIMIT 5000",
		ctype_digit( $term ) ? (int) $term : 0, $like, $like, $like, $like
	) );
	return $ids ? array_map( 'intval', $ids ) : array( 0 );
}

/**
 * WP_User_Query for the paged fundraiser lists. With a search term it keeps only the matching
 * fundraisers and recalculates the page count. Leaves a marker so the page shows the search box.
 */
function glow_fo_user_query( $args, $per_page, &$total_pages ) {
	$term = glow_fo_search_term();
	if ( '' !== $term ) {
		$args['include']     = glow_fo_search_ids( $term );
		$args['count_total'] = true;
	}
	$query = new WP_User_Query( $args );
	if ( '' !== $term ) {
		$total_pages = max( 1, (int) ceil( $query->get_total() / max( 1, (int) $per_page ) ) );
	}
	echo '<span hidden data-glow-fo-server-search="' . esc_attr( $term ) . '"></span>';
	return $query;
}

/**
 * Search box, 25 / 50 / 100 / All per page and page links for the Fundraiser Operations lists.
 * Lists already paged on the server get a search box that searches all pages instead.
 */
function glow_fo_table_tools() {
	?>
<style id="glow-fo-table-tools">
/* Same look as the PAID Fundraisers "Enter Fundraiser ID" search. */
.widget-content.glow-fo-search{background:#f7f8f9;padding:26px 24px 24px!important;margin:0 0 20px;clear:both;border-bottom:1px solid #e2e4e7}
.glow-fo-search form{display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:10px;max-width:860px;margin:0 auto}
.glow-fo-search .search-item{flex:0 0 100%;margin:0 0 2px;text-align:left;font-size:14px;font-weight:600;color:#3c434a}
.glow-fo-search input[type=search]{flex:1 1 320px;min-width:0;height:38px!important;min-height:38px;margin:0;padding:6px 12px;border:1px solid #c3c4c7;border-radius:3px;box-sizing:border-box;font-size:14px}
.glow-fo-search .glow-fo-submit,.glow-fo-search .glow-fo-clear{background-color:#405e6a!important;text-shadow:0 -1px 0 #22404c;background-image:none;box-shadow:inset 0 2px 4px rgba(0,0,0,.15),0 1px 2px rgba(0,0,0,.05);color:#fff!important;padding:0 32px;border-radius:3px;border:0;display:inline-flex;align-items:center;justify-content:center;height:38px;margin:0;cursor:pointer;text-decoration:none;font-size:14px;flex:0 0 auto}
.glow-fo-search .glow-fo-clear{padding:0 20px;background-color:#8a9aa1!important}
.glow-fo-tools{display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:12px;margin:14px 0 0}
.glow-fo-tools select{height:30px;padding:2px 8px}
.glow-fo-tools .glow-fo-count{color:#50575e;font-size:13px}
.glow-fo-pager{display:flex;flex-wrap:wrap;justify-content:center;gap:4px;margin:14px 0}
.glow-fo-pager button{min-width:34px;padding:5px 10px;border:1px solid #c3c4c7;background:#fff;border-radius:3px;cursor:pointer}
.glow-fo-pager button[aria-current=page]{background:#405e6a;border-color:#405e6a;color:#fff}
.glow-fo-pager button:disabled{opacity:.45;cursor:default}
.glow-fo-pager span{padding:5px 4px}
</style>
<script id="glow-fo-table-tools-js">
document.addEventListener('DOMContentLoaded', function () {
	var params = new URLSearchParams(window.location.search);
	var serverMarker = document.querySelector('[data-glow-fo-server-search]');
	var topTables = Array.prototype.filter.call(document.querySelectorAll('table.table-bordered'), function (t) {
		return !t.parentElement.closest('table');
	});
	var tables = topTables.filter(function (t) { return t.tHead && t.tBodies.length && t.tBodies[0].rows.length >= 10; });
	if (!serverMarker && !tables.length) { return; }

	function el(tag, attrs, text) {
		var n = document.createElement(tag);
		Object.keys(attrs || {}).forEach(function (k) { n.setAttribute(k, attrs[k]); });
		if (text !== undefined) { n.textContent = text; }
		return n;
	}

	// The PAID Fundraisers style search box. It takes the place of an older "Enter Fundraiser ID"
	// box on the same tab (that one only matched an exact ID); the separate Search tab is left alone.
	var legacy = params.get('search') === 'true' ? null : document.querySelector('.widget-content.search-section');
	function searchBox(anchor, idSuffix) {
		var box = el('div', { 'class': 'widget-content search-section glow-fo-search' });
		var form = el('form', { method: 'get', role: 'search' });
		var input = el('input', { type: 'search', name: 'fs', id: 'glow-fo-search' + idSuffix, 'aria-label': 'Enter fundraiser ID or name', autocomplete: 'off' });
		form.appendChild(el('label', { 'class': 'search-item', 'for': input.id }, 'Enter Fundraiser ID or Name:'));
		form.appendChild(input);
		form.appendChild(document.createTextNode(' '));
		form.appendChild(el('button', { type: 'submit', 'class': 'glow-fo-submit' }, 'Submit'));
		box.appendChild(form);
		if (legacy) {
			legacy.parentNode.insertBefore(box, legacy);
			legacy.hidden = true;
			legacy = null;
		} else {
			anchor.parentNode.insertBefore(box, anchor);
		}
		return { box: box, form: form, input: input };
	}

	// Lists paged on the server: submit the search so every page is searched.
	if (serverMarker) {
		var s = searchBox(topTables[0] || serverMarker, '');
		params.forEach(function (value, key) {
			if (['fs', 'pg', 'paged'].indexOf(key) === -1) { s.form.appendChild(el('input', { type: 'hidden', name: key, value: value })); }
		});
		s.input.value = serverMarker.getAttribute('data-glow-fo-server-search');
		if (s.input.value) {
			var clear = new URLSearchParams(window.location.search);
			clear.delete('fs'); clear.delete('pg'); clear.delete('paged');
			s.form.appendChild(el('a', { href: '?' + clear.toString(), 'class': 'glow-fo-clear' }, 'Clear'));
		}
		return;
	}

	var saved = 50;
	try {
		var stored = parseInt(localStorage.getItem('glowFoPerPage'), 10);
		if ([25, 50, 100, 0].indexOf(stored) !== -1) { saved = stored; }
	} catch (e) {}

	tables.forEach(function (table, index) {
		var rows = Array.prototype.slice.call(table.tBodies[0].rows);
		// Total rows stay visible under every page.
		var isTotal = function (row) { return /^\s*(grand\s+)?total\b/i.test(row.textContent) || /\bTotal Fundraisers\b/.test(row.textContent); };
		var data = rows.filter(function (r) { return !isTotal(r); });
		var texts = data.map(function (r) { return r.textContent.replace(/\s+/g, ' ').toLowerCase(); });
		var state = { page: 1, per: saved, term: '' };

		var s = searchBox(table, '-' + index);
		var tools = el('div', { 'class': 'glow-fo-tools' });
		var per = el('select', { id: 'glow-fo-per-' + index, 'aria-label': 'Rows per page' });
		[25, 50, 100, 0].forEach(function (n) {
			var o = el('option', { value: String(n) }, n ? n + ' per page' : 'All');
			if (n === state.per) { o.selected = true; }
			per.appendChild(o);
		});
		var count = el('span', { 'class': 'glow-fo-count', 'aria-live': 'polite' });
		tools.appendChild(per); tools.appendChild(count);
		s.box.appendChild(tools);
		var pager = el('nav', { 'class': 'glow-fo-pager', 'aria-label': 'Pages' });
		table.parentNode.insertBefore(pager, table.nextSibling);

		function render() {
			var matches = [];
			data.forEach(function (r, i) { if (!state.term || texts[i].indexOf(state.term) !== -1) { matches.push(r); } });
			var pages = state.per ? Math.max(1, Math.ceil(matches.length / state.per)) : 1;
			state.page = Math.min(state.page, pages);
			var from = state.per ? (state.page - 1) * state.per : 0;
			var to = state.per ? from + state.per : matches.length;
			data.forEach(function (r) { r.hidden = true; });
			matches.slice(from, to).forEach(function (r) { r.hidden = false; });
			count.textContent = matches.length
				? 'Showing ' + (from + 1) + '–' + Math.min(to, matches.length) + ' of ' + matches.length + (state.term ? ' matching' : '')
				: 'No matching rows';
			pager.innerHTML = '';
			if (pages < 2) { return; }
			function btn(label, page, opts) {
				var b = el('button', { type: 'button' }, label);
				if (opts && opts.current) { b.setAttribute('aria-current', 'page'); }
				if (opts && opts.disabled) { b.disabled = true; }
				b.addEventListener('click', function () { state.page = page; render(); s.box.scrollIntoView({ block: 'start' }); });
				pager.appendChild(b);
			}
			btn('‹ Previous', state.page - 1, { disabled: state.page === 1 });
			var last = 0;
			for (var p = 1; p <= pages; p++) {
				if (p === 1 || p === pages || Math.abs(p - state.page) <= 2) {
					if (last && p - last > 1) { pager.appendChild(el('span', {}, '…')); }
					btn(String(p), p, { current: p === state.page });
					last = p;
				}
			}
			btn('Next ›', state.page + 1, { disabled: state.page === pages });
		}
		function apply() { state.term = s.input.value.trim().toLowerCase(); state.page = 1; render(); }
		// Filters as you type; Submit (or Enter) does the same, without reloading the page.
		s.form.addEventListener('submit', function (e) { e.preventDefault(); apply(); });
		s.input.addEventListener('input', apply);
		per.addEventListener('change', function () {
			state.per = parseInt(per.value, 10); state.page = 1;
			try { localStorage.setItem('glowFoPerPage', String(state.per || 0)); } catch (e) {}
			render();
		});
		render();
	});
});
</script>
	<?php
}

function theme_fundraiser_operations_options()
{
	glow_fo_table_tools();
?>
<style>
.widget {
    position: relative;
    clear: both;
    width: auto;
    margin-bottom: 2em;
}
.widget-header {
    position: relative;
    height: 40px;
    line-height: 40px;
    background: #E9E9E9;
    background: -moz-linear-gradient(top, #FAFAFA 0%, #E9E9E9 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#FAFAFA), color-stop(100%,#E9E9E9));
    background: -webkit-linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);
    background: -o-linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);
    background: -ms-linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);
    background: linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FAFAFA', endColorstr='#E9E9E9');
    -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#FAFAFA', endColorstr='#E9E9E9')";
    border: 1px solid #CCC;
    -webkit-border-top-left-radius: 4px;
    -webkit-border-top-right-radius: 4px;
    -moz-border-radius-topleft: 4px;
    -moz-border-radius-topright: 4px;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    -webkit-background-clip: padding-box;
    -moz-box-shadow: inset 0 1px 0 rgba(255,255,255,1);
    -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,1);
    box-shadow: inset 0 1px 0 rgba(255,255,255,1);
}
.widget-header h3 {
    position: relative;
    top: 0px;
    left: 10px;
    float: left;
    display: inline-block;
    margin-right: 3em;
    font-size: 13px;
    font-weight: 600;
    color: #555;
    line-height: 18px;
    text-shadow: 1px 1px 2px rgba(255,255,255,1);
}
.widget-content {
    padding: 20px 15px 15px;
    background: #FFF;
    border: 1px solid #CCC;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px;
}
.widget-header+.widget-content, .widget-tabs+.widget-content, .widget-toolbar+.widget-content {
    border-top: none;
    -webkit-border-top-left-radius: 0;
    -webkit-border-top-right-radius: 0;
    -moz-border-radius-topleft: 0;
    -moz-border-radius-topright: 0;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}
.widget-table .widget-content {
    padding: 0;
}
.widget-content:before, .widget-content:after {
    content: "";
    display: table;
}
table {
    max-width: 100%;
    background-color: transparent;
    border-collapse: collapse;
    border-spacing: 0;
}
.table {
    width: 100%;
    margin-bottom: 18px;
}
.table-bordered {
    border: 1px solid #dddddd;
    border-collapse: separate;
    *border-collapse: collapsed;
    border-left: 0;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
}
.table-highlight.table-bordered {
    border-top: 1px solid #1C2B37;
}
.widget-table .table-bordered {
    border-top: none;
    border-right: none;
    border-bottom: none;
    margin-bottom: 0;
}
.widget-table .widget-header+.widget-content .table-bordered {
    -moz-border-radius: 0 !important;
    -webkit-border-radius: 0 !important;
    border-radius: 0 !important;
}
.table-highlight.table-bordered thead tr {
    background: #263849;
    background: -moz-linear-gradient(top, #3C4C5B 0%, #263849 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #3C4C5B), color-stop(100%, #263849));
    background: -webkit-linear-gradient(top, #3C4C5B 0%, #263849 100%);
    background: -o-linear-gradient(top, #3C4C5B 0%, #263849 100%);
    background: -ms-linear-gradient(top, #3C4C5B 0%, #263849 100%);
    background: linear-gradient(top, #3C4C5B 0%, #263849 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#3C4C5B', endColorstr='#263849');
    -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#3C4C5B', endColorstr='#263849')";
}
.table th, .table td {
    padding: 8px;
    line-height: 18px;
    text-align: left;
    vertical-align: top;
    border-top: 1px solid #dddddd;
}
.table th {
    font-weight: bold;
}
.table-bordered thead th {
    padding-top: 10px;
    padding-bottom: 10px;
    font-size: 13px;
    font-weight: 400;
    color: #444;
    border-left: 1px solid #F1F1F1;
    border-right: 1px solid #CCC;
    -moz-box-shadow: inset 0 1px 0 rgba(255,255,255,1);
    -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,1);
    box-shadow: inset 0 1px 0 rgba(255,255,255,1);
}
.table-highlight.table-bordered thead th {
    color: #FFF;
    border-right: 1px solid #1C2B37;
    border-left: 1px solid rgba(255,255,255,.15);
    -moz-box-shadow: inset 0 1px 0 rgba(255,255,255,.25);
    -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,.25);
    box-shadow: inset 0 1px 0 rgba(255,255,255,.25);
    text-shadow: 1px 1px 2px rgba(0,0,0,.5);
}
.widget-table .table-bordered tr td:first-child, .widget-table .table-bordered tr th:first-child {
    border-left: none;
}
.widget-table .widget-header+.widget-content thead th {
    -moz-border-radius: 0 !important;
    -webkit-border-radius: 0 !important;
    border-radius: 0 !important;
}
.table-bordered thead:first-child tr:first-child th:first-child, .table-bordered tbody:first-child tr:first-child td:first-child {
    -webkit-border-top-left-radius: 4px;
    border-top-left-radius: 4px;
    -moz-border-radius-topleft: 4px;
}
.widget-table .table-highlight thead:first-child tr:first-child th {
    border-top: 1px solid #292C32;
}
.widget-table tbody td {
    border-left: 1px solid #FFF;
    border-right: 1px solid #DDD;
}
.widget-table tbody tr:first-child td {
    border-top-color: #CCC;
}
.widget-table .table-bordered tr td:first-child, .widget-table .table-bordered tr th:first-child {
    border-left: none;
}
.table-highlight.table-bordered tbody tr:nth-child(odd) td, .table-highlight.table-bordered tbody tr:nth-child(odd) th {
    background-color: #F0F3F7;
}
.table-highlight.table-bordered tbody tr:nth-child(odd) td, .table-highlight.table-bordered tbody tr:nth-child(odd) th {
    background-color: #F0F3F7;
}
.label, .badge {
    font-size: 10.998px;
    font-weight: bold;
    line-height: 14px;
    color: #ffffff;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
    white-space: nowrap;
    vertical-align: baseline;
    background-color: #999999;
}
.label {
    padding: 1px 4px 2px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
}
.label-success, .badge-success {
    background-color: #468847;
}
.label-danger, .badge-danger {
    background-color: #bd362f;
}
.paid {
    background-color: #468847;
}
.unpaid {
    background-color: #bd362f;
}
.btn-custom
{
	background-color: #405e6a !important;
    text-shadow: 0px -1px 0px #22404c;
    background-image: none;
    -webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
    -moz-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
    color: #fff;
    display: inline-block;
    padding: 4px 10px 4px;
    margin-bottom: 0;
    margin-left: .3em;
    font-size: 13px;
    line-height: 18px;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    background-repeat: repeat-x;
    border: 0;
    border-color: #e6e6e6 #e6e6e6 #bfbfbf;
    border-bottom-color: #b3b3b3;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
    text-decoration: none;
    display: inline;
}
</style>

	<style>
	.manual-mass-order-popup a 
	{
		text-decoration: none;
		-webkit-transition: color .25s ease;
		-moz-transition: color .25s ease;
		-o-transition: color .25s ease;
		-ms-transition: color .25s ease;
		transition: color .25s ease;
		color: #000;
		cursor: pointer;
	    margin-top: 15px; 
		outline:none;
	}
	.manual-mass-order-popup a:hover{
			color: #cda85c;
	}
	.manual-mass-order-popup_action a 
	{
		text-decoration: none;
		-webkit-transition: color .25s ease;
		-moz-transition: color .25s ease;
		-o-transition: color .25s ease;
		-ms-transition: color .25s ease;
		transition: color .25s ease;
		color: #000;
		cursor: pointer;
	    margin-top: 15px; 
		outline:none;
	}
	.manual-mass-order-popup_action a:hover{
			color: #cda85c;
	}
	.txt-link
	{
		display:block;
		width:100%;
	}
	.status_type
	{
			margin: 0 10px !important;
	}
	.a_status_type
	{
			margin: 0 10px !important;
	}
	.manual-mass-order-popup 
	{
		position: fixed;
		width: 100%;
		height: 100%;
		z-index: 999;
		background: rgba(0,0,0,0.6);
		top: 0;
		left: 0;
		display: none;
	}
	.manual-mass-order-popup .manual-mass-order-popup-wrapper 
	{
		display: block;
		width: 100%;
		max-width: 500px;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translateY(-50%) translateX(-50%);
		text-align: center;
		padding: 40px 25px;
		background: #fff;
		border: 3px solid #000;
		border-radius: 5px;
	}
	.manual-mass-order-popup a.fr-btn 
	{
		display: block;
		margin: 20px auto;
		width: 100%;
		max-width: 250px;
	}
	.manual-mass-order-popup_action span
	{
		width: 100%;
    display: inline-block;
    margin-bottom: 15px;
    text-align: center;
    text-transform: uppercase;
    font-size: 15px;
    font-weight: 600;
    padding-bottom: 10px;
    border-bottom: 2px solid rgba(0,0,0,0.6);
	}
	.manual-mass-order-popup_action 
	{
		position: fixed;
		width: 100%;
		height: 100%;
		z-index: 999;
		background: rgba(0,0,0,0.6);
		top: 0;
		left: 0;
		display: none;
	}
	.manual-mass-order-popup_action .manual-mass-order-popup-wrapper_action 
	{
		display: block;
		width: 100%;
		max-width: 500px;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translateY(-50%) translateX(-50%);
		text-align: center;
		padding: 40px 25px;
		background: #fff;
		border: 3px solid #000;
		border-radius: 5px;
	}
	
	.manual-mass-order-popup_repre_pay 
	{
		position: fixed;
		width: 100%;
		height: 100%;
		z-index: 999;
		background: rgba(0,0,0,0.6);
		top: 0;
		left: 0;
		display: none;
	}
	.manual-mass-order-popup_repre_pay .manual-mass-order-popup-wrapper_repre_pay
	{
		display: block;
		width: 100%;
		max-width: 500px;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translateY(-50%) translateX(-50%);
		text-align: center;
		padding: 40px 25px;
		background: #fff;
		border: 3px solid #000;
		border-radius: 5px;
	}
	.manual-mass-order-popup_action a.fr-btn 
	{
		display: block;
		margin: 20px auto;
		width: 100%;
		max-width: 250px;
	}
	.send_report_another
	{
		margin-left: 10px;
	}
	#another_email
	{
		width: 100%;
	}
	.show
	{
		display: block;
	}
	.manual-mass-order-popup span
	{
		width: 100%;
    display: inline-block;
    margin-bottom: 15px;
    text-align: center;
    text-transform: uppercase;
    font-size: 15px;
    font-weight: 600;
    padding-bottom: 10px;
    border-bottom: 2px solid rgba(0,0,0,0.6);
	}
	#submit_status
	{
		margin-left: 10px;
	}
	.manual-mass-order-popup p,
	.manual-mass-order-popup_action .manual-mass-order-popup-wrapper_action p
	{
		display:none;
		position: absolute;
		margin: 0 auto;
		top: 13px;
		color: rgb(111 105 105 / 95%);
		font-size: 15px;
		font-weight: 500;
	}
	</style>
	<style>
.proccessloader,
.proccessloader2
{
	display:none;
	    position: fixed;
    width: 100%;
    height: 100%;
    z-index: 999;
    background: rgba(0,0,0,0.8);
    top: 0;
    left: 0;
    margin: 0 auto;
    text-align: center;
}
.proccessloader .inner,
.proccessloader2 .inner
{
	    display: inline-block;
    width: 100%;
    max-width: 500px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateY(-50%) translateX(-50%);
    text-align: center;
}
.proccessloader .inner p,
.proccessloader2 .inner p
{
	display:none;
}
.proccessloader.success .inner,
.proccessloader2.success .inner
{
	background: #fff;
    padding-bottom: 30px;
}
.proccessloader.success .inner img,
.proccessloader2.success .inner img
{
	width: 200px;
}
.proccessloader.success .inner p,
.proccessloader2.success .inner p
{
	color: green;
    position: absolute;
    top: 66%;
    left: 32%;
    font-size: 18px;
    margin: 0;
    display:block;
}
</style>
	<script>
		jQuery(document).ready(function (){
         jQuery(document).on('click', '#printDiv', function (e){
       		var divToPrint = document.getElementById('divToPrint');
       		var popupWin = window.open('', '_blank', 'width=500,height=500');
       		popupWin.document.open();
       		popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        	popupWin.document.close();
          });
			var ajax_url= "<?php echo admin_url('admin-ajax.php'); ?>";
			jQuery(document).on('click', '.status', function (e){
				e.preventDefault();
				jQuery(".manual-mass-order-popup").addClass("show");
				let fund_id = jQuery(this).data('id');
				let fund_action = jQuery(this).data('action');
				let rebate_action = jQuery(this).data('rebate');
				jQuery('#submit_status').attr("data-id",fund_id); 
				jQuery('#submit_status').attr("data-action",fund_action); 
				jQuery('#submit_status').attr("data-rebate",rebate_action); 
			if(fund_action == 1) {
				jQuery("#st_1").prop("checked", true);
				jQuery("#st_2").prop("checked", false);
			} else if (fund_action == 2) {
				jQuery("#st_2").prop("checked", true);
				jQuery("#st_1").prop("checked", false);
			} else {
				jQuery("#st_1").prop("checked", false);
				jQuery("#st_2").prop("checked", false);
			}
	    });

		jQuery(document).on('change', 'input[type=radio][name=paid_type]', function() {
			var radioValue = jQuery("input[type=radio][name=paid_type]:checked").val();
			jQuery("#submit_status").attr("data-action", radioValue);
		});

		jQuery(document).on('click', '#submit_status', function (e){
			e.preventDefault();
			jQuery(".manual-mass-order-popup p").css("display","block");
			jQuery(".manual-mass-order-popup .manual-mass-order-popup-wrapper p").text("Please Wait..");
			let st_id = jQuery(this).attr('data-id');
			let st_ac = jQuery(this).attr('data-action');
			let rebate = jQuery(this).attr('data-rebate');
			jQuery.ajax({
				type : "post",
				url : ajaxurl,
				data : {action: 'custom_fund_status', fund_id:st_id, st_ac:st_ac, rebate:rebate},
				success: function(response) {
					console.log(response);
					jQuery(".manual-mass-order-popup .manual-mass-order-popup-wrapper p").text("Successfully Updated!!");
					setTimeout(function(){ jQuery(".manual-mass-order-popup").removeClass("show"); }, 1000);
					
					var actionVal = (response && response.data && response.data.action) ? response.data.action : st_ac;
					if(actionVal == 1) {
						jQuery("#fund_"+st_id+" #6_").html('<span id="" class="label label-success">Paid</span>');
						jQuery("#fund_"+st_id+" .status").data('action', 1).attr('data-action', 1);
					}
					if(actionVal == 2) {
						jQuery("#fund_"+st_id+" #6_").html('<span id="" class="label label-danger">Unpaid</span>');
						jQuery("#fund_"+st_id+" .status").data('action', 2).attr('data-action', 2);
					}
					setTimeout(function(){ jQuery(".manual-mass-order-popup .manual-mass-order-popup-wrapper p").text(""); }, 2000);
				},
				error: function() {
					jQuery(".manual-mass-order-popup .manual-mass-order-popup-wrapper p").text("Update Failed. Try Again.");
				}
			});
		});
		
		/**/
		jQuery(document).on('click', '.repre_pay', function (e){
			e.preventDefault();			
			jQuery(".manual-mass-order-popup_repre_pay").addClass("show");
			var fundID = jQuery(this).data('id');		
			var reprPay_action = jQuery(this).data('action');		
			jQuery('#submit_repre_status').attr("data-id",fundID);
			jQuery('#submit_repre_status').attr("data-action",reprPay_action);
						
			jQuery.ajax({
				type : "post",
				url : ajaxurl,
				data : {action: 'representative_pay', fund_id:fundID},
				success: function(response) {
					jQuery(".repre_pay_content").html(response);
				}
			});
				
	    });
	    
	    jQuery('input[type=radio][name=repre_paid_type]').change(function() 
		{
			 var repre_paid_type = jQuery("input[type=radio][name=repre_paid_type]:checked").val();
			jQuery("#submit_repre_status").attr("data-action",repre_paid_type);
			
		});
		
	    jQuery(document).on('click', '#submit_repre_status', function (e){
			e.preventDefault();
			jQuery(".manual-mass-order-popup_repre_pay p").css("display","block");
			jQuery(".manual-mass-order-popup_repre_pay .manual-mass-order-popup-wrapper_repre_pay p").text("Please Wait..");
			var fund_id = jQuery(this).attr('data-id');
			var repre_action = jQuery(this).attr('data-action');	
			
			 	
			jQuery.ajax({
				type : "post",
				url : ajaxurl,
				data : {action: 'representative_status', fundId:fund_id,repre_action:repre_action},
				success: function(response) {
					console.log(response);
					jQuery(".manual-mass-order-popup_repre_pay .manual-mass-order-popup-wrapper_repre_pay p").text("Successfully Updated!!");
					
					
					/*alert(response.data.action);*/
					if(response.data.action==1)
					{
						jQuery("#fund_"+fund_id+" #10_").html('<span id="" class="label label-success">Paid</span>');
						/*jQuery('#fund_'+st_id+' #7_ #all_action').css("display","none");*/
					}
					if(response.data.action==2)
					{
						jQuery("#fund_"+fund_id+" #10_").html('<span id="" class="label label-danger">Unpaid</span>');
					}
					
					setTimeout(function(){ jQuery(".manual-mass-order-popup_repre_pay .manual-mass-order-popup-wrapper_repre_pay p").text(""); }, 2000);		
				}
			});
		});
		
		
		
		/* Active / Inactive Action */
		jQuery(document).on('click', '.action_status', function (e){
			e.preventDefault();
			jQuery(".manual-mass-order-popup_action").addClass("show");
			let fund_id = jQuery(this).data('id');
			let fund_action = jQuery(this).data('action');
			jQuery('#submit_action_status').attr("data-id", fund_id); 
			jQuery('#submit_action_status').attr("data-action", fund_action); 
			if(fund_action == 1) {
				jQuery("#a_st_1").prop("checked", true);
				jQuery("#a_st_2").prop("checked", false);
			} else if (fund_action == 2) {
				jQuery("#a_st_2").prop("checked", true);
				jQuery("#a_st_1").prop("checked", false);
			} else {
				jQuery("#a_st_1").prop("checked", false);
				jQuery("#a_st_2").prop("checked", false);
			}
		});

		jQuery(document).on('change', 'input[type=radio][name=action_type]', function() {
			var radioValue = jQuery("input[type=radio][name=action_type]:checked").val();
			jQuery("#submit_action_status").attr("data-action", radioValue);
		});

		jQuery(document).on('click', '#submit_action_status', function (e){
			e.preventDefault();
			jQuery(".manual-mass-order-popup_action p").css("display","block");
			jQuery(".manual-mass-order-popup_action .manual-mass-order-popup-wrapper_action p").text("Please Wait..");
			let st_id = jQuery(this).attr('data-id');
			let st_ac = jQuery(this).attr('data-action');
			jQuery.ajax({
				type : "post",
				url : ajaxurl,
				data : {action: 'fund_action_status', a_fund_id:st_id, a_st_ac:st_ac},
				success: function(response) {
					console.log(response);
					jQuery(".manual-mass-order-popup_action .manual-mass-order-popup-wrapper_action p").text("Successfully Updated!!");
					setTimeout(function(){ jQuery(".manual-mass-order-popup_action").removeClass("show"); }, 1000);
					
					var actionVal = (response && response.data && response.data.action) ? response.data.action : st_ac;
					if(actionVal == 1) {
						jQuery("#fund_"+st_id+" #8_").html('<span id="action_st" class="label label-success">Active</span>');
						jQuery("#fund_"+st_id+" .action_status").data('action', 1).attr('data-action', 1);
					}
					if(actionVal == 2) {
						jQuery("#fund_"+st_id+" #8_").html('<span id="action_s2" class="label label-danger">Inactive</span>');
						jQuery("#fund_"+st_id+" .action_status").data('action', 2).attr('data-action', 2);
					}
					setTimeout(function(){ jQuery(".manual-mass-order-popup_action .manual-mass-order-popup-wrapper_action p").text(""); }, 2000);
				},
				error: function() {
					jQuery(".manual-mass-order-popup_action .manual-mass-order-popup-wrapper_action p").text("Update Failed. Try Again.");
				}
			});
		});
		/* End */

		jQuery(document).on('click', '#m-txt-link', function (e) {
			e.preventDefault();
			jQuery(".manual-mass-order-popup,.manual-mass-order-popup_action,.manual-mass-order-popup_repre_pay").removeClass("show");
		});
	
		});
        jQuery(document).on('click', '#send_report', function (e) {
            e.preventDefault();
            var button = jQuery(this);
            if (button.data('sending')) { return; }
            var loader = jQuery('.proccessloader2');
            button.data('sending', true).attr('aria-disabled', 'true');
            loader.removeClass('success').show();
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                dataType: 'text',
                timeout: 120000,
                data: {action: 'custom_fund_report_mail', fund_id: button.data('fund_id')}
            }).done(function (response) {
                if (jQuery.trim(response) === 'Your report has been successfully sent.') {
                    window.alert('Your report has been successfully sent.');
                } else {
                    window.alert('The report could not be sent. Please try again or contact support.');
                }
            }).fail(function (xhr, status) {
                window.alert(status === 'timeout'
                    ? 'The report request timed out. Please check whether the email arrived before retrying.'
                    : 'The report could not be sent. Please refresh the page and try again.');
            }).always(function () {
                loader.hide().removeClass('success');
                button.removeData('sending').removeAttr('aria-disabled');
            });
        });
</script>
<?php  if($_GET['page']=='fundraiser_operations' && ( $_GET['closed'] ?? '' )=="closed_fundraisers"){ ?>
<style>
	.fundraiser_summery
	{
		margin: 21px;
    margin-left: 0;
	}
	.fundraiser_summery .btn-custom
	{
		    padding: 10px;
	}
		/*Search Option */
	#fund_search .search-btn2
	{
		background-color: #405e6a !important;
		text-shadow: 0px -1px 0px #22404c;
		background-image: none;
		-webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
		-moz-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
		box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
		color: #fff;
		padding: 5px 33px;
		border-radius: 3px;
		border: 0;
		display: inline-block;
		height: 30px;
	}
	
	.messageBox2
	{
		color: red;
		margin-bottom: 8px;
		display:none;
		width: 750px;
		margin: 0 auto;
		text-align: left;
		margin-bottom: 3px;
		font-size: 14px;
	}
	.messageBox2 strong
	{
		font-weight:800;
	}
	.widget-table .widget-content.search-section
	{
		background: transparent;
		padding: 20px;
	}
	#fund_search
	{
		width: 100%;
		margin: 0 auto;
		text-align: center;
		display: inline-block;
	}
	#fund_search .search-item
	{
		width: 750px;
		margin: 0 auto;
		text-align: left;
		margin-bottom: 9px;
		font-size: 14px;
	}
		.pagaination_section
	{
		position: relative;
		width: 100%;
		margin: 0 auto;
		text-align: center;
		padding: 40px 0px;
		display: inline-block;
	}
	.pagaination_section a.page-numbers {
    color: black;
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
    border: 1px solid #ddd;
    display: inline-block;
    margin: 0;
    font-size: 14px;
} 

	.pagaination_section span.page-numbers.current {
    background-color: #006799;
    color: white;
    border: 1px solid #006799;
    display: inline-block;
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
}
	</style>
	<script>
		jQuery(document).ready(function () 
		{
			jQuery(".search-btn2").click(function(e){
				
				
				e.preventDefault();
				var search_id=jQuery("#search_id2").val();
				if(search_id!="")
				{
					jQuery(".messageBox2").css("display","none");
					jQuery("#search_table2 table tbody tr").css("display","none");
					jQuery("#search_table2 table tbody tr#fd_"+search_id).css("display","table-row");
					
				}
				else{
					jQuery(".messageBox2").css("display","block");
					jQuery("#search_table2 table tbody tr").css("display","table-row");
				}
			});
		});
</script>
	
	<div class="btn-toolbar fundraiser_summery">
		<a href="?page=fundraiser_operations" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Operations</a>
		<a href="?page=fundraiser_operations&fundraiser_report=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Report</a>
		<a href="?page=fundraiser_operations&paid=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Paid Fundraiser Summary</a>
		<a href="?page=fundraiser_operations&fundraiser_summery=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Summary</a>
		<a href="?page=fundraiser_operations&print_order=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print Order</a>
		<a href="?page=fundraiser_operations&request=close" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Close Out</a>
		<a href="?page=fundraiser_operations&gpd=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Online Profits Deduction</a><br/><br/>
		<a href="?page=fundraiser_operations&fund_owed=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Owed Fundraiser</a>
	</div>
	<div class="widget1 widget-table">
		<div id="wrap" class="widget-content search-section" style="clear: both;">
			<div class="messageBox2"><strong>Error!!</strong> Please Enter Fundraiser ID.</div>
			<form action="#" name="fund_search" id="fund_search" method="get">
				<div class="row">
					<div class="span6">
						<div class="search-item">Enter Fundraiser ID:</div> <input type="text" name="search_id2" size="100" value="" id="search_id2"><input type="submit" value="Submit" class="btn btn-primary search-btn2">
					</div>
					
				</div>
			</form>
		</div>
	<div class="widget-header">						
		<h3>Closed Fundraisers</h3>
	</div>	
	</div>
	<div id="search_table2" class="widget-content " style="clear: both;padding: 0;">
		<table class="table table-bordered table-striped table-highlight responsive" style="margin: 0;">
		<thead>
		  <tr>
		   <th id="" style="vertical-align: top; text-align: center;">Fund ID:</th>
			<th id="" style="vertical-align: top; text-align: left;">Organization:</th>
			<th id="" style="vertical-align: top; text-align: left;">Director:</th>
			<!--<th id="" style="vertical-align: top; text-align: center;">Members:</th>-->
			<th id="" style="vertical-align: top; text-align: center;">Period:</th>
			<th id="" style="vertical-align: top; text-align: center;">Status:</th>			
			<th id="" style="vertical-align: top; text-align: center;">Fund Status:</th>
			<th id="" style="vertical-align: top; text-align: center;">Closed Date:</th>
			<th id="" style="vertical-align: top; text-align: center;">Confirm:</th>
			<th id="" style="vertical-align: top; text-align: center;">Rebook Date:</th>
			<th id="" style="vertical-align: top; text-align: center;">Paid Rebate:</th>
			<th id="" style="vertical-align: top; text-align: center;">Print Order:</th>
			
		 </tr>
		</thead>
		<tbody>
		 <?php 
		 $count_args = array(
					'role'    => 'Fundraiser',
					 'status'   => "published",
					'meta_key'     => 'user_status',
					'meta_value'   => '1',
					'orderby' => 'ID',
					'order'   => 'DESC'
				);
		$user_count_query = new WP_User_Query($count_args);
		$user_count = $user_count_query->get_results();

		// count the number of users found in the query
	   $total_users = $user_count ? count($user_count) : 1;

		// grab the current page number and set to 1 if no page number is set
		$page = isset($_GET['pg']) ? $_GET['pg'] : 1;

		// how many users to show per page
		$users_per_page = 50;

		// calculate the total number of pages.
		$total_pages = 1;
		$offset = $users_per_page * ($page - 1);
		$total_pages = ceil($total_users / $users_per_page); 
		$args = array(
					'role'    => 'Fundraiser',
					 'status'   => "published",
					'orderby' => 'ID',
					'order'   => 'DESC',
					'meta_key'     => 'user_status',
					'meta_value'   => '1',
					'number'    => $users_per_page,
					'offset'    => $offset  // skip the number of users that we have per page  
				);
			/*$users = get_users( $args ); */
			// Create the WP_User_Query object
				$wp_user_query = glow_fo_user_query( $args, $users_per_page, $total_pages );

				// Get the results
				$authors = $wp_user_query->get_results();
				/* $all_meta_for_users = get_users($args); */
		
				/*	$args = array(
					'role'    => 'Fundraiser',
					'orderby' => 'user_nicename',
					'order'   => 'ASC'
				);
				$all_meta_for_users = get_users($args);
				foreach ( $all_meta_for_users as $all_meta_for_user ) {
					*/
					foreach ( $authors as $user ) 
		            {
						$count=0;
						$ID=$user->ID;	
						
						$status= get_user_meta( $ID , 'user_status' , true );
					    $action_status= get_user_meta( $ID , 'user_action_status' , true );
						if($status==1 && $action_status==2)
					    {
							
						$arr=array();
						$total_sale_so_far=0;
						$rebate_so_far=0;
                        $argss = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                        $orders = wc_get_orders($argss);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                          /*  if ($ID3 == $IDD) { */
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) 
								  {
                               $total_sale_so_far+=$order->get_subtotal();
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = glow_fc_number_format($Discount, 2, '.', '');
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = glow_fc_number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
                                   /*} */
						}
							else if ($frID3 == $order_customer_id) 
									{
									  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
										$total_product = $order->get_total();   ///$order_data->get_total();


										$percentage  = 40;
										$percentFifty= 50;

									   $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
							
								 
										 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
										 {
											$Discount    = ($percentFifty / 100) * $totalAmount;
											$Discount = glow_fc_number_format($Discount, 2, '.', '');
											$rebate_so_far+=$Discount;
										 }
										 else
										 {
											$Discount    = ($percentage / 100) * $totalAmount;
											$Discount = glow_fc_number_format($Discount, 2, '.', '');
											 $rebate_so_far+=$Discount;
										 }
									}
								} 
								else{}

						  } } } }

				
				   
					$all_seller = get_users(array(
					'meta_key'     => 'user_registration_seller_fundraiser',
					'meta_value'   => $ID,
				));
				if ( ! empty( $all_seller ) && is_iterable( $all_seller ) ) {
					foreach ( $all_seller as $seller_item ) {
						$arr[] = $seller_item->ID;
					}
				}
				    
                    $org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );
                    
					$member=count($arr);
					$sd=get_user_meta( $ID , 'user_registration_start_date' , true );
					$ed=get_user_meta( $ID , 'user_registration_end_date' , true );
					
					$currentDate=date("Y-m-d");
					$currentDate = date('Y-m-d', strtotime($currentDate));
					$startDate = date('Y-m-d', strtotime($sd));
					$endDate = date('Y-m-d', strtotime($ed));
					date_default_timezone_set('America/Chicago');
					$active_date=get_user_meta($ID, "user_close_out_date_active", true);
					$inactive_date=get_user_meta($ID, "user_close_out_date_inactive", true);
					$rebate= get_user_meta( $ID , 'user_rebate' , true );
					$rebook_yes= get_user_meta( $ID , 'rebook_yes' , true );
					$rebook_date=get_user_meta($ID, "rebook_date", true);
					$user_rebate=get_user_meta($ID, 'user_rebate' , true);
					
				?>						
			<tr id="<?php echo "fd_".$ID; ?>" style="width: 100%;padding: 0px; max-width: 1000px;margin: 0 auto;">
				<td id="0_" style="vertical-align: top; text-align: center; "><?php echo $ID;?></td>
				<td id="1_" style="vertical-align: top; text-align: left;width: 180px;"><?php echo $org; ?></td>
				<td id="2_" style="vertical-align: top; text-align: left; "><?php echo $user->user_firstname; ?> <?php echo $user->user_lastname; ?></td>
				<!--<td id="4_" style="vertical-align: top; text-align: center; "><?php echo $member; ?></td>-->
				<td id="5_" style="vertical-align: top; text-align: center; "><span class="label <?php if (($currentDate >= $startDate) && ($currentDate <= $endDate)){ ?>label-success<?php } else { echo "label-danger"; } ?> "><?php echo date( "M d, Y", strtotime( $sd )); ?> - <?php echo date( "M d, Y", strtotime( $ed )); ?></span></td>
				<td id="6_" class="<?php echo $rebate; ?>" style="vertical-align: top; text-align: center; "><?php if($status==1){?><span id="action_status_<?php echo $ID;?>" class="label label-success">Paid</span><?php }  elseif($status==2){?><span id="action_status_<?php echo $ID;?>" class="label label-danger">Unpaid</span><?php }  else { ?><span id="action_status_<?php echo $ID;?>" class="label label-danger">Unpaid</span><?php } ?></td>
                <td id="8_" class="" style="vertical-align: top; text-align: center; "><?php if($action_status==1){?><span id="action_s" class="label label-success">Active</span><?php }  elseif($action_status==2){?><span id="action_s2" class="label label-danger">Inactive</span><?php }  else { ?><span id="action_st" class="label label-success">Active</span><?php } ?></td>
				<td id="11_" style="vertical-align: top; text-align: center; "><?php if(!empty($inactive_date)){  echo $inactive_date;  } ?></td>
				<td id="7_" style="vertical-align: top; text-align: center; "><?php if($rebook_yes=="yes"){ echo "Yes"; } ?></td>
				<td id="9_" style="vertical-align: top; text-align: center; "><?php if(!empty($rebook_date)){ echo $rebook_date; } ?></td>
				<td id="12_" style="vertical-align: top; text-align: center; "><?php if(!empty($user_rebate)){ echo "$".$user_rebate; } ?></td>
				<td id="10_" style="vertical-align: top; text-align: center; ">
					<div class="btn-toolbar"><div class="first-col" style="margin-bottom:10px;">&nbsp;<a href="<?php echo home_url('/print/'); ?>?fund_id=<?php echo $ID;?>" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print</a></div></div>
				</td>
				
			</tr>
		<?php } ?>
		 <?php } ?>
		</tbody>
	</table>
	<?php
				// grab the current query parameters
		$query_string = $_SERVER['QUERY_STRING'];

		// The $base variable stores the complete URL to our page, including the current page arg

		// if in the admin, your base should be the admin URL + your page
		$base = admin_url('admin.php') . '?' . remove_query_arg('pg', $query_string) . '%_%';

		// if on the front end, your base is the current page
		//$base = get_permalink( get_the_ID() ) . '?' . remove_query_arg('p', $query_string) . '%_%';
		?>
		<div class="pagaination_section">
			<?php 
			echo paginate_links( array(
				'base' => $base, // the base URL, including query arg
				'format' => '&pg=%#%', // this defines the query parameter that will be used, in this case "p"
				'prev_text' => __('&laquo; Previous'), // text for previous page
				'next_text' => __('Next &raquo;'), // text for next page
				'total' => $total_pages, // the total number of pages we have
				'current' => $page, // the current page
				'end_size' => 1,
				'mid_size' => 5,
			));
			?>
		</div>
</div>
<?php } else if($_GET['page']=='fundraiser_operations' && ( $_GET['gpd'] ?? '' )=="true"){ ?>
<style>
	.fundraiser_summery
	{
		margin: 21px;
    margin-left: 0;
	}
	.fundraiser_summery .btn-custom
	{
		    padding: 10px;
	}
	.widget1.widget-table, .widget-content
	{
		width:99%;
	}
	</style>
	<div class="btn-toolbar fundraiser_summery">
		<a href="?page=fundraiser_operations" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Operations</a>
		<a href="?page=fundraiser_operations&fundraiser_report=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Report</a>
		<a href="?page=fundraiser_operations&paid=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Paid Fundraiser Summary</a>
		<a href="?page=fundraiser_operations&fundraiser_summery=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Summary</a>
		<a href="?page=fundraiser_operations&print_order=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print Order</a>
		<a href="?page=fundraiser_operations&request=close" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Close Out</a>
		<a href="?page=fundraiser_operations&closed=closed_fundraisers" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Closed Fundraisers</a>
		<a href="?page=fundraiser_operations&gpd=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Online Profits Deduction</a><br/><br/>
		<a href="?page=fundraiser_operations&fund_owed=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Owed Fundraiser</a>
	</div>
	<div class="widget1 widget-table">
	<div class="widget-header">						
		<h3>Online Profits Deduction</h3>
	</div>	
	</div>
	<div id="" class="widget-content " style="clear: both;padding: 0;">
		<table class="table table-bordered table-striped table-highlight responsive" style="    margin: 0;">
		<thead>
		  <tr>
		    <th id="" style="vertical-align: top;text-align: center;">Fund #ID:</th>
				<th id="" style="vertical-align: top; text-align: center;">No. of Brochure<br>Order:</th>
			<th id="" style="vertical-align: top; text-align: center;">Glow Catalog/Brochure<br>Order ID:</th>
		    <th id="" style="vertical-align: top;text-align: center;">Close Out Date:</th>
			<th id="" style="vertical-align: top; text-align: center;">Organizations/Rep:</th>
			<th id="" style="vertical-align: top; text-align: center;">Fund Status:</th>
			<th id="" style="vertical-align: top; text-align: center;">Status:</th>
			<th id="" style="vertical-align: top; text-align: center;">Total Online Profits (40%):</th>
			<th id="" style="vertical-align: top; text-align: center;">Online Profits Deduction (Paid Rebate):</th>
			<th id="" style="vertical-align: top; text-align: center;">Total Amount<br>Remaining to Group:</th>
			<th id="" style="vertical-align: top; text-align: center;">Remaining to Group<br>Paid Status:</th>
			<th id="" style="vertical-align: top; text-align: center;">View Report:</th>
			
		 </tr>
		</thead>
		<tbody>
		     <?php
			 $arg=array('post_type'=>'close_out','posts_per_page' => -1,'order' => 'DESC');
			 $table_query= new WP_Query();
			 $table_query->query($arg);
			 $remove_duplicate=array();
			 if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
				 $postid = $table_query->post->ID; 
				$ID = get_post_meta( $postid, 'close out_fundraiser_id', true );
				 if(!in_array($ID,(array)$remove_duplicate)){
					 
				 
				 $remove_duplicate[]= $ID;
				 $glow_profit_deduct_user_metakey_yes=get_user_meta($ID, "glow_profit_deduct_user_metakey_yes", true);
				 if($glow_profit_deduct_user_metakey_yes=="Yes"){
					 
					 $date = get_post_meta( $postid, 'close out_fundraiser_date', true );
					 $org = get_user_meta( $ID , 'user_registration_name_of_school_business' , true );
					 $action_status = get_user_meta( $ID , 'user_action_status' , true );
					 $active_date = get_user_meta($ID, "user_close_out_date_active", true);
					 $inactive_date = get_user_meta($ID, "user_close_out_date_inactive", true);
					 $status= get_user_meta( $ID,'user_status', true );
					 $user_rebate = get_user_meta( $ID, 'user_rebate', true );
				
						/*Start: Count Orders in catlog */
					     $cat_totalOrder=0;
						 $user_yes_234 = get_user_meta( $ID, 'glow_profit_deduct_user_metakey_yes', true); 
						 if($user_yes_234=="Yes")
						 {
							 $args = array(
									'status'   => "published",
									'meta_key' => 'fundraiser_field',
									'meta_value' => $ID,
									'limit' => -1
							);
							$orders = wc_get_orders($args);
							foreach ( $orders as $order ) {
									$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
									$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
									$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
									$order_data = $order->get_data();
									$order_customer_id = $order_data['customer_id'];
									$glow_profit_deduct=get_post_meta( $order->get_id(), 'glow_profit_deduct_yes',true);
									
									if ($order->has_status('on-hold') || $order->has_status('completed') || $order->has_status('processing')) {
									  if ($frID3 == $order_customer_id) { 
										  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
										  if($order_type=="Catlog") 
										  {
											  $cat_totalOrder++;
										  }
									 }
								}
							}
						 }
				if($cat_totalOrder>1)
				{
					$arr_order_id2=array();
						 $total_cat_quantity =0; 						
						 $total_sale_so_far2=0;
						 $rebate_so_far2=0;		
						 $item_qty_fund_online=0;
						 $total_sale_so_far_catlog=0;
						 $profit_catlog=0;
						 $total_sales_subtot=0;
						 $total_sale_so_far_catlog_deduct=0;
						 $profit_catlog_a=0;		
					 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                        $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								$glow_profit_deduct=get_post_meta( $order->get_id(), 'glow_profit_deduct_yes',true);
								
								if ($order->has_status('on-hold') || $order->has_status('completed') || $order->has_status('processing')) {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								  if($order_type=="Catlog") 
									 {
										 foreach ($order->get_items() as $item_key => $item_values): 
												$item_data = $item_values->get_data();
												$total_cat_quantity += $item_data['quantity']; 			//Catalog Order qty use in ship to school order 						
										  endforeach;	
										  
										$qty= $total_cat_quantity+$item_qty_fund_online;  
										$new_catalog_order=get_post_meta( $order->get_id(), 'new_catalog_order_50_percent',true);
										/* Start Online Deduction Calculation */
										
										if($cat_totalOrder>1)
										{
											    $glow_profit_deduct_amt=get_post_meta( $order->get_id(), 'glow_profit_deduct_metakey',true);
												$total_sale_so_far_catlog_deduct=get_post_meta( $order->get_id(), 'glow_profit_deduct_metakey_rem',true);
												$tot=$order->get_total(); 
												$shipping_cost=$order->get_shipping_total();
												if($tot==0)
												{
													if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order) ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot+=$subtot; // Total Brochure Sales
														
														$profit_catlog = floatval ($subtot) * floatval (40) / 100; 
														$profit_catlog_a += $profit_catlog;  //Profit
														
													    $sale_total_q = $subtot - $profit_catlog;   //Amt Due
														$sale_total+=$sale_total_q;
														
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total_q + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total_q;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog+=$sale_total_new; 
														
													}  
													else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot+=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (50) / 100;
														
														$profit_catlog_a += $profit_catlog;
														
														$sale_total_q = $subtot - $profit_catlog;
														$sale_total+=$sale_total_q;
														
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total_q + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total_q;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog+=$sale_total_new; 
														
													} 
													else
													{ 
									
													}
												}
												else
												{
													if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order) ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot+=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (40) / 100; 
														$profit_catlog_a += $profit_catlog;
														
														$sale_total_q = $subtot - $profit_catlog;
														$sale_total+=$sale_total_q;
														
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total_q + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total_q;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog+=$sale_total_new; 
													}  
													else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot+=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (50) / 100;
														$profit_catlog_a += $profit_catlog;
														
														$sale_total_q = $subtot - $profit_catlog;
														$sale_total+=$sale_total_q;
														
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total_q + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total_q;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog+=$sale_total_new; 
													} 
													else
													{ 
									
													}
												}
										}
								/* End Online Deduction Calculation */
										  
								} } }
						}
					    
					$glow_profit_deduct_user_metakey_orderID = get_user_meta($ID, "glow_profit_deduct_user_metakey_orderID", true); //orderID
					$glow_profit_deduct_TotalRebate = get_post_meta( $glow_profit_deduct_user_metakey_orderID, 'glow_profit_deduct_metakey_totalRebate', true); // Total fees	
						
					if($total_sale_so_far_catlog>$glow_profit_deduct_TotalRebate)	
					{
						$glow_profit_deduct_user_metakey_amount=$glow_profit_deduct_TotalRebate;
						$amt_paidNew = $glow_profit_deduct_user_metakey_amount;	
						$rem = 0.00;
					}
					else{
						$glow_profit_deduct_user_metakey_amount=$glow_profit_deduct_TotalRebate-$total_sale_so_far_catlog;
						$amt_paidNew = $total_sale_so_far_catlog;	
						$rem = $glow_profit_deduct_user_metakey_amount;	
					}
						
					
				}	   
				else
				{	
					 $glow_profit_deduct_user_metakey_orderID = get_user_meta($ID, "glow_profit_deduct_user_metakey_orderID", true); //orderID
					 $glow_profit_deduct_TotalRebate = get_post_meta( $glow_profit_deduct_user_metakey_orderID, 'glow_profit_deduct_metakey_totalRebate', true); // Total fees
					 $glow_profit_deduct_TotalRebate = glow_fc_number_format($glow_profit_deduct_TotalRebate, 2, '.', '');	
					 
					 $rem=get_post_meta($glow_profit_deduct_user_metakey_orderID, "glow_profit_deduct_metakey_rem", true); //Remaining fees
					 $rem = glow_fc_number_format($rem, 2, '.', '');	
					 
					 $glow_profit_deduct_user_metakey_amount=get_user_meta($ID, "glow_profit_deduct_user_metakey_amount", true);
					 $amt_paid=abs($glow_profit_deduct_user_metakey_amount);  //paid fees
					 $amt_paidNew = glow_fc_number_format($amt_paid, 2, '.', '');	
				}
					 $glow_profit_deduct_user_metakey_orderID = get_user_meta($ID, "glow_profit_deduct_user_metakey_orderID", true); //orderID
					 
					 $user_rebate_remainpaid = get_user_meta( $ID, 'user_rebate_remainpaid', true );
					 $user_rebate_remainpaidAmt = get_user_meta( $ID, 'user_rebate_remainpaidAmt', true );
					 $mergeOrder=get_user_meta( $ID, 'wc_ship_mergeOrderID', true );
					 
					 
			 ?>
								
			<tr id="<?php echo $postid; ?>" data-st="<?php if(!empty($mergeOrder)){ echo $mergeOrder; } else { echo "-"; } ?>" style="width: 100%;padding: 0px; max-width: 1000px;margin: 0 auto;">
		
				<td id="1_" class="fr-order-col fr-order-id" style="width: 6%;vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border-right: 1px solid #ddd;"><?php echo "#".$ID; ?></td>
				<td id="11_" class="fr-order-col fr-order-id" style="width: 6%;vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border-right: 1px solid #ddd;"><?php echo  $cat_totalOrder; ?></td>
				<td  id="9_" class="fr-order-col fr-order-id" style="width: 6%;vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border-right: 1px solid #ddd;"><?php if(!empty($glow_profit_deduct_user_metakey_orderID)) { echo "#". $glow_profit_deduct_user_metakey_orderID; } else{} ?></td>
				<td  id="2_" class="fr-order-col fr-order-id" style="width: 10%;vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border-right: 1px solid #ddd;"><?php echo $date; ?></td>
				<td  id="3_" class="fr-order-col fr-order-id" style="width: 20%;vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border-right: 1px solid #ddd;"><?php echo $org; ?></td>
				<td id="4_" class="" style="width: 6%;vertical-align: top; text-align: center; "><?php if($action_status==1){?><span id="action_s" class="label label-success">Active</span><?php }  else if($action_status==2){?><span id="action_s2" class="label label-danger">Inactive</span><?php }  else { ?><span id="action_st" class="label label-success">Active</span><?php } ?></td>
				<td id="5_" class="" style="width: 6%;vertical-align: top; text-align: center; "><?php if($status==1){?><span id="action_status_<?php echo $ID;?>" data-paid="<?php echo $user_rebate; ?>" class="label label-success">Paid</span><?php }  else if($status==2){?><span id="action_status_<?php echo $ID;?>" class="label label-danger">Unpaid</span><?php }  else { ?><span id="action_status_<?php echo $ID;?>" class="label label-danger">Unpaid</span><?php } ?></td>
				<td  id="6_" class="fr-order-col fr-order-id" style="width: 10%;vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border-right: 1px solid #ddd;"><?php if(!empty($glow_profit_deduct_TotalRebate)) { echo "$".$glow_profit_deduct_TotalRebate; } else{ echo "$0.00";} ?></td>
				<td  id="8_" class="fr-order-col fr-order-id" style="width: 10%;vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border-right: 1px solid #ddd;"><?php if(!empty($amt_paidNew)) { echo "$".$amt_paidNew; } else { echo "$0.00"; } ?></td>
				<td  id="7_" class="fr-order-col fr-order-id" style="width: 10%;vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border-right: 1px solid #ddd;"><?php if(!empty($rem)) { echo "$".$rem; } else{ echo "$0.00"; } ?></td>
				<td  id="7_" class="fr-order-col fr-order-id" style="width: 10%;vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border-right: 1px solid #ddd;" data-rempaid="<?php echo $user_rebate_remainpaidAmt; ?>"><?php if(!empty($user_rebate_remainpaid) && $user_rebate_remainpaid=="Yes") { echo $user_rebate_remainpaid; }  else { } ?></td>
				<td id="10_" style="width: 6%;vertical-align: top; text-align: center; ">
					<div class="btn-toolbar"><div class="first-col" style="margin-bottom:10px;">&nbsp;<a href="<?php echo home_url('/print/'); ?>?fund_id=<?php echo $ID;?>" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print</a></div></div>
				</td>
			</tr>
				 <?php }
				 }
			 endwhile;
			endif;
			?>
		</tbody>
	</table>
	</div>
<?php } else if($_GET['page']=='fundraiser_operations' && ( $_GET['fund_owed'] ?? '' )=="true"){ ?>
<style>
	.fundraiser_summery
	{
		margin: 21px;
    margin-left: 0;
	}
	.fundraiser_summery .btn-custom
	{
		    padding: 10px;
	}
	/*Search Option */
	#fund_search .search-btn
	{
		background-color: #405e6a !important;
		text-shadow: 0px -1px 0px #22404c;
		background-image: none;
		-webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
		-moz-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
		box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
		color: #fff;
		padding: 5px 33px;
		border-radius: 3px;
		border: 0;
		display: inline-block;
		height: 30px;
	}
	
	.messageBox
	{
		color: red;
		margin-bottom: 8px;
		display:none;
		width: 750px;
		margin: 0 auto;
		text-align: left;
		margin-bottom: 3px;
		font-size: 14px;
	}
	.messageBox strong
	{
		font-weight:800;
	}
	.widget-table .widget-content.search-section
	{
		background: transparent;
		padding: 20px;
	}
	#fund_search
	{
		width: 100%;
		margin: 0 auto;
		text-align: center;
		display: inline-block;
	}
	#fund_search .search-item
	{
		width: 750px;
		margin: 0 auto;
		text-align: left;
		margin-bottom: 9px;
		font-size: 14px;
	}
	.payOwe
	{
	    padding: 5px 15px;
		margin-left: 5px;
	}
	#out_F
	{
		display:none;
		    padding: 10px;
    text-align: center;
    font-size: 20px;
	}

/* representative pay popup css 05-05-2023 */
.manual-mass-order-popup_repre_pay .manual-mass-order-popup-wrapper_repre_pay {
    max-width: 730px !important;
    border: none !important;
    border-radius: 10px !important;
}
.manual-mass-order-popup_repre_pay .manual-mass-order-popup-wrapper_repre_pay h3{
	font-size: 40px;
    line-height: 48px;
    color: #212121;
}
.manual-mass-order-popup_repre_pay .manual-mass-order-popup-wrapper_repre_pay table{
	margin: 0 auto;
	margin-top: 30px;
	margin-bottom: 30px;
}
.manual-mass-order-popup_repre_pay .manual-mass-order-popup-wrapper_repre_pay table tr td{
	padding: 10px 20px;
	color: #000;
	font-size: 16px !important;
	width: 50%;
}
.manual-mass-order-popup_repre_pay .manual-mass-order-popup-wrapper_repre_pay table tr td:first-child{
	font-weight: 700;
}
.manual-mass-order-popup_repre_pay .manual-mass-order-popup-wrapper_repre_pay table tr:nth-child(even) {
    background-color: #F4F4F4;
}
.manual-mass-order-popup_repre_pay .manual-mass-order-popup-wrapper_repre_pay .txt-link{
	margin-top: 10px;
	color: #000;
    font-size: 16px;
    font-weight: 600;
}
#submit_repre_status{
	width: 150px;
    margin: 0 auto;
    padding: 12px 0;
    background-color: #9EB7B3;
    color: #000;
    font-size: 16px;
    font-weight: 600;
    border: none;
    text-decoration: none;
    border-radius: 8px;
    margin-left: 20px;
}
.manual-mass-order-popup_repre_pay .manual-mass-order-popup-wrapper_repre_pay .repre_pay_selection{
    color: #000;
    font-size: 16px;
    font-weight: 600;	
}
/* end representative pay popup css 05-05-2023 */
	.pagaination_section
	{
		position: relative;
		width: 100%;
		margin: 0 auto;
		text-align: center;
		padding: 40px 0px;
		display: inline-block;
	}
	.pagaination_section a.page-numbers {
    color: black;
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
    border: 1px solid #ddd;
    display: inline-block;
    margin: 0;
    font-size: 14px;
} 

	.pagaination_section span.page-numbers.current {
    background-color: #006799;
    color: white;
    border: 1px solid #006799;
    display: inline-block;
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
}
	#main_unpaid_onerecord
	{
		display:none;
	}

	.hide_load
	{
		display:none;
	}
	.show_load
	{
		display:block;
	}
	#loading_search
	{
		padding: 90px 40px;
		position: absolute;
		text-align: center;
		margin: 0 auto;
		left: 50%;
	}
	.page_links
	{
	padding: 5px 10px;
    margin: 31px 5px;
    display: inline-block;
    background: #405e6a;
    color: #fff;
    text-decoration: none;
	}
	.current
	{
		    opacity: 0.5;
	}
	</style>
<script>
	jQuery(document).ready(function () 
	{
			jQuery(".search-btn").click(function(e){
				
				e.preventDefault();
				var search_id=jQuery("#search_id").val();
				if(search_id!="")
				{
					jQuery(".messageBox").css("display","none");
					jQuery("#search_table table tbody tr").css("display","none");
					jQuery("#search_table table tbody tr#fund_"+search_id).css("display","table-row");
					
				}
				else{
					jQuery(".messageBox").css("display","block");
					jQuery("#search_table table tbody tr").css("display","table-row");
				}
			});
	
			var ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>";
			jQuery(".search-btn3").click(function(e){
				jQuery("#main_unpaid_onerecord tbody").empty();
				jQuery("#main_unpaid_onerecord #loading_search").addClass("show_load");
				e.preventDefault();
				var search_id=jQuery("#search_id").val();
				if(search_id!="")
				{
					 jQuery.ajax({

							type : "post",

							url : ajaxurl,

							data : {action: 'wp_unpaid_fundraiser_ajax_record', search_id:search_id},
			
							success: function(response) {
								console.log(response);
								jQuery("#main_unpaid_onerecord #loading_search").removeClass("show_load");
								jQuery("#main_unpaid_onerecord #loading_search").addClass("hide_load");
								if(response!=0)
								{
									jQuery("#main_unpaid_onerecord tbody").html(response);
									jQuery("#main_unpaid_allrecord").css("display","none");
									jQuery("#main_unpaid_onerecord").css("display","block");
								}
								else
								{
									jQuery("#main_unpaid_allrecord").css("display","none");
									jQuery("#main_unpaid_onerecord").css("display","block");
									jQuery("#main_unpaid_onerecord tbody").html("<p style='margin: 0 auto;padding: 50px;position: absolute;left: 44%;'>No Results!!</p>");
								}
								
								
							}
						});
					
				}
				else{
					window.location="<?php echo admin_url(); ?>admin.php?page=fundraiser_operations&fund_owed=true";
				}
			});
		});
</script>
	<div class="proccessloader">
		<div class="inner">
		<img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/12/fd420f3a680e39cde8bda39f5f996964.gif"/>
		<p>Successfully Updated!!</p>
		</div>
	</div>
	
	 <div class="proccessloader2">
		<div class="inner">
			<img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/12/fd420f3a680e39cde8bda39f5f996964.gif"/>
			<p>Email Successfully Sent.</p>
		</div>
	</div>
	<div id="" class="manual-mass-order-popup">
        <div class="manual-mass-order-popup-wrapper">
		<p></p>
		<span>Status Action</span>
           <input type="radio" id="st_1" class="status_type" name="paid_type" value="1" >Set paid<input type="radio" id="st_2" class="status_type" name="paid_type" value="2">Unpaid
			   <input id="submit_status" type="submit" name="submit" value="Submit" data-id="" data-action="" data-rebate="">
			 <a href="#" id="m-txt-link" class="txt-link">Cancel</a>
        </div>
    </div>
	<div id="" class="manual-mass-order-popup_action">
        <div class="manual-mass-order-popup-wrapper_action">
		<p></p>
		<span>Active/Inactive Action</span>
           <input type="radio" id="a_st_1" class="a_status_type" name="action_type" value="1" >Active<input type="radio" id="a_st_2" class="a_status_type" name="action_type" value="2">Inactive
			   <input id="submit_action_status" type="submit" name="submit" value="Submit" data-id="" data-action="">
			 <a href="#" id="m-txt-link" class="txt-link">Cancel</a>
        </div>
    </div>
  
	<div class="widget widget-table">	
		<div class="admin-report"></div>
		<div class="btn-toolbar fundraiser_summery">
			<a href="?page=fundraiser_operations" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Operations</a>
			<a href="?page=fundraiser_operations&fundraiser_report=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Report</a>
			<a href="?page=fundraiser_operations&paid=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Paid Fundraiser Summary</a>
			<a href="?page=fundraiser_operations&fundraiser_summery=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Summary</a>
			<a href="?page=fundraiser_operations&print_order=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print Order</a>
			<a href="?page=fundraiser_operations&request=close" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Close Out</a>
			<a href="?page=fundraiser_operations&closed=closed_fundraisers" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Closed Fundraisers</a>
			<a href="?page=fundraiser_operations&search=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Search</a>
			<a href="?page=fundraiser_operations&gpd=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Online Profits Deduction</a><br/><br/>
			<a href="?page=fundraiser_operations&fund_owed=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Owed Fundraiser</a>
		</div>	
		<div id="wrap" class="widget-content search-section" style="clear: both;">
		<div class="messageBox"><strong>Error!!</strong> Please Enter Fundraiser ID.</div>
			<form action="#" name="fund_search" id="fund_search" method="get">
				<div class="row">
					<div class="span6">
						<div class="search-item">Enter Fundraiser ID:</div> <input type="text" name="search_id" size="100" value="" id="search_id"><input type="submit" value="Submit" class="btn btn-primary search-btn3">
					</div>
					
				</div>
			</form>
		</div>
	<div class="widget-header">						
		<h3>Owed Fundraiser</h3>
	</div>
	<div id="main_unpaid_onerecord" class="widget-content" style="clear: both;">
			<table class="table table-bordered table-striped table-highlight responsive">
		<thead>
		  <tr>
			<th id="" style="vertical-align: top; text-align: center;">Fund ID:</th>
			<th id="" style="vertical-align: top; text-align: left;">Organization:</th>
			<th id="" style="vertical-align: top; text-align: left;">Director:</th>
			<th id="" style="vertical-align: top; text-align: center;">Total Rebate:</th>
			<th id="" style="vertical-align: top; text-align: center;">Paid Rebate:</th>
			<th id="" style="vertical-align: top; text-align: center;">Remaining Rebate<br/>Left:</th>
			<th id="" style="vertical-align: top; text-align: center;">Close Out Date:</th>
			<th id="" style="vertical-align: top; text-align: center;">Status:</th>
			<th id="" style="vertical-align: top; text-align: center;">Fund Status:</th>
			<th id="" style="vertical-align: top; text-align: center;">Final Payment:</th>
			<th id="" style="vertical-align: top; text-align: center;">Action:</th>
		 </tr>
		</thead>
				<tbody>
		
					<img id="loading_search" src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/08/ajax-loader.gif" class="hide_load">
		
				</tbody>
			</table>
	</div>
	<div id="main_unpaid_allrecord" class="widget-content" style="clear: both;">
	<div id="search_table" class="widget-content" style="clear: both;">
		<table class="table table-bordered table-striped table-highlight responsive">
		<thead>
		  <tr>
			<th id="" style="vertical-align: top; text-align: center;">Fund ID:</th>
			<th id="" style="vertical-align: top; text-align: left;">Organization:</th>
			<th id="" style="vertical-align: top; text-align: left;">Director:</th>
			<th id="" style="vertical-align: top; text-align: center;">Total Rebate:</th>
			<th id="" style="vertical-align: top; text-align: center;">Paid Rebate:</th>
			<th id="" style="vertical-align: top; text-align: center;">Remaining Rebate:</th>
			<th id="" style="vertical-align: top; text-align: center;">Close Out Date:</th>
			<th id="" style="vertical-align: top; text-align: center;">Status:</th>
			<th id="" style="vertical-align: top; text-align: center;">Fund Status:</th>
			<th id="" style="vertical-align: top; text-align: center;">Action:</th>
		 </tr>
		</thead>
		<tbody>
		<?php 
				//echo $test = ceil(1.3); 
				$q=1;
				$total_user_IDS=array();
				$total_pages = 1;
				$total_users2=0;
				//$users_per_page = 50;
				$arg=array('post_type'=>'close_out','posts_per_page' => -1,'order' => 'DESC');
				$table_query= new WP_Query();
				$user_count = $table_query->query($arg);
				// count the number of users found in the query
			    $total_usersddd = $user_count ? count($user_count) : 1;

				// grab the current page number and set to 1 if no page number is set
				//$page = isset($_GET['pg']) ? $_GET['pg'] : 1;
				//$total_pages = ceil($total_users / $users_per_page); 
				$per_page_record = 20;
				if (isset($_GET["pg"])){    
				  $page = $_GET["pg"];    
				}    
				else {    
				   $page=1;    
				}
				
				if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
				$postid = $table_query->post->ID; 
				$ID = get_post_meta( $postid, 'close out_fundraiser_id', true );
				if(!in_array($ID,(array)$remove_duplicate2))
				{
				$status= get_user_meta( $ID , 'user_status' , true );
				$action_status= get_user_meta( $ID , 'user_action_status' , true );
				$close_out = get_user_meta($ID, "user_close_out_value", true);	
				$user_rebate_remainpaid = get_user_meta( $ID, 'user_rebate_remainpaid', true );
				if((empty($status) && $action_status==2) || ($status!=1 && $action_status==2) || ($status!=1 && $action_status==2 && $close_out==1))
				{
				
					$glow_profit_deduct_user_metakey_yes=get_user_meta($ID, "glow_profit_deduct_user_metakey_yes", true);
					if($glow_profit_deduct_user_metakey_yes=="Yes"){
						if(empty($user_rebate_remainpaid) && $user_rebate_remainpaid!="Yes") 
						{  
							 $rem_val=0;
							 $amt_paidNew=0;
							 $glow_profit_deduct_TotalRebate=0;
							 $org = get_user_meta( $ID , 'user_registration_name_of_school_business' , true );
							 $action_status = get_user_meta( $ID , 'user_action_status' , true );
							 $active_date = get_user_meta($ID, "user_close_out_date_active", true);
							 $inactive_date = get_user_meta($ID, "user_close_out_date_inactive", true);
							 $status= get_user_meta( $ID,'user_status', true );
							 $user_rebate = get_user_meta( $ID, 'user_rebate', true );
				
						/*Start: Count Orders in catlog */
					     $cat_totalOrder=0;
						 $user_yes_234 = get_user_meta( $ID, 'glow_profit_deduct_user_metakey_yes', true); 
						 if($user_yes_234=="Yes")
						 {
							 $args = array(
									'status'   => "published",
									'meta_key' => 'fundraiser_field',
									'meta_value' => $ID,
									'limit' => -1
							);
							$orders = wc_get_orders($args);
							foreach ( $orders as $order ) {
									$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
									$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
									$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
									$order_data = $order->get_data();
									$order_customer_id = $order_data['customer_id'];
									$glow_profit_deduct=get_post_meta( $order->get_id(), 'glow_profit_deduct_yes',true);
									
									if ($order->has_status('on-hold') || $order->has_status('completed') || $order->has_status('processing')) {
									  if ($frID3 == $order_customer_id) { 
										  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
										  if($order_type=="Catlog") 
										  {
											  $cat_totalOrder++;
										  }
									 }
								}
							}
						 }
				if($cat_totalOrder>1)
				{
					     $glow_profit_deduct_user_metakey_orderID = get_user_meta($ID, "glow_profit_deduct_user_metakey_orderID", true); //orderID
						 $arr_order_id2=array();
						 $total_cat_quantity =0; 						
						 $total_sale_so_far2=0;
						 $rebate_so_far2=0;		
						 $item_qty_fund_online=0;
						 $total_sale_so_far_catlog=0;
						 $profit_catlog=0;
						 $total_sales_subtot=0;
						 $total_sale_so_far_catlog_deduct=0;
						 $profit_catlog_a=0;		
						 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                        $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								$glow_profit_deduct=get_post_meta( $order->get_id(), 'glow_profit_deduct_yes',true);
								
								if ($order->has_status('on-hold') || $order->has_status('completed') || $order->has_status('processing')) {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								  if($order_type=="Catlog") 
									 {
										 foreach ($order->get_items() as $item_key => $item_values): 
												$item_data = $item_values->get_data();
												$total_cat_quantity += $item_data['quantity']; 			//Catalog Order qty use in ship to school order 						
										  endforeach;	
										  
										$qty= $total_cat_quantity+$item_qty_fund_online;  
										$new_catalog_order=get_post_meta( $order->get_id(), 'new_catalog_order_50_percent',true);
										/* Start Online Deduction Calculation */
										
										if($cat_totalOrder>1)
										{
											    $glow_profit_deduct_amt=get_post_meta( $order->get_id(), 'glow_profit_deduct_metakey',true);
												$total_sale_so_far_catlog_deduct=get_post_meta( $order->get_id(), 'glow_profit_deduct_metakey_rem',true);
												$tot=$order->get_total(); 
												$shipping_cost=$order->get_shipping_total();
												if($tot==0)
												{
													if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order) ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot+=$subtot; // Total Brochure Sales
														
														$profit_catlog = floatval ($subtot) * floatval (40) / 100; 
														$profit_catlog_a += $profit_catlog;  //Profit
														
													    $sale_total_q = $subtot - $profit_catlog;   //Amt Due
														$sale_total+=$sale_total_q;
														
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total_q + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total_q;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog+=$sale_total_new; 
														
													}  
													else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot+=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (50) / 100;
														
														$profit_catlog_a += $profit_catlog;
														
														$sale_total_q = $subtot - $profit_catlog;
														$sale_total+=$sale_total_q;
														
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total_q + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total_q;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog+=$sale_total_new; 
														
													} 
													else
													{ 
									
													}
												}
												else
												{
													if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order) ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot+=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (40) / 100; 
														$profit_catlog_a += $profit_catlog;
														
														$sale_total_q = $subtot - $profit_catlog;
														$sale_total+=$sale_total_q;
														
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total_q + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total_q;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog+=$sale_total_new; 
													}  
													else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot+=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (50) / 100;
														$profit_catlog_a += $profit_catlog;
														
														$sale_total_q = $subtot - $profit_catlog;
														$sale_total+=$sale_total_q;
														
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total_q + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total_q;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog+=$sale_total_new; 
													} 
													else
													{ 
									
													}
												}
										}
								/* End Online Deduction Calculation */
										  
								} } }
						}
					    
					$glow_profit_deduct_user_metakey_orderID = get_user_meta($ID, "glow_profit_deduct_user_metakey_orderID", true); //orderID
					$glow_profit_deduct_TotalRebate = get_post_meta( $glow_profit_deduct_user_metakey_orderID, 'glow_profit_deduct_metakey_totalRebate', true); // Total fees	
						
					if($total_sale_so_far_catlog>$glow_profit_deduct_TotalRebate)	
					{
						$glow_profit_deduct_user_metakey_amount=$glow_profit_deduct_TotalRebate;
						$amt_paidNew = $glow_profit_deduct_user_metakey_amount;	
						$rem_val = 0.00;
					}
					else{
						$glow_profit_deduct_user_metakey_amount=$glow_profit_deduct_TotalRebate-$total_sale_so_far_catlog;
						$amt_paidNew = $total_sale_so_far_catlog;	
						$rem_val = $glow_profit_deduct_user_metakey_amount;	
					}
						
					
				}	   
				else
				{	
					 $glow_profit_deduct_user_metakey_orderID = get_user_meta($ID, "glow_profit_deduct_user_metakey_orderID", true); //orderID
					 $glow_profit_deduct_TotalRebate = get_post_meta( $glow_profit_deduct_user_metakey_orderID, 'glow_profit_deduct_metakey_totalRebate', true); // Total fees
					 $glow_profit_deduct_TotalRebate = glow_fc_number_format($glow_profit_deduct_TotalRebate, 2, '.', '');	//Use 1
					 
					 $rem_val=get_post_meta($glow_profit_deduct_user_metakey_orderID, "glow_profit_deduct_metakey_rem", true); //Remaining fees
					 $rem_val = glow_fc_number_format($rem_val, 2, '.', '');	 //Use 2
					 
					 $glow_profit_deduct_user_metakey_amount=get_user_meta($ID, "glow_profit_deduct_user_metakey_amount", true);
					 $amt_paid=abs($glow_profit_deduct_user_metakey_amount);  //paid fees
					 $amt_paidNew = glow_fc_number_format($amt_paid, 2, '.', '');	 //Use 3
				}
			
		}
	}
	else {
		
		/*Without Brochure Order */
			$rem_val=0;
			$amt_paid=0;
			$amt_paidNew=0;
			$glow_profit_deduct_TotalRebate=0;
			$rebate_so_far=0;
								$argss = array(
										'status'   => "published",
										'meta_key' => 'fundraiser_field',
										'meta_value' => $ID,
										'limit' => -1
									);
								$orders = wc_get_orders($argss);
								foreach ( $orders as $order ) {
									$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
									  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

								  /*  if ($ID3 == $IDD) { */
										$order_data = $order->get_data();
										$order_customer_id = $order_data['customer_id'];
										if ( $order->has_status('completed') || $order->has_status('processing')) {
											$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
											  if($order_type=="Online") 
											  {
											/* Active/Inactive */	
											$order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
											$extract_date=$order_date;
											$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
											$inactive_s=get_user_meta($frID3, "user_action_status", true);
											$in_s=1;
											if ($inactive_date < $extract_date && $inactive_s==2)
											{
												$in_s=2;
											}
											else if($inactive_s==1 || empty($inactive_s))
											{
												$in_s=1;
											}
											else{
												$in_s=1;
											}
											/*$inactive_date="2020-08-29 01:00:01";*/
										if ($in_s ==1){
										  if ($frID3 != $order_customer_id) 
										  {
									   $total_sale_so_far+=$order->get_subtotal();
									 

											   foreach ($order->get_items() as $item_key => $item_values): 
														$item_data = $item_values->get_data();  
													 endforeach; 
											 
											$total_product = $order->get_total();   ///$order_data->get_total();


											$percentage  = 40;
											$percentFifty= 50;

										   $tdiscount=$order->get_total_discount();
												
											if(!empty($tdiscount))
											{
												 $newamt=$order->get_subtotal()-$tdiscount;
												 $totalAmount = $newamt;
											}
											else
											{
											 $totalAmount = $order->get_subtotal();
											}
								
									 
										 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
											$Discount    = ($percentFifty / 100) * $totalAmount;
											$Discount = glow_fc_number_format($Discount, 2, '.', '');
										 $rebate_so_far+=$Discount;
										 }
										 else
										 {
											$Discount    = ($percentage / 100) * $totalAmount;
											$Discount = glow_fc_number_format($Discount, 2, '.', '');
											 $rebate_so_far+=$Discount;
										 }
									   
									  
										   /*} */
								}
									else if ($frID3 == $order_customer_id) 
											{
											  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
											  if($order_type=="Online") 
											  {
												$tdiscount=$order->get_total_discount();
												
												if(!empty($tdiscount))
												{
												  $newamt=$order->get_subtotal()-$tdiscount;
												  $total_sale_so_far+=$newamt;
												}
												else
												{
												 $total_sale_so_far+=$order->get_subtotal();
												}
									 

											   foreach ($order->get_items() as $item_key => $item_values): 
														$item_data = $item_values->get_data();  
													 endforeach; 
											 
												$total_product = $order->get_total();   ///$order_data->get_total();


												$percentage  = 40;
												$percentFifty= 50;

											   $tdiscount=$order->get_total_discount();
													
												if(!empty($tdiscount))
												{
													 $newamt=$order->get_subtotal()-$tdiscount;
													 $totalAmount = $newamt;
												}
												else
												{
												 $totalAmount = $order->get_subtotal();
												}
									
										 
												 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
												 {
													$Discount    = ($percentFifty / 100) * $totalAmount;
													$Discount = glow_fc_number_format($Discount, 2, '.', '');
													$rebate_so_far+=$Discount;
												 }
												 else
												 {
													$Discount    = ($percentage / 100) * $totalAmount;
													$Discount = glow_fc_number_format($Discount, 2, '.', '');
													 $rebate_so_far+=$Discount;
												 }
											}
										} 
										else{}

								  } } } }
			
		/* Ship to School Total Sales and Rebate */
						/* Calculate Catlog Quantity */
						 $arr_order_id2=array();
						 $total_cat_quantity =0; 						
						 $total_sale_so_far2=0;
						 $rebate_so_far2=0;		
						 $item_qty_fund_online=0;
						 
						 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                        $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($order->has_status('on-hold') || $order->has_status('completed') || $order->has_status('processing')) {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								  if($order_type=="Catlog") 
									 {
										 foreach ($order->get_items() as $item_key => $item_values): 
												$item_data = $item_values->get_data();
												$total_cat_quantity += $item_data['quantity']; 			//ship to school								
										  endforeach;	
								} } }
						}
							   
							   
							   
                            $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($scat==1) {
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
                            if ($order_type=='ship-to-school') {
								
									$arr_order_id2[]=$order->get_id();
								
                               /*$total_sale_so_far+=$order->get_subtotal();*/
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far2+=$newamt;
										}
										else
										{
										 $total_sale_so_far2+=$order->get_subtotal();
										}
										$total_sale_so_far2 = glow_fc_number_format($total_sale_so_far2, 2, '.', '');

                                        foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type_2=="ship-to-school") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															
															else{}
                                             endforeach; 
                         } } } }
						}
						$percentageNEW  = 40;
                        $percentFiftyNEW= 50;
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
						if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFiftyNEW / 100) * $total_sale_so_far2;
							$rebate_so_far2 = glow_fc_number_format($Discount, 2, '.', '');
							
						}
                        else
						{
                            $Discount    = ($percentageNEW / 100) * $total_sale_so_far2;
                            $rebate_so_far2 = glow_fc_number_format($Discount, 2, '.', '');
							
						}
						 // End Ship to school
								  
								  
		/*Total NEW Rebate with Ship to school orders */
		
		$rebateSoFar = $rebate_so_far+$rebate_so_far2;
		$glow_profit_deduct_TotalRebate = glow_fc_number_format($rebateSoFar, 2, '.', '');		 //use 1 -total rebate	
		if(!empty($glow_profit_deduct_TotalRebate))
		{
			$amt_paid = get_user_meta($ID,'user_rebate', true);
			if(!empty($amt_paid))
			{
				$amt_paidNew = glow_fc_number_format($amt_paid, 2, '.', '');	//use 3 - paid rebate
				$rem_val = $glow_profit_deduct_TotalRebate-$amt_paid; // use 2 - unpaid rebate
			}
			else{
				$amt_paid=0;
				$amt_paidNew = glow_fc_number_format($amt_paid, 2, '.', '');
				$rem_val=$glow_profit_deduct_TotalRebate;
			}
		}
		else{
			$amt_paidNew=0.00;
			$rem_val=0.00;
		}
		
	}	
			//	if($rem_val!="" || $rem_val!=0 || $rem_val!=0.00 || $rem_val!="0.00")
				if(!empty($rem_val) && $rem_val>0)
				{
					$total_users2++;
					$total_user_IDS[$q]=$ID;
					$total_data[$q]["remval"]=$rem_val;
					$total_data[$q]["totalrebate"]=$glow_profit_deduct_TotalRebate;
					$total_data[$q]["paidval"]=$amt_paidNew;
					$total_data[$q]["id"]=$ID;
							
					//	$total_data[$q]=array("ID" => $ID, "rem" => $rem2, "paid" => $amt_paidNew, "rebate" => $glow_profit_deduct_TotalRebate);
					$q++;
				}
				$remove_duplicate2[]= $ID;	
				}
					
		}
				 endwhile;
				 $total_pages = ceil($total_users2 / $per_page_record); 
				endif;
			//	echo $total_pages;
			//print_r($total_user_IDS);
				//print_r($total_data);
				if(!empty($total_users2))
				{		
					// Show every owed fundraiser; the page splits them into pages of 50 and searches them.
					$lastindex     = 1;
					$total_records = $total_users2;
				}				
				else {    
				   $lastindex=1;  
				   $total_records=1;				   
				}
				//print_r($total_user_IDS);
				//$offset = $users_per_page * ($page - 1);
				//$total_pages = ceil($total_users / $users_per_page); 
	
			/*	if($total_users2<80)
				{
					$c_arg=array('post_type'=>'close_out','order' => 'DESC','posts_per_page' => -1);
				}
				else {
					$c_arg=array('post_type'=>'close_out','order' => 'DESC','posts_per_page' => $users_per_page,'paged' => $page,'offset'=>$offset);
				}
				$table_query= new WP_Query();
				$table_query->query($c_arg);
				if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
				$postid = $table_query->post->ID; 
				$ID = get_post_meta( $postid, 'close out_fundraiser_id', true );
				*/
			//echo $lastindex;
				$total_rows=count($total_data);
				if($total_rows==0)
				{
				?>
				<tr>
				<p style="margin: 0 auto;padding: 50px;position: absolute;left: 44%;top: 100%;">No Results!!</p></tr>
				<?php
				}
				else {
				for($d=$lastindex;$d<=$total_records;$d++)
				{
				//$ID=$total_user_IDS[$d];
				$ID=$total_data[$d]["id"];
				if(!empty($ID)){
				if(!in_array($ID,(array)$remove_duplicate)){
					 
				$remove_duplicate[]= $ID;
				$count=0;
				$status= get_user_meta( $ID , 'user_status' , true );
				//$repre_status= get_user_meta( $ID , 'user_representative_status' , true );
				$action_status= get_user_meta( $ID , 'user_action_status' , true );
				$close_out = get_user_meta($ID, "user_close_out_value", true);
				$inactive_date=get_user_meta($ID, "user_close_out_date_inactive", true);
				$new_user = get_userdata($ID);
				$first_name = $new_user->first_name;
				$last_name = $new_user->last_name;
				
							$rem=$total_data[$d]["remval"];
							$glow_profit_deduct_TotalRebate=$total_data[$d]["totalrebate"];
							$amt_paidNew=$total_data[$d]["paidval"];
							$arr=array();
							$total_sale_so_far=0;
							$rebate_so_far=0;
							
							$all_seller = get_users(array(
					'meta_key'     => 'user_registration_seller_fundraiser',
					'meta_value'   => $ID,
				));
				if ( ! empty( $all_seller ) && is_iterable( $all_seller ) ) {
					foreach ( $all_seller as $seller_item ) {
						$arr[] = $seller_item->ID;
					}
				}

							$org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );

							$member=count($arr);
							$sd=get_user_meta( $ID , 'user_registration_start_date' , true );
							$ed=get_user_meta( $ID , 'user_registration_end_date' , true );
							
							$currentDate=date("Y-m-d");
							$currentDate = date('Y-m-d', strtotime($currentDate));
							$startDate = date('Y-m-d', strtotime($sd));
							$endDate = date('Y-m-d', strtotime($ed));
							
							$active_date=get_user_meta($ID, "user_close_out_date_active", true);
							$inactive_date=get_user_meta($ID, "user_close_out_date_inactive", true);
							$rebate= get_user_meta( $ID , 'user_rebate' , true );
							//$udata = get_userdata( $ID );
							//$registered = $udata->user_registered;
					
					?>
			<tr id="<?php echo "fund_".$ID; ?>" class="<?php echo $startDate; ?> <?php if($rebate_so_far>0){ echo 'payDue'; } ?>" <?php if($rebate_so_far>0){ echo 'data-rebate="'.$rebate_so_far.'"';  echo 'data-sale="'.glow_fc_number_format($total_sale_so_far, 2, '.', '').'"'; } ?>>
				<td id="0_" style="vertical-align: top; text-align: center; "><?php echo $ID;?></td>
				<td id="1_" style="vertical-align: top; text-align: left;width: 180px;"><?php echo $org; ?></td>
				<td id="2_" style="vertical-align: top; text-align: left;"><?php echo $first_name; ?> <?php echo $last_name; ?></td>
				<td id="4_" style="vertical-align: top; text-align: center; "><?php echo "$".$glow_profit_deduct_TotalRebate; ?></td>
				<td id="9_" style="vertical-align: top; text-align: center; "><?php echo "$".$amt_paidNew; ?></td>
				<td id="10_" style="vertical-align: top; text-align: center;font-weight:700; "><?php echo "$".$rem; ?></td>
				<!--<td id="9_" style="vertical-align: top; text-align: center; "><?php echo date( "M d, Y", strtotime( $registered )); ?></td>-->
				<td id="5_" style="vertical-align: top; text-align: center; "><span class="label" style="background-color: #4FE0CB;"> <?php echo $inactive_date; ?></span></td>
				<td id="6_" class="<?php echo $rebate; ?>" style="vertical-align: top; text-align: center; "><?php if($status==1){?><span id="action_status_<?php echo $ID;?>" class="label label-success">Paid</span><?php }  elseif($status==2){?><span id="action_status_<?php echo $ID;?>" class="label label-danger">Unpaid</span><?php }  else if(empty($status)){ ?><span id="action_status_<?php echo $ID;?>" class="label label-danger">Unpaid</span><?php } else{ }  ?></td>
				<td id="8_" class="" style="vertical-align: top; text-align: center; "><?php if($action_status==1){?><span id="action_s" class="label label-success">Active</span><?php }  elseif($action_status==2){?><span id="action_s2" class="label label-danger">Inactive</span><?php }  else { ?><span id="action_st" class="label label-success">Active</span><?php } ?></td>
				<td id="7_" style="vertical-align: top; text-align: center; width: 240px;">
					<div class="btn-toolbar"><div class="first-col" style="margin-bottom:10px;">
					<a href="<?php echo home_url('/print/'); ?>?fund_id=<?php echo $ID;?>" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print</a>&nbsp;
					<a href="#" class="btn btn-inverse btn-mini padtop btn-custom status" data-id="<?php echo $ID;?>" data-action="<?php echo $status;?>" data-rebate="<?php echo $rebate_so_far=glow_fc_number_format($rebate_so_far, 2, '.', ''); ?>">Status</a>&nbsp;
					<a id="send_report" class="btn btn-inverse btn-mini padtop btn-custom" data-fund_id="<?php echo $ID; ?>">Email Report</a></div><div class="second-col">
					
					</div></div>
				</td>
			</tr>
		
			<?php      ?>
<?php /*  } */   } }

		/*	endwhile;
			endif;
			*/
			}
}
			?>
			
		</tbody>
		</table>
		<?php 
				// grab the current query parameters
	//	$query_string = $_SERVER['QUERY_STRING'];

		// The $base variable stores the complete URL to our page, including the current page arg

		// if in the admin, your base should be the admin URL + your page
	//	$base = admin_url('admin.php') . '?' . remove_query_arg('pg', $query_string) . '%_%';

		// if on the front end, your base is the current page
		//$base = get_permalink( get_the_ID() ) . '?' . remove_query_arg('p', $query_string) . '%_%';
		?>
		
		<!--<div id="out_F"></div>-->
	</div>
	<?php 	
	/*	if($total_users2<80){ }  
		else { ?>
			<div class="pagaination_section">
			<?php 
			echo paginate_links( array(
				'base' => $base, // the base URL, including query arg
				'format' => '&pg=%#%', // this defines the query parameter that will be used, in this case "p"
				'prev_text' => __('&laquo; Previous'), // text for previous page
				'next_text' => __('Next &raquo;'), // text for next page
				'total' => $total_pages, // the total number of pages we have
				'current' => $page, // the current page
				'end_size'     => 2,
				'mid_size'     => 1,
				'prev_next'    => true,
				'add_args'     => true,
				'add_fragment' => ''
			));
			?>
		</div>
		<?php } */
		$c_page="";
		$total_pages = 0; // Every row is on the page now; the browser pages them (glow_fo_table_tools).
		for($y=1;$y<=$total_pages;$y++)
		{
			if($y==1)
			{
				if($page==$y)
				{
					$c_page="current";
				}
				$index=1;
				echo "<a class='page_links ".$c_page."' href='".admin_url()."admin.php?page=fundraiser_operations&fund_owed=true&pg=".$y."&lastindex=1'>1</a>";
			}
			else if($y==2)
			{
				$c_page="";
				if($page==$y)
				{
					$c_page="current";
				}
				$index=21;
				echo "<a class='page_links ".$c_page."' href='".admin_url()."admin.php?page=fundraiser_operations&fund_owed=true&pg=".$y."&lastindex=21'>2</a>";
			}
			else if($y==3)
			{
				$c_page="";
				if($page==$y)
				{
					$c_page="current";
				}
				$index=21+20;
				echo "<a class='page_links ".$c_page."' href='".admin_url()."admin.php?page=fundraiser_operations&fund_owed=true&pg=".$y."&lastindex=41'>3</a>";
			}
			else if($y==4)
			{
				$c_page="";
				if($page==$y)
				{
					$c_page="current";
				}
				$index=21+20+20;
				echo "<a class='page_links ".$c_page."' href='".admin_url()."admin.php?page=fundraiser_operations&fund_owed=true&pg=".$y."&lastindex=61'>4</a>";
			}
			else if($y==5)
			{
				$c_page="";
				if($page==$y)
				{
					$c_page="current";
				}
				$index=21+20+20+20;
				echo "<a class='page_links ".$c_page."' href='".admin_url()."admin.php?page=fundraiser_operations&fund_owed=true&pg=".$y."&lastindex=81'>5</a>";
			}
			else if($y==6)
			{
				$c_page="";
				if($page==$y)
				{
					$c_page="current";
				}
				$index=21+20+20+20+20;
				echo "<a class='page_links ".$c_page."' href='".admin_url()."admin.php?page=fundraiser_operations&fund_owed=true&pg=".$y."&lastindex=101'>6</a>";
			}
			else if($y==7)
			{
				$c_page="";
				if($page==$y)
				{
					$c_page="current";
				}
				$index=21+20+20+20+20+20;
				echo "<a class='page_links ".$c_page."' href='".admin_url()."admin.php?page=fundraiser_operations&fund_owed=true&pg=".$y."&lastindex=121'>7</a>";
			}
			else if($y==8)
			{
				$c_page="";
				if($page==$y)
				{
					$c_page="current";
				}
				$index=21+20+20+20+20+20+20;
				echo "<a class='page_links ".$c_page."' href='".admin_url()."admin.php?page=fundraiser_operations&fund_owed=true&pg=".$y."&lastindex=161'>7</a>";
			}
			else if($y==9)
			{
				$c_page="";
				if($page==$y)
				{
					$c_page="current";
				}
				$index=21+20+20+20+20+20+20+20;
				echo "<a class='page_links ".$c_page."' href='".admin_url()."admin.php?page=fundraiser_operations&fund_owed=true&pg=".$y."&lastindex=181'>7</a>";
			}
			else if($y==10)
			{
				$c_page="";
				if($page==$y)
				{
					$c_page="current";
				}
				$index=21+20+20+20+20+20+20+20+20;
				echo "<a class='page_links ".$c_page."' href='".admin_url()."admin.php?page=fundraiser_operations&fund_owed=true&pg=".$y."&lastindex=201'>7</a>";
			}
			else{
				$c_page="";
			}
		}
		?>
		<style>
		#main_unpaid_allrecord .pagaination_section .page-numbers {
			display: none;
		}
		#main_unpaid_allrecord .pagaination_section .next.page-numbers,
		#main_unpaid_allrecord .pagaination_section .prev.page-numbers {
		    display: inline-block;
			background: #2f4050;
			color: #fff;
		}
		
		#main_unpaid_allrecord .pagaination_section a.page-numbers:focus
		{
			box-shadow: none;
		}
		.pagaination_section {
			position: absolute;
			width: auto;
			margin: 0 auto;
			right: 0px;
			padding: 20px 0px;
			display: inline-block;
		}
		</style>
		</div>
</div>
<?php }
else if($_GET['page']=='fundraiser_operations' && ( $_GET['request'] ?? '' )=="close"){ ?>
<style>
	.fundraiser_summery
	{
		margin: 21px;
    margin-left: 0;
	}
	.fundraiser_summery .btn-custom
	{
		    padding: 10px;
	}
	</style>

	<div class="btn-toolbar fundraiser_summery">
			<a href="?page=fundraiser_operations" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Operations</a>
			<a href="?page=fundraiser_operations&fundraiser_report=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Report</a>
			<a href="?page=fundraiser_operations&paid=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Paid Fundraiser Summary</a>
			<a href="?page=fundraiser_operations&fundraiser_summery=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Summary</a>
			<a href="?page=fundraiser_operations&print_order=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print Order</a>
			<a href="?page=fundraiser_operations&request=close" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Close Out</a>
			<a href="?page=fundraiser_operations&gpd=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Online Profits Deduction</a><br/><br/>
			<a href="?page=fundraiser_operations&fund_owed=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Owed Fundraiser</a>
	</div>
	<div class="widget1 widget-table">
		<div class="widget-header">						
			<h3>Close Out Request</h3>
		</div>	
	</div>
	<div id="closeOUT" class="widget-content " style="clear: both;padding: 0;">
			<table class="table table-bordered table-striped table-highlight responsive" style="margin: 0;">
			<thead>
			  <tr>
				<th id="" style="vertical-align: top;text-align: center;">Fund ID:</th>
				<th id="" style="vertical-align: top;text-align: center;">Date</th>
				<th id="" style="vertical-align: top; text-align: center;">Organizations/Rep:</th>
				<th id="" style="vertical-align: top; text-align: center;">Fund Status:</th>
				<th id="" style="vertical-align: top; text-align: center;">Merge Order:</th>
				<th id="" style="vertical-align: top; text-align: center;">Total Items<br>(Ship To School Orders):</th>
				
			 </tr>
			</thead>
			<tbody>
				 <?php
				 $arg=array('post_type'=>'close_out','posts_per_page' => -1,'order' => 'DESC');
				 $table_query= new WP_Query();
				 $table_query->query($arg);
				 if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$ID= get_post_meta( $postid, 'close out_fundraiser_id', true );
					$date= get_post_meta( $postid, 'close out_fundraiser_date', true );
					$org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );
					
					$action_status= get_user_meta( $ID , 'user_action_status' , true );
					$active_date=get_user_meta($ID, "user_close_out_date_active", true);
					$inactive_date=get_user_meta($ID, "user_close_out_date_inactive", true);
					$mergeOrderID=get_user_meta($ID, "wc_ship_mergeOrderID", true);
				    $waitingItems=get_user_meta($ID, "wc_ship_mergeOrderQuanty", true);
				 ?>
				
				<tr id="<?php echo $postid; ?>" style="width: 100%;padding: 0px; max-width: 1000px;margin: 0 auto;">
					 <td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border-right: 1px solid #ddd;"><?php echo "#".$ID; ?></td>
					 <td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border-right: 1px solid #ddd;"><?php echo $date; ?></td>
					 <td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border-right: 1px solid #ddd;"><?php echo $org; ?></td>
					 <td id="8_" class="" style="vertical-align: top; text-align: center; "><?php if($action_status==1){?><span id="action_s" class="label label-success">Active</span><?php }  elseif($action_status==2){?><span id="action_s2" class="label label-danger">Inactive</span><?php }  else { ?><span id="action_st" class="label label-success">Active</span><?php } ?></td>
					<!--<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border-right: 1px solid #ddd;"><div class="btn-toolbar">&nbsp;<a href="#" class="btn btn-inverse btn-mini padtop btn-custom action_status" data-id="<?php echo $ID;?>" data-action="<?php echo $action_status;?>" data-active="<?php echo $active_date;?>" data-inactive="<?php echo $inactive_date;?>">Status</a></div></td>-->
					<td id="11_" style="vertical-align: top; text-align: center; "><?php if(!empty($mergeOrderID)){ echo "<a style='text-decoration: none;' href='".admin_url()."post.php?post=".$mergeOrderID."&action=edit'>".'#'.$mergeOrderID."</a>"; } ?></td>
					<td id="shipto-school-items" class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border-right: 1px solid #ddd;"><?php if(!empty($waitingItems)){ echo $waitingItems; } else{ "."; } ?></td>
				</tr>
				
				<?php
				 endwhile;
				endif;
				?>
			</tbody>
		</table>
	</div>
<?php } else if($_GET['page']=='fundraiser_operations' && ( $_GET['print_order'] ?? '' )=="true"){ ?>
<style>
	.fundraiser_summery
	{
		margin: 21px;
    margin-left: 0;
	}
	.fundraiser_summery .btn-custom
	{
		    padding: 10px;
	}
	#wrap
	{
		padding: 20px;
	}
	.search-item
	{
		    padding-bottom: 7px;
	}
	#ajax-order
	{
		color: red;
    padding: 17px 0px 0px;
    font-size: 16px;
	}
	/*Ship to school */
	#shipbyfid
	{
		color: #000;
		background: #efefef;
		border: 1px solid #000;
		padding: 3px 6px;
		font-size: 14px;
		text-decoration: none;
	}
</style>
<script>
		jQuery(document).ready(function () 
		{
			 var ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>";
			jQuery(".submit-btn").click(function(e){
			e.preventDefault();
			var orderid=jQuery("#orderid").val();
			 jQuery.ajax({

							type : "post",

							url : ajaxurl,

							data : {action: 'order_ajax', orderid:orderid},
			
							success: function(response) {
								console.log(response);
								if(response.data=="Online")
								{
									jQuery("#ajax-order").text("This is Online Order");
								}
								else if(response.data=="Catlog")
								{
									jQuery("#ajax-order").text("");
									window.location="<?php echo home_url('/print-order/'); ?>?order_id="+orderid+"&q=full";
								}
								else
								{
									jQuery("#ajax-order").text("Something went wrong!");
								}
								
							}
						});
			
			});
			// Keep the Ship to School link current while typing, and open it on Enter
			// instead of submitting the form back to this page.
			jQuery("#orderfid").on('input change', function()
			{
				var fundraiser_id = jQuery.trim(jQuery(this).val());
				jQuery('#shipbyfid').attr("href", "/ship-print-order/?fund_id="+encodeURIComponent(fundraiser_id));
			});
			jQuery("#printOrderByFid").on('submit', function(e)
			{
				e.preventDefault();
				if (jQuery.trim(jQuery("#orderfid").val()) !== '') {
					window.open(jQuery('#shipbyfid').attr("href"), '_blank');
				}
			});
		});
</script>
<div class="widget widget-table">
	<div class="btn-toolbar fundraiser_summery">
		<a href="?page=fundraiser_operations" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Operations</a>
		<a href="?page=fundraiser_operations&fundraiser_report=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Report</a>
		<a href="?page=fundraiser_operations&paid=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Paid Fundraiser Summary</a>
		<a href="?page=fundraiser_operations&fundraiser_summery=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Summary</a>
		<a href="?page=fundraiser_operations&print_order=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print Order</a>
		<a href="?page=fundraiser_operations&request=close" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Close Out</a>
		<a href="?page=fundraiser_operations&gpd=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Online Profits Deduction</a><br/><br/>
		<a href="?page=fundraiser_operations&fund_owed=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Owed Fundraiser</a>
	</div>
	<div class="widget-header">						
		<h3>Print Order</h3>
	</div>	
	
	
	<div id="wrap" class="widget-content " style="clear: both;">
		<form action="#" name="printOrder" id="printOrder" method="get">
			<div class="row">
				<div class="span6">
					<div class="search-item">Order ID:</div> <input type="text" name="orderid" size="10" value="" id="orderid"><input type="submit" value="Submit" class="btn btn-primary submit-btn">
					<div id="ajax-order"></div>
				</div>
				
			</div>
			
		</form>
	</div>
	<div id="wrap" class="widget-content " style="clear: both;">
		<form action="#" name="printOrderByFid" id="printOrderByFid" method="get">
			<div class="row">
				<div class="span6">
					<h2 class="ship_school">Ship to School Order</h2>
					<div class="search-item">Fundraiser ID:</div> <input type="text" name="orderfid" size="10" value="" id="orderfid"><a id="shipbyfid" href="#" target="_blank" class="btn btn-primary">Submit</a>
					
				</div>
				
			</div>
			
		</form>
	</div>
	</div>
<?php } else if($_GET['page']=='fundraiser_operations' && ( $_GET['fundraiser_report'] ?? '' )=="true"){ ?>
<style>
	.fundraiser_summery
	{
		margin: 21px;
    margin-left: 0;
	}
	.fundraiser_summery .btn-custom
	{
		    padding: 10px;
	}
	</style>
	<div class="widget widget-table">
	<div class="widget-header">						
		<h3>Fundraisers Report</h3>
	</div>	
	</div>
	<div class="btn-toolbar fundraiser_summery">
		<a href="?page=fundraiser_operations" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Operations</a>
		<a href="?page=fundraiser_operations&fundraiser_report=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Report</a>
		<a href="?page=fundraiser_operations&paid=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Paid Fundraiser Summary</a>
		<a href="?page=fundraiser_operations&fundraiser_summery=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Summary</a>
		<a href="?page=fundraiser_operations&print_order=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print Order</a>
		<a href="?page=fundraiser_operations&request=close" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Close Out</a>
		<a href="?page=fundraiser_operations&gpd=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Online Profits Deduction</a><br/><br/>
		<a href="?page=fundraiser_operations&fund_owed=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Owed Fundraiser</a>
	</div>
	<?php 
			$year=$_GET['syear']; 
			$month=$_GET['smonth'];
			$frep=$_GET['rep'];
			$f_id=$_GET['fundid'];
			
	?>
	<div id="" class="widget-content " style="clear: both;">
		<form action="?page=fundraiser_operations&fundraiser_report=true" name="fund_search" id="fund_search" method="get">
			<div class="row">
				<div class="span6">
					<div class="search-item">Fund ID:</div> <input type="text" name="fundid" size="10" value="<?php echo $f_id; ?>" id="fundraiserid">
				</div>
				<div class="span6">
					<div class="search-item">Rep Name:</div> <input type="text" name="rep" size="40" value="<?php echo $frep; ?>" id="rep">
				</div>
			</div>
			

			<div class="row">
				<div class="span6">
					<div class="search-item">Period:</div> 
					<select name="smonth" id="smonth" style="max-width: 120px;" onchange="" tabindex="1">
						<option value="">Not Selected</option>
						<option value="01" <?php if($month =='01') { echo 'selected'; } ?>>January</option>
						<option value="02" <?php if($month =='02') { echo 'selected'; }?>>February</option>
						<option value="03" <?php if($month =='03') { echo 'selected'; }?>>March</option>
						<option value="04" <?php if($month =='04') { echo 'selected';} ?>>April</option>
						<option value="05" <?php if($month =='05') { echo 'selected';} ?>>May</option>
						<option value="06" <?php if($month =='06') { echo 'selected';} ?>>June</option>
						<option value="07" <?php if($month =='07') { echo 'selected';} ?>>July</option>
						<option value="08" <?php if($month =='08') { echo 'selected'; }?>>August</option>
						<option value="09" <?php if($month =='09') { echo 'selected'; }?>>September</option>
						<option value="10" <?php if($month =='10') { echo 'selected'; }?>>October</option>
						<option value="11" <?php if($month =='11') { echo 'selected'; }?>>November</option>
						<option value="12" <?php if($month =='12') { echo 'selected'; }?>>December</option>
					</select> &nbsp; 
					<select name="syear" id="syear" style="max-width: 120px;" onchange="" tabindex="1">
						<option value="2020" selected="">2020</option>
						<option value="2021">2021</option>
						<option value="2022">2022</option>
						<option value="2023">2023</option>
						<option value="2024">2024</option>
						<option value="2025">2025</option>
						<option value="2026">2026</option>
						<option value="2027">2027</option>
					</select>
				</div>
			</div>

			<div class="row" style="margin-top: 10px;">
				<div class="span4">
				<input type="submit" value="Search" class="btn btn-primary search-btn">
				</div>
			</div>
			<input type="hidden" value="fundraiser_operations" name="page">
			<input type="hidden" value="true" name="fundraiser_report">
		</form>
	</div>
	
	<script>
	/*$(document).ready(function(){
		$(".search-btn").on("click", function(){
			var id==$("#fundraiserid").val();
			var rep==$("#rep").val();
			var smonth=$("#smonth option:selected").val();
			var syear=$("#syear option:selected").val();
		    window.location.href = "<?php echo admin_url(); ?>admin.php?page=fundraiser_operations&fundraiser_report=true&fundid="+id+"&rep="+rep+"&smonth="+month+"&syear"+year;
			});
		
	});*/

	</script>
	<?php  if(!empty($_GET['syear'])){ ?>
<div id="<?php echo $_GET['syear']?>" class="widget widget-table">
	<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;background:#f2f2f2;width:99%;float:left;" class="widget-content ">
		<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:0;width: 100%;overflow: hidden;" class="table table-bordered table-striped table-highlight responsive">
		<thead>
			<tr style=" border-bottom: 1px solid #cdcdcd;transition: all 0.1s ease 0s;">
				<th class="fr-order-col fr-order-id" style=" vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Fund ID</th>
				 <th class="fr-order-col fr-order-date" style=" vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Organization/Rep:</th>
				 <th class="fr-order-col fr-order-seller" style=" vertical-align: top; text-align: left;  width: 16%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Total Orders</th>
				 <th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center;   width: 16%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Total Sales</th>
				 <th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center;  width: 16%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Rebate</th>
				 <th class="fr-order-col fr-order-customer" style=" vertical-align: top; text-align: center;  width: 16%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Order Details</th>
				  <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			</tr>
		</thead>
		<?php
		$total_sales=array();
		$total_rebate=array(); 
			$args = array(
			'role'    => 'Fundraiser',
		   'status'   => "Approved",
			'order'   => 'ASC'
			);
		$users = get_users( $args );
		foreach ( $users as $user ) {
			
		  $status= get_user_meta( $user->ID , 'user_status' , true );
		  $ID=$_GET['fundid'];
		  $rep=$_GET['rep'];
		  $smonth=$_GET['smonth'];
		  $syear=$_GET['syear'];
		  $ID2=0;
		  $Idd=$user->ID;
		  //$org=get_user_meta( $Idd , 'user_registration_input_company' , true );
		  $org=get_user_meta( $Idd , 'user_registration_name_of_school_business' , true );
		  $sd=get_user_meta( $Idd , 'user_registration_start_date' , true );
		  $ed=get_user_meta( $Idd , 'user_registration_end_date' , true );
		  
		  $sdate = explode('-', $sd);
		
		  $total_sale_so_far=0;
		  $rebate_so_far_n=0;
		  if($sdate[0]== $syear)
		  {
			  if(empty($rep) && empty($smonth) && empty($ID) )
			  {
				 $ID2=$user->ID; 
			  }
			  else if(empty($rep) && !empty($smonth) && empty($ID))
			  {
				  if($sdate[1]==$smonth)
				  {
					 $ID2=$user->ID;  
				  }
			  }
			   else if(empty($rep) && !empty($smonth) && !empty($ID))
			  {
				  if($sdate[1]==$smonth && $ID == $Idd)
				  {
					 $ID2=$user->ID;  
				  }
			  }
			   else if(!empty($rep) && !empty($smonth) && !empty($ID))
			  {
				//   $org=get_user_meta( $Idd , 'user_registration_input_company' , true );
				$org=get_user_meta( $Idd , 'user_registration_name_of_school_business' , true );
				  if($sdate[1]==$smonth && $org == $rep && $ID == $Idd )
				  {
					 $ID2=$user->ID;  
				  }
			  }
			   else if(!empty($rep) && empty($smonth) && empty($ID))
			  {
				//   $org=get_user_meta( $Idd , 'user_registration_input_company' , true );
				$org=get_user_meta( $Idd , 'user_registration_name_of_school_business' , true );
				  if($org == $rep)
				  {
					 $ID2=$user->ID;  
				  }
			  }
			   else if(empty($rep) && empty($smonth) && !empty($ID))
			   {
				   if($Idd==$ID)
				   {
					    $ID2=$user->ID;
				   }
			   }
			  else{
				  
			  }
			  
		?>
		<?php  /*  $all_meta_for_users = get_users(array(
                                    'meta_key'     => 'user_registration_seller_fundraiser',
                                    'meta_value'   => $ID2,
                                ));
							
                                    foreach ( $all_meta_for_users as $all_meta_for_user ) { $IDD=$all_meta_for_user->ID; */?>
		<?php  
				 if($ID2 !=0) {
                        $arr=array();
						$total_sale_so_far=0;
					    $rebate_so_far=0;
                        $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID2,
								'limit' => -1
                            );
                        $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                          /*  if ($ID3 == $IDD) { */
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
								/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) {
										$tdiscount=$order->get_total_discount();
										if(!empty($tdiscount))
										{
											$newamt=$order->get_subtotal()-$tdiscount;
											$total_sale_so_far+=$newamt;
										}
										else
										{
											$total_sale_so_far+=$order->get_subtotal();
										}
										foreach ($order->get_items() as $item_key => $item_values): 
											   $item_data = $item_values->get_data();  
										endforeach; 
										 
										$total_product = $order->get_total();   ///$order_data->get_total();

										$percentage  = 40;
										$percentFifty= 50;

										$tdiscount=$order->get_total_discount();
												
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
											 $totalAmount = $order->get_subtotal();
										}
							
								 
										if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
											$Discount    = ($percentFifty / 100) * $totalAmount;
											$rebate_so_far+=$Discount;
										}
										else
										{
											$Discount    = ($percentage / 100) * $totalAmount;
											$Discount = glow_fc_number_format($Discount, 2, '.', '');
											$rebate_so_far+=$Discount;
										}
                                 /*} */
								} 
								else if ($frID3 == $order_customer_id) 
								{
									  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
											$tdiscount=$order->get_total_discount();
											
											if(!empty($tdiscount))
											{
												$newamt=$order->get_subtotal()-$tdiscount;
												$total_sale_so_far+=$newamt;
											}
											else
											{
												$total_sale_so_far+=$order->get_subtotal();
											}
							 
										foreach ($order->get_items() as $item_key => $item_values): 
                                            $item_data = $item_values->get_data();  
                                        endforeach; 
                                     
										$total_product = $order->get_total();   ///$order_data->get_total();

										$percentage  = 40;
										$percentFifty= 50;

									    $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
							
								 
										 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
										 {
											$Discount    = ($percentFifty / 100) * $totalAmount;
											$Discount = glow_fc_number_format($Discount, 2, '.', '');
											$rebate_so_far+=$Discount;
										 }
										 else
										 {
											$Discount    = ($percentage / 100) * $totalAmount;
											$Discount = glow_fc_number_format($Discount, 2, '.', '');
											 $rebate_so_far+=$Discount;
										 }
									}
								} 
								else{}
		 
		 
						  } } } } /* } */
						  
						  
						  /* Ship to School Total Sales and Rebate */
						/* Calculate Catlog Quantity */
						 $arr_order_id2=array();
						 $total_cat_quantity =0; 						
						 $total_sale_so_far2=0;
						 $rebate_so_far2=0;		
						 $item_qty_fund_online=0;
						 
						 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                        $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($order->has_status('on-hold') || $order->has_status('completed') || $order->has_status('processing')) {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								  if($order_type=="Catlog") 
									 {
										 foreach ($order->get_items() as $item_key => $item_values): 
												$item_data = $item_values->get_data();
												$total_cat_quantity += $item_data['quantity']; 			//ship to school								
										  endforeach;	
								} } }
						}
							   
							   
							   
                            $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($scat==1) {
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
										$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
                                        if ($order_type=='ship-to-school') {
											
											$arr_order_id2[]=$order->get_id();
											/*$total_sale_so_far+=$order->get_subtotal();*/
											$tdiscount=$order->get_total_discount();
											if(!empty($tdiscount))
											{
												$newamt=$order->get_subtotal()-$tdiscount;
												$total_sale_so_far2+=$newamt;
											}
											else
											{
												$total_sale_so_far2+=$order->get_subtotal();
											}
											$total_sale_so_far2 = glow_fc_number_format($total_sale_so_far2, 2, '.', '');

                                        foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type_2=="ship-to-school") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															
															else{}
                                        endforeach; 
										
									} } } }
						}
						$percentage  = 40;
                        $percentFifty= 50;
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
						if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFifty / 100) * $total_sale_so_far2;
							$rebate_so_far2 = glow_fc_number_format($Discount, 2, '.', '');
							
						}
                        else
						{
                            $Discount    = ($percentage / 100) * $total_sale_so_far2;
                            $rebate_so_far2 = glow_fc_number_format($Discount, 2, '.', '');
							
						}
						// End Ship to school
						
						//Ship to School
						
						$total_sale_fundraiser = glow_fc_number_format($total_sale_so_far, 2, '.', ''); 
						$total_rebate_fundraiser = glow_fc_number_format($rebate_so_far, 2, '.', '');
						
						$total_sale_fundraiser2 = glow_fc_number_format($total_sale_so_far2, 2, '.', ''); 
						$total_rebate_fundraiser2 = glow_fc_number_format($rebate_so_far2, 2, '.', '');
						
						$total_sale_fundraiser3 = $total_sale_fundraiser + $total_sale_fundraiser2;
						
						$total_sale_fundraiser3 = glow_fc_number_format($total_sale_fundraiser3, 2, '.', '');
						
						$total_rebate_fundraiser3 = $rebate_so_far + $rebate_so_far2;
						
						$total_rebate_fundraiser3 = glow_fc_number_format($total_rebate_fundraiser3, 2, '.', '');
						
						//End Ship to school
						  
						  if($total_sale_fundraiser3!=0 && $total_rebate_fundraiser3!=0){
										
								$total_sale[]=$total_sale_so_far+$total_sale_so_far2;   //Ship to school
								$total_rebate[]=$rebate_so_far+$rebate_so_far2;     //Ship to school  
										
								//$total_sale[]=$total_sale_so_far;
								//$total_rebate[]=$rebate_so_far;
								// $org=get_user_meta( $Idd , 'user_registration_input_company' , true );
								$org=get_user_meta( $Idd , 'user_registration_name_of_school_business' , true );
						
						?>
								
					   <tr style="width: 100%;padding: 0px; max-width: 1000px;margin: 0 auto;">
						<?php 
									$arr_order_id=array();
									$args = array(
									'status'   => "published",
									'meta_key' => 'fundraiser_field',
									'meta_value' => $ID2,
									'limit' => -1
									);
									$orders = wc_get_orders($args);
									foreach ( $orders as $order ) {
										$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
										$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
										$order_data = $order->get_data();
											$order_customer_id = $order_data['customer_id'];
											if ( $order->has_status('completed') || $order->has_status('processing')) {
												/* Active/Inactive */	
												$order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
												$extract_date=$order_date;
												$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
												$inactive_s=get_user_meta($frID3, "user_action_status", true);
												$in_s=1;
												if ($inactive_date < $extract_date && $inactive_s==2)
												{
													$in_s=2;
												}
												else if($inactive_s==1 || empty($inactive_s))
												{
													$in_s=1;
												}
												else{
													$in_s=1;
												}
												/*$inactive_date="2020-08-29 01:00:01";*/
											if ($in_s ==1){
												if ($frID3 != $order_customer_id) {
													$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
													if($order_type=="Online") 
													{
														$arr_order_id[]=$order->get_id();
													}
												} 
												else if($frID3 == $order_customer_id){
														$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
														  if($order_type=="Online") 
														  {
															  $arr_order_id[]=$order->get_id();
														  }
												}
												else{}
											
											} } }
							?>
								
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><?php echo $ID2; ?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><?php echo $org; ?></td>
								    <!--<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"> <?php echo count($arr_order_id);?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $total_sale_so_far=glow_fc_number_format($total_sale_so_far, 2, '.', ''); ?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $rebate_so_far=glow_fc_number_format($rebate_so_far, 2, '.', ''); ?> </td>-->
								    <td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"> <?php $a=count((array) $arr_order_id); $b=count((array) $arr_order_id2); echo $c=$a+$b; ?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php $total_sale_so_far=glow_fc_number_format($total_sale_so_far, 2, '.', ''); $total_sale_so_far2 = glow_fc_number_format($total_sale_so_far2, 2, '.', ''); $totatC=$total_sale_so_far+$total_sale_so_far2; echo $totatC = glow_fc_number_format($totatC, 2, '.', ''); ?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php  $rebate_so_far=glow_fc_number_format($rebate_so_far, 2, '.', ''); $rebate_so_far2 = glow_fc_number_format($rebate_so_far2, 2, '.', ''); $rebC=$rebate_so_far+$rebate_so_far2; echo $rebC = glow_fc_number_format($rebC, 2, '.', '');  ?> </td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><div class="btn-toolbar">&nbsp;<a href="?page=fundraiser_operations&view=true&fund_id=<?php echo $ID2;?>" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom" style="">View</a></div>
									</td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
						</tr>
						<?php  }  } } }
                             if(!empty($total_sale)) { ?>
							   <tr>
							  <td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><b> Total Fundraisers: <?php echo $totalF=count($total_sale); ?></b></td>
								    <td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"> </td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><b>Total Sales : $<?php $totalSales=array_sum($total_sale); echo $Total_sale_so_far=glow_fc_number_format($totalSales, 2, '.', ''); ?></b></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><b>Total Rebate : $<?php $totalRebate=array_sum($total_rebate);echo $Total_rebate_so_far=glow_fc_number_format($totalRebate, 2, '.', ''); ?> </b></td>
								    <td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"></div>
									</td>
							  </tr>
							 <?php  } ?>
    </table>
	</div>
    </div>
	<?php } ?>
<?php }  elseif($_GET['page']=='fundraiser_operations' && ( $_GET['fundraiser_summery'] ?? '' )=="true"){ ?>
<style>
	.fundraiser_summery
	{
		margin: 21px;
		margin-left: 0;
	}
	.fundraiser_summery .btn-custom
	{
		padding: 10px;
	}
	.pagaination_section
	{
		position: relative;
		width: 100%;
		margin: 0 auto;
		text-align: center;
		padding: 40px 0px;
		display: inline-block;
	}
	.pagaination_section a.page-numbers {
    color: black;
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
    border: 1px solid #ddd;
    display: inline-block;
    margin: 0;
    font-size: 14px;
} 

	.pagaination_section span.page-numbers.current {
    background-color: #006799;
    color: white;
    border: 1px solid #006799;
    display: inline-block;
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
}
	</style>
	<div class="btn-toolbar fundraiser_summery">
		<a href="?page=fundraiser_operations" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Operations</a>
		<a href="?page=fundraiser_operations&fundraiser_report=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Report</a>
		<a href="?page=fundraiser_operations&paid=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Paid Fundraiser Summary</a>
		<a href="?page=fundraiser_operations&fundraiser_summery=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Summary</a>
		<a href="?page=fundraiser_operations&print_order=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print Order</a>
		<a href="?page=fundraiser_operations&request=close" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Close Out</a>
		<a href="?page=fundraiser_operations&gpd=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Online Profits Deduction</a><br/><br/>
		<a href="?page=fundraiser_operations&fund_owed=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Owed Fundraiser</a>
	</div>
	<div class="widget widget-table">
<div class="widget-header">						
		<h3>Fundraisers Summary</h3>
	</div>	
<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;background:#f2f2f2;width:99%;float:left;" class="widget-content ">
   <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:0;width: 100%;overflow: hidden;" class="table table-bordered table-striped table-highlight responsive">
       <thead>
		<tr style=" border-bottom: 1px solid #cdcdcd;transition: all 0.1s ease 0s;">
								<!--<th class="fr-order-col fr-order-id" style=" vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Fund ID</th>
                                <th class="fr-order-col fr-order-date" style=" vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Organization/Rep:</th>
                                 <th class="fr-order-col fr-order-seller" style=" vertical-align: top; text-align: left;  width: 16%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Total Orders</th>
                                <th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center;   width: 16%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Total Sales</th>
                                <th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center;  width: 16%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Total Rebate</th>
								<th class="fr-order-col fr-order-customer" style=" vertical-align: top; text-align: center;  width: 16%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Rebate Details</th>
								<th class="fr-order-col fr-order-customer" style=" vertical-align: top; text-align: center;  width: 16%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Order Details</th>-->
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
								<th class="fr-order-col fr-order-id" style=" vertical-align: top; text-align: center;width: 6%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Fund ID</th>
                                <th class="fr-order-col fr-order-date" style=" vertical-align: top; text-align: left;width: 20%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Organization/Rep:</th>
                                 <th class="fr-order-col fr-order-seller" style=" vertical-align: top; text-align: left;  width: 6%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Total Orders</th>
                                <th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center;   width: 6%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Total Sales</th>
                                <th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center;  width: 6%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Total Rebate</th>
								<th class="fr-order-col fr-order-seller" style=" vertical-align: top; text-align: left;  width: 6%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;background: #607d8b;">Total Orders (Ship To School)</th>
                                <th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center;   width: 6%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;background: #607d8b;">Total Sales (Ship To School)</th>
                                <th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center;  width: 6%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;background: #607d8b;">Total Rebate (Ship To School)</th>
								<th class="fr-order-col fr-order-customer" style=" vertical-align: top; text-align: center;  width: 6%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Rebate Details</th>
								<th class="fr-order-col fr-order-customer" style=" vertical-align: top; text-align: center;  width: 6%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Order Details</th>
								</tr>
		</thead>
		<?php
		
		$count_args = array(
					'role'    => 'Fundraiser',
					 'status'   => "published",
					'order'   => 'DESC',
					'orderby' => 'user_registered',
					'meta_query' => array(
									
											 'key'     => 'user_status', 
											'value'   => array('', '2'), 
											'compare' => 'IN'
								)
				);
		$user_count_query = new WP_User_Query($count_args);
		$user_count = $user_count_query->get_results();

		// count the number of users found in the query
		$total_users = $user_count ? count($user_count) : 1;

		// grab the current page number and set to 1 if no page number is set
		$page = isset($_GET['pg']) ? $_GET['pg'] : 1;

		// how many users to show per page
		$users_per_page = 50;

		// calculate the total number of pages.
		$total_pages = 1;
		$offset = $users_per_page * ($page - 1);
		$total_pages = ceil($total_users / $users_per_page); 
		$args = array(
					'role'    => 'Fundraiser',
					 'status'   => "published",
					'order'   => 'DESC',
					'orderby' => 'user_registered',
					'meta_query' => array(
											'key'     => 'user_status', 
											'value'   => array('', '2'), 
											'compare' => 'IN'
									
					),
					'number'    => $users_per_page,
					'offset'    => $offset  // skip the number of users that we have per page  
				);
			/*$users = get_users( $args ); */
			// Create the WP_User_Query object
				$wp_user_query = glow_fo_user_query( $args, $users_per_page, $total_pages );

				// Get the results
				$authors = $wp_user_query->get_results();
				/* $all_meta_for_users = get_users($args); */
			?>
		
			<?php 
			/*
			$args = array(
				'role'    => 'Fundraiser',
				'order'   => 'DESC',
				'orderby' => 'user_registered'
			);
			$users = get_users( $args );
			foreach ( $users as $user ) {
				*/
				
			foreach ( $authors as $user ) 
		    {	
				$title="";
				$status= get_user_meta( $user->ID , 'user_status' , true );
		
				if ($status!=1) { 
					$ID=$user->ID;
					$org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );
					$total_sale_so_far=0;
					$rebate_so_far_n=0;
		
		?>
		
		 <?php
                  /* $all_meta_for_users = get_users(array(
                                    'meta_key'     => 'user_registration_seller_fundraiser',
                                    'meta_value'   => $ID,
                                ));
					 foreach ( $all_meta_for_users as $all_meta_for_user ) { $IDD=$all_meta_for_user->ID;*/ ?>
		 <?php  
				 $arr=array();
				 $total_sale_so_far=0;
				 $rebate_so_far=0;
                 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                 $orders = wc_get_orders($args);
                 foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                          /*  if ($ID3 == $IDD) { */
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) {
									$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                       endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                    $tdiscount=$order->get_total_discount();
											
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
										 $totalAmount = $order->get_subtotal();
									}
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = glow_fc_number_format($Discount, 2, '.', '');
									$rebate_so_far+=$Discount;
								 }
                               
							   /*} */
								} 
								else if ($frID3 == $order_customer_id) 
									{
									  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
										$total_product = $order->get_total();   ///$order_data->get_total();


										$percentage  = 40;
										$percentFifty= 50;

									   $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
							
								 
										 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
										 {
											$Discount    = ($percentFifty / 100) * $totalAmount;
											$Discount = glow_fc_number_format($Discount, 2, '.', '');
											$rebate_so_far+=$Discount;
										 }
										 else
										 {
											$Discount    = ($percentage / 100) * $totalAmount;
											$Discount = glow_fc_number_format($Discount, 2, '.', '');
											 $rebate_so_far+=$Discount;
										 }
									}
								} 
								else{}
		 
		 
		 
						  } } } } /*}*/
						  
						  
					/* Ship to School Total Sales and Rebate */
						/* Calculate Catlog Quantity */
						 $arr_order_id2=array();
						 $total_cat_quantity =0; 						
						 $total_sale_so_far2=0;
						 $rebate_so_far2=0;		
						 $item_qty_fund_online=0;
						 
						 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                        $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($order->has_status('on-hold') || $order->has_status('completed') || $order->has_status('processing'))  {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								  if($order_type=="Catlog") 
									 {
										 foreach ($order->get_items() as $item_key => $item_values): 
												$item_data = $item_values->get_data();
												$total_cat_quantity += $item_data['quantity']; 			//ship to school								
										  endforeach;	
								} } }
						}
							   
							   
							   
                            $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($scat==1) {
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
                            if ($order_type=='ship-to-school') {
								
									$arr_order_id2[]=$order->get_id();
								
                               /*$total_sale_so_far+=$order->get_subtotal();*/
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far2+=$newamt;
										}
										else
										{
										 $total_sale_so_far2+=$order->get_subtotal();
										}
										$total_sale_so_far2 = glow_fc_number_format($total_sale_so_far2, 2, '.', '');

                                        foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type_2=="ship-to-school") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															
															else{}
                                             endforeach; 
                         } } } }
						}
						
						$percentage  = 40;
                        $percentFifty= 50;
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
						if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFifty / 100) * $total_sale_so_far2;
							$rebate_so_far2 = glow_fc_number_format($Discount, 2, '.', '');
							
						}
                        else
						{
                            $Discount    = ($percentage / 100) * $total_sale_so_far2;
                            $rebate_so_far2 = glow_fc_number_format($Discount, 2, '.', '');
							
						}
						
						//Ship to School
						$total_sale_fundraiser = glow_fc_number_format($total_sale_so_far, 2, '.', ''); 
						$total_rebate_fundraiser = glow_fc_number_format($rebate_so_far, 2, '.', '');
						
						$total_sale_fundraiser2 = glow_fc_number_format($total_sale_so_far2, 2, '.', ''); 
						$total_rebate_fundraiser2 = glow_fc_number_format($rebate_so_far2, 2, '.', '');
						
						$total_sale_fundraiser3 = $total_sale_fundraiser + $total_sale_fundraiser2;
						
						$total_sale_fundraiser3 = glow_fc_number_format($total_sale_fundraiser3, 2, '.', '');
						
						$total_rebate_fundraiser3 = $rebate_so_far + $rebate_so_far2;
						
						$total_rebate_fundraiser3 = glow_fc_number_format($total_rebate_fundraiser3, 2, '.', '');
						
						 // End Ship to school
						  
						if ($status==1){ 
								$rebate= get_user_meta( $ID , 'user_rebate' , true );
								$new_rebate=round($total_rebate_fundraiser3-$rebate, 2);
								if($new_rebate!=0)
								{
									$title="*Ext*";
								}	
								else
								{
									$title="";
								}	
							}									
						if($total_rebate_fundraiser3!=0 && $total_sale_fundraiser3!=0){
				?>
								
				<tr style="width: 100%;padding: 0px; max-width: 1000px;margin: 0 auto;">
						<?php 
							$arr_order_id=array();
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) {
									  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										$arr_order_id[]=$order->get_id();
									  }
                                ?>
								<?php   
						} 
						else if($frID3 == $order_customer_id){
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										  $arr_order_id[]=$order->get_id();
									  }
									}
									else{}
						
						} } }
                        ?>
								
									<td class="fr-order-col fr-order-id <?php echo $new_rebate." ".$total_rebate_fundraiser3." ".$rebate; ?>" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><?php echo $ID.$title; ?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><?php echo $org; ?></td>
								    <td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"> <?php echo count($arr_order_id);?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $total_sale_so_far = glow_fc_number_format($total_sale_so_far, 2, '.', ''); ?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $rebate_so_far = glow_fc_number_format($rebate_so_far, 2, '.', ''); ?> </td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"> <?php echo count($arr_order_id2);?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $total_sale_so_far2 = glow_fc_number_format($total_sale_so_far2, 2, '.', ''); ?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $rebate_so_far2 = glow_fc_number_format($rebate_so_far2, 2, '.', ''); ?> </td>
								     <td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><div class="btn-toolbar">&nbsp;<a href="?page=fundraiser_operations&rebate=true&fund_id=<?php echo $ID;?>"  class="btn btn-inverse btn-mini padtop btn-custom" style="">View</a></div>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><div class="btn-toolbar">&nbsp;<a href="?page=fundraiser_operations&view=true&fund_id=<?php echo $ID;?>"  class="btn btn-inverse btn-mini padtop btn-custom" style="">View</a></div>
									</td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
				</tr>
					<!--<input type="hidden" data-paid="<?php echo $status; ?>"> -->
				<?php  }  }  }  ?>
    </table>

		<?php
		// grab the current query parameters
$query_string = $_SERVER['QUERY_STRING'];

// The $base variable stores the complete URL to our page, including the current page arg

// if in the admin, your base should be the admin URL + your page
$base = admin_url('admin.php') . '?' . remove_query_arg('pg', $query_string) . '%_%';

// if on the front end, your base is the current page
//$base = get_permalink( get_the_ID() ) . '?' . remove_query_arg('p', $query_string) . '%_%';
?>
<div class="pagaination_section">
<?php 
echo paginate_links( array(
    'base' => $base, // the base URL, including query arg
    'format' => '&pg=%#%', // this defines the query parameter that will be used, in this case "p"
    'prev_text' => __('&laquo; Previous'), // text for previous page
    'next_text' => __('Next &raquo;'), // text for next page
    'total' => $total_pages, // the total number of pages we have
    'current' => $page, // the current page
    'end_size' => 1,
    'mid_size' => 5,
));
		?>
		</div>
	
	
	</div>
    </div>
<?php
}
elseif($_GET['page']=='fundraiser_operations' && ( $_GET['paid'] ?? '' )=="true"){
?>
<style>
	.fundraiser_summery
	{
		margin: 21px;
		margin-left: 0;
	}
	.fundraiser_summery .btn-custom
	{
		padding: 10px;
	}
	.pagaination_section
	{
		position: relative;
		width: 100%;
		margin: 0 auto;
		text-align: center;
		padding: 40px 0px;
		display: inline-block;
	}
	.pagaination_section a.page-numbers {
    color: black;
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
    border: 1px solid #ddd;
    display: inline-block;
    margin: 0;
    font-size: 14px;
	} 

	.pagaination_section span.page-numbers.current {
    background-color: #006799;
    color: white;
    border: 1px solid #006799;
    display: inline-block;
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
	}
	/*Search Option */
	#fund_search_paid .search-btn3
	{
		background-color: #405e6a !important;
		text-shadow: 0px -1px 0px #22404c;
		background-image: none;
		-webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
		-moz-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
		box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
		color: #fff;
		padding: 5px 33px;
		border-radius: 3px;
		border: 0;
		display: inline-block;
		height: 30px;
	}
	
	.messageBox2
	{
		color: red;
		margin-bottom: 8px;
		display:none;
		width: 750px;
		margin: 0 auto;
		text-align: left;
		margin-bottom: 3px;
		font-size: 14px;
	}
	.messageBox2 strong
	{
		font-weight:800;
	}
	.widget-table .widget-content.search-section
	{
		background: transparent;
		padding: 20px;
	}
	#fund_search_paid
	{
		width: 100%;
		margin: 0 auto;
		text-align: center;
		display: inline-block;
	}
	#fund_search_paid .search-item
	{
		width: 750px;
		margin: 0 auto;
		text-align: left;
		margin-bottom: 9px;
		font-size: 14px;
	}
	#main_paid_onerecord
	{
		display:none;
	}

	.hide_load
	{
		display:none;
	}
	.show_load
	{
		display:block;
	}
	#loading_search
	{
		padding: 90px 40px;
		position: absolute;
		text-align: center;
		margin: 0 auto;
		left: 50%;
	}
	</style>
	<script>
		jQuery(document).ready(function () 
		{
			var ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>";
			jQuery(".search-btn3").click(function(e){
				jQuery("#main_paid_onerecord tbody").empty();
				jQuery("#main_paid_onerecord #loading_search").addClass("show_load");
				e.preventDefault();
				var search_id=jQuery("#search_id").val();
				if(search_id!="")
				{
					 jQuery.ajax({

							type : "post",

							url : ajaxurl,

							data : {action: 'wp_paid_fundraiser_ajax_record', search_id:search_id},
			
							success: function(response) {
								console.log(response);
								jQuery("#main_paid_onerecord #loading_search").removeClass("show_load");
								jQuery("#main_paid_onerecord #loading_search").addClass("hide_load");
								if(response!=0)
								{
									jQuery("#main_paid_onerecord tbody").html(response);
									jQuery("#main_paid_allrecord").css("display","none");
									jQuery("#main_paid_onerecord").css("display","block");
								}
								else
								{
									jQuery("#main_paid_allrecord").css("display","none");
									jQuery("#main_paid_onerecord").css("display","block");
									jQuery("#main_paid_onerecord tbody").html("<p style='margin: 0 auto;padding: 50px;position: absolute;left: 44%;'>No Results!!</p>");
								}
								
								
							}
						});
					
				}
				else{
					window.location="<?php echo admin_url(); ?>admin.php?page=fundraiser_operations&paid=true";
				}
			});
		});
</script>
	<div class="btn-toolbar fundraiser_summery">
		<a href="?page=fundraiser_operations" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Operations</a>
		<a href="?page=fundraiser_operations&fundraiser_report=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Report</a>
		<a href="?page=fundraiser_operations&paid=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Paid Fundraiser Summary</a>
		<a href="?page=fundraiser_operations&fundraiser_summery=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Summary</a>
		<a href="?page=fundraiser_operations&print_order=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print Order</a>
		<a href="?page=fundraiser_operations&request=close" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Close Out</a>
		<a href="?page=fundraiser_operations&gpd=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Online Profits Deduction</a><br/><br/>
		<a href="?page=fundraiser_operations&fund_owed=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Owed Fundraiser</a>
	</div>
	<div class="widget widget-table">
		<div class="widget-header">						
			<h3>PAID Fundraisers</h3>
		</div>	
		<div id="wrap" class="widget-content search-section" style="clear: both;">
			<!--<div class="messageBox"><strong>Error!!</strong> Please Enter Fundraiser ID.</div>-->
				<form action="#" name="fund_search_paid" id="fund_search_paid" method="get">
					<div class="row">
						<div class="span6">
							<div class="search-item">Enter Fundraiser ID:</div> <input type="text" name="search_id" size="100" value="" id="search_id"><input type="submit" value="Submit" class="btn btn-primary search-btn3">
						</div>
						
					</div>
				</form>
		</div>
		<div id="main_paid_onerecord" marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;background:#f2f2f2;width:99%;float:left;" class="widget-content">
			<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:0;width: 100%;overflow: hidden;" class="table table-bordered table-striped table-highlight responsive">
      
				<thead>
					<tr style=" border-bottom: 1px solid #cdcdcd;transition: all 0.1s ease 0s;">
							 <th class="fr-order-col fr-order-id" style="width: 6%;vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Fund ID</th>
							 <th class="fr-order-col fr-order-date" style="width: 24%;vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Organization/Rep:</th>
							 <th class="fr-order-col fr-order-seller" style=" vertical-align: top; text-align: left; width: 7%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Total Orders (Online)</th>
							 <th class="fr-order-col fr-order-seller" style=" vertical-align: top; text-align: left; width: 7%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif; background: #607d8b;">Total Orders (Ship To School)</th>		
							 <th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center;  width: 7%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Total Sales (Online)</th>
							 <th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center; width: 7%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;background: #607d8b;">Total Sales (Ship To School)</th>
							 <th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center; width: 7%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;background: #607d8b;">Total Sales (Both)</th>
							 <th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center;  width: 7%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Rebate (Online)</th>
							 <th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center;  width: 7%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;background: #607d8b;">Rebate (Ship To School)</th>
							 <th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center; width: 7%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;background: #607d8b;">Total Rebate (Both)</th>
							 <th class="fr-order-col fr-order-customer" style=" vertical-align: top; text-align: center;  width: 7%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Order Details</th>
					</tr>
				</thead>
				<tbody>
		
					<img id="loading_search" src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/08/ajax-loader.gif" class="hide_load">
		
				</tbody>
			</table>
		</div>
		
		<div id="main_paid_allrecord" marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;background:#f2f2f2;width:99%;float:left;" class="widget-content">
			<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:0;width: 100%;overflow: hidden;" class="table table-bordered table-striped table-highlight responsive">
      
			<thead>
				<tr style=" border-bottom: 1px solid #cdcdcd;transition: all 0.1s ease 0s;">
								
								<th class="fr-order-col fr-order-id" style="width: 6%;vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Fund ID</th>
                                <th class="fr-order-col fr-order-date" style="width: 24%;vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Organization/Rep:</th>
                                 <th class="fr-order-col fr-order-seller" style=" vertical-align: top; text-align: left; width: 7%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Total Orders (Online)</th>
								 <th class="fr-order-col fr-order-seller" style=" vertical-align: top; text-align: left; width: 7%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif; background: #607d8b;">Total Orders (Ship To School)</th>		
								<th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center;  width: 7%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Total Sales (Online)</th>
                                 <th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center; width: 7%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;background: #607d8b;">Total Sales (Ship To School)</th>
								 <th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center; width: 7%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;background: #607d8b;">Total Sales (Both)</th>
								<th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center;  width: 7%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Rebate (Online)</th>
								<th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center;  width: 7%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;background: #607d8b;">Rebate (Ship To School)</th>
								<th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center; width: 7%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;background: #607d8b;">Total Rebate (Both)</th>
								<th class="fr-order-col fr-order-customer" style=" vertical-align: top; text-align: center;  width: 7%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Order Details</th>
								</tr></thead>
		<?php
			
			
		$count_args = array(
					'role'    => 'Fundraiser',
					 'status'   => "published",
					'meta_key'     => 'user_status',
					'meta_value'   => '1',
					'order'   => 'DESC'
				);
		$user_count_query = new WP_User_Query($count_args);
		$user_count = $user_count_query->get_results();

		// count the number of users found in the query
		$total_users = $user_count ? count($user_count) : 1;

		// grab the current page number and set to 1 if no page number is set
		$page = isset($_GET['pg']) ? $_GET['pg'] : 1;

		// how many users to show per page
		$users_per_page = 50;

		// calculate the total number of pages.
		$total_pages = 1;
		$offset = $users_per_page * ($page - 1);
		$total_pages = ceil($total_users / $users_per_page); 
		$args = array(
					'role'    => 'Fundraiser',
					 'status'   => "published",
					'order'   => 'DESC',
					'orderby' => 'user_registered',
					'meta_key'     => 'user_status',
					'meta_value'   => '1',
					'number'    => $users_per_page,
					'offset'    => $offset  // skip the number of users that we have per page  
				);
			/*$users = get_users( $args ); */
			// Create the WP_User_Query object
				$wp_user_query = glow_fo_user_query( $args, $users_per_page, $total_pages );

				// Get the results
				$authors = $wp_user_query->get_results();
				/* $all_meta_for_users = get_users($args); */
			
			
		foreach ( $authors as $user ) 
		{
			$status= get_user_meta( $user->ID , 'user_status' , true );
			if ($status==1) { 
				$ID=$user->ID;
				$org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );
                
		
		?>
		
		 <?php $total_sale_so_far=0;
							  $rebate_so_far=0;
                               $arr=array();
                               /* $all_meta_for_users = get_users(array(
                                    'meta_key'     => 'user_registration_seller_fundraiser',
                                    'meta_value'   => $ID,
                                ));
							
                                    foreach ( $all_meta_for_users as $all_meta_for_user ) { $IDD=$all_meta_for_user->ID; */ ?>
									  <?php  
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                          /*  if ($ID3 == $IDD) { */
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) {
                               /*$total_sale_so_far+=$order->get_subtotal();*/
							        $tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                    $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = glow_fc_number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
                                   /*} */
		                        } 
								else if ($frID3 == $order_customer_id) 
									{
									  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
										$total_product = $order->get_total();   ///$order_data->get_total();


										$percentage  = 40;
										$percentFifty= 50;

									   $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
							
								 
										 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
										 {
											$Discount    = ($percentFifty / 100) * $totalAmount;
											$Discount = glow_fc_number_format($Discount, 2, '.', '');
											$rebate_so_far+=$Discount;
										 }
										 else
										 {
											$Discount    = ($percentage / 100) * $totalAmount;
											$Discount = glow_fc_number_format($Discount, 2, '.', '');
											 $rebate_so_far+=$Discount;
										 }
									}
								} 
								else{}
								
								
								
						  } } } } /* } */?>
						  
						   <?php
						  
						  /* Ship to School Total Sales and Rebate */
						/* Calculate Catlog Quantity */
						 $arr_order_id2=array();
						 $total_cat_quantity =0; 						
						 $total_sale_so_far2=0;
						 $rebate_so_far2=0;		
						 $item_qty_fund_online=0;
						 
						 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                        $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($order->has_status('on-hold') || $order->has_status('completed') || $order->has_status('processing'))  {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								  if($order_type=="Catlog") 
									 {
										 foreach ($order->get_items() as $item_key => $item_values): 
												$item_data = $item_values->get_data();
												$total_cat_quantity += $item_data['quantity']; 			//ship to school								
										  endforeach;	
								} } }
						}
							   
							   
							   
                            $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($scat==1) {
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
                            if ($order_type=='ship-to-school') {
								
									$arr_order_id2[]=$order->get_id();
								
                               /*$total_sale_so_far+=$order->get_subtotal();*/
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far2+=$newamt;
										}
										else
										{
										 $total_sale_so_far2+=$order->get_subtotal();
										}
										$total_sale_so_far2 = glow_fc_number_format($total_sale_so_far2, 2, '.', '');

                                        foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type_2=="ship-to-school") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															
															else{}
                                             endforeach; 
                         } } } }
						}
						$percentage  = 40;
                        $percentFifty= 50;
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
						if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFifty / 100) * $total_sale_so_far2;
							$rebate_so_far2 = glow_fc_number_format($Discount, 2, '.', '');
							
						}
                        else
						{
                            $Discount    = ($percentage / 100) * $total_sale_so_far2;
                            $rebate_so_far2 = glow_fc_number_format($Discount, 2, '.', '');
							
						}
						
						//Ship to School
						$total_sale_fundraiser = glow_fc_number_format($total_sale_so_far, 2, '.', ''); 
						$total_rebate_fundraiser = glow_fc_number_format($rebate_so_far, 2, '.', '');
						
						$total_sale_fundraiser2 = glow_fc_number_format($total_sale_so_far2, 2, '.', ''); 
						$total_rebate_fundraiser2 = glow_fc_number_format($rebate_so_far2, 2, '.', '');
						
						$total_sale_fundraiser3 = $total_sale_fundraiser + $total_sale_fundraiser2;
						
						$total_sale_fundraiser3 = glow_fc_number_format($total_sale_fundraiser3, 2, '.', '');
						
						$total_rebate_fundraiser3 = $rebate_so_far + $rebate_so_far2;
						
						$total_rebate_fundraiser3 = glow_fc_number_format($total_rebate_fundraiser3, 2, '.', '');
					?>
						  
					<?php if($total_sale_fundraiser3!=0 && $total_rebate_fundraiser3!=0){ ?>
					<tr style="width: 100%;padding: 0px; max-width: 1000px;margin: 0 auto;">
       
					<?php 
							$arr_order_id=array();
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                           
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true ); //shiptoschool
								  if ($frID3 != $order_customer_id) {
										if($order_type=="Online") 
										{
								         $arr_order_id[]=$order->get_id();
										}
									} 
									else if($frID3 == $order_customer_id){
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									if($order_type=="Online") 
									{
										 $arr_order_id[]=$order->get_id();
									}
								}
								else{}
		 
							} } }
						?>
								
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><?php echo $ID; ?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><?php echo $org; ?></td>
								    <td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"> <?php echo count($arr_order_id);?></td>
									<!--<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $total_sale_so_far = glow_fc_number_format($total_sale_so_far, 2, '.', ''); ?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $rebate_so_far = glow_fc_number_format($rebate_so_far, 2, '.', ''); ?></td>-->
									 <td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"> <?php echo $b=count($arr_order_id2); ?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $total_sale_so_far = glow_fc_number_format($total_sale_so_far, 2, '.', ''); ?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $total_sale_so_far2 = glow_fc_number_format($total_sale_so_far2, 2, '.', '');?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $total_sale_fundraiserNew = glow_fc_number_format($total_sale_fundraiser3, 2, '.', ''); ?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $rebate_so_far = glow_fc_number_format($rebate_so_far, 2, '.', ''); ?> </td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $rebate_so_far2 = glow_fc_number_format($rebate_so_far2, 2, '.', ''); ?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $total_rebate_fundraiserNew = glow_fc_number_format($total_rebate_fundraiser3, 2, '.', ''); ?></td>										
								   <td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><div class="btn-toolbar">&nbsp;<a href="?page=fundraiser_operations&view=true&fund_id=<?php echo $ID;?>" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom" style="">View</a></div>
									</td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								
							
			 
							
						
			
					
			 
				
          
			
		</tr>
								<?php } ?>
<?php  } }
                              ?>
    </table>
	<?php
		// grab the current query parameters
$query_string = $_SERVER['QUERY_STRING'];

// The $base variable stores the complete URL to our page, including the current page arg

// if in the admin, your base should be the admin URL + your page
$base = admin_url('admin.php') . '?' . remove_query_arg('pg', $query_string) . '%_%';

// if on the front end, your base is the current page
//$base = get_permalink( get_the_ID() ) . '?' . remove_query_arg('p', $query_string) . '%_%';
?>
<div class="pagaination_section">
<?php 
echo paginate_links( array(
    'base' => $base, // the base URL, including query arg
    'format' => '&pg=%#%', // this defines the query parameter that will be used, in this case "p"
    'prev_text' => __('&laquo; Previous'), // text for previous page
    'next_text' => __('Next &raquo;'), // text for next page
    'total' => $total_pages, // the total number of pages we have
    'current' => $page, // the current page
    'end_size' => 1,
    'mid_size' => 5,
));
		?>
		</div>
		
	</div>
    </div>
	<!--</div>-->
<?php } elseif($_GET['page']=='fundraiser_operations' && ( $_GET['view'] ?? '' )=="true") {
	$ID=$_GET['fund_id'];
	?>
	<style>
	.fundraiser_summery
	{
		margin: 21px;
    margin-left: 0;
	}
	.fundraiser_summery .btn-custom
	{
		    padding: 10px;
	}
	</style>
	<div class="btn-toolbar fundraiser_summery">
		<a href="?page=fundraiser_operations" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Operations</a>
		<a href="?page=fundraiser_operations&fundraiser_report=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Report</a>
		<a href="?page=fundraiser_operations&paid=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Paid Fundraiser Summary</a>
		<a href="?page=fundraiser_operations&fundraiser_summery=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Summary</a>
		<a href="?page=fundraiser_operations&print_order=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print Order</a>
		<a href="?page=fundraiser_operations&request=close" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Close Out</a>
		<a href="?page=fundraiser_operations&gpd=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Online Profits Deduction</a><br/><br/>
		<a href="?page=fundraiser_operations&fund_owed=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Owed Fundraiser</a>
	</div>
	<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;background:#f2f2f2;width:100%;float:left;">
   <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:0;width: 98%;overflow: hidden;">
        <tr>
             <td align="center" style="padding:30px; text-align:center;background: -webkit-linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);color: #000;">
				<h1 style="color:#555;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;line-height: 18px"> Order Details </h1>
		  	</td>
		</tr>
		
		 <?php
		 /* Start Print Order List of All Seller */
                               $arr=array();
                                $all_meta_for_users = get_users(array(
                                    'meta_key'     => 'user_registration_seller_fundraiser',
                                    'meta_value'   => $ID,
                                ));
							
						$registered_seller=count($all_meta_for_users);
					if($registered_seller==0)
					{
								?>
								<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
								   <td align="center">
										<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
											<tr>
												<td align="center">
													<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
														<tr>
														   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;">A: <?php echo "Registered Seller"; ?></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sale So Far: <strong>$<span class="fr-total-sale">0.00</span></strong></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Rebate So Far: <strong>$<span class="fr-total-rebate">0.00</span></strong></td>
														</tr>
														<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid red;transition: all 0.1s ease 0s;">
															<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Order</th>
															<th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Date</th>
															<th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Customer</th>
															<th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Seller</th>
															<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Product Sales</th>
															<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Rebate</th>
															<!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
										
														</tr>
													</table>
												</td>
									
											</tr>
									 
										</table>
								  
									</td>
								</tr>
							<?php								
					}
					else
					{
						$total_sale_so_far=0;
							  $rebate_so_far=0;
						 foreach ( $all_meta_for_users as $all_meta_for_user ) { $IDD=$all_meta_for_user->ID; 
						  
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                            if ($ID3 == $IDD) { 
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) 
								{
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) 
								  { 
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
									/*$total_sale_so_far+=$order->get_subtotal();*/
									$total_sale_so_far = glow_fc_number_format($total_sale_so_far, 2, '.', '');

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									 $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount_d = glow_fc_number_format($Discount, 2, '.', '');
									$rebate_so_far+=$Discount_d;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount_d = glow_fc_number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount_d;
								 }
                               
							  
								} } } } }
						}
						 }
					?>
					
					<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
								   <td align="center">
										<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
											<tr>
												<td align="center">
													<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:0px 30px;">
														<tr>
														   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;">A: <?php echo "Registered Seller"; ?></td>
														</tr>
														<<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_registered=glow_fc_number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far_registered=glow_fc_number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
														</tr>
													
													</table>
												</td>
									
											</tr>
									 
										</table>
								  
									</td>
								</tr>
					
					<?php	
					}	
							
                                    foreach ( $all_meta_for_users as $all_meta_for_user ) { $IDD=$all_meta_for_user->ID; ?>
		 <tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                            if ($ID3 == $IDD) { 
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) {
                               $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
									  }
									  else
									  {
										 $total_sale_so_far+=$order->get_subtotal();
									  }
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = glow_fc_number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
								} } } } }
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;"><?php echo $all_meta_for_user->user_firstname; ?> <?php echo $all_meta_for_user->user_lastname; ?></td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far=glow_fc_number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far=glow_fc_number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid red;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == $IDD) { 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) {
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid red;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        $slr_info  = get_userdata($slrID);
                                       ?>
                                       <?php echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = glow_fc_number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = glow_fc_number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                    $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                               if ( $order_customer_id != $ID  ) { //
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = glow_fc_number_format($totalAmt, 2, '.', '');
                               }
                               else{
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
                                 else
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = glow_fc_number_format($Discount, 2, '.', '');
                               }
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
								<?php  } } } } }  
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		   <?php } 
           /* End Print Order List of All Seller */  ?>
		     <?php  
           /* Start Print Order List of Manually Added Seller */  ?>
		    <tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                            if ($ID3 == "custom") { 
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) {
                               $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
									  }
									  else
									  {
										 $total_sale_so_far+=$order->get_subtotal();
									  }
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                    $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = glow_fc_number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
								} } } } }
						}
						
						
						 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							
                        foreach ( $orders as $order ) 
						{
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") 
							{ 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
									if ( $order->has_status('completed') || $order->has_status('processing')) 
									{
										$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
											/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
										 if ($frID3 != $order_customer_id) 
										 {
												$slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
												if($slrID=="custom")
												{
													$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
													$s_name_lower= strtolower($s_name);
													if(!in_array($s_name_lower,(array)$seller_name))
													{
														$seller_name[]=strtolower($s_name);
													}
												}
											 
										 }
									 }
									} 
									}
							}
						}
						/* Calculate seller Total*/
							$args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							
						for($i=0;$i<count((array)$seller_name);$i++) 
						{
						$seller_rebate=0;
							$seller_total_sale=0;
							
                        foreach ( $orders as $order ) 
						{
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") 
							{ 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
									if ( $order->has_status('completed') || $order->has_status('processing')) 
									{
										$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
											/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
										 if ($frID3 != $order_customer_id) 
										 {
												$slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
												
													$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
													$s_name_lower= strtolower($s_name);
													if($seller_name[$i] == $s_name_lower) 
													{
														  foreach ($order->get_items() as $item_key => $item_values): 
															$item_data = $item_values->get_data();  
															endforeach; 
															$total_product = $order->get_total();   ///$order_data->get_total();


															$percentage  = 40;
															$percentFifty= 50;

															/*$totalAmount = $order->get_subtotal();*/
										
															$tdiscount=$order->get_total_discount();
										
															if(!empty($tdiscount))
															{
															  $newamt=$order->get_subtotal()-$tdiscount;
															  $totalAmount=$newamt;
															}
															else
															{
															 $totalAmount=$order->get_subtotal();
															}													/*   if ( $order_customer_id != $ID  ) { //
														 $totalAmt    = ($percentage / 100) * $totalAmount;
														   $Discount = glow_fc_number_format($totalAmt, 2, '.', '');
													   }
													   else{*/
														 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
														 {
															$Discount    = ($percentFifty / 100) * $totalAmount;
															$Discount = glow_fc_number_format($Discount, 2, '.', '');
														 }
														 else
														 {
															$Discount    = ($percentage / 100) * $totalAmount;
															$Discount = glow_fc_number_format($Discount, 2, '.', '');
															
														 }
													  /* }*/
													   $seller_rebate+=$Discount;
													   $seller_total_sale+=$totalAmount;
														
													}
												
											 
										 }
									}
									} }
							}
						}
						$seller_total_Sale_array[]=$seller_total_sale;
						$seller_rebate_array[]=$seller_rebate;
						}
						
						
						
						
						
						
						?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:blue;">B: Manually Added Seller</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far=glow_fc_number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far=glow_fc_number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							<?php
								$exist_seller=count((array)$seller_name);
								if($exist_seller==0){
								?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid blue;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								<?php
								}
								?>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							for($i=0;$i<count((array)$seller_name);$i++)
							{ ?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 18px;margin-top:15px;color:blue;"><?php echo $seller_name[$i]; ?></td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $seller_t=glow_fc_number_format($seller_total_Sale_array[$i], 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $seller_r=glow_fc_number_format($seller_rebate_array[$i], 2, '.', ''); ?></span></strong></td>
								</tr>
						
							<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid blue;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								<?php
						
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") { 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
									if ( $order->has_status('completed') || $order->has_status('processing')) {
										$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
											/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
							     if ($frID3 != $order_customer_id) { 
								 
								 $s_name_meta=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
								 if($seller_name[$i] == strtolower($s_name_meta)) {
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid blue;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if($slrID=="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
											
										}
										else
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = glow_fc_number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = glow_fc_number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = glow_fc_number_format($totalAmt, 2, '.', '');
                               }
                               else{ */
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = glow_fc_number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = glow_fc_number_format($Discount, 2, '.', '');
								 }
                             /*  } */
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
									<?php }  } } } } }
                        }
							}
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		    <?php  
           /* End Print Order List of Manually Added Seller */  ?> 
		   
		    <?php  
           /* Start Print Order List of Seller not mentioned */  ?>
		    <tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                            if (empty($ID3)) { 
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) {
                                   $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
									  }
									  else
									  {
										 $total_sale_so_far+=$order->get_subtotal();
									  }
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                    $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = glow_fc_number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
								} } } } }
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:purple;">C: Seller not mentioned</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:purple;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far=glow_fc_number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:purple;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far=glow_fc_number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid purple;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if (empty($ID3)) { 
								
                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) {
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid purple;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if($slrID=="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else if(is_numeric($slrID))
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
										else
										{
											echo "N/A";
										}  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = glow_fc_number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = glow_fc_number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                    $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                               if ( $order_customer_id != $ID  ) { //
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = glow_fc_number_format($totalAmt, 2, '.', '');
                               }
                               else{
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
                                 else
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = glow_fc_number_format($Discount, 2, '.', '');
                               }
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
								<?php  }  } } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		    <?php  
           /* End Print Order List of Seller not mentioned */  ?> 
		   	<!-- Fundraiser Online Sales-->
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							  
							$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
										/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
                            if ($order_type=='Online') {

                               /*$total_sale_so_far+=$order->get_subtotal();*/
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
										$total_sale_so_far = glow_fc_number_format($total_sale_so_far, 2, '.', '');

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}            
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = glow_fc_number_format($Discount, 2, '.', '');
									$rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = glow_fc_number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
						} } } }  
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:#f54298;">D: Fundraiser Online Sales</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#f54298;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_seller_not=glow_fc_number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#f54298;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far_seller_not=glow_fc_number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #f54298;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
							
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							 $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
							  $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
                             if ($frID3 == $order_customer_id)  
							 {

                               
									if ( $order->has_status('completed') || $order->has_status('processing')) {
											/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
							     if ($order_type=='Online') { 
								 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #f54298;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if(empty($slrID))
										{
											echo "N/A";
										}
										else if($slrID =="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else
										{
											$slr_info  = get_userdata($slrID);
											echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = glow_fc_number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = glow_fc_number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = glow_fc_number_format($totalAmt, 2, '.', '');
                               }
                               else{*/
							   
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = glow_fc_number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = glow_fc_number_format($Discount, 2, '.', '');
								 }
                               /*} */
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
									<?php  } } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		 
		 
		 <!-- Catalog Sale -->
		
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  
							 $total_cat_quantity = 0; 
							 $total_sale_so_far=0;
							 $rebate_so_far=0;
                             $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							  	$new_catalog_order=get_post_meta( $order->get_id(), 'new_catalog_order_50_percent',true);
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								  if($order_type=="Catlog") 
								  {
										$order_item_quantity = 0; 
										foreach ($order->get_items() as $item_key => $item_values): 
											 $item_id   = $item_values->get_id();
											 $item_data = $item_values->get_data();
											 $order_item_quantity += $item_data['quantity']; 
											 $total_cat_quantity += $item_data['quantity']; 			//ship to school												
										endforeach;
										$qty= $order_item_quantity; 
										if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order)) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale_so_far+=$subtot-$to_widthdraw; 
										}  
										else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes") 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
											$total_sale_so_far+=$subtot-$to_widthdraw; 
										} 
										else
										{ 
							
										}
										
									}
							} }
						}           
						?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:#a84032;">E: Fundraiser Catalogue Sales</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#a84032;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_seller_not=glow_fc_number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #a84032;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Customer</th>
                                <!--<th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Seller</th>-->
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Product Sales</th>
                                <!--<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Rebate</th>-->
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							foreach ( $orders as $order ) 
							{
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
								$new_catalog_order=get_post_meta( $order->get_id(), 'new_catalog_order_50_percent',true);
								$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ($frID3 == $order_customer_id)  
								{
									if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
									if ($order_type=='Catlog') { 
										$order_item_quantity = 0; 
										$order_item_quantity = 0; 
										foreach ($order->get_items() as $item_key => $item_values): 
											$item_id   = $item_values->get_id();
											$item_data = $item_values->get_data();
											$order_item_quantity += $item_data['quantity']; 										 
										endforeach;
										$qty= $order_item_quantity; 
										if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order)) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale=$subtot-$to_widthdraw; 
										}  
										else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes") 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
											$total_sale=$subtot-$to_widthdraw; 
										} 
										else
										{ 
						
										}
								 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #a84032;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if(empty($slrID))
										{
											echo "N/A";
										}
										else if($slrID =="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else
										{
											$slr_info  = get_userdata($slrID);
											echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>-->
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">$<?php  echo $total_sale=glow_fc_number_format( $total_sale, 2); ?></td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = glow_fc_number_format($totalAmt, 2, '.', '');
                               }
                               else{*/
							   
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS || !empty($new_catalog_order) && $new_catalog_order == "Yes")
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = glow_fc_number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = glow_fc_number_format($Discount, 2, '.', '');
								 }
                               /*} */
                               echo $Discount;?> </td>-->
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
							<?php  } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		<!-- Online Ship to School -->
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 0px; margin-bottom: 35px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
							   $item_qty_fund_online=0;
                            $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ($scat==1) {
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
                            if ($order_type=='ship-to-school') {

                               /*$total_sale_so_far+=$order->get_subtotal();*/
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
										$total_sale_so_far = glow_fc_number_format($total_sale_so_far, 2, '.', '');

                                        foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type_2=="ship-to-school") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															
															else{}
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();

									/* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}            
                             
                                
                               
							  
						} } } }
						}
						$percentage  = 40;
                        $percentFifty= 50;
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
						if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFifty / 100) * $total_sale_so_far;
							$rebate_so_far = glow_fc_number_format($Discount, 2, '.', '');
							
						}
                        else
						{
                            $Discount    = ($percentage / 100) * $total_sale_so_far;
                            $rebate_so_far = glow_fc_number_format($Discount, 2, '.', '');
							
						}
						?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:#ff5722;">F: Ship to School Orders</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#ff5722;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_seller_not=glow_fc_number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#ff5722;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far_seller_not=glow_fc_number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							    <tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#ff5722;">Total Sold Items: <strong><span class="fr-total-rebate"><?php echo $item_qty_fund_online; ?></span></strong></td>
								</tr>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #ff5722;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Product Sales</th>
                                <!--<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Rebate</th> -->
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
							
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
								$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ($frID3 != $order_customer_id)  
								{
									if ($scat==1) {
										/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
							     if ($order_type=='ship-to-school') { 
								 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #ff5722;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;"> <?php 
                                     $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
										if($slrID=="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else if(is_numeric($slrID))
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
										else
										{
											echo "N/A";
										}
									  
                                       ?>
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = glow_fc_number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = glow_fc_number_format( $sub, 2); }  ?></td>
									
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
							 <?php  } } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
    </table>
	
    </div>
<?php } elseif($_GET['page']=='fundraiser_operations' && ( $_GET['rebate'] ?? '' )=="true")
	{ $ID=$_GET['fund_id'];?>
<style>
.pay_now
{
	    background-color: #405e6a !important;
    text-shadow: 0px -1px 0px #22404c;
    background-image: none;
    -webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
    -moz-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
    color: #fff;
    display: inline-block;
    padding: 4px 10px 4px;
    margin-bottom: 0;
    margin-left: 1.3em;
    font-size: 13px;
    line-height: 18px;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    background-repeat: repeat-x;
    border: 0;
    border-color: #e6e6e6 #e6e6e6 #bfbfbf;
    border-bottom-color: #b3b3b3;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
    text-decoration: none;
    display: inline;
    text-transform: capitalize;
}
</style>
<style>
	.fundraiser_summery
	{
		margin: 21px;
    margin-left: 0;
	}
	.fundraiser_summery .btn-custom
	{
		    padding: 10px;
	}
	.wp_cart_button_wrapper
	{
		display: inline-block;
		margin-left: 10px;
	}
	</style>
	<div class="btn-toolbar fundraiser_summery">
		<a href="?page=fundraiser_operations" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Operations</a>
		<a href="?page=fundraiser_operations&fundraiser_report=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Report</a>
		<a href="?page=fundraiser_operations&paid=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Paid Fundraiser Summary</a>
		<a href="?page=fundraiser_operations&fundraiser_summery=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Summary</a>
		<a href="?page=fundraiser_operations&print_order=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print Order</a>
		<a href="?page=fundraiser_operations&request=close" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Close Out</a>
		<a href="?page=fundraiser_operations&gpd=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Online Profits Deduction</a><br/><br/>
		<a href="?page=fundraiser_operations&fund_owed=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Owed Fundraiser</a>
	</div>
<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;background:#f2f2f2;width:100%;float:left;">
   <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:40px auto;width: 98%;overflow: hidden;">
        <tr>
             <td align="center" style="padding:30px; text-align:center;background: -webkit-linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);color: #000;">
				<h1 style="color:#555;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;line-height: 18px"> Rebate </h1>
		  	</td>
		</tr>
		<?php
		
		 
		 /* rebate Total */
							$total_sale_so_far=0;
							$rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							foreach ( $orders as $order ) 
							{
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) 
								{
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									if($order_type=="Online") 
									{
								  /* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){	
								  if ($frID3 != $order_customer_id) 
								  {
									$tdiscount=$order->get_total_discount();
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
											 $total_sale_so_far+=$order->get_subtotal();
										}
										foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                        endforeach; 
                                     
										$total_product = $order->get_total();   ///$order_data->get_total();


										$percentage  = 40;
										$percentFifty= 50;

									   $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
                        
                             
										 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
										 {
											$Discount    = ($percentFifty / 100) * $totalAmount;
											$rebate_so_far+=$Discount;
										 }
										 else
										 {
											$Discount    = ($percentage / 100) * $totalAmount;
											$Discount = glow_fc_number_format($Discount, 2, '.', '');
											 $rebate_so_far+=$Discount;
										 }
									   
									} 
									else if ($frID3 == $order_customer_id) 
									{
									  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
										$total_product = $order->get_total();   ///$order_data->get_total();


										$percentage  = 40;
										$percentFifty= 50;

									   $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
							
								 
										 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
										 {
											$Discount    = ($percentFifty / 100) * $totalAmount;
											$rebate_so_far+=$Discount;
										 }
										 else
										 {
											$Discount    = ($percentage / 100) * $totalAmount;
											$Discount = glow_fc_number_format($Discount, 2, '.', '');
											 $rebate_so_far+=$Discount;
										 }
									}
								} 
								else{}
								} } }
                            
						}
		 
		  /* Ship to School Total Sales and Rebate */
						/* Calculate Catlog Quantity */
						 $arr_order_id2=array();
						 $total_cat_quantity =0; 						
						 $total_sale_so_far2=0;
						 $rebate_so_far2=0;		
						 $item_qty_fund_online=0;
						 
						 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                        $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($order->has_status('on-hold') || $order->has_status('completed') || $order->has_status('processing')) {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								  if($order_type=="Catlog") 
									 {
										 foreach ($order->get_items() as $item_key => $item_values): 
												$item_data = $item_values->get_data();
												$total_cat_quantity += $item_data['quantity']; 			//ship to school								
										  endforeach;	
								} } }
						}
							   
							   
							   
                            $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($scat==1) {
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
                            if ($order_type=='ship-to-school') {
								
									$arr_order_id2[]=$order->get_id();
								
                               /*$total_sale_so_far+=$order->get_subtotal();*/
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far2+=$newamt;
										}
										else
										{
										 $total_sale_so_far2+=$order->get_subtotal();
										}
										$total_sale_so_far2 = glow_fc_number_format($total_sale_so_far2, 2, '.', '');

                                        foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type_2=="ship-to-school") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															
															else{}
                                             endforeach; 
                         } } } }
						}
						$percentage  = 40;
                        $percentFifty= 50;
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
						if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFifty / 100) * $total_sale_so_far2;
							$rebate_so_far2 = glow_fc_number_format($Discount, 2, '.', '');
							
						}
                        else
						{
                            $Discount    = ($percentage / 100) * $total_sale_so_far2;
                            $rebate_so_far2 = glow_fc_number_format($Discount, 2, '.', '');
							
						}
						
						$total_rebate_3 = $rebate_so_far + $rebate_so_far2;
			
						$total_rebate_3 = glow_fc_number_format($total_rebate_3, 2, '.', '');
						 // End Ship to school
		 
								 $status= get_user_meta( $ID , 'user_status' , true );
								// if( $status==1)
								 $glow_profit_deduct=get_user_meta( $ID,'glow_profit_deduct_user_metakey_yes', true);
								 if($glow_profit_deduct=="Yes")
								 {
									$rebate= get_user_meta( $ID , 'user_rebate' , true );
									$remaining_rebate=round((float)$total_rebate_3-(float)$rebate, 2);
								 }
								 else{
									$rebate= get_user_meta( $ID , 'user_rebate' , true );
									$remaining_rebate=round((float)$total_rebate_3-(float)$rebate, 2);
								 }
								 $rebate_option = get_user_meta($ID, 'user_registration_rebate_option', true );
		?>
		<tr>
		<?php if($rebate_option=="Check"){  $option_val = get_user_meta($ID, 'user_registration_check_payable', true ); ?>
			<td style="display: inline-block;margin-bottom: 0px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;padding: 18px 26px 5px;color: green;">Rebate Pay By Cheque: <span style="color: green;text-transform: capitalize;"><?php echo $option_val; ?><span></td>
		<?php } if($rebate_option=="PayPal"){ $option_val = get_user_meta($ID, 'user_registration_paypal_email', true );  ?>
		   <td style="display: inline-block;margin-bottom: 0px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;padding: 18px 26px 5px;color: green;">Rebate Pay By PayPal: <span style="color: green;text-transform: lowercase;"><?php echo $option_val; ?><span></td>
		<?php } ?>
			<td style="display: block;margin-bottom: 0px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;padding: 5px 26px 5px;color: green;">Total Rebate: <strong>$<span class="fr-total-rebate"><?php echo $total_rebate_3 = glow_fc_number_format($total_rebate_3, 2, '.', '');?></span></strong><?php if($rebate_option=="PayPal"){ echo  do_shortcode('[wp_cart_button name="Fund ID: '.$ID.'" price="'.$total_rebate_3.'"]'); } ?>
			</td>
			<?php  if( $status==1 && $remaining_rebate <= 0 ){ ?>
			 <td class="<?php echo $remaining_rebate; ?> gg" style="display: inline-block;margin-bottom: 0px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;padding: 5px 26px 5px;color: green;">Paid Rebate: <span style="color: #333;text-transform: lowercase;"><?php echo "$".$rebate; ?><span></td>
			
	<?php } else{ ?>
	<td class="<?php echo $remaining_rebate; ?>" style="display: block;margin-bottom: 0px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;padding: 5px 26px 5px;color: green;">Remaining Rebate: <span style="color: green;text-transform: lowercase;"><?php $remaining_r = abs($remaining_rebate); $remaing_rebate_cal=glow_fc_number_format($remaining_r, 2, '.', ''); ?><?php echo "$".$remaing_rebate_cal; ?></span></td>
	<?php } ?>
		</tr>
		 <?php
		 /* Start Print Order List of All Seller */
                               $arr=array();
                                $all_meta_for_users = get_users(array(
                                    'meta_key'     => 'user_registration_seller_fundraiser',
                                    'meta_value'   => $ID,
                                ));
							
						$registered_seller=count($all_meta_for_users);
					if($registered_seller==0)
					{
								?>
								<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
								   <td align="center">
										<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
											<tr>
												<td align="center">
													<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
														<tr>
														   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;">A: <?php echo "Registered Seller"; ?></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sale So Far: <strong>$<span class="fr-total-sale">0.00</span></strong></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Rebate So Far: <strong>$<span class="fr-total-rebate">0.00</span></strong></td>
														</tr>
														<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #cdcdcd;transition: all 0.1s ease 0s;">
															<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Order</th>
															<th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Date</th>
															<th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Customer</th>
															<th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Seller</th>
															<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Product Sales</th>
															<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Rebate</th>
															<!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
										
														</tr>
													</table>
												</td>
									
											</tr>
									 
										</table>
								  
									</td>
								</tr>
							<?php								
					}
					else
					{
						$total_sale_so_far=0;
							  $rebate_so_far=0;
						 foreach ( $all_meta_for_users as $all_meta_for_user ) { $IDD=$all_meta_for_user->ID; 
						  
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                            if ($ID3 == $IDD) { 
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) 
								{
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
								/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){	
								  if ($frID3 != $order_customer_id) 
								  { 
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
									/*$total_sale_so_far+=$order->get_subtotal();*/
									$total_sale_so_far = glow_fc_number_format($total_sale_so_far, 2, '.', '');

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									 $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount_d = glow_fc_number_format($Discount, 2, '.', '');
									$rebate_so_far+=$Discount_d;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount_d = glow_fc_number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount_d;
								 }
                               
							  
								} } } } }
						}
						 }
					?>
					
					<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
								   <td align="center">
										<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
											<tr>
												<td align="center">
													<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:0px 30px;">
														<tr>
														   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;">A: <?php echo "Registered Seller"; ?></td>
														</tr>
														<<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_registered=glow_fc_number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far_registered=glow_fc_number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
														</tr>
													
													</table>
												</td>
									
											</tr>
									 
										</table>
								  
									</td>
								</tr>
					
					<?php	
					}	
					/*Registered Seller */
							
                                    foreach ( $all_meta_for_users as $all_meta_for_user ) { $IDD=$all_meta_for_user->ID; ?>
		 <tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                            if ($ID3 == $IDD) { 
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									if($order_type=="Online") 
									{
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) {
                               $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
									  }
									  else
									  {
										 $total_sale_so_far+=$order->get_subtotal();
									  }
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = glow_fc_number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
								} } } } }
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;"><?php echo $all_meta_for_user->user_firstname; ?> <?php echo $all_meta_for_user->user_lastname; ?></td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far=glow_fc_number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far=glow_fc_number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid red;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == $IDD) { 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) {
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid red;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        $slr_info  = get_userdata($slrID);
                                       ?>
                                       <?php echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = glow_fc_number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = glow_fc_number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                    $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                               if ( $order_customer_id != $ID  ) { //
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = glow_fc_number_format($totalAmt, 2, '.', '');
                               }
                               else{
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
                                 else
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = glow_fc_number_format($Discount, 2, '.', '');
                               }
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
								<?php  } } } } }  
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		   <?php } 
           /* End Print Order List of All Seller */  ?>
		     <?php  
           /* Start Print Order List of Manually Added Seller */  ?>
		    <tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                            if ($ID3 == "custom") { 
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) {
                               $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
									  }
									  else
									  {
										 $total_sale_so_far+=$order->get_subtotal();
									  }
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                    $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = glow_fc_number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
								} } } } }
						}
						
						
						 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							
                        foreach ( $orders as $order ) 
						{
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") 
							{ 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
									if ( $order->has_status('completed') || $order->has_status('processing')) 
									{
										$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									    if($order_type=="Online") 
									    {
										/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
										 if ($frID3 != $order_customer_id) 
										 {
												$slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
												if($slrID=="custom")
												{
													$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
													$s_name_lower= strtolower($s_name);
													if(!in_array($s_name_lower,(array)$seller_name))
													{
														$seller_name[]=strtolower($s_name);
													}
												}
											 
										 }
									 }
									} }
							}
						}
						/* Calculate seller Total*/
							$args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							
						for($i=0;$i<count((array)$seller_name);$i++) 
						{
						$seller_rebate=0;
							$seller_total_sale=0;
							
                        foreach ( $orders as $order ) 
						{
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") 
							{ 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
									if ( $order->has_status('completed') || $order->has_status('processing')) 
									{
										$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
										 if ($frID3 != $order_customer_id) 
										 {
												$slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
												
													$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
													$s_name_lower= strtolower($s_name);
													if($seller_name[$i] == $s_name_lower) 
													{
														  foreach ($order->get_items() as $item_key => $item_values): 
															$item_data = $item_values->get_data();  
															endforeach; 
															$total_product = $order->get_total();   ///$order_data->get_total();


															$percentage  = 40;
															$percentFifty= 50;

															/*$totalAmount = $order->get_subtotal();*/
										
															$tdiscount=$order->get_total_discount();
										
															if(!empty($tdiscount))
															{
															  $newamt=$order->get_subtotal()-$tdiscount;
															  $totalAmount=$newamt;
															}
															else
															{
															 $totalAmount=$order->get_subtotal();
															}													/*   if ( $order_customer_id != $ID  ) { //
														 $totalAmt    = ($percentage / 100) * $totalAmount;
														   $Discount = glow_fc_number_format($totalAmt, 2, '.', '');
													   }
													   else{*/
														 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
														 {
															$Discount    = ($percentFifty / 100) * $totalAmount;
															$Discount = glow_fc_number_format($Discount, 2, '.', '');
														 }
														 else
														 {
															$Discount    = ($percentage / 100) * $totalAmount;
															$Discount = glow_fc_number_format($Discount, 2, '.', '');
															
														 }
													  /* }*/
													   $seller_rebate+=$Discount;
													   $seller_total_sale+=$totalAmount;
														
													}
												
											 
										 }
									} } }
							}
						}
						$seller_total_Sale_array[]=$seller_total_sale;
						$seller_rebate_array[]=$seller_rebate;
						}
						
						
						
						
						
						
						?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:blue;">B: Manually Added Seller</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far=glow_fc_number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far=glow_fc_number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							<?php
								$exist_seller=count((array)$seller_name);
								if($exist_seller==0){
								?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #cdcdcd;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								<?php
								}
								?>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							for($i=0;$i<count((array)$seller_name);$i++)
							{ ?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 18px;margin-top:15px;color:blue;"><?php echo $seller_name[$i]; ?></td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $seller_t=glow_fc_number_format($seller_total_Sale_array[$i], 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $seller_r=glow_fc_number_format($seller_rebate_array[$i], 2, '.', ''); ?></span></strong></td>
								</tr>
						
							<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid blue;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								<?php
						
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") { 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
									if ( $order->has_status('completed') || $order->has_status('processing')) {
										$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
							     if ($frID3 != $order_customer_id) { 
								 
								 $s_name_meta=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
								 if($seller_name[$i] == strtolower($s_name_meta)) {
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid blue;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if($slrID=="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
											
										}
										else
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = glow_fc_number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = glow_fc_number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = glow_fc_number_format($totalAmt, 2, '.', '');
                               }
                               else{ */
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = glow_fc_number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = glow_fc_number_format($Discount, 2, '.', '');
								 }
                             /*  } */
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
									<?php }  } } } } }
                        }
							}
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		    <?php  
           /* End Print Order List of Manually Added Seller */  ?> 
		   
		    <?php  
           /* Start Print Order List of Seller not mentioned */  ?>
		    <tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                            if (empty($ID3)) { 
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) {
                                   $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
									  }
									  else
									  {
										 $total_sale_so_far+=$order->get_subtotal();
									  }
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                    $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = glow_fc_number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
								} } } } }
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:purple;">C: Seller not mentioned</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:purple;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far=glow_fc_number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:purple;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far=glow_fc_number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid purple;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if (empty($ID3)) { 
								
                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) {
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid purple;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if($slrID=="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else if(is_numeric($slrID))
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
										else
										{
											echo "N/A";
										}  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = glow_fc_number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = glow_fc_number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                    $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                               if ( $order_customer_id != $ID  ) { //
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = glow_fc_number_format($totalAmt, 2, '.', '');
                               }
                               else{
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
                                 else
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = glow_fc_number_format($Discount, 2, '.', '');
                               }
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
								<?php  }  } } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		    <?php  
           /* End Print Order List of Seller not mentioned */  ?> 
		   	<!-- Fundraiser Online Sales-->
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							  
							$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
                            if ($order_type=='Online') {

                               /*$total_sale_so_far+=$order->get_subtotal();*/
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
										$total_sale_so_far = glow_fc_number_format($total_sale_so_far, 2, '.', '');

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}            
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = glow_fc_number_format($Discount, 2, '.', '');
									$rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = glow_fc_number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
						} } } }
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:#f54298;">D: Fundraiser Online Sales</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#f54298;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_seller_not=glow_fc_number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#f54298;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far_seller_not=glow_fc_number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #f54298;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
							
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							 $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
							  $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
                             if ($frID3 == $order_customer_id)  
							 {

                               
									if ( $order->has_status('completed') || $order->has_status('processing')) {
										/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
							     if ($order_type=='Online') { 
								 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #f54298;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if(empty($slrID))
										{
											echo "N/A";
										}
										else if($slrID =="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else
										{
											$slr_info  = get_userdata($slrID);
											echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = glow_fc_number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = glow_fc_number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = glow_fc_number_format($totalAmt, 2, '.', '');
                               }
                               else{*/
							   
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = glow_fc_number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = glow_fc_number_format($Discount, 2, '.', '');
								 }
                               /*} */
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
									<?php  } } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		   
		   
		 <!-- Catalog Sale -->
		
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  
						    $total_cat_quantity=0;
						    $total_sale_so_far=0;
							$rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							foreach ( $orders as $order ) {
									   $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
									   $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
									   $new_catalog_order=get_post_meta( $order->get_id(), 'new_catalog_order_50_percent',true);
									   $order_data = $order->get_data();
									   $order_customer_id = $order_data['customer_id'];
									   if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
										  if ($frID3 == $order_customer_id) { 
										  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
										  if($order_type=="Catlog") 
										  {
												
											   $order_item_quantity = 0; 
												  foreach ($order->get_items() as $item_key => $item_values): 
												  $item_id   = $item_values->get_id();
												  $item_data = $item_values->get_data();
												  $order_item_quantity += $item_data['quantity']; 	
												  $total_cat_quantity += $item_data['quantity']; 			//ship to school												
												  endforeach;
												  $qty= $order_item_quantity; 
												if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order)) 
												{ 
													$subtot=$order->get_subtotal(); 
													$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
													$total_sale_so_far+=$subtot-$to_widthdraw; 
												}  
												else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes") 
												{ 
													$subtot=$order->get_subtotal(); 
													$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
													$total_sale_so_far+=$subtot-$to_widthdraw; 
												} 
												else
												{ 
								
												}
										 }
									} }
									}      
								?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:#a84032;">E: Fundraiser Catalogue Sales</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#a84032;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_seller_not=glow_fc_number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #a84032;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Customer</th>
                                <!--<th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Seller</th>-->
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Product Sales</th>
                                <!--<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Rebate</th>-->
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                        $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                        $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
							
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							 $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
							  $new_catalog_order=get_post_meta( $order->get_id(), 'new_catalog_order_50_percent',true);
							  $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
                             if ($frID3 == $order_customer_id)  
							 {

                               
									if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
							     if ($order_type=='Catlog') { 
								  $order_item_quantity = 0; 
										  foreach ($order->get_items() as $item_key => $item_values): 
                                         $item_id   = $item_values->get_id();
                                         $item_data = $item_values->get_data();
											$order_item_quantity += $item_data['quantity']; 										 
										  endforeach;
										  $qty= $order_item_quantity; 
										if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order)) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale=$subtot-$to_widthdraw; 
										}  
										else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
											$total_sale=$subtot-$to_widthdraw; 
										} 
										else
										{ 
						
										}
								 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #a84032;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if(empty($slrID))
										{
											echo "N/A";
										}
										else if($slrID =="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else
										{
											$slr_info  = get_userdata($slrID);
											echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>-->
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">$<?php  echo $total_sale=glow_fc_number_format( $total_sale, 2); ?></td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = glow_fc_number_format($totalAmt, 2, '.', '');
                               }
                               else{*/
							   
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS || !empty($new_catalog_order) && $new_catalog_order == "Yes")
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = glow_fc_number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = glow_fc_number_format($Discount, 2, '.', '');
								 }
                               /*} */
                               echo $Discount;?> </td>-->
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
							<?php  } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>   
		  
		 <!-- Online Ship to School -->
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 0px; margin-bottom: 35px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
							   $item_qty_fund_online=0;
                            $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ($scat==1) {
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
                            if ($order_type=='ship-to-school') {

                               /*$total_sale_so_far+=$order->get_subtotal();*/
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
										$total_sale_so_far = glow_fc_number_format($total_sale_so_far, 2, '.', '');

                                        foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type_2=="ship-to-school") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															
															else{}
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();

									/* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}            
                             
                                
                               
							  
						} } } }
						}
						$percentage  = 40;
                        $percentFifty= 50;
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
						if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFifty / 100) * $total_sale_so_far;
							$rebate_so_far = glow_fc_number_format($Discount, 2, '.', '');
							
						}
                        else
						{
                            $Discount    = ($percentage / 100) * $total_sale_so_far;
                            $rebate_so_far = glow_fc_number_format($Discount, 2, '.', '');
							
						}
						?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:#ff5722;">F: Ship to School Orders</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#ff5722;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_seller_not=glow_fc_number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#ff5722;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far_seller_not=glow_fc_number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							    <tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#ff5722;">Total Sold Items: <strong><span class="fr-total-rebate"><?php echo $item_qty_fund_online; ?></span></strong></td>
								</tr>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #ff5722;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Product Sales</th>
                                <!--<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Rebate</th> -->
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
							
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
								$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ($frID3 != $order_customer_id)  
								{
									if ($scat==1) {
										/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
							     if ($order_type=='ship-to-school') { 
								 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #ff5722;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;"> <?php 
                                     $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
										if($slrID=="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else if(is_numeric($slrID))
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
										else
										{
											echo "N/A";
										}
									  
                                       ?>
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = glow_fc_number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = glow_fc_number_format( $sub, 2); }  ?></td>
									
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
							 <?php  } } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr> 
		   
    </table>
	
    </div>
	<?php }  elseif($_GET['page']=='fundraiser_operations' && ( $_GET['search'] ?? '' )=="true"){ ?>
	<style>
	.fundraiser_summery
	{
		margin: 21px;
		margin-left: 0;
	}
	.fundraiser_summery .btn-custom
	{
		padding: 10px;
	}
	#wrap
	{
		padding: 20px;
	}
	.search-item
	{
		padding-bottom: 7px;
	}
	#fund_search .search-btn
	{
		background-color: #405e6a !important;
		text-shadow: 0px -1px 0px #22404c;
		background-image: none;
		-webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
		-moz-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
		box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
		color: #fff;
		padding: 5px 33px;
		border-radius: 3px;
		border: 0;
		display: inline-block;
		height: 30px;
	}
	#search_table
	{
		    margin-top: 20px;
	}
	#search_table, #search_table table tbody tr
	{
		display:none;
	}
	.messageBox
	{
		color: red;
		margin-bottom: 8px;
		display:none;
	}
	.messageBox strong
	{
		font-weight:800;
	}
	.hide_load
	{
		display:none;
	}
	.show_load
	{
		display:block;
	}
	#loading_search
	{
		padding: 90px 40px;
    position: absolute;
    text-align: center;
    margin: 0 auto;
    left: 50%;
	}
	#search_table
	{
		display:block;
	}
	#search_table table tbody tr {
    display: table-row;
}
</style>
<script>
		jQuery(document).ready(function () 
		{
			var ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>";
			jQuery(".search-btn").click(function(e){
				jQuery("#search_table tbody").empty();
				jQuery("#search_table #loading_search").addClass("show_load");
				e.preventDefault();
				var search_fund_id=jQuery("#search_id").val();
				if(search_fund_id!="")
				{
					jQuery(".messageBox").css("display","none");
					jQuery.ajax({

							type : "post",

							url : ajaxurl,

							data : {action: 'wdm_search_fundraisers', search_fund_id:search_fund_id},
			
							success: function(response) {
								console.log(response);
								jQuery("#search_table #loading_search").removeClass("show_load");
								jQuery("#search_table #loading_search").addClass("hide_load");
								if(response!=0)
								{
									jQuery("#search_table tbody").html(response);
									/*jQuery("#search_table").css("display","block");*/
								}
								else
								{
									jQuery("#search_table tbody").html("<p style='margin: 0 auto;padding: 50px;position: absolute;left: 44%;'>No Results!!</p>");
								}
							}
						});
					
				}
				else{
					jQuery(".messageBox").css("display","block");
					jQuery("#search_table #loading_search").removeClass("show_load");
					jQuery("#search_table #loading_search").addClass("hide_load");
				
				}
			});
		});
</script>
<div class="widget widget-table">
	<div class="btn-toolbar fundraiser_summery">
		<a href="?page=fundraiser_operations" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Operations</a>
		<a href="?page=fundraiser_operations&fundraiser_report=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Report</a>
		<a href="?page=fundraiser_operations&paid=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Paid Fundraiser Summary</a>
		<a href="?page=fundraiser_operations&fundraiser_summery=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Summary</a>
		<a href="?page=fundraiser_operations&print_order=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print Order</a>
		<a href="?page=fundraiser_operations&search=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Search</a>
		<a href="?page=fundraiser_operations&gpd=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Online Profits Deduction</a><br/><br/>
		<a href="?page=fundraiser_operations&fund_owed=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Owed Fundraiser</a>
	</div>
	<div class="widget-header">						
		<h3>Search Fundraiser</h3>
	</div>	
	
	
	<div id="wrap" class="widget-content " style="clear: both;">
	<div class="messageBox"><strong>Error!!</strong> Please Enter Fundraiser ID.</div>
		<form action="#" name="fund_search" id="fund_search" method="get">
			<div class="row">
				<div class="span6">
					<div class="search-item">Enter Fundraiser ID:</div> <input type="text" name="search_id" size="30" value="" id="search_id"><input type="submit" value="Submit" class="btn btn-primary search-btn">
				</div>
				
			</div>
			
		</form>
	</div>
	<div id="" class="manual-mass-order-popup">
        <div class="manual-mass-order-popup-wrapper">
		<span>Status Action</span>
           <input type="radio" id="st_1" class="status_type" name="paid_type" value="1" >Set paid<input type="radio" id="st_2" class="status_type" name="paid_type" value="2">Unpaid
			   <input id="submit_status" type="submit" name="submit" value="Submit" data-id="" data-action="" data-rebate="">
			 <a href="#" id="m-txt-link" class="txt-link">Cancel</a>
        </div>
    </div>
	<div id="" class="manual-mass-order-popup_action">
        <div class="manual-mass-order-popup-wrapper_action">
		<span>Active/Inactive Action</span>
           <input type="radio" id="a_st_1" class="a_status_type" name="action_type" value="1" >Active<input type="radio" id="a_st_2" class="a_status_type" name="action_type" value="2">Inactive
			   <input id="submit_action_status" type="submit" name="submit" value="Submit" data-id="" data-action="">
			 <a href="#" id="m-txt-link" class="txt-link">Cancel</a>
        </div>
    </div>
    <div id="" class="manual-mass-order-popup_repre_pay">
        <div class="manual-mass-order-popup-wrapper_repre_pay">
		<h3>Representative Payment</h3>
			<div class="repre_pay_content"></div>
			<div class="repre_pay_selection">
				<input type="radio" id="st_1" class="status_type" name="repre_paid_type" value="1" >Paid
				<input type="radio" id="st_2" class="status_type" name="repre_paid_type" value="2">Unpaid
				<input id="submit_repre_status" type="submit" name="submit" value="Submit" data-id="" data-action="" data-rebate="">
			</div>
			 <a href="#" id="m-txt-link" class="txt-link">Cancel</a>
        </div>
    </div>
<div id="search_table" class="widget widget-table">	
    <div class="admin-report"></div>
	<div id="" class="widget-content " style="clear: both;">
		<table class="table table-bordered table-striped table-highlight responsive">
		<thead>
		  <tr>
			<th id="" style="vertical-align: top; text-align: center;">Fund ID:</th>
			<th id="" style="vertical-align: top; text-align: left;">Organization:</th>
			<th id="" style="vertical-align: top; text-align: left;">Director:</th>
			<th id="" style="vertical-align: top; text-align: center;">Members:</th>
			<th id="" style="vertical-align: top; text-align: center;">Registered:</th>
			<th id="" style="vertical-align: top; text-align: center;">Period:</th>
			<th id="" style="vertical-align: top; text-align: center;">Status:</th>
			<!--<th id="" style="vertical-align: top; text-align: center;">Repre Status:</th>-->
			<th id="" style="vertical-align: top; text-align: center;">Fund Status:</th>
			<th id="" style="vertical-align: top; text-align: center;">Action:</th>
		 </tr>
		</thead>
		<tbody>
		
			<img id="loading_search" src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/08/ajax-loader.gif" class="hide_load">
		
		</tbody>
		</table>
	</div>
	</div>
</div>
	
<?php } else {
	
	
	?>
	<style>
	.fundraiser_summery
	{
		margin: 21px;
    margin-left: 0;
	}
	.fundraiser_summery .btn-custom
	{
		    padding: 10px;
	}
	/*Search Option */
	#fund_search .search-btn
	{
		background-color: #405e6a !important;
		text-shadow: 0px -1px 0px #22404c;
		background-image: none;
		-webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
		-moz-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
		box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
		color: #fff;
		padding: 5px 33px;
		border-radius: 3px;
		border: 0;
		display: inline-block;
		height: 30px;
	}
	
	.messageBox
	{
		color: red;
		margin-bottom: 8px;
		display:none;
		width: 750px;
		margin: 0 auto;
		text-align: left;
		margin-bottom: 3px;
		font-size: 14px;
	}
	.messageBox strong
	{
		font-weight:800;
	}
	.widget-table .widget-content.search-section
	{
		background: transparent;
		padding: 20px;
	}
	#fund_search
	{
		width: 100%;
		margin: 0 auto;
		text-align: center;
		display: inline-block;
	}
	#fund_search .search-item
	{
		width: 750px;
		margin: 0 auto;
		text-align: left;
		margin-bottom: 9px;
		font-size: 14px;
	}
	.payOwe
	{
	    padding: 5px 15px;
		margin-left: 5px;
	}
	#out_F
	{
		display:none;
		    padding: 10px;
    text-align: center;
    font-size: 20px;
	}

/* representative pay popup css 05-05-2023 */
.manual-mass-order-popup_repre_pay .manual-mass-order-popup-wrapper_repre_pay {
    max-width: 730px !important;
    border: none !important;
    border-radius: 10px !important;
}
.manual-mass-order-popup_repre_pay .manual-mass-order-popup-wrapper_repre_pay h3{
	font-size: 40px;
    line-height: 48px;
    color: #212121;
}
.manual-mass-order-popup_repre_pay .manual-mass-order-popup-wrapper_repre_pay table{
	margin: 0 auto;
	margin-top: 30px;
	margin-bottom: 30px;
}
.manual-mass-order-popup_repre_pay .manual-mass-order-popup-wrapper_repre_pay table tr td{
	padding: 10px 20px;
	color: #000;
	font-size: 16px !important;
	width: 50%;
}
.manual-mass-order-popup_repre_pay .manual-mass-order-popup-wrapper_repre_pay table tr td:first-child{
	font-weight: 700;
}
.manual-mass-order-popup_repre_pay .manual-mass-order-popup-wrapper_repre_pay table tr:nth-child(even) {
    background-color: #F4F4F4;
}
.manual-mass-order-popup_repre_pay .manual-mass-order-popup-wrapper_repre_pay .txt-link{
	margin-top: 10px;
	color: #000;
    font-size: 16px;
    font-weight: 600;
}
#submit_repre_status{
	width: 150px;
    margin: 0 auto;
    padding: 12px 0;
    background-color: #9EB7B3;
    color: #000;
    font-size: 16px;
    font-weight: 600;
    border: none;
    text-decoration: none;
    border-radius: 8px;
    margin-left: 20px;
}
.manual-mass-order-popup_repre_pay .manual-mass-order-popup-wrapper_repre_pay .repre_pay_selection{
    color: #000;
    font-size: 16px;
    font-weight: 600;	
}


/* end representative pay popup css 05-05-2023 */
		.pagaination_section
	{
		position: relative;
		width: 100% ;
		margin: 0 auto;
		text-align: center;
		padding: 40px 0px;
		display: inline-block;
	}
	.pagaination_section a.page-numbers {
    color: black;
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
    border: 1px solid #ddd;
    display: inline-block;
    margin: 0;
    font-size: 14px;
} 

	.pagaination_section span.page-numbers.current {
    background-color: #006799;
    color: white;
    border: 1px solid #006799;
    display: inline-block;
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
}

	</style>
	<script>
		jQuery(document).ready(function () 
		{
			// jQuery(".search-btn").click(function(e){
				
				
			// 	e.preventDefault();
			// 	var search_id=jQuery("#search_id").val();
			// 	if(search_id!="")
			// 	{
			// 		jQuery(".messageBox").css("display","none");
			// 		jQuery("#search_table table tbody tr").css("display","none");
			// 		jQuery("#search_table table tbody tr#fund_"+search_id).css("display","table-row");
					
			// 	}
			// 	else{
			// 		jQuery(".messageBox").css("display","block");
			// 		jQuery("#search_table table tbody tr").css("display","table-row");
			// 	}
			// });
			
			jQuery(".payOwe").click(function(e){
				
				
				e.preventDefault();
				
					
					jQuery("#search_table table tbody tr").css("display","none");
					jQuery("#search_table table tbody tr.payDue").css("display","table-row");
					jQuery("#out_F").css("display","block");
					
				
			});
			var sale = [];
			var rebate = [];
			jQuery("#search_table .payDue").each(function(){
				  var v_rebate=jQuery(this).attr("data-rebate");
				  rebate.push(v_rebate);
				  var v_sale=jQuery(this).attr('data-sale');
				  sale.push(v_sale);
			});
			var sumRebate = 0.00;
			var sumSale=0.00;
			jQuery.each(rebate,function(){sumRebate+=parseFloat(this) || 0;});
			jQuery.each(sale,function(){sumSale+=parseFloat(this) || 0;});
			console.log(sumRebate);
			console.log(sumSale);
			
			
			jQuery("#out_F").html("Total Sale: $"+sumSale.toFixed(2)+" and Total Rebate: $"+sumRebate.toFixed(2));
			
			
			
		});
</script>
<div class="proccessloader">
	<div class="inner">
		<img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/12/fd420f3a680e39cde8bda39f5f996964.gif"/>
		<p>Successfully Updated!!</p>
		</div>
	</div>
	
	 <div class="proccessloader2">
	<div class="inner">
		<img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/12/fd420f3a680e39cde8bda39f5f996964.gif"/>
		<p>Email Successfully Sent.</p>
		</div>
	</div>
	<div id="" class="manual-mass-order-popup">
        <div class="manual-mass-order-popup-wrapper">
		<p></p>
		<span>Status Action</span>
           <input type="radio" id="st_1" class="status_type" name="paid_type" value="1" >Set paid<input type="radio" id="st_2" class="status_type" name="paid_type" value="2">Unpaid
			   <input id="submit_status" type="submit" name="submit" value="Submit" data-id="" data-action="" data-rebate="">
			 <a href="#" id="m-txt-link" class="txt-link">Cancel</a>
        </div>
    </div>
	<div id="" class="manual-mass-order-popup_action">
        <div class="manual-mass-order-popup-wrapper_action">
		<p></p>
		<span>Active/Inactive Action</span>
           <input type="radio" id="a_st_1" class="a_status_type" name="action_type" value="1" >Active<input type="radio" id="a_st_2" class="a_status_type" name="action_type" value="2">Inactive
			   <input id="submit_action_status" type="submit" name="submit" value="Submit" data-id="" data-action="">
			 <a href="#" id="m-txt-link" class="txt-link">Cancel</a>
        </div>
    </div>
    <div id="" class="manual-mass-order-popup_repre_pay">		
         <div class="manual-mass-order-popup-wrapper_repre_pay">
		<p></p>
		<h3>Representative Payment</h3>
        	
<div class="print_btn">
  <input type="button" value="print" id="printDiv" />
</div> 
			<div class="repre_pay_content" id="divToPrint"></div>
			<div class="repre_pay_selection">
				<input type="radio" id="st_1" class="status_type" name="repre_paid_type" value="1" >Paid
				<input type="radio" id="st_2" class="status_type" name="repre_paid_type" value="2">Unpaid
				<input id="submit_repre_status" type="submit" name="submit" value="Submit" data-id="" data-action="" data-rebate="">
			</div>
			 <a href="#" id="m-txt-link" class="txt-link">Cancel</a>
             
        </div>
      
    </div>
<div class="widget widget-table">	
    <div class="admin-report"></div>
	<div class="btn-toolbar fundraiser_summery">
		<a href="?page=fundraiser_operations" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Operations</a>
		<a href="?page=fundraiser_operations&fundraiser_report=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Report</a>
		<a href="?page=fundraiser_operations&paid=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Paid Fundraiser Summary</a>
		<a href="?page=fundraiser_operations&fundraiser_summery=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Summary</a>
		<a href="?page=fundraiser_operations&print_order=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print Order</a>
		<a href="?page=fundraiser_operations&request=close" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Close Out</a>
		<a href="?page=fundraiser_operations&closed=closed_fundraisers" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Closed Fundraisers</a>
		<a href="?page=fundraiser_operations&search=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Search</a>
		<a href="?page=fundraiser_operations&gpd=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Online Profits Deduction</a><br/><br/>
		<a href="?page=fundraiser_operations&fund_owed=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Owed Fundraiser</a>
	</div>	
	<div id="wrap" class="widget-content search-section" style="clear: both;">
	<div class="messageBox"><strong>Error!!</strong> Please Enter Fundraiser ID.</div>
		<!-- <form action="#" name="fund_search" id="fund_search" method="get">
			<div class="row">
				<div class="span6">
					<div class="search-item">Enter Fundraiser ID:</div> <input type="text" name="search_id" size="100" value="" id="search_id"><input type="submit" value="Submit" class="btn btn-primary search-btn">
				</div>
				
			</div>
		</form> -->
		<form action="admin.php" name="fund_search" id="fund_search" method="get">
    <input type="hidden" name="page" value="fundraiser_operations">
    <div class="row">
        <div class="span6">
            <div class="search-item">Enter Fundraiser ID:</div>
            <input type="text" name="search_id" size="100"
                   value="<?php echo isset($_GET['search_id']) ? esc_attr($_GET['search_id']) : ''; ?>"
                   id="search_id">
            <input type="submit" value="Submit" class="btn btn-primary search-btn">
        </div>
    </div>
</form>
		
	</div>
	<div class="widget-header">						
		<h3>Fundraiser Operations</h3>
	</div>
	
	<div id="search_table" class="widget-content" style="clear: both;">
		<table class="table table-bordered table-striped table-highlight responsive">
		<thead>
		  <tr>
			<th id="" style="vertical-align: top; text-align: center;">Fund ID:</th>
			<th id="" style="vertical-align: top; text-align: left;">Organization:</th>
			<th id="" style="vertical-align: top; text-align: left;">Director:</th>
			<th id="" style="vertical-align: top; text-align: center;">Members:</th>
			<th id="" style="vertical-align: top; text-align: center;">Registered:</th>
			<th id="" style="vertical-align: top; text-align: center;">Period:</th>
			<th id="" style="vertical-align: top; text-align: center;">Status:</th>
		
			<th id="" style="vertical-align: top; text-align: center;">Fund Status:</th>
			<th id="" style="vertical-align: top; text-align: center;">Action:</th>
		 </tr>
		</thead>
		<tbody>
		<?php 
		$search_id = isset($_GET['search_id']) ? absint($_GET['search_id']) : 0;

				if ( $search_id ) {
			// Search by Fundraiser ID
			$args = array(
				'role' => 'Fundraiser',
				'include' => array( $search_id ) // Only this user
			);
			$all_meta_for_users = get_users($args);
			$total_pages = 1; // no pagination needed for search
		} else {
			// Normal paginated query
			$paged     = ( isset($_GET['paged']) ) ? absint($_GET['paged']) : 1;
			$per_page  = 50;
			$offset    = ( $paged - 1 ) * $per_page;

			$args = array(
				'role'    => 'Fundraiser',
				'orderby' => 'user_registered',
				'order'   => 'DESC',
				'number'  => $per_page,
				'offset'  => $offset
			);
			$total_users = count_users();
			$total_fundraisers = isset($total_users['avail_roles']['fundraiser']) ? $total_users['avail_roles']['fundraiser'] : 0;
			$total_pages = ceil( $total_fundraisers / $per_page );
			// A search term (?fs=) narrows the list and its page count.
			$all_meta_for_users = glow_fo_user_query( $args, $per_page, $total_pages )->get_results();
		}

				foreach ( $all_meta_for_users as $all_meta_for_user ) {
						$count=0;
						$ID=$all_meta_for_user->ID;	
						
						$status= get_user_meta( $ID , 'user_status' , true );
						//$repre_status= get_user_meta( $ID , 'user_representative_status' , true );
					    $action_status= get_user_meta( $ID , 'user_action_status' , true );
						$close_out = get_user_meta($ID, "user_close_out_value", true);
						if((empty($status) && empty($action_status)) || ($status!=1 && $action_status!=2) || ($status!=2 && $action_status!=2) || ($status!=1 && $action_status==2 && $close_out==1))
						{
						
								$arr=array();
								$total_sale_so_far=0;
								$rebate_so_far=0;
								

						
						   
							$all_seller = get_users(array(
					'meta_key'     => 'user_registration_seller_fundraiser',
					'meta_value'   => $ID,
				));
				if ( ! empty( $all_seller ) && is_iterable( $all_seller ) ) {
					foreach ( $all_seller as $seller_item ) {
						$arr[] = $seller_item->ID;
					}
				}

							$org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );

							$member=count($arr);
							$sd=get_user_meta( $ID , 'user_registration_start_date' , true );
							$ed=get_user_meta( $ID , 'user_registration_end_date' , true );
							
							$currentDate=date("Y-m-d");
							$currentDate = date('Y-m-d', strtotime($currentDate));
							$startDate = date('Y-m-d', strtotime($sd));
							$endDate = date('Y-m-d', strtotime($ed));
							
							$active_date=get_user_meta($ID, "user_close_out_date_active", true);
							$inactive_date=get_user_meta($ID, "user_close_out_date_inactive", true);
							$rebate= get_user_meta( $ID , 'user_rebate' , true );
							$udata = get_userdata( $ID );

							$registered = $udata->user_registered;
					
					?>
			<tr id="<?php echo "fund_".$ID; ?>" class="<?php echo $startDate; ?> <?php if($rebate_so_far>0){ echo 'payDue'; } ?>" <?php if($rebate_so_far>0){ echo 'data-rebate="'.$rebate_so_far.'"';  echo 'data-sale="'.glow_fc_number_format($total_sale_so_far, 2, '.', '').'"'; } ?>>
				<td id="0_" style="vertical-align: top; text-align: center; "><?php echo $ID;?></td>
				<td id="1_" style="vertical-align: top; text-align: left;width: 180px;"><?php echo $org; ?></td>
				<td id="2_" style="vertical-align: top; text-align: left;"><?php echo $all_meta_for_user->user_firstname; ?> <?php echo $all_meta_for_user->user_lastname; ?></td>
				<td id="4_" style="vertical-align: top; text-align: center; "><?php echo $member; ?></td>
				<td id="9_" style="vertical-align: top; text-align: center; "><?php echo date( "M d, Y", strtotime( $registered )); ?></td>
				<td id="5_" style="vertical-align: top; text-align: center; "><span class="label <?php if (($currentDate >= $startDate) && ($currentDate <= $endDate)){ ?>label-success<?php } else { echo "label-danger"; } ?> "><?php echo $sd; ?> - <?php echo $ed; ?></span></td>
				<td id="6_" class="<?php echo $rebate; ?>" style="vertical-align: top; text-align: center; "><?php if($status==1){?><span id="action_status_<?php echo $ID;?>" class="label label-success">Paid</span><?php }  elseif($status==2){?><span id="action_status_<?php echo $ID;?>" class="label label-danger">Unpaid</span><?php }  else { ?><span id="action_status_<?php echo $ID;?>" class="label label-danger">Unpaid</span><?php } ?></td>
				
                <td id="8_" class="" style="vertical-align: top; text-align: center; "><?php if($action_status==1){?><span id="action_s" class="label label-success">Active</span><?php }  elseif($action_status==2){?><span id="action_s2" class="label label-danger">Inactive</span><?php }  else { ?><span id="action_st" class="label label-success">Active</span><?php } ?></td>
				<td id="7_" style="vertical-align: top; text-align: center; width: 240px;">
					<div class="btn-toolbar"><div class="first-col" style="margin-bottom:10px;">
					<a href="<?php echo home_url('/print/'); ?>?fund_id=<?php echo $ID;?>" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print</a>&nbsp;
					<a href="#" class="btn btn-inverse btn-mini padtop btn-custom status" data-id="<?php echo $ID;?>" data-action="<?php echo $status;?>" data-rebate="<?php echo $rebate_so_far=glow_fc_number_format($rebate_so_far, 2, '.', ''); ?>">Status</a>&nbsp;
					<a id="send_report" class="btn btn-inverse btn-mini padtop btn-custom" data-fund_id="<?php echo $ID; ?>">Email Report</a></div><div class="second-col">&nbsp;
					<a href="?page=fundraiser_operations&rebate=true&fund_id=<?php echo $ID;?>" id="rebate_pay" class="btn btn-inverse btn-mini padtop btn-custom">Rebate</a>&nbsp;
					<a href="#" class="btn btn-inverse btn-mini padtop btn-custom action_status" data-id="<?php echo $ID;?>" data-action="<?php echo $action_status;?>" data-active="<?php echo $active_date;?>" data-inactive="<?php echo $inactive_date;?>">Action</a>
					</div></div>
				</td>
			</tr>
		
			<?php   }   ?>
<?php   }  

// if ( $total_pages > 1 ) {
//     echo '<div class="pagination">';
//     echo paginate_links( array(
//         'base'      => add_query_arg( array( 'paged' => '%#%', 'search_id' => $search_id ) ),
//         'format'    => '',
//         'current'   => max( 1, $paged ),
//         'total'     => $total_pages,
//         'prev_text' => __('« Prev'),
//         'next_text' => __('Next »'),
//     ) );
//     echo '</div>';
// }

?>
			
		</tbody>
		</table>
		<div class="pagaination_section">
		<?php
		
			$base_args = array( 'page' => 'fundraiser_operations' );
			if ( $search_id ) {
				$base_args['search_id'] = $search_id;
			}
			if ( '' !== glow_fo_search_term() ) {
				$base_args['fs'] = rawurlencode( glow_fo_search_term() );
			}
			if ( $total_pages > 1 ) {
			echo paginate_links( array(
				'base'      => add_query_arg( $base_args, admin_url('admin.php') ) . '&paged=%#%',
				'format'    => '',
				'current'   => max( 1, $paged ),
				'total'     => $total_pages,
				'prev_text' => __('« Prev'),
				'next_text' => __('Next »'),
			) );
			}
		?>
		</div>
		<div id="out_F"></div>
	</div>
</div>

<?php
}
}