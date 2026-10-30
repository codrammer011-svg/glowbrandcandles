<?php
/**
 * Copy the referring representative on the fundraiser's own emails
 * (welcome emails and the campaign start/end reminders).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cc header for the fundraiser's representative, or an empty array when the
 * fundraiser has no referral email. user_registration_referral_email is set on
 * registration and carried over when the fundraiser rebooks.
 */
function glow_fundraiser_rep_cc_headers( $user_id ) {
	$user = get_userdata( (int) $user_id );
	if ( ! $user ) {
		return array();
	}
	$rep_email = sanitize_email( trim( (string) get_user_meta( $user->ID, 'user_registration_referral_email', true ) ) );
	if ( ! is_email( $rep_email ) || 0 === strcasecmp( $rep_email, $user->user_email ) ) {
		return array();
	}
	return array( 'Cc: ' . $rep_email );
}
