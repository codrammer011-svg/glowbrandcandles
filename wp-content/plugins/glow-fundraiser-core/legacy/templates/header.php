<?php 
session_start();
		

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <?php global $etheme_responsive, $woocommerce; ?>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
    
    <meta name="viewport" content="<?php if($etheme_responsive): ?>width=device-width, initial-scale=1, maximum-scale=2.0<?php else: ?>width=1200<?php endif; ?>"/>
   	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE10" >
   	
	<link rel="shortcut icon" href="<?php echo et_get_favicon(); ?>" />
	<title><?php if ( is_front_page() ) {  bloginfo( 'name' );  } else { wp_title( '|', true, 'right' );  } ?></title>
		<?php
			if ( is_singular() && get_option( 'thread_comments' ) )
				wp_enqueue_script( 'comment-reply' );

			wp_head();
		?>
       <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css">
        <script>
		jQuery(document).ready(function () {
			jQuery(document).on('click', '#AddNewSeller', function (e) {
			e.preventDefault();
			var ajax_url= "https://glowfundraisers.com/wp-admin/admin-ajax.php";
			jQuery.post(ajax_url, {
							action: "wp_seller_send_email",
							f_id:'99',
							email:'jatinarora0075@gmail.com'
							}).success(function(posts){
								alert(posts);
							});
	  });
});
</script>-->
<?php $url_link = home_url( $_SERVER['REQUEST_URI'] );  ?>
	<meta property="og:site_name" content="Glow Scented Candles">
	<meta property="og:url" content="<?php echo $url_link; ?>">  
	<meta property="og:type" content="webpage"> 
	<meta property="og:title" content="<?php the_title(); ?> | Glow Scented Candles">
	<meta property="og:description" content="Our dreamy scents are inspired by nature and travel, moments and memories. We hand-pour our candles in our Glow shop using sustainable soy & coconut-waxes and phthalate-free perfume oils infused with essential oils." />
	<meta property="og:image" content="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2025/08/IMG_2919.png">
	<meta property="og:image:width" content="1200">
	<meta property="og:image:height" content="628">
	<meta property="og:image:type" content="image/png">
	<!-- WebPage Schema -> Start -->
	<script type="application/ld+json">
		{
			"@context": "https://schema.org",
			"@type": "WebPage",
			"name": "<?php the_title(); ?> | Glow Scented Candles",
			"url" : "<?php echo $url_link; ?>",
			"publisher": {
				"@type": "Organization",
				"name": "Glow Scented Candles",
				"url" : "<?php echo esc_url( glow_dynamic_url_base('root') ); ?>",
				"logo" : {
					"@type": "ImageObject",
					"url":"<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2025/08/IMG_2919.png",
					"contentUrl": "<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2025/08/IMG_2919.png"
				}
			}
		}
	</script>
<style>
body.user-registration-page .user-registration:not(.user-registration-MyAccount)
{
	max-width: 100%;
}
body.user-registration-page .user-registration:not(.user-registration-MyAccount) .ur-button-container .ur-submit-button
{
	width: 170px;
	background-color: #262626;
	border: 1px solid #262626;
}
 body.user-registration-page .user-registration.ur-frontend-form--flat:not(.login) form .ur-form-row .ur-form-grid textarea:not([type=checkbox]), body.user-registration-page .user-registration.ur-frontend-form--flat:not(.login) form .ur-form-row .ur-form-grid textarea:not([type=file]), body.user-registration-page .user-registration.ur-frontend-form--flat:not(.login) form .ur-form-row .ur-form-grid textarea:not([type=radio])
{
	    border: 1px solid #d5d5d5;
}
.ur-front-spinner {
   
    margin-right: -48px;
    float: right;
}
/*@font-face {
    font-family: 'Noir Pro';
    src: url('<?php echo esc_url( glow_dynamic_url_base('home') ); ?>/wp-content/themes/glowscentedcandles/fonts/noir-pro/np/NoirPro-Regular.woff') format('woff'),
		 url('<?php echo esc_url( glow_dynamic_url_base('home') ); ?>/wp-content/themes/glowscentedcandles/fonts/noir-pro/np/NoirPro-Regular.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: swap;
}
@font-face {
    font-family: 'Noir Pro Semi';
	src: url('<?php echo esc_url( glow_dynamic_url_base('home') ); ?>/wp-content/themes/glowscentedcandles/fonts/noir-pro/np/NoirPro-SemiBold.woff') format('woff'),
		 url('<?php echo esc_url( glow_dynamic_url_base('home') ); ?>/wp-content/themes/glowscentedcandles/fonts/noir-pro/np/NoirPro-SemiBold.woff2') format('woff2');
    font-weight: 600;
    font-style: normal;
    font-display: swap;
}
@font-face {
    font-family: 'Noir Pro';
    src: url('<?php echo esc_url( glow_dynamic_url_base('home') ); ?>/wp-content/themes/glowscentedcandles/fonts/noir-pro/np/NoirPro-Bold.woff2') format('woff2'),
        url('<?php echo esc_url( glow_dynamic_url_base('home') ); ?>/wp-content/themes/glowscentedcandles/fonts/noir-pro/np/NoirPro-Bold.woff') format('woff');
    font-weight: bold;
    font-style: normal;
    font-display: swap;
}*/
.wp-megamenu-main-wrapper.wpmm-orientation-horizontal ul.wpmm-mega-wrapper > li > a
{
	 /*font-family: 'Noir Pro Semi' !important;*/
	 font-weight: 600 !important;
    text-transform: uppercase;
}
.wpmm-sub-menu-wrap ul li.wp-mega-menu-header > a.wp-mega-menu-link span.wpmm-mega-menu-href-title
{
	 /* font-family: 'Noir Pro Semi' !important;*/
	 font-weight: 600 !important;
}
.wpmm-sub-menu-wrapper ul li a span.wpmm-mega-menu-href-title,
.shop-text,
#newhomw #slide-47-layer-18 a
{
	/* font-family: 'Noir Pro' !important;*/
	 font-weight: normal !important;
}
.order-list .media-heading a
{
	/*font-family: 'Noir Pro' !important;*/
    font-weight: 400 !important;
	color: #000;
}
.shopping-container .small-h,
.shopping-container .btn.border-grey,
.shopping-container .big-coast,
bottom-btn .btn.filled.big
{
	/*font-family: 'Noir Pro' !important;*/
    font-weight: 400 !important;
}
#paypal_email_field
{
	display:none;
}
.user-registration-message ul
{
	display:inline-block;
}
.wp-megamenu-main-wrapper.wpmm-orientation-horizontal ul.wpmm-mega-wrapper > li > a:hover
{
    color: #9eb7b3;
}
.header-type-6 .top-bar, .top-bar .free_ship_bann p, .top-bar .top-links a
{
	background-color: #9eb7b3 !important;
}
.GrupName {
    color: #fff !important;
    text-transform: capitalize !important;
}
@media only screen and (max-width: 767px)
{
	.home .wpb_single_image img 
	{
		margin-bottom: 20px;
	}
	.home .wpb_single_image.vc_align_left 
	{
		text-align: center;
		width: 55%;
		margin: 0 auto;
	}
}
@media only screen and (max-width: 450px)
{
	.home .wpb_single_image.vc_align_left 
	{
		text-align: center;
		width: 100%;
		margin: 0 auto;
	}
	.home .banner-boxed .wpb_single_image img
	{
		margin-bottom: 50px;
	}
}
</style>
<style>
.wc_payment_methods .wc_payment_method.payment_method_cheque
{
	display:none;
}
.page-template-template-checkout .payment_methods p
{
	font-size: 14px;
    line-height: 1.49564;
}
.page-template-template-checkout .wc_payment_method.payment_method_authorize label,
.page-template-template-checkout .wc_payment_method.payment_method_cheque label
{
	font-weight: 700;
    font-size: 14px;
}
</style>
<?php
$user = wp_get_current_user();
if ( in_array( 'fundraiser', (array) $user->roles ) ) {
  
?>
<style>
/* 
#wpmega-menu-main-menu #wp_nav_menu-item-21875,
#wpmega-menu-main-menu  #wp_nav_menu-item-20967,
#wpmega-menu-main-menu #wp_nav_menu-item-21925
{
	pointer-events: none;
}
*/
.home a.add_to_cart_button.btn.border-grey.progress-button.etheme_add_to_cart_button.ajax_add_to_cart.product_type_simple,
.archive a.add_to_cart_button.btn.border-grey.progress-button.etheme_add_to_cart_button.ajax_add_to_cart.product_type_simple,
.single button.single_add_to_cart_button.etheme-simple-product.button.alt,
.archive button.single_add_to_cart_button.etheme-simple-product.button.alt,
.home button.single_add_to_cart_button.etheme-simple-product.button.alt,
.single a.add_to_cart_button.btn.border-grey.progress-button.etheme_add_to_cart_button.ajax_add_to_cart.product_type_simple
{
   visibility: hidden;
   display: none;
}
</style>
<?php } ?>
<script>
jQuery(document).ready( function() 
{
	jQuery("input[name='billing_phone']").on('change', function(e)
	{
		jQuery("#billing_phone_field .codeError").remove();			
		console.log(jQuery(this).val().length);
		var dt=jQuery(this).val();
		var remChars = dt.replace(/[^0-9\.]/g,'');// this is to remove alphabetes and special characters
		//alert(remChars);
        var good_data = remChars.replace(/\./g,'');// this is to remove "DOT"
		//alert(good_data);
		var numberOfDigit_length=good_data.length;
	//	alert(numberOfDigit_length);
		if(numberOfDigit_length<10)
		{
		   jQuery(this).after("<b class='codeError' style='color:red;padding-top:4px;'>Error: Please enter 10 digit phone number.</b>");
		}
	});
	jQuery("input[name='fundraiser_field']").on('change', function(e){
		  
	   jQuery("#fundraiser_field_field .codeError").remove();
	   var codelength=jQuery(this).val().length;
	   console.log(jQuery(this).val().length);
	   if(codelength==6)
	   {
	   var $input = jQuery(this),
		   val = $input.val();
		   list = $input.attr('list'),
		   match = jQuery('#'+list + ' option').filter(function() {
			   return (jQuery(this).val() === val);
		   });

		if(match.length > 0) {
			// value is in list
			jQuery("#fundraiser_field_field .codeError").remove();
		} else {
			// value is not in list
			/*alert("value not exist");*/
			
			jQuery(this).after("<b class='codeError' style='color:red;padding-top:4px;'>Error: Fundraiser not found.</b>");
		}
	   }
	   else{
		   jQuery(this).after("<b class='codeError' style='color:red;padding-top:4px;'>Error: Please Enter/select 6 Digits Fundraiser Code.</b>");
	   }
	});
});
</script>

<?php  
if(is_front_page() || is_home())
{
?>
<style>
#seller-list
{
	display:none;
}
</style>

<?php 
}
/* New Field on Seller Register Profile upload */
$v=get_the_title(); if($v=="Seller Register"){ ?>
<style>
#user-registration-form-22005 #imageLoad { position: relative; width: 100%; margin:0 auto 10px !important; max-width: 1105px; }
#user-registration-form-22005 #imageLoad .fileUpload { width: 200px; position: relative; display: inline-block !important; border: 5px solid #ccc; background: #fff; text-align: center; padding: 10px; }
/*#imageLoad .sid_pic_img { background:url() no-repeat 0 0; background-size: cover; width: 225px; height: 205px; border: 5px solid #fff; outline: 5px solid #ccc;  }*/
#user-registration-form-22005 img {
    vertical-align: middle;
    border-style: none;
	width: 120px;
    height: 120px;
}
#user-registration-form-22005 .browse-button {
      width: 120px;
    height: 120px;
    border-radius: 100%;
    position: absolute;
    top: 0px;
    left: 0;
    background: linear-gradient(180deg, transparent, black);
    opacity: 0;
    transition: 0.3s ease;
}
#user-registration-form-22005 .browse-button:hover {
    opacity: 1;
}
#user-registration-form-22005 i {
    color: white;
    font-size: 25px;
    transform: translate(70px,79px);
}
#user-registration-form-22005 .browse-input { width: 100%; height: 100%; transform: translate(-1px,-26px); opacity: 0; cursor: Pointer; }
#user-registration-form-22005 .Error {
    color: crimson;
    font-size: 13px;
}
#user-registration-form-22005 .browse-button .error
{
	    position: absolute;
    width: 240px;
    top: 30px;
}
#user-registration-form-22005 #imageLoad label
{
	font-weight: 700;
	    margin-bottom: 15px;
}
#user-registration-form-22005 .pview
{
	position: relative;
    width: 100%;
}
#user-registration-form-22005 .imgdata
{
	display:none;
}
#user-registration-form-22005 label.ur-label 
{
	text-align:left;
}
#user-registration-form-22005 img {
    vertical-align: middle;
    border-style: none;
    width: 145px;
    height: 145px;
}
#user-registration-form-22005 .browse-button { width: 100%; height: 100%; position: absolute; top: 0px; left: 0;  background: linear-gradient(180deg, transparent, black); opacity: 0; transition: 0.3s ease; border-radius:0; }

#user-registration-form-22005  form.fileUpload {
    width: 100%;
}
#user-registration-form-22005 form.register
{
	width: 100%;
}
</style>
<script>
jQuery(document).ready( function() 
{
	<?php if(!empty($_GET['frid'])) { ?>
	jQuery("#user-registration-form-22005 form #seller_fundraiser").val(<?php echo $_GET['frid']; ?>);
	<?php } ?>
	jQuery("#user-registration-form-22005 form").before('<div id="imageLoad"><form class="fileUpload" enctype="multipart/form-data"><label class="ur-label">Upload your photo</label><div class="pview"><img class="preview-img" src="<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>images/photo.jpg" alt="Preview Image" width="200" height="200" id="img-upload"/><div class="browse-button"><input class="browse-input" type="file" required name="UploadedFile" id="imgInp"/></div></div></form>');
	
	function getExtension(filename) 
	{
		var parts = filename.split('.');
		return parts[parts.length - 1];
	}

	function isImage(filename) 
	{
		var ext = getExtension(filename);
		switch (ext.toLowerCase()) {
			case 'jpg':
			case 'png':
			//etc
		return true;
		}
		return false;
	}
	function failValidation(msg) 
	{
      alert(msg); // just an alert for now but you can spice this up later
      return false;
    }
	
	
    jQuery(document).on('change', '.btn-file :file', function() 
	{
		var input = jQuery(this),
		label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		input.trigger('fileselect', [label]);
	});

	jQuery('.btn-file :file').on('fileselect', function(event, label) 
	{
		var input = jQuery(this).parents('.input-group').find(':text'),
		        log = label;
		    
		if( input.length ) 
		{
		    input.val(log);
		} 
		else 
		{
		    if( log ) alert(log);
		}
	    
	});
	function readURL(input) 
	{
		    if (input.files && input.files[0]) 
			{
		        var reader = new FileReader();
		        
		        reader.onload = function (e) {
					
					 var file = jQuery('#imgInp');
					 if (!isImage(file.val())) 
					 {
						jQuery('#img-upload').attr('src', "<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/11/account.png");
						jQuery('#imgInp').val("");
						return failValidation('Only .jpg or .png formats are allowed.');
					}
					else
					{
						jQuery('#img-upload').attr('src', e.target.result);
						/*file_data = jQuery('#imgInp').prop('files')[0];*/
						file_data = jQuery('#imgInp')[0].files[0];
						form_data = new FormData();
						form_data.append('file', file_data);
						form_data.append('action', 'file_upload');
						var ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>";
						jQuery.ajax({
							url: ajaxurl,
							type: 'POST',
							contentType: false,
							processData: false,
							data: form_data,
							success: function (res) {
								
								console.log(res.data);
								jQuery('#user-registration-form-22005 .imgdata #image_url_profile_field #image_url_profile').attr("value", res.data);
							}
						});
						
					}
		        }
		        
		        reader.readAsDataURL(input.files[0]);
		    }
	}

	jQuery("#imgInp").change(function()
	{
		readURL(this);
	}); 
		
});
</script>
<?php } ?>

<?php $v=get_the_title(); if($v=="Fundraiser Signup"){ ?>
<style>
#imageLoad { position: relative; width: 100%; }
#user-registration-form-21998 #imageLoad
{
    width: 200px;
    margin: 0 auto;
    position: relative;
    padding-bottom: 15px;
    display: inline-block;
}
#user-registration-form-37978 #imageLoad
{
    width: 200px;
    margin: 0 auto;
    position: relative;
    padding-bottom: 15px;
    display: inline-block;
}
#user-registration-form-21998 .preview-img {
    border-radius: 100%;
    box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.7);
}

#user-registration-form-37978 .preview-img {
    border-radius: 100%;
    box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.7);
}

#user-registration-form-21998 img {
    vertical-align: middle;
    border-style: none;
	width: 120px;
    height: 120px;
}

#user-registration-form-37978 img {
    vertical-align: middle;
    border-style: none;
	width: 120px;
    height: 120px;
}

#user-registration-form-21998 .browse-button {
    width: 120px;
    height: 120px;
    border-radius: 100%;
    position: absolute;
    top: 0px;
    left: 0;
    background: linear-gradient(180deg, transparent, black);
    opacity: 0;
    transition: 0.3s ease;
}
#user-registration-form-37978 .browse-button {
    width: 120px;
    height: 120px;
    border-radius: 100%;
    position: absolute;
    top: 0px;
    left: 0;
    background: linear-gradient(180deg, transparent, black);
    opacity: 0;
    transition: 0.3s ease;
}
#user-registration-form-21998 .browse-button:hover {
    opacity: 1;
}
#user-registration-form-37978 .browse-button:hover {
    opacity: 1;
}
#user-registration-form-21998 i {
    color: white;
    font-size: 25px;
    transform: translate(70px,79px);
}
#user-registration-form-37978 i {
    color: white;
    font-size: 25px;
    transform: translate(70px,79px);
}
#user-registration-form-21998 .browse-input {
    width: 120px;
    height: 120px;
    border-radius: 100%;
    transform: translate(-1px,-26px);
    opacity: 0;
	    cursor: Pointer;
}
#user-registration-form-37978 .browse-input {
    width: 120px;
    height: 120px;
    border-radius: 100%;
    transform: translate(-1px,-26px);
    opacity: 0;
	    cursor: Pointer;
}
#user-registration-form-21998 .Error{
    color: crimson;
    font-size: 13px;
}
#user-registration-form-37978 .Error{
    color: crimson;
    font-size: 13px;
}
#user-registration-form-21998 .browse-button .error
{
	position: absolute;
    width: 240px;
    top: 30px;
}
#user-registration-form-37978 .browse-button .error
{
	position: absolute;
    width: 240px;
    top: 30px;
}
#user-registration-form-21998 #imageLoad label
{
	font-weight: 700;
	    margin-bottom: 15px;
}
#user-registration-form-37978 #imageLoad label
{
	font-weight: 700;
	    margin-bottom: 15px;
}
#user-registration-form-21998 .pview
{
	position: relative;
    width: 100%;
}
#user-registration-form-37978 .pview
{
	position: relative;
    width: 100%;
}
#user-registration-form-21998 .imgdata
{
	display:none;
}
#user-registration-form-37978 .imgdata
{
	display:none;
}
#user-registration-form-21998 form .ur-field-item #fr_goal_field span.sale_require
{
	display:none;
}
#user-registration-form-37978 form .ur-field-item #fr_goal_field span.sale_require
{
	display:none;
}
</style>
<script>
jQuery(document).ready( function() 
{
	jQuery("#user-registration-form-21998 form").before('<div id="imageLoad"><form class="fileUpload" enctype="multipart/form-data"><label class="ur-label">Upload Profile Image or logo</label><div class="pview"><img class="preview-img" src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/11/account.png" alt="Preview Image" width="200" height="200" id="img-upload"/><div class="browse-button"><i class="fa fa-pencil"></i><input class="browse-input" type="file" required name="UploadedFile" id="imgInp"/></div></div></form></div>');
	/* Calculation of total sale on Fundraiser Register Form */
	jQuery("#user-registration-form-21998 form .ur-field-item #fr_goal_field input").after("<span class='sale_require'>Total sales required = <b>$</b><b class='val'></b></span>");
	jQuery(document).on('change', '#user-registration-form-21998 form .ur-field-item #fr_goal_field input', function() 
	{
		var goal=jQuery("#user-registration-form-21998 form .ur-field-item #fr_goal_field input").val();
		var total_amount=goal*2.5;
		jQuery("#user-registration-form-21998 form .ur-field-item #fr_goal_field span.sale_require b.val").text(total_amount);
		jQuery("#user-registration-form-21998 form .ur-field-item #fr_goal_field span.sale_require").css("display","block");
	});
	
	/*jQuery("#user-registration-form-21998 form #check_box_new_field").after("<input id='imgdata' type='hidden' value=''>");*/
	function getExtension(filename) 
	{
		var parts = filename.split('.');
		return parts[parts.length - 1];
	}

	function isImage(filename) 
	{
		var ext = getExtension(filename);
		switch (ext.toLowerCase()) {
			case 'jpg':
			case 'png':
			//etc
		return true;
		}
		return false;
	}
	function failValidation(msg) 
	{
      alert(msg); // just an alert for now but you can spice this up later
      return false;
    }
	
	
    jQuery(document).on('change', '.btn-file :file', function() 
	{
		var input = jQuery(this),
		label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		input.trigger('fileselect', [label]);
	});

	jQuery('.btn-file :file').on('fileselect', function(event, label) 
	{
		var input = jQuery(this).parents('.input-group').find(':text'),
		        log = label;
		    
		if( input.length ) 
		{
		    input.val(log);
		} 
		else 
		{
		    if( log ) alert(log);
		}
	    
	});
	function readURL(input) 
	{
		    if (input.files && input.files[0]) 
			{
		        var reader = new FileReader();
		        
		        reader.onload = function (e) {
					
					 var file = jQuery('#imgInp');
					 if (!isImage(file.val())) 
					 {
						jQuery('#img-upload').attr('src', "<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/11/account.png");
						jQuery('#imgInp').val("");
						return failValidation('Only .jpg or .png formats are allowed.');
					}
					else
					{
						jQuery('#img-upload').attr('src', e.target.result);
						/*file_data = jQuery('#imgInp').prop('files')[0];*/
						file_data = jQuery('#imgInp')[0].files[0];
						form_data = new FormData();
						form_data.append('file', file_data);
						form_data.append('action', 'file_upload');
						var ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>";
						jQuery.ajax({
							url: ajaxurl,
							type: 'POST',
							contentType: false,
							processData: false,
							data: form_data,
							success: function (res) {
								
								console.log(res.data);
								jQuery('#user-registration-form-21998 .imgdata #image_url_profile_field #image_url_profile').attr("value", res.data);
							}
						});
						
					}
		        }
		        
		        reader.readAsDataURL(input.files[0]);
		    }
	}

	jQuery("#imgInp").change(function()
	{
		readURL(this);
	}); 

	jQuery("[data-field-id='check_payable']").before('<div id="imageLoad"><form class="fileUpload" enctype="multipart/form-data"><label class="ur-label">Upload Profile Image or logo</label><div class="pview"><img class="preview-img" src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/11/account.png" alt="Preview Image" width="200" height="200" id="img-upload"/><div class="browse-button"><i class="fa fa-pencil"></i><input class="browse-input" type="file" required name="UploadedFile" id="imgInp"/></div></div></form></div>');
		
});
</script>
<?php } ?>
 <script>
		jQuery(document).ready(function () {
			var auto_email="<?php echo $_GET['email']; ?>";
			var auto_pass="<?php echo $_GET['pass']; ?>";
			jQuery("#customer_login #username").val(auto_email);
			jQuery("#customer_login #password").val(auto_pass);
			jQuery("#payment_method_cheque").prop( "checked", false );
			jQuery("#payment_method_authorize").prop( "checked", true );
			
			jQuery("#user-registration-form-21998 .ur-field-item:nth-child(1)").after("<span style='font-weight: 700;font-size: 17px;'>Please complete the below form, however for online nothing will be mailed to you.</span>");
			var frid_val="<?php echo $frid_val=$_SESSION["fundraiser_id"]; ?>";
			var seller_name="<?php echo $sellerName=$_SESSION["seller_name"]; ?>";
			if(seller_name!='')
			{
			 jQuery("#wc_seller_field").val(seller_name);
			}
			/*jQuery('select#fundraiser_field').find('option').each(function() {
					var value=jQuery(this).val();
					if(frid_val==value)
					{
						jQuery(this).attr('selected', true);
					}
			});*/
			  jQuery("input[name='rebate_option']").on( "change", function() {
			  var test =jQuery(this).val();
			  if(test=="Check")
			  {
				  jQuery("#check_payable_field").css("display", "block");
				   jQuery("#paypal_email_field").css("display", "none");
					 jQuery("#paypal_email_field input").val("");
			  }
			  else if(test=="PayPal")
			  {
				  jQuery("#paypal_email_field").css("display", "block");
				   jQuery("#check_payable_field").css("display", "none");
					  jQuery("#check_payable_field input").val("");
			  }
			  else{
				  
			  }
			});
			/*jQuery(".register").submit(function(e) {*/
			jQuery('.register .ur-submit-button').on('click',function() {
				/*	e.preventDefault(); */
					
				/*	var e = jQuery("#name").val();
	                var p = jQuery("#comment").val(); */
					var r = jQuery("input[name='rebate_option']").is(":checked");
					var f = jQuery("input[name='online_fundraiser_field']").is(":checked");
					var c = jQuery("#check_payable").val();
					var agree=jQuery("#check_box_new_field input").is(":checked");
					console.log(c);
					if(f==true && c!=null && agree==true)
					{
						setTimeout(function(){ 
						
							if(jQuery('#user-registration-form-21998 div#ur-submit-message-node').text().length > 0) {  
							
							var txt=jQuery('#user-registration-form-21998 div#ur-submit-message-node ul').text();
							if(txt=='You are successfully registered.'){
								console.log('yes');
								jQuery(".manual-mass-order-popup-fund").addClass("show");
							  }

							}
						}, 18000);
						
						
					}
			});
				jQuery(document).on('click', '#m-txt-link-fund', function (e) {
			e.preventDefault();
			jQuery(".manual-mass-order-popup-fund").removeClass("show");
	  });
	   jQuery(document).on('click', '.login-link a', function (e) 
			{
				e.preventDefault();
				jQuery(".manual-mass-signup-popup").addClass("show");
			});
			jQuery(document).on('click', '#closeBtn', function (e) 
			{
				e.preventDefault();
				jQuery(".manual-mass-signup-popup").removeClass("show");
			});
});
</script>
<style>
	.woocommerce-product-gallery__wrapper.product-images-slider.main-images.images-popups-gallery
	{
		opacity: 1 !important;
	}
		.manual-mass-order-popup-fund,.manual-mass-signup-popup {
		position: fixed;
		width: 100%;
		height: 100%;
		z-index: 999;
		background: rgba(0,0,0,0.6);
		top: 0;
		left: 0;
		display: none;
	}
	.manual-mass-order-popup-fund .manual-mass-order-popup-wrapper,.manual-mass-signup-popup .manual-mass-signup-popup-wrapper {
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
.manual-mass-order-popup-fund a.fr-btn {
    display: block;
    margin: 20px auto;
    width: 100%;
    max-width: 250px;
}
.manual-mass-order-popup-fund .fr-btn
{
	font-size: 20px;
}
.manual-mass-order-popup-fund .fr-btn {
    font-size: 14px;
}
#check_box_new_field label.ur-label
{
	display:none;
}
input.wpcf7-form-control.wpcf7-text.wpcf7-email.wpcf7-validates-as-required.wpcf7-validates-as-email
{
	text-transform: inherit;
}
.menu-social-icons  li:nth-child(2)
{
	display:none;
}
	</style>
<style>
.content .shopping_cart
{
	width:80%;
	margin:40px auto;
}
.shopping_cart table
{
	border:1px solid #ccc !important;
}
.shopping_cart tbody
{
margin: 20px;
    float: left;
    width: 100%;
    display: table;
}
.shopping_cart input
{
	margin:auto;
}
.wpspsc_checkout_form form
{
    margin-top: 29px;
    float: right;
    margin-right: 89px;
}
.shop-table .table-bordered td.product-quantity .quantity input[type="number"] {
    width: 70px !important;
    text-align: center;
}
.productCarousel .owl-item:nth-child(4) .product-content-image
{
	    top: -42px;
}
/*POPUP*/
.manual-mass-signup-popup label
{
	    text-align: left;
    display: block;
}
.manual-mass-signup-popup .form-row
{
	width:100%;
}
.manual-mass-signup-popup span#closeBtn
{
	position: absolute;
    right: 10px;
    top: 5px;
    font-size: 20px;
	cursor:pointer;
}
.manual-mass-signup-popup .form-button
{
	    text-align: left;
    margin-bottom: 5px;
}
.manual-mass-signup-popup .form-button input[type=submit]
{
	width: 110px;
    padding: 10px;
    border-radius: 30px;
}
.manual-mass-signup-popup .lost-password
{
	margin:0;
}
.manual-mass-signup-popup .login-page
{
	width: 100%;
    text-align: left;
    margin: 0;
}
.wpcf7  input.wpcf7-form-control.wpcf7-text.wpcf7-email.wpcf7-validates-as-required.wpcf7-validates-as-email
{
	border: 1px solid #d5d5d5;
	background:none;
	
}
.wpcf7 input[type="text"]
{
	width:100%;
}

.wpcf7 textarea
{
	border: 1px solid #d5d5d5;
	width:100%;
}
.top-header-cls {
    background: #ee5992;
    padding: 5px 0;
}
.top-header-cls p,
.top-header-cls marquee {
    margin-bottom: 0;
    text-align: center;
    color: #fff;
}
.top-mobail-cls{
	display: none;
}
@media(max-width:767.98px){
	.top-desktop-cls{
		display: none;
	}
	.top-mobail-cls{
		display: block;
	}
	.top-header-cls.top-mobail-cls {
    padding: 5px 0px 0px;
}
}
</style>
<?php if(!empty($_SESSION['sellername']) && $_SESSION['sellername']=='Catlog') 
	{
	?>
		<style>
				#wc_seller_field_field
				{
					display:none !important;
				}
				</style>
	<?php
	}
	?>
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-178428900-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-178428900-1');
</script>
	
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
	
</head>

<body <?php body_class(); ?>>

<?php do_action( 'et_after_body' ); ?>
<?php /*?><div class="top-header-cls  top-desktop-cls">
	<p>Shipping notice! Glow is expanding moving to a new location, thanks to you our valued loved customers. Orders will resume shipping June 12th, until then shop away! Thank you for your patience during this exciting time.</p>	
</div>
<div class="top-header-cls top-mobail-cls">
	<marquee direction="left" height="30%">Shipping notice! Glow is expanding moving to a new location, thanks to you our valued loved customers. Orders will resume shipping June 12th, until then shop away! Thank you for your patience during this exciting time.</marquee>	
</div><?php */?>
<div class="manual-mass-signup-popup">
        <div class="manual-mass-signup-popup-wrapper">
		<span id="closeBtn" class="fa fa-close"></span>
            <form method="post" id="login" class="login" action="login" >

                                                <?php do_action( 'woocommerce_login_form_start' ); ?>
							<p class="status"></p>
                                                <p class="form-row form-row-first">
                                                    <label for="username"><?php esc_html_e( 'Username or email', 'royal' ); ?> <span class="required">*</span></label>
                                                    <input type="text" class="input-text" name="username" id="username" />
                                                </p>
												<label for="password"><?php esc_html_e( 'Password', 'royal' ); ?> <span class="required">*</span></label>
                                                <p class="form-row form-row-last password-container">
                                                    
                                                    <input class="input-text" type="password" name="password" id="password" /><i id="toggle-passwor" class="fa fa-eye"></i>
                                                </p>
												<style>
.password-container {
  position: relative;
}

#toggle-passwor {
  position: absolute;
  cursor: pointer;
  /* Position it to the right side of the input field */
  right: 10px;
  top: 10px; /* Adjust the top value as needed */
}

</style>	
<script>
jQuery(document).ready(function () {
	jQuery('body').on('click', '#toggle-passwor', function() {
		
		jQuery(this).toggleClass("fa-eye fa-eye-slash");
		let input = jQuery(jQuery(this).prev("input"));
		if (input.attr("type") == "password") {
		  input.attr("type", "text");
		} else {
		  input.attr("type", "password");
		}
	});
});

</script>
												
                                                <div class="clear"></div>

                                                <?php do_action( 'woocommerce_login_form' ); ?>

                                                <p class="form-row form-button">
                                                    <?php /* wp_nonce_field( 'woocommerce-login' ); */ ?>
													 <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
                                                    <input type="submit" class="button" name="login" value="<?php esc_html_e( 'Login', 'royal' ); ?>" />
                                                </p>

                                                <div class="clear"></div>

                                                <?php do_action( 'woocommerce_login_form_end' ); ?>
													<p class="lost-password pull-left"><?php esc_html_e('Lost your password?', 'royal'); ?> <a href="<?php echo esc_url( wc_lostpassword_url() ); ?>"><?php esc_html_e( 'Remind password', 'royal' ); ?></a></p>
                                                     <p class="pull-left login-page"><a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>my-account">Login Page</a></p>
										   </form>
											
											
        </div>
    </div>
<div id="st-container" class="st-container">
	<nav class="st-menu mobile-menu-block">
		<div class="nav-wrapper">
			<div class="st-menu-content">
				<div class="mobile-nav">
					<div class="close-mobile-nav close-block mobile-nav-heading"><i class="fa fa-bars"></i> <?php esc_html_e('Navigation', 'royal'); ?></div>
					
					<?php et_get_mobile_menu();	?>
					
					<?php if (etheme_get_option('top_links')): ?>
						<div class="mobile-nav-heading"><i class="fa fa-user"></i><?php esc_html_e('Account', 'royal'); ?></div>
						<?php etheme_top_links(array('popups' => false)); ?>
					<?php endif; ?>	
					
					<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('mobile-sidebar')): ?>
						
					<?php endif; ?>	
				</div>
			</div>
		</div>
		
	</nav>
	
	<div class="st-pusher" style="background-color:#fff;">
	<div class="st-content">
	<div class="st-content-inner">
	<div class="page-wrapper fixNav-enabled">

		<?php

			$ht = ''; $ht = apply_filters('custom_header_filter',$ht);
			$hstrucutre = etheme_get_header_structure($ht);
			if ( etheme_get_option('custom_header') ) $hstrucutre = 'custom';
			if ( etheme_get_custom_field( 'current_header_type', et_get_page_id() ) ) $hstrucutre = etheme_get_header_structure( etheme_get_custom_field( 'current_header_type', et_get_page_id() ) );
			if ( etheme_get_custom_field('current_custom_header', et_get_page_id() ) ) $hstrucutre = 'custom';

		?>

		<?php if (etheme_get_option('fixed_nav')): ?>

			<div class="fixed-header-area fixed-header-type-<?php echo esc_attr($ht); ?> color-<?php echo etheme_get_option('fixed_header_colors'); ?>">
				<div class="fixed-header">
					<div class="container">
					
						<div id="st-trigger-effects" class="column">
							<button data-effect="mobile-menu-block" class="menu-icon"></button>
						</div>
						    
						<div class="header-logo">
							<?php etheme_logo(true); ?>
						</div>

						<div class="collapse navbar-collapse">
								
							<?php 
								et_get_main_menu(); 
								et_get_main_menu('main-menu-right');
							?>
							
						</div><!-- /.navbar-collapse -->
						
						<div class="navbar-header navbar-right">
							<div class="navbar-right">
					            <?php if(class_exists('Woocommerce') && !etheme_get_option('just_catalog') && etheme_get_option('cart_widget')): ?>
				                    <?php echo do_shortcode( '[et_top_cart]' ); ?>
					            <?php endif ;?>
						            
								<?php if(etheme_get_option('search_form')): ?>
									<?php etheme_search_form(); ?>
								<?php endif; ?>

							</div>
						</div>
						
						<div class="modal-buttons">
							<?php if (class_exists('Woocommerce') && etheme_get_option('top_links')): ?>
	                        	<a href="#cartModal" class="popup-btn shopping-cart-link hidden-lg">&nbsp;</a>
							<?php endif ?>
							<?php if (is_user_logged_in() && etheme_get_option('top_links')): ?>
								<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" class="my-account-link hidden-lg">&nbsp;</a>
							<?php elseif(etheme_get_option('top_links')): ?>
								<a href="#loginModal" class="popup-btn my-account-link hidden-lg">&nbsp;</a>
							<?php endif ?>
							
							<?php if(etheme_get_option('search_form')): ?>
								<?php etheme_search_form(); ?>
							<?php endif; ?>
						</div>
							
					</div>
				</div>
			</div>
		<?php endif ?>
		
		<?php get_template_part('headers/header-structure', $hstrucutre); ?>