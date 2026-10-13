<?php 
/* 
	Template Name: Booking Detail Template
*/
 
	get_header();
	$u_id=$_GET["id"];
	$f=get_user_meta( $u_id , 'first_name' , true );		
	$l=get_user_meta( $u_id , 'last_name' , true );	
	$user_full_name = $f." ".$l;
// 	$org=get_user_meta( $u_id , 'user_registration_input_company' , true );
$org=get_user_meta( $u_id , 'user_registration_name_of_school_business' , true );
	$user_registration_image_url_profile = get_user_meta( $u_id , 'user_registration_image_url_profile' , true );
	 $user = get_userdata($u_id);
    $user_email = $user->user_email;
	$user_registration_online_fundraiser_field = get_user_meta( $u_id , 'user_registration_online_fundraiser_field' , true ); 
// 	$org=get_user_meta( $u_id , 'user_registration_input_company' , true );
	$sd=get_user_meta( $u_id , 'user_registration_start_date' , true );
	$ed=get_user_meta( $u_id , 'user_registration_end_date' , true );
	$before=get_user_meta( $u_id , 'user_registration_radio_1534260773' , true );
	$hear=get_user_meta( $u_id , 'user_registration_reference' , true );
	$referral=get_user_meta( $u_id , 'user_registration_referral_name' , true );
	$referralEmail=get_user_meta( $u_id ,'user_registration_referral_email', true );
	
	$type=get_user_meta( $u_id , 'user_registration_favorite_candle_scents' , true );
	$ship=get_user_meta( $u_id , 'user_registration_input_box_ship' , true );
	$city=get_user_meta( $u_id , 'user_registration_city' , true );
	$state=get_user_meta( $u_id , 'user_registration_select_state' , true );
	$zip=get_user_meta( $u_id , 'user_registration_input_box_zip' , true );
	
	$phone=get_user_meta( $u_id , 'user_registration_input_box_phone' , true );
	$phone2=get_user_meta( $u_id , 'user_registration_input_box_1595312762' , true );
	
	$Participants= get_user_meta( $u_id , 'user_registration_participants_no' , true );
	$goal_amount=get_user_meta( $u_id , 'user_registration_fr_goal' , true );
	$go_toward=get_user_meta( $u_id , 'user_registration_fund_destination' , true );  
	$selling_goal=get_user_meta( $u_id , 'user_registration_selling_goal' , true );
	$rebate_option=get_user_meta( $u_id , 'user_registration_rebate_option' , true );
	$payable=get_user_meta( $u_id , 'user_registration_check_payable' , true );
	$paypal=get_user_meta( $u_id , 'user_registration_paypal_email' , true );
?>
<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:36px auto;width:90%;overflow:hidden;border: 1px solid #000;font-size: 15px;">
        <tbody><tr>
             <td align="center" style="padding:30px;text-align:center;background:#333">
				<h1 style="color:#ffffff;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left"> <span class="il">Fundraiser</span> <span class="il">Registered</span> </h1>
		  	</td>
		</tr>
		 <tr style="width:100%;padding:25px;max-width:1000px;margin:0 auto;float:left">
           <td align="left" style="width:100%;display:inline-block;border-top: 0;">
	   <p>Hi <?php echo $user_full_name; ?></p>
	   <p style="color:red"><b>!!PLEASE DO NOT DELETE THIS EMAIL!!</b></p>
		<p> Thank you for booking your GLOW SCENTED CANDLES <span class="il">fundraiser</span>! We are excited to work with you &amp; your group while making the sale as successful, hassle-free and turnkey as possible.</p>
		<p style="color:#000;text-decoration:underline"><b>CATALOG SALES (if your group is doing online only, please disregard)</b></p>
		<p>Glow will ship your brochures along with kick off product to you within 2 weeks of your sale start date via UPS to the address given below. Tracking information will be provided once it ships. In the meantime, you can set your online account below.  </p>
      
		<p style="color:#000;text-decoration:underline"><b>ONLINE "DIRECTOR LOGIN":</b></p>
		<p>Click the following link <a href="#" target="_blank">https://glowbrandscandles.com/my-account/</a> to access the Director Login. </p> 
		<p><b>Login Details: </b></p>
         <p><b>Email Address: </b> <a href="mailto:<?php echo $user_email; ?>" target="_blank"><?php echo $user_email; ?></a> </p>
	     <p><b>Password: </b> ******** </p>
		 
		<p>Here you can-</p>
		<ul>
		<li>View your up-to-date sales</li>
		 <li>Invite sellers to <span class="il">register</span> for accounts to track their own sales</li>
		 <li>Place your catalog order at the end of your <span class="il">fundraiser</span></li>
		 <li>View your total <span class="il">fundraiser</span> profits</li>
		 </ul>
		 <hr style="border-style:dotted">
		 <p><b>Your <span class="il">Fundraiser</span> ID: <?php echo $u_id; ?></b></p>
		  
		 <img src="<?php if(!empty($user_registration_image_url_profile)){ echo $user_registration_image_url_profile; } else{ echo "https://glowscentedcandles.com/wp-content/uploads/2021/11/3135715.png"; } ?>" width="120" height="120" class="CToWUd"><br>
		<?php if(!empty($user_registration_image_url_profile)){ ?> <p style="text-align:center;background:rgb(65,132,243);padding:8px 18px;display:inline-block;border-radius:2px;margin:0;margin-top:10px;margin: 12px 0px;font-size: 13px;"><a style="color:#ffffff;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:14px;font-weight:300;line-height:150%;text-decoration:none;" href="<?php echo $user_registration_image_url_profile; ?>" target="_blank">Download Logo</a></p> <?php } else { echo "<p style='margin-top: 20px;'></p>"; } ?>
		 		<pre>Do you want an online only <span class="il">fundraiser</span>? : <?php echo $user_registration_online_fundraiser_field; ?><br>Organization's Name: <?php echo $org; ?><br><span class="il">Fundraiser</span>'s Name:  <?php echo $user_full_name; ?><br>Have you had a Glow <span class="il">Fundraiser</span> before?: <?php echo $before; ?><br>How did you hear about Glow?: <?php echo $hear; ?><br>Name of referral: <?php echo $referral; ?><br>Referral's email address: <a href="mailto:<?php echo $referralEmail; ?>" target="_blank"><?php echo $referralEmail; ?></a><br>What are your favorite type of candle scents?: <?php echo $type; ?><br>Shipping Address: <?php echo $ship; ?><br>City: <?php echo $city; ?><br>State: <?php echo $state; ?><br>Zip: <?php echo $zip; ?><br>Phone Number: <?php echo $phone; ?><br>Cell Phone: <?php echo $phone2; ?><br><a href="mailto:<?php echo $user_email; ?>" target="_blank">Email:<?php echo $user_email; ?></a><br>Number of Participants: <?php echo $Participants; ?><br>Start Date: <?php echo $sd; ?><br>End Date: <?php echo $ed; ?><br><span class="il">Fundraising</span> Goal Amount:  <?php echo $goal_amount; ?><br>Funds Go Toward: <?php echo $go_toward; ?><br>Selling Goal (# of Items):  <?php echo $selling_goal; ?><br>Rebate Pay By ?:  <?php echo $rebate_option; ?><br>Checks made payable to:  <?php echo $payable; ?><br></pre>
		<p style="margin-bottom: 0;"><b>Profile Link: <a href="https://glowbrandscandles.com/profile/<?php echo $u_id; ?>" target="_blank">https://glowbrandscandles.com/profile/<?php echo $u_id; ?></a></b></p>
		<!--<hr style="border-style:dotted"> -->
		<ul>
 	<li>Boost sales and reach everyone with or online shopping option. This is the perfect solution to out of town family &amp; friends. Customer pays for their order online via credit card, their order drop ships directly to the customer within 7 days.</li>
 	<li>Glow issues (ONE) profit check to the <span class="il">fundraiser</span> director for easy disbursing of profits/funds earned by seller. YES, sales are tracked by sellers, do not worry!</li>
 	<li>Online sales earn 40% profits, due to shipping and direct drop ship. For the highest profits, we encourage the catalog sale.</li>
 	<li><span class="il">Fundraising</span> Directors will receive a report of profits earned on the 15th of the month concluding their sale. Check will be distributed to the group name listed on this form. If different, please notify Glow.</li>
	</ul>
		<p style="font-size:22px;color:green"><b>Please note: Customer(s) must enter the 6 digit <span class="il">FUNDRAISER</span> ID CODE or use the full fundid link for sales to be tracked. (Box is found top left of the website)</b></p>
		<p>Want to send the parents an email, text or facebook announcing GLOW with the website? Simply copy and paste the website along with the <span class="il">FUNDRAISING</span> CODE and remind them to "shop away"</p>
		<p><b>If you have any questions at all during your sale please feel free to contact us, we are here to help!</b></p>
		<p>The Glow Team<font color="#888888"><font color="#888888"><br>Glow Scented Candles<br>
<a href="mailto:contact@glowscentedcandles.com" target="_blank">contact@glowscentedcandles.com</a><br>
<a href="https://glowbrandscandles.com" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://glowbrandscandles.com&amp;source=gmail&amp;ust=1636697606100000&amp;usg=AOvVaw1ttRhj0yOY5ZEScI4Ov3X3">www.glowscentedcandles.com</a><u></u><u></u></font></font></p>
	  </td>
		</tr>
        </tbody></table>
<?php
	get_footer();
 
?>