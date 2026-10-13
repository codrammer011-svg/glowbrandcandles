<?php 
	global $etheme_responsive; 
	$fd = etheme_get_option('footer_demo'); 	
	$fbg = etheme_get_option('footer_bg');
	$copybg = etheme_get_option('copyright_bg');
	$fcolor = etheme_get_option('footer_text_color');
	$prebg = etheme_get_option('prefooter_bg');
	$ft = ''; $ft = apply_filters('custom_footer_filter',$ft);
	$custom_footer = etheme_get_custom_field('custom_footer', et_get_page_id()); 
?>
    
    <?php if($custom_footer != 'without'): ?>
		<?php if((is_active_sidebar('footer1') || $fd) && empty($custom_footer)): ?>
			<div class="footer-top footer-top-<?php echo esc_attr($ft); ?>" <?php if($prebg != ''): ?>style="background-color:<?php echo $prebg; ?>"<?php endif; ?>>
				<div class="container">
	                <?php if ( !is_active_sidebar( 'footer1' ) ) : ?>
	               		<?php if($fd) etheme_footer_demo('footer1'); ?>
	                <?php else: ?>
	                    <?php dynamic_sidebar( 'footer1' ); ?>
	                <?php endif; ?>  
				</div>
			</div>
		<?php endif; ?>
		
	
		<?php if((is_active_sidebar('footer2') || $fd) && empty($custom_footer)): ?>
			<footer class="main-footer main-footer-<?php echo esc_attr($ft); ?> text-color-<?php echo $fcolor; ?>" <?php if($fbg != ''): ?>style="background-color:<?php echo $fbg; ?>"<?php endif; ?>>
				<div class="container">
	                <?php if ( !is_active_sidebar( 'footer2' ) ) : ?>
	               		<?php if($fd) etheme_footer_demo('footer2'); ?>
	                <?php else: ?>
	                    <?php dynamic_sidebar( 'footer2' ); ?>
                       
	                <?php endif; ?>
	                <?php do_action('etheme_after_footer_widgets'); ?>
				</div>
               

			</footer>
		<?php endif; ?>
	
		<?php if(!empty($custom_footer)): ?>
            <footer class="main-footer main-footer-<?php echo esc_attr($ft); ?> text-color-<?php echo $fcolor; ?>" <?php if($fbg != ''): ?>style="background-color:<?php echo $fbg; ?>"<?php endif; ?>>
                <div class="container">
                    <?php echo et_get_block($custom_footer); ?>  
                </div>
            </footer>
        <?php endif; ?>
	
		<?php if((is_active_sidebar('footer9') || is_active_sidebar('footer10') || $fd) && empty($custom_footer)): ?>
		<div class="copyright copyright-<?php echo esc_attr($ft); ?> text-color-<?php echo $fcolor; ?>" <?php if($copybg != ''): ?>style="background-color:<?php echo $copybg; ?>"<?php endif; ?>>
			<div class="container">
				<div class="row-copyrights">
					<div class="pull-left">
						<?php if(is_active_sidebar('footer9')): ?> 
							<?php dynamic_sidebar('footer9'); ?>	
						<?php else: ?>
							<?php if($fd) etheme_footer_demo('footer9'); ?>
						<?php endif; ?>
					</div>
					<div class="clearfix visible-xs"></div>
					<div class="copyright-payment pull-right">
						<?php if(is_active_sidebar('footer10')): ?> 
							<?php dynamic_sidebar('footer10'); ?>	
						<?php else: ?>
							<?php if($fd) etheme_footer_demo('footer10'); ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	    <?php endif; ?>
    <?php endif; ?>
	
	</div> <!-- page wrapper -->
	</div> <!-- st-content-inner -->
	</div>
	</div>
	<?php do_action('after_page_wrapper'); ?>
	</div> <!-- st-container -->
    <div class="seller-buyer-cookie-popup">
    <div class="popup-body">
        <form class="seller-buyer-cookie-form">
          <div>
<!--              <label>Buyer Name</label>-->
<!--            <input class="mass-order-buyer-name" type="text">-->
              <label>Seller Name</label>
              <input type="text" name="mass-seller-name-new" class="mass-seller-name-new">
              <select class="mass-order-seller-name" style="display:none;">
                <option value="No-seller">No Seller</option>
              </select>
            <input class="popup-submit-button" type="submit" value="Add Another Order"><br>
            <input class="popup-submit-button" type="submit" value="Checkout">
          </div>
        </form>
        <span class="popup-close">Or, Cancel</span>
    </div>
    </div>
	
<!-- <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script> -->
    <?php if (etheme_get_option('loader')): ?>
    	<script type="text/javascript">
    		if(jQuery(window).width() > 1200) {
		        jQuery("body").queryLoader2({
		            barColor: "#111",
		            backgroundColor: "#fff",
		            percentage: true,
		            barHeight: 2,
		            completeAnimation: "grow",
		            minimumTime: 500,
		            onLoadComplete: function() {
			            jQuery('body').addClass('page-loaded');
		            }
		        });
    		}
        </script>
	<?php endif; ?>

	<?php if ( etheme_get_option('fixed_ppopup') ): ?>
                    <div class="popup_link fixed-popup button <?php if(!etheme_get_option('promo_link')): ?>hidden<?php endif; ?>" <?php if (etheme_get_option('pp_button_bg')): echo 'style="background-color: ' . etheme_get_option('pp_button_bg') . '"'; endif; ?>><a class="etheme-popup <?php echo (etheme_get_option('promo_auto_open')) ? 'open-click': '' ; ?>" href="#etheme-popup"><?php if ( etheme_get_option('pp_title') == '' ) { esc_html_e('Newsletter', 'royal'); } else { echo etheme_get_option('pp_title'); } ?></a></div>
      <?php  endif; ?>
	
	<?php if (etheme_get_option('to_top')): ?>
		<div id="back-top" class="back-top <?php if(!etheme_get_option('to_top_mobile')): ?>visible-lg<?php endif; ?> bounceOut">
			<a href="#top">
				<span></span>
			</a>
		</div>
	<?php endif ?>
<style>
body .header-type-6 .top-bar, body .top-bar .free_ship_bann p, body .top-bar .top-links a,
body .footer-container, body .main-footer, body .copyright-3
{
	/*background: #86c5a1 !important; summer-color-footerfile*/
	background: #bf543a !important;
	 color: #000 !important;
}
.footer-container li a,
.footer-container .block .title_block,
.contact-w .block_content div:nth-child(2) a,
.contact-w .block_content div i {
    color: #000 !important;
}
body.page .header-type-6 .top-bar, body.page .top-bar .free_ship_bann p, body.page .top-bar .top-links a, body.archive .header-type-6 .top-bar, body.archive .top-bar .free_ship_bann p, body.archive .top-bar .top-links a, body.single .header-type-6 .top-bar, body.single .top-bar .free_ship_bann p, body.single .top-bar .top-links a
{
	/*background: #86c5a1 !important; summer-color-footerfile*/
	background: #bf543a !important;
	color: #000000 !important;
}
body.page .footer-container, body.page .main-footer, body.page .copyright-3, body.archive .footer-container, body.archive .main-footer, body.archive .copyright-3, body.single .footer-container, body.single .main-footer, body.single .copyright-3
{
	/*background: #86c5a1 !important; summer-color-footerfile*/
	background: #bf543a !important;
    color: #000000 !important;
}
</style>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script>

	jQuery(document).ready( function() 
{

 jQuery(".testimonial-slider").owlCarousel({
      items: 1,
      loop: true,
      margin: 20,
      nav: true,
      dots: true,
      autoplay: true,
      autoplayTimeout: 5000,
      autoplayHoverPause: true
    });


	jQuery('.ds_pp_box').click(function(e){
		e.stopPropagation();
	});
	jQuery('.ds_popup').click(function(e){

		e.preventDefault();
		e.stopPropagation();
		var val = jQuery(this).data('id');

		jQuery(val).fadeIn();
		//jQuery(val).css('display','block');
	});
	jQuery('.pp_closer').click(function(){
		jQuery('.ds_tech_popup').fadeOut();
	});
	jQuery(document).click(function() {
		jQuery('.ds_tech_popup').fadeOut();
	});

	});
</script>

<script type="text/javascript">
	jQuery('.fund-top').find('img').attr('id', 'pop_up_button');
	
	jQuery('<h4 class="sub-heading">Fundraising Agreement<span>*</span></h4>').insertBefore('#user-registration-form-62179 .ur-field-item[data-field-id="name_of_school_business"], #user-registration-form-37978 .ur-field-item[data-field-id="name_of_school_business"]');
	jQuery('<h4 class="sub-heading">Chairperson’s Information<span>*</span></h4>').insertBefore('#user-registration-form-62179 .ur-field-item[data-field-id="first_name"], #user-registration-form-37978 .ur-field-item[data-field-id="first_name"]');

	jQuery('<h4 class="sub-heading">Details<span>*</span></h4>').insertBefore('#user-registration-form-62179 .ur-field-item[data-field-id="radio_1534260773"], #user-registration-form-37978 .ur-field-item[data-field-id="radio_1534260773"]');
	setTimeout(function(){
		if (!jQuery('#imageLoad').length) {
			jQuery('#user-registration-form-62179 form [data-field-id="check_payable"], #user-registration-form-37978 form [data-field-id="check_payable"]').insertAfter('<div id="imageLoad"><label class="ur-label">Upload Profile Image or logo</label><div class="pview"><img class="preview-img" src="https://glowscentedcandles.com/wp-content/uploads/2020/11/account.png" alt="Preview Image" width="200" height="200" id="img-upload"><div class="browse-button"><i class="fa fa-pencil"></i><input class="browse-input" type="file" required="" name="UploadedFile" id="imgInp"></div></div></div>');
		}
	},300);
	

jQuery('#user-registration-form-22770 .ur-checkbox-list .input-checkbox').click(function(){
	if(jQuery(this).is(":checked")){
		jQuery('#card_no_field, #expire_field, #cvv_field').fadeIn(2000);
		//jQuery('#user-registration-form-22770 .ur-submit-button').attr('disabled',true);
	}
	else{
		jQuery('#card_no_field, #expire_field, #cvv_field').fadeOut(1000);
		//jQuery('#user-registration-form-22770 .ur-submit-button').attr('disabled',false);
	}
})
//jQuery('#card_no_field, #expire_field, #cvv_field')
</script>



<script>
jQuery(document).ready( function() 
{
	<?php if(!empty($_GET['frid'])) { ?>

	jQuery("#seller_fundraiser").val(<?php echo $_GET['frid']; ?>);
	<?php } ?>

	})
</script>

	<?php
		/* Always have wp_footer() just before the closing </body>
		 * tag of your theme, or you will break many plugins, which
		 * generally use this hook to reference JavaScript files.
		 */

		wp_footer();
	?>
	<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1539100323018048');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1539100323018048&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
</body>

</html>