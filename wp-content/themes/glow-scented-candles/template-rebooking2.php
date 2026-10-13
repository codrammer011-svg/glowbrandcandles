<?php 
/* 
	Template Name: Rebooking Template 2
*/
	get_header();
?>

<?php 

	$l = function_exists('et_page_config') ? et_page_config() : array(
		'sidebar' => 'without',
		'sidebar-mobile' => 'bottom',
		'shop-sidebar-mobile' => 'bottom',
		'content-class' => ''
	);

?>
<style>
.content.events-controller {
    max-width: 1105px;
    padding: 0 30px;
    margin: 0 auto;
    margin-top: 120px;
    margin-top: 45px;
    margin-bottom: 45px;
	padding-bottom: 45px;
	padding-bottom:0;
	 margin-bottom: 0px;
	margin-top: 20px;
}
.fundraiser_rebooking_design .header.organizer-fundraisers-detail {
    display: inline-block;
    justify-content: space-between;
    padding-bottom: 20px;
    margin-bottom: 45px;
    border-bottom: 1px solid #efeeee;
    width: 100%;
	text-align: center;
	 margin-bottom: 0px;
}
.fundraiser_rebooking_design h1
{
    text-transform: inherit;
    font-size: 30px;
    line-height: 1.6;
    font-weight: 500;
	margin: 0 0 15px;
    color: #4a4a4a;
	font-family: 'Roboto', Georgia, sans-serif;
}
.fundraiser_rebooking_design p
{
	    color: #828286;
    line-height: 1.6;
    padding: 0;
    font-weight: normal;
    display: inline-block;
    font-family: "SF Pro Display", "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-size: 17px;
}
.left-panel
{
    padding-right: calc(45px / 2);
}
.right-panel
{
    padding-left: calc(45px / 2);
}
.fundraiser_rebooking_design .first-grid h1
{
	    margin-top: 80px;
}
.fundraiser_rebooking_design .second-grid h1
{
	    margin-top: 50px;
}
.small-scr
{
	display:none;
}
.content-page .fundraiser_rebooking_design h1
{
        font-size: 26px;
    line-height: 1.4;
}
.content ul.steps-rebook
{
    list-style: none;
    padding-left: 0;
    line-height: 1.6;
    padding: 0;
    font-weight: normal;
    display: inline-block;
    font-size: 15px;
    color: #4a4a4a;
    font-family: 'Roboto', Georgia, sans-serif;
}
.content ul.steps-rebook li
{
       padding-bottom: 20px; 
}
.content ul.steps-rebook li b
{
    font-size: 17px;
        color: #cda85c;
}
.fundraiser_rebooking_design p.last-line
{
  
        font-size: 17px;
    line-height: 1.4;
    font-weight: 500;
    font-family: 'Roboto', Georgia, sans-serif;
    color: #cda85c;
    letter-spacing: 0.2px;
}
.fundraiser_rebooking_design .third-grid h1
{
	    margin-top: 80px;
}
@media screen and (min-width: 1200px)
{ 

}
@media screen and (max-width: 1199px)
{

.fundraiser_rebooking_design .first-grid p,
.fundraiser_rebooking_design .second-grid p,
.fundraiser_rebooking_design .third-grid p
{
	    font-size: 14px;
}
.fundraiser_rebooking_design .first-grid h1,
.fundraiser_rebooking_design .second-grid h1,
.fundraiser_rebooking_design .third-grid h1
{
   
    font-size: 18px;
}
.fundraiser_rebooking_design .third-grid h1
	{
	    margin-top: 35px;
	}
}
@media screen and (max-width: 768px)
{
.right-panel {
    max-width: 50%;
    float: right;
    width: 47.555%;
	width: 50%;
}
.left-panel {
    float: left;
    max-width: 50%;
    width: 47.555%;
	width: 50%;
}
.fundraiser_rebooking_design .second-grid h1 {
    margin-top: 15px;
}
.fundraiser_rebooking_design .first-grid h1 {
    margin-top: 35px;
}
}
@media screen and (max-width: 700px)
{
	.second-grid.large-scr .right-panel 
	{
		    margin-top: 30px;
	}
	.fundraiser_rebooking_design .first-grid h1, .fundraiser_rebooking_design .second-grid h1, .fundraiser_rebooking_design .third-grid h1 {
    font-size: 16px;
}
}
@media screen and (max-width: 550px)
{
	.left-panel {
		float: left;
		max-width: 100%;
		width: 100%;
		padding-right: 0;
	}
	
	.right-panel {
		max-width: 100%;
		float: right;
		width: 100%;
		padding-left: 0;
	}
	.small-scr
	{
		display:block;
	}
	.large-scr
	{
		display:none;
	}
	.fundraiser_rebooking_design .header.organizer-fundraisers-detail
	{
		    margin-bottom: 0;
	}
	.right-panel
	{
		margin: 0;
	}
	.fundraiser_rebooking_design .first-grid h1
	{
		    margin-top: 0px;
	}

	.fundraiser_rebooking_design .header h1
	{
		    font-size: 18px;
			    margin: 0 0 7px;
	}
	.fundraiser_rebooking_design .header p
	{
		    font-size: 14px;
	}
	.fundraiser_rebooking_design .third-grid h1
	{
	    margin-top: 0px;
	}
}
@media screen and (max-width: 420px)
{
	.fundraiser_rebooking_design .second-grid h1 
	{
		margin-top: 25px;
	}
	.small-scr 
	{
		margin-top: 10px;
	}
}
</style>
<?php do_action( 'et_page_heading' ); ?>

	<div class="container content-page">
		<div class="page-content sidebar-position-<?php esc_attr_e( $l['sidebar'] ); ?> sidebar-mobile-<?php esc_attr_e( class_exists('WooCommerce') && ( is_checkout() || is_cart() || is_account_page() || in_array( 'woocommerce-wishlist', get_body_class() ) ) ? $l['shop-sidebar-mobile'] : $l['sidebar-mobile'] ); ?>">
			<div class="row">

				<!--<div class="content <?php esc_attr_e( $l['content-class'] ); ?>">-->
				<div class="content style-scope events-controller fundraiser_rebooking_design">
					<?php if(have_posts()): while(have_posts()) : the_post(); ?>
						
						<?php the_content(); ?>

						<!--<div class="post-navigation">
							<?php wp_link_pages(); ?>
						</div>-->
						
						<?php /* if ($post->ID != 0 && current_user_can('edit_post', $post->ID)): ?>
							<?php edit_post_link( __('Edit this', 'royal'), '<p class="edit-link">', '</p>' ); ?>
						<?php endif */?>

					<?php endwhile; else: ?>

						<!--<h3><?php esc_html_e('No pages were found!', 'royal') ?></h3>-->

					<?php endif; ?>

				</div>

				<?php /* get_sidebar(); */ ?>

			</div><!-- end row-fluid -->

		</div>
	</div><!-- end container -->

<?php
	get_footer();
?>
