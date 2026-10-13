<?php
/*
Cron Job: Create Merge Order */

	function wpfunc_merge_order_processing_call(){
			$arr_FIDD=array();
			$c=0;
			$arg=array('post_type'=>'close_out','posts_per_page' => 20,'order' => 'DESC');
			$table_query= new WP_Query();
			$table_query->query($arg);
			if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$ID = get_post_meta( $postid, 'close out_fundraiser_id', true );
					$order_quantity_test=0;
					$args = array(
									'status' => array('wc-waiting-call'),
									'meta_key' => 'fundraiser_field',
									'meta_value' => $ID,
									'limit' => -1
							);
					$orders = wc_get_orders($args);
					$totalOrdersQ=count($orders);
					$mID=get_user_meta($ID, "wc_ship_mergeOrderID", true);
					$mQanty=get_user_meta($ID, "wc_ship_mergeOrderQuanty", true);
					$first_catlog_order_for_cron_job=get_user_meta($ID, "wp_meta_first_catlog_order_for_cron_job", true);
					
					if(empty($mID) && empty($mQanty) && ($first_catlog_order_for_cron_job=="Yes") && !empty($totalOrdersQ))
					{
						if(!in_array($ID,$arr_FIDD)){
							$arr_FIDD[$c]=$ID;
							$c++;
						}
					} 
					
				endwhile;
			endif;
			//print_r($arr_FIDD);
			if(!empty($arr_FIDD))
			{
				for($e=0;$e<count($arr_FIDD);$e++)
				{
						$IDT=$arr_FIDD[$e];
						$args = array(
										'status' => array('wc-waiting-call'),
										'meta_key' => 'fundraiser_field',
										'meta_value' => $IDT,
										'limit' => -1
								);
						$orders = wc_get_orders($args);
						$totalOrdersQ=count($orders);
						$mID=get_user_meta($IDT, "wc_ship_mergeOrderID", true);
						$mQanty=get_user_meta($IDT, "wc_ship_mergeOrderQuanty", true);
						$first_catlog_order_for_cron_job=get_user_meta($IDT, "wp_meta_first_catlog_order_for_cron_job", true);
						
						if(empty($mID) && empty($mQanty) && ($first_catlog_order_for_cron_job=="Yes") && !empty($totalOrdersQ))
						{
						
						$merge_productlist=array();
						$prID=0;
						$ship_orders_ID=array();
						$total_order=array();
						$cal_seller_arr=array();
						foreach ( $orders as $forder ) 
						{			
							$order = wc_get_order( $forder->ID );
							//$order = new WC_Order($forder->ID);
							$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
							$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
										
							if($order_type=="ship-to-school")
							{
								if ($scat==1) { 
											 
													//Active/Inactive 
													$order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
													$extract_date = $order_date;
													$inactive_date = get_user_meta($frID3, "user_close_out_date_inactive", true);
													$inactive_s = get_user_meta($frID3, "user_action_status", true);
												
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
												
													if ($in_s==1)
													{		
															$slrID  = get_post_meta($order->get_id(), 'wc_seller_field', true );
															if($slrID=="custom")
															{
																$cal_seller=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
															}
															else if(is_numeric($slrID))
															{
																 $slr_info  = get_userdata($slrID);
																 $cal_seller = $slr_info->first_name." ".$slr_info->last_name; 
															}
															else
															{}
															if(!in_array($cal_seller,$cal_seller_arr))
															{
																$cal_seller_arr[]=$cal_seller;
															}
															foreach ($order->get_items() as $item_key => $item_values): 
																$item_data = $item_values->get_data(); 
																$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
																if($order_type=="ship-to-school") 
																{
																	$order_quantity_test += $item_data['quantity']; 
																	
																	$prID = $item_data['product_id'];
																	$prName = $item_data['name'];
																	$quanty = $item_data['quantity'];
																	$ShipOrderID=$order->get_id();
																	$total_order[]=$order->get_id();
																	$merge_productlist[] = array('ID' => $prID,'p_name' => $prName,'p_qty' => $quanty,'o_id'=>$order->get_id());
																	if(!in_array($ShipOrderID,$ship_orders_ID))
																	{
																		$ship_orders_ID[]=$ShipOrderID;
																		
																	}
																	
																	
																}
																
														endforeach; 
														
													}
										} 
										
									}
							}
							

							// Create New Merge Order 
							
							if(!empty($cal_seller_arr))
							{
								$cal_total_seller=count($cal_seller_arr);
								$seller_name_line = implode(",",$cal_seller_arr);
							}
							$fname =  get_user_meta( $IDT, 'first_name', true );
							$lname = get_user_meta( $IDT, 'last_name', true );
							$company  = get_user_meta( $IDT, 'user_registration_name_of_school_business', true );
							$user_info = get_userdata($IDT);
							$email = $user_info->user_email;
							$address_1 = get_user_meta( $IDT, 'user_registration_street_address', true );
							$address_2  = "";
							$city = get_user_meta( $IDT, 'user_registration_City2', true );
							$phone  = get_user_meta( $IDT, 'user_registration_input_box_phone', true );
							$postcode  = get_user_meta( $IDT, 'user_registration_input_box_zip', true );
							$country  =  get_user_meta( $IDT, 'billing_country', true );
							$state  =  get_user_meta( $IDT, 'user_registration_select_state', true );

							$countries = new WC_Countries(); // Get an instance of the WC_Countries Object
							$countries_array = $countries->get_countries();
							$country_name = $countries_array[$country];
							
							$country_states = $countries->get_states( $country ); // Get the states array from a country code

							foreach( $country_states as $key=>$wc_states ) 
							{
								if($state==$wc_states)
								{
									$scode_state=$key;
								}
							}
							if(empty($fname))
							{
								$fname="";
							}
							if(empty($lname))
							{
								$lname="";
							}
							if(empty($company))
							{
								$company="";
							}
							if(empty($email))
							{
								$email="";
							}
							if(empty($address_1))
							{
								$address_1="";
							}
							if(empty($city))
							{
								$city="";
							}
							if(empty($postcode))
							{
								$postcode="";
							}
							if(empty($country_name))
							{
								$country_name="";
							}
							if(empty($state))
							{
								$state="";
							}
							if(empty($scode_state))
							{
								$scode_state="";
							}
									
							$address = array(
							'first_name' => $fname,
							'last_name'  => $lname,
							'company'    => $company,
							'email'      => $email,
							'phone'      => $phone,
							'address_1'  => $address_1,
							'address_2'  => '', 
							'city'       => $city,
							'state'      => $scode_state,
							'postcode'   => $postcode,
							'country'    => $country
							);
							
						  
							// create Fee object
							$fee = new WC_Order_Item_Fee();
							$fee->set_name( 'Processing fee' );
							$total_no_of_orders=count($ship_orders_ID);
							$p_fee = $total_no_of_orders*1.95;
							$fee->set_amount( $p_fee );
							$fee->set_total( $p_fee );
							
							// add to order
							$norder = wc_create_order();
							
							// Custom Meta Saving
							$morder_type = "ship-to-school";
							$ship_merge_orders_id=implode(',',$ship_orders_ID);
							update_post_meta($norder->ID, 'wc_order_type', $morder_type );
							update_post_meta($norder->ID, 'fundraiser_field', $IDT );
							update_post_meta($norder->ID, 'ship_with_catalog', 1);
							update_post_meta($norder->ID, 'ship_orders_ids', $ship_merge_orders_id );
							update_post_meta($norder->ID, 'ship_orders_total_items', $order_quantity_test );
							update_post_meta($norder->ID,'wc_seller_total_mergeOrder',$cal_total_seller);
							update_post_meta($norder->ID,'wc_seller_name_mergeOrder',$seller_name_line);
							update_post_meta($norder->ID,'wc_mergeOrder_createdby_cron_job',"yes");
							
							$norder->set_address( $address, 'billing' );
							$norder->set_address( $address, 'shipping' );
							
							$norder->add_item( $fee );
							
							for($i=0;$i<count($merge_productlist);$i++)
							{
								$norder->add_product( wc_get_product( $merge_productlist[$i]['ID'] ), $merge_productlist[$i]['p_qty'] );
							}
							$note = "Fund #ID: ".$IDT;
							//$norder->add_order_note( $note );
							$norder->set_customer_note($note);
							
							$payment_gateways = WC()->payment_gateways->payment_gateways();
							$norder->set_payment_method( $payment_gateways[ 'authorize' ] );
							
							$item = new WC_Order_Item_Shipping();

							$item->set_method_title( "Free Shipping" );
							$item->set_method_id( "shiptoschool" );
							$norder->add_item( $item );
							
							$norder->set_status( 'wc-processing-call' );
							$norder->calculate_totals();
							$norder->save();
							
							$merge_orderID = $norder->ID;
							if(!empty($merge_orderID))
							{
								update_user_meta($IDT, 'wc_ship_mergeOrderID', $merge_orderID );
								update_user_meta($IDT, 'wc_ship_mergeOrderQuanty', $order_quantity_test );
							}
							else
							{
								
							}
						
					}
				}
			}
	}
	add_action( 'fundraiser_email_scheduled_cron', 'wpfunc_merge_order_processing_call' );

/*
Scheduled Cron Job: Automatically closes the fundraiser at 30 days and generates a merge order.
*/
function  wpfunc_30days_merge_order_processing_call()
{
    /*
    Cron Job: Trigger when 30 days are completed after fundraiser end date
    */

    $queue = array();

    $args = array(
        'role'    => 'Fundraiser',
        'orderby' => 'user_registered',
        'order'   => 'DESC'
    );

    $users = get_users($args);

    date_default_timezone_set('America/Chicago');
    $current_date = date('Y-m-d');
    $now = strtotime($current_date);

    foreach ($users as $user) {
		$inacive_check=get_user_meta($user->ID, 'user_action_status' , true );
		if(empty($inacive_check) || $inacive_check==1)
		{

        $ed = get_user_meta($user->ID, 'user_registration_end_date', true);

        if (!empty($ed)) {

            $end_date = strtotime($ed);

            if ($now > $end_date) {

                $datediff = $now - $end_date;
                $days_completed = floor($datediff / (60 * 60 * 24));

                if ($days_completed >= 31) {

                    $meta_check = get_user_meta(
                        $user->ID,
                        '30_days_completed_cron_job',
                        true
                    );

                    if (empty($meta_check)) {
						$IDT=$user->ID;
						$args = array(
										'status' => array('wc-waiting-call'),
										'meta_key' => 'fundraiser_field',
										'meta_value' => $IDT,
										'limit' => -1
								);
						$orders = wc_get_orders($args);
						$totalOrdersQ=count($orders);
						$mID=get_user_meta($IDT, "wc_ship_mergeOrderID", true);
						$mQanty=get_user_meta($IDT, "wc_ship_mergeOrderQuanty", true);
						$first_catlog_order_for_cron_job=get_user_meta($IDT, "wp_meta_first_catlog_order_for_cron_job", true);
						
						if(empty($mID) && empty($mQanty) && empty($first_catlog_order_for_cron_job) && !empty($totalOrdersQ))
						{

                        $queue[] = array(
                            'userid'   => $user->ID,
                            'email'    => $user->user_email,
                            'end_date' => $ed,
							'diffr' =>  $days_completed
                        );
						}
                    }
                }
            }
        }
	  }
	}


    foreach ($queue as $item) {

get_fundraiser_30days_completed_campaign(
            $item['userid'],
            $item['email'],
            $item['end_date']
        );

        
    }
}
function get_fundraiser_30days_completed_campaign($id,$email,$ed){
					
						$IDT=$id;
						$inacive_check=get_user_meta($IDT, 'user_action_status' , true );
						if(empty($inacive_check) || $inacive_check==1)
						{
						$args = array(
										'status' => array('wc-waiting-call'),
										'meta_key' => 'fundraiser_field',
										'meta_value' => $IDT,
										'limit' => -1
								);
						$orders = wc_get_orders($args);
						$totalOrdersQ=count($orders);
						$mID=get_user_meta($IDT, "wc_ship_mergeOrderID", true);
						$mQanty=get_user_meta($IDT, "wc_ship_mergeOrderQuanty", true);
						$first_catlog_order_for_cron_job=get_user_meta($IDT, "wp_meta_first_catlog_order_for_cron_job", true);
						
						if(empty($mID) && empty($mQanty) && empty($first_catlog_order_for_cron_job) && !empty($totalOrdersQ))
						{
						
						$merge_productlist=array();
						$prID=0;
						$ship_orders_ID=array();
						$total_order=array();
						$cal_seller_arr=array();
						foreach ( $orders as $forder ) 
						{			
							$order = wc_get_order( $forder->ID );
							//$order = new WC_Order($forder->ID);
							$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
							$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
										
							if($order_type=="ship-to-school")
							{
								if ($scat==1) { 
											 
													//Active/Inactive 
													$order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
													$extract_date = $order_date;
													$inactive_date = get_user_meta($frID3, "user_close_out_date_inactive", true);
													$inactive_s = get_user_meta($frID3, "user_action_status", true);
												
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
												
													if ($in_s==1)
													{		
															$slrID  = get_post_meta($order->get_id(), 'wc_seller_field', true );
															if($slrID=="custom")
															{
																$cal_seller=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
															}
															else if(is_numeric($slrID))
															{
																 $slr_info  = get_userdata($slrID);
																 $cal_seller = $slr_info->first_name." ".$slr_info->last_name; 
															}
															else
															{}
															if(!in_array($cal_seller,$cal_seller_arr))
															{
																$cal_seller_arr[]=$cal_seller;
															}
															foreach ($order->get_items() as $item_key => $item_values): 
																$item_data = $item_values->get_data(); 
																$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
																if($order_type=="ship-to-school") 
																{
																	$order_quantity_test += $item_data['quantity']; 
																	
																	$prID = $item_data['product_id'];
																	$prName = $item_data['name'];
																	$quanty = $item_data['quantity'];
																	$ShipOrderID=$order->get_id();
																	$total_order[]=$order->get_id();
																	$merge_productlist[] = array('ID' => $prID,'p_name' => $prName,'p_qty' => $quanty,'o_id'=>$order->get_id());
																	if(!in_array($ShipOrderID,$ship_orders_ID))
																	{
																		$ship_orders_ID[]=$ShipOrderID;
																		
																	}
																	
																	
																}
																
														endforeach; 
														
													}
										} 
										
									}
							}
							

							// Create New Merge Order 
							
							if(!empty($cal_seller_arr))
							{
								$cal_total_seller=count($cal_seller_arr);
								$seller_name_line = implode(",",$cal_seller_arr);
							}
							$fname =  get_user_meta( $IDT, 'first_name', true );
							$lname = get_user_meta( $IDT, 'last_name', true );
							$company  = get_user_meta( $IDT, 'user_registration_name_of_school_business', true );
							$user_info = get_userdata($IDT);
							$email = $user_info->user_email;
							$address_1 = get_user_meta( $IDT, 'user_registration_street_address', true );
							$address_2  = "";
							$city = get_user_meta( $IDT, 'user_registration_City2', true );
							$phone  = get_user_meta( $IDT, 'user_registration_input_box_phone', true );
							$postcode  = get_user_meta( $IDT, 'user_registration_input_box_zip', true );
							$country  =  get_user_meta( $IDT, 'billing_country', true );
							$state  =  get_user_meta( $IDT, 'user_registration_select_state', true );

							$countries = new WC_Countries(); // Get an instance of the WC_Countries Object
							$countries_array = $countries->get_countries();
							$country_name = $countries_array[$country];
							
							$country_states = $countries->get_states( $country ); // Get the states array from a country code

							foreach( $country_states as $key=>$wc_states ) 
							{
								if($state==$wc_states)
								{
									$scode_state=$key;
								}
							}
							if(empty($fname))
							{
								$fname="";
							}
							if(empty($lname))
							{
								$lname="";
							}
							if(empty($company))
							{
								$company="";
							}
							if(empty($email))
							{
								$email="";
							}
							if(empty($address_1))
							{
								$address_1="";
							}
							if(empty($city))
							{
								$city="";
							}
							if(empty($postcode))
							{
								$postcode="";
							}
							if(empty($country_name))
							{
								$country_name="";
							}
							if(empty($state))
							{
								$state="";
							}
							if(empty($scode_state))
							{
								$scode_state="";
							}
									
							$address = array(
							'first_name' => $fname,
							'last_name'  => $lname,
							'company'    => $company,
							'email'      => $email,
							'phone'      => $phone,
							'address_1'  => $address_1,
							'address_2'  => '', 
							'city'       => $city,
							'state'      => $scode_state,
							'postcode'   => $postcode,
							'country'    => $country
							);
							
						  
							// create Fee object
							$fee = new WC_Order_Item_Fee();
							$fee->set_name( 'Processing fee' );
							$total_no_of_orders=count($ship_orders_ID);
							$p_fee = $total_no_of_orders*1.95;
							$fee->set_amount( $p_fee );
							$fee->set_total( $p_fee );
							
							// add to order
							$norder = wc_create_order();
							
							// Custom Meta Saving
							$morder_type = "ship-to-school";
							$ship_merge_orders_id=implode(',',$ship_orders_ID);
							update_post_meta($norder->ID, 'wc_order_type', $morder_type );
							update_post_meta($norder->ID, 'fundraiser_field', $IDT );
							update_post_meta($norder->ID, 'ship_with_catalog', 1);
							update_post_meta($norder->ID, 'ship_orders_ids', $ship_merge_orders_id );
							update_post_meta($norder->ID, 'ship_orders_total_items', $order_quantity_test );
							update_post_meta($norder->ID,'wc_seller_total_mergeOrder',$cal_total_seller);
							update_post_meta($norder->ID,'wc_seller_name_mergeOrder',$seller_name_line);
							update_post_meta($norder->ID,'wc_mergeOrder_createdby_cron_job',"yes");
							update_post_meta($norder->ID,'wc_mergeOrder_30Daysby_cron_job',"yes");
							update_post_meta($norder->ID,'auto_close',"yes");

							$norder->set_address( $address, 'billing' );
							$norder->set_address( $address, 'shipping' );
							
							$norder->add_item( $fee );
							
							for($i=0;$i<count($merge_productlist);$i++)
							{
								$norder->add_product( wc_get_product( $merge_productlist[$i]['ID'] ), $merge_productlist[$i]['p_qty'] );
							}
							$note = "Fund #ID: ".$IDT;
							//$norder->add_order_note( $note );
							$norder->set_customer_note($note);
							
							$payment_gateways = WC()->payment_gateways->payment_gateways();
							$norder->set_payment_method( $payment_gateways[ 'authorize' ] );
							
							$item = new WC_Order_Item_Shipping();

							$item->set_method_title( "Free Shipping" );
							$item->set_method_id( "shiptoschool" );
							$norder->add_item( $item );
							
							$norder->set_status( 'wc-processing-call' );
							$norder->calculate_totals();
							$norder->save();
							
							$merge_orderID = $norder->ID;
							if(!empty($merge_orderID))
							{
								update_user_meta($IDT, 'wc_ship_mergeOrderID', $merge_orderID );
								update_user_meta($IDT, 'wc_ship_mergeOrderQuanty', $order_quantity_test );
							}
							else
							{
								
							}
							
							// Prevent duplicate processing
							update_user_meta($IDT, '30_days_completed_cron_job', 1);
							
							/* Close Out Email */
							$co_email=get_user_meta($IDT, 'closeout_email_30dayscompleted_bycron_job', true);
							if(empty($co_email)){
							$get_referral = get_user_meta($IDT,'user_registration_referral_email',true);
							$referralUser = get_user_by( 'email', $get_referral );        
							$referralUserID = $referralUser->data->ID;
							close_out_fundraiser_cron_job_func($IDT,$referralUserID);
							}
						
					}
				
	    }	
	}
	add_action( 'fundraiser_email_scheduled_cron', 'wpfunc_30days_merge_order_processing_call' );
	
	function close_out_fundraiser_cron_job_func($id,$referralUID )
	{
		$ID = $id;
		$RID = $referralUID;
		$co_cj=get_user_meta($ID, '30_days_completed_cron_job', true);
		$co_email=get_user_meta($IDT, 'closeout_email_30dayscompleted_bycron_job', true);
		
		if(!empty($co_cj) && $co_cj==1){
			
		if(empty($co_email)){
		
		date_default_timezone_set('America/Chicago');
		$current_date = date('Y-m-d H:i:s a');
		$args=array('post_title'=>'Close Out #'.$ID, 'post_type'=>'close_out', 'post_status' => 'publish', 'post_author' => $ID);
	    $post_id = wp_insert_post($args,  true);
	
		if(!empty($post_id))
	    {
			if(!empty($RID))
			{
				update_post_meta( $post_id, 'close out_by_rep_id', $RID);
			}
			update_post_meta( $post_id , 'close out_fundraiser_id', $ID);
		    update_post_meta( $post_id , 'close out_fundraiser_date', $current_date); 
			update_user_meta($ID, "user_close_out_value", 1);
			update_user_meta($ID, "user_close_out_date_inactive", $current_date);
			update_user_meta($ID, 'user_action_status' , 2 );
		}
		

		$org=get_user_meta( $ID , 'user_registration_name_of_school_business' , true );
		if(empty($org))
		{
		$org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );
		}
		$f=get_user_meta( $ID , 'first_name' , true );		
		$l=get_user_meta( $ID , 'last_name' , true );	
		$user_info = get_userdata($ID);
        $email = $user_info->user_email;
        $to = "candace@glowscentedcandles.com";  
	    /*$to = "jatinarora0075@gmail.com"; */ 
	  
		$subject = "Auto Closed Fundraiser #".$ID;
		$body ='';
		ob_start();
		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		 <html lang="en">
		  <head>
			<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
			<title>
			 Glow Scented Candles
			</title>
		   <!--[if gte mso 9]>
			<style type="text/css">
		   
			 </style>
		   <![endif]-->  
		</head>
		<body style="margin: 0; padding: 0;" class="body">
		<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;background:#f2f2f2;width:100%;float:left;">
		   <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:40px auto;width: 920px;overflow: hidden;">
				<tr>
					 <td align="center" style="padding:30px; text-align:center;background:#333;">
						<h1 style="color:#ffffff;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left"> Fundraiser Close Out Request <?php echo "#".$ID; ?> </h1>
					</td>
				</tr>
					
				<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
				   <td align="center">
						<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;">
							<tr>
								<td align="center">
									<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
									<tr>
										<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: capitalize;line-height: 1.3;font-size: 14px;">Fundraiser ID: <strong><?php echo "#".$ID; ?></strong></td>
									</tr>
									<tr>
										<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: capitalize;line-height: 1.3;font-size: 14px;">Contact Name: <strong><?php  echo $f." ".$l; ?></strong></td>
									</tr>
									<tr>
										<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: capitalize;line-height: 1.3;font-size: 14px;">Contact Email: <strong><?php  echo $email; ?></strong></td>
									</tr>
									<tr>
										<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: capitalize;line-height: 1.3;font-size: 14px;">Organizations Name: <strong><?php  echo $org; ?></strong></td>
									</tr>
									<tr>
										<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: initial;line-height: 1.3;font-size: 14px;">Fundraiser campaign is auto closed.</td>
									</tr>
									</table>
								</td>
					
							</tr>
					 
						</table>
				  
					</td>
				</tr>
				
					
			</table>
			
			</div>
		  </body>
		</html>
	<?php 
		$body= ob_get_clean();
		$m_body=stripslashes($body);
		
		$headers = array('Content-Type: text/html; charset=UTF-8','From: ' . GLOW_FROM_EMAIL);
		$mailSuc= wp_mail( $to, $subject, $m_body, $headers );
	 	if($mailSuc){
			if(!empty($RID))
			{
				/*$mergeID=get_user_meta( $ID, 'wc_ship_mergeOrderID', true );
				$mergeQuanty=get_user_meta( $ID, 'wc_ship_mergeOrderQuanty', true );
				if(empty($mergeID) && empty($mergeQuanty)) { 
				 wp_send_json_success(array(
					'action' => "yes",
					'fund_id'=> $ID,
					'msg'=> "Close out request sent."
					));
				}
				else
				{
					 wp_send_json_success(array(
					'action' => "no",
					'fund_id'=> $ID,
					'msg'=> "Close out request sent."
					));
				}*/
			}
			else {
				echo "Close out request sent.";
			}
			update_user_meta($ID, 'closeout_email_30dayscompleted_bycron_job', 1);
			wp_close_out_send_email_cronjob_func($email);
			} else{
				echo "Error!!";
			}
			die();
		}
	}	
}
	
/* Close Out email Confirmation */ 
function wp_close_out_send_email_cronjob_func( $email)
{
		$to = $email;
		$subject = "Your Fundraiser Campaign is Auto Closed! ";
		$body ='';
	ob_start();
	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html lang="en">
  <head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>
     Glow Scented Candles
    </title>
   <!--[if gte mso 9]>
    <style type="text/css">
   
     </style>
   <![endif]-->  
</head>
<body style="margin: 0; padding: 0;" class="body">
<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;background:#f2f2f2;width:100%;float:left;">
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:40px auto;width: 700px;overflow: hidden;">
        <tr>
             <td align="center" style="padding:30px; text-align:center;background:#333;">
				<h1 style="color:#ffffff;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left"> Close Out Fundraiser Request </h1>
		  	</td>
		</tr>
		 <tr>
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
								<tr>
									<td align="left">
										<p style="font:17px/23px  PTSansRegular , Arial, Helvetica, sans-serif;color:#1b0f0e;">Hi </p>
										<p  style="font:17px/23px PTSansRegular, Arial, Helvetica, sans-serif;color:#1b0f0e;">Your fundraiser campaign is auto closed. we will work on this in next 1-3 business days. </p>
										
										<p style="font:17px/23px  PTSansRegular , Arial, Helvetica, sans-serif;color:#1b0f0e;">Thanks! </p>
								</td>
			
								</tr>
							
								
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		
    </table>
    </div>
  </body>
</html>
<?php 
		$body= ob_get_clean();
		$m_body=stripslashes($body);
		$headers = array('Content-Type: text/html; charset=UTF-8','From: ' . GLOW_FROM_EMAIL);
		$mailSuc= wp_mail( $to, $subject, $m_body, $headers );
	 	
		die();
		
	}	
?>