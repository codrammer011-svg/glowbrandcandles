<?php
/**
 * Seller Registration AJAX Handlers
 *
 * Handles email existence/role check and 6-digit Fundraiser ID validation for seller registration.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Email validation for seller registration
 */
add_action( 'wp_ajax_nopriv_ajax_seller_register_email_validation', 'glow_ajax_seller_register_email_validation' );
add_action( 'wp_ajax_ajax_seller_register_email_validation', 'glow_ajax_seller_register_email_validation' );

function glow_ajax_seller_register_email_validation() {
	$email = isset( $_POST['seller_email'] ) ? sanitize_email( wp_unslash( $_POST['seller_email'] ) ) : '';

	if ( empty( $email ) || ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => 'Invalid email address.' ) );
	}

	$user_id = email_exists( $email );

	if ( $user_id ) {
		$user_info = get_userdata( $user_id );
		$user_roles = (array) ( $user_info ? $user_info->roles : array() );

		if ( in_array( 'fundraiser', $user_roles, true ) ) {
			$user_type = 1;
		} elseif ( in_array( 'seller', $user_roles, true ) ) {
			$user_type = 2;
		} else {
			$user_type = 0;
		}

		wp_send_json_success( array(
			'action'    => 2,
			'user_type' => $user_type,
		) );
	} else {
		wp_send_json_success( array(
			'action' => 1,
		) );
	}
}

/**
 * 2. 6-digit Fundraiser ID validation for seller registration
 */
add_action( 'wp_ajax_nopriv_ajax_seller_register_FID_validation', 'glow_ajax_seller_register_FID_validation' );
add_action( 'wp_ajax_ajax_seller_register_FID_validation', 'glow_ajax_seller_register_FID_validation' );

function glow_ajax_seller_register_FID_validation() {
	$fid = isset( $_POST['fund_id'] ) ? preg_replace( '/\D/', '', sanitize_text_field( wp_unslash( $_POST['fund_id'] ) ) ) : '';

	if ( strlen( $fid ) === 6 ) {
		$user_info = get_userdata( (int) $fid );

		if ( $user_info && in_array( 'fundraiser', (array) $user_info->roles, true ) ) {
			$fundr_status = get_user_meta( (int) $fid, 'user_action_status', true );

			if ( (int) $fundr_status === 2 ) {
				// Inactive fundraiser
				wp_send_json_success( array( 'action' => 4 ) );
			} else {
				// Valid and active fundraiser
				wp_send_json_success( array( 'action' => 1 ) );
			}
		} else {
			// User exists but not a fundraiser or user does not exist
			wp_send_json_success( array( 'action' => 2 ) );
		}
	} else {
		// Invalid length or format
		wp_send_json_success( array( 'action' => 0 ) );
	}
}
