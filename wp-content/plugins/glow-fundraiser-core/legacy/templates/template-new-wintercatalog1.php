<?php
/**
 * Template Name: New Winter Catalogue Design sachin
 */
 get_header('new');
  if(!empty( $_GET['seller-name'] ) )
 {
 $nameSeller=$_GET['seller-name'];
 
?>
<script>					   
jQuery(document).ready(function(){
	var nSeller="<?php echo $nameSeller; ?>";
	jQuery("#DataTables_Table_0_filter #nSeller").val(nSeller);
	jQuery(".mass-seller-name-new").val(nSeller);
});
</script>
<?php } 
 if ( is_user_logged_in() ) {
  $current_user = wp_get_current_user();
   $u_id = $current_user->ID;
   $ajax_cookies_val=get_user_meta( $u_id,"skip_popup",true);
 }
?>

<style>

.page-content{
	width:740px;
}

@media screen and (min-device-width: 650px) and (max-device-width: 950px){
				.admin-bar .header.main-header {
						top: 55px !important;
					}
				#menu {
					height: 162px !important;
					top: 60px !important;
				}
				.add-to-cart-Btn {
						top: 102px !important;
						right: 17px !important;
					}
				.page-content {
						margin-top: 0px !important;
							width:725px !important;
					}
				#section0{
					margin-top: 120px !important;
				}
				#box-glowscandal {
					box-sizing: border-box;
					background-color: #04AA6D;
					width: 150px !important;
					color: #fff;
					padding: 10px 15px 10px 15px;
					line-height: 19px;
					height: 6vh !important;
					border-radius: 5px;
					position: fixed;
					right: 33px;
					top: 151px;
					z-index: 100;
					box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.4);
				}
			}
			
@media screen and (min-device-width: 300px) and (max-device-width: 650px){
		.add-to-cart-Btn {
				top: 172px !important;
				right: 62px !important;
				z-index: 100 !important;
			}
		.admin-bar .add-to-cart-Btn, .admin-bar #menu {
				height: 150px !important;		
			}
		#menu {
				padding-left: 1% !important;
				height: 230px !important;
				top: 72px !important;
			}
		#menu li {
				font-size:12px !important;
			}
		.page-content {
				margin-top: 0px !important;
				width:100%;
			}
		.admin-bar .header.main-header {
				top: 0px !important;
			}
		.admin-bar  #menu {
				top: 72px !important;
			}
		 table#DataTables_Table_0 tbody .card-image {
			   height: 70px;
			}
		.input-text.qty{
				height: 30px;
				width: 90%;
			}
		
		#box-glowscandal {
			box-sizing: border-box;
			background-color: #04AA6D;
			width: 160px !important;
			color: #fff;
			padding: 10px 15px 10px 15px;
			height: 7vh !important;
			border-radius: 5px;
			position: fixed;
			right: 75px;
			top: 221px;
			z-index: 100;
			box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.4);
		}
	}


table#DataTables_Table_0 tbody {
    display: revert !important;
	font-family: Arial, Helvetica, sans-serif;
	border-collapse: collapse;
	width: 100%;
}
.customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

.customers td, #customers th {
  border: 1px solid #ddd;
}

.customers tr:nth-child(even){background-color: #f2f2f2;}

.customers tr:hover {background-color: #ddd;}

.customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}
#menu{
	padding-left: 3%; 
    height: 45px;
	background-color: #ddc49e;
}
.header.main-header{
	background-color:white;
	    padding-bottom: 13px;
}
.page-content{
	//margin-top: 0px !important;
}
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type=number] {
    -moz-appearance: textfield;
}

#section1{
	 border-top: 0px solid #333;
}
#section0{
			margin-top: 100px !important;
			 border-top: 1px solid #333 !important;
		}
#section5 .page-content{
	margin-bottom: 0px;
}

</style>



	<?php 
	
			$summer_category = array(
									
									"signature_wick" 	=> "Signature 2 Wick Candles",
									"celebrate_the_moments" 			=> 		"Celebrate The Moment",
									"classic_candles" 	=> "Classic Candles",
									"large_wax_melts" 	=> "Large Wax Melts",
									"room-spray" 		=> "Room Sprays",
									"essential-oils" 	=> "Essential Oils",
									
								);
	
	
	
	?>
	<div class="main-header" >
        <ul class="lists" id="menu">
			<?php
				foreach($summer_category as $sum_cat => $cat){
					echo '<li class="list-items"><a href="#'.$sum_cat.'" aria-label="" class="bookmark">'.$cat.'</a></li>';
				}
			?>
        </ul>
    </div>
	<?php
	$url_seller_name = htmlspecialchars(isset($_GET['seller-name']) ? (string)$_GET['seller-name'] : '');
	?>
	
	<!-- 18 oz 2-Wick Signature Candles-->
	<p id='signature_wick' style='padding-top: 50px;'></p>
	<div class="section section-wrap light-green-bg-section " id="section1">
		<div class="slide" id="slide1">
		<?php
			$product_args = array(
									'post_status' 	=> 'publish',
									'limit' 		=> -1,
									'category' 		=> '2-wick-signature-candles', 
									'order'      	=>  'ASC'
								);
			$products = wc_get_products($product_args);
			$item_no = array("SC200","SC201","SC202","SC203","SC204","SC205","SC206","SC207","SC208","SC209","SC210","SC211","SC212","SC213","SC214","SC215");

			usort($products, function($a, $b) use ($item_no) {
				$sku_a = $a->get_sku();
				$sku_b = $b->get_sku();
				return array_search($sku_a, $item_no) - array_search($sku_b, $item_no);
			});
		?>
			<div class="container page-content sidebar-position-without sidebar-mobile-bottom">
				<div class="">
					<div class=""> 
						<div id="<?php //echo $category;?>">
							<table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe customers summerTable" role="grid" aria-describedby="DataTables_Table_0_info">
								<tbody style="width: 1140px; display: revert !important;">
								<thead>
								<p align='center' style='margin-bottom:-20px;'>Signature 2 Wick Candles $25.00</p>
								</thead>
								<tr>
									<th style="width: 80px;">Image</th>
									<th style="width: 310px;">Name</th>
									<th style="width: 100px;">SKU</th>
									<th class="summer_cart">Add To cart</th>
									<th style="width: 100px;">Quantity</th>
									<th style="width: 100px;">Price</th>
								</tr>
								<?php 
								$exclude_skus = array("SC270","SC277","SC276","SC275","SC274","SC273","SC271","SC33", "SC272", "SC24","SC25","SC23","SC22","SC20","SC28","SC30","SC-TP","SC31");
								foreach( $products as $product ) {
										$item_no =  $product->get_sku();
										// Skip the product if its SKU is in the excluded list
										if (in_array($item_no, $exclude_skus)) {
											continue; // Skip this iteration and go to the next product
										}								
										$postid  =   $product->get_id();
										$price   =   $product->get_regular_price();
										
										//echo $product->get_image();
										$image = wp_get_attachment_image_src( get_post_thumbnail_id( $postid  ), 'thumbnail' );
								?>
									<tr role="" class="odd" data-product-name="<?php  echo $product->get_name(); ?>">
									<td  class="sorting_1">
										<?php 
										
											if ( !empty( $image) )  { ?>    
												<img src="<?php  echo $image[0]; ?>" alt="" class="card-image"  /> 
											<?php 
											 
											} else 	{ ?>
												<img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" alt="" class="card-image"  />
											<?php } ?>
									</td>
									<td>
										<?php echo strip_tags($product->get_name()); ?>
									</td>
									<td><span class="item_no"><?php echo $item_no; ?></span></td>
										<td  class="summer_cart">
											<input  id="selectbox" name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;" aria-label="add">
										</td>
										<td>
											<input id="qtybox"  type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric" aria-label="quantity">
										</td>
										<td>$<span class="summer_price"><?php echo $price; ?></span></td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Celebrate The Movement-->
	
	<p id='celebrate_the_moments' style='margin-top:-77px'></p> 
	<div class="section section-wrap" id="section5">
	 	<div class="slide" id="slide1">
			<?php 
				$product_args = array(
					'post_status' 	=> 'publish',
					'limit' 		=> -1,
					'category' 		=> 'celebrate-the-movement', 
					'order'      	=>  'ASC'
				);
				$products = wc_get_products($product_args);
				$item_no = array("CTM217","CTM218","CTM219","CTM220","CTM221","CTM222","CTM223","CTM224","CTM225","CTM226");

				usort($products, function($a, $b) use ($item_no) {
					$sku_a = $a->get_sku();
					$sku_b = $b->get_sku();
					return array_search($sku_a, $item_no) - array_search($sku_b, $item_no);
				});
			?>
		 	<div class="container page-content sidebar-position-without sidebar-mobile-bottom cst_margin">
				<div class="">
					<div class=""> 
						<div id="<?php //echo $category;?>">
							<table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe customers summerTable" role="grid" aria-describedby="DataTables_Table_0_info" >
								<tbody style="width: 1140px; display: revert !important;">
								<thead>
								<p align='center' style='margin-bottom:-20px;'>Celebrate the Moment $25.00</p>
								</thead>
								<tr>
									<th style="width: 80px;">Image</th>
									<th style="width: 310px;">Name</th>
									<th style="width: 100px;">SKU</th>
									<th class="summer_cart">Add To cart</th>
									<th style="width: 100px;">Quantity</th>
									<th style="width: 100px;">Price</th>
								</tr>
								<?php 
								$exclude_skus = array('SKU-S-BM93','SKU-S-BM94','SKU-S-BM95','SKU-S-BM96','SKU-S-BM95-1','SKU-S-WOS','SKU-S-BB','SKU-S-DFJ','SKU-S-B','SKU-S-WC','SKU-S-VIP','SKU-S-BBabe','SKU-S-CBB','SKU-S-PG','SKU-S-SB','SKU-S-NP','SKU-S-HDP','SKU-S-BS','SKU-S-WB','SKU-S-WB-2', 'SC32-1', 'SC-TP','CM801','CM802','CM803','CM804');
								foreach( $products as $product ) {
										$item_no =  $product->get_sku();
										// Skip the product if its SKU is in the excluded list
										if (in_array($item_no, $exclude_skus)) {
											continue; // Skip this iteration and go to the next product
										}								
										$postid  =   $product->get_id();
										$price   =   $product->get_regular_price();
										
										// echo $product->get_image();
										$image = wp_get_attachment_image_src( get_post_thumbnail_id( $postid  ), 'thumbnail' );
								?>
									<tr role="" class="odd" data-product-name="<?php  echo $product->get_name(); ?>">
									<td  class="sorting_1">
										<?php 
										
											if ( !empty( $image) )  { ?>    
												<img src="<?php  echo $image[0]; ?>" alt="" class="card-image"  /> 
											<?php 
											 
											} else 	{ ?>
												<img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" alt="" class="card-image"  />
											<?php } ?>
									</td>
									<td>
										<?php echo strip_tags($product->get_name()); ?>
									</td>
									<td><span class="item_no"><?php echo $item_no; ?></span></td>
										<td class="summer_cart">
											<input  id="selectbox" name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;" aria-label="add">
										</td>
										<td>
											<input id="qtybox"  type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric" aria-label="quantity">
										</td>
										<td>$<span class="summer_price"><?php echo $price; ?></span></td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
		
	<!-- Celebrate The Movement-->
	
	<div id="DataTables_Table_0_filter" class="dataTables_filter add-to-cart-Btn"><input id="nSeller" type="text" name="mass-seller-name-new-test" value="<?php echo $url_seller_name ?>" style=" position: absolute;top: 0;left: -150px;" class="mass-seller-name-new-test" placeholder="Seller Name" aria-label="mass-seller-name-new-test"></div>
</div>
<script type='text/javascript' src='<?php echo esc_url( glow_dynamic_url_base('content') ); ?>plugins/quick-orders-for-woocommerce/assets/public/datatables/datatables.min.js'></script>
<div id="fullpage">


	<!--<p id='classic_candles'  style='margin-top:-77px'></p> -->
	<div class="section" id="section0" >
        <div class="slide" id="slide1">
			<?php 
				$product_args = array(
					'post_status' 	=> 'publish',
					'limit' 		=> -1,
					'category' 		=> 'classic-candles',
					'order'      	=>  'ASC'

				);
				$products = wc_get_products($product_args);
				$item_no = array("CC227","CC228","CC229","CC230","CC231","CC232","CC233","CC234","CC235","CC236","CC237","CC238","CC239","CC240","CC241","CC242");

				usort($products, function($a, $b) use ($item_no) {
					$sku_a = $a->get_sku();
					$sku_b = $b->get_sku();
					return array_search($sku_a, $item_no) - array_search($sku_b, $item_no);
				});
			?>
			<div class="container page-content sidebar-position-without sidebar-mobile-bottom" >
				<div class="row">
					<div class="content col-md-12">	
						<div id="<?php //echo $category;?>classic_candles">
							<table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe customers summerTable" role="grid" aria-describedby="DataTables_Table_0_info">
								<tbody style="width: 1140px; display: revert !important;">
								<thead>
								
								<p align='center' style='margin-bottom:-20px;'>Classic Candles $15.00 </p>
								</thead>
								<tr>
									<th style="width: 80px;">Image</th>
									<th style="width: 310px;">Name</th>
									<th style="width: 100px;">SKU</th>
									<th class="summer_cart">Add To cart</th>
									<th style="width: 100px;">Quantity</th>
									<th style="width: 100px;">Price</th>
								</tr>
								<?php 
								$exclude_skus = array("CC3","CC5","CC6","CC8","CC11","CC13","CC14","CC15", "CC106-1", "CC106-2", "CCHB","CC100","CC104","CC106","CC107","CC105","CC103","CC101","CC16","CC102","CC7");
								foreach( $products as $product ) {
										$item_no =  $product->get_sku();
										// Skip the product if its SKU is in the excluded list
										if (in_array($item_no, $exclude_skus)) {
											continue; // Skip this iteration and go to the next product
										}								
										$postid  =   $product->get_id();
										$price   =   $product->get_regular_price();
										
										//echo $product->get_image();
										$image = wp_get_attachment_image_src( get_post_thumbnail_id( $postid  ), 'thumbnail' );
			
								
								?>
									<tr role="" class="odd" data-product-name="<?php  echo $product->get_name(); ?>">
										<td  class="sorting_1">
											<?php 
											
												if ( !empty( $image) )  { ?>    
													<img src="<?php  echo $image[0]; ?>" alt="" class="card-image"  /> 
												<?php 
												
												} else 	{ ?>
													<img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" alt="" class="card-image"  />
												<?php } ?>
										</td>
										<td>
											<?php echo strip_tags($product->get_name()); ?>
										</td>
										<td><span class="item_no"><?php echo $item_no; ?></span></td>
										<td  class="summer_cart">
											<input  id="selectbox" name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;" aria-label="add">
										</td>
										<td>
											<input id="qtybox"  type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric" aria-label="quantity">
										</td>
										<td>$<span class="summer_price"><?php echo $price; ?></span></td>
									</tr>
								<?php }  ?>	
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<style>
		.summer_cart {
			
			display:none;
		}
	
	</style>
	<!-- End Classic Candles Catagory Product-->
	
	
		
		
	<!-- Large Wax Melts Catagory Product-->
	<p id='large_wax_melts' style='margin-top:-77px'></p>
	<div class="section section-wrap" id="section2">
        <div class="slide" id="slide1">
			<?php 
				$product_args = array(
										'post_status' 	=> 'publish',
										'limit' 		=> -1,
										'category' 		=> 'large-wax-melts-6-cubes', 
										'order'      	=>  'ASC'
									);
				$products = wc_get_products($product_args);
				$item_no = array("LM243","LM244","LM245","LM246","LM247","LM248","LM249","LM250","LM251","LM252","LM253","LM254","LM255","LM256","LM257","LM258");

				usort($products, function($a, $b) use ($item_no) {
					$sku_a = $a->get_sku();
					$sku_b = $b->get_sku();
					return array_search($sku_a, $item_no) - array_search($sku_b, $item_no);
				});
			?>
			
			<div class="container page-content sidebar-position-without sidebar-mobile-bottom">
				<div class="">
					<div class=""> 
						<div id="<?php //echo $category;?>">
							<table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe customers summerTable" role="grid" aria-describedby="DataTables_Table_0_info" >
								<tbody style="width: 1140px; display: revert !important;">
								<thead>
								<p align='center' style='margin-bottom:-20px;'>LARGE WAX MELTS $15.00</p>
								</thead>
								<tr>
									<th style="width: 80px;">Image</th>
									<th style="width: 310px;">Name</th>
									<th style="width: 100px;">SKU</th>
									<th class="summer_cart">Add To cart</th>
									<th style="width: 100px;">Quantity</th>
									<th style="width: 100px;">Price</th>
								</tr>
								<?php 
								$exclude_skus = array('LM37','LM39','LM40','LM42','LM45','LM47','LM48','LM49','SC18', 'SC19', 'CC3', 'SC32-1', 'SC-TP','LM447','LM446','LM445','LM444','LM443','LM442','LM441','LM50','LM440','LM41');
								foreach( $products as $product ) {
										$item_no =  $product->get_sku();
										// Skip the product if its SKU is in the excluded list
										if (in_array($item_no, $exclude_skus)) {
											continue; // Skip this iteration and go to the next product
										}								
										$postid  =   $product->get_id();
										$price   =   $product->get_regular_price();
										
										//echo $product->get_image();
										$image = wp_get_attachment_image_src( get_post_thumbnail_id( $postid  ), 'thumbnail' );
								?>
									<tr role="" class="odd" data-product-name="<?php  echo $product->get_name(); ?>">
									<td  class="sorting_1">
										<?php 
										
											if ( !empty( $image) )  { ?>    
												<img src="<?php  echo $image[0]; ?>" alt="" class="card-image"  /> 
											<?php 
											 
											} else 	{ ?>
												<img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" alt="" class="card-image"  />
											<?php } ?>
									</td>
									<td>
										<?php echo strip_tags($product->get_name()); ?>
									</td>
									<td><span class="item_no"><?php echo $item_no; ?></span></td>
										<td  class="summer_cart">
											<input  id="selectbox" name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;" aria-label="add">
										</td>
										<td>
											<input id="qtybox"  type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric" aria-label="quantity">
										</td>
										<td>$<span class="summer_price"><?php echo $price; ?></span></td>
									</tr>
								<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
			</div>
		</div>
	</div>
	<!-- End LARGE WAX MELTS Catagory Product-->
	
	<!-- room-spray Catagory Product -->
	<p id='room-spray' style='margin-top:-77px'></p>
	<div class="section section-wrap" id="section3">
	  <div class="slide" id="slide1">
        <?php 
			$product_args = array(
				'post_status' 	=> 'publish',
				'limit' 		=> -1,
				'category' 		=> 'room-sprays-all-items', 
				'order'      	=>  'ASC'
			);
			$products = wc_get_products($product_args);
			$item_no = array("RM259","RM260","RM261","RM262","RM263","RM264","RM265","RM266","RM267","RM268","RM269","RM270","RM271","RM272","RM273","RM274");

				usort($products, function($a, $b) use ($item_no) {
					$sku_a = $a->get_sku();
					$sku_b = $b->get_sku();
					return array_search($sku_a, $item_no) - array_search($sku_b, $item_no);
				});
		
		?>
		 <div class="container page-content sidebar-position-without sidebar-mobile-bottom">
				<div class="">
					<div class=""> 
						<div id="<?php //echo $category;?>">
							<table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe customers summerTable" role="grid" aria-describedby="DataTables_Table_0_info">
								<tbody style="width: 1140px; display: revert !important;">
								<thead>
								<p align='center' style='margin-bottom:-20px;'>ROOM SPRAYS $15.00</p>
								</thead>
								<tr>
									<th style="width: 80px;">Image</th>
									<th style="width: 310px;">Name</th>
									<th style="width: 100px;">SKU</th>
									<th class="summer_cart">Add To cart</th>
									<th style="width: 100px;">Quantity</th>
									<th style="width: 100px;">Price</th>
								</tr>
								<?php 
								$exclude_skus = array('RS54','RS56','RS57','RS59','RS62','RS64','RS65','RS66','SC18', 'SC19', 'CC3', 'SC32-1', 'SC-TP','RS617','RS616','RS615','RS614','RS613','RS612','RS611','RS67','RS610','RS58');
								foreach( $products as $product ) {
										$item_no =  $product->get_sku();
										// Skip the product if its SKU is in the excluded list
										if (in_array($item_no, $exclude_skus)) {
											continue; // Skip this iteration and go to the next product
										}								
										$postid  =   $product->get_id();
										$price   =   $product->get_regular_price();
										
										//echo $product->get_image();
										$image = wp_get_attachment_image_src( get_post_thumbnail_id( $postid  ), 'thumbnail' );
								?>
									<tr role="" class="odd" data-product-name="<?php  echo $product->get_name(); ?>">
									<td  class="sorting_1">
										<?php 
										
											if ( !empty( $image) )  { ?>    
												<img src="<?php  echo $image[0]; ?>" alt="" class="card-image"  /> 
											<?php 
											 
											} else 	{ ?>
												<img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" alt="" class="card-image"  />
											<?php } ?>
									</td>
									<td>
										<?php echo strip_tags($product->get_name()); ?>
									</td>
									<td><span class="item_no"><?php echo $item_no; ?></span></td>
										<td  class="summer_cart">
											<input  id="selectbox" name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;" aria-label="add">
										</td>
										<td>
											<input id="qtybox"  type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric" aria-label="quantity">
										</td>
										<td>$<span class="summer_price"><?php echo $price; ?></span></td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Essential Oils -->
	<p id='essential-oils' style='margin-top:-77px'></p>
	<div class="section section-wrap" id="section4">
	 	<div class="slide" id="slide1">
			
			 <?php 
			$product_args = array(
			'post_status' => 'publish',
			'limit' => -1,
			'category' => 'essential-oils',
			'order' => 'ASC'
			);
			$products = wc_get_products($product_args);

			//$item_no = array("EO275", "EO276", "EO277", "EO278","EO279");
			$item_no = array("EO275", "EO276", "EO277", "EO278","EO279");

			usort($products, function($a, $b) use ($item_no) {
				$sku_a = $a->get_sku();
				$sku_b = $b->get_sku();
				return array_search($sku_a, $item_no) - array_search($sku_b, $item_no);
			});
            ?>
		 	<div class="container page-content sidebar-position-without sidebar-mobile-bottom cst_margin" >
				<div class="">
					<div class=""> 
						<div id="<?php //echo $category;?>">
							<table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe customers summerTable table_essential" role="grid" aria-describedby="DataTables_Table_0_info" >
								<tbody style="width: 1140px; display: revert !important;">
								<thead>
								<p align='center' style='margin-bottom:-20px;'>Essential Oils $15.00</p>
								</thead>
								<tr>
									<th style="width: 80px;">Image</th>
									<th style="width: 310px;">Name</th>
									<th style="width: 100px;">SKU</th>
									<th class="summer_cart">Add To cart</th>
									<th style="width: 100px;">Quantity</th>
									<th style="width: 100px;">Price</th>
								</tr>
								<?php 
								$exclude_skus = array('SKU-S-EO118','SKU-S-EO124','SKU-S-EO126','SKU-S-EO115','SKU-W-EO115','SKU-W-EO86','EO74');
								foreach( $products as $product ) {
										$item_no =  $product->get_sku();
										// Skip the product if its SKU is in the excluded list
										if (in_array($item_no, $exclude_skus)) {
											continue; // Skip this iteration and go to the next product
										}								
										$postid  =   $product->get_id();
										$price   =   $product->get_regular_price();
										
										//echo $product->get_image();
										$image = wp_get_attachment_image_src( get_post_thumbnail_id( $postid  ), 'thumbnail' );
								?>
									<tr role="" class="odd" data-product-name="<?php  echo $product->get_name(); ?>">
									<td  class="sorting_1">
										<?php 
										
											if ( !empty( $image) )  { ?>    
												<img src="<?php  echo $image[0]; ?>" alt="" class="card-image"  /> 
											<?php 
											 
											} else 	{ ?>
												<img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" alt="" class="card-image"  />
											<?php } ?>
									</td>
									<td>
										<?php echo strip_tags($product->get_name()); ?>
									</td>
									<td><span class="item_no"><?php echo $item_no; ?></span></td>
										<td class="summer_cart">
											<input  id="selectbox" name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;" aria-label="add">
										</td>
										<td>
											<input id="qtybox"  type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric" aria-label="quantity">
										</td>
										<td>$<span class="summer_price"><?php echo $price; ?></span></td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	
	
	<div style="display:none;">
        <div class="intro">
		<div class="container content-page">
		
		<div class="page-content sidebar-position-without sidebar-mobile-bottom">
			<div class="row">

				<div class="content col-md-12">
				<?php if(have_posts()): while(have_posts()) : the_post(); ?>
						
						<?php the_content(); ?>
						<?php endwhile;  ?>
				<?php endif; ?>
					<?php /*echo do_shortcode( '[quickster product_cat="winter-catalogue"]' ); */?>
				</div>
			</div>
		</div>
		</div>
		</div>
    </div>
	<div id="back-top" class="back-top bounceIn" style="bottom:91px;">
			<a href="#signature_wick" aria-label="signature_wick">
				<span></span>
			</a>
	</div>
</div>
<div id="box-glowscandal" class="box-glowscandal">
        <div class="product-numbers"><span id="cst_summer_qty">No. of Product : 0 <br> Total Price: $0</span></div>
</div>
<style>

#box-glowscandal{
            box-sizing: border-box;
            background-color:#04AA6D;
            width: 300px;
            color: #fff;
            padding: 20px;
            height: 15vh;
            /* text-align: center; */
            border-radius: 5px;
            /* border: 1px solid black; */
            position: fixed;
           right: 10px;
           bottom: 150px;
           box-shadow: 0px 2px 10px rgba(0,0,0,0.4);
		   font-size : 20px;
            
        }


.cart-sec
{
	border: none;
    background-color: #262626;
    text-transform: uppercase;
    border-radius: 0;
    font-size: 13px;
    display: block;
    max-width: 200px;
    padding: 10px;
    margin: 0 auto;
    color: #fff;
    text-align: center;
    margin-bottom: 15px;
	display:none;
}
a.cart-sec:hover{
	box-sizing: border-box;
}
.alert-selection
{
	width: 100%;
    margin: 0 auto;
    float: left;
    text-align: center;
    padding-bottom: 10px;
    font-size: 14px;
	display:none;
}
</style>
<style>
.manual-mass-discount-popup {
    position: fixed;
    width: 100%;
    height: 100%;
    z-index: 999;
    background: rgba(0,0,0,0.6);
    top: 0;
    left: 0;
    display: none;
}
.manual-mass-discount-popup .manual-mass-order-popup-wrapper {
    display: block;
    width: 100%;
    max-width: 574px;
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
.manual-mass-discount-popup h3 {
    margin-bottom: 5px;
}
p.linecode, p.linecode2 {
    font-size: 16px;
    color: #000;
    margin: 0;
}
.manual-mass-discount-popup .linecode2 {
    margin-bottom: 6px;
    font-size: 16px;
    color: #000;
}
p.linecode {
    font-size: 17px;
    color: #000;
}
#discount_ajax
{
	background: #000;
    padding: 7px 40px;
    display: inline-block;
    color: #fff;
    font-size: 16px;
    margin-top: 18px;
}
#d_loading_ajax
{
	display:none;
}
</style>
 <?php if ( is_user_logged_in() ) {
    $current_user = wp_get_current_user();
   $u_id = $current_user->ID;
   $first_catalog_order=get_user_meta( $u_id, 'wc_first_catlog_order', true);
	
	if(empty($first_catalog_order) || $first_catalog_order=="")
	{
 
 ?>
<div class="manual-mass-discount-popup">
     <div class="manual-mass-order-popup-wrapper">
		<div class="close_btn" style="
				position: absolute;
				right: 10px;
				top: 5px;
				color: #000;
				font-weight: 400;
				cursor: pointer;"><i class="fa fa-close" style="font-size:24px;"></i></div>
			<h3>Ship to school discount <i class="fa fa-usd" aria-hidden="true" style="font-size: 34px;"></i></h3>
			<p class="linecode">Your Ship to school orders items: <strong id="qant_items"><img id="d_loading_ajax" src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2022/05/ajax-loader.gif" alt="" class=""></strong> <span id="qant_items2">+ Remaining Catlog Items: <strong></strong></span></p>
			<p class="linecode2">If you will place 150+ quantity then we offer 50% Fundraiser discount.</p>
			<p class="linecode2">(Example: Catlog Items + Ship to School items = 150+)</p>
            <a id="discount_ajax" class="txt-link" data-id="<?php echo $u_id; ?>">OK</a>
	</div>
</div>
<script>
	jQuery(document).ready(function () 
	{
				var ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>";
				 var id="<?php if(!empty($u_id)) { echo $u_id; } ?>";
				 if(id!="")
				 {
					 jQuery(".manual-mass-discount-popup img").css("display","inline-block");
				   jQuery.ajax({

							type : "post",

							url : ajaxurl,

							data : {action: 'wdm_discount_shiptoschool',user_id:id},
			
							success: function(response) {
								console.log(response);
							    jQuery(".manual-mass-discount-popup img").css("display","none");
							   if(response.data.action!=0)
							   {
								jQuery(".manual-mass-discount-popup #qant_items").text(response.data.action);
								var ship_orders = response.data.action;
								if(ship_orders<150)
								{
									var rem = 150 - parseInt(ship_orders);
									jQuery(".manual-mass-discount-popup #qant_items2 strong").text(rem);
								}
								
								jQuery(".manual-mass-discount-popup").css("display","block");
							   }
								
							}
						});
						jQuery(document).on('click', '#discount_ajax, .close_btn', function (e) 
						{
							e.preventDefault();
							 jQuery(".manual-mass-discount-popup img").css("display","none");
							jQuery(".manual-mass-discount-popup").css("display","none");
						});
				 }
		});
</script>
 <?php } } ?>
 <style>
  .seller-buyer-cookie-popup_new_onsite {
    display:none;
    position: fixed;
    z-index: -1;
    opacity: 0;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
}
 .seller-buyer-cookie-popup_new_onsite.show {
    z-index: 9;
    opacity: 1;
}

 .seller-buyer-cookie-popup_new_onsite .popup-body {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translateX(-50%) translateY(-50%);
    background: #fff;
    padding: 25px;
    width: 100%;
    max-width: 400px;
    border: 3px solid #000;
}
 .seller-buyer-cookie-popup_new_onsite .cart-sec_a.another_order {
	 cursor: pointer; 
 }
 .seller-buyer-cookie-popup_new_onsite .cart-sec_a {
    border: none;
    background-color: #262626;
    text-transform: uppercase;
    border-radius: 0;
    font-size: 13px;
    display: block;
    max-width: 200px;
    padding: 10px;
    margin: 0 auto;
    color: #fff;
    text-align: center;
    margin-bottom: 15px;
     -webkit-font-smoothing: antialiased;
    -webkit-transition: background 0.25s ease-in;
    -ms-transition: background 0.25s ease-in;
    -o-transition: background 0.25s ease-in;
    transition: background 0.25s ease-in;
	 cursor: pointer;
}
 .seller-buyer-cookie-popup_new_onsite .cart-sec_a:hover
{
	background-color: #cda85c;
	    box-sizing: border-box;
}
 .seller-buyer-cookie-popup_new_onsite span.popup-close {
    text-align: center;
    display: block;
    margin-top: 15px;
    cursor: pointer;
}
.add-to-cart-Btn {
	top: 102px;
	right: 88px;
}
form.seller-buyer-cookie-form input.popup-submit-button
{

}

@-moz-document url-prefix() {
	.mass-seller-name-new-test
	{
		left: -190px !important;
	}
}
 </style>


	<div class="seller-buyer-cookie-popup_new_onsite">
	<div class="popup-body">
		<div>
		  	<span class="successMSG">Added Successfully!</span>
           <span class="cart-sec_a another_order">Add Another Order</span>
			<a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>cart/" aria-label="cart" class="cart-sec_a">Preview Cart</a>
            <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>checkout/" aria-label="checkout" class="cart-sec_a">Checkout</a>
          </div>
         <span class="popup-close">Or, Cancel</span>
    </div>
    </div>
	
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/catalog/examples.js"></script>

<script type="text/javascript">
	
	function isMobileDevice() {
		return window.innerWidth <= 768; // Adjust the threshold based on your needs
	}

	// Initialize fullpage.js only if not on a mobile device
	if (!isMobileDevice()) {
	
	}
	
	jQuery(document).ready(function(){
		
		jQuery(document).on("click", "#DataTables_Table_0_filter .catalogue-dynamic-popup-btn", function()
		{
			var selected = [];
			var quanty = [];
			jQuery("#DataTables_Table_0 .odd input[name=add]:checked").each(function(){
				  var qTY=jQuery(this).closest('td').next().find("input[name=quantity]").val();
				  quanty.push(qTY);
				  var price=jQuery(this).attr('data-price');
				  var totalPrice=price*qTY;
				  selected.push(totalPrice);
			});
			//alert("okay");
			var sum = 0;
			var sumQTY=0;
			jQuery.each(selected,function(){sum+=parseFloat(this) || 0;});
			jQuery.each(quanty,function(){sumQTY+=parseFloat(this) || 0;});
			console.log(selected);
			console.log(quanty);
			/*jQuery(".alert-selection").html("Total: $"+sum+" and Quantity: "+sumQTY);*/
			jQuery(".seller-buyer-cookie-popup_new_onsite .alert-selection").html("Quantity: "+sumQTY+" and Total: $"+sum.toFixed(2));
			jQuery(".seller-buyer-cookie-popup_new_onsite .alert-selection").css("display","block");
			var val=jQuery(this).attr("data-ajax");
			/*alert(val);*/
			if(val==null || val!="yes")
			{
			jQuery(this).attr("data-ajax","yes");
			var ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>";
			var catalogType="Summer";
			jQuery.ajax({

					type : "post",

					url : ajaxurl,

					data : {action: 'wp_catalog_type', catalogType:catalogType},
	
					success: function(response) {
						console.log(response);
						
					}
				});
			}
	    });
		
		jQuery(".mass-seller-name-new-test").change(function(){
			var s=jQuery(this).val();
			jQuery(".seller-buyer-cookie-form .mass-seller-name-new").val(s);
		});
		
	jQuery("#back-top a").on("click",function(){
	
		jQuery('#fullpage').css('transform','translate3d(0px, 0, 0px)');
		window.location.hash = '#signature_wick';
		console.log('clicked sff');
	});
	});
</script>
<style>
#myBtn
{
	position: absolute;
    bottom: 36px;
    right: 76px;
    padding: 11px 13px;
	z-index:9999;
}
.successMSG
{
	text-align:center;
	width:100%;
	display: inline-block;
    margin-bottom: 20px;
	color:green;
}
</style>
<?php /*Take Tour */ ?>
<style>
.dyPNnG {
    position: fixed;
    inset: 0px;
    z-index: 1000;
    background-color: rgba(255, 255, 255, 0.65);
}
.eWPMk {
    margin: 200px auto;
    max-width: 500px;
    height: 460px;
    border-radius: 15px;
    box-shadow: rgba(0, 0, 0, 0.55) 0px 30px 50px 0px;
    background-color: rgb(255, 255, 255);
    padding: 75px 25px 50px;
    display: flex;
    flex-direction: column;
    -webkit-box-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    align-items: center;
    position: relative;
    overflow: hidden;
}
.bhtBxK {
    position: absolute;
    width: 300px;
    height: 300px;
    background: rgb(248, 248, 250);
    border-radius: 100%;
    top: -140px;
    left: -200px;
}
.dWJjwC {
    color: rgb(50, 50, 50);
    font-size: 55px;
    font-style: normal;
    z-index: 1;
    line-height: 1.49;
    font-weight: 600;
    padding-bottom: 10px;
	  font-size: 40px;
}
.gouVSR {
    color: rgb(50, 50, 50);
    font-size: 17px;
    font-weight: 400;
    font-style: normal;
    line-height: 34px;
    text-align: center;
    padding-bottom: 25px;
	  font-size: 16px;
	    line-height: 1.6;
}
.gouVSR span
{
	    font-size: 14px;
}
.jXbIyY {
    font-size: 17px;
    font-style: normal;
    line-height: 34px;
    width: 108px;
    height: 50px;
    border-radius: 5px;
    background-color: rgba(88, 165, 255, 0.15);
    display: flex;
    flex-direction: column;
    -webkit-box-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    align-items: center;
    color: rgb(88, 165, 255);
    font-weight: 500;
    margin-bottom: 50px;
    cursor: pointer;
}
.fwIEMD {
    font-size: 13px;
    font-weight: 400;
    font-style: normal;
    line-height: 21px;
    color: rgb(140, 140, 140);
    cursor: pointer;
}
.NqDzw {
    position: absolute;
    width: 300px;
    height: 300px;
    background: rgb(242, 101, 58);
    border-radius: 100%;
    bottom: -140px;
    right: -200px;
    opacity: 0.8;
}
div#fullpage.tour-active {
    transform: translate3d(0px, 0px, 0px) !important;
}
div.tourBg
{
	   /* background-color: rgb(247 247 247) !important;*/
}
.tourNext.btn.btn-primary.btn-sm {
    font-size: 14px;
    font-style: normal;
    line-height: 34px;
    font-weight: 500;
    border-radius: 5px;
    background-color: rgba(88, 165, 255, 0.15);
    color: rgb(88, 165, 255);
    flex-direction: column;
    -webkit-box-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    align-items: center;
    cursor: pointer;
    padding: 2px 20px;
    height: auto;
    border: 0;
    text-transform: capitalize;
	
}

.tourPrev.btn.btn-default.btn-sm
{
	font-size: 13px;
    font-weight: 400;
    font-style: normal;
    line-height: 21px;
    color: rgb(140, 140, 140);
    margin-right: 20px;
    cursor: pointer;
    padding: 0;
    text-transform: capitalize;
    border: 0;
	color: #555;
	background: none;
}
.panel-footer h6
{
    margin: 0;
    padding-top: 11px;
    font-size: 12px;
	color: #555;
}
.popover{
 
min-width: 280px !important;
    padding: 5px 0px 0px !important;
    border-radius: 8px;
    box-shadow: rgba(0, 0, 0, 0.35) 0px 3px 25px 0px;
}
.panel-footer
{
	    /*margin-bottom: 11px;*/
		width:100%;
		display:inline-block;
		border-bottom-right-radius: 8px;
    border-bottom-left-radius: 8px;
}
.panel-body {

    color: #333;
    font-weight: 700;
    letter-spacing: 0.1px;
    font-size: 15px;
}
button.close
{
	    position: absolute;
    right: 5px;
    top: 5px;
}
.close:hover, .close:focus
{
	    color: rgb(88, 165, 255);
    background: none;
}
</style>

  <style>
.endTourAnimate {
    -webkit-transition: bottom .5s;
    transition: bottom .5s;
    bottom: -370px;
	    bottom: -401px;
}
.jaRTKi {
    overflow: hidden;
    position: fixed;
    right: 45px;
    z-index: 1000;
    background-color: rgb(255, 255, 255);
    max-width: 400px;
    height: 434px;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    border: 1px solid rgb(238, 238, 238);
    padding: 25px 25px 0px;
	    height: 465px;
}
.etYwuz {
    display: flex;
    flex-direction: row;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: justify;
    justify-content: space-between;
}
.IwcWe {
    font-size: 13px;
    font-weight: 400;
    font-style: normal;
    line-height: 21px;
    color: rgb(88, 165, 255);
    cursor: pointer;
}
.joQcpv {
    padding-left: 200px;
    cursor: pointer;
}
.jeiGCo {
    display: flex;
    flex-direction: column;
    -webkit-box-align: center;
    align-items: center;
}
.dYBfUp {
    color: rgb(50, 50, 50);
    font-size: 35px;
    font-weight: 600;
    font-style: normal;
    line-height: 52px;
    padding-top: 25px;
    padding-bottom: 10px;
	    font-size: 30px;
}
.eYvePi {
    color: rgb(50, 50, 50);
    font-size: 17px;
    font-weight: 400;
    font-style: normal;
    line-height: 34px;
	text-align: center;
    padding-bottom: 50px;
	font-size: 16px;
	line-height: 1.6;
	padding-bottom: 26px;
}
.eYvePi span
{
	    font-size: 14px;
}
.qXac {
    height: 50px;
    border-radius: 5px;
    width: 225px;
    background-color: rgba(88, 165, 255, 0.15);
    color: rgb(88, 165, 255);
    display: flex;
    flex-direction: column;
    -webkit-box-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    align-items: center;
    margin-bottom: 50px;
    z-index: 1;
    cursor: pointer;
}
.hyMzIi {
    background: rgb(248, 248, 250);
    position: absolute;
    width: 300px;
    height: 300px;
    top: 360px;
    left: -180px;
    border-radius: 100%;
}
.fQiUEz {
    position: absolute;
    width: 300px;
    height: 300px;
    background: rgb(242, 101, 58);
    bottom: -270px;
    opacity: 0.4;
    border-radius: 100%;
}
#root-2
{
	display:none;
}
.endTourAnimate.isOpen
{
	bottom:0;
}

</style>  
<?php if($ajax_cookies_val=="yes") { ?>
<style> 
#root{
	display:none;
}
#root-2
{
	display:block;
}
</style> 
<?php } ?>
<script>
        jQuery(document).ready(function() {
            var desiredOffset = 170; // Space you want from the top

            jQuery('.bookmark').click(function(event) {
               // event.preventDefault(); // Prevent the default anchor behavior

                // Get the target section
                var targetSectionID = jQuery(this).attr('href');
                var targetSection = jQuery(targetSectionID);
				//alert(targetSectionID);
                if (targetSection.length > 0) {
                    jQuery('html, body').animate({
                        scrollTop: targetSection.offset().top - desiredOffset
                    }, 1000); // 1000 milliseconds for the animation duration
                }
            });
        });
</script>
<script>
        jQuery(document).ready(function() {
            jQuery('.summerTable input[type="number"]').keydown(function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    var currentInput = jQuery(this);
                    var currentTd = currentInput.closest('td');
                    var currentTr = currentTd.closest('tr');
                    var currentTdIndex = currentTd.index();
                    var nextTr = currentTr.next('tr');
                    
                    if (nextTr.length > 0) {
                        var nextInput = nextTr.find('td').eq(currentTdIndex).find('input');
                        if (nextInput.length > 0) {
                            nextInput.focus();
                        }
                    }
                }
            });
        });
</script>

 <script src="<?php echo get_template_directory_uri(); ?>/assets_tour/js/dknotus-tour.js"></script>
 <script>
	jQuery(document).ready(function(){
		
		jQuery(document).on("change", "#qtybox", function()
		{
			var selected = [];
			var quanty = [];
			jQuery("#DataTables_Table_0 .odd input[name=add]:checked").each(function(){
				  var qTY=jQuery(this).closest('td').next().find("input[name=quantity]").val();
				  quanty.push(qTY);
				  var price=jQuery(this).attr('data-price');
				  var totalPrice=price*qTY;
				  selected.push(totalPrice);
			});
			var sum = 0;
			var sumQTY=0;
			jQuery.each(selected,function(){sum+=parseFloat(this) || 0;});
			jQuery.each(quanty,function(){sumQTY+=parseFloat(this) || 0;});
			console.log(selected);
			console.log(quanty);
			jQuery("#cst_summer_qty").html("No. of Product : "+sumQTY+" <br> Total Price: $"+sum.toFixed(2));
		});
	});
			
</script>
 <script>
		jQuery(document).ready(function(){
			// Your code here runs when the DOM is ready
			//alert("The DOM is now loaded and can be manipulated.");
			 jQuery('.input-text.qty').change(function(){
				 //alert("okay");
				 var currentInput = jQuery(this);
				 var qyt = jQuery(this).val();
					jQuery(this).closest('td').prev('td').find('.add').prop('checked', true);
					var rate = jQuery(this).closest('td').prev('td').find('.add').attr('data-price');
					//alert(data);
					var price = (qyt*rate).toFixed(2);
					currentInput.closest('td').next('td').find('.summer_price').text(price);
					//alert(price);
				});
		});

           jQuery(function(){
			     var ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>";
			   jQuery('.sc-dEoRIm.joQcpv').on('click', function(){
				   if(jQuery("#root-2 .endTourAnimate").hasClass("isOpen"))
				   {
					    jQuery("#root-2 .endTourAnimate").removeClass("isOpen");
				   }
				   else{
				   jQuery("#root-2 .endTourAnimate").addClass("isOpen");
				   }
				  
			   });
			    jQuery('.fwIEMD').on('click', function(){
				   jQuery("#root").css("display","none");
				   jQuery('#root-2').css("display","block");
				 
					var skip_popup="yes";
					var id="<?php if(!empty($u_id)) { echo $u_id; } ?>";
				   jQuery.ajax({

							type : "post",

							url : ajaxurl,

							data : {action: 'wdm_welcome_pop_up_skip', skip_popup_user_id:id,skip_popup:skip_popup},
			
							success: function(response) {
								console.log(response);
								
							}
						});
				   
			   });
              jQuery('#positionsBtn').on("click", function()
			  {
				 jQuery('#fullpage').addClass("tour-active");
				  jQuery("#root").css("display","none");
					
                Tour.run([
				  {
                    element: jQuery('#menu'),
					content: 'This is the category menu. Click here to move into different category sections.',
                    position: 'bottom'
                  },
                  {
                    element: jQuery('#nSeller'),
					content: 'Enter each sellers name here.',
					position: 'left'
                  },
                  {
                    element: jQuery('#fullpage #section0 #selectbox'),
                    content: 'Check this box to select products.',
                    position: 'bottom'
                  },
                  {
                    element: jQuery('#fullpage #section0 #qtybox'),
                    content: 'Enter quantity of product.',
                    position: 'bottom'
                  },
				  {
                    element: jQuery('#fullpage #section0 .fp-controlArrow.fp-prev'),
                    content: 'Move to the previous products slide in this category.',
                    position: 'right'
                  },
				  {
                    element: jQuery('#fullpage #section0 .fp-controlArrow.fp-next'),
                    content: 'Move to the next products slide in this category.',
                    position: 'left'
                  },
				  {
                    element: jQuery('#DataTables_Table_0_filter'),
                    content: 'Click Add Order to Cart button to complete each sellers order.',
                    position: 'left'
                  },
				  {
                    element: jQuery('.seller-buyer-cookie-popup .popup-submit-button-cart'),
                    content: 'Click ADD ANOTHER ORDER button to add products to cart and place your next sellers order.',
                    position: 'right'
                  },
				  {
                    element: jQuery('.seller-buyer-cookie-popup .popup-submit-button-checkout'),
                    content: 'Click CHECKOUT button to proceed to checkout.',
                    position: 'right'
                  }
				  
				 
                 
                ]);
					Tour.onstep = function(currentStep){
					console.log(currentStep);
					var get_content_of_current_step=Tour.current(); 
					console.log(get_content_of_current_step);
					if(get_content_of_current_step==7 || get_content_of_current_step== 8)
					{
						if(!jQuery(".seller-buyer-cookie-popup").hasClass("show")){
							jQuery(".seller-buyer-cookie-popup").addClass("show");
						}
					}
					else
					{
						if(jQuery(".seller-buyer-cookie-popup").hasClass("show")){
							jQuery(".seller-buyer-cookie-popup").removeClass("show");
						}
					}
				};
				 Tour.onfinish = function(){
					console.log('The End');
					jQuery('#fullpage').removeClass("tour-active");
					jQuery('#root-2').css("display","block");
					jQuery(".seller-buyer-cookie-popup").removeClass("show");
					var skip_popup="yes";
					var id="<?php if(!empty($u_id)) { echo $u_id; } ?>";
				   jQuery.ajax({

							type : "post",

							url : ajaxurl,

							data : {action: 'wdm_welcome_pop_up_skip', skip_popup_user_id:id,skip_popup:skip_popup},
			
							success: function(response) {
								console.log(response);
								
							}
						});
				};
				Tour.onclose = function(){
					console.log('The End');
					jQuery('#fullpage').removeClass("tour-active");
					jQuery('#root-2').css("display","block");
					jQuery(".seller-buyer-cookie-popup").removeClass("show");
					var skip_popup="yes";
					var id="<?php if(!empty($u_id)) { echo $u_id; } ?>";
				   jQuery.ajax({

							type : "post",

							url : ajaxurl,

							data : {action: 'wdm_welcome_pop_up_skip', skip_popup_user_id:id,skip_popup:skip_popup},
			
							success: function(response) {
								console.log(response);
								
							}
						});
				};
              });
			    jQuery('#positionsBtn-2').on("click", function()
			  {
				 jQuery('#fullpage').addClass("tour-active");
				  jQuery("#root").css("display","none");
					
                Tour.run([
				  {
                    element: jQuery('#menu'),
					content: 'This is the category menu. Click here to move into different category sections.',
                    position: 'bottom'
                  },
                  {
                    element: jQuery('#nSeller'),
					content: 'Enter each sellers name here.',
					position: 'left'
                  },
                  {
                    element: jQuery('#fullpage #section0 #selectbox'),
                    content: 'Check this box to select products.',
                    position: 'bottom'
                  },
                  {
                    element: jQuery('#fullpage #section0 #qtybox'),
                    content: 'Enter quantity of product.',
                    position: 'bottom'
                  },
				  {
                    element: jQuery('#fullpage #section0 .fp-controlArrow.fp-prev'),
                    content: 'Move to the previous products slide in this category.',
                    position: 'right'
                  },
				  {
                    element: jQuery('#fullpage #section0 .fp-controlArrow.fp-next'),
                    content: 'Move to the next products slide in this category.',
                    position: 'left'
                  },
				  {
                    element: jQuery('#DataTables_Table_0_filter'),
                    content: 'Click Add Order to Cart button to complete each sellers order.',
                    position: 'left'
                  },
				  {
                    element: jQuery('.seller-buyer-cookie-popup .popup-submit-button-cart'),
                    content: 'Click ADD ANOTHER ORDER button to add products to cart and place your next sellers order.',
                    position: 'right'
                  },
				  {
                    element: jQuery('.seller-buyer-cookie-popup .popup-submit-button-checkout'),
                    content: 'Click CHECKOUT button to proceed to checkout.',
                    position: 'right'
                  }
				  
				 
                 
                ]);
				Tour.onstep = function(currentStep){
					console.log(currentStep);
					var get_content_of_current_step=Tour.current(); 
					console.log(get_content_of_current_step);
					if(get_content_of_current_step==7 || get_content_of_current_step== 8)
					{
						if(!jQuery(".seller-buyer-cookie-popup").hasClass("show")){
							jQuery(".seller-buyer-cookie-popup").addClass("show");
						}
					}
					else
					{
						if(jQuery(".seller-buyer-cookie-popup").hasClass("show")){
							jQuery(".seller-buyer-cookie-popup").removeClass("show");
						}
					}
				};
				 Tour.onfinish = function(){
					console.log('The End');
					jQuery('#fullpage').removeClass("tour-active");
					jQuery('#root-2').css("display","block");
					jQuery("#root-2 .endTourAnimate").removeClass("isOpen");
					jQuery(".seller-buyer-cookie-popup").removeClass("show");
				};
				Tour.onclose = function(){
					console.log('The End');
					jQuery('#fullpage').removeClass("tour-active");
					jQuery('#root-2').css("display","block");
					jQuery("#root-2 .endTourAnimate").removeClass("isOpen");
					jQuery(".seller-buyer-cookie-popup").removeClass("show");
				};
              });
            });
          </script>

<div id="root">
   <div class="sc-jKVCRD dyPNnG">
      <div class="sc-kaNhvL eWPMk">
         <div class="sc-kxynE bhtBxK"></div>
         <div class="sc-LKuAh dWJjwC">Welcome!</div>
         <div class="sc-iBEsjs gouVSR"><span>ATTENTION!! This portion is for CATALOG sales only!!<br/>(No orders placed online need to be entered)</span><br/>Let’s begin with a quick tour of the catalog ordering page. This will explain each area & step!<br/><b>PLEASE NOTE—These steps are for each of your sellers catalogs you collected. Glow packs by each sellers order for easy hand out for you!</b></div>
         <div id="positionsBtn" class="sc-hzNEM jXbIyY">Let’s Go</div>
         <div class="sc-chbbiW fwIEMD">Skip Tour</div>
         <div class="sc-cooIXK NqDzw"></div>
      </div>
   </div>
   </div>

 <div id="root-2">
   <div class="sc-dliRfk jaRTKi endTourAnimate">
      <div class="sc-kLIISr etYwuz">
         <div class="sc-qrIAp IwcWe" id="positionsBtn-1">Replay Tour</div>
         <div class="sc-dEoRIm joQcpv">
            <svg id="Capa_1" x="0px" y="0px" width="15px" height="9px" viewBox="0 0 451.847 451.847" xml:space="preserve" fill="#8c8c8c">
               <g>
                  <path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751 c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0 c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"></path>
               </g>
               <g></g>
               <g></g>
               <g></g>
               <g></g>
               <g></g>
               <g></g>
               <g></g>
               <g></g>
               <g></g>
               <g></g>
               <g></g>
               <g></g>
               <g></g>
               <g></g>
               <g></g>
            </svg>
         </div>
      </div>
      <div class="sc-iGrrsa jeiGCo">
         <div class="sc-iqzUVk dYBfUp">Welcome!</div>
         <div class="sc-ipZHIp eYvePi"><span>ATTENTION!! This portion is for CATALOG sales only!!<br/>(No orders placed online need to be entered)</span><br/>Let’s begin with a quick tour of the catalog ordering page. This will explain each area & step!<br/><b>PLEASE NOTE—These steps are for each of your sellers catalogs you collected. Glow packs by each sellers order for easy hand out for you!</b></div>
         <div id="positionsBtn-2" class="sc-bmyXtO qXac">View Tour</div>
      </div>
	  <div class="sc-ebFjAB hyMzIi"></div>
	  <div class="sc-jtggT fQiUEz"></div>
	</div>
</div>  


<?php
	wp_footer();
	?>