<?php 
/* 
	Template Name: New Design Home
*/
	get_header();
?>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<style>
@font-face {
    font-family: 'Beautiella';
    src: url('<?php echo esc_url( glow_dynamic_url_base('home') ); ?>/wp-content/themes/glowscentedcandles/new-home-assets/fonts/Beautiella.woff2') format('woff2'),
        url('<?php echo esc_url( glow_dynamic_url_base('home') ); ?>/wp-content/themes/glowscentedcandles/new-home-assets/fonts/Beautiella.woff') format('woff');
    font-weight: normal;
    font-style: normal;
    font-display: swap;
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
    font-family: "Beautiella" !important;
    /* font-style: normal; */
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

@media (min-width: 992px)
{
	.bestforyou .sub-title-widget 
	{
		padding-bottom: 30px !important;
	}
}
@media only screen and (max-width: 480px)
{
	.woocommerce-cart, .st-container, .st-container .st-pusher, .page-wrapper 
	{
		overflow-x: hidden !important;
		-ms-overflow-x: hidden !important;
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
	.ApProductList .addbycustom {
    display: none;
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
<link rel="stylesheet" href="<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>new-home-assets/css/theme-a0c61315.css" type="text/css" media="all"/>
<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>new-home-assets/css/owl-gallery.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
<script type="text/javascript" src="<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>new-home-assets/js/bootstrap.min.js" ></script>
<script type="text/javascript" src="<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>new-home-assets/js/owl-gallery.js" ></script>
<style>
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
@media (min-width: 992px)
{
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
<section id="wrapper-newdesgin">
<div class="row">
	<div class="rev-slider">
		<div class="content">
				<?php if(have_posts()): while(have_posts()) : the_post(); ?>
				<?php the_content(); ?>
				<?php endwhile; else: ?>
				<h3><?php esc_html_e('No pages were found!', 'royal') ?></h3>
				<?php endif; ?>
		</div>
	</div><!-- end row-fluid -->
</div>
<div class="row">
   <div id="content-wrapper" class="col-lg-12 col-xs-12">
      <div id="main">
         <div id="content" class="page-home">
           
            <div class="wrapper">
               <div class="container">
                  <div class="row margin-home margin-thome just-for-you ApRow  has-bg bg-boxed" style="background: no-repeat;" data-bg_data=" no-repeat">
                     <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-sp-12  ApColumn ">
                        <div class="has-icon icon-candle text-xs-center header-box ApHtml block-1">
                           <h4 class="title_block">Just for you</h4>
                           <div class="sub-title-widget">Welcome To Olars Candles Shop</div>
                           <div class="block_content">
                              <p>Our dreamy scents are inspired by nature and travel, moments and memories.&nbsp; We hand-pour our candles in our Olars shop using sustainable soy wax and phthalate-free perfume oils infused with essential oils.&nbsp;</p>
                           </div>
                        </div>
                        <div class="widget-category_image block ApCategoryImage category-glow-2">
                           <div class="block_content">
                              <ul class="level0  ul-categories-image-2154629658">
                                 <li class="cate_4">
                                    <a href="#">
                                       <div class="cate_content">
                                          <div class="cover-img">
                                             <img class="img-fluid" src="<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>new-home-assets/images/oc1.webp" alt="Pilars">
                                          </div>
                                          <div class="nbproducts">
                                             <div class="cate-name">Pilars</div>
                                             <div id="leo-cat-4" class="leo-qty leo-cat-4 badge-2" data-str="items" style="">3<span>items</span></div>
                                             <span class="view-all">
                                             View all
                                             </span>
                                          </div>
                                       </div>
                                    </a>
                                 </li>
                              </ul>
                              <ul class="level0  ul-categories-image-2154629658">
                                 <li class="cate_5">
                                    <a href="#">
                                       <div class="cate_content">
                                          <div class="cover-img">
                                             <img class="img-fluid" src="<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>new-home-assets/images/oc2.webp" alt="Gift Sets">
                                          </div>
                                          <div class="nbproducts">
                                             <div class="cate-name">Gift Sets</div>
                                             <div id="leo-cat-5" class="leo-qty leo-cat-5 badge-2" data-str="items" style="">6<span>items</span></div>
                                             <span class="view-all">
                                             View all
                                             </span>
                                          </div>
                                       </div>
                                    </a>
                                 </li>
                              </ul>
                              <ul class="level0  ul-categories-image-2154629658">
                                 <li class="cate_7">
                                    <a href="#">
                                       <div class="cate_content">
                                          <div class="cover-img">
                                             <img class="img-fluid" src="<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>new-home-assets/images/oc3.webp" alt="Acessories">
                                          </div>
                                          <div class="nbproducts">
                                             <div class="cate-name">Acessories</div>
                                             <div id="leo-cat-7" class="leo-qty leo-cat-7 badge-2" data-str="items" style="">6<span>items</span></div>
                                             <span class="view-all">
                                             View all
                                             </span>
                                          </div>
                                       </div>
                                    </a>
                                 </li>
                              </ul>
                              <ul class="level0  ul-categories-image-2154629658">
                                 <li class="cate_8">
                                    <a href="#">
                                       <div class="cate_content">
                                          <div class="cover-img">
                                             <img class="img-fluid" src="<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>new-home-assets/images/oc4.webp" alt="Home Accessories">
                                          </div>
                                          <div class="nbproducts">
                                             <div class="cate-name">Home Accessories</div>
                                             <div id="leo-cat-8" class="leo-qty leo-cat-8 badge-2" data-str="items" style="">9<span>items</span></div>
                                             <span class="view-all">
                                             View all
                                             </span>
                                          </div>
                                       </div>
                                    </a>
                                 </li>
                              </ul>
							   <ul class="level0  ul-categories-image-2154629658">
                                 <li class="cate_8">
                                    <a href="#">
                                       <div class="cate_content">
                                          <div class="cover-img">
                                             <img class="img-fluid" src="<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>new-home-assets/images/oc4.webp" alt="Home Accessories">
                                          </div>
                                          <div class="nbproducts">
                                             <div class="cate-name">Home Accessories</div>
                                             <div id="leo-cat-8" class="leo-qty leo-cat-8 badge-2" data-str="items" style="">9<span>items</span></div>
                                             <span class="view-all">
                                             View all
                                             </span>
                                          </div>
                                       </div>
                                    </a>
                                 </li>
                              </ul>
							   <ul class="level0  ul-categories-image-2154629658">
                                 <li class="cate_8">
                                    <a href="#">
                                       <div class="cate_content">
                                          <div class="cover-img">
                                             <img class="img-fluid" src="<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>new-home-assets/images/oc4.webp" alt="Home Accessories">
                                          </div>
                                          <div class="nbproducts">
                                             <div class="cate-name">Home Accessories</div>
                                             <div id="leo-cat-8" class="leo-qty leo-cat-8 badge-2" data-str="items" style="">9<span>items</span></div>
                                             <span class="view-all">
                                             View all
                                             </span>
                                          </div>
                                       </div>
                                    </a>
                                 </li>
                              </ul>
                              <div id="view_all_wapper_categories-image-2154629658" class="view_all_wapper" style="display: none;">
                                 <a class="btn btn-outline" href="javascript:void(0)">View all</a>
                              </div>
                           </div>
                        </div>
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
                           	})
                        /*   }); */
                           
                        </script>
                     </div>
                  </div>
               </div>
            </div>
            <div class="wrapper" style="background: #f7f0ed no-repeat">
               <div class="container">
                  <div class="row margin-home margin-thome ApRow  has-bg bg-fullwidth-container bestforyou" style="">
                     <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-sp-12  ApColumn ">
                        <div class="block has-icon icon-jar text-xs-center ApProductList block-2">
                           <h4 class="title_block">
                              Best for you
                           </h4>
                           <div class="sub-title-widget">Recommended For You </div>
						   <?php echo do_shortcode('[products limit="6" columns="3" visibility="visible" category="melts" cat_operator="AND" ]'); ?>
                          
                           <div class="box-show-more open">
                              <a href="javascript:void(0)" class="btn btn-default btn-show-more" data-use-animation="0" data-page="2" data-loading-text="Loading...">
                              <span>Show more</span></a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="row flex-w ApRow  has-bg bg-boxed" style="background: no-repeat;" data-bg_data=" no-repeat">
               <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 col-sp-12 no-padding ApColumn ">
                  <div id="image-form_17693274366222682" class="block ApImage">
                     <img src="<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>new-home-assets/images/block-left.webp" class="" title="" alt="" style=" width:100%; 
                        height:auto">
                  </div>
               </div>
               <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 col-sp-12 no-padding ApColumn ">
                  <div id="image-form_13733885599081795" class="block text-box-fourth text-xs-center  ApImage">
                     <img src="<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>new-home-assets/images/block-right-small-logo.avif" class="" title="" alt="" style=" width:auto; 
                        height:auto">
                     <div class="image_description">
                        <div class="flag">Best only £19.99&nbsp;</div>
                        <div class="title-2">Sale Up 30% Off<br> Minimalist Collection</div>
                        <p>The candle that talks back also gives back.</p>
                        <p><a href="#" data-mce-href="#">Shop Now<i class="fal fa-long-arrow-right"></i></a></p>
                     </div>
                  </div>
               </div>
            </div>
            <div class="row flex-w ApRow  has-bg bg-boxed" style="background: no-repeat;" data-bg_data=" no-repeat">
               <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 col-sp-12 no-padding ApColumn ">
                  <div id="image-form_5974431382296516" class="block text-box-fourth text-xs-center  ApImage">
                     <img src="<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>new-home-assets/images/block-left-small-logo.avif" class="" title="" alt="" style=" width:auto; 
                        height:auto">
                     <div class="image_description">
                        <div class="flag">Best only £59.95</div>
                        <div class="title-2">Light Me Up...<br> Huge Candle Sale!&nbsp;</div>
                        <p>30% Off everything - Today only!</p>
                        <p><a href="#" data-mce-href="#">Shop Now<i class="fal fa-long-arrow-right"></i></a></p>
                     </div>
                  </div>
               </div>
               <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 col-sp-12 no-padding ApColumn ">
                  <div id="image-form_6118828705127580" class="block ApImage">
                     <img src="<?php echo esc_url( glow_dynamic_url_base('theme') ); ?>new-home-assets/images/block-left.webp" class="" title="" alt="" style=" width:100%; 
                        height:auto">
                  </div>
               </div>
            </div>
            
            <div class="wrapper" style="background: #f7f0ed no-repeat">
               <div class="container">
                  <div class="row has-icon icon-letter letter-box text-xs-center ApRow  has-bg bg-fullwidth-container" style="">
                     <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-sp-12  ApColumn ">
                        <div id="blockEmailSubscription_displayHome" class="block_newsletter block">
                           <h3 class="title_block" id="block-newsletter-label">Subcribe</h3>
                           <p>Get on the list and get 10% off your first order!</p>
                           <div class="block_content">
                              <form action="#" method="post">
                                 <div class="row">
                                    <div class="col-xs-12 input-box">
                                       <button class="btn btn-outline float-xs-right" name="submitNewsletter" type="submit" value="Subscribe">
                                       <span>Subscribe</span><i class="fal fa-paper-plane"></i>
                                       </button>
                                       <div class="input-wrapper">
                                          <input name="email" required="" type="email" value="" placeholder="Your email address" aria-labelledby="block-newsletter-label">
                                       </div>
                                       <input type="hidden" name="blockHookName" value="displayHome"> <input type="hidden" name="action" value="0">
                                       <div class="clearfix"></div>
                                    </div>
                                    <div class="col-xs-12"></div>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
       
            <div class="gallery-section">
               <div class="container">
                  <div class="row margin-thome has-icon icon-candle-taller text-xs-center gallery-box ApRow  has-bg bg-boxed" style="background: no-repeat;" data-bg_data=" no-repeat">
                     <h4 class="title_block title-ap-group">New collection </h4>
                     <div class="sub-title-widget sub-title-ap-group">The Gallery Of Everything </div>
                     <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 col-sp-12  ApColumn ">
                        <div id="image-form_8061757028094676" class="block ApImage">
						<a href="#">
                           <div><img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2022/09/nice_metalic.png" class="" title="" alt="" /></div>
							  </a>
                        </div>
                        <div id="image-form_5497523802487310" class="block ApImage">
                          <a href="#">
                          <div> <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2022/09/Season-to-Sparkle_metalic.png" class="" title="" alt="" /></div>
							  </a>
                        </div>
                     </div>
                     <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 col-sp-12  ApColumn ">
                        <div id="image-form_26155117847947185" class="block ApImage">
                           <a href="#">
                          <div> <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2022/09/Season-to-Sparkle_metalic.png" class="" title="" alt="" style=""/></div>
							  </a>
                        </div>
                        <div id="image-form_38417785383501585" class="block ApImage">
						<a href="#" >
                          <div> <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2022/09/Season-to-Sparkle_metalic.png" class="" title="" alt="" style=" "/></div>
							  </a>
                         
                        </div>
                     </div>
                     <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 col-sp-12  ApColumn ">
                        <div id="image-form_4898561287828256" class="block ApImage">
                          <a href="#">
                         <div>  <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2022/09/nice_metalic.png" class="" title="" alt="" style=" "/></div>
							  </a>
                        </div>
                        <div id="image-form_9228800348979924" class="block ApImage">
                           <a href="#">
                           <div><img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2022/09/Season-to-Sparkle_metalic.png" class="" title="" alt="" style=" "/></div>
							  </a>
                        </div>
                     </div>
                     <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 col-sp-12  ApColumn ">
                        <div id="image-form_38878241996381105" class="block ApImage">
                           <a href="#">
                           <div><img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2022/09/ginger_metalic.png" class="" title="" alt="" style=" "/></div>
							  </a>
                        </div>
                        <div id="image-form_19127469386647602" class="block ApImage">
                           <a href="#">
                          <div> <img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2022/09/ginger_metalic.png" class="" title="" alt="" style=" "/></div>
							  </a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="client-logo">
               <div class="container">
                  <div class="row margin-home margin-thome ApRow  has-bg bg-boxed" style="background: no-repeat;" data-bg_data=" no-repeat">
                     <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-sp-12  ApColumn ">
                        <div class="block manufacturers_block exclusive appagebuilder ApManuFacturersCarousel">
                           <div class="block_content">
                              <div class="owl-row hide-loading">
                                 <div class="timeline-wrapper clearfix owl-manu" data-item="5" data-xl="5" data-lg="4" data-md="4" data-sm="3" data-m="2">
                                    <div class="timeline-parent" style="width: 20%; float: left;">
                                       <div class="timeline-item">
                                          <div class="animated-background"></div>
                                       </div>
                                    </div>
                                    <div class="timeline-parent" style="width: 20%; float: left;">
                                       <div class="timeline-item">
                                          <div class="animated-background"></div>
                                       </div>
                                    </div>
                                    <div class="timeline-parent" style="width: 20%; float: left;">
                                       <div class="timeline-item">
                                          <div class="animated-background"></div>
                                       </div>
                                    </div>
                                    <div class="timeline-parent" style="width: 20%; float: left;">
                                       <div class="timeline-item">
                                          <div class="animated-background"></div>
                                       </div>
                                    </div>
                                    <div class="timeline-parent" style="width: 20%; float: left;">
                                       <div class="timeline-item">
                                          <div class="animated-background"></div>
                                       </div>
                                    </div>
                                 </div>
                                 <div id="carousel-1895144326" class="owl-carousel owl-theme owl-loading">
                                   
                                             <div class="item  first">
                                                <div class="manufacturer-container manufacturer-block" itemscope="" itemtype="https://schema.org/Brand">
                                                   <div class="left-block">
                                                      <div class="manufacturer-image-container image">
                                                         <a title="alisa" href="#" itemprop="url">
                                                         <img class="img-fluid" src="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>/wp-content/themes/glowscentedcandles/new-home-assets/images/client/1-manu_default.jpg"alt="alisa" itemprop="image">
                                                         </a>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                         
                                             <div class="item ">
                                                <div class="manufacturer-container manufacturer-block" itemscope="" itemtype="https://schema.org/Brand">
                                                   <div class="left-block">
                                                      <div class="manufacturer-image-container image">
                                                         <a title="Graphic Corner" href="#" itemprop="url">
                                                         <img class="img-fluid" src="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>/wp-content/themes/glowscentedcandles/new-home-assets/images/client/2-manu_default.jpg" alt="Graphic Corner" itemprop="image">
                                                         </a>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                         
                                       
                                             <div class="item ">
                                                <div class="manufacturer-container manufacturer-block" itemscope="" itemtype="https://schema.org/Brand">
                                                   <div class="left-block">
                                                      <div class="manufacturer-image-container image">
                                                         <a title="Handmade" href="#" itemprop="url">
                                                         <img class="img-fluid" src="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>/wp-content/themes/glowscentedcandles/new-home-assets/images/client/3-manu_default.jpg" alt="Handmade" itemprop="image">
                                                         </a>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                        
                                         
                                             <div class="item ">
                                                <div class="manufacturer-container manufacturer-block" itemscope="" itemtype="https://schema.org/Brand">
                                                   <div class="left-block">
                                                      <div class="manufacturer-image-container image">
                                                         <a title="Jewerly" href="#" itemprop="url">
                                                         <img class="img-fluid" src="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>/wp-content/themes/glowscentedcandles/new-home-assets/images/client/4-manu_default.jpg" alt="Jewerly" itemprop="image">
                                                         </a>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                         
                                          
                                             <div class="item ">
                                                <div class="manufacturer-container manufacturer-block" itemscope="" itemtype="https://schema.org/Brand">
                                                   <div class="left-block">
                                                      <div class="manufacturer-image-container image">
                                                         <a title="reverland" href="#" itemprop="url">
                                                         <img class="img-fluid" src="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>/wp-content/themes/glowscentedcandles/new-home-assets/images/client/5-manu_default.jpg" alt="reverland" itemprop="image">
                                                         </a>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                         
                                        
                                             <div class="item ">
                                                <div class="manufacturer-container manufacturer-block" itemscope="" itemtype="https://schema.org/Brand">
                                                   <div class="left-block">
                                                      <div class="manufacturer-image-container image">
                                                         <a title="Studio Design" href="#" itemprop="url">
                                                         <img class="img-fluid" src="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>/wp-content/themes/glowscentedcandles/new-home-assets/images/client/6-manu_default.jpg" alt="Studio Design" itemprop="image">
                                                         </a>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                     
                                 </div>
<link rel="stylesheet" href="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.theme.default.min.css">
<script src="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/owl.carousel.js"></script>								  
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
                              </div>
                           
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div id="form_6295006304980342" class="row ApRow  has-bg bg-fullwidth" style="background:url('https://cdn.shopify.com/s/files/1/0905/2012/files/obg-3.jpg?v=1626859196'); background-size: cover;background-position: center center;" data-src="https://cdn.shopify.com/s/files/1/0905/2012/files/obg-3.jpg?v=1626859196" style="" data-bg_data=" no-repeat center">
               <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-sp-12  ApColumn ">
                  <div class="block has-icon icon-gift text-xs-center footer-text-box ApHtml">
                     <h4 class="title_block"> Gift sets</h4>
                   <div class="choose_sec"><div class="sub-title-widget">Choose The Perfect <br/>Gifts For Your Friends</div></div>
                     <div class="block_content">
                        <p><a href="#" data-mce-href="#">Discovery Now<i class="fal fa-long-arrow-right"></i></a></p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
       
      </div>
   </div>
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
            <div class="row  ApRow  has-bg bg-boxed" style="background: no-repeat;" data-bg_data=" no-repeat">
               <div class="col-xl-4 col-lg-3 col-md-12 col-sm-12 col-xs-12 col-sp-12 first-wrap ApColumn ">
                  <div class="footer_logo "> <a href="#" title="Leo Olars">  <img class="logo img-fluid" src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2022/09/logo-d-1.png" alt="Leo Olars"> </a></div>
                  <div class="block address_w ApHtml">
                     <div class="block_content">
                        <p>Nunc mi ipsum faucibus vitae aliquet nec&nbsp; ullamcorper sit amet. Accumsan in nisl nisi&nbsp; scelerisque eu ultrices vitae.</p>
                     </div>
                  </div>
               </div>
               <div class="col-xl-4 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-sp-12 second-w ApColumn ">
                  <div class="block ApLink ApBlockLink">
                     <h4 class="title_block">
                        Information
                     </h4>
                     <ul>
                        <li><a href="#" target="_self">Custom Service </a></li>
                        <li><a href=" #" target="_self">FAQs</a></li>
                        <li><a href="#" target="_self">Ordering Tracking</a></li>
                        <li><a href="#" target="_self"> Contacts</a></li>
                        <li><a href="#" target="_self"> Events</a></li>
                     </ul>
                  </div>
               </div>
               <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 col-sp-12 third-wrap ApColumn ">
                  <div class="block ApLink ApBlockLink">
                     <h4 class="title_block">
                        My Account
                     </h4>
                     <ul>
                        <li><a href="#" target="_self">Delivery Infomation </a></li>
                        <li><a href="#" target="_self">Privacy Policy</a></li>
                        <li><a href="#" target="_self"> Discount </a></li>
                        <li><a href="#" target="_self">Custom </a></li>
                        <li><a href="#" target="_self">Service Term</a></li>
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
            <div class="row ApRow  " style="">
               <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-sp-12  ApColumn ">
                  <div class="block copyrigh ApRawHtml">
                     Copyright © 2021 Olars. All Rights Reserved 
                  </div>
               </div>
               <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-sp-12  ApColumn ">
                  <div class="block contact-w ApHtml">
                     <div class="block_content">
                        <div><i class="fal fa-map-marker"></i>&nbsp;9200 E Iowa Ave, Denver, USA</div>
                        <div><i class="fal fa-headphones"></i><a href="tel:01123567999" data-mce-href="tel:01123567999">&nbsp;(+01) 123 567 999</a></div>
                        <div><i class="fal fa-paper-plane"></i>&nbsp;demo@demo.com</div>
                     </div>
                  </div>
               </div>
            </div>
            <section class="product-notification leo-smart position4" data-time="1000" data-interval="2000">
               <a href="javascript:void(0);" title="Close" class="close-notifi">×</a>
               <div class="product-suggest">
                  <label class="time-ago">Puchare By CBerthelier  2 minutes ago</label>
                  <div class="product-suggest-content">  
                     <div class="column-right">
                        <a class="product-name" href="#">Hummingbird printed sweater</a>
                        <p class="leo-phone">xxx</p>
                        <p class="leo-address">131 cours emile zola  villeurbanne </p>
                     </div>
                  </div>
               </div>
            </section>
         </div>
      </div>
   </div>
   <svg width="0" height="0" class="hidden">
      <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="bag">
         <path d="M15.12 19.372a.752.752 0 01-1.206.413.75.75 0 01-.234-.825l1.2-4.208a.75.75 0 111.44.413l-1.2 4.207zm-5.31.518a.75.75 0 01-.93-.518l-1.2-4.207a.75.75 0 111.44-.412l1.2 4.207a.75.75 0 01-.51.93z" fill="#347758"></path>
         <path d="M20.947 13.41L19.5 20.415a2.723 2.723 0 01-2.655 2.168h-9.9a2.715 2.715 0 01-2.656-2.168l-1.44-7.02a2.252 2.252 0 11.21-4.5h4.358v-3a4.5 4.5 0 019 0v3h4.5a2.25 2.25 0 010 4.5l.03.015zM5.774 20.115a1.223 1.223 0 001.193.968h9.9a1.215 1.215 0 001.185-.968l1.365-6.697h-15l1.357 6.697zm9.143-14.197a3 3 0 00-6 0v3h6v-3zm6 4.5H3.082a.75.75 0 100 1.5h17.835a.75.75 0 100-1.5z"></path>
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="search-2">
         <path d="M12 2.4a7.68 7.68 0 017.68 7.68 7.612 7.612 0 01-1.997 5.165l-5.319 6.187a.48.48 0 01-.73 0l-5.318-6.187A7.613 7.613 0 014.32 10.08 7.68 7.68 0 0112 2.4zM7.046 14.62L12 20.387l4.953-5.76a6.719 6.719 0 10-9.907 0v-.005z" fill="#347758"></path>
         <path d="M12 6.72a3.36 3.36 0 110 6.72 3.36 3.36 0 010-6.72zm0 5.76a2.4 2.4 0 100-4.8 2.4 2.4 0 000 4.8z" fill="#347758"></path>
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="search">
         <path d="M5.81 5.435c3.107-2.995 7.731-2.875 10.607 0a7.5 7.5 0 01.504 10.05l4.269 4.27-1.06 1.06-4.27-4.269a7.5 7.5 0 01-10.05-.504c-2.875-2.876-3.01-7.704 0-10.607zm9.546 1.06a6 6 0 10-8.485 8.486 6 6 0 008.485-8.485z"></path>
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="user">
         <path d="M14.955 19.5a.75.75 0 100-1.5.75.75 0 000 1.5zm-2.91 0a.75.75 0 100-1.5.75.75 0 000 1.5zm-3 0a.75.75 0 100-1.5.75.75 0 000 1.5z" fill="#347758"></path>
         <path d="M21.442 23.25H2.558A1.807 1.807 0 01.75 21.442a7.2 7.2 0 017.192-7.192h8.116a7.2 7.2 0 017.192 7.192 1.807 1.807 0 01-1.808 1.808zm-13.5-7.5a5.7 5.7 0 00-5.692 5.692.307.307 0 00.308.308h18.884a.307.307 0 00.308-.308 5.7 5.7 0 00-5.692-5.692H7.942zm4.058-3a6 6 0 110-12 6 6 0 010 12zm0-10.5a4.5 4.5 0 100 9 4.5 4.5 0 000-9z"></path>
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="wishlist">
         <path d="M7.215 1.763A6.825 6.825 0 0112 3.3a6.93 6.93 0 019.578.81 7.05 7.05 0 01-.458 9.607l-7.695 7.688a2.317 2.317 0 01-1.545.855 1.124 1.124 0 01-.81-.36l-8.182-8.182a7.042 7.042 0 01-.465-9.608 6.855 6.855 0 014.792-2.347zM3.945 12.66L12 20.685a2.25 2.25 0 00.398-.337l7.657-7.688a5.558 5.558 0 00.398-7.56 5.407 5.407 0 00-3.75-1.845h-.338a5.37 5.37 0 00-3.615 1.373 1.132 1.132 0 01-1.5 0 5.347 5.347 0 00-3.937-1.373A5.408 5.408 0 003.563 5.1a5.557 5.557 0 00.382 7.56z"></path>
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 22" id="green-cart">
         <path d="M15.763 22H5.238c-1.702 0-3.125-1.202-3.313-2.796L.505 7.158a.754.754 0 01.208-.61.854.854 0 01.62-.262H5.5V4.714C5.5 2.114 7.743 0 10.5 0c2.758 0 5 2.114 5 4.714v1.572h4.167c.237 0 .463.095.62.261a.758.758 0 01.208.61l-1.42 12.047C18.888 20.798 17.464 22 15.764 22zm-1.93-17.286c0-1.733-1.495-3.143-3.333-3.143s-3.333 1.41-3.333 3.143v1.572h6.666V4.714zM15.5 7.857V9.43c.46 0 .833.352.833.785 0 .434-.373.786-.833.786h-1.667c-.46 0-.833-.352-.833-.786 0-.433.373-.785.833-.785V7.857H7.167V9.43c.46 0 .833.352.833.785 0 .434-.373.786-.833.786H5.5c-.46 0-.833-.352-.833-.786 0-.433.373-.785.833-.785V7.857H2.264l1.318 11.174c.093.797.806 1.398 1.656 1.398h10.525c.85 0 1.562-.601 1.656-1.398l1.317-11.174H15.5z"></path>
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 22" id="grey-cart">
         <path d="M15.263 22H4.738c-1.702 0-3.125-1.202-3.313-2.796L.005 7.158a.754.754 0 01.208-.61.854.854 0 01.62-.262H5V4.714C5 2.114 7.243 0 10 0c2.758 0 5 2.114 5 4.714v1.572h4.167c.237 0 .463.095.62.261a.758.758 0 01.208.61l-1.42 12.047C18.388 20.798 16.964 22 15.264 22zm-1.93-17.286c0-1.733-1.495-3.143-3.333-3.143s-3.333 1.41-3.333 3.143v1.572h6.666V4.714zM15 7.857V9.43c.46 0 .833.352.833.785 0 .434-.373.786-.833.786h-1.667c-.46 0-.833-.352-.833-.786 0-.433.373-.785.833-.785V7.857H6.667V9.43c.46 0 .833.352.833.785 0 .434-.373.786-.833.786H5c-.46 0-.833-.352-.833-.786 0-.433.373-.785.833-.785V7.857H1.764l1.318 11.174c.093.797.806 1.398 1.656 1.398h10.525c.85 0 1.562-.601 1.656-1.398l1.317-11.174H15z"></path>
      </symbol>
   </svg>
</footer>
<style>
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
    font-family: "Beautiella" !important;
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
@media only screen and (max-width: 640px){
#newhomw div#slide-47-layer-16, #newhomw div#slide-56-layer-1
{
	font-family: "Beautiella" !important;
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
    font-family: "Beautiella" !important;
    font-size: 19px !important;
    line-height: 29px !important;
}
.choose_sec .sub-title-widget {
    font-size: 19px;
    line-height: 25px;
}
.title_block {
    font-family: "Beautiella";
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
</style>



<?php
wp_footer();
/*	get_footer(); */
?>