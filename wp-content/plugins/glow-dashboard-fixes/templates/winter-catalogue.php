<?php
/**
 * GLOW Fall/Winter brochure order page (loaded by GLOW Dashboard Fixes).
 * Same design, seller field, cart script and styling as the Summer Catalogue
 * template (theme/template-summercatalog.php); product sections from the
 * original template-new-wintercatalog.php.
 */
get_header();
?>
<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/catalog/fullpage.css" />
<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/catalog/examples.css" />
<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/catalog/custom-style.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css">
<?php
 if(!empty( $_GET['seller-name'] ) )
 {
	$nameSeller=$_GET['seller-name'];
?>
<script>
jQuery(document).ready(function(){
	var nSeller="<?php echo esc_js($nameSeller); ?>";
	jQuery("#DataTables_Table_0_filter #nSeller").val(nSeller);
	jQuery(".mass-seller-name-new").val(nSeller);
});
</script>
<?php } 
if ( !is_user_logged_in() ) { ?>
<script>
jQuery(document).ready(function(){
	window.location.href = '<?php echo esc_url( function_exists("wc_get_page_permalink") ? wc_get_page_permalink("myaccount") : home_url("/my-account/") ); ?>';
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
		#section0{
			margin-top: 200px !important;
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
	padding-left: 12%; 
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
.section-wrap{
					margin-top: 120px;
				}
table tbody>tr:nth-child(odd)>td, table tbody>tr:nth-child(odd)>th {
    color: #1c1c1c;
}
.container{
	margin: 0 auto;
	max-width: 1200px;
}
</style>



	<?php
$glow_is_new = ( strpos( $_SERVER['REQUEST_URI'], 'new' ) !== false );
if ( $glow_is_new ) {
				$summer_category = array(
									
									"signature_wick" 	=> "Signature 2 Wick Candles",
									"celebrate_the_moments" 			=> 		"Celebrate The Moments",
									"classic_candles" 	=> "Classic Candles",
									"large_wax_melts" 	=> "Large Wax Melts",
									"room-spray" 		=> "Room Sprays",
									"essential-oils" 	=> "Essential Oils",
									
								);
} else {
			$summer_category = array(
									"classic_candles" 	=> "Classic Candles",
									"signature_wick" 	=> "Signature 2 Wick Candles",
									"large_wax_melts" 	=> "Large Wax Melts",
									"room-spray" 		=> "Room Sprays",
									"essential-oils" 	=> "Essential Oils",
									"let-it-snow" 			=> 		"Let it Snow",
									"celebrate_the_moments" 			=> 		"Celebrate The Moments",
								);
}
?>
	<div class="catalog-subnav-sticky" style="background-color: #ddc49e; padding: 8px 15px; position: sticky; top: 0; z-index: 999; box-shadow: 0 2px 5px rgba(0,0,0,0.15);">
		<div class="container" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; max-width: 1200px; margin: 0 auto; gap: 10px;">
			<ul class="lists" id="menu" style="display: flex; list-style: none; margin: 0; padding: 0; flex-wrap: wrap; align-items: center; gap: 12px; height: auto; background: transparent; top: auto;">
				<?php
					foreach($summer_category as $sum_cat => $cat){
						echo '<li class="list-items" style="margin:0;"><a href="#'.$sum_cat.'" class="bookmark" style="color: #000; font-weight: 700; text-transform: uppercase; font-size: 13px; text-decoration: none; padding: 4px 6px;">'.$cat.'</a></li>';
					}
				?>
			</ul>
			<div id="seller_cart_bar_container" class="seller-cart-bar-wrap" style="display: flex; align-items: center; gap: 8px; margin: 0; position: static;">
				<?php glow_render_catalog_seller_field(); ?>
				<button type="button" id="btn-summer-add-order" class="catalogue-dynamic-popup-btn button" style="background: #262626; color: #fff; font-weight: 700; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; padding: 7px 15px; border: none; border-radius: 3px; cursor: pointer; white-space: nowrap;">ADD ORDER TO CART</button>
			</div>
		</div>
	</div>
<style>
.catalog-subnav-sticky a.catalogue-dynamic-popup-btn,
.catalog-subnav-sticky a.button,
.catalog-subnav-sticky .dataTables_filter,
.catalog-subnav-sticky .add_selection,
.catalog-subnav-sticky .add-to-cart-Btn {
    display: none !important;
}
</style>
<script type='text/javascript' src='<?php echo esc_url((defined('QUICKSTER_URL') ? QUICKSTER_URL : plugins_url('quick-orders-for-woocommerces')) . '/assets/public/datatables/datatables.min.js'); ?>'></script>
<?php if ( $glow_is_new ) : ?>
<div id="fullpage">
	<!-- 18 oz 2-Wick Signature Candles-->
	<p id='signature_wick' style='padding-top: 120px;'></p>
<div class="section section-wrap light-green-bg-section" id="section1">
    <div class="slide" id="slide1">
        <?php
            $allowed_skus = array("SC200","SC201","SC202","SC203","SC204","SC205","SC206","SC207","SC208","SC209","SC210","SC211","SC212","SC213","SC214","SC215");

            $product_args = array(
                'post_status'   => 'publish',
                'limit'         => -1,
                'category'      => '2-wick-signature-candles', 
                'order'         => 'ASC'
            );
            $products = wc_get_products($product_args);

            usort($products, function($a, $b) use ($allowed_skus) {
                $sku_a = $a->get_sku();
                $sku_b = $b->get_sku();
                return array_search($sku_a, $allowed_skus) - array_search($sku_b, $allowed_skus);
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
                            $glow_seen = array(); foreach( $products as $product ) {
                                $item_no = $product->get_sku(); if ( '' !== $item_no && isset( $glow_seen[ $item_no ] ) ) { continue; } $glow_seen[ $item_no ] = 1;

                                // Only show products with SKUs in the allowed list
                                if ($allowed_skus && !in_array($item_no, $allowed_skus)) {
                                    continue;
                                }

                                $postid = $product->get_id();
                                $price  = $product->get_regular_price();
                                $image  = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'thumbnail' );
                            ?>
                                <tr role="" class="odd" data-product-name="<?php echo $product->get_name(); ?>">
                                    <td class="sorting_1">
                                        <?php if ( !empty( $image ) ) { ?>    
                                            <img src="<?php echo $image[0]; ?>" alt="" class="card-image" /> 
                                        <?php } else { ?>
                                            <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" alt="" class="card-image" />
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php echo strip_tags($product->get_name()); ?>
                                    </td>
                                    <td><span class="item_no"><?php echo $item_no; ?></span></td>
                                    <td class="summer_cart">
                                        <input id="selectbox" name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;" aria-label="add">
                                    </td>
                                    <td>
                                        <input id="qtybox" type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric" aria-label="quantity">
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
            $allowed_skus = array("CTM217","CTM218","CTM219","CTM220","CTM221","CTM222","CTM223","CTM224","CTM225","CTM226");

            $product_args = array(
                'post_status'   => 'publish',
                'limit'         => -1,
                'category'      => 'celebrate-the-moments', 
                'order'         => 'ASC'
            );
            $products = wc_get_products($product_args);

            usort($products, function($a, $b) use ($allowed_skus) {
                $sku_a = $a->get_sku();
                $sku_b = $b->get_sku();
                return array_search($sku_a, $allowed_skus) - array_search($sku_b, $allowed_skus);
            });
        ?>
        <div class="container page-content sidebar-position-without sidebar-mobile-bottom cst_margin">
            <div class="">
                <div class=""> 
                    <div id="<?php //echo $category;?>">
                        <table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe customers summerTable" role="grid" aria-describedby="DataTables_Table_0_info">
                            <tbody style="width: 1140px; display: revert !important;">
                            <thead>
                                <p align='center' style='margin-bottom:-20px;'>Celebrate the Moments $25.00</p>
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
                            $glow_seen = array(); foreach( $products as $product ) {
                                $item_no = $product->get_sku(); if ( '' !== $item_no && isset( $glow_seen[ $item_no ] ) ) { continue; } $glow_seen[ $item_no ] = 1;

                                // Only show products with SKUs in the allowed list
                                if ($allowed_skus && !in_array($item_no, $allowed_skus)) {
                                    continue;
                                }

                                $postid = $product->get_id();
                                $price  = $product->get_regular_price();
                                $image  = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'thumbnail' );
                            ?>
                                <tr role="" class="odd" data-product-name="<?php echo $product->get_name(); ?>">
                                    <td class="sorting_1">
                                        <?php if ( !empty( $image ) ) { ?>    
                                            <img src="<?php echo $image[0]; ?>" alt="" class="card-image" /> 
                                        <?php } else { ?>
                                            <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" alt="" class="card-image" />
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php echo strip_tags($product->get_name()); ?>
                                    </td>
                                    <td><span class="item_no"><?php echo $item_no; ?></span></td>
                                    <td class="summer_cart">
                                        <input id="selectbox" name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;" aria-label="add">
                                    </td>
                                    <td>
                                        <input id="qtybox" type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric" aria-label="quantity">
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
	
	


	<!--<p id='classic_candles' style='margin-top:-77px'></p> -->
<div class="section" id="section0">
    <div class="slide" id="slide1">
        <?php 
            $allowed_skus = array("CC227","CC228","CC229","CC230","CC231","CC232","CC233","CC234","CC235","CC236","CC237","CC238","CC239","CC240","CC241","CC242");

            $product_args = array(
                'post_status'   => 'publish',
                'limit'         => -1,
                'category'      => 'classic-candles',
                'order'         => 'ASC'
            );
            $products = wc_get_products($product_args);

            usort($products, function($a, $b) use ($allowed_skus) {
                $sku_a = $a->get_sku();
                $sku_b = $b->get_sku();
                return array_search($sku_a, $allowed_skus) - array_search($sku_b, $allowed_skus);
            });
        ?>
        <div class="container page-content sidebar-position-without sidebar-mobile-bottom">
            <div class="row">
                <div class="content col-md-12">	
                    <div id="<?php //echo $category;?>classic_candles">
                        <table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe customers summerTable" role="grid" aria-describedby="DataTables_Table_0_info">
                            <tbody style="width: 1140px; display: revert !important;">
                            <thead>
                                <p align='center' style='margin-bottom:-20px;'>Classic Candles $15.00</p>
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
                            $glow_seen = array(); foreach( $products as $product ) {
                                $item_no = $product->get_sku(); if ( '' !== $item_no && isset( $glow_seen[ $item_no ] ) ) { continue; } $glow_seen[ $item_no ] = 1;

                                // Only show products with SKUs in the allowed list
                                if ($allowed_skus && !in_array($item_no, $allowed_skus)) {
                                    continue;
                                }

                                $postid = $product->get_id();
                                $price  = $product->get_regular_price();
                                $image  = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'thumbnail' );
                            ?>
                                <tr role="" class="odd" data-product-name="<?php echo $product->get_name(); ?>">
                                    <td class="sorting_1">
                                        <?php if ( !empty( $image ) ) { ?>    
                                            <img src="<?php echo $image[0]; ?>" alt="" class="card-image" /> 
                                        <?php } else { ?>
                                            <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" alt="" class="card-image" />
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php echo strip_tags($product->get_name()); ?>
                                    </td>
                                    <td><span class="item_no"><?php echo $item_no; ?></span></td>
                                    <td class="summer_cart">
                                        <input id="selectbox" name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;" aria-label="add">
                                    </td>
                                    <td>
                                        <input id="qtybox" type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric" aria-label="quantity">
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
            $allowed_skus = array("LM243","LM244","LM245","LM246","LM247","LM248","LM249","LM250","LM251","LM252","LM253","LM254","LM255","LM256","LM257","LM258");

            $product_args = array(
                'post_status'   => 'publish',
                'limit'         => -1,
                'category'      => 'large-wax-melts-6-cubes', 
                'order'         => 'ASC'
            );
            $products = wc_get_products($product_args);

            usort($products, function($a, $b) use ($allowed_skus) {
                $sku_a = $a->get_sku();
                $sku_b = $b->get_sku();
                return array_search($sku_a, $allowed_skus) - array_search($sku_b, $allowed_skus);
            });
        ?>
        <div class="container page-content sidebar-position-without sidebar-mobile-bottom">
            <div class="">
                <div class=""> 
                    <div id="<?php //echo $category;?>">
                        <table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe customers summerTable" role="grid" aria-describedby="DataTables_Table_0_info">
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
                            $glow_seen = array(); foreach( $products as $product ) {
                                $item_no = $product->get_sku(); if ( '' !== $item_no && isset( $glow_seen[ $item_no ] ) ) { continue; } $glow_seen[ $item_no ] = 1;

                                // Only show products with SKUs in the allowed list
                                if ($allowed_skus && !in_array($item_no, $allowed_skus)) {
                                    continue;
                                }

                                $postid = $product->get_id();
                                $price  = $product->get_regular_price();
                                $image  = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'thumbnail' );
                            ?>
                                <tr role="" class="odd" data-product-name="<?php echo $product->get_name(); ?>">
                                    <td class="sorting_1">
                                        <?php if ( !empty( $image ) ) { ?>    
                                            <img src="<?php echo $image[0]; ?>" alt="" class="card-image" /> 
                                        <?php } else { ?>
                                            <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" alt="" class="card-image" />
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php echo strip_tags($product->get_name()); ?>
                                    </td>
                                    <td><span class="item_no"><?php echo $item_no; ?></span></td>
                                    <td class="summer_cart">
                                        <input id="selectbox" name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;" aria-label="add">
                                    </td>
                                    <td>
                                        <input id="qtybox" type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric" aria-label="quantity">
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
            $allowed_skus = array("RM259","RM260","RM261","RM262","RM263","RM264","RM265","RM266","RM267","RM268","RM269","RM270","RM271","RM272","RM273","RM274");

            $product_args = array(
                'post_status'   => 'publish',
                'limit'         => -1,
                'category'      => 'room-sprays-all-items', 
                'order'         => 'ASC'
            );
            $products = wc_get_products($product_args);

            usort($products, function($a, $b) use ($allowed_skus) {
                $sku_a = $a->get_sku();
                $sku_b = $b->get_sku();
                return array_search($sku_a, $allowed_skus) - array_search($sku_b, $allowed_skus);
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
                            $glow_seen = array(); foreach( $products as $product ) {
                                $item_no = $product->get_sku(); if ( '' !== $item_no && isset( $glow_seen[ $item_no ] ) ) { continue; } $glow_seen[ $item_no ] = 1;

                                // Only show products with SKUs in the allowed list
                                if ($allowed_skus && !in_array($item_no, $allowed_skus)) {
                                    continue;
                                }

                                $postid = $product->get_id();
                                $price  = $product->get_regular_price();
                                $image  = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'thumbnail' );
                            ?>
                                <tr role="" class="odd" data-product-name="<?php echo $product->get_name(); ?>">
                                    <td class="sorting_1">
                                        <?php if ( !empty( $image ) ) { ?>    
                                            <img src="<?php echo $image[0]; ?>" alt="" class="card-image" /> 
                                        <?php } else { ?>
                                            <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" alt="" class="card-image" />
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php echo strip_tags($product->get_name()); ?>
                                    </td>
                                    <td><span class="item_no"><?php echo $item_no; ?></span></td>
                                    <td class="summer_cart">
                                        <input id="selectbox" name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;" aria-label="add">
                                    </td>
                                    <td>
                                        <input id="qtybox" type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric" aria-label="quantity">
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
            $allowed_skus = array("EO275","EO276","EO277","EO278","EO279");

            $product_args = array(
                'post_status'   => 'publish',
                'limit'         => -1,
                'category'      => 'essential-oils',
                'order'         => 'ASC'
            );
            $products = wc_get_products($product_args);

            usort($products, function($a, $b) use ($allowed_skus) {
                $sku_a = $a->get_sku();
                $sku_b = $b->get_sku();
                return array_search($sku_a, $allowed_skus) - array_search($sku_b, $allowed_skus);
            });
        ?>
        <div class="container page-content sidebar-position-without sidebar-mobile-bottom cst_margin">
            <div class="">
                <div class=""> 
                    <div id="<?php //echo $category;?>">
                        <table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe customers summerTable table_essential" role="grid" aria-describedby="DataTables_Table_0_info">
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
                            $glow_seen = array(); foreach( $products as $product ) {
                                $item_no = $product->get_sku(); if ( '' !== $item_no && isset( $glow_seen[ $item_no ] ) ) { continue; } $glow_seen[ $item_no ] = 1;

                                // Only show products with SKUs in the allowed list
                                if ($allowed_skus && !in_array($item_no, $allowed_skus)) {
                                    continue;
                                }

                                $postid = $product->get_id();
                                $price  = $product->get_regular_price();
                                $image  = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'thumbnail' );
                            ?>
                                <tr role="" class="odd" data-product-name="<?php echo $product->get_name(); ?>">
                                    <td class="sorting_1">
                                        <?php if ( !empty( $image ) ) { ?>    
                                            <img src="<?php echo $image[0]; ?>" alt="" class="card-image" /> 
                                        <?php } else { ?>
                                            <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" alt="" class="card-image" />
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php echo strip_tags($product->get_name()); ?>
                                    </td>
                                    <td><span class="item_no"><?php echo $item_no; ?></span></td>
                                    <td class="summer_cart">
                                        <input id="selectbox" name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;" aria-label="add">
                                    </td>
                                    <td>
                                        <input id="qtybox" type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric" aria-label="quantity">
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
<?php else : ?>
<div id="fullpage">


	<p id='classic_candles' style='padding-top: 125px;'></p>
<div class="section" id="section0">
    <div class="slide" id="slide1">
        <?php 
            $allowed_skus = array(); /* full category (client: use store products) */

            $product_args = array(
                'post_status' => 'publish',
	'orderby' => 'title',
                'limit'         => -1,
                'category'      => 'classic-candles',
                'order'         => 'ASC'
            );
            $products = wc_get_products($product_args);

            usort($products, function($a, $b) use ($allowed_skus) {
                $sku_a = $a->get_sku();
                $sku_b = $b->get_sku();
                return array_search($sku_a, $allowed_skus) - array_search($sku_b, $allowed_skus);
            });
        ?>
        <div class="container page-content sidebar-position-without sidebar-mobile-bottom">
            <div class="row">
                <div class="content col-md-12">	
                    <div id="<?php //echo $category;?>">
                        <table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe customers summerTable" role="grid" aria-describedby="DataTables_Table_0_info">
                            <tbody style="width: 1140px; display: revert !important;">
                            <thead>
                                <p align='center' style='margin-bottom:-20px;'>Classic Candles $15.00</p>
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
                            $glow_seen = array(); foreach( $products as $product ) {
                                $item_no = $product->get_sku(); if ( '' !== $item_no && isset( $glow_seen[ $item_no ] ) ) { continue; } $glow_seen[ $item_no ] = 1;

                                // Only show products with SKUs in the allowed list
                                if ($allowed_skus && !in_array($item_no, $allowed_skus)) {
                                    continue;
                                }

                                $postid = $product->get_id();
                                $price  = $product->get_regular_price();
                                $image  = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'thumbnail' );
                            ?>
                                <tr role="" class="odd" data-product-name="<?php echo $product->get_name(); ?>">
                                    <td class="sorting_1">
                                        <?php if ( !empty( $image ) ) { ?>    
                                            <img src="<?php echo $image[0]; ?>" alt="" class="card-image" /> 
                                        <?php } else { ?>
                                            <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" alt="" class="card-image" />
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php echo strip_tags($product->get_name()); ?>
                                    </td>
                                    <td><span class="item_no"><?php echo $item_no; ?></span></td>
                                    <td class="summer_cart">
                                        <input id="selectbox" name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;" aria-label="add">
                                    </td>
                                    <td>
                                        <input id="qtybox" type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric" aria-label="quantity">
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
	<style>
		.summer_cart {
			
			display:none;
		}
	
	</style>
	<!-- End Classic Candles Catagory Product-->
	
	<!-- 18 oz 2-Wick Signature Candles-->
	<p id='signature_wick' style='margin-top:-77px'></p>
<div class="section section-wrap light-green-bg-section" id="section1">
    <div class="slide" id="slide1">
        <?php
            $allowed_skus = array(); /* full category (client: use store products) */

            $product_args = array(
                'post_status' => 'publish',
	'orderby' => 'title',
                'limit'         => -1,
                'category'      => '2-wick-signature-candles', 
                'order'         => 'ASC'
            );
            $products = wc_get_products($product_args);

            usort($products, function($a, $b) use ($allowed_skus) {
                $sku_a = $a->get_sku();
                $sku_b = $b->get_sku();
                return array_search($sku_a, $allowed_skus) - array_search($sku_b, $allowed_skus);
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
                            $glow_seen = array(); foreach( $products as $product ) {
                                $item_no = $product->get_sku(); if ( '' !== $item_no && isset( $glow_seen[ $item_no ] ) ) { continue; } $glow_seen[ $item_no ] = 1;

                                // Only show products with SKUs in the allowed list
                                if ($allowed_skus && !in_array($item_no, $allowed_skus)) {
                                    continue;
                                }

                                $postid = $product->get_id();
                                $price  = $product->get_regular_price();
                                $image  = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'thumbnail' );
                            ?>
                                <tr role="" class="odd" data-product-name="<?php echo $product->get_name(); ?>">
                                    <td class="sorting_1">
                                        <?php if ( !empty( $image ) ) { ?>    
                                            <img src="<?php echo $image[0]; ?>" alt="" class="card-image" /> 
                                        <?php } else { ?>
                                            <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" alt="" class="card-image" />
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php echo strip_tags($product->get_name()); ?>
                                    </td>
                                    <td><span class="item_no"><?php echo $item_no; ?></span></td>
                                    <td class="summer_cart">
                                        <input id="selectbox" name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;" aria-label="add">
                                    </td>
                                    <td>
                                        <input id="qtybox" type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric" aria-label="quantity">
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
		
		
	<!-- Large Wax Melts Catagory Product-->
	<p id='large_wax_melts' style='margin-top:-77px'></p>
<div class="section section-wrap" id="section2">
    <div class="slide" id="slide1">
        <?php 
            $allowed_skus = array(); /* full category (client: use store products) */

            $product_args = array(
                'post_status' => 'publish',
	'orderby' => 'title',
                'limit'         => -1,
                'category'      => 'large-wax-melts-6-cubes', 
                'order'         => 'ASC'
            );
            $products = wc_get_products($product_args);

            usort($products, function($a, $b) use ($allowed_skus) {
                $sku_a = $a->get_sku();
                $sku_b = $b->get_sku();
                return array_search($sku_a, $allowed_skus) - array_search($sku_b, $allowed_skus);
            });
        ?>
        <div class="container page-content sidebar-position-without sidebar-mobile-bottom">
            <div class="">
                <div class=""> 
                    <div id="<?php //echo $category;?>">
                        <table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe customers summerTable" role="grid" aria-describedby="DataTables_Table_0_info">
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
                            $glow_seen = array(); foreach( $products as $product ) {
                                $item_no = $product->get_sku(); if ( '' !== $item_no && isset( $glow_seen[ $item_no ] ) ) { continue; } $glow_seen[ $item_no ] = 1;

                                // Only show products with SKUs in the allowed list
                                if ($allowed_skus && !in_array($item_no, $allowed_skus)) {
                                    continue;
                                }

                                $postid = $product->get_id();
                                $price  = $product->get_regular_price();
                                $image  = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'thumbnail' );
                            ?>
                                <tr role="" class="odd" data-product-name="<?php echo $product->get_name(); ?>">
                                    <td class="sorting_1">
                                        <?php if ( !empty( $image ) ) { ?>    
                                            <img src="<?php echo $image[0]; ?>" alt="" class="card-image" /> 
                                        <?php } else { ?>
                                            <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" alt="" class="card-image" />
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php echo strip_tags($product->get_name()); ?>
                                    </td>
                                    <td><span class="item_no"><?php echo $item_no; ?></span></td>
                                    <td class="summer_cart">
                                        <input id="selectbox" name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;" aria-label="add">
                                    </td>
                                    <td>
                                        <input id="qtybox" type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric" aria-label="quantity">
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
            $allowed_skus = array(); /* full category (client: use store products) */

            $product_args = array(
                'post_status' => 'publish',
	'orderby' => 'title',
                'limit'         => -1,
                'category'      => 'room-sprays-5oz', 
                'order'         => 'ASC'
            );
            $products = wc_get_products($product_args);

            usort($products, function($a, $b) use ($allowed_skus) {
                $sku_a = $a->get_sku();
                $sku_b = $b->get_sku();
                return array_search($sku_a, $allowed_skus) - array_search($sku_b, $allowed_skus);
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
                            $glow_seen = array(); foreach( $products as $product ) {
                                $item_no = $product->get_sku(); if ( '' !== $item_no && isset( $glow_seen[ $item_no ] ) ) { continue; } $glow_seen[ $item_no ] = 1;

                                // Only show products with SKUs in the allowed list
                                if ($allowed_skus && !in_array($item_no, $allowed_skus)) {
                                    continue;
                                }

                                $postid = $product->get_id();
                                $price  = $product->get_regular_price();
                                $image  = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'thumbnail' );
                            ?>
                                <tr role="" class="odd" data-product-name="<?php echo $product->get_name(); ?>">
                                    <td class="sorting_1">
                                        <?php if ( !empty( $image ) ) { ?>    
                                            <img src="<?php echo $image[0]; ?>" alt="" class="card-image" /> 
                                        <?php } else { ?>
                                            <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" alt="" class="card-image" />
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php echo strip_tags($product->get_name()); ?>
                                    </td>
                                    <td><span class="item_no"><?php echo $item_no; ?></span></td>
                                    <td class="summer_cart">
                                        <input id="selectbox" name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;" aria-label="add">
                                    </td>
                                    <td>
                                        <input id="qtybox" type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric" aria-label="quantity">
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
            $allowed_skus = array(); /* full category (client: use store products) */

            $product_args = array(
                'post_status' => 'publish',
	'orderby' => 'title',
                'limit'         => -1,
                'category'      => 'essential-oils',
                'order'         => 'ASC'
            );
            $products = wc_get_products($product_args);

            usort($products, function($a, $b) use ($allowed_skus) {
                $sku_a = $a->get_sku();
                $sku_b = $b->get_sku();
                return array_search($sku_a, $allowed_skus) - array_search($sku_b, $allowed_skus);
            });
        ?>
        <div class="container page-content sidebar-position-without sidebar-mobile-bottom cst_margin">
            <div class="">
                <div class=""> 
                    <div id="<?php //echo $category;?>">
                        <table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe customers summerTable table_essential" role="grid" aria-describedby="DataTables_Table_0_info">
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
                            $glow_seen = array(); foreach( $products as $product ) {
                                $item_no = $product->get_sku(); if ( '' !== $item_no && isset( $glow_seen[ $item_no ] ) ) { continue; } $glow_seen[ $item_no ] = 1;

                                // Only show products with SKUs in the allowed list
                                if ($allowed_skus && !in_array($item_no, $allowed_skus)) {
                                    continue;
                                }

                                $postid = $product->get_id();
                                $price  = $product->get_regular_price();
                                $image  = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'thumbnail' );
                            ?>
                                <tr role="" class="odd" data-product-name="<?php echo $product->get_name(); ?>">
                                    <td class="sorting_1">
                                        <?php if ( !empty( $image ) ) { ?>    
                                            <img src="<?php echo $image[0]; ?>" alt="" class="card-image" /> 
                                        <?php } else { ?>
                                            <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" alt="" class="card-image" />
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php echo strip_tags($product->get_name()); ?>
                                    </td>
                                    <td><span class="item_no"><?php echo $item_no; ?></span></td>
                                    <td class="summer_cart">
                                        <input id="selectbox" name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;" aria-label="add">
                                    </td>
                                    <td>
                                        <input id="qtybox" type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric" aria-label="quantity">
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
	
	<!-- Boxed Candles-->
	
	<p id='let-it-snow' style='margin-top:-77px'></p>
<div class="section section-wrap" id="section5">
    <div class="slide" id="slide1">
        <?php 
            $product_args = array(
                'post_status' => 'publish',
	'orderby' => 'title',
                'limit'         => -1,
                'category'      => 'let-it-snow-candle-16-oz', 
                'order'         => 'ASC'
            );
            $products = wc_get_products($product_args);
        ?>
        <div class="container page-content sidebar-position-without sidebar-mobile-bottom cst_margin">
            <div class="">
                <div class=""> 
                    <div id="<?php //echo $category;?>">
                        <table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe customers summerTable" role="grid" aria-describedby="DataTables_Table_0_info">
                            <tbody style="width: 1140px; display: revert !important;">
                            <thead>
                                <p align='center' style='margin-bottom:-20px;'>Let it Snow $40.00</p>
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
                            $glow_seen = array(); foreach( $products as $product ) {
                                $item_no = $product->get_sku(); if ( '' !== $item_no && isset( $glow_seen[ $item_no ] ) ) { continue; } $glow_seen[ $item_no ] = 1;
                                $postid  = $product->get_id();
                                $price   = $product->get_regular_price();
                                $image   = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'thumbnail' );
                            ?>
                                <tr role="" class="odd" data-product-name="<?php echo $product->get_name(); ?>">
                                    <td class="sorting_1">
                                        <?php if ( !empty( $image ) ) { ?>    
                                            <img src="<?php echo $image[0]; ?>" alt="" class="card-image" /> 
                                        <?php } else { ?>
                                            <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" alt="" class="card-image" />
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php echo strip_tags($product->get_name()); ?>
                                    </td>
                                    <td><span class="item_no"><?php echo $item_no; ?></span></td>
                                    <td class="summer_cart">
                                        <input id="selectbox" name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;" aria-label="add">
                                    </td>
                                    <td>
                                        <input id="qtybox" type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric" aria-label="quantity">
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
		
	<!-- Celebrate The Movements-->
	<p id='celebrate_the_moments' style='margin-top:-77px'></p> 
<div class="section section-wrap" id="section5">
    <div class="slide" id="slide1">
        <?php 
            $allowed_skus = array(); /* full category (client: use store products) */

            $product_args = array(
                'post_status' => 'publish',
	'orderby' => 'title',
                'limit'         => -1,
                'category'      => 'celebrate-the-moments', 
                'order'         => 'ASC'
            );
            $products = wc_get_products($product_args);

            usort($products, function($a, $b) use ($allowed_skus) {
                $sku_a = $a->get_sku();
                $sku_b = $b->get_sku();
                return array_search($sku_a, $allowed_skus) - array_search($sku_b, $allowed_skus);
            });
        ?>
        <div class="container page-content sidebar-position-without sidebar-mobile-bottom cst_margin">
            <div class="">
                <div class=""> 
                    <div id="<?php //echo $category;?>">
                        <table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe customers summerTable" role="grid" aria-describedby="DataTables_Table_0_info">
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
                            $glow_seen = array(); foreach( $products as $product ) {
                                $item_no = $product->get_sku(); if ( '' !== $item_no && isset( $glow_seen[ $item_no ] ) ) { continue; } $glow_seen[ $item_no ] = 1;

                                // Only show products with SKUs in the allowed list
                                if ($allowed_skus && !in_array($item_no, $allowed_skus)) {
                                    continue;
                                }

                                $postid = $product->get_id();
                                $price  = $product->get_regular_price();
                                $image  = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'thumbnail' );
                            ?>
                                <tr role="" class="odd" data-product-name="<?php echo $product->get_name(); ?>">
                                    <td class="sorting_1">
                                        <?php if ( !empty( $image ) ) { ?>    
                                            <img src="<?php echo $image[0]; ?>" alt="" class="card-image" /> 
                                        <?php } else { ?>
                                            <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" alt="" class="card-image" />
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php echo strip_tags($product->get_name()); ?>
                                    </td>
                                    <td><span class="item_no"><?php echo $item_no; ?></span></td>
                                    <td class="summer_cart">
                                        <input id="selectbox" name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;" aria-label="add">
                                    </td>
                                    <td>
                                        <input id="qtybox" type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric" aria-label="quantity">
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
			<a href="#classic_candles" aria-label="classic_candles">
				<span></span>
			</a>
	</div>
</div>
<?php endif; ?>
<div id="box-glowscandal" class="box-glowscandal">
        <div class="product-numbers"><span id="cst_summer_qty">No. of Product : 0 <br> Total Price: $0</span></div>
</div>
<style>
#box-glowscandal,a.cart-sec:hover{box-sizing:border-box}.alert-selection,.cart-sec{text-align:center;display:none}#box-glowscandal{background-color:#04aa6d;width:300px;color:#fff;padding:20px;height:15vh;border-radius:5px;position:fixed;right:10px;bottom:150px;box-shadow:0 2px 10px rgba(0,0,0,.4);font-size:20px}.cart-sec{border:none;background-color:#262626;text-transform:uppercase;border-radius:0;font-size:13px;max-width:200px;padding:10px;margin:0 auto 15px;color:#fff}.alert-selection{width:100%;margin:0 auto;float:left;padding-bottom:10px;font-size:14px}

.manual-mass-discount-popup{position:fixed;width:100%;height:100%;z-index:999;background:rgba(0,0,0,.6);top:0;left:0;display:none}.manual-mass-discount-popup .manual-mass-order-popup-wrapper{display:block;width:100%;max-width:574px;position:absolute;top:50%;left:50%;transform:translateY(-50%) translateX(-50%);text-align:center;padding:40px 25px;background:#fff;border:3px solid #000;border-radius:5px}.manual-mass-discount-popup h3{margin-bottom:5px}p.linecode,p.linecode2{font-size:16px;color:#000;margin:0}.manual-mass-discount-popup .linecode2{margin-bottom:6px;font-size:16px;color:#000}p.linecode{font-size:17px;color:#000}#discount_ajax{background:#000;padding:7px 40px;display:inline-block;color:#fff;font-size:16px;margin-top:18px}#d_loading_ajax{display:none}

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
			<p class="linecode">Your Ship to school orders items: <strong id="qant_items"><img id="d_loading_ajax" src="<?php echo esc_url( content_url() ); ?>/uploads/2022/05/ajax-loader.gif" class=""></strong> <span id="qant_items2">+ Remaining Catlog Items: <strong></strong></span></p>
			<p class="linecode2">If you will place 150+ quantity then we offer 50% Fundraiser discount.</p>
			<p class="linecode2">(Example: Catlog Items + Ship to School items = 150+)</p>
            <a id="discount_ajax" class="txt-link" data-id="<?php echo $u_id; ?>">OK</a>
	</div>
</div>
<script>
	jQuery(document).ready(function () 
	{
				var ajaxurl="<?php echo esc_url( admin_url("admin-ajax.php") ); ?>";
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
	display: none;
	position: fixed;
	z-index: 999999 !important;
	left: 0;
	top: 0;
	width: 100vw;
	height: 100vh;
	background: rgba(0, 0, 0, 0.65);
}
.seller-buyer-cookie-popup_new_onsite.show {
	display: flex !important;
	align-items: center;
	justify-content: center;
}
.seller-buyer-cookie-popup_new_onsite .popup-body {
	position: relative;
	background: #ffffff;
	padding: 35px 30px;
	width: 90%;
	max-width: 420px;
	border: 3px solid #262626;
	border-radius: 6px;
	text-align: center;
	box-shadow: 0 10px 30px rgba(0,0,0,0.4);
	box-sizing: border-box;
}
.seller-buyer-cookie-popup_new_onsite .cart-sec_a.another_order {
	cursor: pointer;
}
.seller-buyer-cookie-popup_new_onsite .cart-sec_a {
	border: none;
	background-color: #262626;
	text-transform: uppercase;
	border-radius: 3px;
	font-size: 13px;
	font-weight: 700;
	display: block;
	max-width: 250px;
	padding: 12px 15px;
	margin: 10px auto;
	color: #ffffff !important;
	text-align: center;
	text-decoration: none;
	letter-spacing: 0.5px;
	transition: background-color .25s ease-in;
	cursor: pointer;
	box-sizing: border-box;
}
.seller-buyer-cookie-popup_new_onsite .cart-sec_a:hover {
	background-color: #cda85c;
	color: #ffffff !important;
}
.seller-buyer-cookie-popup_new_onsite span.popup-close {
	text-align: center;
	display: block;
	margin-top: 15px;
	color: #888888;
	font-size: 13px;
	cursor: pointer;
	text-decoration: underline;
}
.seller-buyer-cookie-popup_new_onsite span.popup-close:hover {
	color: #000000;
}
.seller-buyer-cookie-popup_new_onsite .successMSG {
	display: block;
	font-size: 22px;
	font-weight: 700;
	color: #27ae60;
	margin-bottom: 20px;
}
</style>

	<div class="seller-buyer-cookie-popup_new_onsite">
		<div class="popup-body">
			<div>
				<span class="successMSG">Added Successfully!</span>
				<span class="cart-sec_a another_order">Add Another Order</span>
				<a href="<?php echo esc_url( function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart/') ); ?>" class="cart-sec_a">Preview Cart</a>
				<a href="<?php echo esc_url( function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : home_url('/checkout/') ); ?>" class="cart-sec_a">Checkout</a>
			</div>
			<span class="popup-close">Or, Cancel</span>
		</div>
	</div>
	<!-- <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/catalog/fullpage.js"></script> -->
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/catalog/examples.js"></script>

<script type="text/javascript">
	
	function isMobileDevice() {
		return window.innerWidth <= 768; // Adjust the threshold based on your needs
	}

	// Initialize fullpage.js only if not on a mobile device
	if (!isMobileDevice()) {
		// var myFullpage = new fullpage('#fullpage', {
		// 	menu: '#menu',
		// 	anchors: ['classic_candles', 'signature_wick', 'large_wax_melts', 'room-spray', 'essential-oils', 'metallic'],
		// 	sectionsColor: ['#FFF', '#fff', '#fff'],
		// 	autoScrolling: true,
		// 	licenseKey: 'OPEN-SOURCE-GPLV3-LICENSE',
		// });
	}
	
	jQuery(document).ready(function($){
		
		function updateCatalogLiveTotals() {
			var totalSum = 0;
			var totalQTY = 0;

			$('.summerTable tbody tr').each(function(){
				var $tr = $(this);
				var $qtyInput = $tr.find('input[name="quantity"]');
				var $checkbox = $tr.find('input.add');
				var rawQty = $qtyInput.val();
				var qnum = parseInt(rawQty, 10);
				var unitPrice = parseFloat($checkbox.attr('data-price')) || 0;

				if (!isNaN(qnum) && qnum > 0) {
					$checkbox.prop('checked', true);
					var lineTotal = (qnum * unitPrice).toFixed(2);
					$tr.find('.summer_price').text(lineTotal);
					totalQTY += qnum;
					totalSum += (qnum * unitPrice);
				} else {
					$checkbox.prop('checked', false);
					$tr.find('.summer_price').text(unitPrice.toFixed(2));
				}
			});

			$('#cst_summer_qty').html("No. of Product : " + totalQTY + " <br> Total Price: $" + totalSum.toFixed(2));
		}

		// Live quantity input and change handler
		$(document).on('input change keyup', '.summerTable input[name="quantity"], .input-text.qty', function(){
			updateCatalogLiveTotals();
		});

		// Checkbox toggle handler
		$(document).on('change', '.summerTable input.add', function(){
			var $tr = $(this).closest('tr');
			var $qty = $tr.find('input[name="quantity"]');
			if ($(this).is(':checked')) {
				if (!$qty.val() || parseInt($qty.val(), 10) <= 0) {
					$qty.val(1);
				}
			} else {
				$qty.val('');
			}
			updateCatalogLiveTotals();
		});

		// ADD ORDER TO CART click handler
		$(document).on("click", "#DataTables_Table_0_filter .catalogue-dynamic-popup-btn, .catalogue-dynamic-popup-btn", function(e)
		{
			e.preventDefault();
			var products = [];
			var quantities = [];
			var sellerName = $("#nSeller").val() || '';

			// Set the cookies wdm_add_item_data() reads, so a fundraiser's catalog
			// order is tagged wc_order_type = 'Catlog' (and the seller is captured).
			// Without a non-empty massSellerName cookie it defaults to 'Online'.
			if ( sellerName ) {
				var glowMaxAge = 30 * 24 * 60 * 60;
				var glowSellerVal = encodeURIComponent(sellerName);
				document.cookie = "massSellerName=" + glowSellerVal + "; path=/; max-age=" + glowMaxAge;
				document.cookie = "massSellerDisplay=" + glowSellerVal + "; path=/; max-age=" + glowMaxAge;
			}

			$('.summerTable tbody tr').each(function(){
				var $tr = $(this);
				var $qtyInput = $tr.find('input[name="quantity"]');
				var $checkbox = $tr.find('input.add');
				var pid = $checkbox.attr('data-product') || $qtyInput.attr('data-product');
				var rawQty = $qtyInput.val();
				var qnum = parseInt(rawQty, 10);

				if (pid && !isNaN(qnum) && qnum > 0) {
					products.push(pid);
					quantities.push({ id: pid, quantity: qnum, seller_name: sellerName });
				}
			});

			if (products.length === 0) {
				alert('Please enter a quantity for at least one product.');
				return;
			}

			var $btn = $(this);
			var origText = $btn.text();
			$btn.text('Adding to cart...').prop('disabled', true);

			var ajaxurl = "<?php echo esc_url( admin_url('admin-ajax.php') ); ?>";

			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: {
					action: 'quickster_add_to_cart',
					security: '<?php echo wp_create_nonce('quick_order'); ?>',
					products: products,
					quantities: quantities,
					seller_name: sellerName
				},
				dataType: 'json',
				success: function(response) {
					// Persist the chosen seller (and fundraiser) so checkout auto-selects it.
					if ( sellerName ) {
						$.post(ajaxurl, {
							action: 'save_fundraiser_session',
							popup_fundraiser_id: "<?php echo esc_js( ( is_user_logged_in() && in_array('fundraiser', (array) wp_get_current_user()->roles, true) ) ? get_current_user_id() : '' ); ?>",
							popup_fundraiser_name: sellerName
						});
					}
					$btn.text(origText).prop('disabled', false);
					$(document.body).trigger('wc_fragment_refresh');
					$(".seller-buyer-cookie-popup_new_onsite").addClass("show");
				},
				error: function() {
					$btn.text(origText).prop('disabled', false);
					$(document.body).trigger('wc_fragment_refresh');
					$(".seller-buyer-cookie-popup_new_onsite").addClass("show");
				}
			});
	    });

		// Modal button handlers
		$(document).on("click", ".seller-buyer-cookie-popup_new_onsite .another_order, .seller-buyer-cookie-popup_new_onsite .popup-close", function(e) {
			e.preventDefault();
			$(".seller-buyer-cookie-popup_new_onsite").removeClass("show");
			$(".summerTable input[name=quantity]").val('');
			$(".summerTable input[name=add]").prop('checked', false);
			$(".summerTable .summer_price").each(function(){
				var p = $(this).closest('tr').find('input.add').attr('data-price');
				if (p) $(this).text(p);
			});
			$("#cst_summer_qty").html("No. of Product : 0 <br> Total Price: $0.00");
			$("#nSeller").val('').focus();
		});

		// Seller name sync
		$(".mass-seller-name-new-test").on('input change', function(){
			var s = $(this).val();
			$(".seller-buyer-cookie-form .mass-seller-name-new").val(s);
		});

		// Bookmark smooth scroll
		$('.bookmark').click(function(event) {
			var targetSectionID = $(this).attr('href');
			var targetSection = $(targetSectionID);
			if (targetSection.length > 0) {
				$('html, body').animate({
					scrollTop: targetSection.offset().top - 120
				}, 600);
			}
		});

		// Back to top
		$("#back-top a").on("click", function(){
			$('html, body').animate({ scrollTop: 0 }, 600);
		});

		// Enter key advances to next row
		$('.summerTable input[type="number"]').keydown(function(event) {
			if (event.key === 'Enter') {
				event.preventDefault();
				var currentTd = $(this).closest('td');
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
<style>
.eWPMk,.jaRTKi{overflow:hidden}.eYvePi,.gouVSR,.successMSG{text-align:center}#myBtn{position:absolute;bottom:36px;right:76px;padding:11px 13px;z-index:9999}.successMSG{width:100%;display:inline-block;margin-bottom:20px;color:green}.eWPMk,.jXbIyY{display:flex;flex-direction:column}.dWJjwC,.gouVSR{color:#323232;font-style:normal}.dyPNnG{position:fixed;inset:0px;z-index:1000;background-color:rgba(255,255,255,.65)}.eWPMk{margin:200px auto;max-width:500px;height:460px;border-radius:15px;box-shadow:rgba(0,0,0,.55) 0 30px 50px 0;background-color:#fff;padding:75px 25px 50px;-webkit-box-pack:center;justify-content:center;-webkit-box-align:center;align-items:center;position:relative}.jXbIyY,.qXac{margin-bottom:50px}.NqDzw,.bhtBxK,button.close{position:absolute}.bhtBxK{width:300px;height:300px;background:#f8f8fa;border-radius:100%;top:-140px;left:-200px}.dWJjwC{z-index:1;line-height:1.49;font-weight:600;padding-bottom:10px;font-size:40px}.gouVSR{font-weight:400;padding-bottom:25px;font-size:16px;line-height:1.6}.jXbIyY,.tourNext.btn.btn-primary.btn-sm{font-style:normal;line-height:34px;font-weight:500;border-radius:5px;background-color:rgba(88,165,255,.15);color:#58a5ff}.eYvePi span,.gouVSR span{font-size:14px}.jXbIyY{font-size:17px;width:108px;height:50px;-webkit-box-pack:center;justify-content:center;-webkit-box-align:center;align-items:center;cursor:pointer}.fwIEMD{font-size:13px;font-weight:400;font-style:normal;line-height:21px;color:#8c8c8c;cursor:pointer}.NqDzw{width:300px;height:300px;background:#f2653a;border-radius:100%;bottom:-140px;right:-200px;opacity:.8}div#fullpage.tour-active{transform:translate3d(0,0,0)!important}.tourNext.btn.btn-primary.btn-sm{font-size:14px;flex-direction:column;-webkit-box-pack:center;justify-content:center;-webkit-box-align:center;align-items:center;cursor:pointer;padding:2px 20px;height:auto;border:0;text-transform:capitalize}.IwcWe,.tourPrev.btn.btn-default.btn-sm{font-style:normal;line-height:21px;cursor:pointer}.card-image{max-height:10%!important}.tourPrev.btn.btn-default.btn-sm{font-size:13px;font-weight:400;margin-right:20px;padding:0;text-transform:capitalize;border:0;color:#555;background:0 0}.panel-footer h6{margin:0;padding-top:11px;font-size:12px;color:#555}.popover{min-width:280px!important;padding:5px 0 0!important;border-radius:8px;box-shadow:rgba(0,0,0,.35) 0 3px 25px 0}.panel-footer{width:100%;display:inline-block;border-bottom-right-radius:8px;border-bottom-left-radius:8px}.etYwuz,.jeiGCo,.qXac{display:flex}.panel-body{color:#333;font-weight:700;letter-spacing:.1px;font-size:15px}button.close{right:5px;top:5px}.close:focus,.close:hover{color:#58a5ff;background:0 0}.endTourAnimate{-webkit-transition:bottom .5s;transition:bottom .5s;bottom:-401px}.jaRTKi{position:fixed;right:45px;z-index:1000;background-color:#fff;max-width:400px;border-top-left-radius:15px;border-top-right-radius:15px;border:1px solid #eee;padding:25px 25px 0;height:465px}.etYwuz{flex-direction:row;-webkit-box-align:center;align-items:center;-webkit-box-pack:justify;justify-content:space-between}.IwcWe{font-size:13px;font-weight:400;color:#58a5ff}.dYBfUp,.eYvePi{color:#323232;font-style:normal}.joQcpv{padding-left:200px;cursor:pointer}.jeiGCo{flex-direction:column;-webkit-box-align:center;align-items:center}.dYBfUp{font-weight:600;line-height:52px;padding-top:25px;padding-bottom:10px;font-size:30px}.eYvePi{font-weight:400;font-size:16px;line-height:1.6;padding-bottom:26px}.qXac{height:50px;border-radius:5px;width:225px;background-color:rgba(88,165,255,.15);color:#58a5ff;flex-direction:column;-webkit-box-pack:center;justify-content:center;-webkit-box-align:center;align-items:center;z-index:1;cursor:pointer}.fQiUEz,.hyMzIi{position:absolute;width:300px;height:300px;border-radius:100%}.hyMzIi{background:#f8f8fa;top:360px;left:-180px}.fQiUEz{background:#f2653a;bottom:-270px;opacity:.4}#root-2{display:none}.endTourAnimate.isOpen{bottom:0}
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
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets_tour/js/dknotus-tour.js"></script>
<script>
jQuery(function($){
	var ajaxurl="<?php echo esc_url( admin_url('admin-ajax.php') ); ?>";
	$('.sc-dEoRIm.joQcpv').on('click', function(){
		if($("#root-2 .endTourAnimate").hasClass("isOpen")) {
			$("#root-2 .endTourAnimate").removeClass("isOpen");
		} else {
			$("#root-2 .endTourAnimate").addClass("isOpen");
		}
	});

	$('.fwIEMD').on('click', function(){
		$("#root").css("display","none");
		$('#root-2').css("display","block");
		var skip_popup="yes";
		var id="<?php if(!empty($u_id)) { echo $u_id; } ?>";
		$.ajax({
			type : "post",
			url : ajaxurl,
			data : {action: 'wdm_welcome_pop_up_skip', skip_popup_user_id:id,skip_popup:skip_popup}
		});
	});

	$('#positionsBtn').on("click", function(){
		$('#fullpage').addClass("tour-active");
		$("#root").css("display","none");
		Tour.run([
			{ element: $('#menu'), content: 'This is the category menu. Click here to move into different category sections.', position: 'bottom' },
			{ element: $('#nSeller'), content: 'Enter each sellers name here.', position: 'left' },
			{ element: $('#fullpage #section0 #selectbox'), content: 'Check this box to select products.', position: 'bottom' },
			{ element: $('#fullpage #section0 #qtybox'), content: 'Enter quantity of product.', position: 'bottom' },
			{ element: $('#DataTables_Table_0_filter'), content: 'Click Add Order to Cart button to complete each sellers order.', position: 'left' }
		]);
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
		/* Always have wp_footer() just before the closing </body>
		 * tag of your theme, or you will break many plugins, which
		 * generally use this hook to reference JavaScript files.
		 */

		get_footer();
	?>
