
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <?php global $etheme_responsive, $woocommerce; ?>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
    
    <meta name="viewport" content="<?php if($etheme_responsive): ?>width=device-width, initial-scale=1, maximum-scale=2.0<?php else: ?>width=1200<?php endif; ?>"/>
   	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE10" >
   	
	<link rel="shortcut icon" href="<?php echo et_get_favicon(); ?>" />
	<title><?php if ( !is_front_page() || !is_home() ) {  wp_title( '|', true, 'right' );  } else { bloginfo( 'name' ); } ?></title>
	
	 <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/catalog/fullpage.css" />
     <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/catalog/examples.css" />
		<?php
			if ( is_singular() && get_option( 'thread_comments' ) )
				wp_enqueue_script( 'comment-reply' );

			wp_head();
		?>
		<!--<link rel='stylesheet' id='datatable-css-css'  href='<?php echo esc_url( glow_dynamic_url_base('content') ); ?>plugins/quick-orders-for-woocommerce/assets/public/datatables/datatables.min.css' type='text/css' media='all' />
		<link rel='stylesheet' id='quickster-public-css-css'  href='<?php echo esc_url( glow_dynamic_url_base('content') ); ?>plugins/quick-orders-for-woocommerce/assets/public/quickster-public.css' type='text/css' media='all' />-->
       
	   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css">
		 <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/catalog/custom-style.css" />
	
		<!--<script type='text/javascript' src='<?php echo esc_url( glow_dynamic_url_base('content') ); ?>plugins/quick-orders-for-woocommerce/assets/public/datatables/datatables.min.js' id='datatable-js-js'></script>
		<script type='text/javascript' src='<?php echo esc_url( glow_dynamic_url_base('content') ); ?>plugins/quick-orders-for-woocommerce/assets/public/jquery.number.js' id='jquery-number-js-js'></script>
		<script type='text/javascript' id='quickster-public-js-js-extra'>
		/* <![CDATA[ */
		var quickster = {"ajax_url":"https:\/\/glowscentedcandles.com\/wp-admin\/admin-ajax.php","ajax_nonce":"2aed081707","home_url":"https:\/\/glowscentedcandles.com","cart_message":"","infinity_scroll_container_height":"0","search_placeholder":"Search..","activate_infinity":"0","currency":"$","currency_position":"left","number_decimals":"2","decimal_separator":".","thousand_separator":",","bulk_price_text":""};
		/* ]]> */
		</script>
		<script type='text/javascript' src='<?php echo esc_url( glow_dynamic_url_base('content') ); ?>plugins/quick-orders-for-woocommerce/assets/public/quickster-public.js' id='quickster-public-js-js'></script>-->
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-178428900-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-178428900-1');
</script>
<style>
body.user-registration-page .user-registration:not(.user-registration-MyAccount)
{
	max-width: 100%;
}
body.user-registration-page .user-registration:not(.user-registration-MyAccount) .ur-button-container .ur-submit-button
{
	background-color: #262626;
	border: 1px solid #262626;
	width: 170px;
}
 body.user-registration-page .user-registration.ur-frontend-form--flat:not(.login) form .ur-form-row .ur-form-grid textarea:not([type=checkbox]), body.user-registration-page .user-registration.ur-frontend-form--flat:not(.login) form .ur-form-row .ur-form-grid textarea:not([type=file]), body.user-registration-page .user-registration.ur-frontend-form--flat:not(.login) form .ur-form-row .ur-form-grid textarea:not([type=radio])
{
	    border: 1px solid #d5d5d5;
}
.ur-front-spinner {
   
    margin-right: -48px;
    float: right;
}
.shopping-container 
{
	position: absolute;
    right: 30px;
}
.header.main-header .navbar
{
padding-bottom:0px;
}
.add-to-cart-Btn
{
	
}
.backButton
{
	    position: absolute;
		    right: 15%;
			    background: #000;
}
.backButton a
{
    display: inline-block;
    padding: 2px 20px;
    line-height: 38px;
    height: 42px;
	  height: auto;
    vertical-align: top;
    cursor: pointer;
    border: 1px dashed #000;
    font-size: 14px;
    text-transform: capitalize;
    color: #fff;
    font-family: 'Roboto', Arial, Helvetica, sans-serif;
    font-weight: 600;
}
.backButton a:hover,
.backButton a:focus
{
	    text-decoration: none;
		    background: rgb(242 242 242 / 16%);
}

</style>
</head>

<body <?php body_class(); ?>>
<div class="top-section">
<header class="header main-header">
	<div class="container">	
			<div class="navbar" role="navigation">
				<div class="container-fluid">
					<div class="header-logo">
					     <a href="<?php echo esc_url( glow_dynamic_url_base('root') ); ?>"><img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/07/logo.png" alt=""></a>
					</div>
					<div class="backButton">
					<?php  if ( is_user_logged_in() ) {
											$user = wp_get_current_user();
												if ( in_array( 'fundraiser', (array) $user->roles ) ) {
									?>
									<a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>fundraiser-dashboard/">fundraiser dashboard</a>
									<?php  }
									else {
									?>
											<a href="<?php echo wp_logout_url(); ?>">Log Out</a>
									<?php 									
									} }
										else { ?>
										<a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>my-account/">Sign In</a>
									<?php } ?>
					</div>
					<?php echo do_shortcode( '[et_top_cart]' ); ?>
				</div>
			</div>
	</div>
</header>
<!--<div class="page-heading bc-type-1">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 a-center">
                            <h1 class="title"><span>Spring Summer Catalogue Mass Order</span></h1>
                            <div class="breadcrumbs"><div id="breadcrumb"><a href="<?php echo esc_url( glow_dynamic_url_base('root') ); ?>">Home</a> <span class="delimeter">/</span> <span class="current">Spring Summer Catalogue Mass Order</span></div></div></div>
                    </div>
                </div>
            </div>
-->