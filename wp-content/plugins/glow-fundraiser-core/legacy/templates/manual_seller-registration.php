<?php 
/* 
	Template Name: Registration
*/
	get_header();
	$c_ID=$_GET['frid'];
	$seller_url="https://glowbrandscandles.com/seller-register/?frid=".$c_ID; 
 ?>
 <script>
		jQuery(document).ready(function () {
			jQuery(document).on('click', '#AddNewSeller', function (e) {
			e.preventDefault();
			var ajax_url= "<?php echo admin_url('admin-ajax.php'); ?>";
			var email_id=jQuery("#email_id").val();
			jQuery.post(ajax_url, {
							action: "wp_seller_send_email",
							f_id:<?php echo $c_ID; ?>,
							email:email_id
							}).success(function(posts){
								/*alert(posts);*/
								jQuery("#invitaion_message").text(posts);
							});
	  });
});
</script>
<style>
.manual-content
{
	background: #f7f7f7;
}
.manual-content .fundraiser-dashboard-section {
    width: 100%;
    padding: 25px;
    max-width: 100%;
    margin: 0 auto;
}
.manual-content #email_id
{
	width: 84%;
    display: inline-block;
}
.manual-content #AddNewSeller
{
	    background: #000;
    color: #fff;
    padding: 10px 20px;
    padding: 10px 20px;
    line-height: 1.5;
    text-align: center;
    word-break: break-word;
    white-space: normal;
    border: none;
    cursor: pointer;
    -webkit-transition: .35s all ease-out;
    transition: .35s all ease-out;
    width: 170px;
    color: #fff;
    display: inline-block;
    -webkit-border-radius: 0;
    border: none;
    background-color: #262626;
    text-transform: uppercase;
    border-radius: 0;
    font-size: 13px;
    -webkit-font-smoothing: antialiased;
    -webkit-transition: background 0.25s ease-in;
    -ms-transition: background 0.25s ease-in;
    -o-transition: background 0.25s ease-in;
    transition: background 0.25s ease-in;
}
.manual-content #AddNewSeller:hover
{
	    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
#invitaion_message
{
	    text-align: left;
    padding-bottom: 10px;
    font-size: 17px;
    color: red;
    padding-left: 29px;
    margin-top: -17px;
}
@media (max-width:1199px)
{
	.manual-content #email_id {
    width: 81%;
  }
}
@media (max-width:965px)
{
	.manual-content #email_id {
    width: 100%;
	}
	.manual-content #AddNewSeller
	{
		position: absolute;
    right: 25px;
    width: 120px;
	}
}
/*@media (max-width:820px)
{
	.manual-content #email_id {
    width: 70%;
	}
}
@media (max-width:636px)
{
	.manual-content #email_id {
    width: 65%;
	}
}
@media (max-width:556px)
{
	.manual-content #email_id {
    width: 60%;
	}
}
@media (max-width:494px)
{
	.manual-content #email_id {
    width: 55%;
	}
}
@media (max-width:446px)
{
	.manual-content #email_id {
    width: 50%;
	}
}*/
</style>
  <?php do_action( 'et_page_heading' ); ?>    
 <div class="container content-page">
		<div class="page-content sidebar-position-<?php esc_attr_e( $l['sidebar'] ); ?> sidebar-mobile-<?php esc_attr_e( class_exists('WooCommerce') && ( is_checkout() || is_cart() || is_account_page() || in_array( 'woocommerce-wishlist', get_body_class() ) ) ? $l['shop-sidebar-mobile'] : $l['sidebar-mobile'] ); ?>">
			<div class="row">
                                 
				<div class="content manual-content <?php esc_attr_e( $l['content-class'] ); ?>">
                    <div class="fundraiser-dashboard-section imp-links">
					    <input id="email_id" type="text" placeholder="Email" value=""><a href="#" id="AddNewSeller" class="txt-link">Send</a>
                    </div>
					<div id="invitaion_message"></div>
                 </div>

				<?php get_sidebar(); ?>

			</div><!-- end row-fluid -->

		</div>
	</div><!-- end container -->

<?php
	get_footer();
?>
