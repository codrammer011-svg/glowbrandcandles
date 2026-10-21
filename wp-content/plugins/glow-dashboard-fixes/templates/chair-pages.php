<?php
/**
 * GLOW Dashboard Fixes – chairperson (fundraiser) pages.
 *
 *  A. Styles the three legacy pages linked from the fundraiser dashboard so they match it:
 *     /edit-fundraiser/ (User Registration edit form), /upload-image-and-description/
 *     (logo + fundraising message) and /fundraiser-reports/ (sales report).
 *  B. Public store pages showed "$0 raised": the legacy templates query orders with
 *     status "published", which is not a WooCommerce status, so nothing was ever counted.
 *  C. "Missing / damaged" → /my-account/view-my-order/ID pointed at an endpoint that no
 *     longer exists (blank page). Provides a claims / replacements form there.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ------------------------------------------------------------------ */
/* Helpers                                                              */
/* ------------------------------------------------------------------ */
function glow_cp_page() {
	if ( ! is_page() ) {
		return '';
	}
	$tpl = basename( (string) get_page_template_slug() );
	if ( 'edit_fundraiser-info.php' === $tpl ) {
		return 'info';
	}
	if ( 'edit_fundraiser-profile.php' === $tpl ) {
		return 'profile';
	}
	if ( 'fundraiser-reports.php' === $tpl ) {
		return 'reports';
	}
	return '';
}

function glow_cp_fid() {
	$fid = isset( $_GET['frid'] ) ? absint( $_GET['frid'] ) : 0;
	if ( ! $fid || ! current_user_can( 'manage_options' ) ) {
		$fid = get_current_user_id();
	}
	return $fid;
}

function glow_cp_dashboard_url( $fid ) {
	$url = home_url( '/fundraiser-dashboard/' );
	if ( current_user_can( 'manage_options' ) && $fid && $fid !== get_current_user_id() ) {
		$url = add_query_arg( 'frid', $fid, $url );
	}
	return $url;
}

/* ------------------------------------------------------------------ */
/* 0. /edit-fundraiser/ needs ?frid=… or it bounces to the home page.   */
/*    The dashboard links to it without one, so chairpersons could never */
/*    open it. Fill in their own id, and keep people out of other       */
/*    chairpersons' forms.                                              */
/* ------------------------------------------------------------------ */
add_action( 'template_redirect', function () {
	if ( 'info' !== glow_cp_page() ) {
		return;
	}
	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( wp_login_url( home_url( $_SERVER['REQUEST_URI'] ) ) );
		exit;
	}
	$me   = get_current_user_id();
	$frid = isset( $_GET['frid'] ) ? absint( $_GET['frid'] ) : 0;
	if ( ! $frid || ( $frid !== $me && ! current_user_can( 'manage_options' ) ) ) {
		wp_safe_redirect( add_query_arg( 'frid', $me, remove_query_arg( 'frid' ) ) );
		exit;
	}
	/* The template writes every field on save, so an empty logo field wiped the
	 * chairperson's logo. Keep the stored logo (and account email) when the field is blank. */
	if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['submit'] ) ) {
		if ( empty( $_POST['image_url_profile'] ) ) {
			$_POST['image_url_profile'] = (string) get_user_meta( $frid, 'user_registration_image_url_profile', true );
		}
		if ( empty( $_POST['user_email'] ) ) {
			$u = get_userdata( $frid );
			$_POST['user_email'] = $u ? $u->user_email : '';
		}
	}
}, 0 );

/* ------------------------------------------------------------------ */
/* A. Shared look for the legacy chairperson pages                      */
/* ------------------------------------------------------------------ */
add_action( 'wp_head', function () {
	$page = glow_cp_page();
	if ( ! $page ) {
		return;
	}
	?>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
	<style id="glow-cp-css">
	.glow-cp{--pink:#ec0f63;--ink:#181735;--muted:#6f6c85;--board:#f2f3f9;--line:#e8e8f0;font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:var(--ink);background:var(--board);padding:28px 18px 48px;-webkit-font-smoothing:antialiased}
	.glow-cp *{box-sizing:border-box}
	.glow-cp-wrap{max-width:960px;margin:0 auto}
	.glow-cp-back{display:inline-flex;align-items:center;gap:6px;font-size:14px;font-weight:600;color:#4a3fd0 !important;text-decoration:none !important;margin-bottom:14px}
	.glow-cp h1.glow-cp-title{margin:0 0 4px;font-size:26px;font-weight:800;letter-spacing:.01em;color:var(--ink)}
	.glow-cp-sub{margin:0 0 20px;color:var(--muted);font-size:15px}
	.glow-cp-card{background:#fff;border:1px solid var(--line);border-radius:20px;box-shadow:0 2px 6px rgba(22,20,50,.05);padding:26px 28px;margin-bottom:18px}
	.glow-cp-notice{padding:12px 16px;border-radius:12px;font-size:14.5px;margin-bottom:18px;background:#eef4ff;color:#2b3a8f;border:1px solid #cfdcff}
	.glow-cp .glow-cp-btn,.glow-cp button[type=submit],.glow-cp input[type=submit],.glow-cp .ur-submit-button{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:12px 28px;border-radius:999px;background:var(--pink) !important;color:#fff !important;font-weight:700;font-size:14px;letter-spacing:.03em;text-transform:uppercase;border:0 !important;cursor:pointer;text-decoration:none !important;width:auto !important;line-height:1.2;font-family:inherit}
	.glow-cp button[type=submit]:hover,.glow-cp input[type=submit]:hover{filter:brightness(1.06)}
	.glow-cp .glow-cp-btn.is-ghost{background:#fff !important;color:var(--ink) !important;border:1.5px solid #d9d7e6 !important}
	/* hide the theme's page heading / breadcrumb – we print our own */
	.glow-cp .page-heading,.glow-cp .breadcrumbs,.glow-cp .intro-content,body.page-template-edit_fundraiser-profile .page-heading,body.page-template-edit_fundraiser-info .page-heading,body.page-template-templatesfundraiser-reports-php .page-heading{display:none !important}
	/* --- inputs --- */
	.glow-cp input[type=text],.glow-cp input[type=email],.glow-cp input[type=tel],.glow-cp input[type=number],.glow-cp input[type=date],.glow-cp input[type=url],.glow-cp input[type=password],.glow-cp select,.glow-cp textarea{width:100%;font:inherit;font-size:15px;padding:11px 14px;border:1px solid #d9d7e6 !important;border-radius:10px !important;background:#fff;color:var(--ink);box-shadow:none !important;height:auto !important;line-height:1.4}
	.glow-cp input:focus,.glow-cp select:focus,.glow-cp textarea:focus{outline:none;border-color:var(--pink) !important;box-shadow:0 0 0 3px rgba(236,15,99,.12) !important}
	.glow-cp textarea{min-height:140px;resize:vertical}
	.glow-cp label,.glow-cp .ur-label{display:block;font-size:12.5px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;color:#8d8aa0;margin:0 0 6px}
	.glow-cp .ur-label abbr,.glow-cp label abbr{color:var(--pink);text-decoration:none;border:0}
	<?php if ( 'info' === $page ) : ?>
	/* --- User Registration edit form --- */
	.glow-cp .ur-frontend-form{border:0 !important;padding:0 !important;margin:0 !important;background:none !important}
	.glow-cp .ur-form-grid{display:grid !important;grid-template-columns:repeat(12,minmax(0,1fr));gap:18px 20px;padding:0 !important}
	.glow-cp .ur-field-item{grid-column:span 12;margin:0 !important;padding:0 !important;width:auto !important}
	.glow-cp .ur-field-item.w-50{grid-column:span 6}
	.glow-cp .ur-field-item.w-33{grid-column:span 4}
	.glow-cp .ur-field-item.w-25{grid-column:span 3}
	.glow-cp .ur-field-item.w-66{grid-column:span 8}
	.glow-cp .ur-field-item.w-75{grid-column:span 9}
	.glow-cp .ur-field-item.field-radio,.glow-cp .ur-field-item.field-checkbox{grid-column:span 12}
	.glow-cp .ur-field-item .form-row{margin:0 !important;padding:0 !important;display:block}
	.glow-cp .ur-field-item ul{list-style:none !important;margin:4px 0 0 !important;padding:0 !important;display:flex;flex-wrap:wrap;gap:10px}
	.glow-cp .ur-field-item ul li{margin:0 !important;list-style:none !important;display:inline-flex;align-items:center;gap:9px;padding:9px 14px !important;border:1px solid #d9d7e6;border-radius:999px;background:#fff;cursor:pointer}
	.glow-cp .ur-field-item ul li:has(input:checked){border-color:var(--pink);background:#fff0f4}
	.glow-cp .ur-field-item ul li label{display:inline;text-transform:none;letter-spacing:0;font-size:14px;font-weight:600;color:var(--ink);margin:0;cursor:pointer}
	.glow-cp .ur-field-item ul li:has(input:checked) label{color:#9b1239}
	.glow-cp .ur-field-item input[type=radio],.glow-cp .ur-field-item input[type=checkbox]{margin:0 !important;width:16px;height:16px;flex:0 0 16px;accent-color:var(--pink);position:static;opacity:1}
	.glow-cp .ur-field-item.field-checkbox > .form-row > .ur-label{display:none}
	.glow-cp .ur-field-item.field-checkbox ul li{border-radius:16px;align-items:flex-start;padding:14px 16px !important;background:#f7f7fb;border-color:#e8e8f0}
	.glow-cp .ur-field-item.field-checkbox ul li input{margin-top:3px !important}
	.glow-cp .ur-field-item.field-checkbox ul li label{font-weight:500;line-height:1.5}
	.glow-cp .ur-field-item .description,.glow-cp .ur-field-item .user-registration-help-tip{font-size:13px;color:var(--muted);text-transform:none;letter-spacing:0;font-weight:400}
	.glow-cp .ur-section-title,.glow-cp .ur-field-item h2,.glow-cp .ur-field-item h3{grid-column:span 12}
	.glow-cp .ur-button-container{grid-column:span 12;margin-top:6px}
	.glow-cp .user-registration-error,.glow-cp .ur-error{font-size:13px;color:#c21646;margin-top:4px;text-transform:none;letter-spacing:0;font-weight:500}
	.glow-cp .ur-submit-button{margin-top:10px}
	.glow-cp .user-registration-message,.glow-cp .user-registration-error.ur-notice{padding:12px 16px;border-radius:12px;margin-bottom:18px;background:#eaf8f1;color:#0d6b50;border:1px solid #c6ecd9;font-size:14.5px;list-style:none}
	.glow-cp .ur-frontend-form .ur-submit-button .ur-front-spinner{margin-left:8px}
	<?php elseif ( 'profile' === $page ) : ?>
	/* --- logo / message page --- */
	.glow-cp .wrap-q{padding:0 !important;margin:0 !important}
	.glow-cp .wrap-q .container{padding:0 !important;max-width:none !important;width:auto !important}
	.glow-cp form.p-form{width:100% !important;max-width:560px !important;margin:0 auto !important;border:0 !important;padding:0 !important;background:none !important;box-shadow:none !important;float:none !important}
	.glow-cp .p-form .browse-button{opacity:1 !important;width:auto !important;height:auto !important;top:auto !important;left:auto !important;right:auto !important;bottom:auto !important;transform:none !important;margin:0 !important}
	.glow-cp .p-form .form-group{text-align:center}
	.glow-cp .p-form .preview{display:flex !important;flex-direction:column;align-items:center;gap:14px;margin:0 0 18px !important;height:auto !important;min-height:0 !important;width:auto !important;max-width:none !important;border:0 !important;padding:0 !important;background:none !important;box-shadow:none !important}
	.glow-cp .p-form .preview-img{width:220px !important;height:220px !important;max-width:100%;border-radius:24px !important;object-fit:cover;border:4px solid #ffd3e0 !important;background:#ffe1e8;display:block;margin:0 !important;position:static !important}
	.glow-cp .p-form .browse-button{position:relative;display:inline-flex;align-items:center;gap:8px;padding:11px 20px;border-radius:999px;background:#fff;border:1.5px solid #d9d7e6;font-weight:700;font-size:13px;letter-spacing:.04em;text-transform:uppercase;cursor:pointer;overflow:hidden;color:var(--ink)}
	.glow-cp .p-form .browse-button::after{content:'Choose a photo / logo'}
	.glow-cp .p-form .browse-button .fa{color:var(--pink)}
	.glow-cp .p-form .browse-input{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%}
	.glow-cp .p-form small{display:block;font-size:13.5px;color:var(--muted);margin:0 0 18px;line-height:1.5}
	.glow-cp .p-form .form-group{margin:0 0 16px}
	.glow-cp .p-form .Error{display:block;color:#c21646;font-size:13px}
	.glow-cp .p-form #preview p{margin:10px 0 0;color:#0d6b50;font-weight:600}
	.glow-cp .p-form #preview.hide{display:none}
	.glow-cp .p-form #preview.show{display:block;margin-top:12px;padding:12px 16px;border-radius:12px;background:#eaf8f1;color:#0d6b50;border:1px solid #c6ecd9;text-align:center}
	.glow-cp .p-form #preview.show p{margin:0;color:#0d6b50}
	.glow-cp .p-form #preview.show a{color:#0d6b50;font-weight:700;text-decoration:underline}
	.glow-cp-tabs{display:flex;gap:8px;margin-bottom:18px;flex-wrap:wrap}
	.glow-cp-tabs a{padding:9px 18px;border-radius:999px;border:1.5px solid #d9d7e6;font-size:13.5px;font-weight:700;color:var(--ink) !important;text-decoration:none !important;background:#fff}
	.glow-cp-tabs a.is-on{background:var(--ink);border-color:var(--ink);color:#fff !important}
	<?php else : ?>
	/* --- reports --- */
	.glow-cp .row,.glow-cp .content{margin:0 !important;padding:0 !important;width:auto !important;max-width:none !important;display:block}
	.glow-cp .fundraiser-dashboard-section.report{background:#fff;border:1px solid var(--line);border-radius:20px;box-shadow:0 2px 6px rgba(22,20,50,.05);padding:24px 26px;margin:0 0 18px}
	.glow-cp .fundraiser-dashboard-section.report h3{margin:0 0 14px;font-size:16.5px;font-weight:800;letter-spacing:.02em;text-transform:uppercase;color:var(--ink)}
	.glow-cp .fundraiser-dashboard-section.report h5{margin:0 0 8px;font-size:15px;font-weight:500;color:var(--muted)}
	.glow-cp .fundraiser-dashboard-section.report h5 strong{color:var(--ink);font-weight:800;font-size:17px}
	.glow-cp .total-sale h5{display:inline-block;margin:0 28px 10px 0;padding:14px 18px;border-radius:14px;background:#fff0f4}
	.glow-cp .total-sale h5:nth-of-type(2){background:#ecf9f3}
	.glow-cp .total-sale h5:nth-of-type(3){background:#eef4ff}
	.glow-cp .admin-report{display:flex;gap:10px;flex-wrap:wrap;margin:0 0 16px}
	.glow-cp .details-btn{display:inline-flex;align-items:center;padding:7px 14px;border-radius:999px;background:var(--ink);color:#fff;font-size:12.5px;font-weight:700;letter-spacing:.03em;cursor:pointer;border:0;line-height:1.2}
	.glow-cp .admin-report .details-btn{background:#fff;color:var(--ink);border:1.5px solid #d9d7e6}
	.glow-cp .fundraiser-order-table{overflow-x:auto;margin-top:10px}
	.glow-cp .fundraiser-order-row{display:flex;flex-wrap:wrap;align-items:center;min-width:680px;border-bottom:1px solid #f0eff4;font-size:14.5px}
	.glow-cp .fundraiser-order-row:not(.fr-header):hover{background:#faf9fc}
	.glow-cp .fundraiser-order-row.fr-header{border-bottom:1px solid var(--line)}
	.glow-cp .fundraiser-order-row.fr-header .fr-order-col{font-size:12px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;color:#8d8aa0;white-space:nowrap}
	.glow-cp .fr-order-col{padding:12px 10px;flex:1 1 0}
	.glow-cp .fr-order-id{flex:0 0 80px;font-weight:700}
	.glow-cp .fr-order-date{flex:0 0 110px;white-space:nowrap}
	.glow-cp .fr-order-customer{flex:1.5 1 0}
	.glow-cp .fr-order-total,.glow-cp .fr-order-rebate{flex:0 0 120px;font-weight:600;white-space:nowrap}
	.glow-cp .fr-order-action{flex:0 0 100px;text-align:right}
	.glow-cp .fundraiser-order-details-table{flex:1 1 100%;width:100%;background:#f7f7fb;border-radius:12px;margin:0 0 12px;padding:6px 10px}
	.glow-cp .fundraiser-order-details-table:not(.show){display:none}
	.glow-cp .fundraiser-order-details-row.fr-footer,.glow-cp .fundraiser-order-details-row:has(.product-total){font-weight:600}
	.glow-cp .fundraiser-order-details-row{display:flex;font-size:13.5px;border-bottom:1px solid #ebeaf2}
	.glow-cp .fundraiser-order-details-row:last-child{border-bottom:0}
	.glow-cp .fundraiser-order-details-row.fr-header .fundraiser-order-details-col{font-size:11.5px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;color:#8d8aa0}
	.glow-cp .fundraiser-order-details-col{padding:8px 6px;flex:1 1 0}
	.glow-cp .fundraiser-order-details-col.product-name{flex:3 1 0}
	.glow-cp-print{float:right}
	@media print{.glow-cp-back,.glow-cp-print,.admin-report,.fr-order-action,header,footer,#wpadminbar{display:none !important}.glow-cp{background:#fff;padding:0}.glow-cp .fundraiser-order-details-table{display:block !important}}
	<?php endif; ?>
	@media(max-width:720px){.glow-cp{padding:18px 12px 36px}.glow-cp-card{padding:20px 18px}.glow-cp .ur-field-item.w-50,.glow-cp .ur-field-item.w-33,.glow-cp .ur-field-item.w-25,.glow-cp .ur-field-item.w-66,.glow-cp .ur-field-item.w-75{grid-column:span 12}.glow-cp .total-sale h5{display:block;margin-right:0}}
	</style>
	<?php
} );

add_action( 'wp_footer', function () {
	$page = glow_cp_page();
	if ( ! $page ) {
		return;
	}
	$fid  = glow_cp_fid();
	$back = glow_cp_dashboard_url( $fid );
	$type = isset( $_GET['type'] ) ? sanitize_key( $_GET['type'] ) : 'image';
	$titles = array(
		'info'    => array( 'Edit Fundraiser Info', 'Update your group, chairperson and shipping details. Changes show on your dashboard and store right away.' ),
		'profile' => array( 'image' === $type ? 'Add Your Logo or Photo' : 'Your Fundraising Message', 'image' === $type ? 'This picture appears at the top of your fundraiser store and on your sellers&rsquo; pages. Square images (475 &times; 475 px) look best.' : 'Tell supporters what you are raising money for. It shows on your fundraiser store and share messages.' ),
		'reports' => array( 'Sales &amp; Reports', 'Every online and brochure order credited to your fundraiser, with the rebate earned on each.' ),
	);
	$tabs = '';
	if ( 'profile' === $page ) {
		$base = remove_query_arg( 'type' );
		$tabs = '<div class="glow-cp-tabs">'
			. '<a class="' . ( 'image' === $type ? 'is-on' : '' ) . '" href="' . esc_url( add_query_arg( 'type', 'image', $base ) ) . '">Logo / Photo</a>'
			. '<a class="' . ( 'description' === $type ? 'is-on' : '' ) . '" href="' . esc_url( add_query_arg( 'type', 'description', $base ) ) . '">Fundraising Message</a>'
			. '</div>';
	}
	$admin = '';
	if ( current_user_can( 'manage_options' ) && $fid && $fid !== get_current_user_id() ) {
		$u     = get_userdata( $fid );
		$admin = '<div class="glow-cp-notice">Admin view &mdash; fundraiser <b>' . esc_html( $u ? $u->display_name : '' ) . '</b> (#' . (int) $fid . ').</div>';
	}
	$print = 'reports' === $page ? '<a href="#" class="glow-cp-btn is-ghost glow-cp-print" onclick="window.print();return false;">Print report</a>' : '';
	$head  = '<a class="glow-cp-back" href="' . esc_url( $back ) . '">&larr; Back to my dashboard</a>' . $print
		. '<h1 class="glow-cp-title">' . $titles[ $page ][0] . '</h1><p class="glow-cp-sub">' . $titles[ $page ][1] . '</p>' . $tabs . $admin;
	?>
	<script id="glow-cp-js">
	(function(){
		var sel = <?php echo wp_json_encode( 'reports' === $page ? '.page-content' : ( 'profile' === $page ? '.wrap-q' : '.page-content' ) ); ?>;
		var target = document.querySelector(sel);
		if (!target) return;
		var card = <?php echo 'reports' === $page ? 'false' : 'true'; ?>;
		var shell = document.createElement('div'); shell.className = 'glow-cp';
		var wrap = document.createElement('div'); wrap.className = 'glow-cp-wrap';
		shell.appendChild(wrap);
		var head = document.createElement('div'); head.innerHTML = <?php echo wp_json_encode( $head ); ?>;
		while (head.firstChild) wrap.appendChild(head.firstChild);
		target.parentNode.insertBefore(shell, target);
		if (card) { var c = document.createElement('div'); c.className = 'glow-cp-card'; c.appendChild(target); wrap.appendChild(c); }
		else { wrap.appendChild(target); }
		<?php if ( 'profile' === $page ) : ?>
		var ph = document.querySelector('.glow-cp .page-heading'); if (ph) ph.style.display = 'none';
		var img = document.getElementById('img-upload');
		if (img && !img.getAttribute('src')) { img.style.display = 'none'; }
		<?php endif; ?>
		<?php if ( 'info' === $page ) : ?>
		document.querySelectorAll('.glow-cp .ur-submit-button').forEach(function(b){ if (!b.textContent.trim()) b.textContent = 'Save changes'; });
		var imgField = document.querySelector('.glow-cp [name=image_url_profile]');
		if (imgField) { var box = imgField.closest('.ur-field-item') || imgField; box.style.display = 'none'; }
		var em = document.querySelector('.glow-cp [name=user_email]');
		if (em && !em.value) { em.value = <?php $cu = get_userdata( $fid ); echo wp_json_encode( $cu ? $cu->user_email : '' ); ?>; }
		var saved = document.getElementById('flash') || document.getElementById('success_message');
		if (saved && saved.textContent.trim()) { saved.className = 'glow-cp-notice'; saved.style.cssText = 'background:#eaf8f1;color:#0d6b50;border-color:#c6ecd9;padding:12px 16px;font-size:14.5px'; var wrapEl = document.querySelector('.glow-cp-wrap'); var cardEl = document.querySelector('.glow-cp-card'); if (wrapEl && cardEl) wrapEl.insertBefore(saved, cardEl); }
		<?php endif; ?>
	})();
	</script>
	<?php
}, 5 );

/* ------------------------------------------------------------------ */
/* A2. Store welcome popup: a fundraising message longer than one line */
/*     ran over the "Support Now" progress block. Nudge the blocks     */
/*     below it down (popup is 440px tall, OK button had 40px of       */
/*     slack); the script grows the popup for longer messages so the   */
/*     whole message shows.                                             */
/* ------------------------------------------------------------------ */
add_action( 'wp_head', function () {
	if ( ! get_query_var( 'profile' ) && ! get_query_var( 'user' ) && ! get_query_var( 'seller' ) ) {
		return;
	}
	echo '<style id="glow-cp-popup">@media(min-width:768px){'
		. '#brave_popup_12975 #brave_element--N3sYSN7DAn8xy7o0Mnh{top:158px !important}'
		. '#brave_popup_12975 #brave_element--N3sYSN7DAn8xy7o0Mnh .brave_element__text_inner{font-size:14px !important;line-height:1.3 !important}'
		. '#brave_popup_12975 #brave_element--WWW{top:204px !important}'
		. '#brave_popup_12975 .exit-intent-popup-button{top:383px !important}'
		. '}</style>';
} );

add_action( 'wp_footer', function () {
	if ( ! get_query_var( 'profile' ) && ! get_query_var( 'user' ) && ! get_query_var( 'seller' ) ) {
		return;
	}
	?>
	<script id="glow-cp-popup-fit">
	(function () {
		var pop = document.getElementById('brave_popup_12975');
		if (!pop) return;
		var inner = pop.querySelector('.brave_popup__step__inner'), wrap = pop.querySelector('.brave_popupSections__wrap');
		var title = document.getElementById('brave_element--N3sYSN7DAn8xy7o0Mng'), desc = document.getElementById('brave_element--N3sYSN7DAn8xy7o0Mnh'), support = document.getElementById('brave_element--WWW'), ok = pop.querySelector('.exit-intent-popup-button');
		if (!inner || !wrap || !title || !desc || !support || !ok) return;
		var chain = [title, desc, support, ok];
		// Top to bottom: wherever a block (a two-line name, a long message) runs into the next one, push everything below it
		// down by that much, and grow the popup by the same amount.
		function fit() {
			chain.forEach(function (el) { el.style.removeProperty('top'); });
			['height', 'max-height', 'overflow-y', 'margin-top'].forEach(function (p) { inner.style.removeProperty(p); });
			wrap.style.removeProperty('height');
			if (!desc.offsetHeight) return;
			var extra = 0;
			for (var i = 1; i < chain.length; i++) {
				var prev = chain[i - 1], el = chain[i], base = el.offsetTop;
				var overlap = prev.offsetTop + prev.offsetHeight + 6 - (base + extra);
				if (overlap > 0) extra += overlap;
				if (extra) el.style.setProperty('top', (base + extra) + 'px', 'important');
			}
			if (extra <= 0) return;
			var h = inner.offsetHeight + extra, max = window.innerHeight - 40;
			wrap.style.height = h + 'px';
			inner.style.height = 'auto';
			inner.style.maxHeight = max + 'px';
			inner.style.overflowY = 'auto';
			inner.style.marginTop = (-Math.min(h, max) / 2) + 'px';
		}
		fit();
		window.addEventListener('load', fit);
		window.addEventListener('resize', fit);
		if (document.fonts && document.fonts.ready) document.fonts.ready.then(fit);
	})();
	</script>
	<?php
}, 100 );

/* ------------------------------------------------------------------ */
/* B. Legacy templates query orders with status "published" (invalid)  */
/* ------------------------------------------------------------------ */
add_filter( 'woocommerce_order_query_args', function ( $args ) {
	if ( empty( $args['status'] ) ) {
		return $args;
	}
	$st = (array) $args['status'];
	if ( in_array( 'published', $st, true ) || in_array( 'wc-published', $st, true ) ) {
		$all = array_map( function ( $s ) { return 'wc-' === substr( $s, 0, 3 ) ? substr( $s, 3 ) : $s; }, array_keys( wc_get_order_statuses() ) );
		$args['status'] = $all; // the templates filter completed/processing themselves
	}
	return $args;
} );

/* ------------------------------------------------------------------ */
/* C. Claims / replacements form at /my-account/view-my-order/ID        */
/* ------------------------------------------------------------------ */
add_action( 'template_redirect', function () {
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '';
	if ( ! preg_match( '#/my-account/view-my-order/(\d+)/?#', $uri, $m ) ) {
		return;
	}
	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( wp_login_url( home_url( $uri ) ) );
		exit;
	}
	$order = wc_get_order( (int) $m[1] );
	if ( ! $order ) {
		wp_die( 'Order not found.', 'Order not found', array( 'response' => 404 ) );
	}
	$uid   = get_current_user_id();
	$fid   = (int) $order->get_meta( 'fundraiser_field' );
	$allow = current_user_can( 'manage_options' ) || ( $fid && $fid === $uid ) || ( (int) $order->get_customer_id() === $uid );
	if ( ! $allow ) {
		wp_die( 'You do not have permission to view this order.', 'No permission', array( 'response' => 403 ) );
	}

	$saved = false;
	$error = '';
	if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['glow_claim_nonce'] ) ) {
		if ( ! wp_verify_nonce( $_POST['glow_claim_nonce'], 'glow_claim_' . $order->get_id() ) ) {
			$error = 'Your session expired. Please try again.';
		} else {
			$lines = array();
			$items = isset( $_POST['claim_qty'] ) ? (array) $_POST['claim_qty'] : array();
			foreach ( $order->get_items() as $item_id => $item ) {
				$q = isset( $items[ $item_id ] ) ? absint( $items[ $item_id ] ) : 0;
				if ( $q > 0 ) {
					$issue   = isset( $_POST['claim_issue'][ $item_id ] ) ? sanitize_text_field( wp_unslash( $_POST['claim_issue'][ $item_id ] ) ) : 'missing';
					$lines[] = sprintf( '%d x %s (%s)', min( $q, $item->get_quantity() ), wp_strip_all_tags( $item->get_name() ), $issue );
				}
			}
			$notes = isset( $_POST['claim_notes'] ) ? sanitize_textarea_field( wp_unslash( $_POST['claim_notes'] ) ) : '';
			if ( ! $lines && '' === trim( $notes ) ) {
				$error = 'Please enter a quantity for at least one item, or describe the problem.';
			} else {
				$text = "Missing / damaged report\n" . implode( "\n", $lines ) . ( $notes ? "\nNotes: " . $notes : '' )
					. "\nSubmitted by: " . wp_get_current_user()->display_name . ' (#' . $uid . ')';
				$order->update_meta_data( 'claim_requested', 1 );
				$order->update_meta_data( 'claim_details', $text );
				$order->update_meta_data( 'claim_date', current_time( 'mysql' ) );
				$order->save();
				$order->add_order_note( $text, false, true );
				$to = array_unique( array_filter( array( get_option( 'admin_email' ), get_option( 'woocommerce_email_from_address' ) ) ) );
				wp_mail( $to, sprintf( '[GLOW] Missing / damaged report for order #%d', $order->get_id() ), $text . "\n\n" . admin_url( 'post.php?post=' . $order->get_id() . '&action=edit' ) );
				$saved = true;
			}
		}
	}

	$claimed  = (bool) $order->get_meta( 'claim_requested' );
	$details  = (string) $order->get_meta( 'claim_details' );
	$back     = glow_cp_dashboard_url( $fid ? $fid : $uid );
	$shipped  = $order->get_shipping_address_1() ? $order->get_formatted_shipping_address() : $order->get_formatted_billing_address();

	status_header( 200 );
	nocache_headers();
	get_header();
	?>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
	<style>
	.glow-cl{--pink:#ec0f63;--ink:#181735;--muted:#6f6c85;--line:#e8e8f0;font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:var(--ink);background:#f2f3f9;padding:28px 18px 48px;-webkit-font-smoothing:antialiased}
	.glow-cl *{box-sizing:border-box}.glow-cl-wrap{max-width:860px;margin:0 auto}
	.glow-cl-back{display:inline-flex;gap:6px;font-size:14px;font-weight:600;color:#4a3fd0 !important;text-decoration:none !important;margin-bottom:14px}
	.glow-cl h1{margin:0 0 4px;font-size:26px;font-weight:800}.glow-cl-sub{margin:0 0 20px;color:var(--muted);font-size:15px}
	.glow-cl-card{background:#fff;border:1px solid var(--line);border-radius:20px;box-shadow:0 2px 6px rgba(22,20,50,.05);padding:26px 28px;margin-bottom:18px}
	.glow-cl-card h3{margin:0 0 14px;font-size:16.5px;font-weight:800;letter-spacing:.02em;text-transform:uppercase}
	.glow-cl-meta{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;font-size:14.5px}.glow-cl-meta b{display:block;font-size:12px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;color:#8d8aa0;margin-bottom:3px}
	.glow-cl table{width:100%;border-collapse:collapse;font-size:14.5px}.glow-cl th{padding:10px 10px;text-align:left;font-size:12px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;color:#8d8aa0;border-bottom:1px solid var(--line)}
	.glow-cl td{padding:12px 10px;border:0;border-bottom:1px solid #f0eff4;vertical-align:middle;background:transparent}
	.glow-cl th{border-left:0;border-right:0;border-top:0;background:transparent}.glow-cl table,.glow-cl tr{border:0;background:transparent}
	.glow-cl input[type=number],.glow-cl select,.glow-cl textarea{font:inherit;font-size:14.5px;padding:9px 12px;border:1px solid #d9d7e6;border-radius:10px;background:#fff;color:var(--ink)}
	.glow-cl input[type=number]{width:80px}.glow-cl textarea{width:100%;min-height:110px;resize:vertical}
	.glow-cl label{display:block;font-size:12.5px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;color:#8d8aa0;margin:16px 0 6px}
	.glow-cl-btn{display:inline-flex;align-items:center;padding:12px 28px;border-radius:999px;background:var(--pink);color:#fff !important;font-weight:700;font-size:14px;letter-spacing:.03em;text-transform:uppercase;border:0;cursor:pointer;margin-top:18px;text-decoration:none !important}
	.glow-cl-notice{padding:12px 16px;border-radius:12px;font-size:14.5px;margin-bottom:18px;background:#eaf8f1;color:#0d6b50;border:1px solid #c6ecd9;white-space:pre-line}
	.glow-cl-notice.is-error{background:#fff0f3;color:#9b1239;border-color:#f7c9d6}
	@media(max-width:720px){.glow-cl-meta{grid-template-columns:1fr}.glow-cl-card{padding:20px 18px}}
	</style>
	<div class="glow-cl"><div class="glow-cl-wrap">
	<a class="glow-cl-back" href="<?php echo esc_url( $back ); ?>">&larr; Back to my dashboard</a>
	<h1>Missing or Damaged Items</h1>
	<p class="glow-cl-sub">Order #<?php echo (int) $order->get_id(); ?> &middot; Tell us what arrived missing or damaged and we will arrange replacements.</p>
	<?php if ( $saved ) : ?>
		<div class="glow-cl-notice">Thank you! Your report was sent to the GLOW team. We will be in touch at <?php echo esc_html( wp_get_current_user()->user_email ); ?>.</div>
	<?php elseif ( $error ) : ?>
		<div class="glow-cl-notice is-error"><?php echo esc_html( $error ); ?></div>
	<?php elseif ( $claimed ) : ?>
		<div class="glow-cl-notice">A report was already submitted for this order:
<?php echo esc_html( $details ); ?></div>
	<?php endif; ?>

	<div class="glow-cl-card">
		<h3>Order summary</h3>
		<div class="glow-cl-meta">
			<div><b>Placed</b><?php echo esc_html( $order->get_date_created() ? $order->get_date_created()->date_i18n( 'F j, Y' ) : '' ); ?></div>
			<div><b>Status</b><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></div>
			<div><b>Order total</b><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></div>
			<div style="grid-column:1/-1"><b>Shipped to</b><?php echo wp_kses_post( $shipped ); ?></div>
		</div>
	</div>

	<form method="post" class="glow-cl-card">
		<?php wp_nonce_field( 'glow_claim_' . $order->get_id(), 'glow_claim_nonce' ); ?>
		<h3>Items in this order</h3>
		<div style="overflow-x:auto"><table>
			<thead><tr><th>Product</th><th>Ordered</th><th>Qty affected</th><th>Problem</th></tr></thead>
			<tbody>
			<?php foreach ( $order->get_items() as $item_id => $item ) : ?>
				<tr>
					<td><?php echo esc_html( trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( str_replace( array( '<br>', '<br/>', '<br />' ), ' ', $item->get_name() ) ) ) ) ); ?></td>
					<td><?php echo (int) $item->get_quantity(); ?></td>
					<td><input type="number" name="claim_qty[<?php echo (int) $item_id; ?>]" min="0" max="<?php echo (int) $item->get_quantity(); ?>" value="0" <?php disabled( $saved ); ?>></td>
					<td><select name="claim_issue[<?php echo (int) $item_id; ?>]" <?php disabled( $saved ); ?>><option value="missing">Missing</option><option value="damaged">Damaged</option><option value="wrong item">Wrong item</option></select></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table></div>
		<label for="claim_notes">Anything else we should know?</label>
		<textarea id="claim_notes" name="claim_notes" placeholder="e.g. Box 2 of 3 arrived crushed; 4 Vanilla candles broken." <?php disabled( $saved ); ?>></textarea>
		<?php if ( ! $saved ) : ?><button type="submit" class="glow-cl-btn" id="glow-cl-send">Send report</button><?php endif; ?>
	</form>
	</div></div>
	<script>
	/* Theme scripts hijack generic submit-button clicks on this page; submit the form directly. */
	(function(){
		var b = document.getElementById('glow-cl-send');
		if (!b) return;
		b.addEventListener('click', function (e) {
			e.preventDefault(); e.stopImmediatePropagation();
			var f = b.closest('form');
			if (f.reportValidity()) { b.disabled = true; b.textContent = 'Sending…'; HTMLFormElement.prototype.submit.call(f); }
		}, true);
	})();
	</script>
	<?php
	get_footer();
	exit;
}, 1 );
