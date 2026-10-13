<?php
/**
 * Template Name: Print Order By Seller
 */
 if ( is_user_logged_in() ) {
	$user = wp_get_current_user();
	if ( in_array( 'administrator', (array) $user->roles ) ) 
	{
	$order_id=$_GET['order_id'];
 
 	global $woocommerce;
	$id=$order_id;
	$order = new WC_Order( $id );
	if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
	$items = $order->get_items();
	$cat_type=get_post_meta($id, 'wc_order_type', true);
	?>
	<html>
	<head>
	
	<title> Print Order </title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
	@media print 
{
   @page
   {
    size: 4in 8in ;
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
		font-size: 18px;
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
	#print_button
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
	</style>
	</head>
	<body style="background:#f2f2f2">
	<span id="print_button">Print Out</span>
	<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;width:100%; width: 384px;margin: 0 auto;">

		<div id="printTable" style=" margin:0 auto; background:#fff;padding:25px;font-size:14px; "> 
		<style>
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
	.edit{
		width: 85%;
    display: inline-block;
    padding: 8px 9px;
	}
		</style>
			<!--<h2><?php
			/* $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if($slrID=="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}*/
			
			?></td></h2>
			<span class="border-line"></span>-->
<?php 
$query_val_c=$_GET['q'];
if($cat_type=='Catlog' && $query_val_c != 'full' & $query_val_c != 'summary') {
	
	$seller_name= array();
	foreach ( $items as $item_id => $item ) :
	$s_name=$item['_wdm_seller_name'];
	//$s_name=wc_get_order_item_meta( $item_id, '_wdm_seller_name', true );
	if(!in_array($s_name,$seller_name))
	{
		$seller_name[]=$s_name;
	}
	endforeach; 
	
	$itmCount=array();
	for($i=0;$i<count($seller_name);$i++) {
		$itemCount=0;
	foreach ( $items as $item_id => $item ) :
	
	$s_name=$item['_wdm_seller_name'];
	//$s_name=wc_get_order_item_meta( $item_id, '_wdm_seller_name', true );
	if($seller_name[$i]==$s_name)
	{
		$itemCount++;
	}
	endforeach; 
	$itmCount[]=$itemCount;
	}
	
	/*for($i=0;$i<count($seller_name);$i++) {*/
		$query_val=$_GET['q'];
		if(empty($query_val))
		{
			$query_val=0;
		}
		$count=0;
	
		?>
		
		<h2>
		<?php  echo $seller_name[$query_val]; ?> - <?php echo $id; ?>
		</h2>
		<div class="printPage" data-print="">
		
			<span class="border-line"></span>
		<?php
foreach ( $items as $item_id => $item ) :
$s_name_meta=$item['_wdm_seller_name'];
//$s_name=wc_get_order_item_meta( $item_id, '_wdm_seller_name', true );
if($seller_name[$query_val]==$s_name_meta)
{
 
	$product       = $item->get_product();
	$sku           = '';
	$purchase_note = '';
	$image         = '';
	echo '<p>'.wp_kses_post( apply_filters( 'woocommerce_email_order_item_quantity', $item->get_quantity(), $item ) ).' '. wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) ).'</p>'; 
	$count+=$item->get_quantity();
} ?>
	<?php endforeach; ?>
	<span class="border-line border-line-2"></span>
	<h4>Total Items Sold: <?php echo $count; ?></h4>
	<span class="border-line border-line-2"></span>
	</div>
		<?php   } ?>
	<?php	if($cat_type=='Catlog' && $query_val_c == 'full') {
	
	$seller_name= array();
	foreach ( $items as $item_id => $item ) :
	$s_name=$item['_wdm_seller_name'];
	//$s_name=wc_get_order_item_meta( $item_id, '_wdm_seller_name', true );
	if(!in_array($s_name,$seller_name))
	{
		$seller_name[]=$s_name;
	}
	endforeach; 
	$itmCount=array();
	for($i=0;$i<count($seller_name);$i++) {
		$itemCount=0;
	foreach ( $items as $item_id => $item ) :
	
	$s_name=$item['_wdm_seller_name'];
	//$s_name=wc_get_order_item_meta( $item_id, '_wdm_seller_name', true );
	if($seller_name[$i]==$s_name)
	{
		$itemCount++;
	}
	endforeach; 
	$itmCount[]=$itemCount;
	}
	for($i=0;$i<count($seller_name);$i++) {
		/*$query_val=$_GET['q'];
		if(empty($query_val))
		{
			$query_val=0;
		}*/
		$count=0;

		?>
		
		<?php
		$countItem=0;
		$pageCount=0;
foreach ( $items as $item_id => $item ) :
$s_name_meta=$item['_wdm_seller_name'];
//$s_name=wc_get_order_item_meta( $item_id, '_wdm_seller_name', true );

if($seller_name[$i]==$s_name_meta)
{
	$countItem++;
	
	if($countItem==1 ||$countItem == 9 || $countItem==17 || $countItem==25 || $countItem==33 || $countItem==41 || $countItem==49 || $countItem==57 || $countItem==65 || $countItem==73 || $countItem==81 || $countItem==89 || $countItem==97 || $countItem==105 || $countItem==113 || $countItem==121 || $countItem==129 || $countItem==137 || $countItem==145 || $countItem==153 || $countItem==161 || $countItem==169 || $countItem==177 || $countItem==185 || $countItem==192 || $countItem==199){
		$pageCount++;
	?>
 <div class="printPage <?php echo $pageCount; ?>" style="height:510px;">
 <?php if($pageCount==1) { ?>
 <div class="SellerName">
		<h2>
		<?php  echo $seller_name[$i]; ?> - <?php echo $id; ?>
		</h2>
			<span class="border-line"></span>
			</div>
 <?php } ?>
 <?php if($pageCount>1) { ?>
	<div class="SellerName">
		<h2>
		<?php  echo $seller_name[$i]; ?> - <?php echo $id; ?> (Page <?php echo $pageCount; ?>)
		</h2>
			<span class="border-line"></span>
			</div>
	<?php } ?>
	<?php } ?>
	
<?php	$product       = $item->get_product();
	$sku           = '';
	$purchase_note = '';
	$image         = '';
	echo '<p class="productName"><span class="pName"> <span class="p-name">'.wp_kses_post( apply_filters( 'woocommerce_email_order_item_quantity', $item->get_quantity(), $item ) ).' '. wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) ).'</span> <i class="fa fa-edit"></i></span> <span class="editName"><input type="text" class="edit"> <i class="fa fa-check"></i></span></p>'; 
	$count+=$item->get_quantity();
	if(($countItem < $itmCount[$i] && $countItem==8) || ($countItem < $itmCount[$i] && $countItem==16 ) || ($countItem < $itmCount[$i] && $countItem==24) || ($countItem < $itmCount[$i] && $countItem==32) || ($countItem < $itmCount[$i] && $countItem==40) || ($countItem < $itmCount[$i] && $countItem==48) || ($countItem < $itmCount[$i] && $countItem==56) || ($countItem < $itmCount[$i] && $countItem==64) || ($countItem < $itmCount[$i] && $countItem==72) || ($countItem < $itmCount[$i] && $countItem==80) || ($countItem < $itmCount[$i] && $countItem==88) || ($countItem < $itmCount[$i] && $countItem==96) || ($countItem < $itmCount[$i] && $countItem==104) || ($countItem < $itmCount[$i] && $countItem==112) || ($countItem < $itmCount[$i] && $countItem==120) || ($countItem < $itmCount[$i] && $countItem==128) || ($countItem < $itmCount[$i] && $countItem==136) || ($countItem < $itmCount[$i] && $countItem==144) || ($countItem < $itmCount[$i] && $countItem==152))
	{
	?>
	<p class="hello" style="display:inline-block;width:100%"> </p>
	<p style="display:inline-block;width:100%"> </p>
	<p style="display:inline-block;width:100%"> </p>
	<p style="display:inline-block;width:100%"> </p>
	<p style="display:inline-block;width:100%"> </p>
	<p style="display:inline-block;width:100%"> </p>
	</div>
<?php }


 } ?>



	<?php endforeach; ?>
	<?php if($itmCount[$i]==8 || $itmCount[$i]==16 || $itmCount[$i]== 24 || $itmCount[$i]== 32 || $itmCount[$i]== 40 || $itmCount[$i]== 48 || $itmCount[$i]== 56 || $itmCount[$i]== 64  || $itmCount[$i]== 72 || $itmCount[$i]== 80 || $itmCount[$i]== 88 || $itmCount[$i]== 96 || $itmCount[$i]== 104 || $itmCount[$i]== 112 || $itmCount[$i]== 120 || $itmCount[$i]== 128 || $itmCount[$i]== 136 || $itmCount[$i]== 144 || $itmCount[$i]== 152 ) { ?>
	<!--<div class="printPage <?php echo $pageCount; ?>" style="height:540px;">-->
	
	<?php if($countItem==$itmCount[$i]) { ?>
	<span class="border-line border-line-2"></span>
	<h4>Total Items Sold: <?php echo $count; ?></h4>
	<span class="border-line border-line-2"></span>
	<p class="break1" style="display:inline-block;width:100%"> </p>
	<p style="display:inline-block;width:100%"> </p>
	<p style="display:inline-block;width:100%"> </p>
	</div>
	<?php }


	} 
	else { ?>
	<?php if($countItem==$itmCount[$i]) { ?>
	<span class="border-line border-line-2"></span>
	<h4>Total Items Sold: <?php echo $count; ?></h4>
	<span class="border-line border-line-2"></span>
		<p class="break2" style="display:inline-block;width:100%"> </p>
	<p style="display:inline-block;width:100%"> </p>
	<p style="display:inline-block;width:100%"> </p>
	<p style="display:inline-block;width:100%"> </p>
	<p style="display:inline-block;width:100%"> </p>
	<p style="display:inline-block;width:100%"> </p>
	</div>
	<?php }
	 } ?>
	<?php }  } ?>
	
	<?php if($cat_type=='Catlog' && $query_val_c == 'summary') { ?>
	<div class="orderSummary">
	<div class="SellerName">
		<h2>
		Order Summary- <?php echo $id; ?>	
		</h2>
		<span class="border-line"></span>
		<?php 
		$productName=array();
		$productID=array();
		foreach ( $items as $item_id => $item ) : 
		$product       = $item->get_name(); 
		$product_id       = $item->get_product_id(); 
		if(!in_array($product,$productName)) 
		{
			$productName[]=$product;
			$productID[]=$product_id;
		}
		
		endforeach; 
		$productQty=array();
		
		for($i=0;$i<count($productName);$i++) 
		{
			$qtyProduct=0;
			foreach ( $items as $item_id => $item ) : 
			$product       = $item->get_name();
			$pQty=$item->get_quantity();			
			if($product ==$productName[$i])
			{
				$qtyProduct+=$pQty;
			}
			endforeach; 
			$productQty[]=$qtyProduct;
		}
		/*hax category */
		$h_sort_p=array();
		$h_ids= array(22433,22413,22425,22426,22439,22445,22447,22449,22440,22450,24386,22419,22432,22423,22417,22455,23058,22458,23061,22460,23064,22457,22478,24387,22459,23073,24388,23077,22468,23080,23082,23084,23085,23086,23090,24389,24390,24391,24392,24393);
		for($j=0;$j<count($h_ids);$j++)
		{
			if(in_array($h_ids[$j],$productID))
			{
				$h_sort_p[]=$h_ids[$j];
			}
		}
		//print_r($h_sort_p);
		for($i=0;$i<count($productName);$i++) {
			$total+= $productQty[$i];
		?>
		<p data-id="<?php echo $productID[$i]; ?>"><?php echo $productQty[$i].' X '.$productName[$i]; ?></p>
		<?php } ?>
		<span class="border-line border-line-2"></span>
	<h4>Total Items Sold: <?php echo $total; ?></h4>
	<span class="border-line border-line-2"></span>
		<p class="break2" style="display:inline-block;width:100%"> </p>
	</div>
			
	
	</div>
	<?php } ?>

</div>
	</div>
	
	<div class="totalSeller">
	<span>Total Sellers: <?php echo count($seller_name); ?> </span>
	</div>
	<div class="sellerPagination">
	<span class="btnSeller"><a href="https://glowbrandscandles.com/blank-pages/?order_id=<?php echo $id; ?>&q=blank">Blank Pages</a></span>
	<span class="btnSeller"><a href="https://glowbrandscandles.com/print-order/?order_id=<?php echo $id; ?>&q=summary"> Order Summary</a></span>
	<span class="btnSeller"><a href="https://glowbrandscandles.com/print-order/?order_id=<?php echo $id; ?>&q=full"> All Sellers</a></span>
	<?php for($i=0;$i<count($seller_name);$i++) { 
	$c=$i;
	$d=$c+1;
	?>
	<span class="btnSeller"><a href="https://glowbrandscandles.com/print-order/?order_id=<?php echo $id; ?>&q=<?php echo $i; ?>">Seller <?php echo $d; ?></a></span>
	<?php } ?>
	</div>
	<script type='text/javascript' src='https://glowbrandscandles.com/wp-includes/js/jquery/jquery.js' id='jquery-core-js'></script>
	<script>
	function printData()
{
   var divToPrint=document.getElementById("printTable");
   newWin= window.open("");
   newWin.document.write(divToPrint.innerHTML);
   newWin.print();
   newWin.close();
}

jQuery('#print_button').on('click',function(){
	
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
});




	</script>
	</body>
	</html>


<?php } else { ?>
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
			
			?></td></h2>
			<span class="border-line"></span>
			<span>Pending payment or Trash Order.</span>
	<span class="border-line border-line-2"></span>
	<h4>Total Items Sold: <?php echo "0"; ?></h4>
	<span class="border-line border-line-2"></span>
	</div>
	</div>
	</body>
	</html>
	<?php } 
	
	}
 }
	else
	{
		$url=home_url("/my-account/");
		wp_redirect($url);
	}
	
	?>