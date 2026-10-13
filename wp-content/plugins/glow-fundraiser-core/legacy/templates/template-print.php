<?php
/**
 * Template Name: Print
 */
 


  if ( is_user_logged_in() ) {
$ID=$_GET['fund_id'];
$u_id=$ID;
if($u_id==205821)
{
	$f="Gary";		
	$l="Margolies";								   
	$org="World Cup";
	$sd="2020-10-16";
	$ed="2020-11-30";
	$referral="Gary Margolies";
	$Participants= "400";
	$goal_amount="5000";
	$go_toward="Cheer season";  
	$selling_goal="10";
	$payable="World Cup";
	$addess=get_user_meta($u_id,'user_registration_input_box_ship',true);
	$city=get_user_meta($u_id,'user_registration_city',true);
	$state=get_user_meta($u_id,'user_registration_select_state',true);
	$zip=get_user_meta($u_id,'user_registration_input_box_zip',true);
	$phone=get_user_meta($u_id,'user_registration_input_box_phone',true);
}
else
{
   $f=get_user_meta( $u_id , 'first_name' , true );		
   $l=get_user_meta( $u_id , 'last_name' , true );								   
//   $org=get_user_meta( $u_id , 'user_registration_input_company' , true );
   $org=get_user_meta( $u_id , 'user_registration_name_of_school_business' , true );
   $sd=get_user_meta( $u_id , 'user_registration_start_date' , true );
   $ed=get_user_meta( $u_id , 'user_registration_end_date' , true );
   $referral=get_user_meta( $u_id , 'user_registration_referral_name' , true );
   $Participants= get_user_meta( $u_id , 'user_registration_participants_no' , true );
   $goal_amount=get_user_meta( $u_id , 'user_registration_fr_goal' , true );
   $go_toward=get_user_meta( $u_id , 'user_registration_fund_destination' , true );  
   $selling_goal=get_user_meta( $u_id , 'user_registration_selling_goal' , true );
   $payable=get_user_meta( $u_id , 'user_registration_check_payable' , true );
   
   if(empty($payable))
   {
	  $payable=get_user_meta($u_id,'user_registration_input_box_1693293590',true);
   }
   
   $addess=get_user_meta($u_id,'user_registration_input_box_ship',true);
   
   if(empty($addess))
   {
	  $addess=get_user_meta($u_id,'user_registration_street_address',true);
   }
   
   $city=get_user_meta($u_id,'user_registration_city',true);
   if(empty($city))
   {
	  $city=get_user_meta($u_id,'user_registration_City2',true);
   }
   $state=get_user_meta($u_id,'user_registration_select_state',true);
   $zip=get_user_meta($u_id,'user_registration_input_box_zip',true);
   $phone=get_user_meta($u_id,'user_registration_input_box_phone',true);

}
?>
 <?php
if($u_id==205821){
	
				/* rebate Total */
							$total_sale_so_far=0;
							$rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID
                            );
                            $orders = wc_get_orders($args);
							foreach ( $orders as $order ) 
							{
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
									$rebook_date="2020-10-15 06:00:41";
									if(!empty($rebook_date) && $extract_date > $rebook_date ){
									
								  if ($frID3 != $order_customer_id) 
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
											$rebate_so_far+=$Discount;
										 }
										 else
										 {
											$Discount    = ($percentage / 100) * $totalAmount;
											$Discount = number_format($Discount, 2, '.', '');
											 $rebate_so_far+=$Discount;
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
                            
						}
                        
		?>
 <style>
 address {
    margin-bottom: 20px;
    font-style: normal;
    line-height: 1.428571429;
}
.text-left
{
	float:left;
}
.text-right
{
	float:right;
	text-align:right;
}
 </style>
<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;background:#f2f2f2;width:100%;float:left;">
   <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:40px auto;width: 98%;overflow: hidden;">
        <tr>
             <td align="center" style="padding:30px; text-align:center;background: -webkit-linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);color: #000;">
				<h1 style="color:#555;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;line-height: 18px;float:left;"> Fundraiser Report </h1>
		  	    <h1 style="color:#555;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;line-height: 18px;float:right;"> Fund ID: <?php echo $ID; ?></h1>
			</td>
		</tr>
         <tr>
             <td align="left" style="padding: 30px 30px;">
			 <div class="text-left">
				<address>
							<strong>Glow Scented Candles:</strong><br>
							3702 Cavalier Street<br>
							Suite 100<br>
							Garland TX 75042<br>
							<?php echo esc_url( glow_dynamic_url_base('home') ); ?><br>
							contact@glowscentedcandles.com<br>
						
				</address>
			</div>
			<div class="text-right">
				<address>
							<strong><?php echo $org; ?></strong><br>
							<?php echo $f; ?> <?php echo $l; ?><br>
							<strong>Address</strong>: <?php echo $addess; ?><br/>
							<strong>City</strong>: <?php echo $city; ?><br/>
							<strong>State</strong>: <?php echo $state;?> <br/>
							<strong>Phone</strong>: <?php echo $phone; ?><br/>
							<strong>Zip</strong>: <?php echo  $zip; ?>
				</address>
			</div>
			</td>
		</tr>
		 <tr>
             <td align="left" style="padding: 0px 30px;">
			 <div class="text-left">
			 <?php 
			$total_sale_fundraiser= number_format($total_sale_so_far, 2, '.', ''); 
			$total_rebate_fundraiser= number_format($rebate_so_far, 2, '.', '');
			 ?>
				<address>
							<strong>Name of referral:</strong> <?php echo $referral; ?><br>
							<strong>Participants:</strong> <?php echo $Participants; ?><br>
							<strong>Period:</strong> <?php echo $sd; ?> to <?php echo $ed; ?><br>
							<strong style="color: green;">Total Sale:</strong> <span style="color: green;"><?php echo "$".$total_sale_fundraiser;?></span><br>
							<strong style="color: green;">Total Rebate:</strong><span style="color: green;"><?php echo "$".$total_rebate_fundraiser; ?></span><br>
							<strong style="color: #333;font-size: 14px;">Note: A+B+C+D+F = Total Rebate and Total Sale.</strong><br>
				</address>
			</div>
			<div class="text-right">
				<address>
							<!--<strong>Org Profit Check:</strong> <?php echo $org; ?><br>
							<strong>Fundraising Goal:</strong> <?php echo $goal_amount; ?><br>
							<strong>Funds Go Toward: </strong> <?php echo $go_toward; ?><br>
							<strong>Selling Goal (# of Items):</strong> <?php echo $selling_goal; ?><br>-->
							<strong>Checks Made Payable To:</strong> <?php echo $payable; ?><br>
						</address>
			</div>
			</td>
		</tr>
		
		<!--<tr>
		<td style="display: inline-block;margin-bottom: 0px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;padding: 18px 26px 5px;color: green;">Total Sale: <strong>$<span class="fr-total-rebate"><?php echo $total_sale_so_far_n; ?></span></strong></td>
			<td style="display: inline-block;margin-bottom: 0px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;padding: 18px 26px 5px;color: green;">Total Rebate: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far_n; ?></span></strong></td>
		</tr>-->
		 <?php
                  $arr=array();
                   $all_meta_for_users = get_users(array(
                                    'meta_key'     => 'user_registration_seller_fundraiser',
                                    'meta_value'   => $ID,
                                ));
					$registered_seller=count($all_meta_for_users);
					if($registered_seller==0)
					{
								?>
								<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
								   <td align="center">
										<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
											<tr>
												<td align="center">
													<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
														<tr>
														   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;">A: <?php echo "Registered Seller"; ?></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sale So Far: <strong>$<span class="fr-total-sale">0.00</span></strong></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Rebate So Far: <strong>$<span class="fr-total-rebate">0.00</span></strong></td>
														</tr>
														<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid red;transition: all 0.1s ease 0s;">
															<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Order</th>
															<th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Date</th>
															<th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Customer</th>
															<th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Seller</th>
															<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Product Sales</th>
															<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Rebate</th>
															<!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
										
														</tr>
													</table>
												</td>
									
											</tr>
									 
										</table>
								  
									</td>
								</tr>
							<?php								
					}
					else
					{
						$total_sale_so_far=0;
							  $rebate_so_far=0;
						 foreach ( $all_meta_for_users as $all_meta_for_user ) { $IDD=$all_meta_for_user->ID; 
						  
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID
                            );
                            $orders = wc_get_orders($args);
							foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                            if ($ID3 == $IDD) { 
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
									
									$rebook_date="2020-10-15 06:00:41";
									if(!empty($rebook_date) && $extract_date > $rebook_date ){
									
									if ($frID3 != $order_customer_id) 
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
									/*$total_sale_so_far+=$order->get_subtotal();*/
									$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
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
									$Discount_d = number_format($Discount, 2, '.', '');
									$rebate_so_far+=$Discount_d;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount_d = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount_d;
								 }
                               
							  
								} 
								} 
								} 
								
								} }
						}
						 }
					?>
					
					<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
								   <td align="center">
										<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
											<tr>
												<td align="center">
													<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:0px 30px;">
														<tr>
														   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;">A: <?php echo "Registered Seller"; ?></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_registered=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far_registered=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
														</tr>
													
													</table>
												</td>
									
											</tr>
									 
										</table>
								  
									</td>
								</tr>
					
					<?php	
					}
         foreach ( $all_meta_for_users as $all_meta_for_user ) { $IDD=$all_meta_for_user->ID; ?>
		 <tr id="<?php echo $IDD; ?>" style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  
							  $total_sale_so_far=0;
							  $rebate_so_far=0;
							    $item_qty=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID
                            );
                            $orders = wc_get_orders($args);
							foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                            if ($ID3 == $IDD) { 
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
									/* total Item  Sold */
										foreach ($order->get_items() as $item_key => $item_values): 
                                            $item_data = $item_values->get_data(); 
											$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
											if($order_type=="Online") 
											{
												$item_qty  +=  $item_data['quantity']; 
											}
											else if($order_type=="") 
											{
												$item_qty  +=  $item_data['quantity']; 
											}
											else{}
																								
                                        endforeach; 
										
									/* total Item  Sold */
									
									$rebook_date="2020-10-15 06:00:41";
									if(!empty($rebook_date) && $extract_date > $rebook_date ){
								  if ($frID3 != $order_customer_id) 
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
									/*$total_sale_so_far+=$order->get_subtotal();*/
									$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
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
									$Discount_d = number_format($Discount, 2, '.', '');
									$rebate_so_far+=$Discount_d;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount_d = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount_d;
								 }
                               
							  
								} } } } }
						} ?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;"><?php echo $all_meta_for_user->user_firstname; ?> <?php echo $all_meta_for_user->user_lastname; ?></td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_registered=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far_registered=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							    <tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sold Items: <strong><span class="fr-total-rebate"><?php echo $item_qty; ?></span></strong></td>
								</tr>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid red;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == $IDD) { 

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
									$rebook_date="2020-10-15 06:00:41";
									if(!empty($rebook_date) && $extract_date > $rebook_date ){
								  if ($frID3 != $order_customer_id) { 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid red;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        $slr_info  = get_userdata($slrID);
                                       ?>
                                       <?php echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">$
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

                                    /*$totalAmount = $order->get_subtotal();*/
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
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{ */
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS) 
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
								 }
                                 else 
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
								 }
                             /*  }*/
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
								<?php  }  } } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		   <?php } 
                              ?>
		 <tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID
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
									$rebook_date="2020-10-15 06:00:41";
									if(!empty($rebook_date) && $extract_date > $rebook_date ){
								  if ($frID3 != $order_customer_id) { 
								  
                            if ($ID3 == "custom") {
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
                              /* $total_sale_so_far+=$order->get_subtotal();*/
							 $total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									
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
                               
							  
								} } } } }
						}
						
						
						
						  $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID
                            );
                            $orders = wc_get_orders($args);
							
                        foreach ( $orders as $order ) 
						{
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") 
							{ 

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
									$rebook_date="2020-10-15 06:00:41";
									if(!empty($rebook_date) && $extract_date > $rebook_date ){
										 if ($frID3 != $order_customer_id) 
										 {
												$slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
												if($slrID=="custom")
												{
													$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
													$s_name_lower= strtolower($s_name);
													if(!in_array($s_name_lower,$seller_name))
													{
														$seller_name[]=strtolower($s_name);
													}
												}
											 
										 }
									}
									}
									}
							}
						}
						/* Calculate seller Total*/
							$args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID
                            );
                            $orders = wc_get_orders($args);
							
						for($i=0;$i<count($seller_name);$i++) 
						{
						$seller_rebate=0;
							$seller_total_sale=0;
								$item_qty_manual=0;
                        foreach ( $orders as $order ) 
						{
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") 
							{ 

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
									$rebook_date="2020-10-15 06:00:41";
									if(!empty($rebook_date) && $extract_date > $rebook_date ){
										 if ($frID3 != $order_customer_id) 
										 {
												$slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
												
													$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
													$s_name_lower= strtolower($s_name);
													if($seller_name[$i] == $s_name_lower) 
													{
														  foreach ($order->get_items() as $item_key => $item_values): 
															$item_data = $item_values->get_data();  
															$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type=="Online") 
															{
																$item_qty_manual +=  $item_data['quantity']; 
															}
															else if($order_type=="") 
															{
																$item_qty_manual +=  $item_data['quantity']; 
															}
															else{}
															endforeach; 
															$total_product = $order->get_total();   ///$order_data->get_total();


															$percentage  = 40;
															$percentFifty= 50;

															/*$totalAmount = $order->get_subtotal();*/
										
															$tdiscount=$order->get_total_discount();
										
															if(!empty($tdiscount))
															{
															  $newamt=$order->get_subtotal()-$tdiscount;
															  $totalAmount=$newamt;
															}
															else
															{
															 $totalAmount=$order->get_subtotal();
															}													/*   if ( $order_customer_id != $ID  ) { //
														 $totalAmt    = ($percentage / 100) * $totalAmount;
														   $Discount = number_format($totalAmt, 2, '.', '');
													   }
													   else{*/
														 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
														 {
															$Discount    = ($percentFifty / 100) * $totalAmount;
															$Discount = number_format($Discount, 2, '.', '');
														 }
														 else
														 {
															$Discount    = ($percentage / 100) * $totalAmount;
															$Discount = number_format($Discount, 2, '.', '');
															
														 }
													  /* }*/
													   $seller_rebate+=$Discount;
													   $seller_total_sale+=$totalAmount;
														
													}
												
											 
										 }
								} } }
							}
						}
						$seller_total_Sale_array[]=$seller_total_sale;
						$seller_rebate_array[]=$seller_rebate;
						$item_qty_arr[]=$item_qty_manual;
						}
						?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:blue;">B: Manually Added Seller</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $tt=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rr= number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								
							<?php
								$exist_seller=count($seller_name);
								if($exist_seller==0){
								?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid blue;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								<?php
								}
								?>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID
                            );
                            $orders = wc_get_orders($args);
							for($i=0;$i<count($seller_name);$i++)
							{ ?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 18px;margin-top:15px;color:blue;"><?php echo $seller_name[$i]; ?></td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $seller_t=number_format($seller_total_Sale_array[$i], 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $seller_r=number_format($seller_rebate_array[$i], 2, '.', ''); ?></span></strong></td>
								</tr>
						        <tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Sold Items: <strong><span class="fr-total-rebate"><?php echo $item_qty_arr[$i]; ?></span></strong></td>
								</tr>
							<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid blue;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								<?php
						
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") { 

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
									$rebook_date="2020-10-15 06:00:41";
									if(!empty($rebook_date) && $extract_date > $rebook_date ){
							     if ($frID3 != $order_customer_id) { 
								 
								 $s_name_meta=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
								 if($seller_name[$i] == strtolower($s_name_meta)) {
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid blue;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if($slrID=="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
											
										}
										else
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">$
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

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{ */
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
								 }
                             /*  } */
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
								<?php }  } } } } }
                        }
							}
                        ?>
						
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		<!-- Seller not mentioned-->
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  
								$total_sale_so_far=0;
								$rebate_so_far=0;
								$item_qty_not_found=0;
								$args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID
								);
								$orders = wc_get_orders($args);
								foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								if($order_type=="Online")
								{
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
									
									if ($in_s ==1){
									$rebook_date="2020-10-15 06:00:41";
									if(!empty($rebook_date) && $extract_date > $rebook_date ){
										if ($frID3 != $order_customer_id) { 
											if(empty($ID3) || $ID3=="No-seller") {
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
												$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

												foreach ($order->get_items() as $item_key => $item_values): 
													$item_data = $item_values->get_data();  
													$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
													if($order_type=="Online") 
													{
														$item_qty_not_found +=  $item_data['quantity']; 
													}
													else if($order_type=="") 
													{
														$item_qty_not_found +=  $item_data['quantity']; 
													}
													else{}
												endforeach; 
                                     
                                    $total_product = $order->get_total();   
									$percentage  = 40;
                                    $percentFifty= 50;
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
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
                       } } } } } }
						}  ?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:purple;">C: Seller Not Mentioned</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:purple;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_seller_not=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:purple;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far_seller_not=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							    <tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:purple;">Total Sold Items: <strong><span class="fr-total-rebate"><?php echo $item_qty_not_found; ?></span></strong></td>
								</tr>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid purple;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Order</th>
									<th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Date</th>
									<th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Customer</th>
									<th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Seller</th>
									<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Product Sales</th>
									<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Rebate</th>
                               </tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID
                            );
                            $orders = wc_get_orders($args);
							foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
								$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								if($order_type=="Online")
								{
									if(empty($ID3) || $ID3=="No-seller") { 

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
									
									if ($in_s ==1){
										$rebook_date="2020-10-15 06:00:41";
										if(!empty($rebook_date) && $extract_date > $rebook_date ){
										if ($frID3 != $order_customer_id) { 
								 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid purple;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                       if(empty($ID3) || $ID3=="No-seller") 
										{
											echo "N/A";
										}
										else
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
									$total_product = $order->get_total();   ///$order_data->get_total();

									$percentage  = 40;
                                    $percentFifty= 50;
									$tdiscount=$order->get_total_discount();
									if(!empty($tdiscount))
									{
										$newamt=$order->get_subtotal()-$tdiscount;
										$totalAmount=$newamt;
									}
									else
									{
										$totalAmount=$order->get_subtotal();
									}
                             
									if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
									{
										$Discount    = ($percentFifty / 100) * $totalAmount;
										$Discount = number_format($Discount, 2, '.', '');
									}
									else
									{
										$Discount    = ($percentage / 100) * $totalAmount;
										$Discount = number_format($Discount, 2, '.', '');
									}
                              
                               echo $Discount;?> </td>
									
								</tr>
							<?php  
								} } } } } }
							}
							?>
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		
		
			<!-- Fundraiser Online Sales-->
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
							   $item_qty_fund_online=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID
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
									$rebook_date="2020-10-15 06:00:41";
									if(!empty($rebook_date) && $extract_date > $rebook_date ){
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
                            if ($order_type=='Online') {

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
										$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

                                        foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type_2=="Online") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															else if($order_type_2=="") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															else{}
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
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
                               
							  
								} } } } }
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:#f54298;">D: Fundraiser Online Sales</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#f54298;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_seller_not=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#f54298;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far_seller_not=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								 <tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#f54298;">Total Sold Items: <strong><span class="fr-total-rebate"><?php echo $item_qty_fund_online; ?></span></strong></td>
								</tr>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #f54298;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
							
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							 $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
							  $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
                             if ($frID3 == $order_customer_id)  
							 {

                               
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
									$rebook_date="2020-10-15 06:00:41";
									if(!empty($rebook_date) && $extract_date > $rebook_date ){
							     if ($order_type=='Online') { 
								 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #f54298;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if(empty($slrID))
										{
											echo "N/A";
										}
										else if($slrID =="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else
										{
											$slr_info  = get_userdata($slrID);
											echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">$
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

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{*/
							   
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
								 }
                               /*} */
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
								<?php  } } } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		<!--catalog sales -->
		
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							  
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
										if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale_so_far+=$subtot-$to_widthdraw; 
										}  
										else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
											$total_sale_so_far+=$subtot-$to_widthdraw; 
										} 
										else
										{ 
						
										}
										

								 }
							
							} }
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:#a84032;">E: Fundraiser Catalogue Sales</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#a84032;">Total Sales So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_seller_not=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #a84032;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Customer</th>
                                <!--<th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Seller</th>-->
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Product Sales</th>
                                <!--<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Rebate</th>-->
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
								  
								  
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
							$seller_name= array();
								  $subTotal=array();
								  $sellerQtyTotal=array();
								  $item_ids=array();
								/*	foreach ( $order->get_items() as $item_id => $item ) :
									$item_t = $item->get_data();
									
									 $item_id   = $item->get_id();
									$s_name=wc_get_order_item_meta( $item_id, '_wdm_seller_name', true );
									if(!in_array($s_name,$seller_name))
									{
										$seller_name[]=$s_name;
										$subtot[]=$item_t['subtotal'];
									}
									
									endforeach; */

                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							 $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
							
							    $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
                             if ($frID3 == $order_customer_id)  
							 {
								
                              
									if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
							     if ($order_type=='Catlog') { 
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
										if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale=$subtot-$to_widthdraw; 
										}  
										else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
											$total_sale=$subtot-$to_widthdraw; 
										} 
										else
										{ 
						
										}
								 
                                  ?>
								<tr class="orderClass" style="display: flex;flex-flow: wrap; border-bottom: 1px solid #a84032;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if(empty($slrID))
										{
											echo "N/A";
										}
										else if($slrID =="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else
										{
											$slr_info  = get_userdata($slrID);
											echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>-->
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">$<?php  echo $total_sale=number_format( $total_sale, 2); ?></td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">$
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

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{*/
							   
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
								 }
                               /*} */
                               echo $Discount;?> </td>-->
							   </tr>
							   <tr>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #a84032;transition: all 0.1s ease 0s;">
									<th style="text-align: left;width: 12%;padding: 10px 5px;font-size: 14px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Seller Name</th>
									<th style="text-align: left;width: 12%;padding: 10px 5px;font-size: 14px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Product Quantity</th>
									<th style="text-align: left;width: 12%;padding: 10px 5px;font-size: 14px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Total Sales</th>
									<th style="text-align: left;width: 12%;padding: 10px 5px;font-size: 14px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Glow</th>
									<th style="text-align: left;width: 12%;padding: 10px 5px;font-size: 14px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Profit</th>
								</tr>
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
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #a84032;transition: all 0.1s ease 0s;">
								 <td style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $seller_name[$i]  ?></td>
								 <td style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $sellerQtyTotal[$i]; ?></td>
								    <td style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php $subtott=$subTotal[$i]; echo '$'.$subtott=number_format( $subtott, 2); ?></td>
									 <td style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php $qty=$order_item_quantity; if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ) { $subtot=$subTotal[$i]; $to_widthdraw = floatval ($subtot) * floatval (40) / 100; $dis=$subtot-$to_widthdraw; echo '$'.$dis=number_format( $dis, 2); }  else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ) { $subtot=$subTotal[$i]; $to_widthdraw = floatval ($subtot) * floatval (50) / 100;  $dis=$subtot-$to_widthdraw; echo '$'.$dis=number_format( $dis, 2);} else{ echo "0.00"; } ?></td>
									<td style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php $qty=$order_item_quantity; if($qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ) { $subtot=$subTotal[$i];  $to_widthdraw = (40 / 100) * $subtot; echo '$'.$to_widthdraw=number_format( $to_widthdraw, 2); }  else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ) { $subtot=$subTotal[$i];  $to_widthdraw = (50 / 100) * $subtot; echo '$'.$to_widthdraw=number_format( $to_widthdraw, 2); } else{ echo "0.00"; } ?></td>
									
									 
									
								</tr>
								<?php } 
								
								/* endforeach; */

								?>
								<?php } ?>
							
								</tr>
								
							<?php  } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		
		
    </table>
	
    </div>
 
<?php	 
 }
 else {
 
	/* rebate Total */
							$total_sale_so_far=0;
							$rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							foreach ( $orders as $order ) 
							{
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
								} }
                            
						}
						
						 /* Ship to School Total Sales and Rebate */
						 
						/* Calculate Catlog Quantity */
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
						 
						  /* Start Ship to school qty calculation */
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
						 
						 /* End Ship to school qty calculation */
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
										if($glow_profit_deduct=="Yes" && $cat_totalOrder==1)
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
														$total_sales_subtot=$subtot; // Total Brochure Sales
														
														$profit_catlog = floatval ($subtot) * floatval (40) / 100; 
														$profit_catlog_a = $profit_catlog;  //Profit
														
													    $sale_total = $subtot - $profit_catlog;   //Amt Due
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
														
													}  
													else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (50) / 100;
														
														$profit_catlog_a = $profit_catlog;
														
														$sale_total = $subtot - $profit_catlog;
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
														
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
														$total_sales_subtot=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (40) / 100; 
														$profit_catlog_a = $profit_catlog;
														
														$sale_total = $subtot - $profit_catlog;
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
													}  
													else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (50) / 100;
														
														$profit_catlog_a = $profit_catlog;
														
														$sale_total = $subtot - $profit_catlog;
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
													} 
													else
													{ 
									
													}
												}
										}
										// Order contain no deduction
										if(empty($glow_profit_deduct) && $cat_totalOrder==1)
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
														$total_sales_subtot=$subtot; // Total Brochure Sales
														
														$profit_catlog = floatval ($subtot) * floatval (40) / 100; 
														$profit_catlog_a = $profit_catlog;  //Profit
														
													    $sale_total = $subtot - $profit_catlog;   //Amt Due
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
														
													}  
													else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (50) / 100;
														
														$profit_catlog_a = $profit_catlog;
														
														$sale_total = $subtot - $profit_catlog;
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
														
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
														$total_sales_subtot=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (40) / 100; 
														$profit_catlog_a = $profit_catlog;
														
														$sale_total = $subtot - $profit_catlog;
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
													}  
													else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (50) / 100;
														
														$profit_catlog_a = $profit_catlog;
														
														$sale_total = $subtot - $profit_catlog;
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
													} 
													else
													{ 
									
													}
												}
										}
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
							   
						/* shiptogroup profit */	   
						$percentage  = 40;
                        $percentFifty= 50;
						$shiptogroup_profit="";
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
						if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFifty / 100) * $total_sale_so_far2;
							$rebate_so_far2 = number_format($Discount, 2, '.', '');
							if($rebate_so_far2>0)
							{
								$shiptogroup_profit = $percentFifty;
							}
						}
                        else
						{
                            $Discount    = ($percentage / 100) * $total_sale_so_far2;
                            $rebate_so_far2 = number_format($Discount, 2, '.', '');
							if($rebate_so_far2>0)
							{
								$shiptogroup_profit = $percentage;
							}
						}
						 // End Ship to school
                        
		?>
 <style>
 address {
    margin-bottom: 20px;
    font-style: normal;
    line-height: 1.428571429;
}
.text-left
{
	float:left;
}
.text-right
{
	float:right;
	text-align:right;
}
 </style>
<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;background:#f2f2f2;width:100%;float:left;">
   <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:40px auto;width: 98%;overflow: hidden;">
        <tr>
             <td align="center" style="padding:30px; text-align:center;background: -webkit-linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);color: #000;">
				<h1 style="color:#555;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;line-height: 18px;float:left;"> Fundraiser Report </h1>
		  	    <h1 style="color:#555;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;line-height: 18px;float:right;"> Fund ID: <?php echo $ID; ?></h1>
			</td>
		</tr>
         <tr>
             <td align="left" style="padding: 30px 30px;">
			 <div class="text-left">
				<address>
							<strong>Glow Scented Candles:</strong><br>
							3702 Cavalier Street<br>
							Suite 100<br>
							Garland TX 75042<br>
							<?php echo esc_url( glow_dynamic_url_base('home') ); ?><br>
							contact@glowscentedcandles.com<br>
							
				</address>
			</div>
			<div class="text-right">
				<address>
							<strong><?php echo $org; ?></strong><br>
							<?php echo $f; ?> <?php echo $l; ?><br>
							<strong>Address</strong>: <?php echo $addess; ?><br/>
							<strong>City</strong>: <?php echo $city; ?><br/>
							<strong>State</strong>: <?php echo $state;?> <br/>
							<strong>Phone</strong>: <?php echo $phone; ?><br/>
							<strong>Zip</strong>: <?php echo  $zip; ?>
				</address>
			</div>
			</td>
		</tr>
		<?php 

		$user_yes = get_user_meta( $ID, 'glow_profit_deduct_user_metakey_yes', true); 
		if($user_yes=="Yes")
		{
?>
		 <tr>
             <td align="left" style="padding: 0px 30px;">
			  <div class="text-left">
			 <?php 
			 //Ship to School
			 $total_sale_fundraiser = number_format($total_sale_so_far, 2, '.', ''); 
			$total_rebate_fundraiser = number_format($rebate_so_far, 2, '.', '');
			
			$total_sale_fundraiser2 = number_format($total_sale_so_far2, 2, '.', ''); 
			$total_rebate_fundraiser2 = number_format($rebate_so_far2, 2, '.', '');
			
			$total_sale_fundraiser3 = $total_sale_fundraiser + $total_sale_fundraiser2;
			
			$total_sale_fundraiser3 = number_format($total_sale_fundraiser3, 2, '.', '');
			
			$total_rebate_fundraiser3 = $rebate_so_far + $rebate_so_far2;
			
			$total_rebate_fundraiser3 = number_format($total_rebate_fundraiser3, 2, '.', '');
			
			$amt_paid = get_user_meta( $ID, 'glow_profit_deduct_user_metakey_amount', true);
			
			$amt_paidNew=abs($amt_paid);
			$amt_paidNew = number_format($amt_paidNew, 2, '.', '');
			$amt_remaining=get_user_meta( $ID,'user_rebate_remain_glowProfitDeduct', true);
			$amt_remaining = number_format($amt_remaining, 2, '.', '');
			
		//	$amt_due=$total_sale_so_far_catlog-$profit_catlog;
		//	$amt_due = number_format($amt_due, 2, '.', '');
	    	$total_sale_so_far_catlog_deduct=number_format($total_sale_so_far_catlog_deduct, 2, '.', '');
			$profit_catlog = number_format($profit_catlog, 2, '.', '');
			$total_sale_so_far_catlog = number_format($total_sale_so_far_catlog, 2, '.', '');
			$curr_balance=0;
		
			$curr_balance=$total_sale_so_far_catlog-$total_rebate_fundraiser3;
			$c_symbole="";
			if($total_rebate_fundraiser3>$total_sale_so_far_catlog)
			{
				$c_symbole = "-";
			}
			//$curr_balance=get_user_meta( $ID,'user_rebate_remain_glowProfitDeduct', true);
			$curr_balance=abs($curr_balance);
			$curr_balance = number_format($curr_balance, 2, '.', '');
			$total_sales_subtot = number_format($total_sales_subtot, 2, '.', '');
			$profit_catlog_a = number_format($profit_catlog_a, 2, '.', '');
			$sale_total = number_format($sale_total, 2, '.', ''); 
			if($shipping_cost>0){
			$total_amt_due=$sale_total+$shipping_cost;
			}
			/* 3 Digits code */
			if( $total_sales_subtot > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_sales_subtot_NEW = preg_replace($pattern, $replace, $total_sales_subtot);
			}
			if( $profit_catlog_a > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$profit_catlog_a_NEW = preg_replace($pattern, $replace, $profit_catlog_a);
			}
			if( $sale_total > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$sale_total_NEW = preg_replace($pattern, $replace, $sale_total);
			}	
			if( $total_amt_due > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_amt_due = preg_replace($pattern, $replace, $total_amt_due);
			}
			/* Ship-to-Home */
			if( $total_sale_fundraiser > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_sale_fundraiser_NEW = preg_replace($pattern, $replace, $total_sale_fundraiser);
			}
			$rebate_so_far=number_format($rebate_so_far, 2, '.', '');
			if( $rebate_so_far > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$rebate_so_far_NEW = preg_replace($pattern, $replace, $rebate_so_far);
			}
			// Ship-to-Group
			
			if( $total_sale_fundraiser2 > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_sale_fundraiser2_NEW = preg_replace($pattern, $replace, $total_sale_fundraiser2);
			}
			$rebate_so_far2=number_format($rebate_so_far2, 2, '.', '');
			if( $rebate_so_far2 > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$rebate_so_far2_NEW = preg_replace($pattern, $replace, $rebate_so_far2);
			}
			/*Catlog*/
			if( $total_sale_so_far_catlog > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_sale_so_far_catlog_NEW = preg_replace($pattern, $replace, $total_sale_so_far_catlog);
			}
			if( $total_rebate_fundraiser3 > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_rebate_fundraiser3_NEW = preg_replace($pattern, $replace, $total_rebate_fundraiser3);
			}
			if( $curr_balance > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$curr_balance = preg_replace($pattern, $replace, $curr_balance);
			}
			
			//End Ship to school
			 ?>
				<address>
							<strong>Name of referral:</strong> <?php echo $referral; ?><br>
							<strong>Participants:</strong> <?php echo $Participants; ?><br>
							<strong>Fundraiser Dates:</strong> <?php echo $sd; ?> to <?php echo $ed; ?><br><br>
							<strong style="color: #a84032;">Brochure Sales:</strong><br>
							<strong style="color: #a84032;">Total Brochure Sales:</strong> <span style="color: #a84032;"><?php if( $total_sales_subtot > 999) { echo "$".$total_sales_subtot_NEW; } else { echo "$".$total_sales_subtot; } ?></span><br>
							<strong style="color: #a84032;">Profit: </strong><span style="color: #a84032;"><?php if( $profit_catlog_a > 999) { echo "$".$profit_catlog_a_NEW; } else{ echo "$".$profit_catlog_a; } ?></span><br>
							<strong style="color: #a84032;">Amt Due: </strong><span style="color: #a84032;">

								<?php 


								$user = wp_get_current_user();
  						if(in_array( 'fundraiser', (array) $user->roles )  )
 			{
 				$pattern = "/\B(?=(\d{3})+(?!\d))/";
									$replace = ",";


if( $profit_catlog_a > 999){

$profit_catlog_a_NEW = str_replace( ',', '', $profit_catlog_a_NEW );

}else{
	$profit_catlog_a_NEW = str_replace( ',', '', $profit_catlog_a );
}

									//$profit_catlog_a_NEW = str_replace( ',', '', $profit_catlog_a_NEW );

									$amt_my = $profit_catlog_a_NEW - $total_rebate_fundraiser3 ;
									echo "$".$amt_my;
 			}
								// if( $sale_total > 999) {

								//  echo "$".$sale_total_NEW; 
								// } 
								// else{
								//  echo "$".$sale_total; 

								// } 
								// if($shipping_cost>0){
								//  echo " + $".$shipping_cost." (Shipping) = $".$total_amt_due; 
								// } 
							?>
								
							</span><br>
						
				</address>
			</div>
			<div class="text-right">
				<address>
							<!--<strong>Org Profit Check:</strong> <?php echo $org; ?><br>
							<strong>Fundraising Goal:</strong> <?php echo $goal_amount; ?><br>
							<strong>Funds Go Toward: </strong> <?php echo $go_toward; ?><br>
							<strong>Selling Goal (# of Items):</strong> <?php echo $selling_goal; ?><br>-->	
							<strong>Checks Made Payable To:</strong> <?php echo $payable; ?><br>
						</address>
			</div>
			</td>
		</tr>
		<tr>
		<td align="left" style="padding: 0px 30px;">
			 <div class="text-left">
			<address>
							<strong style="color: red;">Online Sales</strong><br>
							<strong style="color: red;">Total Online Sales Ship-to-Home:</strong> <span style="color: red;"><?php if( $total_sale_fundraiser > 999) { echo "$".$total_sale_fundraiser_NEW; } else{ echo "$".$total_sale_fundraiser; } ?></span><br>
							<strong style="color: red;">Profit (40%): </strong><span style="color: red;"><?php  if( $rebate_so_far > 999) { echo "$".$rebate_so_far_NEW; } else{ echo "$".$rebate_so_far; } ?></span><br><br>
							
							<strong style="color: #ff5722;">Online Sales Ship-to-Group: </strong><span style="color: #ff5722;"><?php if( $total_sale_fundraiser2 > 999) { echo "$".$total_sale_fundraiser2_NEW; } else{ echo "$".$total_sale_fundraiser2; } ?></span><br>
							<strong style="color: #ff5722;">Profit: <?php if($shiptogroup_profit==40){ echo "40%"; }  else if($shiptogroup_profit==50){ echo "50%"; } else{ echo "(40% or 50%)"; } ?></strong><span style="color: #ff5722;"></span><br>
							<strong style="color: #ff5722;">Total Online Sales Profit: </strong><span style="color: #ff5722;"><?php if( $rebate_so_far2 > 999) { echo "$".$rebate_so_far2_NEW; } else{  echo "$".$rebate_so_far2; } ?></span><br>
			</address>
			<address>
							<strong style="color: green;">Payments and Credits</strong><br>
							<strong style="color: green;">Total charges (includes brochure sales invoice):</strong> <span style="color: green;"><?php if( $total_sale_so_far_catlog > 999) { echo "$".$total_sale_so_far_catlog_NEW; } else {  echo "$".$total_sale_so_far_catlog; } ?></span><br>
							<strong style="color: green;">Online Store Credits: </strong><span style="color: green;"><?php if( $total_rebate_fundraiser3 > 999) { echo "$".$total_rebate_fundraiser3_NEW; } else { echo "$".$total_rebate_fundraiser3; } ?></span><br>
							<strong style="color: green;">Current Balance: </strong><span style="color: green;"><?php echo $c_symbole."$".$curr_balance; ?></span><br>
							<?php 
							$total_profit=$profit_catlog_a+$rebate_so_far+$rebate_so_far2; 
							$total_profit=number_format($total_profit, 2, '.', '');
							if( $total_profit > 999) {
								$pattern = "/\B(?=(\d{3})+(?!\d))/";
								$replace = ",";
								$total_profit_new = preg_replace($pattern, $replace, $total_profit);
							}
							?>
							<strong style="color: green;">YOUR GLOW TOTAL PROFITS: </strong><span style="color: green;"><?php if( $total_profit > 999) { echo "$".$total_profit_new; } else{ echo "$".$total_profit; }  ?></span><br>
			</address>
			</div>
			<div class="text-right">
				
			</div>
			</td>
		</tr>
		<?php }
		else
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
							foreach ( $orders as $order ) 
							{
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
								} }
                            
						}
						
						 /* Ship to School Total Sales and Rebate */
						 
						/* Calculate Catlog Quantity */
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
						 
						  /* Start Ship to school qty calculation */
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
						 
						 /* End Ship to school qty calculation */
						 $cat_totalOrder=0;
						
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
										if($glow_profit_deduct=="Yes" && $cat_totalOrder==1)
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
														$total_sales_subtot=$subtot; // Total Brochure Sales
														
														$profit_catlog = floatval ($subtot) * floatval (40) / 100; 
														$profit_catlog_a = $profit_catlog;  //Profit
														
													    $sale_total = $subtot - $profit_catlog;   //Amt Due
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
														
													}  
													else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (50) / 100;
														
														$profit_catlog_a = $profit_catlog;
														
														$sale_total = $subtot - $profit_catlog;
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
														
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
														$total_sales_subtot=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (40) / 100; 
														$profit_catlog_a = $profit_catlog;
														
														$sale_total = $subtot - $profit_catlog;
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
													}  
													else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (50) / 100;
														
														$profit_catlog_a = $profit_catlog;
														
														$sale_total = $subtot - $profit_catlog;
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
													} 
													else
													{ 
									
													}
												}
										}
										// Order contain no deduction
										if(empty($glow_profit_deduct) && $cat_totalOrder==1)
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
														$total_sales_subtot=$subtot; // Total Brochure Sales
														
														$profit_catlog = floatval ($subtot) * floatval (40) / 100; 
														$profit_catlog_a = $profit_catlog;  //Profit
														
													    $sale_total = $subtot - $profit_catlog;   //Amt Due
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
														
													}  
													else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (50) / 100;
														
														$profit_catlog_a = $profit_catlog;
														
														$sale_total = $subtot - $profit_catlog;
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
														
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
														$total_sales_subtot=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (40) / 100; 
														$profit_catlog_a = $profit_catlog;
														
														$sale_total = $subtot - $profit_catlog;
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
													}  
													else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
													{ 
														
														$subtot=$order->get_subtotal(); 
														
														$total_sales_subtot=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (50) / 100;
														
														$profit_catlog_a = $profit_catlog;
														
														$sale_total = $subtot - $profit_catlog;

														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
													} 
													else
													{ 
									
													}
												}
										}
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
							   
						/* shiptogroup profit */	   
						$percentage  = 40;
                        $percentFifty= 50;
						$shiptogroup_profit="";
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
						if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFifty / 100) * $total_sale_so_far2;
							$rebate_so_far2 = number_format($Discount, 2, '.', '');
							if($rebate_so_far2>0)
							{
								$shiptogroup_profit = $percentFifty;
							}
						}
                        else
						{
                            $Discount    = ($percentage / 100) * $total_sale_so_far2;
                            $rebate_so_far2 = number_format($Discount, 2, '.', '');
							if($rebate_so_far2>0)
							{
								$shiptogroup_profit = $percentage;
							}
						}
						 // End Ship to school
			
			
		?>
		 <tr>
             <td align="left" style="padding: 0px 30px;">
			  <div class="text-left">
			 <?php 
			 //Ship to School
			 $total_sale_fundraiser = number_format($total_sale_so_far, 2, '.', ''); 
			$total_rebate_fundraiser = number_format($rebate_so_far, 2, '.', '');
			
			$total_sale_fundraiser2 = number_format($total_sale_so_far2, 2, '.', ''); 
			$total_rebate_fundraiser2 = number_format($rebate_so_far2, 2, '.', '');
			
			$total_sale_fundraiser3 = $total_sale_fundraiser + $total_sale_fundraiser2;
			
			$total_sale_fundraiser3 = number_format($total_sale_fundraiser3, 2, '.', '');
			
			$total_rebate_fundraiser3 = $rebate_so_far + $rebate_so_far2;
			
			$total_rebate_fundraiser3 = number_format($total_rebate_fundraiser3, 2, '.', '');
			
			$amt_paid = get_user_meta( $ID, 'glow_profit_deduct_user_metakey_amount', true);
			
			$amt_paidNew=abs($amt_paid);
			$amt_paidNew = number_format($amt_paidNew, 2, '.', '');
			$amt_remaining=get_user_meta( $ID,'user_rebate_remain_glowProfitDeduct', true);
			$amt_remaining = number_format($amt_remaining, 2, '.', '');
			
		//	$amt_due=$total_sale_so_far_catlog-$profit_catlog;
		//	$amt_due = number_format($amt_due, 2, '.', '');
	    	$total_sale_so_far_catlog_deduct=number_format($total_sale_so_far_catlog_deduct, 2, '.', '');
			$profit_catlog = number_format($profit_catlog, 2, '.', '');
			$total_sale_so_far_catlog = number_format($total_sale_so_far_catlog, 2, '.', '');
			$curr_balance=0;
		
			$curr_balance=$total_sale_so_far_catlog-$total_rebate_fundraiser3;
			$c_symbole="";
			if($total_rebate_fundraiser3>$total_sale_so_far_catlog)
			{
				$c_symbole = "-";
			}
			//$curr_balance=get_user_meta( $ID,'user_rebate_remain_glowProfitDeduct', true);
			$curr_balance=abs($curr_balance);
			$curr_balance = number_format($curr_balance, 2, '.', '');
			$total_sales_subtot = number_format($total_sales_subtot, 2, '.', '');
			$profit_catlog_a = number_format($profit_catlog_a, 2, '.', '');
			$sale_total = number_format($sale_total, 2, '.', ''); 
			if($shipping_cost>0){
			$total_amt_due=$sale_total+$shipping_cost;
			}
			/* 3 Digits code */
			if( $total_sales_subtot > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_sales_subtot_NEW = preg_replace($pattern, $replace, $total_sales_subtot);
			}
			if( $profit_catlog_a > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$profit_catlog_a_NEW = preg_replace($pattern, $replace, $profit_catlog_a);
			}
			if( $sale_total > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$sale_total_NEW = preg_replace($pattern, $replace, $sale_total);
			}	
			if( $total_amt_due > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_amt_due = preg_replace($pattern, $replace, $total_amt_due);
			}
			/* Ship-to-Home */
			if( $total_sale_fundraiser > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_sale_fundraiser_NEW = preg_replace($pattern, $replace, $total_sale_fundraiser);
			}
			$rebate_so_far=number_format($rebate_so_far, 2, '.', '');
			if( $rebate_so_far > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$rebate_so_far_NEW = preg_replace($pattern, $replace, $rebate_so_far);
			}
			// Ship-to-Group
			
			if( $total_sale_fundraiser2 > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_sale_fundraiser2_NEW = preg_replace($pattern, $replace, $total_sale_fundraiser2);
			}
			$rebate_so_far2=number_format($rebate_so_far2, 2, '.', '');
			if( $rebate_so_far2 > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$rebate_so_far2_NEW = preg_replace($pattern, $replace, $rebate_so_far2);
			}
			/*Catlog*/
			if( $total_sale_so_far_catlog > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_sale_so_far_catlog_NEW = preg_replace($pattern, $replace, $total_sale_so_far_catlog);
			}
			if( $total_rebate_fundraiser3 > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_rebate_fundraiser3_NEW = preg_replace($pattern, $replace, $total_rebate_fundraiser3);
			}
			if( $curr_balance > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$curr_balance = preg_replace($pattern, $replace, $curr_balance);
			}
			
			//End Ship to school
			 ?>
				<address>
							<strong>Name of referral:</strong> <?php echo $referral; ?><br>
							<strong>Participants:</strong> <?php echo $Participants; ?><br>
							<strong>Fundraiser Dates:</strong> <?php echo $sd; ?> to <?php echo $ed; ?><br><br>
							<strong style="color: #a84032;">Brochure Sales:</strong><br>
							<strong style="color: #a84032;">Total Brochure Sales:</strong> <span style="color: #a84032;"><?php if( $total_sales_subtot > 999) { echo "$".$total_sales_subtot_NEW; } else { echo "$".$total_sales_subtot; } ?></span><br>
							<strong style="color: #a84032;">Profit: </strong><span style="color: #a84032;"><?php if( $profit_catlog_a > 999) { echo "$".$profit_catlog_a_NEW; } else{ echo "$".$profit_catlog_a; } ?></span><br>
							<strong style="color: #a84032;">Amt Due: </strong><span style="color: #a84032;">
								<?php 

								$user = wp_get_current_user();
  		if(in_array( 'fundraiser', (array) $user->roles )  )
 			{
 				$pattern = "/\B(?=(\d{3})+(?!\d))/";
									$replace = ",";



if( $profit_catlog_a > 999){

$profit_catlog_a_NEW = str_replace( ',', '', $profit_catlog_a_NEW );

}else{
	$profit_catlog_a_NEW = str_replace( ',', '', $profit_catlog_a );
}



									//$profit_catlog_a_NEW = str_replace( ',', '', $profit_catlog_a_NEW );

									$amt_my = $profit_catlog_a_NEW - $total_rebate_fundraiser3 ;
									echo "$".$amt_my;
 			}

								// if (($ID == 214030) || $ID == 214093 ) {
								// 	$pattern = "/\B(?=(\d{3})+(?!\d))/";
								// 	$replace = ",";
								// 	$profit_catlog_a_NEW = str_replace( ',', '', $profit_catlog_a_NEW );

								// 	$amt_my = $profit_catlog_a_NEW - $total_rebate_fundraiser3 ;
								// 	echo "$".$amt_my; 
								// }else{
								// 	if( $sale_total > 999) { 
									
								// 	echo "$".$sale_total_NEW; 
								// } 
								// 	else{

								// 	 echo "$".$sale_total; 
								// 	} 
								// 	if($shipping_cost>0){
								// 	 echo " + $".$shipping_cost." (Shipping) = $".$total_amt_due; 
								// 	} 
								// }
								


								?>
									
								</span><br>
						
				</address>
			</div>
			<div class="text-right">
				<address>
							<!--<strong>Org Profit Check:</strong> <?php echo $org; ?><br>
							<strong>Fundraising Goal:</strong> <?php echo $goal_amount; ?><br>
							<strong>Funds Go Toward: </strong> <?php echo $go_toward; ?><br>
							<strong>Selling Goal (# of Items):</strong> <?php echo $selling_goal; ?><br>-->	
							<strong>Checks Made Payable To:</strong> <?php echo $payable; ?><br>
						</address>
			</div>
			</td>
		</tr>
		<tr>
		<td align="left" style="padding: 0px 30px;">
			 <div class="text-left">
			<address>
							<strong style="color: red;">Online Sales</strong><br>
							<strong style="color: red;">Total Online Sales Ship-to-Home:</strong> <span style="color: red;"><?php if( $total_sale_fundraiser > 999) { echo "$".$total_sale_fundraiser_NEW; } else{ echo "$".$total_sale_fundraiser; } ?></span><br>
							<strong style="color: red;">Profit (40%): </strong><span style="color: red;"><?php  if( $rebate_so_far > 999) { echo "$".$rebate_so_far_NEW; } else{ echo "$".$rebate_so_far; } ?></span><br><br>
							
							<strong style="color: #ff5722;">Online Sales Ship-to-Group: </strong><span style="color: #ff5722;"><?php if( $total_sale_fundraiser2 > 999) { echo "$".$total_sale_fundraiser2_NEW; } else{ echo "$".$total_sale_fundraiser2; } ?></span><br>
							<strong style="color: #ff5722;">Profit: <?php if($shiptogroup_profit==40){ echo "40%"; }  else if($shiptogroup_profit==50){ echo "50%"; } else{ echo "(40% or 50%)"; } ?></strong><span style="color: #ff5722;"></span><br>
							<strong style="color: #ff5722;">Total Online Sales Profit: </strong><span style="color: #ff5722;"><?php if( $rebate_so_far2 > 999) { echo "$".$rebate_so_far2_NEW; } else{  echo "$".$rebate_so_far2; } ?></span><br>
			</address>
			<address>
							<strong style="color: green;">Payments and Credits</strong><br>
							<strong style="color: green;">Total charges (includes brochure sales invoice):</strong> <span style="color: green;"><?php if( $total_sale_so_far_catlog > 999) { echo "$".$total_sale_so_far_catlog_NEW; } else {  echo "$".$total_sale_so_far_catlog; } ?></span><br>
							<strong style="color: green;">Online Store Credits: </strong><span style="color: green;"><?php if( $total_rebate_fundraiser3 > 999) { echo "$".$total_rebate_fundraiser3_NEW; } else { echo "$".$total_rebate_fundraiser3; } ?></span><br>
							<strong style="color: green;">Current Balance: </strong><span style="color: green;"><?php echo $c_symbole."$".$curr_balance; ?></span><br>
							<?php 
							$total_profit=$profit_catlog_a+$rebate_so_far+$rebate_so_far2; 
							$total_profit=number_format($total_profit, 2, '.', '');
							if( $total_profit > 999) {
								$pattern = "/\B(?=(\d{3})+(?!\d))/";
								$replace = ",";
								$total_profit_new = preg_replace($pattern, $replace, $total_profit);
							}
							?>
							<strong style="color: green;">YOUR GLOW TOTAL PROFITS: </strong><span style="color: green;"><?php if( $total_profit > 999) { echo "$".$total_profit_new; } else{ echo "$".$total_profit; }  ?></span><br>
			</address>
			</div>
			<div class="text-right">
				
			</div>
			</td>
		</tr>
		 <!--<tr>
             <td align="left" style="padding: 0px 30px;">
			 <div class="text-left">
			<?php 
			 /*
		    $total_sale_fundraiser = number_format($total_sale_so_far, 2, '.', ''); 
			$total_rebate_fundraiser = number_format($rebate_so_far, 2, '.', '');
			
		    $total_sale_fundraiser2 = number_format($total_sale_so_far2, 2, '.', ''); 
			$total_rebate_fundraiser2 = number_format($rebate_so_far2, 2, '.', '');
			
			$total_sale_fundraiser3 = $total_sale_fundraiser + $total_sale_fundraiser2;
			
			$total_sale_fundraiser3 = number_format($total_sale_fundraiser3, 2, '.', '');
			
			$total_rebate_fundraiser3 = $rebate_so_far + $rebate_so_far2;
			
			$total_rebate_fundraiser3 = number_format($total_rebate_fundraiser3, 2, '.', '');
			
			if( $total_sale_fundraiser3 > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_sale_so_far_new = preg_replace($pattern, $replace, $total_sale_fundraiser3);
						} 
		    if( $total_rebate_fundraiser3 > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_rebate_fundraiser3_new = preg_replace($pattern, $replace, $total_rebate_fundraiser3);
						} 
			*/
			 ?>
				<address>
							<strong>Name of referral:</strong> <?php echo $referral; ?><br>
							<strong>Participants:</strong> <?php echo $Participants; ?><br>
							<strong>Period:</strong> <?php echo $sd; ?> to <?php echo $ed; ?><br>
							<strong style="color: green;">Total Sale:</strong> <span style="color: green;"><?php if( $total_sale_fundraiser3 > 999) { echo "$".$total_sale_so_far_new; } else { echo "$".$total_sale_fundraiser3; } ?></span><br>
							<strong style="color: green;">Total Rebate:</strong><span style="color: green;"><?php  if( $total_rebate_fundraiser3 > 999) { echo "$".$total_rebate_fundraiser3_new; } else { echo "$".$total_rebate_fundraiser3; } ?></span><br>
							<strong style="color: #333;font-size: 14px;">Note: A+B+C+D = Total Rebate and Total Sale.</strong><br>
				</address>
			</div>
			<div class="text-right">
				<address>
							<strong>Checks Made Payable To:</strong> <?php echo $payable; ?><br>
						</address>
			</div>
			</td>
		</tr>-->
		<?php } ?>
		<!--<tr>
		<td style="display: inline-block;margin-bottom: 0px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;padding: 18px 26px 5px;color: green;">Total Sale: <strong>$<span class="fr-total-rebate"><?php echo $total_sale_so_far_n; ?></span></strong></td>
			<td style="display: inline-block;margin-bottom: 0px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;padding: 18px 26px 5px;color: green;">Total Rebate: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far_n; ?></span></strong></td>
		</tr>-->
		 <?php
                  $arr=array();
                   $all_meta_for_users = get_users(array(
                                    'meta_key'     => 'user_registration_seller_fundraiser',
                                    'meta_value'   => $ID,
                                ));
					$registered_seller=count($all_meta_for_users);
					if($registered_seller==0)
					{
								?>
								<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
								   <td align="center">
										<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
											<tr>
												<td align="center">
													<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
														<tr>
														   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;">A: <?php echo "ONLINE SALES SHIP-TO-HOME REGISTERED SELLERS"; ?></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sales So Far: <strong>$<span class="fr-total-sale">0.00</span></strong></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Profit So Far: <strong>$<span class="fr-total-rebate">0.00</span></strong></td>
														</tr>
														<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid red;transition: all 0.1s ease 0s;">
															<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Order #</th>
															<th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Date</th>
															<th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Customer</th>
															<th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Seller</th>
															<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Total Sales</th>
															<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Rebate</th>
															<!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
										
														</tr>
													</table>
												</td>
									
											</tr>
									 
										</table>
								  
									</td>
								</tr>
							<?php								
					}
					else
					{
						$total_sale_so_far=0;
							  $rebate_so_far=0;
						 foreach ( $all_meta_for_users as $all_meta_for_user ) { $IDD=$all_meta_for_user->ID; 
						  
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

                            if ($ID3 == $IDD) { 
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
									/*$total_sale_so_far+=$order->get_subtotal();*/
									$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
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
									$Discount_d = number_format($Discount, 2, '.', '');
									$rebate_so_far+=$Discount_d;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount_d = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount_d;
								 }
                               
							  
								} } } }
						}
						 }
						 /* 3 digits code */
						$total_sale_so_far=number_format($total_sale_so_far, 2, '.', '');
						$rebate_so_far=number_format($rebate_so_far, 2, '.', '');
						if( $total_sale_so_far > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_sale_so_far_registeredN = preg_replace($pattern, $replace, $total_sale_so_far);
						} 
						if($rebate_so_far > 999)
						{
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$rebate_so_far_registeredN = preg_replace($pattern, $replace, $rebate_so_far);
						}
					?>
					
					<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
								   <td align="center">
										<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
											<tr>
												<td align="center">
													<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:0px 30px;">
														<tr>
														   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;">A: <?php echo "ONLINE SALES SHIP-TO-HOME REGISTERED SELLERS"; ?></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sales So Far: <strong><span class="fr-total-sale"><?php if( $total_sale_so_far > 999) { echo "$".$total_sale_so_far_registeredN; } else { echo "$".$total_sale_so_far; } ?></span></strong></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Profit So Far: <strong><span class="fr-total-rebate"><?php if($rebate_so_far > 999) { echo "$".$rebate_so_far_registeredN; } else{ echo "$".$rebate_so_far; } ?></span></strong></td>
														</tr>
													
													</table>
												</td>
									
											</tr>
									 
										</table>
								  
									</td>
								</tr>
					
					<?php	
					}
         foreach ( $all_meta_for_users as $all_meta_for_user ) { $IDD=$all_meta_for_user->ID; ?>
		 <tr id="<?php echo $IDD; ?>" style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  
							  $total_sale_so_far=0;
							  $rebate_so_far=0;
							  $item_qty=0;
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

                            if ($ID3 == $IDD) { 
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
								/* total Item  Sold */
										foreach ($order->get_items() as $item_key => $item_values): 
                                            $item_data = $item_values->get_data(); 
											$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
											if($order_type=="Online") 
											{
												$item_qty  +=  $item_data['quantity']; 
											}
											else if($order_type=="") 
											{
												$item_qty  +=  $item_data['quantity']; 
											}
											else{}
																								
                                        endforeach; 
										
									/* total Item  Sold */	
									
								  if ($frID3 != $order_customer_id) 
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
									/*$total_sale_so_far+=$order->get_subtotal();*/
									$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
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
									$Discount_d = number_format($Discount, 2, '.', '');
									$rebate_so_far+=$Discount_d;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount_d = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount_d;
								 }
                               
							  
								} } } }
						}
						//3digit code 
						$total_sale_so_far=number_format($total_sale_so_far, 2, '.', '');
						$rebate_so_far=number_format($rebate_so_far, 2, '.', '');
						if( $total_sale_so_far > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_sale_so_far_registeredNN = preg_replace($pattern, $replace, $total_sale_so_far);
						} 
						if( $rebate_so_far > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$rebate_so_far_registeredNN = preg_replace($pattern, $replace, $rebate_so_far);
						} 
						?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;"><?php echo $all_meta_for_user->user_firstname; ?> <?php echo $all_meta_for_user->user_lastname; ?></td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sales So Far: <strong><span class="fr-total-sale"><?php if( $total_sale_so_far > 999) {  echo "$".$total_sale_so_far_registeredNN; } else { echo "$".$total_sale_so_far; } ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Profit So Far: <strong><span class="fr-total-rebate"><?php if( $rebate_so_far > 999) { echo "$".$rebate_so_far_registeredNN; } else { echo "$".$rebate_so_far; } ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Items Sold: <strong><span class="fr-total-rebate"><?php echo $item_qty; ?></span></strong></td>
								</tr>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid red;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Order #</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Total Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Profit</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
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
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == $IDD) { 

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
								  if ($frID3 != $order_customer_id) { 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid red;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order_data['date_created']->date('m-d-Y'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        $slr_info  = get_userdata($slrID);
                                       ?>
                                       <?php echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">
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

                                    /*$totalAmount = $order->get_subtotal();*/
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
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{ */
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS) 
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
								 }
                                 else 
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
								 }
                             /*  }*/
                               echo "$".$Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
								<?php  }  } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		   <?php } 
                              ?>
		 <tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
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
								  if ($frID3 != $order_customer_id) { 
								  
                            if ($ID3 == "custom") {
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
                              /* $total_sale_so_far+=$order->get_subtotal();*/
							 $total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									
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
                               
							  
						} } } }
						}
						
						
						
						  $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							
                        foreach ( $orders as $order ) 
						{
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") 
							{ 

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
												$slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
												if($slrID=="custom")
												{
													$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
													$s_name_lower= strtolower($s_name);
													if(!in_array($s_name_lower,$seller_name))
													{
														$seller_name[]=strtolower($s_name);
													}
												}
											 
										 }
									}
									}
							}
						}
						/* Calculate seller Total*/
							$args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							
						for($i=0;$i<count($seller_name);$i++) 
						{
						$seller_rebate=0;
							$seller_total_sale=0;
								$item_qty_manual=0;
                        foreach ( $orders as $order ) 
						{
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") 
							{ 

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
												$slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
												
													$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
													$s_name_lower= strtolower($s_name);
													if($seller_name[$i] == $s_name_lower) 
													{
														  foreach ($order->get_items() as $item_key => $item_values): 
															$item_data = $item_values->get_data();  
															$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type=="Online") 
															{
																$item_qty_manual +=  $item_data['quantity']; 
															}
															else if($order_type=="") 
															{
																$item_qty_manual +=  $item_data['quantity']; 
															}
															else{}
															endforeach; 
															$total_product = $order->get_total();   ///$order_data->get_total();


															$percentage  = 40;
															$percentFifty= 50;

															/*$totalAmount = $order->get_subtotal();*/
										
															$tdiscount=$order->get_total_discount();
										
															if(!empty($tdiscount))
															{
															  $newamt=$order->get_subtotal()-$tdiscount;
															  $totalAmount=$newamt;
															}
															else
															{
															 $totalAmount=$order->get_subtotal();
															}													/*   if ( $order_customer_id != $ID  ) { //
														 $totalAmt    = ($percentage / 100) * $totalAmount;
														   $Discount = number_format($totalAmt, 2, '.', '');
													   }
													   else{*/
														 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
														 {
															$Discount    = ($percentFifty / 100) * $totalAmount;
															$Discount = number_format($Discount, 2, '.', '');
														 }
														 else
														 {
															$Discount    = ($percentage / 100) * $totalAmount;
															$Discount = number_format($Discount, 2, '.', '');
															
														 }
													  /* }*/
													   $seller_rebate+=$Discount;
													   $seller_total_sale+=$totalAmount;
														
													}
												
											 
										 }
									} }
							}
						}
						$seller_total_Sale_array[]=$seller_total_sale;
						$seller_rebate_array[]=$seller_rebate;
						$item_qty_arr[]=$item_qty_manual;
						}
						
						//3 Digits code
						$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');
						$rebate_so_far = number_format($rebate_so_far, 2, '.', '');
						if( $total_sale_so_far > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$tt = preg_replace($pattern, $replace, $total_sale_so_far);
						} 
						if( $rebate_so_far > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$rr = preg_replace($pattern, $replace, $rebate_so_far);
						} 
						$item_sumNEW=0;
						for($a=0;$a<count($item_qty_arr);$a++)
						{
							$item_sumNEW+=$item_qty_arr[$a];
						}
						?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:blue;">B: Manually Added Sellers</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Sales So Far: <strong><span class="fr-total-sale"><?php if( $total_sale_so_far > 999) { echo "$".$tt; } else{ echo "$".$total_sale_so_far; } ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Profit So Far: <strong><span class="fr-total-rebate"><?php if( $rebate_so_far > 999) { echo "$".$rr; } else{ echo "$".$rebate_so_far; } ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">NUMBER OF ITEMS SOLD: <strong><span class="fr-total-rebate"><?php  echo $item_sumNEW; ?></span></strong></td>
								</tr>

							<?php
								$exist_seller=count($seller_name);
								if($exist_seller==0){
								?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid blue;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Order #</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Total Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Profit</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								<?php
								}
								?>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							
							for($i=0;$i<count($seller_name);$i++)
							{ 
								//3 Digits Code
								$seller_t = number_format($seller_total_Sale_array[$i], 2, '.', '');
								if( $seller_t > 999) {
									$pattern = "/\B(?=(\d{3})+(?!\d))/";
									$replace = ",";
									$seller_t_New = preg_replace($pattern, $replace, $seller_total_Sale_array[$i]);
								} 
								$seller_r = number_format($seller_rebate_array[$i], 2, '.', '');
								if( $seller_r > 999) {
									$pattern = "/\B(?=(\d{3})+(?!\d))/";
									$replace = ",";
									$seller_r_New = preg_replace($pattern, $replace, $seller_rebate_array[$i]);
								} 
						
						?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 18px;margin-top:15px;color:blue;"><?php echo $seller_name[$i]; ?></td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Sales So Far: <strong><span class="fr-total-sale"><?php if( $seller_t > 999) { echo "$".$seller_t_New; } else{ echo "$".$seller_t; } ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Profit So Far: <strong><span class="fr-total-rebate"><?php if( $seller_r > 999) { echo "$".$seller_r_New; } else{ echo "$".$seller_r; } ?></span></strong></td>
								</tr>
						        <tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Items Sold: <strong><span class="fr-total-rebate"><?php echo $item_qty_arr[$i]; ?></span></strong></td>
								</tr>
							<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid blue;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Order #</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Total Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Profit</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								<?php
						
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") { 

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
							     if ($frID3 != $order_customer_id) { 
								 
								 $s_name_meta=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
								 if($seller_name[$i] == strtolower($s_name_meta)) {
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid blue;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order_data['date_created']->date('m-d-Y'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if($slrID=="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
											
										}
										else
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">
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

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{ */
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
								 }
                             /*  } */
                               echo "$".$Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
									<?php }  } } } }
                        }
							}
                        ?>
						
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		<!-- Seller not mentioned-->
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
							  $item_qty_not_found=0;
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
								$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								if($order_type=="Online")
								{
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
								  if ($frID3 != $order_customer_id) { 
								     $wc_is_mergeOrder = get_post_meta($order->get_id(), 'wc_seller_total_mergeOrder', true );
									 if(empty($wc_is_mergeOrder)) 
									 {
										if(empty($ID3) || $ID3=="No-seller") {

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
										$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

                                      foreach ($order->get_items() as $item_key => $item_values): 
                                            $item_data = $item_values->get_data();  
											$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
											if($order_type=="Online") 
											{
												$item_qty_not_found +=  $item_data['quantity']; 
											}
											else if($order_type=="") 
											{
												$item_qty_not_found +=  $item_data['quantity']; 
											}
											else{}
                                    endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
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
                               
							  
						} }  }  } } }
						}
						$total_sale_so_far=number_format($total_sale_so_far, 2, '.', '');
						if( $total_sale_so_far > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_sale_so_far_seller_not = preg_replace($pattern, $replace, $total_sale_so_far);
						} 
						$rebate_so_far=number_format($rebate_so_far, 2, '.', '');
						if( $rebate_so_far > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$rebate_so_far_seller_not = preg_replace($pattern, $replace, $rebate_so_far);
						} 
						?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:purple;">C: ONLINE SALES SHIP-TO-HOME SELLER NOT MENTIONED</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:purple;">Total Sales So Far: <strong>$<span class="fr-total-sale"><?php if( $total_sale_so_far > 999) { echo $total_sale_so_far_seller_not; } else{ echo $total_sale_so_far; } ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:purple;">Total Profit So Far: <strong>$<span class="fr-total-rebate"><?php if( $rebate_so_far > 999) { echo $rebate_so_far_seller_not; } else{ echo $rebate_so_far; }   ?></span></strong></td>
								</tr>
								 <tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:purple;">Total Items Sold: <strong><span class="fr-total-rebate"><?php echo $item_qty_not_found; ?></span></strong></td>
								</tr>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid purple;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Order #</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Total Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Profit</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
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
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							$wc_is_mergeOrder = get_post_meta($order->get_id(), 'wc_seller_total_mergeOrder', true );
							$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
							if($order_type=="Online")
							{
							if(empty($wc_is_mergeOrder)) 
							{
								if(empty($ID3) || $ID3=="No-seller") {

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
							     if ($frID3 != $order_customer_id) { 
								 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid purple;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order_data['date_created']->date('m-d-Y'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                       if(empty($ID3) || $ID3=="No-seller") {
											echo "N/A";
										}
										else
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">
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

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{*/
							   
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
								 }
                               /*} */
                               echo "$".$Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
						<?php  } } } } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		
		
			<!-- Fundraiser Online Sales-->
		<!--<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
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
							  
							$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									
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
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
                            if ($order_type=='Online') {

                             
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
										$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

                                        foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type_2=="Online") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															else if($order_type_2=="") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															else{}
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                  
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
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
                               
							  
						} } } }
						}
						$total_sale_so_far=number_format($total_sale_so_far, 2, '.', '');
						if( $total_sale_so_far > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_sale_so_far_seller_not = preg_replace($pattern, $replace, $total_sale_so_far);
						} 
						if( $rebate_so_far > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$rebate_so_far_seller_not = preg_replace($pattern, $replace, $rebate_so_far);
						}
						?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:#f54298;">D: Fundraiser Online Sales</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#f54298;">Total Sales So Far: <strong>$<span class="fr-total-sale"><?php if( $total_sale_so_far > 999) { echo $total_sale_so_far_seller_not; } else{ echo $total_sale_so_far; } ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#f54298;">Total Profit So Far: <strong>$<span class="fr-total-rebate"><?php if( $rebate_so_far > 999) { echo $rebate_so_far_seller_not; } else{ echo $rebate_so_far; } ?></span></strong></td>
								</tr>
							    <tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#f54298;">Total Items Sold: <strong><span class="fr-total-rebate"><?php echo $item_qty_fund_online; ?></span></strong></td>
								</tr>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #f54298;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Order #</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Total Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Profit</th>
                               
			
								</tr>
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
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							 $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
							  $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
                             if ($frID3 == $order_customer_id)  
							 {

                               
									if ( $order->has_status('completed') || $order->has_status('processing')) {
									
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
							     if ($order_type=='Online') { 
								 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #f54298;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order_data['date_created']->date('m-d-Y'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if(empty($slrID))
										{
											echo "N/A";
										}
										else if($slrID =="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else
										{
											$slr_info  = get_userdata($slrID);
											echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                             
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                          
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
								 }
                              
                               echo "$".$Discount;?> </td>
								
								</tr>
							 <?php  } } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		-->
		<!-- Online Ship to School -->
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
					
					<?php
					 $total_sale_so_far=0;
							  $rebate_so_far=0;
							   $item_qty_fund_online=0;
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
										$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

                                        foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type_2=="ship-to-school") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															
															else{}
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();

									/* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}            
                             
                                
                               
							  
						} } } }
						}
						$percentage  = 40;
                        $percentFifty= 50;
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
						if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFifty / 100) * $total_sale_so_far;
							$rebate_so_far = number_format($Discount, 2, '.', '');
							if($rebate_so_far>0)
							{
								$shiptogroup_profit = $percentFifty;
							}
						}
                        else
						{
                            $Discount    = ($percentage / 100) * $total_sale_so_far;
                            $rebate_so_far = number_format($Discount, 2, '.', '');
							if($rebate_so_far>0)
							{
								$shiptogroup_profit = $percentage;
							}
							
						}
						$total_sale_so_far=number_format($total_sale_so_far, 2, '.', '');
						if( $total_sale_so_far > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_sale_so_far_seller_not = preg_replace($pattern, $replace, $total_sale_so_far);
						} 
						$rebate_so_far=number_format($rebate_so_far, 2, '.', '');
						if( $rebate_so_far > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_sale_so_far_rebate_not = preg_replace($pattern, $replace, $rebate_so_far);
						} 
						?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:#ff5722;">D: SHIP-TO-GROUP/SCHOOL ORDERS</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#ff5722;">Total Sales So Far: <strong>$<span class="fr-total-sale"><?php if( $total_sale_so_far > 999) { echo $total_sale_so_far_seller_not; } else{ echo $total_sale_so_far; } ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#ff5722;">Total Profit So Far: <strong>$<span class="fr-total-rebate"><?php if( $rebate_so_far > 999) {  echo $total_sale_so_far_rebate_not; } else{ echo $rebate_so_far; } ?></span></strong></td>
								</tr>
							    <tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#ff5722;">Total Items Sold: <strong><span class="fr-total-rebate"><?php echo $item_qty_fund_online; ?></span></strong></td>
								</tr>
								
						<?php 
							$seller_name_ship=array();
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

										$slrID  = get_post_meta($order->get_id(), 'wc_seller_field', true );
										if(!empty($slrID))
										{
											if($slrID=="custom")
											{
												$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
												$s_name_lower= strtolower($s_name);
												if(!in_array($s_name_lower,$seller_name_ship))
												{
													$seller_name_ship[]=strtolower($s_name);
												}
											}
											else{
												$slr_info  = get_userdata($slrID);
												$s_name=$slr_info->first_name." ".$slr_info->last_name; 
												$s_name_lower = strtolower($s_name);
												if(!in_array($s_name_lower,$seller_name_ship))
												{
													$seller_name_ship[]=strtolower($s_name);
												}
												
											}
										}
                               } } } }
						}	
					//print_r($seller_name_ship);
					
					$item_qty_arr=array();
					$seller_total_Sale_array=array();
					$seller_rebate_array=array();
					$total_sale_so_far_4=0;
					$rebate_so_far_4=0;
					$args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                    $orders = wc_get_orders($args);
					for($i=0;$i<count($seller_name_ship);$i++) 
					{
						$seller_rebate=0;
						$seller_total_sale=0;
						$item_qty_manual=0;
						
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
								
									if ($in_s ==1){
										if ($frID3 != $order_customer_id) { 
											$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
											if ($order_type=='ship-to-school') {
										
											$slrID = get_post_meta($order->get_id(), 'wc_seller_field', true );
											if(!empty($slrID))
											{
													if($slrID=="custom")
													{
														$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
														$s_name_lower = strtolower($s_name);
													}
													else{
														$slr_info  = get_userdata($slrID);
														$s_name=$slr_info->first_name." ".$slr_info->last_name; 
														$s_name_lower = strtolower($s_name);
													}
													if($seller_name_ship[$i] == $s_name_lower) 
													{
														$tdiscount=$order->get_total_discount();
														if(!empty($tdiscount))
														{
														  $newamt=$order->get_subtotal()-$tdiscount;
														  $total_sale_so_far_4+=$newamt;
														}
														else
														{
														 $total_sale_so_far_4+=$order->get_subtotal();
														}
														$total_sale_so_far_4 = number_format($total_sale_so_far_4, 2, '.', '');
														foreach ($order->get_items() as $item_key => $item_values): 
																$item_data = $item_values->get_data();  
																$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
																			if($order_type_2=="ship-to-school") 
																			{
																				$item_qty_manual +=  $item_data['quantity']; 
																			}
																			else{}
														endforeach; 
													 
													$total_product = $order->get_total();   
													$tdiscount=$order->get_total_discount();
													if(!empty($tdiscount))
													{
														$newamt=$order->get_subtotal()-$tdiscount;
														$totalAmount=$newamt;
													}
													else
													{
														$totalAmount=$order->get_subtotal();
													}  
													/* shiptogroup profit */	   
													$percentage  = 40;
													$percentFifty= 50;
												 
													$real_qty_catlog_online2=$total_cat_quantity+$item_qty_manual;
													if ($real_qty_catlog_online2 > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
													{
														$Discount = ($shiptogroup_profit / 100) * $totalAmount;
														$rebate_so_far_4 = number_format($Discount, 2, '.', '');
													}
													else
													{
														$Discount = ($shiptogroup_profit / 100) * $totalAmount;
														$rebate_so_far_4 = number_format($Discount, 2, '.', '');
													}
												   $seller_rebate+=$Discount;
												   $seller_total_sale+=$totalAmount;
													
														
											}
										}
							  } } } 
							  
							  }
								
						}
						$seller_total_Sale_array[]=$seller_total_sale;
						$seller_rebate_array[]=$seller_rebate;
						$item_qty_arr[]=$item_qty_manual;
					}
					//print_r($seller_total_Sale_array);
					//print_r($item_qty_arr);
					//print_r($seller_rebate_array);
					?>
								
					<?php
								$exist_seller=count($seller_name_ship);
								if($exist_seller==0){
								?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #ff5722;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Order #</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Total Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Profit</th>
							   </tr>
								<?php
								}
								?>
								  <?php 
                            $args = array(
                                'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							for($i=0;$i<count($seller_name_ship);$i++)
							{
								//3 Digits Code
								$seller_t = number_format($seller_total_Sale_array[$i], 2, '.', '');
								if( $seller_t > 999) {
									$pattern = "/\B(?=(\d{3})+(?!\d))/";
									$replace = ",";
									$seller_t_New = preg_replace($pattern, $replace, $seller_total_Sale_array[$i]);
								} 
								$seller_r = number_format($seller_rebate_array[$i], 2, '.', '');
								if( $seller_r > 999) {
									$pattern = "/\B(?=(\d{3})+(?!\d))/";
									$replace = ",";
									$seller_r_New = preg_replace($pattern, $replace, $seller_rebate_array[$i]);
								} 
								?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 18px;margin-top:15px;color:#ff5722;"><?php echo $seller_name_ship[$i]; ?></td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#ff5722;">Total Sales So Far: <strong><span class="fr-total-sale"><?php if( $seller_t > 999) { echo "$".$seller_t_New; } else{ echo "$".$seller_t; } ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#ff5722;">Total Profit So Far: <strong><span class="fr-total-rebate"><?php if( $seller_r > 999) { echo "$".$seller_r_New; } else{ echo "$".$seller_r; } ?></span></strong></td>
								</tr>
						        <tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#ff5722;">Total Items Sold: <strong><span class="fr-total-rebate"><?php echo $item_qty_arr[$i]; ?></span></strong></td>
								</tr>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #ff5722;transition: all 0.1s ease 0s;">
								<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Order #</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Total Sales</th>
								 <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Profit</th>
                               </tr>
								<?php
								$rebate_so_far_5=0;
								foreach ( $orders as $order ) {
							
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
								$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ($frID3 != $order_customer_id)  
								{
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
							     if ($order_type=='ship-to-school') { 
								 		$IDF = get_post_meta($order->get_id(), 'wc_seller_field', true );
										if(!empty($IDF)){
											 if ($IDF == "custom") { 
												$s_name_meta=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
											 }
											 else
											{
											 $slr_info = get_userdata($IDF);
											 $s_name_meta=$slr_info->first_name." ".$slr_info->last_name; 
											}
											
											if($seller_name_ship[$i] == strtolower($s_name_meta)) {
											
													foreach ($order->get_items() as $item_key => $item_values): 
															$item_data = $item_values->get_data();  
															$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type_2=="ship-to-school") 
															{
																$item_qty_manual_s +=  $item_data['quantity']; 
															}
															else{}
													endforeach; 
													 
													$total_product = $order->get_total();   
													$tdiscount=$order->get_total_discount();
													if(!empty($tdiscount))
													{
														$newamt=$order->get_subtotal()-$tdiscount;
														$totalAmount=$newamt;
													}
													else
													{
														$totalAmount=$order->get_subtotal();
													}  
													/* shiptogroup profit */	   
													$percentage  = 40;
													$percentFifty= 50;
												 
													$real_qty_catlog_online3=$total_cat_quantity+$item_qty_manual_s;
													if ($real_qty_catlog_online3 > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
													{
														$Discount = ($shiptogroup_profit / 100) * $totalAmount;
														$rebate_so_far_5 = number_format($Discount, 2, '.', '');
													}
													else
													{
														$Discount = ($shiptogroup_profit / 100) * $totalAmount;
														$rebate_so_far_5 = number_format($Discount, 2, '.', '');
													}
												 											
												
												
												
												
								?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #ff5722;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;"><?php echo $order_data['date_created']->date('m-d-Y'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;"> <?php 
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
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;"><?php echo "$".$rebate_so_far_5; ?></td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
								 <?php  } } } } } }
                        }
 }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		
		
		
		<!--catalog sales -->
		
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $profit_FDisccount=0;
							  $rebate_so_far=0;
							  $itemQTy=array();
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
										  $qty = $order_item_quantity; 
										  $itemQTy[] = $qty;
										if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order) ) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$profit_FDisccount += $to_widthdraw;
											//$total_sale_so_far+=$subtot-$to_widthdraw; 
											$total_sale_so_far+=$subtot; 
										}  
										else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
											$profit_FDisccount += $to_widthdraw;
											//$total_sale_so_far+=$subtot-$to_widthdraw; 
											$total_sale_so_far+=$subtot; 
										} 
										else
										{ 
						
										}
										

								 }
							
							} }
						}
						$total_sale_so_far=number_format($total_sale_so_far, 2, '.', '');
						if( $total_sale_so_far > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_sale_so_far_seller_not = preg_replace($pattern, $replace, $total_sale_so_far);
						}
						$profit_FDisccount=number_format($profit_FDisccount, 2, '.', '');
						if( $profit_FDisccount > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$profit_Fund_discount = preg_replace($pattern, $replace, $profit_FDisccount);
						}
						$itemQTTYY=0;
						for($w=0;$w<count($itemQTy);$w++)
						{
							$itemQTTYY+=$itemQTy[$w];
						}
						?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:#a84032;">E: BROCHURE SALES</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#a84032;">Total Sales So Far: <strong>$<span class="fr-total-sale"><?php if( $total_sale_so_far > 999) { echo $total_sale_so_far_seller_not; } else{ echo $total_sale_so_far; } ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#a84032;">TOTAL PROFIT SO FAR: <strong>$<span class="fr-total-sale"><?php if( $profit_FDisccount > 999) { echo $profit_Fund_discount; } else{ echo $profit_FDisccount; } ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#a84032;">TOTAL ITEMS SOLD: <strong><span class="fr-total-sale"><?php if( $itemQTTYY > 0 ) { echo $itemQTTYY; } else{ echo $itemQTTYY; } ?></span></strong></td>
								</tr>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #a84032;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Order #</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Date</th>
                              <!--  <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Customer</th> -->
                                <!--<th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Seller</th>-->
                               <!-- <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Product Sales</th>-->
                                <!--<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Rebate</th>-->
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
								  
								  
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
							$seller_name= array();
								  $subTotal=array();
								  $sellerQtyTotal=array();
								  $item_ids=array();
								/*	foreach ( $order->get_items() as $item_id => $item ) :
									$item_t = $item->get_data();
									
									 $item_id   = $item->get_id();
									$s_name=wc_get_order_item_meta( $item_id, '_wdm_seller_name', true );
									if(!in_array($s_name,$seller_name))
									{
										$seller_name[]=$s_name;
										$subtot[]=$item_t['subtotal'];
									}
									
									endforeach; */

                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							 $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
							$new_catalog_order=get_post_meta( $order->get_id(), 'new_catalog_order_50_percent',true);
							    $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
                             if ($frID3 == $order_customer_id)  
							 {
								
                              
									if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
							     if ($order_type=='Catlog') { 
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
										if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order)) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale=$subtot-$to_widthdraw; 
										}  
										else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes") 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
											$total_sale=$subtot-$to_widthdraw; 
										} 
										else
										{ 
						
										}
								 
                                  ?>
								<tr class="orderClass" style="display: flex;flex-flow: wrap; border-bottom: 1px solid #a84032;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order_data['date_created']->date('m-d-Y'); ?></td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>-->
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if(empty($slrID))
										{
											echo "N/A";
										}
										else if($slrID =="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else
										{
											$slr_info  = get_userdata($slrID);
											echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>-->
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">$<?php  echo $total_sale=number_format( $total_sale, 2); ?></td>-->
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">$
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

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{*/
							   
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS || !empty($new_catalog_order) && $new_catalog_order == "Yes")
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
								 }
                               /*} */
                               echo "$".$Discount;?> </td>-->
							   </tr>
							   <tr>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #a84032;transition: all 0.1s ease 0s;">
									<th style="text-align: left;width: 12%;padding: 10px 5px;font-size: 14px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Seller</th>
									<th style="text-align: left;width: 12%;padding: 10px 5px;font-size: 14px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Total Items Sold</th>
									<th style="text-align: left;width: 12%;padding: 10px 5px;font-size: 14px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Total Sales</th>
									<th style="text-align: left;width: 12%;padding: 10px 5px;font-size: 14px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Amount Owed</th>
									<th style="text-align: left;width: 12%;padding: 10px 5px;font-size: 14px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Profit</th>
								</tr>
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
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #a84032;transition: all 0.1s ease 0s;">
								 <td style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $seller_name[$i]  ?></td>
								 <td style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $sellerQtyTotal[$i]; ?></td>
								    <td style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php $subtott=$subTotal[$i]; echo '$'.$subtott=number_format( $subtott, 2); ?></td>
									 <td style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php $qty=$order_item_quantity; if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order)) { $subtot=$subTotal[$i]; $to_widthdraw = floatval ($subtot) * floatval (40) / 100; $dis=$subtot-$to_widthdraw; echo '$'.$dis=number_format( $dis, 2); }  else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) { $subtot=$subTotal[$i]; $to_widthdraw = floatval ($subtot) * floatval (50) / 100;  $dis=$subtot-$to_widthdraw; echo '$'.$dis=number_format( $dis, 2);} else{ echo "0.00"; } ?></td>
									<td style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php $qty=$order_item_quantity; if($qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order)) { $subtot=$subTotal[$i];  $to_widthdraw = (40 / 100) * $subtot; echo '$'.$to_widthdraw=number_format( $to_widthdraw, 2); }  else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes") { $subtot=$subTotal[$i];  $to_widthdraw = (50 / 100) * $subtot; echo '$'.$to_widthdraw=number_format( $to_widthdraw, 2); } else{ echo "0.00"; } ?></td>
									
									 
									
								</tr>
								<?php } 
								
								/* endforeach; */

								?>
								<?php } ?>
							
								</tr>
								
							<?php  } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		
		
		
    </table>
	
    </div>
 <?php }
}
	else
	{
		$url=("" . glow_dynamic_url_base('home') . "my-account/");
		wp_redirect($url);
	}

 ?>