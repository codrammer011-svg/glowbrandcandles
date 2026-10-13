<?php

$current_user = wp_get_current_user();
	//print_r($current_user);

	$current_userID = $current_user->ID;
    if($_GET['userid']){
    	$current_userID = $_GET['userid'];
    }else{
    	$current_userID = $current_user->ID;
    }
    
    $current_email = $current_user->user_email;
    
    $fundraisers_list = get_users(array( 
 		'role__in' => array( 'fundraiser' ),
 		'meta_key'     => 'user_registration_referral_email',
 		'meta_value'   => $current_email ,
 		'meta_compare' => '='
 	));
?>
	<table>
				<tr>
					<th>Fundraisers ID</th>
					<th>Fundraisers Name</th>
					<th>Firstname</th>
					<th>Lastname</th>
					<th>Date</th>
					<th>Referral Commission</th>				
				</tr>
				<?php 
				
				foreach($fundraisers_list as $fundraisers) {
					$ID = $fundraisers->data->ID;
	            	$name = $fundraisers->data->user_login;
	            	$email = $fundraisers->data->user_email;
	            	$firstname =  get_user_meta($fundraisers->data->ID,'first_name',true);
	            	$lastname =  get_user_meta($fundraisers->data->ID,'last_name',true);

	            	$get_referral = get_user_meta($ID,'user_registration_referral_email',true);
			        $referralUser = get_user_by( 'email', $get_referral );        
					$referralUserID = $referralUser->data->ID;


			        $get_referral_commission_amount = 0;
					$totalAmount = 0;
					$total_sale_so_far_Catlog = 0;
					$total_sale_so_far = 0;


			        if(get_user_meta($referralUserID,'reps_commission',true)){
			         $get_referral_commission = get_user_meta($referralUserID,'reps_commission',true);       
			        }else{
			        $get_referral_commission = 0;
			        }

	            	$args = array(
					'status'   => "published",
					'meta_key' => 'fundraiser_field',
					'meta_value' => $ID
					);
					
					$orders = wc_get_orders($args);
					foreach ( $orders as $order ){
						
						$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
						$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
						$order_data = $order->get_data();
						$order_customer_id = $order_data['customer_id'];
						if ( $order->has_status('completed') || $order->has_status('processing')) 
						{
							/* Active/Inactive */	
							$order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
							$extract_date=$order_date;
							$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
							$inactive_s=get_user_meta($frID3, "user_action_status", true);
							$in_s=1;
							if ($inactive_date < $extract_date && $inactive_s==2){
								$in_s=2;
							}
							else if($inactive_s==1 || empty($inactive_s)){
								$in_s=1;
							}
							else{
								$in_s=1;
							}
							/*$inactive_date="2020-08-29 01:00:01";*/
							if ($in_s ==1){
								if ($frID3 != $order_customer_id){
									$tdiscount=$order->get_total_discount();
									if(!empty($tdiscount)){
										$newamt=$order->get_subtotal()-$tdiscount;
										$total_sale_so_far+=$newamt;
									}else{
										$total_sale_so_far+=$order->get_subtotal();
									}
								}else if ($frID3 == $order_customer_id){
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									if($order_type=="Online"){
										$tdiscount=$order->get_total_discount();

										if(!empty($tdiscount)){
											$newamt=$order->get_subtotal()-$tdiscount;
											$total_sale_so_far+=$newamt;
										}else{
											$total_sale_so_far+=$order->get_subtotal();
										}
									}

									if($order_type=="Catlog"){
													
										$order_item_quantity = 0; 
										foreach ($order->get_items() as $item_key => $item_values): 
											$item_id   = $item_values->get_id();
											$item_data = $item_values->get_data();
											$order_item_quantity += $item_data['quantity']; 										 
										endforeach;
										$qty= $order_item_quantity; 
										if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ){ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale_so_far_Catlog+=$subtot-$to_widthdraw; 
										}  
										else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ){ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
											$total_sale_so_far_Catlog+=$subtot-$to_widthdraw; 
										} 
										else
										{ 

										}									

									}
								}else{
								
								}
							}
						}
					
					}
					$totalAmount = $total_sale_so_far_Catlog + $total_sale_so_far;
					$get_referral_commission_amount = floatval ($totalAmount) * floatval ($get_referral_commission) / 100;
				?>
				<tr>
					<td><?php echo $ID;?></td>
					<td><?php echo $name;?></td>					
					<td><?php echo $firstname;?></td>
					<td><?php echo $lastname;?></td>
					<td>24-05-2023</td>
					<td>$<?php echo $get_referral_commission_amount;?></td>
				</tr>
			<?php } 
				
			?>
				
			</table>