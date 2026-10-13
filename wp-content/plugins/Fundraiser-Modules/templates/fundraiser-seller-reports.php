<?php 
	get_header();
?>
<style>
.add-seller
{
	display:none;
}
</style>
<div class="container content-page">
		<div class="page-content sidebar-position-<?php esc_attr_e( $l['sidebar'] ); ?> sidebar-mobile-<?php esc_attr_e( class_exists('WooCommerce') && ( is_checkout() || is_cart() || is_account_page() || in_array( 'woocommerce-wishlist', get_body_class() ) ) ? $l['shop-sidebar-mobile'] : $l['sidebar-mobile'] ); ?>">
			<div class="row">
                  <?php do_action( 'et_page_heading' ); ?>                  
				<div class="content <?php esc_attr_e( $l['content-class'] ); ?>">
                    <div class="intro-content"><?php the_content(); ?></div>
                    <?php $current_user = wp_get_current_user(); 
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
                    <div class="seller-selection">
                        <select class="seller-select-report" value="">
                            <option value="">All Sellers</option>
                        </select>                        
                        <h5>Total Sale so far(Online + Catalogue): <strong>$<span class="slr-report-total-sale"></span></strong></h5>
						 <h5>Total Sale so far(Ship To School): <strong>$<span class="slr-shiptoschool-sale"></span></strong></h5>
                    </div>
                    
                    <div class="fundraiser-dashboard-section report online-sale">
                        <h3>Online Sale by <span class="dynamic-seller-name">All Sellers</span></h3>
                        <h5>Online Sale so far: <strong>$<span class="fr-online-sale"></span></strong></h5>
                        <div class="fundraiser-order-table">
                            <div class="fundraiser-order-row fr-header">
                                <div class="fr-order-col fr-order-id">Order</div>
                                <div class="fr-order-col fr-order-date">Date</div>
                                <div class="fr-order-col fr-order-customer">Customer</div>
                                <div class="fr-order-col fr-order-seller">Seller</div>
                                <div class="fr-order-col fr-order-total">Total</div>
                                <div class="fr-order-col fr-order-action">Details</div>
                            </div>
                        <?php 
                            $args = array(
                                'status' => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
                            /* if ($frID3 == $frID2) {  */
                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id']; 
								if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
								/* Active/Inactive */	
								    $order_date = $order->order_date;
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
                                if ($order_customer_id != $frID2) {
									
								$order_type = get_post_meta($order->ID, 'wc_order_type', true );
								if($order_type=="Online") 
								{
								
								?>
                                <div class="fundraiser-order-row sellermatch" order-qty="<?php echo $order_data['quantity']; ?>">
                                    <div class="fr-order-col fr-order-id"><?php echo $order->ID; ?></div>
                                    <div class="fr-order-col fr-order-date"><?php echo $order_data['date_created']->date('Y-m-d'); ?></div>
                                    <div class="fr-order-col fr-order-customer"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></div>
                                    <div class="fr-order-col fr-order-seller"><?php 
                                       $slrID     = get_post_meta($order->ID, 'wc_seller_field', true );
										if($slrID=="custom")
										{
											echo get_post_meta($order->ID, 'wc_seller_field_custom', true);
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
                                    <div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span><span class="fr-order-amt"></span></div>
                                    <div class="fr-order-col fr-order-action"><span class="details-btn" data-trigger="ol-order-<?php echo $order->ID; ?>-details">Expand</span></div>
                                <div id="ol-order-<?php echo $order->ID; ?>-details" class="fundraiser-order-details-table">
                                    <div class="fundraiser-order-details-row fr-header">
                                        <div class="fundraiser-order-details-col product-name">Product</div>
                                        <div class="fundraiser-order-details-col product-quantity">Quantity</div>
                                        <div class="fundraiser-order-details-col product-price">Total</div>
                                    </div>
                                    <?php foreach ($order->get_items() as $item_key => $item_values): 
                                            $item_data = $item_values->get_data();  ?>
                                    <div class="fundraiser-order-details-row fr-body">
                                        <div class="fundraiser-order-details-col product-name"><?php echo $item_data['name']; ?></div>
                                        <div class="fundraiser-order-details-col product-quantity"><?php echo $item_data['quantity']; ?></div>
                                        <div class="fundraiser-order-details-col product-price show">$<span><?php echo $item_data['total']; ?></span></div>
                                    </div>                                    
                                    <?php endforeach; ?>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Subtotal</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col seller-order-subtotal">$<span></span></div>
                                    </div>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Discount</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col seller-order-discount1">$<span><?php $tdiscount=$order->get_total_discount(); if(!empty($tdiscount)) { echo $tdiscount=number_format($tdiscount, 2, '.', ''); } else{ echo "0.00"; } ?></span></div>
                                    </div>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Total</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col product-price">$<span></span></div>
                                    </div>
                                </div>
                                </div>
								<?php } }  
							 else if ($order_customer_id == $frID2) {
								$order_type = get_post_meta($order->ID, 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
							 ?>
                                <div class="fundraiser-order-row sellermatch" order-qty="<?php echo $order_data['quantity']; ?>">
                                    <div class="fr-order-col fr-order-id"><?php echo $order->ID; ?></div>
                                    <div class="fr-order-col fr-order-date"><?php echo $order_data['date_created']->date('Y-m-d'); ?></div>
                                    <div class="fr-order-col fr-order-customer"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></div>
                                    <div class="fr-order-col fr-order-seller"><?php 
                                       $slrID     = get_post_meta($order->ID, 'wc_seller_field', true );
										if($slrID=="custom")
										{
											echo get_post_meta($order->ID, 'wc_seller_field_custom', true);
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
                                    <div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span><span class="fr-order-amt"></span></div>
                                    <div class="fr-order-col fr-order-action"><span class="details-btn" data-trigger="ol-order-<?php echo $order->ID; ?>-details">Expand</span></div>
                                <div id="ol-order-<?php echo $order->ID; ?>-details" class="fundraiser-order-details-table">
                                    <div class="fundraiser-order-details-row fr-header">
                                        <div class="fundraiser-order-details-col product-name">Product</div>
                                        <div class="fundraiser-order-details-col product-quantity">Quantity</div>
                                        <div class="fundraiser-order-details-col product-price">Total</div>
                                    </div>
                                    <?php foreach ($order->get_items() as $item_key => $item_values): 
                                            $item_data = $item_values->get_data();  ?>
                                    <div class="fundraiser-order-details-row fr-body">
                                        <div class="fundraiser-order-details-col product-name"><?php echo $item_data['name']; ?></div>
                                        <div class="fundraiser-order-details-col product-quantity"><?php echo $item_data['quantity']; ?></div>
                                        <div class="fundraiser-order-details-col product-price show">$<span><?php echo $item_data['total']; ?></span></div>
                                    </div>                                    
                                    <?php endforeach; ?>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Subtotal</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col seller-order-subtotal">$<span></span></div>
                                    </div>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Discount</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col seller-order-discount1">$<span><?php $tdiscount=$order->get_total_discount(); if(!empty($tdiscount)) { echo $tdiscount=number_format($tdiscount, 2, '.', ''); } else{ echo "0.00"; } ?></span></div>
                                    </div>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Total</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col product-price">$<span></span></div>
                                    </div>
                                </div>
                                </div>
								<?php } }
							else
							{
								
							}
							
							
							
							
							
								} }
                        /* } */
                        }
                        ?>
                            
                        </div>
                    </div>
                    <?php
					 $current_user = wp_get_current_user(); 
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
						
						if ( !in_array( 'fundraiser', (array)  $current_user->roles ) ) {
					?>
                    <div class="fundraiser-dashboard-section report catalogue-sale">
                        <h3>Catalogue Sale</h3>
                        <h5>Mass Catalogue Sale so far: <strong>$<span class="fr-catalogue-sale"></span></strong></h5>
                        <div class="fundraiser-order-table">
                            <div class="fundraiser-order-row fr-header">
                                <div class="fr-order-col fr-order-id">Order</div>
                                <div class="fr-order-col fr-order-date">Date</div>
                                <div class="fr-order-col fr-order-customer">Customer</div>
                                <div class="fr-order-col fr-order-seller">Seller</div>
                                <div class="fr-order-col fr-order-total">Total</div>
                                <div class="fr-order-col fr-order-action">Details</div>
                            </div>
                        <?php 
						   $qty_sum=0;
                            $args = array(
                                'status' => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
                       /*     if ($frID3 == $frID2) { */
                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id']; 
								if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
                                if ($order_customer_id == $frID2) {
								$order_type = get_post_meta($order->ID, 'wc_order_type', true );
								if($order_type=="Catlog") 
							    {

								?>
                                <div class="fundraiser-order-row itemmatch">
                                    <div class="fr-order-col fr-order-id"><?php echo $order->ID; ?></div>
                                    <div class="fr-order-col fr-order-date"><?php echo $order_data['date_created']->date('Y-m-d'); ?></div>
                                    <div class="fr-order-col fr-order-customer"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></div>
                                    <div class="fr-order-col fr-order-seller"><?php 
                                        $slrID     = get_post_meta($order->ID, 'wc_seller_field', true );
										if($slrID=="custom")
										{
											echo get_post_meta($order->ID, 'wc_seller_field_custom', true);
										}
										else if(is_numeric($slrID))
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
										else
										{
											echo "N/A";
										} ?></div>
                                    <div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span><span class="fr-order-amt"><?php echo $order->get_total();   ?></span></div>
                                    <div class="fr-order-col fr-order-action"><span class="details-btn" data-trigger="cat-order-<?php echo $order->ID; ?>-details">Expand</span></div>
                                <div id="cat-order-<?php echo $order->ID; ?>-details" class="fundraiser-order-details-table">
                                    <div class="fundraiser-order-details-row fr-header">
                                        <div class="fundraiser-order-details-col product-name">Product</div>
                                        <div class="fundraiser-order-details-col product-quantity">Quantity</div>
                                        <div class="fundraiser-order-details-col product-seller">Seller</div>
                                        <div class="fundraiser-order-details-col product-buyer">Buyer</div>
                                        <div class="fundraiser-order-details-col product-price">Total</div>
                                    </div> 
                                    <?php $order_item_quantity = 0; ?>
                                    <?php foreach ($order->get_items() as $item_key => $item_values): 
                                            $item_id = $item_values->get_id();
                                            $item_data = $item_values->get_data();  
                                            $order_item_quantity += $item_data['quantity']; 
											
									?>
                                    <div class="fundraiser-order-details-row fr-body line-item-match">
                                        <div class="fundraiser-order-details-col product-name"><?php echo $item_data['name']; ?></div>
                                        <div class="fundraiser-order-details-col product-quantity num"><?php echo $item_data['quantity']; ?></div>
                                        <div class="fundraiser-order-details-col product-seller"><?php echo wc_get_order_item_meta($item_id, "_wdm_seller_display", true);  ?></div>
                                        <div class="fundraiser-order-details-col product-seller"><?php echo wc_get_order_item_meta($item_id, "_wdm_buyer_name", true); ?></div>
                                        <div class="fundraiser-order-details-col product-price show">$<span><?php echo $item_data['total']; ?></span></div>
                                    </div>                                    
                                    <?php 
									endforeach; 
									?>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Subtotal</div>
                                        <div class="fundraiser-order-details-col product-quantity parent-order-qty"><span><?php echo $order_item_quantity; ?></span></div>
                                        <div class="fundraiser-order-details-col product-seller"></div>
                                        <div class="fundraiser-order-details-col product-buyer"></div>
                                        <div class="fundraiser-order-details-col all-order-subtotal">$<span></span></div>
                                    </div>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Discount</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col product-seller"></div>
                                        <div class="fundraiser-order-details-col product-buyer"></div>
                                        <div class="fundraiser-order-details-col all-order-discount-calc">$<span></span></div>
                                    </div>
									<?php 
									  $glow_profit_deduct=get_post_meta($order->ID,'glow_profit_deduct_yes',true);
									  if($glow_profit_deduct == "Yes")
									  {
										   $glow_profit_deduct_amt=get_post_meta($order->ID,'glow_profit_deduct_metakey',true);
										  
									  ?>
									  <div class="fundraiser-order-details-row fr-footer1">
                                        <div class="fundraiser-order-details-col product-name1" style="width:25%;font-weight: 700;">Online Profits Deduction</div>
                                        <div class="fundraiser-order-details-col product-quantity1" style="width:11%"></div>
                                        <div class="fundraiser-order-details-col product-seller1"></div>
                                        <div class="fundraiser-order-details-col product-buyer1"></div>
                                        <div class="fundraiser-order-details-col product-price1" style="width:11%;font-weight: 700;">$<span><?php echo $glow_profit_deduct_amt=number_format( $glow_profit_deduct_amt, 2);    ?></span></div>
                                    </div>
									   <div class="fundraiser-order-details-row fr-footer1">
                                        <div class="fundraiser-order-details-col product-name1" style="width:25%;font-weight: 700;">Order Total</div>
                                        <div class="fundraiser-order-details-col product-quantity1" style="width:11%"></div>
                                        <div class="fundraiser-order-details-col product-seller1"></div>
                                        <div class="fundraiser-order-details-col product-buyer1"></div>
                                        <div class="fundraiser-order-details-col product-price1" style="width:11%;font-weight: 700;">$<span><?php echo $order->get_total();   ?></span></div>
                                    </div>
									  <?php }
									  else { ?>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Total</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col product-seller"></div>
                                        <div class="fundraiser-order-details-col product-buyer"></div>
                                        <div class="fundraiser-order-details-col product-price">$<span></span></div>
                                    </div>
									 <?php } ?>
                                </div>
                                </div>
								<?php 
								$qty= $order_item_quantity; 
								$qty_sum+=$qty;
								
								} }  }
                   /*     } */
                        }
                        ?>
                           <input id="cat_total_items" type="hidden" value="<?php echo  $qty_sum; ?>">      
                        </div>
                    </div>
					  <?php } ?>
					<!-- Online Ship to School Section  -->
					 <div class="fundraiser-dashboard-section report shiptoschool-sale">
                        <h3>Ship to school orders</h3>
						  <h5>Total Rebate so far: <strong>$<span class="fr-rebate-shiptoschool"></span></strong></h5>
						   <h5>Total Quantity: <strong><span class="fr-rebate-shiptoschool-qty"></span></strong></h5>
                        <div class="fundraiser-order-table">
                            <div class="fundraiser-order-row fr-header">
                                <div class="fr-order-col fr-order-id">Order</div>
                                <div class="fr-order-col fr-order-date">Date</div>
                                <div class="fr-order-col fr-order-customer">Customer</div>
                                <div class="fr-order-col fr-order-seller">Seller</div>
								<div class="fr-order-col fr-order-total">Product Sales</div>
                                
                                <div class="fr-order-col fr-order-action">Details</div>
                            </div>
                        <?php 
						$totalAmount=0;
                        $order_quantity_test=0;
						$args = array(
							 'status' => array('wc-waiting-call'),
							 'meta_key' => 'fundraiser_field',
							 'meta_value' => $ID,
							 'limit' => -1
					   );
					   $orders = wc_get_orders($args);
						

                        foreach ( $orders as $order1 ) {
							$each_order_quantity=0;
							 $order = wc_get_order( $order1->ID );
							// print_r($order);
						   $order_data = $order->get_data();
						   	$scat = get_post_meta($order->ID, 'ship_with_catalog', true );
                           $frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
						   $order_type = get_post_meta($order->ID, 'wc_order_type', true );
							if($order_type=="ship-to-school")
							{
								/* if($frID3 == $frID2) { */
					
                                $order_data = $order->get_data();
								$order_customer_id = $order_data['customer_id'];
								if ($scat==1) {
								/*Active/Inactive */
									$order_date = $order->order_date;
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
									if ($in_s==1){
										  $totalAmount += $order->get_subtotal();
										foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data(); 
												$order_type = get_post_meta($order->ID, 'wc_order_type', true );
											if($order_type=="ship-to-school") 
											{
													$order_quantity_test += $item_data['quantity']; 
													$each_order_quantity += $item_data['quantity']; 
											}
											
										endforeach; 
										
									
                                if ($order_customer_id != $frID2) {   ?>
                                <div class="fundraiser-order-row sellermatch">
                                    <div class="fr-order-col fr-order-id"><?php echo $order->ID; ?></div>
                                    <div class="fr-order-col fr-order-date"><?php echo $order_data['date_created']->date('Y-m-d'); ?></div>
                                    <div class="fr-order-col fr-order-customer"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></div>
                                    <div class="fr-order-col fr-order-seller"><?php 
                                     
									    $slrID     = get_post_meta($order->ID, 'wc_seller_field', true );
										if($slrID=="custom")
										{
											echo get_post_meta($order->ID, 'wc_seller_field_custom', true);
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
									<div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span><span class="fr-order-amt"><?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></span></div>
                                    <div class="fr-order-col fr-order-action"><span class="details-btn" data-trigger="ship-order-<?php echo $order->ID; ?>-details">Expand</span></div>

                                <div id="ship-order-<?php echo $order->ID; ?>-details" class="fundraiser-order-details-table">
                                    <div class="fundraiser-order-details-row fr-header">
                                        <div class="fundraiser-order-details-col product-name">Product</div>
                                        <div class="fundraiser-order-details-col product-quantity">Quantity</div>
                                        <div class="fundraiser-order-details-col product-price">Total</div>
                                    </div>
                                    <?php foreach ($order->get_items() as $item_key => $item_values): 
                                            $item_data = $item_values->get_data();  ?>
                                    <div class="fundraiser-order-details-row fr-body">
                                       <div class="fundraiser-order-details-col product-name"><?php echo $item_data['name']; ?></div>
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
                                        </div> 
                                    </div>
                                </div>
                                </div>
								<?php } 

								else if ($order_customer_id == $frID2) { 
								 $order_type = get_post_meta($order->ID, 'wc_order_type', true );
									  if($order_type=="ship-to-school") 
									  {
								
								?>
                                <div class="fundraiser-order-row sellermatch">
                                    <div class="fr-order-col fr-order-id"><?php echo $order->ID; ?></div>
                                    <div class="fr-order-col fr-order-date"><?php echo $order_data['date_created']->date('Y-m-d'); ?></div>
                                    <div class="fr-order-col fr-order-customer"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></div>
                                    <div class="fr-order-col fr-order-seller"><?php 
									
									    $slrID     = get_post_meta($order->ID, 'wc_seller_field', true );
										if($slrID=="custom")
										{
											echo get_post_meta($order->ID, 'wc_seller_field_custom', true);
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
                                    <div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span><span class="fr-order-amt"><?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></span></div>
                                    <div class="fr-order-col fr-order-action"><span class="details-btn" data-trigger="ship-order-<?php echo $order->ID; ?>-details">Expand</span></div>

                                <div id="ship-order-<?php echo $order->ID; ?>-details" class="fundraiser-order-details-table">
                                    <div class="fundraiser-order-details-row fr-header">
                                        <div class="fundraiser-order-details-col product-name">Product</div>
                                        <div class="fundraiser-order-details-col product-quantity">Quantity</div>
                                        <div class="fundraiser-order-details-col product-price">Total</div>
                                    </div>
                                    <?php foreach ($order->get_items() as $item_key => $item_values): 
                                            $item_data = $item_values->get_data();  ?>
                                    <div class="fundraiser-order-details-row fr-body">
                                       <div class="fundraiser-order-details-col product-name"><?php echo $item_data['name']; ?></div>
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
                                        </div> 
									</div>
                                </div>
                                </div>
								<?php } } 
								else{}


								} }
                       /* } */
						}
                        }
                        ?>
						<input id="totalAmount" type="hidden" value="<?php echo $totalAmount; ?>">
                         <input id="ship_total_items" type="hidden" value="<?php echo $order_quantity_test; ?>">   
                        </div>
                    </div>
                 
                </div>

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

<?php
	get_footer();
?>
