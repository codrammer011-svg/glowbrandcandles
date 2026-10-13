<?php
/**
 * Template Name: Winter Catalogue Design
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
 else
 {
	 
 }

?>
<style>
#DataTables_Table_0 tbody tr[role='row'] {
    width: 19.5% !important;
}
</style>
<ul id="menu">
			<li data-menuanchor="hexagon" class="active"><a href="#hexagon">Hexagon</a></li>
			<li data-menuanchor="metallic"><a href="#metallic">Metallic</a></li>
			<li data-menuanchor="crystal"><a href="#crystal">Crystal</a></li>
			<li data-menuanchor="blacklid"><a href="#blacklid">16OZ Black Lid</a></li>
			<li data-menuanchor="tins"><a href="#tins">Tins</a></li>
			
			<li data-menuanchor="man-cave"><a href="#man-cave">Man Cave</a></li>
			<li data-menuanchor="boxed-collection-melts"><a href="#boxed-collection-melts">Flameless</a></li>
			<li data-menuanchor="melts"><a href="#melts">Melts</a></li>
			<li data-menuanchor="room-spray"><a href="#room-spray">Room Sprays</a></li>
			<li data-menuanchor="essential-oils"><a href="#essential-oils">Essential Oils</a></li>
	</ul>
	<div id="DataTables_Table_0_filter" class="dataTables_filter add-to-cart-Btn"><input id="nSeller" type="text" name="mass-seller-name-new-test" value="" style=" position: absolute;top: 0;left: -150px;" class="mass-seller-name-new-test" placeholder="Seller Name"></div>
</div>
<script type='text/javascript' src='<?php echo esc_url( glow_dynamic_url_base('content') ); ?>plugins/quick-orders-for-woocommerce/assets/public/datatables/datatables.min.js'></script>
<div id="fullpage">
	<!-- Hexagon Catagory Product-->
	<div class="section" id="section0">
        <div class="slide" id="slide1">
		<?php 
		$ids= array(22413,22425,22419,22423,32891,22447,32893,22460,22468,22455);
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
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$14.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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
						<td><input <?php if($postid==22433){ ?> id="selectbox" <?php } ?> name="add" class="add" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>" type="checkbox" style="margin: 0;"></td>
						<td><input <?php if($postid==22433){ ?> id="qtybox" <?php } ?> type="number" data-product="<?php echo $postid; ?>" class="input-text qty text" step="1" min="0" max="9999" name="quantity" value="" title="" size="4" pattern="[0-9]*" inputmode="numeric"></td>
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
		$ids= array(39853,39869,22457,39858,39863,32773,39867,39855,39849,39865);
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
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php  echo "10OZ. HEXAGON CANDLES - BURN 70+ HOURS"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$14.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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
		$ids= array(24392,23085,24393,22426,22439,22445,22449,22440,22450,24386);
		$item_no=array("H21","H22","H23","H24","H25","H26","H27","H28","H29","H30");
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
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$14.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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
		
		<div class="slide" id="slide4">
		<?php 
		$ids= array(22432,22417,23058,22458,23064,22459,24391,23073,24388,23077);
		$item_no=array("H31","H32","H33","H34","H35","H36","H37","H38","H39","H40");
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
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$14.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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
		

				<div class="slide" id="slide5">
		<?php 
		$ids= array(23082,23084,23086,23090,24389,24390);
		$item_no=array("H41","H42","H43","H44","H45","H46","H47","H48","H49","H50");
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
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$14.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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
	
	<!-- Metalic -->
	 <div class="section section-wrap" id="section1">
        <?php 
		$item_no=array("MG41","MG42","MG43");
		$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'order' => 'ASC',
	'product_cat' => esc_html( 'boxed-metallic' )
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
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "16OZ BOXED METALIC GLASS CANDLES - BURN 110+ HOURS"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$36.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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
	
	<!-- Crystal -->
	 <div class="section section-wrap" id="section3">
        <?php 
		$item_no=array("JC44","JC45","JC46","JC47");
		$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'order' => 'ASC',
	'product_cat' => esc_html( 'Boxed Crystal' )
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
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "16OZ SILVER LID/SINGLE WICK CANDLES - BURN 100+ HOURS"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$25.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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
	
	
	
	<!-- 16oz Black Lid Catagory Product-->
	
	<div class="section section-wrap" id="section2">
       <div class="slide" id="slide1">
		<?php 
		$ids= array(32833,32835,22488,22485,32848,22493,32886,22501,32852,24401);
		$item_no=array("JB23","JB24","JB25","JB26","JB27","JB28","JB29","JB30","JB31","JB32");
	$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'post__in',
	'product_cat' => esc_html( '16oz-round-classic' ),
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
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "16OZ BLACK LID/SINGLE WICK CANDLES - BURN 100+ HOURS"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$20.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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
		$ids= array(39751,39761,23109,39723,39776,24398,39727,39725,39806,39719);
		$item_no=array("JB33","JB34","JB35","JB36","JB37","JB38","JB39","JB40","JB41","JB42");
	$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'post__in',
	'product_cat' => esc_html( '16oz-round-classic' ),
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
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "16OZ BLACK LID/SINGLE WICK CANDLES - BURN 100+ HOURS"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$20.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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
		$ids= array(24397,22487,22496,22495,24399,23114,22509,24400,22503,24402);
		$item_no=array("JB43","JB44","JB45","JB46","JB47","JB48","JB49","JB50","JB51","JB52");
	$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'post__in',
	'product_cat' => esc_html( '16oz-round-classic' ),
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
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "16OZ BLACK LID/SINGLE WICK CANDLES - BURN 100+ HOURS"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$20.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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

			<div class="slide" id="slide4">
		<?php 
$ids= array(24403,24404,24405,23116,23112,24406,24407);
		$item_no=array("JB78","JB79","JB80","JB81","JB82","JB83","JB84");
	$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'post__in',
	'product_cat' => esc_html( '16oz-round-classic' ),
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
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "16OZ BLACK LID/SINGLE WICK CANDLES - BURN 100+ HOURS"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$20.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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
	<!-- End 16oz Black Lid Catagory Product-->
	
	
	<!-- Tins -->
	 <div class="section section-wrap" id="section4">
        <?php 
		$ids= array(22620,23127,22621,22623,22618,23131,23133,23135);
		$item_no=array("T72","T73","T74","T75","T76","T77","T78","T79");
		$arg=array('post_type'=>'product', 
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'orderby' => 'post__in',
		'product_cat' => esc_html( 'Designer 16oz Tins' ),
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
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "16 oz. tins - burn 100+ hours"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$20.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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
	<!-- Man Cans -->
	 <div class="section section-wrap" id="section5">
        <?php 
		$item_no=array("MC80","MC81","MC82","MC83");
		$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'order' => 'ASC',
	'product_cat' => esc_html( 'Man Cave' )
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
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "16 oz. pint man cans - burn 100+ hours"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$22.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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
	
	<!-- Boxed Collection melts -->
	 <div class="section section-wrap" id="section6">
        <?php 
		$ids= array(22965,22973,22967,23144,23146,23148);
		$item_no_n=array("SM84","SM85","SM86","SM87","SM88","SM89");
		$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'post__in',
	'product_cat' => esc_html( 'Boxed Collection melts' ),
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
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "3 scents per box, 60+ hours each for over 180+ hours of AROMA"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$16.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i_n=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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
	
	<!-- Melts Catagory Product-->
	<div class="section section-wrap" id="section7">
        <div class="slide" id="slide1">
		<?php 
		$ids= array(39702,32815,32821,22976,32811,22568,22569,22572,24413,22562);
		$item_no=array("LM45","LM46","LM47","LM48","LM49","LM50","LM51","LM52","LM53","LM54");
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
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php  echo "6 large cubes - offering 100+ hours of AROMA"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$12.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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
		$ids= array(39752,39763,39707,39778,32817,39779,39785,39808,39802,24421);
		$item_no=array("LM55","LM56","LM57","LM58","LM59","LM60","LM61","LM62","LM63","LM64");
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
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "6 large cubes - offering 100+ hours of AROMA"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$12.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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
		$ids= array(24422,22566,22565,24412,24414,22567,24415,22564,24417,24418);
		$item_no=array("LM65","LM66","LM67","LM68","LM69","LM70","LM71","LM72","LM73","LM74","LM75");
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
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "6 large cubes - offering 100+ hours of AROMA"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$12.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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

		<div class="slide" id="slide4">
		<?php 
		$ids= array(24419,24420,24421,39808,39707,39785,39778,39802,39779);
		$item_no=array("LM76","LM77","LM78","LM79","LM80","LM81","LM82","LM83","LM84","LM85","LM86");
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
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "6 large cubes - offering 100+ hours of AROMA"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$12.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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
	<!-- End Melts Catagory Product-->
	
	<!-- room-spray Catagory Product-->
	<div class="section section-wrap" id="section8">
        <div class="slide" id="slide1">
		<?php 
		$ids= array(39704,32790,22525,22983,32781,22985,22527,22531,32798,22520);
		$item_no=array("RS67","RS68","RS69","RS70","RS71","RS72","RS73","RS74","RS75","RS76");
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
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php  echo "4 fl oz ROOM SPRAYS"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$14.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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
		$ids= array(39753,39711,39769,39717,23173,32792,39781,39810,39709,23175);
		$item_no=array("RS77","RS78","RS79","RS80","RS81","RS82","RS83","RS84","RS85","RS86");
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
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "4 fl oz ROOM SPRAYS"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$14.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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
		$ids= array(23177,22992,22991,22524,22988,22987,22526,22529,23167,22995);
		$item_no=array("RS87","RS88","RS89","RS90","RS91","RS92","RS93","RS94","RS95","RS100");
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
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "4 fl oz ROOM SPRAYS"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$14.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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
		
			<div class="slide" id="slide4">
		<?php 
		$ids= array(23169,23171,24408,24409,24410,22979,24411);
		$item_no=array("RS101","RS102","RS103","RS104","RS105","RS107","RS108","RS109","RS110","RS111");
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
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo "4 fl oz ROOM SPRAYS"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$14.00"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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
	
	<!-- Essential Oils Catagory Product-->
	<div class="section section-wrap" id="section9">
        <div class="slide" id="slide1">
		<?php 
		$ids= array(22999,23002,23004,23003,23011,23012,23013,23014,23015,23016);
		$item_no=array("EO134","EO135","EO136","EO137","EO138","EO139","EO140","EO141","EO142","EO143");
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
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php  echo ".5fl oz./15ml. essential oils"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$15.00 SINGLES/$25.00 BLENDS"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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
		$ids= array(23193,23195,23197,23030,23032,23033,23034,23199);
		$item_no=array("EO144","EO145","EO146","EO147","EO148","EO149","EO150","EO151");
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
					<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo ".5fl oz./15ml. essential oils"; ?></th>
					<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo "$15.00 SINGLES/$25.00 BLENDS"; ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					$i=0;
					if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$price = get_post_meta( $postid, '_regular_price', true);
					if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
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
	<!-- End Essential Oils Catagory Product-->
	
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
        <span class="popup-close">Or, Cancal</span>
    </div>
    </div>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/catalog/fullpage.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/catalog/examples.js"></script>

<script type="text/javascript">
    var myFullpage = new fullpage('#fullpage', {
        menu: '#menu',
        anchors: ['hexagon', 'metallic', 'crystal', 'blacklid', 'tins', 'man-cave', 'boxed-collection-melts','melts','room-spray', 'essential-oils'],
        sectionsColor: ['#FFF', '#fff', '#fff'],
        autoScrolling: true,
        licenseKey: 'OPEN-SOURCE-GPLV3-LICENSE'
    });
	jQuery(window).scroll(function() {    
    var scroll = jQuery(window).scrollTop();

     //>=, not <=
    if (scroll >= 500) {
        //clearHeader, not clearheader - caps H
        jQuery("#menu").addClass("darkHeader");
    }
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
			var catalogType="Winter";
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
.gouVSR span {
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
	text-align:center;
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
.eYvePi span {
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