<?php
/**
 * Template Name: Seller Profile
 */
get_header();
?>
	<link rel="stylesheet" href='<?php echo get_stylesheet_directory_uri(); ?>/css/custom-profile.css' />
	<link rel="stylesheet" href='<?php echo get_stylesheet_directory_uri(); ?>/fonts/winnie-personal-use-cufonfonts-webfont/style.css' />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
		
.sc-feJyhm.jqEzoc.txt { line-height: 145%; font-size: 19px; font-family: 'Winnie PERSONAL USE' !important; font-weight: 400; color: #000; }
		
	.progress-bar:before {
    content: "";
    background: repeating-linear-gradient(100deg, /* angle of slash */ #ffffff3d, /* color of slash */ #0800E4 8px, /* thickness of slash */ #ffffff21 20px, /* space between slashes */ #ffffff21 8px);
    width: 100%;
    display: block;
    height: 100%;
    border-radius: 16px;
}
		.main-page-banner img {
    width: 100%;
}
		.banner-first.row .col-md-6, .banner-thrid.row .col-md-6  
	{
		width: 46%;
	}
	.banner-second.row .col-md-6
	{
		width: 92%;
	}
	.banner-second img, .banner-first img, .banner-thrid img 
	{
		width: auto !important;
	}
	.cQcVNN
	{
		text-transform: capitalize;
	}
	.hfFGhf 
	{
		display: inline-block;
	}
	.jEXOub 
	{
		min-height: 70px;
	}
	.eYHKCV
	{
			padding-left: 6px;
	}
	.jZDald 
	{
		width: auto;
	}
	.small-screen
	{
		display:none;
		width: 130px;
		margin-top: 20px;
		margin-bottom:20px;
	}
	.mobile-screen
	{
		display:none;
	}
	.brJQDe
	{
		height: auto;
	}
	.eGOSHP
	{
		padding: 11px 30px;
		display: inline-block;
		line-height: 1.6;
	}
	.shop_block
	{
		text-align: center;
	}
	.bio-msg{
    line-height: 145%;
    padding: 30px 5px ;
    font-size: 25px !important;
    font-family: 'Winnie PERSONAL USE';
    font-weight: 400;
		color:#000;
}
	.mobile-screen .bio-msg-smallScr
	{
		line-height: 1.6;
		font-size: 17px;
	}
	.left-side .block-banner
	{
		width: 100%;
		display: inline-block;
		margin: 0 auto;
	}
	.left-side .cover-img 
	{
		display: inline-block;
		position: relative;
		overflow: hidden;
		width: auto;
	}
	.left-side .cover-img  p
	{
		    margin: 0;
	}
	.left-side .cover-img .overlay {
		position: absolute;
		top: 50%;
		left: 50%;
		-webkit-transform: translate(-50%, -50%);
		-ms-transform: translate(-50%, -50%);
		transform: translate(-50%, -50%);
		text-align: center;
		/* visibility: hidden; */
		opacity: 0;
		background-image: -webkit-linear-gradient(bottom, #e1dfed 0%, rgba(225, 223, 237, 0.7) 99%, rgba(225, 223, 237, 0.8) 100%);
		background-image: linear-gradient(to top, rgb(220, 220, 220) 4%, rgb(220, 220, 220 / 50%) 100%, rgb(220, 220, 220/ 50%) 100%);
		background-image: -webkit-linear-gradient(bottom, rgb(238 89 146 / 70%) 0%, rgb(249 144 115 / 90%) 100%, rgb(238 89 146 / 95%) 100%);
		-webkit-transition: all 0.2s ease-in-out;
		transition: all 0.2s ease-in-out;
		width: 100%;
		height: 100%;
		border-radius: 0;
		overflow: hidden;
		cursor: pointer;
		background-image: -webkit-linear-gradient(bottom, rgb(110 16 18 / 70%) 0%, rgb(156 185 91) 100%, rgb(110 16 18) 95%) !important;
	}
	.left-side .cover-img .overlay a {
		display: block;
		position: absolute;
		top: 50%;
		left: 50%;
		-webkit-transform: translate(-50%, -50%);
		-ms-transform: translate(-50%, -50%);
		transform: translate(-50%, -50%);
		width: 100%;
		color: #333333;
		color: #fff;
		font-size: 18px;
		font-family: 'Proxima Nova';
		font-weight: bold;
	}
	.left-side .cover-img .overlay:hover 
	{
		opacity: 1;
	}
	.mainHeading
    {
		text-align: center;
		text-transform: uppercase;
		padding: 5px 0;
		font-size: 30px; font-family: 'Winnie PERSONAL USE' !important;
	}
		

.header .menu-wrapper { display:none !important; }
		
	@media (max-width:992px)
	{
		.small-screen,.mobile-screen
		{
			display:block;
		}
		.rightSide,.small-screen-hide
		{
			display:block;
		}
		.banner-first.row .col-md-6, .banner-thrid.row .col-md-6 
		{
			width: 47%;
		}
		.banner-second.row .col-md-6 
		{
			width: 94%;
		}
		.block-banner .row 
		{
			margin: 0;
		}
		.smallScr992
		{
			display:none;
		}
	}
	@media (max-width:768px)
	{
		.shop_section 
		{
			margin-top: 10px;
		}
		.shop_section a 
		{
			font-size: 15px;
			padding: 12px 28px;
		}
		.hWeIyu
		{
			margin: 0;
		}
		.sc-lhVmIH .shop_section 
		{
			margin-top: 40px;
		}
		.left-side .block-banner 
		{
			text-align: center;
			
		}
		
	}
	@media (max-width:640px)
	{
		.banner-first.row .col-md-6, .banner-thrid.row .col-md-6 
		{
			width: 50%;
		}
		.shop_block 
		{
			text-align: center;
			width: 50%;
			margin: 0 auto;
		}
		.left-side .block-banner 
		{
			
		}
		.banner-second 
		{
			padding-top: 18px;
		}
		.dGEqvM 
		{
			margin-top: 20px;
			margin-bottom: 5px;
		}
	}
	@media (max-width: 580px)
	{
		.col-xs-4.block 
		{
			padding-bottom: 19px;
			width: 100%;
		}
		.banner-second 
		{
			padding-top: 0;
		}
		.left-side .block-banner 
		{
			padding-top: 0;
		}
	}
	/* Profile Design & Layout Overrides */
	.main-page-banner {
		width: 100%;
		margin-bottom: 30px;
	}
	.main-page-banner img {
		width: 100%;
		height: auto;
		display: block;
	}
	.eaTkcl {
		max-width: 1280px;
		margin: 0 auto;
		padding: 0 20px;
	}
	.gxfVgS {
		display: flex !important;
		flex-direction: row !important;
		gap: 50px !important;
	}
	.fYjnlz {
		flex: 1 1 60% !important;
		padding: 0 !important;
	}
	.gaqdDY {
		flex: 1 1 40% !important;
		padding: 0 !important;
	}
	.kJfAmm {
		font-family: 'Winnie PERSONAL USE', sans-serif !important;
		color: #0800E4 !important;
		font-size: 48px !important;
		line-height: 1.15 !important;
		margin: 0 0 10px 0 !important;
		letter-spacing: 0.5px;
	}
	.kZLEoE {
		font-size: 24px !important;
		color: #111 !important;
		font-weight: 500 !important;
		margin: 0 0 15px 0 !important;
	}
	.progressBar {
		position: relative !important;
		width: 100% !important;
		height: 20px !important;
		background: #efeeee !important;
		border-radius: 10px !important;
		margin: 20px 0 10px 0 !important;
		overflow: visible !important;
	}
	.progress-bar {
		position: relative !important;
		height: 100% !important;
		border-radius: 10px !important;
		background: #17c671 !important;
	}
	.progress-bar:before {
		display: none !important;
	}
	.progress-bar:after {
		content: "" !important;
		position: absolute !important;
		left: -12px !important;
		top: -20px !important;
		right: auto !important;
		width: 32px !important;
		height: 48px !important;
		background: url(https://demo.flywebsolutions.com/candace_old/wp-content/uploads/2025/10/84566287_9977709-removebg-preview-1.png) center center / contain no-repeat !important;
		z-index: 10 !important;
	}
	.ccbzhW {
		display: flex !important;
		justify-content: space-between !important;
		align-items: center !important;
		font-size: 20px !important;
		font-weight: 700 !important;
		color: #222 !important;
		margin-top: 15px !important;
		margin-bottom: 30px !important;
	}
	.ccbzhW .jZDald {
		font-size: 20px !important;
		font-weight: 700 !important;
		color: #222 !important;
		width: auto !important;
	}
	.ccbzhW .eYHKCV {
		font-size: 20px !important;
		font-weight: 700 !important;
		color: #222 !important;
	}
	.mainHeading {
		font-family: 'Winnie PERSONAL USE', sans-serif !important;
		font-size: 34px !important;
		color: #222 !important;
		text-align: center !important;
		margin: 25px 0 20px 0 !important;
	}
	.block-banner {
		width: 100% !important;
	}
	.block-banner .row {
		display: flex !important;
		flex-wrap: wrap !important;
		margin-left: -10px !important;
		margin-right: -10px !important;
	}
	.block-banner .block,
	.block-banner .col-md-4,
	.block-banner .col-sm-4,
	.block-banner .col-xs-4 {
		flex: 0 0 33.333333% !important;
		max-width: 33.333333% !important;
		width: 33.333333% !important;
		padding-left: 10px !important;
		padding-right: 10px !important;
		padding-bottom: 20px !important;
		box-sizing: border-box !important;
		float: none !important;
	}
	.block-banner .cover-img {
		border-radius: 6px !important;
		overflow: hidden !important;
		box-shadow: 0 2px 8px rgba(0,0,0,0.06) !important;
		width: 100% !important;
		display: block !important;
	}
	.block-banner .cover-img p {
		margin: 0 !important;
		padding: 0 !important;
	}
	.block-banner .cover-img img {
		width: 100% !important;
		height: auto !important;
		display: block !important;
	}
	.slr_ovrly {
		width: 100% !important;
		text-align: center !important;
		margin: 20px 0 10px 0 !important;
	}
	.slr_ovrly img {
		width: 100% !important;
		max-width: 440px !important;
		height: auto !important;
		display: inline-block !important;
		border-radius: 8px !important;
	}
	.bio-msg {
		font-family: 'Winnie PERSONAL USE', sans-serif !important;
		font-size: 22px !important;
		line-height: 1.35 !important;
		color: #111 !important;
		text-align: center !important;
		padding: 12px 5px !important;
	}
	.brJQDe {
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
		gap: 15px !important;
		margin: 15px 0 25px 0 !important;
	}
	div#DemoStoreShareButton {
		background-color: #0800E4 !important;
		color: #fff !important;
		font-weight: 700 !important;
		font-size: 14px !important;
		letter-spacing: 1px !important;
		border-radius: 30px !important;
		padding: 8px 30px !important;
		display: inline-flex !important;
		align-items: center !important;
		gap: 8px !important;
	}
	a.fr-btn {
		display: inline-flex !important;
		align-items: center !important;
		justify-content: center !important;
		width: 38px !important;
		height: 38px !important;
		background: #0800E4 !important;
		color: #fff !important;
		border-radius: 50% !important;
		font-size: 16px !important;
		text-decoration: none !important;
		margin: 0 !important;
		cursor: pointer;
	}
	.jFdVyI, .kLuVwd {
		font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
		font-size: 22px !important;
		font-weight: 700 !important;
		color: #333 !important;
		margin: 25px 0 15px 0 !important;
	}
	.bqqpfS {
		border: 1px solid #efeeee !important;
		border-radius: 8px !important;
		padding: 12px 18px !important;
		display: flex !important;
		align-items: center !important;
		justify-content: space-between !important;
		background: #fff !important;
		margin-bottom: 12px !important;
		height: auto !important;
	}
	.bqqpfS .eixpDv {
		font-size: 14px !important;
		font-weight: 600 !important;
		color: #555 !important;
		margin-right: 12px !important;
		margin-left: 0 !important;
	}
	.bqqpfS .bnSLTW {
		width: 44px !important;
		height: 44px !important;
		border-radius: 50% !important;
		background-size: cover !important;
		background-position: center !important;
		margin-right: 15px !important;
		margin-left: 0 !important;
	}
	.bqqpfS .cQcVNN {
		font-size: 15px !important;
		font-weight: 600 !important;
		color: #333 !important;
	}
	.bqqpfS .iAGvZs {
		font-size: 15px !important;
		font-weight: 600 !important;
		color: #666 !important;
	}
	.kKkoMh {
		border-radius: 12px !important;
		box-shadow: none !important;
		border: 1px solid #efeeee !important;
	}
	@media (max-width: 900px) {
		.gxfVgS {
			flex-direction: column !important;
		}
		.fYjnlz, .gaqdDY {
			flex: 1 1 100% !important;
			width: 100% !important;
		}
	}
	@media (max-width: 580px) {
		.block-banner .block,
		.block-banner .col-md-4,
		.block-banner .col-sm-4,
		.block-banner .col-xs-4 {
			flex: 0 0 50% !important;
			max-width: 50% !important;
			width: 50% !important;
		}
	}
	</style><?php 

 ?>
   <?php 
							if(get_query_var( 'seller' ))
							{
								$IDG=get_query_var( 'seller' );
							}
							else if(get_query_var( 'user' ))
							{
								$query = get_query_var( 'user' );
								$user = get_user_by('login', $query);
								$IDG = $user->ID;
							}
							else
							{
								
							}
							if(!empty($IDG))
							{
								$u_id  = $IDG;
								$ID=$u_id;
							}
							else
							{
								
								
							}
						$fid=get_user_meta( $u_id , 'user_registration_seller_fundraiser' , true );
						$f=get_user_meta( $u_id , 'first_name' , true );		
						$l=get_user_meta( $u_id , 'last_name' , true );	
						$user_info = get_userdata($u_id);
						$usName=$user_info->user_login;
						session_start();
					
					/*	if (empty($_SESSION["fridID"]) || empty($_SESSION["seller_name"])) 
						{ */
							//$_SESSION["fridID"]=$fid;
							//$_SESSION["seller_name"]=$f." ".$l;
					/*	} */
					
						$sd=get_user_meta( $fid , 'user_registration_start_date' , true );
						$ed=get_user_meta( $fid , 'user_registration_end_date' , true );
						$org=get_user_meta( $fid , 'user_registration_input_company' , true );
						if(empty($org))
						{
							$org=get_user_meta( $fid , 'user_registration_name_of_school_business' , true );
						}
						
						$goal_amount=get_user_meta( $u_id , 'user_registration_seller_goal' , true );
						$description=get_user_meta($u_id,"description",true);
						$seller_desc=get_user_meta($u_id,'seller_profile_description',true);
						$originalDate = $sd;
						$originalDate2 = $ed;
						$newDate = date("F d", strtotime($originalDate));
						$newDate2 = date("F d", strtotime($originalDate2));
						
						// $current_user = wp_get_current_user(); 
                           // $frID2          = $current_user->ID; 
							//$IDG=$_GET['frid'];
							if(!empty($IDG))
							{
								$ID = $IDG;
								$frID2=$IDG;
							}
							else
							{
								$ID = $frID2;
								
							}
							$orderid_buyer=array();
							$total_sale_so_far=0;
							$rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'wc_seller_field',
								'meta_value' => $ID
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                         /*   if ($ID3 == $IDD) { */
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									/*Active / inactive*/
									
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
									/* Start Order ID Array */
										$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
										if($order_type=="Online") 
										{
											$orderid_buyer[]=$order->get_id();	
										}
										/* End Order ID Array */
									
								  if ($frID3 != $order_customer_id) {
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
								 else if ($frID3 == $order_customer_id) {
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
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							if($ID3 == "custom") 
							{
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									
									/*Active / inactive*/
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
										
										
									
									$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
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
								/* Start Order ID Array */
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									if($order_type=="Online") 
									{
										$orderid_buyer[]=$order->get_id();	
									}
									/* End Order ID Array */
										$order_type_forarr = get_post_meta($order->get_id(), 'wc_order_type', true );
										$track_arr[]=array("seller_name"=>$trimmeddata_s_name, "orderId"=>$order->get_id(),"order_type"=>$order_type_forarr);
										/* total Item  Sold */
										foreach ($order->get_items() as $item_key => $item_values): 
                                            $item_data = $item_values->get_data(); 
											$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
											if($order_type=="Online") 
											{
												$item_qty_manly_online  +=  $item_data['quantity']; 
											}
											else{}
										endforeach; 
										/* total Item  Sold */
									
										if ($frID3 != $order_customer_id) {
											$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
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
									  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
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
							   
						/* Ship to School - manaully sellers */	  
						$total_sale_so_far_manly_shiptoschool=0;
						$item_qty_fund_online2=0;
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
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($ID3=="custom") {
									
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
									$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
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
										$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
										$track_arr_shiptschool[]=array("seller_name"=>$trimmeddata_s_name, "orderId"=>$order->get_id(),"order_type"=>$order_type);
										if ($order_type=='ship-to-school') {
								
										$arr_order_id2[]=$order->get_id();
								
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
												$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
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
						$percentage  = 40;
                        $percentFifty= 50;
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online+(isset($item_qty_fund_online2) ? $item_qty_fund_online2 : 0);
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
						
						$rebate_so_far3=$rebate_so_far+$rebate_so_far2+$rebate_so_far_manly_online; 
						$rebate_so_far3=number_format($rebate_so_far3, 2, '.', '');
						 // End Ship to school	
						 rsort($orderid_buyer);
						
				   ?>
<div class="main-page-banner">
	<img src="<?php echo site_url();?>/wp-content/uploads/2025/10/WhatsApp_Image_2025-10-08_at_21.08.23_65ef9796.jpg">
</div>
	<div class="sc-jtRfpW eaTkcl">
		<div class="sc-lhVmIH gxfVgS">
		   <div class="left-side sc-btzYZH fYjnlz">
			  <div class="sc-elJkPf hWeIyu zxcvzxcvz2222">
			  <div class="mobile-screen">
				  <h4 class="seller-msg">
				  Seller Message
			  </h4>
			  <div class="sc-tilXH kKkoMh small-screen">
				<div class="sc-hEsumM ctVDjL">
				   <div class="sc-ktHwxA ebvVGN"></div>
				</div>
			 </div>
			 <div class="sc-kafWEX civCQf">
			 
				<div class="sc-feJyhm jqEzoc bio-msg-smallScr"><?php if(!empty($description) && !empty($seller_desc)){ echo $seller_desc; } else if(!empty($description)){ echo $description; } else if(!empty($seller_desc)){ echo $seller_desc; } else { ?> Help us reach our goal! Thank you for the support! <?php } ?></div>
			 </div>
			 </div>
				 <div id="#store_participant">
				        <h1 class="sc-gxMtzJ kJfAmm">HELP US REACH OUR GOAL</h1>
<!-- 					<h1 class="sc-gxMtzJ kJfAmm"><?php  echo $f." ".$l; ?></h1> -->
					<h3 class="sc-dfVpRl kZLEoE">Fundraiser Code: <?php echo "#".$fid; ?></h3>
				 </div>
				 <div class="progress progressBar">
				 <?php  $goal_amount_clean = preg_replace('/[^0-9.]/', '', $goal_amount);  $width=round($rebate_so_far3 / ($goal_amount_clean / 100),2); ?>
					<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $width."%"; ?>;"></div>
				</div>
				<!-- <div class="sc-gzOgki hFzTRD">
					<div class="sc-iyvyFf kzulUN"></div>
				 </div>-->
				 <div class="sc-dqBHgY ccbzhW">
					<div class="sc-hwwEjo eoCznv">
					   <div width="55" class="sc-kTUwUJ jZDald">$<?php echo $rebate_so_far3=number_format($rebate_so_far3, 2, '.', '')." "; ?></div>
					   <div class="sc-kTUwUJ eYHKCV zxc55"> / $<?php echo $goal_amount_clean=number_format($goal_amount_clean, 2, '.', '')." "; ?></div>
					</div>
					<div id="#fundraising_dates" class="sc-kTUwUJ eYHKCV"><?php  echo $newDate; ?> - <?php  echo $newDate2; ?></div>
				 </div>
				 <div class="mobile-screen">
					<div class="sc-elJkPf hWeIyu">
						<div class="sc-bMvGRv brJQDe">
			   
							<div id="DemoStoreShareButton" class="sc-CtfFt eGOSHP" style="cursor:pointer;" >SHARE</div>
							<div class="social-media"> 
				 <a  title="Click to share this profile on facebook" href="https://www.facebook.com/sharer.php?u=<?php echo home_url('/seller/'); ?><?php echo $userInfo; ?>&t=<?php echo $f." ".$l; ?> | Support Now | Fundraising" class="fr-btn" target="_blank"><i class="fa fa-facebook-square" aria-hidden="true"></i></a>
				 <a class="fr-btn instagrambtn" ><i class="fa fa-instagram"></i></a>
			<a id="CopyLink" class="fr-btn style-scope copy-code" ><i class="fa fa-clone" aria-hidden="true"></i></a>
					 </div>
						</div>
					</div>
				 </div>
			  </div>
			  <div class="sc-bYSBpT lhXSJZ"></div>
			 <!-- <div id="#shop_popcorn" class="shop_fund_profile" data-support="">
				 <div class="sc-hrWEMg jdKEFA">Shop Glow Fundraiser</div>
				 <div class="sc-bwCtUz jdixbU row products-loop products-grid row-count-4">
				 <?php 
				 $count=1;
				 $arg=array('post_type'=>'product', 
						'post_status' => 'publish',
						'posts_per_page' => -1,
						'order' => 'ASC',
						'product_cat' => esc_html( "Hexagon Jars" )
						);
						$table_query= new WP_Query();
						$table_query->query($arg);
						if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
						$postid = $table_query->post->ID; 
						$price = get_post_meta( $postid, '_regular_price', true);
						if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
						if($count<=4){
				 ?>
					<div class="product col-lg-4 col-sm-4 col-xs-6 ">
						<a href="<?php echo get_permalink();?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">	</a>
						   <div class="content-product">
							   <div class="product-image-wrapper hover-effect-slider">
								 <a class="product-content-image" href="<?php echo get_permalink();?>" data-images="">
									
								 <?php if ( has_post_thumbnail( $postid ) ) {
                        $attachment_ids[0] = get_post_thumbnail_id( $postid );
                         $attachment = wp_get_attachment_image_src($attachment_ids[0], 'full' ); ?>    
                        <img  width="400" height="400" src="<?php echo $attachment[0] ; ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" loading="lazy"  />
                    <?php } ?>
								 
								 </a>
								</div>
								<div class="text-center product-details">
   
								   <div class="product-title">
									  <a href="<?php echo get_permalink();?>"><?php the_title();?></a>
								   </div>
								   <span class="price"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span><?php echo $price; ?></bdi></span></span>
								   <div class="product-excerpt">
									  <?php the_excerpt(); ?>	        
								   </div>
								   <a href="?add-to-cart=<?php echo $postid; ?>" rel="nofollow" data-product_id="<?php echo $postid; ?>" data-product_sku="" class="add_to_cart_button btn border-grey progress-button etheme_add_to_cart_button ajax_add_to_cart  product_type_simple" data-style="shrink" data-horizontal=""><i class="ico-smallpacket"></i> Add to cart</a>		
								</div>
						   </div>
					</div>
						<?php } } $count++;
								endwhile;
								endif;
						?>
					
					
				 </div>
			  </div>
			   <div class="shop_section" style="margin-top:0px;"><a href="<?php echo $uRL=get_option("site_url"); ?>/shop-now/">Shop Now</a></div>-->
			 
			    <!-- Blocks Winter/summer season -->
				 <div class="sc-hrWEMg jdKEFA mainHeading">Click To Shop</div>
				   <div class="block-banner">
					 <div class="banner-first row">
						<div class="col-md-4 col-sm-4 col-xs-4 block">
							<div class="cover-img">
								<p>
								<!--<img class="img-fluid" src="https://glowscentedcandles.com/wp-content/uploads/2023/08/296x420-01.png" alt="hexagon-jars">-->
								<!--<img class="img-fluid" src="https://glowscentedcandles.com/wp-content/uploads/2024/08/Main-Banner_1920x851-11.png" alt="hexagon-jars">-->
								<img class="img-fluid" src="https://glowscentedcandles.com/wp-content/uploads/2026/01/classic-banner.jpg" alt="Classic">
								</p>
								<div class="overlay"><a href="<?php echo home_url(); ?>/product-category/classic-candles/">SHOP</a></div>
							</div>
						</div>
						 <div class="col-md-4 col-sm-4 col-xs-4 block">
							<div class="cover-img">
								<!--<p><img class="img-fluid" src="https://glowscentedcandles.com/wp-content/uploads/2023/08/296x420-02.png" alt="16oz-round-classic"></p>-->
								<!--<p><img class="img-fluid" src="https://glowscentedcandles.com/wp-content/uploads/2024/09/Main-Banner_1920x851-10-3.png" alt="16oz-round-classic"></p>-->
								<p><img class="img-fluid" src="https://glowscentedcandles.com/wp-content/uploads/2026/01/melt-banner.jpg" alt="Melt"></p>
								<div class="overlay"><a href="<?php echo home_url(); ?>/product-category/large-wax-melts-6-cubes/">SHOP</a></div>
							</div>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-4 block">
							<div class="cover-img">
								<!--<p><img class="img-fluid" src="https://glowscentedcandles.com/wp-content/uploads/2023/08/296x420-04.png" alt="flameless-scented-melts"></p>-->
								<!--<p><img class="img-fluid" src="https://glowscentedcandles.com/wp-content/uploads/2024/08/Main-Banner_1920x851-08.png" alt="melts"></p>-->
								<p><img class="img-fluid" src="https://glowscentedcandles.com/wp-content/uploads/2026/01/oils-banner.jpg" alt="Oils"></p>
								<div class="overlay"><a href="<?php echo home_url(); ?>/product-category/essential-oils/">SHOP</a></div>
							</div>
						</div>
						
						
				 </div>
				   
				      <div class="banner-second row">
					  <div class="col-md-4 col-sm-4 col-xs-4 block">
							<div class="cover-img">
								<!--<p><img class="img-fluid" src="https://glowscentedcandles.com/wp-content/uploads/2023/08/296x420-03.png" alt="room-spray"></p>-->
								<!--<p><img class="img-fluid" src="https://glowscentedcandles.com/wp-content/uploads/2024/08/Main-Banner_1920x851-05.png" alt="room-spray"></p>-->
								<p><img class="img-fluid" src="https://glowscentedcandles.com/wp-content/uploads/2026/01/signature-banner-1.jpg" alt="Signature Candles"></p>
								<div class="overlay"><a href="<?php echo home_url(); ?>/product-category/2-wick-signature-candles/">SHOP</a></div>
							</div>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-4 block">
							<div class="cover-img">
								<p>
								<!--<img class="img-fluid" src="https://glowscentedcandles.com/wp-content/uploads/2023/08/296x420-05.png" alt="essential-oils">-->
								<!--<img class="img-fluid" src="https://glowscentedcandles.com/wp-content/uploads/2024/08/Main-Banner_1920x851-09.png" alt="essential-oils">-->
								<img class="img-fluid" src="https://glowscentedcandles.com/wp-content/uploads/2026/01/spray-banner.jpg" alt="Room Spray">
								</p>
								<div class="overlay"><a href="<?php echo home_url(); ?>/product-category/all-items/room-sprays-all-items/">SHOP</a></div>
							</div>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-4 block">
							<div class="cover-img">
								<!--<p><img class="img-fluid" src="https://glowscentedcandles.com/wp-content/uploads/2023/08/296x420-06.png" alt="boxed-metallic"></p>-->
								<!--<p><img class="img-fluid" src="https://glowscentedcandles.com/wp-content/uploads/2024/08/Main-Banner_1920x851-07.png" alt="boxed-metallic"></p>-->
								<p><img class="img-fluid" src="https://glowscentedcandles.com/wp-content/uploads/2026/01/boxed-image.jpg" alt="boxed-metallic"></p>
								<div class="overlay"><a href="<?php echo home_url(); ?>/product-category/boxed-metallic/">SHOP</a></div>
							</div>
						</div>
				 </div>
			  </div>
		 </div>
	   <div class="right-side sc-jwKygS gaqdDY">
		   <div class="right-bar-img">
<!-- 			   <img src="<?php // echo site_url();?>/wp-content/uploads/2025/10/Frame-92-1.png"> -->
		   </div>
		   <div class="sc-tilXH kKkoMh rightSide">
				<div class="sc-hEsumM ctVDjL">
				   <div class="sc-ktHwxA ebvVGN">
					</div>
				</div>
			 </div>
		  <div id="#store_personalization" class="sc-elJkPf hWeIyu zxcvzcvxds7889 smallScr992" style="margin-bottom:20px;">
			  <div class="sc-kafWEX civCQf gg small-screen-hide">
				  <div class="sc-feJyhm jqEzoc bio-msg" style="text-align:center;"><?php if(!empty($description) && !empty($seller_desc)){ echo $seller_desc; } else if(!empty($description)){ echo $description; } else if(!empty($seller_desc)){ echo $seller_desc; } else { ?> Help us reach our goal! Thank you for the support! <?php } ?></div>
			  </div>
			  	<div class="slr_ovrly">
					<img src="<?php echo site_url();?>/wp-content/uploads/2025/10/Frame-92-2.png">
				  </div>
			  <div class="bio-msg" style="margin-bottom:0; padding-bottom:0; text-align:center;">
			  You’ve made a difference today 💕 Want to multiply it? Share this link with everyone you know!”
				  
			  </div>
		  </div>
		  <div class="sc-elJkPf hWeIyu small-screen-hide smallScr992">
			 <div class="sc-bMvGRv brJQDe">
			   
				<div id="DemoStoreShareButton" class="sc-CtfFt eGOSHP" style="cursor:pointer;" >SHARE <img src="<?php echo site_url();?>/wp-content/uploads/2025/10/Ellipse-1.svg"></div>
				 
				 <div class="social-media"> 
				 <a  title="Click to share this profile on facebook" href="https://www.facebook.com/sharer.php?u=<?php echo home_url('/seller/'); ?><?php echo $userInfo; ?>&t=<?php echo $f." ".$l; ?> | Support Now | Fundraising" class="fr-btn" target="_blank"><i class="fa fa-facebook-square" aria-hidden="true"></i></a>
				 <a class="fr-btn instagrambtn" ><i class="fa fa-instagram"></i></a>
			<a id="CopyLink" class="fr-btn style-scope copy-code" ><i class="fa fa-clone" aria-hidden="true"></i></a>
					 </div>
			 </div>
		 <div class="sc-bYSBpT lhXSJZ"></div>
		 <div id="#event_leaderboard" class="sc-iQKALj hPPNSx">
			 <div class="sc-RefOD jFdVyI">Participant Leaderboard:</div>
			 <!--<div class="sc-RefOD jFdVyI">North West Track &amp; Field</div>-->
			 <?php  
					/*Ship to School - Catalog qty */
					 $total_cat_quantity =0; 						
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
					/* End */


					$count=1;
					$full_arr = array();
					$all_meta_for_users = get_users(array(
                           'meta_key'     => 'user_registration_seller_fundraiser',
                           'meta_value'   => $ID,
                          ));
						
					foreach ( $all_meta_for_users as $all_meta_for_user ) { 
						$total_sale_so_far=0;
						$rebate_so_far=0;
						$item_qty_fund_online=0;
						$IDD=$all_meta_for_user->ID; 
						$name=$all_meta_for_user->user_firstname." ".$all_meta_for_user->user_lastname;
                        $args = array(
                                'status'   =>  array('wc-waiting-call','completed','processing'),
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
								/* if ( $order->has_status('completed') || $order->has_status('processing')) 
								{  */
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
												
											$percentage  = 40;
											$percentFifty= 50;
											$real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
											if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
											{
												$Discount  = ($percentFifty / 100) * $total_sale_so_far;
												$rebate_so_far = number_format($Discount, 2, '.', '');
												
											}
											else
											{
												$Discount  = ($percentage / 100) * $total_sale_so_far;
												$rebate_so_far = number_format($Discount, 2, '.', '');
												
											}				
														 
									 }
									 else
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
								
								/* } */

							}
					}
					$full_arr[$name]=number_format($total_sale_so_far, 2, '.', '');
					$count++;
		
				}
			?>
		<?php /* mauanlly added seller */
							$seller_name = array();
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
														 $name_manually=$seller_name[$i];
														 foreach ($order->get_items() as $item_key => $item_values): 
															$item_data = $item_values->get_data();  
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
													   $full_arr[$name_manually]=number_format($seller_total_sale, 2, '.', '');
	
														
													}
												
											 
										 }
									} }
							}
						}
						$seller_total_Sale_array[]=$seller_total_sale;
						$seller_rebate_array[]=$seller_rebate;
						}
						   arsort($full_arr);
						/*   print_r($full_arr);*/
					
	?>
	 <?php 
	   /*  $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID
                            );
                            $orders = wc_get_orders($args);
							for($i=0;$i<count($seller_name);$i++)
							{ ?>
						 <div class="sc-kPVwWT bqqpfS">
				<div class="sc-ibxdXY jpHjAO">
				   <div class="sc-kIPQKe eixpDv"><?php $h=$i; echo $h+1;?></div>
				   <div class="sc-kfGgVZ bnSLTW"></div>
				   <div class="sc-esjQYD cQcVNN"><?php echo $seller_name[$i]; ?></div>
				</div>
				<div class="sc-eXEjpC iAGvZs"><?php echo "$".$seller_r=number_format($seller_rebate_array[$i], 2, '.', ''); ?></div>
			 </div>
							<?php } */?>
							 <?php $t=1; foreach($full_arr as $x => $x_value) { 
									if($t<=5){							 
									?>
			 <div class="sc-bYSBpT lhXSJZ"></div>
			 <div class="sc-kPVwWT bqqpfS">
				<div class="sc-ibxdXY jpHjAO">
				   <div class="sc-kIPQKe eixpDv"><?php echo $t;?></div>
				   <div class="sc-kfGgVZ bnSLTW"></div>
				   <div class="sc-esjQYD cQcVNN"><?php echo $x; ?></div>
				</div>
				<div class="sc-eXEjpC iAGvZs"><?php echo "$".$x_value; ?></div>
			 </div>
									<?php $t++; } } ?>
			<!-- <div class="sc-kPVwWT bqqpfS">
				<div class="sc-ibxdXY jpHjAO">
				   <div class="sc-kIPQKe eixpDv">2</div>
				   <div class="sc-kfGgVZ kaIYXE"></div>
				   <div class="sc-esjQYD cQcVNN">Dani Everett</div>
				</div>
				<div class="sc-eXEjpC iAGvZs">$523</div>
			 </div>
			 <div class="sc-kPVwWT bqqpfS">
				<div class="sc-ibxdXY jpHjAO">
				   <div class="sc-kIPQKe eixpDv">3</div>
				   <div class="sc-kfGgVZ bjLWnN"></div>
				   <div class="sc-esjQYD cQcVNN">Coach Kim</div>
				</div>
				<div class="sc-eXEjpC iAGvZs">$475</div>
			 </div>
			 <div class="sc-kPVwWT bqqpfS">
				<div class="sc-ibxdXY jpHjAO">
				
				   <div class="sc-kIPQKe eixpDv">4</div>
				   <div class="sc-kfGgVZ eiboPB"></div>
				   <div class="sc-esjQYD cQcVNN">Melissa Morris</div>
				</div>
				<div class="sc-eXEjpC iAGvZs">$415</div>
			 </div>
			 <div class="sc-kPVwWT bqqpfS">
				<div class="sc-ibxdXY jpHjAO">
				   <div class="sc-kIPQKe eixpDv">5</div>
				   <div class="sc-kfGgVZ kRrrjx"></div>
				   <div class="sc-esjQYD cQcVNN">Sofia Hunter</div>
				</div>
				<div class="sc-eXEjpC iAGvZs">$349</div>
			 </div>-->
		  </div>
		  <div id="buyers" class="sc-elJkPf hWeIyu">
			 <div class="sc-gJWqzi dGEqvM">
				<div class="sc-rBLzX kLuVwd">5 Recent Buyers</div>
			 </div>
			 <?php 
			  /*
			 $buyer_name=array();
			 $buyer_total=array();
			 $c=array();
			 $orderDate=array();
			
			 $args = array(
                                'status'   => "published",
                                'meta_key' => 'wc_seller_field',
								'meta_value' => $ID,
								'order' => 'DESC'
                            );
                            $orders = wc_get_orders($args);
							foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
								$total_sale_so_far=0;
                          
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) 
								{
										
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
								  if ($frID3 != $order_customer_id) 
								  { 
										
                                 
									$first=$order->get_billing_first_name();
									$last=$order->get_billing_last_name();
									$s_name=$first." ".$last;
									$s_name_lower= strtolower($s_name);
									if(!in_array($s_name_lower,$buyer_name))
									{
										$buyer_name[]=strtolower($s_name);
										$c[]=$order->get_billing_country();
										date_default_timezone_set('America/Chicago');
										$date1 = date('Y-m-d');
										
										$d2 = $order->get_date_created();
										$date2 = substr($d2, 0, 10);

										$date1 = new DateTime($date1);
										$date2 = new DateTime($date2);
										$diff=date_diff($date2,$date1);
										$days_ago=$diff->format("%a days");
										$orderDate[]=$days_ago;
										
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
                                             endforeach; 
                                     
										$total_product = $order->get_total();  


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
										$Discount_d = number_format($Discount, 2, '.', '');
										$rebate_so_far+=$Discount_d;
									 }
									 else
									 {
										$Discount    = ($percentage / 100) * $totalAmount;
										$Discount_d = number_format($Discount, 2, '.', '');
										 $rebate_so_far+=$Discount_d;
									 }
									 $buyer_total[]=$total_sale_so_far;
								 }
							  
								} } } 
						}
						*/
				$buyer_name=array();
				$buyer_total=array();
				$c=array();
				$orderDate=array();
				//print_r($orderid_buyer);
				for($i=0;$i<count($orderid_buyer);$i++){
							$orderID=$orderid_buyer[$i];
							$order = wc_get_order( $orderID );
							if ( $order ) {
							$order_status2="";
							$order_status1="";
							$order_status1 = $order->get_status(); 
							$order_status2 = str_replace(' ', '', $order_status1);
							if($order_status2=="processing" || $order_status2=="completed"){
								
								$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								if($order_type=="Online") 
								{	$total_sale_so_far=0;
									$rebate_so_far=0;
									$first=$order->get_billing_first_name();
									$last=$order->get_billing_last_name();
									$s_name=$first." ".$last;
									$s_name_lower= strtolower($s_name);
									$buyer_name[]=strtolower($s_name);
									$c[]=$order->get_billing_country();
									
									date_default_timezone_set('America/Chicago');
									$date1 = date('Y-m-d');
									$d2 = $order->get_date_created();
									$date2 = substr($d2, 0, 10);
									$date1 = new DateTime($date1);
									$date2 = new DateTime($date2);
									$diff=date_diff($date2,$date1);
									$days_ago=$diff->format("%a days");
									$orderDate[]=$days_ago;
									
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
									$buyer_total[]=$total_sale_so_far;
										
								}
								
							}
							}
						 }		
				$cnt=1;
				for($i=0;$i<count($buyer_name);$i++) 
				{
				if($cnt<=5)	{	
			 ?>
			 <div class="sc-eilVRo jEXOub">
				<div class="sc-bnXvFD hfFGhf">
				   <div class="sc-dymIpo SPXWF">
					  <div class="sc-eerKOB bILfZF">😍</div>
					  <div class="sc-cpmLhU IDcrV">
						 <div class="sc-emmjRN iomnjy" style="text-transform:capitalize;"><?php echo $buyer_name[$i]; ?></div>
						
						 <div class="sc-fhYwyz iInHIm"><?php echo $c[$i]." - ".$orderDate[$i]." ago"; ?></div>
					  </div>
				   </div>
				</div>
				<div class="sc-jzgbtB dYgjKy">$<?php echo $buyer_total[$i]; ?></div>
			 </div>
			 <div class="sc-bYSBpT lhXSJZ"></div>
				<?php } $cnt++; } ?>
			
		  </div>
	   </div>
	</div>
</div>
<?php
$image_url_profile=get_user_meta($u_id,"user_registration_image_url_profile",true);
$seller_imageUrl=get_user_meta($u_id,'seller_profile_image',true);
if(!empty($seller_imageUrl)) {
$crop_url_profile2=profile_image_resize_crop( $src=$seller_imageUrl, $w=512, $h=512, $dest = null, $override = true, $createNewIfExists = true );
?>
<style>
.kKkoMh
{
	background: url(<?php echo $crop_url_profile2; ?>) 50% 0px / cover rgb(255, 255, 255) !important;
}
</style>

<?php
}
else if(!empty($image_url_profile)) {
	$crop_url_profile1=profile_image_resize_crop( $src=$image_url_profile, $w=512, $h=512, $dest = null, $override = true, $createNewIfExists = true);
?>
<style>
.kKkoMh
{
	background: url(<?php echo $crop_url_profile1; ?>) 50% 0px / cover rgb(255, 255, 255) !important;
}
</style>
<?php }
else { }?>

<style>
.manual-mass-popup, 
	.manual-mass-generation-popup,
	.manual-mass-close_out-popup
	{
		position: fixed;
		width: 100%;
		height: 100%;
		z-index: 999;
		background: rgba(0,0,0,0.6);
		top: 0;
		left: 0;
		display: none;
	}
	.manual-mass-popup .manual-mass-order-popup-wrapper,
	.manual-mass-generation-popup .manual-mass-order-popup-wrapper,
	.manual-mass-close_out-popup .manual-mass-order-popup-wrapper
	{
    display: block;
    width: 100%;
    max-width: 500px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateY(-50%) translateX(-50%);
    text-align: center;
    padding: 40px 25px;
    background: #fff;
    border: 3px solid #000;
    border-radius: 5px;
	}
.manual-mass-popup a.fr-btn {
    display: block;
    margin: 20px auto;
    width: 100%;
    max-width: 250px;
}
.manual-mass-generation-popup .manual-mass-order-popup-wrapper,
.manual-mass-close_out-popup .manual-mass-order-popup-wrapper
{
    padding: 30px 25px;
    max-width: 600px;
    border: 3px solid #cda85c; 
}
.manual-mass-generation-popup span,
.manual-mass-close_out-popup span
{
    padding-bottom: 6px;
    display: inline-block;
    font-size: 18px;
    font-weight: 700;
    border-bottom: 2px dotted #cda85c;
    color: #cda85c;
}
#manual-mass-popup .show
{
	    background: #fff;
    color: #000;
    margin: 0 auto;
    width: 100%;
    text-align: center;
}
.manual-mass-popup a.fr-btn
{
    display: block;
    margin: 0px auto;
    width: 100%;
    background: none;
    color: #000;
	max-width: 100%;
	display: inline;
    margin: 0px auto;
    width: auto;
	font-size: 25px;
}
#CopyLink
{
	/*cursor: pointer;
    background: #000;
    color: #fff;
    padding: 10px 20px;
    width: 39%;
    margin: 0 auto;
    display: inline-block;*/
}
.banner-second img, .banner-first img,.banner-thrid img  {
    width: 100%;
}
#m-link
{
	    display: block;
    margin-top: 10px;
}
</style>
<!--  <div class="manual-mass-popup">
        <div class="manual-mass-order-popup-wrapper">
		<h3>Share Profile Link and Support Us</h3>
         <?php 
					if(get_query_var( 'seller' ))
					{
						$userInfo = get_query_var( 'seller' );
					}
					else if(get_query_var( 'user' ))
					{
						$userName = get_query_var( 'user' );
						$userInfo = "user/".$userName;
					}
					else
					{
								
					}
		 ?>
		 
			
			<span id="fundLink" style="display:none;"><?php echo home_url('/seller/'); ?><?php echo $userInfo; ?></span>
         
			 <a href="#" id="m-link" class="txt-link">Cancel</a>
        </div>
    </div> -->
	<?php  $fid=get_user_meta( $IDG , 'user_registration_seller_fundraiser' , true );?>
	<script>
	
	jQuery(document).on('click', '#DemoStoreShareButton', function (e) 
			{
				e.preventDefault();
				jQuery(".manual-mass-popup").addClass("show");
				
				
			});
			jQuery(document).on('click', '#m-link', function (e) 
			{
				e.preventDefault();
				jQuery(".manual-mass-popup").removeClass("show");
				
			});
			jQuery(document).on('click', '#CopyLink', function (e) 
			{
				e.preventDefault();
				var $temp = jQuery("<input>");
				jQuery("body").append($temp);
				$temp.val(jQuery('#fundLink').text()).select();
				document.execCommand("copy");
				$temp.remove();
				jQuery('#CopyLink').text('Copied');
			});
			
			jQuery(document).ready(function()
			{
				jQuery('.add_to_cart_button').click(function() { 
				setTimeout(function(){
				var check=jQuery('.shop_fund_profile').attr('data-support');
				if(check=='') {
				var ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>";
         jQuery.ajax({

         type : "post",

         url : ajaxurl,

         data : {action: 'wdm_auto_fundraiser_code2',fund_id2:<?php echo $fid; ?>, seller_id:<?php echo $IDG; ?>},

         success: function(response) {
						jQuery('.shop_fund_profile').attr('data-support','yes');
						/*console.log(response);
						window.location.href="https://devglow.wpengine.com/checkout/";*/
						}
		});
				}
				
				}, 1500);
				});
				
				
			});
	</script>
	<script>
	
	jQuery(document).on('click', '#DemoStoreShareButton', function (e) 
			{
				e.preventDefault();
				jQuery(".manual-mass-popup").addClass("show");
				
				
			});
			jQuery(document).on('click', '#m-link', function (e) 
			{
				e.preventDefault();
				jQuery(".manual-mass-popup").removeClass("show");
				
			});
			jQuery(document).on('click', '#CopyLink', function (e) 
			{
				e.preventDefault();
				var $temp = jQuery("<input>");
				jQuery("body").append($temp);
				$temp.val(jQuery('#fundLink').text()).select();
				document.execCommand("copy");
				$temp.remove();
				jQuery('#CopyLink').text('Copied');
			});
			
			jQuery(document).ready(function()
			{
				jQuery('.add_to_cart_button').click(function() { 
				setTimeout(function(){
				var check=jQuery('.shop_fund_profile').attr('data-support');
				if(check=='') {
				var ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>";
         jQuery.ajax({

         type : "post",

         url : ajaxurl,

         data : {action: 'wdm_auto_fundraiser_code',fund_id:<?php echo $IDG; ?>},

         success: function(response) {
						jQuery('.shop_fund_profile').attr('data-support','yes');
						/*console.log(response);
						window.location.href="https://devglow.wpengine.com/checkout/";*/
						}
		});
				}
				
				}, 1500);
				});
				
				
			});
			jQuery(document).on('click', '#close_id, #ok_id', function (e) 
			{
				e.preventDefault();
				jQuery("#brave_popup_12975").css("display","none");
			});
	</script>
	  <?php 
						
							if(!empty($IDG))
							{
								$ID = $IDG;
								$frID2=$IDG;
							}
							else
							{
								$ID = $frID2;
								
							}
							$goal_amount=get_user_meta( $u_id , 'user_registration_fr_goal' , true );
							$goal_amount=get_user_meta( $u_id , 'user_registration_seller_goal' , true );
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

								/*   if ($ID3 == $IDD) { */
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									/*Active / inactive*/
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
								 else if ($frID3 == $order_customer_id) {
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
								else{}
								} }
                            /*     } */
						}

				   ?>
	<style>
	#brave_popup_12975 {
    z-index: 1000000000;
}
.brave_popup {
    width: 100%;
    float: left;
    position: absolute;
    z-index: 999999999;
    display: block;
    direction: ltr;
}
.brave_popup .brave_popup__step.has_overlay {
    position: fixed;
    width: 100%;
    height: 100vh;
    z-index: 9999999;
    left: 0;
    top: 0;
}
.brave_popup .brave_popup__step__inner {
    transform: translateX(0);
    position: fixed;
    z-index: 9;
    display: block;
}
.brave_popup .brave_popup__step.position_center .brave_popup__step__inner {
    top: 50%;
    left: 0;
    right: 0;
    bottom: auto;
    margin: 0;
    margin-left: auto!important;
    margin-right: auto!important;
    outline: none;
}
#brave_popup_12975__step__0 .brave_popup__step__desktop .brave_popup__step__inner {
    width: 940px;
    height: 440px;
    border-radius: 24px;
    margin-top: -220px;
    font-family: brave_custom-avenirnext;
}
.brave_popup .brave_popup__step__inner .brave_popupSections__wrap {
    width: 100%;
    height: 100%;
    will-change: transform;
    position: relative;
}
.brave_popup .brave_popup__step__inner .brave_popupMargin__wrap {
    width: 100%;
    height: 100%;
    will-change: transform;
    position: relative;
    outline: none;
}
#brave_popup_12975__step__0 .brave_popup__step__desktop .brave_popupMargin__wrap {
    top: 0px;
    left: 0px;
}
.brave_popup .brave_popup__close {
    position: absolute;
    cursor: pointer;
    padding: 3px;
    z-index: 99999;
    transition: all .14s linear;
}
.brave_popup .brave_popup__close.brave_popup__close--inside_right {
    width: auto!important;
}
.brave_popup .brave_popup__close.brave_popup__close--icon {
    opacity: .5;
}
.brave_popup .brave_popup__close.brave_popup__close--inside_right {
    top: 5px!important;
    right: 7px;
}
#brave_popup_12975__step__0 .brave_popup__step__desktop .brave_popup__close {
    font-size: 30px;
    width: 30px;
    color: rgba(0,0,0, 1);
    top: -40px;
}
svg {
    overflow: hidden;
    vertical-align: middle;
}
#brave_popup_12975__step__0 .brave_popup__step__desktop .brave_popup__close svg {
    width: 30px;
    height: 30px;
}
.brave_popup .brave_popup__step__inner .brave_popup__step__popup {
    width: 100%;
    height: 100%;
    box-sizing: border-box;
    position: relative;
    overflow: hidden;
}
#brave_popup_12975__step__0 .brave_popup__step__desktop .brave_popup__step__popup {
    border-radius: 24px;
}
.brave_popup .brave_popup__step__inner .brave_popup__step__popup .brave_popup__step__content {
    width: 100%;
    height: 100%;
    float: left;
    letter-spacing: 0;
    overflow: hidden;
}
#brave_popup_12975__step__0 .brave_popup__step__desktop .brave_popup__step__content {
    background-color: rgba(255,255,255, 1);
}
.brave_popup .brave_element {
    position: absolute;
    will-change: transform;
}
.brave_popup .brave_popup__step__inner .brave_popup__step__popup .brave_popup__step__content a, .brave_popup .brave_popup__step__inner .brave_popup__step__popup .brave_popup__step__content div, .brave_popup .brave_popup__step__inner .brave_popup__step__popup .brave_popup__step__content li, .brave_popup .brave_popup__step__inner .brave_popup__step__popup .brave_popup__step__content ol, .brave_popup .brave_popup__step__inner .brave_popup__step__popup .brave_popup__step__content p, .brave_popup .brave_popup__step__inner .brave_popup__step__popup .brave_popup__step__content span, .brave_popup .brave_popup__step__inner .brave_popup__step__popup .brave_popup__step__content ul {
    font-family: inherit;
    line-height: inherit;
    font-weight: inherit;
    color: inherit;
}
.brave_popup .brave_popup__step__inner .brave_popup__step__popup .brave_popup__step__content .brave_popup__step__elements {
    position: relative;
    z-index: 1;
    width: 1024px;
    height: 100%;
    margin: 0 auto;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnf {
    width: 300px;
    height: 333.56848549888px;
    top: 52.96875px;
    left: 43.871536254883px;
    z-index: 0;
}
#brave_popup_12975__step__0 .brave_popup__step__desktop .brave_element__wrap {
    font-family: brave_custom-avenirnext;
}
.brave_popup .brave_element .brave_element__wrap {
    position: relative;
    min-height: 20px;
    height: 100%;
    width: 100%;
    scrollbar-color: #ddd transparent;
    scrollbar-width: thin;
}
.brave_popup .brave_element.brave_element--image .brave_element__styler {
    width: 100%;
    height: 100%;
    overflow: hidden;
    position: relative;
    box-sizing: border-box;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mng {
    width: 490px;
    height: auto;
    top: 50px;
    left: 380px;
    z-index: 1;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh .brave_element__text_inner {
    text-align: left;
    font-size: 18px;
    font-family: 'brave_custom-Sans pro';
    line-height: 1.7em;
    color: rgba(138,138,138, 1);
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnf .brave_element__styler {
    border-radius: 30px;
}
.brave_popup .brave_element.brave_element--image .brave_element__inner {
    width: 100%;
    height: 100%;
}
.brave_popup .brave_element.brave_element--image .brave_element__styler img {
    width: 100%;
    height: 100%;
    position: absolute;
    -o-object-fit: cover;
    object-fit: cover;
    vertical-align: bottom;
    -o-object-position: top;
    object-position: top;
    max-width: unset;
}
.brave_popup .brave_element.brave_element--text .brave_element__styler {
    font-size: 13px;
    line-height: 1.7em;
    padding: 5px;
    color: #000;
    font-weight: 400;
    font-style: normal;
    text-decoration: none;
    text-align: left;
    width: 100%;
    height: 100%;
    box-sizing: border-box;
}

.brave_popup .resone-dis {
    font-size: 16px;
    font-family: roboto !important;
}
.brave_popup .brave_popup__step__inner {
    transform: translateX(0);
    position: fixed;
    z-index: 9;
    display: block;
}
.brave_popup .brave_popup__step.position_center .brave_popup__step__inner {
    top: 50%;
    left: 0;
    right: 0;
    bottom: auto;
    margin: 0;
    margin-left: auto!important;
    margin-right: auto!important;
    outline: none;
}
.brave_popup .brave_popup__step__inner .brave_popupSections__wrap {
    width: 100%;
    height: 100%;
    will-change: transform;
    position: relative;
}
.brave_popup .brave_popup__step__inner .brave_popupMargin__wrap {
    width: 100%;
    height: 100%;
    will-change: transform;
    position: relative;
    outline: none;
}
.brave_popup .brave_popup__close {
    position: absolute;
    cursor: pointer;
    padding: 3px;
    z-index: 99999;
    transition: all .14s linear;
}
.brave_popup .brave_popup__close.brave_popup__close--inside_right {
    width: auto!important;
}
.brave_popup .brave_popup__close.brave_popup__close--icon {
    opacity: .5;
}
.brave_popup .brave_popup__close.brave_popup__close--inside_right {
    top: 5px!important;
    right: 7px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh {
    width: 450px;
    height: auto;
    top: 162px;
    left: 380px;
    z-index: 2;
	    top: 157px;

}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh .brave_element__text_inner {
    text-align: left;
    font-size: 14px;
    font-family: 'ROBOTO';
    line-height: 1.4;
    color: rgb(0 0 0);
}
#brave_popup_12975__step__0 #brave_element--N3sYSN80c2sV-aMSMJ9 {
    width: 274px;
    height: 39px;
    top: 350px;
    left: 386px;
    z-index: 3;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN80c2sV-aMSMJ9 .brave_element__styler {
    font-size: 20px;
    font-family: brave_custom-Sans pro semi bold;
    font-weight: bold;
    color: rgba(255,255,255, 1);
    background-color: #000;
    line-height: 39px;
}
.brave_popup .brave_element.brave_element--button .brave_element__styler .brave_element__inner_link {
    display: block;
    color: inherit;
    width: 100%;
    text-align: center;
}
.brave_popup .brave_popup__step__overlay {
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100vh;
    background: rgba(0,0,0,.5);
}
#brave_popup_12975__step__0 .brave_popup__step__desktop .brave_popup__step__overlay {
    background-color: rgba(60,60,60, 0.7);
}
.brave_popup .brave_popup__close.brave_popup__close--inside_right {
    top: 3px!important;
    right: 16px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mng {
    width: 490px;
    height: auto;
    top: 84px;
    left: 380px;
    z-index: 1;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh {
    width: 450px;
	 width: 470px;
    height: auto;
    z-index: 2;
    top: 194px;
    left: 384px;
	top: 160px;
	top: 165px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN80c2sV-aMSMJ9 {
    width: 85px;
    height: 39px;
    top: 360px;
    left: 381px;
    z-index: 3;
}

@media screen and (min-width: 760px){
.brave_popup__step_wrap--show .brave_popup__step__desktop {
    display: block;
}
}
 #brave_element--ok
 {
    width: 475px;
    height: auto;
    top: 211px;
    left: 380px;
 }
textarea {
    width: 100%;
    height: 110px;
    padding: 12px 20px;
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 4px;
    background-color: #f8f8f8;
    font-size: 16px;
    resize: none;
}
div#brave_element-IDDD {
    width: 490px;
    height: auto;
    top: 63px;
    left: 382px;
    z-index: 1;
}
#brave_element-IDDD .brave_element__text_inner {
    text-align: left;
    font-size: 16px;
    font-family: 'Roboto' !important;
    line-height: 1.3 !important;
    font-weight: bold !important;
    color: rgba(0,0,0, 1) !important;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mng .brave_element__text_inner {
    text-align: left;
	 font-size: 30px;
    line-height: 1.3; letter-spacing: 3px;
    font-weight: 500;
    color: rgba(0,0,0, 1); font-family: 'Winnie PERSONAL USE' !important;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mng {
    width: 490px;
    height: auto;
    top: 92px;
    left: 380px;
    z-index: 1;
}
 div#brave_element--WWW {
     width: 490px;
    height: auto;
    top: 184px;
    left: 379px;
    z-index: 1;
}
.page-wrapper div#brave_element--WWW {
	    left: 379px;
}
div#brave_element--WWW .sc-hwwEjo.eoCznv {
    text-align: left;
    font-size: 36px;
    font-family: 'Roboto';
    line-height: 1.3;
    font-weight: bold;
    color: rgba(0,0,0, 1);
}
#brave_element--WWW #fundraising_dates
{
	    font-family: 'Roboto', Georgia, sans-serif;
    font-size: 17px;
}
#brave_element--WWW .eYHKCV, #brave_element--WWW .jZDald {
    font-size: 16px;
    font-family: 'Roboto';
}
#brave_element--WWW .progress.progressBar
{
	margin-top: 7px;
}
div#brave_element--WWW {
    width: 490px;
    height: auto;
    top: 236px;
    left: 378px;
    z-index: 1;
	padding-top: 12px;
}
#brave_element--N3sYSN80c2sV-aMSMJ9 .brave_element__button_text
{
	    font-family: 'Roboto';
}
#brave_element--WWW .kZLEoE {
    color: rgb(50, 50, 50);
    font-size: 17px;
    font-style: normal;
    line-height: 27px;
    margin: 0px;
    font-weight: 400;
}
.eGOSHP
{
    padding: 11px 30px;
    display: inline-block;
}
.brJQDe
 {
     height: auto;
}
.civCQf {
    background-color: transparent !important;
    padding: 0;
    margin: 0;
    
}
.jqEzoc{
    font-size: 18px;
}
.jqEzoc::before{
    display: none;
}
.jqEzoc {
    font-size: 17px;
}
@media only screen and (max-width: 990px) 
{
	#brave_popup_12975__step__0 #brave_element--N3sYSN80c2sV-aMSMJ9
	{
		    margin-left: 5px;
	}
	#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh {

    padding-left: 5px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnf
{
	    display: none;
}
div#brave_element-IDDD {
    width: 95%;
	left: 30px;
}
div#brave_element--WWW
{
	    width: 50%;   
		left: 30px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mng {
    width: 90%;
    left: 30px;

}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh
{
	 width: 95%;
 left: 30px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN80c2sV-aMSMJ9
{
	    left: 30px;
}
.brave_popup .brave_popup__step__inner .brave_popup__step__popup .brave_popup__step__content .brave_popup__step__elements
{
	width: auto;
}
#brave_popup_12975__step__0 .brave_popup__step__desktop .brave_popup__step__inner {
    width: 95%;
}

}

@media only screen and (max-width: 664px)
{
	
	.jqEzoc {
    font-size: 14px;
}
	.page-wrapper #brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnf {

	width: 170px !important;
    height: 170px !important;

}
	.page-wrapper #brave_popup_12975__step__0 .brave_popup__step__elements .brave_popup__elements_wrap{
    display:block;
}
	#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mng .brave_element__text_inner {

    font-size: 32px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mng .brave_element__text_inner {
    font-size: 26px;
}
div#brave_element-IDDD {
    width: 95%;
    left: 30px;
    order:2
}
div#brave_element--WWW {
    width: 90%;
    left: 30px;
	top: 170px;
	order: 4;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mng {
    width: 89%;
    left: 30px;
	top: 95px;
	order: 3;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh {
    width: 95%;
    left: 30px;
	    top: 81px;
	    order: 5;
}
#brave_element--WWW .eYHKCV, #brave_element--WWW .jZDald {
    font-size: 13px;
    font-family: 'Roboto';
}
.brave_popup .eYHKCV {
    padding-left: 0;
    /* display: block; */
}
#brave_popup_12975__step__0 .brave_popup__step__desktop .brave_popup__step__inner
{
	height: 380px;
}
div#brave_element--WWW
{
	    top: 130px;
}
div#brave_element--WWW {
    top: 163px;
}
div#brave_element--WWW {
    top: 187px;
	    top: 226px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh {
    width: 93%;
    left: 30px;
    top: 168px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN80c2sV-aMSMJ9 {
  
    top: 337px;
    order: 6;
}
#brave_popup_12975__step__0 .brave_popup__step__desktop .brave_popup__step__inner {
    height: 420px;
}
#brave_popup_12975__step__0 .brave_popup__step__elements .brave_popup__elements_wrap{
    display: flex;
    flex-direction: column;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnf {
    order: 1;
}
}
@media only screen and (max-width: 420px)
{
	#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mng .brave_element__text_inner {
    font-size: 23px;
}
	#brave_popup_12975__step__0 #brave_element--N3sYSN80c2sV-aMSMJ9 {
        top: 341px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh {

    top: 168px;
}
}
@media only screen and (max-width: 380px){
div#brave_element--WWW {
    width: 77%;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh {
    width: 79%;
    left: 30px;
    /* top: 81px; */
}
}
@media only screen and (max-width: 320px){
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh .brave_element__text_inner {
    font-size: 13px;
}
#brave_element--WWW .kZLEoE
{
	    font-size: 15px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh .brave_element__text_inner
{
	font-size: 14px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN80c2sV-aMSMJ9 {
    top: 353px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mng .brave_element__text_inner {
    font-size: 20px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh .brave_element__text_inner {
    font-size: 13px;
}
}
div#brave_element--WWW .sc-dqBHgY.ccbzhW
 {
    width: 100%;
    display: inline-flex;
}
div#brave_element--WWW .sc-hwwEjo.eoCznv
{
	display: flex;

}
	</style>
	<div class="brave_popup brave_popup--popup" id="brave_popup_12975" data-loaded="true" data-triggerfulfilled="true">
   <!-- <p></p> -->
   <div id="brave_popup_12975__step__0" class="brave_popup__step_wrap brave_popup__step--mobile-noContent brave_popup__step_wrap--show">
      <div class="brave_popup__step brave_popup__step__desktop  position_center closeButton_icon brave_popup__step--boxed closeButtonPosition_inside_right has_overlay has_overlay_close" data-nomobilecontent="true" data-scrollock="" data-width="940" data-height="440" data-open="true" style="z-index:99999" data-exitanimlength="0.5" data-layout="boxed" data-position="center">
         <div class="brave_popup__step__inner">
            <div class="brave_popupSections__wrap" style="">
               <div class="brave_popupMargin__wrap" tabindex="0">
                  <div class="brave_popup__step__close">
                     <div id="close_id" class="brave_popup__close brave_popup__close--inside_right brave_popup__close--icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                           <rect x="0" fill="none" width="20" height="20"></rect>
                           <g>
                              <path d="M14.95 6.46L11.41 10l3.54 3.54-1.41 1.41L10 11.42l-3.53 3.53-1.42-1.42L8.58 10 5.05 6.47l1.42-1.42L10 8.58l3.54-3.53z"></path>
                           </g>
                        </svg>
                     </div>
                  </div>
                  <div class="brave_popup__step__popup">
                     <div class="brave_popup__step__content">
                        <div class="brave_popup__step__elements">
                           <div class="brave_popup__elements_wrap ">
                              <div id="brave_element--N3sYSN7DAn8xy7o0Mnf" class="brave_element brave_element--image  left-side">
                                 <div class="brave_element__wrap">
                                    <div class="brave_element__styler">
                                       <div class="brave_element__inner">
                                          <div class="brave_element__image_inner">
										  <!--
                                             <img width="300" height="300" class="brave_element__image  brave_element_img_item skip-lazy no-lazyload" data-lazy="" src="https://devglow.wpengine.com/wp-content/uploads/2022/10/3d-cartoon-character-young-woman-call-center-customer-service-using-laptop_40876-3098.jpg" alt="">
											-->
										 <?php
												$image_url_profile=get_user_meta($u_id,"user_registration_image_url_profile",true);
												if(!empty($fund_image)) { 
												$crop_url_profile1=profile_image_resize_crop( $src=$fund_image, $w=512, $h=512, $dest = null, $override = true, $createNewIfExists = true );
												?>
												 <img width="300" height="300" class=" brave_element__image  brave_element_img_item skip-lazy no-lazyload" data-lazy="" src="<?php echo $crop_url_profile1; ?>" alt="">
												<?php }
												else if(!empty($image_url_profile)) {
													$crop_url_profile2=profile_image_resize_crop( $src=$image_url_profile, $w=512, $h=512, $dest = null, $override = true, $createNewIfExists = true);
												?>
													<img width="300" height="300" class=" brave_element__image  brave_element_img_item skip-lazy no-lazyload" data-lazy="" src="<?php echo $crop_url_profile2; ?>" alt="">
												<?php
												}
												else {	
												?>
												<div class="sc-tilXH kKkoMh rightSide">
												<div class="sc-hEsumM ctVDjL">
												   <div class="sc-ktHwxA ebvVGN"></div>
												</div>
											 </div>
			 
												<?php } ?>
										  </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
							  
							  <div class="right-side">
							  
							   <div id="brave_element-IDDD" class="brave_element brave_element--text ">
                                 <div class="brave_element__wrap ">
                                    <div class="brave_element__styler ">
                                       <div class="brave_element__inner">
                                          <div class="brave_element__text_inner">Fund ID: <?php echo "#".$fid; ?></div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                  
							  <div id="brave_element--WWW" class="brave_element brave_element--text ">
                                 <div class="brave_element__wrap ">
                                    <div class="brave_element__styler ">
                                       <div class="brave_element__inner">
									   <h3 class="sc-dfVpRl kZLEoE">Support Now</h3>
									    <div class="progress progressBar">
										<?php  $goal_amount_clean = preg_replace('/[^0-9.]/', '', $goal_amount);  $width=round($rebate_so_far3 / ($goal_amount_clean / 100),2); ?>
											<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $width."%"; ?>;"></div>
										</div>
                                         <div class="sc-dqBHgY ccbzhW">
											<div class="sc-hwwEjo eoCznv">
											   <div width="55" class="sc-kTUwUJ jZDald">$<?php echo $rebate_so_far3=number_format($rebate_so_far3, 2, '.', '')." "; ?></div>
											   <?php 
											   $goal_amount=get_user_meta( $u_id , 'user_registration_seller_goal' , true ); 
											    $goal_amount_clean = preg_replace('/[^0-9.]/', '', $goal_amount);
											   $formatted_goal_amount = number_format($goal_amount_clean, 2, '.', ''); 
											   ?>
											   <div class="sc-kTUwUJ eYHKCV xczvxc2"> / <?php if(!empty($formatted_goal_amount)) { echo "$".$formatted_goal_amount; } else { echo "0.00"; }?></div>
											</div>
											<div id="#fundraising_dates" class="sc-kTUwUJ eYHKCV"><?php  echo $newDate; ?> - <?php  echo $newDate2; ?></div>
											
										 </div>
										 
                                       </div>
                                    </div>
                                 </div>
                              </div>
							
                              <div id="brave_element--N3sYSN7DAn8xy7o0Mng" class="brave_element brave_element--text ">
                                 <div class="brave_element__wrap ">
                                    <div class="brave_element__styler ">
                                       <div class="brave_element__inner">
                                          <div class="brave_element__text_inner"><?php if(!empty($org)){ echo $org; } else{  echo "--"; } ?></div>
                                           	<div class="resone-dis">
                              
                                              	<?php 
                                              	if(isset($_GET['reason_is']) && !empty($_GET['reason_is']))
                                              	{
                                              	echo '<p>'.$_GET['reason_is'].'</p>';    
                                              	}
                                              	
                                              	?>
                                            </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                
                             
                              <div id="brave_element--N3sYSN7DAn8xy7o0Mnh" class="brave_element brave_element--text ">

                              <div class="sc-kafWEX civCQf kj small-screen-hide">
			 
             <div class="sc-feJyhm jqEzoc txt"><?php if(!empty($description) && !empty($seller_desc)){ echo $excerpt = wp_trim_words( $seller_desc, 30, ' [...]' ); } else if(!empty($description)){ echo $excerpt = wp_trim_words( $description, 30, ' [...]' ); } else if(!empty($seller_desc)){ echo $excerpt = wp_trim_words( $seller_desc, 30, ' [...]' );  } else { ?> Help us reach our goal! Thank you for the support! <?php } ?></div>
          </div>
                                 <div class="brave_element__wrap ">
                                    <div class="brave_element__styler ">
                                       <div class="brave_element__inner">
                                          <div class="brave_element__text_inner"><?php if(!empty($fund_destination)){ echo $fund_destination; } else{ "Help us reach our goal! Thank you for the support!"; } ?></div>
                                       </div>
                                    </div>
                                 </div>
                              </div>


							  <!--<div id="brave_element--ok" class="brave_element brave_element--text ">
                                 <div class="brave_element__wrap ">
                                    <div class="brave_element__styler ">
                                       <div class="brave_element__inner">
                                          <div class="brave_element__text_inner">
										  <form>
											  <textarea>Enter your text...</textarea>
											</form>
									     </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>-->
                              <div id="brave_element--N3sYSN80c2sV-aMSMJ9" class="brave_element brave_element--button  brave_element--has-click-action exit-intent-popup-button">
                                 <div class="brave_element__wrap">
                                    <div class="brave_element__inner">
                                    

                                       <div class="brave_element__styler">

                                          <a id="ok_id" class="brave_element__inner_link">
                                             <div class="brave_element__button_text zcvzxcv">OK</div>
                                          </a>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                             
							</div>
							 
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="brave_popup__step__overlay brave_popup__step__overlay--closable" onclick="brave_close_popup('12975', 0)"></div>
      </div>
     
   </div>
</div>
<?php
get_footer();
	//wp_footer();
?>