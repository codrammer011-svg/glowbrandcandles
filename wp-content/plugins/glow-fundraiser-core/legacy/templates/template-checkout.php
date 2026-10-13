<?php
/**
 * Template Name: Custom Checkout Page
 */
 get_header();
?>
<?php 

	$l = et_page_config();

?>
<?php
		$user = wp_get_current_user();  
		if(!is_wc_endpoint_url( 'order-received' ) ) {
			session_start();
			global $woocommerce;
			
			if(!empty($_SESSION['sellername']) && $_SESSION['sellername']=='Catlog') 
			{
				if( in_array( 'fundraiser', (array) $user->roles ) ) 
				{
					$ID = $user->ID;
					
				/*	$glow_profit_deduct=get_user_meta( $ID,'glow_profit_deduct_user_metakey_yes', true);
					if(empty($glow_profit_deduct))
					{
						*/
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
											  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
											  if($order_type=="Online") 
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
											$Discount = number_format($Discount, 2, '.', '');
										 $rebate_so_far+=$Discount;
										 }
										 else
										 {
											$Discount    = ($percentage / 100) * $totalAmount;
											$Discount = number_format($Discount, 2, '.', '');
											 $rebate_so_far+=$Discount;
										 }
									   
									  
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
													$Discount = number_format($Discount, 2, '.', '');
													$rebate_so_far+=$Discount;
												 }
												 else
												 {
													$Discount    = ($percentage / 100) * $totalAmount;
													$Discount = number_format($Discount, 2, '.', '');
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
							   
						$total_current_cart_catlog_qty=$woocommerce->cart->get_cart_contents_count();   
						if ($total_current_cart_catlog_qty > 0) { 
							$total_cat_quantity=$total_cat_quantity+$total_current_cart_catlog_qty;
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
							
				/*	} */
					update_user_meta( $ID,'glow_profit_deduct_save_oncheckout_page_total_online_sales', $total_sale_so_far_full);
					update_user_meta( $ID,'glow_profit_deduct_save_oncheckout_page', $rebateSoFar);
					/* New Fiedls data for invoice */
					update_user_meta( $ID,'glow_profit_deduct_save_oncheckout_page_total_onlineOrder_rebate', $rebate_so_far);
					update_user_meta( $ID,'glow_profit_deduct_save_oncheckout_page_total_shiptoschoolOrder_rebate', $rebate_so_far2);
					update_user_meta( $ID,'glow_profit_deduct_save_oncheckout_page_total_onlineOrder_sales', $total_sale_so_far);
					update_user_meta( $ID,'glow_profit_deduct_save_oncheckout_page_total_shiptoschoolOrder_sales', $total_sale_so_far2);
					update_user_meta( $ID,'glow_profit_deduct_save_oncheckout_page_total_shiptoschoolOrder_percentageProfit', $shiptogroup_profit);
				
				}
			}
		}
	
?>
<?php do_action( 'et_page_heading' ); ?>

	<div class="container content-page">
		<div class="page-content sidebar-position-<?php esc_attr_e( $l['sidebar'] ); ?> sidebar-mobile-<?php esc_attr_e( class_exists('WooCommerce') && ( is_checkout() || is_cart() || is_account_page() || in_array( 'woocommerce-wishlist', get_body_class() ) ) ? $l['shop-sidebar-mobile'] : $l['sidebar-mobile'] ); ?>">
			<div class="row">

				<div class="content ">
					<?php if(have_posts()): while(have_posts()) : the_post(); ?>
						
						<?php the_content(); ?>

						<div class="post-navigation">
							<?php wp_link_pages(); ?>
						</div>
						
						<?php if ($post->ID != 0 && current_user_can('edit_post', $post->ID)): ?>
							<?php edit_post_link( __('Edit this', 'royal'), '<p class="edit-link">', '</p>' ); ?>
						<?php endif ?>

					<?php endwhile; else: ?>

						<h3><?php esc_html_e('No pages were found!', 'royal') ?></h3>

					<?php endif; ?>

				</div>

			

			</div><!-- end row-fluid -->

		</div>
	</div><!-- end container -->

 <div id="error_popup" class="checkout_popup"></div>
<style>
#shipping_method input[type=radio],#shipping_method label
{
	display:none;
}
#shipping_method input[type=radio]:checked
{
	display:none;
}
#shipping_method input[type=radio]:checked + label
{
	display:block;
}
</style>
 <?php 
get_footer();
?>