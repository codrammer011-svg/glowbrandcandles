<?php 
/*
*  Custom Send check recieved Email
*/
add_action('wp_ajax_nopriv_custom_send_check', 'custom_send_check'); // for not logged in users
add_action('wp_ajax_custom_send_check', 'custom_send_check');
function custom_send_check()
{
	$fid=$_POST['fid'];
	$referral_email = get_user_meta($fid, 'user_registration_referral_email', true );
	$user_info = get_userdata($fid);
	$user_email = $user_info->user_email;
	$user_first = $user_info->first_name;
	$user_second = $user_info->last_name;
	//$to = $user_email;
	if(!empty($user_email) && !empty($referral_email))
	{
		$multiple_recipients = array(
						$user_email,
						$referral_email
		);
	}
	else
	{
		$multiple_recipients = $user_email;		
	}
	$payment_cheque=$_POST['payment_cheque'];
	$payment_sale=$_POST['payment_sale'];
	$subject = "Payment Received!!";
	$body ='';
	ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html lang="en">
  <head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>
     Glow Scented Candles
    </title>
   <!--[if gte mso 9]>
    <style type="text/css">
   
     </style>
   <![endif]-->  
</head>
<body style="margin: 0; padding: 0;" class="body">
<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;background:#f2f2f2;width:100%;float:left;">
   <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:40px auto;width: 920px;overflow: hidden;">
        <tr>
             <td align="center" style="padding:30px; text-align:center;background:#333;">
				<h1 style="color:#ffffff;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left"> Hi <?php echo $user_first." ".$user_second.","; ?> </h1>
		  	</td>
		</tr>
			
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							<tr>
								<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: initial;line-height: 1.3;font-size: 14px;">Glow Scented Candles has recieved your payment for your fundraiser. <br/>We are processing your order and will send you UPS tracking information when the order leaves our facility.</td>
							</tr>
							<tr>
								<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;line-height: 1.3;font-size: 14px;">Thank you for choosing Glow. <br/>-The Glow Team</td>
							</tr>
							
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		
			
    </table>
	
    </div>
  </body>
</html>
<?php 
    $body= ob_get_clean();
    $m_body=stripslashes($body);
	$headers = array('Content-Type: text/html; charset=UTF-8','From: ' . GLOW_FROM_EMAIL);
	$mailSuc= wp_mail( $multiple_recipients, $subject, $m_body, $headers );
	if($mailSuc){
		echo "Your email has been successfully sent.";
	} else{
		echo "Error!!";
	}
	die();
	
}
