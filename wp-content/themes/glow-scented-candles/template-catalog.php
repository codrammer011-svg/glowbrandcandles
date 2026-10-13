<?php
/**
 * Template Name: New Catalogue Design
 */
 get_header();
?>
<ul id="menu">
			<li data-menuanchor="hexagon" class="active"><a href="#hexagon">Hexagon</a></li>
			<li data-menuanchor="crystal"><a href="#crystal">Crystal</a></li>
			<li data-menuanchor="metallic"><a href="#metallic">Metallic</a></li>
			<li data-menuanchor="blacklid"><a href="#blacklid">16OZ Black Lid</a></li>
			<li data-menuanchor="tins"><a href="#tins">Designer 16oz Tins</a></li>
	</ul>
	</div>
<div id="fullpage">
	
    <div class="section section-wrap" id="section0">
        <div class="intro">
		<div class="container content-page">
		
		<div class="page-content sidebar-position-without sidebar-mobile-bottom">
			<div class="row">

				<div class="content col-md-12">
					<?php echo do_shortcode( '[product_shortcode_new category="Hexagon Jars" title="10OZ. HEXAGON CANDLES - BURN 70+ HOURS" price="$14.00" season="summer"]' ); ?>
				</div>
			</div>
		</div>
		</div>
		</div>
    </div>
	
	<div class="section section-wrap" id="section1">
        <div class="intro">
		<div class="container content-page">
		
		<div class="page-content sidebar-position-without sidebar-mobile-bottom">
			<div class="row">

				<div class="content col-md-12">
					<?php echo do_shortcode( '[product_shortcode_new category="Boxed Crystal" title="16OZ SILVER LID/SINGLE WICK CANDLES - BURN 100+ HOURS" price="$25.00" season="summer"]' ); ?>
				</div>
			</div>
		</div>
		</div>
		</div>
    </div>
	<div class="section section-wrap" id="section2">
        <div class="intro">
		<div class="container content-page">
		
		<div class="page-content sidebar-position-without sidebar-mobile-bottom">
			<div class="row">

				<div class="content col-md-12">
					<?php echo do_shortcode( '[product_shortcode_new category="Boxed Metallic" title="16 oz. double wick candles - burn 110+ hours" price="$36.00" season="summer"]' ); ?>
				</div>
			</div>
		</div>
		</div>
		</div>
    </div>
	<div class="section section-wrap" id="section3">
        <div class="intro">
		<div class="container content-page">
		
		<div class="page-content sidebar-position-without sidebar-mobile-bottom">
			<div class="row">

				<div class="content col-md-12">
					<?php echo do_shortcode( '[product_shortcode_new category="16oz-round-classic" title="16OZ BLACK LID/SINGLE WICK CANDLES - BURN 100+ HOURS" price="$20.00" season="summer"]' ); ?>
				</div>
			</div>
		</div>
		</div>
		</div>
    </div>
	<div class="section section-wrap" id="section4">
        <div class="intro">
		<div class="container content-page">
		
		<div class="page-content sidebar-position-without sidebar-mobile-bottom">
			<div class="row">

				<div class="content col-md-12">
					<?php echo do_shortcode( '[product_shortcode_new category="Designer 16oz Tins" title="16 oz. tins - burn 100+ hours" price="$20.00" season="summer"]' ); ?>
				</div>
			</div>
		</div>
		</div>
		</div>
    </div>
</div>
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
        <span class="popup-close">Or, Cancal</span>
    </div>
    </div>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/catalog/fullpage.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/catalog/examples.js"></script>

<script type="text/javascript">
    var myFullpage = new fullpage('#fullpage', {
        menu: '#menu',
        anchors: ['hexagon', 'crystal', 'metallic', 'blacklid', 'tins'],
        sectionsColor: ['#FFF', '#f7f7f7', '#fff'],
        autoScrolling: true,
        licenseKey: 'OPEN-SOURCE-GPLV3-LICENSE'
    });
	jQuery(window).scroll(function() {    
    var scroll = jQuery(window).scrollTop();

     //>=, not <=
    if (scroll >= 500) {
        //clearHeader, not clearheader - caps H
        jQuery("#menu").addClass("darkHeader");
    }
});
</script>
<?php
		/* Always have wp_footer() just before the closing </body>
		 * tag of your theme, or you will break many plugins, which
		 * generally use this hook to reference JavaScript files.
		 */

		wp_footer();
	?>
</body>

</html>