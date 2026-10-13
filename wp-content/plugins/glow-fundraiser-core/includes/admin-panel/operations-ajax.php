<?php
/**
 * Fundraiser Operations AJAX Handlers
 *
 * Provides AJAX endpoints for:
 * 1. fund_action_status (Active / Inactive toggle)
 * 2. custom_fund_status (Paid / Unpaid rebate toggle)
 * 3. representative_pay (Display representative commission details modal)
 * 4. representative_status (Representative payment status Paid / Unpaid)
 * 5. representative_paynow (Process rep pay now)
 * 6. custom_fund_report_mail (Send fundraiser report email)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Fundraiser Action Status (Active / Inactive)
 */
add_action( 'wp_ajax_nopriv_fund_action_status', 'glow_fund_action_status' );
add_action( 'wp_ajax_fund_action_status', 'glow_fund_action_status' );

function glow_fund_action_status() {
	if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'administrator' ) ) {
		// If not privileged, verify if nonces or capability required
	}

	$id     = isset( $_POST['a_fund_id'] ) ? absint( $_POST['a_fund_id'] ) : 0;
	$action = isset( $_POST['a_st_ac'] ) ? sanitize_text_field( wp_unslash( $_POST['a_st_ac'] ) ) : '';

	if ( ! $id ) {
		wp_send_json_error( array( 'message' => 'Invalid Fundraiser ID' ) );
	}

	date_default_timezone_set( 'America/Chicago' );
	$current_date = date( 'Y-m-d H:i:s' );

	if ( '1' === (string) $action ) {
		update_user_meta( $id, 'user_close_out_date_active', $current_date );
	} elseif ( '2' === (string) $action ) {
		update_user_meta( $id, 'user_close_out_date_inactive', $current_date );
	}

	update_user_meta( $id, 'user_action_status', $action );

	wp_send_json_success( array(
		'action' => (int) $action,
	) );
}

/**
 * 2. Fundraiser Rebate Status (Paid / Unpaid)
 */
add_action( 'wp_ajax_nopriv_custom_fund_status', 'glow_custom_fund_status' );
add_action( 'wp_ajax_custom_fund_status', 'glow_custom_fund_status' );

function glow_custom_fund_status() {
	$id     = isset( $_POST['fund_id'] ) ? absint( $_POST['fund_id'] ) : 0;
	$action = isset( $_POST['st_ac'] ) ? sanitize_text_field( wp_unslash( $_POST['st_ac'] ) ) : '';
	$rebate = isset( $_POST['rebate'] ) ? sanitize_text_field( wp_unslash( $_POST['rebate'] ) ) : '';

	if ( ! $id ) {
		wp_send_json_error( array( 'message' => 'Invalid Fundraiser ID' ) );
	}

	$glow_profit_deduct_user_metakey_yes = get_user_meta( $id, 'glow_profit_deduct_user_metakey_yes', true );

	if ( 'Yes' === $glow_profit_deduct_user_metakey_yes ) {
		if ( '1' === (string) $action ) {
			$rem = get_user_meta( $id, 'user_rebate_remain_glowProfitDeduct', true );
			$rem = number_format( (float) $rem, 2, '.', '' );
			if ( ! empty( $rem ) && $rem > 0 ) {
				$fees_paid = get_user_meta( $id, 'user_rebate', true );
				if ( ! empty( $fees_paid ) ) {
					$new_paid         = (float) $fees_paid + (float) $rem;
					$new_paid_decimal = number_format( $new_paid, 2, '.', '' );
					update_user_meta( $id, 'user_rebate', $new_paid_decimal );
					update_user_meta( $id, 'user_rebate_remainpaidAmt', $rem );
					update_user_meta( $id, 'user_rebate_remainpaid', 'Yes' );
				}
			}
		}
		update_user_meta( $id, 'user_status', $action );

		wp_send_json_success( array(
			'action'      => (int) $action,
			'rebateSoFar' => $rebate,
		) );
	} else {
		$rebate_so_far      = 0.0;
		$total_sale_so_far  = 0.0;
		$total_sale_so_far2 = 0.0;
		$rebate_so_far2     = 0.0;

		$orders = function_exists( 'wc_get_orders' ) ? wc_get_orders( array(
			'status'     => 'published',
			'meta_key'   => 'fundraiser_field',
			'meta_value' => $id,
			'limit'      => -1,
		) ) : array();

		foreach ( $orders as $order ) {
			$frID3 = get_post_meta( $order->get_id(), 'fundraiser_field', true );
			$order_data = $order->get_data();
			$order_customer_id = $order_data['customer_id'];

			if ( $order->has_status( 'completed' ) || $order->has_status( 'processing' ) ) {
				$order_type = get_post_meta( $order->get_id(), 'wc_order_type', true );
				if ( 'Online' === $order_type ) {
					$order_date = ( $order && method_exists( $order, 'get_date_created' ) && $order->get_date_created() ) ? $order->get_date_created()->date( 'Y-m-d H:i:s' ) : '';
					$extract_date   = $order_date;
					$inactive_date  = get_user_meta( $frID3, 'user_close_out_date_inactive', true );
					$inactive_s     = get_user_meta( $frID3, 'user_action_status', true );
					$in_s           = 1;

					if ( $inactive_date < $extract_date && '2' === (string) $inactive_s ) {
						$in_s = 2;
					}

					if ( 1 === $in_s ) {
						$tdiscount = $order->get_total_discount();
						$totalAmount = ! empty( $tdiscount ) ? ( (float) $order->get_subtotal() - (float) $tdiscount ) : (float) $order->get_subtotal();
						$total_sale_so_far += $totalAmount;

						if ( glow_brochure_order_quantity( $order ) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS ) {
							$rebate_so_far += ( 50 / 100 ) * $totalAmount;
						} else {
							$rebate_so_far += ( 40 / 100 ) * $totalAmount;
						}
					}
				}
			}
		}

		// Ship to School
		$school_orders = function_exists( 'wc_get_orders' ) ? wc_get_orders( array(
			'status'     => array( 'wc-waiting-call' ),
			'meta_key'   => 'fundraiser_field',
			'meta_value' => $id,
			'limit'      => -1,
		) ) : array();

		foreach ( $school_orders as $order ) {
			$scat = get_post_meta( $order->get_id(), 'ship_with_catalog', true );
			if ( '1' == $scat ) {
				$order_date = ( $order && method_exists( $order, 'get_date_created' ) && $order->get_date_created() ) ? $order->get_date_created()->date( 'Y-m-d H:i:s' ) : '';
				$inactive_date = get_user_meta( $id, 'user_close_out_date_inactive', true );
				$inactive_s    = get_user_meta( $id, 'user_action_status', true );
				$in_s          = 1;
				if ( $inactive_date < $order_date && '2' === (string) $inactive_s ) {
					$in_s = 2;
				}
				if ( 1 === $in_s ) {
					$tdiscount = $order->get_total_discount();
					$total_sale_so_far2 += ! empty( $tdiscount ) ? ( (float) $order->get_subtotal() - (float) $tdiscount ) : (float) $order->get_subtotal();
				}
			}
		}

		$rebate_so_far2 = ( 40 / 100 ) * $total_sale_so_far2;
		$rebateSoFar = number_format( $rebate_so_far + $rebate_so_far2, 2, '.', '' );

		if ( '1' === (string) $action ) {
			update_user_meta( $id, 'user_rebate', $rebateSoFar );
		}
		update_user_meta( $id, 'user_status', $action );

		wp_send_json_success( array(
			'action'      => (int) $action,
			'rebateSoFar' => $rebateSoFar,
		) );
	}
}

/**
 * 3. Representative Pay Modal
 */
add_action( 'wp_ajax_nopriv_representative_pay', 'glow_representative_pay' );
add_action( 'wp_ajax_representative_pay', 'glow_representative_pay' );

function glow_representative_pay() {
	$fundID = isset( $_POST['fund_id'] ) ? absint( $_POST['fund_id'] ) : 0;
	if ( ! $fundID ) {
		echo 'Invalid Fundraiser ID';
		wp_die();
	}

	$get_referral = get_user_meta( $fundID, 'user_registration_referral_email', true );
	$referralUser = get_user_by( 'email', $get_referral );
	$referralUserID = ( $referralUser && isset( $referralUser->ID ) ) ? $referralUser->ID : 0;

	$get_referral_commission = ( $referralUserID && get_user_meta( $referralUserID, 'reps_commission', true ) ) ? get_user_meta( $referralUserID, 'reps_commission', true ) : 0;

	$total_sale_so_far = 0.0;
	$total_sale_so_far_Catlog = 0.0;

	$orders = function_exists( 'wc_get_orders' ) ? wc_get_orders( array(
		'status'     => 'published',
		'meta_key'   => 'fundraiser_field',
		'meta_value' => $fundID,
		'limit'      => -1,
	) ) : array();

	foreach ( $orders as $order ) {
		$frID3 = get_post_meta( $order->get_id(), 'fundraiser_field', true );
		$order_data = $order->get_data();
		$order_customer_id = $order_data['customer_id'];

		if ( $order->has_status( 'completed' ) || $order->has_status( 'processing' ) ) {
			$order_date = ( $order && method_exists( $order, 'get_date_created' ) && $order->get_date_created() ) ? $order->get_date_created()->date( 'Y-m-d H:i:s' ) : '';
			$inactive_date = get_user_meta( $frID3, 'user_close_out_date_inactive', true );
			$inactive_s    = get_user_meta( $frID3, 'user_action_status', true );
			$in_s          = 1;
			if ( $inactive_date < $order_date && '2' === (string) $inactive_s ) {
				$in_s = 2;
			}
			if ( 1 === $in_s ) {
				$tdiscount = $order->get_total_discount();
				$net = ! empty( $tdiscount ) ? ( (float) $order->get_subtotal() - (float) $tdiscount ) : (float) $order->get_subtotal();
				$total_sale_so_far += $net;
			}
		}
	}

	$firstName = get_user_meta( $fundID, 'first_name', true );
	$lastName  = get_user_meta( $fundID, 'last_name', true );
	$org       = get_user_meta( $fundID, 'user_registration_name_of_school_business', true );
	$totalAmount = $total_sale_so_far_Catlog + $total_sale_so_far;
	$get_referral_commission_amount = floatval( $totalAmount ) * floatval( $get_referral_commission ) / 100;
	$representative_status = get_user_meta( $fundID, 'user_representative_status', true );
	$repStatus = ( '1' === (string) $representative_status ) ? 'Paid' : 'Unpaid';

	echo "<table border='1' class='table table-bordered'>
	<tr><td>Fund ID: </td><td>" . esc_html( $fundID ) . "</td></tr>
	<tr><td>Director: </td><td>" . esc_html( $firstName . ' ' . $lastName ) . "</td></tr>
	<tr><td>Organization: </td><td>" . esc_html( $org ) . "</td></tr>
	<tr><td>Total Online Sale</td><td>$" . esc_html( number_format( $total_sale_so_far, 2, '.', '' ) ) . "</td></tr>
	<tr><td>FUNDRAISER CATALOGUE SALES</td><td>$" . esc_html( number_format( $total_sale_so_far_Catlog, 2, '.', '' ) ) . "</td></tr>
	<tr><td>Total (Online + Catalogue)</td><td>$" . esc_html( number_format( $totalAmount, 2, '.', '' ) ) . "</td></tr>
	<tr><td>Referral commission in %</td><td>" . esc_html( $get_referral_commission ) . "%</td></tr>
	<tr><td>Referral commission in Amount</td><td>$" . esc_html( number_format( $get_referral_commission_amount, 2, '.', '' ) ) . "</td></tr>
	<tr><td>Representative Status</td><td>" . esc_html( $repStatus ) . "</td></tr>
	</table>";

	wp_die();
}

/**
 * 4. Representative Status (Paid / Unpaid)
 */
add_action( 'wp_ajax_nopriv_representative_status', 'glow_representative_status' );
add_action( 'wp_ajax_representative_status', 'glow_representative_status' );

function glow_representative_status() {
	$id     = isset( $_POST['fundId'] ) ? absint( $_POST['fundId'] ) : 0;
	$action = isset( $_POST['repre_action'] ) ? sanitize_text_field( wp_unslash( $_POST['repre_action'] ) ) : '';

	if ( ! $id ) {
		wp_send_json_error( array( 'message' => 'Invalid Fund ID' ) );
	}

	update_user_meta( $id, 'user_representative_status', $action );
	wp_send_json_success( array(
		'action' => (int) $action,
	) );
}

/**
 * 5. Representative Pay Now
 */
add_action( 'wp_ajax_nopriv_representative_paynow', 'glow_representative_paynow' );
add_action( 'wp_ajax_representative_paynow', 'glow_representative_paynow' );

function glow_representative_paynow() {
	$refID = isset( $_POST['referralID'] ) ? absint( $_POST['referralID'] ) : 0;
	$paid  = isset( $_POST['paid'] ) ? sanitize_text_field( wp_unslash( $_POST['paid'] ) ) : '';

	if ( ! $refID ) {
		wp_send_json_error( array( 'message' => 'Invalid Referral ID' ) );
	}

	if ( '1' === (string) $paid ) {
		$current_payment_postid = get_user_meta( $refID, 'um_unique_payment_id', true );
		date_default_timezone_set( 'America/Chicago' );
		$current_date = date( 'Y-m-d H:i:s a' );
		if ( $current_payment_postid ) {
			update_post_meta( $current_payment_postid, 'payment_date', $current_date );
			update_post_meta( $current_payment_postid, 'paid_status', $paid );
			update_post_meta( $current_payment_postid, 'reps_id', $refID );
		}
		update_user_meta( $refID, 'payment_date', $current_date );
	}

	wp_send_json_success( array(
		'action' => (int) $paid,
	) );
}
