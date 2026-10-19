<?php
/**
 * GLOW Dashboard Fixes – chairperson "Additional tools" pages + client feedback round 2.
 *
 *  A. Sign-up photo/logo: the registration forms save the upload as an attachment ID in
 *     user_registration_profile_pic_url, but dashboards/stores read *_image_url_profile.
 *     Serve the sign-up image when nothing else is set (fundraisers and sellers).
 *  B. Wording: "Catalog / Catalogue" -> "Brochure" everywhere on the front end.
 *  C. Dashboard tiles that only scrolled the page now open real pages:
 *     /fundraiser-sellers/  /fundraiser-invoices/  /fundraiser-claims/  /fundraiser-orders/
 *     "Invite your sellers" opens the seller registration page.
 *  D. Chairpersons may download invoices for their own fundraiser's orders.
 *  E. Store "Room Sprays" tile pointed at an empty category.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ------------------------------------------------------------------ */
/* A. Sign-up image fallback                                            */
/* ------------------------------------------------------------------ */
add_filter( 'get_user_metadata', function ( $value, $object_id, $meta_key, $single ) {
	static $keys = array( 'user_registration_image_url_profile', 'image_url_profile', 'fund_profile_image', 'seller_profile_image' );
	if ( ! in_array( $meta_key, $keys, true ) ) {
		return $value;
	}
	static $busy = false;
	if ( $busy ) {
		return $value;
	}
	$busy = true;
	$raw  = get_user_meta( $object_id, $meta_key, true );
	$busy = false;
	if ( '' !== trim( (string) $raw ) ) {
		return $value;
	}
	$pic = get_user_meta( $object_id, 'user_registration_profile_pic_url', true );
	if ( is_array( $pic ) ) {
		$pic = reset( $pic );
	}
	$pic = trim( (string) $pic );
	if ( '' === $pic ) {
		return $value;
	}
	$url = is_numeric( $pic ) ? wp_get_attachment_url( (int) $pic ) : $pic;
	return $url ? array( $url ) : $value;
}, 10, 4 );

/* A2. Sign-up "reason for fundraising" doubles as the store message until one is set. */
add_filter( 'get_user_metadata', function ( $value, $object_id, $meta_key, $single ) {
	if ( 'fund_profile_description' !== $meta_key ) {
		return $value;
	}
	static $busy = false;
	if ( $busy ) {
		return $value;
	}
	$busy = true;
	$raw  = get_user_meta( $object_id, $meta_key, true );
	$busy = false;
	if ( '' !== trim( (string) $raw ) ) {
		return $value;
	}
	$alt = trim( (string) get_user_meta( $object_id, 'user_registration_reason_for_fundraising', true ) );
	return '' !== $alt ? array( $alt ) : $value;
}, 10, 4 );

/* ------------------------------------------------------------------ */
/* F. Marketing Toolbox PDF hosted on this site (copied from the old   */
/*    domain once via /?glow_fetch_toolbox=1 as admin).                 */
/* ------------------------------------------------------------------ */
function glow_ct_toolbox_filter( $c ) {
	$u = get_option( 'glow_toolbox_pdf_url' );
	// A migrated option can still contain the development site's uploads URL.
	if ( $u && 'glow.local' === wp_parse_url( $u, PHP_URL_HOST ) ) {
		$path   = wp_parse_url( $u, PHP_URL_PATH );
		$marker = '/wp-content/uploads/';
		$offset = is_string( $path ) ? strpos( $path, $marker ) : false;
		if ( false !== $offset ) {
			$uploads = wp_get_upload_dir();
			$u = trailingslashit( $uploads['baseurl'] ) . substr( $path, $offset + strlen( $marker ) );
		}
	}
	if ( $u && isset( $c['urls'] ) && is_array( $c['urls'] ) ) {
		foreach ( $c['urls'] as $k => $v ) {
			if ( false !== strpos( (string) $v, 'Fundraiser-Dashboard-Glow-Scented-Candles' ) ) {
				$c['urls'][ $k ] = $u;
			}
		}
	}
	return $c;
}
add_filter( 'glow_fd_config', 'glow_ct_toolbox_filter', 20 );
add_filter( 'glow_sd_config', 'glow_ct_toolbox_filter', 20 );

add_action( 'template_redirect', function () {
	if ( empty( $_GET['glow_fetch_toolbox'] ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$src = 'https://glowscentedcandles.com/wp-content/uploads/2020/10/Fundraiser-Dashboard-Glow-Scented-Candles-4-copy.pdf';
	$tmp = download_url( $src, 120 );
	if ( is_wp_error( $tmp ) ) {
		wp_die( 'Download failed: ' . esc_html( $tmp->get_error_message() ) );
	}
	$id = media_handle_sideload( array( 'name' => 'Fundraiser-Marketing-Toolbox.pdf', 'tmp_name' => $tmp ), 0, 'Fundraiser Marketing Toolbox' );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp );
		wp_die( 'Sideload failed: ' . esc_html( $id->get_error_message() ) );
	}
	update_option( 'glow_toolbox_pdf_url', wp_get_attachment_url( $id ) );
	wp_die( 'OK ' . esc_html( wp_get_attachment_url( $id ) ) );
}, 0 );

/* ------------------------------------------------------------------ */
/* B. "Catalog" -> "Brochure" wording (front end only)                  */
/* ------------------------------------------------------------------ */
add_action( 'wp_footer', function () {
	?>
	<script id="glow-brochure-wording">
	(function(){
		var re = /\b(CATALOGUES?|Catalogues?|catalogues?|CATALOGS?|Catalogs?|catalogs?)\b/g;
		var has = /\b(catalogues?|catalogs?)\b/i;
		function fix(s){ return s.replace(re, function(m){
			var isPlural = /(catalogues|catalogs)$/i.test(m);
			var out = isPlural ? 'Brochures' : 'Brochure';
			if (m === m.toUpperCase()) return out.toUpperCase();
			if (m === m.toLowerCase()) return out.toLowerCase();
			return out;
		}); }
		function walk(root){
			var w = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, { acceptNode: function(n){
				var p = n.parentNode; if (!p) return NodeFilter.FILTER_REJECT;
				var tag = p.nodeName; if (tag === 'SCRIPT' || tag === 'STYLE' || tag === 'TEXTAREA' || p.isContentEditable) return NodeFilter.FILTER_REJECT;
				return has.test(n.nodeValue) ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_SKIP; } });
			var list = []; while (w.nextNode()) list.push(w.currentNode);
			list.forEach(function(n){ n.nodeValue = fix(n.nodeValue); });
		}
		walk(document.body);
		if (has.test(document.title)) document.title = fix(document.title);
		document.querySelectorAll('input[type=submit],input[type=button],[placeholder],[title]').forEach(function(el){
			['value','placeholder','title'].forEach(function(a){ if (el.hasAttribute(a) && has.test(el.getAttribute(a))) el.setAttribute(a, fix(el.getAttribute(a))); });
		});
		if (window.MutationObserver) {
			new MutationObserver(function(muts){ muts.forEach(function(m){ m.addedNodes.forEach(function(n){ if (n.nodeType === 1) walk(n); else if (n.nodeType === 3 && has.test(n.nodeValue)) n.nodeValue = fix(n.nodeValue); }); }); }).observe(document.body, { childList: true, subtree: true });
		}
	})();
	</script>
	<?php
}, 99 );

/* ------------------------------------------------------------------ */
/* Helpers                                                              */
/* ------------------------------------------------------------------ */
function glow_ct_fid() {
	$fid = isset( $_GET['frid'] ) ? absint( $_GET['frid'] ) : 0;
	if ( ! $fid || ! current_user_can( 'manage_options' ) ) {
		$fid = get_current_user_id();
	}
	return $fid;
}

function glow_ct_url( $path, $fid ) {
	$url = home_url( $path );
	if ( current_user_can( 'manage_options' ) && $fid && $fid !== get_current_user_id() ) {
		$url = add_query_arg( 'frid', $fid, $url );
	}
	return $url;
}

/** Every order credited to a fundraiser, newest first (cached per request). */
function glow_ct_orders( $fid ) {
	static $cache = array();
	if ( isset( $cache[ $fid ] ) ) {
		return $cache[ $fid ];
	}
	$orders = wc_get_orders( array(
		'limit'      => 300,
		'orderby'    => 'date',
		'order'      => 'DESC',
		'status'     => array( 'completed', 'processing', 'on-hold', 'pending' ),
		'meta_key'   => 'fundraiser_field',
		'meta_value' => (string) $fid,
	) );
	$rows = array();
	foreach ( $orders as $o ) {
		$sid    = (string) $o->get_meta( 'wc_seller_field' );
		$seller = '';
		if ( is_numeric( $sid ) && (int) $sid > 0 ) {
			$su     = get_userdata( (int) $sid );
			$seller = $su ? trim( get_user_meta( $su->ID, 'first_name', true ) . ' ' . get_user_meta( $su->ID, 'last_name', true ) ) : '';
			$seller = $seller ? $seller : ( $su ? $su->display_name : '' );
		} elseif ( $sid ) {
			$seller = $sid;
		}
		$type     = (string) $o->get_meta( 'wc_order_type' );
		$tracking = '';
		foreach ( wc_get_order_notes( array( 'order_id' => $o->get_id(), 'limit' => 20 ) ) as $note ) {
			if ( preg_match( '/tracking number[^0-9A-Z]*([0-9A-Z]{8,})/i', $note->content, $m ) ) {
				$tracking = $m[1];
				break;
			}
		}
		$rows[] = array(
			'id'       => $o->get_id(),
			'date'     => $o->get_date_created() ? $o->get_date_created()->date_i18n( 'M j, Y' ) : '',
			'status'   => wc_get_order_status_name( $o->get_status() ),
			'raw'      => $o->get_status(),
			'customer' => trim( $o->get_billing_first_name() . ' ' . $o->get_billing_last_name() ),
			'seller'   => $seller,
			'sid'      => $sid,
			'total'    => $o->get_total(),
			'type'     => 'Catlog' === $type ? 'Brochure' : ( $type ? $type : 'Online' ),
			'tracking' => $tracking,
			'claimed'  => (bool) $o->get_meta( 'claim_requested' ),
			'key'      => $o->get_order_key(),
		);
	}
	return $cache[ $fid ] = $rows;
}

function glow_ct_invoice_url( $row ) {
	return add_query_arg( array(
		'action'        => 'generate_wpo_wcpdf',
		'document_type' => 'invoice',
		'order_ids'     => $row['id'],
		'access_key'    => $row['key'],
	), admin_url( 'admin-ajax.php' ) );
}

if ( ! function_exists( 'glow_vp_title' ) ) {
function glow_vp_title( $title ) {
	add_filter( 'pre_get_document_title', function () use ( $title ) { return $title . ' – ' . get_bloginfo( 'name' ); }, 99 );
	if ( isset( $GLOBALS['wp_query'] ) ) { $GLOBALS['wp_query']->is_404 = false; }
}
}
function glow_ct_open( $fid, $title, $subtitle, $icon = '' ) {
	glow_vp_title( $title );
	get_header();
	$u = get_userdata( $fid );
	?>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
	<style>
	.glow-ct{--pink:#ec0f63;--ink:#181735;--muted:#6f6c85;--line:#e8e8f0;font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:var(--ink);background:#f2f3f9;padding:28px 18px 48px;-webkit-font-smoothing:antialiased}
	.glow-ct *{box-sizing:border-box}.glow-ct-wrap{max-width:1040px;margin:0 auto}
	.glow-ct-back{display:inline-flex;gap:6px;font-size:14px;font-weight:600;color:#4a3fd0 !important;text-decoration:none !important;margin-bottom:14px}
	.glow-ct h1{margin:0 0 4px;font-size:26px;font-weight:800}.glow-ct-sub{margin:0 0 20px;color:var(--muted);font-size:15px}
	.glow-ct-card{background:#fff;border:1px solid var(--line);border-radius:20px;box-shadow:0 2px 6px rgba(22,20,50,.05);padding:24px 26px;margin-bottom:18px}
	.glow-ct-card h3{margin:0 0 14px;font-size:16.5px;font-weight:800;letter-spacing:.02em;text-transform:uppercase}
	.glow-ct-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:18px}
	.glow-ct-stat{border-radius:16px;padding:16px 18px;background:#fff;border:1px solid var(--line)}.glow-ct-stat b{display:block;font-size:24px;font-weight:800}.glow-ct-stat span{font-size:13px;color:var(--muted)}
	.glow-ct-stat.is-pink{background:#fff0f4}.glow-ct-stat.is-blue{background:#eef4ff}.glow-ct-stat.is-green{background:#ecf9f3}.glow-ct-stat.is-purple{background:#f1edff}
	.glow-ct-tw{overflow-x:auto}.glow-ct table{width:100%;border-collapse:collapse;font-size:14.5px;border:0}
	.glow-ct th{padding:10px 12px;text-align:left;font-size:12px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;color:#8d8aa0;border:0;border-bottom:1px solid var(--line);white-space:nowrap;background:transparent}
	.glow-ct td{padding:12px;border:0;border-bottom:1px solid #f0eff4;vertical-align:middle;background:transparent}.glow-ct tr:hover td{background:#faf9fc}
	.glow-ct-tag{display:inline-block;padding:4px 11px;border-radius:999px;font-size:12.5px;font-weight:700;background:#f1eff6;color:#5d5a75;white-space:nowrap}
	.glow-ct-tag.is-completed{background:#eaf8f1;color:#0d6b50}.glow-ct-tag.is-processing{background:#f0edfd;color:#4030a8}.glow-ct-tag.is-on-hold{background:#fff4e5;color:#9a5b00}
	.glow-ct-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:999px;background:var(--pink);color:#fff !important;font-weight:700;font-size:12.5px;letter-spacing:.03em;text-transform:uppercase;text-decoration:none !important;white-space:nowrap}
	.glow-ct-btn.is-ghost{background:#fff;color:var(--ink) !important;border:1.5px solid #d9d7e6}.glow-ct-btn.is-dark{background:var(--ink)}
	.glow-ct-empty{padding:28px;text-align:center;color:#8d8aa0}
	.glow-ct-link{display:flex;gap:10px;align-items:center;flex-wrap:wrap}.glow-ct-link input{flex:1;min-width:240px;font:inherit;font-size:14.5px;padding:11px 14px;border:1px solid #d9d7e6;border-radius:10px;background:#f7f7fb}
	.glow-ct-notice{padding:12px 16px;border-radius:12px;font-size:14.5px;margin-bottom:18px;background:#eef4ff;color:#2b3a8f;border:1px solid #cfdcff}
	.glow-ct-muted{color:var(--muted);font-size:13px}
	@media(max-width:720px){.glow-ct-stats{grid-template-columns:1fr 1fr}.glow-ct-card{padding:20px 18px}}
	</style>
	<div class="glow-ct"><div class="glow-ct-wrap">
	<a class="glow-ct-back" href="<?php echo esc_url( glow_ct_url( '/fundraiser-dashboard/', $fid ) ); ?>">&larr; Back to my dashboard</a>
	<h1><?php echo esc_html( $title ); ?></h1>
	<p class="glow-ct-sub"><?php echo wp_kses_post( $subtitle ); ?></p>
	<?php if ( current_user_can( 'manage_options' ) && $fid !== get_current_user_id() ) : ?>
		<div class="glow-ct-notice">Admin view &mdash; fundraiser <b><?php echo esc_html( $u ? $u->display_name : '' ); ?></b> (#<?php echo (int) $fid; ?>).</div>
	<?php endif;
}

function glow_ct_close() {
	echo '</div></div>';
	get_footer();
	exit;
}

function glow_ct_guard() {
	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( wp_login_url( home_url( $_SERVER['REQUEST_URI'] ) ) );
		exit;
	}
	$fid = glow_ct_fid();
	$u   = wp_get_current_user();
	if ( $fid === $u->ID && ! in_array( 'fundraiser', (array) $u->roles, true ) && ! current_user_can( 'manage_options' ) ) {
		wp_safe_redirect( home_url( '/my-account/' ) );
		exit;
	}
	status_header( 200 );
	nocache_headers();
	return $fid;
}

/* ------------------------------------------------------------------ */
/* C. Virtual pages                                                     */
/* ------------------------------------------------------------------ */
add_action( 'template_redirect', function () {
	$path = trim( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
	$map  = array( 'fundraiser-sellers' => 'sellers', 'fundraiser-invoices' => 'invoices', 'fundraiser-claims' => 'claims', 'fundraiser-orders' => 'orders' );
	if ( ! isset( $map[ $path ] ) ) {
		return;
	}
	$fid  = glow_ct_guard();
	$page = $map[ $path ];

	/* ---------------- Registered sellers ---------------- */
	if ( 'sellers' === $page ) {
		$sellers = get_users( array( 'meta_key' => 'user_registration_seller_fundraiser', 'meta_value' => $fid, 'number' => -1, 'orderby' => 'registered', 'order' => 'DESC' ) );
		$orders  = glow_ct_orders( $fid );
		$by      = array();
		foreach ( $orders as $r ) {
			if ( in_array( $r['raw'], array( 'completed', 'processing' ), true ) && is_numeric( $r['sid'] ) ) {
				$by[ (int) $r['sid'] ]['n']   = ( $by[ (int) $r['sid'] ]['n'] ?? 0 ) + 1;
				$by[ (int) $r['sid'] ]['sum'] = ( $by[ (int) $r['sid'] ]['sum'] ?? 0 ) + (float) $r['total'];
			}
		}
		$reg_link = ( function_exists( 'glow_tw_reg_url' ) ? glow_tw_reg_url( $fid ) : home_url( '/register-as-a-seller/?frid=' . $fid ) );
		glow_ct_open( $fid, 'Registered Sellers', count( $sellers ) . ' seller' . ( 1 === count( $sellers ) ? '' : 's' ) . ' registered under your fundraiser. Share the registration link to add more.' );
		?>
		<div class="glow-ct-card">
			<h3>Seller registration link</h3>
			<div class="glow-ct-link"><input type="text" readonly value="<?php echo esc_attr( $reg_link ); ?>" onclick="this.select()"><a class="glow-ct-btn" href="#" onclick="navigator.clipboard.writeText(this.previousElementSibling.value);this.textContent='Copied!';return false;">Copy link</a><a class="glow-ct-btn is-ghost" target="_blank" rel="noopener" href="<?php echo esc_url( $reg_link ); ?>">Open page</a></div>
		</div>
		<div class="glow-ct-card">
			<h3>Your sellers</h3>
			<?php if ( ! $sellers ) : ?>
				<div class="glow-ct-empty">No sellers have registered yet. Send them the link above.</div>
			<?php else : ?>
			<div class="glow-ct-tw"><table>
				<thead><tr><th>Seller</th><th>Email</th><th>Phone</th><th>Registered</th><th>Orders</th><th>Sales</th><th>Store</th></tr></thead>
				<tbody>
				<?php foreach ( $sellers as $s ) :
					$name  = trim( get_user_meta( $s->ID, 'first_name', true ) . ' ' . get_user_meta( $s->ID, 'last_name', true ) );
					$name  = $name ? $name : $s->display_name;
					$phone = get_user_meta( $s->ID, 'user_registration_phone', true );
					$phone = $phone ? $phone : get_user_meta( $s->ID, 'billing_phone', true );
					$st    = $by[ $s->ID ] ?? array( 'n' => 0, 'sum' => 0 ); ?>
					<tr>
						<td><b><?php echo esc_html( $name ); ?></b></td>
						<td><a href="mailto:<?php echo esc_attr( $s->user_email ); ?>"><?php echo esc_html( $s->user_email ); ?></a></td>
						<td><?php echo esc_html( $phone ? $phone : '—' ); ?></td>
						<td><?php echo esc_html( date_i18n( 'M j, Y', strtotime( $s->user_registered ) ) ); ?></td>
						<td><?php echo (int) $st['n']; ?></td>
						<td><?php echo wp_kses_post( wc_price( $st['sum'] ) ); ?></td>
						<td><a class="glow-ct-btn is-ghost" target="_blank" rel="noopener" href="<?php echo esc_url( home_url( '/seller/' . $s->ID ) ); ?>">View</a></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table></div>
			<?php endif; ?>
		</div>
		<?php
		glow_ct_close();
	}

	$orders = glow_ct_orders( $fid );

	/* ---------------- Invoices ---------------- */
	if ( 'invoices' === $page ) {
		$rows = array_filter( $orders, function ( $r ) { return in_array( $r['raw'], array( 'completed', 'processing' ), true ); } );
		glow_ct_open( $fid, 'Download Invoices', 'A PDF invoice for every paid order credited to your fundraiser.' );
		echo '<div class="glow-ct-card"><h3>Invoices</h3>';
		if ( ! $rows ) {
			echo '<div class="glow-ct-empty">No paid orders yet &mdash; invoices appear here as soon as orders come in.</div>';
		} else {
			echo '<div class="glow-ct-tw"><table><thead><tr><th>Order</th><th>Date</th><th>Customer</th><th>Seller</th><th>Type</th><th>Total</th><th>Invoice</th></tr></thead><tbody>';
			foreach ( $rows as $r ) {
				printf( '<tr><td><b>#%d</b></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td><a class="glow-ct-btn" target="_blank" rel="noopener" href="%s">Download PDF</a></td></tr>',
					$r['id'], esc_html( $r['date'] ), esc_html( $r['customer'] ), esc_html( $r['seller'] ? $r['seller'] : '—' ), esc_html( $r['type'] ), wp_kses_post( wc_price( $r['total'] ) ), esc_url( glow_ct_invoice_url( $r ) ) );
			}
			echo '</tbody></table></div>';
		}
		echo '</div>';
		glow_ct_close();
	}

	/* ---------------- Claims ---------------- */
	if ( 'claims' === $page ) {
		$rows = array_filter( $orders, function ( $r ) { return in_array( $r['raw'], array( 'completed', 'processing' ), true ); } );
		glow_ct_open( $fid, 'Claims &amp; Replacements', 'Something arrived missing, damaged or wrong? Pick the order and tell us &mdash; we will arrange replacements.' );
		echo '<div class="glow-ct-card"><h3>Your orders</h3>';
		if ( ! $rows ) {
			echo '<div class="glow-ct-empty">No shipped orders yet.</div>';
		} else {
			echo '<div class="glow-ct-tw"><table><thead><tr><th>Order</th><th>Date</th><th>Customer</th><th>Status</th><th>Total</th><th>Report</th></tr></thead><tbody>';
			foreach ( $rows as $r ) {
				$btn = $r['claimed']
					? '<span class="glow-ct-tag is-completed">Report submitted</span> <a class="glow-ct-muted" href="' . esc_url( home_url( '/my-account/view-my-order/' . $r['id'] ) ) . '">view</a>'
					: '<a class="glow-ct-btn is-dark" href="' . esc_url( home_url( '/my-account/view-my-order/' . $r['id'] ) ) . '">Report missing / damaged</a>';
				printf( '<tr><td><b>#%d</b></td><td>%s</td><td>%s</td><td><span class="glow-ct-tag is-%s">%s</span></td><td>%s</td><td>%s</td></tr>',
					$r['id'], esc_html( $r['date'] ), esc_html( $r['customer'] ), esc_attr( $r['raw'] ), esc_html( $r['status'] ), wp_kses_post( wc_price( $r['total'] ) ), $btn );
			}
			echo '</tbody></table></div>';
		}
		echo '</div>';
		glow_ct_close();
	}

	/* ---------------- Order status & tracking ---------------- */
	if ( 'orders' === $page ) {
		$n = count( $orders ); $shipped = 0; $sum = 0;
		foreach ( $orders as $r ) { if ( $r['tracking'] ) $shipped++; if ( in_array( $r['raw'], array( 'completed', 'processing' ), true ) ) $sum += (float) $r['total']; }
		glow_ct_open( $fid, 'Order Status &amp; Tracking', 'Every order placed for your fundraiser, with its current status and UPS tracking when shipped.' );
		?>
		<div class="glow-ct-stats">
			<div class="glow-ct-stat is-pink"><b><?php echo (int) $n; ?></b><span>Orders</span></div>
			<div class="glow-ct-stat is-green"><b><?php echo wp_kses_post( wc_price( $sum ) ); ?></b><span>Paid order value</span></div>
			<div class="glow-ct-stat is-blue"><b><?php echo (int) $shipped; ?></b><span>With tracking</span></div>
			<div class="glow-ct-stat is-purple"><b><?php echo (int) count( array_filter( $orders, function ( $r ) { return 'completed' === $r['raw']; } ) ); ?></b><span>Completed</span></div>
		</div>
		<div class="glow-ct-card"><h3>Orders</h3>
		<?php if ( ! $orders ) : ?>
			<div class="glow-ct-empty">No orders yet.</div>
		<?php else : ?>
			<div class="glow-ct-tw"><table><thead><tr><th>Order</th><th>Date</th><th>Customer</th><th>Seller</th><th>Type</th><th>Total</th><th>Status</th><th>Tracking</th></tr></thead><tbody>
			<?php foreach ( $orders as $r ) :
				$trk = $r['tracking'] ? '<a target="_blank" rel="noopener" href="https://www.ups.com/track?loc=en_US&tracknum=' . rawurlencode( $r['tracking'] ) . '">' . esc_html( $r['tracking'] ) . '</a>' : '<span class="glow-ct-muted">Not shipped yet</span>'; ?>
				<tr><td><b>#<?php echo (int) $r['id']; ?></b></td><td><?php echo esc_html( $r['date'] ); ?></td><td><?php echo esc_html( $r['customer'] ); ?></td><td><?php echo esc_html( $r['seller'] ? $r['seller'] : '—' ); ?></td><td><?php echo esc_html( $r['type'] ); ?></td><td><?php echo wp_kses_post( wc_price( $r['total'] ) ); ?></td><td><span class="glow-ct-tag is-<?php echo esc_attr( $r['raw'] ); ?>"><?php echo esc_html( $r['status'] ); ?></span></td><td><?php echo $trk; ?></td></tr>
			<?php endforeach; ?>
			</tbody></table></div>
		<?php endif; ?>
		</div>
		<?php
		glow_ct_close();
	}
}, 1 );

/* Dashboard tiles -> real pages */
add_action( 'wp_footer', function () {
	if ( ! is_user_logged_in() ) {
		return;
	}
	$fid = glow_ct_fid();
	$map = array(
		'#glow-fd-sellers-card' => glow_ct_url( '/fundraiser-sellers/', $fid ),
		'#glow-fd-orders'       => glow_ct_url( '/fundraiser-orders/', $fid ),
		'#glow-fd-share'        => ( function_exists( 'glow_tw_reg_url' ) ? glow_tw_reg_url( $fid ) : home_url( '/register-as-a-seller/?frid=' . $fid ) ),
	);
	?>
	<script id="glow-fd-tiles">
	(function(){
		if (!document.querySelector('.glow-fd')) return;
		var map = <?php echo wp_json_encode( $map ); ?>;
		var inv = <?php echo wp_json_encode( glow_ct_url( '/fundraiser-invoices/', $fid ) ); ?>, cl = <?php echo wp_json_encode( glow_ct_url( '/fundraiser-claims/', $fid ) ); ?>;
		document.querySelectorAll('.glow-fd a[href^="#glow-fd-"]').forEach(function(a){
			var h = a.getAttribute('href'), t = (a.textContent || '').replace(/\s+/g, ' ');
			if (h === '#glow-fd-orders' && /Invoice/i.test(t)) { a.href = inv; return; }
			if (h === '#glow-fd-orders' && /Claim/i.test(t)) { a.href = cl; return; }
			if (map[h]) a.href = map[h];
		});
	})();
	</script>
	<?php
}, 20 );

/* ------------------------------------------------------------------ */
/* D. Chairpersons can download invoices for their fundraiser's orders */
/* ------------------------------------------------------------------ */
add_filter( 'wpo_wcpdf_check_privs', function ( $allowed, $order_ids ) {
	if ( $allowed || ! is_user_logged_in() ) {
		return $allowed;
	}
	$uid = get_current_user_id();
	foreach ( (array) $order_ids as $oid ) {
		$o = wc_get_order( $oid );
		if ( ! $o || (int) $o->get_meta( 'fundraiser_field' ) !== $uid ) {
			return $allowed;
		}
	}
	return true;
}, 10, 2 );

/* ------------------------------------------------------------------ */
/* E. Empty "Room Sprays" category used by the store tiles             */
/* ------------------------------------------------------------------ */
add_action( 'template_redirect', function () {
	if ( is_tax( 'product_cat', 'room-sprays-all-items' ) ) {
		$t = get_term_by( 'slug', 'room-sprays-5oz', 'product_cat' );
		if ( $t && ! is_wp_error( $t ) ) {
			wp_safe_redirect( get_term_link( $t ), 301 );
			exit;
		}
	}
}, 0 );

/* Same slug is used by the brochure order pages (chair + seller): point those queries at the real category. */
add_filter( 'woocommerce_product_object_query_args', function ( $args ) {
	if ( ! empty( $args['category'] ) ) {
		$cats = (array) $args['category'];
		$cats = array_map( function ( $c ) { return 'room-sprays-all-items' === $c ? 'room-sprays-5oz' : $c; }, $cats );
		$args['category'] = is_array( $args['category'] ) ? $cats : $cats[0];
	}
	return $args;
} );
