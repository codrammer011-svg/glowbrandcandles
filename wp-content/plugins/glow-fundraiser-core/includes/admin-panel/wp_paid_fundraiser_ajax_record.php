<?php 
/* Ajax wp_paid_fundraiser_ajax_record */
add_action('wp_ajax_nopriv_wp_paid_fundraiser_ajax_record', 'wp_paid_fundraiser_ajax_record'); 
add_action('wp_ajax_wp_paid_fundraiser_ajax_record', 'wp_paid_fundraiser_ajax_record');
function wp_paid_fundraiser_ajax_record()
{
		$ID = $_POST['search_id'];
		$status= get_user_meta( $ID, 'user_status', true );
		if ($status==1) { 
				
				$org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );
				$total_sale_so_far=0;
				$rebate_so_far=0;
                $arr=array();
                             
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
								  if ($frID3 != $order_customer_id) {
                               /*$total_sale_so_far+=$order->get_subtotal();*/
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
                               
							  
                                   /*} */
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
								
								if ($order->has_status('on-hold') || $order->has_status('completed') || $order->has_status('processing'))  {
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
						$percentage  = 40;
                        $percentFifty= 50;
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
						if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFifty / 100) * $total_sale_so_far2;
							$rebate_so_far2 = number_format($Discount, 2, '.', '');
							
						}
                        else
						{
                            $Discount    = ($percentage / 100) * $total_sale_so_far2;
                            $rebate_so_far2 = number_format($Discount, 2, '.', '');
							
						}
						
						//Ship to School
						$total_sale_fundraiser = number_format($total_sale_so_far, 2, '.', ''); 
						$total_rebate_fundraiser = number_format($rebate_so_far, 2, '.', '');
						
						$total_sale_fundraiser2 = number_format($total_sale_so_far2, 2, '.', ''); 
						$total_rebate_fundraiser2 = number_format($rebate_so_far2, 2, '.', '');
						
						$total_sale_fundraiser3 = $total_sale_fundraiser + $total_sale_fundraiser2;
						
						$total_sale_fundraiser3 = number_format($total_sale_fundraiser3, 2, '.', '');
						
						$total_rebate_fundraiser3 = $rebate_so_far + $rebate_so_far2;
						
						$total_rebate_fundraiser3 = number_format($total_rebate_fundraiser3, 2, '.', '');
					?>
						  
					<?php if($total_sale_fundraiser3!=0 && $total_rebate_fundraiser3!=0){ ?>
					<tr style="width: 100%;padding: 0px; max-width: 1000px;margin: 0 auto;">
       
					<?php 
							$arr_order_id=array();
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
                           
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
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
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true ); //shiptoschool
								  if ($frID3 != $order_customer_id) {
										if($order_type=="Online") 
										{
								         $arr_order_id[]=$order->get_id();
										}
									} 
									else if($frID3 == $order_customer_id){
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									if($order_type=="Online") 
									{
										 $arr_order_id[]=$order->get_id();
									}
								}
								else{}
		 
							} } }
				?>
								
	<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><?php echo $ID; ?></td>
	<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><?php echo $org; ?></td>
	<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"> <?php echo count($arr_order_id);?></td>
	<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"> <?php echo $b=count($arr_order_id2); ?></td>
	<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $total_sale_so_far = number_format($total_sale_so_far, 2, '.', ''); ?></td>
	<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $total_sale_so_far2 = number_format($total_sale_so_far2, 2, '.', '');?></td>
	<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $total_sale_fundraiserNew = number_format($total_sale_fundraiser3, 2, '.', ''); ?></td>
	<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $rebate_so_far = number_format($rebate_so_far, 2, '.', ''); ?> </td>
	<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $rebate_so_far2 = number_format($rebate_so_far2, 2, '.', ''); ?></td>
	<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $total_rebate_fundraiserNew = number_format($total_rebate_fundraiser3, 2, '.', ''); ?></td>										
	<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><div class="btn-toolbar">&nbsp;<a href="?page=fundraiser_operations&view=true&fund_id=<?php echo $ID;?>" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom" style="">View</a></div>
	</td>
</tr>
	<?php } 
 } 
                              
}
?>