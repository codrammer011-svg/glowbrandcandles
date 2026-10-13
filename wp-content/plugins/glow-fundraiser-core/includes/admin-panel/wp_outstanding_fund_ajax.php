<?php
/*
* Creating a function Fundraiser Outstanding Ajax
*/
add_action('wp_ajax_nopriv_fund_outstanding', 'fund_outstanding'); // for not logged in users
add_action('wp_ajax_fund_outstanding', 'fund_outstanding');
function fund_outstanding()
{
	$wp_body ='';
	ob_start();
?>
<script>
		jQuery(document).ready(function () 
		{
			
			var sale = [];
			var rebate = [];
			jQuery("#search_table .payDue").each(function(){
						  var v_rebate=jQuery(this).attr("data-rebate");
						  rebate.push(v_rebate);
						  var v_sale=jQuery(this).attr('data-sale');
						  sale.push(v_sale);
					});
					var sumRebate = 0.00;
					var sumSale=0.00;
					jQuery.each(rebate,function(){sumRebate+=parseFloat(this) || 0;});
					jQuery.each(sale,function(){sumSale+=parseFloat(this) || 0;});
					console.log(sumRebate);
					console.log(sumSale);
					jQuery("#out_F").html("Total Sale: $"+sumSale.toFixed(2)+" and Total Rebate: $"+sumRebate.toFixed(2));
						 
		});
			
</script>
<div id="search_table" class="widget-content" style="clear: both;">
		<table class="table table-bordered table-striped table-highlight responsive">
		<thead>
		  <tr>
			<th id="" style="vertical-align: top; text-align: center;">Fund ID:</th>
			<th id="" style="vertical-align: top; text-align: left;">Organization:</th>
			<th id="" style="vertical-align: top; text-align: left;">Director:</th>
			<th id="" style="vertical-align: top; text-align: center;">Members:</th>
			<th id="" style="vertical-align: top; text-align: center;">Registered:</th>
			<th id="" style="vertical-align: top; text-align: center;">Period:</th>
			<th id="" style="vertical-align: top; text-align: center;">Status:</th>
			<th id="" style="vertical-align: top; text-align: center;">Fund Status:</th>
			<th id="" style="vertical-align: top; text-align: center;">Rebate</th>
			<th id="" style="vertical-align: top; text-align: center;">Sale</th>
		 </tr>
		</thead>
		<tbody>
		<?php 
		 
		
		$args = array(
					'role'    => 'Fundraiser',
					'orderby' => 'user_registered',
					'order'   => 'DESC',
					'date_query'   => array(
					'column' => 'user_registered',
					'after' => array(
					  'year'  => 2023,
					  'month' => 1,
					  'day'   => 1
					)
				  )
				);
				$all_meta_for_users = get_users($args);
				foreach ( $all_meta_for_users as $all_meta_for_user ) {
						$count=0;
						$ID=$all_meta_for_user->ID;	
						
						$status= get_user_meta( $ID , 'user_status' , true );
					    $action_status= get_user_meta( $ID , 'user_action_status' , true );
						$close_out = get_user_meta($ID, "user_close_out_value", true);
						if((empty($status) && empty($action_status)) || ($status!=1 && $action_status!=2) || ($status!=2 && $action_status!=2) || ($status!=1 && $action_status==2 && $close_out==1))
						{
						
								$arr=array();
								$total_sale_so_far=0;
								$rebate_so_far=0;
								$total_sale_so_far2=0;
								$rebate_so_far2=0;
								$total_cat_quantity=0;
								$rebateSFAR=0;
								$totalSALE=0;
								
								 $argss = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID
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
						$rebateSFAR = $rebate_so_far+$rebate_so_far2;
						$rebateSFAR = number_format($rebateSFAR, 2, '.', '');
						
						$totalSALE = $total_sale_so_far+$total_sale_so_far2;
						$totalSALE = number_format($totalSALE, 2, '.', '');
						 // End Ship to school
						   
						   
						$all_seller = get_users(array(
					'meta_key'     => 'user_registration_seller_fundraiser',
					'meta_value'   => $ID,
				));
				if ( ! empty( $all_seller ) && is_iterable( $all_seller ) ) {
					foreach ( $all_seller as $seller_item ) {
						$arr[] = $seller_item->ID;
					}
				}
						//$org=get_user_meta( $ID , 'user_registration_input_company' , true );
						$org=get_user_meta( $ID , 'user_registration_name_of_school_business' , true );
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
						$udata = get_userdata( $ID );
						$registered = $udata ? $udata->user_registered : '';
					
					?>
					<tr id="<?php echo "fund_".$ID; ?>" class="<?php echo $startDate; ?> <?php if($rebateSFAR>0){ echo 'payDue'; } ?>" <?php if($rebateSFAR>0){ echo 'data-rebate="'.$rebateSFAR.'"';  echo 'data-sale="'.number_format((float)$totalSALE, 2, '.', '').'"'; } ?>>
						<td id="0_" style="vertical-align: top; text-align: center; "><?php echo $ID;?></td>
						<td id="1_" style="vertical-align: top; text-align: left;width: 180px;"><?php echo $org; ?></td>
						<td id="2_" style="vertical-align: top; text-align: left; "><?php echo !empty($all_meta_for_user->user_firstname) ? $all_meta_for_user->user_firstname : ''; ?> <?php echo !empty($all_meta_for_user->user_lastname) ? $all_meta_for_user->user_lastname : ''; ?></td>
						<td id="4_" style="vertical-align: top; text-align: center; "><?php echo $member; ?></td>
						<td id="9_" style="vertical-align: top; text-align: center; "><?php echo !empty($registered) ? date( "M d, Y", strtotime( $registered )) : '-'; ?></td>
						<td id="5_" style="vertical-align: top; text-align: center; "><span class="label <?php if (($currentDate >= $startDate) && ($currentDate <= $endDate)){ ?>label-success<?php } else { echo "label-danger"; } ?> "><?php echo $sd; ?> - <?php echo $ed; ?></span></td>
						<td id="6_" class="<?php echo $rebate; ?>" style="vertical-align: top; text-align: center; "><?php if($status==1){?><span id="action_status_<?php echo $ID;?>" class="label label-success">Paid</span><?php }  elseif($status==2){?><span id="action_status_<?php echo $ID;?>" class="label label-danger">Unpaid</span><?php }  else { ?><span id="action_status_<?php echo $ID;?>" class="label label-danger">Unpaid</span><?php } ?></td>
						<td id="8_" class="" style="vertical-align: top; text-align: center; "><?php if($action_status==1){?><span id="action_s" class="label label-success">Active</span><?php }  elseif($action_status==2){?><span id="action_s2" class="label label-danger">Inactive</span><?php }  else { ?><span id="action_st" class="label label-success">Active</span><?php } ?></td>
						<td id="9_" style="vertical-align: top; text-align: center; "><?php echo "$".$rebateSFAR; ?></td>
						<td id="10_" style="vertical-align: top; text-align: center; "><?php echo "$".number_format((float)$totalSALE, 2, '.', ''); ?></td>
					</tr>
		
			<?php   }   ?>
<?php   }  ?>
			
		</tbody>
		</table>
		<div id="out_F"></div>
	</div>
	<?php 
	$wp_body = ob_get_contents();
	ob_end_flush();
	die();
	}
	?>