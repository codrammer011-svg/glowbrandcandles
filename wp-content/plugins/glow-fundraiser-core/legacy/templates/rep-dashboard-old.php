<?php /* Template Name: Example Template */ ?>
<?php get_header();?>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
	<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri();?>/css/custom-rep.css">
	<style>.fade{opacity:1;}
/*    .page-id-38050 .header-wrapper{display:none;}
    .page-id-38050 .main-footer{display:none;}
    .page-id-38050 .copyright{display:none;}*/
    </style>



	
    <?php
	if ( is_user_logged_in()) {
		
	$current_user = wp_get_current_user();
	//print_r($current_user);

	$current_userID = $current_user->ID;
    if($_GET['userid']){
    	$current_userID = $_GET['userid'];
    }else{
    	$current_userID = $current_user->ID;
    }
    
	$current_email = $current_user->user_email;
	$firstname = get_user_meta($current_userID,'first_name',true);
	$lastname = get_user_meta($current_userID,'last_name',true);
	$phone = get_user_meta($current_userID,'phone',true);
	$sex = get_user_meta($current_userID,'sex',true);
	$reps_company = get_user_meta($current_userID,'billing_company',true);
	$reps_country = get_user_meta($current_userID,'billing_country',true);
	$reps_city = get_user_meta($current_userID,'billing_city',true);
	$reps_address = get_user_meta($current_userID,'billing_address_1',true);
	$reps_commission = get_user_meta($current_userID,'reps_commission',true);
	
	/*get fundraiser list which connected representative */
	$fundraisers_list = get_users(array( 
		'role__in' => array( 'fundraiser' ),
		'meta_key'     => 'user_registration_referral_email',
		'meta_value'   => $current_email ,
		'meta_compare' => '='
	));
	$fundraiserTotal = count($fundraisers_list);
	
	
	
		$total_referral_comm = 0;
		foreach($fundraisers_list as $fundraisers) {
			$ID = $fundraisers->data->ID;
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
			$total_referral_comm = $total_referral_comm + $get_referral_commission_amount; 
	} ?>


<!-- <img src="header.jpg" class="account-banner-img"> -->
<section class="container my-5 py-5">

	<div class="tab-content res-tabingcls" id="inner-tabContent">
	  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-outstanding-tab" tabindex="0">


	  	<ul class="nav nav-tabs justify-content-center border-0" id="myTab" role="tablist">
		  <li class="nav-item" role="presentation">
		    <button class="nav-link active border-0" id="account-information-tab" data-bs-toggle="tab" data-bs-target="#account-information-tab-pane" type="button" role="tab" aria-controls="account-information-tab-pane" aria-selected="true">Account Information</button>
		  </li>
		  <li class="nav-item" role="presentation">
		    <button class="nav-link border-0" id="manage-fundraisers-tab" data-bs-toggle="tab" data-bs-target="#manage-fundraisers-tab-pane" type="button" role="tab" aria-controls="manage-fundraisers-tab-pane" aria-selected="false">Manage Fundraisers</button>
		  </li>
		  <li class="nav-item" role="presentation">
		    <button class="nav-link border-0" id="payment-history-tab" data-bs-toggle="tab" data-bs-target="#payment-history-tab-pane" type="button" role="tab" aria-controls="payment-history-tab-pane" aria-selected="false">Payment History</button>
		  </li>
		  <li class="nav-item" role="presentation">
		    <button class="nav-link border-0" id="fundraisers-reports-tab" data-bs-toggle="tab" data-bs-target="#fundraisers-reports-tab-pane" type="button" role="tab" aria-controls="fundraisers-reports-tab-pane" aria-selected="false">Fundraisers Reports</button>
		  </li>
		  <li class="nav-item" role="presentation">
		    <button class="nav-link border-0" id="order-entry-tab" data-bs-toggle="tab" data-bs-target="#order-entry-tab-pane" type="button" role="tab" aria-controls="order-entry-tab-pane" aria-selected="false">Order Entry</button>
		  </li>
		  <li class="nav-item" role="presentation">
		    <button class="nav-link border-0" id="fundraisers-operation-entry-tab" data-bs-toggle="tab" data-bs-target="#fundraisers-operation-tab-pane" type="button" role="tab" aria-controls="fundraisers-operation-tab-pane" aria-selected="false">Fundraisers Operation</button>
		  </li>
		  
		</ul>
		<div class="tab-content inner-tabContent" id="myTabContent">
		  <div class="tab-pane fade show active" id="account-information-tab-pane" role="tabpanel" aria-labelledby="account-information-tab" tabindex="0">
  			<div class="row justify-content-center mb-5 m-auto w-100">
				<div class="col p-0 your-account-balance m-2">
					<h1 class="inner-tab-label mb-2 "><?php echo $fundraiserTotal;?></h1>
					<h5 class="inner-tab-title">Total Fundraisers</h5>
					<img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2023/04/avatar.png" class="account-info-img">
				</div>
				<div class="col p-0 your-account-balance m-2">
					<h1 class="inner-tab-label mb-2 ">$<?php echo $total_referral_comm;?></h1>
					<h5 class="inner-tab-title">Total Balance:</h5>
					<img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2023/04/money.png" class="account-info-img">
				</div>
				<!-- <div class="col p-0 your-account-balance m-2">
					<h1 class="inner-tab-label mb-2 ">80$</h1>
					<h5 class="inner-tab-title">Your A/C Balance:</h5>
				</div> -->
			</div>
			<div class="row justify-content-center mb-5 m-auto w-100">
				<div  class="col p-0">
					<h5 class="inner-tab-title">First Name:</h5>
					<p class="inner-tab-label mb-0"><?php echo $firstname;?></p>
				</div>
				<div  class="col p-0">
					<h5 class="inner-tab-title">Last Name:</h5>
					<p class="inner-tab-label mb-0"><?php echo $lastname;?></p>
				</div>
				<div  class="col p-0">
					<h5 class="inner-tab-title">Email:</h5>
					<p class="inner-tab-label mb-0"><?php echo $current_email;?></p>
				</div>
			</div>
			<div class="row justify-content-center mb-5 m-auto w-100">
				<div  class="col p-0">
				<!--	<h5 class="inner-tab-title">Sex:</h5>
					<p class="inner-tab-label mb-0"><?php // echo $sex;?></p>-->
					<h5 class="inner-tab-title">Phone Number:</h5>
					<p class="inner-tab-label mb-0"><?php echo $phone;?></p>
				</div>
				<div  class="col p-0">
						<h5 class="inner-tab-title">Company:</h5>
					<p class="inner-tab-label mb-0"><?php echo $reps_company;?></p>
				</div>
				<div  class="col p-0">
				
				</div>
			</div>
			<div class="row justify-content-center mb-1	 m-auto w-100">
				<div  class="col p-0">
					<h5 class="inner-tab-title">Address:</h5>
					<p class="inner-tab-label mb-0"><?php echo $reps_address;?></p>
				</div>
				<div  class="col p-0">
					<h5 class="inner-tab-title">City:</h5>
					<p class="inner-tab-label mb-0"><?php echo $reps_city;?></p>
				</div>
				<div  class="col p-0">
					<h5 class="inner-tab-title">Country:</h5>
					<p class="inner-tab-label mb-0"><?php echo $reps_country;?></p>
				</div>
			</div>
		  </div>
		  <div class="tab-pane fade" id="manage-fundraisers-tab-pane" role="tabpanel" aria-labelledby="manage-fundraisers-tab" tabindex="0">
			<button type="button" class="btn btn-dark mb-4 lineinbtn" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Create A New Fundraisers</button>

			<div class="modal fade popsection" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
			    <div class="modal-dialog modal-dialog-centered">
			        <div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Create A New Fundraisers</h5>
							<button type="button" class="btn close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="frontend_fundraiser_regi"><?php echo do_shortcode('[user_registration_form id="21998"]'); ?> </div>
						</div>			      
					</div>
			    </div>
			</div>
			<table>
				<tr>
					<th>Fundraisers ID</th>
					<th>Fundraisers Name</th>
					<th>Fundraisers Email</th>					
					<th>Firstname</th>
					<th>Lastname</th>
				</tr>
				<?php foreach($fundraisers_list as $fundraisers) {
					$ID = $fundraisers->data->ID;
	            	$name = $fundraisers->data->user_login;
	            	$email = $fundraisers->data->user_email;
	            	$firstname =  get_user_meta($fundraisers->data->ID,'first_name',true);
	            	$lastname =  get_user_meta($fundraisers->data->ID,'last_name',true);
	            	$lastname =  get_user_meta($fundraisers->data->ID,'last_name',true);
				?>
				<tr>
					<td><?php echo $ID;?></td>
					<td><?php echo $name;?></td>
					<td><?php echo $email;?></td>
					<td><?php echo $firstname;?></td>
					<td><?php echo $lastname;?></td>
				</tr>
			<?php } ?>
				
			</table>
		  </div>
		  <div class="tab-pane fade" id="payment-history-tab-pane" role="tabpanel" aria-labelledby="payment-history-tab" tabindex="0">
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
		  </div>
		  <div class="tab-pane fade" id="fundraisers-reports-tab-pane" role="tabpanel" aria-labelledby="fundraisers-reports-tab" tabindex="0">
			<table>
				<tr>
					<th>Fundraisers ID</th>
					<th>Fundraisers Name</th>
					<th>Online Payment</th>
					<th>Catalog Payment</th>
					<th>Total Payment</th>
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
					<td>$<?php echo $total_sale_so_far;?></td>					
					<td>$<?php echo $total_sale_so_far_Catlog;?></td>
					<td>$<?php echo $totalAmount;?></td>
				</tr>
			<?php } ?>
		
				
			</table>
		  </div>
		  <div class="tab-pane fade" id="order-entry-tab-pane" role="tabpanel" aria-labelledby="order-entry-tab" tabindex="0">
		  	<div class="search-item">Select Fundraisers</div>
				<div class="" style="text-align:right;">
					<select id="fundraisersList" name="fundraisersList" class="form-control" />
						<option value="Selected">Select fundraisers</option>
						<?php foreach($fundraisers_list as $fundraisers){ ?>
								<option value="<?php echo $fundraisers->data->user_login;?>"><?php echo $fundraisers->data->user_login;?></option>							
						<?php }	?>					
					</select>
				</div>
				
				<div class="search-item mt-5">Select Category</div>
				
				<div class="tab-content" id="myTabContent">
					
					
				  <div class="tab-pane fade show active" id="filter-by-brochures" role="tabpanel" aria-labelledby="filter-by-brochures-tab">
						<?php /*?><select id="sectionChooser" class="form-control mt-4" >
							<option value="brochures-hide" >Brochures</option>
							<option value="brochures-show">Valentines</option>
						</select> <?php */?>
                        <select id="cat_type" name="cat_type" class="form-control" />
							<option value="Selected">Choose Type</option>
							<?php 
							 $woo_cat_args = array(
								'taxonomy'     => 'product_cat',
								'orderby'      => 'name',
								'hide_empty'   => 1,
							);
							$woo_categories = get_categories( $woo_cat_args );						
							foreach($woo_categories as $pro_cat){ ?>
								<option value="<?php echo $pro_cat->slug;?>"><?php echo $pro_cat->name;?></option>							
							<?php }	?>
						</select>
						
						
						<div class="product-list">
							<div class="mt-5">
								<div class="row">
									<div class="col">
										<div id="category-post-content"></div>										
									</div>
								</div>
							</div>
						</div>
				  </div>
				  <?php /*?><div class="tab-pane fade" id="search-all-brochures" role="tabpanel" aria-labelledby="search-all-brochures-tab">
						<label for="inputAddress" class="form-label mt-3">Search Brochures</label>
						<input type="text" class="form-control" id="inputAddress">
						<div class="product-list">

							<div class="mt-5">
								<div class="row">
									<div class="col">
										<div class="card card-body">
											<div class="d-flex media align-items-center text-lg-left flex-column flex-lg-row">
												<div class="mr-2 mb-3 mb-lg-0 col-2">
													<img src="https://glowscentedcandles.com/wp-content/uploads/2018/06/large_261_Be_love.png" width="90" height="90" alt="">
												</div>
												<div class="media-body col-6">
													<h4 class="media-title font-weight-semibold">
														<a href="#" data-abc="true">DESIGNER 16OZ TINS</a>
													</h4>
													<h6 class="mb-0 font-weight-semibold">$459.99</h6>
												</div>
												<div class="form-outline  col-2">
													<label class="form-label" for="typeNumber1">Quantity</label>
													<input type="number" id="typeNumber" class="form-control" />
												</div>
												<div class=" text-center col-2">
													
													<button type="button" class="btn btn-dark mx-auto text-white">Add to cart</button>
												</div>
											</div>
										</div>
										<div class="card card-body mt-3">
											<div class="d-flex media align-items-center text-lg-left flex-column flex-lg-row">
												<div class="mr-2 mb-3 mb-lg-0 col-2">
													<img src="https://glowscentedcandles.com/wp-content/uploads/2018/06/large_286_tin_NamasteInBed.jpg" width="90" height="90" alt="">
												</div>
												<div class="media-body col-6">
													<h4 class="media-title font-weight-semibold">
														<a href="#" data-abc="true">DESIGNER 16OZ TINS</a>
													</h4>
													<h6 class="mb-0 font-weight-semibold">$612.99</h6>
												</div>
												<div class="form-outline  col-2">
													<label class="form-label" for="typeNumber1">Quantity</label>
													<input type="number" id="typeNumber" class="form-control" />
												</div>
												<div class=" text-center col-2">

													<button type="button" class="btn btn-dark mx-auto text-white">Add to cart</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>
				  </div><?php */?>
				</div>

		  </div>
		  <div class="tab-pane fade" id="fundraisers-operation-tab-pane" role="tabpanel" aria-labelledby="fundraisers-operation-entry-tab" tabindex="0">
				<h3 class="mb-3">Fundraisers-operation</h3>
				
				<div class="tab-content" id="myTabContent">
				  <div class="tab-pane fade show active" id="filter-by-brochures" role="tabpanel" aria-labelledby="filter-by-brochures-tab">
						<?php //include("fundraisers-operation-frontend.php");?>

				  </div>
				</div>

		  </div>
		
		
		
		
		</div>
		
	  </div>
	  
	</div>
</section>


<!-- <img src="footer.jpg" class="account-banner-img"> -->


<script>
	jQuery(".resultshow").click(function(){
		jQuery(".resulttabel965").css("display","block");
	});
	jQuery(".close").click(function(){
		jQuery(".popsection.modal").css("display","none");
		jQuery(".popsection.modal").removeClass("show");
		jQuery(".modal-backdrop").removeClass("modal-backdrop fade show");
	});


$("#sectionChooser").change(function(){
    if($(this).val() == "brochures-show") {
       $('.product-list').addClass('show');
    } else {
       $('.product-list').removeClass('show');
    }
});

$('#cat_type').on('change', function() {
    // Save value in localstorage
    localStorage.setItem("cat_type", $(this).val());
    $('.product-list').addClass('show');
    
    
    var catname = $(this).val();
    var ajaxurl = '<?php echo admin_url( "admin-ajax.php" ); //must echo it ?>';
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {"action": "load-filter", cat: catname },
        success: function(response) {
            jQuery("#category-post-content").html(response);
            jQuery("#loading-animation").hide();
            return false;
        }
    });
 });

$('#fundraisersList').on('change', function() {
    // Save value in localstorage
    localStorage.setItem("fundraisersList", $(this).val());
    
    var fundraisersName = $(this).val();
    var ajaxurl = '<?php echo admin_url( "admin-ajax.php" ); //must echo it ?>';
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {"action": "save_fundraisers", name: fundraisersName },
        success: function(response){
			alert("fundraisers added successfully");
        }
    });
 });


jQuery(document).ready(function($){
	$(document).on('click','button.nav-link',function(e){
		localStorage.setItem('activeTab', $(e.target).attr('id'));		
	});
	
    var activeTab = localStorage.getItem('activeTab');
    if(activeTab){
        $('button.nav-link[id="' + activeTab + '"]').tab('show');
    }else{
    	$('#account-information-tab').tab('show');
    }
    
    
    if ($('#cat_type').length) {
        $('#cat_type').val(localStorage.getItem("cat_type"));
        $('.current_category').html(localStorage.getItem("cat_type"));
        $('.product-list').addClass('show');
    }
});

</script>
<?php } 
 else{
echo "please login first";} ?>

<?php get_footer();?>