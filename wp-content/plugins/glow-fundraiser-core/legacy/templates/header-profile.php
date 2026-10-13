<?php 
if ( session_status() === PHP_SESSION_NONE && ! headers_sent() ) {
	@session_start();
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <?php global $etheme_responsive, $woocommerce; ?>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
    
    <meta name="viewport" content="<?php if(!empty($etheme_responsive)): ?>width=device-width, initial-scale=1, maximum-scale=2.0<?php else: ?>width=1200<?php endif; ?>"/>
   	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE10" >
   	
	<link rel="shortcut icon" href="<?php echo function_exists('et_get_favicon') ? et_get_favicon() : get_site_icon_url(); ?>" />
	<title><?php wp_title( '|', true, 'right' );?></title>
	
		<?php
			if ( is_singular() && get_option( 'thread_comments' ) )
				wp_enqueue_script( 'comment-reply' );

			wp_head();
		?>
		<?php 	if ( get_query_var( 'profile' )) { 
	
	$IDG=get_query_var( 'profile' );
	$org=get_user_meta( $IDG , 'user_registration_input_company' , true );
	?>
	<meta property="og:site_name" content="Glow Scented Candles">
	<meta property="og:url" content="https://glowbrandscandles.com/profile/<?php echo $IDG; ?>">  
	<meta property="og:type" content="webpage"> 
	<meta property="og:title" content="<?php echo $org; ?> | Support Now | Fundraising">
	<meta property="og:description" content="Help us reach our goal! Thank you for the support!" />
	<meta property="og:image" content="https://glowscentedcandles.com/wp-content/uploads/2025/08/IMG_2919.png">
	<!-- WebPage Schema -> Start -->
	<script type="application/ld+json">
		{
			"@context": "https://schema.org",
			"@type": "WebPage",
			"name": "<?php echo $org; ?> | Support Now | Fundraising",
			"url" : "https://glowbrandscandles.com/profile/<?php echo $IDG; ?>",
			"publisher": {
				"@type": "Organization",
				"name": "Glow Scented Candles",
				"url" : "https://glowbrandscandles.com",
				"logo" : {
					"@type": "ImageObject",
					"contentUrl": "https://glowscentedcandles.com/wp-content/uploads/2025/08/IMG_2919.png"
				}
			}
		}
	</script>
	<?php } ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css">
      <!--  <script>
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
<style>
#paypal_email_field
{
	display:none;
}
.user-registration-message ul
{
	display:inline-block;
}
</style>
<style>
.wc_payment_methods .wc_payment_method.payment_method_cheque
{
	display:none;
}
</style>
<?php if(isset($_SESSION['sellername']) && $_SESSION['sellername']=='Catlog'){ ?>
<style>
.wc_payment_methods .wc_payment_method.payment_method_cheque
{
	display:block;
}
</style>
<?php } ?>
 <script>
		jQuery(document).ready(function () {
			var auto_email="<?php echo isset($_GET['email']) ? esc_js($_GET['email']) : ''; ?>";
			var auto_pass="<?php echo isset($_GET['pass']) ? esc_js($_GET['pass']) : ''; ?>";
			jQuery("#customer_login #username").val(auto_email);
			jQuery("#customer_login #password").val(auto_pass);
			jQuery("#payment_method_cheque").prop( "checked", false );
			jQuery("#payment_method_authorize").prop( "checked", true );
			
			jQuery("#user-registration-form-21998 .ur-field-item:nth-child(1)").after("<span style='font-weight: 700;font-size: 17px;'>Please complete the below form, however for online nothing will be mailed to you.</span>");
			var frid_val="<?php echo isset($_SESSION["fundraiser_id"]) ? esc_js($_SESSION["fundraiser_id"]) : ''; ?>";
			var seller_name="<?php echo isset($_SESSION["seller_name"]) ? esc_js($_SESSION["seller_name"]) : ''; ?>";
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
					/*e.preventDefault();*/
					var e = jQuery("#name").val();
	                var p = jQuery("#comment").val();
					var r = jQuery("input[name='rebate_option']").is(":checked");
					var f = jQuery("input[name='online_fundraiser_field']").is(":checked");
					var c= jQuery("#check_payable").val();
					var agree=jQuery("#check_box_new_field input").is(":checked");
					console.log(f);
					if(e!="" && p!="" && f==true && c!="" && agree==true)
					{
						jQuery(".manual-mass-order-popup-fund").addClass("show");
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
</head>

<body <?php body_class(); ?>>

<?php do_action( 'et_after_body' ); ?>

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
                                                <p class="form-row form-row-last">
                                                    <label for="password"><?php esc_html_e( 'Password', 'royal' ); ?> <span class="required">*</span></label>
                                                    <input class="input-text" type="password" name="password" id="password" />
                                                </p>
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
                                                     <p class="pull-left login-page"><a href="https://glowbrandscandles.com/my-account">Login Page</a></p>
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