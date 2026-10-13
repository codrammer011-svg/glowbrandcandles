<?php
/**
 * GLOW seller pages that the seller dashboard links to but which did not exist:
 *   /edit-seller-info/                         Edit My Info (name, email, phone, goal)
 *   /seller-upload-image-and-description/      Customize My Store (photo + message)
 *   /seller-report/                            My Sales Report
 *
 * Rendered as virtual pages (no WordPress page needed). Styled like the seller dashboard.
 * Admins can view a seller's pages with ?sid=USER_ID.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Which seller are we acting for? */
function glow_sp_seller_id() {
	if ( ! is_user_logged_in() ) {
		return 0;
	}
	if ( current_user_can( 'manage_options' ) && ! empty( $_GET['sid'] ) ) {
		return absint( $_GET['sid'] );
	}
	return get_current_user_id();
}

function glow_sp_meta_key( $key ) {
	$defaults = array(
		'fundraiser_id' => 'user_registration_seller_fundraiser',
		'goal'          => 'user_registration_seller_goal',
		'photo'         => 'user_registration_image_url_profile',
		'photo_alt'     => 'seller_profile_image',
		'description'   => 'description',
		'description_2' => 'seller_profile_description',
		'closeout_date' => 'user_close_out_date_inactive',
		'action_status' => 'user_action_status',
	);
	if ( function_exists( 'glow_sd_meta' ) ) {
		$k = glow_sd_meta( $key );
		if ( $k ) {
			return $k;
		}
	}
	return isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
}

function glow_sp_dashboard_url( $sid ) {
	$url = home_url( '/seller-dashboard/' );
	if ( current_user_can( 'manage_options' ) && $sid !== get_current_user_id() ) {
		$url = add_query_arg( 'sid', $sid, $url );
	}
	return $url;
}

function glow_sp_self_url( $sid ) {
	$url = home_url( add_query_arg( array(), wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) ) );
	if ( current_user_can( 'manage_options' ) && $sid !== get_current_user_id() ) {
		$url = add_query_arg( 'sid', $sid, $url );
	}
	return $url;
}

/* ------------------------------------------------------------------ */
/* Router                                                               */
/* ------------------------------------------------------------------ */
add_action( 'template_redirect', function () {
	$path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
	$pages = array(
		'edit-seller-info'                    => 'glow_sp_render_edit_info',
		'seller-upload-image-and-description' => 'glow_sp_render_customize',
		'seller-report'                       => 'glow_sp_render_report',
	);
	if ( ! isset( $pages[ $path ] ) ) {
		return;
	}
	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( wp_login_url( home_url( '/' . $path . '/' ) ) );
		exit;
	}
	$sid  = glow_sp_seller_id();
	$user = get_userdata( $sid );
	if ( ! $user || ( ! in_array( 'seller', (array) $user->roles, true ) && ! current_user_can( 'manage_options' ) ) ) {
		wp_safe_redirect( home_url( '/my-account/' ) );
		exit;
	}
	global $wp_query;
	$wp_query->is_404 = false;
	status_header( 200 );
	nocache_headers();
	call_user_func( $pages[ $path ], $sid );
	exit;
}, 5 );

/* ------------------------------------------------------------------ */
/* Shared chrome                                                        */
/* ------------------------------------------------------------------ */
function glow_sp_open( $sid, $title, $subtitle = '' ) {
	get_header();
	$name = trim( get_user_meta( $sid, 'first_name', true ) . ' ' . get_user_meta( $sid, 'last_name', true ) );
	if ( '' === $name ) {
		$u = get_userdata( $sid );
		$name = $u ? $u->display_name : '';
	}
	?>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
	<style>
	.glow-sp{--pink:#ec0f63;--ink:#181735;--muted:#6f6c85;--board:#f2f3f9;--line:#e8e8f0;--teal:#17b98a;font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:var(--ink);background:var(--board);padding:28px 18px 48px;-webkit-font-smoothing:antialiased}
	.glow-sp *{box-sizing:border-box}
	.glow-sp-wrap{max-width:860px;margin:0 auto}
	.glow-sp-back{display:inline-flex;align-items:center;gap:6px;font-size:14px;font-weight:600;color:#4a3fd0 !important;text-decoration:none;margin-bottom:14px}
	.glow-sp h1{margin:0 0 4px;font-size:26px;font-weight:800;letter-spacing:.01em}
	.glow-sp-sub{margin:0 0 20px;color:var(--muted);font-size:15px}
	.glow-sp-card{background:#fff;border:1px solid var(--line);border-radius:20px;box-shadow:0 2px 6px rgba(22,20,50,.05);padding:26px 28px;margin-bottom:18px}
	.glow-sp-card h3{margin:0 0 16px;font-size:16.5px;font-weight:800;letter-spacing:.02em;text-transform:uppercase}
	.glow-sp-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px 20px}
	.glow-sp-field{display:flex;flex-direction:column;gap:6px}
	.glow-sp-field.is-full{grid-column:1/-1}
	.glow-sp-field label{font-size:12.5px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;color:#8d8aa0}
	.glow-sp-field input,.glow-sp-field textarea{width:100%;font:inherit;font-size:15px;padding:11px 14px;border:1px solid #d9d7e6;border-radius:10px;background:#fff;color:var(--ink)}
	.glow-sp-field input:focus,.glow-sp-field textarea:focus{outline:none;border-color:var(--pink);box-shadow:0 0 0 3px rgba(236,15,99,.12)}
	.glow-sp-field textarea{min-height:120px;resize:vertical}
	.glow-sp-help{font-size:13px;color:var(--muted)}
	.glow-sp-actions{display:flex;align-items:center;gap:14px;margin-top:22px;flex-wrap:wrap}
	.glow-sp-btn{display:inline-flex;align-items:center;gap:8px;padding:12px 26px;border-radius:999px;background:var(--pink);color:#fff !important;font-weight:700;font-size:14px;letter-spacing:.03em;text-transform:uppercase;border:0;cursor:pointer;text-decoration:none !important}
	.glow-sp-btn:hover{filter:brightness(1.06)}
	.glow-sp-btn.is-ghost{background:#fff;color:var(--ink) !important;border:1.5px solid #d9d7e6}
	.glow-sp-notice{padding:12px 16px;border-radius:12px;font-size:14.5px;margin-bottom:18px;background:#eaf8f1;color:#0d6b50;border:1px solid #c6ecd9}
	.glow-sp-notice.is-error{background:#fff0f3;color:#9b1239;border-color:#f7c9d6}
	.glow-sp-photo{display:flex;align-items:center;gap:22px;flex-wrap:wrap}
	.glow-sp-avatar{width:150px;height:150px;border-radius:50%;object-fit:cover;border:4px solid #ffd3e0;background:#ffe1e8;flex:0 0 auto}
	.glow-sp-avatar.is-empty{display:flex;align-items:center;justify-content:center;color:#e05a86;font-size:13px;font-weight:600;text-align:center;padding:10px}
	.glow-sp-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:18px}
	.glow-sp-stat{border-radius:16px;padding:16px 18px;background:#fff;border:1px solid var(--line)}
	.glow-sp-stat b{display:block;font-size:24px;font-weight:800}
	.glow-sp-stat span{font-size:13px;color:var(--muted)}
	.glow-sp-stat.is-pink{background:#fff0f4}.glow-sp-stat.is-blue{background:#eef4ff}.glow-sp-stat.is-green{background:#ecf9f3}.glow-sp-stat.is-purple{background:#f1edff}
	.glow-sp-table-wrap{overflow-x:auto}
	.glow-sp-table{width:100%;border-collapse:collapse;font-size:14.5px}
	.glow-sp-table th{padding:10px 12px;text-align:left;font-size:12px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;color:#8d8aa0;border-bottom:1px solid var(--line);white-space:nowrap}
	.glow-sp-table td{padding:12px;border-bottom:1px solid #f0eff4;vertical-align:top}
	.glow-sp-table tr:hover td{background:#faf9fc}
	.glow-sp-tag{display:inline-block;padding:4px 11px;border-radius:999px;font-size:12.5px;font-weight:700;background:#f1eff6;color:#5d5a75;white-space:nowrap}
	.glow-sp-tag.is-completed{background:#eaf8f1;color:#0d6b50}.glow-sp-tag.is-processing{background:#f0edfd;color:#4030a8}
	.glow-sp-items{color:var(--muted);font-size:13px;margin-top:3px}
	.glow-sp-empty{margin:24px 0;text-align:center;color:#8d8aa0}
	@media(max-width:720px){.glow-sp-grid{grid-template-columns:1fr}.glow-sp-stats{grid-template-columns:1fr 1fr}.glow-sp-card{padding:20px 18px}}
	@media print{.glow-sp-back,.glow-sp-actions,header,footer,#wpadminbar{display:none !important}.glow-sp{background:#fff;padding:0}}
	</style>
	<div class="glow-sp"><div class="glow-sp-wrap">
	<a class="glow-sp-back" href="<?php echo esc_url( glow_sp_dashboard_url( $sid ) ); ?>">&larr; Back to my dashboard</a>
	<h1><?php echo esc_html( $title ); ?></h1>
	<?php if ( $subtitle ) : ?><p class="glow-sp-sub"><?php echo esc_html( $subtitle ); ?></p><?php endif; ?>
	<?php
	if ( current_user_can( 'manage_options' ) && $sid !== get_current_user_id() ) {
		echo '<div class="glow-sp-notice">Admin view &mdash; you are editing seller <b>' . esc_html( $name ) . '</b> (#' . (int) $sid . ').</div>';
	}
}

function glow_sp_close() {
	echo '</div></div>';
	get_footer();
}

function glow_sp_flash( $sid ) {
	if ( ! empty( $_GET['saved'] ) ) {
		delete_transient( 'glow_sd_stats_' . $sid );
		echo '<div class="glow-sp-notice">Saved! Your changes are live.</div>';
	}
	if ( ! empty( $_GET['err'] ) ) {
		echo '<div class="glow-sp-notice is-error">' . esc_html( sanitize_text_field( wp_unslash( $_GET['err'] ) ) ) . '</div>';
	}
}

/* ------------------------------------------------------------------ */
/* 1. Edit My Info                                                      */
/* ------------------------------------------------------------------ */
function glow_sp_render_edit_info( $sid ) {
	if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['glow_sp_action'] ) && 'edit_info' === $_POST['glow_sp_action'] ) {
		if ( ! wp_verify_nonce( $_POST['_wpnonce'] ?? '', 'glow_sp_edit_info_' . $sid ) ) {
			wp_safe_redirect( add_query_arg( 'err', 'Session expired, please try again.', glow_sp_self_url( $sid ) ) );
			exit;
		}
		$first = sanitize_text_field( wp_unslash( $_POST['first_name'] ?? '' ) );
		$last  = sanitize_text_field( wp_unslash( $_POST['last_name'] ?? '' ) );
		$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
		$phone = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
		$goal  = preg_replace( '/[^0-9.]/', '', (string) ( $_POST['goal'] ?? '' ) );

		if ( $email && ! is_email( $email ) ) {
			wp_safe_redirect( add_query_arg( 'err', 'That email address does not look right.', glow_sp_self_url( $sid ) ) );
			exit;
		}
		$exists = $email ? email_exists( $email ) : false;
		if ( $exists && (int) $exists !== (int) $sid ) {
			wp_safe_redirect( add_query_arg( 'err', 'That email address is already used by another account.', glow_sp_self_url( $sid ) ) );
			exit;
		}
		$args = array( 'ID' => $sid, 'first_name' => $first, 'last_name' => $last, 'display_name' => trim( $first . ' ' . $last ) );
		if ( $email ) {
			$args['user_email'] = $email;
		}
		wp_update_user( $args );
		update_user_meta( $sid, 'billing_first_name', $first );
		update_user_meta( $sid, 'billing_last_name', $last );
		update_user_meta( $sid, 'billing_phone', $phone );
		update_user_meta( $sid, 'user_registration_phone', $phone );
		update_user_meta( $sid, glow_sp_meta_key( 'goal' ), $goal );
		update_user_meta( $sid, 'seller_goal', $goal );
		wp_safe_redirect( add_query_arg( 'saved', 1, glow_sp_self_url( $sid ) ) );
		exit;
	}

	$u     = get_userdata( $sid );
	$phone = get_user_meta( $sid, 'billing_phone', true );
	if ( ! $phone ) {
		$phone = get_user_meta( $sid, 'user_registration_phone', true );
	}
	$goal = preg_replace( '/[^0-9.]/', '', (string) get_user_meta( $sid, glow_sp_meta_key( 'goal' ), true ) );
	$fid  = absint( get_user_meta( $sid, glow_sp_meta_key( 'fundraiser_id' ), true ) );
	$chair = $fid ? get_userdata( $fid ) : null;

	glow_sp_open( $sid, 'Edit My Info', 'Update your name, contact details and sales goal.' );
	glow_sp_flash( $sid );
	?>
	<form method="post" class="glow-sp-card">
		<h3>My details</h3>
		<?php wp_nonce_field( 'glow_sp_edit_info_' . $sid ); ?>
		<input type="hidden" name="glow_sp_action" value="edit_info">
		<div class="glow-sp-grid">
			<div class="glow-sp-field"><label for="first_name">First name</label><input id="first_name" name="first_name" type="text" required value="<?php echo esc_attr( $u->first_name ); ?>"></div>
			<div class="glow-sp-field"><label for="last_name">Last name</label><input id="last_name" name="last_name" type="text" value="<?php echo esc_attr( $u->last_name ); ?>"></div>
			<div class="glow-sp-field"><label for="email">Email</label><input id="email" name="email" type="email" value="<?php echo esc_attr( $u->user_email ); ?>"></div>
			<div class="glow-sp-field"><label for="phone">Mobile phone</label><input id="phone" name="phone" type="tel" value="<?php echo esc_attr( $phone ); ?>"></div>
			<div class="glow-sp-field"><label for="goal">My sales goal ($)</label><input id="goal" name="goal" type="number" min="0" step="1" value="<?php echo esc_attr( $goal ); ?>"><span class="glow-sp-help">Shown on your dashboard progress ring.</span></div>
			<div class="glow-sp-field"><label>Seller code</label><input type="text" readonly value="<?php echo esc_attr( $sid ); ?>"><span class="glow-sp-help">Fundraiser: <?php echo $chair ? esc_html( '#' . $fid . ' — ' . trim( $chair->first_name . ' ' . $chair->last_name ) ) : 'not linked yet'; ?></span></div>
		</div>
		<div class="glow-sp-actions">
			<button type="submit" class="glow-sp-btn">Save changes</button>
			<a class="glow-sp-btn is-ghost" href="<?php echo esc_url( glow_sp_dashboard_url( $sid ) ); ?>">Cancel</a>
			<a class="glow-sp-help" href="<?php echo esc_url( home_url( '/my-account/lost-password/' ) ); ?>">Need to change your password?</a>
		</div>
	</form>
	<?php
	glow_sp_close();
}

/* ------------------------------------------------------------------ */
/* 2. Customize My Store                                                */
/* ------------------------------------------------------------------ */
function glow_sp_render_customize( $sid ) {
	if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['glow_sp_action'] ) && 'customize' === $_POST['glow_sp_action'] ) {
		if ( ! wp_verify_nonce( $_POST['_wpnonce'] ?? '', 'glow_sp_customize_' . $sid ) ) {
			wp_safe_redirect( add_query_arg( 'err', 'Session expired, please try again.', glow_sp_self_url( $sid ) ) );
			exit;
		}
		$desc = sanitize_textarea_field( wp_unslash( $_POST['description'] ?? '' ) );
		update_user_meta( $sid, glow_sp_meta_key( 'description' ), $desc );
		update_user_meta( $sid, glow_sp_meta_key( 'description_2' ), $desc );

		if ( ! empty( $_POST['remove_photo'] ) ) {
			delete_user_meta( $sid, glow_sp_meta_key( 'photo' ) );
			delete_user_meta( $sid, glow_sp_meta_key( 'photo_alt' ) );
		} elseif ( ! empty( $_FILES['photo']['name'] ) ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
			$check = wp_check_filetype( $_FILES['photo']['name'] );
			if ( ! in_array( $check['ext'], array( 'jpg', 'jpeg', 'png', 'gif', 'webp' ), true ) ) {
				wp_safe_redirect( add_query_arg( 'err', 'Please upload a JPG, PNG, GIF or WebP image.', glow_sp_self_url( $sid ) ) );
				exit;
			}
			if ( $_FILES['photo']['size'] > 8 * MB_IN_BYTES ) {
				wp_safe_redirect( add_query_arg( 'err', 'That image is too large (max 8 MB).', glow_sp_self_url( $sid ) ) );
				exit;
			}
			$attach_id = media_handle_upload( 'photo', 0 );
			if ( is_wp_error( $attach_id ) ) {
				wp_safe_redirect( add_query_arg( 'err', $attach_id->get_error_message(), glow_sp_self_url( $sid ) ) );
				exit;
			}
			$url = wp_get_attachment_url( $attach_id );
			update_user_meta( $sid, glow_sp_meta_key( 'photo' ), $url );
			update_user_meta( $sid, glow_sp_meta_key( 'photo_alt' ), $url );
			update_user_meta( $sid, 'image_url_profile', $url );
		}
		wp_safe_redirect( add_query_arg( 'saved', 1, glow_sp_self_url( $sid ) ) );
		exit;
	}

	$photo = get_user_meta( $sid, glow_sp_meta_key( 'photo' ), true );
	if ( ! $photo ) {
		$photo = get_user_meta( $sid, glow_sp_meta_key( 'photo_alt' ), true );
	}
	$desc = get_user_meta( $sid, glow_sp_meta_key( 'description' ), true );
	if ( ! $desc ) {
		$desc = get_user_meta( $sid, glow_sp_meta_key( 'description_2' ), true );
	}
	$u = get_userdata( $sid );

	glow_sp_open( $sid, 'Customize My Store', 'Add a photo and a short message so supporters know who they are helping.' );
	glow_sp_flash( $sid );
	?>
	<form method="post" enctype="multipart/form-data" class="glow-sp-card">
		<h3>My photo</h3>
		<?php wp_nonce_field( 'glow_sp_customize_' . $sid ); ?>
		<input type="hidden" name="glow_sp_action" value="customize">
		<div class="glow-sp-photo">
			<?php if ( $photo ) : ?>
				<img class="glow-sp-avatar" id="glow-sp-preview" src="<?php echo esc_url( $photo ); ?>" alt="">
			<?php else : ?>
				<div class="glow-sp-avatar is-empty" id="glow-sp-preview">No photo yet</div>
			<?php endif; ?>
			<div class="glow-sp-field" style="flex:1 1 260px">
				<label for="photo">Upload a new photo</label>
				<input id="photo" name="photo" type="file" accept="image/*">
				<span class="glow-sp-help">Square photos look best. JPG or PNG, up to 8 MB.</span>
				<?php if ( $photo ) : ?>
					<label style="text-transform:none;letter-spacing:0;font-weight:500;color:var(--muted);display:flex;align-items:center;gap:8px;margin-top:6px"><input type="checkbox" name="remove_photo" value="1" style="width:auto"> Remove current photo</label>
				<?php endif; ?>
			</div>
		</div>
		<h3 style="margin-top:28px">My message</h3>
		<div class="glow-sp-field">
			<label for="description">Message to supporters</label>
			<textarea id="description" name="description" maxlength="600" placeholder="Hi! I'm <?php echo esc_attr( $u->first_name ); ?> and I'm raising money for our group. Every candle you buy helps us reach our goal — thank you!"><?php echo esc_textarea( $desc ); ?></textarea>
			<span class="glow-sp-help">Shown on your store page. Up to 600 characters.</span>
		</div>
		<div class="glow-sp-actions">
			<button type="submit" class="glow-sp-btn">Save my store</button>
			<a class="glow-sp-btn is-ghost" href="<?php echo esc_url( glow_sp_dashboard_url( $sid ) ); ?>">Cancel</a>
		</div>
	</form>
	<script>
	(function(){var i=document.getElementById('photo'),p=document.getElementById('glow-sp-preview');if(!i||!p)return;i.addEventListener('change',function(){var f=i.files&&i.files[0];if(!f)return;var r=new FileReader();r.onload=function(e){if(p.tagName==='IMG'){p.src=e.target.result}else{var img=document.createElement('img');img.className='glow-sp-avatar';img.id='glow-sp-preview';img.src=e.target.result;p.replaceWith(img);p=img}};r.readAsDataURL(f)});})();
	</script>
	<?php
	glow_sp_close();
}

/* ------------------------------------------------------------------ */
/* 3. My Sales Report                                                   */
/* ------------------------------------------------------------------ */
function glow_sp_seller_orders( $sid ) {
	$fid      = absint( get_user_meta( $sid, glow_sp_meta_key( 'fundraiser_id' ), true ) );
	$first    = trim( get_user_meta( $sid, 'first_name', true ) );
	$last     = trim( get_user_meta( $sid, 'last_name', true ) );
	$key      = strtolower( trim( $first . ' ' . $last ) );
	$statuses = array( 'processing', 'completed', 'on-hold', 'waiting-call' );

	$mine = wc_get_orders( array( 'limit' => -1, 'status' => $statuses, 'meta_key' => 'wc_seller_field', 'meta_value' => $sid, 'orderby' => 'date', 'order' => 'DESC' ) );
	$manual = array();
	if ( $fid && $key ) {
		$manual = wc_get_orders( array(
			'limit' => -1, 'status' => $statuses, 'orderby' => 'date', 'order' => 'DESC',
			'meta_query' => array( 'relation' => 'AND',
				array( 'key' => 'fundraiser_field', 'value' => $fid ),
				array( 'key' => 'wc_seller_field', 'value' => 'custom' ),
			),
		) );
	}
	$closeout   = $fid ? get_user_meta( $fid, glow_sp_meta_key( 'closeout_date' ), true ) : '';
	$was_closed = $fid && ( 2 == get_user_meta( $fid, glow_sp_meta_key( 'action_status' ), true ) );

	$rows = array();
	$seen = array();
	foreach ( array( $mine, $manual ) as $i => $orders ) {
		foreach ( $orders as $order ) {
			if ( 1 === $i ) {
				$typed = strtolower( trim( (string) $order->get_meta( 'wc_seller_field_custom' ) ) );
				if ( $typed !== $key ) {
					continue;
				}
			}
			$id = $order->get_id();
			if ( isset( $seen[ $id ] ) ) {
				continue;
			}
			$seen[ $id ] = true;
			$created = $order->get_date_created();
			$ts      = $created ? $created->getTimestamp() : 0;
			if ( $was_closed && $closeout && $ts && $ts > strtotime( $closeout ) ) {
				continue;
			}
			$gross = max( 0, (float) $order->get_subtotal() - (float) $order->get_total_discount() );
			$qty = 0; $items = array();
			foreach ( $order->get_items() as $item ) {
				$qty += (int) $item->get_quantity();
				$items[] = $item->get_quantity() . ' × ' . trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( str_replace( array( '<br>', '<br/>', '<br />' ), ' ', $item->get_name() ) ) ) );
			}
			$type = $order->get_meta( 'wc_order_type' );
			$rate = ( $qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ) ? 50 : 40;
			$rows[] = array(
				'id'       => $id,
				'date'     => $ts ? date_i18n( 'M j, Y', $ts ) : '',
				'ts'       => $ts,
				'customer' => trim( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ),
				'type'     => ( 'ship-to-school' === $type ) ? 'Ship to group' : ( 'Catlog' === $type ? 'Brochure' : 'Online' ),
				'status'   => wc_get_order_status_name( $order->get_status() ),
				'raw'      => $order->get_status(),
				'qty'      => $qty,
				'items'    => $items,
				'gross'    => $gross,
				'profit'   => $gross * $rate / 100,
			);
		}
	}
	usort( $rows, function ( $a, $b ) { return $b['ts'] <=> $a['ts']; } );
	return $rows;
}

function glow_sp_render_report( $sid ) {
	$rows   = glow_sp_seller_orders( $sid );
	$total  = 0; $profit = 0; $qty = 0; $buyers = array();
	foreach ( $rows as $r ) {
		$total += $r['gross']; $profit += $r['profit']; $qty += $r['qty'];
		if ( $r['customer'] ) { $buyers[ strtolower( $r['customer'] ) ] = ( $buyers[ strtolower( $r['customer'] ) ] ?? 0 ) + $r['gross']; }
	}
	arsort( $buyers );
	$top = $buyers ? array_key_first( $buyers ) : '';

	glow_sp_open( $sid, 'My Sales Report', 'Every order credited to you, with what it raised for your group.' );
	?>
	<div class="glow-sp-stats">
		<div class="glow-sp-stat is-pink"><b>$<?php echo esc_html( number_format( $total, 2 ) ); ?></b><span>Total sales</span></div>
		<div class="glow-sp-stat is-green"><b>$<?php echo esc_html( number_format( $profit, 2 ) ); ?></b><span>Raised for your group</span></div>
		<div class="glow-sp-stat is-blue"><b><?php echo count( $rows ); ?></b><span>Orders &middot; <?php echo (int) $qty; ?> items</span></div>
		<div class="glow-sp-stat is-purple"><b style="font-size:17px"><?php echo $top ? esc_html( ucwords( $top ) ) : '&mdash;'; ?></b><span>Top customer<?php echo $top ? ' &middot; $' . esc_html( number_format( $buyers[ $top ], 0 ) ) : ''; ?></span></div>
	</div>
	<div class="glow-sp-card">
		<h3>Orders</h3>
		<?php if ( ! $rows ) : ?>
			<p class="glow-sp-empty">No orders yet. Share your link to get your first sale!</p>
		<?php else : ?>
		<div class="glow-sp-table-wrap"><table class="glow-sp-table">
			<thead><tr><th>Order</th><th>Date</th><th>Customer</th><th>Items</th><th>Type</th><th>Status</th><th style="text-align:right">Sale</th><th style="text-align:right">Raised</th></tr></thead>
			<tbody>
			<?php foreach ( $rows as $r ) : ?>
			<tr>
				<td>#<?php echo (int) $r['id']; ?></td>
				<td><?php echo esc_html( $r['date'] ); ?></td>
				<td><?php echo esc_html( $r['customer'] ? $r['customer'] : 'A supporter' ); ?></td>
				<td><?php echo (int) $r['qty']; ?><div class="glow-sp-items"><?php echo esc_html( implode( ', ', array_slice( $r['items'], 0, 3 ) ) ); echo count( $r['items'] ) > 3 ? ' …' : ''; ?></div></td>
				<td><?php echo esc_html( $r['type'] ); ?></td>
				<td><span class="glow-sp-tag is-<?php echo esc_attr( $r['raw'] ); ?>"><?php echo esc_html( $r['status'] ); ?></span></td>
				<td style="text-align:right">$<?php echo esc_html( number_format( $r['gross'], 2 ) ); ?></td>
				<td style="text-align:right">$<?php echo esc_html( number_format( $r['profit'], 2 ) ); ?></td>
			</tr>
			<?php endforeach; ?>
			</tbody>
		</table></div>
		<?php endif; ?>
		<div class="glow-sp-actions">
			<button type="button" class="glow-sp-btn" onclick="window.print()">Print / save as PDF</button>
			<a class="glow-sp-btn is-ghost" href="<?php echo esc_url( glow_sp_dashboard_url( $sid ) ); ?>">Back to dashboard</a>
		</div>
	</div>
	<?php
	glow_sp_close();
}
