<?php 
/* Ajax wp_unpaid_fundraiser_ajax_record */
add_action('wp_ajax_nopriv_wp_unpaid_fundraiser_ajax_record', 'wp_unpaid_fundraiser_ajax_record'); 
add_action('wp_ajax_wp_unpaid_fundraiser_ajax_record', 'wp_unpaid_fundraiser_ajax_record');
function wp_unpaid_fundraiser_ajax_record()
{
		$ID = $_POST['search_id'];
		$check_user="";
		if(is_numeric($ID))
		{
		  $digit_data =preg_replace('/[^0-9]/', '', $ID);
		  $countLength=strlen($digit_data);
		  if($countLength==6)
		  {
			  $user_info_new = get_userdata($ID);
				if(in_array('fundraiser', (array) $user_info_new->roles ))
				{
					$check_user="yes";
				}
		  }
		}
		if($check_user=="yes"){
		$status= get_user_meta( $ID , 'user_status' , true );
		//$repre_status= get_user_meta( $ID , 'user_representative_status' , true );
		$action_status= get_user_meta( $ID , 'user_action_status' , true );
		$close_out = get_user_meta($ID, "user_close_out_value", true);
		$inactive_date=get_user_meta($ID, "user_close_out_date_inactive", true);
		$user_rebate_remainpaid = get_user_meta( $ID, 'user_rebate_remainpaid', true );
		/*if((empty($status) && $action_status==2) || ($status!=1 && $action_status==2) || ($status!=1 && $action_status==2 && $close_out==1))
		{ */
						
				/*Start: Remaining Rebate, Total Rebate, Paid Rebate - Online Profit Deduction */
					
				 $glow_profit_deduct_user_metakey_yes=get_user_meta($ID, "glow_profit_deduct_user_metakey_yes", true);
				 if($glow_profit_deduct_user_metakey_yes=="Yes"){
					 
					 if(!in_array($ID,$remove_duplicate)){
					 
					 $remove_duplicate[]= $ID;
					 $org = get_user_meta( $ID , 'user_registration_name_of_school_business' , true );
					 $action_status = get_user_meta( $ID , 'user_action_status' , true );
					 $active_date = get_user_meta($ID, "user_close_out_date_active", true);
					 $inactive_date = get_user_meta($ID, "user_close_out_date_inactive", true);
					 $status= get_user_meta( $ID,'user_status', true );
					 $user_rebate = get_user_meta( $ID, 'user_rebate', true );
				
						/*Start: Count Orders in catlog */
					     $cat_totalOrder=0;
						 $user_yes_234 = get_user_meta( $ID, 'glow_profit_deduct_user_metakey_yes', true); 
						 if($user_yes_234=="Yes")
						 {
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
									$glow_profit_deduct=get_post_meta( $order->get_id(), 'glow_profit_deduct_yes',true);
									
									if ($order->has_status('on-hold') || $order->has_status('completed') || $order->has_status('processing')) {
									  if ($frID3 == $order_customer_id) { 
										  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
										  if($order_type=="Catlog") 
										  {
											  $cat_totalOrder++;
										  }
									 }
								}
							}
						 }
				if($cat_totalOrder>1)
				{
					     $glow_profit_deduct_user_metakey_orderID = get_user_meta($ID, "glow_profit_deduct_user_metakey_orderID", true); //orderID
						 $arr_order_id2=array();
						 $total_cat_quantity =0; 						
						 $total_sale_so_far2=0;
						 $rebate_so_far2=0;		
						 $item_qty_fund_online=0;
						 $total_sale_so_far_catlog=0;
						 $profit_catlog=0;
						 $total_sales_subtot=0;
						 $total_sale_so_far_catlog_deduct=0;
						 $profit_catlog_a=0;		
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
								$glow_profit_deduct=get_post_meta( $order->get_id(), 'glow_profit_deduct_yes',true);
								
								if ($order->has_status('on-hold') || $order->has_status('completed') || $order->has_status('processing')) {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								  if($order_type=="Catlog") 
									 {
										 foreach ($order->get_items() as $item_key => $item_values): 
												$item_data = $item_values->get_data();
												$total_cat_quantity += $item_data['quantity']; 			//Catalog Order qty use in ship to school order 						
										  endforeach;	
										  
										$qty= $total_cat_quantity+$item_qty_fund_online;  
										$new_catalog_order=get_post_meta( $order->get_id(), 'new_catalog_order_50_percent',true);
										/* Start Online Deduction Calculation */
										
										if($cat_totalOrder>1)
										{
											    $glow_profit_deduct_amt=get_post_meta( $order->get_id(), 'glow_profit_deduct_metakey',true);
												$total_sale_so_far_catlog_deduct=get_post_meta( $order->get_id(), 'glow_profit_deduct_metakey_rem',true);
												$tot=$order->get_total(); 
												$shipping_cost=$order->get_shipping_total();
												if($tot==0)
												{
													if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order) ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot+=$subtot; // Total Brochure Sales
														
														$profit_catlog = floatval ($subtot) * floatval (40) / 100; 
														$profit_catlog_a += $profit_catlog;  //Profit
														
													    $sale_total_q = $subtot - $profit_catlog;   //Amt Due
														$sale_total+=$sale_total_q;
														
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total_q + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total_q;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog+=$sale_total_new; 
														
													}  
													else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot+=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (50) / 100;
														
														$profit_catlog_a += $profit_catlog;
														
														$sale_total_q = $subtot - $profit_catlog;
														$sale_total+=$sale_total_q;
														
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total_q + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total_q;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog+=$sale_total_new; 
														
													} 
													else
													{ 
									
													}
												}
												else
												{
													if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order) ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot+=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (40) / 100; 
														$profit_catlog_a += $profit_catlog;
														
														$sale_total_q = $subtot - $profit_catlog;
														$sale_total+=$sale_total_q;
														
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total_q + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total_q;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog+=$sale_total_new; 
													}  
													else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot+=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (50) / 100;
														$profit_catlog_a += $profit_catlog;
														
														$sale_total_q = $subtot - $profit_catlog;
														$sale_total+=$sale_total_q;
														
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total_q + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total_q;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog+=$sale_total_new; 
													} 
													else
													{ 
									
													}
												}
										}
								/* End Online Deduction Calculation */
										  
								} } }
						}
					    
					$glow_profit_deduct_user_metakey_orderID = get_user_meta($ID, "glow_profit_deduct_user_metakey_orderID", true); //orderID
					$glow_profit_deduct_TotalRebate = get_post_meta( $glow_profit_deduct_user_metakey_orderID, 'glow_profit_deduct_metakey_totalRebate', true); // Total fees	
						
					if($total_sale_so_far_catlog>$glow_profit_deduct_TotalRebate)	
					{
						$glow_profit_deduct_user_metakey_amount=$glow_profit_deduct_TotalRebate;
						$amt_paidNew = $glow_profit_deduct_user_metakey_amount;	
						$rem = 0.00;
					}
					else{
						$glow_profit_deduct_user_metakey_amount=$glow_profit_deduct_TotalRebate-$total_sale_so_far_catlog;
						$amt_paidNew = $total_sale_so_far_catlog;	
						$rem = $glow_profit_deduct_user_metakey_amount;	
					}
						
					
				}	   
				else
				{	
					 $glow_profit_deduct_user_metakey_orderID = get_user_meta($ID, "glow_profit_deduct_user_metakey_orderID", true); //orderID
					 $glow_profit_deduct_TotalRebate = get_post_meta( $glow_profit_deduct_user_metakey_orderID, 'glow_profit_deduct_metakey_totalRebate', true); // Total fees
					 $glow_profit_deduct_TotalRebate = number_format($glow_profit_deduct_TotalRebate, 2, '.', '');	//Use 1
					 
					 $rem=get_post_meta($glow_profit_deduct_user_metakey_orderID, "glow_profit_deduct_metakey_rem", true); //Remaining fees
					 $rem = number_format($rem, 2, '.', '');	 //Use 2
					 
					 $glow_profit_deduct_user_metakey_amount=get_user_meta($ID, "glow_profit_deduct_user_metakey_amount", true);
					 $amt_paid=abs($glow_profit_deduct_user_metakey_amount);  //paid fees
					 $amt_paidNew = number_format($amt_paid, 2, '.', '');	 //Use 3
				}
						
			}
		}
		else{
			/*Without Brochure Order */
			$amt_paid=0;
			$amt_paidNew=0;
			$glow_profit_deduct_TotalRebate=0;
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
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
						if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFiftyNEW / 100) * $total_sale_so_far2;
							$rebate_so_far2 = number_format($Discount, 2, '.', '');
							
						}
                        else
						{
                            $Discount    = ($percentageNEW / 100) * $total_sale_so_far2;
                            $rebate_so_far2 = number_format($Discount, 2, '.', '');
							
						}
						 // End Ship to school
								  
								  
		/*Total NEW Rebate with Ship to school orders */
		
		$rebateSoFar = $rebate_so_far+$rebate_so_far2;
		$glow_profit_deduct_TotalRebate = number_format($rebateSoFar, 2, '.', '');		 //use 1 -total rebate	
		if(!empty($glow_profit_deduct_TotalRebate))
		{
			$amt_paid = get_user_meta($ID,'user_rebate', true);
			if(!empty($amt_paid))
			{
				$amt_paidNew = number_format($amt_paid, 2, '.', '');	//use 3 - paid rebate
				$rem = $glow_profit_deduct_TotalRebate-$amt_paid; // use 2 - unpaid rebate
			}
			else{
				$amt_paid=0;
				$amt_paidNew = number_format($amt_paid, 2, '.', '');
				$rem=$glow_profit_deduct_TotalRebate;
			}
		}
		else{
			$amt_paidNew=0.00;
			$rem=0.00;
		}
		
		}
		/* End:  */
							
							
							
							
							$arr=array();
							$total_sale_so_far=0;
							$rebate_so_far=0;
							
							$all_seller = get_users(array(
					'meta_key'     => 'user_registration_seller_fundraiser',
					'meta_value'   => $ID,
				));
				if ( ! empty( $all_seller ) && is_iterable( $all_seller ) ) {
					foreach ( $all_seller as $seller_item ) {
						$arr[] = $seller_item->ID;
					}
				}

							$org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );

							$member=count($arr);
							$sd=get_user_meta( $ID , 'user_registration_start_date' , true );
							$ed=get_user_meta( $ID , 'user_registration_end_date' , true );
							
							$currentDate=date("Y-m-d");
							$currentDate = date('Y-m-d', strtotime($currentDate));
							$startDate = date('Y-m-d', strtotime($sd));
							$endDate = date('Y-m-d', strtotime($ed));
							
							$active_date=get_user_meta($ID, "user_close_out_date_active", true);
							$inactive_date=get_user_meta($ID, "user_close_out_date_inactive", true);
							$rebate= get_user_meta( $ID , 'user_rebate' , true );
							$new_user = get_userdata($ID);
							$first_name = $new_user->first_name;
							$last_name = $new_user->last_name;
							//$udata = get_userdata( $ID );
							//$registered = $udata->user_registered;
					
					?>
			<tr id="<?php echo "fund_".$ID; ?>" class="<?php echo $startDate; ?> <?php if($rebate_so_far>0){ echo 'payDue'; } ?>" <?php if($rebate_so_far>0){ echo 'data-rebate="'.$rebate_so_far.'"';  echo 'data-sale="'.number_format($total_sale_so_far, 2, '.', '').'"'; } ?>>
				<td id="0_" style="vertical-align: top; text-align: center; "><?php echo $ID;?></td>
				<td id="1_" style="vertical-align: top; text-align: left;width: 180px;"><?php echo $org; ?></td>
				<td id="2_" style="vertical-align: top; text-align: left;"><?php echo $first_name; ?> <?php echo $last_name; ?></td>
				<td id="4_" style="vertical-align: top; text-align: center; "><?php echo "$".$glow_profit_deduct_TotalRebate; ?></td>
				<td id="9_" style="vertical-align: top; text-align: center; "><?php echo "$".$amt_paidNew; ?></td>
				<td id="10_" style="vertical-align: top; text-align: center;font-weight:700; "><?php echo "$".$rem; ?></td>
				<!--<td id="9_" style="vertical-align: top; text-align: center; "><?php echo date( "M d, Y", strtotime( $registered )); ?></td>-->
				<td id="5_" style="vertical-align: top; text-align: center; "><span class="label" style="background-color: #4FE0CB;"> <?php if(!empty($inactive_date)) { echo $inactive_date; } else{ echo "-"; } ?></span></td>
				<td id="6_" class="<?php echo $rebate; ?>" style="vertical-align: top; text-align: center; "><?php if($status==1){?><span id="action_status_<?php echo $ID;?>" class="label label-success">Paid</span><?php }  elseif($status==2){?><span id="action_status_<?php echo $ID;?>" class="label label-danger">Unpaid</span><?php }  else if(empty($status)){ ?><span id="action_status_<?php echo $ID;?>" class="label label-danger">Unpaid</span><?php } else{ }  ?></td>
				
                <td id="8_" class="" style="vertical-align: top; text-align: center; "><?php if($action_status==1){?><span id="action_s" class="label label-success">Active</span><?php }  elseif($action_status==2){?><span id="action_s2" class="label label-danger">Inactive</span><?php }  else { ?><span id="action_st" class="label label-success">Active</span><?php } ?></td>
				<td id="77_" style="vertical-align: top; text-align: center; "><span class="label" style="background-color:#468847;"> <?php if(!empty($user_rebate_remainpaid) && $user_rebate_remainpaid=="Yes") { echo "completed"; } else { echo "-"; }  ?></span></td>
				<td id="7_" style="vertical-align: top; text-align: center; width: 240px;">
					<div class="btn-toolbar"><div class="first-col" style="margin-bottom:10px;">
					<a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>print?fund_id=<?php echo $ID;?>" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print</a>&nbsp;
					<a href="#" class="btn btn-inverse btn-mini padtop btn-custom status" data-id="<?php echo $ID;?>" data-action="<?php echo $status;?>" data-rebate="<?php echo $rebate_so_far=number_format($rebate_so_far, 2, '.', ''); ?>">Status</a>&nbsp;
					<a id="send_report" class="btn btn-inverse btn-mini padtop btn-custom" data-fund_id="<?php echo $ID; ?>">Email Report</a></div><div class="second-col">
					
					</div></div>
				</td>
			</tr>
		
			<?php /* }  */ 
}
                              
}
?>