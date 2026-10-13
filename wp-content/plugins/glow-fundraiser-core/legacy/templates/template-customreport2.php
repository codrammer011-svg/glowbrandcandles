<?php 
/**
 * Template Name: New Custom Report 2
 */
	get_header();
?>
<style>
.catalogue-sale .fundraiser-order-details-col.product-quantity, .catalogue-sale .fundraiser-order-details-col.product-price1, .catalogue-sale .fundraiser-order-details-col.all-order-subtotal1, .catalogue-sale .fundraiser-order-details-col.all-order-discount1, .catalogue-sale .fundraiser-order-details-col.all-order-discount-calc {
    width: 11%;
}
</style>
<?php 
$mode=$_GET['debug'];
if($mode==1)
{
		$current_user = wp_get_current_user(); 
		$frID2 = $current_user->ID; 
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
?>
<div class="container content-page">
		<div class="page-content sidebar-position-<?php esc_attr_e( $l['sidebar'] ); ?> sidebar-mobile-<?php esc_attr_e( class_exists('WooCommerce') && ( is_checkout() || is_cart() || is_account_page() || in_array( 'woocommerce-wishlist', get_body_class() ) ) ? $l['shop-sidebar-mobile'] : $l['sidebar-mobile'] ); ?>">
			<div class="row">
                  <?php do_action( 'et_page_heading' ); ?>                  
				<div class="content <?php esc_attr_e( $l['content-class'] ); ?>">
                    <div class="intro-content h"><?php the_content(); ?></div>
                   
                    

<!-- Catalog section-->
 <?php $current_user = wp_get_current_user(); 
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
							if($ID==207710)
							{
							   $total_sale_so_far=0;
							  $rebate_so_far=0;
                         /*   $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
							*/
							$arr_orders=array(35733);
							for($e=0;$e<count($arr_orders);$e++)
							{
							$order = new WC_Order( $arr_orders[$e] );
							$ISD = $order->get_id();
                            $frID3 = get_post_meta($ISD, 'fundraiser_field', true );
							  $ID3 = get_post_meta($ISD, 'wc_seller_field', true );
								$new_catalog_order=get_post_meta( $ISD, 'new_catalog_order_50_percent',true);	
								$order_type = get_post_meta($ISD, 'wc_order_type', true );
									  if($order_type=="Catlog") 
									  {
                         /*   if ($ID3 == $IDD) { */
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
										if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order)) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale_so_far+=$subtot-$to_widthdraw; 
										}  
										else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
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
                                <div class="fr-order-col fr-order-total">Product Sales</div>
                                <div class="fr-order-col fr-order-action">Details</div>
                            </div>
							
                        <?php  $qty_sum=0;
                           /* $args = array(
                                'status' => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID
                            );

                            $orders = wc_get_orders($args);

                        foreach ( $orders as $order ) {
							*/
							$arr_orders=array(35733);
							for($e=0;$e<count($arr_orders);$e++)
							{
							$order = new WC_Order( $arr_orders[$e] );
								$ISD = $order->get_id();
							$seller_name= array();
								  $subTotal=array();
								  $sellerQtyTotal=array();
								  $item_ids=array();
								$frID3 = get_post_meta($ISD, 'fundraiser_field', true );
									$new_catalog_order=get_post_meta( $ISD, 'new_catalog_order_50_percent',true);	
									$order_type = get_post_meta($ISD, 'wc_order_type', true );
									  if($order_type=="Catlog") 
									  {								
                            if ($frID3 == $frID2) { 
                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id']; 

								if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
								  
                                if ($order_customer_id == $frID2) { 
								 $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Catlog") 
									  { 
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
											  $qty_sum+=$qty;
											if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order) ) 
											{ 
												$subtot=$order->get_subtotal(); 
												$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
												$total_sale=$subtot-$to_widthdraw; 
											}  
											else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
											{ 
												$subtot=$order->get_subtotal(); 
												$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
												$total_sale=$subtot-$to_widthdraw; 
											} 
											else
											{ 
							
											}
								
								?>
                                <div class="fundraiser-order-row itemmatch">
                                    <div class="fr-order-col fr-order-id"><?php echo $order->get_id(); ?></div>
                                    <div class="fr-order-col fr-order-date"><?php echo $order_data['date_created']->date('Y-m-d'); ?></div>

                                    <div class="fr-order-col fr-order-customer"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></div>

                                    <div class="fr-order-col fr-order-seller"> <?php 
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
									   ?></div>

                                    <div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span><span class="fr-order-amt"><?php $qty=$order_item_quantity; if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order) ) { $subtot=$order->get_subtotal(); $to_widthdraw = floatval ($subtot) * floatval (40) / 100; $dis=$subtot-$to_widthdraw; echo $dis=number_format($dis, 2, '.', ''); }  else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes") { $subtot=$order->get_subtotal(); $to_widthdraw = floatval ($subtot) * floatval (50) / 100; $dis=$subtot-$to_widthdraw; echo $dis=number_format($dis, 2, '.', ''); } else{ echo "0.00"; } ?></span></div>
                                    <div class="fr-order-col fr-order-action"><span class="details-btn" data-trigger="cat-order-<?php echo $order->get_id(); ?>-details">Expand</span></div>
                                <div id="cat-order-<?php echo $order->get_id(); ?>-details" class="fundraiser-order-details-table">
                                   <!-- <div class="fundraiser-order-details-row fr-header">
                                        <div class="fundraiser-order-details-col product-name">Product</div>
                                        <div class="fundraiser-order-details-col product-quantity">Quantity</div>
                                        <div class="fundraiser-order-details-col product-seller">Seller</div>
                                        <div class="fundraiser-order-details-col product-buyer">Buyer</div>
                                        <div class="fundraiser-order-details-col product-price">Total</div>
                                    </div>
									 <?php $order_item_quantity = 0; ?>
                                    <?php foreach ($order->get_items() as $item_key => $item_values): 
                                         $item_id   = $item_values->get_id();
                                         $item_data = $item_values->get_data(); 
											$order_item_quantity += $item_data['quantity']; 

										 ?>
                                    <div class="fundraiser-order-details-row fr-body line-item-match">
                                        <div class="fundraiser-order-details-col product-name"><?php echo $item_data['name']; ?></div>
                                        <div class="fundraiser-order-details-col product-quantity"><?php echo $item_data['quantity']; ?>
                                        </div>
                                        <div class="fundraiser-order-details-col product-seller"><?php echo wc_get_order_item_meta($item_id, "_wdm_seller_display", true);  ?></div>
                                        <div class="fundraiser-order-details-col product-seller"><?php echo wc_get_order_item_meta($item_id, "_wdm_buyer_name", true); ?></div>
                                        <div class="fundraiser-order-details-col product-price show">$<span><?php echo $item_t=number_format( $item_data['total'], 2); ?></span></div>
                                    </div>                                    
                                    <?php endforeach; ?>
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
									 </div>-->
									<div class="fundraiser-order-details-row fr-header">
                                        <div class="fundraiser-order-details-col product-name">Seller Name</div>
                                        <div class="fundraiser-order-details-col product-quantity">Product Quantity</div>
                                        <div class="fundraiser-order-details-col product-seller">Total Sales</div>
                                        <div class="fundraiser-order-details-col product-buyer">Glow</div>
                                        <div class="fundraiser-order-details-col product-price">Profit</div>
									</div>
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
								<div class="fundraiser-order-details-row fr-body line-item-match">
                                        <div class="fundraiser-order-details-col product-name"><?php echo $seller_name[$i]  ?></div>
                                        <div class="fundraiser-order-details-col product-quantity"><?php echo $sellerQtyTotal[$i]; ?>
                                        </div>
                                        <div class="fundraiser-order-details-col product-seller"><?php $subtott=$subTotal[$i]; echo '$'.$subtott=number_format( $subtott, 2); ?></div>
                                        <div class="fundraiser-order-details-col  product-seller"><?php $qty=$order_item_quantity; if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order)) { $subtot=$subTotal[$i]; $to_widthdraw = floatval ($subtot) * floatval (40) / 100; $dis=$subtot-$to_widthdraw; echo '$'.$dis=number_format( $dis, 2); }  else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes") { $subtot=$subTotal[$i]; $to_widthdraw = floatval ($subtot) * floatval (50) / 100;  $dis=$subtot-$to_widthdraw; echo '$'.$dis=number_format( $dis, 2);} else{ echo "0.00"; } ?></div>
										<div class="fundraiser-order-details-col product-price show"><span><?php $qty=$order_item_quantity; if($qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order)) { $subtot=$subTotal[$i];  $to_widthdraw = (40 / 100) * $subtot; echo '$'.$to_widthdraw=number_format( $to_widthdraw, 2); }  else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes") { $subtot=$subTotal[$i];  $to_widthdraw = (50 / 100) * $subtot; echo '$'.$to_widthdraw=number_format( $to_widthdraw, 2); } else{ echo "0.00"; } ?> </span></div>
                                        
                                </div>   
								<?php } } ?>
								
                                </div>
                                </div>
								<?php } } }
							}								
                        }
                        }
                        ?>
                         <input type="hidden" value="<?php echo  $qty_sum; ?>">   
                        </div>
                    </div>
							<?php } ?>
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
    <div class="manual-mass-order-popup">
        <div class="manual-mass-order-popup-wrapper">
            <input type="text" name="another_email" id="another_email" placeholder="Email">
             <a  class="fr-btn send_report_other" data-fund_id="<?php echo $frID2; ?>">Send</a>
			 <a href="#" id="m-txt-link" class="txt-link">Cancel</a>
        </div>
    </div>
	<style>
		.manual-mass-order-popup {
		position: fixed;
		width: 100%;
		height: 100%;
		z-index: 999;
		background: rgba(0,0,0,0.6);
		top: 0;
		left: 0;
		display: none;
	}
	.manual-mass-order-popup .manual-mass-order-popup-wrapper {
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
.manual-mass-order-popup a.fr-btn {
    display: block;
    margin: 20px auto;
    width: 100%;
    max-width: 250px;
}
.send_report_another
{
	margin-left: 10px;
}
#another_email
{
	width: 100%;
	</style>
	<script>
		jQuery(document).ready(function () {
		
			jQuery(document).on('click', '.send_report_another', function (e) {
			e.preventDefault();
			jQuery(".manual-mass-order-popup").addClass("show");
	  });
	  	jQuery(document).on('click', '#m-txt-link', function (e) {
			e.preventDefault();
			jQuery(".manual-mass-order-popup").removeClass("show");
	  });
	  var ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>";
	  jQuery('.send_report').click(function(){

let fund_id = jQuery(this).data('fund_id');

         jQuery.ajax({

         type : "post",

         url : ajaxurl,

         data : {action: 'custom_fund_report_mail', fund_id:fund_id},

         success: function(response) {

        console.log(response);
		jQuery(".admin-report").append('<p style="padding-top:10px;color:green;">'+response+'</p>');
    }
});

});
jQuery('.send_report_other').click(function(){
let fund_id = jQuery(this).data('fund_id');

let a_email = jQuery("#another_email").val();
jQuery(this).text('Please Wait...');

         jQuery.ajax({

         type : "post",

         url : ajaxurl,

         data : {action: 'custom_fund_report_another_mail', a_email:a_email, fund_id:fund_id},

         success: function(response) {

        console.log(response);
		jQuery('.send_report_other').text('Send');
		jQuery(".manual-mass-order-popup").removeClass("show");
		jQuery(".admin-report").append('<p style="padding-top:10px;color:green;">'+response+'</p>');
    }
})
});
		});
</script>
<?php } ?>
<?php
	get_footer();
?>
