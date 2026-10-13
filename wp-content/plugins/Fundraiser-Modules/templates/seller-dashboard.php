<?php 

	get_header();

?>
<style>
.catalogue-sale .fundraiser-order-details-col.product-quantity, .catalogue-sale .fundraiser-order-details-col.product-price1, .catalogue-sale .fundraiser-order-details-col.all-order-subtotal1, .catalogue-sale .fundraiser-order-details-col.all-order-discount1, .catalogue-sale .fundraiser-order-details-col.all-order-discount-calc {
    width: 11%;
}
.fundraiser-dashboard-section .list_type
{
	padding:0;
}
.seller_total_sale
{
	width: 100%;
    padding: 0 25px;
    max-width: 1000px;
    margin: 0 auto;
}
</style>
<div class="container content-page">

		<div class="page-content sidebar-position-<?php esc_attr_e( $l['sidebar'] ); ?> sidebar-mobile-<?php esc_attr_e( class_exists('WooCommerce') && ( is_checkout() || is_cart() || is_account_page() || in_array( 'woocommerce-wishlist', get_body_class() ) ) ? $l['shop-sidebar-mobile'] : $l['sidebar-mobile'] ); ?>">

			<div class="row">

                  <?php do_action( 'et_page_heading' ); ?>                  

				<div class="content <?php esc_attr_e( $l['content-class'] ); ?>">

                    <div class="intro-content"><?php the_content(); ?></div>

                    <div class="fundraiser-dashboard-section report total-sale">
						
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
                            
                             $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'wc_seller_field',
								'meta_value' => $ID
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                             $Fund_ID = get_post_meta($order->ID, 'fundraiser_field', true );
							  $frID3 = get_post_meta($order->ID, 'wc_seller_field', true );
								  $frID4 = get_user_meta($frID2, 'user_registration_seller_fundraiser', true );
                         /*   if ($ID3 == $IDD) { */
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
									/*Active / inactive*/
									
									 $order_date = $order->order_date;
										$extract_date=$order_date;
										$inactive_date=get_user_meta($Fund_ID, "user_close_out_date_inactive", true);
										$inactive_s=get_user_meta( $Fund_ID, "user_action_status", true);
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
									
									
								  if ($frID4 != $order_customer_id) {
									  $order_type = get_post_meta($order->ID, 'wc_order_type', true );
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
								 else if ($frID4 == $order_customer_id) {
									  $order_type = get_post_meta($order->ID, 'wc_order_type', true );
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
								   if($order_type=="Catlog") 
									  {
										
                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                                                      
										$qty=$item_data['quantity']; 
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
								} 
								else{}
								} }
                            /*     } */
						}
						
						/*Manually Seller - online */
						$total_sale_so_far_manly_online=0;
						$rebate_so_far_manly_online=0;
						$item_qty_manly_online=0;
						$totalAmount=0;
						$fid=get_user_meta(  $ID , 'user_registration_seller_fundraiser' , true );
						$args = array(
							'status' => 'published',
							'meta_query' => array(
									'relation' => 'AND',
									array(
										'key'     => 'fundraiser_field',
										'value'   => $fid,
										'compare' => '='
									),
									array(
										'key'     => 'wc_seller_field',
										'value'   => 'custom',
										'compare' => '='
									),
									array(
										'key'     => 'wc_order_type',
										'value'   => array('Online'),
										'compare' => 'IN'
									),
								),
							'limit' => -1
							);
						 
                        $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
							$ID3 = get_post_meta($order->ID, 'wc_seller_field', true );
							if($ID3 == "custom") 
							{
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									
									/*Active / inactive*/
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
										
									$s_name=get_post_meta($order->ID, 'wc_seller_field_custom', true);
									$s_name_lower= strtolower($s_name);
									
									$trimmeddata_s_name = preg_replace('/^\s+|\s+$/', '', $s_name);
									$trimmeddata_s_name= strtolower($trimmeddata_s_name);
									
									$f=get_user_meta( $ID , 'first_name' , true );		
									$l=get_user_meta( $ID , 'last_name' , true );
									$f_t = preg_replace('/^\s+|\s+$/', '', $f);	
									$l_t = preg_replace('/^\s+|\s+$/', '', $l);	
									$fullname=$f_t." ".$l_t;	
									$fullname=strtolower($fullname);
									
									$trimmeddata_fullname = preg_replace('/^\s+|\s+$/', '', $fullname);	
									$trimmeddata_fullname= strtolower($trimmeddata_fullname);
									
									if($trimmeddata_fullname===$trimmeddata_s_name)
									{ 
										/* total Item  Sold */
										foreach ($order->get_items() as $item_key => $item_values): 
                                            $item_data = $item_values->get_data(); 
											$order_type = get_post_meta($order->ID, 'wc_order_type', true );
											if($order_type=="Online") 
											{
												$item_qty_manly_online  +=  $item_data['quantity']; 
											}
											else{}
										endforeach; 
										/* total Item  Sold */
									
										if ($frID3 != $order_customer_id) {
											$order_type = get_post_meta($order->ID, 'wc_order_type', true );
										if($order_type=="Online") 
										{
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far_manly_online+=$newamt;
										}
										else
										{
										 $total_sale_so_far_manly_online+=$order->get_subtotal();
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
											$rebate_so_far_manly_online+=$Discount;
										}
										else
										{
											$Discount    = ($percentage / 100) * $totalAmount;
											$Discount = number_format($Discount, 2, '.', '');
											$rebate_so_far_manly_online+=$Discount;
										}
									}
								} 
								else if ($frID3 == $order_customer_id) {
									  $order_type = get_post_meta($order->ID, 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far_manly_online+=$newamt;
										}
										else
										{
										 $total_sale_so_far_manly_online+=$order->get_subtotal();
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
											$rebate_so_far_manly_online+=$Discount;
										}
										else
										{
											$Discount    = ($percentage / 100) * $totalAmount;
											$Discount = number_format($Discount, 2, '.', '');
											$rebate_so_far_manly_online+=$Discount;
										}
									}
								} 
								else{}
								}
								
								} 
								}
						    } 
						}
						
						
						
						/* End */
						/* Ship to School Total Sales and Rebate */
						/* Calculate Catlog Quantity */
						 $arr_order_id2=array();
						 $total_cat_quantity =0; 						
						 $total_sale_so_far2=0;
						 $rebate_so_far2=0;		
						 $item_qty_fund_online=0;
						 
						 $args = array(
                                'status'   => "published",
                                'meta_key' => 'wc_seller_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                        $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
								$ID3 = get_post_meta($order->ID, 'wc_seller_field', true );
							   	$scat = get_post_meta($order->ID, 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($order->has_status('on-hold') || $order->has_status('completed') || $order->has_status('processing')) {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->ID, 'wc_order_type', true );
								  if($order_type=="Catlog") 
									 {
										 foreach ($order->get_items() as $item_key => $item_values): 
												$item_data = $item_values->get_data();
												$total_cat_quantity += $item_data['quantity']; 			//ship to school								
										  endforeach;	
								} } }
						}
							   
						/* Ship to School - manaully sellers */	   
						$total_sale_so_far_manly_shiptoschool=0;
                       $fid=get_user_meta(  $ID , 'user_registration_seller_fundraiser' , true );
						$args = array(
							'status' =>  array('wc-waiting-call'),
							'meta_query' => array(
									'relation' => 'AND',
									array(
										'key'     => 'fundraiser_field',
										'value'   => $fid,
										'compare' => '='
									),
									array(
										'key'     => 'wc_seller_field',
										'value'   => 'custom',
										'compare' => '='
									),
									array(
										'key'     => 'wc_order_type',
										'value'   => array('ship-to-school'),
										'compare' => 'IN'
									),
								),
							'limit' => -1
							);
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
								$ID3 = get_post_meta($order->ID, 'wc_seller_field', true );
							   	$scat = get_post_meta($order->ID, 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($ID3=="custom") {
									
								if ($scat==1) {
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
									
								  if ($frID3 != $order_customer_id) {
									$s_name=get_post_meta($order->ID, 'wc_seller_field_custom', true);
									$s_name_lower= strtolower($s_name);
									
									$trimmeddata_s_name = preg_replace('/^\s+|\s+$/', '', $s_name);
									$trimmeddata_s_name= strtolower($trimmeddata_s_name);
									
									$f=get_user_meta( $ID , 'first_name' , true );		
									$l=get_user_meta( $ID , 'last_name' , true );
									$f_t = preg_replace('/^\s+|\s+$/', '', $f);	
									$l_t = preg_replace('/^\s+|\s+$/', '', $l);	
									$fullname=$f_t." ".$l_t;	
									$fullname=strtolower($fullname);
									
									$trimmeddata_fullname = preg_replace('/^\s+|\s+$/', '', $fullname);	
									$trimmeddata_fullname= strtolower($trimmeddata_fullname);
									
									if($trimmeddata_fullname===$trimmeddata_s_name)
									{  
										$order_type = get_post_meta($order->ID, 'wc_order_type', true );
										if ($order_type=='ship-to-school') {
								
										$arr_order_id2[]=$order->ID;
								
										/*$total_sale_so_far+=$order->get_subtotal();*/
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far_manly_shiptoschool+=$newamt;
										}
										else
										{
										 $total_sale_so_far_manly_shiptoschool+=$order->get_subtotal();
										}
										$total_sale_so_far_manly_shiptoschool = number_format($total_sale_so_far_manly_shiptoschool, 2, '.', '');

                                        foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->ID, 'wc_order_type', true );
															if($order_type_2=="ship-to-school") 
															{
																$item_qty_fund_online2 +=  $item_data['quantity']; 
															}
															
															else{}
                                             endforeach; 
										}
								}
						} } } }
						}
						/*$percentage  = 40;
                        $percentFifty= 50;
					    $real_qty_catlog_online2=$total_cat_quantity+$item_qty_fund_online2;
						if ($real_qty_catlog_online2 > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFifty / 100) * $total_sale_so_far_manly_shiptoschool;
							$rebate_so_far_manly_shiptoschool = number_format($Discount, 2, '.', '');
							
						}
                        else
						{
                            $Discount    = ($percentage / 100) * $total_sale_so_far_manly_shiptoschool;
                            $rebate_so_far_manly_shiptoschool = number_format($Discount, 2, '.', '');
							
						}
						*/						
						/* End */	   
						/* Ship to School - register sellers */		   
							   
                            $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'wc_seller_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
								$ID3 = get_post_meta($order->ID, 'wc_seller_field', true );
							   	$scat = get_post_meta($order->ID, 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($scat==1) {
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
								  if ($frID3 != $order_customer_id) { 
										$order_type = get_post_meta($order->ID, 'wc_order_type', true );
                                        if ($order_type=='ship-to-school') {
											
											$arr_order_id2[]=$order->ID;
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
												$order_type_2 = get_post_meta($order->ID, 'wc_order_type', true );
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
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online+$item_qty_fund_online2;
						$total_sale_for_rebate=$total_sale_so_far2+$total_sale_so_far_manly_shiptoschool;
						if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFifty / 100) * $total_sale_for_rebate;
							$rebate_so_far2 = number_format($Discount, 2, '.', '');
							
						}
                        else
						{
                            $Discount    = ($percentage / 100) * $total_sale_for_rebate;
                            $rebate_so_far2 = number_format($Discount, 2, '.', '');
							
						}
						// End Ship to school
						
						//Ship to School
						
						$total_sale_fundraiser = number_format($total_sale_so_far, 2, '.', ''); 
						$total_rebate_fundraiser = number_format($rebate_so_far, 2, '.', '');
						
						$total_sale_fundraiser2 = number_format($total_sale_so_far2, 2, '.', ''); 
						$total_rebate_fundraiser2 = number_format($rebate_so_far2, 2, '.', '');
						
						$total_sale_fundraiser3 = $total_sale_fundraiser + $total_sale_fundraiser2+$total_sale_so_far_manly_shiptoschool+$total_sale_so_far_manly_online;
						
						$total_sale_fundraiser3 = number_format($total_sale_fundraiser3, 2, '.', '');
						
						$total_rebate_fundraiser3 = $rebate_so_far + $rebate_so_far2+$rebate_so_far_manly_online;
						
						$total_rebate_fundraiser3 = number_format($total_rebate_fundraiser3, 2, '.', '');
						
						//End Ship to school
						
                        ?>
						<h3>Seller Info</h3>
						<?php   $f=get_user_meta( $frID2 , 'first_name' , true );		
							   $l=get_user_meta( $frID2 , 'last_name' , true );	?>
                       <ul class="list_type">
                           <li>Your Fundraiser ID is: <strong><?php  echo  $seller_frID = get_user_meta($frID2, 'user_registration_seller_fundraiser', true ); ?></strong></li>
							<li>Your Seller link is <span class="seller-link"><strong><?php echo get_site_url(); ?>/?frid=<?php echo $seller_frID; ?>&seller_name=<?php echo $f." ".$l; ?></strong></span></li>
						</ul>
				   </div>
					<div class="seller_total_sale">
					     <h5>Total Sale So Far: <strong>$<span class="fr-total-sale1"><?php echo $total_sale_fundraiser3=number_format($total_sale_fundraiser3, 2, '.', ''); ?></span></strong></h5>
						 <h5>Total Rebate So Far: <strong>$<span class="fr-rebate-saleu1"><?php echo $total_rebate_fundraiser3=number_format($total_rebate_fundraiser3, 2, '.', ''); ?></span></strong></h5>
					</div>
                    

                    <div class="fundraiser-dashboard-section report online-sale">

                        <h3>Online Sale</h3>

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
								'meta_key' => 'wc_seller_field',
								'meta_value' => $ID

                            );

                            $orders = wc_get_orders($args);


                        foreach ( $orders as $order ) {
								 $Fund_ID = get_post_meta($order->ID, 'fundraiser_field', true );
                              $frID3 = get_post_meta($order->ID, 'wc_seller_field', true );
                             $frID4 = get_user_meta($frID2, 'user_registration_seller_fundraiser', true );
                            if ($frID3 == $frID2) { 

                                $order_data = $order->get_data();
								$order_customer_id = $order_data['customer_id']; 
                                
							if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
								
								/* Active/Inactive */	
								    $order_date = $order->order_date;
									$extract_date=$order_date;
									$inactive_date=get_user_meta($Fund_ID, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($Fund_ID, "user_action_status", true);
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
                                if ($order_customer_id != $frID4) {
									
								   $order_type = get_post_meta($order->ID, 'wc_order_type', true );
								   if($order_type=="Online") 
								   {
								?>
									
                                <div class="fundraiser-order-row sellermatch">

                                    <div class="fr-order-col fr-order-id"><?php echo $order->ID; ?></div>

                                    <div class="fr-order-col fr-order-date"><?php echo $order_data['date_created']->date('Y-m-d'); ?></div>

                                    <div class="fr-order-col fr-order-customer"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></div>

                                    <div class="fr-order-col fr-order-seller"><?php 

                                        $slrID = get_post_meta($order->ID, 'wc_seller_field', true );

                                        $slr_info = get_userdata($slrID);

                                        ?><?php echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; ?></div>

                                    <!--<div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span><span class="fr-order-amt"><?php echo $order->get_total(); ?></span></div>-->
									 <div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span>
                                       <span class="fr-order-amt"><?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></span>  
                                    </div>
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

                                   <!-- <div class="fundraiser-order-details-row fr-footer">

                                        <div class="fundraiser-order-details-col product-name">Order Subtotal</div>

                                        <div class="fundraiser-order-details-col product-quantity"></div>

                                        <div class="fundraiser-order-details-col all-order-subtotal">$<span></span></div>

                                    </div>

                                    <div class="fundraiser-order-details-row fr-footer">

                                        <div class="fundraiser-order-details-col product-name">Order Discount</div>

                                        <div class="fundraiser-order-details-col product-quantity"></div>

                                        <div class="fundraiser-order-details-col all-order-discount">$<span></span></div>

                                    </div>

                                    <div class="fundraiser-order-details-row fr-footer">

                                        <div class="fundraiser-order-details-col product-name">Order Total</div>

                                        <div class="fundraiser-order-details-col product-quantity"></div>

                                        <div class="fundraiser-order-details-col product-price">$<span><?php echo $order->get_total(); ?></span></div>

                                    </div>-->
									
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
                                     </div>
                                    </div>
									
                                </div>

                                </div>

								<?php }  }
							else if ($order_customer_id == $frID4) {
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
                                    <!--<div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span><span class="fr-order-amt"></span></div>-->
                                     <div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span>
                                       <span class="fr-order-amt"><?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></span>  
                                    </div>
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
                                     </div>
                                </div>
                                </div>
								</div>
								<?php } }
							else
							{
								
							}
							
							} }

                        }

                        }

                        ?>

                            

                        </div>

                    </div>
					<!-- Manually Added Sellers Online Sale -->			
					<div class="fundraiser-dashboard-section report manually-online-sale">

                        <h3>Manually Added Sellers (Online Sale)</h3>

                        <h5>Manually Added Sellers Online Sale so far: <strong>$<span class="fr-online-sale-Manually"></span></strong></h5>

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
						 
						$fid=get_user_meta(  $ID , 'user_registration_seller_fundraiser' , true );
						$args = array(
							'status' => 'published',
							'meta_query' => array(
									'relation' => 'AND',
									array(
										'key'     => 'fundraiser_field',
										'value'   => $fid,
										'compare' => '='
									),
									array(
										'key'     => 'wc_seller_field',
										'value'   => 'custom',
										'compare' => '='
									),
									array(
										'key'     => 'wc_order_type',
										'value'   => array('Online'),
										'compare' => 'IN'
									),
								),
							
							);
                        $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
							$Fund_ID = get_post_meta($order->ID, 'fundraiser_field', true );
							$frID3 = get_post_meta($order->ID, 'wc_seller_field', true );
							$frID4 = get_user_meta($frID2, 'user_registration_seller_fundraiser', true );
                             
							if($frID3 == "custom") 
							{
								$order_data = $order->get_data();
								$order_customer_id = $order_data['customer_id']; 
                                
								if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
								
								    /* Active/Inactive */	
								    $order_date = $order->order_date;
									$extract_date=$order_date;
									$inactive_date=get_user_meta($Fund_ID, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($Fund_ID, "user_action_status", true);
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
									
									$s_name=get_post_meta($order->ID, 'wc_seller_field_custom', true);
									$s_name_lower= strtolower($s_name);
									
									$trimmeddata_s_name = preg_replace('/^\s+|\s+$/', '', $s_name);
									$trimmeddata_s_name= strtolower($trimmeddata_s_name);
									
									$f=get_user_meta( $ID , 'first_name' , true );		
									$l=get_user_meta( $ID , 'last_name' , true );
									$f_t = preg_replace('/^\s+|\s+$/', '', $f);	
									$l_t = preg_replace('/^\s+|\s+$/', '', $l);	
									$fullname=$f_t." ".$l_t;	
									$fullname=strtolower($fullname);
									
									$trimmeddata_fullname = preg_replace('/^\s+|\s+$/', '', $fullname);	
									$trimmeddata_fullname= strtolower($trimmeddata_fullname);
										
									if($trimmeddata_fullname==$trimmeddata_s_name)
									{
																
									
                                if ($order_customer_id != $frID4) {
									
								   $order_type = get_post_meta($order->ID, 'wc_order_type', true );
								   if($order_type=="Online") 
								   {
									   
								?>
									
                                <div class="fundraiser-order-row sellermatch">

                                    <div class="fr-order-col fr-order-id"><?php echo $order->ID; ?></div>

                                    <div class="fr-order-col fr-order-date"><?php echo $order_data['date_created']->date('Y-m-d'); ?></div>

                                    <div class="fr-order-col fr-order-customer"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></div>

                                    <div class="fr-order-col fr-order-seller"><?php 

                                        $slrID  = get_post_meta($order->ID, 'wc_seller_field', true );
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

                                    <!--<div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span><span class="fr-order-amt"><?php echo $order->get_total(); ?></span></div>-->
									 <div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span>
                                       <span class="fr-order-amt"><?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></span>  
                                    </div>
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
                                     </div>
                                    </div>
									
                                </div>

                                </div>

								<?php }  }
							else if ($order_customer_id == $frID4) {
								$order_type = get_post_meta($order->ID, 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
							 ?>
                                <div class="fundraiser-order-row sellermatch con2" order-qty="<?php echo $order_data['quantity']; ?>">
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
                                    <!--<div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span><span class="fr-order-amt"></span></div>-->
                                     <div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span>
                                       <span class="fr-order-amt"><?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></span>  
                                    </div>
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
                                     </div>
                                </div>
                                </div>
								</div>
								<?php } }
							else
							{
								
							}
							
								} } }

                        }

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
							   $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'wc_seller_field',
								'meta_value' => $ID
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->ID, 'wc_seller_field', true );
							 $frID4 = get_user_meta($frID2, 'user_registration_seller_fundraiser', true );
                         /*   if ($ID3 == $IDD) { */
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
							
								  if ($frID4 == $order_customer_id) {
									  $order_type = get_post_meta($order->ID, 'wc_order_type', true );
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
								} 
								else{}
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

                                <div class="fr-order-col fr-order-total">Total</div>

                                <div class="fr-order-col fr-order-action">Details</div>

                            </div>

                        <?php 
							
                         $args = array(

                                'status' => "published",

                                'meta_key' => 'wc_seller_field',
								'meta_value' => $ID

                            );

                            $orders = wc_get_orders($args);
                           
                        foreach ( $orders as $order ) {

                             $frID3 = get_post_meta($order->ID, 'wc_seller_field', true );

                             $frID4 = get_user_meta($frID2, 'user_registration_seller_fundraiser', true );

                            if ($frID3 == $frID2) { 

                                $order_data = $order->get_data();

                               
                                $order_customer_id = $order_data['customer_id']; 
                     
					if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
                                if ($order_customer_id == $frID4) { 
								$order_type = get_post_meta($order->ID, 'wc_order_type', true );
								if($order_type=="Catlog") 
							    {
									 $order_item_quantity = 0; 
										  foreach ($order->get_items() as $item_key => $item_values): 
                                         $item_id   = $item_values->get_id();
                                         $item_data = $item_values->get_data();
											$order_item_quantity += $item_data['quantity']; 										 
										  endforeach;
										  $qty0 = $order_item_quantity; 
								          $qty_sum+=$qty0;
                                       ?>
                                <div class="fundraiser-order-row itemmatch">

                                    <div class="fr-order-col fr-order-id"><?php echo $order_data['id']; ?></div>

                                    <div class="fr-order-col fr-order-date"><?php echo $order_data['date_created']->date('Y-m-d'); ?></div>

                                    <div class="fr-order-col fr-order-customer"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></div>

                                    <div class="fr-order-col fr-order-seller">
									 <?php 
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
									   ?>
									<?php 

                                       // $slrID = get_post_meta($order->ID, 'wc_seller_field', true );

                                       /* $slr_info = get_userdata($frID2);*/

                                        ?><?php /*echo $slr_info->user_nicename;*/ ?></div>

                                    <!--<div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span><span class="fr-order-amt"><?php  echo $order->get_total();?></span></div>-->
									 <div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span><span class="fr-order-amt1"><?php $qty=$order_item_quantity; if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ) { $subtot=$order->get_subtotal(); $to_widthdraw = floatval ($subtot) * floatval (40) / 100; $dis=$subtot-$to_widthdraw; echo $dis=number_format($dis, 2, '.', ''); }  else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ) { $subtot=$order->get_subtotal(); $to_widthdraw = floatval ($subtot) * floatval (50) / 100; $dis=$subtot-$to_widthdraw; echo $dis=number_format($dis, 2, '.', ''); } else{ echo "0.00"; } ?></span></div>
                                    <div class="fr-order-col fr-order-action"><span class="details-btn" data-trigger="cat-order-<?php echo $order_data['id']; ?>-details">Expand</span></div>

                                <div id="cat-order-<?php echo $order_data['id']; ?>-details" class="fundraiser-order-details-table">

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
                                           

                                            $item_seller = wc_get_order_item_meta($item_id, "_wdm_seller_name", true); 
                                           
                                            $order_item_quantity += $item_data['quantity'];

                                            if ($item_seller == $frID2) {?>

                                    <div class="fundraiser-order-details-row fr-body line-item-match">

                                        <div class="fundraiser-order-details-col product-name"><?php echo $item_data['name']; ?></div>

                                        <div class="fundraiser-order-details-col product-quantity"><?php echo $item_data['quantity']; ?></div>

                                        <div class="fundraiser-order-details-col product-seller"><?php echo wc_get_order_item_meta($item_id, "_wdm_seller_display", true);  ?></div>

                                        <div class="fundraiser-order-details-col product-seller"><?php echo wc_get_order_item_meta($item_id, "_wdm_buyer_name", true); ?></div>

                                        <div class="fundraiser-order-details-col product-price show">$<span><?php echo $item_data['total']; ?></span></div>

                                    </div>                                    

                                    <?php } 
											
                                        endforeach; ?>

                                   <!-- <div class="fundraiser-order-details-row fr-footer">

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

                                    <div class="fundraiser-order-details-row fr-footer">

                                        <div class="fundraiser-order-details-col product-name">Order Total</div>

                                        <div class="fundraiser-order-details-col product-quantity"></div>

                                        <div class="fundraiser-order-details-col product-seller"></div>

                                        <div class="fundraiser-order-details-col product-buyer"></div>

                                        <div class="fundraiser-order-details-col product-price">$<span></span></div>

                                    </div>-->
									
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
										<!--<hr>-->
                               
                                    </div>
									

                                </div>

                                </div>

								<?php } } }

                        }

                        }

                        ?>

                            <input id="cat_total_items" type="hidden" value="<?php echo  $qty_sum; ?>">    

                        </div>

                    </div>
					
					<!-- Manually Added Seller Ship to School -->
					 <div class="fundraiser-dashboard-section report manually-shiptoschool-sale">
                        <h3>Manually Added Sellers (Ship to school Sale)</h3>
						  <h5>Manually Added Sellers Ship To School Sale so far: <strong>$<span class="fr-sale-manually_shiptoschool"></span></strong></h5>
						   <!--<h5>Total Quantity: <strong><span class="fr-rebate-shiptoschool-qty"></span></strong></h5>-->
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
						$totalAmount2=0;
                        $order_quantity_test2=0;
						
						$fid=get_user_meta(  $ID , 'user_registration_seller_fundraiser' , true );
						$args = array(
							'status' =>  array('wc-waiting-call'),
							'meta_query' => array(
									'relation' => 'AND',
									array(
										'key'     => 'fundraiser_field',
										'value'   => $fid,
										'compare' => '='
									),
									array(
										'key'     => 'wc_seller_field',
										'value'   => 'custom',
										'compare' => '='
									),
									array(
										'key'     => 'wc_order_type',
										'value'   => array('ship-to-school'),
										'compare' => 'IN'
									),
								),
							'limit' => -1
							);
						
						 $orders = wc_get_orders($args);
						

                        foreach ( $orders as $order1 ) {
							$each_order_quantity=0;
							$order = wc_get_order( $order1->ID );
							$order_data = $order->get_data();
						   	$scat = get_post_meta($order->ID, 'ship_with_catalog', true );
                            $frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
						    $order_type = get_post_meta($order->ID, 'wc_order_type', true );
						    $ID3 = get_post_meta($order->ID, 'wc_seller_field', true );
							if ($ID3 == "custom") 
							{
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
										$s_name=get_post_meta($order->ID, 'wc_seller_field_custom', true);
										$s_name_lower= strtolower($s_name);
										
										$trimmeddata_s_name = preg_replace('/^\s+|\s+$/', '', $s_name);
										$trimmeddata_s_name= strtolower($trimmeddata_s_name);
										
										$f=get_user_meta( $ID , 'first_name' , true );		
										$l=get_user_meta( $ID , 'last_name' , true );
										$f_t = preg_replace('/^\s+|\s+$/', '', $f);	
										$l_t = preg_replace('/^\s+|\s+$/', '', $l);	
										$fullname=$f_t." ".$l_t;	
										$fullname=strtolower($fullname);
										
										$trimmeddata_fullname = preg_replace('/^\s+|\s+$/', '', $fullname);	
										$trimmeddata_fullname= strtolower($trimmeddata_fullname);
									
									if($trimmeddata_fullname===$trimmeddata_s_name)
									{
										  $totalAmount2 += $order->get_subtotal();
										foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data(); 
												$order_type = get_post_meta($order->ID, 'wc_order_type', true );
											if($order_type=="ship-to-school") 
											{
													$order_quantity_test2 += $item_data['quantity']; 
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


									} } }
                       /* } */
						}
						}
                        }
                        ?>
						<input id="manually_totalAmount" type="hidden" value="<?php echo $totalAmount2; ?>">
                         <input id="manually_ship_total_items" type="hidden" value="<?php echo $order_quantity_test2; ?>">   
                        </div>
                    </div>
					<!-- End -->
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
							 'meta_key' => 'wc_seller_field',
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



<?php

	get_footer();

?>

