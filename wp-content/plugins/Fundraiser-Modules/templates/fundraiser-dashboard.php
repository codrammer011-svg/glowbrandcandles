<?php 
	get_header();
?>
<style>
.show_load{
	display:inline-block;
}
.hide_load{
	display:none;
}
.generation_btn:hover {
    box-sizing: border-box;
    background: #cdcdcd;
    color: #000;
}
.generation_btn {
    display: inline-block;
    width: 20%;
    margin: 0 2%;
    color: #fff;
    background: #000;
    text-align: center;
    padding: 10px;
    font-size: 16px;
    transition: all 0.5s ease 0s;
	cursor:pointer;
}
</style>
<style>
#restore_order
{
	-webkit-box-sizing:unset;
	box-sizing: unset;
}
.show_load_restore{
	display:inline-block;
}
.hide_load_restore{
	display:none;
}
.top-grid
{
	position: relative;
    display: inline-block;
    width: 100%;
}
.top-grid .restore-btn
{
	top: 114px;
    background: #000;
    color: #fff;
	position: absolute;
    right: 46px;
    padding: 20px;
}
.top-grid #restore_order
{
	    font-size: 14px;
    color: #fff;
	font-weight: 600;
}
</style>
<div class="container content-page">
		<div class="page-content sidebar-position-<?php esc_attr_e( $l['sidebar'] ); ?> sidebar-mobile-<?php esc_attr_e( class_exists('WooCommerce') && ( is_checkout() || is_cart() || is_account_page() || in_array( 'woocommerce-wishlist', get_body_class() ) ) ? $l['shop-sidebar-mobile'] : $l['sidebar-mobile'] ); ?>">
			<div class="row">
                  <?php do_action( 'et_page_heading' ); ?>                  
				<div class="content <?php esc_attr_e( $l['content-class'] ); ?>">
                    <div class="intro-content"><?php the_content(); ?></div>
                    <div class="fundraiser-dashboard-section link-generation">
					
                        <?php $current_user = wp_get_current_user(); ?>

                        <?php 
                              global $wpdb;
                              session_start();

                              $u_id = $current_user->ID;
                              //$id        = $wpdb->get_row("SELECT meta_value FROM wp_usermeta WHERE user_id='$u_id'  ");

                               $_SESSION['fundraiser_id'] = $u_id ;  //  get_post_meta 
							   $f=get_user_meta( $u_id , 'first_name' , true );		
							   $l=get_user_meta( $u_id , 'last_name' , true );								   
							 //  $org=get_user_meta( $u_id , 'user_registration_input_company' , true );
							 $org=get_user_meta( $u_id , 'user_registration_name_of_school_business' , true );
							   $sd=get_user_meta( $u_id , 'user_registration_start_date' , true );
							   $ed=get_user_meta( $u_id , 'user_registration_end_date' , true );
							   $before=get_user_meta( $u_id , 'user_registration_radio_1534260773' , true );
							   $hear=get_user_meta( $u_id , 'user_registration_reference' , true );
							   $referral=get_user_meta( $u_id , 'user_registration_referral_name' , true );
							   $type=get_user_meta( $u_id , 'user_registration_favorite_candle_scents' , true );
							   $Participants= get_user_meta( $u_id , 'user_registration_participants_no' , true );
							   $goal_amount=get_user_meta( $u_id , 'user_registration_fr_goal' , true );
							   $go_toward=get_user_meta( $u_id , 'user_registration_fund_destination' , true );  
							   $selling_goal=get_user_meta( $u_id , 'user_registration_selling_goal' , true );
							   $payable=get_user_meta( $u_id , 'user_registration_check_payable' , true );
							   $cartContent = get_user_meta($u_id, 'cart-restore', true);
                         ?>
						 <?php $action_status= get_user_meta( $u_id, 'user_action_status' , true );
						/* date_default_timezone_set('America/Chicago');
								echo $current_time = date('Y-m-d H:i:s');	*/					 
						 if($action_status ==2) { ?>
						<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">
								<ul class="woocommerce-error" role="alert">
									<li class="lastItem firstItem">Your GLOW fundraiser has been successfully closed out. Any online sales profits will be sent on the 15th of the month. Thank you for choosing GLOW for your fundraising needs.
We look forward to working with you again!</li>
								</ul>
						</div>
						<?php } ?>
						<div class="top-grid">
							<h3>Fundraiser Info</h3>
							<span class="btn-grad"><a id="close_out"  data-id="<?php echo $current_user->ID; ?>">Close My Fundraiser</a>
								<img id="loading" src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/08/ajax-loader.gif" class="hide_load">
							</span>
							<?php if(!empty($cartContent)){ ?>
							<span class="restore-btn">
								<a id="restore_order">Move Saved Order To Cart</a>
								<img id="loading_r" src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/08/ajax-loader.gif" class="hide_load_restore">
							</span>
							<?php } ?>
						</div>
                        <ul>
                           <li>Your Fundraiser ID is: <strong><?php  echo $u_id;

                                //echo get_user_meta($current_user->ID,'user_registration_fund_id',true);

                            ?></strong></li>
							 <li>Your referral link is <span class="seller-link"><strong><?php echo get_site_url(); ?>?frid=<?php echo $current_user->ID; ?></strong></span></li>
                            <li>Your seller Registration link is <span class="seller-link"><strong><?php echo get_site_url(); ?>/seller-register/?frid=<?php echo $current_user->ID; ?></strong></span></li>
                           
						   <?php if(!empty($f)){?>
						   <li class="show_more">Fundraiser's Name: <strong><?php  echo $f." ".$l; ?></strong></li>
						   <?php } if(!empty($org)){?>
							 <li class="show_more">Organizations Name: <strong><?php  echo $org; ?></strong></li>
							<?php } if(!empty($sd)){?>
							  <li class="show_more">Start Date: <strong><?php  echo $sd; ?></strong></li>
							<?php } if(!empty($ed)){?>
							   <li class="show_more">End Date: <strong><?php  echo $ed; ?></strong></li>
							<?php } if(!empty($before)){?>
							   <li class="show_more">Have you had a Glow Fundraiser before?: <strong><?php  echo $before; ?></strong></li>
							   <?php } if(!empty($hear)){?>
							   <li class="show_more">How did you hear about Glow?: <strong><?php  echo $hear; ?></strong></li>
							   <?php } if(!empty($type)){?>
							   <li class="show_more">What are your favorite type of candle scents?: <strong><?php  echo $type; ?></strong></li>
							   <?php } if(!empty($referral)){?>
							   <li class="show_more">Name of referral: <strong><?php  echo $referral; ?></strong></li>
							   <?php } if(!empty($Participants)){?>
							   <li class="show_more">Number of Participants: <strong><?php  echo $Participants; ?></strong></li>
							   <?php } if(!empty($goal_amount)){?>
							   <li class="show_more">Fundraising Goal: <strong><?php  echo $goal_amount; ?></strong></li>
							   <?php } if(!empty($go_toward)){?>
							   <li class="show_more">Funds Go Toward: <strong><?php  echo $go_toward; ?></strong></li>
							   <?php } if(!empty($selling_goal)){?>
							   <li class="show_more">Selling Goal (# of Items): <strong><?php  echo $selling_goal; ?></strong></li>
							   <?php } if(!empty($payable)){?>
							   <li class="show_more">Checks Made Payable To: <strong><?php  echo $payable; ?></strong></li>
							   <?php } ?>
							   <li><a id="show_more">Show more</a><a id="edit_info" href="<?php echo get_site_url(); ?>/edit-fundraiser/?frid=<?php echo $current_user->ID; ?>">Edit info</a></li>
					   </ul>
                    </div>
                    
                    <div class="fundraiser-dashboard-section imp-links">
					<!--<a class="fr-btn" id="AddNewSeller">Add New Seller</a>-->
                        <a id="manual_seller" class="fr-btn">Add New Seller</a>
                        <a href="#" class="fr-btn mass-order">Submit Catalog Order</a>
                        <a href="/fundraiser-dashboard/fundraiser-reports/" class="fr-btn">Fundraiser Reports</a>
                        <a href="/seller-reports/" class="fr-btn">Seller Reports</a>
                    </div>
                    
                    <div class="fundraiser-dashboard-section active-sellers">
                        <h3>Active Sellers</h3>
                        <div class="fr-seller-list">
                            <div class="list-item list-header">
                                <div class="seller-name">Seller Name</div>
                                <div class="seller-join-date">Registered on</div> 
                            </div>
                            <?php
							
                            $frID2          = $current_user->ID; 
							$IDG=$_GET['frid'];
							if(!empty($IDG))
							{
								$frID=$IDG;
							}
							else
							{
								$frID = $frID2;
								
							}
                                /*$frID = $current_user->ID;*/
                                $all_meta_for_users = get_users(array(
                                    'meta_key'     => 'user_registration_seller_fundraiser',
                                    'meta_value'   => $frID,
                                ));
                                    foreach ( $all_meta_for_users as $all_meta_for_user ) {  ?>
                            <div class="list-item">
                                <div class="seller-name"><?php echo $all_meta_for_user->user_firstname; ?> <?php echo $all_meta_for_user->user_lastname; ?></div>
                                <div class="seller-join-date"><?php echo $all_meta_for_user->user_registered; ?></div>
								<div id="generation_btn" class="generation_btn" data-frid="<?php echo $frID; ?>" data-sellername="<?php echo $all_meta_for_user->user_firstname." ".$all_meta_for_user->user_lastname; ?>">Generate Link</div>
                            </div>  
                                   <?php } 
                              ?>
                        </div>
                        
                    </div>
                    
                </div>

				<?php get_sidebar(); ?>

			</div><!-- end row-fluid -->

		</div>
	</div><!-- end container -->
    <div class="mass-order-popup">
        <div class="mass-order-popup-wrapper">
            <a href="/spring-summer-catalogue-mass-order/" class="fr-btn">Spring Summer Catalogue</a>
            <a href="/digital-fall-winter-catalogue/" class="fr-btn">Digital Fall-Winter Catalogue</a>
            <a href="#" class="txt-link">Cancel</a>
        </div>
    </div>
	 <div class="manual-mass-order-popup">
        <div class="manual-mass-order-popup-wrapper">
            <a href="<?php echo get_site_url(); ?>/invitation/?frid=<?php echo $current_user->ID; ?>" class="fr-btn">Send Invitation</a>
             <a href="<?php echo get_site_url(); ?>/seller-register/?frid=<?php echo $current_user->ID; ?>" class="fr-btn">Add New Seller</a>
			 <a href="#" id="m-txt-link" class="txt-link">Cancel</a>
        </div>
    </div>
	 <div class="manual-mass-generation-popup">
        <div class="manual-mass-order-popup-wrapper">
		<span>Copy link and send to supporter, family & friends</span>
            <a id="generateLink" href=""></a>
            <a href="#" id="m-txt-link" class="txt-link">Cancel</a>
        </div>
    </div>
	<div class="manual-mass-close_out-popup">
        <div class="manual-mass-order-popup-wrapper">
		<span>Do you want to close fundraiser campaign?</span>
            <a id="close_out_ajax" class="txt-link" data-id="<?php echo $current_user->ID; ?>">Yes</a>
            <a href="#" id="m-txt-link" class="txt-link">No</a>
        </div>
    </div>
	<style>
	.show_more
	{
		display:none;
	}
	.fundraiser-dashboard-section
	{
		position:relative;
	}
	#close_out
	{
		color: #333;
		font-size: 18px;
		font-weight: 600;
		outline:none;
	}
	.btn-grad 
	{
		background: red;
		position:absolute;
		right:46px;
		padding:20px;
		color:#fff;
		
		
	}
	.btn-grad:hover 
	{ 
		background-position: right center; 
	}
	#show_more, #edit_info
	{
		display: inline-block;
    width: 12%;
    margin: 5px 2%;
    color: #fff;
    background: #000;
    text-align: center;
    padding: 4px;
    font-size: 16px;
    transition: all 0.5s ease 0s;
   
	}
	#show_more
	{
		 margin-left: 0;
	}
	.manual-mass-order-popup, 
	.manual-mass-generation-popup,
	.manual-mass-close_out-popup
	{
		position: fixed;
		width: 100%;
		height: 100%;
		z-index: 999;
		background: rgba(0,0,0,0.6);
		top: 0;
		left: 0;
		display: none;
	}
	.manual-mass-order-popup .manual-mass-order-popup-wrapper,
	.manual-mass-generation-popup .manual-mass-order-popup-wrapper,
	.manual-mass-close_out-popup .manual-mass-order-popup-wrapper
	{
    display: block;
    width: 100%;
    max-width: 500px;
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
.manual-mass-order-popup a.fr-btn {
    display: block;
    margin: 20px auto;
    width: 100%;
    max-width: 250px;
}
.manual-mass-generation-popup .manual-mass-order-popup-wrapper,
.manual-mass-close_out-popup .manual-mass-order-popup-wrapper
{
    padding: 30px 25px;
    max-width: 600px;
    border: 3px solid #cda85c; 
}
.manual-mass-generation-popup span,
.manual-mass-close_out-popup span
{
    padding-bottom: 6px;
    display: inline-block;
    font-size: 18px;
    font-weight: 700;
    border-bottom: 2px dotted #cda85c;
    color: #cda85c;
}
#generateLink
{
    padding-top: 15px;
    display: inline-block;
    padding-bottom: 20px;
    font-size: 15px;
    width: 100%;
}
.manual-mass-generation-popup #m-txt-link,
.manual-mass-close_out-popup #m-txt-link
{
	    background: #cda85c;
    color: #fff;
    padding: 6px 30px;
    display: inline-block;
    transition: all 0.5s ease 0s;
}
.manual-mass-generation-popup #m-txt-link:hover,
.manual-mass-close_out-popup #m-txt-link:hover
{
	   background: #fff;
    color: #cda85c;
	border:1px solid #cda85c;
	    box-sizing: border-box;
}
.manual-mass-close_out-popup span
{
	width: 100%;
    margin-bottom: 20px;
}
#close_out_ajax
{
	background: #cda85c;
    color: #fff;
    padding: 6px 30px;
    display: inline-block;
    transition: all 0.5s ease 0s;
}
.manual-mass-close_out-popup #close_out_ajax:hover
{
	   background: #fff;
    color: #cda85c;
	border:1px solid #cda85c;
	    box-sizing: border-box;
}
	</style>
	<script>
		jQuery(document).ready(function () 
		{
			jQuery(document).on('click', '#show_more', function (e) 
			{
				e.preventDefault();
				jQuery(".show_more").css("display", "block");
			});
			jQuery(document).on('click', '#manual_seller', function (e) 
			{
				e.preventDefault();
				jQuery(".manual-mass-order-popup").addClass("show");
			});
			jQuery(document).on('click', '#close_out', function (e) 
			{
				e.preventDefault();
				jQuery(".manual-mass-close_out-popup").addClass("show");
			});
			jQuery(document).on('click', '#generation_btn', function (e) 
			{
				e.preventDefault();
				let fund_id = jQuery(this).data('frid');
				let sellername = jQuery(this).data('sellername');
				let link="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>?frid="+fund_id+"&seller_name="+sellername;
				jQuery("#generateLink").attr("href",link);
					jQuery("#generateLink").text(link);
				jQuery(".manual-mass-generation-popup").addClass("show");
			});
			
			jQuery(document).on('click', '#m-txt-link', function (e) 
			{
				e.preventDefault();
				jQuery(".manual-mass-order-popup,.manual-mass-generation-popup,.manual-mass-close_out-popup").removeClass("show");
			});
			jQuery('#close_out_ajax').click(function()
			{
				let fund_id = jQuery(this).data('id');
				jQuery("#loading").removeClass("hide_load");
				jQuery("#loading").addClass("show_load");
				
				jQuery.ajax({

					type : "post",

					url : ajaxurl,

					data : {action: 'close_out_fundraiser', fund_id:fund_id},
	
					success: function(response) {
						console.log(response);
						jQuery("#loading").removeClass("show_load");
						jQuery("#loading").addClass("hide_load");
						jQuery(".fundraiser-dashboard-section.link-generation .lastItem").after('<p style="padding-top:6px;color:green;font-size: 16px;">'+response+'</p>');
						jQuery(".manual-mass-close_out-popup").removeClass("show");
					}
				});

			});
	  
	  
});
</script>
<script>
jQuery('#restore_order').click(function(){
jQuery("#loading_r").removeClass("hide_load_restore");
				jQuery("#loading_r").addClass("show_load_restore");
         jQuery.ajax({

         type : "post",

         url : ajaxurl,

         data : {action: 'wdm_restore_cart_details'},

         success: function(response) {
	jQuery("#loading_r").removeClass("show_load_restore");
						jQuery("#loading_r").addClass("hide_load_restore");
        console.log(response);
		jQuery(".restore-btn").after('<p style="top: 116px; color: green;position: absolute;right: -33px;padding: 20px;">Success!!</p>');
		window.location.href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>checkout/";
    }
});

});
</script> 
<?php
	get_footer();
?>
