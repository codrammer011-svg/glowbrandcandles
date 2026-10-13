<?php 
/* 
	Template Name: New Home Template
*/
	get_header();
?>
<script type="text/javascript">
 
	ap_list_functions.push(function(){
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
	});

</script>
<link rel="stylesheet" href="https://devglow.wpengine.com/wp-content/themes/glowscentedcandles/css/theme-a0c61315.css" type="text/css" media="all"/>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
/*Custom Css*/
body {
    direction: ltr;
    font-family: "Jost",sans-serif;
    font-size: .875rem;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    color: #999;
    line-height: 1.25em;
    background: #fff;
}
i.fa.fa-long-arrow-right {
    font-size: 14px;
}
.text-box-fourth a i {
    margin-left: 8px;
}
h1, h2, h3, h4, h5, h6
{
	    text-transform: initial;
}
@font-face {
    font-family: 'Beautiella';
    src: url('https://devglow.wpengine.com/wp-content/themes/glowscentedcandles/fonts/Beautiella/Beautiella.woff2') format('woff2'),
        url('https://devglow.wpengine.com/wp-content/themes/glowscentedcandles/fonts/Beautiella/Beautiella.woff') format('woff');
    font-weight: normal;
    font-style: normal;
    font-display: swap;
}
#glow_slider div#slide-46-layer-9, #glow_slider div#slide-49-layer-9 {
    font-family: "Beautiella" !important;
    font-size: 50px !important;
}

.block.has-icon.icon-candle.text-xs-center.header-box.ApHtml {
    text-align: center;
}
.col-xs-12 {
    float: left;
    width: 100%;
}
p {
    font-size: 16px;
    color: #999;
    font-weight: 400;
    line-height: 24px;
}
.title_block {
    font-family: "Beautiella";
    font-style: normal;
    font-weight: 400;
    font-size: 46px;
    line-height: 1;
    color: #b26b53;
    display: block;
    padding: 0;
    margin-bottom: 20px;
}
.has-icon .title_block {
    position: relative;
    padding: 28px 0 0;
    margin: 0 auto;
    z-index: 2;
}
.has-icon.icon-candle .title_block {
    display: inline-block;
}
.has-icon .title_block:before {
    content: '';
    position: absolute;
    top: 0;
    left: auto;
    right: auto;
    z-index: -1;
    display: block;
}
.has-icon.icon-candle .title_block {
    display: inline-block;
}
.has-icon.icon-candle .title_block:before {
    background: url("https://devglow.wpengine.com/wp-content/uploads/2022/02/otitle-p1-1.png") center center no-repeat;
    width: 112px;
    height: 101px;
    margin: auto;
    left: 0;
    right: 0;
    text-align: center;
}
.sub-title-widget {
    font-size: 40px;
    line-height: 48px;
    color: #212121;
    margin-top: 10px;
    margin-bottom: 50px;
}
.header-box .sub-title-widget {
    margin-bottom: 25px;
}
.header-box p {
    font-size: 16px;
    margin-bottom: 50px;
    color: #797979;
}
.ApCategoryImage .block_content {
    display: grid;
    gap: 30px;
}
ul {
    list-style: none;
    padding: 0;
}
ol, ul, dl {
    margin-top: 0;
    margin-bottom: 1rem;
}
.ApCategoryImage a {
    display: block;
    overflow: hidden;
}
.img-fluid, .carousel-inner>.carousel-item>img, .carousel-inner>.carousel-item>a>img {
    max-width: 100%;
    height: auto;
}
.ApCategoryImage .block_content img {
    width: 100%;
    object-fit: cover;
}
.ApCategoryImage .nbproducts {
    position: relative;
    display: block;
}
.ApCategoryImage .cate-name {
    font-size: 20px;
    line-height: 24px;
    color: #212121;
    text-transform: capitalize;
}
.ApCategoryImage .cate-name {
    margin-top: 25px;
    display: inline-block;
}
.ApCategoryImage .leo-qty {
    font-size: 16px;
    line-height: 24px;
    color: #797979;
    -webkit-transition: all 0.4s;
    -moz-transition: all 0.4s;
    -ms-transition: all 0.4s;
    -o-transition: all 0.4s;
    transition: all 0.4s;
}
.ApCategoryImage .view-all, .ApCategoryImage .leo-qty {
    float: right;
    margin-top: 25px;
}
.ApCategoryImage .leo-qty {
    position: absolute;
    right: 0;
    top: 0;
}
.ApCategoryImage .leo-qty span {
    padding-left: 5px;
}
.ApCategoryImage .view-all {
    font-size: 18px;
    line-height: 24px;
    color: #212121;
    opacity: 0;
    -webkit-transform: translateX(-100%);
    -moz-transform: translateX(-100%);
    -ms-transform: translateX(-100%);
    -o-transform: translateX(-100%);
    transform: translateX(-100%);
    -webkit-transition: all 0.4s;
    -moz-transition: all 0.4s;
    -ms-transition: all 0.4s;
    -o-transition: all 0.4s;
    transition: all 0.4s;
}
.ApCategoryImage .view-all, .ApCategoryImage .leo-qty {
    float: right;
    margin-top: 25px;
}
.ApCategoryImage .view-all:after {
    font-family: "";
    font-weight: 300;
    font-size: inherit;
    content: "";
    padding-left: 5px;
}
.content ul, .content ol {
    padding-left: 0px;
}
/* Sale banner css*/
.flag {
    position: relative;
    background: #539ab2;
    display: inline-block;
    color: #fff;
    font-size: 16px;
    text-transform: uppercase;
    height: 34px;
    line-height: 34px;
    padding: 0 40px;
    margin-top: 30px;
    margin-bottom: 50px;
}
.flag:before, .flag:after {
    background: #fff;
    border-radius: 10px;
    box-shadow: inset 0 1px rgb(0 0 0 / 25%);
    content: '';
    height: 6px;
    left: 10px;
    position: absolute;
    width: 6px;
    top: 50%;
    transform: translateY(-50%);
}
.flag:after {
    right: 10px;
    left: auto;
}
.text-box-fourth .title {
    font-size: 40px;
    line-height: 48px;
    color: #212121;
	padding:0;
	    border: 0;
}
.text-box-fourth p {
    font-size: 16px;
    line-height: 24px;
    color: #797979;
    margin-top: 20px;
    margin-bottom: 0;
}
.text-box-fourth a {
    font-size: 16px;
    font-weight: 400;
    line-height: 24px;
    color: #212121;
    padding: 14px 30px;
    height: auto;
    display: inline-block;
    margin-top: 20px;
    transition: all 0.4s ease;
    text-transform: capitalize;
    background: transparent;
    border-radius: 0;
}
.text-box-fourth a {
    margin-top: 0;
}
@media (min-width: 1200px)
{
		.row {
		margin-right: -15px;
		margin-left: -15px;
	}
	.container {
		width: 1200px;
		max-width: 100%;
	}
	.col-xl-12 {
		float: left;
		width: 100%;
	}

	.ApCategoryImage .block_content {
		grid-template-columns: repeat(4,1fr);
	}
}
@media (min-width: 992px)
{
	.margin-thome {
		padding-top: 20px;
	}
	.col-lg-12 {
		float: left;
		width: 100%;
	}
	.margin-home .block {
		margin-bottom: 0;
	}
	 .has-pad img{
        width:auto!important
    }
    .flex-w{
        display:flex;
        align-items:center
    }
    .testimonial{
        margin:0 80px
    }
    .testimonial .descript{
        margin:0 160px
    }
}
.text-box-second .item{
    display:flex;
    align-items:center;
    margin-bottom:35px
}
.text-box-second .icon{
    margin-right:30px
}
.text-box-second p{
    margin-bottom:0;
    font-size:16px;
    line-height:26px;
    color:#797979
}
.text-box-second .title{
    font-size:20px;
    line-height:40px;
    color:#212121
}
.text-box-second .block_content{
    margin-top:30px
}
.testimonial .block-carousel-image-container.image{
    display:flex;
    flex-direction:column-reverse
}
.testimonial .descript{
    font-size:20px;
    font-style:italic;
    line-height:30px;
    color:#212121
}
.testimonial .title{
    font-size:16px;
    line-height:24px;
    color:#797979;
    margin:20px 0 0
}
}
@media (min-width: 768px)
{
	.col-md-12 {
		float: left;
		width: 100%;
	}
	.ApCategoryImage .block_content ul:nth-child(2n) {
		margin-top: 60px;
	}
}
@media (min-width: 576px)
{
	.col-sm-12 {
		float: left;
		width: 100%;
	}
}
</style>
<style>
#testimonial_sec 
{
  display:none;
}
.page-template-template-newhome #glow_slider div#slide-46-layer-9, .page-template-template-newhome #glow_slider div#slide-49-layer-9 {
    font-family: "Beautiella" !important;
    font-size: 50px !important;
}
.page-template-template-newhome .main-footer, .page-template-template-newhome .copyright  {
    display: none;
}
#col-subscribe .elementor-container.elementor-column-gap-no
{
  bottom: -90px;
}
#product-section li {
    float: left;
}
.page-template-template-newhome #glow_slider div#slide-46-layer-4 {
    text-align: center !important;
}
#product-categores h2.eael-elements-flip-box-heading {
    margin-top: 15px;
}
#product-categores i.ea-flipbox-icon.hm.hm-arrow-right1 {
    padding-left: 6px;
}
#bottom-banner-gifts .bottom-left-icon .fadeInLeft
{
  left: 23px;
}
 #bottom-banner-gifts .bottom-right-icon .fadeInRight
{
      right: 20px;
}
#glow-footer-section .elementor-social-icon:hover {
    background-color: transparent;
}
#glow-footer-section .elementor-icon i, #glow-footer-section .elementor-icon svg {
    width: 11px;
    height: 11px;
    position: relative;
    display: block;
    margin-right: 22px;
    font-size: 34px;
}
#premium-woo-quick-view-modal .product_meta 
{
margin: 0px 0 25px;
}
 #premium-woo-quick-view-modal h1.product_title.entry-title
{
  font-size:20px;
}
 #premium-woo-quick-view-modal .short-description p {
    margin-bottom: 6px;
}
 #premium-woo-quick-view-modal button.single_add_to_cart_button.etheme-simple-product.button.alt {
    padding: 12px 30px;
}
#product-categores .elementor-container.elementor-column-gap-narrow {
    max-width: 1320px;
}

.elementor-25230 .elementor-element.elementor-element-59f7bff > .elementor-element-populated,
.elementor-25230 .elementor-element.elementor-element-d6b4778 > .elementor-element-populated {
    transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
    padding: 10% 20% 10% 20%;
}

  h4.bdt-ps-sub-title {
    transform: matrix3d(0, -1, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1) !important;
    transform-origin: 41% 12% 0px !important;
}
.bdt-prime-slider-skin-general .bdt-slider-excerpt {
  max-width: 100%;
}
.bdt-prime-slider-skin-general .bdt-slider-excerpt p
{
  color:#fff !important;
}
.page-template-template-newhome footer .elementor-social-icon {
    background-color: unset !important;
}
.elementor-25230 .elementor-element.elementor-element-d7b83dc {
    text-align: center;
    top: -77px;
}
.elementor-25230 .elementor-element.elementor-element-4f4eb37 .ha-image-grid__item {
   
 /*   border-width: 3px;
 border-color: #a5a5a530; */
}
@media only screen and (max-width: 1199px) {
  .elementor-25230 .elementor-element.elementor-element-7451fb5 .bdt-prime-slider .bdt-prime-slider-desc .bdt-sub-title h4 {
    padding-bottom: 0;
}
  .elementor-25230 .elementor-element.elementor-element-2f76ead {
    padding: 0% 20% 15% 20%;
}
  .elementor-25230 .elementor-element.elementor-element-d7b83dc {
    text-align: center;
    top: -125px;
}
  h4.bdt-ps-sub-title {
    transform: matrix3d(0, -1, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1) !important;
    transform-origin: 45% 12% 0px !important;
}
 .bdt-prime-slider .bdt-prime-slider-desc h4 {
   
    font-size: 28px !important;
    line-height: 34px !important;
}
  .bdt-prime-slider .bdt-prime-slider-desc .bdt-main-title .bdt-title-tag {
    font-family: "Jost", Sans-serif;
    font-size: 45px !important;
    line-height: 1.4 !important;
}
  #bottom-banner-gifts .bottom-right-icon .fadeInRight {
  
    top: -16px;
}
#product-section li
{
      float: left;
      width: 33%;
}
.premium-woocommerce .woocommerce-loop-product__title,
   .premium-woocommerce li.product .price,
  .premium-woocommerce .premium-woo-qv-btn {
    color: #FFFFFF;
    font-family: "Jost", Sans-serif;
    font-size: 17px !important;
    line-height: 27px !important;
}
}
@media (max-width: 990px){
.bdt-prime-slider .bdt-prime-slider-desc h4 {
    font-size: 19px !important;
    line-height: 29px !important;
}
  #bottom-banner-gifts #bottom-banner-center
  {
    text-align: center;
    color: #212121;
    font-family: "Jost", Sans-serif;
    font-size: 28px;
    font-weight: 400;
    text-transform: none;
    font-style: normal;
    text-decoration: none;
    line-height: 40px;
  }
  #bottom-banner-gifts #bottom-banner-center a.elementor-button-link.elementor-button.elementor-size-sm
  {
        padding: 15px 18px 15px 27px;
}
  
  .page-template-template-newhome #glow_slider div#slide-46-layer-9, .page-template-template-newhome #glow_slider div#slide-49-layer-9 {
    font-family: "Beautiella" !important;
    font-size: 25px !important;
}
  div#slide-46-layer-3 {
    font-size: 30px !important;
}
#product-categores .elementor-column.elementor-col-25, .elementor-column[data-col="25"] {
    width: 50%;
}
  .elementor-25230 .elementor-element.elementor-element-a35117c {

    font-size: 29px;

    line-height: 35px;
}
}
@media only screen and (max-width: 768px){
  .elementor-25230 .elementor-element.elementor-element-2bb3a4f7 {
    padding: 10px 0px 0px 0px;
}
  .elementor-25230 .elementor-element.elementor-element-2a95edd8 {
    margin-top: 0px;
  }
  .elementor-25230 .elementor-element.elementor-element-b16c49f
  {
        font-size: 26px;
    font-weight: 400;
    text-transform: none;
    font-style: normal;
    text-decoration: none;
    line-height: 36px;
  }
  .elementor-25230 .elementor-element.elementor-element-2f76ead {
    padding: 0 20px !important;
}
  .elementor-25230 .elementor-element.elementor-element-105edce > .elementor-widget-container {
    padding: 0px 0px 0px 0px;
}
  .elementor-25230 .elementor-element.elementor-element-105edce .ha-mc-button .ha-mc-icon-wrapper > i, .elementor-25230 .elementor-element.elementor-element-105edce .ha-mc-button .ha-mc-icon-wrapper > svg {
    margin-right: 0;
}
  .elementor-25230 .elementor-element.elementor-element-ec06462 > .elementor-element-populated
  {
    padding: 20px;
  }
  .elementor-25230 .elementor-element.elementor-element-105edce .ha-mc-button .ha-mc-icon-wrapper {
    font-size: 15px;
}
  .elementor-25230 .elementor-element.elementor-element-334298e .elementor-heading-title {
    font-size: 30px;
}
  .elementor-25230 .elementor-element.elementor-element-543c221 {

    font-size: 26px;
 
    line-height: 36px;
}
footer .elementor-mobile-align-left {
    text-align: center;
}
  footer .elementor-align-left {
    text-align: center;
}
.elementor-25230 .elementor-element.elementor-element-2f76ead {
    padding: 0% 20% 15% 20%;
}
  .elementor-25230 .elementor-element.elementor-element-3285dce6 {
    padding: 20px 30px 50px 30px;
}
.elementor-25230 .elementor-element.elementor-element-825d347 .elementor-spacer-inner,.elementor-25230 .elementor-element.elementor-element-4e03b9e .elementor-spacer-inner {
    height: 460px;
}
  bdt-prime-slider .bdt-prime-slider-desc .bdt-main-title .bdt-title-tag {
    font-family: "Jost", Sans-serif;
    font-size: 30px !important;
    line-height: 1.4 !important;
}
  .bdt-prime-slider .bdt-prime-slider-desc h4 {
    font-size: 19px !important;
    line-height: 29px !important;
}
  .premium-woocommerce .woocommerce-loop-product__title,
   .premium-woocommerce li.product .price,
  .premium-woocommerce .premium-woo-qv-btn {
    color: #FFFFFF;
    font-family: "Jost", Sans-serif;
    font-size: 15px !important;
    line-height: 25px !important;
}
#product-section li {
    float: left;
    width: 50%;
}
  .elementor-25230 .elementor-element.elementor-element-b168835 {
    padding: 40px 5px 10px 5px;
}
}
@media only screen and (max-width: 580px)
{
  .elementor-25230 .elementor-element.elementor-element-7a3eac2
  {
        line-height: 46px;
  }
  #product-section .elementor-widget-container {
    padding: 0 !important;
}
  #subscibe-section 
  {
    padding: 20px;
  }
 #product-section .elementor-widget-container {
    padding: 30px 20px 20px 20px !important;
}
  .elementor-25230 .elementor-element.elementor-element-3285dce6 {
    padding: 20px 20px 10px 20px !important;
}
  .bdt-prime-slider-skin-general .bdt-prime-slider-desc .bdt-main-title {
    width: 100%;
}
  .products-grid .product {
    margin-bottom: 20px !important;
}
  .elementor-25230 .elementor-element.elementor-element-2a95edd8 {
   
    padding: 0px 30px 0px 30px;
}
  .elementor-25230 .elementor-element.elementor-element-4f4eb37 .ha-image-grid__item {

    height: 175px;
  }
  .ha-mailchimp-wrapper .ha-mailchimp-form .ha-mc-input input
  {
        min-height: 35px;
  }
  .elementor-25230 .elementor-element.elementor-element-105edce .ha-mc-button {
    padding: 0px 0px 4px 0px;
}
  .elementor-25230 .elementor-element.elementor-element-334298e {
    text-align: center;
    margin-bottom: 10px;
}
  .elementor-25230 .elementor-element.elementor-element-543c221
  {
    margin-bottom: 10px;
  }
  .elementor-25230 .elementor-element.elementor-element-543c221 {
    font-size: 20px;
    line-height: 30px;
}
  .elementor-25230 .elementor-element.elementor-element-3285dce6 {
    padding: 25px 20px 0px 20px !important;
}
  .elementor-25230 .elementor-element.elementor-element-247ab32 {
    padding: 0px 0px 50px 0px;
}
  .elementor-25230 .elementor-element.elementor-element-cdf1c2b .elementor-heading-title
  {
    margin:0;
  }
  .elementor-25230 .elementor-element.elementor-element-15118df {

    padding: 40px 0px 0px 0px;
}
  .elementor-25230 .elementor-element.elementor-element-b3e9559 .elementor-heading-title {
    font-size: 30px;
    line-height: 95px;
}
  #recm_you .elementor-widget-container {
    padding: 5px 0px 20px!important;
}
  .elementor-element.elementor-element-b3e9559.elementor-widget.elementor-widget-heading {
    margin: 0;
}
  #product-section .elementor-widget-container {
    padding: 30px 20px 0px 20px !important;
}
  #recm_you .elementor-widget-container {
    padding: 0px 0px 0px!important;
}
  .elementor-25230 .elementor-element.elementor-element-7a3eac2
  {
    font-size: 25px;
        line-height: 30px;
    margin:0;
  }
  .elementor-25230 .elementor-element.elementor-element-643ea1e8 .ha-logo-grid-item {
    height: 70px;
}
  .elementor-25230 .elementor-element.elementor-element-32351ae1 > .elementor-element-populated {
    margin: 0px 0px 0px 0px;
  }
}
@media only screen and (max-width: 480px){
  .bdt-prime-slider .bdt-prime-slider-desc .bdt-main-title .bdt-title-tag {
    font-family: "Jost", Sans-serif;
    font-size: 35px !important;
    line-height: 1.4 !important;
}
  h4.bdt-ps-sub-title {
    transform: matrix3d(0, -1, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1) !important;
    transform-origin: 41% 12% 0px !important;
}
   #cat-col2 .elementor-element-populated,
   #cat-col3 .elementor-element-populated,
  #product-categores .elementor-element-populated
  {
        margin: 23px 0px 0px 0px !important;
  }
  #product-categores .elementor-column.elementor-col-25, .elementor-column[data-col="25"] {
    width: 100%;
}
#product-section li {
    float: left;
    width: 50%;
}
}
</style>
<!--<link rel="stylesheet" href="https://devglow.wpengine.com/wp-content/themes/glowscentedcandles/css/theme-design.css" type="text/css" media="all"> -->
<?php 

	$l = et_page_config();

?>

<?php do_action( 'et_page_heading' ); ?>

	<div class="container content-page">
		<div class="page-content">
			<div class="row">

				<div class="content">
					<?php if(have_posts()): while(have_posts()) : the_post(); ?>
						
						<?php the_content(); ?>

						
						

					<?php endwhile; else: ?>

						<h3><?php esc_html_e('No pages were found!', 'royal') ?></h3>

					<?php endif; ?>

				</div>

				

			</div><!-- end row-fluid -->

		</div>
	</div><!-- end container -->
<script type="text/javascript" src="https://devglow.wpengine.com/wp-content/themes/glowscentedcandles/js/bottom-ed2d4e14.js" ></script>
<?php
	get_footer();
?>
