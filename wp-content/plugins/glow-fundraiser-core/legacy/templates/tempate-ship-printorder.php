<?php
/**
 * Template Name: Ship Print Order By Seller
 */
 if ( is_user_logged_in() ) {
	$user = wp_get_current_user();
	
if ( in_array( 'administrator', (array) $user->roles ) ) 
{
	$siteurl=get_option('siteurl');
	global $woocommerce;
	$fundr_id=$_GET['fund_id'];
	
	$user_info = get_userdata($fundr_id);
    if(in_array( 'fundraiser', (array) $user_info->roles ))
	{
	
    
	$ship_mergeOrderID = get_user_meta($fundr_id, 'wc_ship_mergeOrderID', true );
	$id=$ship_mergeOrderID;
	if(!empty($id))
	{
		/* Address Details */
		
		$name_of_school_business = get_user_meta($fundr_id, 'user_registration_name_of_school_business', true );	
		$input_box_phone = get_user_meta($fundr_id, 'user_registration_input_box_phone', true );	
		$street_address = get_user_meta($fundr_id, 'user_registration_street_address', true );	
		$city = get_user_meta($fundr_id, 'user_registration_City2', true );	
		$state = get_user_meta($fundr_id, 'user_registration_select_state', true );	
		$zip = get_user_meta($fundr_id, 'user_registration_input_box_zip', true );	
		$bcountry = get_user_meta($fundr_id, 'billing_country', true );
		$countries = new WC_Countries(); // Get an instance of the WC_Countries Object
		$countries_array = $countries->get_countries();
		$country = isset($countries_array[$bcountry]) ? $countries_array[$bcountry] : $bcountry;
	
		/* End */
		
		$order_type = get_post_meta($id, 'wc_order_type', true );
		$scat = get_post_meta($id, 'ship_with_catalog', true );
		
		if ( $scat==1 && $order_type=="ship-to-school") {
			
		$ship_merge_orders_ids = get_post_meta($id, 'ship_orders_ids', true);
		$ship_merge_orders_id = explode(",",$ship_merge_orders_ids );
	  //  print_r($ship_merge_orders_id);
		/* Filter Array */
		
		$sort_arr=array();
		for($s=0;$s<count($ship_merge_orders_id); $s++){
				
				$slrID_n = get_post_meta( $ship_merge_orders_id[$s], 'wc_seller_field', true );
				if($slrID_n=="custom")
				{
					$sellerName_n = get_post_meta($ship_merge_orders_id[$s], 'wc_seller_field_custom', true);
				}
				else if(is_numeric($slrID_n))
				{
					 $slr_info_n  = get_userdata($slrID_n);
					 $sellerName_n = $slr_info_n->first_name." ".$slr_info_n->last_name; 
				}
				else
				{
					$sellerName_n = "";
				}
				if(!empty($sellerName_n))
				{
					$sort_arr[$ship_merge_orders_id[$s]]=strtolower($sellerName_n);
				}
				
				}
				
				arsort($sort_arr);
				$sort_id=array();
				
				foreach ($sort_arr as $id_key => $seller_value): 
					$sort_id[]=$id_key;
				endforeach; 
				
				//print_r($sort_arr);
				//print_r($sort_id);
				
		/* End */
		
	?>
	
<html>
	<head>
		<title> Print Order </title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<!--<link rel="stylesheet" href="<?php echo $siteurl; ?>/wp-content/themes/glowscentedcandles/custom-assets/custom-shiptoschool.css">-->
		<style>
		@media print 
		{
		   @page
		   {
			size: 4in 8in;
		   size: portrait;
		  }
		}
	.border-line
	{
		border: 1px dashed #333;
		width: 100%;
		display: inline-block;
		margin-bottom: 9px;
	}
	.border-line-2
	{
		margin-top: 9px;
	}
	h4
	{
		font-size: 18pt;
		margin: 15px 0px;
	}
	.productName .fa-edit,.editName
	{
		display:none;
	}
	.productName:hover .fa-edit
	{
		display:inline-block;
	}
	#print_button,#print_button2
	{
		position: absolute;
    top: 40px;
    padding: 10px;
    background: red;
    cursor: pointer;
    font-weight: 600;
    letter-spacing: 1px;
    font-family: Arial,sans-serif;
    border-radius: 7px;
    right: 22px;
    color: #fff;
    width: 90px;
    text-align: center;
		
	}
	#print_button2{
		 top: 90px;
	}
	span#print_button2 a {
		color: white;
		text-decoration: none;
	}
	.totalSeller
	{
		position: absolute;
    top: 3px;
	right:22px;
	}
	.sellerPagination
	{position: absolute;
    right: 22px;
    top: 95px;
    width: 90px;
	}
	span.btnSeller a
	{
		color:#fff;
		outline:none;
	}
	span.btnSeller {
    /* display: inline-block; */
    float: left;
    width: 100%;
    padding: 10px;
    background: #000;
    cursor: pointer;
    font-weight: 600;
    letter-spacing: 1px;
    font-family: Arial,sans-serif;
    border-radius: 7px;
    right: 22px;
    color: #fff;
    /* width: 90px; */
    text-align: center;
    margin-bottom: 10px;
    /* margin-right: 22px; */
    position: relative;
}
.productName .fa-edit,.editName
{
	display:none;
}
	.productName:hover .fa-edit
	{
		display:inline-block;
	}
	.editName
	{
		width:100%;
	}
	.editName .fa-check
	{
		display:inline-block;
	}
	.edit
	{
		width: 85%;
		display: inline-block;
		padding: 8px 9px;
	}
		</style>
		<style>
		#printTable .billing-details 
		{
			width: 100%;
			text-align: right;
		}
		#printTable .billing-details h5 {
			margin: 0;
			padding-bottom: 3px;
			font-size: 14px;
			font-weight: 600;
			line-height: 18px;
		}
		.SellerName h5 
		{
			margin: 0;padding-bottom: 3px;
			font-size: 14px;
			font-weight: 600;
			line-height: 18px;
		}
		#printTable .billing-details span,
		#printTable  .SellerName span
		{
			margin-top: 6px;
		}
		h4.item_sold 
		{
			font-size: 15pt;
			margin: 12px 0px;
		}
		
		</style>
	</head>
	<body style="background:#f2f2f2">
		<span id="print_button">Print Out</span>
		<span id="print_button2"><a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>ship-print-order/?fund_id=<?php echo $fundr_id; ?>&q=compile">Compile</a></span>
		<?php if(!empty($_GET['q']) && $_GET['q']=="compile"){ ?>
		<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;width:100%; width: 384px;margin: 0 auto;">
			<div id="printTable" style=" margin:0 auto; background:#fff;padding:25px;font-size:11pt; "> 
				
			<?php 
			
				for($d=0;$d<count($sort_id); $d++){
					$order = wc_get_order( $sort_id[$d] );
					$billing_first_name = $order->get_billing_first_name();
					$billing_last_name  = $order->get_billing_last_name();
					$customer_name[$d] = $billing_first_name." ".$billing_last_name;
				}
				/*	
					$order_quantity_test1=0;
					foreach ($order->get_items() as $item_key => $item_values): 
						$item_data = $item_values->get_data(); 
						$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
						if($order_type=="ship-to-school") 
						{
							$order_quantity_test1 += $item_data['quantity']; 
						}
						
					endforeach;
					
					$slrID_np = get_post_meta( $sort_id[$d], 'wc_seller_field', true );
					if($slrID_n=="custom")
					{
						$sellerName_np = get_post_meta($sort_id[$d], 'wc_seller_field_custom', true);
					}
					else if(is_numeric($slrID_n))
					{
						 $slr_info_np  = get_userdata($slrID_np);
						 $sellerName_np = $slr_info_np->first_name." ".$slr_info_np->last_name; 
					}
					else
					{
						$sellerName_np = "";
					}
					$slower=strtolower($sellerName_np);
					$store_seller_data2[$d]=array('q'=>$order_quantity_test1,'sellername'=>$slower,'oId'=>$sort_id[$d]);
				}
				$totalqty=0;
				print_r($store_seller_data2);
				for($k=0;$k<count($sort_id);$k++){
					if($k==0)
					{
						$lastseller=strtolower($store_seller_data2[0]['sellername']);
					}
					if($store_seller_data2[$k]['sellername']==strtolower($lastseller))
					{
						$lastseller="";
						$lastseller=strtolower($store_seller_data2[$k]['sellername']);
						$totalqty+=$store_seller_data2[$k]['q'];
					}
				}
		*/
				$totalseller=array();
				$itmCount=array();
				
				for($s=0;$s<count($sort_id); $s++){
				
					$order_quantity_test1=0;
					$countItemOrder=0;				
					$order = new WC_Order( $sort_id[$s] );
					$order_status=$order->get_status(); 
					if($order_status=="waiting-call")
					{
					$items = $order->get_items();
					//$countItem=0;
					
					foreach ($order->get_items() as $item_key => $item_values): 
							$item_data = $item_values->get_data(); 
							$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
							if($order_type=="ship-to-school") 
							{
								$order_quantity_test1 += $item_data['quantity']; 
								$countItemOrder++;
								
							}
							
					endforeach;
					$itmCount[]=$countItemOrder;
					
					$slrID_n = get_post_meta( $sort_id[$s], 'wc_seller_field', true );
					if($slrID_n=="custom")
					{
						$sellerName_n = get_post_meta($sort_id[$s], 'wc_seller_field_custom', true);
					}
					else if(is_numeric($slrID_n))
					{
						 $slr_info_n  = get_userdata($slrID_n);
						 $sellerName_n = $slr_info_n->first_name." ".$slr_info_n->last_name; 
					}
					else
					{
						$sellerName_n = "";
					}
					$sellerName_lower=strtolower($sellerName_n);
					if(!empty($sellerName_lower))
					{
						if(!in_array($sellerName_lower,$totalseller))
						{
						$totalseller[]=$sellerName_lower;
						}
					}
					
					}
				
				}
				$store_seller_data=array();
				for($l=0;$l<count($totalseller);$l++)
				{
					$order_quantity_test1=0;
					$countItemOrder=0;
					$countItemNew=0;
				for($s=0;$s<count($sort_id); $s++)
				{
				//$sellerName_n_arr=array();
				//$pageCount=1;
								
				//$total_soldItems=array();
				$order = new WC_Order( $sort_id[$s] );
				$order_status=$order->get_status(); 
				if($order_status=="waiting-call")
				{
				
				//$itmCount[]=$countItemOrder;
				
				$slrID_n = get_post_meta( $sort_id[$s], 'wc_seller_field', true );
				if($slrID_n=="custom")
				{
					$sellerName_n = get_post_meta($sort_id[$s], 'wc_seller_field_custom', true);
				}
				else if(is_numeric($slrID_n))
				{
					 $slr_info_n  = get_userdata($slrID_n);
					 $sellerName_n = $slr_info_n->first_name." ".$slr_info_n->last_name; 
				}
				else
				{
					$sellerName_n = "";
				}
				if($totalseller[$l]==strtolower($sellerName_n))
				{
					$items = $order->get_items();
				//$countItem=0;
				
				foreach ($order->get_items() as $item_key => $item_values): 
						$item_data = $item_values->get_data(); 
						$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
						if($order_type=="ship-to-school") 
						{
							$order_quantity_test1 += $item_data['quantity']; 
							$countItemNew++;
							
						}
						
				endforeach;
					$countItemOrder++;
					$ordId=$sort_id[$s];
					
				}
				
				}
				
				}
				$store_seller_data[$ordId]=array('q'=>$order_quantity_test1,'c'=>$countItemOrder,'oId'=>$ordId,'totalitems'=>$countItemNew);
				}
		//	print_r($store_seller_data);
				$uniq_seller=array();
				$cv=0;
				$sellerCount=1;
				$unqdata_arr=array();
				//print_r($itmCount);
				/*Start: Order Print */
				for($s=0;$s<count($sort_id); $s++){
					
					/* Start Filter */
					$fundr_iD = get_post_meta( $sort_id[$s], 'fundraiser_field', true );
					$slrID_n1 = get_post_meta( $sort_id[$s], 'wc_seller_field', true );
					
					if($slrID_n1=="custom")
					{
						$sellerName_n1 = get_post_meta($sort_id[$s], 'wc_seller_field_custom', true);
					}
					else if(is_numeric($slrID_n1))
					{
						 $slr_info_n1  = get_userdata($slrID_n1);
						 $sellerName_n1 = $slr_info_n1->first_name." ".$slr_info_n1->last_name; 
					}
					else
					{
						$sellerName_n1 = "";
					}
				
				/* End Filter */				
				/* Items print */
			
				$sellerName_n_arr=array();
				$pageCount=1;
				$order_quantity_test=0;	
				//$total_soldItems=array();
				$order = new WC_Order( $sort_id[$s] );
				$order_status=$order->get_status(); 
				if($order_status=="waiting-call")
				{
				$items = $order->get_items();
				$countItem=0;
				
				foreach ($order->get_items() as $item_key => $item_values): 
						$item_data = $item_values->get_data(); 
						$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
						if($order_type=="ship-to-school") 
						{
							$order_quantity_test += $item_data['quantity']; 
							
						}
						
				endforeach; 
				$total_soldItems[]=array('orderID'=>$sort_id[$s], 'orderQuanty'=>$order_quantity_test,'sellername'=>$sellerName_n1);
				
				$cat_type=get_post_meta($sort_id[$s], 'wc_order_type', true);
				if($cat_type=='ship-to-school') 
				{
					$slrID_n = get_post_meta( $sort_id[$s], 'wc_seller_field', true );
					if($slrID_n=="custom")
					{
						$sellerName_n = get_post_meta($sort_id[$s], 'wc_seller_field_custom', true);
					}
					else if(is_numeric($slrID_n))
					{
						 $slr_info_n  = get_userdata($slrID_n);
						 $sellerName_n = $slr_info_n->first_name." ".$slr_info_n->last_name; 
					}
					else
					{
						$sellerName_n = "";
					}
				  
				   /*if($countItem==1 ||$countItem == 9 || $countItem==17 || $countItem==25 || $countItem==33 || $countItem==41 || $countItem==49 || $countItem==57 || $countItem==65 || $countItem==73 || $countItem==81 || $countItem==89 || $countItem==97 || $countItem==105 || $countItem==113 || $countItem==121 || $countItem==129 || $countItem==137 || $countItem==145 || $countItem==153 || $countItem==161 || $countItem==169 || $countItem==177 || $countItem==185 || $countItem==192 || $countItem==199){*/
					//$pageCount++;
					$s_name_lower = strtolower($sellerName_n);
					if(!in_array($s_name_lower, $unqdata_arr))
					{
						
					?>
					<div id="seller-<?php echo $cv; ?>" class="sellerPage <?php echo $sort_id[$s]." ".$sellerName_n; ?>" data-print="" style="">
					<?php } ?>
					<div class="printPage <?php echo $sort_id[$s]." ".$sellerName_n; ?>" data-print="" style="">
					<?php 
					
				
					 if(!in_array($s_name_lower, $unqdata_arr))
					{ ?>
					<div style="margin: 0;font-size: 11pt;font-weight: 600;line-height: 13pt;" class="billing-details">
						<h5 style="font-size: 11pt;line-height: 13pt;margin:0;padding:0;padding-bottom:3px;"><?php echo "FUND ID #".$fundr_id.", ".$name_of_school_business; ?>,
						<?php echo $street_address.", ".$city.", ".$state.", ".$country; ?></h5>
						<hr style="font-size: 11pt;line-height: 13pt;border: 1px dashed #333;width: 100%;display: inline-block;margin:0;padding:0;margin-top:4px;"/>
					</div>
					<?php } ?>
					<div class="SellerName" style="padding:0;margin:0;">
					<?php 
						if(!in_array($s_name_lower, $unqdata_arr))
						{ ?>
						<h3 style="font-size: 14pt;line-height: 16pt;padding:0;margin: 8px 0px;margin-bottom: 3px;"><?php echo $sellerName_n; ?></h3>
					<?php } ?>
						<h5 style="padding-top: 3px;margin: 0;padding-bottom: 3px;font-size: 11pt;font-weight: 600;line-height: 13pt;"><?php echo "Customer Name: ".$customer_name[$s]; ?></h5>
						<h5 style="margin: 0;padding-bottom: 3px;font-size: 11pt;font-weight: 600;line-height: 13pt;"><?php echo "Order #ID: ".glow_order_display_id($sort_id[$s]); ?></h5>
						<hr style="font-size: 11pt;line-height: 13pt;border: 1px dashed #333;width: 100%;display: inline-block;" />
					</div>
				
				<?php 
				 
					if(!in_array($s_name_lower,$unqdata_arr))
					{
						$unqdata_arr[$cv]=strtolower($sellerName_n);
						$cv++;		
					}
			/* } */ 
			foreach ($order->get_items() as $item_key => $item_values): 
				$countItem++;
				
				if(!empty($sellerName_n))
				{
					$item_data = $item_values->get_data(); 
					$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
					if($order_type=="ship-to-school") 
					{
						$order_quantity_test += $item_data['quantity']; 
					}
					$prID = $item_data['product_id'];
					$prName = $item_data['name'];
					$quanty = $item_data['quantity'];
					echo '<p id='.$prID.' style="margin:5px 0;font-size:11pt;line-height:13pt;" class="productName '.$countItem.'" ><span class="pName"> <span class="p-name">'.$quanty.' '.$prName.'</span> <i class="fa fa-edit"></i>'.glow_product_type_html( $item_values, 'display:block;' ).'</span> <span class="editName"><input type="text" class="edit"> <i class="fa fa-check"></i></span></p>'; 
				} 
	
			endforeach; 
	
  ?>
	<?php  
	 if($store_seller_data[$sort_id[$s]]['oId']==$sort_id[$s]) {
	?>
	
	<div style="margin-bottom:10px;">
		<hr style="border: 1px dashed #333;width: 100%;display: inline-block;margin-bottom: 7px;margin-top: 7px;" class=""/>
		<h4 style="font-size: 11pt;margin: 12px 0px;margin: 0;" class="item_sold test1">Total Items Sold: <?php echo $store_seller_data[$sort_id[$s]]['q']; ?></h4>
		<hr style="border: 1px dashed #333;width: 100%;display: inline-block;margin-bottom: 7px;margin-top: 7px;" class="" />
	</div>
	 <?php } ?>

	</div>
	
	<?php 
	 if($store_seller_data[$sort_id[$s]]['oId']==$sort_id[$s]) {
		 ?>
	</div>
	<?php }
	
	} } } ?>
	</div>
</div>
</div>
		<?php } else { ?>
		<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;width:100%; width: 384px;margin: 0 auto;">
			<div id="printTable" style=" margin:0 auto; background:#fff;padding:25px;font-size:14px; "> 
				
			<?php 
				for($d=0;$d<count($sort_id); $d++){
					$order = wc_get_order( $sort_id[$d] );
					$billing_first_name = $order->get_billing_first_name();
					$billing_last_name  = $order->get_billing_last_name();
					$customer_name[$d] = $billing_first_name." ".$billing_last_name;
				}
				
				$totalseller=array();
				$itmCount=array();
				$ship_orders_ID=array();
				
				for($s=0;$s<count($sort_id); $s++){
				//$sellerName_n_arr=array();
				//$pageCount=1;
				$order_quantity_test1=0;
				$countItemOrder=0;				
				//$total_soldItems=array();
				$order = new WC_Order( $sort_id[$s] );
				$order_status=$order->get_status(); 
				if($order_status=="waiting-call")
				{
				$items = $order->get_items();
				//$countItem=0;
				
				foreach ($order->get_items() as $item_key => $item_values): 
						$item_data = $item_values->get_data(); 
						$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
						if($order_type=="ship-to-school") 
						{
							$order_quantity_test1 += $item_data['quantity']; 
							$countItemOrder++;
							
						}
						
				endforeach;
				$itmCount[]=$countItemOrder;
				
				$slrID_n = get_post_meta( $sort_id[$s], 'wc_seller_field', true );
				if($slrID_n=="custom")
				{
					$sellerName_n = get_post_meta($sort_id[$s], 'wc_seller_field_custom', true);
				}
				else if(is_numeric($slrID_n))
				{
					 $slr_info_n  = get_userdata($slrID_n);
					 $sellerName_n = $slr_info_n->first_name." ".$slr_info_n->last_name; 
				}
				else
				{
					$sellerName_n = "";
				}
				$sellerName_lower=strtolower($sellerName_n);
					if(!empty($sellerName_lower))
					{
						if(!in_array($sellerName_lower,$totalseller))
						{
						$totalseller[]=$sellerName_lower;
						}
					}
				
				}
				
				}
				
				$uniq_seller=array();
				//print_r($itmCount);
				for($s=0;$s<count($sort_id); $s++){
					
					/* Start Filter */
					$fundr_iD = get_post_meta( $sort_id[$s], 'fundraiser_field', true );
					$slrID_n1 = get_post_meta( $sort_id[$s], 'wc_seller_field', true );
					
					if($slrID_n1=="custom")
					{
						$sellerName_n1 = get_post_meta($sort_id[$s], 'wc_seller_field_custom', true);
					}
					else if(is_numeric($slrID_n1))
					{
						 $slr_info_n1  = get_userdata($slrID_n1);
						 $sellerName_n1 = $slr_info_n1->first_name." ".$slr_info_n1->last_name; 
					}
					else
					{
						$sellerName_n1 = "";
					}
					/*
					if(!empty($sellerName_n1))
					{
						if(!in_array($sellerName_n1,$uniq_seller))
						{
								$uniq_seller[$s]=$sellerName_n1;
								
				?>
				<div class="printPage <?php echo $uniq_seller[$s]." BlankPage"; ?>" data-print="" style="height:510px;">
					<div class="SellerName">
						<h5><?php echo "Seller Name: ".$sellerName_n1; ?></h5>
						<h5><?php echo "Fund #ID: ".$fundr_iD; ?></h5>
						<span class="border-line"></span>
					</div>
				
				</div>
				<?php 
						}
					} 
					*/
				/* End Filter */				
				/* Items print */
				
				$sellerName_n_arr=array();
				$pageCount=0;
				$order_quantity_test=0;	
				$total_soldItems=array();
				$order = new WC_Order( $sort_id[$s] );
				$order_status=$order->get_status(); 
				if($order_status=="waiting-call")
				{
				$items = $order->get_items();
				$countItem=0;
				
				foreach ($order->get_items() as $item_key => $item_values): 
						$item_data = $item_values->get_data(); 
						$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
						if($order_type=="ship-to-school") 
						{
							$order_quantity_test += $item_data['quantity']; 
							
						}
						
				endforeach; 
				$total_soldItems[]=array('orderID'=>$sort_id[$s], 'orderQuanty'=>$order_quantity_test);
				
				$cat_type=get_post_meta($sort_id[$s], 'wc_order_type', true);
				if($cat_type=='ship-to-school') 
				{
				  
					foreach ($order->get_items() as $item_key => $item_values): 
				 
						$slrID_n = get_post_meta( $sort_id[$s], 'wc_seller_field', true );
							if($slrID_n=="custom")
							{
								$sellerName_n = get_post_meta($sort_id[$s], 'wc_seller_field_custom', true);
							}
							else if(is_numeric($slrID_n))
							{
								 $slr_info_n  = get_userdata($slrID_n);
								 $sellerName_n = $slr_info_n->first_name." ".$slr_info_n->last_name; 
							}
							else
							{
								$sellerName_n = "";
							}
							/* if(!empty($sellerName_n))
							{
								if(!in_array($sellerName_n,$totalseller))
								{
									$totalseller[]=$sellerName_n;
								}
							} */
				 $countItem++;
				
				if($countItem==1 ||$countItem == 9 || $countItem==17 || $countItem==25 || $countItem==33 || $countItem==41 || $countItem==49 || $countItem==57 || $countItem==65 || $countItem==73 || $countItem==81 || $countItem==89 || $countItem==97 || $countItem==105 || $countItem==113 || $countItem==121 || $countItem==129 || $countItem==137 || $countItem==145 || $countItem==153 || $countItem==161 || $countItem==169 || $countItem==177 || $countItem==185 || $countItem==192 || $countItem==199){
					//$pageCount++;
					$sellerName_n_arr[]=$sellerName_n;
					$pageCount++;
				?>
		
		<div class="printPage <?php echo $sort_id[$s]." ".$sellerName_n." page-".$pageCount; ?>" data-print="" style="height:530px;min-height:530px;">
		<?php if($pageCount==1) { ?>
		<div class="billing-details">
			<h5><?php echo "FUND ID #".$fundr_id.", ".$name_of_school_business; ?></h5>
			<h5><?php echo $street_address.", ".$city.", ".$state.", ".$country; ?></h5>
			<span class="border-line"></span>
		</div>
		<div class="SellerName">
			<h5><?php echo "Seller Name: ".$sellerName_n; ?></h5>
			<h5><?php echo "Customer Name: ".$customer_name[$s]; ?></h5>
			<h5><?php echo "Order #ID: ".glow_order_display_id($sort_id[$s]); ?></h5>
			<span class="border-line"></span>
		</div>
	 <?php }
		 if($pageCount>1) { ?>
		 <div class="billing-details">
			<h5><?php echo "FUND ID #".$fundr_id.", ".$name_of_school_business; ?></h5>
			<h5><?php echo $street_address.", ".$city.", ".$state.", ".$country; ?></h5>
			<span class="border-line"></span>
		</div>
		<div class="SellerName page-incr">
			<h5><?php echo "Seller Name: ".$sellerName_n; ?> <span class="pageNo">(Page <?php echo $pageCount; ?>)</span></h5>
			<h5><?php echo "Customer Name: ".$customer_name[$s]; ?></h5>
			<h5><?php echo "Order #ID: ".glow_order_display_id($sort_id[$s]); ?></h5>
			<span class="border-line"></span>
		</div>
	<?php 
		} 
		if(in_array($sellerName_n,$sellerName_n_arr))
		{
			//$pageCount++;	
		}	
	}  
	if(!empty($sellerName_n))
	{
	    $item_data = $item_values->get_data(); 
		$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
		if($order_type=="ship-to-school") 
		{
			$order_quantity_test += $item_data['quantity']; 
		}
		$prID = $item_data['product_id'];
		$prName = $item_data['name'];
		$quanty = $item_data['quantity'];
		echo '<p id='.$prID.' class="productName '.$countItem.'" ><span class="pName"> <span class="p-name">'.$quanty.' '.$prName.'</span> <i class="fa fa-edit"></i>'.glow_product_type_html( $item_values, 'display:block;' ).'</span> <span class="editName"><input type="text" class="edit"> <i class="fa fa-check"></i></span></p>'; 
	} 
	if(($countItem < $itmCount[$s] && $countItem==8) || ($countItem < $itmCount[$s] && $countItem==16 ) || ($countItem < $itmCount[$s] && $countItem==24) || ($countItem < $itmCount[$s] && $countItem==32) || ($countItem < $itmCount[$s] && $countItem==40) || ($countItem < $itmCount[$s] && $countItem==48) || ($countItem < $itmCount[$s] && $countItem==56) || ($countItem < $itmCount[$s] && $countItem==64) || ($countItem < $itmCount[$s] && $countItem==72) || ($countItem < $itmCount[$s] && $countItem==80) || ($countItem < $itmCount[$s] && $countItem==88) || ($countItem < $itmCount[$s] && $countItem==96) || ($countItem < $itmCount[$s] && $countItem==104) || ($countItem < $itmCount[$s] && $countItem==112) || ($countItem < $itmCount[$s] && $countItem==120) || ($countItem < $itmCount[$s] && $countItem==128) || ($countItem < $itmCount[$s] && $countItem==136) || ($countItem < $itmCount[$s] && $countItem==144) || ($countItem < $itmCount[$s] && $countItem==152))
	{
	
	?>
	<!--<p class="line-code-bar-break1 hhs" style="display:inline-block;width:100%"> </p>
	<p style="display:inline-block;width:100%"> </p>
	<p style="display:inline-block;width:100%"> </p>-->
	<!--<p style="display:inline-block;width:100%"> </p>
	<p style="display:inline-block;width:100%"> </p>
	<p style="display:inline-block;width:100%"> </p>-->
    </div>
<?php  
	} 
	endforeach; 
	if( $countItem==8 || $countItem==16 || $countItem==24 || $countItem==32 || $countItem==40 || $countItem==48 || $countItem==56 || $countItem==64 || $countItem==72 || $countItem==80 || $countItem==88 || $countItem==96 || $countItem==104 || $countItem==112 || $countItem==120 || $countItem==128 || $countItem==136 || $countItem==144 || $countItem==152)
	{
		?>
		<!--<div class="printPage <?php echo $pageCount; ?>" style="height:540px;">-->
	
	<?php if($countItem==$itmCount[$s]) {
	for($w=0;$w<count($total_soldItems); $w++) 
	{
		if(!in_array($sort_id[$s],$ship_orders_ID))
		{
			if($total_soldItems[$w]['orderID'] == $sort_id[$s])
			{
				$ship_orders_ID[]=$sort_id[$s];
?>
	<span class="border-line border-line-2"></span>
	<h4 class="item_sold test1">Total Items Sold: <?php echo $total_soldItems[$w]['orderQuanty']; ?></h4>
	<span class="border-line border-line-2"></span>
	<!--<p class="break1 nn" style="display:inline-block;width:100%"> </p>
	<p style="display:inline-block;width:100%"> </p>
	<p style="display:inline-block;width:100%"> </p> -->
	</div>
	<?php 
	}
	} } }

	} 
	else { ?>
	<?php if($countItem==$itmCount[$s]) { 
	for($w=0;$w<count($total_soldItems); $w++) 
	{
		if(!in_array($sort_id[$s],$ship_orders_ID))
		{
			if($total_soldItems[$w]['orderID'] == $sort_id[$s])
			{
				$ship_orders_ID[]=$sort_id[$s];
?>
	<span class="border-line border-line-2"></span>
	<h4 class="item_sold test1">Total Items Sold: <?php echo $total_soldItems[$w]['orderQuanty']; ?></h4>
	<span class="border-line border-line-2"></span>
	
	<!--<p class="line-code-bar-break2 mm" style="display:inline-block;width:100%"> </p>
	<p style="display:inline-block;width:100%"> </p>
	<p style="display:inline-block;width:100%"> </p> -->
	<!--<p style="display:inline-block;width:100%"> </p>
	<p style="display:inline-block;width:100%"> </p>
	<p style="display:inline-block;width:100%"> </p>-->
</div>
	<?php 
	} } }
	 }
	 } 
	 
	
	
	} } } ?>
	</div>
</div>
</div>
		<?php } ?>	
<div class="totalSeller">
	<span>Total Sellers: <?php echo count($totalseller); ?> </span>
</div>
	
<script type='text/javascript' src='<?php echo esc_url( glow_dynamic_url_base('home') ); ?>wp-includes/js/jquery/jquery.js' id='jquery-core-js'></script>
<script>
	function printData()
	{
	   var divToPrint=document.getElementById("printTable");
	   newWin= window.open("");
	   newWin.document.write(divToPrint.innerHTML);
	   newWin.print();
	   newWin.close();
	}

	jQuery('#print_button').on('click',function()
	{
		jQuery(".productName .editName").hide();
		printData();
	});

	jQuery(document).ready(function()
	{
		
		jQuery('.productName .fa-edit').click(function()
		{
			var pName=jQuery(this).parents(".productName").find(".p-name").text();
			//alert(pName);
			jQuery(this).parents(".productName").find(".edit").val(pName);
			
			jQuery(this).parents(".productName").children(".pName").hide();
			jQuery(this).parents(".productName").children('.editName').css('display','inline-block');
			//var pName=jQuery('.pName').text();
			//jQuery('.editName .edit').val(pName);
			//jQuery('.pName,').hide();
			//jQuery('.editName').css('display','inline-block');
		});
		jQuery('.productName .fa-check').click(function()
		{
			var pName=jQuery(this).parents(".productName").find(".edit").val();
			jQuery(this).parents(".productName").find(".p-name").text(pName);
			jQuery(this).parents(".productName").find(".pName").show();
			jQuery(this).parents(".productName").find('.editName').hide();
		});
		 //get the natural page height -set it in variable above.
		 <?php for($v=0;$v<count($totalseller);$v++){
			 
			 ?>
	var currentHeight = 0;
	
    currentHeight = jQuery('#seller-<?php echo $v; ?>').outerHeight();
	//alert(currentHeight);
	if(currentHeight<=550)
	{
		jQuery('#seller-<?php echo $v; ?>').css("min-height","530px");
		jQuery('#seller-<?php echo $v; ?>').css("height","530px");
	}
	else if(currentHeight>550)
	{
		/*var countDecimals = function (value) {
			if(Math.floor(value) === value) return 0;
			return value.toString().split(".")[1].length || 0; 
		}
		*/
		var cal= currentHeight/530;
		//cal=7.01;
		//var result=cal.countDecimals();
		if(cal>0)
		{
			var cal_new=parseInt(cal)+1;
			var new_height = 530 * cal_new;
		}
		
		jQuery('#seller-<?php echo $v; ?>').css("min-height",new_height);
		jQuery('#seller-<?php echo $v; ?>').css("height",new_height);
		 console.log("set current height on load #seller-<?php echo $v; ?>= " + new_height);
	}
	else{
		
	}
   <?php } ?>
	});


</script>
</body>
</html>
<?php 
}
}
else
{
?>
<html>
	<head>
	<title> Print Order </title>
	<style>
	.border-line
	{
		border: 1px dashed #333;
		width: 100%;
		display: inline-block;
		margin-bottom: 9px;
	}
	.border-line-2
	{
		    margin-top: 9px;
	}
	h4
	{
		font-size: 18px;
		margin: 15px 0px;
	}
	</style>
	</head>
	<body style="background:#f2f2f2">
	<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;width:100%; width: 384px;margin: 0 auto;">

		<div style=" margin:0 auto; background:#fff;padding:25px;font-size:14px; "> 
			<h2><?php
			
			
			?></td></h2>
			<span class="border-line"></span>
			<span>Empty Ship To School Order.</span>
	<span class="border-line border-line-2"></span>
	<h4>Total Items Sold: <?php echo "0"; ?></h4>
	<span class="border-line border-line-2"></span>
	</div>
	</div>
	</body>
	</html>
<?php	
} 
}
else { ?>
<html>
	<head>
	<title> Print Order </title>
	<style>
	.border-line
	{
		border: 1px dashed #333;
		width: 100%;
		display: inline-block;
		margin-bottom: 9px;
	}
	.border-line-2
	{
		    margin-top: 9px;
	}
	h4
	{
		font-size: 18px;
		margin: 15px 0px;
	}
	</style>
	</head>
	<body style="background:#f2f2f2">
	<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;width: 50%;margin: 0 auto; text-align: center; color: #f44336;">
		<div style=" margin:0 auto; background:#fff;padding:25px;font-size:14px; "> 
			<h2 style="font-size: 20px;font-weight: 400;"><?php 
			if(is_numeric($fundr_id))
			{
				$user_info = get_userdata($fundr_id);
				if(!in_array( 'fundraiser', (array) $user_info->roles ))
				{    echo "Error: wrong fundraiser code!!";   
				}
			}
			else
			{
				 echo "Error: something went wrong!!";   
			}

		?></h2>
			
		</div>
	</div>
	</body>
</html>
	<?php } 
	
	}
 }
	else
	{
		$url=("" . glow_dynamic_url_base('home') . "my-account/");
		wp_redirect($url);
	}
	
	?>