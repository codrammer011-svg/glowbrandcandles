
<?php 
	get_header();
	$current_user = wp_get_current_user();
	$u_id = $current_user->ID;
	$IDG=$_GET['frid'];
	if(!empty($IDG))
	{
		$u_id  = $IDG;
	}
	else
	{
	}
	if ( is_user_logged_in() ) {
		
		 $mergeID=get_user_meta( $u_id, 'wc_ship_mergeOrderID', true );
		 $mergeQuanty=get_user_meta( $u_id, 'wc_ship_mergeOrderQuanty', true );
		 
		 $ajax_cookies_val=get_user_meta( $u_id,"skip_popup_FD",true);
	}
	else
	{
		 
	}



						if($u_id==205821)
							   {
								  
								   $fund_desc=get_user_meta($u_id,'fund_profile_description',true);
									$fund_image=get_user_meta($u_id,'fund_profile_image',true);
							   }
							   else
							   {
								   
								   $fund_desc=get_user_meta($u_id,'fund_profile_description',true);
									$fund_image=get_user_meta($u_id,'fund_profile_image',true);
									
							   }




?>
  <link rel='stylesheet' href="<?php echo get_template_directory_uri(); ?>/css/custom-style.css">
    <style>

    	.dg-panel-0 .panel-container.dg-panel {
    border: 0px solid #efeeee;
}
    	.content.style-scope.events-controller.fundraiser_dashboard_design {
    margin: 0;
    height: 0;
    padding: 0;
}
.footer-top.footer-top-3 {
    height: 0;
}
    	.woocommerce-NoticeGroup.woocommerce-NoticeGroup-checkout {
    display: none;
}
    .owl-carousel
 {
    position: relative;
}

.testimonial-item {
  background: #f9f9f9;
  padding: 5px;
}
.owl-nav {
    text-align: center;
    position: absolute;
    top: 20%;
    width: 100%;
    display: flex
;
    justify-content: space-between;
}
.owl-carousel .owl-nav button.owl-next, .owl-carousel .owl-nav button.owl-prev, .owl-carousel button.owl-dot {
    font-size: 80px !important;
}


	section.main-section.section_one.position-relative {
		background: #26273a;
}
.fixed-already { display:none !important; }
.ds_tech_popup { display:none; position: fixed; top: 0; left: 0; width: 100%; background: #000000c9; z-index: 100000; height: 100%; right: 0;  bottom: 0; margin: auto; transition: none; }
.ds_tech_popup .pp_wrpr {  display: flex; justify-content: center; width: 100%; height: 100%; margin: auto; align-items: center; transition: none; }
.ds_tech_popup .ds_pp_box { background: #fff; padding: 10px; position: relative; border-radius: 5px; width: 100%; max-width: 500px; }
.ds_tech_popup span.pp_closer { position: absolute; top: 0; right: 0; width: 30px; height: 30px; background: #000; }
.ds_tech_popup span.pp_closer:before, .ds_tech_popup span.pp_closer:after { content: ""; width: 23px; height: 1px;  background: #ffffff; display: block; transform: rotate(45deg); position: absolute; top: 15px; left: 5px; }
.ds_tech_popup span.pp_closer:after { transform: rotate(-45deg); }
.ds_tech_popup .pp_wrpr .header { padding: 15px; font-weight: 600; font-family: NoirPro-Bold; background: #000000; color: #fff; }

.parent_profile_cls .fundraiser-status span { color: #fff; font-size: 5rem; line-height: 110%; }

.cstm_btn_styl { padding: 5px 30px; transition: background-color 200ms linear; border-radius: 2px; border-radius: 15px; color: #fff; background-color: #4990e2; font-size: 14px; }

	.change-photo { color: #fff; background: #000; display: inline-block; font-weight: 400; text-align: center; border: 1px solid transparent; padding: 7px 13px; font-size: 12px; line-height: 1.5; border-radius: .25rem; margin-top: 10px; }
	.change-photo:hover, .change-photo:focus { color: #fff; border-color: #000; background: #000; opacity: 0.7; }

	  .glow-report-section .final-report.item.no-border.style-scope.organizer-fundraisers-detail{
			background: rgb(0 128 0 / 75%);
			background: #17c671;
			padding-left: 20px;
			padding-right: 20px;
	  }
	  .glow-report-section .item.organizer-fundraisers-detail.final-report .label.organizer-fundraisers-detail {
			color: #fff;
			font-weight: 700;
	  }
	  .glow-report-section .item.organizer-fundraisers-detail.final-report  .value.organizer-fundraisers-detail a{
			padding: 8px 20px 8px;
			background: #000000db;
			color: #fff !important;
			border-radius: 30px;
			letter-spacing: 0.2px;
	  }

	
/* Optional animation */
@keyframes fadeIn {
  from {opacity: 0; transform: scale(0.9);}
  to {opacity: 1; transform: scale(1);}
}
  </style>
  <script>


  


  jQuery(document).ready(function () {
	
	// jQuery('.ds_pp_box').click(function(e){
	// 	e.stopPropagation();
	// });
	// jQuery('.ds_popup').click(function(e){

	// 	e.preventDefault();
	// 	e.stopPropagation();
	// 	var val = jQuery(this).data('id');

	// 	//jQuery(val).fadeIn();
	// 	jQuery(val).css('display','block');
	// });
	// jQuery('.pp_closer').click(function(){
	// 	jQuery('.ds_tech_popup').fadeOut();
	// });
	// jQuery(document).click(function() {
	// 	jQuery('.ds_tech_popup').fadeOut();
	// });
	
	// fundraiser details 
		  // $('.boxWrpr.box-nine').on('click', function(e){
    //     e.preventDefault();
    //     $('#fundraiserPopup').fadeIn(200).css('display', 'flex');
    // });

   
    // $('.popup-close, .popup-overlay').on('click', function(e){
    //     if ($(e.target).is('.popup-close') || $(e.target).is('.popup-overlay')) {
    //         $('#fundraiserPopup').fadeOut(200);
    //     }
    // });



		  // $('.boxWrpr.box-ten').on('click', function(e){
    //     e.preventDefault();
    //     $('#seller_registration_link').fadeIn(200).css('display', 'flex');
    // });

   
    // $('.popup-close, .popup-overlay').on('click', function(e){
    //     if ($(e.target).is('.popup-close') || $(e.target).is('.popup-overlay')) {
    //         $('#seller_registration_link').fadeOut(200);
    //     }
    // });


		  // $('.boxWrpr.box-eight').on('click', function(e){
    //     e.preventDefault();
    //     $('#seller_shopping_link').fadeIn(200).css('display', 'flex');
    // });

   
    // $('.popup-close, .popup-overlay').on('click', function(e){
    //     if ($(e.target).is('.popup-close') || $(e.target).is('.popup-overlay')) {
    //         $('#seller_shopping_link').fadeOut(200);
    //     }
    // });






    //  $('.boxWrpr.box-twelve').on('click', function(e){
    //     e.preventDefault();
    //     $('#registeredSellersPopup').fadeIn(200).css('display', 'flex');
    // });

    // // Close when clicking close button or outside popup
    // $('.popup-close, .popup-overlay').on('click', function(e){
    //     if ($(e.target).is('.popup-close') || $(e.target).is('.popup-overlay')) {
    //         $('#registeredSellersPopup').fadeOut(200);
    //     }
    // });
	  
		jQuery("a.walk-through").hover(
			  function () {
				jQuery(".hoverbox").toggleClass("result_hover")      
			  }
		);	
		jQuery(document).on('click', '#CopyID', function (e) 
		{
			e.preventDefault();
			var $temp = jQuery("<input>");
			jQuery("body").append($temp);
			$temp.val(jQuery('#selectableArea').text()).select();
			document.execCommand("copy");
			$temp.remove();
			jQuery('#CopyID .copy-code').text('Copied');
		});
		jQuery(document).on('click', '#CopyProfileLink', function (e) 
		{
			e.preventDefault();
			var $temp = jQuery("<input>");
			jQuery("body").append($temp);
			$temp.val(jQuery('#selectableAreaProfile').text()).select();
			document.execCommand("copy");
			$temp.remove();
			jQuery('#CopyProfileLink .copy-code').text('Copied');
		});
		jQuery(document).on('click', '#SellerCopyLink', function (e) 
		{
			e.preventDefault();
			var $temp = jQuery("<input>");
			jQuery("body").append($temp);
			$temp.val(jQuery('#sellerLink').text()).select();
			document.execCommand("copy");
			$temp.remove();
			jQuery('#SellerCopyLink .copy-code').text('Copied');
		});
		jQuery(document).on('click', '#SellerCopyLink2', function (e) 
		{
			e.preventDefault();
			var $temp = jQuery("<input>");
			jQuery("body").append($temp);
			$temp.val(jQuery('#sellerLink2').text()).select();
			document.execCommand("copy");
			$temp.remove();
			jQuery('#SellerCopyLink2 .copy-code').text('Copied');
		});
		jQuery(document).on('click', '#CopyRefLink', function (e) 
		{
			e.preventDefault();
			var $temp = jQuery("<input>");
			jQuery("body").append($temp);
			$temp.val(jQuery('#refLink').text()).select();
			document.execCommand("copy");
			$temp.remove();
			jQuery('#CopyRefLink .copy-code').text('Copied');
		});
		
		jQuery('#savedOrder').click(function()
		{
			jQuery("#loading_r").removeClass("hide_load_restore");
			jQuery("#loading_r").addClass("show_load_restore");
			var ajaxurl="https://glowbrandscandles.com/wp-admin/admin-ajax.php";
			 jQuery.ajax({

			 type : "post",

			 url : ajaxurl,

			 data : {action: 'wdm_restore_cart_details'},

			 success: function(response) {
							jQuery("#loading_r").removeClass("show_load_restore");
							jQuery("#loading_r").addClass("hide_load_restore");
							console.log(response);
							window.location.href="https://glowbrandscandles.com/checkout/";
							}
			});

		});
});
  </script>
  <style>
  #C .isDisabled {
  color: currentColor;
  pointer-events: none;
  opacity: 0.5;
  text-decoration: none;
}
  .fundraiser-rebooking
  {
	  width: 100%;
    text-align: right;
  }
  .rebooking-btn
  {
    background: green;
    color: #fff;
    padding: 12px 25px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 0.9px;
    text-transform: uppercase;
  }
  .item.no-border.style-scope.organizer-fundraisers-detail.profile-section
  {
	  background: #000;
    padding-left: 20px;
    padding-right: 20px;
    margin-left: -20px;
    margin-right: -20px;
  }
   .item.no-border.style-scope.organizer-fundraisers-detail.profile-section .label.organizer-fundraisers-detail
   {
	   color:#fff;
	   font-weight: bold;
   }
   .item.no-border.style-scope.organizer-fundraisers-detail.profile-section .label small
   {
	   opacity: 0.8;
		color: #fff;
   }
   .item.no-border.style-scope.organizer-fundraisers-detail.last-space.right-seller-link2
   {
	       padding-bottom: 40px;
   }
   .header.organizer-fundraisers-detail.tp
   {
	   width:83%;
	   padding-bottom:0px;
	   margin-bottom:0px;
   }
   .fundraiser-status-0 .container-status.fundraiser-status
   {
	   padding-top:10px;
   }
   @media (max-width:870px)
   {
	   .item.no-border.style-scope.organizer-fundraisers-detail.last-space.right-seller-link2
		{
	       padding-bottom: 20px;
		}
   }
    .progressBar {
    margin: 25px 0px 15px;
    width: 100%;
    height: 12px;
    background: rgb(239, 238, 238);
}
.progress-bar {
    background: #cda85c;
    height: 28px;
    margin-bottom: 10px;
    position: relative;
    color: #fff;
}
.progress-bar {
    height: 12px;
    background: rgb(23, 198, 113);
}
.bar-sec{
    width: 83%;
    float: left;
}
.image_sec
{
    width: 15%;
}
.ccbzhW {
   display: inline-block;
   flex-flow: row wrap;
   -webkit-box-pack: justify;
   justify-content: space-between;
}
.eoCznv {
   display: flex;
	 float: left;
}
.jZDald {
    width: auto;
    color: rgb(50, 50, 50);
    font-size: 20px;
    font-style: normal;
    line-height: 40px;
    font-weight: 600;
}
.eYHKCV {
    color: rgb(50, 50, 50);
    font-size: 20px;
    font-style: normal;
    line-height: 40px;
    font-weight: 600;
}
.eYHKCV {
    padding-left: 6px;
}
#remianing-left{
	color:red;
	    display: inline-block;
  //  padding-left: 10px;
}
.value.event-payout {
    margin-left: auto;
    color: #4a4a4a;
	float: right;
}
.highlight-green.event-payout {
    color: #17c671;
}
.event-payout-0 .panel.event-payout{
    margin-top: 0;
}
.header.organizer-fundraisers-detail
{
	margin-bottom: 25px;
    border-bottom: 0;
}
.fundraiser-status-0.image_sec .container-status.fundraiser-status
{
	padding: 0;
    margin: 20px;
    margin-top: -22px;
    margin-bottom: 0;
    margin-right: 0;
}
.header.organizer-fundraisers-detail.seller-dashboard-progressbar-and-image
{
    margin-bottom: 4px;	
}
@media (max-width:870px)
{
	.header.organizer-fundraisers-detail.tp {
    width: 100%;
	}
.header.organizer-fundraisers-detail.tp .organizer-fundraisers-detail,
.header.organizer-fundraisers-detail .organizer-fundraisers-detail.fundraiser-status-0
{
	text-align: center;
    width: 100%;
}
.dg-header-1-1 h1.dg-header-1
{
	font-size: 25px;
}
.fundraiser-status-0 .title.fundraiser-status
{
	font-size: 19px;
	padding: 10px 0;
}
.fundraiser-status-0 .container-status.fundraiser-status 
{
    padding-top: 3px;
}
.header.organizer-fundraisers-detail .organizer-fundraisers-detail.fundraiser-status-0.image_sec
{
	float: none;
    text-align: center;
	width: 100%;
}
.header.organizer-fundraisers-detail .organizer-fundraisers-detail.fundraiser-status-0.image_sec .container-status.fundraiser-status
{
	width: 110px;
    display: inline-block;
    margin: 15px;
	margin-bottom: 0;
}
.bar-sec {
    width: 100%;
    float: left;
}
.header.organizer-fundraisers-detail
{
	padding-bottom:0;
}
}
@media screen and (max-width: 580px)
{
	.header.organizer-fundraisers-detail .organizer-fundraisers-detail, .header.organizer-fundraisers-detail .organizer-fundraisers-detail {
		padding: 0 12px;
	}
	.fundraiser-status-0 .title.fundraiser-status,
	.fundraiser-status-0 .live.fundraiser-status{
		font-size: 16px;
	}
	.dg-header-1-1 h1.dg-header-1 {
		font-size: 20px;
	}
	.progressBar
	{
		margin-bottom: 3px;
		margin-top: 16px;
	}
	.jZDald,
	.eYHKCV,
	.eYHKCV
	{
		font-size: 17px;
		line-height: 1.6;
	}
	.woocommerce-error .uploadImage
	{
		top: 36px;
	}
}
  </style>
<!-- new html -->

    <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__); ?>/assets/css/default.css" />
    <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__); ?>/assets/css/responsive.css" />




    <section class="main-section section_one position-relative">
        <div class="container">
            <div class="main-heading">
                <h1><span>GLOW</span>& Go!</h1>
                <h2>Fundraiser Dashboard</h2>
            </div>
            <div class="row">
                <div class="col-md-4">
                	<a href="javascript:;" data-id="#custom_store" class="ds_popup">
                    <div class="boxWrpr box-one">
                        <figure>
                            <img class="img_icon" src="<?php echo plugin_dir_url(__FILE__); ?>assets/images/box-1.png" alt="*">
                        </figure>
                        <article>
                            <h3>Customize Online Store</h3>
                        </article>
                    </div>
                </a>
                </div>
                <div class="col-md-8">
                    <div class="boxWrpr box-two empty-box">
                    	<div class="d-flex">
							<div class="row">
								<div class="col-sm-8">
									<div class="parent_profile_cls">
										<div class="style-scope organizer-fundraisers-detail x-scope dg-header-1-1" large-screen="">
											<?php $org = get_user_meta($u_id,'user_registration_name_of_school_business' , true); ?>
										   <h3 class="no_width"><?php echo $org; ?></h3>
										</div>
										<div class="style-scope organizer-fundraisers-detail x-scope fundraiser-status-0">
											<!--<div class="container-status style-scope fundraiser-rebooking">
											<a id="rebook_ajax" class="rebooking-btn">Rebooking</a>
											</div>-->
										   <div class="container-status style-scope fundraiser-status">
											  <span>Fundraiser Campaign is </span>
											  <?php  if($action_status !=2) { ?>
												<span class="status live style-scope fundraiser-status">Active</span>
											  <?php } else { ?>
											   <span class="status live style-scope fundraiser-status" style="color:red;">Inactive</span>
											  <?php } ?>
											 <a class="walk-through fa fa-file-pdf-o" data-toggle="tooltip" title="Print Fundraiser Instructions here" href="https://glowscentedcandles.com/wp-content/uploads/2020/10/Fundraiser-Dashboard-Glow-Scented-Candles-4-copy.pdf" target=" _blank"></a>
										   <!--<div class="hoverbox"><span class="">Print Fundraiser instructions here.</span></div>-->
										   </div>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="container-status style-scope fundraiser-status prfl_pic">
									   <?php 
									   $image_url_profile=get_user_meta($u_id,"user_registration_image_url_profile",true); 
										if(!empty($fund_image))
										{  
											$crop_url_fund_image=profile_image_resize_crop( $src=$fund_image, $w=500, $h=500, $dest = null, $override = true, $createNewIfExists = true );
										}
										 else if(!empty($image_url_profile))
										 {  
											$crop_url_image_url_profile=profile_image_resize_crop( $src=$image_url_profile, $w=500, $h=500, $dest = null, $override = true, $createNewIfExists = true );
										 }
										 else
										 {
										 }
									   ?>
											<img class="profile-seller extra_profile_image w-25" src="<?php if(!empty($fund_image)){  echo $crop_url_fund_image; } else if(!empty($image_url_profile)){  echo $crop_url_image_url_profile; } else{ ?>https://glowscentedcandles.com/wp-content/uploads/2021/02/3135715.png<?php } ?>"/>
									   <a href="https://glowbrandscandles.com/upload-image-and-description/?type=image" class="btn btn-primary change-photo" > <i class="fa fa-fw fa-camera"></i> <span>Change photo</span> </a>
									   </div>
								</div>
							</div>

			
			</div>


                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box-three round-box">
                        <figure>
                            <img src="<?php echo plugin_dir_url(__FILE__); ?>assets/images/box-3-bg.png" alt="*">
                            <figcaption>
                                <h2>4.32%</h2>
                                <p>3 days left</p>
                            </figcaption>
                        </figure>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="boxWrpr box-four">
                        <figure>
                            <figcaption>
                                <div class="inner-boxes">
                                    <div class="boxWrpr inner-box">
                                        <h3 id="append_total_raised"></h3>
                                        <p>TOTAL RAISED</p>
                                    </div>
                                    <div class="boxWrpr inner-box">
                                        <h3 id="append_goal"></h3>
                                        <p>GOAL</p>
                                    </div>
                                </div>
                                <ul>
                                    <li>Online Sales <i>(ship to home)</i></li>
                                    <li>Online Sales <i>(ship to school)</i></li>
                                    <li>Brochure Sales</li>
                                </ul>
                            </figcaption>
                        </figure>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="boxWrpr box-five">
                        <div class="box-head">
                            <h3>YOUR CHECKLIST</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="boxWrpr box-six">
                        <div class="box-head">
                            <h3>SALES SNAPSHOT</h3>
                        </div>
                        <div class="six-content">
                            <div class="six-cont-detail">
                                <p>Live Sales</p>
                                <h4>$2.720</h4>
                                <p>Online</p>
                            </div>
                            <div class="line-left six-cont-detail">
                                <p>Sellers</p>
                                <h4>45 <span>Sellers</span></h4>
                                <p>$96 <i>avg/seller</i></p>
                            </div>
                        </div>
                        <div class="six-cont-two">
                            <div class="six-cont-detail-two">
                                <h5>Boost your glow!</h5>
                                <p>Add a ‘Top Sharer’ candle bonus this week!</p>
                            </div>
                            <figure>
                                <img src="<?php echo plugin_dir_url(__FILE__); ?>assets/images/box-6.png" alt="*">
                            </figure>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                   
                   <a href="https://glowbrandscandles.com/docs/word.php?fund_id=<?php echo $u_id; ?>">
                    <div class="boxWrpr box-seven">
                        <figure>
                            <img  class="img_icon" src="<?php echo plugin_dir_url(__FILE__); ?>assets/images/box-7.png" alt="*">
                        </figure>
                        <article>
                            <h3>Participant Letter</h3>
                        </article>
                    </div>
                </a>
                </div>
                <div class="col-md-8">
                	<a href="javascript:;" data-id="#seller_shopping_link" class="ds_popup">
                    <div class="boxWrpr box-eight">
                        <div class="scaning-img">
                            <figure>
                                <img class="img_icon" src="<?php echo plugin_dir_url(__FILE__); ?>assets/images/box-8-1.png" alt="*">
                            </figure>
                           
                        </div>
                        <article>
                            <h3 class="no_width">Share Online Group Shopping Link</h3>
                        </article>
                    </div>
                </a>
                </div>
                <div class="col-md-4">
                		<a href="javascript:;" data-id="#fundraiserPopup" class="ds_popup">
                    <div class="boxWrpr box-nine">
                        <figure>
                            <img class="img_icon" src="<?php echo plugin_dir_url(__FILE__); ?>assets/images/box-9.png" alt="*">
                        </figure>
                        <article>
                            <h3>Fundraiser Details</h3>
                        </article>
                    </div>
                </a>
                </div>
                <div class="col-md-8">
                	<a href="javascript:;" data-id="#seller_registration_link" class="ds_popup">
                    <div class="boxWrpr box-ten">
                        <div class="scaning-img">
                            <figure>
                                <img class="img_icon" src="<?php echo plugin_dir_url(__FILE__); ?>assets/images/box-10.png" alt="*">
                            </figure>
                            <?php $fileUpload_full_link2=get_user_meta($u_id,'fundraiser_seller_register_barcode_link',true); 
						   if(!empty($fileUpload_full_link2)) { ?>
                            <figure>
                                <img src="<?php echo $fileUpload_full_link2; ?>" alt="*">
                            </figure>
                        <?php } ?>
                        </div>
                        <article>
                            <h3 class="no_width">Share Seller Registration Link</h3>
                        </article>
                    </div>
                </a>
                </div>
                <div class="col-md-4">
                	<a href="javascript:;" data-id="#toolbox" class="ds_popup">
                    <div class="boxWrpr box-eleven">
                        <figure>
                            <img class="img_icon" src="<?php echo plugin_dir_url(__FILE__); ?>assets/images/box-11.png" alt="*">
                        </figure>
                        <article>
                            <h3>Toolbox for Marketing</h3>
                        </article>
                    </div>
                </a>
                </div>
                <div class="col-md-8">
                	<a href="javascript:;" data-id="#registeredSellersPopup" class="ds_popup">
                    <div class="boxWrpr box-twelve">
                        <figure>
                            <img class="img_icon" src="<?php echo plugin_dir_url(__FILE__); ?>assets/images/box-12.png" alt="*">
                        </figure>
                        <article>
                            <h3 class="no_width">Show list of Registered Sellers</h3>
                        </article>
                    </div>
                </a>
                </div>
                <div class="col-md-4">
                    <div class="boxWrpr box-thirteen">
                        <figure>
                            <img class="img_icon" src="<?php echo plugin_dir_url(__FILE__); ?>assets/images/box-13.png" alt="*">
                        </figure>
                        <article>
                            <h3>Download Invoice</h3>
                        </article>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="boxWrpr box-fourteen">
                        <figure>
                            <img class="img_icon" src="<?php echo plugin_dir_url(__FILE__); ?>assets/images/box-14.png" alt="*">
                        </figure>
                        <article>
                        <h3 class="no_width">Claim Form /Replacements</h3>
                    </article>
                    </div>
                </div>
                <div class="col-md-12">
                <div class="boxWrpr box-fifteen">
                    <figure class="bg-img">
                        <figcaption>
                            <div class="scaning-img">
                             <a href="<?php echo get_site_url(); ?>/spring-summer-catalogue-mass-order/" >
                                <figure>
                                    <img src="<?php echo plugin_dir_url(__FILE__); ?>assets/images/box-15-1.png" alt="*">
                                    <figcaption>
                                        <h6>SPRING SUMMER CATALOG</h6>
                                    </figcaption>
                                </figure>
                            </a>
                            <a href="<?php echo get_site_url(); ?>/digital-fall-winter-catalogue/" >
                                <figure class="offer-two">
                                    <img src="<?php echo plugin_dir_url(__FILE__); ?>assets/images/box-15-2.png" alt="*">
                                    <figcaption>
                                        <h6>FALL WINTER CATALOG</h6>
                                    </figcaption>
                                </figure>
                            </a>
                            </div>
                            <h3 class="no_width">Submit your Brochure Orders</h3>
                        </figcaption>
                    </figure>
                </div>
            </div>
            <div class="col-md-12">
                
                <a href="https://glowbrandscandles.com/fundraiser-dashboard/fundraiser-reports/">
                	<div class="boxWrpr box-sixteen">
                    <figure class="bg-img">
                        <figcaption>
                            <figure>
                                <img src="<?php echo plugin_dir_url(__FILE__); ?>assets/images/box-16.png" alt="*">
                            </figure>
                                <article>
                                    <h3 class="no_width">Sales Report</h3>
                                </article>
                        </figcaption>
                    </figure>
                </div>
            </a>
            </div>
            <div class="col-md-12">

                <div id="close_out" class="boxWrpr box-seventeen">
                    <figure>
                        <img src="<?php echo plugin_dir_url(__FILE__); ?>assets/images/box-17.png" alt="*">
                    </figure>
                    <article>
                        <h3 class="no_width">Close Virtual Fundraiser</h3>
                    </article>
                </div>
         


            </div>
        </div>


  <div class="boxWrpr box-eighteen">
            <div class="fix_order_Table_Head"> 
				<table class="order_roports_header" border=1>
				<?php 
				
					
						 $count_args = array(
                            'type'  => 'shop_order',
                            'meta_key' => 'fundraiser_field',
							'meta_value' => $u_id,
							'limit' => -1
						   );
						$c_orders = wc_get_orders( $count_args); 
						
						
				?>		
				<?php  
				// code for get tracking no.
					
					
				?>
					
					<thead>
					<tr>
					<th colspan=11><center><h3 class="no_width">Check your order status and tracking information</h3></center></th>
					</tr>
					<tr>
						<th><center>Order ID</center></th>
						<!-- <th><center>Seller ID</center></th> -->
						<th><center>Seller Name</center></th>
						<th><center>Customer Name</center></th>
						<th><center>Fund ID</center></th>
						<th><center>Order Date</center></th>
						<th><center>Order Status</center></th>
						<!-- <th><center>Order Status</center></th> -->
						<th><center>Tracking No.</center></th>
						<th><center>Report Missing/Damaged Items</center></th>
						<th><center>Order Detail</center></th>
						<th><center>Invoice</center></th>
						
					</tr>
					</thead>
				
				
					<?php 
					
					foreach( $c_orders as $c_order ){
						$s_name="";
						$order_id = $c_order->get_id();
						$order_status2="";
						$order_status1="";
						$order_status1 = $c_order->get_status(); 
						$order_status2 = str_replace(' ', '', $order_status1);
						if($order_status2=="processing" || $order_status2=="completed" || $order_status2=="waiting-call" || $order_status2=="on-hold"){
						
						$results = $wpdb->get_results("
					select comment_content from wp_comments where comment_post_id=$order_id AND  `comment_content` LIKE  '%tracking number%';
					");
					
					$order_full_date =$c_order->get_date_created();
					$timestamp = strtotime($order_full_date);
					$new_short_date_format = date('Y-m-d', $timestamp);
					$order_data = $c_order->get_data();
					$order_billing_first_name = $order_data['billing']['first_name'];
					$order_billing_last_name = $order_data['billing']['last_name'];
					 //echo $new_short_date_format ."<br>";
					 $c_order_id = $c_order->get_id();
						?>
						<tbody>
						<tr>
						<?php
							$s_id = get_post_meta( $c_order_id, 'wc_seller_field',true ); 
						//	$s_first_name = get_user_meta($s_id, 'first_name', $single);
							//$s_last_name = get_user_meta($s_id, 'last_name', $single);  
							//$s_name = $s_first_name.' '.$s_last_name;
							if($s_id=="custom")
							{
								$s_name=get_post_meta($c_order_id, 'wc_seller_field_custom', true);
							}
							else if(is_numeric($s_id))
							{
								$s_first_name = get_user_meta($s_id, 'first_name', true);
								$s_last_name = get_user_meta($s_id, 'last_name', true);  
								$s_name = $s_first_name.' '.$s_last_name;
							}
							else{}
						?>
							<td>#<?php echo $c_order->get_id();  ?></td>
							<!-- <td><?php echo $s_id[0];  ?></td> -->
							<td><?php echo $s_name;  ?></td>
							<td><?php echo $order_billing_first_name . ' '. $order_billing_last_name;  ?></td>
							<td><?php  echo $u_id; ?></td>
							<td><?php echo $new_short_date_format;  ?></td>
							<!--<td><?php //echo $c_order->get_id();  ?></td> -->
							<td>
							<?php 
								if($c_order->get_status() =='waiting-call'){
									echo 'Ship To School';  
								}
								else{
									echo $c_order->get_status();
								}
							?>
							</td>
							<td><?php foreach( $results as $result ){
						$data_track = $result->comment_content;
						$tack_number = explode(" ",$data_track);
						$name = "number"; //name to be searched
						$foundIndex = array_search($name, $tack_number);
						$remove_data =  $tack_number[$foundIndex+1];
						$final_value = substr($remove_data, 0, -1);
						?>
						<a href="https://www.ups.com/track?loc=en_US&tracknum=<?php echo $final_value; ?>&requester=WT/trackdetails\" target="_blank"><?php echo $final_value; ?></a>
						<?php 
						}  ?></td>
						<td>
						<?php  
							$order_status = $c_order->get_status();
						
						if(  $order_status == 'completed'){
						
						?>
						<a href="<?php echo get_site_url().'/my-account/view-my-order/'.$c_order->get_id(); ?>" target="_blank">Submit</a>
						<?php  
						}
						?>
						</td>
						<td>
							<button class='myBtn'value=<?php echo $c_order->get_id();?> style="border-radius:8px; background-color: #17c671;">check</button>
							<div id="myModal" class="modal">
								<!-- Modal content -->
								<div class="modal-content">
									<span class="close">×</span>
									<div class="order_details">
										<h3 style="text-align: center;margin-top: 20px;"><u>ORDER DETAILS</u></h3>
										<h4>
										Order No : = <?php echo $c_order->get_id(); ?></h4>
										<b>Seller name : -  <?php echo $s_name;  ?>
										</b><table>
										<?php 
										$order_data = $c_order->get_data();
										
										$order_billing_first_name = $order_data['billing']['first_name'];
										$order_billing_last_name = $order_data['billing']['last_name'];
										$billing_name = $order_billing_first_name.' '.$order_billing_last_name;
										$order_billing_address_1 = $order_data['billing']['address_1'];
										$order_billing_address_2 = $order_data['billing']['address_2'];
										$order_billing_city = $order_data['billing']['city'];
										$order_billing_state = $order_data['billing']['state'];
										$order_billing_postcode = $order_data['billing']['postcode'];
										$order_billing_country = $order_data['billing']['country'];
										
										$billing_address = $order_billing_address_1.'<br>'. $order_billing_address_2.' '. $order_billing_city.' '.$order_billing_state.' '.$order_billing_postcode.' '.$order_billing_country; 
										
										$order_billing_email = $order_data['billing']['email'];
										$order_billing_phone = $order_data['billing']['phone'];
										
										$order_shipping_first_name = $order_data['shipping']['first_name'];
										$order_shipping_last_name  = $order_data['shipping']['last_name'];
										$shipping_name = $order_shipping_first_name.' '.$order_shipping_last_name ;
										$order_shipping_address_1 = $order_data['shipping']['address_1'];
										$order_shipping_address_2 = $order_data['shipping']['address_2'];
										$order_shipping_city = $order_data['shipping']['city'];
										$order_shipping_state = $order_data['shipping']['state'];
										$order_shipping_postcode = $order_data['shipping']['postcode'];
										$order_shipping_country = $order_data['shipping']['country'];
										
										$shipping_address = $order_shipping_address_1.'<br>'. $order_shipping_address_2.' '. $order_shipping_city.' '.$order_shipping_state.' '.$order_shipping_postcode.' '.$order_shipping_country;
										
										 
										?>
											<tr>
												<td><b>Billing details</b><br><?php echo $billing_name; ?><br><?php echo $billing_address; ?><br><br><b>E-mail</b><br><?php echo $order_billing_email;  ?></td>
												<td style='padding-left: 275px;'><b>Shipping details</b><br><?php echo $shipping_name; ?><br><?php echo $shipping_address; ?><br><br><b>Shipping Method</b><br><?php echo $c_order->get_shipping_method();  ?></td>
											</tr>
											<tr>
											<td style='border-top: 0px solid #efefef;'><b>Phone</b><br><?php echo $order_billing_phone; ?></td>
											</tr>
										</table><br>
										<table>
										<tr>
										<td><b>Product</b></td>
										<td><b>Qty.</b></td>
										<td><b>Tax</b></td>
										<td><b>Total</b></td>
										</tr>
										<?php
										$t_product =0;
										$t_amount =0;
										foreach( $c_order->get_items() as $item_id => $item ){
										$product_id    = $item['product_id']; // Get the product ID
										
										$variation_id  = $item['variation_id']; // Get the variation ID

										$product_name  = $item['name']; 
										$item_qty      = $item['quantity']; 

										// Get line item totals (non discounted)
										$line_total     = $item['subtotal']; 
										$line_total_tax = $item['subtotal_tax']; 

										// Get line item totals (discounted)
										$line_total2     = $item['total']; 
										$line_total_tax2 = $item['total_tax']; 
										
										?>
											<tr>
											<td><?php echo $product_name ;?></td>
											<td><?php echo $item_qty ;?></td>
											<td><?php echo $line_total_tax;?></td>
											<td>$<?php echo $line_total;?></td>
											</tr>
										<?php 
										$t_product = $t_product + $item_qty;
										$t_amount = $t_amount + $line_total;
									}
										
										?>
										<tr>
										<th>Total</th>
										<td><b><?php echo $t_product; ?></b></td>
										<td></td>
										<td><b>$<?php echo $t_amount; ?></b></td>
										</tr>
										</table>
									</div>
									
									
								</div>

							</div>
						</td>
						<td>
							<?php  
							$order_status = $c_order->get_status();
							if(  $order_status == 'completed'){
							
							?>
							<button style="border-radius:8px; background-color: #17c671;"><a style="color: white;" href="https://glowbrandscandles.com/wp-admin/admin-ajax.php?action=generate_wpo_wcpdf&document_type=invoice&order_ids=<?php echo $c_order->get_id();  ?>&access_key=wc_order_d3GG6Op7BmE3i" target="_blank">Invoice</a></button>
							<?php  
							}
							?>
							</td>
						</tr>
						<?php 	
					}						
						}
						?>
					</tbody>
				</table>
				
				</div>
        </div>



    </div>
            </div>
</section>




<script src="<?php echo plugin_dir_url(__FILE__); ?>assets/js/xJquery.js"></script>



































<div class="content style-scope events-controller fundraiser_dashboard_design">
	
	<?php $current_user = wp_get_current_user(); ?>

                        <?php 
                              global $wpdb;
                              session_start();

                              $u_id = $current_user->ID;
							  $IDG=$_GET['frid'];
							if(!empty($IDG))
							{
								$u_id=$IDG;
							}
							else
							{
								
								
							}
                              //$id        = $wpdb->get_row("SELECT meta_value FROM wp_usermeta WHERE user_id='$u_id'  ");

                               $_SESSION['fundraiser_id'] = $u_id ;  //  get_post_meta 
							   if($u_id==205821)
							   {
								   $f="Gary";		
								   $l="Margolies";								   
								   $org="World Cup";
								   $sd="2020-10-16";
								   $ed="2020-11-30";
								   $before="Yes";
								   $hear="Referral/a friend";
								   $referral="Gary Margolies";
								   $type="Fresh/Clean";
								   $Participants= "400";
								   $goal_amount="5000";
								   $go_toward="Cheer season";  
								   $selling_goal="10";
								   $payable="World Cup";
								   $cartContent = get_user_meta($u_id, 'cart-restore', true);
								   $fund_desc=get_user_meta($u_id,'fund_profile_description',true);
									$fund_image=get_user_meta($u_id,'fund_profile_image',true);
							   }
							   else
							   {
								   $f=get_user_meta( $u_id , 'first_name' , true );		
								   $l=get_user_meta( $u_id , 'last_name' , true );								   
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
								   $fund_desc=get_user_meta($u_id,'fund_profile_description',true);
									$fund_image=get_user_meta($u_id,'fund_profile_image',true);
									 $feedbackStatus = get_user_meta($u_id,'feedback_status',true);
							   }

							/*Progress Calculation*/
							
							$current_user = wp_get_current_user(); 
                            $frID2  = $current_user->ID; 
							$IDG=$_GET['frid'];
							if(!empty($IDG))
							{
								$ID = $IDG;
								$frID2=$IDG;
							}
							else
							{
								$ID = $frID2;
								
							}
							   $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->ID, 'wc_seller_field', true );

                         /*   if ($ID3 == $IDD) { */
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									/*Active / inactive*/
									
									 $order_date = $order->order_date;
										$extract_date=$order_date;
										$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
										$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
									if ($in_s ==1){
									
									
								  if ($frID3 != $order_customer_id) {
									   $order_type = get_post_meta($order->ID, 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
                              $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
									  }
									  else
									  {
										 $total_sale_so_far+=$order->get_subtotal();
									  }
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
								  }
								} 
								 else if ($frID3 == $order_customer_id) {
									  $order_type = get_post_meta($order->ID, 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
								 }
								} 
								else{}
								} }
                            /*     } */
						}
							/*End */
						
						/* Ship to School Total Sales and Rebate */
						/* Calculate Catlog Quantity */
						 $arr_order_id2=array();
						 $total_cat_quantity =0; 						
						 $total_sale_so_far2=0;
						 $rebate_so_far2=0;		
						 $item_qty_fund_online=0;
						 
						 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                        $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
								$ID3 = get_post_meta($order->ID, 'wc_seller_field', true );
							   	$scat = get_post_meta($order->ID, 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($order->has_status('on-hold') || $order->has_status('completed') || $order->has_status('processing')) {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->ID, 'wc_order_type', true );
								  if($order_type=="Catlog") 
									 {
										 foreach ($order->get_items() as $item_key => $item_values): 
												$item_data = $item_values->get_data();
												$total_cat_quantity += $item_data['quantity']; 			//ship to school								
										  endforeach;	
								} } }
						}
							   
							   
							   
                            $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
								$ID3 = get_post_meta($order->ID, 'wc_seller_field', true );
							   	$scat = get_post_meta($order->ID, 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($scat==1) {
									/* Active/Inactive */	
								    $order_date = $order->order_date;
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
								  $order_type = get_post_meta($order->ID, 'wc_order_type', true );
                            if ($order_type=='ship-to-school') {
								
									$arr_order_id2[]=$order->ID;
								
                               /*$total_sale_so_far+=$order->get_subtotal();*/
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far2+=$newamt;
										}
										else
										{
										 $total_sale_so_far2+=$order->get_subtotal();
										}
										$total_sale_so_far2 = number_format($total_sale_so_far2, 2, '.', '');

                                        foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->ID, 'wc_order_type', true );
															if($order_type_2=="ship-to-school") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															
															else{}
                                             endforeach; 
                         } } } }
						}
						$percentage  = 40;
                        $percentFifty= 50;
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
						if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFifty / 100) * $total_sale_so_far2;
							$rebate_so_far2 = number_format($Discount, 2, '.', '');
							
						}
                        else
						{
                            $Discount    = ($percentage / 100) * $total_sale_so_far2;
                            $rebate_so_far2 = number_format($Discount, 2, '.', '');
							
						}
						
						$rebate_so_far3=$rebate_so_far+$rebate_so_far2; 
						$rebate_so_far3=number_format($rebate_so_far3, 2, '.', '');
						 // End Ship to school						
						


						?>
						 <?php $action_status= get_user_meta( $u_id, 'user_action_status' , true );
						 $paid_status= get_user_meta( $u_id , 'user_status' , true );
						/* date_default_timezone_set('America/Chicago');
								echo $current_time = date('Y-m-d H:i:s');	*/					 
						 if($action_status ==2) { ?>
						<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">
								<ul style="display:none;" class="woocommerce-error" role="alert">
									<li class="lastItem firstItem">Your GLOW fundraiser has been successfully closed out. Any online sales profits will be sent on the 15th of the month. Thank you for choosing GLOW for your fundraising needs.
We look forward to working with you again!</li>
								</ul>
						</div>
						<?php }  if($action_status ==2 && empty($feedbackStatus) && $feedbackStatus!="Yes") {  ?>
						<div style="display:none;" class="feedback-section">
								<div class="col-md-6 col-sm-6 col-xs-12 left-side-glow">
									<div class="feedback-sec-inner" role="alert">
										<h1 class="lastItem firstItem">We'd like to hear your feedback</h1>
										<p>Please tell us about your experience in this 1 second. Your feedback help us create a better experience for you and for all of our fundrasiers.</p>
										<div id="c_btn"><span>Give Your Feedback</span></div>
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-12 right-side-glow">
									 <span>
											<img src="https://glowscentedcandles.com/wp-content/uploads/2022/05/img56.png"/>
									 </span>
								</div>
						</div>
						
						<?php } $image_url_profile=get_user_meta($u_id,"user_registration_image_url_profile",true); 
						if((empty($fund_desc) || empty($fund_image)) && empty($image_url_profile)) { ?>
<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">
								<ul class="woocommerce-error" role="alert">
									<li class="lastItem firstItem" >Please upload your profile image. <a class="uploadImage"href="https://glowbrandscandles.com/upload-image-and-description/?type=image" > Upload Now</a></li>
								</ul>
						</div>
						<?php } if( $action_status ==2 ) { ?>
						<div id="rebookRow" class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">
								<ul style="display:none;" class="woocommerce-error" role="alert">
									<li class="lastItem firstItem" >Create NEW FUNDRAISING CAMPAIGN NOW! <a id="rebook_ajax" class="rebookingBtn" > Rebooking</a></li>
								</ul>
						</div>
						<?php } ?>
						
		
		
						
         <div style="display: none;" class="header style-scope organizer-fundraisers-detail tp">
            <div class="style-scope organizer-fundraisers-detail x-scope dg-header-1-1" large-screen="">
                <?php $org = get_user_meta($u_id,'user_registration_name_of_school_business' , true); ?>
               <h1 class="style-scope dg-header-1"><?php echo $org; ?></h1>
            </div>
            <div class="style-scope organizer-fundraisers-detail x-scope fundraiser-status-0">
				<!--<div class="container-status style-scope fundraiser-rebooking">
				<a id="rebook_ajax" class="rebooking-btn">Rebooking</a>
				</div>-->
               <div class="container-status style-scope fundraiser-status">
                  <span class="title style-scope fundraiser-status">Fundraiser Campaign is </span>
				  <?php  if($action_status !=2) { ?>
                  <span class="status live style-scope fundraiser-status">Active</span>
				  <?php } else { ?>
				   <span class="status live style-scope fundraiser-status" style="color:red;">Inactive</span>
				  <?php } ?>
				 <a class="walk-through fa fa-file-pdf-o" data-toggle="tooltip" title="Print Fundraiser Instructions here" href="https://glowscentedcandles.com/wp-content/uploads/2020/10/Fundraiser-Dashboard-Glow-Scented-Candles-4-copy.pdf" target=" _blank"></a>
               <!--<div class="hoverbox"><span class="">Print Fundraiser instructions here.</span></div>-->
			   </div>
			   
            </div>
         </div>
		 
		  <div style="display: none;" class="header style-scope organizer-fundraisers-detail seller-dashboard-progressbar-and-image">
            <div class="style-scope organizer-fundraisers-detail x-scope dg-header-1-1 bar-sec" large-screen="">
              <div class="progress progressBar">
				 <?php $width=round($rebate_so_far3 / ($goal_amount / 100),2); ?>
				
				<script>
				 jQuery(document).ready(function()
				 {
					var i = 0;
					var total_width="<?php echo $width; ?>";
					/*var total_width=40;*/
					  if (i == 0) {
						i = 1;
						var elem = document.getElementById("myBar");
						var width = 0;
						var id = setInterval(frame, 10);
						function frame() {
						  if (width >= total_width) {
							clearInterval(id);
							i = 0;
						  } else {
							  if(width<=total_width){
							width++;
							elem.style.width = width + "%";
							  }
						  }
						  }
						}
					  
					
				 });
				 </script>
					<div id="myBar" class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div style="display: none;" class="sc-dqBHgY ccbzhW">
					<div class="sc-hwwEjo eoCznv">
					<?php 
					    $rebate_so_far3=number_format($rebate_so_far3, 2, '.', '');
						$goal_amount=number_format($goal_amount, 2, '.', '');
						
						if( $rebate_so_far3 > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$rebate_new = preg_replace($pattern, $replace, $rebate_so_far3);
						} 
						if( $goal_amount > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$goal_amount_new = preg_replace($pattern, $replace, $goal_amount);
						} 
						
						?>
					   <div width="55" id="total_raised" class="sc-kTUwUJ jZDald">$<?php if( $rebate_so_far3 > 999) { echo $rebate_new." "; } else { echo $rebate_so_far3." "; }?></div>
					   <div id="goal" class="sc-kTUwUJ eYHKCV">$<?php if($goal_amount > 999){ echo $goal_amount_new; } else if($goal_amount < 999){ echo $goal_amount; } else if(empty($goal_amount)){ echo "0.00";} else { } ?></div>
					</div>
					<div id="remianing-left" class="sc-kTUwUJ eYHKCV"><?php echo ' ';?>  
					<?php 
					if(!empty($goal_amount)){ 
						$rem = ($goal_amount-$rebate_so_far3); 
						$rem = number_format((float)$rem, 2, '.', ''); 
						if( $rem > 999) {
								$pattern = "/\B(?=(\d{3})+(?!\d))/";
								$replace = ",";
								$rem_new = preg_replace($pattern, $replace, $rem);
								echo "Remaining Left: $".$rem_new;
						}
						else if( $rem < 999) {
							$rem = number_format((float)$rem, 2, '.', ''); 
							echo "Remaining Left: $".$rem; 
						}
						else{}
					}
					else{ echo "Remaining Left: $0.00";} ?>
					</div>
				 </div>
            </div>
            <div class="style-scope organizer-fundraisers-detail x-scope fundraiser-status-0 image_sec prfl_pic">
				<!--<div class="container-status style-scope fundraiser-rebooking">
				<a id="rebook_ajax" class="rebooking-btn">Rebooking</a>
				</div>-->
               <div class="container-status style-scope fundraiser-status ">
				   <?php 
					if(!empty($fund_image))
					{  
						$crop_url_fund_image=profile_image_resize_crop( $src=$fund_image, $w=500, $h=500, $dest = null, $override = true, $createNewIfExists = true );
					}
					 else if(!empty($image_url_profile))
					 {  
						$crop_url_image_url_profile=profile_image_resize_crop( $src=$image_url_profile, $w=500, $h=500, $dest = null, $override = true, $createNewIfExists = true );
					 }
					 else
					 {
					 }
				   ?>
						<img class="profile-seller" src="<?php if(!empty($fund_image)){  echo $crop_url_fund_image; } else if(!empty($image_url_profile)){  echo $crop_url_image_url_profile; } else{ ?>https://glowscentedcandles.com/wp-content/uploads/2021/02/3135715.png<?php } ?>"/>
					<a href="https://glowbrandscandles.com/upload-image-and-description/?type=image" class="btn btn-primary change-photo" > <i class="fa fa-fw fa-camera"></i> <span>Change photo</span> </a>
			   </div>
			   
            </div>
         </div>
		 
		 <div id="toolbox" class="ds_tech_popup">
  <div class="pp_wrpr">
					<div class="style-scope organizer-fundraisers-detail x-scope dg-panel-0 custom_store_summary ds_pp_box extrapopup" large-screen="" style="">
						<span class="pp_closer"></span>
<div class="header style-scope dg-panel">
                        <span slot="header" class="style-scope organizer-fundraisers-detail">Toolbox for Marketing</span>
                       
                        <div class="sub-header style-scope dg-panel"></div>
                     </div>

<div class="owl-carousel testimonial-slider">
  <div class="testimonial-item">
<img src="http://glowscentedcandles.com/wp-content/uploads/2025/11/fall-winter_2025_selling-GLOW_1200x628-01.png">
  </div>
   <div class="testimonial-item">
<img src="http://glowscentedcandles.com/wp-content/uploads/2025/11/fall-winter_2025_selling-GLOW_1200x628-02.png">
  </div>
   <div class="testimonial-item">
<img src="http://glowscentedcandles.com/wp-content/uploads/2025/11/fall-winter_2025_selling-GLOW_1200x628-03.png">
  </div>
   <div class="testimonial-item">
<img src="http://glowscentedcandles.com/wp-content/uploads/2025/11/fall-winter_2025_selling-GLOW_1200x628-04.png">
  </div>
   <div class="testimonial-item">
<img src="http://glowscentedcandles.com/wp-content/uploads/2025/11/fall-winter_2025_selling-GLOW_1200x628-05.png">
  </div>
   <div class="testimonial-item">
<img src="http://glowscentedcandles.com/wp-content/uploads/2025/11/fall-winter_2025_selling-GLOW_1200x628-06.png">
  </div>
 
</div>



</div></div></div>


		 
         <div class="row style-scope organizer-fundraisers-detail">
            <div class="large-6 small-12 left-panel style-scope organizer-fundraisers-detail left-side-section">
			
             
<div id="fundraiserPopup" class="ds_tech_popup">
  <div class="pp_wrpr">
					<div class="style-scope organizer-fundraisers-detail x-scope dg-panel-0 custom_store_summary ds_pp_box" large-screen="" style="">
						<span class="pp_closer"></span>
    <div id="A" class="style-scope organizer-fundraisers-detail x-scope dg-panel-0">
                  <div class="panel-container style-scope dg-panel">
                     <div class="header style-scope dg-panel">
                        <span slot="header" class="style-scope organizer-fundraisers-detail">Fundraiser Details </span>
                        <span style="float: right;color: #4990e2;font-size: 14px;"><a id="edit_info" href="<?php echo get_site_url(); ?>/edit-fundraiser/?frid=<?php echo $current_user->ID; ?>">Edit info</a></span>
                        
                        <div class="sub-header style-scope dg-panel"></div>
                     </div>
                     <div class="content style-scope dg-panel">
                        <div slot="content" class="style-scope organizer-fundraisers-detail">
                           <div class="item style-scope organizer-fundraisers-detail">
                              <span class="label style-scope organizer-fundraisers-detail">Fundraiser Code</span>
                              <span class="value style-scope organizer-fundraisers-detail">
                                 <div class="style-scope organizer-fundraisers-detail x-scope copy-code-0">
                                    <div class="style-scope copy-code x-scope dg-copy-text-0 code_text">
                                       <pre class="style-scope dg-copy-text"><div id="selectableArea" class="style-scope dg-copy-text"><?php  echo $u_id; ?></div></pre>
                                    </div>
                                    <div type="custom" class="style-scope copy-code x-scope dg-button-3" large-screen="">
                                       <div id="CopyID" class="custom filled style-scope dg-button x-scope paper-button-0" role="button" tabindex="0" animated="" aria-disabled="false" elevation="1" style="min-width: 90px;">
                                          <span class="center style-scope dg-button"><span class="style-scope copy-code" >Copy</span></span>
                                       </div>
                                    </div>
                                 </div>
                              </span>
                           </div>
                           <?php $fileUpload_full_link=get_user_meta($u_id,'fundraiser_barcode_link',true); 
                           if(!empty($fileUpload_full_link)) { ?>
                            <div class="item style-scope organizer-fundraisers-detail QRcode qrcode-secfp-sm">
                              <span class="label style-scope organizer-fundraisers-detail QRcode-left">QR code</span>
                              <span class="value style-scope organizer-fundraisers-detail QRcode-right"><img src="<?php echo $fileUpload_full_link; ?>" /><br/><a href="<?php echo $fileUpload_full_link; ?>" download="download_img">Download</a></span>
                           </div>
                         <?php  } ?>
                            <div class="item style-scope organizer-fundraisers-detail">
                              <span class="label style-scope organizer-fundraisers-detail">Participant Letter</span>
                              <span class="value style-scope organizer-fundraisers-detail"><a href="https://glowbrandscandles.com/docs/word.php?fund_id=<?php echo $u_id; ?>" style="color: #4990e2;">Download Letter</a></span>
                            </div>
                            <div class="item style-scope organizer-fundraisers-detail">
                              <span class="label style-scope organizer-fundraisers-detail">Change Password?</span>
                              <span class="value style-scope organizer-fundraisers-detail"><a style="padding: 0px 4px;" href="https://glowbrandscandles.com/my-account/lost-password/"><?php  echo "Reset"; ?></a></span>
                           </div>
                           <div class="item style-scope organizer-fundraisers-detail">
                              <span class="label style-scope organizer-fundraisers-detail">Chairperson</span>
                              <span class="value style-scope organizer-fundraisers-detail"><?php  echo $f." ".$l; ?></span>
                           </div>
                         
                           <?php 
                           $IDG=$_GET['frid'];
                           if(!empty($IDG)){
                                if(in_array( 'administrator', (array) $current_user->roles )){
                           ?>
                            <div class="item style-scope organizer-fundraisers-detail">
                              <span class="label style-scope organizer-fundraisers-detail">Ship to School | By administrator</span>
                              <span class="value style-scope organizer-fundraisers-detail"><a data-id="<?php echo $IDG; ?>" id="merge_order_ajax">Merge Order</a></span>
                           </div>
                           <?php 
                                }
                           }
                           ?>
                           <div class="item style-scope organizer-fundraisers-detail">
                              <span class="label style-scope organizer-fundraisers-detail">Start Date</span>
                              <span class="value style-scope organizer-fundraisers-detail"><?php  
                              $sd = date("m/d/Y", strtotime($sd));
                              echo $sd;?></span>
                           </div>
                           <div class="item  style-scope organizer-fundraisers-detail">
                              <span class="label style-scope organizer-fundraisers-detail">End Date</span>
                              <span class="value style-scope organizer-fundraisers-detail"><?php 
                                $ed = date("m/d/Y", strtotime($ed));
                              echo $ed; ?></span>
                           </div>
                          
                           <div class="item  style-scope organizer-fundraisers-detail">
                                <span class="label style-scope organizer-fundraisers-detail">Share your shopping link with friends <br/>& family via email, text or social media!</</span>
                                <span class="value style-scope organizer-fundraisers-detail share_c_btn"><div type="custom" class="style-scope copy-code x-scope dg-button-3" large-screen="">
                                       <div id="CopyProfileLink" class="custom filled style-scope dg-button x-scope paper-button-0" role="button" tabindex="0" animated="" aria-disabled="false" elevation="1" style="min-width: 90px;">
                                          <span class="center style-scope dg-button">
                                          <span id="selectableAreaProfile" style="display:none;">https://glowbrandscandles.com/profile/<?php echo $u_id; ?></span>
                                          <span id="ShareButton" class="" >Share</span>
                                          </span>
                                       </div>
                                    </div></span>
                           </div>
                        </div>
                     </div>
                     <div class="footer style-scope dg-panel">
                     </div>
                 
               </div>
                 </div>
             </div>
         </div>
  </div>
</div>
			   
			   <!-- <div id="C" class="style-scope organizer-fundraisers-detail x-scope dg-panel-0 x-scope dg-panel-0 order_sec" large-screen="" >
                  <div class="panel-container style-scope dg-panel">
                     <div class="header style-scope dg-panel">
                        <span slot="header" class="style-scope event-payout">Submit Your Brochure Orders --> <!--<span id="info" icon="dg:info" data-toggle="tooltip" title="This section shows Spring/Summer & Fall/Winter Catalog page links. Click Order Now, you will be directed to the catalog page to place your catalog orders. For placing catalog order, select the items for each seller (matching their order form), Add to Cart, Type the Sellers name. Continue for each seller in your group.
My Catalog Report Totals section will show your total sales of Catalog orders placed." class="style-scope dg-info-tooltip x-scope iron-icon-3">
                                          <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" class="style-scope iron-icon" style="pointer-events: none; display: block; width: 100%; height: 100%;">
                                             <g class="style-scope iron-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" class="style-scope iron-icon">
                                                   <path fill-rule="nonzero" d="M7.5.078a7.422 7.422 0 1 0 0 14.844A7.422 7.422 0 0 0 7.5.078zm1.63 6.7c-.072.341-1.104 2.225-1.405 2.862-.17.364-.508.87-.434 1.294.059.338.408.521.712.595.1.024.392.057.392.205a.11.11 0 0 1-.108.109l-2.293.001a.649.649 0 0 1-.653-.556c-.047-.366.2-.695.35-1.008.32-.67.684-1.32 1.002-1.991.115-.243.221-.489.338-.73.117-.24.26-.512.165-.784-.103-.29-.446-.435-.722-.502-.1-.024-.392-.057-.392-.204a.11.11 0 0 1 .108-.11c0-.002 2.293-.002 2.293-.002.34 0 .645.266.663.604a.853.853 0 0 1-.017.217zm-.525-1.502a1.06 1.06 0 1 1 0-2.12 1.06 1.06 0 0 1 0 2.12z" class="style-scope iron-icon"></path>
                                                </svg>
                                             </g>
                                          </svg>
                                       </span>-->
						<!-- </span>
                        <div class="action style-scope dg-panel">
                        </div>
                        <div class="sub-header style-scope dg-panel"></div>
                     </div>
                     <div class="content style-scope dg-panel">
                        <div class="item style-scope organizer-fundraisers-detail">
                           <span class="label style-scope organizer-fundraisers-detail ">Spring Summer Brochure Order</span>
                           <span class="value style-scope organizer-fundraisers-detail"><a class="isDisabled" href="<?php echo get_site_url(); ?>/spring-summer-catalogue-mass-order/" style="color: #4990e2;">Order Now</a></span>
                        </div>
                        <div class="item style-scope organizer-fundraisers-detail ">
                           <span class="label style-scope organizer-fundraisers-detail ">Fall-Winter Brochure Order</span>
                           <span class="value style-scope organizer-fundraisers-detail "><a class="sisDisabled" href="<?php echo get_site_url(); ?>/digital-fall-winter-catalogue/" style="color: #4990e2;">Order Now</a></span>
                        </div>
						<div class="item no-border style-scope organizer-fundraisers-detail">
                           <span class="label style-scope organizer-fundraisers-detail">My Brochure Report Totals</span> -->
						   <!-- Catalog section-->
						<?php 
						    $current_user = wp_get_current_user(); 
                            $frID2 = $current_user->ID; 
							$IDG=$_GET['frid'];
							if(!empty($IDG))
							{
								$ID = $IDG;
								$frID2=$IDG;
							}
							else
							{
								$ID = $frID2;
								
							}
							$total_sale_so_far=0;
							$rebate_so_far=0;
							 $item_qty_fund_online=0;
							/* Start Ship to school qty calculation */
						  $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
								$ID3 = get_post_meta($order->ID, 'wc_seller_field', true );
							   	$scat = get_post_meta($order->ID, 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($scat==1) {
									/* Active/Inactive */	
								    $order_date = $order->order_date;
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
								  $order_type = get_post_meta($order->ID, 'wc_order_type', true );
                            if ($order_type=='ship-to-school') {
								
									 foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->ID, 'wc_order_type', true );
															if($order_type_2=="ship-to-school") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															
															else{}
                                             endforeach; 
                         } } } }
						}
						 
						 /* End Ship to school qty calculation */
							
							
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->ID, 'wc_seller_field', true );

                         /*   if ($ID3 == $IDD) { */
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
							
								  if ($frID3 == $order_customer_id) {
									  $order_type = get_post_meta($order->ID, 'wc_order_type', true );
									  if($order_type=="Catlog") 
									  {
										$order_item_quantity = 0; 
										  foreach ($order->get_items() as $item_key => $item_values): 
                                         $item_id   = $item_values->get_id();
                                         $item_data = $item_values->get_data();
											$order_item_quantity += $item_data['quantity']; 										 
										  endforeach;
										  $qty= $order_item_quantity+$item_qty_fund_online; 
										if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale_so_far+=$subtot-$to_widthdraw; 
										}  
										else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
											$total_sale_so_far+=$subtot-$to_widthdraw; 
										} 
										else
										{ 
						
										}
										

								 }
								} 
								else{}
								}
                            /*     } */
						}
						$total_sale_so_far=number_format($total_sale_so_far, 2, '.', '');
						if( $total_sale_so_far > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_sale_so_far_NEW = preg_replace($pattern, $replace, $total_sale_so_far);
						} 
                        ?>
                           <!-- <span class="value style-scope organizer-fundraisers-detail"> -->
                           	<?php 

                           	// if($total_sale_so_far > 999){ echo $total_sale_so_far_NEW; } else if($total_sale_so_far < 999){ echo $total_sale_so_far; } else { }  
                           	?>

                       <!-- </span> -->
                      <!--   </div>
                     </div>
                     <div class="footer style-scope dg-panel">
                     </div>
                  </div>
               </div> -->
			   <?php if($u_id==205821){?>
			   <div style="display:none;" id="E" class="style-scope organizer-fundraisers-detail x-scope event-payout-0 payout_sec top_padding_for_all">
			   
                  <div class="panel style-scope event-payout x-scope dg-panel-0" large-screen="">
                     <div class="panel-container style-scope dg-panel">
                        <div class="header style-scope dg-panel">
                           <span slot="header" class="style-scope event-payout">Sales to Date <!--<span id="info" icon="dg:info" data-toggle="tooltip" title="The section will show your payout details for your Online/Catalog Sales as well as profits broken down by seller. Be sure to Click ClOSE OUT Fundraiser when you are ready to close out and have your online slaes profits payment sent to you." class="style-scope dg-info-tooltip x-scope iron-icon-3">
                                          <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" class="style-scope iron-icon" style="pointer-events: none; display: block; width: 100%; height: 100%;">
                                             <g class="style-scope iron-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" class="style-scope iron-icon">
                                                   <path fill-rule="nonzero" d="M7.5.078a7.422 7.422 0 1 0 0 14.844A7.422 7.422 0 0 0 7.5.078zm1.63 6.7c-.072.341-1.104 2.225-1.405 2.862-.17.364-.508.87-.434 1.294.059.338.408.521.712.595.1.024.392.057.392.205a.11.11 0 0 1-.108.109l-2.293.001a.649.649 0 0 1-.653-.556c-.047-.366.2-.695.35-1.008.32-.67.684-1.32 1.002-1.991.115-.243.221-.489.338-.73.117-.24.26-.512.165-.784-.103-.29-.446-.435-.722-.502-.1-.024-.392-.057-.392-.204a.11.11 0 0 1 .108-.11c0-.002 2.293-.002 2.293-.002.34 0 .645.266.663.604a.853.853 0 0 1-.017.217zm-.525-1.502a1.06 1.06 0 1 1 0-2.12 1.06 1.06 0 0 1 0 2.12z" class="style-scope iron-icon"></path>
                                                </svg>
                                             </g>
                                          </svg>
                                       </span>-->
									  
							</span>
							 <?php 
							 $action_status = get_user_meta( $u_id, 'user_action_status' , true );
							 $fundraiser_preference = get_user_meta( $u_id , 'user_registration_fundraiser_preference' , true);
							 if( $action_status == 2 ) { ?>
							 	<span id="close_out"  data-id="<?php echo $current_user->ID; ?>" class="right-heading" style="float: revert;float: right;color: red;cursor: none;opacity: 0.4;">Closed Fundraiser</span>
							  <?php }  else { ?>
							<span id="close_out"  data-id="<?php echo $current_user->ID; ?>" class="right-heading" style="float: revert;float: right;color: red;"><?php if($fundraiser_preference=='Virtual Fundraiser - No Brochures.') { echo 'End Virtual Fundraiser'; }  ?> <img id="loading" src="https://glowscentedcandles.com/wp-content/uploads/2020/08/ajax-loader.gif" class="hide_load"></span>
							  <?php } ?>
						  <!--<div class="action style-scope dg-panel">
                              <span slot="action" class="style-scope event-payout">
                              <template is="dom-if" class="style-scope event-payout"></template>
                              </span>
                              </div>-->
                           <div class="sub-header style-scope dg-panel"></div>
                        </div>
                        <div class="content style-scope dg-panel">
                           <div slot="content" class="style-scope event-payout">
						    <?php $current_user = wp_get_current_user(); 
                            $frID2          = $current_user->ID; 
							$IDG=$_GET['frid'];
							if(!empty($IDG))
							{
								$ID = $IDG;
								$frID2=$IDG;
							}
							else
							{
								$ID = $frID2;
								
							}
							   $total_sale_so_far=0;
							  $rebate_so_far=0;
							    $item_qty=0;
							  $item_qty_catalog=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->ID, 'wc_seller_field', true );

                         /*   if ($ID3 == $IDD) { */
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
									/*Active / inactive*/
									
									 $order_date = $order->order_date;
										$extract_date=$order_date;
										$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
										$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
									if ($in_s ==1){
									/* total Item  Sold */
										foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data(); 
												
												$order_type = get_post_meta($order->ID, 'wc_order_type', true );
											if($order_type=="Online") 
											{
												$item_qty  +=  $item_data['quantity']; 
											}
											else if($order_type=="Catlog") 
											{
												$item_qty_catalog  +=  $item_data['quantity']; 
											}
											else{}
																								
                                        endforeach; 
										
									
									/* total Item  Sold */	
										
										
									$rebook_date="2020-10-15 06:00:41";
									if(!empty($rebook_date) && $extract_date > $rebook_date ){
									
								  if ($frID3 != $order_customer_id) {
									   $order_type = get_post_meta($order->ID, 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
                              $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
									  }
									  else
									  {
										 $total_sale_so_far+=$order->get_subtotal();
									  }
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
								  }
								} 
								 else if ($frID3 == $order_customer_id) {
									  $order_type = get_post_meta($order->ID, 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
								 }
								} 
								else{}
									} } }
                            /*     } */
						}
						/* Ship to School Total Sales and Rebate */
						/* Calculate Catlog Quantity */
						 $arr_order_id2=array();
						 $total_cat_quantity =0; 						
						 $total_sale_so_far2=0;
						 $rebate_so_far2=0;		
						 $item_qty_fund_online=0;
						 
						 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                        $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
								$ID3 = get_post_meta($order->ID, 'wc_seller_field', true );
							   	$scat = get_post_meta($order->ID, 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($order->has_status('on-hold') || $order->has_status('completed') || $order->has_status('processing')) {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->ID, 'wc_order_type', true );
								  if($order_type=="Catlog") 
									 {
										 foreach ($order->get_items() as $item_key => $item_values): 
												$item_data = $item_values->get_data();
												$total_cat_quantity += $item_data['quantity']; 			//ship to school								
										  endforeach;	
								} } }
						}
							   
							   
							   
                            $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
								$ID3 = get_post_meta($order->ID, 'wc_seller_field', true );
							   	$scat = get_post_meta($order->ID, 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($scat==1) {
									/* Active/Inactive */	
								    $order_date = $order->order_date;
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
								  $order_type = get_post_meta($order->ID, 'wc_order_type', true );
                            if ($order_type=='ship-to-school') {
								
									$arr_order_id2[]=$order->ID;
								
                               /*$total_sale_so_far+=$order->get_subtotal();*/
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far2+=$newamt;
										}
										else
										{
										 $total_sale_so_far2+=$order->get_subtotal();
										}
										$total_sale_so_far2 = number_format($total_sale_so_far2, 2, '.', '');

                                        foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->ID, 'wc_order_type', true );
															if($order_type_2=="ship-to-school") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															
															else{}
                                             endforeach; 
                         } } } }
						}
						$percentage  = 40;
                        $percentFifty= 50;
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
						if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFifty / 100) * $total_sale_so_far2;
							$rebate_so_far2 = number_format($Discount, 2, '.', '');
							
						}
                        else
						{
                            $Discount    = ($percentage / 100) * $total_sale_so_far2;
                            $rebate_so_far2 = number_format($Discount, 2, '.', '');
							
						}
						$rebate_so_far3=$rebate_so_far+$rebate_so_far2; 
						$total_sale_so_far3=$total_sale_so_far+$total_sale_so_far2; 
						
						$total_sale_so_far3=number_format($total_sale_so_far3, 2, '.', '');
						$rebate_so_far3=number_format($rebate_so_far3, 2, '.', '');
						
						if( $total_sale_so_far3 > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_sale_so_far_NEW1 = preg_replace($pattern, $replace, $total_sale_so_far3);
						} 
						if( $rebate_so_far3 > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_sale_so_far_NEW2 = preg_replace($pattern, $replace, $rebate_so_far3);
						} 
						 // End Ship to school
                        ?>
                              <div class="item style-scope event-payout">
                                 <span class="label style-scope event-payout">Total Online & Brochure Sales / Profit</span>
                                 <span class="value style-scope event-payout">
                                 $<?php if($total_sale_so_far3 > 999){ echo $total_sale_so_far_NEW1; } else if($total_sale_so_far3 < 999){ echo $total_sale_so_far3; } else { echo "0.00";} ?> |
                                 <span class="highlight-green style-scope event-payout">$<?php if($rebate_so_far3 > 999){ echo $total_sale_so_far_NEW2; } else if($rebate_so_far3 < 999){ echo $rebate_so_far3; } else { echo "0.00"; } ?></span>
                                 </span>
                              </div>
							  <div class="item style-scope event-payout">
                                 <span class="label style-scope event-payout">Total Online Items Sold</span>
                                 <span class="value style-scope event-payout">
                               <?php echo $item_qty; ?> 
                                 </span>
                              </div>
							   <div class="item style-scope event-payout">
                                 <span class="label style-scope event-payout">Total Brochure Items Sold</span>
                                 <span class="value style-scope event-payout">
                             	<?php echo $item_qty_catalog; ?>
                                 </span>
                              </div>
							  <div class="item style-scope event-payout shipto-school-grid">
                                 <span class="label style-scope event-payout">Total Ship-to-Group Items Sold</span>
                                 <span id="shipto-school-items" class="value style-scope event-payout">
                             	</span>
                              </div>
                              <!--<div class="item style-scope event-payout">
                                 <span class="label style-scope event-payout">Check Made Payable To</span>
                                 <span class="value style-scope event-payout"><?php echo $payable; ?></span>
                              </div>
                              <div class="item style-scope event-payout">
                                 <span class="label style-scope event-payout">Payout Method</span>
                                 <span class="value style-scope event-payout">
                                    <div standout="" class="style-scope event-payout x-scope dg-link-0">
                                       <a class="style-scope dg-link" href="javascript:void(0);" target="_self" style="color: #4990e2;text-decoration:none;">Check</a>
                                    </div>
                                 </span>
                              </div>
							   <div class="item style-scope event-payout">
                                 <span class="label style-scope event-payout">Payout Status</span>
                                 <span class="value style-scope event-payout">
                                    <div standout="" class="style-scope event-payout x-scope dg-link-0">
									<?php  if($action_status ==2) { ?>
                                     <span class="value style-scope event-payout">Paid</span>
									<?php } else { ?>
									<span class="value style-scope event-payout">Unpaid</span>
									<?php } ?>
                                    </div>
                                 </span>
                              </div>
							 
                              <div class="item no-border style-scope event-payout">
                                 <span class="label style-scope event-payout">
                                    Expected Payout Date
                                    <span text="Payout Date is the day your funds will be sent. Funds typically arrive within two weeks from the date your Event ends, and are distributed via direct deposit or check." class="style-scope event-payout x-scope dg-info-tooltip-0">
                                     
                                    </span>
                                 </span>
                                 <span class="value style-scope event-payout">15th of the month</span>
                              </div>-->
                           </div>
                        </div>
                        <div class="footer style-scope dg-panel">
                        </div>
                     </div>
                  </div>
               </div>
			   <?php } else{ ?>
               <div style="display:none;" id="E" class="style-scope organizer-fundraisers-detail x-scope event-payout-0 payout_sec top_padding_for_all">
			   
                  <div class="panel style-scope event-payout x-scope dg-panel-0" large-screen="">
                     <div class="panel-container style-scope dg-panel">
                        <div class="header style-scope dg-panel">
                           <span slot="header" class="style-scope event-payout">Sales to Date <!--<span id="info" icon="dg:info" data-toggle="tooltip" title="The section will show your payout details for your Online/Catalog Sales as well as profits broken down by seller. Be sure to Click ClOSE OUT Fundraiser when you are ready to close out and have your online slaes profits payment sent to you." class="style-scope dg-info-tooltip x-scope iron-icon-3">
                                          <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" class="style-scope iron-icon" style="pointer-events: none; display: block; width: 100%; height: 100%;">
                                             <g class="style-scope iron-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" class="style-scope iron-icon">
                                                   <path fill-rule="nonzero" d="M7.5.078a7.422 7.422 0 1 0 0 14.844A7.422 7.422 0 0 0 7.5.078zm1.63 6.7c-.072.341-1.104 2.225-1.405 2.862-.17.364-.508.87-.434 1.294.059.338.408.521.712.595.1.024.392.057.392.205a.11.11 0 0 1-.108.109l-2.293.001a.649.649 0 0 1-.653-.556c-.047-.366.2-.695.35-1.008.32-.67.684-1.32 1.002-1.991.115-.243.221-.489.338-.73.117-.24.26-.512.165-.784-.103-.29-.446-.435-.722-.502-.1-.024-.392-.057-.392-.204a.11.11 0 0 1 .108-.11c0-.002 2.293-.002 2.293-.002.34 0 .645.266.663.604a.853.853 0 0 1-.017.217zm-.525-1.502a1.06 1.06 0 1 1 0-2.12 1.06 1.06 0 0 1 0 2.12z" class="style-scope iron-icon"></path>
                                                </svg>
                                             </g>
                                          </svg>
                                       </span>-->
							</span>
							 <?php 
							 $action_status = get_user_meta( $u_id, 'user_action_status' , true );
							  $fundraiser_preference = get_user_meta( $u_id , 'user_registration_fundraiser_preference' , true);
						/*	 if( $action_status == 2 ) { ?>
							 	<span id="close_out"  data-id="<?php echo $current_user->ID; ?>" class="right-heading" style="float: revert;float: right;color: red;cursor: none;opacity: 0.4;">Closed Fundraiser</span>
							  <?php }  else { ?>
							<span id="close_out"  data-id="<?php echo $current_user->ID; ?>" class="right-heading" style="float: revert;float: right;color: red;"><?php if($fundraiser_preference=='Virtual Fundraiser - No Brochures.') { echo 'End Virtual Fundraiser'; } ?> <img id="loading" src="https://glowscentedcandles.com/wp-content/uploads/2020/08/ajax-loader.gif" class="hide_load"></span>
							  <?php } */ ?>
							
                           <!--<div class="action style-scope dg-panel">
                              <span slot="action" class="style-scope event-payout">
                              <template is="dom-if" class="style-scope event-payout"></template>
                              </span>
                              </div>-->
                           <div class="sub-header style-scope dg-panel"></div>
                        </div>
                        <div class="content style-scope dg-panel">
                           <div slot="content" class="style-scope event-payout">
						    <?php $current_user = wp_get_current_user(); 
                            $frID2          = $current_user->ID; 
							$IDG=$_GET['frid'];
							if(!empty($IDG))
							{
								$ID = $IDG;
								$frID2=$IDG;
							}
							else
							{
								$ID = $frID2;
								
							}
							   $total_sale_so_far=0;
							  $rebate_so_far=0;
							   $item_qty=0;
							  $item_qty_catalog=0;
							/*  $item_qty_after=0;
							   $item_qty_catalog_after=0;*/
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->ID, 'wc_seller_field', true );

                         /*   if ($ID3 == $IDD) { */
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
									/*Active / inactive*/
									
									 $order_date = $order->order_date;
										$extract_date=$order_date;
										$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
										$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
										foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data(); 
												
												$order_type = get_post_meta($order->ID, 'wc_order_type', true );
											if($order_type=="Online") 
											{
												$item_qty  +=  $item_data['quantity']; 
											}
											else if($order_type=="Catlog") 
											{
												$item_qty_catalog  +=  $item_data['quantity']; 
											}
											else{}
																								
                                        endforeach; 
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
									if ($in_s ==1){
									/* total Item  Sold */
										foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data(); 
												
												$order_type = get_post_meta($order->ID, 'wc_order_type', true );
											if($order_type=="Online") 
											{
												$item_qty  +=  $item_data['quantity']; 
											}
											else if($order_type=="Catlog") 
											{
												$item_qty_catalog  +=  $item_data['quantity']; 
											}
											else{}
																								
                                        endforeach; 
										
									
									/* total Item  Sold */
									
								  if ($frID3 != $order_customer_id) {
									   $order_type = get_post_meta($order->ID, 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
                              $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
									  }
									  else
									  {
										 $total_sale_so_far+=$order->get_subtotal();
									  }
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
								  }
								} 
								 else if ($frID3 == $order_customer_id) {
									  $order_type = get_post_meta($order->ID, 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
								 }
								} 
								else{}
								} }
                            /*     } */
						}
						/* Ship to School Total Sales and Rebate */
						/* Calculate Catlog Quantity */
						 $arr_order_id2=array();
						 $total_cat_quantity =0; 						
						 $total_sale_so_far2=0;
						 $rebate_so_far2=0;		
						 $item_qty_fund_online=0;
						 
						 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                        $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
								$ID3 = get_post_meta($order->ID, 'wc_seller_field', true );
							   	$scat = get_post_meta($order->ID, 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($order->has_status('on-hold') || $order->has_status('completed') || $order->has_status('processing')) {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->ID, 'wc_order_type', true );
								  if($order_type=="Catlog") 
									 {
										 foreach ($order->get_items() as $item_key => $item_values): 
												$item_data = $item_values->get_data();
												$total_cat_quantity += $item_data['quantity']; 			//ship to school								
										  endforeach;	
								} } }
						}
							   
							   
							   
                            $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
								$ID3 = get_post_meta($order->ID, 'wc_seller_field', true );
							   	$scat = get_post_meta($order->ID, 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($scat==1) {
									/* Active/Inactive */	
								    $order_date = $order->order_date;
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
								  $order_type = get_post_meta($order->ID, 'wc_order_type', true );
                            if ($order_type=='ship-to-school') {
								
									$arr_order_id2[]=$order->ID;
								
                               /*$total_sale_so_far+=$order->get_subtotal();*/
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far2+=$newamt;
										}
										else
										{
										 $total_sale_so_far2+=$order->get_subtotal();
										}
										$total_sale_so_far2 = number_format($total_sale_so_far2, 2, '.', '');

                                        foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->ID, 'wc_order_type', true );
															if($order_type_2=="ship-to-school") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															
															else{}
                                             endforeach; 
                         } } } }
						}
						$percentage  = 40;
                        $percentFifty= 50;
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
						if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFifty / 100) * $total_sale_so_far2;
							$rebate_so_far2 = number_format($Discount, 2, '.', '');
							
						}
                        else
						{
                            $Discount    = ($percentage / 100) * $total_sale_so_far2;
                            $rebate_so_far2 = number_format($Discount, 2, '.', '');
							
						}
						$rebate_so_far3=$rebate_so_far+$rebate_so_far2; 
						$total_sale_so_far3=$total_sale_so_far+$total_sale_so_far2; 
						
						$total_sale_so_far3=number_format($total_sale_so_far3, 2, '.', '');
						$rebate_so_far3=number_format($rebate_so_far3, 2, '.', '');
						
						if( $total_sale_so_far3 > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_sale_so_far_NEW1 = preg_replace($pattern, $replace, $total_sale_so_far3);
						} 
						if( $rebate_so_far3 > 999) {
							$pattern = "/\B(?=(\d{3})+(?!\d))/";
							$replace = ",";
							$total_sale_so_far_NEW2 = preg_replace($pattern, $replace, $rebate_so_far3);
						}
						 // End Ship to school
						 $desc=get_user_meta($u_id,'fund_profile_description',true);
						 
                        ?>
                              <!--<div class="item style-scope event-payout">
                                 <span class="label style-scope event-payout">Total Online & Brochure Sales / Profit</span>
                                 <span class="value style-scope event-payout">
                                 $<?php echo $total_sale_so_far3=number_format($total_sale_so_far3, 2, '.', ''); ?> |
                                 <span class="highlight-green style-scope event-payout">$<?php echo $rebate_so_far3=number_format($rebate_so_far3, 2, '.', ''); ?></span>
                                 </span>
                              </div>-->
							  <div class="item style-scope event-payout">
                                 <span class="label style-scope event-payout">Total Online & Brochure Sales / Profit</span>
                                 <span class="value style-scope event-payout">
                                 $<?php if($total_sale_so_far3 > 999){ echo $total_sale_so_far_NEW1; } else if($total_sale_so_far3 < 999){ echo $total_sale_so_far3; } else { echo "0.00"; } ?> |
                                 <span class="highlight-green style-scope event-payout">$<?php if($rebate_so_far3 > 999){ echo $total_sale_so_far_NEW2; } else if($rebate_so_far3 < 999){ echo $rebate_so_far3; } else { echo "0.00"; } ?></span>
                                 </span>
                              </div>
							  <div class="item style-scope event-payout">
                                 <span class="label style-scope event-payout">Total Online Items Sold</span>
                                 <span class="value style-scope event-payout">
                               <?php echo $item_qty; ?> 
                                 </span>
                              </div>
							   <div class="item style-scope event-payout">
                                 <span class="label style-scope event-payout">Total Brochure Items Sold</span>
                                 <span class="value style-scope event-payout">
                             	<?php echo $item_qty_catalog; ?>
                                 </span>
                              </div>
							   <div class="item style-scope event-payout shipto-school-grid">
                                 <span class="label style-scope event-payout">Total Ship-to-Group Items Sold</span>
                                 <span id="shipto-school-items" class="value style-scope event-payout">
                             	</span>
                              </div>
                              <!--<div class="item style-scope event-payout">
                                 <span class="label style-scope event-payout">Check Made Payable To</span>
                                 <span class="value style-scope event-payout"><?php echo $payable; ?></span>
                              </div>
                              <div class="item style-scope event-payout">
                                 <span class="label style-scope event-payout">Payout Method</span>
                                 <span class="value style-scope event-payout">
                                    <div standout="" class="style-scope event-payout x-scope dg-link-0">
                                       <a class="style-scope dg-link" href="javascript:void(0);" target="_self" style="color: #4990e2;text-decoration:none;">Check</a>
                                    </div>
                                 </span>
                              </div>
							   <div class="item style-scope event-payout">
                                 <span class="label style-scope event-payout">Payout Status</span>
                                 <span class="value style-scope event-payout">
                                    <div standout="" class="style-scope event-payout x-scope dg-link-0">
									<?php  if($action_status ==2) { ?>
                                     <span class="value style-scope event-payout">Paid</span>
									<?php } else { ?>
									<span class="value style-scope event-payout">Unpaid</span>
									<?php } ?>
                                    </div>
                                 </span>
                              </div>
							 
                              <div class="item no-border style-scope event-payout">
                                 <span class="label style-scope event-payout">
                                    Expected Payout Date
                                    <span text="Payout Date is the day your funds will be sent. Funds typically arrive within two weeks from the date your Event ends, and are distributed via direct deposit or check." class="style-scope event-payout x-scope dg-info-tooltip-0">
                                     
                                    </span>
                                 </span>
                                 <span class="value style-scope event-payout">15th of the month</span>
                              </div> -->
                           </div>
                        </div>
                        <div class="footer style-scope dg-panel">
                        </div>
                     </div>
                  </div>
               </div>
			   
			   <div style="display: none;" id="close_grid" class="style-scope organizer-fundraisers-detail x-scope event-payout-0 payout_sec top_padding_for_all">
					<div class="panel style-scope event-payout x-scope dg-panel-0" large-screen="">
						 <div class="panel-container style-scope dg-panel">
							<div class="header style-scope dg-panel">
							   <span slot="header" class="style-scope event-payout" data-label="<?php echo $fundraiser_preference; ?>">Close Virtual Fundraiser
								</span>
								<?php if( $action_status == 2 ) { ?>
								<span id="close_out1" data-id="1" class="right-heading" style="float: revert;float: right;color: red;cursor: none;opacity: 0.4;border: 1px solid red;padding: 3px 19px;border-radius: 30px;pointer-events: none;">Close</span>
								<?php } else {
								?>
								<span id="close_out1" data-id="1" class="right-heading" style="float: revert;float: right;color: red;border: 1px solid red;padding: 3px 19px;border-radius: 30px;">Close</span>	
								<?php
								}?>							
							  <div class="sub-header style-scope dg-panel"></div>
							</div>
							<div class="content style-scope dg-panel">
							   <div slot="content" class="style-scope event-payout">
								  <div class="item style-scope event-payout">
									 <span class="close_txt" style="line-height: 1.4;">Click on "Close" only for virtual only fundrasier. Do not click on "Close" if you are submitting Brochure Orders.</span>
								   </div>
								</div>
							</div>
							<div class="footer style-scope dg-panel">
							</div>
						 </div>
					</div>
               </div>
			   <?php } ?>
            </div>
			
			<div id="custom_store" class="ds_tech_popup">
				<div class="pp_wrpr">
					<div class="style-scope organizer-fundraisers-detail x-scope dg-panel-0 custom_store_summary ds_pp_box" large-screen="" style="">
						<span class="pp_closer"></span>
						<div class="panel-container style-scope dg-panel">
							<div class="header style-scope dg-panel">
								<span slot="header" class="style-scope organizer-fundraisers-detail">Customize Online Fundraising Store </span>
								<div class="action style-scope dg-panel">
									<span slot="action" class="style-scope organizer-fundraisers-detail">
										<template is="dom-if" class="style-scope organizer-fundraisers-detail"></template>
									</span>
								</div>
								<div class="sub-header style-scope dg-panel"></div>
							</div>
							<div class="content style-scope dg-panel glow-report-section">
								<div slot="content" class="style-scope organizer-fundraisers-detail">
									<div class="item style-scope organizer-fundraisers-detail custom_first_grid">
										<span class="label style-scope organizer-fundraisers-detail"><a class="store_pic"><img class="profile-seller2" style="width:60px;height:60px;" src="<?php if(!empty($fund_image)){  echo $crop_url_fund_image; } else if(!empty($image_url_profile)){  echo $crop_url_image_url_profile; } else{ ?>https://glowscentedcandles.com/wp-content/uploads/2021/02/3135715.png<?php } ?>"/></a></span>
										<span class="value style-scope organizer-fundraisers-detail"><a href="https://glowbrandscandles.com/upload-image-and-description/?type=image" style="color: #4990e2;">Add/Change Logo/Image</a></span>
									</div>
									<div class="item style-scope organizer-fundraisers-detail">
										<span class="label style-scope organizer-fundraisers-detail">Online Store Message</span>
										<span class="value style-scope organizer-fundraisers-detail"><a href="https://glowbrandscandles.com/upload-image-and-description/?type=description" target="_blank">Add/Change Online Store Message</a></span>
									</div>
									<div class="item no-border style-scope organizer-fundraisers-detail">
										<span class="label style-scope organizer-fundraisers-detail ">View Your Online Store</span>
										<span class="value style-scope organizer-fundraisers-detail view_store"><a href="https://glowbrandscandles.com/profile/<?php echo $u_id; ?>" class="cstm_btn_styl">View</a></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				
				</div>
			</div>
			
			
            <div class="large-6 small-12 right-panel style-scope organizer-fundraisers-detail right-side-section">
				
			   
               <div id="B" class="style-scope organizer-fundraisers-detail x-scope dg-panel-0 x-scope dg-panel-0 shop_order_sec" large-screen="" >
                  <div class="panel-container style-scope dg-panel">
                     <div style="display:none;" class="header style-scope dg-panel">
                        <span slot="header" class="style-scope event-payout">Share Online Store Shopping Links / Seller Registration <!--<span id="info" icon="dg:info" data-toggle="tooltip" title="Seller Register Link ( Share this link with your group, your sellers to track their personal sales. Here they will sign up with their email to be notified of their orders). Click Copy Link Button to click and share. " class="style-scope dg-info-tooltip x-scope iron-icon-3">
                                          <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" class="style-scope iron-icon" style="pointer-events: none; display: block; width: 100%; height: 100%;">
                                             <g class="style-scope iron-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" class="style-scope iron-icon">
                                                   <path fill-rule="nonzero" d="M7.5.078a7.422 7.422 0 1 0 0 14.844A7.422 7.422 0 0 0 7.5.078zm1.63 6.7c-.072.341-1.104 2.225-1.405 2.862-.17.364-.508.87-.434 1.294.059.338.408.521.712.595.1.024.392.057.392.205a.11.11 0 0 1-.108.109l-2.293.001a.649.649 0 0 1-.653-.556c-.047-.366.2-.695.35-1.008.32-.67.684-1.32 1.002-1.991.115-.243.221-.489.338-.73.117-.24.26-.512.165-.784-.103-.29-.446-.435-.722-.502-.1-.024-.392-.057-.392-.204a.11.11 0 0 1 .108-.11c0-.002 2.293-.002 2.293-.002.34 0 .645.266.663.604a.853.853 0 0 1-.017.217zm-.525-1.502a1.06 1.06 0 1 1 0-2.12 1.06 1.06 0 0 1 0 2.12z" class="style-scope iron-icon"></path>
                                                </svg>
                                             </g>
                                          </svg>
                                       </span>-->
						</span>
                        <div class="action style-scope dg-panel">
                        </div>
                        <div class="sub-header style-scope dg-panel"></div>
                     </div>
                     <div class="content style-scope dg-panel">
                        <!--<div class="item style-scope organizer-fundraisers-detail">
                           <span class="label style-scope organizer-fundraisers-detail">Simple Shopping Link<br/><small style="opacity: 0.8;">Share this link to your group for click and shop. <br/>customers will enter SELLERS name when shopping.</small></span>
                           <span class="value style-scope organizer-fundraisers-detail">
						 
								<div type="custom" class="style-scope copy-code x-scope dg-button-3" large-screen="">
                                       <div id="CopyRefLink" class="custom filled style-scope dg-button x-scope paper-button-0" role="button" tabindex="0" animated="" aria-disabled="false" elevation="1" style="min-width: 90px;">
                                          <span class="center style-scope dg-button">
										  <span id="refLink" style="display:none;"><?php echo get_site_url(); ?>/?frid=<?php echo $current_user->ID; ?></span>
                                          <span  class="style-scope copy-code">Copy Link</span>
                                          </span>
                                       </div>
                                </div>
						   
						   </span>
                        </div>-->
                        <div id="seller_registration_link" class="ds_tech_popup">
					  	<div class="pp_wrpr">
					<div class="style-scope organizer-fundraisers-detail x-scope dg-panel-0 custom_store_summary ds_pp_box" large-screen="" style="">
						<span class="pp_closer"></span>
						<div class="item no-border style-scope organizer-fundraisers-detail last-space right-seller-link">
                           <span class="label style-scope organizer-fundraisers-detail">Invite your participants to register as a seller <br/><small style="opacity: 0.8;">Individual sellers and the chairperson can access a dashboard to see <br>the items they have sold up to date and their profit. To register,<br> they enter their name, email, individual sales goals, and  <br>optional photo/logo. They can add a personalized message for their  <br>friends and family to read.
After submitting their registration, <br>sellers receive an email containing their unique <br>shopping link and a link to access their seller’s dashboard. <br>Customers simply click on the link shared <br>with them to shop. Sales are automatically credited to each seller.</small></span>
                           <span class="value style-scope organizer-fundraisers-detail qrcode-secSr-sm">
						   <!--<a href="" style="color: #4990e2;">Copy Link</a>-->
						   <div type="custom" class="style-scope copy-code x-scope dg-button-3" large-screen="">
                                       <div id="SellerCopyLink" class="custom filled style-scope dg-button x-scope paper-button-0" role="button" tabindex="0" animated="" aria-disabled="false" elevation="1" style="min-width: 90px;">
                                          <span class="center style-scope dg-button">
										  <span id="sellerLink" style="display:none;"><?php echo get_site_url(); ?>/seller-register/?frid=<?php echo $current_user->ID; ?></span>
                                          <span  class="style-scope copy-code">Copy Link</span>
                                          </span>
                                       </div>
						 </div>
						<?php $fileUpload_full_link2=get_user_meta($u_id,'fundraiser_seller_register_barcode_link',true); 
						   if(!empty($fileUpload_full_link2)) {  ?>
						   <span class="QRcode-sr"><img src="<?php echo $fileUpload_full_link2; ?>" /><br/><a class="txt_cnt" href="<?php echo $fileUpload_full_link2; ?>" download="download_image">Download</a></span>
                             <?php  } ?>
						   </span>
                        </div>
</div>
</div>
                       
                        </div>
                        
						<div id="seller_shopping_link" class="ds_tech_popup">
					  <div class="pp_wrpr">
					<div class="style-scope organizer-fundraisers-detail x-scope dg-panel-0 custom_store_summary ds_pp_box" large-screen="" style="">
						<span class="pp_closer"></span>
                        <div class="item no-border style-scope organizer-fundraisers-detail last-space right-seller-link2">
                           <span class="label style-scope organizer-fundraisers-detail">Share one shopping link with all your sellers <br/><small style="opacity: 0.8;">Customers are invited to shop at glowscentedcandles.com where they <br> enter your group’s Fundraiser ID and the seller’s name. <br>Participants do not have an individual account to view sales. <br>The chairperson can view the online sales and reports on their dashboard. </small></span>
                           <span class="value style-scope organizer-fundraisers-detail sec-smallsrn">
						   <!--<a href="" style="color: #4990e2;">Copy Link</a>-->
						   <div type="custom" class="style-scope copy-code x-scope dg-button-3" large-screen="">
                                       <div id="SellerCopyLink2" class="custom filled style-scope dg-button x-scope paper-button-0" role="button" tabindex="0" animated="" aria-disabled="false" elevation="1" style="min-width: 90px;">
                                          <span class="center style-scope dg-button">
										  <span id="sellerLink2" style="display:none;">https://glowbrandscandles.com/profile/<?php echo $u_id; ?></span>
                                          <span  class="style-scope copy-code">Copy Link</span>
                                          </span>
                                       </div>
                            </div>
						   </span>
                        </div>
						</div>
					</div>
						</div>
                     </div>
                     <div class="footer style-scope dg-panel">
                     </div>
                  </div>
               </div>
               <div id="registeredSellersPopup" class="ds_tech_popup">
  <div class="pp_wrpr">
					<div class="style-scope organizer-fundraisers-detail x-scope dg-panel-0 custom_store_summary ds_pp_box" large-screen="" style="">
						<span class="pp_closer"></span>
               <div id="D" class="style-scope organizer-fundraisers-detail x-scope event-payout-0 participent_sec top_padding_for_all">
                  <div class="panel style-scope event-payout x-scope dg-panel-0" large-screen="">
                     <div class="panel-container style-scope dg-panel">
                        <div class="header style-scope dg-panel">
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
                             
                                $all_meta_for_users = get_users(array(
                                    'meta_key'     => 'user_registration_seller_fundraiser',
                                    'meta_value'   => $frID,
                                ));
								$countSeller=0;
                                    foreach ( $all_meta_for_users as $all_meta_for_user ) {  ?>
									
									<?php $countSeller ++; } ?>
                           <span slot="header" class="style-scope event-payout">Registered Sellers (<?php echo $countSeller; ?>)
							</span>
                           <div class="sub-header style-scope dg-panel"></div>
                        </div>
                        <div class="content style-scope dg-panel" <?php if($countSeller<4) { ?>style="height:200px;" <?php } if($countSeller>4) { ?>id="style-1" style="height:200px; overflow-y:scroll;" <?php } ?> >
                           <div slot="content" class="style-scope event-payout">
                              <div id="heading_payout" class="item style-scope event-payout">
                                 <span class="label style-scope event-payout">Participant</span>
                                 <span class="value style-scope event-payout">
                                 Sales | Profit | Quantity
                                 </span>
                              </div>
							  <div class="load_part"><img id="loading_p" src="https://glowscentedcandles.com/wp-content/uploads/2020/08/ajax-loader.gif" class="show_load_p"></div>
                           </div>
                        </div>
                        <div class="footer style-scope dg-panel">
                        </div>
                     </div>
                  </div>
               </div>
     </div>
 </div>
       </div>
			   
			   	<?php 
					if($u_id!=205821)
				    {
						 $args = array(
                                 'status'   => array('wc-waiting-call','completed','processing'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							
                        foreach ( $orders as $order ) 
						{
                            $frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
							$ID3 = get_post_meta($order->ID, 'wc_seller_field', true );
                            if ($ID3 == "custom") 
							{ 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								/*	if ( $order->has_status('completed') || $order->has_status('processing')) 
									{ */
										/* Active/Inactive */	
								    $order_date = $order->order_date;
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
										 if ($frID3 != $order_customer_id) 
										 {
												$slrID     = get_post_meta($order->ID, 'wc_seller_field', true );
												if($slrID=="custom")
												{
													$s_name=get_post_meta($order->ID, 'wc_seller_field_custom', true);
													$s_name_lower= strtolower($s_name);
													if(!in_array($s_name_lower,$seller_name))
													{
														$seller_name[]=strtolower($s_name);
													}
												}
											 
										 }
									}
									/*} */
							}
						}
					//	print_r($seller_name);
						$countSeller_mauallay=count($seller_name);
						if($countSeller_mauallay!=0){
						?>
			   <div style="display:none;" id="F" class="style-scope organizer-fundraisers-detail x-scope event-payout-0 participent_sec top_padding_for_all">
                  <div class="panel style-scope event-payout x-scope dg-panel-0" large-screen="">
                     <div class="panel-container style-scope dg-panel">
                        <div class="header style-scope dg-panel">
					
                           <span slot="header" class="style-scope event-payout">Manually Added Sellers (<?php echo $countSeller_mauallay; ?>) <span id="info" icon="dg:info" data-toggle="tooltip" title="This section shows manually added sellers by customer. The customers entered the sellers name in the checkout process. You can check Total Sales and Profit of manually added seller." class="style-scope dg-info-tooltip x-scope iron-icon-3">
                                          <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" class="style-scope iron-icon" style="pointer-events: none; display: block; width: 100%; height: 100%;">
                                             <g class="style-scope iron-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" class="style-scope iron-icon">
                                                   <path fill-rule="nonzero" d="M7.5.078a7.422 7.422 0 1 0 0 14.844A7.422 7.422 0 0 0 7.5.078zm1.63 6.7c-.072.341-1.104 2.225-1.405 2.862-.17.364-.508.87-.434 1.294.059.338.408.521.712.595.1.024.392.057.392.205a.11.11 0 0 1-.108.109l-2.293.001a.649.649 0 0 1-.653-.556c-.047-.366.2-.695.35-1.008.32-.67.684-1.32 1.002-1.991.115-.243.221-.489.338-.73.117-.24.26-.512.165-.784-.103-.29-.446-.435-.722-.502-.1-.024-.392-.057-.392-.204a.11.11 0 0 1 .108-.11c0-.002 2.293-.002 2.293-.002.34 0 .645.266.663.604a.853.853 0 0 1-.017.217zm-.525-1.502a1.06 1.06 0 1 1 0-2.12 1.06 1.06 0 0 1 0 2.12z" class="style-scope iron-icon"></path>
                                                </svg>
                                             </g>
                                          </svg>
                                       </span>
									   <br/><small style="opacity: 0.8;">Orders are placed by entering the Fundraiser ID and Seller’s</small>
							</span>
                         
                           <div class="sub-header style-scope dg-panel"></div>
                        </div>
                        <div class="content style-scope dg-panel" <?php if($countSeller_mauallay<4) { ?>style="height:200px;" <?php } if($countSeller_mauallay>4) { ?>id="style-1" style="height:200px; overflow-y:scroll;" <?php } ?> >
                           <div slot="content" class="style-scope event-payout">
                              <div id="heading_payout_manually" class="item style-scope event-payout">
                                 <span class="label style-scope event-payout">Participant</span>
                                 <span class="value style-scope event-payout">
                                 Sales | Profit | Quantity
                                 </span>
                              </div>
							  <div class="load_part"><img id="loading_p_m" src="https://glowscentedcandles.com/wp-content/uploads/2020/08/ajax-loader.gif" class="show_load_p_m"></div>
							
                           </div>
                        </div>
                        <div class="footer style-scope dg-panel">
                        </div>
                     </div>
                  </div>
               </div>
						<?php } }?>
               <div style="display: none;"  id="G" class="style-scope organizer-fundraisers-detail x-scope dg-panel-0 report-sec" large-screen="" style="">
                  <div class="panel-container style-scope dg-panel">
                     <div class="header style-scope dg-panel">
                        <span slot="header" class="style-scope organizer-fundraisers-detail">Close Virtual Fundraiser 
						</span>
                        <span style="float: right;color: #4990e2;font-size: 14px;"></span>
                        <div class="action style-scope dg-panel">
                           <span slot="action" class="style-scope organizer-fundraisers-detail">
                              <template is="dom-if" class="style-scope organizer-fundraisers-detail"></template>
                           </span>
                        </div>
                        <div class="sub-header style-scope dg-panel"></div>
                     </div>
                     <div class="content style-scope dg-panel glow-report-section">
                        <div slot="content" class="style-scope organizer-fundraisers-detail">
                           <div class="item style-scope organizer-fundraisers-detail">
                              <span class="label style-scope organizer-fundraisers-detail">Fundraiser Report</span>
                              <span class="value style-scope organizer-fundraisers-detail"><a href="https://glowbrandscandles.com/fundraiser-dashboard/fundraiser-reports/" style="color: #4990e2;">View Report</a></span>
                           </div>
						   <?php  /*if($u_id!=205821)
							   { ?>
                           <div class="item style-scope organizer-fundraisers-detail">
                              <span class="label style-scope organizer-fundraisers-detail">Seller Report</span>
                              <span class="value style-scope organizer-fundraisers-detail"><a href="https://glowbrandscandles.com/seller-reports/" style="color: #4990e2;">View Report</a></span>
                           </div>
							   <?php } */ ?>
						   <div class="item no-border style-scope organizer-fundraisers-detail final-report">
                              <span class="label style-scope organizer-fundraisers-detail">Final Sales Report</span>
                              <span class="value style-scope organizer-fundraisers-detail"><a href="https://glowbrandscandles.com/print/?fund_id=<?php echo $u_id; ?>" style="color: #4990e2;">View/Print Report</a></span>
                           </div>
                        </div>
                     </div>
                     <div class="footer style-scope dg-panel">
                     </div>
                  </div>
               </div>

						<?php if(!empty($cartContent)){ ?>
			   <div class="style-scope organizer-fundraisers-detail x-scope dg-panel-0 report-sec" large-screen="" style="">
                  <div class="panel-container style-scope dg-panel">
                     <div class="header style-scope dg-panel">
                        <span slot="header" class="style-scope organizer-fundraisers-detail">Saved Order <span id="info" icon="dg:info" data-toggle="tooltip" title="In This Section is showing your last saved order ( Catalog Order ). Click ( Move To Cart ) Button, to move your saved order into cart and procced checkout." class="style-scope dg-info-tooltip x-scope iron-icon-3">
                                          <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" class="style-scope iron-icon" style="pointer-events: none; display: block; width: 100%; height: 100%;">
                                             <g class="style-scope iron-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" class="style-scope iron-icon">
                                                   <path fill-rule="nonzero" d="M7.5.078a7.422 7.422 0 1 0 0 14.844A7.422 7.422 0 0 0 7.5.078zm1.63 6.7c-.072.341-1.104 2.225-1.405 2.862-.17.364-.508.87-.434 1.294.059.338.408.521.712.595.1.024.392.057.392.205a.11.11 0 0 1-.108.109l-2.293.001a.649.649 0 0 1-.653-.556c-.047-.366.2-.695.35-1.008.32-.67.684-1.32 1.002-1.991.115-.243.221-.489.338-.73.117-.24.26-.512.165-.784-.103-.29-.446-.435-.722-.502-.1-.024-.392-.057-.392-.204a.11.11 0 0 1 .108-.11c0-.002 2.293-.002 2.293-.002.34 0 .645.266.663.604a.853.853 0 0 1-.017.217zm-.525-1.502a1.06 1.06 0 1 1 0-2.12 1.06 1.06 0 0 1 0 2.12z" class="style-scope iron-icon"></path>
                                                </svg>
                                             </g>
                                          </svg>
                                       </span>
						</span>
                        <span style="float: right;color: #4990e2;font-size: 14px;"></span>
                        <div class="action style-scope dg-panel">
                           <span slot="action" class="style-scope organizer-fundraisers-detail">
                              <template is="dom-if" class="style-scope organizer-fundraisers-detail"></template>
                           </span>
                        </div>
                        <div class="sub-header style-scope dg-panel"></div>
                     </div>
                     <div class="content style-scope dg-panel">
                        <div slot="content" class="style-scope organizer-fundraisers-detail">
                           <div class="item style-scope organizer-fundraisers-detail">
                              <span class="label style-scope organizer-fundraisers-detail">Move Saved Order To Cart</span>
                              <span class="value style-scope organizer-fundraisers-detail">
							   <div type="custom" class="style-scope copy-code x-scope dg-button-3" large-screen="">
                                       <div id="savedOrder" class="custom filled style-scope dg-button x-scope paper-button-0" role="button" tabindex="0" animated="" aria-disabled="false" elevation="1" style="min-width: 120px;">
                                          <span class="center style-scope dg-button">
											<span  class="style-scope copy-code">Move To Cart <img id="loading_r" src="https://glowscentedcandles.com/wp-content/uploads/2020/08/ajax-loader.gif" class="hide_load_restore"></span>
                                          </span>
                                       </div>
								</div>
							  </span>
                           </div>
                           
                        </div>
                     </div>
                     <div class="footer style-scope dg-panel">
                     </div>
                  </div>
               </div>
			   <?php } ?>
            </div>
		
			<div style="display: none;" class="large-12 small-12 left-panel style-scope organizer-fundraisers-detail left-side-section check_order_report top_padding_for_all">
				<div class="fix_order_Table_Head"> 
				<table class="order_roports_header" border=1>
				<?php 
				
					
						 $count_args = array(
                            'type'  => 'shop_order',
                            'meta_key' => 'fundraiser_field',
							'meta_value' => $u_id,
							'limit' => -1
						   );
						$c_orders = wc_get_orders( $count_args); 
						
						
				?>		
				<?php  
				// code for get tracking no.
					
					
				?>
					
					<thead>
					<tr>
					<th colspan=11><center><h3 class="no_width">Check your order status and tracking information</h3></center></th>
					</tr>
					<tr>
						<th><center>Order ID</center></th>
						<!-- <th><center>Seller ID</center></th> -->
						<th><center>Seller Name</center></th>
						<th><center>Customer Name</center></th>
						<th><center>Fund ID</center></th>
						<th><center>Order Date</center></th>
						<th><center>Order Status</center></th>
						<!-- <th><center>Order Status</center></th> -->
						<th><center>Tracking No.</center></th>
						<th><center>Report Missing/Damaged Items</center></th>
						<th><center>Order Detail</center></th>
						<th><center>Invoice</center></th>
						
					</tr>
					</thead>
				
				
					<?php 
					
					foreach( $c_orders as $c_order ){
						$s_name="";
						$order_id = $c_order->get_id();
						$order_status2="";
						$order_status1="";
						$order_status1 = $c_order->get_status(); 
						$order_status2 = str_replace(' ', '', $order_status1);
						if($order_status2=="processing" || $order_status2=="completed" || $order_status2=="waiting-call" || $order_status2=="on-hold"){
						
						$results = $wpdb->get_results("
					select comment_content from wp_comments where comment_post_id=$order_id AND  `comment_content` LIKE  '%tracking number%';
					");
					
					$order_full_date =$c_order->get_date_created();
					$timestamp = strtotime($order_full_date);
					$new_short_date_format = date('Y-m-d', $timestamp);
					$order_data = $c_order->get_data();
					$order_billing_first_name = $order_data['billing']['first_name'];
					$order_billing_last_name = $order_data['billing']['last_name'];
					 //echo $new_short_date_format ."<br>";
					 $c_order_id = $c_order->get_id();
						?>
						<tbody>
						<tr>
						<?php
							$s_id = get_post_meta( $c_order_id, 'wc_seller_field',true ); 
						//	$s_first_name = get_user_meta($s_id, 'first_name', $single);
							//$s_last_name = get_user_meta($s_id, 'last_name', $single);  
							//$s_name = $s_first_name.' '.$s_last_name;
							if($s_id=="custom")
							{
								$s_name=get_post_meta($c_order_id, 'wc_seller_field_custom', true);
							}
							else if(is_numeric($s_id))
							{
								$s_first_name = get_user_meta($s_id, 'first_name', true);
								$s_last_name = get_user_meta($s_id, 'last_name', true);  
								$s_name = $s_first_name.' '.$s_last_name;
							}
							else{}
						?>
							<td>#<?php echo $c_order->get_id();  ?></td>
							<!-- <td><?php echo $s_id[0];  ?></td> -->
							<td><?php echo $s_name;  ?></td>
							<td><?php echo $order_billing_first_name . ' '. $order_billing_last_name;  ?></td>
							<td><?php  echo $u_id; ?></td>
							<td><?php echo $new_short_date_format;  ?></td>
							<!--<td><?php //echo $c_order->get_id();  ?></td> -->
							<td>
							<?php 
								if($c_order->get_status() =='waiting-call'){
									echo 'Ship To School';  
								}
								else{
									echo $c_order->get_status();
								}
							?>
							</td>
							<td><?php foreach( $results as $result ){
						$data_track = $result->comment_content;
						$tack_number = explode(" ",$data_track);
						$name = "number"; //name to be searched
						$foundIndex = array_search($name, $tack_number);
						$remove_data =  $tack_number[$foundIndex+1];
						$final_value = substr($remove_data, 0, -1);
						?>
						<a href="https://www.ups.com/track?loc=en_US&tracknum=<?php echo $final_value; ?>&requester=WT/trackdetails\" target="_blank"><?php echo $final_value; ?></a>
						<?php 
						}  ?></td>
						<td>
						<?php  
							$order_status = $c_order->get_status();
						
						if(  $order_status == 'completed'){
						
						?>
						<a href="<?php echo get_site_url().'/my-account/view-my-order/'.$c_order->get_id(); ?>" target="_blank">Submit</a>
						<?php  
						}
						?>
						</td>
						<td>
							<button class='myBtn'value=<?php echo $c_order->get_id();?> style="border-radius:8px; background-color: #17c671;">check</button>
							<div id="myModal" class="modal">
								<!-- Modal content -->
								<div class="modal-content">
									<span class="close">×</span>
									<div class="order_details">
										<h3 style="text-align: center;margin-top: 20px;"><u>ORDER DETAILS</u></h3>
										<h4>
										Order No : = <?php echo $c_order->get_id(); ?></h4>
										<b>Seller name : -  <?php echo $s_name;  ?>
										</b><table>
										<?php 
										$order_data = $c_order->get_data();
										
										$order_billing_first_name = $order_data['billing']['first_name'];
										$order_billing_last_name = $order_data['billing']['last_name'];
										$billing_name = $order_billing_first_name.' '.$order_billing_last_name;
										$order_billing_address_1 = $order_data['billing']['address_1'];
										$order_billing_address_2 = $order_data['billing']['address_2'];
										$order_billing_city = $order_data['billing']['city'];
										$order_billing_state = $order_data['billing']['state'];
										$order_billing_postcode = $order_data['billing']['postcode'];
										$order_billing_country = $order_data['billing']['country'];
										
										$billing_address = $order_billing_address_1.'<br>'. $order_billing_address_2.' '. $order_billing_city.' '.$order_billing_state.' '.$order_billing_postcode.' '.$order_billing_country; 
										
										$order_billing_email = $order_data['billing']['email'];
										$order_billing_phone = $order_data['billing']['phone'];
										
										$order_shipping_first_name = $order_data['shipping']['first_name'];
										$order_shipping_last_name  = $order_data['shipping']['last_name'];
										$shipping_name = $order_shipping_first_name.' '.$order_shipping_last_name ;
										$order_shipping_address_1 = $order_data['shipping']['address_1'];
										$order_shipping_address_2 = $order_data['shipping']['address_2'];
										$order_shipping_city = $order_data['shipping']['city'];
										$order_shipping_state = $order_data['shipping']['state'];
										$order_shipping_postcode = $order_data['shipping']['postcode'];
										$order_shipping_country = $order_data['shipping']['country'];
										
										$shipping_address = $order_shipping_address_1.'<br>'. $order_shipping_address_2.' '. $order_shipping_city.' '.$order_shipping_state.' '.$order_shipping_postcode.' '.$order_shipping_country;
										
										 
										?>
											<tr>
												<td><b>Billing details</b><br><?php echo $billing_name; ?><br><?php echo $billing_address; ?><br><br><b>E-mail</b><br><?php echo $order_billing_email;  ?></td>
												<td style='padding-left: 275px;'><b>Shipping details</b><br><?php echo $shipping_name; ?><br><?php echo $shipping_address; ?><br><br><b>Shipping Method</b><br><?php echo $c_order->get_shipping_method();  ?></td>
											</tr>
											<tr>
											<td style='border-top: 0px solid #efefef;'><b>Phone</b><br><?php echo $order_billing_phone; ?></td>
											</tr>
										</table><br>
										<table>
										<tr>
										<td><b>Product</b></td>
										<td><b>Qty.</b></td>
										<td><b>Tax</b></td>
										<td><b>Total</b></td>
										</tr>
										<?php
										$t_product =0;
										$t_amount =0;
										foreach( $c_order->get_items() as $item_id => $item ){
										$product_id    = $item['product_id']; // Get the product ID
										
										$variation_id  = $item['variation_id']; // Get the variation ID

										$product_name  = $item['name']; 
										$item_qty      = $item['quantity']; 

										// Get line item totals (non discounted)
										$line_total     = $item['subtotal']; 
										$line_total_tax = $item['subtotal_tax']; 

										// Get line item totals (discounted)
										$line_total2     = $item['total']; 
										$line_total_tax2 = $item['total_tax']; 
										
										?>
											<tr>
											<td><?php echo $product_name ;?></td>
											<td><?php echo $item_qty ;?></td>
											<td><?php echo $line_total_tax;?></td>
											<td>$<?php echo $line_total;?></td>
											</tr>
										<?php 
										$t_product = $t_product + $item_qty;
										$t_amount = $t_amount + $line_total;
									}
										
										?>
										<tr>
										<th>Total</th>
										<td><b><?php echo $t_product; ?></b></td>
										<td></td>
										<td><b>$<?php echo $t_amount; ?></b></td>
										</tr>
										</table>
									</div>
									
									
								</div>

							</div>
						</td>
						<td>
							<?php  
							$order_status = $c_order->get_status();
							if(  $order_status == 'completed'){
							
							?>
							<button style="border-radius:8px; background-color: #17c671;"><a style="color: white;" href="https://glowbrandscandles.com/wp-admin/admin-ajax.php?action=generate_wpo_wcpdf&document_type=invoice&order_ids=<?php echo $c_order->get_id();  ?>&access_key=wc_order_d3GG6Op7BmE3i" target="_blank">Invoice</a></button>
							<?php  
							}
							?>
							</td>
						</tr>
						<?php 	
					}						
						}
						?>
					</tbody>
				</table>
				
				</div>
			</div>
		</div>
      </div>



<style>
	.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 100; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: Hidden; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
  }
  
  /* Modal Content */
  .modal-content {
    background-color: #fefefe;
    margin: 10% auto; /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 50%; /* Could be more or less, depending on screen size */
	height:70%;
	overflow-y:scroll;
  }
  
  /* The Close Button */
  .close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
  }
  
  .close:hover,
  .close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
  }
</style>

<script defer>
	jQuery(document).ready(function() {
		jQuery('.myBtn').on('click', function() {
			// Target the .modal sibling and change its CSS
			$(this).siblings(".modal").css({
				'display': 'block',      // for example, to show the modal
				 /* 'background-color': '#fff', // set other CSS properties as needed
				'border-radius': '5px',
				'padding': '10px'  */
				// add other CSS properties you want to change
			});
		});
		jQuery('.close').on('click', function() {
			//alert('I am here');
			// Target the .modal sibling and change its CSS
			$(this).parent().parent().css({
				'display': 'none',      // for example, to show the modal
				/* 'background-color': '#fff', // set other CSS properties as needed
				'border-radius': '5px',
				'padding': '10px' */
				// add other CSS properties you want to change
			});
		});
		
	});

  /* // Get the modal
  var modal = document.getElementById("myModal");

  // Get the button elements that open the modal
  var btns = document.querySelectorAll(".myBtn");

  // Get the <span> element that closes the modal
  var span = document.getElementsByClassName("close")[0];

  // When the user clicks any button, open the modal 
  btns.forEach(function(btn) {
    btn.onclick = function() {
      alert('test');
	  var text = jQuery(this).val();
	   alert(text);
	  modal.style.display = "block";
	  
    }
  });

  // When the user clicks on <span> (x), close the modal
  span.onclick = function() {
    modal.style.display = "none";
  }

  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  } */
</script>
	  
						
	   <div class="manual-mass-close_out-popup">
        <div class="manual-mass-order-popup-wrapper">
		<div class="close_btn" style="
    position: absolute;
    right: 10px;
    top: 5px;
    color: #cda85c;
    font-weight: 400;
    cursor: pointer;
"><i class="fa fa-close" style="font-size:24px;"></i></div>
		<span>Do you want to close fundraiser campaign?</span>
            <a id="close_out_ajax" class="txt-link" data-id="<?php echo $current_user->ID; ?>">Yes</a>
            <a href="#" id="m-txt-link" class="txt-link">No</a>
        </div>
    </div>
	 <div class="manual-mass-rebook-popup">
        <div class="manual-mass-order-popup-wrapper">
		<span>Ready to book your next GLOW Fundraising Campaign?</span>
		<small style="
    font-size: 13px;
    width: 100%;
    display: inline-block;
    margin-bottom: 10px;"><b style="color:red;">Note:</b> Once you will click <b>YES</b> button, you will no longer have access to this fundraiser dashboard or detailed reports. *<b>Please note</b>—If you need any of these reports, please print them and come back to book here. Please complete the Fundraiser Register form and submit. You will receive your NEW FUNDRAISER CODE via email and Fundraiser Dashboard login credentials.</small>
            <a id="rebook_request" class="txt-link" data-action="yes" data-id="<?php echo $current_user->ID; ?>">Yes</a>
            <a href="#" id="m-rebook-link" class="txt-link">No</a>
        </div>
    </div>
	 <div class="manual-mass-feedback-popup">
        <div class="manual-mass-order-popup-wrapper">
			<div class="fclose_btn" style="position: absolute;right: 10px;top: 5px;color: #cda85c;font-weight: 400;cursor: pointer;"><i class="fa fa-close" style="font-size:24px;"></i></div>
			 <div class="feedbackInner">
			
			<span>Your Feedback is important to us</span>
			 <b class="af_am">All fields are mandatory!!</b>
			<p class="desc_src">thank you!</p>
			<p class="desc_src_1">We read every feedback we get and we take it very seriously</p>
			<p class="desc_src_2">How do you feel about your visit on our site today?</p>
			<p class="simlesss">
				<input label="Smiling face with smiling eyes" type="radio" id="smiling_face_with_smiling_eyes" name="smile" value="smiling face with smiling eyes" checked><span data-id="1" class="active">😊</span>
			    <input label="Smiling face with heart-shaped eyes" type="radio" id="smiling_face_with_heart-shaped_eyes" name="smile" value="smiling face with heart-shaped eyes"><span data-id="2">😍</span>
				<input label="neutral face" type="radio" id="neutral_face" name="smile" value="neutral face"><span data-id="3">😐</span>
				<input label="disappointed but relieved face" type="radio" id="disappointed_but_relieved_face" name="smile" value="disappointed but relieved face"><span data-id="4">😥</span>
			</p>
			<p class="desc_src_3">How kind of feedback would you like to give?</p>
			<select data-rules="" data-id="" name="kindOfFeedback" id="kindOfFeedback" class="select ur-frontend-field  user-registration-valid" data-label="How kind of feedback would you like to give?" data-allow_clear="true" data-placeholder="" aria-invalid="false">
					<option value="">Choose a subject</option>
					<option value="Bad">Bad</option>
					<option value="Good">Good</option>
					<option value="Very Good">Very Good</option>
					<option value="Excellent">Excellent</option>
			</select>
			<p class="desc_src_4">Would you like to share something with us?</p>
			<textarea id="share" name="share" rows="4" cols="50"></textarea>
			<p class="desc_src_5">Would you like us to contact you by email?</p>
			<input type="email" id="cemail" name="cemail" placeholder="Add your email here">
			<p class="desc_src_5">How likely are you to recommend us to your friends and colleagues?</p>
			<div class="radio">
				<input label="0" type="radio" id="0" name="friends" value="0" checked>
				<input label="1" type="radio" id="1" name="friends" value="1">
				<input label="2" type="radio" id="2" name="friends" value="2">
				<input label="3" type="radio" id="3" name="friends" value="3">
				<input label="4" type="radio" id="4" name="friends" value="4">
				<input label="5" type="radio" id="5" name="friends" value="5">
				<input label="6" type="radio" id="6" name="friends" value="6">
				<input label="7" type="radio" id="7" name="friends" value="7">
				<input label="8" type="radio" id="8" name="friends" value="8">
				<input label="9" type="radio" id="9" name="friends" value="9">
				<input label="10" type="radio" id="10" name="friends" value="10">
			</div>
			</div>
			<img id="loading_ajax" src="https://glowscentedcandles.com/wp-content/uploads/2022/05/ajax-loader.gif" class="">
            <a id="feedback_ajax" class="txt-link" data-id="<?php echo $current_user->ID; ?>">Submit</a>
            <!--<a href="#" id="m-txt-link" class="txt-link">No</a> -->
        </div>
    </div>
	<div class="manual-mass-feedback-popup2">
        <div class="manual-mass-order-popup-wrapper2">
		<div class="fclose_btn" style="position: absolute;right: 10px;top: 5px;color: #cda85c;font-weight: 400;cursor: pointer;"><i class="fa fa-close" style="font-size:24px;"></i></div>
		  </div>
    </div>
	<div class="manual-mass-popup">
        <div class="manual-mass-order-popup-wrapper">
		<h3>Share Profile Link</h3>
           <!-- <a href="https://glowbrandscandles.com/profile/<?php echo $ID; ?>" class="fr-btn">https://glowbrandscandles.com/profile/<?php echo $ID; ?></a>-->
			<a  title="Click to share this profile on facebook" href="https://www.facebook.com/sharer.php?u=https://glowbrandscandles.com/profile/<?php echo $ID; ?>&t=<?php echo $org; ?> | Support Now | Fundraising" class="fr-btn" target="_blank"><i class="fa fa-facebook-square" aria-hidden="true"></i></a>
			<a title="Click to share this profile on Twitter" href="http://twitter.com/intent/tweet?text=<?php echo $org; ?>&url=https://glowbrandscandles.com/profile/<?php echo $ID; ?>" class="fr-btn"  target="_blank" rel="noopener noreferrer"><i class="fa fa-twitter-square" aria-hidden="true"></i></a>
			<a title="Click to share this profile on Linkedin"  href="https://www.linkedin.com/shareArticle?mini=true&url=https://glowbrandscandles.com/profile/<?php echo $ID; ?>&t=<?php echo $org; ?>" class="fr-btn"  target="_blank" rel="noopener noreferrer"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a>
			<a title="Click to share this profile on Whatsapp"  href="whatsapp://send?text=https://glowbrandscandles.com/profile/<?php echo $ID; ?>" data-action="share/whatsapp/share"class="fr-btn"  target="_blank" rel="noopener noreferrer"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
			<a id="CopyLinkpopup" class="fr-btn style-scope copy-code" ><i class="fa fa-clone" aria-hidden="true"></i></a>
			<span id="fundLink" style="display:none;">https://glowbrandscandles.com/profile/<?php echo $ID; ?></span>
           <!-- <span id="CopyLink" class="style-scope copy-code">Copy Link</span>-->
			 <a href="#" id="m-link" class="txt-link">Cancel</a>
        </div>
    </div>
	<style>
	.feedback-section
	{
	    position: relative;
		width: 100%;
		display: inline-block;
		padding: 35px 25px;
		background: #fafafa;
		margin-bottom: 36px;
	}
	.feedback-section .left-side-glow .feedback-sec-inner 
	{
		margin-top: 14px;
	}
	.feedback-section h1.lastItem.firstItem 
	{
		font-size: 19px;
		font-weight: 800;
	}
	.feedback-sec-inner p 
	{
		font-size: 15px;
		line-height: 1.5;
		padding: 12px 0px 20px;
	}
	.feedback-section div#c_btn 
	{
		padding: 10px 20px;
		background: #4990e2;
		width: 190px;
		text-align: center;
		font-size: 16px;
		display: inline-block;
		color: #fff;
		border: 1px solid #4990e2;
		cursor: pointer;
		    border-radius: 30px;
	}
	.feedback-section div#c_btn:hover 
	{
		opacity: 0.9;
	}
	.col-md-6.col-sm-6.col-xs-12.right-side-glow span 
	{
		width: 100%;
		display: inline-block;
		margin: 0px auto;
		text-align: right;
	}
.col-md-6.col-sm-6.col-xs-12.right-side-glow span img {
    width: 280px;
    display: inline-block;
    margin-right: 65px;
	margin-right:0;
}
.manual-mass-feedback-popup2
{
	display:none;
}
.manual-mass-feedback-popup .radio {
    background: transparent;
    padding: 0;
    position: relative;
    margin: 0;
    min-height: unset;
    display: inline-block;
    margin-bottom: 17px;
}
.manual-mass-feedback-popup .radio input {
    width: auto;
    height: 100%;
    appearance: none;
    outline: none;
    cursor: pointer;
    border-radius: 2px;
    padding: 10px 17px;
    background: #fff;
    color: #cda85c;
    font-size: 14px;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    transition: all 100ms linear;
    margin: 0;
    border-radius: 3px;
    border: 1px solid #ccc;
    margin-right: 3px;
    border-radius: 50%;
    border: 1px solid #cda85c;
    margin-right: 4px;
}

.manual-mass-feedback-popup .radio input:checked {
    background-image: linear-gradient(180deg, #cda85c, #cda85c);
    color: #fff;
    box-shadow: 0 1px 1px #0000002e;
    text-shadow: 0 1px 0px #79485f7a;
    margin: 0;
    border-radius: 50%;
    margin-right: 4px;
}
.manual-mass-feedback-popup .radio input:before {
	content: attr(label);
	display: inline-block;
	text-align: center;
	width: 100%;
}
	.manual-mass-feedback-popup.show {
    z-index: 99999;
}
	p.desc_src_2, p.desc_src_4 {
    margin-top: 10px;
    margin-bottom: 10px;
	}
	p.desc_src_2, p.desc_src_3, p.desc_src_4 {
		font-weight: 800;
		margin-bottom: 7px;
		margin-top: 8px;
	}
	select#kindOfFeedback
	{
		width:100%;
	}
	textarea#share {
    margin: 0;
    width: 100%;
    border: 1px solid #ccc;
}
p.desc_src_4 {
    margin-top: 20px;
}
p.desc_src_5 {
    margin-top: 13px;
    margin-bottom: 7px;
    font-weight: 800;
}
input#cemail {
    width: 100%;
    margin-bottom: 20px;
}
	.manual-mass-feedback-popup a#feedback_ajax 
	{
		float: right;
	}
	.feedbackInner
	{
		text-align:left;
		display:inline;
	}
	.feedbackInner a#feedback_ajax {
	
    float: right;
}
		p.simlesss span {
    display: inline;
    padding-right: 19px;
    border: 0;
}
</style>
	<style>
	#close_out
	{
		cursor: pointer;
	}
	.uploadImage
	{
		padding: 5px 30px;
    background: #f5f5f5;
    margin-left: 10px;
    text-align: right;
    /* float: right; */
    position: absolute;
    right: 10px;
    top: 11px;
	}
	.manual-mass-order-popup, 
	.manual-mass-generation-popup,
	.manual-mass-close_out-popup,
	.manual-mass-rebook-popup,
	.manual-mass-feedback-popup,
	.manual-mass-feedback-popup2
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
	.manual-mass-close_out-popup .manual-mass-order-popup-wrapper,
	.manual-mass-rebook-popup .manual-mass-order-popup-wrapper,
	.manual-mass-feedback-popup .manual-mass-order-popup-wrapper, 
	.manual-mass-feedback-popup2 .manual-mass-order-popup-wrapper2
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
.manual-mass-close_out-popup .manual-mass-order-popup-wrapper,
.manual-mass-feedback-popup .manual-mass-order-popup-wrapper,
.manual-mass-rebook-popup .manual-mass-order-popup-wrapper,
.manual-mass-feedback-popup2 .manual-mass-order-popup-wrapper2
{
    padding: 30px 25px;
    max-width: 600px;
    border: 3px solid #cda85c; 
}
.manual-mass-generation-popup span,
.manual-mass-close_out-popup span,
.manual-mass-rebook-popup span,
.manual-mass-feedback-popup span
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
.manual-mass-close_out-popup #m-txt-link,
.manual-mass-rebook-popup #m-rebook-link,
.manual-mass-feedback-popup #m-txt-link,
.manual-mass-feedback-popup2 #m-txt-link
{
	    background: #cda85c;
    color: #fff;
    padding: 6px 30px;
    display: inline-block;
    transition: all 0.5s ease 0s;
}
.manual-mass-generation-popup #m-txt-link:hover,
.manual-mass-close_out-popup #m-txt-link:hover,
.manual-mass-rebook-popup #m-rebook-link:hover,
.manual-mass-feedback-popup #m-txt-link:hover,
.manual-mass-feedback-popup2 #m-txt-link:hover
{
	   background: #fff;
    color: #cda85c;
	border:1px solid #cda85c;
	    box-sizing: border-box;
}
.manual-mass-close_out-popup span,
.manual-mass-rebook-popup span,
.manual-mass-feedback-popup span
{
	width: 100%;
    margin-bottom: 20px;
}
#close_out_ajax,
#rebook_request,
#feedback_ajax 
{
	background: #cda85c;
    color: #fff;
    padding: 6px 30px;
    display: inline-block;
    transition: all 0.5s ease 0s;
}
.manual-mass-close_out-popup #close_out_ajax:hover,
.manual-mass-feedback-popup #feedback_ajax:hover,
.manual-mass-rebook-popup #rebook_request:hover 
{
	   background: #fff;
    color: #cda85c;
	border:1px solid #cda85c;
	    box-sizing: border-box;
}
.show_load
{
	display:inline-block;
}
.hide_load
{
	display:none;
}
#style-1::-webkit-scrollbar-track
{
	
	border-radius: 10px;
	background-color: #ebebeb;
}

#style-1::-webkit-scrollbar
{
	width: 10px;
	
}

#style-1::-webkit-scrollbar-thumb
{
	border-radius: 10px;
	 background: #6d6d6d; 
}
span.style-scope.copy-code {
    width: 100%;
}
.show_load_restore,.show_load_p,.show_load_p_m{
	display:inline-block;
}
.hide_load_restore,.hide_load_p, .hide_load_p_m{
	display:none;
}
.load_part
{
	width: 100%;
    margin: 0 auto;
    text-align: center;
    padding: 20px;
}
.walk-through
{
	color: #dd2a2a;
    font-size: 25px;
    padding-left: 12px;
}
.tooltip-inner 
{
    max-width: 300px;
    padding: 11px 10px;
	
}
p.simlesss span 
{
	display: inline;
    padding-right: 19px;
    border: 0;
    font-size: 32px;
    position: relative;
    left: -33px;
    top: -3px;
    cursor: pointer;
}
p.simlesss span.active img {
    background-image: linear-gradient(180deg, #cda85c, #cda85c) !important;
    border: 2px solid #cda85c !important;
    border-radius: 50%;
}
.simlesss input[type="radio"] {
    width: 30px;
    height: 30px;
}
#loading_ajax
{
	
    float: right;
    position: absolute;
    bottom: 7px;
    right: 64px;
}
.feedback_hide_load
{
	display:none;
}
.feedback_show_load
{
	display:inline-block;
}
@media only screen and (max-width: 768px)
{
	.col-md-6.col-sm-6.col-xs-12.right-side-glow 
	{
		margin-top: 33px;
		width:50%;
	}
	.col-md-6.col-sm-6.col-xs-12.right-side-glow span {
    width: 100%;
    display: inline-block;
    margin: 0px auto;
    text-align: right;
}
.col-md-6.col-sm-6.col-xs-12.right-side-glow span img {
    width: 270px;
    display: inline-block;
    margin-right: 0;
    text-align: center;
}
	
}
@media only screen and (max-width: 580px)
{
	.col-md-6.col-sm-6.col-xs-12.right-side-glow 
	{
		width:100%;
	}
	.col-md-6.col-sm-6.col-xs-12.right-side-glow span
	{
		    text-align: left;
	}
	.manual-mass-feedback-popup.show 
	{
		overflow-y: scroll;
	}
	.manual-mass-feedback-popup .manual-mass-order-popup-wrapper
    {
		 top: 54%;
	}
	.manual-mass-feedback-popup .radio input
	{
		margin-bottom: 13px;
	}
	p.simlesss 
	{
		width: 90%;
		display: inline-block;
	}
	p.simlesss span 
	{
		display: inline;
     	padding-right: 0;
  	}
}
@media only screen and (max-width: 480px)
{
	h1.lastItem.firstItem    
	{
		font-size: 16px;
		font-weight: 800;
		line-height: 1.6;
	}
	.feedback-sec-inner p 
	{
		font-size: 15px;
		line-height: 1.433334;
		padding: 10px 0px;
	}
	 
}
@media only screen and (max-width: 375px)
{
	.manual-mass-feedback-popup .manual-mass-order-popup-wrapper 
	{
		top: 63%;
	}
}
@media only screen and (max-width: 360px)
{
	.manual-mass-feedback-popup .manual-mass-order-popup-wrapper 
	{
		top: 85%;
	}
}
	</style>
	<style>
	.manual-mass-popup
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
	.manual-mass-popup .manual-mass-order-popup-wrapper
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
.manual-mass-popup a.fr-btn {
    display: block;
    margin: 20px auto;
    width: 100%;
    max-width: 250px;
}
#manual-mass-popup .show
{
	    background: #fff;
    color: #000;
    margin: 0 auto;
    width: 100%;
    text-align: center;
}
.manual-mass-popup a.fr-btn
{
    display: block;
    margin: 0px auto;
    width: 100%;
    background: none;
    color: #000;
	max-width: 100%;
	display: inline;
    margin: 0px auto;
    width: auto;
	font-size: 25px;
}
#m-link
{
	    display: block;
    margin-top: 10px;
}
.manual-mass-rebook-popup span
{
	display:inline-block;
	font-size: 18px;
    font-weight: 700;
	   margin-bottom: 5px;
}
.af_am
{
	display:none;
	    font-size: 18px;
    color: rgb(255 0 0);
    letter-spacing: 0.4px;
}
	</style>
<!-- Ship To School -->
<!--<script>
jQuery(document).ready(function () 
	{
    async function startDownload(a) {
        let result = await axios({
            url: a,
            method: 'GET',
            responseType: 'blob'
        })
        let x = document.createElement('a');
        x.href = window.URL.createObjectURL(new Blob([result.data]));
        x.setAttribute('download', 'download_image.png');
        document.body.appendChild(x);
        x.click();
    }
	});
</script>-->
<script>

	jQuery(document).ready(function () 
	{



var total_raised = jQuery('#total_raised').text().trim();
jQuery('#append_total_raised').text(total_raised);
var goal = jQuery('#goal').text().trim();
jQuery('#append_goal').text(goal);


				var ajaxurl="https://glowbrandscandles.com/wp-admin/admin-ajax.php";
				 var id="<?php if(!empty($u_id)) { echo $u_id; } ?>";
				 if(id!="")
				 {
					 jQuery.ajax({
							type : "post",
							url : ajaxurl,
							data : {action: 'wdm_discount_shiptoschool',user_id:id},
							success: function(response) {
								console.log(response);
							    jQuery(".shipto-school-grid #shipto-school-items").text(response.data.action);
							}
						});
						
				 }
	});
</script>
	<script>
	jQuery(document).on('click', 'p.simlesss span',function() {
		if(jQuery('p.simlesss span').hasClass('active'))
		{
			jQuery('p.simlesss span').removeClass("active");
			jQuery(this).addClass("active");
			jQuery("input[type=radio][name=smile]").prop("checked" , true);
		}
});
		jQuery(document).on('click', '#rebook_ajax',function()
		{
			jQuery(".manual-mass-rebook-popup").addClass("show");
		});
		jQuery(document).on('click', '#m-rebook-link', function (e) 
		{
			e.preventDefault();
			jQuery(".manual-mass-rebook-popup").removeClass("show");
				
		});
		jQuery(document).on('click', '.close_btn', function (e) 
		{
			e.preventDefault();
			jQuery(".manual-mass-close_out-popup").removeClass("show");
				
		});
		jQuery(document).on('click', '.feedback-section #c_btn', function (e) 
		{
			e.preventDefault();
			jQuery(".manual-mass-feedback-popup").addClass("show");
				
		});
		
		jQuery(document).on('click', '.fclose_btn', function (e) 
		{
			e.preventDefault();
		   jQuery(".manual-mass-feedback-popup").removeClass("show");
				
		});
		jQuery('#rebook_request').click(function()
		{
				  var ajaxurl="https://glowbrandscandles.com/wp-admin/admin-ajax.php";
				 var fid=jQuery(this).attr("data-id");
				 var yes_action=jQuery(this).attr("data-action");
				 /*var fid=<?php echo $u_id; ?>;*/
				 jQuery.ajax({

					type : "post",

					url : ajaxurl,

					data : {action: 'wp_rebooking_ajax', rebook_id:fid, yes_action:yes_action},
	
					success: function(response) {
						console.log(response);
						jQuery(".manual-mass-rebook-popup").removeClass("show");
						 window.location.href = "https://glowbrandscandles.com/fundraiser-register/";
					}
				});
	    });	
		/* Merge Order Ajax by administrator */
		jQuery('#merge_order_ajax').click(function()
		{
				 var ajaxurl="https://glowbrandscandles.com/wp-admin/admin-ajax.php";
				 var fid=jQuery(this).attr("data-id");
			
				 jQuery.ajax({

					type : "post",

					url : ajaxurl,

					data : {action: 'wpm_order_merge_ajax', fid:fid},
	
					success: function(response) {
						console.log(response);
						alert("Successfully merge order!!!");
					}
				});
	      });	


		jQuery(document).on('click', '#ShareButton', function (e) 
		{
				e.preventDefault();
				jQuery(".manual-mass-popup").addClass("show");
				
				
			});
			jQuery(document).on('click', '#m-link', function (e) 
			{
				e.preventDefault();
				jQuery(".manual-mass-popup").removeClass("show");
				
			});
			jQuery(document).on('click', '#CopyLinkpopup', function (e) 
			{
				e.preventDefault();
				var $temp = jQuery("<input>");
				jQuery("body").append($temp);
				$temp.val(jQuery('#fundLink').text()).select();
				document.execCommand("copy");
				$temp.remove();
				jQuery('#CopyLinkpopup').text('Copied');
			});
			
			
	</script>
	<script>
		jQuery(document).ready(function () 
		{
			jQuery("#loading_ajax").addClass("feedback_hide_load");
			jQuery("select#kindOfFeedback").on('change', function() {
					var kindOfFeedback = jQuery(this).val();
					
				});
				jQuery(document).on('click', '.fclose_btn', function (e) 
			{
				e.preventDefault();
				jQuery(".manual-mass-feedback-popup2").css("display","none");
			});
			
			jQuery('#feedback_ajax').click(function()
			{
				/* let data_id = jQuery(this).attr('data-id'); */
			    jQuery("#loading_ajax").removeClass("feedback_hide_load");
				jQuery("#loading_ajax").addClass("feedback_show_load");
				if(jQuery('.manual-mass-feedback-popup p.simlesss span').hasClass('active'))
				{
					var numid=jQuery('.manual-mass-feedback-popup p.simlesss span.active').attr("data-id");
					var friends = numid;
				}
				
				
		    	jQuery(".manual-mass-feedback-popup .af_am").css("display","none");
;				var share = jQuery('#share').val();
				var cemail = jQuery('#cemail').val();
				var number = jQuery('input[name="friends"]:checked').val();
				var kindOfFeedback = jQuery("select#kindOfFeedback").val();
				var ajaxurl="https://glowbrandscandles.com/wp-admin/admin-ajax.php";
				 <?php  
							$current_user = wp_get_current_user();
							$u_id = $current_user->ID;
							$IDG=$_GET['frid'];
							if(!empty($IDG))
							{
								$u_id  = $IDG;
								
							}
							else
							{
								
								
							} 
					?>
				var fid="<?php echo $u_id; ?>";
				if(share!=='' && cemail!=='' && number!=='' && kindOfFeedback!=='' && fid!=='' && friends!=='')
				{
					jQuery.ajax({

					type : "POST",
					dataType: "JSON",
					cache: false,
					url : ajaxurl,
					data : {
						action:'feedback_ajax', 
						friends:friends, 
						kindOfFeedback:kindOfFeedback, 
						share:share, 
						cemail:cemail, 
						smile:number, 
						fid:fid 
						},
					success: function(response) {
						console.log(response);
						jQuery("#loading_ajax").removeClass("feedback_show_load");
						jQuery("#loading_ajax").addClass("feedback_hide_load");
						jQuery(".manual-mass-feedback-popup2").css("display","block");
				        jQuery(".manual-mass-feedback-popup2 .manual-mass-order-popup-wrapper2").html('<p style="padding-top:6px;color:green;font-size: 16px;">'+response.data+'</p>');
						jQuery(".manual-mass-feedback-popup").removeClass("show");
						 setTimeout(function(){   location.reload(); }, 2000);
					},
					error: function (data)
					{
						console.log('Error:', data);
					}
					
				});
			}
			else
			{
				jQuery(".manual-mass-feedback-popup .af_am").css("display","block");
			}
			});
			
			/*jQuery("#loading_p").addClass("show_load_p");*/
			var ajaxurl="https://glowbrandscandles.com/wp-admin/admin-ajax.php";
			var fid=<?php echo $u_id; ?>;
			jQuery.ajax({

			 type : "post",

			 url : ajaxurl,

			 data : {action: 'wdm_participant_list',fund_id:fid},

			 success: function(response) {
							
							console.log(response);
								jQuery("#loading_p").removeClass("show_load_p");
							  jQuery("#loading_p").addClass("hide_load_p");
							if(response!=0){
							
							 jQuery("#heading_payout").after(response);
							}
							}
		 });
		 jQuery.ajax({

         type : "post",

         url : ajaxurl,

         data : {action: 'wdm_participant_list_manually',fund_id:fid},

         success: function(response) {
						
				console.log(response);
				jQuery("#loading_p_m").removeClass("show_load_p_m");
				jQuery("#loading_p_m").addClass("hide_load_p_m");
				if(response!=0){
					 jQuery("#heading_payout_manually").after(response);
				}
			}
		});	
			
		jQuery(document).on('click', '#close_out', function (e) 
		{
			e.preventDefault();
			jQuery(".manual-mass-close_out-popup").addClass("show");
		});
		jQuery(document).on('click', '#m-txt-link', function (e) 
		{
			e.preventDefault();
			jQuery(".manual-mass-order-popup,.manual-mass-generation-popup,.manual-mass-close_out-popup").removeClass("show");
		});
		var ajaxurl="https://glowbrandscandles.com/wp-admin/admin-ajax.php";
	
		jQuery(document).on('click', '#close_out_ajax', function (e) 
		{
				e.preventDefault();
				let fund_id = jQuery(this).attr('data-id');
				jQuery("#loading").removeClass("hide_load");
				jQuery("#loading").addClass("show_load");
				jQuery(".manual-mass-close_out-popup .manual-mass-order-popup-wrapper").html('<p style="padding-top:6px;color:green;font-size: 16px;">Please Wait...</p>');
				
						
				jQuery.ajax({

					type : "post",

					url : ajaxurl,

					data : {action: 'close_out_fundraiser', fund_id:fund_id},
	
					success: function(response) {
						console.log(response);
						jQuery("#loading").removeClass("show_load");
						jQuery("#loading").addClass("hide_load");
						
						<?php if(empty($mergeID) && empty($mergeQuanty)) { ?>
						
						jQuery(".manual-mass-close_out-popup .manual-mass-order-popup-wrapper").html('<p style="padding-top:6px;color:green;font-size: 16px;">'+response+'</p><p style="color:red;font-size: 16px;">Please wait, we are processing your order.</p>');
						jQuery.ajax({

							type : "post",

							url : ajaxurl,

							data : {action: 'wpm_order_merge_ajax', fid:fund_id},
			
							success: function(response) {
								console.log(response);
								jQuery(".manual-mass-close_out-popup .manual-mass-order-popup-wrapper").html('<p style="padding-top:6px;color:green;font-size: 16px;">'+response.data.action+'</p>');
							    setTimeout(function(){ jQuery(".manual-mass-close_out-popup").removeClass("show"); location.reload(); }, 2000);
							
							}
						});
						
						<?php } 
						else
						{
						?>
						
						jQuery(".manual-mass-close_out-popup .manual-mass-order-popup-wrapper").html('<p style="padding-top:6px;color:green;font-size: 16px;">'+response+'</p>');
						setTimeout(function(){ jQuery(".manual-mass-close_out-popup").removeClass("show"); location.reload(); }, 2000);
						
						<?php } ?>
					}
				});

			});
		/*	var fundId='206046';
	  jQuery.ajax({

					type : "post",

					url : ajaxurl,

					data : {action: 'seller_profit_fundraiser', fund_id:fundId},
	
					success: function(response) {
						console.log(response);
						
					}
				});*/
				jQuery('#rebook_ajax').click(function()
			  {
				  var fid=<?php echo $u_id; ?>;
				 jQuery.ajax({

					type : "post",

					url : ajaxurl,

					data : {action: 'wp_rebooking_ajax', id:fid},
	
					success: function(response) {
						console.log(response);
						
					}
				});
	           });
});
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script>
jQuery(document).ready(function(){
  jQuery('[data-toggle="tooltip"]').tooltip();   
});
</script>

<!-- Start Take Tour -->
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
	color: #333;
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
	color: #333;
	 line-height: 1.6;
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
 
min-width: 600px !important;
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
	height: 465px;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    border: 1px solid rgb(238, 238, 238);
    padding: 25px 25px 0px;
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
	color: #333;
}
.eYvePi {
    color: rgb(50, 50, 50);
    font-size: 17px;
    font-weight: 400;
    font-style: normal;
    line-height: 34px;
    text-align: center;
    padding-bottom: 50px;
	padding-bottom: 26px;
	color: #333;
	line-height: 1.6;
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
.tour-heading
{
	margin-bottom: 10px;
    border-bottom: 1px solid #cdc7c7;
    padding-bottom: 6px;
    font-weight: normal;
    font-family: 'SF Pro Display', 'Helvetica Neue', 'Helvetica', 'Roboto', 'Arial', sans-serif;
    color: #4a4a4a;
    text-transform: capitalize;
    line-height: 24px;
    font-size: 19px;
	font-family: 'Proxima Nova', Georgia, sans-serif;
	font-weight: 700;
	color: #333;
}
.tour-detail
{
    color: #828286;
    font-weight: normal;
    font: 14px 'SF Pro Display','Helvetica Neue',Helvetica,Arial,sans-serif;
    line-height: 1.5;
	font-family: 'Proxima Nova', Georgia, sans-serif;
	color: #333;
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
			     var ajaxurl="https://glowbrandscandles.com/wp-admin/admin-ajax.php";
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

							data : {action: 'wdm_welcome_pop_up_skip', skip_popup_user_id:id,skip_popup_FD:skip_popup},
			
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
                    element: jQuery('#A'),
                    content: '<h1 class="tour-heading">Fundraiser Details</h1><div class="tour-detail" style="font-size:15px;">In this section, your Fundraiser Code, Director Name, Fundraiser Start & End date. Click <b>Copy Button</b> to share your code. Click <b>Edit Info</b> to edit your fundraiser details. (Example: Fundraising goal, End date, etc.)<br/>View Your Profile, Click <b>Share button</b> to share your profile link on social media, email & text!</div>',
                    position: 'right'
                  },
                  {
                    element: jQuery('#B'),
                    content: '<h1 class="tour-heading">Seller Trackable Share Link</h1><div class="tour-detail" style="font-size:15px;"><b>Seller Register Link</b> ( Share this link with your group, your sellers to track their personal sales. Here they will sign up with their email to be notified of their orders.).<br/> Click <b>Copy Link Button</b> to click and share.</div>',
					position: 'left'
                  },
                  {
                    element: jQuery('#C'),
                    content: '<h1 class="tour-heading">Submit Your Catalog Orders</h1><div class="tour-detail" style="font-size:15px;">This section shows <b>Spring/Summer & Fall/Winter Catalog page links</b>.<br/>Click <b>Order Now</b>, you will be directed to the catalog page to place your catalog orders. For placing catalog order, select the items for each seller (matching their order form), Add to Cart, Type the Sellers name. Continue for each seller in your group.<br/><b>My Catalog Report Totals</b> section will show your total sales of Catalog orders placed.</div>',
                    position: 'right'
                  },
                  {
                    element: jQuery('#D'),
                    content: '<h1 class="tour-heading">Participant Breakdown</h1><div class="tour-detail" style="font-size:15px;">This section will show your total <b>Registered Participant</b> (your sellers). Their total sales & profits of each seller.<br/> By Click <b>Invite Seller</b>, you can send email invitation to your participant (seller) for register as seller. </div>',
                    position: 'left'
                  },
				  {
                    element: jQuery('#E'),
                    content: '<h1 class="tour-heading">Payout</h1><div class="tour-detail" style="font-size:15px;">The section will show your payout details for your Online/Catalog Sales as well as profits broken down by seller.<br/>Be sure to Click <b>ClOSE OUT Fundraiser</b> when you are ready to close out and have your online slaes profits payment sent to you.</div>',
                    position: 'right'
                  },
				  {
                    element: jQuery('#F'),
                    content: '<h1 class="tour-heading">Manually Added Seller</h1><div class="tour-detail" style="font-size:15px;">This section shows manually added sellers by customer. The customers entered the sellers name in the checkout process. You can check Total Sales and Profit of manually added seller.</div>',
                    position: 'left'
                  },
				   {
                    element: jQuery('#G'),
                    content: '<h1 class="tour-heading">Report</h1><div class="tour-detail" style="font-size:15px;">This section will show <b>Fundraiser and Seller Report Page Links</b>. In Fundraiser Report Page, both Online Orders and Catalog Orders with details of order date, amount, rebate, sellers name, etc. Click <b>Send Report to Fundraiser button</b>, you can easily send your fundraiser report to co-directors, sponsors, etc via email or by clicking <b>Send Report to Other</b>, you can send to any email address. In Seller Report Page, you can see orders report for each seller.</div>',
                    position: 'left'
                  }
                 
                ]);
					Tour.onstep = function(currentStep){
					console.log(currentStep);
					var get_content_of_current_step=Tour.current(); 
					console.log(get_content_of_current_step);
					
				};
				 Tour.onfinish = function(){
					console.log('The End');
					jQuery('#fullpage').removeClass("tour-active");
					jQuery('#root-2').css("display","block");
				
					var skip_popup="yes";
					var id="<?php if(!empty($u_id)) { echo $u_id; } ?>";
				   jQuery.ajax({

							type : "post",

							url : ajaxurl,

							data : {action: 'wdm_welcome_pop_up_skip', skip_popup_user_id:id,skip_popup_FD:skip_popup},
			
							success: function(response) {
								console.log(response);
								
							}
						});
				};
				Tour.onclose = function(){
					console.log('The End');
					jQuery('#fullpage').removeClass("tour-active");
					jQuery('#root-2').css("display","block");
				
					var skip_popup="yes";
					var id="<?php if(!empty($u_id)) { echo $u_id; } ?>";
				   jQuery.ajax({

							type : "post",

							url : ajaxurl,

							data : {action: 'wdm_welcome_pop_up_skip', skip_popup_user_id:id,skip_popup_FD:skip_popup},
			
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
			    jQuery("#root-2 .endTourAnimate").removeClass("isOpen");
                Tour.run([
				  {
                    element: jQuery('#A'),
                    content: '<h1 class="tour-heading">Fundraiser Details</h1><div class="tour-detail" style="font-size:15px;">In this section, your Fundraiser Code, Director Name, Fundraiser Start & End date. Click <b>Copy Button</b> to share your code. Click <b>Edit Info</b> to edit your fundraiser details. (Example: Fundraising goal, End date, etc.)<br/>View Your Profile, Click <b>Share button</b> to share your profile link on social media, email & text!</div>',
                    position: 'right'
                  },
                  {
                    element: jQuery('#B'),
                    content: '<h1 class="tour-heading">Seller Trackable Share Link</h1><div class="tour-detail" style="font-size:15px;"><b>Seller Register Link</b> ( Share this link with your group, your sellers to track their personal sales. Here they will sign up with their email to be notified of their orders.).<br/> Click <b>Copy Link Button</b> to click and share.</div>',
					position: 'left'
                  },
                  {
                    element: jQuery('#C'),
                    content: '<h1 class="tour-heading">Submit Your Catalog Orders</h1><div class="tour-detail" style="font-size:15px;">This section shows <b>Spring/Summer & Fall/Winter Catalog page links</b>.<br/>Click <b>Order Now</b>, you will be directed to the catalog page to place your catalog orders. For placing catalog order, select the items for each seller (matching their order form), Add to Cart, Type the Sellers name. Continue for each seller in your group.<br/><b>My Catalog Report Totals</b> section will show your total sales of Catalog orders placed.</div>',
                    position: 'right'
                  },
                  {
                    element: jQuery('#D'),
                    content: '<h1 class="tour-heading">Participant Breakdown</h1><div class="tour-detail" style="font-size:15px;">This section will show your total <b>Registered Participant</b> (your sellers). Their total sales & profits of each seller.<br/> By Click <b>Invite Seller</b>, you can send email invitation to your participant (seller) for register as seller. </div>',
                    position: 'left'
                  },
				  {
                    element: jQuery('#E'),
                    content: '<h1 class="tour-heading">Payout</h1><div class="tour-detail" style="font-size:15px;">The section will show your payout details for your Online/Catalog Sales as well as profits broken down by seller.<br/>Be sure to Click <b>ClOSE OUT Fundraiser</b> when you are ready to close out and have your online slaes profits payment sent to you.</div>',
                    position: 'right'
                  },
				  {
                    element: jQuery('#F'),
                    content: '<h1 class="tour-heading">Manually Added Seller</h1><div class="tour-detail" style="font-size:15px;">This section shows manually added sellers by customer. The customers entered the sellers name in the checkout process. You can check Total Sales and Profit of manually added seller.</div>',
                    position: 'left'
                  },
				   {
                    element: jQuery('#G'),
                    content: '<h1 class="tour-heading">Report</h1><div class="tour-detail" style="font-size:15px;">This section will show <b>Fundraiser and Seller Report Page Links</b>. In Fundraiser Report Page, both Online Orders and Catalog Orders with details of order date, amount, rebate, sellers name, etc. Click <b>Send Report to Fundraiser button</b>, you can easily send your fundraiser report to co-directors, sponsors, etc via email or by clicking <b>Send Report to Other</b>, you can send to any email address. In Seller Report Page, you can see orders report for each seller.</div>',
                    position: 'left'
                  }
                 
                ]);
				Tour.onstep = function(currentStep){
					console.log(currentStep);
					var get_content_of_current_step=Tour.current(); 
					console.log(get_content_of_current_step);
				
				};
				 Tour.onfinish = function(){
					console.log('The End');
					jQuery('#fullpage').removeClass("tour-active");
					jQuery('#root-2').css("display","block");
					jQuery("#root-2 .endTourAnimate").removeClass("isOpen");
					
				};
				Tour.onclose = function(){
					console.log('The End');
					jQuery('#fullpage').removeClass("tour-active");
					jQuery('#root-2').css("display","block");
					
					jQuery("#root-2 .endTourAnimate").removeClass("isOpen");
					
				};
              });
            });
          </script>

<div id="root">
   <div class="sc-jKVCRD dyPNnG">
      <div class="sc-kaNhvL eWPMk">
         <div class="sc-kxynE bhtBxK"></div>
         <div class="sc-LKuAh dWJjwC">Welcome!</div>
         <div class="sc-iBEsjs gouVSR">Let’s begin with a quick tour of the Fundraiser Dashboard page. Here you will see the purpose of each section, what links to share, online sales reports, close out, reporting, etc.<br/>We love the PROFILE Share link! Here your group can see your goal, up to date sales, logo, etc!</div>
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
         <div class="sc-ipZHIp eYvePi">Let’s begin with a quick tour of the Fundraiser Dashboard page. Here you will see the purpose of each section, what links to share, online sales reports, close out, reporting, etc.<br/>We love the PROFILE Share link! Here your group can see your goal, up to date sales, logo, etc!</div>
         <div id="positionsBtn-2" class="sc-bmyXtO qXac">View Tour</div>
      </div>
	  <div class="sc-ebFjAB hyMzIi"></div>
	  <div class="sc-jtggT fQiUEz"></div>
	</div>
</div>  

<?php
	get_footer();