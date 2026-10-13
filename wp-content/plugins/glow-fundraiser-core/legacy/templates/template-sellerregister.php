<?php
/**
 * Template Name: Seller Registraion TESTNEW
 */
 get_header();
?>
<style>
button[disabled], html input[disabled] { cursor: default; opacity: 0.5; }
.page-heading { background-repeat: no-repeat; padding: 55px 20px; background-size: cover; background-position: center; }
.page-heading .title { color: #ffffff; font-size: 5rem; font-family: 'proxima_nova_altblack'; text-transform: capitalize; text-shadow: 1px 1px 5px #000; }
.page-heading a, .page-heading .woocommerce-breadcrumb { font-size: 15px; color:#fff; }
.new-seller-register-page .g-info { font-size: 20px; }
.page-heading .breadcrumbs { display: none; }
.new-seller-register-page h1.largest { padding-top: 60px;  line-height: 72px; font-size: 55px; line-height: 1.1 !important; }
.new-seller-register-page h1.sec { font-size: 25px; line-height: 1.1 !important; font-weight: initial; }
.new-seller-register-page .page-not-found { text-align: center; padding-bottom: 90px; display: inline-block; width: 100%; }
.new-seller-register-page .GrupName { color: #cda85c; text-transform: capitalize; }
.new-seller-register-page .g-info { text-align: center; }
.new-seller-register-page .g-info p { margin: 0; color: #fff; font-size: 22px; font-family: 'proxima_nova_altbold';   line-height: 130%; display: inline-block; padding: 20px; border: 2px solid #000; background: #b5955c; outline: 10px solid #b5955c; } 
body .GrupName { color: #fff !important; }
	
.why-register * { font-family: 'proxima_nova_ltthin'; }
.why-register { margin:0; padding:30px 0 0; text-align: center; }
.why-register h3 { font-family: 'proxima_nova_altblack'; font-size: 40px; text-transform: capitalize; }
.why-register h4 { font-family: 'proxima_nova_ltsemibold'; font-size: 25px; }
.why-register ul { display: flex; list-style: none; padding: 0; gap: 25px; }
.why-register ul li { font-size: 16px; list-style: none; margin:0; background: #000; color:#fff; padding: 35px; font-family: 'proxima_nova_ltsemibold'; }
.why-register p.space_bottom { color: #000; font-size: 20px; font-family: 'proxima_nova_altbold'; max-width: 480px;   margin: 0 auto 30px; line-height: 150%; }

.slr_rg_frm { background: #f9f9f9 !important; padding:20px !important; }
.slr_rg_frm .input-text { padding: 13px !important; max-height: inherit !important; height: auto !important; border: 1px solid #ededed !important; }
.slr_rg_frm .ur-button-container { width: 100% !important; margin: 0 !important; }
.slr_rg_frm .btn { width: 100% !important; }
.slr_rg_frm  textarea::placeholder { color:#767676 !important; }
.slr_rg_frm textarea.input-text { font-size: 14px; }
	button.show-password-input { display:none !important; }
</style>
<?php 

	$l = et_page_config();

?>

<?php do_action( 'et_page_heading' ); ?>

	<div class="container content-page new-seller-register-page">
		<div class="page-content sidebar-position-<?php esc_attr_e( $l['sidebar'] ); ?> sidebar-mobile-<?php esc_attr_e( class_exists('WooCommerce') && ( is_checkout() || is_cart() || is_account_page() || in_array( 'woocommerce-wishlist', get_body_class() ) ) ? $l['shop-sidebar-mobile'] : $l['sidebar-mobile'] ); ?>">
			<div class="row">

				<div class="content">
					
					<?php if(have_posts()): while(have_posts()) : the_post(); ?>
						
						<?php 
		if(!empty($_GET['frid'])) 
		{ 
			$query = $_GET['frid'];
			if ( preg_match( '/([0-9]{6})/', $query) == 1) 
			{
				$user_info = get_userdata($query);
				$username = $user_info->user_login;
				
				if(!empty($username))
				{
					$check=1;
					$userID=$user_info->ID;
					$org = get_user_meta( $userID , 'user_registration_name_of_school_business' , true);   
					if(empty($org))
					{					
						$org=get_user_meta( $userID , 'user_registration_input_company' , true );
					}
				}   
				if($check==1)
				{
					if(!empty($org))
					{
						echo "<div class='g-info'><p>Group Name: <br> <span>".$org."</span></p></div>";?> 
					<div class="why-register">
			<h3>Why Register as a Seller? </h3>
			<p class="space_bottom">Your participation matters! <br> Make it happen. Make it count. Be a Star ⭐ </p>
			
			<h4>Benefits of Registering as a Seller: </h4>
			<ul>
				<li>Easily share your seller link or QR code to invite family & friends to shop online and support your sale.</li>
				<li>No cash? No problem! Direct supporters to your online store for quick and easy checkout.
</li>
				<li>Track your sales in real time and watch your progress toward your personal goal.</li>
			</ul>
			
	    </div>
				</div>
				<?php 
					}
					else
					{
						echo "<div class='g-info'><p>Group Name: <br> <span>Not available</span></p></div>";?>
				
						 <div class="why-register">
			<h3>
				Why Register as a Seller?
			</h3>
			<p class="space_bottom">Your participation matters! <br> Make it happen. Make it count. Be a Star ⭐ </p>			
			<h4>Benefits of Registering as a Seller:</h4>
			<ul>
				<li>Easily share your seller link or QR code to invite family & friends to shop online and support your sale.</li>
				<li>No cash? No problem! Direct supporters to your online store for quick and easy checkout.
</li>
				<li>Track your sales in real time and watch your progress toward your personal goal.</li>
			</ul>
			
	    </div>
				</div>
			<?php 
					}
					echo do_shortcode('[user_registration_form id="22005"]');
				?>
				<script>
				jQuery(document).ready(function()
				{
					jQuery('#seller_fundraiser').attr("disabled", "");
				});
				</script>
				<?php
				}
				else
				{
					echo "<div class='page-not-found'><h1 class='largest'>Error</h1><h1 class='sec'>Incorrect Fundraiser ID</h1><hr class='horizontal-break'><p>Sorry, but the page you are looking for is not found. Please, make sure you have typed the current URL.</p></div>";
				}
			}
			else
			{
				echo "<div class='page-not-found'><h1 class='largest'>Error</h1><h1 class='sec'>Oops! Incorrect url</h1><hr class='horizontal-break'><p>Sorry, but the page you are looking for is not found. Please, make sure you have typed the current URL.</p></div>";
			}
		}
		else
		{
		?>

		<style>
		.wizard {
    margin: 20px auto;
    background: #fff;
}

    .wizard .nav-tabs {
        position: relative;
        margin: 40px auto;
        margin-bottom: 0;
        border-bottom-color: #e0e0e0;
    }

    .wizard > div.wizard-inner {
        position: relative;
    }

.connecting-line {
    height: 2px;
    background: #e0e0e0;
    position: absolute;
    width: 80%;
    margin: 0 auto;
    left: 0;
    right: 0;
    top: 50%;
    z-index: 1;
}

.wizard .nav-tabs > li.active > a, .wizard .nav-tabs > li.active > a:hover, .wizard .nav-tabs > li.active > a:focus {
    color: #555555;
    cursor: default;
    border: 0;
    border-bottom-color: transparent;
}

span.round-tab {
    width: 70px;
    height: 70px;
    line-height: 70px;
    display: inline-block;
    border-radius: 100px;
    background: #fff;
    border: 2px solid #e0e0e0;
    z-index: 2;
    position: absolute;
    left: 0;
    text-align: center;
    font-size: 25px;
}
span.round-tab i{
    color:#555555;
}
.wizard li.active span.round-tab {
    background: #fff;
    border: 2px solid #5bc0de;
    
}
.wizard li.active span.round-tab i{
    color: #5bc0de;
}

span.round-tab:hover {
    color: #333;
    border: 2px solid #333;
}

.wizard .nav-tabs > li {
    width: 25%;
}

.wizard li:after {
    content: " ";
    position: absolute;
    left: 46%;
    opacity: 0;
    margin: 0 auto;
    bottom: 0px;
    border: 5px solid transparent;
    border-bottom-color: #5bc0de;
    transition: 0.1s ease-in-out;
}

.wizard li.active:after {
    content: " ";
    position: absolute;
    left: 46%;
    opacity: 1;
    margin: 0 auto;
    bottom: 0px;
    border: 10px solid transparent;
    border-bottom-color: #5bc0de;
}

.wizard .nav-tabs > li a {
    width: 70px;
    height: 70px;
    margin: 20px auto;
    border-radius: 100%;
    padding: 0;
}

    .wizard .nav-tabs > li a:hover {
        background: transparent;
    }

.wizard .tab-pane {
    position: relative;
    padding-top: 50px;
}

.wizard h3 {
    margin-top: 0;
}
.stepwizard {
    display: table;
    width: 100%;
    position: relative;
}
.stepwizard-row {
    display: table-row;
}
.stepwizard-row:before {
    top: 14px;
    bottom: 0;
    position: absolute;
    content: " ";
    width: 100%;
    height: 1px;
    background-color: #ccc;
    z-index: 0;
}
.stepwizard-step {
    display: table-cell;
    text-align: center;
    position: relative;
}
.btn-success {
    color: #fff;
    background-color: #5cb85c;
    border-color: #4cae4c;
}
.btn-circle {
    width: 30px;
    height: 30px;
    text-align: center;
    padding: 6px 0;
    font-size: 12px;
    line-height: 1.428571429;
    border-radius: 15px;
}
.stepwizard-step p {
    margin-top: 0px;
    color: #666;
}
.stepwizard small {
    font-size: 85%;
}
.stepwizard .btn.disabled, .stepwizard .btn[disabled], .stepwizard fieldset[disabled] .btn {
    opacity: 1 !important;
    color: #bbb;
}
.glow-register_user #step2, .glow-register_user #step1
{
	display:none;
}
.stepwizard-step .btn-success {
    color: #fff;
    background-color: #000;
    border-color: #000;
}
.validateId, .validateEmail
{
	    background: #000;
    color: #fff;
    padding: 10px 44px;
    border-radius: 30px;
    font-size: 13px;
}
.validateId:hover, .validateId:focus, .validateEmail:hover, .validateEmail:focus
{
	    background: #fff;
    color: #000;
    padding: 10px 44px;
    border-radius: 30px;
    font-size: 13px;
	    border: 1px solid;
	  border-color: #000;
}
.alert.alert-info
{
	background-color: #000;
    border-color: #000;
	color:#fff;
	display:none;
	padding: 9px 0px;
	text-align: left;
}
.alert.alert-info p
{
	    color: #fff;
    padding-top: 5px;
    font-size: 14px;
    margin-left: -12px;
}
.glow-register_user .control-label
{
	    font-size: 20px;
    font-style: normal;
    color: #252525;
    margin: 0 auto;
    padding: 10px 0px 10px 0px;
    display: inline-block;
}
.alert.alert-info p
{
	color: #fff;
    padding-top: 5px;
}
.validate-btn-sec
{
    display: inline-block;
    color: #000;
    font-size: 20px;
    position: relative;
    float: right;
}
.validate-btn-sec span
{
    position: relative;
    padding: 0px 10px 0px;
    top: 6px;
}
.validate-btn-sec span.spin-icon
{
	display:none;
}
.glow-register_user .tab-panen
{
	width: 100%;
    text-align: center;
    display: inline-block;
}
.glow-register_user .tab-panen form
{
	width: 50%;
    text-align: center;
    display: inline-block;
}
.glow-register_user .control-label {
    font-size: 24px;
	padding: 10px 0px 2px 0px;
	margin-bottom: 12px;
    float: left;
}
@media( max-width : 1199px )
{
	   .wizard {
        width: 95%;
        height: auto !important;
    }
	.glow-register_user .control-label 
	{
		font-size: 20px;
		margin-bottom: 8px;
	}
}
@media( max-width : 585px ) 
{
	.wizard {
        width: 90%;
        height: auto !important;
    }

    span.round-tab {
        font-size: 16px;
        width: 50px;
        height: 50px;
        line-height: 50px;
    }

    .wizard .nav-tabs > li a {
        width: 50px;
        height: 50px;
        line-height: 50px;
    }

    .wizard li.active:after {
        content: " ";
        position: absolute;
        left: 35%;
    }
	.glow-register_user .tab-panen form 
	{
		width: 95%;
	}
	.validateId, .validateEmail
	{
		padding: 10px 30px;
		font-size: 12px;
	}
	.stepwizard-step p {
		line-height: 1.2;
	}
	.alert.alert-info p {
		font-size: 13px;
		margin-left: -17px;
		line-height: 1.2;
	}
}
		</style>
		 <div class="wizard">
			
			<div class="stepwizard">
				<div class="stepwizard-row setup-panel">
				<div class="stepwizard-step col-xs-4"> 
						<a type="button" class="btn btn-circle btn-default btn-success firstnewstep">1</a>
						<p><small>Validate Seller Email</small></p>
					</div>
					<div class="stepwizard-step col-xs-4"> 
						<a type="button" class="btn btn-circle btn-default firststep">2</a>
						<p><small>Validate Fundraiser Code</small></p>
					</div>
					<div class="stepwizard-step col-xs-4"> 
						<a type="button" class="btn btn-default btn-circle secstep" disabled="disabled">3</a>
						<p><small>Seller Register</small></p>
					</div>
					
					
				</div>
			</div>
			<div class="glow-register_user">
			<div class="setup-content" id="step3">
					<div class="tab-panen">
						<div  class="alert alert-info" role="alert"><p></p></div>
						<form role="form" method="post">
							<div class="form-group">
								<label class="control-label">Email</label>
								<input type="email" required="required" id="textdata3" class="form-control" placeholder="Enter your email">
							</div>
							<div class="validate-btn-sec"><span class="spin-icon"><i class="fa fa-circle-o-notch fa-spin" style=""></i></span><button class="btn btn-primary nextBtn btn-lg pull-right validateEmail" type="submit">Validate</button></div>
						</form>
						
                    </div>
					 </div>
				<div class="setup-content" id="step1">
					 <div class="tab-panen">
						<div  class="alert alert-info" role="alert"><p></p></div>
						<form role="form" action="" method="post">
							<div class="form-group">
								<label class="control-label">Fundraiser ID</label>
								<input maxlength="6" type="number" required="required" id="textdata" class="form-control" placeholder="Fundraiser ID">
							</div>
							<div class="validate-btn-sec"><span class="spin-icon"><i class="fa fa-circle-o-notch fa-spin" style=""></i></span><button class="btn btn-primary nextBtn btn-lg pull-right validateId" type="button">Validate</button></div>
						</form>
						
                    </div>
				</div>
				<div class="setup-content" id="step2">
                    <div class="tab-panen">
                       <?php echo do_shortcode('[user_registration_form id="22005"]'); ?>
                    </div>
				</div>
				
			</div>
			  </div>
			  <script>
							jQuery(document).ready(function()
							{
								  
								jQuery('.validateId').click(function() 
								{ 
									jQuery(".glow-register_user span.spin-icon").css("display","inline-block");
									var FID=jQuery('.glow-register_user #textdata').val();
									if(FID!=null && FID!=undefined) 
									{
										 var ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>";
										 jQuery.ajax({

										 type : "post",

										 url : ajaxurl,

										 data : {action: 'ajax_seller_register_FID_validation',fund_id:FID},

										 success: function(response) {
													
														console.log(response);
														if(response.data.action==1)
														{
															jQuery(".glow-register_user .alert.alert-info p").text("Successfully validated!!");
															jQuery(".glow-register_user .alert.alert-info").css("display","block");
															
															setTimeout(function()
															{
																
																jQuery(".glow-register_user span.spin-icon").css("display","none");
																var aFID=jQuery('.glow-register_user #textdata').val();
																jQuery('#user-registration-form-22005 #seller_fundraiser').val(aFID);
																jQuery('#user-registration-form-22005 #seller_fundraiser').attr("disabled", true);
																jQuery(".stepwizard .firststep").html('<i class="fa fa-check" aria-hidden="true"></i>');
																jQuery('.stepwizard .secstep').attr("disabled", false);
																jQuery(".glow-register_user #step1").css("display","none");
																jQuery(".glow-register_user #step2").css("display","block");
																jQuery(".stepwizard-step .secstep").addClass("btn-success");
															
															}, 2000);
															
														}
														else if(response.data.action==2)
														{
															setTimeout(function()
															{
																jQuery(".glow-register_user span.spin-icon").css("display","none");
																jQuery(".glow-register_user .alert.alert-info p").text("Failed: Wrong fundraiser code!!");
																jQuery(".glow-register_user .alert.alert-info").css("display","block");
															}, 2000);
														}
														else if(response.data.action==4)
														{
															setTimeout(function()
															{
																jQuery(".glow-register_user span.spin-icon").css("display","none");
																jQuery(".glow-register_user .alert.alert-info p").text("Failed: Fundraiser is Inactive!!");
																jQuery(".glow-register_user .alert.alert-info").css("display","block");
															}, 2000);
														}
														else
														{
															setTimeout(function()
															{
																jQuery(".glow-register_user span.spin-icon").css("display","none");
																jQuery(".glow-register_user .alert.alert-info p").text("Error: Something went wrong!!");
																jQuery(".glow-register_user .alert.alert-info").css("display","block");
															}, 2000);
														}
													}
										});
									}
				
				
							});
							  function IsEmail(email) {
									const regex =
						/^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
									if (!regex.test(email)) {
										return false;
									}
									else {
										return true;
									}
								}
							jQuery('.validateEmail').on('click',function(e) 
							{ 
							       
									jQuery(".glow-register_user span.spin-icon").css("display","inline-block");
									var seller_email=jQuery('.glow-register_user #textdata3').val();
									
									e.preventDefault();
									
									if (seller_email === '') {
										jQuery(".glow-register_user span.spin-icon").css("display","none");
										jQuery('.glow-register_user .alert.alert-info p').text("Input field can not be empty!!");
										jQuery(".glow-register_user .alert.alert-info").css("display","block");
										
									}
									else if (IsEmail(seller_email) === false) {
										jQuery(".glow-register_user span.spin-icon").css("display","none");
										jQuery('.glow-register_user .alert.alert-info p').text("Entered Email is not valid!!");
										jQuery(".glow-register_user .alert.alert-info").css("display","block");
										
									}
									else if(IsEmail(seller_email) === true){
									
									if(seller_email!=null && seller_email!=undefined) 
									{
										 var ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>";
										 jQuery.ajax({

										 type : "post",

										 url : ajaxurl,

										 data : {action: 'ajax_seller_register_email_validation',seller_email:seller_email},

										 success: function(response) {
													
														console.log(response);
														if(response.data.action==1)
														{
															//jQuery(".glow-register_user .alert.alert-info p").text("Successfully validated!!");
															//jQuery(".glow-register_user .alert.alert-info").css("display","block");
															
															setTimeout(function()
															{
																
																jQuery(".glow-register_user span.spin-icon").css("display","none");
																var a_seller_email=jQuery('.glow-register_user #textdata3').val();
																jQuery('#user-registration-form-22005 #user_email').val(a_seller_email);
																jQuery('#user-registration-form-22005 #user_email').attr("disabled", true);
																jQuery(".stepwizard .firstnewstep").html('<i class="fa fa-check" aria-hidden="true"></i>');
																jQuery('.stepwizard .firststep').attr("disabled", false);
																jQuery(".glow-register_user #step3").css("display","none");
																jQuery(".glow-register_user .alert.alert-info").css("display","none");
																jQuery(".glow-register_user #step1").css("display","block");
																jQuery(".stepwizard-step .firststep").addClass("btn-success");
															
															}, 2000);
															
														}
														else if(response.data.action==2)
														{
															setTimeout(function()
															{
																jQuery(".glow-register_user span.spin-icon").css("display","none");
																if(response.data.user_type==1)
																{
																	jQuery(".glow-register_user .alert.alert-info p").text("Email has already been used for fundraising. Please use a different email.");
																}
																else if(response.data.user_type==2)
																{
																	jQuery(".glow-register_user .alert.alert-info p").text("Email already exists. Please reregister.");
																	setTimeout(function()
																	{
																		window.location.href = "<?php echo esc_url( glow_dynamic_url_base('home') ); ?>seller-rebooking/";
																	
																	}, 2000);
																}
																else
																{
																	jQuery(".glow-register_user .alert.alert-info p").text("Email already exists. Please use a different email or rebook.");
																}
																
																jQuery(".glow-register_user .alert.alert-info").css("display","block");
															}, 2000);
															
														}
														else
														{
															setTimeout(function()
															{
																jQuery(".glow-register_user span.spin-icon").css("display","none");
																jQuery(".glow-register_user .alert.alert-info p").text("Error: Something went wrong!!");
																jQuery(".glow-register_user .alert.alert-info").css("display","block");
															}, 2000);
														}
													}
										});
									}
							}
							else {
								setTimeout(function()
															{
																jQuery(".glow-register_user span.spin-icon").css("display","none");
																jQuery(".glow-register_user .alert.alert-info p").text("Error: Something went wrong!!");
																jQuery(".glow-register_user .alert.alert-info").css("display","block");
															}, 2000);
							}
				
							});	
						});
					
						</script>
		<?php
		}

		?>

						

					<?php endwhile; else: ?>

						<h3><?php esc_html_e('No pages were found!', 'royal') ?></h3>

					<?php endif; ?>
               

				

			</div><!-- end row-fluid -->

		</div>
	</div><!-- end container -->

<?php
get_footer();
	//wp_footer();
?>
