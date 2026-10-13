<?php 
function product_shortcode_func_new($atts)
{
 $category_name=extract( shortcode_atts( array ('category' => '','title'=>'','price'=>'','season'=>''
    ), $atts ) );
	if($atts['category']=='Hexagon Jars' && $atts['season']  =='winter')
	{
	$ids= array(22433,22413,22425,22426,22439,22445,22447,22449,22440,22450,24386,22419,22432,22423,22417,22455,23058,22458,23061,22460,23064,22457,22478,24387,22459,23073,24388,23077,22468,23080,23082,23084,23085,23086,23090,24389,24390,24391,24392,24393);
	$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'post__in',
	'product_cat' => esc_html( $atts['category'] ),
	'post__in' => $ids
	);
	}
	else if($atts['category']=='Designer 16oz Tins' && $atts['season']  =='winter')
	{
		$ids= array(22620,23127,22621,22623,22618,23131,23133,23135);
		$arg=array('post_type'=>'product', 
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'orderby' => 'post__in',
		'product_cat' => esc_html( $atts['category'] ),
		'post__in' => $ids
		);
	
	}
	else if($atts['category']=='Melts' && $atts['season']  =='winter')
	{
		$ids= array(22566,22976,22565,24412,24413,24414,22567,22568,22569,24415,22562,22564,24416,24417,22572,24418,24419,24420,24421,24422);
		$arg=array('post_type'=>'product', 
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'orderby' => 'post__in',
		'product_cat' => esc_html( $atts['category'] ),
		'post__in' => $ids
		);
	
	}
	else if($atts['category']=='Essential Oils' && $atts['season']  =='winter')
	{
		$ids= array(22999,23002,23004,23003,23011,23012,23013,23014,23015,23016,23193,23195,23197,23030,23032,23033,23034,23199);
		$arg=array('post_type'=>'product', 
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'orderby' => 'post__in',
		'product_cat' => esc_html( $atts['category'] ),
		'post__in' => $ids
		);
	
	}
	else if($atts['category']=='room-spray' && $atts['season']  =='winter')
	{
		$ids= array(22992,22991,22524,22525,22988,22987,22985,22526,22527,22529,22983,22531,23167,22520,22995,23169,23171,24408,24409,23173,23175,24410,22979,24411);
		$arg=array('post_type'=>'product', 
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'orderby' => 'post__in',
		'product_cat' => esc_html( $atts['category'] ),
		'post__in' => $ids
		);
	
	}
	else if($atts['category']=='16oz-round-classic' && $atts['season']  =='winter')
	{
		$ids= array(24397, 22488,22485,22487,24398,22493,22496,22495,24399,23114,22509,24400,24401,22503,24402,22501,24403,23109,24404,24405,23116,23112,24406,24407);
		$arg=array('post_type'=>'product', 
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'orderby' => 'post__in',
		'product_cat' => esc_html( $atts['category'] ),
		'post__in' => $ids
		);
	
	}
	else if($atts['category']=='Boxed Collection melts' && $atts['season']  =='winter')
	{
		$ids= array(22965,22973,22967,23144,23146,23148);
		$arg=array('post_type'=>'product', 
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'orderby' => 'post__in',
		'product_cat' => esc_html( $atts['category'] ),
		'post__in' => $ids
		);
	
	}
	else{
	$arg=array('post_type'=>'product', 
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'order' => 'ASC',
	'product_cat' => esc_html( $atts['category'] )
	);
	}
$table_query= new WP_Query();
$table_query->query($arg);
$num = $table_query->post_count; 
$html_private_category = '';
ob_start();

?>
<style>
#DataTables_Table_0 tr[role='row']
{
	width:14%;
}
#DataTables_Table_0 tr[role='row'] td
{
	text-align: center;
    font-size: 14px;
    padding: 6px 0px;
}
table#DataTables_Table_0 tr td:nth-child(4)
{
	text-align: center;
    font-size: 14px;
}
table#DataTables_Table_0 span.woocommerce-Price-amount.amount {
    font-size: 14px;
}
#DataTables_Table_0 tr[role='row'] td:nth-child(3) input{
	width:100%;
}
#DataTables_Table_0 tr[role='row'] td:nth-child(2)
{
	width: 20%;
    padding: 0;
}
.quickster-table
{
	display:none;
}
</style>
<script type='text/javascript' src='<?php echo esc_url( glow_dynamic_url_base('content') ); ?>plugins/quick-orders-for-woocommerce/assets/public/datatables/datatables.min.js'></script>
<div data-post="<?php echo $num; ?>" id="<?php echo $category;?>">
<div id="DataTables_Table_0_filter" class="dataTables_filter"></div>
<table id="DataTables_Table_0" class="dataTable no-footer dtr-inline hover stripe" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 1140px;">
  <thead style="display:block;">
    <tr style="width: 100%;margin: 0;" role="row" >
	<th style="width: 48%;float: left;border: 0;text-transform: uppercase; font-size: 18px;font-weight:700; padding-left: 0;"><?php echo esc_html( $atts['title'] ); ?></th>
	<th style="width: 45%;float: right; border: 0;text-align: right;text-transform: uppercase; font-size: 18px;font-weight:700;">PRICE: <?php echo esc_html( $atts['price'] ); ?></th>
	</tr>
  </thead>
<tbody>
<?php
if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
$postid = $table_query->post->ID; 
$price = get_post_meta( $postid, '_regular_price', true);
if(has_term( 'Winter Catalogue', 'product_cat', $postid )){
$w="winter";
if(esc_html( $atts['season'] )==$w){
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
<td><?php the_title();?></td>
<td><span class="price" data-product="<?php echo $postid; ?>"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $price; ?></span></span></td>
</tr>
<?php 
} 
}
if(has_term( 'Summer Catalogue', 'product_cat', $postid )){
$s="summer";
if(esc_html( $atts['season'] )==$s){
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
<td><?php the_title();?></td>
<td><span class="price" data-product="<?php echo $postid; ?>"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $price; ?></span></span></td>
</tr>
<?php } }?>
<?php

endwhile;
endif;

?></tbody>
</table>
</div>
<?php

$html_private_category = ob_get_clean();
wp_reset_postdata(); wp_reset_query();
return $html_private_category;

}
add_shortcode( 'product_shortcode_new', 'product_shortcode_func_new' );