<?php
/*
	Template Name: Edit Seller Info
*/
get_header();

/* -------------------------------------------------------------------------
   Resolve seller id (links use ?seller_ID= ; accept lowercase too) + guard.
   ------------------------------------------------------------------------- */
$u_id = 0;
if ( isset( $_GET['seller_ID'] ) ) {
	$u_id = absint( $_GET['seller_ID'] );
} elseif ( isset( $_GET['seller_id'] ) ) {
	$u_id = absint( $_GET['seller_id'] );
}

if ( ! is_user_logged_in() ) {
	wp_safe_redirect( wp_login_url( home_url( '/edit-seller-info/' ) ) );
	exit;
}
$current_id = get_current_user_id();
$is_admin   = current_user_can( 'manage_options' );
// A seller may only edit their own info; admins may edit anyone.
if ( ! $u_id || ( $u_id !== $current_id && ! $is_admin ) ) {
	$u_id = $current_id;
}

$notice       = '';
$notice_type  = '';

/* -------------------------------------------------------------------------
   Save
   ------------------------------------------------------------------------- */
if ( isset( $_POST['glow_edit_seller'] ) && wp_verify_nonce( $_POST['_glow_nonce'] ?? '', 'glow_edit_seller_' . $u_id ) ) {

	$fn      = sanitize_text_field( $_POST['f_name'] ?? '' );
	$ln      = sanitize_text_field( $_POST['l_name'] ?? '' );
	$email   = sanitize_email( $_POST['user_email'] ?? '' );
	$phone   = sanitize_text_field( $_POST['phone'] ?? '' );
	$fid     = preg_replace( '/\D/', '', $_POST['f_id'] ?? '' );
	$message = sanitize_textarea_field( $_POST['message'] ?? '' );

	// Name
	update_user_meta( $u_id, 'first_name', $fn );
	update_user_meta( $u_id, 'last_name', $ln );

	// Email (only if valid and not taken by someone else)
	$email_ok = true;
	if ( $email && is_email( $email ) ) {
		$owner = email_exists( $email );
		if ( ! $owner || (int) $owner === $u_id ) {
			wp_update_user( array( 'ID' => $u_id, 'user_email' => $email ) );
		} else {
			$email_ok = false;
		}
	}
	wp_update_user( array( 'ID' => $u_id, 'first_name' => $fn, 'last_name' => $ln, 'display_name' => trim( $fn . ' ' . $ln ) ) );

	// Phone
	update_user_meta( $u_id, 'billing_phone', $phone );

	// Fundraiser ID (mirror the keys the signup writes)
	if ( '' !== $fid ) {
		update_user_meta( $u_id, 'user_registration_seller_fundraiser', $fid );
		update_user_meta( $u_id, 'seller_fundraiser', $fid );
		update_user_meta( $u_id, 'fundraiser_id', $fid );
	}

	// Message / description
	update_user_meta( $u_id, 'description', $message );

	// Photo upload (optional)
	if ( ! empty( $_FILES['seller_photo']['name'] ) ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		$att_id = media_handle_upload( 'seller_photo', 0 );
		if ( ! is_wp_error( $att_id ) ) {
			$url = wp_get_attachment_url( $att_id );
			update_user_meta( $u_id, 'user_registration_profile_pic_url', $att_id );
			update_user_meta( $u_id, 'user_registration_image_url_profile', esc_url_raw( $url ) );
			update_user_meta( $u_id, 'image_url_profile', esc_url_raw( $url ) );
			update_user_meta( $u_id, 'fund_profile_image', esc_url_raw( $url ) );
		}
	}

	$notice      = $email_ok ? 'Your details were saved successfully.' : 'Details saved, but that email is already in use — email was not changed.';
	$notice_type = $email_ok ? 'ok' : 'warn';
}

/* -------------------------------------------------------------------------
   Load current values
   ------------------------------------------------------------------------- */
$user      = get_userdata( $u_id );
$f         = get_user_meta( $u_id, 'first_name', true );
$l         = get_user_meta( $u_id, 'last_name', true );
$email_val = $user ? $user->user_email : '';
$phone_val = get_user_meta( $u_id, 'billing_phone', true );
$f_id      = get_user_meta( $u_id, 'user_registration_seller_fundraiser', true );
$msg_val   = get_user_meta( $u_id, 'description', true );

$photo_url = '';
$photo_att = get_user_meta( $u_id, 'user_registration_profile_pic_url', true );
if ( $photo_att && is_numeric( $photo_att ) ) {
	$photo_url = wp_get_attachment_url( (int) $photo_att );
}
if ( ! $photo_url ) {
	$photo_url = get_user_meta( $u_id, 'user_registration_image_url_profile', true );
}
if ( ! $photo_url ) {
	$photo_url = get_user_meta( $u_id, 'image_url_profile', true );
}
?>

<style>
.glow-esi-wrap{max-width:640px;margin:40px auto;padding:0 16px;box-sizing:border-box;}
.glow-esi-card{background:#fff;border:1px solid #ececf1;border-radius:16px;
	box-shadow:0 10px 30px rgba(20,20,43,.06);padding:32px;}
.glow-esi-card h1{font-size:24px;line-height:1.2;margin:0 0 4px;color:#1a1a2e;font-weight:700;}
.glow-esi-sub{margin:0 0 24px;color:#8a8aa3;font-size:14px;}
.glow-esi-notice{border-radius:10px;padding:12px 16px;margin:0 0 20px;font-size:14px;font-weight:500;}
.glow-esi-notice.ok{background:#e9f9f1;color:#0f8a5f;border:1px solid #bfe9d5;}
.glow-esi-notice.warn{background:#fff6e6;color:#a66b00;border:1px solid #f3dca8;}
.glow-esi-photo{display:flex;align-items:center;gap:18px;margin-bottom:24px;}
.glow-esi-avatar{width:84px;height:84px;border-radius:50%;object-fit:cover;background:#f1f1f6;
	border:2px solid #f1f1f6;flex:0 0 auto;}
.glow-esi-avatar.ph{display:flex;align-items:center;justify-content:center;color:#b9b9cc;font-size:28px;font-weight:700;}
.glow-esi-filelabel{font-size:13px;color:#6b6b85;margin-bottom:6px;display:block;font-weight:600;}
.glow-esi-field{margin-bottom:18px;}
.glow-esi-field label{display:block;font-size:13px;font-weight:600;color:#44445c;margin-bottom:6px;}
.glow-esi-row{display:flex;gap:14px;}
.glow-esi-row .glow-esi-field{flex:1;}
.glow-esi-card input[type=text],
.glow-esi-card input[type=email],
.glow-esi-card input[type=tel],
.glow-esi-card input[type=file],
.glow-esi-card textarea{width:100%;box-sizing:border-box;border:1px solid #e0e0ea;border-radius:10px;
	padding:11px 14px;font-size:15px;color:#1a1a2e;background:#fcfcfe;transition:border .15s,box-shadow .15s;}
.glow-esi-card input:focus,.glow-esi-card textarea:focus{outline:none;border-color:#22c993;
	box-shadow:0 0 0 3px rgba(34,201,147,.15);background:#fff;}
.glow-esi-card textarea{min-height:110px;resize:vertical;}
.glow-esi-save{width:100%;margin-top:8px;border:none;cursor:pointer;border-radius:10px;
	padding:14px;font-size:16px;font-weight:700;color:#fff;background:#22c993;
	transition:background .2s;}
.glow-esi-save:hover{background:#1aa87b;}
@media(max-width:520px){.glow-esi-row{flex-direction:column;gap:0;}.glow-esi-card{padding:22px;}}
</style>

<div class="glow-esi-wrap">
	<form method="post" action="" enctype="multipart/form-data" class="glow-esi-card">
		<h1>Edit Seller Info</h1>
		<p class="glow-esi-sub">Update the details for your seller account.</p>

		<?php if ( $notice ) : ?>
			<div class="glow-esi-notice <?php echo esc_attr( $notice_type ); ?>"><?php echo esc_html( $notice ); ?></div>
		<?php endif; ?>

		<div class="glow-esi-photo">
			<?php if ( $photo_url ) : ?>
				<img class="glow-esi-avatar" src="<?php echo esc_url( $photo_url ); ?>" alt="Seller photo">
			<?php else : ?>
				<span class="glow-esi-avatar ph"><?php echo esc_html( strtoupper( substr( (string) $f, 0, 1 ) ) ); ?></span>
			<?php endif; ?>
			<div style="flex:1;">
				<span class="glow-esi-filelabel">Photo</span>
				<input type="file" name="seller_photo" accept="image/*">
			</div>
		</div>

		<div class="glow-esi-row">
			<div class="glow-esi-field">
				<label for="f_name">Seller Name</label>
				<input id="f_name" type="text" name="f_name" placeholder="First name" value="<?php echo esc_attr( $f ); ?>">
			</div>
			<div class="glow-esi-field">
				<label for="l_name">&nbsp;</label>
				<input id="l_name" type="text" name="l_name" placeholder="Last name" value="<?php echo esc_attr( $l ); ?>">
			</div>
		</div>

		<div class="glow-esi-field">
			<label for="user_email">Email</label>
			<input id="user_email" type="email" name="user_email" placeholder="you@example.com" value="<?php echo esc_attr( $email_val ); ?>">
		</div>

		<div class="glow-esi-field">
			<label for="phone">Phone Number</label>
			<input id="phone" type="tel" name="phone" placeholder="Phone number" value="<?php echo esc_attr( $phone_val ); ?>">
		</div>

		<div class="glow-esi-field">
			<label for="f_id">Fundraiser ID</label>
			<input id="f_id" type="text" name="f_id" placeholder="6-digit fundraiser ID" value="<?php echo esc_attr( $f_id ); ?>">
		</div>

		<div class="glow-esi-field">
			<label for="message">Message</label>
			<textarea id="message" name="message" placeholder="A short message shown on your seller profile"><?php echo esc_textarea( $msg_val ); ?></textarea>
		</div>

		<?php wp_nonce_field( 'glow_edit_seller_' . $u_id, '_glow_nonce' ); ?>
		<button type="submit" name="glow_edit_seller" value="1" class="glow-esi-save">Save Changes</button>
	</form>
</div>

<?php
get_footer();
