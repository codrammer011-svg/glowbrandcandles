<?php 
/* 
	Template Name: Getting all virtual products
*/
	get_header();
?>

<?php 

	$l = et_page_config();

?>

<?php do_action( 'et_page_heading' ); ?>
<style>
.st-page-content
{
	    width: 90%;
    display: inline-block;
    margin: 0 auto;
    text-align: center;
}
img
{
	    width: 74px;
}
li{
	font-weight:600;
}
h3{
	padding-top:30px;
}
</style>
	<div class="st-container content-page">
		<div class="st-page-content">
			<div class="row">

				<div class="content">
					<?php if(have_posts()): while(have_posts()) : the_post(); ?>
						
						<?php the_content(); ?>

						<?php 
					
								$product_ids = get_posts( array(
								   'post_type' => 'product',
								   'numberposts' => -1,
								   'post_status' => 'publish',
								   'meta_query' => array( array(
									  'key' => '_downloadable',
									  'value' => 'yes',
									  'compare' => '=',
								   )),
								));
								 
								// Print array on screen
							//print_r( $product_ids );
						?>
						<h3>Downloadable Products</h3>
						<ul class="downloadable">
						<?php
						if(!empty($product_ids))
						{
						?>
						
						
						<?php 
						for($d=0;$d<count($product_ids);$d++)
						{
							$product = wc_get_product( $product_ids[$d] );
						
						?>
						<li><?php echo $product->get_image(); echo "ID: ".$product->get_id()." Name: ".$product->get_title();?></li>
						<?php 
						} 
						}
						else
						{
							echo "<li>Empty Downloadable Producta</li>";
						}
						?>
						</ul>
						<?php 
						
						
					
								$product_ids2 = get_posts( array(
								   'post_type' => 'product',
								   'numberposts' => -1,
								   'post_status' => 'publish',
								   'meta_query' => array( array(
									  'key' => '_virtual',
									  'value' => 'yes',
									  'compare' => '=',
								   )),
								));
								 
								// Print array on screen
							//print_r( $product_ids );
						?>
					   <h3>Virtual Products</h3>
						<ul class="virtual" style="padding-bottom:30px;">
						<?php
						if(!empty($product_ids2))
						{
						 
						for($d=0;$d<count($product_ids);$d++)
						{
							$product = wc_get_product( $product_ids2[$d] );
							
						?>
					<li><?php echo $product->get_image(); echo "ID: ".$product->get_id()." Name: ".$product->get_title();?></li>
						<?php 
						} 
						}
						else
						{
							echo "<li>Empty Virtual Products</li>";
						}
						?>
						</ul>
					
					<?php endwhile; else: ?>

						<h3><?php esc_html_e('No pages were found!', 'royal') ?></h3>

					<?php endif; ?>
					
				</div>

				

			</div><!-- end row-fluid -->

		</div>
	</div><!-- end container -->

<?php
	get_footer();
?>
