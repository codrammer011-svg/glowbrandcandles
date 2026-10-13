<?php
/*
* Template Name: fundraiser_summer_glinter
*/

 //get_header(); ?>
<style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
		
        .main-header{
            background-color: #dddf8f;
            width: 100%;
            padding: 15px 30px;
			position: fixed;
			top: 0px;
			z-index:10;
            }
			
        .main-header .lists{
			padding-top: 30px;
			padding-bottom: 15px;
            list-style: none;
            display: flex;
            align-items: center;
            justify-content: space-evenly;
			}
			
        .main-header .lists .list-items a{
            text-decoration: none;
            color: #000;
            font-size: 15px;
            font-family: sans-serif;
            font-weight: 600;
            text-transform: uppercase;
			}
			
       /* Classic Candles */
	   
       .classic-candles{
			width: 100%;
			height: auto;
			background-color: #eeeeee;
			padding: 50px 20px 5px 20px;
			}
			
       .main-heading h2{
			margin-top: 40px;
			font-size: 25px;
			font-family: sans-serif;
			font-weight: 600;
			text-align: center;
			text-transform: uppercase;
			}
			
       .under-line{
			width: 140px;
			height: 2px;
			background-color: #000;
			margin: 5px auto;
			}
			
       .d-flex{
			display: flex;
			align-items: center;
			justify-content: space-evenly;
			margin: 45px 70px;
			}
			
       .main-candles{
			position: relative;
			width: 199px;
			}

       .main-candles .image img{
			width: 200px;
			}
			
       .main-candles .heading{
			display: inline-block;
			position: absolute;
			top: 165px;
			width: 100%;
			}
			
       .main-candles .heading h4{
			font-size: 14px;
            text-align: center;
            font-weight: 600;
			}
			
       .main-candles .quantity{
            background-color: #fff;
            display: flex;
            justify-content: space-between;
            width: 100%;
            align-items: center;
            margin-top: -3px;
            border: 1px solid #000;
			}
			
       .main-candles .quantity span{
            display: inline-block;
            width: 25px;
            background: #ccc;
            text-align: center;
            font-weight: 800;
			}
			
       .main-candles .add-cart{
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #86c5a1;
            height: 35px;
			}
			
       .main-candles .add-cart h5{
            font-size: 12px;
            margin-left: 5px;
            font-family: sans-serif;
            font-weight: 600;
			}
			
    </style>	
 <!--Main Header menu -->
	<?php  
	
	$category_ids = array(152, 153, 154, 112, 110, 122);
	$category_names = array();

	foreach ($category_ids as $category_id) {
		$term = get_term($category_id, 'product_cat');
		
		if (!is_wp_error($term) && $term) {
			$category_names[] = $term->name;
		} else {
			$category_names[] = 'Category not found';
		}
	}
	
		
		$summer_category = array(
								"classic_candles" 	=> "Classic Candles",
								"sig_2_wick" 		=> "Signature 2 Wick Candles",
								"large_wax" 		=> "Large Wax Melts",
								"room_sprays" 		=> "Room Sprays",
								"essential_oils" 	=> "Essential Oils",
								"boxed_candles" 	=> "Boxed Candles",
							);
	?>
    <div class="main-header">
        <ul class="lists">
			<?php
				foreach($summer_category as $sum_cat => $cat){
					echo '<li class="list-items"><a href="#'.$sum_cat.'">'.$cat.'</a></li>';
				}
			?>
        </ul>
    </div>
	
    	
	
	<?php foreach($summer_category as $sum_cat => $cat){ ?>
		<Section class="classic-candles" id="<?php echo $sum_cat; ?>">
			<div class="main-heading">
				<h2><?php echo $cat; ?></h2>
			</div>
			<div class="under-line"></div>
			<?php for ($x = 1; $x <= 2; $x++) { ?> 
				<div class="d-flex">
					<?php for ($y = 1; $y <= 5; $y++) { ?> 
						<div class="main-candles">
							<div class="image">
							   <img src="https://glowscentedcandles.com/wp-content/uploads/2024/02/GLOW_MELT_GOLDEN-SUMMER-GLOW-01.png" alt="classic">
							</div>
							<div class="heading">
								<h4>CC1 Eucalyptus Candle</h4>
							</div>
							<div class="quantity">
							   <span>-</span><h5>1</h5><span>+</span>
							</div>
							
							<div class="add-cart">
								<span><img src="https://glowscentedcandles.com/wp-content/uploads/2024/04/cart-regular-24.png" alt=""></span><h5>ADD TO CART</h5>
							</div>
						</div>
					<?php }	?>
				</div>
			<?php }	?>		
		</Section>
	<?php }	?>


<?php
	foreach($category_names as $cat_name){
		$args = array(
					'post_type' => 'product',
					'posts_per_page' => -1,
					'product_cat' => $cat_name,
					'post_status' => 'publish',
				);

		$query = new WP_Query($args);
		$products_details = array();
		if ($query->have_posts()) : 
			while ($query->have_posts()) : $query->the_post();
				global $product;
				
				// Get the product details
				$product_data = array(
					'name'    => $product->get_name(),
					'sku'     => $product->get_sku(),
					'title'   => get_the_title(),
					'image'   => get_the_post_thumbnail_url($product->get_id(), 'full')
				);

				// Store product details in array
				$products_details[] = $product_data;

			endwhile;
			wp_reset_postdata();
		endif;
		
		echo '<br><b>'.$cat_name.'</b> , no.of element  : '.count($products_details).'<br>';
		foreach($products_details as $pro_det){
			$product_title = strip_tags($pro_det[title]);
			echo '<br>Title : '. $product_title;
			echo '<br>SKU : '. $pro_det[sku];
			echo '<br>Image URL : '. $pro_det[image];
			echo '<br>';
		}
	}
	
	
	echo '<br><br><h4> Categories Name </h4>';
	foreach($category_names as $cat_name){
				echo $cat_name.'<br>';				
		}
		
	


?>