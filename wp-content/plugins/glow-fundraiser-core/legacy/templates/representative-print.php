<?php
/**
 * Template Name: Representative Print
 */
  if ( is_user_logged_in() ) {
	$current_user = wp_get_current_user();
	$u_id = $current_user->ID;

	$ID=$_GET['r_id'];
    $PID=$_GET['pid'];
	if(!empty($ID))
	{
	     $u_id=$ID;
	}
	$user_info = get_userdata($u_id);
	$current_email = $user_info->user_email;
	if(in_array( 'representative', (array) $user_info->roles )){
   $f=get_user_meta( $u_id , 'first_name' , true );		
   $l=get_user_meta( $u_id , 'last_name' , true );								   

   $phone=get_user_meta( $u_id , 'phone' , true );
   $reps_commission=get_user_meta( $u_id , 'reps_commission' , true );
   $billing_country=get_user_meta( $u_id , 'billing_country' , true );
   $city=get_user_meta( $u_id , 'billing_city' , true );
   $billing_company= get_user_meta( $u_id , 'billing_company' , true );
   $billing_address_1=get_user_meta( $u_id , 'billing_address_1' , true );
	if(!empty($PID))
	{
	$paid_status_check=get_post_meta($PID,"paid_status",true);
	/* New Start */
	$total_referral_comm = 0;
	$totalAmount_full=0;
	$fundraisers_list=array();
	$fundraisers_list2=array();
	
	$exist_fids_arr=array();
	$q=0;
	$fundraiserTotal2=0;
	$fundraiserTotal=0;
	$total_users=0;
	$total_users=0;
	$total_query=0;

	//$current_payment_postid=get_user_meta($current_userID,"um_unique_payment_id",true);
	
	if($paid_status_check==0)
	{
		$fundraiser_ids=get_post_meta($PID,"fundraiser_ids",true);
		if(!empty($fundraiser_ids))
		{
			$exist_fids_arr=explode(",",$fundraiser_ids);
			$q=1;
		}
	}
	else if($paid_status_check==1)
	{
		$fundraiser_ids=get_post_meta($PID,"after_payment_fundraiser_ids_inactive",true);
		if(!empty($fundraiser_ids))
		{
			$exist_fids_arr=explode(",",$fundraiser_ids);
			$q=1;
		}
	}
	else{
		$fundraiser_ids=get_post_meta($PID,"fundraiser_ids",true);
		if(!empty($fundraiser_ids))
		{
			$exist_fids_arr=explode(",",$fundraiser_ids);
			$q=1;
		}	
	}

				if($q==1)
				{
				$i=0;
				
				/*get fundraiser list which connected representative */
				$fundraisers_users = get_users(array( 
					'role' => array( 'fundraiser' ),
					'include' => $exist_fids_arr,
					'meta_key'     => 'user_registration_referral_email',
					'meta_value'   => $current_email,
					'meta_compare' => '='
				));
				
				$fundraisers_list = get_users(array( 
					'role' => array( 'fundraiser' ),
					'include' => $exist_fids_arr,
					'meta_key'     => 'user_registration_referral_email',
					'meta_value'   => $current_email,
					'meta_compare' => '='
					
				));	
				$fundraiserTotal = count($fundraisers_list);
				
				$total_users = count($fundraisers_users);
				$fundraisers_list2 = get_users(array( 
					'role' => array( 'fundraiser' ),
					'include' => $exist_fids_arr,
					'meta_key'     => 'user_registration_referral_email',
					'meta_value'   => $current_email,
					'meta_compare' => '=',
				));	
				$fundraiserTotal2 = count($fundraisers_list2);
						
				$fundraisers_list = get_users(array( 
							'role' => array( 'fundraiser' ),
							'include' => $exist_fids_arr,
							'meta_key'     => 'user_registration_referral_email',
							'meta_value'   => $current_email,
							'meta_compare' => '='
				));	
		
	            $online_sales=0;
				$shiptoschool_sales=0;
			    $brochures_sales=0;
				$full_representative_arr=array();
				$z=0;
				foreach($fundraisers_list as $fundraisers) {
							$ID = $fundraisers->data->ID;
							$action_status= get_user_meta($ID,'user_action_status',true );
							
							if($action_status==2)
							{
							//$name = $fundraisers->data->user_login;
									$action_status= get_user_meta($ID,'user_action_status',true );
							$org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );
							if(empty($org))
							{
								$org=get_user_meta( $ID , 'user_registration_input_company' , true );
							}
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
							$subtot=0;
							
							if($paid_status_check==1){
								$get_referral_commission = get_post_meta($PID,"referral_commission",true);
							}
							else {							
							if(get_user_meta($referralUserID,'reps_commission',true)){
							 $get_referral_commission = get_user_meta($referralUserID,'reps_commission',true);       
							}else{
							$get_referral_commission = 0;
							}
							}
							$args = array(
							'status'   => "published",
							'meta_key' => 'fundraiser_field',
							'meta_value' => $ID,
							'limit' => -1
							);
							
							$orders = wc_get_orders($args);
							foreach ( $orders as $order ){
								
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
								$order_data = $order->get_data();
								$order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) 
								{
									/* Active/Inactive */	
									$order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
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
									if($in_s==1){
										
										if ($frID3 != $order_customer_id){
											if($order_type=="Online"){
												$tdiscount=$order->get_total_discount();
												if(!empty($tdiscount)){
													$newamt=$order->get_subtotal()-$tdiscount;
													$total_sale_so_far+=$newamt;
												}else{
													$total_sale_so_far+=$order->get_subtotal();
												}
											}
										}
										else if($frID3 == $order_customer_id){
											if($order_type=="Catlog"){
												$subtot=$order->get_subtotal(); 
												$total_sale_so_far_Catlog+=$subtot; 
											}
										}
										else{
										
										}
									}
									else {
										if($frID3 == $order_customer_id){
											if($order_type=="Catlog"){
												$subtot=$order->get_subtotal(); 
												$total_sale_so_far_Catlog+=$subtot; 
											}
										}
										else{
										
										}
									}
								}
							
							}
							/* Calculate Ship to school Total Sales */
								$total_sale_so_far2=0;
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
										  if ($frID3 != $order_customer_id){ 
											$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
											if ($order_type=='ship-to-school') {
										
												$tdiscount=$order->get_total_discount();
												if(!empty($tdiscount))
												{
												  $newamt2=$order->get_subtotal()-$tdiscount;
												  $total_sale_so_far2+=$newamt2;
												}
												else
												{
												 $total_sale_so_far2+=$order->get_subtotal();
												}
												$total_sale_so_far2 = number_format($total_sale_so_far2, 2, '.', '');

											} 
										} 
									} 
								}
							}
							$totalAmount = $total_sale_so_far_Catlog + $total_sale_so_far + $total_sale_so_far2;
							$online_sales+=$total_sale_so_far;
							$shiptoschool_sales+=$total_sale_so_far2;
							$brochures_sales+=$total_sale_so_far_Catlog;
							$totalAmount_full+=$totalAmount;
							$get_referral_commission_amount = floatval ($totalAmount) * floatval ($get_referral_commission) / 100;
							$total_referral_comm = $total_referral_comm + $get_referral_commission_amount; 
							$full_representative_arr[$z]["id"]=$ID;
							$org=get_user_meta( $ID, 'user_registration_name_of_school_business', true );
							$full_representative_arr[$z]["org_name"] = $org;
							$full_representative_arr[$z]["total_amount"]=number_format($totalAmount, 2, '.', '');
							$full_representative_arr[$z]["online_sales"]=number_format($total_sale_so_far, 2, '.', '');
							$full_representative_arr[$z]["shiptoschool_sales"]=number_format($total_sale_so_far2, 2, '.', '');
							$full_representative_arr[$z]["brochures_sales"]=number_format($total_sale_so_far_Catlog, 2, '.', '');
							$full_representative_arr[$z]["referral_commission_amount"]=number_format($get_referral_commission_amount, 2, '.', '');
							$z++;
							} }
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
.detail_data
{
	border-top: 1px solid #e9ebec;
    padding-top: 20px;
    display: inline-block;
    width: 100%;
}
.ft-size
{
	    font-size: 21px;
}
 </style>
<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;background:#f2f2f2;width:100%;float:left;">
   <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:40px auto;width: 98%;overflow: hidden;">
        <tr>
             <td align="center" style="padding:30px; text-align:center;background: -webkit-linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);color: #000;">
				<h1 style="color:#555;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;line-height: 18px;float:left;"> Representative Report </h1>
		  	    <h1 style="color:#555;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;line-height: 18px;float:right;"> Rep #ID: <?php echo $u_id; ?></h1>
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
							https://glowbrandscandles.com/<br>
							contact@glowscentedcandles.com<br>
						
				</address>
			</div>
			<div class="text-right">
				<address>
							<strong><?php echo $billing_company; ?></strong><br>
							<?php echo $f; ?> <?php echo $l; ?><br>
							<strong>Address</strong>: <?php echo $billing_address_1; ?><br/>
							<strong>City</strong>: <?php echo $city; ?><br/>
							<strong>Phone</strong>: <?php echo $phone; ?><br/>
						
				</address>
			</div>
			</td>
		</tr>
		 <tr class="detail_data">
             <td align="left" style="padding: 0px 30px;">
			 <div class="text-left">
			 <h1 class="new-heading ft-size">Sales and commission details</h1>
				<address>
							<?php if($paid_status_check==1) { ?><strong>#Payment ID: </strong><?php echo $PID;  ?><br><br><?php  } ?>
							<!--<strong>Ship to school:</strong> <?php  if($shiptoschool_sales!=0) { echo "$".$shiptoschool_sales = number_format($shiptoschool_sales, 2, '.', ''); } else { echo "$0.00"; } ?><br>
							<strong>Brochure sales:</strong> <?php if($brochures_sales!=0) { echo "$".$brochures_sales = number_format($brochures_sales, 2, '.', ''); } else { echo "$0.00"; } ?><br>-->
							<strong>Total Sales:</strong> <?php if($totalAmount_full!=0) { echo "$".$totalAmount_full = number_format($totalAmount_full, 2, '.', ''); } else { echo "$0.00"; } ?><br>
							<strong>Total Commission:</strong> <?php if($total_referral_comm!=0) { echo "$".$total_referral_comm = number_format($total_referral_comm, 2, '.', ''); } else { echo "$0.00"; } ?><br>
							
				</address>
			</div>
			<div class="text-right">
				<address></address>
			</div>
			</td>
		</tr>
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
								   <td align="center">
										<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
											<tr>
												<td align="center">
													<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:0px 30px 0px;">
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#000000;"><strong>Total Online sales: </strong><span class="fr-total-sale"><?php  if($online_sales!=0) { echo "$".$online_sales = number_format($online_sales, 2, '.', ''); } else { echo "$0.00"; } ?></span></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#000000;"><strong>Total Ship to school: </strong><span class="fr-total-rebate"><?php  if($shiptoschool_sales!=0) { echo "$".$shiptoschool_sales = number_format($shiptoschool_sales, 2, '.', ''); } else { echo "$0.00"; } ?></span></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#000000;"><strong>Total Brochure sales: </strong><span class="fr-total-rebate"><?php if($brochures_sales!=0) { echo "$".$brochures_sales = number_format($brochures_sales, 2, '.', ''); } else { echo "$0.00"; } ?></span></td>
														</tr>
														
													</table>
												</td>
									
											</tr>
									 
										</table>
								  
									</td>
			</tr>
			<tr id="" style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
			   <td align="center">
					<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:0px 30px 30px;border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
						<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #000000;transition: all 0.1s ease 0s;">
							<th class="fr-order-col fr-order-id" style="text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#000000;">Fundraisers #ID</th>
							<th class="fr-order-col fr-order-date" style="text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#000000;">Organization</th>
							<th class="fr-order-col fr-order-customer" style="text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#000000;">Online Sales</th>
							<th class="fr-order-col fr-order-seller" style="text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#000000;">Ship to school</th>
							<th class="fr-order-col fr-order-total" style="text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#000000;">Brochure sales</th>
							<th class="fr-order-col fr-order-total" style="text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#000000;">Total Sales</th>
							<th class="fr-order-col fr-order-total" style="text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#000000;">Total Commission</th>
						</tr>
						<tr>
							<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="">
							<?php $c_data=count($full_representative_arr); if(!empty($c_data)){ 
								for($a=0; $a<count($full_representative_arr); $a++){
								?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #000000;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#000000;"><?php echo "#".$full_representative_arr[$a]["id"]; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#000000;"><?php echo $full_representative_arr[$a]["org_name"]; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#000000;"><?php echo "$".$full_representative_arr[$a]["online_sales"]; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#000000;"><?php echo "$".$full_representative_arr[$a]["shiptoschool_sales"]; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#000000;"><?php echo "$".$full_representative_arr[$a]["brochures_sales"]; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#000000;"><?php echo "$".$full_representative_arr[$a]["total_amount"]; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#000000;"><?php echo "$".$full_representative_arr[$a]["referral_commission_amount"]; ?></td>
								</tr>
								<?php } } ?>
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
	   }
		  }
		  else{
		  echo "Incorrect Url. Please check.";
		  }
  ?>