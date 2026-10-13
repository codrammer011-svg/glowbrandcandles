<?php
/**
 * Template Name: Rebook Seller Registraion
 */
 get_header();
?>
<style>
button[disabled], html input[disabled] {
    cursor: default;
    opacity: 0.5;
}
.new-seller-register-page h1.largest {
    padding-top: 60px;
    line-height: 72px;
    font-size: 55px;
    line-height: 1.1 !important;
}
.new-seller-register-page h1.sec
{
	    font-size: 25px;
    line-height: 1.1 !important;
    font-weight: initial;
}
.new-seller-register-page .page-not-found 
{
    text-align: center;
    padding-bottom: 90px;
    display: inline-block;
    width: 100%;
}
.new-seller-register-page .GrupName
{
	color: #cda85c;
   text-transform: capitalize;
}
.new-seller-register-page .g-info
{
	text-align: center;
    font-size: 16px;
    text-transform: capitalize;
    background: #000;
    padding: 5px 2px;
    color: #fff;
    margin-bottom: 0;
}
</style>
<?php 

	$l = et_page_config();

?>

<?php do_action( 'et_page_heading' ); ?>

	<div class="container content-page new-seller-register-page">
		<div class="page-content sidebar-position-<?php esc_attr_e( $l['sidebar'] ); ?> sidebar-mobile-<?php esc_attr_e( class_exists('WooCommerce') && ( is_checkout() || is_cart() || is_account_page() || in_array( 'woocommerce-wishlist', get_body_class() ) ) ? $l['shop-sidebar-mobile'] : $l['sidebar-mobile'] ); ?>">
			<div class="row">

				<div class="content">
					<?php if(have_posts()): while(have_posts()) : the_post(); ?>
						
						<?php 
	/*	if(!empty($_GET['frid'])) 
		{ 
			$query = $_GET['frid'];
			if ( preg_match( '/([0-9]{6})/', $query) == 1) 
			{
				$user_info = get_userdata($query);
				$username = $user_info->user_login;
				
				if(!empty($username))
				{
					$check=1;
					$userID=$user_info->ID;
					$org=get_user_meta( $userID , 'user_registration_input_company' , true );
				}
				if($check==1)
				{
					if(!empty($org))
					{
						echo "<p class='g-info'>Group Name: <span class='GrupName'>".$org."</span></p>";
					}
					else
					{
						echo "<p class='g-info'>Group Name: <span class='GrupName'>Not available</span></p>";
					}
					echo do_shortcode('[user_registration_form id="62307"]');
				?>
				<script>
				jQuery(document).ready(function()
				{
					jQuery('#seller_fundraiser').attr("disabled", "");
				});
				</script>
				<?php
				}
				else
				{
					echo "<div class='page-not-found'><h1 class='largest'>Error</h1><h1 class='sec'>Incorrect Fundraiser ID</h1><hr class='horizontal-break'><p>Sorry, but the page you are looking for is not found. Please, make sure you have typed the current URL.</p></div>";
				}
			}
			else
			{
				echo "<div class='page-not-found'><h1 class='largest'>Error</h1><h1 class='sec'>Oops! Incorrect url</h1><hr class='horizontal-break'><p>Sorry, but the page you are looking for is not found. Please, make sure you have typed the current URL.</p></div>";
			}
		} */
		if(!empty($_GET['rebook_seller_id']))
		{
			$query1 = $_GET['rebook_seller_id'];
			if ( preg_match( '/([0-9]{6})/', $query1) == 1) 
			{
				$user_info1 = get_userdata($query1);
				$username1 = $user_info1->user_login;
				
				if(!empty($username1))
				{
					$check1=1;
					$sellerID=$user_info1->ID;
					$sellerURL=get_user_meta($sellerID, 'user_registration_image_url_profile', true);
					$sellerDescription=get_user_meta($sellerID, 'description', true);
					$seller_first_name=get_user_meta($sellerID, 'first_name', true);
					$seller_last_name=get_user_meta($sellerID, 'last_name', true);
					$sellerGoal=get_user_meta($sellerID, 'user_registration_seller_goal', true);
					$sellerEmail=get_user_meta($sellerID, 'rebook_email', true);
					$FrID=get_user_meta( $sellerID, 'user_registration_seller_fundraiser', true );
				 	
					$org=get_user_meta( $FrID, 'user_registration_name_of_school_business', true );
					if(empty($org))
					{
						$org=get_user_meta( $FrID, 'user_registration_input_company', true );
					}
				}
				if($check1==1)
				{
					/* if(!empty($org))
					{
						echo "<p class='g-info'>Group Name: <span class='GrupName'>".$org."</span></p>";
					}
					else
					{
						echo "<p class='g-info'>Group Name: <span class='GrupName'>Not available</span></p>";
					}*/
					echo do_shortcode('[user_registration_form id="62307"]');
					
					?>
				<script>
				jQuery(document).ready(function()
				{
					<?php if(!empty($seller_first_name)) { ?>
					jQuery('#user-registration-form-62307 #first_name').val("<?php echo $seller_first_name; ?>");
					<?php } if(!empty($seller_last_name)) {  ?>
					jQuery('#user-registration-form-62307 #last_name').val("<?php echo $seller_last_name; ?>");
					<?php } if(!empty($sellerDescription)) {  ?>
					jQuery('#user-registration-form-62307 #description').val("<?php echo $sellerDescription; ?>");
					<?php } if(!empty($sellerGoal)) {  ?>
					jQuery('#user-registration-form-62307 #seller_goal').val("<?php echo $sellerGoal; ?>");
					<?php } if(!empty($sellerEmail)) {  ?>
					jQuery('#user-registration-form-62307 #user_email').val("<?php echo $sellerEmail; ?>");
					<?php } if(!empty($sellerURL)) {   ?>
					jQuery('#user-registration-form-62307 #img-upload').attr('src', "<?php echo $sellerURL; ?>");
					<?php } if(!empty($sellerURL)) {  ?>
					jQuery('#user-registration-form-62307 #image_url_profile').val("<?php echo $sellerURL; ?>");
					<?php }   ?>
				});
				</script>
				<?php
				}
				else
				{
					echo "<div class='page-not-found'><h1 class='largest'>Error</h1><h1 class='sec'>Incorrect Seller ID</h1><hr class='horizontal-break'><p>Sorry, but the page you are looking for is not found. Please, make sure you have typed the current URL.</p></div>";
				}
			}
			else
			{
				echo "<div class='page-not-found'><h1 class='largest'>Error</h1><h1 class='sec'>Oops! Incorrect url</h1><hr class='horizontal-break'><p>Sorry, but the page you are looking for is not found. Please, make sure you have typed the current URL.</p></div>";
			}
		}
		else
		{
			echo "<div class='page-not-found'><h1 class='largest'>Error</h1><h1 class='sec'>Oops! Incorrect url</h1><hr class='horizontal-break'><p>Sorry, but the page you are looking for is not found. Please, make sure you have typed the current URL.</p></div>";
		}

		?>

						

					<?php endwhile; else: ?>

						<h3><?php esc_html_e('No pages were found!', 'royal') ?></h3>

					<?php endif; ?>

				</div>

				

			</div><!-- end row-fluid -->

		</div>
	</div><!-- end container -->

<?php
get_footer();
	//wp_footer();
?>
