<?php 
/**
 * Template Name: New Custom Report
 */
	get_header();
?>
<style>
.catalogue-sale .fundraiser-order-details-col.product-quantity, .catalogue-sale .fundraiser-order-details-col.product-price1, .catalogue-sale .fundraiser-order-details-col.all-order-subtotal1, .catalogue-sale .fundraiser-order-details-col.all-order-discount1, .catalogue-sale .fundraiser-order-details-col.all-order-discount-calc {
    width: 11%;
}
</style>
<?php 
$mode=$_GET['debug'];
if($mode==1)
{
		$current_user = wp_get_current_user(); 
		$frID2 = $current_user->ID; 
		$IDG=$_GET['frid'];
		if(!empty($IDG))
		{
			$ID = $IDG;
			$frID2=$IDG;
		}
		else
		{
			$ID = $frID2;
		}
?>
<div class="container content-page">
		<div class="page-content sidebar-position-<?php esc_attr_e( $l['sidebar'] ); ?> sidebar-mobile-<?php esc_attr_e( class_exists('WooCommerce') && ( is_checkout() || is_cart() || is_account_page() || in_array( 'woocommerce-wishlist', get_body_class() ) ) ? $l['shop-sidebar-mobile'] : $l['sidebar-mobile'] ); ?>">
			<div class="row">
                  <?php do_action( 'et_page_heading' ); ?>                  
				<div class="content <?php esc_attr_e( $l['content-class'] ); ?>">
                    <div class="intro-content h"><?php the_content(); ?></div>
                    <div class="fundraiser-dashboard-section report total-sale">
					
                        <?php $current_user = wp_get_current_user(); 
                            $frID2          = $current_user->ID; 
							$IDG=$_GET['frid'];
							//echo $inactive_date=get_user_meta($frID2, "user_close_out_date_inactive", true);
							if(!empty($IDG))
							{
								$ID = $IDG;
								$frID2=$IDG;
							}
							else
							{
								$ID = $frID2;
								
							}
							   $total_sale_so_far=0;
							  $rebate_so_far=0;
							  
                         /*   $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								
                            );
                            $orders = wc_get_orders($args); */
							$total_order=0;
							$item_qty=0;
                        /* foreach ( $orders as $order ) { */
						$arr_orders=array(34478,34424,34215,34209,34201,34152,34132,34122,34119,34116,34104,34089,34070,34064,34056,34035,34031,
							34024,34016,34011,33975,33967,33930,33899,33877,33863,33850,33824,33742,33741,33699,33677,33632,33524,33509,33501,33444,
							33434,33379,33318,33309,33293,33283,33282,33272,33261,33253,33248);
							
							for($e=0;$e<count($arr_orders);$e++)
							{
							$order = new WC_Order( $arr_orders[$e] );
							$ISD = $order->get_id();
                            $frID3 = get_post_meta($ISD, 'fundraiser_field', true );
							$ID3 = get_post_meta($ISD, 'wc_seller_field', true );
							$order_type = get_post_meta($ISD, 'wc_order_type', true );
							if($order_type=="Online")
							{
                         /*   if ($ID3 == $IDD) { */
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
									/*Active / inactive*/
									
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
									 foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
											 endforeach; 
									
								  if ($frID3 != $order_customer_id) {
                              $tdiscount=$order->get_total_discount();
										$total_order=$total_order+1;
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
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
								} 
								 else if ($frID3 == $order_customer_id) {
									  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										  $total_order=$total_order+1;
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
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
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
								} 
								}
								}
                            /*     } */
						}
                        ?>

                        <div class="admin-report"><span class="details-btn send_report" data-fund_id="<?php echo $frID2; ?>">Send Report to Fundraiser</span><span class="details-btn send_report_another" >Send Report to Other</span></div>
                        <h3>Total Sale</h3>
                        <h5>Total Sale So Far: <strong>$<span class="fr-total-sale1"><?php echo $total_sale_so_far=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></h5>
                        <h5>Total Rebate So Far: <strong>$<span class="fr-total-rebate1"><?php echo $rebate_so_far=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></h5>
                       <h5> Total Orders: <?php echo $total_order; ?></h5>
						<!--<div class="fundraiser-order-table">
                            <div class="fundraiser-order-row fr-header">
                                <div class="fr-order-col fr-order-id">Order</div>
                                <div class="fr-order-col fr-order-date">Date</div>
                                <div class="fr-order-col fr-order-customer">Customer</div>
                                <div class="fr-order-col fr-order-seller">Seller</div>
                                <div class="fr-order-col fr-order-total">Product Sales</div>
                                <div class="fr-order-col fr-order-total">Rebate</div>
                                <div class="fr-order-col fr-order-action">Details</div>
                            </div>

                        <?php 
                            $args = array(
                               'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
							if($order_type=="Online")
							{
                            if ($frID3 == $frID2) { 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
									
									/* Active / Inactive */ 
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
                                  ?>

                                <div class="fundraiser-order-row">
                                    <div class="fr-order-col fr-order-id"><?php echo $order->get_id(); ?></div>
                                    <div class="fr-order-col fr-order-date"><?php echo $order_data['date_created']->date('Y-m-d'); ?></div>

                                    <div class="fr-order-col fr-order-customer"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?>
                                    </div>

                                    <div class="fr-order-col fr-order-seller">
                                       <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
										if($slrID=="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else if(is_numeric($slrID))
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
										else
										{
											echo "N/A";
										}
									   ?>
                                    </div>

                                    <div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span>
                                       <span class="fr-order-amt"><?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></span>  
                                    </div>
                                    <div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span>
                                       <span class="fr-order-amt1">

                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
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
                        
                               if ( $order_customer_id != $frID2  ) { //
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
                                 else
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
                               }
                               echo $Discount;
                         
                              ?>
                                       </span>  

                                    </div>
                                    <div class="fr-order-col fr-order-action"><span class="details-btn" data-trigger="all-order-<?php echo $order->get_id(); ?>-details">Expand</span></div>

                                <div id="all-order-<?php echo $order->get_id(); ?>-details" class="fundraiser-order-details-table">
                                    <div class="fundraiser-order-details-row fr-header">
                                        <div class="fundraiser-order-details-col product-name">Product</div>
                                        <div class="fundraiser-order-details-col product-quantity">Quantity</div>
                                        <div class="fundraiser-order-details-col product-price">Total</div>
                                    </div>

                                    <?php foreach ($order->get_items() as $item_key => $item_values): 
                                            $item_data = $item_values->get_data();  ?>

                                    <div class="fundraiser-order-details-row fr-body line-item-match">
                                        <div class="fundraiser-order-details-col product-name"><?php echo $item_data['name']; ?><?php echo glow_product_type_html( $item_values ); ?></div>
                                        <div class="fundraiser-order-details-col product-quantity"><?php echo $item_data['quantity']; ?></div>
                                        <div class="fundraiser-order-details-col product-price show">$<span><?php echo $item_data['subtotal'] = number_format( $item_data['subtotal'], 2);  ?></span>
                                        </div>
                                    </div>                                    
                                    <?php endforeach; ?>

                                   <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Subtotal</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col all-order-subtotal1">$<span><?php $subtot=$order->get_subtotal(); echo $subtot=number_format( $subtot, 2); ?></span></div>
                                    </div>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Discount</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col all-order-discount1">$<span><?php $tdiscount=$order->get_total_discount(); if(!empty($tdiscount)) { echo $tdiscount=number_format($tdiscount, 2, '.', ''); }else{ echo "0.00"; } ?></span></div>
                                    </div>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Total</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col product-price">$<span><?php $tdiscount=$order->get_total_discount(); if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></span>  
                                       <?php 
                                           //$total_product = $order->get_total() ; 

                                           // if ($item_data['quantity'] == 99) {
                                           //      echo $Discount =  $total_product - ( $total_product * (40 / 100));
                                           // }
                                        
                                       ?>

                                        </div>
                                    </div>
                                </div>
                                </div>
						<?php } 
							else if ($frID3 == $order_customer_id) {
								 $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
                                  ?>

                                <div class="fundraiser-order-row">
                                    <div class="fr-order-col fr-order-id"><?php echo $order->get_id(); ?></div>
                                    <div class="fr-order-col fr-order-date"><?php echo $order_data['date_created']->date('Y-m-d'); ?></div>

                                    <div class="fr-order-col fr-order-customer"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?>
                                    </div>

                                    <div class="fr-order-col fr-order-seller">
                                       <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
										if($slrID=="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else if(is_numeric($slrID))
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
										else
										{
											echo "N/A";
										}
									   ?>
                                    </div>

                                    <div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span>
                                       <span class="fr-order-amt"><?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></span>  
                                    </div>
                                    <div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span>
                                       <span class="fr-order-amt1">

                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
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
                        
                               if ( $order_customer_id != $frID2  ) { //
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
                                 else
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
                               }
                               echo $Discount;
                         
                              ?>
                                       </span>  

                                    </div>
                                    <div class="fr-order-col fr-order-action"><span class="details-btn" data-trigger="all-order-<?php echo $order->get_id(); ?>-details">Expand</span></div>

                                <div id="all-order-<?php echo $order->get_id(); ?>-details" class="fundraiser-order-details-table">
                                    <div class="fundraiser-order-details-row fr-header">
                                        <div class="fundraiser-order-details-col product-name">Product</div>
                                        <div class="fundraiser-order-details-col product-quantity">Quantity</div>
                                        <div class="fundraiser-order-details-col product-price">Total</div>
                                    </div>

                                    <?php foreach ($order->get_items() as $item_key => $item_values): 
                                            $item_data = $item_values->get_data();  ?>

                                    <div class="fundraiser-order-details-row fr-body line-item-match">
                                        <div class="fundraiser-order-details-col product-name"><?php echo $item_data['name']; ?><?php echo glow_product_type_html( $item_values ); ?></div>
                                        <div class="fundraiser-order-details-col product-quantity"><?php echo $item_data['quantity']; ?></div>
                                        <div class="fundraiser-order-details-col product-price show">$<span><?php echo $item_data['subtotal'] = number_format( $item_data['subtotal'], 2);  ?></span>
                                        </div>
                                    </div>                                    
                                    <?php endforeach; ?>

                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Subtotal</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col all-order-subtotal1">$<span><?php $subtot=$order->get_subtotal(); echo $subtot=number_format( $subtot, 2); ?></span></div>
                                    </div>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Discount</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col all-order-discount1">$<span><?php $tdiscount=$order->get_total_discount(); if(!empty($tdiscount)) { echo $tdiscount=number_format($tdiscount, 2, '.', ''); } else{ echo "0.00"; } ?></span></div>
                                    </div>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Total</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col product-price">$<span><?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></span>  
                                       <?php 
                                           //$total_product = $order->get_total() ; 

                                           // if ($item_data['quantity'] == 99) {
                                           //      echo $Discount =  $total_product - ( $total_product * (40 / 100));
                                           // }
                                        
                                       ?>

                                        </div>
                                    </div>
                                </div>
                                </div>
							<?php } }
							else{}


								} } 
								}
						}
                        }
                        ?>
                            
                        </div>-->
                    </div>
                    
					<!-- Online Sale Section  -->
					
                    <div class="fundraiser-dashboard-section report online-sale">
                        <h3>Online Sale</h3>
                        <h5>Online Sale so far: <strong>$<span class="fr-online-sale1"><?php echo $total_sale_so_far=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></h5>
                        <div class="fundraiser-order-table">
                            <div class="fundraiser-order-row fr-header">
                                <div class="fr-order-col fr-order-id">Order</div>
                                <div class="fr-order-col fr-order-date">Date</div>
                                <div class="fr-order-col fr-order-customer">Customer</div>
                                <div class="fr-order-col fr-order-seller">Seller</div>
                                <div class="fr-order-col fr-order-total">Product Sales</div>
                                <div class="fr-order-col fr-order-rebate">Rebate</div>
                                <div class="fr-order-col fr-order-action">Details</div>
                            </div>
                        <?php 
						 $order_quantity_test=0;
                        /*    $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);

                        foreach ( $orders as $order ) {
							*/
							$arr_orders=array(34478,34424,34215,34209,34201,34152,34132,34122,34119,34116,34104,34089,34070,34064,34056,34035,34031,
							34024,34016,34011,33975,33967,33930,33899,33877,33863,33850,33824,33742,33741,33699,33677,33632,33524,33509,33501,33444,
							33434,33379,33318,33309,33293,33283,33282,33272,33261,33253,33248);
							
							for($e=0;$e<count($arr_orders);$e++)
							{
							$order = new WC_Order( $arr_orders[$e] );
							$ISD = $order->get_id();
                            $frID3 = get_post_meta($ISD,'fundraiser_field', true );
							$order_type = get_post_meta($ISD, 'wc_order_type', true );
							if($order_type=="Online")
							{
                            if ($frID3 == $frID2) { 
                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
 
								if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
									
								/*Active/Inactive */
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
								 foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data(); 
												
												$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
											if($order_type=="Online") 
											{
													$order_quantity_test += $item_data['quantity']; 
											}
											
																								
                                        endforeach; 
                                if ($order_customer_id != $frID2) { ?>
                                <div class="fundraiser-order-row sellermatch">
                                    <div class="fr-order-col fr-order-id"><?php echo $order->get_id(); ?></div>
                                    <div class="fr-order-col fr-order-date"><?php echo $order_data['date_created']->date('Y-m-d'); ?></div>
                                    <div class="fr-order-col fr-order-customer"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></div>
                                    <div class="fr-order-col fr-order-seller">
                                        <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
										if($slrID=="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else if(is_numeric($slrID))
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
										else
										{
											echo "N/A";
										}
									   ?>
                                    </div>
                                    <div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span><span class="fr-order-amt"><?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></span></div>
                                    <div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span><span class="fr-order-amt1">

                                        <?php 
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
                                        $Discount    = (40 / 100) * $totalAmount;
                                        echo $Discount = number_format($Discount, 2, '.', '');
                        
                                         ?></span></div>
                                    <div class="fr-order-col fr-order-action"><span class="details-btn" data-trigger="ol-order-<?php echo $order->get_id(); ?>-details">Expand</span></div>

                                <div id="ol-order-<?php echo $order->get_id(); ?>-details" class="fundraiser-order-details-table">
                                    <div class="fundraiser-order-details-row fr-header">
                                        <div class="fundraiser-order-details-col product-name">Product</div>
                                        <div class="fundraiser-order-details-col product-quantity">Quantity</div>
                                        <div class="fundraiser-order-details-col product-price">Total</div>
                                    </div>
                                    <?php foreach ($order->get_items() as $item_key => $item_values): 
                                            $item_data = $item_values->get_data();  ?>
                                    <div class="fundraiser-order-details-row fr-body">
                                       <div class="fundraiser-order-details-col product-name"><?php echo $item_data['name']; ?><?php echo glow_product_type_html( $item_values ); ?></div>
                                       <div class="fundraiser-order-details-col product-quantity"><?php echo $item_data['quantity']; ?></div>
                                       <div class="fundraiser-order-details-col product-price show">$<span><?php echo $item_data['subtotal']=number_format( $item_data['subtotal'], 2); ?></span></div>
                                    </div>                                    
                                    <?php endforeach; ?>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Subtotal</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col all-order-subtotal1">$<span><?php $subtot=$order->get_subtotal(); echo $subtot=number_format( $subtot, 2); ?></span></div>
                                    </div>
                                    <div class="fundraiser-order-details-row fr-footer">
										<div class="fundraiser-order-details-col product-name">Order Discount</div>
										<div class="fundraiser-order-details-col product-quantity"></div>
										<div class="fundraiser-order-details-col all-order-discount1">$<span><?php $tdiscount=$order->get_total_discount(); if(!empty($tdiscount)) { echo $tdiscount=number_format($tdiscount, 2, '.', ''); }else{ echo "0.00"; } ?></span></div>
									</div> 
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Total</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col product-price">$<span><?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></span>
                                        </div> <hr>
                                 <?php 
                              ////////////////////// //////////////////////                
                                    $percentage  = 40;
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

                                    $totalAmount =  ($percentage / 100) * $totalAmount;
                                       echo "Online Rebate: $ ".$Rebate = number_format($totalAmount, 2) . "<br>";

                                       //echo $quantity = $item_data['quantity']."<BR>"; /// count(var)  
                                       // foreach ($quantity  => $value) {
                                       //    echo $value."<br>";
                                       //  } 
                                       // echo $quant =  count($quantity);   
                                  ?>
                                    </div>
                                </div>
                                </div>
								<?php } 

								else if ($order_customer_id == $frID2) { 
								 $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
								
								?>
                                <div class="fundraiser-order-row sellermatch">
                                    <div class="fr-order-col fr-order-id"><?php echo $order->get_id(); ?></div>
                                    <div class="fr-order-col fr-order-date"><?php echo $order_data['date_created']->date('Y-m-d'); ?></div>
                                    <div class="fr-order-col fr-order-customer"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></div>
                                    <div class="fr-order-col fr-order-seller">
                                        <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
										if($slrID=="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else if(is_numeric($slrID))
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
										else
										{
											echo "N/A";
										}
									   ?>
                                    </div>
                                    <div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span><span class="fr-order-amt"><?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></span></div>
                                    <div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span><span class="fr-order-amt1">

                                        <?php 
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
                                        $Discount    = (40 / 100) * $totalAmount;
                                        echo $Discount = number_format($Discount, 2, '.', '');
                        
                                         ?></span></div>
                                    <div class="fr-order-col fr-order-action"><span class="details-btn" data-trigger="ol-order-<?php echo $order->get_id(); ?>-details">Expand</span></div>

                                <div id="ol-order-<?php echo $order->get_id(); ?>-details" class="fundraiser-order-details-table">
                                    <div class="fundraiser-order-details-row fr-header">
                                        <div class="fundraiser-order-details-col product-name">Product</div>
                                        <div class="fundraiser-order-details-col product-quantity">Quantity</div>
                                        <div class="fundraiser-order-details-col product-price">Total</div>
                                    </div>
                                    <?php foreach ($order->get_items() as $item_key => $item_values): 
                                            $item_data = $item_values->get_data();  ?>
                                    <div class="fundraiser-order-details-row fr-body">
                                       <div class="fundraiser-order-details-col product-name"><?php echo $item_data['name']; ?><?php echo glow_product_type_html( $item_values ); ?></div>
                                       <div class="fundraiser-order-details-col product-quantity"><?php echo $item_data['quantity']; ?></div>
                                       <div class="fundraiser-order-details-col product-price show">$<span><?php echo $item_data['subtotal']=number_format( $item_data['subtotal'], 2); ?></span></div>
                                    </div>                                    
                                    <?php endforeach; ?>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Subtotal</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col all-order-subtotal1">$<span><?php $subtot=$order->get_subtotal(); echo $subtot=number_format( $subtot, 2); ?></span></div>
                                    </div>
                                    <div class="fundraiser-order-details-row fr-footer">
										<div class="fundraiser-order-details-col product-name">Order Discount</div>
										<div class="fundraiser-order-details-col product-quantity"></div>
										<div class="fundraiser-order-details-col all-order-discount1">$<span><?php $tdiscount=$order->get_total_discount(); if(!empty($tdiscount)) { echo $tdiscount=number_format($tdiscount, 2, '.', ''); }else{ echo "0.00"; } ?></span></div>
									</div> 
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Total</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col product-price">$<span><?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></span>
                                        </div> <hr>
                                 <?php 
                              ////////////////////// //////////////////////                
                                    $percentage  = 40;
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

                                    $totalAmount =  ($percentage / 100) * $totalAmount;
                                       echo "Online Rebate: $ ".$Rebate = number_format($totalAmount, 2) . "<br>";

                                       //echo $quantity = $item_data['quantity']."<BR>"; /// count(var)  
                                       // foreach ($quantity  => $value) {
                                       //    echo $value."<br>";
                                       //  } 
                                       // echo $quant =  count($quantity);   
                                  ?>
                                    </div>
                                </div>
                                </div>
								<?php } } 
								else{}


								} }
                        }
						}
                        }
                        ?>
                         <input type="hidden" value="<?php echo $order_quantity_test; ?>">   
                        </div>
                    </div>
                    

<!-- Catalog section-->
 <?php $current_user = wp_get_current_user(); 
                            $frID2          = $current_user->ID; 
							$IDG=$_GET['frid'];
							if(!empty($IDG))
							{
								$ID = $IDG;
								$frID2=$IDG;
							}
							else
							{
								$ID = $frID2;
								
							}
							if($ID!=207706)
							{
							   $total_sale_so_far=0;
							  $rebate_so_far=0;
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
								$new_catalog_order=get_post_meta( $order->get_id(), 'new_catalog_order_50_percent',true);	
								$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Catlog") 
									  {
                         /*   if ($ID3 == $IDD) { */
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
							
								  if ($frID3 == $order_customer_id) {
									  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Catlog") 
									  {
										$order_item_quantity = 0; 
										  foreach ($order->get_items() as $item_key => $item_values): 
                                         $item_id   = $item_values->get_id();
                                         $item_data = $item_values->get_data();
											$order_item_quantity += $item_data['quantity']; 										 
										  endforeach;
										  $qty= $order_item_quantity; 
										if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order)) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale_so_far+=$subtot-$to_widthdraw; 
										}  
										else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
											$total_sale_so_far+=$subtot-$to_widthdraw; 
										} 
										else
										{ 
						
										}
										

								 }
								} 
								else{}
								}
						 }
                            /*     } */
						}
                        ?>



                    <div class="fundraiser-dashboard-section report catalogue-sale">
                        <h3>Catalogue Sale</h3>
                        <h5>Mass Catalogue Sale so far: <strong>$<span class="fr-catalogue-sale1"><?php echo $total_sale_so_far=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></h5>
                        <div class="fundraiser-order-table">
                            <div class="fundraiser-order-row fr-header">
                                <div class="fr-order-col fr-order-id">Order</div>
                                <div class="fr-order-col fr-order-date">Date</div>
                                <div class="fr-order-col fr-order-customer">Customer</div>
                                <div class="fr-order-col fr-order-seller">Seller</div>
                                <div class="fr-order-col fr-order-total">Product Sales</div>
                                <div class="fr-order-col fr-order-action">Details</div>
                            </div>
							
                        <?php  $qty_sum=0;
                            $args = array(
                                'status' => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID
                            );

                            $orders = wc_get_orders($args);

                        foreach ( $orders as $order ) {
							$seller_name= array();
								  $subTotal=array();
								  $sellerQtyTotal=array();
								  $item_ids=array();
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
									$new_catalog_order=get_post_meta( $order->get_id(), 'new_catalog_order_50_percent',true);	
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Catlog") 
									  {								
                            if ($frID3 == $frID2) { 
                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id']; 

								if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
								  
                                if ($order_customer_id == $frID2) { 
								 $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Catlog") 
									  { 
										 $order_item_quantity = 0; 
										  foreach ($order->get_items() as $item_key => $item_values): 
                                         $item_id   = $item_values->get_id();
                                         $item_data = $item_values->get_data();
											$order_item_quantity += $item_data['quantity']; 										 
										 
										  
										  $s_name=wc_get_order_item_meta( $item_id, '_wdm_seller_name', true );
											/*$s_name=$item_data['_wdm_seller_display'];*/
											if(!in_array($s_name,$seller_name))
											{
												$seller_name[]=$s_name;
												//$subTotal[]=$item_data['subtotal'];
												$item_ids[]=$item_values->get_id();
											}											
										  endforeach;
										  
										  
										  for($j=0;$j<count($seller_name);$j++)
										  {
											 $seller_order_item_subtotal=0;
											$seller_order_item_quantity=0;
											  foreach ($order->get_items() as $item_key => $item_values): 
													$item_id   = $item_values->get_id();
												$item_data = $item_values->get_data();
												$s_name=wc_get_order_item_meta( $item_id, '_wdm_seller_name', true );
												if($seller_name[$j]==$s_name)
												{
													$seller_order_item_quantity += $item_data['quantity'];
													$seller_order_item_subtotal += $item_data['subtotal'];
												}
												endforeach;
												$sellerQtyTotal[]=$seller_order_item_quantity;
												$subTotal[]=$seller_order_item_subtotal;
										  }
										    $qty= $order_item_quantity; 
											  $qty_sum+=$qty;
											if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order) ) 
											{ 
												$subtot=$order->get_subtotal(); 
												$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
												$total_sale=$subtot-$to_widthdraw; 
											}  
											else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
											{ 
												$subtot=$order->get_subtotal(); 
												$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
												$total_sale=$subtot-$to_widthdraw; 
											} 
											else
											{ 
							
											}
								
								?>
                                <div class="fundraiser-order-row itemmatch">
                                    <div class="fr-order-col fr-order-id"><?php echo $order->get_id(); ?></div>
                                    <div class="fr-order-col fr-order-date"><?php echo $order_data['date_created']->date('Y-m-d'); ?></div>

                                    <div class="fr-order-col fr-order-customer"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></div>

                                    <div class="fr-order-col fr-order-seller"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
										if($slrID=="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else if(is_numeric($slrID))
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
										else
										{
											echo "N/A";
										}
									   ?></div>

                                    <div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span><span class="fr-order-amt"><?php $qty=$order_item_quantity; if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order) ) { $subtot=$order->get_subtotal(); $to_widthdraw = floatval ($subtot) * floatval (40) / 100; $dis=$subtot-$to_widthdraw; echo $dis=number_format($dis, 2, '.', ''); }  else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes") { $subtot=$order->get_subtotal(); $to_widthdraw = floatval ($subtot) * floatval (50) / 100; $dis=$subtot-$to_widthdraw; echo $dis=number_format($dis, 2, '.', ''); } else{ echo "0.00"; } ?></span></div>
                                    <div class="fr-order-col fr-order-action"><span class="details-btn" data-trigger="cat-order-<?php echo $order->get_id(); ?>-details">Expand</span></div>
                                <div id="cat-order-<?php echo $order->get_id(); ?>-details" class="fundraiser-order-details-table">
                                   <!-- <div class="fundraiser-order-details-row fr-header">
                                        <div class="fundraiser-order-details-col product-name">Product</div>
                                        <div class="fundraiser-order-details-col product-quantity">Quantity</div>
                                        <div class="fundraiser-order-details-col product-seller">Seller</div>
                                        <div class="fundraiser-order-details-col product-buyer">Buyer</div>
                                        <div class="fundraiser-order-details-col product-price">Total</div>
                                    </div>
									 <?php $order_item_quantity = 0; ?>
                                    <?php foreach ($order->get_items() as $item_key => $item_values): 
                                         $item_id   = $item_values->get_id();
                                         $item_data = $item_values->get_data(); 
											$order_item_quantity += $item_data['quantity']; 

										 ?>
                                    <div class="fundraiser-order-details-row fr-body line-item-match">
                                        <div class="fundraiser-order-details-col product-name"><?php echo $item_data['name']; ?><?php echo glow_product_type_html( $item_values ); ?></div>
                                        <div class="fundraiser-order-details-col product-quantity"><?php echo $item_data['quantity']; ?>
                                        </div>
                                        <div class="fundraiser-order-details-col product-seller"><?php echo wc_get_order_item_meta($item_id, "_wdm_seller_display", true);  ?></div>
                                        <div class="fundraiser-order-details-col product-seller"><?php echo wc_get_order_item_meta($item_id, "_wdm_buyer_name", true); ?></div>
                                        <div class="fundraiser-order-details-col product-price show">$<span><?php echo $item_t=number_format( $item_data['total'], 2); ?></span></div>
                                    </div>                                    
                                    <?php endforeach; ?>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Subtotal</div>
                                        <div class="fundraiser-order-details-col product-quantity parent-order-qty"><span><?php echo $order_item_quantity; ?></span></div>
                                        <div class="fundraiser-order-details-col product-seller"></div>
                                        <div class="fundraiser-order-details-col product-buyer"></div>
                                        <div class="fundraiser-order-details-col all-order-subtotal1">$<span><?php $subtott=$order->get_subtotal(); echo $subtott=number_format( $subtott, 2); ?></span></div>
                                    </div>
                                    <div class="fundraiser-order-details-row fr-footer">
										 <div class="fundraiser-order-details-col product-name">Order Discount</div>
										<div class="fundraiser-order-details-col product-quantity"></div>
										<div class="fundraiser-order-details-col product-seller"></div>
										<div class="fundraiser-order-details-col product-buyer"></div>
										<div class="fundraiser-order-details-col all-order-discount1">$<span><?php $qty=$order_item_quantity; if($qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ) { $subtot=$order->get_subtotal();  $to_widthdraw = (40 / 100) * $subtot; echo $to_widthdraw=number_format( $to_widthdraw, 2); }  else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ) { $subtot=$order->get_subtotal();  $to_widthdraw = (50 / 100) * $subtot; echo $to_widthdraw=number_format( $to_widthdraw, 2); } else{ echo "0.00"; } ?></span></div>
									</div> 
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Total</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                      <div class="fundraiser-order-details-col product-seller"></div>
                                        <div class="fundraiser-order-details-col product-buyer"></div>
                                        <div class="fundraiser-order-details-col product-price1">$<span><?php $qty=$order_item_quantity; if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ) { $subtot=$order->get_subtotal(); $to_widthdraw = floatval ($subtot) * floatval (40) / 100; $dis=$subtot-$to_widthdraw; echo $dis=number_format( $dis, 2); }  else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ) { $subtot=$order->get_subtotal(); $to_widthdraw = floatval ($subtot) * floatval (50) / 100;  $dis=$subtot-$to_widthdraw; echo $dis=number_format( $dis, 2);} else{ echo "0.00"; } ?></span>
                                        </div> 
									 </div>-->
									<div class="fundraiser-order-details-row fr-header">
                                        <div class="fundraiser-order-details-col product-name">Seller Name</div>
                                        <div class="fundraiser-order-details-col product-quantity">Product Quantity</div>
                                        <div class="fundraiser-order-details-col product-seller">Total Sales</div>
                                        <div class="fundraiser-order-details-col product-buyer">Glow</div>
                                        <div class="fundraiser-order-details-col product-price">Profit</div>
									</div>
										<?php 
						      /*  print_r($seller_name);
								 print_r($subTotal);*/
								 $order = new WC_Order( $order->get_id() );
								 $items = $order->get_items();
								for($i=0;$i<count($seller_name);$i++) { 
								
								/*foreach ( $items as $item_id => $item ) :
								 $item_id   = $item->get_id();*/
								/*	$s_name_meta=$item['_wdm_seller_name'];*/
								$s_name_meta=wc_get_order_item_meta( $item_ids[$i], '_wdm_seller_name', true );

								if($seller_name[$i]==$s_name_meta)
								{
								?>
								<div class="fundraiser-order-details-row fr-body line-item-match">
                                        <div class="fundraiser-order-details-col product-name"><?php echo $seller_name[$i]  ?></div>
                                        <div class="fundraiser-order-details-col product-quantity"><?php echo $sellerQtyTotal[$i]; ?>
                                        </div>
                                        <div class="fundraiser-order-details-col product-seller"><?php $subtott=$subTotal[$i]; echo '$'.$subtott=number_format( $subtott, 2); ?></div>
                                        <div class="fundraiser-order-details-col  product-seller"><?php $qty=$order_item_quantity; if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order)) { $subtot=$subTotal[$i]; $to_widthdraw = floatval ($subtot) * floatval (40) / 100; $dis=$subtot-$to_widthdraw; echo '$'.$dis=number_format( $dis, 2); }  else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes") { $subtot=$subTotal[$i]; $to_widthdraw = floatval ($subtot) * floatval (50) / 100;  $dis=$subtot-$to_widthdraw; echo '$'.$dis=number_format( $dis, 2);} else{ echo "0.00"; } ?></div>
										<div class="fundraiser-order-details-col product-price show"><span><?php $qty=$order_item_quantity; if($qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order)) { $subtot=$subTotal[$i];  $to_widthdraw = (40 / 100) * $subtot; echo '$'.$to_widthdraw=number_format( $to_widthdraw, 2); }  else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes") { $subtot=$subTotal[$i];  $to_widthdraw = (50 / 100) * $subtot; echo '$'.$to_widthdraw=number_format( $to_widthdraw, 2); } else{ echo "0.00"; } ?> </span></div>
                                        
                                </div>   
								<?php } } ?>
								
                                </div>
                                </div>
								<?php } } }
							}								
                        }
                        }
                        ?>
                         <input type="hidden" value="<?php echo  $qty_sum; ?>">   
                        </div>
                    </div>
							<?php } ?>
                </div>

				<?php get_sidebar(); ?>

			</div><!-- end row-fluid -->

		</div>
	</div><!-- end container -->
	
    <div class="mass-order-popup">
        <div class="mass-order-popup-wrapper">
            <a href="/spring-summer-catalogue-mass-order/" class="fr-btn">Spring Summer Catalogue</a>
            <a href="/digital-fall-winter-catalogue/" class="fr-btn">Digital Fall-Winter Catalogue</a>
            <a href="#" class="txt-link">Cancel</a>
        </div>
    </div>
    <div class="manual-mass-order-popup">
        <div class="manual-mass-order-popup-wrapper">
            <input type="text" name="another_email" id="another_email" placeholder="Email">
             <a  class="fr-btn send_report_other" data-fund_id="<?php echo $frID2; ?>">Send</a>
			 <a href="#" id="m-txt-link" class="txt-link">Cancel</a>
        </div>
    </div>
	<style>
		.manual-mass-order-popup {
		position: fixed;
		width: 100%;
		height: 100%;
		z-index: 999;
		background: rgba(0,0,0,0.6);
		top: 0;
		left: 0;
		display: none;
	}
	.manual-mass-order-popup .manual-mass-order-popup-wrapper {
    display: block;
    width: 100%;
    max-width: 500px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateY(-50%) translateX(-50%);
    text-align: center;
    padding: 40px 25px;
    background: #fff;
    border: 3px solid #000;
    border-radius: 5px;
}
.manual-mass-order-popup a.fr-btn {
    display: block;
    margin: 20px auto;
    width: 100%;
    max-width: 250px;
}
.send_report_another
{
	margin-left: 10px;
}
#another_email
{
	width: 100%;
	</style>
	<script>
		jQuery(document).ready(function () {
		
			jQuery(document).on('click', '.send_report_another', function (e) {
			e.preventDefault();
			jQuery(".manual-mass-order-popup").addClass("show");
	  });
	  	jQuery(document).on('click', '#m-txt-link', function (e) {
			e.preventDefault();
			jQuery(".manual-mass-order-popup").removeClass("show");
	  });
	  var ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>";
	  jQuery('.send_report').click(function(){

let fund_id = jQuery(this).data('fund_id');

         jQuery.ajax({

         type : "post",

         url : ajaxurl,

         data : {action: 'custom_fund_report_mail', fund_id:fund_id},

         success: function(response) {

        console.log(response);
		jQuery(".admin-report").append('<p style="padding-top:10px;color:green;">'+response+'</p>');
    }
});

});
jQuery('.send_report_other').click(function(){
let fund_id = jQuery(this).data('fund_id');

let a_email = jQuery("#another_email").val();
jQuery(this).text('Please Wait...');

         jQuery.ajax({

         type : "post",

         url : ajaxurl,

         data : {action: 'custom_fund_report_another_mail', a_email:a_email, fund_id:fund_id},

         success: function(response) {

        console.log(response);
		jQuery('.send_report_other').text('Send');
		jQuery(".manual-mass-order-popup").removeClass("show");
		jQuery(".admin-report").append('<p style="padding-top:10px;color:green;">'+response+'</p>');
    }
})
});
		});
</script>
<?php } ?>
<?php
	get_footer();
?>
