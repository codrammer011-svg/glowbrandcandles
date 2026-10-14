<?php
/**
 * Online-store profit deduction — recompute on checkout (hook-based).
 *
 * The brochure (catalog) order's "Online Profits Deduction" is the fundraiser's
 * online-store + ship-to-school profit, credited against what they owe Glow.
 *
 * Historically this was CALCULATED inside the "Custom Checkout Page" page template
 * (legacy/templates/template-checkout.php), which saved the figures to
 * glow_profit_deduct_save_oncheckout_page* user-meta. The fee-adder
 * (theme-functions.php: set_installment_fee, on woocommerce_cart_calculate_fees)
 * then read that meta to add the "Online Profits Deduction" cart fee.
 *
 * When checkout page #14 was switched to the Elementor template, that page template
 * stopped running, so the meta was never refreshed and the deduction silently
 * became $0 (no brochure order since ~2026-05-18 got it). This moves the SAME
 * calculation into a woocommerce_cart_calculate_fees hook at priority 5 — before
 * set_installment_fee (priority 10) reads the meta — so it runs regardless of the
 * checkout page's template/Elementor. The math is preserved verbatim from the
 * legacy template (40%/50% tiers, ship-to-school counted only in waiting-call).
 */
defined( 'ABSPATH' ) || exit;

add_action( 'woocommerce_cart_calculate_fees', 'glow_fc_recompute_online_profit_deduction_meta', 5 );
function glow_fc_recompute_online_profit_deduction_meta() {

	if ( is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		return;
	}
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}
	if ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-received' ) ) {
		return;
	}
	if ( PHP_SESSION_NONE === session_status() && ! headers_sent() ) {
		@session_start();
	}
	if ( ! glow_fc_cart_is_catalog() ) {
		return;
	}

	$user = wp_get_current_user();
	if ( ! in_array( 'fundraiser', (array) $user->roles, true ) ) {
		return;
	}

	global $woocommerce;
	$ID = $user->ID;

	/* =======================================================================
	   VERBATIM calculation ported from legacy/templates/template-checkout.php
	   (lines 28-319). Do not "simplify" — it must match the historical money.
	   ======================================================================= */
	$total_sale_so_far=0;
	$total_current_cart_catlog_qty=0;
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

				$order_data = $order->get_data();
				$order_customer_id = $order_data['customer_id'];
				if ( $order->has_status('completed') || $order->has_status('processing')) {
					$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
					  if($order_type=="Online")
					  {
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
				if ($in_s ==1){
				  if ($frID3 != $order_customer_id)
				  {
					  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
					  if($order_type=="Online")
					  {
						$total_sale_so_far += max(0, $order->get_subtotal() - $order->get_total_discount());

					   foreach ($order->get_items() as $item_key => $item_values):
								$item_data = $item_values->get_data();
							 endforeach;

					$total_product = $order->get_total();

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

				 /* Online (ship-to-home) is ALWAYS 40% per client rule - no 50% tier. */
				$Discount = ($percentage / 100) * $totalAmount;
				$Discount = number_format($Discount, 2, '.', '');
				$rebate_so_far += $Discount;

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
						 $total_sale_so_far += max(0, $order->get_subtotal() - $order->get_total_discount());
						}

					   foreach ($order->get_items() as $item_key => $item_values):
								$item_data = $item_values->get_data();
							 endforeach;

						$total_product = $order->get_total();

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

						 /* Online (ship-to-home) is ALWAYS 40% per client rule - no 50% tier. */
				$Discount = ($percentage / 100) * $totalAmount;
				$Discount = number_format($Discount, 2, '.', '');
				$rebate_so_far += $Discount;
					}
				}
				else{}

		  } } }
	}

	/* Ship to School Total Sales and Rebate */
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
										$total_cat_quantity += $item_data['quantity'];
								  endforeach;
						} } }
				}

		$total_current_cart_catlog_qty=$woocommerce->cart->get_cart_contents_count();
		if ($total_current_cart_catlog_qty > 0) {
			$total_cat_quantity=$total_cat_quantity+$total_current_cart_catlog_qty;
		}

						$args = array(
							 'status' => array('wc-waiting-call', 'wc-processing', 'wc-completed'),
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
						if ($in_s ==1){
						  if ($frID3 != $order_customer_id) {
						  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
						if ($order_type=='ship-to-school') {

							$arr_order_id2[]=$order->get_id();

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
								$total_sale_so_far2 = number_format($total_sale_so_far2, 2, '.', '');

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
				$shiptogroup_profit="";
				$real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
				if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
				{
							$Discount    = ($percentFiftyNEW / 100) * $total_sale_so_far2;
					$rebate_so_far2 = number_format($Discount, 2, '.', '');
					if($rebate_so_far2>0)
					{
						$shiptogroup_profit = $percentFiftyNEW;
					}
				}
						else
				{
							$Discount    = ($percentageNEW / 100) * $total_sale_so_far2;
							$rebate_so_far2 = number_format($Discount, 2, '.', '');
					if($rebate_so_far2>0)
					{
						$shiptogroup_profit = $percentageNEW;
					}
				}
				 // End Ship to school

	/*Total NEW Rebate with Ship to school orders */
	$rebateSoFar = $rebate_so_far+$rebate_so_far2;
	$rebateSoFar = number_format($rebateSoFar, 2, '.', '');

	$total_sale_so_far_full = $total_sale_so_far + $total_sale_so_far2;
	$total_sale_so_far_full = number_format($total_sale_so_far_full, 2, '.', '');

	update_user_meta( $ID,'glow_profit_deduct_save_oncheckout_page_total_online_sales', $total_sale_so_far_full);
	update_user_meta( $ID,'glow_profit_deduct_save_oncheckout_page', $rebateSoFar);
	/* New Fields data for invoice */
	update_user_meta( $ID,'glow_profit_deduct_save_oncheckout_page_total_onlineOrder_rebate', $rebate_so_far);
	update_user_meta( $ID,'glow_profit_deduct_save_oncheckout_page_total_shiptoschoolOrder_rebate', $rebate_so_far2);
	update_user_meta( $ID,'glow_profit_deduct_save_oncheckout_page_total_onlineOrder_sales', $total_sale_so_far);
	update_user_meta( $ID,'glow_profit_deduct_save_oncheckout_page_total_shiptoschoolOrder_sales', $total_sale_so_far2);
	update_user_meta( $ID,'glow_profit_deduct_save_oncheckout_page_total_shiptoschoolOrder_percentageProfit', $shiptogroup_profit);
}
