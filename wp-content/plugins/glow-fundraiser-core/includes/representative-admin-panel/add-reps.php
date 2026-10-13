<?php
/**
 * Add New Representative — admin form.
 *
 * Rebuilt from the old theme's representative-admin-panel/add-reps.php (which was
 * never migrated into the plugin, so the "Add New Reps" page rendered blank).
 * Keeps the original behaviour — full rep fields, the same user meta, and the
 * payment_history post + um_unique_payment_id that the rep payment views rely on
 * — while adding a nonce, sanitisation and inline notices.
 *
 * Rendered by custom_add_new_reps_submenu_page_callback() at
 * admin.php?page=new_user_custom-submenu-page (parent menu: representative).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have permission to add representatives.', 'glow-fundraiser-core' ) );
}

$glow_rep_notice = '';
$glow_rep_error  = '';

// Preserve submitted values on validation error.
$f = array(
	'firstname'      => '',
	'lastname'       => '',
	'email'          => '',
	'username'       => '',
	'reps_phone'     => '',
	'reps_company'   => '',
	'reps_address'   => '',
	'reps_city'      => '',
	'reps_country'   => '',
	'reps_commission'=> '',
	'sex'            => '',
);

if ( isset( $_POST['glow_add_rep_submit'] ) ) {

	if ( ! isset( $_POST['glow_add_rep_nonce'] ) || ! wp_verify_nonce( $_POST['glow_add_rep_nonce'], 'glow_add_rep' ) ) {
		$glow_rep_error = 'Security check failed. Please try again.';
	} else {
		foreach ( $f as $k => $_ ) {
			$f[ $k ] = sanitize_text_field( wp_unslash( $_POST[ $k ] ?? '' ) );
		}
		$f['email'] = sanitize_email( $f['email'] );
		$username   = $f['username'] !== '' ? sanitize_user( $f['username'], true ) : sanitize_user( $f['email'], true );
		$password   = (string) ( $_POST['password'] ?? '' );
		$password2  = (string) ( $_POST['password_confirmation'] ?? '' );

		if ( '' === $f['firstname'] || '' === $f['lastname'] ) {
			$glow_rep_error = 'First name and last name are required.';
		} elseif ( ! is_email( $f['email'] ) ) {
			$glow_rep_error = 'Please enter a valid email address.';
		} elseif ( email_exists( $f['email'] ) ) {
			$glow_rep_error = 'A user with that email already exists.';
		} elseif ( username_exists( $username ) ) {
			$glow_rep_error = 'That username is already taken. Choose another.';
		} elseif ( '' !== $password && strlen( $password ) < 6 ) {
			$glow_rep_error = 'Password must be at least six characters.';
		} elseif ( '' !== $password && $password !== $password2 ) {
			$glow_rep_error = 'Passwords do not match.';
		} else {
			if ( '' === $password ) {
				$password = wp_generate_password( 12, true );
			}

			$user_id = wp_insert_user(
				array(
					'user_login'   => $username,
					'user_pass'    => $password,
					'user_email'   => $f['email'],
					'first_name'   => $f['firstname'],
					'last_name'    => $f['lastname'],
					'display_name' => trim( $f['firstname'] . ' ' . $f['lastname'] ),
					'role'         => 'representative',
				)
			);

			if ( is_wp_error( $user_id ) ) {
				$glow_rep_error = 'Could not create representative: ' . esc_html( $user_id->get_error_message() );
			} else {
				// Same meta keys the original add-reps.php saved.
				$meta = array(
					'phone'             => $f['reps_phone'],
					'sex'               => $f['sex'],
					'billing_company'   => $f['reps_company'],
					'billing_address_1' => $f['reps_address'],
					'billing_city'      => $f['reps_city'],
					'billing_country'   => $f['reps_country'],
					'reps_commission'   => $f['reps_commission'],
					'user_status'       => '1',
					'user_action_status'=> '1',
				);
				foreach ( $meta as $key => $val ) {
					update_user_meta( $user_id, $key, $val );
				}

				// Payment history post + um_unique_payment_id — required by the rep
				// payment views in representative-operation.php.
				$post_id = wp_insert_post(
					array(
						'post_title'  => 'Payment #' . $user_id,
						'post_type'   => 'payment_history',
						'post_status' => 'publish',
						'post_author' => $user_id,
					),
					true
				);
				if ( $post_id && ! is_wp_error( $post_id ) ) {
					update_post_meta( $post_id, 'payment_id', $post_id );
					update_user_meta( $user_id, 'um_unique_payment_id', $post_id );
				}

				// Optionally email the new representative their login details.
				if ( isset( $_POST['rep_send_email'] ) ) {
					$subject = 'Your Glow Representative account';
					$body    = 'Hi ' . $f['firstname'] . ",\n\n"
						. 'A representative account has been created for you at ' . get_bloginfo( 'name' ) . ".\n\n"
						. 'Username: ' . $username . "\n"
						. 'Password: ' . $password . "\n"
						. 'Login: ' . wp_login_url() . "\n\n"
						. 'Please log in and change your password.';
					$headers = array();
					if ( defined( 'GLOW_FROM_EMAIL' ) ) {
						$headers[] = 'From: ' . GLOW_FROM_EMAIL;
					}
					wp_mail( $f['email'], $subject, $body, $headers );
				}

				$glow_rep_notice = 'Representative <strong>' . esc_html( trim( $f['firstname'] . ' ' . $f['lastname'] ) )
					. '</strong> (ID ' . (int) $user_id . ') created successfully.';

				// Clear the form on success.
				foreach ( $f as $k => $_ ) {
					$f[ $k ] = '';
				}
			}
		}
	}
}
?>
<div class="wrap">
	<h1>Add New Representative</h1>

	<?php if ( $glow_rep_notice ) : ?>
		<div class="notice notice-success is-dismissible"><p><?php echo wp_kses_post( $glow_rep_notice ); ?></p></div>
	<?php endif; ?>
	<?php if ( $glow_rep_error ) : ?>
		<div class="notice notice-error is-dismissible"><p><?php echo esc_html( $glow_rep_error ); ?></p></div>
	<?php endif; ?>

	<form method="post" action="">
		<?php wp_nonce_field( 'glow_add_rep', 'glow_add_rep_nonce' ); ?>
		<table class="form-table" role="presentation">
			<tr>
				<th><label for="firstname">First Name <span style="color:#d63638">*</span></label></th>
				<td><input name="firstname" id="firstname" type="text" class="regular-text" value="<?php echo esc_attr( $f['firstname'] ); ?>" required></td>
			</tr>
			<tr>
				<th><label for="lastname">Last Name <span style="color:#d63638">*</span></label></th>
				<td><input name="lastname" id="lastname" type="text" class="regular-text" value="<?php echo esc_attr( $f['lastname'] ); ?>" required></td>
			</tr>
			<tr>
				<th><label for="email">Email <span style="color:#d63638">*</span></label></th>
				<td><input name="email" id="email" type="email" class="regular-text" value="<?php echo esc_attr( $f['email'] ); ?>" required></td>
			</tr>
			<tr>
				<th><label for="username">Username</label></th>
				<td>
					<input name="username" id="username" type="text" class="regular-text" value="<?php echo esc_attr( $f['username'] ); ?>">
					<p class="description">Leave blank to use the email address as the username.</p>
				</td>
			</tr>
			<tr>
				<th><label for="password">Password</label></th>
				<td>
					<input name="password" id="password" type="text" class="regular-text" autocomplete="off">
					<p class="description">Leave blank to auto-generate a strong password. Minimum 6 characters.</p>
				</td>
			</tr>
			<tr>
				<th><label for="password_confirmation">Confirm Password</label></th>
				<td><input name="password_confirmation" id="password_confirmation" type="text" class="regular-text" autocomplete="off"></td>
			</tr>
			<tr>
				<th><label for="reps_phone">Phone</label></th>
				<td><input name="reps_phone" id="reps_phone" type="text" class="regular-text" value="<?php echo esc_attr( $f['reps_phone'] ); ?>"></td>
			</tr>
			<tr>
				<th><label for="reps_company">Company</label></th>
				<td><input name="reps_company" id="reps_company" type="text" class="regular-text" value="<?php echo esc_attr( $f['reps_company'] ); ?>"></td>
			</tr>
			<tr>
				<th><label for="reps_address">Address</label></th>
				<td><input name="reps_address" id="reps_address" type="text" class="regular-text" value="<?php echo esc_attr( $f['reps_address'] ); ?>"></td>
			</tr>
			<tr>
				<th><label for="reps_city">City</label></th>
				<td><input name="reps_city" id="reps_city" type="text" class="regular-text" value="<?php echo esc_attr( $f['reps_city'] ); ?>"></td>
			</tr>
			<tr>
				<th><label for="reps_country">Country</label></th>
				<td><input name="reps_country" id="reps_country" type="text" class="regular-text" value="<?php echo esc_attr( $f['reps_country'] ); ?>"></td>
			</tr>
			<tr>
				<th><label for="reps_commission">Commission (%)</label></th>
				<td><input name="reps_commission" id="reps_commission" type="number" step="any" class="regular-text" value="<?php echo esc_attr( $f['reps_commission'] ); ?>"></td>
			</tr>
			<tr>
				<th>Gender</th>
				<td>
					<label><input type="radio" name="sex" value="Male" <?php checked( $f['sex'], 'Male' ); ?>> Male</label>&nbsp;&nbsp;
					<label><input type="radio" name="sex" value="Female" <?php checked( $f['sex'], 'Female' ); ?>> Female</label>
				</td>
			</tr>
			<tr>
				<th>Notify</th>
				<td><label><input name="rep_send_email" type="checkbox" value="1"> Email login details to the new representative</label></td>
			</tr>
		</table>
		<?php submit_button( 'Add Representative', 'primary', 'glow_add_rep_submit' ); ?>
	</form>
</div>
