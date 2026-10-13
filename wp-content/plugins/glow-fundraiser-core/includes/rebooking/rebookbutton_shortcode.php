<?php
// The shortcode function
function wpb_fund_rebookbutton_shortcode() { 

$string = '';
ob_start();
?>
<style>
.fundr_signIn
{
    background: #000;
    color: #fff;
    padding: 11px 20px;
    border: 1px solid #000;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 400;
    font-size: 13px;
    text-transform: uppercase;
}
.sm_note
{
	padding-top: 15px;
    display: inline-block;
    padding-left: 10px;
}
@media screen and (max-width: 560px)
{
	.fundr_signIn
	{
		margin: 0px 0px 0px 0px;
		display: inline-block;
	}
}
</style>
<?php 
 if ( is_user_logged_in() ) {
?>
<a href="https://glowbrandscandles.com/fundraiser-dashboard/" class="login-link fundr_signIn">Fundraiser Dashboard</a>
<b class="sm_note">You are already logged in. Click on Fundraiser Dashboard</b>
<?php
 }
 else {
?>
<a class="login-link fundr_signIn loginUser">Fundraiser Login</a>
 <?php } ?>
<script>

jQuery(document).ready(function () {
  jQuery(document).on('click', '.login-link.fundr_signIn.loginUser', function (e) 
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
<?php
$string = ob_get_clean();  
return $string;  
}
add_shortcode('fund_rebookbutton_shortcode', 'wpb_fund_rebookbutton_shortcode'); 

// The shortcode function
function wpb_seller_rebookbutton_shortcode() { 

$string = '';
ob_start();
?>
<style>
.fundr_signIn
{
    background: #000;
    color: #fff;
    padding: 11px 20px;
    border: 1px solid #000;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 400;
    font-size: 13px;
    text-transform: uppercase;
}
.sm_note
{
	padding-top: 15px;
    display: block;
    padding-left: 10px;
}
@media screen and (max-width: 560px)
{
	.fundr_signIn
	{
		margin: 0px 0px 0px 0px;
		display: inline-block;
	}
}
</style>
<?php 
 if ( is_user_logged_in() ) {
?>
<a href="https://glowbrandscandles.com/seller-dashboard/" class="login-link fundr_signIn">Seller Dashboard</a>
<b class="sm_note">You are already logged in. Click on Seller Dashboard</b>
<?php
 }
 else {
?>
<a class="login-link fundr_signIn loginUser">Seller Login</a>
 <?php } ?>
<script>

jQuery(document).ready(function () {
  jQuery(document).on('click', '.login-link.fundr_signIn.loginUser', function (e) 
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
<?php
$string = ob_get_clean();  
return $string;  
}
add_shortcode('seller_rebookbutton_shortcode', 'wpb_seller_rebookbutton_shortcode'); 
?>