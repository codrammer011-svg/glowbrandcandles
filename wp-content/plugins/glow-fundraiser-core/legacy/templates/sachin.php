<?php
/**
 * Template Name: sachin Catalogue Design
 */
 //print_r("okay");
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
 else
 {
	 
 }

?>
<style>
#DataTables_Table_0 tbody tr[role='row'] {
    width: 19.5% !important;
}
.add-to-cart-Btn
{
	right: 90px;
}
</style>
<ul id="menu">
			<li data-menuanchor="hexagon" class="active"><a href="#hexagon">Hexagon</a></li>
			<li data-menuanchor="blacklid"><a href="#blacklid">16OZ Jar Candles</a></li>
			<li data-menuanchor="room-spray"><a href="#room-spray">Room Sprays</a></li>
			<li data-menuanchor="melts"><a href="#melts">Melts</a></li>
			<li data-menuanchor="essential-oils"><a href="#essential-oils">Essential Oils</a></li>
			<li data-menuanchor="metallic"><a href="#metallic">Metallics</a></li>
	</ul>
	<div id="DataTables_Table_0_filter" class="dataTables_filter add-to-cart-Btn"><input id="nSeller" type="text" name="mass-seller-name-new-test" value="" style=" position: absolute;top: 0;left: -150px;" class="mass-seller-name-new-test" placeholder="Seller Name"></div>
</div>
<script type='text/javascript' src='<?php echo esc_url( glow_dynamic_url_base('content') ); ?>plugins/quick-orders-for-woocommerce/assets/public/datatables/datatables.min.js'></script>
<div id="fullpage">
	<!-- Hexagon Catagory Product-->
	<div class="section" id="section0">
        <div class="slide" id="slide1">
		<?php 
		$ids= array(22425,22413,37580,22423,22417,22426,37588,22419,37590,37592);
		$item_no=array("H1","H2","H3","H4","H5","H6","H7","H8","H9","H10");
	$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'post__in',
	'product_cat' => esc_html( 'Hexagon Jars' ),
	'post__in' => $ids
	);
	$table_query= new WP_Query();
	$table_query->query($arg);
		?>
		 
		<div class="container page-content sidebar-position-without sidebar-mobile-bottom">
			<div class="row">

				<div class="content col-md-12">	
		<div id="<?php echo $category;?>">
			<!--<div id="DataTables_Table_0_filter" class="dataTables_filter"></div>-->
				<table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 1140px;">
				  <thead style="display:block;">
					<tr style="width: 100%;margin: 0;" role="row" >
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php  echo "10OZ. HEXAGON CANDLES - BURN 70+ HOURS"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$15.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Summer Catalogue', 'product_cat', $postid )){
					?>
					<tr role="row" class="odd" data-product-name="<?php the_title();?>">
						<td tabindex="0" class="sorting_1">
							<?php if ( has_post_thumbnail( $postid ) ) {
											$attachment_ids[0] = get_post_thumbnail_id( $postid );
											 $attachment = wp_get_attachment_image_src($attachment_ids[0], 'full' ); ?>    
											<img src="<?php echo $attachment[0] ; ?>" class="card-image"  />
										<?php } else { ?>
										 <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" class="card-image"  />
										<?php } ?>
						</td>
						<td><input <?php if($postid==22413){ ?> id="selectbox" <?php } ?> name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;"></td>
						<td><input <?php if($postid==22413){ ?> id="qtybox" <?php } ?> type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric"></td>
					<td><span class="item_no"><?php echo $item_no[$i]; ?></span><?php the_title();?></td>
					<!--<td><span class="price" data-product="<?php echo $postid; ?>"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $price; ?></span></span></td>-->
					</tr>
					<?php 
					}?>
					<?php
					$i++;
					endwhile;
					endif;

					?>
					</tbody>
				</table>
		</div>
		
		</div>
		</div>
		</div>
		
		</div>
		
		 <div class="slide" id="slide2">
		<?php 
		$ids= array(37575,32893,22440,32891,37578,22447,22460,37634,22455,37621);
		$item_no=array("H11","H12","H13","H14","H15","H16","H17","H18","H19","H20");
	$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'post__in',
	'product_cat' => esc_html( 'Hexagon Jars' ),
	'post__in' => $ids
	);
	$table_query= new WP_Query();
	$table_query->query($arg);
		?>
		 <div class="container page-content sidebar-position-without sidebar-mobile-bottom">
			<div class="row">

				<div class="content col-md-12"> 
		<div id="<?php echo $category;?>">
			<!--<div id="DataTables_Table_0_filter" class="dataTables_filter"></div>-->
				<table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 1140px;">
				  <thead style="display:block;">
					<tr style="width: 100%;margin: 0;" role="row" >
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "10OZ. HEXAGON CANDLES - BURN 70+ HOURS"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$15.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Summer Catalogue', 'product_cat', $postid )){
					?>
					<tr role="row" class="odd" data-product-name="<?php the_title();?>">
						<td tabindex="0" class="sorting_1">
							<?php if ( has_post_thumbnail( $postid ) ) {
											$attachment_ids[0] = get_post_thumbnail_id( $postid );
											 $attachment = wp_get_attachment_image_src($attachment_ids[0], 'full' ); ?>    
											<img src="<?php echo $attachment[0] ; ?>" class="card-image"  />
										<?php } else { ?>
										 <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" class="card-image"  />
										<?php } ?>
						</td>
						<td><input name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;"></td>
						<td><input type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric"></td>
					<td><span class="item_no"><?php echo $item_no[$i]; ?></span><?php the_title();?></td>
					<!--<td><span class="price" data-product="<?php echo $postid; ?>"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $price; ?></span></span></td>-->
					</tr>
					<?php 
					}?>
					<?php
					$i++;
					endwhile;
					endif;

					?>
					</tbody>
				</table>
		</div>
		
		</div>
		</div>
		</div>
		
		</div>
		
		<div class="slide" id="slide3">
		<?php 
		$ids= array(22468,37623);
		$item_no=array("H21","H22");
	$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'post__in',
	'product_cat' => esc_html( 'Hexagon Jars' ),
	'post__in' => $ids
	);
	$table_query= new WP_Query();
	$table_query->query($arg);
		?>
		 <div class="container page-content sidebar-position-without sidebar-mobile-bottom">
			<div class="row">

				<div class="content col-md-12"> 
		<div id="<?php echo $category;?>">
			<!--<div id="DataTables_Table_0_filter" class="dataTables_filter"></div>-->
				<table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 1140px;">
				  <thead style="display:block;">
					<tr style="width: 100%;margin: 0;" role="row" >
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "10OZ. HEXAGON CANDLES - BURN 70+ HOURS"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$15.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Summer Catalogue', 'product_cat', $postid )){
					?>
					<tr role="row" class="odd" data-product-name="<?php the_title();?>">
						<td tabindex="0" class="sorting_1">
							<?php if ( has_post_thumbnail( $postid ) ) {
											$attachment_ids[0] = get_post_thumbnail_id( $postid );
											 $attachment = wp_get_attachment_image_src($attachment_ids[0], 'full' ); ?>    
											<img src="<?php echo $attachment[0] ; ?>" class="card-image"  />
										<?php } else { ?>
										 <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" class="card-image"  />
										<?php } ?>
						</td>
						<td><input name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;"></td>
						<td><input type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric"></td>
					<td><span class="item_no"><?php echo $item_no[$i]; ?></span><?php the_title();?></td>
					<!--<td><span class="price" data-product="<?php echo $postid; ?>"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $price; ?></span></span></td>-->
					</tr>
					<?php 
					}?>
					<?php
					$i++;
					endwhile;
					endif;

					?>
					</tbody>
				</table>
		</div>
		
		</div>
		</div>
		</div>
		
		</div>
		
		
	</div>
	<!-- End Hexagon Catagory Product-->
	
	<!-- 16 oz JARS Catagory Product-->
	
	<div class="section section-wrap" id="section1">
       <div class="slide" id="slide1">
		<?php 
		$ids= array(32835,32833,37642,22485,32839,32837,37672,32844,37658,37681);
		$item_no=array("J23","J24","J25","J26","J27","J28","J29","J30","J31","J32");
	$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'post__in',
	'post__in' => $ids
	);
	$table_query= new WP_Query();
	$table_query->query($arg);
		?>
		 <div class="container page-content sidebar-position-without sidebar-mobile-bottom">
			<div class="row">

				<div class="content col-md-12"> 
		<div id="<?php echo $category;?>">
			<!--<div id="DataTables_Table_0_filter" class="dataTables_filter"></div>-->
				<table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 1140px;">
				  <thead style="display:block;">
					<tr style="width: 100%;margin: 0;" role="row" >
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "16 oz. Jar Candles burn 100+ hours"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$22.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Summer Catalogue', 'product_cat', $postid )){
					?>
					<tr role="row" class="odd" data-product-name="<?php the_title();?>">
						<td tabindex="0" class="sorting_1">
							<?php if ( has_post_thumbnail( $postid ) ) {
											$attachment_ids[0] = get_post_thumbnail_id( $postid );
											 $attachment = wp_get_attachment_image_src($attachment_ids[0], 'full' ); ?>    
											<img src="<?php echo $attachment[0] ; ?>" class="card-image"  />
										<?php } else { ?>
										 <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" class="card-image"  />
										<?php } ?>
						</td>
						<td><input name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;"></td>
						<td><input type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric"></td>
					<td><span class="item_no"><?php echo $item_no[$i]; ?></span><?php the_title();?></td>
					<!--<td><span class="price" data-product="<?php echo $postid; ?>"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $price; ?></span></span></td>-->
					</tr>
					<?php 
					}?>
					<?php
					$i++;
					endwhile;
					endif;

					?>
					</tbody>
				</table>
		</div>
		
		</div>
		</div>
		</div>
		
		</div>
		
		<div class="slide" id="slide2">
		<?php 
		$ids= array(37674,32886,37683,32848,37689,22493,22501,32850,22504,37691 );
		$item_no=array("J33","J34","J35","J36","J37","J38","J39","J40","J41","J42");
	$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'post__in',
	'post__in' => $ids
	);
	$table_query= new WP_Query();
	$table_query->query($arg);
		?>
		 <div class="container page-content sidebar-position-without sidebar-mobile-bottom">
			<div class="row">

				<div class="content col-md-12"> 
		<div id="<?php echo $category;?>">
			<!--<div id="DataTables_Table_0_filter" class="dataTables_filter"></div>-->
				<table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 1140px;">
				  <thead style="display:block;">
					<tr style="width: 100%;margin: 0;" role="row" >
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "16 oz. Jar Candles burn 100+ hours"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$22.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Summer Catalogue', 'product_cat', $postid )){
					?>
					<tr role="row" class="odd" data-product-name="<?php the_title();?>">
						<td tabindex="0" class="sorting_1">
							<?php if ( has_post_thumbnail( $postid ) ) {
											$attachment_ids[0] = get_post_thumbnail_id( $postid );
											 $attachment = wp_get_attachment_image_src($attachment_ids[0], 'full' ); ?>    
											<img src="<?php echo $attachment[0] ; ?>" class="card-image"  />
										<?php } else { ?>
										 <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" class="card-image"  />
										<?php } ?>
						</td>
						<td><input name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;"></td>
						<td><input type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric"></td>
					<td><span class="item_no"><?php echo $item_no[$i]; ?></span><?php the_title();?></td>
					<!--<td><span class="price" data-product="<?php echo $postid; ?>"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $price; ?></span></span></td>-->
					</tr>
					<?php 
					}?>
					<?php
					$i++;
					endwhile;
					endif;

					?>
					</tbody>
				</table>
		</div>
		
		</div>
		</div>
		</div>
		
		</div>
		<div class="slide" id="slide3">
		<?php 
		$ids= array(32852,37693);
		$item_no=array("J43","J44");
	$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'post__in',
	'post__in' => $ids
	);
	$table_query= new WP_Query();
	$table_query->query($arg);
		?>
		 <div class="container page-content sidebar-position-without sidebar-mobile-bottom">
			<div class="row">

				<div class="content col-md-12"> 
		<div id="<?php echo $category;?>">
			<!--<div id="DataTables_Table_0_filter" class="dataTables_filter"></div>-->
				<table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 1140px;">
				  <thead style="display:block;">
					<tr style="width: 100%;margin: 0;" role="row" >
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "16 oz. Jar Candles burn 100+ hours"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$22.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Summer Catalogue', 'product_cat', $postid )){
					?>
					<tr role="row" class="odd" data-product-name="<?php the_title();?>">
						<td tabindex="0" class="sorting_1">
							<?php if ( has_post_thumbnail( $postid ) ) {
											$attachment_ids[0] = get_post_thumbnail_id( $postid );
											 $attachment = wp_get_attachment_image_src($attachment_ids[0], 'full' ); ?>    
											<img src="<?php echo $attachment[0] ; ?>" class="card-image"  />
										<?php } else { ?>
										 <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" class="card-image"  />
										<?php } ?>
						</td>
						<td><input name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;"></td>
						<td><input type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric"></td>
					<td><span class="item_no"><?php echo $item_no[$i]; ?></span><?php the_title();?></td>
					<!--<td><span class="price" data-product="<?php echo $postid; ?>"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $price; ?></span></span></td>-->
					</tr>
					<?php 
					}?>
					<?php
					$i++;
					endwhile;
					endif;

					?>
					</tbody>
				</table>
		</div>
		
		</div>
		</div>
		</div>
		
		</div>
    </div>
	<!-- End 16oz Jar Candles Catagory Product-->
	
	<!-- room-spray Catagory Product-->
	<div class="section section-wrap" id="section2">
        <div class="slide" id="slide1">
		<?php 
		$ids= array(32790,32787,37714,22983,32796,37715,37717,22525,37722,37724);
		$item_no=array("RS45","RS46","RS47","RS48","RS49","RS50","RS51","RS52","RS53","RS54");
	$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'post__in',
	'product_cat' => esc_html( 'room-spray' ),
	'post__in' => $ids
	);
	$table_query= new WP_Query();
	$table_query->query($arg);
		?>
		 
		<div class="container page-content sidebar-position-without sidebar-mobile-bottom">
			<div class="row">

				<div class="content col-md-12">	
		<div id="<?php echo $category;?>">
			<!--<div id="DataTables_Table_0_filter" class="dataTables_filter"></div>-->
				<table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 1140px;">
				  <thead style="display:block;">
					<tr style="width: 100%;margin: 0;" role="row" >
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php  echo "4 fl. oz. Room Sprays"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$14.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Summer Catalogue', 'product_cat', $postid )){
					?>
					<tr role="row" class="odd" data-product-name="<?php the_title();?>">
						<td tabindex="0" class="sorting_1">
							<?php if ( has_post_thumbnail( $postid ) ) {
											$attachment_ids[0] = get_post_thumbnail_id( $postid );
											 $attachment = wp_get_attachment_image_src($attachment_ids[0], 'full' ); ?>    
											<img src="<?php echo $attachment[0] ; ?>" class="card-image"  />
										<?php } else { ?>
										 <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" class="card-image"  />
										<?php } ?>
						</td>
						<td><input name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;"></td>
						<td><input type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric"></td>
					<td><span class="item_no"><?php echo $item_no[$i]; ?></span><?php the_title();?></td>
					<!--<td><span class="price" data-product="<?php echo $postid; ?>"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $price; ?></span></span></td>-->
					</tr>
					<?php 
					}?>
					<?php
					$i++;
					endwhile;
					endif;

					?>
					</tbody>
				</table>
		</div>
		
		</div>
		</div>
		</div>
		
		</div>
		
		 <div class="slide" id="slide2">
		<?php 
		$ids= array(37726,22527,22530,32781,37730,22985,22531,32783,22520,37748 );
		$item_no=array("RS55","RS56","RS57","RS58","RS59","RS60","RS61","RS62","RS63","RS64");
	$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'post__in',
	'product_cat' => esc_html( 'room-spray' ),
	'post__in' => $ids
	);
	$table_query= new WP_Query();
	$table_query->query($arg);
		?>
		 <div class="container page-content sidebar-position-without sidebar-mobile-bottom">
			<div class="row">

				<div class="content col-md-12"> 
		<div id="<?php echo $category;?>">
			<!--<div id="DataTables_Table_0_filter" class="dataTables_filter"></div>-->
				<table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 1140px;">
				  <thead style="display:block;">
					<tr style="width: 100%;margin: 0;" role="row" >
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "4 fl. oz. Room Sprays"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$14.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Summer Catalogue', 'product_cat', $postid )){
					?>
					<tr role="row" class="odd" data-product-name="<?php the_title();?>">
						<td tabindex="0" class="sorting_1">
							<?php if ( has_post_thumbnail( $postid ) ) {
											$attachment_ids[0] = get_post_thumbnail_id( $postid );
											 $attachment = wp_get_attachment_image_src($attachment_ids[0], 'full' ); ?>    
											<img src="<?php echo $attachment[0] ; ?>" class="card-image"  />
										<?php } else { ?>
										 <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" class="card-image"  />
										<?php } ?>
						</td>
						<td><input name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;"></td>
						<td><input type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric"></td>
					<td><span class="item_no"><?php echo $item_no[$i]; ?></span><?php the_title();?></td>
					<!--<td><span class="price" data-product="<?php echo $postid; ?>"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $price; ?></span></span></td>-->
					</tr>
					<?php 
					}?>
					<?php
$i++;
					endwhile;
					endif;

					?>
					</tbody>
				</table>
		</div>
		
		</div>
		</div>
		</div>
		
		</div>
		 <div class="slide" id="slide3">
		<?php 
		$ids= array(32798,37751);
		$item_no=array("RS65","RS66");
	$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'post__in',
	'product_cat' => esc_html( 'room-spray' ),
	'post__in' => $ids
	);
	$table_query= new WP_Query();
	$table_query->query($arg);
		?>
		 <div class="container page-content sidebar-position-without sidebar-mobile-bottom">
			<div class="row">

				<div class="content col-md-12"> 
		<div id="<?php echo $category;?>">
			<!--<div id="DataTables_Table_0_filter" class="dataTables_filter"></div>-->
				<table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 1140px;">
				  <thead style="display:block;">
					<tr style="width: 100%;margin: 0;" role="row" >
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "4 fl. oz. Room Sprays"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$14.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Summer Catalogue', 'product_cat', $postid )){
					?>
					<tr role="row" class="odd" data-product-name="<?php the_title();?>">
						<td tabindex="0" class="sorting_1">
							<?php if ( has_post_thumbnail( $postid ) ) {
											$attachment_ids[0] = get_post_thumbnail_id( $postid );
											 $attachment = wp_get_attachment_image_src($attachment_ids[0], 'full' ); ?>    
											<img src="<?php echo $attachment[0] ; ?>" class="card-image"  />
										<?php } else { ?>
										 <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" class="card-image"  />
										<?php } ?>
						</td>
						<td><input name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;"></td>
						<td><input type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric"></td>
					<td><span class="item_no"><?php echo $item_no[$i]; ?></span><?php the_title();?></td>
					<!--<td><span class="price" data-product="<?php echo $postid; ?>"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $price; ?></span></span></td>-->
					</tr>
					<?php 
					}?>
					<?php
$i++;
					endwhile;
					endif;

					?>
					</tbody>
				</table>
		</div>
		
		</div>
		</div>
		</div>
		
		</div>
		
		
	</div>
	<!-- End room-spray Catagory Product-->
	
	<!-- melts -->
	 <div class="section section-wrap" id="section3">
	  <div class="slide" id="slide1">
        <?php 
		$ids= array(32815,32813,37770,22976,24412,32819,37775,32821,37776,37782);
		$item_no_n=array("LM67","LM68","LM69","LM70","LM71","LM72","LM73","LM74","LM75","LM76");
		$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'post__in',
	'product_cat' => esc_html( 'Melts' ),
	'post__in' => $ids
	);
	$table_query= new WP_Query();
	$table_query->query($arg);
		?>
		 <div class="container page-content sidebar-position-without sidebar-mobile-bottom">
			<div class="row">

				<div class="content col-md-12"> 
		<div id="<?php echo $category;?>">
			<!--<div id="DataTables_Table_0_filter" class="dataTables_filter"></div>-->
				<table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 1140px;">
				  <thead style="display:block;">
					<tr style="width: 100%;margin: 0;" role="row" >
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "6 Large Cube Melts 120+ hours of aroma"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$13.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i_n=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Summer Catalogue', 'product_cat', $postid )){
					?>
					<tr role="row" class="odd" data-product-name="<?php the_title();?>">
						<td tabindex="0" class="sorting_1">
							<?php if ( has_post_thumbnail( $postid ) ) {
											$attachment_ids[0] = get_post_thumbnail_id( $postid );
											 $attachment = wp_get_attachment_image_src($attachment_ids[0], 'full' ); ?>    
											<img src="<?php echo $attachment[0] ; ?>" class="card-image"  />
										<?php } else { ?>
										 <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" class="card-image"  />
										<?php } ?>
						</td>
						<td><input name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;"></td>
						<td><input type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric"></td>
					<td><span class="item_no"><?php echo $item_no_n[$i_n]; ?></span><?php the_title();?></td>
					<!--<td><span class="price" data-product="<?php echo $postid; ?>"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $price; ?></span></span></td>-->
					</tr>
					<?php 
					$i_n++;
					}?>
					<?php
					
					endwhile;
					endif;

					?>
					</tbody>
				</table>
		</div>
		
		</div>
		</div>
		</div>
		</div>
		 <div class="slide" id="slide2">
        <?php 
		$ids= array(37763,22569,22570,32811,37788,22568,22572,22564,22562,37765);
		$item_no_n=array("LM77","LM78","LM79","LM80","LM81","LM82","LM83","LM84","LM85","LM86");
		$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'post__in',
	'product_cat' => esc_html( 'Melts' ),
	'post__in' => $ids
	);
	$table_query= new WP_Query();
	$table_query->query($arg);
		?>
		 <div class="container page-content sidebar-position-without sidebar-mobile-bottom">
			<div class="row">

				<div class="content col-md-12"> 
		<div id="<?php echo $category;?>">
			<!--<div id="DataTables_Table_0_filter" class="dataTables_filter"></div>-->
				<table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 1140px;">
				  <thead style="display:block;">
					<tr style="width: 100%;margin: 0;" role="row" >
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "6 Large Cube Melts 120+ hours of aroma"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$13.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i_n=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Summer Catalogue', 'product_cat', $postid )){
					?>
					<tr role="row" class="odd" data-product-name="<?php the_title();?>">
						<td tabindex="0" class="sorting_1">
							<?php if ( has_post_thumbnail( $postid ) ) {
											$attachment_ids[0] = get_post_thumbnail_id( $postid );
											 $attachment = wp_get_attachment_image_src($attachment_ids[0], 'full' ); ?>    
											<img src="<?php echo $attachment[0] ; ?>" class="card-image"  />
										<?php } else { ?>
										 <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" class="card-image"  />
										<?php } ?>
						</td>
						<td><input name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;"></td>
						<td><input type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric"></td>
					<td><span class="item_no"><?php echo $item_no_n[$i_n]; ?></span><?php the_title();?></td>
					<!--<td><span class="price" data-product="<?php echo $postid; ?>"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $price; ?></span></span></td>-->
					</tr>
					<?php 
					$i_n++;
					}?>
					<?php
					
					endwhile;
					endif;

					?>
					</tbody>
				</table>
		</div>
		
		</div>
		</div>
		</div>
		</div>
		 <div class="slide" id="slide3">
        <?php 
		$ids= array(24413,37785);
		$item_no_n=array("LM87","LM88");
		$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'post__in',
	'product_cat' => esc_html( 'Melts' ),
	'post__in' => $ids
	);
	$table_query= new WP_Query();
	$table_query->query($arg);
		?>
		 <div class="container page-content sidebar-position-without sidebar-mobile-bottom">
			<div class="row">

				<div class="content col-md-12"> 
		<div id="<?php echo $category;?>">
			<!--<div id="DataTables_Table_0_filter" class="dataTables_filter"></div>-->
				<table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 1140px;">
				  <thead style="display:block;">
					<tr style="width: 100%;margin: 0;" role="row" >
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "6 Large Cube Melts 120+ hours of aroma"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$13.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i_n=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Summer Catalogue', 'product_cat', $postid )){
					?>
					<tr role="row" class="odd" data-product-name="<?php the_title();?>">
						<td tabindex="0" class="sorting_1">
							<?php if ( has_post_thumbnail( $postid ) ) {
											$attachment_ids[0] = get_post_thumbnail_id( $postid );
											 $attachment = wp_get_attachment_image_src($attachment_ids[0], 'full' ); ?>    
											<img src="<?php echo $attachment[0] ; ?>" class="card-image"  />
										<?php } else { ?>
										 <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" class="card-image"  />
										<?php } ?>
						</td>
						<td><input name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;"></td>
						<td><input type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric"></td>
					<td><span class="item_no"><?php echo $item_no_n[$i_n]; ?></span><?php the_title();?></td>
					<!--<td><span class="price" data-product="<?php echo $postid; ?>"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $price; ?></span></span></td>-->
					</tr>
					<?php 
					$i_n++;
					}?>
					<?php
					
					endwhile;
					endif;

					?>
					</tbody>
				</table>
		</div>
		
		</div>
		</div>
		</div>
		</div>
    </div>
	
	
	<!-- Essential Oils -->
	 <div class="section section-wrap" id="section4">
	 <div class="slide" id="slide1">
        <?php 
		$ids= array(23002,23011,23016,23014);
		$item_no_n=array("EO89","EO90","EO91","EO92");
		$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'post__in',
	'product_cat' => esc_html( 'Essential Oils' ),
	'post__in' => $ids
	);
	$table_query= new WP_Query();
	$table_query->query($arg);
		?>
		 <div class="container page-content sidebar-position-without sidebar-mobile-bottom">
			<div class="row">

				<div class="content col-md-12"> 
		<div id="<?php echo $category;?>">
			<!--<div id="DataTables_Table_0_filter" class="dataTables_filter"></div>-->
				<table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 1140px;">
				  <thead style="display:block;">
					<tr style="width: 100%;margin: 0;" role="row" >
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo ".5 fl. oz. Essential Oils"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$15.00 /singles"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i_n=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Summer Catalogue', 'product_cat', $postid )){
					?>
					<tr role="row" class="odd" data-product-name="<?php the_title();?>">
						<td tabindex="0" class="sorting_1">
							<?php if ( has_post_thumbnail( $postid ) ) {
											$attachment_ids[0] = get_post_thumbnail_id( $postid );
											 $attachment = wp_get_attachment_image_src($attachment_ids[0], 'full' ); ?>    
											<img src="<?php echo $attachment[0] ; ?>" class="card-image"  />
										<?php } else { ?>
										 <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" class="card-image"  />
										<?php } ?>
						</td>
						<td><input name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;"></td>
						<td><input type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric"></td>
					<td><span class="item_no"><?php echo $item_no_n[$i_n]; ?></span><?php the_title();?></td>
					<!--<td><span class="price" data-product="<?php echo $postid; ?>"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $price; ?></span></span></td>-->
					</tr>
					<?php 
					$i_n++;
					}?>
					<?php
					
					endwhile;
					endif;

					?>
					</tbody>
				</table>
		</div>
		
		</div>
		</div>
		</div>
		</div>
		
    </div>
	<!-- Metalic -->
	 <div class="section section-wrap" id="section5">
	 <div class="slide" id="slide1">
        <?php 
		$ids= array(37706,37708,37710,37711);
		$item_no=array("BM93","BM94","BM95","BM96");
		$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'post__in',
	'product_cat' => esc_html( 'boxed-metallic' ),
	'post__in' => $ids
	);
	$table_query= new WP_Query();
	$table_query->query($arg);
		?>
		 <div class="container page-content sidebar-position-without sidebar-mobile-bottom">
			<div class="row">

				<div class="content col-md-12"> 
		<div id="<?php echo $category;?>">
			<!--<div id="DataTables_Table_0_filter" class="dataTables_filter"></div>-->
				<table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 1140px;">
				  <thead style="display:block;">
					<tr style="width: 100%;margin: 0;" role="row" >
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "16 oz Boxed Metallics 110+ hours of aroma"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$38.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Summer Catalogue', 'product_cat', $postid )){
					?>
					<tr role="row" class="odd" data-product-name="<?php the_title();?>">
						<td tabindex="0" class="sorting_1">
							<?php if ( has_post_thumbnail( $postid ) ) {
											$attachment_ids[0] = get_post_thumbnail_id( $postid );
											 $attachment = wp_get_attachment_image_src($attachment_ids[0], 'full' ); ?>    
											<img src="<?php echo $attachment[0] ; ?>" class="card-image"  />
										<?php } else { ?>
										 <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" class="card-image"  />
										<?php } ?>
						</td>
						<td><input name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;"></td>
						<td><input type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric"></td>
					<td><span class="item_no"><?php echo $item_no[$i]; ?></span><?php the_title();?></td>
					<!--<td><span class="price" data-product="<?php echo $postid; ?>"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $price; ?></span></span></td>-->
					</tr>
					<?php 
					$i++;
					}?>
					<?php
					
					endwhile;
					endif;

					?>
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
			<a href="#hexagon">
				<span></span>
			</a>
	</div>
</div>
<style>
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
			<p class="linecode">Your Ship to school orders items: <strong id="qant_items"><img id="d_loading_ajax" src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2022/05/ajax-loader.gif" class=""></strong> <span id="qant_items2">+ Remaining Catlog Items: <strong></strong></span></p>
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
<div class="seller-buyer-cookie-popup">
    <div class="popup-body">
        <form class="seller-buyer-cookie-form">
          <div>
<!--              <label>Buyer Name</label>-->
<!--            <input class="mass-order-buyer-name" type="text">-->
              <label style="display:none;">Seller Name</label>
              <input type="text" name="mass-seller-name-new" class="mass-seller-name-new" style="display:none;">
              <select class="mass-order-seller-name" style="display:none;">
                <option value="No-seller">No Seller</option>
              </select>
			   <span class="alert-selection"></span>
            <input class="popup-submit-button popup-submit-button-cart" type="submit" value="Add Another Order"><br>
			<a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>cart/" class="cart-sec">Preview Cart</a>
            <input class="popup-submit-button popup-submit-button-checkout" type="submit" value="Checkout">
          </div>
        </form>
        <span class="popup-close">Or, Cancel</span>
    </div>
    </div>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/catalog/fullpage.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/catalog/examples.js"></script>

<script type="text/javascript">
    var myFullpage = new fullpage('#fullpage', {
        menu: '#menu',
        anchors: ['hexagon', 'blacklid', 'room-spray',  'melts', 'essential-oils', 'metallic'],
        sectionsColor: ['#FFF', '#fff', '#fff'],
        autoScrolling: true,
        licenseKey: 'OPEN-SOURCE-GPLV3-LICENSE'
    });
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
			var sum = 0;
			var sumQTY=0;
			jQuery.each(selected,function(){sum+=parseFloat(this) || 0;});
			jQuery.each(quanty,function(){sumQTY+=parseFloat(this) || 0;});
			console.log(selected);
			console.log(quanty);
			/*jQuery(".alert-selection").html("Total: $"+sum+" and Quantity: "+sumQTY);*/
			jQuery(".alert-selection").html("Quantity: "+sumQTY+" and Total: $"+sum.toFixed(2));
			jQuery(".alert-selection").css("display","block");
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
			jQuery(".mass-seller-name-new").val(s);
		});
		/*jQuery('#back-top a').click(function()
		{
			jQuery('#menu .firstItem').click();
		});*/
	jQuery("#back-top a").on("click",function(){
	
		jQuery('#fullpage').css('transform','translate3d(0px, 0, 0px)');
		window.location.hash = '#hexagon';
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
 <script src="<?php echo get_template_directory_uri(); ?>/assets_tour/js/dknotus-tour.js"></script>
 <script>
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
						/*    Tour.onstart = function(){
				console.log('We started!');
			  };

			  Tour.onfinish = function(){
				console.log('The End');
			  };

			  Tour.onclose = function(){
				console.log('Tour interupted');
			  };

			  Tour.onstep = function(currentStep){
				console.log('"That\'s one small step for a man ..."');
				console.log(currentStep);
			  };*/
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
						/*    Tour.onstart = function(){
				console.log('We started!');
			  };

			  Tour.onfinish = function(){
				console.log('The End');
			  };

			  Tour.onclose = function(){
				console.log('Tour interupted');
			  };

			  Tour.onstep = function(currentStep){
				console.log('"That\'s one small step for a man ..."');
				console.log(currentStep);
			  };*/
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
		/* Always have wp_footer() just before the closing </body>
		 * tag of your theme, or you will break many plugins, which
		 * generally use this hook to reference JavaScript files.
		 */

		wp_footer();
	?>
</body>

</html>