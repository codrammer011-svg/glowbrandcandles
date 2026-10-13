<?php 
/* 
	Template Name: New Design Home 2
*/
	get_header();
?>
<style>
		.ApProductList .addbycustom { display: none; }
body.page .header-type-6 .top-bar, body.page .top-bar .free_ship_bann p, body.page .top-bar .top-links a, body.archive .header-type-6 .top-bar, body.archive .top-bar .free_ship_bann p, body.archive .top-bar .top-links a, body.single .header-type-6 .top-bar, body.single .top-bar .free_ship_bann p, body.single .top-bar .top-links a
{
	background-color: #bf543a !important;
	color: #000000 !important;
}
body.page .footer-container, body.page .main-footer, body.page .copyright-3, body.archive .footer-container, body.archive .main-footer, body.archive .copyright-3, body.single .footer-container, body.single .main-footer, body.single .copyright-3
{
	background: #bf543a !important;
    color: #000000 !important;
}
/*
@font-face {
    font-family: 'Beautiella';
    src: url('Beautiella.woff2') format('woff2'),
        url('Beautiella.woff') format('woff');
    font-weight: normal;
    font-style: normal;
    font-display: swap;
}*/

.modal-type-1 .modal-dialog .mini-text
{
    font-size: 13px;
    color: #6e6e6e;
}
.modal-type-1 .modal-dialog .large-h
{
    font-weight: 400;
    text-transform: uppercase;
    margin-top: 0;
    line-height: 1.3;
	    font-size: 24px;
}
a.btn.text-center.big.filled, a.btn.text-center.border-grey {
    letter-spacing: 0.1px;
}
.has-icon.icon-candle .title_block:before 
{
	background: url('<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>new-home-assets/images/otitle-p1.png') center center no-repeat !important;
}
.has-icon.icon-jar .title_block:before 
{
 background: url('<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>new-home-assets/images/otitle-p2.png') center center no-repeat !important;
}
.has-icon.icon-candle .title_block 
{
    display: inline-block;
    margin-bottom: 25px !important;
}
.title_block
{
  text-transform: unset;
}
.label-product2 p, .star-rating
{
	display:none;
}
.product-title {
    padding-top: 10px;
    display: inline-block;
    width: 100%;
}
.content-product .text-center.product-details {
    text-align: left;
}
a.add_to_cart_button.btn.border-grey.progress-button.etheme_add_to_cart_button.ajax_add_to_cart.product_type_simple {

	    background: transparent;
    border: 0;
    font-size: 15px;
    padding: 0;
	    font-family: "Jost",sans-serif;
		text-transform: unset;
}
.product-details .progress {
    display: unset;
}
.st-container .st-pusher {
    overflow: hidden;
}
.has-icon.icon-letter .title_block:before {
    background: url('<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>new-home-assets/images/otitle-7.png') center center no-repeat  !important;
}
.has-icon.icon-candle-taller .title_block:before {
    background: url('<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>new-home-assets/images/otitle-8.png') center center no-repeat  !important;

}
#image-form_13733885599081795 img,
#image-form_5974431382296516 img {
    width: 180px !important;
    padding-top: 30px !important;
}
.text-box-fourth a:hover, .box-show-more a:hover {
    background: transparent !important;
    color: #B26B53 !important;
	border: 0 !important;
}
.block.address_w.ApHtml {
    padding-left: 50px;
}
div#slide-46-layer-9,
div#slide-49-layer-9 {
   /*  font-family: "Beautiella" !important;
    font-style: normal; */
    font-weight: 400;
    font-size: 41px !important;
    position: absolute !important;
    color: #fff !important;
}
.choose_sec {
    width: 100% !important;
    margin: 0 auto !important;
    display: inline-block;
}
.sub-title-widget {
  
    margin-top: 18px !important; 
    margin-bottom: 10px !important;
}
.footer-container
{
	margin:0 !important;
}
.footer-text-box {
    padding: 110px 0 !important;
}
.sub-title-widget {
    padding-bottom: 30px;
}
.choose_sec .sub-title-widget {
    font-size: 35px;
    line-height: 2.09rem;
}
#slide-47-layer-18:hover a
{
	
}

.cover-img {
    display: inline-block;
    position: relative;
    overflow: hidden;
    width: auto;
}
.cover-img .overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    text-align: center;
    /* visibility: hidden; */
    opacity: 0;
    background-image: -webkit-linear-gradient(bottom, #e1dfed 0%, rgba(225, 223, 237, 0.7) 99%, rgba(225, 223, 237, 0.8) 100%);
    background-image: linear-gradient(to top, rgb(220, 220, 220) 4%, rgb(220, 220, 220 / 50%) 100%, rgb(220, 220, 220/ 50%) 100%);
	background-image: -webkit-linear-gradient(bottom, rgb(238 89 146 / 70%) 0%, rgb(249 144 115 / 90%) 100%, rgb(238 89 146 / 95%) 100%);
    -webkit-transition: all 0.2s ease-in-out;
	transition: all 0.2s ease-in-out;
    width: 100%;
    height: 100%;
    border-radius: 0;
    overflow: hidden;
    cursor: pointer;
}
.cover-img .overlay a
{
	display: block;
    position: absolute;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    width: 100%;
    color: #333333;
	    color: #fff;
    font-size: 18px;
    font-family: 'Proxima Nova';
    font-weight: bold;
}
.cover-img .overlay a:hover
{
	color:#b26b53;
	    color: #fff;
}
.cover-img .overlay:hover 
{
	  opacity: 1;
}
.page-home .right-block .flag 
{

	/*background: #f99073; -summer color*/
	background: #bf543a;
}
.page-home .left-block .flag 
{
	/* background: #98a941; */
	background: #11696b;
}
.page-home a.home_slider_btn
{
	color:#fff !important;
}
#newhomw #slide-47-layer-18 
{
    text-align: center !important;
    width: 180px !important;
    padding: 15px 40px !important;
}
#newhomw #slide-47-layer-18 a
{
	color:#fff !important;
}
#newhomw #slide-47-layer-18:hover a
{
	color:#000 !important;
}

div#slide-47-layer-18 {
    font-size: 16px !important;
    padding: 10px 20px !important;
    text-align: center !important;
}
.season-banner {
    width: 100%;
    margin: 0 auto;
    float: left;
    padding: 50px 0;
    position: relative;
    text-align: center;
    height: auto;
}
.season-banner a
{
	display: inline-block;
    width: 100%;
}
.season-banner a img
{
	    height: auto;
}
@media (max-width: 1199px){


}
@media (max-width: 1090px)
{
	#newhomw #slide-47-layer-18
	{
		text-align: center !important;
		width: 125px !important;
		padding: 12px 40px !important;
	}
}
@media (min-width: 992px)
{
	.bestforyou .sub-title-widget 
	{
		padding-bottom: 30px !important;
	}

}
@media (max-width: 1000px)
{
	.season-banner 
	{
		padding: 20px 0;
	}
}
@media (max-width: 580px)
{
	.season-banner 
	{
		padding: 15px 0;
	}
}
@media only screen and (max-width: 480px)
{
	.woocommerce-cart, .st-container, .st-container .st-pusher, .page-wrapper 
	{
		overflow-x: hidden !important;
		-ms-overflow-x: hidden !important;
	}
	#newhomw #slide-47-layer-18  a
	{
		text-align:center !important;
		width: auto !important
	}
	#newhomw #slide-47-layer-18
	{
		width: 110px !important;
		padding: 7px 20px !important;
		font-size: 14px !important;
	}
}
@media (min-width: 768px) and (max-width: 1199px)
{
	.ApCategoryImage.category-glow-2 .block_content 
	{
		grid-template-columns: repeat(3,1fr);
	}
}
@media (min-width: 1200px)
{
	.ApCategoryImage.category-glow-2 .block_content 
	{
		grid-template-columns: repeat(3,1fr);
	}
}
@media (max-width: 375px)
{
	#slide-47-layer-12
	{
		min-width: 125px !important;
		max-width: 125px !important;
		padding: 7px 10px !important;
		font-size: 14px !important;
		line-height: 20px !important;
	}
}
</style>
<script type="text/javascript">
 
	/*ap_list_functions.push(function(){ */
		$(".view_all_wapper").hide();
		var limit = 5;
		var level = 1 - 1;
		$("ul.ul-categories-image-2348380643, ul.ul-categories-image-2348380643 ul").each(function(){
			var element = $(this).find(">li").length;
			var count = 0;
			$(this).find(">li").each(function(){
				count = count + 1;
				if(count > limit){
					// $(this).remove();
					$(this).hide();
				}
			});
			if(element > limit) {
				view = $(".view_all","#view_all_wapper_categories-image-2348380643").clone(1);
				// view.appendTo($(this).find("ul.level" + level));
				view.appendTo($(this));
				var href = $(this).closest("li").find('a:first-child').attr('href');
				$(view).attr("href", href);
			}
		})
/*	}); */

</script>
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
-->
<link rel="stylesheet" href="<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>new-home-assets/css/theme-a0c61315.css" type="text/css" media="all"/>
<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;0,400;0,500;0,600;0,900&display=swap" rel="stylesheet">
<!--<link rel="stylesheet" href="<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>new-home-assets/css/owl-gallery.css">
<script type="text/javascript" src="<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>new-home-assets/js/bootstrap.min.js" ></script>
<script type="text/javascript" src="<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>new-home-assets/js/owl-gallery.js" ></script>-->
<style>
.main-header .navbar-collapse { display:block; }
.mh-ph .sub-title-widget {
    padding-bottom: 20px;
}
a.add_to_cart_button.btn.border-grey.progress-button.etheme_add_to_cart_button.ajax_add_to_cart.product_type_simple:hover
{
	
}
.footer-container {
    background: #d0bbb7;
	background: #9eb7b3;
    color: #fff;
}
.footer-container .block .title_block {
    color: #fff;
    text-transform: uppercase;
    font-weight: 600;
}
.footer-container li a {
    color: #fff;
}
.footer-bottom {
    border-top: 1px solid #fff;
}
.glow_recommended
{
	    background: #fff;
    border-top: 1px solid #f1f1f1;
}

.block.copyrigh.ApRawHtml a {
    color: #fff;
}
.block.copyrigh.ApRawHtml a:hover {
    color: #b26b53;
}
.ApCategoryImage .view-all:after {
    font-family: "FontAwesome";
    font-weight: 300;
    font-size: inherit;
    content: "\f178";
    padding-left: 5px;
}
.btn-product .icon-btn-product, .btn-product i, .quick-view .icon-btn-product, .quick-view i,
.box-show-more a:after, .fal
{
	font-family: "FontAwesome" !important;
}
.text-box-fourth .title-2 {
    font-size: 40px;
    line-height: 48px;
    color: #212121;
}
.text-box-fourth .title {
    font-size: 40px;
    line-height: 48px;
    color: #212121;
}
.text-box-fourth a,
.footer-text-box a
{
	    height: auto !important;
}
.glow_recommended .product-content-image {
    border: 1px solid #f7f7f7;
}
.glow_recommended a.btn.border-grey.product_type_simple {
    background: transparent;
    padding-left: 0;
    text-transform: initial;
}
.glow_recommended a.btn.border-grey.product_type_simple:hover
{
	border:0;
}

@media (max-width: 1199px)
{
	#image-form_13733885599081795 img, #image-form_5974431382296516 img 
	{
		width: 115px !important;
		padding-top: 30px !important;
		padding-bottom: 20px;
	}
	.footer-center {
    padding-top: 60px;
    padding-bottom: 40px;
}
}
@media (max-width: 768px)
{
	.footer-container .block .title_block 
	{
		margin-bottom: 12px;
		margin-top: 20px;
	}
	.footer-center 
	{
		padding-bottom: 15px;
	}
	#content .bottom_banner.vc_row 
	{
		padding-left: 7px !important;
		padding-right: 13px !important;
	}
}
@media (min-width: 992px)
{
	.glow_recommended .mh-rec
	{
	   
		padding-top: 72px;
	}
	.mh-ph
	{
		padding-bottom: 0;
	}
	.client-logo .margin-home {
		padding-bottom: 75px;
	}
	.client-logo .margin-home {
		padding-top: 10px ;
	}
	.just-for-you.margin-home {
		padding-top: 10px ;
	}
}


</style>

<?php get_template_part('shortcode/sale-banner', ''); ?>
<section id="wrapper-newdesgin">
<div class="row">
	<div class="rev-slider">
		<div class="content">
			<?php echo do_shortcode('[rev_slider alias="new home"]'); ?>
		</div>
	</div><!-- end row-fluid -->
</div>
<div class="row">
   <div id="content-wrapper" class="col-lg-12 col-xs-12">
				<?php if(have_posts()): while(have_posts()) : the_post(); ?>
				<?php the_content(); ?>
				<?php endwhile; else: ?>
				<h3><?php esc_html_e('No pages were found!', 'royal') ?></h3>
				<?php endif; ?>
	
	</div><!-- end row-fluid -->
</div>
</section>

<footer id="footer" class="footer-container">
<div class="footer-top">
<div class="container">
<div class="inner"></div>
</div>
</div>
<div class="footer-center">
<div class="container">
<div class="inner">
<div class="row ApRow has-bg bg-boxed" style="background: no-repeat;" data-bg_data=" no-repeat">
<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-xs-12 col-sp-12 first-wrap ApColumn ">
<div class="footer_logo "><a title="Leo Olars" href="#">
 <!--<img class="logo img-fluid" src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2022/09/logo-d-1.png" alt="" />-->
  <img class="logo img-fluid" src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/07/logo.png" alt="" />
 </a></div>
<div class="block address_w ApHtml">
<div class="block_content">
Glow Scented Candles make it easy for you and your team to fundraise beyond your neighborhood without ever having to handle money or deliver product.
</div>
</div>
</div>
<div class="col-xl-4 col-lg-3 col-md-3 col-sm-12 col-xs-12 col-sp-12 second-w ApColumn ">
<div class="block ApLink ApBlockLink">
<h4 class="title_block">Fundraise</h4>
<ul>
 	<li><a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>online-fundraising/" target="_self" rel="noopener">Online Fundraising</a></li>
 	<li><a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>fundraiser_page/" target="_self" rel="noopener">Fundraiser</a></li>
	<li><a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>wholesale/" target="_self" rel="noopener">Wholesale</a></li>
    <li><a href="/glow-policies/" target="_self" rel="noopener">Privacy Policy</a></li>

</ul>
</div>
</div>
<div class="col-xl-4 col-lg-3 col-md-3 col-sm-12 col-xs-12 col-sp-12 third-wrap ApColumn ">
<div class="block ApLink ApBlockLink">
<h4 class="title_block">Need more help?</h4>
<ul>
 	<li><a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>contact-us/" target="_self" rel="noopener">Contact Us</a></li>
 
</ul>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="footer-bottom">
<div class="container">
<div class="inner">
<div class="row ApRow ">

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-sp-12 ApColumn ">
<div class="block contact-w ApHtml">
<div class="block_content">
<div><i class="fal fa-map-marker"></i> GLOW SCENTED CANDLES LLC 3702 Cavalier Street Suite 100 Garland, TX 75042</div>
<!--<div><i class="fal fa-headphones"></i><a href="tel:972.941.6874">972.941.6874</a></div>-->
	<div><i class="fal fa-paper-plane"></i><a href="mailto:contact@glowscentedcandles.com"> contact@glowscentedcandles.com</a></div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</footer>
<style>
.ApCategoryImage .block_content img {
    width: auto;
  
}
.contact-w .block_content div a {
    color: #fff;
}
.ApCategoryImage .leo-qty {
  right: 67px;
}
.footer-bottom .ApColumn .block {
    text-align: center;
}
#image-form_26155117847947185 a {
    display: inline-block;
}
.gallery-mask {
    background-color: #000000e3;

}
.gallery-container .close {
    position: absolute;
    top: 22%;
}
#gallery-slider .owl-item img
{
	    top: 54%;
		    left: 35%;
    text-align: center;
	width:auto;
}
#image-form_26155117847947185 a {
    display: inline-block;
}
.gallery-mask {
    background-color: #000000e3;

}
.gallery-container .close {
    position: absolute;
    top: 22%;
}
#gallery-slider .owl-item img
{
	    top: 54%;
		    left: 35%;
    text-align: center;
	width:auto;
}
#newhomw div#slide-47-layer-16, #newhomw div#slide-56-layer-1 {
    font-size: 40px !important;
    font-weight: 400 !important;
    text-transform: none;
    font-style: normal;
    text-decoration: none;
    line-height: 56px !important;
}
#newhomw div#slide-47-layer-11,
#newhomw div#slide-47-layer-10,
#newhomw div#slide-56-layer-2,
#newhomw div#slide-56-layer-3 {
    font-family: "jost" !important;

}
/*
div#newhomw_wrapper 
{
    height: 733px !important;
}
*/
.bottom_banner.vc_row
{
	    padding-top: 200px !important;
    padding-bottom: 200px !important;
    background-position: center !important;
    background-repeat: no-repeat !important;
    background-size: cover !important;
}
.footer-bottom,
.contact-w .block_content div,
.contact-w .block_content div:nth-child(2) a {
 
    font-size: 15px;
}
span.top_seller {
    max-width: 100%;
    display: inline-block;
    width: 100%;
    float: left;
    margin-bottom: 30px;
}
.page-home .left-block .flag {
  /*  background: #98a941; */
	background: #11696b;
    margin-top: 3px;
    margin-bottom: 26px;
}
.box-show-more .btn
{
	    border: 0;
}
.page-home .right-block .flag {
  /*  background: #f99073; */
	background: #bf543a;
    margin-top: 2px;
    margin-bottom: 36px;
	margin-bottom: 0px;
}
.home a:hover,
.home a:focus,
.show-quickly:hover,
.wp-megamenu-main-wrapper.wpmm-orientation-horizontal ul.wpmm-mega-wrapper > li > a:hover
{
    color: #9eb7b3;
}
.block.copyrigh.ApRawHtml a:hover,
.footer-container li a:hover {
    color: #f1f1f1;
}
.text-box-fourth a:hover, .box-show-more a:hover
{
	color: #9eb7b3 !important;
}

@media (max-width: 1199px)
{
	.page-home .ApCategoryImage .block_content
	{
		gap: 0px;
	}
	.page-home ul.level0.ul-categories-image-2154629658 
	{
		padding: 0 20px;
		margin: 0;
	}
	.page-home .text-center.product-details 
	{
		padding: 0 10px;
	}
	.page-home .letter-box .block_newsletter
	{
		margin: 35px 20px;
		    padding: 50px 15px;
	}

	.page-home .flag 
	{
		position: relative;
		background: #539ab2;
		background: #f99073;
		display: inline-block;
		color: #fff;
		font-size: 15px;
		text-transform: uppercase;
		height: 34px;
		line-height: 33px;
		padding: 0 40px;
		margin-top: 0;
		margin-bottom: 9px;
		margin-top: 17px;
	}
	.page-home .text-box-fourth .title-2 
	{
		font-size: 21px;
		line-height: 27px;
		color: #212121;
	}
	.page-home .text-box-fourth p {
		font-size: 16px;
		line-height: 24px;
		 margin-top: 0;
	}
	.ApCategoryImage .leo-qty 
	{
		right: 0;
	}	
	.bottom_banner.vc_row 
	{
		padding-top: 190px !important;
		padding-bottom: 195px !important;
		padding-left: 107.5px !important;
		padding-right: 136.5px !important;
	}
}
@media (min-width: 992px)
{
	.just-for-you.margin-home 
	{
		padding-top: 90px;
	}
}
@media only screen and (max-width: 640px){
#newhomw div#slide-47-layer-16, #newhomw div#slide-56-layer-1
{
    font-size: 30px !important;
    line-height: 40px !important;
}

.sub-title-widget {
    font-size: 30px;
    line-height: 40px;
}
.sub-title-widget {
    padding-bottom: 10px;
}
.text-box-fourth .title-2 {
    font-size: 25px;
    line-height: 30px;
    color: #212121;
}
.choose_sec .sub-title-widget {
    font-size: 26px;
    line-height: 30px;
}
}

@media (max-width: 767px)
{
.footer-container .ApColumn .block {
    padding-left: 12px;
}

}
@media (max-width: 575px)
{
	.block_newsletter form .btn {
		position: absolute;
		right: 25px;
	}
	.page-home .ApCategoryImage .block_content 
	{
	  gap: 0;
	}
	.page-home ul.level0.ul-categories-image-2154629658 {
		padding: 0 24px;
		margin: 0;
	}
	.page-home #shop_button
	{
	 left: 160px !important;
	}
	.page-home ul.level0.ul-categories-image-2154629658 
	{
		text-align: center;
	}
	/*div#image-form_4898561287828256 img, div#image-form_26155117847947185 img, div#image-form_8061757028094676 img, div#image-form_38878241996381105 img
	{
		height: 400px;
		width: 400px;
	}
	*/
	.page-home #image-form_13733885599081795 img, #image-form_5974431382296516 img {
    width: 126px !important;
    padding-top: 0  !important;
}
.page-home .right-block .flag {
  
	  /*  background: #f99073; */
	background: #bf543a;
    margin-top: 17px;
    margin-bottom: 27px;
}
.page-home .left-block .flag {
    /* background: #98a941; */
	background: #11696b;
    margin-top: 15px;
    margin-bottom: 25px;
}
.footer-container .block .title_block {

    margin: 0 0 15px 0;
 padding-top: 28px;
}
.footer-container .ApColumn .block ul{
	margin:0;
}
.col-xl-4.col-lg-4.col-md-4.col-sm-12.col-xs-12.col-sp-12.third-wrap.ApColumn {
    padding-bottom: 24px;
}
}
@media only screen and (max-width: 375px)
{
	#shop_button.tp-parallax-wrap
	{
		left: 131px !important
	}
/*	#season-candles.tp-parallax-wrap
	{
		    left: 130px !important;
	}
	*/
	#newhomw #season-candles.tp-parallax-wrap div#slide-56-layer-1,
	#newhomw #season-candles.tp-parallax-wrap div#slide-47-layer-16	
	{
    font-size: 19px !important;
    line-height: 29px !important;
}
.choose_sec .sub-title-widget {
    font-size: 19px;
    line-height: 25px;
}
.title_block {
    font-style: normal;
    font-weight: 400;
    font-size: 45px;
}
.block_newsletter p:not(.alert), .block_newsletter p:not(.leo-phone) {
    font-size: 16px;
    line-height: 23px;
    margin-top: 14px;
}
.text-box-fourth .title-2 {
    font-size: 20px;
    line-height: 1.4;
}
.sub-title-widget {
    font-size: 23px;
    line-height: 33px;
}
.ApCategoryImage .cate-name {
    font-size: 16px;
    line-height: 22px;
}
.header-box p {
    font-size: 14px;
}
}
@media only screen and (max-width: 360px)
{
#shop_button.tp-parallax-wrap
{
    left: 133px !important;
}
}
@media only screen and (max-width: 320px)
{
#shop_button.tp-parallax-wrap
{
    left: 110px !important;
}
}
.bestforyou .text-box-fourth a:hover, .bestforyou .box-show-more a:hover {
    border: 0 !important;
}
.gallery-section .margin-thome {
    padding-top: 0;
}
.home-video-section .html5-video-player a,
.home-video-section .ytp-chrome-top, .home-video-section .ytp-chrome-bottom
{
	z-index: -1 !important;
}
div#royal_img {
    margin-bottom: 26px;
}
.home-video-section .divider.horizontal-break
{
	    padding-right: 52px;
}
</style>
 <script type="text/javascript">

		$('#carousel-1895144326').owlCarousel({
			items :				5,
			itemsDesktop :		[1200,4],
			itemsDesktopSmall :	[992,4],
			itemsTablet :		[768,3],
			itemsMobile :		[576,2],
			itemsCustom :		false,
			singleItem :		false,       // true : show only 1 item
			itemsScaleUp :		false,
			slideSpeed :200,        //  change speed when drag and drop a item
			paginationSpeed :	800,       // change speed when go next page
			autoPlay :			false,       // time to show each item
			stopOnHover :		false,
			navigation :		false,
			navigationText :	["&lsaquo;", "&rsaquo;"],
			scrollPerPage :		false,
			pagination :		false,       // show bullist
			paginationNumbers :	false,       // show number
			responsive :		true,
			lazyLoad :			false,
			lazyFollow :		false,       // true : go to page 7th and load all images page 1...7. false : go to page 7th and load only images of page 7th
			lazyEffect :		"fade",
			autoHeight :		false,
			mouseDrag :			true,
			touchDrag :			true,
			addClassActive :	true,
			direction:			false,
			afterInit:			OwlLoaded
		});
	

function OwlLoaded(el){
	el.removeClass('owl-loading').addClass('owl-loaded').parents('.owl-row').addClass('hide-loading');
};

</script>
<script type="text/javascript">
                         /*  ap_list_functions.push(function(){ */
                           	$(".view_all_wapper").hide();
                           	var limit = 5;
                           	var level = 1 - 1;
                           	$("ul.ul-categories-image-2154629658, ul.ul-categories-image-2154629658 ul").each(function(){
                           		var element = $(this).find(">li").length;
                           		var count = 0;
                           		$(this).find(">li").each(function(){
                           			count = count + 1;
                           			if(count > limit){
                           				// $(this).remove();
                           				$(this).hide();
                           			}
                           		});
                           		if(element > limit) {
                           			view = $(".view_all","#view_all_wapper_categories-image-2154629658").clone(1);
                           			// view.appendTo($(this).find("ul.level" + level));
                           			view.appendTo($(this));
                           			var href = $(this).closest("li").find('a:first-child').attr('href');
                           			$(view).attr("href", href);
                           		}
                           	});
                        /*   }); */
                           
                        </script>
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

<?php do_action('after_page_wrapper'); ?>
<?php
wp_footer();
/*	get_footer(); */
?>