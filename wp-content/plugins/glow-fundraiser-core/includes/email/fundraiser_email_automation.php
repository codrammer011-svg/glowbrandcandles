<?php 
/*Function - Before One Day Campaign Start Date*/
function get_fundraiser_emails_before_oneday_campaign_start_date()
{
    $queue=array();
    $args = array(
        'role'    => 'Fundraiser',
        'orderby' => 'user_registered',
        'order'   => 'DESC'
    );
    $users = get_users( $args );

    $i=0;
    foreach ( $users as $user ) {
        $sd=get_user_meta( $user->ID , 'user_registration_start_date' , true );
        date_default_timezone_set('America/Chicago');
        $current_date = date('Y-m-d');
        if($sd>$current_date)
        {
            $now = time(); // or your date as well
            $your_date = strtotime($sd);
            $datediff = $now - $your_date;

             $d=round($datediff / (60 * 60 * 24));
            if(abs($d)==1)
            {
                $before_oneday_start=get_user_meta($user->ID,"before_oneday_start_compaign_email_meta_glow",true);
                if(empty($before_oneday_start))
                {
                    $queue[$i]['userid']=$user->ID;
                    $queue[$i]['email']=$user->user_email;
                    $queue[$i]['start_date']=$sd;
                    $queue[$i]['diffr']=abs($d);
                    $queue[$i]['function']="GLOW Fundraiser Kick-off Day! (Catalog sales only)";
                    $i++;
                }
            }
        }
    }
    for($j=0;$j<count($queue);$j++)
    {
        func_mail_before_oneday_campaign_start_date($queue[$j]['userid'],$queue[$j]['email'],$queue[$j]['start_date'],$queue[$j]['diffr'],$queue[$j]['function']);
        
    }

}

/*Function - Before Five Days Campaign Start Date*/
function get_fundraiser_emails_before_fivedays_campaign_start_date()
{
    $queue=array();
    $args = array(
        'role'    => 'Fundraiser',
        'orderby' => 'user_registered',
        'order'   => 'DESC'
    );
    $users = get_users( $args );

    $i=0;
    foreach ( $users as $user ) {
        $sd=get_user_meta( $user->ID , 'user_registration_start_date' , true );
        date_default_timezone_set('America/Chicago');
        $current_date = date('Y-m-d');
        if($sd>$current_date)
        {
            $now = time(); // or your date as well
            $your_date = strtotime($sd);
            $datediff = $now - $your_date;

             $d=round($datediff / (60 * 60 * 24));
            if(abs($d)==5)
            {
                $before_fivedays_start=get_user_meta($user->ID,"before_fivedays_start_compaign_email_meta_glow",true);
                if(empty($before_fivedays_start))
                {
                    $queue[$i]['userid']=$user->ID;
                    $queue[$i]['email']=$user->user_email;
                    $queue[$i]['start_date']=$sd;
                    $queue[$i]['diffr']=abs($d);
                    $queue[$i]['function']="2nd email-Director email/Tips & Tricks email.";
                    $i++;
                }
            }
        }
    }
    for($j=0;$j<count($queue);$j++)
    {
        func_mail_before_fivedays_campaign_start_date($queue[$j]['userid'],$queue[$j]['email'],$queue[$j]['start_date'],$queue[$j]['diffr'],$queue[$j]['function']);
        
    }
    /*return $queue; */
}


/*Function - After Five days Campaign Start Date*/
function get_fundraiser_emails_after_fivetdays_campaign_start_date()
{
    $queue=array();
    $args = array(
        'role'    => 'Fundraiser',
        'orderby' => 'user_registered',
        'order'   => 'DESC'
    );
    $users = get_users( $args );

    $i=0;
    foreach ( $users as $user ) {
        $sd=get_user_meta( $user->ID , 'user_registration_start_date' , true );
        date_default_timezone_set('America/Chicago');
        $current_date = date('Y-m-d');
        if($sd<$current_date)
        {
            $now = time(); // or your date as well
            $your_date = strtotime($sd);
            $datediff = $now - $your_date;

            $d=round($datediff / (60 * 60 * 24));
            if(abs($d)==5)
            {
                $after_fivedays_start=get_user_meta($user->ID,"after_fivedays_compaign_email_meta_glow",true);
                if(empty($after_fivedays_start))
                {
                    $queue[$i]['userid']=$user->ID;
                    $queue[$i]['email']=$user->user_email;
                    $queue[$i]['start_date']=$sd;
                    $queue[$i]['diffr']=abs($d);
                    $queue[$i]['function']="3rd email- How are sales going email. 5 days after start date email.";
                    $i++;
                }
            }
        }
    }
    for($j=0;$j<count($queue);$j++)
    {
        
        func_mail_after_fivetdays_campaign_start_date($queue[$j]['userid'],$queue[$j]['email'],$queue[$j]['start_date'],$queue[$j]['diffr'],$queue[$j]['function']);
    }
}


/*Function - Before Five Days Campaign End Date*/
function get_fundraiser_emails_before_fivedays_campaign_end_date()
{
    $queue=array();
    $args = array(
        'role'    => 'Fundraiser',
        'orderby' => 'user_registered',
        'order'   => 'DESC'
    );
    $users = get_users( $args );

    $i=0;
    foreach ( $users as $user ) {
        $ed=get_user_meta( $user->ID , 'user_registration_end_date' , true );
        date_default_timezone_set('America/Chicago');
        $current_date = date('Y-m-d');
        if($ed>$current_date)
        {
            $now = time(); // or your date as well
            $your_date = strtotime($ed);
            $datediff = $now - $your_date;

            $d=round($datediff / (60 * 60 * 24));
            if(abs($d)==5)
            {
                $before_fivedays_end=get_user_meta($user->ID,"before_fivedays_end_compaign_email_meta_glow",true);
        
                if(empty($before_fivedays_end))
                {
                    $queue[$i]['userid']=$user->ID;
                    $queue[$i]['email']=$user->user_email;
                    $queue[$i]['end_date']=$ed;
                    $queue[$i]['diffr']=abs($d);
                    $queue[$i]['function']="4th email- Fundraiser wrap up email. 5 days before ending date.";
                    $i++;
                }
            }
        }
    }
    for($j=0;$j<count($queue);$j++)
    {
        func_mail_before_fivedays_campaign_end_date($queue[$j]['userid'],$queue[$j]['email'],$queue[$j]['end_date'],$queue[$j]['diffr'],$queue[$j]['function']);
    }
}


/* Email Function - Before Five Days Campaihn Open  */
function func_mail_before_oneday_campaign_start_date($id, $email, $start_date, $diffr, $func)
{
    $u_id=$id;
    $f=get_user_meta( $u_id , 'first_name' , true );        
    $l=get_user_meta( $u_id , 'last_name' , true ); 
    
    $recipient2=$email;
    $multiple_recipients = array(
                $recipient2
            );
    $subject="Its Kickoff Day! Lets Make This the Best Fundraiser Ever !ACTION NEEDED!";
    $body ='';
    ob_start(); 
?>
<!-- mail 3 -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Glow Mid-Fundraiser Check-In</title>
</head>
<body style="margin:0;padding:0;background-color:#ffffff;font-family:Helvetica, Arial, sans-serif;color:#343434;">


<center style="width:100%;background-color:#ffffff">
  <table style="max-width: 700px; width: 100%;" border="0" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
      <td>
        
      <!-- welcome table -->
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:15px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;font-weight:700">Hello Glow Partner! 🌟</h1>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 10px 0 15px 0px">Today’s the big day — your <strong>Glow Fundraiser Kickoff! 🎉</strong><br>
         We’ve put together your <strong>3 most important steps</strong> (in order of importance!) to help you make this your <strong>best fundraiser ever.</strong></p>
          </td>
        </tr>
        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434;margin: 0px 0 0px 0px">Let’s GLOW! 💫</p>
          </td>
        </tr>
      </table>
       <!-- welcome table -->
     
      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->


      <!-- fundraiser dashboard -->
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:22px;line-height:1.2;color:#3b9d9d;font-weight:400"><span>💥</span> Step 1 — IMPORTANT, AND WE MEAN IMPORTANT!!!</h1>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 0 0 10px 0;"><strong>Have you sent the QR code or link for your Sellers to Register?</strong><br> This is <strong>HUGE! We mean HUGE!</strong>
         </p>
          </td>
        </tr>
        

        <tr style="margin:0 0 12px 0;">
        <td>
        <p style="font-size:15px;line-height:1.6;color:#343434;margin: 0">When your sellers register, they can:
         </p>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
           <ul style="margin:0 0 12px 0px;font-size:14px;line-height:1.6;color:#343434">
                  <li>Track their personal sales goals 💰</li>
                  <li>Share their online store links with family & friends 💌</li>
                  <li>Help raise even more funds for your group 🌟</li>
                </ul>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
        <p style="font-size:15px;line-height:1.6;color:#343434;margin: 0">And you (the Director!) can easily connect with your sellers to <strong>encourage, motivate, and celebrate</strong> their success throughout the fundraiser.
         </p>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
        <h2 style="font-size:20px;line-height:1.6;color:#343434;margin: 10px 0 0 0">👉 BE SURE TO SEND THIS TODAY! 😊
         </h2>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
        <h2 style="font-size:15px;line-height:1.6;color:#343434;margin: 10px 0 0 0;font-weight: 700"><Span>📱</Span> Seller Registration Link & QR Code:
         </h2>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
        <p style="font-size:15px;line-height:1.6;color:#343434;margin: 0"><a href="https://glowbrandscandles.com/seller-registers/" target="_blank"><img src="http://glowscentedcandles.com/wp-content/uploads/2025/10/Picture-pdf.png" style="width:100%;" alt="*"></a>
         </p>
          </td>
        </tr>

       </table>
       <!-- fundraiser dashboard -->

      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->

      <!-- Make It Personal! -->
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:22px;line-height:1.2;color:#3b9d9d;font-weight:400"><span>📦</span> Step 2 — Hand Out Brochures & Participant Letters</h1>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434;margin: 0;">Time to get those materials into your sellers’ hands!<br>
         Make sure everyone receives:
         </p>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
          <td>
             <ul style="margin:10px 0 12px 0px;font-size:14px;line-height:1.6;color:#343434">
                  <li>Their brochure <Span>📘</Span></li>
                  <li>Their participant letter with instructions <span>✉️</span></li>
                </ul>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434;margin: 0;font-style: italic;">(Insert their participant letter here from the auto email)
         </p>
          </td>
        </tr>


      </table>
      <!-- Make It Personal! -->

      
      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->

      <!-- Don’t Forget to Share It! -->
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:22px;line-height:1.2;color:#3b9d9d;font-weight:400"><span>📣</span> Step 3 — Start Posting on social media</h1>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434;margin: 0px 0 0 0">Spread the word and boost those sales!<br>
         Share your group’s online store link across your social pages to get your community shopping and supporting.</p>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434;margin: 10px 0 0 0"><span>✨</span> <strong>Glow Up Your Fundraiser — One Post at a Time!</strong><Br>
         Use the ready-to-post images below to make it easy and eye-catching 👇</p>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434;margin: 10px 0 0 0"><a href="https://www.dropbox.com/scl/fo/2qaa5ldadzp5nnu0l4acn/AF2UoshWPeClkWYJkxHHpAM?rlkey=ex6gonh1c6hvx1ojit5dxu94v&st=ubblc6lz&dl=0" style="text-align:left;color:blue;font-weight:700;font-size:15px" target="_blank">Download Glow Product Images Here</a></p>
          </td>
        </tr>

        </table>
      <!-- Don’t Forget to Share It! -->
      

      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->


       <!-- Help You Glow -->
      <table class="footer" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:16px;line-height:1.2;color:#1f1f1f;font-weight:700"><Span>✨</Span> You’re Off to a Glowing Start!</h1>
          </td>
        </tr>

        <tr style="margin:20px 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 0px 0 0 0">These small but mighty steps will set the tone for a smooth, successful, and super-fun fundraiser.<Br>
         Your energy makes all the difference — and we can’t wait to celebrate your success! 💛</strong></p>
          </td>
        </tr>


        <tr style="margin:20px 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 20px 0 0 0"><strong>With gratitude,<br>
         The Glow Team 🕯️</strong></p>
          </td>
        </tr>
        </table>
      <!-- Help You Glow -->
      

      </td>
    </tr>
  </table>
</center>

</body>
</html>  

<?php
        $body= ob_get_clean();
        $m_body=stripslashes($body);
        $headers = array('Content-Type: text/html; charset=UTF-8','From: ' . GLOW_FROM_EMAIL);
        if(!empty($multiple_recipients))
        {
            if (wp_mail($multiple_recipients, $subject, $m_body, $headers)) 
            {
                error_log("email has been successfully sent to user whose email is " . $multiple_recipients);
                update_user_meta($u_id,"before_oneday_start_compaign_email_meta_glow", "yes");
            }
            else
            {
              error_log("email failed to sent to user whose email is " . $multiple_recipients);
            }
        }


}

function func_mail_before_fivedays_campaign_start_date($id, $email,$user_full_name, $start_date, $diffr, $func)
{
    $u_id=$id;
    $f=get_user_meta( $u_id , 'first_name' , true );        
    $l=get_user_meta( $u_id , 'last_name' , true ); 
    $fund_name=$user_full_name;

    $recipient2=$email;
    $multiple_recipients = array(
                $recipient2
            );
    $subject="Lets Glow! Must-Do Steps- Be sure to Customize your Online Store!";
    $body ='';
    ob_start(); 
?>

<!--- mail 2-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Glow Fundraiser Kickoff Email</title>
</head>
<body style="margin:0;padding:0;background-color:#ffffff;font-family:Helvetica, Arial, sans-serif;color:#343434;">


<center style="width:100%;background-color:#ffffff">
  <table style="max-width: 700px; width: 100%;" border="0" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
      <td>
        
      <!-- welcome table -->
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:15px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;font-weight:700">Hi <?php echo $fund_name; ?>,</h1>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 10px 0 15px 0px">We’re so excited for your <strong>GLOW Fundraiser</strong> and can’t wait to see the amazing results your group will achieve this season! 🎉</p>
          </td>
        </tr>
        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434;margin: 0px 0 0px 0px">Here’s your quick-access guide to make sure everything is set up for success — from dashboards to incentives.</p>
          </td>
        </tr>
      </table>
       <!-- welcome table -->
     
      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->


      <!-- fundraiser dashboard -->
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;font-weight:700"><span>💻</span> Have You Reviewed Your Fundraiser Dashboard?</h1>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 0 0 10px 0;">Your Fundraiser Dashboard is your home base for managing everything — from sales and reports to group communication.
         </p>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
          <a href="https://glowbrandscandles.com/my-account/" target="_blank" style="color: blue; font-weight:700;font-size:15px;">👉 Click Here to Access Your Dashboard</a>
        </td>
        </tr>

        

        <tr style="margin:0 0 12px 0;">
        <td>
        <p style="font-size:15px;line-height:1.6;color:#343434;margin: 0"><span>💡</span> <strong>Tip:</strong> Bookmark it on your browser for easy daily access!
         </p>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
        <p style="font-size:15px;line-height:1.6;color:#343434;margin: 15px 0 0 0">Your dashboard is where the magic happens — so start there first!</strong>
         </p>
          </td>
        </tr>

       </table>
       <!-- fundraiser dashboard -->

      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->

      <!-- Make It Personal! -->
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;font-weight:700"><span>💻</span> 1.Customize Your Online Store — Make It Personal!</h1>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434;margin: 0;">Within your dashboard, be sure to:
         </p>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
          <td>
             <p style="font-size:14px;line-height:1.6;color:#343434;margin: 0;"><span>✅</span> Upload your group logo</p>
             <p style="font-size:14px;line-height:1.6;color:#343434;margin: 0;"><span>✅</span> Add a personalized message for supporters</p>
             <p style="font-size:14px;line-height:1.6;color:#343434;margin: 0;"><span>✅</span> Share why you’re fundraising and how funds will be used</p>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434;margin: 0;">💡 Fundraisers with a personal story and goal message see up to 35% higher participation!
         </p>
          </td>
        </tr>


      </table>
      <!-- Make It Personal! -->

      
      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->

      <!-- Don’t Forget to Share It! -->
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;font-weight:700"><Span>✉️</Span> 2. Participant Letter — Don’t Forget to Share It!</h1>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434;margin: 0">Make sure you’ve finalized your Participant Letter to send to members.<br>You can print it to include with brochures or share it digitally via email or social media.This letter helps everyone understand how to sell, where to shop online, and the impact of their sales.</p>
          </td>
        </tr>
        </table>
      <!-- Don’t Forget to Share It! -->

      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->
      
      <!-- Incentives = Excitement = Sales! -->
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;font-weight:700"><Span>🎉</Span> 3. Incentives = Excitement = Sales!</h1>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 0px 0px 10px 0px">Incentives help motivate and reward your participants — and they don’t have to be expensive to be effective.</p>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 0px 0px 0px 0px"><strong>🎊 Party or Privilege Rewards:</strong></p>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
           <ul style="margin:0 0 12px 0px;font-size:14px;line-height:1.6;color:#343434">
                  <li><Span>🍕</Span> Pizza or Donut Party for the top-selling class or team</li>
                  <li><Span>🍔</Span> Top Seller Lunch with the coach, teacher, or team captain</li>
                  <li><Span>🎶</Span> Music in Class, Free Dress Day, or Early Dismissal Pass for top participants</li>
                  <li>Team Movie or Ice Cream Day when your group hits its goal</li>
                </ul>
          </td>
        </tr>
        
        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 0px 0px 10px 0px"><span>💡</span> <strong>Pro Tip:</strong> Post a fun chart or leaderboard in your group area to track sales progress!</p>
          </td>
        </tr>

        </table>
      <!-- Incentives = Excitement = Sales! -->

      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->
      

      <!-- Quick Refresher -->
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;font-weight:700"><Span>📘</Span> Need a Quick Refresher?</h1>
          </td>
        </tr>

        <tr style="margin:20px 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 0px 0 0 0">If you’d like a quick overview of everything your fundraiser includes, download the <strong>GLOW Directors Guide.</strong></p>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
          <a href="http://glowscentedcandles.com/wp-content/uploads/2025/10/GLOW_FUNDRAISING-DIRECTOR_GUIDE1.webp" target="_blank" style="color: blue; font-weight:700;font-size:15px;text-align: left;">👉 Click Here for the Directors Guide</a>
        </td>
        </tr>

        <tr style="margin:20px 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 0px 0 0 0">Inside you’ll find step-by-step instructions, tips for success, and helpful visuals to guide your team.</strong></p>
          </td>
        </tr>
        </table>
      <!-- Quick Refresher -->

      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->


       <!-- Help You Glow -->
      <table class="footer" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;font-weight:700"><Span>💫</Span> We’re Here to Help You Glow!</h1>
          </td>
        </tr>

        <tr style="margin:20px 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 0px 0 0 0">If you have questions at any point, we’re just an email away.<br>
         Let’s make your fundraiser a huge success — and see your group shine bright this season!</strong></p>
          </td>
        </tr>


        <tr style="margin:20px 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 20px 0 0 0"><strong>Warmly,<br>
The GLOW Team</strong><br>
✨ Where every sale shines and every fundraiser makes a difference.</p>
          </td>
        </tr>
        </table>
      <!-- Help You Glow -->
      

      </td>
    </tr>
  </table>
</center>

</body>
</html>

    <?php 
    $body= ob_get_clean();
    $m_body=stripslashes($body);
    $headers = array('Content-Type: text/html; charset=UTF-8','From: ' . GLOW_FROM_EMAIL);
        if(!empty($multiple_recipients))
        {
            if (wp_mail($multiple_recipients, $subject, $m_body, $headers)) 
            {
              error_log("email has been successfully sent to user whose email is " . $multiple_recipients);
                update_user_meta($u_id,"before_fivedays_start_compaign_email_meta_glow", "yes");
                  /*echo json_encode("Two Days Start email");*/
            }
            else
            {
              error_log("email failed to sent to user whose email is " . $multiple_recipients);
            }
        }
}

/* Email Function - After Five Days Campaign Opened*/ 
function func_mail_after_fivetdays_campaign_start_date($id, $email, $start_date, $diffr, $func)
{
    $u_id=$id;
    $f=get_user_meta( $u_id , 'first_name' , true );        
    $l=get_user_meta( $u_id , 'last_name' , true ); 

    $recipient2=$email;
    $multiple_recipients = array(
                $recipient2
            );
    $subject="Keep Glow Shining Bright! Lets Make This Fundraiser Sparkle!";
    $body ='';
    ob_start(); 
?>
<!-- mail 4 -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Glow Mid-Fundraiser Check-In</title>
</head>
<body style="margin:0;padding:0;background-color:#ffffff;font-family:Helvetica, Arial, sans-serif;color:#343434;">


<center style="width:100%;background-color:#ffffff">
  <table style="max-width: 700px; width: 100%;" border="0" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
      <td>
        
      <!-- welcome table -->
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:15px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;font-weight:700">Hello Glow Partner! 🌟</h1>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 10px 0 15px 0px">We hope your fundraiser is twinkling with holiday cheer and candlelight! 🎄✨<br>
          You’re doing an amazing job — now let’s keep that <strong>momentum glowing</strong> all the way to the finish line!</p>
          </td>
        </tr>
      </table>
       <!-- welcome table -->
     
      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->


      <!-- fundraiser dashboard -->
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;"><span>🎅</span> How’s Your Fundraiser Going?</h1>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 0 0 10px 0;">Now’s the perfect time to do a quick mid-fundraiser check-in:</strong>
         </p>
          </td>
        </tr>
        

        <tr style="margin:0 0 12px 0;">
        <td>
        <p style="font-size:15px;line-height:1.6;color:#343434;margin: 0"><span>✅</span> <strong>Send your seller links or QR codes again</strong> — remind your group to share with friends and family who are still checking gifts off their lists! 🛍️
        <p style="font-size:15px;line-height:1.6;color:#343434;margin: 0"><span>✅</span> <strong>Post your fundraiser link on social media</strong> — ‘tis the season for giving, glowing, and gifting! 💫
        <p style="font-size:15px;line-height:1.6;color:#343434;margin: 0"><span>✅</span> <strong>Check in with your registered sellers</strong> — a quick “You’re doing amazing!” goes a long way to boost spirits and sales.
         </p>
          </td>
        </tr>


        <tr style="margin:0 0 12px 0;">
        <td>
        <p style="font-size:15px;line-height:1.6;color:#343434;margin: 10px 0 0 0">A little encouragement right now can make this your <strong>merriest and most successful fundraiser ever! 🎁</strong>
         </p>
          </td>
        </tr>


       </table>
       <!-- fundraiser dashboard -->

      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->

      <!-- Make It Personal! -->
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

       <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;"><span>💛</span> Glow Fundraisers Make Spirits (and Profits) Bright!</h1>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434;margin: 0;">Did you know your group earns <Strong>a minimum of 40% profit on every item sold</Strong> — and in most cases, 50% on our bestsellers? 🎉<br>
         That means every candle, melt, and spray helps your group reach its goal and makes perfect holiday gifts!
         </p>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
          <td>
          <p style="font-size:15px;line-height:1.6;color:#343434;margin: 20px 0 0 0;"><span>✨</span> Our top earners come from our <strong>Coconut & Soy Candles, Wax less Melts, Room Sprays, and Essential Oils</strong> — perfect scents for every season!
         </p>
          </td>
        </tr>

      </table>
      <!-- Make It Personal! -->

      
      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->

      <!-- Don’t Forget to Share It! -->
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;"><span>🔔</span> Remind Your Sellers: Share, Sell & Shine!</h1>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434;margin: 0px 0 0 0">Encourage your sellers to:</p>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <ul style="margin:0 0 12px 0px;font-size:14px;line-height:1.6;color:#343434">
                  <li>Share their <strong>personal store links</strong> daily on social media, by text, or email.</li>
                  <li>Take brochures to family gatherings or holiday events — everyone loves an easy, meaningful gift! 🎄</li>
                  <li>Keep the energy high and celebrate every sale — every share helps your group GLOW!</li>
          </ul>
        </tr>
        </table>
      <!-- Don’t Forget to Share It! -->
      

      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->


       <!-- Help You Glow -->
      <table class="footer" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;font-weight:700"><Span>🕯️</Span> Let’s Make This the Brightest Fundraiser Yet!</h1>
          </td>
        </tr>

        <tr style="margin:20px 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 0px 0 15px 0">Your leadership lights the way — and together, we’re spreading warmth, joy, and holiday cheer! 🎅💫<br>
        Keep shining, keep selling, and let’s make this season one to remember</strong></p>
          </td>
        </tr>


        <tr style="margin:20px 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 0px 0 0 0"><strong>Wishing you a season full of glow, gratitude, and great sales!</strong></p>
          </td>
        </tr>

        <tr style="margin:20px 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 15px 0 0 0">With warm holiday wishes,<br>
         <strong>The Glow Team 🕯️💛</strong></p>
          </td>
        </tr>


        </table>
      <!-- Help You Glow -->
      

      </td>
    </tr>
  </table>
</center>

</body>
</html>

    <?php 
    $body= ob_get_clean();
    $m_body=stripslashes($body);
    $headers = array('Content-Type: text/html; charset=UTF-8','From: ' . GLOW_FROM_EMAIL);
        if(!empty($multiple_recipients))
        {
            if (wp_mail($multiple_recipients, $subject, $m_body, $headers)) 
            {
              error_log("email has been successfully sent to user whose email is " . $multiple_recipients);
                update_user_meta($u_id,"after_fivedays_compaign_email_meta_glow", "yes");
                
            }
            else
            {
              error_log("email failed to sent to user whose email is " . $multiple_recipients);
            }
        }
}


function func_mail_before_fivedays_campaign_end_date($id, $email, $end_date, $diffr, $func)
{
    $u_id=$id;
    $f=get_user_meta( $u_id , 'first_name' , true );        
    $l=get_user_meta( $u_id , 'last_name' , true ); 
    
    $recipient2=$email;
    $multiple_recipients = array(
                $recipient2
            );
    $subject="Glow Fundraiser Wrap-Up & Brochure Order Instructions";
    $body ='';
    ob_start(); 
?>
<!-- mail 5 -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Glow Fundraiser Wrap-Up</title>
</head>
<body style="margin:0;padding:0;background-color:#ffffff;font-family:Helvetica, Arial, sans-serif;color:#343434;">


<center style="width:100%;background-color:#ffffff">
  <table style="max-width: 700px; width: 100%;" border="0" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
      <td>
        
      <!-- welcome table -->
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:15px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;font-weight:700">Hello Glow Partner! 🌟</h1>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 10px 0 15px 0px">Your fundraiser’s ending date is right around the corner — and that means it’s time to wrap things up and get those last candle sales in! 💫<br>
         Before we dive into the ordering steps…</p>
          </td>
        </tr>
      </table>
       <!-- welcome table -->
     
      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->


      <!-- fundraiser dashboard -->
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;"><span>💥</span> Have You Sent a “Let’s Finish Strong!” Reminder?</h1>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 0 0 10px 0;">Give your group one final nudge to sell, sell, sell! Encourage everyone to:
         </p>
          </td>
        </tr>
        

        <tr style="margin:0 0 12px 0;">
        <td>
         <ul style="margin:0 0 12px 0px;font-size:14px;line-height:1.6;color:#343434">
                  <li>Share their online store links with friends and family.</li>
                  <li>Reach out to those last few supporters to grab final brochure sales.</li>
                  <li> Make this final week your brightest one yet! 🌟</li>
          </ul>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
        <p style="font-size:15px;line-height:1.6;color:#343434;margin: 20px 0 0 0">You can easily send your “Let’s Finish Strong!” message here:
         </p>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
        <p style="font-size:15px;line-height:1.6;color:#343434;margin: 0px 0 0 0"><a href="[INSERT THE GROUPS PROFILE LINK SHARE HERE]" style="color:blue;padding:0px 0px;border-radius:4px;font-weight:bold;display:inline-block;">Share Your Group Profile</a>
         </p>
          </td>
        </tr>


       </table>
       <!-- fundraiser dashboard -->

      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->

      <!-- Make It Personal! -->
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

       <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;"><span>🧾</span> Brochure Order Instructions</h1>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434;margin: 0;">Follow these simple steps to complete your order:
         </p>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <ol style="margin:0 0 12px 0px;font-size:14px;line-height:1.6;color:#343434;padding: 0">
                  <li>Remind your group/team of the upcoming ending date and to bring you their forms and money.</li>
                  <li>Check all forms carefully — make sure the number of items sold and total money collected are correct.</li>
                  <li>Log in to your Fundraiser Dashboard.</li>
                  <li>Select the season and click “Order Now.”</li>
                  <li>Follow the prompts to place your brochure order.</li>
                  <li>Online Order Profits will automatically reconcile and deduct from the total amount you owe Glow.</li>
                  <ul style="margin:0 0 12px 0px;font-size:14px;line-height:1.6;color:#343434">
                  <li>If your online profit is greater than your brochure balance, you’ll see a negative total (example: “-$125”).</li>
                  <li>This simply means Glow will issue a check or payment for the remaining profit amount — checks are cut on the 1st and 15th of each month.</li>
                 </ul>

                 <li>Online “Ship to Group” orders will automatically be included once your brochure order is submitted.</li>
                 <ul style="margin:0 0 12px 0px;font-size:14px;line-height:1.6;color:#343434">
                  <li style="font-size: 18px;margin: 0 0 10px 0">They’ll ship together in one delivery — each order will arrive clearly labeled by seller name for easy distribution. 🎁</li>
                  <h4 style="font-size: 18px;margin: 0">📝 Helpful Tips:</h4>
                  <li>You can Save your order anytime and return once all seller forms are received.</li>
                  <li>Schools or organizations may choose “Send Check” if payment will follow upon delivery</li>
                 </ul>
          </ol>

        </tr>

      </table>
      <!-- Make It Personal! -->

      
      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->

      <!-- Don’t Forget to Share It! -->
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

        

        <tr style="margin:0 0 12px 0;">
        <td>
        <ol style="margin:0 0 12px 0px;font-size:14px;line-height:1.6;color:#343434; list-style: none;padding: 0">
                  <li><strong>8</strong> Once Glow receives your order, we’ll pull and pack every item by seller — so there’s no sorting for you! 🙌<br>
                  Your order will ship via UPS within 5 business days.</li>
                  <li><strong>9</strong> When your shipment arrives, distribute the individually packed orders to your sellers along with their original brochures (you’ve kept those safe, right? 😉).<Br>
                  This ensures each student knows exactly which items to hand to their customers.</li>
                  <li><strong>10</strong> Once everything has been handed out, ask your sellers to check for any broken or incorrect items.<br>
                  If any issues arise, remind them to submit a claim within 10 days of receiving the order using the Claims Area in their Fundraiser Dashboard.</li>
          </ol>
          </td>
        </tr>
        </table>
      <!-- Don’t Forget to Share It! -->
        
        <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->

      <!-- Don’t Forget to Share It! -->
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;font-weight:700"><Span>💫</Span> That’s a Wrap — and We’d Love Your Feedback!</h1>
          </td>
        </tr>

        <tr style="margin:20px 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 0px 0 15px 0">And voilà — your fundraiser is complete! 🎉<br>
         We’re always looking for ways to make your Glow experience even brighter. Please take a moment to leave us a star rating and share your feedback — your input helps us keep improving for amazing partners like you. 🌙💛</strong></p>
          </td>
        </tr>
        
        </table>
      <!-- Don’t Forget to Share It! -->
      

      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->


       <!-- Help You Glow -->
      <table class="footer" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;font-weight:700">Thank you for choosing Glow Scented Candles!</h1>
          </td>
        </tr>

        <tr style="margin:20px 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 0px 0 15px 0">Your dedication helps your group shine brighter than ever — and we’re honored to be part of your success story. ✨</p>
          </td>
        </tr>

        <tr style="margin:20px 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 15px 0 0 0">Warmly,<br>
         <strong>The Glow Team 🕯️💛</strong></p>
          </td>
        </tr>


        </table>
      <!-- Help You Glow -->
      

      </td>
    </tr>
  </table>
</center>

</body>
</html>

    <?php 
    $body= ob_get_clean();
    $m_body=stripslashes($body);
    $headers = array('Content-Type: text/html; charset=UTF-8','From: ' . GLOW_FROM_EMAIL);
        if(!empty($multiple_recipients))
        {
            if (wp_mail($multiple_recipients, $subject, $m_body, $headers)) 
            {
              error_log("email has been successfully sent to user whose email is " . $multiple_recipients);
                update_user_meta($u_id,"before_fivedays_end_compaign_email_meta_glow", "yes");
                  /*echo json_encode("Two Days Start email");*/
            }
            else
            {
              error_log("email failed to sent to user whose email is " . $multiple_recipients);
            }
        }
}
add_action( 'fundraiser_email_scheduled_cron', 'get_fundraiser_emails_before_oneday_campaign_start_date' );
add_action( 'fundraiser_email_scheduled_cron', 'get_fundraiser_emails_before_fivedays_campaign_start_date' );
add_action( 'fundraiser_email_scheduled_cron', 'get_fundraiser_emails_after_fivetdays_campaign_start_date' );
add_action( 'fundraiser_email_scheduled_cron', 'get_fundraiser_emails_before_fivedays_campaign_end_date' );

/* Start code */
function send_email_after_few_mintues($user_id,$user_full_name)
{
    $u_id=$user_id;
    /*$f=get_user_meta( $u_id , 'first_name' , true );      
    $l=get_user_meta( $u_id , 'last_name' , true ); */
    $user = get_userdata($user_id);
    $user_email = $user->user_email;
    $fund_name=$user_full_name;
    $recipient2=$user_email;
    $multiple_recipients = array(
                $recipient2
            );
    $subject="Welcome to the Glow Fundraiser Family! Lets Light Up Your Success! 🕯️";
     $body ='';
    ob_start();
?>

<!-- mail 1-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Glow Scented Candles — Fundraiser Dashboard</title>
</head>
<body style="margin:0;padding:0;background-color:#ffffff;font-family:Helvetica, Arial, sans-serif;color:#343434;">


<center style="width:100%;background-color:#ffffff">
  <table style="max-width: 700px; width: 100%;" border="0" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
      <td>
        
        <!-- welcome table -->
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:15px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;font-weight:700">Hello &amp; Welcome! 🌟</h1>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434">We’re thrilled to have you as part of the <strong>Glow Scented Candles Fundraiser Family!</strong>Whether you’re a returning partner or starting your very first sale — we’re so excited you’ve joined us. Let’s make your fundraiser shine bright! 💫</p>
          </td>
        </tr>
      </table>
       <!-- welcome table -->
     
      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->


      <!-- fundraiser dashboard -->
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;font-weight:700"><span>💻</span> Get to Know Your Fundraiser Dashboard</h1>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434">Your <strong>Fundraiser Dashboard</strong> is your all-in-one hub for everything you need to make your fundraiser a glowing success:
         </p>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
          <td>
             <p style="font-size:14px;line-height:1.6;color:#343434;margin: 0;"><span>✅</span> Sharable Online Store Link – Share with friends, family, and supporters to shop directly online.</p>
             <p style="font-size:14px;line-height:1.6;color:#343434;margin: 0;"><span>✅</span> Seller Registration Link – Have your sellers/students register to track their goals and sales.</p>
             <p style="font-size:14px;line-height:1.6;color:#343434;margin: 0;"><span>✅</span> Live Sales Reporting – Keep an eye on your progress in real time.</p>
             <p style="font-size:14px;line-height:1.6;color:#343434;margin: 0;"><span>✅</span> Brochure Order Entry – Easily submit brochure orders once they’re collected.</p>
            <p style="font-size:14px;line-height:1.6;color:#343434;margin: 0;"><span>✅</span> Total Sales Reports – Instantly see how your group is performing.</p>
            <p style="font-size:14px;line-height:1.6;color:#343434;margin: 0;"><span>✅</span> Order Tracking – Follow every order from start to finish.</p>
            <p style="font-size:14px;line-height:1.6;color:#343434;margin: 0;"><span>✅</span> Claims Center – Report and resolve any missing or damaged items quickly.</p>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
        <p style="font-size:15px;line-height:1.6;color:#343434"><strong>Accessing Your Dashboard:</strong><br> Log in using the email address and password provided in your <strong>booking confirmation email.</strong>
         </p>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
          <a href="https://glowbrandscandles.com/my-account/" target="_blank" style="color: blue; font-weight:700;font-size:15px;">👉 Click Here to Access Your Dashboard</a>
        </td>
        </tr>
       </table>
       <!-- fundraiser dashboard -->

      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->

      <!-- material on the way -->
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;font-weight:700"><span>📦</span> Materials on the Way</h1>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434">If your fundraiser includes <strong>brochure + online sales:</strong><br> <span>🚚</span> Your brochures and kickoff materials would ship within 3 business days.If you registered early, expect your materials about 2 weeks before your start date.</p>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434">If your fundraiser is <strong>online-only:</strong><br><span>🌐</span> No printed materials will be shipped.<br>
        <span>📄</span> Download your Participant Letter, make any edits you need, and then share or print for your group.</p>
          </td>
        </tr>
      </table>
      <!-- material on the way -->

      
      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->


      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;font-weight:700"><Span>🎁</Span> Seller Incentive Idea!</h1>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434"><span>✨</span> <strong>Motivate your sellers and boost participation!</strong><br> Try offering a <strong>fun incentive</strong> like:</p>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
           <ul style="margin:0 0 12px 0px;font-size:14px;line-height:1.6;color:#343434">
                  <li>A prize for selling 5, 8, or 10 items 🎉</li>
                  <li>A pizza or ice cream party for the group if your goal is met 🍕🍦</li>
                  <li>A prize for the seller with the most online sales 🕯️</li>
                </ul>
          </td>
        </tr>


        <tr style="margin:0 0 0px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 0;">Even a little friendly competition can make a big difference — and keep everyone excited throughout your sale! 💪🎄</p>
          </td>
        </tr>
        </table>


      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->
      

      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

        <tr>
          <td>
          <h1 style="margin:0 0 10px 0;font-size:20px;line-height:1.2;color:#1f1f1f;font-weight:700"><Span>💌</Span> Stay Connected</h1>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 0px 0px 10px 0px">Throughout your sale, you’ll receive helpful updates and tips — so be sure to keep an eye on your inbox! 📬</p>
          </td>
        </tr>

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 0">And of course, if you ever have questions, your <strong>Glow representative</strong> is here to help every step of the way.</p>
          </td>
        </tr>
        </table>
      

      <!-- hr divider -->
      <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:0 15px;">
        <tr><td style="border-top:4px double #edeae9; line-height:0; font-size:0;">&nbsp;</td></tr>
      </table>
      <!-- hr divider -->
      
      <table class="footer" width="100%" cellpadding="0" cellspacing="0" role="presentation" border="0" style="width:100%;background:#ffffff;padding:25px 15px;">

        <tr style="margin:0 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 0">We’re excited to help your group reach its goals and shine bright together! ✨</p>
          </td>
        </tr>

        <tr style="margin:20px 0 12px 0;">
        <td>
         <p style="font-size:15px;line-height:1.6;color:#343434; margin: 20px 0 0 0">Warm wishes,<br><strong>The Glow Team 🕯️💛</strong></p>
          </td>
        </tr>
        </table>

      </td>
    </tr>
  </table>
</center>

</body>
</html>
<?php 
$body= ob_get_clean();
    $m_body=stripslashes($body);
    $headers = array('Content-Type: text/html; charset=UTF-8','From: ' . GLOW_FROM_EMAIL);
    /*if (wp_mail($user_email, $subject, $m_body, $headers)) {
      error_log("email has been successfully sent to user whose email is " . $user_email);
    }
    else
    {
      error_log("email failed to sent to user whose email is " . $user_email);
    }*/
    if(!empty($multiple_recipients))
    {
        if (wp_mail($multiple_recipients, $subject, $m_body, $headers)) 
        {
          error_log("email has been successfully sent to user whose email is " . $multiple_recipients);
            update_user_meta($u_id,"you_are_booked_meta_glow", "yes");
        }
        else
        {
          error_log("email failed to sent to user whose email is " . $multiple_recipients);
        }
    }
}

/* End Code*/
?>


