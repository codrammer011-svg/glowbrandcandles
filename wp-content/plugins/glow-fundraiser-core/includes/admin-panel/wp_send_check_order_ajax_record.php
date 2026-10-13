<?php 
/* Ajax wp_send_check_order_ajax_record */
add_action('wp_ajax_nopriv_wp_send_check_order_ajax_record', 'wp_send_check_order_ajax_record'); 
add_action('wp_ajax_wp_send_check_order_ajax_record', 'wp_send_check_order_ajax_record');
function wp_send_check_order_ajax_record()
{
						$order_id = $_POST['search_id'];
						$ID = get_post_meta($order_id, 'fundraiser_field', true );
						if(!empty($ID)){
						$cno=1;
						$count=0;
						$arr=array();
						$total_sale_so_far=0;
						$rebate_so_far=0;
                        $argss = array(
                               'type'  => 'shop_order',
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'payment_method' => array('cheque','authorize'),
								'limit' => -1
						 );
                        $orders = wc_get_orders($argss);
                        foreach ( $orders as $order ) {
								$moveOrder=0;
								$ID_current=$order->get_id();
								if($order_id==$ID_current){
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							    $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								$order_status = $order_data['status'];
								$payment_method = $order->get_payment_method(); 
								if ( $order->has_status('on-hold') || $order->has_status('completed')) {
									if($payment_method=="authorize"){
										$moveOrder=1;
									}
								}
								if($payment_method=="cheque" || $moveOrder==1){
								$payment_title = $order->get_payment_method_title(); 
								if ( $order->has_status('completed') || $order->has_status('processing')  || $order->has_status('on-hold')) {
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									if ($order_type=='Catlog') 
									{
										$order_item_quantity = 0; 
										foreach ($order->get_items() as $item_key => $item_values): 
                                        $item_id   = $item_values->get_id();
                                        $item_data = $item_values->get_data();
											$order_item_quantity += $item_data['quantity']; 										 
										endforeach;
										$qty= $order_item_quantity; 
										$total_sale=  get_post_meta( $order->get_id(), '_order_total', true);

				
									$org = !empty($frID3) ? get_user_meta( $frID3 , 'user_registration_name_of_school_business' , true ) : '';
									$action_status = !empty($frID3) ? get_user_meta( $frID3 , 'user_action_status' , true ) : '';
									$active_date = !empty($frID3) ? get_user_meta($frID3, "user_close_out_date_active", true) : '';
									$inactive_date = !empty($frID3) ? get_user_meta($frID3, "user_close_out_date_inactive", true) : '';
									$status = get_post_meta( $order->get_id() , 'order_status_send_check' , true );
									$send_check_number = get_post_meta( $order->get_id() , 'order_status_send_check_number' , true );
									$created_date_str = $order->get_date_created() ? $order->get_date_created()->date('M d Y') : '';
?>
					
				<tr id="<?php echo "fund_".$order->get_id(); ?>" class="<?php  echo 'sr_'.$cno." "; if($cno % 2 == 0){ echo "even "; } else{ echo "odd "; } echo $payment_method."  ".$payment_title; ?>">
				<!--<td id="<?php echo 'sr_'.$cno." "; ?>" style="vertical-align: top; text-align: center; "><?php echo $cno; ?></td> -->
				<td id="0_" style="vertical-align: top; text-align: center; "><?php echo "#".$order->get_id(); ?></td>
				<td id="1_" style="vertical-align: top; text-align: center;"><?php echo $frID3;?></td>
				<td id="10_" style="vertical-align: top; text-align: center;"><?php echo $org; ?></td>
				<td id="2_" style="vertical-align: top; text-align: center; "><?php echo $created_date_str; ?></td>
				<td id="4_" style="vertical-align: top; text-align: center; "><?php echo isset($order_data['billing']['first_name']) ? $order_data['billing']['first_name'] : ''; ?> <?php echo isset($order_data['billing']['last_name']) ? $order_data['billing']['last_name'] : ''; ?></td>
				<td id="4_" style="vertical-align: top; text-align: center; ">$<?php  echo $total_sale=number_format( (float)$total_sale, 2); ?></td>
				<td id="6_" style="vertical-align: top; text-align: center; "><?php if($payment_title=="Authorize.net" || $moveOrder==1){ echo "Send Check (For Schools, Gyms & Companies)- <b>Moved Order</b>"; } else { echo $payment_title; } ?></td>
				<td id="9_" style="vertical-align: top; text-align: center; "><?php if(!empty($send_check_number)){ echo $send_check_number; } else{ echo "-"; }?></td>
			    <!--<td id="8_" class="" style="vertical-align: top; text-align: center; "><?php if($action_status==1){?><span id="action_s" class="label label-success">Active</span><?php }  elseif($action_status==2){?><span id="action_s2" class="label label-danger">Inactive</span><?php }  else { ?><span id="action_st" class="label label-success">Active</span><?php } ?></td>-->
				<td id="8_" style="vertical-align: top; text-align: center; "><?php if($status==1){?><span id="action_status_<?php echo $order->get_id();?>" class="label label-success">Paid</span><?php }  elseif($status==2){?><span id="action_status_<?php echo $order->get_id();?>" class="label label-danger">Unpaid</span><?php }  else { ?><span id="action_status_<?php echo $order->get_id();?>" class="label label-danger">Unpaid</span><?php } ?></td>		
				<td id="5_" style="vertical-align: top; text-align: center; "><span class="<?php if($order_status=="on-hold"){ echo 'hold-blue'; } else if($order_status=="completed"){ echo 'hold-green'; } else if($order_status=="processing"){ echo 'hold-pink'; } else{ } ?>"><?php if($order_status=="on-hold"){ echo "On hold"; } else if($order_status=="completed"){ echo 'Completed'; } else if($order_status=="processing"){ echo 'Processing'; } else { echo $order_status; } ?></span></td>
				<td id="7_" style="vertical-align: top; text-align: center; "><div class="btn-toolbar"><div class="first-col" style="margin-bottom:10px;">&nbsp;<a data-fundid="<?php echo $frID3; ?>" data-sale="<?php echo $total_sale; ?>" data-check="<?php echo $send_check_number; ?>" data-gname="<?php echo $org; ?>" data-orderid="<?php echo $order->get_id(); ?>" id="paid_order_status" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Action</a></div></div></td>
			</tr>
			<?php  
			$cno++; 
			}
				
			} } 
			}
			} 
			}

}
?>